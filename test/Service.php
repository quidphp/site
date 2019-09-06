<?php
declare(strict_types=1);
namespace Quid\Test\Site;
use Quid\Site;
use Quid\Core;
use Quid\Base;

// service
class Service extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$boot = $data['boot'];
		
		// googleAnalytics
		$ga = $boot->service('googleAnalytics');
		assert($ga->getKey() === 'googleAnalytics');
		assert($ga->apiKey() === 'UA-test');

		// googleGeocoding
		$ggValue = array('Studio OL');
		$gg = $boot->service('googleGeocoding');
		assert($gg->getKey() === 'googleGeocoding');
		assert(is_string($gg->apiKey()));
		assert($gg->request($ggValue) instanceof Core\Request);
		assert(strpos($gg::target(array('key'=>'what','value'=>$gg::prepareValue($ggValue))),'%') === false);
		assert(is_string($gg->apiKey('key')));

		// googleMaps
		$gmValue = array('Studio OL');
		$gm = $boot->service('googleMaps');
		assert(is_string($gm->apiKey()));
		assert($gm::uri('Studio OL') === "https://maps.google.com/maps?q=Studio%20OL");
		assert(is_string($gm->docOpenJs()));

		// ipApi
		$ipValue = "8.8.8.8";
		$ipApi = $boot->service('ipApi');
		assert(is_null($ipApi->_cast()));
		assert($ipApi->request(null) instanceof Core\Request);
		assert($ipApi::target(array('value'=>'ok')) === 'http://ip-api.com/json/ok');
		assert(is_null($ipApi->apiKey('key')));

		// mailchimp
		$mc = $boot->service('newsletter');
		assert(is_string($mc->apiKey()));
		assert(is_string($mc->getList()));
		assert(is_string($mc->checkList()));
		assert(is_string($mc->server()));
		assert($mc->subscribedStatus() === array('pending','subscribed'));
		assert(Base\Uri::isAbsolute($mc->makeTarget('lists/list')));
		assert($mc->prepareMergeVars(array('firstName'=>'LOL','ok'=>'test')) === array('ok'=>'test','FNAME'=>'LOL'));

		// pdfCrowd
		$pdf = $boot->service('pdfCrowd');
		assert(is_string($pdf->apiKey()));
		assert(is_string($pdf->username()));
		assert(is_array($pdf->userPassword()));
		
		// office365
		assert(Site\Service\Office365::mailto('test@test.com') === "https://outlook.office.com/owa/?path=/mail/action/compose&to=test@test.com");
		assert(Base\Uri::output(Site\Service\Office365::mailto('test@test.com')) === "https://outlook.office.com/owa/?path=%2Fmail%2Faction%2Fcompose&to=test%40test.com");
		$date = Base\Date::make(array(2018,02,02));
		$array = array('dateStart'=>$date,'dateEnd'=>$date,'name'=>'lorem','description'=>'ok','location'=>'well');
		assert(Site\Service\Office365::event($array) === "https://outlook.office.com/owa/?path=/calendar/action/compose&subject=lorem&location=well&body=ok&startdt=20180202T000000-05:00&enddt=20180202T000000-05:00");
		
		// vimeo
		$value = "https://vimeo.com/channels/staffpicks/259901946";
		$vimeo = $boot->service('vimeo');
		assert($vimeo::target(array('value'=>$value)) === 'https://vimeo.com/api/oembed.json?url=https://vimeo.com/channels/staffpicks/259901946');

		// youTube
		$value = 'https://www.youtube.com/watch?v=8HaU7Lq0tew&start_radio=1&list=RD8HaU7Lq0tew';
		$youTube = $boot->service('youTube');
		assert($youTube::target(array('value'=>$value)) === 'https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=8HaU7Lq0tew&start_radio=1&list=RD8HaU7Lq0tew&format=json');
		
		return true;
	}
}
?>