<?php
namespace app\index\service;

use app\index\model\system\Tax;
use app\index\model\branchcompany\CompanyOrder;
use app\index\model\branchcompany\CompanyOrderCustomer;
use app\index\model\branchcompany\CompanyOrderProduct;
use app\index\model\branchcompany\CompanyOrderProductTeam;
use app\index\model\branchcompany\CompanyOrderDiy;
use app\index\model\branchcompany\CompanyOrderProductSource;
use app\index\model\branchcompany\CompanyOrderRelation;
use app\index\model\branchcompany\CompanyOrderProductDiy;
use app\index\model\finance\Receivable;
use app\index\model\finance\ReceivableCustomer;
use app\index\model\finance\Cope;
use app\index\model\system\RouteType;
use app\index\model\system\Company;
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
use app\index\model\publicmodel\Common;
use think\Model;
use app\common\help\Help;
use think\Hook;

class PublicService{
	
	
	private $_company_order_product;
	private $_company_order_customer;
	private $_company_order_product_team;
	private $_company_order_product_source;
	private $_company_order_product_diy;
	private $_hotel;
	private $_team_product;
	private $_team_product_allocation;
	private $_common;
	private $_route_type;
	private $_company;
	public function __construct(){
		
		$this->_team_product = new TeamProduct();
		$this->_team_product_allocation = new TeamProductAllocation();
		$this->_route_type = new RouteType();
		$this->_common = new Common();
	
	}

	/**
	 * 设置表的编号
	 * @param unknown $table_name 表明
	 * @param unknown $major_key 主键
	 * @param unknown $major_key_val 主键值
	 * @param unknown $number_field  编号的字段名称
	 * @param unknown $prefix 前缀
	 * @param unknown $number 编号
	 */
	public function setNumber($table_name,$major_key,$major_key_val,$number_field,$prefix,$number){
		
		$number = $prefix.'-'.$number;
	
		$this->_common->setNumber($table_name, $major_key, $major_key_val, $number_field,$number);
		return $number;
	}
	
	/**
	 * 通过线路类型ID获取父级递归的编码
	 */
	public function getRouteTypeRecursion($route_type_id){
		$route_type_params = [
			'route_type_id'=>$route_type_id	
		];
		$number=[];
		
		while($result = $this->_route_type->getOneRouteType($route_type_params)){
			
			$number[]=$result['route_type_code'];
			$route_type_params = [
				'route_type_id'=>$result['pid']	
			];
			$result = $this->_route_type->getOneRouteType($route_type_params);
			
		}
		//$number = strrev($number);
	
		$number_string = '';
		for($i=count($number);$i>=0;$i--){
			$number_string.=$number[$i];
		}
		
		return $number_string;
	}


    public function getSourceRemindObject($params){
        $model = new TeamProductAllocation();
        $data['supplier_type_id'] = $params['supplier_type_id'];
        $data['source_id'] = $params['source_id'];
        $result = $model->getSourceRemindObject($data);
        return $result;
    }
    
    /**
     * 通过税ID返回该税下的百分比
     */
    public function getTaxTotal($params){
    	$tax = new Tax();
    	$tax_params =[
    		'tax_id'=>$params['tax_id']	
    	];
    	error_log(print_r($tax_params,1));
    	$tax_result = $tax->getTax($tax_params);
    	
    	$baifenbi = $tax_result[0]['gstrate']+$tax_result[0]['pstrate']+$tax_result[0]['hstrate']+$tax_result[0]['otx'];
    	return $baifenbi;
    }
	
}