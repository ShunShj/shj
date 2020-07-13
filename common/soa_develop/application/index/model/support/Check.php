<?php

namespace app\index\model\support;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class Check extends Model{
    //protected $connection = ['database' => 'erp'];
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

    /*
     * 
     */
    public function checkNameIsRepetition($table_name,$data){
		
    	$result = $this->table($table_name)->where($data)->count(); 

    	return $result;

    }
}