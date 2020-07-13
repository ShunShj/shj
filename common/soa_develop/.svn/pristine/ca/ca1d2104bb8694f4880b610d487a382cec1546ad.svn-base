<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/15
 * Time: 14:33
 */

namespace app\index\model\finance;

use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use think\config;
use think\Db;
use app\index\model\finance\Receivable;
use app\index\model\finance\ReceivableCustomer;
class Cope extends Model{

    //protected $connection = ['database' => 'erp'];
    protected $table = 'cope';
    private $_languageList;
    private $_receivable_customer;
    private $_public_service;
    //private $_receivable;
    public function initialize()
    {
    	$this->_receivable_customer = new ReceivableCustomer();
    
        $this->_languageList = config('systom_setting')['language_list'];
        $this->_public_service = new PublicService();
        parent::initialize();

    }

    /****
    * 获取发团毛利统计
    * Hugh
    */
    public function grossProfitStatistics($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
        $where = 'team_product.status=1';
        if($params['sDate']){
            $where .= " and team_product.begin_time>={$params['sDate']}";
        }
        if($params['eDate']){
            $where .= " and team_product.begin_time<={$params['eDate']}";
        }
        if($params['company_id']){ 
            $where .= " and team_product.company_id={$params['company_id']}";
        }
        if($params['company_id_1']){
            $where .= " and team_product.company_id={$params['company_id_1']}";
        }

        if($params['route_type_id']){ 
            $where .= " and team_product.route_type_id={$params['route_type_id']}";    
        }
        if($params['team_product_number']){ 
            $where .= " and team_product.team_product_number='{$params['team_product_number']}'";
        }
        if($params['team_product_name']){
            $where .= " and team_product.team_product_name like '%{$params['team_product_name']}%'";
        }    
        if($params['nickname']){
            $usre = $this->table('user')->where("nickname like '%{$params['nickname']}%'")->select();
            $user_id = 0;
            if($usre[0]['user_id']){
                $user_id = $usre[0]['user_id'];
            }
            $where .= " and team_product.team_product_user_id={$user_id}";
        }
        if($params['supplier_id']){
            $w['receivable_object_id'] = $params['supplier_id'];
            $w['status'] = 1;
            if($params['company_id']){ 
                $w['company_id'] = $params['company_id'];
            }
            $team_product_id_ar = $this->field(['team_product_id'])->table('cope')->where($w)->select();
           // error_log(print_r($team_product_id_ar,1));
           $team_product_id = [];
            foreach ($team_product_id_ar as $key => $value) {
               $team_product_id[] = $value['team_product_id'];     
            }
            // error_log(print_r($team_product_id,1));
            if(count($team_product_id)>0){
                $team_product_id_ar2 = implode(',',$team_product_id);
                // error_log(print_r($team_product_id_ar2,1));
                $where .= " and team_product.team_product_id in ($team_product_id_ar2)";
            }
        }

        // error_log($where);

        if($is_count==true){
            $result = $this->table("team_product")->where($where)->count();
        }else{
            if($is_page == true) {
                $result = $this->table("team_product")->where($where)->limit($page,$page_size)->field([
                    'team_product.team_product_id',
                    'team_product.team_product_number',
                    'team_product.team_product_name',
                    'team_product.begin_time',
                	'team_product.company_id',
                    '(select route_type.route_type_name from route_type where route_type_id=team_product.route_type_id ) as route_type_name',
                    '(select company.company_name from company where company.company_id=team_product.company_id) as company_name',
                    '(select user.nickname from user where  user.user_id=team_product.team_product_user_id) as nickname',
                    '(select count(company_order_customer_lineup_id) from company_order_customer_lineup where company_order_customer_lineup.team_product_number=team_product.team_product_number and company_order_customer_lineup.status=1)  as number_of_people',
                    '(select currency.currency_name FROM currency where currency.currency_id =(select company.currency_id from company where company.company_id=team_product.company_id)) as currency_name',
                    '(select sum(cope.cope_money) from cope where cope.team_product_id=team_product.team_product_id and cope.status=1 and cope.company_id = team_product.company_id) as cope_money',
                    '(select sum(cope_info.receivable_money) from  cope_info   JOIN cope on cope.cope_number=cope_info.cope_number where cope.team_product_number=team_product.team_product_number and cope.status=1 and cope_info.status=1) as cope_receivable_money',
                    '(select sum(receivable.receivable_money) from receivable where receivable.team_product_id=team_product.team_product_id and receivable.status=1 and receivable.company_id = team_product.company_id) as receivable_money',
                    '(select sum(receivable_info.payment_money) from receivable_info RIGHT JOIN receivable on receivable.receivable_number=receivable_info.receivable_number where receivable.team_product_number=team_product.team_product_number and receivable.status=1 and receivable_info.status=1) as payment_money'])->select();
            }else{
                $result = $this->table("team_product")->where($where)->field([
                    'team_product.team_product_id','team_product.team_product_number',
                    'team_product.team_product_name',
                    'team_product.begin_time',
                	'team_product.company_id',
                    '(select route_type.route_type_name from route_type where route_type_id=team_product.route_type_id ) as route_type_name',
                    '(select company.company_name from company where company.company_id=team_product.company_id) as company_name',
                    '(select user.nickname from user where  user.user_id=team_product.team_product_user_id) as nickname',
                    '(select count(company_order_customer_lineup_id) from company_order_customer_lineup where company_order_customer_lineup.team_product_number=team_product.team_product_number and company_order_customer_lineup.status=1)  as number_of_people',
                    '(select currency.currency_name FROM currency where currency.currency_id =(select company.currency_id from company where company.company_id=team_product.company_id)) as currency_name',
                    '(select sum(cope.cope_money) from cope where cope.team_product_id=team_product.team_product_id and cope.status=1 and cope.company_id = team_product.company_id) as cope_money',
                    '(select sum(cope_info.receivable_money) from  cope_info   JOIN cope on cope.cope_number=cope_info.cope_number where cope.team_product_number=team_product.team_product_number and cope.status=1 and cope_info.status=1) as cope_receivable_money',
                    '(select sum(receivable.receivable_money) from receivable where receivable.team_product_id=team_product.team_product_id and receivable.status=1 and receivable.company_id = team_product.company_id ) as receivable_money',
                    '(select sum(receivable_info.payment_money) from receivable_info RIGHT JOIN receivable on receivable.receivable_number=receivable_info.receivable_number where receivable.team_product_number=team_product.team_product_number and receivable.status=1 and receivable_info.status=1) as payment_money'])->select();
            }
        }
      
      // echo $this->table("team_product")->getLastSql();exit;
      return $result;

    }


