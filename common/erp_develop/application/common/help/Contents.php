<?php

/**
 * 助手函数类，主要就是解决一些公用的函数所用
 */

namespace app\common\help;



/**
 * Description of Helper
 *
 * @author 胡
 */
class Contents {
	private $_lianyi_email;
	/**
	 * 分页显示数量
	 */
	CONST PAGE_SIZE = 20;


	//B2B订单状态
	public static function b2bBookingStatus(){
		return	[1=>'Saved',2=>'Submitted',3=>'Confirmed',4=>'Invoice Paid',5=>'Completed',6=>'Cancelled',7=>'Final',8=>'Waiting for Approval'];
	}
	//B2B处理状态
	public static function b2bOfficeStatus(){
		return	[1=>'Done',2=>'Pending',3=>'Processing'];
	}

	//B2B付款状态
	public static function b2bPayment(){
		return	[1=>'INVOICE PAID',2=>'WAITING FOR PAYMENT'];
	}

	public static function getNexusEmail(){
		$email = [
				['email'=>'@nexusholidays.com'],
				['email'=>'@nexusshanghai.com'],
		];
		return $email;
		
		
	}

}
