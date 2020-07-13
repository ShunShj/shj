<?php

namespace app\index\model\branchcompany;
use app\common\help\Help;
use think\Model;
use app\index\service\PublicService;
use think\Db;
class OrderCardPayRecord extends Model{

    protected $table = 'order_cardpay_record';
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
    public function addOrderCardPayRecord($params){

        $data['company_order_number'] = $params['company_order_number'];


		if(!empty($params['ssl_approval_code'])){
			$data['ssl_approval_code'] = $params['ssl_approval_code'];
		}

        if(!empty($params['ssl_card_number'])){
            $data['ssl_card_number'] = $params['ssl_card_number'];
        }

        if(!empty($params['ssl_txn_id'])){
            $data['ssl_txn_id'] = $params['ssl_txn_id'];
        }

        if(!empty($params['ssl_txn_time'])){
            $data['ssl_txn_time'] = $params['ssl_txn_time'];
        }

        if(!empty($params['ssl_amount'])){
            $data['ssl_amount'] = $params['ssl_amount'];
        }

        if(!empty($params['ssl_result_message'])){
            $data['ssl_result_message'] = $params['ssl_result_message'];
        }

        if(!empty($params['ssl_result'])){
            $data['ssl_result'] = $params['ssl_result'];
        }
        
        $data['create_time'] = time();

        try{
            $this->insertGetId($data);
            $result = 1;


        } catch (\Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }


}