<?php
namespace app\index\controller;
use app\common\help\Help;
use app\index\controller\Base;
use think\Cookie;
use think\helper\Arr;
use think\Request;
use think\Session;
use Underscore\Types\Arrays;

//财务管理
class Finance extends Base
{

    public  $types = ['a'=>'请选择',1=>'团队产品',2=>'酒店',3=>'用餐',4=>'航班',5=>'邮轮',6=>'签证',7=>'景点',8=>'车辆',9=>'导游',10=>'单项资源',11=>'自费项目',12=>'其他'];


    /**
    **  资产负债 晔
     */
    public function balanceSheet(){
//        var_dump(session('user'));exit;
        if($_GET['year_month']){
            $year_month = $_GET['year_month'];
        }else{
            $year_month = date('Y-m');
        }
        $this->assign('year_month',$year_month);
        //获取货币
        $where['currency_id'] = session('user')['company_currency_id'];
        $where['status'] = 1;
        $getCurrency = $this->callSoaErp('post','/system/getCurrency',$where);
        $this->assign('Currency',$getCurrency['data']);
        unset($where);
//        var_dump(session('user'));exit;

        //获取资产负债
        if(session('user')['company_id']==1){ //集团
            //获取所有公司
            $where['status'] = 1;
            $getCompany = $this->callSoaErp('post','/system/getCompany',$where);
            $getCompany['data'] = Arrays::sort($getCompany['data'],'company_id');
            $this->assign('getCompany',$getCompany['data']);

            $years = explode('-',$year_month)[0];
            $month = explode('-',$year_month)[1];
            foreach($getCompany['data'] as $vl){
                if(!empty($_GET['company_id']) && $_GET['company_id'] != $vl['company_id']){
                    continue;
                }
                //每家公司的数据
                $where['choose_company_id'] = $vl['company_id'];
                $where['years'] = $years;
                $where['month'] = $month;
                $where['company_currency_id'] = $vl['currency_id'];
                $where['change_currency_id'] = session('user')['company_currency_id'];
                $where['status'] = 1;
                $return[$vl['company_id']] = $this->callSoaErp('post','/balance_sheet/selBalanceSheetCurrencyConversion',$where)['data'];
            }

            //集团汇总
            if(empty($_GET['company_id'])){
                foreach($return as $k=>$v){
                    if($k==1){
                        continue;
                    }
                    $return[1]['monetary_resources'] += $v['monetary_resources'];
                    $return[1]['short_term_investment'] += $v['short_term_investment'];
                    $return[1]['dividends_receivable'] += $v['dividends_receivable'];
                    $return[1]['accounts_receivable'] += $v['accounts_receivable'];
                    $return[1]['other_receivables'] += $v['other_receivables'];
                    $return[1]['advance_payment'] += $v['advance_payment'];
                    $return[1]['deferred_expenses'] += $v['deferred_expenses'];
                    $return[1]['net_loss_of_current_assets_to_be_processed'] += $v['net_loss_of_current_assets_to_be_processed'];
                    $return[1]['investments_in_longterm_bonds_maturing_in_one_year'] += $v['investments_in_longterm_bonds_maturing_in_one_year'];
                    $return[1]['other_current_assets'] += $v['other_current_assets'];
                    $return[1]['total_current_assets'] += $v['total_current_assets'];
                    $return[1]['long_term_equity_investment'] += $v['long_term_equity_investment'];
                    $return[1]['long_term_debt_investment'] += $v['long_term_debt_investment'];
                    $return[1]['long_term_investment_impairment_provision'] += $v['long_term_investment_impairment_provision'];
                    $return[1]['net_long_term_investment'] += $v['net_long_term_investment'];
                    $return[1]['original_value_of_fixed_assets'] += $v['original_value_of_fixed_assets'];
                    $return[1]['accumulated_depreciation'] += $v['accumulated_depreciation'];
                    $return[1]['fixed_assetsnet_value'] += $v['fixed_assetsnet_value'];
                    $return[1]['Provision_for_impairment_of_fixed_assets'] += $v['Provision_for_impairment_of_fixed_assets'];
                    $return[1]['net_fixed_assets'] += $v['net_fixed_assets'];
                    $return[1]['immaterial_assets'] += $v['immaterial_assets'];
                    $return[1]['provision_for_impairment_of_intangible_assets'] += $v['provision_for_impairment_of_intangible_assets'];
                    $return[1]['net_intangible_assets'] += $v['net_intangible_assets'];
                    $return[1]['total_noncurrent_assets'] += $v['total_noncurrent_assets'];
                    $return[1]['total_assets'] += $v['total_assets'];
                    $return[1]['overnight_money'] += $v['overnight_money'];
                    $return[1]['payable_in_account'] += $v['payable_in_account'];
                    $return[1]['deposit_received'] += $v['deposit_received'];
                    $return[1]['pay_payable_to_staff_and_workers'] += $v['pay_payable_to_staff_and_workers'];
                    $return[1]['dividends_payable'] += $v['dividends_payable'];
                    $return[1]['due_taxes_and_fees'] += $v['due_taxes_and_fees'];
                    $return[1]['other_accounts_payable'] += $v['other_accounts_payable'];
                    $return[1]['accounts_payable_others'] += $v['accounts_payable_others'];
                    $return[1]['longterm_liabilities_due_within_one_year'] += $v['longterm_liabilities_due_within_one_year'];
                    $return[1]['other_current_liabilities'] += $v['other_current_liabilities'];
                    $return[1]['total_current_liabilities'] += $v['total_current_liabilities'];
                    $return[1]['money_borrowed_for_long_term'] += $v['money_borrowed_for_long_term'];
                    $return[1]['bond_payable'] += $v['bond_payable'];
                    $return[1]['long_term_accounts_payable'] += $v['long_term_accounts_payable'];
                    $return[1]['account_payable_special_funds'] += $v['account_payable_special_funds'];
                    $return[1]['other_long_term_liabilities'] += $v['other_long_term_liabilities'];
                    $return[1]['total_long_term_liabilities'] += $v['total_long_term_liabilities'];
                    $return[1]['total_liabilities'] += $v['total_liabilities'];
                    $return[1]['paid_in_capital_or_share_capital'] += $v['paid_in_capital_or_share_capital'];
                    $return[1]['capital_reserve'] += $v['capital_reserve'];
                    $return[1]['earned_surplus'] += $v['earned_surplus'];
                    $return[1]['undistributed_profit'] += $v['undistributed_profit'];
                    $return[1]['foreign_currency_statement_translation_difference'] += $v['foreign_currency_statement_translation_difference'];
                    $return[1]['total_owners_equity_or_shareholders_equity'] += $v['total_owners_equity_or_shareholders_equity'];
                    $return[1]['total_liabilities_and_owners_equity_or_shareholders_equity'] += $v['total_liabilities_and_owners_equity_or_shareholders_equity'];
                }

            }
            $this->assign('balanceSheet',$return);

            $companyList = Arrays::group($getCompany['data'],'company_id');
            $this->assign('companyList',$companyList);
            return $this->fetch('group_balance_sheet');
//            echo '<pre>';print_r($return);exit;

        }else{
            $where['years'] = explode('-',$year_month)[0];
            $where['month'] = explode('-',$year_month)[1];
            $where['company_id'] = session('user')['company_id'];
            $where['status'] = 1;

            $balanceSheetList = $this->callSoaErp('post','/balance_sheet/selBalanceSheet',$where);
            $this->assign('balanceSheetList',$balanceSheetList['data']);

            //rmb
            $where['company_currency_id'] = session('user')['company_currency_id'];
            $where['change_currency_id'] = 1;
            $balanceSheetCurrencyConversion = $this->callSoaErp('post','/balance_sheet/selBalanceSheetCurrencyConversion',$where);
            $this->assign('balanceSheetCurrencyConversion',$balanceSheetCurrencyConversion['data']);

//            echo '<pre>';print_r($balanceSheetCurrencyConversion);exit;

            return $this->fetch('balance_sheet');
        }



    }

    /*
     * 编辑资产负债表
     * **/
    public function editBalanceSheet(){
        $year_month = input('get.year_month');

        //获取货币
        $where['currency_id'] = session('user')['company_currency_id'];
        $where['status'] = 1;
        $getCurrency = $this->callSoaErp('post','/system/getCurrency',$where);
        $this->assign('Currency',$getCurrency['data']);

        $where['years'] = explode('-',$year_month)[0];
        $where['month'] = explode('-',$year_month)[1];
        $where['company_id'] = session('user')['company_id'];
        $where['status'] = 1;

        $balanceSheetList = $this->callSoaErp('post','/balance_sheet/selBalanceSheet',$where);
        $this->assign('balanceSheetList',$balanceSheetList['data']);

        return $this->fetch('edit_balance_sheet');
    }

    /**
    * 异步添加编辑资产负债表
     */
    public function editBalanceSheetAjax(){
        $param = Request::instance()->param();

        $year_month = Arrays::get($param,'year_month');
        $d['years'] =  explode('-',$year_month)[0];
        $d['month'] =  explode('-',$year_month)[1];
        $d['company_id'] = session('user')['company_id'];
        $d['balance_sheet_id'] = Arrays::get($param,'balance_sheet_id')?:0;
        $d['monetary_resources'] = Arrays::get($param,'monetary_resources')?:0;
        $d['short_term_investment'] = Arrays::get($param,'short_term_investment')?:0;
        $d['dividends_receivable'] = Arrays::get($param,'dividends_receivable')?:0;
        $d['accounts_receivable'] = Arrays::get($param,'accounts_receivable')?:0;
        $d['other_receivables'] = Arrays::get($param,'other_receivables')?:0;
        $d['advance_payment'] = Arrays::get($param,'advance_payment')?:0;
        $d['deferred_expenses'] = Arrays::get($param,'deferred_expenses')?:0;
        $d['net_loss_of_current_assets_to_be_processed'] = Arrays::get($param,'net_loss_of_current_assets_to_be_processed')?:0;
        $d['investments_in_longterm_bonds_maturing_in_one_year'] = Arrays::get($param,'investments_in_longterm_bonds_maturing_in_one_year')?:0;
        $d['other_current_assets'] = Arrays::get($param,'other_current_assets')?:0;
        $d['total_current_assets'] = Arrays::get($param,'total_current_assets')?:0;
        $d['long_term_equity_investment'] = Arrays::get($param,'long_term_equity_investment')?:0;
        $d['long_term_debt_investment'] = Arrays::get($param,'long_term_debt_investment')?:0;
        $d['long_term_investment_impairment_provision'] = Arrays::get($param,'long_term_investment_impairment_provision')?:0;
        $d['net_long_term_investment'] = Arrays::get($param,'net_long_term_investment')?:0;
        $d['original_value_of_fixed_assets'] = Arrays::get($param,'original_value_of_fixed_assets')?:0;
        $d['accumulated_depreciation'] = Arrays::get($param,'accumulated_depreciation')?:0;
        $d['fixed_assetsnet_value'] = Arrays::get($param,'fixed_assetsnet_value')?:0;
        $d['Provision_for_impairment_of_fixed_assets'] = Arrays::get($param,'Provision_for_impairment_of_fixed_assets')?:0;
        $d['net_fixed_assets'] = Arrays::get($param,'net_fixed_assets')?:0;
        $d['immaterial_assets'] = Arrays::get($param,'immaterial_assets')?:0;
        $d['provision_for_impairment_of_intangible_assets'] = Arrays::get($param,'provision_for_impairment_of_intangible_assets')?:0;
        $d['net_intangible_assets'] = Arrays::get($param,'net_intangible_assets')?:0;
        $d['total_noncurrent_assets'] = Arrays::get($param,'total_noncurrent_assets')?:0;
        $d['total_assets'] = Arrays::get($param,'total_assets')?:0;
        $d['overnight_money'] = Arrays::get($param,'overnight_money')?:0;
        $d['payable_in_account'] = Arrays::get($param,'payable_in_account')?:0;
        $d['deposit_received'] = Arrays::get($param,'deposit_received')?:0;
        $d['pay_payable_to_staff_and_workers'] = Arrays::get($param,'pay_payable_to_staff_and_workers')?:0;
        $d['dividends_payable'] = Arrays::get($param,'dividends_payable')?:0;
        $d['due_taxes_and_fees'] = Arrays::get($param,'due_taxes_and_fees')?:0;
        $d['other_accounts_payable'] = Arrays::get($param,'other_accounts_payable')?:0;
        $d['accounts_payable_others'] = Arrays::get($param,'accounts_payable_others')?:0;
        $d['longterm_liabilities_due_within_one_year'] = Arrays::get($param,'longterm_liabilities_due_within_one_year')?:0;
        $d['other_current_liabilities'] = Arrays::get($param,'other_current_liabilities')?:0;
        $d['total_current_liabilities'] = Arrays::get($param,'total_current_liabilities')?:0;
        $d['money_borrowed_for_long_term'] = Arrays::get($param,'money_borrowed_for_long_term')?:0;
        $d['bond_payable'] = Arrays::get($param,'bond_payable')?:0;
        $d['long_term_accounts_payable'] = Arrays::get($param,'long_term_accounts_payable')?:0;
        $d['account_payable_special_funds'] = Arrays::get($param,'account_payable_special_funds')?:0;
        $d['other_long_term_liabilities'] = Arrays::get($param,'other_long_term_liabilities')?:0;
        $d['total_long_term_liabilities'] = Arrays::get($param,'total_long_term_liabilities')?:0;
        $d['total_liabilities'] = Arrays::get($param,'total_liabilities')?:0;
        $d['paid_in_capital_or_share_capital'] = Arrays::get($param,'paid_in_capital_or_share_capital')?:0;
        $d['capital_reserve'] = Arrays::get($param,'capital_reserve')?:0;
        $d['earned_surplus'] = Arrays::get($param,'earned_surplus')?:0;
        $d['undistributed_profit'] = Arrays::get($param,'undistributed_profit')?:0;
        $d['foreign_currency_statement_translation_difference'] = Arrays::get($param,'foreign_currency_statement_translation_difference')?:0;
        $d['total_owners_equity_or_shareholders_equity'] = Arrays::get($param,'total_owners_equity_or_shareholders_equity')?:0;
        $d['total_liabilities_and_owners_equity_or_shareholders_equity'] = Arrays::get($param,'total_liabilities_and_owners_equity_or_shareholders_equity')?:0;
        $d['user_id'] = session('user')['user_id'];
        return $this->callSoaErp('post','/balance_sheet/editBalanceSheet',$d);


    }


