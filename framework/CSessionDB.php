<?php
/**
 * Created by PhpStorm.
 * User: WalkingSun
 * Date: 2018/9/9
 * Time: 18:09
 */

namespace openyii\framework;

class CSessionDB extends CSession
{
    protected $DBInstance;
    public $keyPrefix;          //键前缀
    public $lifeTime = 3600;
    public $tableName;          //seesion存储表

    public function __construct( $config )
    {
        $this->lifeTime = !empty($config['timeout'])?$config['timeout']:$this->lifeTime;
        $this->keyPrefix = $config['keyPrefix'];
        if( empty($config['tableName']) ) throw new \Exception('please provide db tablename');
        $this->tableName = $config['tableName'];
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
        $this->DBInstance = base::$app->db;
        return true;
    }

    function close()
    {
        return true;
    }

    function read($id)
    {
        $session = Connection::select($this->tableName,[],['jq_SessionId'=>$id]);
        if( $session ){
            Connection::update($this->tableName,['jq_ExpireTime'=>time()+$this->lifeTime],['jq_SessionId'=>$id]);
        }

        return   !empty($session[0]['jq_SessionValue'])?($session[0]['jq_SessionValue']):'';
    }

    function write($id, $value)
    {
        $session = Connection::select($this->tableName,[],['jq_SessionId'=>$id]);
        if( $session ){
            return Connection::update($this->tableName,['jq_SessionValue'=>$value,'jq_ExpireTime'=>time()+$this->lifeTime],['jq_SessionId'=>$id]);
        }else{
            return Connection::insert($this->tableName,['jq_SessionId'=>$id,'jq_SessionValue'=>$value,'jq_ExpireTime'=>time()+$this->lifeTime]);
        }
    }

    function destroy($id)
    {
        return Connection::delete($this->tableName,['jq_SessionId'=>$id])?true:false;
    }

    function gc($lifetime)
    {
        $this->DBInstance->exec("DELETE FROM {$this->tableName} WHERE jq_ExpireTime<".time());
        return true;
    }

}