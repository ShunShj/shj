<?php

namespace app\index\model\branchcompany;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class CompanyOrderProductTemplate extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'company_order_product_template';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加公司订单-产品模板
     * 胡
     */
    public function addCompanyOrderProductTeamplate($params){

        $t = time();
		$data['company_order_number'] = $params['company_order_number'];

		
		
		
		if(!empty($params['company_order_product_id'])){
			$data['company_order_product_id'] = $params['company_order_product_id'];
		}
		if(!empty($params['branch_product_number'])){
			$data['branch_product_number'] = $params['branch_product_number'];
		}
		if(!empty($params['is_type'])){
			$data['is_type'] = $params['is_type'];
		}
		$data['route_template_number'] = $params['route_template_number'];
		$data['team_product_id'] = $params['team_product_id'];

		$data['company_order_product_team_id'] = $params['company_order_product_team_id'];
		
 		
 		if(isset($params['cost_currency_id'])){
 			$data['cost_currency_id']=$params['cost_currency_id'];
 		}
        
      
		$data['create_time'] = $t;

		$data['create_user_id'] = $params['now_user_id'];


		
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
    public function getCompanyOrderProductTemplatem($params){

 
    	$data = "1=1";
    	if(!empty($params['company_order_number'])){
    		$data.= " and company_order_number = '".$params['company_order_number']."'";
    	}
    	if(is_numeric($params['is_type'])){
    		$data.= " and is_type = '".$params['is_type']."'";
    	}

    
            $result= $this->where($data)->
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
    			'co.company_id','co.locked',
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
            $this->where($where)->update($data);

            $result = 1;
            // 提交事务
            $this->commit();

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            $this->rollback();

        }
     //   error_log(print_r($this->getlastsql(),1));
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
     * 更改状态
     */
    public function updateCompanyOrderTeamStatus($params){
    
    	if(!empty($params['company_order_number'])){
    		$where['company_order_number'] = $params['company_order_number'];
    		 
    	}
    	if(!empty($params['team_product_number'])){
    		$where['team_product_number'] = $params['team_product_number'];
    		 
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
}