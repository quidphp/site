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
        'concatenatePhp'=>[
            'quid'=>[
                'option'=>[
                    'namespace'=>[
                        __NAMESPACE__=>['closure'=>true],
                        Test\Site::class=>['closure'=>false]]]]],
        'concatenateJs'=>[
            '[publicJs]/include.js'=>[
                1=>'[vendorSite]/js/include']],
        '@app'=>[
            'service'=>[
                'polyfill'=>Lemur\Service\Polyfill::class,
                'jQuery'=>Lemur\Service\JQuery::class],
            'sessionVersionMatch'=>false,
            'compileScss'=>[
                '[publicCss]/app.css'=>[
                    0=>'[vendorLemur]/scss/include/_init.scss',
                    2=>'[scss]/app/_include.scss',
                    3=>'[component]',
                    10=>'[scss]/app/app.scss']],
            'concatenateJs'=>[
                '[publicJs]/app.js'=>[
                    0=>'[js]/app',
                    1=>'[component]']]],
        '@cms'=>[
            'compileScss'=>[
                '[publicCss]/cms.css'=>[
                    4=>'[vendorSite]/component',
                    20=>'[vendorSite]/scss/cms/site.scss']],
            'concatenateJs'=>[
                '[publicJs]/cms.js'=>[
                    2=>'[vendorSite]/js/cms',
                    3=>'[vendorSite]/component']]]
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