<?php
declare(strict_types=1);
namespace Quid\Site\Row;

// _pageSection
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