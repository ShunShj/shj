<?php
/**
 * Created by PhpStorm.
 * User: godwei
 * Date: 2018/9/20
 * Time: 16:40
 */

namespace app\index\controller;
use \Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;
use app\common\help\Help;

class CompanyOrder extends Base
{
	/**
	 * 添加订单基础信息AJAX
	 */
    public function addCompanyOrderAjax(){
    	$params = Request::instance()->param();
    	
    	
    	$params['begin_time'] = strtotime($params['begin_time']);
    	$params['end_time'] = strtotime($params['end_time']);
  
    	$result = $this->callSoaErp('post', '/branchcompany/addCompanyOrder', $params);
  
    	return $result;
    }
    /**
     * 获取公司订单Ajax
     */
    public function getCompanyOrderAjax(){
    	$params = Request::instance()->param();

    	 
    	$result = $this->callSoaErp('post', '/branchcompany/getCompanyOrder', $params);
    	
    	return $result;
    	
    }
    /**
     * 修改订单AJAX
     */
    public function updateCompanyOrderByCompanyOrderNumberAjax(){
    	$params = Request::instance()->param();
    	$params['begin_time'] = strtotime($params['begin_time']);
    	$params['end_time'] = strtotime($params['end_time']);
    	$result = $this->callSoaErp('post', '/branchcompany/updateCompanyOrderByCompanyOrderNumber', $params);
    	 
    	return $result;
    }
    /**
     * 添加订单游客 的占位
     */
    public function addCompanyOrderCustomerOccupyAjax(){
    	$params = Request::instance()->param();
    
    	$result = $this->callSoaErp('post', '/branchcompany/addCompanyOrderCustomerOccupy', $params);
    	
    	return $result;
    }
    
    /**
     * 添加订单游客
     */
    public function addCompanyOrderCustomerAjax(){
    	
    	$params = Request::instance()->param();
    	$customer_flight= json_decode(input('customer_flight'),true);
    
    	for($i=0;$i<count($customer_flight);$i++){
    		$begin_time = $customer_flight[$i]['flight_begin_time'];
    		$end_time = $customer_flight[$i]['flight_end_time'];
    		if(!empty($begin_time)){
    			$customer_flight[$i]['flight_begin_time'] = strtotime($begin_time);
    		}
    		
    		if(!empty($end_time)){
    			$customer_flight[$i]['flight_end_time'] = strtotime($end_time);
    		}
    	}
    	$params['customer_flight'] = $customer_flight;
    	$result = $this->callSoaErp('post', '/branchcompany/addCompanyOrderCustomer', $params);
    	 
    	return $result;
    
    }
    /**
     * 获取订单游客
     */
    public function getCompanyOrderCustomerAjax(){
    	
    	$params = Request::instance()->param();
    	 
    	$result = $this->callSoaErp('post', '/branchcompany/getCompanyOrderCustomer', $params);
    	
    	return $result;
    	
    	
    }
    /**
     * 修改公司订单游客
     */
    public function updateCompanyOrderCustomerByCompanyOrderCustomerIdAjax(){
    	$params = Request::instance()->param();
    	$customer_flight= json_decode(input('customer_flight'),true);
    	
    	for($i=0;$i<count($customer_flight);$i++){
    		$begin_time = $customer_flight[$i]['flight_begin_time'];
    		$end_time = $customer_flight[$i]['flight_end_time'];
    		if(!empty($begin_time)){
    			$customer_flight[$i]['flight_begin_time'] = strtotime($begin_time);
    		}
    	
    		if(!empty($end_time)){
    			$customer_flight[$i]['flight_end_time'] = strtotime($end_time);
    		}
    	}
    	$params['flight_info'] = $customer_flight;
    	$result = $this->callSoaErp('post', '/branchcompany/updateCompanyOrderCustomerByCompanyOrderCustomerId', $params);
    	 
    	return $result;
    	
    }
    /**
     * 修改公司订单游客状态
     */
    public function updateCompanyOrderCustomerStatusByCompanyOrderCustomerIdAjax(){
    	$params = Request::instance()->param();
    	 
    	$result = $this->callSoaErp('post', '/branchcompany/updateCompanyOrderCustomerStatusByCompanyOrderCustomerId', $params);
    	
    	return $result;
    	
    	
    }
    /**
     * 添加公司订单产品 不包含自定义
     */
    public function addCompanyOrderProductAjax(){
    	$params = Request::instance()->param();
    	$params['branch_product_array'] = json_decode(input('branch_product_array'),true);
    	

    	$result = $this->callSoaErp('post', '/branchcompany/addCompanyOrderProduct', $params);
    	 
    	return $result;
    }
    
