<?php

namespace app\index\controller;

use app\common\help\Help;
use think\Request;

/***
 *
 *
 **/
class OtaSlide extends Base
{
    public function showSlideListManage(){

        $post = Request::instance()->param();

        $post['page'] = $this->page();
        $post['size'] = $this->_page_size;
        $post['choose_company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_slide/getOtaSlideListList',$post);

        $this->getPageParams($result);

        return $this->fetch('slide_list_manage');
    }

    public function showSlideListAdd(){

        return $this->fetch('slide_list_add');
    }

    public function addSlideListAjax(){

        $ota_slide_list_name = input("ota_slide_list_name");
        $style = input("style");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $width = input("width");
        $height = input("height");
        $website_uuid = session('website_uuid');
        $author = input("author");
        $description = input("description");
        $keywords = input("keywords");
        $data = [
            "ota_slide_list_name"=>$ota_slide_list_name,
            "style"=>$style,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "height"=>$height,
            'width'=>$width,
            'website_uuid'=>$website_uuid,
            "author"=>$author,
            "description"=>$description,
            "keywords"=>$keywords,
        ];

        return $this->callSoaErp('post','/ota_slide/addOtaSlideList',$data);
    }

    public function showSlideListEdit(){

        $where['ota_slide_list_id'] = input('ota_slide_list_id');
        $result = $this->callSoaErp('post','/ota_slide/getOneOtaSlideList',$where);
        $this->assign(['slide_list_result' => $result['data']]);

        return $this->fetch('slide_list_edit');
    }

    public function editSlideListAjax(){
        $ota_slide_list_id = input("ota_slide_list_id");
        $ota_slide_list_name = input("ota_slide_list_name");
        $style = input("style");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $width = input("width");
        $height = input("height");
        $author = input("author");
        $description = input("description");
        $keywords = input("keywords");

        $data = [
            "ota_slide_list_id" =>$ota_slide_list_id,
            "ota_slide_list_name"=>$ota_slide_list_name,
            "style"=>$style,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "height"=>$height,
            'width'=>$width,
            "author"=>$author,
            "description"=>$description,
            "keywords"=>$keywords,
        ];

        return $this->callSoaErp('post','/ota_slide/updateOtaSlideList',$data);

    }

    public function showSlideListInfo(){

        $where['ota_slide_list_id'] = input('ota_slide_list_id');
        $result = $this->callSoaErp('post','/ota_slide/getOneOtaSlideList',$where);
        $this->assign(['slide_list_result' => $result['data']]);

        return $this->fetch('slide_list_info');
    }


    public function showSlideManage(){

        $post = Request::instance()->param();

        $post['page'] = $this->page();
        $post['size'] = $this->_page_size;
        $post['choose_company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_slide/getOtaSlideList',$post);

        $this->getPageParams($result);

        return $this->fetch('slide_manage');
    }

    public function showSlideAdd(){

        return $this->fetch('slide_add');
    }

    public function addSlideAjax(){

        $title = input("title");
        $pic = input("pic");
        $video = input("video");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $url = input("url");
        $sort = input("sort");
        $website_uuid = session('website_uuid');
        $ota_slide_list_uuid = input("ota_slide_list_uuid");
        $href_type = input("href_type");
        $without_href = input("without_href");
        $interior_type = input("interior_type");
        $interior_uuid = input("interior_uuid");
        $data = [
            "title"=>$title,
            "pic"=>$pic,
            "video"=>$video,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "sort"=>$sort,
            'url'=>$url,
            'website_uuid'=>$website_uuid,
            'ota_slide_list_uuid'=>$ota_slide_list_uuid,
            'href_type'=>$href_type,
            'without_href'=>$without_href,
            'interior_type'=>$interior_type,
            'interior_uuid'=>$interior_uuid,
        ];

        return $this->callSoaErp('post','/ota_slide/addOtaSlide',$data);
    }

    public function showSlideEdit(){

        $where['ota_slide_id'] = input('ota_slide_id');
        $Slide_result = $this->callSoaErp('post','/ota_slide/getOtaSlide',$where);
        $this->assign(['slide_result' => $Slide_result['data']]);

        $post['choose_company_id'] = session('user')['company_id'];
        $post['status'] = 1;
        if ($Slide_result['data']["interior_type"] == 1){
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_product/getProductTypes',$post);
        }elseif ($Slide_result['data']["interior_type"] == 2){
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_product/getProducts',$post);
        }elseif ($Slide_result['data']["interior_type"] == 3) {
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_article/getArticleTypeList',$post);
        }elseif ($Slide_result['data']["interior_type"] == 4){
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_article/getArticleList',$post);
        }


        $this->assign(['data' => $result1['data']]);


        return $this->fetch('slide_edit');
    }

