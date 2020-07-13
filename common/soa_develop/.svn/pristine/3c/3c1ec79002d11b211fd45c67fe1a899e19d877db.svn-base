<?php

namespace app\index\model;
use think\Model;
use app\common\help\Help;
class Member extends Model{
    protected $connection = ['database' => 'erp'];
    protected $table = 'member';


    //初始化方法
    protected function initialize()
    {

        parent::initialize();

    }
    //通过账号密码查询用户信息
    public function getMemberInfoByUsernameAndPassword($params){
        $data['username'] = $params['username'];
        $data['password'] = $params['password'];
        $result = $this->where($data)->find();


        return $result;
    }
}