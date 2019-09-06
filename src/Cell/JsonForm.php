<?php
declare(strict_types=1);
namespace Quid\Site\Cell;
use Quid\Core;
use Quid\Base;

// jsonForm
class JsonForm extends Core\Cell\JsonArrayAlias
{
	// config
	public static $config = array();
	
	
	// questions
	// retourne les questions du formulaire
	public function questions():array
	{
		$return = array();
		$get = $this->get();
		
		if(Base\Column::is($get))
		{
			foreach ($get as $k => $v) 
			{
				if(array_key_exists('label',$v))
				$return[$k] = $v['label'];
			}
		}
		
		return $return;
	}
	
	
	// hasRequired
	// retourne vrai si le formulaire a un champ requis
	public function hasRequired():bool
	{
		$return = false;
		$get = $this->get();
		
		if(Base\Column::is($get))
		{
			foreach ($get as $v) 
			{
				if(array_key_exists('required',$v) && $v['required'] === true)
				{
					$return = true;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// makeForm
	// génère le formulaire
	public function makeForm(?array $values=null,$attr=null):array
	{
		$return = array();
		$get = $this->get();
		$name = $this->name();
		$values = (array) $values;
		$wrap = "<div class='labelDescription'>%label%%description%</div>%form%";
		$replace = array();
		
		if(Base\Column::is($get))
		{
			foreach ($get as $k => $v) 
			{
				$attr = array('name'=>$name."[$k]");
				$value = (array_key_exists($k,$values))? $values[$k]:null;
				$return[$k] = Base\Html::formWrapArray($value,$v,$wrap,$attr,$replace);
			}
		}
		
		return $return;
	}
}

// config
JsonForm::__config();
?>