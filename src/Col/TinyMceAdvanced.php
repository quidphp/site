<?php
declare(strict_types=1);
namespace Quid\Site\Col;
use Quid\Core;
use Quid\Base;

// tinyMceAdvanced
class TinyMceAdvanced extends TinyMceAlias
{
	// config
	public static $config = array(
		'tinymce'=>array( // custom, ce merge à la classe parent
			'plugins'=>"autolink code charmap fullscreen hr link lists paste print searchreplace visualblocks wordcount image media table",
			'toolbar'=>"styleselect removeformat visualblocks | bold italic underline | bullist numlist | link image media charmap hr table | searchreplace print code fullscreen",
			'media_alt_source'=>false,
			'media_poster'=>false,
			'table_appearance_options'=>false,
			'table_default_attributes'=>array('border'=>0),
			'table_default_styles'=>array('width'=>'100%','border-collapsed'=>'collapse'),
			'table_responsive_width'=>true,
			'table_advtab'=>true,
			'table_row_advtab'=>true,
			'table_cell_advtab'=>true,
			'table_style_by_css'=>true,
			'style_formats'=>array(
				20=>array('title'=>'alignLeft','wrapper'=>true,'selector'=>'*','attributes'=>array('class'=>'alignLeft')),
				21=>array('title'=>'alignCenter','wrapper'=>true,'selector'=>'*','attributes'=>array('class'=>'alignCenter')),
				22=>array('title'=>'alignRight','wrapper'=>true,'selector'=>'*','attributes'=>array('class'=>'alignRight')),
				23=>array('title'=>'floatLeft','wrapper'=>true,'selector'=>'*','attributes'=>array('class'=>'floatLeft')),
				24=>array('title'=>'floatRight','wrapper'=>true,'selector'=>'*','attributes'=>array('class'=>'floatRight'))))
	);
}

// config
TinyMceAdvanced::__config();
?>