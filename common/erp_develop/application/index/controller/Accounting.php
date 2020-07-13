<?php
namespace app\index\controller;
use app\common\help\Help;
use app\index\controller\Base;
use think\Cookie;
use think\helper\Arr;
use think\Request;
use think\Session;
use Underscore\Types\Arrays;

//财务管理
class Accounting extends Base
{


    
    /**
     * 记账列表
     * 
     */
    public function showDepositPaymentManage(){
    	$params = Request::instance()->param();
    	$params['company_id'] =session('user')['user_company_id'];

    	
    	$data = [
    			'page'=>$this->page(),
    			'page_size'=>$this->_page_size,
    	];
    	$data['type'] = 1;
    	
    	$data['object_type'] = 3;
    	
    	if(isset($params['type'])){
    		$data['type'] = $params['type'];
    	}    	
    	//获取该公司下的所有代理
    	$distributor_data = [
    		'choose_company_id'=>session('user')['company_id']	
    	];
    	
    	$distributor_result = $this->callSoaErp('post','/btob/getBtoBDistributor', $distributor_data);

    	//$this->assign('receivable_data_result',$receivable_data_result['data']);

    	$result = $this->callSoaErp('post','/finance/getReceiptsPay', $data);
  
    
    	$this->getPageParams($result);
    	
    	//获取该公司下的所有人
    	$user_params = [
    		'company_id'=>session('user')['company_id'],
    		'status'=>1	
    	];
    	
    	$user_result = $this->callSoaErp('post','/user/getUser', $user_params);
    
    	$this->assign('distributor_result',$distributor_result['data']);
    	$this->assign('user_result',$user_result['data']);
    	return $this->fetch('receipts');
    }
    /**
     * 记账明细列表
     *
     */
    public function receiptsInfo(){
    	$params = Request::instance()->param();

    
    	 
    	$data = [
    		'page'=>$this->page(),
    		'page_size'=>$this->_page_size,
    		'receipts_pay_id'=>$params['receipts_pay_id']	
    	];
    	 

    	//$this->assign('receivable_data_result',$receivable_data_result['data']);
    
    	$result = $this->callSoaErp('post','/finance/getFinanceApprove', $data);
	
    	$this->getPageParams($result);
    	return $this->fetch('receipts_info');
    }   
    /**
     * 新建实收
     */
    public function receiptsAdd(){
    
    	//获取币种
    	$currency_params = [
    		'status'=>1	
    	];
    	$currency_result = $this->callSoaErp('post','/system/getCurrency', $currency_params);
    	
    	//获取本公司币种
		//获取所有公司对本公司币种的汇率
		$proporation_params = [
			'opposite_currency_id'=>session('user')['company_currency_id'],
			'proportion_time'=>date('Ymt', strtotime('-1 month'))
		];
	
		$proporation_result = $this->callSoaErp('post','/system/getOneCurrencyProportion', $proporation_params);
		$this->assign('proporation_result',$proporation_result['data']);
    	$this->assign('currency_result',$currency_result['data']);
    	return $this->fetch('receipts_add');
    }
    /**
     * 添加实收AJAX
     */
    public function receiptsAddAjax(){
    	$params = Request::instance()->param();
    	if(!empty($params['voucher_time'])){
    		$params['voucher_time'] = strtotime($params['voucher_time']);
    	}
    	$params['result_currency_id']=session('user')['company_currency_id'];
    	$result = $this->callSoaErp('post','/finance/addReceiptsPay', $params);
    	
    	return $result;
    }
    
