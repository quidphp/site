<?php
declare(strict_types=1);
namespace Quid\Site\Col;
use Quid\Site;
use Quid\Core;

// vimeo
class Vimeo extends Core\Col\VideoAlias
{
	// config
	public static $config = array(
		'cell'=>Site\Cell\Vimeo::class,
		'preValidate'=>array('uriHost'=>'vimeo.com'),
		'service'=>'vimeo' // custom, clé du service utilisé
	);
}

// config
Vimeo::__config();
?>