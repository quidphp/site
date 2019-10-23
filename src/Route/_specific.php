<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Route;
use Quid\Core;

// _specific
// trait that provides most methods used for a specific route
trait _specific
{
    // config
    public static $configSpecific = [
        'group'=>'specific'
    ];


    // selectedUri
    public function selectedUri():array
    {
        return static::makeParent()->selectedUri();
    }


    // general
    public function general():Core\Route
    {
        return $this->cache(__METHOD__,function() {
            $return = null;
            $parent = static::parent();

            if(!empty($parent))
            $return = $parent::makeGeneral();

            return $return;
        });
    }


    // getBreadcrumbs
    public function getBreadcrumbs():array
    {
        return [static::makeParent(),$this];
    }


    // makeSpecificNav
    // génère la nav à partir d'un objet route vers general
    protected function makeSpecificNav(Core\Route $general,Core\Row $row,string $segment,?string $highlightSegment=null,?array $attr=null):array
    {
        $return = [];
        $sql = $general->sql();
        $specific = $sql->specific($row);
        $attributes = [];

        foreach (['first','prev','count','next','last','back'] as $v)
        {
            $attributes[$v] = [$v];

            if(!empty($attr[$v]))
            $attributes[$v] = Base\Attr::append($attr[$v],$attributes[$v]);
        }

        if(!empty($specific))
        {
            if(!empty($specific['first']))
            {
                $route = $this->changeSegment($segment,$specific['first']);
                $return['first'] = $route->a(static::langText('common/first'),$attributes['first']);
            }

            if(!empty($specific['prev']))
            {
                $route = $this->changeSegment($segment,$specific['prev']);
                $return['prev'] = $route->a(static::langText('common/prev'),$attributes['prev']);
            }

            if(!empty($specific['position']) && !empty($specific['total']))
            $return['count'] = Html::span($specific['position'].' '.static::langText('lc|common/on').' '.$specific['total'],$attributes['count']);

            if(!empty($specific['next']))
            {
                $route = $this->changeSegment($segment,$specific['next']);
                $return['next'] = $route->a(static::langText('common/next'),$attributes['next']);
            }

            if(!empty($specific['last']))
            {
                $route = $this->changeSegment($segment,$specific['last']);
                $return['last'] = $route->a(static::langText('common/last'),$attributes['last']);
            }
        }

        $page = (!empty($specific['page']))? $specific['page']:1;
        if(is_int($page))
        {
            $segments = ['page'=>$specific['page']];

            if(is_string($highlightSegment))
            $segments[$highlightSegment] = $row;

            $back = $general->changeSegments($segments);
            $return['back'] = $back->a(static::langText('common/back'),$attributes['back']);
        }

        return $return;
    }
}
?>