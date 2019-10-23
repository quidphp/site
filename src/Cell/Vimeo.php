<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Cell;
use Quid\Lemur;

// vimeo
// class for dealing with a cell containing a vimeo video
class Vimeo extends Lemur\Cell\VideoAlias
{
    // config
    public static $config = [];
}

// init
Vimeo::__init();
?>