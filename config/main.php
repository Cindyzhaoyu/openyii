<?php
/**
 * Created by PhpStorm.
 * User: zhangxin
 * Date: 2017/12/18
 * Time: 15:28
 */

return [
    'id' => 'basic',                    //设置app id
    "defaultController" =>"index",
    "defaultAction" =>"index",
    'defaultRoute'=>'index/index',

    "name" =>"my Application",

    'db' => require(__DIR__ . '/../config/db.php'),
//    'urlManager' => require(__DIR__ . '/urlmanage.php'),    //启用restful
    'params' => require(__DIR__ . '/params.php'),
    'redis' => [
        'class' => 'openyii\framework\CRedis',
        'hostname' => '192.168.33.30',
        'port' => 6379,
//        'database' => 1,
    ],
    'session'=>[
        'class'=>'openyii\framework\CSession',
        'timeout'=>3600,
        'keyPrefix'=>'sun',
//        'cookieParams' => [
//            'path' => '/',
//            'domain' => ".qian.com",
//        ],
    ],

];