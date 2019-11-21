<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Site\Cell;
use Quid\Lemur;

// youTube
// class for working with a cell containing a youTube video
class YouTube extends Lemur\Cell\VideoAlias
{
    // config
    public static $config = [];
}

// init
YouTube::__init();
?>