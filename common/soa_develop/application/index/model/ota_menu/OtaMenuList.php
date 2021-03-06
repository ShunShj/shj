<?php

namespace app\index\model\ota_menu;
use app\common\help\Help;
use think\Model;

use think\config;
use think\Db;
class OtaMenuList extends Model{
    protected $table = 'ota_menu_list';

    public function initialize()
    {
        parent::initialize();
    }

    public function addOtaMenuList($params){
        $t = time();
        if(isset($params['title'])){
            $data['title'] = $params['title'];
        }
        if(isset($params['style'])){
            $data['style'] = $params['style'];
        }
        if(isset($params['type'])){
            $data['type'] = $params['type'];
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
            Db::name('ota_menu_list')->insertGetId($data);

            $result = 1;
            // 提交事务
            Db::commit();

        } catch (\Exception $e) {

            $result = $e->getMessage();
            // 回滚事务
            Db::rollback();
            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();

        }

        return $result;
    }

    public function getOneOtaMenuList($params){
        $data = "1=1 ";
        if(isset($params['ota_menu_list_id'])){
            $data.= " and ota_menu_list.ota_menu_list_id= ".$params['ota_menu_list_id'];
        }
        if(isset($params['type'])){
            $data.= " and ota_menu_list.type= ".$params['type'];
        }
        if(isset($params['status'])){
            $data.= " and ota_menu_list.status= ".$params['status'];
        }

        if(isset($params['website_uuid'])){
            $data.= " and ota_menu_list.website_uuid= '".$params['website_uuid']."'";
        }
		
        $result = $this->table("ota_menu_list")
            ->join('company','company.company_id = ota_menu_list.company_id', 'left')
            ->where($data)
            ->field(['ota_menu_list.*','company.company_name'])
            ->find();
        return $result;
    }

    public function getOtaMenuListList($params,$is_count=false,$is_page=false,$page=null,$page_size=20){

        $data = "1=1 ";
        if($params['status'] < 2){
            $data.= " and ota_menu_list.status= ".$params['status'];
        }
        if(isset($params['title'])){
            $data.= " and ota_menu_list.title like'%".$params['title']."%'";
        }
        if(isset($params['choose_company_id'])){
            $data.= " and ota_menu_list.company_id = ".$params['choose_company_id'];
        }
        if(isset($params['website_uuid'])) {
            $data .= " and ota_menu_list.website_uuid= '" . $params['website_uuid'] . "'";
        }

        if($is_count==true){
            $result = $this->table("ota_menu_list")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("ota_menu_list")
                    ->join('user', 'user.user_id = ota_menu_list.update_user_id', 'left')
                    ->where($data)
                    ->limit($page, $page_size)
                    ->field(['ota_menu_list.*', 'user.nickname'])
                    ->select();
            } else {
                $result = $this->table("ota_menu_list")
                    ->join('user', 'user.user_id = ota_menu_list.update_user_id', 'left')
                    ->where($data)
                    ->field(['ota_menu_list.*', 'user.nickname'])
                    ->select();
            }
        }
        return $result;
    }


    public function updateOtaMenuListById($params){

        $t = time();
        if(isset($params['title'])){
            $data['title'] = $params['title'];
        }
        if(isset($params['style'])){
            $data['style'] = $params['style'];
        }
        if(isset($params['type'])){
            $data['type'] = $params['type'];
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
            Db::name('ota_menu_list')->where("ota_menu_list_id = ".$params['ota_menu_list_id'])->update($data);

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