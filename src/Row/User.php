<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Site\Row;
use Quid\Lemur;

// user
// extended class for a row of the user table, with app logic
class User extends Lemur\Row\User
{
    // config
    protected static array $config = [
        'permission'=>[
            '*'=>['appLogin'=>false]]
    ];
}

// init
User::__init();
?>