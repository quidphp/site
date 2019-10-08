<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site;
use Quid\Core;
use Quid\Lemur;
use Quid\Test;

// boot
// extended abstract class for the object that bootstraps the app and cms
abstract class Boot extends Lemur\Boot
{
    // config
    public static $config = [
        'types'=>['app','cms'],
        'finderShortcut'=>[
            'vendorSite'=>'[vendor]/quidphp/site'],
        'symlink'=>[
            '[vendorSite]/js/vendor/tinymce'=>'[publicJs]/tinymce'],
        'concatenatePhp'=>[
            'quid'=>[
                'option'=>[
                    'namespace'=>[
                        __NAMESPACE__=>['closure'=>true],
                        Test\Site::class=>['closure'=>false]]]]],
        'config'=>[
            Core\Db::class=>[
                'option'=>[
                    'cols'=>[
                        'content_en'=>['class'=>Col\TinyMce::class],
                        'content_fr'=>['class'=>Col\TinyMce::class],
                        'content'=>['class'=>Col\TinyMce::class],
                        'googleMaps'=>['class'=>Col\GoogleMaps::class,'panel'=>'localization'],
                        'youTube'=>['class'=>Col\YouTube::class,'general'=>false,'panel'=>'media'],
                        'vimeo'=>['class'=>Col\Vimeo::class,'general'=>false,'panel'=>'media']]]]],
        '@app'=>[
            'service'=>[
                'jQuery'=>Lemur\Service\JQuery::class],
            'sessionVersionMatch'=>false,
            'config'=>[
                Core\Route::class=>[
                    'jsInit'=>'$(document).ready(function() { $(this).navigation(); });',
                    'docOpen'=>[
                        'head'=>[
                            'css'=>[
                                'type'=>'css/%type%.css'],
                            'js'=>[
                                'include'=>'js/include.js',
                                'type'=>'js/%type%.js']]]]],
            'compileScss'=>[
                '[publicCss]/app.css'=>[
                    0=>'[vendorLemur]/scss/include/_include.scss',
                    1=>'[vendorLemur]/scss/include/_component.scss',
                    2=>'[scss]/app/_include.scss',
                    3=>'[vendorLemur]/scss/vendor/normalize/normalize.css',
                    10=>'[scss]/app/app.scss']],
            'concatenateJs'=>[
                '[publicJs]/include.js'=>[
                    0=>'[vendorLemur]/js/include',
                    1=>'[vendorSite]/js/include'],
                '[publicJs]/app.js'=>'[js]/app']],
        '@cms'=>[
            'service'=>[
                'tinymce'=>Service\TinyMce::class],
            'compileScss'=>[
                '[publicCss]/cms.css'=>[
                    20=>'[vendorSite]/scss/cms/form.scss'],
                '[publicCss]/tinymce.css'=>[
                    0=>'[vendorLemur]/scss/include/_include.scss',
                    1=>'[scss]/cms/_include.scss',
                    2=>'[vendorSite]/scss/cms/tinymce.scss',
                    10=>'[scss]/cms/tinymce.scss']],
            'concatenateJs'=>[
                '[publicJs]/include.js'=>[
                    1=>'[vendorSite]/js/include'],
                '[publicJs]/cms.js'=>[
                    1=>'[vendorSite]/js/cms']]]
    ];


    // isApp
    // retourne vrai si la clé de l'application roulant présentement est app
    public function isApp():bool
    {
        return ($this->type() === 'app')? true:false;
    }
}

// init
Boot::__init();
?>