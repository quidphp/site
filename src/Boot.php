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
        'compile'=>[
            'php'=>[
                'quid'=>[
                    'option'=>[
                        'namespace'=>[
                            __NAMESPACE__=>['closure'=>true],
                            Test\Site::class=>['closure'=>false]]]]],
            'js'=>[
                '[publicJs]/include.js'=>[
                    1=>'[js]/include'],
                '[publicJs]/component.js'=>[
                    1=>'[vendorSite]/js/component',
                    2=>'[js]/component']]],
        '@dev'=>array(
            'compile'=>[
                'js'=>[
                    '[publicJs]/test.js'=>[
                        1=>'[vendorSite]/js/test',
                        2=>'[js]/test']]]),
        '@app'=>[
            'service'=>[
                'polyfill'=>Lemur\Service\Polyfill::class,
                'jQuery'=>Lemur\Service\JQuery::class],
            'sessionVersionMatch'=>false,
            'compile'=>[
                'scss'=>[
                    '[publicCss]/app.css'=>[
                        0=>'[vendorLemur]/css/include',
                        1=>'[css]/import',
                        2=>'[css]/app']],
                'js'=>[
                    '[publicJs]/app.js'=>[
                        0=>'[js]/app']]]],
        '@cms'=>[
            'compile'=>[
                'scss'=>[
                    '[publicCss]/cms.css'=>[
                        2=>'[vendorSite]/css/import',
                        20=>'[vendorSite]/css/cms'],
                    '[publicCss]/tinymce.css'=>[
                        10=>'[css]/tinymce']],
                'js'=>[
                    '[publicJs]/cms.js'=>[
                        1=>'[vendorSite]/js/cms']]]]
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