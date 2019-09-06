<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site;
use Quid\Lemur;
use Quid\Core;

// boot
abstract class Boot extends Lemur\Boot
{
	// config
	public static $config = [
		'finderShortcut'=>[ // shortcut pour finder
			'vendorSite'=>'[vendor]/quidphp/site'],
		'symlink'=>[
			'[vendorSite]/js/tinymce'=>'[publicJs]/tinymce'],
		'concatenatePhp'=>[
			'quid'=>[
				'option'=>[
					'namespace'=>[
						__NAMESPACE__=>['closure'=>true],
						Test::class=>['closure'=>false]]]]],
		'config'=>[
			Core\Db::class=>[
				'option'=>[
					'cols'=>[
						'content_en'=>['class'=>Col\TinyMce::class],
						'content_fr'=>['class'=>Col\TinyMce::class],
						'content'=>['class'=>Col\TinyMce::class],
						'googleMaps'=>['class'=>Col\GoogleMaps::class,'panel'=>'localization'],
						'youTube'=>['class'=>Col\YouTube::class,'general'=>false,'panel'=>'media'],
						'vimeo'=>['class'=>Col\Vimeo::class,'general'=>false,'panel'=>'media']]]]],
		'@app'=>[
			'config'=>[
				Core\Route::class=>[
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
						'wrapper'=>['#wrapper']]]],
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
				'[publicJs]/app.js'=>'[privateJs]/app']],
		'@cms'=>[
			'config'=>[
				Core\Route::class=>[
					'docOpen'=>[
						'head'=>[
							'js'=>[
								'tinymce'=>'js/tinymce/tinymce.min.js']]]]],
			'compileScss'=>[
				'[publicCss]/cms.css'=>[
					30=>'[vendorSite]/scss/cms/form.scss'],
				'[publicCss]/tinymce.css'=>[
					0=>'[vendorLemur]/scss/include/include.scss',
					1=>'[privateScss]/cms/include.scss',
					30=>'[vendorSite]/scss/cms/tinymce.scss',
					50=>'[privateScss]/cms/tinymce.scss']],
			'concatenateJs'=>[
				'[publicJs]/include.js'=>[
					1=>'[vendorSite]/js/include'],
				'[publicJs]/cms.js'=>[
					1=>'[vendorSite]/js/cms']]]
	];
}

// config
Boot::__config();
?>