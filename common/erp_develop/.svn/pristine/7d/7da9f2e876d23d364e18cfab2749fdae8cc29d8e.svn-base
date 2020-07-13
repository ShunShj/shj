<?php
namespace app\index\controller;

use app\common\help\Help;
use think\Request;
use think\Session;
use Underscore\Types\Arrays;

/***
 * 网站设置
 *
 **/
class OtaSystem extends Base
{

    public function showOtaWebsiteManage(){

        //网站列表
        $post = Request::instance()->param();
        $post['choose_company_id'] = session('user')['company_id'];
        $result = $this->callSoaErp('post','/ota_system/getOtaWebsiteList',$post);
        $this->assign('web_list',$result['data']);
        return $this->fetch('ota_website_manage');
    }


    public function showOtaWebsiteEdit(){
        //获取时区
        $time_zone_result = $this->callSoaErp('post', '/system/getTimeZone');

        //获取货币
        $currency_data = ['status'=>1];
        $currency_data_result = $this->callSoaErp('post', '/system/getCurrency',$currency_data);

        //获取语言
        $language_data_result = $this->callSoaErp('post', '/system/getLanguage',$currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);
        $this->assign('language_data_result',$language_data_result['data']);
        $this->assign('time_zone_result',$time_zone_result['data']);


        if(input('ota_website_id')){
            $where['ota_website_id'] = input('ota_website_id');
            $result = $this->callSoaErp('post','/ota_system/getOtaWebsite',$where);
            $this->assign(['result' => $result['data']]);
        }

        return $this->fetch('ota_website_edit');
    }

    public function setOtaWebsite(){
        Session::set('website_uuid',input('website_uuid'));
        Header("Location: /ota_system/showOtaWebsiteManage?status=1");
        exit;
    }

    public function removeOtaWebsite(){
        Session::delete('website_uuid');
        Header("Location: /ota_system/showOtaWebsiteManage?status=1");
        exit;
    }

    public function editOtaWebsiteAjax(){
        $ota_website_id = input("ota_website_id");
        $logo = input("logo");
        $web_name = input("web_name");
        $web_href = input("web_href");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $time_zone_id = input("time_zone_id");
        $language_id = input("language_id");
        $currency_id = input("currency_id");
        $put_on_records = input("put_on_records");
        $tourism_business_license_number = input("tourism_business_license_number");
        $company_address = input("company_address");
        $reservation_phone = input("reservation_phone");
        $email = input("email");
        $scope_of_business = input("scope_of_business");
        $about_us = input("about_us");
        $contact_us = input("contact_us");
        $author = input("author");
        $description = input("description");
        $keywords = input("keywords");
        $data = [
            "ota_website_id" => $ota_website_id,
            "logo"=>$logo,
            "web_name"=>$web_name,
            "web_href"=>$web_href,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "time_zone_id"=>$time_zone_id,
            'language_id'=>$language_id,
            'currency_id'=>$currency_id,
            'put_on_records'=>$put_on_records,
            'tourism_business_license_number'=>$tourism_business_license_number,
            "reservation_phone"=>$reservation_phone,
            'email'=>$email,
            'scope_of_business'=>$scope_of_business,
            'company_address'=>$company_address,
            "about_us"=>$about_us,
            'contact_us'=>$contact_us,
            "author"=>$author,
            "description"=>$description,
            "keywords"=>$keywords,
        ];

        if($ota_website_id){
            return $this->callSoaErp('post','/ota_system/updateOtaWebsite',$data);
        }else{
            return $this->callSoaErp('post','/ota_system/addOtaWebsite',$data);
        }


    }

    public function showCompanyWebsiteManage(){
        $post = Request::instance()->param();

        $post['page'] = $this->page();
        $post['size'] = $this->_page_size;
        $post['choose_company_id'] = session('user')['company_id'];

        $result = $this->callSoaErp('post','/ota_system/getOtaCompanyWebsiteList',$post);

        $this->getPageParams($result);

        return $this->fetch('company_website_manage');
    }

