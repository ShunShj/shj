<?php
namespace app\index\service;

use app\index\model\branchcompany\Customer;
use app\index\model\branchcompany\CompanyOrder;
use app\index\model\branchcompany\CompanyOrderCustomer;
use app\index\model\branchcompany\CompanyOrderProduct;
use app\index\model\branchcompany\CompanyOrderProductTeam;
use app\index\model\branchcompany\CompanyOrderDiy;
use app\index\model\branchcompany\CompanyOrderProductSource;
use app\index\model\branchcompany\CompanyOrderRelation;
use app\index\model\branchcompany\CompanyOrderProductDiy;
use app\index\model\branchcompany\CompanyOrderCustomerlineup;
use app\index\model\branchcompany\CompanyOrderFlight;
use app\index\model\branchcompany\CompanyOrderAccommodation;
use app\index\model\finance\Receivable;
use app\index\model\finance\ReceivableCustomer;
use app\index\model\finance\Cope;
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
use app\index\service\Source;
use think\Model;
use app\common\help\Help;
use think\Hook;

class CompanyOrderCustomerService{
	
	
	private $_company_order_product;
	private $_company_order_customer;
	private $_company_order_customer_lineup;
	private $_company_order_product_team;
	private $_company_order_product_source;
	private $_company_order_product_diy;
	private $_company_order_flight;
	private $_company_order_accommodation;
	private $_hotel;
	private $_team_product;
	private $_team_product_allocation;
	private	$_source;
	private	$_source_price;
	private $_distributor;
	private $_company_order;
	private $_customer;
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
		$this->_company_order_flight = new CompanyOrderFlight();
		$this->_company_order_customer_lineup =new CompanyOrderCustomerlineup();
		$this->_company_order_accommodation = new CompanyOrderAccommodation();
		$this->_customer = new Customer();
	}
	
	//添加游客信息
	public function addCustomer($params){
	//首先根据证件类型 证件号查询数据
    	$customer_data = [
    		'passport_number'=>$params['passport_number'],
    
    	];

    	
    	$customer_data_result = $this->_customer->getCustomer($customer_data);
  
    	if(count($customer_data_result)>0){//假如有数据
    		
    		//获取用户ID
    		$update_customer_data = [
    			'customer_id'=>$customer_data_result[0]['customer_id'],
    			'customer_first_name'=>$params['customer_first_name'],
    			'customer_last_name'=>$params['customer_last_name'],
    			'english_first_name'=>$params['english_first_name'],
    			'english_last_name'=>$params['english_last_name'],
    			'customer_type'=>$params['customer_type'],
    			'gender'=>$params['gender'],
				'country_id'=>$params['country_id'],
    			'language_id'=>$params['language_id'],
    			'phone'=>$params['phone'],
    			'email'=>$params['email'],
    			'term_of_validity'=>$params['term_of_validity'],
    			'now_user_id'=>$params['now_user_id'],
    			'passport_number'=>$params['passport_number'],
    			'card_type'=>$params['card_type'],
    			'card_number'=>$params['card_number'],
    		];
    		$result = $this->_customer->updateCustomerByCustomerId($update_customer_data);
    		$customer_id = $customer_data_result[0]['customer_id'];
    	
    	}else{//假如没数据
    
    		$add_customer_data = [    	
    			'customer_number'=>Help::getNumber(7),
    			'customer_first_name'=>$params['customer_first_name'],
    			'customer_last_name'=>$params['customer_last_name'],
    			'english_first_name'=>$params['english_first_name'],
    			'english_last_name'=>$params['english_last_name'],
    			'customer_type'=>$params['customer_type'],
    			'gender'=>$params['gender'],
    			'country_id'=>$params['country_id'],
    			'language_id'=>$params['language_id'],
    			'phone'=>$params['phone'],
    			'email'=>$params['email'],
    			'term_of_validity'=>$params['term_of_validity'],
    			'user_id'=>$params['now_user_id'],
    			'company_id'=>$params['company_id'],
    			'card_type'=>$params['card_type'],
    			'card_number'=>$params['card_number'],
    			'passport_number'=>$params['passport_number'],
    			'status'=>1
    		];
    		
    		
    		$customer_id = $this->_customer->addCustomer($add_customer_data);
    		
    	}
    
    	//开始查询公司订单是否有用户数据
    	$company_order_customer_data['customer_id'] = $customer_id;
    	$company_order_customer_data['company_order_number'] = $params['company_order_number'];
    	
    	
    	$company_order_customer_result = $this->_company_order_customer->getCompanyOrderCustomer($company_order_customer_data);
    	$company_order_customer_id = $company_order_customer_result[0]['customer_order_customer_id'];
    	if(count($company_order_customer_result)==0){//如果为空那插入
    		$company_order_customer_data['status'] =  1;
    		$company_order_customer_data['user_id'] =  $params['now_user_id'];
    		$company_order_customer_id = $this->_company_order_customer->addCompanyOrderCustomer($company_order_customer_data);
    	}

    	
  		//开始顾客航班信息
  		
    	$params['customer_id'] = $customer_id;
 
    	if(count($params['customer_flight_info'])>0){
    		$params['company_order_customer_id'] = $company_order_customer_id;
    		$company_order_flight = $this->_company_order_flight->addCompanyOrderFlight($params);
    		 
    		 
//    		if($company_order_flight!=1){//如果添加出错
//    			$this->outPutError(['msg' => "add customer flight error"],$params);
//    		}
    	}

    	
    	//开始添加顾客住宿信息
    
    	$customer_accommodation_info = $params['customer_accommodation_info'];
    	if(count($customer_accommodation_info)>0){
    		
    		$customer_accommodation_info['customer_id'] = $customer_id;
    		$customer_accommodation_info['company_order_customer_id'] = $company_order_customer_id;
    		$customer_accommodation_info['company_order_number'] = $params['company_order_number'];
    		$customer_accommodation_result = $this->_company_order_accommodation->addCompanyOrderCustomerAccommodation($customer_accommodation_info);
    		
    	}
    	

		
		
	}
}