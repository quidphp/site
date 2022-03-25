<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Col;
use Quid\Base;
use Quid\Base\Html;
use Quid\Core;
use Quid\Lemur;
use Quid\Orm;
use Quid\Site;

// jsonFormRelation
// class to manage a column containing a relation value to another column which is a jsonForm
class JsonFormRelation extends Lemur\Col\JsonArrayAlias
{
    // trait
    use Lemur\Col\_jsonRelation;


    // config
    protected static array $config = [
        'cell'=>Site\Cell\JsonFormRelation::class,
        'onExport'=>[self::class,'jsonFormExport']
    ];


    // prepare
    // arrange le tableau pour les méthode onGet et onSet
    final protected function prepare(array $return)
    {
        return array_values($return) ?: null;
    }


    // onSet
    // gère la logique onSet pour jsonFormRelation
    // prepare est utilisé sur le tableau
    final protected function onSet($return,?Orm\Cell $cell,array $row,array $option)
    {
        if(is_array($return))
        $return = $this->prepare($return);

        $fromCell = $this->fromCell();
        if(!empty($row[$fromCell]))
        {
            $cellForm = $this->relationCell($row[$fromCell]);

            if(!empty($cellForm) && is_array($return) && $cellForm->isDataValid($return))
            {
                foreach ($cellForm->getData() as $k => $v)
                {
                    if($v['type'] === 'checkbox' && is_string($return[$k]))
                    $return[$k] = Base\Arr::cast(Base\Set::arr($return[$k]));
                }
            }

            else
            $return = null;
        }

        return parent::onSet($return,$cell,$row,$option);
    }


    // beforeModel
    // génère la question avant model pour jsonFormRelation
    final public function beforeModel(int $i,$value,Core\Cell $cell):string
    {
        $return = '';
        $question = $cell->relationIndex($i);

        if($value !== null && !empty($question['label']))
        $return .= Html::div($question['label'],'question');

        return $return;
    }


    // prepareValueForm
    // prépare la valeur value pour le formulaire
    final protected function prepareValueForm($return,$option)
    {
        if($return instanceof Core\Cell && !$return->isDataValid())
        $return = null;

        $return = parent::prepareValueForm($return,$option);

        return $return;
    }


    // formComplex
    // génère le formComplex pour jsonFormRelation avec le relation export
    final public function formComplex($value=true,?array $attr=null,?array $option=null):string
    {
        $return = parent::formComplex($value,$attr,$option);
        $tag = $this->complexTag($attr);

        if($tag === 'add-remove' && $value instanceof Core\Cell && $value->isDataValid())
        {
            $answers = $value->answers();
            $return .= Html::div(Base\Debug::export($answers),'relation-export');
        }

        return $return;
    }


    // jsonFormExport
    // méthode utilisé pour exporter les colonnes et cellules d'un formulaire
    final public static function jsonFormExport(array $value,string $type,Core\Cell $cell,array $option):array
    {
        $return = [];

        if($type === 'col')
        $return = $cell->questions();

        else
        $return = $cell->answers();

        return $return;
    }
}

// init
JsonFormRelation::__init();
?>