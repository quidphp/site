<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Site\Col;
use Quid\Lemur;
use Quid\Site;

// vimeo
// class for a column containing a Vimeo video
class Vimeo extends Lemur\Col\VideoAlias
{
    // config
    public static array $config = [
        'cell'=>Site\Cell\Vimeo::class,
        'preValidate'=>['uriHost'=>'vimeo.com'],
        'group'=>'video',
        'service'=>Site\Service\Vimeo::class // custom, classe du service utilisé
    ];
}

// init
Vimeo::__init();
?>