    /***
     * 利润表 晔
     */
    public function profitStatement(){

//        var_dump(session('user'));exit;
        if($_GET['year_month']){
            $year_month = $_GET['year_month'];
        }else{
            $year_month = date('Y-m');
        }
        $this->assign('year_month',$year_month);
        $company_id = session('user')['company_id'];

        $ar = explode('-',$year_month);
        $year = $ar[0];
        $month = $ar[1];
        $this->assign('year',$year);
        $this->assign('month',$month);

        //获取货币
        $where['currency_id'] = session('user')['company_currency_id'];
        $where['status'] = 1;
        $getCurrency = $this->callSoaErp('post','/system/getCurrency',$where);
        $this->assign('Currency',$getCurrency['data']);
        unset($where);
        if($company_id==1){
            //获取所有公司
            $where['status'] = 1;
            $getCompany = $this->callSoaErp('post','/system/getCompany',$where);
            $getCompany['data'] = Arrays::sort($getCompany['data'],'company_id');
            $this->assign('getCompany',$getCompany['data']);
            foreach($getCompany['data'] as $vl){
                 if(!empty($_GET['company_id']) && $_GET['company_id'] != $vl['company_id']){
                    continue;
                }
                 //每家公司的数据
                $where['choose_company_id'] = $vl['company_id'];
                $where['years'] = $year;
                $where['month'] = $month;
                $where['company_currency_id'] = $vl['currency_id'];
                $where['change_currency_id'] = session('user')['company_currency_id'];
                $return[$vl['company_id']] = $this->callSoaErp('post','/profit_statement/getObtainBranchProfitDataWithCurrencyConversion',$where)['data'];
            }

            //集团汇总
            if(empty($_GET['company_id'])) {
                foreach ($return as $k => $v) {
                    if ($k == 1) {
                        continue;
                    }
                    $return[1]['budgetAmount']['number_of_staff'] += $v['budgetAmount']['number_of_staff'];
                    $return[1]['budgetAmount']['number_of_guests_received'] += $v['budgetAmount']['number_of_guests_received'];
                    $return[1]['budgetAmount']['order_amount'] += $v['budgetAmount']['order_amount'];
                    $return[1]['budgetAmount']['main_operating_income'] += $v['budgetAmount']['main_operating_income'];
                    $return[1]['budgetAmount']['external_income'] += $v['budgetAmount']['external_income'];
                    $return[1]['budgetAmount']['internal_settlement_income'] += $v['budgetAmount']['internal_settlement_income'];
                    $return[1]['budgetAmount']['main_business_cost'] += $v['budgetAmount']['main_business_cost'];
                    $return[1]['budgetAmount']['external_cost'] += $v['budgetAmount']['external_cost'];
                    $return[1]['budgetAmount']['internal_settlement_cost'] += $v['budgetAmount']['internal_settlement_cost'];
                    $return[1]['budgetAmount']['operating_taxes_and_attachments'] += $v['budgetAmount']['operating_taxes_and_attachments'];
                    $return[1]['budgetAmount']['selling_expenses'] += $v['budgetAmount']['selling_expenses'];
                    $return[1]['budgetAmount']['commission'] += $v['budgetAmount']['commission'];
                    $return[1]['budgetAmount']['other'] += $v['budgetAmount']['other'];
                    $return[1]['budgetAmount']['overhead_expenses'] += $v['budgetAmount']['overhead_expenses'];
                    $return[1]['budgetAmount']['salary'] += $v['budgetAmount']['salary'];
                    $return[1]['budgetAmount']['chummage'] += $v['budgetAmount']['chummage'];
                    $return[1]['budgetAmount']['hydroelectricity'] += $v['budgetAmount']['hydroelectricity'];
                    $return[1]['budgetAmount']['handle_official_business'] += $v['budgetAmount']['handle_official_business'];
                    $return[1]['budgetAmount']['cost_of_financing'] += $v['budgetAmount']['cost_of_financing'];
                    $return[1]['budgetAmount']['interest'] += $v['budgetAmount']['interest'];
                    $return[1]['budgetAmount']['exchange_gain_or_loss'] += $v['budgetAmount']['exchange_gain_or_loss'];
                    $return[1]['budgetAmount']['poundage'] += $v['budgetAmount']['poundage'];
                    $return[1]['budgetAmount']['nonbusiness_income'] += $v['budgetAmount']['nonbusiness_income'];
                    $return[1]['budgetAmount']['non_business_expenditure'] += $v['budgetAmount']['non_business_expenditure'];
                    $return[1]['budgetAmount']['total_profit'] += $v['budgetAmount']['total_profit'];
                    $return[1]['budgetAmount']['income_tax'] += $v['budgetAmount']['income_tax'];
                    $return[1]['budgetAmount']['net_margin'] += $v['budgetAmount']['net_margin'];

                    $return[1]['amountOfActualBalance']['number_of_staff'] += $v['amountOfActualBalance']['number_of_staff'];
                    $return[1]['amountOfActualBalance']['number_of_guests_received'] += $v['amountOfActualBalance']['number_of_guests_received'];
                    $return[1]['amountOfActualBalance']['order_amount'] += $v['amountOfActualBalance']['order_amount'];
                    $return[1]['amountOfActualBalance']['main_operating_income'] += $v['amountOfActualBalance']['main_operating_income'];
                    $return[1]['amountOfActualBalance']['external_income'] += $v['amountOfActualBalance']['external_income'];
                    $return[1]['amountOfActualBalance']['internal_settlement_income'] += $v['amountOfActualBalance']['internal_settlement_income'];
                    $return[1]['amountOfActualBalance']['main_business_cost'] += $v['amountOfActualBalance']['main_business_cost'];
                    $return[1]['amountOfActualBalance']['external_cost'] += $v['amountOfActualBalance']['external_cost'];
                    $return[1]['amountOfActualBalance']['internal_settlement_cost'] += $v['amountOfActualBalance']['internal_settlement_cost'];
                    $return[1]['amountOfActualBalance']['operating_taxes_and_attachments'] += $v['amountOfActualBalance']['operating_taxes_and_attachments'];
                    $return[1]['amountOfActualBalance']['selling_expenses'] += $v['amountOfActualBalance']['selling_expenses'];
                    $return[1]['amountOfActualBalance']['commission'] += $v['amountOfActualBalance']['commission'];
                    $return[1]['amountOfActualBalance']['other'] += $v['amountOfActualBalance']['other'];
                    $return[1]['amountOfActualBalance']['overhead_expenses'] += $v['amountOfActualBalance']['overhead_expenses'];
                    $return[1]['amountOfActualBalance']['salary'] += $v['amountOfActualBalance']['salary'];
                    $return[1]['amountOfActualBalance']['chummage'] += $v['amountOfActualBalance']['chummage'];
                    $return[1]['amountOfActualBalance']['hydroelectricity'] += $v['amountOfActualBalance']['hydroelectricity'];
                    $return[1]['amountOfActualBalance']['handle_official_business'] += $v['amountOfActualBalance']['handle_official_business'];
                    $return[1]['amountOfActualBalance']['cost_of_financing'] += $v['amountOfActualBalance']['cost_of_financing'];
                    $return[1]['amountOfActualBalance']['interest'] += $v['amountOfActualBalance']['interest'];
                    $return[1]['amountOfActualBalance']['exchange_gain_or_loss'] += $v['amountOfActualBalance']['exchange_gain_or_loss'];
                    $return[1]['amountOfActualBalance']['poundage'] += $v['amountOfActualBalance']['poundage'];
                    $return[1]['amountOfActualBalance']['nonbusiness_income'] += $v['amountOfActualBalance']['nonbusiness_income'];
                    $return[1]['amountOfActualBalance']['non_business_expenditure'] += $v['amountOfActualBalance']['non_business_expenditure'];
                    $return[1]['amountOfActualBalance']['total_profit'] += $v['amountOfActualBalance']['total_profit'];
                    $return[1]['amountOfActualBalance']['income_tax'] += $v['amountOfActualBalance']['income_tax'];
                    $return[1]['amountOfActualBalance']['net_margin'] += $v['amountOfActualBalance']['net_margin'];

                    $return[1]['accumulatedAmount']['number_of_staff'] += $v['accumulatedAmount']['number_of_staff'];
                    $return[1]['accumulatedAmount']['number_of_guests_received'] += $v['accumulatedAmount']['number_of_guests_received'];
                    $return[1]['accumulatedAmount']['order_amount'] += $v['accumulatedAmount']['order_amount'];
                    $return[1]['accumulatedAmount']['main_operating_income'] += $v['accumulatedAmount']['main_operating_income'];
                    $return[1]['accumulatedAmount']['external_income'] += $v['accumulatedAmount']['external_income'];
                    $return[1]['accumulatedAmount']['internal_settlement_income'] += $v['accumulatedAmount']['internal_settlement_income'];
                    $return[1]['accumulatedAmount']['main_business_cost'] += $v['accumulatedAmount']['main_business_cost'];
                    $return[1]['accumulatedAmount']['external_cost'] += $v['accumulatedAmount']['external_cost'];
                    $return[1]['accumulatedAmount']['internal_settlement_cost'] += $v['accumulatedAmount']['internal_settlement_cost'];
                    $return[1]['accumulatedAmount']['operating_taxes_and_attachments'] += $v['accumulatedAmount']['operating_taxes_and_attachments'];
                    $return[1]['accumulatedAmount']['selling_expenses'] += $v['accumulatedAmount']['selling_expenses'];
                    $return[1]['accumulatedAmount']['commission'] += $v['accumulatedAmount']['commission'];
                    $return[1]['accumulatedAmount']['other'] += $v['accumulatedAmount']['other'];
                    $return[1]['accumulatedAmount']['overhead_expenses'] += $v['accumulatedAmount']['overhead_expenses'];
                    $return[1]['accumulatedAmount']['salary'] += $v['accumulatedAmount']['salary'];
                    $return[1]['accumulatedAmount']['chummage'] += $v['accumulatedAmount']['chummage'];
                    $return[1]['accumulatedAmount']['hydroelectricity'] += $v['accumulatedAmount']['hydroelectricity'];
                    $return[1]['accumulatedAmount']['handle_official_business'] += $v['accumulatedAmount']['handle_official_business'];
                    $return[1]['accumulatedAmount']['cost_of_financing'] += $v['accumulatedAmount']['cost_of_financing'];
                    $return[1]['accumulatedAmount']['interest'] += $v['accumulatedAmount']['interest'];
                    $return[1]['accumulatedAmount']['exchange_gain_or_loss'] += $v['accumulatedAmount']['exchange_gain_or_loss'];
                    $return[1]['accumulatedAmount']['poundage'] += $v['accumulatedAmount']['poundage'];
                    $return[1]['accumulatedAmount']['nonbusiness_income'] += $v['accumulatedAmount']['nonbusiness_income'];
                    $return[1]['accumulatedAmount']['non_business_expenditure'] += $v['accumulatedAmount']['non_business_expenditure'];
                    $return[1]['accumulatedAmount']['total_profit'] += $v['accumulatedAmount']['total_profit'];
                    $return[1]['accumulatedAmount']['income_tax'] += $v['accumulatedAmount']['income_tax'];
                    $return[1]['accumulatedAmount']['net_margin'] += $v['accumulatedAmount']['net_margin'];

                }
                $return[1]['budgetAmount']['gross_profit'] = sprintf('%.2f',$return[1]['budgetAmount']['main_operating_income'] - $return[1]['budgetAmount']['main_business_cost']);
                $return[1]['budgetAmount']['ratio_of_margin'] = sprintf('%.4f',$return[1]['budgetAmount']['main_business_cost'] / $return[1]['budgetAmount']['main_operating_income']);
                $return[1]['amountOfActualBalance']['gross_profit'] = sprintf('%.2f',$return[1]['amountOfActualBalance']['main_operating_income'] - $return[1]['amountOfActualBalance']['main_business_cost']);
                $return[1]['amountOfActualBalance']['ratio_of_margin'] = sprintf('%.4f',$return[1]['amountOfActualBalance']['main_business_cost'] / $return[1]['amountOfActualBalance']['main_operating_income']);
                $return[1]['accumulatedAmount']['gross_profit'] = sprintf('%.2f',$return[1]['accumulatedAmount']['main_operating_income'] - $return[1]['accumulatedAmount']['main_business_cost']);
                $return[1]['accumulatedAmount']['ratio_of_margin'] = sprintf('%.4f',$return[1]['accumulatedAmount']['main_business_cost'] / $return[1]['accumulatedAmount']['main_operating_income']);
            }
//            echo '<pre>';print_r($return);exit;
            $companyList = Arrays::group($getCompany['data'],'company_id');
            $this->assign('companyList',$companyList);
            $this->assign('profitStatement',$return);
            return $this->fetch('group_profit_statement');
        }else{
            /*********************** 分公司 *********************/


            //获取利润表预计
            $where['company_id'] = $company_id;
            $where['years'] = $year;
            $where['month'] = $month;
            $where['is_predict'] = 1;
            $BranchProfitForecast = $this->callSoaErp('post','/profit_statement/gainBranchProfits',$where);
//            var_dump($BranchProfitForecast);exit;
            $this->assign('BranchProfitForecast',$BranchProfitForecast['data']);

            //分公司利润
            $where['is_predict'] = 2;
            $BranchProfit = $this->callSoaErp('post','/profit_statement/gainBranchProfits',$where);
            $this->assign('BranchProfit',$BranchProfit['data']);

            //分公司利润累计
            $accumulatedProfitsOfBranchCompanies = $this->callSoaErp('post','/profit_statement/accumulatedProfitsOfBranchCompanies',$where);
            $this->assign('accumulatedProfitsOfBranchCompanies',$accumulatedProfitsOfBranchCompanies['data']);
//            var_dump($accumulatedProfitsOfBranchCompanies);exit;
            return $this->fetch('profitStatement');
        }


    }

