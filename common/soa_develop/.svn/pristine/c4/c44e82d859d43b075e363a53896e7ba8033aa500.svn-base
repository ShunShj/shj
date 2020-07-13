<?php
namespace app\index\service;


use app\index\model\branchcompany\CompanyOrder;
use app\index\model\branchcompany\CompanyOrderCustomer;
use app\index\model\branchcompany\CompanyOrderProduct;
use app\index\model\branchcompany\CompanyOrderProductTeam;
use app\index\model\branchcompany\CompanyOrderDiy;
use app\index\model\branchcompany\CompanyOrderProductSource;
use app\index\model\branchcompany\CompanyOrderRelation;
use app\index\model\branchcompany\CompanyOrderProductDiy;
use app\index\model\branchcompany\CompanyOrderCustomerlineup;
use app\index\model\branchcompany\BranchProduct;
use app\index\model\branchcompany\BranchProductTeam;
use app\index\model\branchcompany\BranchProductSource;
use app\index\model\branchcompany\BranchProductRouteTemplate;
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
use app\index\model\system\User;
use app\index\service\ProportionService;

use app\index\service\Source;
use think\Model;
use app\common\help\Help;
use think\Hook;
use think\Controller;

class ProductService{
	
	
	private $_company_order_product;
	private $_company_order_customer;
	private $_company_order_customer_lineup;
	private $_company_order_product_team;
	private $_company_order_product_source;
	private $_company_order_product_diy;
	
	private $_team_product;
	private $_team_product_allocation;
	private	$_source;
	private	$_source_price;
	private $_distributor;
	private $_company_order;
	private $_receivable;
	private $_cope;
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
	private $_branch_product;
	private $_user;
	private $_branch_product_team;
	private $_branch_product_source;
	private $_branch_product_route_template;
	private $_proportion_service;
	
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
		$this->_receivable = new Receivable();
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
    	$this->_branch_product = new BranchProduct();
    	$this->_user = new User();
    	$this->_branch_product_team = new BranchProductTeam();
    	$this->_branch_product_source = new BranchProductSource();
    	$this->_branch_product_route_template =new BranchProductRouteTemplate();
    	$this->_proportion_service = new ProportionService();
	}
	//添加团队产品应收供应商
    public function addTeamProductReceivableSupplier($params){
		
    	$team_product_id = $params['team_product_id'];
    	$receivable_supplier_array = $params['receivable_supplier_array'];
    	 
    	 
    	$receivable_status = [
    		'team_product_id'=>$team_product_id,
    		'fee_type_type'=>208,
    				
    	];
    	$this->_receivable->updateStatusByData($receivable_status);
    	
    	for($i=0;$i<count($receivable_supplier_array);$i++){
			$receivable_supplier_params = [];

			$receivable_supplier_params = [
				'product_name' => $receivable_supplier_array[$i]['product_name'],
				'source_type_id'=>$receivable_supplier_array[$i]['source_type_id'],
				'payment_object_type'=>2,
				'payment_object_id'=>$receivable_supplier_array[$i]['payment_object_id'],
				'price'=>$receivable_supplier_array[$i]['price'],
				'unit'=>$receivable_supplier_array[$i]['unit'],
				'receivable_currency_id'=>$receivable_supplier_array[$i]['receivable_currency_id'],
				'receivable_money'=>$receivable_supplier_array[$i]['receivable_money'],
				'fee_type_type'=>208,
				'now_user_id'=>$params['now_user_id'],
				'company_id'=>$params['user_company_id'],
				'is_auto'=>1,
				'team_product_id'=>$team_product_id
			];
			if($receivable_supplier_array[$i]['source_type_id']==2){
				$receivable_supplier_params['fee_type_code']=305;		
			}else if($receivable_supplier_array[$i]['source_type_id']==3){
				$receivable_supplier_params['fee_type_code']=306;		
			}else if($receivable_supplier_array[$i]['source_type_id']==4){
				$receivable_supplier_params['fee_type_code']=307;	
			}else if($receivable_supplier_array[$i]['source_type_id']==5){
				$receivable_supplier_params['fee_type_code']=308;
			}else if($receivable_supplier_array[$i]['source_type_id']==6){
				$receivable_supplier_params['fee_type_code']=309;
			}else if($receivable_supplier_array[$i]['source_type_id']==7){
				$receivable_supplier_params['fee_type_code']=310;
			}else if($receivable_supplier_array[$i]['source_type_id']==8){
				$receivable_supplier_params['fee_type_code']=311;
			}else if($receivable_supplier_array[$i]['source_type_id']==9){
				$receivable_supplier_params['fee_type_code']=312;
			}else if($receivable_supplier_array[$i]['source_type_id']==10){
				$receivable_supplier_params['fee_type_code']=313;
			}else if($receivable_supplier_array[$i]['source_type_id']==11){
				$receivable_supplier_params['fee_type_code']=314;
			}else if($receivable_supplier_array[$i]['source_type_id']==12){
				$receivable_supplier_params['fee_type_code']=315;
			}
			if(!empty($receivable_supplier_array[$i]['receivable_number'])){//有就修改没就新增
				
				$receivable_supplier_params['receivable_number'] = $receivable_supplier_array[$i]['receivable_number'];
				
				$this->_receivable->updateReceivableByReceivableNumber($receivable_supplier_params);
			}else{
				
				$this->_receivable->addReceivable($receivable_supplier_params);
			}

		}
		return 1;
    }
	


}