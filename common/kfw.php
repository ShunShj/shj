<?php
/**
 * Created by PhpStorm.
 * User: Shj
 * Date: 2018/8/1
 * Time: 16:19
 * 框架核心类
 */
namespace common;
use common\lib\log;
use common\lib\route;

class kfw{
    public static $classMap = array();
    public $assign = array();

    /**
     *启动框架
     */
    static public function run(){
        log::init();
        $route = new route();
        $ctrlClass = $route->ctrl;
        $action = $route->action;
        $ctrlFile = APP.'/ctrl/'.$ctrlClass.'Ctrl.php';
        $ctrlClass = '\\'.MODULE.'\ctrl\\'.$ctrlClass.'Ctrl';
        if(is_file($ctrlFile)){
            include $ctrlFile;
            $ctrl = new $ctrlClass;
            $ctrl->beforeAction($action);
            $ctrl->$action();
            $ctrl->afterAction($action);
            log::log('ctrl:'.$ctrlClass.'  action:'.$action);
        }else{
            log::log('找不到控制器'.$ctrlClass);
            throw new \Exception('找不到控制器'.$ctrlClass);
        }
    }

    /**
     * 自动加载类库
     */
    static public function load($class){
        if(isset($classMap[$class])){
            return true;
        }else{
            $class = str_replace('\\','/',$class);
            $file = WEB_PATH.'/'.$class.'.php';
            if(is_file($file)){
                include $file;
                self::$classMap[$class] = $class;
            }else{
                return false;
            }
        }
    }
}
