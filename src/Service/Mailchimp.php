<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Service;
use Quid\Site;
use Quid\Core;
use Quid\Base;

// mailchimp
// class that provides some methods to communication with mailchimp (subscribe to a list)
class Mailchimp extends Core\ServiceRequestAlias implements Site\Contract\Newsletter
{
    // config
    public static $config = [
        'option'=>[
            'ping'=>2, // s'il y a un ping avant la requête
            'responseCode'=>[200,500], // le code de réponse peut être 200 ou 500
            'key'=>null, // apiKey pour mailchimp
            'list'=>null, // code de la liste
            'addLang'=>true, // si lang est ajouté au merge vars
            'subscribed'=>['pending','subscribed'], // status considéré comme subscribed
            'mergeVars'=>['firstName'=>'FNAME','lastName'=>'LNAME']], // remplacement pour les mergeVars
        'target'=>'https://%server%.api.mailchimp.com/2.0/%method%.json' // uri target pour mailchimp
    ];


    // apiKey
    // retourne la clé d'api
    public function apiKey():string
    {
        return $this->getOption('key');
    }


    // getList
    // retourne la liste ou null
    public function getList():?string
    {
        return $this->getOption('list');
    }


    // checkList
    // retourne la liste ou envoie une exception
    public function checkList():string
    {
        $return = $this->getList();

        if(empty($return))
        static::throw();

        return $return;
    }


    // setList
    // change la liste courante
    public function setList(?string $value):void
    {
        $this->setOption($value);

        return;
    }


    // server
    // obtient le serveur à partir de l'apiKey
    public function server():string
    {
        $return = null;
        $apiKey = $this->apiKey();
        $x = explode('-',$apiKey);

        if(is_array($x) && count($x) === 2)
        $return = $x[1];

        return $return;
    }


    // makeTarget
    // retourne la target du service mailchimp pour la méthode
    public function makeTarget(string $method):string
    {
        $replace = [];
        $replace['server'] = $this->server();
        $replace['method'] = $method;
        $return = static::target($replace);

        return $return;
    }


    // subscribedStatus
    // retourne les noms de status pour subscribed
    public function subscribedStatus():array
    {
        return $this->getOption('subscribed');
    }


    // trigger
    // fait un appel à mailchimp, retourne un objet réponse
    public function trigger(string $method,?array $post=null,?array $option=null):Core\Response
    {
        $return = null;
        $value = [];
        $option = Base\Arr::plus($this->option(),$option);
        $post = (array) $post;
        $post['apikey'] = $this->apiKey();

        $value['uri'] = $this->makeTarget($method);
        $value['method'] = 'post';
        $value['post'] = $post;

        $request = $this->makeRequest($value,$option);
        $return = $request->trigger();

        return $return;
    }


    // triggerBody
    // retourne le body de la réponse en tableau
    // retourne même si le code n'est pas 200
    public function triggerBody(string $method,?array $post=null,?array $option=null):?array
    {
        $return = null;
        $response = $this->trigger($method,$post,$option);
        $return = $response->body(true);

        return $return;
    }


    // triggerBody200
    // retourne le body de la réponse en tableau
    // retourne seulement si le code est 200
    public function triggerBody200(string $method,?array $post=null,?array $option=null):?array
    {
        $return = null;
        $response = $this->trigger($method,$post,$option);

        if($response->is200())
        $return = $response->body(true);

        return $return;
    }


    // triggerData
    // retourne le contenu de data dans le tableau de la réponse en tableau
    // retorne null si pas de data
    public function triggerData(string $method,?array $post=null,?array $option=null):?array
    {
        $return = null;
        $body = $this->triggerBody200($method,$post,$option);

        if(is_array($body) && array_key_exists('data',$body))
        $return = $body['data'];

        return $return;
    }


    // triggerDataFirst
    // retourne le contenu de la première clé de data dans le tableau de la réponse en tableau
    // retorne null si pas de data
    public function triggerDataFirst(string $method,?array $post=null,?array $option=null):?array
    {
        $return = null;
        $data = $this->triggerData($method,$post,$option);

        if(is_array($data) && !empty($data))
        $return = current($data);

        return $return;
    }


    // listsInfo
    // retourne les informations sur les lists dans mailchimp
    public function listsInfo():?array
    {
        return $this->triggerData('lists/list');
    }


