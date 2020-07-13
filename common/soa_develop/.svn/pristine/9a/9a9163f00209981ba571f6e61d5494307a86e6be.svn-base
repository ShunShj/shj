<?php

namespace app\index\model\ota_slide;
use think\Model;
use think\config;
use app\common\help\Help;
use think\Db;
class OtaSlide extends Model{
    protected $table = 'ota_slide';

    public function initialize()
    {

        parent::initialize();

    }

    public function addOtaSlide($params){

        $t = time();
        if($params['pic']){
            $data['pic'] = $params['pic'];
        }
        if($params['video']){
            $data['video'] = $params['video'];
        }
        if($params['title']){
            $data['title'] = $params['title'];
        }
        if($params['url']){
            $data['url'] = $params['url'];
        }
        if($params['sort']){
            $data['sort'] = $params['sort'];
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
        $data['ota_slide_list_uuid'] = $params['ota_slide_list_uuid'];
        $data['website_uuid'] = $params['website_uuid'];
        $data['company_id'] = $params['choose_company_id'];
        $data['status'] = $params['status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        $data['create_user_id'] = $params['user_id'];
        $data['create_time'] = $t;
        $data['type'] = 1;
        $data['uuid'] = Help::getUuid();
        Db::startTrans();
        try{
            $result = Db::name('ota_slide')->insert($data);
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

    public function getOneOtaSlide($params){

        $data['ota_slide_id'] = $params['ota_slide_id'];
        $result = $this->table("ota_slide")->where($data)->find();

        return $result;
    }

    public function getOtaSlideList($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
        $data = "1=1 ";
        if($params['status'] < 2){
            $data.= " and ota_slide.status= ".$params['status'];
        }
        if(isset($params['title'])){
            $data.= " and ota_slide.title like'%".$params['title']."%'";
        }
        if(isset($params['choose_company_id'])){
            $data.= " and ota_slide.company_id = ".$params['choose_company_id'];
        }
        if(isset($params['ota_slide_list_uuid'])){
            $data.= " and ota_slide.ota_slide_list_uuid = '".$params['ota_slide_list_uuid']."'";
        }
        if(isset($params['website_uuid'])) {
            $data .= " and ota_slide.website_uuid= '" . $params['website_uuid'] . "'";
        }
        if($is_count==true){
            $result = $this->table("ota_slide")->where($data)->count();
        }else {
            if ($is_page == true) {

                $result = $this->table("ota_slide")
                    ->join('user','user.user_id = ota_slide.update_user_id', 'left')
                    ->where($data)
                    ->limit($page, $page_size)
                    ->field(['ota_slide.*','user.nickname'])
                    ->order('sort asc')
                    ->select();

            } else {
                $result = $this->table("ota_slide")
                    ->where($data)
                    ->field(['ota_slide.*'])
                    ->order('sort asc')
                    ->select();
            }
        }

        return $result;
    }


    public function updateOtaSlideById($params){
        $t = time();

        if(isset($params['pic'])){
            $data['pic'] = $params['pic'];
        }
        if(isset($params['video'])){
            $data['video'] = $params['video'];
        }
        if(isset($params['type'])){
            $data['type'] = $params['type'];
        }
        if(isset($params['title'])){
            $data['title'] = $params['title'];
        }
        if(isset($params['url'])){
            $data['url'] = $params['url'];
        }
        if(isset($params['sort'])){
            $data['sort'] = $params['sort'];
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
            Db::name('ota_slide')->where("ota_slide_id = ".$params['ota_slide_id'])->update($data);

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