<?php
namespace app\index\controller;
use app\common\help\Help;
use think\config;
use think\Model;
use think\Controller;
use app\index\model\system\SupplierType;
use app\index\model\multilingual\Multilingual as m_Multilingual;
/**
 * 
 */
class Multilingual extends Base
{
	private $mMultilingual;
	
	public function __construct()
    {
    	$this->mMultilingual = new m_Multilingual();
		parent::__construct();
	}

	/*
	* 添加/编辑多语言
	*/
	public function editMultilingual(){
		$params = $this->input();  
		// $params['original_table_name'] = 'supplier';
		// $params['original_table_field_name'] = 'supplier_name';
		// $params['original_table_id'] = 187;
		// $params['operation_id'] = 392;
		// $params['operation_time'] = time();
		// $params['status'] = 1;
		// $params['language_id'] = 6;
		// $params['translate'] = 'ytu';
		// $params['multilingual_id'] = 3;
		$result =  $this->mMultilingual->editMultilingual($params);
		$this->outPut($result); 
	}

	/**
	* 获取对应的语言
	*/
	public function getMultilingual(){
		$params = $this->input();  
		$result = $this->mMultilingual->getMultilingual($params);
		$this->outPut($result); 
	}


	/*
	* 获取所有语言数据
	*/
	public function selMultilingualAll(){
		$params = $this->input(); 
		$result = $this->mMultilingual->selMultilingualAll($params);
		$this->outPut($result); 
	}


	/**
	* 语言模糊查询
	*/
	public function languageFuzzyQuery(){
		$params = $this->input(); 

		// $params['original_table_name'] = 'supplier';
		// $params['original_table_field_name'] = 'supplier_name';
		// $params['translate'] = 'の喜来登ホテル';
		// $params['language_id'] = 6;

		$result = $this->mMultilingual->languageFuzzyQuery($params);
		$this->outPut($result); 
	}


}