<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site;
use Quid\Lemur;

// table
// extended class to represent an existing table within a database, adds app config
class Table extends Lemur\Table
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

// config
Table::__config();
?>