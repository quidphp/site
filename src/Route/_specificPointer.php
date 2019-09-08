<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Route;
use Quid\Core;

// _specificPointer
// trait that grants methods to deal with a specific resource represent by a pointer (table/id)
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