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
        if(is_int($row))
        $this->row = Site\Row\Page::select($row);
    }
}
?>