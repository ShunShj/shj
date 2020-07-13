<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/25
 * Time: 10:00
 */
namespace app\index\controller;

use http\Client\Curl\User;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;
use app\common\help\Contents;
use app\common\help\Help;
use \Underscore\Types\Arrays;

class System extends Base
{
	private $_language;
	public function __initialize(){
		$this->_language = $this->callSoaErp('post','/system/getLanguage',[])['data'];


	}
	/////公共JS开始
	/**
	 * 获取部门通过公司ID
	 */
    public function getDepartmentByCompanyId(){
    	$company_id= input('company_id');
    	$department_data = [
    		'choose_company_id'=>$company_id,
             'status'=>1
    	];
    	
    	$data = $this->callSoaErp('post','/system/getDepartment',$department_data);
    	return $data;
    }
    /**
     * 获取职位通过部门ID
     * 胡
     */
    public function getJobByDepartmentId(){
    	$department_id= input('department_id');
    	 
    	 
    	$job_data = [

    			'department_id'=>$department_id,
                'status'=>1

    	];
    	$data = $this->callSoaErp('post','/system/getJob',$job_data);
    	 
    	return $data;
    }    
    /////公共JS结束
    /*
    * 韩冰
    * 2018/7/25
    * 货币管理-(展示)界面
    */
    public function showCurrencyManage(){

        $data = [
        	'page'=>$this->page(),
        	'page_size'=>$this->_page_size,
        	'currency_name'=>input('currency_name'),
        	'status'=>input('status')


        ];

        if(!empty(input('currency_name'))){
        	$data['currency_name'] = input('currency_name');
        }
        $data['company_id'] =  session('user')['company_id'];
        $result =  $this->callSoaErp('post','/system/getCurrency',$data);
		
        $this->getPageParams($result);    
        return $this->fetch("currency_manage");

    }
     
    /**
     * 新增货币页面显示
     */
    public function showCurrencyAdd(){
        return $this->fetch('currency_add');
    }

    

    /*
    * 韩冰
    * 2018/7/25
    * 货币管理-(添加)界面
    */
    public function addCurrencyAjax(){

        $data = Request::instance()->param();
        $data['user_id'] = session('user')['user_id'];

        $result =  $this->callSoaErp('post','/system/addCurrency',$data);

        return $result;
    }

    /*
    * 韩冰
    * 2018/7/25
    * 货币管理-(展示)界面
    */
    public function showCurrencyEdit(){

       $currency_id = $_GET['id'];
         $data = [
            "currency_id"=>$currency_id];
        $result = $this->callSoaErp('post', '/system/getCurrency', $data);

        $this -> assign('data',$result['data']);
        $this -> assign('currency_id',$currency_id);

         return $this->fetch("currency_edit");
     } 

    public function showCurrencyInfo(){
        $currency_id = $_GET['id'];
        $data = ["currency_id"=>$currency_id];

        $result = $this->callSoaErp('post', '/system/getCurrency', $data);

        $this -> assign('data',$result['data']);
        $this -> assign('currency_id',$currency_id);

        return $this->fetch("currency_info");
    }

    /*
    * 韩冰
    * 2018/7/25
    * 货币管理-(修改)界面
    */
    public function editCurrencyAjax(){
        $data = Request::instance()->param();
        $data['user_id'] = session('user')['user_id'];
        $result = $this->callSoaErp('post', '/system/updateCurrencyByCurrencyId', $data);
        return $result;
    }
     /*
    * 王伟
    * 2018/8/24
    * 货币编辑-(展示)界面
    */
    public function editCurrencyManage(){
        $currency_edit_data = [];

        $data = [
            'currency_id'=>input('currency_id'),
            'symbol'=>input('symbol'),
            'unit'=>input('unit'),
            'status'=>input("status"),
            'user_id'=>session('user')['user_id'],
            'currency_name'=>input('currency_name')
        ];
        $result = $this->callSoaErp('post', '/system/updateCurrencyByCurrencyId',$data);

        return $result;

   }


    /*
    * 韩冰
    * 2018/7/26
    * 货币管理-(搜索)界面
    */
    public function searchCurrencyManage()
    {
        $name = $_POST['name'];
        $status = $_POST['status'];
        $user_id = session('user')['user_id'];

        if($status == 0){
            if(!empty($name)){
                $data = ['currency_name'=>$name,'user_id'=>$user_id];
                $result =  $this->callSoaErp('post','/system/getCurrency',$data);

                if($result['code']==200 && $result['msg']=="success") {
                    return json_encode($result);
                }
            }else{
                $data = [];
                $result =  $this->callSoaErp('post','/system/getCurrency',$data);
                if($result['code']==200 && $result['msg']=="success") {
                    return json_encode($result);
                }
            }
        }else{
            if(!empty($name)){
                $data = ['currency_name'=>$name,'status'=>$status,'user_id'=>'123'];
                $result =  $this->callSoaErp('post','/system/getCurrency',$data);

                if($result['code']==200 && $result['msg']=="success") {
                    return json_encode($result);
                }
            }else{
                $data = ['status'=>$status,'user_id'=>'123'];
                $result =  $this->callSoaErp('post','/system/getCurrency',$data);

                if($result['code']==200 && $result['msg']=="success") {
                    return json_encode($result);
                }
            }
        }
    }



    /*
    * 韩冰
    * 2018/7/26
    * 语言管理-(展示)界面
    */
    public function showLanguageManage(){
		
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];

        if(!empty(input('language_name'))){
            $data['language_name'] = input('language_name');
        }
        if(!empty(input('status'))){
            $data['status'] = input('status');
        }

        $result =  $this->callSoaErp('post','/system/getLanguage',$data);

