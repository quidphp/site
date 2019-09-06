<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Row;
use Quid\Site;
use Quid\Lemur;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// event
abstract class Event extends Core\RowAlias implements Main\Contract\Meta
{
	// trait
	use _meta;


	// config
	public static $config = [
		'key'=>['slug_[lang]',0],
		'cols'=>[
			'datetimeStart'=>['required'=>true,'general'=>true],
			'datetimeEnd'=>['required'=>true]],
		'@cms'=>[
			'route'=>[
				'export'=>Lemur\Cms\GeneralExportDialog::class],
			'specificOperation'=>[self::class,'specificOperation']]
	];


	// category
	// retourne la catégorie de l'activité
	abstract public function category():Core\Row;


	// submitClass
	// retourne la classe pour s'inscrire à une activité
	abstract protected static function submitClass():string;


	// isDay
	// retourne vrai si l'activité a lieu durant le jour du timestamp donnée en argument
	// pour les activités à multiple jour, retourne vrai si la date est dans un des jours de l'activité
	public function isDay(int $timestamp):bool
	{
		$return = false;
		$start = $this['datetimeStart']->value();
		$end = $this['datetimeEnd']->value();

		if(is_int($start) && is_int($end))
		{
			if(Base\Date::isDay($start,null,$timestamp) || Base\Date::isDay($end,null,$timestamp) || Base\Number::in($start,$timestamp,$end))
			$return = true;
		}

		return $return;
	}


	// date
	// retourne la date de début et fin de l'activité en une string
	public function date(?array $option=null):string
	{
		return Base\Date::formatStartEnd($this['datetimeStart'],$this['datetimeEnd'],Base\Arr::plus(['format'=>1,'formatDay'=>0],$option));
	}


	// exportArray
	// génère un tableau d'exportation, utilie pour faire un fichier ICS ou ajout au calendrier via office365
	public function exportArray():array
	{
		$return = [];

		$return['dateStart'] = $this['datetimeStart'];
		$return['dateEnd'] = $this['datetimeEnd'];
		$return['name'] = $this->cellName();
		$return['description'] = $this->cellContent();
		$return['location'] = null;
		$return['uri'] = null;
		$return['id'] = $this->primary();
		$return['app'] = static::boot()->label();

		return $return;
	}


	// office365
	// créer le lien pour ajouter l'activité au calendrier d'office 365
	public function office365():string
	{
		return Site\Service\Office365::event($this->exportArray());
	}


	// canSubscribe
	// retourne vrai s'il est possible de s'inscrire à l'activité
	public function canSubscribe(bool $visible=true):bool
	{
		return (($visible === false || $this->isVisible()) && $this['subscribe']->isEqual(1))? true:false;
	}


	// isSubscribed
	// retourne vrai si l'utilisateur est inscrit à l'activité
	public function isSubscribed(Main\Contract\User $user):bool
	{
		return static::submitClass()::exists($this,$user);
	}


	// subscribe
	// inscrit l'utilisateur à l'activité
	public function subscribe(Main\Contract\User $user,?array $option=null):?EventSubmit
	{
		return static::submitClass()::subscribe($this,$user,$option);
	}


	// unsubscribe
	// désinscrit l'utilisateur à l'activité
	public function unsubscribe(Main\Contract\User $user,?array $option=null):?int
	{
		return static::submitClass()::unsubscribe($this,$user,$option);
	}


	// specificOperation
	// dans le cms, permet l'exportation des inscriptions en CSV
	public static function specificOperation(self $row):string
	{
		$r = '';

		if($row->table()->hasPermission('specificOperation'))
		{
			$export = $row->routeClass('export');
			$table = EventSubmit::tableFromFqcn();

			if(!empty($export) && $row->isUpdateable() && $row->canSubscribe(false) && $table->hasPermission('export') && $row->hasRelationChilds($table))
			{
				$segment = ['table'=>$table,'order'=>'id','direction'=>'desc','filter'=>['event_id'=>$row]];
				$export = $export::makeOverload($segment)->initSegment();
				$r .= $export->aDialog();
			}
		}

		return $r;
	}
}

// config
Event::__config();
?>