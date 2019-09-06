<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Route;
use Quid\Base\Html;

// _breadcrumbs
trait _breadcrumbs
{
	// getBreadcrumbs
	// retourne un tableau avec les routes breadcrumbs
	abstract public function getBreadcrumbs():array;


	// makeBreadcrumbs
	// construit les breadcrumbs pour la route
	public function makeBreadcrumbs(string $separator='/',int $min=1,int $length=40):string
	{
		$return = '';
		$separator = Html::span($separator,'separator');
		$breadcrumbs = $this->getBreadcrumbs();

		if(count($breadcrumbs) >= $min)
		$return = static::routes()->makeBreadcrumbs($separator,$length,...$breadcrumbs);

		return $return;
	}


	// selectedUri
	// gère les selected uri, renvoie vers selectedUriBreadcrumbs
	public function selectedUri():array
	{
		return $this->selectedUriBreadcrumbs();
	}


	// selectedUriBreadcrumbs
	// gère les selected uri selon le breadcumbs
	// méthode protégé
	protected function selectedUriBreadcrumbs():array
	{
		$return = [];

		foreach ($this->getBreadcrumbs() as $route)
		{
			$uri = $route->uri();
			if(!empty($uri))
			$return[$uri] = true;
		}

		return $return;
	}
}
?>