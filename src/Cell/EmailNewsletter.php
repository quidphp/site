<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Cell;
use Quid\Core;
use Quid\Site;

// emailNewsletter
// class for an email newsletter cell (subscribes to a third-party newsletter)
class EmailNewsletter extends Core\CellAlias
{
    // config
    protected static array $config = [];


    // isSubscribed
    // retourne vrai si l'utilisateur est enregistré à l'infolettre
    final public function isSubscribed(bool $confirmed=false):bool
    {
        return $this->col()->isSubscribed($this->value(),$confirmed);
    }


    // getService
    // retourne le service newsletter
    final public function getService():?Site\Contract\Newsletter
    {
        return $this->col()->getService();
    }
}

// init
EmailNewsletter::__init();
?>