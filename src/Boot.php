<?php
declare(strict_types=1);
namespace Quid\Site;
use Quid\Lemur;
use Quid\Core;

// boot
abstract class Boot extends Lemur\Boot
{
	// config
	public static $config = array(
		'finderShortcut'=>array( // shortcut pour finder
			'vendorSite'=>'[vendor]/quidphp/site'),
		'symlink'=>array(
			'[vendorSite]/js/tinymce'=>'[publicJs]/tinymce'),
		'concatenatePhp'=>array(
			'quid'=>array(
				'option'=>array(
					'namespace'=>array(
						__NAMESPACE__=>array('closure'=>true),
						Test::class=>array('closure'=>false))))),	
		'config'=>array(
			Core\Db::class=>array(
				'option'=>array(
					'cols'=>array(
						'content_en'=>array('class'=>Col\TinyMce::class),
						'content_fr'=>array('class'=>Col\TinyMce::class),
						'content'=>array('class'=>Col\TinyMce::class),
						'googleMaps'=>array('class'=>Col\GoogleMaps::class,'panel'=>'localization'),
						'youTube'=>array('class'=>Col\YouTube::class,'general'=>false,'panel'=>'media'),
						'vimeo'=>array('class'=>Col\Vimeo::class,'general'=>false,'panel'=>'media'))))),
		'@app'=>array(
			'config'=>array(
				Core\Route::class=>array(
					'metaTitle'=>['typeLabel'=>true],
					'jsInit'=>'$(document).ready(function() { $(this).navigation(); });',
					'docOpen'=>[
						'head'=>[
							'css'=>[
								'type'=>'css/%type%.css'],
							'js'=>[
								'jquery'=>'js/jquery/jquery.js',
								'include'=>'js/include.js',
								'type'=>'js/%type%.js']],
						'wrapper'=>['#wrapper']])),
			'compileScss'=>[
				'[publicCss]/app.css'=>[
					0=>'[vendorLemur]/scss/normalize/normalize.css',
					1=>'[vendorLemur]/scss/include/include.scss',
					2=>'[vendorLemur]/scss/include/component.scss',
					50=>'[privateScss]/app/app.scss']],
			'concatenateJs'=>[
				'[publicJs]/include.js'=>[
					0=>'[vendorLemur]/js/include',
					1=>'[vendorSite]/js/include'],
				'[publicJs]/app.js'=>'[privateJs]/app']),
		'@cms'=>array(
			'config'=>array(
				Core\Route::class=>array(
					'docOpen'=>array(
						'head'=>array(
							'js'=>array(
								'tinymce'=>'js/tinymce/tinymce.min.js'))))),
			'compileScss'=>array(
				'[publicCss]/cms.css'=>array(
					30=>'[vendorSite]/scss/cms/form.scss'),
				'[publicCss]/tinymce.css'=>array(
					0=>'[vendorLemur]/scss/include/include.scss',
					1=>'[privateScss]/cms/include.scss',
					30=>'[vendorSite]/scss/cms/tinymce.scss',
					50=>'[privateScss]/cms/tinymce.scss')),
			'concatenateJs'=>array(
				'[publicJs]/include.js'=>array(
					1=>'[vendorSite]/js/include'),
				'[publicJs]/cms.js'=>array(
					1=>'[vendorSite]/js/cms')))
	);
}

// config
Boot::__config();
?>