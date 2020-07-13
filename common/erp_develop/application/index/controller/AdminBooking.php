<?php
/**
 * Created by PhpStorm.
 * User: Hugh
 * Date: 2019/11/04
 * Time: 13:40
 */

namespace app\index\controller;
use app\common\help\Contents;
use \Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;
use app\common\help\Help;

class AdminBooking extends Base
{


    public function index(){

        $b2bBookingStatus = Contents::b2bBookingStatus();
        $b2bOfficeStatus = Contents::b2bOfficeStatus();
        $b2bPayment = Contents::b2bPayment();
        $this->assign('b2bBookingStatus',$b2bBookingStatus);
        $this->assign('b2bOfficeStatus',$b2bOfficeStatus);
        $this->assign('b2bPayment',$b2bPayment);
        $params = $_GET;

        $w = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        if(!empty($params['s_company_order_number'])){
           $w['company_order_number'] = $params['s_company_order_number'];
        }
        if(!empty($params['s_create_time'])){
           // $w['create_time'] = $params['s_create_time'];
        }
        if(!empty($params['s_begin_time'])){
            $w['begin_time'] = strtotime($params['s_begin_time']);
        }
        if(!empty($params['s_distributor_name'])){
            $w['agent_name'] = $params['s_distributor_name'];
        }
        if(!empty($params['s_distributor_code'])){
            $w['agent_code'] = $params['s_distributor_code'];
        }
        if(!empty($params['s_booking_status'])){
            $w['booking_status'] = $params['s_booking_status'];
        }
        if(!empty($params['s_office_status'])){
            $w['office_status'] = $params['s_office_status'];
        }
        if(!empty($params['s_payment_status'])){
            $w['payment_status'] = $params['s_payment_status'];
        }


        $result = $this->callSoaErp('post', '/b2b_booking/getB2bBooking',$w);
        $this->getPageParams($result);
//        var_dump($result);exit;

        $this->assign('B2bBooking',$result['data']);



        return $this->fetch('list');
    }

    //General
    public function updateBookingFormGeneral(){
        //获取所有的 agent
        $params['choose_company_id'] = session('user')['company_id'];
        $params['status'] = 1;
        $result = $this->callSoaErp('post', '/btob/getBtoBDistributor', $params);
        $this->assign('all_agent',$result['data']);
        //var_dump($result);exit;
        unset($params);unset($result);


        $b2bBookingStatus = Contents::b2bBookingStatus();
        $b2bOfficeStatus = Contents::b2bOfficeStatus();
        $b2bPayment = Contents::b2bPayment();
        $this->assign('b2bBookingStatus',$b2bBookingStatus);
        $this->assign('b2bOfficeStatus',$b2bOfficeStatus);
        $this->assign('b2bPayment',$b2bPayment);


        return $this->fetch('update_booking_form_general');
    }

    //Passenger
    public function updateBookingFormPassenger(){
        return $this->fetch('update_booking_form_passenger');
    }

    //Room
    public function updateBookingFormRoom(){
        return $this->fetch('update_booking_form_room');
    }

    //Transfer
    public function updateBookingFormTransfer(){
        return $this->fetch('update_booking_form_transfer');
    }

    //option
    public function  updateBookingFormOption(){
        return $this->fetch('update_booking_form_option');
    }

    //Accommodation
    public function  updateBookingFormAccommodation(){
        return $this->fetch('update_booking_form_accommodation');
    }

    //Hotel
    public function updateBookingFormHotel(){
        return $this->fetch('update_booking_form_hotel');
    }

    //File
    public function updateBookingFormFile(){
        return $this->fetch('update_booking_form_file');
    }

    //Sales
    public function updateBookingFormSales(){
        return $this->fetch('update_booking_form_sales');
    }

