<?php
/**
 * Created by PhpStorm.
 * User: MW
 * Date: 2018/9/14
 * Time: 16:33
 */

namespace openyii\framework;


class CRedisHa //extends CRedis
{
    public static $sentinels = null;

    protected  $_socket;

    public $masterName;

    public  function  __construct( $config ){
        self::$sentinels = $config['sentinels'];
        if (! self::$sentinels) {
            throw new \Exception("Sentinels must be set");
        }

        foreach (self::$sentinels as $val){
            $s = explode(':',$val);
            new CRedis(['hostname'=>$s[0],'port'=>$s[1]]);
            $redis = base::$app->redis;
//            $infos = $redis->rawCommand('SENTINEL', 'masters');

            //根据所配置的主库redis名称获取对应的信息
//            $infos = $redis->rawCommand('SENTINEL', 'master', $config['masterName']);

            //根据所配置的主库redis名称获取其对应从库列表及其信息
//            $infos = $redis->rawCommand('SENTINEL', 'slaves', $config['masterName']);

//获取特定名称的redis主库地址
//            $infos = $redis->rawCommand('SENTINEL', 'get-master-addr-by-name', $config['masterName']);
            $infos = $redis->rawCommand('info','Replication');
            print_r( $infos );die;
            new CRedis(['hostname'=>$infos[0],'port'=>$infos[1]]);

            var_dump( base::$app->redis);die;


            //todo 找出master，返回实例
            return $redis;
        }
        throw new \Exception('cannot find master');
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