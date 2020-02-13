<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Site\Cms;
use Quid\Lemur;
use Quid\Site;

// cliPreload
// class for the cli route to generate the preload PHP script for the CMS
class CliPreload extends Lemur\Cms\CliPreload
{
    // config
    public static $config = [
        'compile'=>[
            'closure'=>[
                'from'=>[
                    Site::class=>['closure'=>true]]]]
    ];
}

// init
CliPreload::__init();
?>