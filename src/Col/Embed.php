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

// embed
// class for a column containing an embed video (from youtube or vimeo)
class Embed extends Lemur\Col\VideoAlias
{
    // config
    protected static array $config = [
        'preValidate'=>['uriHost'=>['youtube.com','www.youtube.com','vimeo.com']],
        'services'=>[Site\Service\YouTube::class,Site\Service\Vimeo::class] // custom, classe du service utilisé
    ];
}

// init
Embed::__init();
?>