<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Row;
use Quid\Site;
use Quid\Core;
use Quid\Orm;
use Quid\Main;

// formSubmit
// class to deal with a row of the formSubmit table
class FormSubmit extends Core\RowAlias
{
    // config
    public static $config = [
        'cols'=>[
            'user_id'=>['general'=>true,'onExport'=>[Core\Row\User::class,'userExport']],
            'json'=>[
                'class'=>Site\Col\JsonFormRelation::class,
                'relationCols'=>['form_id','json_fr']]]
    ];


    // cacheStatic
    protected static $cacheStatic = [];


    // getSubmit
    // retourne la dernière réponse d'un utilisateur au formulaire
    public static function getSubmit(Form $form,Main\Contract\User $user):?self
    {
        return static::cacheStatic([__METHOD__,$form,$user],function() use($form,$user) {
            $table = static::tableFromFqcn();
            $where = ['form_id'=>$form,'user_id'=>$user];
            return $table->select($where);
        });
    }


    // hasSubmitted
    // retourne vrai si l'utilisateur a déjà soumis le formulaire
    public static function hasSubmitted(Form $form,Main\Contract\User $user):bool
    {
        $return = false;
        $row = static::getSubmit($form,$user);

        if(!empty($row))
        $return = true;

        return $return;
    }


    // submit
    // soumet le formulaire, réponse dans un tableau en premier argument
    public static function submit(array $values,Form $form,Main\Contract\User $user,?array $option=null):bool
    {
        $return = false;
        $table = static::tableFromFqcn();
        $set = ['json'=>$values,'form_id'=>$form,'user_id'=>$user];

        $row = $table->insert($set,$option);

        if(!empty($row))
        $return = true;

        return $return;
    }


    // commitFinalValidate
    // gère la validation finale pour form
    // retourne une erreur s'il y a déjà une réponse de l'utilisateur pour le formulaire
    public static function commitFinalValidate(array $set,?Orm\Row $row,array $option)
    {
        $return = null;
        $form = $set['form_id'] ?? null;
        $user = $set['user_id'] ?? null;

        if(!empty($form) && !empty($user))
        {
            $form = Form::row($form);

            if(!empty($form) && !$form->allowMultiple())
            {
                $table = static::tableFromFqcn();
                $where = ['form_id'=>$form,'user_id'=>$user];

                if(!empty($row))
                $where[] = [$table->primary(),'!=',$row->primary()];

                $id = $table->selectPrimary($where);

                if(!empty($id))
                $return = 'formSubmit/duplicate';
            }
        }

        return $return;
    }
}

// config
FormSubmit::__config();
?>