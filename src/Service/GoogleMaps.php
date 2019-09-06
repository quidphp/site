<?php
declare(strict_types=1);
namespace Quid\Site\Service;
use Quid\Core;
use Quid\Base;

// googleMaps
class GoogleMaps extends Core\ServiceRequestAlias
{
	// config
	public static $config = array(
		'js'=>'//maps.googleapis.com/maps/api/js?v=3&key=%value%', // uri vers fichier js à charger
		'uri'=>'https://maps.google.com/maps?q=%value%' // uri vers googleMaps
	);
	
	
	// apiKey
	// retourne la clé d'api
	public function apiKey():string 
	{
		return $this->getOption('key');
	}
	
	
	// docOpenJs
	// retourne l'uri vers le fichier js à charger
	public function docOpenJs() 
	{
		return Base\Str::replace(array('%value%'=>$this->apiKey()),static::$config['js']);
	}
	
	
	// uri
	// méthode statique pour générer une uri vers googleMaps
	public static function uri(string $value):string 
	{
		$return = Base\Str::replace(array('%value%'=>$value),static::$config['uri']);
		$return = Base\Uri::absolute($return);
		
		return $return;
	}
}

// config
GoogleMaps::__config();
?>