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
                            Test\Site::class=>['closure'=>false]]]]]],
        '@app'=>[
            'service'=>[
                'polyfill'=>Lemur\Service\Polyfill::class,
                'jQuery'=>Lemur\Service\JQuery::class],
            'sessionVersionMatch'=>false,
            'compile'=>[
                'scss'=>[
                    '[publicCss]/app.css'=>[
                        0=>'[vendorLemur]/include/css/_init.scss',
                        1=>'[scss]/app/_include.scss',
                        2=>'[component]',
                        10=>'[scss]/app/app.scss']],
                'js'=>[
                    '[publicJs]/app.js'=>[
                        0=>'[js]/app',
                        1=>'[component]',
                        2=>'[vendorLemur]/component/modal',
                        3=>'[vendorSite]/component/googleMaps']]]],
        '@cms'=>[
            'compile'=>[
                'scss'=>[
                    '[publicCss]/cms.css'=>[
                        6=>'[vendorSite]/component',
                        20=>'[vendorSite]/cms/index.scss']],
                'js'=>[
                    '[publicJs]/cms.js'=>[
                        2=>'[vendorSite]/cms',
                        3=>'[vendorSite]/component']]]]
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