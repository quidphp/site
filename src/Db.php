<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site;
use Quid\Lemur;

// db
// extended class used to query the database, adds app config
class Db extends Lemur\Db
{
    // config
    protected static array $config = [
        'cols'=>[
            'googleMaps'=>['class'=>Col\GoogleMaps::class,'panel'=>'localization'],
            'embed'=>['class'=>Col\Embed::class,'general'=>false,'panel'=>'media'],
            'youTube'=>['class'=>Col\YouTube::class,'general'=>false,'panel'=>'media'],
            'vimeo'=>['class'=>Col\Vimeo::class,'general'=>false,'panel'=>'media']]
    ];
}

// init
Db::__init();
?>