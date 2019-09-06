<?php
declare(strict_types=1);
namespace Quid\Site\Service;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// googleGeocoding
class GoogleGeocoding extends Core\ServiceRequestAlias
{
	// config
	public static $config = array(
		'target'=>'https://maps.googleapis.com/maps/api/geocode/json?address=%value%&key=%key%' // uri target pour googleGeocoding
	);
	
	
	// apiKey
	// retourne la clé d'api
	public function apiKey():string 
	{
		return $this->getOption('key');
	}
	
	
	// localize
	// lance la requête à googleGeocoding et retourne un objet de localization en cas de succès
	// plusieurs exceptions peuvent être envoyés
	public function localize($value):?Main\Localization
	{
		$return = null;
		$value = $this->prepareValue($value);
		$request = $this->request($value);
		$response = $request->trigger();
		$json = $response->body(true);

		if(!empty($json))
		{
			if(!empty($json['error_message']))
			static::throw($json['error_message']);
			
			$json = static::parse($json);
			
			if(!empty($json))
			{
				$json['input'] = $value;
				$return = Main\Localization::newOverload($json);
			}
			
			else
			static::catchable(null,'invalidResponseFormat');
		}
		
		return $return;
	}
	
	
	// request
	// retourne la requête à utiliser pour aller chercher une localization auprès de googleGeocoding
	public function request($value,?array $option=null):Main\Request 
	{
		return static::makeRequest(static::target(array('key'=>$this->apiKey(),'value'=>$value)),Base\Arr::plus($this->option(),$option));
	}
	
	
	// prepareValue
	// prépare la valeur, peut envoyer une exception
	// méthode protégé
	public static function prepareValue($return) 
	{
		if(is_array($return))
		$return = Base\Arr::implode(', ',$return);
		
		if(!is_string($return) || empty($return))
		static::throw($return);
		
		return $return;
	}
	
	
	// parse
	// parse le tableau de retour en provenance de googleGeocoding
	public static function parse(array $array):array 
	{
		$return = array();
		
		if(!empty($array['results'][0]))
		{
			$array = $array['results'][0];
			
			$location = $array['geometry']['location'] ?? null;
			if(!empty($location) && Base\Arr::keysExists(array('lat','lng'),$location))
			{
				$return['lat'] = $location['lat'];
				$return['lng'] = $location['lng'];
			}

			$formated = $array['formatted_address'] ?? null;
			if(!empty($formated) && is_string($formated))
			$return['address'] = $formated;
			
			$components = $array['address_components'] ?? null;
			if(!empty($components) && is_array($components))
			{
				foreach ($components as $value)
				{
					if(Base\Arr::keysExists(array('short_name','long_name','types'),$value) && is_string($value['long_name']) && is_array($value['types']))
					{
						if($value['types'][0] === 'country' && is_string($value['short_name']))
						$return['countryCode'] = $value['short_name'];
						
						$key = Base\Arrs::keyPrepare($value['types']);
						$value = $value['long_name'];
						$return[$key] = $value;
					}
				}
			}
		}
		
		return $return;
	}
}

// config
GoogleGeocoding::__config();
?>