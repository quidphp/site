<?php
declare(strict_types=1);
namespace Quid\Site\App;
use Quid\Core;

// robots
// class for the robots.txt route of the app
class Robots extends Core\Route\Robots
{
    // config
    public static $config = [];
}

// init
Robots::__init();
?>