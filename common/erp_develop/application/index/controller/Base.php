<?php
namespace app\index\controller;
use app\common\help\Help;
use think\Lang;
use think\config;
use think\Request;
use think\Controller;
use app\common\help\Contents;
use Underscore\Types\Arrays;
use think\Cache;

class Base extends Controller
{
    protected $_soaerpConfig;
    protected $_soaerpUrl;
    protected $_controller_name;
    protected $_function_name;
    protected $_page_size;
    protected $_authConfig;
    protected $_http_referer;

    public $room_type;

    public function __construct()
    {
        if(empty(session('user')) || is_null(session('user'))){
            echo "<script>location.href='/login/show'</script>";
        }
	
    
        /***
         * 判断是否是初始密码
         */
        if(session('user')['base_password']==1){
        	if(strtolower(Request::instance()->controller())=='system' && strtolower(Request::instance()->action())=='showchangepassword'){
        		//$this->assign('base_tixing','请重置您的密码Please reset your password');
        	}else{
        		if(strtolower(Request::instance()->action())=='sendsystememailajax'){
        			
        		}else{
        			echo "<script>location.href='/system/showChangePassword/user_id/".session('user')['user_id']."'</script>";
        		}
        		
        		
        		
        		
        	}
        	
        	
        }
        
        
       	if(session('user')['language_id'] ==1){
       		cookie('think_var','zh-cn');
       	}else if(session('user')['language_id'] ==2){
       		cookie('think_var','zh-hk');
       	}else if(session('user')['language_id'] ==3){
       		cookie('think_var','fr-fr');
       	}else if(session('user')['language_id'] ==4){
       		cookie('think_var','ru-ru');
       	}else if(session('user')['language_id'] ==7){
       		cookie('think_var','en-us');
       	}else{
       		cookie('think_var','zh-cn');
       	}


	
        $this->_soaerpConfig= config('soaerp');

        $this->_soaerpUrl = $this->_soaerpConfig['ip'].':'.$this->_soaerpConfig['port'];
        parent::__construct();
        
        $url_params = help::delUrlPage(Request::instance()->query());
        $url_params = substr($url_params, strpos($url_params, '&'));
        
       
        $this->assign('controller_name',strtolower(Request::instance()->controller()));
        $this->assign('function_name',strtolower(Request::instance()->action()));
		$this->assign('url_params',$url_params);
        $this->assign('page_url','http://'.Request::instance()->host().Request::instance()->baseUrl());
        $this->assign('language_tag', json_decode(Cache::get('tag_language'.session('user')['user_id']), true));

        //搜索缓存 TODO
        $this->assign('currency_manage', cookie('currency_manage')?:'/system/showCurrencyManage?status=1');
        $this->assign('supplier_manage', cookie('supplier_manage')?:'/source/showSupplierManage?status=1');
        $this->assign('route_template_manage', cookie('route_template_manage')?:'/product/showRouteTemplateManage?status=1');
        $this->assign('plan_tour_manage', cookie('plan_tour_manage')?:'/product/ShowPlanTour?status=1');
        $this->assign('route_type_manage', cookie('route_type_manage')?:'/product/showRouteTypeManage?status=1&pid=0');
        $this->assign('branch_product_manage', cookie('branch_product_manage')?:'/branchcompany/showBranchProductManage?status=1');
        $this->assign('branch_product_type_manage', cookie('branch_product_type_manage')?:'/branchcompany/showBranchProductTypeManage?status=1');
        $this->assign('customer_manage', cookie('customer_manage')?:'/branchcompany/showCustomerManage?status=1');
        $this->assign('distributor_manage', cookie('distributor_manage')?:'/branchcompany/showDistributorManage?status=1');
        $this->assign('receivable_manage', cookie('receivable_manage')?:'/finance/showReceivableManage?status=1');
        $this->assign('mustpay_manage', cookie('mustpay_manage')?:'/finance/showMustPayManage?status=1');


        if($_GET['bool']!=1){
            $where['eTime'] = time();
            $where['user_id'] = session('user')['user_id'];
            $where['status'] = 1;
            $getInStationLetter =  $this->callSoaErp('post','/system_alert_event/getInStationLetter',$where);
            $this->assign('InStationLetterStime',$where['eTime']);
            $this->assign('InStationLetterList',$getInStationLetter['data']);
            unset($where);
        }

        //获取房型
        $RoomType = $this->callSoaErp('post','/system/getRoomType',[]);
        $RoomType_ar = [];
        foreach($RoomType['data'] as $v){
            $RoomType_ar[$v['room_type_id']] = $v['room_type_name'];
        }
        $this->room_type = $RoomType_ar;

        //获取语言库数据
        Cache::rm('MultilingualAll_group');
        $MultilingualAll_group = Cache::get('MultilingualAll_group');
        //var_dump($MultilingualAll_group);exit;
        if(empty($MultilingualAll_group)){
            $where['status'] = 1;
            $MultilingualAll = $this->callSoaErp('post','/multilingual/selMultilingualAll',$where);
            $MultilingualAll_data = $MultilingualAll['data'];
            $MultilingualAll_group = Arrays::group($MultilingualAll['data'],function($ar){
                return $ar['original_table_name'] .'-'. $ar['original_table_field_name'] .'-'. $ar['original_table_id'] .'-'. $ar['language_id'];
            });
            Cache::set('MultilingualAll_group',$MultilingualAll_group,86400);
        }

        $this->assign('MultilingualAll',$MultilingualAll_group);
        unset($where);
//        var_dump($MultilingualAll_group);exit;

       	//分页默认数量
       	$this->_page_size = Contents::PAGE_SIZE;
       	$this->assign('page_size',Contents::PAGE_SIZE);
       	//获取权限
       // $this->_authConfig = $this->callSoaErp('post','/system/getAuthConfig',[])['data'];
       	
       	//开始判断权限是否有
       	$controller_name =  strtolower(Request::instance()->controller());
       	$function_name =  Request::instance()->action();
		$user_auth_config  = session('authConfig');
		$check_auth = false;

        $this->assign('auth_id',array_column($user_auth_config,'auth_id'));
		//echo $controller_name.' '.$function_name;

		for($i=0;$i<count($user_auth_config);$i++){
			if($controller_name==$user_auth_config[$i]['controller_name'] && $function_name== strtolower($user_auth_config[$i]['function_name'])){
				$check_auth =  true;
			}
		}
		if($check_auth==false){
			
			//echo 1;
			//$this->error('没权限','/');
		}
		$this->_http_referer = $_SERVER['HTTP_REFERER'];
		$this->assign('http_referer',$_SERVER['HTTP_REFERER']);
		//dump(session('user'));
	
    }



