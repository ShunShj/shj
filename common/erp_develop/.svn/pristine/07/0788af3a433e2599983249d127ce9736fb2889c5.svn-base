<?php
/**
 * Created by PhpStorm.
 * User: Hugh
 * Date: 2019/11/04
 * Time: 13:40
 */

namespace app\index\controller;
use \Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;
use app\common\help\Help;

class MyBooking extends Base
{
    public function index(){

        if($_POST['search_submit'] && $_POST['search_submit']=='Search'){

        }

        $all_booking = [];

        $this->assign('site_title','My Booking');
        $this->assign('all_booking',$all_booking);

        return $this->fetch('/my_booking/index');
    }
}