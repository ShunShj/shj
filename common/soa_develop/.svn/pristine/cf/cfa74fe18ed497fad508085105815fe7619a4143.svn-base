<?php
namespace app\index\controller;
use app\common\help\Contents;
use app\index\model\b2b_booking\B2bBooking as B2bBookingModel;
use app\index\model\b2b_booking\B2bBookingCustomer;
use app\index\model\b2b_tour\B2bTourCommission;
use think\console\command\make\Model;
use think\Db;


class B2bBooking extends Base
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 创建B2bBooking
     */
    public function addB2bBooking(){
        $params = $this->input();

        $tour_commission_model = new B2bTourCommission();
        $tour_commission_data = $tour_commission_model->getB2bTourCommissionByBtbTourId($params['btb_tour_id']);

        //明细
        $sales_array = [];
        $customer_count = count($params['customer']);
        $arr['qty'] = $customer_count;      //数量
        $arr['is_gst'] = 1;
        $arr['status'] = 'enable';
        if ($tour_commission_data['adult_nett_cn'])
        {
            $arr['product_code_id'] = 2;
            $arr['net_unit_price'] = $tour_commission_data['adult_nett_cn'];               //单价
            $arr['net_total'] = bcmul($arr['net_unit_price'], $customer_count);       //总价
            array_push($sales_array, $arr);
        }

        if ($tour_commission_data['child_wbed_nett_cn'])
        {
            $arr['product_code_id'] = 15;
            $arr['net_unit_price'] = $tour_commission_data['child_wbed_nett_cn'];           //单价
            $arr['net_total'] = bcmul($arr['net_unit_price'], $customer_count);         //总价
            array_push($sales_array, $arr);
        }

        if ($tour_commission_data['child_nbed_nett_cn'])
        {
            $arr['product_code_id'] = 16;
            $arr['net_unit_price'] = $tour_commission_data['child_nbed_nett_cn'];       //单价
            $arr['net_total'] = bcmul($arr['net_unit_price'], $customer_count);       //总价
            array_push($sales_array, $arr);
        }

        if ($tour_commission_data['single_supp'])
        {
            $arr['product_code_id'] = 11;
            $arr['net_unit_price'] = $tour_commission_data['single_supp'];        //单价
            $arr['net_total'] = bcmul($arr['net_unit_price'], $customer_count);    //总价
            array_push($sales_array, $arr);
        }

        if ($tour_commission_data['transfer'])
        {
            //接机
            if ($params['hotel_transfer'] > 0)
            {
                $arr['product_code_id'] = 3;
                $arr['net_unit_price'] = $tour_commission_data['transfer'];        //单价
                $arr['net_total'] = bcmul($arr['net_unit_price'], $customer_count);      //总价
                array_push($sales_array, $arr);
            }
            //送机
            if ($params['airport_transfer'] > 0)
            {
                $arr['product_code_id'] = 4;
                $arr['net_unit_price'] = $tour_commission_data['transfer'];     //单价
                $arr['net_total'] = bcmul($arr['net_unit_price'], $customer_count);          //总价
                array_push($sales_array, $arr);
            }
        }

        if ($tour_commission_data['infant']) {
            $arr['product_code_id'] = 32;
            $arr['net_unit_price'] = $tour_commission_data['infant'];     //单价
            $arr['net_total'] = bcmul($arr['net_unit_price'], $customer_count);   //总价
            array_push($sales_array, $arr);
        }

        if ($tour_commission_data['tipping']) {
            $arr['product_code_id'] = 7;
            $arr['net_unit_price'] = $tour_commission_data['tipping'];    //单价
            $arr['net_total'] = bcmul($arr['net_unit_price'], $customer_count);  //总价
            array_push($sales_array, $arr);
        }

        if ($tour_commission_data['compulsory_program']) {
            $arr['product_code_id'] = 8;
            $arr['net_unit_price'] = $tour_commission_data['compulsory_program'];       //单价
            $arr['net_total'] = bcmul($arr['net_unit_price'], $customer_count);       //总价
            array_push($sales_array, $arr);
        }

        $params['sales'] = $sales_array;
        $model = new B2bBookingModel();
        $result = $model->addB2bBooking($params);
        $this->outPut($result);
    }






    public function getB2bBooking()
    {
        $params = $this->input();
        $model = new B2bBookingModel();
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getB2bBooking($params, true);
            $result = $model->getB2bBooking($params,false,'true',$page,$page_size);
        }else{
            $result = $model->getB2bBooking($params);
        }

        if(isset($params['page'])){
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];
            return $this->output($data);
        }else{

            $this->outPut($result);
        }
    }

    public function updateB2bBookingGeneral()
    {
        $params = $this->input();

        $paramRule = [
            'b2b_booking_id'=>'b2b_booking_id',

        ];
        $this->paramCheckRule($paramRule,$params);

        $model = new B2bBookingModel();
        $result = $model->updateB2bBookingGeneral($params);
        $this->outPut($result);
    }

    public function updateB2bTourDates()
    {
        $params = $this->input();

        $model = new B2bBookingCustomer();
        $result = $model->updateB2bBookingCustomer($params);
        $this->outPut($result);
    }


    public function getB2bTourCommission()
    {
        $params = $this->input();

        $model = new B2bTourCommission();
        $result = $model->getB2bTourCommissionByBtbTourId($params['btb_tour_id']);
        $this->outPut($result);
    }


}