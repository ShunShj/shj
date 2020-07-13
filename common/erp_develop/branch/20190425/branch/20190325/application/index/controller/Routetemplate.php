<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/25
 * Time: 10:00
 */
namespace app\index\controller;

use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;
use app\common\help\Contents;

class Routetemplate extends Base
{

	/**
	 * 添加酒店资源到线路模板
	 */
	public function addHotelSource(){
		
		$data = [
			'route_template_id'=>input('route_template_id'),
			'source_price'=>input('hotel_single_price'),
			'source_count'=> input('hotel_quantity'),
			'source_total_price'=>input('hotel_total_price'),
			'route_template_id'=>input('route_template_id'),
			'status'=>1,
			'user_id'=>session('user')['user_id'],
			'source_id'=>input('source_id'),
			'supplier_type_id'=>2	
				
		];
	
		$result = $this->callSoaErp('post', '/product/addRouteSource',$data);
		return   $result;//['code' => '400', 'msg' => $data];
	}
	
	
	/**
	 * 修改酒店资源数据
	 */
	public function routeTemplateAllicationById(){
		$request = Request::instance()->param();
	

		$data = [
			'route_source_allocation_id'=>input('route_template_allocation_id'),

			'user_id'=>session('user')['user_id'],
			'source_id'=>input('source_id')	
		];
		if(isset($request['status'])){
			$data['status'] = input('status');
		} 
		if(isset($request['source_price'])){
			$data['source_price'] = input('source_price');
		}
		if(isset($request['source_count'])){
			$data['source_count'] = input('source_count');
		}
		if(isset($request['source_total_price'])){
			$data['source_total_price'] = input('source_total_price');
		}

		$result = $this->callSoaErp('post', '/product/updateRouteSourceAllocationByRouteSourceAllocationId',$data);
		return   $result;//['code' => '400', 'msg' => $data];		
	}

	/**
	 * 添加用餐资源到线路模板
	 */
	public function addDingingSource(){
		
		$data = [
			'route_template_id'=>input('route_template_id'),
			'source_price'=>input('dining_single_price'),
			'source_count'=> input('dining_quantity'),
			'source_total_price'=>input('dining_total_price'),
			'route_template_id'=>input('route_template_id'),
			'status'=>1,
			'user_id'=>session('user')['user_id'],
			'source_id'=>input('source_id'),
			'supplier_type_id'=>3			
		];
	
		$result = $this->callSoaErp('post', '/product/addRouteSource',$data);
		return   $result;//['code' => '400', 'msg' => $data];
	}
	/**
	 * 添加航班资源到线路模板
	 */
	public function addFlightSource(){
		
		$data = [
			'route_template_id'=>input('route_template_id'),
			'source_price'=>input('flight_single_price'),
			'source_count'=> input('flight_quantity'),
			'source_total_price'=>input('flight_total_price'),
			'status'=>1,
			'user_id'=>session('user')['user_id'],
			'source_id'=>input('source_id'),
			'supplier_type_id'=>4			
		];
	
		$result = $this->callSoaErp('post', '/product/addRouteSource',$data);
		return   $result;//['code' => '400', 'msg' => $data];
	}
	/**
	 * 添加邮轮资源到线路模板
	 */
	public function addCruiseSource(){
		
		$data = [
			'route_template_id'=>input('route_template_id'),
			'source_price'=>input('cruise_single_price'),
			'source_count'=> input('cruise_quantity'),
			'source_total_price'=>input('cruise_total_price'),
			'status'=>1,
			'user_id'=>session('user')['user_id'],
			'source_id'=>input('source_id'),
			'supplier_type_id'=>5			
		];
	
		$result = $this->callSoaErp('post', '/product/addRouteSource',$data);
		return   $result;//['code' => '400', 'msg' => $data];
	}
	/**
	 * 添加签证资源到线路模板
	 */
	public function addVisaSource(){
		
		$data = [
			'route_template_id'=>input('route_template_id'),
			'source_price'=>input('visa_single_price'),
			'source_count'=> input('visa_quantity'),
			'source_total_price'=>input('visa_total_price'),
			'status'=>1,
			'user_id'=>session('user')['user_id'],
			'source_id'=>input('source_id'),
			'supplier_type_id'=>6			
		];
	
		$result = $this->callSoaErp('post', '/product/addRouteSource',$data);
		return   $result;//['code' => '400', 'msg' => $data];
	}
	/**
	 * 添加景点资源到线路模板
	 */
	public function addScenicSpotSource(){
		
		$data = [
			'route_template_id'=>input('route_template_id'),
			'source_price'=>input('scenicspot_single_price'),
			'source_count'=> input('scenicspot_quantity'),
			'source_total_price'=>input('scenicspot_total_price'),
			'status'=>1,
			'user_id'=>session('user')['user_id'],
			'source_id'=>input('source_id'),
			'supplier_type_id'=>7			
		];
	
		$result = $this->callSoaErp('post', '/product/addRouteSource',$data);
		return   $result;//['code' => '400', 'msg' => $data];
	}
	/**
	 * 添加车辆资源到线路模板
	 */
	public function addVehicleSource(){
		
		$data = [
			'route_template_id'=>input('route_template_id'),
			'source_price'=>input('vehicle_single_price'),
			'source_count'=> input('vehicle_quantity'),
			'source_total_price'=>input('vehicle_total_price'),
			'status'=>1,
			'user_id'=>session('user')['user_id'],
			'source_id'=>input('source_id'),
			'supplier_type_id'=>8			
		];
	
		$result = $this->callSoaErp('post', '/product/addRouteSource',$data);
		return   $result;//['code' => '400', 'msg' => $data];
	}
	/**
	 * 添加导游资源到线路模板
	 */
	public function addTourGuideSource(){
		
		$data = [
			'route_template_id'=>input('route_template_id'),
			'source_price'=>input('tourguide_single_price'),
			'source_count'=> input('tourguide_quantity'),
			'source_total_price'=>input('tourguide_total_price'),
			'status'=>1,
			'user_id'=>session('user')['user_id'],
			'source_id'=>input('source_id'),
			'supplier_type_id'=>9			
		];
	
		$result = $this->callSoaErp('post', '/product/addRouteSource',$data);
		return   $result;//['code' => '400', 'msg' => $data];
	}
	/**
	 * 添加单项资源到线路模板
	 */
	public function addSingleSource(){
		
		$data = [
			'route_template_id'=>input('route_template_id'),
			'source_price'=>input('single_source_single_price'),
			'source_count'=> input('single_source_quantity'),
			'source_total_price'=>input('single_source_total_price'),
			'status'=>1,
			'user_id'=>session('user')['user_id'],
			'source_id'=>input('source_id'),
			'supplier_type_id'=>10			
		];
	
		$result = $this->callSoaErp('post', '/product/addRouteSource',$data);
		return   $result;//['code' => '400', 'msg' => $data];
	}
    /**
     * 添加单项资源到线路模板
     */
    public function addOwnExpenseSource(){

        $data = [
            'route_template_id'=>input('route_template_id'),
            'source_price'=>input('own_expense_single_price'),
            'source_count'=> input('own_expense_quantity'),
            'source_total_price'=>input('own_expense_total_price'),
            'status'=>1,
            'user_id'=>session('user')['user_id'],
            'source_id'=>input('source_id'),
            'supplier_type_id'=>11
        ];

        $result = $this->callSoaErp('post', '/product/addRouteSource',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }
}