    /**
     * 读取soaerp方法
     */
    public function callSoaErp($method,$function,$data=[]){

        $data['appKey'] = 'nexus';
        $data['appSecret']='nexusIt';
        $data['lang_id']  = session('user')['language_id'];
        $data['now_user_id']  = session('user')['user_id'];
        $data['user_company_id']  = $data['user_company_id'] ? $data['user_company_id'] : session('user')['company_id'];
        $data['user_company_name']  = session('user')['company_name'];
		if(session('user')['company_id']==1){			
			unset($data['company_id']);	
			if(isset($data['choose_company_id'])){
				$data['company_id'] = $data['choose_company_id'];
			}
		}
	

        $result = Help::http($method,$this->_soaerpUrl.$function,$data);
		//error_log(print_r($result,1));
//		dump($result);exit;
		$result = json_decode($result,true);
	
		//dump($result);
		if($result['code']==200){
            $this->outPut($result['data']);
            $result = ['code' => '200', 'msg' => 'success','data'=>$result['data'],'count'=>$result['data_count']];

        }else{
            //$this->outPutError($result);
            $result = ['code' => '400', 'msg' => $result['msg']];
        }
        return $result;
    }
    //错误日志
    protected function outPutError($errorMsg) {

        // \think\Response::create(['code' => '400', 'msg' => $errorMsg['msg']], 'json')->send();
        //防止意外发生
        // exit;
    }
    //输出 之后加上日志存放REDIS
    public function outPut($result) {

        if(empty($result)){
            $result = [];
        }
        // \think\Response::create(['code' => '200', 'data' => $result], 'json')->send();
        //防止意外发生
        //exit;
    }


