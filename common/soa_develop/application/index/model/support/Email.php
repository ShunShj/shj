<?php

namespace app\index\model\support;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class Email extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'email';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加邮件
     * 胡
     */
    public function addEmail($params){

		$data = $params;
		$data['create_time'] = time();




        Db::startTrans();
        try{
            Db::name('email')->insert($data);
  
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

    /**
     * 获取邮件数据
     * 胡
     */
    public function getEmail($params){

 
		$data = [
			'user_id'=>$params['user_id'],
			'type'=>1
		];
    	


        $result= $this->where($data)->order("create_time desc")->find();
        return $result;

    }



}