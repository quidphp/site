<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
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


    // areAnswersValid
    // retourne vrai si les réponses sont valides pour le formulaire
    final public function areAnswersValid(array $array):bool
    {
        return true;
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
        {
            foreach ($get as $v)
            {
                if(array_key_exists('required',$v) && $v['required'] === true)
                {
                    $return = true;
                    break;
                }
            }
        }

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