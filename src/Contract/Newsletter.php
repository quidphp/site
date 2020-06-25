<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */

namespace Quid\Site\Contract;

// newsletter
// interface to describe methods for a newsletter third-party service
interface Newsletter
{
    // memberInfo
    // retourne l'information sur un utisateur dans la liste de l'infolettre à partir d'un email
    public function memberInfo(string $email,?array $post=null):?array;


    // isSubscribed
    // retourne vrai si le membre existe dans la liste de l'infolettre
    public function isSubscribed(string $email,?array $post=null):bool;


    // members
    // retourne tous les membres inscrits dans la liste de l'infolettre
    public function members(?array $post=null):array;


    // subscribe
    // inscrit un utilisateur à une liste de l'infolettre
    public function subscribe(string $email,$vars=[],?array $post=null,bool $isSubscribed=true):?array;


    // subscribeBool
    // inscrit un utilisateur à une liste de l'infolettre et retourne un vrai ou faux
    public function subscribeBool(string $email,$vars=[],?array $post=null,bool $isSubscribed=true):bool;


    // unsubscribe
    // désinscrit un utilisateur à une liste de l'infolettre
    public function unsubscribe(string $email,?array $post=null):?array;
}
?>