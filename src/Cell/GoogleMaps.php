<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Cell;
use Quid\Base\Html;
use Quid\Core;
use Quid\Main;

// googleMaps
// class to work with a cell containing Google maps geo-localization data
class GoogleMaps extends Core\CellAlias
{
    // config
    protected static array $config = [];


    // export
    // retourne la valeur pour l'exportation de cellules relation
    final public function export(?array $option=null):array
    {
        return $this->exportCommon($this->input(),$option);
    }


    // localization
    // retourne l'objet de localization ou null
    final public function localization():?Main\Localization
    {
        return $this->get();
    }


    // html
    // output la colonne googleMaps sous forme de map simple
    final public function html(?int $zoom=null,bool $uri=true):?string
    {
        $return = null;
        $localization = $this->localization();

        if(!empty($localization))
        $return = $this->col()->html($localization,$zoom,$uri);

        return $return;
    }


    // mapDataAttr
    // retourne le tableau des data attr pour faire la map
    final public function mapDataAttr(?int $zoom=null,bool $uri=true):array
    {
        $return = [];
        $localization = $this->localization();

        if(!empty($localization))
        $return = $this->col()->mapDataAttr($localization,$zoom,$uri);

        return $return;
    }


    // address
    // retourne l'adresse formaté à partir de l'objet localization googleMaps
    final public function address():?string
    {
        $return = null;
        $localization = $this->localization();

        if(!empty($localization))
        $return = $localization->get('address');

        return $return;
    }


    // uri
    // retourne l'uri absolu vers googleMaps
    final public function uri():?string
    {
        $return = null;
        $localization = $this->localization();

        if(!empty($localization))
        $return = $this->col()->uri($localization);

        return $return;
    }


    // input
    // retourne l'input de googleMaps
    final public function input():?string
    {
        $return = null;
        $localization = $this->localization();

        if(!empty($localization))
        $return = $localization->input();

        return $return;
    }


    // generalOutput
    // génère le output général pour une cellule googleMaps
    final public function generalOutput(array $option):?string
    {
        return $this->html();
    }
}

// init
GoogleMaps::__init();
?>