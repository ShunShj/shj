<?php
/**
 * Created by PhpStorm.
 * User: 胡
 * Date: 2018/8/13
 * Time: 9:24
 */
namespace app\index\controller;

use app\common\help\Help;
use think\helper\Arr;
use \Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;

 
class Bookings extends Base
{

    /**
     * 
     */
    public function showBookingList(){
        return $this->fetch('booking_list');
    }

    /**
     * 
     */
    public function showClientPaymentList(){
        return $this->fetch('client_payment_list');
    }

    public function showAccountPaymentList(){
    	return $this->fetch('accountant_payment_list');
    }
    
    /**
     *
     */
    public function showCostList(){
    	return $this->fetch('cost_list');
    }

    /**
     *
     */
    public function companyOrderManageAdd(){
    	return $this->fetch('company_order_add');
    }

    /**
    *
    */
    public function companyOrderAddvisitor(){
        return $this->fetch('company_order_addvisitor');
    }
     /**
        *
        */
   	public function pdf(){
   		$bill_template_id = input('bill_template_id');
   		$company_order_number = input('company_order_number');
   		$receivable_info_id = input('receivable_info_id');
   		$time = time();
   		//获取当前域名
		$request = Request::instance();
		// 获取当前域名
		$domain = $request->domain();
   	
		shell_exec("xvfb-run --server-args='-screen 0, 1024x768x24' wkhtmltopdf  {$domain}/bookings/pdf?bill_template_id={$bill_template_id}&company_order_number={$company_order_number}&receivable_info_id={$receivable_info_id} /var/www/pdf/{$time}.pdf");
    
		return $this->fetch('pdf');
   	}

}