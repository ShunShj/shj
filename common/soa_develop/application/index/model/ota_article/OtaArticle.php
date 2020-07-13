<?php

namespace app\index\model\ota_article;
use think\Model;
use think\config;
use app\common\help\Help;
use think\Db;
class OtaArticle extends Model{

    protected $autoWriteTimestamp = true;
    public function initialize()
    {
        parent::initialize();
    }

    public function addOtaArticle($params){

        $t = time();
        if(isset($params['title'])){
            $data['title'] = $params['title'];
        }
        if(isset($params['ota_article_type_uuid'])){
            $data['ota_article_type_uuid'] = $params['ota_article_type_uuid'];
        }
        if(isset($params['image'])){
            $data['image'] = $params['image'];
        }
        if(isset($params['content'])){
            $data['content'] = $params['content'];
        }
        if(isset($params['style'])){
            $data['style'] = $params['style'];
        }
        if(isset($params['start_time'])){
            $data['start_time'] = strtotime($params['start_time']);
        }
        if(isset($params['end_time'])){
            $data['end_time'] = strtotime($params['end_time']);
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
            Db::name('ota_article')->insertGetId($data);

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

    public function getOneArticle($params){


        if(isset($params['ota_article_id'])) {
            $data['ota_article_id'] = $params['ota_article_id'];
        }
        if(isset($params['article_uuid'])) {
            $data['uuid'] = $params['article_uuid'];
        }
        $result = $this->table("ota_article")->where($data)->find();

        //$result['content'] =preg_replace('/<img src="/','<img src ="http://' . $_SERVER['SERVER_ADDR'] .':7001',$result['content']);
        return $result;
    }

    public function getArticleList($params,$is_count=false,$is_page=false,$page=null,$page_size=20){

        $data = "1=1 ";

        if($params['status'] < 2){
            $data.= " and ota_article.status= ".$params['status'];
        }
        if(isset($params['website_uuid'])) {
            $data .= " and ota_article.website_uuid = '" . $params['website_uuid'] . "'";
        }
        if(isset($params['title'])){
            $data.= " and ota_article.title like'%".$params['title']."%'";
        }
        if(isset($params['choose_company_id'])){
            $data.= " and ota_article.company_id = ".$params['choose_company_id'];
        }
        if(isset($params['ota_article_type_uuid'])){
            $data.= " and ota_article.ota_article_type_uuid like'%".$params['ota_article_type_uuid']."%'";
        }

        if($is_count==true){
            $result = $this->table("ota_article")->where($data)->count();
        }else {
            if ($is_page == true) {

                $result = $this->table("ota_article")
                    ->join('user','user.user_id = ota_article.update_user_id', 'left')
                    ->where($data)
                    ->limit($page, $page_size)
                    ->field(['ota_article.*','user.nickname'])
                    ->select();
            } else {
                $result = $this->table("ota_article")
                    ->where($data)
                    ->field(['ota_article.*'])
                    ->select();
            }
        }

        return $result;
    }


    public function updateArticleById($params){
        $t = time();
        if(isset($params['title'])) {
            $data['title'] = $params['title'];
        }
        if(isset($params['ota_article_type_uuid'])){
            $data['ota_article_type_uuid'] = $params['ota_article_type_uuid'];
        }
        if(isset($params['image'])){
            $data['image'] = $params['image'];
        }
        if(isset($params['content'])) {
            $data['content'] = $params['content'];
        }
        if(isset($params['style'])){
            $data['style'] = $params['style'];
        }
        if(isset($params['start_time'])) {
            $data['start_time'] = strtotime($params['start_time']);
        }
        if(isset($params['end_time'])) {
            $data['end_time'] = strtotime($params['end_time']);
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
            Db::name('ota_article')->where("ota_article_id = ".$params['ota_article_id'])->update($data);

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