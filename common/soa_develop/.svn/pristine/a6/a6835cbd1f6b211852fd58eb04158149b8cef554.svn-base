<?php
namespace app\index\controller;
use app\common\help\Help;
use think\config;
use think\Model;
use think\Controller;
use app\index\model\balance_sheet\BalanceSheet as m_BalanceSheet;

class BalanceSheet extends Base
{	
	public $mBalanceSheet; 

	public function __construct()
    {
    	$this->mBalanceSheet = new m_BalanceSheet(); 
    	 
    	parent::__construct();
    }


    /**
    * 添加、编辑资产负债表
    */
    public function editBalanceSheet(){ 
    	$params = $this->input(); 
  		$result = $this->mBalanceSheet->editBalanceSheet($params);
  		$this->outPut($result); 
    }


    /**
    * 获取资产负债表
    */
    public function selBalanceSheet(){
    	$params = $this->input();  
		// $params['years'] = '2019';
  //       $params['month'] = '02';
  //       $params['company_id'] = 13;
  //       $params['status'] = 1; 
    	$result = $this->mBalanceSheet->selBalanceSheet($params);
		$this->outPut($result); 
    }

    /**
    * 获取资产负债表(带汇率转换)
    **/
    public function selBalanceSheetCurrencyConversion(){
		$params = $this->input(); 

		// $params['years'] = '2019';
  //       $params['month'] = '02';
  //       $params['company_id'] = 13;
  //       $params['status'] = 1; 
  //       $params['company_currency_id'] = 1;
  //       $params['change_currency_id'] = 1;
       
		$result = $this->mBalanceSheet->selBalanceSheetCurrencyConversion($params);
		$this->outPut($result); 
    }

}