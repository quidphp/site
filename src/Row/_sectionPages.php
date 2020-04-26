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
use Quid\Core;

// _sectionPages
// trait related to a row representing a section which contains pages
trait _sectionPages
{
    // inMenu
    // retourne vrai si la section s'affiche dans le menu
    final public function inMenu():bool
    {
        return ($this['menu']->isEmpty())? false:true;
    }


    // getRoute
    // retourne la route de la section, soit celle de la première page
    final public function getRoute():?Core\Route
    {
        return $this->childRoute();
    }


    // childs
    // retourne les enfants de la section
    // si la section n'a qu'un seul enfant, retourne quand même un objet rows
    final public function childs():Core\Rows
    {
        $return = null;
        $cell = $this->cellPattern('page');

        if(!empty($cell))
        {
            $return = $cell(true);

            if($return instanceof Core\Row)
            $return = $return->toRows();
        }

        return $return;
    }


    // rowsInMenu
    // retourne toutes les sections qui s'affichent dans le menu
    final public static function rowsInMenu():Core\Rows
    {
        return static::rowsVisibleOrder()->filter(fn($row) => $row->inMenu());
    }
}
?>