    /***
    * 获取成本报表（支出）
    * Hugh
    */
    public function costSheet($params){
        $where = 'cope.status=1';
        if($params['sDate']){ 
            $where .= " and team_product.begin_time>={$params['sDate']}";
        }   
        if($params['eDate']){
            $where .= " and team_product.begin_time<={$params['eDate']}";
        }
        if($params['company_id']){ 
            $where .= " and team_product.company_id={$params['company_id']}";
        }
        if($params['route_type_id']){ 
            $where .= " and team_product.route_type_id={$params['route_type_id']}";    
        }
        if($params['team_product_number']){ 
            $where .= " and team_product.team_product_number='{$params['team_product_number']}'";
        }
        if($params['team_product_name']){
            $where .= " and team_product.team_product_name like '%{$params['team_product_name']}%'";
        }    
        if($params['nickname']){
            $usre = $this->table('user')->where("nickname like '%{$params['nickname']}%'")->select();
            $user_id = 0;
            if($usre[0]['user_id']){
                $user_id = $usre[0]['user_id'];
            }
            $where .= " and team_product.team_product_user_id={$user_id}";
        }    
        
        $result= $this->table("cope")
        ->join('team_product','team_product.team_product_number=cope.team_product_number')
        ->join('cope_info','cope_info.cope_number=cope.cope_number','left')
        ->where($where)
        
        ->field([
            'team_product.team_product_number',
            'team_product.team_product_name',
            '(select route_type.route_type_name from route_type where route_type_id=team_product.route_type_id ) as route_type_name',
            'team_product.begin_time',
            '(select company.company_name from company where company.company_id=team_product.company_id) as company_name',
            '(select user.nickname from user where  user.user_id=team_product.team_product_user_id) as nickname',
            '(select count(company_order_customer_lineup_id) from company_order_customer_lineup where company_order_customer_lineup.team_product_number=team_product.team_product_number and company_order_customer_lineup.status=1)  as number_of_people',
            'sum(cope.cope_money) as sum_cope_money',
            'sum(cope_info.receivable_money) as sum_receivable_money',
            '(select currency.currency_name FROM currency where currency.currency_id =(select company.currency_id from company where company.company_id=team_product.company_id)) as currency_name'
        ])
        ->group('team_product.team_product_number')->select();

        return $result;
    }

