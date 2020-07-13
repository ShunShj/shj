<?php
namespace app\index\model\system;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class ReturnReceiptInfo extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'return_receipt_info';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

    /**
     * 添加回执单模板内容
     * 胡
     */
    public function addReturnReceiptInfo($params){
    	$t = time();
    	$return_receipt_id = $params['return_receipt_id'];
    	$user_id = $params['now_user_id'];
    	$status = $params['status'];
		$values="insert into return_receipt_info(return_receipt_id,title,content,sorting,create_time,create_user_id,update_time,update_user_id,status) values";
		for($i=0;$i<count($params['return_receipt_info']);$i++){
			$title = $params['return_receipt_info'][$i]['title'];
			$content = $params['return_receipt_info'][$i]['content'];
			$sorting = isset($params['return_receipt_info'][$i]['sorting'])?$params['return_receipt_info'][$i]['sorting']:1;
			$values.="($return_receipt_id,'$title','$content',$sorting,$t,$user_id,$t,$user_id,$status),";
		}
		
    	//删除最右边的,
    	$values = rtrim($values,',');
 
    	$this->startTrans();
    	try{
    		$this->execute($values);
			
    		$result = 1;
    		// 提交事务
    		$this->commit();
    
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		$this->rollback();

    
    	}
    
    	return $result;
    }
    
    /**
     * 获取回执单内容
     * 胡
     */
    public function getReturnReceiptInfo($params){
    

    	$data = "1=1 ";
    	if(isset($params['return_receipt_info_id'])){
    		$data.= " and return_receipt_info.return_receipt_info_id= '".$params['return_receipt_info_id']."'";
    	}
    	if(isset($params['status'])){
    		$data.= " and return_receipt_info.status = ".$params['status'];
    	}
    	if(isset($params['return_receipt_id'])){
    		$data.= " and return_receipt_info.return_receipt_id = '".$params['return_receipt_id']."'";
    	}
		


        $result = $this->table("return_receipt_info")->alias('return_receipt_info')->
			join("return_receipt",'return_receipt.return_receipt_id = return_receipt_info.return_receipt_id')->
            where($data)->
            
            field(['return_receipt_info.return_receipt_id',"return_receipt_info.return_receipt_info_id",
            		"return_receipt.return_receipt_name",
            		'return_receipt_info.title',
            		'return_receipt_info.content',
            		'return_receipt_info.sorting',
            		
            		"(select nickname  from user where user.user_id = return_receipt_info.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = return_receipt_info.update_user_id)"=>'update_user_name',
            		'return_receipt_info.update_time',"return_receipt_info.status",
            ])->order("return_receipt_info.sorting asc")->select();
            
     


        return $result;
    
    }

    
    /**
     * 修改用餐
     */
    public function updateReturnReceiptInfoByReturnReceiptInfoId($params){
    
    	$t = time();
    	
    	if(!empty($params['title'])){
    		$data['title'] = $params['title'];
    	
    	}
    	if(!empty($params['content'])){
    		$data['content'] = $params['content'];
    		 
    	}
    	if(!empty($params['return_receipt_id'])){
    		$data['return_receipt_id'] = $params['return_receipt_id'];
    		 
    	}    	
    	if(!empty($params['sorting'])){
    		$data['sorting'] = $params['sorting'];
    		 
    	}
    	if(!empty($params['status'])){
    		$data['status'] = $params['status'];
    		 
    	}



    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;

    
    
    	$source_price=[];
    	Db::startTrans();
    	try{
    		Db::name('return_receipt_info')->where("return_receipt_info_id= ".$params['return_receipt_info_id'])->update($data);
  
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