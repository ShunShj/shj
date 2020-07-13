<?php

namespace app\index\model\Btob;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class DistributorType extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'distributor_type';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加经销商
     * 胡
     */
    public function addDistributorType($params){

        $t = time();
       
        $data['company_id'] = $params['company_id'];
        $data['distributor_type_name'] = $params['distributor_type_name'];
        
        $data['create_time'] = $t;
        $data['create_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        $data['update_user_id'] = $params['user_id'];
        $data['status'] = $params['status'];


        Db::startTrans();
        try{
            $result = Db::name('distributor_type')->insertGetId($data);
  
       
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
     * 获取经销商数据
     * 胡
     */
    public function getDistributorType($params,$is_count=false,$is_page=false,$page=null,$page_size=20){

 
    	$data = "1=1";
    	if($params['distributor_type_id']>0){
    		$data.= " and distributor_type.distributor_type_id = '".$params['distributor_type_id']."'";
    	}
    	if($params['status']<2 && isset($params['status'])){
    		$data.= " and distributor_type.status = ".$params['status'];
    	}
    	if(!empty($params['distributor_type_name'])){
    		$data.= " and distributor_type.distributor_type_name like'%".$params['distributor_type_name']."%'";
    	}
        if(!empty($params['choose_company_id'])){
            $data.= " and distributor_type.company_id = '".$params['choose_company_id']."'";
        }

        if($is_count==true){
            $result = $this->table("distributor_type")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("distributor_type")
                    ->join('user','user.user_id = distributor_type.update_user_id', 'left')
                    ->where($data)->limit($page, $page_size)->order('create_time desc')
                    ->field(['distributor_type.*','user.nickname'])
                    ->select();
            }else{
                $result = $this->table("distributor_type")
                    ->join('user','user.user_id = distributor_type.update_user_id', 'left')
                    ->where($data)->order('create_time desc')
                    ->field(['distributor_type.*','user.nickname'])
                    ->select();
            }
        }

       


        return $result;

    }


    /**
     * 修改经销商 根据distributor_id
     */
    public function updateDistributorType($params){

        $t = time();
        
        if(!empty($params['company_id'])){
        	$data['company_id'] = $params['company_id'];
        }
        if(!empty($params['distributor_type_name'])){
        	$data['distributor_type_name'] = $params['distributor_type_name'];
        }
        if(isset($params['status'])){
            $data['status'] = $params['status'];
        }
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;

        Db::startTrans();
        try{
            Db::name('distributor_type')->where("distributor_type_id = ".$params['distributor_type_id'])->update($data);

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