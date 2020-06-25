<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\App;
use Quid\Base\Cli;
use Quid\Core;

// cliCompile
// class for a cli route to compile assets (js and css)
class CliCompile extends Core\Route\CliCompile
{
    // trait
    use Core\Route\_cli;


    // config
    protected static array $config = [];
}

// init
CliCompile::__init();
?>