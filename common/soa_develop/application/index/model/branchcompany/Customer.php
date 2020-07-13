<?php

namespace app\index\model\branchcompany;
use think\Exception;
use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use think\config;
use think\Db;
class Customer extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'customer';
    private $_languageList;
    private $_public_service;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        $this->_public_service = new PublicService();
        parent::initialize();

    }

    /**
     * 添加顾客
     * 胡
     */
    public function addCustomer($params){

        $t = time();
       	$data['customer_number'] = $params['customer_number'];
        $data['customer_first_name'] = $params['customer_first_name'];
        $data['customer_last_name'] = $params['customer_last_name'];
        if(!empty($params['english_first_name'])){
            $data['english_first_name'] = $params['english_first_name'];
        }
        if(!empty($params['english_last_name'])){
            $data['english_last_name'] = $params['english_last_name'];
        }
        if(!empty($params['customer_type'])){
        	$data['customer_type'] = $params['customer_type'];
        }


        if(!empty($params['phone'])){
            $data['phone'] = $params['phone'];
        }

        if(!empty($params['gender'])){
            $data['gender'] = $params['gender'];
        }
        if(!empty($params['email'])){
            $data['email'] = $params['email'];
        }
        if(!empty($params['language_id'])){
            $data['language_id'] = $params['language_id'];
        }
        if(!empty($params['issuing_date'])){
        	$data['issuing_date'] = $params['issuing_date'];
        }        
        if(!empty($params['term_of_validity'])){
            $data['term_of_validity'] = $params['term_of_validity'];
        }
        if(!empty($params['passport_number'])){
        	$data['passport_number'] = $params['passport_number'];
        }
        if(!empty($params['emergency_contact'])){
        	$data['emergency_contact'] = $params['emergency_contact'];
        }
        if(!empty($params['emergency_call'])){
        	$data['emergency_call'] = $params['emergency_call'];
        }
        if(!empty($params['address'])){
        	$data['address'] = $params['address'];
        }        
        if(!empty($params['middle_name'])){
        	$data['middle_name'] = $params['middle_name'];
        }
        if(!empty($params['country_id'])){
            $data['country_id'] = $params['country_id'];
        }
        if(!empty($params['card_type'])){
        	$data['card_type'] = $params['card_type'];
        }
        if(!empty($params['card_number'])){
        	$data['card_number'] = $params['card_number'];
        }

        if(!empty($params['birthday'])){
        	$data['birthday'] = $params['birthday'];
        }
        if(!empty($params['remark'])){
        	$data['remark'] = $params['remark'];
        }
        
        $data['company_id'] = $params['choose_company_id'];
        
        $data['create_time'] = $t;
        $data['create_user_id'] = $params['now_user_id'];
        $data['update_time'] = $t;
        $data['update_user_id'] = $params['now_user_id'];
        $data['status'] = $params['status'];


        //b2b游客信息
        if(!empty($params['birthday'])){
            $data['birthday'] = $params['birthday'];
        }
        if(!empty($params['age_group'])){
            $data['age_group'] = $params['age_group'];
        }
        if(!empty($params['speaking_fluent'])){
            $data['speaking_fluent'] = $params['speaking_fluent'];
        }
        if(!empty($params['ethnicity'])){
            $data['ethnicity'] = $params['ethnicity'];
        }
        if(!empty($params['nationality'])){
            $data['nationality'] = $params['nationality'];
        }

        Db::startTrans();
        try{
            $result = Db::name('customer')->insertGetId($data);
            $this->_public_service->setNumber('customer', 'customer_id', $result, 'customer_number',Help::getNumber(7), $result);
       
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
     * 获取顾客数据
     * 胡
     */
    public function getCustomer($params,$is_count=false){//第一个为参数，第二个为是否要获取 总数

 
    	$data = "1=1";
    	if(!empty($params['customer_id'])){
    		$data.= " and customer.customer_id = ".$params['customer_id'];
    	}
    	if(is_numeric($params['status'])){
    		$data.= " and customer.status = ".$params['status'];
    	}
    	if(!empty($params['customer_first_name'])){
    		$data.= " and customer.customer_first_name = '".$params['customer_first_name']."'";
    	}
    	if(!empty($params['customer_last_name'])){
    		$data.= " and customer.customer_last_name = '".$params['customer_last_name']."'";
    	}
    	if(!empty($params['english_first_name'])){
    		$data.= " and customer.english_first_name = '".$params['english_first_name']."'";
    	}
    	if(!empty($params['english_last_name'])){
    		$data.= " and customer.english_last_name = '".$params['english_last_name']."'";
    	}
    	if(!empty($params['phone'])){
    		$data.= " and customer.phone = '".$params['phone']."'";
    	}
    	if(!empty($params['card_type'])){
    		$data.= " and customer.card_type = '".$params['card_type']."'";
    	}
    	if(!empty($params['card_number'])){
    		$data.= " and customer.card_number = '".$params['card_number']."'";
    	}
        if(is_numeric($params['company_id'])){
            $data.= " and customer.company_id = '".$params['company_id']."'";
        }
        if(!empty($params['passport_number'])){
        	$data.= " and customer.passport_number = '".$params['passport_number']."'";
        }
        if(!empty($params['customer_number'])){
        	$data.= " and customer.customer_number = '".$params['customer_number']."'";
        }

        if (!empty($params['customer_name']))
        {
            $customer_name = trim($params['customer_name']);
            //$data.= " and (customer.customer_first_name like '%". $customer_name ."%'". " or customer.customer_last_name like '%". $customer_name ."%'". " or customer.english_first_name like '%". $customer_name ."%'". " or customer.english_last_name like '%". $customer_name."%')";
            $data.= " and (CONCAT(customer_first_name,customer_last_name) like '%". $customer_name ."%' or CONCAT(english_first_name,english_last_name) like '%". $customer_name ."%')";
        }

        if($is_count==true){

            $result = $this->
            field([
                '(SELECT CONCAT(customer_first_name,customer_last_name))',
                '(SELECT CONCAT(english_first_name,english_last_name))'
            ])->where($data)->count();

        }else{
            if(is_numeric($params['page'])){
                $result= $this->table("customer")->alias("customer")->
                join("country country","customer.country_id = country.country_id",'left')->
                join("company company","company.company_id = customer.company_id",'left')->
                join("language language","customer.language_id = language.language_id",'left')->

                field(['customer.customer_id','customer.customer_number',
                    'company.company_id','company.company_name',

                    'customer.customer_first_name','customer.customer_last_name',
                    'customer.english_first_name','customer.english_last_name',
                    'customer.birthday',
                    'customer.customer_type','customer.gender','customer.phone','customer.country_id',
                    'customer.email','customer.card_type','customer.card_number',
                    'customer.issuing_date','customer.term_of_validity','customer.remark',
                    'customer.language_id','language.language_name',
                    'country.country_id as country_id','country.country_name as country_name',
                    'customer.passport_number','customer.middle_name','customer.emergency_contact','customer.emergency_call','customer.address',
                    "(select nickname  from user where user.user_id = customer.create_user_id)"=>'create_user_name',
                    "(select nickname  from user where user.user_id = customer.update_user_id)"=>'update_user_name',
                    'customer.create_user_id','customer.create_time','customer.update_user_id',
                    'customer.update_time','customer.status',
                    '(SELECT CONCAT(customer_first_name,customer_last_name))',
                    '(SELECT CONCAT(english_first_name,english_last_name))'
                ])->
                where($data)->order('create_time asc')->limit($params['page'],$params['page_size'])->
                select();
            }else{
                $result= $this->table("customer")->alias("customer")->
                join("country country","customer.country_id = country.country_id",'left')->
                join("company company","company.company_id = customer.company_id",'left')->
                join("language language","customer.language_id = language.language_id",'left')->

                field(['customer.customer_id','customer.customer_number',
                   'company.company_id','company.company_name',

                   'customer.customer_first_name','customer.customer_last_name',
                   'customer.english_first_name','customer.english_last_name',
                   'customer.birthday',
                   'customer.customer_type','customer.gender','customer.phone','customer.country_id',
                   'customer.email','customer.card_type','customer.card_number',
                   'customer.issuing_date','customer.term_of_validity','customer.remark',
                   'customer.language_id','language.language_name',
                   'country.country_id as country_id','country.country_name as country_name',
                   'customer.passport_number','customer.middle_name','customer.emergency_contact','customer.emergency_call','customer.address',
                    "(select nickname  from user where user.user_id = customer.create_user_id)"=>'create_user_name',
                    "(select nickname  from user where user.user_id = customer.update_user_id)"=>'update_user_name',
                    'customer.create_user_id','customer.create_time','customer.update_user_id',
                    'customer.update_time','customer.status',
                    '(SELECT CONCAT(customer_first_name,customer_last_name))',
                    '(SELECT CONCAT(english_first_name,english_last_name))'
                ])->
                where($data)->order('create_time asc')->
                select();
            }
        }


        return $result;

    }


    /**
     * 修改顾客 根据customer_id
     */
    public function updateCustomerByCustomerId($params){

        $t = time();
        

        if(!empty($params['customer_first_name'])){
        	$data['customer_first_name'] = $params['customer_first_name'];
        
        }
        if(!empty($params['customer_last_name'])){
        	$data['customer_last_name'] = $params['customer_last_name'];
        
        }
        if(!empty($params['english_first_name'])){
        	$data['english_first_name'] = $params['english_first_name'];
        
        }
        if(!empty($params['english_last_name'])){
        	$data['english_last_name'] = $params['english_last_name'];
        
        }
        if(!empty($params['customer_type'])){
        	$data['customer_type'] = $params['customer_type'];
        
        }

        if(!empty($params['gender'])){
            $data['gender'] = $params['gender'];
        }
        if(!empty($params['email'])){
            $data['email'] = $params['email'];
        }
        if(!empty($params['phone'])){
            $data['phone'] = $params['phone'];
        }
        if(isset($params['language_id'])){
            $data['language_id'] = $params['language_id'];
        }
        if(!empty($params['issuing_date'])){
        	$data['issuing_date'] =$params['issuing_date'];
        }
        if(!empty($params['term_of_validity'])){
            $data['term_of_validity'] = $params['term_of_validity'];
        }
        if(!empty($params['emergency_contact'])){
        	$data['emergency_contact'] = $params['emergency_contact'];
        }
        if(!empty($params['emergency_call'])){
        	$data['emergency_call'] = $params['emergency_call'];
        }
        if(!empty($params['address'])){
        	$data['address'] = $params['address'];
        }
        if(isset($params['country_id'])){
            $data['country_id'] = $params['country_id'];
        }
        if(!empty($params['card_type'])){
        	$data['card_type'] = $params['card_type'];
        	
        }
        if(!empty($params['card_number'])){
        	$data['card_number'] = $params['card_number'];

        }
        if(!empty($params['passport_number'])){
        	$data['passport_number'] = $params['passport_number'];
        	 
        }
        if(!empty($params['middle_name'])){
        	$data['middle_name'] = $params['middle_name'];
        
        }
        
        if(!empty($params['birthday'])){
        	$data['birthday'] = $params['birthday'];
        
        }
        if(!empty($params['remark'])){
        	$data['remark'] = $params['remark'];
        	 
        }


        if(is_numeric($params['status'])){
            $data['status'] = $params['status'];

        }


        $data['update_user_id'] = $params['now_user_id'];

        $data['update_time'] = $t;

        //b2b游客信息
        if(!empty($params['birthday'])){
            $data['birthday'] = $params['birthday'];
        }
        if(!empty($params['age_group'])){
            $data['age_group'] = $params['age_group'];
        }
        if(!empty($params['speaking_fluent'])){
            $data['speaking_fluent'] = $params['speaking_fluent'];
        }
        if(!empty($params['ethnicity'])){
            $data['ethnicity'] = $params['ethnicity'];
        }
        if(!empty($params['nationality'])){
            $data['nationality'] = $params['nationality'];
        }
	

        Db::startTrans();
        try{
            Db::name('customer')->where("customer_id = ".$params['customer_id'])->update($data);

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

    public function getOneCustomer($customer_id){
        $result = $this->table("customer")->where(['customer_id' => $customer_id])->find();
        return $result;
    }

    public function getOneCustomerByPassport($passport_number)
    {
        return $this->table("customer")->where(['passport_number' => $passport_number])->find();
    }

}