<?php
namespace app\index\controller;
use app\common\help\Contents;
use app\common\help\Help;
use app\index\model\product\RouteOncePrice;
use app\index\model\product\TeamProductAllocation;
use app\index\model\product\TeamProductFlight;
use app\index\model\product\TeamProductTourGuideJounery;
use app\index\model\product\TeamProductJourney;
use app\index\model\product\TeamProductOncePrice;
use app\index\model\product\TeamProductReturnReceipt;
use think\config;
use app\index\controller\Base;
use app\index\model\product\ReturnReceipt;
use app\index\model\product\ReturnReceiptInfo;
use app\index\model\product\RouteType;
use app\index\model\product\RouteTemplateComment;
use app\index\model\product\TeamProduct;
use app\index\model\product\RouteFlight;
use app\index\model\product\RouteJourney;
use app\index\model\product\RouteReturnReceipt;
use app\index\model\product\RouteSourceCruise;
use app\index\model\product\RouteSourceHotel;
use app\index\model\product\RouteSourceDining;
use app\index\model\product\RouteSourceFlight;
use app\index\model\product\RouteSourceVisa;
use app\index\model\product\RouteSourceVehicle;
use app\index\model\product\RouteSourceScenicSport;
use app\index\model\product\RouteSourceTourGuide;
use app\index\model\product\RouteSourceAllocation;
use app\index\model\product\RouteTemplate;
use app\index\model\product\RouteSingleSource;
use app\index\model\product\TeamProductOtherCope; 
use app\index\model\product\ProductCommon;
use app\index\model\finance\Cope;
use app\index\model\finance\Receivable;
use app\index\model\finance\ReceivableCustomer;
use app\index\model\finance\TravelAgencyReimbursement;
use app\index\model\finance\TravelAgencyReimbursementCope;
use app\index\model\finance\TravelAgencyReimbursementReceivable;
use app\index\model\finance\TravelAgencyReimbursementReceivableCustomer;
use app\index\model\source\Supplier;
use app\index\model\branchcompany\CompanyOrderCustomerlineup;
use app\index\model\branchcompany\CompanyOrderFlight;
use app\index\model\branchcompany\CompanyOrderProductDiy;
use app\index\model\branchcompany\CompanyOrderCustomer;
use app\index\model\branchcompany\CompanyOrderProductSource;
use app\index\model\branchcompany\CompanyOrderRelation;
use app\index\model\branchcompany\CompanyOrder;
use app\index\model\branchcompany\CompanyOrderProductTeam;
use app\index\service\CompanyOrderService;
use app\index\service\ReceivableService;
use app\index\service\FinacesService;
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
use app\index\model\approve\StatementApprove;
use app\index\model\system\Currency;
use think\Model;
use app\index\service\ReceivableInfoService;
class Product extends Base
{
	private $_language;
	private $_cope;
	private $_travel_agency_reimbursement;
	private $_travel_agency_reimbursement_cope;
	private $_receivable;
	private $_supplier;
	private $_team_product;
	private $_team_product_journey;
	private $_company_order_customer_lineup;
	private $_company_order_product_source;
	private $_company_order_relation;
	private $_company_order_customer;
	private $_company_order_product_diy;
	private $_team_product_return_receipt;
	private $_team_product_tour_guide_jounery;
	private $_company_order;
	private $_company_order_product_team;
	private $_company_order_service;
	private $_source_service;
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
    private $_product_common;
    private $_finaces_service;
    private $_statement_approve;
    private $_receivable_service;
    private $_currency;
    //_lang Base里的属性，
    public function __construct()
    {
    	$this->_language = config("systom_setting")['language_default'];
    	$this->_cope = new Cope();
    	$this->_receivable = new Receivable();
    	$this->_travel_agency_reimbursement = new TravelAgencyReimbursement();
    	$this->_travel_agency_reimbursement_cope = new TravelAgencyReimbursementCope();
    	$this->_supplier = new Supplier();
    	$this->_team_product = new TeamProduct();
    	$this->_company_order_customer_lineup = new CompanyOrderCustomerlineup();
    	$this->_team_product_journey = new TeamProductJourney();
    	$this->_company_order_flight = new CompanyOrderFlight();
    	$this->_company_order_product_source = new CompanyOrderProductSource();
    	$this->_company_order_relation = new CompanyOrderRelation();
    	$this->_team_product_return_receipt = new TeamProductReturnReceipt();
    	$this->_team_product_tour_guide_jounery = new TeamProductTourGuideJounery();
    	$this->_company_order = new CompanyOrder();
    	$this->_company_order_product_team = new CompanyOrderProductTeam();
    	$this->_company_order_customer = new CompanyOrderCustomer();
    	$this->_company_order_service = new CompanyOrderService();
    	$this->_company_order_product_diy = new CompanyOrderProductDiy();
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
        $this->_product_common = new ProductCommon();
        $this->_finaces_service = new FinacesService();
        $this->_statement_approve = new StatementApprove();
        $this->_receivable_service = new ReceivableService();
        $this->_currency = new Currency();
        parent::__construct();
    }
    /**
     * 添加回执单
     * 胡
     */
//     public function addReturnReceipt(){

//         $params = $this->input();

//         $paramRule = [
//             'return_receipt_name' => 'string',
//             'user_id'=>'string',
//         	'status'=>'number'	

//         ];
			
//         $this->paramCheckRule($paramRule,$params);

//         $return_receipt = new ReturnReceipt();

//         //首先判断名字是否有重复
//         $return_receipt_result = $return_receipt->addReturnReceipt($params);

//         $this->outPut($return_receipt_result);
//     }
    /**
     * 获取回执单
     * 胡
     */
//     public function getReturnReceipt(){


//         $params = $this->input();

//         $return_receipt = new ReturnReceipt();
//         /*
//         if(isset($params['lang'])){
//             $lang = $params['lang'];
//         }else{
//             $lang = [];
//         }
//         */
//         $return_receiptResult = $return_receipt->getReturnReceipt($params);
//         $this->outPut($return_receiptResult);

//     }

    /**
     * 修改回执单
     * 胡
     */

//     public function updateReturnReceiptByReturnReceiptId(){
//         $params = $this->input();

//         $paramRule = [

//             'user_id'=>'number',
//         	'return_receipt_id'=>'number'	

//         ];

//         $this->paramCheckRule($paramRule,$params);
//         $return_receipt = new ReturnReceipt();
//         $return_receiptResult = $return_receipt->updateReturnReceiptByReturnReceiptId($params);
//         $this->outPut($return_receiptResult);


//     }
    /**
     * 添加回执单内容
     * 胡
     */
//     public function addReturnReceiptInfo(){
    
//     	$params = $this->input();
    
//     	$paramRule = [
//     		'return_receipt_id' => 'number',
//     		'user_id'=>'string',
//     		'status'=>'number',
//     		'return_receipt_info'=>'array'
    
//     	];
    		
//     	$this->paramCheckRule($paramRule,$params);
    
    
    
    
//     	$return_receipt_info = new ReturnReceiptInfo();
    

//     	$return_receipt_info_result = $return_receipt_info->addReturnReceiptInfo($params);
    
//     	$this->outPut($return_receipt_info_result);
//     }
    /**
     * 获取回执单内容
     * 胡
     */
//     public function getReturnReceiptInfo(){
    
    
//     	$params = $this->input();
    
//     	$return_receipt_info = new ReturnReceiptInfo();
//     	/*
//     	 if(isset($params['lang'])){
//     	 $lang = $params['lang'];
//     	 }else{
//     	 $lang = [];
//     	 }
//     	*/
//     	$return_receipt_infoResult = $return_receipt_info->getReturnReceiptInfo($params);
//     	$this->outPut($return_receipt_infoResult);
    
//     }
    
    /**
     * 修改回执单内容
     * 胡
     */
    
//     public function updateReturnReceiptInfoByReturnReceiptInfoId(){
//     	$params = $this->input();
    
//     	$paramRule = [
    
//     		'user_id'=>'number',
//     		'return_receipt_info_id'=>'number'
    
//     	];
    
//     	$this->paramCheckRule($paramRule,$params);
//     	$return_receipt_info = new ReturnReceiptInfo();
//     	$return_receipt_infoResult = $return_receipt_info->updateReturnReceiptInfoByReturnReceiptInfoId($params);
//     	$this->outPut($return_receipt_infoResult);
    
    
//     }   
    /**
     * 添加路线类型
     * 胡
     */
//     public function addRouteType(){
    
//     	$params = $this->input();
    
//     	$paramRule = [
    	
//     		'route_type_name' => 'string',
//     		'user_id'=>'string',
//     		'status'=>'number',
//     		'type'=>'number'
    
//     	];
    
//     	$this->paramCheckRule($paramRule,$params);
    
    
    
    
//     	$route_type = new RouteType();
    
    
    
    
    
//     	//首先判断名字是否有重复
//     	$route_type_result = $route_type->addRouteType($params);
    
//     	$this->outPut($route_type_result);
//     }
    /**
     * 获取路线类型
     * 胡
     */
//     public function getRouteType(){
    
    
//     	$params = $this->input();
    
//     	$route_type = new RouteType();
//     	/*
//     	 if(isset($params['lang'])){
//     	 $lang = $params['lang'];
//     	 }else{
//     	 $lang = [];
//     	 }
//     	*/
//     	$route_typeResult = $route_type->getRouteType($params);
//     	$this->outPut($route_typeResult);
    
//     }
    
    /**
     * 修改路线类型
     * 胡
     */
    
