<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Site;
use Quid\Lemur;

// boot
// extended abstract class for the object that bootstraps the app and cms
abstract class Boot extends Lemur\Boot
{
    // config
    public static $config = [
        'types'=>['app','cms'],
        'finderShortcut'=>[
            'vendorSite'=>'[vendor]/quidphp/site'],

        'compileJs'=>[
            'include'=>[
                'from'=>[
                    1=>'[vendorSite]/include',
                    2=>'[js]/include']],
            'component'=>[
                'from'=>[
                    2=>'[vendorSite]/js/component',
                    3=>'[js]/component']]],

        '@dev'=>[
            'compileJs'=>[
                'test'=>[
                    'from'=>[
                        2=>'[vendorSite]/js/test',
                        3=>'[js]/test']]]],

        '@app'=>[
            'service'=>[
                'polyfill'=>Lemur\Service\Polyfill::class,
                'jQuery'=>Lemur\Service\JQuery::class],
            'sessionVersionMatch'=>false,

            'compileCss'=>[
                'app'=>[
                    'to'=>'[publicCss]/app.css',
                    'from'=>[
                        0=>'[vendorLemur]/css/include',
                        1=>'[vendorLemur]/css/component',
                        2=>'[vendorSite]/css/include',
                        3=>'[vendorSite]/css/component',
                        4=>'[css]/include',
                        5=>'[css]/component',
                        10=>'[css]/app']]],

            'compileJs'=>[
                'app'=>[
                    'to'=>'[publicJs]/app.js',
                    'from'=>[
                        0=>'[vendorLemur]/js/import',
                        1=>'[js]/app']]]],

        '@cms'=>[
            'compileCss'=>[
                'cms'=>[
                    'from'=>[
                        3=>'[vendorSite]/css/component',
                        4=>'[vendorSite]/css/cms-component',
                        5=>'[css]/include',
                        6=>'[css]/component',
                        7=>'[css]/cms-component',
                        9=>'[vendorSite]/css/cms',
                        10=>'[css]/cms']],
                'tinymce'=>[
                    'from'=>[
                        1=>'[css]/include',
                        10=>'[css]/cms-tinymce']]],

            'compileJs'=>[
                'cms'=>[
                    'from'=>[
                        2=>'[vendorSite]/js/cms']]]]
    ];


    // isApp
    // retourne vrai si la clé de l'application roulant présentement est app
    final public function isApp():bool
    {
        return ($this->type() === 'app')? true:false;
    }
}

// init
Boot::__init();
?>