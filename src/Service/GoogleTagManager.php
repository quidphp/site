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
use Quid\Routing;

// googleTagManager
// class used to generate the googleTagManager trackers
class GoogleTagManager extends Main\Service
{
    // trait
    use Routing\_service;


    // config
    protected static array $config = [
        'uri'=>'https://www.googletagmanager.com/gtm.js',
        'uriNoScript'=>'https://www.googletagmanager.com/ns.html'
    ];


    // apiKey
    // retourne la clé d'api
    final public function apiKey():string
    {
        return $this->getAttr('key');
    }


    // docOpenScript
    // code généré dans un tag script dans le head
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
    // balise noscript à générer dans le body, près de l'ouverture du coument
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