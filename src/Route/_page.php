<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
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
    protected $row = null;


    // onMake
    // lors de la construction de la route
    final protected function onMake():void
    {
        $this->makeRow();

        return;
    }


    // onBefore
    // avant le lancement de la route
    final protected function onBefore()
    {
        $return = parent::onBefore();

        if($return !== false)
        {
            if($this->rowExists() && $this->row()->isVisible())
            $return = true;
        }

        return $return;
    }


    // rowExists
    // retourne vrai si la row existe
    final public function rowExists():bool
    {
        return (!empty($this->row))? true:false;
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

        return;
    }
}
?>