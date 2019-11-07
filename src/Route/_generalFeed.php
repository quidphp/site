<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Route;
use Quid\Base;
use Quid\Base\Html;
use Quid\Orm;

// _generalFeed
// trait that grants methods related a general feed (load more)
trait _generalFeed
{
    // rows
    protected $ids = null;


    // pageSlice
    // permet de slice les entrées dans une page
    final protected function pageSlice(bool $validate=false):array
    {
        $return = Base\Nav::pageSlice($this->segment('page'),$this->getAttr('limit'),$this->ids());

        if($validate === true)
        {
            $slice = $return;
            $return = [];

            foreach ($slice as $key => $value)
            {
                if(is_array($value) && Base\Arr::keysExists(['id','-table-'],$value))
                {
                    if(is_string($value['-table-']) && is_int($value['id']))
                    $return[$key] = $value;
                }
            }
        }

        return $return;
    }


    // pageNext
    // retourne le numéro de la prochaine page si existant
    final protected function pageNext():?int
    {
        return Base\Nav::pageNext($this->segment('page'),$this->ids(),$this->getAttr('limit'));
    }


    // general
    // retourne le tableau sur les informations de pagination genral si existant
    final protected function general(int $amount=3):?array
    {
        return Base\Nav::general($this->segment('page'),$this->ids(),$this->getAttr('limit'),$amount);
    }


    // ids
    // retourne le tableau de tout les ids
    final protected function ids():array
    {
        return $this->ids;
    }


    // rows
    // retourne l'objet rows pour la page courante
    final public function rows():Orm\RowsIndex
    {
        return $this->cache(__METHOD__,function() {
            $return = Orm\RowsIndex::newOverload();
            $db = $this->db();
            $ids = $this->makeIds();
            $slice = $this->pageSlice(true);
            $loads = [];

            if(!empty($slice))
            {
                foreach ($slice as $value)
                {
                    $loads[$value['-table-']][] = $value['id'];
                }

                if(!empty($loads))
                {
                    foreach ($loads as $table => $ids)
                    {
                        $rows = $db->table($table)->rows(...$ids);
                        $loads[$table] = $rows;
                    }

                    foreach ($slice as $value)
                    {
                        $row = $loads[$value['-table-']][$value['id']] ?? null;

                        if(!empty($row))
                        $return->add($row);
                    }
                }
            }

            return $return;
        });
    }


    // rowsVisible
    // retourne l'objet rows pour la page courante (mais seulement les visibles)
    final public function rowsVisible():Orm\RowsIndex
    {
        return $this->cache(__METHOD__,function() {
            return $this->rows()->filter(['isVisible'=>true]);
        });
    }


    // makePager
    // construit le pager
    final protected function makePager(int $amount=3):string
    {
        $r = '';
        $general = $this->general($amount);

        if(!empty($general))
        $r .= $this->makeGeneralPager($general,true,true,true);

        return $r;
    }


    // loadMore
    // génère le bouton pour charger la prochaine page
    final protected function loadMore():string
    {
        $r = '';
        $pageNext = $this->pageNext();

        if(is_int($pageNext))
        {
            $route = $this->changeSegment('page',$pageNext);
            $data = ['href'=>$route];
            $r .= Html::divOp(['load-more','data'=>$data]);
            $r .= Html::div(static::langText('common/loadMore'),'text');
            $r .= Html::divCl();
        }

        return $r;
    }
}
?>