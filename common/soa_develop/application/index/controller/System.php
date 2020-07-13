<?php
namespace app\index\controller;
use app\common\help\Help;
use app\index\model\source\Supplier;
use app\index\model\system\RoomType;
use app\index\model\system\Season;
use app\index\model\system\TimeZone;
use think\config as thinkConfig;
use app\index\controller\Base;
use app\index\model\system\Currency;

use app\index\model\system\Language;
use app\index\model\system\Country;
use app\index\model\system\Company;
use app\index\model\system\Department;
use app\index\model\system\Job;
use app\index\model\system\Tag;
use app\index\model\system\User;
use app\index\model\system\SupplierType;
use app\index\model\system\EmailSms;
use app\index\model\SourceLevel;
use think\Model;
use app\common\help\Contents;
use app\index\model\system\ReturnReceipt;
use app\index\model\system\ReturnReceiptInfo;
use app\index\model\system\RouteType;
use app\index\model\system\EmailTemplate;
use app\index\model\system\Tax;
use app\index\model\system\BillTemplate;
use app\index\model\system\Role;
use app\index\model\system\Auth;
use app\index\model\system\AuthRole;

use app\index\model\product\TeamProduct;
use app\index\model\branchcompany\CompanyOrderCustomer;
use app\index\model\finance\Receivable;
use app\index\model\finance\ReceivableInfo;
use app\index\model\finance\Cope;
use app\index\service\ProportionService;
use app\index\model\system\CurrencyProportion;
use think\Controller;
class System extends Base
{
	private $_language;
	private $_company_order_customer;
	private $_team_product;
	private $_receivable;
	private $_receivable_info;
	private $_cope;
	private $_proportion_service;
	private $_currency_proportion;
	private $_user;
    //_lang Base里的属性，
    public function __construct()
    {
    	$this->_language = config("systom_setting")['language_default'];
    	$this->_company_order_customer = new CompanyOrderCustomer();
    	$this->_team_product = new TeamProduct();
    	$this->_receivable = new Receivable();
    	$this->_cope = new Cope();
    	$this->_receivable_info = new ReceivableInfo();
    	$this->_proportion_service = new ProportionService();
    	$this->_currency_proportion = new CurrencyProportion;
    	$this->_user = new User();
        parent::__construct();
        
    }
    /**
     * 添加语言数据
     * 胡
     */
    public function addLanguage(){

        $params = $this->input();

        $paramRule = [
            'language_name' => 'string',
            'user_id'=>'string',
        	'status'=>'number'
        ];
        $this->paramCheckRule($paramRule,$params);
		
        //开始判断名字是否重复
        $data = [
        	'language_name'=>$params['language_name'],
        ];
		$this->checkNameIsRepetition('language',$data);
        //结束判断名字重复

        
        $language = new Language();
        $languageResult = $language->addLanguage($params);

        $this->outPut($languageResult);
    }
    /**
     * 获取语言数据
     * 胡
     */
    public function getLanguage(){


        $params = $this->input();

        $language = new Language();
    	if(isset($params['page'])){
			$params['page_size'] =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
			$params['page'] = ($params['page']-1)*$params['page_size'];
			$count = $language->getLanguage($params, true);
			$result = $language->getLanguage($params,false);
			
			$data = [
				'count'=>$count,
				'list'=>$result,
				'page_count'=>ceil($count/$params['page_size'])
			];
		
			return $this->output($data);
		}
        $languageResult = $language->getLanguage($params);
        $this->outPut($languageResult);

    }

    /**
     * 修改语言
     * 胡
     */

    public function updateLanguageByLanguageId(){
        $params = $this->input();
        $language = new Language();

        $languageInfo = $language->getOneLanguage($params['language_id']);
        if($languageInfo['language_name'] == $params['language_name']){
        }else{
            //开始判断名字是否重复
            $data = [
                'language_name'=>$params['language_name'],
            ];
            $this->checkNameIsRepetition('language',$data);
            //结束判断名字重复
        }

        $paramRule = [
            'language_id' => 'string',
            'user_id'=>'string',
        ];
        $this->paramCheckRule($paramRule,$params);
        $languageResult = $language->updateLanguageByLanguageId($params);
        $this->outPut($languageResult);


    }
    /**
     * 添加货币数据
     * 胡
     */
    public function addCurrency(){

        $params = $this->input();

        $paramRule = [
            'currency_name' => 'string',
            'user_id'=>'string',
        	'status'=>'number'
        ];
        $this->paramCheckRule($paramRule,$params);

        //首先判断名字是否有重复
        $data = [
            'currency_name'=>$params['currency_name'],
        ];
        $this->checkNameIsRepetition('currency',$data);
        //结束判断名字重复

        $currency = new Currency();
        $currencyResult = $currency->addCurrency($params);

        $this->outPut($currencyResult);
    }
    /**
     * 获取货币
     * 胡
     */
    public function getCurrency(){

        $params = $this->input();
		

        $currency = new Currency();
		
        if(isset($params['page'])){
        	$page = ($params['page']-1)*$params['page_size'];
		
        	$page_size = $params['page_size'];      
        	$count = $currency->getCurrency($params, true);
        	$result = $currency->getCurrency($params,false,'true',$page,$page_size);   	 
        	$data = [
        		'count'=>$count,
        		'list'=>$result,
        		'page_count'=>ceil($count/$page_size)
        	];
        	
        	return $this->output($data);
        }
        
        $currencyResult = $currency->getCurrency($params,false);
		
		
		
        $this->outPut($currencyResult);
    }

    /**
     * 修改货币
     * 胡
     */
    public function updateCurrencyByCurrencyId(){
        $params = $this->input();
        $paramRule = [
            'currency_id' => 'string',
            'user_id'=>'string',
        ];
        $this->paramCheckRule($paramRule,$params);
        $currency = new Currency();
        $currencyInfo = $currency->getOneCurrency($params['currency_id']);
        if($currencyInfo['currency_name'] == $params['currency_name']){
        }else{
            //开始判断名字是否重复
            $data = [
                'currency_name'=>$params['currency_name'],
            ];
            $this->checkNameIsRepetition('currency',$data);
            //结束判断名字重复
        }

        $currencyResult = $currency->updateCurrencyByCurrencyId($params);
        $this->outPut($currencyResult);


    }





    /**
     * 添加国家/地区
     * 胡
     */
    public function addCountry(){
        $params = $this->input();
        $paramRule = [
            'country_name' => 'string',//国家名称
            'user_id'=>'string',
  
        	'pid'=>'number',
        	'level'=>'number'
        	

        ];
        
        $this->paramCheckRule($paramRule,$params);

        $data = [
            'country_name'=>$params['country_name'],
        ];
        $this->checkNameIsRepetition('country',$data);

        if($params['level'] == 1){//表明添加的是国家
        	if(empty($params['country_code'])){
        		\think\Response::create(['code' => '400', 'msg' => ' country_code is empty because add level 1'], 'json')->send();
        		exit();
        	}
        }else if($params['level'] == 2){
        	if(empty($params['pid']) || $params['pid'] == 0){
        		\think\Response::create(['code' => '400', 'msg' => ' pid is empty because add level 2'], 'json')->send();
        		exit();
        	}       	
        	
       }
//       else if($params['level'] == 3){
//        	if(empty($params['currency_id']) || empty($params['language_id']) || empty($params['timezone'])){
//        		\think\Response::create(['code' => '400', 'msg' => ' currency_id or language_id or timezone  is empty because add level 3'], 'json')->send();
//        		exit();
//        	}
//
//        }

        $country = new Country();
        $countryResult = $country->addCountry($params);
        $this->outPut($countryResult);
    }
    
