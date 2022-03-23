<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Test\Site;
use Quid\Base;
use Quid\Core;
use Quid\Main;
use Quid\Site;

// db
// class for testing Quid\Site\Db
class Db extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $db = Core\Boot::inst()->db();

        // col

        // prepare
        $table = 'ormCol';
        $tb = $db[$table];
        $googleMaps = $tb['googleMaps'];
        $tinymce = $tb['wysiwyg'];
        $form = $tb['form'];
        $myVideo = $tb['myVideo'];
        $email = $tb['email'];

        // email
        assert($email->get() === 'default@def.james');

        // emailNewsletter

        // googleMaps
        $localization = new Main\Localization(['address'=>'ok','lat'=>2,'lng'=>1,'input'=>'whét asd','countryCode'=>'ca']);
        assert($googleMaps->tag() === 'textarea');
        assert(strlen($googleMaps->formComplex($localization)) === 215);
        assert(strlen($googleMaps->html($localization)) === 130);

        // hierarchy

        // jsonForm

        // jsonFormRelation

        // tinyMce
        assert($tinymce->tag() === 'textarea');
        assert(count($tinymce->formAttr()) === 1);
        assert(count($tinymce->attr()) === 74);
        assert(count($tinymce->getAttr('tinymce')) === 17);
        assert(strlen($tinymce->formComplex()) > 500);

        // tinyMceAdvanced

        // vimeo
        $vimeo = ['title'=>'James','html'=>'test','thumbnail_url'=>'http://image.com','provider_url'=>'https://vimeo.com','video_id'=>'132132','description'=>'bla','upload_date'=>'2018-07-25 23:30:36'];
        $video = Site\Service\Vimeo::makeVideo($vimeo);
        assert($myVideo instanceof Site\Col\Vimeo);
        assert(strlen($myVideo->formComplex($video)) === 216);

        // youTube

        // core
        assert($tb->colAttr('myVideo') === ['class'=>Site\Col\Vimeo::class]);
        assert($form instanceof Site\Col\Vimeo);
        assert($googleMaps->hasDefault());
        assert($googleMaps->hasNullDefault());
        assert(!$googleMaps->hasNotEmptyDefault());
        assert($googleMaps->default() === null);

        // cell

        // prepare
        $table = 'ormCell';
        assert($db->truncate($table) instanceof \PDOStatement);
        assert($db->inserts($table,['id','date','name','dateAdd','userAdd','dateModify','userModify','integer','enum','set','user_ids'],[1,time(),'james',10,2,12,13,12,5,'2,3',[2,1]],[2,time(),'james2',10,11,12,13,12,5,'2,4','2,3']) === [1,2]);
        $tb = $db[$table];
        $row = $tb[1];
        $googleMaps = $row->cell('googleMaps');
        $vimeo = $row->cell('vimeo');

        // googleMaps
        assert($googleMaps instanceof Site\Cell\GoogleMaps);
        assert($googleMaps->html() === null);
        assert($googleMaps->address() === null);
        assert($googleMaps->uri() === null);
        assert($googleMaps->input() === null);

        // jsonForm

        // jsonFormRelation

        // vimeo
        assert($vimeo->col() instanceof Site\Col\Vimeo);
        assert($vimeo->formComplex() === "<input maxlength='65535' name='vimeo' placeholder='NULL' type='text'/>");
        assert(strlen($vimeo->specificComponentWrap()) === 171);

        // youTube

        // cleanup
        assert($db->truncate($table) instanceof \PDOStatement);

        return true;
    }
}
?>