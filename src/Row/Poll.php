<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Row;
use Quid\Lemur;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// poll
// class to work with a row of the poll table
abstract class Poll extends Core\RowAlias
{
    // config
    public static $config = [
        'cols'=>[
            'json_fr'=>['class'=>Core\Col\JsonArray::class,'exists'=>false],
            'json_en'=>['class'=>Core\Col\JsonArray::class,'exists'=>false]],
        '@cms'=>[
            'route'=>[
                'export'=>Lemur\Cms\GeneralExportDialog::class],
            'specificOperation'=>[self::class,'specificOperation']]
    ];


    // submitClass
    // retourne la classe de row pour soumettre un poll
    abstract protected static function submitClass():string;


    // isVisible
    // retourne vrai si le sondage est visible
    public function isVisible():bool
    {
        return (parent::isVisible() && $this['datetimeStart']->isAfter() && $this['datetimeEnd']->isBefore(true))? true:false;
    }


    // answers
    // retourne un tableau avec les réponses possible au sondage
    public function answers():array
    {
        return (array) $this['json_[lang]']->get();
    }


    // votes
    // retourne les votes sur le sondage
    public function votes():?array
    {
        $return = [];
        $details = $this->details();

        if(!empty($details['vote']))
        $return = $details['vote'];

        return $return;
    }


    // percents
    // retourne un tableau avec un pourcentage de vote pour chaque réponse du sondage
    public function percents(bool $percent=false):?array
    {
        $return = [];
        $details = $this->details();

        if(!empty($details['percent']))
        {
            $return = $details['percent'];

            if($percent === true)
            $return = Base\Number::formats('%',$return);
        }

        return $return;
    }


    // details
    // retourne les détails du sondage sous forme de tableau multidimensionnel
    // le tableau est mis en cache
    public function details():array
    {
        return $this->cache(__METHOD__,function() {
            return static::submitClass()::details($this);
        });
    }


    // answer
    // retourne la réponse à partir d'un index de réponse
    public function answer(int $value):?string
    {
        $return = null;
        $answers = $this->answers();

        if(is_array($answers) && array_key_exists($value,$answers))
        $return = $answers[$value];

        return $return;
    }


    // hasVoted
    // retourne vrai si l'utilisateur a voté sur le sondage
    public function hasVoted(Main\Contract\User $user):bool
    {
        return static::submitClass()::hasVoted($this,$user);
    }


    // getVote
    // retourne le vote de l'utilisateur au sondage
    public function getVote(Main\Contract\User $user):?PollSubmit
    {
        return static::submitClass()::getVote($this,$user);
    }


    // vote
    // permet à un utilisateur de voter au sondage
    public function vote(int $value,Main\Contract\User $user,?array $option=null):bool
    {
        return static::submitClass()::vote($value,$this,$user,$option);
    }


    // specificOperation
    // dans le cms, permet l'exportation des réponses en CSV
    public static function specificOperation(self $row):string
    {
        $r = '';

        if($row->table()->hasPermission('specificOperation'))
        {
            $export = $row->routeClass('export');
            $table = PollSubmit::tableFromFqcn();

            if(!empty($export) && $row->isUpdateable() && $table->hasPermission('export') && $row->hasRelationChilds($table))
            {
                $segment = ['table'=>$table,'order'=>'id','direction'=>'desc','filter'=>['poll_id'=>$row]];
                $export = $export::makeOverload($segment)->initSegment();
                $r .= $export->aDialog();
            }
        }

        return $r;
    }
}

// config
Poll::__config();
?>