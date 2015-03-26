<?php
/**
 * Created by PhpStorm.
 * User: bozh
 * Date: 3/23/15
 * Time: 12:34 PM
 */

class User extends ModelBase {

    public $email = '';
    public $name = '';
    public $password = '';
    public $created = 0;


    public function beforeCreate(){
        $this->created = time();
        $this->num = self::getKey();
    }


    public static function getLogged($user){
        if($user){
            $user = self::findFirst([['_id' => new MongoId($user)]]);
            return $user;
        } else{
            return null;
        }
    }
}