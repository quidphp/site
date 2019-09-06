<?php
declare(strict_types=1);
namespace Quid\Site\Route;
use Quid\Core;

// _specificPointer
trait _specificPointer
{
	// onBefore
	// avant le lancement de la route
	protected function onBefore() 
	{
		return $this->pointer()->isVisible();
	}
	
	
	// hasPointer
	// retourne vrai si le pointeur existe
	protected function hasPointer():bool 
	{
		return ($this->segment('pointer') instanceof Core\Row)? true:false;
	}
	
	
	// pointer
	// retourne la ligne de pointeur
	protected function pointer():Core\Row
	{
		return $this->segment('pointer');
	}
	
	
	// pointerRoute
	// retourne la route de la ligne du pointeur
	protected function pointerRoute():Core\Route
	{
		return $this->pointer()->route();
	}
}
?>