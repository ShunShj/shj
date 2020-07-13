<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/14
 * Time: 19:45
 */

namespace app\index\controller;
use app\common\help\Help;
use app\index\controller\Base;
use \think\File;
use think\Request;

class Json extends Base
{
    public function getCustomer(){
        $company_order_number = $_REQUEST["company_order_number"];

        $data = [
            "company_order_number"=>$company_order_number,
        ];

        $customer_result = $this->callSoaErp('post', '/branchcompany/getCompanyOrderCustomer',$data);
		
        $data=[];
        foreach($customer_result['data'] as $key=>$val){

            $data[$key]['name'] = $val['customer_name'];

            $data[$key]['value'] = $val['company_order_customer_id'];
            $data[$key]['selected'] = "selected";

        }
        return json_encode($data);
    }
    /**
     * 获取选中的游客
     * @return string
     */
    public function getCustomerIsSelect(){
    	$company_order_number = input('company_order_number');
    	$receivable_number = input('receivable_number');
    	
    	
    	
    	$data = [
    		"company_order_number"=>$company_order_number,
    	];
    
    	$customer_result = $this->callSoaErp('post', '/branchcompany/getCompanyOrderCustomer',$data);
    	
    	
    	//再获取应收下的游客
    	$receivable_params = [
    		'receivable_number'=>$receivable_number,
    		'status'=>1	
    	];
    	$receivable_customer_result = $this->callSoaErp('post', '/finance/getReceivableCustomerByReceivableNumber',$receivable_params);
    	
    	
    	$customer_result = $customer_result['data'];
    	$receivable_customer_result = $receivable_customer_result['data'];
    
    	$data=[];
    	for($i=0;$i<count($customer_result);$i++){
    		$data_params = [
    			'name'=>$customer_result[$i]['customer_name'],
    			'value'=>$customer_result[$i]['company_order_customer_id'],
    			
    		];
    		if(is_numeric(array_search($customer_result[$i]['company_order_customer_id'], array_column($receivable_customer_result,'company_order_customer_id')))){
    			
    			$data_params['selected']='selected';
    		}
    		$data[] = $data_params;
    	}
    	//foreach($customer_result['data'] as $key=>$val){
    
//     		$data[$key]['name'] = $val['customer_name'];
    
//     		$data[$key]['value'] = $val['company_order_customer_id'];
//     		$data[$key]['selected'] = "selected";
    
    	//}
    	
    	return json_encode($data);
    }
    
    //通过资源ID获取供应商名称
    public function getSupplierAjax(){
    	$data['status'] = 1;
    	if(!empty(input('supplier_type_id'))){
    		$data['supplier_type_id']=input('supplier_type_id');
    		
    	}
    	$data['company_id'] = session('user')['company_id'];
    	$supplier_result = $this->callSoaErp('post', '/source/getSupplier',$data);
    	
    	return json_encode($supplier_result);
    }
    
}