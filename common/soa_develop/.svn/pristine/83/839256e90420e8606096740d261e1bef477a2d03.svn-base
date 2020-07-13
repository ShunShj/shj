<?php
namespace app\index\service;


use app\index\model\branchcompany\CompanyOrder;
use app\index\model\branchcompany\CompanyOrderCustomer;
use app\index\model\branchcompany\CompanyOrderProduct;
use app\index\model\branchcompany\CompanyOrderProductTeam;
use app\index\model\branchcompany\CompanyOrderDiy;
use app\index\model\branchcompany\CompanyOrderProductDiyPrice;
use app\index\model\branchcompany\CompanyOrderProductSource;
use app\index\model\branchcompany\CompanyOrderRelation;
use app\index\model\branchcompany\CompanyOrderProductDiy;
use app\index\model\branchcompany\CompanyOrderCustomerlineup;
use app\index\model\branchcompany\BranchProduct;
use app\index\service\FinacesService;
use app\index\service\BranchProductService;
use app\index\model\finance\Receivable;
use app\index\model\finance\ReceivableInfo;
use app\index\model\finance\ReceivableCustomer;
use app\index\model\finance\Cope;
use app\index\model\finance\CopeInfo;
use app\index\model\product\TeamProduct;
use app\index\model\product\TeamProductAllocation;
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
use app\index\model\source\SourcePrice;
use app\index\model\btob\Distributor;
use app\index\model\system\Company;
use app\index\model\system\Currency;
use app\index\model\system\User;
use app\index\service\SourceService;
use app\index\service\ProportionService;
use think\Model;
use app\common\help\Help;
use think\Hook;
use think\Controller;

class CompanyOrderService{
	
	
	private $_company_order_product;
	private $_company_order_customer;
	private $_company_order_customer_lineup;
	private $_company_order_product_team;
	private $_company_order_product_source;
	private $_company_order_product_diy;
	private $_company;
	private $_team_product;
	private $_team_product_allocation;
	private	$_source;
	private	$_source_price;
	private $_distributor;
	private $_company_order;
	private $_receivable;
	private $_receivable_info;
	private $_receivable_customer;
	private $_cope;
	private $_cope_info;
	private $_company_order_relation;
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
	private $_source_service;
	private $_currency;
	private $_proportion_service;
	private $_finances_service;
	private $_user;
	private $_branch_product;
	private $_branch_product_service;
	public function __construct(){
		
		$this->_team_product = new TeamProduct();
		$this->_team_product_allocation = new TeamProductAllocation();
		$this->_source = new Source();
		$this->_source_price = new SourcePrice(); 
		$this->_distributor = new Distributor();
		$this->_company_order_product_team = new CompanyOrderProductTeam();
		$this->_company_order_product_source = new CompanyOrderProductSource();
		$this->_company_order_product_diy = new CompanyOrderProductDiy();
		$this->_company_order_customer = new CompanyOrderCustomer();
		$this->_company_order_product = new CompanyOrderProduct();
		$this->_company_order = new CompanyOrder();
		$this->_company_order_customer_lineup =new CompanyOrderCustomerlineup();
		$this->_branch_product = new BranchProduct();
		$this->_branch_product_service = new BranchProductService();
		$this->_receivable = new Receivable();
		$this->_receivable_customer = new ReceivableCustomer();
		$this->_cope = new Cope();
		$this->_company_order_relation = new CompanyOrderRelation();
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
    	$this->_source_service = new SourceService();
    	$this->_company = new Company();
    	$this->_currency = new Currency();
    	$this->_proportion_service = new ProportionService();
    	$this->_receivable_info = new ReceivableInfo();
    	$this->_cope_info = new  CopeInfo();
    	$this->_finances_service = new FinacesService();
    	$this->_user = new User();
    
	}
	
	//获取订单名称
	public function getCompanyOrderName($company_order_result){
		
		for($i=0;$i<count($company_order_result);$i++){
			if($company_order_result[$i]['channel_type']==1){
				$distributor_params = [
						'distributor_id'=>$company_order_result[$i]['distributor_id']
				];
				$company_order_result[$i]['distributor_info'] =  $this->_distributor->getDistributor($distributor_params);
			}
			$order_name_params = [
					'status'=>1,
					'company_order_number'=>$company_order_result[$i]['company_order_number']
			];
			$order_name='';
			//获取团队产品
			$company_order_product_result = $this->_company_order_product->getCompanyOrderProduct($order_name_params);
		
			for($j=0;$j<count($company_order_product_result);$j++){
		
				$order_name.="+".$company_order_product_result[$j]['branch_product_name'];
			}
		
			//获取资源
			$company_order_source_result = $this->_company_order_product_source->getCompanyOrderProductSource($order_name_params);
		
			for($j=0;$j<count($company_order_source_result);$j++){
				$order_name.="+".$company_order_source_result[$j]['source_name'];
			}
		
			//获取其他
			$company_order_diy_result = $this->_company_order_product_diy->getCompanyOrderProductDiy($order_name_params);
		
			for($j=0;$j<count($company_order_diy_result);$j++){
				$order_name.="+".$company_order_diy_result[$j]['diy_name'];
			}
		
			$order_name = trim($order_name,'+');
			$company_order_result[$i]['order_name'] = $order_name;
		}		
		
		return $company_order_result;
	}
	
	/**
	 * 获取游客的订单以及团队产品编号
	 */
	public function getCustomerLineUp($params){
	
		$company_order_number = $params['company_order_number'];
		$team_product_id = $params['$team_product_id'];
		$customer_info = $params['customer_info'];
		for($i=0;$i<count($customer_info);$i++){
			//
			$customer_and_linueup_params = [
				'status'=>1,
				
				'company_order_number'=>$company_order_number,
				'company_order_customer_id'=>$customer_info[$i]['company_order_customer_id'],
				'lineup_type'=>1	
			];
			
			$line_up_result = $this->_company_order_customer_lineup->getLineup($customer_and_linueup_params);
			
			$customer_info[$i]['company_order_lineup_number'] = help::getLineupPrefix(1).$line_up_result[0]['lineup_number'];
			
			if(!empty($team_product_id)){
				$customer_and_linueup_params = [
					'status'=>1,
					'team_product_id'=>$team_product_id,		
					'company_order_number'=>$company_order_number,
					'company_order_customer_id'=>$customer_info[$i]['company_order_customer_id'],
					'lineup_type'=>2
				];
				
				$line_up_result = $this->_company_order_customer_lineup->getLineup($customer_and_linueup_params);
				$customer_info[$i]['team_product_lineup_number'] = help::getLineupPrefix(2).$line_up_result[0]['lineup_number'];
			}

		}
		
		return $customer_info;
	}
	
	//获取顾客信息 包含 占位 
	public function getCustomer($params){
		$company_order_customer = new CompanyOrderCustomer();
		$company_order_customer_result  = $company_order_customer->getCompanyOrderCustomer($params);
		return $company_order_customer_result;
	}
	
