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
// class that provides some methods to communicate with Mailchimp
class Mailchimp extends Main\ServiceRequest implements Site\Contract\Newsletter
{
    // access
    use Core\_bootAccess;


    // config
    protected static array $config = [
        'target'=>'https://%server%.api.mailchimp.com/2.0/%method%.json', // uri target pour mailchimp
        'ping'=>2, // s'il y a un ping avant la requête
        'responseCode'=>[200,500], // le code de réponse peut être 200 ou 500
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
    // retourne la liste ou null
    final public function getList():?string
    {
        return $this->getAttr('list');
    }


    // checkList
    // retourne la liste ou envoie une exception
    final public function checkList():string
    {
        return $this->getList() ?: static::throw();
    }


    // setList
    // change la liste courante
    final public function setList(?string $value):void
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


    // makeTarget
    // retourne la target du service mailchimp pour la méthode
    final public function makeTarget(string $method):string
    {
        $replace = [];
        $replace['server'] = $this->server();
        $replace['method'] = $method;
        $return = static::target($replace);

        return $return;
    }


    // subscribedStatus
    // retourne les noms de status pour subscribed
    // si confirmed est true, retourne juste les status inscrit et confirmer
    final public function subscribedStatus(bool $confirmed=false):array
    {
        return ($confirmed === true)? $this->getAttr('subscribedConfirmed'):$this->getAttr('subscribed');
    }


    // trigger
    // fait un appel à mailchimp, retourne un objet réponse
    final public function trigger(string $method,?array $post=null,?array $attr=null):Main\Response
    {
        $return = null;
        $value = [];
        $attr = Base\Arr::plus($this->attr(),$attr);
        $post = (array) $post;
        $post['apikey'] = $this->apiKey();

        $value['uri'] = $this->makeTarget($method);
        $value['method'] = 'post';
        $value['post'] = $post;

        $request = $this->makeRequest($value,$attr);
        $return = $request->trigger();

        return $return;
    }


    // triggerBody
    // retourne le body de la réponse en tableau
    // retourne même si le code n'est pas 200
    final public function triggerBody(string $method,?array $post=null,?array $attr=null):?array
    {
        $return = null;
        $response = $this->trigger($method,$post,$attr);
        $return = $response->body(true);

        return $return;
    }


    // triggerBody200
    // retourne le body de la réponse en tableau
    // retourne seulement si le code est 200
    final public function triggerBody200(string $method,?array $post=null,?array $attr=null):?array
    {
        $return = null;
        $response = $this->trigger($method,$post,$attr);

        if($response->is200())
        $return = $response->body(true);

        return $return;
    }


    // triggerData
    // retourne le contenu de data dans le tableau de la réponse en tableau
    // retorne null si pas de data
    final public function triggerData(string $method,?array $post=null,?array $attr=null):?array
    {
        $return = null;
        $body = $this->triggerBody200($method,$post,$attr);

        if(is_array($body) && array_key_exists('data',$body))
        $return = $body['data'];

        return $return;
    }


    // triggerDataFirst
    // retourne le contenu de la première clé de data dans le tableau de la réponse en tableau
    // retorne null si pas de data
    final public function triggerDataFirst(string $method,?array $post=null,?array $attr=null):?array
    {
        $return = null;
        $data = $this->triggerData($method,$post,$attr);

        if(is_array($data) && !empty($data))
        $return = current($data);

        return $return;
    }


    // listsInfo
    // retourne les informations sur les lists dans mailchimp
    final public function listsInfo():?array
    {
        return $this->triggerData('lists/list');
    }


    // memberInfo
    // retourne l'information sur un utisateur dans la liste à partir d'un email
    final public function memberInfo(string $email,?array $post=null):?array
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


    // addMemberStaticSegment
    // permet d'ajouter un static segment à un membre
    final public function addMemberStaticSegment(string $email,int $staticSegment,?array $post=null):?array
    {
        $return = null;
        $memberInfo = $this->memberInfo($email);
        $tags = $memberInfo['static_segments'] ?? [];
        $find = Base\Arr::some($tags,fn($tag) => $tag['id'] === $staticSegment);

        if($find === false)
        {
            $list = $this->checkList();
            $post = (array) $post;
            $post['id'] = $list;
            $post['seg_id'] = $staticSegment;
            $post['batch'] = [['email'=>$email]];
            $return = $this->triggerBody('lists/static-segment-members-add',$post);
        }

        return $return;
    }


    // isSubscribed
    // retourne vrai si le membre existe dans la liste
    final public function isSubscribed(string $email,?array $post=null,bool $confirmed=false):bool
    {
        $return = false;
        $member = $this->memberInfo($email,$post);

        if(!empty($member) && !empty($member['status']))
        $return = (in_array($member['status'],$this->subscribedStatus($confirmed),true));

        return $return;
    }


    // members
    // retourne tous les membres inscrits dans la liste
    final public function members(?array $post=null):array
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
    final public function subscribe(string $email,array $vars=[],?array $post=null,bool $isSubscribed=true):?array
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

                if($this->getAttr('addLang') === true)
                {
                    $lang = static::boot()->lang()->currentLang();
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
    final public function subscribeBool(string $email,array $vars=[],?array $post=null,bool $isSubscribed=true):bool
    {
        return !empty($this->subscribe($email,$vars,$post,$isSubscribed));
    }


    // unsubscribe
    // désinscrit un utilisateur à une liste mailchimp
    final public function unsubscribe(string $email,?array $post=null):?array
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
    final public function prepareMergeVars(array $array):array
    {
        return Base\Arr::keysChange($this->getAttr('mergeVars'),$array);
    }


    // makeNameFromMergeVars
    // génère un nom complet à partir de tableaux de mergeVars
    final public function makeNameFromMergeVars(array $value):string
    {
        $return = '';
        $mergeVars = $this->getAttr('mergeVars');

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

// init
Mailchimp::__init();
?>