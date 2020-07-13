<?php

namespace app\index\model\ota_slide;
use app\common\help\Help;
use think\Model;

use think\config;
use think\Db;
class OtaAdvertisingSubtitle extends Model{
    protected $table = 'ota_advertising_subtitle';

    public function initialize()
    {
        parent::initialize();
    }

    public function addOtaAdvertisingSubtitle($params){

        $t = time();
        if($params['subtitle']){
            $data['subtitle'] = $params['subtitle'];
        }
        if($params['sort']){
            $data['sort'] = $params['sort'];
        }
        if($params['url']){
            $data['url'] = $params['url'];
        }
        if($params['href_type']){
            $data['href_type'] = $params['href_type'];
        }
        if($params['without_href']){
            $data['without_href'] = $params['without_href'];
        }
        if($params['interior_type']){
            $data['interior_type'] = $params['interior_type'];
        }
        if($params['interior_uuid']){
            $data['interior_uuid'] = $params['interior_uuid'];
        }
        $data['ota_advertising_uuid'] = $params['ota_advertising_uuid'];
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
            Db::name('ota_advertising_subtitle')->insertGetId($data);

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

    public function getOneOtaAdvertisingSubtitle($params){

        $data['ota_advertising_subtitle_id'] = $params['ota_advertising_subtitle_id'];
        $result = $this->table("ota_advertising_subtitle")->where($data)->find();

        return $result;
    }

    public function getOtaAdvertisingSubtitleList($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
        $data = "1=1 ";
        if($params['status'] < 2){
            $data.= " and ota_advertising_subtitle.status= ".$params['status'];
        }
        if(!empty($params['subtitle'])){
            $data.= " and ota_advertising_subtitle.subtitle like'%".$params['subtitle']."%'";
        }
        if(isset($params['choose_company_id'])){
            $data.= " and ota_advertising_subtitle.company_id = ".$params['choose_company_id'];
        }
        if(isset($params['website_uuid'])) {
            $data .= " and ota_advertising_subtitle.website_uuid= '" . $params['website_uuid'] . "'";
        }
        if(isset($params['ota_advertising_uuid'])){
            $data.= " and ota_advertising_subtitle.ota_advertising_uuid= '".$params['ota_advertising_uuid']."'";
        }
        if($is_count==true){
            $result = $this->table("ota_advertising_subtitle")->where($data)->count();
        }else {
            if ($is_page == true) {

                $result = $this->table("ota_advertising_subtitle")
                    ->join('user','user.user_id = ota_advertising_subtitle.update_user_id', 'left')
                    ->where($data)
                    ->limit($page, $page_size)
                    ->field(['ota_advertising_subtitle.*','user.nickname'])
                    ->order('sort asc')
                    ->select();

            } else {
                $result = $this->table("ota_advertising_subtitle")
                    ->where($data)
                    ->field(['ota_advertising_subtitle.ota_advertising_subtitle_id,ota_advertising_subtitle.subtitle,ota_advertising_subtitle.href_type,ota_advertising_subtitle.without_href,ota_advertising_subtitle.interior_type,ota_advertising_subtitle.interior_uuid'])
                    ->order('sort asc')
                    ->select();
            }
        }
        return $result;
    }


    public function updateOtaAdvertisingSubtitleById($params){
        $t = time();
        if(isset($params['subtitle'])){
            $data['subtitle'] = $params['subtitle'];
        }
        if(isset($params['sort'])){
            $data['sort'] = $params['sort'];
        }
        if(isset($params['url'])){
            $data['url'] = $params['url'];
        }
        if(isset($params['href_type'])){
            $data['href_type'] = $params['href_type'];
        }
        if(isset($params['without_href'])){
            $data['without_href'] = $params['without_href'];
        }
        if(isset($params['interior_type'])){
            $data['interior_type'] = $params['interior_type'];
        }
        if(isset($params['interior_uuid'])){
            $data['interior_uuid'] = $params['interior_uuid'];
        }
        $data['company_id'] = $params['choose_company_id'];
        $data['status'] = $params['status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        Db::startTrans();
        try{
            Db::name('ota_advertising_subtitle')->where("ota_advertising_subtitle_id = ".$params['ota_advertising_subtitle_id'])->update($data);

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