        $this->getPageParams($result);
        return $this->fetch("language_manage");

    }

    public function showLanguageInfo(){
        $language_id = $_GET['id'];
        $data = ["language_id"=>$language_id];

        $result = $this->callSoaErp('post', '/system/getLanguage', $data);

        $this -> assign('data',$result['data']);
        $this -> assign('language_id',$language_id);

        return $this->fetch("language_info");
    }

    /*
     * 新增语言页面显示
     */
    public function showLanguageAdd(){
        return $this->fetch('language_add');
    }

    /*
    * 韩冰
    * 2018/7/26
    * 语言管理-(添加)界面
    */
    public function addLanguageAjax(){
        $data = Request::instance()->param();
        $data['user_id'] = session('user')['user_id'];
       
        $result =  $this->callSoaErp('post','/system/addLanguage',$data);
        return $result;
    }

    /*
    * 韩冰
    * 2018/7/26
    * 语言管理-(展示)界面
    */
    public function showLanguageEdit(){

        $language_id = $_GET['id'];

        $data = ["language_id"=>$language_id];

        $result = $this->callSoaErp('post', '/system/getLanguage', $data);

        $this -> assign('data',$result['data']);
        $this -> assign('language_id',$language_id);

        return $this->fetch("language_edit");  
    }

    /*
    * 韩冰
    * 2018/7/26
    * 语言管理-(修改)界面
    */
    public function editLanguageAjax(){
        $data = Request::instance()->param();
        $data['user_id'] = session('user')['user_id'];
        $result = $this->callSoaErp('post', '/system/updateLanguageByLanguageId', $data);
        return $result;
    }

    /*
    * 韩冰
    * 2018/7/26
    * 语言管理-(搜索)界面
    */
    public function searchLanguageManage(){
        $name = $_POST['name'];
        $status = $_POST['status'];

        if($status == 0){
            if(!empty($name)){
                $data = ['language_name'=>$name,'user_id'=>'123'];
                $result =  $this->callSoaErp('post','/system/getLanguage',$data);

                if($result['code']==200 && $result['msg']=="success") {
//                    //默认语言
//                    $config_language = \think\Lang::range('zh-cn');
//                    //session设置语言
//                    $session_language = Session::get("language");
//
//                    if (!empty($session_language)) {
//                        $data = $result['data'][$session_language];
//                    } else {
//                        $data = $result['data'][$config_language];
//                    }
                    return json_encode($result);
                }
            }else{
                $data = [];
                $result =  $this->callSoaErp('post','/system/getLanguage',$data);
                if($result['code']==200 && $result['msg']=="success") {
//                    //默认语言
//                    $config_language = \think\Lang::range('zh-cn');
//                    //session设置语言
//                    $session_language = Session::get("language");
//
//                    if(!empty($session_language)){
//                        $data = $result['data'][$session_language];
//                    }else{
//                        $data = $result['data'][$config_language];
//                    }
                    return json_encode($result);
                }
            }
        }else{
            if(!empty($name)){
                $data = ['language_name'=>$name,'status'=>$status,'user_id'=>'123'];
                $result =  $this->callSoaErp('post','/system/getLanguage',$data);

                if($result['code']==200 && $result['msg']=="success") {
//                    //默认语言
//                    $config_language = \think\Lang::range('zh-cn');
//                    //session设置语言
//                    $session_language = Session::get("language");
//
//                    if(!empty($session_language)){
//                        $data = $result['data'][$session_language];
//                    }else{
//                        $data = $result['data'][$config_language];
//                    }
                    return json_encode($result);
                }
            }else{
                $data = ['status'=>$status,'user_id'=>'123'];
                $result =  $this->callSoaErp('post','/system/getLanguage',$data);

                if($result['code']==200 && $result['msg']=="success") {
//                    //默认语言
//                    $config_language = \think\Lang::range('zh-cn');
//                    //session设置语言
//                    $session_language = Session::get("language");
//
//                    if (!empty($session_language)) {
//                        $data = $result['data'][$session_language];
//                    } else {
//                        $data = $result['data'][$config_language];
//                    }
                    return json_encode($result);
                }
            }
        }
    }

    /*
    * 韩冰
    * 2018/7/26
    * 国家管理-(展示)界面
    */
    public function showCountryManage(){

        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];

        if(!empty(input('country_name'))){
            $data['country_name'] = input("country_name");
        }
        if(!empty(input('status'))){
            $data['status'] = input('status');
        }

        //$result =  $this->callSoaErp('post','/system/getCountry',$data);
        $result =  $this->callSoaErp('post','/system/getCountryCity',$data);

        $this->getPageParams($result);
        return $this->fetch("system/country_manage");

    }

    /*
     * 新增国家页面显示
     */
    public function showCountryStateAdd(){
        //获取国家
        $country_data = [
            'status'=>1,
            'pid'=>0
        ];

        $country_data_result = $this->callSoaErp('post', '/system/getCountry',$country_data);
        //dump($country_data_result);exit();
        $this->assign('country_data_result',$country_data_result['data']);
        return $this->fetch('country_state_add');
    }

    /*
     * 新增城市页面显示
     */
    public function showCountryCityAdd(){

        //获取国家
        $country_data = [
            'status'=>1,
            'pid'=>0
        ];

        $country_data_result = $this->callSoaErp('post', '/system/getCountry',$country_data);
        // dump($country_data_result);exit();
        $this->assign('country_data_result',$country_data_result['data']);
        return $this->fetch('country_city_add');
    }

    /*
     * 新增地区页面显示
     */
    public function showCountryZoneAdd(){

        //获取国家
        $country_data = [
            'status'=>1,
            'pid'=>0
        ];

        //获取地区
        $country_zone_data = [
            'status'=>1,
            'level'=>2
        ];
        //获取所有省市
        
        $country_level2_result = $this->callSoaErp('post','/system/getProvinceTop',[]);
        
        
        $country_data_result = $this->callSoaErp('post', '/system/getCountry',$country_data);
        //dump($country_data_result);exit();
        $this->assign('country_data_result',$country_data_result['data']);

        $country_zone_data_result = $this->callSoaErp('post', '/system/getCountry',$country_zone_data);
        $this->assign('country_zone_data_result',$country_zone_data_result['data']);
        //获取语言
        $language_data = [
            'status'=>1,

        ];
        $language_data_result = $this->callSoaErp('post', '/system/getLanguage',$language_data);
   
        $this->assign('language_data_result',$language_data_result['data']);
        //获取货币
        $currency_data = [
            'status'=>1,

        ];
        $currency_data_result = $this->callSoaErp('post', '/system/getCurrency',$currency_data);
        $this->assign('currency_data_result',$currency_data_result['data']);
        $this->assign('country_level2_result',$country_level2_result['data']);
        return $this->fetch('country_zone_add');
    }

    /*
     * 读取所有国家信息
     */
    public function loadCountryManage(){
        $level = $_POST['level'];
        $data = ['level'=>$level];

        $result =  $this->callSoaErp('post','/system/getCountry',$data);
        return json_encode($result['data']);
    }

    /*
    * 韩冰
    * 2018/7/26
    * 国家管理-(添加)界面
    */
    public function addCountryAjax(){

        $level = input('level');
        $pid = input('pid');
        $country_name = input('country_name');
        //添加地区
        $currency_id = input('currency_id');
        $language_id = input('language_id');
        $timezone = input('timezone');

        if($level==1){
            //添加国家
            $country_code = input('country_code');
            $data = ['country_name'=>$country_name,'pid'=>$pid,'level'=>$level,'country_code'=>$country_code,'user_id'=>Session::get('user')['user_id']];
        }else if($level==2){
            //添加城市
            $data = ['country_name'=>$country_name,'pid'=>$pid,'level'=>$level,'user_id'=>Session::get('user')['user_id']];
        }else if($level==3){
            //添加地区
            $data = ['country_name'=>$country_name,'pid'=>$pid,'level'=>$level,'currency_id'=>$currency_id,'language_id'=>$language_id,'timezone'=>$timezone,'user_id'=>Session::get('user')['user_id']];
        }

        $result =  $this->callSoaErp('post','/system/addCountry',$data);
        return $result;
    }

    /*
   * 韩冰
   * 2018/7/26
   * 国家管理-(修改)界面
   */



    public function showCountryEditManage($country_id){
        //获取国家
        $level=$_GET['level'];
        $data_country = ['level'=>$level];
        $result_data=  $this->callSoaErp('post','/system/getCountry',$data_country);

        $data = ["country_id"=>$country_id];
        $result = $this->callSoaErp('post', '/system/getCountry', $data);

      	//获取 所有国家
      	
        
        $country_level_1['level'] = 1;
        $country_level1_result = $this->callSoaErp('post','/system/getCountry',$country_level_1);
      	//获取所有省市
 
        $country_level2_result = $this->callSoaErp('post','/system/getProvinceTop',[]);
        
        
        //获取对应的语言
        $language_data = [
            "status"=>1
        ];
        $language_data_result = $this->callSoaErp('post', '/system/getLanguage', $language_data);
        //获取对应的货币
        $currency_data = [
            "status"=>1
        ];
        $result_data_currency = $this->callSoaErp('post', '/system/getCurrency', $currency_data);
        $this->assign('data',$result['data'][0]);
        $this->assign('data_country',$result_data['data']);
        $this->assign('data_language',$language_data_result['data']);
        $this->assign('data_currency',$result_data_currency['data']);
		$this->assign('country_level1_result',$country_level1_result['data']);
		$this->assign('country_level2_result',$country_level2_result['data']);
        return $this->fetch("country_edit");

    }

    /**
     * 编辑国家管理-国家
     */
    public function showCountryEdit(){
        $country_edit_data = [];
        $country_edit_data['user_id'] = session('user')['user_id'];

        $country_edit_data['country_name']=input("country_name");
        $country_edit_data['country_code']=input("country_code");
        $country_edit_data = [
            'country_id'=>input('country_id'),
            'level'=>1,
            'country_name'=>$country_edit_data['country_name'],
            'country_code'=>$country_edit_data['country_code'],
            'status'=>input("status"),
            'user_id'=>$country_edit_data['user_id']
        ];
        $result = $this->callSoaErp('post', '/system/updateCountryByCountryId',$country_edit_data);

        return $result;
    }

    /**
     * 编辑国家管理-城市
     */
    public function showCountryEdit2(){
        $country_edit_data = [];
        $country_edit_data['user_id'] = session('user')['user_id'];

        $country_edit_data['country_name']=input("country_name");
        $country_edit_data = [
            'country_id'=>input('country_id'),
            'level'=>2,
            'country_name'=>$country_edit_data['country_name'],
            'status'=>input("status"),
            'user_id'=>$country_edit_data['user_id'],
        	'pid'=>input('pid')	
        ];
        $result = $this->callSoaErp('post', '/system/updateCountryByCountryId',$country_edit_data);

       return $result;
    }

    /**
     * 编辑国家管理-地区
     */
    public function showCountryEdit3(){
        $country_edit_data = [];
        $country_edit_data['user_id'] = session('user')['user_id'];
        $country_edit_data['country_name']=input("country_name");
        $country_edit_data['language_id']=input("language_id");
        $country_edit_data['currency_id']=input("currency_id");
        $country_edit_data['timezone']=input("timezone");

        $country_edit_data = [
            'country_id'=>input('country_id'),
            'level'=>2,
            'country_name'=>$country_edit_data['country_name'],
            'language_id'=>$country_edit_data['language_id'],
            'currency_id'=>$country_edit_data['currency_id'],
            'timezone'=>$country_edit_data['timezone'],
            'status'=>input("status"),
            'user_id'=>$country_edit_data['user_id'],
        	'pid'=>input('province_id')	
        ];
     
        $result = $this->callSoaErp('post', '/system/updateCountryByCountryId',$country_edit_data);

        return $result;
    }

    /*
      * 韩冰
    * 2018/7/26
    * 国家管理-(显示)界面
    */
    public function showCountryInfo($country_id){
        //获取国家
        $level=$_GET['level'];
        $data_country = ['level'=>$level];
        $result_data=  $this->callSoaErp('post','/system/getCountry',$data_country);

        $data = ["country_id"=>$country_id];
        $result = $this->callSoaErp('post', '/system/getCountry', $data);
        //获取 所有国家


        $country_level_1['level'] = 1;
        $country_level1_result = $this->callSoaErp('post','/system/getCountry',$country_level_1);
        //获取所有省市

        $country_level2_result = $this->callSoaErp('post','/system/getProvinceTop',[]);


        //获取对应的语言
        $result_data_language = $this->callSoaErp('post', '/system/getLanguage', $data);
        //获取对应的货币
        $result_data_currency = $this->callSoaErp('post', '/system/getCurrency', $data);

        $this->assign('data',$result['data'][0]);
        $this->assign('data_country',$result_data['data']);
        $this->assign('data_language',$result_data_language['data']);
        $this->assign('data_currency',$result_data_currency['data']);
        $this->assign('country_level1_result',$country_level1_result['data']);
        $this->assign('country_level2_result',$country_level2_result['data']);

        return $this->fetch("country_info");
    }

    //国家搜索
    public function searchCountryManage(){
        $name = $_POST['name'];
        $status = $_POST['status'];
        $user_id = session('user')['user_id'];

        if($status == 0){
            if(!empty($name)){
                $data = ['name'=>$name,'user_id'=>$user_id];
                $result =  $this->callSoaErp('post','/system/getCountry',$data);

                if($result['code']==200 && $result['msg']=="success") {
                    return json_encode($result);
                }
            }else{
                $data = [];
                $result =  $this->callSoaErp('post','/system/getCountry',$data);
                if($result['code']==200 && $result['msg']=="success") {
                    return json_encode($result);
                }
            }
        }else{
            if(!empty($name)){
                $data = ['name'=>$name,'status'=>$status,'user_id'=>$user_id];
                $result =  $this->callSoaErp('post','/system/getCountry',$data);

                if($result['code']==200 && $result['msg']=="success") {
                    return json_encode($result);
                }
            }else{
                $data = ['status'=>$status,'user_id'=>$user_id];
                $result =  $this->callSoaErp('post','/system/getCountry',$data);

                if($result['code']==200 && $result['msg']=="success") {
                    return json_encode($result);
                }
            }
        }
    }
    /**
     * 国家多语言编辑页面
     */
    public function showCountryEditLanguage(){
    	$data['country_id'] = input('country_id');
    	$country_language_result = $this->callSoaErp('post','/system/getCountryLanguage',$data);

    	$this->assign('country_language_result',$country_language_result['data']);
    	return $this->fetch('country_edit_language');
    }
    /**
     * 国家多语言编辑AJAX
     */
    public function countryEditLanguageAjax(){
    	$data =$this->input();

    	$params['data']  = $data;
    	$params['user_id'] = session('user')['user_id'];

    	
    	$country_language_result = $this->callSoaErp('post','/system/updateCoungtryLanguageByCountryLanguageId',$params);
    	return $country_language_result;
    }
    
    /////以下是部门管理
    /**
     * 公司管理显示页面
     */
    public function showCompanyManage(){

        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $company_name = input("company_name");
        if(!empty($company_name)){
            $data['company_name'] = $company_name;
        }
        if(is_numeric(input('status'))){
            $data['status'] = input('status');
        }
        $data_result = $this->callSoaErp('post', '/system/getCompany',$data);
        //$this->assign('data',$data_result['data']);
        $this->getPageParams($data_result);
    	return $this->fetch('company_manage');
    	
    }
    /**
     * 新增公司管理显示页面
     */
    public function showCompanyAdd(){
    	//获取国家地区
        $data1['status'] = 1;
        $country_result = $this->callSoaErp('post', '/system/getCountryCity',$data1);

    	//获取货币
    	$currency_data = ['status'=>1];
    	$currency_data_result = $this->callSoaErp('post', '/system/getCurrency',$currency_data);
    	
    	//获取语言
    	$language_data_result = $this->callSoaErp('post', '/system/getLanguage',$currency_data);
    	
    	$this->assign('country_data_result',$country_result['data']);
    	$this->assign('currency_data_result',$currency_data_result['data']);
    	$this->assign('language_data_result',$language_data_result['data']);
    	return $this->fetch('company_add');
    }
    /**
     * 新增公司AJAX
     */
    public function addCompanyAjax(){
    	$data = Request::instance()->param();
    	$data['user_id'] = session('user')['user_id'];
    	$result = $this->callSoaErp('post', '/system/addCompany',$data);
    	return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 编辑公司管理显示页面
     */
    public function showCompanyEdit(){
    	$company_id = input('company_id');

        if(session('user')['company_id']==1){
            $company_data = [
                'choose_company_id'=>$company_id,
            ];
        }else{
            $company_data = [
                'company_id'=>$company_id,
            ];
        }
    	$data = $this->callSoaErp('post','/system/getCompany',$company_data);
    	
    	//获取国家地区
        $data1['status'] = 1;
        $country_result = $this->callSoaErp('post', '/system/getCountryCity',$data1);
        
    	//获取货币
    	$currency_data = ['status'=>1];
    	$currency_data_result = $this->callSoaErp('post', '/system/getCurrency',$currency_data);
    	 
    	//获取语言
    	$language_data_result = $this->callSoaErp('post', '/system/getLanguage',$currency_data);
    
    	$this->assign('data',$data['data'][0]);
    	$this->assign('country_data_result',$country_result['data']);
    	$this->assign('currency_data_result',$currency_data_result['data']);
    	$this->assign('language_data_result',$language_data_result['data']);
    	$this->assign('company_id',$company_id);
    	return $this->fetch('company_edit');
    }
    /**
     * 修改公司AJAX
     */
    public function editCompanyAjax(){
    	$data = Request::instance()->param();
    	$data['user_id'] = session('user')['user_id'];

        if(session('user')['company_id']==1){
            $data['choose_company_id'] = $data['company_id'];
        }

    	$result = $this->callSoaErp('post', '/system/updateCompanyByCompanyId',$data);
    	return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 公司详情页面显示
     */
    public function showCompanyInfo(){
    	$company_id = input('company_id');

        if(session('user')['company_id']==1){
            $company_data = [
                'choose_company_id'=>$company_id,
            ];
        }else{
            $company_data = [
                'company_id'=>$company_id,
            ];
        }
    	$data = $this->callSoaErp('post','/system/getCompany',$company_data);

    	$this->assign('data',$data['data'][0]);
    	
    	return $this->fetch('company_info');
    	
    }
    /////以上是公司管理
    
    /////以下开始是部门管理
    /**
     * 部门管理显示页面
     */
    public function showDepartmentManage(){

        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];

        if(!empty(input('department_name'))){
            $data['department_name'] = input('department_name');
        }
        if(!empty(input('status'))){
            $data['status'] = input('status');
        }
        $data['company_id'] =  session('user')['company_id'];
    	$result = $this->callSoaErp('post','/system/getDepartment',$data);

        $this->getPageParams($result);
    	return $this->fetch('department_manage');
    	 
    }
    /**
     * 新增部门管理显示页面
     */
    public function showDepartmentAdd(){

    	//获取公司
    	$company_data = [
    			'status'=>1,
            'company_id'=>session('user')['company_id']
        ];

    	$company_data_result = $this->callSoaErp('post', '/system/getCompany',$company_data);

    	$this->assign('company_data_result',$company_data_result['data']);

    	return $this->fetch('department_add');
    }
    /**
     * 新增部门AJAX
     */
    public function addDepartmentAjax(){
    	$data = Request::instance()->param();

    	$data['user_id'] = session('user')['user_id'];
    	$result = $this->callSoaErp('post', '/system/addDepartment',$data);

    	return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 编辑部门管理显示页面
     */
    public function showDepartmentEdit(){
    	$department_id = input('department_id');
    	 
    	 
    	$department_data = [
    			'department_id'=>$department_id,
    	];
    	$data = $this->callSoaErp('post','/system/getDepartment',$department_data);
    	//获取公司
    	$company_data = [
    	    'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
    	$company_data_result = $this->callSoaErp('post', '/system/getCompany',$company_data);
    
    	$this->assign('data',$data['data'][0]);
    	$this->assign('company_data_result',$company_data_result['data']);

    	$this->assign('department_id',$department_id);
    	return $this->fetch('department_edit');
    }
    /**
     * 修改部门AJAX
     */
    public function editDepartmentAjax(){
    	$data = Request::instance()->param();
    	$data['user_id'] = session('user')['user_id'];
    	$result = $this->callSoaErp('post', '/system/updateDepartmentByDepartmentId',$data);
    	return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 部门详情页面显示
     */
    public function showDepartmentInfo(){
    	$department_id = input('department_id');
    
    
    	$department_data = [
    			'department_id'=>$department_id,
    	];
    	$data = $this->callSoaErp('post','/system/getDepartment',$department_data);
    
    	$this->assign('data',$data['data'][0]);
    
    	 
    	return $this->fetch('department_info');
    	 
    }    
    
    
    /////以上是部门管理
	
    /////以下是职位管理
    /**
     * 职位管理显示页面
     */
    public function showJobManage(){

        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];

		
        if(!empty(input('job_name'))){
            $data['job_name'] = input('job_name');
        }
        if(!empty(input('status'))){
            $data['status'] = input('status');
        }
        $data['company_id'] =  session('user')['company_id'];
    	$result = $this->callSoaErp('post','/system/getJob',$data);
	
        $this->getPageParams($result);
    	return $this->fetch('job_manage');
    
    }
    /**
     * 新增职位管理显示页面
     */
    public function showJobAdd(){
    
    	//获取公司
    	$company_data = [
    		'status'=>1,
    		'company_id'=>session('user')['company_id']	
    	];
    	$company_data_result = $this->callSoaErp('post', '/system/getCompany',$company_data);
    	$this->assign('company_data_result',$company_data_result['data']);
    
    	return $this->fetch('job_add');
    }
    /**
     * 新增职位AJAX
     */
    public function addJobAjax(){
    	$data = Request::instance()->param();
    	$data['user_id'] = session('user')['user_id'];
    	$result = $this->callSoaErp('post', '/system/addJob',$data);
    	return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 编辑职位管理显示页面
     */
    public function showJobEdit(){
    	$job_id = input('job_id');
    
    
    	$job_data = [
    			'job_id'=>$job_id,
    	];
    	$data = $this->callSoaErp('post','/system/getJob',$job_data);
    	
    	//获取公司
    	$company_data = [
    		'status'=>1,
            'company_id'=>session('user')['company_id']

        ];
        if(session('user')['company_id'] == 1){
            unset($company_data['company_id']);}
    	$company_data_result = $this->callSoaErp('post', '/system/getCompany',$company_data);
    	//获取部门
    	$department_data = [
    		'status'=>1,
    		'company_id'=>	$data['data'][0]['company_id']
    	];
    	
    	$department_data_result = $this->callSoaErp('post', '/system/getDepartment',$department_data);
    
    	
    	$this->assign('data',$data['data'][0]);
    	$this->assign('company_data_result',$company_data_result['data']);
    	$this->assign('department_data_result',$department_data_result['data']);
    	$this->assign('job_id',$job_id);
    	return $this->fetch('job_edit');
    }
    /**
     * 修改职位AJAX
     */
    public function editJobAjax(){
    	$data = Request::instance()->param();
       
    	$data['user_id'] = session('user')['user_id'];
    	$result = $this->callSoaErp('post', '/system/updateJobByJobId',$data);
    	return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 职位详情页面显示
     */
    public function showJobInfo(){
    	$job_id = input('job_id');
    
    
    	$job_data = [
    			'job_id'=>$job_id,
    	];
    	$data = $this->callSoaErp('post','/system/getJob',$job_data);
    
    
    	$this->assign('data',$data['data'][0]);
    
    
    	return $this->fetch('job_info');
    
    }    
    
    /////以上是职位管理
    
    
    /////以下是用户管理
    /**
     * 用户管理显示页面
     */
    public function showUserManage(){

        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];

        if(!empty(input('nickname'))){
            $data['nickname'] = input('nickname');
        }
        if(!empty(input('status'))){
            $data['status'] = input('status');
        }
        $data['company_id'] = session('user')['company_id'];
    	$result = $this->callSoaErp('post','/user/getUser',$data);
        $this->getPageParams($result);
    	return $this->fetch('user_manage');
    
    }
    /**
     * 新增用户管理显示页面
     */
    public function showUserAdd(){
    
    	//获取公司
    	$company_data = [
			'status'=>1,
            'company_id'=>session('user')['company_id']
    	];
    	$company_data_result = $this->callSoaErp('post', '/system/getCompany',$company_data);
    
    	$this->assign('company_data_result',$company_data_result['data']);
    	
    	//获取语言
    	$language_data = [
			'status'=>1
    	];
    	$language_data_result = $this->callSoaErp('post', '/system/getLanguage',$language_data);
        //获取角色
        $role_data = [
            'status'=>1
        ];
        $role_data_result = $this->callSoaErp('post', '/system/getRole',$role_data);
    	
    	$this->assign('company_data_result',$company_data_result['data']);   
    	$this->assign('language_data_result',$language_data_result['data']);
        $this->assign('role_data_result',$role_data_result['data']);
    	return $this->fetch('user_add');
    }
    /**
     * 新增用户AJAX
     */
    public function addUserAjax(){
    	$data = Request::instance()->param();
    	$data['user_id'] = session('user')['user_id'];
    	$result = $this->callSoaErp('post', '/user/addUser',$data);
    	
    	return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 编辑用户管理显示页面
     */
    public function showUserEdit(){
    	$user_id = input('user_id');
    
    
    	$user_data = [
    		'user_id'=>$user_id,
    	];
    	$data = $this->callSoaErp('post','/user/getUser',$user_data);
    	
    	//获取公司
    	$company_data = [
    		'status'=>1,
            'company_id'=>session('user')['company_id']
    	];
    	$company_data_result = $this->callSoaErp('post', '/system/getCompany',$company_data);
    	//获取部门
    	$department_data = [
    			'status'=>1,
    			'company_id'=>	$data['data'][0]['company_id']
    	];
    	 
    	$department_data_result = $this->callSoaErp('post', '/system/getDepartment',$department_data);

    	//获取语言
    	$language_data = [
    			'status'=>1
    	];
    	$language_data_result = $this->callSoaErp('post', '/system/getLanguage',$language_data);
    	//获取角色
    	$role_data = [
    			'status'=>1
    	];
    	$role_data_result = $this->callSoaErp('post', '/system/getRole',$role_data);
    	 
    	$this->assign('data',$data['data'][0]);
    	$this->assign('company_data_result',$company_data_result['data']);
    	$this->assign('department_data_result',$department_data_result['data']);
    	$this->assign('job_data_result',$job_data_result['data']);
    	$this->assign('language_data_result',$language_data_result['data']);
    	
    	$this->assign('role_data_result',$role_data_result['data']);
    	return $this->fetch('user_edit');
    }

    /**
     * setUserInfo
     *
     * 修改用户信息
     * @author shj
     * @return mixed
     * Date: 2019/2/25
     * Time: 16:22
     */
    public function setUserInfo(){
        $user_id = input('user_id');
        $user_data = [
            'user_id'=>$user_id,
        ];
        $data = $this->callSoaErp('post','/user/getUser',$user_data);

        //获取公司
        $company_data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $company_data_result = $this->callSoaErp('post', '/system/getCompany',$company_data);
        //获取部门
        $department_data = [
            'status'=>1,
            'company_id'=>	$data['data'][0]['company_id']
        ];

        $department_data_result = $this->callSoaErp('post', '/system/getDepartment',$department_data);

        //获取语言
        $language_data = [
            'status'=>1
        ];
        $language_data_result = $this->callSoaErp('post', '/system/getLanguage',$language_data);
        $this->assign('data',$data['data'][0]);
        $this->assign('language_data_result',$language_data_result['data']);
        $this->assign('company_data_result',$company_data_result['data']);
        $this->assign('department_data_result',$department_data_result['data']);
        return $this->fetch('set_user_info');
    }

    /**
     * 修改用户AJAX
     */
    public function editUserAjax(){
    	$data = Request::instance()->param();

		
    	$result = $this->callSoaErp('post', '/user/updateUserByUserId',$data);
    	
		if($result['code']==200){
			$user = session('user');
			$user['language_id'] = $data['language_id'];
			Session::set('user',$user);
			

		}
    	return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 用户详情页面显示
     */
    public function showUserInfo(){
    	$user_id = input('user_id');
    	$user_data = [
    		'user_id'=>$user_id

    	];
    	$data = $this->callSoaErp('post','/user/getUser',$user_data);

    
    	$this->assign('data',$data['data'][0]);


    	return $this->fetch('user_info');
    
    }    
    /**
     * 重置密码
     * 胡
     */
    public function showChangePassword(){
    	
    	return $this->fetch('change_password');
    	
    }
    
    /**
     * 发送邮件 AJAX
     */
    public function sendEmailByPassword(){
    	$data = [
    		'user_id'=>	session('user')['user_id'],
    			
    	];
    	
    	$result = $this->callSoaErp('post', '/user/findPasswordByEmail',$data);
    	return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 重置密码
     */
    public function ChangePasswordAjax(){

        $data = [
            'user_id'=>	session('user')['user_id'],

            'password'=>input('password')

        ];
        $result = $this->callSoaErp('post', '/user/updateUserByUserId',$data);
        return   $result;


//    	//首先判断验证码是否正确
//    	$check_data = [
//    		'user_id'=>	session('user')['user_id'],
//    		'code'=>input('code')
//    	];
//    	$result = $this->callSoaErp('post', '/user/checkEmail',$check_data);
//    	if($result['code']==200){//假如成功后 发送后台验证邮箱
//    		$data = [
//				'user_id'=>	session('user')['user_id'],
//
//    			'password'=>input('password')
//
//    		];
//    		$result = $this->callSoaErp('post', '/user/updateUserByUserId',$data);
//    		return   $result;
//    	}else{
//    		return $result;
//    	}
    	
    	

    	//['code' => '400', 'msg' => $data];    	
    	
    }
    /////以上是用户管理
    

    /**
     * 胡
     * 2018/11/15
     * 角色管理-(展示)界面
     */
    public function showRoleManage(){
    
    	$data = [
    		'page'=>$this->page(),
    		'page_size'=>$this->_page_size,
    	];
        $status = input('status');
        $role_name = input('role_name');
        if(!empty($role_name)){
            $data['role_name'] = $role_name;
        }
        if(is_numeric(input('status'))){
            $data['status'] = $status;
        }
       
    	$result =  $this->callSoaErp('post','/system/getRole',$data);
    	$this->getPageParams($result);
    	$this->assign('role_result',$result['data']);
    	
    	return $this->fetch("role_manage");
    
    }
    /**
     * 胡
     * 2018/11/15
     * 角色编辑显示界面
     */
    public function showRoleEdit(){
    	$role_id = input('role_id');
		$data['role_id'] = $role_id;
		$result = $this->callSoaErp('post', '/system/getRole',$data);
    	$this->assign('data',$result['data'][0]);
    	return $this->fetch("role_edit");
    
    }   
    /**
     * 编辑角色ajax
     */
    public function editRoleAjax(){
    	
    	$role_edit_data = [];
    	$role_edit_data['user_id'] = session('user')['user_id'];
    	
    	$role_edit_data['role_id']=input("role_id");
    	$role_edit_data['role_name']=input("role_name");
    	$role_edit_data['status']=input("status");

    		
    
    	$result = $this->callSoaErp('post', '/system/updateRoleByRoleId',$role_edit_data);
    	
    	return $result;
    	
    }
    /**
     * 显示角色新增页面
     */
    public function showRoleAdd(){
    	
    	return $this->fetch("role_add");
    }
    /**
     * 添加角色AJAX
     */
    public function addRoleAjax(){
    	
    	$role_add_data = [];
    	$role_add_data['user_id'] = session('user')['user_id'];
    	 
    	$role_add_data['role_id']=input("role_id");
    	$role_add_data['role_name']=input("role_name");

    	
    	
    	
    	$result = $this->callSoaErp('post', '/system/addRole',$role_add_data);
    	 
    	return $result;
    	
    	
    }
    /**
     * 胡
     * 2018/11/15
     * 权限管理-(展示)界面
     */
    public function showAuthManage(){
    
    	$data = [
    		'page'=>$this->page(),
    		'page_size'=>$this->_page_size,
    	];
    
    	if(!empty(input('auth_id'))){
    		$data['auth_id'] = input('auth_id');
    	}
    	if(!empty(input('controller_name'))){
    		$data['controller_name'] = input('controller_name');
    	}
    
    	if(!empty(input('status'))){
    		$data['status'] = input('status');
    	}
    	$result =  $this->callSoaErp('post','/system/getAuth',$data);
   
    	$this->getPageParams($result);
    	
    	 
    	return $this->fetch("auth_manage");
    
    }
    /**
     * 胡
     * 2018/11/15
     * 权限控制器编辑显示界面
     */
    public function showAuthControllerEdit(){
    	$auth_id = input('auth_id');
    	$data['auth_id'] = $auth_id;
    	$result = $this->callSoaErp('post', '/system/getAuth',$data);
  
    	$this->assign('vv',$result['data'][0]);
    	return $this->fetch("auth_controller_edit");
    
    }
    /**
     * 胡
     * 2018/11/15
     * 权限按钮编辑显示界面
     */
    public function showAuthButtonEdit(){
    	$auth_id = input('auth_id');
    	$data['auth_id'] = $auth_id;
    	$result = $this->callSoaErp('post', '/system/getAuth',$data);
    	//获取所有1级的权限
    	$params = [
    			'status'=>1,
    			'level'=>1
    	];
    	$auth_level1_result = $this->callSoaErp('post', '/system/getAuth',$params);
    	$this->assign('auth_level1_result',$auth_level1_result['data']);
    	
    	$this->assign('v',$result['data'][0]);
    	return $this->fetch("auth_button_edit");
    
    }
    /**
     * 编辑权限ajax
     */
    public function editAuthAjax(){
    	 
    	$edit_data = [];
    	$edit_data['user_id'] = session('user')['user_id'];
    	$edit_data['auth_id']=input("auth_id");
    	$edit_data['controller_name']=input("controller_name");
    	$edit_data['function_name']=input("function_name");
    	$edit_data['button_name']=input("button_name");
    	$edit_data['controller_explain']=input("controller_explain");
    	$edit_data['function_explain']=input("function_explain");
    	$edit_data['button_explain']=input("button_explain");
    	$edit_data['status']=input("status");
    	$edit_data['pid']=input("pid");
    	$edit_data['level']=input("level");
    
    
    	$result = $this->callSoaErp('post', '/system/updateAuthByAuthId',$edit_data);
    	
    	return $result;
    	 
    }
    /**
     * 显示权限新增控制器页面
     */
    public function showAuthControllerAdd(){
    	//获取所有1级的权限

    	return $this->fetch("auth_controller_add");
    }
    /**
     * 显示权限新增按钮页面
     */
    public function showAuthButtonAdd(){
    	//获取所有1级的权限
    	$params = [
    			'status'=>1,
    			'level'=>1
    	];
    	$auth_level1_result = $this->callSoaErp('post', '/system/getAuth',$params);
    	$this->assign('auth_level1_result',$auth_level1_result['data']);
    	return $this->fetch("auth_button_add");
    }
    /**
     * 添加权限AJAX
     */
    public function addAuthAjax(){
    	 
    	$auth_add_data = [];
    	$auth_add_data['user_id'] = session('user')['user_id'];
    
    	$auth_add_data['controller_name']=input("controller_name");
    	$auth_add_data['function_name']=input("function_name");
    	$auth_add_data['button_name']=input("button_name");
    	$auth_add_data['controller_explain']=input("controller_explain");
    	$auth_add_data['function_explain']=input("function_explain");
    	$auth_add_data['button_explain']=input("button_explain");
    	$auth_add_data['level']=input("level");
    	$auth_add_data['pid']=input("pid");
    	 
    	 
    	$result = $this->callSoaErp('post', '/system/addAuth',$auth_add_data);
    
    	return $result;
    	 
    	 
    }    
   
    /**
     * 权限配置展示页面
     * 2018-11-16
     * 胡
     */
    public function showAuthConfigEdit(){
    	//获取所有权限
    	
    	$result = $this->callSoaErp('post', '/system/getAuthConfig',[]);
    	$role_id = input('role_id');
    	$role_auth = $this->callSoaErp('post', '/system/getAuthConfigByRoleId',['role_id'=>$role_id]);
	
    	$this->assign('result',$result['data']);
    	$this->assign('role_auth',$role_auth['data']);
    	return $this->fetch("auth_config_edit");
    }
   	
    /**
     * 权限配置提交AJAX
     */
    public function authRoleEditAjax(){
    	$role_id = input('role_id');
    	$auth_id = input('auth_id');
    	$data = [
    		'role_id'=>$role_id,
    		'auth_str'=>$auth_id
    	];
    	
    	$result = $this->callSoaErp('post', '/system/updateAuthRole',$data);
    	
    
    	return $result;
    }
    /**
     * 账单模版
     * 2018-12-4
     * wang
     */
    public function showBillTemplateManage(){

        //获取账单模板
        $bill_data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $bill_template_title = input("bill_template_title");
        $status = input("status");
        if(!empty($bill_template_title)){
            $bill_data['bill_template_title'] = $bill_template_title;
        }
        if(is_numeric(input('status'))){
            $bill_data['status'] = input('status');
        }
        $bill_data['company_id'] =  session('user')['company_id'];
        $bill_data_result = $this->callSoaErp('post', '/system/getBillTemplate',$bill_data);
        //$this->assign('bill_data_result',$bill_data_result['data']);
        $this->getPageParams($bill_data_result);
        return $this->fetch("bill_template_manage");
    }
    /**
     * 账单模版添加
     * 2018-12-4
     * wang
     */
    public function showBillTemplateAdd(){

        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        $this->assign('company_result',$company_result['data']);
        return $this->fetch("bill_template_add");
    }
    //账单模版添加提交操作
    public function addBillTemplateAjax(){

        $bill_template_title = input("bill_template_title");
        $bill_template_title_pic = input("bill_template_title_pic");
        $bill_template_foot_pic = input("bill_template_foot_pic");
        $choose_company_id = input("choose_company_id");
        $status = input("status");
        $user_id = session('user')['user_id'];
        $data = [
            "bill_template_title"=>$bill_template_title,
            "bill_template_title_pic"=>$bill_template_title_pic,
            'bill_template_foot_pic'=>$bill_template_foot_pic,
            'choose_company_id'=>$choose_company_id,
            'user_id'=>$user_id,
            'status'=>$status,
        ];

        $result = $this->callSoaErp('post','/system/addBillTemplate',$data);

        return $result;
    }
    /**
     * 账单模版修改
     * 2018-12-4
     * wang
     */
    public function showBillTemplateEdit(){
        $bill_template_id = input('bill_template_id');
        //获取账单信息
        $bill_data = [
            'bill_template_id'=>$bill_template_id,

        ];
        $bill_data_result =  $this->callSoaErp('post', '/system/getBillTemplate',$bill_data);

        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        $this->assign('company_result',$company_result['data']);
        $this->assign('bill_data_result',$bill_data_result['data']);
        return $this->fetch("bill_template_edit");
    }
    //账单模版添加修改
    public function editBillTemplateAjax(){
        $bill_template_id = input("bill_template_id");
        $bill_template_title = input("bill_template_title");
        $bill_template_title_pic = input("bill_template_title_pic");
        $bill_template_foot_pic = input("bill_template_foot_pic");
        $choose_company_id = input("choose_company_id");
        $status = input("status");
        $user_id = session('user')['user_id'];

        $data = [
            "bill_template_id"=>$bill_template_id,
            "bill_template_title"=>$bill_template_title,
            "bill_template_title_pic"=>$bill_template_title_pic,
            'bill_template_foot_pic'=>$bill_template_foot_pic,
            'choose_company_id'=>$choose_company_id,
            'status'=>$status,
            'user_id'=>$user_id,
        ];
        $result = $this->callSoaErp('post','/system/updateBillTemplateByBillTemplateId',$data);
        return $result;

    }
    /**
     * 账单模版详情
     * 2018-12-4
     * wang
     */
    public function showBillTemplateInfo(){
        $bill_template_id = input('bill_template_id');
        //获取账单信息
        $bill_data = [
            'bill_template_id'=>$bill_template_id,

        ];
        $bill_data_result =  $this->callSoaErp('post', '/system/getBillTemplate',$bill_data);
    
        $this->assign('bill_data_result',$bill_data_result['data']);

        return $this->fetch("bill_template_info");
    }
    /**
     * 税点管理
     */
    public function showTaxManage(){
        $choose_company_id = input('choose_company_id');
    	$status = input('status');
    	$data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        if(!empty($choose_company_id)){
            $data['choose_company_id'] = $choose_company_id;
        }
    	if(is_numeric($status)){
    		$data['status']= $status;
    	}
        $data['company_id'] =  session('user')['company_id'];
    	$tax_result = $this->callSoaErp('post', '/system/getTax',$data);
    	
    	//获取分公司接口
    	$company_params = [
    		'status'=>1		
    	];
    	$company_result = $this->callSoaErp('post', '/system/getCompany',$company_params);

    	//$this->assign('tax_result',$tax_result['data']);
        $this->getPageParams($tax_result);
        $this->assign('company_id',$data['company_id']);
        $this->assign('company_name',session('user')['company_name']);
    	$this->assign('company_result',$company_result['data']);
    	return $this->fetch("tax_manage");
    }
    
    /**
     * 添加税点
     */
    public function showTaxAdd(){
        //dump(session('user'));die;
    	//获取分公司接口
    	$company_params = [
    			'status'=>1,
    	];
    	$company_result = $this->callSoaErp('post', '/system/getCompany',$company_params);
    	//获取所有税点
    	$tax_result = $this->callSoaErp('post', '/system/getTax',[]);
    	
    	$this->assign('tax_result',$tax_result['data']);
    	$this->assign('company_result',$company_result['data']);
    	return $this->fetch("tax_add");
    }
    /**
     * 添加税点AJAX
     */
    public function addTaxAjax(){
        $choose_company_id = input("choose_company_id");
    	$tx_cd = input('tx_cd');
    	$gst_rate = input('gst_rate');
    	$pst_rate = input('pst_rate');
    	$hst_rate = input('hst_rate');
		$otx = input('otx');
    	$note = input('note');
    	$status = input('status');
    	
    	$data = [
    		'status'=>$status,
    		'choose_company_id'=>$choose_company_id,
    		'txcd'=>$tx_cd,
    		'gstrate'=>$gst_rate,
    		'pstrate'=>$pst_rate,
    		'hstrate'=>$hst_rate,
			'otx'=>$otx,
    		'note'=>$note
    	];

    	$tax_result = $this->callSoaErp('post', '/system/addTax',$data);
    	
    	return $tax_result;
    	
    }
    /**
     * 修改税点页面
     */
    public function showTaxEdit(){
    	$tax_id = input('tax_id');
    	
    	$data = [
    		'tax_id'=>$tax_id		
    	];
    	
    	
    	$tax_result = $this->callSoaErp('post', '/system/getTax',$data);
 	
    	//获取分公司接口
    	$company_params = [
    			 
    			'status'=>1
    	];
    	$company_result = $this->callSoaErp('post', '/system/getCompany',$data);
    	
    	//获取所有税点
    	$tax_info = $this->callSoaErp('post', '/system/getTax',[]);
    	
    	$this->assign('tax_info',$tax_info['data']);
    	
    	$this->assign('company_result',$company_result['data']);
    	$this->assign('tax_result',$tax_result['data'][0]);
    	$this->assign('tax_id',$tax_id);
    	return $this->fetch("tax_edit");
    }
    /**
     * 修改税点AJAX页面
     */
    public function editTaxAjax(){
        $choose_company_id = input("choose_company_id");
    	$tx_cd = input('tx_cd');
    	$gst_rate = input('gst_rate');
    	$pst_rate = input('pst_rate');
    	$hst_rate = input('hst_rate');
		$otx = input('otx');
    	$note = input('note');
    	$status = input('status');
    	$tax_id = input('tax_id');
    	$data = [
    			'status'=>$status,
    			'choose_company_id'=>$choose_company_id,
    			'txcd'=>$tx_cd,
    			'gstrate'=>$gst_rate,
    			'pstrate'=>$pst_rate,
    			'hstrate'=>$hst_rate,
				'otx'=>$otx,
    			'note'=>$note,
    			'tax_id'=>$tax_id
    	];
    	 
    	$tax_result = $this->callSoaErp('post', '/system/updateTaxByTaxId',$data);
    	 
    	return $tax_result;
    	
    	
    	
    }
    /**
     * 汇率模版
     * wang
     */
    public function showCurrencyProportionManage(){

        //获取货币
        $currency = [
            'status'=>1
        ];
        $currency_result = $this->callSoaErp('post', '/system/getCurrency',$currency);
        //搜索
        $currency_id = input("currency_id");
       
        if(!empty($currency_id)){
            $currency_data['currency_id'] = $currency_id;
            
        }
        $time = input("proportion_time");
        if(empty($time)){
        	$time = date('Ym');
        }
        $date_time = date('Ym');
        //获取汇率
        $currency_data["proportion_time"] = date("Ymd",strtotime('-1 days',strtotime("+1 month",strtotime($time."01"))));
      
		
        $currency_proportion_result = $this->callSoaErp('post', '/system/getCurrencyProportion',$currency_data);

        //获取当前年月
        $this->assign('date_time',$date_time);
        $this->assign('currency_result',$currency_result['data']);
        $this->assign('currency_proportion_result',$currency_proportion_result['data']);
        return $this->fetch("currency_proportion_manage");
    }
    /**
     * 汇率修改
     * wang
     */
    public function showCurrencyProportionEdit(){
        $currency = [
            'status'=>1
        ];
        $currency_result = $this->callSoaErp('post', '/system/getCurrency',$currency);
       
        $time = input("proportion_time");


        //获取汇率
        $currency_data = [
            "proportion_time"=> date("Ymd",strtotime('-1 days',strtotime("+1 month",strtotime($time."01"))))
        ];

        $currency_proportion_result = $this->callSoaErp('post', '/system/getCurrencyProportion',$currency_data);
    
		
        $this->assign('currency_result',$currency_result['data']);
        $this->assign('currency_proportion_result',$currency_proportion_result['data']);
        return $this->fetch("currency_proportion_edit");
    }
    /***
     * 修改汇率AJAX页面
     */
    public function editCurrencyProportionAjax(){
        $time=input("proportion_time");
        //获取 当前月的最后一天
        
      
        $data=[
          'proportion_time'=>  date("Ymd",strtotime('-1 days',strtotime("+1 month",strtotime($time."01"))))
        ];
    
//        $currency_result = $this->callSoaErp('post', '/system/selectCurrencyProportion', $data);
//         if($currency_result['data']==1){
//             $data = json_decode(input("currency_proportion_arr"), true);
//             $data['user_id'] = session('user')['user_id'];

//             $data['currency_proportion_arr'] = $data;
//             $currency_result = $this->callSoaErp('post', '/system/updateCurrencyProportionByCurrencyProportionId', $data);

//         }else{
            $currency_proportion_arr = json_decode(input("currency_proportion_arr"), true);

            $data['currency_proportion_arr'] = $currency_proportion_arr;

            $currency_result = $this->callSoaErp('post', '/system/addProportionByCurrencyProportion', $data);

       // }

        return $currency_result;

    }
    /**
    *调取控制面板AJAX
    */
    public function getDashboardAjax(){
        $data['company_id']=session('user')['company_id'];
        $result = $this->callSoaErp('post', '/system/getDashboard', $data);
        return $result;
    }
    
    //获取货币AJAX
    public function getCurrencyAjax(){
    	$params = Request::instance()->param();
    	$params['status']=1;
    	$result = $this->callSoaErp('post', '/system/getCurrency', $params);
    	return $result;
    }
    //获取公司AJAX
    public function getCompanyAjax(){
    	$params = Request::instance()->param();
    	$params['status']=1;
    	$result = $this->callSoaErp('post', '/system/getCompany', $params);
  
    	return $result;
    }
    
	public function getTaxAjax(){
		$params = Request::instance()->param();
		$params['company_id'] = session('user')['company_id'];
		$result = $this->callSoaErp('post', '/system/getTax', $params);
		
		return $result;
	}
	
	/**
	 * 获取国家区域AJAX
	 */
	public function getCountryAjax(){
		$params = Request::instance()->param();
		$result = $this->callSoaErp('post', '/system/getCountry', $params);
	
		return $result;
	}
	
	/**
	 * 获取语言ajax
	 */
	public function getLanguageAjax(){
		$params = Request::instance()->param();
		$result = $this->callSoaErp('post', '/system/getLanguage', $params);
	
		return $result;
	}
	/**
	 * 获取账单模板
	 */
	public function getBillTemplateAjax(){
		$params = Request::instance()->param();
		$params['company_id'] = session('user')['company_id'];
		$result = $this->callSoaErp('post', '/system/getBillTemplate', $params);
		
		return $result;
		
	}

    /**
     * showTagManage
     *
     * 标签管理显示页面
     * @author shj
     * @return void
     * Date: 2019/3/26
     * Time: 15:55
     */
    public function showTagManage(){
        $tag_name = input('tag_name');
        $type_name = input('type_name');
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        if(!empty($tag_name)){
            $data['tag_name'] = $tag_name;
        }
        $data['language_id'] = session('user')['language_id'];
        if($type_name){
            $data['type_name']= $type_name;
        }
        $result = $this->callSoaErp('post', '/system/getTag',$data);


        $type_data = ['公共','控制面板','资源管理','产品管理','分公司管理','财务管理','审批管理','系统设置','提醒管理'];
        $this->assign('type_data',$type_data);
        $this->getPageParams($result);
        return $this->fetch('tag_manage');
    }

    /**
     * showTagAdd
     *
     * 新增标签页面
     * @author shj
     * @return mixed
     * Date: 2019/4/18
     * Time: 14:15
     */
    public function showTagAdd(){

        //获取语言
        $data = [
            'status'=>1
        ];
        $language_data = $this->callSoaErp('post', '/system/getLanguage',$data);

        $this->assign('language_data',$language_data['data']);

        $type_data = ['公共','控制面板','资源管理','产品管理','分公司管理','财务管理','审批管理','系统设置','提醒管理'];
        $this->assign('type_data',$type_data);

        return $this->fetch('tag_add');
    }

    /**
     * addTagAjax
     *
     * 标签管理新增数据
     * @author shj
     * @return array|bool|mixed|string
     * Date: 2019/4/18
     * Time: 16:29
     */
    public function addTagAjax(){

        $data = Request::instance()->param();

        $data['user_id'] = session('user')['user_id'];

        $result = $this->callSoaErp('post', '/system/addTag',$data);

        return $result;

    }


    /**
     * showTagEdit
     *
     * 标签管理修改页面
     * @author shj
     * @return mixed
     * Date: 2019/3/29
     * Time: 17:21
     */
    public function showTagEdit(){
        $code_name = input('code_name');
        $data['code_name'] = $code_name;
        $data['status'] = 1;
        $result = $this->callSoaErp('post', '/system/getTag',$data);
        $group = Arrays::group($result['data'],function($ar){
            return $ar['language_id'];
        });

        $this->assign('result',$group);
        //获取语种
        $getLanguage = $this->callSoaErp('post','/system/getLanguage',['status'=>1]);
        $this->assign('LanguageAr',$getLanguage['data']);


        return $this->fetch('tag_edit');
    }

    /**
     * editTagAjax
     *
     * 标签管路修改数据
     * @author shj
     * @return array|bool|mixed|string
     * Date: 2019/4/18
     * Time: 16:29
     */
    public function editTagAjax(){

        $params = Request::instance()->param();
        //echo "<pre>";print_r($params);echo "<pre>";die;

        foreach ($params['language_id'] as $k=>$v){
            $data['language_id'] = $v;
            $data['code_name'] = $params['code_name'];
            $data['type_name'] = $params['type_name'];
            $data['tag_name'] = $params['tag_name'][$k];
            $data['user_id'] = session('user')['user_id'];
            $result = $this->callSoaErp('post', '/system/updateTag',$data);
        }

        return $result;

    }

    /**
     * 发送邮件ajax
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/7
     * Time: 10:22
     */
    public function sendEmailAjax()
    {
        $data = Request::instance()->param();


        $result = $this->callSoaErp('post', '/system/sendEmail',$data);

        return $result;
    }
}

