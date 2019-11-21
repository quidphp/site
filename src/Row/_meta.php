<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Site\Row;

// _meta
// trait with methods to make a row a meta-source
trait _meta
{
    // metaTitle
    // retourne les données pour le meta title
    public function getMetaTitle($value=null)
    {
        return $this->metaLoop(['metaTitle_[lang]','name_[lang]']);
    }


    // metaKeywords
    // retourne les données pour le meta keywords
    public function getMetaKeywords($value=null)
    {
        return $this->metaLoop(['metaKeywords_[lang]']);
    }


    // metaDescription
    // retourne les données pour le meta description
    public function getMetaDescription($value=null)
    {
        return $this->metaLoop(['metaDescription_[lang]','excerpt_[lang]','content_[lang]']);
    }


    // metaLoop
    // loop des noms de cellules, retourne la première cellule existante et non vide
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


    // getHtmlAttr
    // retourne les données des attributs de html
    public function getHtmlAttr($value=null)
    {
        return;
    }


    // getBodyAttr
    // retourne les données des attributs de body
    public function getBodyAttr($value=null)
    {
        return;
    }
}
?>