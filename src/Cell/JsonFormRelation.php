<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Cell;
use Quid\Base;
use Quid\Core;

// jsonFormRelation
// class to manage a cell containing a relation value to another cell containing a json form
class JsonFormRelation extends Core\Cell\JsonArrayRelationAlias
{
    // config
    public static $config = [];


    // questions
    // retourne les questions du formulaire
    public function questions():?array
    {
        $return = null;
        $row = $this->relationRow();

        if(!empty($row))
        $return = $row->questions();

        return $return;
    }


    // formInfo
    // retourne les données complêtes du formulaire
    public function formInfo():?array
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
    public function answers():array
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
    public function answersString(string $separator):string
    {
        return implode($separator,$this->answers());
    }


    // areAnswersValid
    // retourne vrai si les réponses sont valides
    public function areAnswersValid():bool
    {
        $return = false;
        $form = $this->relationRow();
        $get = $this->get();

        if(!empty($form) && !empty($get) && $form->areAnswersValid($get))
        $return = true;

        return $return;
    }
}

// config
JsonFormRelation::__config();
?>