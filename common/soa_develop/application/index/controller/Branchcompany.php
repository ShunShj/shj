<?php
namespace app\index\controller;
use app\common\help\Help;
use app\index\model\branchcompany\CompanyOrderAnnex;
use app\index\model\branchcompany\CompanyOrderComment;
use app\index\model\branchcompany\CustomerSource;
use app\index\model\branchcompany\OrderCardPayRecord;
use app\index\model\branchcompany\OrderPayRecord;
use app\index\model\branchcompany\BranchProductComment;
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
use app\index\model\branchcompany\CompanyOrderProductDiyPrice;
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
use app\index\service\InStationLetterService;
use think\Model;
use think\Controller;
use app\common\help\Contents;
use app\index\service\PublicService;

class Branchcompany extends Base
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
        parent::__construct();
    }
    
    
    /**
     * 修改公司订单 成本
     */
    

    
    /**
     * 添加分公司产品基础信息
     * 王
     */
    public function addBranchProduct(){
    	$params = $this->input();
    
    	$paramRule = [
			'team_name'=>'string',
    		'branch_product_begin_time'=>'number',
    		'branch_product_end_time'=>'number',
    		'user_id'=>'number',
    		'status'=>'number'
    	
    	];
    	$this->paramCheckRule($paramRule,$params);

        $data = [
            'branch_product_name'=>$params['branch_product_name'],
            'company_id'=>$params['user_company_id'],
        ];
        $this->checkNameIsRepetition('branch_product',$data);


    	$branch_product = new BranchProduct();
    	$params['branch_product_number'] = Help::getNumber(3);
    	
   
    	$branch_product_result = $branch_product->addBranchProduct($params);
    
    	$this->outPut($branch_product_result);
    }
    
    /**
     * 添加 分公司产品 大数组
     */
    public function addBranchProductBigArray(){
    	$params = $this->input();
    	$paramRule = [
    		'branch_product_name'=>'string',
    		'branch_product_begin_time'=>'number',	
			'branch_product_type_id'=>'number',
    		'status'=>'number'	
   
    	];
    	$this->paramCheckRule($paramRule,$params);


    	//首先添加分公司产品
    	$branch_product = new BranchProduct();

    	
    	if(!empty($params['branch_product_number'])){//走修改逻辑

            $Info = $branch_product->getOneBranchProduct($params['branch_product_id']);
            if($Info['branch_product_name'] == $params['branch_product_name']){
            }else{
                //开始判断名字是否重复
                $data = [
                    'branch_product_name'=>$params['branch_product_name'],
                    'company_id'=>$Info['user_company_id'],
                ];
                $this->checkNameIsRepetition('branch_product',$data);
                //结束判断名字重复
            }

    		$branch_product_result = $branch_product->updateBranchProductByBranchProductNumber($params);
    	}else{

            $data = [
                'branch_product_name'=>$params['branch_product_name'],
                'company_id'=>$params['user_company_id'],
            ];
            $this->checkNameIsRepetition('branch_product',$data);

    		$params['branch_product_number'] = Help::getNumber(3);
    		$branch_product_result = $branch_product->addBranchProduct($params);
    	}
    	
    	
    	$this->outPut($branch_product_result);
    	
    }
    
    /**
     * 修改分公司产品
     * 王
     */
    public function updateBranchProductByBranchProductNumber(){
    	$params = $this->input();


        $branch_product = new BranchProduct();
        $Info = $branch_product->getOneBranchProduct($params['branch_product_id']);
        if($Info['branch_product_name'] == $params['branch_product_name']){
        }else{

            //开始判断名字是否重复
            $data = [
                'branch_product_name'=>$params['branch_product_name'],
                'company_id'=>$Info['user_company_id'],
            ];
            $this->checkNameIsRepetition('branch_product',$data);
            //结束判断名字重复
        }



    	$paramRule = [
    		'branch_product_number'=>'string',
    		
    	];
    	$this->paramCheckRule($paramRule,$params);

    	$branch_product_result = $branch_product->updateBranchProductByBranchProductNumber($params);
    	$this->outPut($branch_product_result);
    	
    }
    /**
     * 修改分公司状态
     */
    public function updateBranchProductStatusByBranchProductNumber(){
    	$params = $this->input();
    	
    	$paramRule = [
    		'branch_product_number'=>'string',
    	
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$branch_product = new BranchProduct();
    	$branch_product_result = $branch_product->updateBranchProductStatusByBranchProductNumber($params);
    	$this->outPut($branch_product_result);
    	
    }
    
    /**
     * 获取分公司产品
     * 胡
     */
    public function getBranchProduct(){
    	$params = $this->input();
    
    	$branch_product = new BranchProduct();
		$company_order_product = new CompanyOrderProduct();
		$company_order_customer = new CompanyOrderCustomer();
		$branch_product_source = new BranchProductSource();
		if(isset($params['page'])){
			$page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
			$page = ($params['page']-1)*$page_size;
			$count = $branch_product->getBranchProduct($params, true);
			$branch_product_result = $branch_product->getBranchProduct($params,false,'true',$page,$page_size);
		}else{
			$branch_product_result = $branch_product->getBranchProduct($params);
		}
		$user_params = [
				'user_id'=>$params['now_user_id']
		];
		
	
		
		
		$user_result = $this->_user->getUser($user_params);
		$base_currency_id = $user_result[0]['company_currency_id'];
		
		for($i=0;$i<count($branch_product_result);$i++){
			$distributor_price = 0;
			$customer_price = 0;
		
			//通过分公司编号反查有多少订单
			$company_order_product_params = [
					'status'=>1,
					'branch_product_number'=>$branch_product_result[$i]['branch_product_number']
			];
			$company_order_product_result = $company_order_product->getCompanyOrderProduct($company_order_product_params);
			$customer_count=0;
			for($k=0;$k<count($company_order_product_result);$k++){
				$company_order_customer_params = [
						'status'=>1,
						'company_order_number'=>$company_order_product_result[$k]['company_order_number']
				];
		
				$company_order_customer_result = $company_order_customer->getCompanyOrderCustomer($company_order_customer_params);
				$customer_count+=count($company_order_customer_result);
			}
		
			$branch_product_result[$i]['customer_count'] = $customer_count;
			//获取线路模板
			$branch_product_route_template_params['branch_product_number'] = $branch_product_result[$i]['branch_product_number'];

			
			$route_teamplate_result = $this->_branch_product_route_template->getBranchProductRouteTemplate($branch_product_route_template_params);
			
			for($k=0;$k<count($route_teamplate_result);$k++){
		
				$route_teamplate_params = [
					'route_number'=>$route_teamplate_result[$k]['route_number'],
					
				];
				//通过编号查询ID
				$route_template_result = $this->_route_template->getRouteTemplate($route_teamplate_params);
				$route_teamplate_result[$k]['route_template_id'] = $route_template_result[0]['route_template_id'];
				$route_teamplate_params = [
						'route_template_id'=> $route_template_result[0]['route_template_id'],
						'can_watch_company_id'=>$params['user_company_id']
				];
				
				
				$template= $this->_route_template->getRouteTemplate($route_teamplate_params);
				 
			
				 $route_teamplate_result[$k]['company_name'] = $template[0]['company_name'];
				 $route_teamplate_result[$k]['plan_custom_number'] = $template[0]['plan_custom_number'];
				 $route_teamplate_result[$k]['is_choose'] = $template[0]['is_choose'];
				 $route_teamplate_result[$k]['route_name'] = $template[0]['route_name'];
				 $route_teamplate_result[$k]['stance'] = $template[0]['stance'];
				 $route_teamplate_result[$k]['settlement_type'] =$template[0]['settlement_type'];
				 if(isset($template[0]['once_price'])){
				 	$route_teamplate_result[$k]['once_price'] =$template[0]['once_price'];
				 }
				 if(isset($template[0]['real_price'])){
				 	$route_teamplate_result[$k]['real_price'] =$template[0]['real_price'];
				 }
				
				 
				 $route_teamplate_result[$k]['stance'] = $template[0]['stance'];
				 $route_teamplate_result[$k]['unit'] = $template[0]['unit'];
				 $route_teamplate_result[$k]['cost_currency_id'] =5;
				 $route_teamplate_result[$k]['cost_currency_name'] = $template[0]['currency_name'];
				 $proportion_result = $this->_proportion_service->getProportion($route_teamplate_result[$k]['price_currency_id'],$base_currency_id);
				 $distributor_price = $route_teamplate_result[$k]['distributor_price']*$proportion_result;
				 $customer_price= $route_teamplate_result[$k]['customer_price']*$proportion_result;
			}
			$branch_product_result[$i]['route_teamplate_array'] = $route_teamplate_result;
			
			
// 			$branch_product_team_params =[
// 					'branch_product_number'=>$branch_product_result[$i]['branch_product_number'],
// 					'status'=>1
// 			];
		
// 			$branch_product_team_result = $this->_branch_product_team->getBranchProductTeam($branch_product_team_params);
		
 			
// 			for($j=0;$j<count($branch_product_team_result);$j++){
// 				$data['branch_product_number'] = $branch_product_team_result[$j]['branch_product_number'];
// 				$data['team_product_number'] = $branch_product_team_result[$j]['team_product_number'];
// 				//获取收客人数
// 				$company_order_customer_lineup_params=[
// 					'team_product_number'=>$branch_product_team_result[$j]['team_product_number'],
// 					'status'=>1
// 				];
// 				$company_order_customer_lineup_result = $this->_company_order_customer_lineup->getCustomerAndLinueup($company_order_customer_lineup_params);
// 				$branch_product_team_result[$j]['stance'] = count($company_order_customer_lineup_result);
// 				$data['supplier_type_id'] = 11;
		
// 				$branch_product_team_result[$j]['own_expens_source_array'] = $branch_product_source->getBranchProductSource2($data);
// 				$proportion_result = $this->_proportion_service->getProportion($base_currency_id,$branch_product_team_result[$j]['price_currency_id']);
		
// 				$distributor_price = $distributor_price+ $branch_product_team_result[$j]['branch_distributor_price']*$proportion_result;
// 				$customer_price = $customer_price+ $branch_product_team_result[$j]['branch_customer_price']*$proportion_result;
				 
// 			}
// 			$branch_product_result[$i]['team_product_array'] = $branch_product_team_result;
		
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
				}		
			}
			$branch_product_result[$i]['source_array'] = $source_array;
			$branch_product_result[$i]['distributor_price'] = $distributor_price;
			$branch_product_result[$i]['customer_price'] = $customer_price;
		
		}
	

        if(isset($params['page'])){
            $data = [
                'count'=>$count,
                'list'=>$branch_product_result,
                'page_count'=>ceil($count/$page_size)
            ];
            return $this->output($data);
        }else{
          
            $this->outPut($branch_product_result);
        }
    }
    
    /**
     * 添加分公司产品 团队产品 
     * 王
     */
    public function addBranchProductTeam(){
  		
        $params = $this->input();
        $paramRule = [
            'team_product_name'=>'string',
            'supplier_name'=>'string',
            'plan_custom_number'=>'number',
            'begin_time'=>'number',
     
            'status'=>'number'

        ];
        $this->paramCheckRule($paramRule,$params);
  		
        $branch_product_team = new BranchProductTeam();

        $branch_product_team_result = $branch_product_team->addBranchProductTeam($params);
     
        $this->outPut($branch_product_team_result);
    }
    /**
     * 获取分公司产品 团队产品
     * 王
     */
    public function getBranchProductTeam(){
    	$params = $this->input();
        $branch_product_team = new BranchProductTeam();
    	$branch_product_team_result = $branch_product_team->getBranchProductTeam($params);
    	
    	$branch_product_source = new BranchProductSource();
		for($i=0;$i<count($branch_product_team_result);$i++){
			$data['branch_product_number'] = $branch_product_team_result[$i]['branch_product_number'];
			$data['team_product_number'] = $branch_product_team_result[$i]['team_product_number'];
			$data['supplier_type_id'] = 11;
		
			$branch_product_team_result[$i]['own_expense_array'] = $branch_product_source->getBranchProductSource2($data);
		}
    	$this->outPut($branch_product_team_result);    
    	 
    	 
    }    
    
    /**
     * 修改分公司产品团队产品
     * 王
     */

     public function updateBranchProductTeamByBranchProductNumber(){
        $params = $this->input();
         
        $paramRule = [
			'team_product_number'=>'string',
            'branch_product_number'=>'string',
            'user_id'=>'number',

        ];
        $this->paramCheckRule($paramRule,$params);
        $branch_product_team = new BranchProductTeam();

        $branch_product_team_result = $branch_product_team->updateBranchProductTeamByBranchProductNumber($params);
        $this->outPut($branch_product_team_result);
        
    }
    /**
     * 修改分公司产品线路模板
     * 王
     */
    
    public function updateBranchProductRouteTemplate(){
    	$params = $this->input();
    	 
    	$paramRule = [
    		'route_number'=>'string',
    		'branch_product_number'=>'string',
    		
    
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$result = $this->_branch_product_route_template->delBranchProductRouteTemplate($params);

    	$this->outPut($result);
    
    }
     /**
     * 添加分公司产品 资源
     * 王
     */
    public function addBranchProductSource(){

		
        $params = $this->input();
   		
        $paramRule = [
          
            'supplier_type_id'=>'number',
            'source_id'=>'number',
            'is_team_product'=>'number',
            'supplier_name'=>'string',
            'source_number'=>'string',
            'source_name'=>'string',

            'user_id'=>'number',
            'status'=>'number'
        ];

        $this->paramCheckRule($paramRule,$params);

        $branch_product_source = new BranchProductSource();
        $branch_product_source_result = $branch_product_source->addBranchProductSource($params);
        $this->outPut($branch_product_source_result);     
        
        
    }

    /**
     * 修改分公司产品 资源
     * 王
     */
    public function updateBranchProductSourceByBranchProductSourceId(){
        $params = $this->input();
         
        $paramRule = [

            'branch_product_source_id'=>'number',
            'user_id'=>'number',

        ];
        $this->paramCheckRule($paramRule,$params);
        $branch_product_source = new BranchProductSource();

        $branch_product_source_result = $branch_product_source->updateBranchProductSourceByBranchProductSourceId($params);
        $this->outPut($branch_product_source_result);
        
    }
    /**
     * 获取分公司产品 资源
     * 王
     */
    public function getBranchProductSource(){
        $params = $this->input();
        $branch_product_source = new BranchProductSource();
        $branch_product_source_result = $branch_product_source->getBranchProductSource2($params);
        
        $this->outPut($branch_product_source_result);

    }

    /**
     * 获取分公司订单资源
     * 王
     */
    public function getCompanyOrderProductSource(){
        $params = $this->input();
        $company_order_product_source_result = $this->_company_order_product_source->getCompanyOrderProductSource($params);

        $this->outPut($company_order_product_source_result);

    }
    
    /**
     * 修改分公司团队产品以及资源的报价与货币
     */
    public function updateBranchProductPriceAndCurrencyId(){
    	$params = $this->input();
    	

    	$branch_product_source = new BranchProductSource();
    	$branch_product_team = new BranchProductTeam();
    	$branch_product_arr = $params['branch_product_arr'];

    	for($i=0;$i<count($branch_product_arr);$i++){
    		if($branch_product_arr[$i]['type']==1){//修改团队产品
    			$branch_product_team_params = [
    				'branch_product_team_id'=>$branch_product_arr[$i]['branch_product_type_id'],
    				'branch_price'=>$branch_product_arr[$i]['price'],
    				'price_currency_id'=>$branch_product_arr[$i]['price_currency_id'],	
    			];
    			
    			$branch_product_team->updateBranchProductTeamPriceAndCurrencyIdByBranchProductTeamId($branch_product_team_params);
    		}else{//修改资源
    			$branch_product_source_params = [
    					'branch_product_source_id'=>$branch_product_arr[$i]['branch_product_type_id'],
    					'branch_price'=>$branch_product_arr[$i]['price'],
    					'price_currency_id'=>$branch_product_arr[$i]['price_currency_id'],
    			];
    		
    			$branch_product_source->updateBranchProductSourcePriceAndCurrencyIdByBranchProductSourceId($branch_product_source_params);
    		}
    	}
    	
    	$this->outPut(1);
    	
    }
    /**
     * 获取所属分公司资源
     * 王
     */
    public function getOwnCompanySource(){
        $params = $this->input();
        $own_company_source = new BranchProductSource();
        $own_company_source_result = $own_company_source ->getOwnCompanySource($params);

        $this->outPut($own_company_source_result);

    }

    /**
     * 获取分公司产品类型
     */
    public function getBranchProductType(){
    	$params = $this->input();
    	
    	
    	if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
    		$count =  $this->_branch_product_type->getBranchProductType($params,true);
    		$result = $this->_branch_product_type->getBranchProductType($params,false,'true',$page,$page_size);
    		$data = [
    				'count'=>$count,
    				'list'=>$result,
    				'page_count'=>ceil($count/$page_size)
    		];
    		 
    		return $this->output($data);
    	}
    	
    	$result = $this->_branch_product_type->getBranchProductType($params);
    	$arr = ['branch_product_type_id' => -1, 'branch_product_type_name' => '代售线路'];
        array_push($result, $arr);
    	$this->outPut($result);
    
    	
    }
    
    /**
     * 添加分公司产品类型
     */
    public function addBranchProductType(){
    	$params = $this->input();
    	$paramRule = [
    		'branch_product_type_name'=>'string'
    			 
    	];
    	$this->paramCheckRule($paramRule,$params);

        $data = [
            'branch_product_type_name'=>$params['branch_product_type_name'],
            'company_id'=>$params['user_company_id'],
        ];
        $this->checkNameIsRepetition('branch_product_type',$data);

    	$result = $this->_branch_product_type->addBranchProductType($params);
 
    	$this->outPut($result);
    }
    /**
     * 修改分公司产品类型
     */
    public function updateBranchProductType(){
    	$params = $this->input();
    	$paramRule = [
    		'branch_product_type_id'=>'number'
    
    	];
    	$this->paramCheckRule($paramRule,$params);


        $Info = $this->_branch_product_type->getOneBranchProductType($params['branch_product_type_id']);
        if($Info['branch_product_type_name'] == $params['branch_product_type_name']){
        }else{

            //开始判断名字是否重复
            $data = [
                'branch_product_type_name'=>$params['branch_product_type_name'],
                'company_id'=>$Info['user_company_id'],
            ];
            $this->checkNameIsRepetition('branch_product_type',$data);
            //结束判断名字重复
        }

    	 
    	$result = $this->_branch_product_type->updateBranchProductType($params);
    
    	$this->outPut($result);
    }
    
    /**
     * 锁定分公司产品
     */
    public function updateBranchProductLocked(){
    	$params = $this->input();
    	$paramRule = [
    		'branch_product_number'=>'string'
    	
    	];
    	$this->paramCheckRule($paramRule,$params);
    	
    	$result = $this->_branch_product->updateBranchProductLocked($params);
    	$this->outPut($result);
    }
    /**
     * 添加公司订单
     */
    public function addCompanyOrder(){
    	$params = $this->input();

    	if($params['channel_type'] ==1){
    		$paramRule = [    		
    			'wr'=>'number',
    			//'clientsource'=>'number',
    			'distributor_id'=>'number',
    			'channel_type'=>'number',	
    			'buy_order_time'=>'number',
    			'begin_time'=>'number',
    			
    		];    		
    	}else{
    		$paramRule = [
    		
    			'wr'=>'number',
    			//'clientsource'=>'number',
    			'channel_type'=>'number',
    			'buy_order_time'=>'number',
    			'begin_time'=>'number',
    		

    		
    		];    		
    	}

    	
    	$this->paramCheckRule($paramRule,$params);		    	
    	$company_order = new CompanyOrder();
    	$params['company_order_number'] = help::getNumber(4);
    	
    	$company_order_result = $company_order->addCompanyOrder($params);
    	$this->outPut($company_order_result);
    	
    }
    
    
    /**
     * 添加公司订单 大数组 
     */
    public function addCompanyOrderBigArray(){
    	$params = $this->input();
        if($params['channel_type'] ==1){
    		$paramRule = [
    		
    			'wr'=>'number',
    			'clientsource'=>'number',
    			'channel_type'=>'number',
    			
    		
    			'begin_time'=>'number',
    			'end_time'=>'number',
    			'distributor_id'=>'number',
 
    		];    		
    	}else{
    		$paramRule = [
    	
    			'wr'=>'number',
    			'clientsource'=>'number',
    			'channel_type'=>'number',
    			'begin_time'=>'number',
    			'end_time'=>'number',

    	
    		];    		
    	}
    	//$paramRule['depart_city_id'] = 'number';
    	$this->paramCheckRule($paramRule,$params);
    	$now_user_id = $params['now_user_id'];
    	$company_id = $params['user_company_id'];
    	//首先添加订单到数据库
    	$params['company_order_number'] = Help::getNumber(4);
		$company_order_result = $this->_company_order->addCompanyOrder($params);
		//获取到订单编号 
		$company_order_number = $params['company_order_number'];
		
		
		//开始添加游客 信息
		
		//先判断是否有占位
		$customer_info = $params['customer_info'];
		if(!empty($customer_info['zhanwei_count'])){ //有展位添加占位
			
			$zhanwei_params = [
				'occupy_count'=>$customer_info['zhanwei_count'],
				'now_user_id'=>$now_user_id,
				'company_order_number'=>$company_order_number	
			];
			$company_order_customer_result = $this->_company_order_customer->addCustomerOccupy($zhanwei_params);
		}
		//再判断游客信息
		$customer_info_array = $customer_info['customer_array'];
		
		for($i=0;$i<count($customer_info_array);$i++){
			$customer_info_array_params = $customer_info_array[$i];
			$paramRule = [
	
				'customer_first_name'=>'string',//客户姓
				'customer_last_name'=>'string',//客户名
				'customer_type'=>'number',//客户类型
				'gender'=>'number',
				'country_id'=>'number',
				'language_id'=>'number',

				'passport_number'=>'string'	
			
					 
			];
			$customer_info_array_params['company_order_number'] = $company_order_number;
			$customer_info_array_params['now_user_id'] = $now_user_id;
			$customer_info_array_params['company_id'] = $company_id;
			$this->paramCheckRule($paramRule,$customer_info_array_params);
			$this->_company_order_customer_service->addCustomer($customer_info_array_params);
		}
		//开始添加游客信息
		
    	$this->output($company_order_result);	
    }
    /**
     * 修改公司订单
     */
    public function updateCompanyOrderByCompanyOrderNumber(){
    	$params = $this->input();
    	
 		
    	$paramRule = [
			'company_order_number'=>'string',
    	
    	];
		
    	 
    	$this->paramCheckRule($paramRule,$params);
    	$company_order = new CompanyOrder();
    	$company_order_result = $company_order->updateCompanyOrderByCompanyOrderNumber($params);
    	
    	$this->outPut($company_order_result);
    }
    /**
     * 添加游客
     * 胡
     **/
    public function addCompanyOrderCustomer(){
    	
    	
    	$params = $this->input();
    
    	$paramRule = [
    		
    		'company_order_number'=>'string',
    		'customer_first_name'=>'string',//客户姓
    		'customer_last_name'=>'string',//客户名    		
    
    		'gender'=>'number',
    		//'country_id'=>'number',
    		//'language_id'=>'number',
			
    		'passport_number'=>'string'	

    	];
    	$this->paramCheckRule($paramRule,$params);   
    	
    	//首先根据证件类型 证件号查询数据
    	$customer_data = [
    		'passport_number'=>$params['passport_number'],
 
    	];
    	$customer = new Customer();
    
    	$customer_data_result = $customer->getCustomer($customer_data);
  
    	if(count($customer_data_result)>0){//假如有数据
    		
    		//获取用户ID
    		$update_customer_data = [
    			'customer_id'=>$customer_data_result[0]['customer_id'],
    				
    			'customer_first_name'=>$params['customer_first_name'],
    			'customer_last_name'=>$params['customer_last_name'],
    			'middle_name'=>$params['middle_name'],	
    			'english_first_name'=>$params['english_first_name'],
    			'english_last_name'=>$params['english_last_name'],
    			
    			'gender'=>$params['gender'],
				'country_id'=>$params['country_id'],
    			'language_id'=>$params['language_id'],
    			'phone'=>$params['phone'],
    			'email'=>$params['email'],
    			'emergency_contact'=>$params['emergency_contact'],
    			'emergency_call'=>$params['emergency_call'],
    			'address'=>$params['address'],
    			'issuing_date'=>$params['issuing_date'],	
    			'term_of_validity'=>$params['term_of_validity'],
    			'now_user_id'=>$params['now_user_id'],    				
    			'card_type'=>$params['card_type'],
    			'card_number'=>$params['card_number'],
    			'birthday'=>$params['birthday'],
    				
    		
    		];
    		$result = $customer->updateCustomerByCustomerId($update_customer_data);
    		$customer_id = $customer_data_result[0]['customer_id'];
    	
    	}else{//假如没数据
    
    		$add_customer_data = [    	
    			'customer_number'=>Help::getNumber(7),
    			'passport_number'=>$params['passport_number'],
    			'customer_first_name'=>$params['customer_first_name'],
    			'customer_last_name'=>$params['customer_last_name'],
    			'middle_name'=>$params['middle_name'],    				
    			'english_first_name'=>$params['english_first_name'],
    			'english_last_name'=>$params['english_last_name'],
    		
    			'gender'=>$params['gender'],
    			'country_id'=>$params['country_id'],
    			'language_id'=>$params['language_id'],
    			'phone'=>$params['phone'],
    			'email'=>$params['email'],
    			'emergency_contact'=>$params['emergency_contact'],
    			'emergency_call'=>$params['emergency_call'],
    			'address'=>$params['address'],
    			'issuing_date'=>$params['issuing_date'],    				
    			'term_of_validity'=>$params['term_of_validity'],
    	
				'now_user_id'=>$params['now_user_id'],
    			'card_type'=>$params['card_type'],
    			'card_number'=>$params['card_number'],
    			'status'=>1,
    			'birthday'=>$params['birthday'],
    			'user_company_id'=>$params['user_company_id'],
    			'choose_company_id'=>$params['user_company_id']	
    		];
    		
    		
    		$customer_id = $customer->addCustomer($add_customer_data);
    		
    	}
    
    	//开始查询公司订单是否有用户数据
    	$company_order_customer_data['customer_id'] = $customer_id;
    	$company_order_customer_data['company_order_number'] = $params['company_order_number'];
    	
    	$company_order_customer = new CompanyOrderCustomer();
    	$company_order_customer_result = $company_order_customer->getCompanyOrderCustomer($company_order_customer_data);
    	$company_order_customer_id = $company_order_customer_result[0]['company_order_customer_id'];
    	if(count($company_order_customer_result)==0){//如果为空那插入
    		$company_order_customer_data['status'] =  1;
    		$company_order_customer_data['now_user_id'] =  $params['now_user_id'];
    		$company_order_customer_data['special_claim'] = $params['special_claim'];
    		$company_order_customer_id = $company_order_customer->addCompanyOrderCustomer($company_order_customer_data);
    		
    		$params['company_order_customer_id'] = $company_order_customer_id;
    		//开始顾客航班信息
    		
    		$params['customer_id'] = $customer_id;
    		 
    		 
    	
    		if(count($params['customer_flight'])>0){
    			$customer_flight = new CompanyOrderFlight();
    		
    			$company_order_flight = $customer_flight->addCompanyOrderFlight($params);
    		
    		}
    		
    		 
    		//开始添加顾客住宿信息
    		 
    		$customer_accommodation = new CompanyOrderAccommodation();
    		 
    		$customer_accommodation_result = $customer_accommodation->addCompanyOrderCustomerAccommodation($params);
    		
    		
    		if(!is_numeric($customer_accommodation_result)){//如果添加出错
    			$this->outPutError(['msg' => "add customer accommodation error"],$params);
    		}    		
    	}


    	//团队产品成本修改
    	$company_order_team_cost_params['company_order_number'] = $params['company_order_number'];
    	$company_order_team_cost_params['now_user_id'] = $params['now_user_id'];
    	$this->_company_order_service->updateCompanyOrderTeamCost($company_order_team_cost_params);
    	
    	//修改公司订单状态为未确认
    	$company_order = new CompanyOrder();
    	$company_order_params=[
    		'company_order_number'=>$params['company_order_number'],
    		'company_order_status'=>1	
    	];
    	$company_order_result = $company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
    	 
    	$this->outPut($customer_id);
    	//$customer_flight = $company_order_flight->add
    	

    	
    }
    
    /**
     * 获取该订单下的游客
     */
    public function getCompanyOrderCustomer(){
    	$params = $this->input();
    	$paramRule = [
    		'company_order_number'=>'string',

    	]; 

    	$this->paramCheckRule($paramRule,$params);
	
    	$company_order_customer = new CompanyOrderCustomer();
    	$company_order_customer_result = $company_order_customer->getCompanyOrderCustomer($params);
	
    	$company_order_accommodation = new CompanyOrderAccommodation();
    	$company_order_flight = new CompanyOrderFlight();
    	for($i=0;$i<count($company_order_customer_result);$i++){
    		$company_order_customer_result[$i]['check_in_hotel_id'] = $company_order_customer_result[$i]['check_in_hotel'];
    		$company_order_customer_result[$i]['check_on_hotel_id'] = $company_order_customer_result[$i]['check_on_hotel'];
    		//开始判断是否有生日没有则为空
    		if(is_numeric($company_order_customer_result[$i]['birthday'])){
    			$company_order_customer_result[$i]['age'] = (date('Y')-date("Y",$company_order_customer_result[$i]['birthday']))+1;
    		}else{
    			$company_order_customer_result[$i]['age']='';
    		}
    		
    		
    		//查询住宿信息
    		$check_in_hotel = $company_order_customer_result[$i]['check_in_hotel'];
    		$check_in_hotel_string='';
    		$check_on_hotel = $company_order_customer_result[$i]['check_on_hotel'];
    		$check_in_hotel_array =  explode(',',$check_in_hotel);
    		$check_on_hotel_array =  explode(',',$check_on_hotel);
    		$hotel_in_name ='';
    		$hotel_on_name ='';
    		
    		for($j=0;$j<count($check_in_hotel_array);$j++){
    			
    			if(is_numeric($check_in_hotel_array[$j])){
    				$hotel_params['hotel_id'] = $check_in_hotel_array[$j];
    				$hotel_result = $this->_hotel->getHotel($hotel_params);
    				$hotel_name = $hotel_result[0]['room_name'];
    			}else{
    				$hotel_name ='';
    			}
    			
    			$hotel_in_name.=','.$hotel_name;
    			
    		}
    		
    		$hotel_in_name = trim($hotel_in_name,',');
    		$company_order_customer_result[$i]['check_in_hotel'] = $hotel_in_name;
    		
    		for($j=0;$j<count($check_on_hotel_array);$j++){
    			 
    			if(is_numeric($check_on_hotel_array[$j])){
    				$hotel_params['hotel_id'] = $check_on_hotel_array[$j];
    				$hotel_result = $this->_hotel->getHotel($hotel_params);
    				$hotel_name = $hotel_result[0]['room_name'];
    			}else{
    				$hotel_name ='';
    			}
    			 
    			$hotel_on_name.=','.$hotel_name;
    			 
    		}
    	
    		$hotel_on_name = trim($hotel_on_name,',');
    		$company_order_customer_result[$i]['check_on_hotel'] = $hotel_on_name;    		
    		
    		
    		//$company_order_customer_result[$i]['check_in_hotel'] = '2,3';
    		$params_data['customer_id'] = $company_order_customer_result[$i]['customer_id'];
    		$params_data['company_order_number'] = $params['company_order_number'];
    		$params_data['status'] = 1;
    		if($company_order_customer_result[$i]['customer_id']!=0){
    			//$company_order_customer_result[$i]['accommodation_info'] = $company_order_accommodation->getCompanyOrderAccommodation($params_data);
    			//查询航班信息
    			$company_order_customer_result[$i]['flight_info'] = $company_order_flight->getCompanyOrderFlight($params_data);
    			
    		}
			//获取排队编号
			$company_order_customer_lineup_params = [
				'company_order_number'=>$params['company_order_number'],
				'company_order_customer_id'=>$company_order_customer_result[$i]['company_order_customer_id']	
			];
			$company_order_customer_linue_result = $this->_company_order_customer_lineup->getLineup($company_order_customer_lineup_params);
    		for($k=0;$k<count($company_order_customer_linue_result);$k++){
    			if($company_order_customer_linue_result[$k]['lineup_type']==1){
    				$company_order_customer_result[$i]['company_order_lineup'] = Help::getLineupPrefix(1).$company_order_customer_linue_result[$k]['lineup_number'];
    			}
    		}
    	}

    	$this->outPut($company_order_customer_result); 
    }

	/**
	 * 修改公司订单的游客(根据公司订单的游客ID)
	 */
	
	public function updateCompanyOrderCustomerByCompanyOrderCustomerId(){
		
		$params = $this->input();

		$paramRule = [
			'company_order_customer_id'=>'number',
			'company_order_number'=>'string',
//			'user_id'=>'number',
			//游客信息
			'passport_number'=>"string",
			'customer_first_name'=>'string',//客户姓
			'customer_last_name'=>'string',//客户名
//			'english_first_name'=>'string',//英文姓
//			'english_last_name'=>'string',//英文名
		
		
			'gender'=>'number',
// 			'country_id'=>'number',
// 			'language_id'=>'number',
//			'phone'=>'string',
//			'email'=>'email',
			
		
//			'term_of_validity'=>'number',
		
			//航班信息
//			'customer_flight'=>'array',
			//住宿信息
//			'room_code'=>'number',
//			'room_type'=>'number',
//			'check_in'=>'number',
//			'check_on'=>'number',
			
		];
		$this->paramCheckRule($paramRule,$params);
		//首先根据证件类型 证件号查询数据
		$customer_data = [
			'passport_number'=>$params['passport_number'],
	
		];

		$customer = new Customer();
		 
		$customer_data_result = $customer->getCustomer($customer_data);

		if(count($customer_data_result)>0){//假如有数据
	
			//获取用户ID
			$update_customer_data = [
				'customer_id'=>$customer_data_result[0]['customer_id'],
				'customer_first_name'=>$params['customer_first_name'],
				'customer_last_name'=>$params['customer_last_name'],
				'middle_name'=>$params['middle_name'],
				'english_first_name'=>$params['english_first_name'],
				'english_last_name'=>$params['english_last_name'],
		
				'gender'=>$params['gender'],
				'country_id'=>$params['country_id'],
				'language_id'=>$params['language_id'],
				'phone'=>$params['phone'],
				'email'=>$params['email'],
				'issuing_date'=>$params['issuing_date'],
				'term_of_validity'=>$params['term_of_validity'],
				'emergency_contact'=>$params['emergency_contact'],
				'emergency_call'=>$params['emergency_call'],
				'address'=>$params['address'],
				'now_user_id'=>$params['now_user_id'],
				'status'=>$params['status'],
				'birthday'=>$params['birthday'],
                'choose_company_id'=>$params['user_company_id'],
			];
			$result = $customer->updateCustomerByCustomerId($update_customer_data);
			$customer_id = $customer_data_result[0]['customer_id'];
			 
		}else{//假如没数据
			
			$add_customer_data = [
					'customer_number'=>Help::getNumber(7),
					'customer_first_name'=>$params['customer_first_name'],
					'customer_last_name'=>$params['customer_last_name'],
					'middle_name'=>$params['middle_name'],
					'english_first_name'=>$params['english_first_name'],
					'english_last_name'=>$params['english_last_name'],
			
					'gender'=>$params['gender'],
					'country_id'=>$params['country_id'],
					'language_id'=>$params['language_id'],
					'phone'=>$params['phone'],
					'email'=>$params['email'],
					'issuing_date'=>$params['issuing_date'],
					'term_of_validity'=>$params['term_of_validity'],
					'emergency_contact'=>$params['emergency_contact'],
					'emergency_call'=>$params['emergency_call'],
					'address'=>$params['address'],
					'now_user_id'=>$params['now_user_id'],
					'choose_company_id'=>$params['user_company_id'],
					'card_type'=>$params['card_type'],
					'card_number'=>$params['card_number'],
					'status'=>1,
					'birthday'=>$params['birthday'],
					'passport_number'=>$params['passport_number'],
			];
		
		
			$customer_id = $customer->addCustomer($add_customer_data);
		
		}
	
		//修改公司订单游客信息 
		$company_order_customer = new CompanyOrderCustomer();
		$company_order_customer_params = [
			'status'=>$params['status'],
			'company_order_customer_id'=>$params['company_order_customer_id'],
			'customer_id'=>$customer_id,
			'now_user_id'=>$params['now_user_id'],
			'special_claim'=>$params['special_claim']	
				
		];
		if($company_order_customer_params['status']==2){
			$company_order_customer_params['customer_id']=0;
		}
		
		$company_order_customer->updateCompanyOrderCustomerByCompanyOrderCustomerId($company_order_customer_params);
		
	
		
		$customer_flight = new CompanyOrderFlight();
		//首先把顾客的航班信息状态修改为0
		$customer_flight_params = [
			'status'=>0,
			'customer_id'=>$customer_id,
			'company_order_number'=>$params['company_order_number'],
			'user_id'=>$params['now_user_id']	
				
		];
		
	
		$customer_flight->updateCompanyOrderFlightByCompanyOrderNumberAndCustomerId($customer_flight_params);
		$params['customer_id'] = $customer_id;
		$flight_info = $params['customer_flight'];
	
		//开始顾客航班信息
		if(count($flight_info)>0){
			$company_order_flight = $customer_flight->addCompanyOrderFlight($params);

		}
		
		
		
		

		
		//开始添加顾客住宿信息
		$customer_accommodation = new CompanyOrderAccommodation();
		//首先先判断是否有住宿
		$customer_accommodation_params = [
				'company_order_customer_id'=>$params['company_order_customer_id'],
				
		];
		$customer_accommodation_result = $customer_accommodation->getCompanyOrderAccommodation($customer_accommodation_params);
	
		if(count($customer_accommodation_result)>0){
			$customer_accommodation_params = [
				'customer_id'=>$customer_id,
				'company_order_number'=>$params['company_order_number'],
				'now_user_id'=>$params['now_user_id'],
				'room_code'=>$params['room_code'],
				'room_type'=>$params['room_type'],
				'check_in'=>$params['check_in'],
				'check_on'=>$params['check_on'],
				'check_in_hotel'=>$params['check_in_hotel'],
				'check_on_hotel'=>$params['check_on_hotel'],
				'company_order_accommodation_id'=>	$customer_accommodation_result[0]['company_order_accommodation_id']
			];
			
			$customer_accommodation->updateCompanyOrderAccommodationByCompanyOrderAccommodationId($customer_accommodation_params);
		}else{
			$customer_accommodation_params = [
				'customer_id'=>$customer_id,
				'company_order_number'=>$params['company_order_number'],
				'now_user_id'=>$params['now_user_id'],
				'room_code'=>$params['room_code'],
				'room_type'=>$params['room_type'],
				'check_in'=>$params['check_in'],
				'check_on'=>$params['check_on'],
				'check_in_hotel'=>$params['check_in_hotel'],
				'check_on_hotel'=>$params['check_on_hotel'],
				'company_order_customer_id'=>$params['company_order_customer_id'],
			];
			
			$customer_accommodation->addCompanyOrderCustomerAccommodation($customer_accommodation_params);
			
		}
		

		//修改公司订单状态为未确认
		$company_order = new CompanyOrder();
		$company_order_params=[
				'company_order_number'=>$params['company_order_number'],
				'company_order_status'=>1
		];
		$company_order_result = $company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
		
		$this->outPut(1);	
		
	}
	/**
	 * 修改公司订单的游客状态(根据公司订单的游客ID)
	 */
	
	public function updateCompanyOrderCustomerStatusByCompanyOrderCustomerId(){
	
		$params = $this->input();
		$paramRule = [
			'company_order_customer_id'=>'number',

			'status'=>'number'
		];
	
		$this->paramCheckRule($paramRule,$params);
		
		$customer = new CompanyOrderCustomer();
		
		$customer_data_result = $customer->updateCompanyOrderCustomerStatusByCompanyOrderCustomerId($params);
		
		
		$company_order_customer_params['company_order_customer_id']=$params['company_order_customer_id'];
		
		
	
		$customer_result = $this->_company_order_customer->getCompanyOrderNumberByCompanyOrderCustomerId($company_order_customer_params);
	
		//团队产品成本修改
		$company_order_team_cost_params['company_order_number'] = $customer_result[0]['company_order_number'];
		$company_order_team_cost_params['now_user_id'] = $params['now_user_id'];
		$this->_company_order_service->updateCompanyOrderTeamCost($company_order_team_cost_params);
		$this->outPut($customer_data_result);
	
	}	
    /**
     * 添加游客占位
     */
    public function addCompanyOrderCustomerOccupy(){

    	$params = $this->input();

    	$paramRule = [
    		'occupy_count'=>'number',
			'company_order_number'=>'string',
    
    	
    	];

    	$this->paramCheckRule($paramRule,$params);
    	$company_order_customer_occupy = new CompanyOrderCustomer();
    	$company_order_customer_occupy_result = $company_order_customer_occupy->addCustomerOccupy($params);
    
    	//团队产品成本修改
    	$company_order_team_cost_params['company_order_number'] = $params['company_order_number'];
    	$company_order_team_cost_params['now_user_id'] = $params['now_user_id'];
    	$this->_company_order_service->updateCompanyOrderTeamCost($company_order_team_cost_params);
    	
    	//修改公司订单状态为未确认
    	$company_order = new CompanyOrder();
    	$company_order_params=[
    			'company_order_number'=>$params['company_order_number'],
    			'company_order_status'=>1
    	];
    	$company_order_result = $company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
    	
    	$this->outPut($company_order_customer_occupy_result);
    }
    

    
    /**
     * 添加分公司游客航班
     */
    public function addCompanyOrderFlight(){
    	$params = $this->input();
    	$paramRule = [
    		'customer_id'=>'number',
    		'company_order_number'=>'string',
    		'user_id'=>'number',
    		'customer_flight'=>'array'
    			 
    	];
    
    	$this->paramCheckRule($paramRule,$params);    	
    	
    	$company_order_flight = new CompanyOrderFlight();
    	$company_order_flight_result = $company_order_flight->addCompanyOrderFlight($params);
    	
    	$this->outPut($company_order_flight_result);    	
    	
    }
    
    /**
     * 修改分公司游客航班 根据游客航班ID
     * 
     */
    public function updateCompanyOrderFlightByCompanyOrderFlightId(){
    	$params = $this->input();
    	$paramRule = [
    		'company_order_flight_id'=>'number',

    		'user_id'=>'number',

    	
    	];
    	
    	$this->paramCheckRule($paramRule,$params);
    	$customer_flight = new CompanyOrderFlight();
    	
    	$customer_flight_result = $customer_flight->updateCompanyOrderFlightByCompanyOrderFlightId($params);
    	$this->outPut($customer_flight_result);
    	
    	
    }
    
    /**
     * 获取分公司订单-游客航班
     */
    public function getCompanyOrderFlight(){
    	$params = $this->input();

    	 
    	$this->paramCheckRule($paramRule,$params);
    	$customer_flight = new CompanyOrderFlight();
   
    	$customer_flight_result = $customer_flight->getCompanyOrderFlight($params);
    	$this->outPut($customer_flight_result);    	
    }
    /**
     * 添加分公司订单游客住宿
     */
    public function addCompanyOrderAccommodation(){
    	$params = $this->input();
    	$paramRule = [
    			'customer_id'=>'number',
    			'company_order_number'=>'string',
    			'user_id'=>'number',
    			'room_code'=>'number',
    			'room_type'=>'number',
    			'check_in'=>"string",
    			'check_on'=>'string'
    
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    	 
    	$company_order_accomodation = new CompanyOrderAccommodation();
    	$company_order_accomodation_result = $company_order_accomodation->addCompanyOrderAccommodation($params);
    	//修改公司订单状态为未确认
    	$company_order = new CompanyOrder();
    	$company_order_params=[
    			'company_order_number'=>$params['company_order_number'],
    			'company_order_status'=>1
    	];
    	$company_order_result = $company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
    	    	 
    	$this->outPut($company_order_accomodation_result);
    	 
    } 
    /**
     * 获取分公司订单游客住宿
     */
    public function getCompanyOrderAccommodation(){
    	$params = $this->input();
    	$this->paramCheckRule($paramRule,$params);
    	$company_order_accommodation = new CompanyOrderAccommodation();
    	$company_order_accommodation_result = $company_order_accommodation->getCompanyOrderAccommodation($params);
    	$this->outPut($company_order_accommodation_result);
    }
    
    /**
     * 修改分公司订单游客住宿
     */
    public function updateCompanyOrderAccommodationByCompanyOrderAccommodationId(){
    	$params = $this->input();
    	$paramRule = [
    			
    		'company_order_accommodation_id'=>'number',
    		'user_id'=>'number',

    	
    	];
    		
    	$this->paramCheckRule($paramRule,$params);
    	
    	$company_order_accomodation = new CompanyOrderAccommodation();
    	$company_order_accomodation_result = $company_order_accomodation->updateCompanyOrderAccommodationByCompanyOrderAccommodationId($params);
    	
    	$this->outPut($company_order_accomodation_result);
    	
    	
    }
    /**
     * 测试 分公司产品 成本
     */
    public function asd(){
    	$params = $this->input();
    	$result = $this->_branch_product_service->getBranchProduct($params);
    	
    	$this->outPut($result);
    }
    /**
     * 添加公司订单-产品
     */
    public function addCompanyOrderProduct(){   	
    	$params = $this->input();
    	
    	$paramRule = [   			 
    		'company_order_number'=>'string',
			//'branch_product_array'=>'array',

    	];
		
    	//首先获取分公司的信息插入到数据库
    	$this->paramCheckRule($paramRule,$params); 
    	$company_order_product = new CompanyOrderProduct();
    	$branch_company_product = new BranchProductTeam();//分公司产品-团队产品
    	$company_order_product_team = new  CompanyOrderProductTeam();//公司订单团队产品
    	$branch_company_product_source= new BranchProductSource();//分公司产品-资源
    	$company_order_product_source = new  CompanyOrderProductSource();//公司订单资源
    	$hotel = new Hotel();
    	$dining =new Dining();
    	$flight = new Flight();
    	$cruise = new Cruise();
    	$visa = new Visa();
    	$tour_guide = new TourGuide();
    	$vehicle =new Vehicle();
    	$scenic_spot =new ScenicSpot();
    	$single_source =new SingleSource();
    	$own_expense = new  OwnExpense();
    	$branch_product = new BranchProduct();
    	
    	$company_order_number = $params['company_order_number']; 
    	$branch_product_array = $params['branch_product_array'];
    	$team_product_array = $params['team_product_array'];
    	

    	
    	//获取订单的数据
    	$company_order_result = $this->_company_order->getCompanyOrder($company_order_number);
    	$company_order_result = $company_order_result[0];
		//获取自己的信息
		$company_params = [
			'company_id'=>$params['user_company_id']	
		];
		$company_result = $this->_company->getCompany($company_params);
    	$company_currency_id = $company_result[0]['currency_id'];
    	//获取订单下的游客
    	$company_order_customer_params = [
    	
    		'company_order_number'=>$params['company_order_number']	
    	];
    	$company_order_customer_result = $this->_company_order_customer->getCompanyOrderCustomer($company_order_customer_params);
    	$company_order_customer_count = count($company_order_customer_result);

    	//首先先 把分公司产品的线路模板以及团队产品存到数据库
  
    	for($i=0;$i<count($branch_product_array);$i++){
    		$branch_product_params_array = $branch_product_array[$i]['branch_product_params_array'];
    		$branch_product_number = $branch_product_array[$i]['branch_product_number'];
    		
    		//首先添加分公司产品
    		$company_order_product_params = [
    				'company_order_number'=>$company_order_number,
    				'branch_product_number'=>$branch_product_array[$i]['branch_product_number'],
    				'branch_product_name'=>$branch_product_array[$i]['branch_product_name'],
    				'now_user_id'=>$params['now_user_id'],
    				'status'=>1
    		];
    	
    		$company_order_product_params['now_user_id'] = $params['now_user_id'];
    		$company_order_product_params['status']= 1;
    		$company_order_product_params['company_order_number'] = $params['company_order_number'];
    		$company_order_product_params['branch_product_number'] = $branch_product_array[$i]['branch_product_number'];
    		
    		
    		
    		//根据分公司编号查询分公司报价
    		$branch_product_result  = $this->_branch_product_service->getBranchProduct($company_order_product_params);

    		//判断是直客还是代理
    		if($company_order_result['channel_type'] == 1){
    			$company_order_product_params['branch_product_price'] = $branch_product_result[0]['distributor_price']*$company_order_customer_count;
    			$company_order_product_params['price_before_tax'] = $branch_product_result[0]['distributor_price']*$company_order_customer_count;
    		
    		}else{
    			$company_order_product_params['branch_product_price'] = $branch_product_result[0]['customer_price']*$company_order_customer_count;
    			$company_order_product_params['price_before_tax'] = $branch_product_result[0]['customer_price']*$company_order_customer_count;
    		
    		}
    		
    		
    		
    		
    		
    		$company_order_product_params['branch_product_cost'] = $branch_product_result[0]['branch_product_cost']*$company_order_customer_count;
    		
    		$company_order_product_params['branch_product_name'] = $branch_product_result[0]['branch_product_name'];
    		
    		$company_order_product_params['price_currency_id'] = $branch_product_result[0]['price_currency_id'];
    		 
    		 
    		$company_order_product_params['cost_currency_id'] = $branch_product_result[0]['price_currency_id'];
    		 
    		$company_order_product_params['supplier_name'] = $branch_product_result[0]['company_name'];
    		

    		

    		$company_order_product_id = $this->_company_order_product->addCompanyOrderProduct($company_order_product_params);
    	
    		//查询分公司资源把资源调取资源数据获得报价插入到订单 资源中
    		
    		$branch_company_product_source_result = $branch_company_product_source->getBranchProductSource2(['branch_product_number'=>$params['branch_product_array'][$i]['branch_product_number']]);

    		for($k=0;$k<count($branch_company_product_source_result);$k++){
    		
    			$k_data_params['company_order_number'] = $company_order_number;
    			$k_data_params['branch_product_number'] = $branch_product_number;
    			$k_data_params['now_user_id'] = $params['now_user_id'];
    			$k_data_params['supplier_type_id'] = $branch_company_product_source_result[$k]['supplier_type_id'];
    			$k_data_params['source_id'] = $branch_company_product_source_result[$k]['source_id'];
    			$k_data_params['source_name'] = $branch_company_product_source_result[$k]['source_name'];
    		
    		
    		
    		
    		
    			//假如 是自费项目
    			if($branch_company_product_source_result[$k]['supplier_type_id']==11){
    		
    				$k_data_params['source_cost'] = 0;
    				if($company_order_result['channel_type'] == 1){//如果是代理
    					$k_data_params['source_cost_univalence'] = $branch_company_product_source_result[$k]['source_cost'];
    		
    				}else{
    					$k_data_params['source_cost_univalence'] = $branch_company_product_source_result[$k]['source_cost'];
    		
    				}
    		
    		
    				$k_data_params['source_price'] = 0;
    				$k_data_params['price_before_tax'] = 0;
    		
    		
    		
    			}else{
    		
    				//计算 成本与报价
    		
    				if($company_order_result['channel_type'] == 1){//如果是代理
    					$k_data_params['source_cost'] = $branch_company_product_source_result[$k]['source_cost']*$company_order_customer_count;
    					$k_data_params['source_cost_univalence'] = $branch_company_product_source_result[$k]['source_cost'];
    		
    					$k_data_params['source_price'] = $branch_company_product_source_result[$k]['source_distributor_price']*$company_order_customer_count;
    					$k_data_params['price_before_tax'] = $branch_company_product_source_result[$k]['source_distributor_price']*$company_order_customer_count;
    		
    				}else{
    					$k_data_params['source_cost'] = $branch_company_product_source_result[$k]['source_cost']*$company_order_customer_count;
    					$k_data_params['source_cost_univalence'] = $branch_company_product_source_result[$k]['source_cost'];
    		
    					$k_data_params['source_price'] = $branch_company_product_source_result[$k]['source_distributor_price']*$company_order_customer_count;
    					$k_data_params['price_before_tax'] = $branch_company_product_source_result[$k]['source_distributor_price']*$company_order_customer_count;
    				}
    			}
    		
    		
    		
    		
    			$k_data_params['price_currency_id'] = $branch_company_product_source_result[$k]['price_currency_id'];
    			$k_data_params['cost_currency_id'] = $branch_company_product_source_result[$k]['cost_currency_id'];
    			$k_data_params['supplier_name'] = $branch_company_product_source_result[$k]['supplier_name'];
    			$k_data_params['team_product_number'] = $branch_company_product_source_result[$k]['team_product_number'];

    			$k_data_params['is_own_source_by_branch_product'] = 1;
    			$a = $company_order_product_source->addCompanyOrderProductSource($k_data_params);

    		}








    		for($j=0;$j<count($branch_product_params_array);$j++){

    			
    			
    			
    			
    			$team_product_params = [
    				'team_product_id'=>$branch_product_params_array[$j]['team_product_id'],
    				'can_watch_company_id'=>$params['user_company_id']	
    			];
 
    		
    			$team_product_result = $this->_team_product->getTeamProductBase($team_product_params);
    			$company_order_product_team_params['settlement_type'] = $team_product_result[0]['settlement_type'];
    			$company_order_product_team_params['supplier_name'] = $team_product_result[0]['company_name'];
    			$company_order_product_team_params['team_product_name'] = $team_product_result[0]['team_product_name'];
    			$company_order_product_team_params['team_product_id'] = $branch_product_params_array[$j]['team_product_id'];
    			$company_order_product_team_params['branch_product_number'] =  $branch_product_array[$i]['branch_product_number'];
    			$company_order_product_team_params['company_order_number'] =  $company_order_number;
    			$company_order_product_team_params['company_order_number'] =  $company_order_number;
    			$company_order_product_team_params['status']=1;
    			$company_order_product_team_params['now_user_id'] = $params['now_user_id'];
    			if($team_product_result[0]['settlement_type']== 1){

    				$company_order_product_team_params['team_product_price']=$team_product_result[0]['once_price']*$company_order_customer_count;
    				$company_order_product_team_params['team_product_cost']=$team_product_result[0]['once_price']*$company_order_customer_count;
    				$company_order_product_team_params['team_product_cost_univalence'] =$team_product_result[0]['once_price'];
    			}else{
    				$company_order_product_team_params['team_product_price']=$team_product_result[0]['real_price']*$company_order_customer_count;
    				$company_order_product_team_params['team_product_cost']=$team_product_result[0]['real_price']*$company_order_customer_count;
    				$company_order_product_team_params['team_product_cost_univalence'] =$team_product_result[0]['real_price'];
    			}
    			$company_order_product_team_params['price_before_tax'] = $company_order_product_team_params['team_product_price'];
    			$company_order_product_team_params['cost_currency_id'] = $team_product_result[0]['currency_id'];
    			$company_order_product_team_params['price_currency_id'] = $team_product_result[0]['currency_id'];
    			
    			//$company_order_product_team_params['team_product_cost']=$branch_company_product_result[$j]['branch_cost']*$company_order_customer_count;
    			$company_order_product_team_id = $company_order_product_team->addCompanyOrderProductTeam($company_order_product_team_params);
    			//插入模板数据库
    			$company_order_product_params = [
    					'company_order_number'=>$company_order_number,
    					'branch_product_number'=>$branch_product_array[$i]['branch_product_number'],
    					'company_order_product_id'=>$company_order_product_id,
    					'route_template_number'=>$branch_product_params_array[$j]['route_template_number'],
    					'team_product_id'=>$branch_product_params_array[$j]['team_product_id'],
    					'now_user_id'=>$params['now_user_id'],
    					'company_order_product_team_id'=>$company_order_product_team_id	
    			];
    			
    			$b = $this->_company_order_product_template->addCompanyOrderProductTeamplate($company_order_product_params);

    			
    			
    			//开始查询团队产品里的资源插入数据库
    			$team_product_allocation_params = [
    				'team_product_id'=>	 $team_product_result[0]['team_product_id'],
    				'status'=>1	
    			];
 
    			$team_product_allocation_result= $this->_team_product_allocation->getTeamProductAllocation($team_product_allocation_params);

				for($k=0;$k<count($team_product_allocation_result);$k++){
					$k_data_params = [];
					//获取资源信息
					$source_info_params = [
						'supplier_type_id'=>$team_product_allocation_result[$k]['supplier_type_id'],
						'source_id'=>$team_product_allocation_result[$k]['source_id']	
					];
					$source_result = $this->_source_service->getSourceInfo($source_info_params);
					
					$k_data_params['branch_product_number'] = $branch_product_number;
					$k_data_params['company_order_number'] = $company_order_number;				
					$k_data_params['now_user_id'] = $params['now_user_id'];
					$k_data_params['supplier_type_id'] = $team_product_allocation_result[$k]['supplier_type_id'];
					$k_data_params['source_id'] = $team_product_allocation_result[$k]['source_id'];
					$k_data_params['source_name'] = $source_result[0]['source_name'];
					$k_data_params['team_product_allocation_id'] = $team_product_allocation_result[$k]['team_product_allocation_id'];
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
    				$k_data_params['team_product_id'] =$branch_product_params_array[$j]['team_product_id'];
    				
    				$this->_company_order_product_source->addCompanyOrderProductSource($k_data_params);
				}
				

    			
    		}
    	}    	
  
    	//再把团队产品放入数据库
    	for($i=0;$i<count($team_product_array);$i++){
    	

    		$team_product_params = [
    			'team_product_id'=>$team_product_array[$i]['team_product_id'],
    			'can_watch_company_id'=>$params['user_company_id']
    		];
    		
    		
    	
    		$team_product_result = $this->_team_product->getTeamProductBase($team_product_params);
    	
    		$company_order_product_team_params['settlement_type'] = $team_product_result[0]['settlement_type'];
    		$company_order_product_team_params['supplier_name'] = $team_product_result[0]['company_name'];
    		$company_order_product_team_params['team_product_name'] = $team_product_result[0]['team_product_name'];
    		$company_order_product_team_params['team_product_id'] = $team_product_array[$i]['team_product_id'];
    	
    		$company_order_product_team_params['company_order_number'] =  $company_order_number;
    		$company_order_product_team_params['company_order_number'] =  $company_order_number;
    		$company_order_product_team_params['status']=1;
    		$company_order_product_team_params['now_user_id'] = $params['now_user_id'];
    		if($team_product_result[0]['settlement_type']== 1){
    			 
    			$company_order_product_team_params['team_product_price']=$team_product_result[0]['once_price']*$company_order_customer_count;
    			$company_order_product_team_params['team_product_cost']=$team_product_result[0]['once_price']*$company_order_customer_count;
    			$company_order_product_team_params['team_product_cost_univalence'] =$team_product_result[0]['once_price'];
    		}else{
    			$company_order_product_team_params['team_product_price']=$team_product_result[0]['real_price']*$company_order_customer_count;
    			$company_order_product_team_params['team_product_cost']=$team_product_result[0]['real_price']*$company_order_customer_count;
    			$company_order_product_team_params['team_product_cost_univalence'] =$team_product_result[0]['real_price'];
    		}
    		$company_order_product_team_params['price_before_tax'] = $company_order_product_team_params['team_product_price'];
    		$company_order_product_team_params['cost_currency_id'] = $team_product_result[0]['currency_id'];
    		$company_order_product_team_params['price_currency_id'] = $team_product_result[0]['currency_id'];
    		$company_order_product_team_params['is_type'] = 2;
    		//$company_order_product_team_params['team_product_cost']=$branch_company_product_result[$j]['branch_cost']*$company_order_customer_count;
    		$company_order_product_team_id = $company_order_product_team->addCompanyOrderProductTeam($company_order_product_team_params);
    	
    		//插入模板数据库
    		$company_order_product_params = [
    			'company_order_number'=>$company_order_number,
				'is_type'=>2,
    			'route_template_number'=>$team_product_array[$i]['route_template_number'],
    			'team_product_id'=>$team_product_array[$i]['team_product_id'],
    			'now_user_id'=>$params['now_user_id'],
    			'company_order_product_team_id'=>$company_order_product_team_id	
    		];
    	
    		$this->_company_order_product_template->addCompanyOrderProductTeamplate($company_order_product_params);
    		 
    	
    		

    		//开始查询团队产品里的资源插入数据库
    		$team_product_allocation_params = [
    				'team_product_id'=>	 $team_product_result[0]['team_product_id'],
    				'status'=>1
    		];
    		
    		
    		//先把团队产品当成团费资源插入到数据库
    		
    		if($team_product_result[0]['settlement_type'] == 1){
    			$price = $team_product_result[0]['once_price'];
    		}else{
    			$price = $team_product_result[0]['real_price'];
    		}
    		$team_product_source_params = [
    			'company_order_number'=>$company_order_number,
    			'supplier_type_id'=>14,
    			'company_order_product_team_id'=>$company_order_product_team_id,
    			'source_name'=> $team_product_result[0]['team_product_name'],
						
				'source_price'=>$price*$company_order_customer_count,
				'price_currency_id'=> $team_product_result[0]['currency_id'],
				'is_receivable_company'=>2,
				'now_user_id'=>$params['now_user_id'],
				'team_product_id'=>$team_product_result[0]['team_product_id'],
				'supplier_name'=>$params['user_company_name'],
						
				'is_type'=>2,
				'is_settle'=>2,
    		];

    	
    		$this->_company_order_product_source->addCompanyOrderProductSource($team_product_source_params);
    		
    	
    		//exit();
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
    			$k_data_params['team_product_allocation_id'] = $team_product_allocation_result[$k]['team_product_allocation_id'];
    	
    			if($team_product_allocation_result[$k]['supplier_type_id']==11){
    			
    				$k_data_params['source_cost_univalence'] = $source_result[0]['normal_price']*$team_product_allocation_result[$k]['source_count'];
    				/*
    				if($params['user_company_id'] ==  $source_result[0]['source_name']){
    					$k_data_params['source_cost_univalence'] = $source_result[0]['normal_price'];
    				}else{
    					$k_data_params['source_cost_univalence'] = $team_product_allocation_result[$k]['source_total_price'];
    				}
    				*/
    				$k_data_params['source_cost'] = 0;
    	
    				$k_data_params['source_price'] = 0;
    				$k_data_params['price_before_tax'] = 0;
    			}else{
    				/*
    				if($team_product_result[0]['settlement_type']==1){
    					continue;//跳出当前循环
    					();
    				}
    				*/
    				/*
    				//开始判断是否是本公司
    				if($params['user_company_id'] ==  $source_result[0]['source_name']){
    					$k_data_params['source_cost_univalence'] = $source_result[0]['normal_price'];
    				}else{
    					$k_data_params['source_cost_univalence'] = $team_product_allocation_result[$k]['source_total_price'];
    				}
    				*/ 
    				$k_data_params['source_cost_univalence'] = $source_result[0]['normal_price'];
    				$k_data_params['source_cost'] = $k_data_params['source_cost_univalence'] *$company_order_customer_count;
    				//$k_data_params['source_cost_univalence'] = $team_product_allocation_result[$k]['source_total_price'];
    				$k_data_params['source_price'] = $k_data_params['source_cost_univalence']*$company_order_customer_count;
    				$k_data_params['price_before_tax'] = $k_data_params['source_cost_univalence']*$company_order_customer_count;
    				 
    			}
    		
    			
    			$k_data_params['price_currency_id'] = $team_product_allocation_result[$k]['payment_currency_id'];
    			$k_data_params['cost_currency_id'] = $team_product_allocation_result[$k]['payment_currency_id'];
    			$k_data_params['supplier_name'] = $source_result[0]['supplier_name'];
    			$k_data_params['team_product_id'] =$team_product_array[$i]['team_product_id'];
    			$k_data_params['is_type'] =2;
    			
    		
    			
    			$r = $this->_company_order_product_source->addCompanyOrderProductSource($k_data_params);
    		
    		}
    		 
    		 
    	
    	
    	}	
    	
    	//团队产品放入数据库 结束
    	$company_order_name_update_params = [
    		'company_order_number'=>$params['company_order_number']	
    	];
    	$this->_company_order_service->updateCompanyOrderName($company_order_name_update_params);
    	
    	//修改公司订单状态为未确认
    	$company_order = new CompanyOrder();
    	$company_order_params=[
    			'company_order_number'=>$params['company_order_number'],
    			'company_order_status'=>1
    	];
    	$company_order_result = $company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
    	
    	
    	$this->outPut(1);
    	exit();
    	
//     	for($i=0;$i<count($branch_product_array);$i++){
//     		$company_order_product_params = [];
//     		$company_order_product_params['now_user_id'] = $params['now_user_id'];
//     		$company_order_product_params['status']= 1;
//     		$company_order_product_params['company_order_number'] = $params['company_order_number'];
//     		$company_order_product_params['branch_product_number'] = $branch_product_array[$i]['branch_product_number'];
    		
    		
    		
//     		//根据分公司编号查询分公司报价
//     		$branch_product_result  = $this->_branch_product_service->getBranchProduct($company_order_product_params);
    		

           
//             $company_order_product_params['supplier_name'] = $branch_product_result[0]['company_name'];
            
//             //插入公司信息
//     		$company_order_product_result = $company_order_product->addCompanyOrderProduct($company_order_product_params);
    
//     		//插入分公司的团队产品
//     		//首先查询有几个团队产品
    		
//     		$branch_company_product_params['status'] = 1;
//     		$branch_company_product_params['branch_product_number'] =  $params['branch_product_array'][$i]['branch_product_number'];
//     		$branch_company_product_result = $branch_company_product->getBranchProductTeam($branch_company_product_params);
    	
//     		//拿到信息后去查询报价并插入数据库
//     		for($j=0;$j<count($branch_company_product_result);$j++){
//     			//首先判断团队产品是否是一口价
//     			$team_product_params = [
//     				'team_product_number'=>$branch_company_product_result[$j]['team_product_number']	
//     			];
//     			$team_product_result = $this->_team_product->getTeamProduct($team_product_params);
// //     			if($team_product_result[0]['settlement_type']==2){//如果真实 结算则不添加进成本
// //     				continue;
    				
// //     			}
//     			$company_order_product_team_params['settlement_type'] = $team_product_result[0]['settlement_type'];
    			
//     			$company_order_product_team_params['team_product_name'] = $branch_company_product_result[$j]['team_product_name'];
//     			$company_order_product_team_params['team_product_number'] = $branch_company_product_result[$j]['team_product_number'];
//     			$company_order_product_team_params['branch_product_number'] =  $params['branch_product_array'][$i]['branch_product_number'];
//     			$company_order_product_team_params['company_order_number']=$params['company_order_number'];
//     			if($company_order_result['channel_type'] == 1){
    				
    				

//     				$company_order_product_team_params['team_product_price']=$branch_company_product_result[$j]['branch_distributor_price']*$company_order_customer_count;
    				 
//     			}else{
//     				$company_order_product_team_params['team_product_price']=$branch_company_product_result[$j]['branch_customer_price']*$company_order_customer_count;
//     			}
    			
    
//     			$company_order_product_team_params['team_product_cost']=$branch_company_product_result[$j]['branch_cost']*$company_order_customer_count;
    			 
//     			$company_order_product_team_params['team_product_cost_univalence'] = $branch_company_product_result[$j]['branch_cost'];
//     			$company_order_product_team_params['price_currency_id']=$branch_company_product_result[$j]['price_currency_id'];
//                 $company_order_product_team_params['cost_currency_id']=$branch_company_product_result[$j]['cost_currency_id'];
//                 $company_order_product_team_params['supplier_name']=$branch_company_product_result[$j]['supplier_name'];

// //     			$cost  = action('index/product/getTeamProductBaseForController',['status'=>1,'team_product_number' => $branch_company_product_result[$j]['team_product_number']]); //第一种
// //     			if($cost[0]['settlement_type'] == 1){
// //     				$company_order_product_team_params['team_product_cost']=$cost[0]['once_price'];
// //     			}else{
// //     				$company_order_product_team_params['team_product_cost']=$cost[0]['real_price'];
// //     			}
//                 $company_order_product_team_params['status']=1;
//                 $company_order_product_team_params['now_user_id'] = 1;
//     			$r = $company_order_product_team->addCompanyOrderProductTeam($company_order_product_team_params);
    			
//     		}
    		
//     		//查询分公司资源把资源调取资源数据获得报价插入到订单 资源中
    		
//     		$branch_company_product_source_result = $branch_company_product_source->getBranchProductSource2(['branch_product_number'=>$params['branch_product_array'][$i]['branch_product_number']]);
			
//     		for($k=0;$k<count($branch_company_product_source_result);$k++){
    		
//     			$k_data_params['company_order_number'] = $params['company_order_number'];
//     			$k_data_params['branch_product_number'] = $params['branch_product_array'][$i]['branch_product_number'];
//     			$k_data_params['now_user_id'] = $params['now_user_id'];
//     			$k_data_params['supplier_type_id'] = $branch_company_product_source_result[$k]['supplier_type_id'];
//     			$k_data_params['source_id'] = $branch_company_product_source_result[$k]['source_id'];
//     			$k_data_params['source_name'] = $branch_company_product_source_result[$k]['source_name'];
    			
    			
    			
    			
    			
//     			//假如 是自费项目
//     			if($branch_company_product_source_result[$k]['supplier_type_id']==11){
// //     				//通过自费项目去查询人数
// //     				$company_order_relation_params = [
// //     					'status'=>1,
// //     					'company_order_number'=>$company_order_number,
// //     					'company_order_product_source_id'=>$branch_company_product_source_result[$k]['company_order_product_source_id']	
// //     				];
// //     				$company_order_relation_result = $this->_company_order_relation->getCompanyOrderRelation($company_order_relation_params);
    				
//     				$k_data_params['source_cost'] = 0;
//     				if($company_order_result['channel_type'] == 1){//如果是代理
//     					$k_data_params['source_cost_univalence'] = $branch_company_product_source_result[$k]['source_cost'];
    				 				
//     				}else{
//     					$k_data_params['source_cost_univalence'] = $branch_company_product_source_result[$k]['source_cost'];
    				    				
//     				}
    				
    				
//     				$k_data_params['source_price'] = 0;
//     				$k_data_params['price_before_tax'] = 0;
    				
    				
    				
//     			}else{

//     				//计算 成本与报价
   
//     				if($company_order_result['channel_type'] == 1){//如果是代理
//     					$k_data_params['source_cost'] = $branch_company_product_source_result[$k]['source_cost']*$company_order_customer_count;
//     					$k_data_params['source_cost_univalence'] = $branch_company_product_source_result[$k]['source_cost'];
    						
//     					$k_data_params['source_price'] = $branch_company_product_source_result[$k]['source_distributor_price']*$company_order_customer_count;
//     					$k_data_params['price_before_tax'] = $branch_company_product_source_result[$k]['source_distributor_price']*$company_order_customer_count;
    						
//     				}else{
//     					$k_data_params['source_cost'] = $branch_company_product_source_result[$k]['source_cost']*$company_order_customer_count;
//     					$k_data_params['source_cost_univalence'] = $branch_company_product_source_result[$k]['source_cost'];
    						
//     					$k_data_params['source_price'] = $branch_company_product_source_result[$k]['source_distributor_price']*$company_order_customer_count;
//     					$k_data_params['price_before_tax'] = $branch_company_product_source_result[$k]['source_distributor_price']*$company_order_customer_count;    						
//     				}
//     			}
		
    	
		
    			
//                 $k_data_params['price_currency_id'] = $branch_company_product_source_result[$k]['price_currency_id'];
//                 $k_data_params['cost_currency_id'] = $branch_company_product_source_result[$k]['cost_currency_id'];
//                 $k_data_params['supplier_name'] = $branch_company_product_source_result[$k]['supplier_name'];
//                 $k_data_params['team_product_number'] = $branch_company_product_source_result[$k]['team_product_number'];
           
//                 $company_order_product_source->addCompanyOrderProductSource($k_data_params);
    			
//     		}

    		
//     	}

//     	$this->outPut(1);
    }

    /**
     * 添加游客回执单上传文件
     * 韩
     */
    public function addCompanyOrderCustomerGuideReceiptFile(){

        $params = $this->input();
        $guide_receipt_file = new CompanyOrderCustomerGuideReceiptFile();
		
        $guide_receipt_file_result = $guide_receipt_file->addCompanyOrderCustomerGuideReceiptFile($params);

        $this->outPut($guide_receipt_file_result);
    }

