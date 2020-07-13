<?php

namespace app\index\model\publicmodel;
use think\Model;
use app\common\help\Help;
class Common extends Model{
   // protected $connection = ['database' => 'erp'];
    protected $table = 'member';


    //初始化方法
    protected function initialize()
    {
//     	$log_name = config('log_database');
//     	$this->connection = ['database'=>$log_name];
        parent::initialize();

    }
    //设置编号
    public function setNumber($table_name,$major_key,$major_key_val,$number_field,$number){

    	$data = [
    		$number_field=>$number
    	];
    	$where = [
    			$major_key=>$major_key_val
    	];
        
	
        $this->startTrans();
        try{
        	$result = $this->table($table_name)->where($where)->update($data);
        	
        	
        	$this->commit();
        
        } catch (\Exception $e) {
        	$result = $e->getMessage();
        	// 回滚事务
        	$this->rollback();
        	//\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
        	//exit();
        
        }
        
        return $result;
    }
}