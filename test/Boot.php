<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
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
    final public static function trigger(array $data):bool
    {
        // prepare
        $boot = $data['boot'];
        $lang = $boot->lang();

        // isApp
        assert(!$boot->isApp());

        // boot
        assert($boot->langContentClass('en') === Site\Lang\En::class);

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

        // googleGeocoding
        $ggValue = ['Studio ZZ'];
        $gg = $boot->service('googleGeocoding');
        assert($gg->getServiceKey() === 'googleGeocoding');
        assert(is_string($gg->apiKey()));
        assert($gg->request($ggValue) instanceof Core\Request);
        assert(strpos($gg::target(['key'=>'what','value'=>$gg::prepareValue($ggValue)]),'%') === false);
        assert(is_string($gg->apiKey('key')));

        // googleMaps
        $gmValue = ['Studio ZZ'];
        $gm = new Site\Service\GoogleMaps(['key'=>'googleMaps']);
        assert(is_string($gm->apiKey()));
        assert($gm::uri('Studio ZZ') === 'https://maps.google.com/maps?q=Studio%20ZZ');
        assert(is_array($gm->docOpenJs()));

        // mailchimp
        $mc = $boot->service('newsletter');
        assert(is_string($mc->apiKey()));
        assert(is_string($mc->getList()));
        assert(is_string($mc->server()));

        // pdfCrowd
        $pdf = $boot->service('pdfCrowd');
        assert(is_string($pdf->apiKey()));
        assert(is_string($pdf->username()));
        assert(is_array($pdf->userPassword()));

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