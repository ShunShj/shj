<?php

namespace app\index\controller;

use app\common\help\Help;
use \Underscore\Types\Arrays;
use think\Request;

/***
 * 产品
 *
 **/
class OtaMember extends Base
{

    //erp账户列表
    public function lst(){

        $post = Request::instance()->param();

        $post['is_like'] = 1;
        $post['page'] = $this->page();
        $post['size'] = $this->_page_size;
        $post['status'] = 1;
        $post['company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_member/getOtaMemberList',$post);

        $this->getPageParams($result);

        return $this->fetch('member_manage');
    }

    //erp添加账户
    public function add(){
        return $this->fetch('member_add');
    }


    //erp执行添加账户
    public function addAjax(){
        $post = Request::instance()->param();
        $d['company_id'] = session('user')['company_id'];
        $d['username'] = Arrays::get($post,'username');
        $d['password'] = Arrays::get($post,'password');
        $d['nickname'] = Arrays::get($post,'nickname');
        $d['gender'] = Arrays::get($post,'gender');
        $d['email'] = Arrays::get($post,'email');
        $d['website_uuid'] = session('website_uuid');

        return $this->callSoaErp('post','/ota_member/addOtaMember',$d);

    }



    //erp修改账户
    public function edit(){
        $where['company_id'] = session('user')['company_id'];

        $where['uuid'] = input('get.uuid');
        $result = $this->callSoaErp('post','/ota_member/getMemberInfo',$where);

        $this->assign([
            'member_info' => $result['data'],
        ]);

        return $this->fetch('member_edit');
    }


    //erp执行修改账户
    public function editAjax(){
        $post = Request::instance()->param();
        $d['uuid'] = Arrays::get($post,'uuid');
        $d['company_id'] = session('user')['company_id'];
        $d['username'] = Arrays::get($post,'username');
        $d['password'] = Arrays::get($post,'password');
        $d['nickname'] = Arrays::get($post,'nickname');
        $d['gender'] = Arrays::get($post,'gender');
        $d['email'] = Arrays::get($post,'email');
        $d['website_uuid'] = session('website_uuid');
        return $this->callSoaErp('post','/ota_member/editOtaMember',$d);

    }

    //erp执行删除账户
    public function delAjax(){
        $post = Request::instance()->param();
        $d['uuid'] = Arrays::get($post,'uuid');
        $d['company_id'] = session('user')['company_id'];
        $d['status'] = 0;
        $d['website_uuid'] = session('website_uuid');
        return $this->callSoaErp('post','/ota_member/delOtaMember',$d);
    }


}