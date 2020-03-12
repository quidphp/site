<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 * Readme: https://github.com/quidphp/site/blob/master/README.md
 */

namespace Quid\Test\Suite {
use Quid\Lemur;
use Quid\Site;
use Quid\Test;

// prepare
class_alias(Site\Boot::class,Test\Suite\BootAlias::class);
require dirname(__FILE__,4).'/core/test/Suite/BootCore.php';


// bootSite
// class for booting the testsuite (with lemur and site)
class BootSite extends Test\Suite\BootCore
{
    // config
    public static $config = [
        'typeAs'=>['assert'=>'cms'],
        'routeNamespace'=>['assert'=>[Lemur\Cms::class,Test\Suite\Assert::class]],
        'assert'=>[
            'exclude'=>null,
            'js'=>true],
        'service'=>[
            'googleGeocoding'=>[Site\Service\GoogleGeocoding::class,['key'=>'wqeqwweqwqeeqwqwe']],
            'ipApi'=>Site\Service\IpApi::class,
            'newsletter'=>[Site\Service\Mailchimp::class,['key'=>'wqeqwweqwqeeqwqwe-us1','list'=>'zxczzxc']],
            'pdfCrowd'=>[Site\Service\PdfCrowd::class,['key'=>'wqeqwweqwqeeqwqwe','username'=>'wqeqqwewqwesad']]],
        'compile'=>true,
        'compileCss'=>false,
        'compileJsOption'=>['compress'=>false],
        'compileJs'=>[
            'test'=>[
                'to'=>'[publicJs]/test.js',
                'from'=>[
                    0=>'[vendorFront]/js/import',
                    1=>'[vendorFront]/js/test']]],
    ];
}
}


// ASSERT
namespace Quid\Test\Suite\Assert {
use Quid\Base;
use Quid\Base\Html;
use Quid\Lemur;
use Quid\Main;

\Quid\Main\Autoload::setClosure("Quid\Test\Suite\Assert",'Home',function() {

// home
class Home extends Lemur\Cms\Home
{
    // config
    public static $config = [
        'selectedUri'=>false,
        'jsInit'=>'document.addEventListener("DOMContentLoaded", function() {
            const jsDiv = Lemur.Doc.scopedQuery(this,"#javascript");
            const span = Lemur.Ele.scopedQuery(jsDiv,"span:last-child");
            const test = (Lemur.Test.Include() && Lemur.Test.Component())? true:false;
            const color = (test === true)? Lemur.Ele.getAttr(jsDiv,"data-success-color"):Lemur.Ele.getAttr(jsDiv,"data-failure-color");
            const text = (test === true)? Lemur.Ele.getAttr(jsDiv,"data-success"):Lemur.Ele.getAttr(jsDiv,"data-failure");
            Lemur.Ele.setHtml(span,text);
            Lemur.Ele.setCss(span,"color",color);
        });',
        'docOpen'=>[
            'head'=>[
                'css'=>false,
                'js'=>[
                    'test'=>'js/test.js',
                    'type'=>null]]]
    ];


    // trigger
    final public function trigger()
    {
        $return = '';
        $isCli = Base\Cli::is();
        $boot = static::boot();

        if($isCli === false)
        {
            $return .= $this->docOpen();
            $data = ['success'=>'Success','success-color'=>'green','failure'=>'Failure','failure-color'=>'red'];
            $return .= Html::divOp(['#javascript','data'=>$data,'style'=>['padding'=>'5px 0','border-bottom'=>'2px solid black']]);
            $return .= Html::span('JavaScript: ');
            $return .= Html::span('Idle');
            $return .= Html::divCl();
        }

        Base\Html::setUriOption('script',['append'=>false,'exists'=>false]);
        $target = $boot->getAttr('assert/target');
        $exclude = $boot->getAttr('assert/exclude');
        $data = ['boot'=>$boot];

        if($target === true)
        {
            $closure = function(string $value) {
                return (stripos($value,'quid') === 0 && stripos($value,'quid\\test') === false)? true:false;
            };
            $target = Main\Autoload::findNamespace($closure,true,true,true);

            if(!empty($exclude))
            $target = Base\Arr::valuesStrip($exclude,$target);
        }

        $array = Main\Autoload::callNamespace($target,'classTestTrigger',$exclude,$data);
        $return .= Base\Debug::exportExtra($array);
        $return .= Base\Debug::export(Base\Num::addition(...array_values($array)));

        $overview = $boot->getAttr('assert/overview');
        if(!empty($overview))
        {
            $return .= Base\Debug::export(Base\Server::overview());

            if($overview === true || $overview > 1)
            {
                $closure = function(string $value) {
                    return (stripos($value,'quid') === 0)? true:false;
                };
                $codeOverview = Base\Autoload::overview($closure,true);

                foreach (['front'=>'[vendorFront]','lemur'=>'[vendorLemur]','site'=>'[vendorSite]'] as $key => $value)
                {
                    $codeOverview[$key.'-css'] = Base\Dir::overview($value.'/css');
                    $codeOverview[$key.'-js'] = Base\Dir::overview($value.'/js');
                }

                $return .= Base\Debug::export($codeOverview);
                $lines = Base\Column::value('line',$codeOverview);
                $return .= Base\Debug::export(Base\Num::addition(...$lines));

                if($overview === true || $overview > 2)
                $return .= Base\Debug::export(Base\Autoload::all());
            }
        }

        if($isCli === false)
        $return .= $this->docClose();

        return $return;
    }
}
});
}
namespace Quid\Test\Suite\Assert {
use Quid\Lemur;

\Quid\Main\Autoload::setClosure("Quid\Test\Suite\Assert",'ActivatePassword',function() {

// activatePassword
class ActivatePassword extends Lemur\Cms\ActivatePassword
{
    // config
    public static $config = [
        'path'=>[
            'en'=>'activate/password/[primary]/[hash]',
            'fr'=>'activer/mot-de-passe/[primary]/[hash]']
    ];
}
});
}
namespace Quid\Test\Suite\Assert {
use Quid\Lemur;

\Quid\Main\Autoload::setClosure("Quid\Test\Suite\Assert",'Error',function() {

// error
class Error extends Lemur\Cms\Error
{
    // config
    public static $config = [];
}
});
}
namespace Quid\Test\Suite\Assert {
use Quid\Lemur;

\Quid\Main\Autoload::setClosure("Quid\Test\Suite\Assert",'Sitemap',function() {

// sitemap
class Sitemap extends Lemur\Cms\Sitemap
{
    // config
    public static $config = [];
}
});
}


