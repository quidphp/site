<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Route;
use Quid\Core;

// _specificSlugSection
trait _specificSlugSection
{
	// trait
	use _specificSlug;


	// onBefore
	// avant le lancement de la route
	protected function onBefore()
	{
		return ($this->row()->isVisible() && $this->section()->isVisible())? true:false;
	}


	// sectionExists
	// retourne vrai si la section existe
	public function sectionExists():bool
	{
		return ($this->section() instanceof Core\Row)? true:false;
	}


	// section
	// retourne la section
	public function section():Core\Row
	{
		return $this->row()->section();
	}


	// sectionRowClass
	// retourne la classe de la section
	public static function sectionRowClass():string
	{
		return static::$config['section'];
	}


	// sectionTableFromRowClass
	// retourne la table de la section
	public static function sectionTableFromRowClass():Core\Table
	{
		return static::boot()->db()->table(static::sectionRowClass());
	}


	// allSegment
	// génère tous les combinaisons possibles pour le sitemap
	public static function allSegment()
	{
		$return = [];
		$class = static::sectionRowClass();

		foreach ($class::grabVisible() as $section)
		{
			if($section->inAllSegment())
			{
				foreach ($section->childs() as $row)
				{
					$return = static::allSegmentDig($row,$return);
				}
			}
		}

		return $return;
	}


	// allSegmentDig
	// utilisé par allSegment pour creuser dans la hiérarchie
	// méthode protégé
	protected static function allSegmentDig(Core\Row $row,array $return):array
	{
		if($row->inAllSegment() && !in_array($row,$return,true))
		{
			$return[] = $row;
			$childs = $row->childs();

			if(!empty($childs))
			{
				foreach ($childs as $child)
				{
					$return = static::allSegmentDig($child,$return);
				}
			}
		}

		return $return;
	}
}
?>