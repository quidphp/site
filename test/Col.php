<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Test\Site;
use Quid\Site;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// col
class Col extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$db = Core\Boot::inst()->db();
		$table = 'ormCol';
		$tb = $db[$table];
		$googleMaps = $tb['googleMaps'];
		$tinymce = $tb['wysiwyg'];
		$form = $tb['form'];
		$myVideo = $tb['myVideo'];

		// emailNewsletter

		// googleMaps
		$localization = new Main\Localization(['address'=>'ok','lat'=>2,'lng'=>1,'input'=>'whét asd','countryCode'=>'ca']);
		assert($googleMaps->tag() === 'textarea');
		assert(strlen($googleMaps->formComplex($localization)) === 138);
		assert(strlen($googleMaps->html($localization)) === 72);

		// hierarchy

		// jsonForm

		// jsonFormRelation

		// tinyMce
		assert($tinymce->tag() === 'textarea');
		assert(count($tinymce->formAttr()) === 1);
		assert(count($tinymce->attr()) === 32);
		assert(count($tinymce->attr('tinymce')) === 18);
		assert(strlen($tinymce->formComplex()) > 500);

		// tinyMceAdvanced

		// vimeo
		$vimeo = ['title'=>'James','html'=>'test','thumbnail_url'=>'http://image.com','provider_url'=>'https://vimeo.com','video_id'=>'132132','description'=>'bla','upload_date'=>'2018-07-25 23:30:36'];
		$video = Site\Service\Vimeo::makeVideo($vimeo);
		assert($myVideo instanceof Site\Col\Vimeo);
		assert(strlen($myVideo->formComplex($video)) === 186);

		// youTube

		// core
		assert($tb->colAttr('myVideo') === ['class'=>Site\Col\Vimeo::class]);
		assert($form instanceof Site\Col\Vimeo);
		assert($googleMaps->hasDefault());
		assert($googleMaps->hasNullDefault());
		assert(!$googleMaps->hasNotEmptyDefault());
		assert($googleMaps->default() === null);

		return true;
	}
}
?>