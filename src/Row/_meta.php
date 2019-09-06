<?php
declare(strict_types=1);
namespace Quid\Site\Row;

// _meta
trait _meta
{
	// metaTitle
	// retourne les données pour le meta title
	public function getMetaTitle($value=null)
	{
		return $this->metaLoop(array('metaTitle_[lang]','name_[lang]'));
	}
	
	
	// metaKeywords
	// retourne les données pour le meta keywords
	public function getMetaKeywords($value=null)
	{
		return $this->metaLoop(array('metaKeywords_[lang]'));
	}
	
	
	// metaDescription
	// retourne les données pour le meta description
	public function getMetaDescription($value=null)
	{
		return $this->metaLoop(array('metaDescription_[lang]','excerpt_[lang]','content_[lang]'));
	}
	
	
	// metaLoop
	// loop des noms de cellules, retourne la première cellule existante et non vide
	// méthode protégé
	protected function metaLoop(array $array)
	{
		$return = null;
		
		foreach ($array as $name) 
		{
			if($this->hasCell($name))
			{
				$cell = $this->cell($name);
				if($cell->isNotEmpty())
				{
					$return = $cell;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// metaImage
	// retourne les données pour le meta image
	public function getMetaImage($value=null)
	{
		$return = null;
		
		if($this->hasCell('metaImage_[lang]'))
		{
			$cell = $this->cell('metaImage_[lang]');

			if($cell->fileExists('large'))
			$return = $cell;
		}
		
		return $return;
	}
	
	
	// getBodyClass
	// retourne les données les classes de body
	public function getBodyClass($value=null)
	{
		return;
	}
	
	
	// getBodyStyle
	// retourne les données pour les styles de body
	public function getBodyStyle($value=null)
	{
		return;
	}
}
?>