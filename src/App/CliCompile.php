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

// cliCompile
// class for a cli route to compile assets (js and css)
class CliCompile extends Core\Route\CliCompile
{
    // trait
    use Core\Route\_cli;


    // config
    public static array $config = [];
}

// init
CliCompile::__init();
?>