<?php
/**
 * Created by PhpStorm.
 * User: Shj
 * Date: 2018/8/1
 * Time: 17:02
 * 路由类
 */
namespace common\lib;

class route{
    public $ctrl;
    public $action;

    /**
     * 1. 隐藏index.php
     * 2. 获取URL参数部分
     * 3. 返回对应的控制器和方法
     */
    public function __construct(){
        if(isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] != '/'){
            $path = $_SERVER['PATH_INFO'];
            $patharr = explode('/',trim($path,'/'));
            if(isset($patharr[0])){
                $this->ctrl = $patharr[0];
            }
            unset($patharr[0]);
            if(isset($patharr[1])){
                $this->action = $patharr[1];
                unset($patharr[1]);
            }else{
                $this->action = conf::get('DEFAULT_ACTION','route');
            }

            $count = count($patharr) + 2;
            $i = 2;
            while ($i<$count){
                if(isset($patharr[$i + 1])){
                    $_GET[$patharr[$i]] = $patharr[$i + 1];
                }
                $i = $i + 2;
            }
        }else{
            $this->ctrl = conf::get('DEFAULT_ACTION','route');
            $this->action = conf::get('DEFAULT_ACTION','route');
        }
    }
}