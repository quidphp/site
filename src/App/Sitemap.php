<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\App;
use Quid\Core;

// sitemap
// class for the automated sitemap.xml route of the app
class Sitemap extends Core\Route\Sitemap
{
    // config
    public static $config = [];
}

// init
Sitemap::__init();
?>