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
    protected  $hostname = null;
    protected  $port = null;
    protected  $timeout=0;            //连接时长(可选, 默认为 0 ，无限链接时间)

    public  function __construct( $config , $showException=true )
    {
        $this->hostname = $config['hostname'];
        $this->port = $config['port'];
        $redis = new \Redis();
        $redis->connect($this->hostname,$this->port,$this->timeout);
        if( $showException && !$redis->ping() ) throw new \Exception('Redis server cannot be connected');
        base::$app->redis = $redis;
    }

    public function __set( $key, $value ){
        $this->$key = $value;
    }

    public function __get( $key ){
        return $this->$key;
    }
}