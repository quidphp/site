<?php
declare(strict_types=1);
namespace Quid\Site\Service;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// vimeo
class Vimeo extends Core\ServiceVideoAlias
{
	// config
	public static $config = array(
		'required'=>array('provider_url','video_id','html'),
		'video'=>array(
			'name'=>'title',
			'date'=>'upload_date',
			'description'=>'description',
			'absolute'=>array(self::class,'videoAbsolute'),
			'thumbnail'=>'thumbnail_url',
			'html'=>'html'),
		'target'=>'https://vimeo.com/api/oembed.json?url=%value%' // uri target pour vimeo
	);
	
	
	// videoAbsolute
	// retourne l'uri absolut pour la vidéo vimeo
	// callback utilisé par la classe video
	public static function videoAbsolute(Main\Video $video):?string 
	{
		$return = null;
		$provider = $video->get('provider_url');
		$videoId = $video->get('video_id');
		
		if(!empty($provider) && !empty($videoId))
		{
			$change = array('path'=>$videoId);
			$return = Base\Uri::change($change,$provider);
		}
		
		return $return;
	}
}

// config
Vimeo::__config();
?>