<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Row;
use Quid\Core;

// _pageSectionSlug
trait _pageSectionSlug
{
	// trait
	use _pageSectionConfig;


	// config
	public static $configSectionSlug = [
		'cols'=>[
			'slug_fr'=>['slug'=>[self::class,'makeSlug'],'exists'=>false],
			'slug_en'=>['slug'=>[self::class,'makeSlug'],'exists'=>false],
			'slugPath_fr'=>['slug'=>[self::class,'makeSlug'],'exists'=>false],
			'slugPath_en'=>['slug'=>[self::class,'makeSlug'],'exists'=>false]]
	];


	// getSlugPrepend
	// ajout le contenu avant le slug, soit le slug du parent ou soit le nom de la section
	public static function getSlugPrepend(Core\Col $col,array $row,?Core\Cell $cell=null):?Core\Cell
	{
		$return = null;
		$parent = (!empty($cell))? $cell->row():null;

		if(empty($parent))
		{
			$parent = $row['page_id'] ?? null;

			if(is_int($parent))
			{
				$table = static::tableFromFqcn();
				$parent = $table->row($parent);
			}
		}

		if(!empty($parent))
		{
			$section = $parent->section();
			if(!empty($section))
			$return = $section->cellName();
		}

		return $return;
	}
}
?>