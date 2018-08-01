<?php
/**
 * Created by PhpStorm.
 * User: Shj
 * Date: 2018/8/1
 * Time: 12:14
 */

function p($var){
    if(is_bool($var)){
        var_dump($var);
    }else if(is_null($var)){
        var_dump(NULL);
    }else{
        echo "<pre style='position:relative;z-index:1000;padding:10px;border-radius:5px;background:#F5F5F5;border:1px solid #aaa;font-size:14px;line-height:18px;opacity:0.9;'>".print_r($var, true)."</pre>";
    }
}

function post($name,$default = false,$fitt = false){
    if(isset($_POST[$name])){
        if($fitt){
            switch ($fitt){
                case 'int':
                    if(is_numeric($_POST[$name])){
                        return $_POST[$name];
                    }else{
                        return $default;
                    }
                    break;
                default:;
            }
        }else{
            return $_POST[$name];
        }
    }else{
        return $default;
    }
}