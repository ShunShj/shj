<?php

namespace app\index\model\branchcompany;
use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use think\config;
use think\Db;
class CompanyOrderCustomer extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'company_order_customer';
    private $_languageList;
    private $_public_service;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        $this->_public_service = new PublicService();
        parent::initialize();

    }

    /**
     * 添加分公司 订单-游客
     * 胡
     */
    public function addCompanyOrderCustomer($params){

        $t = time();

        $data['customer_id'] = $params['customer_id'];
        $data['company_order_number'] = $params['company_order_number'];


		if(!empty($params['special_claim'])){
			$data['special_claim'] = $params['special_claim'];
		}


        
        $data['create_time'] = $t;
        $data['create_user_id'] = $params['now_user_id'];
        $data['update_time'] = $t;
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
    	$user_id = $params['now_user_id'];
    	$order_number = $params['company_order_number'];
    	$sql =  "insert into company_order_customer (company_order_number,customer_id,create_time,create_user_id,update_time,update_user_id,status) values";
    	
    	
    	$this->startTrans();
    	try{
    		for($i=0;$i<$params['occupy_count'];$i++){
    			$sql.="('$order_number',0,$t,$user_id,$t,$user_id,2),";
    			
    			
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
     * 通过顾客ID获取订单编号
     */
    public function getCompanyOrderNumberByCompanyOrderCustomerId($params){
    	
    	if(!empty($params['company_order_customer_id'])){
    		$data= " company_order_customer_id = ".$params['company_order_customer_id'];
    	}

    	$result= $this->
    	where($data)->
    	field([
    			'company_order_number',

    			'status'])->
    	
    			select();
    
    	return $result;
    }
    /**
     * 获取顾客数据
     * 胡
     */
    public function getCompanyOrderCustomer($params){

 
    	$data = "1=1 and coc.status >0";
    	if(!empty($params['company_order_customer_id'])){
    		$data.= " and coc.company_order_customer_id = ".$params['company_order_customer_id'];
    	}
    	if(!empty($params['customer_id'])){
    		$data.= " and coc.customer_id = ".$params['customer_id'];
    	}
    	if(!empty($params['company_order_number'])){
    		$data.= " and coc.company_order_number = '".$params['company_order_number']."'";
    	}
 
   	
            $result= $this->table("company_order_customer")->alias("coc")->
            join("customer customer","customer.customer_id = coc.customer_id",'left')->
            join("country country","customer.country_id = country.country_id",'left')->
            join("company company","company.company_id = customer.company_id",'left')->
            join("language language","customer.language_id = language.language_id",'left')->
            join("company_order_accommodation coa","coa.company_order_number = coc.company_order_number and coa.company_order_customer_id = coc.company_order_customer_id",'left')->
            join("room_type",'room_type.room_type_id = coa.room_type','left')->
            where($data)->
            field(['coc.customer_id','coc.company_order_number','coc.company_order_customer_id','coc.special_claim',
            		"if(coc.customer_id=0,'占位',concat(customer.customer_first_name,' ',customer.customer_last_name)) customer_name",
            		'company.company_id','company.company_name',
            		'customer.customer_number',
            		'customer.passport_number','customer.emergency_contact','customer.emergency_call','customer.address',
            		'customer.customer_first_name','customer.customer_last_name','customer.middle_name',
            		'customer.english_first_name','customer.english_last_name',
            		'customer.customer_type','customer.gender','customer.phone',
            		'customer.email','customer.card_type','customer.card_number',
            		'customer.issuing_date','customer.term_of_validity','customer.birthday','customer.remark',
            		'customer.language_id','language.language_name',
            		'country.country_id as country_id','country.country_name as country_name',
            		'coa.check_in_hotel','coa.check_on_hotel',
            		"coa.room_code",'coa.room_type','coa.check_in','coa.check_on',
            		
            		"(select nickname  from user where user.user_id = customer.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = customer.update_user_id)"=>'update_user_name',
            		'coc.create_user_id','coc.create_time','coc.update_user_id','room_type.room_type_name','room_type.accommodate',
            		'coc.update_time','coc.status'])->
            
            select();
            
    
        return $result;

    }

    /**
     * 通过团队产品编号获取所有游客信息
     */

    public function getCompanyOrderCustomerByTeamProductId($params){
    
    
    	$data = "1=1 and copt.status >0 and co.status>0 and coc.status>0";
    	if(!empty($params['team_product_id'])){
    		$data.= " and copt.team_product_id = ".$params['team_product_id'];
    	}
    
    	
    	$result= $this->table('company_order_product_team')->alias('copt')->
    	join("company_order co",'co.company_order_number = copt.company_order_number ','left')->
    	join("company_order_customer coc",'coc.company_order_number = co.company_order_number and coc.status>0','right')->
    	join("customer customer","customer.customer_id = coc.customer_id",'left')->
     	join("country country","customer.country_id = country.country_id",'left')->
//     	join("company company","company.company_id = customer.company_id",'left')->
     	join("language language","customer.language_id = language.language_id",'left')->
    	join("company_order_accommodation coa","coa.company_order_customer_id = coc.company_order_customer_id ",'left')->
    	join('`room_type` rt','rt.room_type_id = coa.room_type','left')->
    	where($data)->
    	field(['coc.customer_id','coc.company_order_customer_id','coc.special_claim','co.company_order_number',
    			"if(coc.customer_id=0,'占位',concat(customer.customer_first_name,' ',customer.customer_last_name)) customer_name",
    			//'company.company_id','company.company_name',
    			'customer.customer_number',
    			'customer.passport_number','customer.emergency_contact','customer.emergency_call','customer.address',
    			'customer.customer_first_name','customer.customer_last_name','customer.middle_name',
    			'customer.english_first_name','customer.english_last_name',
    			'customer.customer_type','customer.gender','customer.phone',
    			'customer.email','customer.card_type','customer.card_number',
    			'customer.issuing_date','customer.term_of_validity','customer.birthday','customer.remark',
    			'customer.language_id','language.language_name',
    			'country.country_id as country_id','country.country_name as country_name',
    			'coa.check_in_hotel','coa.check_on_hotel',
    			"coa.room_code",'coa.room_type','coa.check_in','coa.check_on',
				'rt.room_type_name',
    
    			"(select nickname  from user where user.user_id = customer.create_user_id)"=>'create_user_name',
    			"(select nickname  from user where user.user_id = customer.update_user_id)"=>'update_user_name',
    			'coc.create_user_id','coc.create_time','coc.update_user_id',
    			'coc.update_time','coc.status'])->
    
    			select();
		
    	return $result;
    
    }    
	/**
	 * 获取订单下的顾客信息
	 */
	 public function getCompanyOrderCustomerByCompanyOrderNumber($params){
	 	$data = "1=1  and coc.status>0";

	 	if(isset($params['company_order_number'])){
	 		$data.= " and coc.company_order_number = '".$params['company_order_number']."'";
	 	}

	 	
	 	$result= $this->table("company_order_customer")->alias("coc")->
	 	join("customer customer","customer.customer_id = coc.customer_id",'left')->

	 	where($data)->
	 	field(['company_order_customer.company_order_customer_id','company_order_customer.customer_id',
	 			
	 			'customer.customer_first_name','customer.customer_last_name',
	 			'customer.english_first_name','customer.english_last_name',
	 			"(select nickname  from user where user.user_id = customer.create_user_id)"=>'create_user_name',
	 			"(select nickname  from user where user.user_id = customer.update_user_id)"=>'update_user_name',
	 			'customer.create_user_id','customer.create_time','customer.update_user_id',
	 			'customer.update_time','coc.status'])->
	 	
	 			select();
	 	
	 	
	 	return $result;	 	
	 	
	 }
    /**
     * 修改顾客 根据customer_id
     */
    public function updateCompanyOrderCustomerByCompanyOrderCustomerId($params){

        $t = time();
        

   

        if(isset($params['customer_id'])){
        	$data['customer_id'] = $params['customer_id'];
        	$data['status']=1;
        	
        }




        if(is_numeric($params['status'])){
            $data['status'] = $params['status'];

        }
		if(isset($params['special_claim'])){
			$data['special_claim'] = $params['special_claim'];
		}

        $data['update_user_id'] = $params['now_user_id'];

        $data['update_time'] = $t;



        Db::startTrans();
        try{
            Db::name('company_order_customer')->where("company_order_customer_id = ".$params['company_order_customer_id'])->update($data);

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
	//修改游客状态
	public function updateCompanyOrderCustomerStatusByCompanyOrderCustomerId($params){
		$t = time();
		

		
		
		
		
	
		$data['status'] = $params['status'];

		
		
		$data['update_user_id'] = $params['now_user_id'];
		
		$data['update_time'] = $t;
		
		
	
		
		Db::startTrans();
		try{
			Db::name('company_order_customer')->where("company_order_customer_id = ".$params['company_order_customer_id'])->update($data);
		
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
	 * 获取所有订单的收客人数
	 */
	public function getAllCompanyOrderCustomer($params){
		$data="1=1 and company_order_customer.status>0 and co.status=1";
		if(!empty($params['create_time_day'])){
			$data.=" and from_unixtime(company_order_customer.create_time,'%Y%m%d') = '".$params['create_time_day']."'";
		}
		if(!empty($params['create_time_month'])){
			$data.=" and from_unixtime(company_order_customer.create_time,'%Y%m') = '".$params['create_time_month']."'";
		}
		if(!empty($params['company_id'])){
			$data.=" and co.company_id = ".$params['company_id'];
		}
		if(!empty($params['create_time_diy_day'])){
			$data.=" and from_unixtime(company_order_customer.create_time,'%Y%m%d') >=".$params['create_time_diy_day'];
		}
		
		$result= $this->table("company_order")->alias("co")->
		join("company_order_customer",'company_order_customer.company_order_number = co.company_order_number','left')->
		join("customer customer","customer.customer_id = company_order_customer.customer_id",'left')->
		
		where($data)->
		field(['company_order_customer.company_order_customer_id','company_order_customer.customer_id',
			 	
				'customer.customer_first_name','customer.customer_last_name',
				'customer.english_first_name','customer.english_last_name',
				"(select nickname  from user where user.user_id = customer.create_user_id)"=>'create_user_name',
				"(select nickname  from user where user.user_id = customer.update_user_id)"=>'update_user_name',
				'customer.create_user_id','company_order_customer.create_time','customer.update_user_id',
				"from_unixtime(company_order_customer.create_time,'%Y-%m-%d') as from_unixtime_time",
				'customer.update_time','company_order_customer.status'])->
				 
				select();
		 
		
		return $result;
	}
}