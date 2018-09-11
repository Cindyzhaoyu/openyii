<?php
/**
 * Created by PhpStorm.
 * User: WalkingSun
 * Date: 2018/9/9
 * Time: 13:08
 */

namespace openyii\framework;


abstract class CSession
{
    /**打开
     * @param $savePath
     * @param $sessionName
     * @return mixed
     */
    abstract protected function open($savePath, $sessionName);

    /**关闭
     * @return mixed
     */
    abstract protected function close();

    /**读取
     * @param $id sessionid
     * @return mixed
     */
    abstract protected function read( $id );

    /**写入
     * @param $id sessionid
     * @param $value 设置值
     * @return mixed
     */
    abstract protected function write( $id, $value  );

    /**销毁
     * @param $id sessionid
     * @return mixed
     */
    abstract protected function destroy( $id );

    /**回收
     * @param $lifetime 生命周期
     * @return mixed
     */
    abstract protected function gc( $lifetime );

}