<?php
/**
 * Created by PhpStorm.
 * User: Shj
 * Date: 2018/7/27
 * Time: 18:23
 */

/**
 * 入口文件
 * 1. 定义常量
 * 2. 加载函数库
 * 3. 启动框架
 */

define('WEB_PATH',str_replace('\\','/',dirname(realpath(__FILE__))));
define('APP',WEB_PATH.'/app');
define('COMMON',WEB_PATH.'/common');
define('DEBUG',true);
define('MODULE', 'app');
include "vendor/autoload.php";

if(DEBUG){
    $whoops = new \Whoops\Run();
    $option = new \Whoops\Handler\PrettyPageHandler();
    $option->setPageTitle('你造不造你错了');
    $whoops->pushHandler($option);
    $whoops->register();
    ini_set('display_errors','On');
}else{
    ini_set('display_errors','Off');
}

include COMMON.'/common/function.php';
include COMMON.'/kfw.php';