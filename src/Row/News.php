<?php
declare(strict_types=1);
namespace Quid\Site\Row;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// news
class News extends Core\RowAlias implements Main\Contract\Meta
{
	// trait
	use _meta;
	
	
	// config
	public static $config = array(
		'key'=>array('slug_[lang]',0),
		'@app'=>array(
			'order'=>array('date'=>'desc'),
			'where'=>array(array('datetimeStart','<=',array(Base\Date::class,'timestamp'))))
	);
	
	
	// isVisible
	// retourne vrai si la nouvelle est visible
	public function isVisible():bool 
	{
		return (parent::isVisible() && $this['datetimeStart']->isAfter())? true:false;
	}
}

// config
News::__config();
?>