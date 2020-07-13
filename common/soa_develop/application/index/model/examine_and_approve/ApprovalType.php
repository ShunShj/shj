<?php
namespace app\index\model\examine_and_approve;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class ApprovalType extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'approval_type';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

	/**
	* 获得审批类型
	*/
    public function obtain_approval_type($params){
    	//$where=1;
    	if($params['approval_type_id']){
    		$where['approval_type_id'] = $params['approval_type_id'];		
    	}
    	if($params['pid']){
    		$where['pid'] = $params['pid'];		
    	}
    	if($params['level']){
    		$where['level'] = $params['level'];		
    	}
    	if($params['status']){
    		$where['status'] = $params['status'];		
    	}
    	$return = $this->table('approval_type')->field(['approval_type_id','apellation','pid','level','status'])->where($where)->select();
    	return $return;

    }

}