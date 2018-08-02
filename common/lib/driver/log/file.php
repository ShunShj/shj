<?php
/**
 * Created by PhpStorm.
 * User: Shj
 * Date: 2018/8/2
 * Time: 17:06
 */
namespace common\lib\driver\log;

use common\lib\conf;

class file{

    public $path;  //日志存放位置

    public function __construct(){
        $conf = conf::get('OPTION','log');
        $this->path = $conf['PATH'];
    }

    public function log($message,$file = 'log'){
        /**
         * 1. 确定文件存储位置是否存在
         *    不存在新建目录
         * 2. 写入日志
         */
        if(!is_dir($this->path.date('Ymd'))){
            mkdir($this->path.date('Ymd'),0777,true);
        }
        $message = date('Y-m-d H:i:s').json_encode($message).PHP_EOL;
        return file_put_contents($this->path.date('Ymd').'/'.$file.'.php', $message, FILE_APPEND);
    }
}