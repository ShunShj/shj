<?php
/**
 * Created by PhpStorm.
 * User: Shj
 * Date: 2018/8/1
 * Time: 12:17
 */
namespace common\common;
class response{
    /**
     * 按json方式输出通信数据
     * @param integer $code 状态码
     * @param string $message 提示信息
     * @param array $data 数据
     * return string
     */
    public static function json($code,$message = '',$data = array()){

        if(!is_numeric($code)){
            return '';
        }

        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        );

        echo json_encode($result,JSON_UNESCAPED_UNICODE);
        exit;

    }
}