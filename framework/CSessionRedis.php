<?php
/**
 * Created by PhpStorm.
 * User: WalkingSun
 * Date: 2018/9/9
 * Time: 18:09
 */

namespace openyii\framework;

class CSessionRedis extends CSession
{
    protected $redisInstance;
    public $keyPrefix;          //键前缀
    public $lifeTime;

    public function __construct( $keyPrefix,$lifeTime=3600 )
    {
        $this->lifeTime = $lifeTime;
        $this->keyPrefix = $keyPrefix;
        session_set_save_handler(
            array($this, 'open'),
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'gc')
        );
        if ($this->keyPrefix === null) {
            $this->keyPrefix = substr(md5(base::$app->id), 0, 5);
        }
        session_start();            //以全局变量形式保存在一个session中并且会生成一个唯一的session_id，
    }

    function open($savePath, $sessionName)
    {
        $this->redisInstance = base::$app->redis;
        return true;
    }

    function close()
    {
        return true;
    }

    function read($id)
    {
        if( !$this->redisInstance->exists($this->calculateKey($id)) ){
            $this->redisInstance->set( $this->calculateKey($id),'' );
        }
        return $this->redisInstance->get( $this->calculateKey($id) );
    }

    function write($id, $value)
    {
        return $this->redisInstance->set($this->calculateKey($id), $value, $this->lifeTime)?true:false;
    }

    function destroy($id)
    {
        $this->redisInstance->delete($this->calculateKey($id));
        if( !$this->redisInstance->exists($this->calculateKey($id)) ){
            return false;
        }
        return true;
    }

    function gc($lifetime)
    {
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