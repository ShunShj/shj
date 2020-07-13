<?php

namespace app\index\model\ota_article;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class OtaArticleType extends Model{

    public function initialize()
    {
        parent::initialize();
    }

    public function addOtaArticleType($params){

        $t = time();
        if($params['article_type_name']){
            $data['article_type_name'] = $params['article_type_name'];
        }
        if($params['image']){
            $data['image'] = $params['image'];
        }
        if($params['content']){
            $data['content'] = $params['content'];
        }
        if($params['author']){
            $data['author'] = $params['author'];
        }
        if($params['description']){
            $data['description'] = $params['description'];
        }
        if($params['keywords']){
            $data['keywords'] = $params['keywords'];
        }
        $data['pid'] = $params['pid'];
        $a = $this->getOneArticleType(['ota_article_type_id' => $params['pid']]);

        $data['level'] = $a['level'] ? $a['level']+1 : 1;
        $data['company_id'] = $params['choose_company_id'];
        $data['status'] = $params['status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        $data['create_user_id'] = $params['user_id'];
        $data['create_time'] = $t;
        $data['uuid'] =  Help::getUuid();
        $data['website_uuid'] = $params['website_uuid'];
        Db::startTrans();
        try{
            Db::name('ota_article_type')->insertGetId($data);

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

    public function getOneArticleType($params){
        if(isset($params['ota_article_type_id'])) {
            $data['ota_article_type_id'] = $params['ota_article_type_id'];
        }
        if(isset($params['ota_article_type_uuid'])){
            $data['uuid'] = $params['ota_article_type_uuid'];
        }
        $result = $this->table("ota_article_type")->where($data)->find();
        return $result;
    }

    public function getArticleTypeList($params,$is_count=false,$is_page=false,$page=null,$page_size=20){

        $data = "1=1 ";
        if($params['status'] < 2){
            $data.= " and ota_article_type.status= ".$params['status'];
        }
        $data.= " and ota_article_type.website_uuid = '".$params['website_uuid']."'";
        if(!empty($params['article_type_name'])){
            $data.= " and ota_article_type.article_type_name like'%".$params['article_type_name']."%'";
        }
        if(isset($params['choose_company_id'])){
            $data.= " and ota_article_type.company_id = ".$params['choose_company_id'];
        }

        if($is_count==true){
            $result = $this->table("ota_article_type")->where($data)->count();
        }else {
            if ($is_page == true) {

                $result = $this->table("ota_article_type")
                    ->join('user','user.user_id = ota_article_type.update_user_id', 'left')
                    ->where($data)
                    ->limit($page, $page_size)
                    ->field(['ota_article_type.*','user.nickname'])
                    ->select();

            } else {

                $result = $this->table("ota_article_type")
                    ->where($data)
                    ->field(['ota_article_type.*'])
                    ->select();

                $result = Help::toTree($result,0,0,'ota_article_type_id');
                foreach ($result as &$value) {
                    $value['article_type_name'] = str_repeat('--', $value['level']) . $value['article_type_name'];
                }
            }
        }

        return $result;
    }


    public function updateArticleTypeById($params){
        $t = time();

        if($params['article_type_name']){
            $data['article_type_name'] = $params['article_type_name'];
        }
        if($params['level']){
            $data['level'] = $params['level'];
        }
        if($params['pid']){
            $data['pid'] = $params['pid'];
        }
//        if(isset($params['image'])){
//            $data['image'] = $params['image'];
//        }
        if(isset($params['content'])) {
            $data['content'] = $params['content'];
        }
        $data['image'] = isset($params['image']) ? $params['image']: '';
        $data['author'] = isset($params['author']) ? $params['author']: '';
        $data['description'] = isset($params['description']) ? $params['description']: '';
        $data['keywords'] = isset($params['keywords']) ? $params['keywords']: '';

        $data['company_id'] = $params['choose_company_id'];
        $data['status'] = $params['status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        Db::startTrans();
        try{
            Db::name('ota_article_type')->where("ota_article_type_id = ".$params['ota_article_type_id'])->update($data);

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