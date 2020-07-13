<?php

namespace app\index\model\finance;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class ApportionProportionInfo extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'apportion_proportion_info';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }
	public function aa(){
		return 1;
	}
    /**
     * 获取分摊详情
     */
    public function getApportionProportionInfo($params){
    	
    	
    	$data['apportion_proportion_id'] = $params['apportion_proportion_id'];
    
    	$data['status'] = 1;
    	$result = $this->table("apportion_proportion_info")->where($data)->field([
    		'apportion_proportion_info_id','company_id','money','apportion_proportion',
    		"(select company_name from company where company.company_id = apportion_proportion_info.company_id) as company_name"	
    			
    			
    	])->select();
    	return $result;

    }
}