    /***
     * 编辑利润表 晔
     */
    public function editProfitStatement(){
        $year_month = $_GET['year_month'];
        $this->assign('year_month',$year_month);
        $company_id = session('user')['company_id'];

        $ar = explode('-',$year_month);
        $year = $ar[0];
        $month = $ar[1];
        $this->assign('year',$year);
        $this->assign('month',$month);

        //获取货币
        $where['currency_id'] = session('user')['company_currency_id'];
        $where['status'] = 1;
        $getCurrency = $this->callSoaErp('post','/system/getCurrency',$where);
        $this->assign('Currency',$getCurrency['data']);
        unset($where);

        //获取利润表预计
        $where['company_id'] = $company_id;
        $where['years'] = $year;
        $where['month'] = $month;
        $where['is_predict'] = 1;
        $BranchProfitForecast = $this->callSoaErp('post','/profit_statement/gainBranchProfits',$where);
        $this->assign('BranchProfitForecast',$BranchProfitForecast['data']);

        //分公司利润
        $where['is_predict'] = 2;
        $BranchProfit = $this->callSoaErp('post','/profit_statement/gainBranchProfits',$where);
        $this->assign('BranchProfit',$BranchProfit['data']);

        //分公司利润累计
        $accumulatedProfitsOfBranchCompanies = $this->callSoaErp('post','/profit_statement/accumulatedProfitsOfBranchCompanies',$where);
        $this->assign('accumulatedProfitsOfBranchCompanies',$accumulatedProfitsOfBranchCompanies['data']);


        return $this->fetch('profitStatement_edit');
    }

    /****
     * 添加编辑利润表 晔
     */
    public function editProfitStatementAjax(){
        $param = Request::instance()->param();
        $year_month = Arrays::get($param,'year_month');
        $d['years'] =  explode('-',$year_month)[0];
        $d['month'] =  explode('-',$year_month)[1];

        $d['profit_statement_id'] = Arrays::get($param,'profit_statement_id')?:0;
        $d['number_of_staff'] = Arrays::get($param,'number_of_staff')?:0;
        $d['number_of_guests_received'] = Arrays::get($param,'number_of_guests_received')?:0;
        $d['order_amount'] = Arrays::get($param,'order_amount')?:0;
        $d['main_operating_income'] = Arrays::get($param,'main_operating_income')?:0;
        $d['external_income'] = Arrays::get($param,'external_income')?:0;
        $d['internal_settlement_income'] = Arrays::get($param,'internal_settlement_income')?:0;
        $d['main_business_cost'] = Arrays::get($param,'main_business_cost')?:0;
        $d['external_cost'] = Arrays::get($param,'external_cost')?:0;
        $d['internal_settlement_cost'] = Arrays::get($param,'internal_settlement_cost')?:0;
        $d['gross_profit'] = Arrays::get($param,'gross_profit')?:0;
        $d['ratio_of_margin'] = Arrays::get($param,'ratio_of_margin')?:0;
        $d['operating_taxes_and_attachments'] = Arrays::get($param,'operating_taxes_and_attachments')?:0;

        $d['commission'] = Arrays::get($param,'commission')?:0;
        $d['other'] = Arrays::get($param,'other')?:0;

        $d['salary'] = Arrays::get($param,'salary')?:0;
        $d['chummage'] = Arrays::get($param,'chummage')?:0;
        $d['hydroelectricity'] = Arrays::get($param,'hydroelectricity')?:0;
        $d['handle_official_business'] = Arrays::get($param,'handle_official_business')?:0;

        $d['interest'] = Arrays::get($param,'interest')?:0;
        $d['exchange_gain_or_loss'] = Arrays::get($param,'exchange_gain_or_loss')?:0;
        $d['poundage'] = Arrays::get($param,'poundage')?:0;
        $d['nonbusiness_income'] = Arrays::get($param,'nonbusiness_income')?:0;
        $d['non_business_expenditure'] = Arrays::get($param,'non_business_expenditure')?:0;

        $d['income_tax'] = Arrays::get($param,'income_tax')?:0;
        $d['net_margin'] = Arrays::get($param,'net_margin')?:0;
        $d['is_predict'] = 2;
        $d['user_id'] = session('user')['user_id'];
        $d['company_id'] = session('user')['company_id'];


        //销售费用
        $d['selling_expenses'] = $d['commission']+$d['other'];
        //管理费用
        $d['overhead_expenses'] = $d['salary']+$d['chummage']+$d['hydroelectricity']+$d['handle_official_business'];
        //财务费用
        $d['cost_of_financing'] = $d['interest']+$d['exchange_gain_or_loss']+$d['poundage'];
        //利润总额
        $d['total_profit'] = $d['main_operating_income']-$d['main_business_cost']-$d['operating_taxes_and_attachments']-$d['selling_expenses']-$d['overhead_expenses']-$d['cost_of_financing']+$d['nonbusiness_income']-$d['non_business_expenditure'];
        //净利润
        $d['net_margin'] = $d['total_profit']-$d['income_tax'];


//        var_dump($d);exit;
        return $this->callSoaErp('post','/profit_statement/editProfitStatementAjax',$d);

    }


    /***
     * 发团毛利统计明细
     * Hugh
     */
    public function grossProfitStatisticsDetail(){
        //应收
        $number = input('get.number'); //团队编号
        $where['team_product_number'] = $number;
        $where['team_product_id'] = input('get.plan_id');
        $getTeamProductReceivableCompany = $this->callSoaErp('post','/product/getTeamProductReceivableCompany',$where);

//        echo '<pre>';print_r($getTeamProductReceivableCompany);exit;

        //团队应收分公司
        if($getTeamProductReceivableCompany['data']['receivable_info']){
            foreach($getTeamProductReceivableCompany['data']['receivable_info'] as $k=>$v){
                $getTeamProductReceivableCompany['data']['receivable_info'][$k]['product_source_type_id'] = $v['source_type_id'];
                //换算后的金额
            }
            $vl['团费']['count'] = count($getTeamProductReceivableCompany['data']['receivable_info']);
            $d2 = Arrays::group($getTeamProductReceivableCompany['data']['receivable_info'],'payment_object_id');
            foreach($d2 as $ky=>$vy){
                $vl['团费']['data'][$ky]['count'] = count($vy);
                $vl['团费']['data'][$ky]['data'] = Arrays::group($vy,'order_number');
                foreach($vl['团费']['data'][$ky]['data'] as $k1=>$y1){
                    foreach($vl['团费']['data'][$ky]['data'][$k1] as $k2=>$y2){
                        $w['company_order_number'] = $k1;
                        $list = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$w);
                        $vl['团费']['data'][$ky]['data'][$k1][$k2]['youke'] = $list['data'];
                        $vl['团费']['data'][$ky]['data'][$k1][$k2]['customer_info'] = Arrays::keys(Arrays::group($vl['团费']['data'][$ky]['data'][$k1][$k2]['customer_info'],'company_order_customer_id'));
                    }
                }
            }
        }

        //其他应收分公司
        if($getTeamProductReceivableCompany['data']['team_product_other_info']){
            foreach($getTeamProductReceivableCompany['data']['team_product_other_info'] as $k=>$v){
                $getTeamProductReceivableCompany['data']['team_product_other_info'][$k]['product_source_type_id'] = $v['source_type_id'];
                //换算后的金额
            }

            $vl['其他']['count'] = count($getTeamProductReceivableCompany['data']['team_product_other_info']);
            $d2 = Arrays::group($getTeamProductReceivableCompany['data']['team_product_other_info'],'payment_object_id');
            foreach($d2 as $ky=>$vy){
                $vl['其他']['data'][$ky]['count'] = count($vy);
                $vl['其他']['data'][$ky]['data'] = Arrays::group($vy,'order_number');
                foreach($vl['其他']['data'][$ky]['data'] as $k1=>$y1){
                    foreach($vl['其他']['data'][$ky]['data'][$k1] as $k2=>$y2){
                        $w['company_order_number'] = $k1;
                        $list = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$w);
                        $vl['其他']['data'][$ky]['data'][$k1][$k2]['youke'] = $list['data'];
                        $vl['其他']['data'][$ky]['data'][$k1][$k2]['customer_info'] = Arrays::keys(Arrays::group($vl['其他']['data'][$ky]['data'][$k1][$k2]['customer_info'],'company_order_customer_id'));
                    }
                }
            }
        }
        $this->assign('list',$vl);
        unset($vl);

        $where['team_product_number'] = $number;
        $Receivable = $this->callSoaErp('post','/branchcompany/getCompanyOrderNumberByTeamProductNumber',$where);
        $ReceivableList = Arrays::group($Receivable['data'],'company_id');
//        var_dump($ReceivableList);exit;
        $this->assign('ReceivableList',$ReceivableList);
        unset($where);

        $where['status'] = 1;
        $branchs = $this->callSoaErp('post','/system/getCompany',$where);
        $this->assign('branchs',Arrays::group($branchs['data'],'company_id'));
        $this->assign('types',$this->types);
        unset($where);

        $where['status'] = 1;
        $Currency = $this->callSoaErp('post','/system/getCurrency',$where);
        if(!empty($Currency['data'])){
            $this->assign('Currency',Arrays::group($Currency['data'],'currency_id'));
        }
        unset($where);


        //获取团队应付供应商
        $where['team_product_number'] = input('get.number');
        $where['team_product_id'] = input('get.plan_id');
        $TeamProductCopeSupplier = $this->callSoaErp('post','/product/getTeamProductCopeSupplier',$where);
