<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Site\Col;
use Quid\Base;
use Quid\Base\Html;
use Quid\Core;
use Quid\Main;
use Quid\Orm;
use Quid\Site;

// googleMaps
// class for a GoogleMaps column, with geo-localization data
class GoogleMaps extends Core\ColAlias
{
    // config
    protected static array $config = [
        'tag'=>'textarea',
        'cell'=>Site\Cell\GoogleMaps::class,
        'required'=>true,
        'search'=>false,
        'check'=>['kind'=>'text'],
        'service'=>'googleGeocoding' // custom, clé du service utilisé
    ];


    // onGet
    // sur onGet, retourne l'objet de localization
    final protected function onGet($return,?Orm\Cell $cell=null,array $option)
    {
        if(Base\Json::is($return))
        $return = Main\Localization::newOverload($return);

        return $return;
    }


    // onSet
    // gère la logique onSet pour googleMaps
    final protected function onSet($return,?Orm\Cell $cell=null,array $row,array $option)
    {
        $hasChanged = true;

        if(!empty($cell))
        {
            $localization = $cell->get();

            if($localization instanceof Main\Localization && $localization->input() === $return)
            {
                $return = $localization;
                $hasChanged = false;
            }
        }

        if(!empty($return) && $hasChanged === true)
        {
            $googleMaps = $this->getService();
            $return = $googleMaps->localize($return);
        }

        return $return;
    }


    // getService
    // retourne le service à utiliser
    final public function getService():Main\Service
    {
        return $this->service($this->getAttr('service'));
    }


    // formComplex
    // génère le formulaire complex pour googleMaps
    final public function formComplex($value=true,?array $attr=null,?array $option=null):string
    {
        $return = '';
        $value = $this->get($value,$option);

        if($value instanceof Main\Localization)
        {
            $return .= $this->html($value);
            $value = $value->input();
        }

        else
        $value = null;

        $return .= parent::formComplex($value,$attr,$option);

        return $return;
    }


    // uri
    // retourne l'uri absolu vers googleMaps
    final public function uri(Main\Localization $value):?string
    {
        $return = null;
        $input = $value->input();

        if(!empty($input))
        {
            $service = Site\Service\GoogleMaps::class;
            $return = $service::uri($input);
        }

        return $return;
    }


    // html
    // fait une map à partir d'un objet de localization
    final public function html(Main\Localization $value,?int $zoom=null,bool $uri=true):?string
    {
        return Html::div(null,['map-render','id'=>true,'data'=>$this->mapDataAttr($value,$zoom,$uri)]);
    }


    // mapDataAttr
    // retourne un tableau avec les data-attr pour la balise de la map
    final public function mapDataAttr(Main\Localization $value,?int $zoom=null,bool $uri=true):array
    {
        $return = $value->latLng();
        $return['zoom'] = $zoom;
        $return['uri'] = ($uri === true)? $this->uri($value):null;

        return $return;
    }
}

// init
GoogleMaps::__init();
?>