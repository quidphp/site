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

// document
// class to work with a row of the document table
class Document extends Core\RowAlias implements Main\Contract\Meta
{
	// trait
	use _meta;


	// config
	public static $config = [
		'key'=>['slug_[lang]',0],
	];
}

// config
Document::__config();
?>