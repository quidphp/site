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

// cliSessionGc
// class for a cli route to remove expired sessions for the app
class CliSessionGc extends Core\Route\CliSessionGc
{
    // trait
    use Core\Route\_cli;


    // config
    public static $config = [];
}

// init
CliSessionGc::__init();
?>