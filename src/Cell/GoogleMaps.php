<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Cell;
use Quid\Base\Html;
use Quid\Core;
use Quid\Main;
use Quid\Site;

// googleMaps
// class to work with a cell containing google maps geo-localization data
class GoogleMaps extends Core\CellAlias
{
    // config
    public static $config = [];


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
    final public function html(?int $zoom=null):?string
    {
        $return = null;
        $localization = $this->localization();

        if(!empty($localization))
        $return = $this->col()->html($localization,$zoom);

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
        $input = $this->input();

        if(!empty($input))
        {
            $service = Site\Service\GoogleMaps::class;
            $return = $service::uri($input);
        }

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
}

// init
GoogleMaps::__init();
?>