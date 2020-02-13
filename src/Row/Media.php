<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Site\Row;
use Quid\Base;
use Quid\Base\Html;
use Quid\Core;
use Quid\Main;

// media
// class to work with a row of the media table, can contain medias, storages and videos
class Media extends Core\RowAlias
{
    // config
    public static $config = [
        'relation'=>[
            'method'=>'relationOutput','separator'=>'<br/>','order'=>['dateAdd'=>'desc'],'output'=>'name_fr'],
        'videoProvider'=>['youTube','vimeo'] // custom, service pour la vidéo
    ];


    // thumbnail
    // retourne le thumbnail à utiliser pour représenter le ou les médias
    final public function thumbnail():?Main\File\Image
    {
        return $this['medias']->file(0,'large');
    }


    // regenerateVersion
    // méthode pour regénérer les versions de la colonne medias
    final public function regenerateVersion(?array $option=null):?array
    {
        $return = null;

        if($this->hasCell('medias'))
        {
            $medias = $this['medias'];
            $return = $medias->makeVersion(true,$option);
        }

        return $return;
    }


    // relationThumbnail
    // retourne le thumbnail de relation à utiliser
    final public function relationThumbnail():?string
    {
        $return = null;
        $thumbnail = $this->thumbnail();

        if(!empty($thumbnail))
        $return = $thumbnail->img();

        return $return;
    }


    // relationOutput
    // génère le output de relation pour la table media
    final public function relationOutput():string
    {
        $return = '';
        $namePrimary = $this->namePrimary();

        $return .= Html::spanCond($this->relationThumbnail(),'thumbnail');
        $return .= Html::span($namePrimary,'legend');

        return $return;
    }


    // tableRelationArray
    // génère le tableau de output pour les médias dans la ligne
    // utiliser par l'outil de relation dans le cms
    final protected function tableRelationArray():array
    {
        $return = [];

        if($this->hasCell('medias'))
        $return = Base\Arr::append($return,$this->mediasArray());

        if($this->hasCell('storages'))
        $return = Base\Arr::append($return,$this->storagesArray());

        if(!empty($this->videoProvider()))
        $return = Base\Arr::append($return,$this->videosArray());

        return $return;
    }


    // mediasArray
    // génère le tableau de output pour les images dans la ligne
    final public function mediasArray():array
    {
        $return = [];
        $medias = $this['medias'];
        $rowName = $this->cellName();

        if($medias->isNotEmpty())
        {
            foreach ($medias->indexes(1) as $file)
            {
                $name = $file->basename();
                $uri = $file->pathToUri();

                if(!empty($name) && !empty($uri))
                {
                    $r = [];
                    $r['thumbnail'] = $uri;
                    $r['name'] = $name;
                    $r['content'] = '';

                    $excerpt = Base\Str::excerpt(50,$name);
                    $r['from'] = Html::img($uri,$rowName);
                    $r['from'] .= Html::span($excerpt,'legend');

                    $r['to'] = Html::img($uri,$rowName);

                    $return[] = $r;
                }
            }
        }

        return $return;
    }


    // storagesArray
    // génère le tableau de output pour les fichiers dans la ligne
    final public function storagesArray():array
    {
        $return = [];
        $storages = $this['storages'];
        $rowName = $this->cellName();

        if($storages->isNotEmpty())
        {
            foreach ($storages->indexes() as $file)
            {
                $name = $file->basename();
                $uri = $file->pathToUri();

                if(!empty($name) && !empty($uri))
                {
                    $r = [];
                    $r['thumbnail'] = null;
                    $r['name'] = $name;
                    $r['content'] = '';

                    $excerpt = Base\Str::excerpt(50,$name);
                    $r['from'] = Html::div(null,['big-icon','storage']);
                    $r['from'] .= Html::span($excerpt,'legend');

                    $r['to'] = Html::a($uri,$rowName,['target'=>false]);

                    $return[] = $r;
                }
            }
        }

        return $return;
    }


    // videoProvider
    // retourne les providers de videos liés à la ligne
    final public function videoProvider():array
    {
        return (array) $this->getAttr('videoProvider');
    }


