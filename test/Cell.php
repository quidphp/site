<?php
declare(strict_types=1);
namespace Quid\Test\Site;
use Quid\Site;
use Quid\Core;
use Quid\Base;

// cell
class Cell extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare 
		$db = Core\Boot::inst()->db();
		$table = 'ormCell';
		assert($db->truncate($table) instanceof \PDOStatement);
		assert($db->inserts($table,array('id','date','name','dateAdd','userAdd','dateModify','userModify','integer','enum','set','user_ids'),array(1,time(),'james',10,2,12,13,12,5,"2,3",array(2,1)),array(2,time(),'james2',10,11,12,13,12,5,"2,4","2,3")) === array(1,2));
		$tb = $db[$table];
		$row = $tb[1];
		$googleMaps = $row->cell('googleMaps');
		$vimeo = $row->cell('vimeo');
		
		// googleMaps
		assert($googleMaps instanceof Site\Cell\GoogleMaps);
		assert($googleMaps->html() === null);
		assert($googleMaps->address() === null);
		assert($googleMaps->uri() === null);
		assert($googleMaps->input() === null);
		
		// jsonForm
		
		// jsonFormRelation
		
		// vimeo
		assert($vimeo instanceof Site\Cell\Vimeo);
		assert($vimeo->formComplex() === "<input maxlength='65535' name='vimeo' type='text'/>");
		assert(strlen($vimeo->formComplexWrap()) === 114);
		
		// youTube
		
		// cleanup
		assert($db->truncate($table) instanceof \PDOStatement);
		
		return true;
	}
}
?>