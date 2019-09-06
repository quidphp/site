<?php
declare(strict_types=1);
namespace Quid\Site\Row;
use Quid\Core;
use Quid\Orm;
use Quid\Main;
use Quid\Base;

// pollSubmit
class PollSubmit extends Core\RowAlias
{
	// config
	public static $config = array(
		'cols'=>array(
			'user_id'=>array('general'=>true,'onExport'=>array(Core\Row\User::class,'userExport')),
			'value'=>array(
				'class'=>Core\Col\JsonArrayRelation::class,
				'relationCols'=>array('poll_id','json_fr'),
				'validate'=>array('>='=>0,'<='=>100)))
	);
	
	
	// cacheStatic
	protected static $cacheStatic = array();
	
	
	// hasVoted
	// retourne vrai si l'utilisateur a voté sur le sondage
	public static function hasVoted(Poll $poll,Main\Contract\User $user):bool 
	{
		$return = false;
		$row = static::getVote($poll,$user);
		
		if(!empty($row))
		$return = true;
		
		return $return;
	}
	
	
	// getVote
	// retourne le vote de l'utilisateur sur le sondage
	public static function getVote(Poll $poll,Main\Contract\User $user):?self
	{
		return static::cacheStatic(array(__METHOD__,$poll,$user),function() use($poll,$user) {
			$table = static::tableFromFqcn();
			$where = array('poll_id'=>$poll,'user_id'=>$user);
			return $table->select($where);
		});
	}
	
	
	// vote
	// ajoute un vote au sondage
	public static function vote(int $value,Poll $poll,Main\Contract\User $user,?array $option=null):bool
	{
		$return = false;
		$option = Base\Arr::plus(array('com'=>true),$option);
		$table = static::tableFromFqcn();
		$set = array('value'=>$value,'poll_id'=>$poll,'user_id'=>$user);
		
		$row = $table->insert($set,$option);
		
		if(!empty($row))
		$return = true;
		
		return $return;
	}
	
	
	// details
	// retourne les détails d'un sondage sous forme de tableau multidimensionnel
	public static function details(Poll $poll):array
	{
		$return = array();
		$table = static::tableFromFqcn();
		$primary = $table->primary();
		$where = array('poll_id'=>$poll);
		$answers = $poll->answers();
		
		if(!empty($answers))
		{
			$keyPair = $table->keyValue($primary,'value',false,$where);
			
			if(is_array($keyPair))
			{
				$return['total'] = count($keyPair);
				$return['vote'] = Base\Arr::countValues($keyPair);
				$return['vote'] = Base\Arr::keysSort($return['vote']);
				$return['percent'] = Base\Number::percentCalc($return['vote'],true);
				
				$return['all'] = array();
				if(!empty($return['percent']))
				{
					foreach ($answers as $key => $label) 
					{
						$r = array();
						$r['percent'] = $return['percent'][$key] ?? 0;
						$r['label'] = $label;
						$r['vote'] = $return['vote'][$key] ?? 0;
						
						$return['all'][$key] = $r;
					}
				}
			}
		}
		
		return $return;
	}
	
	
	// commitFinalValidate
	// gère la validation finale pour poll
	// retourne une erreur s'il y a déjà une réponse de l'utilisateur pour le sondage
	public static function commitFinalValidate(array $set,?Orm\Row $row,array $option) 
	{
		$return = null;
		$poll = $set['poll_id'] ?? null;
		$user = $set['user_id'] ?? null;
		
		if(!empty($poll) && !empty($user))
		{
			$table = static::tableFromFqcn();
			$where = array('poll_id'=>$poll,'user_id'=>$user);
			
			if(!empty($row))
			$where[] = array($table->primary(),'!=',$row->primary());

			$id = $table->selectPrimary($where);
			
			if(!empty($id))
			$return = 'pollSubmit/duplicate';
		}
		
		return $return;
	}
}

// config
PollSubmit::__config();
?>