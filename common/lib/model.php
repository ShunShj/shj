<?php
/**
 * Created by PhpStorm.
 * User: Shj
 * Date: 2018/8/2
 * Time: 16:38
 */
namespace common\lib;
/**
 * 模型类
 */
class Model extends \Medoo\medoo{

    public function __construct(){
        $option = conf::all('database');
        parent::__construct($option);
    }
}