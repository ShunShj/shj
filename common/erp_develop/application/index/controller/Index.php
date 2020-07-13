<?php
namespace app\index\controller;
use app\common\help\Help;
use app\index\controller\Base;
use think\Session;

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
//        Session::delete('website_uuid');
        //p(session('system_config_id'));
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

    public function changeStatus(){

        $table_name = input("table_name");
        $table_id = input("table_id");
        $table_id_name = input("table_id_name");
        $status = input("status");
        $data = [
            "table_name"=>"$table_name",
            "table_id"=>$table_id,
            'table_id_name'=>"$table_id_name",
            'status'=>$status,
        ];

        return $this->callSoaErp('post','/index/changeStatus',$data);
    }
}
