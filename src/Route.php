<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site;
use Quid\Lemur;

// route
// extended abstract class for a route, adds app logic
abstract class Route extends Lemur\Route
{
    // config
    public static $config = [
        '@app'=>[
            'jsInit'=>'$(document).ready(function() { quid.core.document(this); });',
            'docOpen'=>[
                'head'=>[
                    'css'=>[
                        'type'=>'css/%type%.css'],
                    'js'=>[
                        'include'=>'js/include.js',
                        'type'=>'js/%type%.js']]]]
    ];
}

// init
Route::__init();
?>