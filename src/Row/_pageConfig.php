<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Row;
use Quid\Base;
use Quid\Core;
use Quid\Routing;

// _pageConfig
// trait related to the configuration of a row representing a page
trait _pageConfig
{
    // config
    public static $configPageConfig = [
        'route'=>[
            'app'=>[self::class,'dynamicRoute']],
        'cols'=>[
            'route'=>[
                'visible'=>['role'=>['>='=>70]],
                'required'=>true]],
        'routeKey'=>'app', // custom
        '@app'=>[
            'route'=>[
                0=>[self::class,'dynamicRoute']]],
    ];


    // dynamique
    protected $routePrepared = false; // garde en mémoire si la route a été préparé


    // isRoutePrepared
    // retourne vrai la route a été préparé
    public function isRoutePrepared():bool
    {
        return ($this->routePrepared === true)? true:false;
    }


    // setRoutePrepared
    // permet de mettre si la route a été préparé ou non
    // méthode protégé
    protected function setRoutePrepared(bool $value=true):self
    {
        $this->routePrepared = $value;

        return $this;
    }


    // shouldPrepareRoute
    // retourne vrai si la route doit être préparé
    // la route doit se retrouver dans l'objet routes qui vient de routesCanPrepare
    public function shouldPrepareRoute($key,string $route):bool
    {
        $return = false;

        if(!$this->isRoutePrepared() && $key === static::getRouteKey() && is_subclass_of($route,Core\Route::class,true))
        {
            $routes = static::getRoutesCanPrepare();
            $fqcn = $route::classFqcn();

            if(!empty($routes) && $routes->in($fqcn))
            $return = true;
        }

        return $return;
    }


    // getRoutesCanPrepare
    // retourne toutes les routes qui doivent être préparés
    public static function getRoutesCanPrepare():?Routing\Routes
    {
        return static::boot()->routes(static::getRouteKey());
    }


    // routePrepareConfig
    // méthode principale pour préparer une route
    // va appeler routeConfig si la route est visible
    protected function routePrepareConfig($key,string $route):void
    {
        $config['label'] = $this->cellName();

        if($this->isVisible())
        {
            $allLang = $this->lang()->allLang();
            $config = $this->routeConfig($config);

            if($route::isGroup('home'))
            {
                if(count($allLang) === 1)
                $config['path'] = [''];
                else
                $config['path'][] = '';
            }

            elseif($route::isGroup('error'))
            $config['path'] = null;
        }

        else
        $config['sitemap'] = false;

        $config['rowObj'] = $this;
        $config = Base\Obj::cast($config);

        $route::config($config);
        $this->setRoutePrepared(true);

        return;
    }


    // routeConfig
    // configuration supplémentaire pour la route
    // peut être étendu dans un trait ou classe
    public function routeConfig(array $return):array
    {
        return $return;
    }


    // getRouteKey
    // retourne le type pour la route
    public static function getRouteKey():?string
    {
        return static::$config['routeKey'] ?? null;
    }


    // prepareRoutes
    // prépare les pages des routes
    public static function prepareRoutes():void
    {
        $routes = static::getRoutesCanPrepare();

        if(!empty($routes))
        static::prepareRoutesObject($routes);

        return;
    }


    // prepareRoutesObject
    // permet de préparer toutes les pages des routes de l'objet routes
    // méthode protégé
    protected static function prepareRoutesObject(Routing\Routes $return):Routing\Routes
    {
        $table = static::tableFromFqcn();
        $where = [true];
        $keys = $return->keys();
        $where[] = ['route','in',$keys];
        $routeKey = static::getRouteKey();
        foreach ($table->selects($where) as $page)
        {
            $page->routeSafe($routeKey);
        }

        return $return;
    }


    // dynamicRoute
    // gère la création de la route pour la page
    // si make est false, retourne le nom de la classe plutôt que l'objet
    public static function dynamicRoute(self $page,bool $make=true)
    {
        $return = null;
        $value = $page['route']->value();

        if(!empty($value))
        {
            $return = static::getDynamicRouteFromValue($value);

            if(!empty($return) && $make === true)
            $return = static::dynamicRouteMake($return,$page);
        }

        return $return;
    }


    // getDynamicRouteFromValue
    // retourne la classe de route a utilisé à partir d'une valeur route, stocké dans une base de donnée
    public static function getDynamicRouteFromValue(string $value):?string
    {
        return Base\Fqcn::append(static::routeNamespace(),ucfirst($value));
    }


    // dynamicRouteMake
    // génère la route de la page, peut être étendu
    public static function dynamicRouteMake(string $route,self $page):?Core\Route
    {
        return (is_subclass_of($route,Core\Route::class,true))? $route::make():null;
    }
}
?>