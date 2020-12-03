<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Col;
use Quid\Base;
use Quid\Base\Html;
use Quid\Core;
use Quid\Orm;

// hierarchy
// class for an hierarchy column, like a website page sitemap
class Hierarchy extends Core\Col\EnumAlias
{
    // config
    protected static array $config = [
        'orderHierarchy'=>['order'=>'asc'],
        'complex'=>'hierarchy'
    ];


    // onSet
    // gère la logique onSet pour hierarchy
    final protected function onSet($return,?Orm\Cell $cell=null,array $row,array $option)
    {
        $return = parent::onSet($return,$cell,$row,$option);

        if($return === 0)
        $return = null;

        return $return;
    }


    // formComplexHierarchy
    // génère un élément de formulaire pour hiérarchie
    final protected function formComplexHierarchy($value=true,?array $attr=null,?array $option=null):string
    {
        $return = '';
        $id = ($value instanceof Core\Cell)? $value->row()->primary():null;
        $value = $this->valueComplex($value,$option);

        $where = $this->getWhere($id);
        $hierarchy = $this->getHierarchy($where);
        $names = $this->getNames($where);
        $attr = Base\Arr::plus(['tag'=>'radio',$attr]);
        $option = Base\Arr::plus(['value'=>$value],$option);

        $return .= $this->formHidden();
        $return .= Html::divCond($this->makeHierarchyStructure($value,$hierarchy,$names,0,$attr,$option),'scroller');

        return $return;
    }


    // getWhere
    // retourne le where pour la requête
    // si id fourni, ne l'inclut pas dans le résultat de la requête
    final public function getWhere(?int $id=null):array
    {
        $return = [];

        if(is_int($id))
        {
            $table = $this->table();
            $primary = $table->primary();
            $return[] = [$primary,'!=',$id];
        }

        return $return;
    }


    // getHierarchy
    // retournee la hiérarchie
    final public function getHierarchy(?array $where=null):array
    {
        $table = $this->table();
        $order = $this->getAttr('orderHierarchy');

        return $table->hierarchy($this,true,$where,$order);
    }


    // getFlatHierarchy
    // retourne la hiérarchie dans un tableau associatif non multidimensionnel
    final public function getFlatHierarchy(?array $where=null):array
    {
        $return = [];
        $hierarchy = $this->getHierarchy($where);
        $keys = Base\Arrs::keys($hierarchy);

        foreach ($keys as $array)
        {
            foreach ($array as $value)
            {
                if(!in_array($value,$return,true))
                $return[] = $value;
            }
        }

        return $return;
    }


    // getNames
    // retourne un tableau avec tous les noms de pages
    final protected function getNames($where=null):array
    {
        $return = [];
        $db = $this->db();
        $table = $this->table();
        $primary = $table->primary();
        $col = $table->colName();

        if(!empty($col))
        $return = $db->selectKeyPairs($primary,$col,$table,$where);

        return $return;
    }


    // makeHierarchyStructure
    // crée la structure html ul/li pour la hierarchy
    final protected function makeHierarchyStructure($value,array $hierarchy,array $names,int $i=0,?array $attr=null,?array $option=null):string
    {
        $return = '';
        $lang = $this->db()->lang();

        foreach ($hierarchy as $k => $v)
        {
            if(is_int($k) && array_key_exists($k,$names))
            {
                $name = $this->relationExcerpt($names[$k]);
                $name = Orm\Relation::appendPrimary($name,$k);

                $liHtml = $this->formComplexOutput([$k=>$name],$attr,$option);

                if(is_array($v))
                $liHtml .= $this->makeHierarchyStructure($value,$v,$names,($i + 1),$attr,$option);

                $return .= Html::li($liHtml,'choice');
            }
        }

        if($i === 0)
        {
            if($value === null)
            $option['value'] = 0;

            $noParent = '-- '.$lang->text('hierarchy/noParent').' --';
            $liHtml = $this->formComplexOutput([0=>$noParent],$attr,$option);
            $return .= Html::li($liHtml,'choice');
        }

        $return = Html::ulCond($return);

        return $return;
    }
}

// init
Hierarchy::__init();
?>