    /**
     * 提交撤销AJAX
     */
    public function postFinanceRevocationAjax(){
    	$params = Request::instance()->param();

    	$result = $this->callSoaErp('post','/finance/postFinanceRevocation', $params);
    	 
    	return $result;
    	
    	
    }
    //显示应收汇总-批量收款
    public function showReceivableBtoBAllManage(){
    	//获取实收数据
    	$receipts_pay_id = input('receipts_pay_id');
    	$receipts_pay_params =[
    			'receipts_pay_id'=>$receipts_pay_id
    	];
    	$receipts_pay_result = $this->callSoaErp('post', '/finance/getReceiptsPay',$receipts_pay_params);
    	$receipts_pay_result = $receipts_pay_result['data'][0];
    
    	//搜索
    	$data['status'] = 1;
    
    	$data['is_like'] = 1;
    
    	/**
    	 * 获取币种
    	 */
    	$currency_params['status'] = 1;
    	$currency_result = $this->callSoaErp('post','/system/getCurrency',$currency_params);
    
    	$data['payment_object_type'] = $receipts_pay_result['object_type'];
    	if($receipts_pay_result['object_type']!=4){
    		$data['payment_object_id'] = $receipts_pay_result['object_id'];
    	}
    
    	$data['company_id'] = session('user')['company_id'];
    
    	$order_number = input('order_number');
    
    	if(!empty($order_number)){
    		$data['order_number'] = input('order_number');
    	}
    	$data = $this->callSoaErp('post','/finance/getReceivable',$data);
    
    
    	$data = $data['data'];
    
    		
    	$receivable_count=0;
    	for($i=0;$i<count($data);$i++){
    		if($data[$i]['receivable_money']-$data[$i]['true_receipt']<=0){
    			$receivable_count+=0;
    			$data[$i]['need_receivable_money'] =0;
    		}else{
    			$receivable_count+=$data[$i]['receivable_money']-$data[$i]['true_receipt'];
    			$data[$i]['need_receivable_money'] =$data[$i]['receivable_money']-$data[$i]['true_receipt'];
    		}
    
    	}
    	//获取当前汇率
    	$proportion_params = [
    			'opposite_currency_id'=>$receipts_pay_result['base_currency_id'],
    			'proportion_time'=>date('Ymt', strtotime('-1 month'))
    	];
    	$proportion_result = $this->callSoaErp('post','/system/getOneCurrencyProportion',$proportion_params);
    	$proportion_result= $proportion_result['data'];
    
    		
    
    	$this->assign('proportion_result',$proportion_result);
    	$this->assign('receipts_pay_result',$receipts_pay_result);
    	$this->assign('receivableInfo_data_result',$receivableInfo_data_result['data']);
    	$this->assign('receivable_data_result',$data);
    	$this->assign('receivable_count',$receivable_count);
    	$this->assign('currency_result',$currency_result['data']);
    	$this->assign('receivable_currency_id',$use_currency_id);
    	$this->assign('receipts_pay_id',$receipts_pay_id);
    	return $this->fetch('receivable_btob_all_manage');
    
    }


    public function showAccountTourCodeManage(){
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $account_code = input("account_code");
        $status = input("status");
        $tour_name = input("tour_name");
        $data['company_id'] =  session('user')['company_id'];
        $data['tour_name'] = $tour_name;
        $data['status'] = $status;
        $data['account_code'] = $account_code;
        $result = $this->callSoaErp('post', '/b2b_tour/getB2bTour',$data);


        $data1['status'] = 1;
        $data1['company_id'] =  session('user')['company_id'];

        $code_result = $this->callSoaErp('post', '/btob/getAccountCode',$data1);
        $this->assign('code_result',$code_result['data']);
        $this->getPageParams($result);

        return $this->fetch('account_tour_code_manage');
    }



    public function showAccountCodeManage(){
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $name = input("name");
        $status = input("status");

        $data['name'] = $name;
        $data['status'] = $status;
        $data['company_id'] =  session('user')['company_id'];

        $result = $this->callSoaErp('post', '/btob/getAccountCode',$data);

        $this->getPageParams($result);

        return $this->fetch('account_code_manage');
    }

    public function showAccountCodeAdd(){
        return $this->fetch('account_code_add');
    }

    public function addAccountCodeAjax(){
        $name = input("name");
        $status = input("status");
        $user_id = session('user')['user_id'];
        $company_id = input("choose_company_id") ? : session('user')['company_id'];
        $data = [
            "name"=>$name,
            "status"=>$status,
            "user_id"=>$user_id,
            "choose_company_id"=>$company_id,
        ];

        $result = $this->callSoaErp('post', '/btob/addAccountCode',$data);
        return   $result;
    }

    public function showAccountCodeEdit(){

        $account_code_id = input("account_code_id");

        $data = ["account_code_id"=>$account_code_id];
        $result = $this->callSoaErp('post', '/btob/getOneAccountCode',$data);
        $this->assign('result',$result['data']);


        return $this->fetch('account_code_edit');
    }


    public function editAccountCodeAjax(){

        $account_code_id = input("account_code_id");
        $name = input("name");
        $status = input("status");
        $user_id = session('user')['user_id'];
        $company_id = input("choose_company_id") ? : session('user')['company_id'];
        $data = [
            "account_code_id" => $account_code_id,
            "name" => $name,
            "status"=> $status,
            "user_id" => $user_id,
            "choose_company_id" => $company_id,
        ];

        $result = $this->callSoaErp('post', '/btob/updateAccountCodeByAccountCodeId',$data);
        return   $result;
    }



    public function changeAccountCode(){

        $account_code = input("account_code");
        $btb_tour_id = input("btb_tour_id");
        $data = [
            'account_code'=>"$account_code",
            'btb_tour_id'=>$btb_tour_id,
        ];

        return $this->callSoaErp('post','/b2b_tour/updateB2bTourAccountCode',$data);
    }

}
