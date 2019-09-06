<?php
declare(strict_types=1);
namespace Quid\Test\Site;
use Quid\Site;
use Quid\Core;
use Quid\Base;

// boot
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