<?php
/**
 * Created by PhpStorm.
 * User: Shj
 * Date: 2018/8/1
 * Time: 17:32
 * 日志类
 */
namespace common\lib;
class log{

    static $class;

    /**
     * 1.确定日志的存储方式
     * 2.写日志
     */
    static public function init(){
        //确定存储方式
        $driver = conf::get('DRIVER','log');
        $class = '\common\lib\driver\log\\'.$driver;
        self::$class = new $class;
    }

    static public function log($name,$file = 'log'){
        self::$class->log($name,$file);
    }

}