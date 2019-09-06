<?php
declare(strict_types=1);
namespace Quid\Site\Row;
use Quid\Site;
use Quid\Core;
use Quid\Orm;
use Quid\Main;

// formSubmit
class FormSubmit extends Core\RowAlias
{
	// config
	public static $config = array(
		'cols'=>array(
			'user_id'=>array('general'=>true,'onExport'=>array(Core\Row\User::class,'userExport')),
			'json'=>array(
				'class'=>Site\Col\JsonFormRelation::class,
				'relationCols'=>array('form_id','json_fr')))
	);
	
	
	// cacheStatic
	protected static $cacheStatic = array();
	
	
	// getSubmit
	// retourne la dernière réponse d'un utilisateur au formulaire
	public static function getSubmit(Form $form,Main\Contract\User $user):?self 
	{
		return static::cacheStatic(array(__METHOD__,$form,$user),function() use($form,$user) {
			$table = static::tableFromFqcn();
			$where = array('form_id'=>$form,'user_id'=>$user);
			return $table->select($where);
		});
	}
	
	
	// hasSubmitted
	// retourne vrai si l'utilisateur a déjà soumis le formulaire
	public static function hasSubmitted(Form $form,Main\Contract\User $user):bool 
	{
		$return = false;
		$row = static::getSubmit($form,$user);
		
		if(!empty($row))
		$return = true;
		
		return $return;
	}
	
	
	// submit
	// soumet le formulaire, réponse dans un tableau en premier argument
	public static function submit(array $values,Form $form,Main\Contract\User $user,?array $option=null):bool
	{
		$return = false;
		$table = static::tableFromFqcn();
		$set = array('json'=>$values,'form_id'=>$form,'user_id'=>$user);
		
		$row = $table->insert($set,$option);
		
		if(!empty($row))
		$return = true;
		
		return $return;
	}
	
	
	// commitFinalValidate
	// gère la validation finale pour form
	// retourne une erreur s'il y a déjà une réponse de l'utilisateur pour le formulaire
	public static function commitFinalValidate(array $set,?Orm\Row $row,array $option) 
	{
		$return = null;
		$form = $set['form_id'] ?? null;
		$user = $set['user_id'] ?? null;
		
		if(!empty($form) && !empty($user))
		{
			$form = Form::row($form);
			
			if(!empty($form) && !$form->allowMultiple())
			{
				$table = static::tableFromFqcn();
				$where = array('form_id'=>$form,'user_id'=>$user);
				
				if(!empty($row))
				$where[] = array($table->primary(),'!=',$row->primary());

				$id = $table->selectPrimary($where);
				
				if(!empty($id))
				$return = 'formSubmit/duplicate';
			}
		}
		
		return $return;
	}
}

// config
FormSubmit::__config();
?>