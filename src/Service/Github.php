<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Service;
use Quid\Base;
use Quid\Main;

// github
// class that grants some static methods related to Github
class Github extends Main\Service
{
    // config
    protected static array $config = [];


    // construct
    // constructeur privé
    final private function __construct()
    {
        return;
    }


    // allMarkdown
    // génère le markdown pour php, js et css
    final public static function allMarkdown(string $namespace):array
    {
        $return = [];
        $return['php'] = static::namespaceToMarkdown($namespace);
        $return['js'] = static::assetToMarkdown('js',$namespace);
        $return['css'] = static::assetToMarkdown('css',$namespace);

        return $return;
    }


    // namespaceToMarkdown
    // génère la string markdown à partir du tableau retourné par namespaceToArray
    final public static function namespaceToMarkdown(string $namespace):?string
    {
        $return = null;
        $array = static::namespaceToArray($namespace);

        if(!empty($array))
        $return = static::arrayToMarkdown($array);

        return $return;
    }


    // assetToMarkdown
    // génère la string markdown à partir du tableau retourné par assetsToArray
    final public static function assetToMarkdown(string $type,string $namespace):?string
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
    final public static function namespaceToArray(string $namespace):?array
    {
        $return = null;
        $path = static::getDirPath($namespace);

        if(!empty($path))
        {
            $return = [];
            $array = Main\Autoload::findMany($namespace,true);

            if(!empty($array))
            {
                $array = array_keys($array);
                $dirPath = dirname($path);
                $closure = fn(string $file) => static::getClassDescription($file,true);

                $return = static::makeArray($array,$dirPath,$closure,true);
            }
        }

        return $return;
    }


    // assetsToArray
    // retourne un tableau multidimensinnel column avec le filename, chemin github et description de la classe
    // pour toutes les assets du type dans le namespace
    final public static function assetsToArray(string $type,string $namespace):?array
    {
        $return = null;
        $path = static::getDirPath($namespace);

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
    final protected static function makeArray(array $array,string $dirPath,\Closure $closure,bool $filename=false):array
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
    final protected static function arrayToMarkdown(array $array):?string
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
    final public static function getClassDescription(string $file,bool $ucfirst=false):?string
    {
        $return = null;
        $lines = Base\File::lines(0,50,$file);
        $description = null;

        if(!empty($lines))
        {
            foreach ($lines as $value)
            {
                $value = trim($value);

                if(Base\Str::isStarts(['class ','trait ','interface ','abstract ','final '],$value))
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
    final public static function getAssetDescription(string $file,string $type,bool $ucfirst=false):?string
    {
        $return = null;
        $target = 0;

        if($type === 'js')
        $target = 7;

        elseif($type === 'css')
        $target = 7;

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
    final public static function prepareDescription(string $return,bool $ucfirst=false):string
    {
        $return = str_replace('//','',$return);
        $return = trim($return);

        if($ucfirst === true)
        $return = ucfirst($return);

        return $return;
    }


    // getDirPath
    // permet d'obtenir le path pour le namespace
    // code spéciale pour front qui n'a pas de namespasce PHP enregistré
    final public static function getDirPath(string $namespace):?string
    {
        $return = Base\Autoload::getDirPath($namespace);

        if(empty($return))
        {
            $last = Base\Fqcn::last($namespace);
            if(!empty($last))
            {
                $vendor = "[vendor$last]/src";
                $return = Base\Finder::shortcut($vendor);
            }
        }

        return $return;
    }
}

// init
GitHub::__init();
?>