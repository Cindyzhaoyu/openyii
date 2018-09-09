<?php
/**
 * Created by PhpStorm.
 * User: MW
 * Date: 2018/9/9
 * Time: 18:19
 */

namespace openyii\framework;

/**
 * Class CRedis redis操作类
 * @package openyii\framework
 */
class CRedis extends base
{
    protected $hostname;
    protected  $port;
    protected  $timeout=0;            //连接时长(可选, 默认为 0 ，丌限链接时间)

    public  function __construct( $hostname, $port)
    {
        $redis = new \Redis();
        $redis->connect($hostname,$port,$this->timeout);
        base::$app->redis = $redis;
    }


}