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

// _pageSection
// trait with methods to deal with a page row within a section
trait _pageSection
{
    // dynamique
    protected bool $sectionGrabbed = false; // garde en mémoire si la section a été grab


    // hasSection
    // retourne vrai si la section existe
    final public function hasSection():bool
    {
        return true;
    }


    // section
    // retourne la section de la page
    // si les sections n'ont pas encore été chargés, fait le
    final public function section():?Section
    {
        return $this->cache(__METHOD__,function() {
            $return = null;
            $class = Section::class;
            $rows = $class::rows();

            if($rows->isEmpty() && $this->sectionGrabbed === false)
            {
                $class::grabVisible();
                $this->sectionGrabbed = true;
            }

            foreach ($class::rows() as $section)
            {
                if($section->isChild($this))
                $return = $section;
            }

            return $return;
        });
    }
}
?>