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
use Quid\Core;

// _specific
// trait that provides basic methods used for a specific route
trait _specific
{
    // config
    public static $configSpecific = [
        'group'=>'specific'
    ];


    // selectedUri
    final public function selectedUri():array
    {
        return static::makeParent()->selectedUri();
    }


    // general
    final public function general():Core\Route
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
    final public function getBreadcrumbs():array
    {
        return [static::makeParent(),$this];
    }
}
?>