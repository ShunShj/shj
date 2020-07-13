<?php
namespace app\index\model\product;
use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use app\index\model\product\TeamProduct;
use think\config;
use think\Db;
use think\Controller;
use app\index\service\CompanyOrderService;
/**
* 线路模板 团队产品模块
* HUGH
**/
class ProductCommon extends Model{
	private $_public_service;
	//protected $table = 'team_product';
	private $_team_product;
	public function initialize()
	{

		$this->_public_service = new PublicService();
		$this->_team_product = new TeamProduct();
		parent::initialize();
	 
	}
	/**
	* 线路模板同步团队产品
	* HUGH
	*/
	public function synchronizationTeamProduct($params){
		$route_template_id = $params['route_template_id'];

		//获取线路模板信息
		$where['route_template_id'] = $route_template_id;
		$route_template = $this->table('route_template')->where($where)->find(); //基本信息

		//行程
		$where['status'] = 1;
		$route_journey = $this->table('route_journey')->where($where)->select();
		//航班
		$route_flight = $this->table('route_flight')->where($where)->select();
		//回执单
		$route_return_receipt = $this->table('route_return_receipt')->where($where)->select();
		//资源
		$route_source_allocation = $this->table('route_source_allocation')->where($where)->select();
		//一口价
		$route_once_price = $this->table('route_once_price')->where($where)->select();
		unset($where);

		//团队产品
		$where = "route_template_id = {$route_template_id}";
		if($params['s_time']){ //开始
			$s_time = strtotime($params['s_time']);
			$where .= " and begin_time >={$s_time}";
		}
		if($params['e_time']){ //结束
			$e_time = strtotime($params['e_time']);
			$where .= " and begin_time <={$e_time}" ;
		}
		
		
		$team_product = $this->table('team_product')->where($where)->select();
		
		$k=1;
		unset($where);
		$plur_status_id = []; 
		try{
			
			$this->startTrans();
			foreach($team_product as $ky=>$vl){
				
				if($vl['plur_status']==0){
					$plur_status_id[] = $vl['team_product_id'];
				}

				$where['team_product_id'] = $vl['team_product_id'];
				//基本
				$d['team_product_name'] = $route_template['route_name']; //团队产品名称
				$d['route_type_id'] = $route_template['route_type_id']; //路线类型ID
				$d['use_company_id'] = $route_template['use_company_id']; //可见分公司ID，多个用逗号连接所有用星号
				$d['settlement_type'] = $route_template['settlement_type']; //'结算方式1
				$d['team_product_user_id'] = $route_template['route_user_id']; //团队产品负责人ID
				$d['before_days'] = $route_template['before_days']; // 出团前几天截至收客
				$d['plan_custom_number'] = $route_template['plan_custom_number']; //计划收客人数
				$d['update_time'] = time();
				$d['update_user_id'] = $params['user_id'];
				$this->table('team_product')->where($where)->update($d);
				
				
				//团队产品的命名规则
				$route_type_code = $this->_public_service->getRouteTypeRecursion($vl['route_type_id']);
				//再获取有几个团队产品
				
				 
				$team_product_params_two = [
					'begin_time_day'=>date("Ymd",$vl['begin_time'])
				];
				$team_product_result = $this->_team_product->getTeamProduct($team_product_params_two);
				
				$team_product_code = count($team_product_result)+$k;
				
				$team_product_code = str_pad($team_product_code,3,'0',STR_PAD_LEFT );
					
					
			
				$team_product_params = [
						'team_product_id'=>$vl['team_product_id'],
						'team_product_number'=>$route_type_code.'-'.date('y',$vl['begin_time']).'-'.date('md',$vl['begin_time']).$team_product_code
				];
				
			
				$this->_team_product->updateTeamProductBaseByTeamProductBaseId($team_product_params);
				$k++;
				
				unset($d);

				//行程
				$this->table('team_product_journey')->where($where)->update(['status'=>0]);
				foreach($route_journey as $v){
					$d['team_product_id'] = $vl['team_product_id'];
					$d['the_days'] = $v['the_days'];
					$d['route_journey_title'] = $v['route_journey_title'];
					$d['route_journey_content'] = $v['route_journey_content'];
					$d['route_journey_traffic'] = $v['route_journey_traffic'];
					$d['route_journey_stay'] = $v['route_journey_stay'];
					$d['eat_mark'] = $v['eat_mark'];
					$d['route_journey_breakfast'] = $v['route_journey_breakfast'];
					$d['route_journey_lunch'] = $v['route_journey_lunch'];
					$d['route_journey_dinner'] = $v['route_journey_dinner'];
					$d['route_journey_scenic_sport'] = $v['route_journey_scenic_sport'];
					$d['route_journey_picture'] = $v['route_journey_picture'];
					$d['route_journey_remark'] = $v['route_journey_remark'];
					$d['route_journey_zone'] = $v['route_journey_zone'];
					$d['create_time'] = time();
					$d['create_user_id'] = $params['user_id'];
					$d['update_time'] = time();
					$d['update_user_id'] = $params['user_id'];
					$d['status'] = 1;
					$this->table('team_product_journey')->insert($d);
					unset($d);
				}
				//航班
				$this->table('team_product_flight')->where($where)->update(['status'=>0]);
				foreach ($route_flight as $v) {
					$d['team_product_id'] = $vl['team_product_id'];
					$d['the_days'] = $v['the_days'];	 
					$d['start_city'] = $v['start_city'];	 
					$d['end_city'] = $v['end_city'];	 
					$d['start_time'] = $v['start_time'];	 
					$d['end_time'] = $v['end_time'];	 
					$d['flight_number'] = $v['flight_number'];	 
					$d['flight_type'] = $v['flight_type'];	 
					$d['create_time'] = time();
					$d['create_user_id'] = $params['user_id'];
					$d['update_time'] = time();
					$d['update_user_id'] = $params['user_id']; 
					$d['status'] = 1;
					$this->table('team_product_flight')->insert($d);
					unset($d);
				}
				//回执单
				$this->table('team_product_return_receipt')->where($where)->update(['status'=>0]);
				foreach ($route_return_receipt as $v) {
					$d['team_product_id'] = $vl['team_product_id'];
					$d['title'] = $v['title']; 
					$d['content'] = $v['content']; 
					$d['sorting'] = $v['sorting']; 
					$d['create_time'] = time();
					$d['create_user_id'] = $params['user_id'];
					$d['update_time'] = time();
					$d['update_user_id'] = $params['user_id']; 
					$d['status'] = 1;
					$this->table('team_product_return_receipt')->insert($d);
					unset($d);
				}
				//资源
				$this->table('team_product_allocation')->where($where)->update(['status'=>0]);
				foreach($route_source_allocation as $v){
					$d['team_product_id'] = $vl['team_product_id'];
					$d['supplier_type_id'] = $v['supplier_type_id'];
					$d['source_id'] = $v['source_id'];
					$d['payment_currency_id'] = $v['payment_currency_id'];
					$d['source_price'] = $v['source_price'];
					$d['source_count'] = $v['source_count'];
					$d['source_total_price'] = $v['source_total_price'];
					$d['source_the_days'] = $v['source_the_days'];
					$d['create_time'] = time();
					$d['create_user_id'] = $params['user_id'];
					$d['update_time'] = time();
					$d['update_user_id'] = $params['user_id']; 
					$d['status'] = 1;
					$this->table('team_product_allocation')->insert($d);
					unset($d);
				}
				//一口价
				$this->table('team_product_once_price')->where($where)->update(['status'=>0]);
				foreach ($route_once_price as $v) {
					$d['team_product_id'] = $vl['team_product_id'];
					$d['company_id'] = $v['company_id'];
					$d['total_price'] = $v['total_price'];
					$d['once_price_type'] = $v['once_price_type'];
					$d['team_price_currency_id'] = $v['team_price_currency_id'];
					$d['own_price_currency_id'] = $v['own_price_currency_id'];
					$d['create_time'] = time();
					$d['create_user_id'] = $params['user_id'];
					$d['update_time'] = time();
					$d['update_user_id'] = $params['user_id']; 
					$d['status'] = 1;
					$this->table('team_product_once_price')->insert($d);
					unset($d);
				}

			}

			$this->commit();

			if(count($plur_status_id)>0){
				$up['team_product_id'] = implode(',', $plur_status_id);
				$CompanyOrderService = new CompanyOrderService();
	            $CompanyOrderService->CompanyOrderInfoChangeByTeamProduct($up);
			}
			

			return true;
		}catch (\Exception $e) { 
			$this->rollback();
            return $result = $e->getMessage(); 
        } 	
	}

