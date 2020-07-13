<?php

namespace app\index\controller;

use app\common\help\Help;
use app\index\model\product\TeamProduct;
use app\index\model\product\TeamProductGuideReceiptFile;
use think\Request;
use think\config;
use think\Session;

class Upload extends Base
{



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


    /**
     * 导游回执单-文件上传案例-执行
     * 韩
     */
    public function upload_guide_receipt_file(){


        $file = request()->file('file');
        $team_product_number = input("team_product_number");
        $team_product_id = input("team_product_id");

        if($file){
            $file_result = $file->getInfo();

            $info = $file->move(ROOT_PATH . 'public' . DS . 'static'.DS.'uploads'.DS.'file'.DS."team_product_tour_guide_jounery");

            $image_name = $file_result['name'];
            //获取后缀名
            $image_name = substr($image_name,strpos($image_name,'.')+1);

            $temp_name = $file_result['tmp_name'];
            $temp_name = str_replace('tmp',$image_name,$temp_name);
            $url = config('soaupload')['ip'].':'.config('soaupload')['port'];

            $result = help::curlImages($info->getPathname(), $url."/index/uploadImages2");

            $file_name = json_decode($result,true)['data'];

            //把数据存到数据库中
            $files_data=[
                "team_product_id"=>$team_product_id,
                "team_product_number"=>$team_product_number,
                "file_name"=>$file_name,
                "user_id"=>session('user')['user_id']
            ];
           
            $files_result = $this->callSoaErp('post','/product/addTeamProductGuideReceiptFile',$files_data);

            if($result){
                $result = json_decode($result,true);
                $result['get'] = $_GET;
                return json_encode($result);

            }else{
                $d['data'] = '/static/uploads/file/team_product_tour_guide_jounery/'.$info->getSaveName();
                $d['code'] = 200;
                $d['get'] = $_GET;
                echo json_encode($d);
            }
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }

    }

    /**
     * 获取导游回执单-上传文件
     * 韩
     */
    public function get_guide_receipt_file(){
        $team_product_number = input("team_product_number");
        $data=[
            "team_product_number"=>$team_product_number
        ];
     
        $file_name = $this->callSoaErp('post','/product/getTeamProductTourGuideJounery',$data);

        return $file_name['data'][0]['file_name'];
    }


    /**
     * 游客回执单-文件上传案例-执行
     * 韩
     */
    public function upload_company_order_customer_guide_receipt_file(){


        $file = request()->file('file');
        $company_order_number = input("company_order_number");
        $company_order_id = input("company_order_id");

        if($file){
            $file_result = $file->getInfo();

            $info = $file->move(ROOT_PATH . 'public' . DS . 'static'.DS.'uploads'.DS.'file'.DS."team_product_tour_guide_jounery");

            $image_name = $file_result['name'];
            //获取后缀名
            $image_name = substr($image_name,strpos($image_name,'.')+1);

            $temp_name = $file_result['tmp_name'];
            $temp_name = str_replace('tmp',$image_name,$temp_name);
            $url = config('soaupload')['ip'].':'.config('soaupload')['port'];

            $result = help::curlImages($info->getPathname(), $url."/index/uploadImages3");

            $file_name = json_decode($result,true)['data'];

            //把数据存到数据库中
            $files_data=[
                "company_order_id"=>$company_order_id,
                "company_order_number"=>$company_order_number,
                "file_name"=>$file_name,
                "user_id"=>session('user')['user_id']
            ];

//            error_log(print_r($files_data,1));
            $files_result = $this->callSoaErp('post','/branchcompany/addCompanyOrderCustomerGuideReceiptFile',$files_data);


            if($result){
                $result = json_decode($result,true);
                $result['get'] = $_GET;
                return json_encode($result);

            }else{
                $d['data'] = '/static/uploads/file/company_order_customer_tour_guide_jounery/'.$info->getSaveName();
                $d['code'] = 200;
                $d['get'] = $_GET;
                echo json_encode($d);
            }
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }

    }

    /**
     * 获取游客回执单-上传文件
     * 韩
     */
    public function get_company_order_customer_guide_receipt_file(){
        $company_order_number = input("company_order_number");
        $data=[
            "company_order_number"=>$company_order_number
        ];
        $file_name = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomerGuideReceiptFile',$data);

        return $file_name['data'][0]['file_name'];
    }

}