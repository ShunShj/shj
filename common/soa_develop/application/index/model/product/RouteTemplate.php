<?php
namespace app\index\model\product;
use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use think\config;
use app\index\service\ProportionService;
use think\Db;
class RouteTemplate extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'route_template';
    private $_languageList;
    private $_public_service;
    private $_proportion_service;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	$this->_public_service = new PublicService();
        $this->_proportion_service = new ProportionService();
    	parent::initialize();
     
    }

    /**
     * 添加路线模板
     * 胡-隐
     * 韩-改
     */
    public function addRouteTemplate($params){

        $t = time();

        //线路名称
        $data['route_name'] = $params['route_name'];
        //线路模版编号
    	$data['route_number'] = $params['route_number'];
        //线路类型ID
    	$data['route_type_id'] = $params['route_type_id'];
    	//线路负责人id
        $data['route_user_id'] = $params['route_user_id'];
        //所属分公司id
        $data['company_id'] = $params['company_id'];

        //可见分工司
        $data['use_company_id'] = $params['use_company_id'];
        //结算方式
        $data['settlement_type'] = $params['settlement_type'];
        //计划收客
        $data['plan_custom_number'] = $params['plan_custom_number'];
        //截止日期
        $data['before_days'] = $params['before_days'];

    	$data['create_time'] = $t;
    	$data['create_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['user_id'];
    	$data['status'] = $params['status'];

        $this->startTrans();
        try{
            //获取主键
            $pk_id = $this->insertGetId($data);
            $this->_public_service->setNumber('route_template', 'route_template_id', $pk_id, 'route_number', $data['route_number'] , $pk_id);
            
            //添加行程
            $flight_values="insert into route_flight (route_template_id,the_days,start_city,end_city,start_time,end_time,flight_number,flight_type,create_time,create_user_id,update_time,update_user_id,status) values";

            if(!empty($params['route_template_flight_info'])){
                for($i=0;$i<count($params['route_template_flight_info']);$i++){
                    //路线模板ID
                    $route_template_id = $pk_id;
                    //第几天
                    $the_days = $params['route_template_flight_info'][$i]['the_days'];
                    //出发地
                    $start_city = $params['route_template_flight_info'][$i]['start_city'];
                    //目的地
                    $end_city = $params['route_template_flight_info'][$i]['end_city'];
                    //出发时间
                    $start_time = $params['route_template_flight_info'][$i]['start_time'];
                    //到达时间
                    $end_time = $params['route_template_flight_info'][$i]['end_time'];
                    //航班编号
                    $flight_number = $params['route_template_flight_info'][$i]['flight_number'];
                    //接送机
                    $flight_type = $params['route_template_flight_info'][$i]['flight_type'];

                    $create_time = $t;
                    $create_user_id = $params['user_id'];
                    $update_time = $t;
                    $update_user_id = $params['user_id'];
                    $status = 1;

                    if($i!=count($params['route_template_flight_info'])-1){
                        $comma = ',';
                    }else{
                        $comma = '';
                    }

                    $flight_values.="($route_template_id,$the_days,'$start_city','$end_city',$start_time,$end_time,'$flight_number',$flight_type,$create_time,$create_user_id,$update_time,$update_user_id,$status)".$comma;

                }

                $this->execute($flight_values);
            }

            //添加行程内容
            $journey_values="insert into route_journey (route_template_id,the_days,route_journey_title,route_journey_content,route_journey_traffic,route_journey_stay,eat_mark,route_journey_breakfast,route_journey_lunch,route_journey_dinner,route_journey_scenic_sport,route_journey_picture,route_journey_remark,route_journey_zone,create_time,create_user_id,update_time,update_user_id,status) values";

            if(!empty($params['route_template_journey_info'])){
                for($i=0;$i<count($params['route_template_journey_info']);$i++){
                	/*
                    //线路模版ID
                    $route_template_id = $pk_id;
                    //第几天
                    $the_days = $params['route_template_journey_info'][$i]['the_days'];
                    //行程标题
                    $route_journey_title = $params['route_template_journey_info'][$i]['route_journey_title'];
                    //行程内容
                    $route_journey_content = $params['route_template_journey_info'][$i]['route_journey_content'];
                    //交通
                    $route_journey_traffic = $params['route_template_journey_info'][$i]['route_journey_traffic'];
                    //住宿
                    $route_journey_stay = $params['route_template_journey_info'][$i]['route_journey_stay'];
                    //吃饭标注
                    $eat_mark = $params['route_template_journey_info'][$i]['eat_mark'];
                    //早餐
                    $route_journey_breakfast = $params['route_template_journey_info'][$i]['route_journey_breakfast'];
                    //午餐
                    $route_journey_lunch = $params['route_template_journey_info'][$i]['route_journey_lunch'];
                    //晚餐
                    $route_journey_dinner = $params['route_template_journey_info'][$i]['route_journey_dinner'];
                    //景点
                    $route_journey_scenic_sport = $params['route_template_journey_info'][$i]['route_journey_scenic_sport'];
                    //图片
                    $route_journey_picture = addslashes($params['route_template_journey_info'][$i]['route_journey_picture']);
                    //备注
                    $route_journey_remark = $params['route_template_journey_info'][$i]['route_journey_remark'];
                    //地区
                    
                    
                    $route_journey_zone =  $params['route_template_journey_info'][$i]['route_journey_zone'];
					if(!is_numeric($route_journey_zone)){
						$route_journey_zone=0;
					}
                    $create_time = $t;
                    $create_user_id = $params['user_id'];
                    $update_time = $t;
                    $update_user_id = $params['user_id'];
                    $status = 1;

                    if($i!=count($params['route_template_journey_info'])-1){
                        $comma = ',';
                    }else{
                        $comma = '';
                    }
					*/
                    //$journey_values.="($route_template_id,$the_days,'$route_journey_title','$route_journey_content','$route_journey_traffic','$route_journey_stay','$eat_mark','$route_journey_breakfast','$route_journey_lunch','$route_journey_dinner','$route_journey_scenic_sport','$route_journey_picture','$route_journey_remark','$route_journey_zone',$create_time,$create_user_id,$update_time,$update_user_id,$status)".$comma;
                	$route_journey_data['route_template_id'] = $pk_id;
                	
                	if(!empty($params['route_template_journey_info'][$i]['the_days'])){
                		$route_journey_data['the_days'] = $params['route_template_journey_info'][$i]['the_days'];
                	}
                	if(!empty($params['route_template_journey_info'][$i]['route_journey_traffic'])){
                		$route_journey_data['route_journey_traffic'] = $params['route_template_journey_info'][$i]['route_journey_traffic'];
                	}
                	if(!empty($params['route_template_journey_info'][$i]['route_journey_stay'])){
                		$route_journey_data['route_journey_stay'] = $params['route_template_journey_info'][$i]['route_journey_stay'];
                	}
                	if(!empty($params['route_template_journey_info'][$i]['eat_mark'])){
                		$route_journey_data['eat_mark'] = $params['route_template_journey_info'][$i]['eat_mark'];
                	}
                	if(!empty($params['route_template_journey_info'][$i]['route_journey_breakfast'])){
                		$route_journey_data['route_journey_breakfast'] = $params['route_template_journey_info'][$i]['route_journey_breakfast'];
                	}
                	if(!empty($params['route_template_journey_info'][$i]['route_journey_lunch'])){
                		$route_journey_data['route_journey_lunch'] = $params['route_template_journey_info'][$i]['route_journey_lunch'];
                	}
                	if(!empty($params['route_template_journey_info'][$i]['route_journey_dinner'])){
                		$route_journey_data['route_journey_dinner'] = $params['route_template_journey_info'][$i]['route_journey_dinner'];
                	}
                	if(!empty($params['route_template_journey_info'][$i]['route_journey_scenic_sport'])){
                		$route_journey_data['route_journey_scenic_sport'] = $params['route_template_journey_info'][$i]['route_journey_scenic_sport'];
                	}
                	if(!empty($params['route_template_journey_info'][$i]['route_journey_picture'])){
                		$route_journey_data['route_journey_picture'] = $params['route_template_journey_info'][$i]['route_journey_picture'];
                	}
                	if(!empty($params['route_template_journey_info'][$i]['route_journey_remark'])){
                		$route_journey_data['route_journey_remark'] = $params['route_template_journey_info'][$i]['route_journey_remark'];
                	}
                	$route_journey_data['route_journey_title'] = $params['route_template_journey_info'][$i]['route_journey_title'];
                	$route_journey_data['route_journey_content'] = $params['route_template_journey_info'][$i]['route_journey_content'];
                	
                	$route_journey_data['create_time'] = $t;
                	$route_journey_data['create_user_id'] = $params['user_id'];
                	$route_journey_data['update_time'] = $t;
                	$route_journey_data['update_user_id'] = $params['user_id'];
                	$route_journey_data['status'] = 1;
                	$this->table('route_journey')->insert($route_journey_data);
                	
                	
                	

                }
                
                

               	
               
            }

            //添加资源信息
            $allocation_values="insert into route_source_allocation (route_template_id,supplier_type_id,source_id,payment_currency_id,source_price,source_count,source_total_price,source_the_days,create_time,create_user_id,update_time,update_user_id,status) values";

            if(!empty($params['route_template_allocation_info'])){
                for($i=0;$i<count($params['route_template_allocation_info']);$i++){
                    //团队产品ID
                    $route_template_id = $pk_id;
                    //资源类型ID
                    $supplier_type_id = $params['route_template_allocation_info'][$i]['supplier_type_id'];
                    //对应资源ID
                    $source_id = $params['route_template_allocation_info'][$i]['source_id'];
                    //币种
                    $payment_currency_id = $params['route_template_allocation_info'][$i]['payment_currency_id'];
                    //单价
                    $source_price = $params['route_template_allocation_info'][$i]['source_price'];
                    //数量
                    $source_count = $params['route_template_allocation_info'][$i]['source_count'];
                    //总价
                    $source_total_price = $params['route_template_allocation_info'][$i]['source_total_price'];
                    //第几天
                    $source_the_days = $params['route_template_allocation_info'][$i]['source_the_days'];

                    $create_time = $t;
                    $create_user_id = $params['user_id'];
                    $update_time = $t;
                    $update_user_id = $params['user_id'];
                    $status = 1;

                    if($i!=count($params['route_template_allocation_info'])-1){
                        $comma = ',';
                    }else{
                        $comma = '';
                    }
                    $allocation_values.="($route_template_id,$supplier_type_id,$source_id,$payment_currency_id,$source_price,$source_count,$source_total_price,$source_the_days,$create_time,$create_user_id,$update_time,$update_user_id,$status)".$comma;

                }

                $this->execute($allocation_values);
            }

            //添加回执单模版
            $return_receipt_values="insert into route_return_receipt (route_template_id,title,content,sorting,create_time,create_user_id,update_time,update_user_id,status) values";

            if(!empty($params['route_return_receipt_info'])){
                for($i=0;$i<count($params['route_return_receipt_info']);$i++){
                    //线路模版ID
                    $route_template_id = $pk_id;
                    //标题
                    $title = $params['route_return_receipt_info'][$i]['title'];
                    //内容
                    $content = $params['route_return_receipt_info'][$i]['content'];
                    //排序
                    $sorting = $params['route_return_receipt_info'][$i]['sorting'];

                    $create_time = $t;
                    $create_user_id = $params['user_id'];
                    $update_time = $t;
                    $update_user_id = $params['user_id'];
                    $status = 1;

                    if($i!=count($params['route_return_receipt_info'])-1){
                        $comma = ',';
                    }else{
                        $comma = '';
                    }
                    $return_receipt_values.="($route_template_id,'$title','$content',$sorting,$create_time,$create_user_id,$update_time,$update_user_id,$status)".$comma;

                }

                $this->execute($return_receipt_values);
            }

            if(!empty($params['settlement_type'])){
                //假如是一口价
                if($params['settlement_type'] == 1){
                    $once_price_values="insert into route_once_price (route_template_id,company_id,team_price_currency_id,total_price,create_time,create_user_id,update_time,update_user_id,status) values";

                    for($i=0;$i<count($params['route_once_price']);$i++){
                        //团队产品ID
                        $route_template_id = $pk_id;

                        $company_id = $params['route_once_price'][$i]['company_id'];
                        $total_price = $params['route_once_price'][$i]['total_price'];
                        //一口价币种
                        $team_price_currency_id =$params['route_once_price'][$i]['team_price_currency_id'];
                        $create_time = $t;
                        $create_user_id = $params['user_id'];
                        $update_time = $t;
                        $update_user_id = $params['user_id'];
                        $status = 1;

                        if($i!=count($params['route_once_price'])-1){
                            $comma = ',';
                        }else{
                            $comma = '';
                        }
                        $once_price_values.="($route_template_id,$company_id,$team_price_currency_id,$total_price,$create_time,$create_user_id,$update_time,$update_user_id,$status)".$comma;

                    }

                    $this->execute($once_price_values);
                }
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

//    	$t = time();
//
//
//
//
//    	$data['route_name'] = $params['route_name'];
//    	$data['route_number'] = $params['route_number'];
//    	$data['route_type_id'] = $params['route_type_id'];
//
//
//
//    	$data['route_user_id'] = $params['route_user_id'];
//    	$data['create_time'] = $t;
//    	$data['create_user_id'] = $params['user_id'];
//    	$data['update_time'] = $t;
//    	$data['update_user_id'] = $params['user_id'];
//    	$data['status'] = $params['status'];
//
//
//    	Db::startTrans();
//    	try{
//    		$pk_id = Db::name('route_template')->insertGetId($data);
//
//    		$result = $pk_id;
//    		// 提交事务
//    		Db::commit();
//
//    	} catch (\Exception $e) {
//    		$result = $e->getMessage();
//    		// 回滚事务
//    		Db::rollback();
//
//    		//exit();
//
//    	}
//
//    	return $result;

    }
    
    /**
     * 获取路线模板
     * 胡
     */
    public function getRouteTemplate($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
    

    	$data = "1=1";
    	if(!empty($params['route_template_id'])){
    		$data.= ' and route_template.route_template_id='.$params['route_template_id'];
    	}
    	if(!empty($params['route_number'])){
    		$data.= " and route_template.route_number='".$params['route_number']."'";
    	}
    	if(!empty($params['route_name'])){
    		$data.= " and route_template.route_name like '%".$params['route_name']."%'";
    	}
    	if(!empty($params['company_id'])){
    		$data.= " and route_template.company_id = ".$params['company_id'];
    	}
    	if(is_numeric($params['status'])){
    		$data.= " and route_template.status = ".$params['status'];
    	}
    	if(is_numeric($params['not_company_id'])){
    		$data.= " and route_template.company_id != ".$params['not_company_id'];
    	}
        if(is_numeric($params['can_watch_company_id'])){
            $data.= " and( find_in_set('".$params['can_watch_company_id']."',use_company_id) or route_template.use_company_id = '*' )";
        }
        if(!empty($params['route_type_id'])){
            $data .= " and route_template.route_type_id={$params['route_type_id']}";
        }
       	
        if(!empty($params['route_user'])){
            $where['nickname'] = ['like',"%{$params['route_user']}%"];
            $user_id_ar = $this->table('user')->where($where)->field(['user_id'])->select();
            
            if(!empty($user_id_ar)){
                $a = [];
                foreach ($user_id_ar as $key => $value) {
                   array_push($a, $value['user_id']);        
                } 
                $im = implode(',', $a);
                $data .= " and route_template.route_user_id in ({$im})";
            }else{
                $data .= " and route_template.route_user_id in (0)";
            }
            
        }

         

        if($is_count==true){
            $result = $this->table("route_template")->alias("route_template")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("route_template")->alias('route_template')->where($data)->limit($page, $page_size)
                    ->order('create_time desc')
                    ->join("user", 'user.user_id = route_template.route_user_id')
                    ->join("company", 'company.company_id = route_template.company_id')
                    ->join("route_type", 'route_type.route_type_id = route_template.route_type_id')
                    ->join("currency","currency.currency_id = company.currency_id",'left')
                    ->field([
                        'currency.currency_name','currency.currency_id','currency.unit','route_template.*','user.nickname route_user_name','route_type.route_type_name',"(select count(*) from route_journey where route_template_id = route_template.route_template_id and status = 1) as days",'company.company_name'
//                    "(select nickname  from user where user.user_id = route_template.create_user_id)" => 'create_user_name',
//                    "(select nickname  from user where user.user_id = route_template.update_user_id)" => 'update_user_name',
                    ])->select();
            }else{
                $result = $this->table("route_template")->alias('route_template')->where($data)->order('create_time desc')
                ->join("user", 'user.user_id = route_template.route_user_id')
                ->join("company", 'company.company_id = route_template.company_id')
                ->join("route_type", 'route_type.route_type_id = route_template.route_type_id')
                ->join("currency","currency.currency_id = company.currency_id",'left')
                ->field([
                    'currency.currency_name','currency.currency_id','currency.unit','route_template.*','user.nickname route_user_name','route_type.route_type_name',"(select count(*) from route_journey where route_template_id = route_template.route_template_id and status = 1) as days",'company.company_name'
//                    ,'route_template.route_number','route_template.route_type_id','route_template.route_name','route_template.company_id',
//                    'route_template.plan_custom_number','route_template.status',
//                    "(select nickname  from user where user.user_id = route_template.create_user_id)" => 'create_user_name',
//                    "(select nickname  from user where user.user_id = route_template.update_user_id)" => 'update_user_name',
                ])->select();
                
          
            }
        }

        foreach ($result as $key=>$val){
            if($val['settlement_type']==1){
                if(is_numeric($params['can_watch_company_id'])){
                    $once_price_params = "status = 1  and route_template_id = ".$val['route_template_id']. " and company_id = ". $params['can_watch_company_id'];
                }else if(is_numeric($params['company_id'])){
                	$once_price_params = "status = 1  and route_template_id = ".$val['route_template_id'] . " and company_id = ". $params['company_id'];
                	 
                }else{
                    $once_price_params = "status = 1  and route_template_id = ".$val['route_template_id'];
                }

                $once_price_result = Db::table('route_once_price')->where($once_price_params)->select();
                if (!empty($once_price_result[0]['total_price'])) {
                    if($val['currency_id']!=$once_price_result[0]['currency_id']){
                        $proportion = $this->_proportion_service->getProportion($once_price_result[0]['currency_id'],$val['currency_id']);
                        $result[$key]['once_price'] = number_format($once_price_result[0]['total_price']*$proportion,2);
                    }else{
                        $result[$key]['once_price'] = $once_price_result[0]['total_price'];
                    }
                } else {
                    $result[$key]['once_price'] = 0;
                }
            }else if($val['settlement_type']==2){

                $num=0;
                $real_price_result = Db::table('route_source_allocation')->where("status = 1 and supplier_type_id !=11 and route_template_id =  ".$val['route_template_id'])->select();
                foreach($real_price_result as $key_r=>$val_r){
                    if($val_r['payment_currency_id']!=$result[$key]['currency_id']){
                        $proportion = $this->_proportion_service->getProportion($val_r['payment_currency_id'],$result[$key]['currency_id']);
                        $k_price = number_format($val_r['source_total_price']*$proportion,2);
                    }else{
                        $k_price = $val_r['source_total_price'];
                    }
                    $num+=$k_price;
                }

                $result[$key]['real_price'] = $num;
            }
           
            //读取资源信息
            $source = Db::table('route_source_allocation')->where(array("route_template_id" => $val['route_template_id'],'status'=>1))->select();
            $source_array = [];
            $supplier_type_id = [];
            foreach ($source as $key_s => $val_s) {
                if ($val_s['supplier_type_id'] == 1) {
                    array_push($source_array, "地接");
                } else if ($val_s['supplier_type_id'] == 2) {
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
            $result[$key]['is_choose'] = 1;
            $result[$key]['stance'] = 1;

        }
      
        return $result;
    
    }
    /**
     * 获取路线模板
     * 胡
     */
    public function getRouteTemplateName($params){
    
    
    	$data['status'] = 1;
        if(!empty($params['company_id'])){
    		$data['company_id']= $params['company_id'];
    	} 
    	$result = $this->table("route_template")->alias('route_template')->where($data)->

    	field([
    			'route_template.route_template_id','route_template.route_number','route_template.route_name',
  
    	])->select();
    
    	return $result;
    
    }
    
    /**
     * 修改路线模板
     */
    public function updateRouteTemplateByRouteTemplateId($params){

        $t = time();

        $this->startTrans();
        try {
            //基本信息
            //线路模版名
            if (!empty($params['route_name'])) {
                $data['route_name'] = $params['route_name'];
            }
            if (is_numeric($params['status'])) {
                $data['status'] = $params['status'];
            }
            //路线类型ID
            if (!empty($params['route_type_id'])) {
                $data['route_type_id'] = $params['route_type_id'];
            }

            //所属分公司
            if (!empty($params['company_id'])) {
                $data['company_id'] = trim($params['company_id'], ',');
            }

            //线路负责人ID
            if (!empty($params['route_user_id'])) {
                $data['route_user_id'] = $params['route_user_id'];
            }

            //可见分工司
            $data['use_company_id'] = $params['use_company_id'];
            //结算方式
            $data['settlement_type'] = $params['settlement_type'];
            //计划收客
            $data['plan_custom_number'] = $params['plan_custom_number'];
            //截止日期
            $data['before_days'] = $params['before_days'];

            $data['update_user_id'] = $params['user_id'];
            $data['update_time'] = $t;
            $this->table('route_template')->where("route_template_id = " . $params['route_template_id'])->update($data);

            //航班
            //修改航班状态
            $this->table('route_flight')->where(array('route_template_id' => $params['route_template_id']))->update(['status' => 0]);

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

                    $t_data['route_flight_id'] = $params['edit_flight'][$i]['route_flight_id'];
                    $this->table('route_flight')->where($t_data)->update($data[$i]);

                }
            }


            $user_id = $params['user_id'];

            //添加航班信息
            $flight_values = "insert into route_flight (route_template_id,the_days,start_city,end_city,start_time,end_time,flight_number,flight_type,create_time,create_user_id,update_time,update_user_id,status) values";

            if (!empty($params['add_flight'])) {
                for ($i = 0; $i < count($params['add_flight']); $i++) {
                    //线路模版ID
                    $route_template_id = $params['add_flight'][$i]['route_template_id'];
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

                    $flight_values .= "($route_template_id,$the_days,'$start_city','$end_city',$start_time,$end_time,'$flight_number',$flight_type,$create_time,$create_user_id,$update_time,$update_user_id,$status)" . $comma;
                }

                $this->execute($flight_values);

            }

            //修改行程状态
            $this->table('route_journey')->where(array('route_template_id'=>$params['route_template_id']))->update(['status'=>0]);

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

                    //地区
                    if (isset($params['edit_journey'][$i]['route_journey_zone'])) {
                        $data_journey[$i]['route_journey_zone'] = $params['edit_journey'][$i]['route_journey_zone'];

                    }

                    if (isset($params['edit_journey'][$i]['status'])) {
                        $data_journey[$i]['status'] = $params['edit_journey'][$i]['status'];

                    }

                    $data_journey[$i]['update_user_id'] = $params['user_id'];
                    $data_journey[$i]['update_time'] = $t;

                    $this->table('route_journey')->where(array('route_journey_id' => $params['edit_journey'][$i]['route_journey_id']))->update($data_journey[$i]);
               
                }
            }

            $t = time();
            $user_id = $params['user_id'];

            //添加行程内容
            $journey_values="insert into route_journey (route_template_id,the_days,route_journey_title,route_journey_content,route_journey_traffic,route_journey_stay,eat_mark,route_journey_breakfast,route_journey_lunch,route_journey_dinner,route_journey_scenic_sport,route_journey_picture,route_journey_remark,route_journey_zone,create_time,create_user_id,update_time,update_user_id,status) values";
			
            if(!empty($params['add_journey'])){
                for ($i = 0; $i < count($params['add_journey']); $i++) {
               
                	$data_journey = [];
					$data_journey['route_template_id'] = $params['add_journey'][$i]['route_template_id'];
                    //第几天
                    if (isset($params['add_journey'][$i]['the_days'])) {
                        $data_journey['the_days'] = $params['add_journey'][$i]['the_days'];

                    }

                    //行程标题
                    if (isset($params['add_journey'][$i]['route_journey_title'])) {
                        $data_journey['route_journey_title'] = $params['add_journey'][$i]['route_journey_title'];

                    }

                    //行程内容
                    if (isset($params['add_journey'][$i]['route_journey_content'])) {
                        $data_journey['route_journey_content'] = $params['add_journey'][$i]['route_journey_content'];

                    }

                    //交通
                    if (isset($params['add_journey'][$i]['route_journey_traffic'])) {
                        $data_journey['route_journey_traffic'] = $params['add_journey'][$i]['route_journey_traffic'];

                    }

                    //航班号
                    if (isset($params['add_journey'][$i]['flight_number'])) {
                        $data_journey['flight_number'] = $params['add_journey'][$i]['flight_number'];

                    }

                    //住宿
                    if (isset($params['add_journey'][$i]['route_journey_stay'])) {
                        $data_journey['route_journey_stay'] = $params['add_journey'][$i]['route_journey_stay'];

                    }

                    //吃饭标注
                    if (isset($params['add_journey'][$i]['eat_mark'])) {
                        $data_journey['eat_mark'] = $params['add_journey'][$i]['eat_mark'];

                    }

                    //早餐
                    if (isset($params['add_journey'][$i]['route_journey_breakfast'])) {
                        $data_journey['route_journey_breakfast'] = $params['add_journey'][$i]['route_journey_breakfast'];

                    }

                    //午餐
                    if (isset($params['add_journey'][$i]['route_journey_lunch'])) {
                        $data_journey['route_journey_lunch'] = $params['add_journey'][$i]['route_journey_lunch'];

                    }

                    //晚餐
                    if (isset($params['add_journey'][$i]['route_journey_dinner'])) {
                        $data_journey['route_journey_dinner'] = $params['add_journey'][$i]['route_journey_dinner'];

                    }

                    //景点
                    if (isset($params['add_journey'][$i]['route_journey_scenic_sport'])) {
                        $data_journey['route_journey_scenic_sport'] = $params['add_journey'][$i]['route_journey_scenic_sport'];

                    }

                    //图片
                    if (isset($params['add_journey'][$i]['route_journey_picture'])) {
                        $data_journey['route_journey_picture'] = $params['add_journey'][$i]['route_journey_picture'];

                    }

                    //备注
                    if (isset($params['add_journey'][$i]['route_journey_remark'])) {
                        $data_journey['route_journey_remark'] = $params['add_journey'][$i]['route_journey_remark'];

                    }

                    //地区
                    if (isset($params['add_journey'][$i]['route_journey_zone'])) {
                        $data_journey['route_journey_zone'] = $params['add_journey'][$i]['route_journey_zone'];

                    }

                    if (isset($params['add_journey'][$i]['status'])) {
                        $data_journey['status'] = $params['add_journey'][$i]['status'];

                    }

                    $data_journey['update_user_id'] = $user_id;
                    $data_journey['create_user_id'] = $user_id;
                    $data_journey['create_time'] = $t;
                    $data_journey['update_time'] = $t;
                    $a = $this->table('route_journey')->insert($data_journey);
                  
                }

                
            }

            //修改资源状态
            $this->table('route_source_allocation')->where(array('route_template_id'=>$params['route_template_id']))->update(['status'=>0]);

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

                    $this->table('route_source_allocation')->where(array('route_source_allocation_id' => $params['edit_allocation'][$i]['route_source_allocation_id']))->update($data_allocation[$i]);
                }
            }

            $t = time();
            $user_id = $params['user_id'];

            //添加资源信息
            $allocation_values="insert into route_source_allocation (route_template_id,supplier_type_id,source_id,payment_currency_id,source_price,source_count,source_total_price,source_the_days,create_time,create_user_id,update_time,update_user_id,status) values";

            if(!empty($params['add_allocation'])){
                for ($i = 0; $i < count($params['add_allocation']); $i++) {
                    //线路模版ID
                    $route_template_id = $params['add_allocation'][$i]['route_template_id'];
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
                    //地区
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
                    $allocation_values .= "($route_template_id,$supplier_type_id,$source_id,$payment_currency_id,$source_price,$source_count,$source_total_price,$source_the_days,$create_time,$create_user_id,$update_time,$update_user_id,$status)" . $comma;

                }

                $this->execute($allocation_values);
            }

            //修改回执单模版状态
            $this->table('route_return_receipt')->where(array('route_template_id' => $params['route_template_id']))->update(['status' => 0]);

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

                    $this->table('route_return_receipt')->where(array('route_return_receipt_id' => $params['edit_return_receipt'][$i]['route_return_receipt_id']))->update($data_return_receipt[$i]);

                }
            }

            $t = time();
            $user_id = $params['user_id'];

            //添加回执单模版
            $return_receipt_values = "insert into route_return_receipt (route_template_id,title,content,sorting,create_time,create_user_id,update_time,update_user_id,status) values";

            if (!empty($params['add_return_receipt'])) {
                for ($i = 0; $i < count($params['add_return_receipt']); $i++) {
                    //团队产品ID
                    $route_template_id = $params['add_return_receipt'][$i]['route_template_id'];
                    //标题
                    $title = $params['add_return_receipt'][$i]['title'];
                    //内容
                    $content = $params['add_return_receipt'][$i]['content'];
                    //排序
                    $sorting = $params['add_return_receipt'][$i]['sorting'];

                    $create_time    = $t;
                    $create_user_id = $user_id;
                    $update_time    = $t;
                    $update_user_id = $user_id;
                    $status         = 1;

                    if ($i != count($params['add_return_receipt']) - 1) {
                        $comma = ',';
                    } else {
                        $comma = '';
                    }
                    $return_receipt_values .= "($route_template_id,'$title','$content',$sorting,$create_time,$create_user_id,$update_time,$update_user_id,$status)" . $comma;

                }

                $this->execute($return_receipt_values);
            }
            //一口价
            //修改一口价状态
            $this->table('route_once_price')->where(array('route_template_id'=>$params['route_template_id']))->update(['status'=>0]);

            if(!empty($params['edit_once_price'])) {

                for ($i = 0; $i <count($params['edit_once_price']); $i++) {
                    if (is_numeric($params['edit_once_price'][$i]['company_id'])) {
                        $data_once_price['company_id'] = $params['edit_once_price'][$i]['company_id'];
                    }
                    if (!empty($params['edit_once_price'][$i]['total_price'])) {
                        $data_once_price['total_price'] = $params['edit_once_price'][$i]['total_price'];
                    }
                    if (!empty($params['edit_once_price'][$i]['team_price_currency_id'])) {
                        $data_once_price['team_price_currency_id'] = $params['edit_once_price'][$i]['team_price_currency_id'];
                    }

                    $data_once_price['status'] = 1;
                    $data_once_price['update_user_id'] = $params['user_id'];
                    $data_once_price['update_time'] = $t;

                    $where2['route_once_price_id']  = $params['edit_once_price'][$i]['route_once_price_id'];
                    $this->table('route_once_price')->where($where2)->update($data_once_price);
                    unset($data_once_price);
                }
            }

            //添加一口价
            $once_price_values="insert into route_once_price (route_template_id,company_id,team_price_currency_id,total_price,create_time,create_user_id,update_time,update_user_id,status) values";

            if(!empty($params['add_once_price'])){
                for ($i = 0; $i < count($params['add_once_price']); $i++) {
                    //团队产品ID
                    $route_template_id = $params['route_template_id'];
                    //可见分公司ID
                    $company_id = $params['add_once_price'][$i]['company_id'];
                    //总价
                    $total_price = $params['add_once_price'][$i]['total_price'];
                    //币种
                    $team_price_currency_id =$params['add_once_price'][$i]['team_price_currency_id'];
                    $create_time = $t;
                    $create_user_id = $user_id;
                    $update_time = $t;
                    $update_user_id = $user_id;
                    $status = 1;

                    if ($i != count($params['add_once_price']) - 1) {
                        $comma = ',';
                    } else {
                        $comma = '';
                    }
                    $once_price_values .= "($route_template_id,$company_id,$team_price_currency_id,$total_price,$create_time,$create_user_id,$update_time,$update_user_id,$status)" . $comma;

                }
				
                $this->execute($once_price_values);
            }

            $result = 1;
            // 提交事务
            $this->commit();

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            $this->rollback();

        }
        return $result;

//    	$t = time();
//
//    	if(!empty($params['route_name'])){
//    		$data['route_name'] = $params['route_name'];
//
//    	}
//    	if(!empty($params['route_type_id'])){
//    		$data['route_type_id'] = $params['route_type_id'];
//
//    	}
//    	if(!empty($params['route_user_id'])){
//    		$data['route_user_id'] = $params['route_user_id'];
//
//    	}
//
//    	if(!empty($params['status'])){
//    		$data['status'] = $params['status'];
//
//    	}
//
//
//
//    	$data['update_user_id'] = $params['user_id'];
//    	$data['update_time'] = $t;
//
//
//
//    	$source_price=[];
//    	Db::startTrans();
//    	try{
//    		Db::name('route_template')->where("route_template_id = ".$params['route_template_id'])->update($data);
//    		$result = 1;
//    		// 提交事务
//    		Db::commit();
//
//    	} catch (\Exception $e) {
//    		$result = $e->getMessage();
//    		// 回滚事务
//    		Db::rollback();
//
//    	}
//    	return $result;
    }

    public function getOneRouteTemplate($route_template_id){
        $result = $this->table("route_template")->where(['route_template_id' => $route_template_id])->find();
        return $result;
    }


}