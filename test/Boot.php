<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Test\Site;
use Quid\Base;
use Quid\Core;
use Quid\Site;

// boot
// class for testing Quid\Site\Boot
class Boot extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        $boot = $data['boot'];
        $lang = $boot->lang();

        // isApp
        assert(!$boot->isApp());

        // boot
        assert($boot->langContentClass('en') === Site\Lang\En::class);
        assert($boot->service('googleMaps') instanceof Core\ServiceRequest);

        // lang
        assert($lang->existsRelation('contextType/app'));
        assert($lang->existsRelation('contextType/app','en'));
        assert(!$lang->existsRelation('contextType','en'));
        assert($lang->typeLabel('app') === 'Application');
        assert(!empty($lang->relation('contextType')));
        assert($lang->relation('contextType/app') === 'Application');
        assert($lang->relation('contextType/app','en') === 'Application');
        assert($lang->relation('jsonForm') !== $lang->relation('jsonForm',null,false));
        assert($lang->required(true,null,['path'=>['tables','formSubmit','json']]) === 'The form is invalid.');
        assert($lang->pathAlternateTake('required',null,['tables','formSubmit','json']) === 'The form is invalid.');
        assert(count($lang->pathAlternateValue('required','common',false,['tables','formSubmit','json'])) === 4);
        assert(count($lang->pathAlternateValue('required','common',true,['tables','formSubmit','json'])) === 2);
        assert(count($lang->pathAlternate('required',['tables','formSubmit','json'])) === 4);

        return true;
    }
}
?>