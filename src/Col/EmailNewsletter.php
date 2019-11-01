<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Col;
use Quid\Base;
use Quid\Base\Html;
use Quid\Core;
use Quid\Site;

// emailNewsletter
// class for an email newsletter column (subscribes to a third-party newsletter)
class EmailNewsletter extends Core\Col\EmailAlias
{
    // config
    public static $config = [
        'service'=>'newsletter' // custom, nom du service d'infolettre
    ];


    // formComplex
    // génère le formulaire complex pour emailNewsletter
    public function formComplex($value=true,?array $attr=null,?array $option=null):string
    {
        $return = parent::formComplex($value,$attr,$option);

        $email = Base\Obj::cast($value);
        if(Base\Validate::isEmail($email))
        $return .= $this->formNewsletter($email);

        return $return;
    }


    // formNewsletter
    // génère le bloc indiquant si le email est dans l'infolettre
    protected function formNewsletter(string $email):string
    {
        $return = '';
        $service = $this->getService();

        if(!empty($service))
        {
            $lang = $this->db()->lang();
            $label = $lang->text('emailNewsletter/label');
            $subscribed = $service->isSubscribed($email);
            $return .= Html::divOp('subscribed');
            $return .= Html::span($label.':','label');
            $return .= Html::span($lang->bool($subscribed),'value');
            $return .= Html::divCl();
        }

        return $return;
    }


    // getService
    // retourne le service mailchimp
    public function getService():?Site\Contract\Newsletter
    {
        $return = null;
        $service = $this->getAttr('service');

        if(is_string($service))
        $return = $this->service($service);

        return $return;
    }
}

// init
EmailNewsletter::__init();
?>