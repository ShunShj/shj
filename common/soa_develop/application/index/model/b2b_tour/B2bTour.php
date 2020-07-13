<?php

namespace app\index\model\b2b_tour;
use app\common\help\Help;
use think\Exception;
use think\Model;
use think\Db;
class B2bTour extends Model{
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
    public function addB2bTour($params){

        $data['supplier_tour'] = $params['supplier_tour'];

        if ($params['is_inbound'])
        {
            $data['is_inbound'] = $params['is_inbound'];
        }
        $data['tour_type1'] = $data['tour_type2'] = 0;
        if ($params['tour_type1'])
        {
            $data['tour_type1'] = $params['tour_type1'];
        }

        if ($params['tour_type2'])
        {
            $data['tour_type2'] = $params['tour_type2'];
        }

        if ($params['tour_tab'])
        {
            $data['tour_tab'] = $params['tour_tab'];
        }

        if ($params['local_category'])
        {
            $data['local_category'] = $params['local_category'];
        }

        if ($params['tour_code'])
        {
            $data['tour_code'] = $params['tour_code'];
        }

        if ($params['tour_name'])
        {
            $data['tour_name'] = $params['tour_name'];
        }

        if ($params['tour_name_ch'])
        {
            $data['tour_name_ch'] = $params['tour_name_ch'];
        }

        if ($params['length_days'])
        {
            $data['length_days'] = $params['length_days'];
        }

        if ($params['length_nights'])
        {
            $data['length_nights'] = $params['length_nights'];
        }

        if ($params['frequency'])
        {
            $data['frequency'] = $params['frequency'];
        }

        if ($params['start_city'])
        {
            $data['start_city'] = $params['start_city'];
        }

        if ($params['end_city'])
        {
            $data['end_city'] = $params['end_city'];
        }

        if ($params['departure_airport'])
        {
            $data['departure_airport'] = $params['departure_airport'];
        }

        if ($params['arrival_airport'])
        {
            $data['arrival_airport'] = $params['arrival_airport'];
        }

        if ($params['country'])
        {
            $data['country'] = $params['country'];
        }

        if ($params['land_only'])
        {
            $data['land_only'] = $params['land_only'];
        }

        if ($params['minimum_passenger'])
        {
            $data['minimum_passenger'] = $params['minimum_passenger'];
        }

        if ($params['pdf_flyer'])
        {
            $data['pdf_flyer'] = $params['pdf_flyer'];
        }

        if ($params['en_pdf_flyer'])
        {
            $data['en_pdf_flyer'] = $params['en_pdf_flyer'];
        }

        if ($params['terms'])
        {
            $data['terms'] = $params['terms'];
        }

        if ($params['booking_notice'])
        {
            $data['booking_notice'] = $params['booking_notice'];
        }

        if ($params['tour_languages'])
        {
            $data['tour_languages'] = implode(',', $params['tour_languages']);
        }

        if ($params['infant'])
        {
            $data['infant'] = $params['infant'];
        }

        if ($params['child'])
        {
            $data['child'] = $params['child'];
        }

        if ($params['child_without_bed'])
        {
            $data['child_without_bed'] = $params['child_without_bed'];
        }

        if ($params['service_charge_age'])
        {
            $data['service_charge_age'] = $params['service_charge_age'];
        }


    	$data['created_at'] = time();
    	$data['create_user_id'] = $params['now_user_id'];
    	$data['status'] = $params['status'];
        $data['sort'] = 100;

    	Db::startTrans();
    	try{
    		$btb_tour_id = Db::name('b2b_tour')->insertGetId($data);

            $b2b_tour_date_model = new B2bTourDate();

            foreach ($params['date'] as $date_v)
            {
                $date_v['btb_tour_id'] = $btb_tour_id;
                $b2b_tour_date_model->addB2bTourDate($date_v);
            }

            $b2b_tour_itinerary_model = new B2bTourItinerary();
            foreach ($params['itinerary'] as $itinerary_v)
            {
                $itinerary_v['btb_tour_id'] = $btb_tour_id;
                $b2b_tour_itinerary_model->addB2bTourItinerary($itinerary_v);
            }

            $b2b_tour_room_model = new B2bTourRoom();
            foreach ($params['room'] as $room_v)
            {
                $room_v['btb_tour_id'] = $btb_tour_id;
                $b2b_tour_room_model->addB2bTourRoom($room_v);
            }

            $b2b_tour_transfer_model = new B2bTourTransfer();
            foreach ($params['transfer'] as $transfer_v)
            {
                $transfer_v['btb_tour_id'] = $btb_tour_id;
                $b2b_tour_transfer_model->addB2bTourTransfer($transfer_v);
            }

            $b2b_tour_commission_model = new B2bTourCommission();
            $params['commission']['btb_tour_id'] = $btb_tour_id;
            $b2b_tour_commission_model->addB2bTourCommission($params['commission']);

            $b2b_tour_options_model = new B2bTourOptions();
            foreach ($params['options'] as $options_v)
            {
                $options_v['btb_tour_id'] = $btb_tour_id;
                $b2b_tour_options_model->addB2bTourOptions($options_v);
            }

            $b2b_tour_setting_model = new B2bTourSetting();
            $params['setting']['btb_tour_id'] = $btb_tour_id;
            $b2b_tour_setting_model->addB2bTourSetting($params['setting']);
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
    
    public function getB2bTour($params,$is_count=false,$is_page=false,$page=null,$page_size=20)
    {
        $data = "1=1 and branch_product.type = 2";

        if(!empty($params['btb_tour_id'])){
            $data.= " and b2b_tour.btb_tour_id= ".$params['btb_tour_id'];
        }

        if(!empty($params['status'])){
            $data.= " and b2b_tour.status = ".$params['status'];
        }

        if(!empty($params['account_code'])){
            $data.= " and b2b_tour.account_code like '%".$params['account_code']."%'";
        }

        if(!empty($params['tour_name'])){
            $data.= " and b2b_tour.tour_name like '%".$params['tour_name']."%'";
        }

        if(!empty($params['tour_code'])){
            $data.= " and b2b_tour.tour_code like '%".$params['tour_code']."%'";
        }

        if(!empty($params['company_id'])){
            $data.= " and branch_product.company_id = ".$params['company_id'];
        }

        try
        {
            if($is_count==true){
                $result = $this->table("b2b_tour")->alias("b2b_tour")->
                    join("branch_product", "b2b_tour.supplier_tour = branch_product.branch_product_id")->
                    where($data)->count();
            }else {
                if ($is_page == true) {
                    $result = $this->table("b2b_tour")->alias('b2b_tour')->
                    join("branch_product", "b2b_tour.supplier_tour = branch_product.branch_product_id")->
                    where($data)->limit($page, $page_size)->order('created_at desc')->
                    field(['b2b_tour.*',
                        '(select tour_type_name from b2b_tour_type where b2b_tour_type.tour_type_id = b2b_tour.tour_type1) as tour_type_name1',
                        '(select tour_type_name from b2b_tour_type where b2b_tour_type.tour_type_id = b2b_tour.tour_type2) as tour_type_name2'

                    ])->select();
                }else{
                    $date = date('Y-m-d');
                    $result = $this->table("b2b_tour")->alias('b2b_tour')->
                    join("branch_product", "b2b_tour.supplier_tour = branch_product.branch_product_id")->
                    where($data)->order('created_at desc')->
                    field(['b2b_tour.*',
                        '(select tour_type_name from b2b_tour_type where b2b_tour_type.tour_type_id = b2b_tour.tour_type1) as tour_type_name1',
                        '(select tour_type_name from b2b_tour_type where b2b_tour_type.tour_type_id = b2b_tour.tour_type2) as tour_type_name2',
                        '(select IFNULL ((select b2b_tour_date_id from b2b_tour_date where b2b_tour_date.btb_tour_id = b2b_tour.btb_tour_id and b2b_tour_date.status=1 and b2b_tour_date.departure_date > '. $date .' limit 1), 0)) as is_date',
                        '(select adult_nett_cn from b2b_tour_commission where b2b_tour_commission.btb_tour_id = b2b_tour.btb_tour_id) as adult_nett_cn'
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


    public function updateB2bTourGeneral($params){

        if ($params['supplier_tour'])
        {
            $data['supplier_tour'] = $params['supplier_tour'];
        }


        if ($params['is_inbound'])
        {
            $data['is_inbound'] = $params['is_inbound'];
        }

        $data['tour_type1'] = $data['tour_type2'] = 0;

        if ($params['tour_type1'])
        {
            $data['tour_type1'] = $params['tour_type1'];
        }

        if ($params['tour_type2'])
        {
            $data['tour_type2'] = $params['tour_type2'];
        }

        if ($params['tour_tab'])
        {
            $data['tour_tab'] = $params['tour_tab'];
        }

        if ($params['local_category'])
        {
            $data['local_category'] = $params['local_category'];
        }

        if ($params['tour_code'])
        {
            $data['tour_code'] = $params['tour_code'];
        }

        if ($params['tour_name'])
        {
            $data['tour_name'] = $params['tour_name'];
        }

        if ($params['tour_name_ch'])
        {
            $data['tour_name_ch'] = $params['tour_name_ch'];
        }

        if ($params['length_days'])
        {
            $data['length_days'] = $params['length_days'];
        }

        if ($params['length_nights'])
        {
            $data['length_nights'] = $params['length_nights'];
        }

        if ($params['frequency'])
        {
            $data['frequency'] = $params['frequency'];
        }

        if ($params['start_city'])
        {
            $data['start_city'] = $params['start_city'];
        }

        if ($params['end_city'])
        {
            $data['end_city'] = $params['end_city'];
        }

        if ($params['departure_airport'])
        {
            $data['departure_airport'] = $params['departure_airport'];
        }

        if ($params['arrival_airport'])
        {
            $data['arrival_airport'] = $params['arrival_airport'];
        }

        if ($params['country'])
        {
            $data['country'] = $params['country'];
        }

        if ($params['land_only'])
        {
            $data['land_only'] = $params['land_only'];
        }

        if ($params['minimum_passenger'])
        {
            $data['minimum_passenger'] = $params['minimum_passenger'];
        }

        if ($params['pdf_flyer'])
        {
            $data['pdf_flyer'] = $params['pdf_flyer'];
        }

        if ($params['en_pdf_flyer'])
        {
            $data['en_pdf_flyer'] = $params['en_pdf_flyer'];
        }

        if ($params['terms'])
        {
            $data['terms'] = $params['terms'];
        }

        if ($params['booking_notice'])
        {
            $data['booking_notice'] = $params['booking_notice'];
        }

        if ($params['tour_languages'])
        {
            $data['tour_languages'] = implode(',', $params['tour_languages']);
        }

        if ($params['infant'])
        {
            $data['infant'] = $params['infant'];
        }

        if ($params['child'])
        {
            $data['child'] = $params['child'];
        }

        if ($params['child_without_bed'])
        {
            $data['child_without_bed'] = $params['child_without_bed'];
        }

        if ($params['service_charge_age'])
        {
            $data['service_charge_age'] = $params['service_charge_age'];
        }

        if ($params['status'])
        {
            $data['status'] = $params['status'];
        }

        if ($params['sort'])
        {
            $data['sort'] = $params['sort'];
        }

        $data['updated_at'] = time();
        $data['update_user_id'] = $params['now_user_id'];

        $where = " btb_tour_id = ". $params['btb_tour_id'];

    	Db::startTrans();
    	try{
    		Db::name('b2b_tour')->where($where)->update($data);

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


    public function getB2bTourGeneralById($btb_tour_id)
    {
        try
        {
            $where['btb_tour_id'] = $btb_tour_id;
            $result = $this->table("b2b_tour")->alias('b2b_tour')->
            join("branch_product", "b2b_tour.supplier_tour = branch_product.branch_product_id")->
            join("branch_product_route_template", "branch_product_route_template.branch_product_number = branch_product.branch_product_number")->
            join("route_template", "branch_product_route_template.route_number = route_template.route_number")->
            where($where)->
            field(['b2b_tour.*', 'route_template.route_template_id'])->find();
        }
        catch (Exception $e)
        {
            $result = $e->getMessage();
            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();
        }

        return $result;
    }


    public function updateB2bTourAccountCode($params){

        if(isset($params['account_code'])){
            $data['account_code'] = $params['account_code'];
        }

        $where = " btb_tour_id = ". $params['btb_tour_id'];

        Db::startTrans();
        try{
            Db::name('b2b_tour')->where($where)->update($data);

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

}