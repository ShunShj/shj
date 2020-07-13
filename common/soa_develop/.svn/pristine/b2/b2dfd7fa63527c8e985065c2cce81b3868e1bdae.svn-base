<?php

namespace app\index\model\finance;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;

use app\index\model\branchcompany\CompanyOrderCustomer;
class TravelAgencyReimbursementReceivable extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'travel_agency_reimbursement_receivable';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 获取地接报账的应收
     * 胡
     */
    public function getTravelAgencyReimbursementReceivable($params){
    	
    	$data = [];
    	if(isset($params['travel_agency_reimbursement_number'])){
    		$data['travel_agency_reimbursement_number']= $params['travel_agency_reimbursement_number'];
    	}
		if(is_numeric($params['status'])){
			$data['status']= $params['status'];
		}
        $result= $this->
          
            where($data)->
            field([
            		'travel_agency_reimbursement_receivable_id','company_order_number','source_type_id','source_name','currency_id','reimbursement_money'
            		
            ])->select();
	
        return $result;

    }


}