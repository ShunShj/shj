<?php

namespace app\index\model\branchcompany;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class CompanyOrderCustomerlineup extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'company_order_customer_lineup';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

	//添加排队

    public function  addLineup($params){
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$lineup_type = $params['lineup_type'];
    	$company_order_number = $params['company_order_number'];
    	$company_order_id = $params['company_order_id'];

    	$customer_info = $params['customer_info'];

 		$team_product_result = $params['team_product_result'];
 		
    	$this->startTrans();
    	try{
    		$this->updateStatus($params);
			//首先查询现在编号为几



    		for($i=0;$i<count($customer_info);$i++){
    			 
    			$status_params = [
    				'status'=>1,
    				'update_user_id'=>$params['now_user_id'],
    				'update_time'=>time()	
    			];
    			//开始修改排队按钮
    			//查询如果有数据则修改状态为1否则走新增
    			$where = [
    				'company_order_number'=>$params['company_order_number'],
    				'lineup_type'=>1,
    				'company_order_customer_id'=>$customer_info[$i]['company_order_customer_id']	
    			];
    			$r= $this->where($where)->update($status_params);
    			if($r==0){//说明没数据要新增
    				//查询订单有多少用户
    				$c = $this->where("lineup_type = 1 and company_order_number = '".$params['company_order_number']."'")->count();
    				$company_order_linuup_params = [
    					'company_order_number'=>$params['company_order_number'],
    					'company_order_id'=>$params['company_order_id'],
    					'lineup_type'=>1,
    					'lineup_number'=>$c+1,	
    					'company_order_customer_id'=>$customer_info[$i]['company_order_customer_id'],
    					'customer_id'=>$customer_info[$i]['customer_id'],
    					'create_user_id'=>$params['now_user_id'],
    					'create_time'=>time(),
    					'update_user_id'=>$params['now_user_id'],
    					'update_time'=>time(),
    					'status'=>1	
    				];
    				$this->insert($company_order_linuup_params);
    			}
    			//$customer_id =$customer_info[$i]['customer_id'];
    			//$company_order_customer_id = $customer_info[$i]['company_order_customer_id'];

    			 
    			 
    		
    		}
    		
    		for($i=0;$i<count($team_product_result);$i++){
    			for($j=0;$j<count($customer_info);$j++){
    				//
    				$status_params = [
    					'status'=>1,
    					'update_user_id'=>$params['now_user_id'],
    					'update_time'=>time()
    				];
    				//开始修改排队按钮
    				//查询如果有数据则修改状态为1否则走新增
    				$where = [
    						
    						'lineup_type'=>2,
    						'team_product_id'=>$team_product_result[$i]['team_product_id'],
    						'company_order_customer_id'=>$customer_info[$j]['company_order_customer_id']
    				];
    				$r= $this->where($where)->update($status_params);
    			    if($r==0){//说明没数据要新增
    					//查询团队产品有多少排队者
    					$c = $this->where("lineup_type = 2  and team_product_id ='".$team_product_result[$i]['team_product_id']."'")->count();
    				
    					$company_order_linuup_params = [
    							'company_order_number'=>$params['company_order_number'],
    							'company_order_id'=>$params['company_order_id'],
    							'lineup_type'=>2,
    							'lineup_number'=>$c+1,
    							'team_product_id'=>$team_product_result[$i]['team_product_id'],
    							'team_product_number'=>$team_product_result[$i]['team_product_number'],
    							'company_order_customer_id'=>$customer_info[$j]['company_order_customer_id'],
    							'customer_id'=>$customer_info[$j]['customer_id'],
    							'create_user_id'=>$params['now_user_id'],
    							'create_time'=>time(),
    							'update_user_id'=>$params['now_user_id'],
    							'update_time'=>time(),
    							'status'=>1
    					];
    					$this->insert($company_order_linuup_params);
    				}
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
    	
   	
    }
	
    
    //查询排队
    public function getLineup($params){
    	$data='1=1 and status = 1';
    	if(!empty($params['team_product_number'])){
    		$data.=" and team_product_number='".$params['team_product_number']."'";
    	}
    	if(!empty($params['company_order_number'])){
    		$data.=" and company_order_number='".$params['company_order_number']."'";
    	}
    	if(!empty($params['company_order_number'])){
    		$data.=" and company_order_number='".$params['company_order_number']."'";
    	}
    	if(!empty($params['company_order_customer_id'])){
    		$data.=" and company_order_customer_id='".$params['company_order_customer_id']."'";
    	}
    	if(!empty($params['lineup_type'])){
    		$data.=" and lineup_type='".$params['lineup_type']."'";
    	}
    	$result  = $this->where($data)->order("lineup_number desc")->select();
    	return $result;
    }
    //游客兼排队信息
    public function getCustomerAndLinueup($params){
    	$data = '1=1';
    	if(!empty($params['team_product_number'])){
    		$data.=" and cocl.team_product_number='".$params['team_product_number']."'";
    	}
    	if(!empty($params['lineup_type'])){
    		$data.=" and cocl.lineup_type='".$params['lineup_type']."'";
    	}
    	if(is_numeric($params['status'])){
    		$data.=" and cocl.status=".$params['status'];
    	}
    
    	$result= $this->table("company_order_customer_lineup")->alias("cocl")->
    	join("company_order_customer coc",'coc.company_order_customer_id = cocl.company_order_customer_id','left')->
    	join("customer customer","customer.customer_id = coc.customer_id",'left')->
    	join("country country","customer.country_id = country.country_id",'left')->
    	join("company company","company.company_id = customer.company_id",'left')->
    	join("language language","customer.language_id = language.language_id",'left')->
    	join("company_order_accommodation coa","coa.company_order_number = coc.company_order_number and coa.customer_id = coc.customer_id",'left')->
    	where($data)->
    	field(['coc.customer_id','coc.company_order_customer_id',
    			'cocl.company_order_number','cocl.team_product_number',
    			"if(coc.customer_id=0,'占位',concat(customer.customer_first_name,' ',customer.customer_last_name)) customer_name",
    			'company.company_id','company.company_name',
    			'customer.customer_number',
    			'customer.customer_first_name','customer.customer_last_name',
    			'customer.english_first_name','customer.english_last_name',
    			'customer.customer_type','customer.gender','customer.phone',
    			'customer.email','customer.card_type','customer.card_number',
    			'customer.term_of_validity','customer.remark',
    			'customer.language_id','language.language_name',
    			'country.country_id as country_id','country.country_name as country_name',
    			'coa.check_in_hotel','coa.check_on_hotel',
    			"coa.room_code",'coa.room_type','coa.check_in','coa.check_on',
    			'cocl.lineup_number',
    			"(select nickname  from user where user.user_id = customer.create_user_id)"=>'create_user_name',
    			"(select nickname  from user where user.user_id = customer.update_user_id)"=>'update_user_name',
    			'coc.create_user_id','coc.create_time','coc.update_user_id',
    			'coc.update_time','coc.status'])->
    	
    			select();
    	
    	
    	return $result;
    }
    public function updateStatus($params){
    	$where['company_order_number'] = $params['company_order_number'];
    	if(!empty($params['team_product_number'])){
    		$where['company_order_number'] = $params['company_order_number'];
    	}
    	$where['lineup_type'] = $params['lineup_type'];
    	$data['status']=0;
    	$this->where($where)->update($data);
    	
    	
    }
}