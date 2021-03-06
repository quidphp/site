<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\App;
use Quid\Core;

// error
// abstract class for the error route of the app
abstract class Error extends Core\Route\Error
{
    // config
    protected static array $config = [];
}

// init
Error::__init();
?>