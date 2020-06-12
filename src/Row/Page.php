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
use Quid\Base;
use Quid\Base\Html;
use Quid\Core;

// page
// class for a row which represents a page
class Page extends Core\RowAlias
{
    // config
    protected static array $config = [
        'key'=>['slug_[lang]','slugPath_[lang]',0],
        'cols'=>[
            'slug_fr'=>[
                'slug'=>[self::class,'makeSlug'],
                'exists'=>false],
            'slug_en'=>[
                'slug'=>[self::class,'makeSlug'],
                'exists'=>false],
            'slugPath_fr'=>[
                'slug'=>[self::class,'makeSlug'],
                'exists'=>false],
            'slugPath_en'=>[
                'slug'=>[self::class,'makeSlug'],
                'exists'=>false]],
        'priority'=>2,
        'hierarchy'=>'page_id' // custom, détermine la hiérarchie
    ];


    // hasParentCell
    // retourne vrai si la ligne a une cellule pour déterminer son parent
    final public function hasParentCell():bool
    {
        return $this->hasCell(static::hierarchy());
    }


    // hasOrder
    // retourne vrai si la page a un ordre
    final public function hasOrder():bool
    {
        return $this->hasCell('order');
    }


    // isTop
    // retourne vrai si la page n'a pas de parent
    final public function isTop():bool
    {
        return $this->top() === null;
    }


    // inAllSegment
    // retorune vrai si la page est incluse dans allSegment, pour sitemap
    public function inAllSegment():bool
    {
        return true;
    }


    // getOrder
    // retourne l'ordre de la page si existant, ou null
    final public function getOrder():?Core\Cell
    {
        $return = null;

        if($this->hasOrder())
        $return = $this->cell('order');

        return $return;
    }


    // parent
    // retourne le parent direct de la page
    final public function parent():?self
    {
        return ($this->hasParentCell())? $this[static::hierarchy()](true):null;
    }


    // parentOrSelf
    // retourne la page parent ou la page courante
    final public function parentOrSelf():?self
    {
        return $this->parent() ?? $this;
    }


    // top
    // retourne la page top en lien avec la page
    final public function top():?self
    {
        return $this->cache(__METHOD__,function() {
            $return = null;
            $row = $this;

            while ($row = $row->parent())
            {
                $return = $row;
            }

            return $return;
        });
    }


    // topOrSelf
    // retourne la page top ou la page courante
    final public function topOrSelf():?self
    {
        return $this->top() ?? $this;
    }


    // parents
    // retourne tous les parents de la page
    final public function parents():Core\Rows
    {
        $return = $this->table()->rows(false);
        $row = $this;

        while ($row = $row->parent())
        {
            $return->add($row);
        }

        return $return;
    }


    // breadcrumb
    // retourne les parents de la page et la page sous forme de breadcrumbs
    final public function breadcrumb():Core\Rows
    {
        $return = $this->parents()->reverse();
        $return->add($this);

        return $return;
    }


    // childs
    // retourne les enfants directs de la page
    // possible de retourner un objet rows
    final public function childs(bool $rows=true)
    {
        $return = null;

        if($this->hasParentCell())
        {
            $table = $this->table();
            $return = $this->cache(__METHOD__,function() {
                $hierarchy = static::hierarchy();
                $table = $this->table();
                $where = $table->where([$hierarchy=>$this]);
                $order = $table->order();
                return $table->selectPrimaries($where,$order);
            });

            if($rows === true)
            $return = (!empty($return))? $table->rows(...$return):null;
        }

        return $return;
    }


