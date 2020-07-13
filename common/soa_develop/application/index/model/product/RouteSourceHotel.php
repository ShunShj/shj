<?php
namespace app\index\model\product;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class RouteSourceHotel extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'route_source_hotel';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
     
    }

    /**
     * 添加酒店路线资源
     * 胡
     */
    public function addRouteSourceHotel($params){
    	$t = time();


    	$data['route_template_id'] = $params['route_template_id'];
    	$data['hotel_id'] = $params['hotel_id'];
    	$data['source_price'] = $params['source_price'];
    	$data['source_count'] = $params['source_count'];
    	$data['source_total_price'] = $params['source_total_price'];
    	$data['create_time'] = $t;  	
    	$data['create_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['user_id'];
    	$data['status'] = $params['status'];

    	
    	$this->startTrans();
    	try{
    		$result = $this->insertGetId($data);
		
    		 
    		// 提交事务
    		$this->commit();
    
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		$this->rollback();
    		//\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
    		//exit();
    
    	}
    
    	return $result;
    }
    
    /**
     * 获取酒店路线资源
     * 胡
     */
    public function getRouteSourceHotel($params){
    

    	$data = '1=1';
//     	if(isset($params['return_receipt_name'])){
//     		$data['return_receipt_name']= $params['return_receipt_name'];
//     	}
//     	if(isset($params['return_receipt_id'])){
//     		$data['return_receipt_id']= $params['return_receipt_id'];
//     	}
    	if(isset($params['route_template_id'])){
    		$data.= " and route_source_hotel.route_template_id = ".$params['route_template_id'];
    	}
        if(isset($params['status'])){
    		$data.= " and route_source_hotel.status = ".$params['status'];
    	}
    	if(isset($params['route_source_hotel_id'])){
    		$data.= " and route_source_hotel.route_source_hotel_id = ".$params['route_source_hotel_id'];
    	}    	 
        $result = $this->table("route_source_hotel")->alias('route_source_hotel')->where($data)->
        join('hotel','hotel.hotel_id = route_source_hotel.hotel_id','left')->
        join("source_price",'source_price.pk_id = hotel.hotel_id and source_price.supplier_type_id=2','left')->
        join('supplier','supplier.supplier_id = hotel.supplier_id')->
        where($data)->
        field([
        		'route_source_hotel.route_source_hotel_id',
        		'route_source_hotel.source_price','route_source_hotel.source_count',
        		'hotel.hotel_id',"hotel.room_name",
        		 
        		'hotel.guest_number',
        		'hotel.free_wifi',"hotel.room_area",
        		'hotel.floor','hotel.is_add_bed','hotel.smoke_treatment','hotel.remark',
        		'hotel.level_name',
        		'hotel.supplier_id','supplier.supplier_name',
        		'hotel.agent_id',
        		"(select supplier2.supplier_name  from supplier as supplier2 where supplier2.supplier_id = hotel.agent_id)"=>'agent_name',
        		 
        		'hotel.create_time','source_price.payment_currency_type',
        		'source_price.normal_price','source_price.normal_settlement_price',
        		
        		"(select nickname  from user where user.user_id = hotel.create_user_id)"=>'create_user_name',
        		"(select nickname  from user where user.user_id = hotel.update_user_id)"=>'update_user_name',
        		'hotel.update_time',"hotel.status",
        ])->select();

        return $result;
    
    }

    
    /**
     * 修改酒店路线资源
     */
    public function updateRouteSourceHotelByRouteSourceHotelId($params){
    
    	$t = time();
    	
    	if(!empty($params['source_price'])){
    		$data['source_price'] = $params['source_price'];
    	
    	}
    	if(!empty($params['source_count'])){
    		$data['source_count'] = $params['source_count'];
    		 
    	}
    	if(!empty($params['hotel_id'])){
    		$data['hotel_id'] = $params['hotel_id'];
    		 
    	}    	
    	if(!empty($params['status'])){
    		$data['status'] = $params['status'];
    		 
    	}



    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;

    	$this->startTrans();
    	try{
    		$this->where("route_source_hotel_id = ".$params['route_source_hotel_id'])->update($data);
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
}