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
use Quid\Orm;
use Quid\Site;

// jsonFormRelation
// class to manage a column containing a relation value to another column which is a jsonForm
class JsonFormRelation extends Lemur\Col\JsonArrayAlias
{
    // config
    protected static array $config = [
        'cell'=>Site\Cell\JsonFormRelation::class,
        'onExport'=>[self::class,'jsonFormExport'],
        'relationCols'=>null // custom
    ];


    // prepare
    // arrange le tableau pour les méthode onGet et onSet
    final protected function prepare(array $return)
    {
        $return = array_values($return);

        if(empty($return))
        $return = null;

        return $return;
    }


    // onSet
    // gère la logique onSet pour jsonFormRelation
    // prepare est utilisé sur le tableau
    final protected function onSet($return,?Orm\Cell $cell=null,array $row,array $option)
    {
        if(is_array($return))
        $return = $this->prepare($return);

        $fromCell = $this->fromCell();
        if(!empty($row[$fromCell]))
        {
            $cellForm = $this->relationCell($row[$fromCell]);

            if(!empty($cellForm) && is_array($return) && $cellForm->areAnswersValid($return))
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

        $return = parent::onSet($return,$cell,$row,$option);

        return $return;
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
        if($return instanceof Core\Cell && !$return->areAnswersValid())
        $return = null;

        $return = parent::prepareValueForm($return,$option);

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


    // formComplex
    // génère le formComplex pour jsonFormRelation avec le relation export
    final public function formComplex($value=true,?array $attr=null,?array $option=null):string
    {
        $return = parent::formComplex($value,$attr,$option);
        $tag = $this->complexTag($attr);

        if($tag === 'add-remove' && $value instanceof Core\Cell)
        {
            $answers = $value->answers();
            $return .= Html::div(Base\Debug::export($answers),'relation-export');
        }

        return $return;
    }


    // relationCell
    // retourne la cellule de la row de relation
    final public function relationCell(int $id):?Core\cell
    {
        $return = null;
        $fromCell = $this->fromCell();
        $tableName = Orm\ColSchema::table($fromCell);

        if(!empty($tableName))
        {
            $toCell = $this->toCell();
            $db = $this->db();
            $row = $db->table($tableName)->row($id);

            if(!empty($row))
            $return = $row->cell($toCell);
        }

        return $return;
    }


    // fromCell
    // retourne la cellule from de la ligne courante
    final public function fromCell():string
    {
        $return = null;
        $relationCols = $this->getAttr('relationCols');

        if(is_array($relationCols) && count($relationCols) === 2)
        $return = $relationCols[0];

        return $return;
    }


    // toCell
    // retourne la cellule to de la ligne de relation
    final public function toCell():string
    {
        $return = null;
        $relationCols = $this->getAttr('relationCols');

        if(is_array($relationCols) && count($relationCols) === 2)
        $return = $relationCols[1];

        return $return;
    }
}

// init
JsonFormRelation::__init();
?>