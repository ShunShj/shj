<?php
namespace app\index\model\product;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class RouteGoTeam extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'team_product_schedule';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
     
    }

    /**
     * 添加路线出团
     * 胡
     */
    public function addRouteGoTeam($params){
    	$t = time();
    	

    	$route_template_id = $params['route_template_id'];
    	$user_id = $params['user_id'];
    	$status = $params['status'];
    	$values="insert into route_go_team(route_template_id,route_go_team_time,create_time,create_user_id,update_time,update_user_id,status) values";
    	for($i=0;$i<count($params['route_team_info']);$i++){
    		$time = $params['route_team_info'][$i]['route_go_team_time'];

    		$values.="($route_template_id,$time,$t,$user_id,$t,$user_id,$status),";

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
     * 获取路线出团
     * 胡
     */
    public function getRouteGoTeam($params){
    

    	$data = [];
    	if(isset($params['return_receipt_name'])){
    		$data['return_receipt_name']= $params['return_receipt_name'];
    	}
    	if(isset($params['return_receipt_id'])){
    		$data['return_receipt_id']= $params['return_receipt_id'];
    	}
    	if(isset($params['status'])){
    		$data['status']= $params['status'];
    	}
    	 
        $result = $this->table("return_receipt")->alias('return_receipt')->where($data)->
            
            field(['*',
            		"(select nickname  from user where user.user_id = return_receipt.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = return_receipt.update_user_id)"=>'update_user_name',
            		
            ])->select();

        return $result;
    
    }

    
    /**
     * 修改路线出团
     */
    public function updateRouteGoTeamByRouteGoTeamId($params){
    
    	$t = time();
    	
    	if(!empty($params['return_receipt_name'])){
    		$data['return_receipt_name'] = $params['return_receipt_name'];
    	
    	}

    	if(!empty($params['status'])){
    		$data['status'] = $params['status'];
    		 
    	}



    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;

    
    
    	$source_price=[];
    	Db::startTrans();
    	try{
    		Db::name('return_receipt')->where("return_receipt_id = ".$params['return_receipt_id'])->update($data);
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