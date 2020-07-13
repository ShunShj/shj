<?php

namespace app\index\model\ota_slide;
use think\Model;
use think\config;
use app\common\help\Help;
use think\Db;
class OtaSlideList extends Model{
    protected $table = 'ota_slide_list';

    public function initialize()
    {

        parent::initialize();

    }

    public function addOtaSlideList($params){
        $t = time();
        if(isset($params['ota_slide_list_name'])){
            $data['ota_slide_list_name'] = $params['ota_slide_list_name'];
        }
        if(isset($params['style'])){
            $data['style'] = $params['style'];
        }
        if(isset($params['width'])){
            $data['width'] = $params['width'];
        }
        if(isset($params['height'])){
            $data['height'] = $params['height'];
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
        $data['website_uuid'] = $params['website_uuid'];
        $data['status'] = $params['status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        $data['create_user_id'] = $params['user_id'];
        $data['create_time'] = $t;
        $data['uuid'] = Help::getUuid();
        Db::startTrans();
        try{
            $result = Db::name('ota_slide_list')->insert($data);
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

    public function getOneOtaSlideList($params){
        if(isset($params['ota_slide_list_id'])) {
            $data['ota_slide_list_id'] = $params['ota_slide_list_id'];
        }
        if(isset($params['website_uuid'])) {
            $data['website_uuid'] = $params['website_uuid'];
        }
        if(isset($params['status'])) {
            $data['status'] = $params['status'];
        }
        $result = $this->table("ota_slide_list")->where($data)->find();

        return $result;
    }

    public function getOtaSlideListList($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
        $data = "1=1 ";
        if($params['status'] < 2){
            $data.= " and ota_slide_list.status= ".$params['status'];
        }
        if(isset($params['ota_slide_list_name'])){
            $data.= " and ota_slide_list.ota_slide_list_name like'%".$params['ota_slide_list_name']."%'";
        }
        if(isset($params['choose_company_id'])){
            $data.= " and ota_slide_list.company_id = ".$params['choose_company_id'];
        }
        if(isset($params['website_uuid'])) {
            $data .= " and ota_slide_list.website_uuid= '" . $params['website_uuid'] . "'";
        }
        if($is_count==true){
            $result = $this->table("ota_slide_list")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("ota_slide_list")
                    ->join('user','user.user_id = ota_slide_list.update_user_id', 'left')
                    ->where($data)
                    ->limit($page, $page_size)
                    ->field(['ota_slide_list.*','user.nickname'])
                    ->select();
            } else {
                $result = $this->table("ota_slide_list")
                    ->where($data)
                    ->field(['ota_slide_list.*'])
                    ->select();
            }
        }
        return $result;
    }


    public function updateOtaSlideListById($params){
        $t = time();
        if(isset($params['ota_slide_list_name'])){
            $data['ota_slide_list_name'] = $params['ota_slide_list_name'];
        }
        if(isset($params['style'])){
            $data['style'] = $params['style'];
        }
        if(isset($params['width'])){
            $data['width'] = $params['width'];
        }
        if(isset($params['height'])){
            $data['height'] = $params['height'];
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
            Db::name('ota_slide_list')->where("ota_slide_list_id = ".$params['ota_slide_list_id'])->update($data);

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