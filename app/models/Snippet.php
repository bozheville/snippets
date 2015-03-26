<?php
/**
 * Created by PhpStorm.
 * User: bozh
 * Date: 3/23/15
 * Time: 12:34 PM
 */

class Snippet extends ModelBase {
    public $title = '';
    public $body = '';
    public $num = 0;
    public $tags = [];
    public $created = [];
    public $updated = [];

    const ON_PAGE = 10;


    public function beforeCreate(){
        $di = Phalcon\DI::getDefault();
        $this->created = ['user' => $di->getSession()->get('session_id'), 'time' => time()];
        $this->num = self::getKey();
    }

    public function beforeSave(){
        $tags = preg_split('#\s*,\s*#', trim($this->tags));
        $this->tags = [];
        foreach($tags as $tag){
            $this->tags[preg_replace('#[^a-z]#', '_', strtolower($tag))] = trim($tag);
        }
    }

    public static function getPage($page = 1, $tags = []){
        $skip = ($page - 1) * self::ON_PAGE;
        return self::find([
            [],
            'sort' => ['num' => -1],
            'skip' => (int) $skip,
            'limit' => (int) self::ON_PAGE
        ]);
    }

    public static function getPagination($page = 1, $tags = []){
        $db = new MongoClient();
        $db = $db->selectDB('snippets');
        $total = $db->snippet->count();
        $total_pages = ceil($total / self::ON_PAGE);
        $pager = [];
        foreach([1,2,3, $page - 2, $page - 1, $page, $page + 1, $page + 2, $total_pages - 2, $total_pages -1 , $total_pages] as $i){
            if($i > 0 && $i <= $total_pages && !in_array($i, $pager)){
                $pager[] = $i;
            }
        }
        $pager = array_filter($pager);
        if(count($pager) < 2){
            return null;
        }
        $pager = ['pages' => $pager, 'current' => $page];
        if($page > 1){
            $pager['prev'] = $page - 1;
        }
        if($page < $total_pages){
            $pager['next'] = $page + 1;
        }
        return $pager;
    }

}