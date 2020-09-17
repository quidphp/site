<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Service;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// squarePayment
// class that grants methods to use the Square payment gateway
class SquarePayment extends Main\ServiceRequest
{
    // trait
    use Core\_access;


    // config
    protected static array $config = [
        'target'=>[
            'production'=>'https://connect.squareup.com/v2/%method%', // uri target pour square
            'sandbox'=>'https://connect.squareupsandbox.com/v2/%method%'], // uri pour le sandbox
        'sandbox'=>false,
        'postJson'=>true,
        'responseCode'=>[200,400,401,404,415],
        'appId'=>null, // défini la app id
        'website'=>'https://squareup.com',
        'version'=>'2020-08-26',
        'fakeCard'=>['number'=>'5105 1051 0510 5100','cvv'=>'111']
    ];


    // isSandbox
    // retourne vrai si le service est présentement en sandbox
    final public function isSandbox():bool
    {
        return $this->getAttr('sandbox') === true;
    }


    // apiKey
    // retourne la clé d'api
    final public function apiKey():?string
    {
        return $this->getAttr('key');
    }


    // appId
    // retourne la appId
    final public function appId():string
    {
        return $this->getAttr('appId');
    }


    // getWebsite
    // retourne le website
    final public function getWebsite():string
    {
        return $this->getAttr('website');
    }


    // getVersion
    // retourne la version square
    final public function getVersion():string
    {
        return $this->getAttr('version');
    }


    // trigger
    // fait un appel à square, retourne un objet réponse
    final public function trigger(string $httpMethod,string $method,?array $data=null,?array $attr=null):Main\Response
    {
        $return = null;
        $boot = static::boot();
        $secret = $boot->getSecretKey();
        $apiKey = $this->apiKey();
        $targetKey = ($this->isSandbox())? 'sandbox':'production';

        $value = [];
        $data = (array) $data;
        $data['idempotency_key'] = uniqid($secret);
        $attr = Base\Arr::plus($this->attr(),$attr);

        $value['method'] = $httpMethod;

        $headers = [];
        $headers['Square-Version'] = $this->getVersion();
        $headers['Authorization'] = "Bearer $apiKey";
        $headers['Content-Type'] = 'application/json';
        $headers['Accept'] = 'application/json';
        $value['headers'] = $headers;

        $replace = [];
        $replace['method'] = $method;
        $value['uri'] = static::target($replace,$targetKey);

        if($httpMethod === 'post')
        $value['post'] = $data;

        else
        $value['query'] = $data;

        $request = $this->makeRequest($value,$attr);
        $return = $request->trigger();

        return $return;
    }


    // payFromNonce
    // charge un paiement à partir d'un nonce
    final public function payFromNonce(string $nonce,string $amount,array $data):array
    {
        $data['amount_money']['amount'] = (int) ($amount * 100);
        $data['source_id'] = $nonce;
        $data['autocomplete'] = true;
        $response = $this->trigger('post','payments',$data);

        return ['bool'=>$response->is200(),'body'=>$response->body(true)];
    }
}

// init
SquarePayment::__init();
?>