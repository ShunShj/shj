<?php
namespace app\index\service;

use app\index\model\finance\Receivable;
use app\index\model\finance\Cope;
use app\index\model\system\Company;
use app\index\model\system\Currency;
use app\index\model\source\Supplier;
use app\index\model\btob\Distributor;
use think\Model;

class ReceivableService{
	private $_receivable;
	private $_cope;
	private $_company;
	private $_supplier;
	private $_distributor;
	private $_currency;
	public function __construct(){
		$this->_currency = new Currency();
		$this->_receivable = new Receivable();
		$this->_cope = new Cope();
		$this->_company = new Company();
		$this->_supplier = new Supplier();
		$this->_distributor = new Distributor();
	}
	/**
	 * 添加应收 //添加应收 如果 是分公司 必定会有一条添加应付
	 * @param unknown $params
	 */
	public function addReceivable($params){
		
		$this->_receivable->addReceivable($params);
		
		return $company_order_customer_result;
	}
	
	//获取应收 更加相信的信息
	public function getReceivableInfo($params){
		for($i=0;$i<count($params);$i++){
			if($params[$i]['payment_object_type']==1){//代表公司
				$params_data['company_id'] = $params[$i]['payment_object_id'];
				$company_result = $this->_company->getCompany($params_data);
				$params[$i]['supplier_name'] = $company_result[0]['company_name'];
				
			}else if($params[$i]['payment_object_type']==2){//代表供应商
				$params_data['supplier_id'] =  $params[$i]['payment_object_id'];
				$supplier_result = $this->_supplier->getSupplier($params_data);
				$params[$i]['supplier_name'] = $supplier_result[0]['supplier_name'];
			}else if($params[$i]['payment_object_type']==3){//代表渠道
				$params_data['distributor_id'] =  $params[$i]['payment_object_id'];
				$distributor_result = $this->_distributor->getDistributor($params_data);
				$params[$i]['supplier_name'] = $supplier_result[0]['distributor_name'];
				
			}else{
				$params[$i]['supplier_name'] = '';
			}

		}
		return $params;
	}
	
	
}