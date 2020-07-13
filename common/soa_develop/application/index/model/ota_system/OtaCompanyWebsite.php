<?php

namespace app\index\model\ota_system;
use app\common\help\Help;
use think\Model;

use think\config;
use think\Db;
class OtaCompanyWebsite extends Model{
    protected $table = 'ota_company_website';

    public function initialize()
    {
        parent::initialize();
    }

    public function addOtaCompanyWebsite($params){
        $t = time();

        if($params['describe']){
            $data['describe'] = $params['describe'];
        }
        if($params['website']){
            $data['website'] = $params['website'];
        }
        $data['company_id'] = $params['choose_company_id'];
        $data['status'] = $params['status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        $data['create_user_id'] = $params['user_id'];
        $data['create_time'] = $t;
        $data['uuid'] = rand();

        Db::startTrans();
        try{
            Db::name('ota_company_website')->insertGetId($data);

            $result = 1;
            // 提交事务
            Db::commit();

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            Db::rollback();
            //\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            //exit();

        }

        return $result;
    }

    public function getOneOtaCompanyWebsite($params){
        $data = "1=1 ";
        if($params['ota_company_website_id']){
            $data.= " and ota_company_website.ota_company_website_id= ".$params['ota_company_website_id'];
        }
        $result = $this->table("ota_company_website")
            ->join('company','company.company_id = ota_company_website.company_id', 'left')
            ->where($data)
            ->field(['ota_company_website.*','company.company_name'])
            ->find();
        return $result;
    }

    public function getOtaCompanyWebsiteList($params,$is_count=false,$is_page=false,$page=null,$page_size=20){

        $data = "1=1 ";
        if($params['status'] < 2){
            $data.= " and ota_company_website.status= ".$params['status'];
        }
        if(!empty($params['website'])){
            $data.= " and ota_company_website.website like'%".$params['website']."%'";
        }
        if(isset($params['choose_company_id'])){
            $data.= " and ota_company_website.company_id = ".$params['choose_company_id'];
        }
        if($is_count==true){
            $result = $this->table("ota_company_website")->where($data)->count();
        }else {
            if ($is_page == true) {

                $result = $this->table("ota_company_website")
                    ->join('user','user.user_id = ota_company_website.update_user_id', 'left')
                    ->where($data)
                    ->limit($page, $page_size)
                    ->field(['ota_company_website.*','user.nickname'])
                    ->select();

            } else {
                $result = $this->table("ota_company_website")
                    ->where($data)
                    ->field(['ota_company_website.*'])
                    ->select();
            }
        }

        return $result;
    }


    public function updateOtaCompanyWebsiteById($params){
        $t = time();

        if($params['describe']){
            $data['describe'] = $params['describe'];
        }
        if($params['website']){
            $data['website'] = $params['website'];
        }
        $data['company_id'] = $params['choose_company_id'];
        $data['status'] = $params['status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        $data['uuid'] = rand();
        Db::startTrans();
        try{
            Db::name('ota_company_website')->where("ota_company_website_id = ".$params['ota_company_website_id'])->update($data);

            $result = 1;
            // 提交事务
            Db::commit();

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            Db::rollback();

        }
        return $result;


    }

}