<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Site\Service;
use Quid\Base;
use Quid\Main;

// vimeo
// class used to make requests to the Vimeo API
class Vimeo extends Main\ServiceVideo
{
    // config
    protected static array $config = [
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
    final public static function videoAbsolute(Main\Video $video):?string
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

// init
Vimeo::__init();
?>