<?php

namespace app\index\model\system;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class Role extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'role';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

    /**
     * 添加角色
     * 胡
     */
    public function addRole($params){
    	$t = time();

    	
    	$data['role_name'] = $params['role_name'];


    	$data['create_time'] = $t;  	
    	$data['create_user_id'] = $params['user_id'];
    	$data['update_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;
    	$data['status'] = 1;   		

    	
    
    
    	

    
    
    	Db::startTrans();
    	try{
    		Db::name('role')->insert($data);
  
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
     * 获取角色
     * 胡
     */
    public function getRole($params,$is_count=false){//第一个为参数，第二个为是否要获取 总数
    
	
    	$data = [];
    	if(!empty($params['role_id'])){
    		$data['role_id']= $params['role_id'];
    	}
    	if(!empty($params['role_name'])){
		
    		$data['role_name']= ['like', "%".$params['role_name']."%"];
    	}
    	if(is_numeric($params['status'])){
    		$data['status']= $params['status'];
    	}
   
    	if($is_count==true){
    		$result = $this->where($data)->count();
    		 
    	}else{
    		if(isset($params['page'])){
    	
    			

    			$result = $this->where($data)->order('create_time desc')->limit($params['page'],$params['page_size'])->
    			
    					field([
    					'role_id','role_name','create_time','update_time',
    					"(select nickname  from user where user.user_id = role.create_user_id)"=>'create_user_name',
    					"(select nickname  from user where user.user_id = role.update_user_id)"=>'update_user_name',
    					'status'
    			
    			])->order("create_time desc")->select();
    			 
    		}else{
				$result = $this->where($data)->order('create_time desc')->limit($params['page'],$params['page_size'])->
					field([
					'role_id','role_name','create_time','update_time',
					"(select nickname  from user where user.user_id = role.create_user_id)"=>'create_user_name',
		            "(select nickname  from user where user.user_id = role.update_user_id)"=>'update_user_name',
					'status'
					
					])->order("create_time desc")->select();
    			 
    		}
    		 
    	}
		
        return $result;
    
    }

    
    /**
     * 修改角色根据角色ID
     */
    public function updateRoleByRoleId($params){
    
    	$t = time();
    	
		
    	if(!empty($params['role_name'])){
    		$data['role_name'] = $params['role_name'];
    		
    	}
	

	
    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    		
    	}


    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;
    	
    	$data_en_us['update_user_id'] = $params['user_id'];    
    	$data_en_us['update_time'] = $t;
    
    
    
    	Db::startTrans();
    	try{
    		Db::name('role')->where("role_id = ".$params['role_id'])->update($data);
    	
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
     * getOneRole
     *
     * 获取一条角色信息
     * @author shj
     *
     * @param $role_id
     *
     * @return void
     * Date: 2019/2/28
     * Time: 14:09
     */
    public function getOneRole($role_id){
        $result = $this->table("role")->where(['role_id' => $role_id])->find();
        return $result;
    }
}