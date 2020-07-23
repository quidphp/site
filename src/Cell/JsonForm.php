<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Cell;
use Quid\Base;
use Quid\Lemur;

// jsonForm
// class to work with a cell containing a json form
class JsonForm extends Lemur\Cell\JsonArrayAlias
{
    // config
    protected static array $config = [];


    // isDataValid
    // retourne vrai si les réponses sont valides
    final public function isDataValid($values):bool
    {
        $return = false;
        $formData = $this->getData();

        if(is_array($formData) && !empty($formData) && is_array($values) && array_keys($formData) === array_keys($values))
        {
            foreach ($formData as $k => $v)
            {
                $valid = (empty($v['required']) || !Base\Validate::isReallyEmpty($values[$k]));

                if(Base\Html::isRelationTag($v['type']) && is_array($v['choices']))
                {
                    if($values[$k] === '')
                    $values[$k] = null;

                    if($values[$k] !== null)
                    {
                        if(is_scalar($values[$k]))
                        $values[$k] = Base\Set::arr($values[$k]);

                        $valid = (is_array($values[$k]))? Base\Arr::keysExists($values[$k],$v['choices']):false;
                    }
                }

                if($valid === false)
                break;
            }

            $return = $valid;
        }

        return $return;
    }


    // questions
    // retourne les questions du formulaire
    final public function questions():array
    {
        $return = [];
        $get = $this->get();

        if(Base\Column::is($get))
        {
            foreach ($get as $k => $v)
            {
                if(array_key_exists('label',$v))
                $return[$k] = $v['label'];
            }
        }

        return $return;
    }


    // hasRequired
    // retourne vrai si le formulaire a un champ requis
    final public function hasRequired():bool
    {
        $return = false;
        $get = $this->get();

        if(Base\Column::is($get))
        $return = Base\Arr::some($get,fn($v) => (array_key_exists('required',$v) && $v['required'] === true));

        return $return;
    }


    // makeForm
    // génère le formulaire
    final public function makeForm(?array $values=null,$attr=null):array
    {
        $return = [];
        $get = $this->get();
        $name = $this->name();
        $values = (array) $values;
        $wrap = "<div class='labelDescription'>%label%%description%</div>%form%";
        $replace = [];

        if(Base\Column::is($get))
        {
            foreach ($get as $k => $v)
            {
                $attr = ['name'=>$name."[$k]"];
                $value = (array_key_exists($k,$values))? $values[$k]:null;
                $return[$k] = Base\Html::formWrapArray($value,$v,$wrap,$attr,$replace);
            }
        }

        return $return;
    }
}

// init
JsonForm::__init();
?>