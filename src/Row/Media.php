<?php
declare(strict_types=1);
namespace Quid\Site\Row;
use Quid\Base\Html;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// media
class Media extends Core\RowAlias
{
	// config
	public static $config = array(
		'relation'=>array(
			'method'=>'relationOutput','separator'=>'<br/>','order'=>array('dateAdd'=>'desc'),'output'=>'name_fr'),
		'videoProvider'=>array('youTube','vimeo') // custom, service pour la vidéo
	);
	
	
	// thumbnail
	// retourne le thumbnail à utiliser pour représenter le ou les médias
	public function thumbnail():?Core\File\Image
	{
		return $this['medias']->file(0,'large');
	}
	
	
	// regenerateVersion
	// méthode pour regénérer les versions de la colonne medias
	public function regenerateVersion(?array $option=null):?array
	{
		$return = null;
		
		if($this->hasCell('medias'))
		{
			$medias = $this['medias'];
			$return = $medias->makeVersion(true,$option);
		}
		
		return $return;
	}
	
	
	// relationThumbnail
	// retourne le thumbnail de relation à utiliser
	public function relationThumbnail():?string
	{
		$return = null;
		$thumbnail = $this->thumbnail();
		
		if(!empty($thumbnail))
		$return = $thumbnail->img();
		
		return $return;
	}
	
	
	// relationOutput
	// génère le output de relation pour la table media
	public function relationOutput():string 
	{
		$return = '';
		$namePrimary = $this->namePrimary();

		$return .= Html::divCond($this->relationThumbnail(),'thumbnail');
		$return .= Html::div($namePrimary,'legend');
		
		return $return;
	}
	
	
	// tableRelationArray
	// génère le tableau de output pour les médias dans la ligne
	// utiliser par l'outil de relation dans le cms
	protected function tableRelationArray():array 
	{
		$return = array();
		
		if($this->hasCell('medias'))
		$return = Base\Arr::append($return,$this->mediasArray());
		
		if($this->hasCell('storages'))
		$return = Base\Arr::append($return,$this->storagesArray());
		
		if(!empty($this->videoProvider()))
		$return = Base\Arr::append($return,$this->videosArray());
		
		return $return;
	}
	
	
	// mediasArray
	// génère le tableau de output pour les images dans la ligne
	public function mediasArray():array
	{
		$return = array();
		$medias = $this['medias'];
		$rowName = $this->cellName();
		
		if($medias->isNotEmpty())
		{
			foreach ($medias->indexes(1) as $file) 
			{
				$name = $file->basename();
				$uri = $file->pathToUri();
				
				if(!empty($name) && !empty($uri))
				{
					$r = array();
					$r['thumbnail'] = $uri;
					$r['name'] = $name;
					$r['content'] = '';
					
					$excerpt = Base\Str::excerpt(50,$name);
					$r['from'] = Html::img($uri,$rowName);
					$r['from'] .= Html::div($excerpt,'legend');
					
					$r['to'] = Html::img($uri,$rowName);
					
					$return[] = $r;
				}
			}
		}
		
		return $return;
	}
	
	
	// storagesArray
	// génère le tableau de output pour les fichiers dans la ligne
	public function storagesArray():array
	{
		$return = array();
		$storages = $this['storages'];
		$rowName = $this->cellName();
		
		if($storages->isNotEmpty())
		{
			foreach ($storages->indexes() as $file) 
			{
				$name = $file->basename();
				$uri = $file->pathToUri();
				
				if(!empty($name) && !empty($uri))
				{
					$r = array();
					$r['thumbnail'] = null;
					$r['name'] = $name;
					$r['content'] = '';
					
					$excerpt = Base\Str::excerpt(50,$name);
					$r['from'] = Html::div(null,array('bigIcon','storage'));
					$r['from'] .= Html::div($excerpt,'legend');
					
					$r['to'] = Html::a($uri,$rowName,array('target'=>false));
					
					$return[] = $r;
				}
			}
		}
		
		return $return;
	}
	
	
	// videoProvider
	// retourne les providers de videos liés à la ligne
	public function videoProvider():?array 
	{
		return $this->attr('videoProvider');
	}
	
	
	// videosArray
	// génère le tableau de output pour les vidéos dans la ligne
	// utilise les différents provider
	public function videosArray():array 
	{
		$return = array();
		$providers = $this->videoProvider();
		
		if(!empty($providers))
		{
			foreach ($providers as $provider) 
			{
				if($this->hasCell($provider))
				{
					$cell = $this->cell($provider);
					$video = $cell->video();
					
					if(!empty($video))
					{
						$array = $this->makeVideoArray($video);
						if(!empty($array))
						$return[] = $array;
					}
				}
			}
		}
		
		return $return;
	}
	
	
	// makeVideoArray
	// génère le tableau de output pour une vidéo, à partir d'un objet main/video
	// méthode protégé
	protected function makeVideoArray(Main\Video $video):?array
	{
		$return = null;
		$html = $video->html();
		$name = $video->name();
		
		if(!empty($html) && !empty($name))
		{
			$r = array();
			$r['thumbnail'] = null;
			$r['name'] = $name;
			$date = $video->date(0);
			$content = $video->description(200);
			
			$r['content'] = '';
			if(!empty($date))
			{
				$r['content'] .= $date;
				if(!empty($content))
				$r['content'] .= " - ";
			}
			$r['content'] .= $content;
			
			$excerpt = Base\Str::excerpt(50,$name);
			$r['from'] = Html::div(null,array('bigIcon','video'));
			$r['from'] .= Html::div($excerpt,'legend');
			
			$r['to'] = $html;
			
			$return = $r;
		}
		
		return $return;
	}
	
	
	// tableRelationOutput
	// gère le output de relation pour tableRelation dans le cms
	// permet insertion au curseur
	public function tableRelationOutput():string
	{
		$return = '';
		$namePrimary = $this->namePrimary();
		
		$html = '';
		foreach ($this->tableRelationArray() as $value) 
		{
			if(!empty($value['from']) && !empty($value['to']))
			{
				$data = array('html'=>$value['to']);
				$html .= Html::divOp(array('insert','data'=>$data));
				$html .= $value['from'];
				$html .= Html::divCl();
			}
		}
		
		if(!empty($html))
		{
			$html = Html::divCond($html,'triggers');
			
			$return .= Html::divOp('medias');
			$return .= Html::div($namePrimary,'legend');
			$return .= $html;
			$return .= Html::divCl();
		}
		
		return $return;
	}
	
	
	// slides
	// retourne un tableau avec toutes les slides à partir de la row media
	protected function slides(?array $option=null):array 
	{
		$return = array();
		$option = Base\Arr::plus(array('name'=>false,'content'=>false),$option);
		
		foreach (array('photo'=>'mediasArray','video'=>'videosArray') as $type => $method) 
		{
			foreach ($this->$method() as $value) 
			{
				$html = '';
				
				if(is_array($value) && !empty($value))
				{
					if($method === 'mediasArray')
					$ratio = Html::div(null,array('media','bgimg'=>$value['thumbnail']));
					else
					$ratio = Html::div($value['to'],'media');
					
					$html .= Html::divOp(array('wrap',$type));
					$html .= Html::div($ratio,'ratio');
					$html .= Html::divCl();
					$html .= Html::divOp('info');
					
					if($option['name'] === true)
					$html .= Html::divCond($value['name'],'name');
					
					if($option['content'] === true)
					$html .= Html::divCond($value['content'],'content');
					
					$html .= Html::divCl();
				}
				
				if(strlen($html))
				$return[] = $html;
			}
		}
		
		return $return;
	}
	
	
	// outputSlides
	// génère toutes les slides à partir de la row media
	public function outputSlides(?array $option=null):string 
	{
		$r = '';
		$slides = $this->slides($option);
		
		if(!empty($slides))
		{
			foreach ($slides as $value) 
			{
				if(is_string($value) && !empty($value))
				$r .= Html::div($value,'slide');
			}
		}
		
		return $r;
	}
	
	
	// makeSlider
	// fait un slider à partir de plusieurs lignes média
	public static function makeSlider(Core\Rows $rows,?array $option=null):string
	{
		$r = '';
		$html = '';
		
		foreach ($rows as $row) 
		{
			$html .= $row->outputSlides($option);
		}
		
		if(!empty($html))
		{
			$r .= Html::divOp('slider');
			$r .= Html::div(null,array('prev','arrow'));
			$r .= Html::div(null,array('next','arrow'));
			$r .= $html;
			$r .= Html::divCl();
		}
		
		return $r;
	}
	
	
	// refreshVersions
	// méthode pour rafraichir les versions de plusieurs lignes dans la médiathèque
	public static function refreshVersions($where=true,?array $option=null):array 
	{
		$return = array();
		$table = static::tableFromFqcn();
		
		foreach ($table->selects($where) as $id => $row) 
		{
			$regenerate = $row->regenerateVersion($option);
			$return[] = $regenerate;
		}
		
		return $return;
	}
}

// config
Media::__config();
?>