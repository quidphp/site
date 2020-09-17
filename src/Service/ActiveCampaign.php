<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Service;
use Quid\Base;
use Quid\Core;
use Quid\Main;
use Quid\Site;

// activeCampaign
// class that provides some methods to communicate with ActiveCampaign
class ActiveCampaign extends Main\ServiceRequest implements Site\Contract\Newsletter
{
    // access
    use Core\_bootAccess;


    // config
    protected static array $config = [
        'postJson'=>true,
        'target'=>'https://%account%.api-us1.com/api/3/%method%', // uri target pour activeCampaign
        'account'=>null, // contient le account pour activeCampaign
        'ping'=>2, // s'il y a un ping avant la requête
        'responseCode'=>[200,201,422,500],
        'key'=>null // apiKey pour activeCampaign
    ];


    // apiKey
    // retourne la clé d'api
    final public function apiKey():string
    {
        return $this->getAttr('key');
    }


    // getAccount
    // retourne le nom de compte
    final public function getAccount():string
    {
        return $this->getAttr('account');
    }


    // trigger
    // fait un appel à activeCampaign, retourne un objet réponse
    final public function trigger(string $httpMethod,string $method,?array $data=null,?array $attr=null):Main\Response
    {
        $return = null;

        $value = [];
        $data = (array) $data;
        $attr = Base\Arr::plus($this->attr(),$attr);

        $headers = [];
        $headers['Api-Token'] = $this->apiKey();

        $replace = [];
        $replace['account'] = $this->getAccount();
        $replace['method'] = $method;
        $value['uri'] = static::target($replace);
        $value['method'] = $httpMethod;

        if($httpMethod === 'post')
        $value['post'] = $data;

        else
        $value['query'] = $data;

        $value['headers'] = $headers;

        $request = $this->makeRequest($value,$attr);
        $return = $request->trigger();

        return $return;
    }


    // triggerBody
    // retourne le body de la réponse en tableau
    // retourne même si le code n'est pas 200
    final public function triggerBody(string $httpMethod,string $method,?array $data=null,?array $attr=null):?array
    {
        $response = $this->trigger($httpMethod,$method,$data,$attr);
        return $response->body(true);
    }


    // triggerBody200
    // retourne le body de la réponse en tableau
    // retourne seulement si le code est 200
    final public function triggerBody200(string $httpMethod,string $method,?array $data=null,?array $attr=null):?array
    {
        $return = null;
        $response = $this->trigger($httpMethod,$method,$data,$attr);

        if($response->is200())
        $return = $response->body(true);

        return $return;
    }


    // memberInfo
    // retourne l'information sur un utisateur dans à partir d'un email
    final public function memberInfo(string $email,?array $get=null):?array
    {
        $return = null;

        if(Base\Validate::isEmail($email))
        {
            $get = (array) $get;
            $get['email'] = $email;
            $result = $this->triggerBody200('get','contacts',$get);

            $return = $result['contacts'][0] ?? null;
        }

        return $return;
    }


    // memberId
    // retourne le id du membre si existant
    final public function memberId(string $email,?array $get=null):?string
    {
        $info = $this->memberInfo($email);
        return $info['id'] ?? null;
    }


    // isSubscribed
    // retourne vrai si le membre existe
    final public function isSubscribed(string $email,?array $get=null):bool
    {
        return !empty($this->memberInfo($email,$get));
    }


    // members
    // retourne tous les membres inscrits
    final public function members(?array $get=null):array
    {
        return $this->triggerBody200('get','contacts',$get) ?? [];
    }


    // subscribe
    // inscrit un utilisateur à activeCampaign
    final public function subscribe(string $email,array $vars=[],?array $post=null,?int $list=null):?array
    {
        $return = null;

        if(Base\Validate::isEmail($email))
        {
            $post = (array) $post;
            $contact = ['email'=>$email];
            $contact = Base\Arr::replace($vars,$contact);
            $post['contact'] = $contact;
            $return = $this->triggerBody('post','contacts',$post);

            if(is_int($list) && !empty($return['contact']['id']))
            $this->subscribeToList($return['contact']['id'],$list);
        }

        return $return;
    }


    // subscribeToList
    // permet d'inscrire un utilisateur à une liste
    final public function subscribeToList($value,int $list,int $status=1):?array
    {
        $return = null;

        if(Base\Validate::isEmail($value))
        $value = $this->memberId($value);

        if(is_numeric($value))
        {
            $contactList = ['list'=>$list,'status'=>$status,'contact'=>$value];
            $return = $this->triggerBody('post','contactLists',['contactList'=>$contactList]);
        }

        return $return;
    }


    // subscribeBool
    // inscrit un utilisateur à activeCampaign et retourne un vrai ou faux
    final public function subscribeBool(string $email,array $vars=[],?array $post=null,?int $list=null):bool
    {
        $subscribe = $this->subscribe($email,$vars,$post,$list);
        return !empty($subscribe['contact']['id']);
    }


    // unsubscribe
    // désinscrit un utilisateur à activeCampaign
    final public function unsubscribe(string $email):?array
    {
        $return = null;

        if(Base\Validate::isEmail($email))
        {
            $id = $this->memberId($email);

            if(!empty($id))
            $return = $this->triggerBody('delete','contacts/'.$id);
        }

        return $return;
    }
}

// init
ActiveCampaign::__init();
?>