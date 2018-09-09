<?php
/**
 * Created by PhpStorm.
 * User: MW
 * Date: 2018/9/9
 * Time: 18:09
 */

namespace openyii\framework;


class CSessionRedis extends CSession
{
    protected static $instance;        //操作实例
    protected $redisInstance;
    public $keyPrefix;          //键前缀

    private function __construct( $keyPrefix )
    {
        $this->keyPrefix = $keyPrefix;
        session_set_save_handler(
            [$this, 'open'],
            [$this, 'close'],
            [$this, 'read'],
            [$this, 'write'],
            [$this, 'destroy'],
            [$this, 'gc']);
        if ($this->keyPrefix === null) {
            $this->keyPrefix = substr(md5(base::$app->id), 0, 5);
        }
        session_start();            //以全局变量形式保存在一个session中并且会生成一个唯一的session_id，
    }


    /**打开
     * @return mixed
     */
    protected function open(){
        $this->redisInstance = base::$app->redis;
        return true;
    }

    /**关闭
     * @return mixed
     */
    protected function close(){
        return true;
    }

    /**读取
     * @return mixed
     */
    protected function read( $id ){
        if( !$this->redisInstance->exists($this->calculateKey($id)) ){
            return false;
        }
        return $this->redisInstance->get( $this->calculateKey($id) );
    }

    /**写入
     * @return mixed
     */
    protected function write( $id, $value ,$timeout){
        $this->redisInstance->set($this->calculateKey($id), $value, $timeout);
        if( !$this->redisInstance->exists($this->calculateKey($id)) ){
            return false;
        }
        return true;
    }

    /**销毁
     * @return mixed
     */
    protected function destory( $id ){
        $this->redisInstance->delete($this->calculateKey($id));
        if( !$this->redisInstance->exists($this->calculateKey($id)) ){
            return false;
        }
        return true;
    }

    /**回收
     * @return mixed
     */
    protected function gc( $maxLifetime ){
        return true;
    }

    /**
     * 加密key
     * @param $id
     * @return string
     */
    protected function calculateKey($id)
    {
        return $this->keyPrefix . md5(json_encode([__CLASS__, $id]));
    }


}