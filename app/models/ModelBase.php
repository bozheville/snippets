<?php
/**
 * Created by PhpStorm.
 * User: bozh
 * Date: 3/12/15
 * Time: 5:08 PM
 */

class ModelBase extends \Phalcon\Mvc\Collection {

    public function toJson(){
        return json_encode($this->toArray());
    }


    public static function getKey(){
        return (int) self::findFirst([[],'sort' => ['num' => -1]])->num + 1;
    }

}