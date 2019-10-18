<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site;
use Quid\Lemur;

// db
// extended class used to query the database, adds app logic
class Db extends Lemur\Db
{
    // config
    public static $config = [
        'option'=>[
            'cols'=>[
                'content_en'=>['class'=>Col\TinyMce::class],
                'content_fr'=>['class'=>Col\TinyMce::class],
                'content'=>['class'=>Col\TinyMce::class],
                'googleMaps'=>['class'=>Col\GoogleMaps::class,'panel'=>'localization'],
                'youTube'=>['class'=>Col\YouTube::class,'general'=>false,'panel'=>'media'],
                'vimeo'=>['class'=>Col\Vimeo::class,'general'=>false,'panel'=>'media']]]
    ];
}

// init
Db::__init();
?>