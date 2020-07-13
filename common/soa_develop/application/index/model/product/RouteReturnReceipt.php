<?php
namespace app\index\model\product;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class RouteReturnReceipt extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'route_return_receipt';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
     
    }

    /**
     * 添加路线回执单模板内容
     * 胡
     */
    public function addRouteReturnReceiptInfo($params){
    	$t = time();
    	$route_template_id = $params['route_template_id'];
    	$user_id = $params['user_id'];
    	$status = $params['status'];
		$values="insert into route_return_receipt_info(route_template_id,title,content,sorting,create_time,create_user_id,update_time,update_user_id,status) values";
		for($i=0;$i<count($params['route_return_receipt_info']);$i++){
			$title = $params['route_return_receipt_info'][$i]['title'];
			$content = $params['route_return_receipt_info'][$i]['content'];
			$sorting = isset($params['route_return_receipt_info'][$i]['sorting'])?$params['route_return_receipt_info'][$i]['sorting']:1;
			$values.="($route_template_id,'$title','$content',$sorting,$t,$user_id,$t,$user_id,$status),";
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
    public function getRouteReturnReceipt($params){
    

    	$data = [];
    	if(!empty($params['route_return_receipt_id'])){
    		$data['route_return_receipt_id']= $params['route_return_receipt_id'];
    	}
    	if(!empty($params['route_template_id'])){
    		$data['route_template_id']= $params['route_template_id'];
    	}
    	if(!empty($params['status'])){
    		$data['status']= $params['status'];
    	}
		


        $result = $this->
			
            where($data)->
            
            field(['route_return_receipt_id',"route_template_id",'title',
            		'content','sorting',           		
            		"(select nickname  from user where user.user_id = route_return_receipt.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = route_return_receipt.update_user_id)"=>'update_user_name',            		
            		'create_time','update_time',"status",
            ])->order("sorting asc")->select();
            
     


        return $result;
    
    }

    
    /**
     * 修改 路线回执单
     */
    public function updateRouteReturnReceiptByRouteReturnReceiptId($params){
    
    	$t = time();
    	
    	if(!empty($params['title'])){
    		$data['title'] = $params['title'];
    	
    	}
    	if(!empty($params['content'])){
    		$data['content'] = $params['content'];
    		 
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
    		Db::name('route_return_receipt')->where("route_return_receipt_id= ".$params['route_return_receipt_id'])->update($data);
  
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