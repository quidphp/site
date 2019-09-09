<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Row;
use Quid\Site;
use Quid\Lemur;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// form
// class to deal with a row of the form table
abstract class Form extends Core\RowAlias implements Main\Contract\Meta
{
    // trait
    use _meta;


    // config
    public static $config = [
        'key'=>['slug_[lang]',0],
        'cols'=>[
            'multiple'=>['class'=>Core\Col\Yes::class],
            'json_fr'=>['class'=>Site\Col\JsonForm::class,'exists'=>false],
            'json_en'=>['class'=>Site\Col\JsonForm::class,'exists'=>false]],
        '@cms'=>[
            'route'=>[
                'export'=>Lemur\Cms\GeneralExportDialog::class],
            'specificOperation'=>[self::class,'specificOperation']]
    ];


    // submitClass
    // retourne la classe de row pour soumettre un formulaire
    abstract protected static function submitClass():string;


    // isVisible
    // retourne vrai si le formulaire est visible
    public function isVisible():bool
    {
        return (parent::isVisible() && $this['datetimeStart']->isAfter() && $this['datetimeEnd']->isBefore(true))? true:false;
    }


    // allowMultiple
    // retourne vrai si le formulaire peut être soumis plusieurs fois par un même utilisateur
    public function allowMultiple():bool
    {
        return ($this['multiple']->isNotEmpty())? true:false;
    }


    // formInfo
    // retourne le tableau des informations sur le formulaire
    // peut retourner null
    public function formInfo():?array
    {
        return $this['json_[lang]']->get();
    }


    // makeForm
    // génère le formulaire
    public function makeForm(?array $values=null):array
    {
        return $this['json_[lang]']->makeForm($values);
    }


    // questions
    // retourne les questions du formulaire
    public function questions():array
    {
        return $this['json_[lang]']->questions();
    }


    // hasRequired
    // retourne vrai si un des champs est requis
    public function hasRequired():bool
    {
        return $this['json_[lang]']->hasRequired();
    }


    // areAnswersValid
    // retourne vrai si les réponses sont valides pour le formulaire
    public function areAnswersValid(array $values):bool
    {
        $return = false;
        $formInfo = $this->formInfo();

        if(is_array($formInfo) && !empty($formInfo) && array_keys($formInfo) === array_keys($values))
        {
            foreach ($formInfo as $k => $v)
            {
                $valid = (empty($v['required']) || !Base\Validate::isReallyEmpty($values[$k]))? true:false;

                if(Base\Html::isRelationTag($v['type']) && is_array($v['choices']) && $values[$k] !== null)
                {
                    if(is_scalar($values[$k]))
                    $values[$k] = Base\Set::arr($values[$k]);

                    $valid = (is_array($values[$k]))? Base\Arr::keysExists($values[$k],$v['choices']):false;
                }

                if($valid === false)
                break;
            }

            $return = $valid;
        }

        return $return;
    }


    // hasSubmitted
    // retourne vrai si l'utilisateur a soumis le formulaire au moins une fois
    public function hasSubmitted(Main\Contract\User $user):bool
    {
        return static::submitClass()::hasSubmitted($this,$user);
    }


    // getSubmit
    // retourne la dernière réponse d'un utilisateur au formulaire
    public function getSubmit(Main\Contract\User $user):?FormSubmit
    {
        return static::submitClass()::getSubmit($this,$user);
    }


    // submit
    // soumet une réponse au formulaire
    public function submit(array $values,Main\Contract\User $user,?array $option=null):bool
    {
        return static::submitClass()::submit($values,$this,$user,$option);
    }


    // specificOperation
    // dans le cms, permet l'exportation des réponses en CSV
    public static function specificOperation(self $row):string
    {
        $r = '';

        if($row->table()->hasPermission('specificOperation'))
        {
            $export = $row->routeClass('export');
            $table = FormSubmit::tableFromFqcn();

            if(!empty($export) && $row->isUpdateable() && $table->hasPermission('export') && $row->hasRelationChilds($table))
            {
                $segment = ['table'=>$table,'order'=>'id','direction'=>'desc','filter'=>['form_id'=>$row]];
                $export = $export::makeOverload($segment)->initSegment();
                $r .= $export->aDialog();
            }
        }

        return $r;
    }
}

// config
Form::__config();
?>