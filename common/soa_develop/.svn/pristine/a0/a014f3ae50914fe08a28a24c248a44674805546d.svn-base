<?php

namespace app\index\model\approve;
use think\Model;
use app\index\model\finance\ReceivableInfo;
use app\index\model\finance\CopeInfo;
use app\index\service\InStationLetterService;
use app\common\help\Help;
use app\index\service\ProductService;
use think\config;
use think\Db;
class StatementApprove extends Model{
	  
    //protected $connection = ['database' => 'erp'];
    protected $table = 'statement_approve';
    private $_in_station_letter_service;
    private $_product_service;
    public function initialize()
    {
    	$this->_in_station_letter_service = new InStationLetterService();
    	$this->_product_service = new ProductService();
        parent::initialize();

    }

    /**
     *添加结算单审批数据
     */
    public function addStatementApprove($params){
    	$time = time();


    	$data['team_product_id'] = $params['team_product_id'];
    	$data['json_data'] = $params['json_data'];
		$data['company_id'] = $params['user_company_id'];
  		$data['create_time'] = $time;
  		$data['update_user_id'] = $params['now_user_id'];
  		$data['update_time'] = $time;
  		$data['create_user_id'] = $params['now_user_id'];
  		$data['type'] = $params['type'];
      	$data['status'] = 1;

      	
        $this->startTrans();
        try{
        	$this->insertGetId($data);
            // 提交事务
            $this->commit();
            $result = 1;

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            $this->rollback();

        }

        return $result;
    }
    /**
     * 修改结算单审批
     */
    public function updateStatementApprove($params){

        
    	$data['approve_result'] = $params['approve_result'];
    	 
        
        if(isset($params['approve_opinion'])){
        	$data['approve_opinion'] = $params['approve_opinion'];
        }
        

        
        if(is_numeric($params['statement_approve_id'])) {
            $where['statement_approve_id'] = $params['statement_approve_id'];

        }
        
        
        
        
        
        $data['status'] = 2;

      
        $data['approve_user_id'] = $params['now_user_id'];
        

        $data['approve_time'] = time();
        $this->startTrans();
        try{
            $this->where($where)->update($data);
			//再查询此条结算单信息
			$statement_params['statement_approve_id'] = $params['statement_approve_id'];
			$statement_result = $this->getStatementApprove($statement_params);
			if($params['approve_result']==1){
					//开始判断
					if($statement_result[0]['type']==1){
						$json_data = json_decode($statement_result[0]['json_data'],true);
						
						$result = $this->_product_service->addTeamProductReceivableSupplier($json_data);
					}
				
				
				//再发布站内信
				$in_station_letter_params = [
					'system_alert_event_id'=>26,	
					'user_id'=>$finance_result[0]['create_user_id'],
					'content'=>'您有一条审批信息，请到审批-结算单审批查看'	
				];
		
				$this->_in_station_letter_service->addInStationLetter($in_station_letter_params);
				
			}else{
				$result = 1;
			}

            // 提交事务
            $this->commit();

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            $this->rollback();

        }
        return $result;
    }

    /**
     * 获取审批详情
     * 胡
     */
    public function getStatementApprove($params)
    {
    	$data = "1=1";
    
    	if(!empty($params['team_product_id'])){
    		$data.= " and statement_approve.team_product_id = '".$params['team_product_id']."'";
    	}
    	if(is_numeric($params['type'])){
    		$data.= " and statement_approve.type= ".$params['type'];
    	}
    	if(is_numeric($params['company_id'])){
    		$data.= " and statement_approve.company_id = ".$params['company_id'];
    	}
    	if(is_numeric($params['statement_approve_id'])){
    		$data.= " and statement_approve.statement_approve_id = ".$params['statement_approve_id'];
    	}   	
    	if(is_numeric($params['approve_result'])){
    		 
    		$data.= " and statement_approve.approve_result = ".$params['approve_result'];
    	}
    	if(is_numeric($params['status'])){
    		 
    		$data.= " and statement_approve.status = ".$params['status'];
    	}   
    	if(is_numeric($params['create_user_id'])){
    		 
    		$data.= " and statement_approve.create_user_id = ".$params['create_user_id'];
    	}   	
    	if(!empty($params['create_time'])){
    		 
    		$data.= " and FROM_UNIXTIME(statement_approve.create_time,'%Y-%m-%d') = '".$params['create_time']."'";
    	}    	
    	if(!empty($params['approve_time'])){
    		 
    		$data.= " and FROM_UNIXTIME(statement_approve.approve_time,'%Y-%m-%d') = '".$params['approve_time']."'";
    	}   	
  
    	$result = $this->table("statement_approve")->alias("statement_approve")->

    	join("user user",'user.user_id= statement_approve.create_user_id')->
    	where($data)->
    	field(['statement_approve.*','user.nickname as create_user_name'])->
    	
    	select();
    	
    	return $result;
    
    
    }
}