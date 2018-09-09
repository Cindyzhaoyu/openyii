<?php
/**
 * Created by PhpStorm.
 * User: MW
 * Date: 2018/9/9
 * Time: 13:08
 */

namespace openyii\framework;


abstract class CSession
{
    /**打开
     * @return mixed
     */
    abstract protected function open();

    /**关闭
     * @return mixed
     */
    abstract protected function close();

    /**读取
     * @return mixed
     */
    abstract protected function read( $id );

    /**写入
     * @return mixed
     */
    abstract protected function write( $id, $value , $timeout );

    /**销毁
     * @return mixed
     */
    abstract protected function destory( $id );

    /**回收
     * @return mixed
     */
    abstract protected function gc( $id );

}