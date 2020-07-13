<?php
namespace app\index\controller;
use app\common\help\Help;
use think\config;
use think\Db;
use think\Model;
use think\Controller;
use app\index\model\operations\Operations as m_Operations; 
use app\index\model\system\User;

/***
* 获取待办操作 
* Hugh 190401
*/
class Operations extends Base
{

	public $mOperations;


	public function __construct()
    {
    	$this->mOperations = new m_Operations(); 
    	parent::__construct();
    }


	//获取待办操作
	public function selOperations(){
		$params = $this->input();   
		$result = $this->mOperations->selOperations($params);
       	$this->outPut($result); 
	}

	//添加待办操作
	public function addOperations(){
		$params = $this->input();
		$result = $this->mOperations->addOperations($params);
		$this->outPut($result);
	}

	//编辑待办操作
	public function upOperations(){
		$params = $this->input();
		$result = $this->mOperations->upOperations($params);
		$this->outPut($result);
	}

	//删除待办操作
	public function delOperation(){
		$params = $this->input();
		$result = $this->mOperations->delOperation($params);
		$this->outPut($result);
	}

	//添加待办邮件模板
	public function addOperationsEmail(){
		$params = $this->input();
		$result = $this->mOperations->addOperationsEmail($params);
		$this->outPut($result);
	}


	//获取待办邮件模板
	public function selOperationsEmail(){
		$params = $this->input();
		$result = $this->mOperations->selOperationsEmail($params);
		$this->outPut($result);
	}

	//修改邮件模板
	public function upOperationsEmail(){
		$params = $this->input();
		$result = $this->mOperations->upOperationsEmail($params);
		$this->outPut($result);
	}

	//删除邮件模板
	public function delOperationsEmail(){
		$params = $this->input();
		$result = $this->mOperations->delOperationsEmail($params);
		$this->outPut($result);
	}

	//修改待办模板邮件模板选择
	public function ModifyMailTemplateSelection(){
		$params = $this->input();
		$result = $this->mOperations->ModifyMailTemplateSelection($params);
		$this->outPut($result);
	}

	//根据分公司订单ID获取订单待办模板 
	public function setCompanyOrderOperationsByCompanyOrderId(){
		$params = $this->input();
		$paramRule = [
             'company_order_id'=>'number', 
        ];
        $this->paramCheckRule($paramRule,$params); 
        
		$result = $this->mOperations->setCompanyOrderOperationsByCompanyOrderId($params);
		$this->outPut($result);
	}

	//创建订单待办模板
	public function FoundCompanyOrderOperations(){
		$params = $this->input();
		$paramRule = [
             'company_order_id'=>'number', 
             'company_id'=>'number', 
             'company_order_number'=>'string',
             'user_id'=>'number'
        ];
        $this->paramCheckRule($paramRule,$params); 

        $result = $this->mOperations->FoundCompanyOrderOperations($params);
        $this->outPut($result);
        	
	}


	//添加待办类型
	public function addOperationsType(){
		$params = $this->input();
		$paramRule = [
             'company_id'=>'number', 
             'name'=>'string',  
        ];
        $this->paramCheckRule($paramRule,$params);

        $result = $this->mOperations->addOperationsType($params);
        $this->outPut($result);

	}

	//获取待办类型
	public function selOperationsType(){
		$params = $this->input();
	
		$result = $this->mOperations->selOperationsType($params);
        $this->outPut($result);
	}

	//修改待办类型
	public function upOperationsType(){
		$params = $this->input();
		$paramRule = [
             'company_id'=>'number', 
             'name'=>'string', 
             'id'=>'number',
        ];
        $this->paramCheckRule($paramRule,$params);

        $result = $this->mOperations->upOperationsType($params);
        $this->outPut($result);
	}

	//添加待办附件
	public function addCompanyOrderOperationsAttachments(){
		$params = $this->input();

		$paramRule = [
             'company_order_operations_id'=>'number', 
             'company_order_id'=>'number', 
             'name'=>'string',
             'savepath'=>'string'
        ];
        $this->paramCheckRule($paramRule,$params);

		$result = $this->mOperations->addCompanyOrderOperationsAttachments($params);
        $this->outPut($result);
	}

	//删除待办附件
	public function delCompanyOrderOperationsAttachments(){
		$params = $this->input();
		$paramRule = [
             'id'=>'number',  
        ];
        $this->paramCheckRule($paramRule,$params);

		$result = $this->mOperations->delCompanyOrderOperationsAttachments($params);
        $this->outPut($result);
	}

	//修改订单待办模板
	public function upCompanyOrderOperations(){
		$params = $this->input();
		$paramRule = [
             'id'=>'number',  
        ];
        $this->paramCheckRule($paramRule,$params);
		$result = $this->mOperations->upCompanyOrderOperations($params);
        $this->outPut($result);
	}



	//根据订单获取选择的邮件模板信息
	public function selOperationsEmailTemplatesByCompanyOrderOperationsId(){
		$params = $this->input();
		$paramRule = [
             'id'=>'number',  
        ];
        $this->paramCheckRule($paramRule,$params);
		$result = $this->mOperations->selOperationsEmailTemplatesByCompanyOrderOperationsId($params);
        $this->outPut($result);
	}
	 
	

    /**
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/4/24
     * Time: 10:41
     */
    public function getServiceReminderList()
    {
        $params = $this->input();
        $where = [
           // 'o.status' => 1,
            'co.status' => 1,
            'coo.remind_to'=> $params['now_user_id'],
            //'coo.is_visible'=> 1,
           // 'remind_at' => ['<=',date('Y-m-d')],
            'coo.status' => 1
        ];

        $field = "coo.*, co.company_order_number";
        $list = $this->mOperations->getOperationsServiceReminderList($where, $field);
        $user_model = new User();
        $user_arr = $user_model->getUser(['department_id' => $params['department_id']]);
        foreach ($list as $key => $value)
        {
            //提醒对象
            $value['remind_to_nickname'] = $user_model->getOneUser($value['remind_to'])['nickname'];

            $list[$key]['attachments'] = Db::table('company_order_operations_attachments')->where([
                'company_order_operations_id' => $value['id'],
                'company_order_id' => $value['company_order_id'],
                'status' => 1
            ])->select();
            // $list[$key]['operations_email_templates'] = Db::table('operations_email_templates')->where([
            //     'operation_id' => $value['operation_id'],
            //     'status' => 1
            // ])->select();
            $list[$key]['operations_email_templates'] = Db::table('operations_email_templates')->where([
                'company_id' => $params['company_id'] ,
                'status' => 1
            ])->select();

            $list[$key]['user_arr'] = $user_arr;
        }

        $this->outPut($list);
    }


}