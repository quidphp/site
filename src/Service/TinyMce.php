<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Service;
use Quid\Core;

// tinyMce
// class that provides a method to integrate tinyMce WYSIWYG editor
class TinyMce extends Core\ServiceAlias
{
    // config
    public static $config = [];


    // docOpenJs
    // retourne le javascript à lier en début de document
    public function docOpenJs()
    {
        return 'js/tinymce/tinymce.min.js';
    }
}

// config
TinyMce::__config();
?>