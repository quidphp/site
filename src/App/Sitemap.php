<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Site\App;
use Quid\Core;

// sitemap
// class for the automated sitemap.xml route of the app
class Sitemap extends Core\Route\Sitemap
{
    // config
    public static array $config = [];
}

// init
Sitemap::__init();
?>