    //后台创建订单
    public function createBookingForm(){
//        echo '<pre>';print_r($_SESSION);exit;

        //获取所有的 agent
        $params['choose_company_id'] = session('user')['company_id'];
        $params['status'] = 1;
        $result = $this->callSoaErp('post', '/btob/getBtoBDistributor', $params);
        $this->assign('all_agent',$result['data']);

        unset($params);unset($result);
        //获取B2Btour
        $data['status'] = 1;
        $data['company_id'] =  session('user')['company_id'];
        $result = $this->callSoaErp('post', '/b2b_tour/getB2bTour',$data);
        $this->assign('all_tours',$result['data']);
        unset($data);unset($result);

        return $this->fetch('create_booking_form');

    }

    public function createBookingFormAjax(){
        //获取产品信息
        $where['btb_tour_id'] = $_POST['s_tour'];
        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourGeneral',$where);
        session::delete('adminBooking_select_tour');
        session::delete('adminBooking_createBookingFormAjax');
        session::set('adminBooking_select_tour',$result['data']);
        session::set('adminBooking_createBookingFormAjax',$_POST);
        return ['code'=>200];
    }

    //后台创建订单
    public function addBookingAjax()
    {



    }

    public function select_date(){
//        echo '<pre>';print_r($_SESSION);exit;

        //获取团日期
        $where['status'] = 1;
        $where['btb_tour_id'] = session('adminBooking_createBookingFormAjax')['s_tour'];
        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourDates',$where);
        $dateOj = [];
        foreach($result['data'] as $date){
            if(date('Y-m-d')<=$date['arrival_date'])
                $dateOj[] = date('d-m-Y',strtotime($date['arrival_date']));
        }
        sort($dateOj);
        $this->assign('dateOj',$dateOj);
        unset($date);unset($result);

        //获取语言
        $where['btb_tour_id'] = session('adminBooking_createBookingFormAjax')['s_tour'];
        $result = $this->callSoaErp('post', '/b2b_tour/getB2bTour',$where);
        $this->assign('sLanguage',explode(',',$result['data'][0]['tour_languages']));
        unset($date);unset($result);
        $where = ['status'=>1];
        $Language = $this->callSoaErp('post','/system/getLanguage',$where);
        $this->assign('Language',$Language['data']);
//        var_dump($Language);exit;
        unset($date);

        return $this->fetch('select_date');
    }

    public function select_dateAjax(){
        session::delete('adminBooking_select_dateAjax');
        session::set('adminBooking_select_dateAjax',$_POST);
        return ['code'=>200];
    }

    public function passenger_details(){
//        echo '<pre>';print_r($_SESSION);exit;
        $where['status'] = 1;
        $where['level'] = 1;
        $nations = $this->callSoaErp('post','/system/getCountry',$where);
        $this->assign('all_nations',$nations['data']);
//        var_dump($nations);exit;

        return $this->fetch('passenger_details');
    }


    //获取年龄
    public function check_age($dob, $tour_date, $tour_id){

        $date_format = date('Y',strtotime($tour_date));
        error_log(print_r($date_format,1));
        $bday = date('Y',strtotime($dob));
        error_log(print_r($bday,1));
        $age = ($date_format-$bday)?($date_format-$bday):1;
        error_log(print_r($age,1));

        $infant = session('adminBooking_select_tour')['infant'];
        $child  = session('adminBooking_select_tour')['child'];

        if($age <= $infant) {
            return "Infant";
        }
        if($age > $child) {
            return "Adult";
        } else {
            return "Child";
        }

    }

