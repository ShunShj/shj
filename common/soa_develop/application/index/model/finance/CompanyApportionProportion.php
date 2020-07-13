<?php

namespace app\index\model\finance;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class CompanyApportionProportion extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'company_apportion_proportion';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

	 //获取比率
	 public function getCompanyApportionProportion($params){
	 	
	 	if(isset($params['company_id'])){
	 		$data['company_id'] =  $params['company_id'];
	 	}
	 	
	 	$data['status']= 1;
	 	
	 	
	 	
	 	
	 
	 	$result = $this->where($data)->field([
	 	'company_id','apportion_proportion'		
	 	])->select();
	 	
	 	return $result;
	 }
	 /**
	  * 修改分公司费用分摊比例
	  */
	 public function updateApportionProportion($params){
	 	 
	 	$t = time();
	 	 
	 	$user_id = $params['now_user_id'];
	 	$apportion_proportion = $params['company_apportion_proportion_array'];
	 
	 	$this->startTrans();
	 	try{
	 		//先把所有数据变更为0
	 		$status_params=[
	 			'status'=>0
	 		];
	 		$this->where("1=1")->update($status_params);
	 		for($i=0;$i<count($apportion_proportion);$i++){
	 			$apportion_proportion_params = [
	 					'create_user_id' => $user_id,
	 					'update_user_id' => $user_id,
	 					'update_time' => $t,
	 					'create_time'=>$t,
	 					'apportion_proportion'=>$apportion_proportion[$i]['apportion_proportion'],
	 					'company_id'=>$apportion_proportion[$i]['company_id'],
	 					'status'=>1
	 			];
	 
	 			$this->insert($apportion_proportion_params);
	 			 
	 		}
	 
	 		 
	 		$result = 1;
	 		// 提交事务
	 		Db::commit();
	 		 
	 	} catch (\Exception $e) {
	 		$result = $e->getMessage();
	 		// 回滚事务
	 		$this->rollback();
	 		 
	 	}
	 	return $result;

	 }
}