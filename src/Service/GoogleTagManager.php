<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Service;
use Quid\Base\Html;
use Quid\Main;

// googleTagManager
// class that provides some methods to integrate GoogleTagManager platform
class GoogleTagManager extends Main\ServiceRequest
{
    // config
    protected static array $config = [
        'uri'=>'https://www.googletagmanager.com/gtm.js', // uri vers le script
        'uriNoScript'=>'https://www.googletagmanager.com/ns.html'
    ];


    // apiKey
    // retourne la clé d'api
    final public function apiKey():string
    {
        return $this->getAttr('key');
    }


    // docOpenScript
    // retourne le script en début de document
    final public function docOpenScript():string
    {
        $return = "\n";
        $key = $this->apiKey();
        $uri = $this->getAttr('uri');

        $return .= "(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':\n";
        $return .= "new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],\n";
        $return .= "j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=\n";
        $return .= "'".$uri."?id='+i+dl;f.parentNode.insertBefore(j,f);\n";
        $return .= "})(window,document,'script','dataLayer','".$key."');\n";

        return $return;
    }


    // bodyNoscript
    // génère le tag noscript pour le body
    final public function bodyNoscript():string
    {
        $key = $this->apiKey();
        $uri = $this->getAttr('uriNoScript');
        $html = "<iframe src='".$uri.'?id='.$key."' height='0' width='0' style='display:none; visibility:hidden'></iframe>";

        return Html::noscript($html);
    }
}

// init
GoogleTagManager::__init();
?>