	/**
	* 根据线路模板获取团队产品
	**/
	public function selTeamProductbyRouteTemplateId($params){
		$where = "plur_status = 0 and status = 1 and route_template_id =".$params['route_template_id']." and (company_id = ".$params['user_company_id']." or ( find_in_set('".$params['user_company_id']."',use_company_id) or team_product.use_company_id = '*' ))";
	

		return $this->table('team_product')->where($where)->select();
	}


	/**
	* 复制新的团 HUGH
	* 团队编号
	***/
	public function replicatingRegiment($team_product_id,$user_id){
		$num = ['A','B','C','D','E','F','G','H','I','J','E','F','G','H'];
		try{
			$this->startTrans();

			//获取原团队内容
			$where['team_product_id'] = $team_product_id;
			$team_product = $this->table('team_product')->field('*')->where($where)->find();
			unset($where);	
			foreach ($num as $key => $value) {
		  		$d['team_product_number'] = $team_product['team_product_number'].'-'.$value;
				//重复验证
				$team_product_id = $this->table('team_product')->field('team_product_id')->where($d)->find();
				if(empty($team_product_id)){
					break;
				}
			}

			 $d['route_template_id'] = $team_product['route_template_id'];
			 $d['begin_time'] = $team_product['begin_time'];
			 $d['team_product_name'] = $team_product['team_product_name'];
			 $d['route_type_id'] = $team_product['route_type_id'];
			 $d['use_company_id'] = $team_product['use_company_id'];
			 $d['settlement_type'] = $team_product['settlement_type'];
			 $d['team_product_user_id'] = $team_product['team_product_user_id'];
			 $d['before_days'] = $team_product['before_days'];
			 $d['plan_custom_number'] = $team_product['plan_custom_number'];
			 $d['company_id'] = $team_product['company_id'];
			 $d['is_travel_agency_reimbursement'] = 0;
			 $d['is_establish_team_product'] = 0;
			 $d['create_time'] = time();
			 $d['create_user_id'] = $user_id;
			 $d['update_time'] = time();
			 $d['update_user_id'] = $user_id;
			 $d['plur_status'] = 0;
			 $d['status'] = 1;
			 Db::table('team_product')->insert($d);
			 $n_team_product_id = Db::name('team_product')->getLastInsID(); 
			 unset($d);	

			 //获取团队产品行程
			 $where['team_product_id'] = $team_product_id;
			 $where['status'] = 1;
			 $team_product_journey = $this->table('team_product_journey')->field('*')->where($where)->select();	
			 unset($where);
			 foreach ($team_product_journey as $key => $value) {
			 	 $d['team_product_id'] = $n_team_product_id;
			 	 $d['the_days'] = $value['the_days'];
			 	 $d['route_journey_title'] = $value['route_journey_title'];
			 	 $d['route_journey_content'] = $value['route_journey_content'];
			 	 $d['route_journey_traffic'] = $value['route_journey_traffic'];
			 	 $d['route_journey_stay'] = $value['route_journey_stay'];
			 	 $d['eat_mark'] = $value['eat_mark'];
			 	 $d['route_journey_breakfast'] = $value['route_journey_breakfast'];
			 	 $d['route_journey_lunch'] = $value['route_journey_lunch'];
			 	 $d['route_journey_dinner'] = $value['route_journey_dinner'];
			 	 $d['route_journey_scenic_sport'] = $value['route_journey_scenic_sport'];
			 	 $d['route_journey_picture'] = $value['route_journey_picture'];
			 	 $d['route_journey_remark'] = $value['route_journey_remark'];
			 	 $d['route_journey_zone'] = $value['route_journey_zone'];
			 	 $d['create_time'] = time();
				 $d['create_user_id'] = $user_id;
				 $d['update_time'] = time();
				 $d['update_user_id'] = $user_id;
			 	 $d['status'] = 1;
			 	 Db::table('team_product_journey')->insert($d);
			 	 unset($d);
			  } 

			  //获取团队产品航班
		     $where['team_product_id'] = $team_product_id;
			 $where['status'] = 1;
			 $team_product_flight = $this->table('team_product_flight')->field('*')->where($where)->select();	
			 unset($where);
			 foreach ($team_product_flight as $key => $value) {
			 	 $d['team_product_id'] = $n_team_product_id;
			 	 $d['the_days'] = $value['the_days'];
			 	 $d['start_city'] = $value['start_city'];
			 	 $d['end_city'] = $value['end_city'];
			 	 $d['start_time'] = $value['start_time'];
			 	 $d['end_time'] = $value['end_time'];
			 	 $d['flight_number'] = $value['flight_number'];
			 	 $d['flight_type'] = $value['flight_type'];
			 	 $d['create_time'] = time();
				 $d['create_user_id'] = $user_id;
				 $d['update_time'] = time();
				 $d['update_user_id'] = $user_id;
			 	 $d['status'] = 1;
			 	 Db::table('team_product_flight')->insert();
			 	 unset($d);
			 }

			 //获取资源
			 $where['team_product_id'] = $team_product_id;
			 $where['status'] = 1;
			 $team_product_allocation = $this->table('team_product_allocation')->field('*')->where($where)->select();
			 unset($where);	
			 foreach ($team_product_allocation as $key => $value) {
			 	 $d['team_product_id'] = $n_team_product_id;
			 	 $d['supplier_type_id'] = $value['supplier_type_id'];
			 	 $d['source_id'] = $value['source_id'];
			 	 $d['payment_currency_id'] = $value['payment_currency_id'];
			 	 $d['source_price'] = $value['source_price'];
			 	 $d['source_count'] = $value['source_count'];
			 	 $d['source_total_price'] = $value['source_total_price'];
			 	 $d['source_the_days'] = $value['source_the_days'];
			 	 $d['create_time'] = time();
				 $d['create_user_id'] = $user_id;
				 $d['update_time'] = time();
				 $d['update_user_id'] = $user_id;
			 	 $d['status'] = 1;
			 	 Db::table('team_product_allocation')->insert();
			 	 unset($d);
			 }

			 //一口价
			 $where['team_product_id'] = $team_product_id;
			 $where['status'] = 1;
			 $team_product_once_price = $this->table('team_product_once_price')->field('*')->where($where)->select();
			 unset($where);	
			 foreach ($team_product_once_price as $key => $value) {
			 	 $d['team_product_id'] = $n_team_product_id;
			 	 $d['company_id'] = $value['company_id'];
			 	 $d['total_price'] = $value['total_price'];
			 	 $d['once_price_type'] = $value['once_price_type'];
			 	 $d['team_price_currency_id'] = $value['team_price_currency_id'];
			 	 $d['own_price_currency_id'] = $value['own_price_currency_id'];
			 	 $d['create_time'] = time();
				 $d['create_user_id'] = $user_id;
				 $d['update_time'] = time();
				 $d['update_user_id'] = $user_id;
			 	 $d['status'] = 1;
			 	 Db::table('team_product_once_price')->insert();
			 	 unset($d);
			 }

			// 提交事务
    		$this->commit();
    		return $n_team_product_id;
		}catch (\Exception $e) { 
			$this->rollback();
            return $result = $e->getMessage(); 
        } 		
	}


