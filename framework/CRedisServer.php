<?php
/**
 * Created by PhpStorm.
 * User: WalkingSun
 * Date: 2018/9/14
 * Time: 16:33
 */

namespace openyii\framework;


class CRedisServer
{
    public static $servers = null;

    public $masterName;

    protected $hostname;
    protected $port;

    public  function  __construct( $config ){
        self::$servers = $config['servers'];
        if (! self::$servers) {
            throw new \Exception("Servers must be set");
        }

        foreach (self::$servers as $val){
            $s = explode(':',$val);
            $c = ['hostname'=>$s[0],'port'=>$s[1]];
            new CRedis( $c , false );
            $redis = base::$app->redis;

            //故障的跳过
            if( empty($redis->socket) ) continue;

            //获取当前server role
//            $infos = $redis->rawCommand('info','Replication');
           $infos = $redis->info();

            if( $infos['role']=='master' ) {
                $this->hostname = $c['hostname'];
                $this->port = $c['port'];
                break;
            }
        }

        if( !$this->hostname ) throw new \Exception('cannot find master');

//        new CRedis(['hostname'=>$this->hostname,'port'=>$this->port]);

        return  base::$app->redis;
    }

}