<?php
/**
 * Created by PhpStorm.
 * User: 胡
 * Date: 20190/02/19
 * Time: 16:40
 */

namespace app\index\controller;
use \Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;
use app\common\help\Help;

class Customer extends Base
{
	/**
	 * 获取游客AJAX
	 */
    public function getCustomerAjax(){
    	$params = Request::instance()->param();

    	$result = $this->callSoaErp('post', '/branchcompany/getCustomer', $params);
  
    	return $result;
    }
  
}