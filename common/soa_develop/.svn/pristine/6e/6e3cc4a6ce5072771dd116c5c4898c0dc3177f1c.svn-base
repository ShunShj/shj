<?php

namespace app\index\model\finance;
use think\Model;
use app\index\model\finance\ReceivableInfo;
use app\index\model\finance\CopeInfo;
use app\index\model\finance\Receivable;
use app\index\model\finance\Cope;
use app\index\service\InStationLetterService;
use app\common\help\Help;
use think\config;
use think\Db;
class FinanceApprove extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'finance_approve';
    private $_languageList;
    private $_in_station_letter_service;
    private $_receivable;
    private $_cope;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        $this->_in_station_letter_service = new InStationLetterService();
        $this->_receivable = new Receivable();
        $this->_cope = new Cope();
        parent::initialize();

    }

    /**
     *添加财务审批数据
     */
    public function addFinanceApprove($params){
    	$time = time();
      	if(!empty($params['order_number'])){
      		$data['company_order_number'] = $params['order_number'];
      	}
      	if($params['finance_type']==1){//为应收 2为应付
      		$data['finance_type'] = 1;
      		
      		
      		$data['finance_number']= $params['receivable_number'];
      		//通过编号查询应收的团队产品ID
      		$receivable_params =[
      			'receivable_number'=>$params['receivable_number']
      		];
      		$receivable_result  = $this->_receivable->getReceivable($receivable_params);
      		$data['money'] = $params['payment_money'];
      		if(!empty($receivable_result)){
      			
      			if(is_numeric($receivable_result[0]['team_product_id'])){
      				$data['team_product_id'] = $receivable_result[0]['team_product_id'];
      			}
      			
      			//开始判断是否是供应商的数据
      			if($receivable_result[0]['payment_object_type']==2){
      				$data['supplier_id'] = $receivable_result[0]['payment_object_id'];
      			}
				
      			/**
      			 * 这里开始 代码注释
					
				//开始判断是否是供应商的数据
      			if($receivable_result[0]['payment_object_type']==2){
      				$data['supplier_id'] = $receivable_result[0]['payment_object_id'];
      				
      				 先获取未收
      				$miss_receivable = $receivable_result[0]['receivable_money']-$receivable_result[0]['true_receipt'];
      				//开始判断未收 实收 >= 实收
      				if($params['payment_money']>$miss_receivable){ //如果实付大于未付
      					$data['money'] = $miss_receivable;
      				
      				
      				}
      			}	
					
					




      			 开始判断是否是代理商数据
      			if($receivable_result[0]['payment_object_type']==3){
      				$data['supplier_id'] = $receivable_result[0]['payment_object_id'];
      			}
      			
      			
      			
      			
      			*/	
      		}
      		

      		
      		
      		
      			
      		
      		
      		if(is_numeric($params['payment_stage'])){
      			$data['stage'] = $params['payment_stage'];
      		}
      		$data['currency_id'] = $params['payment_currency_id'];
      	
      		$data['type'] = $params['payment_type'];

      	
      		//$data['voucher_time'] = $params['voucher_time'];
      		
      		$data['receivable_info_type'] = $params['receivable_info_type'];
      		if(!empty($params['exg_rate_gain'])){
      			$data['exg_rate_gain'] = $params['exg_rate_gain'];
      		}
      		if(!empty($params['supplier_name'])){
      			$data['supplier_name'] = $params['supplier_name'];
      		}
      		if(!empty($params['sn_number'])){
      			$data['sn_number'] = $params['sn_number'];
      		}
      		if(!empty($params['account_number'])){
      			$data['account_number'] = $params['account_number'];
      		}     		
      		if(!empty($params['voucher_number'])){
      			$data['voucher_number'] = $params['voucher_number'];
      		}
      		if(!empty($params['voucher_time'])){//付款时间等于凭证时间
      			$data['voucher_time'] = $params['voucher_time'];
      		}
      		if(!empty($params['remark'])){//付款时间等于凭证时间
      			$data['remark'] = $params['remark'];
      		}
      		if(!empty($params['attachment'])){//附件
      			$data['attachment'] = $params['attachment'];
      		}
      		if(!empty($params['receipts_pay_id'])){
      			$data['receipts_pay_id'] = $params['receipts_pay_id'];
      		}
      		if(is_numeric($params['base_money'])){
      			$data['base_money'] = $params['base_money'];
      		}
      	}else{
      		$data['finance_type'] = 2;
      		$data['finance_number']= $params['cope_number'];
      		//通过编号查询应收的团队产品ID
      		$cope_params =[
      			'cope_number'=>$params['cope_number']
      		];
      		$cope_result  = $this->_cope->getCope($cope_params);
      		if(!empty($cope_result)){
      			 
      			if(is_numeric($cope_result[0]['team_product_id'])){
      				$data['team_product_id'] = $cope_result[0]['team_product_id'];
      			}
      			//开始判断是否是供应商的数据
      			if($cope_result[0]['receivable_object_type']==2){
      				$data['supplier_id'] = $cope_result[0]['receivable_object_id'];
      			}
      		
      		}
      		
      	    if(!empty($params['voucher_number'])){
      			$data['voucher_number'] = $params['voucher_number'];
      		}
      		if(!empty($params['voucher_time'])){//付款时间等于凭证时间
      			$data['voucher_time'] = $params['voucher_time'];
      		}
      		if(!empty($params['remark'])){//付款时间等于凭证时间
      			$data['remark'] = $params['remark'];
      		}
      		if(!empty($params['attachment'])){//附件
      			$data['attachment'] = $params['attachment'];
      		}
      		
      		$data['money'] = $params['payment_money'];
      		$data['currency_id'] = $params['payment_currency_id'];
      		$data['type'] = $params['payment_type'];
      		if(!empty($params['receipts_pay_id'])){
      			$data['receipts_pay_id'] = $params['receipts_pay_id'];
      		}
      		if(is_numeric($params['base_money'])){
      			$data['base_money'] = $params['base_money'];
      		}
      	}
      	
      	if(!empty($params['order_number'])){
      		$data['company_order_number'] = $params['order_number'];
      	}

		$data['company_id'] = $params['user_company_id'];
  		$data['create_time'] = $time;
  		$data['update_user_id'] = $params['now_user_id'];
  		$data['update_time'] = $time;
  		$data['create_user_id'] = $params['now_user_id'];
      	$data['status'] = 1;

        $this->startTrans();
        try{
        	
        
        	
        	
        	$result = $this->insertGetId($data);
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
     * 修改财务审批
     */
    public function updateFinanceApprove($params){
		$receivable_info = new ReceivableInfo();
		$cope_info = new CopeInfo();
        

        

        
        if(is_numeric($params['finance_approve_id'])) {
            $where['finance_approve_id'] = $params['finance_approve_id'];

        }
        
        	 if($params['finance_type']==1){//代表应收

        	 	$data['status'] = 2;
        	 	$data['approve_user_id'] = $params['now_user_id'];
        	 	$data['approve_time'] = time();
        	 	$data['approve_result'] = $params['approve_result'];
        	 	$data['update_time'] = time();
        	 }else{//代表应付
				if($params['approve_result']==2){//代表不同意
					$data['status'] = 2;
					$data['approve_user_id'] = $params['now_user_id'];
					$data['approve_time'] = time();
					$data['approve_result'] = $params['approve_result'];
					$data['update_time'] = time();					
				
				}else{
					if($params['account_status'] ==1){
						if(isset($params['account_opinion'])){
							$data['account_opinion'] = $params['account_opinion'];
						}
						$data['account_guess_time'] = time();       		
						$data['account_status'] = 1;
						$data['account_user_id'] = $params['now_user_id'];
						$data['status'] = 2;
					}else{
						$data['approve_user_id'] = $params['now_user_id'];
						$data['approve_time'] = time();
						$data['approve_result'] = $params['approve_result'];
						$data['update_time'] = time();
						
					}
					
				}
			 
			 

        	 	
        	 }
        

        	if(isset($params['approve_opinion'])){
        		$data['approve_opinion'] = $params['approve_opinion'];
        	}
        	
     
   
        

        $this->startTrans();
        try{
            $result = $this->where($where)->update($data);
           
            	//再查询此条财务信息
            	$finance_params['finance_approve_id'] = $params['finance_approve_id'];
            	$finance_result = $this->getFinanceApprove($finance_params);
            	
            	
            	if( $params['approve_result']==1){
            		
            		if($finance_result[0]['finance_type']==1){//如果是应收
            		
            			$receivable_params['receivable_number'] = $finance_result[0]['finance_number'];
            			$receivable_params['receivable_voucher'] = $finance_result[0]['voucher_number'];
            			$receivable_params['voucher_time'] = $finance_result[0]['voucher_time'];
            			$receivable_params['payment_currency_id'] = $finance_result[0]['currency_id'];
            			$receivable_params['payment_type'] = $finance_result[0]['type'];
            			$receivable_params['payment_number'] = $finance_result[0]['number'];
            			$receivable_params['create_user_id'] = $finance_result[0]['create_user_id'];
            			$receivable_params['update_user_id'] = $finance_result[0]['update_user_id'];
            			$receivable_params['create_time'] = $finance_result[0]['create_time'];
            			$receivable_params['update_time'] = $finance_result[0]['update_time'];
            			$receivable_params['receivable_number'] = $finance_result[0]['finance_number'];
            			$receivable_params['payment_money'] = $finance_result[0]['money'];
            			$receivable_params['payment_stage']  = $finance_result[0]['stage'];
            			$receivable_params['payment_time']  = $finance_result[0]['payment_time'];
            			$receivable_params['receivable_info_type'] =  $finance_result[0]['receivable_info_type'];
            	
            			$receivable_params['supplier_name'] = $finance_result[0]['supplier_name'];
            	
            			$receivable_params['sn_number']  = $finance_result[0]['sn_number'];
            	
            			$receivable_params['invoice_numbe'] = $finance_result[0]['invoice_number'];
            	
            			$receivable_params['pts'] = $finance_result[0]['pts'];
            	
            			$receivable_params['exg_rate_gain'] = $finance_result[0]['exg_rate_gain'];
        
            			$receivable_params['remark'] = $finance_result[0]['remark'];
            			$receivable_params['status'] = 1;
            			$receivable_params['account_number'] = $finance_result[0]['account_number'];
            			$receivable_params['attachment'] = $finance_result[0]['attachment'];
            			$receivable_params['receipts_pay_id'] = $finance_result[0]['receipts_pay_id'];
            			$receivable_params['finance_approve_id'] = $finance_result[0]['finance_approve_id'];
            			$receivable_params['base_money'] = $finance_result[0]['base_money'];
            			$result = $receivable_info->addReceivableInfo($receivable_params);
            		}else if($finance_result[0]['finance_type']==2){//如果是应付
            			
//             			$cope_params['cope_number'] =  $finance_result[0]['finance_number'];
//             			$cope_params['cope_voucher'] =  $finance_result[0]['voucher_number'];
//             			$cope_params['voucher_time'] =  $finance_result[0]['voucher_time'];
//             			$cope_params['receivable_money'] = $finance_result[0]['money'];
//             			$cope_params['receivable_currency_id'] = $finance_result[0]['currency_id'];
//             			$cope_params['receivable_type'] =  $finance_result[0]['type'];
//             			$cope_params['receivable_number'] =  $finance_result[0]['number'];
            	
//             			$cope_params['create_time'] = $finance_result[0]['create_time'];
//             			$cope_params['update_time'] = $finance_result[0]['update_time'];
//             			$cope_params['create_user_id'] = $finance_result[0]['create_user_id'];
//             			$cope_params['update_user_id'] = $finance_result[0]['update_user_id'];
//             			$cope_params['status'] = 1;
            	
            	
//             			$result = $cope_info->addCopeInfo($cope_params);
            		}
            	
            		//再发布站内信
            		$in_station_letter_params = [
            				'system_alert_event_id'=>25,
            				'user_id'=>$finance_result[0]['create_user_id'],
            				'content'=>'您有一条审批信息，请到审批-财务审批查看'
            		];
            	
            		$this->_in_station_letter_service->addInStationLetter($in_station_letter_params);
            	
            	}else if($params['account_status']==1){
            		
            		$cope_params['cope_number'] =  $finance_result[0]['finance_number'];
            		$cope_params['cope_voucher'] =  $finance_result[0]['voucher_number'];
            		$cope_params['voucher_time'] =  $finance_result[0]['voucher_time'];
            		$cope_params['receivable_money'] = $finance_result[0]['money'];
            		$cope_params['receivable_currency_id'] = $finance_result[0]['currency_id'];
            		$cope_params['receivable_type'] =  $finance_result[0]['type'];
            		$cope_params['receivable_number'] =  $finance_result[0]['number'];
            		
            		$cope_params['create_time'] = $finance_result[0]['create_time'];
            		$cope_params['update_time'] = $finance_result[0]['update_time'];
            		$cope_params['create_user_id'] = $finance_result[0]['create_user_id'];
            		$cope_params['update_user_id'] = $finance_result[0]['update_user_id'];
            		$cope_params['status'] = 1;
            		$cope_params['attachment'] = $finance_result[0]['attachment'];
            		$cope_params['remark'] = $finance_result[0]['remark'];
            		$cope_params['receipts_pay_id'] = $finance_result[0]['receipts_pay_id'];
            		$cope_params['finance_approve_id'] = $finance_result[0]['finance_approve_id'];
            		$cope_params['base_money'] = $finance_result[0]['base_money'];
            		$result = $cope_info->addCopeInfo($cope_params);
            
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
     * 修改财务撤销审批
     */
    public function updateFinanceRevocationApprove($params){

    
    	if(is_numeric($params['finance_approve_id'])) {
    		$where['finance_approve_id'] = $params['finance_approve_id'];
    
    	}


    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];	
    	}
    	if(is_numeric($params['finance_type'])){
    		$data['finance_type'] = $params['finance_type'];
    	}
    	if(isset($params['approve_result'])){
    		$data['approve_result'] = $params['approve_result'];
    		
    	}
    	if(is_numeric($params['approve_time'])){
    		$data['approve_time'] = $params['approve_time'];
    	
    	}

    
    	
    	$this->startTrans();
    	try{
    		$result = $this->where($where)->update($data);
			if($params['finance_type']==3){//代表同意撤销 相应的实收实付取药
				
			
				if($params['base_finance_type']==1){ //代表应收
		    		$receivable_info['status'] = 0;
		    		$receivable_info['update_time'] = time();		    	 
		    		$data_where['finance_approve_id'] =$params['finance_approve_id'];
		    		
		    		$this->table('receivable_info')->where($data_where)->update($receivable_info);
		    	
				}else{
					$cope_info['status'] = 0;
					$cope_info['update_time'] = time();
					$data_where['finance_approve_id'] =$params['finance_approve_id'];
					$this->table('cope_info')->where($data_where)->update($cope_info);
				}
			}
    
    
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
     * 审批撤销
     */
    public function updateFinanceRevocation($params){
    	
    	$receivable_info = new ReceivableInfo();
    	$cope_info = new CopeInfo();
    	
    	if(is_numeric($params['finance_approve_id'])) {
    		$where['finance_approve_id'] = $params['finance_approve_id'];
    	
    	}
    	
    	$data['revocation_time'] =time();
    	
    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    	}
    	if(is_numeric($params['finance_type'])){
    		$data['finance_type'] = $params['finance_type'];
    	}
    	if(isset($params['approve_result'])){
    		$data['approve_result'] = $params['approve_result'];
    	
    	}
    	if(is_numeric($params['approve_time'])){
    		$data['approve_time'] = $params['approve_time'];
    		 
    	}
    	
    	
    	 
    	$this->startTrans();
    	try{
    		$result = $this->where($where)->update($data);
    	
    	
    	
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
    public function getFinanceApprove($params,$is_count=false,$is_page=false,$page=null,$page_size=20)
    {
    	$data = "1=1";
    
    	if($params['is_like'] == 1){
    		if(!empty($params['company_order_number'])){
    			$data.= " and finance_approve.company_order_number like '%".$params['company_order_number']."%'";
    		}
    		if(!empty($params['create_user_name'])){
    			$data.= " and user.nickname like '%".$params['create_user_name']."%'";
    			
    		}
    		if(!empty($params['team_product_number'])){
    			$data.= " and team_product.team_product_number like '%".$params['team_product_number']."%'";
    			 
    		}
    	}else{
    		if(!empty($params['company_order_number'])){
    			$data.= " and finance_approve.company_order_number = '".$params['company_order_number']."'";
    		}  
    		if(!empty($params['create_user_name'])){
    			 
    			$data.= " and user.nickname= '".$params['create_user_name']."'";
    		}
    		if(!empty($params['team_product_number'])){
    			$data.= " and team_product.team_product_number = '".$params['team_product_number']."'";
    		
    		}    		
    	}
    	

    	if(is_numeric($params['finance_type'])){
    		$data.= " and finance_approve.finance_type = ".$params['finance_type'];
    	}
    	if(is_numeric($params['receivable_info_type'])){
    		$data.= " and finance_approve.receivable_info_type = ".$params['receivable_info_type'];
    	}
    	if(is_numeric($params['company_id'])){
    		$data.= " and finance_approve.company_id = ".$params['company_id'];
    	}
    	if(is_numeric($params['finance_approve_id'])){
    		$data.= " and finance_approve.finance_approve_id = ".$params['finance_approve_id'];
    	}   	
    	if(is_numeric($params['approve_result'])){
    		 
    		$data.= " and finance_approve.approve_result = ".$params['approve_result'];
    	}
    	if(is_numeric($params['status'])){
    		 
    		$data.= " and finance_approve.status = ".$params['status'];
    	}   
    	if(is_numeric($params['receipts_pay_id'])){
    		 
    		$data.= " and finance_approve.receipts_pay_id = ".$params['receipts_pay_id'];
    	}
    	if(is_numeric($params['create_user_id'])){
    		 
    		$data.= " and finance_approve.create_user_id = ".$params['create_user_id'];
    	}   	
    	if(!empty($params['create_time'])){
    		 
    		$data.= " and FROM_UNIXTIME(finance_approve.create_time,'%Y-%m-%d') = '".$params['create_time']."'";
    	}    	
    	if(!empty($params['approve_time'])){
    		 
    		$data.= " and FROM_UNIXTIME(finance_approve.approve_time,'%Y-%m-%d') = '".$params['approve_time']."'";
    	}   	
    	
    	if($is_count==true){
			$result = $this->table("finance_approve")->alias("finance_approve")->
			join("currency currency",'currency.currency_id = finance_approve.currency_id')->
			join("user user",'user.user_id= finance_approve.create_user_id')->
			join('team_product','team_product.team_product_id = finance_approve.team_product_id','left')->
			join('supplier','supplier.supplier_id = finance_approve.supplier_id','left')->
			where($data)->order('finance_approve.create_time desc')->
			field(['finance_approve.*','currency.unit as currency_unit','currency.currency_name','user.nickname as create_user_name'])->count();
    	}else {
    	    if ($is_page == true) {
    	    	
    	    	$result = $this->table("finance_approve")->alias("finance_approve")->
    	    	join("currency currency",'currency.currency_id = finance_approve.currency_id')->
    	    	join("user user",'user.user_id= finance_approve.create_user_id')->
    	    	
    	    	join('team_product','team_product.team_product_id = finance_approve.team_product_id','left')->
    	    	join('supplier','supplier.supplier_id = finance_approve.supplier_id','left')->
    	    	where($data)->limit($page, $page_size)->order('finance_approve.create_time desc')->
    	    	field(['finance_approve.*','team_product.company_id as team_product_company_id','supplier.pay_message','supplier.account_name','supplier.bank_code','supplier.bank_number','supplier.bank_name','team_product.team_product_number','currency.unit as currency_unit','currency.currency_name','user.nickname as create_user_name'])->
    	    			 
    	    	select();
    	    
    	    }else{
				$result = $this->table("finance_approve")->alias("finance_approve")->
				join("currency currency",'currency.currency_id = finance_approve.currency_id')->
				join("user user",'user.user_id= finance_approve.create_user_id')->
				join('team_product','team_product.team_product_id = finance_approve.team_product_id','left')->
				join('supplier','supplier.supplier_id = finance_approve.supplier_id','left')->
				where($data)->order('finance_approve.create_time desc')->
				field(['finance_approve.*','team_product.company_id as team_product_company_id','supplier.pay_message','supplier.account_name','supplier.bank_code','supplier.bank_number','supplier.bank_name','team_product.team_product_number','currency.unit as currency_unit','currency.currency_name','user.nickname as create_user_name'])->
				    	
				select();
    	    }
    	    		
    	 }
    
    	
    	return $result;
    
    
    }
    
    /**
     * 修改状态
     */
    public function updateFinanceApproveStatus($params){

    	if(is_numeric($params['finance_approve_id'])) {
    		$where['finance_approve_id'] = $params['finance_approve_id'];
    	
    	}
    	if(is_numeric($params['status'])) {
    		$data['status'] = $params['status'];
    		 
    	}    	

    	$data['update_time'] =time();
    	$data['update_user_id'] =$params['now_user_id'];
    
    	$this->startTrans();
    	try{
    		$result = $this->where($where)->update($data);

    		// 提交事务
    		$this->commit();
    	
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		$this->rollback();
    	
    	}
    	return $result;    	
    }
}