<?php
namespace app\index\service;
use app\index\model\system\CurrencyProportion;

use think\Model;
use app\common\help\Help;
use think\Hook;

class ProportionService{
	
	
	private $_currency_proportion;

	public function __construct(){
		
		$this->_currency_proportion = new CurrencyProportion();
	
	}

	
	
	//获得汇率
	public function getProportion($base_currency_id,$obj_currency_id){
		$params = [
			'currency_id'=>$base_currency_id,
			'opposite_currency_id'=>$obj_currency_id,
			'proportion_time'=>date('Ymd',strtotime('-1 days',strtotime(date('Y-m-01'))))	
		];
		$result = $this->_currency_proportion->getCurrencyProportion($params);
		if(count($result)==1){
			$key = $result[0]['currency_proportion'];
		}else{
			$key = 1;
		}
		
		return $key;
	}
}