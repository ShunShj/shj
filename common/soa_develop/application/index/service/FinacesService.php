<?php
namespace app\index\service;


use app\index\model\branchcompany\CompanyOrder;
use app\index\model\branchcompany\CompanyOrderCustomer;
use app\index\model\branchcompany\CompanyOrderProduct;
use app\index\model\branchcompany\CompanyOrderProductTeam;
use app\index\model\branchcompany\CompanyOrderDiy;
use app\index\model\branchcompany\CompanyOrderProductSource;
use app\index\model\branchcompany\CompanyOrderRelation;
use app\index\model\branchcompany\CompanyOrderProductDiy;
use app\index\model\finance\Receivable;
use app\index\model\finance\ReceivableInfo;
use app\index\model\finance\ReceivableCustomer;
use app\index\model\finance\Cope;
use app\index\model\product\TeamProduct;
use app\index\model\product\TeamProductAllocation;
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

use think\Model;
use app\common\help\Help;
use think\Hook;

class FinacesService{
	
	private $_company_order;
	private $_company_order_product;
	private $_company_order_customer;
	private $_company_order_product_team;
	private $_company_order_product_source;
	private $_company_order_product_diy;
	private $_hotel;
	private $_team_product;
	private $_team_product_allocation;
	public function __construct(){
		$this->_company_order = new CompanyOrder();
		$this->_team_product = new TeamProduct();
		$this->_team_product_allocation = new TeamProductAllocation();
	}

	
	
	//获取应收根据应付编号
	public function getReceivableByCopeNumber($params){
		

		$cope = new Cope();
		$receivable = new Receivable();
		$cope_number = $params['cope_number'];
		$cope_params = [
			'cope_number'=>$cope_number	
		];
		$cope_number = $cope->getCope($cope_params);
		
		$receivable_params=[
			'public_number'=>$cope_number[0]['public_number']	
		];
		
		return $receivable->getReceivable($receivable_params);
		
	}
	//获取应付根据应收编号
	public function getCopeByReceivableNumber($params){
	
	
		$cope = new Cope();
		$receivable = new Receivable();
		$receivable_number = $params['receivable_number'];
		$receivable_params = [
			'receivable_number'=>$receivable_number
		];
		
		$receivable_result = $receivable->getReceivable($receivable_params);
		
		$cope_params=[
			'public_number'=>$receivable_result[0]['public_number']
		];

		return $cope->getCope($cope_params);
	}	
	
	/**
	 * 判断应收应付是否结清
	 */
	public function checkReceivableAndCope($params){
		$key = 1;
		$team_product_id = $params['team_product_id'];
		$cope = new Cope();
		$receivable = new Receivable();
		$finance_params['team_product_id'] = $team_product_id;
		
		
		$cope_result = $cope->getCopeBalance($finance_params);
		$receivable_result=$receivable->getReceivableBalance($finance_params);
		$balance_result = array_merge($cope_result,$receivable_result);
		for($i=0;$i<count($balance_result);$i++){
			if($balance_result[$i]['balance']>0){
				return 2;
				exit();
			}
		}
		return $key;
		
	}
	
	public function websiteOrderReceivableFull($params){
		
		//paypal 凭证编号 等于流水单号
		
		$company_order_number = $params['company_order_number'];
		
		//首先获得订单信息 查看是否 付款
		$company_order_result = $this->_company_order->getCompanyOrder($params);
		if($company_order_result[0]['order_status']>1){
			return 2; //代表已经付完款 不需要再付 这里需要做 业务逻辑处理
		}
		
		
		//通过订单编号获取应收直客代理的钱
		$time = time();
		$receivable = new Receivable();
		$receivable_info = new ReceivableInfo();
		$receivable_params = [
			'order_number'=>$params['company_order_number'],
			'status'=>1,
			'payment_object_type'=>4
		];
		$receivable_result = $receivable->getReceivable($receivable_params);
		for($i=0;$i<count($receivable_result);$i++){//开始循环插入实收
			$data = [
				'receivable_voucher' => $params['serial_number'],//第三方的流水号 paypal的给paypal流水 支付宝给支付宝的流水
				'receivable_number'=>$receivable_result[$i]['receivable_number'],
				'payment_money'=>$receivable_result[$i]['receivable_money'],
				'payment_currency_id'=>	$receivable_result[$i]['receivable_currency_id'],
				'payment_type'=>$params['payment_type'],// 9是paypal
				'payment_number'=>$params['serial_number'],
				'receivable_info_type'=>1,
				'status'=>1,
				'create_time'=>$time,
		
		
					
			];
			$receivable_info->addReceivableInfo($data);
		}
		
		//最后把订单状态改成待确认
		$company_order_params =[
			'company_order_number'=>$params['company_order_number'],
			'order_status'=>2
		];
		$this->_company_order->updateCompanyOrderByCompanyOrderNumber($company_order_params);
		
		return 1;
	}
	
	
	
}