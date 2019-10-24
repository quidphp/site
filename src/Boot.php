<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
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
        '@app'=>[
            'service'=>[
                'jQuery'=>Lemur\Service\JQuery::class],
            'sessionVersionMatch'=>false,
            'compileScss'=>[
                '[publicCss]/app.css'=>[
                    0=>'[vendorLemur]/scss/utils/_include.scss',
                    2=>'[scss]/app/_include.scss',
                    10=>'[scss]/app/app.scss']],
            'concatenateJs'=>[
                '[publicJs]/utils.js'=>[
                    0=>'[vendorLemur]/js/utils'],
                '[publicJs]/component.js'=>[
                    0=>'[vendorLemur]/js/component',
                    1=>'[vendorSite]/js/component'],
                '[publicJs]/app.js'=>'[js]/app']],
        '@cms'=>[
            'compileScss'=>[
                '[publicCss]/cms.css'=>[
                    20=>'[vendorSite]/scss/cms/form.scss']],
            'concatenateJs'=>[
                '[publicJs]/component.js'=>[
                    1=>'[vendorSite]/js/component'],
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