    public function updateRouteTypeByRouteTypeId(){
    
    	$params = $this->input();
    
    	$paramRule = [
    
    		'user_id'=>'number',
    		'route_type_id'=>'number'
    
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    	$route_type = new RouteType();
    	$route_typeResult = $route_type->updateRouteTypeByRouteTypeId($params);
    	
    	$this->outPut($route_typeResult);
    
    
    } 
    /**
     * 添加路线模板
     * 胡
     */
    public function addRouteTemplate(){
    
    	$params = $this->input();

    	$paramRule = [
			'route_name' => 'string',
    		'user_id'=>'number',
    		'status'=>'number',
    		'route_type_id'=>'number',
    		'route_user_id'=>'number',
            'company_id'=>'number',
            'use_company_id'=>'string',
    		'settlement_type'=>'number',
//            'plan_custom_number'=>'number',
//            'before_days'=>'number',
    	];
    	$this->paramCheckRule($paramRule,$params);

        //首先判断名字是否有重复
        $data = [
            'route_name'=>$params['route_name'],
            'company_id'=>$params['company_id']
        ];
        $this->checkNameIsRepetition('route_template',$data);
        //结束判断名字重复

    	$params['route_number'] = Help::getNumber(1);//获取线路编号
    	$route_template = new RouteTemplate();

		$route_template_result =$route_template->addRouteTemplate($params);
		
		

    	$this->outPut($route_template_result);


    	
    }
    /**
     * 获取路线模板
     * 胡
     */
    public function getRouteTemplate(){
    	
    	$params = $this->input();
    
        $route_template = new RouteTemplate();
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $route_template->getRouteTemplate($params, true);
            $result = $route_template->getRouteTemplate($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

    	$route_templateResult = $route_template->getRouteTemplate($params);
       
    	$this->outPut($route_templateResult);
    
    }
    /**
     *获取线路模板名称 
     */
    
    public function getRouteTemplateName(){
    	$params = $this->input();
    	$route_template = new RouteTemplate();
    	
    	$route_templateResult = $route_template->getRouteTemplateName($params);
    	$this->outPut($route_templateResult);    	
    }
    /**
     * 修改路线模板
     * 胡
     */
    
    public function updateRouteTemplateByRouteTemplateId(){
    	$params = $this->input();
        $route_template = new RouteTemplate();

        $Info = $route_template->getOneRouteTemplate($params['route_template_id']);
        if($Info['route_name'] == $params['route_name']){
        }else{

            //开始判断名字是否重复
            $data = [
                'route_name'=>$params['route_name'],
                'company_id'=>$params['company_id'],
            ];
            $this->checkNameIsRepetition('route_template',$data);
            //结束判断名字重复
        }



    	$paramRule = [
    
    		'user_id'=>'number',
    		'route_template_id'=>'number',
    		'status'=>'number'	
    
    	];    
  
    	$this->paramCheckRule($paramRule,$params);

    	$route_template_result = $route_template->updateRouteTemplateByRouteTemplateId($params);
    
    	$this->outPut($route_template_result);
    
    
    }    

    /**
     * 添加路线模板航班信息
     * 胡
     */
    public function addRouteFlight(){
    
    	$params = $this->input();
    
    	$paramRule = [    
    		'route_template_id' => 'number',
    		'user_id'=>'number',
    		'status'=>'number',

    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    	$route_template = new RouteFlight();
    

    	$route_template_result = $route_template->addRouteFlight($params);
    	$this->outPut($route_template_result);
    
    }    
    /**
     * 获取路线模板航班信息
     * 胡
     */
    public function getRouteFlight(){
    
    	$params = $this->input();

    

    	$route_template = new RouteFlight();
    	$route_template_result = $route_template->getRouteFlight($params);
    	$this->outPut($route_template_result);
    
    }
    /**
     * 编辑路线模板航班信息通过路线模板航班信息ID
     * 胡
     */
    public function updateRouteFlightByRouteFlightId(){
    
    	$params = $this->input();
    	$paramRule = [
    		'route_flight_id' => 'number',
    		'user_id'=>'number',
    
    	
    	];
 
    	$this->paramCheckRule($paramRule,$params);
    
    	$route_template = new RouteFlight();
    	$route_template_result = $route_template->updateRouteFlightByRouteFlightId($params);
    	$this->outPut($route_template_result);
    
    }    
    /**
     * 添加路线行程信息
     */
    public function addRouteJourney(){
    	
    	$params = $this->input();
		
    	$paramRule = [
    		'route_template_id' =>'number',
    		'user_id'=>'number',
    		'status'=>'number',
    	];
    	 
    	$this->paramCheckRule($paramRule,$params);
    	 
    	$route_journey = new RouteJourney();
    	 
    	
    	$route_template_result = $route_journey->addRouteJourney($params);

    	$this->outPut($route_template_result);

    }
    /**
     * 获取路线行程信息
     */
    public function getRouteJourney(){
    	$params = $this->input();
    	$route_journey = new RouteJourney();
    	
    	 
    	$route_template_result = $route_journey->getRouteJourney($params);
    	
    	$this->outPut($route_template_result);    	
    	
    }
    
    /**
     * 修改路线行程信息
     */
    public function updateRouteJourneyByRouteJourneyId(){
    	$params = $this->input();
    	
    	$paramRule = [
    		'route_journey_id' => 'number',
    		'user_id'=>'number',
    		
    	];
    	
    	$this->paramCheckRule($paramRule,$params);
    	
    	$route_journey = new RouteJourney();
    	
    	 
    	$route_template_result = $route_journey->updateRouteJourneyByRouteJourneyId($params);
    	
    	$this->outPut($route_template_result);    	
    }
    /**
     * 添加路线回执单内容
     * 胡
     */
    public function addRouteReturnReceiptInfo(){
    
    	$params = $this->input();
    
    	$paramRule = [
    		'route_template_id' => 'number',
    		'user_id'=>'string',
    		'status'=>'number',
    		'route_return_receipt_info'=>'array'
    
    	];
    
    	$this->paramCheckRule($paramRule,$params);

    	$return_receipt = new RouteReturnReceipt();

    	$return_receipt_result = $return_receipt->addRouteReturnReceiptInfo($params);
    
    	$this->outPut($return_receipt_result);
    }  
    /**
     * 获取路线回执单
     */
    public function getRouteReturnReceipt(){
    	$params = $this->input();

    	
    	$return_receipt = new RouteReturnReceipt();
    	
    	$return_receipt_result = $return_receipt->getRouteReturnReceipt($params);
    	
    	$this->outPut($return_receipt_result);    	
    }
    /**
     * 修改路线回执单
     */
    public function updateRouteReturnReceiptByRouteReturnReceiptId(){
    	$params = $this->input();
    	
    	$paramRule = [
    		
    		'user_id'=>'string',
  
    		'route_return_receipt_id'=>'number'
    	
    	];
    	
    	$this->paramCheckRule($paramRule,$params);
    	
    	$return_receipt = new RouteReturnReceipt();
    	
    	$return_receipt_result = $return_receipt->updateRouteReturnReceiptByRouteReturnReceiptId($params);
    	
    	$this->outPut($return_receipt_result);
    	
    }
    /**
     * 添加路线模板资源
     */
    public function addRouteSource(){
    	$params = $this->input();
    	
    	$paramRule = [
    		'route_template_id' => 'number',
    		'supplier_type_id'=>'number',
			'source_id'=>'number',
    		'source_price'=>'number',
    		'source_count'=>'number',
    		'source_total_price'=>'number',
    		'user_id'=>'number',
    		'status'=>'number',
    	
    	];
    	
    	$this->paramCheckRule($paramRule,$params);
    	$route_source = new RouteSourceAllocation();
    	$route_source_result = $route_source->addRouteSourceAllocation($params);
    	$this->outPut($route_source_result);
    
    }
    
    /**
     * 修改路线资源配置根据资源配置ID
     */
    public function updateRouteSourceAllocationByRouteSourceAllocationId(){
    	$params = $this->input();
    	 
    	$paramRule = [
    		
    		'user_id'=>'number',   		
    		'route_source_allocation_id'=>'number'
   
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$route_source = new RouteSourceAllocation();
    	$route_source_result = $route_source->updateRouteSourceAllocationByRouteSourceAllocationId($params);
    	$this->outPut($route_source_result);

    } 

    /**
     * 获取路线模板资源根据资源ID
     */
    public function getRouteSourceAllocation(){
    	$params = $this->input();
    	$route_source = new routeSourceAllocation($params);
    	if(!empty($params['supplier_type_id'])){//如果有了供应商ID

    		$route_source_result = $route_source->getRouteSourceAllocation($params);
    	}else{
    		$params['supplier_type_id'] = 2;
    		$route_source_result['hotel'] = $route_source->getRouteSourceAllocation($params);
    		$params['supplier_type_id'] = 3;
    		$route_source_result['dining'] = $route_source->getRouteSourceAllocation($params); 
    		$params['supplier_type_id'] = 4;
    		$route_source_result['flight'] = $route_source->getRouteSourceAllocation($params);
    		$params['supplier_type_id'] = 5;
    		$route_source_result['cruise'] = $route_source->getRouteSourceAllocation($params);
    		$params['supplier_type_id'] = 6;
    		$route_source_result['visa'] = $route_source->getRouteSourceAllocation($params);
    		$params['supplier_type_id'] = 7;
    		$route_source_result['scenic_spot'] = $route_source->getRouteSourceAllocation($params);
    		$params['supplier_type_id'] = 8;
    		$route_source_result['vehicle'] = $route_source->getRouteSourceAllocation($params);
    		$params['supplier_type_id'] = 9;
    		$route_source_result['tour_guide'] = $route_source->getRouteSourceAllocation($params);
    		$params['supplier_type_id'] = 10;
    		$route_source_result['single_source'] = $route_source->getRouteSourceAllocation($params);
            $params['supplier_type_id'] = 11;
            $route_source_result['own_expense'] = $route_source->getRouteSourceAllocation($params);
    	}

    	
    	$this->outPut($route_source_result);

    }  
   	 
    /**
     * 添加团队产品
     * 韩
     */
    public function addTeamProduct(){
    	$params = $this->input();

    	$paramRule = [

    		'team_product_name'=>'string',
    		'route_type_id'=>'number',
            'use_company_id'=>'string',
    		'settlement_type'=>'number',
            'plan_custom_number'=>'number',
            'begin_time'=>'number',
    		'user_id'=>'number',
    		'status'=>'number'
    	];
    	
    	$this->paramCheckRule($paramRule,$params);
    	if(empty($params['use_company_id'])){
    		$params['use_company_id']='*';
    	}
    	if(empty($params['team_product_user_id'])){
    		$params['team_product_user_id'] = $params['user_id'];
    	}


    	$params['team_product_number'] = Help::getNumber(2);

        
    	$team_product = new TeamProduct();

    	$team_product_result = $team_product->addTeamProduct($params);

    	$this->outPut($team_product_result);
    	 
    }

    /**
     * 获取团队产品(基础信息)
     * 韩
     */
    public function getTeamProductBase(){
    
    	$params = $this->input();
    	$team_product = new TeamProduct();
    	
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $team_product->getTeamProductBase($params, true);
            $result = $team_product->getTeamProductBase($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];
            
            return $this->output($data);
        }
    	$team_product_result = $team_product->getTeamProductBase($params);

    	$this->outPut($team_product_result);
    }

	/**
	 * 获取团队产品
	 */
    public function getTeamProduct(){
    	
    	$params = $this->input();
    	$team_product = new TeamProduct();
    	 

    	$team_product_result = $team_product->getTeamProduct($params);
    	
    	$this->outPut($team_product_result);
    }
    

    /**
     * 获取团队产品(基础信息)-给其他控制器使用
     * 韩
     */
    public function getTeamProductBaseForController(){
    
    	$params = $this->input();
    	$team_product = new TeamProduct();
    
    	$team_product_result = $team_product->getTeamProductBase($params);
    
    	return $team_product_result;
    }
    /**
     * 获取团队产品(航班信息)
     * 韩
     */
    public function getTeamProductFlight(){

        $params = $this->input();
        $team_product_flight = new TeamProductFlight();

        $team_product_flight_result = $team_product_flight->getTeamProductFlight($params);

        $this->outPut($team_product_flight_result);
    }

    /**
     * 获取团队产品(行程信息)
     * 韩
     */
    public function getTeamProductJourney(){

        $params = $this->input();
        $team_product_journey = new TeamProductJourney();

        $team_product_journey_result = $team_product_journey->getTeamProductJourney($params);

        $this->outPut($team_product_journey_result);
    }

    /**
     * 获取团队产品(资源信息)
     * 韩
     */
    public function getTeamProductAllocation(){

        $params = $this->input();
        $team_product_allocation = new TeamProductAllocation();

        $team_product_allocation_result = $team_product_allocation->getTeamProductAllocation($params);
		for($i=0;$i<count($team_product_allocation_result);$i++){
			if($team_product_allocation_result[$i]['supplier_type_id']==2){
				$source_params = [
					'hotel_id'=>$team_product_allocation_result[$i]['source_id']	
				];
				$source_result = $this->_hotel->getHotel($source_params);
				$team_product_allocation_result[$i]['room_type'] = $source_result[0]['room_type'];
			}else if($team_product_allocation_result[$i]['supplier_type_id']==3){
				$source_params = [
					'dining_id'=>$team_product_allocation_result[$i]['source_id']
				];
				$source_result = $this->_dining->getDining($source_params);
				$team_product_allocation_result[$i]['standard_type'] = $source_result[0]['standard_type'];
			}else if($team_product_allocation_result[$i]['supplier_type_id']==4){
				$source_params = [
					'flight_id'=>$team_product_allocation_result[$i]['source_id']
				];
				$source_result = $this->_flight->getFlight($source_params);
				$team_product_allocation_result[$i]['shipping_space'] = $source_result[0]['shipping_space'];
			}else if($team_product_allocation_result[$i]['supplier_type_id']==5){
				$source_params = [
					'cruise_id'=>$team_product_allocation_result[$i]['source_id']
				];
				$source_result = $this->_cruise->getCruise($source_params);
				$team_product_allocation_result[$i]['room_name'] = $source_result[0]['room_name'];
			}else if($team_product_allocation_result[$i]['supplier_type_id']==6){
				$source_params = [
					'visa_id'=>$team_product_allocation_result[$i]['source_id']
				];
				$source_result = $this->_visa->getVisa($source_params);
				$team_product_allocation_result[$i]['file_url'] = $source_result[0]['file_url'];
			}else if($team_product_allocation_result[$i]['supplier_type_id']==7){

			}else if($team_product_allocation_result[$i]['supplier_type_id']==8){
				$source_params = [
					'vehicle_id'=>$team_product_allocation_result[$i]['source_id']
				];
				$source_result = $this->_vehicle->getVehicle($source_params);
				$team_product_allocation_result[$i]['vehicle_number'] = $source_result[0]['vehicle_number'];
				$team_product_allocation_result[$i]['load'] = $source_result[0]['load'];

			}else if($team_product_allocation_result[$i]['supplier_type_id']==9){
				$source_params = [
					'tour_guide_id'=>$team_product_allocation_result[$i]['source_id']
				];
				$source_result = $this->_tour_guide->getTourGuide($source_params);
				$team_product_allocation_result[$i]['guide_id_card'] = $source_result[0]['guide_id_card'];
				$team_product_allocation_result[$i]['phone'] = $source_result[0]['phone'];

				
			}else if($team_product_allocation_result[$i]['supplier_type_id']==10){
				
			}else if($team_product_allocation_result[$i]['supplier_type_id']==11){
				
			}
		}
        $this->outPut($team_product_allocation_result);
    }

    /**
     * 获取团队产品(产品报价)
     * 韩
     */
    public function getTeamProductPrice(){

        $params = $this->input();
        
        $paramRule = [
        	'team_product_settlement_type'=>'number',
   
        ];
       	  
        $this->paramCheckRule($paramRule,$params);

        if($params['team_product_settlement_type'] == 1){//一口价

            $team_product_once_price = new TeamProductOncePrice();
            $team_product_once_price_result = $team_product_once_price->getTeamProductOncePrice($params);

            $this->outPut($team_product_once_price_result);

        }else if($params['team_product_settlement_type'] == 2){//真实结算

            $team_product_price = new TeamProductAllocation();
            $team_product_price_result = $team_product_price->getTeamProductPrice($params);

            $this->outPut($team_product_price_result);
        	
        }
    }

    /**
     * 添加导游回执单上传文件
     * 韩
     */
    public function addTeamProductGuideReceiptFile(){

        $params = $this->input();
       
        $guide_receipt_file = new TeamProductTourGuideJounery();

        $guide_receipt_file_result = $guide_receipt_file->addTeamProductGuideReceiptFile($params);
        $this->outPut($guide_receipt_file_result);
    }

    /**
    * 获取导游回执单上传文件
    * 韩
    */
    public function getTeamProductTourGuideJounery(){

        $params = $this->input();
        

        $guide_receipt_file_result = $this->_team_product_tour_guide_jounery->getTeamProductGuideReceiptFile($params);
		
        $this->outPut($guide_receipt_file_result);
    }

    /**
     * 获取团队产品(回执单模版)
     * 韩
     */
    public function getTeamProductReturnReceipt(){

        $params = $this->input();
        $team_product_allocation = new TeamProductReturnReceipt();

        $team_product_allocation_result = $team_product_allocation->getTeamProductReturnReceipt($params);

        $this->outPut($team_product_allocation_result);
    }

    /**
     * 编辑团队产品根据团队产品id
     * 韩
     */
    public function updateTeamProductByTeamProductId(){
        $params = $this->input();
      
        $paramRule = [
            'team_product_id'=>'number',
            'user_id'=>'number'
        ];

        $this->paramCheckRule($paramRule,$params);
	
        $team_product = new TeamProduct();
        $team_product_result = $team_product->updateTeamProductByTeamProductId($params);
		
        $this->outPut($team_product_result);
    }

    /**
     * 编辑团队产品(基本信息)根据团队产品id
     * 韩
     */
    public function updateTeamProductBaseByTeamProductBaseId(){
    	
    	$params = $this->input();
    	$paramRule = [
			'team_product_id'=>'number',
    
    	];
		
    	$this->paramCheckRule($paramRule,$params);
    	$team_product = new TeamProduct();
        $team_product_result = $team_product->updateTeamProductBaseByTeamProductBaseId($params);
    	$this->outPut($team_product_result);
    }

    /**
     * 编辑团队产品(航班信息)根据团队航班id
     * 韩
     */
    public function updateTeamProductFlightByTeamProductFlightId(){

        $params = $this->input();
        $paramRule = [
//            'team_product_flight_id'=>'number',
            'user_id'=>'number'
        ];

        $this->paramCheckRule($paramRule,$params);
        $team_product_flight = new TeamProductFlight();
        $team_product_flight_result = $team_product_flight->updateTeamProductFlightByTeamProductFlightId($params);
        $this->outPut($team_product_flight_result);
    }

    /**
     * 编辑团队产品(行程信息)根据团队行程id
     * 韩
     */
    public function updateTeamProductJourneyByTeamProductJourneyId(){

        $params = $this->input();
        $paramRule = [
//            'team_product_journey_id'=>'number',
            'user_id'=>'number'
        ];

        $this->paramCheckRule($paramRule,$params);
        $team_product_journey = new TeamProductJourney();
        $team_product_journey_result = $team_product_journey->updateTeamProductJourneyByTeamProductJourneyId($params);
        $this->outPut($team_product_journey_result);
    }

    /**
     * 编辑团队产品(资源信息)根据团队资源id
     * 韩
     */
    public function updateTeamProductAllocationByTeamProductAllocationId(){

        $params = $this->input();
        $paramRule = [
//            'team_product_allocation_id'=>'number',
            'user_id'=>'number'
        ];

        $this->paramCheckRule($paramRule,$params);
        $team_product_allocation = new TeamProductAllocation();
        $team_product_allocation_result = $team_product_allocation->updateTeamProductAllocationByTeamProductAllocationId($params);
        $this->outPut($team_product_allocation_result);
    }

    /**
     * 编辑团队产品(回执单模版)根据团队资源id
     * 韩
     */
    public function updateTeamProductReturnReceiptByTeamProductReturnReceiptId(){

        $params = $this->input();
        $paramRule = [
            'user_id'=>'number'
        ];

        $this->paramCheckRule($paramRule,$params);
        $team_product_allocation = new TeamProductReturnReceipt();
        $team_product_allocation_result = $team_product_allocation->updateTeamProductReturnReceiptByTeamProductReturnReceiptId($params);
        $this->outPut($team_product_allocation_result);
    }

    /**
     * 编辑团队产品(一口价)根据团队资源id
     * 韩
     */
    public function updateTeamProductOncePriceByTeamProductOncePriceId(){

        $params = $this->input();
        $paramRule = [
            'team_product_once_price_id',
           
        ];

        $this->paramCheckRule($paramRule,$params);
        $team_product_allocation = new TeamProductReturnReceipt();
        $team_product_allocation_result = $team_product_allocation->updateTeamProductOncePriceByTeamProductOncePriceId($params);
        $this->outPut($team_product_allocation_result);
    }

    /**
     * 添加团队产品时刻表
     * 胡
     */
    public function addTeamProductSchedule(){
    
    	$params = $this->input();
    
    	$paramRule = [
    
    		'team_product_id' => 'number',
    		'user_id'=>'number',
    		'status'=>'number',
    		'team_product_schedule_info'=>'array'
    
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    	$team_product_schedule = new TeamProductSchedule();
    

    
    	if($route_template_result>0 && is_numeric($route_template_result)){
    		$this->outPut($route_template_result);
    	}else{
    		$this->outPut('',$route_template_result);
    	}
    
    }
    
    /**
     * 添加团队产品应付供应商
     */
    public function addTeamProductCopeSupplier(){
    	$params = $this->input();
    	
    	$paramRule = [
    	
    		'team_product_id' => 'string',
// 			'cope_info'=>'array',
//     		'travel_agency_reimbursement_cope_info'=>'array',
//     		'team_product_other_info'=>'array'	
    	
    	];    	
    	$this->paramCheckRule($paramRule,$params);
    	//首先添加团队应付
    	$team_product_id = $params['team_product_id'];
    	//$team_product_number = $params['team_product_number'];
    	
    	//判断是否有地接报账
    	
    	//首先把地接报账的状态设定 为0
    	$cope_status = [
    		'team_product_id'=>$team_product_id,
    		'fee_type_type'=>102
    	];
    	$this->_cope->updateStatusByData($params);
    	
    
    	
    	$travel_agency_reimbursement_cope_info = $params['travel_agency_reimbursement_cope_info'];
    	//地接报账编号不存在则新增
    	if(!empty($params['travel_agency_reimbursement_number'])){//存在 走修改逻辑
    		$travel_agency_reimbursement_number = $params['travel_agency_reimbursement_number'];
    		$travel_agency_reimbursement_params = [
    			'team_product_id'=>$team_product_id,
    			'now_user_id'=>$params['now_user_id'],
    			'user_company_id'=>$params['user_company_id'],
				'travel_number'=>$travel_agency_reimbursement_number,    				
    			'cope'=>$travel_agency_reimbursement_cope_info
    		];
   			
			    		
    		$this->_travel_agency_reimbursement->updateTravelAgencyReimbursement($travel_agency_reimbursement_params);
    	}else{//不存在 走新增
    		
    		$travel_agency_reimbursement_params = [
    			'team_product_id'=>$team_product_id,
    			'now_user_id'=>$params['now_user_id'],
    			'user_company_id'=>$params['user_company_id'],
    			'travel_number'=>Help::getNumber(9,2),
    			'cope'=>$travel_agency_reimbursement_cope_info
    		];
		
    
    		$this->_travel_agency_reimbursement->addTravelAgencyReimbursement($travel_agency_reimbursement_params);
    	}
    	

    	//判断是否会有团队应付
    	$cope_info = $params['cope_info'];
    	//首先把团队应付的状态 设定 为0
    	$cope_status = [
    		'team_product_id'=>$team_product_id,
    		'fee_type_type'=>101	
    	];

    	$this->_cope->updateStatusByData($cope_status);

    	//再把订单中的自定义的团队产品状态修改为0
    	$company_order_product_diy_params = [
    		'status'=>0,
    		'team_product_id'=>$team_product_id	
    	];
    	$this->_company_order_product_diy->updateStatueByTeamProductId($company_order_product_diy_params);
    	
    	for($i=0;$i<count($cope_info);$i++){
    		if($cope_info[$i]['source_type_id']==2){
    			$fee_type_code=1;
    		}else if($cope_info[$i]['source_type_id']==3){
    			$fee_type_code=2;
    		}else if($cope_info[$i]['source_type_id']==4){
    			$fee_type_code=3;
    		}else if($cope_info[$i]['source_type_id']==5){
    			$fee_type_code=4;
    		}else if($cope_info[$i]['source_type_id']==6){
    			$fee_type_code=5;
    		}else if($cope_info[$i]['source_type_id']==7){
    			$fee_type_code=6;
    		}else if($cope_info[$i]['source_type_id']==8){
    			$fee_type_code=7;
    		}else if($cope_info[$i]['source_type_id']==9){
    			$fee_type_code=8;
    		}else if($cope_info[$i]['source_type_id']==10){
    			$fee_type_code=9;
    		}else if($cope_info[$i]['source_type_id']==11){
    			$fee_type_code=10;
    		}else if($cope_info[$i]['source_type_id']==12){
    			$fee_type_code=11;
    		}
    		$cope_add_data = [];
    		if(!empty($cope_info[$i]['cope_number'])){//走修改逻辑
    			$cope_add_data['source_type_id'] = $cope_info[$i]['source_type_id'];

    			$cope_add_data['receivable_object_id'] = $cope_info[$i]['supplier_id'];
    			$cope_add_data['product_name'] = $cope_info[$i]['product_name'];
   
    			$cope_add_data['cope_currency_id'] = $cope_info[$i]['cope_currency_id'];
    			$cope_add_data['price'] = $cope_info[$i]['price'];
    			$cope_add_data['unit'] = $cope_info[$i]['unit'];
    			$cope_add_data['cope_money'] = $cope_info[$i]['cope_money'];

    			$cope_add_data['now_user_id'] = $params['now_user_id'];
    			$cope_add_data['fee_type_code'] = $fee_type_code;
    			$cope_add_data['cope_number'] = $cope_info[$i]['cope_number'];
    			$cope_add_data['status'] = 1;
    			$result = $this->_cope->updateCopeByCopeNumber($cope_add_data);
    			
    			//开始判断是否在公司订单自定义里面有数据的话要更新
    			
    			$company_order_product_diy_params = [
    				'supplier_id'=>$cope_info[$i]['supplier_id'],
    				'diy_cost'=>$cope_info[$i]['cope_money'],
    				'cost_currency_id'=>$cope_info[$i]['cope_currency_id'],
    				'status'=>1,
    				'cope_number'=>$cope_info[$i]['cope_number']	
    			];
    			
    			$this->_company_order_product_diy->updateCompanyOrderProduct($company_order_product_diy_params);
    			
    			
    			
    			
    		}else{
    			
    			$cope_add_data['source_type_id'] = $cope_info[$i]['source_type_id'];
    			$cope_add_data['receivable_object_type'] = 2;
    			$cope_add_data['receivable_object_id'] = $cope_info[$i]['supplier_id'];
    			$cope_add_data['product_name'] = $cope_info[$i]['product_name'];
    			$cope_add_data['team_product_id'] = $team_product_id;
    			$cope_add_data['cope_currency_id'] = $cope_info[$i]['cope_currency_id'];
    			$cope_add_data['price'] = $cope_info[$i]['price'];
    			$cope_add_data['unit'] = $cope_info[$i]['unit'];
    			$cope_add_data['cope_money'] = $cope_info[$i]['cope_money'];
    			
    			$cope_add_data['company_id'] = $params['user_company_id'];
    			$cope_add_data['now_user_id'] = $params['now_user_id'];
    			$cope_add_data['fee_type_code'] = $fee_type_code;
    			$cope_add_data['fee_type_type'] = 101;
    			$cope_add_data['is_auto'] = 1;
    			$result = $this->_cope->addCope($cope_add_data);
    		}
    	}
    	
    	//判断是否会有其他应付
    	$team_product_other_info = $params['team_product_other_info'];
    	//首先把团队应付的状态 设定 为0
    	$cope_status = [
    		'team_product_id'=>$team_product_id,
    		'fee_type_type'=>103
    	];
    	$this->_cope->updateStatusByData($cope_status);
    	for($i=0;$i<count($team_product_other_info);$i++){
    		if($team_product_other_info[$i]['source_type_id']==2){
    			$fee_type_code=23;
    		}else if($team_product_other_info[$i]['source_type_id']==3){
    			$fee_type_code=24;
    		}else if($team_product_other_info[$i]['source_type_id']==4){
    			$fee_type_code=25;
    		}else if($team_product_other_info[$i]['source_type_id']==5){
    			$fee_type_code=26;
    		}else if($team_product_other_info[$i]['source_type_id']==6){
    			$fee_type_code=27;
    		}else if($team_product_other_info[$i]['source_type_id']==7){
    			$fee_type_code=28;
    		}else if($team_product_other_info[$i]['source_type_id']==8){
    			$fee_type_code=29;
    		}else if($team_product_other_info[$i]['source_type_id']==9){
    			$fee_type_code=30;
    		}else if($team_product_other_info[$i]['source_type_id']==10){
    			$fee_type_code=31;
    		}else if($team_product_other_info[$i]['source_type_id']==11){
    			$fee_type_code=32;
    		}else if($team_product_other_info[$i]['source_type_id']==12){
    			$fee_type_code=33;
    		}
    		$cope_add_data = [];
    		if(!empty($team_product_other_info[$i]['cope_number'])){//走修改逻辑
    			$cope_add_data['source_type_id'] = $team_product_other_info[$i]['source_type_id'];
    	
    			$cope_add_data['receivable_object_id'] = $team_product_other_info[$i]['supplier_id'];
    			$cope_add_data['product_name'] = $team_product_other_info[$i]['product_name'];
    			 
    			$cope_add_data['cope_currency_id'] = $team_product_other_info[$i]['cope_currency_id'];
    			$cope_add_data['price'] = $team_product_other_info[$i]['price'];
    			$cope_add_data['unit'] = $team_product_other_info[$i]['unit'];
    			$cope_add_data['cope_money'] = $team_product_other_info[$i]['cope_money'];
    	
    			$cope_add_data['now_user_id'] = $params['now_user_id'];
    			$cope_add_data['fee_type_code'] = $fee_type_code;
    			$cope_add_data['cope_number'] = $team_product_other_info[$i]['cope_number'];
    			$cope_add_data['status'] = 1;
    			$result = $this->_cope->updateCopeByCopeNumber($cope_add_data);
    			 
    		}else{
    			 
    			$cope_add_data['source_type_id'] = $team_product_other_info[$i]['source_type_id'];
    			$cope_add_data['receivable_object_type'] = 2;
    			$cope_add_data['receivable_object_id'] = $team_product_other_info[$i]['supplier_id'];
    			$cope_add_data['product_name'] = $team_product_other_info[$i]['product_name'];
    			$cope_add_data['team_product_id'] = $team_product_id;
    			$cope_add_data['cope_currency_id'] = $team_product_other_info[$i]['cope_currency_id'];
    			$cope_add_data['price'] = $team_product_other_info[$i]['price'];
    			$cope_add_data['unit'] = $team_product_other_info[$i]['unit'];
    			$cope_add_data['cope_money'] = $team_product_other_info[$i]['cope_money'];
    			 
    			$cope_add_data['company_id'] = $params['user_company_id'];
    			$cope_add_data['now_user_id'] = $params['now_user_id'];
    			$cope_add_data['fee_type_code'] = $fee_type_code;
    			$cope_add_data['fee_type_type'] = 103;
    			$cope_add_data['is_auto'] = 1;
    			$result = $this->_cope->addCope($cope_add_data);
    		}
    	}    	
    	
//     	$cope_result = $cope->addCopeByTeamProduct($params);
//     	//添加地接报账支出
//     	$travel_agency_reimbursement = new TravelAgencyReimbursement();
//     	$travel_agency_reimbursement_result = $travel_agency_reimbursement->updateTravelAgencyReimbursement($params);
    	
//     	//添加其他成本
//     	$team_product_other_cope = new TeamProductOtherCope();
//     	$team_product_other_cope_result = $team_product_other_cope->addTeamProductOtherCope($params);
    	$this->outPut(1);
    }

    //获取团队产品应付供应商
    public function getTeamProductCopeSupplier(){
    	$params = $this->input();
   
    	$paramRule = [
    			 
    	'team_product_id' => 'string',
    	// 			'cope_info'=>'array',
    	//     		'travel_agency_reimbursement_cope_info'=>'array',
    	//     		'team_product_other_info'=>'array'
    			 
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$params['status'] = 1;
    	$params['fee_type_type'] = 101;
    	$params['company_id'] = $params['user_company_id'];
    	$cope = new Cope();
    	//首先获取团队应付
    
    	$cope_info= $cope->getCope($params);
    	
        for($i=0;$i<count($cope_info);$i++){

        	$supplier_params['supplier_id'] = $cope_info[$i]['receivable_object_id'];
        	$supplier_result = $this->_supplier->getSupplier($supplier_params);
        	$cope_info[$i]['receivable_object_name'] = $supplier_result[0]['supplier_name'];
   
        	$get_fee_type_params = [
        		'fee_type_code'=>$cope_info[$i]['fee_type_code']
        	];
        	 
        	$fee_type_name = Help::getFeeType($get_fee_type_params);
        	$cope_info[$i]['fee_type_name'] = $fee_type_name['fee_type_name'];
        	
        	$cope_info[$i]['balance_money'] = $cope_info[$i]['cope_money']-$cope_info[$i]['true_receipt'];
        }
        
 
    	//首先地接报账
    	$params['fee_type_type'] = 102;
    	$travel_agency_reimbursement_cope_info = $cope->getCope($params);  
    	for($i=0;$i<count($travel_agency_reimbursement_cope_info);$i++){
    	
    		$supplier_params['supplier_id'] = $travel_agency_reimbursement_cope_info[$i]['receivable_object_id'];
    		$supplier_result = $this->_supplier->getSupplier($supplier_params);
    		$travel_agency_reimbursement_cope_info[$i]['receivable_object_name'] = $supplier_result[0]['supplier_name'];
    		 
    		$get_fee_type_params = [
    			'fee_type_code'=>$travel_agency_reimbursement_cope_info[$i]['fee_type_code']
    		];
    	
    		$fee_type_name = Help::getFeeType($get_fee_type_params);
    		$travel_agency_reimbursement_cope_info[$i]['fee_type_name'] = $fee_type_name['fee_type_name'];
    		$travel_agency_reimbursement_cope_info[$i]['balance_money'] = $travel_agency_reimbursement_cope_info[$i]['cope_money']-$travel_agency_reimbursement_cope_info[$i]['true_receipt'];
    	}
    	
    	
    	
    	//首先其他
    	$params['fee_type_type'] = 103;
    	$team_product_other_info = $cope->getCope($params);
    	for($i=0;$i<count($team_product_other_info);$i++){
    		 
    		$supplier_params['supplier_id'] = $team_product_other_info[$i]['receivable_object_id'];
    		$supplier_result = $this->_supplier->getSupplier($supplier_params);
    		$team_product_other_info[$i]['receivable_object_name'] = $supplier_result[0]['supplier_name'];
    		 
    		$get_fee_type_params = [
    			'fee_type_code'=>$team_product_other_info[$i]['fee_type_code']
    		];
    		 
    		$fee_type_name = Help::getFeeType($get_fee_type_params);
    		$team_product_other_info[$i]['fee_type_name'] = $fee_type_name['fee_type_name'];
    		$team_product_other_info[$i]['balance_money'] = $team_product_other_info[$i]['cope_money']-$team_product_other_info[$i]['true_receipt'];
    	}
    	$data =[ 
    		'cope_info'=>$cope_info,
    		'travel_agency_reimbursement_cope_info'=>$travel_agency_reimbursement_cope_info,
    		'team_product_other_info'=>$team_product_other_info
    	];
    	
    	
    	$this->outPut($data);
    	
    }
    
    //获取团队产品应收供应商
    
    public function getTeamProductReceivableSupplier(){
    	$params = $this->input();  	
    	$paramRule = [    			 
    		'team_product_id' => 'string',      			 
    	];

    	$this->paramCheckRule($paramRule,$params);

    	$receivable_params = [
    		'team_product_id'=>$params['team_product_id'],
    		'status'=>1,
    		'fee_type_type'=>208
    	];
    
    	$receivable_result = $this->_receivable->getReceivable($receivable_params);
    	
		$receivable_result= $this->_receivable_service->getReceivableInfo($receivable_result);
		
		
		
    	$this->outPut($receivable_result);
    	 
    }
    
    //添加团队产品应收供应商(进入审批)
    public function addTeamProductReceivableSupplier(){
    	$params = $this->input();
    	
    	$paramRule = [
    	
    		'team_product_id' => 'string',

    	
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$team_product_id = $params['team_product_id'];
    	$receivable_supplier_array = $params['receivable_supplier_array'];
    	

    	
//     	for($i=0;$i<count($receivable_supplier_array);$i++){ //需要知道供应商名称和货币名称
//     		$supplier_params =[
//     			'supplier_id'=>$receivable_supplier_array[$i]['payment_object_id']
//     		];
//     		$supplier_result = $this->_supplier->getSupplier($supplier_params);
//     		$currency_params = [
//     			'currency_id'=>	$receivable_supplier_array[$i]['receivable_currency_id']
//     		];
//     		$currency_result = $this->_currency->getCurrency($currency_params);
    		
//     		$receivable_supplier_array[$i]['currency_unit'] = $currency_result[0]['unit'];
//     		$receivable_supplier_array[$i]['supplier_name'] = $supplier_result[0]['supplier_name'];
//     	}
    	
//     	$params['receivable_supplier_array'] = $receivable_supplier_array;
    	
    	
//     	$json_data = json_encode($params);
//     	$statement_approve_params = [
//     		'json_data'=>$json_data,
//     		'team_product_id'=>$team_product_id,
//     		'user_company_id'=>$params['user_company_id'],
//     		'type'=>1,
//     		'now_user_id'=>$params['now_user_id']	
//     	];
//     	//error_log(print_r($receivable_supplier_array,1));

//     	//$result = $this->_statement_approve->addStatementApprove($statement_approve_params);
    
//     	$result = $this->_statement_approve->addStatementApprove($statement_approve_params);
//     	$this->outPut($result);
//   		exit();
    	//$this->outPut($result);
    	//先隐藏数据 

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
		$this->outPut(1);
    }
    
    //获取团队产品应付分公司
    public function getTeamProductCopeCompany(){
    	$params = $this->input();
    
    	$paramRule = [
    
    		'team_product_id' => 'string',

    
    	];
    	$this->paramCheckRule($paramRule,$params);   	
    	$receivable_customer = new ReceivableCustomer();
    	$params = [
    				
    		'team_product_id'=>$params['team_product_id'],
    		'status'=>1,
    		'fee_type_type'=>109,
    	];
    
    	$cope_result = $this->_cope->getCope($params);
		$data = $cope_result;
		//再获取游客信息
    	for($i=0;$i<count($data);$i++){
    		//通过public编号获取应收编号
    		$public_params = [
    			'public_number'=>$data[$i]['public_number']	
    		];
    		$receivable_result = $this->_receivable->getReceivable($public_params);
    	
    		$params_customer = [
    				'status'=>1,
    				'receivable_number'=> $receivable_result[0]['receivable_number']
    		];
    		
    		$data[$i]['customer_info'] = $receivable_customer->getReceivableCustomer($params_customer);
    	}	
    	$data = [
    		'cope_info'=>$data,

    	];
    
    	$this->outPut($data);
    
    }
    
    
	//获取团队产品应收直客/代理
	public function getTeamProductReceivableCustomerAndDistributor(){
    	$params = $this->input();
    	 
    	$paramRule = [
    			 
    		'team_product_id' => 'string',
	 
    	];
    	$this->paramCheckRule($paramRule,$params);		
    	$receivable_customer = new ReceivableCustomer();
    	$team_product_id = $params['team_product_id'];
		
		

		
		//获取资源应收
		$team_product_source_params = [
			'team_product_id'=>$team_product_id,
			'company_order_number'=>$params['company_order_number'],
			'status'=>1,
			'is_type'=>2	
			
		];
	
		$team_product_source_result = $this->_company_order_product_source->getCompanyOrderProductSource($team_product_source_params);

		for($i=0;$i<count($team_product_source_result);$i++){
			//首先线通过数据去查应收分公司的应收表
			if(!empty($team_product_source_result[$i]['receivable_number'])){
				$receivable_params=[
						'receivable_number'=>$team_product_source_result[$i]['receivable_number']
				];
				$receivable_result = $this->_receivable->getReceivable($receivable_params);
				for($j=0;$j<count($receivable_result);$j++){
			
						
					$params_customer = [
							'status'=>1,
							'receivable_number'=>$receivable_result[$j]['receivable_number']
					];
						
					$receivable_result[$j]['customer_info'] = $receivable_customer->getReceivableCustomer($params_customer);
						
						
				}
				$data[] = $receivable_result[0];
			}

		}
	
// 		for($i=0;$i<count($data);$i++){

			
// 			$params_customer = [
// 				'status'=>1,
// 				'receivable_number'=>$data[$i]['receivable_number']
// 			];
			
// 			$data[$i]['customer_info'] = $receivable_customer->getReceivableCustomer($params_customer);
			
// 		}
		

		$data = [
			'receivable_info'=>	$data,
			//'travel_agency_reimbursement_receivable_info'=>	$travel_result,
			'team_product_other_info'=>$other_result
		];

		$this->outPut($data);

	}
	//获取团队产品应收分公司
	public function getTeamProductReceivableCompany(){
		$params = $this->input();
	
		$paramRule = [
	
				'team_product_id' => 'string',
				// 			'cope_info'=>'array',
				//     'travel_agency_reimbursement_cope_info'=>'array',
				//     'team_product_other_info'=>'array'
	
		];
		$this->paramCheckRule($paramRule,$params);
		$receivable_customer = new ReceivableCustomer();
		$team_product_id = $params['team_product_id'];
	
		$receivble = new Receivable();
		//首先获取团队应收
// 		$team_product_params = [
// 				'team_product_id'=>$team_product_id,
// 				'company_order_number'=>$params['company_order_number'],
// 				'settlement_type'=>1,
// 				'is_type'=>1,
// 				'status'=>1,
// 				'company_order_status'=>1
// 		];
// 		$team_product_result = $this->_company_order_product_team->getCompanyOrderProductTeam($team_product_params);
		$receivable_params =[
			'team_product_id'=>$team_product_id,
			'status'=>1,
			'payment_object_type'=>1,
			'company_id'=>$params['user_company_id']	
		];
		$data = $receivble->getReceivable($receivable_params);
// 		$data = [];
// 		for($i=0;$i<count($team_product_result);$i++){
// 			//首先线通过数据去查应收分公司的应收表
// 			$cope_params=[
// 					'cope_number'=>$team_product_result[$i]['cope_number']
// 			];
// 			$receivable_result = $this->_finaces_service->getReceivableByCopeNumber($cope_params);
// 			for($j=0;$j<count($receivable_result);$j++){
// 				$receivable_result[$j]['source_type_id'] = 1;
// 			}
// 			$data[] = $receivable_result[0];
// 		}
	
// 		//获取资源应收
// 		$team_product_source_params = [
// 				'team_product_id'=>$team_product_id,
// 				'company_order_number'=>$params['company_order_number'],
// 				'status'=>1,
// 				'is_type'=>1,
// 				'company_order_status'=>1,
// 				//'now_is_own_source_by_branch_product'=>1//代表是自己的资源
// 				'not_is_own_source_by_branch_product'=>1//代表是自己的资源
// 		];
		
		
	
// 		$team_product_source_result = $this->_company_order_product_source->getCompanyOrderProductSource($team_product_source_params);
	
// 		for($i=0;$i<count($team_product_source_result);$i++){
// 			//首先线通过数据去查应收分公司的应收表
// 			$cope_params=[
// 					'cope_number'=>$team_product_source_result[$i]['cope_number']
// 			];
// 			$receivable_result = $this->_finaces_service->getReceivableByCopeNumber($cope_params);
	
// 			$data[] = $receivable_result[0];
		//}
	
		for($i=0;$i<count($data);$i++){
	
				
			$params_customer = [
					'status'=>1,
					'receivable_number'=>$data[$i]['receivable_number']
			];
				
			$data[$i]['customer_info'] = $receivable_customer->getReceivableCustomer($params_customer);
				
		}
	
		// 		$params = [
		// 			'team_product_id'=>$params['team_product_id'],
		// 			'status'=>1,
		// 			'fee_type_type'=>201,
		// 			'order_number'=>$params['company_order_number']
		// 		];
	
		// 		$receivable_result = $receivable->getReceivable($params);
	
		// 		for($i=0;$i<count($receivable_result);$i++){
		// 			$params_customer = [
		// 				'status'=>1,
		// 				'receivable_number'=>$receivable_result[$i]['receivable_number']
		// 			];
		// 			$receivable_result[$i]['customer_info'] = $receivable_customer->getReceivableCustomer($params_customer);
		// 			$receivable_result[$i]['balance_money'] = $receivable_result[$i]['receivable_money']-$receivable_result[$i]['true_receipt'];
		// 		}
	
	
		// 	    //首先地接报账
		// 	    //通过团队产品编号查询地接 报账NUMBER
		// 	    $travel_agency_reimbursement  =  new TravelAgencyReimbursement();
	
		//     	//$params['resource_type'] = 3;
		//     	//$data['travel_agency_reimbursement_cope_info'] = $cope->getCope($params);
		// 		$travel_agency_reimbursement_result = $travel_agency_reimbursement->getTravelAgencyReimbursement($params);
		// 		$travel_agency_reimbursement_number = $travel_agency_reimbursement_result[0]['travel_agency_reimbursement_number'];
	
		// 		//假如有地接报账
	
		// 		$params = [
	
		// 		'resource_type'=>3,
		// 		'status'=>1,
		// 		'team_product_number'=>$params['team_product_number'],
	
		// 		];
	
		// 		$travel_result = $receivable->getReceivable($params);
		// 		for($i=0;$i<count($travel_result);$i++){
		// 			$params_customer = [
		// 				'status'=>1,
		// 				'receivable_number'=>$travel_result[$i]['receivable_number']
		// 			];
		// 			$travel_result[$i]['customer_info'] = $receivable_customer->getReceivableCustomer($params_customer);
		// 		}
		//其他应收
		// 		$params = [
			
		// 			'team_product_id'=>$params['team_product_id'],
		// 			'status'=>1,
		// 			'fee_type_type'=>203,
	
	
		// 		];
		// 		$other_result = $receivable->getReceivable($params);
		// 		for($i=0;$i<count($other_result);$i++){
	
		// 			$params_customer = [
		// 					'status'=>1,
		// 					'receivable_number'=>$other_result[$i]['receivable_number']
		// 			];
		// 			$other_result[$i]['customer_info'] = $receivable_customer->getReceivableCustomer($params_customer);
		// 			$other_result[$i]['balance_money'] = $other_result[$i]['receivable_money']-$other_result[$i]['true_receipt'];
		// 		}
		
		
		
		
		$data1 = [
		'receivable_info'=>	$data,
		//'travel_agency_reimbursement_receivable_info'=>	$travel_result,
		'team_product_other_info'=>$other_result
		];
		
		
// 		//首先获取团队应收
// 		$team_product_params = [
// 				'team_product_id'=>$team_product_id,
				
// 				'is_type'=>2,
// 				'status'=>1
// 		];
		
	
// 		$team_product_result = $this->_company_order_product_team->getCompanyOrderProductTeam($team_product_params);
		
// 		$data = [];
// 		for($i=0;$i<count($team_product_result);$i++){
// 			//首先线通过数据去查应收分公司的应收表
// 			$receivable_params=[
// 					'receivable_number'=>$team_product_result[$i]['receivable_number']
// 			];
// 			$receivable_result = $this->_receivable->getReceivable($receivable_params);
// 			for($j=0;$j<count($receivable_result);$j++){
// 				$receivable_result[$j]['source_type_id'] = 1;
// 			}
// 			$data[] = $receivable_result[0];
// 		}
		
		
		
// 		//获取资源应收
// 		$team_product_source_params = [
// 				'team_product_id'=>$team_product_id,
// 				'company_order_number'=>$params['company_order_number'],
// 				'status'=>1,
// 				'supplier_type_id'=>11
// 		];
// 		$team_product_source_result = $this->_company_order_product_source->getCompanyOrderProductSource($team_product_source_params);
		
// 		for($i=0;$i<count($team_product_source_result);$i++){
// 			//首先线通过数据去查应收分公司的应收表
		
// 			$receivable_params=[
// 					'receivable_number'=>$team_product_source_result[$i]['receivable_number']
// 			];
// 			$receivable_result = $this->_receivable->getReceivable($receivable_params);
				
// 			$data[] = $receivable_result[0];
// 		}
// 		$data1['receivable_customer_distributor_info'] = $data;
		$this->outPut($data1);
	
	}	
	/**
	 * 添加团队产品应付分公司
	 */
	public function addTeamProductCopeCompany(){
		$params = $this->input();
			
		$paramRule = [
					
			'team_product_id' => 'string',
				// 			'receivable_info'=>'array',
				//     		'travel_agency_reimbursement_cope_info'=>'array',
				//     		'team_product_other_info'=>'array'
					
		];
		$this->paramCheckRule($paramRule,$params);
				
		$team_product_cope = $params['cope_info_array'];
		$team_product_number = $params['team_product_number'];
		$team_product_id = $params['team_product_id'];
		//首先先把应收应付状态变成0
		
		$cope_status = [
			'team_product_id'=>$team_product_id,
			'fee_type_type'=>109,
					
		];
		$this->_cope->updateStatusByData($cope_status);

		$receivable_status = [
			'team_product_id'=>$team_product_id,
			'fee_type_type'=>209,
					
		];
		$this->_receivable->updateStatusByData($receivable_status);
		//首先添加
		for($i=0;$i<count($team_product_cope);$i++){
			$team_product_cope_params = [];
		
			$team_product_cope_params = [
				'product_name' => $team_product_cope[$i]['product_name'],
				'order_number'=>$team_product_cope[$i]['order_number'],
				'receivable_object_type'=>1,
				'receivable_object_id'=>$team_product_cope[$i]['payment_object_id'],
				'company_order_customer_id'=>$team_product_cope[$i]['company_order_customer_id'],
				'cope_currency_id'=>$team_product_cope[$i]['cope_currency_id'],
				'cope_money'=>$team_product_cope[$i]['cope_money'],
				'fee_type_type'=>109,
				'now_user_id'=>$params['now_user_id'],
				'company_id'=>$params['user_company_id'],
				'is_auto'=>1,
				'team_product_id'=>$team_product_id,
				'status'=>1	
			];
			if($team_product_cope[$i]['source_type_id']==1){
				$team_product_cope_params['fee_type_code']=316;
			}else if($team_product_cope[$i]['source_type_id']==2){
				$team_product_cope_params['fee_type_code']=317;
				$team_product_cope_params['source_type_id']=2;
			}else if($team_product_cope[$i]['source_type_id']==3){
				$team_product_cope_params['fee_type_code']=318;
				$team_product_cope_params['source_type_id']=3;
			}else if($team_product_cope[$i]['source_type_id']==4){
				$team_product_cope_params['fee_type_code']=319;
				$team_product_cope_params['source_type_id']=4;
			}else if($team_product_cope[$i]['source_type_id']==5){
				$team_product_cope_params['fee_type_code']=320;
				$team_product_cope_params['source_type_id']=5;
			}else if($team_product_cope[$i]['source_type_id']==6){
				$team_product_cope_params['fee_type_code']=321;
				$team_product_cope_params['source_type_id']=6;
			}else if($team_product_cope[$i]['source_type_id']==7){
				$team_product_cope_params['fee_type_code']=322;
				$team_product_cope_params['source_type_id']=7;
			}else if($team_product_cope[$i]['source_type_id']==8){
				$team_product_cope_params['fee_type_code']=323;
				$team_product_cope_params['source_type_id']=8;
			}else if($team_product_cope[$i]['source_type_id']==9){
				$team_product_cope_params['fee_type_code']=324;
				$team_product_cope_params['source_type_id']=9;
			}else if($team_product_cope[$i]['source_type_id']==10){
				$team_product_cope_params['fee_type_code']=325;
				$team_product_cope_params['source_type_id']=10;
			}else if($team_product_cope[$i]['source_type_id']==11){
				$team_product_cope_params['fee_type_code']=326;
				$team_product_cope_params['source_type_id']=11;
			}else if($team_product_cope[$i]['source_type_id']==12){
				$team_product_cope_params['fee_type_code']=327;
				$team_product_cope_params['source_type_id']=12;
			}
			if(!empty($team_product_cope[$i]['cope_number'])){//有就修改没就新增
		
				$team_product_cope_params['cope_number'] = $team_product_cope[$i]['cope_number'];
		
				
				$this->_cope->updateCopeByCopeNumber($team_product_cope_params);
			}else{
		
				$this->_cope->addCope($team_product_cope_params);
			}
		
				
				
				
		}
		
		$this->outPut(1);
		
	}
	/**
	 * 添加团队产品应收分公司
	 */
	public function addTeamProductReceivableCompany(){
		$params = $this->input();
	
		$paramRule = [
				 
			'team_product_id' => 'string',
		 	'receivable_info'=>'array',

		//     	'travel_agency_reimbursement_cope_info'=>'array',
		//     	'team_product_other_info'=>'array'
				 
		];
		$this->paramCheckRule($paramRule,$params);
		

		$team_product_receivable = $params['receivable_info'];
		$team_product_number = $params['team_product_number'];
		$team_product_id = $params['team_product_id'];
		$team_product_other_info = $params['team_product_other_info'];
		
		//$team_product
		
		
// 		//首先先把应收应付状态变成0
		
		$receivable_status = [
			'team_product_id'=>$team_product_id,
			'fee_type_type'=>201,
			
		];
		$this->_receivable->updateStatusByData($receivable_status);
		$cope_status = [
			'team_product_id'=>$team_product_id,
			'fee_type_type'=>104,
			'receivable_object_type'=>1	
	
		];
		$this->_cope->updateStatusByData($cope_status);
			
		$team_params=[
			'status'=>0,
			'team_product_id'=>$team_product_id,
			'settlement_type'=>1	
		];
		$this->_company_order_product_team->updateCompanyOrderTeamStatus($team_params);
		
		$source_params=[
			'status'=>0,
			'team_product_id'=>$team_product_id
		];
		$this->_company_order_product_source->updateCompanyOrderSourceStatus($source_params);
		
		
		
		//首先添加团队产品应收
		for($i=0;$i<count($team_product_receivable);$i++){
			
			
			
			$team_product_receivable_params = [];
			
			$team_product_receivable_params = [
				'product_name' => $team_product_receivable[$i]['product_name'],
				'order_number'=>$team_product_receivable[$i]['order_number'],
				'payment_object_type'=>1,
				'payment_object_id'=>$team_product_receivable[$i]['payment_object_id'],
				'company_order_customer_id'=>$team_product_receivable[$i]['company_order_customer_id'],
				'team_product_id'=>$team_product_id,
				'receivable_currency_id'=>$team_product_receivable[$i]['receivable_currency_id'],
				'receivable_money'=>$team_product_receivable[$i]['receivable_money'],
				'fee_type_type'=>201,
				'now_user_id'=>$params['now_user_id'],
				'company_id'=>$params['user_company_id'],
				'is_auto'=>1,
				'team_product_id'=>$team_product_id	
			];
			if($team_product_receivable[$i]['source_type_id']==1){
				$team_product_receivable_params['fee_type_code']=34;
			}else if($team_product_receivable[$i]['source_type_id']==2){
				$team_product_receivable_params['fee_type_code']=35;
				$team_product_receivable_params['source_type_id']=2;
			}else if($team_product_receivable[$i]['source_type_id']==3){
				$team_product_receivable_params['fee_type_code']=36;
				$team_product_receivable_params['source_type_id']=3;
			}else if($team_product_receivable[$i]['source_type_id']==4){
				$team_product_receivable_params['fee_type_code']=37;
				$team_product_receivable_params['source_type_id']=4;
			}else if($team_product_receivable[$i]['source_type_id']==5){
				$team_product_receivable_params['fee_type_code']=38;
				$team_product_receivable_params['source_type_id']=5;
			}else if($team_product_receivable[$i]['source_type_id']==6){
				$team_product_receivable_params['fee_type_code']=39;
				$team_product_receivable_params['source_type_id']=6;
			}else if($team_product_receivable[$i]['source_type_id']==7){
				$team_product_receivable_params['fee_type_code']=40;
				$team_product_receivable_params['source_type_id']=7;
			}else if($team_product_receivable[$i]['source_type_id']==8){
				$team_product_receivable_params['fee_type_code']=41;
				$team_product_receivable_params['source_type_id']=8;
			}else if($team_product_receivable[$i]['source_type_id']==9){
				$team_product_receivable_params['fee_type_code']=42;
				$team_product_receivable_params['source_type_id']=9;
			}else if($team_product_receivable[$i]['source_type_id']==10){
				$team_product_receivable_params['fee_type_code']=43;
				$team_product_receivable_params['source_type_id']=10;
			}else if($team_product_receivable[$i]['source_type_id']==11){
				$team_product_receivable_params['fee_type_code']=44;
				$team_product_receivable_params['source_type_id']=11;
			}else if($team_product_receivable[$i]['source_type_id']==12){
				$team_product_receivable_params['fee_type_code']=45;
				$team_product_receivable_params['source_type_id']=12;
			}
			
			
			if(!empty($team_product_receivable[$i]['receivable_number'])){//有就修改没就新增
		
				$team_product_receivable_params['receivable_number'] = $team_product_receivable[$i]['receivable_number'];
				$team_product_receivable_params['public_number'] = $team_product_receivable[$i]['public_number'];
				$this->_receivable->updateReceivableByReceivableNumber($team_product_receivable_params);
				//拿到应收 去查对应的PUBLIC
				$finaces_params=[
					'receivable_number'=>$team_product_receivable_params['receivable_number'],
				];
				
				$cope_result = $this->_finaces_service->getCopeByReceivableNumber($finaces_params);
			
					
				//再走修改
				if($team_product_receivable[$i]['source_type_id']==1){//走团队产品修改
					$company_order_product_team_params=[
						'company_order_number'=>$team_product_receivable_params['order_number'],
						'team_product_id'=>$team_product_id,
						
						'team_product_name'=>	$team_product_receivable_params['product_name'],
						'team_product_cost'=>$team_product_receivable_params['receivable_money'],
						'cost_currency_id'=>$team_product_receivable_params['receivable_currency_id'],
						'cope_number'=>$cope_result[0]['cope_number'],
						'status'=>1,
						'now_user_id'=>$params['now_user_id']	
							
					];
					
					$this->_company_order_product_team->updateCompanyOrderTeamByCopeNumber($company_order_product_team_params);
					
				}else{

					$company_order_product_source_params=[
							'company_order_number'=>$team_product_receivable_params['order_number'],
							'supplier_type_id'=>$team_product_receivable_params['source_type_id'],
							'source_name'=>	$team_product_receivable_params['product_name'],
							'source_cost'=>$team_product_receivable_params['receivable_money'],
							'cost_currency_id'=>$team_product_receivable_params['receivable_currency_id'],
							'status'=>1,
							'cope_number'=>$cope_result[0]['cope_number'],
					];
					
					
					$this->_company_order_product_source->updateCompanyOrderSourceByCopeNumber($company_order_product_source_params);
					
				}
			}else{
				
				$team_product_receivable_params['receivable_number'] = help::getNumber(5,false);
				$receivable_number = $this->_receivable->addReceivable($team_product_receivable_params);

				$receivable_params = [
					'receivable_number'=>$receivable_number	
				];
			
				//通过应收查询应付编号
				$cope_result = $this->_finaces_service->getCopeByReceivableNumber($receivable_params);
				
				//添加
				if($team_product_receivable[$i]['source_type_id']==1){//走团队产品添加
					
					$company_order_product_team_params=[
							'company_order_number'=>$team_product_receivable_params['order_number'],
							'team_product_id'=>$team_product_id,
							'is_receivable_company'=>1,
							'team_product_name'=>	$team_product_receivable_params['product_name'],
							'team_product_cost'=>$team_product_receivable_params['receivable_money'],
							'cost_currency_id'=>$team_product_receivable_params['receivable_currency_id'],
							'company_order_customer_id'=>$team_product_receivable_params['company_order_customer_id'],
							'cope_number'=>$cope_result[0]['cope_number'],
							'settlement_type'=>1,
							'supplier_name'=>$params['user_company_name'],
							'status'=>1,
							'now_user_id'=>$params['now_user_id']
								
					];
					
					$this->_company_order_product_team->addCompanyOrderProductTeam($company_order_product_team_params);
						
				}else{

					$company_order_product_source_params=[
							'company_order_number'=>$team_product_receivable_params['order_number'],
							'supplier_type_id'=>$team_product_receivable_params['source_type_id'],
							'source_name'=>	$team_product_receivable_params['product_name'],
							'source_cost'=>$team_product_receivable_params['receivable_money'],
							'cost_currency_id'=>$team_product_receivable_params['receivable_currency_id'],
							'is_receivable_company'=>1,
							'now_user_id'=>$params['now_user_id'],
							'team_product_id'=>$team_product_id,
							'supplier_name'=>$params['user_company_name'],
							'cope_number'=>$cope_result[0]['cope_number'],
					];
					
					$this->_company_order_product_source->addCompanyOrderProductSource($company_order_product_source_params);
						
				}
			}
			
			
			


			
			
		}


		$this->outPut(1);
	}
	/**
	 * 添加团队产品应收直客/代理商
	 */
	public function addTeamProductReceivableCustomerAndDistributor(){
		
		
		
		$params = $this->input();
		
		$paramRule = [
					
				'team_product_id' => 'string',
				'receivable_info'=>'array',
				//     	'travel_agency_reimbursement_cope_info'=>'array',
				//     	'team_product_other_info'=>'array'
					
		];
		$this->paramCheckRule($paramRule,$params);

	
		$team_product_receivable = $params['receivable_info'];
		$team_product_number = $params['team_product_number'];
		$team_product_id = $params['team_product_id'];
		$team_product_other_info = $params['team_product_other_info'];
	
	
	
	
		// 		//首先先把应收应付状态变成0
	
		$receivable_status = [
			'team_product_id'=>$team_product_id,
			'fee_type_type'=>2010,
			'status'=>0,
		];
		$this->_receivable->updateStatusByData($receivable_status);

		

		$source_params=[
				'status'=>0,
				'team_product_id'=>$team_product_id,
				'is_type'=>2,
				'receivable_number'=>''
			
				
		];
		
		$this->_company_order_product_source->updateCompanyOrderSourceStatus($source_params);
	
		
	
		//首先添加团队产品应收
		for($i=0;$i<count($team_product_receivable);$i++){
								
				
			$team_product_receivable_params = [];
				
			$team_product_receivable_params = [
				'product_name' => $team_product_receivable[$i]['product_name'],
				'order_number'=>$team_product_receivable[$i]['order_number'],
			
				'company_order_customer_id'=>$team_product_receivable[$i]['company_order_customer_id'],
				'team_product_id'=>$team_product_id,
				'receivable_currency_id'=>$team_product_receivable[$i]['receivable_currency_id'],
				'receivable_money'=>$team_product_receivable[$i]['receivable_money'],
				'fee_type_type'=>2010,
				'now_user_id'=>$params['now_user_id'],
				'company_id'=>$params['user_company_id'],
				'is_auto'=>1,
				'team_product_id'=>$team_product_id
			];
			
			$team_product_receivable_params['source_type_id']=$team_product_receivable[$i]['source_type_id'];

// 			$team_product_receivable_params['payment_object_type'] = $team_product_receivable[$i]['payment_object_type'];
			
// 			if($team_product_receivable[$i]['payment_object_type']==3){
// 				$team_product_receivable_params['payment_object_id'] = $team_product_receivable[$i]['payment_object_id'];
// 			}
			


			//通过订单号查询订单信息
			$company_order_params =[
					'company_order_number'=>$team_product_receivable[$i]['order_number']
			];
		
			
			$company_order_result = $this->_company_order->getCompanyOrder($company_order_params);
			
			
		
			if(!empty($team_product_receivable[$i]['receivable_number'])){//有就修改没就新增
			
				$team_product_receivable_params['receivable_number'] = $team_product_receivable[$i]['receivable_number'];
		
				if($company_order_result[0]['channel_type']==1){
					$team_product_receivable_params['payment_object_type'] =3;
					$team_product_receivable_params['payment_object_id'] =$company_order_result[0]['distributor_id'];
				}else{
					$team_product_receivable_params['payment_object_type'] =4;
				
				}

				$this->_receivable->updateReceivableByReceivableNumber($team_product_receivable_params);

				//拿到应收 去查对应的PUBLIC
				$finaces_params=[
						'receivable_number'=>$team_product_receivable_params['receivable_number'],
				];

					
				//再走修改
// 				if($team_product_receivable[$i]['source_type_id']==1){//走团队产品修改
					
					
// 					$company_order_product_team_params=[
// 							'company_order_number'=>$team_product_receivable_params['order_number'],
// 							'team_product_id'=>$team_product_id,
	
// 							'team_product_name'=>	$team_product_receivable_params['product_name'],
// 							'team_product_price'=>$team_product_receivable_params['receivable_money'],
// 							'price_currency_id'=>$team_product_receivable_params['receivable_currency_id'],
// 							'receivable_number'=>$team_product_receivable[$i]['receivable_number'],
// 							'status'=>1,
// 							'now_user_id'=>$params['now_user_id'],
// 							'company_order_customer_id'=>$team_product_receivable_params['company_order_customer_id'],
// 							'is_receivable_company'=>1
							
							
								
// 					];
					
					
// 					$r = $this->_company_order_product_team->updateCompanyOrderTeamByReceivableNumber($company_order_product_team_params);
// 					if($r==0){//代表原来是自费的现在变团队产品 
// 					$company_order_product_team_params=[
// 							'company_order_number'=>$team_product_receivable_params['order_number'],
// 							'team_product_id'=>$team_product_id,
// 							'is_receivable_company'=>1,
// 							'team_product_name'=>	$team_product_receivable_params['product_name'],
// 							'team_product_price'=>$team_product_receivable_params['receivable_money'],
// 							'price_currency_id'=>$team_product_receivable_params['receivable_currency_id'],
// 							'company_order_customer_id'=>$team_product_receivable_params['company_order_customer_id'],
// 							'receivable_number'=>$team_product_receivable[$i]['receivable_number'],
// 							'settlement_type'=>1,
// 							'is_type'=>2,						
// 							'supplier_name'=>$params['user_company_name'],
// 							'status'=>1,
// 							'now_user_id'=>$params['now_user_id']
	
// 					];
				
			

						

					
					
// 					$this->_company_order_product_team->addCompanyOrderProductTeam($company_order_product_team_params);
					 
// 					 }
// 				}else{
	
					$company_order_product_source_params=[
							'company_order_number'=>$team_product_receivable_params['order_number'],
							'supplier_type_id'=>$team_product_receivable[$i]['source_type_id'],
							'source_name'=>	$team_product_receivable_params['product_name'],
							'source_price'=>$team_product_receivable_params['receivable_money'],
							'price_currency_id'=>$team_product_receivable_params['receivable_currency_id'],
							'status'=>1,
							'receivable_number'=>$team_product_receivable[$i]['receivable_number'],
							'company_order_customer_id'=>$team_product_receivable_params['company_order_customer_id'],
							'is_receivable_company'=>1
					];
						
					
					$r = $this->_company_order_product_source->updateCompanyOrderSourceByReceivableNumber($company_order_product_source_params);
					
					if($r==0){//代表原来是团队产品现在是自费了，走添加逻辑
						$company_order_product_source_params=[
								'company_order_number'=>$team_product_receivable_params['order_number'],
								'supplier_type_id'=>11,
								'source_name'=>	$team_product_receivable_params['product_name'],
								'source_price'=>$team_product_receivable_params['receivable_money'],
								'price_currency_id'=>$team_product_receivable_params['receivable_currency_id'],
								'is_receivable_company'=>1,
								'now_user_id'=>$params['now_user_id'],
								'team_product_id'=>$team_product_id,
								'supplier_name'=>$params['user_company_name'],
								'receivable_number'=>$team_product_receivable[$i]['receivable_number'],
								'is_type'=>2,
								'company_order_customer_id'=>$team_product_receivable_params['company_order_customer_id'],
						
						];
						
						
						$r = $this->_company_order_product_source->addCompanyOrderProductSource($company_order_product_source_params);
						
					}
					$params_data = [
						'receivable_number'=>$team_product_receivable[$i]['receivable_number']	
							
					];
					$source_result = $this->_company_order_product_source->getCompanyOrderProductSource($params_data);
					//添加游客
					$company_relation_params=[
						'company_order_product_source_id'=>$source_result[0]['company_order_product_source_id'],
						'company_order_customer'=>	$team_product_receivable_params['company_order_customer_id']
					];
					$this->_company_order_relation->addCompanyOrderRelation($company_relation_params);
				//}
			}else{ //添加
				if($team_product_receivable[$i]['source_type_id']==11){//自费
					$team_product_receivable_params['source_type_id'] = $team_product_receivable[$i]['source_type_id'];
					if($team_product_receivable[$i]['payment_object_type']==3){
						$team_product_receivable_params['fee_type_code']=343;
					}else{
						$team_product_receivable_params['fee_type_code']=344;
					}
					
				}else{
					if($team_product_receivable[$i]['payment_object_type']==3){
						$team_product_receivable_params['fee_type_code']=341;
					}else{
						$team_product_receivable_params['fee_type_code']=342;
					}
					
				}
				$team_product_receivable_params['receivable_number'] = help::getNumber(5,false);
				
				if($company_order_result[0]['channel_type']==1){
					$team_product_receivable_params['payment_object_type'] =3;
					$team_product_receivable_params['payment_object_id'] =$company_order_result[0]['distributor_id'];
				}else{
					$team_product_receivable_params['payment_object_type'] =4;
				
				}
				
				
				$receivable_number = $this->_receivable->addReceivable($team_product_receivable_params);
	
				$receivable_params = [
						'receivable_number'=>$receivable_number
				];
					

	
				//添加
// 				if($team_product_receivable[$i]['source_type_id']==1){//走团队产品添加
						
// 					$company_order_product_team_params=[
// 							'company_order_number'=>$team_product_receivable_params['order_number'],
// 							'team_product_id'=>$team_product_id,
// 							'is_receivable_company'=>1,
// 							'team_product_name'=>	$team_product_receivable_params['product_name'],
// 							'team_product_price'=>$team_product_receivable_params['receivable_money'],
// 							'price_currency_id'=>$team_product_receivable_params['receivable_currency_id'],
// 							'company_order_customer_id'=>$team_product_receivable_params['company_order_customer_id'],
// 							'receivable_number'=>$receivable_number,
// 							'settlement_type'=>1,
// 							'is_type'=>2,
							
// 							'supplier_name'=>$params['user_company_name'],
// 							'status'=>1,
// 							'now_user_id'=>$params['now_user_id']
	
// 					];
				
			

						

					
					
// 					$this->_company_order_product_team->addCompanyOrderProductTeam($company_order_product_team_params);
	
// 				}else{
	
					$company_order_product_source_params=[
							'company_order_number'=>$team_product_receivable_params['order_number'],
							'supplier_type_id'=>$team_product_receivable[$i]['source_type_id'],
							'source_name'=>	$team_product_receivable_params['product_name'],
							'source_price'=>$team_product_receivable_params['receivable_money'],
							'price_currency_id'=>$team_product_receivable_params['receivable_currency_id'],
							'is_receivable_company'=>1,
							'now_user_id'=>$params['now_user_id'],
							'team_product_id'=>$team_product_id,
							'supplier_name'=>$params['user_company_name'],
							'receivable_number'=>$receivable_number,
							'is_type'=>2,
							'is_settle'=>1,
							'company_order_customer_id'=>$team_product_receivable_params['company_order_customer_id'],
						
					];
					//通过订单和团队产品编号去查询 company_order_product_team_id
					$team_params = [
						'company_order_number'=>$team_product_receivable_params['order_number'],
						'team_product_id'=>	$team_product_id,
						'status'=>1	
					];
					
					$team_result = $this->_company_order_product_team->getCompanyOrderProductTeam($team_params);
					$company_order_product_source_params['company_order_product_team_id'] = $team_result[0]['company_order_product_team_id'];
					$this->_company_order_product_source->addCompanyOrderProductSource($company_order_product_source_params);
	
			//	}
			}
				
				
				
	
	
				
				
		}
		
		$this->outPut(1);
	}	
	/**
	 * 修改导游行程单
	 */
	public function updateTeamProductGuideReceipt(){
		$params = $this->input();
		$paramRule = [
		
			'team_product_number'=>'string',
		
					
		];
		$this->paramCheckRule($paramRule,$params);
		$result = $this->_team_product_tour_guide_jounery->updateTeamProductTourGuideJounery($params);
		$this->outPut($result);
	}
	/**
	 * 获取团队产品导游回执单
	 * 韩
	 */
	public function getTeamProductGuideReceipt(){	
		$params = $this->input();
		$paramRule = [		
			'team_product_number'=>'string',
			'team_product_id'=>'int'	
		];
	
		$team_product_guide_receipt = [];
		$this->paramCheckRule($paramRule,$params);
		$team_product_number = $params['team_product_number'];
		$team_product_id = $params['team_product_id'];
		//首先获取团队产品信息
	
		$team_product_params = [
			'status'=>1,
			'team_product_id'=>$team_product_id
		];
	
		$team_product_result = $this->_team_product->getTeamProduct($team_product_params);
	
		$team_product_guide_receipt['team_product_name'] = $team_product_result[0]['team_product_name'];
		$team_product_guide_receipt['team_product_number'] = $team_product_number;
		$team_product_guide_receipt['begin_time'] = $team_product_result[0]['begin_time'];
		$team_product_guide_receipt['operational_log'] = $team_product_result[0]['operational_log'];
		//首先获取整个团队的客人
		
		$team_product_customer_params = [
			
			'team_product_id'=>$team_product_id,
			
		];
	
		$team_product_customer = $this->_company_order_customer->getCompanyOrderCustomerByTeamProductId($team_product_customer_params);

		
		//根据团队产品 序号进行排序
		
	//	foreach ($team_product_customer as $key => $row) {
	//		$lineup_number[$key] = $row['lineup_number'];
			 
	//	}
		//升序处理行程时间
	//	array_multisort($lineup_number,SORT_ASC,$team_product_customer);
		
		
		for($i=0;$i<count($team_product_customer);$i++){
			//$team_product_customer[$i]['team_product_lineup_number'] = Help::getLineupPrefix(2).$team_product_customer[$i]['lineup_number'];
			if(is_numeric($team_product_customer[$i]['birthday'])){
				$team_product_customer[$i]['age'] = (date('Y')-date("Y",$team_product_customer[$i]['birthday']))+1;
			}else{
				$team_product_customer[$i]['age']='';
			}
			//给游客添加接送机
			$team_product_customer[$i]['flight_info'] = [];
			if($team_product_customer[$i]['customer_id']>0){//不能是站位
				$company_order_flight_params = [
					'status'=>1,
					'company_order_customer_id'=>$team_product_customer[$i]['company_order_customer_id'],
					//'customer_id'=>$team_product_customer[$i]['customer_id']
				];
				
				$team_product_customer[$i]['flight_info'] = $this->_company_order_flight->getCompanyOrderFlight($company_order_flight_params);
				$team_product_customer_flight  = $team_product_customer[$i]['flight_info'];
// 				for($kk=0;$kk<count($team_product_customer_flight);$kk++){
// 					$team_product_customer[$i]['flight_info'][$kk]['team_product_lineup_number'] =$team_product_customer[$i]['team_product_lineup_number']; 
// 				}
			}
		}
		
		//开始计算团队产品出发以及结束时间
		$begin_time = $team_product_result[0]['begin_time']; //团队产品开始时间
		//团队产品结束时间
		$team_product_journey_params = [
			'status'=>1,
			'team_product_id'=>	$team_product_result[0]['team_product_id']
		];
		$team_product_journey_result = $this->_team_product_journey->getTeamProductJourney($team_product_journey_params);
		$team_product_journey_count = count($team_product_journey_result);
		$add_t = count($team_product_journey_result)-1;
		$end_time = strtotime("+ $add_t days",$team_product_result[0]['begin_time']); //团队产品开始时间
		
		
		//开始处理行程单的内容
		for($i=0;$i<count($team_product_journey_result);$i++){
			$team_product_journey_result[$i]['customer_count'] = 0;
			$team_product_journey_result[$i]['room_type']='';
			$team_product_journey_result[$i]['jounery_time'] = date('Y/m/d',strtotime("$i days",$begin_time));
			
			//再依次循环每个游客 主要计算房型 数量
			for($j=0;$j<count($team_product_customer);$j++){
				
			
				$check_on_days = $team_product_customer[$j]['check_on'];
				$check_in_days = $team_product_customer[$j]['check_in'];
				$number = $team_product_customer[$j]['company_order_number'];
				$kk=0;
				if($team_product_customer[$j]['customer_id']==0){
					break;
				}
				if($check_in_days==0 && $check_on_days==0){ //属于正常入住
					$team_product_journey_result[$i]['customer_count']+=1;
					$team_product_journey_result[$i]['room_type'].=','.$number."+".$team_product_customer[$j]['room_code'].'-'.$team_product_customer[$j]['room_type'];
						
				}else if($check_in_days<0){
					if(($check_in_days) < ($j+1)){
						if( $check_on_days < (count($team_product_journey_result)+$i) ){
							$team_product_journey_result[$i]['customer_count']+=1;
							$team_product_journey_result[$i]['room_type'].=','.$number."+".$team_product_customer[$j]['room_code'].'-'.$team_product_customer[$j]['room_type'];
						}else{
							$team_product_journey_result[$i]['customer_count']+=1;
							$team_product_journey_result[$i]['room_type'].=','.$number."+".$team_product_customer[$j]['room_code'].'-'.$team_product_customer[$j]['room_type'];
							
							
						}
					}
				}else{//checi_in_days 大于0的
					if( $check_on_days < (count($team_product_journey_result)+$i) ){
						$team_product_journey_result[$i]['customer_count']+=1;
						$team_product_journey_result[$i]['room_type'].=','.$number."+".$team_product_customer[$j]['room_code'].'-'.$team_product_customer[$j]['room_type'];
					
							
					}else{
						$team_product_journey_result[$i]['customer_count']+=1;
						$team_product_journey_result[$i]['room_type'].=','.$number."+".$team_product_customer[$j]['room_code'].'-'.$team_product_customer[$j]['room_type'];
							
							
					}
					
				}
				

			}
			$team_product_journey_result[$i]['room_type']=trim($team_product_journey_result[$i]['room_type'],',');//$team_product_customer[$j]['room_type'];
		}
	
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
		//初始化入住房型
		$check_in_1_room_type='';
		$check_in_2_room_type='';
		$check_in_3_room_type='';
		$check_in_4_room_type='';
		$check_in_5_room_type='';
		
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
		//初始化退房房型
		$check_on_1_room_type='';
		$check_on_2_room_type='';
		$check_on_3_room_type='';
		$check_on_4_room_type='';
		$check_on_5_room_type='';
		
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
		
		
		
		//开始计算提前以及延后
		$flight_info = [];

		for($i=0;$i<count($team_product_customer);$i++){
			$customer_flight_info = $team_product_customer[$i]['flight_info'];
			$customer_order_number = $team_product_customer[$i]['company_order_number'];
			//$team_product_customer[$i]['room_type'] = $customer_order_number.'+'.$team_product_customer['room_code'].'_'.$team_product_customer[$i]['room_type'];
			for($j=0;$j<count($customer_flight_info);$j++){//添加航班信息
				$customer_flight_info_j = $customer_flight_info[$j];
				$flight_type = $customer_flight_info[$j]['flight_type'];
				$flight_begin_time =$customer_flight_info[$j]['flight_begin_time'];
				$flight_end_time = $customer_flight_info[$j]['flight_end_time'];
				$customer_flight_info[$j]['customer_name'] = $team_product_customer[$i]['customer_name'];
				if($flight_type==1 and !empty($flight_end_time)){//接机要到达时间
					$customer_flight_info_j['flight_time'] = date('Y/m/d',$flight_end_time);
			
					$flight_info[]=$customer_flight_info_j;
			
				}else if($flight_type==2 and !empty($flight_begin_time)){//送机要出发时间
				
					$customer_flight_info_j['flight_time'] = date('Y/m/d',$flight_begin_time);
					$flight_info[]=$customer_flight_info_j;
			
				}else{
    				$flight_info[]=$customer_flight_info_j;
    			}
			}
			
			
			
			
			
			
			
			$check_in = $team_product_customer[$i]['check_in'];
		
			$room_type = $team_product_customer[$i]['room_type'];
			$room_code = $team_product_customer[$i]['room_code'];
			$number = $team_product_customer[$i]['company_order_number'];
			$check_in_hotel = $team_product_customer[$i]['check_in_hotel'];
			$check_on_hotel = $team_product_customer[$i]['check_on_hotel'];
			
			
			$check_in_hotel_array = [];
			if($team_product_customer[$i]['check_in']==-1){
				$check_in_hotel_array[] = $check_in_hotel;
			}else{
				if(!empty($check_in_hotel)){
					$check_in_hotel_array =  explode(',',$check_in_hotel);
				}else{
					$check_in_hotel_array =  [];
				}
			}
			$check_on_hotel_array =[];
			if($team_product_customer[$i]['check_on']==1){
				$check_on_hotel_array[] =  $check_on_hotel;
			}else{
				if(!empty($check_on_hotel)){
					$check_on_hotel_array = explode(',',$check_on_hotel);
				}else{
					$check_on_hotel_array =  [];
				}
			}
			
			
		
	
			for($j=0;$j<count($check_in_hotel_array);$j++){
				if($j==0 ){
					$check_in_1_day = [
						'route_journey_title'=>'提前入住1天',
						'jounery_time'=>date('Y/m/d',strtotime("-1 days",$begin_time))
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
				    

    				
    				
    				
    				
    				
					if(!empty($room_type)){
						if($check_in_1_room_type==''){
							$check_in_1_room_type=$number."+".$room_code.'-'.$room_type;
						}else{
							$check_in_1_room_type.=','.$number."+".$room_code.'-'.$room_type;
						}
					}
					$check_in_1_hotel_customer_count++;
				}else if($j==1 ){
					$check_in_2_day = [
							'route_journey_title'=>'提前入住2天',
							'jounery_time'=>date('Y/m/d',strtotime("-2 days",$begin_time))
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
    				
    				

					if(!empty($room_type)){
						if($check_in_2_room_type==''){
							$check_in_2_room_type=$number."+".$room_code.'-'.$room_type;
						}else{
							$check_in_2_room_type.=','.$number."+".$room_code.'-'.$room_type;
						}
					}
					$check_in_2_hotel_customer_count++;
				}else if($j==2 ){
					$check_in_3_day = [
							'route_journey_title'=>'提前入住3天',
							'jounery_time'=>date('Y/m/d',strtotime("-3 days",$begin_time))
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

					if(!empty($room_type)){
						if($check_in_3_room_type==''){
							$check_in_3_room_type=$number."+".$room_code.'-'.$room_type;
						}else{
							$check_in_3_room_type.=','.$number."+".$room_code.'-'.$room_type;
						}
					}
					$check_in_3_hotel_customer_count++;
				}else if($j==3 ){
					$check_in_4_day = [
							'route_journey_title'=>'提前入住4天',
							'jounery_time'=>date('Y/m/d',strtotime("-4 days",$begin_time))
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

					if(!empty($room_type)){
						if($check_in_4_room_type==''){
							$check_in_4_room_type=$number."+".$room_code.'-'.$room_type;
						}else{
							$check_in_4_room_type.=','.$number."+".$room_code.'-'.$room_type;
						}
					}
					$check_in_4_hotel_customer_count++;
				}else if($j==4 ){
					$check_in_5_day = [
							'route_journey_title'=>'提前入住5天',
							'jounery_time'=>date('Y/m/d',strtotime("-5 days",$begin_time))
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

					if(!empty($room_type)){
						if($check_in_5_room_type==''){
							$check_in_5_room_type=$number."+".$room_code.'-'.$room_type;
						}else{
							$check_in_5_room_type.=','.$number."+".$room_code.'-'.$room_type;
						}
					}
					$check_in_5_hotel_customer_count++;
				}
			}
			
			for($k=0;$k<count($check_on_hotel_array);$k++){
				if($k==0 ){
					$check_on_1_day = [
							'route_journey_title'=>'延后退房1天',
							'jounery_time'=>date('Y/m/d',strtotime("+1 days",$end_time))
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
				    

					if(!empty($room_type)){
						if($check_on_1_room_type==''){
							$check_on_1_room_type=$number."+".$room_code.'-'.$room_type;
						}else{
							$check_on_1_room_type.=','.$number."+".$room_code.'-'.$room_type;
						}
					}
					$check_on_1_hotel_customer_count++;
				}else if($k==1 ){
					$check_on_2_day = [
							'route_journey_title'=>'延后退房2天',
							'jounery_time'=>date('Y/m/d',strtotime("+2 days",$end_time))
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

					if(!empty($room_type)){
						if($check_on_2_room_type==''){
							$check_on_2_room_type=$number."+".$room_code.'-'.$room_type;
						}else{
							$check_on_2_room_type.=','.$number."+".$room_code.'-'.$room_type;
						}
					}
					$check_on_2_hotel_customer_count++;
				}else if($k==2 ){
					$check_on_3_day = [
							'route_journey_title'=>'延后退房3天',
							'jounery_time'=>date('Y/m/d',strtotime("+3 days",$end_time))
					];
				    				$check_on_3_day = [
    						'route_journey_title'=>'延后退房3天',
    						'jounery_time'=>date('Y/m/d',strtotime("+3 days",strtotime($last_day_jounery)))
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
    				

					if(!empty($room_type)){
						if($check_on_3_room_type==''){
							$check_on_3_room_type=$number."+".$room_code.'-'.$room_type;
						}else{
							$check_on_3_room_type.=','.$number."+".$room_code.'-'.$room_type;
						}
					}
					$check_on_3_hotel_customer_count++;
				}else if($k==3 ){
					$check_on_4_day = [
							'route_journey_title'=>'延后退房4天',
							'jounery_time'=>date('Y/m/d',strtotime("+4 days",$end_time))
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
				    

					if(!empty($room_type)){
						if($check_on_4_room_type==''){
							$check_on_4_room_type=$number."+".$room_code.'-'.$room_type;
						}else{
							$check_on_4_room_type.=','.$number."+".$room_code.'-'.$room_type;
						}
					}
					$check_on_4_hotel_customer_count++;
				}else if($k==4 ){
					$check_on_5_day = [
							'route_journey_title'=>'延后退房5天',
							'jounery_time'=>date('Y/m/d',strtotime("+5 days",$end_time))
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

		
				
					
					if(!empty($room_type)){
						if($check_on_5_room_type==''){
							$check_on_5_room_type=$number."+".$room_code.'-'.$room_type;
						}else{
							$check_on_5_room_type.=','.$number."+".$room_code.'-'.$room_type;
						}
					}
					$check_on_5_hotel_customer_count++;
		
				}
		
			}
		}
		
		
		
	
		 
		if(count($check_in_1_day)>0){
			$check_in_1_day['route_journey_stay'] = $check_in_1_hotel;
			$check_in_1_day['room_type'] = $check_in_1_room_type;			
			$check_in_1_day['route_journey_scenic_sport'] = '';
			$check_in_1_day['route_journey_stay_phone'] = $check_in_1_hotel_phone;
			$check_in_1_day['route_journey_stay_address'] = $check_in_1_hotel_address;
			$check_in_1_day['customer_count'] = $check_in_1_hotel_customer_count;
			array_unshift($team_product_journey_result,$check_in_1_day);
		}
		if(count($check_in_2_day)>0){
			$check_in_2_day['route_journey_stay'] = $check_in_2_hotel;
			$check_in_2_day['room_type'] = $check_in_2_room_type;
			$check_in_2_day['route_journey_scenic_sport'] = '';
			$check_in_2_day['route_journey_stay_phone'] = $check_in_2_hotel_phone;
			$check_in_2_day['route_journey_stay_address'] = $check_in_2_hotel_address;
			$check_in_2_day['customer_count'] = $check_in_2_hotel_customer_count;
			array_unshift($team_product_journey_result,$check_in_2_day);
		}
		if(count($check_in_3_day)>0){
			$check_in_3_day['route_journey_stay'] = $check_in_3_hotel;
			$check_in_3_day['room_type'] = $check_in_3_room_type;
			$check_in_3_day['route_journey_scenic_sport'] = '';
			$check_in_3_day['route_journey_stay_phone'] = $check_in_3_hotel_phone;
			$check_in_3_day['route_journey_stay_address'] = $check_in_3_hotel_address;		
			$check_in_3_day['customer_count'] = $check_in_3_hotel_customer_count;
			array_unshift($team_product_journey_result,$check_in_3_day);
		}
		if(count($check_in_4_day)>0){
			$check_in_4_day['route_journey_stay'] = $check_in_4_hotel;
			$check_in_4_day['room_type'] = $check_in_4_room_type;
			$check_in_4_day['route_journey_scenic_sport'] = '';
			$check_in_4_day['route_journey_stay_phone'] = $check_in_4_hotel_phone;
			$check_in_4_day['route_journey_stay_address'] = $check_in_4_hotel_address;		
			$check_in_4_day['customer_count'] = $check_in_4_hotel_customer_count;
			array_unshift($team_product_journey_result,$check_in_4_day);
		}
		if(count($check_in_5_day)>0){
			$check_in_5_day['route_journey_stay'] = $check_in_5_hotel;
			$check_in_5_day['room_type'] = $check_in_5_room_type;
			$check_in_5_day['route_journey_scenic_sport'] = '';
			$check_in_5_day['route_journey_stay_phone'] = $check_in_5_hotel_phone;
			$check_in_5_day['route_journey_stay_address'] = $check_in_5_hotel_address;		
			$check_in_5_day['customer_count'] = $check_in_5_hotel_customer_count;
			array_unshift($team_product_journey_result,$check_in_5_day);
		}
		if(count($check_on_1_day)>0){
			$check_on_1_day['route_journey_stay'] = $check_on_1_hotel;
			$check_on_1_day['room_type'] = $check_on_1_room_type;
			$check_on_1_day['route_journey_scenic_sport'] = '';
			$check_on_1_day['route_journey_stay_phone'] = $check_on_1_hotel_phone;
			$check_on_1_day['route_journey_stay_address'] = $check_on_1_hotel_address;
			$check_on_1_day['customer_count'] = $check_on_1_hotel_customer_count;
			$team_product_journey_result[] = $check_on_1_day;
		}
		if(count($check_on_2_day)>0){
			$check_on_2_day['route_journey_stay'] = $check_on_2_hotel;
			$check_on_2_day['room_type'] = $check_on_2_room_type;
			$check_on_2_day['route_journey_scenic_sport'] = '';
			$check_on_2_day['route_journey_stay_phone'] = $check_on_2_hotel_phone;
			$check_on_2_day['route_journey_stay_address'] = $check_on_2_hotel_address;	
			$check_on_2_day['customer_count'] = $check_on_2_hotel_customer_count;
			$team_product_journey_result[] = $check_on_2_day;
		}
		if(count($check_on_3_day)>0){
			$check_on_3_day['route_journey_stay'] = $check_on_3_hotel;
			$check_on_3_day['room_type'] = $check_on_3_room_type;
			$check_on_3_day['route_journey_scenic_sport'] = '';
			$check_on_3_day['route_journey_stay_phone'] = $check_on_3_hotel_phone;
			$check_on_3_day['route_journey_stay_address'] = $check_on_3_hotel_address;		
			$check_on_3_day['customer_count'] = $check_on_3_hotel_customer_count;
			$team_product_journey_result[] = $check_on_3_day;
		}
		if(count($check_on_4_day)>0){
			$check_on_4_day['route_journey_stay'] = $check_on_4_hotel;
			$check_on_4_day['room_type'] = $check_on_4_room_type;
			$check_on_4_day['route_journey_scenic_sport'] = '';
			$check_on_4_day['route_journey_stay_phone'] = $check_on_4_hotel_phone;
			$check_on_4_day['route_journey_stay_address'] = $check_on_4_hotel_address;	
			$check_on_5_day['customer_count'] = $check_on_5_hotel_customer_count;
			$team_product_journey_result[] = $check_on_4_day;
		}
		if(count($check_on_5_day)>0){
			$check_on_5_day['route_journey_stay'] = $check_on_5_hotel;
			$check_on_5_day['room_type'] = $check_on_5_room_type;
			$check_on_5_day['route_journey_scenic_sport'] = '';
			$check_on_5_day['route_journey_stay_phone'] = $check_on_5_hotel_phone;
			$check_on_5_day['route_journey_stay_address'] = $check_on_5_hotel_address;	
			$check_on_5_day['customer_count'] = $check_on_5_hotel_customer_count;
			$team_product_journey_result[] = $check_on_5_day;
		}
		
	
		for($i=0;$i<count($team_product_journey_result);$i++){
			$room_type_str = $team_product_journey_result[$i]['room_type'];
			
			if(!empty($room_type_str)){
				//首先循环出数据
				$room_type_arr  = explode(",",$room_type_str);
				$room_code=[];
				$room_result=[];
				$room_array_result=[];
				for($j=0;$j<count($room_type_arr);$j++){
					$room_code_str = substr($room_type_arr[$j],0,strrpos($room_type_arr[$j],'-'));
				
					$room_type_str = substr($room_type_arr[$j],strrpos($room_type_arr[$j],'-')+1);
				
				
					if(!in_array($room_code_str,$room_array_result)){
							
						$room_array_result[] = $room_code_str;
				
						$room_result[] =$room_type_str;
					}
				
				}
					
				//print_r($room_array_result);
				$team_product_journey_result[$i]['room_type'] = $room_result;				
			}
			

		}
	
		
		$team_product_guide_receipt['team_product_journey'] = $team_product_journey_result;

		//根据房型升序
	
		
		foreach ($team_product_customer as $key => $row) {
			$room_code[$key] = $row['room_code'];
		
		}
		//升序处理行程时间
		array_multisort($room_code,SORT_ASC,$team_product_customer);

		//客人名单
		for($i=0;$i<count($team_product_customer);$i++){
			$check_in_time = $team_product_customer[$i]['check_in'];
			$check_on_time = $team_product_customer[$i]['check_on'];
			if(!empty($check_in_time)){
				
				if($check_in_time>0){
					$team_product_customer[$i]['check_in_time']='延后'.$check_in_time.'天';
				}else{
					$team_product_customer[$i]['check_in_time']='提前'.abs($check_in_time).'天';
				}
				
				
				
			}else{
				$team_product_customer[$i]['check_in_time'] = '';
			}
			if(!empty($check_on_time)){
				if($check_on_time>0){
					$team_product_customer[$i]['check_on_time'] = '延后'.$check_on_time.'天';
				}else{
					$team_product_customer[$i]['check_on_time'] = '提前'.$check_on_time.'天';
				}
				
				
			}else{
				$team_product_customer[$i]['check_on_time'] = '';
			}
			
		
		}
		
		
		foreach ($team_product_customer as $key => $row) {
			$company_order_number[$key] = $row['company_order_number'];
			$room_code[$key] = $row['room_code'];
		}
		array_multisort($company_order_number,SORT_ASC,$room_code,SORT_ASC,$team_product_customer);
		
		
		$new_team_product_customer = [];
		for($i=0;$i<count($team_product_customer);$i++){
			$company_order_number_this_number = $team_product_customer[$i]['company_order_number'];
			$new_team_product_customer[$company_order_number_this_number][] = $team_product_customer[$i];
		}
		
		
		//for($i=0;$i<count($new_team_product_customer);$i++){
		foreach ($new_team_product_customer[$i] as $key => $row) {
			$room_code[$key] = $row['room_code'];
		
		}
		array_multisort($room_code,SORT_ASC,$new_team_product_customer);
		//}
		
		
		$new_team_product_customer = array_values($new_team_product_customer);
		
		
		$team_product_guide_receipt['customer_info'] = $new_team_product_customer;
// 		//开始接送机业务逻辑
		
// 		foreach ($flight_info as $key => $row) {
// 			$flight_time[$key] = $row['flight_time'];
		
// 		}
// 		//升序处理行程时间
// 		array_multisort($flight_time,SORT_ASC,$flight_info);
		$new_flight_info = [];
		for($i=0;$i<count($flight_info);$i++){
			$flight_code = $flight_info[$i]['flight_code'];
			$new_flight_info[$flight_code][] = $flight_info[$i];
		}
		 
		$new_flight_info = array_values($new_flight_info);
		$team_product_guide_receipt['flight_info'] = $new_flight_info;
		
		
		//开始获取自费项目
		
		//获取自费项目以及游客信息
		$company_order_product_source_params = [
			'team_product_id'=>$team_product_id,
			'status'=>1,
			'supplier_type_id'=>11
		];
		
		$company_order_product_source_result = $this->_company_order_product_source->getCompanyOrderProductSource($company_order_product_source_params);
		
		for($i=0;$i<count($company_order_product_source_result);$i++){
			$company_order_product_source_params = [];
		
			$company_order_product_source_params = [
				'company_order_product_source_id'=>$company_order_product_source_result[$i]['company_order_product_source_id'],
				'company_order_number'=>$company_order_product_source_result[$i]['company_order_number'],
				'status'=>1
		
			];
		
			$customer_info_result = $this->_company_order_relation->getCompanyOrderRelation($company_order_product_source_params);
		
			
			
			for($j=0;$j<count($customer_info_result);$j++){
				$customer_info_params=[
					'status'=>1,
					'company_order_number'=>$company_order_product_source_result[$i]['company_order_number'],
					'team_product_number'=>$company_order_product_source_result[$i]['team_product_number'],
					'lineup_type'=>2,
					'company_order_customer_id'=>$customer_info_result[$j]['company_order_customer_id']
				];
			
				$company_order_customer_lineup_result = $this->_company_order_customer_lineup->getLineup($customer_info_params);
				if(count($company_order_customer_lineup_result)==0){
					unset($customer_info_result[$j]);	
				}else{
					$customer_info_result[$j]['team_product_lineup_number'] = Help::getLineupPrefix(2).$company_order_customer_lineup_result[0]['lineup_number'];
						
				}
			}
			$customer_info_result = array_values($customer_info_result);
			$company_order_product_source_result[$i]['customer_info'] =$customer_info_result;
		}
		
		
		
		$new_company_order_product_source_result = [];
		for($i=0;$i<count($company_order_product_source_result);$i++){
			$source_id = $company_order_product_source_result[$i]['source_id'];
			$supplier_type_id = $company_order_product_source_result[$i]['supplier_type_id'];
			$new_i =$supplier_type_id.$source_id;
 			$new_company_order_product_source_result[$new_i]['customer_info'][] = $company_order_product_source_result[$i]['customer_info'];

		}
		
		
		
		for($i=0;$i<count($company_order_product_source_result);$i++){
			$source_id = $company_order_product_source_result[$i]['source_id'];
			$supplier_type_id = $company_order_product_source_result[$i]['supplier_type_id'];
			$new_i =$supplier_type_id.$source_id;
			$new_customer_info = $new_company_order_product_source_result[$new_i]['customer_info'];
			$new_customer_info_array = [];
			for($j=0;$j<count($new_customer_info);$j++){
				for($k=0;$k<count($new_customer_info[$j]);$k++){
					$new_customer_info_array[] = $new_customer_info[$j][$k];
				}
			}
			$company_order_product_source_result[$i]['customer_info'] =$new_customer_info_array;
		
		}
		$own_expense_info_array = [];
		for($i=0;$i<count($company_order_product_source_result);$i++){
			$source_id = $company_order_product_source_result[$i]['source_id'];
			$supplier_type_id = $company_order_product_source_result[$i]['supplier_type_id'];
			$new_i =$supplier_type_id.$source_id;
			$own_expense_info_array[$new_i] = $company_order_product_source_result[$i];
		}
		$own_expense_info_array = array_values($own_expense_info_array);
		
  		//$new_company_order_product_source_result = array_values($new_company_order_product_source_result);
 		
		$team_product_guide_receipt['own_expense_info'] = $own_expense_info_array;//$new_company_order_product_source_result;
		
		
		//
		$team_product_return_receipt_params = [
			'status'=>1,
			'team_product_id'=>	 $team_product_result[0]['team_product_id']
		];
		$team_product_return_receipt_result = $this->_team_product_return_receipt->getTeamProductReturnReceipt($team_product_return_receipt_params);
		 
		$team_product_guide_receipt['return_receipt']=$team_product_return_receipt_result;
		
		$this->outPut($team_product_guide_receipt);
	}
	
	/**
	 * 团队产品生成应收应付
	 */
	public function addTeamProductPriceAndCope(){
		exit();
		$params = $this->input();
		$paramRule = [	
			'team_product_id'=>'string',					
		];
							
		$this->paramCheckRule($paramRule,$params);
		
		$team_product_id = $params['team_product_id'];
		//首先获取团队产品进出信息查看是否已经成团
		$team_product_params = [
			'team_product_id'=>$team_product_id
		];
		$team_product_result = $this->_team_product->getTeamProduct($team_product_params);
		if($team_product_result[0]['is_establish_team_product']==1){
			$this->outPutError(['msg'=>'team product is establish']);
			exit();
		}
		//首先获取哪些公司订单用了团队产品
		$company_order_product_team_params = [
			'team_product_id'=>$team_product_id,
			'status'=>1,	
			'company_order_status'=>1
		];
		
		$company_order_product_team_result = $this->_company_order_product_team->getCompanyOrderProductTeam($company_order_product_team_params);

		
		for($i=0;$i<count($company_order_product_team_result);$i++){
			$company_order_params = [
				'company_order_number'=>$company_order_product_team_result[$i]['company_order_number']	
			];
			$company_order_result = $this->_company_order->getCompanyOrder($company_order_params);
			
			if($company_order_result[0]['status']==0){
				continue;
			}
			//让使用该团的订单变成锁定
			$company_order_params = [
				'locked'=>1,
				'company_order_number'=>$company_order_product_team_result[$i]['company_order_number']	
			];
			$this->_company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
			$company_order_product_team_result[$i]['now_user_id'] = $params['now_user_id'];
			$this->_company_order_service->addTeamProductPriceAndCope($company_order_product_team_result[$i]);
		}
		
		
		$team_product_params = [
			'is_establish_team_product'=>1,
			'team_product_id'=>$team_product_result[0]['team_product_id'],
			'now_user_id'=>$params['now_user_id']	
		];
		
		$result = $this->_team_product->updateTeamProductBaseByTeamProductBaseId($team_product_params);
		//最后更新状态
		
		$this->outPut(1);
	}

    /**
     * getRouteOnePrice
     *
     * 获取线路模版报价
     * @author shj
     * @return void
     * Date: 2019/3/27
     * Time: 17:31
     */
    public function getRouteOnePrice(){
        $params = $this->input();
        $paramRule = [
            'route_settlement_type'=>'number',
        ];
        $this->paramCheckRule($paramRule,$params);

        if($params['route_settlement_type'] == 1){//一口价

            $route_once_price = new RouteOncePrice();
            $route_once_price_result = $route_once_price->getRouteOncePrice($params);

            $this->outPut($route_once_price_result);

        }else if($params['team_product_settlement_type'] == 2){//真实结算

            $route_price = new RouteSourceAllocation();
            $route_price_result = $route_price->getRouteSourcePrice($params);

            $this->outPut($route_price_result);

        }
    }


    /**
    * 修改团队产品的团队状态
    * HUGH 
    */
    public function upPlurStatusAjax(){
		$params = $this->input();
     
       	if($params['plur_status']==3){//如果是结团需要判断应收应付是否结清
			$result = $this->_finaces_service->checkReceivableAndCope($params);
			
			if($result==2){
				$this->output('','应收应付还没结清');
				exit();
			}
			
       	}
       
       	$retun = $this->_team_product->upPlurStatusAjax($params);

       
       	$this->output($retun);
    }


    /**
    * 线路模板同步团队产品
    * HUGH
    */
    public function synchronizationTeamProduct(){
        $params = $this->input();

        // $params['route_template_id'] = 254;
        // $params['s_time'] = '2019-04-01';
        // $params['e_time'] = '2019-09-01';
        // $params['user_id'] = 394;
        $paramRule = [
            'route_template_id'=>'number',
        ];
        $this->paramCheckRule($paramRule,$params);

        if(empty($params['s_time']) && empty($params['e_time'])){
            $this->outPutError(['msg' => "请选择日期区间"]);    
        }
        if(!empty($params['s_time']) && !empty($params['e_time'])){
            if($params['s_time']>$params['e_time']){
               $this->outPutError(['msg' => "错误的日期区间"]);     
            }
        }

        $return = $this->_product_common->synchronizationTeamProduct($params); 
        $this->output($return);
    }



    /**
    * 分团 
    * HUGH
    * 2019/04/04
    */
    public function subGroup(){
        $params = $this->input();
        // $params['old_team_product_id'] =  13; // 原团队编号
        // $params['new_team_product_id'] = -1; // -1 创建新的团产品   新的团队编号
        // $params['user_id'] = 392;
        $paramRule = [  
            'old_team_product_id'=>'string',  //原团队编号
            'new_team_product_id'=>'string',  //新的团队编号
            'order_number'=>'array'              //订单编号
        ];                         
        $this->paramCheckRule($paramRule,$params); 

        if($params['new_team_product_id'] == -1){ //复制个新团
            $params['new_team_product_number'] = $this->_product_common->replicatingRegiment($params['old_team_product_id'],$params['user_id']);
        }

        //执行订单移团操作
        

    }


    /**
    * 线路模板获取团队产品
    * HUGH
    */
    public function selTeamProductbyRouteTemplateId(){
        $params = $this->input();
        $paramRule = [  
            'route_template_id'=>'string',  //线路模板ID
        ];       
  
        $this->paramCheckRule($paramRule,$params);
        $return = $this->_product_common->selTeamProductbyRouteTemplateId($params); 
       
        $this->output($return);
        
    }


    /*
    * 获取个线路收客人数
    */
    public function getNumberOfPassengersOnALine(){
        $params = $this->input();
        // $params['company_id'] = 13;
        
        $return = $this->_product_common->getNumberOfPassengersOnALine($params);
        $this->output($return);
    }

    /**
    * 获取同类团队产品(分团)
    */
    public function otherRegimentNumber(){ 
        $params = $this->input();
        $return = $this->_product_common->otherRegimentNumber($params);
        $this->output($return);
    }

    /**
    * 复制线路模板
    */
    public function copyCircuitTemplate(){
        $params = $this->input();
        $paramRule = [  
            'route_template_id'=>'string',  //线路模板ID
        ];   
        $this->paramCheckRule($paramRule,$params);

        $return = $this->_product_common->copyCircuitTemplate($params);
        $this->output($return);
    }
    /**
     * 获取结算单审批
     */
    public function getStatementApprove(){
    	$params = $this->input();

    	$result = $this->_statement_approve->getStatementApprove($params);

    	
    	$this->outPut($result);
    }
    

    /**
     * 提交结算单审批意见
     */
    public function postStatementApprove(){
    	$params = $this->input();
    	$paramRule = [
    		'statement_approve_id'=>'int',
    		'approve_result'=>'int'
    
   
    	];
    	$this->paramCheckRule($paramRule,$params);
    	 
    	//首先修改
    	//首先先判断是否需要修改
    	$statement_params['statement_approve_id'] = $params['statement_approve_id'];
    	$statement_result = $this->_statement_approve->getStatementApprove($statement_params);
    	if($statement_result[0]['status']== 2){
    		$this->outPutError(['msg'=>'data is Checked'], $params);
    		exit();
    	}
    	$result = $this->_statement_approve->updateStatementApprove($params);
    	$this->outPut($result);
    }
    //添加线路模板留言板
    public function addRouteTemplateComment()
    {
    	$params = $this->input();
    	$paramRule = [
    			'comment' => 'string',
    			'route_template_id' => 'route_template_id'
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    	$order_pay_record_model = new RouteTemplateComment();
    
    	$result = $order_pay_record_model->addRouteTemplateComment($params);
    
    	$this->outPut($result);
    }
    //添加线路模板留言板
    public function getRouteTemplateComment()
    {
    	$params = $this->input();

    	$this->paramCheckRule($paramRule,$params);
    
    	$order_pay_record_model = new RouteTemplateComment();

    	$result = $order_pay_record_model->getRouteTemplateComment($params);
    
    	$this->outPut($result);
    }
}
