<?php

namespace app\index\model\log;
use app\index\model\system\User as  UserLogin;
use app\common\help\Help;
use think\config;
use think\Model;
class LoginError extends Model{
   // protected $connection = ['database' => 'log'];
    protected $table = 'erp_login_error_log';


    //初始化方法
    public function __construct($tableName)
    {
   
    	if(gettype($tableName)=='string'){
    		$log_name = config('log_database');
    		$this->connection = ['database'=>$log_name];
    		$date = date('Ymd');
    		
    		$this->table = $tableName . '_' . $date;
    		
    		# 若表(Sql判断)不存在,则创建
    		$create_sql = "CREATE TABLE IF NOT EXISTS " . $this->table . " LIKE " . $tableName;
    		
    		
    		$result = $this->execute($create_sql);
    	}
    	


		 parent::__construct();
    }

	/**
	 * 获取登录错误日志
	 */
    
    public function getLoginError($params){
		$data['user_id'] = $params['user_id'];
	
	


		$result  = $this->where($data)->select();
	
	
			// 提交事务

	
		
		return $result;
    }
    
    /**
     * 添加登陆错误日志
     */
    public function addLoginError($params){
    	$data['user_id'] = $params['user_id'];
  
    	$data['create_time'] = time();
    	
    	$this->insert($data);

    }
    
    /**
     * 删除登陆错误日志
     */
    public function deleteLoginError($params){
    	$data['user_id'] = $params['user_id'];
    
   
    	 
    	$this->where($data)->delete();
    	 
    
    	 
    	 
    }
    
}