<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Service;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// ipApi
// class that grants methods to use the ipApi API, which converts IP to localization data
class IpApi extends Core\ServiceRequestAlias
{
    // config
    public static $config = [
        'target'=>'http://ip-api.com/json/%value%' // uri target pour ipApi
    ];


    // apiKey
    // retourne la clé d'api
    public function apiKey():?string
    {
        return $this->getOption('key');
    }


    // localize
    // lance la requête à ipApi et retourne un objet de localization en cas de succès
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
    public function request($value,?array $option=null):Main\Request
    {
        return static::makeRequest(static::target(['value'=>$value]),Base\Arr::plus($this->option(),$option));
    }


    // prepareValue
    // prépare la valeur, peut envoyer une exception
    // méthode protégé
    public static function prepareValue($return)
    {
        if($return === null)
        $return = Core\Request::live();

        if($return instanceof Core\Request)
        $return = $return->ip();

        if(!Base\Ip::is($return))
        static::throw('provideValidIp');

        if(Base\Ip::isLocal($return))
        static::throw('ipIsLocal');

        return $return;
    }


    // parse
    // parse le tableau de retour en provenance de ipApi
    public static function parse(array $array):array
    {
        return Base\Arr::keysChange(['lon'=>'lng','query'=>'input'],$array);
    }
}

// config
IpApi::__config();
?>