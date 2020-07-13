<?php

namespace app\index\model\system;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class EmailTemplate extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'email_template';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

    /**
     * 添加邮件模板
     * 胡
     */
    public function addEmailTemplate($params){
    	$t = time();

    	$data['email_template_title'] = $params['email_template_title'];
    	$data['email_template_content'] = $params['email_template_content'];

    	$data['create_time'] = $t;  	
    	$data['create_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['user_id'];
    	$data['status'] = 1;   		

    	
    
    
    	

    
    
    	Db::startTrans();
    	try{
    		$result = Db::name('email_template')->insertGetId($data);
  
    		 
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
     * 获取邮件模板
     * 胡
     */
    public function getEmailTemplate($params,$is_count=false){//第一个为参数，第二个为是否要获取 总数
    

    	$data = "1=1 ";
    	if(!empty($params['email_template_title'])){
    		$data.= " and email_template.email_template_title= ".$params['email_template_title'];
    	}
        if(!empty($params['email_template_content'])){
            $data.= " and email_template.email_template_content= ".$params['email_template_content'];
        }
    	if(!empty($params['status'])){
    		$data.= " and email_template.status = ".$params['status'];
    	}

    	if(!empty($params['email_template_id'])){
    		$data.= " and email_template.email_template_id = '".$params['email_template_id']."'";
    	}
    	if($is_count==true){
    		$result = $this->where($data)->count();
    	
    	}else{
    		if(isset($params['page'])){
	            $result = $this->table("email_template")->alias('email_template')->

	            where($data)->limit($params['page'],$params['page_size'])->
	            
	            field(['email_template.email_template_id',"email_template.email_template_title","email_template.email_template_content",
	            		"(select nickname  from user where user.user_id = email_template.create_user_id)"=>'create_user_name',
	            		"(select nickname  from user where user.user_id = email_template.update_user_id)"=>'update_user_name',
	            		'email_template.create_time','email_template.update_time',
	            		'email_template.create_user_id','email_template.update_user_id','email_template.status'
	            		
	            ])->order("create_time desc")->select();
	            
	     
    		}else{
	            $result = $this->table("email_template")->alias('email_template')->

	            where($data)->
	            
	            field(['email_template.email_template_id',"email_template.email_template_title","email_template.email_template_content",
	            		"(select nickname  from user where user.user_id = email_template.create_user_id)"=>'create_user_name',
	            		"(select nickname  from user where user.user_id = email_template.update_user_id)"=>'update_user_name',
	            		'email_template.create_time','email_template.update_time',
	            		'email_template.create_user_id','email_template.update_user_id','email_template.status'
	            		
	            ])->order("create_time desc")->select();
    		}
    	
    	}      




        return $result;
    
    }

    
    /**
     * 修改邮件模板 根据email_template_id
     */
    public function updateEmailTemplateByEmailTemplateId($params){
    
    	$t = time();
    	
		
    	if(!empty($params['email_template_title'])){
    		$data['email_template_title'] = $params['email_template_title'];
    		
    	}
	
    	if(!empty($params['email_template_content'])){
    		$data['email_template_content'] = $params['email_template_content'];
    		
    		
    	}   	
    	
	
    	if(!empty($params['status'])){
    		$data['status'] = $params['status'];
    		
    	}


    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;

    
    
    
    	Db::startTrans();
    	try{
    		Db::name('email_template')->where("email_template_id = ".$params['email_template_id'])->update($data);
    	
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
}