<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\App;
use Quid\Core;

// cliClearCache
// class for a cli route to remove all cached data
class CliClearCache extends Core\RouteAlias
{
    // trait
    use Core\Route\_cli;
    use Core\Route\_cliClear;


    // config
    protected static array $config = [
        'path'=>['-clearcache'],
        'priority'=>8002,
        'parent'=>CliClearAll::class,
        'clear'=>[
            '[storageCache]',
            '[publicCss]',
            '[publicJs]',
            '[publicMedia]',
            '[publicStorage]',
            Core\Row\CacheRoute::class]
    ];
}

// init
CliClearCache::__init();
?>