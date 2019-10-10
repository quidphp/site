<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\App;
use Quid\Core;

// home
// abstract class for the home route of the CMS
abstract class Home extends Core\Route\Home
{
    // config
    public static $config = [];
}

// init
Home::__init();
?>