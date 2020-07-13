<?php

namespace app\index\model\system;
use think\Model;
use think\Db;
use app\common\help\Help;
class Season extends Model{
    
    protected $table = 'season';
    private $_languageList;
    
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();
    }

    public function getSeason($params,$is_count=false,$is_page=false,$page=null,$page_size=20){

        $data = "1=1 ";
        if(!empty($params['season_name'])){
            $data.= " and season.season_name like'%".$params['season_name']."%'";
        }
        if(!empty($params['season_id'])){
            $data.= " and season.season_id = '".$params['season_id']."'";
        }
        if($params['status']<2){
            $data.= " and season.status = ".$params['status'];
        }
        if($is_count==true){
            $result = $this->where($data)->count();
        }else{
            if($is_page==true) {
                $result = $this->table("season")->where($data)->order('create_time desc')->limit($page, $page_size)->field(['season.*'])->select();
            }else{
                $result = $this->table("season")->where($data)->order('create_time desc')->field(['season.*'])->select();
            }
        }
        return $result;

    }


    public function addSeason($params){
        $t = time();
        if(!empty($params['season_name'])){
            $data['season_name'] = $params['season_name'];
        }
        if(!empty($params['content'])){
            $data['content'] = $params['content'];
        }
        $data['create_time'] = $t;
        $data['create_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        $data['update_user_id'] = $params['user_id'];
        $data['status'] = 1;
        Db::startTrans();
        try{
            Db::name('season')->insert($data);
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

    public function updateSeason($params){
        $t = time();
        if(!empty($params['season_name'])){
            $data['season_name'] = $params['season_name'];
        }
        if(!empty($params['content'])){
            $data['content'] = $params['content'];
        }
        $data['update_time'] = $t;
        $data['update_user_id'] = $params['user_id'];
        Db::startTrans();
        try{
            Db::name('season')->where("season_id = ".$params['season_id'])->update($data);
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

    public function deleteSeason($params){

        if(!empty($params['season_id'])){
            $season_id = $params['season_id'];
        }
        Db::startTrans();
        try{
            Db::table('season')->delete($season_id);
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

    public function getOneSeason($season_id){
        $result = $this->table("season")->where(['season_id' => $season_id])->find();
        return $result;
    }

    public function getSeasonAjax($params){

        $result = $this->table("season")
            ->order('create_time desc')
            ->where('status', 1)
            ->field(['season.*'])
            ->select();

        foreach ($result as $k=>$v){
            $where['language_id'] = $params['language_id'];
            $where['original_table_name'] = 'season';
            $where['original_table_field_name'] = 'season_name';
            $where['original_table_id'] = $v['season_id'];

            $translate =  $this->table('multilingual')->where($where)->column('translate');
            $result[$k]['translate'] = $translate[0] ? : $v['season_name'];
        }

        return $result;

    }

}