<?php
/**
 * Created by PhpStorm.
 * User: jiye
 * Date: 2018/8/13
 * Time: 9:24
 */
namespace app\index\controller;

use app\common\help\Help;
use think\helper\Arr;
use \Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;

/***
 * 需求定制
 * Hugh
 **/
class Enquirty extends Base
{
    //需求定制列表
    public function index(){

        $param = Request::instance()->param();

        //获取负责人
        $w['department_id'] = session('user')['department_id'];
        $w['status'] = 1;
        $UserList = $this->callSoaErp('post','/user/getUser',$w);
        $this->assign('UserList',$UserList['data']);
        unset($w);

        $w = [];
        $w['travel_destinations'] = Arrays::get($param,'travel_destinations');
        $w['s_create_time'] = Arrays::get($param,'s_create_time');
        $w['e_create_time'] = Arrays::get($param,'e_create_time');
        $w['s_departure_date'] = Arrays::get($param,'s_departure_date');
        $w['e_departure_date'] = Arrays::get($param,'e_departure_date');
        $w['contect_name'] = Arrays::get($param,'contect_name');
        $w['tel'] = Arrays::get($param,'tel');
        if(session('user')['role_id']==6){ //Manager
            $w['is_manager'] = true;
        }

        $w['company_id'] = session('user')['company_id'];
        $w['website_uuid'] = session('website_uuid');
        $w['user_id'] = session('user')['user_id'];
        $w['page'] = $this->page();
        $w['size'] = $this->_page_size;
        $w['is_page'] = true;
        $Enquirty = $this->callSoaErp('post','/enquirty/selEnquirty',$w);
        $this->getPageParams($Enquirty);

        return $this->fetch('enquirty_list');
    }

    //添加需求定制
    public function addEnquirty(){
        //获取系统语言
        $w['status'] = 1;
        $Language = $this->callSoaErp('post','/system/getLanguage',$w);
        $this->assign('language',$Language['data']);
        return $this->fetch('enquirty_add');
    }

    //异步添加需求定制
    public function addEnquirtyAjax(){
        $param = Request::instance()->param();
//        error_log(print_r(session('user'),1));

        $d['company_id'] = session('user')['company_id'];
        $d['travel_destinations'] = Arrays::get($param,'travel_destinations');
        $d['departure_date'] = Arrays::get($param,'departure_date');
        $d['estimated_number_adults'] = Arrays::get($param,'estimated_number_adults')?:0;
        $d['estimated_number_children'] = Arrays::get($param,'estimated_number_children')?:0;
        $d['estimated_number_the_elderly'] = Arrays::get($param,'estimated_number_the_elderly')?:0;
        $d['estimated_number_bed_free_children'] = Arrays::get($param,'estimated_number_bed_free_children')?:0;
        $d['travel_days'] = Arrays::get($param,'travel_days');
        $d['accommodation_standard'] = Arrays::get($param,'accommodation_standard');
        $d['contect_name'] = Arrays::get($param,'contect_name');
        $d['tel'] = Arrays::get($param,'tel');
        $d['email'] = Arrays::get($param,'email');
        $d['language_id'] = Arrays::get($param,'language_id');
        $d['remark'] = Arrays::get($param,'remark');
        $d['user_id'] = session('user')['user_id'];
        $d['website_uuid'] = session('website_uuid');
        return $this->callSoaErp('post','/enquirty/addEnquirty',$d);

    }

    //编辑需求定制
    public function editEnquirty(){
        $enquiry_id = input('get.enquiry_id');

        $w['user_id'] = session('user')['user_id'];
        if(session('user')['role_id']==6){ //Manager
           $w['is_manager'] = true;
        }

        $w['enquiry_id'] = $enquiry_id;
        $Enquirty = $this->callSoaErp('post','/enquirty/selEnquirty',$w);
        $this->assign('Enquirty',$Enquirty['data'][0]);

        //获取系统语言
        $w['status'] = 1;
        $Language = $this->callSoaErp('post','/system/getLanguage',$w);
        $this->assign('language',$Language['data']);
        return $this->fetch('enquirty_edit');

    }

    //异步修改需求定制
    public function editEnquirtyAjax(){
        $param = Request::instance()->param();

        $d['enquiry_id'] = Arrays::get($param,'enquiry_id');
        $d['travel_destinations'] = Arrays::get($param,'travel_destinations');
        $d['departure_date'] = Arrays::get($param,'departure_date');
        $d['estimated_number_adults'] = Arrays::get($param,'estimated_number_adults')?:0;
        $d['estimated_number_children'] = Arrays::get($param,'estimated_number_children')?:0;
        $d['estimated_number_the_elderly'] = Arrays::get($param,'estimated_number_the_elderly')?:0;
        $d['estimated_number_bed_free_children'] = Arrays::get($param,'estimated_number_bed_free_children')?:0;
        $d['travel_days'] = Arrays::get($param,'travel_days');
        $d['accommodation_standard'] = Arrays::get($param,'accommodation_standard');
        $d['contect_name'] = Arrays::get($param,'contect_name');
        $d['tel'] = Arrays::get($param,'tel');
        $d['email'] = Arrays::get($param,'email');
        $d['language_id'] = Arrays::get($param,'language_id');
        $d['remark'] = Arrays::get($param,'remark');
        $d['user_id'] = session('user')['user_id'];

        return $this->callSoaErp('post','/enquirty/editEnquirty',$d);
    }

    //修改需求定制负责人
    public function editEnquirtyPersonInCharge(){
        $u['person_in_charge'] = input('post.person_in_charge');
        $u['enquiry_id'] = input('post.enquiry_id');

        return $this->callSoaErp('post','/enquirty/editEnquirtyPersonInCharge',$u);

    }


}