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
	private $_soaerpConfig;
	private $_soaerpUrl;
    public function _initialize()
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
     * 调取报价账单AJAX
     */
    public function getPriceBillAjax(){
    
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
    	 
    	$sale_bill_result = $this->callSoaErp('post','/branchcompany/getPriceBill',$bill_template);
    
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
    	
    	//		dump($result);exit;
    	$result = json_decode($result,true);
    	//error_log(print_r($result,1));
    	//dump($result);
    	if($result['code']==200){
    	
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
		$company_order_number = input('company_order_number');
		$receivable_info_id = input('receivable_info_id');
		$default_bill_template_id = input('default_bill_template_id');
		$now_user_id = input('now_user_id');
		$this->assign('bill_template_id',$bill_template_id);
		$this->assign('company_order_id',$company_order_id);
		$this->assign('receivable_info_id',$receivable_info_id);
		$this->assign('default_bill_template_id',$default_bill_template_id);
		$this->assign('now_user_id',$now_user_id);
		return $this->fetch('pdf');
   	}
   	//销售收款
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
   		
   		$path_name = ROOT_PATH.'public/static/uploads/pdf/'.$time.'.pdf';
   		shell_exec("wkhtmltopdf  {$domain}/publicshow/pdf/now_user_id/{$now_user_id}/bill_template_id/{$bill_template_id}/company_order_id/{$company_order_id}/receivable_info_id/{$receivable_info_id}/default_bill_template_id/{$default_bill_template_id} {$path_name}");
   		//echo "wkhtmltopdf  {$domain}/publicshow/pdf/now_user_id/{$now_user_id}/bill_template_id/{$bill_template_id}/company_order_id/{$company_order_id}/receivable_info_id/{$receivable_info_id}/default_bill_template_id/{$default_bill_template_id} {$path_name}";
   		
   		return '/static/uploads/pdf/'.$time.'.pdf';
   	}
   	//报价PDF
   	public function pricePdf(){
   		$bill_template_id = input('bill_template_id');
   		$company_order_id = input('company_order_id');
   		$company_order_number = input('company_order_number');
   		$receivable_info_id = input('receivable_info_id');
   		$default_bill_template_id = input('default_bill_template_id');
   		$now_user_id = input('now_user_id');
   		$this->assign('bill_template_id',$bill_template_id);
   		$this->assign('company_order_id',$company_order_id);
   		$this->assign('receivable_info_id',$receivable_info_id);
   		$this->assign('default_bill_template_id',$default_bill_template_id);
   		$this->assign('now_user_id',$now_user_id);
   		return $this->fetch('pricepdf');
   	}
   	
   	//报价账单
   	public function downloadPricePdf(){
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
   		 
   		$path_name = ROOT_PATH.'public/static/uploads/pdf/'.$time.'.pdf';
   		shell_exec("wkhtmltopdf  {$domain}/publicshow/pricePdf/now_user_id/{$now_user_id}/bill_template_id/{$bill_template_id}/company_order_id/{$company_order_id}/receivable_info_id/{$receivable_info_id}/default_bill_template_id/{$default_bill_template_id} {$path_name}");
   		
   		error_log(print_r($bill_template_id,1));
   		error_log(print_r($company_order_id,1));
   		error_log(print_r($receivable_info_id,1));
   		error_log(print_r($default_bill_template_id,1));
   		error_log(print_r($now_user_id,1));
   		
   		//echo "wkhtmltopdf  {$domain}/publicshow/pdf/now_user_id/{$now_user_id}/bill_template_id/{$bill_template_id}/company_order_id/{$company_order_id}/receivable_info_id/{$receivable_info_id}/default_bill_template_id/{$default_bill_template_id} {$path_name}";
   		 
   		return '/static/uploads/pdf/'.$time.'.pdf';
   	}   
   	//erp 游客行程单
   	public function text_pdf(){
   		
   		$company_order_number = input('company_order_number');
   		$this->assign('company_order_number',$company_order_number);
   		return $this->fetch('/branchcompany/planTourIframe');
   		
   	}
   	//erp 游客行程单
   	public function downloadYkPdf(){
   		$company_order_number = input('company_order_number');
   		
   		
   		//获取当前域名
   		$request = Request::instance();
   		$request->root();
   		// 获取当前域名
   		$domain = $request->domain();
   		$time = time();
   		$path_name = ROOT_PATH.'public/static/uploads/pdf/'.$time.'.pdf';
   		
   		shell_exec("wkhtmltopdf  {$domain}/publicshow/text_pdf/company_order_number/{$company_order_number} $path_name");
   		return "{$domain}/static/uploads/pdf/".$time.".pdf";
   		
   	}
   	
   	//导游
   	//导游回执单
   	public function daoyou_pdf(){
		$number = input('number');
   		$team_product_id =  input('team_product_id');
   		$this->assign('number',$number);
   		$this->assign('team_product_id',$team_product_id);
   		return $this->fetch('/product/tourGuideIframe');
   		 
   	}  	
   	//erp 游客行程单
   	public function downloadDaoyouPdf(){
   		$team_product_id = input('team_product_id');
   		$team_product_number = input('team_product_number');
   		//获取当前域名
   		$request = Request::instance();
   		$request->root();
   		// 获取当前域名
   		$domain = $request->domain();
   		$time = time();
   		$path_name = ROOT_PATH.'public/static/uploads/pdf/'.$time.'.pdf';
   		
   		
   		$a = shell_exec("wkhtmltopdf  {$domain}/publicshow/daoyou_pdf/number/$team_product_number/team_product_id/{$team_product_id} $path_name");
   	//	echo $a;
   	//	echo "wkhtmltopdf  {$domain}/publicshow/daoyou_pdf/number/$team_product_number/team_product_id/{$team_product_id} $path_name";
   		return "{$domain}/static/uploads/pdf/".$time.".pdf";
   		 
   	}   
   	
   	
   	/**
   	 * 获取团队产品导游计划 AJAX请求
   	 */
   	public function getGuideReceiptInfoAjax(){
   		$team_product_number = input("team_product_number");
   		$team_product_id = input("team_product_id");
   		$data=[
   			"team_product_number"=>$team_product_number,
   			"team_product_id"=>$team_product_id
   		];
   	
   		$guide_receipt_result = $this->callSoaErp('post', '/product/getTeamProductGuideReceipt', $data);
   	
   		return $guide_receipt_result;
   	}
   	
   	public function downFinanceInfoAjax(){
   		$params = input('params');
   		$company_id = input('company_id');
   		$username = input('username');
   		//获取当前域名
   		$request = Request::instance();
   		$request->root();
   		// 获取当前域名
   		$domain = $request->domain();
   		$time = time();
   		$path_name = ROOT_PATH.'public/static/uploads/pdf/'.$time.'.pdf';
   		 
   		 
   		$a = shell_exec("wkhtmltopdf  {$domain}/publicshow/getfinanceInfo/params/$params/company_id/{$company_id}/username/$username $path_name");
   		//	echo $a;
   		//	echo "wkhtmltopdf  {$domain}/publicshow/daoyou_pdf/number/$team_product_number/team_product_id/{$team_product_id} $path_name";
   		return "{$domain}/static/uploads/pdf/".$time.".pdf";
   	
   	}
   	
   	//记账明细
   	public function getfinanceInfo(){
   		$params = input("params");
   		$company_id = input('company_id');
   		$zhanghao = input('zhanghao');
   		//根据字符串分割
   		$params = explode('_',$params);
   		$username = input('username');
   		$fukuanfang ='';
   		$tuanhao = '';
   		$kaihumingcheng ='';
   		$yinhanghanghao ='';
   		$yinhangmingcheng='';
   		$yinhangzhanghao ='';
   		$fukuanfangshi ='';
   		$jine=0;
   		$beizhu='';
   		$shenheren='';
   		$company_unit = '';
   		$company_params = [
   			'company_id'=>$company_id['company_id']	
   		];
   		$company_result = $this->callSoaErp('post', '/system/getCompany', $company_params);
   		
   		
   		
   		$company_result = $company_result['data'][0];
   		$company_unit = $company_result['unit'];
   	
   		$company_currency_id = $company_result['currency_id'];
   		
   		$new_result = [];
   		
   		//获取币种
   		for($i=0;$i<count($params);$i++){
   			//分割
   			$arr = $params[$i];
   			$arr_type = substr($arr,0,1);
   			$arr_id =substr($arr, 2);
   			if($arr_type==1){
   				$cope_info_params = [
   					'cope_info_id'=>$arr_id
   				];
   				$result = $this->callSoaErp('post', '/finance/getCopeInfo', $cope_info_params);
   				$result = $result['data'][0];
   				
   			}else{
   				$finance_approve_params = [
   					'finance_approve_id'=>$arr_id
   				];
   			
   				$finance_approve_result = $this->callSoaErp('post', '/finance/getFinanceApprove', $finance_approve_params);
   				$finance_approve_result = $finance_approve_result['data'][0];
   				$cope_params =[
   						'cope_number'=>$finance_approve_result['finance_number']
   				];
   				$cope_result = $this->callSoaErp('post', '/finance/getCope', $cope_params);
   				$cope_result = $cope_result['data'][0];
   				$result = [
   						'receivable_money'=>$finance_approve_result['money'],
   						'receivable_currency_id'=>$finance_approve_result['currency_id'],
   						'cope_voucher'=>$finance_approve_result['voucher_number'],
   						'voucher_time'=>$finance_approve_result['voucher_time'],
   						'receivable_type'=>$finance_approve_result['type'],
   						'remark'=>$finance_approve_result['remark'],
   						'attachment'=>$finance_approve_result['attachment'],
   						'unit'=>$finance_approve_result['currency_unit'],
   						'create_user_name'=>$finance_approve_result['create_user_name'],
   						'receivable_object_type'=>$cope_result['receivable_object_type'],
   						'receivable_object_id'=>$cope_result['receivable_object_id'],
   						'product_name'=>$cope_result['product_name'],
   						'finance_approve_id'=>$finance_approve_result['finance_approve_id'],
   						'team_product_id'=>$finance_approve_result['team_product_id'],
   						'source_type_id'=>$finance_approve_result['source_type_id'],
   						 
   				];
   				
   				if($result['receivable_object_type'] == 1){
   					$company_params['company_id'] = $result['receivable_object_id'];
   					$company_result = $this->callSoaErp('post', '/system/getCompany',$company_params);
   					$result['receivable_object_name'] = $company_result['data'][0]['company_name'];
   				}else if($result['receivable_object_type'] == 2){
   					$supplier_params['supplier_id'] =$result['receivable_object_id'];
   				
   					$supplier_result = $this->callSoaErp('post', '/source/getSupplier',$supplier_params);
   				
   					$result['receivable_object_name'] = $supplier_result['data'][0]['supplier_name'];
   				}else if($result['receivable_object_type'] == 3){
   					$distributor_params['distributor_id'] = $result['receivable_object_id'];
   					$distributor_result = $this->callSoaErp('post', '/btob/getDistributor',$distributor_params);
   				
   					$result['receivable_object_name'] = $distributor_result['data'][0]['distributor_name'];
   				}else{
   				
   					$result['receivable_object_name'] ='直客';
   				}
   				
   			}
   	
   			
   				//获取付款方
   				$fukuanfang.=','.$result['receivable_object_name'];
   				//获取团号
   				if(!empty($result['team_product_id'])){
   					$team_product_params = [
   						'team_product_id'=>	$result['team_product_id']
   					];
   					
   					$team_product_result = $this->callSoaErp('post', '/product/getTeamProductBase', $team_product_params);
   					$team_product_result = $team_product_result['data'][0];
   					
   					
   					
   					$tuanhao.=','.$team_product_result['team_product_number'];
   					$result['team_product_number'] = $team_product_result['team_product_number'];
   				}else{
   					$result['team_product_number'] = '';
   				}
   				//获取供应商4条信息
   				if($result['receivable_object_type']==2){ //代表供应商
   					$supplier_params = [
   						'supplier_id'=>$result['receivable_object_id']
   					];
   					$supplier_result = $this->callSoaErp('post', '/source/getSupplier', $supplier_params);
   					$supplier_result = $supplier_result['data'][0];
   					if(!empty($supplier_result['account_name'])){
   						$kaihumingcheng =','.$supplier_result['account_name'];
   					}
   					if(!empty($supplier_result['bank_code'])){
   						$yinhanghanghao =','.$supplier_result['bank_code'];
   					}
   					if(!empty($supplier_result['bank_name'])){
   						$yinhangmingcheng=','.$supplier_result['bank_name'];
   					}   					
   					if(!empty($supplier_result['bank_number'])){
   						$yinhangzhanghao =','.$supplier_result['bank_number'];
   					}

   				}

   				
   				//获取付款方式
   				if($result['receivable_type'] == 1){
   					$fukuanfangshi=','.'cash';
   				}else if($result['receivable_type'] ==2){
   					
   					$fukuanfangshi=','.'check';
   				}else if($result['receivable_type'] ==3){
   					$fukuanfangshi=','.'debit card';
   					
   				}else if($result['receivable_type'] ==4){
   					$fukuanfangshi=','.'credit card(mc)';
   					
   				}else if($result['receivable_type'] ==5){
   					$fukuanfangshi=','.'credit card(vs)';
   					
   				}else if($result['receivable_type'] ==6){
   					$fukuanfangshi=','.'credit card(ax)';
   					
   				}else if($result['receivable_type'] ==7){
   					$fukuanfangshi=','.'direct depsit';
   					
   				}else if($result['receivable_type'] ==8){
   					$fukuanfangshi=','.'others';
   					
   				}
   				
   				//金额也要相加 //开始换算汇率
   				
   			 $proporation_params = [
   			 	'currency_id'=>	$result['receivable_currency_id'],
        		'opposite_currency_id'=>$company_currency_id,
        		'proportion_time'=>help::getLastMonthDay()
        	];
        
        	$proporation_result = $this->callSoaErp('post','/system/getOneCurrencyProportion', $proporation_params);
   				
   			$proporation_result = $proporation_result['data'][0]['currency_proportion'];
   			
   			$jine+=number_format($result['receivable_money']*$proporation_result,2);
   			
   			//获取备注
   			if(!empty($result['remark'])){
   				
   			}
   			$beizhu.=','.$result['remark'];
   			

   			
   			$new_result[]	=$result;
   			
   			
   		}
  
   		//获取该公司的经理
   		$user_params = [
   				'companyid'=>$company_id,
   				'role_id'=>6
   		];
   		$user_result = $this->callSoaErp('post','/user/getUser', $user_params);
   			
   		$user_result = $user_result['data'];
   		
   		for($i=0;$i<count($user_result);$i++){
   			
   			if($i==0){
   				
   				$shenheren=$user_result[$i]['nickname'];
   			}else{
   				$shenheren.=','.$user_result[$i]['nickname'];
   			}
   			
   		}
		
   		
   		$this->assign('fukuanfang',trim($fukuanfang,','));
   		$this->assign('tuanhao',trim($tuanhao,','));
   		$this->assign('kaihumingcheng',trim($kaihumingcheng,','));
   		$this->assign('yinhanghanghao',trim($yinhanghanghao,','));
   		$this->assign('yinhangmingcheng',trim($yinhangmingcheng,','));
   		$this->assign('yinhangzhanghao',trim($yinhangzhanghao,','));   		
   		$this->assign('fukuanfangshi',trim($fukuanfangshi,','));
   		
   		$this->assign('jine',$jine);
   		$this->assign('company_unit',$company_unit);
   		
   		$this->assign('beizhu',trim($beizhu,','));
   		$this->assign('shenheren',$shenheren);
   	
   		$this->assign('result',$new_result);
   		$this->assign('username',$username);
   		return $this->fetch('finance_info');
   	}
   	
   	
   	
   	/**
   	 *
   	 */
   	public function getRoomTypeAjax(){
   		$params = Request::instance()->param();
   		$result = $this->callSoaErp('post', '/system/getRoomTypeAjax', $params);
   	
   		return $result;
   	}
   	/**
   	 * 获取游客行程单 AJAX请求
   	 */
   	public function getCompanyOrderCustomerJouneryMenuAjax(){
   		$company_order_number = input("company_order_number");
   	
   		$data=[
   				"company_order_number"=>$company_order_number
   		];
   	
   		$guide_receipt_result = $this->callSoaErp('post', '/branchcompany/getCompanyOrderCustomerJouneryMenu', $data);
   	
   		return $guide_receipt_result;
   	}   	
   	/**
   	 * 验证码
   	 */
   	public function showYzm(){
	
        $captcha = new \think\captcha\Captcha();


   		return $captcha->entry();
   		
   	}
   	/**
   	 * 重置密码
   	 */
   	public function ChangePasswordAjax(){
   	
   		$data = [
   				'user_id'=>	session('user')['user_id'],
   	
   				'password'=>input('password'),
   				 
   	
   		];
   		$this->callSoaErp('post', '/user/updateUserByUserId',$data);
   		return 1;
   	}
}
