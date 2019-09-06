<?php
declare(strict_types=1);
namespace Quid\Site\Col;
use Quid\Site;
use Quid\Core;

// youTube
class YouTube extends Core\Col\VideoAlias
{
	// config
	public static $config = array(
		'cell'=>Site\Cell\YouTube::class,
		'preValidate'=>array('uriHost'=>array('youtube.com','www.youtube.com')),
		'service'=>'youTube' // custom, clé du service utilisé
	);
}

// config
YouTube::__config();
?>