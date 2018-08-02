<?php
/**
 * Created by PhpStorm.
 * User: Shj
 * Date: 2018/8/2
 * Time: 16:11
 */
namespace common\lib;

use common\common\response;

class Controller{

    public $assign = array();
    const DEFAULT_TYPE = "json";

    /**
     * 执行前调用
     * @param $action
     */
    public function beforeAction($action){
        log::log('beforeAction:'.$action);
    }

    /**
     * 执行后调用
     * @param $action
     */
    public function afterAction($action){
        log::log('afterAction:'.$action);
    }

    /**
     * 绑定数据
     * @param $name
     * @param $value
     */
    public function assign($name, $value){
        $this->assign[$name] = $value;
    }

    /**
     * 调用视图
     * @param $fileName
     */
    public function display($fileName){
        $file = APP.'/views/'.$fileName;
        if(is_file($file)){
            extract($this->assign);
            include $file;
        }
    }

    /**
     * 返回json数据
     * @param $code
     * @param $message
     * @param $data
     */
    public function renderJson($code, $message, $data){
        response::json($code,$message,$data);
    }

    public function success($data){
        $this->renderJson(0,'成功',$data);
    }

    public function error($code,$message,$data){
        $this->renderJson($code,$message,$data);
    }

    public function redirect($url){
        header('location:'.$url);
        exit();
    }
}