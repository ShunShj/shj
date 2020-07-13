<?php

namespace app\index\model\log;
use think\config;
use think\Model;

class Log extends Model{
   // protected $connection = ['database' => 'log'];
    protected $table = 'soaerp_log';


    //初始化方法
    public function __construct($tableName)
    {
    	
    	$log_name = config('log_database');
    	$this->connection = ['database'=>$log_name];
        $date = date('Ymd');
	
        $this->table = $tableName . '_' . $date;
		
        # 若表(Sql判断)不存在,则创建
        $create_sql = "CREATE TABLE IF NOT EXISTS " . $this->table . " LIKE " . $tableName;

        $result = $this->execute($create_sql);


    }

    //添加接口日志
    public function addLog($params,$type,$result=[]){

        $data['params'] = json_encode($params);
        $data['time'] = time();
        $data['type'] = $type;
      
       	$data['user_id'] = isset($params['now_user_id'])?$params['now_user_id']:0;
        $data['result'] = json_encode($result);


        $data['url'] = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $this->insert($data);
        return true;

    }
    /**
     * 添加登陆日志
     */
    public function addLoginLog($params){
    	//$data['username'] = $params['username'];
    	//$data['ip'] = $params['ip'];
    	$data['create_time'] = time();
    	
    	$this->insert($data);
    }
}