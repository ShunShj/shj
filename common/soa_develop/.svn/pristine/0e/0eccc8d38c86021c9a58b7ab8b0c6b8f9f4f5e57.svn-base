<?php
namespace app\index\model\system_alert_event;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class Communal extends Model{
	
	private $_languageList;

    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize(); 
    }

    /**
    * 根据资源id和资源类型获得团队产品负责人
    */
    public function getTeamProductAllocationForTeamProductUserId($supplier_type_id,$source_id){
    	$where['supplier_type_id'] = $supplier_type_id;
    	$where['source_id'] = $source_id;
    	$team_product_user_ar = $this->table('team_product')
    	->join('team_product_allocation','team_product_allocation.team_product_id=team_product.team_product_id','left')->where($where)->field([
    		'team_product.team_product_user_id',
    		"(select user.email from user where user_id=team_product.team_product_user_id) as email", 
    	])->group('team_product.team_product_user_id')->select();

    	return $team_product_user_ar;
    }

    /**
    * 根据线路模板id 获取 团队产品负责人
    */
    public function getTeamProductUserIdByRouteTemplateId($route_template_id){
    	$where['route_template_id'] = $route_template_id;
    	$team_product_user_ar = $this->table('team_product')->where($where)->field([
    		'team_product.team_product_user_id',
    		"(select user.email from user where user_id=team_product.team_product_user_id) as email",
    	])->group('team_product.team_product_user_id')->select();

    	return $team_product_user_ar;
    }


    /**
	* 根据团队产品ID获取订单负责人
    */
	public function getAnOrderOwnerBasedOnTheTeamProductId($team_product_id){
		$team_product_number = $this->table("team_product")->where("team_product.team_product_id={$team_product_id}")->field('team_product.team_product_number')->find();


		$where = "company_order_product_team.team_product_number='{$team_product_number['team_product_number']}' and company_order_product_team.status=1 and company_order.status=1";
		// echo $where;exit;
		$team_product_user_ar = $this->table('company_order')->join('company_order_product_team','company_order_product_team.company_order_number=company_order.company_order_number','left')->where($where)->field([
				'company_order.create_user_id',
				'company_order.company_id',
				'company_order_product_team.team_product_number',
				'company_order.company_order_number',
				"(select user.email from user where user_id=company_order.create_user_id) as email",
		])->select();
		// echo $this->table()->getLastsql();exit;
        error_log(print_r(Help::modelDataToArr($team_product_user_ar),1));
		return $team_product_user_ar;
	}

    /**
     * shj
     */
    public function getAnOrderOwnerBasedOnTheTeamProductIds($team_product_id){

        $where = "company_order_product_team.team_product_id='{$team_product_id}' and company_order_product_team.status=1 and company_order.status=1";
        // echo $where;exit;
        $team_product_user_ar = $this->table('company_order')
            ->join('company_order_product_team','company_order_product_team.company_order_number=company_order.company_order_number','left')
            ->join('team_product','team_product.team_product_id = company_order_product_team.team_product_id','left')
            ->where($where)
            ->field(['company_order.create_user_id', 'company_order.company_id', 'company_order.company_order_number', "(select user.email from user where user_id=company_order.create_user_id) as email",'team_product.team_product_number'])
            ->select();
        // echo $this->table()->getLastsql();exit;
        error_log(print_r(Help::modelDataToArr($team_product_user_ar),1));
        return $team_product_user_ar;
    }

	/****
	* 根据游客ID获取订单负责人
	*/
	public function getAnOrderOwnerBasedAccordingToTheTouristId($company_order_customer_id){
		$where['lineup_type'] = 2; $where['company_order_customer_id'] = $company_order_customer_id; $where['status'] = 1;
		$company_order_number = $this->table('company_order_customer_lineup')->where($where)->field('company_order_number')->find();
		unset($where);
		//var_dump($company_order_number);exit;

		$where['company_order_number'] = $company_order_number['company_order_number'];
		$company_order_user_ar = $this->table('company_order')->where($where)->field([
				'company_order.create_user_id',
				'company_order.company_order_number',
				"(select user.email from user where user_id=company_order.create_user_id) as email", 
		])->select();
		return $company_order_user_ar;
	} 


	/***
	* 获取分公司财务id和邮箱
	*/
	public function getBrachFinance($company_id_ar){
		if(empty($company_id_ar)){
			return;
		}

		$company_id = implode(',', $company_id_ar);
		$where = "job.company_id in ({$company_id}) and job.job_name='accountant'";

        $job_id = $this->table('job')->where($where)->column('job_id');

		// var_dump($where);exit;
        $userInfo = $this->table('user')
            ->where('job_id', 'IN', $job_id)
            ->field(['user_id','email'])
            ->select();
        return $userInfo;
	}

	/**
	* 根据订单编号获取订单负责人
	*/
	public function getAnOrderOwnerBasedAccordingToCompanyOrderNumber($company_order_number_ar){
		$company_order_user_ar = [];
		$uidAr = [];
		foreach ($company_order_number_ar as $key => $value) {
		 	$where = "company_order.company_order_number = '{$value}' and company_order.status=1";
			$obj = $this->table('company_order')->join('user','user.user_id=company_order.create_user_id')->where($where)->field([
				'user.user_id','user.email','user.company_id'
			])->group('company_order.create_user_id')->find();
		 	if(!in_array($obj['user_id'], $uidAr)){
		 		$uidAr[] = $obj['user_id'];
		 		$company_order_user_ar[] = $obj;
		 	}
		 }

		return $company_order_user_ar;
	}

    /**
     * shj
     */
    public function getAnOrderOwnerBasedAccordingToCompanyOrderNumbers($company_order_number){
        $company_order_user_ar = [];
        $uidAr = [];

            $where = "company_order.company_order_number = '$company_order_number' and company_order.status=1";
            $obj = $this->table('company_order')->join('user','user.user_id=company_order.create_user_id')->where($where)->field([
                'user.user_id','user.email','user.company_id'
            ])->group('company_order.create_user_id')->find();
            if(!in_array($obj['user_id'], $uidAr)){
                $uidAr[] = $obj['user_id'];
                $company_order_user_ar[] = $obj;
            }

        return $company_order_user_ar;
    }


	/**
	* 根据订单编号获取团队产品负责人
	*/
	public function getTeamLeaderToCompanyOrderNumber($company_order_number){
		$where = "company_order_product_team.company_order_number='{$company_order_number}' and company_order_product_team.status=1";
		$user_ar = $this->table('company_order_product_team')->join('team_product','team_product.team_product_number=company_order_product_team.team_product_number')
		->where($where)->field([
			'team_product.team_product_id',
			'team_product.team_product_number',
			'team_product.team_product_user_id',
			"(select user.email from user where user_id=team_product.team_product_user_id) as email",
		])->select();
		return $user_ar;
	}


}