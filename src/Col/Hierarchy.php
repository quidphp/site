<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Col;
use Quid\Base\Html;
use Quid\Core;
use Quid\Orm;
use Quid\Base;

// hierarchy
// class for an hierarchy column, like a website page sitemap
class Hierarchy extends Core\Col\EnumAlias
{
    // config
    public static $config = [
        'complex'=>'hierarchy'
    ];


    // onSet
    // gère la logique onSet pour hierarchy
    public function onSet($return,array $row,?Orm\Cell $cell=null,array $option)
    {
        if($return === 0)
        $return = null;

        return $return;
    }


    // formComplexHierarchy
    // génère un élément de formulaire pour hiérarchie
    protected function formComplexHierarchy($value=true,?array $attr=null,?array $option=null):string
    {
        $return = '';
        $id = ($value instanceof Core\Cell)? $value->row()->primary():null;
        $value = $this->valueComplex($value,$option);
        $table = $this->table();
        $primary = $table->primary();
        $where = [true];

        if(is_int($id))
        $where[] = [$primary,'!=',$id];

        $hierarchy = $table->hierarchy($this,true,$where,['order'=>'asc']);
        $names = $this->getNames($where);
        $attr = Base\Arr::plus(['tag'=>'radio',$attr]);
        $option = Base\Arr::plus(['value'=>$value],$option);

        $return .= $this->formHidden();
        $return .= Html::divCond($this->makeHierarchyStructure($value,$hierarchy,$names,0,$attr,$option),'scroller');

        return $return;
    }


    // getNames
    // retourne un tableau avec tous les noms de pages
    protected function getNames($where=null):array
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
    protected function makeHierarchyStructure($value=true,array $hierarchy,array $names,int $i=0,?array $attr=null,?array $option=null):string
    {
        $return = '';

        if(!empty($hierarchy))
        {
            $return .= Html::ulOp();

            foreach ($hierarchy as $k => $v)
            {
                if(is_int($k) && array_key_exists($k,$names))
                {
                    $name = $this->valueComplexExcerpt($names[$k]);
                    $name .= " (#$k)";

                    $return .= Html::liOp('choice');
                    $return .= $this->formComplexOutput([$k=>$name],$attr,$option);

                    if(is_array($v))
                    $return .= $this->makeHierarchyStructure($value,$v,$names,($i + 1),$attr,$option);

                    $return .= Html::liCl();
                }
            }

            if($i === 0)
            {
                if($value === null)
                $option['value'] = 0;

                $noParent = '-- '.static::langText('hierarchy/noParent').' --';
                $return .= Html::liOp('choice');
                $return .= $this->formComplexOutput([0=>$noParent],$attr,$option);
                $return .= Html::liCl();
            }

            $return .= Html::ulCl();
        }

        return $return;
    }
}

// config
Hierarchy::__config();
?>