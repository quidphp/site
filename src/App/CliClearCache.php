<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */

namespace Quid\Site\App;
use Quid\Base;
use Quid\Base\Cli;
use Quid\Core;

// cliClearCache
// class for the cli route to remove all cached data
class CliClearCache extends Core\Route\CliClearCache
{
    // config
    public static $config = [];
}

// init
CliClearCache::__init();
?>