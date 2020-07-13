<?php

namespace app\index\model\branchcompany;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class CompanyOrderAccommodation extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'company_order_accommodation';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加公司订单的顾客住宿
     * 胡
     */
    public function addCompanyOrderCustomerAccommodation($params){

        $t = time();
		$data['company_order_number'] = $params['company_order_number'];
		$data['company_order_customer_id'] = $params['company_order_customer_id'];
		$data['customer_id'] = $params['customer_id'];
		if(is_numeric($params['room_code'])){
			$data['room_code'] = $params['room_code'];
		}
		
		if(is_numeric($params['room_type'])){
			$data['room_type'] =$params['room_type'];
		}
		
		
		$data['check_in'] = $params['check_in'];
		$data['check_on'] = $params['check_on'];
        $data['remark'] = $params['remark'];
		$data['check_in_hotel'] = $params['check_in_hotel'];
        $data['check_on_hotel'] = $params['check_on_hotel'];
		
		$data['create_time'] = $t;
		$data['update_time'] = $t;
		$data['create_user_id'] = $params['now_user_id'];
		$data['update_user_id'] = $params['now_user_id'];

		$data['status'] = 1;
		
        $this->startTrans();
        try{
			
        	$result = $this->insertGetId($data);
        	
        	

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
	 * 添加占位
	 */
    public function addCustomerOccupy($params){
    	$t = time();
    	$user_id = $params['user_id'];
    	$order_number = $params['company_order_number'];
    	$sql =  "insert into company_order_customer (company_order_customer_id,company_order_number,customer_id,create_time,create_user_id,update_time,update_user_id,status) values";
    	
    	
    	$this->startTrans();
    	try{
    		for($i=0;$i<$params['occupy_count'];$i++){
    			$sql.="('','$order_number','',$t,$user_id,$t,$user_id,2),";
    			
    			
    		}
    		
    		$sql = trim($sql,',');
    		
    		$this->execute($sql);

    		
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
     * 获取分公司订单住宿
     * 胡
     */
    public function getCompanyOrderAccommodation($params){

 
    	$data = "1=1";
    	if(isset($params['customer_id'])){
    		$data.= " and company_order_accommodation.customer_id = ".$params['customer_id'];
    	}
    	if(isset($params['company_order_customer_id'])){
    		$data.= " and company_order_accommodation.company_order_customer_id = ".$params['company_order_customer_id'];
    	}
    	if(is_numeric($params['status'])){
    		$data.= " and company_order_accommodation.status = ".$params['status'];
    	}
    	if(isset($params['company_order_accommodation_id'])){
    		$data.= " and company_order_accommodation.company_order_accommodation_id = '".$params['company_order_accommodation_id']."'";
    	}
    	if(isset($params['company_order_number'])){
    		$data.= " and company_order_accommodation.company_order_number = '".$params['company_order_number']."'";
    	}
		
            $result= $this->table("company_order_accommodation")->alias("company_order_accommodation")->
            join("customer customer","customer.customer_id = company_order_accommodation.customer_id",'left')->

            where($data)->
            field(['company_order_accommodation.company_order_accommodation_id',"company_order_accommodation.company_order_number",
            		'company_order_accommodation.customer_id','customer.customer_first_name',
            		'customer.customer_last_name','customer.english_first_name','customer.english_last_name',
            		'company_order_accommodation.room_code',
            		'company_order_accommodation.room_type','company_order_accommodation.check_in','company_order_accommodation.check_on',
                    'company_order_accommodation.check_in_hotel','company_order_accommodation.check_on_hotel',
            		"(select nickname  from user where user.user_id = company_order_accommodation.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = company_order_accommodation.update_user_id)"=>'update_user_name',
            		'company_order_accommodation.create_user_id','company_order_accommodation.create_time','company_order_accommodation.update_user_id',
            		'company_order_accommodation.update_time','company_order_accommodation.status'])->
            
            select();

       


        return $result;

    }


    /**
     * 修改分公司订单-游客住宿 根据游客住宿ID
     */
    public function updateCompanyOrderAccommodationByCompanyOrderAccommodationId($params){

        $t = time();
        
        if(!empty($params['room_code'])){
        	$data['room_code'] = $params['room_code'];

        }
        if(!empty($params['room_type'])){
        	$data['room_type'] = $params['room_type'];
        
        }
        if(isset($params['check_in'])){
        	$data['check_in'] = $params['check_in'];
        
        } 
        if(isset($params['check_on'])){
        	$data['check_on'] = $params['check_on'];
        
        }       
        if(isset($params['check_in_hotel'])){
        	$data['check_in_hotel'] = $params['check_in_hotel'];
        
        }
        if(isset($params['check_on_hotel'])){
        	$data['check_on_hotel'] = $params['check_on_hotel'];
        
        }

        if(is_numeric($params['status'])){
            $data['status'] = $params['status'];

        }


        $data['update_user_id'] = $params['user_id'];

        $data['update_time'] = $t;




        $this->startTrans();
        try{
            $this->where("company_order_accommodation_id = ".$params['company_order_accommodation_id'])->update($data);

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
     * 修改分公司订单-游客住宿 根据公司订单航班ID以及顾客ID
     */
    public function updateCompanyOrderAccommodationByCompanyOrderNumberAndCustomerId($params){
    
    	$t = time();
    
    	if(!empty($params['room_code'])){
    		$data['room_code'] = $params['room_code'];
    
    	}
    	if(!empty($params['room_type'])){
    		$data['room_type'] = $params['room_type'];
    
    	}
    	if(is_numeric($params['check_in'])){
    		$data['check_in'] = $params['check_in'];
    
    	}
    
    	if(is_numeric($params['check_on'])){
    		$data['check_on'] = $params['check_on'];
    
    	}

        if(isset($params['check_in_hotel'])){
            $data['check_in_hotel'] = $params['check_in_hotel'];

        }
        if(isset($params['check_on_hotel'])){
            $data['check_on_hotel'] = $params['check_on_hotel'];

        }
    
    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    
    	}
    
    
    	$data['update_user_id'] = $params['user_id'];
    
    	$data['update_time'] = $t;
    
    
    
    	
    	$this->startTrans();
    	try{
    		$result = $this->where("company_order_number = '".$params['company_order_number']."' and customer_id =".$params['customer_id'])->update($data);
    	

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