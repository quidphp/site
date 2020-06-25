<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Service;
use Quid\Base;
use Quid\Main;

// pdfCrowd
// class that provides some methods to communicate with Pdfcrowd (and generate a pdf from html)
class PdfCrowd extends Main\ServiceRequest
{
    // config
    protected static array $config = [
        'target'=>'http://api.pdfcrowd.com/convert/', // uri target pour PdfCrowd
        'responseCode'=>null,
        'post'=>[ // données post par défaut
            'input_format'=>'html',
            'output_format'=>'pdf',
            'page_size'=>'letter',
            'orientation'=>'portrait']
    ];


    // apiKey
    // retourne la clé d'api
    final public function apiKey():string
    {
        return $this->getAttr('key');
    }


    // username
    // retourne le username de l'api
    final public function username():string
    {
        return $this->getAttr('username');
    }


    // userPassword
    // retourne la tableau user password pour curl
    final public function userPassword():array
    {
        return [$this->username(),$this->apiKey()];
    }


    // checkType
    // envoie une exception si le type n'est pas supporté
    final protected function checkType(string $type):void
    {
        if(!in_array($type,['url','text'],true))
        static::throw();
    }


    // prepareOption
    // prépare le tableau option avant de le joindre à la requête
    final protected function prepareOption(?array $option=null):array
    {
        $return = Base\Arr::plus($this->attr(),$option);
        $return = Base\Arr::keysStrip(['username','key','post'],$return);
        $return['userPassword'] = $this->userPassword();

        return $return;
    }


    // preparePost
    // prépare le tableau post avant de le joindre à la requête
    final protected function preparePost(string $type,$value,?array $post=null):array
    {
        $return = [];
        $this->checkType($type);
        $value = Base\Obj::cast($value);

        $return = Base\Arr::plus($this->getAttr('post'),$post);
        $return[$type] = $value;

        return $return;
    }


    // request
    // retourne la requête à utiliser pour aller chercher une resource auprès de pdfCrowd
    final public function request(string $type,$value,?array $post=null,?array $option=null):Main\Request
    {
        $return = null;
        $option = $this->prepareOption($option);

        $request = [];
        $request['uri'] = static::target();
        $request['post'] = $this->preparePost($type,$value,$post);
        $return = static::makeRequest($request,$option);

        return $return;
    }


    // convertUri
    // lance une requête à pdfCrowd pour convertir une uri
    // retourne un objet de réponse
    final public function convertUri($value,?array $post=null,?array $option=null):Main\Response
    {
        $return = null;

        if($value instanceof Main\Request)
        $value = $value->absolute();

        $request = $this->request('url',$value,$post,$option);
        $return = $request->trigger();

        return $return;
    }


    // convertString
    // lance une requête à pdfCrowd pour convertir une string
    // retourne un objet de réponse
    final public function convertString($value,?array $post=null,?array $option=null):Main\Response
    {
        $return = false;

        if($value instanceof Main\File)
        $value = $value->read();

        $request = $this->request('text',$value,$post,$option);
        $return = $request->trigger();

        return $return;
    }
}

// init
PdfCrowd::__init();
?>