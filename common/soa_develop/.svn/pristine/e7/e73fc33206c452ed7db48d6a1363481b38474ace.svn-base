<?php

namespace app\index\model\approve;
use think\Model;
use app\index\model\finance\Receivable;
use app\index\model\finance\Cope;
use app\index\service\InStationLetterService;
use app\index\model\approve\CompanyFinanceCustomer;
use app\common\help\Help;
use think\config;
use think\Db;
class CompanyFinanceApprove extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'company_finance_approve';
    private $_languageList;
	private $_in_station_letter_service;
	private $_company_finance_customer;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        $this->_in_station_letter_service = new InStationLetterService();
        $this->_company_finance_customer = new CompanyFinanceCustomer();
        parent::initialize();

    }

    /**
     *添加分公司财务审批数据
     */
    public function addCompanyFinanceApprove($params){
    	$time = time();
    	if(!empty($params['company_order_number'])){
			$data['company_order_number'] = $params['company_order_number'];
		}	
		if(is_numeric($params['source_type_id'])){
			$data['source_type_id'] = $params['source_type_id'];
		}
		
   

        $data['type'] = $params['type'];
		$data['object_company_id'] =  $params['object_company_id'];

		$data['product_name'] =  $params['product_name'];
		$data['currency_id'] = $params['currency_id'];
		$data['money'] =  $params['money'];
		if(!empty($params['remark'])){
			$data['remark'] = $params['remark'];
		}		
		if($params['type'] ==2){
			if(!empty($params['invoice_number'])){
				$data['invoice_number'] = $params['invoice_number'];
			}
			if(!empty($params['invoice_time'])){
				$data['invoice_time'] = $params['invoice_time'];
			}
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
     * 创建往来账审批
     */
    public function updateCompanyFinanceApprove($params){
		$receivable = new Receivable();
		$cope = new Cope();
        
    	$data['approve_result'] = $params['approve_result'];
    	 
        
        if(isset($params['approve_opinion'])){
        	$data['approve_opinion'] = $params['approve_opinion'];
        }
        

        
        if(is_numeric($params['company_finance_approve_id'])) {
            $where['company_finance_approve_id'] = $params['company_finance_approve_id'];

        }
        
        
        
        
        
        $data['status'] = 2;

      
        $data['approve_user_id'] = $params['now_user_id'];
        

        $data['approve_time'] = time();
        $this->startTrans();
        try{
            $this->where($where)->update($data);
			//再查询此条财务信息
			$company_finance_params['company_finance_approve_id'] = $params['company_finance_approve_id'];
			$company_finance_result = $this->getCompanyFinanceApprove($company_finance_params);
			if( $params['approve_result']==1){
				if($company_finance_result[0]['type']==1){//如果是应收
					$receivable_params['receivable_number'] = help::getNumber(5,2);
					if(!empty($company_finance_result[0]['company_order_number'])){
						$receivable_params['order_number'] = $company_finance_result[0]['company_order_number'];
					}
					
					$receivable_params['payment_object_id'] = $company_finance_result[0]['object_company_id'];
					$receivable_params['source_type_id'] = $company_finance_result[0]['source_type_id'];
					$receivable_params['product_name'] = $company_finance_result[0]['product_name'];
					$receivable_params['receivable_money'] = $company_finance_result[0]['money'];
					$receivable_params['receivable_currency_id'] = $company_finance_result[0]['currency_id'];
					$receivable_params['remark'] = $company_finance_result[0]['remark'];
					$receivable_params['company_id']=$company_finance_result[0]['company_id'];
					$receivable_params['now_user_id']=$company_finance_result[0]['create_user_id'];
					$receivable_params['is_auto'] = 1;
					$receivable_params['payment_object_type']=1;
					$receivable_params['fee_type_type']=2011;
					
					if($receivable_params['source_type_id']==1){
						$receivable_params['fee_type_code']=345;
					}else if($receivable_params['source_type_id']==2){
						$receivable_params['fee_type_code']=346;
					}else if($receivable_params['source_type_id']==3){
						$receivable_params['fee_type_code']=347;
					}else if($receivable_params['source_type_id']==4){
						$receivable_params['fee_type_code']=348;
					}else if($receivable_params['source_type_id']==5){
						$receivable_params['fee_type_code']=349;
					}else if($receivable_params['source_type_id']==6){
						$receivable_params['fee_type_code']=350;
					}else if($receivable_params['source_type_id']==7){
						$receivable_params['fee_type_code']=351;
					}else if($receivable_params['source_type_id']==8){
						$receivable_params['fee_type_code']=352;
					}else if($receivable_params['source_type_id']==9){
						$receivable_params['fee_type_code']=353;
					}else if($receivable_params['source_type_id']==10){
						$receivable_params['fee_type_code']=354;
					}else if($receivable_params['source_type_id']==11){
						$receivable_params['fee_type_code']=355;
					}else if($receivable_params['source_type_id']==12){
						$receivable_params['fee_type_code']=356;
					}else if($receivable_params['source_type_id']==13){
						$receivable_params['fee_type_code']=357;
					}else{
						$receivable_params['fee_type_code']=371;
					}
					//再获取游客信息
// 					$company_finance_customer_params = [
// 						'company_finance_approve_id'=>	$params['company_finance_approve_id']
// 					];
					
// 					$company_finance_customer_result = $this->_company_finance_customer->getCompanyFinanceCustomer($company_finance_customer_params);
// 					$company_order_customer_id = '';
// 					for($i=0;$i<count($company_finance_customer_result);$i++){
// 						if($i==0){
// 							$company_order_customer_id=$company_finance_customer_result[$i]['company_order_customer_id'];
// 						}else{
// 							$company_order_customer_id.=','.$company_finance_customer_result[$i]['company_order_customer_id'];
// 						}
// 					}
// 					$receivable_params['company_order_customer_id'] = $company_order_customer_id;
					
					$result = $receivable->addReceivable($receivable_params);
					 
			
				}else if($company_finance_result[0]['type']==2){//如果是应付
					$cope_params['cope_number'] = help::getNumber(6,2);
					if(!empty($company_finance_result[0]['company_order_number'])){
						$cope_params['order_number'] = $company_finance_result[0]['company_order_number'];
					}
					$cope_params['receivable_object_id'] = $company_finance_result[0]['object_company_id'];
					$cope_params['source_type_id'] = $company_finance_result[0]['source_type_id'];
					$cope_params['product_name'] = $company_finance_result[0]['product_name'];
					$cope_params['cope_money'] = $company_finance_result[0]['money'];
					$cope_params['cope_currency_id'] = $company_finance_result[0]['currency_id'];
					$cope_params['remark'] = $company_finance_result[0]['remark'];
					$cope_params['company_id']=$company_finance_result[0]['company_id'];
					$cope_params['now_user_id']=$company_finance_result[0]['create_user_id'];
					$cope_params['invoice_number']=$company_finance_result[0]['invoice_number'];
					$cope_params['invoice_time']=$company_finance_result[0]['invoice_time'];
					$cope_params['is_auto'] = 1;
					$cope_params['receivable_object_type']=1;
					$cope_params['fee_type_type']=2012;
						
					if($cope_params['source_type_id']==1){
						$cope_params['fee_type_code']=358;
					}else if($cope_params['source_type_id']==2){
						$cope_params['fee_type_code']=359;
					}else if($cope_params['source_type_id']==3){
						$cope_params['fee_type_code']=360;
					}else if($cope_params['source_type_id']==4){
						$cope_params['fee_type_code']=361;
					}else if($cope_params['source_type_id']==5){
						$cope_params['fee_type_code']=362;
					}else if($cope_params['source_type_id']==6){
						$cope_params['fee_type_code']=363;
					}else if($cope_params['source_type_id']==7){
						$cope_params['fee_type_code']=364;
					}else if($cope_params['source_type_id']==8){
						$cope_params['fee_type_code']=365;
					}else if($cope_params['source_type_id']==9){
						$cope_params['fee_type_code']=366;
					}else if($cope_params['source_type_id']==10){
						$cope_params['fee_type_code']=367;
					}else if($cope_params['source_type_id']==11){
						$cope_params['fee_type_code']=368;
					}else if($cope_params['source_type_id']==12){
						$cope_params['fee_type_code']=369;
					}else if($cope_params['source_type_id']==13){
						$cope_params['fee_type_code']=370;
					}else{
						$cope_params['fee_type_code']=372;
					}
					//再获取游客信息
// 					$company_finance_customer_params = [
// 							'company_finance_approve_id'=>	$params['company_finance_approve_id']
// 					];
// 					error_log(print_r($company_finance_customer_params,1));	
// 					$company_finance_customer_result = $this->_company_finance_customer->getCompanyFinanceCustomer($company_finance_customer_params);
// 					$company_order_customer_id = '';
// 					for($i=0;$i<count($company_finance_customer_result);$i++){
// 						if($i==0){
// 							$company_order_customer_id=$company_finance_customer_result[$i]['company_order_customer_id'];
// 						}else{
// 							$company_order_customer_id.=','.$company_finance_customer_result[$i]['company_order_customer_id'];
// 						}
// 					}
// 					$cope_params['company_order_customer_id'] = $company_order_customer_id;
				
					$result = $cope->addcope($cope_params);
		
				}
				
				//再发布站内信
				$in_station_letter_params = [
					'system_alert_event_id'=>27,	
					'user_id'=>$company_finance_result[0]['create_user_id'],
					'content'=>'您有一条审批信息，请到审批-往来账审批查看'	
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
    public function getCompanyFinanceApprove($params,$is_count=false,$is_page=false,$page=null,$page_size=20)
    {
    	$data = "1=1";
    
    	if(!empty($params['company_order_number'])){
    		$data.= " and company_finance_approve.company_order_number = '".$params['company_order_number']."'";
    	}
    	if(is_numeric($params['type'])){
    		$data.= " and company_finance_approve.type = ".$params['type'];
    	}
    	if(is_numeric($params['source_type_id'])){
    		$data.= " and company_finance_approve.source_type_id = ".$params['source_type_id'];
    	}
    	if(is_numeric($params['receivable_info_type'])){
    		$data.= " and company_finance_approve.receivable_info_type = ".$params['receivable_info_type'];
    	}
    	if(is_numeric($params['object_company_id'])){
    		$data.= " and company_finance_approve.object_company_id = ".$params['object_company_id'];
    	}
    	if(is_numeric($params['company_finance_approve_id'])){
    		$data.= " and company_finance_approve.company_finance_approve_id = ".$params['company_finance_approve_id'];
    	}   	
    	if(is_numeric($params['approve_result'])){
    		 
    		$data.= " and company_finance_approve.approve_result = ".$params['approve_result'];
    	}
    	if(is_numeric($params['status'])){
    		 
    		$data.= " and company_finance_approve.status = ".$params['status'];
    	}   
    	if(is_numeric($params['create_user_id'])){
    		 
    		$data.= " and company_finance_approve.create_user_id = ".$params['create_user_id'];
    	}   	
    	if(!empty($params['create_time'])){
    		 
    		$data.= " and FROM_UNIXTIME(company_finance_approve.create_time,'%Y-%m-%d') = '".$params['create_time']."'";
    	}    	
    	if(!empty($params['approve_time'])){
    		 
    		$data.= " and FROM_UNIXTIME(company_finance_approve.approve_time,'%Y-%m-%d') = '".$params['approve_time']."'";
    	}   	
    	if($is_count==true){
    			$result = $this->table("company_finance_approve")->alias("company_finance_approve")->
		    	join("currency currency",'currency.currency_id = company_finance_approve.currency_id')->
		    	join("user user",'user.user_id= company_finance_approve.create_user_id')->
		    	join("company company",'company.company_id= company_finance_approve.company_id')->
		    	where($data)->
		    	field(['company_finance_approve.*','company.company_name','currency.unit as currency_unit','currency.currency_name',
		    			"(select company_name  from company where company.company_id = company_finance_approve.object_company_id) as object_company_name",		
		    	'user.nickname as create_user_name'])->
		    	
		    	count();
    	}else {
    		if ($is_page == true) {
		    	$result = $this->table("company_finance_approve")->alias("company_finance_approve")->
		    	join("currency currency",'currency.currency_id = company_finance_approve.currency_id')->
		    	join("user user",'user.user_id= company_finance_approve.create_user_id')->
		    	join("company company",'company.company_id= company_finance_approve.company_id')->
		    	where($data)->limit($page, $page_size)->order('create_time desc')->
		    	field(['company_finance_approve.*','company.company_name','currency.unit as currency_unit','currency.currency_name',
		    			"(select company_name  from company where company.company_id = company_finance_approve.object_company_id) as object_company_name",		
		    	'user.nickname as create_user_name'])->
		    	
		    	select();
    		}else{
		    	$result = $this->table("company_finance_approve")->alias("company_finance_approve")->
		    	join("currency currency",'currency.currency_id = company_finance_approve.currency_id')->
		    	join("user user",'user.user_id= company_finance_approve.create_user_id')->
		    	join("company company",'company.company_id= company_finance_approve.company_id')->
		    	where($data)->
		    	field(['company_finance_approve.*','company.company_name','currency.unit as currency_unit','currency.currency_name',
		    			"(select company_name  from company where company.company_id = company_finance_approve.object_company_id) as object_company_name",		
		    	'user.nickname as create_user_name'])->
		    	
		    	select();
    		}
    		
    	}

    	
    	return $result;
    
    
    }
}