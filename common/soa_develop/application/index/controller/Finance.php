<?php
namespace app\index\controller;
use app\common\help\Help;
use app\common\help\Contents;
use app\index\model\branchcompany\CompanyOrder;
use app\index\model\finance\ReceiptsPay;
use app\index\model\finance\SalesReport;
use app\index\service\ProportionService;
use think\config;
use think\Model;
use think\Controller;
use app\index\model\finance\Receivable;
use app\index\model\finance\ReceivableInfo;
use app\index\model\finance\ReceivableCustomer;
use app\index\model\finance\Cope;
use app\index\model\finance\CopeInfo;
use app\index\model\finance\TravelAgencyReimbursement;
use app\index\model\finance\TravelAgencyReimbursementReceivableCustomer;
use app\index\model\finance\TravelAgencyReimbursementCope;
use app\index\model\finance\TravelAgencyReimbursementReceivable;
use app\index\model\system\Company;
use app\index\model\source\Supplier;
use app\index\model\btob\Distributor;
use app\index\model\system\Currency;
use app\index\model\finance\CompanyApportionProportion;
use app\index\model\finance\ApportionProportion;
use app\index\model\finance\ApportionProportionInfo;
use app\index\model\finance\FinanceApprove;
use app\index\service\InStationLetterService;
use app\index\service\FinacesService;
use app\index\model\approve\CompanyFinanceApprove;
use app\index\model\approve\CompanyFinanceCustomer;
class Finance extends Base
{
	private $_language;
	private $_company_apportion_proprotion;
	private $_apportion_proportion;
	private $_apportion_proportion_info;
	private $_company;
	private $_distributor;
	private $_supplier;
	private $_finance_approve;
	private $_in_station_letter_service;
	private $_finaces_serivce;
	private $_company_finance_approve;
	private $_company_finance_customer;
    //_lang Base里的属性，
    public function __construct()
    {
    	$this->_language = config("systom_setting")['language_default'];
    	$this->_company_apportion_proportion =  new CompanyApportionProportion();
    	$this->_apportion_proportion =  new ApportionProportion();
    	$this->_apportion_proportion_info =  new ApportionProportionInfo();
    	$this->_company = new Company();
    	$this->_distributor = new Distributor();
    	$this->_supplier = new Supplier();
    	$this->_finance_approve = new FinanceApprove();
    	$this->_in_station_letter_service = new InStationLetterService();
    	$this->_finaces_serivce = new FinacesService();
    	$this->_company_finance_approve = new CompanyFinanceApprove();
    	$this->_company_finance_customer = new CompanyFinanceCustomer();
        parent::__construct();
    }

