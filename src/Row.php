<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site;
use Quid\Lemur;

// row
// extended class to represent a row within a table, adds app config
class Row extends Lemur\Row
{
    // config
    public static $config = [
        '@app'=>[
            'where'=>true,
            'search'=>false,
            'route'=>[
                'cms'=>Lemur\Cms\Specific::class]]
    ];
}

// init
Row::__init();
?>