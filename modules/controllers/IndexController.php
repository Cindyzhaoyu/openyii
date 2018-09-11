<?php
/**
 * Created by PhpStorm.
 * User: zhangxin
 * Date: 2017/12/18
 * Time: 10:04
 */


namespace openyii\modules\controllers;

use openyii\framework\CController;
use openyii\framework\CUrl;
use openyii\modules\models\Article;
use openyii\modules\models\IndexModels;

class IndexController extends CController
{
     public $layouts = "main";

    /**
     * 首页
     */
    public function actionIndex(){
        $this->layouts = false;
        $result = 'Welcome to OpenYii!';

        return $this ->render('index/index',array('result'=>$result));

    }

    /**
     * 列表页
     */
    public function actionList(){

        $data = array(
            'refrence'=>'index/article',
            'test'=>'zx',
        );
        $this ->redirect("index/article",$data);

    }

    public function actionArticle(){

        var_dump($_GET);

    }

    public function actionError(){
        $code = 404;
        Header("HTTP/1.1 {$code} Not Found");
        die;

    }

}