    public function editSlideAjax(){
        $ota_slide_id = input("ota_slide_id");
        $title = input("title");
        $pic = input("pic");
        $video = input("video");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $url = input("url");
        $sort = input("sort");
        $href_type = input("href_type");
        $without_href = input("without_href");
        $interior_type = input("interior_type");
        $interior_uuid = input("interior_uuid");
        $data = [
            "ota_slide_id" => $ota_slide_id,
            "title"=>$title,
            "pic"=>$pic,
            "video"=>$video,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "sort"=>$sort,
            'url'=>$url,
            'href_type'=>$href_type,
            'without_href'=>$without_href,
            'interior_type'=>$interior_type,
            'interior_uuid'=>$interior_uuid,
        ];

        return $this->callSoaErp('post','/ota_slide/updateOtaSlide',$data);

    }

    public function showSlideInfo(){

        $where['ota_slide_id'] = input('ota_slide_id');
        $result = $this->callSoaErp('post','/ota_slide/getOtaSlide',$where);
        $this->assign(['result' => $result['data']]);

        return $this->fetch('slide_info');
    }


    public function showAdvertListManage(){

        $post = Request::instance()->param();

        $post['page'] = $this->page();
        $post['size'] = $this->_page_size;
        $post['choose_company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_slide/getOtaAdvertListList',$post);

        $this->getPageParams($result);

        return $this->fetch('advert_list_manage');
    }

    public function showAdvertListAdd(){
        return $this->fetch('advert_list_add');
    }

    public function addAdvertListAjax(){

        $ota_advert_list_name = input("ota_advert_list_name");
        $style = input("style");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $website_uuid = session('website_uuid');
        $data = [
            "ota_advert_list_name"=>$ota_advert_list_name,
            "style"=>$style,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            'website_uuid'=>$website_uuid
        ];

        return $this->callSoaErp('post','/ota_slide/addOtaAdvertList',$data);
    }

    public function showAdvertListEdit(){
        $where['ota_advert_list_id'] = input('ota_advert_list_id');
        $result = $this->callSoaErp('post','/ota_slide/getOneOtaAdvertList',$where);
        $this->assign(['result' => $result['data']]);
        return $this->fetch('advert_list_edit');
    }

    public function editAdvertListAjax(){
        $ota_advert_list_id = input("ota_advert_list_id");
        $ota_advert_list_name = input("ota_advert_list_name");
        $style = input("style");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $data = [
            "ota_advert_list_name"=>$ota_advert_list_name,
            "style"=>$style,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            'ota_advert_list_id'=>$ota_advert_list_id
        ];

        return $this->callSoaErp('post','/ota_slide/updateOtaAdvertList',$data);

    }

    public function showAdvertListInfo(){

        $where['ota_advert_list_id'] = input('ota_advert_list_id');
        $result = $this->callSoaErp('post','/ota_slide/getOneOtaAdvertList',$where);
        $this->assign(['result' => $result['data']]);

        return $this->fetch('advert_list_info');
    }


    public function showAdvertManage(){

        $post = Request::instance()->param();

        $post['page'] = $this->page();
        $post['size'] = $this->_page_size;
        $post['choose_company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_slide/getOtaAdvertList',$post);

        $this->getPageParams($result);

        return $this->fetch('advert_manage');
    }

    public function showAdvertAdd(){
        return $this->fetch('advert_add');
    }

    public function addAdvertAjax(){

        $title = input("title");
        $pic = input("pic");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $url = input("url");
        $sort = input("sort");
        $description = input("description");
        $ota_advert_list_uuid = input("ota_advert_list_uuid");
        $website_uuid = session('website_uuid');
        $href_type = input("href_type");
        $without_href = input("without_href");
        $interior_type = input("interior_type");
        $interior_uuid = input("interior_uuid");
        $data = [
            "title"=>$title,
            "pic"=>$pic,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "sort"=>$sort,
            'url'=>$url,
            'description'=>$description,
            'ota_advert_list_uuid'=>$ota_advert_list_uuid,
            'website_uuid'=>$website_uuid,
            'href_type'=>$href_type,
            'without_href'=>$without_href,
            'interior_type'=>$interior_type,
            'interior_uuid'=>$interior_uuid,
        ];

        return $this->callSoaErp('post','/ota_slide/addOtaAdvert',$data);
    }

