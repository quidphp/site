<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Row;

// _meta
// trait with methods to make a row a meta-source
trait _meta
{
    // metaTitle
    // retourne les données pour le meta title
    final public function getMetaTitle($value=null)
    {
        return $this->metaLoop(['metaTitle_[lang]','name_[lang]']);
    }


    // metaKeywords
    // retourne les données pour le meta keywords
    final public function getMetaKeywords($value=null)
    {
        return $this->metaLoop(['metaKeywords_[lang]']);
    }


    // metaDescription
    // retourne les données pour le meta description
    final public function getMetaDescription($value=null)
    {
        return $this->metaLoop(['metaDescription_[lang]','excerpt_[lang]','content_[lang]']);
    }


    // metaLoop
    // loop des noms de cellules, retourne la première cellule existante et non vide
    final protected function metaLoop(array $array)
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
    final public function getMetaImage($value=null)
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
    final public function getBodyClass($value=null)
    {
        return;
    }


    // getBodyStyle
    // retourne les données pour les styles de body
    final public function getBodyStyle($value=null)
    {
        return;
    }
}
?>