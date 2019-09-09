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

// youTube
// class that can be used to make requests to the youTube API
class YouTube extends Core\ServiceVideoAlias
{
    // config
    public static $config = [
        'required'=>['provider_url','thumbnail_url','html'],
        'video'=>[
            'name'=>'title',
            'absolute'=>[self::class,'videoAbsolute'],
            'thumbnail'=>'thumbnail_url',
            'html'=>'html'],
        'target'=>'https://www.youtube.com/oembed?url=%value%&format=json' // uri target pour youTube
    ];


    // videoAbsolute
    // retourne l'uri absolut pour la vidéo youTube
    // callback utilisé par la classe video
    public static function videoAbsolute(Main\Video $video):?string
    {
        $return = null;
        $provider = $video->get('provider_url');
        $thumbnail = $video->get('thumbnail_url');

        if(!empty($provider) && !empty($thumbnail))
        {
            $path = Base\Uri::path($thumbnail);
            $x = Base\Path::arr($path);
            $key = $x[1] ?? null;

            if(!empty($key))
            {
                $query = ['v'=>$key];
                $change = [];
                $change['path'] = 'watch';
                $change['query'] = $query;
                $return = Base\Uri::change($change,$provider);
            }
        }

        return $return;
    }
}

// config
YouTube::__config();
?>