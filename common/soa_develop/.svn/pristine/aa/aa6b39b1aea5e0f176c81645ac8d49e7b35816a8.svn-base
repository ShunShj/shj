<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/3
 * Time: 15:56
 */

namespace app\index\model\branchcompany;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;

class CompanyOrderCustomerGuideReceiptFile extends Model
{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'company_order_customer_jounery';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加订单-游客回执单上传文件
     * 韩
     */
    public function addCompanyOrderCustomerGuideReceiptFile($params){
        $t = time();
        $user_id = $params['user_id'];

        $data['company_order_id'] = $params['company_order_id'];
        $data['company_order_number'] = $params['company_order_number'];
        $data['file_name'] = $params['file_name'];

        $data['create_time'] = $t;
        $data['create_user_id']= $user_id;
        $data['update_time'] = $t;
        $data['update_user_id'] = $user_id;
        $data['status'] = 1;

        //修上传文件状态
        $this->table('company_order_customer_jounery')->where(array('company_order_number'=>$params['company_order_number']))->update(['status'=>0]);

        $this->startTrans();
        try{
            $result  = $this->insertGetId($data);


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
     * 获取订单-游客回执单上传文件
     * 韩
     */
    public function getCompanyOrderCustomerGuideReceiptFile(){

        $data = "1=1 ";
        //团队产品ID
        if(isset($params['company_order_id'])){
            $data.= " and company_order_customer_jounery.company_order_id ='".$params['company_order_id']."'";
        }

        //团队产品编号
        if(isset($params['company_order_number'])){
            $data.= " and company_order_customer_jounery.company_order_number ='".$params['company_order_number']."'";
        }

        $result =  $this->query("select * from company_order_customer_jounery where {$data} and status=1");

        return $result;
    }
    
    
    
    /**
     * 修改回执单
     * 胡
     */
    public function updateCompanyOrderGuideReceipt($params){
    	$t = time();

    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    		 
    	}
    	
    	
    	$data['update_user_id'] = $params['now_user_id'];
    	$data['update_time'] = $t;
    	
    	$data_where['company_order_id'] = $params['company_order_id'];
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