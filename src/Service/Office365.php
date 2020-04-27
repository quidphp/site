<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Site\Service;
use Quid\Base;
use Quid\Main;

// office365
// class that grants some static methods related to Office365
class Office365 extends Main\Service
{
    // config
    protected static array $config = [
        'uri'=>'https://outlook.office.com/owa/'
    ];


    // construct
    // constructeur privé
    final private function __construct()
    {
        return;
    }


    // mailto
    // génère un lien mailto pour office365
    final public static function mailto(string $email):string
    {
        $return = null;

        if(Base\Validate::isEmail($email))
        {
            $uri = static::$config['uri'];
            $query = [];
            $query['path'] = '/mail/action/compose';
            $query['to'] = $email;

            $return = Base\Uri::changeQuery($query,$uri);
        }

        return $return;
    }


    // event
    // génère un lien d'ajout au calendrier pour office365
    final public static function event(array $array):?string
    {
        $return = null;
        $array = Base\Obj::cast($array);

        if(Base\Arr::keysExists(['dateStart','dateEnd','name','description','location'],$array))
        {
            if(is_int($array['dateStart']) && is_int($array['dateEnd']) && is_string($array['name']))
            {
                $uri = static::$config['uri'];
                $query = [];
                $query['path'] = '/calendar/action/compose';
                $query['subject'] = Base\Str::excerpt(255,$array['name'],['removeLineBreaks'=>true]);
                $query['location'] = Base\Str::excerpt(255,$array['location'] ?? '',['removeLineBreaks'=>true]);
                $query['body'] = Base\Str::excerpt(255,$array['description'] ?? '',['removeLineBreaks'=>true]);
                $query['startdt'] = Base\Datetime::format('office365',$array['dateStart']);
                $query['enddt'] = Base\Datetime::format('office365',$array['dateEnd']);

                $return = Base\Uri::changeQuery($query,$uri);
            }
        }

        return $return;
    }
}

// init
Office365::__init();
?>