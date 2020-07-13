<?php
/**
 * Created by PhpStorm.
 * User: Hugh
 * Date: 2019/11/04
 * Time: 13:40
 */

namespace app\index\controller;
use \Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;
use app\common\help\Help;

class BookProduct extends Base
{
    public function showProductsManage(){
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $product_name = input("product_name");
        $product_code = input("product_code");
        $status = input("status");

        $data['product_name'] = $product_name;
        $data['product_code'] = $product_code;
        $data['status'] = $status;
        $data['company_id'] =  session('user')['company_id'];

        $result = $this->callSoaErp('post', '/btob/getProduct',$data);

        $this->getPageParams($result);

        return $this->fetch('product_manage');
    }

    public function showProductsAdd(){
        return $this->fetch('product_add');
    }

    public function addProductsAjax(){
        $product_name = input("product_name");
        $product_code = input("product_code");
        $sort = input("sort");
        $status = input("status");
        $user_id = session('user')['user_id'];
        $company_id = input("choose_company_id") ? : session('user')['company_id'];
        $data = [
            "product_name"=>$product_name,
            "product_code"=>$product_code,
            "sort"=>$sort,
            "status"=>$status,
            "user_id"=>$user_id,
            "choose_company_id"=>$company_id,
        ];

        $result = $this->callSoaErp('post', '/btob/addProduct',$data);
        return   $result;
    }

    public function showProductsEdit(){

        $product_id = input("product_id");

        $data = ["product_id"=>$product_id];
        $result = $this->callSoaErp('post', '/btob/getOneProduct',$data);
        $this->assign('result',$result['data']);


        return $this->fetch('product_edit');
    }


    public function editProductsAjax(){

        $product_id = input("product_id");
        $product_name = input("product_name");
        $product_code = input("product_code");
        $sort = input("sort");
        $status = input("status");
        $user_id = session('user')['user_id'];
        $company_id = input("choose_company_id") ? : session('user')['company_id'];
        $data = [
            "product_id" => $product_id,
            "product_name" => $product_name,
            "product_code" => $product_code,
            "sort" => $sort,
            "status"=> $status,
            "user_id" => $user_id,
            "choose_company_id" => $company_id,
        ];

        $result = $this->callSoaErp('post', '/btob/updateProductByProductId',$data);
        return   $result;
    }

}