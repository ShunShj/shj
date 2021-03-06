<?php
namespace app\index\model\system_alert_event;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class InStationLetter extends Model{ 
	protected $table = 'in_station_letter';
	private $_languageList;

    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize(); 
    }

    /** 
    * 添加站内信
    **/
    public function addInStationLetter($params){
    	$data['system_alert_event_id'] = $params['system_alert_event_id'];
    	$data['user_id'] = $params['user_id'];
    	$data['content'] = $params['content'];
    	if(!empty($params['url'])){
    		$data['url'] = $params['url'];
    	}
    	
    	$data['status'] = $params['status'];
    	$data['create_time'] = time();
    	// var_dump($data);exit;
    	$result = $this->insertGetId($data);
	
		return $result;
    }

    /**
    * 获取站内信 
    */
	public function getInStationLetter($params){
		$where = "user_id={$params['user_id']}";
		if($params['status']){
			$where .= " and status={$params['status']}";
		}	
		if($params['sTime']){
			$where .= " and create_time>={$params['sTime']}";
		}
		if($params['eTime']){
			$where .= " and create_time<={$params['eTime']}";
		}
	

		$query = $this->table('in_station_letter')->where($where);	
    	$query->order('status asc,create_time desc');
    	$ar = $query->select();
    	return $ar;
    }

    /*
    * 修改站内信状态为已读
    */
    public function readInStationLetter($params){
    	$where['in_station_letter_id'] = $params['in_station_letter_id'];
    	$up['status'] = 2;
    	$up['consult_time'] = time();
    	return Db::table('in_station_letter')->where($where)->update($up);
    }


}