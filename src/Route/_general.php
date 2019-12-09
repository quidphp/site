<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Site\Route;
use Quid\Base;

// _general
// trait that provides basic methods used for a general route
trait _general
{
    // config
    public static $configSpecific = [
        'group'=>'general'
    ];


    // selectedUri
    // gère les selected uri pour une route general
    // par défaut la route avec segment par défaut est sélectionné
    final public function selectedUri():array
    {
        $return = [];
        $route = static::make();
        $uri = $route->uri(null,['query'=>false]);
        $return[$uri] = true;

        return $return;
    }


    // makeGeneral
    // cette méthode permet de retourner une route general à partir de la classe
    final public static function makeGeneral($navKey=null,$segment=null):?self
    {
        $return = null;
        $class = static::class;

        $key = [$class];
        if($navKey !== null)
        $key = Base\Arr::append($key,$navKey);

        $route = static::session()->nav()->route($key);

        if(empty($route))
        $route = $class::make($segment);

        if($route->isValidSegment())
        $return = $route;

        return $return;
    }
}
?>