	//通过订单号给 每一个游客添加排队序号
	public function updateCustomerLineUp($params){
		$now_user_id = $params['now_user_id'];
		$company_order_number = $params['company_order_number'];
		
		//通过 订单编号 查询订单ID
		$company_order_result = $this->_company_order->getCompanyOrder($params);
		
		//首先查询有多少游客
		$customer_info = $this->_company_order_customer->getCompanyOrderCustomer($params);
	
		//开始给每一个游客加上订单号
		$params['customer_info'] = $customer_info;
		$params['lineup_type'] = 1;
		$params['company_order_id'] = $company_order_result[0]['company_order_id'];
		$params['status']=1;
		$team_product_result = $this->_company_order_product_team->getCompanyOrderProductTeam($params);
		for($i=0;$i<count($team_product_result);$i++){
			$team_product_number = $team_product_result[$i]['team_product_number'];
			//通过NUMBER获取团队产品ID
			$team_product_params['team_product_id'] = $team_product_result[$i]['team_product_id'];
			$team_product_find_id = $this->_team_product->getTeamProduct($team_product_params);
			$team_product_result[$i]['team_product_id'] =$team_product_find_id[0]['team_product_id'];
	
		
		
		}
		$params['team_product_result'] = $team_product_result;
		$this->_company_order_customer_lineup->addLineup($params);


	}
	
	
	/*
	 *生成应收与应付 
	 */
	public function updateCompanyOrderPriceAndCope($params){
		$user_id = $params['now_user_id'];
		//首先查询订单的基础信息
		
		$company_order_params = [
			'company_order_number'=>$params['company_order_number']		
		];
		$company_order_result = $this->_company_order->getCompanyOrder($company_order_params);
		
		$channel_type = $company_order_result[0]["channel_type"];
		$order_number = $company_order_result[0]['company_order_number'];
		$company_order_number = $params['company_order_number'];
		$company_id = $company_order_result[0]['company_id'];
		//获取订单下 的游客 
		$company_order_customer_params = [
			'company_order_number'=>$company_order_number,
		];
		$company_order_customer_result = $this->_company_order_customer->getCompanyOrderCustomer($company_order_customer_params);
		$company_order_customer_id='';
		for($i=0;$i<count($company_order_customer_result);$i++){
			if($i==0){
				$company_order_customer_id.=$company_order_customer_result[$i]['company_order_customer_id'];
			}else{
				$company_order_customer_id.=','.$company_order_customer_result[$i]['company_order_customer_id'];
			}
		}
		$customer_count = count($company_order_customer_result);
		
		$company_order_product_params = [
			'company_order_number'=>$params['company_order_number'],
			'status'=>1	
		];
		
		//先把应收代理或者直客的数据变为0
		$update_status_params = [
				'order_number'=>$company_order_number,
		
				'fee_type_type'=>6001,

		];
		$this->_receivable->updateStatusByData($update_status_params);
		//先把应收代理或者直客的数据变为0
		$update_status_params = [
				'order_number'=>$company_order_number,
		
				'fee_type_type'=>6002,
		
		];
		$this->_receivable->updateStatusByData($update_status_params);		
		$company_diy_price = new CompanyOrderProductDiyPrice();
		
		$company_order_product_diy_price_params = [
				'company_order_number'=>$company_order_number,
				'status'=>1
		];
		
		
		$company_order_product_diy_price_result =$company_diy_price->getCompanyOrderProductDiyPrice($company_order_product_diy_price_params);
	
		for($i=0;$i<count($company_order_product_diy_price_result);$i++){
			if($channel_type==1){//经销商
				$data['payment_object_type'] =3;
				$data['payment_object_id'] =$company_order_result[0]['distributor_id'];
					
				
				
				if($company_order_product_diy_price_result[$i]['diy_type']==1){
					$data['fee_type_code'] =391;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==2){
					$data['fee_type_code'] =392;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==3){
					$data['fee_type_code'] =393;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==4){
					$data['fee_type_code'] =394;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==5){
					$data['fee_type_code'] =395;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==6){
					$data['fee_type_code'] =396;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==7){
					$data['fee_type_code'] =397;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==8){
					$data['fee_type_code'] =398;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==9){
					$data['fee_type_code'] =399;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==10){
					$data['fee_type_code'] =400;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==11){
					$data['fee_type_code'] =401;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==12){
					$data['fee_type_code'] =402;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==13){
					$data['fee_type_code'] =403;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==14){
					$data['fee_type_code'] =404;
				}
				$data['fee_type_type'] =6002;
			}else{
				$data['payment_object_type'] =4;
				$data['fee_type_code'] =84;
				if($company_order_product_diy_price_result[$i]['diy_type']==1){
					$data['fee_type_code'] =377;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==2){
					$data['fee_type_code'] =378;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==3){
					$data['fee_type_code'] =379;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==4){
					$data['fee_type_code'] =380;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==5){
					$data['fee_type_code'] =381;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==6){
					$data['fee_type_code'] =382;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==7){
					$data['fee_type_code'] =383;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==8){
					$data['fee_type_code'] =384;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==9){
					$data['fee_type_code'] =385;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==10){
					$data['fee_type_code'] =386;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==11){
					$data['fee_type_code'] =387;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==12){
					$data['fee_type_code'] =388;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==13){
					$data['fee_type_code'] =389;
				}else if($company_order_product_diy_price_result[$i]['diy_type']==14){
					$data['fee_type_code'] =390;
				}
				$data['fee_type_type'] =6001;
			}
		
			$data['order_number'] =$order_number;
			$data['is_auto'] = 1;
			$data['receivable_currency_id'] = $company_order_product_diy_price_result[$i]['price_currency_id'];
			$data['receivable_money'] = $company_order_product_diy_price_result[$i]['diy_price'];
			$data['tax_money'] = $company_order_product_diy_price_result[$i]['tax_price'];
			$data['company_id'] = $company_id;
			$data['product_name']=$company_order_product_diy_price_result[$i]['diy_name'];
			$data['now_user_id'] = $params['now_user_id'];
		
			$data['company_order_customer_id'] = $company_order_customer_id;
				
			//开始判断是否有应收编号
			if(!empty($company_order_product_diy_price_result[$i]['receivable_number'])){
					
				$data['receivable_number'] = $company_order_product_diy_price_result[$i]['receivable_number'];
					
				$this->_receivable->updateReceivableByReceivableNumber($data);
			}else{
				$receivable_number = $this->_receivable->addReceivable($data);
				//把应收编号存入公司订单分公司产品表中
				$company_order_product_diy_price_params=[
						'company_order_product_diy_price_id'=>$company_order_product_diy_price_result[$i]['company_order_product_diy_price_id'],
						'receivable_number'=>$receivable_number,
						'now_user_id'=>$user_id
				];
					
				$company_diy_price->updateCompanyOrderProductDiyPriceById($company_order_product_diy_price_params);
			}
				
			
			
		}

		
		
		
		//第一部查询分公司的价格
		
		$company_order_product_result = $this->_company_order_product->getCompanyOrderProduct($company_order_product_params);
		
		//先把应收代理或者直客的数据变为0
		$update_status_params = [
			'order_number'=>$company_order_number,
		
			'fee_type_type'=>204,
		
			'is_auto'=>2
		];
		$this->_receivable->updateStatusByData($update_status_params);
		for($i=0;$i<count($company_order_product_result);$i++){
	
			if($channel_type==1){//经销商
				$data['payment_object_type'] =3;
				$data['payment_object_id'] =$company_order_result[0]['distributor_id'];
					
				$data['fee_type_code'] =82;
			}else{
				$data['payment_object_type'] =4;
				$data['fee_type_code'] =84;
			}
			$data['fee_type_type'] =204;
			$data['order_number'] =$order_number;
			$data['is_auto'] = 2;
			$data['receivable_currency_id'] = $company_order_product_result[$i]['price_currency_id'];
			$data['receivable_money'] = $company_order_product_result[$i]['branch_product_price'];
			$data['tax_money'] = $company_order_product_result[$i]['branch_product_price']- $company_order_product_result[$i]['price_before_tax'];
			$data['company_id'] = $company_id;
			$data['product_name']=$company_order_product_result[$i]['branch_product_name'];
			$data['now_user_id'] = $params['now_user_id'];
			$data['remark'] = $company_order_product_result[$i]['remark'];
			$data['company_order_customer_id'] = $company_order_customer_id;
			
			//开始判断是否有应收编号
			if(!empty($company_order_product_result[$i]['receivable_number'])){
			
				$data['receivable_number'] = $company_order_product_result[$i]['receivable_number'];
			
				$this->_receivable->updateReceivableByReceivableNumber($data);
			}else{
				$receivable_number = $this->_receivable->addReceivable($data);
				//把应收编号存入公司订单分公司产品表中
				$company_order_product_params=[
						'company_order_product_id'=>$company_order_product_result[$i]['company_order_product_id'],
						'receivable_number'=>$receivable_number,
						'now_user_id'=>$user_id
				];
					
				$this->_company_order_product->updateCompanyOrderProductById($company_order_product_params);
			}
			
			

		}
		
		
		//第二部查询团队产品
		//先把团队产品的应收应付变为0
		$update_status_params = [
			'order_number'=>$company_order_number,
		
			'fee_type_type'=>201,
		
			'is_auto'=>2
		];
		$this->_receivable->updateStatusByData($update_status_params);
		$update_status_params = [
				'order_number'=>$company_order_number,
		
				'fee_type_type'=>2010,
		
				'is_auto'=>2
		];
		$this->_receivable->updateStatusByData($update_status_params);
		
		$update_status_params = [
			'order_number'=>$company_order_number,
		
			'fee_type_type'=>104,
		
			'is_auto'=>2
		];
		$this->_cope->updateStatusByData($update_status_params);
		$company_order_product_team_params = [
			'company_order_number'=>$params['company_order_number'],
			'status'=>1,
			'settlement_type'=>1
		];
		
		$company_order_product_team_result = $this->_company_order_product_team->getCompanyOrderProductTeam($company_order_product_team_params);

		for($i=0;$i<count($company_order_product_team_result);$i++){
		
			if($company_order_product_team_result[$i]['is_type']==1){
				
				$data['order_number']=$company_order_number;
				$data['team_product_id']=$company_order_product_team_result[$i]['team_product_id'];
				//$data['product_name'] = '';
				$data['receivable_object_type'] = 1;
				//通过团队产品编号查询团队产品所属分公司ID
				$team_product_params = [
						'team_product_id' =>$company_order_product_team_result[$i]['team_product_id'],
						'can_watch_company_id'=>$params['user_company_id']
				];
				$team_product_result = $this->_team_product->getTeamProduct($team_product_params);
				//$team_product_base_result =  $this->_team_product->getTeamProductBase($team_product_params);
				//首先判断团队产品的类型是 一口价 还是真实结算
				if($team_product_result[0]['settlement_type']==1){//如果是一口价
						
					$data['order_number']=$company_order_number;
						
					$data['receivable_object_type'] =1;
						
					$data['receivable_object_id'] =$team_product_result[0]['company_id'];
						
					$data['cope_currency_id'] = $company_order_product_team_result[$i]['cost_currency_id'];
						
					$data['invoice_number'] =$company_order_product_team_result[$i]['invoice_number'];
						
					$data['invoice_time'] = $company_order_product_team_result[$i]['invoice_time'];
						
					$data['cope_money'] = $company_order_product_team_result[$i]['team_product_cost'];
					$data['price'] = $company_order_product_team_result[$i]['team_product_cost_univalence'];
					$data['unit'] = $customer_count;
					$data['product_name'] = $company_order_product_team_result[$i]['team_product_name'];
					$data['company_id'] =$company_id;
					$data['fee_type_code']=70;
					$data['fee_type_type']=104;
					$data['is_auto'] =2;
					$data['now_user_id'] = $params['now_user_id'];
					$data['company_order_customer_id'] = $company_order_customer_id;
					$data['status'] = 1;
					//开始判断是否有应付编号
					if(!empty($company_order_product_team_result[$i]['cope_number'])){
						$data['cope_number'] = $company_order_product_team_result[$i]['cope_number'];
							
						$this->_cope->updateCopeByCopeNumber($data);
					}else{//新增
						$data['status'] = 1;
				
						$cope_number =  $this->_cope->addCope($data);
						//把应付编号存入公司订单团队产品中
						$company_order_product_team_params=[
								'company_order_product_team_id'=>$company_order_product_team_result[$i]['company_order_product_team_id'],
								'cope_number'=>$cope_number,
								'now_user_id'=>$user_id
						];
							
						$this->_company_order_product_team->updateCompanyOrderTeam($company_order_product_team_params);
							
							
					}
				}				
			}

			
			//开始判断 是否是直接定的团队产品 如果是 则添加 应收
			if($company_order_product_team_result[$i]['is_type']==2){
				if($channel_type==1){//经销商
					$data['payment_object_type'] =3;
					$data['payment_object_id'] =$company_order_result[0]['distributor_id'];
						
					$data['fee_type_code'] =341;
				}else{
					$data['payment_object_type'] =4;
					$data['fee_type_code'] =342;
				}
				$data['fee_type_type'] =2010;
				$data['order_number'] =$order_number;
				$data['is_auto'] = 2;
				$data['team_product_id'] = $company_order_product_team_result[$i]['team_product_id'];
				$data['receivable_currency_id'] = $company_order_product_team_result[$i]['price_currency_id'];
				$data['receivable_money'] = $company_order_product_team_result[$i]['team_product_price'];
				$data['tax_money'] = $company_order_product_team_result[$i]['team_product_price']- $company_order_product_team_result[$i]['price_before_tax'];
				
				$data['company_id'] =$company_id;
				$data['product_name']=$company_order_product_team_result[$i]['team_product_name'];
				$data['now_user_id'] = $params['now_user_id'];
				$data['remark'] = $company_order_product_team_result[$i]['remark'];
				$data['company_order_customer_id'] = $company_order_customer_id;
				//开始判断资源是否有该数据
				$source_params = [
					'company_order_product_team_id'=>$company_order_product_team_result[$i]['company_order_product_team_id'],
					'status'=>1	
				];
				$source_result = $this->_company_order_product_source->getCompanyOrderProductSource($source_params);
				
				
				
				//开始判断是否有数据
				if(count($source_result)>0){//代表有数据 更改receivable_number
					$source_params = [
							
							
					
							'price_currency_id'=>$company_order_product_team_result[$i]['price_currency_id'],
							'source_price'=>$company_order_product_team_result[$i]['team_product_price'],
							'company_order_product_source_id'=>$source_result[0]['company_order_product_source_id'],
						
							
							
					
					];
					
					
					$this->_company_order_product_source->updateCompanyOrderSource($source_params);
				}else{
					$data['source_type_id'] = 14;
					$receivable_number = $this->_receivable->addReceivable($data);

					//把团费添加到资源中去
						
					$source_params = [
							'company_order_number'=>$company_order_product_team_result[$i]['company_order_number'],
							'team_product_id'=>$company_order_product_team_result[$i]['team_product_id'],
							'source_name'=>$company_order_product_team_result[$i]['team_product_name'],
							'price_currency_id'=>$company_order_product_team_result[$i]['price_currency_id'],
							'source_price'=>$company_order_product_team_result[$i]['team_product_price'],
							'source_cost'=>0,
							'cost_currency_id'=>$company_order_product_team_result[$i]['price_currency_id'],
							'supplier_type_id'=>14,
							'is_receivable_company'=>2,
							'receivable_number'=>$receivable_number,
							"is_type"=>2,
							'company_order_product_team_id'=>$company_order_product_team_result[$i]['company_order_product_team_id'],
					];
						
			
					$this->_company_order_product_source->addCompanyOrderProductSource($source_params);
					
					
				}
				
				
				/*
				//开始判断是否有应收编号
				if(!empty($company_order_product_team_result[$i]['receivable_number'])){
						
					$data['receivable_number'] = $company_order_product_team_result[$i]['receivable_number'];
						
					$this->_receivable->updateReceivableByReceivableNumber($data);
				}else{
					$receivable_number = $this->_receivable->addReceivable($data);
					//把应收编号存入公司订单分公司产品表中
					$company_order_product_team_params=[
							'company_order_product_team_id'=>$company_order_product_team_result[$i]['company_order_product_team_id'],
							'receivable_number'=>$receivable_number,
							'now_user_id'=>$user_id
					];
						
					$this->_company_order_product_team->updateCompanyOrderTeam($company_order_product_team_params);
					
					//把团费添加到资源中去
					
					$source_params = [
						'company_order_number'=>$company_order_product_team_result[$i]['company_order_number'],	
						'team_product_id'=>$company_order_product_team_result[$i]['team_product_id'],
						'source_name'=>$company_order_product_team_result[$i]['team_product_name'],
						'price_currency_id'=>$company_order_product_team_result[$i]['price_currency_id'],
						'source_price'=>$company_order_product_team_result[$i]['team_product_price'],
						'source_cost'=>0,
						'cost_currency_id'=>$company_order_product_team_result[$i]['price_currency_id'],	
						'supplier_type_id'=>14,
						'is_receivable_company'=>2,
							
					];
					
					
					$this->_company_order_product_source->addCompanyOrderProductSource($source_params);
					
				}
				*/
			}

		}
		//开始结算供应商的钱
		$company_order_product_team_params = [
			'company_order_number'=>$params['company_order_number'],
			'status'=>1,
			
		];
		
		$cope_supplier_result = $this->_company_order_product_team->getCompanyOrderProductTeam($company_order_product_team_params);
		for($j=0;$j<count($cope_supplier_result);$j++){
			$cope_supplier_result[$j]['now_user_id'] = $params['now_user_id'];
			$this->addTeamProductPriceAndCope($cope_supplier_result[$j]);
			
			
		}
		

		
		
		//第三部查询资源
		
	
		
	
		$company_order_product_source_params = [
			'company_order_number'=>$company_order_number,
			'status'=>1
		];
		
		$company_order_product_source_result = $this->_company_order_product_source->getCompanyOrderProductSource($company_order_product_source_params);
	
		for($i=0;$i<count($company_order_product_source_result);$i++){
			$data = [];
			//判断是否是团队应收分公司过来的数据，是的话就不出现
			if($company_order_product_source_result[$i]['is_receivable_company']==1 ){
				continue;
			}

			$source_params = [
				'supplier_type_id'=>$company_order_product_source_result[$i]['supplier_type_id'],
				'source_id'=>$company_order_product_source_result[$i]['source_id']
			];
			
			$source_result = $this->_source->getSource($source_params);
			//查询团队产品信息
			$team_product_result_params = [
					'team_product_id'=>	$company_order_product_source_result[$i]['team_product_id']
			];
			$team_product_result = $this->_team_product->getTeamProduct($team_product_result_params);
			//如果为自费项目或团费
			if($company_order_product_source_result[$i]['supplier_type_id']==11 || $company_order_product_source_result[$i]['supplier_type_id']==14){
				if($company_order_product_source_result[$i]['supplier_type_id']==11){
					
					//开始查询自费项目的参与人数
					$company_order_relation_params = [
							'company_order_number'=>$company_order_number,
							'company_order_product_source_id'=>$company_order_product_source_result[$i]['company_order_product_source_id'],
							'status'=>1
					];
					$company_order_relation_result = $this->_company_order_relation->getCompanyOrderRelation($company_order_relation_params);
					$data['unit']=count($company_order_relation_result)?count($company_order_relation_result):0;
					
					$company_order_customer_id='';
					for($ii=0;$ii<count($company_order_relation_result);$ii++){
						if($ii==0){
							$company_order_customer_id.=$company_order_relation_result[$ii]['company_order_customer_id'];
						}else{
							$company_order_customer_id.=','.$company_order_relation_result[$ii]['company_order_customer_id'];
						}
					}
				}else{
					$data['unit'] = $customer_count;
					$company_order_customer_id = $company_order_customer_id;
				}

					if($channel_type==1){//经销商
						$data['payment_object_type'] =3;
						$data['payment_object_id'] =$company_order_result[0]['distributor_id'];
						if($company_order_product_source_result[$i]['is_type']==1){
							$data['fee_type_type'] =204;
							$data['fee_type_code'] =83;
						}else{
							if($company_order_product_source_result[$i]['supplier_type_id']==11){
								$data['fee_type_code'] =343;
								$data['source_type_id']=11;
							}else{
								$data['fee_type_code'] =341;
								$data['source_type_id']=14;
							}
							$data['fee_type_type'] =2010;
						}	
						
					}else{
						$data['payment_object_type'] =4;
						if($company_order_product_source_result[$i]['is_type']==1){
							$data['fee_type_type'] =204;
							$data['fee_type_code'] =85;
						}else{
							$data['fee_type_type'] =2010;
							if($company_order_product_source_result[$i]['supplier_type_id']==11){
								$data['source_type_id']=11;
								$data['fee_type_code'] =344;
							}else{
								$data['source_type_id']=14;
								$data['fee_type_code'] =342;
							}
							
						}
						
				
					}
				
					$data['order_number'] =$order_number;
					$data['is_auto'] = 2;
					$data['receivable_currency_id'] = $company_order_product_source_result[$i]['price_currency_id'];
					$data['receivable_money'] = $company_order_product_source_result[$i]['source_price'];
					$data['tax_money'] = $company_order_product_source_result[$i]['source_price']- $company_order_product_source_result[$i]['price_before_tax'];
					
					$data['company_id'] = $company_id;
					$data['product_name']=$company_order_product_source_result[$i]['source_name'];
					$data['now_user_id'] = $params['now_user_id'];
					$data['remark'] = $company_order_product_source_result[$i]['remark'];
					$data['company_order_customer_id'] = $company_order_customer_id;
			
					if(is_numeric($company_order_product_source_result[$i]['team_product_id'])){
						$data['team_product_id'] = $company_order_product_source_result[$i]['team_product_id'];
					}
					//开始判断是否有应收编号
					if(!empty($company_order_product_source_result[$i]['receivable_number'])){
							
						$data['receivable_number'] = $company_order_product_source_result[$i]['receivable_number'];
							
						$this->_receivable->updateReceivableByReceivableNumber($data);
					}else{
						$receivable_number = $this->_receivable->addReceivable($data);
						//把应收编号存入公司订单分公司产品表中
						$company_order_product_source_params=[
							'company_order_product_source_id'=>$company_order_product_source_result[$i]['company_order_product_source_id'],
							'receivable_number'=>$receivable_number,
							'now_user_id'=>$user_id
						];
							
						$this->_company_order_product_source->updateCompanyOrderSource($company_order_product_source_params);
					}
				
				
				
				
			}else{
				$data['unit']=$customer_count;
			}
			
			
			//首先需要判断是否是 自己的资源
			if($company_order_product_source_result[$i]['is_type']==1){//需要付给其他公司的应付 或其他供应商

				if($company_order_product_source_result[$i]['is_own_source_by_branch_product'] == 1){//代表付给供应商
					$data['receivable_object_type'] =2;
					
					
					$source_params = [
							'supplier_type_id'=>$company_order_product_source_result[$i]['supplier_type_id'],
							'source_id'=>$company_order_product_source_result[$i]['source_id']
								
					];
					
					$source_result = $this->_source->getSource($source_params);
					
					//再获取供应商ID
					
					$data['receivable_object_id'] =$source_result['supplier_id'];
					
					$data['cope_currency_id'] = $company_order_product_source_result[$i]['cost_currency_id'];
					$data['cope_money'] =$company_order_product_source_result[$i]['source_cost'];
					
					
					$data['company_id'] =$company_id;
					if($company_order_product_source_result[$i]['supplier_type_id']==2){
						$team_product_fee_type_code = 1;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==3){
						$team_product_fee_type_code = 2;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==4){
						$team_product_fee_type_code = 3;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==5){
						$team_product_fee_type_code = 4;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==6){
						$team_product_fee_type_code = 5;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==7){
						$team_product_fee_type_code = 6;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==8){
						$team_product_fee_type_code = 7;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==9){
						$team_product_fee_type_code = 8;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==10){
						$team_product_fee_type_code = 9;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==11){
						$team_product_fee_type_code = 10;
					}
					
						
					$data['fee_type_code']=$team_product_fee_type_code;
						
					//以下是公共部分
						
					$data['price']=$company_order_product_source_result[$i]['source_cost_univalence'];
					$data['order_number']=$company_order_number;
					$data['source_type_id']=$company_order_product_source_result[$i]['supplier_type_id'];
					$data['source_number']=$source_result['source_number'];
					$data['team_product_number']=$company_order_product_source_result[$i]['team_product_number'];
					$data['product_name'] = $company_order_product_source_result[$i]['source_name'];
					$data['fee_type_type']=101;
					$data['is_auto'] =2;
					$data['now_user_id'] = $params['now_user_id'];
					$data['company_order_customer_id'] = $company_order_customer_id;
						
					$data['invoice_number']=$company_order_product_source_result[$i]['invoice_number'];
					$data['invoice_time']=$company_order_product_source_result[$i]['invoice_time'];
					$data['status'] = 1;
						
						
						
					//首先判断是否有应付编号
					if(!empty($company_order_product_source_result[$i]['cope_number'])){
						$data['cope_number'] = $company_order_product_source_result[$i]['cope_number'];
						$this->_cope->updateCopeByCopeNumber($data);
					}else{
							
							
							
						$code_number = $this->_cope->addCope($data);
				
						//修改应付编号
						$company_order_product_source_params=[
								'company_order_product_source_id'=>$company_order_product_source_result[$i]['company_order_product_source_id'],
								'cope_number'=>$code_number,
								'now_user_id'=>$user_id
						];
					
						$this->_company_order_product_source->updateCompanyOrderSource($company_order_product_source_params);
					}
				}else{
					$data['receivable_object_type'] =1;
					$data['receivable_object_id'] =$team_product_result[0]['company_id'];
					$data['cope_currency_id'] = $company_order_product_source_result[$i]['price_currency_id'];
					$data['cope_money'] =$company_order_product_source_result[$i]['source_cost'];
					
					
					$data['company_id'] =$company_id;

					if($company_order_product_source_result[$i]['supplier_type_id']==2){
						$team_product_fee_type_code = 290;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==3){
						$team_product_fee_type_code = 291;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==4){
						$team_product_fee_type_code = 292;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==5){
						$team_product_fee_type_code = 293;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==6){
						$team_product_fee_type_code = 294;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==7){
						$team_product_fee_type_code = 295;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==8){
						$team_product_fee_type_code = 296;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==9){
						$team_product_fee_type_code = 297;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==10){
						$team_product_fee_type_code = 298;
					}else if($company_order_product_source_result[$i]['supplier_type_id']==11){
						$team_product_fee_type_code = 299;
					}
					else if($company_order_product_source_result[$i]['supplier_type_id']==12){
						$team_product_fee_type_code = 300;
					}	
						
					$data['fee_type_code']=$team_product_fee_type_code;
						
					//以下是公共部分
						
					$data['price']=$company_order_product_source_result[$i]['source_cost_univalence'];
					$data['order_number']=$company_order_number;
					$data['source_type_id']=$company_order_product_source_result[$i]['supplier_type_id'];
					$data['source_number']=$source_result['source_number'];
					$data['team_product_number']=$company_order_product_source_result[$i]['team_product_number'];
					$data['product_name'] = $company_order_product_source_result[$i]['source_name'];
					$data['fee_type_type']=104;
					$data['is_auto'] =2;
					$data['now_user_id'] = $params['now_user_id'];
					$data['company_order_customer_id'] = $company_order_customer_id;
						
					$data['invoice_number']=$company_order_product_source_result[$i]['invoice_number'];
					$data['invoice_time']=$company_order_product_source_result[$i]['invoice_time'];
					$data['status'] = 1;
						
						
						
					//首先判断是否有应付编号
					if(!empty($company_order_product_source_result[$i]['cope_number'])){
						$data['cope_number'] = $company_order_product_source_result[$i]['cope_number'];
						$this->_cope->updateCopeByCopeNumber($data);
					}else{
							
							
							
						$code_number = $this->_cope->addCope($data);
							
						//修改应付编号
						$company_order_product_source_params=[
								'company_order_product_source_id'=>$company_order_product_source_result[$i]['company_order_product_source_id'],
								'cope_number'=>$code_number,
								'now_user_id'=>$user_id
						];
					
						$this->_company_order_product_source->updateCompanyOrderSource($company_order_product_source_params);
					}					
				}


 				
 				


			}



			
		}
		
		
		
		//再查询自定义
		//再查询自定义项目
		$company_order_product_diy_params = [
			'company_order_number'=>$company_order_number,
			'status'=>1
		];
		
		
		$company_order_product_diy_result = $this->_company_order_product_diy->getCompanyOrderProductDiy($company_order_product_diy_params);
		for($i=0;$i<count($company_order_product_diy_result);$i++){
			$company_order_relation_div_params = [
				'company_order_number'=>$company_order_number,
				'status'=>1,
				'company_order_product_diy_id'=>$company_order_product_diy_result[$i]['company_order_product_diy_id']
						
			];
			
			$company_order_relation_result = $this->_company_order_relation->getCompanyOrderRelation($company_order_relation_div_params);
			
			$company_order_relation_result_count = count($company_order_relation_result)?count($company_order_relation_result):0;
			$company_order_customer_id_two='';
			for($j=0;$j<count($company_order_relation_result);$j++){
				if($j==0){
					$company_order_customer_id_two.=$company_order_relation_result[$j]['company_order_customer_id'];
				}else{
					$company_order_customer_id_two.=','.$company_order_relation_result[$j]['company_order_customer_id'];
				}
			}
			$data = [];
			
			
			if($company_order_product_diy_result[$i]['is_type'] == 2){
				$data['team_product_id']=$company_order_product_diy_result[$i]['team_product_id'];
			}
			
			
			$data['order_number']=$company_order_number;
			$data['product_name']=$company_order_product_diy_result[$i]['diy_name'];
			$data['company_order_customer_id'] = $company_order_customer_id_two;			
			$data['source_type_id'] = 12;
			$data['receivable_object_type'] = 2;				
			$data['receivable_object_id'] = $company_order_product_diy_result[$i]['supplier_id'];
			$data['cope_currency_id'] = $company_order_product_diy_result[$i]['cost_currency_id'];
			$data['cope_money'] = $company_order_product_diy_result[$i]['diy_cost'];
			$data['price'] = $company_order_product_diy_result[$i]['diy_cost'];
			$data['company_id'] =  $company_id;
			$data['company_order_customer_id'] = $company_order_customer_id;
			$data['is_auto'] =2;
			$data['now_user_id'] = $params['now_user_id'];			
			$data['unit']=1;			
			//$data['price']=number_format($data['cope_money']/$data['unit'],2);
			$data['fee_type_code']=11;
			$data['fee_type_type']=101;
			$data['status']=1;
			if(!empty($company_order_product_diy_result[$i]['cope_number'])){
				$data['cope_number'] = $company_order_product_diy_result[$i]['cope_number'];
				$this->_cope->updateCopeByCopeNumber($data);
			}else{
				$code_number = $this->_cope->addCope($data);
					
				//把应付编号存入公司订单资源中团队产品中
				$company_order_product_diy_params=[
					'company_order_product_diy_number'=>$company_order_product_diy_result[$i]['company_order_product_diy_number'],
					'cope_number'=>$code_number,
					'now_user_id'=>$user_id,
					'remark'=>$company_order_product_source_result[$i]['remark']
				];					
				$this->_company_order_product_diy->updateCompanyOrderProductDiyByDiyNumber($company_order_product_diy_params);
			}
			
			
			$data = [];
			/*
			//应收直客或代理的钱
			$data['order_number']=$company_order_number;
			$data['product_name']=$company_order_product_diy_result[$i]['diy_name'];
			$data['company_order_customer_id'] = $company_order_customer_id_two;
			$data['source_type_id'] = 12;
			
			$data['payment_object_id'] = $company_order_result[0]['distributor_id'];
			$data['receivable_currency_id'] = $company_order_product_diy_result[$i]['price_currency_id'];
			$data['receivable_money'] = $company_order_product_diy_result[$i]['diy_price'];
			$data['company_id'] =  $params['user_company_id'];
			$data['company_order_customer_id'] = $company_order_customer_id;
			$data['is_auto'] =2;
			$data['now_user_id'] = $params['now_user_id'];
			$data['unit']=$company_order_relation_result_count;
			$data['price']=number_format($data['receivable_money']/$data['unit'],2);
			$data['status']=1;
			if($channel_type==1){
				$data['fee_type_code']=303;
				$data['payment_object_type'] = 3;
			}else{
				$data['fee_type_code']=304;
				$data['payment_object_type'] = 4;
			}
			
			$data['fee_type_type']=204;
			if(!empty($company_order_product_diy_result[$i]['receivable_number'])){
				$data['receivable_number'] = $company_order_product_diy_result[$i]['receivable_number'];
				$this->_receivable->updateReceivableByReceivableNumber($data);
			}else{
				$receivable_number = $this->_receivable->addReceivable($data);
					
				//把应付编号存入公司订单资源中团队产品中
				$company_order_product_diy_params=[
						'company_order_product_diy_number'=>$company_order_product_diy_result[$i]['company_order_product_diy_number'],
						'receivable_number'=>$receivable_number,
						'now_user_id'=>$user_id,
						'remark'=>$company_order_product_source_result[$i]['remark']
				];
				$this->_company_order_product_diy->updateCompanyOrderProductDiyByDiyNumber($company_order_product_diy_params);
			}
		
			*/
			
		}
		

		
		return 1;
		
	}
	
	
	
