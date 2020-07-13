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

class Supplier extends Base
{

	//获取供应商数据
	public function getSupplier(){

		
		$data = [

			'status'=>1,
			"belong_supplier_id"=>input('supplier_id'),
			'supplier_type'=>2
		];
		
		$result = $this->callSoaErp('post', '/source/getHotel',$data);
		return   $result;//['code' => '400', 'msg' => $data];
	}
	
	/**
	 * 获取当前供应商下的地接数据 以及当前供应商下的房型
	 * 
	 */
	public function getAgentInfoAndRoomBySupplierId(){
		$agent_data = [
		
			'status'=>1,
			"belong_supplier_id"=>input('supplier_id'),
			'supplier_type'=>2
		];
		
		$agent_result = $this->callSoaErp('post', '/source/getHotel',$agent_data);
		$result['agent_result'] = $agent_result['data'];
		$room_data = [
		
			'status'=>1,
			"supplier_id"=>input('supplier_id'),
			'supplier_type'=>1
		];
		
		$room_result  = $this->callSoaErp('post', '/source/getHotel',$room_data);
		
		$result['room_result'] = $room_result['data'];
	
		return   ['code'=>200,'msg'=>'success',data=>$result];	
		
	}
    

}

