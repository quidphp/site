<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\App;
use Quid\Core;

// robots
// class for the robots.txt route of the app
class Robots extends Core\Route\Robots
{
    // config
    protected static array $config = [];
}

// init
Robots::__init();
?>