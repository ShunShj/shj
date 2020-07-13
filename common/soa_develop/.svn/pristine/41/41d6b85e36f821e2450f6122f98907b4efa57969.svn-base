<?php

namespace app\index\model\ota_system;
use app\common\help\Help;
use think\Model;

use think\config;
use think\Db;
class OtaSystemConfig extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'ota_system_config';

    public function initialize()
    {

        parent::initialize();

    }

    public function addOtaSystemConfig($params){
        error_log(print_r(Help::modelDataToArr($params),1));
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
        $data['company_id'] = $params['choose_company_id'];
        $data['status'] = $params['status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        $data['create_user_id'] = $params['user_id'];
        $data['create_time'] = $t;
        $data['uuid'] = rand();

        Db::startTrans();
        try{
            Db::name('ota_system_config')->insertGetId($data);

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

    public function getOneOtaSystemConfig($params){

        if($params['system_config_id']){
            $where['system_config_id'] = $params['system_config_id'];
        }
        //$where['company_id'] = $params['choose_company_id'];
        $result = $this->table("ota_system_config")->where($where)->find();
        error_log(print_r(Help::modelDataToArr($result),1));
        return $result;
    }

    public function getOtaSystemConfigList($params){
        $data = "1=1 ";
        if($params['status'] < 2){
            $data.= " and ota_system_config.status= ".$params['status'];
        }
        if(!empty($params['web_name'])){
            $data.= " and ota_system_config.web_name like'%".$params['web_name']."%'";
        }
        if(isset($params['choose_company_id'])){
            $data.= " and ota_system_config.company_id = ".$params['choose_company_id'];
        }

        $result = $this->table("ota_system_config")
            ->join('user','user.user_id = ota_system_config.update_user_id', 'left')
            ->join('company','company.company_id = ota_system_config.company_id', 'left')
            ->where($data)
            ->field(['ota_system_config.*','company.company_name','user.nickname'])
            ->select();
        return $result;
    }


    public function updateOtaSystemConfigById($params){
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
        $data['company_id'] = $params['choose_company_id'];
        $data['status'] = $params['status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        $data['uuid'] = rand();
        Db::startTrans();
        try{
            Db::name('ota_system_config')->where("system_config_id = ".$params['system_config_id'])->update($data);

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