    // memberInfo
    // retourne l'information sur un utisateur dans la liste à partir d'un email
    public function memberInfo(string $email,?array $post=null):?array
    {
        $return = null;
        $list = $this->checkList();

        if(Base\Validate::isEmail($email))
        {
            $post = (array) $post;
            $post['id'] = $list;
            $post['emails'] = [['email'=>$email]];

            $return = $this->triggerDataFirst('lists/member-info',$post);
        }

        return $return;
    }


    // isSubscribed
    // retourne vrai si le membre existe dans la liste
    public function isSubscribed(string $email,?array $post=null):bool
    {
        $return = false;
        $member = $this->memberInfo($email,$post);

        if(!empty($member) && !empty($member['status']))
        {
            if(in_array($member['status'],$this->subscribedStatus(),true))
            $return = true;
        }

        return $return;
    }


    // members
    // retourne tous les membres inscrits dans la liste
    public function members(?array $post=null):array
    {
        $return = [];
        $list = $this->checkList();
        $subscribed = $this->subscribedStatus();
        $post = (array) $post;

        $post['id'] = $list;
        $limit = 100;
        $start = 0;

        while (true)
        {
            $post['opts'] = ['start'=>$start,'limit'=>$limit];
            $data = $this->triggerData('lists/members',$post);

            if(!empty($data))
            {
                foreach ($data as $member)
                {
                    if(!empty($member['email']) && Base\Validate::isEmail($member['email']))
                    {
                        if(!empty($member['status']) && in_array($member['status'],$subscribed,true))
                        {
                            $email = $member['email'];
                            $name = '';

                            if(!empty($member['merges']))
                            $name = $this->makeNameFromMergeVars($member['merges']);

                            $return[$email] = $name;
                        }
                    }
                }
            }

            else
            break;

            $start += 1;
        }

        return $return;
    }


    // subscribe
    // inscrit un utilisateur à une liste mailchimp
    public function subscribe(string $email,$vars=[],?array $post=null,bool $isSubscribed=true):?array
    {
        $return = null;
        $list = $this->checkList();

        if(Base\Validate::isEmail($email))
        {
            if($isSubscribed === false || !$this->isSubscribed($email))
            {
                $post = (array) $post;
                $post['id'] = $list;
                $post['email'] = ['email'=>$email];
                $post['merge_vars'] = [];

                if($this->getOption('addLang') === true)
                {
                    $lang = static::getLangCode();
                    if(is_string($lang))
                    $vars['MC_LANGUAGE'] = $lang;
                }

                if(!empty($vars) && is_array($vars))
                $post['merge_vars'] = $this->prepareMergeVars($vars);

                $return = $this->triggerBody('lists/subscribe',$post);
            }
        }

        return $return;
    }


    // subscribeBool
    // inscrit un utilisateur à une liste mailchimp et retourne un vrai ou faux
    public function subscribeBool(string $email,$vars=[],?array $post=null,bool $isSubscribed=true):bool
    {
        $return = false;
        $subscribe = $this->subscribe($email,$vars,$post,$isSubscribed);

        if(!empty($subscribe))
        $return = true;

        return $return;
    }


    // unsubscribe
    // désinscrit un utilisateur à une liste mailchimp
    public function unsubscribe(string $email,?array $post=null):?array
    {
        $return = null;
        $list = $this->checkList();

        if(Base\Validate::isEmail($email) && $this->isSubscribed($email))
        {
            $post = (array) $post;
            $post['id'] = $list;
            $post['email'] = ['email'=>$email];
            $post['delete_member'] = true;

            $return = $this->triggerBody('lists/unsubscribe',$post);
        }

        return $return;
    }


    // prepareMergeVars
    // prepare le tableau mergeVars, remplace les clés
    public function prepareMergeVars(array $array):array
    {
        return Base\Arr::keysChange($this->getOption('mergeVars'),$array);
    }


    // makeNameFromMergeVars
    // génère un nom complet à partir de tableaux de mergeVars
    public function makeNameFromMergeVars(array $value):string
    {
        $return = '';
        $mergeVars = $this->getOption('mergeVars');

        if(!empty($mergeVars['firstName']) && array_key_exists($mergeVars['firstName'],$value))
        $return .= $value[$mergeVars['firstName']];

        if(!empty($mergeVars['lastName']) && array_key_exists($mergeVars['lastName'],$value))
        {
            $return .= (strlen($return))? ' ':'';
            $return .= $value[$mergeVars['lastName']];
        }

        return $return;
    }
}

// config
Mailchimp::__config();
?>