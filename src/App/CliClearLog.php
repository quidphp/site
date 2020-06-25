<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\App;
use Quid\Core;

// cliClearLog
// class for a cli route to remove all log data
class CliClearLog extends Core\RouteAlias
{
    // trait
    use Core\Route\_cli;
    use Core\Route\_cliClear;


    // config
    protected static array $config = [
        'path'=>['-clearlog'],
        'clear'=>[
            '[storageLog]',
            '[storageError]',
            Core\Row\Log::class,
            Core\Row\LogCron::class,
            Core\Row\LogEmail::class,
            Core\Row\LogError::class,
            Core\Row\LogHttp::class,
            Core\Row\LogSql::class],
        'logCron'=>null
    ];
}

// init
CliClearLog::__init();
?>