<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
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