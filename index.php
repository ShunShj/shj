<?php
/**
 * Created by PhpStorm.
 * User: shj
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
