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
use Quid\Base\Html;
use Quid\Core;

// section
// class for a row which represents a section containing one or many pages
abstract class Section extends Core\RowAlias
{
    // config
    public static $config = [
        'priority'=>1,
        '@app'=>[
            'route'=>[ // méthode pour générer la route de la section
                0=>[self::class,'routeSection']]]
    ];


    // getRoute
    // retoure la route à utiliser pour la section
    abstract public function getRoute():?Core\Route;


    // childs
    // retourne les enfants de la section
    abstract public function childs():?Core\Rows;


    // isVisible
    // retourne vrai si la route est visible
    public function isVisible():bool
    {
        return parent::isVisible() && $this->hasRoute();
    }


    // inAllSegment
    // retourne vrai si la section et ses enfants doivent apparaîtrent dans le sitemap
    final public function inAllSegment():bool
    {
        return true;
    }


    // hasRoute
    // retourne vrai si la section a une route
    final public function hasRoute():bool
    {
        return !empty($this->getRoute());
    }


    // isChild
    // retourne vrai si la row est un efant de la section
    final public function isChild(Core\Row $value,bool $top=true):bool
    {
        $return = false;
        $childs = $this->childs();

        if($top === true)
        $value = $value->topOrSelf();

        if(!empty($childs) && $childs->in($value))
        $return = true;

        return $return;
    }


    // hasChilds
    // retourne vrai si la section a au moins un enfant
    final public function hasChilds():bool
    {
        $return = false;
        $childs = $this->childs();

        if(!empty($childs) && $childs->isNotEmpty())
        $return = true;

        return $return;
    }


    // hasManyChilds
    // retourne vrai si la section a plusieurs enfants
    final public function hasManyChilds():bool
    {
        $return = false;
        $childs = $this->childs();

        if(!empty($childs) && $childs->isMinCount(2))
        $return = true;

        return $return;
    }


    // child
    // retourne le premier enfant de la section
    final public function child():?Core\Row
    {
        $return = null;
        $childs = $this->childs();

        if(!empty($childs) && $childs->isNotEmpty())
        $return = $childs->first();

        return $return;
    }


    // childRoute
    // retourne la route du premier enfant de la section
    public function childRoute():?Core\Route
    {
        $return = null;
        $child = $this->child();

        if(!empty($child))
        $return = $child->route();

        return $return;
    }


    // childsRoute
    // retourne les routes de tous les enftans de la section
    final public function childsRoute():array
    {
        $return = [];
        $childs = $this->childs();

        if(!empty($childs))
        {
            foreach ($childs as $id => $row)
            {
                $return[$id] = $row->route();
            }
        }

        return $return;
    }


    // childsRouteLi
    // retourne des liens vers les routes des enfants dans un structure ul li
    final public function childsRouteLi(bool $deepChilds=false,int $min=1):string
    {
        $return = '';
        $childs = $this->childsRoute();

        if(!empty($childs) && count($childs) >= $min)
        {
            foreach ($childs as $child)
            {
                $return .= Html::liOp();
                $return .= $child->aTitle();

                if($deepChilds === true)
                {
                    $childRow = $child->row();
                    $return .= Html::ulCond($childRow->childsRouteLi());
                }

                $return .= Html::liCl();
            }
        }

        return $return;
    }


    // routeSection
    // méthode statique utilisé pour déterminer la route d'une section, renvoie à getRoute
    final public static function routeSection(self $row):?Core\Route
    {
        return $row->getRoute();
    }
}

// init
Section::__init();
?>