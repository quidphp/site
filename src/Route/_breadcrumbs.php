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
use Quid\Base\Html;

// _breadcrumbs
// trait that provides methods related to generating breadcrumbs
trait _breadcrumbs
{
    // getBreadcrumbs
    // retourne un tableau avec les routes breadcrumbs
    abstract public function getBreadcrumbs():array;


    // makeBreadcrumbs
    // construit les breadcrumbs pour la route
    final public function makeBreadcrumbs(string $separator='/',int $min=1,int $length=40):string
    {
        $return = '';
        $separator = Html::span($separator,'separator');
        $breadcrumbs = $this->getBreadcrumbs();

        if(count($breadcrumbs) >= $min)
        $return = static::routes()->makeBreadcrumbs($separator,$length,...$breadcrumbs);

        return $return;
    }


    // onPrepared
    // gère les selected uri, renvoie vers selectedUriBreadcrumbs
    final protected function onPrepared()
    {
        $this->selectedUriBreadcrumbs();
        
        return;
    }


    // selectedUriBreadcrumbs
    // gère les selected uri selon le breadcumbs
    final protected function selectedUriBreadcrumbs():void
    {
        foreach ($this->getBreadcrumbs() as $route)
        {
            $route->addSelectedUri();
        }

        return;
    }
}
?>