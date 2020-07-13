<?php

namespace app\index\model\ota_menu;
use app\common\help\Help;
use think\Model;

use think\config;
use think\Db;
class OtaMenu extends Model{
    protected $table = 'ota_menu';

    public function initialize()
    {
        parent::initialize();
    }

    public function addOtaMenu($params){
        $t = time();
        if(isset($params['menu_name'])){
            $data['menu_name'] = $params['menu_name'];
        }
        if(isset($params['style'])){
            $data['style'] = $params['style'];
        }
        if(isset($params['pid'])){
            $data['pid'] = $params['pid'];
        }
        if(isset($params['sorting'])){
            $data['sorting'] = $params['sorting'];
        }
        if(isset($params['href_type'])) {
            $data['href_type'] = $params['href_type'];
        }
        if(isset($params['without_href'])){
            $data['without_href'] = $params['without_href'];
        }
        if(isset($params['interior_type'])) {
            $data['interior_type'] = $params['interior_type'];
        }
        if(isset($params['interior_uuid'])){
            $data['interior_uuid'] = $params['interior_uuid'];
        }
        if(isset($params['website_uuid'])) {
            $data['website_uuid'] = $params['website_uuid'];
        }
        if(isset($params['ota_menu_list_uuid'])) {
            $data['ota_menu_list_uuid'] = $params['ota_menu_list_uuid'];
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
            Db::name('ota_menu')->insertGetId($data);

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

    public function getOneOtaMenu($params){
        $data = "1=1 ";
        if($params['ota_menu_id']){
            $data.= " and ota_menu.ota_menu_id= ".$params['ota_menu_id'];
        }
        $result = $this->table("ota_menu")
            ->join('company','company.company_id = ota_menu.company_id', 'left')
            ->where($data)
            ->field(['ota_menu.*','company.company_name'])
            ->find();
        return $result;
    }

    public function getOtaMenuList($params,$is_count=false,$is_page=false,$page=null,$page_size=20){

        $data = "1=1 ";
        if($params['status'] < 2){
            $data.= " and ota_menu.status= ".$params['status'];
        }
        if(isset($params['menu_name'])){
            $data.= " and ota_menu.menu_name like'%".$params['menu_name']."%'";
        }
        if(isset($params['ota_menu_list_uuid'])){
            $data.= " and ota_menu.ota_menu_list_uuid = '".$params['ota_menu_list_uuid']."'";
        }
        if(isset($params['choose_company_id'])){
            $data.= " and ota_menu.company_id = ".$params['choose_company_id'];
        }
        if(isset($params['website_uuid'])) {
            $data .= " and ota_menu.website_uuid= '" . $params['website_uuid'] . "'";
        }
        if(isset($params['pid'])) {
            $data .= " and ota_menu.pid= " . $params['pid'];
        }
        if($is_count==true){
            $result = $this->table("ota_menu")->where($data)->count();
        }else {
            if ($is_page == true) {

                $result = $this->table("ota_menu")
                    ->join('user','user.user_id = ota_menu.update_user_id', 'left')
                    ->where($data)
                    ->limit($page, $page_size)
                    ->field(['ota_menu.*','user.nickname', "(select menu_name  from ota_menu a where a.ota_menu_id= ota_menu.pid)"=>'pid_name',])
                    ->order('sorting asc')
                    ->select();

            } else {
                $result = $this->table("ota_menu")
                    ->join('user','user.user_id = ota_menu.update_user_id', 'left')
                    ->where($data)
                    ->field(['ota_menu.*','user.nickname', "(select menu_name  from ota_menu a where a.ota_menu_id= ota_menu.pid)"=>'pid_name',])
                    ->order('sorting asc')
                    ->select();
            }
        }

        return $result;
    }


    public function updateOtaMenuById($params){
        $t = time();
        if(isset($params['menu_name'])){
            $data['menu_name'] = $params['menu_name'];
        }
        if(isset($params['style'])){
            $data['style'] = $params['style'];
        }
        if(isset($params['sorting'])){
            $data['sorting'] = $params['sorting'];
        }
        if(isset($params['href_type'])) {
            $data['href_type'] = $params['href_type'];
        }
        if($params['without_href']){
            $data['without_href'] = $params['without_href'];
        }
        if($params['interior_type']) {
            $data['interior_type'] = $params['interior_type'];
        }
        if($params['interior_uuid']){
            $data['interior_uuid'] = $params['interior_uuid'];
        }
        $data['company_id'] = $params['choose_company_id'];
        $data['status'] = $params['status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        Db::startTrans();
        try{
            Db::name('ota_menu')->where("ota_menu_id = ".$params['ota_menu_id'])->update($data);

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

    public function getOtaWebMenuList($params){
        $data = "1=1 ";
        $data.= " and ota_menu.status= 1";
        if(!empty($params['menu_name'])){
            $data.= " and ota_menu.menu_name like'%".$params['menu_name']."%'";
        }
        if(!empty($params['ota_menu_list_uuid'])){
            $data.= " and ota_menu.ota_menu_list_uuid = '".$params['ota_menu_list_uuid']."'";
        }
        if(!empty($params['website_uuid'])) {
            $data .= " and ota_menu.website_uuid= '" . $params['website_uuid'] . "'";
        }
        $result = $this->table("ota_menu")
            ->join('user','user.user_id = ota_menu.update_user_id', 'left')
            ->where($data)
            ->field(['ota_menu.ota_menu_id','ota_menu.uuid','ota_menu.menu_name','ota_menu.pid','ota_menu.href_type','ota_menu.without_href','ota_menu.interior_type','ota_menu.interior_uuid','user.nickname',])
            ->order('sorting asc')
            ->select();
        $result = $this->get_tree($result);
        return $result;
    }

    protected function get_tree($arr, $pid = 0, $id = 'ota_menu_id', $pname = 'pid', $child = 'children')
    {
        $tree = array();
        foreach ($arr as $value) {
            if ($value[$pname] == $pid) {
                $value[$child] = $this->get_tree($arr, $value[$id], $id = 'ota_menu_id', $pname = 'pid', $child = 'children');
                if ($value[$child] == null) {
                    unset($value[$child]);
                }
                $tree[] = $value;
            }
        }
        return $tree;
    }

}