	/**
	* 获取各线路人数统计
	**/
	public function getNumberOfPassengersOnALine($params){
		$company_id = $params['company_id'];

		 
		//线路类型
		if($company_id){$w['company_id'] =  $company_id;} 
		$w['status'] = 1;
		$route_type = $this->table('route_type')->field(['route_type_id','route_type_name','pid'])->where($w)->select();
		unset($w);
		if(!empty($route_type)){
		    // $result = Help::toTree($route_type);  
		    // foreach($result as &$value){
      //               $value['route_type_name'] = str_repeat('--', $value['level']).$value['route_type_name'];
      //       }
            $r['route_type'] = $route_type;
		}  

		//线路产品
		if($company_id){$w['a.company_id'] = $company_id;}
		$w['a.status'] = 1;
		// if($params['s_time']){
	 //    	$w['b.begin_time'] = ['>=',$params['s_time']];
	 //    }
	 //    if($params['e_time']){
		// 	$w['b.begin_time'] = ['<=',$params['e_time']];
	 //    }
		if($params['route_type_id']){
	    	$w['a.route_type_id'] = $params['route_type_id'];
	    }
	    if($params['route_user']){
	    	$where['nickname'] = ['like',"%{$params['route_user']}%"];
            $user_id_ar = $this->table('user')->where($where)->field(['user_id'])->select();
            $ar = [];
            foreach ($user_id_ar as $key => $value) {
                array_push($ar, $value['user_id']);
            }
            $im = implode(',', $ar);
            $w['a.route_user_id'] = ['in',$im];
	    }

		$route_template = $this->table('route_template')->alias('a')
		->join('team_product b','a.route_template_id=b.route_template_id')
		->field([
			'a.route_template_id','a.route_name','a.route_type_id',
			"(select nickname from user where user_id=a.route_user_id) as nickname"
		])->where($w)->group('a.route_template_id')->select();
		unset($w);
		$r['route_template'] = $route_template; 


		//获取分公司线路收客人数
	    $w['b.status' ] = ['in','1,2,3'];
	    $w['c.status'] =1;
	    if($params['s_time']){
	    	$w['d.begin_time'] = ['>=',$params['s_time']];
	    }
	    if($params['e_time']){
			$w['d.begin_time'] = ['<=',$params['e_time']];
	    }
	    if($params['route_type_id']){
	    	$w['e.route_type_id'] = $params['route_type_id'];
	    }

	    $art = $this->table('company_order_product_template')->alias('a')
	    ->join('company_order_customer b','a.company_order_number=b.company_order_number')
	    ->join('company_order c','a.company_order_number=c.company_order_number and b.company_order_number=c.company_order_number')
	    ->join('team_product d','d.team_product_id=a.team_product_id')
	    ->join('route_template e','e.route_template_id=d.route_template_id')
	    ->field(['a.team_product_id','b.company_order_customer_id','c.company_id','e.route_template_id','e.route_type_id'])->where($w)->select();
	  	$r['NumberOfPassengersOnALine'] = $art;
	  	// return $this->table('company_order_product_template')->getLastSql(); eixt;
	  	return $r;
	}
	
