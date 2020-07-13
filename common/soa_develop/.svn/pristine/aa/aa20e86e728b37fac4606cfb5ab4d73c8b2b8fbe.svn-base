<?php
namespace app\index\model\Btob;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class CommissionTable extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'b2b_commission_table';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }


    public function addCommission($params){

    	$t = time();
    	$data['name'] = $params['name'];
        $data['order'] = $params['order'];
        $data['color'] = $params['color'];
        $data['content'] = $params['content'];
        $data['company_id'] = $params['choose_company_id'];
    	$data['create_time'] = $t;
    	$data['create_user_id'] = $params['now_user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['now_user_id'];
    	$data['status'] = $params['status'];

    	Db::startTrans();
    	try{
    		$pk_id = Db::name('b2b_commission_table')->insertGetId($data);
		
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
    

    public function getCommission($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
        $data = "1=1";
        if(!empty($params['name'])){
            $data.= " and b2b_commission_table.name like '%".$params['name']."%'";
        }
        if(!empty($params['color'])){
            $data.= " and b2b_commission_table.color = '".$params['color']."'";
        }
        if($params['status']<2 && is_numeric($params['status'])){
            $data.= " and b2b_commission_table.status = ".$params['status'];
        }
        if(is_numeric($params['company_id'])){
            $data.= " and b2b_commission_table.company_id = ".$params['company_id'];
        }

        if($is_count==true){
            $result = $this->table("b2b_commission_table")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("b2b_commission_table")
                    ->where($data)->limit($page, $page_size)
                    ->field(['b2b_commission_table.*'])
                    ->select();

            }else{
                $result = $this->table("b2b_commission_table")
                    ->where($data)
                    ->field(['b2b_commission_table.*'])
                    ->select();
            }
        }
      
        return $result;
    }


    public function getOneCommission($params){

        $data = ['commission_id' => $params['commission_id']];

        $result = $this->table("b2b_commission_table")
            ->field(['b2b_commission_table.*'])
            ->where($data)
            ->find();

        return $result;
    }
    

    public function updateCommissionByCommissionId($params){
    
    	$t = time();
    	
    	if(isset($params['name'])){
    		$data['name'] = $params['name'];
    	}

    	$data['status'] = $params['status'];

    	if(isset($params['order'])){
    		$data['order'] = $params['order'];
    	}

        if(isset($params['choose_company_id'])){
            $data['company_id'] = $params['choose_company_id'];
        }

        if(isset($params['color'])){
            $data['color'] = $params['color'];
        }

        if(isset($params['content'])){
            $data['content'] = $params['content'];
        }

    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;

    	Db::startTrans();
    	try{
    		Db::name('b2b_commission_table')->where("commission_id = ".$params['commission_id'])->update($data);
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