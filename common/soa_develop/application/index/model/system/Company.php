<?php

namespace app\index\model\system;
use app\index\model\source\Supplier;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class Company extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'company';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加公司
     * 胡
     */
    public function addCompany($params){

        $t = time();
       
        $data['company_name'] = $params['company_name'];
        if(isset($params['linkman'])){
            $data['linkman'] = $params['linkman'];
        }
        if(isset($params['phone'])){
            $data['phone'] = $params['phone'];
        }
        if(isset($params['currency_id'])){
            $data['currency_id'] = $params['currency_id'];
        }
        if(isset($params['timezone'])){
            $data['timezone'] = $params['timezone'];
        }
        if(isset($params['language_id'])){
            $data['language_id'] = $params['language_id'];
        }
        if(isset($params['country_id'])){
            $data['country_id'] = $params['country_id'];
        }
        if(isset($params['booking_identification'])){
        	$data['booking_identification'] = $params['booking_identification'];
        }        
        if(isset($params['address'])){
        	$data['address'] = $params['address'];
        }        
        $data['is_supplier'] = $params['is_supplier'];
        $data['create_time'] = $t;
        $data['create_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        $data['update_user_id'] = $params['user_id'];
        $data['status'] = 1;

        Db::startTrans();
        try{
            $id = Db::name('company')->insertGetId($data);

            if($data['is_supplier'] == 1){
                $supplier['supplier_name'] = $params['company_name'];
                $supplier['supplier_type_id'] = 1;
                $supplier['country_id'] = $params['country_id'];
                $supplier['supplier_number'] = Help::getNumber(51);
                $supplier['choose_company_id'] = $id;
                $supplier['is_company'] = 1;
                $supplier['create_time'] = $t;
                $supplier['update_time'] = $t;
                $supplier['now_user_id'] = $params['user_id'];
                $supplier['status'] = 1;
                $supplierM = new Supplier();
                $supplierM->addSupplier($supplier);
            }
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
     * 获取公司数据
     * 胡
     */
    public function getCompany($params,$is_count=false){//第一个为参数，第二个为是否要获取 总数

 
    	$data = "1=1";
    	if(isset($params['company_id'])){
    		$data.= " and company.company_id = ".$params['company_id'];
    	}
    	if(is_numeric($params['status'])){
    		$data.= " and company.status = ".$params['status'];
    	}
    	if(isset($params['company_name'])){
    		$data.= " and company.company_name like'%".$params['company_name']."%'";
    	}
    	if(isset($params['language_id'])){
    		$data.= " and language.language_id = ".$params['language_id'];
    	}
    	if(isset($params['currency_id'])){
    		$data.= " and currency.currency_id = ".$params['currency_id'];
    	}
    	if(isset($params['linkman'])){
    		$data.= " and company.linkman = '".$params['linkman']."'";
    	}   
    	if(isset($params['phone'])){
    		$data.= " and company.phone = '".$params['phone']."'";
    	}
    	if(isset($params['role_id'])){
    		$data.= " and company.role_id = ".$params['role_id'];
    	}
    	if($is_count==true){
    		$result = $this->where($data)->count();
    		 
    	}else{
            if(isset($params['page'])){
                $result= $this->table("company")->alias("company")->
                join("country country","company.country_id = country.country_id",'left')->
                join("currency currency","company.currency_id = currency.currency_id",'left')->
                join("language language","company.language_id = language.language_id",'left')->
                where($data)->order('create_time desc')->limit($params['page'],$params['page_size'])->
                field(['company.company_id','company.company_name','company.phone','company.linkman','company.country_id','country.country_name',
                    'company.timezone','company.language_id','currency_name','company.currency_id','language.language_name',
                    'company.is_supplier','company.booking_identification','company.address','currency.unit',
                    'company.create_user_id','company.create_time','company.update_user_id','company.update_time','company.status'])
                    ->order("company.create_time desc")->

                    select();

    		}else{
	            $result= $this->table("company")->alias("company")->
	            join("country country","company.country_id = country.country_id",'left')->
	            join("currency currency","company.currency_id = currency.currency_id",'left')->
	            join("language language","company.language_id = language.language_id",'left')->
	            where($data)->
	            field(['company.company_id','company.company_name','company.phone','company.linkman','company.country_id','country.country_name',
	            		'company.timezone','company.language_id','currency_name','company.currency_id','language.language_name','company.create_user_id','company.create_time','company.update_user_id','company.update_time','company.is_supplier',
	            		"(select nickname  from user where user.user_id = company.create_user_id)"=>'create_user_name',
	            		"(select nickname  from user where user.user_id = company.update_user_id)"=>'update_user_name',
	            		'company.booking_identification','company.address','currency.unit',
	            		'company.status'])->order("company.create_time desc")->
	            select();
    			 
    		}
    		 
    	}
	
        return $result;

    }

	/**
	 * 获取公司根据公司多个ID
	 */
    public function getCompanyByCompanysId($params){
    	$data['company_id'] = array('in',$params['use_company_id']);
    	$result = $this->where($data)->field(['company.company_id','company.company_name','company.phone','company.linkman','company.country_id','company.timezone','company.language_id','company.currency_id','company.create_user_id','company.create_time','company.update_user_id','company.update_time','company.status','company.is_supplier'])
            ->select();
   
    	return $result;
    	
    	
    	
    }
    /**
     * 修改公司 根据company_id
     */
    public function updateCompanyByCompanyId($params){

        $t = time();
        
        if(!empty($params['company_name'])){
        	$data['company_name'] = $params['company_name'];
        }
        if(isset($params['linkman'])){
            $data['linkman'] = $params['linkman'];
        }
        if(isset($params['phone'])){
            $data['phone'] = $params['phone'];
        }
        if(isset($params['currency_id'])){
            $data['currency_id'] = $params['currency_id'];
        }
        if(isset($params['timezone'])){
            $data['timezone'] = $params['timezone'];
        }
        if(isset($params['language_id'])){
            $data['language_id'] = $params['language_id'];
        }
        if(isset($params['country_id'])){
            $data['country_id'] = $params['country_id'];
        }
        if(!empty($params['status'])){
            $data['status'] = $params['status'];
        }
        if(!empty($params['booking_identification'])){
        	$data['booking_identification'] = $params['booking_identification'];
        }
        if(isset($params['address'])){
        	$data['address'] = $params['address'];
        }        
        $data['is_supplier'] = $params['is_supplier'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
		
        Db::startTrans();
        try{
            Db::name('company')->where("company_id = ".$params['company_id'])->update($data);

            if($data['is_supplier'] == 1 && $params['have_supplier'] == 0){
                $supplier['supplier_name'] = $params['company_name'];
                $supplier['supplier_type_id'] = 1;
                $supplier['country_id'] = $params['country_id'];
                $supplier['supplier_number'] = Help::getNumber(51);
                $supplier['choose_company_id'] = $params['company_id'];
                $supplier['is_company'] = 1;
                $supplier['create_time'] = $t;
                $supplier['update_time'] = $t;
                $supplier['now_user_id'] = $params['user_id'];
                $supplier['status'] = 1;
                $supplierM = new Supplier();
                $supplierM->addSupplier($supplier);
            }elseif($data['is_supplier'] == 1 && $params['have_supplier'] == 1 && $params['is_company'] == 0){
                $supplier['is_company'] = 1;
                $supplier['status'] = 1;
                $supplier['supplier_name'] = $params['company_name'];
                Db::name('supplier')->where("supplier_id = ".$params['supplier_id'])->update($supplier);
            }elseif($data['is_supplier'] == 0 && $params['have_supplier'] == 1 && $params['is_company'] == 1){
                $supplier['is_company'] = 0;
                $supplier['status'] = 2;
                $supplier['supplier_name'] = $params['company_name'];
                Db::name('supplier')->where("supplier_id = ".$params['supplier_id'])->update($supplier);
            }
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
     * getOneCompany
     *
     * 获取一条公司信息
     * @author shj
     *
     * @param $company_id
     *
     * @return void
     * Date: 2019/2/28
     * Time: 10:59
     */
    public function getOneCompany($company_id){
        $result = $this->table("company")->where(['company_id' => $company_id])->find();
        return $result;
    }
}