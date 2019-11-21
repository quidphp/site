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

// jsonFormRelation
// class to manage a cell containing a relation value to another cell containing a json form
class JsonFormRelation extends Lemur\Cell\JsonArrayRelationAlias
{
    // config
    public static $config = [];


    // questions
    // retourne les questions du formulaire
    final public function questions():?array
    {
        $return = null;
        $row = $this->relationRow();

        if(!empty($row))
        $return = $row->questions();

        return $return;
    }


    // formInfo
    // retourne les données complêtes du formulaire
    final public function formInfo():?array
    {
        $return = null;
        $row = $this->relationRow();

        if(!empty($row))
        $return = $row->formInfo();

        return $return;
    }


    // answers
    // retourne les réponses au formulaire sous forme de tableau unidimensionnel
    // le label de la question est la clé
    final public function answers():array
    {
        $return = null;
        $infos = $this->formInfo();
        $get = $this->get();

        if(!empty($infos) && !empty($get))
        {
            $return = [];

            foreach ($get as $i => $answer)
            {
                if(array_key_exists($i,$infos) && !empty($infos[$i]['label']))
                {
                    $question = $infos[$i];

                    if(Base\Html::isRelationTag($question['type']) && is_array($question['choices']))
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


    // areAnswersValid
    // retourne vrai si les réponses sont valides
    final public function areAnswersValid():bool
    {
        $return = false;
        $form = $this->relationRow();
        $get = $this->get();

        if(!empty($form) && !empty($get) && $form->areAnswersValid($get))
        $return = true;

        return $return;
    }
}

// init
JsonFormRelation::__init();
?>