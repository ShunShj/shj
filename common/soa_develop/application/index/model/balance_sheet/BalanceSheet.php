<?php
namespace app\index\model\balance_sheet;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class BalanceSheet extends Model{ 
	private $_languageList;

    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize(); 
    }

    /** 
    * 晔
    * 获取资产负债表(带汇率转换) 
    **/
    public function selBalanceSheetCurrencyConversion($params){
        $where['years'] = $params['years'];
        $where['month'] = $params['month'];
        $where['company_id'] = $params['company_id'];
        $where['status'] = $params['status'];

        $return = $this->table('balance_sheet')->field([
            '*',
            '(select company.company_name from company where company.company_id=balance_sheet.company_id) as company_name',
            "(select user.nickname from user where user.user_id=balance_sheet.create_user_id) as create_nickname",
            "(select user.nickname from user where user.user_id=balance_sheet.update_user_id) as update_nickname",
            "(select company.currency_id from company where company.company_id=balance_sheet.company_id) as currency_id",
        ])->where($where)->find();

        //汇率
        $eYearMonth = $params['years'].'-'.$params['month'].'-01';
        $proportion_time = date('Ymd',strtotime("{$eYearMonth} +1 month -1 day")) ;

        $currency_proportion = $this->get_currency_proportion($params['company_currency_id'],$params['change_currency_id'],$proportion_time);


       $return['monetary_resources'] = round($return['monetary_resources']*$currency_proportion,2);
        $return['short_term_investment'] = round($return['short_term_investment']*$currency_proportion,2);
        $return['dividends_receivable'] = round($return['dividends_receivable']*$currency_proportion,2);
        $return['accounts_receivable'] = round($return['accounts_receivable']*$currency_proportion,2);
        $return['other_receivables'] = round($return['other_receivables']*$currency_proportion,2);
        $return['advance_payment'] = round($return['advance_payment']*$currency_proportion,2);
        $return['deferred_expenses'] = round($return['deferred_expenses']*$currency_proportion,2);
        $return['net_loss_of_current_assets_to_be_processed'] = round($return['net_loss_of_current_assets_to_be_processed']*$currency_proportion,2);
        $return['investments_in_longterm_bonds_maturing_in_one_year'] = round($return['investments_in_longterm_bonds_maturing_in_one_year']*$currency_proportion,2);
        $return['other_current_assets'] = round($return['other_current_assets']*$currency_proportion,2);
        $return['total_current_assets'] = round($return['total_current_assets']*$currency_proportion,2);
        $return['long_term_equity_investment'] = round($return['long_term_equity_investment']*$currency_proportion,2);
        $return['long_term_debt_investment'] = round($return['long_term_debt_investment']*$currency_proportion,2);
        $return['long_term_investment_impairment_provision'] = round($return['long_term_investment_impairment_provision']*$currency_proportion,2);
        $return['net_long_term_investment'] = round($return['net_long_term_investment']*$currency_proportion,2);
        $return['original_value_of_fixed_assets'] = round($return['original_value_of_fixed_assets']*$currency_proportion,2);
        $return['accumulated_depreciation'] = round($return['accumulated_depreciation']*$currency_proportion,2);
        $return['fixed_assetsnet_value'] = round($return['fixed_assetsnet_value']*$currency_proportion,2);
        $return['Provision_for_impairment_of_fixed_assets'] = round($return['Provision_for_impairment_of_fixed_assets']*$currency_proportion,2);
        $return['net_fixed_assets'] = round($return['net_fixed_assets']*$currency_proportion,2);
        $return['immaterial_assets'] = round($return['immaterial_assets']*$currency_proportion,2);
        $return['provision_for_impairment_of_intangible_assets'] = round($return['provision_for_impairment_of_intangible_assets']*$currency_proportion,2);
        $return['net_intangible_assets'] = round($return['net_intangible_assets']*$currency_proportion,2);
        $return['total_noncurrent_assets'] = round($return['total_noncurrent_assets']*$currency_proportion,2);
        $return['total_assets'] = round($return['total_assets']*$currency_proportion,2);
        $return['overnight_money'] = round($return['overnight_money']*$currency_proportion,2);
        $return['payable_in_account'] = round($return['payable_in_account']*$currency_proportion,2);
        $return['deposit_received'] = round($return['deposit_received']*$currency_proportion,2);
        $return['pay_payable_to_staff_and_workers'] = round($return['pay_payable_to_staff_and_workers']*$currency_proportion,2);
        $return['dividends_payable'] = round($return['dividends_payable']*$currency_proportion,2);
        $return['due_taxes_and_fees'] = round($return['due_taxes_and_fees']*$currency_proportion,2);
        $return['other_accounts_payable'] = round($return['other_accounts_payable']*$currency_proportion,2);
        $return['accounts_payable_others'] = round($return['accounts_payable_others']*$currency_proportion,2);
        $return['longterm_liabilities_due_within_one_year'] = round($return['longterm_liabilities_due_within_one_year']*$currency_proportion,2);
        $return['other_current_liabilities'] = round($return['other_current_liabilities']*$currency_proportion,2);
        $return['total_current_liabilities'] = round($return['total_current_liabilities']*$currency_proportion,2);
        $return['money_borrowed_for_long_term'] = round($return['money_borrowed_for_long_term']*$currency_proportion,2);
        $return['bond_payable'] = round($return['bond_payable']*$currency_proportion,2);
        $return['long_term_accounts_payable'] = round($return['long_term_accounts_payable']*$currency_proportion,2);
        $return['account_payable_special_funds'] = round($return['account_payable_special_funds']*$currency_proportion,2);
        $return['other_long_term_liabilities'] = round($return['other_long_term_liabilities']*$currency_proportion,2);
        $return['total_long_term_liabilities'] = round($return['total_long_term_liabilities']*$currency_proportion,2);
        $return['total_liabilities'] = round($return['total_liabilities']*$currency_proportion,2);
        $return['paid_in_capital_or_share_capital'] = round($return['paid_in_capital_or_share_capital']*$currency_proportion,2);
        $return['capital_reserve'] = round($return['capital_reserve']*$currency_proportion,2);
        $return['earned_surplus'] = round($return['earned_surplus']*$currency_proportion,2);
        $return['undistributed_profit'] = round($return['undistributed_profit']*$currency_proportion,2);
        $return['foreign_currency_statement_translation_difference'] = round($return['foreign_currency_statement_translation_difference']*$currency_proportion,2);
        $return['total_owners_equity_or_shareholders_equity'] = round($return['total_owners_equity_or_shareholders_equity']*$currency_proportion,2);
        $return['total_liabilities_and_owners_equity_or_shareholders_equity'] = round($return['total_liabilities_and_owners_equity_or_shareholders_equity']*$currency_proportion,2);
       
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

    /*
    * 晔
    * 获取资产负债表
    */
    public function selBalanceSheet($params){
        $where['years'] = $params['years'];
        $where['month'] = $params['month'];
        $where['company_id'] = $params['company_id'];
        $where['status'] = $params['status'];

        $return = $this->table('balance_sheet')->field([
            '*',
            '(select company.company_name from company where company.company_id=balance_sheet.company_id) as company_name',
            "(select user.nickname from user where user.user_id=balance_sheet.create_user_id) as create_nickname",
            "(select user.nickname from user where user.user_id=balance_sheet.update_user_id) as update_nickname",
            "(select company.currency_id from company where company.company_id=balance_sheet.company_id) as currency_id",
        ])->where($where)->find();

        return $return;

    }



    /**
    * 晔
    * 添加、编辑资产负债表
    */
    public function editBalanceSheet($params){
         
    	$data['update_time'] = time(); 
    	$data['update_user_id'] = $params['user_id'];

    	$data['company_id'] = $params['company_id']; 
    	$data['years'] = $params['years']; 
    	$data['month'] = $params['month']; 

    	$data['monetary_resources'] = $params['monetary_resources']?:0;
    	$data['short_term_investment'] = $params['short_term_investment']?:0;
    	$data['dividends_receivable'] = $params['dividends_receivable']?:0;
    	$data['accounts_receivable'] = $params['accounts_receivable']?:0;
    	$data['other_receivables'] = $params['other_receivables']?:0;
    	$data['advance_payment'] = $params['advance_payment']?:0;
    	$data['deferred_expenses'] = $params['deferred_expenses']?:0;
    	$data['net_loss_of_current_assets_to_be_processed'] = $params['net_loss_of_current_assets_to_be_processed']?:0;
    	$data['investments_in_longterm_bonds_maturing_in_one_year'] = $params['investments_in_longterm_bonds_maturing_in_one_year']?:0;
    	$data['other_current_assets'] = $params['other_current_assets']?:0;
    	$data['total_current_assets'] = $params['total_current_assets']?:0;
    	$data['long_term_equity_investment'] = $params['long_term_equity_investment']?:0;
    	$data['long_term_debt_investment'] = $params['long_term_debt_investment']?:0;
    	$data['long_term_investment_impairment_provision'] = $params['long_term_investment_impairment_provision']?:0;
    	$data['net_long_term_investment'] = $params['net_long_term_investment']?:0;
    	$data['original_value_of_fixed_assets'] = $params['original_value_of_fixed_assets']?:0;
    	$data['accumulated_depreciation'] = $params['accumulated_depreciation']?:0;
    	$data['fixed_assetsnet_value'] = $params['fixed_assetsnet_value']?:0;
    	$data['Provision_for_impairment_of_fixed_assets'] = $params['Provision_for_impairment_of_fixed_assets']?:0;
    	$data['net_fixed_assets'] = $params['net_fixed_assets']?:0;
    	$data['immaterial_assets'] = $params['immaterial_assets']?:0;
    	$data['provision_for_impairment_of_intangible_assets'] = $params['provision_for_impairment_of_intangible_assets']?:0;
    	$data['net_intangible_assets'] = $params['net_intangible_assets']?:0;
    	$data['total_noncurrent_assets'] = $params['total_noncurrent_assets']?:0;
    	$data['total_assets'] = $params['total_assets']?:0;
    	$data['overnight_money'] = $params['overnight_money']?:0;
    	$data['payable_in_account'] = $params['payable_in_account']?:0;
    	$data['deposit_received'] = $params['deposit_received']?:0;
    	$data['pay_payable_to_staff_and_workers'] = $params['pay_payable_to_staff_and_workers']?:0;
    	$data['dividends_payable'] = $params['dividends_payable']?:0;
    	$data['due_taxes_and_fees'] = $params['due_taxes_and_fees']?:0;
    	$data['other_accounts_payable'] = $params['other_accounts_payable']?:0;
    	$data['accounts_payable_others'] = $params['accounts_payable_others']?:0;
    	$data['longterm_liabilities_due_within_one_year'] = $params['longterm_liabilities_due_within_one_year']?:0;
    	$data['other_current_liabilities'] = $params['other_current_liabilities']?:0;
    	$data['total_current_liabilities'] = $params['total_current_liabilities']?:0;
    	$data['money_borrowed_for_long_term'] = $params['money_borrowed_for_long_term']?:0;
    	$data['bond_payable'] = $params['bond_payable']?:0;
    	$data['long_term_accounts_payable'] = $params['long_term_accounts_payable']?:0;
    	$data['account_payable_special_funds'] = $params['account_payable_special_funds']?:0;
    	$data['other_long_term_liabilities'] = $params['other_long_term_liabilities']?:0;
    	$data['total_long_term_liabilities'] = $params['total_long_term_liabilities']?:0;
    	$data['total_liabilities'] = $params['total_liabilities']?:0;
    	$data['paid_in_capital_or_share_capital'] = $params['paid_in_capital_or_share_capital']?:0;
    	$data['capital_reserve'] = $params['capital_reserve']?:0;
    	$data['earned_surplus'] = $params['earned_surplus']?:0;
    	$data['undistributed_profit'] = $params['undistributed_profit']?:0;
    	$data['foreign_currency_statement_translation_difference'] = $params['foreign_currency_statement_translation_difference']?:0;
    	$data['total_owners_equity_or_shareholders_equity'] = $params['total_owners_equity_or_shareholders_equity']?:0;
    	$data['total_liabilities_and_owners_equity_or_shareholders_equity'] = $params['total_liabilities_and_owners_equity_or_shareholders_equity']?:0;
    	$data['status'] = 1;


    	try{
    		if($params['balance_sheet_id']){
    			$where['balance_sheet_id'] =  $params['balance_sheet_id'];
    			DB::table('balance_sheet')->where($where)->update($data);		
    			return 1;
	    	}else{
	    		$data['create_user_id'] = $params['user_id'];
	    		$data['create_time'] = time();	
	    		Db::table('balance_sheet')->insert($data);
				$balance_sheet_id = Db::name('balance_sheet')->getLastInsID(); 
				return $balance_sheet_id;
	    	}
    	} catch (\Exception $e) {
    		return $result = $e->getMessage(); 
    	}
    }	


}