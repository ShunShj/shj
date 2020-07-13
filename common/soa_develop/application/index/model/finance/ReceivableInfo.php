<?php
namespace app\index\model\finance;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;

class ReceivableInfo extends Model
{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'receivable_info';
    private $_languageList;

    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }
    /**
     * 添加批量应收
     * 
     */
    public function addReceivableInfo($params){

        $t = time();
		
		
		if(!empty( $params['receivable_voucher'])){
			$data['receivable_voucher'] = $params['receivable_voucher'];
		}
		if(!empty( $params['voucher_time'])){
			$data['voucher_time'] = $params['voucher_time'];
		}
		$data['receivable_number'] = $params['receivable_number'];
		$data['payment_money'] = $params['payment_money'];
		
		$data['payment_currency_id'] = $params['payment_currency_id'];
		$data['payment_type'] = $params['payment_type'];
		$data['payment_number'] = $params['payment_number'];
		$data['receivable_info_type'] = $params['receivable_info_type'];
		
		if(!empty($params['payment_stage'])){
			$data['payment_stage'] = $params['payment_stage'];
		}
		if(!empty($params['create_user_id'])){
			$data['create_user_id'] = $params['create_user_id'];
		}
		if(!empty($params['update_user_id'])){
			$data['update_user_id'] = $params['update_user_id'];
		}
		if(!empty($params['create_time'])){
			$data['create_time'] = $params['create_time'];
		}
		if(!empty($params['update_time'])){
			$data['update_time'] = $params['update_time'];
		}		
		if(!empty($params['payment_time'])){
			$data['payment_time'] = $params['payment_time'];
		}
		if(!empty($params['supplier_name'])){
			$data['supplier_name'] = $params['supplier_name'];
		}
		if(!empty($params['sn_number'])){
			$data['sn_number'] = $params['sn_number'];
		}
		if(!empty($params['invoice_number'])){
			$data['invoice_number'] = $params['invoice_number'];
		}
		if(!empty($params['status'])){
			$data['status'] = $params['status'];
		}		
		if(!empty($params['account_number'])){
			$data['account_number'] = $params['account_number'];
		}	
		if(!empty($params['attachment'])){
			$data['attachment'] = $params['attachment'];
		}
		if(!empty($params['remark'])){
			$data['remark'] = $params['remark'];
		}
		if(is_numeric($params['receipts_pay_id'])){
			$data['receipts_pay_id'] = $params['receipts_pay_id'];
		}
		if(is_numeric($params['finance_approve_id'])){
			$data['finance_approve_id'] = $params['finance_approve_id'];
		}
		if(is_numeric($params['base_money'])){
			$data['base_money'] = $params['base_money'];
		}
        $this->startTrans();
        try{
			$result = $this->insert($data);
        	//$sql_values = trim($sql_values,',');
  		
        	
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
     * 添加应收-销售收款
     * 胡
     */
    public function addReceivableInfoSale($params){
    
    	$t = time();
		$user_id = $params['now_user_id'];

        $params['sn_number'] && $data['sn_number'] = $params['sn_number'];    //sn_number
    	
		$data['receivable_info_type'] = 1;
    	$data['payment_stage'] = $params['payment_stage'];
    	$data['payment_currency_id'] = $params['payment_currency_id'];
    	$data['receivable_number'] = $params['receivable_number'];
    	$data['payment_money'] = $params['payment_money'];
    	if(!empty($params['payment_time'])){
    		$data['payment_time'] = $params['payment_time'];
    	}
    	
    	if(!empty($params['payment_number'])){
    		$data['payment_number'] = $params['payment_number'];
    	}else{
    		$data['payment_number'] = Help::getNumber(201);
    	}
    	
    	$data['receivable_info_type'] =2;
    	$data['payment_type'] =$params['payment_type'];
    	$data['receivable_voucher'] = $params['receivable_voucher'];
    	$data['voucher_time'] = $params['voucher_time'];
   		$data['create_time'] = $t;
   		$data['update_time'] = $t;
   		$data['create_user_id'] = $user_id;
   		$data['update_user_id'] = $user_id;
   		$data['approval_status']=1;
   		
   		$r = $this->getReceivableInfo([]);
   		$data['invoice_number']= help::getNumber(202,1).str_pad(count($r)+1,5,"0",STR_PAD_LEFT)."-0A";
   		if(!empty($params['remark'])){
   			$data['remark'] = $params['remark'];
   		}
   		$data['status'] = 1;
    	$this->startTrans();
    	try{
			$this->insert($data);
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
     * 获取应收详情
     * 王
     */
    public function getReceivableInfo($params, $is_count=false)
    {
        $data = "1=1 and receivable_info.status = 1";

        if(isset($params['receivable_number'])){
            $data.= " and receivable_info.receivable_number = '".$params['receivable_number']."'";
        }
        if(isset($params['receivable_info_id'])){
        	$data.= " and receivable_info.receivable_info_id = '".$params['receivable_info_id']."'";
        }
        if(is_numeric($params['status'])){
            $data.= " and receivable_info.status = ".$params['status'];
        }
        if(is_numeric($params['receivable_info_type'])){
        	$data.= " and receivable_info.receivable_info_type = ".$params['receivable_info_type'];
        }

        if(!empty($params['company_order_number'])){
        	$data.= " and receivable.order_number = '".$params['company_order_number']."'";
        }
        if (is_numeric($params['company_id']))
        {
            $data.= " and receivable.company_id = '".$params['company_id']."'";
        }


        if($is_count==true){
            $result = $this->table("receivable_info")->alias("receivable_info")->
                join("receivable receivable","receivable.receivable_number = receivable_info.receivable_number",'right')
                ->where($data)->count();
        }else {
            if (isset($params['page'])) {
                $page = ($params['page']-1)*$params['limit'];

                $result = $this->table("receivable_info")->alias('receivable_info')->
                join("receivable receivable","receivable.receivable_number = receivable_info.receivable_number",'right')->
               
                where($data)->limit($page, $params['limit'])->
                field(['receivable_info.receivable_info_id','receivable_info.receivable_number','receivable_info.payment_currency_id',
                    'receivable_info.receivable_voucher','receivable_info.voucher_time',
                    'receivable_info.payment_type',  'receivable_info.payment_money',  'receivable_info.payment_number',
                	'receivable_info.invoice_number','receivable_info.attachment',
                    'receivable_info.payment_stage','receivable_info.receivable_info_type','receivable_info.payment_time',
                    'receivable.create_user_id as receivable_create_user_id', 'receivable_info.account_number', 'receivable_info.exg_rate_gain', 'receivable_info.is_locked', 'receivable_info.pts', 'receivable_info.sn_number', 'receivable_info.supplier_name',
                    'receivable_info.remark', 'receivable_info.supplier_name', 'receivable_info.sn_number', 'receivable_info.pts',
                    "(select nickname  from user where user.user_id =receivable_create_user_id)"=>'receivable_user_name',
                    "(select  currency_name from currency where  currency.currency_id = receivable_info.payment_currency_id) as payment_currency_name",
                	"(select  unit from currency where  currency.currency_id = receivable_info.payment_currency_id) as unit",
                		
                	"(select nickname  from user where user.user_id = receivable_info.create_user_id)"=>'create_user_name',
                    "(select nickname  from user where user.user_id = receivable_info.update_user_id)"=>'update_user_name',
                    'receivable_info.create_user_id','receivable_info.create_time','receivable_info.update_user_id',
                    'receivable_info.update_time','receivable_info.status', 'receivable.fee_type_code', 'receivable.product_name'])->select();

            }else{

                $result = $this->table("receivable_info")->alias('receivable_info')->
                join("receivable","receivable.receivable_number = receivable_info.receivable_number",'right')->
                where($data)->
                field(['receivable_info.receivable_info_id','receivable_info.receivable_number','receivable_info.payment_currency_id',
                    'receivable_info.receivable_voucher','receivable_info.voucher_time',
                    'receivable_info.payment_type',  'receivable_info.payment_money',  'receivable_info.payment_number',
                	'receivable_info.invoice_number','receivable_info.attachment',
                	'receivable_info.payment_stage','receivable_info.receivable_info_type','receivable_info.payment_time',
                    'receivable.create_user_id as receivable_create_user_id', 'receivable_info.account_number', 'receivable_info.exg_rate_gain', 'receivable_info.is_locked', 'receivable_info.pts', 'receivable_info.sn_number', 'receivable_info.supplier_name',
                    'receivable_info.remark','receivable_info.supplier_name', 'receivable_info.sn_number', 'receivable_info.pts',
                    "(select nickname  from user where user.user_id =receivable_create_user_id)"=>'receivable_user_name',
                    "(select  currency_name from currency where  currency.currency_id = receivable_info.payment_currency_id) as payment_currency_name",
                	"(select  unit from currency where  currency.currency_id = receivable_info.payment_currency_id) as unit",
                	"(select nickname  from user where user.user_id = receivable_info.create_user_id)"=>'create_user_name',
                    "(select nickname  from user where user.user_id = receivable_info.update_user_id)"=>'update_user_name',
                    'receivable_info.create_user_id','receivable_info.create_time','receivable_info.update_user_id',
                    'receivable_info.update_time','receivable_info.status', 'receivable.fee_type_code', 'receivable.product_name'])->select();
            }
        }
	
        return $result;


    }
    

	//修改应收详情根据应收详情ID
	public function updateReceivableInfoByReceivableInfoId($params){

		$t = time();
		if(!empty($params['payment_stage'])){
			$data['payment_stage'] = $params['payment_stage'];
		
		}
		if(!empty($params['payment_currency_id'])){
			$data['payment_currency_id'] = $params['payment_currency_id'];
		
		}
		if(!empty($params['payment_money'])){
			$data['payment_money'] = $params['payment_money'];
		
		}
        if(!empty($params['payment_time'])){
            $data['payment_time'] = $params['payment_time'];

        }
		if(!empty($params['payment_type'])){
			$data['payment_type'] = $params['payment_type'];
		
		}
		if(!empty($params['payment_time'])){
			$data['payment_time'] = $params['payment_time'];
		
		}
		if(is_numeric($params['status'])){
			$data['status'] = $params['status'];
		
		}


        if(is_numeric($params['pts'])){
            $data['pts'] = $params['pts'];

        }
        if(is_numeric($params['is_locked'])){
            $data['is_locked'] = $params['is_locked'];

        }


		$data['update_user_id'] = $params['now_user_id'];
		$data['update_time'] = $t;
		
		$data_where['receivable_info_id'] = $params['receivable_info_id'];
		$this->startTrans();
		try{
			 
			 
			$this->table('receivable_info')->where($data_where)->update($data);
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
	

	//修改应收详情根据应收详情ID
	public function updateReceivableInfoByPaymentNumber($params){
		$t = time();

		if(is_numeric($params['status'])){
			$data['status'] = $params['status'];
	
		}
		$data['update_user_id'] = $params['now_user_id'];
		$data['update_time'] = $t;
	
		$data_where['payment_number'] = $params['payment_number'];
		$this->startTrans();
		try{
	
	
			$this->table('receivable_info')->where($data_where)->update($data);
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
	//获取已收金额
	public function getReceivableInfoMoney($params){
		$data=" and 1=1 ";
		if(!empty($params['company_id'])){
			$data.=" and company_id = ".$params['company_id'];
		}
		$sql="select  if(sum(payment_money)>0,sum(payment_money),0)  as true_receivable from receivable_info where receivable_info.receivable_number in (select receivable_number from receivable where receivable.receivable_number = receivable_info.receivable_number and status = 1  $data) and status = 1";
		
		$result = $this->query($sql);
		
		return $result;
	}


    /**
     * 添加or更新一条数据
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/4/25
     * Time: 17:39
     */
    public function addOrUpdateOne($data, $where = [])
    {
    	$r = $this->getReceivableInfo([]);
    
    	$data['invoice_number']= help::getNumber(202,1).str_pad(count($r)+1,5,"0",STR_PAD_LEFT)."-0A";
        if (empty($where))
        {
            return $this->insert($data);
        }
        else
        {
            return $this->table('receivable_info')->where($where)->update($data);
        }
    }
    
    
    /**
     * 通过记账ID 获取 金额
     */
    public function getReceivableInfoByReceiptsPayId($params){
    	$data="  1=1 ";
    	if(!empty($params['receipts_pay_id'])){
    		$data.=" and receipts_pay_id = ".$params['receipts_pay_id'];
    	}
    	$sql="select  if(sum(base_money)>0,sum(base_money),0)  as true_receivable from receivable_info where  $data and status = 1";

    	$result = $this->query($sql);
    	
    	return $result;
    }

    
    
}