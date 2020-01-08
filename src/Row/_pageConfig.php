<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
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
    public static $configPageConfig = [
        'route'=>[
            'app'=>[self::class,'dynamicRoute']],
        'cols'=>[
            'route'=>['class'=>Site\Col\Route::class,'routeType'=>'app']],
        'routeKey'=>'app', // custom
        '@app'=>[
            'route'=>[
                0=>[self::class,'dynamicRoute']]],
    ];


    // dynamique
    protected $routePrepared = false; // garde en mémoire si la route a été préparé


    // isRoutePrepared
    // retourne vrai la route a été préparé
    final public function isRoutePrepared():bool
    {
        return ($this->routePrepared === true)? true:false;
    }


    // setRoutePrepared
    // permet de mettre si la route a été préparé ou non
    final protected function setRoutePrepared(bool $value=true):void
    {
        $this->routePrepared = $value;

        return;
    }


    // shouldPrepareRoute
    // retourne vrai si la route doit être préparé
    // la route doit se retrouver dans l'objet routes qui vient de routesCanPrepare
    final public function shouldPrepareRoute($key,string $route):bool
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
    // n'inclut pas la route page dynamique, car plusieurs uris peuvent utilisateur cette route
    final public static function getRoutesCanPrepare():?Routing\Routes
    {
        $return = static::boot()->routes(static::getRouteKey());
        $dynamic = static::dynamicPageClass();
        
        if(!empty($dynamic))
        {
            $dynamic = (array) $dynamic;
            $return = $return->not(...$dynamic);
        }
        
        return $return;
    }


    // routePrepareConfig
    // méthode principale pour préparer une route
    // va appeler routeConfig si la route est visible
    final protected function routePrepareConfig($key,string $route):void
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
                    $path = [$cell];
                    $return['path'][$key] = Base\Path::append(...$path);
                }
            }
        }
        
        return $return;
    }
    
    
    // getRouteKey
    // retourne le type pour la route
    final public static function getRouteKey():?string
    {
        return static::$config['routeKey'] ?? null;
    }


    // prepareRoutes
    // prépare les pages des routes
    final public static function prepareRoutes():void
    {
        $routes = static::getRoutesCanPrepare();

        if(!empty($routes))
        static::prepareRoutesObject($routes);

        return;
    }


    // prepareRoutesObject
    // permet de préparer toutes les pages des routes de l'objet routes
    final protected static function prepareRoutesObject(Routing\Routes $return):Routing\Routes
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
                    $return = $route::make($page);
                    break;
                }
            }
        }
        
        if($return === null && is_subclass_of($route,Core\Route::class,true))
        $return = $route::make();

        return $return;
    }
    
    
    // dynamicPageClass
    // retourne le nom de la classe de la route de page dynamique
    // peut retourner une string ou un array
    public static function dynamicPageClass()
    {
        return null;
    }
}
?>