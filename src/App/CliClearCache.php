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
use Quid\Base\Cli;
use Quid\Core;

// cliClearCache
// class for a cli route to remove all cached data
class CliClearCache extends Core\Route\CliClearCache
{
    // trait
    use Core\Route\_cli;


    // config
    public static $config = [];
}

// init
CliClearCache::__init();
?>