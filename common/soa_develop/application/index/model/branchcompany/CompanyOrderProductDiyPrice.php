<?php

namespace app\index\model\branchcompany;
use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use think\config;
use think\Db;
class CompanyOrderProductDiyPrice extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'company_order_product_diy_price';
    private $_languageList;
    private $_public_service;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        $this->_public_service = new PublicService();
        parent::initialize();

    }

    /**
     * 添加公司订单产品-自定义
     * 胡
     */
    public function addCompanyOrderProductDiyPrice($params){

        $t = time();
		$data['company_order_number'] = $params['company_order_number'];

		$data['diy_name'] = $params['diy_name'];
		$data['diy_type'] = $params['diy_type'];
		$data['diy_price'] = $params['diy_price'];
		$data['tax_price'] = $params['tax_price'];
		if(!empty($params['price_before_tax'])){
			$data['price_before_tax'] = $params['price_before_tax'];
		}
		
		if(!empty($params['tax_id'])){
			$data['tax_id'] = $params['tax_id'];
		}

		$data['price_currency_id']=$params['price_currency_id'];

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
     * 获取公司订单 自定义报价
     * 胡
     */
    public function getCompanyOrderProductDiyPrice($params){

 
    	$data = "1=1";
    	if(!empty($params['company_order_number'])){
    		$data.= " and company_order_product_diy_price.company_order_number = '".$params['company_order_number']."'";
    	}

    	if(!empty($params['receivable_number'])){
    		$data.= " and company_order_product_diy_price.receivable_number =  '".$params['receivable_number']."'";
    	}
    	if(is_numeric($params['status'])){
    		$data.= " and company_order_product_diy_price.status = ".$params['status'];
    	}

        if(is_numeric($params['utc_substribe_time'])){
    		$data.= " and FROM_UNIXTIME(company_order_product_diy.utc_substribe_time,'%Y%m%d%H')= ".$params['utc_substribe_time'];
    	}   	
            $result= $this->table("company_order_product_diy_price")->alias("company_order_product_diy_price")->
            join("tax",'tax.tax_id = company_order_product_diy_price.tax_id','left')->
            where($data)->
            field(['company_order_product_diy_price.company_order_product_diy_price_id','company_order_product_diy_price.company_order_number',
            		'company_order_product_diy_price.diy_name','company_order_product_diy_price.diy_price','company_order_product_diy_price.diy_type',
            		'company_order_product_diy_price.price_currency_id','company_order_product_diy_price.tax_price',
            	
                	'company_order_product_diy_price.netamt','company_order_product_diy_price.gst','company_order_product_diy_price.pst',
                	'company_order_product_diy_price.hst','company_order_product_diy_price.p_otx','company_order_product_diy_price.invamt',
                	'company_order_product_diy_price.estcost','company_order_product_diy_price.paidamt','company_order_product_diy_price.balance',

            		'company_order_product_diy_price.receivable_number',
            		'company_order_product_diy_price.tax_id',
            		'company_order_product_diy_price.substribe_time','company_order_product_diy_price.utc_substribe_time',
            		'company_order_product_diy_price.price_before_tax',
            		'tax.txcd','tax.gstrate','tax.pstrate','tax.hstrate','tax.otx','tax.note',
            		"(select currency_name from currency where company_order_product_diy_price.price_currency_id = currency.currency_id) as price_currency_name",

            		"(select nickname  from user where user.user_id = company_order_product_diy_price.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = company_order_product_diy_price.update_user_id)"=>'update_user_name',
            		'company_order_product_diy_price.create_user_id','company_order_product_diy_price.update_user_id','company_order_product_diy_price.create_time',
            		'company_order_product_diy_price.update_time','company_order_product_diy_price.status'])->
            
            select();

       


        return $result;

    }


    /**
     * 修改公司订单产品自定义根据自定义编号
     */
    public function updateCompanyOrderProductDiyPriceById($params){

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
        if(!empty($params['supplier_id'])){
        	$data['supplier_id'] = $params['supplier_id'];
        
        }
        if(!empty($params['diy_price'])){
        	$data['diy_price'] = $params['diy_price'];
        
        }       
        if(!empty($params['diy_type'])){
        	$data['diy_type'] = $params['diy_type'];
        
        }  
        if(!empty($params['diy_name'])){
        	$data['diy_name'] = $params['diy_name'];
        
        }
        if(!empty($params['cost_currency_id'])){
        	$data['cost_currency_id'] = $params['cost_currency_id'];
        
        }
        if(is_numeric($params['status'])){
            $data['status'] = $params['status'];

        }

        if(is_numeric($params['tax_id'])){
        	$data['tax_id'] = $params['tax_id'];
        
        }  
        if(is_numeric($params['tax_price'])){
        	$data['tax_price'] = $params['tax_price'];
        
        }
        if(!empty($params['price_before_tax'])){
        	$data['price_before_tax'] = $params['price_before_tax'];
        
        }
        if(!empty($params['remark'])){
        	$data['remark'] = $params['remark'];
        
        }
        if(!empty($params['cope_number'])){
        	$data['cope_number'] = $params['cope_number'];
        
        }
        if(!empty($params['receivable_number'])){
        	$data['receivable_number'] = $params['receivable_number'];
        
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
        if(isset($params['substribe_time'])){
        	
        	$data['substribe_time'] = $params['substribe_time'];
        
        }
        if(isset($params['utc_substribe_time'])){
        	$data['utc_substribe_time'] = $params['utc_substribe_time'];
        
        }        
        isset($params['tax_cd']) && $data['tax_cd'] = $params['tax_cd'];

        $data['update_user_id'] = $params['now_user_id'];

        $data['update_time'] = $t;


	
        $this->startTrans();
        try{
            $this->where("company_order_product_diy_price_id = ".$params['company_order_product_diy_price_id'])->update($data);
	
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
	
    //修改公司订单自定义根据团队产品ID
	public function updateStatueByTeamProductId($params){
		$t = time();

		if(is_numeric($params['status'])){
			$data['status'] = $params['status'];
		}

		
		$data['update_user_id'] = $params['now_user_id'];
		
		$data['update_time'] = $t;
		

		
		$this->startTrans();
		try{
			$this->where("team_product_id = '".$params['team_product_id']."'")->update($data);
		
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
	
	
	//修改公司订单自定义根据团队产品ID
	public function updateCompanyOrderProduct($params){
		$t = time();
	
		if(is_numeric($params['status'])){
			$data['status'] = $params['status'];
		}
	
		if(!empty($params['cope_number'])){
			$where['cope_number'] = $params['cope_number'];
		}
		
		if(is_numeric($params['diy_cost'])){
			$data['diy_cost'] = $params['diy_cost'];
		}
		if(is_numeric($params['cost_currency_id'])){
			$data['cost_currency_id'] = $params['cost_currency_id'];
		}
		if(is_numeric($params['supplier_id'])){
			$data['supplier_id'] = $params['supplier_id'];
		}
		
		$data['update_user_id'] = $params['now_user_id'];
	
		$data['update_time'] = $t;
	
	
	
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
}