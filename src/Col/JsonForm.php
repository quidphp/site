<?php
declare(strict_types=1);
namespace Quid\Site\Col;
use Quid\Base\Html;
use Quid\Site;
use Quid\Core;
use Quid\Base;

// jsonForm
class JsonForm extends Core\Col\JsonArrayAlias
{
	// config
	public static $config = array(
		'cell'=>Site\Cell\JsonForm::class
	);
	
	
	// preValidatePrepare
	// prépare le tableau de chargement avant la prévalidation
	public function preValidatePrepare($value) 
	{
		$return = null;

		if(Base\Arrs::is($value))
		$return = Base\Column::keySwap($value);
		
		return $return;
	}
	
	
	// classHtml
	// retourne la classe additionnelle à utiliser
	public function classHtml():array
	{
		return array(static::className(true));
	}
	
	
	// prepare
	// arrange le tableau pour les méthode onGet et onSet
	protected function prepare(array $value):?array
	{
		$return = array();
		$lang = $this->db()->lang();
		
		if(Base\Arr::isAssoc($value))
		$return = $this->preValidatePrepare($value);
		
		elseif(Base\Column::is($value))
		{
			foreach ($value as $k => $v) 
			{
				if(is_int($k) && Base\Arr::keysExists(array('label','type','description','choices'),$v))
				{
					if(!empty($v['label']) && !empty($v['type']) && !empty($lang->relation(array('jsonForm',$v['type']))))
					{
						$v['required'] = (!empty($v['required']))? true:false;
						$choiceInput = static::isChoicesInput($v['type']);
						
						if($choiceInput === true && !empty($v['choices']))
						{
							if(is_string($v['choices']))
							$v['choices'] = Base\Str::lines($v['choices']);
						}
						else
						$v['choices'] = null;
						
						if($choiceInput === false || is_array($v['choices']))
						{
							$v = Base\Arr::reallyEmptyToNull($v);
							$v = Base\Arrs::trim($v);
							$return[] = $v;
						}
					}
				}
			}
		}

		if(empty($return))
		$return = null;
		
		return $return;
	}
	
	
	// makeModel
	// génère le model pour jsonForm
	public function makeModel($value,array $attr,?Core\Cell $cell=null,array $option):string
	{
		$return = Html::divOp('ele');
		$name = $attr['name'];
		$lang = $this->db()->lang();
		$choicesInput = static::getChoicesInput();
		$type = $value['type'] ?? null;
		
		if(!empty($cell) && array_key_exists('index',$option))
		$return .= $this->beforeModel($option['index'],$value,$cell);
		
		$return .= Html::divOp('current');
		$return .= Html::divOp('label');
		$label = $lang->text('jsonForm/label');
		$val = $value['label'] ?? null;
		$form = array('inputText',$val,Base\Arr::plus($attr,array('name'=>$name."[label]")),$option);
		$return .= Html::formWrap("*".$label.":",$form,'divtable');
		$return .= Html::divCl();
		
		$return .= Html::divOp('type');
		$label = $lang->text('jsonForm/type');
		$relation = $lang->relation('jsonForm',null,false);
		$opt = Base\Arr::plus($option,array('value'=>$type));
		$data = array('choices'=>$choicesInput);
		$form = array('select',$relation,Base\Arr::plus($attr,array('name'=>$name."[type]",'data'=>$data)),$opt);
		$return .= Html::formWrap($label.":",$form,'divtable',null);
		$return .= Html::divCl();
		
		$return .= Html::divOp('description');
		$label = $lang->text('jsonForm/description');
		$val = $value['description'] ?? null;
		$form = array('textarea',$val,Base\Arr::plus($attr,array('name'=>$name."[description]")),$option);
		$return .= Html::formWrap($label.":",$form,'divtable');
		$return .= Html::divCl();
		
		$return .= Html::divOp('required');
		$label = $lang->text('jsonForm/required');
		$relation = $lang->relation('bool');
		$val = (!empty($value['required']))? 1:0;
		$opt = Base\Arr::plus($option,array('value'=>$val));
		$form = array('select',$relation,Base\Arr::plus($attr,array('name'=>$name."[required]")),$opt);
		$return .= Html::formWrap($label.":",$form,'divtable',null);
		$return .= Html::divCl();
		
		$class = (static::isChoicesInput($type))? 'visible':null;
		$return .= Html::divOp(array('choices',$class));
		$label = $lang->text('jsonForm/choices');
		$val = $value['choices'] ?? null;
		$val = (is_array($value['choices']))? Base\Str::lineImplode($value['choices']):$val;
		$form = array('textarea',$val,Base\Arr::plus($attr,array('name'=>$name."[choices]")),$option);
		$return .= Html::formWrap("*".$label.":",$form,'divtable');
		$return .= Html::divCl();
		
		$return .= Html::divCl();
		
		$return .= $this->makeModelUtils();
		$return .= Html::divCl();
		
		return $return;
	}
	
	
	// isChoicesInput
	// retourne vrai si le input en est un avec des choix
	public static function isChoicesInput($value):bool
	{
		return (is_string($value) && in_array($value,static::getChoicesInput(),true))? true:false;
	}
	
	
	// getChoicesInput
	// retourne les inputs avec choix de réponse
	public static function getChoicesInput():array
	{
		return Base\Html::relationTag('multiselect');
	}
}

// config
JsonForm::__config();
?>