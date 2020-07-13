<?php
namespace app\index\controller;
use app\common\help\Help;
use app\index\model\branchcompany\CompanyOrderAnnex;
use app\index\model\branchcompany\CompanyOrderComment;
use app\index\model\branchcompany\CustomerSource;
use app\index\model\branchcompany\OrderPayRecord;
use app\index\model\source\Supplier;
use app\index\model\system\Currency;
use app\index\model\system\Tax;
use app\index\service\ReceivableInfoService;
use think\config;
use app\index\model\system\User;
use app\index\model\btob\Distributor;
use app\index\model\branchcompany\Customer;
use app\index\model\branchcompany\BranchProduct;
use app\index\model\branchcompany\BranchProductType;
use app\index\model\branchcompany\BranchProductSource;
use app\index\model\branchcompany\BranchProductTeam;
use app\index\model\branchcompany\BranchProductRouteTemplate;
use app\index\model\branchcompany\CompanyOrder;
use app\index\model\branchcompany\CompanyOrderApprove;
use app\index\model\branchcompany\CompanyOrderCustomer;
use app\index\model\branchcompany\CompanyOrderCustomerlineup;
use app\index\model\branchcompany\CompanyOrderFlight;
use app\index\model\branchcompany\CompanyOrderAccommodation;
use app\index\model\branchcompany\CompanyOrderProduct;
use app\index\model\branchcompany\CompanyOrderProductSource;
use app\index\model\branchcompany\CompanyOrderProductTeam;
use app\index\model\branchcompany\CompanyOrderProductTemplate;
use app\index\model\branchcompany\CompanyOrderProductDiy;
use app\index\model\branchcompany\CompanyOrderRelation;
use app\index\model\branchcompany\CompanyOrderCustomerGuideReceiptFile;
use app\index\model\finance\ReceivableInfo;
use app\index\model\source\TourGuide;
use app\index\model\source\Vehicle;
use app\index\model\source\ScenicSpot;
use app\index\model\source\Visa;
use app\index\model\source\Cruise;
use app\index\model\source\Flight;
use app\index\model\source\Dining;
use app\index\model\source\Hotel;
use app\index\model\source\SingleSource;
use app\index\model\source\OwnExpense;
use app\index\model\system\BillTemplate;
use app\index\service\CompanyOrderService;
use app\index\service\CompanyOrderCustomerService;
use app\index\service\ProportionService;
use app\index\model\product\TeamProductJourney;
use app\index\model\product\TeamProduct;
use app\index\model\product\TeamProductAllocation;
use app\index\model\product\TeamProductReturnReceipt;
use app\index\model\finance\Receivable;
use app\index\model\finance\Cope;
use app\index\service\SourceService;
use app\index\service\BranchProductService;
use app\index\model\system\Company;
use app\index\model\product\RouteTemplate;
use app\index\model\finance\FinanceApprove;
use app\index\model\system\CurrencyProportion;
use app\index\service\InStationLetterService;
use think\Model;
use think\Controller;
use app\common\help\Contents;

