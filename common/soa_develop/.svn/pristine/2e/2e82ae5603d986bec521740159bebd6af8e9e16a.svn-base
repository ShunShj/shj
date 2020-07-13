<?php

namespace app\index\model\system;
use think\Model;
use think\Db;
use app\common\help\Help;
class RoomType extends Model{
    
    protected $table = 'room_type';
    private $_languageList;
    
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();
    }

    public function getRoomType($params,$is_count=false,$is_page=false,$page=null,$page_size=20){

        $data = "1=1 ";
        if(!empty($params['room_type_name'])){
            $data.= " and room_type.room_type_name like'%".$params['room_type_name']."%'";
        }
        if(!empty($params['accommodate'])){
            $data.= " and room_type.accommodate = '".$params['accommodate']."'";
        }
        if(!empty($params['room_type_id'])){
            $data.= " and room_type.room_type_id = '".$params['room_type_id']."'";
        }

        if($is_count==true){
            $result = $this->where($data)->count();
        }else{
            if($is_page==true) {
                $result = $this->table("room_type")->where($data)->order('create_time desc')->limit($page, $page_size)->field(['room_type.*'])->select();
            }else{
                $result = $this->table("room_type")->where($data)->order('create_time desc')->field(['room_type.*'])->select();
            }
        }
        return $result;

    }


    public function addRoomType($params){
        $t = time();
        if(!empty($params['room_type_name'])){
            $data['room_type_name'] = $params['room_type_name'];
        }
        if(!empty($params['accommodate'])){
            $data['accommodate'] = $params['accommodate'];
        }
        $data['create_user_id'] = $params['user_id'];
        $data['create_time'] = $t;
        Db::startTrans();
        try{
            Db::name('room_type')->insert($data);
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

    public function updateRoomType($params){
        $t = time();
        if(!empty($params['room_type_name'])){
            $data['room_type_name'] = $params['room_type_name'];
        }
        if(!empty($params['accommodate'])){
            $data['accommodate'] = $params['accommodate'];
        }
        $data['create_user_id'] = $params['user_id'];
        $data['create_time'] = $t;
        Db::startTrans();
        try{
            Db::name('room_type')->where("room_type_id = ".$params['room_type_id'])->update($data);
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

    public function deleteRoomType($params){

        if(!empty($params['room_type_id'])){
            $room_type_id = $params['room_type_id'];
        }
        Db::startTrans();
        try{
            Db::table('room_type')->delete($room_type_id);
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

    public function getOneRoomType($room_type_id){
        $result = $this->table("room_type")->where(['room_type_id' => $room_type_id])->find();
        return $result;
    }

    public function getRoomTypeAjax($params){

        $result = $this->table("room_type")
            ->order('create_time desc')
            ->field(['room_type.*'])
            ->select();

        foreach ($result as $k=>$v){
            $where['language_id'] = $params['language_id'];
            $where['original_table_name'] = 'room_type';
            $where['original_table_field_name'] = 'room_type_name';
            $where['original_table_id'] = $v['room_type_id'];

            $translate =  $this->table('multilingual')->where($where)->column('translate');
            $result[$k]['translate'] = $translate[0] ? : $v['room_type_name'];
        }

        return $result;

    }
}