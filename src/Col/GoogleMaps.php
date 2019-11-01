<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Col;
use Quid\Base;
use Quid\Base\Html;
use Quid\Core;
use Quid\Main;
use Quid\Orm;
use Quid\Site;

// googleMaps
// class for a googleMaps column, with geo-localization data
class GoogleMaps extends Core\ColAlias
{
    // config
    public static $config = [
        'tag'=>'textarea',
        'cell'=>Site\Cell\GoogleMaps::class,
        'required'=>true,
        'search'=>false,
        'check'=>['kind'=>'text'],
        'service'=>'googleGeocoding' // custom, clé du service utilisé
    ];


    // onGet
    // sur onGet, retourne l'objet de localization
    public function onGet($return,array $option)
    {
        if(!$return instanceof Main\Localization)
        {
            $return = $this->value($return);

            if(is_string($return) && Base\Json::is($return))
            $return = Main\Localization::newOverload($return);
        }

        return $return;
    }


    // onSet
    // gère la logique onSet pour googleMaps
    public function onSet($return,array $row,?Orm\Cell $cell=null,array $option)
    {
        $hasChanged = true;
        $return = $this->value($return);

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
    public function getService():Main\Service
    {
        return $this->service($this->getAttr('service'));
    }


    // formComplex
    // génère le formulaire complex pour googleMaps
    public function formComplex($value=true,?array $attr=null,?array $option=null):string
    {
        $return = '';
        $value = $this->onGet($value,(array) $option);

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


    // html
    // fait une map à partir d'un objet de localization
    public function html(Main\Localization $value,?int $zoom=null):?string
    {
        $return = null;
        $data = $value->latLng();
        $data['zoom'] = $zoom;
        $return = Html::div(null,['googleMaps','id'=>true,'data'=>$data]);

        return $return;
    }
}

// init
GoogleMaps::__init();
?>