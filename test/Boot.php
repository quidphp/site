<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Test\Site;
use Quid\Site;
use Quid\Core;
use Quid\Base;

// boot
// class for testing Quid\Site\Boot
class Boot extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$boot = $data['boot'];

		// boot
		assert($boot->langContentClass('en') === Site\Lang\En::class);
		assert($boot->service('googleMaps') instanceof Core\ServiceRequest);

		return true;
	}
}
?>