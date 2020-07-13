<?php

namespace app\index\model\branchcompany;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class CompanyOrderProduct extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'company_order_product';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加公司订单的分公司产品
     * 胡
     */
    public function addCompanyOrderProduct($params){

        $t = time();

		$data['company_order_number'] = $params['company_order_number'];
		
		$data['branch_product_number'] = $params['branch_product_number'];
		$data['branch_product_name'] = $params['branch_product_name'];
		if(is_numeric($params['branch_product_cost'])){
			$data['branch_product_cost'] = $params['branch_product_cost'];
		}
		if(is_numeric($params['branch_product_price'])){
			$data['branch_product_price'] = $params['branch_product_price'];
		}
        if(!empty($params['price_currency_id'])){
            $data['price_currency_id'] = $params['price_currency_id'];
        }
        if(!empty($params['cost_currency_id'])){
            $data['cost_currency_id'] = $params['cost_currency_id'];
        }
        if(!empty($params['supplier_name'])){
            $data['supplier_name'] = $params['supplier_name'];
        }
        if(is_numeric($params['price_before_tax'])){
        	$data['price_before_tax'] = $params['price_before_tax'];
        }
        $data['create_time'] = $t;
        $data['create_user_id'] = $params['now_user_id'];
        $data['update_time'] = $t;
        $data['update_user_id'] = $params['now_user_id'];
        $data['status'] = $params['status'];
        $this->startTrans();
        try{
			
        	$pk_id = $this->insertGetId($data);
        	
        	$result = $pk_id;
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
     * 获取公司订单的分公司产品
     * 胡
     */
    public function getCompanyOrderProduct($params){

 
    	$data = "1=1";
    	if(isset($params['company_order_number'])){
    		$data.= " and company_order_product.company_order_number = '".$params['company_order_number']."'";
    	}
    	if(isset($params['branch_product_number'])){
    		$data.= " and company_order_product.branch_product_number = '".$params['branch_product_number']."'";
    	}
    	if(!empty($params['receivable_number'])){
    		$data.= " and company_order_product.receivable_number = '".$params['receivable_number']."'";
    	}
    	if(is_numeric($params['status'])){
    		$data.= " and company_order_product.status = ".$params['status'];
    	}
    	if(is_numeric($params['utc_substribe_time'])){
    		$data.= " and FROM_UNIXTIME(company_order_product.utc_substribe_time,'%Y%m%d%H')= ".$params['utc_substribe_time'];
    	}   	
    	
    	
       $result= $this->table("company_order_product")->alias("company_order_product")->
   //         join("customer customer","customer.customer_id = company_order_flight.customer_id",'left')->
			join("tax",'tax.tax_id = company_order_product.tax_id','left')->
            where($data)->
            field(['company_order_product.company_order_product_id','company_order_product.company_order_number',
					'company_order_product.branch_product_number','company_order_product.branch_product_name',
            		'company_order_product.branch_product_cost','company_order_product.cost_currency_id',
            		'company_order_product.branch_product_price','company_order_product.price_currency_id',
                    'company_order_product.supplier_name','company_order_product.receivable_number',
            		'company_order_product.tax_id','company_order_product.remark','company_order_product.price_before_tax',
            		'company_order_product.substribe_time','company_order_product.utc_substribe_time',
            		'tax.txcd','tax.gstrate','tax.pstrate','tax.hstrate','tax.otx','tax.note',
            		"(select currency_name from currency where company_order_product.price_currency_id = currency.currency_id) as price_currency_name",
                    "(select unit from currency where company_order_product.price_currency_id = currency.currency_id) as price_currency_unit",
            		"(select currency_name from currency where company_order_product.cost_currency_id = currency.currency_id) as cost_currency_name",
            		"(select nickname  from user where user.user_id = company_order_product.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = company_order_product.update_user_id)"=>'update_user_name',
            		'company_order_product.create_user_id','company_order_product.update_user_id','company_order_product.create_time',
            		'company_order_product.update_time','company_order_product.status'])->
            
            select();


        
    
        return $result;

    }


    /**
     * 修改公司订单产品
     */
    public function updateCompanyOrderProduct($params){

        $t = time();
        

        if(is_numeric($params['status'])){
            $data['status'] = $params['status'];

        }
		if(is_numeric($params['branch_product_price'])){
			$data['branch_product_price'] = $params['branch_product_price'];
		}

        $data['update_user_id'] = $params['now_user_id'];

        $data['update_time'] = $t;


		$where = "company_order_number ='".$params['company_order_number']."' and branch_product_number = '".$params['branch_product_number']."'";

        $this->startTrans();
        try{
            $this->where($where)->update($data);
			if($params['status'] ==0){
				//则把所有的都变成0
				$company_order_params['status'] = 0;
				$this->table('company_order_product_source')->where($where)->update($company_order_params);
				$this->table('company_order_product_team')->where($where)->update($company_order_params);
				$this->table('company_order_product_template')->where($where)->delete();
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
     * 修改公司订单分公司产品
     */
    public function updateCompanyOrderProductById($params){
    
    	$t = time();
    
    
    	if(!empty($params['invoice_number'])){
    		$data['invoice_number'] = $params['invoice_number'];
    
    	}
    	if(!empty($params['invoice_time'])){
    		$data['invoice_time'] = $params['invoice_time'];
    
    	}
    	if(!empty($params['price_currency_id'])){
    		$data['price_currency_id'] = $params['price_currency_id'];
    
    	}
    	if(!empty($params['branch_product_price'])){
    		$data['branch_product_price'] = $params['branch_product_price'];
    
    	}
    	if(!empty($params['tax_id'])){
    		$data['tax_id'] = $params['tax_id'];
    	
    	}
    	if(!empty($params['price_before_tax'])){
    		$data['price_before_tax'] = $params['price_before_tax'];
    	
    	}
    	if(!empty($params['receivable_number'])){
    		$data['receivable_number'] = $params['receivable_number'];
    		 
    	}    	
    	if(!empty($params['remark'])){
    		$data['remark'] = $params['remark'];
    	
    	}
    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    
    	}
    	if(isset($params['substribe_time'])){
    		$data['substribe_time'] = $params['substribe_time'];
    	
    	}    
    	if(isset($params['utc_substribe_time'])){
    		$data['utc_substribe_time'] = $params['utc_substribe_time'];
    	
    	}   
    	$data['update_user_id'] = $params['now_user_id'];
    
    	$data['update_time'] = $t;
    
    
    
    	
    	$this->startTrans();
    	try{
    		$this->where("company_order_product_id = '".$params['company_order_product_id']."'")->update($data);
    
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