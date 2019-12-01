<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
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
            'jsInit'=>'$(document).ready(function() { Lemur.Evt.triggerSetup(Lemur.Component.Document.call(this)); });',
            'docOpen'=>[
                'head'=>[
                    'css'=>[
                        'type'=>'css/%type%.css'],
                    'js'=>[
                        'include'=>'js/include.js',
                        'component'=>'js/component.js',
                        'type'=>'js/%type%.js']]]]
    ];
}

// init
Route::__init();
?>