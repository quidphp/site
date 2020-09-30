<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site;
use Quid\Lemur;

// route
// extended abstract class for a route, adds app logic
abstract class Route extends Lemur\Route
{
    // config
    protected static array $config = [
        '@app'=>[
            'jsInit'=>'Quid.InitDoc();',
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