// CMS
namespace Quid\Test\Suite\Cms {
use Quid\Core;

\Quid\Main\Autoload::setClosure("Quid\Test\Suite\Cms",'System',function() {

// system
class System extends Core\Route
{
    // config
    public static $config = [
        'type'=>'cms',
        'user'=>'extended',
        'group'=>'module'
    ];


    // trigger
    public function trigger():string
    {
        return '';
    }
}
});
}
namespace Quid\Test\Suite\Row {
use Quid\Core;
use Quid\Lemur;
use Quid\Orm;
use Quid\Site;
use Quid\Test\Suite;

\Quid\Main\Autoload::setClosure("Quid\Test\Suite\Row",'OrmCol',function() {

// ormCol
class OrmCol extends Site\Row
{
    // config
    public static $config = [
        'cols'=>[
            'form'=>['class'=>Site\Col\Vimeo::class],
            'myVideo'=>['class'=>Site\Col\Vimeo::class],
            'wysiwyg'=>['class'=>Lemur\Col\TinyMce::class],
            'other'=>['relation'=>[2,3,4]],
            'password'=>['class'=>Core\Col\UserPassword::class],
            'myRelation'=>['relation'=>['test',3,4,9=>'ok']],
            'relationRange'=>['relation'=>['min'=>0,'max'=>20,'inc'=>2],'editable'=>false],
            'relationStr'=>['relation'=>[0=>'test','what'=>'james','lol'=>'ok']],
            'relationLang'=>['complex'=>'radio','relation'=>'test'],
            'relationCall'=>['relation'=>[self::class,'testCall']],
            'rangeInt'=>['relation'=>8],
            'multi'=>['complex'=>'multiselect','set'=>true,'relation'=>'test'],
            'check'=>['set'=>true,'relation'=>['min'=>0,'max'=>20,'inc'=>2]],
            'user_ids'=>['class'=>Suite\Col\UserIds::class],
            'json'=>['class'=>Lemur\Col\JsonArray::class,'required'=>true],
            'medias'=>['media'=>6],
            'media'=>['version'=>[
                'small'=>[50,'jpg','crop',300,200],
                'large'=>[70,'jpg','ratio_y',500,400]]],
            'email'=>['description'=>'Ma description']]
    ];


    // testCall
    final public static function testCall(Orm\ColRelation $relation):array
    {
        return ['test','test2','test3'];
    }
}
});
}
namespace Quid\Test\Suite\Row {
use Quid\Site;
use Quid\Test\Suite;

\Quid\Main\Autoload::setClosure("Quid\Test\Suite\Row",'User',function() {

// user
class User extends Site\Row\User
{
    // config
    public static $config = [
        'emailModel'=>[
            'resetPassword'=>'resetPassword',
            'registerConfirm'=>'registerConfirm'],
        'crypt'=>[
            'passwordHash'=>['options'=>['cost'=>4]]],
        'permission'=>[
            '*'=>['assertLogin'=>false],
            'user'=>['assertLogin'=>true],
            'editor'=>['assertLogin'=>true],
            'admin'=>['assertLogin'=>true]]
    ];


    // activatePasswordRoute
    public function activatePasswordRoute():string
    {
        return Suite\Assert\ActivatePassword::class;
    }
}
});
}

// init
namespace Quid\Test\Suite {
return [BootSite::class,'start'];
}
?>