    /**
     * 修改公司订单产品
     */
    public function updateCompanyOrderProductAjax(){
    	$params = Request::instance()->param();
    	
    	$result = $this->callSoaErp('post', '/branchcompany/updateCompanyOrderProduct', $params);
    
    	return $result;
    }
    
    /**
     * 添加公司自定义产品
     */
    public function addCompanyOrderProductDiyAjax(){
    	$params = Request::instance()->param();
    	$result = $this->callSoaErp('post', '/branchcompany/addCompanyOrderProductDiy', $params);
    	
    	return $result;
    	
    }
    
    /**
     * 修改公司订单成本与报价
     */
    public function updateCompanyOrderCostAndPriceAjax(){
 
    	
    	$team_product_array = json_decode(input('team_product_array'),true);
    	$source_array = json_decode(input('source_array'),true);
    	$diy_array = json_decode(input('diy_array'),true);
    	
    	$branch_product_array = json_decode(input('branch_product_array'),true);    
    	$company_order_number = input('company_order_number');
    	for($i=0;$i<count($team_product_array);$i++){
    		$invoice_time = $team_product_array[$i]['invoice_time'];
    		if(!empty($invoice_time)){
    			$team_product_array[$i]['invoice_time'] = strtotime($invoice_time);
    		}    		
    	}
    	for($j=0;$j<count($source_array);$j++){
    		$invoice_time = $source_array[$j]['invoice_time'];
    		if(!empty($invoice_time)){
    			$source_array[$j]['invoice_time'] = strtotime($invoice_time);
    		}
    	}
    	for($k=0;$k<count($diy_array);$k++){
    		$invoice_time = $diy_array[$k]['invoice_time'];
    		if(!empty($invoice_time)){
    			$diy_array[$k]['invoice_time'] = strtotime($invoice_time);
    		}
    	}
    	$data = [
    		"team_product_array" => $team_product_array,
    		"source_array" => $source_array,
    		"diy_array" => $diy_array,
    		'branch_product_array' => $branch_product_array,
    		'company_order_number'=>$company_order_number,

    	
    	];
    	
    
    	$result = $this->callSoaErp('post', '/branchcompany/updateCompanyOrderCostAndPrice', $data);
    	 
    	return $result;
    	 
    }
    
    
   /**
    * 获取公司订单产品
    */
    public function getCompanyOrderProductAjax(){
    	$params = Request::instance()->param();

    	$result = $this->callSoaErp('post', '/branchcompany/getCompanyOrderProduct', $params);
    	
    	return $result;
    }
    /**
     * 生成公司订单的应收与应付
     */
    public function updateCompanyOrderReveivableAndCopeAjax(){
    	$params = Request::instance()->param();
    	$result = $this->callSoaErp('post', '/branchcompany/updateCompanyOrderReveivableAndCope', $params);
    	
    	return $result;
    }
    
    /**
     * 订单大提交
     */
    public function addCompanyOrderBigAjax(){
    	$params = Request::instance()->param();
    	$result = $this->callSoaErp('post', '/branchcompany/updateCompanyOrderReveivableAndCope', $params);
    	 
    	return $result;
    }
    
    
    /**
     * 添加销售收款
     */
    public function addCompanyOrderSaleAjax(){
    	$params = Request::instance()->param();
    	$payment_time = $params['payment_time'];
    	$params['payment_time'] = strtotime($payment_time);
    	
    	$result = $this->callSoaErp('post', '/branchcompany/addCompanyOrderSale', $params);
    	
    	return $result;
    }
    
    /**
     * 添加产品与游客的关联
     * 
     */
    public function updateCompanyOrderProductCustomerAjax(){
    	$params = Request::instance()->param();
    	$company_order_customer = $params['company_order_customer'];
    	$company_order_customer = trim($company_order_customer,',');
    	$params['company_order_customer'] = $company_order_customer;
    	$result = $this->callSoaErp('post', '/branchcompany/updateCompanyOrderProductCustomer', $params);
    	 
    	return $result;
    }
    /**
     * 获取公司订单小报表
     */
    public function getCompanyOrderReceivableInfoAjax(){
    	$params = Request::instance()->param();
    	
    	$result = $this->callSoaErp('post', '/branchcompany/getCompanyOrderReceivableInfo', $params);
    	
    	return $result;
    }
}