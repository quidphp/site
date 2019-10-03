<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Cell;
use Quid\Core;

// youTube
// class for working with a cell containing a youTube video
class YouTube extends Core\Cell\VideoAlias
{
    // config
    public static $config = [];
}

// init
YouTube::__init();
?>