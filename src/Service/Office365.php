<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Service;
use Quid\Base;
use Quid\Core;

// office365
// class that grants some static methods related to office365
class Office365 extends Core\ServiceAlias
{
    // config
    public static $config = [
        'uri'=>'https://outlook.office.com/owa/'
    ];


    // mailto
    // génère un lien mailto pour office365
    public static function mailto(string $email):string
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
    public static function event(array $array):?string
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
                $query['startdt'] = Base\Date::format('office365',$array['dateStart']);
                $query['enddt'] = Base\Date::format('office365',$array['dateEnd']);

                $return = Base\Uri::changeQuery($query,$uri);
            }
        }

        return $return;
    }
}

// config
Office365::__config();
?>