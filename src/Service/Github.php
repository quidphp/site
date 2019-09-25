<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Service;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// github
// class that grants some static methods related to github
class Github extends Core\ServiceAlias
{
    // config
    public static $config = [];


    // allMarkdown
    // génère le markdown pour php, js et scss
    public static function allMarkdown(string $namespace):array
    {
        $return = [];
        $return['php'] = static::namespaceToMarkdown($namespace);
        $return['js'] = static::assetToMarkdown('js',$namespace);
        $return['scss'] = static::assetToMarkdown('scss',$namespace);

        return $return;
    }


    // namespaceToMarkdown
    // génère la string markdown à partir du tableau retourné par namespaceToArray
    public static function namespaceToMarkdown(string $namespace):?string
    {
        $return = null;
        $array = static::namespaceToArray($namespace);

        if(!empty($array))
        $return = static::arrayToMarkdown($array);

        return $return;
    }


    // assetToMarkdown
    // génère la string markdown à partir du tableau retourné par assetsToArray
    public static function assetToMarkdown(string $type,string $namespace):?string
    {
        $return = null;
        $array = static::assetsToArray($type,$namespace);

        if(!empty($array))
        $return = static::arrayToMarkdown($array);

        return $return;
    }


    // namespaceToArray
    // retourne un tableau multidimensinnel column avec le filename, chemin github et description de la classe
    // pour toutes les classes dans le namespace
    public static function namespaceToArray(string $namespace):?array
    {
        $return = null;
        $path = Base\Autoload::getDirPath($namespace);

        if(!empty($path))
        {
            $return = [];
            $array = Main\Autoload::findMany($namespace,true);

            if(!empty($array))
            {
                $array = array_keys($array);
                $dirPath = dirname($path);
                $closure = function(string $file) {
                    return static::getClassDescription($file,true);
                };

                $return = static::makeArray($array,$dirPath,$closure,true);
            }
        }

        return $return;
    }


    // assetsToArray
    // retourne un tableau multidimensinnel column avec le filename, chemin github et description de la classe
    // pour toutes les assets du type dans le namespace
    public static function assetsToArray(string $type,string $namespace):?array
    {
        $return = null;
        $path = Base\Autoload::getDirPath($namespace);

        if(!empty($path))
        {
            $return = [];
            $path = dirname($path)."/$type";
            $array = Base\Dir::getVisible($path,true,['in'=>['type'=>'file']]);

            if(!empty($array))
            {
                $dirPath = dirname($path);
                $closure = function(string $file) use($type) {
                    return static::getAssetDescription($file,$type,true);
                };

                $return = static::makeArray($array,$dirPath,$closure,false);
            }
        }

        return $return;
    }


    // makeArray
    // génère le array, utilisé par namespaceToArray et assetsToArray
    // méthode protégé
    protected static function makeArray(array $array,string $dirPath,\Closure $closure,bool $filename=false):array
    {
        $return = [];
        ksort($array);
        $defaultArr = ['filename'=>null,'path'=>null,'description'=>null,'parent'=>0,'exists'=>true];

        foreach ($array as $file)
        {
            $arr = $defaultArr;
            $path = str_replace($dirPath.'/','',$file);
            $key = Base\PathTrack::removeExtension($path);
            $dirnamePath = dirname($path);
            $parent = (Base\Str::subCount('/',$path) - 1);
            $method = ($filename === true)? 'filename':'basename';

            $arr['filename'] = Base\Path::$method($file);
            $arr['path'] = $path;
            $arr['description'] = $closure($file);
            $arr['parent'] = $parent;

            if($parent > 0 && !array_key_exists($dirnamePath,$return))
            {
                $dirnameArr = $defaultArr;
                $dirnameArr['filename'] = Base\Path::$method($dirnamePath);
                $dirnameArr['path'] = $dirnamePath;
                $dirnameArr['exists'] = false;
                $return[$dirnamePath] = $dirnameArr;
            }

            $return[$key] = $arr;
        }

        return $return;
    }


    // arrayToMarkdown
    // génère la string markdown à partir du tableau
    // méthode protégé
    protected static function arrayToMarkdown(array $array):?string
    {
        $return = null;

        if(!empty($array))
        {
            $lines = [];

            $count = 0;
            foreach ($array as $key => $value)
            {
                if($value['exists'] === true)
                $count++;
            }
            $lines[] = $count;

            foreach ($array as $value)
            {
                $line = '';
                $parent = $value['parent'];

                while ($parent > 0)
                {
                    $line .= "\t";
                    $parent--;
                }

                $line .= '- ['.$value['filename'].']('.$value['path'].')';

                if(!empty($value['description']) && $value['description'] !== $value['filename'])
                $line .= ' - '.$value['description'];

                $lines[] = $line;
            }

            $return = Base\Str::lineImplode($lines);
        }

        return $return;
    }


    // getClassDescription
    // retourne la ligne de description de la classe ou null
    // à partir d'une string fichier
    public static function getClassDescription(string $file,bool $ucfirst=false):?string
    {
        $return = null;
        $lines = Base\File::lines(0,50,$file);
        $description = null;

        if(!empty($lines))
        {
            foreach ($lines as $value)
            {
                $value = trim($value);

                if(Base\Str::isStarts(['class','trait','interface','abstract','final'],$value))
                {
                    if(!empty($description))
                    $return = static::prepareDescription($description,$ucfirst);

                    break;
                }

                $description = $value;
            }
        }

        return $return;
    }


    // getAssetDescription
    // retourne la ligne de description du fichier asset
    // à partir d'une string fichier
    public static function getAssetDescription(string $file,string $type,bool $ucfirst=false):?string
    {
        $return = null;
        $target = 0;

        if($type === 'js')
        $target = 9;

        elseif($type === 'scss')
        $target = 9;

        $array = Base\File::lines($target,1,$file);
        if(!empty($array))
        {
            $current = current($array);
            if(Base\Str::isStart('//',$current))
            $return = static::prepareDescription($current,$ucfirst);
        }

        return $return;
    }


    // prepareDescription
    // prépare la description à partir de la ligne du fichier de programmation
    public static function prepareDescription(string $return,bool $ucfirst=false):string
    {
        $return = str_replace('//','',$return);
        $return = trim($return);

        if($ucfirst === true)
        $return = ucfirst($return);

        return $return;
    }
}

// config
GitHub::__config();
?>