<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Col;
use Quid\Site;
use Quid\Core;

// youTube
// class for a column containing a youTube video
class YouTube extends Core\Col\VideoAlias
{
    // config
    public static $config = [
        'cell'=>Site\Cell\YouTube::class,
        'preValidate'=>['uriHost'=>['youtube.com','www.youtube.com']],
        'service'=>'youTube' // custom, clé du service utilisé
    ];
}

// config
YouTube::__config();
?>