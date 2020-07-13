<?php
namespace app\index\model\finance;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
use app\index\model\branchcompany\CompanyOrderCustomer;
class ReceivableCustomer extends Model
{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'receivable_customer';
    private $_languageList;

    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }
	
    /**
     * 添加顾客
     */
    public function addReceivableCustomer($params){
    	$company_order_customer_id = $params['company_order_customer_id'];
    	
    	
    	$t = time();
    	$user_id = $params['now_user_id'];
    	$receivable_number = $params['receivable_number'];
    	//先把应收状态的游客变为0
    	$update_status_params = [
    		'receivable_number'=>$receivable_number,
    		'now_user_id'=>$user_id	
    	];
    	
    	$this->updateStatus($update_status_params);
    	
    	//转换成数组
    	$company_order_customer_id = explode(',', $company_order_customer_id);
    	
    	$sql="insert into receivable_customer (receivable_number,company_order_customer_id,customer_id,create_time,update_time,create_user_id,update_user_id,status) values";
   		$company_order_customer = new CompanyOrderCustomer();
   		$count=0;
   		if($params['company_order_customer_id']!=''){
   			for($i=0;$i<count($company_order_customer_id);$i++){
   				$params_customer['company_order_customer_id'] = $company_order_customer_id[$i];
   				
   				//通过companhy_order_customer_id查询数据
   				$r = $company_order_customer->getCompanyOrderCustomer($params_customer);
   			
   				$customer_id = $r[0]['customer_id'];
   				$coc_id = $r[0]['company_order_customer_id'];
   				$sql.="('$receivable_number',$coc_id,$customer_id,$t,$t,$user_id,$user_id,1),";
   				$count++;
   			}
   			  			
   		}
		
		$sql = trim($sql,',');
		
        $this->startTrans();
        try{
          
         	if($count>0){
         		$this->execute($sql);
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
     * 获取应收顾客
     * 王
     */
    public function getReceivableCustomer($params)
    {
        $data = "1=1 ";

        if(isset($params['receivable_number'])){
            $data.= " and receivable_customer.receivable_number = '".$params['receivable_number']."'";
        }
		if(is_numeric($params['status'])){
			$data.= " and receivable_customer.status = ".$params['status'];
		}

        $result = $this->table("receivable_customer")->alias('receivable_customer')->
        join("customer","customer.customer_id = receivable_customer.customer_id",'left')->
        where($data)->
        field(['receivable_customer.receivable_customer_id','receivable_customer.customer_id',
        		'receivable_customer.company_order_customer_id',
        		"if(receivable_customer.customer_id =0,'占位',concat(customer.customer_first_name,customer.customer_last_name)) as customer_name",

            		"(select nickname  from user where user.user_id = receivable_customer.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = receivable_customer.update_user_id)"=>'update_user_name',
                    'receivable_customer.create_time',"receivable_customer.status",])->select();

        return $result;


    }
    
	/**
	 * 修改应收游客状态
	 */
    public function updateStatus($params){
    	
    	$where['receivable_number'] = $params['receivable_number'];
    	
    	$data['status'] = 0;
    	$data['update_time'] = time();
    	$data['update_user_id'] = $params['now_user_id'];
    	
    	return $this->where($where)->update($data);
    }
	/**
	 * 修改订单的游客
	 */
	public function updateReceivableCustomer($params){
		if($params['company_order_customer_id']==''){
			$customer_count=0;
		}else{
			$customer_count=1;
			$t = time();
			$user_id = $params['now_user_id'];
			$receivable_number = $params['receivable_number'];
			$customer_arr = $params['customer_arr'];
			$sql="insert into receivable_customer (receivable_number,company_order_customer_id,customer_id,create_time,update_time,create_user_id,update_user_id,status) values";
			if(!empty($params['key'])){
					
				$company_order_customer_id = $params['company_order_customer_id'];
				$company_order_customer_id_arr = explode(',',$company_order_customer_id);
					
				for($i=0;$i<count($company_order_customer_id_arr);$i++){
					//通过company_order_customer_id 查询
					$company_order_customer = new CompanyOrderCustomer();
					$coc_id = $company_order_customer_id_arr[$i];
					$result_data = $company_order_customer->getCompanyOrderCustomer(['company_order_customer_id'=>$coc_id]);
					$customer_id = $result_data[0]['customer_id'];
					$coc_id = $company_order_customer_id_arr[$i];
					$sql.="('$receivable_number',$coc_id,$customer_id,$t,$t,$user_id,$user_id,1),";
						
				}
			
			}else{
					
				for($i=0;$i<count($customer_arr);$i++){
						
					$customer_id = $customer_arr[$i]['customer_id'];
					$coc_id = $customer_arr[$i]['company_order_customer_id'];
					$sql.="('$receivable_number',$coc_id,$customer_id,$t,$t,$user_id,$user_id,1),";
						
				}
			}
			
			
			$sql = trim($sql,',');
		}
		

	
		$this->startTrans();
		try{
			$update_data['status'] = 0;
			$this->where("receivable_number = '".$receivable_number."'")->update($update_data);
			if($customer_count==1){
				$this->execute($sql);
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

}