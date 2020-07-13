<?php
namespace app\index\model\product;
use think\Model;
use app\common\help\Help;
use app\index\model\system\Currency;
use app\index\model\branchcompany\CompanyOrderCustomer;
use app\index\model\branchcompany\CompanyOrder;
use app\index\model\branchcompany\CompanyOrderProductTeam;
use app\index\service\ProportionService;
use app\index\service\PublicService;
use app\index\model\system\User as UserModel;
use app\index\service\CompanyOrderService;
use think\config;
use think\Db;
use think\Controller;
class TeamProduct extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'team_product';
    private $_languageList;
    private $_currency;
    private $_proportion_service;
    private $_user;
    private $_company_order_customer;
  
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	$this->_currency = new Currency();
    	$this->_proportion_service = new ProportionService();
    	$this->_user = new UserModel();
    	

    	//$this->_company_order_customer = new CompanyOrderCustomer();
    	parent::initialize();
    
    }

    /**
    * 修改团队产品的团队状态
    * HUGH
    */
    public function upPlurStatusAjax($params){
        $w['team_product_id'] = $params['team_product_id'];
        $data['plur_status'] = $params['plur_status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = time();
        return DB::table('team_product')->where($w)->update($data); 
    }

    /**
     * 添加团队产品
     * 韩
     */
    public function addTeamProduct($params){
    	$public_service = new PublicService();
    	$t = time();
    	$user_id = $params['user_id'];
        $nickname = $this->table('user')->where("user_id={$user_id}")->find()['nickname'];
        // return $nickname;    
    	//产品编号
		$data['team_product_number']=$params['team_product_number'];
		//团名
		$data['team_product_name']=$params['team_product_name'];
		//线路模板ID
        $data['route_template_id']=$params['route_template_id'];
		//路线类型ID
		$data['route_type_id'] = $params['route_type_id'];
		//可见分公司
		$data['use_company_id'] = $params["use_company_id"];
		//结算方式
		$data['settlement_type'] = $params['settlement_type'];
		//团队产品负责人ID
		$data['team_product_user_id'] = $params['team_product_user_id'];
		//计划收客人数
        $data['plan_custom_number'] = $params['plan_custom_number'];
		//出团前几天截至收客
		$data['before_days'] = $params['before_days'];
		//开团日期
        $data['begin_time'] = $params['begin_time'];

        //$data['begin_time'] = Help::changeTimeZone($params['begin_time'],'',$params['time_zone'],'Asia/Shanghai');
        //$data['time_zone'] = $params['time_zone'];
        
		$data['company_id'] = $params['user_company_id'];
    	$data['create_time'] = $t;  	
    	$data['create_user_id'] = $user_id;
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $user_id;
    	$data['status'] = $params['status'];
        $data['operational_log'] = $params['operational_log'];
    	$this->startTrans();
    	try{
    		$team_product_params_two = [
    				'begin_time_day'=>date("Ymd",$data['begin_time'])
    		];
    		$team_product_result = $this->getTeamProduct($team_product_params_two);
    		//获取主键
    		$pk_id = $this->insertGetId($data);
			//团队产品的命名规则
			$route_type_code = $public_service->getRouteTypeRecursion($data['route_type_id']);
    		//再获取有几个团队产品

    	
    		
	
			$team_product_code = count($team_product_result)+1;
			$team_product_code = str_pad($team_product_code,3,'0',STR_PAD_LEFT );
			
			
			
    		$team_product_params = [
    			'team_product_id'=>$pk_id,
    			'team_product_number'=>$route_type_code.'-'.date('y',$data['begin_time']).'-'.date('md',$data['begin_time']).$team_product_code
    		];

    		
			$this->updateTeamProductBaseByTeamProductBaseId($team_product_params);
    		
    		
    		
    		
            //创建团队产品基本信息 日志
            // $nd['user_name'] = $nickname;
            // $nd['model_name'] = 'team_product';
            // $nd['model_id'] = $pk_id;
            // $nd['type'] = 'C';
            // $nd['created_at'] = time();
            // Db::table('audits')->insert($nd);
            // $audit_id = DB::table->getLastInsID();
            // unset($nd); 
            // $nd['audit_id'] = $audit_id;
            // $nd['field_name'] = 'team_product_number';
            // $nd['old_value'] = '';
            // $nd['new_value'] = $params['team_product_number'];
            // Db::table('audit_detail')->insert($nd);
            // unset($nd);
            // $nd['audit_id'] = $audit_id;
            // $nd['field_name'] = 'route_template_id';
            // $nd['old_value'] = '';
            // $nd['new_value'] = $params['route_template_id'];
            // Db::table('audit_detail')->insert($nd);
            // unset($nd);
            // $nd['audit_id'] = $audit_id;
            // $nd['field_name'] = 'begin_time';
            // $nd['old_value'] = '';
            // $nd['new_value'] = $params['begin_time'];
            // Db::table('audit_detail')->insert($nd);
            // unset($nd);
            // $nd['audit_id'] = $audit_id;
            // $nd['field_name'] = 'team_product_name';
            // $nd['old_value'] = '';
            // $nd['new_value'] = $params['team_product_name'];
            // Db::table('audit_detail')->insert($nd);
            // unset($nd);
            // $nd['audit_id'] = $audit_id;
            // $nd['field_name'] = 'route_type_id';
            // $nd['old_value'] = '';
            // $nd['new_value'] = $params['route_type_id'];
            // Db::table('audit_detail')->insert($nd);
            // unset($nd);
            // $nd['audit_id'] = $audit_id;
            // $nd['field_name'] = 'use_company_id';
            // $nd['old_value'] = '';
            // $nd['new_value'] = $params['use_company_id'];
            // Db::table('audit_detail')->insert($nd);
            // unset($nd);
            // $nd['audit_id'] = $audit_id;
            // $nd['field_name'] = 'settlement_type';
            // $nd['old_value'] = '';
            // $nd['new_value'] = $params['settlement_type'];
            // Db::table('audit_detail')->insert($nd);
            // unset($nd);
            // $nd['audit_id'] = $audit_id;
            // $nd['field_name'] = 'team_product_user_id';
            // $nd['old_value'] = '';
            // $nd['new_value'] = $params['team_product_user_id'];
            // Db::table('audit_detail')->insert($nd);
            // unset($nd);
            // $nd['audit_id'] = $audit_id;
            // $nd['field_name'] = 'before_days';
            // $nd['old_value'] = '';
            // $nd['new_value'] = $params['before_days'];
            // Db::table('audit_detail')->insert($nd);
            // unset($nd);
            // $nd['audit_id'] = $audit_id;
            // $nd['field_name'] = 'plan_custom_number';
            // $nd['old_value'] = '';
            // $nd['new_value'] = $params['plan_custom_number'];
            // Db::table('audit_detail')->insert($nd);
            // unset($nd);
            // $nd['audit_id'] = $audit_id;
            // $nd['field_name'] = 'create_time';
            // $nd['old_value'] = '';
            // $nd['new_value'] = $params['create_time'];
            // Db::table('audit_detail')->insert($nd);
            // unset($nd);
            // $nd['audit_id'] = $audit_id;
            // $nd['field_name'] = 'create_user_id';
            // $nd['old_value'] = '';
            // $nd['new_value'] = $params['create_user_id'];
            // Db::table('audit_detail')->insert($nd);
            // unset($nd);

    		//添加航班信息
    		$flight_values="insert into team_product_flight (team_product_id,the_days,start_city,end_city,start_time,end_time,flight_number,flight_type,create_time,create_user_id,update_time,update_user_id,status) values";

    		if(!empty($params['team_product_flight_info'])){
                for($i=0;$i<count($params['team_product_flight_info']);$i++){
                    //团队产品ID
                    $team_product_id = $pk_id;
                    //第几天
                    $the_days = $params['team_product_flight_info'][$i]['the_days'];
                    //出发地
                    $start_city = $params['team_product_flight_info'][$i]['start_city'];
                    //目的地
                    $end_city = $params['team_product_flight_info'][$i]['end_city'];
                    //出发时间
                    $start_time = $params['team_product_flight_info'][$i]['start_time'];
                    //到达时间
                    $end_time = $params['team_product_flight_info'][$i]['end_time'];
                    //航班编号
                    $flight_number = $params['team_product_flight_info'][$i]['flight_number'];
                    //接送机
                    $flight_type = $params['team_product_flight_info'][$i]['flight_type'];

                    $create_time = $t;
                    $create_user_id = $user_id;
                    $update_time = $t;
                    $update_user_id = $user_id;
                    // $status = $params['status'];
                    $status = 1;

                    if($i!=count($params['team_product_flight_info'])-1){
                        $comma = ',';
                    }else{
                        $comma = '';
                    }


                    $flight_values.="($team_product_id,$the_days,'$start_city','$end_city',$start_time,$end_time,'$flight_number',$flight_type,$create_time,$create_user_id,$update_time,$update_user_id,$status)".$comma;
                
                }

                $this->execute($flight_values);
            }

			//添加行程内容
			$journey_values="insert into team_product_journey (team_product_id,the_days,route_journey_title,route_journey_content,route_journey_traffic,route_journey_stay,eat_mark,route_journey_breakfast,route_journey_lunch,route_journey_dinner,route_journey_scenic_sport,route_journey_picture,route_journey_remark,route_journey_zone,create_time,create_user_id,update_time,update_user_id,status) values";


            if(!empty($params['team_product_journey_info'])){
                for($i=0;$i<count($params['team_product_journey_info']);$i++){
                	$route_journey_data = [];
                	$route_journey_data['team_product_id'] = $pk_id;
                	 
                	if(!empty($params['team_product_journey_info'][$i]['the_days'])){
                		$route_journey_data['the_days'] = $params['team_product_journey_info'][$i]['the_days'];
                	}
                	if(!empty($params['team_product_journey_info'][$i]['route_journey_traffic'])){
                		$route_journey_data['route_journey_traffic'] = $params['team_product_journey_info'][$i]['route_journey_traffic'];
                	}
                	if(!empty($params['team_product_journey_info'][$i]['route_journey_stay'])){
                		$route_journey_data['route_journey_stay'] = $params['team_product_journey_info'][$i]['route_journey_stay'];
                	}
                	if(!empty($params['team_product_journey_info'][$i]['eat_mark'])){
                		$route_journey_data['eat_mark'] = $params['team_product_journey_info'][$i]['eat_mark'];
                	}
                	if(!empty($params['team_product_journey_info'][$i]['route_journey_breakfast'])){
                		$route_journey_data['route_journey_breakfast'] = $params['team_product_journey_info'][$i]['route_journey_breakfast'];
                	}
                	if(!empty($params['team_product_journey_info'][$i]['route_journey_lunch'])){
                		$route_journey_data['route_journey_lunch'] = $params['team_product_journey_info'][$i]['route_journey_lunch'];
                	}
                	if(!empty($params['team_product_journey_info'][$i]['route_journey_dinner'])){
                		$route_journey_data['route_journey_dinner'] = $params['team_product_journey_info'][$i]['route_journey_dinner'];
                	}
                	if(!empty($params['team_product_journey_info'][$i]['route_journey_scenic_sport'])){
                		$route_journey_data['route_journey_scenic_sport'] = $params['team_product_journey_info'][$i]['route_journey_scenic_sport'];
                	}
                	if(!empty($params['team_product_journey_info'][$i]['route_journey_picture'])){
                		$route_journey_data['route_journey_picture'] = $params['team_product_journey_info'][$i]['route_journey_picture'];
                	}
                	if(!empty($params['team_product_journey_info'][$i]['route_journey_remark'])){
                		$route_journey_data['route_journey_remark'] = $params['team_product_journey_info'][$i]['route_journey_remark'];
                	}
                	if(!empty($params['team_product_journey_info'][$i]['route_journey_content'])){
                		$route_journey_data['route_journey_content'] = $params['team_product_journey_info'][$i]['route_journey_content'];
                	}
                	if(!empty($params['team_product_journey_info'][$i]['route_journey_title'])){
                		$route_journey_data['route_journey_title'] = $params['team_product_journey_info'][$i]['route_journey_title'];
                	}
                	
    
                	 
                	$route_journey_data['create_time'] = $t;
                	$route_journey_data['create_user_id'] = $params['user_id'];
                	$route_journey_data['update_time'] = $t;
                	$route_journey_data['update_user_id'] = $params['user_id'];
                	$route_journey_data['status'] = 1;
                	$this->table('team_product_journey')->insert($route_journey_data);
                }	 
                	/*
                    //团队产品ID
                    $team_product_id = $pk_id;
                    //第几天
                    $the_days = $params['team_product_journey_info'][$i]['the_days'];
                    //行程标题
                    $route_journey_title = $params['team_product_journey_info'][$i]['route_journey_title'];
                    //行程内容
                    $route_journey_content = $params['team_product_journey_info'][$i]['route_journey_content'];
                    //交通
                    $route_journey_traffic = $params['team_product_journey_info'][$i]['route_journey_traffic'];
                    //住宿
                    $route_journey_stay = $params['team_product_journey_info'][$i]['route_journey_stay'];
                    //吃饭标注
                    $eat_mark = $params['team_product_journey_info'][$i]['eat_mark'];
                    //早餐
                    $route_journey_breakfast = $params['team_product_journey_info'][$i]['route_journey_breakfast'];
                    //午餐
                    $route_journey_lunch = $params['team_product_journey_info'][$i]['route_journey_lunch'];
                    //晚餐
                    $route_journey_dinner = $params['team_product_journey_info'][$i]['route_journey_dinner'];
                    //景点
                    $route_journey_scenic_sport = $params['team_product_journey_info'][$i]['route_journey_scenic_sport'];
                    //图片
                    $route_journey_picture = addslashes($params['team_product_journey_info'][$i]['route_journey_picture']);
                    //备注
                    $route_journey_remark = $params['team_product_journey_info'][$i]['route_journey_remark'];
                    //地区
                    $route_journey_zone = $params['team_product_journey_info'][$i]['route_journey_zone'];
                    if(!is_numeric($route_journey_zone)){
                    	$route_journey_zone=0;
                    }
                    $create_time = $t;
                    $create_user_id = $user_id;
                    $update_time = $t;
                    $update_user_id = $user_id;
                    // $status = $params['status'];
                    $status = 1;

                    if($i!=count($params['team_product_journey_info'])-1){
                        $comma = ',';
                    }else{
                        $comma = '';
                    }

                    $journey_values.="($team_product_id,$the_days,'$route_journey_title','$route_journey_content','$route_journey_traffic','$route_journey_stay','$eat_mark','$route_journey_breakfast','$route_journey_lunch','$route_journey_dinner','$route_journey_scenic_sport','$route_journey_picture','$route_journey_remark','$route_journey_zone',$create_time,$create_user_id,$update_time,$update_user_id,$status)".$comma;

                }
                $this->execute($journey_values);
                */
            }

            //添加资源信息
            $allocation_values="insert into team_product_allocation (team_product_id,supplier_type_id,source_id,payment_currency_id,source_price,source_count,source_total_price,source_the_days,create_time,create_user_id,update_time,update_user_id,status) values";

            if(!empty($params['team_product_allocation_info'])){
       
                for($i=0;$i<count($params['team_product_allocation_info']);$i++){
                    //团队产品ID
                    $team_product_id = $pk_id;
                    //资源类型ID
                    $supplier_type_id = $params['team_product_allocation_info'][$i]['supplier_type_id'];
                    //对应资源ID
                    $source_id = $params['team_product_allocation_info'][$i]['source_id'];
                    //币种
                    $payment_currency_id = $params['team_product_allocation_info'][$i]['payment_currency_id'];
                    //单价
                    $source_price = $params['team_product_allocation_info'][$i]['source_price'];
                    //数量
                    $source_count = $params['team_product_allocation_info'][$i]['source_count'];
                    //总价
                    $source_total_price = $params['team_product_allocation_info'][$i]['source_total_price'];
                    //第几天
                    $source_the_days =1;// $params['team_product_allocation_info'][$i]['source_the_days'];

                    $create_time = $t;
                    $create_user_id = $user_id;
                    $update_time = $t;
                    $update_user_id = $user_id;
                    $status = 1;

                    if($i!=count($params['team_product_allocation_info'])-1){
                        $comma = ',';
                    }else{
                        $comma = '';
                    }
                    $allocation_values.="($team_product_id,$supplier_type_id,$source_id,$payment_currency_id,$source_price,$source_count,$source_total_price,$source_the_days,$create_time,$create_user_id,$update_time,$update_user_id,$status)".$comma;
                
                }
				
               $this->execute($allocation_values);
            }

            if(!empty($params['settlement_type'])){
                //假如是一口价
                if($params['settlement_type'] == 1){
                    $once_price_values="insert into team_product_once_price (team_product_id,company_id,team_price_currency_id,total_price,create_time,create_user_id,update_time,update_user_id,status) values";

                    for($i=0;$i<count($params['team_product_once_price']);$i++){
                        //团队产品ID
                        $team_product_id = $pk_id;

                        $company_id = $params['team_product_once_price'][$i]['company_id'];
                        $total_price = $params['team_product_once_price'][$i]['total_price'];
                        //一口价币种
						$team_price_currency_id =$params['team_product_once_price'][$i]['team_price_currency_id'];
                        $create_time = $t;
                        $create_user_id = $user_id;
                        $update_time = $t;
                        $update_user_id = $user_id;
                        // $status = $params['status']; 
                        $status = 1;

                        if($i!=count($params['team_product_once_price'])-1){
                            $comma = ',';
                        }else{
                            $comma = '';
                        }
                        $once_price_values.="($team_product_id,$company_id,$team_price_currency_id,$total_price,$create_time,$create_user_id,$update_time,$update_user_id,$status)".$comma;

                    }

                    $this->execute($once_price_values);
                }
            }

            //添加回执单模版
            $return_receipt_values="insert into team_product_return_receipt (team_product_id,title,content,sorting,create_time,create_user_id,update_time,update_user_id,status) values";

            if(!empty($params['team_product_return_receipt'])){
                for($i=0;$i<count($params['team_product_return_receipt']);$i++){
                    //团队产品ID
                    $team_product_id = $pk_id;
                    //标题
                    $title = $params['team_product_return_receipt'][$i]['title'];
                    //内容
                    $content = $params['team_product_return_receipt'][$i]['content'];
                    //排序
                    $sorting = $params['team_product_return_receipt'][$i]['sorting'];

                    $create_time = $t;
                    $create_user_id = $user_id;
                    $update_time = $t;
                    $update_user_id = $user_id;
                    // $status = $params['status'];
                    $status = 1;

                    if($i!=count($params['team_product_return_receipt'])-1){
                        $comma = ',';
                    }else{
                        $comma = '';
                    }
                    $return_receipt_values.="($team_product_id,'$title','$content',$sorting,$create_time,$create_user_id,$update_time,$update_user_id,$status)".$comma;

                }

                $this->execute($return_receipt_values);
            }

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
	 * 获取团队产品名称根据团队产品名称
	 */
    public function getTeamProduct($params){
    	$data= "1=1 ";
    	if(!empty($params['team_product_name'])){
    		$data.= " and team_product_name='".$params['team_product_name']."'";
    	}
    	if(!empty($params['team_product_number'])){
    		$data.= "and team_product_number='".$params['team_product_number']."'";
    	}
    	if(!empty($params['team_product_id'])){
    		$data.= " and team_product_id =".$params['team_product_id'];
    	}   
    	if(!empty($params['company_id'])){
    		$data.=" and company_id=".$params['company_id'];
    	}
    	
    	if(!empty($params['begin_time_day'])){
    		$data.=" and from_unixtime(begin_time,'%Y%m%d') = '".$params['begin_time_day']."'";
    	}
    	if(!empty($params['create_time'])){
    		$data.=" and from_unixtime(create_time,'%Y%m%d') = '".$params['create_time']."'";
    	}
    	if(!empty($params['begin_time_month'])){
    		$data.= " and from_unixtime(begin_time,'%Y%m') = '".$params['begin_time_month']."'";
    	}   	
    	if(!empty($params['plan_id'])){
    		$data.= " and plan_id = ".$params['plan_id'];
    	}
    	if(!empty($params['plur_status'])){
    		$data.= " and plur_status = ".$params['plur_status'];
    	}
    	if(is_numeric($params['status'])){
    		$data.= " and status= 1";
    	}
    	if(is_numeric($params['route_template_id'])){
    		$data.= " and route_template_id= ".$params['route_template_id'];
    	}   
    	$result =  $this->
 
    	where($data)->
    	field(['team_product_user_id,operational_log,plur_status,is_establish_team_product','settlement_type','team_product_id','team_product_name','company_id','create_user_id,team_product_number','begin_time'
    	])->select();
    
    	return $result;
    }
    
    /**
     * 获取团队产品(基础信息)
     * 韩
     */
    public function getTeamProductBase($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
    
        $data = "1=1  ";
		//and (find_in_set(".$params['user_company_id'].",use_company_id) or use_company_id ='*')
        //团队产品编号
        if($params['is_branch_product']==1 ){//分公司产品的查询
        	if(!empty($params['team_product_number'])){
        		$data.= " and team_product.team_product_number like '%".$params['team_product_number']."%'";
        	}
        	if(!empty($params['team_product_name'])){
        		$data.= " and team_product.team_product_name like '%".$params['team_product_name']."%'";
        	}
        	if(!empty($params['choose_company_id'])){
        		$data.= " and team_product.company_id ='".$params['choose_company_id']."'";
        	}
        }else{
        	if($params['is_like']==1){ //开启模糊查询
        	    if(!empty($params['team_product_number'])){
	        		$data.= " and team_product.team_product_number like '%".$params['team_product_number']."%'";
	        	}
	        	if(!empty($params['team_product_name'])){
	        		$data.= " and team_product.team_product_name like '%".$params['team_product_name']."%'";
	        	}
        	}else{
        		if(!empty($params['team_product_number'])){
        			$data.= " and team_product.team_product_number ='".$params['team_product_number']."'";
        		}
        		//团队产品名称
        		if(!empty($params['team_product_name'])){
        			$data.= " and team_product.team_product_name like '%".$params['team_product_name']."%'";
        		}
        	}

        	//隶属 分公司ID
        	if(is_numeric($params['company_id'])){
        	
        		 
        		$data.= " and team_product.company_id ='".$params['company_id']."'";
        	}
        	
        	
        	
        }

		/**
		 * 线路米板ID
		 */
        if(!empty($params['route_template_id'])){
        	$data.= " and team_product.route_template_id ='".$params['route_template_id']."'";
        }
        
        //线路类型ID
        if(!empty($params['route_type_id'])){
            $data.= " and team_product.route_type_id ='".$params['route_type_id']."'";
        }
        if($params['is_between_time']==1){//要开启时间范围内查询
        	//开团日期
        	if(!empty($params['begin_time'])){
        		$data.= " and team_product.begin_time >=".$params['begin_time'];
        	}
        	//结束日期
        	if(!empty($params['end_time'])){
        		$data.= " and team_product.begin_time <=".$params['end_time'];
        	}
        }else{
        	//开团日期
        	if(!empty($params['begin_time'])){
        		$data.= " and team_product.begin_time ='".$params['begin_time']."'";
        	}        	
        	
        }
        
		
        //团队产品ID
        if(!empty($params['team_product_id'])){
            $data.= " and team_product.team_product_id =".$params['team_product_id'];
        }

        //团队产品负责人
        if(!empty($params['team_product_user'])){
            $where['nickname'] = ['like',"%{$params['team_product_user']}%"];
            $user_id_ar = $this->table('user')->where($where)->field(['user_id'])->select();
            $ar = [];
            foreach ($user_id_ar as $key => $value) {
                array_push($ar, $value['user_id']);
            }
            $im = implode(',', $ar);
            $data .= " and team_product_user_id in ({$im})";
        }

        //团队状态
        if(is_numeric($params['plur_status'])){
            $data .= " and team_product.plur_status={$params['plur_status']}";     
        }
        //利润表审核
        if(is_numeric($params['is_profit_approve'])){
        	$data .= " and team_product.is_profit_approve={$params['is_profit_approve']}";
        }
        //地接保障
        if(is_numeric($params['dijie'])){
        	$data .= " and team_product.plur_status=<4";
        }

        
        //隶属 分公司ID
        if(is_numeric($params['can_watch_company_id'])){
        
        	 
       		$data.= " and( find_in_set('".$params['can_watch_company_id']."',use_company_id) or team_product.use_company_id = '*' )";
        	 	 
        	
        }

        //筛选条件  分公司ID
        if(is_numeric($params['choose_company_id'])){
            $data.= " and team_product.company_id =".$params['choose_company_id'];


        }
        
        

        //团队产品状态
      
        if(is_numeric($params['status'])){
            $data.= " and team_product.status = {$params['status']}";
        }

        //游客已确认
        if (is_numeric($params['customer_confirm']))
        {
            if ($params['customer_confirm'] == 1)
            {
                $data .= " and (select count(company_order_product_team.company_order_number) from company_order_product_team INNER JOIN company_order on company_order.company_order_number = company_order_product_team.company_order_number
where company_order_product_team.team_product_id = team_product.team_product_id and company_order.status = 1) > 0 and (select COUNT(*) from company_order_customer where company_order_number in (select company_order_number from company_order_product_team where company_order_product_team.team_product_id = team_product.team_product_id) and company_order_customer.status <> 3) < 1";
            }
            elseif ($params['customer_confirm'] == 2)
            {
                $data .= " and (select count(company_order_product_team.company_order_number) from company_order_product_team INNER JOIN company_order on company_order.company_order_number = company_order_product_team.company_order_number
where company_order_product_team.team_product_id = team_product.team_product_id and company_order.status = 1) > 0 and (select COUNT(*) from company_order_customer where company_order_number in (select company_order_number from company_order_product_team where company_order_product_team.team_product_id = team_product.team_product_id) and company_order_customer.status <> 3) > 0";
            }
        }

		//公司订单状态是否确认     
        if(is_numeric($params['company_order_status'])){
        	if($params['company_order_status']==1){
        		$data .=" and (( select count(company_order.company_order_status) from company_order where  company_order.status>0 and company_order.company_order_number in (select company_order_product_team.company_order_number from company_order_product_team where company_order_product_team.team_product_id=team_product.team_product_id) and company_order.company_order_status = ".$params['company_order_status'].") >0)";
        		        		
        	}else{
        		$data .=" and (( select count(company_order.company_order_status) from company_order where company_order.status >0 and company_order.company_order_number in (select company_order_product_team.company_order_number from company_order_product_team where company_order_product_team.team_product_id=team_product.team_product_id) and company_order.company_order_status = ".$params['company_order_status'].") =
( select count(company_order.company_order_status) from company_order where  company_order.status >0 and company_order.company_order_number in (select company_order_product_team.company_order_number from company_order_product_team where company_order_product_team.team_product_id=team_product.team_product_id) )
) and ( select count(company_order.company_order_status) from company_order where company_order.company_order_number in (select company_order_product_team.company_order_number from company_order_product_team where company_order_product_team.team_product_id=team_product.team_product_id) ) >0";
        		        		
        	}
        	
	
        }
      

        error_log(print_r($data,1));
       	$company_order_customer = new CompanyOrderCustomer();
        if($is_count==true){
            $result = $this->table("team_product")->alias("team_product")->where($data)->count();
            return $result;
            exit();
        }else {
            if ($is_page == true) {
                $result = $this->table("team_product")->alias('team_product')->
                join("company",'company.company_id = team_product.company_id')->
                join("route_type", 'route_type.route_type_id = team_product.route_type_id')->
                join("currency",'currency.currency_id= company.currency_id')->
                where($data)->limit($page, $page_size)->
                field(['team_product.*',
                	'currency.currency_id','currency.currency_name','company.company_name','route_type.route_type_name',
                    "(select nickname from user where  user.user_id = team_product.team_product_user_id)" => 'team_product_user_name',
                    "(select nickname  from user where user.user_id = team_product.create_user_id)"       => 'create_user_name',
                    "(select nickname  from user where user.user_id = team_product.update_user_id)"       => 'update_user_name',
                	"(select route_number  from route_template where route_template.route_template_id= team_product.route_template_id)"       => 'route_number',
                ])->order('begin_time desc')->select();
                
              
            }else{
                $result = $this->table("team_product")->alias('team_product')->
                join("company",'company.company_id = team_product.company_id')->
                join("route_type", 'route_type.route_type_id = team_product.route_type_id')->
                join("currency",'currency.currency_id= company.currency_id')->
           
                where($data)->
                field(['team_product.*',
                	'currency.currency_id','currency.currency_name','company.company_name',	'route_type.route_type_name',
                    "(select nickname from user where  user.user_id = team_product.team_product_user_id)" => 'team_product_user_name',
                    "(select nickname  from user where user.user_id = team_product.create_user_id)"       => 'create_user_name',
                    "(select nickname  from user where user.user_id = team_product.update_user_id)"       => 'update_user_name',
                	"(select route_number  from route_template where route_template.route_template_id= team_product.route_template_id)"       => 'route_number',
                		
                ])->order('begin_time desc')->select();
            }
        }
        $company_order = new CompanyOrder();
        $company_order_product_team = new CompanyOrderProductTeam();
        $company_order_customer = new CompanyOrderCustomer();
		//通过团队产品ID获取 收客人数
		$company_customer_count = 0;
		
		
		
        for($k=0;$k<count($result);$k++){
        	$stance= 0;
        	$company_customer_count=0;
			//通过团队产品编号查询订单号
			$company_order_product_team_params = [
				'team_product_id'=>$result[$k]['team_product_id'],
				'status'=>1	
			];
			

			$company_order_product_team_result = $company_order_product_team->getCompanyOrderProductTeam($company_order_product_team_params);

			//exit();
        	for($jj=0;$jj<count($company_order_product_team_result);$jj++){

        		
        		$company_order_params = [
        			'company_order_number'=>$company_order_product_team_result[$jj]['company_order_number']
        		];
        		
        		
        		$company_order_result = $company_order->getCompanyOrder($company_order_params);
        		//拿到编号再去查询游客数量
        		$company_order_customer_params = [
        				'company_order_number'=>$company_order_product_team_result[$jj]['company_order_number'],
        		
        		];
        		
        		
        		if($company_order_result[0]['company_order_status']<3 && $company_order_result[0]['status'] >0){
        		
        			$customer_count = count($company_order_customer->getCompanyOrderCustomer($company_order_customer_params));
        			
        		
        			if($company_order_result[0]['company_id'] == $params['user_company_id']){//代表订单属于自己公司 开始算游客数量
        			
        				$company_customer_count+=$customer_count;
        			}
        			
        			
        			$stance+=$customer_count;        			
        			
        		}
        		

        	}
        	

        	
        	$result[$k]['stance'] = $stance;
        	$result[$k]['company_customer_count'] = $company_customer_count;
    
        }
        
        foreach($result as $key=>$val) {
            //读取相关天数
            $team_product_id = $val['team_product_id'];
            $source_count = Db::table('team_product_journey')->where(array("team_product_id" => $team_product_id))->select();
            $base_count_result = count($source_count);
            if ($base_count_result > 0) {
                $result[$key]['base_count'] = $base_count_result;
            }else{
            	$result[$key]['base_count'] = 0;
            }


            //获取成本价格
            $team_product_id = $val['team_product_id'];
            $source_cb_cost = Db::table('team_product_allocation')->where(" supplier_type_id !=11 and status=1 and team_product_id = $team_product_id")->select();

            $cost_all = 0;
            foreach($source_cb_cost as $key_cb => $val_ck){
                $cost_all+= $val_ck['source_price']*$val_ck['source_count'];
            }
            $result[$key]['cost_all'] = $cost_all;

            //读取资源信息
            $source = Db::table('team_product_allocation')->where(array("team_product_id" => $team_product_id,'status'=>1))->select();
            $source_array = [];
            $supplier_type_id = [];
            foreach ($source as $key_s => $val_s) {
                if ($val_s['supplier_type_id'] == 2) {
                    array_push($source_array, "酒店");
                } else if ($val_s['supplier_type_id'] == 3) {
                    array_push($source_array, "用餐");
                } else if ($val_s['supplier_type_id'] == 4) {
                    array_push($source_array, "航班");
                } else if ($val_s['supplier_type_id'] == 5) {
                    array_push($source_array, "邮轮");
                } else if ($val_s['supplier_type_id'] == 6) {
                    array_push($source_array, "签证");
                } else if ($val_s['supplier_type_id'] == 7) {
                    array_push($source_array, "景点");
                } else if ($val_s['supplier_type_id'] == 8) {
                    array_push($source_array, "车辆");
                } else if ($val_s['supplier_type_id'] == 9) {
                    array_push($source_array, "导游");
                } else if ($val_s['supplier_type_id'] == 10) {
                    array_push($source_array, "单向资源");
                } else if ($val_s['supplier_type_id'] == 10) {
                    array_push($source_array, "单向资源");
                } else if ($val_s['supplier_type_id'] == 11) {
                    array_push($source_array, "自费项目");
                }
                array_push($supplier_type_id, $val_s['supplier_type_id']);
            }
            $result[$key]['source'] = implode(",", array_unique($source_array));
            $result[$key]['supplier_type_id'] = array_unique($supplier_type_id);

            //读取线路模板
            $route_template_id = $val['route_template_id'];
            $route_template_id_result = Db::table('route_template')->where(array("route_template_id" => $route_template_id))->select();
            if (!empty($route_template_id_result[0]['route_name'])) {
                $result[$key]['route_template_name'] = $route_template_id_result[0]['route_name'];
            } else {
                $result[$key]['route_template_name'] = "";
            }

            //获取已收客人数
            //先获取当前团队产品下的订单
            $team_product_number = $val['team_product_number'];
            $team_product_id = $val['team_product_id'];
            //$stance=Db::query("select *  from company_order_customer where company_order_number = (SELECT company_order_number FROM `company_order_product_team` where team_product_id = {$team_product_id})");

            $company_order_customer_params = [
           	'team_product_id'=>$team_product_id
           ];
			//$stance = $company_order_customer->getCompanyOrderCustomerByTeamProductId($company_order_customer_params);
           // $result[$key]['stance']=count($stance);

            //读取创建人分公司信息
            $create_user_id = $val['create_user_id'];
//             $user_result = Db::table('user')->where(array("user_id" => $create_user_id))->find();
//             $job_id = $user_result[0]['job_id'];
//             $job_result = Db::table('job')->where(array("job_id" => $job_id))->find();
//             $department_id = $job_result[0]['department_id'];
//             $department_result = Db::table('department')->where(array("department_id" => $department_id))->find();
//             $company_id = $department_result[0]['company_id'];
//             $company_result = Db::table('company')->where(array("company_id" => $result[$key]['company_id']))->find();
            


//             if(!empty($company_result[0]['company_name'])){
//                 $result[$key]['company_name'] = $company_result[0]['company_name'];
//             }else{
//                 $result[$key]['company_name'] = "";
//             }

            //自费项目
            $own_expense_source = Db::table('team_product_allocation')->alias('tpa')->join('own_expense oe','tpa.source_id = oe.own_expense_id')
            ->join("supplier s ",'s.supplier_id = oe.supplier_id')->where("tpa.status = 1 and tpa.supplier_type_id =11 and tpa.team_product_id = ".$team_product_id)
            ->join("currency c",'tpa.payment_currency_id = c.currency_id')->where("tpa.status =1")->
            field("s.company_id,s.supplier_name,oe.source_number,oe.own_expense_name,tpa.source_price*tpa.source_count as cost_all,tpa.source_total_price,tpa.supplier_type_id,tpa.source_id,tpa.payment_currency_id,c.currency_name")->
            select();

            $result[$key]['own_expens_source_array'] = $own_expense_source;

            $currency_name = Db::table("currency")->where(array("currency_id"=>$own_expense_source[$key]['payment_currency_id']))->select();

           
            //报价 1、一口价 2、真实结算
            if($val['settlement_type']==1){
                if(is_numeric($params['can_watch_company_id'])){
                					
                	
                	$once_price_params = "status = 1  and team_product_id = $team_product_id and company_id = ".$params['can_watch_company_id'];
                	 

                }else{
                	$once_price_params = "status = 1  and team_product_id = $team_product_id and company_id = 0";

                }
              	
            	$once_price_result = Db::table('team_product_once_price')->where($once_price_params)->select();
       			if($once_price_result[0]['team_price_currency_id']!=$result[$key]['currency_id']){
       				$proportion = $this->_proportion_service->getProportion($once_price_result[0]['team_price_currency_id'],$result[$key]['currency_id']);
       				$once_price_result[0]['total_price'] = number_format($once_price_result[0]['total_price']*$proportion,2);
       			}
       			
       			
                if (!empty($once_price_result[0]['total_price'])) {
                    $result[$key]['once_price'] = $once_price_result[0]['total_price'];

                } else {
                    $result[$key]['once_price'] = 0;
                }

                
            }else if($val['settlement_type']==2){
                $num=0;
                $real_price_result = Db::table('team_product_allocation')->where("status = 1 and supplier_type_id !=11 and team_product_id =  $team_product_id")->select();
                foreach($real_price_result as $key_r=>$val_r){
                	if($val_r['payment_currency_id']!=$result[$key]['currency_id']){
                		$proportion = $this->_proportion_service->getProportion($val_r['payment_currency_id'],$result[$key]['currency_id']);
                		$k_price = number_format($val_r['source_total_price']*$proportion,2);
                	}else{
                		$k_price = $val_r['source_total_price'];
                	}
                	
                    $num+=$k_price;
                }
                //获取创建公司的货币ID
                $company_id = $result[$key]['company_id'];
                
                $result[$key]['real_price'] = $num;
                


            }

        }

        return $result;
    }

    /**
     * 编辑团队产品
     * 韩
     */
    public function updateTeamProductByTeamProductId($params){
        $t = time();
        $public_service = new PublicService();
        $this->startTrans();
        try {
            //基本信息
            //团名
            if (!empty($params['team_product_name'])) {
                $data['team_product_name'] = $params['team_product_name']; 
            }

            //线路模板ID
            if (!empty($params['route_template_id'])) {
                $data['route_template_id'] = $params['route_template_id']; 
            }

            //路线类型ID
            if (!empty($params['route_type_id'])) {
                $data['route_type_id'] = $params['route_type_id'];

            }

            //可见分公司
            if (!empty($params['use_company_id'])) {
                $data['use_company_id'] = trim($params['use_company_id'], ',');

            }

            //结算方式
            if (!empty($params['settlement_type'])) {
                $data['settlement_type'] = $params['settlement_type'];

            }

            //团队产品负责人ID
            if (!empty($params['team_product_user_id'])) {
                $data['team_product_user_id'] = $params['team_product_user_id'];

            }


            //计划收客人数
            if (!empty($params['plan_custom_number'])) {
                $data['plan_custom_number'] = $params['plan_custom_number'];

            }

            //出团前几天截至收客
            if (!empty($params['before_days'])) {
                $data['before_days'] = $params['before_days'];

            }

            //开团时间
            if (!empty($params['begin_time'])) {
                $data['begin_time'] = $params['begin_time'];
                //$data['begin_time'] = Help::changeTimeZone($params['begin_time'],'',$params['time_zone'],'Asia/Shanghai');
            }

//            if (!empty($params['time_zone'])) {
//                $data['time_zone'] = $params['time_zone'];
//            }

            if (is_numeric($params['status'])) {
                $data['status'] = $params['status']; 
            }

            $data['update_user_id'] = $params['user_id'];
            $data['update_time'] = $t;
            $team_product_params = [
            		'team_product_id'=>$params['team_product_id']
            ];
            $team_product_result = $this->getTeamProduct($team_product_params);
        
	        

            
           // if(date('Ymd',$team_product_result[0]['begin_time']) != date("Ymd",$params['begin_time'])){
            	//团队产品的命名规则
            	$route_type_code = $public_service->getRouteTypeRecursion($data['route_type_id']);
            	//再获取有几个团队产品
            	$team_product_params_two = [
            			'begin_time_day'=>date("Ymd",$data['begin_time'])
            	];
            	$team_product_result = $this->getTeamProduct($team_product_params_two);
            	$team_product_code = count($team_product_result)+1;            	
            	$team_product_code = str_pad($team_product_code,3,'0',STR_PAD_LEFT );            	             	 
            	$team_product_params = [
            			'team_product_id'=> $params['team_product_id'],
            			'team_product_number'=>$route_type_code.'-'.date('y',$data['begin_time']).'-'.date('md',$data['begin_time']).$team_product_code
            	];           	
            	$this->updateTeamProductBaseByTeamProductBaseId($team_product_params);
           // }
            
           $data['operational_log'] = $params['operational_log'];     

            $this->table('team_product')->where("team_product_id = " . $params['team_product_id'])->update($data);
            //航班
            //修改航班状态
            $this->table('team_product_flight')->where(array('team_product_id' => $params['team_product_id']))->update(['status' => 0]);

            if (!empty($params['edit_flight'])) {
                //修改航班信息
                for ($i = 0; $i < count($params['edit_flight']); $i++) {

                    //第几天
                    if (isset($params['edit_flight'][$i]['the_days'])) {
                        $data[$i]['the_days'] = $params['edit_flight'][$i]['the_days'];
                    }

                    //出发地
                    if (isset($params['edit_flight'][$i]['start_city'])) {
                        $data[$i]['start_city'] = $params['edit_flight'][$i]['start_city'];
                    }

                    //目的地
                    if (isset($params['edit_flight'][$i]['end_city'])) {
                        $data[$i]['end_city'] = $params['edit_flight'][$i]['end_city'];
                    }

                    //出发时间
                    if (isset($params['edit_flight'][$i]['start_time'])) {
                        $data[$i]['start_time'] = $params['edit_flight'][$i]['start_time'];
                    }

                    //到达时间
                    if (isset($params['edit_flight'][$i]['end_time'])) {
                        $data[$i]['end_time'] = $params['edit_flight'][$i]['end_time'];
                    }

                    //航班号
                    if (isset($params['edit_flight'][$i]['flight_number'])) {
                        $data[$i]['flight_number'] = $params['edit_flight'][$i]['flight_number'];
                    }

                    //接送机
                    if (isset($params['edit_flight'][$i]['flight_type'])) {
                        $data[$i]['flight_type'] = $params['edit_flight'][$i]['flight_type'];
                    }

                    if (isset($params['edit_flight'][$i]['status'])) {
                        $data[$i]['status'] = $params['edit_flight'][$i]['status'];
                    }

                    $data[$i]['update_user_id'] = $params['user_id'];
                    $data[$i]['update_time'] = $t;
					
					$t_data['team_product_flight_id'] = $params['edit_flight'][$i]['team_product_flight_id'];
                    $this->table('team_product_flight')->where($t_data)->update($data[$i]);
                   
                }
            }


            $user_id = $params['user_id'];

            //添加航班信息
            $flight_values = "insert into team_product_flight (team_product_id,the_days,start_city,end_city,start_time,end_time,flight_number,flight_type,create_time,create_user_id,update_time,update_user_id,status) values";

            if (!empty($params['add_flight'])) {
                for ($i = 0; $i < count($params['add_flight']); $i++) {
                    //团队产品ID
                    $team_product_id = $params['add_flight'][$i]['team_product_id'];
                    //第几天
                    $the_days = $params['add_flight'][$i]['the_days'];
                    //出发地
                    $start_city = $params['add_flight'][$i]['start_city'];
                    //目的地
                    $end_city = $params['add_flight'][$i]['end_city'];
                    //出发时间
                    $start_time = $params['add_flight'][$i]['start_time'];
                    //到达时间
                    $end_time = $params['add_flight'][$i]['end_time'];
                    //航班编号
                    $flight_number = $params['add_flight'][$i]['flight_number'];
                    //接送机
                    $flight_type = $params['add_flight'][$i]['flight_type'];

                    $create_time = $t;
                    $create_user_id = $user_id;
                    $update_time = $t;
                    $update_user_id = $user_id;
                    $status = 1;

                    if ($i != count($params['add_flight']) - 1) {
                        $comma = ',';
                    } else {
                        $comma = '';
                    }

                    $flight_values .= "($team_product_id,$the_days,'$start_city','$end_city',$start_time,$end_time,'$flight_number',$flight_type,$create_time,$create_user_id,$update_time,$update_user_id,$status)" . $comma;
                }

                $this->execute($flight_values);

            }

            //修改回执单模版状态
            $this->table('team_product_return_receipt')->where(array('team_product_id' => $params['team_product_id']))->update(['status' => 0]);
	
            if (!empty($params['edit_return_receipt'])) {
                //修改回执单模版
                for ($i = 0; $i < count($params['edit_return_receipt']); $i++) {
                    //标题
                    if (isset($params['edit_return_receipt'][$i]['title'])) {
                        $data_return_receipt[$i]['title'] = $params['edit_return_receipt'][$i]['title'];

                    }

                    //内容
                    if (isset($params['edit_return_receipt'][$i]['content'])) {
                        $data_return_receipt[$i]['content'] = $params['edit_return_receipt'][$i]['content'];

                    }

                    //排序
                    if (isset($params['edit_return_receipt'][$i]['sorting'])) {
                        $data_return_receipt[$i]['sorting'] = $params['edit_return_receipt'][$i]['sorting'];

                    }

                    if (isset($params['edit_return_receipt'][$i]['status'])) {
                        $data_return_receipt[$i]['status'] = $params['edit_return_receipt'][$i]['status'];

                    }

                    $data_return_receipt[$i]['update_user_id'] = $params['user_id'];
                    $data_return_receipt[$i]['update_time'] = $t;

                    $this->table('team_product_return_receipt')->where(array('route_return_receipt_id' => $params['edit_return_receipt'][$i]['route_return_receipt_id']))->update($data_return_receipt[$i]);

                }
            }

            $t = time();
            $user_id = $params['user_id'];

            //添加回执单模版
            $return_receipt_values = "insert into team_product_return_receipt (team_product_id,title,content,sorting,create_time,create_user_id,update_time,update_user_id,status) values";

            if (!empty($params['add_return_receipt'])){
                for ($i = 0; $i < count($params['add_return_receipt']); $i++){
                    //团队产品ID
                    $team_product_id = $params['add_return_receipt'][$i]['team_product_id'];
                    //标题
                    $title = $params['add_return_receipt'][$i]['title'];
                    //内容
                    $content = $params['add_return_receipt'][$i]['content'];
                    //排序
                    $sorting = $params['add_return_receipt'][$i]['sorting'];

                    $create_time = $t;
                    $create_user_id = $user_id;
                    $update_time = $t;
                    $update_user_id = $user_id;
                    $status = 1;

                    if ($i != count($params['add_return_receipt']) - 1) {
                        $comma = ',';
                    } else {
                        $comma = '';
                    }
                    $return_receipt_values .= "($team_product_id,'$title','$content',$sorting,$create_time,$create_user_id,$update_time,$update_user_id,$status)" . $comma;

                }

                $this->execute($return_receipt_values);
            }

            //修改行程状态
            $this->table('team_product_journey')->where(array('team_product_id'=>$params['team_product_id']))->update(['status'=>0]);

            //修改行程信息
            if(!empty($params['edit_journey'])){
                for ($i = 0; $i < count($params['edit_journey']); $i++) {
                    //第几天
                    if (isset($params['edit_journey'][$i]['the_days'])) {
                        $data_journey[$i]['the_days'] = $params['edit_journey'][$i]['the_days'];

                    }

                    //行程标题
                    if (isset($params['edit_journey'][$i]['route_journey_title'])) {
                        $data_journey[$i]['route_journey_title'] = $params['edit_journey'][$i]['route_journey_title'];

                    }

                    //行程内容
                    if (isset($params['edit_journey'][$i]['route_journey_content'])) {
                        $data_journey[$i]['route_journey_content'] = $params['edit_journey'][$i]['route_journey_content'];

                    }

                    //交通
                    if (isset($params['edit_journey'][$i]['route_journey_traffic'])) {
                        $data_journey[$i]['route_journey_traffic'] = $params['edit_journey'][$i]['route_journey_traffic'];

                    }

                    //航班号
                    if (isset($params['edit_journey'][$i]['flight_number'])) {
                        $data_journey[$i]['flight_number'] = $params['edit_journey'][$i]['flight_number'];

                    }

                    //住宿
                    if (isset($params['edit_journey'][$i]['route_journey_stay'])) {
                        $data_journey[$i]['route_journey_stay'] = $params['edit_journey'][$i]['route_journey_stay'];

                    }

                    //吃饭标注
                    if (isset($params['edit_journey'][$i]['eat_mark'])) {
                        $data_journey[$i]['eat_mark'] = $params['edit_journey'][$i]['eat_mark'];

                    }

                    //早餐
                    if (isset($params['edit_journey'][$i]['route_journey_breakfast'])) {
                        $data_journey[$i]['route_journey_breakfast'] = $params['edit_journey'][$i]['route_journey_breakfast'];

                    }

                    //午餐
                    if (isset($params['edit_journey'][$i]['route_journey_lunch'])) {
                        $data_journey[$i]['route_journey_lunch'] = $params['edit_journey'][$i]['route_journey_lunch'];

                    }

                    //晚餐
                    if (isset($params['edit_journey'][$i]['route_journey_dinner'])) {
                        $data_journey[$i]['route_journey_dinner'] = $params['edit_journey'][$i]['route_journey_dinner'];

                    }

                    //景点
                    if (isset($params['edit_journey'][$i]['route_journey_scenic_sport'])) {
                        $data_journey[$i]['route_journey_scenic_sport'] = $params['edit_journey'][$i]['route_journey_scenic_sport'];

                    }

                    //图片
                    if (isset($params['edit_journey'][$i]['route_journey_picture'])) {
                        $data_journey[$i]['route_journey_picture'] = $params['edit_journey'][$i]['route_journey_picture'];

                    }

                    //备注
                    if (isset($params['edit_journey'][$i]['route_journey_remark'])) {
                        $data_journey[$i]['route_journey_remark'] = $params['edit_journey'][$i]['route_journey_remark'];

                    }


                    if(is_numeric($params['edit_journey'][$i]['route_journey_zone'])){
                  		$data_journey[$i]['route_journey_zone'] = $params['edit_journey'][$i]['route_journey_zone'];
                  	}else{
                  		$route_journey_zone = '';
                  	}
                  	
                  	
                    if (isset($params['edit_journey'][$i]['status'])) {
                        $data_journey[$i]['status'] = $params['edit_journey'][$i]['status'];

                    }

                    $data_journey[$i]['update_user_id'] = $params['user_id'];
                    $data_journey[$i]['update_time'] = $t;

                    $this->table('team_product_journey')->where(array('team_product_journey_id' => $params['edit_journey'][$i]['team_product_journey_id']))->update($data_journey[$i]);
                }
            }

            $t = time();
            $user_id = $params['user_id'];

            //添加行程内容
            $journey_values="insert into team_product_journey (team_product_id,the_days,route_journey_title,route_journey_content,route_journey_traffic,route_journey_stay,eat_mark,route_journey_breakfast,route_journey_lunch,route_journey_dinner,route_journey_scenic_sport,route_journey_picture,route_journey_remark,route_journey_zone,create_time,create_user_id,update_time,update_user_id,status) values";

            if(!empty($params['add_journey'])){
                for ($i = 0; $i < count($params['add_journey']); $i++) {
                    //团队产品ID
                    $team_product_id = $params['add_journey'][$i]['team_product_id'];
                    //第几天
                    $the_days = $params['add_journey'][$i]['the_days'];
                    //行程标题
                    $route_journey_title = $params['add_journey'][$i]['route_journey_title'];
                    //行程内容
                    $route_journey_content = $params['add_journey'][$i]['route_journey_content'];
                    //交通
                    $route_journey_traffic = $params['add_journey'][$i]['route_journey_traffic'];
                    //住宿
                    $route_journey_stay = $params['add_journey'][$i]['route_journey_stay'];
                    //吃饭标注
                    $eat_mark = $params['add_journey'][$i]['eat_mark'];
                    //早餐
                    $route_journey_breakfast = $params['add_journey'][$i]['route_journey_breakfast'];
                    //午餐
                    $route_journey_lunch = $params['add_journey'][$i]['route_journey_lunch'];
                    //晚餐
                    $route_journey_dinner = $params['add_journey'][$i]['route_journey_dinner'];
                    //景点
                    $route_journey_scenic_sport = $params['add_journey'][$i]['route_journey_scenic_sport'];
                    //图片
                    $route_journey_picture = $params['add_journey'][$i]['route_journey_picture'];
                    //备注
                    $route_journey_remark = $params['add_journey'][$i]['route_journey_remark'];
                    //地区
                  	if(is_numeric($params['add_journey'][$i]['route_journey_zone'])){
                  		$route_journey_zone = $params['add_journey'][$i]['route_journey_zone'];
                  	}else{
                  		$route_journey_zone = '';
                  	}
                    

                    $create_time = $t;
                    $create_user_id = $user_id;
                    $update_time = $t;
                    $update_user_id = $user_id;
                    $status = 1;

                    if ($i != count($params['add_journey']) - 1) {
                        $comma = ',';
                    } else {
                        $comma = '';
                    }

                    $journey_values .= "($team_product_id,$the_days,'$route_journey_title','$route_journey_content','$route_journey_traffic','$route_journey_stay','$eat_mark','$route_journey_breakfast','$route_journey_lunch','$route_journey_dinner','$route_journey_scenic_sport','$route_journey_picture','$route_journey_remark','$route_journey_zone',$create_time,$create_user_id,$update_time,$update_user_id,$status)" . $comma;

                }

                    $this->execute($journey_values);
            }

            //修改资源状态
            $this->table('team_product_allocation')->where(array('team_product_id'=>$params['team_product_id']))->update(['status'=>0]);

            if(!empty($params['edit_allocation'])){
                //修改资源信息
                for ($i = 0; $i < count($params['edit_allocation']); $i++) {
                    //资源类型ID
                    if (isset($params['edit_allocation'][$i]['supplier_type_id'])) {
                        $data_allocation[$i]['supplier_type_id'] = $params['edit_allocation'][$i]['supplier_type_id'];

                    }

                    //对应资源ID
                    if (isset($params['edit_allocation'][$i]['source_id'])) {
                        $data_allocation[$i]['source_id'] = $params['edit_allocation'][$i]['source_id'];

                    }

                    //币种
                    if (isset($params['edit_allocation'][$i]['payment_currency_id'])) {
                        $data_allocation[$i]['payment_currency_id'] = $params['edit_allocation'][$i]['payment_currency_id'];

                    }

                    //单价
                    if (isset($params['edit_allocation'][$i]['source_price'])) {
                        $data_allocation[$i]['source_price'] = $params['edit_allocation'][$i]['source_price'];

                    }

                    //数量
                    if (isset($params['edit_allocation'][$i]['source_count'])) {
                        $data_allocation[$i]['source_count'] = $params['edit_allocation'][$i]['source_count'];

                    }

                    //总价
                    if (isset($params['edit_allocation'][$i]['source_total_price'])) {
                        $data_allocation[$i]['source_total_price'] = $params['edit_allocation'][$i]['source_total_price'];

                    }

                    //第几天
                    if (isset($params['edit_allocation'][$i]['source_the_days'])) {
                        $data_allocation[$i]['source_the_days'] = $params['edit_allocation'][$i]['source_the_days'];

                    }

                    if (isset($params['edit_allocation'][$i]['status'])) {
                        $data_allocation[$i]['status'] = $params['edit_allocation'][$i]['status'];

                    }

                    $data_allocation[$i]['update_user_id'] = $params['user_id'];
                    $data_allocation[$i]['update_time'] = $t;

                    $this->table('team_product_allocation')->where(array('team_product_allocation_id' => $params['edit_allocation'][$i]['team_product_allocation_id']))->update($data_allocation[$i]);
                }
            }

            $t = time();
            $user_id = $params['user_id'];

            //添加资源信息
            $allocation_values="insert into team_product_allocation (team_product_id,supplier_type_id,source_id,payment_currency_id,source_price,source_count,source_total_price,source_the_days,create_time,create_user_id,update_time,update_user_id,status) values";

            if(!empty($params['add_allocation'])){
                for ($i = 0; $i < count($params['add_allocation']); $i++) {
                    //团队产品ID
                    $team_product_id = $params['add_allocation'][$i]['team_product_id'];
                    //资源类型ID
                    $supplier_type_id = $params['add_allocation'][$i]['supplier_type_id'];
                    //对应资源ID
                    $source_id = $params['add_allocation'][$i]['source_id'];
                    //币种
                    $payment_currency_id = $params['add_allocation'][$i]['payment_currency_id'];
                    //单价
                    $source_price = $params['add_allocation'][$i]['source_price'];
                    //数量
                    $source_count = $params['add_allocation'][$i]['source_count'];
                    //总价
                    $source_total_price = $params['add_allocation'][$i]['source_total_price'];
                    //第几天
                    $source_the_days = $params['add_allocation'][$i]['source_the_days'];

                    $create_time = $t;
                    $create_user_id = $user_id;
                    $update_time = $t;
                    $update_user_id = $user_id;
                    $status = 1;

                    if ($i != count($params['add_allocation']) - 1) {
                        $comma = ',';
                    } else {
                        $comma = '';
                    }
                    $allocation_values .= "($team_product_id,$supplier_type_id,$source_id,$payment_currency_id,$source_price,$source_count,$source_total_price,$source_the_days,$create_time,$create_user_id,$update_time,$update_user_id,$status)" . $comma;

                }

                $this->execute($allocation_values);
            }

            //一口价
            //修改一口价状态
            $this->table('team_product_once_price')->where(array('team_product_id'=>$params['team_product_id']))->update(['status'=>0]);
			
            if(!empty($params['edit_once_price'])) {
                for ($i = 0; $i <count($params['edit_once_price']); $i++) {
                    if (is_numeric($params['edit_once_price'][$i]['company_id'])) {
                        $data_once_price[$i]['company_id'] = $params['edit_once_price'][$i]['company_id'];

                    }

                    if (!empty($params['edit_once_price'][$i]['total_price'])) {
                        $data_once_price[$i]['total_price'] = $params['edit_once_price'][$i]['total_price'];

                    }
                    //if (!empty($params['edit_once_price'][$i]['team_price_currency_id'])) {
                    	$data_once_price[$i]['team_price_currency_id'] = $params['edit_once_price'][$i]['team_price_currency_id'];
                    
                    // }
                    if (is_numeric($params['edit_once_price'][$i]['status'])) {
                        $data_once_price[$i]['status'] = $params['edit_once_price'][$i]['status'];

                    }

                    $data_once_price[$i]['update_user_id'] = $params['user_id'];
                    $data_once_price[$i]['update_time'] = $t;
					
                    $this->table('team_product_once_price')->where("team_product_once_price_id = ".$params['edit_once_price'][$i]['team_product_once_price_id'][0])->update($data_once_price[$i]);
                }
            }

            //添加一口价
            $once_price_values="insert into team_product_once_price (team_product_id,company_id,total_price,create_time,create_user_id,update_time,update_user_id,status) values";

            if(!empty($params['add_once_price'])){
                for ($i = 0; $i < count($params['add_once_price']); $i++) {
                    //团队产品ID
                    $team_product_id = $params['add_once_price'][$i]['team_product_id'];
                    //可见分公司ID
                    $company_id = $params['add_once_price'][$i]['company_id'];
                    //总价
                    $total_price = $params['add_once_price'][$i]['total_price'];

                    $create_time = $t;
                    $create_user_id = $user_id;
                    $update_time = $t;
                    $update_user_id = $user_id;
                    $status = $params['status'];

                    if ($i != count($params['add_once_price']) - 1) {
                        $comma = ',';
                    } else {
                        $comma = '';
                    }
                    $once_price_values .= "($team_product_id,$company_id,$total_price,$create_time,$create_user_id,$update_time,$update_user_id,$status)" . $comma;

                }

                $this->execute($once_price_values);
            }

            $result = 1;
            // 提交事务
            $this->commit();

            //获取团队状态
            $plur_status =  $this->table('team_product')->where("team_product_id = " . $params['team_product_id'])->find()['plur_status'];
            if($plur_status == 0){
                $up['team_product_id'] = $params['team_product_id'];
                $CompanyOrderService = new CompanyOrderService();
                $CompanyOrderService->CompanyOrderInfoChangeByTeamProduct($up);
            }

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            $this->rollback();

        }
        return $result;
    }

    /**
     * 修改团队产品(基础配置)
     * 韩
     */
    public function updateTeamProductBaseByTeamProductBaseId($params){
    
    	$t = time();

    	//团号
    	if(!empty($params['team_product_number'])){
    		$data['team_product_number'] = $params['team_product_number'];
    	
    	}
    	//团名
    	if(!empty($params['team_product_name'])){
    		$data['team_product_name'] = $params['team_product_name'];

    	}

        //线路模板ID
        if(!empty($params['route_template_id'])){
            $data['route_template_id'] = $params['route_template_id'];

        }

    	//路线类型ID
    	if(!empty($params['route_type_id'])){
    		$data['route_type_id'] = $params['route_type_id'];

    	}

    	//可见分公司
    	if(!empty($params['use_company_id'])){
    		$data['use_company_id'] = trim($params['use_company_id'],',');

    	}

    	//结算方式
    	if(!empty($params['settlement_type'])){
    		$data['settlement_type'] = $params['settlement_type'];

    	}

    	//团队产品负责人ID
    	if(!empty($params['team_product_user_id'])){
    		$data['team_product_user_id'] = $params['team_product_user_id'];

    	}

    	//团队产品类型
    	if(!empty($params['team_product_type'])){
    		$data['team_product_type'] = $params['team_product_type'];

    	}

	    //计划收客人数
	    if(!empty($params['plan_custom_number'])){
	    	$data['plan_custom_number'] = $params['plan_custom_number'];

	    }
	    if(is_numeric($params['is_profit_approve'])){
	    	$data['is_profit_approve'] = $params['is_profit_approve'];
	    
	    }
	    //出团前几天截至收客
    	if(!empty($params['before_days'])){
    		$data['before_days'] = $params['before_days'];

    	}

    	//开团时间
        if(!empty($params['begin_time'])){
            $data['begin_time'] = $params['begin_time'];

        }
        //成团操作
        if(!empty($params['is_establish_team_product'])){
        	$data['is_establish_team_product'] = $params['is_establish_team_product'];
        
        }
    	if(!empty($params['status'])){
    		$data['status'] = $params['status'];

    	}

    	$data['update_user_id'] = $params['now_user_id'];
    	$data['update_time'] = $t;

        $this->startTrans();
    	try{
    		$this->table('team_product')->where("team_product_id = ".$params['team_product_id'])->update($data);

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
     * 获取团队产品-导游回执单(废弃)
     * 韩
     */
    public function getTeamProductGuideReceipt($params){
        $data = "1=1 ";

        //团队产品编号
        if(!empty($params['team_product_number'])){
            $data .= " and team_product.team_product_number = '".$params['team_product_number']."'";
        }

        $result =  $this->table("team_product")->alias('team_product')->
        where($data)->
        field(['team_product.team_product_id','team_product.team_product_number','team_product.team_product_name',
            'team_product.begin_time','team_product.route_type_id',
            "(select nickname from user where  user.user_id = team_product.team_product_user_id)"=>'team_product_user_name',
            "(select nickname  from user where user.user_id = team_product.create_user_id)"=>'create_user_name',
            "(select nickname  from user where user.user_id = team_product.update_user_id)"=>'update_user_name',

        ])->select();

        //获取团队产品基本信息
        $team_product_number = $result[0]['team_product_number'];
        $result[0]['begin_time'] = date('Y-m-d',$result[0]['begin_time']);

        //团队产品行程
        $team_product_journey = Db::query("SELECT a.team_product_id, a.team_product_number, a.begin_time, b.route_journey_title, b.the_days, 
b.route_journey_scenic_sport, b.eat_mark, b.route_journey_breakfast, b.route_journey_lunch, 
b.route_journey_dinner, b.route_journey_stay, b.route_journey_remark
from team_product a, team_product_journey b
where a.team_product_id=b.team_product_id and a.team_product_number='{$team_product_number}'
order by b.the_days");

        for($j=0;$j<count($team_product_journey);$j++){

            $team_product_journey[$j]['begin_time']=date('Y-m-d',strtotime("{$result[0]['begin_time']} +".($team_product_journey[$j]['the_days']-1)." day"));

            $result[0]['team_product_journey'] = $team_product_journey;

//            $result[0]['begin_time'] = date('Y-m-d',$team_product_journey[0]['begin_time']);
//            $result[0]['begin_time'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} +1 day"));
        }

        //游客信息
        $order_customers = Db::query("select dd.customer_id, dd.team_customer_number, gg.check_in_hotel, gg.check_on_hotel, hh.customer_type, hh.customer_first_name, hh.customer_last_name, hh.gender, ii.country_name, hh.card_type, hh.card_number,
hh.term_of_validity, hh.phone, hh.email, jj.language_name, dd.`status`,
aa.team_product_number, bb.company_order_number, gg.customer_id, gg.check_in, gg.check_on, gg.room_code, gg.room_type
from team_product aa, company_order bb, company_order_product_team cc, company_order_customer dd,
     company_order_accommodation gg, customer hh, country ii, `language` jj
where aa.team_product_number=cc.team_product_number and bb.company_order_number=cc.company_order_number
and bb.company_order_number=dd.company_order_number and dd.customer_id= gg.customer_id
and bb.company_order_number=gg.company_order_number and gg.customer_id=hh.customer_id
and hh.country_id=ii.country_id and hh.language_id=jj.language_id and cc.`status`=1
and dd.`status`=1 and aa.team_product_number='{$team_product_number}'
order by dd.team_customer_number, dd.customer_id");

        $company_order_flight = Db::query("select dd.customer_id, dd.team_customer_number, aa.team_product_number, bb.company_order_number, 
ee.end_place, ee.flight_begin_time, ee.flight_code, ee.flight_end_time, ee.flight_type, ee.start_place, ee.remark, hh.customer_first_name, hh.customer_last_name
from team_product aa, company_order bb, company_order_product_team cc, company_order_customer dd, company_order_flight ee, customer hh
where aa.team_product_number=cc.team_product_number and bb.company_order_number=cc.company_order_number
and bb.company_order_number=dd.company_order_number and bb.company_order_number=ee.company_order_number
and dd.customer_id=hh.customer_id and ee.flight_type != 3
and cc.`status`=1 and dd.`status`=1 and ee.`status`=1 
and aa.team_product_number='{$team_product_number}'
order by dd.team_customer_number, dd.customer_id");

        $checkInNumber = 0;
        $checkOutNumber = 0;
        $checkInItem = 0;
        $checkOutItem = 0;
        $flightList = [];
        $flightNumber = 0;
        $flightCustomerNumber = 1;
        $flightCustomerNames = '';
        $flightDate = '';

        for($i=0;$i<count($order_customers);$i++){
            //入住时间
            $checkInItem = $order_customers[$i]['check_in'];
            $checkOutItem = $order_customers[$i]['check_on'];
            if ($checkInNumber > $checkInItem){
                $checkInNumber = $checkInItem;
            }
            if ($checkOutNumber < $checkOutItem){
                $checkOutNumber = $checkOutItem;
            }

            if($order_customers[$i]['check_in']=="-5"){
                $order_customers[$i]['check_in'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} -5 day"));
            }else if($order_customers[$i]['check_in']=="-4"){
                $order_customers[$i]['check_in'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} -4 day"));
            }else if($order_customers[$i]['check_in']=="-3"){
                $order_customers[$i]['check_in'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} -3 day"));
            }else if($order_customers[$i]['check_in']=="-2"){
                $order_customers[$i]['check_in'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} -2 day"));
            }else if($order_customers[$i]['check_in']=="-1"){
                $order_customers[$i]['check_in'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} -1 day"));
            }else if($order_customers[$i]['check_in']=="0"){
                $order_customers[$i]['check_in'] = date('Y-m-d',strtotime("{$result[0]['begin_time']}"));
            }else if($order_customers[$i]['check_in']=="5"){
                $order_customers[$i]['check_in'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} +5 day"));
            }else if($order_customers[$i]['check_in']=="4"){
                $order_customers[$i]['check_in'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} +4 day"));
            }else if($order_customers[$i]['check_in']=="3"){
                $order_customers[$i]['check_in'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} +3 day"));
            }else if($order_customers[$i]['check_in']=="2"){
                $order_customers[$i]['check_in'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} +2 day"));
            }else if($order_customers[$i]['check_in']=="1"){
                $order_customers[$i]['check_in'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} +1 day"));
            }

            //退房时间
            if($order_customers[$i]['check_on']=="-5"){
                $order_customers[$i]['check_on'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} -5 day"));
            }else if($order_customers[$i]['check_on']=="-4"){
                $order_customers[$i]['check_on'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} -4 day"));
            }else if($order_customers[$i]['check_on']=="-3"){
                $order_customers[$i]['check_on'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} -3 day"));
            }else if($order_customers[$i]['check_on']=="-2"){
                $order_customers[$i]['check_on'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} -2 day"));
            }else if($order_customers[$i]['check_on']=="-1"){
                $order_customers[$i]['check_on'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} -1 day"));
            }else if($order_customers[$i]['check_on']=="0"){
                $order_customers[$i]['check_on'] = date('Y-m-d',strtotime("{$result[0]['begin_time']}"));
            }else if($order_customers[$i]['check_on']=="5"){
                $order_customers[$i]['check_on'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} +5 day"));
            }else if($order_customers[$i]['check_on']=="4"){
                $order_customers[$i]['check_on'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} +4 day"));
            }else if($order_customers[$i]['check_in']=="3"){
                $order_customers[$i]['check_on'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} +3 day"));
            }else if($order_customers[$i]['check_on']=="2"){
                $order_customers[$i]['check_on'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} +2 day"));
            }else if($order_customers[$i]['check_on']=="1"){
                $order_customers[$i]['check_on'] = date('Y-m-d',strtotime("{$result[0]['begin_time']} +1 day"));
            }

            $result[0]['order_customers'] = $order_customers;
        }

        $isRepeat = 0;
        for($i=0;$i<count($company_order_flight);$i++){
            //获得游客数量
            for($j=0;$j<count($flightList);$j++){
                if ($flightList[$j]['flight_code'] == $company_order_flight[$i]['flight_code']
                    && $flightList[$j]['flight_date'] == $company_order_flight[$i]['flight_date']) {
                    $flightList[$j]['flight_customer_number'] = ($flightList[$j]['flight_customer_number'] + 1);
                    $flightList[$j]['flight_customer_name'] = $flightList[$j]['flight_customer_name'] . ',' . $company_order_flight[$i]['customer_first_name'];
                    $isRepeat = 1;
                    $flightCustomerNumber = 1;
                    $flightCustomerNames = '';
                }
            }

            if ($isRepeat == 0) {
                $flightList[$flightNumber]['flight_customer_number'] = 1;
                $flightList[$flightNumber]['flight_customer_name'] = $company_order_flight[$i]['flight_customer_name'];
                $flightList[$flightNumber]['flight_code'] = $company_order_flight[$i]['flight_code'];
                $flightList[$flightNumber]['start_place'] = $company_order_flight[$i]['start_place'];
                $flightList[$flightNumber]['end_place'] = $company_order_flight[$i]['end_place'];
                $flightList[$flightNumber]['flight_time'] = date("h:i:s",$company_order_flight[$i]['flight_begin_time']) . '/' . date("h:i:s",$company_order_flight[$i]['flight_end_time']);
                $flightList[$flightNumber]['remark'] = $company_order_flight[$i]['remark'];
                if ($company_order_flight[$i]['flight_type'] == 1) {
                    $flightList[$flightNumber]['flight_date'] = date("Y-m-d",$company_order_flight[$i]['flight_end_time']);
                }else{
                    $flightList[$flightNumber]['flight_date'] = date("Y-m-d",$company_order_flight[$i]['flight_begin_time']);
                }

                $flightNumber++;
            }
            $isRepeat = 0;
        }

        $result[0]['team_product_flight'] = $flightList;
    

        if($checkInNumber<0){
            for($k=-1;$k>=$checkInNumber;$k--){

                //统计游客和房型
                $counr_customers_and_room = Db::query("select dd.customer_id, dd.team_customer_number, gg.check_in_hotel, gg.check_on_hotel, hh.customer_type, hh.customer_first_name, hh.customer_last_name, hh.gender, ii.country_name, hh.card_type, hh.card_number,
hh.term_of_validity, hh.phone, hh.email, jj.language_name, dd.`status`,
aa.team_product_number, bb.company_order_number, gg.customer_id, gg.check_in, gg.check_on, gg.room_code, gg.room_type
from team_product aa, company_order bb, company_order_product_team cc, company_order_customer dd,
     company_order_accommodation gg, customer hh, country ii, `language` jj
where aa.team_product_number=cc.team_product_number and bb.company_order_number=cc.company_order_number
and bb.company_order_number=dd.company_order_number and dd.customer_id= gg.customer_id
and bb.company_order_number=gg.company_order_number and gg.customer_id=hh.customer_id
and hh.country_id=ii.country_id and hh.language_id=jj.language_id and cc.`status`=1
and dd.`status`=1 and aa.team_product_number='{$team_product_number}' and gg.check_in='{$checkInNumber}'
order by dd.team_customer_number, dd.customer_id");

                $customer_room="";
                for($m=0;$m<count($counr_customers_and_room);$m++){
                    if($counr_customers_and_room[$m]['room_type']==1||$counr_customers_and_room[$m]['room_type']==2||$counr_customers_and_room[$m]['room_type']==3||$counr_customers_and_room[$m]['room_type']==4){
                        $customer_room.=$counr_customers_and_room[$m]['room_code'].",";
                    }
                }
                if(!empty($customer_room)){
                    $customer_room=rtrim($customer_room,",");
                    $customer_room_info = explode(",",$customer_room);

                    $asd='';
                    foreach(array_count_values($customer_room_info) as $key=>$val){
                        if($key==1){
                            $asd.="双人房*".$val." ";
                        }else if($key==2){
                            $asd.="大床房*".$val." ";
                        }else if($key==3){
                            $asd.="单人房*".$val." ";
                        }else if($key==4){
                            $asd.="加床*".$val." ";
                        }
                    }
                    $team_product_journey[$k]['customers_room']=$asd;
                }

                $team_product_journey[$k]=[
                    "begin_time"=>date('Y-m-d',strtotime("{$result[0]['begin_time']} -".(-$k)." day")),
                    "route_journey_title"=>"提前入住".(-$k)."天",
                    "customers_all"=>count($counr_customers_and_room),
                    "customers_room"=>$team_product_journey[0]['customers_room'],
                    "eat_mark"=>'',
                    "route_journey_breakfast"=>'',
                    "route_journey_lunch"=>'',
                    "route_journey_dinner"=>'',
                    "route_journey_scenic_sport"=>'',
                    "route_journey_stay"=>$counr_customers_and_room[0]['check_in_hotel'],
                    "route_journey_remark"=>''
                ];
                // 取得列的列表
                foreach ($team_product_journey as $key => $row)
                {
                    $volume[$key]  = $row['volume'];
                    $edition[$key] = $row['edition'];
                }

                array_multisort($volume, SORT_DESC, $edition, SORT_ASC, $team_product_journey);
                $result[0]['team_product_journey'] = $team_product_journey;
            }
        }

        if($checkOutNumber>0){
            $kk_start=count($team_product_journey);
            $kk_bc=(count($team_product_journey)+$checkOutNumber);
            for($kk=$kk_start;$kk<$kk_bc;$kk++){

                //统计游客和房型
                $counr_customers_and_room = Db::query("select dd.customer_id, dd.team_customer_number, gg.check_in_hotel, gg.check_on_hotel, hh.customer_type, hh.customer_first_name, hh.customer_last_name, hh.gender, ii.country_name, hh.card_type, hh.card_number,
hh.term_of_validity, hh.phone, hh.email, jj.language_name, dd.`status`,
aa.team_product_number, bb.company_order_number, gg.customer_id, gg.check_in, gg.check_on, gg.room_code, gg.room_type
from team_product aa, company_order bb, company_order_product_team cc, company_order_customer dd,
     company_order_accommodation gg, customer hh, country ii, `language` jj
where aa.team_product_number=cc.team_product_number and bb.company_order_number=cc.company_order_number
and bb.company_order_number=dd.company_order_number and dd.customer_id= gg.customer_id
and bb.company_order_number=gg.company_order_number and gg.customer_id=hh.customer_id
and hh.country_id=ii.country_id and hh.language_id=jj.language_id and cc.`status`=1
and dd.`status`=1 and aa.team_product_number='{$team_product_number}' and gg.check_on='{$checkOutNumber}'
order by dd.team_customer_number, dd.customer_id");

                $customer_room="";
                for($m=0;$m<count($counr_customers_and_room);$m++){
                    if($counr_customers_and_room[$m]['room_type']==1||$counr_customers_and_room[$m]['room_type']==2||$counr_customers_and_room[$m]['room_type']==3||$counr_customers_and_room[$m]['room_type']==4){
                        $customer_room.=$counr_customers_and_room[$m]['room_code'].",";
                    }
                }
                if(!empty($customer_room)){
                    $customer_room=rtrim($customer_room,",");
                    $customer_room_info = explode(",",$customer_room);

                    $asd='';
                    foreach(array_count_values($customer_room_info) as $key=>$val){
                        if($key==1){
                            $asd.="双人房*".$val." ";
                        }else if($key==2){
                            $asd.="大床房*".$val." ";
                        }else if($key==3){
                            $asd.="单人房*".$val." ";
                        }else if($key==4){
                            $asd.="加床*".$val." ";
                        }
                    }
                    $team_product_journey[$kk]['customers_room']=$asd;
                }

                $team_product_journey[$kk]=[
                    "begin_time"=>date('Y-m-d',strtotime("{$result[0]['begin_time']} +".($kk)." day")),
                    "route_journey_title"=>"延后退房".($checkOutNumber)."天",
                    "customers_all"=>count($counr_customers_and_room),
                    "customers_room"=>$team_product_journey[$kk]['customers_room'],
                    "eat_mark"=>'',
                    "route_journey_breakfast"=>'',
                    "route_journey_lunch"=>'',
                    "route_journey_dinner"=>'',
                    "route_journey_scenic_sport"=>'',
                    "route_journey_stay"=>$counr_customers_and_room[0]['check_on_hotel'],
                    "route_journey_remark"=>''
                ];
            }
            $result[0]['team_product_journey'] = $team_product_journey;
        }


        //自费项目
        //获取团队产品下所有订单
        $team_product_company_order = DB::query("select dd.company_order_product_source_id,dd.company_order_number,dd.source_id,dd.source_name,dd.source_price
from team_product aa, company_order bb, company_order_product_team cc, company_order_product_source dd
where aa.team_product_number=cc.team_product_number and bb.company_order_number=cc.company_order_number and cc.company_order_number=dd.company_order_number
and supplier_type_id=11
and aa.`status`=1 and bb.`status`=1 and cc.`status`=1 and dd.status=1
and aa.team_product_number='{$team_product_number}'");

        //订单游客信息
        $team_product_customer_info = DB::query("select gg.customer_first_name,gg.customer_last_name,ee.company_order_customer_id,dd.company_order_product_source_id,dd.company_order_number,dd.source_id,dd.source_name,dd.source_price
from team_product aa, company_order bb, company_order_product_team cc, company_order_product_source dd, company_order_relation ee, company_order_customer ff, customer gg
where aa.team_product_number=cc.team_product_number and bb.company_order_number=cc.company_order_number and cc.company_order_number=dd.company_order_number 
and ee.company_order_product_source_id=dd.company_order_product_source_id and ee.company_order_customer_id =ff.company_order_customer_id and ff.customer_id = gg.customer_id
and supplier_type_id=11
and aa.`status`=1 and bb.`status`=1 and cc.`status`=1 and dd.status=1
and aa.team_product_number='{$team_product_number}'");

        //游客名字
        $customer_name_info="";
        $customerNumberMount=0;
        for($y=0;$y<count($team_product_customer_info);$y++){
            $customerNumberMount++;
            $customer_name_info.=($team_product_customer_info[$y]['customer_first_name']." ".$team_product_customer_info[$y]['customer_last_name']).",";
        }
        $customer_name_info=trim($customer_name_info,",");

        for($x=0;$x<count($team_product_company_order);$x++){
            $team_product_company_order[$x]['customer_name']=$customer_name_info;
            $team_product_company_order[$x]['customer_mount']=$customerNumberMount;
            $result[0]['team_product_single_source']=$team_product_company_order;
        }


        //回执单内容
        $route_type_id = $result[0]['route_type_id'];
        $return_receipt_info = DB::query("select title,content from return_receipt_info 
where return_receipt_id='{$route_type_id}' ORDER BY sorting asc");

        $result[0]['team_product_return_receipt']=$return_receipt_info;

        return $result;
    }
    
    /**
     * 添加团队产品（导入数据）
     */
    public function addTeamProductByImport($params){
    	$t = time();

    	
    	$data['plan_id'] = $params['plan_id'];
		$data['team_product_number']=$params['team_product_number'];
		$data['team_product_name']=$params['team_product_name'];
    	$data['settlement_type']=$params['settlement_type'];
    	$data['team_product_user_id']=$params['team_product_user_id'];
    	$data['company_id']=$params['user_company_id'];
    	$data['status']=$params['status'];
    	//$data['']=$params[''];

    	
		
    


    	$data['create_time'] = $params['create_time'];  	
    	$data['create_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['user_id'];
    	$data['status'] = 1;   		

    	
    
    	


    
    
    	$this->startTrans();
    	try{
    		
    		$result = $this->insertGetId($data);
  
    		
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
    
    //修改团队产品开团时间根据PLAN_ID
    public function updateTeamProductBeginTimeByPlanId($params){
    	$data['begin_time'] = $params['begin_time'];
    	$this->startTrans();
    	try{
    		$this->where("plan_id = ".$params['plan_id'])->update($data);
    	
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