//        echo '<pre>';print_r($TeamProductCopeSupplier);exit;

        if($TeamProductCopeSupplier['data']){
            if($TeamProductCopeSupplier['data']['cope_info']){
                foreach($TeamProductCopeSupplier['data']['cope_info'] as $k=>$v){
                    $vl['团费']['count'] = count($TeamProductCopeSupplier['data']['cope_info']);
                    $d2 = Arrays::group($TeamProductCopeSupplier['data']['cope_info'],'source_type_id');
                    foreach($d2 as $ky=>$vy){
                        $vl['团费']['data'][$ky]['count'] = count($vy);
                        $vl['团费']['data'][$ky]['data'] = Arrays::group($vy,'receivable_object_id');
                    }

                }
            }

            if($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info']){
                foreach($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'] as $k=>$v){
                    $vl['地接']['count'] = count($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info']);
                    $d2 = Arrays::group($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'],'source_type_id');
                    foreach($d2 as $ky=>$vy){
                        $vl['地接']['data'][$ky]['count'] = count($vy);
                        $vl['地接']['data'][$ky]['data'] = Arrays::group($vy,'receivable_object_id');
                    }
                }
            }

            if($TeamProductCopeSupplier['data']['team_product_other_info']){
                foreach($TeamProductCopeSupplier['data']['team_product_other_info'] as $k=>$v){
                    $vl['其他']['count'] = count($TeamProductCopeSupplier['data']['team_product_other_info']);
                    $d2 = Arrays::group($TeamProductCopeSupplier['data']['team_product_other_info'],'source_type_id');
                    foreach($d2 as $ky=>$vy){
                        $vl['其他']['data'][$ky]['count'] = count($vy);
                        $vl['其他']['data'][$ky]['data'] = Arrays::group($vy,'receivable_object_id');
                    }
                }
            }
        }
//        echo '<pre>';print_r($vl);exit;
        $this->assign('list2',$vl);
        //10大供应商
        $where['status'] = 1;
        $where['company_id'] = session('user')['company_id'];
        $SupplierList = $this->callSoaErp('post','/source/getSupplier',$where);
        $SupplierArr = Arrays::group($SupplierList['data'],'supplier_type_id');
        foreach($SupplierArr as $ky=>$vy){
            if($ky!=1){
                if(empty($SupplierArr[1])){
                    $SupplierArr[1] = [];
                }
                if(empty($SupplierArr[$ky])){
                    $SupplierArr[$ky] = [];
                }
                $SupplierArr[$ky] = array_merge($SupplierArr[1],$SupplierArr[$ky]);
            }
        }
        for($i=2;$i<=20;$i++){
            if(empty($SupplierArr[$i])){
                $SupplierArr[$i] = $SupplierArr[1];
            }
        }
        $this->assign('SupplierArr',$SupplierArr);

        return $this->fetch('gross_profit_statistics_detail');
    }


    /**
     * 发团毛利统计
     * Hugh
     */
    public function grossProfitStatistics(){
        $company_id = Session('user')['company_id'];

        //线路类型
        if($company_id<>1){
            $where['company_id'] = $company_id;
            $_GET['company_id'] = $company_id;
        }
        $where['status'] = 1;
        $RouteType = $this->callSoaErp('post','/system/getRouteType',$where);
        $this->assign('RouteType',Arrays::group($RouteType['data'],'type'));

        //公司
        $Company = $this->callSoaErp('post','/system/getCompany',$where);
        $this->assign('Company',$Company['data']);

        //成本报表列表
        $sDate = input('get.sDate');
        $eDate = input('get.eDate');
        $route_type_id = input('get.route_type_id');
        $team_product_number = input('get.team_product_number');
        $team_product_name = input('get.team_product_name');
        $nickname = input('get.nickname');
        $company_id = input('get.company_id');
        $supplier_id = input('get.supplier_id');

        $ar = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        if($sDate){
            $ar['sDate'] = strtotime($sDate);
        }
        if($eDate){
            $ar['eDate'] = strtotime($eDate);
        }
        if(is_numeric($company_id)){
            $ar['company_id'] = $company_id;
            if(Session('user')['company_id']==1){
                $ar['company_id_1'] = $company_id;
            }
        }
        if(is_numeric($route_type_id)){
            $ar['route_type_id'] = $route_type_id;
        }
        if($team_product_number){
            $ar['team_product_number'] = $team_product_number;
        }
        if($team_product_name){
            $ar['team_product_name'] = $team_product_name;
        }
        if($nickname){
            $ar['nickname'] = $nickname;
        }
        if($supplier_id){
            $ar['supplier_id'] = $supplier_id;
        }
//var_dump($ar);exit;
        $GrossProfitStatistics = $this->callSoaErp('post','/finance/getGrossProfitStatistics',$ar);
        $this->getPageParams($GrossProfitStatistics);

//        echo '<pre>';print_r($GrossProfitStatistics);exit;

        //供应商
        $result = $this->callSoaErp('post','/source/getSupplier',$where);
        $this->assign('supplier',$result['data']);

        return $this->fetch('gross_profit_statistics');
    }


    /**
     * 成本明细
     * Hugh
     */
    public function codeDetail(){
        //获取团队应付供应商
        $where['team_product_number'] = input('get.number');
        $TeamProductCopeSupplier = $this->callSoaErp('post','/product/getTeamProductCopeSupplier',$where);

        if($TeamProductCopeSupplier['data']){
            if($TeamProductCopeSupplier['data']['cope_info']){
                $TeamProductCopeSupplier['data']['cope_info'] = Arrays::sort($TeamProductCopeSupplier['data']['cope_info'],'source_type_id','asc');
                foreach($TeamProductCopeSupplier['data']['cope_info'] as $k=>$v){
                    $vl['团费']['count'] = count($TeamProductCopeSupplier['data']['cope_info']);
                    $d2 = Arrays::group($TeamProductCopeSupplier['data']['cope_info'],'source_type_id');
                    foreach($d2 as $ky=>$vy){
                        $vl['团费']['data'][$ky]['count'] = count($vy);
                        $vl['团费']['data'][$ky]['data'] = Arrays::group($vy,'receivable_object_id');
                    }

                }
            }

            if($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info']){
                $TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'] = Arrays::sort($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'],'source_type_id','asc');
                foreach($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'] as $k=>$v){
                    $vl['地接']['count'] = count($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info']);
                    $d2 = Arrays::group($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'],'source_type_id');
                    foreach($d2 as $ky=>$vy){
                        $vl['地接']['data'][$ky]['count'] = count($vy);
                        $vl['地接']['data'][$ky]['data'] = Arrays::group($vy,'receivable_object_id');
                    }
                }
            }

            if($TeamProductCopeSupplier['data']['team_product_other_info']){
                $TeamProductCopeSupplier['data']['team_product_other_info'] = Arrays::sort($TeamProductCopeSupplier['data']['team_product_other_info'],'source_type_id','asc');
                foreach($TeamProductCopeSupplier['data']['team_product_other_info'] as $k=>$v){
                    $vl['其他']['count'] = count($TeamProductCopeSupplier['data']['team_product_other_info']);
                    $d2 = Arrays::group($TeamProductCopeSupplier['data']['team_product_other_info'],'source_type_id');
                    foreach($d2 as $ky=>$vy){
                        $vl['其他']['data'][$ky]['count'] = count($vy);
                        $vl['其他']['data'][$ky]['data'] = Arrays::group($vy,'receivable_object_id');
                    }

                }
            }
        }
        $this->assign('list',$vl);

        $this->assign('types',$this->types);
        //10大供应商
        $where['status'] = 1;
        $where['company_id'] = session('user')['company_id'];
        $SupplierList = $this->callSoaErp('post','/source/getSupplier',$where);
        $SupplierArr = Arrays::group($SupplierList['data'],'supplier_type_id');
        foreach($SupplierArr as $ky=>$vy){
            if($ky!=1){
                if(empty($SupplierArr[1])){
                    $SupplierArr[1] = [];
                }
                if(empty($SupplierArr[$ky])){
                    $SupplierArr[$ky] = [];
                }
                $SupplierArr[$ky] = $SupplierArr[1]+$SupplierArr[$ky];
            }
        }
        $this->assign('SupplierArr',$SupplierArr);

        $where['status'] = 1;
        $Currency = $this->callSoaErp('post','/system/getCurrency',$where);
        if(!empty($Currency['data'])){
            $this->assign('Currency',Arrays::group($Currency['data'],'currency_id'));
        }

        return $this->fetch('code_detail');

    }


    /**
     * 成本报表
     * Hugh 19-01-03
     */
    public function costSheet(){
//        var_dump(Session('user'));exit;
        $company_id = Session('user')['company_id'];
        //线路类型
        if($company_id<>1){
            $where['company_id'] = $company_id;
            $_GET['company_id'] = $company_id;
        }
        $where['status'] = 1;
        $RouteType = $this->callSoaErp('post','/system/getRouteType',$where);
        $this->assign('RouteType',Arrays::group($RouteType['data'],'type'));

        //公司
        $Company = $this->callSoaErp('post','/system/getCompany',$where);
        $this->assign('Company',$Company['data']);

        //成本报表列表
        $sDate = input('get.sDate');
        $eDate = input('get.eDate');
        $route_type_id = input('get.route_type_id');
        $team_product_number = input('get.team_product_number');
        $team_product_name = input('get.team_product_name');
        $nickname = input('get.nickname');
        $company_id = input('get.company_id');

        $ar = [];
        if($sDate){
            $ar['sDate'] = strtotime($sDate);
        }
        if($eDate){
            $ar['eDate'] = strtotime($eDate);
        }
        if(is_numeric($company_id)){
            $ar['company_id'] = $company_id;
        }
        if(is_numeric($route_type_id)){
            $ar['route_type_id'] = $route_type_id;
        }
        if($team_product_number){
            $ar['team_product_number'] = $team_product_number;
        }
        if($team_product_name){
            $ar['team_product_name'] = $team_product_name;
        }
        if($nickname){
            $ar['nickname'] = $nickname;
        }

        $CostSheet = $this->callSoaErp('post','/finance/getCostSheet',$ar);
        $this->assign('CostSheet',$CostSheet['data']);
        return $this->fetch('cost_sheet');
    }


    public function CostSharingInfoCsv(){
        $year = input('get.year');
        $month = input('get.month');
        if($month<10){
            $month = '0'.$month;
        }
        $companyId = input('get.company');
        $Company = $this->callSoaErp('post','/system/getCompany',['status'=>1]);
        $CompanyGroup = Arrays::group($Company['data'],'company_id'); //公司

        //分摊比例
        $CompanyApportionProportion = $this->callSoaErp('post','/finance/getCompanyApportionProportion');
        $CompanyApportionProportion = Arrays::group($CompanyApportionProportion['data'],'company_id');
        //分摊明细
        $where['year'] = $year;
        $where['month'] = $month;
        if(is_numeric($companyId)){
            $where['choose_company_id'] = $companyId;
        }
        $ApportionProportion = $this->callSoaErp('post','/finance/getApportionProportion',$where);

        $filename = $year.$month.'.csv';

        $mess = "公司{$year}年{$month}月垫付集团公司费用分摊"."\r";
        $mess .= "分公司,明细,总金额(人民币)"."\r";
        foreach($ApportionProportion['data'] as $v){
            $mess .=  "{$CompanyGroup[$v['company_id']][0]['company_name']},{$v['project_name']},{$v['total_money']}"."\r";
        }
        $mess .= "合计：{$ApportionProportion['data'][0]['all_money']} RMB";
        $mess .= "\r\r\r";
        $mess .= "分摊总计"."\r";
        $mess .= "分公司,分摊比例,分摊金额(人民币)"."\r";
        $nust = 0;
        foreach($Company['data'] as $k=>$v){
            if($v['company_id']!=1){
                $nust += round($CompanyApportionProportion[$v['company_id']][0]['apportion_proportion']*$ApportionProportion['data'][0]['all_money']/100,2);
                $bili = sprintf('%.2f',$CompanyApportionProportion[$v['company_id']][0]['apportion_proportion']);
                $jine = round($CompanyApportionProportion[$v['company_id']][0]['apportion_proportion']*$ApportionProportion['data'][0]['all_money']/100,2);
                $mess .= "{$v['company_name']},{$bili},{$jine}"."\r";
            }
        }
        $mess .= "合计：{$nust} RMB";


        header("Content-type: application/octet-stream");
        header("Content-type:text/html;charset=utf-8");
        header("Accept-Ranges:bytes");
        $encoded_filename = urlencode($filename);
        $encoded_filename = str_replace("+", "%20", $encoded_filename);
        $ua = $_SERVER["HTTP_USER_AGENT"];
        if (preg_match("/MSIE|rv:11.0/", $ua)) {        // MSIE => IE6-10 , rv:11.0 => IE11
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header('Content-Disposition: attachment; filename*="utf8\'\'' . $filename . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }
        echo mb_convert_encoding($mess, "GBK", "UTF-8");
        exit;
    }

    /***
     * 费用分摊
     * Hugh
     */
    public function CostSharingList(){
        $year = input('get.year')?:date('Y');
        $this->assign('year',$year);

        $ApportionProportionList = $this->callSoaErp('post','/finance/getApportionProportionList',['year'=>$year]);
        $this->assign('ApportionProportionList',$ApportionProportionList['data']);

        return $this->fetch('cost_sharing_list_manage');
    }

    /*
     * 费用分摊详情
     * Hugh
     */
    public function CostSharingInfo(){
        $year = input('get.year');
        $month = input('get.month');
        if($month<10){
            $month = '0'.$month;
        }
        $companyId = input('get.company');

        $Company = $this->callSoaErp('post','/system/getCompany',['status'=>1]);
//        var_dump(Arrays::group($Company['data'],'company_id'));exit;
        $this->assign('Company',$Company['data']);
        $this->assign('CompanyGroup',Arrays::group($Company['data'],'company_id'));

        //分摊比例
        $CompanyApportionProportion = $this->callSoaErp('post','/finance/getCompanyApportionProportion');
        $this->assign('CompanyApportionProportion',Arrays::group($CompanyApportionProportion['data'],'company_id'));
        //分摊明细
        $where['year'] = $year;
        $where['month'] = $month;
        if(is_numeric($companyId)){
            $where['choose_company_id'] = $companyId;
        }
        $ApportionProportion = $this->callSoaErp('post','/finance/getApportionProportion',$where);
        $this->assign('ApportionProportion',$ApportionProportion['data']);
//        var_dump($ApportionProportion);exit;
        return $this->fetch('cost_sharing_info_manage');
    }

    public function DelCostSharing(){
        $apportion_proportion_id = input('get.apportion_proportion_id');
        $ar['apportion_proportion_id'] = $apportion_proportion_id;
        $ar['status'] = 0;
        $this->callSoaErp('post','/finance/updateApportionProportionByApportionProportionId',$ar);
        echo"<script>history.go(-1);</script>";
    }


    /***
     * 设置分摊比例
     * Hugh
     */
    public function EstablishmentOfAScaleOfAssessments(){
        $Company = $this->callSoaErp('post','/system/getCompany',['status'=>1]);
        $this->assign('Company',$Company['data']);

        $CompanyApportionProportion = $this->callSoaErp('post','/finance/getCompanyApportionProportion');
        $this->assign('CompanyApportionProportion',Arrays::group($CompanyApportionProportion['data'],'company_id'));
        return $this->fetch('establishment_of_a_scale_of_assessments');
    }

    /**
     * 添加分摊明细
     * Hugh
     */
    public function AddCostSharing(){
        $Company = $this->callSoaErp('post','/system/getCompany',['status'=>1]);
        $this->assign('Company',$Company['data']);
        //分摊比例
        $CompanyApportionProportion = $this->callSoaErp('post','/finance/getCompanyApportionProportion');
        $this->assign('CompanyApportionProportion',Arrays::group($CompanyApportionProportion['data'],'company_id'));

        return $this->fetch('cost_sharing_add');
    }

    /***
     * 修改分摊明细
     */
    public function UpCostSharing(){
        $apportion_proportion_id = input('get.apportion_proportion_id');
        $w['apportion_proportion_id'] = $apportion_proportion_id;
        $w['year'] = input('get.year');
        $w['month'] = input('get.month');
        if($w['month']<10){
            $w['month'] = '0'.$w['month'];
        }
        $ApportionProportion = $this->callSoaErp('post','/finance/getApportionProportion',$w);
        $this->assign('ApportionProportion',$ApportionProportion['data'][0]);
//            var_dump($ApportionProportion['data'][0]);exit;
        return $this->fetch('cost_sharing_update');
    }


    /***
     * 修改分摊比例Ajax
     * hugh
     */
    public function updateCompanyApportionProportionAjax(){
        $param = Request::instance()->param();
        $scale = Arrays::get($param,'scale');
        $d['company_apportion_proportion_array'] = [];
        foreach($scale as $k=>$v){
            $ar['company_id'] = $k;
            $ar['apportion_proportion'] = $v;
            $d['company_apportion_proportion_array'][] = $ar;
            unset($ar);
        }
        return $this->callSoaErp('post','/finance/updateCompanyApportionProportion',$d);
    }

    /***
     * 添加分摊Ajax
     * hugh
     */
    public function addApportionProportionAjax(){
        $param = Request::instance()->param();
//        var_dump($param);
        $apportion_proportion = Arrays::get($param,'apportion_proportion',[]);
        $money = Arrays::get($param,'money',[]);

        $d['project_name'] = Arrays::get($param,'project_name');
        $d['total_money'] = Arrays::get($param,'total_money');
        $d['year'] = Arrays::get($param,'year');
        $d['month'] = Arrays::get($param,'month');
        if($d['month']<10){
            $d['month'] = '0'.$d['month'];
        }
        $d['apportion_proportion_array'] = [];
        foreach($apportion_proportion as $k=>$v){
            $ar['company_id'] = $k;
            $ar['apportion_proportion'] = $apportion_proportion[$k];
            $ar['money'] = $money[$k];
            $d['apportion_proportion_array'][] = $ar;
            unset($ar);
        }
        return $this->callSoaErp('post','/finance/addApportionProportion',$d);

    }

    /***
     * 添加修改分摊Ajax
     * hugh
     */
    public function editApportionProportionAjax(){
        $param = Request::instance()->param();
//        var_dump($param);
        $apportion_proportion = Arrays::get($param,'apportion_proportion',[]);
        $money = Arrays::get($param,'money',[]);

        $d['apportion_proportion_id'] = Arrays::get($param,'apportion_proportion_id');
        $d['project_name'] = Arrays::get($param,'project_name');
        $d['total_money'] = Arrays::get($param,'total_money');

        if($d['month']<10){
            $d['month'] = '0'.$d['month'];
        }
        $d['apportion_proportion_array'] = [];
        foreach($apportion_proportion as $k=>$v){
            $ar['company_id'] = $k;
            $ar['apportion_proportion'] = $apportion_proportion[$k];
            $ar['money'] = $money[$k];
            $d['apportion_proportion_array'][] = $ar;
            unset($ar);
        }
        return $this->callSoaErp('post','/finance/updateApportionProportionByApportionProportionId',$d);

    }



	//显示应收汇总

	public function showReceivableManage(){
        Cookie::set("receivable_manage",$_SERVER['REQUEST_URI']);
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        //获取付款公司名称
        $company_data = [
            'status'=>1
        ];
        $company_data_result = $this->callSoaErp('post', '/system/getCompany',$company_data);

        //获取货币
        $currency_data = [
			'status'=>1
        ];
        $currency_data_result = $this->callSoaErp('post', '/system/getCurrency',$currency_data);
        //获取所有顾客
        $customer_params = [
            'status'=>1
        ];
       // $customer_result = $this->callSoaErp('post','/branchcompany/getCustomer',$customer_params);
        //搜索
        $data['status'] = 1;
        $data['is_like'] = 1;
        $team_product_number = input("team_product_number");
        $product_name = input("product_name");
        $receivable_number = input('receivable_number');
        $order_number = input("order_number");
        $object_type = input('object_type');
        $object_id = input('object_id');
        if(!empty($team_product_number)){
            $data['team_product_number'] = $team_product_number;
        }
        
        
        
        if(!empty($product_name)){
            $data['product_name'] = $product_name;
        }
        if(!empty($receivable_number)){
            $data['receivable_number'] = $receivable_number;
        }
        if(!empty($order_number)){
            $data['order_number'] =$order_number;
        }
        
        if(is_numeric($object_type)){
        	$data['payment_object_type'] =$object_type;
        	
        	if(is_numeric($object_id)){
        		$data['payment_object_id'] =$object_id;
        	}
        }

        $data['company_id'] = session('user')['company_id'];
        //获取本公司币种
        //获取所有公司对本公司币种的汇率
        $proporation_params = [
        		'opposite_currency_id'=>session('user')['company_currency_id'],
        		'proportion_time'=>help::getLastMonthDay()
        ];
        
        $proporation_result = $this->callSoaErp('post','/system/getOneCurrencyProportion', $proporation_params);
        
        $proporation_result = $proporation_result['data'];
      
        
        
        $receivable_data_result = $this->callSoaErp('post', '/finance/getReceivable',$data);
        
        
     
        $receivable_data_result_arr = $receivable_data_result['data']['list'];
 
        
     
        
        //$this->assign('receivable_data_result',$receivable_data_result['data']);
        $this->getPageParams($receivable_data_result);
        
        
        $receivable_data_result = $receivable_data_result_arr;

        //汇总
        $yingshou = 0;
        $shishou = 0;
        $weishou = 0;
        for($i=0;$i<count($receivable_data_result);$i++){
        
        	if($receivable_data_result[$i]['receivable_currency_id'] != session('user')['company_currency_id']){
        	
        		for($j=0;$j<count($proporation_result);$j++){
        			if($proporation_result[$j]['currency_id'] == $receivable_data_result[$i]['receivable_currency_id']){
        				
        				$huilv = $proporation_result[$j]['currency_proportion'];

        				
        				$money =  $receivable_data_result[$i]['receivable_money']*$huilv;

        				$yingshou=$yingshou+$money;
        
        				$shishou+=$receivable_data_result[$i]['true_receipt']*$huilv;
        				
        			}
        		}
        
        	}else{
        	
        		$yingshou+=$receivable_data_result[$i]['receivable_money'];
        		$shishou+=$receivable_data_result[$i]['true_receipt'];
        
        	}
        	 
        }

      
        $weishou = $yingshou-$shishou;
        $this->assign('company_data_result',$company_data_result['data']);
        $this->assign('order_data_result',$order_data_result['data']);
        $this->assign('currency_data_result',$currency_data_result['data']);
        $this->assign('yingshou',$yingshou);
        $this->assign('shishou',$shishou);
        $this->assign('weishou',$weishou);
        
       // $this->assign('customer_result',$customer_result['data']);
        return $this->fetch('receivable_manage');
		
	}
    //获取游客company_order_customer_id
	public function getReceivableCustomerCompanyOrderCustomerIdAjax(){
		
        $order_number = input("company_order_number");
	
        $data = [
            "company_order_number"=>$order_number,
        	'status'=>1	
        ];
        
        //获取该订单下的所有游客
        $order_customer = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$data);
 		
//         $receivable_customer_result = $receivable_customer['data'];
       
//         $customer_number='';
//         for($i=0;$i<count($receivable_customer_result);$i++){
//         	$customer_number.=','.$receivable_customer_result[$i]['company_order_customer_id'];
//         }
//         $customer_number = trim($customer_number,',');

        
        return $order_customer;
	}
	//获取游客
	public function getReceivableCustomerAjax(){
	
		$receivable_number = input("receivable_number");
	
		$data = [
			"receivable_number"=>$receivable_number,
			'status'=>1
		];
		
		//获取该订单下的所有游客
		$receivable_customer = $this->callSoaErp('post','/finance/getReceivableCustomerByReceivableNumber',$data);
		
		
		$receivable_customer_result = $receivable_customer['data'];
		 

		return $receivable_customer_result;
	}
    //获取公司订单游客
    public function getCompanyOrderCustomerAjax(){

        $company_order_number = input("company_order_number");

        $data = [
            "company_order_number"=>$company_order_number

        ];

        $company_order_number = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$data);


        return  $company_order_number['data'];
    }


    /**
     *  新增应收添加Ajax
     */
    public function addReceivableAjax(){
        $payment_company_id = input("payment_company_id");
        $order_number = input("order_number");
        $product_name = input("product_name");
        $product_type = input("product_type");
        $currency_id = input("currency_id");
        $receivable_money = input("receivable_money");
        $remark = input("remark");
        $status = 1;
        $user_id = session('user')['user_id'];
		$company_order_customer_id = input('company_order_customer_id');
        $data = [
            "payment_company_id"=>$payment_company_id,
            "order_number"=>$order_number,
            "product_name"=>$product_name,
            "product_type"=>$product_type,
            "currency_id"=>$currency_id,
            "receivable_money"=>$receivable_money,
            "remark"=>$remark,
            "status"=>$status,
            'user_id'=>$user_id,
        	'company_order_customer_id'=>$company_order_customer_id,
        	'company_id'=>session('user')['company_id']	
        ];
        
        $result = $this->callSoaErp('post', '/finance/addReceivable',$data);

        return   $result;//['code' => '400', 'msg' => $data];
    }
    
    /**
     *  新增往来账审批Ajax
     */
    public function addComapnyFinacneApproveAjax(){
    	$invoice_time = input("invoice_time");
    	$invoice_number = input("invoice_number");
    	$payment_company_id = input("object_company_id");
    	$type = input("type");
    	$order_number = input("order_number");
    	$product_name = input("product_name");
    	$product_type = input("product_type");
    	$currency_id = input("currency_id");
    	$receivable_money = input("receivable_money");
    	$remark = input("remark");
    	$status = 1;
    	$user_id = session('user')['user_id'];
    	$company_order_customer_id = input('company_order_customer_id');
    	$data = [
    			"object_company_id"=>$payment_company_id,
    			'type'=>$type,
    			"company_order_number"=>$order_number,
    			"product_name"=>$product_name,
    			"source_type_id"=>$product_type,
    			"currency_id"=>$currency_id,
    			"money"=>$receivable_money,
    			"remark"=>$remark,
    			"status"=>$status,
    			'user_id'=>$user_id,
    	
    			'company_id'=>session('user')['company_id'],
    			'invoice_number'=>$invoice_number,
    			'invoice_time'=>strtotime($invoice_time)
    	];
    
	
    	$result = $this->callSoaErp('post', '/finance/addCompanyFinanceApprove',$data);

    	return   $result;//['code' => '400', 'msg' => $data];
    }
    
    /**
     *  修改应收Ajax
     */
    public function editReceivableAjax(){
        $receivable_number = input("receivable_number");
        $payment_company_id = input("payment_company_id");
        $order_number = input("order_number");
        $product_name = input("product_name");
        $product_type = input("product_type");
        $currency_id = input("currency_id");
        $receivable_money = input("receivable_money");
        $remark = input("remark");
        $status = input("status");;
        $user_id = session('user')['user_id'];
        $company_order_customer_id = input('company_order_customer_id');
        $data = [
            "receivable_number"=>$receivable_number,
            "payment_company_id"=>$payment_company_id,
            "order_number"=>$order_number,
            "product_name"=>$product_name,
            "product_type"=>$product_type,
            "currency_id"=>$currency_id,
            "receivable_money"=>$receivable_money,
            "remark"=>$remark,
        	'company_order_customer_id'=>$company_order_customer_id,
              "status"=>$status,
            'user_id'=>$user_id,
        ];

        $result = $this->callSoaErp('post', '/finance/updateReceivableByReceivableNumber',$data);
        return   $result;
    }

	/**
	 * 修改应收顾客
	 */
    public function updateReceivableCustomerAjax(){
    	$receivable_number = input('receivable_number');
    	$company_order_number = input('company_order_number');
    	$customer_str = input('customer_str');
    	$customer_arr = explode(',',$customer_str);
    	for($i=0;$i<count($customer_arr);$i++){
    		$data = [
    			'customer_id'=>substr($customer_arr[$i], 0,strpos($customer_arr[$i],'_')),
    			'company_order_customer_id'=>	substr($customer_arr[$i], strpos($customer_arr[$i],'_')+1)
    				
    		];
    		$customer_arr_result[] = $data;
    	}
    	$data_params = [
    		'receivable_number'=>$receivable_number,
    		'company_order_number'=>$company_order_number,
    		'customer_arr'=>$customer_arr_result
    			
    			
    	];

    	$result = $this->callSoaErp('post', '/finance/updateReceivableCustomer',$data_params);
    	return   $result;
    }
    
	//显示应收汇总-批量收款
	public function showReceivableAllManage(){
		//获取实收数据
		$receipts_pay_id = input('receipts_pay_id');
		$receipts_pay_params =[
			'receipts_pay_id'=>$receipts_pay_id		
		];
		$receipts_pay_result = $this->callSoaErp('post', '/finance/getReceiptsPay',$receipts_pay_params);
		$receipts_pay_result = $receipts_pay_result['data'][0];
		
        //搜索
        $data['status'] = 1;
        $team_product_number = input("team_product_number");
        $product_name = input("product_name");
        $receivable_number = input('receivable_number');
        $order_number = input("order_number");
        $currency_id = input("currency_id");
        $begin_time = input("begin_time");
        $end_time = input("end_time");
        $data['is_like'] = 1;
        $approve_status = input('approve_status');
        if(!empty($team_product_number)){
            $data['team_product_number'] = $team_product_number;
        }
        if(!empty($product_name)){
            $data['product_name'] = $product_name;
        }
        if(!empty($receivable_number)){
            $data['receivable_number'] = $receivable_number;
        }
        if(!empty($order_number)){
            $data['order_number'] = $order_number;
        }
        if(!empty($begin_time)){
        	$data['begin_time'] = $begin_time;
        }
        if(!empty($end_time)){
        	$data['end_time'] = $end_time;
        }  
        
        

        /**
         * 获取币种
         */
        $currency_params['status'] = 1;
        $currency_result = $this->callSoaErp('post','/system/getCurrency',$currency_params);
       	
        $data['payment_object_type'] = $receipts_pay_result['object_type'];
        if($receipts_pay_result['object_type']!=4){
        	$data['payment_object_id'] = $receipts_pay_result['object_id'];
        }
     	
        $data['company_id'] = session('user')['company_id'];
        $data = $this->callSoaErp('post','/finance/getReceivable',$data);
        $data = $data['data'];
 

        

        
        
        $receivable_count=0;
        for($i=0;$i<count($data);$i++){
        	if($data[$i]['receivable_money']-$data[$i]['true_receipt']<=0){
        		$receivable_count+=0;
        		$data[$i]['need_receivable_money'] =0;
        	}else{
        		$receivable_count+=$data[$i]['receivable_money']-$data[$i]['true_receipt'];
        		$data[$i]['need_receivable_money'] =$data[$i]['receivable_money']-$data[$i]['true_receipt'];
        	}
        	
        }
        
        if(is_numeric($approve_status)){
        	$new_data = [];
        	if($approve_status == 3){
        
        		for($i=0;$i<count($data);$i++){
        		
        			 
        			if($data[$i]['need_receivable_money']==0){
        				$new_data[] = $data[$i];
        			}
        		}
        	}else{
        		if($approve_status==1){//代表审核中
        			for($i=0;$i<count($data);$i++){
        				if($data[$i]['finance_approve_money']>0){
        					$new_data[] = $data[$i];
        				}
        			}
        		}else{
        			for($i=0;$i<count($data);$i++){
        				if($data[$i]['finance_approve_money'] =='' && $data[$i]['need_receivable_money']!=0){
        					$new_data[] = $data[$i];
        				}
        			}
        		}
        
        	}
        	 
        	 
        
        	$data = $new_data;
        }     
        //获取当前汇率
        $proportion_params = [
        	'opposite_currency_id'=>$receipts_pay_result['base_currency_id'],	
        	'proportion_time'=>help::getLastMonthDay()
        ];
        $proportion_result = $this->callSoaErp('post','/system/getOneCurrencyProportion',$proportion_params);
        $proportion_result= $proportion_result['data'];
   	
        $this->assign('proportion_result',$proportion_result);
       	$this->assign('receipts_pay_result',$receipts_pay_result);
        $this->assign('receivableInfo_data_result',$receivableInfo_data_result['data']);
        $this->assign('receivable_data_result',$data);
        $this->assign('receivable_count',$receivable_count);
        $this->assign('currency_result',$currency_result['data']);
        $this->assign('receivable_currency_id',$use_currency_id);
        $this->assign('receipts_pay_id',$receipts_pay_id);
        return $this->fetch('receivable_all_manage');
		
	}
	//显示应收汇总-批量收款
	public function showReceivableBtoBAllManage(){
		//获取实收数据
		$receipts_pay_id = input('receipts_pay_id');
		$receipts_pay_params =[
				'receipts_pay_id'=>$receipts_pay_id
		];
		$receipts_pay_result = $this->callSoaErp('post', '/finance/getReceiptsPay',$receipts_pay_params);
		$receipts_pay_result = $receipts_pay_result['data'][0];
		
		//搜索
		$data['status'] = 1;

		$data['is_like'] = 1;

		/**
		 * 获取币种
		 */
		$currency_params['status'] = 1;
		$currency_result = $this->callSoaErp('post','/system/getCurrency',$currency_params);
	
		$data['payment_object_type'] = $receipts_pay_result['object_type'];
		if($receipts_pay_result['object_type']!=4){
			$data['payment_object_id'] = $receipts_pay_result['object_id'];
		}
	
		$data['company_id'] = session('user')['company_id'];
	
		$order_number = input('order_number');
		
		if(!empty($order_number)){
			$data['order_number'] = input('order_number');
		}
		$data = $this->callSoaErp('post','/finance/getReceivable',$data);
	
		
		$data = $data['data'];
	
		 
		$receivable_count=0;
		for($i=0;$i<count($data);$i++){
			if($data[$i]['receivable_money']-$data[$i]['true_receipt']<=0){
				$receivable_count+=0;
				$data[$i]['need_receivable_money'] =0;
			}else{
				$receivable_count+=$data[$i]['receivable_money']-$data[$i]['true_receipt'];
				$data[$i]['need_receivable_money'] =$data[$i]['receivable_money']-$data[$i]['true_receipt'];
			}
			 
		}
		//获取当前汇率
		$proportion_params = [
				'opposite_currency_id'=>$receipts_pay_result['base_currency_id'],
				'proportion_time'=>help::getLastMonthDay()
		];
		$proportion_result = $this->callSoaErp('post','/system/getOneCurrencyProportion',$proportion_params);
		$proportion_result= $proportion_result['data'];
	
			
		
		$this->assign('proportion_result',$proportion_result);
		$this->assign('receipts_pay_result',$receipts_pay_result);
		$this->assign('receivableInfo_data_result',$receivableInfo_data_result['data']);
		$this->assign('receivable_data_result',$data);
		$this->assign('receivable_count',$receivable_count);
		$this->assign('currency_result',$currency_result['data']);
		$this->assign('receivable_currency_id',$use_currency_id);
		$this->assign('receipts_pay_id',$receipts_pay_id);
		return $this->fetch('receivable_btob_all_manage');
	
	}	
	
    /**
     *  新增批量添加Ajax
     */
    public function addReceivableInfoAjax(){
       
        $invoice_time = strtotime(input('invoice_time'));
        $invoice_number = input('invoice_number');

        $remark = input('remark');
        $receivable_info_result = input('receivable_info_result');
        $user_id = session('user')['user_id'];
        $attachment = input('attachment');
		$receivable_info_array = explode(',',$receivable_info_result);

		$payment_type = input('payment_type');
		$receivable  =  [];
		for($i=0;$i<count($receivable_info_array);$i++){
			$params['receivable_number'] =substr($receivable_info_array[$i],0,strpos($receivable_info_array[$i],'_'));
			$params['payment_money'] =  substr($receivable_info_array[$i],strpos($receivable_info_array[$i],'_')+1,strrpos($receivable_info_array[$i],'_')-strpos($receivable_info_array[$i],'_')-1);
			$params['base_money'] =  substr($receivable_info_array[$i],strrpos($receivable_info_array[$i],'_')+1);
			$receivable[] = $params;
			
		}
		
        $data = [

     		
			'receivable_info'=>$receivable,
            'user_id'=>$user_id,
			'receipts_pay_id'=>input('receipts_pay_id')
        ];
      
        $result = $this->callSoaErp('post', '/finance/addReceivableInfo',$data);
        $data = [
        		 
        	'system_alert_event_id'=>25,
        
        		 
        		 
        
        ];
         
       // $result =  $this->callSoaErp('post','/system_alert_event/addInStationLetterAndEmail',$data);//getSingleSource
        return   $result;//['code' => '400', 'msg' => $data];
    }

	//显示应付汇总
	public function showMustPayManage(){
        Cookie::set("mustpay_manage",$_SERVER['REQUEST_URI']);
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        //付款方公司名称
        $company_data = [
            'status'=>1
        ];
        $company_data_result = $this->callSoaErp('post', '/system/getCompany',$company_data);
        $this->assign('company_data',$company_data_result['data']);
        //获取货币
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result = $this->callSoaErp('post', '/system/getCurrency',$currency_data);
        $this->assign('currency_data_result',$currency_data_result['data']);
        //搜索
        //获取应收信息
        $data['status'] = 1;
        $team_product_number = input("team_product_number");
        $product_name = input("product_name");
        $cope_number = input('cope_number');
        $supplier_name = input("supplier_name");
        $order_number = input("order_number");
        $object_type = input('object_type');
        $object_id = input('object_id');
        $data['is_like'] = 1;
        if(!empty($team_product_number)){
            $data['team_product_number'] = $team_product_number;
        }
        if(!empty($product_name)){
            $data['product_name'] = $product_name;
        }
        if(!empty($cope_number)){
            $data['cope_number'] = $cope_number;
        }
        if(!empty($supplier_name)){
            $data['supplier_name'] =$supplier_name;
        }
        if(!empty($order_number)){
        	$data['order_number'] =$order_number;
        }
        if(is_numeric($object_type)){
        	$data['receivable_object_type'] =$object_type;
        	 
        	if(is_numeric($object_id)){
        		$data['receivable_object_id'] =$object_id;
        	}
        }
        
        
        $data['company_id'] = session('user')['company_id'];
        $cope_result = $this->callSoaErp('post','/finance/getCope',$data);

        $cope_result_arr = $cope_result['data']['list'];
        
        $this->getPageParams($cope_result);
        $receivable_data_result = $cope_result_arr;
        
        //汇总
        $yingshou = 0;
        $shishou = 0;
        $weishou = 0;
        for($i=0;$i<count($receivable_data_result);$i++){
        
        	if($receivable_data_result[$i]['cope_currency_id'] != session('user')['company_currency_id']){
        		 
        		for($j=0;$j<count($proporation_result);$j++){
        			if($proporation_result[$j]['currency_id'] == $receivable_data_result[$i]['cope_currency_id']){
        
        				$huilv = $proporation_result[$j]['currency_proportion'];
        
        
        				$money =  $receivable_data_result[$i]['cope_money']*$huilv;
        
        				$yingshou=$yingshou+$money;
        
        				$shishou+=$receivable_data_result[$i]['true_receipt']*$huilv;
        
        			}
        		}
        
        	}else{
        		 
        		$yingshou+=$receivable_data_result[$i]['cope_money'];
        		$shishou+=$receivable_data_result[$i]['true_receipt'];
        
        	}
        
        }
        
        
        $weishou = $yingshou-$shishou;        
        
        
        
        $this->assign('yingshou',$yingshou);
        $this->assign('shishou',$shishou);
        $this->assign('weishou',$weishou);
        $this->assign('order_data_result',$order_data_result['data']);
        return $this->fetch('mustpay_manage');
		
	}

	//应付提交操作
    public function addMustPayAjax(){
        $data = Request::instance()->param();
        $data['invoice_time'] = strtotime(input("invoice_time"));
        $data["user_id"] = session('user')['user_id'];
        $data["status"] = 1;

        
      
        $result = $this->callSoaErp('post','/finance/addCope',$data);


        return $result;
    }

    /**
     *  修改应付jax
     */
    public function editMustPayAjax(){
        $data = Request::instance()->param();
        $data['invoice_time'] = strtotime(input("invoice_time"));
        $data["user_id"] = session('user')['user_id'];
        $result = $this->callSoaErp('post', '/finance/updateCopeByCopeNumber',$data);
        return   $result;
    }

	//显示应付汇总-批量付款
	public function showMustPayAllManage(){
		//获取记账数据
		$receipts_pay_id = input('receipts_pay_id');
	
		$receipts_pay_params =[
				'receipts_pay_id'=>$receipts_pay_id
		];
		$receipts_pay_result = $this->callSoaErp('post', '/finance/getReceiptsPay',$receipts_pay_params);
		$receipts_pay_result = $receipts_pay_result['data'][0];
		//获取应付数据
		$cope_data = [
			'status'=>1,


		];
        $cope_number = input('cope_number');
        $product_name = input('product_name');
        $supplier_name = input('supplier_name');
        $team_product_number = input('team_product_number');
        $currency_id = input('currency_id');
        $approve_status = input('approve_status');
        if(!empty($team_product_number)){
        	$cope_data['team_product_number'] = $team_product_number;
        }       
        if(!empty($cope_number)){
            $cope_data['cope_number'] = $cope_number;
        }
        if(!empty($product_name)){
            $cope_data['product_name'] =$product_name;
        }
        if(!empty($supplier_name)){
        	$cope_data['supplier_name'] =$supplier_name;
        }
      
        //获取货币
        $currency_data = [
        		'status'=>1
        ];
        $currency_data_result = $this->callSoaErp('post', '/system/getCurrency',$currency_data);
        if(is_numeric($currency_id)){
        	$cope_data['cope_currency_id'] = $currency_id;
        	$use_currency_id = $currency_id;
        }
        

        
        $cope_data['is_like'] = 1;
        $cope_data['receivable_object_type'] = $receipts_pay_result['object_type'];
        if($receipts_pay_result['object_type']!=4){
        	$cope_data['receivable_object_id'] = $receipts_pay_result['object_id'];
        }

        $cope_data['company_id'] = session('user')['company_id'];
        
	
		$cope_data_result = $this->callSoaErp('post', '/finance/getCope',$cope_data);
		$cope_data_result = $cope_data_result['data'];


		$cope_count=0;
		for($i=0;$i<count($cope_data_result);$i++){
			if($cope_data_result[$i]['cope_money']-$cope_data_result[$i]['true_receipt']<=0){
                $cope_count+=0;
				$cope_data_result[$i]['need_cope_money'] =0;
			}else{
				$cope_count+=$cope_data_result[$i]['cope_money']-$cope_data_result[$i]['true_receipt'];
				$cope_data_result[$i]['need_cope_money'] =$cope_data_result[$i]['cope_money']-$cope_data_result[$i]['true_receipt'];
			}
			 
		}
		if(is_numeric($approve_status)){
			$new_data = [];
			if($approve_status == 3){
		
				for($i=0;$i<count($cope_data_result);$i++){
		
		
					if($cope_data_result[$i]['need_cope_money']==0){
						$new_data[] = $cope_data_result[$i];
					}
				}
			}else{
				if($approve_status==1){//代表审核中
					for($i=0;$i<count($cope_data_result);$i++){
						if($cope_data_result[$i]['finance_approve_money']>0){
							$new_data[] = $cope_data_result[$i];
						}
					}
				}else{
					for($i=0;$i<count($cope_data_result);$i++){
						if($cope_data_result[$i]['finance_approve_money'] =='' && $data[$i]['need_cope_money']!=0){
							$new_data[] = $cope_data_result[$i];
						}
					}
				}
		
			}
		
		
		
			$cope_data_result = $new_data;
		}
		
		//获取当前汇率
		$proportion_params = [
				'opposite_currency_id'=>$receipts_pay_result['base_currency_id'],
				'proportion_time'=>help::getLastMonthDay()
		];
		$proportion_result = $this->callSoaErp('post','/system/getOneCurrencyProportion',$proportion_params);
		$proportion_result= $proportion_result['data'];
		$this->assign('proportion_result',$proportion_result);
		$this->assign('receipts_pay_result',$receipts_pay_result);
		$this->assign('use_currency_id',$use_currency_id);
		$this->assign('currency_result',$currency_data_result['data']);
		$this->assign('cope_data_result',$cope_data_result);
		$this->assign('cope_count',$cope_count);
		$this->assign('receipts_pay_id',$receipts_pay_id);
        return $this->fetch('mustpay_all_manage');
	
	}
	/**
	 *  批量添加 应付AJAX
	 */
	public function addCopeInfoAjax(){
		 
		$invoice_time = strtotime(input('invoice_time'));
		$invoice_number = input('invoice_number');
		$payment_currency_id = input('payment_currency_id');
		 
		
		
		$user_id = session('user')['user_id'];
		 
		
		$receipts_pay_id = input('receipts_pay_id');
		$remark= input('remark');
		$attachment = input('attachment');
		$payment_type = input('payment_type');
		$cope  =  [];
		$cope_info_result = input('cope_info_result');
		$cope_info_array = explode(',',$cope_info_result);
		for($i=0;$i<count($cope_info_array);$i++){
			$params['cope_number'] =substr($cope_info_array[$i],0,strpos($cope_info_array[$i],'_'));
			$params['payment_money'] =  substr($cope_info_array[$i],strpos($cope_info_array[$i],'_')+1,strrpos($cope_info_array[$i],'_')-strpos($cope_info_array[$i],'_')-1);
			$params['base_money'] =  substr($cope_info_array[$i],strrpos($cope_info_array[$i],'_')+1);
			$cope[] = $params;
				
		}

		$data = [
				'voucher_time'=>$invoice_time,
				'voucher_number'=>$invoice_number,
				"payment_currency_id"=>$payment_currency_id,
				'cope_info'=>$cope,
				'user_id'=>$user_id,
				'payment_type'=>$payment_type,
				'remark'=>$remark,
				'attachment'=>$attachment,
				'receipts_pay_id'=>$receipts_pay_id
				
		];
	
		$result = $this->callSoaErp('post', '/finance/addCopeInfo',$data);
		//审核数据后 提交消息提醒
		$where['system_alert_event_id'] = 25;				 
		$this->callSoaErp('post','/system_alert_event/addInStationLetterAndEmail',$where);
		return   $result;//['code' => '400', 'msg' => $data];
	}
	
	
	
	//显示地接报账
	public function showAgentExpenseManage(){

        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $team_product_number = input("team_product_number");
        if(!empty($team_product_number)){
            $data['team_product_number'] = $team_product_number;
        }
        if(is_numeric(input('status'))){
            $data['status'] = input('status');
        }
		$data['company_id'] = session('user')['company_id'];
		$data['is_like'] = 1;
		
		$travel_agency_result = $this->callSoaErp('post', '/finance/getTravelAgencyReimbursement',$data);
   
		//$this->assign('travel_agency_result',$travel_agency_result['data']);
        $this->getPageParams($travel_agency_result);
        return $this->fetch('agent_expense_manage');
		
	}

	//显示新增地接报账
	public function showAgentExpenseAdd(){
		//获取货币
		$currency_params['status'] = 1;
		$currency_result = $this->callSoaErp('post', '/system/getCurrency',$currency_params);
		
		
		
// 		//获取团队产品
 		$team_product_params['status'] = 1;
 		$team_product_params['company_id'] = session('user')['company_id'];
 		$team_product_params['dijie'] = 1;
 		$team_product_result = $this->callSoaErp('post', '/product/getTeamProduct',$team_product_params);
 		
		//通过团队产品编号获取订单号
		$company_order_params = [
			'status'=>1,
			'team_product_id'=>input('team_product_id')
		];
		$company_order_result = $this->callSoaErp('post', '/branchcompany/getCompanyOrderNumberByTeamProductId',$company_order_params);
		
		$this->assign('currency_result',$currency_result['data']);
		$this->assign('team_product_result',$team_product_result['data']);
		$this->assign('company_order_result',$company_order_result['data']);
        return $this->fetch('agent_expense_add_manage');

	}

	/**
	 * 添加地接报账
	 */
	public function AgentExpenseAddAjax(){
		$shouru_arr = json_decode(input('shouru_arr'),true);
		$zhichu_arr = json_decode(input('zhichu_arr'),true);
		

		$team_product_id = input('team_product_id');
		$zhichu_arr_result = [];
		$shouru_arr_result = [];
		for($i=0;$i<count($shouru_arr);$i++){
			$data = [
				'company_order_number'=>$shouru_arr[$i][0],
				'source_type_id'=>$shouru_arr[$i][1],
				'source_name'=>$shouru_arr[$i][2],
				'company_order_customer_id'=>$shouru_arr[$i][3],
				'currency_id'=>$shouru_arr[$i][4],
				'reimbursement_money'=>$shouru_arr[$i][5],
			
						
			];
			$shouru_arr_result[] = $data;
		}
		

		
		for($i=0;$i<count($zhichu_arr);$i++){
			$data = [
				'source_type_id'=>$zhichu_arr[$i][0],
				'supplier_id'=>$zhichu_arr[$i][1],
				'product_name'=>$zhichu_arr[$i][2],
				'cope_currency_id'=>$zhichu_arr[$i][3],
				'price'=>$zhichu_arr[$i][4],
				'unit'=>$zhichu_arr[$i][5],
				'cope_money'=>$zhichu_arr[$i][6],
					
			];
			$zhichu_arr_result[] = $data;
		}

		
		//添加地接报账
		$params = [
			'receivable'=>$shouru_arr_result,
			'cope'=>$zhichu_arr_result,
			'team_product_id'=>$team_product_id	
		];

		$result = $this->callSoaErp('post', '/finance/addTravelAgencyReimbursement',$params);
	
		return $result;

	}
	/**
	 * 修改地接报账 
	 */
	public function AgentExpenseEditAjax(){
		$shouru_arr = json_decode(input('shouru_arr'),true);
		$zhichu_arr = json_decode(input('zhichu_arr'),true);
		$travel_agency_reimbursement = input('travel_agency_reimbursement');
		$team_product_id = input('team_product_id');
		$zhichu_arr_result = [];
		$shouru_arr_result = [];
		for($i=0;$i<count($shouru_arr);$i++){
			$data = [
				'company_order_number'=>$shouru_arr[$i][0],
				'source_type_id'=>$shouru_arr[$i][1],
				'source_name'=>$shouru_arr[$i][2],
				'company_order_customer_id'=>$shouru_arr[$i][3],
				'currency_id'=>$shouru_arr[$i][4],
				'reimbursement_money'=>$shouru_arr[$i][5],
				'receivable_id'=>$shouru_arr[$i][6]
						
		
			];
			$shouru_arr_result[] = $data;
		}
		
		
		
		for($i=0;$i<count($zhichu_arr);$i++){
			$data = [
				'source_type_id'=>$zhichu_arr[$i][0],
				'supplier_id'=>$zhichu_arr[$i][1],
				'product_name'=>$zhichu_arr[$i][2],
				'cope_currency_id'=>$zhichu_arr[$i][3],
				'price'=>$zhichu_arr[$i][4],
				'unit'=>$zhichu_arr[$i][5],
				'cope_money'=>$zhichu_arr[$i][6],
				'travel_agency_reimbursement_cope_id'=>$zhichu_arr[$i][7],
				'cope_number'=>$zhichu_arr[$i][8]
						
			];
			$zhichu_arr_result[] = $data;
		}
		//添加地接报账
		$params = [
			'receivable'=>$shouru_arr_result,
			'cope'=>$zhichu_arr_result,
			'travel_number'=>$travel_agency_reimbursement,
			'team_product_id'=>$team_product_id	
		];
	
		$result = $this->callSoaErp('post', '/finance/updateTravelAgencyReimbursementByTravelAgencyReimbursementNumber',$params);
	
		return $result;		
		
		
	}
	
	//显示修改地接报账
	public function showAgentExpenseEdit(){
		//获取货币
		$currency_params['status'] = 1;
		$currency_result = $this->callSoaErp('post', '/system/getCurrency',$currency_params);
		//获取团队产品
		$team_product_params['status'] = 1;
		$team_product_params['company_id'] = session('user')['company_id'];
		$team_product_params['plur_status'] =1;//成团
		$team_product_result = $this->callSoaErp('post', '/product/getTeamProduct',$team_product_params);
		
		//获取地接报账所有数据
	
		$data['travel_agency_reimbursement_number'] = input('travel_agency_reimbursement');
		
		$travel_agency_result = $this->callSoaErp('post', '/finance/getTravelAgencyReimbursement',$data);
	
		
		
		$travel_agency_result = $travel_agency_result['data'][0];
		

		for($i=0;$i<count($travel_agency_result['receivable_info']);$i++){
			$customer_str='';
			for($j=0;$j<count($travel_agency_result['receivable_info'][$i]['customer_info']);$j++){
				
				if($j==0){
					$customer_str.=$travel_agency_result['receivable_info'][$i]['customer_info'][$j]['company_order_customer_id'];
				}else{
					$customer_str.=','.$travel_agency_result['receivable_info'][$i]['customer_info'][$j]['company_order_customer_id'];
				}
			}
			$travel_agency_result['receivable_info'][$i]['customer_str'] = $customer_str;
		}
	
		for($j=0;$j<count($travel_agency_result['cope_info']);$j++){
			$travel_agency_result['cope_info'][$j]['supplier_info'] = [];
			$supplier_info = [];
			$supplier_params['status']=1;
			$supplier_params['company_id']=session('user')['company_id'];
			$supplier_params['supplier_id']=$travel_agency_result['cope_info'][$j]['supplier_id'];
			$supplier_info = $this->callSoaErp('post', '/source/getSupplier',$supplier_params);
			$travel_agency_result['cope_info'][$j]['supplier_info'] = $supplier_info['data']; 
			
		}
		
		//通过团队产品编号获取订单号
		$company_order_params = [
			'status'=>1,
			'team_product_number'=>$travel_agency_result['team_product_number']
		];
		$company_order_result = $this->callSoaErp('post', '/branchcompany/getCompanyOrderNumberByTeamProductNumber',$company_order_params);
		
	
		//获取供应商数据
		$supplier_result = $this->callSoaErp('post', '/source/getSupplier',['status'=>1]);
		$this->assign('travel_agency_result',$travel_agency_result);
		
		$this->assign('currency_result',$currency_result['data']);
		$this->assign('team_product_result',$team_product_result['data']);
		$this->assign('company_order_result',$company_order_result['data']);
		$this->assign('supplier_result',$supplier_result['data']);
		return $this->fetch('agent_expense_edit_manage');
	
	}

    /**
     * 利润表分公司
     */
    public function showSalesReportManage(){
        //公司
        $Company = $this->callSoaErp('post','/system/getCompany',['status'=>1]);
        $this->assign('CompanyList',$Company['data']);

        //线路类型
        $where['status'] = 1;
        $where['company_id'] = session('user')['company_id'];
        $RouteType = $this->callSoaErp('post','/system/getRouteType',$where);
        $this->assign('RouteType',Arrays::group($RouteType['data'],'type'));

        return $this->fetch('sales_report_manage');
    }
    
    /**
     * 获取应收
     */
    public function getReceivableAjax(){
    	$params = Request::instance()->param();
    	$params['status'] = 1;
    	$params['company_id'] = session('user')['company_id'];
    	$result = $this->callSoaErp('post', '/finance/getReceivable', $params);
    
    	return $result;
    }
    /**
     * 获取应付
     */
    public function getCopeAjax(){
    	$params = Request::instance()->param();
    	$params['status'] = 1;
    	$params['company_id'] = session('user')['company_id'];
    	$result = $this->callSoaErp('post', '/finance/getCope', $params);
    	//error_log()
    	return $result;
    }    
    /**
     * 获取应收详情
     */
    public function getReceivableInfoAjax(){
    
    	$params = Request::instance()->param();
    	$params['company_id']= session('user')['company_id'];
    	$result = $this->callSoaErp('post', '/finance/getReceivableInfo', $params);
    	
    	return $result;
    }
    

    /**
     * 修改销售收款
     */
    public function updateCompanyOrderSaleAjax(){
    	$params = Request::instance()->param();
    	$result = $this->callSoaErp('post', '/branchcompany/updateCompanyOrderSale', $params);
    	
    	return $result;
    }
    /**
     * 修改销售收款根据销售收款应付编号
     * 
     */
    public function updateCompanyOrderSaleByPaymentNumberAjax(){
    	$params = Request::instance()->param();
    	$result = $this->callSoaErp('post', '/branchcompany/updateCompanyOrderSaleByPaymentNumber', $params);
    	 
    	return $result;
    }

    /***
    ** 销售报告-产品
     **/
    public function SalesReportProducts(){
        $params = Request::instance()->param();
        //获取分公司
        $Company = $this->callSoaErp('post','/system/getCompany',['status'=>1]);
        $this->assign('companyList',$Company['data']);


        $params['status'] = 1;
        $params['company_id'] = $params['company_id'] ? : session('user')['company_id'];
        $data = $this->callSoaErp('post','/finance/getSalesReport',$params);
        $this->assign('data',$data['data']);


        if(session('user')['company_id'] == 1){
            $params1 = [
                'status'=>1,
            ];
        }else{
            $params1 = [
                'status'=>1,
                'can_watch_company_id' => $params['company_id'] ? : session('user')['company_id']
            ];
        }
        $type = $this->callSoaErp('post', '/branchcompany/getBranchProductType',$params1);
        $this->assign('type',$type['data']);

        return $this->fetch('sales_report_products');
    }

    /***
     ** 销售报告-团队产品
     **/
    public function SalesReportTeamProducts(){
        $params = Request::instance()->param();
        //获取分公司
        $company = $this->callSoaErp('post','/system/getCompany',['status'=>1]);
        $this->assign('companyList',$company['data']);


        $params['status'] = 1;
        $params['company_id'] = $params['company_id'] ? : session('user')['company_id'];
        $data = $this->callSoaErp('post','/finance/getSalesTeamReport',$params);
        $this->assign('data',$data['data']);


        return $this->fetch('sales_report_team_products');
    }

    /**
     * 销售报表-创建人
     */
    public function SalesReportAgent(){
        $params = Request::instance()->param();
        $params['status'] = 1;
        $params['company_id'] = $params['company_id'] ? : session('user')['company_id'];
        $data = $this->callSoaErp('post','/finance/getSalesReportAgent',$params);
        $this->assign('data',$data['data']);
        return $this->fetch('sales_report_agent');
    }

    /**
     * 修改财务收款的字段状态
     */
    public function updateFinancialPaymentStatus(){
        $params = Request::instance()->param();
        $result = $this->callSoaErp('post', '/finance/updateStatus', $params);

        return $result;
    }

    /**
     * 删除应收详情
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/4/28
     * Time: 10:28
     */
    public function delReceivableInfoAjax()
    {
        $params = Request::instance()->param();
        $params['status'] = 0;
        $result = $this->callSoaErp('post', '/finance/updateStatus', $params);

        return $result;
    }

	/**
	 * 财务审核
	 */
    public function postFinanceApprove(){
    	$params = Request::instance()->param();
    
    	$result = $this->callSoaErp('post', '/finance/postFinanceApprove', $params);
    	
    	//审核数据后 提交消息提醒
    	$where['system_alert_event_id'] = 26;
		
    	
    	$where['finance_approve_id'] = $params['finance_approve_id'];
   
    	$this->callSoaErp('post','/system_alert_event/addInStationLetterAndEmail',$where);
    	return $result;
    }
    /**
     * 财务撤销审核
     */
    public function postRevocationApplyAjax(){
    	$params = Request::instance()->param();
  
    	$result = $this->callSoaErp('post', '/finance/postRevocationApply', $params);
    	 
    	return $result;
    }    
    
    /**
     * 修改财务审核状态
     */
    public function updateFinanceApproveAjax(){
    	$params = Request::instance()->param();
    
    	$result = $this->callSoaErp('post', '/finance/updateFinanceApprove', $params);
    	//审核数据后 提交消息提醒
    	$where['system_alert_event_id'] = 26;
    	
    	 
    	$where['finance_approve_id'] = $params['finance_approve_id'];
    	 
    	$this->callSoaErp('post','/system_alert_event/addInStationLetterAndEmail',$where);
    	return $result;
    }
    
    /**
     * 财务审核
     */
    public function updateFinanceCopeApproveAjax(){
    	$params = Request::instance()->param();
    	
    	$result = $this->callSoaErp('post', '/finance/updateFinanceCopeApprove', $params);
    	//审核数据后 提交消息提醒
    	$where['system_alert_event_id'] = 26;
    	
    	 
    	$where['finance_approve_id'] = $params['finance_approve_id'];
    	 
    	$this->callSoaErp('post','/system_alert_event/addInStationLetterAndEmail',$where);
    	return $result;
    }
    
    /**
     * 往来账财务审核
     */
    public function postCompanyFinanceApproveAjax(){
    	$params = Request::instance()->param();

    	$result = $this->callSoaErp('post', '/finance/postCompanyFinanceApprove', $params);
    	//审核数据后 提交消息提醒
    	$where['system_alert_event_id'] = 26;
    	
    	 
    	$where['finance_approve_id'] = $params['finance_approve_id'];
    	 
    	$this->callSoaErp('post','/system_alert_event/addInStationLetterAndEmail',$where);
    	return $result;
    }

    /**
     * 代理商统计
     */
    public function agentStatistics(){
        $params = Request::instance()->param();

        $params['status'] = 1;
        $params['channel_type'] = 1;
        $params['company_currency_id'] = session('user')['company_currency_id'];
        $params['distributor_company_id'] = session('user')['company_id'];
        $params['page'] = $this->page();
        $params['limit'] = $this->_page_size;
        $params['is_like'] = 1;

        $params['begin_create_time'] = $params['begin_create_time'] ? strtotime($params['begin_create_time']) : '';
        $params['end_create_time'] = $params['end_create_time'] ? strtotime($params['end_create_time']) : '';
        $params['begin_begin_time'] = $params['begin_begin_time'] ? strtotime($params['begin_begin_time']) : '';
        $params['end_begin_time'] = $params['end_begin_time'] ? strtotime($params['end_begin_time']) : '';

        $result = $this->callSoaErp('post','/finance/agentStatistics', $params);

        $this->getPageParams($result);

        return $this->fetch('agent_statistics');
    }
    
    /**
     * 记账列表
     * 
     */
    public function receipts(){
    	$params = Request::instance()->param();
    	$params['company_id'] =session('user')['user_company_id'];

    	
    	$data = [
    			'page'=>$this->page(),
    			'page_size'=>$this->_page_size,
    	];
    	
    	if(isset($params['object_type'])){
    		$data['object_type'] = $params['object_type'];
    	}
    	if(isset($params['type'])){
    		$data['type'] = $params['type'];
    	}    	
    	//$this->assign('receivable_data_result',$receivable_data_result['data']);

    	$result = $this->callSoaErp('post','/finance/getReceiptsPay', $data);
    	
    	
    	$this->getPageParams($result);
    	return $this->fetch('receipts');
    }
    /**
     * 记账明细列表
     *
     */
    public function receiptsInfo(){
    	$params = Request::instance()->param();

    
    	 
    	$data = [
    		'page'=>$this->page(),
    		'page_size'=>$this->_page_size,
    		'receipts_pay_id'=>$params['receipts_pay_id']	
    	];
    	 

    	//$this->assign('receivable_data_result',$receivable_data_result['data']);
    
    	$result = $this->callSoaErp('post','/finance/getFinanceApprove', $data);
	
    	$this->getPageParams($result);
    	return $this->fetch('receipts_info');
    }   
    /**
     * 新建实收
     */
    public function receiptsAdd(){
    
    	//获取币种
    	$currency_params = [
    		'status'=>1	
    	];
    	$currency_result = $this->callSoaErp('post','/system/getCurrency', $currency_params);
    	
    	//获取本公司币种
		//获取所有公司对本公司币种的汇率
		$proporation_params = [
			'opposite_currency_id'=>session('user')['company_currency_id'],
			'proportion_time'=>help::getLastMonthDay()
		];
	
		$proporation_result = $this->callSoaErp('post','/system/getOneCurrencyProportion', $proporation_params);
		
		$this->assign('proporation_result',$proporation_result['data']);
    	$this->assign('currency_result',$currency_result['data']);
    	return $this->fetch('receipts_add');
    }
    /**
     * 添加实收AJAX
     */
    public function receiptsAddAjax(){
    	$params = Request::instance()->param();
    	if(!empty($params['voucher_time'])){
    		$params['voucher_time'] = strtotime($params['voucher_time']);
    	}
    	if(!empty($params['deposit_payment_time'])){
    		$params['deposit_payment_time'] = strtotime($params['deposit_payment_time']);
    	}  	
    	
    	
    	if(is_numeric($params['result_currency_id'])){
    		$params['result_currency_id'] = $params['result_currency_id'];
    	}else{
    		$params['result_currency_id']=session('user')['company_currency_id'];
    	}
		
    	$result = $this->callSoaErp('post','/finance/addReceiptsPay', $params);
    //	error_log(print_r($result,1));
    	
    	$data = [
    			 
    			'system_alert_event_id'=>25,

    	];
    	 
    	 $this->callSoaErpTest('post','/system_alert_event/addInStationLetterAndEmail',$data);//getSingleSource

    	return $result;
    }
    
    /**
     * 提交撤销AJAX
     */
    public function postFinanceRevocationAjax(){
    	$params = Request::instance()->param();

    	$result = $this->callSoaErp('post','/finance/postFinanceRevocation', $params);
    	 
    	return $result;
    	
    	
    }
    
}