    /**
     * 添加应付
     * 胡
     */
    public function addCope($params){
        $t = time();
		$user_id = $params['now_user_id'];
        $data['cope_number'] = Help::getNumber(6,2);
        
        //订单
        if(!empty($params['order_number'])){ 
        	$data['order_number'] = $params['order_number'];
        }
        if(!empty($params['team_product_number'])){
        	$data['team_product_number'] = $params['team_product_number'];
        }
        if(!empty($params['team_product_id'])){
        	$data['team_product_id'] = $params['team_product_id'];
        }
        if(!empty($params['product_name'])){
        	$data['product_name'] = $params['product_name'];
        }
        if(!empty($params['source_type_id'])){
        	$data['source_type_id'] = $params['source_type_id'];
        }
        if(!empty($params['source_number'])){
        	$data['source_number'] = $params['source_number'];
        }
        if(!empty($params['receivable_object_type'])){
        	$data['receivable_object_type'] = $params['receivable_object_type'];
        }
        if(!empty($params['receivable_object_id'])){
        	$data['receivable_object_id'] = $params['receivable_object_id'];
        }

        if(!empty($params['cope_currency_id'])){
        	$data['cope_currency_id'] = $params['cope_currency_id'];
        }
        if(!empty($params['travel_agency_reimbursement_number'])){
        	$data['travel_agency_reimbursement_number'] = $params['travel_agency_reimbursement_number'];
        }
        if(!empty($params['travel_agency_reimbursement_cope_id'])){
        	$data['travel_agency_reimbursement_cope_id'] = $params['travel_agency_reimbursement_cope_id'];
        }
        if(!empty($params['price'])){
        	$data['price'] = $params['price'];
        }
        if(is_numeric($params['unit'])){
        	$data['unit'] = $params['unit'];
        }
        if(!empty($params['invoice_number'])){
        	$data['invoice_number'] = $params['invoice_number'];
        }
        if(!empty($params['invoice_time'])){
        	$data['invoice_time'] = $params['invoice_time'];
        }
        $data['cope_money'] = $params['cope_money'];
     

        $data['public_number'] = $data['cope_number'];
        
        $data['company_id'] = $params['company_id'];

        
        $data['fee_type_code'] = $params['fee_type_code'];
        $data['fee_type_type'] = $params['fee_type_type'];
        $data['is_auto'] = $params['is_auto'];
        $data['create_time'] = $t;
        $data['update_time'] = $t;
        $data['create_user_id'] = $user_id;
        $data['update_user_id'] = $user_id;
		$data['status'] = 1;
		
		
		
		
		$receivable_count = 0;
        //$params
        //判断 如果是 应付公司那么就 会有应收公司
        if($params['receivable_object_type'] ==1){
        	$data_receivable['receivable_number'] = Help::getNumber(5,2);
            if(!empty($params['order_number'])){ 
        		$data_receivable['order_number'] = $params['order_number'];
        	}
        	if(!empty($params['team_product_number'])){
        		$data_receivable['team_product_number'] = $params['team_product_number'];
        	}
        	
        	if(!empty($params['team_product_id'])){
        		$data_receivable['team_product_id'] = $params['team_product_id'];
        	}
        	if($params['fee_type_code']==70 ){
        		$data_receivable['fee_type_code'] = 34;
        		$data_receivable['fee_type_type'] = 201;
        	}else if($params['fee_type_code']==289){
        		$data_receivable['fee_type_code'] = 45;
        		$data_receivable['fee_type_type'] = 201;
        	}else if($params['fee_type_code']==290){
        		$data_receivable['fee_type_code'] = 36;
        		$data_receivable['fee_type_type'] = 201;
        	}else if($params['fee_type_code']==291){
        		$data_receivable['fee_type_code'] = 37;
        		$data_receivable['fee_type_type'] = 201;
        	}else if($params['fee_type_code']==292){
        		$data_receivable['fee_type_code'] = 37;
        		$data_receivable['fee_type_type'] = 201;
        	}else if($params['fee_type_code']==293){
        		$data_receivable['fee_type_code'] = 38;
        		$data_receivable['fee_type_type'] = 201;
        	}else if($params['fee_type_code']==294){
        		$data_receivable['fee_type_code'] = 39;
        		$data_receivable['fee_type_type'] = 201;
        	}else if($params['fee_type_code']==295){
        		$data_receivable['fee_type_code'] = 40;
        		$data_receivable['fee_type_type'] = 201;
        	}else if($params['fee_type_code']==296){
        		$data_receivable['fee_type_code'] = 41;
        		$data_receivable['fee_type_type'] = 201;
        	}else if($params['fee_type_code']==297){
        		$data_receivable['fee_type_code'] = 42;
        		$data_receivable['fee_type_type'] = 201;
        	}else if($params['fee_type_code']==298){
        		$data_receivable['fee_type_code'] = 43;
        		$data_receivable['fee_type_type'] = 201;
        	}else if($params['fee_type_code']==299){
        		$data_receivable['fee_type_code'] = 44;
        		$data_receivable['fee_type_type'] = 201;
        	}else if($params['fee_type_code']==300){
        		$data_receivable['fee_type_code'] = 45;
        		$data_receivable['fee_type_type'] = 201;
        	}else if($params['fee_type_code']==301){
        		$data_receivable['fee_type_code'] = 302;
        		$data_receivable['fee_type_type'] = 201;
        	}
        	
        	else if($params['fee_type_code']==316){
        		$data_receivable['fee_type_code'] = 328;
        		$data_receivable['fee_type_type'] = 209;
        	}else if($params['fee_type_code']==317){
        		$data_receivable['fee_type_code'] = 329;
        		$data_receivable['fee_type_type'] = 209;
        	}else if($params['fee_type_code']==318){
        		$data_receivable['fee_type_code'] = 330;
        		$data_receivable['fee_type_type'] = 209;
        	}else if($params['fee_type_code']==319){
        		$data_receivable['fee_type_code'] = 331;
        		$data_receivable['fee_type_type'] = 209;
        	}else if($params['fee_type_code']==320){
        		$data_receivable['fee_type_code'] = 332;
        		$data_receivable['fee_type_type'] = 209;
        	}else if($params['fee_type_code']==321){
        		$data_receivable['fee_type_code'] = 333;
        		$data_receivable['fee_type_type'] = 209;
        	}else if($params['fee_type_code']==322){
        		$data_receivable['fee_type_code'] = 334;
        		$data_receivable['fee_type_type'] = 209;
        	}else if($params['fee_type_code']==323){
        		$data_receivable['fee_type_code'] = 335;
        		$data_receivable['fee_type_type'] = 209;
        	}else if($params['fee_type_code']==324){
        		$data_receivable['fee_type_code'] = 336;
        		$data_receivable['fee_type_type'] = 209;
        	}else if($params['fee_type_code']==325){
        		$data_receivable['fee_type_code'] = 337;
        		$data_receivable['fee_type_type'] = 209;
        	}else if($params['fee_type_code']==326){
        		$data_receivable['fee_type_code'] = 338;
        		$data_receivable['fee_type_type'] = 209;
        	}else if($params['fee_type_code']==327){
        		$data_receivable['fee_type_code'] = 339;
        		$data_receivable['fee_type_type'] = 209;
        	}else if($params['fee_type_code']==358){
        		$data_receivable['fee_type_code'] = 345;
        		$data_receivable['fee_type_type'] = 2012;
        	}else if($params['fee_type_code']==359){
        		$data_receivable['fee_type_code'] = 346;
        		$data_receivable['fee_type_type'] = 2012;
        	}else if($params['fee_type_code']==360){
        		$data_receivable['fee_type_code'] = 347;
        		$data_receivable['fee_type_type'] = 2011;
        	}else if($params['fee_type_code']==361){
        		$data_receivable['fee_type_code'] = 348;
        		$data_receivable['fee_type_type'] = 2011;
        	}else if($params['fee_type_code']==362){
        		$data_receivable['fee_type_code'] = 349;
        		$data_receivable['fee_type_type'] = 2011;
        	}else if($params['fee_type_code']==363){
        		$data_receivable['fee_type_code'] = 350;
        		$data_receivable['fee_type_type'] = 2011;
        	}else if($params['fee_type_code']==364){
        		$data_receivable['fee_type_code'] = 351;
        		$data_receivable['fee_type_type'] = 2011;
        	}else if($params['fee_type_code']==365){
        		$data_receivable['fee_type_code'] = 352;
        		$data_receivable['fee_type_type'] = 2011;
        	}else if($params['fee_type_code']==366){
        		$data_receivable['fee_type_code'] = 353;
        		$data_receivable['fee_type_type'] = 2011;
        	}else if($params['fee_type_code']==367){
        		$data_receivable['fee_type_code'] = 354;
        		$data_receivable['fee_type_type'] = 2011;
        	}else if($params['fee_type_code']==368){
        		$data_receivable['fee_type_code'] = 355;
        		$data_receivable['fee_type_type'] = 2011;
        	}else if($params['fee_type_code']==369){
        		$data_receivable['fee_type_code'] = 356;
        		$data_receivable['fee_type_type'] = 2011;
        	}else if($params['fee_type_code']==370){
        		$data_receivable['fee_type_code'] = 357;
        		$data_receivable['fee_type_type'] = 2011;
        	}else if($params['fee_type_code']==372){
        		$data_receivable['fee_type_code'] = 371;
        		$data_receivable['fee_type_type'] = 2011;
        	}
        	
        	if(!empty($params['product_name'])){
        		$data_receivable['product_name'] = $params['product_name'];
        	}
 
        	if(!empty($params['source_type_id'])){
        		$data_receivable['source_type_id'] = $params['source_type_id'];
        	}
        	if(!empty($params['source_number'])){
        		$data_receivable['source_number'] = $params['source_number'];
        	}
        
        	$data_receivable['payment_object_id'] = $params['company_id'];
        
        	if(!empty($params['receivable_object_type'])){
        		$data_receivable['payment_object_type'] = 1;
        	}
        	
        	
        	
        	
        	$data_receivable['receivable_money'] = $params['cope_money'];
        	
        	if(!empty($params['cope_currency_id'])){
        		$data_receivable['receivable_currency_id'] = $params['cope_currency_id'];
        	}        	
        
        	$data_receivable['public_number'] = $data['cope_number'];
        	
        	$data_receivable['is_auto'] = $params['is_auto'];
        	$data_receivable['company_id'] = $params['receivable_object_id'];
        	$data_receivable['create_time'] = $t;
        	$data_receivable['create_user_id'] = $user_id;
        	$data_receivable['update_time'] = $t;
        	$data_receivable['update_user_id'] = $user_id;
        	$data_receivable['status'] = 1;
        	$receivable_count = 1;
        }
		




        
        $this->startTrans();
        try{
        	
            $pk_id = $this->insertGetId($data);
            $pk_number = $this->_public_service->setNumber('cope', 'cope_id', $pk_id, 'cope_number',Help::getNumber(6), $pk_id);
            
            if($receivable_count == 1){
            	
            	
            	$pk_id = $this->table('receivable')->insertGetId($data_receivable);
            	
            	$receivable_number = $this->_public_service->setNumber('receivable', 'receivable_id', $pk_id, 'receivable_number', Help::getNumber(5), $pk_id);
            	 
            	
            	$receivable_customer_params = [
            		'company_order_customer_id'=>$params['company_order_customer_id'],
            		'receivable_number'=>$receivable_number,
            		'now_user_id'=>$user_id	
            	];
            	
            	$this->_receivable_customer->addReceivableCustomer($receivable_customer_params);
            }
            //
         

            // 提交事务
            $this->commit();
            $result = $pk_number;

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            $this->rollback();

        }
       
        return $result;
    }
    /**
     * 自动添加 //暂时没用
     * 王
     */
    public function addCopeAuto($params){

        $t = time();
        $data['cope_number'] = Help::getNumber(6);
        if(isset($params['invoice_number'])){
        	$data['invoice_number'] = $params['invoice_number'];
        }
        if(isset($params['invoice_time'])){
        	$data['invoice_time'] = $params['invoice_time'];
        }        
        if(isset($params['order_number'])){
        	$data['order_number'] = $params['order_number'];
        }  
        if(isset($params['product_type'])){
        	$data['product_type'] = $params['product_type'];
        }
        $data['product_name'] = $params['product_name'];

        $data['receivable_company_id'] = $params['receivable_company_id'];
        $data['source_type_id'] = $params['source_type_id'];

        $data['cope_currency_id'] = $params['cope_currency_id'];
        $data['cope_money'] = $params['cope_money'];

		if(isset($params['public_number'])){
			$data['public_number'] = $params['public_number'];
		}
		$data['company_id'] = $params['user_company_id'];
        $data['resource_type']= $params['resource_type'];//手动添加
        $data['create_time'] = $t;
        $data['create_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        $data['update_user_id'] = $params['user_id'];
        $data['status'] = $params['status'];

	
        $this->startTrans();
        try{
            $pk_id = $this->insertGetId($data);
           
            $pk_number = $data['cope_number'];

            // 提交事务
            $this->commit();
            $result = $pk_number;

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            $this->rollback();

        }

        return $result;
    }
    
    /**
     * 团队产品 自动添加
     */
    public function addCopeByTeamProduct($params){
    	
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$cope_info = $params['cope_info'];
    	$company_id = $params['user_company_id'];
    	$team_product_number = $params['team_product_number'];
    	//$sql ='insert into cope (cope_number,team_product_number,product_type,resource_type,source_type_id,supplier_name,product_name,cope_currency_id,company_id,price,unit,cope_money,create_user_id,update_user_id,create_time,update_time,status) values';
    	$sql_count = 0;

    	
    	$this->startTrans();
    	try{
    		$cope_data['status']=0;
    		$this->where("team_product_number = '".$team_product_number."'")->update($cope_data);
    	    for($i=0;$i<count($cope_info);$i++){
	    		//$cope_number = Help::getNumber(6);
	    		if(is_numeric($cope_info[$i]['source_type_id'])){
	    			$source_type_id = $cope_info[$i]['source_type_id'];
	    		}else{
	    			$source_type_id = 0;
	    		}
	    		
	    		$supplier_id = $cope_info[$i]['supplier_id'];
	    		$product_name = $cope_info[$i]['product_name'];
	    		$product_type = $cope_info[$i]['product_type'];
	    		$cope_currency_id = $cope_info[$i]['cope_currency_id'];
	    		$cope_number = $cope_info[$i]['cope_number'];
	    		$price = $cope_info[$i]['price'];
	    		$unit = $cope_info[$i]['unit'];
	    		$cope_money = $cope_info[$i]['cope_money'];
	    		if(!empty($cope_number)){//如果有数据 那走修改
	    			$data['source_type_id'] = $source_type_id;
	    			$data['supplier_id'] = $supplier_id;
	    			$data['product_name'] = $product_name;
	    			$data['cope_currency_id'] = $cope_currency_id;
	    			$data['product_type'] = $product_type;
	    			$data['price'] = $price;
	    			$data['unit'] = $unit;
	    			$data['cope_money'] = $cope_money;
	    			$data['update_time'] = $t;
	    			$data['update_user_id'] = $user_id;
	    			$data['status'] = 1;
	    			$this->table("cope")->where("cope_number = '".$cope_number."'")->update($data);
	    		}else{//新增
	    			$data['cope_number'] = Help::getNumber(6);
	    			$data['team_product_number'] = $team_product_number;
	    			$data['source_type_id'] = $source_type_id;
	    			$data['supplier_id'] = $supplier_id;
	    			$data['product_name'] = $product_name;
	    			$data['cope_currency_id'] = $cope_currency_id;
	    			$data['product_type'] = $product_type;
	    			$data['company_id'] = $company_id;
	    			
	    			$data['resource_type'] = 1;
	    			$data['price'] = $price;
	    			$data['unit'] = $unit;
	    			$data['cope_money'] = $cope_money;
	    			$data['create_time'] = $t;
	    			$data['create_user_id'] = $user_id;
	    			$data['update_time'] = $t;
	    			$data['update_user_id'] = $user_id;
	    			$data['status'] = 1;
	    			$this->table("cope")->insert($data);	    			
	    		}

    		}
    	
    		// 提交事务
    		$this->commit();
    		$result = 1;
    	
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		$this->rollback();
    	
    	}
    	
    	return $result;
    }
    /**
     * 获取应付
     * 王
     */
    public function getCope($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
        $data ="1=1";
        
        if($params['is_like'] == 1){ //开启模糊
        	if(!empty($params['product_name'])){
        		$data.= " and cope.product_name like'%".$params['product_name']."%'";
        	}
        	if(!empty($params['order_number'])){
        		$data.=" and order_number like '%".$params['order_number']."%'";
        	}
        	if(!empty($params['team_product_number'])){
        		$data.=" and team_product.team_product_number like '%".$params['team_product_number']."%'";
        	}
        	if(!empty($params['cope_number'])){
        		$data.=" and cope.cope_number like '%".$params['cope_number']."%'";
        	}
        }else{
        	if(!empty($params['product_name'])){
        		$data.= " and cope.product_name = '".$params['product_name']."'";
        	}
        	if(!empty($params['order_number'])){
        		$data.=" and order_number ='".$params['order_number']."'";
        	}
            if(!empty($params['team_product_number'])){
        		$data.=" and team_product.team_product_number = '".$params['team_product_number']."'";
        	}
        	if(!empty($params['cope_number'])){
        		$data.=" and cope.cope_number = '".$params['cope_number']."'";
        	}
        }
        if(!empty($params['team_product_id'])){
        	$data.=" and cope.team_product_id ='".$params['team_product_id']."'";
        }
        if(!empty($params['source_type_id'])){
        	$data.=" and cope.source_type_id ='".$params['source_type_id']."'";
        }
        if(!empty($params['source_number'])){
        	$data.=" and cope.source_number ='".$params['source_number']."'";
        }
        if(!empty($params['public_number'])){
        	$data.=" and cope.public_number ='".$params['public_number']."'";
        }
        if(!empty($params['company_id'])){
        	$data.=" and cope.company_id =".$params['company_id'];
        }      
        if(is_numeric($params['receivable_object_type'])){
        	$data.=" and cope.receivable_object_type =".$params['receivable_object_type'];
        }
        if(is_numeric($params['receivable_object_id'])){
        	$data.=" and cope.receivable_object_id =".$params['receivable_object_id'];
        }
        if(is_numeric($params['status'])){
            $data.=" and cope.status =".$params['status'];
        }

		if(is_numeric($params['fee_type_type'])){
			$data.=" and cope.fee_type_type ='".$params['fee_type_type']."'";
		}
		if(is_numeric($params['cope_currency_id'])){
			$data.=" and cope.cope_currency_id =".$params['cope_currency_id'];
		}


	
        if($is_count==true){
              $result = $this->table("cope")->alias("cope")->
                join("team_product", "team_product.team_product_id = cope.team_product_id", 'left')->
                where($data)->count();
        }else {
            if ($is_page == true) {
                $result= $this->table("cope")->alias("cope")->
                join("team_product", "team_product.team_product_id = cope.team_product_id", 'left')->
                join("currency", "currency.currency_id = cope.cope_currency_id", 'left')->
                where($data)->limit($page, $page_size)->order('create_time desc')->
                field([
                    'cope.cope_id','cope.cope_number','cope.order_number',
                    'cope.source_number','cope.source_type_id','cope.team_product_number','cope.team_product_id',
                    'cope.product_name','cope.receivable_object_id',
                    'cope.receivable_object_type','cope.cope_currency_id','cope.cope_money',
                    'cope.invoice_number','cope.invoice_time','cope.price','cope.unit','cope.remark',
                    'cope.public_number','cope.company_id','cope.travel_agency_reimbursement_number',
                    'cope.fee_type_type','cope.fee_type_code','cope.is_auto',
                		'team_product.team_product_number',
                		'currency.unit as currency_unit','currency.symbol as currency_symbol',
                    "(select  currency_name from currency where  currency.currency_id = cope.cope_currency_id) as currency_name",
                    "(select if(sum(receivable_money)>0,sum(receivable_money),0) from  cope_info  where cope_info.cope_number = cope.cope_number and cope_info.status = 1) as true_receipt",//应付总金额
                    "(select company_name  from company where company.company_id = cope.company_id)"=>'company_name',
                    "(select nickname  from user where user.user_id = cope.create_user_id)"=>'create_user_name',
                    "(select nickname  from user where user.user_id = cope.update_user_id)"=>'update_user_name',
                    'cope.create_time','cope.update_time',"cope.status",
                	"(select sum(finance_approve.money) from finance_approve where finance_approve.finance_number = cope.cope_number and finance_approve.status = 1)"=>'finance_approve_money',
                		
                	"(select company_order.company_order_status from company_order where company_order.company_order_number = cope.order_number) as company_order_status"
                		
                		
                ])->select();
            } else {
                $result = $this->table("cope")->alias("cope")->
                join("team_product", "team_product.team_product_id = cope.team_product_id", 'left')->
                join("currency", "currency.currency_id = cope.cope_currency_id", 'left')->
                where($data)->order('create_time desc')->
                field([
                    'cope.cope_id', 'cope.cope_number', 'cope.order_number',
                    'cope.source_number', 'cope.source_type_id', 'cope.team_product_number','cope.team_product_id',
                    'cope.product_name', 'cope.receivable_object_id',
                    'cope.receivable_object_type', 'cope.cope_currency_id', 'cope.cope_money',
                    'cope.invoice_number', 'cope.invoice_time', 'cope.price', 'cope.unit', 'cope.remark',
                    'cope.public_number', 'cope.company_id', 'cope.travel_agency_reimbursement_number',
                    'cope.fee_type_type', 'cope.fee_type_code', 'cope.is_auto',
                		'team_product.team_product_number',
                		'currency.unit as currency_unit','currency.symbol as currency_symbol',
                    "(select  currency_name from currency where  currency.currency_id = cope.cope_currency_id) as currency_name",
                    "(select if(sum(receivable_money)>0,sum(receivable_money),0) from  cope_info  where cope_info.cope_number = cope.cope_number and cope_info.status = 1) as true_receipt",//应付总金额
                    "(select company_name  from company where company.company_id = cope.company_id)" => 'company_name',
                    "(select nickname  from user where user.user_id = cope.create_user_id)"          => 'create_user_name',
                    "(select nickname  from user where user.user_id = cope.update_user_id)"          => 'update_user_name',
                    'cope.create_time', 'cope.update_time', "cope.status",
                		"(select sum(finance_approve.money) from finance_approve where finance_approve.finance_number = cope.cope_number and finance_approve.status = 1)"=>'finance_approve_money',
                		"(select company_order.company_order_status from company_order where company_order.company_order_number = cope.order_number) as company_order_status"
                		
                		
                ])->select();
            }
        }
        return $result;

    }
    /**
     * 修改应付根据public_number
     */
    public function updateCopeByPublicNumber($params){
    
    	$t = time();
    	if(!empty($params['cope_currency_id'])){
    		$data['cope_currency_id'] = $params['cope_currency_id'];
    	
    	}
    	if(!empty($params['receivable_object_id'])){
    		$data['receivable_object_id'] = $params['receivable_object_id'];
    	
    	}
    	if(!empty($params['invoice_number'])){
    		$data['invoice_number'] = $params['invoice_number'];
    	
    	}
    	if(!empty($params['invoice_time'])){
    		$data['invoice_time'] = $params['invoice_time'];
    	
    	}
    	if(!empty($params['cope_money'])){
    		$data['cope_money'] = $params['cope_money'];
    	
    	}
    	if(!empty($params['fee_type_code'])){
    		$data['fee_type_code'] = $params['fee_type_code'];
    	
    	}
    	if(!empty($params['fee_type_type'])){
    		$data['fee_type_type'] = $params['fee_type_type'];
    	
    	}
    	if(!empty($params['product_name'])){
    		$data['product_name'] = $params['product_name'];
    	
    	}
    	if(!empty($params['cope_money'])){
    		$data['cope_money'] = $params['cope_money'];
    	
    	}
    	if(!empty($params['remark'])){
    		$data['remark'] = $params['remark'];
    	
    	}
    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    	
    	}
		
		
    	$data['update_user_id'] = $params['now_user_id'];
    	$data['update_time'] = $t;

    	$data_where['public_number'] = $params['public_number'];
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
    /**
     * 修改应付 根据cope_number
     */
    public function updateCopeByCopeNumber($params){
		$receivable = new Receivable();
        $t = time();
        if(!empty($params['cope_currency_id'])){
            $data['cope_currency_id'] = $params['cope_currency_id'];

        }
        if(!empty($params['receivable_object_id'])){
            $data['receivable_object_id'] = $params['receivable_object_id'];

        }
        if(!empty($params['currency_id'])){
        	$data['cope_currency_id'] = $params['currency_id'];
        
        }
        if(!empty($params['invoice_number'])){
            $data['invoice_number'] = $params['invoice_number'];

        }
        if(!empty($params['invoice_time'])){
            $data['invoice_time'] = $params['invoice_time'];

        }
        if(!empty($params['cope_money'])){
            $data['cope_money'] = $params['cope_money'];

        }
        if(is_numeric($params['unit'])){
        	$data['unit'] = $params['unit'];
        
        }
        if(is_numeric($params['price'])){
        	$data['price'] = $params['price'];
        
        }
        if(!empty($params['fee_type_code'])){
        	$data['fee_type_code'] = $params['fee_type_code'];
        
        }
        if(!empty($params['fee_type_type'])){
        	$data['fee_type_type'] = $params['fee_type_type'];
        
        }
        if(!empty($params['product_name'])){
            $data['product_name'] = $params['product_name'];

        }
        if(!empty($params['cope_money'])){
            $data['cope_money'] = $params['cope_money'];

        }
        if(!empty($params['remark'])){
            $data['remark'] = $params['remark'];

        }
        if(is_numeric($params['status'])){
            $data['status'] = $params['status'];

        }
        $data['update_user_id'] = $params['now_user_id'];
        $data['update_time'] = $t;
    
        $data_where['cope_number'] = $params['cope_number'];
      
        $this->startTrans();
        try{
            $this->where($data_where)->update($data);
           	$cope_result = $this->where($data_where)->select();
            //应付的金额修改了，应收也会更改
            $public_params = [
            	'public_number'=>$cope_result[0]['public_number'],
            	'receivable_money'=>$params['cope_money'],
            	'now_user_id'=>$params['now_user_id'],
            	'status'=>1,
            	'company_order_customer_id'=>$params['company_order_customer_id'],
            	'unit'=>$params['unit'],
            	'price'=>$params['price'],
            ];
          
            $receivable->updateReceivableByPublicNumber($public_params);
            
            
            
            
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
	//把应付状态变更为0
	public function updateStatusByData($params){


// 		if($params['product_type']>=2){
// 			$data['source_type_id'] = $params['product_type'];
// 			$where['product_type'] = 2;
// 		}else{
// 			$where['product_type'] = $params['product_type'];
// 		}
		if(!empty($params['order_number'])){
			$where['order_number'] = $params['order_number'];
		
		}
		if(!empty($params['receivable_object_type'])){
			$where['receivable_object_type'] = $params['receivable_object_type'];
		
		}
		if(!empty($params['team_product_number'])){
			$where['team_product_number'] = $params['team_product_number'];
		
		}
		if(!empty($params['team_product_id'])){
			$where['team_product_id'] = $params['team_product_id'];
		
		}		
		if(!empty($params['fee_type_type'])){
			$where['fee_type_type'] = $params['fee_type_type'];
		
		}
		if(!empty($params['fee_type_code'])){
			$where['fee_type_code'] = $params['fee_type_code'];
		
		}
		if(!empty($params['is_auto'])){
			$where['is_auto'] = $params['is_auto'];
		
		}
		if(!empty($params['travel_agency_reimbursement_number'])){
			$where['travel_agency_reimbursement_number'] = $params['travel_agency_reimbursement_number'];
		
		}
	
		$data['status'] = 0;
		
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

	public function getCopeAndProductDiy($where)
    {
         return $this->table("company_order_product_diy")->alias("d")->join('company_order c','d.company_order_number = c.company_order_number')->where($where)->select();

    }

    public function getCopeAndProductSource($where)
    {
        return $this->table("company_order_product_source")->alias("s")->join('company_order c','s.company_order_number = c.company_order_number')->where($where)->select();
    }

    public function getCopeAndProductTeam($where)
    {
        return $this->table("company_order_product_team")->alias("t")->join('company_order c','t.company_order_number = c.company_order_number')->where($where)->select();
    }
    
    /**
     * 获取应付的剩余金额
     */
    public function getCopeBalance($params){
    	//error_log(print_r("SELECT cope_money-(select sum(receivable_money) from cope_info  as ci where ci.cope_number=cope_number and ci.status=1 ) as balance FROM `cope` where status = 1 and team_product_id=".$params['team_product_id'],1));
    	$result = Db::query("SELECT cope_money-(select if(sum(receivable_money)>0,sum(receivable_money),0) from cope_info  as ci where ci.cope_number=cope_number and ci.status=1 ) as balance FROM `cope` where status = 1 and team_product_id=".$params['team_product_id']);
    
    	return $result;
    }
}