	public function addTeamProductPriceAndCope($params){
		

		$team_product_id = $params['team_product_id'];
		//$team_product_number = $params['team_product_number'];
		$company_order_number = $params['company_order_number'];
		$branch_product_number = $params['branch_product_number'];
		$team_product_params = [
			'team_product_id' =>$team_product_id,
			'status'=>1
		];
		$team_product_result = $this->_team_product->getTeamProduct($team_product_params);
		

		
		//通过订单编号查询订单人数
		$company_order_customer_params = [
	
			'company_order_number'=>$company_order_number	
		];
		$company_order_customer = $this->_company_order_customer->getCompanyOrderCustomerByCompanyOrderNumber($company_order_customer_params);
		
		$company_order_customer_count = count($company_order_customer);

		
		
		$team_product_allocation_params = [

			'team_product_id'=>$team_product_id,
			'status'=>1,
					

				
		];
		
		//发团公司给供应商的钱
		$team_product_allocation_result = $this->_team_product_allocation->getTeamProductAllocation($team_product_allocation_params);
//     暂时 注释		
// 		$source_status_params = [
// 				'status'=>0,
// 				'company_ordere_number'=>$company_order_number,
// 				'team_product_id'=>	$team_product_id,
// 				'is_not_source_type_14'=>1
		
// 		];
	
// 		$this->_company_order_product_source->updateCompanyOrderSourceStatus($source_status_params);
		
		for($j=0;$j<count($team_product_allocation_result);$j++){
		
		

				//if($team_product_allocation_result[$j]['supplier_type_id']!=11){//代表不是自费，，开始发团公司向供应商添加应付
				
				

					
				$source_params = [
					'supplier_type_id'=>$team_product_allocation_result[$j]['supplier_type_id'],
					'source_id'=>$team_product_allocation_result[$j]['source_id']
							
				];
	
				$source_result = $this->_source->getSource($source_params);
		
				
				//通过团队产品获取团队产品信息
				if($team_product_allocation_result[$j]['supplier_type_id']==2){
					$fee_type_code=1;
				
						
				}else if($team_product_allocation_result[$j]['supplier_type_id']==3){
					$fee_type_code=2;
				
				}else if($team_product_allocation_result[$j]['supplier_type_id']==4){
					$fee_type_code=3;
			
				}else if($team_product_allocation_result[$j]['supplier_type_id']==5){
					$fee_type_code=4;
			
				}else if($team_product_allocation_result[$j]['supplier_type_id']==6){
					$fee_type_code=5;
				
				}else if($team_product_allocation_result[$j]['supplier_type_id']==7){
					$fee_type_code=6;
				
				}else if($team_product_allocation_result[$j]['supplier_type_id']==8){
					$fee_type_code=7;
				
				}else if($team_product_allocation_result[$j]['supplier_type_id']==9){
					$fee_type_code=8;
				
				}else if($team_product_allocation_result[$j]['supplier_type_id']==10){
					$fee_type_code=9;
					
				}else if($team_product_allocation_result[$j]['supplier_type_id']==11){
					$fee_type_code=10;
				
				}
				else if($team_product_allocation_result[$j]['supplier_type_id']==12){
					$fee_type_code=11;
				}
				
				
				//通过当前的 id以及供应商类型获取团队产品的资源价格
				$source_price_params = [
					'supplier_type_id'=>$team_product_allocation_result[$j]['supplier_type_id'],
					'pk_id'=>$team_product_allocation_result[$j]['source_id']
		
				];
					
				$source_price_result = $this->_source_price->getSourcePirce($source_price_params);
				
				if($team_product_allocation_result[$j]['supplier_type_id']==11){ //如果等于自费则去判断该订单有多少游客参与
					
					//通过资源ID 供应商ID 分公司产品 团队产品 公司订单 来查询 公司订单的资源ID去请求多少人参与
					
					$company_order_product_source_params = [
						'source_id'=>$team_product_allocation_result[$j]['source_id'],
						'supplier_type_id'=>11,
						'company_order_number'=>$company_order_number,
						'branch_product_number'=>$branch_product_number,
						'team_product_id'=>	$team_product_id
					];
					$company_order_product_source_result = $this->_company_order_product_source->getCompanyOrderProductSource($company_order_product_source_params);
					
					//通过资源ID查询游客
					$company_order_relation_params = [
						'status'=>1,
						'company_order_number'=>$company_order_number,
						'company_order_product_source_id'=>$company_order_product_source_result[0]['company_order_product_source_id']
					];
					$r = $this->_company_order_relation->getCompanyOrderRelation($company_order_relation_params);
					
					
					$customer_count = count($r);
				}else{
					$customer_count = $company_order_customer_count;
				}
				//获取单价
				
				$team_product_allocation_params = [
				
						'team_product_id'=>$team_product_id,
						'supplier_type_id'=>$team_product_allocation_result[$j]['supplier_type_id'],
						'source_id'=>$team_product_allocation_result[$j]['source_id'],
						'status'=>1
					
				];
			
				//发团公司给供应商的钱
				$allocation_result= $this->_team_product_allocation->getTeamProductAllocation($team_product_allocation_params);
				
				$cope_data = [
						'order_number'=>$company_order_number,
						'source_type_id'=>$team_product_allocation_result[$j]['supplier_type_id'],
						'source_number'=>$source_result['source_number'],				
						'team_product_id'=>$team_product_result[0]['team_product_id'],
						'product_name'=>$source_result['source_name'],
						'receivable_object_type'=>2,
						'receivable_object_id'=>$source_result['supplier_id'],
						'cope_currency_id'=>$team_product_allocation_result[$j]['payment_currency_id'],
						"cope_money"=>$source_price_result[0]['normal_price']*$allocation_result[0]['source_count']*$customer_count,
						'price'=>$source_price_result[0]['normal_price'],
						'unit'=>$allocation_result[0]['source_count']*$customer_count,
						'fee_type_code'=>$fee_type_code,
						'company_id'=>$team_product_result[0]['company_id'],
						'is_auto'=>2,
						'fee_type_type'=>101,
						'now_user_id'=>$team_product_result[0]['create_user_id']
				];
				
				
				//开始 判断是否有数据 就修改没数据 就 新增
				$cope_params = [
					'order_number'=>$company_order_number,
					'source_type_id'=>$team_product_allocation_result[$j]['supplier_type_id'],
					'source_number'=>$source_result['source_number'],
					'team_product_id'=>$team_product_result[0]['team_product_id'],
				];
				//判断是否有应付数据 
				$cope_result = $this->_cope->getCope($cope_params);
				if(!empty($cope_result)){//如果不等于空则修改
					//修改应付供应商数据
					$cope_supplier = [
						'cope_number'=>$cope_result[0]['cope_number'],	
						'cope_currency_id'=>$team_product_allocation_result[$j]['payment_currency_id'],
						"cope_money"=>$source_price_result[0]['normal_price']*$allocation_result[0]['source_count']*$customer_count,
						'price'=>$source_price_result[0]['normal_price'],
						'unit'=>$allocation_result[0]['source_count']*$customer_count,
						'now_user_id'=>$params['now_user_id'],
						'status'=>1	
					];
					
					$this->_cope->updateCopeByCopeNumber($cope_supplier);
				
					$cope_number =$cope_result[0]['cope_number']; 
					
					
				}else{
					$cope_number = $this->_cope->addCope($cope_data);
				}
				
				
				
				
				
				//去寻找该订单把状态变回来
				//先查询company_order_product_source_id
				
				$source_params = [
					'company_order_number'=>$company_order_number,
					'team_product_id'=>	$team_product_result[0]['team_product_id'],
					'supplier_type_id'=>$team_product_allocation_result[$j]['supplier_type_id'],
					'source_id'=>$team_product_allocation_result[$j]['source_id']	
				];
		
				$source_result = $this->_company_order_product_source->getCompanyOrderProductSource($source_params);
				for($i=0;$i<count($source_result);$i++){
					if($i==0){//只修改一个
						if($source_result[0]['is_type']==2){ //如果是自己资源则添加应付编号否则不添加应付编号
							//再走修改
							$source_params = [
									'cope_number'=>$cope_number,
									'company_order_product_source_id'=>	$source_result[0]['company_order_product_source_id'],
									'status'=>1,
									'now_user_id'=>$params['now_user_id']
							];
						
								
							$this->_company_order_product_source->updateCompanyOrderSource($source_params);
						}else{
							$source_params = [
						
									'company_order_product_source_id'=>	$source_result[0]['company_order_product_source_id'],
									'status'=>1,
									'now_user_id'=>$params['now_user_id']
							];
						
						
							$this->_company_order_product_source->updateCompanyOrderSource($source_params);
								
								
						}
					}
					
				}

				
			


				

			//}

		}
		return $result;	
	}
	//获得报价插入 到数据库
	public function updateCompanyOrderPrice($params){
		
		
		
		$company_order_number = $params['company_order_number'];
	
		$company_order = new CompanyOrder();
		$company_order_product = new CompanyOrderProduct();//公司订单的产品
		$company_order_customer = new  CompanyOrderCustomer();
		$company_order_relation = new CompanyOrderRelation();
		$company_order_product_diy = new  CompanyOrderProductDiy();
		$company_order_product_team = new CompanyOrderProductTeam();
		$team_product = new TeamProduct();
		$cope = new Cope();
		$receivable = new Receivable();
		$hotel = new Hotel();
		$dining = new Dining();
		$flight = new Flight();
		$cruise = new Cruise();
		$visa = new Visa();
		$scenic_spot = new ScenicSpot();
		$vehicle = new Vehicle();
		$tour_guide = new TourGuide();
		$single_source = new SingleSource();
		$own_expense = new OwnExpense();
// 		$company_order_product_source = 
// 		$company_order_product_params = [
// 			'company_order_number'=>$company_order_number,
// 			'status'=>1
// 		];
// 		$company_order_product_result = $company_order_product->getCompanyOrderProduct($company_order_product_params);

		//再获取订单下的所有游客，分公司产品默认所有游客购买
		$company_order_customer_params = [
			'company_order_number'=>$company_order_number,
		];
		
		
		
		$company_order_customer_result = $company_order_customer->getCompanyOrderCustomer($company_order_customer_params);
	
		$company_order_customer_id='';
		for($i=0;$i<count($company_order_customer_result);$i++){
			if($i==0){
				$company_order_customer_id.=$company_order_customer_result[$i]['company_order_customer_id'];
			}else{
				$company_order_customer_id.=','.$company_order_customer_result[$i]['company_order_customer_id'];
			}
		}
		$customer_count = count($company_order_customer_result);
		//开始查询
		
		//开始查询分公司产品报价 （只为了生成报价而已）
		
		$company_order_product = new CompanyOrderProduct();
		$company_order_product_params = [
			'status'=>1,
			'company_order_number'=>$company_order_number	
		];
		$total=0;
		$company_order_product_result = $company_order_product->getCompanyOrderProduct($company_order_product_params);
		for($i=0;$i<count($company_order_product_result);$i++){
			$total+=$company_order_product_result[$i]['branch_product_price'];
		}
		
		//开始查询有多少个团队产品
		$company_order_product_team_params = [
			'status'=>1,
			'company_order_number'=>$company_order_number
		];
		
		$company_order_product_team_result = $company_order_product_team->getCompanyOrderProductTeam($company_order_product_team_params);
		
		

		//先把老数据状态变更为0
		$update_cope_status_params = [
			'order_number'=>$company_order_number,	
		
			'fee_type_type'=>101,
		
			'is_auto'=>2
		];
		$cope->updateStatusByData($update_cope_status_params);
		$update_receivable_status_params = [
				'order_number'=>$company_order_number,
		
				'fee_type_type'=>201,
		
				'is_auto'=>2
		];
		
		$receivable->updateStatusByData($update_receivable_status_params);
		
		$update_receivable_status_params = [
				'order_number'=>$company_order_number,
		
			
				'fee_type_type'=>104,
				'is_auto'=>2
		];

		
		$cope->updateStatusByData($update_receivable_status_params);
		//提前把资源删除
		$update_receivable_status_params = [
				'order_number'=>$company_order_number,
		
					
				'fee_type_type'=>101,
				'is_auto'=>2
		];
		
		
		$cope->updateStatusByData($update_receivable_status_params);
		
		for($i=0;$i<count($company_order_product_team_result);$i++){
			$data = [];
			
			$data['order_number']=$company_order_number;
			$data['team_product_number']=$company_order_product_team_result[$i]['team_product_number'];
			//$data['product_name'] = '';
			$data['receivable_object_type'] = 1;
			//通过团队产品编号查询团队产品所属分公司ID
			$team_product_params = [
				'team_product_number' =>$company_order_product_team_result[$i]['team_product_number'],
				'can_watch_company_id'=>$params['user_company_id']
			];
			$team_product_result = $team_product->getTeamProduct($team_product_params);
			$team_product_base_result = $team_product->getTeamProductBase($team_product_params);
			//首先判断团队产品的类型是 一口价 还是真实结算
			if($team_product_result[0]['settlement_type']==1){//如果是一口价
				
				$data['order_number']=$company_order_number;

				$data['receivable_object_type'] =1;

				$data['receivable_object_id'] =$team_product_result[0]['company_id'];
				
				$data['cope_currency_id'] = $company_order_product_team_result[$i]['cost_currency_id'];
				
				$data['invoice_number'] =$company_order_product_team_result[$i]['invoice_number'];
				
				$data['invoice_time'] = $company_order_product_team_result[$i]['invoice_time'];
				
				$data['cope_money'] = $team_product_base_result[0]['once_price']*$customer_count;
				$data['price'] = $team_product_base_result[0]['once_price'];
				$data['unit'] = $customer_count;
				$data['product_name'] = $team_product_result[$i]['team_product_name'];
				$data['company_id'] =$params['user_company_id'];
				$data['fee_type_code']=70;
				$data['fee_type_type']=104;
				$data['is_auto'] =2;
				$data['now_user_id'] = $params['now_user_id'];
				$data['company_order_customer_id'] = $company_order_customer_id;
				$cope->addCope($data);
				
				
				
			}

			
			//开始查询团队产品的资源
			//拿到团队产品ID 反查数据
			$team_product_id = $team_product_result[0]['team_product_id'];
			
			$team_product_allocation_params = [
				'team_product_id'=>$team_product_id,
				'status'=>1,
					
			];
		
			$team_product_allocation_result = $this->_team_product_allocation->getTeamProductAllocation($team_product_allocation_params);
			
			for($j=0;$j<count($team_product_allocation_result);$j++){


				$cope_data = [];
				if($team_product_allocation_result[$j]['supplier_type_id']!=11){//代表不是自费，，开始发团公司向供应商添加应付
					
					
					$source_params = [
						'supplier_type_id'=>$team_product_allocation_result[$j]['supplier_type_id'],
						'source_id'=>$team_product_allocation_result[$j]['source_id']
					
					];
					$source_result = $this->_source->getSource($source_params);
		
					//通过团队产品获取团队产品信息
					if($team_product_allocation_result[$j]['supplier_type_id']==2){
						$fee_type_code=1;
						$team_product_fee_type_code = 290;
					
					}else if($team_product_allocation_result[$j]['supplier_type_id']==3){
						$fee_type_code=2;
						$team_product_fee_type_code = 291;
					}else if($team_product_allocation_result[$j]['supplier_type_id']==4){
						$fee_type_code=3;
						$team_product_fee_type_code = 292;
					}else if($team_product_allocation_result[$j]['supplier_type_id']==5){
						$fee_type_code=4;
						$team_product_fee_type_code = 293;
					}else if($team_product_allocation_result[$j]['supplier_type_id']==6){
						$fee_type_code=5;
						$team_product_fee_type_code = 294;
					}else if($team_product_allocation_result[$j]['supplier_type_id']==7){
						$fee_type_code=6;
						$team_product_fee_type_code = 295;
					}else if($team_product_allocation_result[$j]['supplier_type_id']==8){
						$fee_type_code=7;
						$team_product_fee_type_code = 296;
					}else if($team_product_allocation_result[$j]['supplier_type_id']==9){
						$fee_type_code=8;
						$team_product_fee_type_code = 297;
					}else if($team_product_allocation_result[$j]['supplier_type_id']==10){
						$fee_type_code=9;
						$team_product_fee_type_code = 298;
					}else if($team_product_allocation_result[$j]['supplier_type_id']==11){
						$fee_type_code=10;
						$team_product_fee_type_code = 299;
					}
					else if($team_product_allocation_result[$j]['supplier_type_id']==12){
						$fee_type_code=11;
					}
					//通过当前的 id以及供应商类型获取团队产品的资源价格
					$source_price_params = [
						'supplier_type_id'=>$team_product_allocation_result[$j]['supplier_type_id'],
						'pk_id'=>$team_product_allocation_result[$j]['source_id']
								
					];
					
					$source_price_result = $this->_source_price->getSourcePirce($source_price_params);
					

					$cope_data = [
						'order_number'=>$company_order_number,
						'source_type_id'=>$team_product_allocation_result[$j]['supplier_type_id'],
						'source_number'=>$source_result['source_number'],
						'team_product_number'=>$team_product_result[0]['team_product_number'],
						'product_name'=>$source_result['source_name'],
						'receivable_object_type'=>2,
						'receivable_object_id'=>$source_result['supplier_id'],
						'cope_currency_id'=>$team_product_allocation_result[$j]['payment_currency_id'],
						"cope_money"=>$source_price_result[0]['normal_price']*$team_product_allocation_result[$j]['source_count']*$customer_count,
						'price'=>$source_price_result[0]['normal_price'],
						'unit'=>$team_product_allocation_result[$j]['source_count']*$customer_count,	
						'fee_type_code'=>$fee_type_code,
						'company_id'=>$team_product_result[0]['company_id'],
						'is_auto'=>2,	
						'fee_type_type'=>101,
						'now_user_id'=>$team_product_result[0]['create_user_id']	
					];
					
					$cope->addCope($cope_data);
					if($team_product_result[0]['settlement_type']==2){//如果是真实结算

							$data['order_number']=$company_order_number;
							$data['source_type_id']=$team_product_allocation_result[$j]['supplier_type_id'];
							$data['source_number']=$source_result['source_number'];
							$data['team_product_number']=$team_product_result[0]['team_product_number'];
							$data['product_name'] = $source_result['source_name'];
							$data['receivable_object_type'] =1;
							$data['receivable_object_id'] =$team_product_result[0]['company_id'];								
							$data['cope_currency_id'] = $team_product_allocation_result[$j]['payment_currency_id'];
							$data['cope_money'] =$team_product_allocation_result[$j]['source_total_price']*$customer_count;
							$data['price']=$team_product_allocation_result[$j]['source_total_price'];
							$data['unit']=$customer_count;
							$data['company_id'] =$params['user_company_id'];
							$data['fee_type_code']=$team_product_fee_type_code;
							$data['fee_type_type']=104;
							$data['is_auto'] =2;
							$data['now_user_id'] = $params['now_user_id'];
							$data['company_order_customer_id'] = $company_order_customer_id;
							
							$data['invoice_number']=$company_order_product_team_result[$i]['invoice_number'];
							$data['invoice_time']=$company_order_product_team_result[$i]['invoice_time'];
							$cope->addCope($data);
				
					
					
					
					
					}
				}
			}
			

		}
		

	

		
		//再查询资源
		$company_order_product_source = new CompanyOrderProductSource();
		$company_order_product_source_params = [
			'status'=>1,
		
			'company_order_number'=>$company_order_number	
				
		];
		$company_order_product_source_result = $company_order_product_source->getCompanyOrderProductSource($company_order_product_source_params);
		

		

		
		for($i=0;$i<count($company_order_product_source_result);$i++){

			
			$c_count = $customer_count;
			$data = [];
				
			$data['order_number']=$company_order_number;
			$data['company_order_customer_id'] = $company_order_customer_id;
			$data['fee_type_type'] = 104;
			if($company_order_product_source_result[$i]['supplier_type_id']==2){
				$hotel = new Hotel();
				$source_params['hotel_id'] = $company_order_product_source_result[$i]['source_id'];
				$source_result = $hotel->getHotel($source_params);
				$source_id = $source_result[0]['hotel_id'];
				$data['fee_type_code']=71;
				$source_name = $source_result[0]['room_name'];
				
			}
			if($company_order_product_source_result[$i]['supplier_type_id']==3){

				$dining = new Dining();
				$source_params['dining_id'] = $company_order_product_source_result[$i]['source_id'];
				
		
				
				$source_result = $dining->getDining($source_params);
				
				$source_id = $source_result[0]['dining_id'];
				$data['fee_type_code']=72;
				$source_name = $source_result[0]['dining_name'];
			}
			if($company_order_product_source_result[$i]['supplier_type_id']==4){

				$flight = new Flight();
				$source_params['flight_id'] = $company_order_product_source_result[$i]['source_id'];
				$source_result = $flight->getFlight($source_params);
				$source_id = $source_result[0]['flight_id'];
				$data['fee_type_code']=73;
				$source_name = $source_result[0]['flight_number'];
			}
			if($company_order_product_source_result[$i]['supplier_type_id']==5){

				$cruise = new Cruise();
				$source_params['cruise_id'] = $company_order_product_source_result[$i]['source_id'];
				$source_result = $cruise->getCruise($source_params);
				$source_id = $source_result[0]['cruise_id'];
				$data['fee_type_code']=74;
				$source_name = $source_result[0]['cruise_name'];
			}
			if($company_order_product_source_result[$i]['supplier_type_id']==6){

				$visa = new Visa();
				$source_params['visa_id'] = $company_order_product_source_result[$i]['source_id'];
				$source_result = $visa->getVisa($source_params);
				$source_id = $source_result[0]['visa_id'];
				$data['fee_type_code']=75;
				$source_name = $source_result[0]['visa_name'];
			}
			if($company_order_product_source_result[$i]['supplier_type_id']==7){

		
				$scenic_spot = new ScenicSpot();
				$source_params['scenic_spot_id'] = $company_order_product_source_result[$i]['source_id'];
				$source_result = $scenic_spot->getScenicSpot($source_params);
				$source_id = $source_result[0]['scenic_spot_id'];
				$data['fee_type_code']=76;
				$source_name = $source_result[0]['scenic_spot_name'];
			}
			if($company_order_product_source_result[$i]['supplier_type_id']==8){

				$vehicle = new Vehicle();
				$source_params['vehicle_id'] = $company_order_product_source_result[$i]['source_id'];
				$source_result = $vehicle->getVehicle($source_params);
				$source_id = $source_result[0]['vehicle_id'];
				$data['fee_type_code']=77;
				$source_name = $source_result[0]['vehicle_name'];
			}
			if($company_order_product_source_result[$i]['supplier_type_id']==9){

				$tour_guide = new TourGuide();
				$source_params['tour_guide_id'] = $company_order_product_source_result[$i]['source_id'];
				$source_result = $tour_guide->getTourGuide($source_params);
				$source_id = $source_result[0]['tour_guide_id'];
				$data['fee_type_code']=78;
				$source_name = $source_result[0]['tour_guide_name'];
			}
			if($company_order_product_source_result[$i]['supplier_type_id']==10){

				$single_source = new SingleSource();
				$source_params['single_source_id'] = $company_order_product_source_result[$i]['source_id'];
				$source_result = $single_source->getSingleSource($source_params);
				$source_id = $source_result[0]['single_source_id'];
				$data['fee_type_code']=79;
				$source_name = $source_result[0]['single_source_name'];
			}
			if($company_order_product_source_result[$i]['supplier_type_id']==11){

				$own_expense = new OwnExpense();
				$source_params['own_expense_id'] = $company_order_product_source_result[$i]['source_id'];
				$source_result = $own_expense->getOwnExpense($source_params);
				$source_id = $source_result[0]['own_expense_id'];
				$source_name = $source_result[0]['own_expense_name'];
				
				$data['fee_type_code']=80;
				$company_order_relation_params = [
					'company_order_number'=>$company_order_number,
				 	'status'=>1,
				 	'company_order_product_source_id'=>$company_order_product_source_result[$i]['company_order_product_source_id']
				];
				
				$company_order_relation_result = $company_order_relation->getCompanyOrderRelation($company_order_relation_params);
				$c_count = count($company_order_relation_result);
 				$company_order_customer_id_two='';
				for($j=0;$j<count($company_order_relation_result);$j++){
					if($j==0){
						$company_order_customer_id_two.=$company_order_relation_result[$j]['company_order_customer_id'];
					}else{
						$company_order_customer_id_two.=','.$company_order_relation_result[$j]['company_order_customer_id'];
					}
				}

 				$data['company_order_customer_id'] = $company_order_customer_id_two;
 				$total+=$company_order_product_source_result[$i]['source_price'];
 				
 				if($company_order_product_source_result[$i]['team_product_number']!=''){//如果是其他公司的团队产品里的自费项目
 					
 					//通过团队产品编号查询团队产品所属分公司ID
 					$team_product_params = [
 							'team_product_number' =>$company_order_product_source_result[$i]['team_product_number']
 					];
 					
 					$team_product_result = $team_product->getTeamProduct($team_product_params);
 					
 					//发布公司 付钱给  供应商
 					
 					$team_product_allocation_params = [
 						'team_product_id'=>$team_product_id,
 						'status'=>1,
 						'supplier_type_id'=>11,
 						'source_id'=>$source_id	
 								
 					];
 					$team_product_allocation_result = $this->_team_product_allocation->getTeamProductAllocation($team_product_allocation_params);
 						
 					
 					//通过当前的 id以及供应商类型获取团队产品的资源价格
 					$source_price_params = [
 							'supplier_type_id'=>11,
 							'pk_id'=>$source_id
 					
 					];
 						
 					$source_price_result = $this->_source_price->getSourcePirce($source_price_params);
 					//发团公司给供应商的钱
 					$cope_data = [
 							'order_number'=>$company_order_number,
 							'source_type_id'=>11,
 							'source_number'=>$source_result[0]['source_number'],
 							'team_product_number'=>$team_product_result[0]['team_product_number'],
 							'product_name'=>$source_result[0]['own_expense_name'],
 							'receivable_object_type'=>2,
 							'receivable_object_id'=>$source_result[0]['supplier_id'],
 							'cope_currency_id'=>$team_product_allocation_result[0]['payment_currency_id'],
 							"cope_money"=>$source_price_result[0]['normal_price']*$team_product_allocation_result[0]['source_count']*$c_count,
 							'price'=>$source_price_result[0]['normal_price'],
 							'unit'=>$team_product_allocation_result[0]['source_count']*$c_count,	
 							'company_id'=>$team_product_result[0]['company_id'],
 							'is_auto'=>2,
 							'fee_type_code'=>10,
 							
 							'fee_type_type'=>101,
 							'now_user_id'=>$team_product_result[0]['create_user_id']
 					];
 					
 					$cope->addCope($cope_data);
 					//订购公司需要应付给发团公司
 					
 					$allocation_result_params = [
 						'supplier_type_id'=>11,
 						'source_id'=>$source_id,
 						'team_product_id'=>$team_product_result[0]['team_product_id']
 					];
 					$allocation_result = $this->_team_product_allocation->getTeamProductAllocation($allocation_result_params);
 					
 					
 					$cope_data = [];
 					$cope_data = [
 						'order_number'=>$company_order_number,
 						'source_type_id'=>11,
 						'source_number'=>$source_result[0]['source_number'],
 						'team_product_number'=>$team_product_result[0]['team_product_number'],
 						'product_name'=>$source_result[0]['own_expense_name'],	
 						'receivable_object_type'=>1,
 						'receivable_object_id'=>$team_product_result[0]['company_id'],
 						'cope_currency_id'=>$team_product_allocation_result[0]['payment_currency_id'],
 						"cope_money"=>$allocation_result[0]['source_total_price']*$c_count,
 						'price'=>$source_price_result[0]['normal_price'],
 						'unit'=>$c_count,
 						'company_id'=>$params['user_company_id'],
 						'company_order_customer_id'=>$company_order_customer_id_two,
 						'is_auto'=>2,
 						'fee_type_code'=>289,
 						'invoice_number'=>$company_order_product_source_result[$i]['invoice_number'],
 							
 						'invoice_time' =>$company_order_product_source_result[$i]['invoice_time'],
 						'fee_type_type'=>104,
 						'now_user_id'=>$team_product_result[0]['create_user_id']
 					];
 				
 					$cope->addCope($cope_data);
 					
 					
 					
 					continue;
 				}
 					
 				
			}
	
			//通过当前的 id以及供应商类型获取团队产品的资源价格
			$source_price_params = [
				'supplier_type_id'=>$company_order_product_source_result[$i]['supplier_type_id'],
				'pk_id'=>$source_id
			
			];
				
			$source_price_result = $this->_source_price->getSourcePirce($source_price_params);
			$data['source_number'] = $source_result[0]['source_number'];			
			$data['source_type_id'] = $company_order_product_source_result[$i]['supplier_type_id'];
			
			$data['product_name']=$source_name;

			$data['receivable_object_type'] = 2;
			$data['receivable_object_id'] = $source_result[0]['supplier_id'];	
			$data['cope_currency_id'] = $company_order_product_source_result[$i]['cost_currency_id'];			
			$data['cope_money'] = $source_price_result[0]['normal_price']*$c_count;
			
			$data['price']=$source_price_result[0]['normal_price'];
			$data['unit']=$c_count;
			$data['company_id'] =  $params['user_company_id'];
			$data['company_order_customer_id'] = $company_order_customer_id;
			$data['is_auto'] =2;
			$data['now_user_id'] = $params['now_user_id'];
			
			$cope->addCope($data);

		}
		
	

		//最后查询自定义项目
		$company_order_product_diy_params = [
			'company_order_number'=>$company_order_number,
			'status'=>1	
		];
		
		
		$company_order_product_diy_result = $company_order_product_diy->getCompanyOrderProductDiy($company_order_product_diy_params);
		
		

		for($k=0;$k<count($company_order_product_diy_result);$k++){
		
			$company_order_relation_div_params = [
				'company_order_number'=>$company_order_number,
				'status'=>1,
				'company_order_product_diy_id'=>$company_order_product_diy_result[$k]['company_order_product_diy_id']
					
			];
			
			$company_order_relation_result = $company_order_relation->getCompanyOrderRelation($company_order_relation_div_params);
			$company_order_customer_id_two='';
			for($j=0;$j<count($company_order_relation_result);$j++){
				if($j==0){
					$company_order_customer_id_two.=$company_order_relation_result[$j]['company_order_customer_id'];
				}else{
					$company_order_customer_id_two.=','.$company_order_relation_result[$j]['company_order_customer_id'];
				}
			}
			$data = [];
			
			$data['order_number']=$company_order_number;
			$data['company_order_customer_id'] = $company_order_customer_id_two;

			$data['source_type_id'] = 12;
				
		
				
		
			$data['receivable_object_type'] = 3;
			
			
			$data['receivable_object_id'] = $company_order_product_diy_result[$k]['supplier_id'];
			$data['cope_currency_id'] = $company_order_product_diy_result[$k]['cost_currency_id'];
			$data['cope_money'] = $company_order_product_diy_result[$k]['diy_cost']*count($company_order_relation_result);
				
				
				
			
			$data['company_id'] =  $params['user_company_id'];
			$data['company_order_customer_id'] = $company_order_customer_id;
			$data['is_auto'] =2;
			$data['now_user_id'] = $params['now_user_id'];

			$cope->addCope($data);
			$total+=$company_order_product_diy_result[$k]['diy_price'];
			
		}
		
		
		//把应收变成0
		$update_receivable_status_params = [
			'order_number'=>$company_order_number,
		
			'fee_type_type'=>204,
			'is_auto'=>2
		];
		
		$receivable->updateStatusByData($update_receivable_status_params);
		
		
		//添加应收
 		$update_status_params = [
 			'order_number'=>$company_order_number,
			'fee_type_type'=>204,
 			'is_auto'=>2
 		];
		

		$data = [];
		$data['order_number'] = $company_order_number;
	
		//查订单 的信息
		$company_order_params = [
			'company_order_number'=>$company_order_number	
		];
		$company_order_result = $company_order->getCompanyOrder($company_order_params);
		
		for($i=0;$i<count($company_order_result);$i++){
			if($company_order_result[$i]['channel_type']==1){
				$distributor_params = [
						'distributor_id'=>$company_order_result[$i]['distributor_id']
				];
				$company_order_result[$i]['distributor_info'] =  $this->_distributor->getDistributor($distributor_params);
			}
			$order_name_params = [
					'status'=>1,
					'company_order_number'=>$company_order_result[$i]['company_order_number']
			];
			$order_name='';
			//获取团队产品
			$company_order_product_result = $company_order_product->getCompanyOrderProduct($order_name_params);
		
			for($j=0;$j<count($company_order_product_result);$j++){
		
				$order_name.="+".$company_order_product_result[$j]['branch_product_name'];
			}
		
			//获取资源
			$company_order_source_result = $this->_company_order_product_source->getCompanyOrderProductSource($order_name_params);
		
			for($j=0;$j<count($company_order_source_result);$j++){
				$order_name.="+".$company_order_source_result[$j]['source_name'];
			}
		
			//获取其他
			$company_order_diy_result = $this->_company_order_product_diy->getCompanyOrderProductDiy($order_name_params);
		
			for($j=0;$j<count($company_order_diy_result);$j++){
				$order_name.="+".$company_order_diy_result[$j]['diy_name'];
			}
			 
			$order_name = trim($order_name,'+');
			$company_order_result[$i]['order_name'] = $order_name;
		}
		
		
		if($company_order_result[0]['channel_type']==1){//经销商
			$data['payment_object_type'] =3;
			$data['payment_object_id'] =$company_order_result[0]['distributor_id'];
																	
			$data['fee_type_code'] =82;
		}else{
			$data['payment_object_type'] =4;
			$data['fee_type_code'] =84;
		}
		$data['fee_type_type'] =204;
		
		$data['is_auto'] = 2;
		$data['receivable_currency_id'] = 1;
		$data['receivable_money'] = $total;
		$data['resource_type'] = 4;
		$data['company_id'] = $params['user_company_id'];
		$data['product_name']=$company_order_result[0]['order_name'];
		$data['now_user_id'] = $params['now_user_id'];
		$data['company_order_customer_id'] = $company_order_customer_id;
		
	
		$receivable->addReceivable($data);
		
		
		return 1;
	}
	/**
	 * 获取公司订单产品
	 */
	public function getCompanyOrderProduct($params){
		$company_order_product = new CompanyOrderProduct();//公司订单的产品
		$company_order_product_team = new CompanyOrderProductTeam();//公司订单的团队产品
		$company_order_product_source = new CompanyOrderProductSource();//资源
		$company_order_product_diy = new CompanyOrderProductDiy();//DIY成本
		$company_order_product_diy_price = new CompanyOrderProductDiyPrice();//DIY报价
		$company_order_customer = new CompanyOrderCustomer();
		$company_order_service = new CompanyOrderService();
		$company_order_relation =  new CompanyOrderRelation();
	

		//获取订单基本信息


		$order_params['company_order_number'] = $params['company_order_number'];
		$company_order_result = $this->_company_order->getCompanyOrder($order_params);
		
		$company_order_company_name = $company_order_result[0]['company_name'];
		
		
		//获取所有游客
		$params['status']=1;
		$company_order_customer_result = $company_order_service->getCustomer($params);
		$company_order_customer_params['company_order_number']=$params['company_order_number'];
		$company_order_customer_params['team_product_id']=$params['team_product_id'];
		$company_order_customer_params['customer_info'] = $company_order_customer_result;

		//$company_order_customer_result = $this->getCustomerLineUp($company_order_customer_params);
		//首先获得公司订单的分公司产品
		$company_order_product_result = $company_order_product->getCompanyOrderProduct($params);
		
		
		
		//只要是分公司产品必须所有人参与
		for($i=0;$i<count($company_order_product_result);$i++){
			$company_order_product_result[$i]['customer_info'] = $company_order_customer_result;
		}
	
		//获取团队产品
		//$params['settlement_type'] = 1;
		$company_order_product_team_result = $company_order_product_team->getCompanyOrderProductTeam($params);
		
		for($i=0;$i<count($company_order_product_team_result);$i++){
			if($company_order_product_team_result[$i]['is_receivable_company']==1){//
				//通过应付编号去查应收信息
				$cope_params = [
						'cope_number'=>$company_order_product_team_result[$i]['cope_number']
				];
				$receivable_result = $this->_finances_service->getReceivableByCopeNumber($cope_params);
				

				
				if($company_order_product_team_result[$i]['is_type']==2){
					//通过应收编号去查看游客
					$receivable_customer_params = [
							'receivable_number'=>$company_order_product_team_result[$i]['receivable_number'],
							'status'=>1
					];
					

					
				}else{
					//通过应收编号去查看游客
					$receivable_customer_params = [
						'receivable_number'=>$receivable_result[0]['receivable_number'],
						'status'=>1
					];
				}
				
				
				
				$receivable_customer_result = $this->_receivable_customer->getReceivableCustomer($receivable_customer_params);
				$company_order_product_team_result[$i]['customer_info'] = $receivable_customer_result;
			}else{
				$company_order_product_team_result[$i]['customer_info'] = $company_order_customer_result;
			}
			

			if($company_order_product_team_result[$i]['is_type']==2){
				//他的报价应该为所有团费相加
				$source_params = [
						'status'=>1,
						'company_order_product_team_id'=>$company_order_product_team_result[$i]['company_order_product_team_id'],
				];
			
				$r = $this->_company_order_product_source->getCompanyOrderProductSource($source_params);
			
				$price = 0;
				for($j=0;$j<count($r);$j++){//把类型等于团费的相加
					if($r[$j]['supplier_type_id']==11){
						continue;
					}
 					if($company_order_product_team_result[$i]['price_currency_id'] != $r[$j]['price_currency_id']){
					
 						$proportion = $this->_proportion_service->getProportion( $r[$j]['price_currency_id'], $company_order_product_team_result[$i]['price_currency_id']);
 						$price+=$proportion*$r[$j]['source_price'];
 					}else{
 						$price+=$r[$j]['source_price'];
 					}
				}
				$company_order_product_team_result[$i]['team_product_price'] = $price;
			}
		

			
		}
		 
		
		
		//获取资源
		if($params['is_not_source_type_14']!=2){
			
			$params['is_not_source_type_14'] =1;
		}else{
			unset($params['is_not_source_type_14']);
		}
	
		$company_order_product_source_result = $company_order_product_source->getCompanyOrderProductSource($params);
		
		
		for($i=0;$i<count($company_order_product_source_result);$i++){
			$company_order_product_source_params = [];
			if($company_order_product_source_result[$i]['supplier_type_id']==11){//如果是自费项目就去查看自费项目的人数
				$company_order_product_source_params = [
						'company_order_product_source_id'=>$company_order_product_source_result[$i]['company_order_product_source_id'],
						'company_order_number'=>$params['company_order_number'],
						'status'=>$params['status']
							
				];
				$own_expense_customer = $company_order_relation->getCompanyOrderRelation($company_order_product_source_params);
				

				$company_order_relation_source_params=[
					
					'company_order_product_source_id'=>$company_order_product_source_result[$i]['company_order_product_source_id'],
					'company_order_number'=>$params['company_order_number'],
					'status'=>1
					
				];
			
				if($company_order_product_source_result[$i]['is_receivable_company']==1){
					//通过应付编号去查应收信息
					$cope_params = [
							'cope_number'=>$company_order_product_source_result[$i]['cope_number']
					];
					$receivable_result = $this->_finances_service->getReceivableByCopeNumber($cope_params);
					
					if($company_order_product_source_result[$i]['is_type']==2){
						//通过应收编号去查看游客
						$receivable_customer_params = [
								'receivable_number'=>$company_order_product_source_result[$i]['receivable_number'],
								'status'=>1
						];
					}else{
						//通过应收编号去查看游客
						$receivable_customer_params = [
								'receivable_number'=>$receivable_result[0]['receivable_number'],
								'status'=>1
						];
					}
					

				
					$receivable_customer_result = $this->_receivable_customer->getReceivableCustomer($receivable_customer_params);
					$company_order_product_source_result[$i]['customer_info'] = $receivable_customer_result;
				}else{
					$own_expense_customer_result = $company_order_relation->getCompanyOrderRelation($company_order_relation_source_params);
					$company_order_product_source_result[$i]['customer_info'] =$own_expense_customer_result;
				}
				
				

			}else{
				if($company_order_product_source_result[$i]['is_receivable_company']==1){
					//通过应付编号去查应收信息
					$cope_params = [
							'cope_number'=>$company_order_product_source_result[$i]['cope_number']
					];
					$receivable_result = $this->_finances_service->getReceivableByCopeNumber($cope_params);
				
					//通过应收编号去查看游客
					$receivable_customer_params = [
							'receivable_number'=>$receivable_result[0]['receivable_number'],
							'status'=>1
					];
					$receivable_customer_result = $this->_receivable_customer->getReceivableCustomer($receivable_customer_params);
					$company_order_product_source_result[$i]['customer_info'] = $receivable_customer_result;
				}else{
					$company_order_product_source_result[$i]['customer_info'] =  $company_order_customer_result;
				}
				
				
				
			}
		
			//开始查询每个的NUMBER
			if($company_order_product_source_result[$i]['supplier_type_id']==2){
				$source_params = [
						'hotel_id'=>$company_order_product_source_result[$i]['source_id']
				];
				$result = $this->_hotel->getHotel($source_params);
		
			}else if($company_order_product_source_result[$i]['supplier_type_id']==3){
				$source_params = [
						'dining_id'=>$company_order_product_source_result[$i]['source_id']
				];
				$result = $this->_dining->getDining($source_params);
		
			}else if($company_order_product_source_result[$i]['supplier_type_id']==4){
				$source_params = [
						'flight_id'=>$company_order_product_source_result[$i]['source_id']
				];
				$result = $this->_flight->getFlight($source_params);
		
			}else if($company_order_product_source_result[$i]['supplier_type_id']==5){
				$source_params = [
						'cruise_id'=>$company_order_product_source_result[$i]['source_id']
				];
				$result = $this->_cruise->getCruise($source_params);
		
			}else if($company_order_product_source_result[$i]['supplier_type_id']==6){
				$source_params = [
						'visa_id'=>$company_order_product_source_result[$i]['source_id']
				];
				$result = $this->_visa->getVisa($source_params);
		
			}else if($company_order_product_source_result[$i]['supplier_type_id']==7){
				$source_params = [
						'scenic_spot_id'=>$company_order_product_source_result[$i]['source_id']
				];
				$result = $this->_scenic_spot->getScenicSpot($source_params);
		
			}else if($company_order_product_source_result[$i]['supplier_type_id']==8){
				$source_params = [
						'vehicle_id'=>$company_order_product_source_result[$i]['source_id']
				];
				$result = $this->_vehicle->getVehicle($source_params);
		
			}else if($company_order_product_source_result[$i]['supplier_type_id']==9){
				$source_params = [
						'tour_guide_id'=>$company_order_product_source_result[$i]['source_id']
				];
				$result = $this->_tour_guide->getTourGuide($params);
		
			}else if($company_order_product_source_result[$i]['supplier_type_id']==10){
				$source_params = [
						'single_source_id'=>$company_order_product_source_result[$i]['source_id']
				];
				$result = $this->_single_source->getSingleSource($source_params);
		
			}else if($company_order_product_source_result[$i]['supplier_type_id']==11){
				$source_params = [
					'own_expense_id'=>$company_order_product_source_result[$i]['source_id']
				];
				
				$result = $this->_own_expense->getOwnExpense($source_params);
				
			}
			if(empty($company_order_product_source_result[$i]['source_id'])){
				$company_order_product_source_result[$i]['source_number'] = $result[0]['source_number'];
				$company_order_product_source_result[$i]['source_company_id'] = $result[0]['company_id'];
				$company_order_product_source_result[$i]['source_company_name'] =$company_order_product_source_result[$i]['supplier_name'];
			}else{
				$company_order_product_source_result[$i]['source_number'] = $result[0]['source_number'];
				$company_order_product_source_result[$i]['source_company_id'] = $result[0]['company_id'];
				$company_order_product_source_result[$i]['source_company_name'] = $result[0]['company_name'];
			}

		}
		
		
		
		
		
		 
		$company_order_product_diy_params = [
				'status'=>$params['status'],
				'company_order_number'=>$params['company_order_number']
		];
		 
		//获取DIY成本
		$company_order_product_diy_result = $company_order_product_diy->getCompanyOrderProductDiy($company_order_product_diy_params);
		for($i=0;$i<count($company_order_product_diy_result);$i++){
			$company_order_relation_diy_params = [];
			$company_order_relation_diy_params = [
					'company_order_product_diy_id'=>$company_order_product_diy_result[$i]['company_order_product_diy_id'],
					'company_order_number'=>$params['company_order_number'],
					'status'=>$params['status']
			];
			$company_order_product_diy_result[$i]['diy_company_name'] = $company_order_company_name;
		
			$company_order_product_diy_result[$i]['customer_info'] = $company_order_relation->getCompanyOrderRelation($company_order_relation_diy_params);
		
		}
		 
		//获取DIY报价 
		$company_order_product_diy_price_params = [
				'status'=>$params['status'],
				'company_order_number'=>$params['company_order_number']
		];

		//获取DIY成本
		$company_order_product_diy_price_result = $company_order_product_diy_price->getCompanyOrderProductDiyPrice($company_order_product_diy_price_params);
		
		
		//开始拼接
		$data = [
				'company_order_product'=>$company_order_product_result,//分公司产品
				'company_order_product_team'=>$company_order_product_team_result, //团队产品
				'company_order_product_source'=>$company_order_product_source_result, //资源
				'company_order_product_diy'=>$company_order_product_diy_result,
				'company_order_product_diy_price'=>$company_order_product_diy_price_result,
		];
		return $data;
	}
	
	
	/**
	 * 同步应收应付到团队产品以及资源
	 */
	public function updateReceivableAndCopeToCompanyOrderProduct($params){
	
		 
		$company_order_number = $params['company_order_number'];
		
		$cope_result =[];
		//获取应付
		//团队应付
		$cope_params = [
			'status'=>1,
			'fee_type_type'=>104,
			'order_number'=>$company_order_number,
				 
				 
		];
		$cope_result1 = $this->_cope->getCope($cope_params);
		//其他应付
		$cope_params = [
			'status'=>1,
			'fee_type_type'=>207,
			'order_number'=>$company_order_number,
	
		];
		$cope_result2 = $this->_cope->getCope($cope_params);
		$cope_result = array_merge($cope_result1,$cope_result2);
	
		
		//首先获取有多少团队产品
		$team_product_array = [];
		for($i=0;$i<count($cope_result);$i++){
			$team_product_id = $cope_result[$i]['team_product_id'];
			if(!empty($team_product_id)){
				if(!in_array($team_product_id, $team_product_array)){
					$team_product_array[] = $team_product_id;
				}
			}
		}
		
		
		
		//把每个团队产品编号 状态变更为0
		for($i=0;$i<count($team_product_array);$i++){
			$update_status = [
				'company_order_number'=>$company_order_number,
				'team_product_id'=>$team_product_array[$i],
				'status'=>0,
				'settlement_type'=>1	
			
			];
	
			$this->_company_order_product_team->updateCompanyOrderTeamStatus($update_status);
			 
			$this->_company_order_product_source->updateCompanyOrderSourceStatus($update_status);
	
	
		}
		 
	
		
		for($i=0;$i<count($cope_result);$i++){
			$team_product_id = $cope_result[$i]['team_product_id'];
	
	
			if(!empty($team_product_id)){
				if(!empty($cope_result[$i]['source_type_id'])){
					$source_params = [
							'supplier_type_id'=>$cope_result[$i]['source_type_id'],
							'source_number'=>$cope_result[$i]['source_number']
					];
						
					$source_result = $this->_source_service->getSource($source_params);
						
					//开始走修改逻辑
					$update_params = [
							'cope_number'=>$cope_result[$i]['cope_number'],
							'status'=>1,
							'source_cost'=>$cope_result[$i]['cope_money'],
							'cost_currency_id'=>$cope_result[$i]['cope_currency_id'],
							'source_name'=>$cope_result[$i]['product_name'],
							'supplier_type_id'=>$cope_result[$i]['source_type_id'],
							'source_id'=>$source_result['source_id'],
							'now_user_id'=>$params['now_user_id']
					];
						
					$result = $this->_company_order_product_source->updateCompanyOrderSourceByCopeNumber($update_params);
						
					if($result == 1){//修改成功
					
					}else{//如果没修改成功则走新增
					
						$update_params = [
								'cope_number'=>$cope_result[$i]['cope_number'],
								'status'=>1,
								'source_cost'=>$cope_result[$i]['cope_money'],
								'cost_currency_id'=>$cope_result[$i]['cope_currency_id'],
								'source_name'=>$cope_result[$i]['product_name'],
								'supplier_type_id'=>$cope_result[$i]['source_type_id'],
								'source_id'=>$source_result['source_id'],
								'now_user_id'=>$params['now_user_id'],
								'team_product_id'=>$team_product_id,
								'is_receivable_company'=>1,
								'company_order_number'=>$company_order_number,
								'supplier_name'=>$source_result['supplier_name'],
					
						];
						if($cope_result[$i]['fee_type_type']==207){
							$update_params['team_product_receivable_type'] = 2;
						}else{
							$update_params['team_product_receivable_type'] = 1;
						}
						$rr = $this->_company_order_product_source->addCompanyOrderProductSource($update_params);
					
					}
				}else{
					
					
					
					//开始走修改逻辑
					$update_params = [
							'cope_number'=>$cope_result[$i]['cope_number'],
							'status'=>1,
							'team_product_cost'=>$cope_result[$i]['cope_money'],
							'team_product_cost_univalence'=>$cope_result[$i]['cope_money'],
							'cost_currency_id'=>$cope_result[$i]['cope_currency_id'],
							'team_product_name'=>$cope_result[$i]['product_name'],
								
					
							'now_user_id'=>$params['now_user_id']
					];
						
					$result = $this->_company_order_product_team->updateCompanyOrderTeamByCopeNumber($update_params);
				
					if($result == 1){//修改成功
					
					}else{//如果没修改成功则走新增
						
						//通过公司ID查询公司名称
						$company_params['company_id']=$cope_result[$i]['receivable_object_id'];
						$company_result = $this->_company->getCompany($company_params);
						
						
						$update_params = [
								'cope_number'=>$cope_result[$i]['cope_number'],
								'status'=>1,
								'team_product_cost'=>$cope_result[$i]['cope_money'],
								'team_product_cost_univalence'=>$cope_result[$i]['cope_money'],
								'cost_currency_id'=>$cope_result[$i]['cope_currency_id'],
								'team_product_name'=>$cope_result[$i]['product_name'],
								'now_user_id'=>$params['now_user_id'],
								'team_product_id'=>$team_product_id,
								'is_receivable_company'=>1,
								'company_order_number'=>$company_order_number,
								'supplier_name'=>$company_result[0]['company_name'],
								'settlement_type'=>1,
							
					
						];
					
						if($cope_result[$i]['fee_type_type']==207){
							$update_params['team_product_receivable_type'] = 2;
						}else{
							$update_params['team_product_receivable_type'] = 1;
						}
						$rr = $this->_company_order_product_team->addCompanyOrderProductTeam($update_params);
						
					}
					
				}
				
		
			}
	
		}
	
		return 1;
	}
	
