<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Site\Col;
use Quid\Core;
use Quid\Orm;
use Quid\Routing;

// route
// class for column that creates an enum out of a route class
class Route extends Core\Col\EnumAlias
{
    // config
    public static $config = [
        'editable'=>['role'=>['>='=>70]],
        'required'=>true,
        'relation'=>[self::class,'getRoutes'],
        'relationIndex'=>false,
        'relationSortKey'=>false,
        'routeType'=>null // type pour les routes
    ];


    // onGet
    // logique onGet pour un champ route
    // va retourner le nom de la classe à partir de l'index
    final protected function onGet($return,array $option)
    {
        if($return instanceof Core\Cell)
        {
            $value = $return->value();

            if(is_string($value))
            {
                $routes = $this->routes();
                $return = $routes->get($value);
            }
        }

        return $return;
    }


    // routeType
    // retourne le type de route
    final public function routeType():string
    {
        return $this->getAttr('routeType');
    }


    // routes
    // retourne l'objet route pour la colonne
    final public function routes():Routing\Routes
    {
        $boot = static::boot();
        $type = $this->routeType();

        if(!is_string($type))
        static::throw('invalidRouteType');

        $return = $boot->routes($type);

        return $return;
    }


    // getRoutes
    // retourne un tableau avec les routes pour le namespace
    final public static function getRoutes(Orm\ColRelation $relation):array
    {
        $return = null;
        $col = $relation->col();
        $routes = $col->routes();
        $return = $routes->toArray();

        return $return;
    }
}

// init
Route::__init();
?>