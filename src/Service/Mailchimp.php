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

// mailchimp
// class that provides some methods to communicate with Mailchimp using api 3
class Mailchimp extends Main\ServiceRequest implements Site\Contract\Newsletter
{
    // access
    use Core\_bootAccess;


    // config
    protected static array $config = [
        'target'=>'https://%server%.api.mailchimp.com/3.0/', // uri target pour mailchimp
        'postJson'=>true, // convertie le post en json
        'ping'=>2, // s'il y a un ping avant la requête
        'responseCode'=>[200,204,400,404], // différent code de réponse possible
        'key'=>null, // apiKey pour mailchimp
        'list'=>null, // code de la liste
        'addLang'=>true, // si lang est ajouté au merge vars
        'subscribed'=>['pending','subscribed'], // status considéré comme subscribed
        'subscribedConfirmed'=>['subscribed'], // status considéré comme subscribed et confirmer
        'mergeVars'=>['firstName'=>'FNAME','lastName'=>'LNAME'] // remplacement pour les mergeVars
    ];


    // apiKey
    // retourne la clé d'api
    final public function apiKey():string
    {
        return $this->getAttr('key');
    }


    // getList
    // retourne la liste
    final public function getList():string
    {
        return $this->getAttr('list');
    }


    // setList
    // change la liste courante
    final public function setList(string $value):void
    {
        $this->setAttr('list',$value);
    }


    // server
    // obtient le serveur à partir de l'apiKey
    final public function server():string
    {
        $return = null;
        $apiKey = $this->apiKey();
        $x = explode('-',$apiKey);

        if(is_array($x) && count($x) === 2)
        $return = $x[1];

        return $return;
    }


    // prepareUri
    // permet de préparer l'uri en vue de l'appel à mailchimp
    final protected function prepareUri(string $path):string
    {
        $replace = ['server'=>$this->server(),'list'=>$this->getList()];
        $target = static::target($replace);
        $replace = Base\Arr::keysWrap('%','%',$replace);
        $path = Base\Str::replace($replace,$path);

        return $target.$path;
    }


    // trigger
    // fait un appel à mailchimp, retourne un objet réponse
    final public function trigger(string $httpMethod,string $path,?array $post=null,?array $attr=null):Main\Response
    {
        $return = null;
        $attr = Base\Arr::plus($this->attr(),$attr);
        $token = $this->apiKey();

        $value = [];
        $value['uri'] = $this->prepareUri($path);
        $value['method'] = $httpMethod;
        $value['post'] = (array) $post;
        $value['headers']['Authorization'] = "Bearer $token";

        $request = $this->makeRequest($value,$attr);

        return $request->trigger();
    }


    // triggerBody
    // retourne le body de la réponse en tableau
    final public function triggerBody(string $httpMethod,string $path,?array $post=null,?array $attr=null):?array
    {
        $response = $this->trigger($httpMethod,$path,$post,$attr);
        return $response->body(true);
    }


    // isSubscribed
    // retourne vrai si le membre existe dans la liste
    final public function isSubscribed(string $email,?array $post=null,bool $confirmed=false):bool
    {
        $return = false;
        $member = $this->member($email,$post);

        if(!empty($member) && !empty($member['status']))
        {
            $key = ($confirmed === true)? 'subscribedConfirmed':'subscribed';
            $array = $this->getAttr($key) ?? static::throw();
            $return = (in_array($member['status'],$array,true));
        }

        return $return;
    }


    // account
    // retourne les informations sur le compte courant
    final public function account():?array
    {
        return $this->triggerBody('get','');
    }


    // lists
    // retourne les informations sur les lists dans mailchimp
    final public function lists():?array
    {
        return $this->triggerBody('get','lists');
    }


    // member
    // retourne l'information sur un utisateur dans la liste à partir d'un email
    final public function member(string $email,?array $post=null):?array
    {
        $return = null;

        if(Base\Validate::isEmail($email))
        {
            $hash = Base\Crypt::md5($email);
            $return = $this->triggerBody('get',"lists/%list%/members/$hash",$post);
        }

        return $return;
    }


    // subscribe
    // inscrit un utilisateur à une liste mailchimp
    // possible de changer le statut par défaut en passant un tableau post
    final public function subscribe(string $email,array $vars=[],?array $post=null,bool $checkSubscribed=true):?array
    {
        $return = null;

        if(Base\Validate::isEmail($email))
        {
            if($checkSubscribed === false || !$this->isSubscribed($email))
            {
                $post = (array) $post;
                $post['email_address'] = $email;

                if(!array_key_exists('status',$post))
                $post['status'] = 'pending';

                if($this->getAttr('addLang') === true)
                {
                    $lang = static::boot()->lang()->currentLang();
                    if(is_string($lang))
                    $post['language'] = $lang;
                }

                if(!empty($vars) && is_array($vars))
                $post['merge_fields'] = Base\Arr::keysChange($this->getAttr('mergeVars'),$vars);

                $return = $this->triggerBody('post','lists/%list%/members',$post);
            }
        }

        return $return;
    }


    // subscribeBool
    // inscrit un utilisateur à une liste mailchimp et retourne un vrai ou faux
    final public function subscribeBool(string $email,array $vars=[],?array $post=null,bool $isSubscribed=true):bool
    {
        $result = $this->subscribe($email,$vars,$post,$isSubscribed);
        return is_array($result) && !empty($result['email_address']) && $result['email_address'] === $email;
    }


    // addMemberTag
    // permet d'ajouter un tag à un membre
    // en cas de succès, le body de cette réponse sera null
    final public function addMemberTag(string $email,string $tag,?array $post=null):?array
    {
        $return = null;
        $member = $this->member($email);

        if(!empty($member))
        {
            $tagsCurrent = $member['tags'] ?? [];
            $find = Base\Arr::some($tagsCurrent,fn($value) => $value['name'] === $tag);

            if($find === false)
            {
                $hash = Base\Crypt::md5($email);
                $tagsArray = [['name'=>$tag,'status'=>'active']];
                $post = (array) $post;
                $post['tags'] = $tagsArray;
                $return = $this->triggerBody('post',"lists/%list%/members/$hash/tags",$post);
            }
        }

        return $return;
    }
}

// init
Mailchimp::__init();
?>