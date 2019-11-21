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

// _pageBreadcrumbs
// trait that provides a method related to generating breadcrumbs for a page
trait _pageBreadcrumbs
{
    // getBreadcrumbs
    // génère les breadcrumbs pour la page
    final public function getBreadcrumbs():array
    {
        $return = [];
        $row = $this->row();
        $breadcrumbs = $row->breadcrumb();

        foreach ($breadcrumbs as $breadcrumb)
        {
            $return[] = $breadcrumb->route();
        }

        return $return;
    }
}
?>