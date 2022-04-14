<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Cell;
use Quid\Base;
use Quid\Base\Html;
use Quid\Lemur;

// jsonFormRelation
// class to manage a cell containing a relation value to another cell containing a json form
class JsonFormRelation extends Lemur\Cell\JsonRelationAlias
{
    // config
    protected static array $config = [];


    // questions
    // retourne les questions du formulaire
    final public function questions():?array
    {
        $return = null;
        $cell = $this->relationCell();

        if(!empty($cell))
        $return = $cell->questions();

        return $return;
    }


    // getData
    // retourne les données complêtes du formulaire soumis
    final public function getData():?array
    {
        $return = null;
        $toCell = $this->toCell();
        $row = $this->relationRow();

        if(!empty($row))
        $return = $row[$toCell]->get();

        return $return;
    }


    // answers
    // retourne les réponses au formulaire sous forme de tableau unidimensionnel
    // le label de la question est la clé
    final public function answers():?array
    {
        $return = null;
        $datas = $this->getData();
        $get = $this->get();

        if(!empty($datas) && !empty($get))
        {
            $return = [];

            foreach ($get as $i => $answer)
            {
                if(array_key_exists($i,$datas) && !empty($datas[$i]['label']))
                {
                    $question = $datas[$i];

                    if(Html::isRelationTag($question['type']) && is_array($question['choices']))
                    {
                        if(is_array($answer))
                        $answer = Base\Arr::getsExists($answer,$question['choices']);

                        elseif(is_scalar($answer) && array_key_exists($answer,$question['choices']))
                        $answer = $question['choices'][$answer];
                    }

                    if(is_array($answer))
                    $answer = implode(', ',$answer);

                    $key = $question['label'];
                    $return[$key] = $answer;
                }
            }
        }

        return $return;
    }


    // answersString
    // retourne les réponses au formulaire sous forme de string
    final public function answersString(string $separator):string
    {
        return implode($separator,$this->answers());
    }


    // isDataValid
    // retourne vrai si les réponses sont valides
    final public function isDataValid():bool
    {
        $cell = $this->relationCell();
        $get = $this->get();

        if(is_array($get))
        $get = Base\Arr::clean($get);

        return !empty($cell) && !empty($get) && $cell->isDataValid($get);
    }


    // generalOutput
    // génère le output général pour une cellule jsonFormRelation
    final public function generalOutput(array $option):?string
    {
        return ($this->isDataValid())? $this->answersString(' | '):null;
    }
}

// init
JsonFormRelation::__init();
?>