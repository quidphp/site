<?php
declare(strict_types=1);
namespace Quid\Site\Route;

// _pageBreadcrumbs
trait _pageBreadcrumbs
{
	// getBreadcrumbs
	// génère les breadcrumbs pour la page
	public function getBreadcrumbs():array 
	{
		$return = array();
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