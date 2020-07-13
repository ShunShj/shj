<?php
namespace app\index\model\system_alert_event;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class SystemAlertEvent extends Model{
	
	private $_languageList;

    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize(); 
    }

    /**
    * 获取系统提醒事件
    * Hugh	
    */
    public function getSystemAlertEvent($params=[]){
    	$where['event_type'] = 1; //默认系统提醒数据
    	$where['status'] = 1; //状态

    	if($params['system_alert_event_id']){
    		$where['system_alert_event_id'] = $params['system_alert_event_id'];
    	}
    	if($params['event_name']){
    		$where['event_name'] = ['like',"%{$params['event_name']}%"];	
    	}
    	if($params['event_type']){
    		$where['event_type'] = $params['event_type'];
    	}
    	if($params['status']){
    		$where['status'] = $params['status'];	
    	}
	
    	$result = $this->table('system_alert_event')->where($where)->field([
    		'system_alert_event_id','event_name','event_type','status'
    	])->order('system_alert_event_id asc')->select();
    	
    	
 		return $result; 
    }


}