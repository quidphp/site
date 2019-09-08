<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Row;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// news
// class to work with a row of the news table
class News extends Core\RowAlias implements Main\Contract\Meta
{
	// trait
	use _meta;


	// config
	public static $config = [
		'key'=>['slug_[lang]',0],
		'@app'=>[
			'order'=>['date'=>'desc'],
			'where'=>[['datetimeStart','<=',[Base\Date::class,'timestamp']]]]
	];


	// isVisible
	// retourne vrai si la nouvelle est visible
	public function isVisible():bool
	{
		return (parent::isVisible() && $this['datetimeStart']->isAfter())? true:false;
	}
}

// config
News::__config();
?>