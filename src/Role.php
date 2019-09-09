<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site;
use Quid\Lemur;

// role
// extended abstract class that provides app logic for a role
abstract class Role extends Lemur\Role
{
	// config
	public static $config = [
		'can'=>[
			'login'=>['app'=>false]
		]
	];
}

// config
Role::__config();
?>