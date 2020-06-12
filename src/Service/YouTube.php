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

// youTube
// class that can be used to make requests to the YouTube API
class YouTube extends Main\ServiceVideo
{
    // config
    protected static array $config = [
        'required'=>['provider_url','thumbnail_url','html'],
        'video'=>[
            'name'=>'title',
            'absolute'=>[self::class,'videoAbsolute'],
            'thumbnail'=>'thumbnail_url',
            'html'=>'html'],
        'hostsValid'=>['youtube.com','www.youtube.com'],
        'target'=>'https://www.youtube.com/oembed?url=%value%&format=json' // uri target pour youTube
    ];


    // videoAbsolute
    // retourne l'uri absolut pour la vidéo youTube
    // callback utilisé par la classe video
    final public static function videoAbsolute(Main\Video $video):?string
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

// init
YouTube::__init();
?>