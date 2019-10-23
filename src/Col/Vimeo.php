<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Col;
use Quid\Lemur;
use Quid\Site;

// vimeo
// class for a column containing a vimeo video
class Vimeo extends Lemur\Col\VideoAlias
{
    // config
    public static $config = [
        'cell'=>Site\Cell\Vimeo::class,
        'preValidate'=>['uriHost'=>'vimeo.com'],
        'group'=>'video',
        'service'=>'vimeo' // custom, clé du service utilisé
    ];
}

// init
Vimeo::__init();
?>