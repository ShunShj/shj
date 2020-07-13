<?php

namespace app\index\controller;

use Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;


class Sale extends Base
{
    public $customer_type = [1=>'成人',2=>'占床儿童',3=>'不占床儿童',4=>'老人'];
    public $card_type = [1=>'护照',2=>'身份证'];

}