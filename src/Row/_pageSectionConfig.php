<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Row;
use Quid\Core;
use Quid\Base;

// _pageSectionConfig
trait _pageSectionConfig
{
	// trait
	use _pageSection; use _pageConfig;
	// dynamicPageClass
	// retourne le nom de la classe de la route de page dynamique
	// la page dynamique a un segment et peut avoir différentes uris de page
	abstract public static function dynamicPageClass():string;


	// getRoutesCanPrepare
	// retourne toutes les routes qui doivent être préparés
	// n'inclut pas la route page dynamique, car plusieurs uris peuvent utilisateur cette route
	public static function getRoutesCanPrepare():?Core\Routes
	{
		return static::boot()->routes(static::getRouteKey())->not('Page');
	}


	// dynamicRouteMake
	// génère la route de la page ou de la page dynamique, dépendamment de la classe
	public static function dynamicRouteMake(string $route,Page $page):Core\Route
	{
		$return = null;
		$class = static::dynamicPageClass();

		if(is_a($route,$class,true))
		$return = $route::make($page);

		else
		$return = $route::make();

		return $return;
	}


	// routeConfig
	// fait une configuration sur la page non dynamique
	// lie la section à la configuration
	// cette méthode est appelé par routePrepareConfig
	public function routeConfig(array $return):array
	{
		$path = null;
		$section = $this->section();
		$lang = $this->db()->lang();

		if(!empty($section))
		{
			$return['section'] = $section;

			foreach ($lang->allLang() as $key)
			{
				foreach (["slug_$key","slugPath_$key"] as $value)
				{
					if($this->hasCell($value))
					{
						$cell = $this->cell($value);
						$path = [$cell];
						$return['path'][$key] = Base\Path::append(...$path);
					}
				}
			}
		}

		return $return;
	}
}
?>