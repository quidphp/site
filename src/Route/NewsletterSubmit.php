<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Route;
use Quid\Base\Html;
use Quid\Site;
use Quid\Core;
use Quid\Base;

// newsletterSubmit
// abstract class for a newsletter submit route
abstract class NewsletterSubmit extends Core\RouteAlias
{
	// trait
	use Core\Route\_formSubmit;


	// config
	public static $config = [
		'path'=>[
			'fr'=>'infolettre/enregistrement',
			'en'=>'newsletter/subscribe'],
		'match'=>[
			'method'=>'post'],
		'verify'=>[
			'post'=>['email','firstName','lastName'],
			'timeout'=>true,
			'genuine'=>true,
			'csrf'=>true],
		'timeout'=>[
			'trigger'=>['max'=>4,'timeout'=>600]],
		'group'=>'submit',
		'service'=>'newsletter'
	];


	// dynamique
	protected $duplicate = false; // détermine si la raison de l'échec est un duplicata


	// onSuccess
	// traite le succès
	protected function onSuccess():void
	{
		$com = static::sessionCom();
		static::timeoutReset('trigger');
		$com->pos('newsletter/subscribe/success');

		return;
	}


	// onFailure
	// traite l'erreur
	protected function onFailure():void
	{
		$com = static::sessionCom();

		if($this->duplicate === true)
		$com->neg('newsletter/subscribe/duplicate');
		else
		$com->neg('newsletter/subscribe/failure');

		return;
	}


	// setFlash
	// conserve les données flash, seulement si duplicate est false
	protected function setFlash():void
	{
		if($this->duplicate === false)
		$this->session()->flashPost($this);

		return;
	}


	// routeSuccess
	// retourne la route vers laquelle redirigé
	public function routeSuccess():Core\Route
	{
		return Home::makeOverload();
	}


	// getService
	// retourne le service à utiliser pour l'enregistrement à l'infolettre
	public function getService():Site\Contract\Newsletter
	{
		return $this->service(static::$config['service']);
	}


	// post
	// retourne les données post pour l'enregistrement à l'infolettre
	protected function post():array
	{
		$return = [];
		$request = $this->request();

		foreach (static::getFields() as $value)
		{
			if(is_string($value))
			$return[$value] = (string) $request->get($value);
		}

		return $return;
	}


	// proceed
	// lance l'opération d'enregistrement
	protected function proceed():bool
	{
		$return = false;
		$service = $this->getService();
		$post = $this->post();
		$post = $this->onBeforeCommit($post);

		if($post !== null)
		{
			$email = static::email($post);
			$vars = static::vars($post);

			if($service->isSubscribed($email))
			$this->duplicate = true;

			else
			$return = $service->subscribeBool($email,$vars,null,false);
		}

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


	// getFieldsInfo
	// retourne les informations détaillés des champs pour le formulaire
	public static function getFieldsInfo():array
	{
		$return = [];

		foreach (static::getFields() as $key)
		{
			$array = [];
			$array['method'] = 'inputText';
			$array['attr']['name'] = $key;
			$array['attr']['placeholder'] = static::langText(['newsletter',$key]);
			$array['attr']['data-required'] = true;

			if($key === 'email')
			{
				$array['method'] = 'inputEmail';
				$array['attr']['data-pattern'] = Base\Validate::pattern('email');
			}

			$return[$key] = $array;
		}

		return $return;
	}


	// makeForm
	// génère le formulaire pour l'inscription à l'infolettre
	public static function makeForm(?array $flash=null):string
	{
		$r = '';

		foreach (static::getFieldsInfo() as $key => $array)
		{
			$value = (is_array($flash) && array_key_exists($key,$flash))? $flash[$key]:null;
			$method = $array['method'];

			$r .= Html::divOp('field');
			$r .= Html::$method($value,$array['attr']);
			$r .= Html::divCl();
		}

		return $r;
	}


	// email
	// retourne le email à partir d'un tableau post
	protected static function email(array $post):string
	{
		return $post['email'] ?? null;
	}


	// vars
	// retourne les variables vars pour l'enregistrement à l'infolettre à partir d'un tableau post
	protected static function vars(array $post):array
	{
		return Base\Arr::keyStrip('email',$post);
	}
}

// config
NewsletterSubmit::__config();
?>