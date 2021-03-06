<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Route;
use Quid\Core;
use Quid\Site;

// _page
// trait that provides basic logic for a page route
trait _page
{
    // config
    protected static array $configSitePage = [
        'configPath'=>true,
        'pageRowClass'=>Site\Row\Page::class
    ];


    // dynamique
    protected ?Core\Row $row = null;


    // onMake
    // lors de la construction de la route
    final protected function onMake():void
    {
        $this->makeRow();
    }


    // canTrigger
    // si la route peut être lancé
    final public function canTrigger():bool
    {
        return parent::canTrigger() && $this->rowExists() && $this->row()->isVisible();
    }


    // rowExists
    // retourne vrai si la row existe
    final public function rowExists():bool
    {
        return !empty($this->row);
    }


    // row
    // retourne la row
    final public function row():Core\Row
    {
        return $this->row;
    }


    // makeRow
    // construit l'objet row pour la route
    final protected function makeRow():void
    {
        $row = $this->getAttr('rowObj');
        $class = $this->getAttr('pageRowClass');

        if(!empty($class))
        {
            if(is_int($row))
            $row = $class::select($row);

            if($row instanceof Core\Row)
            $this->row = $row;
        }
    }


    // shouldConfigPath
    // retourne vrai si le chemin de la page doit être configuré
    // ceci permet de conserver les chemins dans le fichier, même si défini dans la base de données
    final public static function shouldConfigPath():bool
    {
        return static::$config['configPath'];
    }
}
?>