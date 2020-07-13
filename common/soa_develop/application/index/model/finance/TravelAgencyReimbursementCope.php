<?php

namespace app\index\model\finance;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;

use app\index\model\branchcompany\CompanyOrderCustomer;
class TravelAgencyReimbursementCope extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'travel_agency_reimbursement_cope';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }


    /**
     * 获取地接报账的应付
     * 胡
     */
    public function getTravelAgencyReimbursementCope($params){
    	
    	$data = [];
    	if(isset($params['travel_agency_reimbursement_number'])){
    		$data['travel_agency_reimbursement_number']= $params['travel_agency_reimbursement_number'];
    	}
		if(is_numeric($params['status'])){
			$data['status']= $params['status'];
		}
        $result= $this->
          
            where($data)->
            field([
            		'travel_agency_reimbursement_cope_id',
            		"(select cope_number from cope where cope.travel_agency_reimbursement_cope_id = travel_agency_reimbursement_cope.travel_agency_reimbursement_cope_id) as cope_number",
            		'supplier_id','source_type_id','product_name','currency_id','price','unit',
            		'reimbursement_money'
            		
            ])->select();
	
        return $result;

    }
    
    /**
     * 修改地接报账状态
     */
    public function updateTravelAgencyReimbursementCopeStatus($params){
     
    	
    	if(!empty($params['travel_agency_reimbursement_cope_id'])){
    		$where["travel_agency_reimbursement_cope_id"] = $params['travel_agency_reimbursement_cope_id'];
    	}
    	if(!empty($params['travel_agency_reimbursement_number'])){
    		$where["travel_agency_reimbursement_number"] = $params['travel_agency_reimbursement_number'];
    	}		
    	$data['status'] = 0;
		
    	$this->startTrans();
    	try{
    		$this->where($where)->update($data);
    	
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
     * 修改地接报账支出根据地接报账支出ID
     */
    public function updateTravelAgencyReimbursementCope($params){
    
    
    	$travel_agency_reimbursement_cope_id = $params['travel_agency_reimbursement_cope_id'];
    
    	if(!empty($params['supplier_id'])){
    		$data['supplier_id'] = $params['supplier_id'];
    	}
    	if(!empty($params['source_type_id'])){
    		$data['source_type_id'] = $params['source_type_id'];
    	}
    	if(!empty($params['product_name'])){
    		$data['product_name'] = $params['product_name'];
    	}
    	if(!empty($params['currency_id'])){
    		$data['currency_id'] = $params['currency_id'];
    	}
    	
    	if(is_numeric($params['unit'])){
    		$data['unit'] = $params['unit'];
    	}
    	if(is_numeric($params['price'])){
    		$data['price'] = $params['price'];
    	}
    	if(is_numeric($params['cope_money'])){
    		$data['reimbursement_money'] = $params['cope_money'];
    	}
    	
    	$data['status'] = 1;
    	$data['update_time'] = time();
    	$data['update_user_id'] = $params['now_user_id'];
    
    	$this->startTrans();
    	try{
    		$this->where("travel_agency_reimbursement_cope_id = $travel_agency_reimbursement_cope_id")->update($data);
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

}