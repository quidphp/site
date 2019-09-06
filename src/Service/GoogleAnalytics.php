<?php
declare(strict_types=1);
namespace Quid\Site\Service;
use Quid\Core;
use Quid\Base;

// googleAnalytics
class GoogleAnalytics extends Core\ServiceRequestAlias
{
	// config
	public static $config = array(
		'uri'=>'https://www.google-analytics.com/analytics.js' // uri vers les cript analytics
	);
	
	
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
		$return .= "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){\n";
		$return .= "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\n";
		$return .= "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\n";
		$return .= "})(window,document,'script','".static::$config['uri']."','ga');\n";
		$return .= "ga('create', '".$this->apiKey()."', 'auto');\n";
		$return .= "ga('send', 'pageview');\n";
		
		return $return;
	}
}

// config
GoogleAnalytics::__config();
?>