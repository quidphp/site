<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Route;
use Quid\Site;

// _pageSection
trait _pageSection
{
	// trait
	use _page;


	// dynamique
	protected $section = null;


	// onMake
	// lors de la construction de la route
	protected function onMake():void
	{
		$this->makeRow();
		$this->makeSection();

		return;
	}


	// onBefore
	// avant le lancement de la route
	protected function onBefore()
	{
		$return = false;

		if($this->rowExists() && $this->sectionExists())
		{
			if($this->row()->isVisible() && $this->section()->isVisible())
			{
				if($this->row()->section() === $this->section())
				$return = true;
			}
		}

		return $return;
	}


	// sectionExists
	// retourne vrai si la section existe
	public function sectionExists():bool
	{
		return (!empty($this->section))? true:false;
	}


	// section
	// retourne la section
	public function section():Site\Row\Section
	{
		return $this->section;
	}


	// makeSection
	// construit la section, utilisé dans onMake
	protected function makeSection():self
	{
		if(empty($this->section))
		{
			$section = static::$config['section'] ?? null;
			if(is_int($section))
			$this->section = Site\Row\Section::row($section);
		}

		return $this;
	}
}
?>