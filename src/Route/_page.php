<?php
declare(strict_types=1);
namespace Quid\Site\Route;
use Quid\Site;
use Quid\Core;

// _page
trait _page
{
	// dynamique
	protected $row = null;
	
	
	// onMake
	// lors de la construction de la route
	protected function onMake():void
	{
		$this->makeRow();
		
		return;
	}
	
	
	// onBefore
	// avant le lancement de la route
	protected function onBefore() 
	{
		$return = parent::onBefore();
		
		if($return !== false)
		{
			if($this->rowExists() && $this->row()->isVisible())
			$return = true;
		}
		
		return $return;
	}
	
	
	// rowExists
	// retourne vrai si la row existe
	public function rowExists():bool 
	{
		return (!empty($this->row))? true:false;
	}
	
	
	// row
	// retourne la row
	public function row():Core\Row
	{
		return $this->row;
	}
	
	
	// makeRow
	// construit l'objet row pour la route
	protected function makeRow():self 
	{
		$row = static::$config['rowObj'] ?? null;
		if(is_int($row))
		$return = $this->row = Site\Row\Page::select($row);
		
		return $this;
	}
}
?>