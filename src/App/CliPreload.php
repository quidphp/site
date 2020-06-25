<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
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
    protected static array $config = [
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