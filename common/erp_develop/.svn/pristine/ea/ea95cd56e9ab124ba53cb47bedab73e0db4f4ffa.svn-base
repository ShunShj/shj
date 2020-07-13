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

}