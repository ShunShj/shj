<?php
namespace app\index\controller;
use app\common\help\Help;
use app\index\controller\Base;
use think\Request;
use Underscore\Types\Arrays;

class Language extends Base
{

    /**
     * 获取语言
     * 胡
     * status 非必填项 可查看日志系统参数具体意义
     */
    public function getLanguage(){

        $data = [
            'status'=>2

        ];
        $result =  $this->callSoaErp('post','language/getLanguage',$data);
        return $this->outPut($result);
    }
    //获取语言通过language_id
    public function getLanguageByLanguageId(){
        $language_id = input('language');
        $data = [
            'language_id'=>$language_id

        ];
        $result =  $this->callSoaErp('post','language/getLanguage',$data);
        return $this->outPut($result);


    }

    /***
     * 多语言设置
     */
    public function  multilingualSetting(){
        //获取语种
        $getLanguage = $this->callSoaErp('post','/system/getLanguage',['status'=>1]);
        $this->assign('LanguageAr',$getLanguage['data']);

        return $this->fetch('multilingual_setting');
    }

    /****
     * 编辑多语言
     */
    public function editMultilingualSetting(){

        $post = Request::instance()->param();

        $original_table_name = Arrays::get($post,'original_table_name');
        $original_table_field_name = Arrays::get($post,'original_table_field_name');
        $original_table_id = Arrays::get($post,'original_table_id');
        $multilingual_id = Arrays::get($post,'multilingual_id');
        $language = Arrays::get($post,'language',[]);

        $d['original_table_name'] = $original_table_name;
        $d['original_table_field_name'] = $original_table_field_name;
        $d['original_table_id'] = $original_table_id;
        $d['operation_id'] = session('user')['user_id'];
        $d['operation_time'] = time();
        $d['status'] = 1;
//        var_dump($d);
        foreach($language as $k=>$v){
            $d['language_id'] = $k;
            $d['translate'] = $v;
            if($multilingual_id[$k]){
                $d['multilingual_id'] = $multilingual_id[$k];
            }else{
                unset($d['multilingual_id']);
            }
            $r = $this->callSoaErp('post','/multilingual/editMultilingual',$d);
//            var_dump($d);
        }
        return ['code'=>200,'msg'=>''];

    }




}
