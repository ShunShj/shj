<?php
namespace app\index\model\approve;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
use app\index\model\branchcompany\CompanyOrderCustomer;
class CompanyFinanceCustomer extends Model
{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'company_finance_customer';
    private $_languageList;

    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }
	
    /**
     * 添加顾客
     */
    public function addCompanyFinanceCustomer($params){
    	$company_order_customer_id = $params['company_order_customer_id'];
    										
    	
    
    	$t = time();
    	$user_id = $params['now_user_id'];
    	$company_finance_approve_id = $params['company_finance_approve_id'];

    	
    	//转换成数组
    	$company_order_customer_id = explode(',', $company_order_customer_id);
    	
    	$sql="insert into company_finance_customer (company_finance_approve_id,company_order_customer_id,customer_id,create_time,update_time,create_user_id,update_user_id,status) values";
   		$company_order_customer = new CompanyOrderCustomer();
   		$count=0;
   		if($params['company_order_customer_id']!=''){
   			
   			for($i=0;$i<count($company_order_customer_id);$i++){
   				$params_customer['company_order_customer_id'] = $company_order_customer_id[$i];
   				
   				//通过companhy_order_customer_id查询数据
   				$r = $company_order_customer->getCompanyOrderCustomer($params_customer);
   			
   				$customer_id = $r[0]['customer_id'];
   				$coc_id = $r[0]['company_order_customer_id'];
   				$sql.="('$company_finance_approve_id',$coc_id,$customer_id,$t,$t,$user_id,$user_id,1),";
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
     * 获取往来账应收顾客
     * 王
     */
    public function getCompanyFinanceCustomer($params)
    {
        $data = "1=1 ";

        if(!empty($params['company_finance_approve_id'])){
            $data.= " and company_finance_customer.company_finance_approve_id = ".$params['company_finance_approve_id'];
        }


        $result = $this->table("company_finance_customer")->alias('company_finance_customer')->
        join("customer","customer.customer_id = company_finance_customer.customer_id",'left')->
        where($data)->
        field(['company_finance_customer.company_finance_customer_id','company_finance_customer.customer_id',
        		'company_finance_customer.company_order_customer_id',
        		"if(company_finance_customer.customer_id =0,'占位',concat(customer.customer_first_name,customer.customer_last_name)) as customer_name",

            		"(select nickname  from user where user.user_id = company_finance_customer.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = company_finance_customer.update_user_id)"=>'update_user_name',
                    'company_finance_customer.create_time',"company_finance_customer.status",])->select();

        return $result;


    }
    


}