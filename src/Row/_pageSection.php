<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Row;

// _pageSection
// trait with methods to deal with a page row within a section
trait _pageSection
{
	// dynamic
	protected $sectionGrabbed = false; // garde en mémoire si les sections ont été chargés au moins une fois


	// hasSection
	// retourne vrai si la section existe
	public function hasSection():bool
	{
		return true;
	}


	// section
	// retourne la section de la page
	// si les sections n'ont pas encore été chargés, fait le
	public function section():?Section
	{
		return $this->cache(__METHOD__,function() {
			$return = null;
			$class = Section::class;
			$rows = $class::rows();

			if($rows->isEmpty() && $this->sectionGrabbed === false)
			{
				$class::grabVisible();
				$this->sectionGrabbed = true;
			}

			foreach ($class::rows() as $section)
			{
				if($section->isChild($this))
				$return = $section;
			}

			return $return;
		});
	}
}
?>