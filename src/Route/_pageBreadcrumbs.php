<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Route;

// _pageBreadcrumbs
// trait that provides methods related to generating breadcrumbs for a page
trait _pageBreadcrumbs
{
	// getBreadcrumbs
	// génère les breadcrumbs pour la page
	public function getBreadcrumbs():array
	{
		$return = [];
		$row = $this->row();
		$breadcrumbs = $row->breadcrumb();

		foreach ($breadcrumbs as $breadcrumb)
		{
			$return[] = $breadcrumb->route();
		}

		return $return;
	}
}
?>