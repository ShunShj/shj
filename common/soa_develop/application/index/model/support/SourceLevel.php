<?php

namespace app\index\model\support;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class SourceLevel extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'source_level';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

    /**
     * 添加签证
     * 胡
     */
    public function addSourceLevel($params){
    	$t = time();


    	$data['source_level_name'] = $params['source_level_name'];
    	
    	$data['supplier_type_id'] = $params['supplier_type_id'];

    	$data['create_time'] = $t;  	
    	$data['create_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['user_id'];
    	$data['status'] = $params['status'];

    	
    	Db::startTrans();
    	try{
    		Db::name('source_level')->insertGetId($data);

   			
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
     * 获取签证
     * 胡
     */
    public function getSourceLevel($params){
    

    	$data = "1=1 ";
    	if(isset($params['source_level_id'])){
    		$data.= " and source_level.source_level_id= '".$params['source_level_id']."'";
    	}
    	if(isset($params['status'])){
    		$data.= " and source_level.status = ".$params['status'];
    	}
    	if(isset($params['supplier_type_id'])){
    		$data.= " and source_level.supplier_type_id = '".$params['supplier_type_id']."'";
    	}
		
    	if(isset($params['source_level_name'])){
    		$data.= " and source_level.source_level_name = '".$params['source_level_name']."'";
    	}	

        $result = $this->table("source_level")->alias('source_level')->
			join("supplier_type",'supplier_type.supplier_type_id= source_level.supplier_type_id','left')->
			

            where($data)->
            
            field(['source_level.source_level_id',"source_level.source_level_name",

            		'supplier_type.supplier_type_id','supplier_type.supplier_type_name',

            		
            		"(select nickname  from user where user.user_id = source_level.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = source_level.update_user_id)"=>'update_user_name',
            		'source_level.update_time','source_level.create_time',"source_level.status",
            ])->select();
            
     


        return $result;
    
    }

    
    /**
     * 修改签证
     */
    public function updateSourceLevelBySourceLevelId($params){
    
    	$t = time();
    	
    	if(!empty($params['supplier_type_id'])){
    		$data['supplier_type_id'] = $params['supplier_type_id'];
    	
    	}
    	if(!empty($params['source_level_name'])){
    		$data['source_level_name'] = $params['source_level_name'];
    		 
    	}

    	if(!empty($params['status'])){
    		$data['status'] = $params['status'];
    		 
    	}



    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;

    
   
    	Db::startTrans();
    	try{
    		Db::name('source_level')->where("source_level_id = ".$params['source_level_id'])->update($data);
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