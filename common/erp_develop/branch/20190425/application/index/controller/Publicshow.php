<?php
namespace app\index\controller;
use app\common\help\Help;
use app\index\controller\Base;
use \think\File;
use think\Session;
use think\Request;
use think\config;
use think\Controller;
class Publicshow extends Controller
{
    public function __initialize()
    {
    	$this->_soaerpConfig= config('soaerp');
    	
    	$this->_soaerpUrl = $this->_soaerpConfig['ip'].':'.$this->_soaerpConfig['port'];
    	parent::_initialize();
    }

    /**
     * 调取销售收款发票AJAX
     */
    public function getSaleBillAjax(){
    	 
    	$bill_template_id = input("bill_template_id");//账单ID
    	$company_order_number = input('company_order_number');//公司订单
    	$company_order_id = input("company_order_id");
    	if(!empty($company_order_id)){
    		$company_order_params['company_order_id'] = $company_order_id;
    		$company_order_result  = $this->callSoaErp('post','/branchcompany/getCompanyOrder',$company_order_params);
    
    		$company_order_number = $company_order_result['data'][0]['company_order_number'];
    	}
    	$receivable_info_id = input('receivable_info_id');//收款ID多个逗号隔开
    	$default_bill_template_id = input('default_bill_template_id',0);//是否默认 1为是
    	$bill_template = [
    			'bill_template_id'=>$bill_template_id,
    			'company_order_number'=>$company_order_number,
    			'receivable_info_id'=>$receivable_info_id,
    			'default_bill_template_id'=>$default_bill_template_id,
    			'now_user_id'=>input('now_user_id')
    	];
    	 
    	$sale_bill_result = $this->callSoaErp('post','/branchcompany/getSaleBill',$bill_template);
    	 
    	return $sale_bill_result;
    	 
    }
    /**
     * 读取soaerp方法
     */
    public function callSoaErp($method,$function,$data=[]){
    
    	$data['appKey'] = 'nexus';
    	$data['appSecret']='nexusIt';

    	$data['now_user_id']  =$data['now_user_id'];

    
    
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
   	public function pdf(){
		$bill_template_id = input('bill_template_id');
		$company_order_id = input('company_order_id');
		$receivable_info_id = input('receivable_info_id');
		$default_bill_template_id = input('default_bill_template_id');
		$now_user_id = input('now_user_id');
		$this->assign('bill_template_id',$bill_template_id);
		$this->assign('company_order_id',$company_order_id);
		$this->assign('receivable_info_id',$receivable_info_id);
		$this->assign('default_bill_template_id',$default_bill_template_id);
		$this->assign('now_user_id',$default_bill_template_id);
		return $this->fetch('pdf');
   	}
   	public function downloadPdf(){
   		$bill_template_id = input('bill_template_id');
   		$company_order_id = input('company_order_id');
   		$receivable_info_id = input('receivable_info_id');
   		$default_bill_template_id = input('default_bill_template_id');
   		$now_user_id = input('now_user_id');
   		$time = time();
   		//获取当前域名
   		$request = Request::instance();
   		$request->root();
   		// 获取当前域名
   		$domain = $request->domain();
   		
   		$path_name = ROOT_PATH.'public/static/uploads/pdf/'.time().'.pdf';
   		shell_exec("wkhtmltopdf  {$domain}/publicshow/pdf/now_user_id/{$now_user_id}/bill_template_id/{$bill_template_id}/company_order_id/{$company_order_id}/receivable_info_id/{$receivable_info_id}/default_bill_template_id/{$default_bill_template_id} {$path_name}");
   		error_log(print_r("wkhtmltopdf  {$domain}/publicshow/pdf/now_user_id/{$now_user_id}/bill_template_id/{$bill_template_id}/company_order_id/{$company_order_id}/receivable_info_id/{$receivable_info_id}/default_bill_template_id/{$default_bill_template_id} {$path_name}",1));
   		return '/static/uploads/pdf/'.time().'.pdf';
   	}
    
}
