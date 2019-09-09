<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Cell;
use Quid\Core;
use Quid\Base;

// jsonForm
// class to work with a cell containing a json form (advanced jsonArray)
class JsonForm extends Core\Cell\JsonArrayAlias
{
    // config
    public static $config = [];


    // questions
    // retourne les questions du formulaire
    public function questions():array
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
    public function hasRequired():bool
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
    public function makeForm(?array $values=null,$attr=null):array
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

// config
JsonForm::__config();
?>