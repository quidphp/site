<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Site\Col;
use Quid\Base;
use Quid\Base\Html;
use Quid\Core;
use Quid\Lemur;
use Quid\Site;

// jsonForm
// class for a column containing a json form
class JsonForm extends Lemur\Col\JsonArrayAlias
{
    // config
    protected static array $config = [
        'complex'=>'json-form',
        'cell'=>Site\Cell\JsonForm::class
    ];


    // preValidatePrepare
    // prépare le tableau de chargement avant la prévalidation
    final public function preValidatePrepare($value)
    {
        $return = null;

        if(Base\Arrs::is($value))
        $return = Base\Column::keySwap($value);

        return $return;
    }


    // prepare
    // arrange le tableau pour les méthode onGet et onSet
    final protected function prepare(array $value):?array
    {
        $return = [];

        if(Base\Arr::isAssoc($value))
        $return = $this->preValidatePrepare($value);

        elseif(Base\Column::is($value))
        {
            foreach ($value as $k => $v)
            {
                if(is_int($k) && Base\Arr::keysExists(['label','type','description','choices'],$v))
                {
                    if(!empty($v['label']) && !empty($v['type']))
                    {
                        $v['required'] = (!empty($v['required']));
                        $choiceInput = static::isChoicesInput($v['type']);

                        if($choiceInput === true && !empty($v['choices']))
                        {
                            if(is_string($v['choices']))
                            $v['choices'] = Base\Str::lines($v['choices']);
                        }
                        else
                        $v['choices'] = null;

                        if($choiceInput === false || is_array($v['choices']))
                        {
                            $v = Base\Arr::reallyEmptyToNull($v);
                            $v = Base\Arrs::trim($v);
                            $return[] = $v;
                        }
                    }
                }
            }
        }

        if(empty($return))
        $return = null;

        return $return;
    }


    // makeModel
    // génère le model pour jsonForm
    final public function makeModel($value,array $attr,?Core\Cell $cell=null,array $option):string
    {
        $return = Html::divOp('ele');
        $name = $attr['name'];
        $lang = $this->db()->lang();
        $choicesInput = static::getChoicesInput();
        $type = $value['type'] ?? null;

        if(!empty($cell) && array_key_exists('index',$option))
        $return .= $this->beforeModel($option['index'],$value,$cell);

        $return .= Html::divOp('current');
        $return .= Html::divOp('label');
        $label = $lang->text('jsonForm/label');
        $val = $value['label'] ?? null;
        $form = ['inputText',$val,Base\Arr::plus($attr,['name'=>$name.'[label]']),$option];
        $return .= Html::formWrap('*'.$label.':',$form,'divtable');
        $return .= Html::divCl();

        $return .= Html::divOp(['type','data'=>['choices'=>$choicesInput]]);
        $label = $lang->text('jsonForm/type');
        $relation = $lang->relation('jsonForm',null,false);
        $opt = Base\Arr::plus($option,['value'=>$type]);
        $form = ['select',$relation,Base\Arr::plus($attr,['name'=>$name.'[type]']),$opt];
        $return .= Html::formWrap($label.':',$form,'divtable',null);
        $return .= Html::divCl();

        $return .= Html::divOp('description');
        $label = $lang->text('jsonForm/description');
        $val = $value['description'] ?? null;
        $form = ['textarea',$val,Base\Arr::plus($attr,['name'=>$name.'[description]']),$option];
        $return .= Html::formWrap($label.':',$form,'divtable');
        $return .= Html::divCl();

        $return .= Html::divOp('required');
        $label = $lang->text('jsonForm/required');
        $relation = $lang->relation('bool');
        $val = (!empty($value['required']))? 1:0;
        $opt = Base\Arr::plus($option,['value'=>$val]);
        $form = ['select',$relation,Base\Arr::plus($attr,['name'=>$name.'[required]']),$opt];
        $return .= Html::formWrap($label.':',$form,'divtable',null);
        $return .= Html::divCl();

        $class = (static::isChoicesInput($type))? 'visible':null;
        $return .= Html::divOp(['choices',$class]);
        $label = $lang->text('jsonForm/choices');
        $val = $value['choices'] ?? null;
        $val = (is_array($value['choices']))? Base\Str::lineImplode($value['choices']):$val;
        $form = ['textarea',$val,Base\Arr::plus($attr,['name'=>$name.'[choices]']),$option];
        $return .= Html::formWrap('*'.$label.':',$form,'divtable');
        $return .= Html::divCl();

        $return .= Html::divCl();

        $return .= $this->makeModelUtils();
        $return .= Html::divCl();

        return $return;
    }


    // isChoicesInput
    // retourne vrai si le input en est un avec des choix
    final public static function isChoicesInput($value):bool
    {
        return is_string($value) && in_array($value,static::getChoicesInput(),true);
    }


    // getChoicesInput
    // retourne les inputs avec choix de réponse
    final public static function getChoicesInput():array
    {
        return Base\Html::relationTag('multiselect');
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