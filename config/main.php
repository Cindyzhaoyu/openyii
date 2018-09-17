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

//    'db' => require(__DIR__ . '/../config/db.php'),         //启用mysql
//    'urlManager' => require(__DIR__ . '/urlmanage.php'),    //启用restful
    'params' => require(__DIR__ . '/params.php'),
//        'redis' => [                                            //启用redis
//        'class' => 'openyii\framework\CRedis',
//        'hostname' => '192.168.33.30',
//        'port' => 6379,
//    ],
    'redis' => [                                            //启用redis
        'class' => 'openyii\framework\CRedisHa',
        'sentinels' => [
            '192.168.33.30:6379',
//            '192.168.33.30:5001',
            '192.168.33.30:6380',
            '192.168.33.30:6381',
        ],
        'masterName'=>'redis-master',
    ],
//    'session'=>[                                            //session存贮
//        'class'=>'openyii\framework\CSessionRedis',
//        'timeout'=>3600,
//        'keyPrefix'=>'sun',
//    ],

];