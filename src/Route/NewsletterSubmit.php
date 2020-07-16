<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Route;
use Quid\Base;
use Quid\Base\Html;
use Quid\Core;
use Quid\Lemur;
use Quid\Site;

// newsletterSubmit
// abstract class for a newsletter submit route
abstract class NewsletterSubmit extends Core\RouteAlias
{
    // trait
    use Lemur\Route\_formSubmit;


    // config
    protected static array $config = [
        'path'=>[
            'en'=>'newsletter/subscribe',
            'fr'=>'infolettre/enregistrement'],
        'match'=>[
            'method'=>'post',
            'post'=>['email','firstName','lastName'],
            'timeout'=>true,
            'genuine'=>true,
            'csrf'=>false],
        'timeout'=>[
            'trigger'=>['max'=>4,'timeout'=>600]],
        'group'=>'submit',
        'service'=>'newsletter'
    ];


    // dynamique
    protected bool $duplicate = false; // détermine si la raison de l'échec est un duplicata


    // onSuccess
    // traite le succès
    final protected function onSuccess():void
    {
        $com = static::sessionCom();
        static::timeoutReset('trigger');
        $com->pos('newsletter/subscribe/success');
    }


    // onFailure
    // traite l'erreur
    final protected function onFailure():void
    {
        $com = static::sessionCom();

        if($this->duplicate === true)
        $com->neg('newsletter/subscribe/duplicate');
        else
        $com->neg('newsletter/subscribe/failure');
    }


    // setFlash
    // conserve les données flash, seulement si duplicate est false
    final protected function setFlash():void
    {
        if($this->duplicate === false)
        $this->session()->flashPost($this);
    }


    // routeSuccess
    // redirige vers la dernière route valable de l'historique
    final public function routeSuccess()
    {
        return true;
    }


    // getService
    // retourne le service à utiliser pour l'enregistrement à l'infolettre
    final public function getService():Site\Contract\Newsletter
    {
        return $this->service($this->getAttr('service'));
    }


    // post
    // retourne les données post pour l'enregistrement à l'infolettre
    final protected function post():array
    {
        $return = [];
        $request = $this->request();

        foreach ($this->getFields() as $value)
        {
            if(is_string($value))
            $return[$value] = (string) $request->get($value);
        }

        return $return;
    }


    // proceed
    // lance l'opération d'enregistrement
    final protected function proceed():bool
    {
        $return = false;
        $service = $this->getService();
        $post = $this->post();
        $post = $this->onBeforeCommit($post);

        if($post !== null)
        {
            $email = static::email($post);
            $vars = static::vars($post);

            if($service->isSubscribed($email))
            $this->duplicate = true;

            else
            $return = $service->subscribeBool($email,$vars,null,false);
        }

        if(empty($return))
        $this->failureComplete();

        else
        $this->successComplete();

        return $return;
    }


    // getFields
    // retourne les champs pour le formulaire
    final public function getFields():array
    {
        return $this->getAttr(['match','post']) ?? [];
    }


    // isFieldRequired
    // retourne vrai si le champ est requis
    protected function isFieldRequired(string $key):bool
    {
        return true;
    }


    // getFieldsInfo
    // retourne les informations détaillés des champs pour le formulaire
    final public function getFieldsInfo():array
    {
        $return = [];

        foreach ($this->getFields() as $key)
        {
            $array = [];
            $array['method'] = 'inputText';
            $array['attr']['name'] = $key;
            $array['attr']['placeholder'] = static::langText(['newsletter',$key]);
            $array['attr']['data-required'] = $this->isFieldRequired($key);

            if($key === 'email')
            {
                $array['method'] = 'inputEmail';
                $array['attr']['data-pattern'] = Base\Validate::pattern('email');
            }

            $return[$key] = $array;
        }

        return $return;
    }


    // makeForm
    // génère le formulaire pour l'inscription à l'infolettre
    final public function makeForm(?array $flash=null):string
    {
        $r = '';

        foreach ($this->getFieldsInfo() as $key => $array)
        {
            $value = (is_array($flash) && array_key_exists($key,$flash))? $flash[$key]:null;
            $method = $array['method'];

            $r .= Html::divOp('field');
            $r .= Html::$method($value,$array['attr']);
            $r .= Html::divCl();
        }

        return $r;
    }


    // email
    // retourne le email à partir d'un tableau post
    final protected static function email(array $post):string
    {
        return $post['email'] ?? null;
    }


    // vars
    // retourne les variables vars pour l'enregistrement à l'infolettre à partir d'un tableau post
    final protected static function vars(array $post):array
    {
        return Base\Arr::keyStrip('email',$post);
    }
}

// init
NewsletterSubmit::__init();
?>