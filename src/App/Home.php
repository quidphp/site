<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\App;
use Quid\Core;

// home
// abstract class for the home route of the app
abstract class Home extends Core\Route\Home
{
    // config
    protected static array $config = [];
}

// init
Home::__init();
?>