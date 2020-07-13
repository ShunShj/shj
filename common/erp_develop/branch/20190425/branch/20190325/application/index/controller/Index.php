<?php
namespace app\index\controller;
use app\common\help\Help;
use app\index\controller\Base;

class Index extends Base
{
    public function __initialize()
    {

    }

    public function test(){


        return $this->fetch('index');
    }
    public function index()
    {
	
        return $this->fetch('index');
    }

    public function test1(){
        //测试语言
        /*
        $data=[

            'name_zh-cn'=>'中文',
            'user_id'=>123,
            'name_en-us'=>'chinese',
            'lang'=>'zh-cn',
            'language_id'=>1

        ];
        */

        //测试货币
        $data = [
            /*
                'name_zh-cn'=>'人名币',
                'user_id'=>213,
                'name_en-us'=>'rmb',
                'symbol'=>'¥',
                'unit'=>'cny',¥
                */
            //'symbol'=>'¥',
            'user_id'=>1423,
            'language_id'=>1

        ];
        $result =  $this->callSoaErp('post','/config/updateLanguageByLanguageId',$data);
        return json_encode($result);
    }
}