class Queue 
{
	private $_language;
	private $_hotel;
	private $_dining;
	private $_flight;
	private $_cruise;
	private $_visa;
	private $_scenic_spot;
	private $_vehicle;
	private $_tour_guide;
	private $_single_source;
	private $_own_expense;
	private $_branch_product;
	private $_branch_product_team;
	private $_branch_product_source;
	private $_branch_product_type;
	private $_branch_product_route_template;
	private $_company_order;
	private $_company_order_approve;
	private $_company_order_service;
	private $_company_order_product_team;
	private $_company_order_product_template;
	private $_company_order_product_source;
	private $_company_order_product_diy;
	private $_company_order_relation;
	private $_company_order_customer_lineup;
	private $_team_product_journey;
	private $_team_product;
	private $_team_product_allocation;
	private $_company_order_customer;
	private $_company_order_product;
	private $_company_order_flight;
	private $_team_product_return_receipt;
	private $_distributor;
	private $_receivable;
	private $_receivable_info;
	private $_bill_template;
	private $_company_order_customer_service;
	private $_user;
	private $_proportion_service;
	private $_cope;
	private $_source_service;
	private $_branch_product_service;
	private $_company;
	private $_route_template;
	private $_currency;
	private $_finance_approve;
	private $_in_station_letter_service;
    //_lang Base里的属性，
    public function __construct()
    {
    	$this->_language = config("systom_setting")['language_default'];
    	$this->_hotel = new Hotel();
    	$this->_dining = new Dining();
    	$this->_flight = new Flight();
    	$this->_cruise = new Cruise();
    	$this->_visa = new Visa();
    	$this->_scenic_spot = new ScenicSpot();
    	$this->_vehicle = new Vehicle();
    	$this->_tour_guide = new TourGuide();
    	$this->_single_source = new SingleSource();
    	$this->_own_expense = new OwnExpense();
    	$this->_branch_product = new BranchProduct();
    	$this->_branch_product_team = new BranchProductTeam();
    	$this->_branch_product_source = new BranchProductSource();
    	$this->_branch_product_route_template = new BranchProductRouteTemplate();
    	$this->_branch_product_type = new BranchProductType();
    	$this->_company_order = new CompanyOrder();
    	$this->_company_order_approve = new CompanyOrderApprove();
    	$this->_company_order_service = new CompanyOrderService();
    	$this->_company_order_customer_service = new CompanyOrderCustomerService();
    	$this->_company_order_product_team = new CompanyOrderProductTeam();
    	$this->_company_order_product_template = new CompanyOrderProductTemplate();
    	$this->_company_order_customer = new CompanyOrderCustomer();
    	$this->_company_order_flight = new CompanyOrderFlight();
    	$this->_team_product_journey = new TeamProductJourney();
    	$this->_team_product = new TeamProduct();
    	$this->_team_product_allocation = new TeamProductAllocation();
    	$this->_company_order_product = new CompanyOrderProduct();
    	$this->_company_order_product_source = new CompanyOrderProductSource();
    	$this->_company_order_product_diy = new CompanyOrderProductDiy();
    	$this->_company_order_relation = new CompanyOrderRelation();
    	$this->_team_product_return_receipt = new TeamProductReturnReceipt();
    	$this->_company_order_customer_lineup =new CompanyOrderCustomerlineup();
    	$this->_distributor = new Distributor();
    	$this->_receivable_info = new ReceivableInfo();
    	$this->_receivable = new Receivable();
    	$this->_cope = new Cope();
    	$this->_bill_template = new BillTemplate();
    	$this->_user = new User();
    	$this->_proportion_service = new ProportionService();
    	$this->_source_service = new  SourceService();
    	$this->_branch_product_service = new BranchProductService();
    	$this->_company = new Company();
    	$this->_route_template = new RouteTemplate();
    	$this->_currency = new Currency();
    	$this->_finance_approve = new FinanceApprove();
    	$this->_in_station_letter_service = new InStationLetterService();
       
    }
    

