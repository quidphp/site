<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
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
    // config
    public static $config = [
        'cell'=>Site\Cell\JsonFormRelation::class,
        'onExport'=>[self::class,'jsonFormExport']
    ];


    // prepare
    // arrange le tableau pour les méthode onGet et onSet
    protected function prepare(array $return)
    {
        $return = array_values($return);

        if(empty($return))
        $return = null;

        return $return;
    }


    // onGet
    // logique onGet pour un champ jsonFormRelation
    public function onGet($return,array $option)
    {
        if(!empty($option['context']) && $option['context'] === 'cms:general' && $return instanceof Core\Cell)
        $return = ($return->areAnswersValid())? $return->answersString(' | '):null;

        else
        $return = parent::onGet($return,$option);

        return $return;
    }


    // onSet
    // gère la logique onSet pour jsonFormRelation
    // prepare est utilisé sur le tableau
    public function onSet($return,array $row,?Orm\Cell $cell=null,array $option)
    {
        if(is_array($return))
        $return = $this->prepare($return);

        if(!empty($row['form_id']))
        {
            $db = $this->db();
            $form = $db->table('form')->row($row['form_id']);

            if(!empty($form) && $form->areAnswersValid($return))
            {
                foreach ($form->formInfo() as $k => $v)
                {
                    if($v['type'] === 'checkbox' && is_string($return[$k]))
                    $return[$k] = Base\Arr::cast(Base\Set::arr($return[$k]));
                }
            }

            else
            $return = null;
        }

        $return = parent::onSet($return,$row,$cell,$option);

        return $return;
    }


    // beforeModel
    // génère la question avant model pour jsonFormRelation
    public function beforeModel(int $i,$value,Core\Cell $cell):string
    {
        $return = '';
        $question = $cell->relationIndex($i);

        if($value !== null && !empty($question['label']))
        $return .= Html::div($question['label'],'question');

        return $return;
    }


    // prepareValueForm
    // prépare la valeur value pour le formulaire
    protected function prepareValueForm($return,$option)
    {
        if($return instanceof Core\Cell && !$return->areAnswersValid())
        $return = null;

        $return = parent::prepareValueForm($return,$option);

        return $return;
    }


    // jsonFormExport
    // méthode utilisé pour exporter les colonnes et cellules d'un formulaire
    public static function jsonFormExport(array $value,string $type,Core\Cell $cell,array $option):array
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