    public function showCompanyWebsiteAdd(){
        $data = ['status'=>1];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        $this->assign('company_result',$company_result['data']);

        return $this->fetch('company_website_add');
    }

    public function addCompanyWebsiteAjax(){
        $website = input("website");
        $describe = input("describe");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];

        $data = [
            "website"=>$website,
            "describe"=>$describe,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
        ];

        return $this->callSoaErp('post','/ota_system/addOtaCompanyWebsite',$data);
    }

    public function showCompanyWebsiteEdit(){

        $data = ['status'=>1];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        $this->assign('company_result',$company_result['data']);

        $where['ota_company_website_id'] = input('ota_company_website_id');
        $result = $this->callSoaErp('post','/ota_system/getOtaCompanyWebsite',$where);
        $this->assign(['result' => $result['data']]);

        return $this->fetch('company_website_edit');
    }

    public function editCompanyWebsiteAjax(){
        $ota_company_website_id = input("ota_company_website_id");
        $website = input("website");
        $describe = input("describe");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];

        $data = [
            "ota_company_website_id"=>$ota_company_website_id,
            "website"=>$website,
            "describe"=>$describe,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
        ];

        return $this->callSoaErp('post','/ota_system/updateOtaCompanyWebsite',$data);
    }

    public function showCompanyWebsiteInfo(){
        $where['ota_company_website_id'] = input('ota_company_website_id');
        $result = $this->callSoaErp('post','/ota_system/getOtaCompanyWebsite',$where);
        $this->assign(['result' => $result['data']]);

        return $this->fetch('company_website_info');
    }

    public function showOtaMenuManage(){
        $post = Request::instance()->param();

        $post['page'] = $this->page();
        $post['size'] = $this->_page_size;
        $post['choose_company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_menu/getOtaMenuList',$post);

        $this->getPageParams($result);

        return $this->fetch('ota_menu_manage');
    }

    public function showOtaMenuAdd(){

        return $this->fetch('ota_menu_add');
    }

    public function addOtaMenuAjax(){
        $menu_name = input("menu_name");
        $style = input("style");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $website_uuid = session('website_uuid');
        $pid = input("pid");
        $sorting = input("sorting");
        $href_type = input("href_type");
        $without_href = input("without_href");
        $interior_type = input("interior_type");
        $interior_uuid = input("interior_uuid");
        $ota_menu_list_uuid = input("ota_menu_list_uuid");
        $data = [
            'website_uuid'=>$website_uuid,
            "menu_name"=>$menu_name,
            "style"=>$style,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "pid"=>$pid,
            'sorting'=>$sorting,
            'href_type'=>$href_type,
            'without_href'=>$without_href,
            'interior_type'=>$interior_type,
            'interior_uuid'=>$interior_uuid,
            'ota_menu_list_uuid'=>$ota_menu_list_uuid,
        ];

        return $this->callSoaErp('post','/ota_menu/addOtaMenu',$data);
    }

    public function showOtaMenuEdit(){

        $where['ota_menu_id'] = input('ota_menu_id');
        $result = $this->callSoaErp('post','/ota_menu/getOtaMenu',$where);
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


        return $this->fetch('ota_menu_edit');
    }

    public function editOtaMenuAjax(){
        $ota_menu_id = input("ota_menu_id");
        $menu_name = input("menu_name");
        $style = input("style");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $pid = input("pid");
        $sorting = input("sorting");
        $href_type = input("href_type");
        $without_href = input("without_href");
        $interior_type = input("interior_type");
        $interior_uuid = input("interior_uuid");
        $data = [
            "ota_menu_id"=>$ota_menu_id,
            "menu_name"=>$menu_name,
            "style"=>$style,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "pid"=>$pid,
            'sorting'=>$sorting,
            'href_type'=>$href_type,
            'without_href'=>$without_href,
            'interior_type'=>$interior_type,
            'interior_uuid'=>$interior_uuid,
        ];

        return $this->callSoaErp('post','/ota_menu/updateOtaMenu',$data);
    }

    public function showOtaMenuListManage(){

        $post = Request::instance()->param();

        $post['page'] = $this->page();
        $post['size'] = $this->_page_size;
        $post['choose_company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_menu/getOtaMenuListList',$post);

        $this->getPageParams($result);

        return $this->fetch('ota_menu_list_manage');

    }

    public function showOtaMenuListAdd(){
        return $this->fetch('ota_menu_list_add');
    }

    public function addOtaMenuListAjax(){
        $title = input("title");
        $style = input("style");
        $type = input("type");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $website_uuid = session('website_uuid');
        $author = input("author");
        $description = input("description");
        $keywords = input("keywords");
        $data = [
            'website_uuid'=>$website_uuid,
            "title"=>$title,
            "style"=>$style,
            "type"=>$type,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "author"=>$author,
            "description"=>$description,
            "keywords"=>$keywords,
        ];

        return $this->callSoaErp('post','/ota_menu/addOtaMenuList',$data);
    }

    public function showOtaMenuListEdit(){

        $where['ota_menu_list_id'] = input('ota_menu_list_id');
        $result = $this->callSoaErp('post','/ota_menu/getOneOtaMenuList',$where);
        $this->assign(['result' => $result['data']]);

        return $this->fetch('ota_menu_list_edit');
    }

    public function editOtaMenuListAjax(){
        $ota_menu_list_id = input("ota_menu_list_id");
        $title = input("title");
        $style = input("style");
        $type = input("type");
        $choose_company_id = session('user')['company_id'];
        $status = input("status");
        $user_id = session('user')['user_id'];
        $author = input("author");
        $description = input("description");
        $keywords = input("keywords");
        $data = [
            "ota_menu_list_id"=>$ota_menu_list_id,
            "title"=>$title,
            "style"=>$style,
            "type"=>$type,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
            "author"=>$author,
            "description"=>$description,
            "keywords"=>$keywords,
        ];
        return $this->callSoaErp('post','/ota_menu/updateOtaMenuList',$data);
    }

    public function getOtaMenuInteriorUuidAjax(){

        $post['choose_company_id'] = session('user')['company_id'];
        $post['status'] = 1;
        if (input("interior_type") == 1){
            $post['website_uuid'] = session('website_uuid');
            $result = $this->callSoaErp('post','/ota_product/getProductTypes',$post);
        }elseif (input("interior_type") == 2){
            $post['website_uuid'] = session('website_uuid');
            $result = $this->callSoaErp('post','/ota_product/getProducts',$post);
        }elseif (input("interior_type") == 3) {
            $post['website_uuid'] = session('website_uuid');
            $result = $this->callSoaErp('post','/ota_article/getArticleTypeList',$post);
        }elseif (input("interior_type") == 4){
            $post['website_uuid'] = session('website_uuid');
            $result = $this->callSoaErp('post','/ota_article/getArticleList',$post);
        }

        return $result;
    }


    public function showOtaWebsitePayManage(){
        //网站列表
        $post = Request::instance()->param();
        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_system/getOtaWebsitePayList',$post);

        $result['data'] = Arrays::group($result['data'], 'pay_type');

        $this->assign('pay_list',$result['data']);
        return $this->fetch('ota_website_pay_manage');
    }

    public function showPaypalAdd()
    {
        $post = Request::instance()->param();

        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_system/getOtaWebsitePayList',$post);

        if ($result['code'] == 200 && $result['data']['0'])
        {
            $result['data']['0']['pay_desc'] = json_decode($result['data']['0']['pay_desc'], true);
            $this->assign('paypal_info',$result['data']['0']);
        }

        return $this->fetch('paypal_add');
    }

    public function otaWebsitePayAjax()
    {
        $post = Request::instance()->param();
        $post['website_uuid'] = session('website_uuid');

        $result = $this->callSoaErp('post', '/ota_system/openOtaWebsitePay', $post);

        return $result;
    }

    public function showOtaSubscribeManage()
    {
        //订阅列表
        $post = Request::instance()->param();
        $post['page'] = $this->page();
        $post['size'] = $this->_page_size;
        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_system/getSubscribe',$post);

        $this->getPageParams($result);
        return $this->fetch('ota_subscribe_manage');
    }
}