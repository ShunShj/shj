<?php
namespace app\index\model\ota_member;
use think\Model;

class OtaMember extends Model{

    protected $table = 'ota_member';

    //自动过滤掉不存在的字段
    protected $field = true;

    public function initialize()
    {
        parent::initialize();
    }

    public function getOtaMemberById($id)
    {
        return $this->where(['member_id'=> $id])->find();
    }

    public function getOtaMember($post_data)
    {
        $where['uuid'] =  $post_data['uuid'];
        return $this->where($where)->find();
    }

    public function addOtaMember($data)
    {
        if ($this->create($data)->save() !== false)
        {
            return true;
        }
        return false;
    }

    public function editOtaMember(array $data, $where)
    {
        return $this->update($data, $where);
    }



}