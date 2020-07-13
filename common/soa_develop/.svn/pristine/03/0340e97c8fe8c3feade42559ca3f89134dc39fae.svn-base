<?php

namespace app\index\model\branchcompany;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class BranchProductTeam extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'branch_product_team';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加分公司产品 团队产品
     * 王
     */
    public function addBranchProductTeam($params)
    {
        $t = time();

        $data['branch_product_number'] = $params['branch_product_number'];
        $data['team_product_number'] = $params['team_product_number'];
        $data['team_product_name'] = $params['team_product_name'];
        $data['supplier_name'] = $params['supplier_name'];
    
        $data['branch_cost'] = (float)$params['branch_cost'];
        $data['branch_distributor_price'] = (float)$params['branch_distributor_price'];
        $data['branch_customer_price'] = (float)$params['branch_customer_price'];
        
        
        
        $data['cost_currency_id'] = (float)$params['cost_currency_id'];
        $data['price_currency_id'] = (float)$params['price_currency_id'];
        
        $data['settlement_type'] = $params['settlement_type'];
        $data['create_time'] = $t;
        $data['create_user_id'] = $params['now_user_id'];
        $data['update_time'] = $t;
        $data['update_user_id'] = $params['now_user_id'];
        $data['status'] =1;
		
        $this->startTrans();
        try{
            $pk_id = $this->insertGetId($data);
            $pk_number = $data['branch_product_number'];
            // 提交事务
            $this->commit();
            $result =  $pk_id;

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            $this->rollback();

        }
     
		
         return $result;
    }
    /**
     * 获取分公司产品团队产品
     * 王
     */
    public function getBranchProductTeam($params){

    	$data = "1=1";
       if(!empty($params['branch_product_team_id'])){
           $data.= " and branch_product_team.branch_product_team_id = ".$params['branch_product_team_id'];
       }
        if(!empty($params['branch_product_number'])){
            $data.= " and branch_product_team.branch_product_number = '".$params['branch_product_number']."'";
        }
        if(!empty($params['team_product_number'])){
           $data.= " and branch_product_team.team_product_number = '".$params['team_product_number']."'";
       }
       if(!empty($params['team_product_name'])){
          $data.= " and branch_product_team.team_product_name = '".$params['team_product_name']."'";
       }
        if(!empty($params['supplier_name'])){
            $data.= " and branch_product_team.supplier_name = '".$params['supplier_name']."'";
        }
        if(!empty($params['plan_custom_number'])){
            $data.= " and branch_product_team.plan_custom_number = ".$params['plan_custom_number'];
        }

        if(is_numeric($params['status'])){
            $data.= " and branch_product_team.status = ".$params['status'];
        }
     
        
        $result= $this->table("branch_product_team")->alias("branch_product_team")->
        join('team_product','branch_product_team.team_product_number = team_product.team_product_number','left')->
        where($data)->
        field(['branch_product_team.branch_product_team_id','branch_product_team.branch_product_number',
            'branch_product_team.team_product_number','branch_product_team.team_product_name',
            'branch_product_team.supplier_name',
            'branch_product_team.branch_distributor_price',
        	'branch_product_team.branch_customer_price',
        	'branch_product_team.branch_cost',
        		
            'branch_product_team.price_currency_id','branch_product_team.cost_currency_id',
        	"(select currency_name  from currency where currency.currency_id = branch_product_team.price_currency_id)"=>'price_currency_name',
        	"(select currency_name  from currency where currency.currency_id = branch_product_team.cost_currency_id)"=>'cost_currency_name',
        	'branch_product_team.supplier_name',
            'branch_product_team.status',
        	'team_product.plan_custom_number',	
        	'team_product.begin_time',
            "(select nickname  from user where user.user_id = branch_product_team.create_user_id)"=>'create_user_name',
            "(select nickname  from user where user.user_id = branch_product_team.update_user_id)"=>'update_user_name'
        ])->select();

        return $result;

    }


    /**
     * 修改分公司产品 团队产品 根据branch_product_number
     */
    public function updateBranchProductTeamByBranchProductNumber($params){

        $t = time();

        if(is_numeric($params['status'])){
            $data['status'] = $params['status'];

        }
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;

		$data_where['branch_product_number'] = $params['branch_product_number'];
		$data_where['team_product_number'] = $params['team_product_number'];
        $this->startTrans();
        try{
            $this->table('branch_product_team')->where($data_where)->update($data);
            $this->table('branch_product_source')->where($data_where)->update($data);
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
	
	//修改报价与货币根据主键
	public function updateBranchProductTeamPriceAndCurrencyIdByBranchProductTeamId($params){
		$t = time();
		
	
		$data['price_currency_id'] = $params['price_currency_id'];
		$data['branch_price'] = $params['branch_price'];
	

		$data['update_time'] = $t;
		
		$data_where['branch_product_team_id'] = $params['branch_product_team_id'];
	
		$this->startTrans();
		try{
			$this->where($data_where)->update($data);

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