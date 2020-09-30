<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site;
use Quid\Lemur;

// boot
// extended abstract class for the object that bootstraps the app and cms
abstract class Boot extends Lemur\Boot
{
    // config
    protected static array $config = [
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
                    3=>'[vendorSite]/js/component',
                    4=>'[js]/component']],
            'app'=>[
                'to'=>'[publicJs]/app.js',
                'from'=>[
                    0=>'[vendorFront]/js/import',
                    1=>'[js]/app']]],

        'compileCss'=>[
            'app'=>[
                'to'=>'[publicCss]/app.css',
                'from'=>[
                    0=>'[vendorFront]/css/include',
                    1=>'[vendorFront]/css/component',
                    2=>'[vendorSite]/css/component',
                    3=>'[css]/include',
                    4=>'[css]/component',
                    10=>'[css]/app']]],

        '@app'=>[
            'service'=>[
                'polyfill'=>Lemur\Service\Polyfill::class],
            'sessionVersionMatch'=>false],

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
                        2=>'[css]/include',
                        10=>'[css]/cms-tinymce']]],

            'compileJs'=>[
                'cms'=>[
                    'from'=>[
                        2=>'[vendorSite]/js/cms',
                        3=>'[js]/cms']]]]
    ];


    // isApp
    // retourne vrai si la clé de l'application roulant présentement est app
    final public function isApp():bool
    {
        return $this->type() === 'app';
    }
}
?>