<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Service;
use Quid\Base;
use Quid\Main;
use Quid\Routing;

// googleMaps
// class used to generate javascript GoogleMaps
class GoogleMaps extends Main\ServiceRequest
{
    // trait
    use Routing\_service;


    // config
    protected static array $config = [
        'js'=>'//maps.googleapis.com/maps/api/js?v=3&key=%value%&loading=async&libraries=marker&callback=setGoogleMapLoaded', // uri vers fichier js à charger
        'uri'=>'https://maps.google.com/maps?q=%value%' // uri vers googleMaps
    ];


    // apiKey
    // retourne la clé d'api
    final public function apiKey():string
    {
        return $this->getAttr('key');
    }


    // docOpenJs
    // retourne l'uri vers le fichier js à charger
    final public function docOpenJs()
    {
        return [[Base\Str::replace(['%value%'=>$this->apiKey()],$this->getAttr('js')),['async'=>true]]];
    }


    // uri
    // méthode statique pour générer une uri vers googleMaps
    final public static function uri(string $value):string
    {
        $return = Base\Str::replace(['%value%'=>$value],static::$config['uri']);
        $return = Base\Uri::absolute($return);

        return $return;
    }
}

// init
GoogleMaps::__init();
?>