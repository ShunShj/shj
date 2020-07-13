<?php

namespace app\index\model\finance;
use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use think\config;
use think\Db;
use app\index\model\finance\Cope;
use app\index\model\finance\ReceivableCustomer;
class ReceiptsPay extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'receipts_pay';
    private $_languageList;
    private	$_cope;
    private $_public_service;
    public function initialize()
    {
    	
    	$this->_receivable_customer = new ReceivableCustomer();
        $this->_languageList = config('systom_setting')['language_list'];
        $this->_cope = new Cope();
        $this->_public_service = new PublicService();
        parent::initialize();

    }

    /**
     * 添加实收
     * 胡
     */
    public function addReceiptsPay($params){
	
	
        $t = time();
		$user_id = $params['now_user_id'];

		$data['type'] = $params['type'];
		
        //订单
        if(!empty($params['serial_number'])){ 
        	$data['serial_number'] = $params['serial_number'];
        }
        if(!empty($params['object_type'])){
        	$data['object_type'] = $params['object_type'];
        }
        if(!empty($params['object_id'])){
        	$data['object_id'] = $params['object_id'];
        }
        if(!empty($params['pay_type'])){
        	$data['pay_type'] = $params['pay_type'];
        }
        if(!empty($params['base_currency_id'])){
        	$data['base_currency_id'] = $params['base_currency_id'];
        }
        if(is_numeric($params['base_money'])){
        	$data['base_money'] = $params['base_money'];
        }
        if(!empty($params['proportion'])){
        	$data['proportion'] = $params['proportion'];
        }
        if(!empty($params['result_currency_id'])){
        	$data['result_currency_id'] = $params['result_currency_id'];
        }
        if(!empty($params['result_money'])){
        	$data['result_money'] = $params['result_money'];
        }
        
        if(!empty($params['payment_number'])){
        	$data['payment_number'] = $params['payment_number'];
        }      
        
        if(!empty($params['voucher_number'])){
        	$data['voucher_number'] = $params['voucher_number'];
        }

        if(!empty($params['voucher_time'])){
        	$data['voucher_time'] = $params['voucher_time'];
        }
        if(!empty($params['remark'])){
        	$data['remark'] = $params['remark'];
        }        
        
        if(!empty($params['attachment'])){
        	$data['attachment'] = $params['attachment'];
        }

        
        if(is_numeric($params['deposit_payment_time'])){
        	$data['deposit_payment_time'] = $params['deposit_payment_time'];
        }
        if(is_numeric($params['deposit_user_id'])){
        	$data['deposit_user_id'] = $params['deposit_user_id'];
        }

        $data['company_id'] = $params['user_company_id'];
        $data['create_user_id'] = $params['now_user_id'];
        $data['create_time'] = time();
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
     * 获取应收
     * 王
     */
    public function getReceiptsPay($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
		
    	$data = "1=1";
    	if($params['is_like'] == 1){

    	}else{

    	}
		
    	if(!empty($params['receipts_pay_id'])){
    		$data.= " and receipts_pay.receipts_pay_id =".$params['receipts_pay_id'];
    	}
    	if(!empty($params['base_currency_id'])){
    		$data.= " and receipts_pay.base_currency_id = '".$params['base_currency_id']."'";
    	}
    	if(is_numeric($params['object_type'])){
    		$data.= " and receipts_pay.object_type = '".$params['object_type']."'";
    	}
    	
    	if(is_numeric($params['type'])){
    		$data.= " and receipts_pay.type = '".$params['type']."'";
    	}


        if($is_count==true){
             $result = $this->table("receipts_pay")->alias("receipts_pay")->
    
                join("currency", "currency.currency_id = receipts_pay.base_currency_id", 'left')->

                where($data)->count();
              	
                
        }else {
            if ($is_page == true) {
             	$result = $this->table("receipts_pay")->alias("receipts_pay")->
             	join("user", "user.user_id= receipts_pay.create_user_id", 'left')->
                join("currency", "currency.currency_id = receipts_pay.base_currency_id", 'left')->
            	
                where($data)->limit($page, $page_size)->order('receipts_pay.create_time desc')->
                field(['receipts_pay.*','currency.unit as base_currency_unit','user.nickname as create_user_name',
                		"(select user.nickname from user where user.user_id = receipts_pay.deposit_user_id) as deposit_user_name"
                		
                		
                		
                ])->select();
            } else {
             	$result = $this->table("receipts_pay")->alias("receipts_pay")->
             	join("user", "user.user_id= receipts_pay.create_user_id", 'left')->
                join("currency", "currency.currency_id = receipts_pay.base_currency_id", 'left')->
                where($data)->order('receipts_pay.create_time desc')->
  				field(['receipts_pay.*','currency.unit as base_currency_unit','user.nickname as create_user_name',
  					"(select user.nickname from user where user.user_id = receipts_pay.deposit_user_id) as deposit_user_name"	
  						
  						
  						
                	])->select();
            }
           
        }
       
	
        return $result;

    }
    /**
     * 修改应收 根据receivable_number
     */
    public function updateReceivableByReceivableNumber($params){

        $t = time();
		$user_id = $params['now_user_id'];

        
        //订单
        if(!empty($params['order_number'])){ 
        	$data['order_number'] = $params['order_number'];
        }
        if(!empty($params['team_product_number'])){
        	$data['team_product_number'] = $params['team_product_number'];
        }
        if(!empty($params['product_name'])){
        	$data['product_name'] = $params['product_name'];
        }
        if(isset($params['source_type_id'])){
        	$data['source_type_id'] = $params['source_type_id'];
        }
        if(!empty($params['source_number'])){
        	$data['source_number'] = $params['source_number'];
        }
        if(isset($params['payment_object_type'])){
        	$data['payment_object_type'] = $params['payment_object_type'];
        }
        if(isset($params['payment_object_id'])){
        	$data['payment_object_id'] = $params['payment_object_id'];
        }

        if(!empty($params['receivable_currency_id'])){
        	$data['receivable_currency_id'] = $params['receivable_currency_id'];
        }
        if(!empty($params['price'])){
        	$data['price'] = $params['price'];
        }
        if(!empty($params['unit'])){
        	$data['unit'] = $params['unit'];
        }
        if(!empty($params['remark'])){
        	$data['remark'] = $params['remark'];
        }    
        if(!empty($params['fee_type_code'])){
        	$data['fee_type_code'] = $params['fee_type_code'];
        }
     
        if(!empty($params['fee_type_type'])){
        	$data['fee_type_type'] = $params['fee_type_type'];
        }      
        
       
        $data['receivable_money'] = $params['receivable_money'];
     
        
  
        $data['update_time'] = $t;

        $data['update_user_id'] = $user_id;
		$data['status'] = 1;
	
        $data_where['receivable_number'] = $params['receivable_number'];
        
        
        $this->startTrans();
        try{
        	
        	
            $this->where($data_where)->update($data);
			//通过应收去查询PUBLIC
			
			$receivable_result = $this->getReceivable($data_where);
			
			$receivable_public_number = $receivable_result[0]['public_number'];
			//通过public去修改应付的代码
			
			if(!empty($params['order_number'])){
				$data_cope['order_number'] = $params['order_number'];
			}
			if(!empty($params['team_product_number'])){
				$data_cope['team_product_number'] = $params['team_product_number'];
			}
			if(!empty($params['product_name'])){
				$data_cope['product_name'] = $params['product_name'];
			}

			if(!empty($params['source_type_id'])){
				$data_cope['source_type_id'] = $params['source_type_id'];
			}
			if($params['fee_type_type']==201){
				$data_cope['fee_type_type']=104;
			}
			if($params['fee_type_code']==34){
				$data_cope['fee_type_code']=70;
			}else if($params['fee_type_code']==35){
				$data_cope['fee_type_code']=71;
			}else if($params['fee_type_code']==36){
				$data_cope['fee_type_code']=72;
			}else if($params['fee_type_code']==37){
				$data_cope['fee_type_code']=73;
			}else if($params['fee_type_code']==38){
				$data_cope['fee_type_code']=74;
			}else if($params['fee_type_code']==39){
				$data_cope['fee_type_code']=75;
			}else if($params['fee_type_code']==40){
				$data_cope['fee_type_code']=76;
			}else if($params['fee_type_code']==41){
				$data_cope['fee_type_code']=77;
			}else if($params['fee_type_code']==42){
				$data_cope['fee_type_code']=78;
			}else if($params['fee_type_code']==43){
				$data_cope['fee_type_code']=79;
			}else if($params['fee_type_code']==44){
				$data_cope['fee_type_code']=80;
			}else if($params['fee_type_code']==45){
				$data_cope['fee_type_code']=81;
			}
			 
			 
			$data_cope['receivable_object_id'] = $params['company_id'];

			
			$data_cope['cope_money'] = $params['receivable_money'];
			
			
			$data_cope['cope_currency_id'] = $params['receivable_currency_id'];
			 
			
		
			 
	
			$data_cope['company_id'] = $params['payment_object_id'];

			
			$data_cope['now_user_id'] = $user_id;
			$data_cope['status'] = 1;
			$data_cope['public_number'] = $receivable_public_number;
			$this->_cope->updateCopeByPublicNumber($data_cope);
			
			$receivable_customer_params = [
					'company_order_customer_id'=>$params['company_order_customer_id'],
					'receivable_number'=> $params['receivable_number'],
					'now_user_id'=>$user_id
			];
			$this->_receivable_customer->addReceivableCustomer($receivable_customer_params);
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
     * 修改应收根据public_number
     */
    public function updateReceivableByPublicNumber($params){
    
    	$t = time();
    
    	if(!empty($params['receivable_money'])){
    		$data['receivable_money'] = $params['receivable_money'];
    
    	}
    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    	
    	}   
    	if(is_numeric($params['unit'])){
    		$data['unit'] = $params['unit'];
    		 
    	}
    	if(is_numeric($params['price'])){
    		$data['price'] = $params['price'];
    		 
    	}
    	$data['update_user_id'] = $params['now_user_id'];
    	$data['update_time'] = $t;
    
    	$data_where['public_number'] = $params['public_number'];
    	
   
    	$this->startTrans();
    	try{
    		$this->where($data_where)->update($data);
    		
    		
    		
    		$receivable_result = $this->getReceivable($data_where);
    		$receivable_customer_params = [
    			'company_order_customer_id'=>$params['company_order_customer_id'],
    			'receivable_number'=> $receivable_result[0]['receivable_number'],
    			'now_user_id'=> $params['now_user_id']
    		];
    		$this->_receivable_customer->addReceivableCustomer($receivable_customer_params);
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
     * 添加团队产品其他应付
     * 韩
     */
    public function addTeamProductOtherReceivable($params){
    	//首先根据团队产品账号查询出地接账号编号 否则就添加
    	$team_product_number = $params['team_product_number'];
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$company_id = $params['user_company_id'];
    
    	$team_product_other_info = $params['team_product_other_info'];
    	$receivable_customer = new  ReceivableCustomer();
    	$cope = new  Cope();
    	//开始应付 应收//先把团队其他变成0
    	$cope_data['status'] = 0;
    	$this->table('cope')->where("resource_type = 4 and team_product_number = '".$team_product_number."'")->update($cope_data);
    	$this->table('receivable')->where("resource_type = 4 and team_product_number = '".$team_product_number."'")->update($cope_data);
    	    	 
    	$this->startTrans();
    	try{
    		for($i=0;$i<count($team_product_other_info);$i++){
    			$receivable_number = $team_product_other_info[$i]['receivable_number'];
    			
    			$order_number = $team_product_other_info[$i]['order_number'];
    			$payment_company_id = $team_product_other_info[$i]['payment_company_id'];
    			$product_type = $team_product_other_info[$i]['product_type'];
    			
    			if(isset($team_product_other_info[$i]['product_source_type_id'])){
    				$source_type_id = $team_product_other_info[$i]['product_source_type_id'];
    			}else{
    				$source_type_id = 0;
    			}
    			
    			$product_name = $team_product_other_info[$i]['product_name'];
    			
    			$currency_id = $team_product_other_info[$i]['currency_id'];
    			
			
    			$receivable_money = $team_product_other_info[$i]['receivable_money'];
    			$company_order_customer_id = $team_product_other_info[$i]['company_order_customer_id'];
    			
    			if(!empty($receivable_number)){//如果有数据 那走修改
    				$data['order_number'] = $order_number;
    				$data['payment_company_id'] = $payment_company_id;
    				$data['product_source_type_id'] = $source_type_id;
    				$data['product_name'] = $product_name;
    				$data['currency_id'] = $currency_id;    			
    				$data['receivable_money'] = $receivable_money;
    				$data['update_time'] = $t;
    				$data['update_user_id'] = $user_id;
    				$data['status'] = 1;
    				$this->where("receivable_number = '".$receivable_number."'")->update($data);
    				//再把应付的价格修改
    				//先通过应收编号获取公共编号
    				$receivable_result = $this->getReceivable("receivable_number = '$receivable_number'");
    				$public_number = $receivable_result[0]['public_number'];
    				
    				$data_cope['status'] = 1;
    				$data_cope['public_number'] = $public_number;
    				$data_cope['cope_money'] = $receivable_money;
    				$data_cope['source_type_id'] = $source_type_id;
    				$cope->updateCopeByPublicNumber($data_cope);
    				
    			}else{//新增
    				$data['order_number'] = $order_number;
    				$data['payment_company_id'] = $payment_company_id;
    				$data['receivable_number'] = Help::getNumber(5);
    				$data['team_product_number']=$team_product_number;
    				$data['product_source_type_id'] = $source_type_id;
    				$data['product_name'] = $product_name;
    				$data['product_type'] = $product_type;
    				$data['currency_id'] = $currency_id;    			
    				$data['receivable_money'] = $receivable_money;
    				$data['resource_type'] = 4;
    				$data['update_time'] = $t;
    				$data['update_user_id'] = $user_id;
    				$data['create_user_id'] = $user_id;
    				$data['public_number'] = $data['receivable_number'];
    				$data['create_time'] = $t;
    				$data['status'] = 1;
    				$this->insert($data);
    				$receivable_number=$data['receivable_number'];
    				//添加应付
    				$data_cope['cope_number']=Help::getNumber(6);
    				$data_cope['product_type'] = $product_type;
    				$data_cope['product_name']=$product_name;
    				$data_cope['team_product_number']=$team_product_number;
    				$data_cope['receivable_company_id'] = $payment_company_id;
    				$data_cope['source_type_id'] = $source_type_id;
    				$data_cope['cope_money'] = $receivable_money;
    				$data_cope['cope_currency_id'] = $currency_id;
    				$data_cope['company_id'] = $company_id;
    				$data_cope['cope_money'] = $receivable_money;
    				$data_cope['resource_type'] = 4;
    				$data_cope['unit'] = 1;
    				$data_cope['price'] = 0;
    				$data_cope['update_time'] = $t;
    				$data_cope['update_user_id'] = $user_id;
    				$data_cope['create_user_id'] = $user_id;
    				$data_cope['public_number'] = $data['receivable_number'];
    				$data_cope['create_time'] = $t;
    				$data_cope['status'] = 1;
    				$this->table('cope')->insert($data_cope);
    			}
    			
    			//先把游客删除为0
    			$receivable_customer_params1['status']=0;
    			$this->table('receivable_customer')->where("receivable_number = '$receivable_number'")->update($receivable_customer_params1);
    			//开始添加游客信息
				$receivable_customer_params = [
					'receivable_number'=> $receivable_number,
					'company_order_customer_id'=>$company_order_customer_id,
					'user_id'=>$user_id	
				];
			
				$receivable_customer->addReceivableCustomer($receivable_customer_params);
    			
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
    
    //把应收状态变更为0
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
    	if(!empty($params['team_product_id'])){
    		$where['team_product_id'] = $params['team_product_id'];
    	
    	}
    	if(!empty($params['order_number'])){
    		$where['order_number'] = $params['order_number'];
    	
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
    
    /**
     * 获取营业额
     */
    public function getReceivableSum($params){
    	$data='status =1 ';
		if(!empty($params['create_time_day'])){
			$data.=" and from_unixtime(create_time,'%Y%m%d') = '".$params['create_time_day']."'";
		}
		if(!empty($params['create_time_month'])){
			$data.=" and from_unixtime(create_time,'%Y%m') = '".$params['create_time_month']."'";
		}
		if(!empty($params['company_id'])){
			$data.=" and company_id = ".$params['company_id'];
		}
	
    	return $this->where($data)->field(['if(sum(receivable_money)>0,sum(receivable_money),0) as receivable_money'])->select();
    	
    }
    //获取未收账款排名
    public function getMissPaymentCompany($params){
    	$data = 'and 1=1';
    	if(!empty($params['company_id'])){
    		$data.=" and company_id= ".$params['company_id'];
    	}
    	
   
    	$sql="SELECT receivable_number,payment_object_id,receivable_money,
    		(select company_name from company where company_id = payment_object_id) as company_name,	
			(select if(sum(payment_money)>0,sum(payment_money),0) from receivable_info where receivable_info.receivable_number=receivable.receivable_number and status=1) as true_money
			
			FROM `receivable` where status =1 and payment_object_type=1 $data";
    	
    
    	$result = $this->query($sql);
    	
    	return $result;
    }


    /**
     * Created by PhpStorm.
     * User: yyy
     * Date: 2019/4/22
     * Time: 16:35
     * @param $receivable_number string receivable编号
     * @return array|mixed
     */
    public function getReceivableByReceivableNumber($receivable_number)
    {
        return $this->table("receivable")->alias("receivable")->where(['receivable_number' => $receivable_number])->find();
    }
    
    /**
     * 获取应收的剩余金额
     */
    public function getReceivableBalance($params){
    	//error_log(print_r("SELECT cope_money-(select sum(receivable_money) from cope_info  as ci where ci.cope_number=cope_number and ci.status=1 ) as balance FROM `cope` where status = 1 and team_product_id=".$params['team_product_id'],1));
    	$result = Db::query("SELECT receivable.receivable_money-(select if(sum(payment_money)>0,sum(payment_money),0) from receivable_info  as ri where ri.receivable_number=receivable.receivable_number and ri.status=1 ) as balance FROM `receivable` where status = 1 and team_product_id=".$params['team_product_id']);
    
    	return $result;
    }
    
}