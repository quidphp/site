<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Service;
use Quid\Main;

// googleAnalytics
// class that provides some methods to integrate googleAnalytics tracking
class GoogleAnalytics extends Main\ServiceRequest
{
    // config
    public static $config = [
        'uri'=>'https://www.google-analytics.com/analytics.js' // uri vers les cript analytics
    ];


    // apiKey
    // retourne la clé d'api
    public function apiKey():string
    {
        return $this->getOption('key');
    }


    // docOpenScript
    // retourne le script en début de document
    public function docOpenScript()
    {
        $return = "\n";
        $uri = $this->getAttr('uri');
        
        $return .= "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){\n";
        $return .= "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\n";
        $return .= "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\n";
        $return .= "})(window,document,'script','".$uri."','ga');\n";
        $return .= "ga('create', '".$this->apiKey()."', 'auto');\n";
        $return .= "ga('send', 'pageview');\n";

        return $return;
    }
}

// init
GoogleAnalytics::__init();
?>