<?php
namespace app\index\model\system_alert_event;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class SystemAlertSetting extends Model{
	
	private $_languageList;

    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize(); 
    }

    /**
    * 获取系统提醒设置
    */
    public function getSystemAlertSetting($params){

    	if($params['system_alert_event_id']){
    		$where['system_alert_event_id'] = $params['system_alert_event_id'];
    	}
    	if($params['status']){
    		$where['status'] = $params['status'];
    	}	
    	if(is_numeric($params['company_id'])){
    		$where['company_id'] = $params['company_id'];
    	}
		
    	$result = $this->table('system_alert_setting')->where($where)
    	->field([
    		'system_alert_setting_id',
    		'system_alert_event_id',
    		'is_system_reminder',
    		'system_reminder_content',
    		'is_email_reminder',
    		'email_reminder_content',
    		'status',
    		'company_id',
    		'create_time',
    		'update_time',
    		"(select system_alert_event.event_name from system_alert_event where system_alert_event.system_alert_event_id=system_alert_setting.system_alert_event_id) as event_name",
    		"(select company.company_name from company where company.company_id=system_alert_setting.company_id) as company_name",
    		"(select user.nickname from user where user.user_id=system_alert_setting.create_user_id) as create_user",
    		"(select user.nickname from user where user.user_id=system_alert_setting.update_user_id) as update_user",
    	])->select();

    	return $result;
    }


    /**
    * 获取系统提醒列表
    */
   public function getSystemReminderList($params){
   		if(!empty($params['company_id'])){
   			//$where['company_id'] = $params['company_id'];
   		}
   		
		$where['system_alert_event.status'] = 1;
   		
		if($params['system_alert_setting_id']){
			$where['system_alert_setting.system_alert_setting_id'] = $params['system_alert_setting_id'];	
		}

   		if($params['status']){
   			$where['system_alert_setting.status'] = $params['status'];
   		}

   		if($params['system_alert_event_id']){
   			$where['system_alert_setting.system_alert_event_id'] = $params['system_alert_event_id'];
   		} 
		
   		$result = $this->table('system_alert_event')
   		->join('system_alert_setting','system_alert_event.system_alert_event_id=system_alert_setting.system_alert_event_id')
   		->where($where)->field([ 
   			'system_alert_setting.system_alert_setting_id',
   			'system_alert_setting.system_alert_event_id',
   			'system_alert_event.event_name',
   			'system_alert_event.event_type',
   			'system_alert_setting.is_system_reminder',
   			'system_alert_setting.system_reminder_content',
   			'system_alert_setting.is_email_reminder',
   			'system_alert_setting.email_reminder_content',
   			'system_alert_setting.status as system_alert_setting_status',
   			"(select user.nickname from user where user.user_id=system_alert_setting.create_user_id) as create_user",
   			'create_time',
   			"(select user.nickname from user where user.user_id=system_alert_setting.update_user_id) as update_user",
    		'update_time',

   		])->order('system_alert_event.system_alert_event_id','asc')->select();

   		return $result;
   }

   /**
   * 添加系统提醒
   */
   public function AddSystemReminder($params){
   		$company_id = $params['company_id'];
   		$user_id = $params['user_id'];
   		$time = time(); 

   		$str = 'insert into system_alert_setting (system_alert_event_id,is_system_reminder,system_reminder_content,is_email_reminder,email_reminder_content,status,company_id,create_time,create_user_id,update_time,update_user_id) values';

   		$str .= "(1,1,'资源信息已更新',1,'资源信息已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(2,1,'线路模板-行程内容已更新',1,'线路模板-行程内容已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(3,1,'线路模板-资源配置已更新',1,'线路模板-资源配置已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(4,1,'团队产品-基本信息已更新',1,'团队产品-基本信息已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(5,1,'团队产品-行程内容已更新',1,'团队产品-行程内容已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(6,1,'团队产品-编辑资源配置',1,'团队产品-编辑资源配置',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(7,1,'团队产品-产品报价已更新',1,'团队产品-产品报价已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(8,1,'团队产品-游客信息已更新',1,'团队产品-游客信息已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(9,1,'团队产品-拼团信息已更新',1,'团队产品-拼团信息已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(10,1,'团队产品-分团信息已更新',1,'团队产品-分团信息已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(11,1,'团队产品-成团状态已更新',1,'团队产品-成团状态已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(12,1,'团队产品-应付供应商信息已更新',1,'团队产品-应付供应商信息已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(13,1,'团队产品-应收分公司信息已更新',1,'团队产品-应收分公司信息已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(14,1,'地接报账已更新',1,'地接报账已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(15,1,'实收款已确认',1,'实收款已确认',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(16,1,'订单已提交',1,'订单已提交',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(17,1,'订单已取消',1,'订单已取消',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(18,1,'订单订单状态已变更',1,'订单订单状态已变更',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(19,1,'游客信息已更新',1,'游客信息已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(20,1,'产品信息已更新',1,'产品信息已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(21,1,'产品报价已更新',1,'产品报价已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(22,1,'发票信息已更新',1,'发票信息已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(23,1,'代理信息已更新',1,'代理信息已更新',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		$str .= ",(24,1,'销售收款已提交',1,'销售收款已提交',1,{$company_id},{$time},{$user_id},{$time},{$user_id})";
   		 
		$this->startTrans();
    	try{
		$this->execute($str);
		$this->commit();
		$result = 1;
	    } catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		$this->rollback();
    	}

    	return $result;
   }


   /**
   * 修改分公司消息提醒
   */
   public function updateSystemReminder($params){
   		$where['system_alert_setting_id'] = $params['system_alert_setting_id'];

   		$data['is_system_reminder'] = $params['is_system_reminder'];
   		$data['system_reminder_content'] = $params['system_reminder_content'];
   		$data['is_email_reminder'] = $params['is_email_reminder'];
   		$data['email_reminder_content'] = $params['email_reminder_content'];
   		$data['update_time'] = $params['update_time']?:time();
   		$data['update_user_id'] = $params['user_id'];	

   		return Db::table('system_alert_setting')->where($where)->update($data);

   }

   /**
   * 批量修改分公司消息提醒状态
   **/
   public function batchEditSystemReminderState($params){
   	try{
   		$where = "system_alert_setting_id in ({$params['system_alert_setting_id']})";
		$up['status'] = $params['status']; 
		$up['is_system_reminder'] = $params['status']; 
		$up['is_email_reminder'] = $params['status']; 
   		return Db::table('system_alert_setting')->where($where)->update($up);
   		} catch (\Exception $e) {
    		$result = $e->getMessage();
    	}
   }


}