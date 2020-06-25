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

// vimeo
// class for a column containing a Vimeo video
class Vimeo extends Lemur\Col\VideoAlias
{
    // config
    protected static array $config = [
        'preValidate'=>['uriHost'=>'vimeo.com'],
        'services'=>[Site\Service\Vimeo::class] // custom, classe du service utilisé
    ];
}

// init
Vimeo::__init();
?>