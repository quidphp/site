<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Row;
use Quid\Base;
use Quid\Core;
use Quid\Routing;
use Quid\Site;

// _pageConfig
// trait related to the configuration of a row representing a page
trait _pageConfig
{
    // config
    protected static array $configPageConfig = [
        'route'=>[
            'app'=>[self::class,'dynamicRoute']],
        'cols'=>[
            'route'=>['class'=>Site\Col\Route::class,'routeType'=>'app']],
        '@app'=>[
            'route'=>[
                0=>[self::class,'dynamicRoute']]],
    ];


    // dynamique
    protected bool $routePrepared = false; // garde en mémoire si la route a été préparé


    // isRoutePrepared
    // retourne vrai la route a été préparé
    final public function isRoutePrepared():bool
    {
        return $this->routePrepared === true;
    }


    // setRoutePrepared
    // permet de mettre si la route a été préparé ou non
    final protected function setRoutePrepared(bool $value=true):void
    {
        $this->routePrepared = $value;
    }


    // shouldPrepareRoute
    // retourne vrai si la route doit être préparé
    // la route doit se retrouver dans l'objet routes qui vient de routesCanPrepare
    final public function shouldPrepareRoute($key,string $route):bool
    {
        $return = false;
        $routeKey = $this->getRouteKey();

        if(!$this->isRoutePrepared() && !empty($routeKey) && $key === $routeKey && is_subclass_of($route,Core\Route::class,true))
        {
            $routes = static::getRoutesCanPrepare($routeKey);
            $fqcn = $route::classFqcn();
            $return = (!empty($routes) && $routes->in($fqcn));
        }

        return $return;
    }


    // getRoutesCanPrepare
    // retourne toutes les routes qui doivent être préparés
    // n'inclut pas la route page dynamique, car plusieurs uris peuvent utilisateur cette route
    final public static function getRoutesCanPrepare(string $routeKey):?Routing\Routes
    {
        $return = static::boot()->routes($routeKey);
        $dynamic = static::dynamicPageClass();

        if(!empty($dynamic))
        {
            $dynamic = (array) $dynamic;
            $return = $return->filterReject(...$dynamic);
        }

        return $return;
    }


    // routePrepareConfig
    // méthode principale pour préparer une route
    // va appeler routeConfig si la route est visible
    final protected function routePrepareConfig($key,string $route):void
    {
        $config = [];
        $config['label'] = $this->cellName();
        $shouldConfig = $this->isVisible() && $route::classHasMethod('shouldConfigPath') && $route::shouldConfigPath();

        if($shouldConfig === true)
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

        $config = $this->routeFinishConfig($config,$route,$shouldConfig);
        $config = Base\Obj::cast($config);
        $config['rowObj'] = $this;

        $route::config($config);
        $this->setRoutePrepared(true);
    }


    // routeFinishConfig
    // méthode à étendre pour finir la configuration de la route
    protected function routeFinishConfig($return,string $route,bool $shouldConfig):array
    {
        return $return;
    }


    // routeConfig
    // configuration supplémentaire pour la route
    // peut être étendu dans un trait ou classe
    final public function routeConfig(array $return):array
    {
        return $this->routePathConfig($return);
    }


    // routePathConfig
    // fait la configuration du chemin sur la route non dynamique
    final protected function routePathConfig(array $return):array
    {
        $lang = $this->db()->lang();

        foreach ($lang->allLang() as $key)
        {
            foreach (["slug_$key","slugPath_$key"] as $value)
            {
                if($this->hasCell($value))
                {
                    $cell = $this->cell($value);

                    if($cell->isNotEmpty())
                    {
                        $path = [$cell];
                        $return['path'][$key] = Base\Path::append(...$path);
                    }
                }
            }
        }

        return $return;
    }


    // getRouteKey
    // retourne le type pour la route
    public function getRouteKey():string
    {
        return $this->cell('route')->col()->routeType();
    }


    // getViewRouteType
    // retourne le type à utiliser pour voir la route via la page spécifique du cms
    final public function getViewRouteType():?string
    {
        return $this->getRouteKey();
    }


    // prepareRoutes
    // prépare les pages des routes
    final public static function prepareRoutes(string $type):void
    {
        $routes = static::getRoutesCanPrepare($type);

        if(!empty($routes))
        static::prepareRoutesObject($type,$routes);
    }


    // prepareRoutesObject
    // permet de préparer toutes les pages des routes de l'objet routes
    final protected static function prepareRoutesObject(string $routeKey,Routing\Routes $return):Routing\Routes
    {
        $table = static::tableFromFqcn();
        $where = [true];
        $keys = $return->keys();
        $where[] = ['route','in',$keys];

        foreach ($table->selects($where) as $page)
        {
            $page->routeSafe($routeKey);
        }

        return $return;
    }


    // dynamicRoute
    // gère la création de la route pour la page
    // si make est false, retourne le nom de la classe plutôt que l'objet
    final public static function dynamicRoute(self $page,bool $make=true)
    {
        $return = null;
        $value = $page['route']->get();

        if(!empty($value))
        {
            $return = $value;

            if($make === true)
            $return = static::dynamicRouteMake($return,$page);
        }

        return $return;
    }


    // dynamicRouteMake
    // génère la route de la page ou de la page dynamique, dépendamment de la classe
    // la page dynamique a un segment et peut avoir différentes uris de page
    final public static function dynamicRouteMake(string $route,self $page):?Core\Route
    {
        $return = null;
        $class = static::dynamicPageClass();

        if(!empty($class))
        {
            $classes = (array) $class;
            foreach ($classes as $class)
            {
                if(is_a($route,$class,true))
                {
                    $return = $route::make($page,false);
                    break;
                }
            }
        }

        if($return === null && is_subclass_of($route,Core\Route::class,true))
        $return = $route::make(null,false);

        return $return;
    }


    // dynamicPageClass
    // retourne le nom de la classe de la route de page dynamique
    // peut retourner une string ou un array
    public static function dynamicPageClass()
    {
        return;
    }
}
?>