    public function showAdvertEdit(){
        $where['ota_advert_id'] = input('ota_advert_id');
        $result = $this->callSoaErp('post','/ota_slide/getOtaAdvert',$where);
        $this->assign(['result' => $result['data']]);

        $post['choose_company_id'] = session('user')['company_id'];
        $post['status'] = 1;
        if ($result['data']["interior_type"] == 1){
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_product/getProductTypes',$post);
        }elseif ($result['data']["interior_type"] == 2){
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_product/getProducts',$post);
        }elseif ($result['data']["interior_type"] == 3) {
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_article/getArticleTypeList',$post);
        }elseif ($result['data']["interior_type"] == 4){
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_article/getArticleList',$post);
        }

        $this->assign(['data' => $result1['data']]);

        return $this->fetch('advert_edit');
    }

    public function editAdvertAjax(){
        $ota_advert_id = input("ota_advert_id");
        $title = input("title");
        $pic = input("pic");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $url = input("url");
        $sort = input("sort");
        $description = input("description");
        $href_type = input("href_type");
        $without_href = input("without_href");
        $interior_type = input("interior_type");
        $interior_uuid = input("interior_uuid");
        $data = [
            "ota_advert_id" => $ota_advert_id,
            "title"=>$title,
            "pic"=>$pic,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "sort"=>$sort,
            'url'=>$url,
            'description'=>$description,
            'href_type'=>$href_type,
            'without_href'=>$without_href,
            'interior_type'=>$interior_type,
            'interior_uuid'=>$interior_uuid,
        ];

        return $this->callSoaErp('post','/ota_slide/updateOtaAdvert',$data);

    }

    public function showAdvertInfo(){

        $where['ota_advert_id'] = input('ota_advert_id');
        $result = $this->callSoaErp('post','/ota_slide/getOtaAdvert',$where);
        $this->assign(['result' => $result['data']]);

        return $this->fetch('advert_info');
    }

    public function showAdvertisingManage(){
        $post = Request::instance()->param();

        $post['page'] = $this->page();
        $post['size'] = $this->_page_size;
        $post['choose_company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_slide/getOtaAdvertisingList',$post);

        $this->getPageParams($result);

        return $this->fetch('advertising_manage');
    }

    public function showAdvertisingAdd(){
        return $this->fetch('advertising_add');
    }

    public function addAdvertisingAjax(){
        $title = input("title");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $style = input("style");
        $more_title = input("more_title");
        $more_url = input("more_url");
        $website_uuid = session('website_uuid');
        $href_type = input("href_type");
        $without_href = input("without_href");
        $interior_type = input("interior_type");
        $interior_uuid = input("interior_uuid");
        $content = input("content");
        $author = input("author");
        $description = input("description");
        $keywords = input("keywords");
        $data = [
            "title"=>$title,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "more_title"=>$more_title,
            'style'=>$style,
            'more_url'=>$more_url,
            'website_uuid'=>$website_uuid,
            'href_type'=>$href_type,
            'without_href'=>$without_href,
            'interior_type'=>$interior_type,
            'interior_uuid'=>$interior_uuid,
            'content'=>$content,
            "author"=>$author,
            "description"=>$description,
            "keywords"=>$keywords,
        ];

        return $this->callSoaErp('post','/ota_slide/addOtaAdvertising',$data);
    }

    public function showAdvertisingEdit(){

        $where['ota_advertising_id'] = input('ota_advertising_id');
        $result = $this->callSoaErp('post','/ota_slide/getOtaAdvertising',$where);
        $this->assign(['result' => $result['data']]);

        $post['choose_company_id'] = session('user')['company_id'];
        $post['status'] = 1;
        if ($result['data']["interior_type"] == 1){
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_product/getProductTypes',$post);
        }elseif ($result['data']["interior_type"] == 2){
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_product/getProducts',$post);
        }elseif ($result['data']["interior_type"] == 3) {
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_article/getArticleTypeList',$post);
        }elseif ($result['data']["interior_type"] == 4){
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_article/getArticleList',$post);
        }
        $this->assign(['data' => $result1['data']]);

        return $this->fetch('advertising_edit');
    }