    // videosArray
    // génère le tableau de output pour les vidéos dans la ligne
    // utilise les différents provider
    final public function videosArray():array
    {
        $return = [];
        $providers = $this->videoProvider();

        if(!empty($providers))
        {
            foreach ($providers as $provider)
            {
                if($this->hasCell($provider))
                {
                    $cell = $this->cell($provider);
                    $video = $cell->video();

                    if(!empty($video))
                    {
                        $array = $this->makeVideoArray($video);
                        if(!empty($array))
                        $return[] = $array;
                    }
                }
            }
        }

        return $return;
    }


    // makeVideoArray
    // génère le tableau de output pour une vidéo, à partir d'un objet main/video
    final protected function makeVideoArray(Main\Video $video):?array
    {
        $return = null;
        $html = $video->html();
        $name = $video->name();

        if(!empty($html) && !empty($name))
        {
            $r = [];
            $r['thumbnail'] = null;
            $r['name'] = $name;
            $date = $video->date(0);
            $content = $video->description(200);

            $r['content'] = '';
            if(!empty($date))
            {
                $r['content'] .= $date;
                if(!empty($content))
                $r['content'] .= ' - ';
            }
            $r['content'] .= $content;

            $excerpt = Base\Str::excerpt(50,$name);
            $r['from'] = Html::span(null,['big-icon','video']);
            $r['from'] .= Html::span($excerpt,'legend');

            $r['to'] = $html;

            $return = $r;
        }

        return $return;
    }


    // tableRelationOutput
    // gère le output de relation pour tableRelation dans le cms
    // permet insertion au curseur
    final public function tableRelationOutput():string
    {
        $return = '';
        $namePrimary = $this->namePrimary();

        $html = '';
        foreach ($this->tableRelationArray() as $value)
        {
            if(!empty($value['from']) && !empty($value['to']))
            {
                $data = ['html'=>$value['to']];
                $html .= Html::button($value['from'],['insert','data'=>$data]);
            }
        }

        if(!empty($html))
        {
            $html = Html::divCond($html,'triggers');

            $return .= Html::divOp('medias');
            $return .= Html::div($namePrimary,'legend');
            $return .= $html;
            $return .= Html::divCl();
        }

        return $return;
    }


    // slides
    // retourne un tableau avec toutes les slides à partir de la row media
    final protected function slides(?array $option=null):array
    {
        $return = [];
        $option = Base\Arr::plus(['name'=>false,'content'=>false],$option);

        foreach (['photo'=>'mediasArray','video'=>'videosArray'] as $type => $method)
        {
            foreach ($this->$method() as $value)
            {
                $html = '';

                if(is_array($value) && !empty($value))
                {
                    if($method === 'mediasArray')
                    $ratio = Html::div(null,['media','bgimg'=>$value['thumbnail']]);
                    else
                    $ratio = Html::div($value['to'],'media');

                    $html .= Html::divOp(['wrap',$type]);
                    $html .= Html::div($ratio,'ratio');
                    $html .= Html::divCl();
                    $info = '';

                    if($option['name'] === true)
                    $info .= Html::divCond($value['name'],'name');

                    if($option['content'] === true)
                    $info .= Html::divCond($value['content'],'content');

                    $html .= Html::divCond($info,'info');
                }

                if(strlen($html))
                $return[] = $html;
            }
        }

        return $return;
    }


    // outputSlides
    // génère toutes les slides à partir de la row media
    final public function outputSlides(?array $option=null):string
    {
        $r = '';
        $slides = $this->slides($option);

        if(!empty($slides))
        {
            foreach ($slides as $value)
            {
                if(is_string($value) && !empty($value))
                $r .= Html::div($value,'slide');
            }
        }

        return $r;
    }


    // refreshVersions
    // méthode pour rafraichir les versions de plusieurs lignes dans la médiathèque
    final public static function refreshVersions($where=true,?array $option=null):array
    {
        $return = [];
        $table = static::tableFromFqcn();

        foreach ($table->selects($where) as $id => $row)
        {
            $regenerate = $row->regenerateVersion($option);
            $return[] = $regenerate;
        }

        return $return;
    }
}

// init
Media::__init();
?>