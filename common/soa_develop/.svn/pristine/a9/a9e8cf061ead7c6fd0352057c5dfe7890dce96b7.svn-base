<?php

namespace app\index\model\branchcompany;
use think\Model;
use app\common\help\Help;
use app\index\model\branchcompany\CompanyOrderProductSource;
use app\index\model\branchcompany\CompanyOrderProductDiy;
use app\index\model\finance\ReceivableCustomer;
use think\config;
use think\Db;
class CompanyOrderRelation extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'company_order_relation';
    private $_company_order_product_source;
    private $_company_order_product_diy;
    private $_languageList;
    private $_receivable_customer;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        $this->_company_order_product_diy = new CompanyOrderProductDiy();
        $this->_company_order_product_source = new CompanyOrderProductSource();
        $this->_receivable_customer = new ReceivableCustomer();
        parent::initialize();

    }

    /**
     * 添加公司订单的顾客关联
     * 胡
     */
    public function addCompanyOrderRelation($params){
		if($params['company_order_customer']==''){
			return 1;
			exit();
		}
		
        $t = time();
		$user_id = $params['now_user_id'];
		$receivable_number = '';
		$company_order_number = $params['company_order_number'];
        if(!empty($params['company_order_product_source_id'])){
        	
        	$company_order_product_source_id=$params['company_order_product_source_id'];
        	
        	//开始查询应收编号
        	$source_params['company_order_product_source_id'] = $params['company_order_product_source_id'];
        	$source_result = $this->_company_order_product_source->getCompanyOrderProductSource($source_params);
        	$receivable_number = $source_result[0]['receivable_number'];
        	
        }else{
        	$company_order_product_source_id=0;
        }
        if(!empty($params['company_order_product_diy_id'])){
        	
        	$company_order_product_diy_id=$params['company_order_product_diy_id'];
        	
        	
        }else{
        	$company_order_product_diy_id=0;
        }
       
		$sql = "insert into company_order_relation(company_order_number,company_order_customer_id,company_order_product_source_id,company_order_product_diy_id,create_time,update_time,create_user_id,update_user_id,status) values";
		
		$company_order_customer_array = explode(",",$params['company_order_customer']);
		
 		for($i=0;$i<count($company_order_customer_array);$i++){
 			
			$customer_id = $company_order_customer_array[$i];
			
 			$sql.="('$company_order_number',$customer_id,'$company_order_product_source_id',$company_order_product_diy_id,$t,$t,$user_id,$user_id,1),";
 		}
		$sql = trim($sql,',');
		
		
		
        $this->startTrans();
        try{
			//首先先把当前游客设置为空
        	$this->execute($sql);
        	if(!empty($receivable_number)){
        		$receivable_number_params = [
        			'receivable_number'=>$receivable_number,
        			'company_order_customer_id'=>$params['company_order_customer'],
        			'now_user_id'=>$user_id	
        		];
        		$this->_receivable_customer->addReceivableCustomer($receivable_number_params);
        	}
        	//开始判断是否是自定义还是资源
        	if(!empty($params['company_order_product_diy_id'])){
        	
        		//首先获取此资源的信息
        		$company_order_product_params = [
        			'company_order_product_diy_id'=>$params['company_order_product_diy_id'],
        				 
        		];
        	
        		$company_order_product_result = $this->_company_order_product_diy->getCompanyOrderProductDiy($company_order_product_params);
        	
        		$company_order_product_params['diy_cost'] = count($company_order_customer_array)*$company_order_product_result[0]['diy_cost_univalence'];
        		$company_order_product_params['company_order_product_diy_number'] = $company_order_product_result[0]['company_order_product_diy_number'];
        		
        		$this->_company_order_product_diy->updateCompanyOrderProductDiyByDiyNumber($company_order_product_params);
        		
        	}else{
        		//首先获取此资源的信息
        		$company_order_product_params = [
        			'company_order_product_source_id'=>$params['company_order_product_source_id'],
        			
        		];
        
        		$company_order_product_result= $this->_company_order_product_source->getCompanyOrderProductSource($company_order_product_params);
        		
        		
        		$company_order_product_params['source_cost'] = count($company_order_customer_array)*$company_order_product_result[0]['source_cost_univalence'];
        		
        		$this->_company_order_product_source->updateCompanyOrderSource($company_order_product_params);
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
     * 获取公司订单游客关联
     * 胡
     */
    public function getCompanyOrderRelation($params){

 
    	$data = "1=1 and company_order_customer.status >0";
    	if(isset($params['company_order_number'])){
    		$data.= " and company_order_relation.company_order_number = '".$params['company_order_number']."'";
    	}
    	if(isset($params['company_order_product_source_id'])){
    		$data.= " and company_order_relation.company_order_product_source_id = '".$params['company_order_product_source_id']."'";
    	}
    	if(isset($params['company_order_product_diy_id'])){
    		$data.= " and company_order_relation.company_order_product_diy_id = '".$params['company_order_product_diy_id']."'";
    	}	
    	if(is_numeric($params['status'])){
    		$data.= " and company_order_relation.status = ".$params['status'];
    	}

    	
            $result= $this->table("company_order_relation")->alias("company_order_relation")->
          
			join("company_order_customer",'company_order_customer.company_order_customer_id = company_order_relation.company_order_customer_id and company_order_customer.status>0 and company_order_customer.company_order_number='."'".$params['company_order_number']."'",'left')->
			join("customer customer","customer.customer_id = company_order_customer.customer_id",'left')->
			join("company_order_accommodation","company_order_accommodation.company_order_customer_id= company_order_relation.company_order_customer_id",'left')->
            where($data)->
            field([
            		'company_order_relation_id','company_order_relation.company_order_customer_id',
            		'customer.customer_first_name','customer.customer_last_name','customer.english_first_name','customer.english_last_name',
                 	'customer.gender','company_order_accommodation.room_type',
     
            		
            		"if(company_order_customer.customer_id=0,'占位',concat(customer.customer_first_name,' ',customer.customer_last_name)) customer_name",
            		"(select nickname  from user where user.user_id = company_order_relation.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = company_order_relation.update_user_id)"=>'update_user_name',
            		'company_order_relation.create_user_id','company_order_relation.update_user_id','company_order_relation.create_time',
            		'company_order_relation.update_time','company_order_relation.status'])->
            
            select();

       

        return $result;

    }


    /**
     * 修改公司订单游客关联
     */
    public function updateCompanyOrderRelation($params){

        $t = time();
        

        $data['status'] = 0;

    
		

        $data['update_user_id'] = $params['user_id'];

        $data['update_time'] = $t;
		
        $where =  [
        	'company_order_number'=>$params['company_order_number']	
        		
        ];
        if(!empty($params['company_order_product_source_id'])){
        	$where =[
        		'company_order_product_source_id'=>$params['company_order_product_source_id']	
        	];
        }
        if(!empty($params['company_order_product_diy_id'])){
        	$where =[
        		'company_order_product_diy_id'=>$params['company_order_product_diy_id']
        	];
        }
        


        $this->startTrans();
        try{
            $this->where($where)->update($data);

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