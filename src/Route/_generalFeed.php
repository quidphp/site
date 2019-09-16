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
use Quid\Core;

// _generalFeed
// trait that grants methods related a general feed (load more)
trait _generalFeed
{
    // trait
    use Core\Route\_generalPager;


    // rows
    protected $ids = null;


    // pageSlice
    // permet de slice les entrées dans une page
    protected function pageSlice(bool $validate=false):array
    {
        $return = Base\Nav::pageSlice($this->segment('page'),static::$config['limit'],$this->ids());

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
    protected function pageNext():?int
    {
        return Base\Nav::pageNext($this->segment('page'),$this->ids(),static::$config['limit']);
    }


    // general
    // retourne le tableau sur les informations de pagination genral si existant
    protected function general(int $amount=3):?array
    {
        return Base\Nav::general($this->segment('page'),$this->ids(),static::$config['limit'],$amount);
    }


    // ids
    // retourne le tableau de tout les ids
    protected function ids():array
    {
        return $this->ids;
    }


    // rows
    // retourne l'objet rows pour la page courante
    public function rows():Core\RowsIndex
    {
        return $this->cache(__METHOD__,function() {
            $return = Core\RowsIndex::newOverload();
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
    public function rowsVisible():Core\RowsIndex
    {
        return $this->cache(__METHOD__,function() {
            return $this->rows()->filter(['isVisible'=>true]);
        });
    }


    // makePager
    // construit le pager
    protected function makePager(int $amount=3):string
    {
        $r = '';
        $general = $this->general($amount);

        if(!empty($general))
        $r .= $this->makeGeneralPager($general,true,true,true);

        return $r;
    }


    // loadMore
    // génère le bouton pour charger la prochaine page
    protected function loadMore():string
    {
        $r = '';
        $pageNext = $this->pageNext();

        if(is_int($pageNext))
        {
            $route = $this->changeSegment('page',$pageNext);
            $data = ['href'=>$route];
            $r .= Html::divOp(['loadMore','data'=>$data]);
            $r .= Html::div(static::langText('common/loadMore'),'text');
            $r .= Html::divCl();
        }

        return $r;
    }
}
?>