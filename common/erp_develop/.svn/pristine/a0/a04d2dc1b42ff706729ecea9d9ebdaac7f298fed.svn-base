<?php
/**
 * Created by PhpStorm.
 * User: godwei
 * Date: 2018/9/20
 * Time: 16:40
 */

namespace app\index\controller;
use \Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;
use app\common\help\Help;

class Distributor extends Base
{
  public function getDistributorAjax(){
  	$params = Request::instance()->param();
  	
  	$params['company_id'] = session('user')['company_id'];
  	$result = $this->callSoaErp('post', '/btob/getDistributor', $params);
  	 
  	return $result;
  }
}