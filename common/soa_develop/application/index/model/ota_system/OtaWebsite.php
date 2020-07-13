<?php

namespace app\index\model\ota_system;
use app\common\help\Help;
use think\Model;

use think\config;
use think\Db;
class OtaWebsite extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'ota_website';

    public function initialize()
    {

        parent::initialize();

    }

    public function addOtaWebsite($params){
        $t = time();
        if($params['logo']){
            $data['logo'] = $params['logo'];
        }
        if($params['web_name']){
            $data['web_name'] = $params['web_name'];
        }
        if($params['web_href']){
            $data['web_href'] = $params['web_href'];
        }
        if($params['time_zone_id']){
            $data['time_zone_id'] = $params['time_zone_id'];
        }
        if($params['language_id']){
            $data['language_id'] = $params['language_id'];
        }
        if($params['currency_id']){
            $data['currency_id'] = $params['currency_id'];
        }
        if($params['put_on_records']){
            $data['put_on_records'] = $params['put_on_records'];
        }
        if($params['tourism_business_license_number']){
            $data['tourism_business_license_number'] = $params['tourism_business_license_number'];
        }
        if($params['reservation_phone']){
            $data['reservation_phone'] = $params['reservation_phone'];
        }
        if($params['email']){
            $data['email'] = $params['email'];
        }
        if($params['scope_of_business']){
            $data['scope_of_business'] = $params['scope_of_business'];
        }
        if($params['company_address']){
            $data['company_address'] = $params['company_address'];
        }
        if($params['about_us']){
            $data['about_us'] = $params['about_us'];
        }
        if($params['contact_us']){
            $data['contact_us'] = $params['contact_us'];
        }
        if(isset($params['author'])){
            $data['author'] = $params['author'];
        }
        if(isset($params['keywords'])){
            $data['keywords'] = $params['keywords'];
        }
        if(isset($params['description'])){
            $data['description'] = $params['description'];
        }
        $data['company_id'] = $params['choose_company_id'];
        $data['status'] = $params['status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        $data['create_user_id'] = $params['user_id'];
        $data['create_time'] = $t;
        $data['uuid'] = Help::getUuid();

        Db::startTrans();
        try{
            Db::name('ota_website')->insertGetId($data);

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

    public function getOneOtaWebsite($params){

        if($params['ota_website_id']){
            $where['ota_website_id'] = $params['ota_website_id'];
        }
        if($params['web_href']){
            $where['web_href'] = $params['web_href'];
        }
        $result = $this->table("ota_website")->where($where)->find();
        return $result;
    }

    public function getOneOtaWebsiteWeb($params){
        $where['web_href'] = $params['web_href'];
        $result = $this->table("ota_website")->where($where)->find();
        return $result;
    }

    public function getOtaWebsiteList($params){
        $data = "1=1 ";
        if($params['status'] < 2){
            $data.= " and ota_website.status= ".$params['status'];
        }
        if(isset($params['web_name'])){
            $data.= " and ota_website.web_name like'%".$params['web_name']."%'";
        }
        if(isset($params['choose_company_id'])){
            $data.= " and ota_website.company_id = ".$params['choose_company_id'];
        }

        $result = $this->table("ota_website")
            ->join('user','user.user_id = ota_website.update_user_id', 'left')
            ->join('company','company.company_id = ota_website.company_id', 'left')
            ->where($data)
            ->field(['ota_website.*','company.company_name','user.nickname'])
            ->select();
        return $result;
    }


    public function updateOtaWebsiteById($params){
        $t = time();

        if(isset($params['logo'])){
            $data['logo'] = $params['logo'];
        }
        if(isset($params['web_name'])){
            $data['web_name'] = $params['web_name'];
        }
        if(isset($params['web_href'])) {
            $data['web_href'] = $params['web_href'];
        }
        if(isset($params['time_zone_id'])){
            $data['time_zone_id'] = $params['time_zone_id'];
        }
        if(isset($params['language_id'])){
            $data['language_id'] = $params['language_id'];
        }
        if(isset($params['currency_id'])){
            $data['currency_id'] = $params['currency_id'];
        }
        if(isset($params['put_on_records'])) {
            $data['put_on_records'] = $params['put_on_records'];
        }
        if(isset($params['tourism_business_license_number'])) {
            $data['tourism_business_license_number'] = $params['tourism_business_license_number'];
        }
        if(isset($params['reservation_phone'])) {
            $data['reservation_phone'] = $params['reservation_phone'];
        }
        if(isset($params['email'])) {
            $data['email'] = $params['email'];
        }
        if(isset($params['scope_of_business'])) {
            $data['scope_of_business'] = $params['scope_of_business'];
        }
        if(isset($params['company_address'])) {
            $data['company_address'] = $params['company_address'];
        }
        if(isset($params['about_us'])) {
            $data['about_us'] = $params['about_us'];
        }
        if(isset($params['contact_us'])) {
            $data['contact_us'] = $params['contact_us'];
        }
        $data['author'] = isset($params['author']) ? $params['author']: '';
        $data['description'] = isset($params['description']) ? $params['description']: '';
        $data['keywords'] = isset($params['keywords']) ? $params['keywords']: '';
        $data['company_id'] = $params['choose_company_id'];
        $data['status'] = $params['status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        Db::startTrans();
        try{
            Db::name('ota_website')->where("ota_website_id = ".$params['ota_website_id'])->update($data);

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