<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\App;
use Quid\Core;
use Quid\Base;

// version
// class for the version route, accessible via the cli
class Version extends Core\RouteAlias
{
    // config
    public static $config = [
        'path'=>array('-v','-version','-about'), // plusieurs chemins pour la route
        'match'=>array(
            'cli'=>true)
    ];
    
    
    // trigger
    // lancement de la route cli version
    public function trigger() 
    {
        return $this->outputCli();
    }
    
    
    // outputCli
    // génère le output du cli
    protected function outputCli():string
    {
        $r = '';
        $boot = static::boot();

        $r .= static::asciiArt();
        $r .= Base\Cli::pos($boot->label());
        $r .= Base\Cli::pos($boot->typeLabel());
        $r .= Base\Cli::pos($boot->version());

        return $r;
    }


    // asciiArt
    // retourne le ascii art pour le cli
    public static function asciiArt():string
    {
return '
 .d88888b.           d8b      888 8888888b.  888    888 8888888b.  
d88P" "Y88b          Y8P      888 888   Y88b 888    888 888   Y88b 
888     888                   888 888    888 888    888 888    888 
888     888 888  888 888  .d88888 888   d88P 8888888888 888   d88P 
888     888 888  888 888 d88" 888 8888888P"  888    888 8888888P"  
888 Y8b 888 888  888 888 888  888 888        888    888 888        
Y88b.Y8b88P Y88b 888 888 Y88b 888 888        888    888 888        
 "Y888888"   "Y88888 888  "Y88888 888        888    888 888        
       Y8b                                                                                                                
';
    }
}

// init
Version::__init();
?>