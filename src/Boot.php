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
            
        'compileJs'=>array(
            'include'=>array(
                'from'=>array(
                    1=>'[js]/include')),
            'component'=>array(
                'from'=>array(
                    1=>'[vendorSite]/js/component',
                    2=>'[js]/component'))),
                    
        '@dev'=>[
            'compileJs'=>[
                'test'=>[
                    'from'=>array(
                        1=>'[vendorSite]/js/test',
                        2=>'[js]/test')]]],

        '@app'=>[
            'service'=>[
                'polyfill'=>Lemur\Service\Polyfill::class,
                'jQuery'=>Lemur\Service\JQuery::class],
            'sessionVersionMatch'=>false,
            
            'compileCss'=>[
                'app'=>array(
                    'to'=>'[publicCss]/app.css',
                    'from'=>array(
                        0=>'[vendorLemur]/css/include',
                        1=>'[css]/include',
                        2=>'[css]/import',
                        3=>'[css]/app'))],
                        
            'compileJs'=>[
                'app'=>array(
                    'to'=>'[publicJs]/app.js',
                    'from'=>array(
                        0=>'[js]/app'))]],

        '@cms'=>[
            'compileCss'=>[
                'cms'=>array(
                    'from'=>array(
                        3=>'[vendorSite]/css/import',
                        20=>'[vendorSite]/css/cms',
                        40=>'[css]/cms')),
                'tinymce'=>array(
                    'from'=>array(
                        5=>'[css]/include',
                        10=>'[css]/tinymce'))],
                        
            'compileJs'=>[
                'cms'=>array(
                    'from'=>array(
                        1=>'[vendorSite]/js/cms'))]]
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