<?php
declare(strict_types=1);
namespace Quid\Site\Route;
use Quid\Core;
use Quid\Base;

// contactSubmit
abstract class ContactSubmit extends Core\RouteAlias
{
	// trait
	use Core\Route\_formSubmit;
	
	
	// config
	public static $config = array(
		'path'=>array(
			'fr'=>'nous-joindre/soumettre',
			'en'=>'contact-us/submit'),
		'match'=>array(
			'method'=>'post'),
		'verify'=>array(
			'post'=>array('name','phone','email','message'),
			'csrf'=>true,
			'captcha'=>true,
			'genuine'=>true,
			'timeout'=>true),
		'timeout'=>array(
			'failure'=>array('max'=>8,'timeout'=>600),
			'success'=>array('max'=>2,'timeout'=>600)),
		'row'=>null
	);
	
	
	// onSuccess
	// traite le succès
	protected function onSuccess():void 
	{
		static::sessionCom()->stripFloor();
		static::timeoutIncrement('success');
		
		return;
	}
	
	
	// onFailure
	// increment le timeout et appele onFailure
	protected function onFailure():void 
	{
		static::sessionCom()->stripFloor();
		static::timeoutIncrement('failure');
		
		return;
	}
	
	
	// routeSuccess
	// retourne la route vers laquelle redirigé, home par défaut
	public function routeSuccess():Core\Route
	{
		return Home::makeOverload();
	}
	
	
	// post
	// retourne les données post pour le formulaire de contact
	protected function post():array 
	{
		$return = array();
		$request = $this->request();
		
		foreach (static::getFields() as $value) 
		{
			if(is_string($value))
			$return[$value] = (string) $request->get($value);
		}
		
		return $return;
	}
	
	
	// proceed
	// lance le processus pour le contact
	protected function proceed():?Core\Row
	{
		$return = null;
		$session = static::session();
		$table = static::tableFromRowClass();
		$post = $this->post();
		$post = $this->onBeforeCommit($post);
		
		if($post !== null)
		$return = $table->insert($post,array('com'=>true));
		
		if(empty($return))
		$this->failureComplete();
		
		else
		$this->successComplete();
		
		return $return;
	}
	
	
	// getFields
	// retourne les champs pour le formulaire
	public static function getFields():array 
	{
		return static::$config['verify']['post'];
	}
}

// config
ContactSubmit::__config();
?>