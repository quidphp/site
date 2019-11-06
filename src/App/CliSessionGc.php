<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */

namespace Quid\Site\App;
use Quid\Core;

// cliSessionGc
// class for the cli route to remove expired sessions for the app
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