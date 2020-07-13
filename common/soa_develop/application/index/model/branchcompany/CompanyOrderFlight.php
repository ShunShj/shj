<?php

namespace app\index\model\branchcompany;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class CompanyOrderFlight extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'company_order_flight';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加分公司订单的顾客航班
     * 胡
     */
    public function addCompanyOrderFlight($params){
    	
        $t = time();
		$user_id = $params['now_user_id'];
		$customer_id = $params['customer_id'];
		$company_order_number = $params['company_order_number'];
		$company_order_customer_id = $params['company_order_customer_id'];
// 		$sql = "insert into company_order_flight(company_order_number,customer_id,flight_code,flight_begin_time,flight_end_time,flight_type,start_place,end_place,create_time,update_time,create_user_id,update_user_id,status) values";

// 		$sql_count = 0;

		$sql = trim($sql,',');
		
        $this->startTrans();
        try{
        	$customer_flight_info = $params['customer_flight'];
        
	        for($i=0;$i<count($customer_flight_info);$i++){
	        	$key=0;
	        	$data = [];
	        	if(!empty($customer_flight_info[$i]['flight_code'])){
	        		$data['flight_code'] = $customer_flight_info[$i]['flight_code'];
	        		$key++;
	        	}
	        	if(!empty($customer_flight_info[$i]['flight_begin_time'])){
	        		$data['flight_begin_time'] = $customer_flight_info[$i]['flight_begin_time'];
	        		$key++;
	        	}
	        	if(!empty($customer_flight_info[$i]['flight_end_time'])){
	        		$data['flight_end_time'] =$customer_flight_info[$i]['flight_end_time'];
	        		$key++;
	        	}
	        	if(!empty($customer_flight_info[$i]['flight_time'])){
	        		$data['flight_time'] =$customer_flight_info[$i]['flight_time'];
	        		$key++;
	        	}
	        	if(!empty($customer_flight_info[$i]['flight_type'])){
	        		$data['flight_type'] =$customer_flight_info[$i]['flight_type'];
	        		$key++;
	        	}
	        	if(!empty($customer_flight_info[$i]['start_place'])){
	        		$data['start_place'] = $customer_flight_info[$i]['start_place'];
	        		$key++;
	        	}
	        	if(!empty($customer_flight_info[$i]['end_place'])){
	        		$data['end_place'] = $customer_flight_info[$i]['end_place'];
	        		$key++;
	        	}
	        	if(!empty($customer_flight_info[$i]['remark'])){
	        		$data['remark'] = $customer_flight_info[$i]['remark'];
	        		$key++;
	        	}	        
	        	if($key==0){
	        		continue;
	        	}
	        	$data['company_order_number'] = $company_order_number;
	        	$data['company_order_customer_id'] = $company_order_customer_id;
	        	$data['customer_id'] = $customer_id;
	        	$data['update_time'] = $t;
				$data['create_time'] = $t;
				$data['create_user_id'] = $user_id;
				$data['update_user_id'] = $user_id;
				$data['status'] = 1;
				
				$this->insert($data);
			
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
    }

    /**
     * 获取分公司订单 顾客航班
     * 胡
     */
    public function getCompanyOrderFlight($params){

 
    	$data = "1=1";
    	if(isset($params['company_order_number'])){
    		$data.= " and company_order_flight.company_order_number = '".$params['company_order_number']."'";
    	}
    	if(isset($params['company_order_flight_id'])){
    		$data.= " and company_order_flight.company_order_flight_id = '".$params['company_order_flight_id']."'";
    	}
    	if(isset($params['company_order_customer_id'])){
    		$data.= " and company_order_flight.company_order_customer_id = '".$params['company_order_customer_id']."'";
    	}
    	if(is_numeric($params['customer_id'])){
    		$data.= " and company_order_flight.customer_id = ".$params['customer_id'];
    	}   	
    	if(is_numeric($params['status'])){
    		$data.= " and company_order_flight.status = ".$params['status'];
    	}


            $result= $this->table("company_order_flight")->alias("company_order_flight")->
            join("customer customer","customer.customer_id = company_order_flight.customer_id",'left')->

            where($data)->
            field(['company_order_flight.company_order_flight_id','company_order_flight.customer_id','company_order_flight.company_order_customer_id',
            		'company_order_flight.company_order_number','company_order_flight.customer_id',
            		'customer.customer_first_name','customer.customer_last_name','customer.english_first_name','customer.english_last_name',
            		'company_order_flight.flight_code',
            		'company_order_flight.flight_begin_time','company_order_flight.flight_end_time',
            		
            		'company_order_flight.flight_type','company_order_flight.start_place',
            		'company_order_flight.end_place','company_order_flight.remark',
            		"(select nickname  from user where user.user_id = company_order_flight.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = company_order_flight.update_user_id)"=>'update_user_name',
            		'company_order_flight.create_user_id','company_order_flight.update_user_id','company_order_flight.create_time',
            		'company_order_flight.update_time','company_order_flight.status'])->
            
            select();

       


        return $result;

    }


    /**
     * 修改分公司订单根据分公司订单ID
     */
    public function updateCompanyOrderFlightByCompanyOrderFlightId($params){

        $t = time();
        
        if(!empty($params['flight_code'])){
        	$data['flight_code'] = $params['flight_code'];

        }
        if(!empty($params['flight_time'])){
        	$data['flight_time'] = $params['flight_time'];
        
        }
        if(!empty($params['flight_type'])){
        	$data['flight_type'] = $params['flight_type'];
        
        } 
        
        if(!empty($params['start_place'])){
        	$data['start_place'] = $params['start_place'];
        
        }       
        if(!empty($params['end_place'])){
        	$data['end_place'] = $params['end_place'];
        
        }     
     




        if(is_numeric($params['status'])){
            $data['status'] = $params['status'];

        }
		

        $data['update_user_id'] = $params['user_id'];

        $data['update_time'] = $t;




        $this->startTrans();
        try{
            $this->where("company_order_flight_id = ".$params['company_order_flight_id'])->update($data);

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
     * 修改公司订单航班根据公司订单航班ID以及顾客ID
     */
    public function updateCompanyOrderFlightByCompanyOrderNumberAndCustomerId($params){
    
    	$t = time();
    
    	if(!empty($params['flight_code'])){
    		$data['flight_code'] = $params['flight_code'];
    
    	}
    	if(!empty($params['flight_begin_time'])){
    		$data['flight_begin_time'] = $params['flight_begin_time'];
    
    	}
        if(!empty($params['flight_end_time'])){
            $data['flight_end_time'] = $params['flight_end_time'];

        }
    	if(!empty($params['flight_type'])){
    		$data['flight_type'] = $params['flight_type'];
    
    	}
    
    	if(!empty($params['start_place'])){
    		$data['start_place'] = $params['start_place'];
    
    	}
    	if(!empty($params['end_place'])){
    		$data['end_place'] = $params['end_place'];
    
    	}
    	 
    
    
    
    
    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    
    	}
    

    	$data['update_user_id'] = $params['user_id'];
    
    	$data['update_time'] = $t;
    

    	
    	$this->startTrans();
    	try{
    		$this->where("company_order_number = '".$params['company_order_number']."' and customer_id =".$params['customer_id'])->update($data);
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