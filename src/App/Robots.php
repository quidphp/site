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

// robots
// class for the robots.txt route of the app
class Robots extends Core\Route\Robots
{
    // config
    public static $config = [];
}

// init
Robots::__init();
?>