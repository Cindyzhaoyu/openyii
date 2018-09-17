<?php
/**
 * Created by PhpStorm.
 * User: zhangxin
 * Date: 2017/12/18
 * Time: 15:13
 */

namespace openyii\framework;

use openyii\modules\controllers;
class CWebApplication
{
    private static $_app;

    private function __construct($config=null){
        //获取配置文件
        if(is_string($config));
            $config = require $config;

        if(is_array($config)){
            foreach ($config as $key =>$val){
                $this ->$key = $val;
            }
        }

    }

    /**
     * 静态方法用于创建它本身的静态私有对象
     * @return CWebApplication
     */
    public static function createApplication($config=null){

        if(self::$_app == null){
            self::$_app = new CWebApplication($config);
        }
        return self::$_app;

    }

    public static function app(){
        return self::$_app;
    }


    /**
     * 创建application执行方法
     */
    public function run(){

        $config = self::$_app;

        base::$app = new \stdClass();

        CRequest::init( $config );

        base::$app->id = isset($config->id)?$config->id:'';

        if( isset($config->db) ){
            $Connection = new Connection( $config->db );
            base::$app->db = $Connection::$pdo;
        }

        if( isset($config->redis) ){
            new $config->redis['class']( $config->redis );
        }

        if( isset($config->session) ){
            new $config->session['class']( $config->session['keyPrefix'] );
        }

        if( CRequest::$route ){
            self::commonSite(CRequest::$route);
        }else{
            $this::commonSite(trim(self::$_app ->defaultRoute));
        }

    }


    /**
     * 站点跳转方法
     * @param $route
     */
    private static function commonSite($route){

        header("Content-type: text/html; charset=utf-8");
        $pos = strpos($route,'/');   // 查找字符串在另一字符串中第一次出现的位置

        $defaultController = substr($route,0,$pos);
        $defaultController = strtolower($defaultController); //  把所有字符转换为小写
        $defaultAction = (string) substr($route,$pos+1);

        $className = ucfirst($defaultController)."Controller";      // 函数把字符串中的首字符转换为大写
        $functionName = "action".ucfirst($defaultAction);

        if(is_file(__DIR__."/../modules/controllers/".$className.".php")){

            if(!class_exists($className,false)){

                //获取控制器对象
                $reflector = new \ReflectionClass( "openyii\modules\controllers\\{$className}");
                $instance  = $reflector->newInstance( );  // 相当于实例化 类

                if( !$reflector->hasMethod($functionName) ){
//                    $m = $reflector->getmethod('http_output');
//                    $m->invokeArgs($instance,['404']);
                    self::commonSite("index/error");
                }

                //反射执行动作方法
                $method = $reflector->getmethod($functionName);
                $method->invoke($instance);
            }

        }else{

            self::commonSite("index/error");

        }

    }

    /**
     * 默认路由设置
     */
    private static function defaultSite(){

        $route = trim(self::$_app ->defaultRoute);

        if($route ==""){
            $route = 'index/index';
        }

        self::commonSite($route);

    }


}