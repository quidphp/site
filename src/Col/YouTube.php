<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Col;
use Quid\Lemur;
use Quid\Site;

// youTube
// class for a column containing a YouTube video
class YouTube extends Lemur\Col\VideoAlias
{
    // config
    protected static array $config = [
        'preValidate'=>['uriHost'=>['youtube.com','www.youtube.com']],
        'services'=>[Site\Service\YouTube::class] // custom, classe du service utilisé
    ];
}

// init
YouTube::__init();
?>