    public function editAdvertisingAjax(){
        $ota_advertising_id = input("ota_advertising_id");
        $title = input("title");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $style = input("style");
        $more_title = input("more_title");
        $more_url = input("more_url");
        $href_type = input("href_type");
        $without_href = input("without_href");
        $interior_type = input("interior_type");
        $interior_uuid = input("interior_uuid");
        $content = input("content");
        $author = input("author");
        $description = input("description");
        $keywords = input("keywords");
        $data = [
            "ota_advertising_id"=>$ota_advertising_id,
            "title"=>$title,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "more_title"=>$more_title,
            'style'=>$style,
            'more_url'=>$more_url,
            'href_type'=>$href_type,
            'without_href'=>$without_href,
            'interior_type'=>$interior_type,
            'interior_uuid'=>$interior_uuid,
            'content'=>$content,
            "author"=>$author,
            "description"=>$description,
            "keywords"=>$keywords,
        ];

        return $this->callSoaErp('post','/ota_slide/updateOtaAdvertising',$data);
    }

    public function showAdvertisingInfo(){

        $where['ota_advertising_id'] = input('ota_advertising_id');
        $result = $this->callSoaErp('post','/ota_slide/getOtaAdvertising',$where);
        $this->assign(['result' => $result['data']]);

        return $this->fetch('advertising_info');
    }

    public function showAdvertisingSubtitleManage(){

        $post = Request::instance()->param();

        $post['page'] = $this->page();
        $post['size'] = $this->_page_size;
        $post['choose_company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_slide/getOtaAdvertisingSubtitleList',$post);

        $this->getPageParams($result);

        return $this->fetch('advertising_subtitle_manage');
    }

    public function showAdvertisingSubtitleAdd(){
        return $this->fetch('advertising_subtitle_add');
    }

    public function addAdvertisingSubtitleAjax(){
        $website_uuid = session('website_uuid');
        $ota_advertising_uuid = input("ota_advertising_uuid");
        $subtitle = input("subtitle");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $sort = input("sort");
        $url = input("url");
        $href_type = input("href_type");
        $without_href = input("without_href");
        $interior_type = input("interior_type");
        $interior_uuid = input("interior_uuid");
        $data = [
            "ota_advertising_uuid"=>$ota_advertising_uuid,
            "subtitle"=>$subtitle,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "url"=>$url,
            'sort'=>$sort,
            'website_uuid'=>$website_uuid,
            'href_type'=>$href_type,
            'without_href'=>$without_href,
            'interior_type'=>$interior_type,
            'interior_uuid'=>$interior_uuid,
        ];

        return $this->callSoaErp('post','/ota_slide/addOtaAdvertisingSubtitle',$data);
    }

    public function showAdvertisingSubtitleEdit(){
        $where['ota_advertising_subtitle_id'] = input('ota_advertising_subtitle_id');
        $result = $this->callSoaErp('post','/ota_slide/getOtaAdvertisingSubtitle',$where);
        $this->assign(['result' => $result['data']]);

        $post['choose_company_id'] = session('user')['company_id'];
        $post['status'] = 1;
        if ($result['data']["interior_type"] == 1){
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_product/getProductTypes',$post);
        }elseif ($result['data']["interior_type"] == 2){
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_product/getProducts',$post);
        }elseif ($result['data']["interior_type"] == 3) {
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_article/getArticleTypeList',$post);
        }elseif ($result['data']["interior_type"] == 4){
            $post['website_uuid'] = session('website_uuid');
            $result1 = $this->callSoaErp('post','/ota_article/getArticleList',$post);
        }
        $this->assign(['data' => $result1['data']]);
        return $this->fetch('advertising_subtitle_edit');
    }

    public function editAdvertisingSubtitleAjax(){

        $ota_advertising_subtitle_id = input("ota_advertising_subtitle_id");
        $subtitle = input("subtitle");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $sort = input("sort");
        $url = input("url");
        $href_type = input("href_type");
        $without_href = input("without_href");
        $interior_type = input("interior_type");
        $interior_uuid = input("interior_uuid");
        $data = [
            "ota_advertising_subtitle_id"=>$ota_advertising_subtitle_id,
            "subtitle"=>$subtitle,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "url"=>$url,
            'sort'=>$sort,
            'href_type'=>$href_type,
            'without_href'=>$without_href,
            'interior_type'=>$interior_type,
            'interior_uuid'=>$interior_uuid,
        ];

        return $this->callSoaErp('post','/ota_slide/updateOtaAdvertisingSubtitle',$data);
    }

