<?php
/**
 * Created by PhpStorm.
 * User: WalkingSun
 * Date: 2018/9/14
 * Time: 16:33
 */

namespace openyii\framework;


class CRedisHa
{
    public static $sentinels = null;

    protected  $_socket;

    public $masterName;

    protected $hostname;
    protected $port;

    public  function  __construct( $config ){
        self::$sentinels = $config['sentinels'];
        if (! self::$sentinels) {
            throw new \Exception("Sentinels must be set");
        }

        $redis_master = [];
        $num = ceil(count(self::$sentinels)/2);
        foreach (self::$sentinels as $val){
            $s = explode(':',$val);
            new CRedis(['hostname'=>$s[0],'port'=>$s[1]],false);
            $redis = base::$app->redis;

            //故障的跳过
            if( empty($redis->socket) ) continue;

            //获取master信息
//            $infos = $redis->rawCommand('SENTINEL', 'masters');

            //根据所配置的主库redis名称获取对应的信息
            $infos = $redis->rawCommand('SENTINEL', 'master', $config['masterName']);

            //根据所配置的主库redis名称获取其对应从库列表及其信息
//            $infos = $redis->rawCommand('SENTINEL', 'slaves', $config['masterName']);

            //获取特定名称的redis主库地址
//            $infos = $redis->rawCommand('SENTINEL', 'get-master-addr-by-name', $config['masterName']);
//            $infos = $redis->rawCommand('info','Replication');

            //超过一半哨兵认为这是master，则当前是master
            $redis_master[$infos[3].':'.$infos[5]] = !isset($redis_master[$infos[3].':'.$infos[5]]) ?1:++$redis_master[$infos[3].':'.$infos[5]];
            if( $redis_master[$infos[3].':'.$infos[5]]>= $num ) {
                $this->hostname = $infos[3];
                $this->port = $infos[5];
                break;
            }
        }

        if( !$this->hostname ) throw new \Exception('cannot find master');
        new CRedis(['hostname'=>$this->hostname,'port'=>$this->port]);

        return  base::$app->redis;
    }


    //这个方法可以将以上sentinel返回的信息解析为数组
    function parseArrayResult(array $data)
    {
        $result = array();
        $count = count($data);
        for ($i = 0; $i < $count;) {
            $record = $data[$i];
            if (is_array($record)) {
                $result[] = parseArrayResult($record);
                $i++;
            } else {
                $result[$record] = $data[$i + 1];
                $i += 2;
            }
        }
        return $result;
    }

}