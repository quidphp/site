<?php
declare(strict_types=1);
namespace Quid\Site\App;
use Quid\Core;

// error
// abstract class for the error route of the CMS
abstract class Error extends Core\Route\Error
{
    // config
    public static $config = [];
}

// init
Error::__init();
?>