    /***
     * 获取供应商名称
     * @param $sid 供应商ID
     */
    public function getSupplierName($sid){
        //获取供应商
        $data['supplier_id'] = $sid;
        $SupplierAr =  $this->callSoaErp('post', '/source/getSupplier', $data);
        if($SupplierAr['code']==200){
            $this->assign('supplierName',$SupplierAr['data'][0]['supplier_name']);
        }else{
            $this->assign('supplierName','全部');
        }
    }
    /***
     * 获取供应商信息
     * @param $supplier_type_id 供应商分类
     */
    public function getSupplierBySupplierTypeId($supplier_type_id){
        //获取供应商
        $data['supplier_type_id'] = $supplier_type_id;
//        $data['status'] = 1;
        $SupplierAr =  $this->callSoaErp('post', '/source/getSupplier', $data);
        if($SupplierAr['code']==200){
            return $SupplierAr['data'];
        }
        return;
    }
//    /***
//     * 获取供应商信息
//     * @param $supplier_type_id 供应商分类
//     */
//    public function getSupplierBySupplierTypeId2($supplier_type_id){
//        //获取供应商
//        $data['supplier_type_id'] = $supplier_type_id;
//        $SupplierAr =  $this->callSoaErp('post', '/source/getSupplier2', $data);
//        if($SupplierAr['code']==200){
//            return $SupplierAr['data'];
//        }
//        return;
//    }


    /***
     * 获取货币
     * @param  array $data['currency_id','name','status']
     */
    public function getCurrency($data=null){
        if(empty($data)){
            $Currency = $this->callSoaErp('post','/config/getCurrency');
        }else{
            $Currency = $this->callSoaErp('post','/config/getCurrency',$data);
        }

        if($Currency['code']==200){
            return $Currency['data'];
        }
        return;
    }
    
    //获取当前页的方法
    public function page(){
    	$page = input('page')?input('page'):1;
    	return $page;
    }
	/**
	 * 获取分页的一些参数
	 */
    public function getPageParams($result){
    	$this->assign('total',$result['data']['count']);//总数
    	$this->assign('page',$this->page()); //当前页数
    	$this->assign('total_page',$result['data']['page_count']);//总共分几页
    	$this->assign('data',$result['data']['list']);//分页数据
    
    }
    
    protected function input() {
    	$contents = file_get_contents("php://input");
    	
    	$param = json_decode($contents, TRUE);
    	if (empty($param))
    		return $this->output([], 'json error', '0004');
    	return $param;
    }
    
    
    /**
     * 读取soaerp方法 用于测试
     */
    public function callSoaErpTest($method,$function,$data=[]){
    
    	$data['appKey'] = 'nexus';
    	$data['appSecret']='nexusIt';
    	$data['lang_id']  = session('user')['language_id'];
    	$data['now_user_id']  = session('user')['user_id'];
    	 
    	$data['user_company_id']  = session('user')['company_id'];
    	$data['user_company_name']  = session('user')['company_name'];
    	if(session('user')['company_id']==1){
    		unset($data['company_id']);
    		if(isset($data['choose_company_id'])){
    			$data['company_id'] = $data['choose_company_id'];
    		}
    	}
    
    
    	$result = Help::http($method,$this->_soaerpUrl.$function,$data);
    	//error_log(print_r($result,1));
    	//dump($result);
    	$result = json_decode($result,true);
    	//error_log(print_r($result,1));
    	//dump($result);
    	if($result['code']==200){
    		$this->outPut($result['data']);
    		$result = ['code' => '200', 'msg' => 'success','data'=>$result['data']];
    
    	}else{
    		//$this->outPutError($result);
    		$result = ['code' => '400', 'msg' => $result['msg']];
    	}
    	return $result;
    }

}