    // childsRoute
    // retourne les routes des enfants directs de la page
    final public function childsRoute():array
    {
        $return = [];
        $childs = $this->childs(true);

        if($childs instanceof Core\Rows)
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
    // possible de creuser dans la hiérarchie si deep est true
    final public function childsRouteLi(bool $deep=false):string
    {
        $return = '';
        $childs = $this->childsRoute();

        foreach ($childs as $child)
        {
            $return .= Html::liOp();
            $return .= $child->aTitle();

            if($deep === true)
            {
                $row = $child->row();
                $return .= Html::ulCond($row->childsRouteLi(true));
            }

            $return .= Html::liCl();
        }

        return $return;
    }


    // parentsRoute
    // retourne les routes des parents de la page
    final public function parentsRoute():array
    {
        $return = [];

        foreach ($this->parents() as $id => $row)
        {
            $return[$id] = $row->route();
        }

        return $return;
    }


    // makeBreadcrumbs
    // génère le breadcrumbs pour la page
    final public function makeBreadcrumbs(string $delimiter='/',int $max=2,int $length=40):string
    {
        $r = '';
        $routes = static::boot()->routes();
        $parents = $this->parentsRoute();

        if(!empty($parents))
        {
            $parents = array_values($parents);
            $parents = Base\Arr::unsetAfterCount($max,$parents);
            $parents = array_reverse($parents);
            $separator = Html::span($delimiter,'separator');
            $r .= $routes::makeBreadcrumbs($separator,$length,...$parents);
        }

        return $r;
    }


    // tableRelationOutput
    // gère le output de relation pour wysiwyg dans le cms
    // permet insertion au caret
    public function tableRelationOutput():string
    {
        $return = '';

        if($this->inAllSegment())
        {
            $key = $this->getRouteKey();

            if(!empty($key))
            {
                $route = $this->route($key);

                if(!empty($route) && $route::isRedirectable())
                {
                    $wysiwyg = $route->aTitle(null,['target'=>false]);
                    $data = ['html'=>$wysiwyg];
                    $namePrimary = $this->namePrimary();
                    $return = Html::button($namePrimary,['insert','data'=>$data]);
                }
            }
        }

        return $return;
    }


    // slug
    // retourne le slug de la page, utilise cellKey
    final public function slug():Core\Cell
    {
        return $this->cellKey();
    }


    // regenerateSlug
    // regenere le slug de la page, pour ce faire tu le vides
    final public function regenerateSlug(?array $option=null)
    {
        $return = null;
        $slug = $this->slug();

        $slug->set('');
        $return = $this->updateValid($option);

        return $return;
    }


    // getSlugSliceLength
    // retourne la longueur minimale et maximale de chaque slice pour le slug
    final public static function getSlugSliceLength():?array
    {
        return null;
    }


    // getSlugPrepend
    // retourne le contenu à mettre avant le slug de la page
    // cette méthode peut être étendu, par exemple pour mettre les parents
    public static function getSlugPrepend(Core\Col $col,array $row,?Core\Cell $cell=null):?Core\Cell
    {
        return null;
    }


    // getSlugArray
    // retourne le tableau de tous les éléments à mettre dans le slug pour la page
    final public static function getSlugArray(Core\Col $col,array $row,?Core\Cell $cell=null):array
    {
        $return = [];
        $hierarchy = static::hierarchy();
        $parent = $row[$hierarchy] ?? null;

        if(is_int($parent))
        {
            $table = static::tableFromFqcn();
            $parent = $table->row($parent);
            if(!empty($parent))
            {
                foreach ($parent->breadcrumb() as $breadcrumb)
                {
                    $return[] = $breadcrumb->cellName();
                }
            }
        }

        $return[] = Base\Lang::arr('name',$row);

        $prepend = static::getSlugPrepend($col,$row,$cell);
        if(!empty($prepend))
        $return = Base\Arr::merge($prepend,$return);

        return $return;
    }


    // makeSlug
    // construit le slug pour la page
    // cette méthode est appelé via la colonne slug
    final public static function makeSlug(Core\Col $col,array $row,?Core\Cell $cell=null,?array $option=null):string
    {
        $return = '';
        $array = static::getSlugArray($col,$row,$cell);
        $sliceLength = static::getSlugSliceLength();
        $slug = ['totalLength'=>$col->length()];
        if(is_array($sliceLength))
        $slug['sliceLength'] = $sliceLength;

        $return = $col::slugMake($array,$slug);

        return $return;
    }


    // refreshSlugs
    // cette méthode permet de rafraichir tous les slugs de la table
    final public static function refreshSlugs(?array $option=null):array
    {
        $return = [];
        $table = static::tableFromFqcn();
        $hierarchy = static::hierarchy();
        $array = [];

        if(!empty($hierarchy))
        $array = $table->hierarchy($hierarchy,true,true);

        else
        {
            $primaries = $table->selectPrimaries(true);
            if(!empty($primaries))
            $array = array_flip($primaries);
        }

        if(!empty($array))
        $return = static::refreshSlugsArray($array,$return,$option);

        return $return;
    }


    // refreshSlugsArray
    // méthode protégé utilisé par refreshSlugs
    // permet de regénérer plusieurs slugs en respectant la hiérarchie des pages
    final protected static function refreshSlugsArray(array $array,array &$return,?array $option=null):array
    {
        $table = static::tableFromFqcn();

        foreach ($array as $id => $value)
        {
            if(is_int($id))
            {
                $row = $table->row($id);
                if(!empty($row))
                $return[$id] = $row->regenerateSlug($option);

                if(is_array($value))
                static::refreshSlugsArray($value,$return,$option);
            }
        }

        return $return;
    }


    // hierarchy
    // retourne le nom de la colonne pour la hiérarchie, si existant
    // par défaut c'est page_id
    final public static function hierarchy():?string
    {
        return static::$config['hierarchy'];
    }
}

// init
Page::__init();
?>