    /***
    * 获取发团毛利统计
    * Hugh
    */
    public function getGrossProfitStatistics(){
       $params = $this->input(); 
		$receivable = new Receivable();
    

       $cope = new Cope();
       if(isset($params['page'])){

            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $cope->grossProfitStatistics($params, true);
            $result = $cope->grossProfitStatistics($params,false,'true',$page,$page_size);
            
//             //再走个汇率换算
            
//             for($i=0;$i<count($result);$i++){
// 			//通过团队产品查找所有应收
// 			$receivable_money = 0;
// 			$receivable_params = [
// 				'team_product_id'=>$result[0]['team_product_id'],
// 				'status'=>1,
// 				'company_id'=>$result[0]['company_id']	
					
// 			];
// 			$receivable->getReceivable($receivable_params);
            	
//             //通过团队产品查找所有应付	
            	
//             }
//             /**
//              * 
//                    '(select sum(cope.cope_money) from cope where cope.team_product_id=team_product.team_product_id and cope.status=1 and cope.company_id = team_product.company_id) as cope_money',
//                     '(select sum(cope_info.receivable_money) from  cope_info   JOIN cope on cope.cope_number=cope_info.cope_number where cope.team_product_number=team_product.team_product_number and cope.status=1 and cope_info.status=1) as cope_receivable_money',
//                     '(select sum(receivable.receivable_money) from receivable where receivable.team_product_id=team_product.team_product_id and receivable.status=1 and receivable.company_id = team_product.company_id) as receivable_money',
//                     '(select sum(receivable_info.payment_money) from receivable_info RIGHT JOIN receivable on receivable.receivable_number=receivable_info.receivable_number where receivable.team_product_number=team_product.team_product_number and receivable.status=1 and receivable_info.status=1) as payment_money'])->select();
             
//              * 
//              * 
//              */
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];
            return $this->output($data);
        }
       $result = $cope->grossProfitStatistics($params);
       $this->outPut($result);
    }

    /****
    *  获取成本报表列表
    *  Hugh
    */
    public function getCostSheet(){
       $params = $this->input(); 

       $cope = new Cope();
       $result = $cope->costSheet($params);

       //var_dump($result);exit;
       $this->outPut($result); 
    }


    /**
     * 添加应收
     * 王
     */
    public function addReceivable(){
    	$params = $this->input();
 
    	$paramRule = [
			'payment_company_id'=>'number',
            'order_number'=>'string',
   		    'product_type'=>'number',
    		'product_name'=>'string',
    		'currency_id'=>'number',
            'receivable_money'=>'number',
    		

    	
    	];
    	$this->paramCheckRule($paramRule,$params);
    	
    	$receivable = new Receivable();
    	$params['receivable_number'] = Help::getNumber(5);
    	$receivable_result = $receivable->addReceivable($params);
    	//有了应收编号后把用户插入数据库
    	$receivable_customer = new ReceivableCustomer();
    	$receivable_customer_result = $receivable_customer->addReceivableCustomer($params);
    	$this->outPut($receivable_result);
    }
	
    //添加往来账审批
    public function addCompanyFinanceApprove(){
    	$params = $this->input();    	
    	$paramRule = [
    
    		'type'=>'int',	
    		'object_company_id'=>'number',
 
    		'product_name'=>'string',
    		'currency_id'=>'number',
    		'money'=>'number',
    	

    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    	$company_finance_approve_id = $this->_company_finance_approve->addCompanyFinanceApprove($params);
    	
//     	$params['company_finance_approve_id'] = $company_finance_approve_id;
//     	if($params['type']==1){
//     		$this->_company_finance_customer->addCompanyFinanceCustomer($params);
//     	}
    	
    	$this->outPut($company_finance_approve_id);
    }
    
    
    /**
     * 获取应收
     * 王
     */
    public function getReceivable(){
    	$params = $this->input();
        $receivable = new Receivable(); 
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $receivable->getReceivable($params, true);
            $receivable_result = $receivable->getReceivable($params,false,'true',$page,$page_size);
        }else{
            $receivable_result = $receivable->getReceivable($params);
        }
        $receivable_customer = new ReceivableCustomer();
        //获取应收的顾客
        for($i=0;$i<count($receivable_result);$i++){
        	$receivable_customer_params['receivable_number'] = $receivable_result[$i]['receivable_number'];
        	$receivable_customer_params['status']=1;
        	$receivable_result[$i]['customer_info'] = $receivable_customer->getReceivableCustomer($receivable_customer_params);
        	//开始做判断
        	if($receivable_result[$i]['payment_object_type'] == 1){
        		$company_params['company_id'] = $receivable_result[$i]['payment_object_id'];
        		$company_result = $this->_company->getCompany($company_params);
        		$receivable_result[$i]['payment_object_name'] = $company_result[0]['company_name'];
        	}else if($receivable_result[$i]['payment_object_type'] == 2){
        		$supplier_params['supplier_id'] = $receivable_result[$i]['payment_object_id'];
        		$supplier_result = $this->_supplier->getSupplier($supplier_params);
        		$receivable_result[$i]['payment_object_name'] = $supplier_result[0]['supplier_name'];
        	}else if($receivable_result[$i]['payment_object_type'] == 3){
        		$distributor_params['distributor_id'] = $receivable_result[$i]['payment_object_id'];
        		
        		$distributor_result = $this->_distributor->getDistributor($distributor_params);
        		$receivable_result[$i]['payment_object_name'] = $distributor_result[0]['distributor_name'];
        	}else{
        		$receivable_result[$i]['payment_object_name'] ='直客';
        	}
        	$get_fee_type_params = [
        		'fee_type_code'=>$receivable_result[$i]['fee_type_code']	
        	];
        	$fee_type_name = Help::getFeeType($get_fee_type_params);
        	$receivable_result[$i]['fee_type_name'] = $fee_type_name['fee_type_name'];
        }
        if(isset($params['page'])) {
            $data = [
                'count'      => $count,
                'list'       => $receivable_result,
                'page_count' => ceil($count / $page_size)
            ];
            return $this->output($data);
        }
    	$this->outPut($receivable_result);
    }
    /**
     * 修改应收
     * 王
     */
    public function updateReceivableByReceivableNumber(){
        $params = $this->input();

        $paramRule = [

            'receivable_number'=>'string',
            //'user_id'=>'number',

        ];
        $this->paramCheckRule($paramRule,$params);
        $receivable = new Receivable();
        $receivable_result = $receivable->updateReceivableByReceivableNumber($params);
        //有了应收编号后把用户插入数据库
        $receivable_customer = new ReceivableCustomer();
        $params['key'] = 1;
        $receivable_customer_result = $receivable_customer->updateReceivableCustomer($params);
        
        //修改应付
        //通过应收编号获取
        $receivable_info_result = $receivable->getReceivable(['receivable_number'=>$params['receivable_number']]);
        
        //修改应付
        $cope = new Cope();
        $cope_params = [
        	'public_number'=>$receivable_info_result[0]['public_number'],
        	'cope_money'=>$params['receivable_money']	
        ];
        $cope->updateCopeByPublicNumber($cope_params);
        
        
        $this->outPut($receivable_result);

    }
    /**
     * 添加批量应收
     * 王
     */
    public function addReceivableInfo(){
        $params = $this->input();

        $paramRule = [

     		
      
         
        	'receivable_info'=>'array'	

        ];
        $this->paramCheckRule($paramRule,$params);
        $receivable_info = new ReceivableInfo();
        $params['payment_number'] = Help::getNumber(200,2);
        $receivable_info_result = $receivable_info->addReceivableInfo($params);
        
        $receivable_info_array = $params['receivable_info'];
        //读取实收列表
        
   		$receivable = new Receivable();
   		
   		
   		
   	
        
        

        if(is_numeric($params['receipts_pay_id'])){
        	$receipts_pay = new ReceiptsPay();
        	$receipts_pay_params = [
        			'receipts_pay_id'=>$params['receipts_pay_id']
        	];
        	$receipts_pay_result = $receipts_pay->getReceiptsPay($receipts_pay_params);
        	
        	$params['voucher_number'] = $receipts_pay_result[0]['voucher_number'];
        	$params['voucher_time'] = $receipts_pay_result[0]['voucher_time'];
        	$params['payment_type'] = $receipts_pay_result[0]['pay_type'];
        	$params['remark'] = $receipts_pay_result[0]['remark'];
        	$params['attachment'] = $receipts_pay_result[0]['attachment'];

        }
    
       // exit();
        for($i=0;$i<count($receivable_info_array);$i++){
        	$finance_approve_data['finance_type'] = 1;   
        	$finance_approve_data['receivable_info_type'] = 1;
        	$finance_approve_data['receivable_number'] = $receivable_info_array[$i]['receivable_number'];
        	
        	//
        	$receivable_params['receivable_number'] = $receivable_info_array[$i]['receivable_number'];
        	$receivable_result = $receivable->getReceivable($receivable_params);
        	
        	$finance_approve_data['order_number'] = $receivable_result[0]['order_number'];
        	$finance_approve_data['voucher_number'] = $params['voucher_number'];
        	$finance_approve_data['voucher_time'] = $params['voucher_time'];
        	$finance_approve_data['account_number'] = $params['receivable_voucher'];
        	$finance_approve_data['payment_money'] = $receivable_info_array[$i]['payment_money'];
        	$finance_approve_data['payment_currency_id'] =  $receivable_result[0]['receivable_currency_id'];
        	$finance_approve_data['payment_number'] = $params['payment_number'];
        	$finance_approve_data['payment_type'] = $params['payment_type'];
        	$finance_approve_data['now_user_id'] = $params['now_user_id'];   
        	$finance_approve_data['invoice_number'] = help::getNumber(202,1).str_pad(count($r)+1,5,"0",STR_PAD_LEFT)."-0A";
        	$finance_approve_data['remark'] = $params['remark'];
        	$finance_approve_data['attachment'] = $params['attachment'];
        	$finance_approve_data['user_company_id'] = $params['user_company_id'];
        	$finance_approve_data['base_money'] = $receivable_info_array[$i]['base_money'];
        	$finance_approve_data['receipts_pay_id'] = $receipts_pay_result[0]['receipts_pay_id'];
        	$this->_finance_approve->addFinanceApprove($finance_approve_data);
        	
        	//再发布站内信
        	$in_station_letter_params = [
        			'system_alert_event_id'=>25,
        			'role_id'=>5,
        			'company_id'=>$params['user_company_id'],
        			'content'=>'您有一条审批信息，请到审批-财务审批查看'
        	];
        	
        	$this->_in_station_letter_service->addInStationLetter($in_station_letter_params);
        }
        
    
        $this->outPut(1);
    }
    /**
     * 获取应收详细
     * 
     */
    public function getReceivableInfo(){
    
        $params = $this->input();
        
      
        $receivable_info = new ReceivableInfo();

        $receivable_info_result = $receivable_info->getReceivableInfo($params);
        
     	for($i=0;$i<count($receivable_info_result);$i++){
     		$receivable_info_result[$i]['is_finance_approve'] = 1;
     	}
      	
        if($params['receivable_info_type'] ==2){//代表销售收款 则需要 未审批的数据
        	
        	//这里查询审批中的销售收款
        	$finance_approve_params['company_order_number'] = $params['company_order_number'];
        	$finance_approve_params['finance_type'] = 1;
        	$finance_approve_params['receivable_info_type'] = 2;
        	$finance_approve_params['status'] = 1;
        	$finance_approve_result = $this->_finance_approve->getFinanceApprove($finance_approve_params);
        	$data_result = [];
        
        	for($i=0;$i<count($finance_approve_result);$i++){
        	
        		$data = [
        			'company_order_number'=>$finance_approve_result[$i]['company_order_number'],	
        			'payment_currency_id'=>$finance_approve_result[$i]['currency_id'],
        			'payment_currency_name'=>$finance_approve_result[$i]['currency_name'],
        			'payment_type'=>$finance_approve_result[$i]['type'],       			
        			'payment_money'=>$finance_approve_result[$i]['money'],
        			'payment_number'=>$finance_approve_result[$i]['number'],
        			'payment_stage'=>$finance_approve_result[$i]['stage'],       				
        			'receivable_info_type'=>2,
        			'receivable_number'=>$finance_approve_result[$i]['finance_number'],
        			'create_user_name'=>$finance_approve_result[$i]['create_user_name'],
        			'is_finance_approve'=>2,
        			'finance_approve_id'=>$finance_approve_result[$i]['finance_approve_id'],
        			'receivable_voucher'=>$finance_approve_result[$i]['voucher_number'],
        			'voucher_time'=>$finance_approve_result[$i]['voucher_time'],
        			'remark'=>$finance_approve_result[$i]['remark'],
        			'attachment'=>$finance_approve_result[$i]['attachment'],
        			'sn_number'=>$finance_approve_result[$i]['sn_number'],
        			'exg_rate_gain'=>$finance_approve_result[$i]['exg_rate_gain'],
        			'supplier_name'=>$finance_approve_result[$i]['supplier_name']
        				
        				
        		];
        	
        		$data_result[] = $data;
        	}
        
        	$finance_approve_params['company_order_number'] = $params['company_order_number'];
        	$finance_approve_params['finance_type'] = 1;
        	$finance_approve_params['receivable_info_type'] = 2;
        	$finance_approve_params['status'] = 2;
        	$finance_approve_params['approve_result'] = 2;

        	$finance_approve_result = $this->_finance_approve->getFinanceApprove($finance_approve_params);
      
        	for($i=0;$i<count($finance_approve_result);$i++){
        		$data = [
        				'company_order_number'=>$finance_approve_result[$i]['company_order_number'],
        				'payment_currency_id'=>$finance_approve_result[$i]['currency_id'],
        				'payment_currency_name'=>$finance_approve_result[$i]['currency_name'],
        				'payment_type'=>$finance_approve_result[$i]['type'],
        				'payment_money'=>$finance_approve_result[$i]['money'],
        				'payment_number'=>$finance_approve_result[$i]['number'],
        				'payment_stage'=>$finance_approve_result[$i]['stage'],
        				'receivable_info_type'=>2,
        				'receivable_number'=>$finance_approve_result[$i]['finance_number'],
        				'create_user_name'=>$finance_approve_result[$i]['create_user_name'],
        				'is_finance_approve'=>3,
        				'finance_approve_id'=>$finance_approve_result[$i]['finance_approve_id'],
	        			'receivable_voucher'=>$finance_approve_result[$i]['voucher_number'],
	        			'voucher_time'=>$finance_approve_result[$i]['voucher_time'],
	        			'remark'=>$finance_approve_result[$i]['remark'],
	        			'attachment'=>$finance_approve_result[$i]['attachment'],
        				'sn_number'=>$finance_approve_result[$i]['sn_number'],
        				'exg_rate_gain'=>$finance_approve_result[$i]['exg_rate_gain'],
        				'supplier_name'=>$finance_approve_result[$i]['supplier_name']
        	
        		];
        		$data_result[] = $data;
        	}
        	
        	if(!empty($data_result)){
        		$receivable_info_result = array_merge($receivable_info_result,$data_result);
        		//$receivable_info_result[] =;
        	}
        	
        	
        	
        	
        }
        $this->outPut($receivable_info_result);
    }
	
    //获取订单的应付列表
    public function getCopeInfo(){
    	$params = $this->input();
    	
    	
    	$cope_info = new CopeInfo();
    	$cope = new Cope();
    	$cope_info_result = $cope_info->getCopeInfo($params);
    	
    	
    	
    	
    	for($i=0;$i<count($cope_info_result);$i++){
    		$cope_info_result[$i]['is_finance_approve'] = 1;
    	}
    	 
    	if($params['company_order'] ==1){//代表订单里的应付数据
    		 
    		//这里查询审批中的应付
    		$finance_approve_params['company_order_number'] = $params['company_order_number'];
    		$finance_approve_params['finance_type'] = 2;
    	
    		$finance_approve_params['status'] = 1;
    		$finance_approve_result = $this->_finance_approve->getFinanceApprove($finance_approve_params);
    		$data_result = [];
    	
    		for($i=0;$i<count($finance_approve_result);$i++){
    			 //去查询数据
    			$cope_params =[
    			 	'cope_number'=>$finance_approve_result[$i]['finance_number']	
    			];
    			$cope_result = $cope->getCope($cope_params);
    			$data = [
    					'receivable_money'=>$finance_approve_result[$i]['money'],
    					'receivable_currency_id'=>$finance_approve_result[$i]['currency_id'],
    					'cope_voucher'=>$finance_approve_result[$i]['voucher_number'],
    					'voucher_time'=>$finance_approve_result[$i]['voucher_time'],
    					'receivable_type'=>$finance_approve_result[$i]['type'],
    					'remark'=>$finance_approve_result[$i]['remark'],
    					'attachment'=>$finance_approve_result[$i]['attachment'],
    					'unit'=>$finance_approve_result[$i]['currency_unit'],
    					'create_user_name'=>$finance_approve_result[$i]['create_user_name'],
						'receivable_object_type'=>$cope_result[0]['receivable_object_type'],
    					'receivable_object_id'=>$cope_result[0]['receivable_object_id'],
    					'is_finance_approve'=>2,
    					'finance_approve_id'=>$finance_approve_result[$i]['finance_approve_id'],
    					'team_product_id'=>$finance_approve_result[$i]['team_product_id']
    	
    			];
    			 
    			$data_result[] = $data;
    		}
    	
    		$finance_approve_params['company_order_number'] = $params['company_order_number'];
    		$finance_approve_params['finance_type'] = 2;
    	
    		$finance_approve_params['status'] = 2;
    		$finance_approve_params['approve_result'] = 2;
    	
    		$finance_approve_result = $this->_finance_approve->getFinanceApprove($finance_approve_params);
    	
    		for($i=0;$i<count($finance_approve_result);$i++){
    			$data = [
    					'receivable_money'=>$finance_approve_result[$i]['money'],
    					'receivable_currency_id'=>$finance_approve_result[$i]['currency_id'],
    					'cope_voucher'=>$finance_approve_result[$i]['voucher_number'],
    					'voucher_time'=>$finance_approve_result[$i]['voucher_time'],
    					'receivable_type'=>$finance_approve_result[$i]['type'],
    					'remark'=>$finance_approve_result[$i]['remark'],
    					'attachment'=>$finance_approve_result[$i]['attachment'],
    					'unit'=>$finance_approve_result[$i]['currency_unit'],
    					'create_user_name'=>$finance_approve_result[$i]['create_user_name'],
						'receivable_object_type'=>$cope_result[0]['receivable_object_type'],
    					'receivable_object_id'=>$cope_result[0]['receivable_object_id'],
    					'is_finance_approve'=>2,
    					'finance_approve_id'=>$finance_approve_result[$i]['finance_approve_id'],
    					'team_product_id'=>$finance_approve_result[$i]['team_product_id']
    					 
    			];
    			$data_result[] = $data;
    		}
    		 
    		if(!empty($data_result)){
    			$cope_info_result = array_merge($cope_info_result,$data_result);
    			//$receivable_info_result[] =;
    		}
    		 
    		 
    		 
    		 
    	}
    	$cope_result = $cope_info_result;
    	for($i=0;$i<count($cope_result);$i++){
    		if($cope_result[$i]['receivable_object_type'] == 1){
    			$company_params['company_id'] = $cope_result[$i]['receivable_object_id'];
    			$company_result = $this->_company->getCompany($company_params);
    			$cope_result[$i]['receivable_object_name'] = $company_result[0]['company_name'];
    		}else if($cope_result[$i]['receivable_object_type'] == 2){
    			$supplier_params['supplier_id'] = $cope_result[$i]['receivable_object_id'];
    			 
    			$supplier_result = $this->_supplier->getSupplier($supplier_params);
    		
    			$cope_result[$i]['receivable_object_name'] = $supplier_result[0]['supplier_name'];
    		}else if($cope_result[$i]['receivable_object_type'] == 3){
    			$distributor_params['distributor_id'] = $cope_result[$i]['receivable_object_id'];
    			$distributor_result = $this->_distributor->getDistributor($distributor_params);
    		
    			$cope_result[$i]['receivable_object_name'] = $distributor_result[0]['distributor_name'];
    		}else{
    			 
    			$cope_result[$i]['receivable_object_name'] ='直客';
    		}    		
    		
    	}
    	$cope_info_result = $cope_result;
    	$this->outPut($cope_info_result);    	
    	
    }
    
	/**
	 * 查询应收的顾客通过应收编号
	 */
    public function getReceivableCustomerByReceivableNumber(){
    	$params = $this->input(); 	
    	$paramRule = [
    			'receivable_number'=>'string',    	
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$receibable = new ReceivableCustomer();
    	
    	$receibable_result = $receibable->getReceivableCustomer($params);
    	$this->outPut($receibable_result);
    	
    	
    }
    /**
     * 修改应收详情根据应收详情ID
     */
    public function  updateReceivableInfoByReceivableInfoId(){
    	$params = $this->input();
    	$paramRule = [
    
    			'receivable_info_id'=>'number',
    			'payment_stage'=>'number',
    			'payment_currency_id'=>'number',
    			'payment_money'=>'string',
    			'payment_type'=>'number'
    
    	];
    	$this->paramCheckRule($paramRule,$params);
    
    	$receivable_info = new ReceivableInfo();
    	$receivable_info_result = $receivable_info->updateReceivableInfoByReceivableInfoId($params);
    	$this->outPut($receivable_info_result);
    }
    //通过团队产品编号查询以及分公司ID查询
    
    /**
     * 添加应付
     */
    public function addCope(){
    	
    	$params = $this->input();
    	$paramRule = [
    		'receivable_company_id'=>'number',    			
    		'cope_currency_id'=>'number',    		
    		'product_type'=>'number',
    		'product_name'=>'string',
    		'cope_money'=>'number',
    		'status'=>'number',
    		'user_id'=>'number'
    	];

    	$this->paramCheckRule($paramRule,$params);    	
    	$cope =new Cope();
    	$params['cope_number'] = help::getNumber(6);
    	$result = $cope->addCope($params);
    	$this->outPut($result);
    	
    }
    
    /**
     * 获取应付
     */
    public function getCope(){
        $params = $this->input();
        $cope = new Cope();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $cope->getCope($params, true);
            $cope_result = $cope->getCope($params,false,'true',$page,$page_size);
        }else{
            $cope_result = $cope->getCope($params);
        }
       
        for($i=0;$i<count($cope_result);$i++){
        
        	if($cope_result[$i]['receivable_object_type'] == 1){
        		$company_params['company_id'] = $cope_result[$i]['receivable_object_id'];
        		$company_result = $this->_company->getCompany($company_params);
        		$cope_result[$i]['receivable_object_name'] = $company_result[0]['company_name'];
        	}else if($cope_result[$i]['receivable_object_type'] == 2){
        		$supplier_params['supplier_id'] = $cope_result[$i]['receivable_object_id'];
        	
        		$supplier_result = $this->_supplier->getSupplier($supplier_params);
        		
        		$cope_result[$i]['receivable_object_name'] = $supplier_result[0]['supplier_name'];
        	}else if($cope_result[$i]['receivable_object_type'] == 3){
        		$distributor_params['distributor_id'] = $cope_result[$i]['receivable_object_id'];
        		$distributor_result = $this->_distributor->getDistributor($distributor_params);
        
        		$cope_result[$i]['receivable_object_name'] = $distributor_result[0]['distributor_name'];
        	}else{
        	
        		$cope_result[$i]['receivable_object_name'] ='直客';
        	}      
        	$get_fee_type_params = [
        		'fee_type_code'=>$cope_result[$i]['fee_type_code']
        	];
        	 
        	$fee_type_name = Help::getFeeType($get_fee_type_params);
        	$cope_result[$i]['fee_type_name'] = $fee_type_name['fee_type_name'];
        }
        
     
        
        if(isset($params['page'])) {
            $data = [
                'count'      => $count,
                'list'       => $cope_result,
                'page_count' => ceil($count / $page_size)
            ];
            return $this->output($data);
        }

        $this->outPut($cope_result);
    }
    /**
     * 修改应付
     * 王
     */
    public function updateCopeByCopeNumber(){
        $params = $this->input();
        $paramRule = [

            'cope_number'=>'string',
            'user_id'=>'number',

        ];
        $this->paramCheckRule($paramRule,$params);
        $cope = new Cope();
        $cope_result = $cope->updateCopeByCopeNumber($params);
        $this->outPut($cope_result);

    }

    /**
     * 修改应付批量
     * 王
     */
    public function updateCopeInfoByCopeNumber(){
        $params = $this->input();
        $paramRule = [

            'cope_number'=>'string',
            'user_id'=>'number',

        ];
        $this->paramCheckRule($paramRule,$params);
        $cope_info = new CopeInfo();
        $cope_info_result = $cope_info->updateCopeInfoByCopeNumber($params);
        $this->outPut($cope_info_result);

    }
    
    /**
     * 修改应收顾客
     */
    public function updateReceivableCustomer(){
    	$params = $this->input();
    	$paramRule = [
    	
    		'receivable_number'=>'string',
    		
    	
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$receivable_customer = new ReceivableCustomer();
    	$receivable_customer_result = $receivable_customer->updateReceivableCustomer($params);

    	$this->outPut($receivable_customer_result);

    	
    }
    /**
     * 添加批量添加应付
     * 王
     */
    public function addCopeInfo(){
    	$params = $this->input();
    
    	$paramRule = [
    			 


    	
    		'cope_info'=>'array'
    
    
    
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$cope_info = new CopeInfo();
    	$cope = new Cope();
    	$params['payment_number'] = Help::getNumber(200,2);
    	$cope_info_array =$params['cope_info'];
    	if(is_numeric($params['receipts_pay_id'])){
    		$receipts_pay = new ReceiptsPay();
    		$receipts_pay_params = [
    				'receipts_pay_id'=>$params['receipts_pay_id']
    		];
    		$receipts_pay_result = $receipts_pay->getReceiptsPay($receipts_pay_params);
    		 
    		$params['voucher_number'] = $receipts_pay_result[0]['voucher_number'];
    		$params['voucher_time'] = $receipts_pay_result[0]['voucher_time'];
    		$params['payment_type'] = $receipts_pay_result[0]['pay_type'];
    		$params['remark'] = $receipts_pay_result[0]['remark'];
    		$params['attachment'] = $receipts_pay_result[0]['attachment'];
    	
    	}
    	for($i=0;$i<count($cope_info_array);$i++){
    		$finance_approve_data['finance_type'] = 2;
    		$finance_approve_data['cope_number'] = $cope_info_array[$i]['cope_number'];
    		 
    		//
    		$cope_params['cope_number'] = $cope_info_array[$i]['cope_number'];
    		$cope_result = $cope->getCope($cope_params);
    		
    		$finance_approve_data['order_number'] = $cope_result[0]['order_number'];
    		$finance_approve_data['voucher_number'] = $params['voucher_number'];
    		$finance_approve_data['voucher_time'] = $params['voucher_time'];
    		$finance_approve_data['remark'] = $params['remark'];
    		$finance_approve_data['attachment'] = $params['attachment'];
    		
    		$finance_approve_data['payment_money'] = $cope_info_array[$i]['payment_money'];
    		$finance_approve_data['payment_currency_id'] =  $cope_result[0]['cope_currency_id'];
    		$finance_approve_data['payment_number'] = $params['payment_number'];
    		$finance_approve_data['payment_type'] = $params['payment_type'];
    		$finance_approve_data['now_user_id'] = $params['now_user_id'];
    		$finance_approve_data['user_company_id'] = $params['user_company_id'];
    		$finance_approve_data['base_money'] = $cope_info_array[$i]['base_money'];
    		$finance_approve_data['receipts_pay_id'] = $receipts_pay_result[0]['receipts_pay_id'];
    		$this->_finance_approve->addFinanceApprove($finance_approve_data);
    		//再发布站内信
    		$in_station_letter_params = [
    				'system_alert_event_id'=>25,
    				'role_id'=>5,
    				'company_id'=>$params['user_company_id'],
    				'content'=>'您有一条审批信息，请到审批-财务审批查看'
    		];
    		 
    		$this->_in_station_letter_service->addInStationLetter($in_station_letter_params);
    	
    	}
    	
    	//$cope_info_result = $cope_info->addCopeInfo($params);
    	$this->outPut(1);
    }    
    /**
     * 添加地接报账
     */
    public function addTravelAgencyReimbursement(){
    	$params = $this->input();
    	$paramRule = [
    			 
    		'team_product_id'=>'int',
	 
    	];
   		
    	$this->paramCheckRule($paramRule,$params);
    	

    	$traval_agency_receivable =  new TravelAgencyReimbursement();
   
    	$team_product_id = $params['team_product_id'];
    	$team_product_id_arr = explode(',',$team_product_id);

    	for($i=0;$i<count($team_product_id_arr);$i++){
    		$params['travel_number'] = Help::getNumber(9,2);
    		$params['team_product_id'] = $team_product_id_arr[$i];
    		$result = $traval_agency_receivable->addTravelAgencyReimbursement($params);
    	}

    	
    	$this->outPut($result);
    	
    	
    }
    /**
     * 修改地接报账
     */
    public function updateTravelAgencyReimbursementByTravelAgencyReimbursementNumber(){
    	$params = $this->input();
    	$paramRule = [   
    		'travel_number'=>'string'
    	];    	
    	$this->paramCheckRule($paramRule,$params);
    	
    
    	$traval_agency_receivable =  new TravelAgencyReimbursement();
    
    	$result = $traval_agency_receivable->updateTravelAgencyReimbursement($params);
    	
    	$this->outPut($result);
    	 
    	 
    }    
    /**
     * 获取地接报账
     */
    public function getTravelAgencyReimbursement(){
    	$params = $this->input();
    	$traval_agency_reimbursement =  new TravelAgencyReimbursement();
    	$traval_agency_reimbursement_cope =new  TravelAgencyReimbursementCope();
    	$traval_agency_reimbursement_receivable =new  TravelAgencyReimbursementReceivable();
    	$traval_agency_reimbursement_receivable_customer = new TravelAgencyReimbursementReceivableCustomer();
		
        if(isset($params['page'])){

            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
       
            $count = $traval_agency_reimbursement->getTravelAgencyReimbursement($params, true);

            $result = $traval_agency_reimbursement->getTravelAgencyReimbursement($params,false,'true',$page,$page_size);


        }else{
            $result = $traval_agency_reimbursement->getTravelAgencyReimbursement($params);
        }
        
    	//拿到数据后循环获取应收与应付
    	for($i=0;$i<count($result);$i++){
    		$travel_agency_reimbursement_number = $result[$i]['travel_agency_reimbursement_number'];
    		$result[$i]['receivable_info'] = [];
    		$result_receivable = [];
    		$result_cope = [];
    		$receivable_customer = [];
    		$receivable_info_array = [];
    		$data = [
    			'status'=>1,
    			'travel_agency_reimbursement_number'=>$travel_agency_reimbursement_number	
    		];
    		$result_cope[] = $traval_agency_reimbursement_cope->getTravelAgencyReimbursementCope($data);
    		$result[$i]['cope_info']= $result_cope[0];
    		$result_receivable[] = $traval_agency_reimbursement_receivable->getTravelAgencyReimbursementReceivable($data);
    		$result[$i]['receivable_info']= $result_receivable[0];
    		$result[$i]['receivable_info']['customer_info'] = [];
    		//通过receivable_info数据获取游客信息
    		$receivable_info_array = $result[$i]['receivable_info'];
    	
    		for($j=0;$j<count($receivable_info_array);$j++){
    			
    			$travel_agency_reimbursement_receivable_customer_params['travel_agency_reimbursement_receivable_id'] = $receivable_info_array[$j]['travel_agency_reimbursement_receivable_id'];
     			$receivable_customer[]= $traval_agency_reimbursement_receivable_customer->getTravelAgencyReimbursementReceivableCustomer($travel_agency_reimbursement_receivable_customer_params);
     			$receivable_info_array[$j]['customer_info'] = $receivable_customer[0];
    		}
    	}
        if(isset($params['page'])) {
            $data = [
                'count'      => $count,
                'list'       => $result,
                'page_count' => ceil($count / $page_size)
            ];
            return $this->output($data);
        }
    	$this->outPut($result);
    	
    }
    
    /**
     * 获取分摊列表
     */
    
    public function getApportionProportionList(){
    	$params = $this->input();
    	$paramRule = [
    		'year'=>'number'
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$list = [];
    	//开始循环 
    	
    	for($i=1;$i<13;$i++){
    		if($i<10){
    			$k='0'.$i;
    			
    		}else{
    			$k=$i;
    		}
    		$apportion_proportion_params = [
    			'status'=>1,
    			'company_id'=>$params['company_id'],
    			'year'=>$params['year'],
    			'month'=>$i
    		];
    		//开始 查询 
    		$list[$i] = $this->_apportion_proportion->getApportionProportion($apportion_proportion_params);
    	}
    	$this->outPut($list);
    	
    }
    
    
    //获取费用分摊比率
    public function getCompanyApportionProportion(){
		//首先获取所有公司
    	$company = new Company();
    	$company_params['status'] = 1;
    	$company_result  = $company->getCompany($company_params);
    	
    	//循环 去寻找比率有则返回，没返回0
    	for($i=0;$i<count($company_result);$i++){
    		$company_result_params['company_id'] = $company_result[$i]['company_id'];
    		$company_apportion_proportion_result = $this->_company_apportion_proportion->getCompanyApportionProportion($company_result_params);
    	
    		if($company_apportion_proportion_result[0]['apportion_proportion']>0){
    			$company_result[$i]['apportion_proportion'] =  $company_apportion_proportion_result[0]['apportion_proportion'];
    		}else{
    			$company_result[$i]['apportion_proportion'] =  0;
    		}
    	}
    	$this->outPut($company_result);    	
    	
    }
    
    //设置费用分摊比率
    public function updateCompanyApportionProportion(){
    	$params = $this->input();
    	$paramRule = [
    		'company_apportion_proportion_array'=>'array'
    	];
    	$this->paramCheckRule($paramRule,$params);
  
    	$company_apportion_proportion_result  = $this->_company_apportion_proportion->updateApportionProportion($params);
    	$this->outPut($company_apportion_proportion_result);
    }
    /**
     * 添加分摊
     */
    public function addApportionProportion(){
    	
    	
    	$params = $this->input();
    	$paramRule = [
    		'project_name'=>'string',
    		'total_money'=>'srring',
    		'year'=>'string',
    		'month'=>'string',	
    		'apportion_proportion_array'=>'array'
    	];
    	$this->paramCheckRule($paramRule,$params);
    	
    	$result = $this->_apportion_proportion->addApportionProportin($params);
    	$this->outPut($result);
    	
    }
    
    /**
     * 查询分摊
     */
    public function getApportionProportion(){
    	$params = $this->input();
    	$paramRule = [
    		'year'=>'string',
    		'month'=>'string'	

    	];
    	$this->paramCheckRule($paramRule,$params);    	
    	$apportion_proportion_result =  $this->_apportion_proportion->getApportionProportion($params);
    	$all_money = 0;//合计
    	for($i=0;$i<count($apportion_proportion_result);$i++){
     		$apportion_proportion_info_params = [
     			'apportion_proportion_id'=>$apportion_proportion_result[$i]['apportion_proportion_id']	
     		];
    		$apportion_proportion_result[$i]['apportion_proportion_info'] = [];
    	
    		$apportion_proportion_result[$i]['apportion_proportion_info'] = $this->_apportion_proportion_info->getApportionProportionInfo($apportion_proportion_info_params);
    		$all_money+=$apportion_proportion_result[$i]['total_money'];
    	}
    	if(count($apportion_proportion_result)>0){
    		$apportion_proportion_result[0]['all_money'] = $all_money;
    	}
    	
    	$this->outPut($apportion_proportion_result);
    	
    }
    /**
     * 修改分摊
     */
    public function updateApportionProportionByApportionProportionId(){
    	$params = $this->input();
    	$paramRule = [
			'apportion_proportion_id'=>'string'

    	];
    	$this->paramCheckRule($paramRule,$params);
    	$apportion_proportion_result =  $this->_apportion_proportion->updateApportionProportionByApportionProportionId($params);

    	$this->outPut($apportion_proportion_result);
    	 
    }

    /**
 * 获取销售报表
 */
    public function getSalesReport(){
        $params = $this->input();
        $paramRule = [

        ];
        $this->paramCheckRule($paramRule,$params);
        $sales_report = new SalesReport();

        $sales_report_number_result = $sales_report->getSalesReport($params);

        $this->outPut($sales_report_number_result);
    }

    /**
     * 获取团队产品销售报表
     */
    public function getSalesTeamReport(){
        $params = $this->input();
        $paramRule = [

        ];
        $this->paramCheckRule($paramRule,$params);
        $sales_report = new SalesReport();

        $result = $sales_report->getSalesTeamReport($params);

        $this->outPut($result);
    }

    public function getSalesReportAgent(){
        $params = $this->input();
        $paramRule = [

        ];
        $this->paramCheckRule($paramRule,$params);
        $sales_report = new SalesReport();

        $result = $sales_report->getSalesReportAgent($params);

        $this->outPut($result);
    }

    /**
     * 修改receivable_info的字段状态
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/4/26
     * Time: 10:51
     */
    public function updateStatus()
    {
        $params = $this->input();
        $this->paramCheckRule(['receivable_info_id' => 'number'],$params);
        $receivable_info = new ReceivableInfo();
        $result = $receivable_info->updateReceivableInfoByReceivableInfoId($params);;
        $result === 1 ? $this->outPut($result) : $this->outPutError($result);
    }
    
    /**
     * 提交财务审批意见
     */
    public function postFinanceApprove(){
    	$params = $this->input();
    	$paramRule = [
    		'finance_approve_id'=>'int',
    		'approve_result'=>'int'	

    	
    	];
    	$this->paramCheckRule($paramRule,$params);

    	//首先修改
    	//首先先判断是否需要修改
    	$finance_params['finance_approve_id'] = $params['finance_approve_id'];
    	$finance_result = $this->_finance_approve->getFinanceApprove($finance_params);
    	if($finance_result[0]['status']== 2){
    		$this->outPutError(['msg'=>'data is Checked'], $params);
    		exit();
    	}
    	$params['finance_type'] = $finance_result[0]['finance_type'];
    	$result = $this->_finance_approve->updateFinanceApprove($params);
    	$this->outPut($result);
    }
    //提交撤销申请
    public function postRevocationApply(){
    	$params = $this->input();
    	$paramRule = [
    		'finance_approve_id'=>'int',

    	];
    	
    
    	$this->paramCheckRule($paramRule,$params);
    	//首先获取撤销这条数据的信息	
    	$finance_approve_params = [
    		'finance_approve_id'=>$params['finance_approve_id']	
    	];
    	$finance_approve_result= $this->_finance_approve->getFinanceApprove($finance_approve_params);
    	
    	if($finance_approve_result[0]['status']==1){//代表审核中 科室直接撤销
    		
    		$finance_revocation_params = [
    			'finance_approve_id'=>$params['finance_approve_id'],
    			'finance_type'=>3,
    			'status'=>2,
    			'approve_result'=>2	
    		];
    		$result = $this->_finance_approve->updateFinanceRevocationApprove($finance_revocation_params);
    	}else{ //已经审核通过的 需要审批
    		$finance_revocation_params = [
    				'finance_approve_id'=>$params['finance_approve_id'],
    				'finance_type'=>3,
    				'status'=>1,
    				'approve_result'=>''
    		];
    		$result = $this->_finance_approve->updateFinanceRevocationApprove($finance_revocation_params);
    		
    	}
    	

    	$this->outPut($result);
    	
    	
    }
    
    /**
     * 审批撤销
     */
    public function postFinanceRevocation(){
    	$params = $this->input();
    	$paramRule = [
    			'finance_approve_id'=>'int',
    	
    	];  	
    	$this->paramCheckRule($paramRule,$params);
    	
    	//首先获取该审核资料
    	$finance_approve_params = [
    		'finance_approve_id'=>$params['finance_approve_id']
    	];
    	$finance_approve_result= $this->_finance_approve->getFinanceApprove($finance_approve_params);
    	 
    	//获取编号
    	$finance_number = $finance_approve_result[0]['finance_number'];
    	$finance_number = substr($finance_number,0,2);
    	 
    	if($finance_number=='RC'){
    		$finance_type = 1;
    	}else{
    		$finance_type =2;
    	}
    	
    	if($params['approve_result']==1){//同意撤销
    		$finance_revocation_params = [
    			'finance_approve_id'=>$params['finance_approve_id'],
    			'finance_type'=>3,
    			'status'=>2,
    			'approve_result'=>1,
    			'base_finance_type'=>$finance_type
    		];
    		$result = $this->_finance_approve->updateFinanceRevocationApprove($finance_revocation_params);
    	}else{//不同意撤销
    		
    		$finance_revocation_params = [
    				'finance_approve_id'=>$params['finance_approve_id'],
    				'finance_type'=>$finance_type,
    				'status'=>2,
    				'approve_result'=>1
    		];
    		
    		
    	
    		$result = $this->_finance_approve->updateFinanceRevocationApprove($finance_revocation_params);
    		
    		
    	}
    
    	$this->outPut($result);
    	
    }
    
    /**
     * 提交确认应付
     */
    public function updateFinanceCopeApprove(){
    	$params = $this->input();
    	$paramRule = [
    		'finance_approve_id'=>'int',
    		
    
   
    	];
    	$this->paramCheckRule($paramRule,$params);
    	 
    	//首先修改
    	//首先先判断是否需要修改
    	$finance_params['finance_approve_id'] = $params['finance_approve_id'];
	
    	$result = $this->_finance_approve->updateFinanceApprove($params);
    	$this->outPut($result);
    }  
    
    /**
     * 修改财务管理
     */
    public function updateFinanceApprove(){
    	$params = $this->input();
    	$paramRule = [
    		'finance_approve_id'=>'int',
 
    	];
    	$this->paramCheckRule($paramRule,$params);
    	

    	
    	$result = $this->_finance_approve->updateFinanceApproveStatus($params);
    	$this->outPut($result);
    	
    }
    
    /**
     * 获取审批
     */
    public function getFinanceApprove(){
    	$params = $this->input();
    	$receivable = new Receivable();
    	$cope = new Cope();
    	if(isset($params['page'])){
    		$page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
    		$page = ($params['page']-1)*$page_size;
    		$count = $this->_finance_approve->getFinanceApprove($params, true);
    		$result = $this->_finance_approve->getFinanceApprove($params,false,'true',$page,$page_size);
    		
    		for($i=0;$i<count($result);$i++){//循环读取项目名称
    			//获取编号
    			$finance_number = $result[$i]['finance_number'];
    			$finance_number = substr($finance_number,0,2);
   
    			if($finance_number=='RC'){
    				$finance_type = 1;
    			}else{
    				$finance_type =2;
    			}
    		
    			if($finance_type==1){//为应收
    				$finance_params['receivable_number'] = $result[$i]['finance_number'];
    				$receivable_result = $receivable->getReceivable($finance_params);
    				$result[$i]['product_name'] = $receivable_result[0]['product_name'];
    				$type = $receivable_result[0]['fee_type_code'];
    				 
    			}else{
    				$finance_params['cope_number'] = $result[$i]['finance_number'];
    				$cope_result = $cope->getCope($finance_params);
    				$result[$i]['product_name'] = $cope_result[0]['product_name'];
    				$type = $cope_result[0]['fee_type_code'];
    				$result[$i]['source_type_id'] = $cope_result[0]['source_type_id'];
    			}
    			$get_fee_type_params = [
    					'fee_type_code'=>$type
    			];
    			$fee_type_name = Help::getFeeType($get_fee_type_params);
    			$result[$i]['fee_type_name'] = $fee_type_name['fee_type_name'];
    			//help::getFeeType($params)
    		}
    		$data = [
    				'count'=>$count,
    				'list'=>$result,
    				'page_count'=>ceil($count/$page_size)
    		];
    		 
    		return $this->output($data);
    	}else{
    		$result =$this->_finance_approve->getFinanceApprove();
    	}
    	
    	
    	
    	for($i=0;$i<count($result);$i++){//循环读取项目名称
    	   	//获取编号
    		$finance_number = $result[$i]['finance_number'];
    		$finance_number = substr($finance_number,0,2);
   
    		if($finance_number=='RC'){
    			$finance_type = 1;
    		}else{
    			$finance_type =2;
    			
    			
    			

    			
    			
    			
    		}
    		if($finance_type==1){//为应收
    			$finance_params['receivable_number'] = $result[$i]['finance_number'];
    			$receivable_result = $receivable->getReceivable($finance_params);
    			$result[$i]['product_name'] = $receivable_result[0]['product_name'];
    			$type = $receivable_result[0]['fee_type_code'];
    			 
    		}else{
    			$finance_params['cope_number'] = $result[$i]['finance_number'];
    			$cope_result = $cope->getCope($finance_params);
    			$result[$i]['product_name'] = $cope_result[0]['product_name'];
    			$type = $cope_result[0]['fee_type_code'];
    		}
    		$get_fee_type_params = [
    				'fee_type_code'=>$type
    		];
    		$fee_type_name = Help::getFeeType($get_fee_type_params);
    		$result[$i]['fee_type_name'] = $fee_type_name['fee_type_name'];
    		//help::getFeeType($params)
    	}
    	
    	
    	
    	
    	

    	
    	$this->outPut($result);
    }
    /**
     * 获取往来账审批
     */
    public function getCompanyFinanceApprove(){
    	$params = $this->input();
   	
    	
    	if(isset($params['page'])){
    		$page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
    		$page = ($params['page']-1)*$page_size;
    		$count = $this->_company_finance_approve->getCompanyFinanceApprove($params, true);
    		$result =$this->_company_finance_approve->getCompanyFinanceApprove($params,false,'true',$page,$page_size);
    		$data = [
    				'count'=>$count,
    				'list'=>$result,
    				'page_count'=>ceil($count/$page_size)
    		];
    	
    		return $this->output($data);
    	}else{
    		$result =$this->_company_finance_approve->getCompanyFinanceApprove($params,false);
    	}

    	 
    	$this->outPut($result);
    }   
    /**
     * 提交往来账审批意见
     */
    public function postCompanyFinanceApprove(){
    	$params = $this->input();
    	$paramRule = [
    		'company_finance_approve_id'=>'int',
    		'approve_result'=>'int'
    
   
    	];
    	$this->paramCheckRule($paramRule,$params);
    	 
    	//首先修改
    	//首先先判断是否需要修改
    	$company_finance_params['company_finance_approve_id'] = $params['company_finance_approve_id'];
    	$company_finance_result = $this->_company_finance_approve->getCompanyFinanceApprove($company_finance_params);
    	if($company_finance_result[0]['status']== 2){
    		$this->outPutError(['msg'=>'data is Checked'], $params);
    		exit();
    	}
    	$result = $this->_company_finance_approve->updateCompanyFinanceApprove($params);
    	$this->outPut($result);
    }
    
    /**
     * 网站订单收满金额
     */
    
    public function websiteOrderReceivableFull(){
    	$params = $this->input();
    	
    	$result = $this->_finaces_serivce->websiteOrderReceivableFull($params);
    		
    	$this->outPut($result);
    		
    		
	}

    /**
     * 代理商统计
     */
	public function agentStatistics()
    {
        $params = $this->input();

        $order_model = new CompanyOrder();
        $list = $order_model->getCompanyOrder($params);

        $receivable_model = new Receivable();
        $receivable_info_model = new ReceivableInfo();
        $proportion_service = new ProportionService();
        foreach ($list as $k=>$v)
        {
            $receivable_money_count = 0.00;            //应收
            $payment_money_count = 0.00;               //实收

            //获取应付
            $receivable_result = $receivable_model->getReceivable(['order_number' => $v['company_order_number'], 'status'=>1]);
            foreach ($receivable_result as $receivable_v)
            {
                if ($receivable_v['receivable_currency_id'] != $params['company_currency_id'])
                {
                    //获取应收汇率
                    $proportion1 = $proportion_service->getProportion($receivable_v['receivable_currency_id'],$params['company_currency_id']);
                    $receivable_v['receivable_money'] = number_format($receivable_v['receivable_money'] * $proportion1,2);
                }
                //应收count
                $receivable_money_count = (float) $receivable_v['receivable_money'] + (float) $receivable_money_count;

                //获取实付
                $receivable_info_result = $receivable_info_model->getReceivableInfo(['receivable_number' => $receivable_v['receivable_number'], 'status'=>1]);

                foreach ($receivable_info_result as $receivable_info_v)
                {
                    if ($receivable_info_v['payment_currency_id'] != $params['company_currency_id'])
                    {
                        //获取实收汇率
                        $proportion2 = $proportion_service->getProportion($receivable_info_v['payment_currency_id'],$params['company_currency_id']);
                        $receivable_info_v['receivable_money'] = number_format($receivable_info_v['payment_money'] * $proportion2,2);
                    }
                    //实收count
                    $payment_money_count = (float) $receivable_info_v['payment_money'] + (float) $payment_money_count;
                }
            }

            $list[$k]['receivable_money_count'] = $receivable_money_count;            //应收
            $list[$k]['payment_money_count'] = $payment_money_count;               //实收
        }


        if(isset($params['page'])){
            $page_size =  isset($params['limit']) ? $params['limit'] : Contents::PAGE_SIZE;

            $count = $order_model->getCompanyOrder($params, true);

            $data = [
                'count' => $count,
                'list' => $list,
                'page_count'=>ceil($count/$page_size)
            ];

        }

        $this->outPut($data);
    }
    
    //获取实收列表
    public function getReceiptsPay(){
    	$params = $this->input();
    	
    	$receipts_pay = new ReceiptsPay();
    	$company = new Company();
    	$supplier = new Supplier();
    	$distributor = new Distributor();
    	$receivable_info = new ReceivableInfo();
    	$cope_info = new CopeInfo();
    	$currency = new Currency();
    	if(isset($params['page'])){
    		$page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
    		$page = ($params['page']-1)*$page_size;
    		$count = $receipts_pay->getReceiptsPay($params, true);
    		$result =$receipts_pay->getReceiptsPay($params,false,'true',$page,$page_size);
    	
    		for($i=0;$i<count($result);$i++){
    			
    			if($result[$i]['object_type']==1){//代表公司
    				$company_params = [
    						'company_id'=>$result[$i]['object_id']
    				];
    				$company_result = $company->getCompany($company_params);
    				$result[$i]['object_name']=$company_result[0]['company_name'];
    			}else if($result[$i]['object_type']==2){//代表供应商
    				$supplier_params = [
    						'supplier_id'=>$result[$i]['object_id']
    				];
    				$supplier_result = $supplier->getSupplier($supplier_params);
    				$result[$i]['object_name']=$supplier_result[0]['supplier_name'];
    				
    				$result[$i]['bank_code']=$supplier_result[0]['bank_code'];
    				$result[$i]['account_name']=$supplier_result[0]['account_name'];
    				$result[$i]['bank_name']=$supplier_result[0]['bank_name'];
    				$result[$i]['bank_number']=$supplier_result[0]['bank_number'];
    				
    				
    				
    				
    			}else if($result[$i]['object_type']==3){//代表渠道商
    				
    				
    				
    				$distributor_params = [
    						'distributor_id'=>$result[$i]['object_id']
    				];
    				$distributor_result = $distributor->getDistributor($distributor_params);
    				
    			
    				$result[$i]['object_name']=$distributor_result[0]['distributor_name'];
    				$result[$i]['object_code']=$distributor_result[0]['distributor_code'];
    			}else{
    				$result[$i]['object_name']='直客';
    				 
    			}
    			
    			$currency_params=[
    				'currency_id'=>$result[$i]['result_currency_id']	
    			];
    			$currency_result = $currency->getCurrency($currency_params);
    			
    			$result[$i]['result_currency_unit']=$currency_result[0]['unit'];
    	
    			//开始获取余额
    			if($result[$i]['type']==1){
    				$receipts_pay_params = [
    					'receipts_pay_id'=>	$result[$i]['receipts_pay_id']	
    				];
    		
    				$receipts_pay_result = $receivable_info->getReceivableInfoByReceiptsPayId($receipts_pay_params);
    				
    				$result[$i]['balance_money'] = $result[$i]['base_money']-$receipts_pay_result[0]['true_receivable'];
    	
    			}else{
    				$receipts_pay_params = [
    					'receipts_pay_id'=>	$result[$i]['receipts_pay_id']
    				];
    				$receipts_pay_result = $cope_info->getCopeInfoByReceiptsPayId($receipts_pay_params);
    				
    				$result[$i]['balance_money'] = $result[$i]['base_money']-$receipts_pay_result[0]['true_cope'];
    				
    			}
    			
    			
    		}
    	
    		$data = [
    				'count'=>$count,
    				'list'=>$result,
    				'page_count'=>ceil($count/$page_size)
    		];
    		return $this->output($data);
    	}else{
    		$result =$receipts_pay->getReceiptsPay($params,false);
    	}
    	
    	for($i=0;$i<count($result);$i++){
    		if($result[$i]['object_type']==1){//代表公司
    			$company_params = [
    				'company_id'=>$result[$i]['object_id']	
    			];
    			$company_result = $company->getCompany($company_params);
    			$result[$i]['object_name']=$company_result[0]['company_name'];
    		}else if($result[$i]['object_type']==2){//代表供应商
    			$supplier_params = [
    				'supplier_id'=>$result[$i]['object_id']
    			];
    			$supplier_result = $supplier->getSupplier($supplier_params);
    			$result[$i]['object_name']=$supplier_result[0]['supplier_name'];
    		}else if($result[$i]['object_type']==3){//代表渠道商
    			$distributor_params = [
    				'distributor_id'=>$result[$i]['object_id']
    			];
    			$distributor_result = $distributor->getDistributor($distributor_params);
    			$result[$i]['object_name']=$distributor_result[0]['distributor_name'];
    			$result[$i]['object_code']=$distributor_result[0]['distributor_code'];
    		}else{
    			$result[$i]['object_name']='直客';
    			
    		}
    		$currency_params=[
    				'currency_id'=>$result[$i]['result_currency_id']
    		];
    		$currency_result = $currency->getCurrency($currency_params);
    		 
    		$result[$i]['result_currency_unit']=$currency_result[0]['unit'];
    		//开始获取余额
    		if($result[$i]['type']==1){
    			$receipts_pay_params = [
    					'receipts_pay_id'=>	$result[$i]['receipts_pay_id']
    			];
    		
    			$receipts_pay_result = $receivable_info->getReceivableInfoByReceiptsPayId($receipts_pay_params);
    			
    			$result[$i]['balance_money'] = $result[$i]['base_money']-$receipts_pay_result[0]['true_receivable'];
    		}else{
    			$receipts_pay_params = [
    					'receipts_pay_id'=>	$result[$i]['receipts_pay_id']
    			];
    			$receipts_pay_result = $cope_info->getCopeInfoByReceiptsPayId($receipts_pay_params);
    		
    			$result[$i]['balance_money'] =  $result[$i]['base_money']-$receipts_pay_result[0]['true_cope'];
    		
    		}
    	}
    	$this->outPut($result);    	
    	
    }
    /**
     * 添加实收列表
     */
    public function addReceiptsPay(){
    	$params = $this->input();
    	$paramRule = [
    		'object_type'=>'int',
    		'base_currency_id'=>'int',
    		'base_money'=>'int'	
    	
  
    	];
    	$this->paramCheckRule($paramRule,$params);
  		if($params['payment_number'] == 1){
  			$params['payment_number'] = help::getNumber(201,2);
  		}else{
  			unset($params['payment_number']);
  		}
    	
    	

    	$receipts_pay = new ReceiptsPay();
		
    	$result =$receipts_pay->addReceiptsPay($params);
    	
    	$this->outPut($result);
    	
    }
}
