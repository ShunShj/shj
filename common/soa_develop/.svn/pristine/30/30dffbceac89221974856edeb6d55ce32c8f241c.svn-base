<?php

namespace app\index\model\branchcompany;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class CompanyOrderProductSource extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'company_order_product_source';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加公司订单-资源
     * 胡
     */
    public function addCompanyOrderProductSource($params){

        $t = time();
		$data['company_order_number'] = $params['company_order_number'];

		
		$data['supplier_type_id'] = $params['supplier_type_id'];
		if(!empty($params['source_id'])){
			$data['source_id']= $params['source_id'];
		}
		
	
		
		$data['source_name'] = $params['source_name'];
		if(!empty($params['branch_product_number'])){
			$data['branch_product_number']= $params['branch_product_number'];
		}
		//$data['price_currency_id']
		if(!empty($params['team_product_number'])){
			$data['team_product_number']= $params['team_product_number'];
		}
		if(!empty($params['company_order_product_team_id'])){
			$data['company_order_product_team_id']= $params['company_order_product_team_id'];
		}
		if(!empty($params['is_receivable_company'])){
			$data['is_receivable_company']= $params['is_receivable_company'];
		}
		if(!empty($params['is_settle'])){
			$data['is_settle']= $params['is_settle'];
		}
		
		
		if(is_numeric($params['is_own_source_by_branch_product'])){
			$data['is_own_source_by_branch_product']= $params['is_own_source_by_branch_product'];
		}	
		if(is_numeric($params['price_before_tax'])){
			$data['price_before_tax']= $params['price_before_tax'];
		}
		if(is_numeric($params['is_type'])){
			$data['is_type']= $params['is_type'];
		}
		if(!empty($params['source_cost_univalence'])){
			$data['source_cost_univalence']= $params['source_cost_univalence'];
		}
		if(!empty($params['cope_number'])){
			$data['cope_number']= $params['cope_number'];
		}
		if(!empty($params['receivable_number'])){
			$data['receivable_number']= $params['receivable_number'];
		}
		if(!empty($params['supplier_name'])){
			$data['supplier_name']= $params['supplier_name'];
		}
		
		if(!empty($params['team_product_allocation_id'])){
			$data['team_product_allocation_id']= $params['team_product_allocation_id'];
		}
		
		
		
		$data['source_cost']=$params['source_cost'];
		$data['source_price']=$params['source_price'];
        $data['cost_currency_id']=$params['cost_currency_id'];
        $data['price_currency_id']=$params['price_currency_id'];
       
        
		$data['create_time'] = $t;
		$data['update_time'] = $t;
		$data['create_user_id'] = $params['now_user_id'];
		$data['update_user_id'] = $params['now_user_id'];
		$data['status'] = 1;
		if(!empty($params['team_product_number'])){
			$data['team_product_number'] = $params['team_product_number'];
		}
		if(!empty($params['team_product_id'])){
			$data['team_product_id'] = $params['team_product_id'];
		}

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
     * 获取分公司订单资源
     * 胡
     */
    public function getCompanyOrderProductSource($params){

    	
    	$data = "1=1";
    	if(!empty($params['company_order_number'])){
    		$data.= " and company_order_product_source.company_order_number = '".$params['company_order_number']."'";
    	}
    	if(!empty($params['team_product_number'])){
    		$data.= " and company_order_product_source.team_product_number = '".$params['team_product_number']."'";
    	}
    	if(!empty($params['team_product_id'])){
    		$data.= " and company_order_product_source.team_product_id = '".$params['team_product_id']."'";
    	}

        if(!empty($params['team_product_allocation_id'])){
            $data.= " and company_order_product_source.team_product_allocation_id = '".$params['team_product_allocation_id']."'";
        }
    	if(!empty($params['company_order_product_source_id'])){
    		$data.= " and company_order_product_source.company_order_product_source_id = ".$params['company_order_product_source_id'];
    	}
		if(!empty($params['supplier_type_id'])){
			$data.= " and company_order_product_source.supplier_type_id = '".$params['supplier_type_id']."'";
		}
		if(!empty($params['source_id'])){
			$data.= " and company_order_product_source.source_id = '".$params['source_id']."'";
		}
		if(!empty($params['branch_product_number'])){
			$data.= " and company_order_product_source.branch_product_number = '".$params['branch_product_number']."'";
		}
		if(!empty($params['receivable_number'])){
			$data.= " and company_order_product_source.receivable_number = '".$params['receivable_number']."'";
		}
    	if(is_numeric($params['status'])){
    		$data.= " and company_order_product_source.status = ".$params['status'];
    	}
    	if(is_numeric($params['is_type'])){
    		$data.= " and company_order_product_source.is_type = ".$params['is_type'];
    	}
    	if(is_numeric($params['company_order_product_team_id'])){
    		$data.= " and company_order_product_source.company_order_product_team_id = ".$params['company_order_product_team_id'];
    	}
    	if(is_numeric($params['is_not_source_type_14'])){
    		$data.= " and company_order_product_source.supplier_type_id !=14";
    	}
    	if(is_numeric($params['company_order_status'])){
    		$data.= " and company_order.status>=0";
    	}    	
    	
    	if(is_numeric($params['now_is_own_source_by_branch_product'])){
    		$data.= " and company_order_product_source.is_own_source_by_branch_product=1";
    	}   	
    	if(is_numeric($params['not_is_own_source_by_branch_product'])){
    		$data.= " and company_order_product_source.is_own_source_by_branch_product is null";
    	}   	
    	if(is_numeric($params['not_is_own_source_by_branch_product'])){
    		$data.= " and company_order_product_source.is_own_source_by_branch_product is null";
    	}
    	
    	if(is_numeric($params['utc_substribe_time'])){
    		$data.= " and FROM_UNIXTIME(company_order_product_source.utc_substribe_time,'%Y%m%d%H')= ".$params['utc_substribe_time'];
    	}    	
    	
       $result= $this->table("company_order_product_source")->alias("company_order_product_source")->
            join("tax",'tax.tax_id = company_order_product_source.tax_id','left')->
    
            join("company_order",'company_order.company_order_number = company_order_product_source.company_order_number','left')->
            where($data)->
            field([ 'company_order_product_source_id','company_order_product_source.company_order_number','branch_product_number','supplier_type_id','source_id',
            		'source_name','source_cost','company_order_product_source.source_cost_univalence','source_price','cost_currency_id','price_currency_id','invoice_number','invoice_time',
					'is_type',
            		'supplier_name',
					'company_order_product_source.team_product_receivable_type','company_order_product_source.is_receivable_company',
            		'company_order_product_source.team_product_number','company_order_product_source.team_product_id',
					'company_order_product_source.is_own_source_by_branch_product',
           			'company_order_product_source.netamt','company_order_product_source.gst','company_order_product_source.pst',
           			'company_order_product_source.hst','company_order_product_source.p_otx','company_order_product_source.invamt',
           			'company_order_product_source.estcost','company_order_product_source.paidamt','company_order_product_source.balance',
            		'company_order_product_source.is_settle',
            		'company_order_product_source.cope_number','company_order_product_source.receivable_number',
            		'company_order_product_source.tax_id','company_order_product_source.tax_cd','company_order_product_source.price_before_tax','company_order_product_source.remark',
            		
            		'company_order_product_source.substribe_time','company_order_product_source.utc_substribe_time',
            		'tax.txcd','tax.gstrate','tax.pstrate','tax.hstrate','tax.otx','tax.note',
            		"(select currency_name from currency where company_order_product_source.price_currency_id = currency.currency_id) as price_currency_name",
            		"(select currency_name from currency where company_order_product_source.cost_currency_id = currency.currency_id) as cost_currency_name",
            		"(select nickname  from user where user.user_id = company_order_product_source.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = company_order_product_source.update_user_id)"=>'update_user_name',
            		'company_order_product_source.create_user_id','company_order_product_source.update_user_id','company_order_product_source.create_time',
            		'company_order_product_source.update_time','company_order_product_source.status'])->
            
            select();

       
	
		
        return $result;

    }


    /**
     * 修改公司订单资源
     */
    public function updateCompanyOrderSource($params){


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
        if(!empty($params['source_price'])){
        	$data['source_price'] = $params['source_price'];
        
        }
        
        if(!empty($params['source_cost'])){
        	$data['source_cost'] = $params['source_cost'];
        
        }
        if(!empty($params['cost_currency_id'])){
        	$data['cost_currency_id'] = $params['cost_currency_id'];
        
        }

        isset($params['tax_id']) && $data['tax_id'] = $params['tax_id'];

        if(!empty($params['price_before_tax'])){
        	$data['price_before_tax'] = $params['price_before_tax'];
        
        }
        if(isset($params['remark'])){
        	$data['remark'] = $params['remark'];
        
        }
        
        if(!empty($params['cope_number'])){
        	$data['cope_number'] = $params['cope_number'];
        
        }
        
        if(!empty($params['receivable_number'])){
        	$data['receivable_number'] = $params['receivable_number'];
        
        }
        if(is_numeric($params['status'])){
            $data['status'] = $params['status'];

        }


        if(!empty($params['netamt'])){
            $data['netamt'] = $params['netamt'];
        }

        if(!empty($params['gst'])){
            $data['gst'] = $params['gst'];
        }

        if(!empty($params['pst'])){
            $data['pst'] = $params['pst'];
        }

        if(!empty($params['hst'])){
            $data['hst'] = $params['hst'];
        }

        if(!empty($params['p_otx'])){
            $data['p_otx'] = $params['p_otx'];
        }

        if(!empty($params['invamt'])){
            $data['invamt'] = $params['invamt'];
        }

        if(!empty($params['estcost'])){
            $data['estcost'] = $params['estcost'];
        }

        if(!empty($params['paidamt'])){
            $data['paidamt'] = $params['paidamt'];
        }

        if(!empty($params['balance'])){
            $data['balance'] = $params['balance'];
        }

        isset($params['tax_cd']) && $data['tax_cd'] = $params['tax_cd'];
		

        $data['update_user_id'] = $params['now_user_id'];

        $data['update_time'] = $t;

        if(isset($params['substribe_time'])){
        	$data['substribe_time'] = $params['substribe_time'];
        
        }
        if(isset($params['utc_substribe_time'])){
        	$data['utc_substribe_time'] = $params['utc_substribe_time'];
        
        }
		
        $this->startTrans();
        try{
            $this->where("company_order_product_source_id = ".$params['company_order_product_source_id'])->update($data);

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
     * 修改公司订单资源
     */
    public function updateCompanyOrderSourceByCompanyOrderNumberAndTeamProductId($params){
    
    
    	$t = time();

    
    	if(isset($params['source_cost'])){
    		$data['source_cost'] = $params['source_cost'];
    
    	}

    	if(isset($params['source_cost_univalence'])){
    		$data['source_cost_univalence'] = $params['source_cost_univalence'];
    	
    	}   
    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    	}
    	
    
    	$data['update_time'] = $t;
    
    	$where['company_order_number'] = $params['company_order_number'];
    	$where['team_product_id'] = $params['team_product_id'];
    	$where['team_product_allocation_id'] = $params['team_product_allocation_id'];
  
    
    	$this->startTrans();
    	try{
    		$result= $this->where($where)->update($data);
    
    		
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
     * 更改状态
     */
    public function updateCompanyOrderSourceStatus($params){

    	if(!empty($params['company_order_number'])){
    		$where['company_order_number'] = $params['company_order_number'];
    	
    	}
    	if(!empty($params['team_product_number'])){
    		$where['team_product_number'] = $params['team_product_number'];
    		 
    	}
    	if(!empty($params['team_product_id'])){
    		$where['team_product_id'] = $params['team_product_id'];
    		 
    	}
    	if(!empty($params['supplier_type_id'])){
    		$where['supplier_type_id'] = $params['supplier_type_id'];
    		 
    	}
    	if(!empty($params['source_id'])){
    		$where['source_id'] = $params['source_id'];
    		 
    	}
    	if(is_numeric($params['is_type'])){
    		$where['is_type'] = $params['is_type'];
    		 
    	}
    	if(isset($params['receivable_number'])){
    	
    		$where['receivable_number'] = '';
    		 
    	}
    	
    	if(is_numeric($params['is_not_source_type_14'])){
    		$where['supplier_type_id'] = array('neq',14);
    		 
    	}
    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    	
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
     * 修改公司订单资源根据应付编号
     */
    public function updateCompanyOrderSourceByCopeNumber($params){
    
    
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
    	if(!empty($params['source_price'])){
    		$data['source_price'] = $params['source_price'];
    
    	}
    
    	if(!empty($params['source_cost'])){
    		$data['source_cost'] = $params['source_cost'];
    
    	}
    	if(!empty($params['cost_currency_id'])){
    		$data['cost_currency_id'] = $params['cost_currency_id'];
    
    	}
    	if(!empty($params['source_name'])){
    		$data['source_name'] = $params['source_name'];
    	
    	}
    	
    	if(!empty($params['supplier_type_id'])){
    		$data['supplier_type_id'] = $params['supplier_type_id'];
    		 
    	}
    	if(!empty($params['source_id'])){
    		$data['source_id'] = $params['source_id'];
    		 
    	}
    	if(!empty($params['remark'])){
    		$data['remark'] = $params['remark'];
    
    	}
    
    	if(!empty($params['cope_number'])){
    		$where['cope_number'] = $params['cope_number'];
    
    	}
    	if(!empty($params['receivable_number'])){
    		$where['receivable_number'] = $params['receivable_number'];
    	
    	}
    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    
    	}
    
    
    
    
    	$data['update_time'] = $t;
    
    
    
    
    	$this->startTrans();
    	try{
    		$result = $this->where($where)->update($data);
    
    		
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
     * 修改公司订单资源根据应收编号
     */
    public function updateCompanyOrderSourceByReceivableNumber($params){
    
    
    	$t = time();
    
    	if(!empty($params['invoice_number'])){
    		$data['invoice_number'] = $params['invoice_number'];
    
    	}
    	if(!empty($params['invoice_time'])){
    		$data['invoice_time'] = $params['invoice_time'];
    
    	}
    	if(isset($params['price_currency_id'])){
    		$data['price_currency_id'] = $params['price_currency_id'];
    
    	}
    	if(isset($params['source_price'])){
    		$data['source_price'] = $params['source_price'];
    
    	}
    
    	if(!empty($params['source_cost'])){
    		$data['source_cost'] = $params['source_cost'];
    
    	}
    	if(!empty($params['cost_currency_id'])){
    		$data['cost_currency_id'] = $params['cost_currency_id'];
    
    	}
    	if(!empty($params['source_name'])){
    		$data['source_name'] = $params['source_name'];
    		 
    	}
    	 
    	if(!empty($params['supplier_type_id'])){
    		$data['supplier_type_id'] = $params['supplier_type_id'];
    		 
    	}
    	if(!empty($params['source_id'])){
    		$data['source_id'] = $params['source_id'];
    		 
    	}
    	if(!empty($params['remark'])){
    		$data['remark'] = $params['remark'];
    
    	}
    
    	if(!empty($params['receivable_number'])){
    		$where['receivable_number'] = $params['receivable_number'];
    
    	}
    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    
    	}
    
    
    
    
    	$data['update_time'] = $t;
    
    
    
    
    	$this->startTrans();
    	try{
    		$result = $this->where($where)->update($data);
    
    
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
     * 修改公司订单资源
     */
    public function updateCompanyOrderSourceByCompanyOrderProductTeamId($params){
    
    
    	$t = time();
    
 
    	if(!empty($params['price_currency_id'])){
    		$data['price_currency_id'] = $params['price_currency_id'];
    
    	}
    	if(!empty($params['source_price'])){
    		$data['source_price'] = $params['source_price'];
    
    	}
    
    
    	$data['update_user_id'] = $params['now_user_id'];
    
    	$data['update_time'] = $t;
    
    
    
    
    	$this->startTrans();
    	try{
    		$this->where("company_order_product_team_id = ".$params['company_order_product_team_id'])->update($data);
    
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
     * 获取公司订单资源
     */
    public function getCompanyOrderSourceByParams(){
    	$data = "1=1";
    	if(!empty($params['company_order_number'])){
    		$data.= " and company_order_product_source.company_order_number = '".$params['company_order_number']."'";
    	}

    	if(!empty($params['team_product_id'])){
    		$data.= " and company_order_product_source.team_product_id = '".$params['team_product_id']."'";
    	}

    	if(!empty($params['supplier_type_id'])){
    		$data.= " and company_order_product_source.supplier_type_id = '".$params['supplier_type_id']."'";
    	}
    	
    	
    	if(!empty($params['source_id'])){
    		$data.= " and company_order_product_source.source_id = '".$params['source_id']."'";
    	}


    	$result= $this->table("company_order_product_source")->alias("company_order_product_source")->

    	where($data)->select();
    	
    	 
    	
    	
    	return $result;    	
    	
    }
    
}