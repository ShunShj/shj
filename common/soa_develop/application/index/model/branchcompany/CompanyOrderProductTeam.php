<?php

namespace app\index\model\branchcompany;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
use app\index\model\finance\ReceivableCustomer;
class CompanyOrderProductTeam extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'company_order_product_team';
    private $_languageList;
    private $_receivable_customer;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        $this->_receivable_customer = new ReceivableCustomer();
        parent::initialize();

    }

    /**
     * 添加公司订单-团队产品
     * 胡
     */
    public function addCompanyOrderProductTeam($params){

        $t = time();
		$data['company_order_number'] = $params['company_order_number'];
		if(!empty($params['team_product_number'])){
			$data['team_product_number'] = $params['team_product_number'];
		}
		$data['team_product_id'] = $params['team_product_id'];
		$data['team_product_name'] = $params['team_product_name'];
		$data['branch_product_number'] = $params['branch_product_number'];
		$data['price_before_tax'] = $params['price_before_tax'];
		//$data['cost_currency_id']=1;
		$data['team_product_cost']=$params['team_product_cost'];
		$data['team_product_price'] =$params['team_product_price'];
		if(isset($params['price_currency_id'])){
			$data['price_currency_id']=$params['price_currency_id'];
		}
		if(is_numeric($params['is_type'])){
			$data['is_type']=$params['is_type'];
		} 
 		if(!empty($params['team_product_cost_univalence'])){
 			$data['team_product_cost_univalence']=$params['team_product_cost_univalence'];
 		}
 		if(!empty($params['cope_number'])){
 			$data['cope_number']=$params['cope_number'];
 		}
 		if(!empty($params['receivable_number'])){
 			$data['receivable_number']=$params['receivable_number'];
 		}
 		if(!empty($params['settlement_type'])){
 			$data['settlement_type']=$params['settlement_type'];
 		}
 		if(!empty($params['cope_number'])){
 			$data['cope_number']=$params['cope_number'];
 		}
 		if(isset($params['supplier_name'])){
 			$data['supplier_name']=$params['supplier_name'];
 		}
 		
 		if(isset($params['cost_currency_id'])){
 			$data['cost_currency_id']=$params['cost_currency_id'];
 		}
        if(!empty($params['is_receivable_company'])){
        	$data['is_receivable_company']=$params['is_receivable_company'];
        }
      
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
     * 获取公司订单 -团队产品
     * 胡
     */
    public function getCompanyOrderProductTeam($params){

 
    	$data = "1=1";
    	if(!empty($params['company_order_number'])){
    		$data.= " and company_order_product_team.company_order_number = '".$params['company_order_number']."'";
    	}
    	if(!empty($params['team_product_number'])){
    		$data.= " and company_order_product_team.team_product_number = '".$params['team_product_number']."'";
    	}
    	if(!empty($params['team_product_id'])){
    		$data.= " and company_order_product_team.team_product_id = '".$params['team_product_id']."'";
    	}
    	if(!empty($params['is_type'])){
    		$data.= " and company_order_product_team.is_type = ".$params['is_type'];
    	}
    	if(is_numeric($params['status'])){
    		$data.= " and company_order_product_team.status = ".$params['status'];
    	}
		if(!empty($params['settlement_type'])){
			$data.= " and company_order_product_team.settlement_type = ".$params['settlement_type'];
		}
    	if(!empty($params['company_order_status'])){
			$data.= " and company_order.status>=0";
		}
		if(is_numeric($params['utc_substribe_time'])){
			$data.= " and FROM_UNIXTIME(company_order_product_team.utc_substribe_time,'%Y%m%d%H')= ".$params['utc_substribe_time'];
		}		
            $result= $this->table("company_order_product_team")->alias("company_order_product_team")->
            join("company_order_product_template copt",'copt.company_order_product_team_id = company_order_product_team.company_order_product_team_id ','left')->
            join("tax",'tax.tax_id = company_order_product_team.tax_id','left')->
            join("team_product team_product",'team_product.team_product_id= company_order_product_team.team_product_id','left')->
            join("company_order",'company_order_product_team.company_order_number = company_order.company_order_number','left')->
            where($data)->
            field(['company_order_product_team.company_order_product_team_id','company_order_product_team.company_order_number','company_order_product_team.team_product_number','company_order_product_team.team_product_id',
            		'company_order_product_team.team_product_name','company_order_product_team.branch_product_number','company_order_product_team.team_product_price',
            		'company_order_product_team.team_product_cost','company_order_product_team.cost_currency_id','company_order_product_team.price_currency_id','company_order_product_team.invoice_number',
            		'company_order_product_team.supplier_name','company_order_product_team.supplier_name as team_product_company_name',
                    'company_order_product_team.invoice_time','company_order_product_team.settlement_type',
            		'company_order_product_team.is_receivable_company','company_order_product_team.is_type',
            		'company_order_product_team.price_before_tax','company_order_product_team.tax_id','company_order_product_team.tax_cd',
            		'tax.txcd','tax.gstrate','tax.pstrate','tax.hstrate','tax.otx','tax.note',
            		'company_order_product_team.team_product_cost_univalence',
            		'company_order_product_team.cope_number','company_order_product_team.receivable_number',
            		"(select company_id from company_order where company_order.company_order_number = company_order_product_team.company_order_number) as company_id",
  					'company_order_product_team.team_product_id',
            		'copt.route_template_number','team_product.team_product_number',
            		'company_order_product_team.remark','company_order_product_team.substribe_time','company_order_product_team.utc_substribe_time',
                	'company_order_product_team.netamt','company_order_product_team.gst','company_order_product_team.pst','company_order_product_team.hst','company_order_product_team.p_otx','company_order_product_team.invamt','company_order_product_team.estcost','company_order_product_team.paidamt','company_order_product_team.balance',
            		"(select begin_time from team_product where team_product.team_product_id= company_order_product_team.team_product_id) as begin_time",
            		"(select route_name from route_template where route_template.route_number = copt.route_template_number) as route_name",
            		
            		"(select currency_name from currency where company_order_product_team.price_currency_id = currency.currency_id) as price_currency_name",
            		"(select currency_name from currency where company_order_product_team.cost_currency_id = currency.currency_id) as cost_currency_name",
            		"(select nickname  from user where user.user_id = company_order_product_team.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = company_order_product_team.update_user_id)"=>'update_user_name',
            		
            		'company_order_product_team.create_user_id','company_order_product_team.update_user_id','company_order_product_team.create_time',
            		'company_order_product_team.update_time','company_order_product_team.status'])->
            
            select();


		

        return $result;

    }
	/**
	 * 根据团队产品编号获取公司订单号
	 */
    public function getCompanyOrderNumberByTeamProductNumber($params){
        $team_product_number = $params['team_product_number'];
    	$where = [
    		'copt.team_product_number'=>$team_product_number,
    		'copt.status'=>1,
    		'co.status'=>1	
    		
    	];
    	if(!empty($params['company_id'])){
    		$where["co.company_id"] = $params['company_id'];
    	}
       if(!empty($params['company_order_number'])){
           $where["co.company_order_number"] = $params['company_order_number'];
       }
    	if(!empty($params['nickname'])){
           $create_user_id = $this->table('user')->where("nickname LIKE '%{$params['nickname']}%'")->field('user_id')->select();
           // echo ($this->table("user")->getLastSql());exit;
           // var_dump($create_user_id); exit;
           $where['co.create_user_id'] = $create_user_id[0]['user_id']?:0;
       } 
       
    	$result = $this->table("company_order_product_team")->alias("copt")->
    		join("company_order co",'co.company_order_number = copt.company_order_number')->
    		join("company",'co.company_id = company.company_id')->
    		where($where)->field([
    			'copt.company_order_number','company.company_name','co.create_user_id',
    			'co.company_id',
    			"(select count(*) from company_order_customer where company_order_customer.company_order_number = co.company_order_number and status<>0) as people_number",
    			"(select nickname  from user where user.user_id = co.create_user_id)"=>'create_user_name',
    			'co.create_time'	
    			
    	])->select(); 
    	
    	return $result;
    }
    /**
     * 根据团队产品编号获取公司订单号
     */
    public function getCompanyOrderNumberByTeamProductId($params){
    	$team_product_id = $params['team_product_id'];
    	$where = [
    			'copt.team_product_id'=>$team_product_id,    		
    	];
    	if(!empty($params['company_id'])){
    		$where["co.company_id"] = $params['company_id'];
    	}
    	if(!empty($params['company_order_number'])){
    		$where["co.company_order_number"] = $params['company_order_number'];
    	}
    	
    	if(!empty($params['company_order_number'])){
    		$where["co.company_order_number"] = $params['company_order_number'];
    	}   	
    	if(is_numeric($params['company_order_status'])){
    		$where["co.company_order_status"] = $params['company_order_status'];
    	}  
    	$where["copt.status"] = 1;
    	  	
    	$where['co.status'] =array('egt',0);
    	
    	if(!empty($params['nickname'])){
    		$create_user_id = $this->table('user')->where("nickname LIKE '%{$params['nickname']}%'")->field('user_id')->select();
    		// echo ($this->table("user")->getLastSql());exit;
    		// var_dump($create_user_id); exit;
    		$where['co.create_user_id'] = $create_user_id[0]['user_id']?:0;
    	}
    	
    	
    	$result = $this->table("company_order_product_team")->alias("copt")->
    	join("company_order co",'co.company_order_number = copt.company_order_number','left')->
    	join("company",'co.company_id = company.company_id','left')->
    	where($where)->field([
    			'copt.company_order_number','company.company_name','co.create_user_id',
    			'co.company_id','co.locked','co.company_order_status',
    			"(select count(*) from company_order_customer where company_order_customer.company_order_number = co.company_order_number and status<>0) as people_number",
    			"(select nickname  from user where user.user_id = co.create_user_id)"=>'create_user_name',
    			'co.create_time'
    
    	])->select();
	
    	return $result;
    }
    /**
     * 修改公司订单的团队产品
     */
    public function updateCompanyOrderTeam($params){

        $t = time();

        if(!empty($params['source_name'])){
        	$data['source_name'] = $params['source_name'];
        
        }
        if(!empty($params['invoice_number'])){
        	$data['invoice_number'] = $params['invoice_number'];

        }
        if(!empty($params['invoice_time'])){
        	$data['invoice_time'] = $params['invoice_time'];
        
        }
        if(!empty($params['price_currency_id'])){
        	$data['price_currency_id'] = $params['price_currency_id'];
        
        }
        if(isset($params['team_product_price'])){
        	$data['team_product_price'] = $params['team_product_price'];
        
        }
        if(isset($params['team_product_cost'])){
        	$data['team_product_cost'] = $params['team_product_cost'];
        
        }
        if(isset($params['team_product_cost_univalence'])){
        	$data['team_product_cost_univalence'] = $params['team_product_cost_univalence'];
        
        }
        if(!empty($params['receivable_number'])){
        	$data['receivable_number'] = $params['receivable_number'];
        
        }
        
        if(!empty($params['cost_currency_id'])){
        	$data['cost_currency_id'] = $params['cost_currency_id'];
        
        }

        isset($params['tax_id']) && $data['tax_id'] = $params['tax_id'];


        if(isset($params['price_before_tax'])){
        	$data['price_before_tax'] = $params['price_before_tax'];
        
        }
        
        
        if(!empty($params['company_order_number'])){
        	$where['company_order_number'] = $params['company_order_number'];
        }
        if(!empty($params['team_product_number'])){
        	$where['team_product_number'] = $params['team_product_number'];
        }
        if(!empty($params['team_product_id'])){
        	$where['team_product_id'] = $params['team_product_id'];
        }
        if(!empty($params['branch_product_number'])){
        	$where['branch_product_number'] = $params['branch_product_number'];
        }
        if(!empty($params['company_order_product_team_id'])){
        	$where['company_order_product_team_id'] = $params['company_order_product_team_id'];
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
        if(isset($params['remark'])){
        	$data['remark'] = $params['remark'];
        
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
     * 修改公司订单的团队产品根据订单编号以及团队编号
     */
    public function updateCompanyOrderTeamByCompanyOrderNumberAndTeamProductId($params){
    
    	$t = time();
    


    	if(isset($params['team_product_cost'])){
    		$data['team_product_cost'] = $params['team_product_cost'];
    
    	}

    
    	if(!empty($params['cost_currency_id'])){
    		$data['cost_currency_id'] = $params['cost_currency_id'];
    
    	}
    

    
    	if(!empty($params['company_order_number'])){
    		$where['company_order_number'] = $params['company_order_number'];
    	}
    	if(!empty($params['team_product_number'])){
    		$where['team_product_number'] = $params['team_product_number'];
    	}
    	if(!empty($params['team_product_id'])){
    		$where['team_product_id'] = $params['team_product_id'];
    	}
    	if(!empty($params['branch_product_number'])){
    		$where['branch_product_number'] = $params['branch_product_number'];
    	}
    	if(!empty($params['company_order_product_team_id'])){
    		$where['company_order_product_team_id'] = $params['company_order_product_team_id'];
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
    
    
    	$data['update_user_id'] = $params['now_user_id'];
    
    	$data['update_time'] = $t;
    
    
    
    	
    	$this->startTrans();
    	try{
    		//首先获得改团队产品所有信息
    		$find_data['company_order_product_team_id'] = $params['company_order_product_team_id'];
    		$find_result = $this->where($find_data)->find();
    		$this->where($where)->update($data);
    		if($params['status'] ==0){
    			//则把所有的都变成0
    			if($find_result['is_type'] == 2){
    				//删除资源 以及模板
    				$company_order_params['status'] = 0;
    				$company_order_product_source_params['company_order_number'] = $find_result['company_order_number'];
    				$company_order_product_source_params['team_product_id'] = $find_result['team_product_id'];
    				$company_order_product_source_params['is_type'] = 2;
    				$this->table('company_order_product_source')->where($company_order_product_source_params)->update($company_order_params);
    				
    				$this->table('company_order_product_template')->where($company_order_product_source_params)->delete();
    				
    				
    			}

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
     * 修改公司订单的团队产品根据应付编号
     */
    public function updateCompanyOrderTeamByCopeNumber($params){
    
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
    	if(!empty($params['team_product_name'])){
    		$data['team_product_name'] = $params['team_product_name'];
    	
    	}
    	if(!empty($params['team_product_price'])){
    		$data['team_product_price'] = $params['team_product_price'];
    
    	}
    	if(!empty($params['team_product_cost'])){
    		$data['team_product_cost'] = $params['team_product_cost'];
    
    	}
    	if(!empty($params['cost_currency_id'])){
    		$data['cost_currency_id'] = $params['cost_currency_id'];
    
    	}
    
    
 
    	if(!empty($params['cope_number'])){
    		$where['cope_number'] = $params['cope_number'];
    	}

    	if(!empty($params['receivable_number'])){
    		$data['receivable_number'] = $params['receivable_number'];
    	}
    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    
    	}
    
    
    	$data['update_user_id'] = $params['now_user_id'];
    
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
     * 修改公司订单的团队产品根据应收编号
     */
    public function updateCompanyOrderTeamByReceivableNumber($params){
    
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
    	if(!empty($params['team_product_price'])){
    		$data['team_product_price'] = $params['team_product_price'];
    
    	}
    	if(!empty($params['team_product_name'])){
    		$data['team_product_name'] = $params['team_product_name'];
    	
    	}
    	if(!empty($params['team_product_cost'])){
    		$data['team_product_cost'] = $params['team_product_cost'];
    
    	}
    	if(!empty($params['cost_currency_id'])){
    		$data['cost_currency_id'] = $params['cost_currency_id'];
    
    	}
    	if(!empty($params['is_receivable_company'])){
    		$data['is_receivable_company'] = $params['is_receivable_company'];
    	
    	}   
    	
    
    	if(!empty($params['receivable_number'])){
    		$where['receivable_number'] = $params['receivable_number'];
    	}
    

    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    
    	}
    
    
    	$data['update_user_id'] = $params['now_user_id'];
    
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
     * 更改状态
     */
    public function updateCompanyOrderTeamStatus($params){
    
    	if(!empty($params['company_order_number'])){
    		$where['company_order_number'] = $params['company_order_number'];
    		 
    	}
    	if(!empty($params['team_product_number'])){
    		$where['team_product_number'] = $params['team_product_number'];
    		 
    	}
    	if(!empty($params['team_product_id'])){
    		$where['team_product_id'] = $params['team_product_id'];
    		 
    	}   	
    	if(!empty($params['settlement_type'])){
    		$where['settlement_type'] = $params['settlement_type'];
    		 
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
     * 通过 ProductId 获取数据
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/4/22
     * Time: 17:32
     * @param int $product_id 团队产品ID
     * @return  mixed|array
     */
    public function getCompanyOrderProductTeamByProductId($product_id)
    {
        return $this->table("company_order_product_team")->where(['team_product_id' => $product_id, 'status' => 1])->select();
    }
}