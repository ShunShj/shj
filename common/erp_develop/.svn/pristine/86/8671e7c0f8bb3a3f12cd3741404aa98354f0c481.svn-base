<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use app\common\help\Help;

// 应用公共文件
function getProportionOneToOne(){
    $firstday = date("Y-m");
    $data['proportion_time'] = date('Ymd',strtotime("$firstday +1 month -1 day"));
   $getProportionOneToOne = Help::callSoaErp('post','/system/getProportionOneToOne',$data);
//   var_dump($getProportionOneToOne);exit;
}

if (!function_exists("to_JS_date_format")) {
    /*
     * Matches each symbol of PHP date format standard
     * with jQuery equivalent codeword
     * @author Tristan Jahier
     */
    function to_JS_date_format($php_format, $type = 1)
    {
        /*
         * $type 1 ==>jquery ui
         * $type 2 ==>masked input
         * $type 3 ==>date js
         */
        switch ($type) {
            case 3:
                $SYMBOLS_MATCHING = array(
                    // Day
                    'd' => 'dd',
                    'D' => 'ddd',
                    'j' => 'd',
                    'l' => 'dddd',
                    'N' => '',
                    'S' => '',
                    'w' => '',
                    'z' => '',
                    // Week
                    'W' => '',
                    // Month
                    'F' => '',
                    'm' => 'MM',
                    'M' => 'MMM',
                    'n' => 'M',
                    't' => '',
                    // Year
                    'L' => '',
                    'o' => '',
                    'Y' => 'yyyy',
                    'y' => 'yy',
                    // Time
                    'a' => 't',
                    'A' => 'tt',
                    'B' => '',
                    'g' => 'h',
                    'G' => 'H',
                    'h' => 'hh',
                    'H' => 'HH',
                    'i' => 'mm',
                    's' => 'ss',
                    'u' => ''
                );
                break;
            case 2:
                $SYMBOLS_MATCHING = array(
                    // Day
                    'd' => '99',
                    'D' => 'aaa',
                    'j' => '99',
                    'l' => '',
                    'N' => '9',
                    'S' => '',
                    'w' => '',
                    'z' => '999',
                    // Week
                    'W' => '',
                    // Month
                    'F' => '',
                    'm' => '99',
                    'M' => 'aaa',
                    'n' => '99',
                    't' => '99',
                    // Year
                    'L' => '',
                    'o' => '',
                    'Y' => '9999',
                    'y' => '99',
                    // Time
                    'a' => 'aa',
                    'A' => 'aa',
                    'B' => '999',
                    'g' => '99',
                    'G' => '99',
                    'h' => '99',
                    'H' => '99',
                    'i' => '99',
                    's' => '99',
                    'u' => ''
                );
                break;
            case 1:
            default:
                $SYMBOLS_MATCHING = array(
                    // Day
                    'd' => 'dd',
                    'D' => 'D',
                    'j' => 'd',
                    'l' => 'DD',
                    'N' => '',
                    'S' => '',
                    'w' => '',
                    'z' => 'o',
                    // Week
                    'W' => '',
                    // Month
                    'F' => 'MM',
                    'm' => 'mm',
                    'M' => 'M',
                    'n' => 'm',
                    't' => '',
                    // Year
                    'L' => '',
                    'o' => '',
                    'Y' => 'yy',
                    'y' => 'y',
                    // Time
                    'a' => '',
                    'A' => '',
                    'B' => '',
                    'g' => '',
                    'G' => '',
                    'h' => '',
                    'H' => '',
                    'i' => '',
                    's' => '',
                    'u' => ''
                );
                break;
        }
        $jqueryui_format = "";
        $escaping = false;
        for ($i = 0; $i < strlen($php_format); $i++) {
            $char = $php_format[$i];
            if ($char === '\\') // PHP date format escaping character
            {
                $i++;
                if ($escaping) $jqueryui_format .= $php_format[$i];
                else $jqueryui_format .= '\'' . $php_format[$i];
                $escaping = true;
            } else {
                if ($escaping) {
                    $jqueryui_format .= "'";
                    $escaping = false;
                }
                if (isset($SYMBOLS_MATCHING[$char]))
                    $jqueryui_format .= $SYMBOLS_MATCHING[$char];
                else
                    $jqueryui_format .= $char;
            }
        }
        return $jqueryui_format;
    }
}


function base_url($url){
    return '/'.$url;
}


