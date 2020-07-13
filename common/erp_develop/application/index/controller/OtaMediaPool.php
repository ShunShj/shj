<?php

namespace app\index\controller;

use app\common\help\Help;
use think\Request;

/***
 *媒体库
 **/
class OtaMediaPool extends Base
{
    public function showMediaPoolManage(){

        $post = Request::instance()->param();

        $post['page'] = $this->page();
        $post['size'] = $this->_page_size;
        $post['choose_company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_system/getOtaMediaPoolList',$post);

        $this->getPageParams($result);

        return $this->fetch('media_pool_manage');
    }

    public function getMediaPoolListAjax(){
        $post['choose_company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_system/getOtaMediaPoolList',$post);
        return $result;
    }

    public function showMediaPoolAdd(){

        return $this->fetch('media_pool_add');
    }

    public function addMediaPoolAjax(){

        $ota_slide_list_name = input("ota_slide_list_name");
        $style = input("style");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $width = input("width");
        $height = input("height");
        $website_uuid = session('website_uuid');
        $data = [
            "ota_slide_list_name"=>$ota_slide_list_name,
            "style"=>$style,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "height"=>$height,
            'width'=>$width,
            'website_uuid'=>$website_uuid
        ];

        return $this->callSoaErp('post','/ota_media_pool/addOtaMediaPool',$data);
    }

    public function deleteMediaPool(){
        $data['ota_media_pool_id'] = input("ota_media_pool_id");
        $this->callSoaErp('post','/ota_system/deleteOtaMediaPool',$data);
        Header("Location: /ota_media_pool/showMediaPoolManage");
        exit;
    }

}