    public function showAdvertisingProductManage(){

        $post = Request::instance()->param();

        $post['page'] = $this->page();
        $post['size'] = $this->_page_size;
        $post['choose_company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_slide/getOtaAdvertisingProductList',$post);

        $this->getPageParams($result);

        return $this->fetch('advertising_product_manage');
    }

    public function showAdvertisingProductAdd(){
        $post['choose_company_id'] = session('user')['company_id'];
        $post['status'] = 1;
        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_product/getProducts',$post);
        $this->assign('pro_result',$result['data']);
        $currency_data = ["status"=>1];
        $result_data_currency = $this->callSoaErp('post', '/system/getCurrency', $currency_data);
        $this->assign('data_currency',$result_data_currency['data']);
        return $this->fetch('advertising_product_add');
    }

    public function addAdvertisingProductAjax(){
        $href_type = input("href_type");
        $without_href = input("without_href");
        $interior_type = input("interior_type");
        $interior_uuid = input("interior_uuid");
        $website_uuid = session('website_uuid');
        $ota_advertising_uuid = input("ota_advertising_uuid");
        $product_title = input("product_title");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $currency_id = input("currency_id");
        $price = input("price");
        $tag_name = input("tag_name");
        $team_product_id = input("team_product_id");
        $sort = input("sort");
        $banner_image = input("banner_image");
        $data = [
            "ota_advertising_uuid"=>$ota_advertising_uuid,
            "product_title"=>$product_title,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "banner_image"=>$banner_image,
            'sort'=>$sort,
            'website_uuid'=>$website_uuid,
            'currency_id'=>$currency_id,
            'price'=>$price,
            'tag_name'=>$tag_name,
            'team_product_id'=>$team_product_id,
            'href_type'=>$href_type,
            'without_href'=>$without_href,
            'interior_type'=>$interior_type,
            'interior_uuid'=>$interior_uuid,
        ];

        return $this->callSoaErp('post','/ota_slide/addOtaAdvertisingProduct',$data);
    }

    public function showAdvertisingProductEdit(){

        $post['choose_company_id'] = session('user')['company_id'];
        $post['status'] = 1;
        $post['website_uuid'] = session('website_uuid');
        $result1 = $this->callSoaErp('post','/ota_product/getProducts',$post);
        $this->assign('pro_result',$result1['data']);

        $currency_data = ["status"=>1];
        $result_data_currency = $this->callSoaErp('post', '/system/getCurrency', $currency_data);
        $this->assign('data_currency',$result_data_currency['data']);

        $where['ota_advertising_product_id'] = input('ota_advertising_product_id');
        $result = $this->callSoaErp('post','/ota_slide/getOtaAdvertisingProduct',$where);
        $this->assign(['result' => $result['data']]);

        if ($result['data']["interior_type"] == 1){
            $result1 = $this->callSoaErp('post','/ota_product/getProductTypes',$post);
        }elseif ($result['data']["interior_type"] == 2){
            $result1 = $this->callSoaErp('post','/ota_product/getProducts',$post);
        }elseif ($result['data']["interior_type"] == 3) {
            $result1 = $this->callSoaErp('post','/ota_article/getArticleTypeList',$post);
        }elseif ($result['data']["interior_type"] == 4){
            $result1 = $this->callSoaErp('post','/ota_article/getArticleList',$post);
        }
        $this->assign(['data' => $result1['data']]);


        return $this->fetch('advertising_product_edit');
    }

    public function editAdvertisingProductAjax(){

        $ota_advertising_product_id = input("ota_advertising_product_id");
        $product_title = input("product_title");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $currency_id = input("currency_id");
        $price = input("price");
        $tag_name = input("tag_name");
        $team_product_id = input("team_product_id");
        $sort = input("sort");
        $banner_image = input("banner_image");
        $href_type = input("href_type");
        $without_href = input("without_href");
        $interior_type = input("interior_type");
        $interior_uuid = input("interior_uuid");
        $data = [
            "ota_advertising_product_id"=>$ota_advertising_product_id,
            "product_title"=>$product_title,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "banner_image"=>$banner_image,
            'sort'=>$sort,
            'currency_id'=>$currency_id,
            'price'=>$price,
            'tag_name'=>$tag_name,
            'team_product_id'=>$team_product_id,
            'href_type'=>$href_type,
            'without_href'=>$without_href,
            'interior_type'=>$interior_type,
            'interior_uuid'=>$interior_uuid,
        ];

        return $this->callSoaErp('post','/ota_slide/updateOtaAdvertisingProduct',$data);
    }
}