    /**
     * 查询国家信息
     */
	public function getCountry(){
 		$params = $this->input();
 		$country = new Country();
 		if(isset($params['page'])){
 			$page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
 			$params['page'] = ($params['page']-1)*$page_size;
 			$count = $country->getCountry($params, true);
 			$result = $country->getCountry($params,false);
 			$data = [
 				'count'=>$count,
 				'list'=>$result,
 				'page_count'=>ceil($count/$page_size)
 			];
 		
 			return $this->output($data);
 		}
		$countryResult = $country->getCountry($params);
		$this->outPut($countryResult);
		
	}

    /**
     * getCountryCity
     *
     * 重写获取地区
     * @author shj
     * @return void
     * Date: 2019/3/4
     * Time: 16:17
     */
	public function getCountryCity(){
        $params = $this->input();

        $country = new Country();
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $params['page'] = ($params['page']-1)*$page_size;
            $count = $country->getCountryCity($params, true);
            $result = $country->getCountryCity($params,false);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];
            return $this->output($data);
        }
        $countryResult = $country->getCountryCity($params);
        $this->outPut($countryResult);
    }
	
	/**
	 * 查询国家相信信息 //之后放到service层
	 */
	public function getCountryInfo(){
		$params = $this->input();
		$country = new Country();
		$params_data = $params;
		$params['country_id'] = $params_data['country_id'];
		//获取了国家
		$countryResult = $country->getCountry($params);
		$province_data['pid'] = $countryResult[0]['country_id'];
		//获取省/市
		$countryResult['province'] = $country->getCountry($province_data);
		for($i=0;$i<count($countryResult['province']);$i++){
			$son_data['pid'] = $countryResult['province'][$i]['country_id'];
			$countryResult['province'][$i]['city'] = $country->getCountry($son_data);
		}
		$this->outPut($countryResult);
	
	}
	/**
	 * 通过城市ID获取该城市父级以及爷爷级的信息
	 */
	public function getCityTop(){
		$params = $this->input();

		
		$this->paramCheckRule($paramRule,$params);
		$country = new Country();
		$country_result = $country->getCityTop($params);
		$this->outPut($country_result);
	
	}	
	/**
	 * 通过省市ID获取该省市以及父级的信息
	 */
	public function getProvinceTop(){
		$params = $this->input();
		
		
		$this->paramCheckRule($paramRule,$params);
		$country = new Country();
		$country_result = $country->getProvinceTop($params);
		$this->outPut($country_result);		
		
	}
	/**
	 * 通过country_id修改国家信息
	 * 胡
	 */
	public function updateCountryByCountryId(){
		
		$params = $this->input();
		$paramRule = [
			'country_id'=>'number',
			'user_id' =>'string'
		];
		
		$this->paramCheckRule($paramRule,$params);
		
	
		$country = new Country();

        $Info = $country->getOneCountry($params['country_id']);
        if($Info['country_name'] == $params['country_name']){
        }else{
            //开始判断名字是否重复
            $data = [
                'country_name'=>$params['country_name'],
            ];
            $this->checkNameIsRepetition('country',$data);
            //结束判断名字重复
        }

		//这里先把status取出
		if(isset($params['status'])){
			$country_params = [
				'country_id'=>$params['country_id']
			];
			$params_statys = $params['status'];
			unset($params['status']);
			$countryLevel = $country->getCountry($country_params);
			$params['status'] = $params_statys;
		}


		//获取层级
		if(empty($countryLevel)){
			$this->outPutError(['msg'=>'data is null'],$params);
		}else{
			$level = $countryLevel[0]['level'];
			$params['level'] = $level;
		}
		
 		$countryResult = $country->updateCountryByCountryId($params);
 		$this->outPut($countryResult);
 

	}
	
	
	/**
	 * 获取多语言国家
	 */
	public function getCountryLanguage(){
		$params = $this->input();
		$paramRule = [
			'country_id'=>'number',
			
		];
		$this->paramCheckRule($paramRule,$params);
		$language_data['status']= 1;
		$language = new Language();
		$language_result = $language->getLanguage($language_data);		
		$country = new Country();
		for($i=0;$i<count($language_result);$i++){
			$params_language['lang_id'] = $language_result[$i]['language_id'];
			$params_language['country_id'] = $params['country_id'];
			$language_result[$i]['language_info'] = $country->getCountryByCountryIdLangId($params_language);
		}
		$this->outPut($language_result);
		
	}
	
	/**
	 * 修改国家多语言 
	 */
	public function updateCoungtryLanguageByCountryLanguageId(){
		
		$params = $this->input();
		$paramRule = [
			'data'=>'array',
			'user_id'=>'number',
		];
		
		$this->paramCheckRule($paramRule,$params);
		
		
		$country = new Country();
		$country_result = $country->updateCoungtryLanguageByCountryLanguageId($params);
		
		$this->outPut($country_result);
	}
	
	/**
	 * 添加公司
	 * 胡
	 */
	public function addCompany(){
		$params = $this->input();

		$paramRule = [
			'company_name' => 'string',//
			//'country_id'=>'number',
//			'linkman'=>'string',
//			'phone'=>'string',
			'currency_id'=>'string',
			'timezone'=>'string',
     		'language_id'=>'number',
//			'user_id'=>'string',
			'booking_identification'=>'string'
		];

		$this->paramCheckRule($paramRule,$params);

        //首先判断名字是否有重复
        $data = [
            'company_name'=>$params['company_name'],
        ];
        $this->checkNameIsRepetition('company',$data);
        //结束判断名字重复
        //开始判断名字是否重复
        $data = [
        	'booking_identification'=>$params['booking_identification'],
        ];
        $this->checkNameIsRepetition('company',$data);
		$company = new Company();
		$companyResult = $company->addCompany($params);
		$this->outPut($companyResult);
	}
	/**
	 * 获取公司
	 */
	public function getCompany(){
		$params = $this->input();
		$company = new Company();
		if(isset($params['page'])){
			$page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
			$params['page'] = ($params['page']-1)*$page_size;
			$count = $company->getCompany($params, true);
			$result = $company->getCompany($params,false);
			$data = [
					'count'=>$count,
					'list'=>$result,
					'page_count'=>ceil($count/$page_size)
			];
			return $this->output($data);
		}
		$companyResult = $company->getCompany($params);
		$this->outPut($companyResult);		
		
	}
	/**
	 * 修改公司根据公司ID
	 */
	public function updateCompanyByCompanyId(){
		$params = $this->input();
		$paramRule = [
			'company_id'=>'number',
			'user_id'=>'number',
			'currency_id'=>'string',
			'timezone'=>'string',
     		'language_id'=>'number',
			'booking_identification'=>'string'
		];
		$this->paramCheckRule($paramRule,$params);
        $company = new Company();
        $companyInfo = $company->getOneCompany($params['company_id']);
        if($companyInfo['company_name'] == $params['company_name']){
        }else{
            //开始判断名字是否重复
            $data = [
                'company_name'=>$params['company_name'],
            ];
            $this->checkNameIsRepetition('company',$data);
            //结束判断名字重复
        }
        
        if($companyInfo['booking_identification'] == $params['booking_identification']){
        }else{
        	//开始判断名字是否重复
        	$data = [
        			'booking_identification'=>$params['booking_identification'],
        	];
        	$this->checkNameIsRepetition('company',$data);
        	//结束判断名字重复
        }
        
        $params1['company_id'] = $params['company_id'];
        $params1['supplier_name'] = $companyInfo['company_name'];
//        $params1['is_company'] = 1;
        $params1['supplier_type_id'] = 1;
        $supplier = new Supplier();
        $supplierData = $supplier->getOneSupplierByParams($params1);

        $params['have_supplier'] = $supplierData ? 1 : 0;
        $params['is_company'] = $supplierData ? $supplierData['is_company'] : 0;
        $params['supplier_id'] = $supplierData ? $supplierData['supplier_id'] : 0;
		$companyResult = $company->updateCompanyByCompanyId($params);
		$this->outPut($companyResult);
		
	}
	/**
	 * 
	 * 添加部门
	 * 胡
	 */
	public function addDepartment(){
		$params = $this->input();
		$paramRule = [
			'department_name' => 'string',
		    'company_id'=>'number',
//			'linkman'=>'string',
//			'phone'=>'string',
			'user_id'=>'string',
		];
		$this->paramCheckRule($paramRule,$params);

        //首先判断名字是否有重复
        $data = [
            'department_name'=>$params['department_name'],
            'company_id'=>$params['company_id'],
        ];
        $this->checkNameIsRepetition('department',$data);
        //结束判断名字重复

		$department = new Department();
		$departmentResult = $department->addDepartment($params);
		$this->outPut($departmentResult);
		
		
	}
	
	/**
	 * 获取部门
	 * 胡
	 */
	public function getDepartment(){
		$params = $this->input();
		$department = new Department();
		if(isset($params['page'])){
			$page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
			$page = ($params['page']-1)*$page_size;
			$count = $department->getDepartment($params, true);
			$result = $department->getDepartment($params,false,'true',$page,$page_size);
			$data = [
					'count'=>$count,
					'list'=>$result,
					'page_count'=>ceil($count/$page_size)
			];
				
			return $this->output($data);
		}
		$departmentResult = $department->getDepartment($params);
		$this->outPut($departmentResult);
		
	}
	/**
	 * 修改部门
	 */
	public function updateDepartmentByDepartmentId(){
		$params = $this->input();
		$paramRule = [
			'department_id' => 'number',
			'user_id'=>'number'
		];
		$this->paramCheckRule($paramRule,$params);

		$department = new Department();
        $departmentInfo = $department->getOneDepartment($params['department_id']);
        if($departmentInfo['department_name'] == $params['department_name']){
        }else{
            //开始判断名字是否重复
            $data = [
                'department_name'=>$params['department_name'],
                'company_id'=>$params['company_id'],
            ];
            $this->checkNameIsRepetition('department',$data);
            //结束判断名字重复
        }


		$departmentResult = $department->updateDepartmentByDepartmentId($params);
		$this->outPut($departmentResult);
		
		
		
	}
	
	/**
	 * 增加职位
	 * 胡
	 */
	public function addJob(){
		$params = $this->input();
		$paramRule = [
			'job_name' => 'string',
			'department_id'=>'number',

			'user_id'=>'number',
		
		];
		
		$this->paramCheckRule($paramRule,$params);
		
		$job = new Job();
		$jobResult = $job->addJob($params);
		$this->outPut($jobResult);
		
		
	}
	/**
	 * 获取职位
	 * 胡
	 */
	public function getJob(){
		$params = $this->input();

		$job = new Job();
	
		if(isset($params['page'])){
			$params['page_size'] =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
			$params['page'] = ($params['page']-1)*$params['page_size'];
			$count = $job->getJob($params, true);
			$result = $job->getJob($params,false);
			$data = [
	
					'count'=>$count,
					'list'=>$result,
					'page_count'=>ceil($count/$params['page_size'])
		
		
	
			];
			return $this->output($data);
		}
		
		$jobResult = $job->getJob($params);
		$this->outPut($jobResult);		
		
	}
	
	/**
	 * 编辑职位
	 * 胡
	 */
	public function updateJobByJobId(){
		$params = $this->input();
		$paramRule = [
				
			'job_id'=>'number',
			'user_id'=>'number',
		
		];
		
		$this->paramCheckRule($paramRule,$params);
		
		$job = new Job();
		$jobResult = $job->updateJobByJobId($params);
		$this->outPut($jobResult);		
		
	}



	/**
	 * 添加超级配置
	 */
	public function AddSuperConfig(){
		$params = $this->input();
	
		$paramRule = [
				'super_config_name' => 'string',
				'user_id'=>'string',
				'status'=>'number',
				
  
		];
	
		$this->paramCheckRule($paramRule,$params);
		$super = new SuperConfig();
		$superResult = $super->addSuperConfig($params);
		$this->outPut($superResult);

	}
	
	/**
	 * 获取超级配置
	 */
	public function getSuperConfig(){
		$params = $this->input();

		$super = new SuperConfig();
		$superResult = $super->getSuperConfig($params);
		$this->outPut($superResult);
	
	}	
	
	/**
	 * 编辑超级配置
	 * 胡
	 */
	public function updateSuperConfigBySuperConfigId(){
		$params = $this->input();
		$paramRule = [
	
			'super_config_id'=>'number',
			'user_id'=>'number',
	
		];
	
		$this->paramCheckRule($paramRule,$params);
	
		$super = new SuperConfig();
		$superResult = $super->updateSuperConfigBySuperConfigId($params);
		$this->outPut($superResult);
	
	}
	/**
	 * 添加供应商类型
	 */
	public function AddSupplierType(){
		$params = $this->input();
	
		$paramRule = [
			'supplier_type_name' => 'string',
			'user_id'=>'string',
			'status'=>'number'
	
		];
	
		$this->paramCheckRule($paramRule,$params);
		$supplier = new SupplierType();
		$supplierResult = $supplier->addSupplierType($params);
		$this->outPut($supplierResult);
	
	}
	
	/**
	 * 获取供应商类型
	 */
	public function getSupplierType(){
		$params = $this->input();
	
		$supplier = new SupplierType();
		$supplierResult = $supplier->getSupplierType($params);
		$this->outPut($supplierResult);
	
	}
	
	/**
	 * 编辑供应商类型根据供应商ID
	 * 胡
	 */
	public function updateSupplierTypeBySupplierTypeId(){
		$params = $this->input();
		$paramRule = [
	
				'supplier_type_id'=>'number',
				'user_id'=>'number',
	
		];
	
		$this->paramCheckRule($paramRule,$params);
	
		$supplier = new SupplierType();
		$supplierResult = $supplier->updateSupplierTypeBySupplierTypeId($params);
		$this->outPut($supplierResult);
	
	}
	/**
	 * 添加超级配置信息
	 */
	public function AddSuperConfigInfo(){
		$params = $this->input();
	
		$paramRule = [
			'super_config_info_name' => 'string',
			'user_id'=>'string',
			'status'=>'number',
			'super_config_id'=>'number'
	
		];
	
		$this->paramCheckRule($paramRule,$params);
		$super = new SuperConfigInfo();
		$superResult = $super->addSuperConfigInfo($params);
		$this->outPut($superResult);
	
	}
	
	/**
	 * 获取超级配置信息
	 */
	public function getSuperConfigInfo(){
		$params = $this->input();
	
		$super = new SuperConfigInfo();
		$superResult = $super->getSuperConfigInfo($params);
		$this->outPut($superResult);
	
	}
	
	/**
	 * 编辑超级配置信息
	 * 胡
	 */
	public function updateSuperConfigInfoBySuperConfigInfoId(){
		$params = $this->input();
		$paramRule = [
	
			'super_config_info_id'=>'number',
			'user_id'=>'number',
	
		];
	
		$this->paramCheckRule($paramRule,$params);
	
		$super = new SuperConfigInfo();
		$superResult = $super->updateSuperConfigInfoBySuperConfigInfoId($params);
		$this->outPut($superResult);
	
	}
	/**
	 * 添加资源等级
	 * 胡
	 */
	public function addSourceLevel(){
		$params = $this->input();
		$paramRule = [
			'supplier_type_id' => 'number',//
			'source_level_name'=>'string',
			'status'=>'number',
			'user_id'=>'string',
	
		];
	
		$this->paramCheckRule($paramRule,$params);
	
		$source_level = new SourceLevel();
		$source_level_result = $source_level->addSourceLevel($params);
		$this->outPut($source_level_result);
	}
	/**
	 * 获取资源等级
	 */
	public function getSourceLevel(){
		$params = $this->input();
		$source_level = new SourceLevel();
		$source_level_result = $source_level->getSourceLevel($params);
		$this->outPut($source_level_result);
	
	}
	/**
	 * 修改资源等级根据资源等级ID
	 */
	public function updateSourceLevelBySourceLevelId(){
		$params = $this->input();
		$paramRule = [
			'source_level_id'=>'number',
			'user_id'=>'number'
		
		];
	
		$this->paramCheckRule($paramRule,$params);
		$source_level = new SourceLevel();
		$source_levelResult = $source_level->updateSourceLevelBySourceLevelId($params);
		$this->outPut($source_levelResult);
	
	}
	/**
	 * 添加回执单
	 * 胡
	 */
	public function addReturnReceipt(){

		$params = $this->input();

		$paramRule = [
			'return_receipt_name' => 'string',
			'status'=>'number'
		];

		$this->paramCheckRule($paramRule,$params);
        $data = [
            'return_receipt_name'=>$params['return_receipt_name'],
            'company_id'=>$params['user_company_id'],
        ];
        $this->checkNameIsRepetition('return_receipt',$data);

		$return_receipt = new ReturnReceipt();

		//首先判断名字是否有重复
		$return_receipt_result = $return_receipt->addReturnReceipt($params);

		$this->outPut($return_receipt_result);
	}
	/**
	 * 获取回执单
	 * 胡
	 */
	public function getReturnReceipt(){
	
	
		$params = $this->input();
	
		$return_receipt = new ReturnReceipt();
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
            $count = $return_receipt->getReturnReceipt($params, true);
            $result = $return_receipt->getReturnReceipt($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
		$return_receiptResult = $return_receipt->getReturnReceipt($params);
		$this->outPut($return_receiptResult);
	
	}
	
	/**
	 * 修改回执单
	 * 胡
	 */
	
	public function updateReturnReceiptByReturnReceiptId(){
		$params = $this->input();

        $return_receipt = new ReturnReceipt();
        $Info = $return_receipt->getOneReturnReceipt($params['return_receipt_id']);
        if($Info['return_receipt_name'] == $params['return_receipt_name']){
        }else{

            //开始判断名字是否重复
            $data = [
                'return_receipt_name'=>$params['return_receipt_name'],
                'company_id'=>$Info['user_company_id'],
            ];
            $this->checkNameIsRepetition('return_receipt',$data);
            //结束判断名字重复
        }

		$paramRule = [
	
				'user_id'=>'number',
				'return_receipt_id'=>'number'
	
		];

		$this->paramCheckRule($paramRule,$params);

		$return_receiptResult = $return_receipt->updateReturnReceiptByReturnReceiptId($params);
		$this->outPut($return_receiptResult);
	
	
	}
	/**
	 * 添加回执单内容
	 * 胡
	 */
	public function addReturnReceiptInfo(){
	
		$params = $this->input();
	
		$paramRule = [
				'return_receipt_id' => 'number',
				'status'=>'number',
				'return_receipt_info'=>'array'
		];
		$this->paramCheckRule($paramRule,$params);

        //首先判断名字是否有重复
        $data = [
            'return_receipt_name'=>$params['return_receipt_name'],
            'company_id'=>$params['company_id']
        ];
        $this->checkNameIsRepetition('return_receipt',$data);
        //结束判断名字重复
	
		$return_receipt_info = new ReturnReceiptInfo();
		$return_receipt_info_result = $return_receipt_info->addReturnReceiptInfo($params);
	
		$this->outPut($return_receipt_info_result);
	}
	/**
	 * 获取回执单内容
	 * 胡
	 */
	public function getReturnReceiptInfo(){
	
	
		$params = $this->input();
	
		$return_receipt_info = new ReturnReceiptInfo();
		/*
		 if(isset($params['lang'])){
		 $lang = $params['lang'];
		 }else{
		 $lang = [];
		 }
		*/
		
		$return_receipt_infoResult = $return_receipt_info->getReturnReceiptInfo($params);
		$this->outPut($return_receipt_infoResult);
	
	}
	
	/**
	 * 修改回执单内容
	 * 胡
	 */
	
	public function updateReturnReceiptInfoByReturnReceiptInfoId(){
		$params = $this->input();
	
		$paramRule = [
	
			'user_id'=>'number',
			'return_receipt_info_id'=>'number'
	
		];
	
		$this->paramCheckRule($paramRule,$params);
		$return_receipt_info = new ReturnReceiptInfo();
		$return_receipt_infoResult = $return_receipt_info->updateReturnReceiptInfoByReturnReceiptInfoId($params);
		$this->outPut($return_receipt_infoResult);
	
	
	}

	/**
	 * 添加路线类型
	 * 胡
	 */
	public function addRouteType(){
	
		$params = $this->input();
		$paramRule = [
            'route_type_name' => 'string',
            'route_type_user_id' => 'number',
            'status'=>'number',
            'pid'=>'number',
		];
		$this->paramCheckRule($paramRule,$params);

        //首先判断名字是否有重复
        $data = [
            'route_type_name'=>$params['route_type_name'],
        ];
        $this->checkNameIsRepetition('route_type',$data);
        //结束判断名字重复
	
		$route_type = new RouteType();
		$route_type_result = $route_type->addRouteType($params);
	
		$this->outPut($route_type_result);
	}
	/**
	 * 获取路线类型
	 * 胡
	 */
	public function getRouteType(){

		$params = $this->input();

		$route_type = new RouteType();
		
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $route_type->getRouteType($params, true);
            $result = $route_type->getRouteType($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
    
		$route_typeResult = $route_type->getRouteType($params);
		
		$this->outPut($route_typeResult);
	}

    /**
     * getOneRouteType
     *
     * 获取一条线路类型
     * @author shj
     * @return void
     * Date: 2019/3/14
     * Time: 10:28
     */
    public function getOneRouteType(){

        $params = $this->input();
        $route_type = new RouteType();
        $route_typeResult = $route_type->getOneRouteType($params);
        $this->outPut($route_typeResult);
    }
	
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
	 * 新增邮件模板
     * 韩
	 */
	public function addEmailTemplate(){
        $params = $this->input();

        $paramRule = [
            'email_template_content' => 'string',
            'email_template_title'=> 'string',
            'user_id'=>'string',
            'status'=>'number'

        ];

        $this->paramCheckRule($paramRule,$params);

        $return_email_template = new EmailTemplate();

        //首先判断名字是否有重复
        $return_email_template_result = $return_email_template->addEmailTemplate($params);

        $this->outPut($return_email_template_result);
	}
	/**
	 * 获取邮件模板
     * 韩
	 */
	public function getEmailTemplate(){
        $params = $this->input();

        $return_email_template = new EmailTemplate();
        /*
         if(isset($params['lang'])){
         $lang = $params['lang'];
         }else{
         $lang = [];
         }
        */
        $return_emailTemplate = $return_email_template->getEmailTemplate($params);
        $this->outPut($return_emailTemplate);
	}
	/**
	 * 修改邮件模板根据邮件模板ID
	 */
	public function updateEmailTemplateByEmailTemplateId(){
        $params = $this->input();

        $paramRule = [

            'user_id'=>'number',
            'email_template_id'=>'number'

        ];

        $this->paramCheckRule($paramRule,$params);
        $return_email_template = new EmailTemplate();
        $return_emailTemplate = $return_email_template->updateEmailTemplateByEmailTemplateId($params);
        $this->outPut($return_emailTemplate);
	}	
	/**
	 * 新增账单模板
	 */
	public function addBillTemplate(){
		$params = $this->input();
		
		$paramRule = [
		    'bill_template_title'=>'string',
//            'bill_template_title_pic'=>'string',
//            'bill_template_foot_pic'=>'string',
			'user_id'=>'number',
			'status'=>'number'
		];
		$this->paramCheckRule($paramRule,$params);
        //首先判断名字是否有重复
        $data = [
            'bill_template_title'=>$params['bill_template_title'],
        ];
        $this->checkNameIsRepetition('bill_template',$data);
        //结束判断名字重复

		$bill_template = new BillTemplate();
		$bill_template_result = $bill_template->addBillTemplate($params);
		
		$this->outPut($bill_template_result);
			
	}
	/**
	 * 获取账单模板
	 */
	public function getBillTemplate(){
		$params = $this->input();
		$bill_template = new BillTemplate();
		if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
			$count = $bill_template->getBillTemplate($params, true);
			$result = $bill_template->getBillTemplate($params,false,'true',$page,$page_size);
			$data = [
					'count'=>$count,
					'list'=>$result,
					'page_count'=>ceil($count/$page_size)
			];
			return $this->output($data);
		}
		$bill_template_result = $bill_template->getBillTemplate($params);
		//拿到当前用户ID来判断是否
		//获取用户的默认账单
		$user_params = [
			'user_id'=>$params['now_user_id']	
		];
		
		$user_result = $this->_user->getUser($user_params);
		if(!empty($user_result[0]['default_bill_template_id'])){
			for($i=0;$i<count($bill_template_result);$i++){
				if($bill_template_result[$i]['bill_template_id'] == $user_result[0]['default_bill_template_id']){
					$bill_template_result[$i]['is_default'] = 1;
				}else{
					$bill_template_result[$i]['is_default'] = 0;
				}
			}
		}
		
		$this->outPut($bill_template_result);	
	}	
	/**
	 * 修改账单根据账单模板ID
	 */
	public function updateBillTemplateByBillTemplateId(){
		$params = $this->input();
		
		$paramRule = [
			'bill_template_id' => 'number',
            'user_id'=>'number',
		];
		$this->paramCheckRule($paramRule,$params);
		$bill_template = new BillTemplate();
        $billTemplateInfo = $bill_template->getOneBillTemplate($params['bill_template_id']);
        if($billTemplateInfo['bill_template_title'] == $params['bill_template_title']){
        }else{
            //开始判断名字是否重复
            $data = [
                'bill_template_title'=>$params['bill_template_title'],
            ];
            $this->checkNameIsRepetition('bill_template',$data);
            //结束判断名字重复
        }
		$bill_template_result = $bill_template->updateBillTemplateByBillTemplateId($params);
		$this->outPut($bill_template_result);	
	}
	/**
	 * 新增税点
	 */
	public function addTax(){
        $params = $this->input();

        $paramRule = [
            'choose_company_id' => 'number',
            'txcd' => 'string',
            'gstrate'=> 'string',
            'pstrate'=> 'string',
            'hstrate'=> 'string',
            'status'=>'number'
        ];

        $this->paramCheckRule($paramRule,$params);

        $return_tax = new Tax();

        //首先判断名字是否有重复
        $return_tax_result = $return_tax->addTax($params);

        $this->outPut($return_tax_result);
	}

	/**
	 * 获取税点
	 */
	public function getTax(){
        $params = $this->input();

        $return_tax = new Tax();
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
            $count = $return_tax->getTax($params, true);
            $result = $return_tax->getTax($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
        $return_tax_result =$return_tax->getTax($params);
        $this->outPut($return_tax_result);
	}

	/**
	 * 修改税点根据税点ID
     * 韩
	 */
	public function updateTaxByTaxId(){
        $params = $this->input();

        $paramRule = [

            'tax_id'=>'number'

        ];

        $this->paramCheckRule($paramRule,$params);
        $return_tax = new Tax();
        $return_tax = $return_tax->updateTaxByTaxId($params);
        $this->outPut($return_tax);
	}	
	
	/**
	 * 查询角色
	 */
	public function getRole(){
		$params = $this->input();
		
		$role = new Role();
		if(isset($params['page'])){
			$params['page_size'] =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
			$params['page'] = ($params['page']-1)*$params['page_size'];
			$count = $role->getRole($params, true);
			$result = $role->getRole($params,false);
				
			$data = [
					'count'=>$count,
					'list'=>$result,
					'page_count'=>ceil($count/$params['page_size'])
			];
			
			return $this->output($data);
		}
		$role_result = $role->getRole($params);
		$this->outPut($role_result);
				
	}
	/**
	 * 添加角色
	 */
	public function addRole(){
		$params = $this->input();
		$paramRule = [
			'role_name'=>'string',
			'user_id'=>'number',
		];
		$this->paramCheckRule($paramRule,$params);

        //首先判断名字是否有重复
        $data = [
            'role_name'=>$params['role_name'],
        ];
        $this->checkNameIsRepetition('role',$data);
        //结束判断名字重复

		$role = new Role();
		$result = $role->addRole($params);
		$this->outPut($result);
	}
	/**
	 * 修改角色
	 */
	public function updateRoleByRoleId(){
		$params = $this->input();
		$paramRule = [
			'role_id'=>'string',
			'user_id'=>'number'
		];
		$this->paramCheckRule($paramRule,$params);
		$role = new Role();
        $roleInfo = $role->getOneRole($params['role_id']);
        if($roleInfo['role_name'] == $params['role_name']){
        }else{
            //开始判断名字是否重复
            $data = [
                'role_name'=>$params['role_name'],
            ];
            $this->checkNameIsRepetition('role',$data);
            //结束判断名字重复
        }
		$result = $role->updateRoleByRoleId($params);
		$this->outPut($result);
	}
	
	/**
	 * 添加权限
	 */
	public function addAuth(){
		$params = $this->input();
		$paramRule = [
			'pid'=>'number',
			'user_id'=>'number',
			'level'=>'number'	
		
		];
		
		$this->paramCheckRule($paramRule,$params);
		if($params['level']==1){
			$paramRule = [			
				'controller_name'=>'string',
				'function_name'=>'string',

			];			
			$this->paramCheckRule($paramRule,$params);			
		}else{
			$paramRule = [
				'button_name'=>'string',			
			];
				
			$this->paramCheckRule($paramRule,$params);			
		}
		$auth = new Auth();
		$auth_result = $auth->addAuth($params);
		$this->outPut($auth_result);
	}
	/**
	 * 获取权限
	 */
	public function getAuth(){
		$params = $this->input();
		
		$auth = new Auth();
		if(isset($params['page'])){
			$params['page_size'] =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
			$params['page'] = ($params['page']-1)*$params['page_size'];
			$count = $auth->getAuth($params, true);
			$result = $auth->getAuth($params,false);
				
			$data = [
					'count'=>$count,
					'list'=>$result,
					'page_count'=>ceil($count/$params['page_size'])
			];
		
			return $this->output($data);
		}
		$auth_result = $auth->getAuth($params);
		$this->outPut($auth_result);
	}
	/**
	 * 修改节点根据节点ID
	 */
	public function updateAuthByAuthId(){
		$params = $this->input();
		$paramRule = [
		
	
			'user_id'=>'number',
			'level'=>'number',
			'auth_id'=>'number'	

		
		];
		
		$this->paramCheckRule($paramRule,$params);
		
		$auth =new Auth();
		$auth_result = $auth->updateAuthByAuthId($params);
		$this->outPut($auth_result);
		
	}
	
	/**
	 * 获取权限(1 2级自动分配)
	 */
	public function getAuthConfig(){
		$auth =new Auth();
		//首先获取1级的
		$level1['level'] = 1;
		$level1['status'] = 1;
		$auth_result = $auth->getAuth($level1);
		for($i=0;$i<count($auth_result);$i++){
			$pid_params['pid'] = $auth_result[$i]['auth_id'];
			$pid_params['status'] = 1;
			$auth_result[$i]['children_button_info'] = $auth->getAuth($pid_params);
		}
		$this->outPut($auth_result);
		
	}
	/**
	 * 获取权限配置根据角色ID
	 */
	public function getAuthConfigByRoleId(){
		$params = $this->input();
		$paramRule = [

			'role_id'=>'number',
		];		
		$this->paramCheckRule($paramRule,$params);
		$auth = new AuthRole();
		$auth_result = $auth->getAuthRole($params);

		$this->outPut($auth_result);
		
	}
	/**
	 * 修改权限角色配置
	 */
	public function updateAuthRole(){
		$params = $this->input();
		$paramRule = [
		
			'role_id'=>'number',
			'auth_str'=>'string'	
		];
		$this->paramCheckRule($paramRule,$params);
		$auth = new AuthRole();
		$auth_result = $auth->updateAuthRole($params);
		
		$this->outPut($auth_result);		
	}

    /**
     * 获取货币汇率
     * 王
     */
    public function getCurrencyProportion(){

        $params = $this->input();
        
       
        //首先获取全部货币;
        $currency = new Currency();
        
        $currency_params = [
        	'status'=>1	
        ];
        if(is_numeric($params['currency_id'])){
        	$currency_params['currency_id'] = $params['currency_id'];
        }
        $currency_result = $currency->getCurrency($currency_params);
      	$params['currency_result'] = $currency_result;
       
         $currency_proportion = new CurrencyProportion();
//         $currency_proportion_result = $currency_proportion->getCurrencyProportion($params);
        
        //开始组装数据
        for($i=0;$i<count($currency_result);$i++){
        	
			$currency_result[$i]['currency_array'] = [];
			$currency_proportion_params = [
					'currency_id'=>$currency_result[$i]['currency_id'],
				
					'proportion_time'=>$params['proportion_time'],
			];
			$result = $currency_proportion->getCurrencyProportion($currency_proportion_params);
			$currency_result[$i]['currency_array'] = $result;
        }
       	
        $this->outPut($currency_result);
    }
    
    /**
     * 获取某条 数据的汇率
     */
    public function getOneCurrencyProportion(){
    	
    	$params = $this->input();
    	$currency_proportion = new CurrencyProportion();
    	$result = $currency_proportion->getCurrencyProportion($params);
    	$this->outPut($result);
    	
    }
    public function selectCurrencyProportion(){

        $params = $this->input();
        $currency_proportion = new CurrencyProportion();
        $currency_proportion_Result = $currency_proportion->selectCurrencyProportion($params);
        $this->outPut($currency_proportion_Result);
    }

    /**
     * 添加汇率
     */
    public function addProportionByCurrencyProportion(){
        $params = $this->input();
        $paramRule = [
        
        	'proportion_time'=>'number',
        	'currency_proportion_arr'=>'array'
        ];
        $this->paramCheckRule($paramRule,$params);
       	
        $currency_proportion = new CurrencyProportion();
	
        $result = $currency_proportion->addProportionByCurrencyProportion($params);
    

        $this->outPut($result);

    }
    
    /**
     * 货币1对1的汇率
     */
    public function getProportionOneToOne(){
    	$params = $this->input();
    	$paramRule = [
    	
    		'proportion_time'=>'number',
    	
    	];
    	$this->paramCheckRule($paramRule,$params);
    	
    	$result = $this->_currency_proportion->getCurrencyProportion($params);
    	$new_result = [];
    	for($i=0;$i<count($result);$i++){
    		$new_result[$result[$i]['currency_id'].'-'.$result[$i]['opposite_currency_id']] = $result[$i]['currency_proportion'];
    	}
    	$this->outPut($new_result);
    	
    }
    //获取控制面板数据
    public function getDashboard(){
    	$params = $this->input();

    	$data = [];
    	//获取今日订单人数
    	$company_order_customer_params = [
    		'company_id'=>$params['company_id'],
    		'status'=>1,
    		"create_time_day"=>date('Ymd')
    	];



    	$today_customer_result= $this->_company_order_customer->getAllCompanyOrderCustomer($company_order_customer_params);
    	$data['today_customer_count'] = count($today_customer_result);

    	//获取今日出团数
    	$team_product_params=[
    		'begin_time_day'=>date('Ymd'),
    		'status'=>1,
    		'company_id'=>$params['company_id'],

    	];

    	$today_team_product_result = $this->_team_product->getTeamProduct($team_product_params);
    	$data['today_team_product_count'] = count($today_team_product_result);


    	//获取今日营业额
    	$receivable_params = [
    		'status'=>1,
    		'create_time_day'=>date('Ymd'),
    		'company_id'=>$params['company_id']

    	];

    	$today_receivable = $this->_receivable->getReceivableSum($receivable_params);

    	$data['today_receivable'] = $today_receivable[0]['receivable_money'];
    	//获取当月订单人数
    	$company_order_customer_params = [
    			'company_id'=>$params['company_id'],
    			'status'=>1,
    			"create_time_month"=>date('Ym')
    	];



    	$month_customer_result= $this->_company_order_customer->getAllCompanyOrderCustomer($company_order_customer_params);
    	$data['month_customer_count'] = count($month_customer_result);


    	//获取本月出团数
    	$team_product_params=[
    		'begin_time_month'=>date('Ym'),
    		'status'=>1,
    		'company_id'=>$params['company_id'],

    	];

    	$today_team_product_result = $this->_team_product->getTeamProduct($team_product_params);
    	$data['month_team_product_count'] = count($today_team_product_result);
    	//获取本月营业额
    	$receivable_params = [
    		'status'=>1,
    		'create_time_month'=>date('Ym'),
    		'company_id'=>$params['company_id']

    	];

    	$today_receivable = $this->_receivable->getReceivableSum($receivable_params);
    	$data['month_receivable'] = $today_receivable[0]['receivable_money'];
    	//获取应收汇总
    	$receivable_params = [
    			'status'=>1,

    			'company_id'=>$params['company_id']

    	];

    	$all_receivable = $this->_receivable->getReceivableSum($receivable_params);
    	$data['all_receivable'] = $all_receivable[0]['receivable_money'];
    	//获取已收汇总
    	$receivable_info_params = [


    		'company_id'=>$params['company_id']

    	];

    	$all_receivable_info = $this->_receivable_info->getReceivableInfoMoney($receivable_info_params);

    	$data['all_receivable_info'] = $all_receivable_info[0]['true_receivable'];

    	//获取 7天 30天 90天的 收客统计

    	//获取7天的收客统计
    	$company_order_customer_params = [
    		'company_id'=>$params['company_id'],
    		'status'=>1,
    		"create_time_diy_day"=>date('Ymd',strtotime('-6 days'))
    	];



    	$day7_customer_result= $this->_company_order_customer->getAllCompanyOrderCustomer($company_order_customer_params);

    	$day7_arr = [];

    	for($i=0;$i<7;$i++){
    		$the_days =  date('Y-m-d',strtotime("- $i days"));
    		$day7_arr[$i]['the_days'] =$the_days;

    		$day7_arr[$i]['customer_count'] = 0;
    		for($j=0;$j<count($day7_customer_result);$j++){
    			if($the_days == $day7_customer_result[$j]['from_unixtime_time']){
    				$day7_arr[$i]['customer_count']++;

    			}
    		}
    	}

    	$data['day7_customer_info'] = $day7_arr;
    	//获取30天的收客统计
    	$company_order_customer_params = [
    			'company_id'=>$params['company_id'],
    			'status'=>1,
    			"create_time_diy_day"=>date('Ymd',strtotime('-29 days'))
    	];



    	$day30_customer_result= $this->_company_order_customer->getAllCompanyOrderCustomer($company_order_customer_params);

    	$day30_arr = [];

    	for($i=0;$i<30;$i++){
    		$the_days =  date('Y-m-d',strtotime("- $i days"));
    		$day30_arr[$i]['the_days'] =$the_days;

    		$day30_arr[$i]['customer_count'] = 0;
    		for($j=0;$j<count($day30_customer_result);$j++){
    			if($the_days == $day30_customer_result[$j]['from_unixtime_time']){
    				$day30_arr[$i]['customer_count']++;

    			}
    		}
    	}

    	$data['day30_customer_info'] = $day30_arr;

    	//获取90天的收客统计
    	$company_order_customer_params = [
    			'company_id'=>$params['company_id'],
    			'status'=>1,
    			"create_time_diy_day"=>date('Ymd',strtotime('-89 days'))
    	];



    	$day90_customer_result= $this->_company_order_customer->getAllCompanyOrderCustomer($company_order_customer_params);

    	$day90_arr = [];

    	for($i=0;$i<90;$i++){
    		$the_days =  date('Y-m-d',strtotime("- $i days"));
    		$day90_arr[$i]['the_days'] =$the_days;

    		$day90_arr[$i]['customer_count'] = 0;
    		for($j=0;$j<count($day90_customer_result);$j++){
    			if($the_days == $day90_customer_result[$j]['from_unixtime_time']){
    				$day90_arr[$i]['customer_count']++;

    			}
    		}
    	}

    	$data['day90_customer_info'] = $day90_arr;


    	//获取 7天 30天 90天的 应收统计

    	//获取7天的应收统计
    	$receivable_sum_params = [
    			'company_id'=>$params['company_id'],
    			'status'=>1,
    			"create_time_diy_day"=>date('Ymd',strtotime('-6 days'))
    	];



    	$day7_receivable_result= $this->_receivable->getReceivable($receivable_sum_params);



    	$day7_arr = [];

    	for($i=0;$i<7;$i++){
    		$the_days =  date('Y-m-d',strtotime("- $i days"));
    		$day7_arr[$i]['the_days'] =$the_days;

    		$day7_arr[$i]['receivable_money'] = 0;
    		for($j=0;$j<count($day7_receivable_result);$j++){
    			if($the_days == date("Y-m-d",$day7_receivable_result[$j]['create_time'])){
    				$day7_arr[$i]['receivable_money']+=$day7_receivable_result[$j]['receivable_money'];

    			}
    		}
    	}

    	$data['day7_receivable_info'] = $day7_arr;


    	//获取30天的应收统计
    	$receivable_sum_params = [
    			'company_id'=>$params['company_id'],
    			'status'=>1,
    			"create_time_diy_day"=>date('Ymd',strtotime('-29 days'))
    	];



    	$day30_receivable_result= $this->_receivable->getReceivable($receivable_sum_params);



    	$day30_arr = [];

    	for($i=0;$i<30;$i++){
    		$the_days =  date('Y-m-d',strtotime("- $i days"));
    		$day30_arr[$i]['the_days'] =$the_days;

    		$day30_arr[$i]['receivable_money'] = 0;
    		for($j=0;$j<count($day30_receivable_result);$j++){
    			if($the_days == date("Y-m-d",$day30_receivable_result[$j]['create_time'])){
    				$day30_arr[$i]['receivable_money']+=$day30_receivable_result[$j]['receivable_money'];

    			}
    		}
    	}

    	$data['day30_receivable_info'] = $day30_arr;

    	//获取90天的收客统计
    	$receivable_sum_params = [
    			'company_id'=>$params['company_id'],
    			'status'=>1,
    			"create_time_diy_day"=>date('Ymd',strtotime('-89 days'))
    	];



    	$day90_receivable_result= $this->_receivable->getReceivable($receivable_sum_params);



    	$day90_arr = [];

    	for($i=0;$i<90;$i++){
    		$the_days =  date('Y-m-d',strtotime("- $i days"));
    		$day90_arr[$i]['the_days'] =$the_days;

    		$day90_arr[$i]['receivable_money'] = 0;
    		for($j=0;$j<count($day90_receivable_result);$j++){
    			if($the_days == date("Y-m-d",$day90_receivable_result[$j]['create_time'])){
    				$day90_arr[$i]['receivable_money']+=$day90_receivable_result[$j]['receivable_money'];

    			}
    		}
    	}

    	$data['day90_receivable_info'] = $day90_arr;
    	//未收账款排名

    	$miss_payment_company_params = [
    		'company_id'=>$params['company_id']
    	];

    	$miss_payment_company = $this->_receivable->getMissPaymentCompany($miss_payment_company_params);
    	$company_arr = [];
    	for($i=0;$i<count($miss_payment_company);$i++){
			$company_arr[$miss_payment_company[$i]['payment_object_id']]['miss_money']+=$miss_payment_company[$i]['receivable_money']-$miss_payment_company[$i]['true_money'];
			$company_arr[$miss_payment_company[$i]['payment_object_id']]['company_name']=$miss_payment_company[$i]['company_name'];


    	}

    	array_multisort($company_arr,SORT_DESC,array_column($company_arr,'miss_money'));
    	$data['miss_payment_company'] = $company_arr;
    	$this->outPut($data);


    }

    /**
     * getTag
     *
     * 获取标签列表
     * @author shj
     * @return void
     * Date: 2019/3/26
     * Time: 18:13
     */
    public function getTag(){
    	$params = $this->input();

        $tab = new Tag();
        if(isset($params['page'])) {
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $tab->getTag($params, true);
            $result = $tab->getTag($params, false,'true',$page,$page_size);

            $data = [
                'count'      => $count,
                'list'       => $result,
                'page_count' => ceil($count / $params['page_size'])
            ];

            return $this->output($data);
        }
        $result = $tab->getTag($params);
        $this->outPut($result);
    }


    public function getTagByLanguageId()
    {
        $params = $this->input();
       error_log(print_r($params,1));
        $tab = new Tag();

        $result = $tab->getTagByLanguageId($params);
   		
        $this->outPut($result);
    }

    /**
     * updateTag
     *
     * 修改标签
     * @author shj
     * @return void
     * Date: 2019/4/18
     * Time: 16:31
     */
    public function updateTag(){
        $params = $this->input();
        $tab = new Tag();

        $result = $tab->updateTagByCodeName($params);
        $this->outPut($result);

    }

    /**
     * addTag
     *
     * 新增标签
     * @author shj
     * @return void
     * Date: 2019/4/18
     * Time: 16:32
     */
    public function addTag(){
        $params = $this->input();
       
        $tab = new Tag();
        $result = $tab->addTag($params);
        $this->outPut($result);
    }

    /**
     * 发送邮件
     * Created by PhpStorm.
     * User: yyy
     * Date: 2019/5/7
     * Time: 10:21
     */
    public function sendEmail()
    {
        $params = $this->input();

        $paramRule = [
            'to_email'=>'string',
            'content'=>'string',
            'subject' => 'string'
        ];
        $this->paramCheckRule($paramRule,$params);

        $result = Help::sendOperationsEmail($params);
		
        if ($result === 1)
        {
            $this->outPut(1);
        }

        $this->outPut('', $result);
    }
    
    
    
    //调去系统邮件
    public function sendSystemEmail()
    {
    	$email_sms = new EmailSms();
    	$params = $this->input();
    	
    	$paramRule = [
    		'to_email'=>'string',
    		'type'=>'int',
    		
    	];
    	$this->paramCheckRule($paramRule,$params);
    	
    	//获取6位随机数
    	$code = help::getRandomNumber(6);
    	//获取文案
    	$content = help::getEmailContent($params['type']);
    	
    	if($params['type']==1){//重置密码
    		$email_params = [
    			'to_email'=>$params['to_email'],
    			'content'=>$content.'['.$code.']',
    				
    		];
    		

    	}
    	
    	$a =Help::sendOperationsEmail($email_params);
   
    	if($a==1){// 代表发送成功
    	    //开始把验证码信息插入到SMS表中
    	    $email_sms_params = [
				'code'=>$code,
    	    	'email'=>$params['to_email'],
    	    	'type'=>$params['type'],
    	    	'now_user_id'=>$params['now_user_id']	
    	    ];
    		$email_sms->addEmailSms($email_sms_params);
    		
    		$this->outPut('发送成功');
    		
    	}else{
    		
    		$array='邮箱发送失败，请重新发送'; 
    		$this->outPutError($array);
    		
    	}
    }
    
    /**
     * 判断验证码是否正确
     */
    public function getCodeIsTrue(){
    	$email_sms = new EmailSms();
    	$params = $this->input();
    	 
    	$paramRule = [
    		'user_id'=>'string',
    		'type'=>'int',
    	
    	];
    	$this->paramCheckRule($paramRule,$params);
    	
    	$result = $email_sms->getEmailSms($params);
    	$this->outPut($result);
    	
    }
    
    /**
     * 更新系统邮件状态
     */
    public function updateEmailSms(){
    	$email_sms = new EmailSms();
    	$params = $this->input();
    	
    	$paramRule = [
    		'email_sms_id'=>'int',
    		
    			 
    	];
    	$this->paramCheckRule($paramRule,$params);
    
    	$result = $email_sms->updateEmailSms($params);
    	$this->outPut($result);
    	
    }
    
    public function del_city(){
    	
    	$params = $this->input();
    	$country = new Country();
    	$data['level'] = 2;
    	$city_result = $country->getCountry($data);
    	for($i=0;$i<count($city_result);$i++){
    		$city_data['country_id'] = $city_result[$i]['pid'];
    		$city_result2 = $country->getCountry($city_data);
    		
    		
    		if(count($city_result2)==0){
    			$del_params['country_id'] = $city_result[$i]['country_id'];
    			$country->del_country($del_params);
    		}
    		//$city
    	}
    	//$this->outPut($city_result);
    }


    public function getTimeZone(){

        $model = new TimeZone();
        $result = $model->getTimeZoneList();
        $this->outPut($result);

    }

    public function getRoomType(){
        $params = $this->input();

        $room_type = new RoomType();
        if(isset($params['page'])) {
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $room_type->getRoomType($params, true);
            $result = $room_type->getRoomType($params, false,'true',$page,$page_size);

            $data = [
                'count'      => $count,
                'list'       => $result,
                'page_count' => ceil($count / $params['page_size'])
            ];

            return $this->output($data);
        }
        $result = $room_type->getRoomType($params);
        $this->outPut($result);
    }


    public function addRoomType(){
        $params = $this->input();

        $room_type = new RoomType();
        $result = $room_type->addRoomType($params);
        $this->outPut($result);
    }

    public function updateRoomType(){
        $params = $this->input();

        $room_type = new RoomType();
        $result = $room_type->updateRoomType($params);
        $this->outPut($result);
    }

    public function getOneRoomType(){
        $params = $this->input();

        $room_type = new RoomType();
        $result = $room_type->getOneRoomType($params['room_type_id']);
        $this->outPut($result);
    }


    public function deleteRoomType(){
        $params = $this->input();
        $room_type = new RoomType();
        $result = $room_type->deleteRoomType($params);
        $this->outPut($result);
    }

    public function getRoomTypeAjax(){
        $params = $this->input();

        $room_type = new RoomType();

        $result = $room_type->getRoomTypeAjax($params);
        $this->outPut($result);
    }

    public function getSeason(){
        $params = $this->input();

        $season = new Season();
        if(isset($params['page'])) {
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $season->getSeason($params, true);
            $result = $season->getSeason($params, false,'true',$page,$page_size);

            $data = [
                'count'      => $count,
                'list'       => $result,
                'page_count' => ceil($count / $params['page_size'])
            ];

            return $this->output($data);
        }
        $result = $season->getSeason($params);
        $this->outPut($result);
    }


    public function addSeason(){
        $params = $this->input();

        $season = new Season();
        $result = $season->addSeason($params);
        $this->outPut($result);
    }

    public function updateSeason(){
        $params = $this->input();

        $season = new Season();
        $result = $season->updateSeason($params);
        $this->outPut($result);
    }

    public function getOneSeason(){
        $params = $this->input();

        $season = new Season();
        $result = $season->getOneSeason($params['season_id']);
        $this->outPut($result);
    }

    public function deleteSeason(){
        $params = $this->input();
        $season = new Season();
        $result = $season->deleteSeason($params);
        $this->outPut($result);
    }

    public function getSeasonAjax(){
        $params = $this->input();

        $season = new Season();

        $result = $season->getSeasonAjax($params);
        $this->outPut($result);
    }

}
