<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Cms;
use Quid\Lemur;
use Quid\Site;

// cliPreload
// class for a cli route to generate the preload PHP script for the CMS
class CliPreload extends Lemur\Cms\CliPreload
{
    // config
    protected static array $config = [
        'compile'=>[
            'closure'=>[
                'from'=>[
                    Site::class=>['closure'=>true]]]]
    ];
}

// init
CliPreload::__init();
?>