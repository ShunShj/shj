<?php
namespace app\index\model\product;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class RouteFlight extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'route_flight';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
     
    }

    /**
     * 添加路线航班线路
     * 胡
     */
    public function addRouteFlight($params){
    	$t = time();
    	$user_id = $params['user_id'];
		$data['route_template_id'] = $params['route_template_id'];
		if(!empty($params['the_days'])){
			$data['the_days'] = $params['the_days'];
		
		}
    	if(!empty($params['start_city'])){
    		$data['start_city'] = $params['start_city'];
    		
    	}
    	if(!empty($params['end_city'])){
    		$data['end_city'] = $params['end_city'];
    	}
    	if(!empty($params['start_time'])){
    		$data['start_time'] = $params['start_time'];
    	}
    	if(!empty($params['end_time'])){
    		$data['end_time'] = $params['end_time'];
    	}
    	if(!empty($params['flight_number'])){
    		$data['flight_number'] = $params['flight_number'];
    	}
    	if(!empty($params['flight_type'])){
    		$data['flight_type'] = $params['flight_type'];
    	}
		$data['create_time'] = $t;
		$data['create_user_id']= $user_id;
		$data['update_time'] = $t;
    	$data['update_user_id'] = $user_id;
    	$data['status'] = $params['status'];
    	
        $this->startTrans();
    	try{
    		$result  = $this->insertGetId($data);
			
    		
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
     * 获取路线航班信息
     * 胡
     */
    public function getRouteFlight($params){
    

    	$data = [];
    	if(isset($params['route_flight_id'])){
    		$data['route_flight_id']= $params['route_flight_id'];
    	}
    	if(isset($params['route_template_id'])){
    		$data['route_template_id']= $params['route_template_id'];
    	}
    	if(isset($params['status'])){
    		$data['status']= $params['status'];
    	}

        if(isset($params['the_days'])){
            $data['the_days']= $params['the_days'];
        }
    	 
        $result = $this->table("route_flight")->alias('route_flight')->where($data)->
            
            field(["route_flight.route_flight_id","route_flight.the_days","route_flight.start_city","route_flight.end_city",
            		"route_flight.start_time","route_flight.end_time","route_flight.flight_number","route_flight.flight_type",
            		
            		
            		"route_flight.create_time","route_flight.update_time",
            		"(select nickname  from user where user.user_id = route_flight.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = route_flight.update_user_id)"=>'update_user_name',
            		'route_flight.status'
            		
            		
            ])->select();

        return $result;
    
    }
	/**
	 * 修改线路模板的航班
	 */
    public function updateRouteFlightByRouteFlightId($params){
    	$t = time();
    	 
    	if(!empty($params['the_days']) ){
    		$data['the_days'] = $params['the_days'];
    		 
    	}
    	if(!empty($params['start_city']) ){
    		$data['start_city'] = $params['start_city'];
    		 
    	}
    	if(!empty($params['end_city']) ){
    		$data['end_city'] = $params['end_city'];
    		 
    	}
    	if(!empty($params['start_time']) ){
    		$data['start_time'] = $params['start_time'];
    		 
    	}
    	if(!empty($params['end_time']) ){
    		$data['end_time'] = $params['end_time'];    		 
    	}
    	if(!empty($params['flight_number']) ){
    		$data['flight_number'] = $params['flight_number'];
    	}    	
    	if(!empty($params['flight_type']) ){
    		$data['flight_type'] = $params['flight_type'];
    	}
        if(is_numeric($params['status'])){
            $data['status'] = $params['status'];

        }
    	$data['update_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;
    	
    	
    	
    	$source_price=[];
    	Db::startTrans();
    	try{
    		Db::name('route_flight')->where("route_flight_id = ".$params['route_flight_id'])->update($data);
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