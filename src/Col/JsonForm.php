<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Col;
use Quid\Base;
use Quid\Core;
use Quid\Lemur;
use Quid\Site;

// jsonForm
// class for a column containing a json form
class JsonForm extends Lemur\Col\JsonArrayModelAlias
{
    // config
    protected static array $config = [
        'complex'=>'json-form',
        'cell'=>Site\Cell\JsonForm::class,
        'formFields'=>['label','description','required','minLength','maxLength','choices'],
        'typesGroup'=>[
            'all'=>['inputText','textarea','select','radio','checkbox','inputFile','separator']],
        'fieldsType'=>[
            'label'=>['inputText','textarea','select','radio','checkbox','inputFile','separator'],
            'description'=>['inputText','textarea','select','radio','checkbox','inputFile','separator'],
            'required'=>['inputText','textarea','select','radio','checkbox','inputFile'],
            'minLength'=>['textarea'],
            'maxLength'=>['textarea'],
            'choices'=>['select','radio','checkbox']]
    ];


    // onModelPrepare
    // arrange le tableau pour les méthode onGet et onSet
    final protected function onModelPrepare(array $value):?array
    {
        $return = [];

        foreach ($value as $k => $v)
        {
            if(is_int($k) && Base\Arr::keysExists(['type','label'],$v) && !Base\Vari::isReallyEmpty($v['label']) && !empty($v['type']))
            {
                $keep = true;

                $v['required'] = (!empty($v['required']));
                $v['minLength'] = (!empty($v['minLength']) && is_int($v['minLength']))? $v['minLength']:null;
                $v['maxLength'] = (!empty($v['maxLength']) && is_int($v['maxLength']))? $v['maxLength']:null;
                $choiceInput = static::isChoicesInput($v['type']);

                if($choiceInput === true)
                {
                    if(!empty($v['choices']) && is_string($v['choices']))
                    $v['choices'] = Base\Str::lines($v['choices']);

                    if(empty($v['choices']) || !is_array($v['choices']))
                    $keep = false;
                }

                else
                $v['choices'] = null;

                if($keep === true)
                {
                    $v = Base\Arr::reallyEmptyToNull($v);
                    $return[] = Base\Arrs::trim($v);
                }
            }
        }

        return $return ?: null;
    }


    // onModel
    // génère le formulaire du modèle
    final protected function onModel($value,array $attr,?Core\Cell $cell,array $option):string
    {
        $r = '';
        $lang = $this->db()->lang();

        $val = $value['type'] ?? null;
        $opt = Base\Arr::plus($option,['value'=>$val]);
        $label = $lang->text(['jsonForm','type']);
        $r .= static::makeModelFormElement('type','select',$label,true,static::getTypes(),$attr,$opt);

        $label = $lang->text(['jsonForm','label']);
        $r .= static::makeModelFormElement('label','inputText',$label,true,$value['label'] ?? null,$attr,$option);

        $label = $lang->text(['jsonForm','description']);
        $r .= static::makeModelFormElement('description','textarea',$label,false,$value['description'] ?? null,$attr,$option);

        $val = (!empty($value['required']))? 1:0;
        $opt = Base\Arr::plus($option,['value'=>$val]);
        $label = $lang->text(['jsonForm','required']);
        $r .= static::makeModelFormElement('required','select',$label,false,$lang->relation('bool'),$attr,$opt);

        $label = $lang->text(['jsonForm','minLength']);
        $r .= static::makeModelFormElement('minLength','inputDecimal',$label,false,$value['minLength'] ?? null,$attr,$option);

        $label = $lang->text(['jsonForm','maxLength']);
        $r .= static::makeModelFormElement('maxLength','inputDecimal',$label,false,$value['maxLength'] ?? null,$attr,$option);

        $val = $value['choices'] ?? null;
        $val = (is_array($val))? Base\Str::lineImplode($val):$val;
        $label = $lang->text(['jsonForm','choices']);
        $r .= static::makeModelFormElement('choices','textarea',$label,true,$val,$attr,$option);

        return $r;
    }


    // getSpecificComponentAttr
    // retourne les attr pour le specific component de jsonForm
    public function getSpecificComponentAttr(array $return):array
    {
        $return = parent::getSpecificComponentAttr($return);
        $return['data-form'] = static::getFormDataAttr();

        return $return;
    }


    // getFormDataAttr
    // retourne les data attr pour form
    protected static function getFormDataAttr():array
    {
        $return = [];

        foreach (static::$config['formFields'] as $v)
        {
            $return[$v] = static::getFieldWith($v);
        }

        return $return;
    }


    // isChoicesInput
    // retourne vrai si le input en est un avec des choix
    final public static function isChoicesInput($value):bool
    {
        return is_string($value) && in_array($value,static::getFieldWith('choices'),true);
    }


    // getTypes
    // retourne les types pour le jsonForm
    final public static function getTypes():array
    {
        $return = [];
        $lang = static::lang();

        foreach (static::$config['typesGroup']['all'] as $v)
        {
            $return[$v] = $lang->relation(['jsonForm',$v],null,false);
        }

        return $return;
    }


    // getFieldWith
    // retourne les inputs avec
    final public static function getFieldWith(string $type):array
    {
        return static::$config['fieldsType'][$type];
    }


    // isComplexTag
    // retourne vrai si la tag est pour le formulaire complexe
    protected static function isComplexTag(string $value):bool
    {
        return $value === 'json-form';
    }
}

// init
JsonForm::__init();
?>