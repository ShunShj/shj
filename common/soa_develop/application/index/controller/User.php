<?php
namespace app\index\controller;
use app\common\help\Help;
use think\config as thinkConfig;
use app\index\controller\Base;

use app\index\model\log\LoginLog;
use app\index\model\system\User as UserModel;
use app\index\model\support\Email;
use app\index\model\log\LoginError;
use app\index\model\Permission;
use think\Model;
use think\Controller;
class User extends Base
{
	private $_language;
    //_lang Base里的属性，
    public function __construct()
    {
    	$this->_language = config("systom_setting")['language_default'];
        parent::__construct();
    }
    /**
     * 添加语言数据
     * 胡
     */
    public function login(){
		
        $params = $this->input();

        $paramRule = [
            'username' => 'string',
            'password'=>'string',
			'ip'=>'string',
        ];
    
        $this->paramCheckRule($paramRule,$params);
		
		$user = new UserModel();
		$login_error = new LoginError('erp_login_error_log');
		$userResult = $user->getUser($params);
		
	
		if(empty($userResult) ){
			\think\Response::create(['code' => '400', 'msg' => 'username or password is error'], 'json')->send();
			exit();
		}
		if($userResult[0]['status']==3 ){
			\think\Response::create(['code' => '400', 'msg' => '此账号已锁定/username is locked'], 'json')->send();
			exit();
		}		
		if($userResult[0]['password'] != md5($params['password'])){//如果密码错误则添加日志
			
			$login_error_params = [
				'user_id'=>	$userResult[0]['user_id']
			];
			$login_error->addLoginError($login_error_params);
			
			$get_params['user_id']=$userResult[0]['user_id'];
			
			$login_error_result = $login_error->getLoginError($get_params);
			

			if(count($login_error_result)>=10){
				$u = new UserModel();
				$u_params = [
						'user_id'=>$userResult[0]['user_id'],
						'status'=>3
				];
				$u->updateUserByUserId($u_params);
			
			}
			
			
			\think\Response::create(['code' => '400', 'msg' => ' username or password is error'], 'json')->send();
			exit();
			
			
		}
	
		if($userResult[0]['status'] != 1){
			\think\Response::create(['code' => '400', 'msg' => ' user is disabled'], 'json')->send();
			exit();
				
				
		}        
		
		
		$log = new LoginLog('soaerp_login_log');
		$log->addLoginLog($params);
		
        $this->outPut($userResult);
    }
    /**
     * 新增用户
     */
    public function addUser(){
    	$params = $this->input();
    	$paramRule = [
    		'username'=>'string',
   			'password'=>'string',
//    			'job_id'=>'number',
   			'nickname'=>'string',
//    			'phone'=>'string',
//    			'language_id'=>'number',
//    			'email'=>'email',
    			'user_id'=>'number',
    			'role_id'=>'number',
    			'department_id'=>'number'
    	];
    
    	$this->paramCheckRule($paramRule,$params);

        //首先判断名字是否有重复
        $data = [
            'username'=>$params['username'],
        ];
        $this->checkNameIsRepetition('user',$data);
        //结束判断名字重复

    	$user = new UserModel();
    	$userResult = $user->addUser($params);
    	$this->outPut($userResult);
    
    }
    
    /**
     * 获取用户
     */
    public function getUser(){
    	$params = $this->input();
    	$user = new UserModel();
    	
    	if(isset($params['page'])){
    		$page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
    		$page = ($params['page']-1)*$page_size;
    		$count = $user->getUser($params, true);
    		$result = $user->getUser($params,false,'true',$page,$page_size);
    		$data = [
    				'count'=>$count,
    				'list'=>$result,
    				'page_count'=>ceil($count/$page_size)
    		];
    	
    		return $this->output($data);
    	}
    	$userResult = $user->getUser($params);
    	$this->outPut($userResult);


    }

    /**
     * 获取用户
     */
    public function getOneUser(){
        $params = $this->input();
        $user = new UserModel();
        $userInfo = $user->getUserByNickname($params);
        $this->outPut($userInfo);
    }
    /**
     * 编辑用户根据用户ID
     */
    public function updateUserByUserId(){
    	$params = $this->input();
    	$paramRule = [
    		'user_id'=>'number'
    	];
    	$this->paramCheckRule($paramRule,$params);
    
    	$user = new UserModel();

        $userInfo = $user->getOneUser($params['user_id']);
        
        
        
        
        if($userInfo['username'] == $params['username']){
        }else{
            //开始判断名字是否重复
            $data = [
                'username'=>$params['username'],
            ];
            $this->checkNameIsRepetition('user',$data);
            //结束判断名字重复
        }
		if($userInfo['status'] == 3 && $params['status'] == 1){
			
			//清空当天的 登录次数
			$login_error = new LoginError('erp_login_error_log');
			$del_params['user_id'] = $params['user_id'];
			$login_error->deleteLoginError($del_params);
		}
        
        
    	$userResult = $user->updateUserByUserId($params);

    	
    	$this->outPut($userResult);
    }
    
    /**
     * 发送邮件找回密码
     */
    public function findPasswordByEmail(){
    	$params = $this->input();
    	$paramRule = [
    		'user_id'=>'number'    	
    	];
    	
    	$this->paramCheckRule($paramRule,$params);
    	
    	//通过USERID查询用户
    	$user = new UserModel();
    	$user_data = [
    		'user_id'=>$params['user_id']	
    	];
    	$user_resukt = $user->getUser($user_data);
    	$user_email = $user_resukt[0]['email'];
    	$code = rand(1111,9999);
    	//发送邮件并记录数据库
    	$add_data = [
    		'type'=>1,
    		'code'=>$code,
    		'user_id'=>$user_resukt[0]['user_id']
    	];
    	$email = new Email();
    	$email_result = $email->addEmail($add_data);
    	if($email_result== 1){
    		if(Help::sendEmail($user_email,'找回密码','您得验证码为'.$code)){
    			$this->outPut(1);
    		}else{
    			\think\Response::create(['code' => '400', 'msg' => ' send email is error'], 'json')->send();
    			exit();
    		}
    	}else{
    		\think\Response::create(['code' => '400', 'msg' => ' add data is error'], 'json')->send();
    		exit();
    	}
    	//sendEmail();
    }
    /**
     * 获取权限
     */
    public function getPermissionByUserId(){
    	$params = $this->input();
    	$paramRule = [
    		'user_id'=>'number',
    		
    	];
    	
    	$this->paramCheckRule($paramRule,$params);
    	$user= new UserModel();
    	$user_permission = $user->getPermissionByUserId($params);
    	$this->outPut($user_permission);
    }
    /**
     * 验证密码
     */
    public function checkEmail(){
    	$params = $this->input();
    	$paramRule = [
    		'user_id'=>'number',
    		'code'=>'number'	
    	];
    	 
    	$this->paramCheckRule($paramRule,$params);
    	
    	$email = new Email();
    	$email_result = $email->getEmail($params);
    	if($email_result['code'] == $params['code']){
    		$this->outPut(1);
    	}else{
    		\think\Response::create(['code' => '400', 'msg' => 'code is error'], 'json')->send();
    	}
    	
    	
    }

    //待办模板邮件发送
    public function sendOperationsEmail(){
        $params = $this->input();
        $paramRule = [
            'to_email'=>'string',
            // 'from_email'=>'string',
            'content'=>'string',
            'subject'=>'string',  
        ]; 
        $this->paramCheckRule($paramRule,$params);


        $this->outPut(Help::sendOperationsEmail($params));
    }






}
