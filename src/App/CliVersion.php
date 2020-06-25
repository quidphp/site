<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\App;
use Quid\Core;

// cliVersion
// class for a version route of the app, accessible via the cli
class CliVersion extends Core\Route\CliVersion
{
    // trait
    use Core\Route\_cli;


    // config
    protected static array $config = [
        'priority'=>800
    ];
}

// init
CliVersion::__init();
?>