	/**
	* 获取同类团队产品(分团)
	**/
	public function otherRegimentNumber($params){
		$w['team_product_id'] = $params['team_product_id'];
		$team_product = $this->table('team_product')->field('*')->where($w)->find(); 
		if(empty($team_product)){
			return [];
		}
		unset($w);

		$w['status'] = 1;
		$w['plur_status'] = 0;
		$w['begin_time'] = $team_product['begin_time'];
		$w['route_template_id'] = $team_product['route_template_id'];
		$w['team_product_id'] = ['<>',$params['team_product_id']];
		return $this->table('team_product')->field('*')->where($w)->select(); 
	}

	//执行订单移团操作
	public function performOrderTransferGroupOperation($params){
		foreach ($params['order_number'] as $key => $value) {
			$where['company_order_number'] = $value; //订单编号
			$where['team_product_id'] = $params['old_team_product_id'];  //原团队产品编号
			$where['status'] = 1;
		}  
	}

	/**
    * 复制线路模板
    */
	public function copyCircuitTemplate($params){
		

		try{
			$this->startTrans();
			//获取线路模板数据
			$w['route_template_id'] = $params['route_template_id'];
			$route_template = $this->table('route_template')->where($w)->find();
			unset($w);

			$d['route_number'] = Help::getNumber(1);//获取线路编号
			$d['company_id'] = $route_template['company_id'];
			$d['route_name'] = $route_template['route_name'].'-复制';
			$d['route_type_id'] = $route_template['route_type_id'];
			$d['route_user_id'] = $route_template['route_user_id'];
			$d['use_company_id'] = $route_template['use_company_id'];
			$d['settlement_type'] = $route_template['settlement_type'];
			$d['before_days'] = $route_template['before_days'];
			$d['plan_custom_number'] = $route_template['plan_custom_number'];
			$d['status'] = 1;
			$d['create_time'] = time();
			$d['update_time'] = time();
			$d['create_user_id'] = $params['user_id'];
			$d['update_user_id'] = $params['user_id'];
			// error_log(print_r($d,1));
			$n_team_product_id = $this->table('route_template')->insertGetId($d);
			$this->_public_service->setNumber('route_template', 'route_template_id', $n_team_product_id, 'route_number',$d['route_number'] , $n_team_product_id);
			
			
			
			unset($d);
			

			//获取行程
			$w['status'] = 1;
			$w['route_template_id'] = $params['route_template_id'];
			$route_journey = $this->table('route_journey')->where($w)->select();
			unset($w);
			foreach ($route_journey as $key => $value) {
				$d['route_template_id'] = $n_team_product_id;	 
				$d['the_days'] = $value['the_days'];	 
				$d['route_journey_title'] = $value['route_journey_title'];	 
				$d['route_journey_content'] = $value['route_journey_content'];	 
				$d['route_journey_traffic'] = $value['route_journey_traffic'];	 
				$d['route_journey_stay'] = $value['route_journey_stay'];	 
				$d['eat_mark'] = $value['eat_mark'];	 
				$d['route_journey_breakfast'] = $value['route_journey_breakfast'];	 
				$d['route_journey_lunch'] = $value['route_journey_lunch'];	 
				$d['route_journey_dinner'] = $value['route_journey_dinner'];	 
				$d['route_journey_scenic_sport'] = $value['route_journey_scenic_sport'];	 
				$d['route_journey_picture'] = $value['route_journey_picture'];	 
				$d['route_journey_remark'] = $value['route_journey_remark'];	 
				$d['route_journey_zone'] = $value['route_journey_zone'];	 
				$d['status'] = 1;	 
				$d['create_time'] = time();
				$d['update_time'] = time();
				$d['create_user_id'] = $params['user_id'];
				$d['update_user_id'] = $params['user_id'];
				$this->table('route_journey')->insert($d);
				unset($d);
			}

			//获取行程航班
			$w['status'] = 1;
			$w['route_template_id'] = $params['route_template_id'];
			$route_flight = $this->table('route_flight')->where($w)->select();
			unset($w);
			foreach ($route_flight as $key => $value) {
				$d['route_template_id']= $n_team_product_id;
				$d['the_days'] = $value['the_days'];
				$d['start_city'] = $value['start_city'];
				$d['end_city'] = $value['end_city'];
				$d['start_time'] = $value['start_time'];
				$d['end_time'] = $value['end_time'];
				$d['flight_number'] = $value['flight_number'];
				$d['flight_type'] = $value['flight_type'];
				$d['status'] = 1;	 
				$d['create_time'] = time();
				$d['update_time'] = time();
				$d['create_user_id'] = $params['user_id'];
				$d['update_user_id'] = $params['user_id'];
				$this->table('route_flight')->insert($d);
				unset($d);
			} 
			
			//获取回执单内容
			$w['status'] = 1;
			$w['route_template_id'] = $params['route_template_id'];
			$route_return_receipt = $this->table('route_return_receipt')->where($w)->select();
			unset($w);
			foreach($route_return_receipt as $key=>$value){
				$d['route_template_id'] = $n_team_product_id;
				$d['title'] = $value['title'];
				$d['content'] = $value['content'];
				$d['sorting'] = $value['sorting'];
				$d['status'] = 1;	 
				$d['create_time'] = time();
				$d['update_time'] = time();
				$d['create_user_id'] = $params['user_id'];
				$d['update_user_id'] = $params['user_id'];
				$this->table('route_return_receipt')->insert($d);
				unset($d);
			}

			//获取资源
			$w['status'] = 1;
			$w['route_template_id'] = $params['route_template_id'];
			$route_source_allocation = $this->table('route_source_allocation')->where($w)->select();
			unset($w);
			foreach ($route_source_allocation as $key => $value) {
				$d['route_template_id'] = $n_team_product_id;
				$d['supplier_type_id'] = $value['supplier_type_id'];
				$d['source_id'] = $value['source_id'];
				$d['payment_currency_id'] = $value['payment_currency_id'];
				$d['source_price'] = $value['source_price'];
				$d['source_count'] = $value['source_count'];
				$d['source_total_price'] = $value['source_total_price'];
				$d['source_the_days'] = $value['source_the_days'];
				$d['status'] = 1;	 
				$d['create_time'] = time();
				$d['update_time'] = time();
				$d['create_user_id'] = $params['user_id'];
				$d['update_user_id'] = $params['user_id'];
				$this->table('route_source_allocation')->insert($d);
				unset($d);
			}

			//一口价
			$w['status'] = 1;
			$w['route_template_id'] = $params['route_template_id'];
			$route_once_price = $this->table('route_once_price')->where($w)->select();
			unset($w);
			foreach ($route_once_price as $key => $value) {
				$d['route_template_id'] = $n_team_product_id;
				$d['company_id'] = $value['company_id'];
				$d['total_price'] = $value['total_price'];
				$d['once_price_type'] = $value['once_price_type'];
				$d['team_price_currency_id'] = $value['team_price_currency_id'];
				$d['own_price_currency_id'] = $value['own_price_currency_id'];
				$d['status'] = 1;	 
				$d['create_time'] = time();
				$d['update_time'] = time();
				$d['create_user_id'] = $params['user_id'];
				$d['update_user_id'] = $params['user_id'];
				$this->table('route_once_price')->insert($d);
				unset($d);
			} 

			$this->commit();
    		return $n_team_product_id; 
		}catch (\Exception $e) { 
			$this->rollback();
            return $result = $e->getMessage(); 
        } 	
		




	}


}