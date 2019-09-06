<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Row;
use Quid\Core;
use Quid\Orm;
use Quid\Main;
use Quid\Base;

// eventSubmit
class EventSubmit extends Core\RowAlias
{
	// config
	public static $config = [
		'cols'=>[
			'user_id'=>['general'=>true,'onExport'=>[Core\Row\User::class,'userExport']]]
	];


	// subscribe
	// inscrit un utilisateur à une activité
	public static function subscribe(Event $event,Main\Contract\User $user,?array $option=null):?self
	{
		$return = null;
		$table = static::tableFromFqcn();
		$set = ['event_id'=>$event,'user_id'=>$user];
		$return = $table->insert($set,$option);

		return $return;
	}


	// unsubscribe
	// désinscrit un utilisateur à une activité
	public static function unsubscribe(Event $event,Main\Contract\User $user,?array $option=null):?int
	{
		$return = null;
		$find = static::find($event,$user);

		if(!empty($find))
		$return = $find->delete($option);

		return $return;
	}


	// find
	// retourne l'inscription d'un utilisateur à une activité
	public static function find(Event $event,Main\Contract\User $user):?self
	{
		$return = null;
		$table = static::tableFromFqcn();
		$where = ['event_id'=>$event,'user_id'=>$user];
		$return = $table->row($where);

		return $return;
	}


	// exists
	// retourne vrai si l'utilisatur est inscrit à l'activité
	public static function exists(Event $event,Main\Contract\User $user):bool
	{
		return (!empty(static::find($event,$user)))? true:false;
	}


	// upcoming
	// retourne les inscriptions d'activités à venir pour un utilisateur
	public static function upcoming(Main\Contract\User $user,int $limit=20):?Core\Rows
	{
		$return = null;
		$table = static::tableFromFqcn();
		$eventTable = Event::tableFromFqcn();
		$where = ['user_id'=>$user];
		$order = ['id'=>'desc'];
		$rows = $table->selects($where,$order);

		if(!empty($rows) && $rows->isNotEmpty())
		{
			$return = $eventTable->rowsNew();
			$timestamp = Base\Date::timestamp();

			foreach ($rows as $row)
			{
				$event = $row['event_id'](true);

				if(!empty($event) && $event->isVisible() && !$return->in($event))
				{
					$return->add($event);

					if($return->isCount($limit))
					break;
				}
			}

			$return = $return->order(['datetimeStart'=>'desc']);
		}

		return $return;
	}


	// commitFinalValidate
	// gère la validation finale pour event
	// retourne une erreur s'il y a déjà une inscription de l'utilisateur pour l'événement
	public static function commitFinalValidate(array $set,?Orm\Row $row,array $option)
	{
		$return = null;
		$event = $set['event_id'] ?? null;
		$user = $set['user_id'] ?? null;

		if(!empty($event) && !empty($user))
		{
			$table = static::tableFromFqcn();
			$where = ['event_id'=>$event,'user_id'=>$user];

			if(!empty($row))
			$where[] = [$table->primary(),'!=',$row->primary()];

			$id = $table->selectPrimary($where);

			if(!empty($id))
			$return = 'eventSubmit/duplicate';
		}

		return $return;
	}
}

// config
EventSubmit::__config();
?>