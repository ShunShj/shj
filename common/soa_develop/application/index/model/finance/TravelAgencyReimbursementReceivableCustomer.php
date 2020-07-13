<?php
namespace app\index\model\finance;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
use app\index\model\branchcompany\CompanyOrderCustomer;
class TravelAgencyReimbursementReceivableCustomer extends Model
{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'travel_agency_reimbursement_receivable_customer';
    				    
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
    	$user_id = $params['user_id'];
    	$receivable_number = $params['receivable_number'];
    	
    	//转换成数组
    	$company_order_customer_id = explode(',', $company_order_customer_id);
    	$sql="insert into receivable_customer (receivable_number,company_order_customer_id,customer_id,create_time,update_time,create_user_id,update_user_id,status) values";
   		$company_order_customer = new CompanyOrderCustomer();
		for($i=0;$i<count($company_order_customer_id);$i++){
			$params_customer['company_order_customer_id'] = $company_order_customer_id[$i];
			//通过companhy_order_customer_id查询数据
			$r = $company_order_customer->getCompanyOrderCustomer($params_customer);
		
			$customer_id = $r[0]['customer_id'];
			$coc_id = $r[0]['company_order_customer_id'];
			$sql.="('$receivable_number',$coc_id,$customer_id,$t,$t,$user_id,$user_id,1),";
				
		}
		
		$sql = trim($sql,',');
	
        $this->startTrans();
        try{
            $this->execute($sql);
         
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
    public function getTravelAgencyReimbursementReceivableCustomer($params)
    {
        $data['status'] = 1;

        if(isset($params['travel_agency_reimbursement_receivable_id'])){
        	$data['travel_agency_reimbursement_receivable_id'] = $params['travel_agency_reimbursement_receivable_id'];
        }

   
        $result = $this->
        where($data)->
        field([
        	'travel_agency_reimbursement_customer_receivable_id','travel_agency_reimbursement_number',
        	'travel_agency_reimbursement_receivable_id','company_order_customer_id','customer_id'	
        		
        		
        ])->select();
	
        return $result;


    }
    


}