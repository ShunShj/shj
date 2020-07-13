<?php

namespace app\index\model\branchcompany;
use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use think\config;
use think\Db;
class CustomerSource extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'customer_source';
    private $_languageList;
    private $_public_service;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        $this->_public_service = new PublicService();
        parent::initialize();

    }

    /**
     * addCustomerSource
     *
     * 新增客户来源
     * @author shj
     *
     * @param $params
     *
     * @return int|string
     * Date: 2019/4/11
     * Time: 17:59
     */
    public function addCustomerSource($params){

        $t = time();
        $data['company_id'] = $params['user_company_id'];

        $data['customer_source_name'] = $params['customer_source_name'];

        if(!empty($params['remark'])){
        	$data['remark'] = $params['remark'];
        }
        
        $data['create_time'] = $t;
        $data['create_user_id'] = $params['now_user_id'];
        $data['update_time'] = $t;
        $data['update_user_id'] = $params['now_user_id'];
        $data['status'] = 1;
        Db::startTrans();
        try{
            $result = Db::name('customer_source')->insertGetId($data);
            // 提交事务
            Db::commit();

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            Db::rollback();

        }

        return $result;
    }

    /**
     * getCustomerSource
     *
     * 获取客户来源列表
     * @author shj
     *
     * @param      $params
     * @param bool $is_count
     *
     * @return false|int|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Date: 2019/4/11
     * Time: 17:59
     */
    public function getCustomerSource($params,$is_count=false){//第一个为参数，第二个为是否要获取 总数

    	$data = "1=1";
    	if(is_numeric($params['status'])){
    		$data.= " and customer_source.status = ".$params['status'];
    	}
    	if(!empty($params['customer_source_name'])){
    		$data.= " and customer_source.customer_source_name = '".$params['customer_source_name']."'";
    	}
    	if(!empty($params['company_id'])){
    		$data.= " and customer_source.company_id = '".$params['company_id']."'";
    	}
    	if(!empty($params['customer_source_id'])){
    		$data.= " and customer_source.customer_source_id = ".$params['customer_source_id'];
    	}
    
        if($is_count==true){
        	$result = $this->where($data)->count();
        }else{
        	if(is_numeric($params['page'])){
                $result= $this->table("customer_source")
                    ->join("user","user.user_id = customer_source.update_user_id",'left')
                    ->join("company","company.company_id = customer_source.company_id",'left')
                    ->where($data)
                    ->order('customer_source.create_time asc')
                    ->limit($params['page'],$params['page_size'])
                    ->field(['customer_source.*','company.company_name','user.nickname as update_username'])
                    ->select();
            }else{
                $result= $this->table("customer_source")
                    ->join("user","user.user_id = customer_source.update_user_id",'left')
                    ->join("company","company.company_id = customer_source.company_id",'left')
                    ->where($data)
                    ->order('customer_source.create_time asc')
                    ->field(['customer_source.*','company.company_name','user.nickname as update_username'])
                    ->select();
        	}

        }
        return $result;

    }

    /**
     * getOneCustomerSource
     *
     * 获取一条客户来源
     * @author shj
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Date: 2019/4/11
     * Time: 17:19
     */
    public function getOneCustomerSource($params){
        $data = "1=1 ";
        if(!empty($params['customer_source_id'])){
            $data.= " and customer_source.customer_source_id = '".$params['customer_source_id']."'";
        }
        $result = $this->table("customer_source")
            ->join("company","company.company_id = customer_source.company_id",'left')
            ->where($data)
            ->field(['customer_source.*','company.company_name'])
            ->find();
        return $result;
    }

    /**
     * updateCustomerSource
     *
     * 修改客户来源
     * @author shj
     *
     * @param $params
     *
     * @return int|string
     * Date: 2019/4/11
     * Time: 17:58
     */
    public function updateCustomerSource($params){

        $t = time();
  

        $data['customer_source_name'] = $params['customer_source_name'];

        if(!empty($params['remark'])){
            $data['remark'] = $params['remark'];
        }
        if(is_numeric($params['status'])){
        	$data['status'] = $params['status'];
        }
   

        $data['update_user_id'] = $params['now_user_id'];

        $data['update_time'] = $t;

        Db::startTrans();
        try{
            Db::name('customer_source')->where("customer_source_id = ".$params['customer_source_id'])->update($data);

            $result = 1;
            // 提交事务
            Db::commit();

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            Db::rollback();

        }
        return $result;
    }

}