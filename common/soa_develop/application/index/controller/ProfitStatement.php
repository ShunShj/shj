<?php
namespace app\index\controller;
use app\common\help\Help;
use think\config;
use think\Model;
use think\Controller;
use app\index\model\profit_statement\ProfitStatement as m_ProfitStatement;

class ProfitStatement extends Base
{
	public $mProfitStatement; 

	public function __construct()
    {
    	$this->mProfitStatement = new m_ProfitStatement(); 
    	 
    	parent::__construct();
    }

	/*
	* 获取分公司利润预计
	*/
	public function gainBranchProfits (){
		$params = $this->input();
		// $params['company_id']  = 13;
		// $params['years']  = '2019';
		// $params['month']  = '01';
		// $params['is_predict'] = 2;
		$result = $this->mProfitStatement->gainBranchProfits($params);
		$this->outPut($result); 
	}

	
	/***
	* 添加编辑利润表
	*/
	public function editProfitStatementAjax(){
		$params = $this->input();
		// $this->outPut($params); exit();
		$result = $this->mProfitStatement->editProfitStatementAjax($params);
		$this->outPut($result); 
	}

	/***
	* 分公司利润统计
	*/
	public function accumulatedProfitsOfBranchCompanies(){
		$params = $this->input();
		$result = $this->mProfitStatement->accumulatedProfitsOfBranchCompanies($params);
		$this->outPut($result); 
	}


	/***
	* 获取分公司利润数据（带货币转换）
	*/
	public function getObtainBranchProfitDataWithCurrencyConversion(){
		$params = $this->input();
		$result = $this->mProfitStatement->getObtainBranchProfitDataWithCurrencyConversion($params);
		$this->outPut($result); 	
	}



	
}