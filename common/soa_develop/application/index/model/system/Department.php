<?php

namespace app\index\model\system;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class Department extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'department';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

    /**
     * 添加部门
     * 胡
     */
    public function addDepartment($params){
    	$t = time();

    	
    	$data['department_name'] = $params['department_name'];
        if(isset($params['linkman'])){
            $data['linkman'] = $params['linkman'];
        }
        $data['company_id'] = $params['choose_company_id'];
        if(isset($params['linkman'])){
            $data['linkman'] = $params['linkman'];
        }
        if(isset($params['phone'])){
            $data['phone'] = $params['phone'];
        }
    	$data['create_time'] = $t;  	
    	$data['create_user_id'] = $params['user_id'];
    	$data['status'] = 1;   		

    	
    
    
    	

    
    
    	Db::startTrans();
    	try{
    		Db::name('department')->insert($data);

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
     * 获取部门
     * 胡
     */
    public function getDepartment($params,$is_count=false,$is_page=false,$page=null,$page_size=20){//第一个为参数，第二个为是否要获取 总数
    
   
    	$data = "1=1 ";
    	if(isset($params['department_id'])){
    		$data.= " and department.department_id= ".$params['department_id'];
    	}
    	if(isset($params['status'])){
    		$data.= " and department.status = ".$params['status'];
    	}
    	if(isset($params['department_name'])){
    		$data.= " and department.department_name like '%".$params['department_name']."%'";
    	}
	
    	if(isset($params['company_id'])){
    		$data.= " and department.company_id = ".$params['company_id'];
    	}
    	if(isset($params['choose_company_id'])){
    		$data.= " and department.company_id = ".$params['choose_company_id'];
    	}
    	if($is_count==true){
    		$result = $this->where($data)->count();
    		 
    	}else{
    		if($is_page == true){
		        $result= $this->table("department")->alias('department')->
		        join("company com","department.company_id = com.company_id",'left')->
		
		        where($data)->order('create_time desc')->limit($page,$page_size)->
		            
		        field(['department.department_id',"department.department_name","department.company_id",'com.company_name',
		            		'department.linkman','department.phone','department.update_time','department.update_user_id',
		        		'department.create_time','department.create_user_id',"department.status"])->order("create_time desc")->select();
		            
		      
    	
    			 
    		}else{
		        $result= $this->table("department")->alias('department')->
		        join("company com","com.company_id = department.company_id",'left')->
		
		        where($data)->
		            
		        field(['department.department_id',"department.department_name","department.company_id",'com.company_name',
		            		'department.linkman','department.phone','department.update_time','department.update_user_id','department.create_time',
		        		"(select nickname  from user where user.user_id = department.create_user_id)"=>'create_user_name',
		        		"(select nickname  from user where user.user_id = department.update_user_id)"=>'update_user_name',
		        			'department.create_user_id',"department.status"])->order("create_time desc")->select();
		            
		      
    			 
    		}
    		 
    	}



        return $result;
    
    }

    
    /**
     * 修改部门 根据department_id
     */
    public function updateDepartmentByDepartmentId($params){
    
    	$t = time();
    	
		
    	if(!empty($params['department_name'])){
    		$data['department_name'] = $params['department_name'];
    		
    	}

        if(isset($params['linkman'])){
            $data['linkman'] = $params['linkman'];
        }

        if(isset($params['linkman'])){
            $data['linkman'] = $params['linkman'];
        }
        if(isset($params['phone'])){
            $data['phone'] = $params['phone'];
        }
        if(isset($params['choose_company_id'])){
            $data['company_id'] = $params['choose_company_id'];
        }
    	if(!empty($params['status'])){
    		$data['status'] = $params['status'];
    	
    	}


    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;
    	

    
    
    	Db::startTrans();
    	try{
    		Db::name('department')->where("department_id = ".$params['department_id'])->update($data);
    	
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
     * getOneDepartment

     * 获取一条部门信息
     * @author shj
     *
     * @param $department_id
     *
     * @return void
     * Date: 2019/2/28
     * Time: 11:34
     */
    public function getOneDepartment($department_id){
        $result = $this->table("department")->where(['department_id' => $department_id])->find();
        return $result;
    }
}