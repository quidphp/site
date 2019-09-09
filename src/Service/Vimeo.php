<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Service;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// vimeo
// class used to make requests to the vimeo API
class Vimeo extends Core\ServiceVideoAlias
{
    // config
    public static $config = [
        'required'=>['provider_url','video_id','html'],
        'video'=>[
            'name'=>'title',
            'date'=>'upload_date',
            'description'=>'description',
            'absolute'=>[self::class,'videoAbsolute'],
            'thumbnail'=>'thumbnail_url',
            'html'=>'html'],
        'target'=>'https://vimeo.com/api/oembed.json?url=%value%' // uri target pour vimeo
    ];


    // videoAbsolute
    // retourne l'uri absolut pour la vidéo vimeo
    // callback utilisé par la classe video
    public static function videoAbsolute(Main\Video $video):?string
    {
        $return = null;
        $provider = $video->get('provider_url');
        $videoId = $video->get('video_id');

        if(!empty($provider) && !empty($videoId))
        {
            $change = ['path'=>$videoId];
            $return = Base\Uri::change($change,$provider);
        }

        return $return;
    }
}

// config
Vimeo::__config();
?>