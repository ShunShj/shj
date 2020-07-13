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

    public function __construct()
    {
        if(empty(session('user')) || is_null(session('user'))){
            echo "<script>location.href='/login/show'</script>";
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
        $this->assign('controller_name',strtolower(Request::instance()->controller()));
        $this->assign('function_name',strtolower(Request::instance()->action()));
		$this->assign('url_params',help::delUrlPage(Request::instance()->query()));
        $this->assign('page_url','http://'.Request::instance()->host().Request::instance()->baseUrl());
        $this->assign('language_tag', json_decode(Cache::get('tag_language'), true));

        if($_GET['bool']!=1){
            $where['eTime'] = time();
            $where['user_id'] = session('user')['user_id'];
            $where['status'] = 1;
            $getInStationLetter =  $this->callSoaErp('post','/system_alert_event/getInStationLetter',$where);
            $this->assign('InStationLetterStime',$where['eTime']);
            $this->assign('InStationLetterList',$getInStationLetter['data']);
            unset($where);
        }

        //获取语言库数据
        $where['status'] = 1;
        $MultilingualAll = $this->callSoaErp('post','/multilingual/selMultilingualAll',$where);
        $MultilingualAll_data = $MultilingualAll['data'];
        $MultilingualAll_group = Arrays::group($MultilingualAll['data'],function($ar){
            return $ar['original_table_name'] .'-'. $ar['original_table_field_name'] .'-'. $ar['original_table_id'] .'-'. $ar['language_id'];
        });
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
		
		//echo $controller_name.' '.$function_name;
		//dump($user_auth_config);
		for($i=0;$i<count($user_auth_config);$i++){
			if($controller_name==$user_auth_config[$i]['controller_name'] && $function_name== strtolower($user_auth_config[$i]['function_name'])){
				$check_auth =  true;
				
			}
		}
		if($check_auth==false){
			
			//echo 1;
			//$this->error('没权限','/');
		}

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
//		dump($result);exit;
		$result = json_decode($result,true);
		//error_log(print_r($result,1));
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
    	dump($result);
    	$result = json_decode($result,true);
    	//error_log(print_r($result,1));
    	dump($result);
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
