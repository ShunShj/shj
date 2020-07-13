<?php

namespace app\index\model\system;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class Currency extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'currency';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加货币
     * 胡
     */
    public function addCurrency($params){

        $t = time();
        $data['create_user_id'] = $params['user_id'];
        $data['currency_name'] = $params['currency_name'];
        $data['symbol'] = $params['symbol'];
        $data['unit'] = $params['unit'];
        $data['create_time'] = $t;
        $data['status'] = $params['status'];



        Db::startTrans();
        try{
            Db::name('currency')->insert($data);
      
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

    /**
     * 获取货币数据
     * 胡
     */
    public function getCurrency($params,$is_count=false,$is_page=false,$page=null,$page_size=20){//第一个为参数，第二个为是否要获取 总数

       
    	$data = [];
    	if(!empty($params['currency_id'])){
    		$data['currency_id'] = $params['currency_id'];
    	}
    	if(!empty($params['status'])){
    		$data['status'] = $params['status'];
    	}
    	if(!empty($params['currency_name'])){
    		$data['currency_name'] = ['like', "%".$params['currency_name']."%"];
    	}
		if($is_count==true){
			$result = $this->where($data)->count();
				
		}else{
			if($is_page == true){
				$result = $this->where($data)->order('create_time desc')->limit($page,$page_size)->field(['currency_id','currency_name','symbol','unit','create_time','update_time','status'])
				->order("create_time desc")->select();
		
			}else{
				$result = $this->where($data)->order('create_time desc')->field(['currency_id','currency_name','symbol','unit','create_time','update_time','status'])
				->order("create_time desc")->select();
				
			}
				
		}
	
        return $result;

    }
    /**
     * 获取货币通过currency_id
     * 胡
     */
    public function getCurrencyByCurrencyId($currency_id){
        $language_list = $this->_languageList;

        for($i=0;$i<count($language_list);$i++){

            $result[$language_list[$i]] = $this->table("currency_$language_list[$i]")->where("currency_id  = $currency_id")->field(['currency_id','name','symbol','unit'])->select();

        }
        return $result;
    }

    /**
     * 修改货币 根据currency_id
     */
    public function updateCurrencyByCurrencyId($params){

        $t = time();
        if(!empty($params['status'])){
            $data['status'] = $params['status'];

        }
        if(!empty($params['symbol'])){
            $data['symbol'] = $params['symbol'];

        }
        if(!empty($params['unit'])){
            $data['unit'] = $params['unit'];
      
        }
        if(!empty($params['currency_name'])){
            $data['currency_name'] = $params['currency_name'];

        }
        $data['update_user_id'] = $params['user_id'];

        $data['update_time'] = $t;



        Db::startTrans();
        try{
            Db::name('currency')->where("currency_id = ".$params['currency_id'])->update($data);

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

    /**
     * getOneCurrency
     *
     * 获取一条货币信息
     * @author shj
     *
     * @param $currency_id
     *
     * @return void
     * Date: 2019/2/27
     * Time: 17:57
     */
    public function getOneCurrency($currency_id){
        $result = $this->table("currency")->where(['currency_id' => $currency_id])->find();
        return $result;
    }

}