//     /**
//      * 获取游客回执单上传文件
//      * 韩
//      */
    public function getCompanyOrderCustomerGuideReceiptFile(){

        $params = $this->input();
        $guide_receipt_file = new CompanyOrderCustomerGuideReceiptFile();

        $guide_receipt_file_result = $guide_receipt_file->getCompanyOrderCustomerGuideReceiptFile($params);

        $this->outPut($guide_receipt_file_result);
    }
    //修改游客行程单
    public function updateCompanyOrderGuideReceipt(){
    
    	$params = $this->input();
    	$guide_receipt_file = new CompanyOrderCustomerGuideReceiptFile();
    
    	$guide_receipt_file_result = $guide_receipt_file->updateCompanyOrderGuideReceipt($params);
    
    	$this->outPut($guide_receipt_file_result);
    }
    
    public function addCompanyOrderApprove(){
    	$params = $this->input();
    	$paramRule = [
    		'company_order_number'=>'string',
    	];
    	
    	$this->paramCheckRule($paramRule,$params);
    	$company_service = new CompanyOrderService();
		//把该订单的所有信息保存一份到备份表
		$company_order_params = [
			'company_order_number'=>$params['company_order_number']	
		];
    	$company_order_result = $this->_company_order->getCompanyOrder($company_order_params);

    	//开始 同步更新数据到 审批表中
    	//先订单
    
    	$result=$this->_company_order_approve->addCompanyOrderApprove($company_order_params);
    	
    	//审批之后则把订单锁住
    	$company_order_params = [
    		'is_approve'=>1,
    		'company_order_number'=>$params['company_order_number'],
    		'now_user_id'=>$params['now_user_id']	
    	];
    	$this->_company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
    	$this->outPut($result);
    }
    
    /**
     * 更新公司订单应收和应付 以及提交订单把状态变更为1
     */
    public function updateCompanyOrderReveivableAndCope(){
    	$params = $this->input();
    	$paramRule = [
    		'company_order_number'=>'string',
    	];

    	$this->paramCheckRule($paramRule,$params);
    	$company_service = new CompanyOrderService();
    	
    	//把状态变更为1
    	$company_order_params = [
    		'status'=>1,
    		'company_order_number'=>$params['company_order_number']
    	];
    	$this->_company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
    	
    	//先算排队
    	$customer_lineup = new CompanyOrderService();
    	$customer_lineup->updateCustomerLineUp($params);
    	
    	//再算应收应付

    	//游客编号生成
    	$result = $company_service->updateCompanyOrderPriceAndCope($params);
    	//修改公司订单名称
    	$company_service->updateCompanyOrderName($params);
    	$this->outPut($result);
    	
    }
    
    public  function aa(){
    	$params = $this->input();
    	$paramRule = [
    		'company_order_number'=>'string',
    	];
    	
    	$this->paramCheckRule($paramRule,$params);

    	$customer_lineup = new CompanyOrderService();
    	$result = $customer_lineup->updateCustomerLineUp($params);

    	 
    	//游客编号生成
    	 
    	$this->outPut($result);
    	
    	
    }
    
    /**
     * 添加分公司产品-自定义
     */
    public function addCompanyOrderProductDiy(){
    	$params = $this->input();
    	$paramRule = [
    		'company_order_number'=>'string',
    		'diy_name'=>'string',
    
    		'cost_currency_id'=>'number',
    		'diy_cost'=>'string',
    		'supplier_id'=>'string'	
    			
    	];

    	//首先获取分公司的信息插入到数据库
    	$this->paramCheckRule($paramRule,$params);    	


    	$params['company_order_product_diy_number'] = help::getNumber(8);
    	
    	$params['diy_cost_univalence'] = $params['diy_cost'];
    
    	$params['diy_price'] = $params['diy_cost'];
    	$params['price_before_tax'] = $params['diy_cost'];
    	$company_order_product_diy = new CompanyOrderProductDiy();
    	
    	$company_order_product_diy_result = $company_order_product_diy->addCompanyOrderProductDiy($params);
    	
    	
    	$company_order_name_update_params = [
    			'company_order_number'=>$params['company_order_number']
    	];
    	
    	$this->_company_order_service->updateCompanyOrderName($company_order_name_update_params);
    	//修改公司订单状态为未确认
    	$company_order = new CompanyOrder();
    	$company_order_params=[
    			'company_order_number'=>$params['company_order_number'],
    			'company_order_status'=>1
    	];
    	$company_order_result = $company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
    	
    	$this->outPut($company_order_product_diy_result);
    	 
    }
    
    /**
     * 添加公司订单-报价
     */
    public function addCompanyOrderProductDiyPrice(){
    	$public_service = new PublicService();
    	$params = $this->input();
    	$paramRule = [
    		'company_order_number'=>'string',
    		'diy_name'=>'string',
    		'diy_type'=>'int',
    		'price_currency_id'=>'number',
			'diy_price'=>'string'
    
    	];
    
    	//首先获取分公司的信息插入到数据库
    	$this->paramCheckRule($paramRule,$params);
    

    
    	$params['diy_price'] = $params['diy_price'];
    	if(is_numeric($params['tax_id'])){//如果有税ID
    		$tax_result_params = [
    			'tax_id'=>$params['tax_id']
    		];
    		
    		$tax_result = $public_service->getTaxTotal($tax_result_params);
    		$params['tax_price'] =number_format($params['diy_price']*($tax_result/100),2);
    		$params['price_before_tax'] = $params['diy_price'] - number_format($params['diy_price']*($tax_result/100),2);
    		
    	}else{
    		$params['tax_price'] = 0;
    		$params['price_before_tax'] = $params['diy_price'];
    		
    	}
    	
    	
    	$company_order_product_diy = new CompanyOrderProductDiyPrice();
    	 
    	$company_order_product_diy_result = $company_order_product_diy->addCompanyOrderProductDiyPrice($params);
    	//修改公司订单状态为未确认
    	$company_order = new CompanyOrder();
    	$company_order_params=[
    			'company_order_number'=>$params['company_order_number'],
    			'company_order_status'=>1
    	];
    	$company_order_result = $company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
    	
    	$this->outPut($company_order_product_diy_result);
    
    }   
    
    /**
     * 同步应收应付到团队产品以及资源
     */
    public function updateReceivableAndCopeToCompanyOrderProduct(){
    	$params = $this->input();
    	
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
    		$team_product_number = $cope_result[$i]['team_product_number'];
    		if(!empty($team_product_number)){
				if(!in_array($team_product_number, $team_product_array)){
					$team_product_array[] = $team_product_number;
				}							    				
    		}
    	}
    	//把每个团队产品编号 状态变更为0
    	for($i=0;$i<count($team_product_array);$i++){
    		$update_status = [
    			'company_order_number'=>$company_order_number,
    			'team_product_number'=>$team_product_array[$i],
    			'status'=>0	
    		];
    		
    		$this->_company_order_product_team->updateCompanyOrderTeamStatus($update_status);
    	
    		$this->_company_order_product_source->updateCompanyOrderSourceStatus($update_status);
    		
    		
    	}
    	
		
    	
    	for($i=0;$i<count($cope_result);$i++){
    		$team_product_number = $cope_result[$i]['team_product_number'];
    		
    		
    		if(!empty($team_product_number)){
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
    				'source_id'=>$source_result['source_id']
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
    					'team_product_number'=>$team_product_number,
    					'is_receivable_company'=>1,
    					'company_order_number'=>$company_order_number	
    						
    				];
    				if($cope_result[$i]['fee_type_type']==207){
    					$update_params['team_product_receivable_type'] = 2;
    				}else{
    					$update_params['team_product_receivable_type'] = 1;
    				}
    				$rr = $this->_company_order_product_source->addCompanyOrderProductSource($update_params);
    			
    			}
    		}
    		
    	}
    	
    	
    
    	
    }
    
    public function aassd(){
    	$params = $this->input();
    	
    	$this->_company_order_service->updateReceivableAndCopeToCompanyOrderProduct($params);
    }
    /**
     * 获取公司订单-产品
     */
    public function getCompanyOrderProduct(){
    	$params = $this->input();
    	$paramRule = [
    		'company_order_number'=>'string',
    		'status'=>'number'	
    	];



    	
   		$data = $this->_company_order_service->getCompanyOrderProduct($params);
   		//首先判断自己的分公司
   		$user_params = [
   			'user_id'=>$params['now_user_id']	
   		];
   		$user_result = $this->_user->getUser($user_params);


   		//开始循环判断是否是本公司的数据 应付成本是否可修改
   		$company_order_product_team = $data['company_order_product_team'];
   		for($i=0;$i<count($company_order_product_team);$i++){
   			//首先获取 团队产品信息
//    			$team_product_params= [
//    				'team_product_number'=>	$company_order_product_team[$i]['team_product_number']
//    			];
//    			$team_product_result = $this->_team_product->getTeamProduct($team_product_params);
//    			if($user_result[0]['company_id'] == $team_product_result[0]['company_id']){
//    				$company_order_product_team[$i]['is_own_company'] = 1;
//    			}else{
//    				$company_order_product_team[$i]['is_own_company'] = 2;
//    			}
   			$company_order_product_team[$i]['is_own_company'] = 2;
   		}
   		
   		
   		
   		$company_order_product_source = $data['company_order_product_source'];


   		for($i=0;$i<count($company_order_product_source);$i++){
   			//首先获取资源信息
//    			$source_params= [
//    				'supplier_type_id'=>$company_order_product_source[$i]['supplier_type_id'],
//    				'source_id'=>$company_order_product_source[$i]['source_id'],
//    			];
//    			$source_result = $this->_source_service->getSource($source_params);
   			
   			if(!empty($company_order_product_source[$i]['team_product_id'])){
   				$company_order_product_source[$i]['is_own_company'] = 2;
   			}else{
   				$company_order_product_source[$i]['is_own_company'] = 1;
   			}
   			
   			if($params['user_company_id'] != $company_order_product_source[$i]['source_company_id']){
   				$company_order_product_source[$i]['supplier_name'] = $company_order_product_source[$i]['source_company_name'];
   			}
   		}
   		$data['company_order_product_team'] = $company_order_product_team;
   		$data['company_order_product_source'] = $company_order_product_source;

    	$this->outPut($data);

    }
    /**
     * 修改公司订单游客住宿
     */
    public function updateCompanyOrderProductByCompanyOrderProductId(){
        $params = $this->input();
      
        $paramRule = [

            'company_order_product_id'=>'number',
            'user_id'=>'number',


        ];

        $this->paramCheckRule($paramRule,$params);

        $company_order_product = new CompanyOrderProduct();
        $company_order_product_result = $company_order_product->updateCompanyOrderProductByCompanyOrderProductId($params);

        $this->outPut($company_order_product_result);


    }
	
    /**
     * 修改公司订单产品的游客
     */
    public function updateCompanyOrderProductCustomer(){
    	 
    	$params = $this->input();
    	
    	$paramRule = [
    	
    		'company_order_number'=>'string',

    			
    	
    	
    	];
    	$this->paramCheckRule($paramRule,$params);
    	

    	$relation = new CompanyOrderRelation();
    	//首先把该ID下的所有用户状态变成0
    	$relation->updateCompanyOrderRelation($params);
    	//再插入数据库
    	
    	$company_order_relation_result = $relation->addCompanyOrderRelation($params);
    	
    	$this->outPut($company_order_relation_result);
    	
    	
    	
    	 
    	 
    }
    
    
   
    /**
     * 获取公司订单
     */
    public function getCompanyOrder(){
    	$params = $this->input();

    	$company_order = new  CompanyOrder();
    	$distributor = new Distributor();
    	$company_order_product = new CompanyOrderProduct();
    	$company_order_source = new CompanyOrderProductSource();
    	$company_order_diy = new CompanyOrderProductDiy();
    	$company_order_count =  $company_order->getCompanyOrder($params,true);
    
    	$company_order_result = $company_order->getCompanyOrder($params);

    	$company_order_result = $this->_company_order_service->getCompanyOrderReceivableCopeTrueReceivableTrueCope($company_order_result);
			
 		
    	
    	/**
    	 * 通过订单查询团队产品编号
    	 */
    	
    	
    	for($i=0;$i<count($company_order_result);$i++){
    		//通过订单编号查询团队产品编号
    		$company_order_product_team_params = [
    			'company_order_number'=>$company_order_result[$i]['company_order_number'],
    			'status'=>1	
    		];
    		$company_order_team_result = $this->_company_order_product_team->getCompanyOrderProductTeam($company_order_product_team_params);
    		$team_product ='';
    		for($j=0;$j<count($company_order_team_result);$j++){
    			$team_product.=','.$company_order_team_result[$j]['team_product_number'];
    		}
    		$team_product = trim($team_product,',');
    		$company_order_result[$i]['team_product_number'] = $team_product;
    		
    		if($company_order_result[$i]['channel_type']==1){
    			$distributor_params = [
    				'distributor_id'=>$company_order_result[$i]['distributor_id']
    			];
    			$company_order_result[$i]['distributor_info'] =  $distributor->getDistributor($distributor_params);
    		}

    		
    		$company_order_receivable_info_params = [
    			'company_order_number'=>$company_order_result[$i]['company_order_number'],
    			'now_user_id'=>$params['now_user_id']	
    		];
    		$company_order_result[$i]['company_order_receivable_info'] = $this->_company_order_service->getCompanyOrderReceivableInfo($company_order_receivable_info_params);
    	}
    	

    
 
    	$this->outPut($company_order_result,'',$company_order_count);
    	
    }
    
    
    /**
     * 修改公司订单产品
     */
    public function updateCompanyOrderProduct(){
    	$params = $this->input();
    	
    	$paramRule = [
    		
    		'type'=>'string',

    	];
    	$this->paramCheckRule($paramRule,$params);
    	
    	if($params['type']==1){
    		$paramRule = [
    		
    			'company_order_number'=>'string',
    			'branch_product_number'=>'string',
    		
    		];
    		$this->paramCheckRule($paramRule,$params);    
    		
    		$company_order_product = new CompanyOrderProduct();
    
    		$result = $company_order_product->updateCompanyOrderProduct($params);
    	}else if($params['type']==2){
    		$paramRule = [
    		
    			'company_order_product_diy_number'=>'string',

    		
    		];    		
    		
    		$this->paramCheckRule($paramRule,$params);
    		$company_order_product_diy = new CompanyOrderProductDiy();
    		$result = $company_order_product_diy->updateCompanyOrderProductDiyByDiyNumber($params);
    	}else if($params['type']==3){
    		$paramRule = [
    		
    			'company_order_product_team_id'=>'number',

    		
    		];    		
    		
    		$this->paramCheckRule($paramRule,$params);
    		$company_order_product_team = new CompanyOrderProductTeam();
    		$company_order_product_team->updateCompanyOrderTeamByCompanyOrderNumberAndTeamProductId($params);
    		$result =1;
    	}else if($params['type']==4){
    		$paramRule = [
    	
    			'company_order_product_diy_price_id'=>'string',
    	
    	
    		];
    	
    		$this->paramCheckRule($paramRule,$params);
    		$company_order_product_diy = new CompanyOrderProductDiyPrice();
    		$result = $company_order_product_diy->updateCompanyOrderProductDiyPriceById($params);
    	}
    	$company_order_name_update_params = [
    		'company_order_number'=>$params['company_order_number']
    	];
    	$this->_company_order_service->updateCompanyOrderName($company_order_name_update_params);
    	//修改公司订单状态为未确认
    	$company_order = new CompanyOrder();
    	$company_order_params=[
    			'company_order_number'=>$params['company_order_number'],
    			'company_order_status'=>1
    	];
    	$company_order_result = $company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
    	
		$this->outPut($result);
    	
    }
    
    /**
     * 修改公司订单成本与报价
     */
    public function updateCompanyOrderCostAllAndPrice(){
    	$params = $this->input();
    	$paramRule = [

    
    		'info'=>'array'	
    			
    	
    	];
    	
    
    	$this->paramCheckRule($paramRule,$params);
    	$company_order_product_team = new CompanyOrderProductTeam();
    	$company_order_product_source = new CompanyOrderProductSource();
    	$company_order_product_diy  = new CompanyOrderProductDiy();
    	$company_order_product = new CompanyOrderProduct();
    	$info = $params['info'];
    	$info['user_id'] =$params['now_user_id'];
    	for($i=0;$i<count($info);$i++){
    		if($info[$i]['type']==2){//代表资源

				$result = $company_order_product_source->updateCompanyOrderSource($info[$i]);
			}else if($info[$i]['type']==3){//代表自定义产品
	
				$result = $company_order_product_diy->updateCompanyOrderProductDiyByDiyNumber($info[$i]);
			}else if($info[$i]['type']==4){
	
				$result = $company_order_product->updateCompanyOrderProductById($info[$i]);
			}
    	}

    	//修改公司订单状态为未确认
    	$company_order = new CompanyOrder();
    	$company_order_params=[
    			'company_order_number'=>$params['company_order_number'],
    			'company_order_status'=>1
    	];
    	$company_order_result = $company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
    	
		
		
    	$this->outPut($result);
    	
    }
    
    /**
     * 修改公司订单成本与报价
     */
    public function updateCompanyOrderCostAndPrice(){
    	$params = $this->input();
    	$paramRule = [

    		'company_order_number'=>'string'

    	];
    	
    	$this->paramCheckRule($paramRule,$params);
    	$company_order_product_team = new CompanyOrderProductTeam();
    	$company_order_product_source = new CompanyOrderProductSource();
    	$company_order_product_diy  = new CompanyOrderProductDiy();
    	$company_order_product_diy_price  = new CompanyOrderProductDiyPrice();
    	$company_order_product = new CompanyOrderProduct();
    	
    	$team_product_array = $params['team_product_array'];
    	$source_array = $params['source_array'];
    	$diy_array = $params['diy_array'];
    	$branch_product_array = $params['branch_product_array'];
    	$diy_price = $params['company_order_product_diy_price'];
    
 		//首先通过团队公司订单获取公司的信息再获取公司的时区
 		$company_order_params = $params['company_order_number'];
    	$company_order_result = $this->_company_order->getCompanyOrder($company_order_params);
    	$company_id = $company_order_result[0]['company_id'];
    	$company_params = [
    		'company_id'=>$company_id	
    	];
    	$company_result = $this->_company->getCompany($company_params);
    	$timezone = $company_result[0]['timezone'];
    	
    	for($i=0;$i<count($team_product_array);$i++){
    		
    		$substribe_time = $team_product_array[$i]['substribe_time'];

    		if(!empty($substribe_time)){
    			$h = 8-$timezone+8;
    			$utc_substribe_time = strtotime("$h hours",$substribe_time);
    		}else{
    			$utc_substribe_time ='';
    		}
    		
    		
    		$team_product_array_params = [
    			'now_user_id'=>$params['now_user_id'],
    			'company_order_product_team_id'=>$team_product_array[$i]['company_order_product_team_id'],
    			'price_currency_id'=>$team_product_array[$i]['price_currency_id'],
    			'remark'=>$team_product_array[$i]['remark'],
    			'team_product_price'=>$team_product_array[$i]['team_product_price'],
    			'invoice_number'=>$team_product_array[$i]['invoice_number'],
    			'invoice_time'=>$team_product_array[$i]['invoice_time'],
    			'team_product_cost'=>$team_product_array[$i]['team_product_cost'],
    			'cost_currency_id'=>$team_product_array[$i]['cost_currency_id'],
    			'price_before_tax'=>$team_product_array[$i]['price_before_tax'],    				
                'netamt'=>isset($team_product_array[$i]['netamt']) ? $team_product_array[$i]['netamt'] : '',
                'gst'=>isset($team_product_array[$i]['gst']) ? $team_product_array[$i]['gst'] : '',
                'pst'=>isset($team_product_array[$i]['pst']) ? $team_product_array[$i]['pst'] : '',
                'hst'=>isset($team_product_array[$i]['hst']) ? $team_product_array[$i]['hst'] : '',
                'p_otx'=>isset($team_product_array[$i]['p_otx']) ? $team_product_array[$i]['p_otx'] : '',
                'invamt'=>isset($team_product_array[$i]['invamt']) ? $team_product_array[$i]['invamt'] : '',
                'estcost'=>isset($team_product_array[$i]['estcost']) ? $team_product_array[$i]['estcost'] : '',
                'paidamt'=>isset($team_product_array[$i]['paidamt']) ? $team_product_array[$i]['paidamt'] : '',
                'balance'=>isset($team_product_array[$i]['balance']) ? $team_product_array[$i]['balance'] : '',
    			'substribe_time'=>$team_product_array[$i]['substribe_time'],
    			'utc_substribe_time'=>$utc_substribe_time

    				
    		];

            isset($team_product_array[$i]['tax_cd']) && $team_product_array_params['tax_cd'] = $team_product_array[$i]['tax_cd'];
            isset($team_product_array[$i]['tax_id']) && $team_product_array_params['tax_id'] = $team_product_array[$i]['tax_id'];
			
    		$company_order_product_team->updateCompanyOrderTeam($team_product_array_params);
    		
    		//再改资源里的14状态的团费
    		$source_params = [
    			'company_order_product_team_id'=> $team_product_array[$i]['company_order_product_team_id'],
    			'source_price'=> $team_product_array[$i]['team_product_price'],
    			'price_currency_id'=> $team_product_array[$i]['price_currency_id'],
    		];
    		$this->_company_order_product_source->updateCompanyOrderSourceByCompanyOrderProductTeamId($source_params);
    	}
    	
   
    	
    	for($i=0;$i<count($source_array);$i++){
    		$source_array_params = [];
    		$substribe_time = $source_array[$i]['substribe_time'];
    		
    		if(!empty($substribe_time)){
    			$h = 8-$timezone+8;
    			$utc_substribe_time = strtotime("$h hours",$substribe_time);
    		}else{
    			$utc_substribe_time ='';
    		}
    		
    		$source_array_params = [
    			'now_user_id'=>$params['now_user_id'],
    			'company_order_product_source_id'=>$source_array[$i]['company_order_product_source_id'],
    			'price_currency_id'=>$source_array[$i]['price_currency_id'],
    			'source_price'=>$source_array[$i]['source_price'],
    			'source_cost'=>$source_array[$i]['source_cost'],
    			'cost_currency_id'=>$source_array[$i]['cost_currency_id'],
    			'invoice_number'=>$source_array[$i]['invoice_number'],
    			'invoice_time'=>$source_array[$i]['invoice_time'],
    			'price_before_tax'=>$source_array[$i]['price_before_tax'],
    			'remark'=>$source_array[$i]['remark'],
                'netamt'=>isset($source_array[$i]['netamt']) ? $source_array[$i]['netamt'] : '',
                'gst'=>isset($source_array[$i]['gst']) ? $source_array[$i]['gst'] : '',
                'pst'=>isset($source_array[$i]['pst']) ? $source_array[$i]['pst'] : '',
                'hst'=>isset($source_array[$i]['hst']) ? $source_array[$i]['hst'] : '',
                'p_otx'=>isset($source_array[$i]['p_otx']) ? $source_array[$i]['p_otx'] : '',
                'invamt'=>isset($source_array[$i]['invamt']) ? $source_array[$i]['invamt'] : '',
                'estcost'=>isset($source_array[$i]['estcost']) ? $source_array[$i]['estcost'] : '',
                'paidamt'=>isset($source_array[$i]['paidamt']) ? $source_array[$i]['paidamt'] : '',
                'balance'=>isset($source_array[$i]['balance']) ? $source_array[$i]['balance'] : '',
    			'substribe_time'=>$substribe_time,
    			'utc_substribe_time'=>$utc_substribe_time
    		];
			
            isset($source_array[$i]['tax_cd']) && $source_array_params['tax_cd'] = $source_array[$i]['tax_cd'];
            isset($source_array[$i]['tax_id']) && $source_array_params['tax_id'] = $source_array[$i]['tax_id'];
    		$company_order_product_source->updateCompanyOrderSource($source_array_params);
    	}
		
    	for($i=0;$i<count($diy_array);$i++){
    		$substribe_time = $diy_array[$i]['substribe_time'];
    		
    		if(!empty($substribe_time)){
    		
    			$h = 8-$timezone+8;
    			$utc_substribe_time = strtotime("$h hours",$substribe_time);
    		}else{
    		
    			$utc_substribe_time ='';
    		}

    		$diy_array_params = [
    			'company_order_product_diy_number'=>$diy_array[$i]['company_order_product_diy_number'],	
    			'diy_price'=>$diy_array[$i]['diy_price'],
    			'price_currency_id'=>$diy_array[$i]['price_currency_id'],
    			'diy_cost'=>$diy_array[$i]['diy_cost'],
    			'cost_currency_id'=>$diy_array[$i]['cost_currency_id'],
				'invoice_number'=>$diy_array[$i]['invoice_number'],
    			'invoice_time'=>$diy_array[$i]['invoice_time'],
    			'now_user_id'=>$params['now_user_id'],
    			'price_before_tax'=>$diy_array[$i]['price_before_tax'],
    			'remark'=>$diy_array[$i]['remark'],
                'netamt'=>isset($diy_array[$i]['netamt']) ? $diy_array[$i]['netamt'] : '',
                'gst'=>isset($diy_array[$i]['gst']) ? $diy_array[$i]['gst'] : '',
                'pst'=>isset($diy_array[$i]['pst']) ? $diy_array[$i]['pst'] : '',
                'hst'=>isset($diy_array[$i]['hst']) ? $diy_array[$i]['hst'] : '',
                'p_otx'=>isset($diy_array[$i]['p_otx']) ? $diy_array[$i]['p_otx'] : '',
                'invamt'=>isset($diy_array[$i]['invamt']) ? $diy_array[$i]['invamt'] : '',
                'estcost'=>isset($diy_array[$i]['estcost']) ? $diy_array[$i]['estcost'] : '',
                'paidamt'=>isset($diy_array[$i]['paidamt']) ? $diy_array[$i]['paidamt'] : '',
                'balance'=>isset($diy_array[$i]['balance']) ? $diy_array[$i]['balance'] : '',
    			'substribe_time'=>$substribe_time,
    			'utc_substribe_time'=>$utc_substribe_time,
    		];

            isset($diy_array[$i]['tax_cd']) && $diy_array_params['tax_cd'] = $diy_array[$i]['tax_cd'];
            isset($diy_array[$i]['tax_id']) && $diy_array_params['tax_id'] = $diy_array[$i]['tax_id'];

    		$company_order_product_diy->updateCompanyOrderProductDiyByDiyNumber($diy_array_params);
    	}
    	$public_service = new PublicService();
    	for($i=0;$i<count($diy_price);$i++){
    		$substribe_time = $diy_price[$i]['substribe_time'];
    	
    		if(!empty($substribe_time)){
    	
    			$h = 8-$timezone+8;
    			$utc_substribe_time = strtotime("$h hours",$substribe_time);
    		}else{
    	
    			$utc_substribe_time ='';
    		}
    

    		$diy_array_params = [
    				'company_order_product_diy_price_id'=>$diy_price[$i]['company_order_product_diy_price_id'],
    				'diy_price'=>$diy_price[$i]['diy_price'],
    				'price_currency_id'=>$diy_price[$i]['price_currency_id'],
    				'diy_name'=>$diy_price[$i]['diy_name'],
    				'diy_type'=>$diy_price[$i]['diy_type'],
					'tax_id'=>$diy_price[$i]['tax_id'],
    				'now_user_id'=>$params['now_user_id'],
    				'price_before_tax'=>$diy_price[$i]['price_before_tax'],
    				'remark'=>$diy_price[$i]['remark'],
    				'netamt'=>isset($diy_price[$i]['netamt']) ? $diy_price[$i]['netamt'] : '',
    				'gst'=>isset($diy_price[$i]['gst']) ? $diy_price[$i]['gst'] : '',
    				'pst'=>isset($diy_price[$i]['pst']) ? $diy_price[$i]['pst'] : '',
    				'hst'=>isset($diy_price[$i]['hst']) ? $diy_price[$i]['hst'] : '',
    				'p_otx'=>isset($diy_price[$i]['p_otx']) ? $diy_price[$i]['p_otx'] : '',
    				'invamt'=>isset($diy_price[$i]['invamt']) ? $diy_price[$i]['invamt'] : '',
    				'estcost'=>isset($diy_price[$i]['estcost']) ? $diy_price[$i]['estcost'] : '',
    				'paidamt'=>isset($diy_price[$i]['paidamt']) ? $diy_price[$i]['paidamt'] : '',
    				'balance'=>isset($diy_price[$i]['balance']) ? $diy_price[$i]['balance'] : '',
    				'substribe_time'=>$substribe_time,
    				'utc_substribe_time'=>$utc_substribe_time,
    		];
    		if(is_numeric($diy_price[$i]['tax_id'])){//如果有税ID
    			$diy_price_params = [
    					'tax_id'=>$diy_price[$i]['tax_id']
    			];
    			
    			$tax_result = $public_service->getTaxTotal($diy_price_params);
    			
    			$diy_array_params['tax_price'] =number_format($diy_price[$i]['diy_price']*($tax_result/100),2);
    			$diy_array_params['price_before_tax'] = $diy_price[$i]['diy_price'] - number_format($diy_price[$i]['diy_price']*($tax_result/100),2);
    		
    		}else{
    			$diy_array_params['tax_price'] = 0;
    			$diy_array_params['price_before_tax'] = $diy_price[$i]['diy_price'];
    		
    		}

    	
    		$company_order_product_diy_price->updateCompanyOrderProductDiyPriceById($diy_array_params);
    	}   	
    	
    	
    	for($i=0;$i<count($branch_product_array);$i++){
    		$substribe_time = $branch_product_array[$i]['substribe_time'];
    		
    		if(!empty($substribe_time)){
    			$h = 8-$timezone+8;
    			$utc_substribe_time = strtotime("$h hours",$substribe_time);
    		}else{
    			$utc_substribe_time ='';
    		}    		
    		$branch_product_array_params = [
    			'company_order_product_id'=>$branch_product_array[$i]['company_order_product_id'],
    			'price_currency_id'=>$branch_product_array[$i]['price_currency_id'],
    			'branch_product_price'=>$branch_product_array[$i]['branch_product_price'],
    			'now_user_id'=>$params['now_user_id'],
    			'tax_id'=>$branch_product_array[$i]['tax_id'],
    			'price_before_tax'=>$branch_product_array[$i]['price_before_tax'],
    			'remark'=>$branch_product_array[$i]['remark'],
    			'substribe_time'=>$substribe_time,
    			'utc_substribe_time'=>$utc_substribe_time,
    		];
    		$company_order_product->updateCompanyOrderProductById($branch_product_array_params);
    	}
    	
    	
    	//修改公司订单状态为未确认
    	$company_order = new CompanyOrder();
    	$company_order_params=[
    			'company_order_number'=>$params['company_order_number'],
    			'company_order_status'=>1
    	];
    	$company_order_result = $company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
    	 
    
    
    
    	$this->outPut(1);
    	 
    }
    /**
     * 获取公司订单号根据团队产品编号
     */
    public function getCompanyOrderNumberByTeamProductNumber(){

    	$params = $this->input();
    	$paramRule = [
    			 
    		'team_product_number'=>'string',
    	
    	];
    	$this->paramCheckRule($paramRule,$params);
		$company_order_product_team = new CompanyOrderProductTeam();
		
		$company_order_product_team_result = $company_order_product_team->getCompanyOrderNumberByTeamProductNumber($params);
		$this->outPut($company_order_product_team_result);
    	
    }
    /**
     * 获取公司订单号根据团队产品ID
     */
    public function getCompanyOrderNumberByTeamProductId(){
    
    	$params = $this->input();
    	$paramRule = [
    
    		'team_product_id'=>'string',
    			 
    	];
    	
    	$this->paramCheckRule($paramRule,$params);
    	$company_order_product_team = new CompanyOrderProductTeam();
    
    	$company_order_product_team_result = $company_order_product_team->getCompanyOrderNumberByTeamProductId($params);
    	$this->outPut($company_order_product_team_result);
    	 
    }
    /**
     * 添加销售收款
     */
    public function  addCompanyOrderSale(){
    	$params = $this->input();
    	$paramRule = [
    		
    		'receivable_number'=>'string',
    		'payment_stage'=>'number',
    		'payment_currency_id'=>'number',
    		'payment_money'=>'string',	
    		'payment_type'=>'number',
    		
    			 
    	];
 
    	$this->paramCheckRule($paramRule,$params);
    	
    	$receivable_number = trim($params['receivable_number'],',');
    	
    	$receivable_number_arry =  explode(",",$receivable_number);
    	
    	
    	$receivable_info = new ReceivableInfo();
    	$params['voucher_number']= $params['voucher_number'];
    	$params['voucher_time'] = $params['voucher_time'];
    	$payment_money = $params['payment_money'];
    	$params['remark'] = $params['remark'];
    	$params['attachment'] = $params['attachment'];
    	


    	
    	for($i=0;$i<count($receivable_number_arry);$i++){
    		if($payment_money==0){
    			break;
    		}
    		
    		$params['receivable_number'] = $receivable_number_arry[$i];
    		
    		//首先 查询应收
    		$receivable_params = [
    			'receivable_number'=>$receivable_number_arry[$i]	
    		];
    		$receivable_result = $this->_receivable->getReceivable($receivable_params);

    		//开始算剩余的应收
    		$miss_receivable =$receivable_result[0]['receivable_money']-$receivable_result[0]['true_receipt'];
    		
    		

    		
    		
			if($miss_receivable<=0){
				continue;
			}else{
				if($payment_money>=$miss_receivable){
					$params['payment_money'] = $miss_receivable;
				}else{
					$params['payment_money'] = $payment_money;
				}
				
				//这里直接走审批 不直接添加到实收中
				
				
				$params['finance_type'] = 1;
				$params['receivable_info_type'] = 2;
 				$this->_finance_approve->addFinanceApprove($params);
				//$receivable_info_result = $receivable_info->addReceivableInfoSale($params);
 				//再发布站内信
 				$in_station_letter_params = [
 						'system_alert_event_id'=>25,
 						'role_id'=>5,
 						'company_id'=>$params['user_company_id'],
 						'content'=>'您有一条审批信息，请到审批-财务审批查看'
 				];
 				 
 				$this->_in_station_letter_service->addInStationLetter($in_station_letter_params);
				
				$payment_money-=$params['payment_money'];
			}
    		
    		
    	}
    
    	
    	$this->outPut(1);
    }
	  
	/**
	 * 修改销售收款
	 */
    public function updateCompanyOrderSale(){
    	$params = $this->input();
    	$paramRule = [
    	
    		'receivable_info_id'=>'number',
 
    	
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$result = $this->_receivable_info->updateReceivableInfoByReceivableInfoId($params);
    	$this->outPut($result);
    }
    /**
     * 修改销售收款根据销售收款应付编号
     */
    public function updateCompanyOrderSaleByPaymentNumber(){
    	$params = $this->input();
    	$paramRule = [
    			 
    		'payment_number'=>'string',
    
    			 
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$result = $this->_receivable_info->updateReceivableInfoByPaymentNumber($params);
    	$this->outPut($result);
    }    
    /**
     * 添加顾客
     * 胡
     */
    public function addCustomer(){
    	$params = $this->input();
    	$paramRule = [
    		'customer_first_name'=>'string',
    		'customer_last_name'=>'string',
//    		'english_first_name'=>'string',
//    		'english_last_name'=>'string',
//    		'company_id'=>'user_company_id',
    		'customer_type'=>'number',
    		'gender'=>'number',
    		'country_id'=>'number',
    		'language_id'=>'number',
//    		'phone'=>'string',
//    		'email'=>'string',
    		'passport_number'=>'string',
    		'status'=>'number',
    	];
    	
    	$this->paramCheckRule($paramRule,$params);

        $data = [
            'passport_number'=>$params['passport_number'],
        ];
        $this->checkNameIsRepetition('customer',$data);

    	$customer = new Customer();
    	$params['customer_number'] = help::getNumber(7);
    	$customer_result = $customer->addCustomer($params);
    	$this->outPut($customer_result);
    	
    	
    	
    }
    
    /**
     * 修改顾客
     */
    public function updateCustomerByCustomerId(){
    	$params = $this->input();
    	$paramRule = [

    
			'customer_id'=>'number'
    	
    	];
    	 
    	$this->paramCheckRule($paramRule,$params);

        $customer = new Customer();
        $Info = $customer->getOneCustomer($params['customer_id']);
        if($Info['passport_number'] == $params['passport_number']){
        }else{

            //开始判断名字是否重复
            $data = [
                'passport_number'=>$params['passport_number'],
            ];
            $this->checkNameIsRepetition('customer',$data);
            //结束判断名字重复
        }


    	$customer_result = $customer->updateCustomerByCustomerId($params);
    	$this->outPut($customer_result);
    	
    }
    
    /**
     * 查询顾客
     */
    public function getCustomer(){
    	$params = $this->input();

    	$customer = new Customer();

    	if(isset($params['page'])){
    		$params['page_size'] =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
    		$params['page'] = ($params['page']-1)*$params['page_size'];
    		$count =  $customer->getCustomer($params,true);
    		
    		$result = $customer->getCustomer($params,false);
    		$data = [
    	
    				'count'=>$count,
    				'list'=>$result,
    				'page_count'=>ceil($count/$params['page_size'])

    		];
    		return $this->output($data);
    	}
    	$customer_result = $customer->getCustomer($params,false);
    	$this->outPut($customer_result);
    	
    }
    
    /**
     * 获取游客行程单
     */
    public function getCompanyOrderCustomerJouneryMenu(){
    	 
    	$params = $this->input();
    	$paramRule = [    			 
    		'company_order_number'=>'string',
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$company_order_number = $params['company_order_number'];
    	$company_order_customer_jounery_menu = [];
    	//首先通过订单编号查询订单信息
    	$company_order_result = $this->_company_order->getCompanyOrder($params);
    	//获取公司订单名称
    	$company_order_name_result = $this->_company_order_service->getCompanyOrderName($company_order_result);
    	
    	//获取所有订单的顾客信息
    	$company_order_customer_params['company_order_number'] = $params['company_order_number'];
    	$company_order_customer_result = $this->_company_order_customer->getCompanyOrderCustomer($company_order_customer_params);
		for($i=0;$i<count($company_order_customer_result);$i++){
			$company_order_customer_lineup_params=[
				'status'=>1,
				'company_order_number'=>$company_order_number,
				'lineup_type'=>1,
				'company_order_customer_id'=>$company_order_customer_result[$i]['company_order_customer_id']
			];
			
			$company_order_customer_lineup_result = $this->_company_order_customer_lineup->getLineup($company_order_customer_lineup_params);
			$company_order_customer_result[$i]['company_order_lineup_number'] = Help::getLineupPrefix(1).$company_order_customer_lineup_result[0]['lineup_number'];
		}
   		//根据房号升序
    	foreach ($company_order_customer_result as $key => $row) {
    		$room_code[$key] = $row['room_code'];
    		 
    	}
    	//升序处理行程时间
    	array_multisort($room_code,SORT_ASC,$company_order_customer_result);
    	
    	//给每个游客添加接送机信息
    	for($i=0;$i<count($company_order_customer_result);$i++){
    		$company_order_customer_result[$i]['flight_info'] = [];
    		if($company_order_customer_result[$i]['customer_id']>0){//不能是站位
    			$company_order_flight_params = [
    					'status'=>1,
    					'company_order_number'=>$company_order_number,
    					'customer_id'=>$company_order_customer_result[$i]['customer_id']
    			];
    			$company_order_customer_result[$i]['flight_info'] = $this->_company_order_flight->getCompanyOrderFlight($company_order_flight_params);
    			//$company_order_customer_result[$i]['flight_info']['company_order_lineup_number'] = $company_order_customer_result[$i]['company_order_lineup_number'];
    		}

    	}
  
	
    	//订单名称
    	$company_order_customer_jounery_menu['company_order_name'] = $company_order_name_result[0]['order_name'];
    	
    	//订单编号
    	$company_order_customer_jounery_menu['company_order_number'] = $company_order_result[0]['company_order_number'];
    	//出发时间
    	$company_order_customer_jounery_menu['begin_time'] = $company_order_result[0]['begin_time'];
    	//结束 时间
    	$company_order_customer_jounery_menu['end_time'] = $company_order_result[0]['end_time'];
    	
    	//备注
    	$company_order_customer_jounery_menu['remark'] = $company_order_result[0]['remark'];
    	$company_order_customer_jounery_menu['begin_city'] = $company_order_result[0]['begin_city'];
    	
    	//根据订单编号开始获取团队产品
    	$company_order_product_team_params=[
    		'status'=>1,
    		'company_order_number'=>$params['company_order_number']	
    	];
    	$company_order_product_team_result = $this->_company_order_product_team->getCompanyOrderProductTeam($company_order_product_team_params);
    	

    	$team_product_jounery =[];
    	$team_product_return_receipt = [];//回执单模板
    	for($i=0;$i<count($company_order_product_team_result);$i++){
    		//先通过编号获取到ID
    		$team_product_params['team_product_id'] =$company_order_product_team_result[$i]['team_product_id'];
    		$team_product_result = $this->_team_product->getTeamProduct($team_product_params);
    		$team_product_jounery_params = [];
    		$team_product_jounery_params ['team_product_id'] = $company_order_product_team_result[$i]['team_product_id'];
    		
    		$team_product_journey_result = $this->_team_product_journey->getTeamProductJourney($team_product_jounery_params);

    		$team_product_return_receipt_params = [
    			'status'=>1,
    			'team_product_id'=>	 $team_product_result[0]['team_product_id']
    		];
    		if(!empty($team_product_result[0]['team_product_id'])){
    			$team_product_return_receipt_result = $this->_team_product_return_receipt->getTeamProductReturnReceipt($team_product_return_receipt_params);   			
    			$team_product_return_receipt = array_merge($team_product_return_receipt,$team_product_return_receipt_result);
    		}

    		//给每个行程加上开团时间
    		for($j=0;$j<count($team_product_journey_result);$j++){
    			$team_product_journey_result[$j]['begin_time'] =date('Ymd ',$team_product_result[0]['begin_time']); 
    		}
    		$team_product_jounery = array_merge($team_product_jounery,$team_product_journey_result);
    	}
    	
    	//有了所有行程的开团时间
    	
    	
    	foreach ($team_product_jounery as $key => $row) {
    		$begin[$key] = $row['begin_time'];
    	
    	}
    	//升序处理行程时间
    	array_multisort($begin,SORT_ASC,$team_product_jounery);
    	
    	for($i=0;$i<count($team_product_jounery);$i++){
    		$k = $team_product_jounery[$i]['the_days'];
    		$k = $k-1;
    		$team_product_jounery[$i]['jounery_time'] = strtotime("+ $k days",strtotime($team_product_jounery[$i]['begin_time']));
    	}

    	
    	//首先获取第一天日期  以及最后一天日期 用于 对比游客的 形成。来设置提前或者延后
    	$firsr_day_jounery = $team_product_jounery[0]['jounery_time'];
    	
    	$last_day_jounery = $team_product_jounery[count($team_product_jounery)-1]['jounery_time'];
 
    	$check_in_1_day = [];
    	$check_in_2_day = [];
    	$check_in_3_day = [];
    	$check_in_4_day = [];
    	$check_in_5_day = [];
    	$check_on_1_day = [];
    	$check_on_2_day = [];
    	$check_on_3_day = [];
    	$check_on_4_day = [];
    	$check_on_5_day = [];
    	//初始化提前入住酒店
    	$check_in_1_hotel='';
    	$check_in_2_hotel='';
    	$check_in_3_hotel='';
    	$check_in_4_hotel='';
    	$check_in_5_hotel='';
    	//初始化延后退房酒店
    	$check_on_1_hotel='';
    	$check_on_2_hotel='';
    	$check_on_3_hotel='';
    	$check_on_4_hotel='';
    	$check_on_5_hotel='';
    	//初始化提前入住酒店的电话
    	$check_in_1_hotel_phone='';
    	$check_in_2_hotel_phone='';
    	$check_in_3_hotel_phone='';
    	$check_in_4_hotel_phone='';
    	$check_in_5_hotel_phone='';
    	//初始化延后退房酒店电话
    	$check_on_1_hotel_phone='';
    	$check_on_2_hotel_phone='';
    	$check_on_3_hotel_phone='';
    	$check_on_4_hotel_phone='';
    	$check_on_5_hotel_phone=''; 
    	//初始化提前入住酒店的地址
    	$check_in_1_hotel_address='';
    	$check_in_2_hotel_address='';
    	$check_in_3_hotel_address='';
    	$check_in_4_hotel_address='';
    	$check_in_5_hotel_address='';
    	//初始化延后退房酒店的地址
    	$check_on_1_hotel_address='';
    	$check_on_2_hotel_address='';
    	$check_on_3_hotel_address='';
    	$check_on_4_hotel_address='';
    	$check_on_5_hotel_address='';  
    	//提前入住人数
    	$check_in_1_hotel_customer_count=0;
    	$check_in_2_hotel_customer_count=0;
    	$check_in_3_hotel_customer_count=0;
    	$check_in_4_hotel_customer_count=0;
    	$check_in_5_hotel_customer_count=0;
    	//延后入住人数
    	$check_on_1_hotel_customer_count=0;
    	$check_on_2_hotel_customer_count=0;
    	$check_on_3_hotel_customer_count=0;
    	$check_on_4_hotel_customer_count=0;
    	$check_on_5_hotel_customer_count=0;
    	//
		$flight_info = [];
    	for($i=0;$i<count($company_order_customer_result);$i++){
    		$check_in = $company_order_customer_result[$i]['check_in'];
		//	$jounery_time = $company_order_customer_result[$i]['jounery_time'];
			$customer_flight_info = $company_order_customer_result[$i]['flight_info'];
    		for($j=0;$j<count($customer_flight_info);$j++){//添加航班信息
    			$customer_flight_info_j = $customer_flight_info[$j];
    			$customer_flight_info_j['company_order_lineup_number'] = $company_order_customer_result[$i]['company_order_lineup_number'];
    			$flight_type = $customer_flight_info[$j]['flight_type'];
    			$flight_begin_time =$customer_flight_info[$j]['flight_begin_time'];
    			$flight_end_time = $customer_flight_info[$j]['flight_end_time'];
    			if($flight_type==1 and !empty($flight_end_time)){//接机要到达时间
    				$customer_flight_info_j['flight_time'] = $flight_end_time;
    				$flight_info[]=$customer_flight_info_j;
    				
    			}else if($flight_type==2 and !empty($flight_begin_time)){//送机要出发时间
    				$customer_flight_info_j['flight_time'] = $flight_begin_time;
    				$flight_info[]=$customer_flight_info_j;
    				
    			}else{
    				$flight_info[]=$customer_flight_info_j;
    			}
    		}
    		$check_in_hotel = $company_order_customer_result[$i]['check_in_hotel'];
    		
    		
    		$check_on_hotel = $company_order_customer_result[$i]['check_on_hotel'];
    		$check_in_hotel_array = [];
    		if($company_order_customer_result[$i]['check_in']==-1){
    			$check_in_hotel_array[] =  $check_in_hotel;
    		}else{
    			if(!empty($check_in_hotel)){
    				$check_in_hotel_array =  explode(',',$check_in_hotel);
    			}else{
    				$check_in_hotel_array =  [];
    			}   			
    		}
    		$check_on_hotel_array =[];
    		if($company_order_customer_result[$i]['check_on']==1){
    			$check_on_hotel_array[] =  $check_on_hotel;
    		}else{
    		    if(!empty($check_on_hotel)){
    				$check_on_hotel_array = explode(',',$check_on_hotel);
    			}else{
    				$check_on_hotel_array =  [];
    			}
    		}
    		


    		$check_in_hotel_phone='';
    		$check_in_hotel_address='';
    		for($j=0;$j<count($check_in_hotel_array);$j++){
    			if($j==0 ){
    				$check_in_1_day = [
    						'route_journey_title'=>'提前入住1天',
    						'jounery_time'=>strtotime("-1 days",$firsr_day_jounery)
    				];
    				$hotel_params['hotel_id'] = $check_in_hotel_array[0];
    				
    				if(is_numeric($hotel_params['hotel_id'])){
    					$hotel_result = $this->_hotel->getHotel($hotel_params);
    					
    					
    					if(strlen($check_in_1_hotel)>0 && strlen($check_in_hotel_array[0])>0){
    						//获得酒店ID查询酒店
    					
    						$check_in_1_hotel=','.$hotel_result[0]['room_name'];
    						$check_in_1_hotel_phone.=','.$hotel_result[0]['phone'];
    						$check_in_1_hotel_address.=','.$hotel_result[0]['address'];
    					}else{
    						$check_in_1_hotel = $hotel_result[0]['room_name'];;
    						$check_in_1_hotel_phone=$hotel_result[0]['phone'];
    						$check_in_1_hotel_address=$hotel_result[0]['address'];
    					}    					
    				}
    				
 
    				$check_in_1_hotel_customer_count++;
    			}else if($j==1 ){
    				$check_in_2_day = [
    						'route_journey_title'=>'提前入住2天',
    						'jounery_time'=>strtotime("-2 days",$firsr_day_jounery)
    				];
    				//获得酒店ID查询酒店
    				$hotel_params['hotel_id'] = $check_in_hotel_array[1];
    				
    				if(is_numeric($hotel_params['hotel_id'])){
    					
    					$hotel_result = $this->_hotel->getHotel($hotel_params);
    					if(strlen($check_in_2_hotel)>0){
    					
    						$check_in_2_hotel.=','.$hotel_result[0]['room_name'];
    						$check_in_2_hotel_phone.=','.$hotel_result[0]['phone'];
    						$check_in_2_hotel_address=','.$hotel_result[0]['address'];
    					}else{
    						$check_in_2_hotel = $hotel_result[0]['room_name'];
    						$check_in_2_hotel_phone=1;//$hotel_result[0]['phone'];
    						$check_in_2_hotel_address=$hotel_result[0]['address'];
    					}   					
    					
    					
    					
    				}
    				

    				$check_in_2_hotel_customer_count++;
    			}else if($j==2 ){
    				$check_in_3_day = [
    						'route_journey_title'=>'提前入住3天',
    						'jounery_time'=>strtotime("-3 days",$firsr_day_jounery)
    				];
    				$hotel_params['hotel_id'] = $check_in_hotel_array[2];
    				if(is_numeric($hotel_params['hotel_id'])){
    					$hotel_result = $this->_hotel->getHotel($hotel_params);
    					if(strlen($check_in_3_hotel)>0){
    					
    						$check_in_3_hotel.=','.$hotel_result[0]['room_name'];
    						$check_in_3_hotel_phone.=','.$hotel_result[0]['phone'];
    						$check_in_3_hotel_address.=','.$hotel_result[0]['address'];
    					}else{
    						$check_in_3_hotel = $hotel_result[0]['room_name'];
    						$check_in_3_hotel_phone=$hotel_result[0]['phone'];
    						$check_in_3_hotel_address=$hotel_result[0]['address'];
    					}   					
    					
    					
    					
    				}

    				$check_in_3_hotel_customer_count++;
    			}else if($j==3 ){
    				$check_in_4_day = [
    						'route_journey_title'=>'提前入住4天',
    						'jounery_time'=>strtotime("-4 days",$firsr_day_jounery)
    				];
    				$hotel_params['hotel_id'] = $check_in_hotel_array[3];
    				
    				if(is_numeric($hotel_params['hotel_id'])){
    					$hotel_result = $this->_hotel->getHotel($hotel_params);
    					if(strlen($check_in_4_hotel)>0){
    					
    						$check_in_4_hotel.=','.$hotel_result[0]['room_name'];
    						$check_in_4_hotel_phone.=','.$hotel_result[0]['phone'];
    						$check_in_4_hotel_address.=','.$hotel_result[0]['address'];
    					}else{
    						$check_in_4_hotel = $hotel_result[0]['room_name'];
    						$check_in_4_hotel_phone=$hotel_result[0]['phone'];
    						$check_in_4_hotel_address=$hotel_result[0]['address'];
    					}   					
    					
    					
    				}
    				

    				$check_in_4_hotel_customer_count++;
    			}else if($j==4 ){//&& strlen($check_in_hotel_array[4])>0
    				$check_in_5_day = [
    						'route_journey_title'=>'提前入住5天',
    						'jounery_time'=>strtotime("-5 days",$firsr_day_jounery)
    				];
    				$hotel_params['hotel_id'] = $check_in_hotel_array[4];
    				
    				if(is_numeric($hotel_params['hotel_id'])){
    					
    					$hotel_result = $this->_hotel->getHotel($hotel_params);
    					if(strlen($check_in_5_hotel)>0){
    					
    						$check_in_5_hotel.=','.$hotel_result[0]['room_name'];
    						$check_in_5_hotel_phone.=','.$hotel_result[0]['phone'];
    						$check_in_5_hotel_address.=','.$hotel_result[0]['address'];
    					}else{
    						$check_in_5_hotel = $hotel_result[0]['room_name'];
    						$check_in_5_hotel_phone=$hotel_result[0]['phone'];
    						$check_in_5_hotel_address=$hotel_result[0]['address'];
    					}   					
    					
    				}
    				

    				$check_in_5_hotel_customer_count++;
    			}
    		}

    		for($k=0;$k<count($check_on_hotel_array);$k++){
    			if($k==0 ){//&&  strlen($check_on_hotel_array[0])>0
    				$check_on_1_day = [
    					'route_journey_title'=>'延后退房1天',
    					'jounery_time'=>strtotime("+1 days",$last_day_jounery)
    				];
    				$hotel_params['hotel_id'] = $check_on_hotel_array[0];
    				
    				if(is_numeric($hotel_params['hotel_id'])){
    					$hotel_result = $this->_hotel->getHotel($hotel_params);
    					if(strlen($check_on_1_hotel)>0){
    					
    						$check_on_1_hotel.=','.$hotel_result[0]['room_name'];
    						$check_on_1_hotel_phone.=','.$hotel_result[0]['phone'];
    						$check_on_1_hotel_address.=','.$hotel_result[0]['address'];
    					}else{
    						$check_on_1_hotel = $hotel_result[0]['room_name'];
    						$check_on_1_hotel_phone=$hotel_result[0]['phone'];
    						$check_on_1_hotel_address=$hotel_result[0]['address'];
    					}   					
    					
    					
    				}
    				

    				$check_on_1_hotel_customer_count++;
    			}else if($k==1){// &&  strlen($check_on_hotel_array[1])>0
    				$check_on_2_day = [
    						'route_journey_title'=>'延后退房2天',
    						'jounery_time'=>strtotime("+2 days",$last_day_jounery)
    				];
    				$hotel_params['hotel_id'] = $check_on_hotel_array[1];
    				
    				if(is_numeric($hotel_params['hotel_id'])){
    					$hotel_result = $this->_hotel->getHotel($hotel_params);
    					if(strlen($check_on_2_hotel)>0){
    					
    						$check_on_2_hotel.=','.$hotel_result[0]['room_name'];
    						$check_on_2_hotel_phone.=','.$hotel_result[0]['phone'];
    						$check_on_2_hotel_address.=','.$hotel_result[0]['address'];
    					}else{
    						$check_on_2_hotel = $hotel_result[0]['room_name'];
    						$check_on_2_hotel_phone=$hotel_result[0]['phone'];
    						$check_on_2_hotel_address=$hotel_result[0]['address'];
    					}    					
    					
    				}
    				

    				$check_on_2_hotel_customer_count++;
    			}else if($k==2 ){
    				$check_on_3_day = [
    						'route_journey_title'=>'延后退房3天',
    						'jounery_time'=>strtotime("+3 days",$last_day_jounery)
    				];
    				$hotel_params['hotel_id'] = $check_on_hotel_array[2];
    				
    				if(is_numeric($hotel_params['hotel_id'])){
    					
    					$hotel_result = $this->_hotel->getHotel($hotel_params);
    					if(strlen($check_on_3_hotel)>0){
    					
    						$check_on_3_hotel.=','.$hotel_result[0]['room_name'];
    						$check_on_3_hotel_phone.=','.$hotel_result[0]['phone'];
    						$check_on_3_hotel_address.=','.$hotel_result[0]['address'];
    					}else{
    						$check_on_3_hotel = $hotel_result[0]['room_name'];
    						$check_on_3_hotel_phone=$hotel_result[0]['phone'];
    						$check_on_3_hotel_address.=$hotel_result[0]['address'];
    					}   					
    				}
    				

    				$check_on_3_hotel_customer_count++;
    			}else if($k==3){
    				$check_on_4_day = [
    						'route_journey_title'=>'延后退房4天',
    						'jounery_time'=>strtotime("+4 days",$last_day_jounery)
    				];
    				$hotel_params['hotel_id'] = $check_on_hotel_array[3];
    				
    				if(is_numeric($hotel_params['hotel_id'])){
    					$hotel_result = $this->_hotel->getHotel($hotel_params);
    					if(strlen($check_on_4_hotel)>0){
    					
    						$check_on_4_hotel.=','.$hotel_result[0]['room_name'];
    						$check_on_4_hotel_phone.=','.$hotel_result[0]['phone'];
    						$check_on_4_hotel_address.=','.$hotel_result[0]['address'];
    					}else{
    						$check_on_4_hotel = $hotel_result[0]['room_name'];
    						$check_on_4_hotel_phone=$hotel_result[0]['phone'];
    						$check_on_4_hotel_address=$hotel_result[0]['address'];
    					}   					
    					
    				}
    				

    				$check_on_4_hotel_customer_count++;
    			}else if($k==4 ){
    				$check_on_5_day = [
    						'route_journey_title'=>'延后退房5天',
    						'jounery_time'=>strtotime("+5 days",$last_day_jounery)
    				];
    				$hotel_params['hotel_id'] = $check_on_hotel_array[4];
    				
    				if(is_numeric($hotel_params['hotel_id'])){
    					$hotel_result = $this->_hotel->getHotel($hotel_params);
    					if(strlen($check_on_hotel_array[4])>0){
    						if(strlen($check_on_5_hotel)>0){
    					
    							$check_on_5_hotel.=','.$hotel_result[0]['room_name'];
    							$check_on_5_hotel_phone.=','.$hotel_result[0]['phone'];
    							$check_on_5_hotel_address.=','.$hotel_result[0]['address'];
    						}else{
    							$check_on_5_hotel = $hotel_result[0]['room_name'];
    							$check_on_5_hotel_phone=$hotel_result[0]['phone'];
    							$check_on_5_hotel_address=$hotel_result[0]['address'];
    						}
    					
    					}    					
    					
    					
    				}
    				

    				$check_on_5_hotel_customer_count++;

    			}

    		}
    	}

    		
    		
    		

    	
    	if(count($check_in_1_day)>0){
    		$check_in_1_day['route_journey_stay'] = $check_in_1_hotel;
    		$check_in_1_day['route_journey_stay_phone'] = $check_in_1_hotel_phone;
    		$check_in_1_day['route_journey_stay_address'] = $check_in_1_hotel_address;
    		array_unshift($team_product_jounery,$check_in_1_day);
    	}
    	if(count($check_in_2_day)>0){
    		$check_in_2_day['route_journey_stay'] = $check_in_2_hotel;
    		$check_in_2_day['route_journey_stay_phone'] = $check_in_2_hotel_phone;
    		$check_in_2_day['route_journey_stay_address'] = $check_in_2_hotel_address;
    		array_unshift($team_product_jounery,$check_in_2_day);
    	}
    	if(count($check_in_3_day)>0){
    		$check_in_3_day['route_journey_stay'] = $check_in_3_hotel;
    		$check_in_3_day['route_journey_stay_phone'] = $check_in_3_hotel_phone;
    		$check_in_3_day['route_journey_stay_address'] = $check_in_3_hotel_address;
    		array_unshift($team_product_jounery,$check_in_3_day);
    	}
    	if(count($check_in_4_day)>0){
    		$check_in_4_day['route_journey_stay'] = $check_in_4_hotel;
    		$check_in_4_day['route_journey_stay_phone'] = $check_in_4_hotel_phone;
    		$check_in_4_day['route_journey_stay_address'] = $check_in_4_hotel_address;    		
    		array_unshift($team_product_jounery,$check_in_4_day);
    	}
    	if(count($check_in_5_day)>0){
    		$check_in_5_day['route_journey_stay'] = $check_in_5_hotel;
    		$check_in_5_day['route_journey_stay_phone'] = $check_in_5_hotel_phone;
    		$check_in_5_day['route_journey_stay_address'] = $check_in_5_hotel_address;
    		array_unshift($team_product_jounery,$check_in_5_day);
    	}
    	if(count($check_on_1_day)>0){
    		$check_on_1_day['route_journey_stay'] = $check_on_1_hotel;
    		$check_on_1_day['route_journey_stay_phone'] = $check_on_1_hotel_phone;
    		$check_on_1_day['route_journey_stay_address'] = $check_on_1_hotel_address;
    		$team_product_jounery[] = $check_on_1_day;
    	}
    	if(count($check_on_2_day)>0){
    		$check_on_2_day['route_journey_stay'] = $check_on_2_hotel;
    		$check_on_2_day['route_journey_stay_phone'] = $check_on_2_hotel_phone;
    		$check_on_2_day['route_journey_stay_address'] = $check_on_2_hotel_address;
    		$team_product_jounery[] = $check_on_2_day;
    	}
    	if(count($check_on_3_day)>0){
    		$check_on_3_day['route_journey_stay'] = $check_on_3_hotel;
    		$check_on_3_day['route_journey_stay_phone'] = $check_on_3_hotel_phone;
    		$check_on_3_day['route_journey_stay_address'] = $check_on_3_hotel_address;
    		$team_product_jounery[] = $check_on_3_day;
    	}
    	if(count($check_on_4_day)>0){
    		$check_on_4_day['route_journey_stay'] = $check_on_4_hotel;
    		$check_on_4_day['route_journey_stay_phone'] = $check_on_4_hotel_phone;
    		$check_on_4_day['route_journey_stay_address'] = $check_on_4_hotel_address;
    		$team_product_jounery[] = $check_on_4_day;
    	}
    	if(count($check_on_5_day)>0){
    		$check_on_5_day['route_journey_stay'] = $check_on_5_hotel;
    		$check_on_5_day['route_journey_stay_phone'] = $check_on_5_hotel_phone;
    		$check_on_5_day['route_journey_stay_address'] = $check_on_5_hotel_address;
    		$team_product_jounery[] = $check_on_5_day;
    	}
    	
    	
    	$company_order_customer_jounery_menu['team_product_jounery'] =$team_product_jounery;
    	
    	//开始读取客人名单
    	//开始循环读取每个客人的信息，主要换算入住和退房
    	for($i=0;$i<count($company_order_customer_result);$i++){
    		$check_in_time = $company_order_customer_result[$i]['check_in'];
    		$check_on_time = $company_order_customer_result[$i]['check_on'];
    		if(!empty($check_in_time)){
    			if($check_in_time>0){
					$company_order_customer_result[$i]['check_in_time']=strtotime("$check_in_time day",$firsr_day_jounery);
				}else{
					$company_order_customer_result[$i]['check_in_time']=strtotime("$check_in_time day",$firsr_day_jounery);
				}
    		}else{
    			$company_order_customer_result[$i]['check_in_time'] = strtotime($firsr_day_jounery);
    			 
    		}
    		if(!empty($check_on_time)){
    			if($check_on_time>0){
					$company_order_customer_result[$i]['check_on_time'] =strtotime("$check_on_time day",$last_day_jounery);
				}else{
					$company_order_customer_result[$i]['check_on_time'] = strtotime("$check_on_time day",$last_day_jounery);
				}
    		}else{
    			$company_order_customer_result[$i]['check_on_time'] = strtotime($last_day_jounery);
    			 
    		}
    		
    		$check_in_hotel= $company_order_customer_result[$i]['check_in_hotel'];
    		$check_on_hotel = $company_order_customer_result[$i]['check_on_hotel'];
    		
    		if(!empty($check_in_hotel)){
    			$check_in_hotel = explode(",",$check_in_hotel);
    			 
    			$check_in_string = '';
    			for($j=0;$j<count($check_in_hotel);$j++){
    				$hotel_id = $check_in_hotel[$j];
    			
    				if(is_numeric($hotel_id)){
    			
    					$hotel_params = [
    							'hotel_id'=>$hotel_id
    					];
    					$hotel_result = $this->_hotel->getHotel($hotel_params);
    					$check_in_string.=",".$hotel_result[0]['room_name'];
    				}
    			
    				 
    			}
    			$check_in_hotel =trim($check_in_string,',');
    			
    			$company_order_customer_result[$i]['check_in_hotel'] = $check_in_hotel;
    		}
    		if(!empty($check_on_hotel)){
    			$check_on_hotel = explode(",",$check_on_hotel);
    		
    			$check_on_string = '';
    			for($j=0;$j<count($check_on_hotel);$j++){
    				$hotel_id = $check_on_hotel[$j];
    				 
    				if(is_numeric($hotel_id)){
    					 
    					$hotel_params = [
    							'hotel_id'=>$hotel_id
    					];
    					$hotel_result = $this->_hotel->getHotel($hotel_params);
    					$check_on_string.=",".$hotel_result[0]['room_name'];
    				}
    				 
    					
    			}
    			$check_on_hotel =trim($check_on_string,',');
    			 
    			$company_order_customer_result[$i]['check_on_hotel'] = $check_on_hotel;
    		}   		

    		
    	}
    	$company_order_customer_jounery_menu['customer_info'] =$company_order_customer_result;
    	
    	$company_order_fangxing = [];
    	$fangxing= [];
    	$teshuyaoqiu_str = '';
    	for($i=0;$i<count($company_order_customer_result);$i++){
    		
    		$room_code = $company_order_customer_result[$i]['room_code'];
    		if(!empty($room_code)){
    			$room_type = $company_order_customer_result[$i]['room_type'];
    			
    			if(!in_array($room_code.$room_type,$fangxing)){
    				$fangxing[] = $room_code.$room_type;
    				if(isset($company_order_fangxing[$room_type])){
    					$company_order_fangxing[$room_type]+=1;
    				}else{
    					$company_order_fangxing[$room_type] =1;
    				}
    			}
    			
    			
    			
    			

    			
    		}
    		$teshuyaoqiu = $company_order_customer_result[$i]['special_claim'];
    		if(!empty($teshuyaoqiu)){
    			$teshuyaoqiu_str.=' '.$teshuyaoqiu;
    		}
    		
    		
    		
    	}
    	$company_order_customer_jounery_menu['teshuyaoqiu_str']=$teshuyaoqiu_str;
    	$company_order_customer_jounery_menu['room_type']=$company_order_fangxing;
    	//接送机项目
    	//获取接送机名单
    	
//     	foreach ($flight_info as $key => $row) {
//     		$flight_time[$key] = $row['flight_time'];
    	
//     	}

    	
    	//升序处理行程时间
    	array_multisort($flight_time,SORT_ASC,$flight_info);
    	
    	//根据航班号来
    	$new_flight_info = [];
    	for($i=0;$i<count($flight_info);$i++){
    		$flight_code = $flight_info[$i]['flight_code'];
    		$new_flight_info[$flight_code][] = $flight_info[$i];
    	}
    	
    	$new_flight_info = array_values($new_flight_info);
    	$company_order_customer_jounery_menu['flight_info'] =$new_flight_info;
    	
    	
    	//获取自费项目以及游客信息
    	$company_order_product_source_params = [
    		'company_order_number'=>$company_order_number,
    		'status'=>1,
    		'supplier_type_id'=>11	
    	];
    	$company_order_product_source_result = $this->_company_order_product_source->getCompanyOrderProductSource($company_order_product_source_params);
    	
    	for($i=0;$i<count($company_order_product_source_result);$i++){
    		$company_order_product_source_params = [];
    		
    		$company_order_product_source_params = [
    				'company_order_product_source_id'=>$company_order_product_source_result[$i]['company_order_product_source_id'],
    				'company_order_number'=>$params['company_order_number'],
    				'status'=>1
    						
    		];
    		$customer_info_result = $this->_company_order_relation->getCompanyOrderRelation($company_order_product_source_params);
    		
    		for($j=0;$j<count($customer_info_result);$j++){
    			$customer_info_params=[
    				'status'=>1,
    				'company_order_number'=>$company_order_number,
    				'lineup_type'=>1,
    				'company_order_customer_id'=>	$customer_info_result[$j]['company_order_customer_id']
    			];
    				
    			$company_order_customer_lineup_result = $this->_company_order_customer_lineup->getLineup($customer_info_params);
    			$customer_info_result[$j]['company_order_lineup_number'] = Help::getLineupPrefix(1).$company_order_customer_lineup_result[0]['lineup_number'];
    		}
    		$company_order_product_source_result[$i]['customer_info'] =$customer_info_result;
    	}	
    	
    	
    	$company_order_customer_jounery_menu['own_expense_info'] = $company_order_product_source_result;
    	
    	$company_order_customer_jounery_menu['return_receipt'] = $team_product_return_receipt;
    	
    	//获取订单tour code
    	//首先判断订单的分公司产品
    	$company_order_product_template_params = [
    		'company_order_number'=>$company_order_number,
    		'status'=>1	
    	];
    	$company_order_product_template_result = $this->_company_order_product_template->getCompanyOrderProductTemplatem($company_order_product_template_params);
    	$branch_product_tour_code='';
    	$team_product_tour_code='';
    	for($i=0;$i<count($company_order_product_template_result);$i++){
    		if($company_order_product_template_result[$i]['is_type']==1){
    			$branch_product_tour_code.=','.$company_order_product_template_result[$i]['branch_product_number'];
    		}else{
    			$team_product_params['team_product_id'] = $company_order_product_template_result[$i]['team_product_id'];
    			$team_product_result = $this->_team_product->getTeamProduct($team_product_params);
    			
    			$team_product_tour_code.=','.$team_product_result[0]['team_product_number'];
    		}
    	}
    	$branch_product_tour_code = trim($branch_product_tour_code,',');
    	$team_product_tour_code = trim($team_product_tour_code,',');
    	if(strlen($branch_product_tour_code)>0){
    		$company_order_customer_jounery_menu['tour_code'] = $branch_product_tour_code;
    	}else{
    		$company_order_customer_jounery_menu['tour_code'] = $team_product_tour_code;
    	}
    	
    	// 判断 订单是否是 代理
    
    	if($company_order_result[0]['channel_type']==1){
    		$distributor_id = $company_order_result[0]['distributor_id'];
    		$distributor_params = [
    			'distributor_id'=>$distributor_id
    		];
    		$distributor_result = $this->_distributor->getDistributor($distributor_params);
    		$distributor_name = $distributor_result[0]['distributor_name'];
    		$distributor_address = $distributor_result[0]['address'];
    		$distributor_phone = $distributor_result[0]['tel'];
    		$distributor_lianxiren = $distributor_result[0]['distributor_name'];
    		$distributor_lianxidianhua =  $distributor_result[0]['tel'];
    		
    		
    	}else{
    		$company_params = [
    			'company_id'=>$company_order_result[0]['company_id']
    		];
    		$company_result = $this->_company->getCompanyByCompanysId($company_params);
    		$distributor_name = $company_order_result[0]['company_name'];
    		
    		
    		
    		$distributor_address = $company_result[0]['address'];
    		$distributor_phone = $company_order_result[0]['tel'];
    		$distributor_lianxiren = $company_order_result[0]['contect_name'];
    		$distributor_lianxidianhua = $company_order_result[0]['tel'];
    		
    	}//等于直客
    	

    	if(is_numeric($company_order_result[0]['end_time'])){
    		
    		
    		
    		$day_count = intval(($company_order_result[0]['end_time']-$company_order_result[0]['begin_time'])/86400)+1;
    	}else{
    		$day_count='';
    	}
    	
    	$company_order_customer_jounery_menu['distributor_name'] = $distributor_name;
    	$company_order_customer_jounery_menu['distributor_address'] = $distributor_address;
    	$company_order_customer_jounery_menu['distributor_name'] = $distributor_name;
    	$company_order_customer_jounery_menu['lianxiren'] = $distributor_lianxiren;
    	$company_order_customer_jounery_menu['lianxidianhua'] = $distributor_lianxidianhua;
    	$company_order_customer_jounery_menu['day_count'] = $day_count;
    	$this->outPut($company_order_customer_jounery_menu);
    	 
    	 
    	 
    }    

    //获取销售首款账单
    public function getSaleBill(){
    	$params = $this->input();
    	
    	$paramRule = [
    		'company_order_number' => 'string',
    		'bill_template_id'=>'number'
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    	$receivable_info_id = $params['receivable_info_id'];
    	//首先判断是否需要设置默认账单模板
    	if($params['default_bill_template_id'] == 1){
    		$user_params = [
    			'user_id'=>$params['now_user_id'],
    			'default_bill_template_id'=>$params['bill_template_id']	
    		];
    		
    		$this->_user->updateUserByUserId($user_params);
    	}
    	
    	$sale_bill = [];
    	
    	//首先获取账单数据
    	$bill_template_params = [
    		'bill_template_id'=>$params['bill_template_id']
    	];
    	$bill_result = $this->_bill_template->getBillTemplate($bill_template_params);
    	 
    	$sale_bill['bill_template_title_pic'] = $bill_result[0]['bill_template_title_pic'];
    	$sale_bill['bill_template_foot_pic'] = $bill_result[0]['bill_template_foot_pic'];
    	
    	$company_order_number = $params['company_order_number'];
    	$receivable_info_id = $params['receivable_info_id'];
    	$receivable_info_array = explode(',',$receivable_info_id);
    	//通过订单编号获取订单信息
    	$company_order_params = [
    		'company_order_number'=>$params['company_order_number']	
    	];
    	$company_order_result = $this->_company_order->getCompanyOrder($company_order_params);
    	
    	
    	
    	if($company_order_result[0]['channel_type']==1){//经销商 
    		//通过经销商ID查询经销商的信息
    		$distributor_params = [
    			'distributor_id'=>	$company_order_result[0]['distributor_id']
    		];
    		$distributor_result = $this->_distributor->getDistributor($distributor_params);
    		$sale_bill['content_name'] = $distributor_result[0]['distributor_name'];
    		$sale_bill['city_name'] =$distributor_result[0]['city_name'];
    		$sale_bill['country_name'] = $distributor_result[0]['country_name'];
    		$sale_bill['tel'] = $distributor_result[0]['tel'];
    		$sale_bill['email'] = $distributor_result[0]['email'];
    		$payment_object_type = 3;
    		
    	}else{//直客
    		$sale_bill['content_name'] =$company_order_result[0]['contect_name'];
    		$sale_bill['city_name'] ='';
    		$sale_bill['country_name'] = '';
    		$sale_bill['tel'] = $company_order_result[0]['tel'];
    		$sale_bill['email'] = $company_order_result[0]['email'];
    		$payment_object_type = 4;
    	}
    	//通过订单查询游客信息
    	$company_order_customer_params = [
    		'company_order_number'=>$company_order_number,
    		'status'=>1	
    	];
    	$company_order_customer_result = $this->_company_order_customer->getCompanyOrderCustomer($company_order_customer_params);
    	
		//通过实收ID查询出应收编号
		$receivable_info_params = [
			'receivable_info_id'=>$receivable_info_id	
		];
		$receivable_info_result = $this->_receivable_info->getReceivableInfo($receivable_info_params);
		$receivable_params = [
				'receivable_number'=>$receivable_info_result[0]['receivable_number']
		];
		$receivable_info_result = $this->_receivable->getReceivable($receivable_params);

		
		$receivable_result = $receivable_info_result;
	
		
		$price = $receivable_info_result[0]['receivable_money']-$receivable_info_result[0]['tax_money'];
		$tax = $receivable_info_result[0]['tax_money'];
		
		
		if($receivable_info_result[0]['source_type_id'] == 11){
			//首先查询订单资源
			$diy_params = [
				'receivable_number'=>	$receivable_info_result[0]['receivable_number']
			];
			$diy_result = $this->_company_order_product_diy->getCompanyOrderProductDiy($diy_params);
			
			if(!empty($diy_result)){
				$company_order_relation_params= [
						'company_order_product_source_id'=>	$diy_result[0]['company_order_product_source_id'],
						'company_order_number'=>$receivable_info_result[0]['order_number'],
						'status'=>1
				];
					
				$company_order_customer_result = $this->_company_order_relation->getCompanyOrderRelation($company_order_relation_params);
				
			}
			

		
		
		}else{
		
			$company_order_relation_params= [
		
					'company_order_number'=>$receivable_info_result[0]['order_number'],
					
			];
			
			$company_order_customer_result = $this->_company_order_customer->getCompanyOrderCustomerByCompanyOrderNumber($company_order_relation_params);
		}
		
		
	
		
		//查询每个订单有多少个游客
// 		if($receivable_info_result[0]['fee_type_code']==82){
// 			$company_order_customer_params = [
// 				'company_order_number'=>$receivable_info_result[0]['order_number']
// 			];
// 			$company_order_customer_result = $this->_company_order_customer->getCompanyOrderCustomer($company_order_customer_params);
// 			$company_order_product_params = [
// 				'receivable_number'=>$receivable_info_result[0]['receivable_number']
// 			];
// 			$company_order_product_result = $this->_company_order_product->getCompanyOrderProduct($company_order_product_params);
// 			$price = $company_order_product_result[0]['price_before_tax'];
// 			$tax = $company_order_product_result[0]['branch_product_price']-$company_order_product_result[0]['price_before_tax'];
// 		}else if($receivable_info_result[0]['fee_type_code']==83){
// 			//首先获取订单的资源编号
// 			$company_order_product_source_params = [
// 				'receivable_number'=>$receivable_info_result[0]['receivable_number']
// 			];
// 			$company_order_product_source_result = $this->_company_order_product_source->getCompanyOrderProductSource($company_order_product_source_params);
			 

// 			$price = $company_order_product_source_result[0]['price_before_tax'];
// 			$tax = $company_order_product_source_result[0]['source_price']-$company_order_product_source_result[0]['price_before_tax'];
// 		}else{
// 			//首先获取订单的资源编号
// 			$company_order_product_diy_params = [
// 					'receivable_number'=>$receivable_info_result[0]['receivable_number']
// 			];
// 			$company_order_product_diy_result = $this->_company_order_product_diy->getCompanyOrderProductDiy($company_order_product_diy_params);
// 			$company_order_relation_params= [
// 					'company_order_product_diy_id'=>	$company_order_product_diy_result[0]['company_order_product_diy_id'],
// 					'company_order_number'=>$receivable_info_result[0]['order_number'],
// 					'status'=>1
// 			];
// 			$company_order_customer_result = $this->_company_order_relation->getCompanyOrderRelation($company_order_relation_params);
			 
// 			$price = $company_order_product_diy_result[0]['price_before_tax'];
// 			$tax = $company_order_product_diy_result[0]['diy_price']-$company_order_product_diy_result[0]['price_before_tax'];
// 		}
		//获取税前报价
		
		
//     	for($i=0;$i<count($company_order_customer_result);$i++){
//     		$company_order_customer_lineup_params=[
//     				'status'=>1,
//     				'company_order_number'=>$company_order_number,
//     				'lineup_type'=>1,
//     				'company_order_customer_id'=>$company_order_customer_result[$i]['company_order_customer_id']
//     		];
    			
//     	}
    	
    	$sale_bill['customer_count'] = count($company_order_customer_result);
    	$sale_bill['customer_info'] = $company_order_customer_result;
    	
    	
    	
    	//通过销售收款ID获取报价信息
    	
    	
    	
    	/*
    	$company_order_price = [];
    	//获取分公司产品
    	$company_order_product_result = $this->_company_order_product->getCompanyOrderProduct($params);
    	for($i=0;$i<count($company_order_product_result);$i++){
    		$company_order_product_result_params = [
    			'product_name'=>$company_order_product_result[$i]['branch_product_name'],
    			'currency_name'=>$company_order_product_result[$i]['price_currency_name'],
    			'price'=>$company_order_product_result[$i]['branch_product_price'],
    			'total'=>$company_order_product_result[$i]['branch_product_price'],
    			'remark'=>'暂无'	
    		];
    		$company_order_price[] = $company_order_product_result_params;
    	}
    	
    	$company_order_product_source_params = [
    		'company_order_number'=>$company_order_number,
    		'status'=>1	
    	];
    	//获取资源
    	$company_order_product_source_result = $this->_company_order_product_source->getCompanyOrderProductSource($company_order_product_source_params);
     	for($i=0;$i<count($company_order_product_source_result);$i++){
//     		$company_order_product_source_params = [];
     		$company_order_product_source_result_params = [
     				'product_name'=>$company_order_product_source_result[$i]['source_name'],
     				'currency_name'=>$company_order_product_source_result[$i]['price_currency_name'],
     				'price'=>$company_order_product_source_result[$i]['source_price'],
     				'total'=>$company_order_product_source_result[$i]['source_price'],
     				'remark'=>'暂无'
     		];
     		$company_order_price[] = $company_order_product_source_result_params;
     	}

    	$company_order_product_diy_params = [
    			'status'=>1,
    			'company_order_number'=>$params['company_order_number']
    	];
    	 
    	//获取DIY
    	$company_order_product_diy_result = $this->_company_order_product_diy->getCompanyOrderProductDiy($company_order_product_diy_params);
    	    	for($i=0;$i<count($company_order_product_diy_result);$i++){
    		$company_order_product_diy_result_params = [
    				'product_name'=>$company_order_product_diy_result[$i]['diy_name'],
    				'currency_name'=>$company_order_product_diy_result[$i]['price_currency_name'],
    				'price'=>$company_order_product_diy_result[$i]['diy_price'],
    				'total'=>$company_order_product_diy_result[$i]['diy_price'],
    				'remark'=>'暂无'
    		];
    		$company_order_price[] = $company_order_product_diy_result_params;
    	}
    	
    	$sale_bill['company_order_price'] = $company_order_price;
    	
    	
    	*/
    	//
    	$company_order_price = [];
    	//通过应收编号获取报价
    	$receivable_params = [
    		'order_number'=>$company_order_number,
    		'status'=>1,
    		
    			
    	];
    	$company_order_receivable_result = $this->_receivable->getReceivable($receivable_params);
   
    	$temporary_result = [];
    	$receivable_info_array = explode(',',$receivable_info_id);
		
    	for($i=0;$i<count($receivable_info_array);$i++){
    		//通过id查询应收编号
    		$receivable_info_params = [
    			'receivable_info_id'=>$receivable_info_array[$i]
    		];
    		$receivable_info_result  = $this->_receivable_info->getReceivableInfo($receivable_info_params);
    
    		for($j=0;$j<count($company_order_receivable_result);$j++){
    			if($receivable_info_result[0]['receivable_number']==$company_order_receivable_result[$j]['receivable_number']){
    				$temporary_result[] = $company_order_receivable_result[$j];
    				continue;
    			}
    			 
    		}
    	}
						
    	$company_order_receivable_result = $temporary_result;
    
    	$receivable_voucher_string ='';
    	$receivable_info_array_result = [];
    	$paid = 0;
    	for($i=0;$i<count($receivable_info_array);$i++){
    		$receivable_info_params = [
    			'receivable_info_id'=>$receivable_info_array[$i]
    		];
    		
    		$receivable_info_result = $this->_receivable_info->getReceivableInfo($receivable_info_params);
    	
    		$receivable_voucher_string.=','.$receivable_info_result[0]['receivable_voucher'];
    		$paid+=$receivable_info_result[0]['payment_money'];
    		$receivable_info_result_params = [
    			
    			'currency_name'=>$receivable_info_result[0]['unit'],
    			'amount'=>	$receivable_info_result[0]['payment_money'],
    			'payment_type'=>	$receivable_info_result[0]['payment_type'],	
    			'payment_number'=>	$receivable_info_result[0]['payment_number'],
    			'receivable_user_name'=>$receivable_info_result[0]['receivable_user_name'],
    			'remark'=>$receivable_info_result[0]['remark'],
    				
    		];
    		if(!empty($receivable_info_result[0]['payment_time'])){
    			$receivable_info_result_params['payment_time']=date('Y-m-d',$receivable_info_result[0]['payment_time']);
    		}else{
    			$receivable_info_result_params['payment_time']='';
    		}
    		
    		$receivable_info_array_result[] = $receivable_info_result_params;
    	}
    	$receivable_voucher_string = trim($receivable_voucher_string,',');
    	
    	$sale_bill['sale_info'] = $receivable_info_array_result;
    	$balance = [];
    	for($i=0;$i<count($company_order_receivable_result);$i++){
    		$company_order_receivable_result_params = [
    				'product_name'=>$company_order_receivable_result[$i]['product_name'],
    				'currency_name'=>$company_order_receivable_result[$i]['currency_unit'],
    				
    				'price'=>$price,
    				'total'=>$company_order_receivable_result[$i]['receivable_money'],
    				'remark'=>$company_order_receivable_result[$i]['remark']
    		];
    		
    		$balance_params = [
    			'blance_money'=>$company_order_receivable_result[$i]['receivable_money']- $company_order_receivable_result[$i]['true_receipt'],
    			'unit'=>$company_order_receivable_result[$i]['unit']	
    		];
    		
    		
    		
    		$balance[] = $balance_params;
    		$company_order_price[] = $company_order_receivable_result_params;
    	}
    	$temporary_balance = [];
    	for($i=0;$i<count($balance);$i++){
    		$temporary_balance[$balance[$i]['unit']]+=$balance[$i]['blance_money'];
    	}
    	$balance = [];
    	foreach($temporary_balance as $key=>$v){
    		$balance_params =[
    			'blance_money'=>$v,
    			'unit'=>$key	
    		];
    		$balance[] = $balance_params;
    	}
    
    	$sale_bill['company_order_price'] = $company_order_price;
    	//获取当前的应收
//     	$receivable_params = [
//     		'status'=>1,
//     		'order_number'=>$company_order_number,
//     		'payment_object_type'=>$payment_object_type
//     	];
    	
//     	$receivable_result = $this->_receivable->getReceivable($receivable_params);
    	
    	$sale_bill['price'] = $price;
    	$sale_bill['tax'] = $tax;
    	$sale_bill['total'] = $receivable_result[0]['receivable_money'];
    	$sale_bill['paid'] =$receivable_info_result[0]['payment_money'];//实收
    	
    	//销售收款的未收
    	//通过销售收款的实收获得应收的数据
    	
    	$sale_bill['balance'] = $receivable_result[0]['receivable_money']-$receivable_info_result[0]['payment_money'];
    	$sale_bill['receivable_voucher'] = $receivable_voucher_string;
    	
    	$sale_bill['create_time'] =  $company_order_result[0]['create_time'];
    	
    	//通过公司订单获取分公司信息
    	$company_order_product_params = [
    		'company_order_number'=>$company_order_number,
    		'status'=>1	
    	];
    	
    	$company_order_product_result = $this->_company_order_product->getCompanyOrderProduct($company_order_product_params);
    	$company_order_product_number='';
    	$company_order_product_name='';
    	for($i=0;$i<count($company_order_product_result);$i++){
    		$company_order_product_number.=",".$company_order_product_result[$i]['branch_product_number'];
    		$company_order_product_name.=",".$company_order_product_result[$i]['branch_product_name'];
    	}
    	$sale_bill['branch_product_number'] =  trim($company_order_product_number,',');
    	$sale_bill['branch_product_name'] =  trim($company_order_product_name,',');
    	$sale_bill['begin_time'] = $company_order_result[0]['begin_time'];
    	$sale_bill['create_user_name'] = $company_order_result[0]['create_user_name'];
    	$sale_bill['description'] = $company_order_result[0]['remark'];
    	//$this->_company_order->
    	
    	$this->outPut($sale_bill);
    	
    }
    
    //获取报价账单
    public function getPriceBill(){
    	$params = $this->input();
    	 
    	$paramRule = [
    			'company_order_number' => 'string',
    			'bill_template_id'=>'number'
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    	$receivable_info_id = $params['receivable_info_id'];
    	//首先判断是否需要设置默认账单模板
    	if($params['default_bill_template_id'] == 1){
    		$user_params = [
    				'user_id'=>$params['now_user_id'],
    				'default_bill_template_id'=>$params['bill_template_id']
    		];
    
    		$this->_user->updateUserByUserId($user_params);
    	}
    	 
    	$sale_bill = [];
    	 
    	//首先获取账单数据
    	$bill_template_params = [
    			'bill_template_id'=>$params['bill_template_id']
    	];
    	$bill_result = $this->_bill_template->getBillTemplate($bill_template_params);
    
    	$sale_bill['bill_template_title_pic'] = $bill_result[0]['bill_template_title_pic'];
    	$sale_bill['bill_template_foot_pic'] = $bill_result[0]['bill_template_foot_pic'];
    	 
    	$company_order_number = $params['company_order_number'];
    	$receivable_info_id = $params['receivable_info_id'];
    	$receivable_info_array = explode(',',$receivable_info_id);
    	//通过订单编号获取订单信息
    	$company_order_params = [
    			'company_order_number'=>$params['company_order_number']
    	];
    	$company_order_result = $this->_company_order->getCompanyOrder($company_order_params);
    	 
    	 
    	 
    	if($company_order_result[0]['channel_type']==1){//经销商
    		//通过经销商ID查询经销商的信息
    		$distributor_params = [
    				'distributor_id'=>	$company_order_result[0]['distributor_id']
    		];
    		$distributor_result = $this->_distributor->getDistributor($distributor_params);
    		$sale_bill['content_name'] = $distributor_result[0]['distributor_name'];
    		$sale_bill['city_name'] =$distributor_result[0]['city_name'];
    		$sale_bill['country_name'] = $distributor_result[0]['country_name'];
    		$sale_bill['tel'] = $distributor_result[0]['tel'];
    		$sale_bill['email'] = $distributor_result[0]['email'];
    		$payment_object_type = 3;
    
    	}else{//直客
    		$sale_bill['content_name'] =$company_order_result[0]['contect_name'];
    		$sale_bill['city_name'] ='';
    		$sale_bill['country_name'] = '';
    		$sale_bill['tel'] = $company_order_result[0]['tel'];
    		$sale_bill['email'] = $company_order_result[0]['email'];
    		$payment_object_type = 4;
    	}
    	//通过订单查询游客信息
    	$company_order_customer_params = [
    			'company_order_number'=>$company_order_number,
    			'status'=>1
    	];
    	$company_order_customer_result = $this->_company_order_customer->getCompanyOrderCustomer($company_order_customer_params);
    	 

    	$receivable_params = [
    			'receivable_number'=>$receivable_info_id
    	];
    
    	$receivable_info_result = $this->_receivable->getReceivable($receivable_params);
    
    
    	$receivable_result = $receivable_info_result;
    
    
    	$price = $receivable_info_result[0]['receivable_money']-$receivable_info_result[0]['tax_money'];
    	$tax = $receivable_info_result[0]['tax_money'];
    
    
    	if($receivable_info_result[0]['source_type_id'] == 11){
    		//首先查询订单资源
    		$diy_params = [
    				'receivable_number'=>	$receivable_info_result[0]['receivable_number']
    		];
    		$diy_result = $this->_company_order_product_diy->getCompanyOrderProductDiy($diy_params);
    			
    		if(!empty($diy_result)){
    			$company_order_relation_params= [
    					'company_order_product_source_id'=>	$diy_result[0]['company_order_product_source_id'],
    					'company_order_number'=>$receivable_info_result[0]['order_number'],
    					'status'=>1
    			];
    				
    			$company_order_customer_result = $this->_company_order_relation->getCompanyOrderRelation($company_order_relation_params);
    
    		}
    			
    
    
    
    	}else{
    
    		$company_order_relation_params= [
    
    				'company_order_number'=>$receivable_info_result[0]['order_number'],
    					
    		];
    			
    		$company_order_customer_result = $this->_company_order_customer->getCompanyOrderCustomerByCompanyOrderNumber($company_order_relation_params);
    	}
    
    
    
    

    	$sale_bill['customer_count'] = count($company_order_customer_result);
    	$sale_bill['customer_info'] = $company_order_customer_result;
    	 
    	 
    	 

    	$company_order_price = [];
    	//通过应收编号获取报价

    
    	$temporary_result = [];
    	$receivable_info_array = explode(',',$receivable_info_id);
    	
    	for($i=0;$i<count($receivable_info_array);$i++){
    		$receivable_params = [
    				'order_number'=>$company_order_number,
    				'status'=>1,
    				'receivable_number'=>$receivable_info_array[$i]
    		
    		];
    		$company_order_receivable_result = $this->_receivable->getReceivable($receivable_params);
    		
    	
    		$temporary_result[] = $company_order_receivable_result[0];
    	}
    	
    	$company_order_receivable_result = $temporary_result;
    	error_log(print_r(help::modelDataToArr($company_order_receivable_result),1));
    	$receivable_voucher_string ='';
    	$receivable_info_array_result = [];
    	$paid = 0;
    	for($i=0;$i<count($receivable_info_array);$i++){
    		$receivable_info_params = [
    				'receivable_info_id'=>$receivable_info_array[$i]
    		];
    
    		$receivable_info_result = $this->_receivable_info->getReceivableInfo($receivable_info_params);
    		 
    		$receivable_voucher_string.=','.$receivable_info_result[0]['receivable_voucher'];
    		$paid+=$receivable_info_result[0]['payment_money'];
    		$receivable_info_result_params = [
    				 
    				'currency_name'=>$receivable_info_result[0]['unit'],
    				'amount'=>	$receivable_info_result[0]['payment_money'],
    				'payment_type'=>	$receivable_info_result[0]['payment_type'],
    				'payment_number'=>	$receivable_info_result[0]['payment_number'],
    				'receivable_user_name'=>$receivable_info_result[0]['receivable_user_name'],
    				'remark'=>$receivable_info_result[0]['remark'],
    
    		];
    		if(!empty($receivable_info_result[0]['payment_time'])){
    			$receivable_info_result_params['payment_time']=date('Y-m-d',$receivable_info_result[0]['payment_time']);
    		}else{
    			$receivable_info_result_params['payment_time']='';
    		}
    
    		$receivable_info_array_result[] = $receivable_info_result_params;
    	}
    	$receivable_voucher_string = trim($receivable_voucher_string,',');
    	 
    	$sale_bill['sale_info'] = $receivable_info_array_result;
    	$balance = [];
    	for($i=0;$i<count($company_order_receivable_result);$i++){
    		$company_order_receivable_result_params = [
    				'product_name'=>$company_order_receivable_result[$i]['product_name'],
    				'currency_name'=>$company_order_receivable_result[$i]['currency_unit'],
    
    				'price'=>$price,
    				'total'=>$company_order_receivable_result[$i]['receivable_money'],
    				'remark'=>$company_order_receivable_result[$i]['remark']
    		];
    
    		$balance_params = [
    				'blance_money'=>$company_order_receivable_result[$i]['receivable_money']- $company_order_receivable_result[$i]['true_receipt'],
    				'unit'=>$company_order_receivable_result[$i]['unit']
    		];
    
    
    
    		$balance[] = $balance_params;
    		$company_order_price[] = $company_order_receivable_result_params;
    	}
    	$temporary_balance = [];
    	for($i=0;$i<count($balance);$i++){
    		$temporary_balance[$balance[$i]['unit']]+=$balance[$i]['blance_money'];
    	}
    	$balance = [];
    	foreach($temporary_balance as $key=>$v){
    		$balance_params =[
    				'blance_money'=>$v,
    				'unit'=>$key
    		];
    		$balance[] = $balance_params;
    	}
    
    	$sale_bill['company_order_price'] = $company_order_price;
    	//获取当前的应收
    	//     	$receivable_params = [
    	//     		'status'=>1,
    	//     		'order_number'=>$company_order_number,
    	//     		'payment_object_type'=>$payment_object_type
    	//     	];
    	 
    	//     	$receivable_result = $this->_receivable->getReceivable($receivable_params);
    	 
    	$sale_bill['price'] = $price;
    	$sale_bill['tax'] = $tax;
    	$sale_bill['total'] = $receivable_result[0]['receivable_money'];
    	$sale_bill['paid'] =$receivable_info_result[0]['payment_money'];//实收
    	 
    	//销售收款的未收
    	//通过销售收款的实收获得应收的数据
    	 
    	$sale_bill['balance'] = $receivable_result[0]['receivable_money']-$receivable_info_result[0]['payment_money'];
    	$sale_bill['receivable_voucher'] = $receivable_voucher_string;
    	 
    	$sale_bill['create_time'] =  $company_order_result[0]['create_time'];
    	 
    	//通过公司订单获取分公司信息
    	$company_order_product_params = [
    			'company_order_number'=>$company_order_number,
    			'status'=>1
    	];
    	 
    	$company_order_product_result = $this->_company_order_product->getCompanyOrderProduct($company_order_product_params);
    	$company_order_product_number='';
    	$company_order_product_name='';
    	for($i=0;$i<count($company_order_product_result);$i++){
    		$company_order_product_number.=",".$company_order_product_result[$i]['branch_product_number'];
    		$company_order_product_name.=",".$company_order_product_result[$i]['branch_product_name'];
    	}
    	$sale_bill['branch_product_number'] =  trim($company_order_product_number,',');
    	$sale_bill['branch_product_name'] =  trim($company_order_product_name,',');
    	$sale_bill['begin_time'] = $company_order_result[0]['begin_time'];
    	$sale_bill['create_user_name'] = $company_order_result[0]['create_user_name'];
    	$sale_bill['description'] = $company_order_result[0]['remark'];
    	//$this->_company_order->
    	 
    	$this->outPut($sale_bill);
    	 
    }    
    /**
     * 获取订单收款明细
     */
    public function getCompanyOrderReceivableInfo(){
    	$params = $this->input();
    
    	$paramRule = [
    		'company_order_number' => 'string',
    		
    	];
    			 
    	$this->paramCheckRule($paramRule,$params);
    	
		$return_data = $this->_company_order_service->getCompanyOrderReceivableInfo($params);
    	
    	
    	$this->outPut($return_data);
    	
    }
    
    public function getaaa(){
    	$params = $this->input();
    				$branch_product_route_template_params =[
					'branch_product_number'=>$params['branch_product_number'],
					
			];
    		$branch_product_route_template_result = $this->_branch_product_route_template->getBranchProductRouteTemplate($branch_product_route_template_params);
		
			for($j=0;$j<count($branch_product_route_template_result);$j++){

			
				$proportion_result = $this->_proportion_service->getProportion($base_currency_id,$branch_product_route_template_result[$j]['price_currency_id']);
				$cost_proportion_result =  $this->_proportion_service->getProportion($base_currency_id,$branch_product_route_template_result[$j]['price_currency_id']);
				$distributor_price = $distributor_price+ $branch_product_route_template_result[$j]['distributor_price']*$proportion_result;
				$customer_price = $customer_price+ $branch_product_route_template_result[$j]['customer_price']*$proportion_result;
				$branch_product_cost = 1;				
			}

    	//通过团队产品 ID获取 订单
    	
    	$this->outPut(1);	
    }

    /**
     * getCustomerSource
     *
     * 获取客户来源列表
     * @author shj
     * @return void
     * Date: 2019/4/10
     * Time: 16:21
     */
    public function getCustomerSource(){
        $params = $this->input();

        $customer = new CustomerSource();

        if(isset($params['page'])){
            $params['page_size'] =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $params['page'] = ($params['page']-1)*$params['page_size'];
            $count =  $customer->getCustomerSource($params,true);

            $result = $customer->getCustomerSource($params,false);
            $data = [

                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$params['page_size'])

            ];
            return $this->output($data);
        }

        $result = $customer->getCustomerSource($params,false);

        $this->outPut($result);
    }

    /**
     * getOneCustomerSource
     *
     * 获取一条客户来源
     * @author shj
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Date: 2019/4/11
     * Time: 18:00
     */
    public function getOneCustomerSource(){
        $params = $this->input();
        $customerSource = new CustomerSource();
        $result = $customerSource->getOneCustomerSource($params);
        $this->outPut($result);
    }

    public function getCustomerOrder(){
        $params = $this->input();
        $customerSource = new CompanyOrderCustomer();
        $result = $customerSource->getCompanyOrderCustomer($params);

        $this->outPut($result);
    }

    /**
     * addCustomerSource
     *
     * 新增客户来源
     * @author shj
     * @return void
     * Date: 2019/4/11
     * Time: 16:31
     */
    public function addCustomerSource(){
        $params = $this->input();
        $paramRule = [
            'customer_source_name' => 'string',
          
          
        ];
        $this->paramCheckRule($paramRule,$params);
        //重复性校验

        $customerSource = new CustomerSource();

        $result = $customerSource->addCustomerSource($params);

        $this->outPut($result);
    }

    /**
     * editCustomerSource
     *
     * 修改客户来源
     * @author shj
     * @return void
     * Date: 2019/4/11
     * Time: 18:00
     */
    public function editCustomerSource(){
        $params = $this->input();
        $paramRule = [
            'customer_source_id' => 'number',
            'customer_source_name' => 'string',
         
     
        ];
        $this->paramCheckRule($paramRule,$params);
        //重复性校验

        $customerSource = new CustomerSource();

        $result = $customerSource->updateCustomerSource($params);

        $this->outPut($result);
    }
    
    public function getCompanyOrderBookingListByThatWomen(){
    	
    }

    /**
     * booking订单接口
     * Created by PhpStorm.
     * User: yyy
     * Date: 2019/4/22
     * Time: 10:16
     */
    public function getCompanyOrderBookingList()
    {

        $params = $this->input();
        
        //实例化用到的模型

        $company_order_count =  $this->_company_order->getCompanyOrder($params,true);
        $company_order_arr = $this->_company_order->getCompanyOrder($params);
        $company_order_arr = $this->_company_order_service->getCompanyOrderReceivableCopeTrueReceivableTrueCope($company_order_arr);

        $company_order_list = [];

        foreach ($company_order_arr as $k => $v)
        {
            //对象转为数组
            $v = $v->toArray();

            //人数
            $customer = $this->_company_order_service->getCustomer(['company_order_number'=> $v['company_order_number']]);
            $v['customer_count'] = count($customer);
            //创建人
            $v['agent_nickname'] = $this->_user->getOneUser($v['create_user_id'])['nickname'];
            //零售/整售
            $v['wr'] = $v['wr'] == 1 ? 'retail' : ($v['wr'] ==2 ? 'wholesale' : '');

            //客户来源
            $arr = ['1' => 'toronto star', '2' => 'fairchild radio', '3'=> 'repeat client', '4' => 'nexus web', '5' => 'singtao newspaper'];
            $v['clientsource'] = $arr[$v['clientsource']];

            //代理 distributor_id
            $channel_type = $v['channel_type'];
            if ($v['distributor_id'])
            {
                // 销售渠道
                $v['channel_type'] = '代理';
                $distributor_info = $this->_distributor->getDistributor(['distributor_id' => $v['distributor_id']]);

                $distributor_info =  $distributor_info[0]->toArray();

                //经销商类型
                $distributor_info['associate_type'] = $distributor_info['associate_type'] == 1 ? 'assembled' : ($distributor_info['associate_type'] ==2 ? 'not assembled' : '');
                //将代理经销商的数组遍历到 $v 里
                foreach($distributor_info as $distributor_k => $distributor_v)
                {
                    $v['distributor_'.$distributor_k] = $distributor_v;
                }
            }

            if ($channel_type != 1)
            {
                // 销售渠道
                $v['channel_type'] = $channel_type == 2 ? '直客' : '';
                //邮箱
                $v['distributor_email'] = $v['email'];
                //联系人
                $v['distributor_tel'] = $v['tel'];
                //电话
                $v['distributor_contect'] = $v['contect_name'];
            }

            //小报表  receivable
            $company_order_receivable_info = $this->_company_order_service
                                                   ->getCompanyOrderReceivableInfo([
                                                        'company_order_number' => $v['company_order_number'],
                                                        'now_user_id' => $params['now_user_id']
                                                   ]);

            //将应收的数组遍历到 $v里
            foreach($company_order_receivable_info as $receivable_k => $receivable_v)
            {
                $v['receivable_'.$receivable_k] = $receivable_v;
            }

            //佣金 暂时 0
            $v['receivable_brokerage'] = 0.00;
            //订购日期时间戳 格式化
            $v['buy_order_time'] = $v['buy_order_time'] ? date('Y-m-d', $v['buy_order_time']) : '-';
            //出发日期时间戳 格式化
            $v['begin_time'] = $v['begin_time'] ? date('Y-m-d', $v['begin_time']) : '-';
            //结束日期时间戳 格式化
            $v['end_time'] = $v['end_time'] ? date('Y-m-d', $v['end_time']) : '-';

            array_push($company_order_list, $v);
        }

        $this->outPut($company_order_list,'',$company_order_count);

    }


    /**
     * 付款方式列表
     * Created by PhpStorm.
     * User: yyy
     * Date: 2019/4/22
     * Time: 15:09
     */
    public function getClientPaymentList()
    {
        $params = $this->input();
        $params['status'] = 1;
        $params['receivable_info_type'] = 2;

        $client_payment_count = $this->_receivable_info->getReceivableInfo($params, true);
        $receivable_info_list = $this->_receivable_info->getReceivableInfo($params);

        $client_payment_list = $this->_formatData($receivable_info_list);

        $this->outPut($client_payment_list,'',$client_payment_count);
    }


    /**
     * 获取会员付款方式列表
     * Created by PhpStorm.
     * User: yyy
     * Date: 2019/4/23
     * Time: 9:44
     */
    public function getAccountantPaymentList()
    {
        $params = $this->input();
        $params['status'] = 1;
        $params['receivable_info_type'] = 1;

        $account_payment_count = $this->_receivable_info->getReceivableInfo($params, true);
        $receivable_info_list = $this->_receivable_info->getReceivableInfo($params);

        $client_payment_list = $this->_formatData($receivable_info_list);

        $client_payment_list = Help::replaceNull($client_payment_list, null , '-');

        $this->outPut($client_payment_list,'',$account_payment_count);
    }


    /**
     * 格式化数组
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/4/23
     * Time: 10:14
     * @param array $data 需要格式化的数组
     * @return array
     */
    private function _formatData($data)
    {
        $arr = [];
        foreach ($data as $k => $v)
        {
            //对象转为数组
            $v = $v->toArray();
            //支付类型
            $payment_type_arr = ['1'=>'cash', '2'=>'check', '3'=>'debit card', '4'=>'credit card(mc)', '5'=>'credit card(vs)', '6'=>'credit card(ax)', '7'=>'direct depsit', '8'=>'others'];
            $v['payment_type'] = $payment_type_arr[$v['payment_type']];
            //支付方式
            $payment_stage_arr = ['1'=>'Balance', '2'=>'Deposit', '3'=>'Fullpmt', '4'=>'Gratuities', '5'=>'Insurance', '6'=>'Partpmt', '7'=>'Rebate', '8'=>'Tkt', '9'=>'Visa'];
            $v['payment_stage'] = $payment_stage_arr[$v['payment_stage']];
            //币种
            $v['payment_currency'] = $this->_currency->getCurrency(['currency_id' => $v['payment_currency_id']])[0]['currency_name'];

            //is_locked
            $v['is_locked'] = $v['is_locked'] == 1 ? '锁定' : '不锁定';
            //pts
            $v['pts'] = $v['pts'] == 1 ? '是' : '否';
            //创建人
            $v['create_user_nickname'] = $this->_user->getOneUser($v['create_user_id'])['nickname'];
            //订单编号
            $v['order_number'] = $this->_receivable->getReceivableByReceivableNumber($v['receivable_number'])['order_number'];

            //订单info
            $company_order_info = $this->_company_order->getCompanyOrderByOrderNumber($v['order_number']);
            //人数
            $v['persions_count'] = $company_order_info['persions_count'];
            //零售/整售
            $v['wr'] = $company_order_info['wr'] == 1 ? 'retail' : ($company_order_info['wr'] == 2 ? 'wholesale' : '');
            //出发日期
            $v['begin_time'] = date('Y-m-d', $company_order_info['begin_time']);

            //代理名称
            $v['distributor_name'] = $this->_distributor->getDistributorByDistributorId($company_order_info['distributor_id'])['distributor_name'];


            $v['team_product_number'] = '';
            $v['branch_product_numbers'] = '';
            if (in_array($v['fee_type_code'], [82,84]))
            {
                //团队产品编号
                $team_product = $this->_company_order_product_team->getCompanyOrderProductTeam(['status' => 1, 'company_order_number' => $v['order_number']]);
                $team_product_number = [];
                foreach ($team_product as $value)
                {
                    $team_product_number[] = $this->_team_product->getTeamProduct(['team_product_id' => $value['team_product_id']])[0]['team_product_number'];
                }
                $v['team_product_number'] = implode(',', $team_product_number);

                //分公司产品编号
                $company_order_product = $this->_company_order_product->getCompanyOrderProduct(['status'=>1, 'receivable_number' => $v['receivable_number']]);
                $v['branch_product_numbers'] = implode(',', array_column($company_order_product, 'branch_product_number'));
            }


            //付款时间格式化
            $v['payment_time'] = $v['payment_time'] ? date('Y-m-d', $v['payment_time']) : '-';
            array_push($arr, $v);
        }

        return $arr;
    }



    /**
     * 获取Cost列表
     * Created by PhpStorm.
     * User: yyy
     * Date: 2019/4/23
     * Time: 11:31
     */
    public function getCostList()
    {
        $params = $this->input();

        $where = ['c.company_id' => $params['company_id'], 'c.status' => 1];
        $diy_arr = $this->_cope->getCopeAndProductDiy($where);

        $diy_arr = $this->_productData($diy_arr, 3);

        $source_arr = $this->_cope->getCopeAndProductSource($where);
        $source_arr = $this->_productData($source_arr, 2);

        $where['settlement_type'] = '1';
        $team_arr = $this->_cope->getCopeAndProductTeam($where);
        $team_arr = $this->_productData($team_arr, 1);

        $arr = array_merge($diy_arr, $team_arr, $source_arr);

        $this->outPut($arr, '', count($arr));

    }


    /**
     * 格式化getCostList列表的数据
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/6
     * Time: 11:27
     * @param array $data 需要格式化的数组
     * @param int $type  1 team 2 source 3 diy
     * @return array 格式化后的数组
     */
    private function _productData($data, $type)
    {
        $array = [];
        foreach ($data as $k=>$v)
        {
            //对象转为数组
            $v = $v->toArray();

            switch ($type)
            {
                case 1:
                    //team
                    $v['type'] = '团队产品';
                    $v['cost'] = $v['team_product_cost'];
                    break;
                case 2:
                    //source
                    $v['type'] = Help::getSupplierTypeNameById($v['supplier_type_id']);
                    $v['cost'] = $v['source_cost'];
                    break;
                case 3:
                    //diy
                    $supplier_model = new Supplier();
                    $v['supplier_name'] = $supplier_model->where("supplier_id = ". $v['supplier_id'])->find()['supplier_name'];
                    $v['type'] = '其他';
                    $v['cost'] = $v['diy_cost'];
                    break;
            }

            //格式化发票日期
            $v['invoice_time'] = $v['invoice_time'] ? date('Y-m-d', $v['invoice_time']) : '-';
            $company_order = $this->_company_order->getCompanyOrderByOrderNumber($v['company_order_number']);
            //创建人
            $v['create_user_nickname'] = $this->_user->getOneUser($company_order['create_user_id'])['nickname'];
            //订单编号
            $v['order_number'] = $v['company_order_number'];
            //出发日期
            $v['begin_time'] = $company_order['begin_time'] ? date('Y-m-d', $company_order['begin_time']) : '-';
            //币种
            $v['currency'] = $this->_currency->getCurrency(['currency_id' => $v['cost_currency_id']])[0]['currency_name'];
            //税种
            $v['txcd'] = $v['tax_cd'];

            array_push($array, $v);
        }
        return $array;
    }


    /**
     * 添加财务收款
     * Created by PhpStorm.
     * User: yyy
     * Date: 2019/4/25
     * Time: 16:09
     */
    public function addReceivableInfo()
    {
        $params = $this->input();
		
        $paramRule = [
            'payment_type'=>'number',           //支付类型
            'payment_stage'=>'number',          //支付方式
            'payment_currency_id'=>'number',    //币种id
            'receivable_number'  => 'string',   //对应应收编号
            'payment_money'     => 'string'         //金额

        ];
	
        $this->paramCheckRule($paramRule,$params);
        $receivable_info_service = new ReceivableInfoService();
        $result = $receivable_info_service->editReceivableInfo($params);

        $result === true ? $this->outPut(1) : $this->outPutError($result);
    }

    //paypal支付信息纪录
    public function addCompanyOrderPayRecord()
    {
        $params = $this->input();

        $paramRule = [
            'company_order_number'=>'string',           //订单编号
            'payer_id'=>'string',          //买方 paypal id
            'payment_status' => 'string',   //付款状态
            'payer_email'=>'string',          //买方邮件
            'payment_date'=>'string',          //PayPal生成的格式的时间和日期戳
            'first_name'=>'string',          //客户的名字
            'last_name'=>'string',          //客户姓氏
            'receiver_email'=>'string',          //收款方邮件
            'business'=>'string',          //收款方 电子邮件地址或帐户 ID支付接收者
            'mc_gross'=>'string',          //扣除交易费之前，全额支付客户的款项
            'mc_fee'=>'string',          //paypal 手续费
            'mc_currency'=>'string',          //付款的货币
            'receiver_id'=>'string',          //商家id
            'txn_id' => 'string'
        ];

        $this->paramCheckRule($paramRule,$params);

        $order_pay_record_model = new OrderPayRecord();

        $result = $order_pay_record_model->addOrderPayRecord($params);

        $result === 1 ? $this->outPut($result) : $this->outPutError($result);

    }

    //添加订单附件
    public function addCompanyOrderAnnex()
    {
        $params = $this->input();
        $paramRule = [
            'url'=>'string',           //附件地址url
            'company_order_number' => 'string'
        ];

        $this->paramCheckRule($paramRule,$params);


        $order_pay_record_model = new CompanyOrderAnnex();

        $result = $order_pay_record_model->addCompanyOrderAnnex($params);

        $this->outPut($result);
    }

    //获取订单附件
    public function getCompanyOrderAnnex()
    {
        $params = $this->input();
        $order_pay_record_model = new CompanyOrderAnnex();

        $result = $order_pay_record_model->getCompanyOrderAnnex($params);
        $this->outPut($result);

    }

    public function delCompanyOrderAnnex()
    {
        $params = $this->input();
        $order_pay_record_model = new CompanyOrderAnnex();

        $paramRule = [
            'company_order_annex_id' => 'number'
        ];

        $this->paramCheckRule($paramRule,$params);


        $result = $order_pay_record_model->delCompanyOrderAnnex($params);
        $this->outPut($result);
    }

    //添加订单留言板
    public function addCompanyOrderComment()
    {
        $params = $this->input();
        $paramRule = [
            'comment' => 'string',
            'company_order_number' => 'string'
        ];

        $this->paramCheckRule($paramRule,$params);
 
        $order_pay_record_model = new CompanyOrderComment();

        $result = $order_pay_record_model->addCompanyOrderComment($params);
        //修改公司订单状态为未确认
        $company_order = new CompanyOrder();
        $company_order_params=[
        		'company_order_number'=>$params['company_order_number'],
        		'company_order_status'=>1
        ];
        $company_order_result = $company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
         
        $this->outPut($result);
    }

    //获取订单留言板
    public function getCompanyOrderComment()
    {
        $params = $this->input();
        $order_pay_record_model = new CompanyOrderComment();

        $result = $order_pay_record_model->getCompanyOrderComment($params);
        $this->outPut($result);

    }



    //加拿大信用卡 支付信息纪录
    public function addCompanyOrderCardPayRecord()
    {
        $params = $this->input();

        $paramRule = [
            'company_order_number' => 'string',           //订单编号
            'ssl_approval_code' => 'string', //交易批准代码 信用卡处理器返回的唯一代码，指示交易的批准状态。
            'ssl_card_number' => 'string', //付款方卡号
            'ssl_txn_id' => 'string',      //交易编号 交易的唯一标识符。
            'ssl_txn_time' => 'string',      //处理日期和时间 格式： MM / DD / YYYY hh：mm：ss AM / PM
            'ssl_amount' => 'string',          //交易总额 该金额包括小费金额
            'ssl_result_message' => 'string',  //交易结果信息
            'ssl_result' => 'number', //ssl_result= 0表示已批准交易 ssl_result 不等于0表示拒绝交易和未经授权的交易。
        ];

        $this->paramCheckRule($paramRule,$params);

        $order_pay_record_model = new OrderCardPayRecord();

        $result = $order_pay_record_model->addOrderCardPayRecord($params);

        $result === 1 ? $this->outPut($result) : $this->outPutError($result);

    }

    public function updateBranchProductStatusByRouteTemplateId()
    {
        $params = $this->input();

        $paramRule = [
            'route_template_id'=>'number',
        ];
        $this->paramCheckRule($paramRule,$params);

        $branch_product = new BranchProduct();
        $result = $branch_product->updateBranchProductStatusByRouteTemplateId($params);
        $result === 1 ? $this->outPut($result) : $this->outPutError($result);

    }
    //添加线路模板留言板
    public function addBranchProductComment()
    {
    	$params = $this->input();
    	$paramRule = [
    		'comment' => 'string',
    		'branch_product_number' => 'string'
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    	$order_pay_record_model = new BranchProductComment();
    
    	$result = $order_pay_record_model->addBranchProductComment($params);
    
    	$this->outPut($result);
    }
    //添加线路模板留言板
    public function getBranchProductComment()
    {
    	$params = $this->input();
    
    	$this->paramCheckRule($paramRule,$params);
    
    	$order_pay_record_model = new BranchProductComment();
  
    	$result = $order_pay_record_model->getBranchProductComment($params);
    
    	$this->outPut($result);
    }

    public function getBranchProductByRouteTemplateId()
    {
        $params = $this->input();

        $order_pay_record_model = new BranchProduct();

        $result = $order_pay_record_model->getBranchProductByRouteTemplateId($params['route_template_id']);

        $this->outPut($result);
    }
    
    /*8
     * 获取付款单的相应信息
     */
    
    
    public function getCodeBill(){
    	$params = $this->input();
    	//第一版假设给了 应付编号
    	
    	$cope_array = $params['cope_array'];
    	
    	
    	
    	
    	
    }
}
