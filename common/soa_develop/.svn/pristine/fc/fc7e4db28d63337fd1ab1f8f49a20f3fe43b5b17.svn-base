<?php

namespace app\index\model\finance;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class ApportionProportion extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'apportion_proportion';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

 	/**
 	 * 添加分摊
 	 */
    public function addApportionProportin($params){
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$company_id = $params['user_company_id'];
    	$apportion_proportion_array = $params['apportion_proportion_array'];
    	$this->startTrans();
    	try{
    		//首先添加分摊信息到数据库
    		$data = [
    			'create_time'=>$t,
    			'update_time'=>$t,
    			'create_user_id'=>$user_id,
    			'update_user_id'=>$user_id,
    			'company_id'=>$company_id,
    			'total_money'=>$params['total_money'],
    			'project_name'=>$params['project_name'],
    			'year'=>$params['year'],
    			'month'=>$params['month'],
    			'status'=>1	
    				
    		];
    		$pk_id = $this->insertGetId($data);

    	
    		for($i=0;$i<count($apportion_proportion_array);$i++){
    			$apportion_proportion_params = [
    				'apportion_proportion_id'=>$pk_id,	
    				'create_user_id' => $user_id,
    				'update_user_id' => $user_id,
    				'update_time' => $t,
    				'create_time'=>$t,
    				'apportion_proportion'=>$apportion_proportion_array[$i]['apportion_proportion'],
    				'company_id'=>$apportion_proportion_array[$i]['company_id'],
    				'money'=>$apportion_proportion_array[$i]['money'],
    				'status'=>1
    			];
    	
    			$this->table('apportion_proportion_info')->insert($apportion_proportion_params);
    	
    		}
    	
    			
    		$result = 1;
    		// 提交事务
    		Db::commit();
    			
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		$this->rollback();
    			
    	}
    	return $result;
    	 
    }
    
    /**
     * 获取分摊
     */
    public function getApportionProportion($params){
    	$data = "status = 1";
    	
    	if(!empty($params['company_id'])){
    		$data.= " and  company_id=".$params['company_id'];
    	}
    	
    	if(!empty($params['apportion_proportion_id'])){
    		$data.= " and  apportion_proportion_id=".$params['apportion_proportion_id'];
    	}    	
    	$data.= " and ".$params['year']." = year and month =".$params['month'];
    	
    	$result = $this->where($data)->select();
    	
    	return $result;

    }
    /**
     * 修改分摊
     */
    public function updateApportionProportionByApportionProportionId($params){
    	$user_id = $params['now_user_id'];
    	$apportion_proportion_id = $params['apportion_proportion_id'];
    	$t =  time();
    	$apportion_proportion_array = $params['apportion_proportion_array'];
    	if(!empty($params['project_name'])){
    		$data['project_name']=$params['project_name'];
    	}
 
    	if(is_numeric($params['total_money'])){
    		$data['total_money']=$params['total_money'];
    	}

    	if(is_numeric($params['status'])){
    		$data['status']=$params['status'];
    	}
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $user_id;
    	$this->startTrans();
    	try{

    		$this->where("apportion_proportion_id = $apportion_proportion_id")->update($data);
    		$apportion_proportion_info_params = [
    			'status'=>0	
    		];
    		//把分摊详情都设置为0
    		$this->table("apportion_proportion_info")->where("apportion_proportion_id = $apportion_proportion_id")->update($apportion_proportion_info_params);
    		for($i=0;$i<count($apportion_proportion_array);$i++){
    			$apportion_proportion_params = [
    					'apportion_proportion_id'=>$apportion_proportion_id,
    					'create_user_id' => $user_id,
    					'update_user_id' => $user_id,
    					'update_time' => $t,
    					'create_time'=>$t,
    					'apportion_proportion'=>$apportion_proportion_array[$i]['apportion_proportion'],
    					'company_id'=>$apportion_proportion_array[$i]['company_id'],
    					'money'=>$apportion_proportion_array[$i]['money'],
    					'status'=>1
    			];
    			 
    			$this->table('apportion_proportion_info')->insert($apportion_proportion_params);
    			 
    		}
    		 
    		 
    		$result = 1;
    		// 提交事务
    		Db::commit();
    		 
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		$this->rollback();
    		 
    	}
    	return $result;    	
    	
    	
    }
}