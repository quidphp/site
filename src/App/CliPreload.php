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
use Quid\Lemur;
use Quid\Site;

// cliPreload
// class for a cli route to generate the preload PHP script
class CliPreload extends Core\Route\CliPreload
{
    // trait
    use Core\Route\_cli;


    // config
    public static $config = [
        'compile'=>[
            'closure'=>[
                'from'=>[
                    Lemur::class=>['closure'=>true],
                    Site::class=>['closure'=>true]]]]
    ];
}

// init
CliPreload::__init();
?>