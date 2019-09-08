<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Row;
use Quid\Core;

// pageContent
// class for a content which is the children of a page
class PageContent extends Core\RowAlias
{
	// config
	public static $config = [
		'key'=>['fragment_[lang]',0],
		'priority'=>1,
		'cols'=>[
			'method'=>['required'=>true]]
	];


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
		$return = $table->selects($where,$table->order())->filter(['isVisible'=>true]);

		return $return;
	}
}

// config
PageContent::__config();
?>