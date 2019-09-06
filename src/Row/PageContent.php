<?php
declare(strict_types=1);
namespace Quid\Site\Row;
use Quid\Core;

// pageContent
class PageContent extends Core\RowAlias
{
	// config
	public static $config = array(
		'key'=>array('fragment_[lang]',0),
		'priority'=>1,
		'cols'=>array(
			'method'=>array('required'=>true))
	);
	
	
	// method
	// retourne la méthode à utiliser pour représenter le contenu de page
	public function method():string 
	{
		return $this['method']->value();
	}
	
	
	// grabFromPage
	// retourne tous les contenus de page à partir d'une page
	public static function grabFromPage(Page $page,?array $where=null):Core\Rows
	{
		$return = null;
		$table = static::tableFromFqcn();
		$where = (array) $where;
		$where['page_id'] = $page;
		$where = $table->where($where);
		$return = $table->selects($where,$table->order())->filter(array('isVisible'=>true));
		
		return $return;
	}
}

// config
PageContent::__config();
?>