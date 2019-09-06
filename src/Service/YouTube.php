<?php
declare(strict_types=1);
namespace Quid\Site\Service;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// youTube
class YouTube extends Core\ServiceVideoAlias
{
	// config
	public static $config = array(
		'required'=>array('provider_url','thumbnail_url','html'),
		'video'=>array(
			'name'=>'title',
			'absolute'=>array(self::class,'videoAbsolute'),
			'thumbnail'=>'thumbnail_url',
			'html'=>'html'),
		'target'=>'https://www.youtube.com/oembed?url=%value%&format=json' // uri target pour youTube
	);
	
	
	// videoAbsolute
	// retourne l'uri absolut pour la vidéo youTube
	// callback utilisé par la classe video
	public static function videoAbsolute(Main\Video $video):?string 
	{
		$return = null;
		$provider = $video->get('provider_url');
		$thumbnail = $video->get('thumbnail_url');
		
		if(!empty($provider) && !empty($thumbnail))
		{
			$path = Base\Uri::path($thumbnail);
			$x = Base\Path::arr($path);
			$key = $x[1] ?? null;
			
			if(!empty($key))
			{
				$query = array('v'=>$key);
				$change = array();
				$change['path'] = 'watch';
				$change['query'] = $query;
				$return = Base\Uri::change($change,$provider);
			}
		}
		
		return $return;
	}
}

// config
YouTube::__config();
?>