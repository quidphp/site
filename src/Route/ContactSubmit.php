<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Route;
use Quid\Core;

// contactSubmit
// abstract class for a contact submit route
abstract class ContactSubmit extends Core\RouteAlias
{
    // trait
    use Core\Route\_formSubmit;


    // config
    public static $config = [
        'path'=>[
            'fr'=>'nous-joindre/soumettre',
            'en'=>'contact-us/submit'],
        'match'=>[
            'method'=>'post'],
        'verify'=>[
            'post'=>['name','phone','email','message'],
            'csrf'=>true,
            'captcha'=>true,
            'genuine'=>true,
            'timeout'=>true],
        'timeout'=>[
            'failure'=>['max'=>8,'timeout'=>600],
            'success'=>['max'=>2,'timeout'=>600]],
        'row'=>null
    ];


    // onSuccess
    // traite le succès
    protected function onSuccess():void
    {
        static::sessionCom()->stripFloor();
        static::timeoutIncrement('success');

        return;
    }


    // onFailure
    // increment le timeout et appele onFailure
    protected function onFailure():void
    {
        static::sessionCom()->stripFloor();
        static::timeoutIncrement('failure');

        return;
    }


    // routeSuccess
    // retourne la route vers laquelle redirigé, home par défaut
    public function routeSuccess():Core\Route
    {
        return Home::makeOverload();
    }


    // post
    // retourne les données post pour le formulaire de contact
    protected function post():array
    {
        $return = [];
        $request = $this->request();

        foreach (static::getFields() as $value)
        {
            if(is_string($value))
            $return[$value] = (string) $request->get($value);
        }

        return $return;
    }


    // proceed
    // lance le processus pour le contact
    protected function proceed():?Core\Row
    {
        $return = null;
        $session = static::session();
        $table = static::tableFromRowClass();
        $post = $this->post();
        $post = $this->onBeforeCommit($post);

        if($post !== null)
        $return = $table->insert($post,['com'=>true]);

        if(empty($return))
        $this->failureComplete();

        else
        $this->successComplete();

        return $return;
    }


    // getFields
    // retourne les champs pour le formulaire
    public static function getFields():array
    {
        return static::$config['verify']['post'];
    }
}

// config
ContactSubmit::__config();
?>