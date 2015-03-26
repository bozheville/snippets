<?php
/**
 * Created by PhpStorm.
 * User: bozh
 * Date: 3/23/15
 * Time: 1:15 PM
 */

$router = new \Phalcon\Mvc\Router();

$routes = array(
    "/{controller}/{id:[1-9][0-9a-z-]*}" => [
            'action' => 'view'
        ],
    "/add" => [
        'controller' => 'snippets',
        'action' => 'add'
    ],
    "/{page:[0-9]+}" => [
        'controller' => 'snippets',
        'action' => 'index'
    ]
);

return $routes;
