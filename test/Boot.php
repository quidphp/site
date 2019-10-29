<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Test\Site;
use Quid\Base;
use Quid\Core;
use Quid\Site;

// boot
// class for testing Quid\Site\Boot
class Boot extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        $boot = $data['boot'];
        $lang = $boot->lang();

        // isApp
        assert(!$boot->isApp());

        // boot
        assert($boot->langContentClass('en') === Site\Lang\En::class);
        assert($boot->service('googleMaps') instanceof Site\Service\GoogleMaps);

        // lang
        assert($lang->existsRelation('contextType/app'));
        assert($lang->existsRelation('contextType/app','en'));
        assert(!$lang->existsRelation('contextType','en'));
        assert($lang->typeLabel('app') === 'Application');
        assert(!empty($lang->relation('contextType')));
        assert($lang->relation('contextType/app') === 'Application');
        assert($lang->relation('contextType/app','en') === 'Application');
        assert($lang->relation('jsonForm',null,true) !== $lang->relation('jsonForm',null));
        assert($lang->required(true,null,['path'=>['tables','formSubmit','json']]) === 'The form is invalid.');
        assert($lang->pathAlternateTake('required',null,['tables','formSubmit','json']) === 'The form is invalid.');
        assert(count($lang->pathAlternateValue('required','common',false,['tables','formSubmit','json'])) === 4);
        assert(count($lang->pathAlternateValue('required','common',true,['tables','formSubmit','json'])) === 2);
        assert(count($lang->pathAlternate('required',['tables','formSubmit','json'])) === 4);

        // googleAnalytics
        $ga = $boot->service('googleAnalytics');
        assert($ga->getKey() === 'googleAnalytics');
        assert($ga->apiKey() === 'UA-test');

        // googleGeocoding
        $ggValue = ['Studio OL'];
        $gg = $boot->service('googleGeocoding');
        assert($gg->getKey() === 'googleGeocoding');
        assert(is_string($gg->apiKey()));
        assert($gg->request($ggValue) instanceof Core\Request);
        assert(strpos($gg::target(['key'=>'what','value'=>$gg::prepareValue($ggValue)]),'%') === false);
        assert(is_string($gg->apiKey('key')));

        // googleMaps
        $gmValue = ['Studio OL'];
        $gm = $boot->service('googleMaps');
        assert(is_string($gm->apiKey()));
        assert($gm::uri('Studio OL') === 'https://maps.google.com/maps?q=Studio%20OL');
        assert(is_string($gm->docOpenJs()));

        // ipApi
        $ipValue = '8.8.8.8';
        $ipApi = $boot->service('ipApi');
        assert(null === $ipApi->_cast());
        assert($ipApi->request(null) instanceof Core\Request);
        assert($ipApi::target(['value'=>'ok']) === 'http://ip-api.com/json/ok');
        assert(null === $ipApi->apiKey('key'));

        // mailchimp
        $mc = $boot->service('newsletter');
        assert(is_string($mc->apiKey()));
        assert(is_string($mc->getList()));
        assert(is_string($mc->checkList()));
        assert(is_string($mc->server()));
        assert($mc->subscribedStatus() === ['pending','subscribed']);
        assert(Base\Uri::isAbsolute($mc->makeTarget('lists/list')));
        assert($mc->prepareMergeVars(['firstName'=>'LOL','ok'=>'test']) === ['ok'=>'test','FNAME'=>'LOL']);

        // pdfCrowd
        $pdf = $boot->service('pdfCrowd');
        assert(is_string($pdf->apiKey()));
        assert(is_string($pdf->username()));
        assert(is_array($pdf->userPassword()));

        // office365
        assert(Site\Service\Office365::mailto('test@test.com') === 'https://outlook.office.com/owa/?path=/mail/action/compose&to=test@test.com');
        assert(Base\Uri::output(Site\Service\Office365::mailto('test@test.com')) === 'https://outlook.office.com/owa/?path=%2Fmail%2Faction%2Fcompose&to=test%40test.com');
        $date = Base\Date::make([2018,02,02]);
        $array = ['dateStart'=>$date,'dateEnd'=>$date,'name'=>'lorem','description'=>'ok','location'=>'well'];
        assert(Site\Service\Office365::event($array) === 'https://outlook.office.com/owa/?path=/calendar/action/compose&subject=lorem&location=well&body=ok&startdt=20180202T000000-05:00&enddt=20180202T000000-05:00');

        // vimeo
        $value = 'https://vimeo.com/channels/staffpicks/259901946';
        $vimeo = Site\Service\Vimeo::class;
        assert($vimeo::target(['value'=>$value]) === 'https://vimeo.com/api/oembed.json?url=https://vimeo.com/channels/staffpicks/259901946');

        // youTube
        $value = 'https://www.youtube.com/watch?v=8HaU7Lq0tew&start_radio=1&list=RD8HaU7Lq0tew';
        $youTube = Site\Service\YouTube::class;
        assert($youTube::target(['value'=>$value]) === 'https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=8HaU7Lq0tew&start_radio=1&list=RD8HaU7Lq0tew&format=json');

        return true;
    }
}
?>