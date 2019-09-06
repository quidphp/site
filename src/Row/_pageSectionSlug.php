<?php
declare(strict_types=1);
namespace Quid\Site\Row;
use Quid\Core;

// _pageSectionSlug
trait _pageSectionSlug
{
	// trait
	use _pageSectionConfig;
	
	
	// config
	public static $configSectionSlug = array(
		'cols'=>array(
			'slug_fr'=>array('slug'=>array(self::class,'makeSlug'),'exists'=>false),
			'slug_en'=>array('slug'=>array(self::class,'makeSlug'),'exists'=>false),
			'slugPath_fr'=>array('slug'=>array(self::class,'makeSlug'),'exists'=>false),
			'slugPath_en'=>array('slug'=>array(self::class,'makeSlug'),'exists'=>false))
	);


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