	public function autoPostCompanyOrderSubstribe(){
		
		$params = [
			'status'=>1,
			'utc_substribe_time'=>date('YmdH')	
				
		];
		$company_order_array = [];
		//公司订单分公司产品
		$company_order_result = $this->_company_order_product->getCompanyOrderProduct($params);
	
		
		for($i=0;$i<count($company_order_result);$i++){
			//首先先判断订单是否在数组 中 这样可以不用去查询数据库
			$company_order_number = $company_order_result[$i]['company_order_number'];
			
			if(!in_array($company_order_number,$company_order_array)){
				//如果不在查询订单相关信息
				$company_order_number_params = [
					'company_order_number'=>$company_order_number	
				];
				$company_result = $this->_company_order->getCompanyOrder($company_order_number_params);
				$user_params=[
					'user_id'=>$company_result[0]['create_user_id']	
				];
				$create_user_result = $this->_user->getUser($user_params);
				if($company_result[0]['create_user_id'] == $company_result[0]['update_user_id']){
					$update_user_result = $create_user_result;
				}else{
					$user_params=[
						'user_id'=>$company_result[0]['update_user_id']
					];
					$update_user_result = $this->_user->getUser($user_params);
				}
				//有了创建人和修改人ID后去查询相应的邮箱
				
				$company_order_array["$company_order_number"] = [
					'create_user_id'=>	$company_result[0]['create_user_id'],
					'update_user_id'=> 	$company_result[0]['update_user_id'],
					'create_user_email'=>$create_user_result[0]['email'],
					'update_user_email'=>$update_user_result[0]['email'],	
						
						
				];

				
			}
			//开始发送站内信
			
			$content = $company_order_number.$company_order_result[$i]['branch_product_name']." reminding time is up/已到提醒时间";
			
			$letter_params = [
					'system_alert_event_id'=>28,
					'user_id'=>$company_order_array["$company_order_number"]['create_user_id'],
					'content'=>$content,
					'url'=>'/branchcompany/companyOrderManage?company_order_number='.$company_order_number,
					'status'=>1
			
			];
			
			$this->_in_station_letter_service->addInStationLetter($letter_params);
			
			$create_user_email = $company_order_array["$company_order_number"]['create_user_email'];
			$update_user_email = $company_order_array["$company_order_number"]['update_user_email'];
			if(!empty($create_user_email)){
				$email_params = [
						'to_email'=>$company_order_array["$company_order_number"]['create_user_email'],
						'content'=>	$content,
						'subject'=>'提醒/reminding'
							
				];
				help::sendOperationsEmail($email_params);
			}
			if(!empty($update_user_email) && ($create_user_email!=$update_user_email)){
				$email_params = [
						'to_email'=>$company_order_array["$company_order_number"]['update_user_email'],
						'content'=>	$content,
						'subject'=>'提醒/reminding'
				
				];
				help::sendOperationsEmail($email_params);				
			}

		}
		
		
		//搜索团队产品
		$company_order_result = $this->_company_order_product_team->getCompanyOrderProductTeam($params);
		
	
		for($i=0;$i<count($company_order_result);$i++){
			//首先先判断订单是否在数组 中 这样可以不用去查询数据库
			$company_order_number = $company_order_result[$i]['company_order_number'];
				
			if(!in_array($company_order_number,$company_order_array)){
				//如果不在查询订单相关信息
				$company_order_number_params = [
						'company_order_number'=>$company_order_number
				];
				$company_result = $this->_company_order->getCompanyOrder($company_order_number_params);
				$user_params=[
						'user_id'=>$company_result[0]['create_user_id']
				];
				$create_user_result = $this->_user->getUser($user_params);
				if($company_result[0]['create_user_id'] == $company_result[0]['update_user_id']){
					$update_user_result = $create_user_result;
				}else{
					$user_params=[
							'user_id'=>$company_result[0]['update_user_id']
					];
					$update_user_result = $this->_user->getUser($user_params);
				}
				//有了创建人和修改人ID后去查询相应的邮箱
		
				$company_order_array["$company_order_number"] = [
						'create_user_id'=>	$company_result[0]['create_user_id'],
						'update_user_id'=> 	$company_result[0]['update_user_id'],
						'create_user_email'=>$create_user_result[0]['email'],
						'update_user_email'=>$update_user_result[0]['email'],
		
		
				];
		
		
			}
			//开始发送站内信
				
			$content = $company_order_number.$company_order_result[$i]['team_product_name']." reminding time is up/已到提醒时间";
				
			$letter_params = [
					'system_alert_event_id'=>28,
					'user_id'=>$company_order_array["$company_order_number"]['create_user_id'],
					'content'=>$content,
					'url'=>'/branchcompany/companyOrderManage?company_order_number='.$company_order_number,
					'status'=>1
						
			];
				
			$this->_in_station_letter_service->addInStationLetter($letter_params);
				
			$create_user_email = $company_order_array["$company_order_number"]['create_user_email'];
			$update_user_email = $company_order_array["$company_order_number"]['update_user_email'];
			if(!empty($create_user_email)){
				$email_params = [
						'to_email'=>$company_order_array["$company_order_number"]['create_user_email'],
						'content'=>	$content,
						'subject'=>'提醒/reminding'
				
				];
				help::sendOperationsEmail($email_params);
			}
			if(!empty($update_user_email) && ($create_user_email!=$update_user_email)){
				$email_params = [
						'to_email'=>$company_order_array["$company_order_number"]['update_user_email'],
						'content'=>	$content,
						'subject'=>'提醒/reminding'
		
				];
				help::sendOperationsEmail($email_params);
			}
		
		}
				
		//搜索资源
		$company_order_result = $this->_company_order_product_source->getCompanyOrderProductSource($params);
		
		
		for($i=0;$i<count($company_order_result);$i++){
			//首先先判断订单是否在数组 中 这样可以不用去查询数据库
			$company_order_number = $company_order_result[$i]['company_order_number'];
		
			if(!in_array($company_order_number,$company_order_array)){
				//如果不在查询订单相关信息
				$company_order_number_params = [
						'company_order_number'=>$company_order_number
				];
				$company_result = $this->_company_order->getCompanyOrder($company_order_number_params);
				$user_params=[
						'user_id'=>$company_result[0]['create_user_id']
				];
				$create_user_result = $this->_user->getUser($user_params);
				if($company_result[0]['create_user_id'] == $company_result[0]['update_user_id']){
					$update_user_result = $create_user_result;
				}else{
					$user_params=[
							'user_id'=>$company_result[0]['update_user_id']
					];
					$update_user_result = $this->_user->getUser($user_params);
				}
				//有了创建人和修改人ID后去查询相应的邮箱
		
				$company_order_array["$company_order_number"] = [
						'create_user_id'=>	$company_result[0]['create_user_id'],
						'update_user_id'=> 	$company_result[0]['update_user_id'],
						'create_user_email'=>$create_user_result[0]['email'],
						'update_user_email'=>$update_user_result[0]['email'],
		
		
				];
		
		
			}
			//开始发送站内信
		
			$content = $company_order_number.$company_order_result[$i]['source_name']." reminding time is up/已到提醒时间";
		
			$letter_params = [
					'system_alert_event_id'=>28,
					'user_id'=>$company_order_array["$company_order_number"]['create_user_id'],
					'content'=>$content,
					'url'=>'/branchcompany/companyOrderManage?company_order_number='.$company_order_number,
					'status'=>1
		
			];
		
			$this->_in_station_letter_service->addInStationLetter($letter_params);
		
			$create_user_email = $company_order_array["$company_order_number"]['create_user_email'];
			$update_user_email = $company_order_array["$company_order_number"]['update_user_email'];
			if(!empty($create_user_email)){
				$email_params = [
						'to_email'=>$company_order_array["$company_order_number"]['create_user_email'],
						'content'=>	$content,
						'subject'=>'提醒/reminding'
		
				];
				help::sendOperationsEmail($email_params);
			}
			if(!empty($update_user_email) && ($create_user_email!=$update_user_email)){
				$email_params = [
						'to_email'=>$company_order_array["$company_order_number"]['update_user_email'],
						'content'=>	$content,
						'subject'=>'提醒/reminding'
		
				];
				help::sendOperationsEmail($email_params);
			}
		
		}		
		//搜索自定义
		$company_order_result = $this->_company_order_product_diy->getCompanyOrderProductDiy($params);
		
		
		for($i=0;$i<count($company_order_result);$i++){
			//首先先判断订单是否在数组 中 这样可以不用去查询数据库
			$company_order_number = $company_order_result[$i]['company_order_number'];
		
			if(!in_array($company_order_number,$company_order_array)){
				//如果不在查询订单相关信息
				$company_order_number_params = [
						'company_order_number'=>$company_order_number
				];
				$company_result = $this->_company_order->getCompanyOrder($company_order_number_params);
				$user_params=[
						'user_id'=>$company_result[0]['create_user_id']
				];
				$create_user_result = $this->_user->getUser($user_params);
				if($company_result[0]['create_user_id'] == $company_result[0]['update_user_id']){
					$update_user_result = $create_user_result;
				}else{
					$user_params=[
							'user_id'=>$company_result[0]['update_user_id']
					];
					$update_user_result = $this->_user->getUser($user_params);
				}
				//有了创建人和修改人ID后去查询相应的邮箱
		
				$company_order_array["$company_order_number"] = [
						'create_user_id'=>	$company_result[0]['create_user_id'],
						'update_user_id'=> 	$company_result[0]['update_user_id'],
						'create_user_email'=>$create_user_result[0]['email'],
						'update_user_email'=>$update_user_result[0]['email'],
		
		
				];
		
		
			}
			//开始发送站内信
		
			$content = $company_order_number.$company_order_result[$i]['diy_name']." reminding time is up/已到提醒时间";
		
			$letter_params = [
					'system_alert_event_id'=>28,
					'user_id'=>$company_order_array["$company_order_number"]['create_user_id'],
					'content'=>$content,
					'url'=>'/branchcompany/companyOrderManage?company_order_number='.$company_order_number,
					'status'=>1
		
			];
		
			$this->_in_station_letter_service->addInStationLetter($letter_params);
		
			$create_user_email = $company_order_array["$company_order_number"]['create_user_email'];
			$update_user_email = $company_order_array["$company_order_number"]['update_user_email'];
			if(!empty($create_user_email)){
				$email_params = [
						'to_email'=>$company_order_array["$company_order_number"]['create_user_email'],
						'content'=>	$content,
						'subject'=>'提醒/reminding'
		
				];
				help::sendOperationsEmail($email_params);
			}
			if(!empty($update_user_email) && ($create_user_email!=$update_user_email)){
				$email_params = [
						'to_email'=>$company_order_array["$company_order_number"]['update_user_email'],
						'content'=>	$content,
						'subject'=>'提醒/reminding'
		
				];
				help::sendOperationsEmail($email_params);
			}
		
		}		
		
	}

