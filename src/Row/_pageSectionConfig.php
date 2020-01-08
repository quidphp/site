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

// _pageSectionConfig
// trait related to the configuration of a row representing a page within a section
trait _pageSectionConfig
{
    // trait
    use _pageSection;
    use _pageConfig;


    // routeConfig
    // fait une configuration sur la page non dynamique
    // lie la section à la configuration
    // cette méthode est appelé par routePrepareConfig
    final public function routeConfig(array $return):array
    {
        $path = null;
        $section = $this->section();

        if(!empty($section))
        {
            $return['section'] = $section;
            $return = $this->routePathConfig($return);
        }

        return $return;
    }
}
?>