    public function passenger_detailsAjax(){
        $post = Request::instance()->param();

        $lname 			=		Arrays::get($post,'lname');
        $fname 			= 		Arrays::get($post,'fname');
        $dob 			= 		Arrays::get($post,'dob');
        $passport 		= 		Arrays::get($post,'passport');
        $nationality 	= 		Arrays::get($post,'nationality');
        $ethnicity 		= 		Arrays::get($post,'ethnicity');
        $gender 		= 		Arrays::get($post,'gender');
        $speaking 		= 		Arrays::get($post,'speaking');
        $temp_id 		= 		array();

        for($i=0;$i<count($lname);$i++){
            $age = $this->check_age($dob[$i],session('adminBooking_select_dateAjax')['s_date'],session('adminBooking_createBookingFormAjax')['s_tour']);
            $age_cat[]  = $age;
            $allow_nbed_arr[] = 1;
            $nation_type[] = 0;
            $temp_id[$i] = $i+1;
        }

        $passenger_info = [
            'temp_id'		=>  $temp_id,
            'lname' 		=> 	$lname,
            'fname' 		=> 	$fname,
            'dob' 			=> 	$dob,
            'passport' 		=> 	$passport,
            'nation_type'	=>  $nation_type,  // Use for pricing
            'nationality' 	=> 	$nationality,
            'ethnicity' 	=> 	$ethnicity,
            'gender' 		=> 	$gender,
            'age_cat'		=> 	$age_cat,
            'allow_nbed'    =>  $allow_nbed_arr,
            'speaking'		=> 	$speaking
        ];

        session::set('adminBooking_passenger_detailsAjax',$passenger_info);
        return ['code'=>200];
    }

    public function room_config(){
//        echo '<pre>';print_r($_SESSION);exit;

        //获取房型
        $where['btb_tour_id'] = session('adminBooking_createBookingFormAjax')['s_tour'];
        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourRoom',$where);
        $this->assign('all_rooms',$result['data']);
//        var_dump($result);
        unset($where);unset($result);

        $where['status'] =1;
        $where['language_id'] = session('user')['language_id'];
        $result = $this->callSoaErp('post', '/system/getRoomTypeAjax', $where);
        $roomType = Arrays::group($result['data'],'room_type_id');
//        var_dump($roomType);exit;
        $this->assign('roomType',$roomType);

        return $this->fetch('room_config');
    }


    public function room_configAjax(){
        session::delete('adminBooking_room_configAjax');
        session::set('adminBooking_room_configAjax',$_POST);
        return ['code'=>200];
    }

    public function clear_room_configAjax(){
        session::delete('adminBooking_room_configAjax');
        return ['code'=>200];
    }


    public function service_requests(){
//        echo '<pre>';print_r($_SESSION);exit;

        //获取接送机信息
        $where['btb_tour_id'] = session('adminBooking_select_tour')['btb_tour_id'];
        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourTransfer',$where);
        $tourTransfer = Arrays::group($result['data'],'type');
//        var_dump($tourTransfer);exit;
        $this->assign('tourTransfer',$tourTransfer);
        unset($where);

        //获取房型
        $where['btb_tour_id'] = session('adminBooking_select_tour')['btb_tour_id'];
        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourRoom',$where);
        $this->assign('all_rooms',$result['data']);
//        var_dump($result['data']);
        unset($where);unset($result);
        $where['status'] =1;
        $where['language_id'] = session('user')['language_id'];
        $result = $this->callSoaErp('post', '/system/getRoomTypeAjax', $where);
        $roomType = Arrays::group($result['data'],'room_type_id');
//        var_dump($roomType);exit;
        $this->assign('roomType',$roomType);

        return $this->fetch('service_requests');
    }


    public function service_requestsAjax(){
        session::delete('adminBooking_service_requestsAjax');
        session::set('adminBooking_service_requestsAjax',$_POST);
        return ['code'=>200];
    }

    public function clear_service_requestsAjax(){
        session::delete('adminBooking_service_requestsAjax');
        return ['code'=>200];
    }

    public function booking_summary(){
//        echo '<pre>';print_r($_SESSION);exit;
        return $this->fetch('booking_summary');
    }

    public function booking_summaryAjax(){
        session::delete('adminBooking_service_booking_summaryAjax');
        session::set('adminBooking_service_booking_summaryAjax',$_POST);
        return ['code'=>200];
    }



}