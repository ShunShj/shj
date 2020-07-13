<?php
namespace app\index\model\btob;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class AccountCode extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'b2b_account_code';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

    public function addAccountCode($params){

    	$t = time();
    	$data['name'] = $params['name'];
        $data['company_id'] = $params['choose_company_id'];
    	$data['create_time'] = $t;
    	$data['create_user_id'] = $params['now_user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['now_user_id'];
    	$data['status'] = $params['status'];

    	Db::startTrans();
    	try{
    		$pk_id = Db::name('b2b_account_code')->insertGetId($data);
		
    		$result = 1;
    		// 提交事务
    		Db::commit();
    
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		Db::rollback();
    		//\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
    		//exit();
    
    	}
    
    	return $result;
    }

    public function getAccountCode($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
        $data = "1=1";
        if(!empty($params['name'])){
            $data.= " and b2b_account_code.name like '%".$params['name']."%'";
        }
        if($params['status']<2 && is_numeric($params['status'])){
            $data.= " and b2b_account_code.status = ".$params['status'];
        }
        if(is_numeric($params['company_id'])){
            $data.= " and b2b_account_code.company_id = ".$params['company_id'];
        }
        if($is_count==true){
            $result = $this->table("b2b_account_code")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("b2b_account_code")
                    ->where($data)->limit($page, $page_size)
                    ->field(['b2b_account_code.*'])
                    ->select();

            }else{
                $result = $this->table("b2b_account_code")
                    ->where($data)
                    ->field(['b2b_account_code.*'])
                    ->select();
            }
        }
      
        return $result;
    }

    public function getOneAccountCode($params){

        $data = ['account_code_id' => $params['account_code_id']];

        $result = $this->table("b2b_account_code")
            ->field(['b2b_account_code.*'])
            ->where($data)
            ->find();

        return $result;
    }

    public function updateAccountCodeByAccountCodeId($params){
    
    	$t = time();
    	
    	if(isset($params['name'])){
    		$data['name'] = $params['name'];
    	}

    	$data['status'] = $params['status'];

        if(isset($params['choose_company_id'])){
            $data['company_id'] = $params['choose_company_id'];
        }

    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;

    	Db::startTrans();
    	try{
    		Db::name('b2b_account_code')->where("account_code_id = ".$params['account_code_id'])->update($data);
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