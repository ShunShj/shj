<?php

namespace app\index\model\system;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class Auth extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'auth';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

    /**
     * 添加权限
     * 胡
     */
    public function addAuth($params){
    	$t = time();

    	
    	$data['pid'] = $params['pid'];
    	$data['level'] = $params['level'];
    	$data['type'] = 1;
    	if($params['level'] == 1){
    		if(!empty($params['controller_name'])){
    			$data['controller_name'] = $params['controller_name'];
    		}
    		if(!empty($params['function_name'])){
    			$data['function_name'] = $params['function_name'];
    		}   
    		if(!empty($params['controller_explain'])){
    			$data['controller_explain'] = $params['controller_explain'];
    		}
    		if(!empty($params['function_explain'])){
    			$data['function_explain'] = $params['function_explain'];
    		}
    	}else{
    		if(!empty($params['button_name'])){
    			$data['button_name'] = $params['button_name'];
    		}
    		if(!empty($params['button_explain'])){
    			$data['button_explain'] = $params['button_explain'];
    		}

    	}
 		
    	
		
    


    	$data['create_time'] = $t;  	
    	$data['create_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['user_id'];
    	$data['status'] = 1;   		

    	
    
    	
    	

    
    
    	Db::startTrans();
    	try{
    		
    		Db::name('auth')->insert($data);
  
    		$result = 1;
    		// 提交事务
    		Db::commit();
    
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		Db::rollback();
    		//\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
    		//exit();
    
    	}
    
    	return $result;
    }
    
    /**
     * 获取权限
     * 胡
     */
    public function getAuth($params,$is_count=false){//第一个为参数，第二个为是否要获取 总数
    	
    	$data = [];
    	if(!empty($params['auth_id'])){
    		$data['auth_id'] = $params['auth_id'];
    	}
    	if(!empty($params['pid'])){
    		$data['pid'] = $params['pid'];
    	}
    	if(is_numeric($params['status']) && $params['status']<2){
    		$data['status'] = $params['status'];
    	}
    	if(!empty($params['controller_name'])){
    		$data['controller_name'] = $params['controller_name'];
    	}
    	if(!empty($params['level'])){
    		$data['level'] = $params['level'];
    	}		
    	if($is_count==true){
    		$result = $this->where($data)->count();
    		 
    	}else{
    		if(isset($params['page'])){
       			$result =   $this->table("auth")->alias('auth')->
       						where($data)->limit($params['page'],$params['page_size'])->
            
		            		field(['auth.auth_id','auth.pid',"auth.controller_name","auth.function_name","auth.button_name", 
		            		"auth.controller_explain","auth.function_explain","auth.button_explain",
		            		'auth.level','auth.type',
		            		'auth.create_time',
		            		"(select nickname  from user where user.user_id = auth.create_user_id)"=>'create_user_name',
		            		"(select nickname  from user where user.user_id = auth.update_user_id)"=>'update_user_name',
		            		'auth.update_time',"auth.status",
		            		])->order('create_time desc')->select();
    			 
    	
    		}else{
       			$result = $this->table("auth")->alias('auth')->where($data)->
            
		            		field(['auth.auth_id','auth.pid',"auth.controller_name","auth.function_name","auth.button_name", 
		            				"auth.controller_explain","auth.function_explain","auth.button_explain",
		            		'auth.level','auth.type',
		            		'auth.create_time',
		            		"(select nickname  from user where user.user_id = auth.create_user_id)"=>'create_user_name',
		            		"(select nickname  from user where user.user_id = auth.update_user_id)"=>'update_user_name',
		            		'auth.update_time',"auth.status",
		            		])->order('create_time desc')->select();
    	
    		}
    		 
    	}    	
    	

    	
    	return $result;

    }

    
    /**
     * 修改权限根据权限ID
     */
    public function updateAuthByAuthId($params){
    
    	$t = time();
    	
    
    	if(!empty($params['controller_name'])){
    		$data['controller_name'] = $params['controller_name'];
    		
    	}
	
	
    	if(!empty($params['function_name'])){
    		$data['function_name'] = $params['function_name'];
    	
    	
    	}
    	if(!empty($params['button_name'])){
    		$data['button_name'] = $params['button_name'];
    	
    	
    	}
    	if(!empty($params['controller_explain'])){
    		$data['controller_explain'] = $params['controller_explain'];
    	
    	}
    	
    	
    	if(!empty($params['function_explain'])){
    		$data['function_explain'] = $params['function_explain'];
    		 
    		 
    	}
    	if(!empty($params['button_explain'])){
    		$data['button_explain'] = $params['button_explain'];
    		 
    		 
    	}
    	if(!empty($params['pid'])){
    		$data['pid'] = $params['pid'];
    	
    	
    	}
    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    		
    	}


    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;
    	

    
    
    
    	Db::startTrans();
    	try{
    		Db::name('auth')->where("auth_id = ".$params['auth_id'])->update($data);
    		
    		if($params['level'] == 2){ //如果等级为2则需要 查询控制器与方法名修改此条数据;
    			$params_pid['auth_id'] = $params['pid'];
    			$result = $this->getAuth($params_pid);
    			
    			$result_data['controller_name'] = $result[0]['controller_name'];
    			$result_data['function_name'] = $result[0]['function_name'];
    			Db::name('auth')->where("auth_id = ".$params['auth_id'])->update($result_data);
    		}
    		$result = 1;
    		// 提交事务
    		Db::commit();
    
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		Db::rollback();
    
    	}
    	return $result;
    }
    /**
     * 获取权限配置
     */

}