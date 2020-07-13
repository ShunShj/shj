<?php
namespace app\index\service;


use app\index\model\system_alert_event\InStationLetter;
use app\index\model\system\Company;
use app\index\model\system\User;
use think\Model;
use app\common\help\Help;
use think\Hook;

class InStationLetterService{
	
	
	private $_in_station_letter;
	private $_company;
	public function __construct(){
		
		
		$this->_in_station_letter = new InStationLetter();
		$this->_company = new Company();
	
	}

	
	
	//添加站内信
	public function addInStationLetter($params){
		$user = new User();
		//可以传公司可以传用户
		if(!empty($params['user_id'])){//假如选择了用户
			$data = [
				'system_alert_event_id'=>$params['system_alert_event_id'],	
				'user_id'=>$params['user_id'],
				'content'=>$params['content'],
				'url'=>$params['url'],
				'status'=>1	
			];
			
			$r = $this->_in_station_letter->addInStationLetter($data);
		}else if(!empty($params['company_id'])){//如果有公司了，也要判断是否该公司下的所有职位
		
			$user_params['company_id'] = $params['company_id'];
			if(!empty($params['role_id'])){
				$user_params['role_id'] = $params['role_id'];
				$user_result = $user->getUser($user_params);
			}else{
				$user_result = $user->getUser($user_params);
			
			}
			for($i=0;$i<count($user_result);$i++){
				$data = [
					'system_alert_event_id'=>$params['system_alert_event_id'],
					'user_id'=>$user_result[$i]['user_id'],
					'content'=>$params['content'],
					'url'=>$params['url'],
					'status'=>1
						
				];
				$this->_in_station_letter->addInStationLetter($data);
			}
			
		}else if(!empty($params['role_id'])){// 发给所有职位的人
			
			$user_params['role_id'] = $params['role_id'];
			$user_result = $user->getUser($user_params);

			for($i=0;$i<count($user_result);$i++){
				$data = [
						'system_alert_event_id'=>$params['system_alert_event_id'],
						'user_id'=>$user_result[$i]['user_id'],
						'content'=>$params['content'],
						'url'=>$params['url'],
						'status'=>1
			
				];
				$this->_in_station_letter->addInStationLetter($data);
			}
		
		
		}else{
			$user_params = [];
			$user_result = $user->getUser($user_params);
			
			for($i=0;$i<count($user_result);$i++){
				$data = [
						'system_alert_event_id'=>$params['system_alert_event_id'],
						'user_id'=>$user_result[$i]['user_id'],
						'content'=>$params['content'],
						'url'=>$params['url'],
						'status'=>1
							
				];
				$this->_in_station_letter->addInStationLetter($data);
			}
			
		}
		return 1;
	}
	
}