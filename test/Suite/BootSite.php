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
use Quid\Site;
use Quid\Test;

// prepare
class_alias(Site\Boot::class,Test\Suite\BootAlias::class);
require dirname(__FILE__,4).'/lemur/test/Suite/BootLemur.php';


// bootSite
// class for booting the Quid\Site testsuite
class BootSite extends Test\Suite\BootLemur
{
    // config
    public static $config = [
        'assert'=>[
            'namespaces'=>[
                Site::class=>Test\Site::class],
            'frontEnd'=>['front'=>'[vendorFront]','lemur'=>'[vendorLemur]','site'=>'[vendorSite]'],],
        'service'=>[
            'googleGeocoding'=>[Site\Service\GoogleGeocoding::class,['key'=>'wqeqwweqwqeeqwqwe']],
            'ipApi'=>Site\Service\IpApi::class,
            'newsletter'=>[Site\Service\Mailchimp::class,['key'=>'wqeqwweqwqeeqwqwe-us1','list'=>'zxczzxc']],
            'pdfCrowd'=>[Site\Service\PdfCrowd::class,['key'=>'wqeqwweqwqeeqwqwe','username'=>'wqeqqwewqwesad']]]
    ];
}
}


// CMS
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

// init
namespace Quid\Test\Suite {
return [BootSite::class,'start'];
}
?>