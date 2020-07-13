<?php

namespace app\index\model\ota_slide;
use app\common\help\Help;
use think\Model;

use think\config;
use think\Db;
class OtaAdvertList extends Model{
    protected $table = 'ota_advert_list';

    public function initialize()
    {
        parent::initialize();
    }

    public function addOtaAdvertList($params){

        $t = time();
        if($params['ota_advert_list_name']){
            $data['ota_advert_list_name'] = $params['ota_advert_list_name'];
        }
        if($params['style']){
            $data['style'] = $params['style'];
        }
        $data['website_uuid'] = $params['website_uuid'];
        $data['company_id'] = $params['choose_company_id'];
        $data['status'] = $params['status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        $data['create_user_id'] = $params['user_id'];
        $data['create_time'] = $t;
        $data['uuid'] = Help::getUuid();

        Db::startTrans();
        try{
            Db::name('ota_advert_list')->insertGetId($data);

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

    public function getOneOtaAdvertList($params){
        if(isset($params['ota_advert_list_id'])) {
            $data['ota_advert_list_id'] = $params['ota_advert_list_id'];
        }
        if(isset($params['website_uuid'])) {
            $data['website_uuid'] = $params['website_uuid'];
        }
        if(isset($params['status'])) {
            $data['status'] = $params['status'];
        }
        $result = $this->table("ota_advert_list")->where($data)->find();

        return $result;
    }

    public function getOtaAdvertListList($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
        $data = "1=1 ";
        if($params['status'] < 2){
            $data.= " and ota_advert_list.status= ".$params['status'];
        }
        if(isset($params['ota_advert_list_name'])){
            $data.= " and ota_advert_list.ota_advert_list_name like'%".$params['ota_advert_list_name']."%'";
        }
        if(isset($params['choose_company_id'])){
            $data.= " and ota_advert_list.company_id = ".$params['choose_company_id'];
        }
        if(isset($params['website_uuid'])) {
            $data .= " and ota_advert_list.website_uuid= '" . $params['website_uuid'] . "'";
        }
        if($is_count==true){
            $result = $this->table("ota_advert_list")->where($data)->count();
        }else {
            if ($is_page == true) {

                $result = $this->table("ota_advert_list")
                    ->join('user','user.user_id = ota_advert_list.update_user_id', 'left')
                    ->where($data)
                    ->limit($page, $page_size)
                    ->field(['ota_advert_list.*','user.nickname'])
                    ->select();

            } else {
                $result = $this->table("ota_advert_list")
                    ->where($data)
                    ->field(['ota_advert_list.*'])
                    ->select();
            }
        }
        return $result;
    }


    public function updateOtaAdvertListById($params){
        $t = time();
        if(isset($params['ota_advert_list_name'])){
            $data['ota_advert_list_name'] = $params['ota_advert_list_name'];
        }
        if(isset($params['style'])){
            $data['style'] = $params['style'];
        }
        $data['company_id'] = $params['choose_company_id'];
        $data['status'] = $params['status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        Db::startTrans();
        try{
            Db::name('ota_advert_list')->where("ota_advert_list_id = ".$params['ota_advert_list_id'])->update($data);

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