<?php
namespace app\index\model\product;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class routeSourceAllocation extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'route_source_allocation';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
     
    }

    /**
     * 添加资源
     * 胡
     */
    public function addRouteSourceAllocation($params){
    	$t = time();

    	$data['route_template_id'] = $params['route_template_id'];


    	$data['supplier_type_id'] = $params['supplier_type_id'];
    	$data['source_id'] = $params['source_id'];
    	
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
    		$pk_id = $this->insertGetId($data);
		
    		$result = $pk_id;
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
     * 获取路线资源配置
     * 胡
     */
    public function getRouteSourceAllocation($params){
    
    	$data = '1=1 ';
		if(!empty($params['route_template_id'])){
			$data.=" and route_source_allocation.route_template_id = ".$params['route_template_id'];
		}
		if(isset($params['status'])){
			$data.=" and route_source_allocation.status = ".$params['status'];
		}
    	if($params['supplier_type_id'] == 2){
			$data.=" and supplier_type_id = 2";
    		$result = $this->table("route_source_allocation")->alias('route_source_allocation')->where($data)->
    					join("hotel","hotel.hotel_id = route_source_allocation.source_id")->
    		field(['route_source_allocation.route_source_allocation_id',"route_source_allocation.route_template_id","route_source_allocation.supplier_type_id","route_source_allocation.payment_currency_id",
    				"route_source_allocation.source_id",
    				"route_source_allocation.source_price","route_source_allocation.source_count",
    				"route_source_allocation.source_total_price",
    				"route_source_allocation.source_the_days",
    				'hotel.hotel_id','hotel.supplier_id','hotel.room_name','hotel.supplier_type',
    				"(select nickname  from user where user.user_id = route_source_allocation.create_user_id)"=>'create_user_name',
    				"(select nickname  from user where user.user_id = route_source_allocation.update_user_id)"=>'update_user_name',
    				'route_source_allocation.status'
    		
    		])->select();    		
    	}else if($params['supplier_type_id'] == 3){
    		$data.=" and supplier_type_id = 3";
    		$result = $this->table("route_source_allocation")->alias('route_source_allocation')->where($data)->
    		join("dining","dining.dining_id = route_source_allocation.source_id")->
    		field(['route_source_allocation.route_source_allocation_id',"route_source_allocation.route_template_id","route_source_allocation.supplier_type_id","route_source_allocation.payment_currency_id",
    				"route_source_allocation.source_id",
    				"route_source_allocation.source_price","route_source_allocation.source_count",
    				"route_source_allocation.source_total_price",
					"route_source_allocation.source_the_days",
    				'dining.dining_id','dining.supplier_id','dining.supplier_type','dining.standard_type','dining.dining_name',
    				"(select nickname  from user where user.user_id = route_source_allocation.create_user_id)"=>'create_user_name',
    				"(select nickname  from user where user.user_id = route_source_allocation.update_user_id)"=>'update_user_name',
    				'route_source_allocation.status'
    		
    		])->select();    		
    	}else if($params['supplier_type_id'] == 4){
    		$data.=" and supplier_type_id = 4";
    		$result = $this->table("route_source_allocation")->alias('route_source_allocation')->where($data)->
    		join("flight","flight.flight_id = route_source_allocation.source_id")->
    		field(['route_source_allocation.route_source_allocation_id',"route_source_allocation.route_template_id","route_source_allocation.supplier_type_id","route_source_allocation.payment_currency_id",
    				"route_source_allocation.source_id",
    				"route_source_allocation.source_price","route_source_allocation.source_count",
    				"route_source_allocation.source_total_price",
					"route_source_allocation.source_the_days",
    				'flight.flight_id','flight.supplier_id','flight.supplier_type','flight.flight_number','flight.shipping_space','flight.begin_country_id','flight.end_country_id',
    				"(select nickname  from user where user.user_id = route_source_allocation.create_user_id)"=>'create_user_name',
    				"(select nickname  from user where user.user_id = route_source_allocation.update_user_id)"=>'update_user_name',
    				'route_source_allocation.status'
    		
    		])->select();
    	}else if($params['supplier_type_id'] == 5){
    		$data.=" and supplier_type_id = 5";
    		$result = $this->table("route_source_allocation")->alias('route_source_allocation')->where($data)->
    		join("cruise","cruise.cruise_id = route_source_allocation.source_id")->
    		field(['route_source_allocation.route_source_allocation_id',"route_source_allocation.route_template_id","route_source_allocation.supplier_type_id","route_source_allocation.payment_currency_id",
    				"route_source_allocation.source_id",
    				"route_source_allocation.source_price","route_source_allocation.source_count",
    				"route_source_allocation.source_total_price",
					"route_source_allocation.source_the_days",
    				'cruise.cruise_id','cruise.supplier_id','cruise.supplier_type','cruise.room_name','cruise.cruise_name','cruise.room_name','cruise.deck',
    				"(select nickname  from user where user.user_id = route_source_allocation.create_user_id)"=>'create_user_name',
    				"(select nickname  from user where user.user_id = route_source_allocation.update_user_id)"=>'update_user_name',
    				'route_source_allocation.status'
    		
    		])->select();
    	}else if($params['supplier_type_id'] == 6){
    		$data.=" and supplier_type_id = 6";
    		$result = $this->table("route_source_allocation")->alias('route_source_allocation')->where($data)->
    		join("visa","visa.visa_id = route_source_allocation.source_id")->
    		field(['route_source_allocation.route_source_allocation_id',"route_source_allocation.route_template_id","route_source_allocation.supplier_type_id","route_source_allocation.payment_currency_id",
    				"route_source_allocation.source_id",
    				"route_source_allocation.source_price","route_source_allocation.source_count",
    				"route_source_allocation.source_total_price",
					"route_source_allocation.source_the_days",
    				'visa.visa_id','visa.supplier_id','visa.supplier_type','visa.visa_name',
    				"(select nickname  from user where user.user_id = route_source_allocation.create_user_id)"=>'create_user_name',
    				"(select nickname  from user where user.user_id = route_source_allocation.update_user_id)"=>'update_user_name',
    				'route_source_allocation.status'
    		
    		])->select();
    	}else if($params['supplier_type_id'] == 7){
    		$data.=" and supplier_type_id = 7";
    		$result = $this->table("route_source_allocation")->alias('route_source_allocation')->where($data)->
    		join("scenic_spot","scenic_spot.scenic_spot_id = route_source_allocation.source_id")->
    		field(['route_source_allocation.route_source_allocation_id',"route_source_allocation.route_template_id","route_source_allocation.supplier_type_id","route_source_allocation.payment_currency_id",
    				"route_source_allocation.source_id",
    				"route_source_allocation.source_price","route_source_allocation.source_count",
    				"route_source_allocation.source_total_price",
					"route_source_allocation.source_the_days",
    				'scenic_spot.scenic_spot_id','scenic_spot.supplier_id','scenic_spot.supplier_type','scenic_spot.scenic_spot_name',
    				"(select nickname  from user where user.user_id = route_source_allocation.create_user_id)"=>'create_user_name',
    				"(select nickname  from user where user.user_id = route_source_allocation.update_user_id)"=>'update_user_name',
    				'route_source_allocation.status'
    		
    		])->select();
    	}else if($params['supplier_type_id'] == 8){
    		$data.=" and supplier_type_id = 8";
    		$result = $this->table("route_source_allocation")->alias('route_source_allocation')->where($data)->
    		join("vehicle","vehicle.vehicle_id = route_source_allocation.source_id")->
    		field(['route_source_allocation.route_source_allocation_id',"route_source_allocation.route_template_id","route_source_allocation.supplier_type_id","route_source_allocation.payment_currency_id",
    				"route_source_allocation.source_id",
    				"route_source_allocation.source_price","route_source_allocation.source_count",
    				"route_source_allocation.source_total_price",
					"route_source_allocation.source_the_days",
    				'vehicle.vehicle_id','vehicle.supplier_id','vehicle.supplier_type','vehicle.vehicle_name','vehicle.vehicle_number','vehicle.load',
    				"(select nickname  from user where user.user_id = route_source_allocation.create_user_id)"=>'create_user_name',
    				"(select nickname  from user where user.user_id = route_source_allocation.update_user_id)"=>'update_user_name',
    				'route_source_allocation.status'
    		
    		])->select();
    	}else if($params['supplier_type_id'] == 9){
    		$data.=" and supplier_type_id = 9";
    		$result = $this->table("route_source_allocation")->alias('route_source_allocation')->where($data)->
    		join("tour_guide","tour_guide.tour_guide_id = route_source_allocation.source_id")->
    		field(['route_source_allocation.route_source_allocation_id',"route_source_allocation.route_template_id","route_source_allocation.supplier_type_id","route_source_allocation.payment_currency_id",
    				"route_source_allocation.source_id",
    				"route_source_allocation.source_price","route_source_allocation.source_count",
    				"route_source_allocation.source_total_price",
					"route_source_allocation.source_the_days",
    				'tour_guide.tour_guide_id','tour_guide.supplier_id','tour_guide.supplier_type','tour_guide.tour_guide_name',
    				"(select nickname  from user where user.user_id = route_source_allocation.create_user_id)"=>'create_user_name',
    				"(select nickname  from user where user.user_id = route_source_allocation.update_user_id)"=>'update_user_name',
    				'route_source_allocation.status'
    		
    		])->select();
    	}else if($params['supplier_type_id'] == 10){
    		$data.=" and supplier_type_id = 10";
    		$result = $this->table("route_source_allocation")->alias('route_source_allocation')->where($data)->
    		join("single_source","single_source.single_source_id = route_source_allocation.source_id")->
    		field(['route_source_allocation.route_source_allocation_id',"route_source_allocation.route_template_id","route_source_allocation.supplier_type_id","route_source_allocation.payment_currency_id",
    				"route_source_allocation.source_id",
    				"route_source_allocation.source_price","route_source_allocation.source_count",
    				"route_source_allocation.source_total_price",
					"route_source_allocation.source_the_days",
    				'single_source.single_source_id','single_source.supplier_id','single_source.supplier_type','single_source.single_source_name',
    				"(select nickname  from user where user.user_id = route_source_allocation.create_user_id)"=>'create_user_name',
    				"(select nickname  from user where user.user_id = route_source_allocation.update_user_id)"=>'update_user_name',
    				'route_source_allocation.status'
    		
    		])->select();
    	}else if($params['supplier_type_id'] == 11){
            $data.=" and supplier_type_id = 11";
            $result = $this->table("route_source_allocation")->alias('route_source_allocation')->where($data)->
            join("own_expense","own_expense.own_expense_id = route_source_allocation.source_id")->
            field(['route_source_allocation.route_source_allocation_id',"route_source_allocation.route_template_id","route_source_allocation.supplier_type_id","route_source_allocation.payment_currency_id",
                "route_source_allocation.source_id",
                "route_source_allocation.source_price","route_source_allocation.source_count",
                "route_source_allocation.source_total_price",
				"route_source_allocation.source_the_days",
                'own_expense.own_expense_id','own_expense.supplier_id','own_expense.supplier_type','own_expense.own_expense_name',
                "(select nickname  from user where user.user_id = route_source_allocation.create_user_id)"=>'create_user_name',
                "(select nickname  from user where user.user_id = route_source_allocation.update_user_id)"=>'update_user_name',
                'route_source_allocation.status'

            ])->select();
        }


        return $result;
    
    }

    
    /**
     * 修改路线模板资源
     */
    public function updateRouteSourceAllocationByRouteSourceAllocationId($params){
    
    	$t = time();
    	
    	if(!empty($params['source_id'])){
    		$data['source_id'] = $params['source_id'];
    	
    	}
    	if(!empty($params['source_price'])){
    		$data['source_price'] = $params['source_price'];
    		 
    	}
    	if(!empty($params['source_count'])){
    		$data['source_count'] = $params['source_count'];
    		 
    	}
    	if(!empty($params['source_total_price'])){
    		$data['source_total_price'] = $params['source_total_price'];
    		 
    	}
    	if(isset($params['status'])){
    		$data['status'] = $params['status'];
    		 
    	}



    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;

    
    
    	$source_price=[];
    	Db::startTrans();
    	try{
    		Db::name('route_source_allocation')->where("route_source_allocation_id = ".$params['route_source_allocation_id'])->update($data);
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
     * getRouteSourcePrice
     *
     * 获取线路模版报价
     * @author shj
     *
     * @param $params
     *
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Date: 2019/3/27
     * Time: 17:48
     */
    public function getRouteSourcePrice($params){
        $data = "1=1 ";

        //资源主键ID
        if(isset($params['route_source_allocation_id'])){
            $data.= " and route_source_allocation.route_source_allocation_id ='".$params['route_source_allocation_id']."'";
        }

        //线路模板ID
        if(isset($params['route_template_id'])){
            $data.= " and route_source_allocation.route_template_id ='".$params['route_template_id']."'";
        }

        $result =  $this->table("route_source_allocation")->where($data)->
        field(['*',
            "(select nickname  from user where user.user_id = route_source_allocation.create_user_id)"=>'create_user_name',
            "(select nickname  from user where user.user_id = route_source_allocation.update_user_id)"=>'update_user_name',
        ])->select();

        return $result;
    }
}