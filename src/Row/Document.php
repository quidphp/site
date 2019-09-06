<?php
declare(strict_types=1);
namespace Quid\Site\Row;
use Quid\Core;
use Quid\Main;

// document
class Document extends Core\RowAlias implements Main\Contract\Meta
{
	// trait
	use _meta;
	
	
	// config
	public static $config = array(
		'key'=>array('slug_[lang]',0),
	);
}

// config
Document::__config();
?>