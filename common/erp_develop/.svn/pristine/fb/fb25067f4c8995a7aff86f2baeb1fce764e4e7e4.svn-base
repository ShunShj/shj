<?php

namespace app\index\controller;

use app\common\help\Help;
use think\Request;

class Demo extends Base
{ 
   
    public function Demo(){

    }

    /**
     * 多选案例
     * 韩
     */
    public function formSelects(){
        return $this->fetch('formselects');
    }

    /**
     * 文件上传案例-界面
     * 韩
     */
    public function showUpload(){
        return $this->fetch('upload');
    }

    /**
     * 文件上传案例-执行
     * 韩
     */
    public function uploadFile(){

        $file = request()->file('file');

        if($file){
            $file_result = $file->getInfo();
            $info = $file->move(ROOT_PATH . 'public' . DS . 'static'.DS.'uploads'.DS.'images');
            $image_name = $file_result['name'];
            //获取后缀名
            $image_name = substr($image_name,strpos($image_name,'.')+1);
            $temp_name = $file_result['tmp_name'];
            $temp_name = str_replace('tmp',$image_name,$temp_name);
            $url = config('soaupload')['ip'].':'.config('soaupload')['port'];         
            $result = help::curlImages($info->getPathname(), $url."/index/uploadImages");
	
            if($result){
                $result = json_decode($result,true);
                $result['get'] = $_GET;
                $result['name'] = $file_result['name'];
                return json_encode($result);

            }else{
                $d['data'] = '/static/uploads/images/'.$info->getSaveName();
                $d['name'] = $file_result['name'];
                $d['code'] = 200;
                $d['get'] = $_GET;
                echo json_encode($d);
            }
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }

    }

    
    
    

    public function imgUploadFile(){

        $file = request()->file('file');

        if($file){
            $file_result = $file->getInfo();
            $info = $file->move(ROOT_PATH . 'public' . DS . 'static'.DS.'uploads'.DS.'images');

            $image_name = $file_result['name'];
            //获取后缀名
            $image_name = substr($image_name,strpos($image_name,'.')+1);



            $temp_name = $file_result['tmp_name'];
            $temp_name = str_replace('tmp',$image_name,$temp_name);
            $url = config('soaupload')['ip'].':'.config('soaupload')['port'];


            $result2 = help::curlImages($info->getPathname(), $url."/index/uploadImages");

            if($result2){
                $result2 = json_decode($result2,true);
                $result['code'] = 0;
                $result['msg'] = '';
                $result['data']['src'] = $result2['data'];
                $result['data']['title'] = $_GET;

                return json_encode($result);
            }else{
                $d['data'] = '/static/uploads/images/'.$info->getSaveName();
                $d['code'] = 200;
                $d['get'] = $_GET;
                echo json_encode($d);
            }
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }

    }


    public function uploadOtaFile(){

        $file = request()->file('file');

        if($file){
            $file_result = $file->getInfo();
            $info = $file->move(ROOT_PATH . 'public' . DS . 'static'.DS.'uploads'.DS.'images');

            $image_name = $file_result['name'];
            //获取后缀名
            $image_name = substr($image_name,strpos($image_name,'.')+1);



            $temp_name = $file_result['tmp_name'];
            $temp_name = str_replace('tmp',$image_name,$temp_name);
            $url = config('soaupload')['ip'].':'.config('soaupload')['port'];


            $result = help::curlImages($info->getPathname(), $url."/index/uploadImages");

            $a = json_decode($result,true);
            $data['url'] = $a['data'];
            $data['size'] = $file_result['size'];
            $data['choose_company_id'] = session('user')['company_id'];
            $data['website_uuid'] = session('website_uuid');
            $data['user_id'] = session('user')['user_id'];
            $this->callSoaErp('post','/ota_system/addOtaMediaPool',$data);

            if($result){
                $result = json_decode($result,true);
                $result['get'] = $_GET;
                return json_encode($result);

            }else{
                $d['data'] = '/static/uploads/images/'.$info->getSaveName();
                $d['code'] = 200;
                $d['get'] = $_GET;
                echo json_encode($d);
            }
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }

    }


    public function uploadImages(){

        $file = request()->file('file');

        if($file){
            $file_result = $file->getInfo();
            $info = $file->move(ROOT_PATH . 'public' . DS . 'static'.DS.'uploads'.DS.'images');
            $image_name = $file_result['name'];
            //获取后缀名
            $image_name = substr($image_name,strpos($image_name,'.')+1);
            $temp_name = $file_result['tmp_name'];
            $temp_name = str_replace('tmp',$image_name,$temp_name);
            $url = config('soaupload')['ip'].':'.config('soaupload')['port'];
            $result = help::curlImages($info->getPathname(), $url."/index/uploadImages");
            if($result){
                $result = json_decode($result,true);
                $result['code'] = 0;
                $result['msg'] = "ok";
                $result['file'] = $result['data'];
//                $result['size'] = $file['size'];
                return json_encode($result);

            }else{
                $d['data'] = '/static/uploads/images/'.$info->getSaveName();
                $d['code'] = 1;
                $d['msg'] = "false";
                $d['file'] = $d['data'];
                echo json_encode($d);
            }
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }

    }

}