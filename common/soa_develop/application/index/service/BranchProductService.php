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

class BranchProductService{
	
	
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
	
	//获取分公司产品成本与报价
	public function getBranchProduct($params){
		$branch_product = new BranchProduct();
		$company_order_product = new CompanyOrderProduct();
		$company_order_customer = new CompanyOrderCustomer();
		

		$branch_product_result = $this->_branch_product->getBranchProduct($params);
	
		$user_params = [
				'user_id'=>$params['now_user_id']
		];
		$user_result = $this->_user->getUser($user_params);
		$base_currency_id = $user_result[0]['company_currency_id'];
		
		for($i=0;$i<count($branch_product_result);$i++){
			$distributor_price = 0;
			$customer_price = 0;
			$branch_product_cost = 0;//成本
			//通过分公司编号反查有多少订单
			$company_order_product_params = [
					'status'=>1,
					'branch_product_number'=>$branch_product_result[$i]['branch_product_number']
			];

		
			$branch_product_team_params =[
					'branch_product_number'=>$branch_product_result[$i]['branch_product_number'],
					'status'=>1
			];
		
// 			$branch_product_team_result = $this->_branch_product_team->getBranchProductTeam($branch_product_team_params);
		
 			$branch_product_source = new BranchProductSource();
// 			for($j=0;$j<count($branch_product_team_result);$j++){
// 				$data['branch_product_number'] = $branch_product_team_result[$j]['branch_product_number'];
// 				$data['team_product_number'] = $branch_product_team_result[$j]['team_product_number'];
	
// 				$company_order_customer_lineup_result = $this->_company_order_customer_lineup->getCustomerAndLinueup($company_order_customer_lineup_params);
// 				$branch_product_team_result[$j]['stance'] = count($company_order_customer_lineup_result);
// 				$data['supplier_type_id'] = 11;
		
// 				$branch_product_team_result[$j]['own_expens_source_array'] = $this->_branch_product_source->getBranchProductSource2($data);
// 				$proportion_result = $this->_proportion_service->getProportion($base_currency_id,$branch_product_team_result[$j]['price_currency_id']);
// 				$cost_proportion_result =  $this->_proportion_service->getProportion($base_currency_id,$branch_product_team_result[$j]['cost_currency_id']);
// 				$distributor_price = $distributor_price+ $branch_product_team_result[$j]['branch_distributor_price']*$proportion_result;
// 				$customer_price = $customer_price+ $branch_product_team_result[$j]['branch_customer_price']*$proportion_result;
// 				$branch_product_cost = $branch_product_cost+$branch_product_team_result[$j]['branch_cost']*$cost_proportion_result;
// 			}

			$branch_product_route_template_params =[
					'branch_product_number'=>$branch_product_result[$i]['branch_product_number'],
					
			];
			
			$branch_product_route_template_result = $this->_branch_product_route_template->getBranchProductRouteTemplate($branch_product_route_template_params);
		
			for($j=0;$j<count($branch_product_route_template_result);$j++){

			
				//$branch_product_team_result[$j]['own_expens_source_array'] = $this->_branch_product_source->getBranchProductSource2($data);
				$proportion_result = $this->_proportion_service->getProportion($branch_product_route_template_result[$j]['price_currency_id'],$base_currency_id);
				$cost_proportion_result =  $this->_proportion_service->getProportion($branch_product_route_template_result[$j]['price_currency_id'],$base_currency_id);
				$distributor_price = $distributor_price+ $branch_product_route_template_result[$j]['distributor_price']*$proportion_result;
				$customer_price = $customer_price+ $branch_product_route_template_result[$j]['customer_price']*$proportion_result;
	
				$branch_product_cost = 1;				
			}
			//$branch_product_result[$i]['team_product_array'] = $branch_product_team_result;
		
			//获取分公司的自己的资源
			$params_source = [
						
				'branch_product_number'=>$branch_product_result[$i]['branch_product_number'],
				'is_team_product'=>2
						
			];
			
			$source_array = $branch_product_source->getBranchProductSource2($params_source);
		
			for($k=0;$k<count($source_array);$k++){
				if($source_array[$k]['is_team_product'] ==2 && $source_array[$k]['supplier_type_id']!=11){
					$proportion_result = $this->_proportion_service->getProportion($source_array[$k]['price_currency_id'],$base_currency_id);
					$distributor_price = $distributor_price+ $source_array[$k]['source_distributor_price']*$proportion_result;
					$customer_price = $customer_price+ $source_array[$k]['source_customer_price']*$proportion_result;
					$cost_proportion_result = $this->_proportion_service->getProportion($source_array[$k]['cost_currency_id'],$base_currency_id);
						
					$branch_product_cost = $branch_product_cost+$branch_product_team_result[$j]['source_cost']*$cost_proportion_result;

				}
		
		
			}
			//$branch_product_result[$i]['source_array'] = $source_array;
			$branch_product_result[$i]['branch_product_price'] = $branch_product_price;
			$branch_product_result[$i]['distributor_price'] = $distributor_price;
			$branch_product_result[$i]['customer_price'] = $customer_price;
			$branch_product_result[$i]['branch_product_cost'] = $branch_product_cost;//分公司成本
		
		}
		return $branch_product_result;
	}
	
}