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

// ipApi
// class that grants methods to use the IpApi API, which converts IP to localization data
class IpApi extends Main\ServiceRequest
{
    // config
    public static array $config = [
        'target'=>'http://ip-api.com/json/%value%' // uri target pour ipApi
    ];


    // apiKey
    // retourne la clé d'api
    final public function apiKey():?string
    {
        return $this->getAttr('key');
    }


    // localize
    // lance la requête à ipApi et retourne un objet de localization en cas de succès
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
            if(!empty($json['message']))
            static::throw($json['status'] ?? null,$json['message']);

            $json = static::parse($json);

            if(!empty($json))
            $return = Main\Localization::newOverload($json);

            else
            static::throw('invalidResponseFormat');
        }

        return $return;
    }


    // request
    // retourne la requête à utiliser pour aller chercher une localization auprès de ipApi
    final public function request($value,?array $attr=null):Main\Request
    {
        return static::makeRequest(static::target(['value'=>$value]),Base\Arr::plus($this->attr(),$attr));
    }


    // prepareValue
    // prépare la valeur, peut envoyer une exception
    final public static function prepareValue($return)
    {
        if($return === null)
        $return = Main\Request::live();

        if($return instanceof Main\Request)
        $return = $return->ip();

        if(!Base\Ip::is($return))
        static::throw('provideValidIp');

        if(Base\Ip::isLocal($return))
        static::throw('ipIsLocal');

        return $return;
    }


    // parse
    // parse le tableau de retour en provenance de ipApi
    final public static function parse(array $array):array
    {
        return Base\Arr::keysChange(['lon'=>'lng','query'=>'input'],$array);
    }
}

// init
IpApi::__init();
?>