<?php
declare(strict_types=1);
namespace Quid\Site\Service;
use Quid\Core;
use Quid\Base;

// pdfCrowd
class PdfCrowd extends Core\ServiceRequestAlias
{
	// config
	public static $config = array(
		'target'=>'http://api.pdfcrowd.com/convert/', // uri target pour PdfCrowd
		'option'=>array(
			'responseCode'=>null,
			'post'=>array( // données post par défaut
				'input_format'=>'html',
				'output_format'=>'pdf',
				'page_size'=>'letter',
				'orientation'=>'portrait'))
	);
	
	
	// apiKey
	// retourne la clé d'api
	public function apiKey():string 
	{
		return $this->getOption('key');
	}
	
	
	// username
	// retourne le username de l'api
	public function username():string 
	{
		return $this->getOption('username');
	}
	
	
	// userPassword
	// retourne la tableau user password pour curl
	public function userPassword():array 
	{
		return array($this->username(),$this->apiKey());
	}
	
	
	// checkType
	// envoie une exception si le type n'est pas supporté
	protected function checkType(string $type):self 
	{
		if(!in_array($type,array('url','text'),true))
		static::throw();
		
		return $this;
	}
	
	
	// prepareOption
	// prépare le tableau option avant de le joindre à la requête
	protected function prepareOption(?array $option=null):array 
	{
		$return = Base\Arr::plus($this->option(),$option);
		$return = Base\Arr::keysStrip(array('username','key','post'),$return);
		$return['userPassword'] = $this->userPassword();
		
		return $return;
	}
	
	
	// preparePost
	// prépare le tableau post avant de le joindre à la requête
	protected function preparePost(string $type,$value,?array $post=null):array 
	{
		$return = array();
		$this->checkType($type);
		$value = Base\Obj::cast($value);
		
		$return = Base\Arr::plus($this->getOption('post'),$post);
		$return[$type] = $value;
		
		return $return;
	}
	
	
	// request
	// retourne la requête à utiliser pour aller chercher une resource auprès de pdfCrowd
	public function request(string $type,$value,?array $post=null,?array $option=null):Core\Request 
	{
		$return = null;
		$option = $this->prepareOption($option);
		
		$request = array();
		$request['uri'] = static::target();
		$request['post'] = $this->preparePost($type,$value,$post);
		$return = static::makeRequest($request,$option);
		
		return $return;
	}
	
	
	// convertUri
	// lance une requête à pdfCrowd pour convertir une uri
	// retourne un objet de réponse
	public function convertUri($value,?array $post=null,?array $option=null):Core\Response 
	{
		$return = null;
		
		if($value instanceof Core\Request)
		$value = $value->absolute();
		
		$request = $this->request('url',$value,$post,$option);
		$return = $request->trigger();
		
		return $return;
	}
	
	
	// convertString
	// lance une requête à pdfCrowd pour convertir une string
	// retourne un objet de réponse
	public function convertString($value,?array $post=null,?array $option=null):Core\Response
	{
		$return = false;
		
		if($value instanceof Core\File)
		$value = $value->read();
		
		$request = $this->request('text',$value,$post,$option);
		$return = $request->trigger();
		
		return $return;
	}
}

// config
PdfCrowd::__config();
?>