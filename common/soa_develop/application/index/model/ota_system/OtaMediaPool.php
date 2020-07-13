<?php

namespace app\index\model\ota_system;
use app\common\help\Help;
use think\Model;

use think\config;
use think\Db;
class OtaMediaPool extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'ota_media_pool';

    public function initialize()
    {

        parent::initialize();

    }

    public function addOtaMediaPool($params){
        $t = time();
        if($params['url']){
            $data['url'] = $params['url'];
        }
        if($params['size']){
            $data['size'] = $params['size'];
        }

        $data['type'] = $params['type'] ? : 1;
        $data['website_uuid'] = $params['website_uuid'];
        $data['company_id'] = $params['choose_company_id'];
        $data['create_user_id'] = $params['user_id'];
        $data['create_time'] = $t;
        error_log(print_r(Help::modelDataToArr($params),1));
        Db::startTrans();
        try{
            Db::name('ota_media_pool')->insertGetId($data);

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

    public function getOneOtaMediaPool($params){

        if($params['ota_media_pool_id']){
            $where['ota_website_id'] = $params['ota_media_pool_id'];
        }
        $result = $this->table("ota_media_pool")->where($where)->find();
        return $result;
    }

    public function deleteOtaMediaPool($params){
        if($params['ota_media_pool_id']){
            $where['ota_media_pool_id'] = $params['ota_media_pool_id'];
        }
        $result = $this->table("ota_media_pool")->where($where)->delete();
        return $result;
    }

    public function getOtaMediaPoolList($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
        $data = "1=1 ";
        if(!empty($params['website_uuid'])){
            $data.= " and ota_media_pool.website_uuid= '".$params['website_uuid']."'";
        }
        if(isset($params['choose_company_id'])){
            $data.= " and ota_media_pool.company_id = ".$params['choose_company_id'];
        }

        if($is_count==true){
            $result = $this->table("ota_media_pool")->where($data)->count();
        }else {
            if ($is_page == true) {

                $result = $this->table("ota_media_pool")
                    ->join('user','user.user_id = ota_media_pool.create_user_id', 'left')
                    ->where($data)
                    ->limit($page, $page_size)
                    ->field(['ota_media_pool.*','user.nickname'])
                    ->select();

            } else {
                $result = $this->table("ota_media_pool")
                    ->where($data)
                    ->field(['ota_media_pool.*'])
                    ->order('create_time desc')
                    ->select();
            }
        }

        return $result;
    }


}