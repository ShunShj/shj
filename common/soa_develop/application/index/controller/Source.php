<?php
namespace app\index\controller;
use app\common\help\Contents;
use app\common\help\Help;
use app\index\model\source\B2bHotel;
use app\index\model\source\Shopping;
use app\index\model\source\SupplierAccount;
use think\config ;
use app\index\model\source\Supplier;
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
use app\index\model\system\Language;
use app\index\model\source\SourcePrice;
use app\index\service\SourceService;
use think\Model;
class Source extends Base
{
	private $_language;
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
    //_lang Base里的属性，
    public function __construct()
    {
    	$this->_language = config("systom_setting")['language_default'];
    	$this->_source_service = new SourceService();
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
        parent::__construct();
    }
    
    /**
     * 添加供应商数据
     * 胡
     */
    public function addSupplier(){
    
    	$params = $this->input();
    
    	$paramRule = [
 			'supplier_name' => 'string',
    		'country_id'=>'number',
    		'status'=>'number',
    	];
    		
    	$this->paramCheckRule($paramRule,$params);
    
    	//开始判断名字是否重复
    	$data = [
    		'supplier_name'=>$params['supplier_name'],
    		'company_id'=>$params['choose_company_id'],
    	];
    	$this->checkNameIsRepetition('supplier',$data);
    	//结束判断名字重复
    
    	$supplier = new Supplier();

    	$params['supplier_number'] = Help::getNumber(51);
    	$supplierResult = $supplier->addSupplier($params);
    
    	$this->outPut($supplierResult);
    }
    /**
     * 获取供应商数据
     * 胡
     */
    public function getSupplier(){

    	$params = $this->input();
    	$supplier = new Supplier();
    	/*
    	 if(isset($params['lang'])){
    	 $lang = $params['lang'];
    	 }else{
    	 $lang = [];
    	 }
    	*/
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $supplier->getNewSupplier($params, true);
            $result = $supplier->getNewSupplier($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
    	$supplierResult = $supplier->getNewSupplier($params);
    	$this->outPut($supplierResult);
    
    }

    /**
     * getOneSupplier
     *
     * 根据id获取一条供应商信息
     * @author shj
     * @return void
     * Date: 2019/3/20
     * Time: 15:26
     */
    public function getOneSupplier(){
        $params = $this->input();
        $supplier = new Supplier();

        $supplierResult = $supplier->getOneSupplier($params['supplier_id']);
        $this->outPut($supplierResult);
    }

    /**
     * getOneSupplier
     *
     * 根据数组获取一条供应商信息
     * @author shj
     * @return void
     * Date: 2019/3/20
     * Time: 15:26
     */
    public function getOneSupplierByArr(){
        $where = $this->input();
        $params1['company_id'] = $where['company_id'];
        $params1['supplier_name'] = $where['company_name'];
        $params1['status'] = 1;
        $params1['is_company'] = 1;
        $params1['supplier_type_id'] = 1;
        $supplier = new Supplier();
        $supplierResult = $supplier->getOneSupplierByParams($params1);
        $this->outPut($supplierResult);
    }

    /**
     * 修改供应商
     * 胡
     */
    
    public function updateSupplierBySupplierId(){
    	$params = $this->input();
        $supplier = new Supplier();
    	$supplierInfo = $supplier->getOneSupplier($params['supplier_id']);
        //重复性验证
    	if($supplierInfo['supplier_name'] == $params['supplier_name']){
        }else{
            //开始判断名字是否重复
            $data = [
                'supplier_name'=>$params['supplier_name'],
                'company_id'=>$params['choose_company_id'],
            ];
            $this->checkNameIsRepetition('supplier',$data);
            //结束判断名字重复
        }

    	$paramRule = [
 			'supplier_id' => 'string',
    		'user_id'=>'string',
    	];
    	$this->paramCheckRule($paramRule,$params);

    	$supplierResult = $supplier->updateSupplierBySupplierId($params);
    	$this->outPut($supplierResult);
    
    
    }    
    /**
     * 获取供应商多语言
     */
    public function getSupplierLanguage(){
    	$params = $this->input();
    	$paramRule = [
    		'supplier_number'=>'string',
    				
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$language_data['status']= 1;
    	$language = new Language();
    	$language_result = $language->getLanguage($language_data);
    	$supplier = new Supplier();
    	for($i=0;$i<count($language_result);$i++){
    		$params_language['lang_id'] = $language_result[$i]['language_id'];
    		$params_language['supplier_number'] = $params['supplier_number'];
    		$language_result[$i]['language_info'] = $supplier->getSupplierBySupplierIdLangId($params_language);
    	}
    	$this->outPut($language_result);
    
    }
    
    /**
     * 修改供应商多语言
     */
    public function updateSupplierLanguageBySupplierLanguageId(){
    
    	$params = $this->input();
    	$paramRule = [
    		'data'=>'array',
    		'user_id'=>'number',
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    
    	$supplier = new Supplier();
    	$supplier_result = $supplier->updateSupplierLanguageBySupplierLanguageId($params);
    
    	$this->outPut($supplier_result);
    }    
    /**
     * 添加导游数据
     * 胡
     */
    public function addTourGuide(){
    
    	$params = $this->input();
    
    	$paramRule = [
    		'supplier_id'=>'number',
//    		'company_id'=>'number',
    		'tour_guide_name' => 'string',


			'normal_price'=>'number',
    		'normal_settlement_price'=>'number',
            'payment_currency_type'=>'number',
    	
    		'user_id'=>'number',
    		'status'=>'number'	
    	];
    
    	$this->paramCheckRule($paramRule,$params);


        $data = [
            'tour_guide_name'=>$params['tour_guide_name'],
            'supplier_id'=>$params['supplier_id'],
        ];
        $this->checkNameIsRepetition('tour_guide',$data);


    	$supplier = new TourGuide();
    
    	$params['source_number'] = Help::getNumber(59);
    	 
    	$supplierResult = $supplier->addTourGuide($params);
    
    	$this->outPut($supplierResult);
    }
    /**
     * 获取导游数据
     * 胡
     */
    public function getTourGuide(){
    	$params = $this->input();
    
    	$supplier = new TourGuide();
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $supplier->getTourGuide($params, true);
            $result = $supplier->getTourGuide($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
    	$supplierResult = $supplier->getTourGuide($params);
    	$this->outPut($supplierResult);
    
    }
    
    /**
     * 修改导游
     * 胡
     */
    
    public function updateTourGuideByTourGuideId(){
    	$params = $this->input();

        $supplier = new TourGuide();
        $Info = $supplier->getOneTourGuide($params['tour_guide_id']);
        if($Info['tour_guide_name'] == $params['tour_guide_name']){
        }else{

            //开始判断名字是否重复
            $data = [
                'tour_guide_name'=>$params['tour_guide_name'],
                'supplier_id'=>$Info['supplier_id'],
            ];
            $this->checkNameIsRepetition('tour_guide',$data);
            //结束判断名字重复
        }

    	$paramRule = [
    		'tour_guide_id' => 'string',
    		'user_id'=>'string',
    	];
    	 
    	$this->paramCheckRule($paramRule,$params);

    	$supplierResult = $supplier->updateTourGuideByTourGuideId($params);
    	$this->outPut($supplierResult);
    
    
    }
    /**
     * 获取导游-多语言
     */
    public function getTourGuideLanguage(){
    
    	$params = $this->input();
    	$paramRule = [
    		'source_number'=>'string',
    
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$language_data['status']= 1;
    	$language = new Language();
    	$language_result = $language->getLanguage($language_data);
    	$tour_guide = new TourGuide();
    	for($i=0;$i<count($language_result);$i++){
    		$params_language['lang_id'] = $language_result[$i]['language_id'];
    		$params_language['source_number'] = $params['source_number'];
    		$language_result[$i]['language_info'] = $tour_guide->getTourGuideByTourGuideIdLangId($params_language);
    		
    	}
    	$this->outPut($language_result);
    
    }
    
    /**
     * 修改导游 多语言
     */
    public function updateTourGuideLanguageByTourGuideLanguageId(){
    
    
    	$params = $this->input();
    	$paramRule = [
    			'data'=>'array',
    			'user_id'=>'number',
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    
    	$source = new TourGuide();
    	$source_result = $source->updateTourGuideLanguageByTourGuideLanguageId($params);
    		
    
    	$this->outPut($source_result);
    }    
    /**
     * 添加车辆数据
     * 胡
     */
    public function addVehicle(){
    
    	$params = $this->input();
    
    	$paramRule = [
    		'vehicle_name' => 'string',
    		'supplier_id'=>'number',

//    		'company_id'=>'number',
    		'normal_price'=>'number',
    		'normal_settlement_price'=>'number',
            'payment_currency_type'=>'number',
    		
    		'user_id'=>'number',
    		'status'=>'number'
    	];
    
    	$this->paramCheckRule($paramRule,$params);
        //重复性校验
        $data = [
            'vehicle_name'=>$params['vehicle_name'],
            'supplier_id'=>$params['supplier_id'],
        ];
        $this->checkNameIsRepetition('vehicle',$data);

    	$supplier = new Vehicle();
    
    
    	$params['source_number'] = Help::getNumber(58);
    	$supplierResult = $supplier->addVehicle($params);
    
    	$this->outPut($supplierResult);
    }
    /**
     * 获取车辆数据
     * 胡
     */
    public function getVehicle(){
    
    
    	$params = $this->input();
    
    	$supplier = new Vehicle();
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $supplier->getVehicle($params, true);
            $result = $supplier->getVehicle($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
    	$supplierResult = $supplier->getVehicle($params);
    	$this->outPut($supplierResult);
    
    }
    
    /**
     * 修改车辆
     * 胡
     */
    
    public function updateVehicleByVehicleId(){
    	$params = $this->input();
        $supplier = new Vehicle();
        $vehicleInfo = $supplier->getOneVehicle($params['vehicle_id']);
        if($vehicleInfo['vehicle_name'] == $params['vehicle_name']){
        }else{

            //开始判断名字是否重复
            $data = [
                'vehicle_name'=>$params['vehicle_name'],
                'supplier_id'=>$vehicleInfo['supplier_id'],
            ];
            $this->checkNameIsRepetition('vehicle',$data);
            //结束判断名字重复
        }

    	$paramRule = [
    		'vehicle_id' => 'string',
    		'user_id'=>'string',
    
    	];
    
    	$this->paramCheckRule($paramRule,$params);

    	$supplierResult = $supplier->updateVehicleByVehicleId($params);
    	$this->outPut($supplierResult);
    
    
    }	
    /**
     * 获取车辆-多语言
     */
    public function getVehicleLanguage(){
    
    	$params = $this->input();
    	$paramRule = [
    		'source_number'=>'string',
    
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$language_data['status']= 1;
    	$language = new Language();
    	$language_result = $language->getLanguage($language_data);
    	$vehicle = new Vehicle();
    	for($i=0;$i<count($language_result);$i++){
    		$params_language['lang_id'] = $language_result[$i]['language_id'];
    		$params_language['source_number'] = $params['source_number'];
    		$language_result[$i]['language_info'] = $vehicle->getVehicleByVehicleIdLangId($params_language);
    	}
    	$this->outPut($language_result);
    
    }
    
    /**
     * 修改车辆 多语言
     */
    public function updateVehicleLanguageByVehicleLanguageId(){
    
    
    	$params = $this->input();
    	$paramRule = [
    		'data'=>'array',
    		'user_id'=>'number',
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    
    	$source = new Vehicle();
    	$source_result = $source->updateVehicleLanguageByVehicleLanguageId($params);
    							  
    
    	$this->outPut($source_result);
    }    
    /**
     * 添加景点数据
     * 胡
     */
    public function addScenicSpot(){
    
    	$params = $this->input();
    
    	$paramRule = [
    		'scenic_spot_name' => 'string',
    		'supplier_id'=>'number',
//    		'country_id'=>'number',
//    		'company_id'=>'number',
    		'normal_price'=>'number',
    		'normal_settlement_price'=>'number',
            'payment_currency_type'=>'number',
    		
    		'user_id'=>'number',
    		'status'=>'number'
    	];
    
    	$this->paramCheckRule($paramRule,$params);

        //重复性校验
        $data = [
            'scenic_spot_name'=>$params['scenic_spot_name'],
            'supplier_id'=>$params['supplier_id'],
        ];
        $this->checkNameIsRepetition('scenic_spot',$data);
    
    	$supplier = new ScenicSpot();
    
    
    	$params['source_number'] = Help::getNumber(57);
    	$supplierResult = $supplier->addScenicSpot($params);
    
    	$this->outPut($supplierResult);
    }
    /**
     * 获取景点数据
     * 胡
     */
    public function getScenicSpot(){

    	$params = $this->input();
    
    	$supplier = new ScenicSpot();
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $supplier->getScenicSpot($params, true);
            $result = $supplier->getScenicSpot($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];
            return $this->output($data);
        }
    	$supplierResult = $supplier->getScenicSpot($params);
    	$this->outPut($supplierResult);
    
    }
    
    /**
     * 修改景点
     * 胡
     */
    
    public function updateScenicSpotByScenicSpotId(){
    	$params = $this->input();
        $supplier = new ScenicSpot();
        $scenicSpotInfo = $supplier->getOneScenicSpot($params['scenic_spot_id']);
        if($scenicSpotInfo['scenic_spot_name'] == $params['scenic_spot_name']){
        }else{

            //开始判断名字是否重复
            $data = [
                'scenic_spot_name'=>$params['scenic_spot_name'],
                'supplier_id'=>$scenicSpotInfo['supplier_id']
            ];
            $this->checkNameIsRepetition('scenic_spot',$data);
            //结束判断名字重复
        }

    	$paramRule = [
    			'scenic_spot_id' => 'string',
    			'user_id'=>'string',
    	];
    
    	$this->paramCheckRule($paramRule,$params);

    	$supplierResult = $supplier->updateScenicSpotByScenicSpotId($params);
    	$this->outPut($supplierResult);
    
    
    }  
    /**
     * 获取景点-多语言
     */
    public function getScenicSpotLanguage(){
    
    	$params = $this->input();
    	$paramRule = [
    		'source_number'=>'string',
    
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$language_data['status']= 1;
    	$language = new Language();
    	$language_result = $language->getLanguage($language_data);
    	$scenic_spot = new ScenicSpot();
    	for($i=0;$i<count($language_result);$i++){
    		$params_language['lang_id'] = $language_result[$i]['language_id'];
    		$params_language['source_number'] = $params['source_number'];
    		$language_result[$i]['language_info'] = $scenic_spot->getScenicSpotByScenicSpotIdLangId($params_language);
    	}
    	$this->outPut($language_result);
    
    }
    
    /**
     * 修改景点 多语言
     */
    public function updateScenicSpotLanguageByScenicSpotLanguageId(){
    
    
    	$params = $this->input();
    	$paramRule = [
    			'data'=>'array',
    			'user_id'=>'number',
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    
    	$source = new ScenicSpot();
    	$source_result = $source->updateScenicSpotLanguageByScenicSpotLanguageId($params);
    
    	$this->outPut($source_result);
    }    
    /**
     * 添加签证数据
     * 胡
     */
    public function addVisa(){
    
    	$params = $this->input();
    
    	$paramRule = [
    		'visa_name' => 'string',
    		'supplier_id'=>'number',
//    		'company_id'=>'number',
    		'user_id'=>'number',
    		'status'=>'number',
    		'normal_price'=>'number',
    		'normal_settlement_price'=>'number',
            'payment_currency_type'=>'number',
    		//'normal_retail_price'=>'number'
    	];
    
    	$this->paramCheckRule($paramRule,$params);

        //重复性校验
        $data = [
            'visa_name'=>$params['visa_name'],
            'supplier_id'=>$params['supplier_id'],
        ];
        $this->checkNameIsRepetition('visa',$data);
    
    	$supplier = new Visa();
    
    
    	$params['source_number'] = Help::getNumber(56);
    	$supplierResult = $supplier->addVisa($params);
    
    	$this->outPut($supplierResult);
    }
    /**
     * 获取签证数据
     * 胡
     */
    public function getVisa(){
    
    
    	$params = $this->input();
    
    	$supplier = new Visa();
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $supplier->getVisa($params, true);
            $result = $supplier->getVisa($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
    	$supplierResult = $supplier->getVisa($params);
    	$this->outPut($supplierResult);
    
    }
    
    /**
     * 修改签证
     * 胡
     */
    
    public function updateVisaByVisaId(){
    	$params = $this->input();
        $visa = new Visa();
        $visaInfo = $visa->getOneVisa($params['visa_id']);
        if($visaInfo['visa_name'] == $params['visa_name']){
        }else{

            //开始判断名字是否重复
            $data = [
                'visa_name'=>$params['visa_name'],
                'supplier_id'=>$visaInfo['supplier_id']
            ];
            $this->checkNameIsRepetition('visa',$data);
            //结束判断名字重复
        }

    	$paramRule = [
    		'visa_id' => 'string',
    		'user_id'=>'string',
    	];
    
    	$this->paramCheckRule($paramRule,$params);

    	$supplierResult = $visa->updateVisaByVisaId($params);
    	$this->outPut($supplierResult);
    
    
    }    
    /**
     * 获取签证-多语言
     */
    public function getVisaLanguage(){
    
    	$params = $this->input();
    	$paramRule = [
    			'source_number'=>'string',
    
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$language_data['status']= 1;
    	$language = new Language();
    	$language_result = $language->getLanguage($language_data);
    	$visa = new Visa();
    	for($i=0;$i<count($language_result);$i++){
    		$params_language['lang_id'] = $language_result[$i]['language_id'];
    		$params_language['source_number'] = $params['source_number'];
    		$language_result[$i]['language_info'] = $visa->getVisaByVisaIdLangId($params_language);
    	}
    	$this->outPut($language_result);
    
    }
    
    /**
     * 修改签证 多语言
     */
    public function updateVisaLanguageByVisaLanguageId(){
    
    
    	$params = $this->input();
    	$paramRule = [
    			'data'=>'array',
    			'user_id'=>'number',
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    
    	$source = new Visa();
    	$source_result = $source->updateVisaLanguageByVisaLanguageId($params);
    
    	$this->outPut($source_result);
    }    
    /**
     * 添加游轮数据
     * 胡
     */
    public function addCruise(){
    	
    	$params = $this->input();
    
    	$paramRule = [
    		'cruise_name' => 'string',
    		'supplier_id'=>'number',
    		'deck'=>'string',
//    		'company_id'=>'number',
    		'normal_price'=>'number',
    		'normal_settlement_price'=>'number',
            'payment_currency_type'=>'number',
    		
    		'user_id'=>'number',
    		'status'=>'number'
    	];
    
    	$this->paramCheckRule($paramRule,$params);

        //重复性校验
        $data = [
            'cruise_name'=>$params['cruise_name'],
            'supplier_id'=>$params['supplier_id'],
        ];
        $this->checkNameIsRepetition('cruise',$data);
    
    	$supplier = new Cruise();
    
    
    	$params['source_number'] = Help::getNumber(55);
    	$supplierResult = $supplier->addCruise($params);
    
    	$this->outPut($supplierResult);
    }
    /**
     * 获取游轮数据
     * 胡
     */
    public function getCruise(){
    
    
    	$params = $this->input();
    
    	$supplier = new Cruise();
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $supplier->getCruise($params, true);
            $result = $supplier->getCruise($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
    	$supplierResult = $supplier->getCruise($params);
    	$this->outPut($supplierResult);
    
    }
    
    /**
     * 修改游轮
     * 胡
     */
    
    public function updateCruiseByCruiseId(){
    	$params = $this->input();
        $cruise = new Cruise();
        $flightInfo = $cruise->getOneCruise($params['cruise_id']);
        if($flightInfo['cruise_name'] == $params['cruise_name']){
        }else{

            //开始判断名字是否重复
            $data = [
                'cruise_name'=>$params['cruise_name'],
                'supplier_id'=>$flightInfo['supplier_id']
            ];
            $this->checkNameIsRepetition('cruise',$data);
            //结束判断名字重复
        }

    	$paramRule = [
    		'cruise_id' => 'string',
    		'user_id'=>'string',
    
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    	$supplierResult = $cruise->updateCruiseByCruiseId($params);
    	$this->outPut($supplierResult);
    
    
    } 
    /**
     * 获取邮轮-多语言
     */
    public function getCruiseLanguage(){
    	 
    	$params = $this->input();
    	$paramRule = [
    		'source_number'=>'string',
    
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$language_data['status']= 1;
    	$language = new Language();
    	$language_result = $language->getLanguage($language_data);
    	$cruise = new Cruise();
    	for($i=0;$i<count($language_result);$i++){
    		$params_language['lang_id'] = $language_result[$i]['language_id'];
    		$params_language['source_number'] = $params['source_number'];
    		$language_result[$i]['language_info'] = $cruise->getCruiseByCruiseIdLangId($params_language);
    	}
    	$this->outPut($language_result);
    
    }
    
    /**
     * 修改邮轮 多语言
     */
    public function updateCruiseLanguageByCruiseLanguageId(){
    
    	 
    	$params = $this->input();
    	$paramRule = [
    			'data'=>'array',
    			'user_id'=>'number',
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    
    	$source = new Cruise();
    	$source_result = $source->updateCruiseLanguageByCruiseLanguageId($params);
    
    	$this->outPut($source_result);
    }    
    /**
     * 添加航班数据
     * 胡
     */
    public function addFlight(){
    
    	$params = $this->input();
    
    	$paramRule = [
    		'flight_number' => 'string',
//    		'airplane_type_name' => 'string',
    		'supplier_id'=>'number',
    		'shipping_space'=>'string',
    		'begin_country_id'=>'number',
    		'end_country_id'=>'number',
//			'company_id'=>'number',
    	
    		'normal_price'=>'number',
    		'normal_settlement_price'=>'number',
            'payment_currency_type'=>'number',
    		
    		'user_id'=>'number',
    		'status'=>'number'
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    	//重复性校验
    	$data = [
    		'flight_number'=>$params['flight_number'],
            'supplier_id'=>$params['supplier_id'],
    	];
    	$this->checkNameIsRepetition('flight',$data);
    
    	$supplier = new Flight();
    
    
    	$params['source_number'] = Help::getNumber(54);
    	$supplierResult = $supplier->addFlight($params);
    
    	$this->outPut($supplierResult);
    }
    /**
     * 获取航班数据
     * 胡
     */
    public function getFlight(){
    
    
    	$params = $this->input();
    
    	$supplier = new Flight();
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $supplier->getFlight($params, true);
            $result = $supplier->getFlight($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
    	$supplierResult = $supplier->getFlight($params);
    	$this->outPut($supplierResult);
    
    }
    
    /**
     * 修改航班
     * 胡
     */
    
    public function updateFlightByFlightId(){
    	$params = $this->input();

        $flight = new Flight();

        $flightInfo = $flight->getOneFlight($params['flight_id']);

        if($flightInfo['flight_number'] == $params['flight_number']){
        }else{
            //开始判断名字是否重复
            $data = [
                'flight_number'=>$params['flight_number'],
                'supplier_id'=>$params['supplier_id']
            ];
            $this->checkNameIsRepetition('flight',$data);
            //结束判断名字重复
        }

        $paramRule = [
            'flight_id' => 'string',
            'user_id'=>'string',

        ];

        $this->paramCheckRule($paramRule,$params);

    	$supplierResult = $flight->updateFlightByFlightId($params);
    	$this->outPut($supplierResult);
    
    
    } 
    /**
     * 获取航班-多语言
     */
    public function getFlightLanguage(){
    	
    	$params = $this->input();
    	$paramRule = [
    		'source_number'=>'string',
    
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$language_data['status']= 1;
    	$language = new Language();
    	$language_result = $language->getLanguage($language_data);
    	$flight = new Flight();
    	for($i=0;$i<count($language_result);$i++){
    		$params_language['lang_id'] = $language_result[$i]['language_id'];
    		$params_language['source_number'] = $params['source_number'];
    		$language_result[$i]['language_info'] = $flight->getFlightByFlightIdLangId($params_language);
    	}
    	$this->outPut($language_result);
    
    }
    
    /**
     * 修改航班 多语言
     */
    public function updateFlightLanguageByFlightLanguageId(){
    	           
    	
    	$params = $this->input();
    	$paramRule = [
    			'data'=>'array',
    			'user_id'=>'number',
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    
    	$source = new Flight();
    	$source_result = $source->updateFlightLanguageByFlightLanguageId($params);
    
    	$this->outPut($source_result);
    }    
    /**
     * 添加用餐数据
     * 胡
     */
    public function addDining(){
    
    	$params = $this->input();
    
    	$paramRule = [
    		'dining_name' => 'string',
    		'supplier_id'=>'number',
			'standard_type'=>'number',
    				
//    		'company_id'=>'number',
    		'normal_price'=>'number',
    		'normal_settlement_price'=>'number',
            'payment_currency_type'=>'number',
    	
    		'user_id'=>'number',
    		'status'=>'number'
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
//    	$data = [
//    		'dining_name'=>$params['dining_name'],
//            'supplier_id'=>$params['supplier_id'],
//    	];
//    	$this->checkNameIsRepetition('dining',$data);
    
    	$supplier = new Dining();
    
    
    	$params['source_number'] = Help::getNumber(53);
    	$supplierResult = $supplier->addDining($params);
    
    	$this->outPut($supplierResult);
    }
    /**
     * 获取用餐数据
     * 胡
     */
    public function getDining(){

    	$params = $this->input();

    	$supplier = new Dining();
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $supplier->getDining($params, true);
            $result = $supplier->getDining($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
    	$supplierResult = $supplier->getDining($params);
    	$this->outPut($supplierResult);
    
    }
    
    /**
     * 修改用餐
     * 胡
     */
    
    public function updateDiningByDiningId(){
    	$params = $this->input();
        $dining = new Dining();
//        $hotelInfo = $dining->getOneDining($params['dining_id']);
//
//        if($hotelInfo['dining_name'] == $params['dining_name']){
//        }else{
//            //开始判断名字是否重复
//            $data = [
//                'dining_name'=>$params['dining_name'],
//                'supplier_id'=>$params['supplier_id']
//            ];
//            $this->checkNameIsRepetition('dining',$data);
//            //结束判断名字重复
//        }

    	$paramRule = [
    		'dining_id' => 'string',
    		'user_id'=>'string',
    	];
    
    	$this->paramCheckRule($paramRule,$params);

    	$supplierResult = $dining->updateDiningByDiningId($params);
    	$this->outPut($supplierResult);
    
    
    }   
    /**
     * 获取用餐-多语言
     */
    public function getDiningLanguage(){
    	$params = $this->input();
    	$paramRule = [
    		'source_number'=>'string',
    
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$language_data['status']= 1;
    	$language = new Language();
    	$language_result = $language->getLanguage($language_data);
    	$dining = new Dining();
    	for($i=0;$i<count($language_result);$i++){
    		$params_language['lang_id'] = $language_result[$i]['language_id'];
    		$params_language['source_number'] = $params['source_number'];
    		$language_result[$i]['language_info'] = $dining->getDiningByDiningIdLangId($params_language);
    	}
    	$this->outPut($language_result);
    
    }
    
    /**
     * 修改用餐 多语言
     */
    public function updateDiningLanguageByDiningLanguageId(){
    
    	$params = $this->input();
    	$paramRule = [
    		'data'=>'array',
    		'user_id'=>'number',
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    
    	$source = new Dining();
    	$source_result = $source->updateDiningLanguageByDiningLanguageId($params);
    
    	$this->outPut($source_result);
    }    
    /**
     * 添加酒店数据
     * 胡
     */
    public function addHotel(){
    
    	$params = $this->input();
    
    	$paramRule = [
    		'room_name' => 'string',
    		'room_type' => 'number',
    		'supplier_id'=>'number',
//    		'company_id'=>'number',
    		'normal_price'=>'number',
    		'normal_settlement_price'=>'number',
            'payment_currency_type'=>'number',
    		
    		'user_id'=>'number',
    		'status'=>'number'
    			
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    	//开始判断名字是否重复
//    	$data = [
//    		'room_name'=>$params['room_name'],
//            'supplier_id'=>$params['supplier_id'],
//    	];
//    	$this->checkNameIsRepetition('hotel',$data);
    
    
    	$supplier = new Hotel();
    
    	$params['source_number'] = Help::getNumber(52);
    	$supplierResult = $supplier->addHotel($params);
    
    	$this->outPut($supplierResult);
    }
    /**
     * 获取酒店数据
     * 胡
     */
    public function getHotel(){
    
    
    	$params = $this->input();
    
    	$supplier = new Hotel();
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $supplier->getHotel($params, true);
            $result = $supplier->getHotel($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
    	$supplierResult = $supplier->getHotel($params);
    	
//     	$source_price = new SourcePrice();
//     	//开始去读source_price有没有数据 有则跳过没则添加默认
//     	for($i=0;$i<count($supplierResult);$i++){
    		
//     		$source_price_params = [
//     			'supplier_type_id'=>2,
//     			'pk_id'=>$supplierResult[$i]['hotel_id']	
//     		];
    		
//     		$source_price_result = $source_price->getSourcePirce($source_price_params);
//     		if(!empty($source_price_result)){
//     			continue;
//     		}else{
    			
//     			$source_price_params = [
//     				'supplier_type_id'=>2,
//     				'pk_id'=>$supplierResult[$i]['hotel_id'],
//     				'payment_currency_type'=>1,
//     				'normal_price'=>0,
//     				'normal_settlement_price'=>0	
    					
//     			];
//     			$source_price->addSourcePirce($source_price_params);
//     		}
    		
//     	}
    	
//     	//error_log(print_r(help::modelDataToArr($supplierResult),1));
//     	exit();
    	
    	$this->outPut($supplierResult);
    
    }
    
    /**
     * 修改酒店
     * 胡
     */
    
    public function updateHotelByHotelId(){
    	$params = $this->input();
    	$hotel = new Hotel();

        //$hotelInfo = $hotel->getOneHotel($params['hotel_id']);
        //error_log(print_r(Help::modelDataToArr($hotelInfo),1));
        //error_log(print_r(Help::modelDataToArr($params),1));
        //重复性验证
//        if($hotelInfo['room_name'] == $params['room_name']){
//        }else{
//            //开始判断名字是否重复
//            $data = [
//                'room_name'=>$params['room_name'],
//                'supplier_id'=>$params['supplier_id']
//            ];
//            $this->checkNameIsRepetition('hotel',$data);
//            //结束判断名字重复
//        }
        $paramRule = [
            'hotel_id' => 'string',
            'user_id'=>'string',
        ];

        $this->paramCheckRule($paramRule,$params);

        $hotelResult = $hotel->updateHotelByHotelId($params);
    	$this->outPut($hotelResult);
    
    
    } 
    /**
     * 获取酒店-多语言
     */
    public function getHotelLanguage(){
    	$params = $this->input();
    	$paramRule = [
    		'source_number'=>'string',
    
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$language_data['status']= 1;
    	$language = new Language();
    	$language_result = $language->getLanguage($language_data);
    	$hotel = new Hotel();
    	for($i=0;$i<count($language_result);$i++){
    		$params_language['lang_id'] = $language_result[$i]['language_id'];
    		$params_language['source_number'] = $params['source_number'];
    		$language_result[$i]['language_info'] = $hotel->getHotelByHotelIdLangId($params_language);
    	}
    	$this->outPut($language_result);
    
    }
    
    /**
     * 修改酒店 多语言
     */
    public function updateHotelLanguageByHotelLanguageId(){
    
    	$params = $this->input();
    	$paramRule = [
    		'data'=>'array',
    		'user_id'=>'number',
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    
    	$source = new Hotel();
    	$source_result = $source->updateHotelLanguageByHotelLanguageId($params);
    
    	$this->outPut($source_result);
    }    
    /**
     * 添加单项资源数据
     * 胡
     */
    public function addSingleSource(){
    
    	$params = $this->input();
    	$paramRule = [
    		'single_source_name' => 'string',
    		'supplier_id'=>'number',
//    		"company_id"=>'number',
    		'normal_price'=>'number',
    		'normal_settlement_price'=>'number',
            'payment_currency_type'=>'number',
    		'user_id'=>'number',
    		'status'=>'number'
    
    	];
    	$this->paramCheckRule($paramRule,$params);
        //重复性校验
        $data = [
            'single_source_name'=>$params['single_source_name'],
            'supplier_id'=>$params['supplier_id'],
        ];
        $this->checkNameIsRepetition('single_source',$data);
    
    	$supplier = new SingleSource();
    
    	$params['source_number'] = Help::getNumber(60);
    	$supplierResult = $supplier->addSingleSource($params);
    
    	$this->outPut($supplierResult);
    }
    /**
     * 获取单项资源数据
     * 胡
     */
    public function getSingleSource(){
    
    
    	$params = $this->input();
    
    	$supplier = new SingleSource();
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $supplier->getSingleSource($params, true);
            $result = $supplier->getSingleSource($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
    	$supplierResult = $supplier->getSingleSource($params);
    	$this->outPut($supplierResult);
    
    }
    
    /**
     * 修改单项资源
     * 胡
     */
    
    public function updateSingleSourceBySingleSourceId(){
    	$params = $this->input();
        $supplier = new SingleSource();
        $singleSourceInfo = $supplier->getOneSingleSource($params['single_source_id']);

        if($singleSourceInfo['single_source_name'] == $params['single_source_name']){
        }else{
            //开始判断名字是否重复
            $data = [
                'single_source_name'=>$params['single_source_name'],
                'supplier_id'=>$singleSourceInfo['supplier_id']
            ];
            $this->checkNameIsRepetition('single_source',$data);
            //结束判断名字重复
        }

    	$paramRule = [
    		'single_source_id' => 'string',
    		'user_id'=>'string',
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    	$supplierResult = $supplier->updateSingleSourceBySingleSourceId($params);
    	$this->outPut($supplierResult);

    }
    /**
     * 获取单项资源-多语言
     */
    public function getSingleSourceLanguage(){
    
    	$params = $this->input();
    	$paramRule = [
    			'source_number'=>'string',
    
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$language_data['status']= 1;
    	$language = new Language();
    	$language_result = $language->getLanguage($language_data);
    	$single_source = new SingleSource();
    	for($i=0;$i<count($language_result);$i++){
    		$params_language['lang_id'] = $language_result[$i]['language_id'];
    		$params_language['source_number'] = $params['source_number'];
    		$language_result[$i]['language_info'] = $single_source->getSingleSourceBySingleSourceIdLangId($params_language);
    
    	}
    	$this->outPut($language_result);
    
    }
    
    /**
     * 修改单项资源 多语言
     */
    public function updateSingleSourceLanguageBySingleSourceLanguageId(){
    
    
    	$params = $this->input();
    	$paramRule = [
    		'data'=>'array',
    		'user_id'=>'number',
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    
    	$source = new SingleSource();
    	$source_result = $source->updateSingleSourceLanguageBySingleSourceLanguageId($params);
    
    
    	$this->outPut($source_result);
    }    
    /**
     * 添加自费项目
     * 王
     */
    public function addOwnExpense(){

        $params = $this->input();

        $paramRule = [
            'supplier_id'=>'number',
            'own_expense_name'=>'string',
//            'company_id'=>'number',
            'normal_price'=>'number',
            'normal_settlement_price'=>'number',
            'payment_currency_type'=>'number',
            'user_id'=>'number',
            'status'=>'number'
        ];
        $this->paramCheckRule($paramRule,$params);

        //重复性校验
        $data = [
            'own_expense_name'=>$params['own_expense_name'],
            'supplier_id'=>$params['supplier_id'],
        ];
        $this->checkNameIsRepetition('own_expense',$data);

        $supplier = new OwnExpense();

        $params['source_number'] = Help::getNumber(61);
        $supplierResult = $supplier->addOwnExpense($params);

        $this->outPut($supplierResult);
    }
    /**
     * 获取自费数据
     * 王
     */
    public function getOwnExpense(){


        $params = $this->input();

        $supplier = new OwnExpense();
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $supplier->getOwnExpense($params, true);
            $result = $supplier->getOwnExpense($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
        $supplierResult = $supplier->getOwnExpense($params);
        $this->outPut($supplierResult);

    }
    /**
     * 修改自费项目
     * 王
     */

    public function updateOwnExpenseByOwnExpenseId(){
        $params = $this->input();
        $supplier = new OwnExpense();
        $paramRule = [
            'own_expense_id' => 'string',
            'user_id'=>'string',

        ];
        $this->paramCheckRule($paramRule,$params);

        $ownExpenseInfo = $supplier->getOneOwnExpense($params['own_expense_id']);

        if($ownExpenseInfo['own_expense_name'] == $params['own_expense_name']){
        }else{
            //开始判断名字是否重复
            $data = [
                'own_expense_name'=>$params['own_expense_name'],
                'supplier_id'=>$ownExpenseInfo['supplier_id']
            ];
            $this->checkNameIsRepetition('own_expense',$data);
            //结束判断名字重复
        }

        $supplierResult = $supplier->updateOwnExpenseByOwnExpenseId($params);
        $this->outPut($supplierResult);


    }
    /**
     * 获取自费项目-多语言
     */
    public function getOwnExpenseLanguage(){
    
    	$params = $this->input();
    	$paramRule = [
    		'source_number'=>'string',
    
    	];
    	$this->paramCheckRule($paramRule,$params);
    	$language_data['status']= 1;
    	$language = new Language();
    	$language_result = $language->getLanguage($language_data);
    	$own_expense = new OwnExpense();
    	for($i=0;$i<count($language_result);$i++){
    		$params_language['lang_id'] = $language_result[$i]['language_id'];
    		$params_language['source_number'] = $params['source_number'];
    		$language_result[$i]['language_info'] = $own_expense->getOwnExpenseByOwnExpenseIdLangId($params_language);
    
    	}
    	$this->outPut($language_result);
    
    }
    
    /**
     * 修改自费项目- 多语言
     */
    public function updateOwnExpenseLanguageByOwnExpenseLanguageId(){
    
    
    	$params = $this->input();
    	$paramRule = [
    			'data'=>'array',
    			'user_id'=>'number',
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    
    
    	$source = new OwnExpense();
    	$source_result = $source->updateOwnExpenseLanguageByOwnExpenseLanguageId($params);
    
    
    	$this->outPut($source_result);
    }
        
    /**
     * 获取地接信息根据供应商ID
     */
    public function getAgentBysupplierId(){
    	$params = $this->input();   	
    	$paramRule = [
    		'supplier_id' => 'number',
    		
    		'supplier_type_id'=>'number'	
    	];
   		
    	
    	$this->paramCheckRule($paramRule,$params);    
    	$supplier_type_id = $params['supplier_type_id'];

    	$data = [
    		"show_agent"=>1,
    		'supplier_id'=>$params['supplier_id'],
    		'status'=>1
    	];
    	if($supplier_type_id == 2){
    		$hotel = new  Hotel();

    		$supplierResult = $hotel->getHotel($data);
    	
    	}
    	if($supplier_type_id==3){
    		$dining = new  Dining();
    		
    		$supplierResult = $dining->getDining($data);     		 
    	}
    	if($supplier_type_id==4){
    		$flight = new  Flight();
    		
    		$supplierResult = $flight->getFlight($data);    		 
    	}
    	if($supplier_type_id==5){
    		$cruise = new  Cruise();
    		
    		$supplierResult = $cruise->getCruise($data);    		 
    	}
    	
    	if($supplier_type_id==6){
    		$visa = new  Visa();
    		
    		$supplierResult = $visa->getVisa($data);
    	}
    	if($supplier_type_id==7){
    		$scenic_spot = new  ScenicSpot();
    		
    		$supplierResult = $scenic_spot->getScenicSpot($data);
    	}
    	if($supplier_type_id==8){
    		$vehicle = new  Vehicle();
    		
    		$supplierResult = $vehicle->getVehicle($data);
    	}
    	if($supplier_type_id==9){
    		$tour_guide = new  TourGuide();
    		
    		$supplierResult = $tour_guide->getTourGuide($data);
    	}  	
    	$this->outPut($supplierResult);
    	
    }
	
    /**
     * 获取资源
     */
    public function getSource(){
    	$params = $this->input();
    	
    	if(!empty($params['source_number'])){
    		$source_params['source_number'] = $params['source_number'];
    	}
    	if(!empty($params['source_name'])){
    		$source_params['source_name'] = $params['source_name'];
    	}
    	if(!empty($params['supplier_type_id'])){
    		$source_params['supplier_type_id'] = $params['supplier_type_id'];
    	}
    	if(!empty($params['supplier_name'])){
    		$source_params['supplier_name'] = $params['supplier_name'];
    	}
    		
    	$source_params['company_id'] = $params['user_company_id'];
    	$source_params['is_branch_product'] =  $params['is_branch_product'];
    	
		if(!empty($params['supplier_type_id'])){
			
			$result= $this->_source_service->getSourceInfo($source_params);
		}else{
			$result = [];
			$source_params['supplier_type_id'] = 2;
			$k = $this->_source_service->getSourceInfo($source_params);
			if(count($k)>0){
				$result = array_merge($result,$k);
			}
			
			$source_params['supplier_type_id'] = 3;
			$k = $this->_source_service->getSourceInfo($source_params);
			if(count($k)>0){
				$result = array_merge($result,$k);
			}
			$source_params['supplier_type_id'] = 4;
			$k = $this->_source_service->getSourceInfo($source_params);
			if(count($k)>0){
				$result = array_merge($result,$k);
			}
			$source_params['supplier_type_id'] = 5;
					$k = $this->_source_service->getSourceInfo($source_params);
			if(count($k)>0){
				$result = array_merge($result,$k);
			}
			$source_params['supplier_type_id'] = 6;
			$k = $this->_source_service->getSourceInfo($source_params);
			if(count($k)>0){
				$result = array_merge($result,$k);
			}
			$source_params['supplier_type_id'] = 7;
			$k = $this->_source_service->getSourceInfo($source_params);
			if(count($k)>0){
				$result = array_merge($result,$k);
			}
			$source_params['supplier_type_id'] = 8;
			$k = $this->_source_service->getSourceInfo($source_params);
			if(count($k)>0){
				$result = array_merge($result,$k);
			}
			$source_params['supplier_type_id'] = 9;
			$k = $this->_source_service->getSourceInfo($source_params);
			if(count($k)>0){
				$result = array_merge($result,$k);
			}
			$source_params['supplier_type_id'] = 10;
			$k = $this->_source_service->getSourceInfo($source_params);
			if(count($k)>0){
				$result = array_merge($result,$k);
			}
			$source_params['supplier_type_id'] = 11;
			$k = $this->_source_service->getSourceInfo($source_params);
			if(count($k)>0){
				$result = array_merge($result,$k);
			}

			
			
			
		}
		$this->outPut($result);
    }
    
    
    /**
     * 获取全部资源
     * 
     */
    public function getAllSouce(){
    	$params = $this->input();
    	$company_id = $params['user_company_id'];
    	$data['company_id'] = $company_id;
    	//获取资源
//     	$this->_hotel = new Hotel();
//     	$this->_dining = new Dining();
//     	$this->_flight = new Flight();
//     	$this->_cruise = new Cruise();
//     	$this->_visa = new Visa();
//     	$this->_scenic_spot = new ScenicSpot();
//     	$this->_vehicle = new Vehicle();
//     	$this->_tour_guide = new TourGuide();
//     	$this->_single_source = new SingleSource();
//     	$this->_own_expense = new OwnExpense();
    	$result = [];
    	$result['hotel'] = $this->_hotel->getHotel($data);
    	$result['dining'] = $this->_dining->getDining($data);
    	$result['flight'] = $this->_flight->getFlight($data);
    	$result['cruise'] = $this->_cruise->getCruise($data);
    	$result['visa'] = $this->_visa->getVisa($data);
    	$result['scenic_spot'] = $this->_scenic_spot->getScenicSpot($data);
    	$result['vehicle'] = $this->_vehicle->getVehicle($data);
    	$result['tour_guide'] = $this->_tour_guide->getTourGuide($data);
    	$result['single_source'] = $this->_single_source->getSingleSource($data);
     	$result['own_expense'] = $this->_own_expense->getOwnExpense($data);
    	$this->outPut($result);
    }

    /**
     * getShopping
     *
     * 获取购物店列表
     * @author shj
     * @return void
     * Date: 2019/4/4
     * Time: 17:51
     */
    public function getShopping(){
        $params = $this->input();
        $supplier = new Shopping();
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $supplier->getShopping($params, true);
            $result = $supplier->getShopping($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
        $supplierResult = $supplier->getShopping($params);
        $this->outPut($supplierResult);
    }

    /**
     * getOneShopping
     *
     * 获取购物店详情
     * @author shj
     * @return void
     * Date: 2019/4/4
     * Time: 17:53
     */
    public function getOneShopping(){

        $params = $this->input();
        $supplier = new Shopping();
        $supplierResult = $supplier->getOneShopping($params);
        $this->outPut($supplierResult);
    }

    /**
     * addShopping
     *
     * 新增购物店
     * @author shj
     * @return void
     * Date: 2019/4/4
     * Time: 17:52
     */
    public function addShopping(){
        $params = $this->input();
        $paramRule = [
            'shopping_name' => 'string',
            'supplier_id'=>'number',
            'shopping_type'=>'number',
            'user_id'=>'number',
            'status'=>'number'
        ];
        $this->paramCheckRule($paramRule,$params);
        //重复性校验
        $data = [
            'shopping_name'=>$params['shopping_name'],
            'supplier_id'=>$params['supplier_id'],
        ];
        $this->checkNameIsRepetition('shopping',$data);

        $supplier = new Shopping();

        $params['source_number'] = Help::getNumber(62);
        $supplierResult = $supplier->addShopping($params);

        $this->outPut($supplierResult);
    }

    public function editShopping(){
        $params = $this->input();
        $paramRule = [
            'shopping_name' => 'string',
            'supplier_id'=>'number',
            'shopping_type'=>'number',
            'user_id'=>'number',
            'status'=>'number'
        ];
        $this->paramCheckRule($paramRule,$params);
        //重复性校验
//        $data = [
//            'shopping_name'=>$params['shopping_name'],
//            'supplier_id'=>$params['supplier_id'],
//        ];
//        $this->checkNameIsRepetition('shopping',$data);

        $supplier = new Shopping();

        $supplierResult = $supplier->updateShopping($params);

        $this->outPut($supplierResult);
    }


    public function getSupplierAccount()
    {
        $params = $this->input();
        $paramRule = [
            'supplier_id'=>'number',
            'object_company_id'=>'number'
        ];

        $this->paramCheckRule($paramRule,$params);

        $supplier = new SupplierAccount();

        $data['supplier_account'] = $supplier->getSupplierAccount($params);

        $data['supplier_account_info'] = $supplier->getSupplierAccountInfo($params);

        $this->outPut($data);
    }

    public function addB2bHotel()
    {
        $params = $this->input();
        $paramRule = [
            'hotel_name_en'=>'string',
            'address_en'=>'string',
            'country_id'=>'number',
        ];

        $this->paramCheckRule($paramRule,$params);
        $b2b_hotel_model = new B2bHotel();
        $result = $b2b_hotel_model->addB2bHotel($params);
        $this->outPut($result);
    }


    public function getB2bHotel()
    {
        $params = $this->input();
        $b2b_hotel_model = new B2bHotel();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $b2b_hotel_model->getB2bHotel($params, true);
            $result = $b2b_hotel_model->getB2bHotel($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

        $result = $b2b_hotel_model->getB2bHotel($params);
        $this->outPut($result);
    }


    public function updateB2bHotel()
    {
        $params = $this->input();
        $paramRule = [
            'hotel_name_en'=>'string',
            'address_en'=>'string',
            'country_id'=>'number'
        ];

        $this->paramCheckRule($paramRule,$params);
        $b2b_hotel_model = new B2bHotel();
        $result = $b2b_hotel_model->updateB2bHotel($params);
        $this->outPut($result);
    }

}
