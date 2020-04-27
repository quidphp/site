<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Site\Service;
use Quid\Base;
use Quid\Main;

// googleGeocoding
// class used to make GoogleGeocoding localization requests
class GoogleGeocoding extends Main\ServiceRequest
{
    // config
    protected static array $config = [
        'target'=>'https://maps.googleapis.com/maps/api/geocode/json?address=%value%&key=%key%' // uri target pour googleGeocoding
    ];


    // apiKey
    // retourne la clé d'api
    final public function apiKey():string
    {
        return $this->getAttr('key');
    }


    // localize
    // lance la requête à googleGeocoding et retourne un objet de localization en cas de succès
    // plusieurs exceptions peuvent être envoyés
    final public function localize($value):?Main\Localization
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
    final public function request($value,?array $attr=null):Main\Request
    {
        return static::makeRequest(static::target(['key'=>$this->apiKey(),'value'=>$value]),Base\Arr::plus($this->attr(),$attr));
    }


    // prepareValue
    // prépare la valeur, peut envoyer une exception
    final public static function prepareValue($return)
    {
        if(is_array($return))
        $return = Base\Arr::implode(', ',$return);

        if(!is_string($return) || empty($return))
        static::throw($return);

        return $return;
    }


    // parse
    // parse le tableau de retour en provenance de googleGeocoding
    final public static function parse(array $array):array
    {
        $return = [];

        if(!empty($array['results'][0]))
        {
            $array = $array['results'][0];

            $location = $array['geometry']['location'] ?? null;
            if(!empty($location) && Base\Arr::keysExists(['lat','lng'],$location))
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
                    if(Base\Arr::keysExists(['short_name','long_name','types'],$value) && is_string($value['long_name']) && is_array($value['types']))
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

// init
GoogleGeocoding::__init();
?>