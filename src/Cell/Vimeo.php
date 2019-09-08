<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Cell;
use Quid\Core;

// vimeo
// class for dealing with a cell containing a vimeo video
class Vimeo extends Core\Cell\VideoAlias
{
	// config
	public static $config = [];
}

// config
Vimeo::__config();
?>