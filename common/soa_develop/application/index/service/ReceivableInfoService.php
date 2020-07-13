<?php
namespace app\index\service;
use app\index\model\finance\FinanceApprove;
use app\index\model\finance\Receivable;
use app\index\model\finance\ReceivableInfo;
use think\Console;
use think\Exception;
use app\common\help\Help;
class ReceivableInfoService{
	private $_finance_approve;
	public function __construct(){
		$this->_finance_approve = new FinanceApprove();
	}

    /**
     * 添加or修改ReceivableInfo（目前只有添加逻辑）
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/4/26
     * Time: 9:11
     * @param mixed
     */
    public function editReceivableInfo($data)
    {
        $receivable_info_model = new ReceivableInfo();

        $data['payment_currency_id'] && $save_data['payment_currency_id'] = $data['payment_currency_id'];       //币种id
        $data['payment_type'] && $save_data['payment_type'] = $data['payment_type'];                      //支付类型
        $data['payment_stage'] && $save_data['payment_stage'] = $data['payment_stage'];                   //支付方式
        $data['sn_number'] && $save_data['sn_number'] = $data['sn_number'];                               //sn_number
        $data['supplier_name'] && $save_data['supplier_name'] = $data['supplier_name'];       //供应商
        $data['exg_rate_gain'] && $save_data['exg_rate_gain'] = $data['exg_rate_gain'];       //汇兑损溢
        $data['payment_time'] && $save_data['payment_time'] = $data['payment_time'];         //付款时间

        $data['account_number'] && $save_data['account_number'] = $data['account_number'];     //account_number
        $data['remark'] && $save_data['remark'] = $data['remark'];                              //备注
        $data['receivable_info_type'] && $save_data['receivable_info_type'] = $data['receivable_info_type'];

        //付款编号
        if ($save_data['receivable_info_type'] == 1)
        {
            //财务手填
            $save_data['payment_number'] = $data['payment_number'];
        }
        else
        {
            //销售 自动
            $save_data['payment_number'] = Help::getNumber(201,2);
        }


        $save_data['create_user_id'] = $data['now_user_id'];         //创建人


        $receivable_number_arr = explode(',', $data['receivable_number']); //应收编号数组

        if($data['receivable_info_id'])
        {
            //修改
        }
        else
        {
            //添加
            try
            {
                $save_data['status'] = 1;     //status
                $receivable_model = new Receivable();
                $receivable_info_model->startTrans();
                foreach ($receivable_number_arr as $value)
                {
                    //如果金额为0 结束循环
                    if($data['payment_money'] == 0){
                        break;
                    }

                    //首先 查询应收
                    $receivable_params = [
                        'receivable_number' => $value
                    ];
                    $receivable_result = $receivable_model->getReceivable($receivable_params);

                    //开始算剩余的应收
                    $miss_receivable =$receivable_result[0]['receivable_money'] - $receivable_result[0]['true_receipt'];

                    if($miss_receivable<=0){
                        continue;
                    }else{
                        //一条应收编号
                        $save_data['receivable_number'] = $value;
                        $save_data['payment_money'] = $data['payment_money'];
                        if($save_data['payment_money'] > $miss_receivable){
                            $save_data['payment_money'] = $miss_receivable;
                        }
						//添加到财务审批
																																												
                        $save_data['finance_type'] = 1;
                        
                        $save_data['receivable_info_type'] = 2;
                        //走审批
                        $save_data['now_user_id'] = $data['now_user_id'];
                        $save_data['user_company_id'] = $data['user_company_id'];
                        $save_data['order_number'] = $data['order_number'];
                        $this->_finance_approve->addFinanceApprove($save_data);
                        //$receivable_info_model->addOrUpdateOne($save_data);

                        $data['payment_money'] -= $save_data['payment_money'];

                    }
                }

                $receivable_info_model->commit();
                return true;
            }
            catch (Exception $e)
            {
                $receivable_info_model->rollback();
                return $e->getMessage();
            }
        }
    }

	
	
}