	/**
	 * 修改公司订单团队产品成本以及报价
	 */
    public function updateCompanyOrderTeamCost($params){
    	
    	//获取订单基本信息
    	$company_order_params['company_order_number'] = $params['company_order_number'];
    	$company_order_result = $this->_company_order->getCompanyOrder($company_order_params);
    	//
    	$customer_result = $this->_company_order_customer->getCompanyOrderCustomer($params);
    	$customer_count = count($customer_result);
    	
  
    	//获取团队产品
    	$company_order_product = $this->getCompanyOrderProduct($params);
    	
    	$company_order_product_result = $company_order_product['company_order_product'];
    	 
    	for($i=0;$i<count($company_order_product_result);$i++){
    		//通过分公司编号获取相应信息
    	
    		$branch_product_service_params = [
    			'branch_product_number'=>$company_order_product_result[$i]['branch_product_number'],
    			'now_user_id'=>$params['now_user_id']	
    		];
    	
    		$branch_product_service_result = $this->_branch_product_service->getBranchProduct($branch_product_service_params);
    		$update_company_order_product_params = [
    		
    				'company_order_product_id'=>$company_order_product_result[$i]['company_order_product_id'],
    				'now_user_id'=>$params['now_user_id'],
    		
    		];
    		if($company_order_result[0]['channel_type'] == 1){//代理
    			$update_company_order_product_params['branch_product_price'] = $branch_product_service_result[0]['distributor_price']*$customer_count;
    			$update_company_order_product_params['price_before_tax'] = $branch_product_service_result[0]['distributor_price']*$customer_count;
    			
    		}else{
    			$update_company_order_product_params['branch_product_price'] = $branch_product_service_result[0]['customer_price']*$customer_count;
    			$update_company_order_product_params['price_before_tax'] = $branch_product_service_result[0]['customer_price']*$customer_count;
    			 
    		
    		}

    
    		$this->_company_order_product->updateCompanyOrderProductById($update_company_order_product_params);
    	
    	}
    	
    	
    	$company_order_product_team = $company_order_product['company_order_product_team'];
    	
    	for($i=0;$i<count($company_order_product_team);$i++){
    		
    		$update_company_order_team_params = [
    			'team_product_cost'=>	$company_order_product_team[$i]['team_product_cost_univalence']*$customer_count,
    			'company_order_product_team_id'=>$company_order_product_team[$i]['company_order_product_team_id'],
    			'now_user_id'=>$params['now_user_id'],
    			'team_product_price'=>	$company_order_product_team[$i]['team_product_cost_univalence']*$customer_count,
    			'price_before_tax'=>$company_order_product_team[$i]['team_product_cost_univalence']*$customer_count,
    		];
    	
    		$this->_company_order_product_team->updateCompanyOrderTeam($update_company_order_team_params);
    		
    		//再修改 资源里的金钱
    		
    		$update_company_order_source_params = [
    		
    				'company_order_product_team_id'=>$company_order_product_team[$i]['company_order_product_team_id'],
    				'now_user_id'=>$params['now_user_id'],
    				'source_price'=>	$company_order_product_team[$i]['team_product_cost_univalence']*$customer_count,
    				'price_before_tax'=>$company_order_product_team[$i]['team_product_cost_univalence']*$customer_count,
    		];
    		 
    		$this->_company_order_product_source->updateCompanyOrderSourceByCompanyOrderProductTeamId($update_company_order_source_params);
    		
    		
    		
    	}
    	$company_order_product_source = $company_order_product['company_order_product_source'];

    	for($i=0;$i<count($company_order_product_source);$i++){
    		if($company_order_product_source[$i]['supplier_type_id']!=11 && !empty($company_order_product_source[$i]['supplier_type_id']) ){
    			$company_order_product_source_params = [
    					'source_cost'=>	$company_order_product_source[$i]['source_cost_univalence']*$customer_count,
    					'company_order_product_source_id'=>$company_order_product_source[$i]['company_order_product_source_id'],
    					'now_user_id'=>$params['now_user_id'],
    					'price_before_tax'=>$company_order_product_source[$i]['source_cost_univalence']*$customer_count,
    					
    			];
    	
    			$this->_company_order_product_source->updateCompanyOrderSource($company_order_product_source_params);
    		}
    	}
    	
    }
    
    
    /**
     * 获取 订单的应收实收应付实付
     */
    public function getCompanyOrderReceivableCopeTrueReceivableTrueCope($params){
    	
    	for($i=0;$i<count($params);$i++){//首先获取公司币种 以及币种ID
    		$company_params = [
    			'company_id'=>$params[$i]['company_id']	
    		];
    		$company_result = $this->_company->getCompany($company_params);
    		$currency_id = $company_result[0]['currency_id'];
    		$currency_params = [
    			'currency_id'=>$currency_id	
    		];
    		$currency_result = $this->_currency->getCurrency($currency_params);
    		$params[$i]['currency_id'] = $currency_result[0]['currency_id'];
    		$params[$i]['currency_name'] = $currency_result[0]['currency_name'];
    		
    		
    		$receivable_money = 0;
    		$cope_money = 0;
    		$true_receivable = 0;
    		$true_cope = 0;
    		//获取应收
    		
    		$receivable_params = [
    			'status'=>1,
    			'order_number'=>$params[$i]['company_order_number'],
    			'company_id'=>$params[$i]['company_id']		
    		];
    		$receivable_result = $this->_receivable->getReceivable($receivable_params);
    	
    		for($j=0;$j<count($receivable_result);$j++){
    			if($receivable_result[$j]['receivable_currency_id']==$currency_result[0]['currency_id']){
    				$receivable_money+=$receivable_result[$j]['receivable_money'];
    			}else{
    				$proportion = $this->_proportion_service->getProportion($receivable_result[$j]['receivable_currency_id'],$currency_result[0]['currency_id']);
    				$receivable_money+= $receivable_result[$j]['receivable_money']*$proportion;
    			}
    		}
    		
    		$cope_params = [
    			'status'=>1,
    			'order_number'=>$params[$i]['company_order_number']
    		];
    		$cope_result = $this->_cope->getCope($cope_params);
    		
    		
    		
    		for($j=0;$j<count($cope_result);$j++){
    			if($cope_result[$j]['cope_currency_id']==$currency_result[0]['currency_id']){
    				$cope_money+=$cope_result[$j]['cope_money'];
    			}else{
    				$proportion = $this->_proportion_service->getProportion($cope_result[$j]['cope_currency_id'],$currency_result[0]['currency_id']);
    			
    				$cope_money+= $cope_result[$j]['cope_money']*$proportion;
    			}
    		}
    
//     		//实收
    		$receivable_info_params =[
    			'status'=>1,
    			'company_order_number'=>$params[$i]['company_order_number']	
    		];
    		$receivable_info_result = $this->_receivable_info->getReceivableInfo($receivable_info_params);
    		
    		for($j=0;$j<count($receivable_info_result);$j++){
    			if($receivable_info_result[$j]['payment_currency_id']==$currency_result[0]['currency_id']){
    				$true_receivable+=$receivable_info_result[$j]['payment_money'];
    			}else{
    				$proportion = $this->_proportion_service->getProportion($receivable_info_result[$j]['payment_currency_id'],$currency_result[0]['currency_id']);
    				$true_receivable+= $receivable_info_result[$j]['payment_money']*$proportion;
    			}
    		}
    		
//     		//实付
    		$cope_info_params =[
    			'status'=>1,
    			'company_order_number'=>$params[$i]['company_order_number']
    		];
    		$cope_info_result = $this->_cope_info->getCopeInfo($cope_info_params);
    		for($j=0;$j<count($cope_info_result);$j++){
    			if($cope_info_result[$j]['receivable_currency_id']==$currency_result[0]['currency_id']){
    				$true_cope+=$cope_info_result[$j]['receivable_money'];
    			}else{
    				$proportion = $this->_proportion_service->getProportion( $cope_info_result[$j]['receivable_currency_id'],$currency_result[0]['currency_id']);
    				$true_cope+= $cope_info_result[$j]['receivable_money']*$proportion;
    			}
    		}
    		
    		$params[$i]['receivable_money'] = $receivable_money;
    		$params[$i]['cope_money'] = $cope_money;
    		$params[$i]['true_receivable'] = $true_receivable;
    		$params[$i]['true_cope'] = $true_cope;
    	}
    	
    	return $params;

    	
    	
    }
    /**
     * 获取订单收款明细
     * 0425分支
     * 胡伊敏
     */
    public function getCompanyOrderReceivableInfo($params){
    	$return_data = [];
    	//获取本公司货币ID
    	$user_params = [
    		'user_id'=>$params['now_user_id']
    	];
    	
    	
    	$user_result = $this->_user->getUser($user_params);
    	$company_currency_id = $user_result[0]['company_currency_id'];
    	
    	 
    	//获取公司订单基本信息
    	$company_order_result = $this->_company_order->getCompanyOrder($params);
    	 
    	$company_order_result = $company_order_result[0];
    	$company_order_id = $company_order_result['company_order_id'];
    	//创建人姓名
    	$return_data['create_user_name'] = $company_order_result['create_user_name'];
    	
    	$params['is_not_source_type_14']=2;
    	$data = $this->getCompanyOrderProduct($params);
    	 
    	//需要循环4个地方 分公司产品，资源（自费） 自定义成本 自定义报价
    	 
    	$company_order_product = $data['company_order_product'];
    	 
    	$price_before_tax = 0;//税前价格
    	$tax = 0;//税
    	$price =0;//含税价
    	$sale_receivable = 0;//销售收款
    	$finance_receivable = 0;//财务已收
    	$cost = 0;//成本
    	$yifu = 0;//已付
    	$weifu = 0;//未付
    	
    	$huiduisunyi = 0;
    	
    	
    	

    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	for($i=0;$i<count($company_order_product);$i++){
    	
    		//判断是否有已收
    		if(!empty($company_order_product[$i]['receivable_number'])){
    			$receivable_info_params = [
    					'receivable_number'=>$company_order_product[$i]['receivable_number']
    			];
    			$receivable_info_result  = $this->_receivable_info->getReceivableInfo($receivable_info_params);
    			for($j=0;$j<count($receivable_info_result);$j++){
    	
    				if($receivable_info_result[$j]['payment_currency_id'] == $company_currency_id){
    					$pm = $receivable_info_result[$j]['payment_money'];
    				}else{
    	
    					$pm = $this->_proportion_service->getProportion($receivable_info_result[$j]['payment_currency_id'],$company_currency_id);
    				}
    				if($receivable_info_result[$j]['receivable_info_type']==2){//普通收款
    					$sale_receivable+=$pm;
    				}
    				$finance_receivable+=$pm;
    			}
    		}
    	
    		if($company_currency_id == $company_order_product[$i]['price_currency_id']){
    			$pbt = $company_order_product[$i]['price_before_tax'];
    			$p = $company_order_product[$i]['branch_product_price'];
    	
    		}else{
    			 
    			$proportion = $this->_proportion_service->getProportion($company_order_product[$i]['price_currency_id'],$company_currency_id);
    			$pbt = $company_order_product[$i]['price_before_tax']*$proportion;
    			$p = $company_order_product[$i]['branch_product_price']*$proportion;
    			$huidui+= $p;
    			 
    		}
			
    		//计算成本
//     		if($company_currency_id == $company_order_product[$i]['cost_currency_id']){
    			 
//     			$c = $company_order_product[$i]['branch_product_cost'];
    	
//     		}else{
    	
//     			$proportion = $this->_proportion_service->getProportion($company_order_product[$i]['cost_currency_id'],$company_currency_id);
//     			$c = $company_order_product[$i]['branch_product_cost']*$proportion;
    	
    	
//     		}
    	
    		$price_before_tax+=$pbt;
    		$tax+= $p-$pbt;
    		$price+=$p;
    	
    	
    	}
    
    	
    	$company_order_product_team = $data['company_order_product_team'];
  
    	for($i=0;$i<count($company_order_product_team);$i++){
    		//如果团队产品 是真实结算 
    		if($company_order_product_team[$i]['settlement_type']==2){
    			continue;
    			exit();
    		}
    		
    		if($company_order_product_team[$i]['is_type'] == 2){
    			
    			if($company_currency_id == $company_order_product_team[$i]['price_currency_id']){
    				$pbt = $company_order_product_team[$i]['price_before_tax'];
    				$p = $company_order_product_team[$i]['team_product_price'];
    				 
    			}else{
    			
    				$proportion = $this->_proportion_service->getProportion($company_order_product[$i]['price_currency_id'],$company_currency_id);
    				$pbt = $company_order_product_team[$i]['price_before_tax']*$proportion;
    				$p = $company_order_product_team[$i]['team_product_price']*$proportion;
    				$huidui+= $p;
    			
    			}
    			$price+=$p;
    			$price_before_tax+=$pbt;
    			$tax+= $p-$pbt;
    			//判断是否有已收
    			if(!empty($company_order_product_team[$i]['receivable_number'])){
    				$receivable_info_params = [
    						'receivable_number'=>$company_order_product_team[$i]['receivable_number']
    				];
					
					
    				$receivable_info_result  = $this->_receivable_info->getReceivableInfo($receivable_info_params);
    				
					
					for($j=0;$j<count($receivable_info_result);$j++){
    					 
    					if($receivable_info_result[$j]['payment_currency_id'] == $company_currency_id){
    						$pm = $receivable_info_result[$j]['payment_money'];
    					}else{
    							
    						$pm = $this->_proportion_service->getProportion($receivable_info_result[$j]['payment_currency_id'],$company_currency_id);
    					}
    					if($receivable_info_result[$j]['receivable_info_type']==2){//普通收款
    						$sale_receivable+=$pm;
    					}
    					$finance_receivable+=$pm;
    				}
    			}
    		}else{
    			//计算成本
    			if($company_currency_id == $company_order_product_team[$i]['cost_currency_id']){
    			
    				$c = $company_order_product_team[$i]['team_product_cost'];
    			
    			}else{
    			
    				$proportion = $this->_proportion_service->getProportion($company_order_product_team[$i]['cost_currency_id'],$company_currency_id);
    				$c = $company_order_product_team[$i]['team_product_cost']*$proportion;
    			
    			
    			}
    			$cost+=$c;
    			

  
//     			//通过付款编号 查询cope表
    			if(!empty($company_order_product_team[$i]['cope_number'])){//如果有应付编号
    				$cope_params = [
    					'cope_number'=>$company_order_product_team[$i]['cope_number']		
    				];
    				$cope_result = $this->_cope->getCope($cope_params);
    			
     				if(!empty($cope_result)){
     					if($company_currency_id ==$cope_result[0]['cope_currency_id'] ){
     							$yifu+=$cope_result[0]['true_receipt'];
     					}else{
     						$proportion = $this->_proportion_service->getProportion($cope_result[0]['cope_currency_id'],$company_currency_id);
     						$c = $cope_result[0]['true_receipt']*$proportion;
     						$yifu+=$c;
     						
     					}
     				}

     			}
    		}


    	}
    	
    	
    	
    	$company_order_product_source = $data['company_order_product_source'];
	
    	
    	for($i=0;$i<count($company_order_product_source);$i++){
    	
    		if($company_order_product_source[$i]['supplier_type_id']==11){
    	
    			//判断是否有已收
    			if(!empty($company_order_product_source[$i]['receivable_number'])){
    				$receivable_info_params = [
    						'receivable_number'=>$company_order_product_source[$i]['receivable_number']
    				];
					
			
    				$receivable_info_result  = $this->_receivable_info->getReceivableInfo($receivable_info_params);
   
					for($j=0;$j<count($receivable_info_result);$j++){
    	
    					if($receivable_info_result[$j]['payment_currency_id'] == $company_currency_id){
    						$pm = $receivable_info_result[$j]['payment_money'];
    					}else{
    							
    						$pm = $this->_proportion_service->getProportion($receivable_info_result[$j]['payment_currency_id'],$company_currency_id);
    					}
    					if($receivable_info_result[$j]['receivable_info_type']==2){//普通收款
    						$sale_receivable+=$pm;
    					}
    					$finance_receivable+=$pm;
    				}
    			}
    			 
    			 
    			 
    			 
    			if($company_currency_id == $company_order_product_source[$i]['price_currency_id']){
    				$pbt = $company_order_product_source[$i]['price_before_tax'];
    				$p = $company_order_product_source[$i]['source_price'];
    			}else{
    				 
    				$proportion = $this->_proportion_service->getProportion($company_order_product_source[$i]['price_currency_id'],$company_currency_id);
    				$pbt = $company_order_product_source[$i]['price_before_tax']*$proportion;
    				$p = $company_order_product_source[$i]['source_price']*$proportion;
    				$huidui+= $p;
    			}
    			$price_before_tax+=$pbt;
    			$tax+= $p-$pbt;
    			$price+=$p;
    			 
    		}
    	
    		
    		//计算成本
    		if($company_order_product_source[$i]['supplier_type_id'] == 14 ){
	
				if(empty($company_order_product_source[$i]['receivable_number'])){
					
					continue;
				}
				 
    			$receivable_info_params = [
    					'receivable_number'=>$company_order_product_source[$i]['receivable_number']
    			];
			
    			$receivable_info_result  = $this->_receivable_info->getReceivableInfo($receivable_info_params);
    						
				
				for($j=0;$j<count($receivable_info_result);$j++){
    				 
    				if($receivable_info_result[$j]['payment_currency_id'] == $company_currency_id){
    					$pm = $receivable_info_result[$j]['payment_money'];
    				}else{
    						
    					$pm = $this->_proportion_service->getProportion($receivable_info_result[$j]['payment_currency_id'],$company_currency_id);
    				}
    				if($receivable_info_result[$j]['receivable_info_type']==2){//普通收款
    					$sale_receivable+=$pm;
    				}
    				$finance_receivable+=$pm;
    			}
    			
    			
    			continue;
    		}
    		
    		if($company_currency_id == $company_order_product_source[$i]['cost_currency_id']){
    					
    			$c = $company_order_product_source[$i]['source_cost'];
    				 
    		}else{
    				 
    			$proportion = $this->_proportion_service->getProportion($company_order_product_source[$i]['cost_currency_id'],$company_currency_id);
    			$c = $company_order_product_source[$i]['source_cost']*$proportion;
    				 
    				 
    		}
    		$cost+=$c;
    		
    		//     			//通过付款编号 查询cope表
    		if(!empty($company_order_product_source[$i]['cope_number'])){//如果有应付编号
    			$cope_params = [
    					'cope_number'=>$company_order_product_source[$i]['cope_number']
    			];
    			$cope_result = $this->_cope->getCope($cope_params);
    			 
    			if(!empty($cope_result)){
    				if($company_currency_id ==$cope_result[0]['cope_currency_id'] ){
    					$yifu+=$cope_result[0]['true_receipt'];
    				}else{
    					$proportion = $this->_proportion_service->getProportion($cope_result[0]['cope_currency_id'],$company_currency_id);
    					$c = $cope_result[0]['true_receipt']*$proportion;
    					$yifu+=$c;
    						
    				}
    			}
    		
    		}    	
    	
    	}
	
    	$company_order_product_diy = $data['company_order_product_diy'];
    	for($i=0;$i<count($company_order_product_diy);$i++){
    		//判断是否有已收
//     		if(!empty($company_order_product_diy[$i]['receivable_number'])){
//     			$receivable_info_params = [
//     					'receivable_number'=>$company_order_product_diy[$i]['receivable_number']
//     			];
//     			$receivable_info_result  = $this->_receivable_info->getReceivableInfo($receivable_info_params);
//     			for($j=0;$j<count($receivable_info_result);$j++){
    	
//     				if($receivable_info_result[$j]['payment_currency_id'] == $company_currency_id){
//     					$pm = $receivable_info_result[$j]['payment_money'];
//     				}else{
    	
//     					$pm = $this->_proportion_service->getProportion($receivable_info_result[$j]['payment_currency_id'],$company_currency_id);
//     				}
//     				if($receivable_info_result[$j]['receivable_info_type']==2){//普通收款
//     					$sale_receivable+=$pm;
//     				}
//     				$finance_receivable+=$pm;
//     			}
//     		}
//     		if($company_currency_id == $company_order_product_diy[$i]['price_currency_id']){
//     			$pbt = $company_order_product_diy[$i]['price_before_tax'];
//     			$p = $company_order_product_diy[$i]['diy_price'];
//     		}else{
    				
//     			$proportion = $this->_proportion_service->getProportion($company_order_product_diy[$i]['price_currency_id'],$company_currency_id);
//     			$pbt = $company_order_product_diy[$i]['price_before_tax']*$proportion;
//     			$p = $company_order_product_diy[$i]['diy_price']*$proportion;
//     			$huidui+= $p;
//     		}
    	
    		//计算成本
    		if($company_currency_id == $company_order_product_diy[$i]['cost_currency_id']){
    	
    			$c = $company_order_product_diy[$i]['diy_cost'];
    	
    		}else{
    	
    			$proportion = $this->_proportion_service->getProportion($company_order_product_diy[$i]['cost_currency_id'],$company_currency_id);
    			$c = $company_order_product_diy[$i]['diy_cost']*$proportion;
    	
    	
    		}
    	
//     		$price_before_tax+=$pbt;
//     		$tax+= $p-$pbt;
//     		$price+=$p;
    		$cost+=$c;
    		//     			//通过付款编号 查询cope表
    		if(!empty($company_order_product_diy[$i]['cope_number'])){//如果有应付编号
    			$cope_params = [
    					'cope_number'=>$company_order_product_diy[$i]['cope_number']
    			];
    			$cope_result = $this->_cope->getCope($cope_params);
    		
    			if(!empty($cope_result)){
    				if($company_currency_id ==$cope_result[0]['cope_currency_id'] ){
    					$yifu+=$cope_result[0]['true_receipt'];
    				}else{
    					$proportion = $this->_proportion_service->getProportion($cope_result[0]['cope_currency_id'],$company_currency_id);
    					$c = $cope_result[0]['true_receipt']*$proportion;
    					$yifu+=$c;
    		
    				}
    			}
    		
    		}    	
    	}
    	$company_order_product_diy_price = $data['company_order_product_diy_price'];
    	 
    	for($i=0;$i<count($company_order_product_diy_price);$i++){
    		 
    		//判断是否有已收
    		if(!empty($company_order_product_diy_price[$i]['receivable_number'])){
    			$receivable_info_params = [
    					'receivable_number'=>$company_order_product_diy_price[$i]['receivable_number']
    			];
    			$receivable_info_result  = $this->_receivable_info->getReceivableInfo($receivable_info_params);
    			for($j=0;$j<count($receivable_info_result);$j++){
    					
    				if($receivable_info_result[$j]['payment_currency_id'] == $company_currency_id){
    					$pm = $receivable_info_result[$j]['payment_money'];
    				}else{
    	
    					$pm = $this->_proportion_service->getProportion($receivable_info_result[$j]['payment_currency_id'],$company_currency_id);
    				}
    				if($receivable_info_result[$j]['receivable_info_type']==2){//普通收款
    					$sale_receivable+=$pm;
    				}
    				$finance_receivable+=$pm;
    			}
    		}
    		 
    		if($company_currency_id == $company_order_product_diy_price[$i]['price_currency_id']){
    			$pbt = $company_order_product_diy_price[$i]['price_before_tax'];
    			$p = $company_order_product_diy_price[$i]['diy_price'];
    	
    		}else{
    			 
    			$proportion = $this->_proportion_service->getProportion($company_order_product[$i]['price_currency_id'],$company_currency_id);
    			$pbt = $company_order_product_diy_price[$i]['price_before_tax']*$proportion;
    			$p = $company_order_product_diy_price[$i]['diy_price']*$proportion;
    	
    			 
    		}
    		 
    		//计算成本
    		//     		if($company_currency_id == $company_order_product[$i]['cost_currency_id']){
    		 
    		//     			$c = $company_order_product[$i]['branch_product_cost'];
    		 
    		//     		}else{
    		 
    		//     			$proportion = $this->_proportion_service->getProportion($company_order_product[$i]['cost_currency_id'],$company_currency_id);
    		//     			$c = $company_order_product[$i]['branch_product_cost']*$proportion;
    		 
    		 
    		//     		}
    		 
    		$price_before_tax+=$pbt;
    		$tax+= $p-$pbt;
    		$price+=$p;
    		 
    		 
    	}	 
    	/**
    	 * 获取应收详情
    	 * @var unknown
    	 */
    	$receivable_info_params = [];
    	$receivable_info_params['company_order_number'] = $params['company_order_number'];
    	$receivable_info_result = $this->_receivable_info->getReceivableInfo($receivable_info_params);
    	

    	for($i=0;$i<count($receivable_info_result);$i++){
    		$huiduisunyi+=$receivable_info_result[$i]['exg_rate_gain'];
    	}
    	$return_data['price'] =$price_before_tax;
    	$return_data['tax'] = $tax;
    	$return_data['total'] = $price;
    	$return_data['sale_receivable'] = $sale_receivable;//销售收款
    	$return_data['finance_receivable'] = $finance_receivable;//财务已收
    	$return_data['miss_sale_receivable'] = $return_data['total']-$sale_receivable;
    	$return_data['miss_finance_receivable'] = $return_data['total']-$finance_receivable;
    	$return_data['cost'] = $cost;
    	$return_data['yifu'] = $yifu;
    	$return_data['weifu'] = $cost-$yifu;
    	$return_data['huiduisunyi'] = $huiduisunyi;
    	$return_data['maoli'] = $price-$cost;
    	return $return_data;
    }
    
    
    /**
     * 订单的信息随着团队产品变化而变化
     */
    public function CompanyOrderInfoChangeByTeamProduct($params){
    	//首先把团队产品ID字符串换成数组
    	$team_product_array = explode(',',$params['team_product_id']);
    	 
    	 
    	for($i=0;$i<count($team_product_array);$i++){
    		$team_product_id_params = [
    				'team_product_id'=>$team_product_array[$i]
    		];
			
			
    		$company_order_result = $this->_company_order_product_team->getCompanyOrderNumberByTeamProductId($team_product_id_params);
			
    		 
    		for($j=0;$j<count($company_order_result);$j++){
				
    			//开始判断订单是否锁住 如锁住则无法联动修改资源
    			 
    			if($company_order_result[$j]['locked']==1){
    				continue;
    				exit();
    			}else{
    	
    				$company_order_number = $company_order_result[0]['company_order_number'];
    	
    	
    				//通过团队产品ID获取团队产品信息
    				$team_product_result = $this->_team_product->getTeamProductBase($team_product_id_params);
    				//首先让订单下 的团队产品以及资源状态变更为0
    	
    	
    				$company_order_team_params = [
    						'company_order_number'=>$company_order_number,
    						'team_product_id'=>$team_product_result[0]['team_product_id'],
    						'settlement_type'=>$team_product_result[0]['settlement_type']
    				];
    				if($team_product_result[0]['settlement_type']==1){//一口价
    					$company_order_team_params['team_product_cost']=$team_product_result[0]['once_price'];
    				}else{//真实结算
    					$company_order_team_params['team_product_cost']=$team_product_result[0]['real_price'];
    				}
    				$company_order_team_params['team_product_cost_univalence'] = $company_order_team_params['team_product_cost'];
    	
    				$a = $this->_company_order_product_team->updateCompanyOrderTeam($company_order_team_params);
    	
    				//修改公司订单团队产品的成本价
    	
    				//把订单下的团队产品资源状态全部修改为0
    				$company_order_product_source_params = [
    						'company_order_number'=>$company_order_number,
    						'team_product_id'=>$team_product_result[0]['team_product_id'],
    						'status'=>0
    				];
    	
    				$this->_company_order_product_source->updateCompanyOrderSourceStatus($company_order_product_source_params);
    	
    				//开始获取资源
    				//开始查询团队产品里的资源插入数据库
    				$team_product_allocation_params = [
    						'team_product_id'=>	 $team_product_result[0]['team_product_id'],
    						'status'=>1
    				];
    				$team_product_allocation_result  = $this->_team_product_allocation->getTeamProductAllocation($team_product_allocation_params);
    				 
    				for($k=0;$k<count($team_product_allocation_result);$k++){
    					//获取资源信息
    					$source_info_params = [
    							'supplier_type_id'=>$team_product_allocation_result[$k]['supplier_type_id'],
    							'source_id'=>$team_product_allocation_result[$k]['source_id']
    					];
    					$source_result = $this->_source_service->getSourceInfo($source_info_params);
    	
    	
    					$k_data_params['company_order_number'] = $company_order_number;
    					$k_data_params['now_user_id'] = $params['now_user_id'];
    					$k_data_params['supplier_type_id'] = $team_product_allocation_result[$k]['supplier_type_id'];
    					$k_data_params['source_id'] = $team_product_allocation_result[$k]['source_id'];
    					$k_data_params['source_name'] = $source_result[0]['source_name'];
    					if($team_product_allocation_result[$k]['supplier_type_id']==11){
    							
    						//开始判断是否是本公司
    						if($params['user_company_id'] ==  $source_result[0]['source_name']){
    							$k_data_params['source_cost_univalence'] = $source_result[0]['normal_price'];
    						}else{
    							$k_data_params['source_cost_univalence'] = $team_product_allocation_result[$k]['source_total_price'];
    						}
    							
    						$k_data_params['source_cost'] = 0;
    							
    						$k_data_params['source_price'] = 0;
    						$k_data_params['price_before_tax'] = 0;
    					}else{
    						if($team_product_result[0]['settlement_type']==1){
    							continue;//跳出当前循环
    							exit();
    						}
    						//开始判断是否是本公司
    						if($params['user_company_id'] ==  $source_result[0]['source_name']){
    							$k_data_params['source_cost_univalence'] = $source_result[0]['normal_price'];
    						}else{
    							$k_data_params['source_cost_univalence'] = $team_product_allocation_result[$k]['source_total_price'];
    						}
    							
    							
    						$k_data_params['source_cost'] = $k_data_params['source_cost_univalence'] *$company_order_customer_count;
    						//$k_data_params['source_cost_univalence'] = $team_product_allocation_result[$k]['source_total_price'];
    						$k_data_params['source_price'] = $k_data_params['source_cost_univalence']*$company_order_customer_count;
    						$k_data_params['price_before_tax'] = $k_data_params['source_cost_univalence']*$company_order_customer_count;
    							
    					}
    					$k_data_params['price_currency_id'] = $team_product_allocation_result[$k]['payment_currency_id'];
    					$k_data_params['cost_currency_id'] = $team_product_allocation_result[$k]['payment_currency_id'];
    					$k_data_params['supplier_name'] = $source_result[0]['supplier_name'];
    					$k_data_params['team_product_id'] =$team_product_result[0]['team_product_id'];
    					$k_data_params['team_product_allocation_id'] = $team_product_allocation_result[$k]['team_product_allocation_id'];
    					//首先修改成本价
    					$company_order_source_params=[
    							'team_product_id'=>$team_product_result[0]['team_product_id'],
    							'company_order_number'=>$company_order_number,
    							'team_product_allocation_id'=>$team_product_allocation_result[$k]['team_product_allocation_id'],
    	
    							'source_cost'=>$k_data_params['source_cost_univalence'],
    							'source_cost_univalence'=>	$k_data_params['source_cost_univalence'],
    							'status'=>1
    					];
    	
    					$source_result = $this->_company_order_product_source->updateCompanyOrderSourceByCompanyOrderNumberAndTeamProductId($company_order_source_params);
    	
    					if($source_result == 0){
    						$this->_company_order_product_source->addCompanyOrderProductSource($k_data_params);
    					}
    						
    				}
    				
    				$company_order_name_update_params = [
    						'company_order_number'=>$company_order_number
    				];
    				$this->updateCompanyOrderName($company_order_name_update_params);
    			}
				$company_order_team_cost_params['company_order_number'] = $company_order_number;
				$company_order_team_cost_params['now_user_id'] = $params['now_user_id'];
    	
				$this->updateCompanyOrderTeamCost($company_order_team_cost_params);
    		}
 
    	}
    	return 1;
    }
    //修改公司订单名称
    public function updateCompanyOrderName($params){
    	
    	$params['status']=1;
    	$order_name='';
    	//获取分公司产品
    	$company_order_product_result = $this->_company_order_product->getCompanyOrderProduct($params);
    	
    	for($j=0;$j<count($company_order_product_result);$j++){
    	
    		$order_name.="+".$company_order_product_result[$j]['branch_product_name'];
    	}
    	//获取团队产品
    	$company_order_product_team_result = $this->_company_order_product_team->getCompanyOrderProductTeam($params);
    	 
    	for($j=0;$j<count($company_order_product_team_result);$j++){
    		 
    		$order_name.="+".$company_order_product_team_result[$j]['team_product_name'];
    	}
    	
    	
    	//获取资源
    	$company_order_source_result = $this->_company_order_product_source->getCompanyOrderProductSource($params);
    	
    	for($j=0;$j<count($company_order_source_result);$j++){
    		$order_name.="+".$company_order_source_result[$j]['source_name'];
    	}
    	
    	//获取其他
    	$company_order_diy_result = $this->_company_order_product_diy->getCompanyOrderProductDiy($params);
    	
    	for($j=0;$j<count($company_order_diy_result);$j++){
    		$order_name.="+".$company_order_diy_result[$j]['diy_name'];
    	}
    	 
    	$order_name = trim($order_name,'+');
    	$company_order_params = [
			'company_order_number'=>$params['company_order_number'],
    	    'order_name'=>$order_name	
    	];
    	$this->_company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
    }
    
    

}