	//把锁定的用户状态变更为正常
	public function reloadUserStatus(){
		$this->_user->reloadUserStatus();
		
	}
 	
	
	//每月的1月1号变更汇率
	
	public function updateMonthCurrencyProportion(){
		$currencyProportion = new CurrencyProportion();
		
		//开始判断当前是否有数据

		$params = [
			'status'=>1,
			'proportion_time'=>	date('Ymt')
				
		];
		$now_result = $currencyProportion->getCurrencyProportion($params);
	
		if(empty($now_result)){
	
			
		
			$last_day = date('Ymd',strtotime('-1 days',strtotime(date('Ym01'))));
				echo $last_day;
			$params = [
					'proportion_time'=>$last_day,
					'status'=>1
			];
			
			$result = $currencyProportion->getCurrencyProportion($params);
		
			for($i=0;$i<count($result);$i++){
				$currency_params = [
						'currency_id'=>$result[$i]['currency_id'],
						'opposite_currency_id'=>$result[$i]['opposite_currency_id'],
						'currency_proportion'=>$result[$i]['currency_proportion'],						
						'proportion_time'=>date('Ymt')
				
				];
				dump($currency_params);
				$currencyProportion->addCurrencyProportion($currency_params);
			}
			
		}
		
		
		
	}
	
	
	
	
	
}
