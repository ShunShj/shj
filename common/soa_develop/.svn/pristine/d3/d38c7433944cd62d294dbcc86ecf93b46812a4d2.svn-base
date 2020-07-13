<?php

namespace app\index\model\b2b_booking;
use app\common\help\Help;
use app\index\model\branchcompany\CompanyOrderAnnex;
use app\index\model\branchcompany\Customer;
use think\Exception;
use think\Model;
use think\Db;
class B2bBooking extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'b2b_tour';
    private $_languageList;
    private $_public_service;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

    /**
     * 添加供应商
     * 胡
     */
    public function addB2bBooking($params){
        $data['company_order_number'] = $params['company_order_number'];
        $data['btb_tour_id'] = $params['btb_tour_id'];
        $data['voucher_number'] = '';   //行程单 自动生成
        $data['agent_id'] = $params['agent_id'];
        $data['language_id'] = $params['language_id'];
        $data['status'] = 1;

        if ($params['agent_reference_id'])
        {
            $data['agent_reference_id'] = $params['agent_reference_id'];        //代理商相关ID
        }


        if ($params['tour_voucher'])
        {
            $data['tour_voucher'] = $params['tour_voucher'];        //UPLOAD TOUR VOUCHE
        }

        if ($params['bus_no'])
        {
            $data['bus_no'] = $params['bus_no'];
        }

        if ($params['seat_group'])
        {
            $data['seat_group'] = $params['seat_group'];
        }


        if ($params['emergency_name'])
        {
            $data['emergency_name'] = $params['emergency_name'];      //代理商联系人
        }

        if ($params['emergency_phone'])
        {
            $data['emergency_phone'] = $params['emergency_phone'];      //代理商联系电话
        }

        if ($params['emergency_email'])
        {
            $data['emergency_email'] = $params['emergency_email'];      //代理商邮箱
        }

        if ($params['lead_pax_mobile'])
        {
            $data['lead_pax_mobile'] = $params['lead_pax_mobile'];      //游客联系人
        }

        if ($params['tour_guide'])
        {
            $data['tour_guide'] = $params['tour_guide'];
        }

        if ($params['tour_contact'])
        {
            $data['tour_contact'] = $params['tour_contact'];
        }

        if ($params['is_pre_paid'])
        {
            $data['is_pre_paid'] = $params['is_pre_paid'];
        }

        if ($params['special_requests'])
        {
            $data['special_requests'] = $params['special_requests'];
        }

        if ($params['office_status'])
        {
            $data['office_status'] = $params['office_status'];
        }

        if ($params['booking_status'])
        {
            $data['booking_status'] = $params['booking_status'];
        }

        if ($params['payment_status'])
        {
            $data['payment_status'] = $params['payment_status'];
        }

        if ($params['remark'])
        {
            $data['remark'] = $params['remark'];
        }

    	Db::startTrans();
    	try{
    		$b2b_booking_id = Db::name('b2b_booking')->insertGetId($data);
    		//游客
            $b2b_booking_customer_model = new B2bBookingCustomer();
            $customer_model = new Customer();
            foreach ($params['customer'] as $customer_v)
            {
                $customer_info = $customer_model->getOneCustomerByPassport($customer_v['passport_number']);
                if ($customer_info)
                {
                    //有 修改
                    $customer_v['customer_id'] = $customer_info['customer_id'];
                    $customer_model->updateCustomerByCustomerId($customer_info['customer_id']);
                }
                else
                {
                    //无 添加
                    $customer_id = $customer_model->addCustomer($customer_v);
                    $customer_v['customer_id'] = $customer_id;
                }
                $customer_v['b2b_booking_id'] = $b2b_booking_id;
                $b2b_booking_customer_model->addB2bBookingCustomer($customer_v);
            }


            //房间
            $b2b_booking_room_model = new B2bBookingRoom();
            foreach ($params['room'] as $room_v)
            {
                $room_v['b2b_booking_id'] = $b2b_booking_id;
                $b2b_booking_room_model->addB2bBookingRoom($room_v);
            }

            //接送机
            $b2b_booking_transfer_model = new B2bBookingTransfer();
            foreach ($params['transfer'] as $transfer_v)
            {
                $transfer_v['b2b_booking_id'] = $b2b_booking_id;
                $b2b_booking_transfer_model->addB2bBookingTransfer($transfer_v);
            }

            //提前延后
            $b2b_booking_accommodation_model = new B2bBookingAccommodation();
            foreach ($params['accommodation'] as $accommodation_v)
            {
                $accommodation_v['b2b_booking_id'] = $b2b_booking_id;
                $b2b_booking_accommodation_model->addB2bBookingAccommodation($accommodation_v);
            }

            //提前延后
            $b2b_booking_accommodation_model = new B2bBookingAccommodation();
            foreach ($params['accommodation'] as $accommodation_v)
            {
                $accommodation_v['b2b_booking_id'] = $b2b_booking_id;
                $b2b_booking_accommodation_model->addB2bBookingAccommodation($accommodation_v);
            }

            //附件
            $annex_model = new CompanyOrderAnnex();
            foreach ($params['upload_passport'] as $upload_passport_v)
            {
                $arr = [];
                $arr['company_order_number'] = $params['company_order_number'];
                $arr['url'] = $upload_passport_v['data'];
                $arr['annex_name'] = $upload_passport_v['name'];
                $arr['create_user_id'] = $params['now_user_id'];
                $annex_model->addB2bAnnex($arr);
            }


            //明细
            $b2b_booking_sales_model = new B2bBookingSales();
            $params['myob_sales'] = $params['myob_sales'] = 0.00;
            foreach ($params['sales'] as $sales_v)
            {
                $sales_v['b2b_booking_id'] = $b2b_booking_id;
                $b2b_booking_sales_model->addB2bBookingSales($sales_v);
                if ($sales_v['net_unit_price'] > 0.00)
                {
                    $params['myob_sales'] = bcadd($sales_v['net_unit_price'] + $params['myob_sales']);
                }
                else
                {
                    $params['myob_cost'] = bcadd($sales_v['net_unit_price'] + $params['myob_sales']);
                }
            }

            //invoice
            $b2b_booking_invoice_model = new B2bBookingInvoice();
            //获取erp的应收应付


            $b2b_booking_invoice_model->addB2bBookingInvoice($params);

            $result = 1;
    		// 提交事务
    		Db::commit();
    
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		Db::rollback();
    		\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
    		exit();
    	}
    	
    	return $result;
    }

    public function getB2bBooking($params,$is_count=false,$is_page=false,$page=null,$page_size=20)
    {
        $data = "1=1 and company_order.order_type = 3";
        //订单编号
        if(!empty($params['company_order_number'])){
            $data.= " and company_order.company_order_number= ".$params['company_order_number'];
        }

        //创建时间
        if(!empty($params['create_time'])){
            $data.= " and company_order.create_time = ".$params['create_time'];
        }
        //代理商
        if(!empty($params['agent_name'])){
            $data.= " and distributor.distributor_name like '%".$params['agent_name']."%'";
        }
        //代理商编号
        if(!empty($params['agent_code'])){
            $data.= " and distributor.distributor_code like '%".$params['agent_code']."%'";
        }

        //出发日期
        if(!empty($params['begin_time'])){
            $data.= " and company_order.begin_time = ".$params['begin_time'];
        }
        //处理状态
        if(!empty($params['office_status'])){
            $data.= " and b2b_booking.office_status = ".$params['office_status'];
        }
        //订单状态
        if(!empty($params['booking_status'])){
            $data.= " and b2b_booking.booking_status = ".$params['booking_status'];
        }
        //付款状态
        if(!empty($params['payment_status'])){
            $data.= " and b2b_booking.payment_status = ".$params['payment_status'];
        }

        try
        {
            if($is_count==true){
                $result = $this->table("b2b_booking")->alias("b2b_booking")->
                join("company_order", "company_order.company_order_number = b2b_booking.company_order_number")->
                where($data)->count();
            }else {
                if ($is_page == true) {
                    $result = $this->table("b2b_booking")->alias('b2b_booking')->
                    join("company_order", "company_order.company_order_number = b2b_booking.company_order_number")->
                    join("b2b_tour", "b2b_tour.btb_tour_id = b2b_booking.btb_tour_id")->
                    join("distributor", "distributor.distributor_id = b2b_booking.agent_id")->
                    where($data)->limit($page, $page_size)->order('company_order.created_time desc')->
                    field(['b2b_booking.*', 'distributor.distributor_name', 'distributor.distributor_code', 'company_order.create_time',
                        "(select count(b2b_booking_customer_id) from b2b_booking_customer where b2b_booking_customer.b2b_booking_id = b2b_booking.b2b_booking_id)"=>'customer_count', 'b2b_tour.tour_name', 'b2b_tour.tour_name_ch', 'b2b_tour.tour_code', 'company_order.begin_time'
                    ])->select();
                }else{
                    $result = $this->table("b2b_booking")->alias('b2b_booking')->
                    join("company_order", "company_order.company_order_number = b2b_booking.company_order_number")->
                    join("b2b_tour", "b2b_tour.btb_tour_id = b2b_booking.btb_tour_id")->
                    join("distributor", "distributor.distributor_id = b2b_booking.agent_id")->
                    where($data)->order('created_at desc')->
                    field(['b2b_booking.*', 'distributor.distributor_name', 'distributor.distributor_code', 'company_order.create_time',
                        "(select count(b2b_booking_customer_id) from b2b_booking_customer where b2b_booking_customer.b2b_booking_id = b2b_booking.b2b_booking_id)"=>'customer_count', 'b2b_tour.tour_name', 'b2b_tour.tour_name_ch', 'b2b_tour.tour_code', 'company_order.begin_time'
                    ])->select();
                }
            }
        }
        catch (Exception $e)
        {
            $result = $e->getMessage();
            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();
        }

        return $result;
    }


    public function updateB2bBookingGeneral($params){
        if ($params['agent_id'])
        {
            $data['agent_id'] = $params['agent_id'];        //代理商id
        }
        if ($params['agent_reference_id'])
        {
            $data['agent_reference_id'] = $params['agent_reference_id'];        //代理商相关ID
        }

        if ($params['tour_code'])
        {
            $data['tour_code'] = $params['tour_code'];        //tour_code
        }

        if ($params['tour_name'])
        {
            $data['tour_name'] = $params['tour_name'];        //tour_name
        }

        if ($params['language_id'])
        {
            $data['language_id'] = $params['language_id'];        //language_id
        }

        if ($params['tour_voucher'])
        {
            $data['tour_voucher'] = $params['tour_voucher'];        //UPLOAD TOUR VOUCHE
        }

        if ($params['bus_no'])
        {
            $data['bus_no'] = $params['bus_no'];
        }

        if ($params['seat_group'])
        {
            $data['seat_group'] = $params['seat_group'];
        }

        if ($params['emergency_name'])
        {
            $data['emergency_name'] = $params['emergency_name'];      //代理商联系人
        }

        if ($params['emergency_phone'])
        {
            $data['emergency_phone'] = $params['emergency_phone'];      //代理商联系电话
        }

        if ($params['emergency_email'])
        {
            $data['emergency_email'] = $params['emergency_email'];      //代理商邮箱
        }

        if ($params['lead_pax_mobile'])
        {
            $data['lead_pax_mobile'] = $params['lead_pax_mobile'];      //游客联系人
        }

        if ($params['tour_guide'])
        {
            $data['tour_guide'] = $params['tour_guide'];
        }

        if ($params['tour_contact'])
        {
            $data['tour_contact'] = $params['tour_contact'];
        }

        if ($params['is_pre_paid'])
        {
            $data['is_pre_paid'] = $params['is_pre_paid'];
        }

        if ($params['special_requests'])
        {
            $data['special_requests'] = $params['special_requests'];
        }

        if ($params['office_status'])
        {
            $data['office_status'] = $params['office_status'];
        }

        if ($params['booking_status'])
        {
            $data['booking_status'] = $params['booking_status'];
        }

        if ($params['payment_status'])
        {
            $data['payment_status'] = $params['payment_status'];
        }

        if ($params['remark'])
        {
            $data['remark'] = $params['remark'];
        }

        $company_order_data['update_time'] = time();
        $company_order_data['update_user_id'] = $params['now_user_id'];
        $where = " b2b_booking_id = ". $params['b2b_booking_id'];

    	Db::startTrans();
    	try{
    		Db::name('b2b_booking')->where($where)->update($data);
    		$b2b_booking = $this->where($where)->find();
            Db::name('company_order')->where(['company_order_number' => $b2b_booking['company_order_number']])->update($company_order_data);

    		$result = 1;
    		// 提交事务
    		Db::commit();
    
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		Db::rollback();
    
    	}
    	return $result;
    }


    public function getB2bBookingGeneralById($b2b_booking_id)
    {
        try
        {
            $where['b2b_booking_id'] = $b2b_booking_id;
            $result = $this->table("b2b_booking")->alias('b2b_booking')->
            join("company_order", "company_order.company_order_number = b2b_booking.company_order_number")->
            where($where)->
            field([
                'b2b_tour.*', 'company_order.begin_time', 'company_order.create_time', 'company_order.update_time',
                "(select nickname from user where user.user_id = company_order.create_user_id)"=>'create_user_name',
                "(select nickname from user where user.user_id = company_order.update_user_id)"=>'update_user_name',
            ])->find();
        }
        catch (Exception $e)
        {
            $result = $e->getMessage();
            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();
        }

        return $result;
    }
}