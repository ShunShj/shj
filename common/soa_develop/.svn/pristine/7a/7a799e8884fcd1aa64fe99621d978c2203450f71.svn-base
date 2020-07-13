<?php
namespace app\index\model\profit_statement;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class ProfitStatement extends Model{ 
	
	private $_languageList;

    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize(); 
    }


   /***
    * 获取分公司利润数据（带货币转换）
    */
    public function getObtainBranchProfitDataWithCurrencyConversion($params){ 

        //获取预算金额
        $where['company_id'] = $params['company_id'];
        $where['years'] = $params['years'];
        $where['month'] = $params['month'];
        $where['is_predict'] = 1;
        $where['status'] = 1;

        $budgetAmount = $this->table('profit_statement')->field([
            '*',
            "(select company.company_name from company where company.company_id=profit_statement.company_id) as company_name",
            "(select user.nickname from user where user.user_id=profit_statement.create_user_id) as create_nickname",
            "(select user.nickname from user where user.user_id=profit_statement.update_user_id) as update_nickname",
        ])->where($where)->find(); 
        unset($where); 

        // if(empty($budgetAmount)){
        //     $budgetAmount['number_of_staff'] = 0; 
        //     $budgetAmount['number_of_guests_received'] = 0;
        //     $budgetAmount['order_amount'] = 0;
        //     $budgetAmount['main_operating_income'] = 0;
        //     $budgetAmount['external_income'] = 0;
        //     $budgetAmount['internal_settlement_income'] = 0;
        //     $budgetAmount['main_business_cost'] = 0;
        //     $budgetAmount['external_cost'] = 0;
        //     $budgetAmount['internal_settlement_cost'] = 0;
        //     $budgetAmount['gross_profit'] = 0;
        //     $budgetAmount['ratio_of_margin'] = 0;
        //     $budgetAmount['operating_taxes_and_attachments'] = 0;
        //     $budgetAmount['selling_expenses'] = 0;
        //     $budgetAmount['commission'] = 0;
        //     $budgetAmount['other'] = 0;
        //     $budgetAmount['overhead_expenses'] = 0;
        //     $budgetAmount['salary'] = 0;
        //     $budgetAmount['chummage'] = 0;
        //     $budgetAmount['hydroelectricity'] = 0;
        //     $budgetAmount['handle_official_business'] = 0;
        //     $budgetAmount['cost_of_financing'] = 0;
        //     $budgetAmount['interest'] = 0;
        //     $budgetAmount['exchange_gain_or_loss'] = 0;
        //     $budgetAmount['poundage'] = 0;
        //     $budgetAmount['nonbusiness_income'] = 0;
        //     $budgetAmount['non_business_expenditure'] = 0;
        //     $budgetAmount['total_profit'] = 0;
        //     $budgetAmount['income_tax'] = 0;
        //     $budgetAmount['net_margin'] = 0; 
        // }
        $ar['budgetAmount'] = $budgetAmount; 

        //获取实际金额
        $where['company_id'] = $params['company_id'];
        $where['years'] = $params['years'];
        $where['month'] = $params['month'];
        $where['is_predict'] = 1;
        $where['status'] = 1;
        $where['is_predict'] = 2;
        $amountOfActualBalance = $this->table('profit_statement')->field([
            '*',
            "(select company.company_name from company where company.company_id=profit_statement.company_id) as company_name",
            "(select user.nickname from user where user.user_id=profit_statement.create_user_id) as create_nickname",
            "(select user.nickname from user where user.user_id=profit_statement.update_user_id) as update_nickname",
        ])->where($where)->find(); 
        unset($where);  
        // if(empty($amountOfActualBalance)){
        //     $amountOfActualBalance['number_of_staff'] = 0; 
        //     $amountOfActualBalance['number_of_guests_received'] = 0;
        //     $amountOfActualBalance['order_amount'] = 0;
        //     $amountOfActualBalance['main_operating_income'] = 0;
        //     $amountOfActualBalance['external_income'] = 0;
        //     $amountOfActualBalance['internal_settlement_income'] = 0;
        //     $amountOfActualBalance['main_business_cost'] = 0;
        //     $amountOfActualBalance['external_cost'] = 0;
        //     $amountOfActualBalance['internal_settlement_cost'] = 0;
        //     $amountOfActualBalance['gross_profit'] = 0;
        //     $amountOfActualBalance['ratio_of_margin'] = 0;
        //     $amountOfActualBalance['operating_taxes_and_attachments'] = 0;
        //     $amountOfActualBalance['selling_expenses'] = 0;
        //     $amountOfActualBalance['commission'] = 0;
        //     $amountOfActualBalance['other'] = 0;
        //     $amountOfActualBalance['overhead_expenses'] = 0;
        //     $amountOfActualBalance['salary'] = 0;
        //     $amountOfActualBalance['chummage'] = 0;
        //     $amountOfActualBalance['hydroelectricity'] = 0;
        //     $amountOfActualBalance['handle_official_business'] = 0;
        //     $amountOfActualBalance['cost_of_financing'] = 0;
        //     $amountOfActualBalance['interest'] = 0;
        //     $amountOfActualBalance['exchange_gain_or_loss'] = 0;
        //     $amountOfActualBalance['poundage'] = 0;
        //     $amountOfActualBalance['nonbusiness_income'] = 0;
        //     $amountOfActualBalance['non_business_expenditure'] = 0;
        //     $amountOfActualBalance['total_profit'] = 0;
        //     $amountOfActualBalance['income_tax'] = 0;
        //     $amountOfActualBalance['net_margin'] = 0; 
        // }
        $ar['budgetAmount'] = $amountOfActualBalance;  
        
        if($params['company_currency_id'] && $params['change_currency_id']){
            //获取该月汇率
            $eYearMonth = $params['years'].'-'.$params['month'].'-01';
            $proportion_time = date('Ymd',strtotime("{$eYearMonth} +1 month -1 day")) ;    
            $currency_proportion = $this->get_currency_proportion($params['company_currency_id'],$params['change_currency_id'],$proportion_time);

            //预算
            $budgetAmount['order_amount']  = sprintf("%.2f",$budgetAmount['order_amount']*$currency_proportion); 
            $budgetAmount['main_operating_income'] = sprintf("%.2f",$budgetAmount['main_operating_income']*$currency_proportion);
            $budgetAmount['external_income'] = sprintf("%.2f",$budgetAmount['external_income']*$currency_proportion);
            $budgetAmount['internal_settlement_income']  = sprintf("%.2f",$budgetAmount['main_operating_income']*$currency_proportion);
            $budgetAmount['main_business_cost']  = sprintf("%.2f",$budgetAmount['main_business_cost']*$currency_proportion); 
            $budgetAmount['external_cost']  = sprintf("%.2f",$budgetAmount['external_cost']*$currency_proportion); 
            $budgetAmount['internal_settlement_cost']  = sprintf("%.2f",$budgetAmount['internal_settlement_cost']*$currency_proportion); 
            $budgetAmount['gross_profit']  = sprintf("%.2f",$budgetAmount['internal_settlement_income']-$budgetAmount['internal_settlement_cost']);  
            $budgetAmount['ratio_of_margin'] = sprintf('%.4f',$budgetAmount['internal_settlement_cost']/$budgetAmount['main_business_cost']);  
            $budgetAmount['operating_taxes_and_attachments'] = sprintf("%.2f",$budgetAmount['operating_taxes_and_attachments']*$currency_proportion); 
            $budgetAmount['selling_expenses'] = sprintf("%.2f",$budgetAmount['selling_expenses']*$currency_proportion); 
            $budgetAmount['commission'] = sprintf("%.2f",$budgetAmount['commission']*$currency_proportion);  
            $budgetAmount['other'] = sprintf("%.2f",$budgetAmount['other']*$currency_proportion);  
            $budgetAmount['overhead_expenses'] = sprintf("%.2f",$budgetAmount['overhead_expenses']*$currency_proportion); 
            $budgetAmount['salary'] = sprintf("%.2f",$budgetAmount['salary']*$currency_proportion);  
            $budgetAmount['chummage'] = sprintf("%.2f",$budgetAmount['chummage']*$currency_proportion); 
            $budgetAmount['hydroelectricity'] = sprintf("%.2f",$budgetAmount['hydroelectricity']*$currency_proportion);  
            $budgetAmount['handle_official_business'] = sprintf("%.2f",$budgetAmount['handle_official_business']*$currency_proportion); 
            $budgetAmount['cost_of_financing'] = sprintf("%.2f",$budgetAmount['cost_of_financing']*$currency_proportion);  
            $budgetAmount['interest'] = sprintf("%.2f",$budgetAmount['interest']*$currency_proportion); 
            $budgetAmount['exchange_gain_or_loss'] = sprintf("%.2f",$budgetAmount['exchange_gain_or_loss']*$currency_proportion);  
            $budgetAmount['poundage'] = sprintf("%.2f",$budgetAmount['poundage']*$currency_proportion);  
            $budgetAmount['nonbusiness_income'] = sprintf("%.2f",$budgetAmount['nonbusiness_income']*$currency_proportion);  
            $budgetAmount['non_business_expenditure'] = sprintf("%.2f",$budgetAmount['non_business_expenditure']*$currency_proportion); 
            $budgetAmount['total_profit'] = sprintf("%.2f",$budgetAmount['total_profit']*$currency_proportion);   
            $budgetAmount['income_tax'] = sprintf("%.2f",$budgetAmount['income_tax']*$currency_proportion); 
            $budgetAmount['net_margin'] = sprintf("%.2f",$budgetAmount['net_margin']*$currency_proportion);    
            $ar['budgetAmount'] = $budgetAmount;

            //实际
            $year_month =  $params['years'].'-'.$params['month'];  
            $sTime = strtotime($year_month);
            $eTime = strtotime('+1 months',$sTime);
            //收客人数 
            $where = "company_order.company_id={$params['company_id']} and company_order_customer_lineup.lineup_type=1 and company_order.status=1 and company_order_customer_lineup.status=1";
            $where .= " and company_order.create_time>={$sTime} and company_order.create_time<{$eTime}"; //按订单日期计算
            $amountOfActualBalance['number_of_guests_received'] = $this->table('company_order_customer_lineup')->join('company_order','company_order_customer_lineup.company_order_number=company_order.company_order_number','left')
            ->field(['count(company_order_customer_lineup.company_order_customer_lineup_id) as coun'])->where($where)->find()['coun'];
            //订单金额
            $where = "company_id={$params['company_id']} and status=1 and create_time>={$sTime} and create_time<{$eTime}";
            $receivable = $this->table('receivable')->field([
                'sum(receivable_money) as sum_receivable_money','receivable_currency_id'
            ])->where($where)->group('receivable_currency_id')->select();
            
            //汇率换算
            $order_amount = 0;
            $eYearMonth = $params['years'].'-'.$params['month'].'-01';
            $proportion_time = date('Ymd',strtotime("{$eYearMonth} +1 month -1 day")) ;
            foreach ($receivable as $key => $value) {
                $currency_proportion = $this->get_currency_proportion($value['receivable_currency_id'],$params['change_currency_id'],$proportion_time);
                $order_amount += $value['sum_receivable_money']*$currency_proportion;
            }
            $amountOfActualBalance['order_amount'] = sprintf('%.2f',$order_amount);

            $amountOfActualBalance['main_operating_income'] = sprintf("%.2f",$amountOfActualBalance['main_operating_income']*$currency_proportion);
            $amountOfActualBalance['external_income'] = sprintf("%.2f",$amountOfActualBalance['external_income']*$currency_proportion);
            $amountOfActualBalance['internal_settlement_income']  = sprintf("%.2f",$amountOfActualBalance['main_operating_income']*$currency_proportion);
            $amountOfActualBalance['main_business_cost']  = sprintf("%.2f",$amountOfActualBalance['main_business_cost']*$currency_proportion); 
            $amountOfActualBalance['external_cost']  = sprintf("%.2f",$amountOfActualBalance['external_cost']*$currency_proportion); 
            $amountOfActualBalance['internal_settlement_cost']  = sprintf("%.2f",$amountOfActualBalance['internal_settlement_cost']*$currency_proportion); 
            $amountOfActualBalance['gross_profit']  = sprintf("%.2f",$amountOfActualBalance['internal_settlement_income']-$amountOfActualBalance['internal_settlement_cost']);  
            $amountOfActualBalance['ratio_of_margin'] = sprintf('%.4f',$amountOfActualBalance['internal_settlement_cost']/$amountOfActualBalance['main_business_cost']);  
            $amountOfActualBalance['operating_taxes_and_attachments'] = sprintf("%.2f",$amountOfActualBalance['operating_taxes_and_attachments']*$currency_proportion); 
            $amountOfActualBalance['selling_expenses'] = sprintf("%.2f",$amountOfActualBalance['selling_expenses']*$currency_proportion); 
            $amountOfActualBalance['commission'] = sprintf("%.2f",$amountOfActualBalance['commission']*$currency_proportion);  
            $amountOfActualBalance['other'] = sprintf("%.2f",$amountOfActualBalance['other']*$currency_proportion);  
            $amountOfActualBalance['overhead_expenses'] = sprintf("%.2f",$amountOfActualBalance['overhead_expenses']*$currency_proportion); 
            $amountOfActualBalance['salary'] = sprintf("%.2f",$amountOfActualBalance['salary']*$currency_proportion);  
            $amountOfActualBalance['chummage'] = sprintf("%.2f",$amountOfActualBalance['chummage']*$currency_proportion); 
            $amountOfActualBalance['hydroelectricity'] = sprintf("%.2f",$amountOfActualBalance['hydroelectricity']*$currency_proportion);  
            $amountOfActualBalance['handle_official_business'] = sprintf("%.2f",$amountOfActualBalance['handle_official_business']*$currency_proportion); 
            $amountOfActualBalance['cost_of_financing'] = sprintf("%.2f",$amountOfActualBalance['cost_of_financing']*$currency_proportion);  
            $amountOfActualBalance['interest'] = sprintf("%.2f",$amountOfActualBalance['interest']*$currency_proportion); 
            $amountOfActualBalance['exchange_gain_or_loss'] = sprintf("%.2f",$amountOfActualBalance['exchange_gain_or_loss']*$currency_proportion);  
            $amountOfActualBalance['poundage'] = sprintf("%.2f",$amountOfActualBalance['poundage']*$currency_proportion);  
            $amountOfActualBalance['nonbusiness_income'] = sprintf("%.2f",$amountOfActualBalance['nonbusiness_income']*$currency_proportion);  
            $amountOfActualBalance['non_business_expenditure'] = sprintf("%.2f",$amountOfActualBalance['non_business_expenditure']*$currency_proportion); 
            $amountOfActualBalance['total_profit'] = sprintf("%.2f",$amountOfActualBalance['total_profit']*$currency_proportion);   
            $amountOfActualBalance['income_tax'] = sprintf("%.2f",$amountOfActualBalance['income_tax']*$currency_proportion); 
            $amountOfActualBalance['net_margin'] = sprintf("%.2f",$amountOfActualBalance['net_margin']*$currency_proportion);    
               

            // $amountOfActualBalance['main_operating_income'] *= $currency_proportion;  
            // $amountOfActualBalance['external_income'] *= $currency_proportion;  
            // $amountOfActualBalance['internal_settlement_income'] *= $currency_proportion;  
            // $amountOfActualBalance['main_business_cost'] *= $currency_proportion;  
            // $amountOfActualBalance['external_cost'] *= $currency_proportion;  
            // $amountOfActualBalance['internal_settlement_cost'] *= $currency_proportion;  
            // $amountOfActualBalance['gross_profit']  = sprintf('%.2f', $amountOfActualBalance['internal_settlement_income']-$amountOfActualBalance['internal_settlement_cost']);
            // $amountOfActualBalance['ratio_of_margin'] = sprintf('%.4f', $amountOfActualBalance['internal_settlement_cost']/$amountOfActualBalance['main_business_cost']);  
            // $amountOfActualBalance['operating_taxes_and_attachments'] *= $currency_proportion;  
            // $amountOfActualBalance['selling_expenses'] *= $currency_proportion;  
            // $amountOfActualBalance['commission'] *= $currency_proportion;  
            // $amountOfActualBalance['other'] *= $currency_proportion;  
            // $amountOfActualBalance['overhead_expenses'] *= $currency_proportion;  
            // $amountOfActualBalance['salary'] *= $currency_proportion;  
            // $amountOfActualBalance['chummage'] *= $currency_proportion;  
            // $amountOfActualBalance['hydroelectricity'] *= $currency_proportion;  
            // $amountOfActualBalance['handle_official_business'] *= $currency_proportion;  
            // $amountOfActualBalance['cost_of_financing'] *= $currency_proportion;  
            // $amountOfActualBalance['interest'] *= $currency_proportion;  
            // $amountOfActualBalance['exchange_gain_or_loss'] *= $currency_proportion;  
            // $amountOfActualBalance['poundage'] *= $currency_proportion;  
            // $amountOfActualBalance['nonbusiness_income'] *= $currency_proportion;  
            // $amountOfActualBalance['non_business_expenditure'] *= $currency_proportion;  
            // $amountOfActualBalance['total_profit'] *= $currency_proportion;  
            // $amountOfActualBalance['income_tax'] *= $currency_proportion;  
            // $amountOfActualBalance['net_margin'] *= $currency_proportion;   
            $ar['amountOfActualBalance'] = $amountOfActualBalance;  
            unset($where);
            //累计金额
            $where['years'] = $params['years'];
            $where['month'] = ['<=',$params['month']];
            $where['company_id'] = $params['company_id'];
            $where['status'] = 1;
            $where['is_predict'] = 2;
            $accumulatedAmount = $this->table('profit_statement')->field([
                '*',
                "(select company.company_name from company where company.company_id=profit_statement.company_id) as company_name",
                "(select user.nickname from user where user.user_id=profit_statement.create_user_id) as create_nickname",
                "(select user.nickname from user where user.user_id=profit_statement.update_user_id) as update_nickname",
            ])->where($where)->select();

            $ary['number_of_staff'] = 0; 
            $ary['number_of_guests_received'] = 0;
            $ary['order_amount'] = 0;
            $ary['main_operating_income'] = 0;
            $ary['external_income'] = 0;
            $ary['internal_settlement_income'] = 0;
            $ary['main_business_cost'] = 0;
            $ary['external_cost'] = 0;
            $ary['internal_settlement_cost'] = 0;
            $ary['gross_profit'] = 0;
            $ary['ratio_of_margin'] = 0;
            $ary['operating_taxes_and_attachments'] = 0;
            $ary['selling_expenses'] = 0;
            $ary['commission'] = 0;
            $ary['other'] = 0;
            $ary['overhead_expenses'] = 0;
            $ary['salary'] = 0;
            $ary['chummage'] = 0;
            $ary['hydroelectricity'] = 0;
            $ary['handle_official_business'] = 0;
            $ary['cost_of_financing'] = 0;
            $ary['interest'] = 0;
            $ary['exchange_gain_or_loss'] = 0;
            $ary['poundage'] = 0;
            $ary['nonbusiness_income'] = 0;
            $ary['non_business_expenditure'] = 0;
            $ary['total_profit'] = 0;
            $ary['income_tax'] = 0;
            $ary['net_margin'] = 0; 
             
            foreach ($accumulatedAmount as $key => $value) {
               //获取该月汇率
                $eYearMonth = $params['years'].'-'.$value['month'].'-01';
                $proportion_time = date('Ymd',strtotime("{$eYearMonth} +1 month -1 day")) ;    
                $currency_proportion = $this->get_currency_proportion($params['company_currency_id'],$params['change_currency_id'],$proportion_time);

                $ary['main_operating_income'] += sprintf('%.2f',$value['main_operating_income']*$currency_proportion);
                $ary['external_income'] += sprintf('%.2f',$value['external_income']*$currency_proportion);
                $ary['internal_settlement_income'] += sprintf('%.2f',$value['internal_settlement_income']*$currency_proportion);
                $ary['main_business_cost'] += sprintf('%.2f',$value['main_business_cost']*$currency_proportion);
                $ary['external_cost'] += sprintf('%.2f',$value['external_cost']*$currency_proportion);
                $ary['internal_settlement_cost'] += sprintf('%.2f',$value['internal_settlement_cost']*$currency_proportion);
                $ary['operating_taxes_and_attachments'] += sprintf('%.2f',$value['operating_taxes_and_attachments']*$currency_proportion);
                $ary['selling_expenses'] += sprintf('%.2f',$value['selling_expenses']*$currency_proportion);
                $ary['commission'] += sprintf('%.2f',$value['commission']*$currency_proportion);
                $ary['other'] += sprintf('%.2f',$value['other']*$currency_proportion);
                $ary['overhead_expenses'] += sprintf('%.2f',$value['overhead_expenses']*$currency_proportion);
                $ary['salary'] += sprintf('%.2f',$value['salary']*$currency_proportion);
                $ary['chummage'] += sprintf('%.2f',$value['chummage']*$currency_proportion);
                $ary['hydroelectricity'] += sprintf('%.2f',$value['hydroelectricity']*$currency_proportion);
                $ary['handle_official_business'] += sprintf('%.2f',$value['handle_official_business']*$currency_proportion);
                $ary['cost_of_financing'] += sprintf('%.2f',$value['cost_of_financing']*$currency_proportion);
                $ary['interest'] += sprintf('%.2f',$value['interest']*$currency_proportion);
                $ary['exchange_gain_or_loss'] += sprintf('%.2f',$value['exchange_gain_or_loss']*$currency_proportion);
                $ary['poundage'] += sprintf('%.2f',$value['poundage']*$currency_proportion);
                $ary['nonbusiness_income'] += sprintf('%.2f',$value['nonbusiness_income']*$currency_proportion);
                $ary['non_business_expenditure'] += sprintf('%.2f',$value['non_business_expenditure']*$currency_proportion);
                $ary['total_profit'] += sprintf('%.2f',$value['total_profit']*$currency_proportion);
                $ary['income_tax'] += sprintf('%.2f',$value['income_tax']*$currency_proportion);
                $ary['net_margin'] += sprintf('%.2f',$value['net_margin']*$currency_proportion);
                
            } 

            $ary['gross_profit']  = sprintf('%.2f', $ary['internal_settlement_income']-$ary['internal_settlement_cost']);  
            $ary['ratio_of_margin'] = sprintf('%.4f', $ary['internal_settlement_cost']/$ary['main_business_cost']);  

            $wt['years'] = $params['years'];
            $wt['month'] = $params['month'];
            $wt['company_id'] = $params['company_id'];
            $wt['status'] = 1;
            $wt['is_predict'] = 2;
            $number_of_staff = $this->table('profit_statement')->field(['number_of_staff'])->where($wt)->find();
            if($number_of_staff){
                 $ary['number_of_staff'] =  $number_of_staff['number_of_staff'];  
            }else{
               $m = ltrim($params['month'],0)-1;
               if($m<10){
                 $m = '0'.$m;
               }
               $number_of_staff = $this->table('profit_statement')->field(['number_of_staff'])->where($wt)->find();
               $ary['number_of_staff'] =  $number_of_staff['number_of_staff'];  
            }    
            //收客人数
            $where = "company_order.company_id={$params['company_id']} and company_order_customer_lineup.lineup_type=1 and company_order.status=1 and company_order_customer_lineup.status=1";
            $where .= " and company_order.create_time>={$sTime} and company_order.create_time<{$eTime}"; //按订单日期计算

            $ary['number_of_guests_received'] = $this->table('company_order_customer_lineup')->join('company_order','company_order_customer_lineup.company_order_number=company_order.company_order_number','left')
            ->field(['count(company_order_customer_lineup.company_order_customer_lineup_id) as coun'])->where($where)->find()['coun']; 

            // 订单金额
            $order_amount = 0;
            $params_month = ltrim($params['month'],0);
            for($i=1;$i<=$params_month;$i++){
                $ii = $i;
                if($i<10){
                    $ii = '0'.$i;   
                }
                $year_month = $params['years'].'-'.$ii;
                $sTime = strtotime($year_month);
                $eTime = strtotime('+1 months',strtotime($year_month));     
                $where = "company_id={$params['company_id']} and status=1 and create_time>={$sTime} and create_time<{$eTime}";
                $receivable = $this->table('receivable')->field([
                    'sum(receivable_money) as sum_receivable_money','receivable_currency_id'
                ])->where($where)->group('receivable_currency_id')->select(); 
                
                //汇率
                $eYearMonth = $params['years'].'-'.$ii.'-01';
                $proportion_time = date('Ymd',strtotime("{$eYearMonth} +1 month -1 day")) ;
                foreach ($receivable as $key => $value) {
                    $currency_proportion = $this->get_currency_proportion($value['receivable_currency_id'],$params['change_currency_id'],$proportion_time);
                    $order_amount += $value['sum_receivable_money']*$currency_proportion;
                }
                    
            }
            $ary['order_amount'] = $order_amount; 

            $ary['number_of_guests_received'] = 0;
            $ary['order_amount'] = 0;

            $ar['accumulatedAmount'] = $ary;
        }

       
        return $ar;
    }


    /**
    * 获取分公司利润预计
    */
    public function gainBranchProfits($params){ 
    	$where['company_id'] = $params['company_id'];
    	$where['years'] = $params['years'];
    	$where['month'] = $params['month'];
    	$where['is_predict'] = $params['is_predict'];
    	$where['status'] = 1;

    	$return = $this->table('profit_statement')->field([
    		'*',
    		"(select company.company_name from company where company.company_id=profit_statement.company_id) as company_name",
    		"(select user.nickname from user where user.user_id=profit_statement.create_user_id) as create_nickname",
    		"(select user.nickname from user where user.user_id=profit_statement.update_user_id) as update_nickname",
    	])->where($where)->find();

    	 

    	if($params['is_predict']==2){ //实际利润
    		$year_month =  $params['years'].'-'.$params['month'];

    		//获取分公司货币
    		$where = "company_id={$params['company_id']}";
    		$currency_id = $this->table('company')->field(['currency_id'])->where($where)->find()['currency_id']; 

    		$sTime = strtotime($year_month);
    		$eTime = strtotime('+1 months',$sTime); 

    		//收客 select company_order_customer_lineup.* from company_order_customer_lineup  LEFT JOIN company_order  on company_order_customer_lineup.company_order_number=company_order.company_order_number where company_order.company_id=13 and company_order.`status`=1 and company_order_customer_lineup.`status`=1 and company_order.create_time>=1546272000 and company_order.create_time<1548950400
    		$where = "company_order.company_id={$params['company_id']} and company_order_customer_lineup.lineup_type=1 and company_order.status=1 and company_order_customer_lineup.status=1";
    		$where .= " and company_order.create_time>={$sTime} and company_order.create_time<{$eTime}"; //按订单日期计算

    		$return['number_of_guests_received'] = $this->table('company_order_customer_lineup')->join('company_order','company_order_customer_lineup.company_order_number=company_order.company_order_number','left')
    		->field(['count(company_order_customer_lineup.company_order_customer_lineup_id) as coun'])->where($where)->find()['coun'];

    		//订单金额 select sum(receivable_money),receivable_currency_id from receivable where company_id=13 and `status`=1 and create_time>=1546272000 and create_time<1548950400 GROUP BY receivable_currency_id;
    		$where = "company_id={$params['company_id']} and status=1 and create_time>={$sTime} and create_time<{$eTime}";
    		$receivable = $this->table('receivable')->field([
    			'sum(receivable_money) as sum_receivable_money','receivable_currency_id'
    		])->where($where)->group('receivable_currency_id')->select();
    		
    		$order_amount = 0;
    		//汇率
    		$eYearMonth = $params['years'].'-'.$params['month'].'-01';
    		$proportion_time = date('Ymd',strtotime("{$eYearMonth} +1 month -1 day")) ;
    		foreach ($receivable as $key => $value) {
    			$currency_proportion = $this->get_currency_proportion($value['receivable_currency_id'],$currency_id,$proportion_time);
	    		$order_amount += $value['sum_receivable_money']*$currency_proportion;
	    	}
	    	$return['order_amount'] = $order_amount; 

	    	//主营业务收入 select SUM(receivable_info.payment_money) as sum_payment_money,receivable_info.payment_currency_id from receivable_info LEFT JOIN  receivable ON receivable_info.receivable_number=receivable.receivable_number  where  receivable.company_id=13 and receivable.status=1  and receivable_info.create_time>=1546272000 and receivable_info.create_time<1548950400 GROUP BY receivable_info.payment_currency_id 
/***
	    	$where = "receivable.company_id={$params['company_id']} and receivable.status=1 and receivable_info.status=1 and receivable_info.create_time>={$sTime} and receivable_info.create_time<{$eTime}";
	    	$receivable_info = $this->table('receivable_info')->join('receivable','receivable_info.receivable_number=receivable.receivable_number','left')->field(['SUM(receivable_info.payment_money) as sum_payment_money','receivable_info.payment_currency_id'
	    	])->where($where)->group('receivable_info.payment_currency_id')->select();
	    	$main_operating_income = 0;
 			foreach ($receivable_info as $key => $value) {
 				$currency_proportion = $this->get_currency_proportion($value['payment_currency_id'],$currency_id,$proportion_time);	
 				$main_operating_income += $value['sum_payment_money']*$currency_proportion;	 
 			}
 			$return['main_operating_income'] = $main_operating_income;
***/

 			//主营业务成本 select sum(cope_info.receivable_money),cope_info.receivable_currency_id  from cope_info LEFT JOIN cope on cope.cope_number=cope_info.cope_number  where cope.status=1 and cope_info.status=1 and cope.company_id=13 and cope_info.create_time>=1546272000 and cope_info.create_time<1548950400  GROUP BY cope_info.receivable_currency_id
/*** 			
 			$where = "cope.status=1 and cope_info.status=1 and cope.company_id={$params['company_id']} and cope_info.create_time>={$sTime} and cope_info.create_time<{$eTime}";
 			$cope_info = $this->table('cope_info')->join('cope','cope.cope_number=cope_info.cope_number','left')->field([
 				'sum(cope_info.receivable_money) as sum_receivable_money','cope_info.receivable_currency_id'])->where($where)->group('cope_info.receivable_currency_id')->select();
 			$main_business_cost = 0;
 			foreach ($cope_info as $key => $value) {
 				 $currency_proportion = $this->get_currency_proportion($value['receivable_currency_id'],$currency_id,$proportion_time);
 				 $main_business_cost += $value['sum_receivable_money']*$currency_proportion;	
 			}
 			$return['main_business_cost'] = $main_business_cost;
**/
    	}

    	return $return;	
    }

    /**
    * 获取货币汇率
    currency_id 原货币
    opposite_currency_id 需换算的货币
    proportion_time 时间区间
    */
    public function get_currency_proportion($currency_id,$opposite_currency_id,$proportion_time){
    	$w['proportion_time'] = $proportion_time;
	   	$w['currency_id'] = $currency_id;
 		$w['opposite_currency_id'] = $opposite_currency_id;
 		$w['status'] = 1;
	    $currency_proportion = $this->table('currency_proportion')->field(['currency_proportion'])->where($w)->find()['currency_proportion'];
	    if(empty($currency_proportion)){
	    	unset($w['proportion_time']);
	    	$w['proportion_time'] = ['<',$proportion_time];	
	    	$currency_proportion = $this->table('currency_proportion')->field(['currency_proportion'])->where($w)->order("proportion_time desc")->find()['currency_proportion']?:1;
	    }

	    return $currency_proportion; 
    }

    /**
	*  添加编辑利润表
    **/
    public function editProfitStatementAjax($params){
    	$data['update_time'] = time();
    	$data['update_user_id'] = $params['user_id'];
    	
    	$data['company_id'] = $params['company_id']; 
    	$data['years'] = $params['years']; 
    	$data['month'] = $params['month']; 
    	$data['number_of_staff'] = $params['number_of_staff']; 
    	$data['number_of_guests_received'] = $params['number_of_guests_received']; 
    	$data['order_amount'] = $params['order_amount']; 
    	$data['main_operating_income'] = $params['main_operating_income']; 
    	$data['external_income'] = $params['external_income']; 
    	$data['internal_settlement_income'] = $params['internal_settlement_income']; 
    	$data['main_business_cost'] = $params['main_business_cost']; 
    	$data['external_cost'] = $params['external_cost']; 
    	$data['internal_settlement_cost'] = $params['internal_settlement_cost']; 
    	$data['gross_profit'] = $params['gross_profit']; 
    	$data['ratio_of_margin'] = $params['ratio_of_margin']; 
    	$data['operating_taxes_and_attachments'] = $params['operating_taxes_and_attachments']; 
    	$data['selling_expenses'] = $params['selling_expenses']; 
    	$data['commission'] = $params['commission']; 
    	$data['other'] = $params['other']; 
    	$data['overhead_expenses'] = $params['overhead_expenses']; 
    	$data['salary'] = $params['salary']; 
    	$data['chummage'] = $params['chummage']; 
    	$data['hydroelectricity'] = $params['hydroelectricity']; 
    	$data['handle_official_business'] = $params['handle_official_business']; 
    	$data['cost_of_financing'] = $params['cost_of_financing']; 
    	$data['interest'] = $params['interest']; 
    	$data['exchange_gain_or_loss'] = $params['exchange_gain_or_loss']; 
    	$data['poundage'] = $params['poundage']; 
    	$data['nonbusiness_income'] = $params['nonbusiness_income']; 
    	$data['non_business_expenditure'] = $params['non_business_expenditure']; 
    	$data['total_profit'] = $params['total_profit']; 
    	$data['income_tax'] = $params['income_tax']; 
    	$data['net_margin'] = $params['net_margin']; 
    	$data['is_predict'] = $params['is_predict']; 
    	$data['status'] = 1; 

    	try{
    		if($params['profit_statement_id']){
    			$where['profit_statement_id'] =  $params['profit_statement_id'];
    			return DB::table('profit_statement')->where($where)->update($data);		
	    	}else{
                $data['create_user_id'] = $params['user_id'];
        		$data['create_time'] = time();	
	    		Db::table('profit_statement')->insert($data);
				$profit_statement_id = Db::name('profit_statement')->getLastInsID(); 
				return $profit_statement_id;
	    	}
    	} catch (\Exception $e) {
    		return $result = $e->getMessage(); 
    	}
    	
    }

    /**
    * 分公司利润累计
    **/
    public function accumulatedProfitsOfBranchCompanies($params){
    	
    	try{
	    	$where['years'] = $params['years'];
	    	$where['month'] = ['<=',$params['month']];
	    	$where['company_id'] = $params['company_id'];
	    	$where['status'] = 1;
	    	$where['is_predict'] = 2;
	    	$return = $this->table('profit_statement')->field([
	    		"number_of_staff",
	    		"sum(number_of_guests_received) as number_of_guests_received",
	    		"sum(order_amount) as order_amount",
	    		"sum(main_operating_income) as main_operating_income",
	    		"sum(external_income) as external_income",
	    		"sum(internal_settlement_income) as internal_settlement_income",
	    		"sum(main_business_cost) as main_business_cost",
	    		"sum(external_cost) as external_cost",
	    		"sum(internal_settlement_cost) as internal_settlement_cost",
	    		"gross_profit",
	    		"ratio_of_margin",
	    		"sum(operating_taxes_and_attachments) as operating_taxes_and_attachments",
	    		"sum(selling_expenses) as selling_expenses",
	    		"sum(commission) as commission",
	    		"sum(other) as other",
	    		"sum(overhead_expenses) as overhead_expenses",
	    		"sum(salary) as salary",
	    		"sum(chummage) as chummage",
	    		"sum(hydroelectricity) as hydroelectricity",
	    		"sum(handle_official_business) as handle_official_business", 
	    		"sum(cost_of_financing) as cost_of_financing",
	    		"sum(interest) as interest",
	    		"sum(exchange_gain_or_loss) as exchange_gain_or_loss",
	    		"sum(poundage) as poundage",
	    		"sum(nonbusiness_income) as nonbusiness_income",
	    		"sum(non_business_expenditure) as non_business_expenditure",
	    		"sum(total_profit) as total_profit",
	    		"sum(income_tax) as income_tax",
	    		"sum(net_margin) as net_margin",
	    		"(select company.company_name from company where company.company_id=profit_statement.company_id) as company_name", 
	    		"company_id",
	    	])->where($where)->find(); 

            $wt['years'] = $params['years'];
            $wt['month'] = $params['month'];
            $wt['company_id'] = $params['company_id'];
            $wt['status'] = 1;
            $wt['is_predict'] = 2;
            $number_of_staff = $this->table('profit_statement')->field(['number_of_staff'])->where($wt)->find();
            if($number_of_staff){
                 $return['number_of_staff'] =  $number_of_staff['number_of_staff'];  
            }else{
               $m = ltrim($params['month'],0)-1;
               if($m<10){
                 $m = '0'.$m;
               }
               $number_of_staff = $this->table('profit_statement')->field(['number_of_staff'])->where($wt)->find();
               $return['number_of_staff'] =  $number_of_staff['number_of_staff'];  
            }    

 
    		$year_month =  $params['years'].'-'.$params['month'];
    		$s_year_month = $params['years'].'-01';

    		//获取分公司货币
    		$where = "company_id={$params['company_id']}";
    		$currency_id = $this->table('company')->field(['currency_id'])->where($where)->find()['currency_id']; 

    		$sTime = strtotime($s_year_month);
    		$eTime = strtotime('+1 months',strtotime($year_month)); 

    		//收客 select company_order_customer_lineup.* from company_order_customer_lineup  LEFT JOIN company_order  on company_order_customer_lineup.company_order_number=company_order.company_order_number where company_order.company_id=13 and company_order.`status`=1 and company_order_customer_lineup.`status`=1 and company_order.create_time>=1546272000 and company_order.create_time<1548950400
    		$where = "company_order.company_id={$params['company_id']} and company_order_customer_lineup.lineup_type=1 and company_order.status=1 and company_order_customer_lineup.status=1";
    		$where .= " and company_order.create_time>={$sTime} and company_order.create_time<{$eTime}"; //按订单日期计算

    		$return['number_of_guests_received'] = $this->table('company_order_customer_lineup')->join('company_order','company_order_customer_lineup.company_order_number=company_order.company_order_number','left')
    		->field(['count(company_order_customer_lineup.company_order_customer_lineup_id) as coun'])->where($where)->find()['coun'];

    		//订单金额 select sum(receivable_money),receivable_currency_id from receivable where company_id=13 and `status`=1 and create_time>=1546272000 and create_time<1548950400 GROUP BY receivable_currency_id;
    		$order_amount = 0;
    		$params_month = ltrim($params['month'],0);
    		for($i=1;$i<=$params_month;$i++){
    			$ii = $i;
    			if($i<10){
    				$ii = '0'.$i;	
    			}
    			$year_month = $params['years'].'-'.$ii;
    			$sTime = strtotime($year_month);
    			$eTime = strtotime('+1 months',strtotime($year_month)); 	
    			$where = "company_id={$params['company_id']} and status=1 and create_time>={$sTime} and create_time<{$eTime}";
	    		$receivable = $this->table('receivable')->field([
	    			'sum(receivable_money) as sum_receivable_money','receivable_currency_id'
	    		])->where($where)->group('receivable_currency_id')->select();
	    		
	    		
	    		//汇率
	    		$eYearMonth = $params['years'].'-'.$ii.'-01';
	    		$proportion_time = date('Ymd',strtotime("{$eYearMonth} +1 month -1 day")) ;
	    		foreach ($receivable as $key => $value) {
	    			$currency_proportion = $this->get_currency_proportion($value['receivable_currency_id'],$currency_id,$proportion_time);
		    		$order_amount += $value['sum_receivable_money']*$currency_proportion;
		    	}
		    		
    		}
    		$return['order_amount'] = $order_amount; 

    		$return['gross_profit'] = sprintf("%.2f",$return['main_operating_income']-$return['main_business_cost']);
    		$return['ratio_of_margin'] = sprintf("%.4f",$return['main_business_cost']/$return['main_operating_income']);
    		
		} catch (\Exception $e) {
    		return $result = $e->getMessage(); 
    	}
	    return $return;

    }




}