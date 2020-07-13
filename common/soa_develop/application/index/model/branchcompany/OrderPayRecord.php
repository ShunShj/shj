<?php

namespace app\index\model\branchcompany;
use app\common\help\Help;
use think\Model;
use app\index\service\PublicService;
use think\Db;
class OrderPayRecord extends Model{

    protected $table = 'order_pay_record';
    private $_languageList;
    private $_public_service;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        $this->_public_service = new PublicService();
        parent::initialize();

    }

    /**
     * 添加 支付信息记录
     *
     */
    public function addOrderPayRecord($params){

        $data['company_order_number'] = $params['company_order_number'];


		if(!empty($params['payer_id'])){
			$data['payer_id'] = $params['payer_id'];
		}

        if(!empty($params['payer_email'])){
            $data['payer_email'] = $params['payer_email'];
        }

        if(!empty($params['payment_date'])){
            $data['payment_date'] = $params['payment_date'];
        }

        if(!empty($params['first_name'])){
            $data['first_name'] = $params['first_name'];
        }

        if(!empty($params['last_name'])){
            $data['last_name'] = $params['last_name'];
        }

        if(!empty($params['receiver_email'])){
            $data['receiver_email'] = $params['receiver_email'];
        }

        if(!empty($params['business'])){
            $data['business'] = $params['business'];
        }

        if(!empty($params['mc_gross'])){
            $data['mc_gross'] = $params['mc_gross'];
        }

        if(!empty($params['mc_fee'])){
            $data['mc_fee'] = $params['mc_fee'];
        }

        if(!empty($params['mc_currency'])){
            $data['mc_currency'] = $params['mc_currency'];
        }

        if(!empty($params['receiver_id'])){
            $data['receiver_id'] = $params['receiver_id'];
        }

        if(!empty($params['txn_id'])){
            $data['txn_id'] = $params['txn_id'];
        }

        if(!empty($params['payment_status'])){
            $data['payment_status'] = $params['payment_status'];
        }

        if(!empty($params['pending_reason'])){
            $data['pending_reason'] = $params['pending_reason'];
        }

        
        $data['create_time'] = time();
        $data['uuid'] = Help::getUuid();



        $this->startTrans();
        try{
            $result = $this->insertGetId($data);
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