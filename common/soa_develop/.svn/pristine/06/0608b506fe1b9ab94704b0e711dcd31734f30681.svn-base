<?php

namespace app\index\model\system;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class CurrencyProportion extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'currency_proportion';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }
    /**
     * 添加货币汇率
     */
    public function addProportionByCurrencyProportion($params){

        $t = time();
        $user_id = $params['now_user_id'];
		$sql =  "insert into currency_proportion (currency_id,opposite_currency_id,currency_proportion,proportion_time,create_time,create_user_id,update_time,update_user_id,status) values";
		$currency_proportion_arr = $params['currency_proportion_arr'];
		for($i=0;$i<count($currency_proportion_arr);$i++){
			$currency_id = $currency_proportion_arr[$i]['currency_id'];
			$currency_proportion_currency_id = $currency_proportion_arr[$i]['currency_proportion_currency_id'];
			$currency_proportion =$currency_proportion_arr[$i]['currency_proportion'];
			$proportion_time = $params['proportion_time'];
			$sql.="($currency_id,$currency_proportion_currency_id,$currency_proportion,$proportion_time,$t,$user_id,$t,$user_id,1),";
			 
			 
		}
		$sql = trim($sql,',');
    	
        $this->startTrans();
        try{
			//先把本月的弄为0
			$update_params=[
				'status'=>0	
			];
			$this->where("proportion_time = ".$params['proportion_time'])->update($update_params);
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
	public function addCurrencyProportion($params){
		
		$data['currency_id'] = $params['currency_id'];
		$data['opposite_currency_id'] = $params['opposite_currency_id'];
		$data['currency_proportion'] = $params['currency_proportion'];
		$data['proportion_time'] = $params['proportion_time'];
		$data['create_time'] = time();
		$data['update_time'] = time();
		$data['create_user_id'] = 1;
		$data['update_user_id'] = 1;
		$data['status'] = 1;
		$this->insert($data);
		

		
	}
    /**
     * 获取货币汇率
     * 王
     */
    public function getCurrencyProportion($params){
    	if(!empty($params['proportion_time'])){
    		$data['proportion_time'] = $params['proportion_time'];
    	}
    	
    	
		if(!empty($params['currency_id'])){
			$data['currency_id'] = $params['currency_id'];
		}
		if(!empty($params['opposite_currency_id'])){
			$data['opposite_currency_id'] = $params['opposite_currency_id'];
		}
			$data['status']=1;


		$result = $this->where($data)->select();

        return $result;
    }
//修改查询
    public function selectCurrencyProportion($params){

            $data = "1=1";
            if(isset($params['proportion_time'])){
                $data['proportion_time']=$params['proportion_time'];
            }
            $currency_id_result = Db::table('currency_proportion')->where('proportion_time='.$params['proportion_time'])->select();
            if(count($currency_id_result)>0){
                return 1;
            }else{
                return 2;
            }
    }

    /**
     * 修改货币汇率
     */
    public function updateCurrencyProportionByCurrencyProportionId($params){

        $t = time();

        $data=[
            "currency_id"=>$params['branch_product_team_array']['currency_id'],
            "opposite_currency_id"=>$params['branch_product_team_array']['opposite_currency_id'],
            "currency_proportion"=>$params['branch_product_team_array']['currency_proportion'],
            "update_user_id"=>$params['branch_product_team_array']['update_user_id'],
            "proportion_time"=>$params['branch_product_team_array']['proportion_time'],
            "update_time" => $t,
            ];

        Db::startTrans();
        try{
            Db::name('currency_proportion')->where("currency_proportion_id = ".$params['branch_product_team_array']['currency_proportion_id'])->update($data);

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