<?php
/**
 * Created by PhpStorm.
 * User: jiye
 * Date: 2018/8/13
 * Time: 9:24
 */
namespace app\index\controller;

use think\helper\Arr;
use \Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;

 
class Product extends Base
{
    //团队计划状态
    public $PlanTourState=[1=>'启用',0=>'禁用',3=>'审批'];//,3=>'审批',4=>'审批不通过'

    //成团
    public $is_establish_team_product = [1=>'成团',0=>'未成团'];

    //应付供应商类型
    public  $types = ['a'=>'请选择',2=>'酒店',3=>'用餐',4=>'航班',5=>'邮轮',6=>'签证',7=>'景点',8=>'车辆',9=>'导游',10=>'单项资源',11=>'自费项目',12=>'其他'];  //1=>'团队产品',
    //应收供应商类型
    public  $types2 = ['a'=>'请选择',1=>'团队产品',2=>'酒店',3=>'用餐',4=>'航班',5=>'邮轮',6=>'签证',7=>'景点',8=>'车辆',9=>'导游',10=>'单项资源',11=>'自费项目',12=>'其他'];

    public $room_type = [1=>'双人房',2=>'大床房',3=>'单人房',4=>'加床'];

    public $orderState = [0=>'取消',1=>'未确认',2=>'占位',3=>'已确认'];

    public $customer_type = [1=>'成人',2=>'占床儿童',3=>'不占床儿童',4=>'老人'];


    /**
     * 团队产品
     */
    public function showTeamProductManage(){
        return $this->fetch('team_product_manage');
    }

    /**
     * 团队产品添加界面
     */
    public function showTeamProductAdd(){
        return $this->fetch('team_product_add');
    }

    /**
     * 团队产品添加数据
     */
    public function addTeamProductAjax(){

    }

    /**
     * 团队产品修改界面
     */
    public function showTeamProductEdit(){
        return $this->fetch('team_product_edit');
    }

    /**
     * 团队产品修改数据
     */
    public function editTeamProductAjax(){

    }

    /**
     * 团队产品详情界面
     */
    public function showTeamProductInfo(){
        return $this->fetch('team_product_info');
    }

    /**
    *团队产品成团
    */
    public function chengTuan(){
        $param = Request::instance()->param();
        $w['team_product_number'] = Arrays::get($param,'team_product_number');
        $this->callSoaErp('post','/product/addTeamProductPriceAndCope',$w);

        $where['system_alert_event_id'] = 11;
        $where['company_id'] = session('user')['company_id'];
        $where['team_product_id'] = Arrays::get($param,'team_product_id');
        $this->callSoaErp('post','/system_alert_event/addInStationLetterAndEmail',$where);
    }

    public function TemplateUpdateAjax(){
        $param = Request::instance()->param();

        //基本信息
        $d['route_name'] = Arrays::get($param,'route_name');
        $d['route_type_id'] = Arrays::get($param,'route_type_id');
        $d['status'] = Arrays::get($param,'status',0);
        $d['route_user_id'] = Arrays::get($param,'route_user_id');
        $d['user_id'] = session('user')['user_id'];
        $d['company_id'] = session('user')['company_id'];
        $d['route_template_id'] = Arrays::get($param,'route_template_id');

        //航班
        $d['edit_flight'] = [];
        $d['add_flight'] = [];

        $route_flight_id = Arrays::get($param,'route_flight_id');
        $flight_number = Arrays::get($param,'flight_number');
        $flight_type = Arrays::get($param,'flight_type');
        $fight_start_city = Arrays::get($param,'fight_start_city');
        $fight_start_time = Arrays::get($param,'fight_start_time');
        $fight_end_city = Arrays::get($param,'fight_end_city');
        $fight_end_time = Arrays::get($param,'fight_end_time');
        $fight_the_days = Arrays::get($param,'fight_the_days');

        foreach($flight_number as $ky=>$flight_numberV){
            $ar['the_days'] = $fight_the_days[$ky];
            $ar['flight_number'] = $flight_number[$ky];
            $ar['flight_type'] = empty($flight_type[$ky])?3:$flight_type[$ky];
            $ar['start_city'] = $fight_start_city[$ky];
            $ar['end_city'] = $fight_end_city[$ky];
            $ar['start_time'] = strtotime(date('Y-m-d').' '.$fight_start_time[$ky]);
            $ar['end_time'] = strtotime(date('Y-m-d').' '.$fight_end_time[$ky]);
            $ar['status'] = 1;
            if(!empty($route_flight_id[$ky])){
                $ar['route_flight_id'] = $route_flight_id[$ky];
                $d['edit_flight'][] = $ar;
            }else{
                $ar['route_template_id'] = Arrays::get($param,'route_template_id');
                $d['add_flight'][] = $ar;
            }
            unset($ar);
        }


        //行程
        $d['edit_journey'] = [];
        $d['add_journey'] = [];

        $journey_the_days = Arrays::get($param,'journey_the_days');
        $journey_title = Arrays::get($param,'journey_title');
        $journey_content = Arrays::get($param,'journey_content');
        $journey_traffic = Arrays::get($param,'journey_traffic');
        $journey_stay = Arrays::get($param,'journey_stay');
        $eat_mark = Arrays::get($param,'eat_mark');
        $journey_breakfast = Arrays::get($param,'journey_breakfast');
        $journey_lunch = Arrays::get($param,'journey_lunch');
        $journey_dinner = Arrays::get($param,'journey_dinner');
        $journey_scenic_sport = Arrays::get($param,'journey_scenic_sport');
        $journey_picture = Arrays::get($param,'journey_picture');
        $journey_remark = Arrays::get($param,'journey_remark');
        $route_journey_id = Arrays::get($param,'route_journey_id');
        $country_id = Arrays::get($param,'country_id');

        foreach($journey_the_days as $k=>$v){
            $ar['the_days'] = $journey_the_days[$k];
            $ar['route_journey_title'] = $journey_title[$k];
            $ar['route_journey_content'] = $journey_content[$k];
            $ar['route_journey_traffic'] = $journey_traffic[$k];
            $ar['route_journey_stay'] = $journey_stay[$k];
            $ar['eat_mark'] = empty($eat_mark[$k])?'':implode(',',$eat_mark[$k]);
            $ar['route_journey_breakfast'] = $journey_breakfast[$k];
            $ar['route_journey_lunch'] = $journey_lunch[$k];
            $ar['route_journey_dinner'] = $journey_dinner[$k];
            $ar['route_journey_scenic_sport'] = $journey_scenic_sport[$k];
            $ar['route_journey_picture'] = empty($journey_picture[$k])?'':implode(',',$journey_picture[$k]);
            $ar['route_journey_remark'] = $journey_remark[$k];
            $ar['route_journey_zone'] = $country_id[$k];
            $ar['status'] = 1;

            if(!empty($route_journey_id[$k])){
                $ar['route_journey_id'] = $route_journey_id[$k];
                $d['edit_journey'][] = $ar;
            }else{
                $ar['route_template_id'] = Arrays::get($param,'route_template_id');
                $d['add_journey'][] = $ar;
            }

            unset($ar);
        }


        //回执单模板
        $d['edit_return_receipt'] = [];
        $d['add_return_receipt'] = [];
        $ReturnReceiptTitle = Arrays::get($param,'ReturnReceiptTitle');
        $ReturnReceiptSorting = Arrays::get($param,'ReturnReceiptSorting');
        $ReturnReceiptContent = Arrays::get($param,'ReturnReceiptContent');
        $route_return_receipt_id = Arrays::get($param,'route_return_receipt_id');

//        $d['route_return_receipt_info'] = [];
        foreach($ReturnReceiptTitle as $k=>$v){
            $ar['title'] = $ReturnReceiptTitle[$k];
            $ar['content'] = $ReturnReceiptContent[$k];
            $ar['sorting'] = $ReturnReceiptSorting[$k];
            $ar['status'] = 1;
            if(!empty($route_return_receipt_id[$k])){
                $ar['route_return_receipt_id'] = $route_return_receipt_id[$k];
                $d['edit_return_receipt'][] = $ar;
            }else{
                $ar['route_template_id'] = Arrays::get($param,'route_template_id');
                $d['add_return_receipt'][] = $ar;
            }

            unset($ar);
        }


        //获取资源配置
        $session_hotel = Session::get('sessionTemplateHotelAjax'); //酒店
        $session_dining = Session::get('sessionTemplateDiningAjax'); //用餐
        $session_flight = Session::get('sessionTemplateFlightAjax'); //航班
        $session_cruise = Session::get('sessionTemplateCruiseAjax');//邮轮
        $session_visa = Session::get('sessionTemplateVisaAjax');//签证
        $session_scenic_spot = Session::get('sessionTemplateScenicSpotAjax'); //景点
        $session_vehicle = Session::get('sessionTemplateVehicleAjax'); //车辆
        $session_tourGuide = Session::get('sessionTemplateTourGuideAjax'); //导游
        $session_singleSource = Session::get('sessionTemplateSingleSourceAjax'); //单项资源
        $session_Optional = Session::get('sessionTemplateOptionalAjax'); //自费项目



        //资源
        $d['edit_allocation'] = [];
        $d['add_allocation'] = [];
        //酒店
        $d = $this->setTemplateAllocationInfoUpdate($d,2,$session_hotel,'room_name');
        //用餐
        $d = $this->setTemplateAllocationInfoUpdate($d,3,$session_dining,'dining_name');
        //航班
        $d = $this->setTemplateAllocationInfoUpdate($d,4,$session_flight,'flight_number');
        //邮轮
        $d = $this->setTemplateAllocationInfoUpdate($d,5,$session_cruise,'cruise_name');
        //签证
        $d = $this->setTemplateAllocationInfoUpdate($d,6,$session_visa,'visa_name');
        //景点
        $d = $this->setTemplateAllocationInfoUpdate($d,7,$session_scenic_spot,'scenic_spot_name');
        //车辆
        $d = $this->setTemplateAllocationInfoUpdate($d,8,$session_vehicle,'vehicle_name');
        //导游
        $d = $this->setTemplateAllocationInfoUpdate($d,9,$session_tourGuide,'tour_guide_name');
        //单项资源
        $d = $this->setTemplateAllocationInfoUpdate($d,10,$session_singleSource,'single_source_name');
        //自费项目
        $d = $this->setTemplateAllocationInfoUpdate($d,11,$session_Optional,'own_expense_name');

//        var_dump($d);

        //消息提醒
        $ary['system_alert_event_id'] = 2;
        $ary['route_template_id'] = $d['route_template_id'];
        $ary['company_id'] = session('user')['company_id'];
        $this->callSoaErp('post','/system_alert_event/addInStationLetterAndEmail',$ary);

        return $this->callSoaErp('post','/product/updateRouteTemplateByRouteTemplateId',$d);

    }


    /**
     * 匹配session资源存取
     * @param $d 写入数据
     * @param $supplier_type_id 供应商类型
     * @param $source_data  session数据
     * @param $source_ky 资源ID对应的字段
     * @return mixed 返回写入数据
     */
    public function setTemplateAllocationInfoUpdate($d,$supplier_type_id,$source_data,$source_ky){
        foreach($source_data as $vl){
            $ar['supplier_type_id'] = $supplier_type_id;
            $ar['source_id'] = $vl[$source_ky];
            $ar['payment_currency_id'] = $vl['currency'];
            $ar['source_price'] = $vl['unit_price'];
            $ar['source_count'] = $vl['quantity'];
            $ar['source_total_price'] = $vl['total'];
            $ar['status'] = 1;
            $ar['source_the_days'] = $vl['the_day'];
            if(!empty($vl['team_product_allocation_id'])){
                $ar['route_source_allocation_id'] = $vl['team_product_allocation_id'];
                $d['edit_allocation'][] = $ar;
            }else{
                $ar['route_template_id'] = $d['route_template_id'];
                $d['add_allocation'][] = $ar;
            }

            unset($ar);
        }
        return $d;
    }

    /**
     * 线路模板添加Ajax
     * Hugh
     */
    public function TemplateAddAjax(){
        $param = Request::instance()->param();

        //基本信息
        $d['route_name'] = Arrays::get($param,'route_name');
        $d['route_type_id'] = Arrays::get($param,'route_type_id');
        $d['status'] = Arrays::get($param,'status',0);
        $d['route_user_id'] = Arrays::get($param,'route_user_id');
        $d['user_id'] = session('user')['user_id'];
        $d['company_id'] = session('user')['company_id'];


        //航班
        $flight_number = Arrays::get($param,'flight_number');
        $flight_type = Arrays::get($param,'flight_type');
        $fight_start_city = Arrays::get($param,'fight_start_city');
        $fight_start_time = Arrays::get($param,'fight_start_time');
        $fight_end_city = Arrays::get($param,'fight_end_city');
        $fight_end_time = Arrays::get($param,'fight_end_time');
        $fight_the_days = Arrays::get($param,'fight_the_days');

        //航班
        $d['route_template_flight_info'] = [];
        foreach($flight_number as $ky=>$flight_numberV){
            $ar['the_days'] = $fight_the_days[$ky];
            $ar['flight_number'] = $flight_number[$ky];
            $ar['flight_type'] = empty($flight_type[$ky])?3:$flight_type[$ky];
            $ar['start_city'] = $fight_start_city[$ky];
            $ar['end_city'] = $fight_end_city[$ky];
            $ar['start_time'] = strtotime(date('Y-m-d').' '.$fight_start_time[$ky]);
            $ar['end_time'] = strtotime(date('Y-m-d').' '.$fight_end_time[$ky]);
            $ar['status'] = 1;
            $d['route_template_flight_info'][] = $ar;
            unset($ar);
        }


        //行程
        $journey_the_days = Arrays::get($param,'journey_the_days');
        $journey_title = Arrays::get($param,'journey_title');
        $journey_content = Arrays::get($param,'journey_content');
        $journey_traffic = Arrays::get($param,'journey_traffic');
        $journey_stay = Arrays::get($param,'journey_stay');
        $eat_mark = Arrays::get($param,'eat_mark');
        $journey_breakfast = Arrays::get($param,'journey_breakfast');
        $journey_lunch = Arrays::get($param,'journey_lunch');
        $journey_dinner = Arrays::get($param,'journey_dinner');
        $journey_scenic_sport = Arrays::get($param,'journey_scenic_sport');
        $journey_picture = Arrays::get($param,'journey_picture');
        $journey_remark = Arrays::get($param,'journey_remark');
        $country_id = Arrays::get($param,'country_id');



        $d['route_template_journey_info'] = [];
        foreach($journey_the_days as $k=>$v){
            $ar['the_days'] = $journey_the_days[$k];
            $ar['route_journey_title'] = $journey_title[$k];
            $ar['route_journey_content'] = $journey_content[$k];
            $ar['route_journey_traffic'] = $journey_traffic[$k];
            $ar['route_journey_stay'] = $journey_stay[$k];
            $ar['eat_mark'] = empty($eat_mark[$k])?'':implode(',',$eat_mark[$k]);
            $ar['route_journey_breakfast'] = $journey_breakfast[$k];
            $ar['route_journey_lunch'] = $journey_lunch[$k];
            $ar['route_journey_dinner'] = $journey_dinner[$k];
            $ar['route_journey_scenic_sport'] = $journey_scenic_sport[$k];
            $ar['route_journey_picture'] = empty($journey_picture[$k])?'':implode(',',$journey_picture[$k]);
            $ar['route_journey_remark'] = $journey_remark[$k];
            $ar['route_journey_zone'] = $country_id[$k];
            $ar['status'] = 1;
            $d['route_template_journey_info'][] = $ar;

            unset($ar);
        }

        //回执单模板
        $ReturnReceiptTitle = Arrays::get($param,'ReturnReceiptTitle');
        $ReturnReceiptSorting = Arrays::get($param,'ReturnReceiptSorting');
        $ReturnReceiptContent = Arrays::get($param,'ReturnReceiptContent');

        $d['route_return_receipt_info'] = [];
        foreach($ReturnReceiptTitle as $k=>$v){
            $ar['title'] = $ReturnReceiptTitle[$k];
            $ar['content'] = $ReturnReceiptContent[$k];
            $ar['sorting'] = $ReturnReceiptSorting[$k];
            $d['route_return_receipt_info'][] = $ar;
            unset($ar);
        }

        //获取资源配置
        $session_hotel = Session::get('sessionTemplateHotelAjax'); //酒店
        $session_dining = Session::get('sessionTemplateDiningAjax'); //用餐
        $session_flight = Session::get('sessionTemplateFlightAjax'); //航班
        $session_cruise = Session::get('sessionTemplateCruiseAjax');//邮轮
        $session_visa = Session::get('sessionTemplateVisaAjax');//签证
        $session_scenic_spot = Session::get('sessionTemplateScenicSpotAjax'); //景点
        $session_vehicle = Session::get('sessionTemplateVehicleAjax'); //车辆
        $session_tourGuide = Session::get('sessionTemplateTourGuideAjax'); //导游
        $session_singleSource = Session::get('sessionTemplateSingleSourceAjax'); //单项资源
        $session_Optional = Session::get('sessionTemplateOptionalAjax'); //自费项目

//        var_dump($session_hotel);exit;

        //资源
        $d['route_template_allocation_info'] = [];
        //酒店
        $d = $this->setTemplateAllocationInfo($d,2,$session_hotel,'room_name');
        //用餐
        $d = $this->setTemplateAllocationInfo($d,3,$session_dining,'dining_name');
        //航班
        $d = $this->setTemplateAllocationInfo($d,4,$session_flight,'flight_number');
        //邮轮
        $d = $this->setTemplateAllocationInfo($d,5,$session_cruise,'cruise_name');
        //签证
        $d = $this->setTemplateAllocationInfo($d,6,$session_visa,'visa_name');
        //景点
        $d = $this->setTemplateAllocationInfo($d,7,$session_scenic_spot,'scenic_spot_name');
        //车辆
        $d = $this->setTemplateAllocationInfo($d,8,$session_vehicle,'vehicle_name');
        //导游
        $d = $this->setTemplateAllocationInfo($d,9,$session_tourGuide,'tour_guide_name');
        //单项资源
        $d = $this->setTemplateAllocationInfo($d,10,$session_singleSource,'single_source_name');
        //自费项目
        $d = $this->setTemplateAllocationInfo($d,11,$session_Optional,'own_expense_name');

//        var_dump($d);
        $return =  $this->callSoaErp('post','/product/addRouteTemplate',$d);
//        exit;
        return $return;
        exit;

    }


    /**
     * 匹配session资源存取
     * @param $d 写入数据
     * @param $supplier_type_id 供应商类型
     * @param $source_data  session数据
     * @param $source_ky 资源ID对应的字段
      * @return mixed 返回写入数据
     */
    public function setTemplateAllocationInfo($d,$supplier_type_id,$source_data,$source_ky){
        foreach($source_data as $vl){
            $ar['supplier_type_id'] = $supplier_type_id;
            $ar['source_id'] = $vl[$source_ky];
            $ar['payment_currency_id'] = $vl['currency'];
            $ar['source_price'] = $vl['unit_price'];
            $ar['source_count'] = $vl['quantity'];
            $ar['source_total_price'] = $vl['total'];
            $ar['status'] = 1;
            $ar['source_the_days'] = $vl['the_day'];
            $d['route_template_allocation_info'][] = $ar;
            unset($ar);
        }
        return $d;
    }


    /**
     * 线路模板添加
     * Hugh 18-11-19
     */
    public function showRouteTemplateAdd(){
        Session::delete('sessionTemplateHotelAjax'); //酒店
        Session::delete('sessionTemplateDiningAjax'); //用餐
        Session::delete('sessionTemplateFlightAjax'); //航班
        Session::delete('sessionTemplateCruiseAjax');//邮轮
        Session::delete('sessionTemplateVisaAjax');//签证
        Session::delete('sessionTemplateScenicSpotAjax'); //景点
        Session::delete('sessionTemplateVehicleAjax'); //车辆
        Session::delete('sessionTemplateTourGuideAjax'); //导游
        Session::delete('sessionTemplateSingleSourceAjax'); //单项资源
        Session::delete('sessionTemplateOptionalAjax'); //自费项目

        $where['status'] = 1;
        $where['company_id'] = session('user')['company_id'];
        $list = $this->callSoaErp('post','/system/getRouteType',$where);
        if($list['data']){
            $RouteType = Arrays::group($list['data'],'type');
            $this->assign('RouteType',$RouteType);
        }
        unset($where['company_id']);
        $where['department_id'] = session('user')['department_id'];
        $UserList = $this->callSoaErp('post','/user/getUser',$where);
        if($UserList['data']){
            $this->assign('UserList',$UserList['data']);
        }
        unset($where['department_id']);
        //操作用户
        $this->assign('UserId',session('user')['user_id']);

        //回执单模板
        $where['company_id'] = session('user')['company_id'];
        $ReturnReceiptList = $this->callSoaErp('post','/system/getReturnReceipt',$where);
        if($ReturnReceiptList['data']){
            $this->assign('ReturnReceiptList',$ReturnReceiptList['data']);
        }

        //城市
        $getCountry =$this->callSoaErp('post','/system/getCountry',['status'=>1,'level'=>3]);
        $this->assign('CountryList',$getCountry['data']);

        return $this->fetch('new_route_template_add');
    }

    /***
     * 货币标签
     */
    public function getCurrency2(){
        $Currency = $this->callSoaErp('post','/system/getCurrency',['status'=>1]);
        if(!empty($Currency)){
            $this->assign('CurrencyList',$Currency['data']);
            $this->assign('CurrencyJson',Arrays::group($Currency['data'],'currency_id'));
         }

    }

    /**
     * 线路模板-酒店添加
     * Hugh
     */
    public function showTemplateHotelAdd(){
        //酒店供应商
        $data['status'] = 1;
        $data['supplier_type_id'] = 2;
        $data['company_id'] = session('user')['company_id'];

        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);

        $this->getCurrency2();
        $this->assign('room_type',$this->room_type);

//        return $this->fetch('new_template_hotel_add');
        return $this->fetch('n_template_hotel_add');
    }


    /**
     * 线路模板-酒店资源session
     * Hugh 18-12-17
     */
    public function NsessionTemplateHotelAjax(){
        $post = Request::instance()->param();
//        var_dump($post);exit;
        if(empty($post)){
            Session::delete('sessionTemplateHotelAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $room_name = Arrays::get($post,'room_name');
            $room_type = Arrays::get($post,'room_type');
            $quantity = Arrays::get($post,'quantity');
            $cost_price = Arrays::get($post,'cost_price');
            $unit_price = Arrays::get($post,'unit_price');
            $currency = Arrays::get($post,'currency');
            $symbol = Arrays::get($post,'symbol');
            $total = Arrays::get($post,'total');
            $the_day = Arrays::get($post,'the_day');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $hotel = [];
            foreach($supplier_id as $k=>$v){
                $ar['supplier_id'] = $supplier_id[$k];
                $ar['agent_id'] = $agent_id[$k];
                $ar['room_name'] = $room_name[$k];
                $ar['room_type'] = $room_type[$k];
                $ar['quantity'] = $quantity[$k];
                $ar['cost_price'] = $cost_price[$k];
                $ar['unit_price'] = $unit_price[$k];
                $ar['currency'] = $currency[$k];
                $ar['symbol'] = $symbol[$k];
                $ar['total'] = $total[$k];
                $ar['the_day'] = $the_day[$k];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$k];
                $hotel[] = $ar;
                unset($ar);
            }
            Session::set('sessionTemplateHotelAjax',$hotel);
        }
        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }


    /**
     * 线路模板-酒店资源session
     * Hugh 18-11-19
     */
    public function sessionTemplateHotelAjax(){
        $post = Request::instance()->param();
//        var_dump($post);exit;
        if(empty($post)){
            Session::delete('sessionTemplateHotelAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $room_name = Arrays::get($post,'room_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $room_type = Arrays::get($post,'room_type');
            $hotel = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['room_name'] = $room_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $ar['room_type'] = $room_type[$ky];
                $hotel[] = $ar;
            }
            Session::set('sessionTemplateHotelAjax',$hotel);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /**
     * 线路模板-酒店修改
     * Hugh
     */
    public function showTemplateHotelUpdate(){
        $sessionTemplateHotelAjax = Session::get('sessionTemplateHotelAjax'); //酒店
//        var_dump($sessionTemplateHotelAjax);exit;
        //获取供应商
        $this->getSupplier(2);
        $sessionTemplateHotelAjax =  $this->linkage($sessionTemplateHotelAjax,'/source/getHotel');
        $this->assign('session_hotel',$sessionTemplateHotelAjax);
//        var_dump($session_hotel);exit;

        $this->getCurrency2();
        $this->assign('room_type',$this->room_type);

//        return $this->fetch('new_template_hotel_update');
        return $this->fetch('n_template_hotel_update');
    }

    public function showTemplateHotelInfo(){
        $sessionTemplateHotelAjax = Session::get('sessionTemplateHotelAjax'); //酒店
//        var_dump($sessionTemplateHotelAjax);exit;
        //获取供应商
        $this->getSupplier(2);
        $sessionTemplateHotelAjax =  $this->linkage($sessionTemplateHotelAjax,'/source/getHotel');
        $this->assign('session_hotel',$sessionTemplateHotelAjax);
//        var_dump($session_hotel);exit;

        $this->getCurrency2();
        $this->assign('room_type',$this->room_type);

//        return $this->fetch('new_template_hotel_update');
        return $this->fetch('n_template_hotel_info');
    }


    /***
     * 线路模板-用餐添加
     * Hugh
     */
    public function showTemplateDiningAdd(){
        //用餐供应商
        $data['status'] = 1;
        $data['supplier_type_id'] = 3;
        $data['company_id'] = session('user')['company_id'];

        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);

        $this->getCurrency2();

//        return $this->fetch('new_template_dining_add');
        return $this->fetch('n_template_dining_add');
    }



    /***
     * 线路模板-用餐session
     * Hugh 18-12-17
     */
    public function NsessionTemplateDiningAjax(){
        $post = Request::instance()->param();
//        var_dump($post);exit;
        if(empty($post)){
            Session::delete('sessionTemplateDiningAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $dining_name = Arrays::get($post,'dining_name');
            $standard_type = Arrays::get($post,'standard_type');
            $quantity = Arrays::get($post,'quantity');
           // $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');


            $dining = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['dining_name'] = $dining_name[$ky];
                $ar['standard_type'] = $standard_type[$ky];
                $ar['quantity'] = $quantity[$ky];
//                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $dining[] = $ar;
            }
            Session::set('sessionTemplateDiningAjax',$dining);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /***
     * 线路模板-用餐session
     * Hugh
     */
    public function sessionTemplateDiningAjax(){
        $post = Request::instance()->param();
//        var_dump($post);exit;
        if(empty($post)){
             Session::delete('sessionTemplateDiningAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $dining_name = Arrays::get($post,'dining_name');
            $standard_type = Arrays::get($post,'standard_type');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $dining = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['dining_name'] = $dining_name[$ky];
                $ar['standard_type'] = $standard_type[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $dining[] = $ar;
            }
            Session::set('sessionTemplateDiningAjax',$dining);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /**
     * 线路模板-用餐
     * Hugh
     */
    public function showTemplateDiningUpdate(){
        $sessionTemplateDiningAjax = Session::get('sessionTemplateDiningAjax');
        $this->getSupplier(3);//获取供应商
        $sessionTemplateDiningAjax =  $this->linkage($sessionTemplateDiningAjax,'/source/getDining');
        $this->assign('session_dining',$sessionTemplateDiningAjax);
        $this->getCurrency2();
//        return $this->fetch('new_template_dining_update');
        return $this->fetch('n_template_dining_update');
    }

    public function showTemplateDiningInfo(){
        $sessionTemplateDiningAjax = Session::get('sessionTemplateDiningAjax');
        $this->getSupplier(3);//获取供应商
        $sessionTemplateDiningAjax =  $this->linkage($sessionTemplateDiningAjax,'/source/getDining');
        $this->assign('session_dining',$sessionTemplateDiningAjax);
        $this->getCurrency2();
//        return $this->fetch('new_template_dining_update');
        return $this->fetch('n_template_dining_info');
    }

    /**
     * 线路模板-航班添加
     * Hugh
     */
    public function showTemplateFlightAdd(){
        //航班供应商
        $data['status'] = 1;
        $data['supplier_type_id'] = 4;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);

        $this->getCurrency2();

//        return $this->fetch('new_template_flight_add');
        return $this->fetch('n_template_flight_add');
    }

    /**
     * 线路模板 - 航班session
     * Hugh  18-12-17
     */
    public function NsessionTemplateFlightAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            Session::delete('sessionTemplateFlightAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $flight_number = Arrays::get($post,'flight_number');
            $shipping_space = Arrays::get($post,'shipping_space');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $flight = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['flight_number'] = $flight_number[$ky];
                $ar['shipping_space'] = $shipping_space[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];
                $flight[] = $ar;
                unset($ar);
            }
            Session::set('sessionTemplateFlightAjax',$flight);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /**
     * 线路模板 - 航班session
     * Hugh
     */
    public function sessionTemplateFlightAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            Session::delete('sessionTemplateFlightAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $flight_number = Arrays::get($post,'flight_number');
            $shipping_space = Arrays::get($post,'shipping_space');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $flight = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['flight_number'] = $flight_number[$ky];
                $ar['shipping_space'] = $shipping_space[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $flight[] = $ar;
            }
            Session::set('sessionTemplateFlightAjax',$flight);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /**
     * 线路模板 - 航班编辑
     * Hugh
     */
    public function showTemplateFlightUpdate(){
        $sessionTemplateFlightAjax = Session::get('sessionTemplateFlightAjax');
//        var_dump($session_flight);exit;
        $this->getSupplier(4);//获取供应商
        $sessionTemplateFlightAjax =  $this->linkage($sessionTemplateFlightAjax,'/source/getFlight');
        $this->assign('session_flight',$sessionTemplateFlightAjax);

        $this->getCurrency2();
//        return $this->fetch('new_template_flight_update');
        return $this->fetch('n_template_flight_update');
    }


    public function showTemplateFlightInfo(){
        $sessionTemplateFlightAjax = Session::get('sessionTemplateFlightAjax');
//        var_dump($session_flight);exit;
        $this->getSupplier(4);//获取供应商
        $sessionTemplateFlightAjax =  $this->linkage($sessionTemplateFlightAjax,'/source/getFlight');
        $this->assign('session_flight',$sessionTemplateFlightAjax);

        $this->getCurrency2();
//        return $this->fetch('new_template_flight_update');
        return $this->fetch('n_template_flight_info');
    }

    /***
     * 线路模板 - 邮轮添加
     * Hugh
     */
    public function showTemplateCruiseAdd(){
        //邮轮供应商
        $data['status'] = 1;
        $data['supplier_type_id'] = 5;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);

        $this->getCurrency2();

//        return $this->fetch('new_template_cruise_add');
        return $this->fetch('n_template_cruise_add');
    }


    /***
     * 线路模板 - 邮轮session
     * Hugh
     */
    public function NsessionTemplateCruiseAjax(){
        $post = Request::instance()->param();

        if(empty($post)){
            Session::delete('sessionTemplateCruiseAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $cruise_name = Arrays::get($post,'cruise_name');
            $room_name = Arrays::get($post,'room_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $cruise = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['cruise_name'] = $cruise_name[$ky];
                $ar['room_name'] = $room_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];
                $cruise[] = $ar;
                unset($ar);

            }
            Session::set('sessionTemplateCruiseAjax',$cruise);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /***
     * 线路模板 - 邮轮session
     * Hugh
     */
    public function sessionTemplateCruiseAjax(){
        $post = Request::instance()->param();

        if(empty($post)){
            Session::delete('sessionTemplateCruiseAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $cruise_name = Arrays::get($post,'cruise_name');
            $room_name = Arrays::get($post,'room_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $cruise = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['cruise_name'] = $cruise_name[$ky];
                $ar['room_name'] = $room_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $cruise[] = $ar;
            }
            Session::set('sessionTemplateCruiseAjax',$cruise);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /***
     * 线路模板 - 邮轮编辑
     * Hugh
     */
    public function showTemplateCruiseUpdate(){
        $sessionTemplateCruiseAjax = Session::get('sessionTemplateCruiseAjax');
        $this->getSupplier(5);//获取供应商
        $sessionTemplateCruiseAjax =  $this->linkage($sessionTemplateCruiseAjax,'/source/getCruise');
        $this->assign('session_cruise',$sessionTemplateCruiseAjax);
        $this->getCurrency2();
//        return $this->fetch('new_template_cruise_update');
        return $this->fetch('n_template_cruise_update');

    }

    public function showTemplateCruiseInfo(){
        $sessionTemplateCruiseAjax = Session::get('sessionTemplateCruiseAjax');
        $this->getSupplier(5);//获取供应商
        $sessionTemplateCruiseAjax =  $this->linkage($sessionTemplateCruiseAjax,'/source/getCruise');
        $this->assign('session_cruise',$sessionTemplateCruiseAjax);
        $this->getCurrency2();
//        return $this->fetch('new_template_cruise_update');
        return $this->fetch('n_template_cruise_info');

    }


    /***
     * 线路模板 - 签证添加
     * Hugh
     */
    public function showTemplateVisaAdd(){
        //签证供应商
        $data['status'] = 1;
        $data['supplier_type_id'] = 6;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);

        $this->getCurrency2();
//        return $this->fetch('new_template_visa_add');
        return $this->fetch('n_template_visa_add');
    }

    /**
     * 线路模板 - 签证session
     * Hugh 18-12-17
     */
    public function NsessionTemplateVisaAjax(){
        $post = Request::instance()->param();

        if(empty($post)){
            Session::delete('sessionTemplateVisaAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $visa_name = Arrays::get($post,'visa_name');
            $file_url = Arrays::get($post,'file_url');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $visa = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['visa_name'] = $visa_name[$ky];
                $ar['file_url'] = $file_url[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $visa[] = $ar;
            }

            Session::set('sessionTemplateVisaAjax',$visa);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /**
     * 线路模板 - 签证session
     * Hugh
     */
    public function sessionTemplateVisaAjax(){
        $post = Request::instance()->param();

        if(empty($post)){
            Session::delete('sessionTemplateVisaAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $visa_name = Arrays::get($post,'visa_name');
            $file_url = Arrays::get($post,'file_url');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $visa = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['visa_name'] = $visa_name[$ky];
                $ar['file_url'] = $file_url[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $visa[] = $ar;
            }

            Session::set('sessionTemplateVisaAjax',$visa);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /***
     * 线路模板 - 签证编辑
     * Hugh
     */
    public function showTemplateVisaUpdate(){
        $sessionTemplateVisaAjax = Session::get('sessionTemplateVisaAjax');
        $this->getSupplier(6);//获取供应商
        $sessionTemplateVisaAjax =  $this->linkage($sessionTemplateVisaAjax,'/source/getVisa');
        $this->assign('session_visa',$sessionTemplateVisaAjax);
        $this->getCurrency2();
//        return $this->fetch('new_template_visa_update');
        return $this->fetch('n_template_visa_update');
    }

    public function showTemplateVisaInfo(){
        $sessionTemplateVisaAjax = Session::get('sessionTemplateVisaAjax');
        $this->getSupplier(6);//获取供应商
        $sessionTemplateVisaAjax =  $this->linkage($sessionTemplateVisaAjax,'/source/getVisa');
        $this->assign('session_visa',$sessionTemplateVisaAjax);
        $this->getCurrency2();
//        return $this->fetch('new_template_visa_update');
        return $this->fetch('n_template_visa_info');
    }


    /**
     * 线路模板 - 景点添加
     * Hugh
     */
    public function showTemplateScenicSpotAdd(){
        //景点供应商
        $data['status'] = 1;
        $data['supplier_type_id'] = 7;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);

        $this->getCurrency2();
//        return $this->fetch('new_template_scenic_spot_add');
        return $this->fetch('n_template_scenic_spot_add');
    }

    /***
     * 线路模板 - 景点session
     * Hugh
     */
    public function NsessionTemplateScenicSpotAjax(){
        $post = Request::instance()->param();
//        var_dump($post);exit;
        if(empty($post)){
            Session::delete('sessionTemplateScenicSpotAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $scenic_spot_name = Arrays::get($post,'scenic_spot_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $ScenicSpot = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['scenic_spot_name'] = $scenic_spot_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $ScenicSpot[] = $ar;
                unset($ar);
            }

            Session::set('sessionTemplateScenicSpotAjax',$ScenicSpot);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /***
     * 线路模板 - 景点session
     * Hugh
     */
    public function sessionTemplateScenicSpotAjax(){
        $post = Request::instance()->param();

        if(empty($post)){
            Session::delete('sessionTemplateScenicSpotAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $scenic_spot_name = Arrays::get($post,'scenic_spot_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $ScenicSpot = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['scenic_spot_name'] = $scenic_spot_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $ScenicSpot[] = $ar;
            }

            Session::set('sessionTemplateScenicSpotAjax',$ScenicSpot);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /***
     * 线路模板 - 景点修改
     * Hugh
     */
    public function showTemplateScenicSpotUpdate(){
        $sessionTemplateScenicSpotAjax = Session::get('sessionTemplateScenicSpotAjax');
//        var_dump($sessionTemplateScenicSpotAjax);exit;

        $this->getSupplier(7);//获取供应商
        $sessionTemplateScenicSpotAjax =  $this->linkage($sessionTemplateScenicSpotAjax,'/source/getScenicSpot');
        $this->assign('session_scenic_spot',$sessionTemplateScenicSpotAjax);
        $this->getCurrency2();
//        return $this->fetch('new_template_scenic_spot_update');
        return $this->fetch('n_template_scenic_spot_update');
    }


    public function showTemplateScenicSpotInfo(){
        $sessionTemplateScenicSpotAjax = Session::get('sessionTemplateScenicSpotAjax');
//        var_dump($sessionTemplateScenicSpotAjax);exit;

        $this->getSupplier(7);//获取供应商
        $sessionTemplateScenicSpotAjax =  $this->linkage($sessionTemplateScenicSpotAjax,'/source/getScenicSpot');
        $this->assign('session_scenic_spot',$sessionTemplateScenicSpotAjax);
        $this->getCurrency2();
//        return $this->fetch('new_template_scenic_spot_update');
        return $this->fetch('n_template_scenic_spot_info');
    }


    /***
     * 线路模板 - 车辆添加
     * Hugh
     */
    public function showTemplateVehicleAdd(){
        //车辆供应商
        $data['status'] = 1;
        $data['supplier_type_id'] = 8;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);

        $this->getCurrency2();
//        return $this->fetch('new_template_vehicle_add');
        return $this->fetch('n_template_vehicle_add');
    }

    /***
     * 线路模板 - 车辆session
     * Hugh 18-12-17
     */
    public function NsessionTemplateVehicleAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            Session::delete('sessionTemplateVehicleAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $vehicle_name = Arrays::get($post,'vehicle_name');
            $vehicle_number = Arrays::get($post,'vehicle_number');
            $load = Arrays::get($post,'load');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $Vehicle = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['vehicle_name'] = $vehicle_name[$ky];
                $ar['vehicle_number'] = $vehicle_number[$ky];
                $ar['load'] = $load[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $Vehicle[] = $ar;
                unset($ar);
            }

            Session::set('sessionTemplateVehicleAjax',$Vehicle);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /***
     * 线路模板 - 车辆session
     * Hugh
     */
    public function sessionTemplateVehicleAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
             Session::delete('sessionTemplateVehicleAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $vehicle_name = Arrays::get($post,'vehicle_name');
            $vehicle_number = Arrays::get($post,'vehicle_number');
            $load = Arrays::get($post,'load');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $Vehicle = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['vehicle_name'] = $vehicle_name[$ky];
                $ar['vehicle_number'] = $vehicle_number[$ky];
                $ar['load'] = $load[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $Vehicle[] = $ar;
            }

            Session::set('sessionTemplateVehicleAjax',$Vehicle);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /**
     * 线路模板 - 车辆修改
     * Hugh
     */
    public function showTemplateVehicleUpdate(){
        $sessionTemplateVehicleAjax = Session::get('sessionTemplateVehicleAjax');
        $this->getSupplier(8);//获取供应商
        $sessionTemplateVehicleAjax =  $this->linkage($sessionTemplateVehicleAjax,'/source/getVehicle');
        $this->assign('session_vehicle',$sessionTemplateVehicleAjax);
        $this->getCurrency2();
//        return $this->fetch('new_template_vehicle_update');
        return $this->fetch('n_template_vehicle_update');
    }


    public function showTemplateVehicleInfo(){
        $sessionTemplateVehicleAjax = Session::get('sessionTemplateVehicleAjax');
        $this->getSupplier(8);//获取供应商
        $sessionTemplateVehicleAjax =  $this->linkage($sessionTemplateVehicleAjax,'/source/getVehicle');
        $this->assign('session_vehicle',$sessionTemplateVehicleAjax);
        $this->getCurrency2();
//        return $this->fetch('new_template_vehicle_update');
        return $this->fetch('n_template_vehicle_info');
    }


    /***
     * 线路模板 - 导游添加
     * Hugh
     */
    public function showTemplateTourGuideAdd(){
        $data['status'] = 1;
        $data['supplier_type_id'] = 9;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);

        $this->getCurrency2();

//        return $this->fetch('new_template_tour_guide_add');
        return $this->fetch('n_template_tour_guide_add');
    }


    /**
     * 线路模板 - 导游session
     * Hugh 18-12-17
     */
    public function NsessionTemplateTourGuideAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            Session::delete('sessionTemplateTourGuideAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $tour_guide_name = Arrays::get($post,'tour_guide_name');
            $guide_id_card = Arrays::get($post,'guide_id_card');
            $phone = Arrays::get($post,'phone');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $tourGuide = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['tour_guide_name'] = $tour_guide_name[$ky];
                $ar['guide_id_card'] = $guide_id_card[$ky];
                $ar['phone'] = $phone[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id']= $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $tourGuide[] = $ar;
                unset($ar);
            }

            Session::set('sessionTemplateTourGuideAjax',$tourGuide);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /**
     * 线路模板 - 导游session
     * Hugh
     */
    public function sessionTemplateTourGuideAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
             Session::delete('sessionTemplateTourGuideAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $tour_guide_name = Arrays::get($post,'tour_guide_name');
            $guide_id_card = Arrays::get($post,'guide_id_card');
            $phone = Arrays::get($post,'phone');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $tourGuide = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['tour_guide_name'] = $tour_guide_name[$ky];
                $ar['guide_id_card'] = $guide_id_card[$ky];
                $ar['phone'] = $phone[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id']= $team_product_allocation_id[$ky];
                $tourGuide[] = $ar;
            }

            Session::set('sessionTemplateTourGuideAjax',$tourGuide);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /***
     * 线路模板 - 导游修改
     * Hugh
     */
    public function showTemplateTourGuideUpdate(){
        $sessionTemplateTourGuideAjax = Session::get('sessionTemplateTourGuideAjax');
        $this->getSupplier(9);//获取供应商
        $sessionTemplateTourGuideAjax =  $this->linkage($sessionTemplateTourGuideAjax,'/source/getTourGuide');
        $this->assign('session_tourGuide',$sessionTemplateTourGuideAjax);
//        var_dump($session_tourGuide[0]);exit;

        $this->getCurrency2();

//        return $this->fetch('new_template_tour_guide_update');
        return $this->fetch('n_template_tour_guide_update');
    }

    public function showTemplateTourGuideInfo(){
        $sessionTemplateTourGuideAjax = Session::get('sessionTemplateTourGuideAjax');
        $this->getSupplier(9);//获取供应商
        $sessionTemplateTourGuideAjax =  $this->linkage($sessionTemplateTourGuideAjax,'/source/getTourGuide');
        $this->assign('session_tourGuide',$sessionTemplateTourGuideAjax);
//        var_dump($session_tourGuide[0]);exit;

        $this->getCurrency2();

//        return $this->fetch('new_template_tour_guide_update');
        return $this->fetch('n_template_tour_guide_info');
    }


    /***
     * 线路模板 - 单项资源添加
     * Hugh
     */
    public function showTemplateSingleSourceAdd(){
        $data['status'] = 1;
        $data['supplier_type_id'] = 10;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);

        $this->getCurrency2();
//        return $this->fetch('new_template_single_source_add');
        return $this->fetch('n_template_single_source_add');
    }

    /***
     * 线路模板 - 单项资源 session
     * Hugh
     */
    public function NsessionTemplateSingleSourceAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            Session::delete('sessionTemplateSingleSourceAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $single_source_name = Arrays::get($post,'single_source_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $singleSource = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['single_source_name'] = $single_source_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $singleSource[] = $ar;
                unset($ar);
            }

            Session::set('sessionTemplateSingleSourceAjax',$singleSource);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /***
     * 线路模板 - 单项资源 session
     * Hugh
     */
    public function sessionTemplateSingleSourceAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            Session::delete('sessionTemplateSingleSourceAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $single_source_name = Arrays::get($post,'single_source_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $singleSource = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['single_source_name'] = $single_source_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $singleSource[] = $ar;
            }

            Session::set('sessionTemplateSingleSourceAjax',$singleSource);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /***
     * 线路模板 - 单项资源编辑
     * Hugh
     */
    public function showTemplateSingleSourceUpdate(){
        $sessionTemplateSingleSourceAjax = Session::get('sessionTemplateSingleSourceAjax');
        $this->getSupplier(10);//获取供应商
        $sessionTemplateSingleSourceAjax =  $this->linkage($sessionTemplateSingleSourceAjax,'/source/getSingleSource');
        $this->assign('session_singleSource',$sessionTemplateSingleSourceAjax);
//        var_dump($session_singleSource[0]);exit;
        $this->getCurrency2();
//        return $this->fetch('new_template_single_source_update');
        return $this->fetch('n_template_single_source_update');
    }


    public function showTemplateSingleSourceInfo(){
        $sessionTemplateSingleSourceAjax = Session::get('sessionTemplateSingleSourceAjax');
        $this->getSupplier(10);//获取供应商
        $sessionTemplateSingleSourceAjax =  $this->linkage($sessionTemplateSingleSourceAjax,'/source/getSingleSource');
        $this->assign('session_singleSource',$sessionTemplateSingleSourceAjax);
//        var_dump($session_singleSource[0]);exit;
        $this->getCurrency2();
//        return $this->fetch('new_template_single_source_update');
        return $this->fetch('n_template_single_source_info');
    }

    /***
     * 线路模板 - 自费项目添加
     * Hugh
     */
    public function showTemplateOptionalAdd(){
        $data['status'] = 1;
        $data['supplier_type_id'] = 11;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);

        $this->getCurrency2();
//        return $this->fetch('new_template_ptional_add');
        return $this->fetch('n_template_ptional_add');
    }

    /**
     * 线路模板 - 自费项目session
     * Hugh 18-12-18
     */
    public function NsessionTemplateOptionalAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            Session::delete('sessionTemplateOptionalAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $own_expense_name = Arrays::get($post,'own_expense_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $Optional = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['own_expense_name'] = $own_expense_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $Optional[] = $ar;
            }

            Session::set('sessionTemplateOptionalAjax',$Optional);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }


    /**
     * 线路模板 - 自费项目session
     * Hugh
     */
    public function sessionTemplateOptionalAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            Session::delete('sessionTemplateOptionalAjax');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $own_expense_name = Arrays::get($post,'own_expense_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $Optional = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['own_expense_name'] = $own_expense_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $Optional[] = $ar;
            }

            Session::set('sessionTemplateOptionalAjax',$Optional);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /***
     * 线路模板 - 自费项目
     * Hugh
     */
    public function showTemplateOptionalUpdate(){
        $sessionTemplateOptionalAjax = Session::get('sessionTemplateOptionalAjax');
        $this->getSupplier(11);//获取供应商
        $sessionTemplateOptionalAjax =  $this->linkage($sessionTemplateOptionalAjax,'/source/getOwnExpense');
        $this->assign('session_Optional',$sessionTemplateOptionalAjax);
//        var_dump($session_Optional[1]);exit;
        $this->getCurrency2();
//        return $this->fetch('new_template_optional_update');
        return $this->fetch('n_template_optional_update');
    }

    public function showTemplateOptionalInfo(){
        $sessionTemplateOptionalAjax = Session::get('sessionTemplateOptionalAjax');
        $this->getSupplier(11);//获取供应商
        $sessionTemplateOptionalAjax =  $this->linkage($sessionTemplateOptionalAjax,'/source/getOwnExpense');
        $this->assign('session_Optional',$sessionTemplateOptionalAjax);
//        var_dump($session_Optional[1]);exit;
        $this->getCurrency2();
//        return $this->fetch('new_template_optional_update');
        return $this->fetch('n_template_optional_info');
    }



    /**
     * 删除资源配置
     * Hugh
     */
    public function TemplateSourceDel(){
        $type = input('post.type');
        switch($type){
            case 2:Session::delete('sessionTemplateHotelAjax');break; //酒店
            case 3:Session::delete('sessionTemplateDiningAjax');break; //用餐
            case 4:Session::delete('sessionTemplateFlightAjax');break; //航班
            case 5:Session::delete('sessionTemplateCruiseAjax');break;//邮轮
            case 6:Session::delete('sessionTemplateVisaAjax');break;//签证
            case 7:Session::delete('sessionTemplateScenicSpotAjax');break; //景点
            case 8:Session::delete('sessionTemplateVehicleAjax');break; //车辆
            case 9:Session::delete('sessionTemplateTourGuideAjax');break; //导游
            case 10:Session::delete('sessionTemplateSingleSourceAjax');break; //单项资源
            case 11:Session::delete('sessionTemplateOptionalAjax');break; //自费项目
            default:break;
        }
        echo $type;
    }

    /**
     * 线路模板编辑
     * Hugh
     */
    public function showRouteTemplateEdit(){
        Session::delete('sessionTemplateHotelAjax'); //酒店
        Session::delete('sessionTemplateDiningAjax'); //用餐
        Session::delete('sessionTemplateFlightAjax'); //航班
        Session::delete('sessionTemplateCruiseAjax');//邮轮
        Session::delete('sessionTemplateVisaAjax');//签证
        Session::delete('sessionTemplateScenicSpotAjax'); //景点
        Session::delete('sessionTemplateVehicleAjax'); //车辆
        Session::delete('sessionTemplateTourGuideAjax'); //导游
        Session::delete('sessionTemplateSingleSourceAjax'); //单项资源
        Session::delete('sessionTemplateOptionalAjax'); //自费项目

        $route_template_id = input('get.route_template_id');
        //获取线路模板基本信息
        $RouteTemplate = $this->callSoaErp('post','/product/getRouteTemplate',['route_template_id'=>$route_template_id]);
        if(!empty($RouteTemplate['data'])){
            $this->assign('getRouteTemplate',$RouteTemplate['data'][0]);
        }


        //获取线路行程
        $RouteJourney = $this->callSoaErp('post','/product/getRouteJourney',['status'=>1,'route_template_id'=>$route_template_id]);
        if(!empty($RouteJourney['data']))
            $this->assign('getRouteJourney',$RouteJourney['data']);


        //获取线路航班
        $RouteFlight = $this->callSoaErp('post','/product/getRouteFlight',['status'=>1,'route_template_id'=>$route_template_id]);
        if(!empty($RouteFlight['data']))
            $this->assign('getRouteFlight',$RouteFlight['data']);

//        var_dump($RouteJourney);exit;

        //回执单
        $RouteReturnReceipt = $this->callSoaErp('post','/product/getRouteReturnReceipt',['status'=>1,'route_template_id'=>$route_template_id]);
        if(!empty($RouteReturnReceipt['data']))
            $this->assign('getRouteReturnReceipt',$RouteReturnReceipt['data']);

        //资源
        $this->getRouteTemplateEditResourceConfigure($route_template_id);

        $where['status'] = 1;
        $where['company_id'] =  session('user')['company_id'];
        $list = $this->callSoaErp('post','/system/getRouteType',$where);
        if($list['data']){
            $RouteType = Arrays::group($list['data'],'type');
            $this->assign('RouteType',$RouteType);
        }
        unset($where['company_id']);
        $where['department_id'] = session('user')['department_id'];
        $UserList = $this->callSoaErp('post','/user/getUser',$where);
        if($UserList['data']){
            $this->assign('UserList',$UserList['data']);
        }
        unset($where['department_id']);

        //操作用户
        $this->assign('UserId',session('user')['user_id']);

        //回执单模板
        $where['company_id'] =  session('user')['company_id'];
        $ReturnReceiptList = $this->callSoaErp('post','/system/getReturnReceipt',$where);
        if($ReturnReceiptList['data']){
            $this->assign('ReturnReceiptList',$ReturnReceiptList['data']);
        }

        //城市
        $getCountry =$this->callSoaErp('post','/system/getCountry',['status'=>1,'level'=>3]);
        $this->assign('CountryList',$getCountry['data']);

        return $this->fetch('new_route_template_update');

    }

     //线路模板资源修改获取
     public function getRouteTemplateEditResourceConfigure($route_template_id){
         $where['status'] = 1;
         $where['route_template_id'] = $route_template_id;
         $list = $this->callSoaErp('post','/product/getRouteSourceAllocation',$where);
         if(!empty($list['data'])){
//                var_dump($list['data']);exit;
             $data = Arrays::group($list['data'],'supplier_type_id');

             if($list['data']['hotel']){ //酒店
                 $this->resettingResourceSession2('sessionTemplateHotelAjax',$list['data']['hotel'],2,'/source/getHotel','hotel_id');
             }
             if($list['data']['dining']){ //用餐
                 $this->resettingResourceSession2('sessionTemplateDiningAjax',$list['data']['dining'],3,'/source/getDining','dining_id');
             }
             if($list['data']['flight']){ //航班
                 $this->resettingResourceSession2('sessionTemplateFlightAjax',$list['data']['flight'],4,'/source/getFlight','flight_id');
             }
             if($list['data']['cruise']){ //邮轮
                 $this->resettingResourceSession2('sessionTemplateCruiseAjax',$list['data']['cruise'],5,'/source/getCruise','cruise_id');
             }
             if($list['data']['visa']){ //签证
                 $this->resettingResourceSession2('sessionTemplateVisaAjax',$list['data']['visa'],6,'/source/getVisa','visa_id');
             }
             if($list['data']['scenic_spot']){ //景点
                 $this->resettingResourceSession2('sessionTemplateScenicSpotAjax',$list['data']['scenic_spot'],7,'/source/getScenicSpot','scenic_spot_id');
             }
             if($list['data']['vehicle']){ //车辆
                 $this->resettingResourceSession2('sessionTemplateVehicleAjax',$list['data']['vehicle'],8,'/source/getVehicle','vehicle_id');
             }
             if($list['data']['tour_guide']){ //导游
                 $this->resettingResourceSession2('sessionTemplateTourGuideAjax',$list['data']['tour_guide'],9,'/source/getTourGuide','tour_guide_id');
             }
             if($list['data']['single_source']){//单项资源
                 $this->resettingResourceSession2('sessionTemplateSingleSourceAjax',$list['data']['single_source'],10,'/source/getSingleSource','single_source_id');
             }
             if($list['data']['own_expense']){//自费项目
                 $this->resettingResourceSession2('sessionTemplateOptionalAjax',$list['data']['own_expense'],11,'/source/getOwnExpense','own_expense_id');
             }

         }

         //获取资源配置
         $session_hotel = Session::get('sessionTemplateHotelAjax'); //酒店
         $session_dining = Session::get('sessionTemplateDiningAjax'); //用餐
         $session_flight = Session::get('sessionTemplateFlightAjax'); //航班
         $session_cruise = Session::get('sessionTemplateCruiseAjax');//邮轮
         $session_visa = Session::get('sessionTemplateVisaAjax');//签证
         $session_scenic_spot = Session::get('sessionTemplateScenicSpotAjax'); //景点
         $session_vehicle = Session::get('sessionTemplateVehicleAjax'); //车辆
         $session_tourGuide = Session::get('sessionTemplateTourGuideAjax'); //导游
         $session_singleSource = Session::get('sessionTemplateSingleSourceAjax'); //单项资源
         $session_Optional = Session::get('sessionTemplateOptionalAjax');
//        var_dump($session_dining);exit;

//         var_dump($session_hotel);exit;

         $this->assign('sessionTemplateHotelAjax',$session_hotel);
         $this->assign('sessionTemplateDiningAjax',$session_dining);
         $this->assign('sessionTemplateFlightAjax',$session_flight);
         $this->assign('sessionTemplateCruiseAjax',$session_cruise);
         $this->assign('sessionTemplateVisaAjax',$session_visa);
         $this->assign('sessionTemplateScenicSpotAjax',$session_scenic_spot);
         $this->assign('sessionTemplateVehicleAjax',$session_vehicle);
         $this->assign('sessionTemplateTourGuideAjax',$session_tourGuide);
         $this->assign('sessionTemplateSingleSourceAjax',$session_singleSource);
         $this->assign('sessionTemplateOptionalAjax',$session_Optional);
     }




    /**
     * 线路模板管理页面
     * 王伟 2018-09-03
     */
    public function showRouteTemplateManage(){

        //搜索
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $route_template_id = input('get.route_template_id');
        $route_name = input('get.route_name');
       // $data['route_template_id']=$route_template_id;
        if(!empty($route_template_id)){
            $data['route_template_id'] = $route_template_id;
        }
        if(is_numeric(input('status'))){
            $data['status'] = input('status');
        }
        if(!empty($route_name)){
            $data['route_name'] = $route_name;
        }

        $data['company_id'] =  session('user')['company_id'];
        $route_template_result = $this->callSoaErp('post','/product/getRouteTemplate',$data);
        $this->getPageParams($route_template_result);
        return $this->fetch('route_template_manage');
    }

    /**
     * 线路模板详情显示界面
     */
    public function showRouteTemplateInfo(){
        Session::delete('sessionTemplateHotelAjax'); //酒店
        Session::delete('sessionTemplateDiningAjax'); //用餐
        Session::delete('sessionTemplateFlightAjax'); //航班
        Session::delete('sessionTemplateCruiseAjax');//邮轮
        Session::delete('sessionTemplateVisaAjax');//签证
        Session::delete('sessionTemplateScenicSpotAjax'); //景点
        Session::delete('sessionTemplateVehicleAjax'); //车辆
        Session::delete('sessionTemplateTourGuideAjax'); //导游
        Session::delete('sessionTemplateSingleSourceAjax'); //单项资源
        Session::delete('sessionTemplateOptionalAjax'); //自费项目

        $route_template_id = input('get.route_template_id');
        //获取线路模板基本信息
        $RouteTemplate = $this->callSoaErp('post','/product/getRouteTemplate',['route_template_id'=>$route_template_id]);
        if(!empty($RouteTemplate['data'])){
            $this->assign('getRouteTemplate',$RouteTemplate['data'][0]);
        }


        //获取线路行程
        $RouteJourney = $this->callSoaErp('post','/product/getRouteJourney',['status'=>1,'route_template_id'=>$route_template_id]);
        if(!empty($RouteJourney['data']))
            $this->assign('getRouteJourney',$RouteJourney['data']);


        //获取线路航班
        $RouteFlight = $this->callSoaErp('post','/product/getRouteFlight',['status'=>1,'route_template_id'=>$route_template_id]);
        if(!empty($RouteFlight['data']))
            $this->assign('getRouteFlight',$RouteFlight['data']);

//        var_dump($RouteJourney);exit;

        //回执单
        $RouteReturnReceipt = $this->callSoaErp('post','/product/getRouteReturnReceipt',['status'=>1,'route_template_id'=>$route_template_id]);
        if(!empty($RouteReturnReceipt['data']))
            $this->assign('getRouteReturnReceipt',$RouteReturnReceipt['data']);

        //资源
        $this->getRouteTemplateEditResourceConfigure($route_template_id);

        $where['status'] = 1;
        $where['company_id'] =  session('user')['company_id'];
        $list = $this->callSoaErp('post','/system/getRouteType',$where);
        if($list['data']){
            $RouteType = Arrays::group($list['data'],'type');
            $this->assign('RouteType',$RouteType);
        }
        unset($where['company_id']);
        $where['department_id'] = session('user')['department_id'];
        $UserList = $this->callSoaErp('post','/user/getUser',$where);
        if($UserList['data']){
            $this->assign('UserList',$UserList['data']);
        }
        unset($where['department_id']);

        //操作用户
        $this->assign('UserId',session('user')['user_id']);

        //回执单模板
        $where['company_id'] =  session('user')['company_id'];
        $ReturnReceiptList = $this->callSoaErp('post','/system/getReturnReceipt',$where);
        if($ReturnReceiptList['data']){
            $this->assign('ReturnReceiptList',$ReturnReceiptList['data']);
        }

        //城市
        $getCountry =$this->callSoaErp('post','/system/getCountry',['status'=>1,'level'=>3]);
        $this->assign('CountryList',$getCountry['data']);


        return $this->fetch('route_template_info');

    }

    /**
     * 线路模板显示修改界面
     */
    public function showRouteTemplateEdit2(){

        $route_template_id = input("route_template_id");

        $data = [];
        $data = ["route_template_id"=>$route_template_id];


        // 获取线路类型
        $route_type_data=[
        	'status'=>1,

        ];
        $route_type_data_result = $this->callSoaErp('post','/system/getRouteType',$route_type_data);
        $route_type_user_result = $this->callSoaErp('post', '/user/getUser',$data);
        $route_type_result = $this->callSoaErp('post', '/product/getRouteTemplate',$data);
        
        //获取行程内容数据
        
        //获取航班信息
        $flight_data = [
        	'status'=>1,
        	'route_template_id'=>$route_template_id
        ];
        $flight_result = $this->callSoaErp('post', '/product/getRouteFlight',$flight_data);
       	//获取行程信息
       	$journey_data = [
       			'status'=>1,
       			'route_template_id'=>$route_template_id       			
       			
       	];
       	$journey_result = $this->callSoaErp('post', '/product/getRouteJourney',$journey_data);

        
        //获取线路 资源数据 
        //首先获取酒店数据
        $source_data = [
        	'status'=>1,
        	'route_template_id'=>$route_template_id        		
        ];
        $source_data['supplier_type_id'] = 2;
        $source_data_result['hotel'] = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data)['data'];
        //获取 餐厅数据
		$source_data['supplier_type_id'] = 3;
        $source_data_result['dining'] = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data)['data'];        
        //获取航班数据
        $source_data['supplier_type_id'] = 4;
        $source_data_result['flight'] = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data)['data'];
        //获取游轮数据
        $source_data['supplier_type_id'] = 5;
        $source_data_result['cruise'] = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data)['data'];       
        //获取签证数据
        $source_data['supplier_type_id'] = 6;
        $source_data_result['visa'] = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data)['data'];
        //获取景点数据
        $source_data['supplier_type_id'] = 7;
        $source_data_result['scenic_spot'] = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data)['data'];
        //获取车辆数据
        $source_data['supplier_type_id'] = 8;
        $source_data_result['vehicle'] = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data)['data'];       
        //获取导游数据
        $source_data['supplier_type_id'] = 9;
        $source_data_result['tour_guide'] = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data)['data'];
        //获取单项资源数据
        $source_data['supplier_type_id'] = 10;
        $source_data_result['single_source'] = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data)['data'];

        for($i=0;$i<count($journey_result['data']);$i++){
            $journey_result['data'][$i]['img_list'] = explode(",",$journey_result['data'][$i]['route_journey_picture']);
        }
       
        $this->assign('route_type_result',$route_type_result['data'][0]);
        $this->assign('route_type_user_result',$route_type_user_result['data']);
        $this->assign('route_type_data_result',$route_type_data_result['data']);
        $this->assign('source_data_result',$source_data_result);
 		$this->assign('flight_result',$flight_result['data']);
 		$this->assign('journey_result',$journey_result['data']);


        $this->assign('journey_imgs_result',$journey_imgs);

        return $this->fetch('route_template_edit');
    }

    /**
     * 线路模板修改数据ajax
     */
    public function editRouteTemplateAjax(){
        $route_template_id=input('route_template_id');
        $route_template_name=input('route_name');
        $route_type_id = input("route_type_id");
        $route_user_id = input("route_user_id");
        $status = input("status");
        $user_id = session('user')['user_id'];

        $data = [
            "route_template_id"=>$route_template_id,
            "route_name"=>$route_template_name,
            "route_type_id"=>$route_type_id,
            "route_user_id"=>$route_user_id,
            "status"=>$status,
            "user_id"=>$user_id
        ];
	
        $result = $this->callSoaErp('post', '/product/updateRouteTemplateByRouteTemplateId',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 线路模板添加界面
     */
    public function showRouteTemplateAdd2(){
        // 获取线路类型华人团
        $route_type_data=[
            'status'=>1,
        	'type'=>1	
        ];
        $route_type_data_chinese_result = $this->callSoaErp('post','/system/getRouteType',$route_type_data); 
        $route_type_data=[
        	'status'=>1,
        	'type'=>2
        ];
        $route_type_data_foreigner_result = $this->callSoaErp('post','/system/getRouteType',$route_type_data);
        
        //获取所有人
        $user_data = [
        		'status' => 1
        ];
        $user_data_result = $this->callSoaErp('post', '/user/getUser',$user_data);
        
        $this->assign('route_type_data_chinese_result',$route_type_data_chinese_result['data']);
        $this->assign('route_type_data_foreigner_result',$route_type_data_foreigner_result['data']);
        $this->assign('user_data_result',$user_data_result['data']);
        return $this->fetch('route_template_add');
    }

    /**
     * 线路模板添加数据
     */
    public function addRouteTemplateAjax(){
        $route_name = input("route_name");
        $status = input("status");
        $route_type_id = input("route_type_id");
        $user_id = session('user')['user_id'];

        $data = [
            "route_name"=>$route_name,
            "status"=>$status,
            'route_type_id'=>$route_type_id,
            "route_user_id"=>$user_id,
        	'user_id'=>$user_id
        ]; 
		
        $result = $this->callSoaErp('post', '/product/addRouteTemplate',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }

     /**
     * 线路模板行程添加界面
     */
    public function showRouteJourneyAdd(){
        // 获取线路行程信息
        $route_journey_data=[
            'status'=>1,
            'route_template_id'=>input('route_template_id'),
            'route_journey_id'=>input('route_journey_id')    
        ];
        $route_journey_data_chinese_result = $this->callSoaErp('post','/product/getRouteJourney',$route_journey_data); 
        $this->assign('route_journey_data_chinese_result',$route_journey_data_chinese_result['data']);
        return $this->fetch('route_template_add');
    }
     /**
     * 线路模板行程添加数据
     */
    public function addRouteJourneyAjax(){
        $route_template_id = input("route_template_id");
        $the_days = input("the_days");
        $status = input("status");
        $route_journey_title = input("route_journey_title");
        $route_journey_content = input("route_journey_content");
        $route_journey_traffic = input("route_journey_traffic");
        $route_journey_stay = input("route_journey_stay");
        $route_journey_breakfast = input("route_journey_breakfast");
        $route_journey_lunch = input("route_journey_lunch");
        $route_journey_dinner = input("route_journey_dinner");
        $eat_mark = input("eat_mark");
        $route_journey_scenic_sport = input("route_journey_scenic_sport");
        $route_journey_picture = input("route_journey_picture");
        $route_journey_remark = input("route_journey_remark");

        $data = [
            "route_template_id"=>$route_template_id,
            "the_days"=>$the_days,
            "status"=>$status,
            "route_journey_title"=>$route_journey_title,
            "route_template_id"=>$route_template_id,
            "route_journey_content"=>$route_journey_content,
            "route_journey_traffic"=>$route_journey_traffic,
            "route_journey_stay"=>$route_journey_stay,
            "route_journey_breakfast"=>$route_journey_breakfast,
            "route_journey_lunch"=>$route_journey_lunch,
            "route_journey_dinner"=>$route_journey_dinner,
            "eat_mark"=>$eat_mark,
            'route_journey_scenic_sport'=>$route_journey_scenic_sport,
            "route_journey_picture"=>$route_journey_picture,
            "route_journey_remark"=>$route_journey_remark,
            "status"=>$status,
            'user_id'=>session('user')['user_id']
        ]; 
        
        $result = $this->callSoaErp('post', '/product/addRouteJourney',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }

    public function loadFlight(){

        // 获取线路行程信息
        $route_flight_data=[
            'status'=>1,
            'route_template_id'=>input('route_template_id'),
            'route_flight_id'=>input('route_flight_id')
        ];
        $route_flight_result = $this->callSoaErp('post','/product/getRouteFlight',$route_flight_data);
        $this->assign('route_flight_result',$route_flight_result['data']);
        return $this->fetch('route_template_edit');

    }

    /**
     * 线路模板行程航班添加界面
     */
    public function showFlightAdd(){
        // 获取线路行程信息
        $route_flight_data=[
            'status'=>1,
            'route_template_id'=>input('route_template_id'),
            'route_flight_id'=>input('route_flight_id')    
        ];
        $route_flight_data_chinese_result = $this->callSoaErp('post','/product/getRouteFlight',$route_flight_data);
        $this->assign('route_flight_data_chinese_result',$route_flight_data_chinese_result['data']);
        return $this->fetch('route_template_add');
    }
     /**
     * 线路模板行程航班添加数据
     */
    public function addRouteFlightAjax(){
        $route_template_id = input("route_template_id");
        $the_days = input("the_days");
        $status = input("status");
        $start_city = input("start_city");
        $end_city = input("end_city");
        $start_time = input("start_time");
        $end_time = input("end_time");
        $flight_number = input("flight_number");
        $flight_type = input("flight_type");

        $data = [
            "route_template_id"=>$route_template_id,
            "the_days"=>$the_days,
             "status"=>$status,
            "start_city"=>$start_city,
            "end_city"=>$end_city,
            "start_time"=>strtotime($start_time),
            "end_time"=>strtotime($end_time),
            "flight_number"=>$flight_number,
            "flight_type"=>$flight_type,
            'user_id'=>session('user')['user_id']
        ]; 

        $result = $this->callSoaErp('post', '/product/addRouteFlight',$data);

        return   $result;//['code' => '400', 'msg' => $data];
    }
    
    /**
     * 线路模板修改航班AJAX
     */
    public function editRouteFLightAjax(){
        $route_flight_id = input("route_flight_id");
        $the_days = input("the_days");
        $start_city = input("start_city");
        $end_city = input("end_city");
        $start_time = input("start_time");
        $end_time = input("end_time");
        $flight_number = input("flight_number");
        $flight_type = input("flight_type");
        $status = input("status");
        $user_id = session('user')['user_id'];

        $data = [
            "route_flight_id"=>$route_flight_id,
            "the_days"=>$the_days,
            "flight_number"=>$flight_number,
            "flight_type"=>$flight_type,
            "start_city"=>$start_city,
            "start_time"=>strtotime($start_time),
            "end_city"=>$end_city,
            "end_time"=>strtotime($end_time),
            'status'=>$status,
            'user_id'=>$user_id
        ];
  
    	$result = $this->callSoaErp('post', '/product/updateRouteFlightByRouteFlightId',$data);
    	return   $result;//['code' => '400', 'msg' => $data];    	
    }

    /**
     * 线路模板行程添加数据
     */
    public function editRouteJourneyAjax(){
        $route_journey_id = input("route_journey_id");
        $the_days = input("the_days");
        $route_journey_title = input("route_journey_title");
        $route_journey_content = input("route_journey_content");
        $route_journey_traffic = input("route_journey_traffic");
        $route_journey_stay = input("route_journey_stay");
        $route_journey_breakfast = input("route_journey_breakfast");
        $route_journey_lunch = input("route_journey_lunch");
        $route_journey_dinner = input("route_journey_dinner");
        $eat_mark = input("eat_mark");
        $route_journey_scenic_sport = input("route_journey_scenic_sport");
        $route_journey_picture = input("route_journey_picture");
        $route_journey_remark = input("route_journey_remark");
        $user_id = session('user')['user_id'];
        $status = input("status");

        $data = [
            "route_journey_id"=>$route_journey_id,
            "the_days"=>$the_days,
            "route_journey_title"=>$route_journey_title,
            "route_journey_content"=>$route_journey_content,
            "route_journey_traffic"=>$route_journey_traffic,
            "route_journey_stay"=>$route_journey_stay,
            "route_journey_breakfast"=>$route_journey_breakfast,
            "route_journey_lunch"=>$route_journey_lunch,
            "route_journey_dinner"=>$route_journey_dinner,
            "eat_mark"=>trim($eat_mark,','),
            'route_journey_scenic_sport'=>$route_journey_scenic_sport,
            "route_journey_picture"=>$route_journey_picture,
            "route_journey_remark"=>$route_journey_remark,
            'user_id'=>$user_id,
            'status'=>$status
        ];
	
        $result = $this->callSoaErp('post', '/product/updateRouteJourneyByRouteJourneyId',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }

    /**
     * 线路模板配置酒店资源
     */
    public function showRouteHotelAdd(){
		$hotel_data = [
			'status'=>1	,
            'company_id'=>session('user')['company_id']
        ];
		$hotel_result = $this->callSoaErp('post', '/source/getHotel',$hotel_data);

		//获取当前酒店资源
		$source_data = [
				'supplier_type_id'=>2,
				'route_template_id'=>input('route_template_id'),
				'status'=>1
				
		];
		$source_result = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data);
    
		$this->assign('hotel_result',$hotel_result['data']);
		$this->assign('source_result',$source_result['data']);
        return $this->fetch('route_hotel_add');
    }
    /**
     * 线路模板配置酒店资源数据
     */
    public function showRouteHotelAddAjax(){
        $data['hotel_id'] = input('post.hotel_id');
        $hotelList = $this->callSoaErp('post','/source/getHotel',$data);
        $oj['code'] = 400;
        if(!empty($hotelList['data'])){
            $oj['code'] = 200;
            $oj['hotelData'] = $hotelList['data'][0];
        }
        echo json_encode($oj);
    }

    /**
     * 线路模板配置用餐资源
     */
    public function showRouteDiningAdd(){
        $dining_data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $dining_result = $this->callSoaErp('post', '/source/getDining',$dining_data);
        //获取当前用餐资源
        $source_data = [
                'supplier_type_id'=>3,
                'route_template_id'=>input('route_template_id'),
                'status'=>1
                
        ];
        $source_result = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data);
        
        
        $this->assign('dining_result', $dining_result['data']);
        $this->assign('source_result',$source_result['data']);
        return $this->fetch('route_dining_add');  
    }
    /**
     * 线路模板获取用餐资源数据
     */
    public function showRouteDiningAddAjax(){
        $data['dining_id'] = input('post.dining_id');
        $diningList = $this->callSoaErp('post','/source/getDining',$data);
        $oj['code'] = 400;
        if(!empty( $diningList['data'])){
            $oj['code'] = 200;
            $oj['DiningData'] =  $diningList['data'][0];
        }
        echo json_encode($oj);
    }
    /**
     * 线路模板配置航班资源
     */
    public function showRouteFlightAdd(){
        $flight_data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $flight_result = $this->callSoaErp('post', '/source/getFlight',$flight_data);
        //获取当前航班资源
        $source_data = [
                'supplier_type_id'=>4,
                'route_template_id'=>input('route_template_id'),
                'status'=>1
                
        ];
        $source_result = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data);
        
        
        $this->assign('flight_result', $flight_result['data']);
        $this->assign('source_result',$source_result['data']);
        return $this->fetch('route_flight_add');  
    }
    /**
     * 线路模板获取航班资源数据
     */
    public function showRouteFlightAddAjax(){
        $data['flight_id'] = input('post.flight_id');
        $flightList = $this->callSoaErp('post','/source/getFlight',$data);
        $oj['code'] = 400;
        if(!empty( $flightList['data'])){
            $oj['code'] = 200;
            $oj['FlightData'] =  $flightList['data'][0];
        }
        echo json_encode($oj);
    }
    /**
     * 线路模板配置邮轮资源
     */
    public function showRouteCruiseAdd(){
        $cruise_data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $cruise_result = $this->callSoaErp('post', '/source/getCruise',$cruise_data);
        
    
        //获取当前邮轮资源
        $source_data = [
                'supplier_type_id'=>5,
                'route_template_id'=>input('route_template_id'),
                'status'=>1
                
        ];
        $source_result = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data);
        
       
        $this->assign('cruise_result', $cruise_result['data']);
        $this->assign('source_result',$source_result['data']);
        return $this->fetch('route_cruise_add');  
    }
    /**
     * 线路模板获取邮轮资源数据
     */
    public function showRouteCruiseAddAjax(){
        $data['cruise_id'] = input('post.cruise_id');
        $cruiseList = $this->callSoaErp('post','/source/getCruise',$data);
        $oj['code'] = 400;
        if(!empty( $cruiseList['data'])){
            $oj['code'] = 200;
            $oj['CruiseData'] =  $cruiseList['data'][0];
        }
        echo json_encode($oj);
    }
     /**
     * 线路模板配置签证资源
     */
    public function showRouteVisaAdd(){
        $visa_data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $visa_result = $this->callSoaErp('post', '/source/getVisa',$visa_data);
        //获取当前签证资源
        $source_data = [
                'supplier_type_id'=>6,
                'route_template_id'=>input('route_template_id'),
                'status'=>1
                
        ];
        $source_result = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data);
        
        $this->assign('visa_result', $visa_result['data']);
        $this->assign('source_result',$source_result['data']);
        return $this->fetch('route_visa_add');  
    }
    /**
     * 线路模板获取签证资源数据
     */
    public function showRouteVisaAddAjax(){
        $data['visa_id'] = input('post.visa_id');
        $visaList = $this->callSoaErp('post','/source/getVisa',$data);
        $oj['code'] = 400;
        if(!empty( $visaList['data'])){
            $oj['code'] = 200;
            $oj['VisaData'] =  $visaList['data'][0];
        }
        echo json_encode($oj);
    }
     /**
     * 线路模板配置景点资源
     */
    public function showRouteScenicSpotAdd(){
        $scenic_spot_data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $scenic_spot_result = $this->callSoaErp('post', '/source/getScenicSpot',$scenic_spot_data);
        //获取当前景点资源
        $source_data = [
                'supplier_type_id'=>7,
                'route_template_id'=>input('route_template_id'),
                'status'=>1
                
        ];
        $source_result = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data);
        $this->assign('scenic_spot_result', $scenic_spot_result['data']);
        $this->assign('source_result',$source_result['data']);
        return $this->fetch('route_scenic_spot_add');  
    }
    /**
     * 线路模板获取景点资源数据
     */
    public function showRouteScenicSpotAddAjax(){
        $data['scenic_spot_id'] = input('post.scenic_spot_id');
        $scenic_spot_List = $this->callSoaErp('post','/source/getScenicSpot',$data);
        $oj['code'] = 400;
        if(!empty( $scenic_spot_List['data'])){
            $oj['code'] = 200;
            $oj['Scenic_spot_Data'] = $scenic_spot_List['data'][0];
        }
        echo json_encode($oj);
    }
     /**
     * 线路模板配置车辆资源
     */
    public function showRouteVehicleAdd(){
        $vehicle_data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $vehicle_result = $this->callSoaErp('post', '/source/getVehicle',$vehicle_data);
        //获取当前车辆资源
        $source_data = [
                'supplier_type_id'=>8,
                'route_template_id'=>input('route_template_id'),
                'status'=>1
                
        ];
        $source_result = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data);
        
        
        $this->assign('vehicle_result', $vehicle_result['data']);
        $this->assign('source_result',$source_result['data']);
        return $this->fetch('route_vehicle_add');  
    }
    /**
     * 线路模板获取车辆资源数据
     */
    public function showRouteVehicleAddAjax(){
        $data['vehicle_id'] = input('post.vehicle_id');
        $VehicleList = $this->callSoaErp('post','/source/getVehicle',$data);
        $oj['code'] = 400;
        if(!empty( $VehicleList['data'])){
            $oj['code'] = 200;
            $oj['VehicleData'] = $VehicleList['data'][0];
        }
        echo json_encode($oj);
    }
     /**
     * 线路模板配置导游资源
     */
    public function showRouteTourGuideAdd(){
        $tourGuide_data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $tourGuide_result = $this->callSoaErp('post', '/source/getTourGuide',$tourGuide_data);
        //获取当前导游资源
        $source_data = [
                'supplier_type_id'=>9,
                'route_template_id'=>input('route_template_id'),
                'status'=>1
                
        ];
        $source_result = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data);
        
        
        $this->assign('tourGuide_result', $tourGuide_result['data']);
        $this->assign('source_result',$source_result['data']);
        return $this->fetch('route_tour_guide_add');  
    }
    /**
     * 线路模板获导游资源数据
     */
    public function showRouteTourGuideAddAjax(){
        $data['tour_guide_id'] = input('post.tour_guide_id');
        $Tour_Guide_List = $this->callSoaErp('post','/source/getVehicle',$data);
        $oj['code'] = 400;
        if(!empty( $Tour_Guide_List['data'])){
            $oj['code'] = 200;
            $oj['TourGuideData'] = $Tour_Guide_List['data'][0];
        }
        echo json_encode($oj);
    }
     /**
     * 线路模板配置单项资源
     */
    public function showRouteSingleSourceAdd(){
        $single_data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $single_result = $this->callSoaErp('post', '/source/getSingleSource',$single_data);
        //获取当前单项资源
        $source_data = [
                'supplier_type_id'=>10,
                'route_template_id'=>input('route_template_id'),
                'status'=>1
                
        ];
        $source_result = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data);
        
        
        $this->assign('single_result', $single_result['data']);
        $this->assign('source_result',$source_result['data']);
        return $this->fetch('route_single_source_add');  
    }
    /**
     * 线路模板获单项资源数据
     */
    public function showRouteSingleSourceAddAjax(){
        $data['single_source_id'] = input('post.single_source_id');
        $single_source_List = $this->callSoaErp('post','/source/getSingleSource',$data);
        $oj['code'] = 400;
        if(!empty( $single_source_List['data'])){
            $oj['code'] = 200;
            $oj['SingleSourceData'] = $single_source_List['data'][0];
        }
        echo json_encode($oj);
    }
    /**
     * 线路模板配置自费
     */
    public function showRouteOwnExpenseAdd(){
        $own_expense_data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $own_expense_result = $this->callSoaErp('post', '/source/getOwnExpense',$own_expense_data);
        //获取当前单项资源
        $source_data = [
            'supplier_type_id'=>11,
            'route_template_id'=>input('route_template_id'),
            'status'=>1

        ];
        $source_result = $this->callSoaErp('post', '/product/getRouteSourceAllocation',$source_data);


        $this->assign('own_expense_result', $own_expense_result['data']);
        $this->assign('source_result',$source_result['data']);
        return $this->fetch('route_own_expense_add');
    }
    /**
     * 线路模板获自费数据
     */
    public function showRouteOwnExpenseAddAjax(){
        $data['own_expense_id'] = input('post.own_expense_id');
        $own_expense_List = $this->callSoaErp('post','/source/getOwnExpense',$data);
        $oj['code'] = 400;
        if(!empty( $own_expense_List['data'])){
            $oj['code'] = 200;
            $oj['OwnExpenseData'] = $own_expense_List['data'][0];
        }
        echo json_encode($oj);
    }
    

    /*******************************  分割线   *************************************************************/

    //利润表
    public function profitStatement(){
//        var_dump(Session('user'));

        //应收
        $number = input('get.number'); //团队编号
        $where['team_product_number'] = $number;
        $getTeamProductReceivableCompany = $this->callSoaErp('post','/product/getTeamProductReceivableCompany',$where);
        unset($where);
//        echo '<pre>';print_r($getTeamProductReceivableCompany);exit;

        //团队应收分公司
        if($getTeamProductReceivableCompany['data']['receivable_info']){
            foreach($getTeamProductReceivableCompany['data']['receivable_info'] as $k=>$v){
                $getTeamProductReceivableCompany['data']['receivable_info'][$k]['product_source_type_id'] = $v['source_type_id'];
                //换算后的金额
            }
            $vl['团费']['count'] = count($getTeamProductReceivableCompany['data']['receivable_info']);
            $d2 = Arrays::group($getTeamProductReceivableCompany['data']['receivable_info'],'payment_object_id');
            foreach($d2 as $ky=>$vy){
                $vl['团费']['data'][$ky]['count'] = count($vy);
                $vl['团费']['data'][$ky]['data'] = Arrays::group($vy,'order_number');
                foreach($vl['团费']['data'][$ky]['data'] as $k1=>$y1){
                    foreach($vl['团费']['data'][$ky]['data'][$k1] as $k2=>$y2){
                        $w['company_order_number'] = $k1;
                        $list = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$w);
                        $vl['团费']['data'][$ky]['data'][$k1][$k2]['youke'] = $list['data'];
                        $vl['团费']['data'][$ky]['data'][$k1][$k2]['customer_info'] = Arrays::keys(Arrays::group($vl['团费']['data'][$ky]['data'][$k1][$k2]['customer_info'],'company_order_customer_id'));
                    }
                }
            }
        }
//        echo '<pre>';print_r($vl);exit;


        //其他应收分公司
        if($getTeamProductReceivableCompany['data']['team_product_other_info']){
            foreach($getTeamProductReceivableCompany['data']['team_product_other_info'] as $k=>$v){
                $getTeamProductReceivableCompany['data']['team_product_other_info'][$k]['product_source_type_id'] = $v['source_type_id'];
                //换算后的金额
            }

            $vl['其他']['count'] = count($getTeamProductReceivableCompany['data']['team_product_other_info']);
            $d2 = Arrays::group($getTeamProductReceivableCompany['data']['team_product_other_info'],'payment_object_id');
            foreach($d2 as $ky=>$vy){
                $vl['其他']['data'][$ky]['count'] = count($vy);
                $vl['其他']['data'][$ky]['data'] = Arrays::group($vy,'order_number');
                foreach($vl['其他']['data'][$ky]['data'] as $k1=>$y1){
                    foreach($vl['其他']['data'][$ky]['data'][$k1] as $k2=>$y2){
                        $w['company_order_number'] = $k1;
                        $list = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$w);
                        $vl['其他']['data'][$ky]['data'][$k1][$k2]['youke'] = $list['data'];
                        $vl['其他']['data'][$ky]['data'][$k1][$k2]['customer_info'] = Arrays::keys(Arrays::group($vl['其他']['data'][$ky]['data'][$k1][$k2]['customer_info'],'company_order_customer_id'));
                    }
                }
            }
        }
        $this->assign('list',$vl);
        unset($vl);

        $where['team_product_number'] = $number;
        $Receivable = $this->callSoaErp('post','/branchcompany/getCompanyOrderNumberByTeamProductNumber',$where);
        $ReceivableList = Arrays::group($Receivable['data'],'company_id');
//        var_dump($ReceivableList);exit;
        $this->assign('ReceivableList',$ReceivableList);
        unset($where);

        $where['status'] = 1;
        $branchs = $this->callSoaErp('post','/system/getCompany',$where);
        $this->assign('branchs',Arrays::group($branchs['data'],'company_id'));
        $this->assign('types',$this->types2);
        unset($where);

        $where['status'] = 1;
        $Currency = $this->callSoaErp('post','/system/getCurrency',$where);
        if(!empty($Currency['data'])){
            $this->assign('Currency',Arrays::group($Currency['data'],'currency_id'));
        }
        unset($where);


        //获取团队应付供应商
        $where['team_product_number'] = input('get.number');
        $TeamProductCopeSupplier = $this->callSoaErp('post','/product/getTeamProductCopeSupplier',$where);
//        echo '<pre>';print_r($TeamProductCopeSupplier);exit;

        if($TeamProductCopeSupplier['data']){
            if($TeamProductCopeSupplier['data']['cope_info']){
                foreach($TeamProductCopeSupplier['data']['cope_info'] as $k=>$v){
                    $vl['团费']['count'] = count($TeamProductCopeSupplier['data']['cope_info']);
                    $d2 = Arrays::group($TeamProductCopeSupplier['data']['cope_info'],'source_type_id');
                    foreach($d2 as $ky=>$vy){
                        $vl['团费']['data'][$ky]['count'] = count($vy);
                        $vl['团费']['data'][$ky]['data'] = Arrays::group($vy,'receivable_object_id');
                    }

                }
            }

            if($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info']){
                foreach($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'] as $k=>$v){
                    $vl['地接']['count'] = count($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info']);
                    $d2 = Arrays::group($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'],'source_type_id');
                    foreach($d2 as $ky=>$vy){
                        $vl['地接']['data'][$ky]['count'] = count($vy);
                        $vl['地接']['data'][$ky]['data'] = Arrays::group($vy,'receivable_object_id');
                    }
                }
            }

            if($TeamProductCopeSupplier['data']['team_product_other_info']){
                foreach($TeamProductCopeSupplier['data']['team_product_other_info'] as $k=>$v){
                    $vl['其他']['count'] = count($TeamProductCopeSupplier['data']['team_product_other_info']);
                    $d2 = Arrays::group($TeamProductCopeSupplier['data']['team_product_other_info'],'source_type_id');
                    foreach($d2 as $ky=>$vy){
                        $vl['其他']['data'][$ky]['count'] = count($vy);
                        $vl['其他']['data'][$ky]['data'] = Arrays::group($vy,'receivable_object_id');
                    }
                }
            }
        }
//        echo '<pre>';print_r($vl);exit;
        $this->assign('list2',$vl);
        //10大供应商
        $where['status'] = 1;
        $where['company_id'] = session('user')['company_id'];
        $SupplierList = $this->callSoaErp('post','/source/getSupplier',$where);
        $SupplierArr = Arrays::group($SupplierList['data'],'supplier_type_id');
        foreach($SupplierArr as $ky=>$vy){
            if($ky!=1){
                if(empty($SupplierArr[1])){
                    $SupplierArr[1] = [];
                }
                if(empty($SupplierArr[$ky])){
                    $SupplierArr[$ky] = [];
                }
                $SupplierArr[$ky] = $SupplierArr[1]+$SupplierArr[$ky];
            }
        }
        $this->assign('SupplierArr',$SupplierArr);


        return $this->fetch('plan_profit_statement');

    }



    /**
     * 保存应付供应商
     */
    public function AddPlanSuppliersPayableAjax(){
         $param = Request::instance()->param();
//        var_dump($param);

        //团队应付
        $team_coping_type = Arrays::get($param,'team_coping_type');
        $team_coping_supplier = Arrays::get($param,'team_coping_supplier');
        $team_coping_item = Arrays::get($param,'team_coping_item');
        $team_coping_currency = Arrays::get($param,'team_coping_currency');
        $team_coping_quantity = Arrays::get($param,'team_coping_quantity');
        $team_coping_price = Arrays::get($param,'team_coping_price');
        $team_coping_number = Arrays::get($param,'team_coping_number');
        //地接报账
        $ground_connection_type = Arrays::get($param,'ground_connection_type');
        $ground_connection_supplier = Arrays::get($param,'ground_connection_supplier');
        $ground_connection_item = Arrays::get($param,'ground_connection_item');
        $ground_connection_currency = Arrays::get($param,'ground_connection_currency');
        $ground_connection_quantity = Arrays::get($param,'ground_connection_quantity');
        $ground_connection_price = Arrays::get($param,'ground_connection_price');
        $ground_connection_number = Arrays::get($param,'ground_connection_number');
        //其他成本
        $other_costs_type = Arrays::get($param,'other_costs_type');
        $other_costs_supplier = Arrays::get($param,'other_costs_supplier');
        $other_costs_item = Arrays::get($param,'other_costs_item');
        $other_costs_currency = Arrays::get($param,'other_costs_currency');
        $other_costs_quantity = Arrays::get($param,'other_costs_quantity');
        $other_costs_price = Arrays::get($param,'other_costs_price');
        $other_costs_number = Arrays::get($param,'other_costs_number');

        $d['team_product_number'] = Arrays::get($param,'number');
        $d['cope_info'] = [];
        $d['travel_agency_reimbursement_cope_info'] = [];
        $d['team_product_other_info'] = [];

        //团费应付
        foreach($team_coping_type as $ky=>$vy){
            if(is_numeric($vy)){
                $source_type_id = $vy;
                foreach($team_coping_supplier[$ky] as $ku=>$vu){
                    $supplier_name = $vu;
                    foreach($team_coping_item[$ky][$ku] as $k=>$v){
                        if($team_coping_number[$ky][$ku][$k]){
                            $ary['cope_number'] = $team_coping_number[$ky][$ku][$k];
                        }
                        if($source_type_id>1){
                            $ary['source_type_id'] = $source_type_id;  //类型
                        }
                        $ary['product_type'] = $source_type_id>1?2:$source_type_id;


                        $ary['supplier_id'] = $supplier_name; //供应商
                        $ary['product_name'] = $team_coping_item[$ky][$ku][$k];//名称
                        $ary['cope_currency_id'] = $team_coping_currency[$ky][$ku][$k];//币种
                        $ary['price'] = $team_coping_price[$ky][$ku][$k];//数量
                        $ary['unit'] = $team_coping_quantity[$ky][$ku][$k]; //价格
                        $ary['cope_money'] = $ary['price']*$ary['unit'];
                        $d['cope_info'][] = $ary;
                        unset($ary);
                    }
                }
            }
        }
        //地接报账
        foreach($ground_connection_type as $ky=>$vy){
            if(is_numeric($vy)){
                $source_type_id = $vy;
                foreach($ground_connection_supplier[$ky] as $ku=>$vu){
                    $supplier_name = $vu;
                    foreach($ground_connection_item[$ky][$ku] as $k=>$v){
                        if($ground_connection_number[$ky][$ku][$k]){
                            $ary['cope_number'] = $ground_connection_number[$ky][$ku][$k];
                        }
                        if($source_type_id>1){
                            $ary['source_type_id'] = $source_type_id;  //类型
                        }
                        $ary['product_type'] = $source_type_id>1?2:$source_type_id;
                        $ary['supplier_id'] = $supplier_name;
                        $ary['product_name'] = $ground_connection_item[$ky][$ku][$k];
                        $ary['cope_currency_id'] = $ground_connection_currency[$ky][$ku][$k];;
                        $ary['unit'] = $ground_connection_quantity[$ky][$ku][$k];;
                        $ary['price'] = $ground_connection_price[$ky][$ku][$k];
                        $ary['cope_money'] = $ary['price']*$ary['unit'];
                        
                        $d['travel_agency_reimbursement_cope_info'][] = $ary;
                        unset($ary);
                    }
                }
            }
        }
        //其他成本
        foreach($other_costs_type as $ky=>$vy){
            if(is_numeric($vy)){
                $source_type_id = $vy;
                foreach($other_costs_supplier[$ky] as $ku=>$vu){
                    $supplier_name = $vu;
                    foreach($other_costs_item[$ky][$ku] as $k=>$v){
                        if($other_costs_number[$ky][$ku][$k]){
                            $ary['cope_number'] = $other_costs_number[$ky][$ku][$k];
                        }
                        if($source_type_id>1){
                            $ary['source_type_id'] = $source_type_id;  //类型
                        }
                        $ary['product_type'] = $source_type_id>1?2:$source_type_id;
                        $ary['supplier_id'] = $supplier_name;
                        $ary['product_name'] = $other_costs_item[$ky][$ku][$k];
                        $ary['cope_currency_id'] = $other_costs_currency[$ky][$ku][$k];;
                        $ary['unit'] = $other_costs_quantity[$ky][$ku][$k];;
                        $ary['price'] = $other_costs_price[$ky][$ku][$k];
                        $ary['cope_money'] = $ary['price']*$ary['unit'];
                        $d['team_product_other_info'][] = $ary;
                        unset($ary);
                    }
                }
            }
        }

        return $this->callSoaErp('post','/product/addTeamProductCopeSupplier',$d);

//        var_dump($d);
//        Session::set('dd',$d);

        exit;
    }

    //添加团队应付供应商
    public function NAddPlanSuppliersPayableAjax(){
        $param = Request::instance()->param();
//        var_dump($param);exit;
		$d['travel_agency_reimbursement_number'] = Arrays::get($param,'travel_agency_reimbursement_number');
        $d['team_product_number'] = Arrays::get($param,'number');
        //团队应付
        $team_coping_type = Arrays::get($param,'team_coping_type');
        $team_coping_supplier = Arrays::get($param,'team_coping_supplier');
        $team_coping_item = Arrays::get($param,'team_coping_item');
        $team_coping_currency = Arrays::get($param,'team_coping_currency');
        $team_coping_quantity = Arrays::get($param,'team_coping_quantity');
        $team_coping_price = Arrays::get($param,'team_coping_price');
        $team_coping_number = Arrays::get($param,'team_coping_number');
        $d['cope_info'] = [];
        foreach($team_coping_type as $ky=>$vy){
            if(is_numeric($vy)){
                $source_type_id = $vy;
                foreach($team_coping_supplier[$ky] as $ku=>$vu){
                    $supplier_name = $vu;
                    foreach($team_coping_item[$ky][$ku] as $k=>$v){
                        if($team_coping_number[$ky][$ku][$k]){
                            $ary['cope_number'] = $team_coping_number[$ky][$ku][$k]; //应付编号
                        }
                        $ary['source_type_id'] = $source_type_id; //资源类型
                        $ary['supplier_id'] = $supplier_name; //供应商
                        $ary['product_name'] = $team_coping_item[$ky][$ku][$k];//名称
                        $ary['cope_currency_id'] = $team_coping_currency[$ky][$ku][$k];//币种
                        $ary['price'] = $team_coping_price[$ky][$ku][$k];//价格
                        $ary['unit'] = $team_coping_quantity[$ky][$ku][$k]; //数量
                        $ary['cope_money'] = $ary['price']*$ary['unit'];
                        $d['cope_info'][] = $ary;
                        unset($ary);
                    }
                }
            }
        }

        //地接报账
        $ground_connection_type = Arrays::get($param,'ground_connection_type');
        $ground_connection_supplier = Arrays::get($param,'ground_connection_supplier');
        $ground_connection_item = Arrays::get($param,'ground_connection_item');
        $ground_connection_currency = Arrays::get($param,'ground_connection_currency');
        $ground_connection_quantity = Arrays::get($param,'ground_connection_quantity');
        $ground_connection_price = Arrays::get($param,'ground_connection_price');
        $ground_connection_number = Arrays::get($param,'ground_connection_number');
        $d['travel_agency_reimbursement_cope_info'] = [];
        foreach($ground_connection_type as $ky=>$vy){
            if(is_numeric($vy)){
                $source_type_id = $vy;
                foreach($ground_connection_supplier[$ky] as $ku=>$vu){
                    $supplier_name = $vu;
                    foreach($ground_connection_item[$ky][$ku] as $k=>$v){
                        if($ground_connection_number[$ky][$ku][$k]){
                            $ary['cope_number'] = $ground_connection_number[$ky][$ku][$k]; //应付编号
                        }
                        $ary['source_type_id'] = $source_type_id; //资源类型
                        $ary['supplier_id'] = $supplier_name; //供应商
                        $ary['product_name'] = $ground_connection_item[$ky][$ku][$k];//名称
                        $ary['cope_currency_id'] = $ground_connection_currency[$ky][$ku][$k];//币种
                        $ary['price'] = $ground_connection_price[$ky][$ku][$k];//价格
                        $ary['unit'] = $ground_connection_quantity[$ky][$ku][$k]; //数量
                        $ary['cope_money'] = $ary['price']*$ary['unit'];
                        $d['travel_agency_reimbursement_cope_info'][] = $ary;
                        unset($ary);
                    }
                }
            }
        }

        //其他成本
        $other_costs_type = Arrays::get($param,'other_costs_type');
        $other_costs_supplier = Arrays::get($param,'other_costs_supplier');
        $other_costs_item = Arrays::get($param,'other_costs_item');
        $other_costs_currency = Arrays::get($param,'other_costs_currency');
        $other_costs_quantity = Arrays::get($param,'other_costs_quantity');
        $other_costs_price = Arrays::get($param,'other_costs_price');
        $other_costs_number = Arrays::get($param,'other_costs_number');
        $d['team_product_other_info'] = [];
        foreach($other_costs_type as $ky=>$vy){
            if(is_numeric($vy)){
                $source_type_id = $vy;
                foreach($other_costs_supplier[$ky] as $ku=>$vu){
                    $supplier_name = $vu;
                    foreach($other_costs_item[$ky][$ku] as $k=>$v){
                        if($other_costs_number[$ky][$ku][$k]){
                            $ary['cope_number'] = $other_costs_number[$ky][$ku][$k]; //应付编号
                        }
                        $ary['source_type_id'] = $source_type_id; //资源类型
                        $ary['supplier_id'] = $supplier_name; //供应商
                        $ary['product_name'] = $other_costs_item[$ky][$ku][$k];//名称
                        $ary['cope_currency_id'] = $other_costs_currency[$ky][$ku][$k];//币种
                        $ary['price'] = $other_costs_price[$ky][$ku][$k];//价格
                        $ary['unit'] = $other_costs_quantity[$ky][$ku][$k]; //数量
                        $ary['cope_money'] = $ary['price']*$ary['unit'];
                        $d['team_product_other_info'][] = $ary;
                        unset($ary);
                    }
                }
            }
        }

      return $this->callSoaErp('post','/product/addTeamProductCopeSupplier',$d);

    }

    /**
     * 团队应付供应商列表
     * Hugh 18-12-24
     */
    public function PlanSuppliersPayable(){
        //获取团队应付供应商
        $where['team_product_number'] = input('get.number');
        $TeamProductCopeSupplier = $this->callSoaErp('post','/product/getTeamProductCopeSupplier',$where);
       

        if($TeamProductCopeSupplier['data']){
            if($TeamProductCopeSupplier['data']['cope_info']){
                $TeamProductCopeSupplier['data']['cope_info'] = Arrays::sort($TeamProductCopeSupplier['data']['cope_info'],'source_type_id','asc');
                foreach($TeamProductCopeSupplier['data']['cope_info'] as $k=>$v){
                    $vl['团费']['count'] = count($TeamProductCopeSupplier['data']['cope_info']);
                    $d2 = Arrays::group($TeamProductCopeSupplier['data']['cope_info'],'source_type_id');
                    foreach($d2 as $ky=>$vy){
                        $vl['团费']['data'][$ky]['count'] = count($vy);
                        $vl['团费']['data'][$ky]['data'] = Arrays::group($vy,'receivable_object_id');
                    }

                }
            }

            if($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info']){
                $TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'] = Arrays::sort($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'],'source_type_id','asc');
                foreach($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'] as $k=>$v){
                    $vl['地接']['count'] = count($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info']);
                    $d2 = Arrays::group($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'],'source_type_id');
                    foreach($d2 as $ky=>$vy){
                        $vl['地接']['data'][$ky]['count'] = count($vy);
                        $vl['地接']['data'][$ky]['data'] = Arrays::group($vy,'receivable_object_id');
                    }

                }
                $travel_agency_reimbursement_number = $TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'][0]['travel_agency_reimbursement_number'];
            }

            if($TeamProductCopeSupplier['data']['team_product_other_info']){
                $TeamProductCopeSupplier['data']['team_product_other_info'] = Arrays::sort($TeamProductCopeSupplier['data']['team_product_other_info'],'source_type_id','asc');
                foreach($TeamProductCopeSupplier['data']['team_product_other_info'] as $k=>$v){
                    $vl['其他']['count'] = count($TeamProductCopeSupplier['data']['team_product_other_info']);
                    $d2 = Arrays::group($TeamProductCopeSupplier['data']['team_product_other_info'],'source_type_id');
                    foreach($d2 as $ky=>$vy){
                        $vl['其他']['data'][$ky]['count'] = count($vy);
                        $vl['其他']['data'][$ky]['data'] = Arrays::group($vy,'receivable_object_id');
                    }

                }
            }


        }
//        echo '<pre>';print_r($vl);exit;
        $this->assign('list',$vl);
		$this->assign('travel_agency_reimbursement_number',$travel_agency_reimbursement_number);
        //10大供应商
        $where['status'] = 1;
        $where['company_id'] = session('user')['company_id'];
        $SupplierList = $this->callSoaErp('post','/source/getSupplier',$where);
        $SupplierArr = Arrays::group($SupplierList['data'],'supplier_type_id');
        foreach($SupplierArr as $ky=>$vy){
            if($ky!=1){
                 if(empty($SupplierArr[1])){
                     $SupplierArr[1] = [];
                 }
                 if(empty($SupplierArr[$ky])){
                    $SupplierArr[$ky] = [];
                }
                $SupplierArr[$ky] = $SupplierArr[1]+$SupplierArr[$ky];
            }
        }
//        unset($SupplierArr[1]);
//        var_dump($SupplierArr);exit;


        $this->assign('SupplierArr',$SupplierArr);
        $this->assign('types',$this->types);

        $w['status'] = 1;
        $Currency = $this->callSoaErp('post','/system/getCurrency',$w);
        if(!empty($Currency['data'])){
            $this->assign('Currency',$Currency['data']);
        }

        return $this->fetch('n_plan_suppliers_payable');
    }


    /**
     * 应付供应商
     * Hugh 19-09-25
     */
    public function PlanSuppliersPayable2(){

        $where['team_product_number'] = input('get.number');
        $TeamProductCopeSupplier = $this->callSoaErp('post','/product/getTeamProductCopeSupplier',$where);

        $vl = [];
        if($TeamProductCopeSupplier['data']){
            if($TeamProductCopeSupplier['data']['cope_info']){
                foreach($TeamProductCopeSupplier['data']['cope_info'] as $k=>$v){
                    if($v['product_type']<=1){
                        $TeamProductCopeSupplier['data']['cope_info'][$k]['source_type_id'] = $v['product_type'];
                    }
                    if(empty($v['supplier_id'])){
                        $TeamProductCopeSupplier['data']['cope_info'][$k]['supplier_id'] = 0;
                    }
                }

                $vl['团费']['count'] = count($TeamProductCopeSupplier['data']['cope_info']);
                $d2 = Arrays::group($TeamProductCopeSupplier['data']['cope_info'],'source_type_id');
                foreach($d2 as $ky=>$vy){
                    $vl['团费']['data'][$ky]['count'] = count($vy);
                    $vl['团费']['data'][$ky]['data'] = Arrays::group($vy,'supplier_id');
                }
            }

            if($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info']){
                foreach($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'] as $k=>$v){
                    if($v['product_type']<=1){
                        $TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'][$k]['source_type_id'] = $v['product_type'];
                    }
                    if(empty($v['supplier_id'])){
                        $TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'][$k]['supplier_id'] = 0;
                    }
                }


                $vl['地接']['count'] = count($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info']);
                $d3 = Arrays::group($TeamProductCopeSupplier['data']['travel_agency_reimbursement_cope_info'],'source_type_id');
                foreach($d3 as $ky=>$vy){
                    $vl['地接']['data'][$ky]['count'] = count($vy);
                    $vl['地接']['data'][$ky]['data'] = Arrays::group($vy,'supplier_id');
                }
            }

            if($TeamProductCopeSupplier['data']['team_product_other_info']){
                foreach($TeamProductCopeSupplier['data']['team_product_other_info'] as $k=>$v){
                    if($v['product_type']<=1){
                        $TeamProductCopeSupplier['data']['team_product_other_info'][$k]['source_type_id'] = $v['product_type'];
                    }
                    if(empty($v['supplier_id'])){
                        $TeamProductCopeSupplier['data']['team_product_other_info'][$k]['supplier_id'] = 0;
                    }
                }
                $vl['其他']['count'] = count($TeamProductCopeSupplier['data']['team_product_other_info']);
                $d3 = Arrays::group($TeamProductCopeSupplier['data']['team_product_other_info'],'source_type_id');
                foreach($d3 as $ky=>$vy){
                    $vl['其他']['data'][$ky]['count'] = count($vy);
                    $vl['其他']['data'][$ky]['data'] = Arrays::group($vy,'supplier_id');
                }
            }

        }

//        echo "<pre>";
//        print_r($vl['团费']);
//        echo "<pre>";
//        print_r($vl['地接']);
//        echo "<pre>";
//        print_r($vl['其他']);
//        exit;


//        $vl = [];
//        $d = Arrays::group($dd, '付款种类');
//        //团费
//        foreach($d as $k=>$v){
//            $vl[$k]['count'] = count($v);
//            $d2 = Arrays::group($v,'类型');
//            foreach($d2 as $ky=>$vy){
//                $vl[$k]['data'][$ky]['count'] = count($vy);
//                $vl[$k]['data'][$ky]['data'] = Arrays::group($vy,'供应商');
//            }
//        }

//        var_dump($vl['团费']);exit;

        //10大供应商
        $where['status'] = 1;
        $where['company_id'] = session('user')['company_id'];
        $SupplierList = $this->callSoaErp('post','/source/getSupplier',$where);
        $SupplierArr = Arrays::group($SupplierList['data'],'supplier_type_id');
        foreach($SupplierArr as $ky=>$vy){
            if($ky!=1){
                $SupplierArr[$ky] = array_merge($SupplierArr[$ky],$SupplierArr[1]);
            }
        }
//        unset($SupplierArr[1]);
//        var_dump($SupplierArr);exit;


        $this->assign('SupplierArr',$SupplierArr);
        $this->assign('list',$vl);
        $this->assign('types',$this->types);

        $w['status'] = 1;
        $Currency = $this->callSoaErp('post','/system/getCurrency',$w);
        if(!empty($Currency['data'])){
            $this->assign('Currency',$Currency['data']);
        }

        return $this->fetch('plan_suppliers_payable');
    }

    /***
     * 根据类型获取供应商信息
     * Hugh
     */
    public function SupplierBySupplierTypeIdAjax(){
        $SupplierTypeId = input('post.SupplierTypeId');
        $where['supplier_type_id'] = $SupplierTypeId;
        $where['status'] = 1;
        $where['company_id'] = session('user')['company_id'];
        $SupplierList1 = $this->callSoaErp('post','/source/getSupplier',$where);

        if($SupplierTypeId<>1 && $SupplierTypeId<>'a'){
            $where['supplier_type_id'] = 1;
            $SupplierList2 = $this->callSoaErp('post','/source/getSupplier',$where);
            $list = array_merge($SupplierList1['data'],$SupplierList2['data']);
        }else{
            $list = $SupplierList1['data'];
        }


        return $list;
    }


    /***
     * 获取应收分公司 订单编号
     * @team_product_number
     * @payment_company_id
     */
    public function getReceivableAjax(){
        $w['team_product_number'] = input('team_product_number');
        $w['company_id'] = input('payment_company_id');
        $w['status'] = 1;

        $list = $this->callSoaErp('post','/branchcompany/getCompanyOrderNumberByTeamProductNumber',$w);
//        var_dump($list);exit;
        $ar = [];
        foreach($list['data'] as $k=>$v){
            $ar[$k]['order_number'] = $v['company_order_number'];
        }
        return $ar;

//        return [0=>['order_number'=>'BO20190301353991'],1=>['order_number'=>'BO20181114775262'],['order_number'=>'BO20181115699324'],['order_number'=>'BO20181116179065']];
    }

    /***
     * 公司订单游客
     * @company_order_number
     */
    public function getCompanyOrderCustomerAjax(){
        $w['company_order_number'] = input('post.team_coping_booking');
        $list = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$w);
        return $list['data'];
    }

    /**
     * 添加团队产品游客
     * Hugh 18-12-24
     */
    public function NaddTeamProductReceivableCompanyAjax()
    {
        $param = Request::instance()->param();

        $d['team_product_number'] = Arrays::get($param, 'team_product_number');
        //团队应付
        $d['receivable_info'] = [];
        $team_coping_branch = Arrays::get($param, 'team_coping_branch');
        $team_coping_booking = Arrays::get($param, 'team_coping_booking');
        $team_coping_sn = Arrays::get($param, 'team_coping_sn');
        $team_coping_types = Arrays::get($param, 'team_coping_types');
        $team_coping_item = Arrays::get($param, 'team_coping_item');
        $team_coping_currency = Arrays::get($param, 'team_coping_currency');
        $team_coping_price = Arrays::get($param, 'team_coping_price');
        $team_coping_receivable_number = Arrays::get($param,'team_coping_receivable_number');
        foreach ($team_coping_branch as $ak => $ay) {
            if (is_numeric($ay)) {
                $payment_company_id = $ay;
                foreach ($team_coping_booking[$ak] as $bk => $by) {
                    $order_number = $by;
                    foreach ($team_coping_sn[$ak][$bk] as $ck => $cy) {
                        if($payment_company_id!='a' && $order_number!='a' && !empty($cy) &&  $team_coping_types[$ak][$bk][$ck]!='a'  && !empty($team_coping_item[$ak][$bk][$ck]) && $team_coping_currency[$ak][$bk][$ck]!='a' && !empty($team_coping_price[$ak][$bk][$ck]) ){
                            if($team_coping_receivable_number[$ak][$bk][$ck]){
                                $ar['receivable_number'] = $team_coping_receivable_number[$ak][$bk][$ck]; //应付编号
                            }
                            $ar['payment_object_id'] = $payment_company_id; //付款公司ID
                            $ar['order_number'] = $order_number; //订单编号
                            $ar['company_order_customer_id'] = implode(',', $cy); //公司顾客ID
                            $ar['source_type_id'] = $team_coping_types[$ak][$bk][$ck]; //类型1团队产品2酒店3用餐。。。。等等
                            $ar['product_name'] = $team_coping_item[$ak][$bk][$ck]; //产品名称
                            $ar['receivable_currency_id'] = $team_coping_currency[$ak][$bk][$ck]; // 货币ID
                            $ar['receivable_money'] = $team_coping_price[$ak][$bk][$ck]; //应收价格
                            $d['receivable_info'][] = $ar;
                            unset($ar);
                        }
                    }
                }
            }
        }

        //团队其他应付
        $d['team_product_other_info'] = [];
        $other_receivable_branch = Arrays::get($param,'other_receivable_branch');
        $other_receivable_booking = Arrays::get($param,'other_receivable_booking');
        $other_receivable_sn = Arrays::get($param,'other_receivable_sn');
        $other_receivable_types = Arrays::get($param,'other_receivable_types');
        $other_receivable_item = Arrays::get($param,'other_receivable_item');
        $other_receivable_currency = Arrays::get($param,'other_receivable_currency');
        $other_receivable_price = Arrays::get($param,'other_receivable_price');
        $other_receivable_number = Arrays::get($param,'other_receivable_number');
        foreach ($other_receivable_branch as $ak => $ay) {
            if (is_numeric($ay)) {
                $payment_company_id = $ay;
                foreach ($other_receivable_booking[$ak] as $bk => $by) {
                    $order_number = $by;
                    foreach ($other_receivable_sn[$ak][$bk] as $ck => $cy) {
                        if($payment_company_id!='a' && $order_number!='a' && !empty($cy) &&  $other_receivable_types[$ak][$bk][$ck]!='a'  && !empty($other_receivable_item[$ak][$bk][$ck]) && $other_receivable_currency[$ak][$bk][$ck]!='a' && !empty($other_receivable_price[$ak][$bk][$ck]) ) {
                            if($other_receivable_number[$ak][$bk][$ck]){
                                $ar['receivable_number'] = $other_receivable_number[$ak][$bk][$ck]; //应付编号
                            }
                            $ar['payment_object_id'] = $payment_company_id; //付款公司ID
                            $ar['order_number'] = $order_number; //订单编号
                            $ar['company_order_customer_id'] = implode(',', $cy); //公司顾客ID
                            $ar['source_type_id'] = $other_receivable_types[$ak][$bk][$ck]; //类型1团队产品2酒店3用餐。。。。等等
                            $ar['product_name'] = $other_receivable_item[$ak][$bk][$ck]; //产品名称
                            $ar['receivable_currency_id'] = $other_receivable_currency[$ak][$bk][$ck]; //货币ID
                            $ar['receivable_money'] = $other_receivable_price[$ak][$bk][$ck]; //应收价格
                            $d['team_product_other_info'][] = $ar;
                            unset($ar);
                        }
                    }
                }
            }
        }

        return $this->callSoaErp('post','/product/addTeamProductReceivableCompany',$d);

//        echo '<pre>';print_r($param);exit;
    }


    /***
     * 添加团队产品应收
     *
     */
    public function addTeamProductReceivableCompanyAjax()
    {
        $param = Request::instance()->param();
//        var_dump($param);exit;
        $d['receivable_info'] = [];
        $d['travel_agency_reimbursement_receivable_info'] = [];
        $d['team_product_other_info'] = [];

        $d['team_product_number'] = Arrays::get($param, 'team_product_number');
        $team_coping_branch = Arrays::get($param, 'team_coping_branch');
        $team_coping_booking = Arrays::get($param, 'team_coping_booking');
        $team_coping_sn = Arrays::get($param, 'team_coping_sn');
        $team_coping_types = Arrays::get($param, 'team_coping_types');
        $team_coping_item = Arrays::get($param, 'team_coping_item');
        $team_coping_currency = Arrays::get($param, 'team_coping_currency');
        $team_coping_price = Arrays::get($param, 'team_coping_price');
        $team_coping_receivable_number = Arrays::get($param,'team_coping_receivable_number');
        foreach ($team_coping_branch as $ak => $ay) {
        
            if (is_numeric($ay)) {
                $payment_company_id = $ay;
                foreach ($team_coping_booking[$ak] as $bk => $by) {
                    $order_number = $by;
                    foreach ($team_coping_sn[$ak][$bk] as $ck => $cy) {
                        if($payment_company_id!='a' && $order_number!='a' && !empty($cy) &&  $team_coping_types[$ak][$bk][$ck]!='a'  && !empty($team_coping_item[$ak][$bk][$ck]) && $team_coping_currency[$ak][$bk][$ck]!='a' && !empty($team_coping_price[$ak][$bk][$ck]) ){
                            if($team_coping_receivable_number[$ak][$bk][$ck]){
                                $ar['receivable_number'] = $team_coping_receivable_number[$ak][$bk][$ck];
                            }
                            $ar['payment_company_id'] = $payment_company_id;
                            $ar['order_number'] = $order_number;
                            $ar['company_order_customer_id'] = implode(',', $cy);
                            if($team_coping_types[$ak][$bk][$ck]>1){
                                $ar['product_source_type_id'] = $team_coping_types[$ak][$bk][$ck];
                            }
                            $ar['product_type'] = $team_coping_types[$ak][$bk][$ck]>1?2:$team_coping_types[$ak][$bk][$ck];

                            $ar['product_name'] = $team_coping_item[$ak][$bk][$ck];
                            $ar['currency_id'] = $team_coping_currency[$ak][$bk][$ck];
                            $ar['receivable_money'] = $team_coping_price[$ak][$bk][$ck];
                            $d['receivable_info'][] = $ar;
                            unset($ar);
                        }
                       
                    }
                }
            }
        }

        $ground_connection_branch = Arrays::get($param,'ground_connection_branch');
        $ground_connection_booking = Arrays::get($param,'ground_connection_booking');
        $ground_connection_sn = Arrays::get($param,'ground_connection_sn');
        $ground_connection_types = Arrays::get($param,'ground_connection_types');
        $ground_connection_item = Arrays::get($param,'ground_connection_item');
        $ground_connection_currency = Arrays::get($param,'ground_connection_currency');
        $ground_connection_price = Arrays::get($param,'ground_connection_price');
        $ground_connection_receivable_number = Arrays::get($param,'ground_connection_receivable_number');
        foreach ($ground_connection_branch as $ak => $ay) {
            if (is_numeric($ay)) {
                $payment_company_id = $ay;
                foreach ($ground_connection_booking[$ak] as $bk => $by) {
                    $order_number = $by;
                    foreach ($ground_connection_sn[$ak][$bk] as $ck => $cy) {
                        if($payment_company_id!='a' && $order_number!='a' && !empty($cy) &&  $ground_connection_types[$ak][$bk][$ck]!='a'  && !empty($ground_connection_item[$ak][$bk][$ck]) && $ground_connection_currency[$ak][$bk][$ck]!='a' && !empty($ground_connection_price[$ak][$bk][$ck]) ) {
                            if($ground_connection_receivable_number[$ak][$bk][$ck]){
                                $ar['receivable_number'] = $ground_connection_receivable_number[$ak][$bk][$ck];
                            }
                            $ar['payment_company_id'] = $payment_company_id;
                            $ar['order_number'] = $order_number;
                            $ar['company_order_customer_id'] = implode(',', $cy);
                            if($ground_connection_types[$ak][$bk][$ck]>1){
                                $ar['product_source_type_id'] = $ground_connection_types[$ak][$bk][$ck];
                            }
                            $ar['product_type'] = $ground_connection_types[$ak][$bk][$ck]>1?2:$ground_connection_types[$ak][$bk][$ck];
                            $ar['product_name'] = $ground_connection_item[$ak][$bk][$ck];
                            $ar['currency_id'] = $ground_connection_currency[$ak][$bk][$ck];
                            $ar['receivable_money'] = $ground_connection_price[$ak][$bk][$ck];
                            $d['travel_agency_reimbursement_receivable_info'][] = $ar;
                            unset($ar);
                        }
                    }
                }
            }
        }

        $other_receivable_branch = Arrays::get($param,'other_receivable_branch');
        $other_receivable_booking = Arrays::get($param,'other_receivable_booking');
        $other_receivable_sn = Arrays::get($param,'other_receivable_sn');
        $other_receivable_types = Arrays::get($param,'other_receivable_types');
        $other_receivable_item = Arrays::get($param,'other_receivable_item');
        $other_receivable_currency = Arrays::get($param,'other_receivable_currency');
        $other_receivable_price = Arrays::get($param,'other_receivable_price');
        $other_receivable_number = Arrays::get($param,'other_receivable_number');
        foreach ($other_receivable_branch as $ak => $ay) {
            if (is_numeric($ay)) {
                $payment_company_id = $ay;
                foreach ($other_receivable_booking[$ak] as $bk => $by) {
                    $order_number = $by;
                    foreach ($other_receivable_sn[$ak][$bk] as $ck => $cy) {
                        if($payment_company_id!='a' && $order_number!='a' && !empty($cy) &&  $other_receivable_types[$ak][$bk][$ck]!='a'  && !empty($other_receivable_item[$ak][$bk][$ck]) && $other_receivable_currency[$ak][$bk][$ck]!='a' && !empty($other_receivable_price[$ak][$bk][$ck]) ) {
                            if($other_receivable_number[$ak][$bk][$ck]){
                                $ar['receivable_number'] = $other_receivable_number[$ak][$bk][$ck];
                            }
                            $ar['payment_company_id'] = $payment_company_id;
                            $ar['order_number'] = $order_number;
                            $ar['company_order_customer_id'] = implode(',', $cy);
                            if($other_receivable_types[$ak][$bk][$ck]>1){
                                $ar['product_source_type_id'] = $other_receivable_types[$ak][$bk][$ck];
                            }
                            $ar['product_type'] = $other_receivable_types[$ak][$bk][$ck]>1?2:$other_receivable_types[$ak][$bk][$ck];
                            $ar['product_name'] = $other_receivable_item[$ak][$bk][$ck];
                            $ar['currency_id'] = $other_receivable_currency[$ak][$bk][$ck];
                            $ar['receivable_money'] = $other_receivable_price[$ak][$bk][$ck];
                            $d['team_product_other_info'][] = $ar;
                            unset($ar);
                        }
                    }
                }
            }
        }
	
       
        $result = $this->callSoaErp('post', '/product/addTeamProductReceivableCompany', $d);
		return $result;
    }

    /***
     * 应收分公司
     * Hugh 18-12-24
     */
    public function PlanReceivableBranch(){
        $number = input('get.number'); //团队编号
        $where['team_product_number'] = $number;
        $getTeamProductReceivableCompany = $this->callSoaErp('post','/product/getTeamProductReceivableCompany',$where);
//        echo '<pre>';print_r($getTeamProductReceivableCompany);exit;

        if($getTeamProductReceivableCompany['data']['receivable_info']){
            $getTeamProductReceivableCompany['data']['receivable_info'] = Arrays::sort($getTeamProductReceivableCompany['data']['receivable_info'],'payment_object_id','asc');
            foreach($getTeamProductReceivableCompany['data']['receivable_info'] as $k=>$v){
                $getTeamProductReceivableCompany['data']['receivable_info'][$k]['product_source_type_id'] = $v['source_type_id'];
            }

            $vl['团费']['count'] = count($getTeamProductReceivableCompany['data']['receivable_info']);
            $d2 = Arrays::group($getTeamProductReceivableCompany['data']['receivable_info'],'payment_object_id');
            foreach($d2 as $ky=>$vy){
                $vl['团费']['data'][$ky]['count'] = count($vy);
                $vl['团费']['data'][$ky]['data'] = Arrays::group($vy,'order_number');
                foreach($vl['团费']['data'][$ky]['data'] as $k1=>$y1){
                    foreach($vl['团费']['data'][$ky]['data'][$k1] as $k2=>$y2){
                        $w['company_order_number'] = $k1;
                        $list = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$w);
                        $vl['团费']['data'][$ky]['data'][$k1][$k2]['youke'] = $list['data'];
                        $vl['团费']['data'][$ky]['data'][$k1][$k2]['customer_info'] = Arrays::keys(Arrays::group($vl['团费']['data'][$ky]['data'][$k1][$k2]['customer_info'],'company_order_customer_id'));
                    }
                }
            }
        }


        if($getTeamProductReceivableCompany['data']['team_product_other_info']){
            $getTeamProductReceivableCompany['data']['team_product_other_info'] = Arrays::sort($getTeamProductReceivableCompany['data']['team_product_other_info'],'payment_object_id','asc');
            foreach($getTeamProductReceivableCompany['data']['team_product_other_info'] as $k=>$v){
                  $getTeamProductReceivableCompany['data']['team_product_other_info'][$k]['product_source_type_id'] = $v['source_type_id'];
             }

            $vl['其他']['count'] = count($getTeamProductReceivableCompany['data']['team_product_other_info']);
            $d2 = Arrays::group($getTeamProductReceivableCompany['data']['team_product_other_info'],'payment_object_id');
            foreach($d2 as $ky=>$vy){
                $vl['其他']['data'][$ky]['count'] = count($vy);
                $vl['其他']['data'][$ky]['data'] = Arrays::group($vy,'order_number');
                foreach($vl['其他']['data'][$ky]['data'] as $k1=>$y1){
                    foreach($vl['其他']['data'][$ky]['data'][$k1] as $k2=>$y2){
                        $w['company_order_number'] = $k1;
                        $list = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$w);
                        $vl['其他']['data'][$ky]['data'][$k1][$k2]['youke'] = $list['data'];
                        $vl['其他']['data'][$ky]['data'][$k1][$k2]['customer_info'] = Arrays::keys(Arrays::group($vl['其他']['data'][$ky]['data'][$k1][$k2]['customer_info'],'company_order_customer_id'));
                    }
                }
            }
        }
        $this->assign('list',$vl);

        unset($where);
        $where['team_product_number'] = $number;
        $Receivable = $this->callSoaErp('post','/branchcompany/getCompanyOrderNumberByTeamProductNumber',$where);
        $ReceivableList = Arrays::group($Receivable['data'],'company_id');
//        var_dump($ReceivableList);exit;
        $this->assign('ReceivableList',$ReceivableList);
        unset($where);

        $where['status'] = 1;
        $branchs = $this->callSoaErp('post','/system/getCompany',$where);
        $this->assign('branchs',$branchs['data']);
        $this->assign('types',$this->types2);
        unset($where);

        $where['status'] = 1;
        $Currency = $this->callSoaErp('post','/system/getCurrency',$where);
        if(!empty($Currency['data'])){
            $this->assign('Currency',$Currency['data']);
        }
        unset($where);

        return $this->fetch('n_plan_receivable_branch');

    }

    /***
     * 应收分公司
     * Hugh
     */
    public function PlanReceivableBranch2(){
        $number = input('get.number'); //团队编号

        $where['team_product_number'] = $number;
        $getTeamProductReceivableCompany =  $this->callSoaErp('post','/product/getTeamProductReceivableCompany',$where);
//        echo '<pre>';
//        print_r($getTeamProductReceivableCompany);exit;


        $v = [];
        if($getTeamProductReceivableCompany['data']){
            if($getTeamProductReceivableCompany['data']['receivable_info']){
                foreach($getTeamProductReceivableCompany['data']['receivable_info'] as $k=>$v){
                    if($v['product_type']<=1){
                        $getTeamProductReceivableCompany['data']['receivable_info'][$k]['product_source_type_id'] = $v['product_type'];
                    }
                }

                $vl['团费']['count'] = count($getTeamProductReceivableCompany['data']['receivable_info']);
                $d2 = Arrays::group($getTeamProductReceivableCompany['data']['receivable_info'],'payment_company_id');
                foreach($d2 as $ky=>$vy){
                    $vl['团费']['data'][$ky]['count'] = count($vy);
                    $vl['团费']['data'][$ky]['data'] = Arrays::group($vy,'order_number');
                    foreach($vl['团费']['data'][$ky]['data'] as $k1=>$y1){
                        foreach($vl['团费']['data'][$ky]['data'][$k1] as $k2=>$y2){
                            $w['company_order_number'] = $k1;
                            $list = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$w);
                            $vl['团费']['data'][$ky]['data'][$k1][$k2]['youke'] = $list['data'];
                            $vl['团费']['data'][$ky]['data'][$k1][$k2]['customer_info'] = Arrays::keys(Arrays::group($vl['团费']['data'][$ky]['data'][$k1][$k2]['customer_info'],'company_order_customer_id'));
                        }
                    }
                }
            }

            if($getTeamProductReceivableCompany['data']['travel_agency_reimbursement_receivable_info']){
                foreach($getTeamProductReceivableCompany['data']['travel_agency_reimbursement_receivable_info'] as $k=>$v){
                    if($v['product_type']<=1){
                        $getTeamProductReceivableCompany['data']['travel_agency_reimbursement_receivable_info'][$k]['product_source_type_id'] = $v['product_type'];
                    }
                }

                $vl['地接']['count'] = count($getTeamProductReceivableCompany['data']['travel_agency_reimbursement_receivable_info']);
                $d2 = Arrays::group($getTeamProductReceivableCompany['data']['travel_agency_reimbursement_receivable_info'],'payment_company_id');
                foreach($d2 as $ky=>$vy){
                    $vl['地接']['data'][$ky]['count'] = count($vy);
                    $vl['地接']['data'][$ky]['data'] = Arrays::group($vy,'order_number');
                    foreach($vl['地接']['data'][$ky]['data'] as $k1=>$y1){
                        foreach($vl['地接']['data'][$ky]['data'][$k1] as $k2=>$y2){
                            $w['company_order_number'] = $k1;
                            $list = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$w);
                            $vl['地接']['data'][$ky]['data'][$k1][$k2]['youke'] = $list['data'];
                            $vl['地接']['data'][$ky]['data'][$k1][$k2]['customer_info'] = Arrays::keys(Arrays::group($vl['地接']['data'][$ky]['data'][$k1][$k2]['customer_info'],'company_order_customer_id'));
                        }
                    }
                }
            }


            if($getTeamProductReceivableCompany['data']['team_product_other_info']){
                foreach($getTeamProductReceivableCompany['data']['team_product_other_info'] as $k=>$v){
                    if($v['product_type']<=1){
                        $getTeamProductReceivableCompany['data']['team_product_other_info'][$k]['product_source_type_id'] = $v['product_type'];
                    }
                }

                $vl['其他']['count'] = count($getTeamProductReceivableCompany['data']['team_product_other_info']);
                $d2 = Arrays::group($getTeamProductReceivableCompany['data']['team_product_other_info'],'payment_company_id');
                foreach($d2 as $ky=>$vy){
                    $vl['其他']['data'][$ky]['count'] = count($vy);
                    $vl['其他']['data'][$ky]['data'] = Arrays::group($vy,'order_number');
                    foreach($vl['其他']['data'][$ky]['data'] as $k1=>$y1){
                        foreach($vl['其他']['data'][$ky]['data'][$k1] as $k2=>$y2){
                            $w['company_order_number'] = $k1;
                            $list = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$w);
                            $vl['其他']['data'][$ky]['data'][$k1][$k2]['youke'] = $list['data'];
                            $vl['其他']['data'][$ky]['data'][$k1][$k2]['customer_info'] = Arrays::keys(Arrays::group($vl['其他']['data'][$ky]['data'][$k1][$k2]['customer_info'],'company_order_customer_id'));
                        }
                    }
                }
            }

        }
        $this->assign('list',$vl);
        unset($where);

        $where['team_product_number'] = $number;
        $Receivable = $this->callSoaErp('post','/finance/getReceivable',$where);
        $ReceivableList = Arrays::group($Receivable['data'],'payment_company_id');
        $this->assign('ReceivableList',$ReceivableList);

//        echo '<pre>';
//        print_r($ReceivableList);exit;
//
//        echo '<pre>';
//        print_r($vl['其他']);exit;


        unset($where);
        $where['status'] = 1;
        $branchs = $this->callSoaErp('post','/system/getCompany',$where);
        $this->assign('branchs',$branchs['data']);
        $this->assign('types',$this->types2);
        unset($where);
        $where['status'] = 1;
        $Currency = $this->callSoaErp('post','/system/getCurrency',$where);
        if(!empty($Currency['data'])){
            $this->assign('Currency',$Currency['data']);
        }
        unset($where);

        return $this->fetch('plan_receivable_branch');
    }

    /*
     * 订单
     * Hugh
     */
    public function PlanBooking(){
        $plan_id = input('get.plan_id');
        $number = input('get.number');

        $getCompany = $this->callSoaErp('post','/system/getCompany',['status'=>1]);
        $this->assign('Company',$getCompany['data']);

        $where['team_product_number'] = $number;
        if($_GET['search_booking']){
            $where['company_order_number'] = $_GET['search_booking'];
        }
        if($_GET['search_company_id'] && is_numeric($_GET['search_company_id'])){
            $where['company_id'] = $_GET['search_company_id'];
        }
        if($_GET['search_visitor']){

        }
        if($_GET['search_founder']){
            $where['nickname'] = $_GET['search_founder'];
        }

//        var_dump($where);

        $order = $this->callSoaErp('post','/branchcompany/getCompanyOrderNumberByTeamProductNumber',$where);
//        var_dump($order);exit;
        if($order['data'])
             $this->assign('order',$order['data']);

        return $this->fetch('plan_booking');
    }

    /**
     * 团队订单搜索
     */
    public function setPlanBooking(){
        Session::set('setPlanBooking',$_POST);
        $this->redirect('/product/PlanBooking');
    }

    /**
     * 取消团队订单搜索条件
     */
    public function clearPlanBooking(){
        Session::delete('setPlanBooking');
        $this->redirect('/product/PlanBooking');
    }


    /**
     * 订单详情
     * Hugh
     */
    public function PlanBookingInfo(){
        $company_order_number = input('get.bookingId');
        $where['company_order_number'] = $company_order_number;
        $where['status'] = 1;
        $where['team_product_number'] =input('number');
        $orderInfo = $this->callSoaErp('post','/branchcompany/getCompanyOrderProduct',$where);
//		echo '<pre>';print_r($orderInfo);exit;

        if(!empty($orderInfo['data'])){
            //团队产品
            if(!empty($orderInfo['data']['company_order_product_team'])){
                foreach($orderInfo['data']['company_order_product_team'] as $k=>$v){
                    $orderInfo['data']['company_order_product_team'][$k]['customer_info_number'] = implode(',',Arrays::keys(Arrays::group($v['customer_info'],'team_product_lineup_number')));
                    $orderInfo['data']['company_order_product_team'][$k]['customer_info_name'] = implode(',',array_column($v['customer_info'],'customer_name'));
                }
                $this->assign('company_order_product_team',$orderInfo['data']['company_order_product_team']);
            }
       		
            //自费项目
            if(!empty($orderInfo['data']['company_order_product_source'])){
                $company_order_product_source = Arrays::group($orderInfo['data']['company_order_product_source'],'supplier_type_id');
                if($company_order_product_source[11]){
                    $company_order_product_source_11 = $company_order_product_source[11];
                    foreach($company_order_product_source_11 as $k=>$v){
                        $company_order_product_source_11[$k]['customer_info_number'] = implode(',',Arrays::keys(Arrays::group($v['customer_info'],'team_product_lineup_number')));

                        $company_order_product_source_11[$k]['customer_info_name'] = implode(',',array_column($v['customer_info'],'customer_name'));
                    }
                    $this->assign('company_order_product_source_11',$company_order_product_source_11);
                }
            }

        }
		
        unset($where);
        // 游客信息
        $where['company_order_number'] = $company_order_number;
        $CompanyOrderCustomer = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$where);
//        echo '<pre>';print_r($CompanyOrderCustomer);exit;

        if(!empty($CompanyOrderCustomer['data'])){
            foreach($CompanyOrderCustomer['data'] as $k=>$v){
                $CompanyOrderCustomer['data'][$k]['accommodation_info'][0]['room_code'] = $v['room_code']?:'-';
                $CompanyOrderCustomer['data'][$k]['accommodation_info'][0]['room_type'] = $v['room_type']?:0;
                $CompanyOrderCustomer['data'][$k]['accommodation_info'][0]['check_in'] = $v['check_in']?:0;
                $CompanyOrderCustomer['data'][$k]['accommodation_info'][0]['check_on'] = $v['check_on']?:0;


                if($CompanyOrderCustomer['data'][$k]['flight_info']){
                    foreach($CompanyOrderCustomer['data'][$k]['flight_info'] as $ky=>$vy){
                        $CompanyOrderCustomer['data'][$k]['flight_info'][$ky]['flight_code'] = $CompanyOrderCustomer['data'][$k]['flight_info'][$ky]['flight_code']?:'-';
                        $CompanyOrderCustomer['data'][$k]['flight_info'][$ky]['flight_type'] = $CompanyOrderCustomer['data'][$k]['flight_info'][$ky]['flight_type']?:0;
                        $CompanyOrderCustomer['data'][$k]['flight_info'][$ky]['start_place'] = $CompanyOrderCustomer['data'][$k]['flight_info'][$ky]['start_place']?:'-';
                        $CompanyOrderCustomer['data'][$k]['flight_info'][$ky]['end_place'] = $CompanyOrderCustomer['data'][$k]['flight_info'][$ky]['end_place']?:'-';
                        $CompanyOrderCustomer['data'][$k]['flight_info'][$ky]['flight_begin_time'] = $CompanyOrderCustomer['data'][$k]['flight_info'][$ky]['flight_begin_time']?date('Y-m-d H:i:s',$CompanyOrderCustomer['data'][$k]['flight_info'][$ky]['flight_begin_time']):'-';
                        $CompanyOrderCustomer['data'][$k]['flight_info'][$ky]['flight_end_time'] = $CompanyOrderCustomer['data'][$k]['flight_info'][$ky]['flight_end_time']?date('Y-m-d H:i:s',$CompanyOrderCustomer['data'][$k]['flight_info'][$ky]['flight_end_time']):'-';
                    }
//                    var_dump($CompanyOrderCustomer['data'][$k]['flight_info']);
                }
            }
            $this->assign('CompanyOrderCustomer',$CompanyOrderCustomer['data']);
        }
////        var_dump($CompanyOrderCustomer['data'][3]['flight_info']);exit;
		
        $this->assign('orderState',$this->orderState);
        $this->assign('customer_type',$this->customer_type);

        return $this->fetch('plan_booking_info');
    }

    /***
     * 添加 编辑 乘客
     * Hugh
     */
    public function PlanBookingPassenger(){
        $passengers_id = input('get.passengers_id');
        $bookingId = input('get.bookingId');
        $where['company_order_number'] = $bookingId;
        $where['company_order_customer_id'] = $passengers_id;
        $CompanyOrderCustomer = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$where);

//        echo '<pre>';print_r($CompanyOrderCustomer);exit;

        if(!empty($CompanyOrderCustomer['data'])){
            $this->assign('v',$CompanyOrderCustomer['data'][0]);
        }
        unset($where);
        $where['pid'] = 0;
        $where['status'] = 1;
        $Country  = $this->callSoaErp('post','/system/getCountry',$where);
        if(!empty($Country['data'])){
            $this->assign('Country',$Country['data']);
        }
        unset($where);
        $where['status'] = 1;
        $Language = $this->callSoaErp('post','/system/getLanguage',$where);
        if(!empty($Language['data'])){
            $this->assign('Language',$Language['data']);
        }

        $this->assign('orderState',$this->orderState);
        $this->assign('customer_type',$this->customer_type);
        $this->assign('room_type',$this->room_type);
//        var_dump($CompanyOrderCustomer);exit; 111

//        return $this->fetch('plan_booking_passenger');

        return $this->fetch('n_plan_booking_passenger');
    }


    public function nSavePlanBookingPassengerAjax(){
        $param = Request::instance()->param();

//        echo '<pre>';print_r($param);exit;

        $d['company_order_number'] = Arrays::get($param,'bookingId');
        $d['passport_number'] = Arrays::get($param,'passport_number');
        $d['company_order_customer_id'] = Arrays::get($param,'passengers_id');
        $d['customer_first_name'] = Arrays::get($param,'customer_first_name');
        $d['customer_last_name'] = Arrays::get($param,'customer_last_name');
        $d['middle_name'] = Arrays::get($param,'middle_name');
        $d['gender'] = Arrays::get($param,'gender');
        $d['country_id'] = Arrays::get($param,'country_id');
        $d['birthday'] = Arrays::get($param,'birthday')?strtotime(Arrays::get($param,'birthday')):'';
        $d['language_id'] = Arrays::get($param,'language_id');
        $d['customer_type'] = Arrays::get($param,'customer_type');
        $d['phone'] = Arrays::get($param,'phone');
        $d['issuing_date'] = Arrays::get($param,'issuing_date')?:'';
        $d['term_of_validity'] = Arrays::get($param,'term_of_validity')?:'';
        $d['email'] = Arrays::get($param,'email');
        $d['emergency_contact'] = Arrays::get($param,'emergency_contact');
        $d['emergency_call'] = Arrays::get($param,'emergency_call');
        $d['address'] = Arrays::get($param,'address');
        $d['status'] = Arrays::get($param,'status');

        //住宿信息
        $d['room_code'] = Arrays::get($param,'room_code');
        $d['room_type'] = Arrays::get($param,'room_type');
        $d['check_in'] = Arrays::get($param,'check_in');
        $d['check_on'] = Arrays::get($param,'check_on');
        $check_in_edit = Arrays::get($param,'check_in_edit');
        $check_on_edit = Arrays::get($param,'check_on_edit');

//        $check_in = [];
//        $check_on = [];
//        foreach($check_in_edit as $k=>$v){
//            if($v){
//                $check_in[] = $v;
//            }
//        }
//        foreach($check_on_edit as $k=>$v){
//            if($v){
//                $check_on[] = $v;
//            }
//        }
        $d['check_in_hotel'] = implode(',',$check_in_edit);
        $d['check_on_hotel'] = implode(',',$check_on_edit);

        //航班
        $d['flight_info'] = [];
        $flight_code = Arrays::get($param,'flight_code');
        $start_place = Arrays::get($param,'start_place');
        $end_place = Arrays::get($param,'end_place');
        $flight_begin_time = Arrays::get($param,'flight_begin_time');
        $flight_end_time = Arrays::get($param,'flight_end_time');
        $flight_type = Arrays::get($param,'flight_type');
        $remark = Arrays::get($param,'remark');
        foreach($flight_code as $k=>$v){
            $ary['flight_code'] = $flight_code[$k]?:'';
            $ary['flight_begin_time'] = $flight_begin_time[$k]?strtotime($flight_begin_time[$k]):'';
            $ary['flight_end_time'] = $flight_end_time[$k]?strtotime($flight_begin_time[$k]):'';
            $ary['flight_type'] = $flight_type[$k]?:'';
            $ary['start_place'] = $start_place[$k]?:'';
            $ary['end_place'] = $end_place[$k]?:'';
            $ary['remark'] = $remark[$k]?:'';
            $d['flight_info'][] = $ary;
            unset($ary);
        }

        return $this->callSoaErp('post','/branchcompany/updateCompanyOrderCustomerByCompanyOrderCustomerId',$d);

    }

    /**
     * 乘客信息修改
     * Hugh
     */
    public function SavePlanBookingPassengerAjax(){
        $param = Request::instance()->param();

        $d['company_order_number'] = Arrays::get($param,'bookingId');
        $d['company_order_customer_id'] = Arrays::get($param,'passengers_id');
        $d['user_id'] = Session('user')['user_id'];
        $d['company_id'] = Session('user')['company_id'];
        $d['customer_last_name'] = Arrays::get($param,'customer_last_name');
        $d['customer_first_name'] = Arrays::get($param,'customer_first_name');
        $d['english_last_name'] = Arrays::get($param,'english_last_name');
        $d['english_first_name'] = Arrays::get($param,'english_first_name');
        $d['customer_type'] = Arrays::get($param,'customer_type');
        $d['gender'] = Arrays::get($param,'gender');
        $d['country_id'] = Arrays::get($param,'country_id');
        $d['language_id'] = Arrays::get($param,'language_id');
        $d['phone'] = Arrays::get($param,'phone');
        $d['email'] = Arrays::get($param,'email');
        $d['card_type'] = Arrays::get($param,'card_type');
        $d['card_number'] = Arrays::get($param,'card_number');
        $d['term_of_validity'] = strtotime(Arrays::get($param,'term_of_validity'));
        $d['status'] = Arrays::get($param,'status');
        $d['company_id'] = session('user')['company_id'];
        //航班
        $d['customer_flight'] = [];
        $flight_code = Arrays::get($param,'flight_code');
        $flight_type = Arrays::get($param,'flight_type');
        $flight_begin_time = Arrays::get($param,'flight_begin_time');
        $flight_end_time = Arrays::get($param,'flight_end_time');
        $start_place = Arrays::get($param,'start_place');
        $end_place = Arrays::get($param,'end_place');
        foreach($flight_code as $k=>$v){
            $customer_flight['flight_code'] = $flight_code[$k];
            $customer_flight['flight_begin_time'] = strtotime($flight_begin_time[$k]);
            $customer_flight['flight_end_time'] = strtotime($flight_end_time[$k]);
            $customer_flight['flight_type'] = $flight_type[$k];
            $customer_flight['start_place'] = $start_place[$k];
            $customer_flight['end_place'] = $end_place[$k];
            $d['customer_flight'][] = $customer_flight;
            unset($customer_flight);
        }

        //用房
        $d['room_code'] = Arrays::get($param,'room_code');
        $d['room_type'] = Arrays::get($param,'room_type');
        $d['check_in'] = Arrays::get($param,'check_in',0);
        $d['check_on'] = Arrays::get($param,'check_on',0);
        $check_in_edit = Arrays::get($param,'check_in_edit');
        $check_on_edit = Arrays::get($param,'check_on_edit');

        $check_in = [];
        $check_on = [];
        foreach($check_in_edit as $k=>$v){
            if($v){
                $check_in[] = $v;
            }
        }
        foreach($check_on_edit as $k=>$v){
            if($v){
                $check_on[] = $v;
            }
        }
        $d['check_in_hotel'] = implode(',',$check_in);
        $d['check_on_hotel'] = implode(',',$check_on);

        return $this->callSoaErp('post','/branchcompany/updateCompanyOrderCustomerByCompanyOrderCustomerId',$d);

//        var_dump($param);
        exit;
    }


    public function updateCompanyOrderCustomerStatusByCompanyOrderCustomerId(){
        $company_order_customer_id = input('post.company_order_customer_id');
        $ar = explode('-',$company_order_customer_id);
        $up['company_order_customer_id'] = $ar[1];
        $up['status'] = $ar[0];
        return $this->callSoaErp('post','/branchcompany/updateCompanyOrderCustomerStatusByCompanyOrderCustomerId',$up);
    }

    //批量确认
    public function BatchConfirmationAjax(){
        $company_order_customer_id = input('post.et');
        $ar = explode('-',$company_order_customer_id);
        foreach($ar as $v){
            $up['company_order_customer_id'] = $v;
            $up['status'] = 3;
            $this->callSoaErp('post','/branchcompany/updateCompanyOrderCustomerStatusByCompanyOrderCustomerId',$up);
        }
        return ['code'=>200];
    }


    /**
     * 分团
     * Hugh
     */
    public function PlanSubgroupAjax(){
        $newQ = $_POST['newQ'];
        $ay['code'] = 400;
        if(empty($newQ)){
            return $ay;
        }
        $ay['code'] = 200;
        $ay['data']['booking'] = $newQ;

        return $ay;
    }

    /**
     * 确认分团
     * Hugh
     */
    public function ConfirmPlanSubgroupAjax(){
        $param = Request::instance()->param();
        $ay['code'] = 400;
        return $ay;
    }


    /**
     * 确认拼团
     * Hugh
     */
    public function ConfirmConglomerationAjax(){
        $param = Request::instance()->param();
        $ay['code'] = 400;
        return $ay;
    }



    /***
     * 拼团
     * Hugh
     */
    public function ConglomerationAjax(){
        $newQ = $_POST['newQ'];
        $ary['code'] = 400;
        if(empty($newQ)){
           return $ary;
        }

        $varList = [];
        foreach($newQ as $ky){
            $where['team_product_id'] = $ky;
            $list = $this->callSoaErp('post','/product/getTeamProductBase',$where);
            if(!empty($list['data']) && $list['code']==200){
                $ar['team_product_id'] = $list['data'][0]['team_product_id'];
                $ar['team_product_number'] = $list['data'][0]['team_product_number'];
                $ar['team_product_name'] = $list['data'][0]['team_product_name'];
                $varList[] = $ar;
                unset($ar);
            }
        }
        $ary['code'] = 200;
        $ary['data'] = $varList;
        return $ary;
    }


    /***
     * 团队产品
     * Hugh 18-08-29
     */
    public function ShowPlanTour(){
        //线路类型
        $data['status'] = 1;
        $list = $this->callSoaErp('post','/system/getRouteType',$data);
        if($list['data']){
            $RouteType = Arrays::group($list['data'],'type');
            $this->assign('RouteType',$RouteType);
        }
        $this->assign('PlanTourState',$this->PlanTourState);
        $this->assign('is_establish_team_product',$this->is_establish_team_product);
        //获取负责人
        $data['department_id'] = session('user')['department_id'];
        $userList = $this->callSoaErp('post','/user/getUser',$data);
        if($userList['data']){
            $this->assign('userList',$userList['data']);
        }
        unset($data['department_id']);
        //列表
        $setPlanTourManage = Session::get('setPlanTourManage');
//        var_dump($setPlanTourManage);exit;

        $where = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        if($setPlanTourManage['search_code']){ //产品编号
            $where['team_product_number'] = $setPlanTourManage['search_code'];
        }
        if($setPlanTourManage['search_name']){  //团队名称
            $where['team_product_name'] = $setPlanTourManage['search_name'];
        }
        if($setPlanTourManage['search_customer']){ //客户

        }
        if($setPlanTourManage['search_responsible_person']){ //线路负责人

        }
        if($setPlanTourManage['search_route_type']){ //类型
            $where['route_type_id'] = $setPlanTourManage['search_route_type'];
        }
        $where['status'] = 'a';
        if(is_numeric($setPlanTourManage['search_status']) && $setPlanTourManage['search_status']>=0){ //状态
            $where['status'] = $setPlanTourManage['search_status'];
        }else{
            $setPlanTourManage['search_status'] = -1;
        }


        $where['company_id'] = session('user')['company_id'];

        $TeamProductList = $this->callSoaErp('post','/product/getTeamProductBase',$where);
//        echo '<pre>';print_r($TeamProductList);exit;

        if($TeamProductList['data']){
            $TeamProductList['data'] = Arrays::sort($TeamProductList['data'],'begin_time','desc');
            $this->getPageParams($TeamProductList);
        }
        $this->assign('setPlanTourManage',$setPlanTourManage);
        return $this->fetch('plan_tour_manage');
    }

    //团队产品搜索
    public function setPlanTourManage(){
        Session::set('setPlanTourManage',$_POST);
        $this->redirect('/product/ShowPlanTour');
    }
    public function clearPlanTourManage(){
        Session::delete('setPlanTourManage');
        $this->redirect('/product/ShowPlanTour');
    }


    /**
     * 团队产品提交保存
     * Hugh
     */
    public function PlanTourAddAjax(){
        $param = Request::instance()->param();
//        var_dump($param);exit;
        $dateData = Arrays::get($param,'all_time_name',[]);

        //出团日期为空放回
        if(empty($dateData)){
            $ar['code'] = 400;
            $ar['msg'] = '请添加出团日期再试';
            $ar['data'] = '';
            return $ar;
        }

        $route_template_id = Arrays::get($param,'route_template_id'); //线路模板
        $use_company_id = Arrays::get($param,'use_company_id'); //可见分公司

        //航班
        $flight_number = Arrays::get($param,'flight_number');
        $flight_type = Arrays::get($param,'flight_type');
        $fight_start_city = Arrays::get($param,'fight_start_city');
        $fight_start_time = Arrays::get($param,'fight_start_time');
        $fight_end_city = Arrays::get($param,'fight_end_city');
        $fight_end_time = Arrays::get($param,'fight_end_time');
        $fight_the_days = Arrays::get($param,'fight_the_days');

        //行程
        $journey_title = Arrays::get($param,'journey_title');
        $journey_content = Arrays::get($param,'journey_content');
        $journey_traffic = Arrays::get($param,'journey_traffic');
        $journey_stay = Arrays::get($param,'journey_stay');
        $eat_mark = Arrays::get($param,'eat_mark');
        $journey_breakfast = Arrays::get($param,'journey_breakfast');
        $journey_lunch = Arrays::get($param,'journey_lunch');
        $journey_dinner = Arrays::get($param,'journey_dinner');
        $journey_scenic_sport = Arrays::get($param,'journey_scenic_sport');
        $journey_picture = Arrays::get($param,'journey_picture');
        $journey_remark = Arrays::get($param,'journey_remark');
        $country_id = Arrays::get($param,'country_id');

        $OneBitePrice = Arrays::get($param,'OneBitePrice');//一口价
        $settlement_type = Arrays::get($param,'settlement_type'); //结算方式
        $tourPrice = Arrays::get($param,'tourPrice',[]); //真实报价
        $tourCurrency = Arrays::get($param,'tourCurrency',[]); //真实报价货币

        //获取资源配置
        $session_hotel = Session::get('session_hotel'); //酒店
        $session_dining = Session::get('session_dining'); //用餐
        $session_flight = Session::get('session_flight'); //航班
        $session_cruise = Session::get('session_cruise');//邮轮
        $session_visa = Session::get('session_visa');//签证
        $session_scenic_spot = Session::get('session_scenic_spot'); //景点
        $session_vehicle = Session::get('session_vehicle'); //车辆
        $session_tourGuide = Session::get('session_tourGuide'); //导游
        $session_singleSource = Session::get('session_singleSource'); //单项资源
        $session_Optional = Session::get('session_Optional'); //自费项目

        foreach($dateData as $dateDataV){
            $d['begin_time'] = strtotime($dateDataV);
            $d['team_product_name'] = Arrays::get($param,'team_product_name');
            $d['route_template_id'] = 0;
            if($route_template_id){
                $d['route_template_id'] = $route_template_id;
            }
            $d['use_company_id'] = '*';
            if($use_company_id){
                $d['use_company_id'] = $use_company_id;
            }
            $d['route_type_id'] = Arrays::get($param,'route_type_id');

            $d['settlement_type'] = Arrays::get($param,'settlement_type');
            $d['team_product_user_id'] = Arrays::get($param,'team_product_user_id');
            $d['before_days'] = Arrays::get($param,'before_days',1);
            $d['plan_custom_number'] = Arrays::get($param,'plan_custom_number');
            $d['user_id'] = session('user')['user_id'];
            $d['status'] = Arrays::get($param,'status',2);
            $d['company_id'] = session('user')['company_id'] ;

            //航班
            $d['team_product_flight_info'] = [];
            foreach($flight_number as $ky=>$flight_numberV){
                $ar['the_days'] = $fight_the_days[$ky];
                $ar['flight_number'] = $flight_number[$ky];
                $ar['flight_type'] = empty($flight_type[$ky])?3:$flight_type[$ky];
                $ar['start_city'] = $fight_start_city[$ky];
                $ar['end_city'] = $fight_end_city[$ky];
                $ar['start_time'] = strtotime(date('Y-m-d').' '.$fight_start_time[$ky]);
                $ar['end_time'] = strtotime(date('Y-m-d').' '.$fight_end_time[$ky]);
                $d['team_product_flight_info'][] = $ar;
                unset($ar);
            }
            //行程
            $d['team_product_journey_info'] = [];
            foreach($journey_title as $ky=>$journey_titleV){
                $ar['the_days'] = $ky;
                $ar['route_journey_title'] = $journey_title[$ky];
                $ar['route_journey_content'] = $journey_content[$ky];
                $ar['route_journey_traffic'] = $journey_traffic[$ky];
                $ar['route_journey_stay'] = $journey_stay[$ky];
                $ar['eat_mark'] = empty($eat_mark[$ky])?'':implode(',',$eat_mark[$ky]);
                $ar['route_journey_breakfast'] = $journey_breakfast[$ky];
                $ar['route_journey_lunch'] = $journey_lunch[$ky];
                $ar['route_journey_dinner'] = $journey_dinner[$ky];
                $ar['route_journey_scenic_sport'] = $journey_scenic_sport[$ky];
                $ar['route_journey_picture'] = empty($journey_picture[$ky])?'':implode(',',$journey_picture[$ky]);
                $ar['route_journey_remark'] = $journey_remark[$ky];
                $ar['route_journey_zone'] = $country_id[$ky];
                $d['team_product_journey_info'][] = $ar;
                unset($ar);
            }

            //回执单模板
            $d['team_product_return_receipt'] = [];
            $ReturnReceiptTitle  = Arrays::get($param,'ReturnReceiptTitle');
            $ReturnReceiptSorting = Arrays::get($param,'ReturnReceiptSorting');
            $ReturnReceiptContent = Arrays::get($param,'ReturnReceiptContent');
            foreach($ReturnReceiptTitle as $ky=>$ReturnReceiptTitleV){
                $ar['title'] = $ReturnReceiptTitle[$ky];
                $ar['content'] = $ReturnReceiptContent[$ky];
                $ar['sorting'] = $ReturnReceiptSorting[$ky];
                $d['team_product_return_receipt'][] = $ar;
            }

            //资源
            $d['team_product_allocation_info'] = [];
            //酒店
            $d = $this->setTeamProductAllocationInfo($d,2,$session_hotel,'room_name',$tourPrice,$tourCurrency);
            //用餐
            $d = $this->setTeamProductAllocationInfo($d,3,$session_dining,'dining_name',$tourPrice,$tourCurrency);
            //航班
            $d = $this->setTeamProductAllocationInfo($d,4,$session_flight,'flight_number',$tourPrice,$tourCurrency);
            //邮轮
            $d = $this->setTeamProductAllocationInfo($d,5,$session_cruise,'cruise_name',$tourPrice,$tourCurrency);
            //签证
            $d = $this->setTeamProductAllocationInfo($d,6,$session_visa,'visa_name',$tourPrice,$tourCurrency);
            //景点
            $d = $this->setTeamProductAllocationInfo($d,7,$session_scenic_spot,'scenic_spot_name',$tourPrice,$tourCurrency);
            //车辆
            $d = $this->setTeamProductAllocationInfo($d,8,$session_vehicle,'vehicle_name',$tourPrice,$tourCurrency);
            //导游
            $d = $this->setTeamProductAllocationInfo($d,9,$session_tourGuide,'tour_guide_name',$tourPrice,$tourCurrency);
            //单项资源
            $d = $this->setTeamProductAllocationInfo($d,10,$session_singleSource,'single_source_name',$tourPrice,$tourCurrency);
            //自费项目
            $d = $this->setTeamProductAllocationInfo($d,11,$session_Optional,'own_expense_name',$tourPrice,$tourCurrency);


            //一口价
            $OneBitePriceCurrency = Arrays::get($param,'OneBitePriceCurrency');
            $d['team_product_once_price'] = [];
            if($settlement_type==1){
                foreach($OneBitePrice as $ky=>$OneBitePriceV){
                    $ar['company_id'] = $ky;
                    $ar['total_price'] = $OneBitePriceV;
                    $ar['team_price_currency_id'] = $OneBitePriceCurrency[$ky]; //一口价货币
                    $d['team_product_once_price'][] = $ar;
                    unset($ar);
                }
            }

            $d['status'] = 3; //审批
            $retun = $this->callSoaErp('post','/product/addTeamProduct',$d);

            if(!empty($retun['data'])){
                $d['status'] = Arrays::get($param,'status',2);
                //数据已 JSON格式 保存到审批表
                $_examine_and_approve['pk_id'] = $retun['data'];
                $_examine_and_approve['company_id'] = session('user')['company_id'];
                $_examine_and_approve['approval_type_id']=12; // 表 approval_type
                $_examine_and_approve['examination_and_approval_content'] = json_encode($d);
                $_examine_and_approve['remarks'] = '新建团队产品';
                $_examine_and_approve['user_id'] = session('user')['user_id'];
                $_examine_and_approve['status']=1;
                $r = $this->callSoaErp('post','/examine_and_approve/addExamineAndApprove',$_examine_and_approve);
            }
            unset($_examine_and_approve);
            unset($d);

//            var_dump($return);exit;
//            var_dump($return);exit;
//            exit;

        }
        return ['code'=>200];
    }

    /**
     * 匹配session资源存取
     * @param $d 写入数据
     * @param $supplier_type_id 供应商类型
     * @param $source_data  session数据
     * @param $source_ky 资源ID对应的字段
     * @param $tourPrice 真实报价
     * @param $tourCurrency 真实报价货币
     * @return mixed 返回写入数据
     */
   public function setTeamProductAllocationInfo($d,$supplier_type_id,$source_data,$source_ky,$tourPrice,$tourCurrency){
        foreach($source_data as $vl){
            $ar['supplier_type_id'] = $supplier_type_id;
            $ar['source_id'] = $vl[$source_ky];
//            $ar['payment_currency_id'] = $vl['currency'];
            $ar['source_price'] = $vl['unit_price'];
            $ar['source_count'] = $vl['quantity'];
            $ar['source_total_price'] = $tourPrice[$supplier_type_id][$vl[$source_ky]]?:$vl['total'];
            $ar['payment_currency_id'] = $tourCurrency[$supplier_type_id][$vl[$source_ky]]?:$vl['currency'];
            $ar['status'] = 1;
            $ar['source_the_days'] = $vl['the_day'];
            $d['team_product_allocation_info'][] = $ar;
            unset($ar);
        }
        return $d;
    }

    /**
     * 匹配session资源存取
     * @param $d 写入数据
     * @param $supplier_type_id 供应商类型
     * @param $source_data  session数据
     * @param $source_ky 资源ID对应的字段
     * @param $tourPrice 真实报价
     * @param $tourCurrency 真实报价货币
     * @param $team_product_id 团队产品ID
     * @return mixed 返回写入数据
     */
    public function setTeamProductAllocationInfoUpdate($d,$supplier_type_id,$source_data,$source_ky,$tourPrice,$tourCurrency,$team_product_id){
        foreach($source_data as $vl){
            $ar['supplier_type_id'] = $supplier_type_id;
            $ar['source_id'] = $vl[$source_ky];
//            $ar['payment_currency_id'] = $vl['currency'];
            $ar['source_price'] = $vl['unit_price'];
            $ar['source_count'] = $vl['quantity'];
            $ar['source_total_price'] = $tourPrice[$supplier_type_id][$vl[$source_ky]]?:$vl['total'];
            $ar['payment_currency_id'] = $tourCurrency[$supplier_type_id][$vl[$source_ky]]?:$vl['currency'];
            $ar['status'] = 1;
            $ar['source_the_days'] = $vl['the_day'];
            if(empty($vl['team_product_allocation_id'])){
                $ar['team_product_id'] = $team_product_id;
                $d['add_allocation'][] = $ar;
            }else{
                $ar['team_product_allocation_id'] = $vl['team_product_allocation_id'];
                $d['edit_allocation'][] = $ar;
            }

            unset($ar);
        }
        return $d;
    }


    /**
     * 获取资源Session所需要的值(线路模板专用)
     * @param $session  session名
     * @param $data  数据
     * @param $supplier_type_id 供应商类型
     * @param $url 资源数据所对应的url
     * @param $ky  资源的主键
     */
    public function resettingResourceSession2($session,$data,$supplier_type_id,$url,$ky){
//        获取货币
        $getCurrency = $this->callSoaErp('post','/system/getCurrency',['status'=>1]);
        $CurrencyGroup = Arrays::group($getCurrency['data'],'currency_id');

        $Sar = [];
//        var_dump($data);exit;
        foreach($data as $vl){
            $where[$ky] = $vl['source_id'];
            $list = $this->callSoaErp('post',$url,$where);


            if(!empty($list['data'])){
                if($supplier_type_id==2){
                    $ar['room_name'] = $vl['source_id'];
                    $ar['room_type'] = $list['data'][0]['room_type'];
                }
                if($supplier_type_id==3){
                    $ar['dining_name'] = $vl['source_id'];
                    $ar['standard_type'] = $list['data'][0]['standard_type'];
                }
                if($supplier_type_id==4){
                    $ar['flight_number'] = $vl['source_id'] ;
                    $ar['shipping_space'] = $list['data'][0]['shipping_space'];
                }
                if($supplier_type_id==5){
                    $ar['cruise_name'] = $vl['source_id'];
                    $ar['room_name'] = $list['data'][0]['room_name'];
                }
                if($supplier_type_id==6){
                    $ar['visa_name'] = $vl['source_id'];
                    $ar['file_url'] = $list['data'][0]['file_url'];
                }
                if($supplier_type_id==7){
                    $ar['scenic_spot_name'] = $vl['source_id'];
                }
                if($supplier_type_id==8){
                    $ar['vehicle_name'] = $vl['source_id'];
                    $ar['vehicle_number'] = $list['data'][0]['vehicle_number'];
                    $ar['load'] = $list['data'][0]['load'];
                }
                if($supplier_type_id==9){
                    $ar['tour_guide_name'] = $vl['source_id'];
                    $ar['guide_id_card'] = $list['data'][0]['guide_id_card'];
                    $ar['phone'] = $list['data'][0]['phone'];
                }
                if($supplier_type_id==10){
                    $ar['single_source_name'] = $vl['source_id'];
                }
                if($supplier_type_id==11){
                    $ar['own_expense_name'] = $vl['source_id'];
                }


                $ar['supplier_id'] = $list['data'][0]['supplier_type']==1?$list['data'][0]['supplier_id']:$list['data'][0]['belong_supplier_id'];
                $ar['agent_id'] = $list['data'][0]['supplier_type']==2?$list['data'][0]['supplier_id']:'';
                $ar['quantity'] = $vl['source_count'];
                $ar['unit_value_type'] = 1;
                $ar['currency'] = $vl['payment_currency_id'];
//                $ar['unit_price'] = $vl['source_price'];
                $ar['cost_price'] = $list['data'][0]['normal_price'];
                $ar['unit_price'] = $list['data'][0]['normal_settlement_price'];
                $ar['symbol'] = $CurrencyGroup[$list['data'][0]['payment_currency_type']][0]['symbol'];
                $ar['the_day'] = $vl['source_the_days'];
                $ar['total'] = $vl['source_total_price'];
                $ar['team_product_allocation_id'] = $vl['route_source_allocation_id'];
                $Sar[] = $ar;
            }
        }
        if(!empty($Sar)){
            Session::set($session,$Sar);
        }


    }


    /**
     * 获取资源Session所需要的值
     * @param $session  session名
     * @param $data  数据
     * @param $supplier_type_id 供应商类型
     * @param $url 资源数据所对应的url
     * @param $ky  资源的主键
     */
    public function resettingResourceSession($session,$data,$supplier_type_id,$url,$ky){
        //        获取货币
        $getCurrency = $this->callSoaErp('post','/system/getCurrency',['status'=>1]);
        $CurrencyGroup = Arrays::group($getCurrency['data'],'currency_id');

        $Sar = [];
//        var_dump($data);exit;
        foreach($data as $vl){
            $where[$ky] = $vl['source_id'];
            $list = $this->callSoaErp('post',$url,$where);

//            var_dump($list);
            if(!empty($list['data'])){
                if($supplier_type_id==2){
                    $ar['room_name'] = $vl['source_id'];
                    $ar['room_type'] = $list['data'][0]['room_type'];
                }
                if($supplier_type_id==3){
                    $ar['dining_name'] = $vl['source_id'];
                    $ar['standard_type'] = $list['data'][0]['standard_type'];
                }
                if($supplier_type_id==4){
                    $ar['flight_number'] = $vl['source_id'] ;
                    $ar['shipping_space'] = $list['data'][0]['shipping_space'];
                }
                if($supplier_type_id==5){
                    $ar['cruise_name'] = $vl['source_id'];
                    $ar['room_name'] = $list['data'][0]['room_name'];
                }
                if($supplier_type_id==6){
                    $ar['visa_name'] = $vl['source_id'];
                    $ar['file_url'] = $list['data'][0]['file_url'];
                }
                if($supplier_type_id==7){
                    $ar['scenic_spot_name'] = $vl['source_id'];
                }
                if($supplier_type_id==8){
                    $ar['vehicle_name'] = $vl['source_id'];
                    $ar['vehicle_number'] = $list['data'][0]['vehicle_number'];
                    $ar['load'] = $list['data'][0]['load'];
                }
                if($supplier_type_id==9){
                    $ar['tour_guide_name'] = $vl['source_id'];
                    $ar['guide_id_card'] = $list['data'][0]['guide_id_card'];
                    $ar['phone'] = $list['data'][0]['phone'];
                }
                if($supplier_type_id==10){
                    $ar['single_source_name'] = $vl['source_id'];
                }
                if($supplier_type_id==11){
                    $ar['own_expense_name'] = $vl['source_id'];
                }


                $ar['supplier_id'] = $list['data'][0]['supplier_type']==1?$list['data'][0]['supplier_id']:$list['data'][0]['belong_supplier_id'];
                $ar['agent_id'] = $list['data'][0]['supplier_type']==2?$list['data'][0]['supplier_id']:'';
                $ar['quantity'] = $vl['source_count'];
                $ar['unit_value_type'] = 1;
                $ar['currency'] = $vl['payment_currency_id'];
               // $ar['unit_price'] = $vl['source_price'];
                $ar['total'] = $vl['source_total_price'];

                $ar['cost_price'] = $list['data'][0]['normal_price'];
                $ar['unit_price'] = $list['data'][0]['normal_settlement_price'];
                $ar['symbol'] = $CurrencyGroup[$list['data'][0]['payment_currency_type']][0]['symbol'];
                $ar['the_day'] = $vl['source_the_days'];
                $ar['total'] = $vl['source_total_price'];

                $ar['team_product_allocation_id'] = $vl['team_product_allocation_id'];
                $Sar[] = $ar;
            }
        }
        if(!empty($Sar)){
            Session::set($session,$Sar);
        }


    }




    /**
     * 获取资源配置
     * @param int $ResourceTy 资源类型 1.产品编辑资源 2.模板资源 0.创建的资源
     * @param int $id
     */
    public function getResourceConfigure($ResourceTy=0,$id=0){

        if($ResourceTy==1 && $id!=0){//产品编辑资源
            $where['status'] = 1;
            $where['team_product_id'] = $id;
            $list = $this->callSoaErp('post','/product/getTeamProductAllocation',$where);
            if(!empty($list['data'])){
                $data = Arrays::group($list['data'],'supplier_type_id');
                foreach($data as $ky=>$vl){
                    if($ky==2){ //酒店
                        $this->resettingResourceSession('session_hotel',$vl,2,'/source/getHotel','hotel_id');
                    }
                    if($ky==3){ //用餐
                        $this->resettingResourceSession('session_dining',$vl,3,'/source/getDining','dining_id');
                    }
                    if($ky==4){ //航班
                        $this->resettingResourceSession('session_flight',$vl,4,'/source/getFlight','flight_id');
                    }
                    if($ky==5){ //邮轮
                        $this->resettingResourceSession('session_cruise',$vl,5,'/source/getCruise','cruise_id');
                    }
                    if($ky==6){ //签证
                        $this->resettingResourceSession('session_visa',$vl,6,'/source/getVisa','visa_id');
                    }
                    if($ky==7){ //景点
                        $this->resettingResourceSession('session_scenic_spot',$vl,7,'/source/getScenicSpot','scenic_spot_id');
                    }
                    if($ky==8){ //车辆
                        $this->resettingResourceSession('session_vehicle',$vl,8,'/source/getVehicle','vehicle_id');
                    }
                    if($ky==9){ //导游
                        $this->resettingResourceSession('session_tourGuide',$vl,9,'/source/getTourGuide','tour_guide_id');
                    }
                    if($ky==10){//单项资源
                        $this->resettingResourceSession('session_singleSource',$vl,10,'/source/getSingleSource','single_source_id');
                    }
                    if($ky==11){
                        $this->resettingResourceSession('session_Optional',$vl,11,'/source/getOwnExpense','own_expense_id');
                    }
                }
            }
            unset($where);
        }

        if($ResourceTy==2 && $id!=0){//获取线路模板资源
            $where['status'] = 1;
            $where['route_template_id'] = $id;
            $list = $this->callSoaErp('post','/product/getRouteSourceAllocation',$where);
            if(!empty($list['data'])){
//                var_dump($list['data']);exit;
                $data = Arrays::group($list['data'],'supplier_type_id');


                    if($list['data']['hotel']){ //酒店
                        $this->resettingResourceSession('session_hotel',$list['data']['hotel'],2,'/source/getHotel','hotel_id');
                    }
                    if($list['data']['dining']){ //用餐
                        $this->resettingResourceSession('session_dining',$list['data']['dining'],3,'/source/getDining','dining_id');
                    }
                    if($list['data']['flight']){ //航班
                        $this->resettingResourceSession('session_flight',$list['data']['flight'],4,'/source/getFlight','flight_id');
                    }
                    if($list['data']['cruise']){ //邮轮
                        $this->resettingResourceSession('session_cruise',$list['data']['cruise'],5,'/source/getCruise','cruise_id');
                    }
                    if($list['data']['visa']){ //签证
                        $this->resettingResourceSession('session_visa',$list['data']['visa'],6,'/source/getVisa','visa_id');
                    }
                    if($list['data']['scenic_spot']){ //景点
                        $this->resettingResourceSession('session_scenic_spot',$list['data']['scenic_spot'],7,'/source/getScenicSpot','scenic_spot_id');
                    }
                    if($list['data']['vehicle']){ //车辆
                        $this->resettingResourceSession('session_vehicle',$list['data']['vehicle'],8,'/source/getVehicle','vehicle_id');
                    }
                    if($list['data']['tour_guide']){ //导游
                        $this->resettingResourceSession('session_tourGuide',$list['data']['tour_guide'],9,'/source/getTourGuide','tour_guide_id');
                    }
                    if($list['data']['single_source']){//单项资源
                        $this->resettingResourceSession('session_singleSource',$list['data']['single_source'],10,'/source/getSingleSource','single_source_id');
                    }
                    if($list['data']['own_expense']){//自费项目
                        $this->resettingResourceSession('session_Optional',$list['data']['own_expense'],11,'/source/getOwnExpense','own_expense_id');
                    }

                }

            unset($where);

        }


        if($ResourceTy==3){//获取线路模板资源
            if(!empty($id)){
                $data = Arrays::group($id,'supplier_type_id');
                foreach($data as $ky=>$vl){
                    if($ky==2){ //酒店
                        $this->resettingResourceSession('session_hotel',$vl,2,'/source/getHotel','hotel_id');
                    }
                    if($ky==3){ //用餐
                        $this->resettingResourceSession('session_dining',$vl,3,'/source/getDining','dining_id');
                    }
                    if($ky==4){ //航班
                        $this->resettingResourceSession('session_flight',$vl,4,'/source/getFlight','flight_id');
                    }
                    if($ky==5){ //邮轮
                        $this->resettingResourceSession('session_cruise',$vl,5,'/source/getCruise','cruise_id');
                    }
                    if($ky==6){ //签证
                        $this->resettingResourceSession('session_visa',$vl,6,'/source/getVisa','visa_id');
                    }
                    if($ky==7){ //景点
                        $this->resettingResourceSession('session_scenic_spot',$vl,7,'/source/getScenicSpot','scenic_spot_id');
                    }
                    if($ky==8){ //车辆
                        $this->resettingResourceSession('session_vehicle',$vl,8,'/source/getVehicle','vehicle_id');
                    }
                    if($ky==9){ //导游
                        $this->resettingResourceSession('session_tourGuide',$vl,9,'/source/getTourGuide','tour_guide_id');
                    }
                    if($ky==10){//单项资源
                        $this->resettingResourceSession('session_singleSource',$vl,10,'/source/getSingleSource','single_source_id');
                    }
                    if($ky==11){
                        $this->resettingResourceSession('session_Optional',$vl,11,'/source/getOwnExpense','own_expense_id');
                    }
                }
            }
            unset($where);

        }


        //获取资源配置
        $session_hotel = Session::get('session_hotel'); //酒店
        $session_dining = Session::get('session_dining'); //用餐
        $session_flight = Session::get('session_flight'); //航班
        $session_cruise = Session::get('session_cruise');//邮轮
        $session_visa = Session::get('session_visa');//签证
        $session_scenic_spot = Session::get('session_scenic_spot'); //景点
        $session_vehicle = Session::get('session_vehicle'); //车辆
        $session_tourGuide = Session::get('session_tourGuide'); //导游
        $session_singleSource = Session::get('session_singleSource'); //单项资源
        $session_Optional = Session::get('session_Optional');
//        var_dump($session_singleSource);exit;

        $this->assign('session_hotel',$session_hotel);
        $this->assign('session_dining',$session_dining);
        $this->assign('session_flight',$session_flight);
        $this->assign('session_cruise',$session_cruise);
        $this->assign('session_visa',$session_visa);
        $this->assign('session_scenic_spot',$session_scenic_spot);
        $this->assign('session_vehicle',$session_vehicle);
        $this->assign('session_tourGuide',$session_tourGuide);
        $this->assign('session_singleSource',$session_singleSource);
        $this->assign('session_Optional',$session_Optional);
    }

    /**
     * 获取联动数据
     * Hugh
     */
    public function linkage($data,$url){
        foreach($data as $k=>$v){
            $where['status']=1;
            //获取代理Data
            $where['belong_supplier_id'] = $v['supplier_id'];
            $agentList = $this->callSoaErp('post',$url,$where);
            if(!empty($agentList['data'])){
                $data[$k]['agent_data'] = $this->getLocalTravelAgency($agentList['data']);
            }
            unset($where['belong_supplier_id']);
            //获取资源数据
            if($data[$k]['agent_id']){
                $where['supplier_id'] = $data[$k]['agent_id'];
            }else{
                $where['supplier_id'] = $data[$k]['supplier_id'];
            }
            $resourceList = $this->callSoaErp('post',$url,$where);
            if(!empty($resourceList['data'])){
                $data[$k]['resource_data'] = $resourceList['data'];
            }
            unset($where);
        }
        return $data;
    }


    /**
     * 获取供应商(启用的)
     * Hugh
     */
    public function getSupplier($supplier_type_id=0){
        $where['status']=1;
        $where['company_id'] = session('user')['company_id'];
        if($supplier_type_id){
            $where['supplier_type_id']=$supplier_type_id;
        }
        $SupplierList = $this->callSoaErp('post','/source/getSupplier',$where);
        if($SupplierList['data']){
            $this->assign('SupplierList',$SupplierList['data']);
        }
    }

    /**
     * 配置酒店资源编辑
     * Hugh 18-09-06
     */
    public function showPlanHotelUpdate(){
        $session_hotel = Session::get('session_hotel'); //酒店
        //获取供应商
        $this->getSupplier(2);
        $session_hotel =  $this->linkage($session_hotel,'/source/getHotel');
        $this->assign('session_hotel',$session_hotel);
//        var_dump($session_hotel);exit;
        $this->getCurrency2();
        $this->assign('room_type',$this->room_type);

//        return $this->fetch('plan_hotel_update');
        return $this->fetch('n_plan_hotel_update');
    }


    public function showPlanHotelInfo(){
        $session_hotel = Session::get('session_hotel'); //酒店
        //获取供应商
        $this->getSupplier(2);
        $session_hotel =  $this->linkage($session_hotel,'/source/getHotel');
        $this->assign('session_hotel',$session_hotel);
//        var_dump($session_hotel);exit;
        $this->getCurrency2();
        $this->assign('room_type',$this->room_type);

//        return $this->fetch('plan_hotel_update');
        return $this->fetch('n_plan_hotel_info');
    }

    /**
     * 配置用餐资源编辑
     * Hugh 18-09-06
     */
    public function showPlanDiningUpdate(){
        $session_dining = Session::get('session_dining');
        $this->getSupplier(3);//获取供应商
        $session_dining =  $this->linkage($session_dining,'/source/getDining');
        $this->assign('session_dining',$session_dining);
        $this->getCurrency2();
//        return $this->fetch('plan_dining_update');
        return $this->fetch('n_plan_dining_update');
    }

    public function showPlanDiningInfo(){
        $session_dining = Session::get('session_dining');
        $this->getSupplier(3);//获取供应商
        $session_dining =  $this->linkage($session_dining,'/source/getDining');
        $this->assign('session_dining',$session_dining);
        $this->getCurrency2();
//        return $this->fetch('plan_dining_update');
        return $this->fetch('n_plan_dining_info');
    }

    /**
     * 配置航班资源编辑
     * Hugh 18-09-06
     */
    public function showPlanFlightUpdate(){
        $session_flight = Session::get('session_flight');
//        var_dump($session_flight);exit;
        $this->getSupplier(4);//获取供应商
        $session_flight =  $this->linkage($session_flight,'/source/getFlight');
        $this->assign('session_flight',$session_flight);
        $this->getCurrency2();
//        return $this->fetch('plan_flight_update');
        return $this->fetch('n_plan_flight_update');
    }

    public function showPlanFlightInfo(){
        $session_flight = Session::get('session_flight');
//        var_dump($session_flight);exit;
        $this->getSupplier(4);//获取供应商
        $session_flight =  $this->linkage($session_flight,'/source/getFlight');
        $this->assign('session_flight',$session_flight);
        $this->getCurrency2();
//        return $this->fetch('plan_flight_update');
        return $this->fetch('n_plan_flight_info');
    }


    /**
     * 配置邮轮资源编辑
     * Hugh 18-09-07
     */
    public function showPlanCruiseUpdate(){
        $session_cruise = Session::get('session_cruise');
        $this->getSupplier(5);//获取供应商
        $session_cruise =  $this->linkage($session_cruise,'/source/getCruise');
        $this->assign('session_cruise',$session_cruise);
        $this->getCurrency2();
//        return $this->fetch('plan_cruise_update');
        return $this->fetch('n_plan_cruise_update');
    }

    public function showPlanCruiseInfo(){
        $session_cruise = Session::get('session_cruise');
        $this->getSupplier(5);//获取供应商
        $session_cruise =  $this->linkage($session_cruise,'/source/getCruise');
        $this->assign('session_cruise',$session_cruise);
        $this->getCurrency2();
//        return $this->fetch('plan_cruise_update');
        return $this->fetch('n_plan_cruise_info');
    }

    /**
     * 配置签证资源编辑
     * Hugh 18-09-07
     */
    public function showPlanVisaUpdate(){
        $session_visa = Session::get('session_visa');
        $this->getSupplier(6);//获取供应商
        $session_visa =  $this->linkage($session_visa,'/source/getVisa');
        $this->assign('session_visa',$session_visa);
        $this->getCurrency2();
//        return $this->fetch('plan_visa_update');
        return $this->fetch('n_plan_visa_update');
    }

    public function showPlanVisaInfo(){
        $session_visa = Session::get('session_visa');
        $this->getSupplier(6);//获取供应商
        $session_visa =  $this->linkage($session_visa,'/source/getVisa');
        $this->assign('session_visa',$session_visa);
        $this->getCurrency2();
//        return $this->fetch('plan_visa_update');
        return $this->fetch('n_plan_visa_info');
    }

    /**
     * 配置景点资源编辑
     * Hugh 18-09-07
     */
    public function showPlanScenicSpotUpdate(){
        $session_scenic_spot = Session::get('session_scenic_spot');
        $this->getSupplier(7);//获取供应商
        $session_scenic_spot =  $this->linkage($session_scenic_spot,'/source/getScenicSpot');
        $this->assign('session_scenic_spot',$session_scenic_spot);
        $this->getCurrency2();
//        return $this->fetch('plan_scenic_spot_update');
        return $this->fetch('n_plan_scenic_spot_update');
    }


    public function showPlanScenicSpotInfo(){
        $session_scenic_spot = Session::get('session_scenic_spot');
        $this->getSupplier(7);//获取供应商
        $session_scenic_spot =  $this->linkage($session_scenic_spot,'/source/getScenicSpot');
        $this->assign('session_scenic_spot',$session_scenic_spot);
        $this->getCurrency2();
//        return $this->fetch('plan_scenic_spot_update');
        return $this->fetch('n_plan_scenic_spot_info');
    }


    /***
     * 配置车辆资源编辑
     * Hugh 18-09-07
     */
    public function showPlanVehicleUpdate(){
        $session_vehicle = Session::get('session_vehicle');
        $this->getSupplier(8);//获取供应商
        $session_vehicle =  $this->linkage($session_vehicle,'/source/getVehicle');
        $this->assign('session_vehicle',$session_vehicle);
        $this->getCurrency2();
//        return $this->fetch('plan_vehicle_update');
        return $this->fetch('n_plan_vehicle_update');
    }


    public function showPlanVehicleInfo(){
        $session_vehicle = Session::get('session_vehicle');
        $this->getSupplier(8);//获取供应商
        $session_vehicle =  $this->linkage($session_vehicle,'/source/getVehicle');
        $this->assign('session_vehicle',$session_vehicle);
        $this->getCurrency2();
//        return $this->fetch('plan_vehicle_update');
        return $this->fetch('n_plan_vehicle_info');
    }


    /**
     * 配置导游资源编辑
     * Hugh 18-09-07
     */
    public function showPlanTourGuideUpdate(){
        $session_tourGuide = Session::get('session_tourGuide');
        $this->getSupplier(9);//获取供应商
        $session_tourGuide =  $this->linkage($session_tourGuide,'/source/getTourGuide');
        $this->assign('session_tourGuide',$session_tourGuide);
//        var_dump($session_tourGuide[0]);exit;
        $this->getCurrency2();
//        return $this->fetch('plan_tour_guide_update');
        return $this->fetch('n_plan_tour_guide_update');
    }

    public function showPlanTourGuideInfo(){
        $session_tourGuide = Session::get('session_tourGuide');
        $this->getSupplier(9);//获取供应商
        $session_tourGuide =  $this->linkage($session_tourGuide,'/source/getTourGuide');
        $this->assign('session_tourGuide',$session_tourGuide);
//        var_dump($session_tourGuide[0]);exit;
        $this->getCurrency2();
//        return $this->fetch('plan_tour_guide_update');
        return $this->fetch('n_plan_tour_guide_info');
    }

    /**
     * 配置单项资源编辑
     * Hugh 18-09-07
     */
    public function showPlanSingleSourceUpdate(){
        $session_singleSource = Session::get('session_singleSource');
        $this->getSupplier(10);//获取供应商
        $session_singleSource =  $this->linkage($session_singleSource,'/source/getSingleSource');
        $this->assign('session_singleSource',$session_singleSource);
//        var_dump($session_singleSource[0]);exit;
        $this->getCurrency2();
//        return $this->fetch('plan_single_source_update');
        return $this->fetch('n_plan_single_source_update');
    }

    public function showPlanSingleSourceInfo(){
        $session_singleSource = Session::get('session_singleSource');
        $this->getSupplier(10);//获取供应商
        $session_singleSource =  $this->linkage($session_singleSource,'/source/getSingleSource');
        $this->assign('session_singleSource',$session_singleSource);
//        var_dump($session_singleSource[0]);exit;
        $this->getCurrency2();
//        return $this->fetch('plan_single_source_update');
        return $this->fetch('n_plan_single_source_info');
    }

    /**
     * 删除资源配置
     * Hugh 18-09-07
     */
    public function SourceDel(){
        $type = input('post.type');
        switch($type){
            case 2:Session::delete('session_hotel');break; //酒店
            case 3:Session::delete('session_dining');break; //用餐
            case 4:Session::delete('session_flight');break; //航班
            case 5:Session::delete('session_cruise');break;//邮轮
            case 6:Session::delete('session_visa');break;//签证
            case 7:Session::delete('session_scenic_spot');break; //景点
            case 8:Session::delete('session_vehicle');break; //车辆
            case 9:Session::delete('session_tourGuide');break; //导游
            case 10:Session::delete('session_singleSource');break; //单项资源
            case 11:Session::delete('session_Optional');break; //自费项目
            default:break;
        }
        echo $type;
    }

    /**
     * 团队产品导游计划
     */
    public function showshowPlanTourGuideReceipt(){
        return $this->fetch('plan_tour_guide_receipt');
    }

    /**
     * 获取团队产品导游计划 AJAX请求
     */
    public function getGuideReceiptInfoAjax(){
        $team_product_number = input("team_product_number");

        $data=[
            "team_product_number"=>$team_product_number
            ];

        $guide_receipt_result = $this->callSoaErp('post', '/product/getTeamProductGuideReceipt', $data);

        return $guide_receipt_result;
    }
	/**
	 * 删除团队产品的导游回执单
	 */
    public function updateTeamProductGuideReceipt(){
    	$team_product_number = input("team_product_number");
    	
    	$data=[
    		"team_product_number"=>$team_product_number
    	];

    	$guide_receipt_result = $this->callSoaErp('post', '/product/updateTeamProductGuideReceipt', $data);
    	
    	return $guide_receipt_result;
    }
    



    /**
     * 团队产品修改保存
     */
    public function PlanTourUpdateAjax(){
        $post = Request::instance()->param();
        $d = [];
//        var_dump($post);exit;
        //基本信息
        $team_product_id = Arrays::get($post,'team_product_id');
        $settlement_type = Arrays::get($post,'settlement_type');
        $d['team_product_id'] = $team_product_id;
        $begin_time = Arrays::get($post,'begin_time');
        $d['begin_time'] = strtotime($begin_time);
        $d['team_product_name'] = Arrays::get($post,'team_product_name');
        $d['route_type_id'] = Arrays::get($post,'route_type_id');
        $d['use_company_id'] = Arrays::get($post,'use_company_id','*');
        $d['settlement_type'] = $settlement_type;
        $d['team_product_user_id'] = Arrays::get($post,'team_product_user_id');
        $d['before_days'] = Arrays::get($post,'before_days');
        $d['plan_custom_number'] = Arrays::get($post,'plan_custom_number');
        $d['status'] = Arrays::get($post,'status');
        $d['user_id'] = session('user')['user_id'];
        $d['company_id'] = session('user')['company_id'] ;

        //团队产品(行程)
        $d['add_journey'] = [];
        $d['edit_journey'] = [];
        $team_product_journey_id = Arrays::get($post,'team_product_journey_id',[]);
        $journey_the_days = Arrays::get($post,'journey_the_days');

        $journey_title = Arrays::get($post,'journey_title');
        $journey_content = Arrays::get($post,'journey_content');
        $journey_traffic = Arrays::get($post,'journey_traffic');
        $journey_stay = Arrays::get($post,'journey_stay');
        $eat_mark = Arrays::get($post,'eat_mark');
        $journey_breakfast = Arrays::get($post,'journey_breakfast');
        $journey_lunch = Arrays::get($post,'journey_lunch');
        $journey_dinner = Arrays::get($post,'journey_dinner');
        $journey_scenic_sport = Arrays::get($post,'journey_scenic_sport');
        $journey_remark = Arrays::get($post,'journey_remark');
        $journey_picture = Arrays::get($post,'journey_picture');
        $country_id = Arrays::get($post,'country_id');
        foreach($journey_the_days as $ky=>$vl){
            $ar['the_days'] = $journey_the_days[$ky];
            $ar['route_journey_title'] = $journey_title[$ky];
            $ar['route_journey_content'] = $journey_content[$ky];
            $ar['route_journey_traffic'] = $journey_traffic[$ky];
            $ar['route_journey_stay'] = $journey_stay[$ky];
            $ar['eat_mark'] = empty($eat_mark[$ky])?'':implode(',',$eat_mark[$ky]);
            $ar['route_journey_breakfast'] = $journey_breakfast[$ky];
            $ar['route_journey_lunch'] = $journey_lunch[$ky];
            $ar['route_journey_dinner'] = $journey_dinner[$ky];
            $ar['route_journey_scenic_sport'] = $journey_scenic_sport[$ky];
            $ar['route_journey_picture'] = empty($journey_picture[$ky])?'':implode(',',$journey_picture[$ky]);
            $ar['route_journey_remark'] = $journey_remark[$ky];
            $ar['route_journey_zone'] = $country_id[$ky];
            $ar['status'] = 1;
            if(empty($team_product_journey_id[$ky])){
                $ar['team_product_id'] = $team_product_id;
                $d['add_journey'][] = $ar;
            }else{
                $ar['team_product_journey_id'] = $team_product_journey_id[$ky];
                $d['edit_journey'][] = $ar;
            }
            unset($ar);
        }

        //航班
        $d['add_flight'] = [];
        $d['edit_flight'] = [];
        $flight_number = Arrays::get($post,'flight_number');
        $flight_type = Arrays::get($post,'flight_type');
        $fight_start_city = Arrays::get($post,'fight_start_city');
        $fight_start_time = Arrays::get($post,'fight_start_time');
        $fight_end_city = Arrays::get($post,'fight_end_city');
        $fight_end_time = Arrays::get($post,'fight_end_time');
        $fight_the_days = Arrays::get($post,'fight_the_days');
        $team_product_flight_id = Arrays::get($post,'team_product_flight_id');
        foreach($flight_number as $ky=>$vl){
            $ar['the_days'] = $fight_the_days[$ky];
            $ar['start_city'] = $fight_start_city[$ky];
            $ar['end_city'] = $fight_end_city[$ky];
//            $ar['start_time'] = strtotime($fight_start_time[$ky]);
//            $ar['end_time'] = strtotime($fight_end_time[$ky]);


            $ar['start_time'] = strtotime(date('Y-m-d').' '.$fight_start_time[$ky]);
            $ar['end_time'] = strtotime(date('Y-m-d').' '.$fight_end_time[$ky]);
            $ar['flight_number'] = $flight_number[$ky];
            $ar['flight_type'] = $flight_type[$ky];
            $ar['status'] = 1;
            if(empty($team_product_flight_id[$ky])){
                $ar['team_product_id'] = $team_product_id;
                $d['add_flight'][] = $ar;

            }else{
                $ar['team_product_flight_id'] = $team_product_flight_id[$ky];
                $d['edit_flight'][] = $ar;
            }
            unset($ar);
        }

        //回执单
        $d['edit_return_receipt'] = [];
        $d['add_return_receipt'] = [];
        $ReturnReceiptTitle = Arrays::get($post,'ReturnReceiptTitle');
        $ReturnReceiptSorting = Arrays::get($post,'ReturnReceiptSorting');
        $ReturnReceiptContent = Arrays::get($post,'ReturnReceiptContent');
        $route_return_receipt_id = Arrays::get($post,'route_return_receipt_id');
        foreach($ReturnReceiptTitle as $ky=>$vl){
            $ar['title'] = $ReturnReceiptTitle[$ky];
            $ar['content'] = $ReturnReceiptContent[$ky];
            $ar['sorting'] = $ReturnReceiptSorting[$ky];
            $ar['status'] = 1;
            if(empty($route_return_receipt_id[$ky])){
                $ar['team_product_id'] = $team_product_id;
                $d['add_return_receipt'][] = $ar;
            }else{
                $ar['route_return_receipt_id'] = $route_return_receipt_id[$ky];
                $d['edit_return_receipt'][] = $ar;
            }
            unset($ar);
        }

        //获取资源配置
        $session_hotel = Session::get('session_hotel'); //酒店
        $session_dining = Session::get('session_dining'); //用餐
        $session_flight = Session::get('session_flight'); //航班
        $session_cruise = Session::get('session_cruise');//邮轮
        $session_visa = Session::get('session_visa');//签证
        $session_scenic_spot = Session::get('session_scenic_spot'); //景点
        $session_vehicle = Session::get('session_vehicle'); //车辆
        $session_tourGuide = Session::get('session_tourGuide'); //导游
        $session_singleSource = Session::get('session_singleSource'); //单项资源
        $session_Optional = Session::get('session_Optional');

        $tourPrice = Arrays::get($post,'tourPrice');
        $tourCurrency = Arrays::get($post,'tourCurrency');
        $d['add_allocation'] = [];
        $d['edit_allocation'] = [];
        //酒店
        $d = $this->setTeamProductAllocationInfoUpdate($d,2,$session_hotel,'room_name',$tourPrice,$tourCurrency,$team_product_id);
        //用餐
        $d = $this->setTeamProductAllocationInfoUpdate($d,3,$session_dining,'dining_name',$tourPrice,$tourCurrency,$team_product_id);
        //航班
        $d = $this->setTeamProductAllocationInfoUpdate($d,4,$session_flight,'flight_number',$tourPrice,$tourCurrency,$team_product_id);
        //邮轮
        $d = $this->setTeamProductAllocationInfoUpdate($d,5,$session_cruise,'cruise_name',$tourPrice,$tourCurrency,$team_product_id);
        //签证
        $d = $this->setTeamProductAllocationInfoUpdate($d,6,$session_visa,'visa_name',$tourPrice,$tourCurrency,$team_product_id);
        //景点
        $d = $this->setTeamProductAllocationInfoUpdate($d,7,$session_scenic_spot,'scenic_spot_name',$tourPrice,$tourCurrency,$team_product_id);
        //车辆
        $d = $this->setTeamProductAllocationInfoUpdate($d,8,$session_vehicle,'vehicle_name',$tourPrice,$tourCurrency,$team_product_id);
        //导游
        $d = $this->setTeamProductAllocationInfoUpdate($d,9,$session_tourGuide,'tour_guide_name',$tourPrice,$tourCurrency,$team_product_id);
        //单项资源
        $d = $this->setTeamProductAllocationInfoUpdate($d,10,$session_singleSource,'single_source_name',$tourPrice,$tourCurrency,$team_product_id);
        //自费项目
        $d = $this->setTeamProductAllocationInfoUpdate($d,11,$session_Optional,'own_expense_name',$tourPrice,$tourCurrency,$team_product_id);
//var_dump($session_Optional);


        //一口价
        $d['add_once_price'] = [];
        $d['edit_once_price'] = [];
        $OneBitePrice = Arrays::get($post,'OneBitePrice');
        $team_product_once_price_id = Arrays::get($post,'team_product_once_price_id');
        $OneBitePriceCurrency = Arrays::get($post,'OneBitePriceCurrency');
        $d['team_product_once_price'] = [];
        if($settlement_type==1){
            foreach($OneBitePrice as $ky=>$OneBitePriceV){
                $ar['company_id'] = $ky;
                $ar['total_price'] = $OneBitePriceV;
                $ar['team_price_currency_id'] = $OneBitePriceCurrency[$ky];
                $ar['status'] = 1;
                if(empty($team_product_once_price_id[$ky])){
                    $ar['team_product_id'] = $team_product_id;
                    $d['add_once_price'][] = $ar;
                }else{
                    $ar['team_product_once_price_id'][] = $team_product_once_price_id[$ky];
                    $d['edit_once_price'][] = $ar;
                }
                unset($ar);
            }
        }


         //审批
        $dd['company_id'] = session('user')['company_id'];
        $dd['approval_type_id'] = 13;
        $dd['pk_id'] =  Arrays::get($post,'team_product_id');
        $dd['examination_and_approval_content'] = json_encode($d);
        $dd['remarks'] = Arrays::get($post,'up_remarks');
        $dd['user_id'] = session('user')['user_id'];
        $dd['status'] = 1;
        $this->callSoaErp('post','/examine_and_approve/addExamineAndApprove/',$dd);

        $d['status'] = 3;
        return  $this->callSoaErp('post','/product/updateTeamProductBaseByTeamProductBaseId',$d);
//updateTeamProductBaseByTeamProductBaseId
//        return  $this->callSoaErp('post','/product/updateTeamProductByTeamProductId',$d);

        exit;
    }

    /**
    * 团队审核产品详情
     */
    public function showPlanTourAuditingInfo(){
        Session::delete('session_hotel'); //酒店
        Session::delete('session_dining'); //用餐
        Session::delete('session_flight'); //航班
        Session::delete('session_cruise');//邮轮
        Session::delete('session_visa');//签证
        Session::delete('session_scenic_spot'); //景点
        Session::delete('session_vehicle'); //车辆
        Session::delete('session_tourGuide'); //导游
        Session::delete('session_singleSource'); //单项资源
        Session::delete('session_Optional'); //自费项目

        if(input('get.examine_and_approve_id')){
            $where['examine_and_approve_id'] = input('get.examine_and_approve_id');
        }else{
            $where['pk_id'] = input('get.plan_id');
            $where['approval_type_id'] = ['in','12,13'];
            $where['status'] = 1;
        }

        $selExamineAndApprove = $this->callSoaErp('post','/examine_and_approve/selExamineAndApprove/',$where);
//        echo '<pre>';print_r(json_decode($selExamineAndApprove['data'][0]['examination_and_approval_content'],true));exit;
        $examination_and_approval_content = json_decode($selExamineAndApprove['data'][0]['examination_and_approval_content'],true);
        if($selExamineAndApprove['data'][0]['approval_type_id']==13){
            //基本信息
            $this->assign('TeamProductBase',$examination_and_approval_content);
            //行程
            $TeamProductJourney = Arrays::sort(array_merge($examination_and_approval_content['add_journey'],$examination_and_approval_content['edit_journey']),'the_days','asc');
            $this->assign('TeamProductJourney',$TeamProductJourney); //行程
            //航班
            $TeamProductFlight = Arrays::sort(array_merge($examination_and_approval_content['add_flight'],$examination_and_approval_content['edit_flight']),'the_days','asc');
            $this->assign('TeamProductFlight',$TeamProductFlight); //航班
            //回执单
            $TeamProductReturnReceipt = Arrays::sort(array_merge($examination_and_approval_content['add_return_receipt'],$examination_and_approval_content['edit_return_receipt']),'sorting','asc');
            $this->assign('TeamProductReturnReceipt',$TeamProductReturnReceipt);
            //资源
            $team_product_allocation_info = array_merge($examination_and_approval_content['add_allocation'],$examination_and_approval_content['edit_allocation']);
            $this->getResourceConfigure(3,$team_product_allocation_info);//获取资源配置

        }else{
            $this->assign('TeamProductBase',$examination_and_approval_content); //基本信息
            $this->assign('TeamProductJourney',$examination_and_approval_content['team_product_journey_info'] ); //行程
            $this->assign('TeamProductFlight',$examination_and_approval_content['team_product_flight_info']); //航班

//        回执单
            $TeamProductReturnReceipt = Arrays::sort($examination_and_approval_content['team_product_return_receipt'],'sorting','asc');
            $this->assign('TeamProductReturnReceipt',$TeamProductReturnReceipt);

            $this->getResourceConfigure(3,$examination_and_approval_content['team_product_allocation_info']);//获取资源配置
        }


        $data['status'] = 1;
        //线路模板
        $RouteTemplateList = $this->callSoaErp('post','/product/getRouteTemplateName',$data);
//        var_dump($RouteTemplateList);exit;
        if($RouteTemplateList['data']){
            $this->assign('RouteTemplateList',$RouteTemplateList['data']);
        }
        //线路类型
        $data['company_id'] = session('user')['company_id'];
        $RouteTypeList = $this->callSoaErp('post','/system/getRouteType',$data);
        if($RouteTypeList['data']){
//            $RouteType = Arrays::group($RouteTypeList['data'],'type');
            $this->assign('RouteType',$RouteTypeList['data']);
        }
        unset($data['company_id']);
        //分公司
        $CompanyList = $this->callSoaErp('post','/system/getCompany',$data);
        if($CompanyList['data']){
            $this->assign('CompanyList',$CompanyList['data']);
        }
        //线路负责人
        $company_id = session('user')['company_id'];
        $data['department_id'] = session('user')['department_id'];
        $UserList = $this->callSoaErp('post','/user/getUser',$data);
        if($UserList['data']){
            $this->assign('UserList',$UserList['data']);
        }
        unset($data['department_id']);
        //操作用户
        $this->assign('UserId',session('user')['user_id']);
        //回执单模板
        $data['company_id'] = session('user')['company_id'];
        $ReturnReceiptList = $this->callSoaErp('post','/system/getReturnReceipt',$data);
        if($ReturnReceiptList['data']){
            $this->assign('ReturnReceiptList',$ReturnReceiptList['data']);
        }

        $CountryList = $this->callSoaErp('post','/system/getCountry',['level'=>3,'status'=>1]);
        $this->assign('CountryList',$CountryList['data']);

        return $this->fetch('plan_tour_info');
    }


    /**
     * 团队产品详情
     */
    public function showPlanTourInfo(){
        Session::delete('session_hotel'); //酒店
        Session::delete('session_dining'); //用餐
        Session::delete('session_flight'); //航班
        Session::delete('session_cruise');//邮轮
        Session::delete('session_visa');//签证
        Session::delete('session_scenic_spot'); //景点
        Session::delete('session_vehicle'); //车辆
        Session::delete('session_tourGuide'); //导游
        Session::delete('session_singleSource'); //单项资源
        Session::delete('session_Optional'); //自费项目

        $team_product_id = input('get.plan_id');

        //获取团队产品基本信息
        $where['team_product_id'] = $team_product_id;
        $TeamProductBase = $this->callSoaErp('post','/product/getTeamProductBase',$where);
        if($TeamProductBase['data']){
            $this->assign('TeamProductBase',$TeamProductBase['data'][0]);
        }
//      var_dump($TeamProductBase['data'][0]);exit;
        //获取行程内容
        $where['status'] = 1;
        $TeamProductJourney = $this->callSoaErp('post','/product/getTeamProductJourney',$where);
        if($TeamProductJourney['data']){
            $this->assign('TeamProductJourney',$TeamProductJourney['data'] );
        }
//        var_dump($TeamProductJourney['data'] );exit;
        //获取行程
        $TeamProductFlight = $this->callSoaErp('post','/product/getTeamProductFlight',$where);
        if($TeamProductFlight['data']){
            $this->assign('TeamProductFlight',$TeamProductFlight['data'] );
        }

        //回执单
        $TeamProductReturnReceipt = $this->callSoaErp('post','/product/getTeamProductReturnReceipt',$where);
        if($TeamProductReturnReceipt['data']){
            $TeamProductReturnReceipt = Arrays::sort($TeamProductReturnReceipt['data'],'sorting','asc');
            $this->assign('TeamProductReturnReceipt',$TeamProductReturnReceipt);
        }


        $this->getResourceConfigure(1,$team_product_id);//获取资源配置

        $data['status'] = 1;
        //线路模板
        $RouteTemplateList = $this->callSoaErp('post','/product/getRouteTemplateName',$data);
//        var_dump($RouteTemplateList);exit;
        if($RouteTemplateList['data']){
            $this->assign('RouteTemplateList',$RouteTemplateList['data']);
        }
        //线路类型
        $data['company_id'] = session('user')['company_id'];
        $RouteTypeList = $this->callSoaErp('post','/system/getRouteType',$data);
        if($RouteTypeList['data']){
            $RouteType = Arrays::group($RouteTypeList['data'],'type');
            $this->assign('RouteType',$RouteType);
        }
        unset($data['company_id']);
        //分公司
        $CompanyList = $this->callSoaErp('post','/system/getCompany',$data);
        if($CompanyList['data']){
            $this->assign('CompanyList',$CompanyList['data']);
        }
        //线路负责人
        $company_id = session('user')['company_id'];
        $data['department_id'] = session('user')['department_id'];
        $UserList = $this->callSoaErp('post','/user/getUser',$data);
        if($UserList['data']){
            $this->assign('UserList',$UserList['data']);
        }
        unset($data['department_id']);
        //操作用户
        $this->assign('UserId',session('user')['user_id']);
        //回执单模板
        $data['company_id'] = session('user')['company_id'];
        $ReturnReceiptList = $this->callSoaErp('post','/system/getReturnReceipt',$data);
        if($ReturnReceiptList['data']){
            $this->assign('ReturnReceiptList',$ReturnReceiptList['data']);
        }

        $CountryList = $this->callSoaErp('post','/system/getCountry',['level'=>3,'status'=>1]);
        $this->assign('CountryList',$CountryList['data']);


        return $this->fetch('plan_tour_info');
    }

    /**
     * 团队产品修改
     * Hugh 18-09-10
     */
    public function showPlanTourUpdate(){
        Session::delete('session_hotel'); //酒店
        Session::delete('session_dining'); //用餐
        Session::delete('session_flight'); //航班
        Session::delete('session_cruise');//邮轮
        Session::delete('session_visa');//签证
        Session::delete('session_scenic_spot'); //景点
        Session::delete('session_vehicle'); //车辆
        Session::delete('session_tourGuide'); //导游
        Session::delete('session_singleSource'); //单项资源
        Session::delete('session_Optional'); //自费项目

        $team_product_id = input('get.plan_id');

        //获取团队产品基本信息
        $where['team_product_id'] = $team_product_id;
        $TeamProductBase = $this->callSoaErp('post','/product/getTeamProductBase',$where);
        if($TeamProductBase['data']){
            $this->assign('TeamProductBase',$TeamProductBase['data'][0]);
        }
//      var_dump($TeamProductBase['data'][0]);exit;
        //获取行程内容
        $where['status'] = 1;
        $TeamProductJourney = $this->callSoaErp('post','/product/getTeamProductJourney',$where);
        if($TeamProductJourney['data']){
            $this->assign('TeamProductJourney',$TeamProductJourney['data'] );
        }
//        var_dump($TeamProductJourney['data'] );exit;
        //获取行程
        $TeamProductFlight = $this->callSoaErp('post','/product/getTeamProductFlight',$where);
        if($TeamProductFlight['data']){
            $this->assign('TeamProductFlight',$TeamProductFlight['data'] );
        }

        //回执单
        $TeamProductReturnReceipt = $this->callSoaErp('post','/product/getTeamProductReturnReceipt',$where);
        if($TeamProductReturnReceipt['data']){
            $TeamProductReturnReceipt = Arrays::sort($TeamProductReturnReceipt['data'],'sorting','asc');
            $this->assign('TeamProductReturnReceipt',$TeamProductReturnReceipt);
        }


        $this->getResourceConfigure(1,$team_product_id);//获取资源配置

        $data['status'] = 1;
        //线路模板
        $RouteTemplateList = $this->callSoaErp('post','/product/getRouteTemplateName',$data);
//        var_dump($RouteTemplateList);exit;
        if($RouteTemplateList['data']){
            $this->assign('RouteTemplateList',$RouteTemplateList['data']);
        }
        //线路类型
        $data['company_id'] = session('user')['company_id'];
        $RouteTypeList = $this->callSoaErp('post','/system/getRouteType',$data);
        if($RouteTypeList['data']){
            $RouteType = Arrays::group($RouteTypeList['data'],'type');
            $this->assign('RouteType',$RouteType);
        }
        unset($data['company_id']);
        //分公司
        $CompanyList = $this->callSoaErp('post','/system/getCompany',$data);
        if($CompanyList['data']){
            $this->assign('CompanyList',$CompanyList['data']);
        }
        //线路负责人
        $company_id = session('user')['company_id'];
        $data['department_id'] = session('user')['department_id'];
        $UserList = $this->callSoaErp('post','/user/getUser',$data);
        if($UserList['data']){
            $this->assign('UserList',$UserList['data']);
        }
        unset($data['department_id']);
        //操作用户
        $this->assign('UserId',session('user')['user_id']);
        //回执单模板
        $data['company_id'] = session('user')['company_id'];
        $ReturnReceiptList = $this->callSoaErp('post','/system/getReturnReceipt',$data);
        if($ReturnReceiptList['data']){
            $this->assign('ReturnReceiptList',$ReturnReceiptList['data']);
        }

        $CountryList = $this->callSoaErp('post','/system/getCountry',['level'=>3,'status'=>1]);
        $this->assign('CountryList',$CountryList['data']);


        return $this->fetch('plan_tour_update');
    }




    /**
     * 创建团队产品
     * Hugh 18-08-30
     */
    public function showPlanTourAdd(){
        //资源配置
        Session::delete('session_hotel'); //酒店
        Session::delete('session_dining'); //用餐
        Session::delete('session_flight'); //航班
        Session::delete('session_cruise');//邮轮
        Session::delete('session_visa');//签证
        Session::delete('session_scenic_spot'); //景点
        Session::delete('session_vehicle'); //车辆
        Session::delete('session_tourGuide'); //导游
        Session::delete('session_singleSource'); //单项资源
        Session::delete('session_Optional'); //自费项目

        $route_template_id = input('get.route_template_id');
        if($route_template_id){
            $where['route_template_id'] = $route_template_id;
            $RouteTemplate = $this->callSoaErp('post','/product/getRouteTemplate',$where);
            unset($where);
            if($RouteTemplate['data']){
                $this->assign('RouteTemplate',$RouteTemplate['data'][0]);
                if($RouteTemplate['data'][0]['days']>0){
                    //获取线路行程
                    $where['route_template_id'] = $route_template_id;
                    $where['status'] = 1;
                    $RouteJourney = $this->callSoaErp('post','/product/getRouteJourney',$where);
                    if($RouteJourney['data']){
                        $this->assign('RouteJourney',$RouteJourney['data']);
                    }
                    unset($where);
                    //获取航班
                    $where['route_template_id'] = $route_template_id;
                    $where['status'] = 1;
                    $RouteFlight = $this->callSoaErp('post','/product/getRouteFlight',$where);
                    if($RouteFlight['data']){
                        $this->assign('RouteFlight',$RouteFlight['data']);
                    }
                    unset($where);
                }

                //回执单
                $where['route_template_id'] = $route_template_id;
                $where['status'] = 1;
                $RouteReturnReceipt = $this->callSoaErp('post','/product/getRouteReturnReceipt',$where);
                if($RouteReturnReceipt['data']){
                    $RouteReturnReceipt = Arrays::sort($RouteReturnReceipt['data'],'sorting','asc');
                    $this->assign('RouteReturnReceipt',$RouteReturnReceipt);
                }

                $this->getResourceConfigure(2,$route_template_id);//获取资源配置

            }

        }else{
            $this->getResourceConfigure();//获取资源配置
        }

        $data['status'] = 1;
        $data['company_id'] =  session('user')['company_id'];
        //线路模板
        $RouteTemplateList = $this->callSoaErp('post','/product/getRouteTemplateName',$data);
//        var_dump($RouteTemplateList);exit;
        if($RouteTemplateList['data']){
            $this->assign('RouteTemplateList',$RouteTemplateList['data']);
        }
        unset($data['company_id']);

        //线路类型
        $data['company_id'] = session('user')['company_id'];
        $RouteTypeList = $this->callSoaErp('post','/system/getRouteType',$data);
        if($RouteTypeList['data']){
            $RouteType = Arrays::group($RouteTypeList['data'],'type');
            $this->assign('RouteType',$RouteType);
        }
        unset($data['company_id']);
        //分公司
        $CompanyList = $this->callSoaErp('post','/system/getCompany',$data);
        if($CompanyList['data']){
            $this->assign('CompanyList',$CompanyList['data']);
        }
        //线路负责人
        $company_id = session('user')['company_id'];
        $data['department_id'] = session('user')['department_id'];
        
        $UserList = $this->callSoaErp('post','/user/getUser',$data);
        if($UserList['data']){
            $this->assign('UserList',$UserList['data']);
        }
        unset($data['department_id']);

        //操作用户
        $this->assign('UserId',session('user')['user_id']);
        //回执单模板
        $data['company_id'] = session('user')['company_id'];
        $ReturnReceiptList = $this->callSoaErp('post','/system/getReturnReceipt',$data);
        if($ReturnReceiptList['data']){
            $this->assign('ReturnReceiptList',$ReturnReceiptList['data']);
        }

        $CountryList = $this->callSoaErp('post','/system/getCountry',['level'=>3,'status'=>1]);
        $this->assign('CountryList',$CountryList['data']);
        return $this->fetch('plan_tour_add');
    }

    /***
     * 异步获取回执单内容
     * Hugh 19-09-03
     */
    public function showReturnReceiptAjax(){
        $data['return_receipt_id'] = input('post.ReturnReceiptId');
        $data['status'] = 1;
        $ReturnReceiptInfo = $this->callSoaErp('post','/system/getReturnReceiptInfo',$data);
        if($ReturnReceiptInfo['data']){
            $data['code'] = 200;
            $data['data'] = $ReturnReceiptInfo['data'];
        }else{
            $data['code'] = 400;
            $data['data'] = '';
        }
        echo json_encode($data);
    }

    /**
     * 团队产品酒店资源
     * Hugh 19-09-03
     */
    public function showPlanHotelAdd(){
        //酒店供应商
        $data['status'] = 1;
        $data['supplier_type_id'] = 2;
        $data['company_id'] = session('user')['company_id'];

        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);

        $this->getCurrency2();
        $this->assign('room_type',$this->room_type);

//        return $this->fetch('plan_hotel_add');
        return $this->fetch('n_plan_hotel_add');
    }

    /**
     * 供应商获取酒店
     * Hugh 19-09-03
     */
    public function getHotelAjax(){
        //获取代理酒店资源
        $data['status'] = 1;
        $data['supplier_id'] = input('post.supplier_id');

        $oj['code'] = 400;
        $hotelList = $this->callSoaErp('post','/source/getHotel',$data);
        if(!empty($hotelList['data'])){
            $oj['code'] = 200;
            $oj['hotelData'] = $hotelList['data'];
        }
        unset($data['supplier_id']);
        $data['belong_supplier_id'] = input('post.supplier_id');
        $hotelList2 = $this->callSoaErp('post','/source/getHotel',$data);
        if(!empty($hotelList2['data'])){
            $oj['code'] = 200;
            $oj['LocalTravelAgencyAr'] = $this->getLocalTravelAgency($hotelList2['data']);
        }

        echo json_encode($oj);
    }

    //获取代理下的地接
    public function getLocalTravelAgency($data){
        $agent_id = Arrays::group($data,'supplier_id');
        $LocalTravelAgencyAr = [];
        foreach($agent_id as $ky=>$vl){
            $w['status'] = 1;
            $w['supplier_id'] = $ky;
            $w['supplier_type_id']=1;
            $SupplierLs = $this->callSoaErp('post','/source/getSupplier',$w);
            if(!empty($SupplierLs['data'])){
                $ar['supplier_id'] = $SupplierLs['data'][0]['supplier_id'];
                $ar['supplier_name'] = $SupplierLs['data'][0]['supplier_name'];
                $LocalTravelAgencyAr[] = $ar;
            }
        }
        return $LocalTravelAgencyAr;
    }

    /***
     * 地接获取酒店信息
     * Hugh 19-09-03
     */
    public function getHotelAgentAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.agent_id');
        $data['belong_supplier_id'] = input('post.supplier_id');
        $hotelList = $this->callSoaErp('post','/source/getHotel',$data);
        $oj['code'] = 400;
        if(!empty($hotelList['data'])){
            $oj['code'] = 200;
            $oj['hotelData'] = $hotelList['data'];
        }
        echo json_encode($oj);
    }

    /**
     * 获取酒店信息
     * Hugh 19-09-03
     */
    public function getHotelInfoAjax(){
        $data['hotel_id'] = input('post.hotel_id');
        $hotelList = $this->callSoaErp('post','/source/getHotel',$data);
        $oj['code'] = 400;
        if(!empty($hotelList['data'])){
            $oj['code'] = 200;
            $oj['hotelData'] = $hotelList['data'][0];
        }
        echo json_encode($oj);
    }


    /**
     * 酒店资源session
     * Hugh 18-12-18
     */
    public function NsessionHotelAjax(){
        $post = Request::instance()->param();
//        var_dump($post);exit;
        if(empty($post)){
            Session::delete('session_hotel');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $room_name = Arrays::get($post,'room_name');
            $room_type = Arrays::get($post,'room_type');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $hotel = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['room_name'] = $room_name[$ky];
                $ar['room_type'] = $room_type[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $hotel[] = $ar;
                unset($ar);
            }
            Session::set('session_hotel',$hotel);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }


    /**
     * 酒店资源session
     * Hugh 19-09-03
     */
    public function sessionHotelAjax(){
        $post = Request::instance()->param();
//        var_dump($post);exit;
        if(empty($post)){
            Session::delete('session_hotel');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $room_name = Arrays::get($post,'room_name');
            $room_type = Arrays::get($post,'room_type');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $hotel = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['room_name'] = $room_name[$ky];
                $ar['room_type'] = $room_type[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $hotel[] = $ar;
            }
            Session::set('session_hotel',$hotel);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }



    /**
     * 团队产品用餐资源
     * Hugh 19-09-03
     */
    public function showPlanDiningAdd(){
        //用餐供应商
        $data['status'] = 1;
        $data['supplier_type_id'] = 3;
        $data['company_id'] = session('user')['company_id'];

        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);
        $this->getCurrency2();
//        return $this->fetch('plan_dining_add');
        return $this->fetch('n_plan_dining_add');
    }

    /**
     * 供应商获取用餐信息
     * Hugh 19-09-03
     */
    public function getDiningAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.supplier_id');
        $diningList = $this->callSoaErp('post','/source/getDining',$data);
        $oj['code'] = 400;
        if(!empty($diningList['data'])){
            $oj['code'] = 200;
            $oj['diningData'] = $diningList['data'];
        }
        unset($data['supplier_id']);
        $data['belong_supplier_id'] = input('post.supplier_id');
        $diningList2 = $this->callSoaErp('post','/source/getDining',$data);
        if(!empty($diningList2['data'])){
            $oj['code'] = 200;
            $oj['LocalTravelAgencyAr'] = $this->getLocalTravelAgency($diningList2['data']);
        }

        echo json_encode($oj);
    }

    /**
     * 地接获取用餐
     * Hugh 19-09-03
     */
    public function getDiningAgentAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.agent_id');
        $data['belong_supplier_id'] = input('post.supplier_id');
        $diningList = $this->callSoaErp('post','/source/getDining',$data);
//        var_dump($diningList);
        $oj['code'] = 400;
        if(!empty($diningList['data'])){
            $oj['code'] = 200;
            $oj['diningData'] = $diningList['data'];
        }
        echo json_encode($oj);
    }

    /**
     * 用餐详情
     * Hugh 19-09-03
     */
    public function getDiningInfo(){
        $data['dining_id'] = input('post.dining_id');
        $diningList = $this->callSoaErp('post','/source/getDining',$data);
        $oj['code'] = 400;
        if(!empty($diningList['data'])){
            $oj['code'] = 200;
            $oj['diningData'] = $diningList['data'][0];
        }
        echo json_encode($oj);
    }

    /***
     * 用餐资源Session
     * Hugh 18-12-18
     */
    public function NsessionDiningAjax(){
        $post = Request::instance()->param();
//        var_dump($post);exit;
        if(empty($post)){
            Session::delete('session_dining');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $dining_name = Arrays::get($post,'dining_name');
            $standard_type = Arrays::get($post,'standard_type');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $dining = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['dining_name'] = $dining_name[$ky];
                $ar['standard_type'] = $standard_type[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $dining[] = $ar;
                unset($ar);
            }
            Session::set('session_dining',$dining);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /***
     * 用餐资源Session
     * Hugh 19-09-03
     */
    public function sessionDiningAjax(){
        $post = Request::instance()->param();
//        var_dump($post);exit;
        if(empty($post)){
            Session::delete('session_dining');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $dining_name = Arrays::get($post,'dining_name');
            $standard_type = Arrays::get($post,'standard_type');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $dining = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['dining_name'] = $dining_name[$ky];
                $ar['standard_type'] = $standard_type[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $dining[] = $ar;
            }
            Session::set('session_dining',$dining);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /**
     * 团队产品航班资源
     * Hugh 19-09-04
     */
    public function showPlanFlightAdd(){
        //航班供应商
        $data['status'] = 1;
        $data['supplier_type_id'] = 4;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);

        $this->getCurrency2();
//        return $this->fetch('plan_flight_add');
        return $this->fetch('n_plan_flight_add');
    }

    /**
     * 供应商获取航班信息
     * Hugh 19-09-04
     */
    public function getFlightAjax(){
        //获取供应商航班资源
        $data['status'] = 1;
        $data['supplier_id'] = input('post.supplier_id');
        $oj['code'] = 400;
        $flightList = $this->callSoaErp('post','/source/getFlight',$data);
        if(!empty($flightList['data'])){
            $oj['code'] = 200;
            $oj['flightData'] = $flightList['data'];
        }
        unset($data['supplier_id']);
        $data['belong_supplier_id'] = input('post.supplier_id');
        $flightList2 = $this->callSoaErp('post','/source/getFlight',$data);
        if(!empty($flightList2['data'])){
            $oj['code'] = 200;
            $oj['LocalTravelAgencyAr'] = $this->getLocalTravelAgency($flightList2['data']);
        }

        echo json_encode($oj);
    }

    /**
     * 地接航班信息
     * Hugh 19-09-04
     */
    public function getFlightAgentAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.agent_id');
        $data['belong_supplier_id'] = input('post.supplier_id');
        $FlightList = $this->callSoaErp('post','/source/getFlight',$data);
        $oj['code'] = 400;
        if(!empty($FlightList['data'])){
            $oj['code'] = 200;
            $oj['flightData'] = $FlightList['data'];
        }
        echo json_encode($oj);
    }

    /**
     * 航班信息
     * Hugh 19-09-04
     */
    public function getFlightInfo(){
        $data['flight_id'] = input('post.flight_id');
        $flightList = $this->callSoaErp('post','/source/getFlight',$data);
        $oj['code'] = 400;
        if(!empty($flightList['data'])){
            $oj['code'] = 200;
            $oj['flightData'] = $flightList['data'][0];
        }
        echo json_encode($oj);
    }


    /***
     * 航班资源Session
     * Hugh 18-12-18
     */
    public function NsessionFlightAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            Session::delete('session_flight');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $flight_number = Arrays::get($post,'flight_number');
            $shipping_space = Arrays::get($post,'shipping_space');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $flight = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['flight_number'] = $flight_number[$ky];
                $ar['shipping_space'] = $shipping_space[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $flight[] = $ar;
                unset($ar);
            }
            Session::set('session_flight',$flight);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /***
     * 航班资源Session
     * Hugh 19-09-04
     */
    public function sessionFlightAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            Session::delete('session_flight');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $flight_number = Arrays::get($post,'flight_number');
            $shipping_space = Arrays::get($post,'shipping_space');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $flight = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['flight_number'] = $flight_number[$ky];
                $ar['shipping_space'] = $shipping_space[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $flight[] = $ar;
            }
            Session::set('session_flight',$flight);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }


    /**
     * 团队产品邮轮资源
     * Hugh 19-09-04
     */
    public function showPlanCruiseAdd(){
        //邮轮供应商
        $data['status'] = 1;
        $data['supplier_type_id'] = 5;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);
        $this->getCurrency2();
//        return $this->fetch('plan_cruise_add');
        return $this->fetch('n_plan_cruise_add');
    }

    /**
     * 供应商邮轮
     * Hugh 19-09-04
     */
    public function getCruiseAjax(){
        //获取供应商邮轮资源
        $data['status'] = 1;
        $data['supplier_id'] = input('post.supplier_id');
        $oj['code'] = 400;
        $cruiseList = $this->callSoaErp('post','/source/getCruise',$data);
        if(!empty($cruiseList['data'])){
            $oj['code'] = 200;
            $oj['cruiseData'] = $cruiseList['data'];
        }
        unset($data['supplier_id']);
        $data['belong_supplier_id'] = input('post.supplier_id');
        $cruiseList2 = $this->callSoaErp('post','/source/getCruise',$data);
        if(!empty($cruiseList2['data'])){
            $oj['code'] = 200;
            $oj['LocalTravelAgencyAr'] = $this->getLocalTravelAgency($cruiseList2['data']);
        }

        echo json_encode($oj);
    }

    /**
     * 地接社邮轮
     * Hugh 19-09-04
     */
    public function getCruiseAgentAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.agent_id');
        $data['belong_supplier_id'] = input('post.supplier_id');
        $cruiseList = $this->callSoaErp('post','/source/getCruise',$data);
        $oj['code'] = 400;
        if(!empty($cruiseList['data'])){
            $oj['code'] = 200;
            $oj['cruiseData'] = $cruiseList['data'];
        }
        echo json_encode($oj);
    }

    /**
     * 邮轮信息
     * Hugh 19-09-04
     */
    public function getCruiseInfo(){
        $data['cruise_id'] = input('post.cruise_id');
        $cruiseList = $this->callSoaErp('post','/source/getCruise',$data);
        $oj['code'] = 400;
        if(!empty($cruiseList['data'])){
            $oj['code'] = 200;
            $oj['cruiseData'] = $cruiseList['data'][0];
        }
        echo json_encode($oj);
    }

    /**
     * 邮轮Session
     * Hugh 19-09-04
     */
    public function NsessionCruiseAjax(){
        $post = Request::instance()->param();

        if(empty($post)){
            Session::delete('session_cruise');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $cruise_name = Arrays::get($post,'cruise_name');
            $room_name = Arrays::get($post,'room_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $cruise = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['cruise_name'] = $cruise_name[$ky];
                $ar['room_name'] = $room_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $cruise[] = $ar;
                unset($ar);
            }
            Session::set('session_cruise',$cruise);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }


    /**
     * 邮轮Session
     * Hugh 19-09-04
     */
    public function sessionCruiseAjax(){
        $post = Request::instance()->param();

        if(empty($post)){
            Session::delete('session_cruise');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $cruise_name = Arrays::get($post,'cruise_name');
            $room_name = Arrays::get($post,'room_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $cruise = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['cruise_name'] = $cruise_name[$ky];
                $ar['room_name'] = $room_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $cruise[] = $ar;
            }
            Session::set('session_cruise',$cruise);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }


    /**
     * 团队产品签证资源
     * Hugh 19-09-04
     */
    public function showPlanVisaAdd(){
        //签证供应商
        $data['status'] = 1;
        $data['supplier_type_id'] = 6;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);
        $this->getCurrency2();
//        return $this->fetch('plan_visa_add');
        return $this->fetch('n_plan_visa_add');
    }

    /**
     * 供应商签证
     * Hugh 19-09-04
     */
    public function getVisaAjax(){
        //获取供应商签证资源
        $data['status'] = 1;
        $data['supplier_id'] = input('post.supplier_id');
        $oj['code'] = 400;
        $visaList = $this->callSoaErp('post','/source/getVisa',$data);
        if(!empty($visaList['data'])){
            $oj['code'] = 200;
            $oj['visaData'] = $visaList['data'];
        }
        unset($data['supplier_id']);
        $data['belong_supplier_id'] = input('post.supplier_id');
        $visaList2 = $this->callSoaErp('post','/source/getVisa',$data);
        if(!empty($visaList2['data'])){
            $oj['code'] = 200;
            $oj['LocalTravelAgencyAr'] = $this->getLocalTravelAgency($visaList2['data']);
        }

        echo json_encode($oj);
    }

    /**
     * 地接社签证
     * Hugh 19-09-04
     */
    public function getVisaAgentAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.agent_id');
        $data['belong_supplier_id'] = input('post.supplier_id');
        $visaList = $this->callSoaErp('post','/source/getVisa',$data);
        $oj['code'] = 400;
        if(!empty($visaList['data'])){
            $oj['code'] = 200;
            $oj['visaData'] = $visaList['data'];
        }
        echo json_encode($oj);
    }

    /**
     * 签证详情
     * Hugh 19-09-04
     */
    public function getVisaInfo(){
        $data['visa_id'] = input('post.visa_id');
        $visaList = $this->callSoaErp('post','/source/getVisa',$data);
        $oj['code'] = 400;
        if(!empty($visaList['data'])){
            $oj['code'] = 200;
            $oj['visaData'] = $visaList['data'][0];
        }
        echo json_encode($oj);
    }


    /**
     * 签证session
     * Hugh 19-09-04
     */
    public function NsessionVisaAjax(){
        $post = Request::instance()->param();

        if(empty($post)){
            Session::delete('session_visa');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $visa_name = Arrays::get($post,'visa_name');
            $file_url = Arrays::get($post,'file_url');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $visa = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['visa_name'] = $visa_name[$ky];
                $ar['file_url'] = $file_url[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $visa[] = $ar;
                unset($ar);
            }

            Session::set('session_visa',$visa);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /**
     * 签证session
     * Hugh 19-09-04
     */
    public function sessionVisaAjax(){
        $post = Request::instance()->param();

        if(empty($post)){
            Session::delete('session_visa');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $visa_name = Arrays::get($post,'visa_name');
            $file_url = Arrays::get($post,'file_url');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $visa = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['visa_name'] = $visa_name[$ky];
                $ar['file_url'] = $file_url[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $visa[] = $ar;
            }

            Session::set('session_visa',$visa);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }


    /**
     * 团队产品景点资源
     * Hugh 19-09-04
     */
    public function showPlanScenicSpotAdd(){
        //景点供应商
        $data['status'] = 1;
        $data['supplier_type_id'] = 7;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);

        $this->getCurrency2();
//        return $this->fetch('plan_scenic_spot_add');
        return $this->fetch('n_plan_scenic_spot_add');
    }

    /**
     * 供应商景点
     * Hugh 19-09-04
     */
    public function getScenicSpotAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.supplier_id');
        $oj['code'] = 400;
        $ScenicSpotList = $this->callSoaErp('post','/source/getScenicSpot',$data);
        if(!empty($ScenicSpotList['data'])){
            $oj['code'] = 200;
            $oj['ScenicSpotData'] = $ScenicSpotList['data'];
        }
        unset($data['supplier_id']);
        $data['belong_supplier_id'] = input('post.supplier_id');
        $ScenicSpotList2 = $this->callSoaErp('post','/source/getScenicSpot',$data);
        if(!empty($ScenicSpotList2['data'])){
            $oj['code'] = 200;
            $oj['LocalTravelAgencyAr'] = $this->getLocalTravelAgency($ScenicSpotList2['data']);
        }

        echo json_encode($oj);
    }

    /**
     * 地接社景点
     * Hugh 19-09-04
     */
    public function getScenicSpotAgentAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.agent_id');
        $data['belong_supplier_id'] = input('post.supplier_id');
        $ScenicSpotList = $this->callSoaErp('post','/source/getScenicSpot',$data);
        $oj['code'] = 400;
        if(!empty($ScenicSpotList['data'])){
            $oj['code'] = 200;
            $oj['ScenicSpotData'] = $ScenicSpotList['data'];
        }
        echo json_encode($oj);
    }


    /**
     * 景点详情
     * Hugh 19-09-04
     */
    public function getScenicSpotInfo(){
        $data['scenic_spot_id'] = input('post.scenic_spot_id');
        $ScenicSpotList = $this->callSoaErp('post','/source/getScenicSpot',$data);
        $oj['code'] = 400;
        if(!empty($ScenicSpotList['data'])){
            $oj['code'] = 200;
            $oj['ScenicSpotData'] = $ScenicSpotList['data'][0];
        }
        echo json_encode($oj);
    }

    /**
     * 景点Session
     * Hugh 18-12-18
     */
    public function NsessionScenicSpotAjax(){
        $post = Request::instance()->param();

        if(empty($post)){
            Session::delete('session_scenic_spot');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $scenic_spot_name = Arrays::get($post,'scenic_spot_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $ScenicSpot = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['scenic_spot_name'] = $scenic_spot_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $ScenicSpot[] = $ar;
                unset($ar);
            }

            Session::set('session_scenic_spot',$ScenicSpot);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /**
     * 景点Session
     * Hugh 19-09-04
     */
    public function sessionScenicSpotAjax(){
        $post = Request::instance()->param();

        if(empty($post)){
            Session::delete('session_scenic_spot');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $scenic_spot_name = Arrays::get($post,'scenic_spot_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $ScenicSpot = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['scenic_spot_name'] = $scenic_spot_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $ScenicSpot[] = $ar;
            }

            Session::set('session_scenic_spot',$ScenicSpot);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /**
     * 团队产品车辆资源
     * Hugh 19-09-04
     */
    public function showPlanVehicleAdd(){
        //车辆供应商
        $data['status'] = 1;
        $data['supplier_type_id'] = 8;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);

        $this->getCurrency2();
//        return $this->fetch('plan_vehicle_add');
        return $this->fetch('n_plan_vehicle_add');
    }

    /**
     * 供应商车辆资源
     * Hugh 19-09-04
     */
    public function getVehicleAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.supplier_id');
        $oj['code'] = 400;
        $vehicleList = $this->callSoaErp('post','/source/getVehicle',$data);
        if(!empty($vehicleList['data'])){
            $oj['code'] = 200;
            $oj['vehicleData'] = $vehicleList['data'];
        }
        unset($data['supplier_id']);
        $data['belong_supplier_id'] = input('post.supplier_id');
        $vehicleList2 = $this->callSoaErp('post','/source/getVehicle',$data);
        if(!empty($vehicleList2['data'])){
            $oj['code'] = 200;
            $oj['LocalTravelAgencyAr'] = $this->getLocalTravelAgency($vehicleList2['data']);
        }

        echo json_encode($oj);
    }

    /**
     * 地接社车辆
     * Hugh 19-09-04
     */
    public function getVehicleAgentAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.agent_id');
        $data['belong_supplier_id'] = input('post.supplier_id');
        $vehicleList = $this->callSoaErp('post','/source/getVehicle',$data);
        $oj['code'] = 400;
        if(!empty($vehicleList['data'])){
            $oj['code'] = 200;
            $oj['vehicleData'] = $vehicleList['data'];
        }
        echo json_encode($oj);
    }

    /**
     * 车辆详情
     * Hugh 19-09-04
     */
    public function getVehicleInfo(){
        $data['vehicle_id'] = input('post.vehicle_id');
        $vehicleList = $this->callSoaErp('post','/source/getVehicle',$data);
        $oj['code'] = 400;
        if(!empty($vehicleList['data'])){
            $oj['code'] = 200;
            $oj['vehicleData'] = $vehicleList['data'][0];
        }
        echo json_encode($oj);
    }


    /**
     * 车辆Session
     * Hugh 19-09-04
     */
    public function NsessionVehicleAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            Session::delete('session_vehicle');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $vehicle_name = Arrays::get($post,'vehicle_name');
            $vehicle_number = Arrays::get($post,'vehicle_number');
            $load = Arrays::get($post,'load');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');
            $Vehicle = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['vehicle_name'] = $vehicle_name[$ky];
                $ar['vehicle_number'] = $vehicle_number[$ky];
                $ar['load'] = $load[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $Vehicle[] = $ar;
            }

            Session::set('session_vehicle',$Vehicle);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /**
     * 车辆Session
     * Hugh 19-09-04
     */
    public function sessionVehicleAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            Session::delete('session_vehicle');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $vehicle_name = Arrays::get($post,'vehicle_name');
            $vehicle_number = Arrays::get($post,'vehicle_number');
            $load = Arrays::get($post,'load');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $Vehicle = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['vehicle_name'] = $vehicle_name[$ky];
                $ar['vehicle_number'] = $vehicle_number[$ky];
                $ar['load'] = $load[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $Vehicle[] = $ar;
            }

            Session::set('session_vehicle',$Vehicle);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /**
     * 团队产品导游
     * Hugh 19-09-04
     */
    public function showPlanTourGuideAdd(){
        $data['status'] = 1;
        $data['supplier_type_id'] = 9;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);
        $this->getCurrency2();
//        return $this->fetch('plan_tour_guide_add');
        return $this->fetch('n_plan_tour_guide_add');
    }

    /**
     * 代理商导游
     * Hugh 19-09-04
     */
    public function getTourGuideAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.supplier_id');
        $oj['code'] = 400;
        $tourGuideList = $this->callSoaErp('post','/source/getTourGuide',$data);
        if(!empty($tourGuideList['data'])){
            $oj['code'] = 200;
            $oj['tourGuideData'] = $tourGuideList['data'];
        }
        unset($data['supplier_id']);
        $data['belong_supplier_id'] = input('post.supplier_id');
        $tourGuideList2 = $this->callSoaErp('post','/source/getTourGuide',$data);
        if(!empty($tourGuideList2['data'])){
            $oj['code'] = 200;
            $oj['LocalTravelAgencyAr'] = $this->getLocalTravelAgency($tourGuideList2['data']);
        }

        echo json_encode($oj);
    }

    /**
     * 地接社导游
     * Hugh 19-09-04
     */
    public function getTourGuideAgentAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.agent_id');
        $data['belong_supplier_id'] = input('post.supplier_id');
        $tourGuideList = $this->callSoaErp('post','/source/getTourGuide',$data);
        $oj['code'] = 400;
        if(!empty($tourGuideList['data'])){
            $oj['code'] = 200;
            $oj['tourGuideData'] = $tourGuideList['data'];
        }
        echo json_encode($oj);
    }

    /**
     * 导游详情
     * Hugh 19-09-04
     */
    public function getTourGuideInfo(){
        $data['tour_guide_id'] = input('post.tour_guide_id');
        $tourGuideList = $this->callSoaErp('post','/source/getTourGuide',$data);
        $oj['code'] = 400;
        if(!empty($tourGuideList['data'])){
            $oj['code'] = 200;
            $oj['tourGuideData'] = $tourGuideList['data'][0];
        }
        echo json_encode($oj);
    }


    /*
     * 导游Session
     * Hugh 18-12-18
     */
    public function NsessionTourGuideAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            session::delete('session_tourGuide');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $tour_guide_name = Arrays::get($post,'tour_guide_name');
            $guide_id_card = Arrays::get($post,'guide_id_card');
            $phone = Arrays::get($post,'phone');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $tourGuide = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['tour_guide_name'] = $tour_guide_name[$ky];
                $ar['guide_id_card'] = $guide_id_card[$ky];
                $ar['phone'] = $phone[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id']= $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $tourGuide[] = $ar;
                unset($ar);
            }

            Session::set('session_tourGuide',$tourGuide);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }


    /*
     * 导游Session
     * Hugh 19-09-04
     */
    public function sessionTourGuideAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            session::delete('session_tourGuide');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $tour_guide_name = Arrays::get($post,'tour_guide_name');
            $guide_id_card = Arrays::get($post,'guide_id_card');
            $phone = Arrays::get($post,'phone');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $tourGuide = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['tour_guide_name'] = $tour_guide_name[$ky];
                $ar['guide_id_card'] = $guide_id_card[$ky];
                $ar['phone'] = $phone[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id']= $team_product_allocation_id[$ky];
                $tourGuide[] = $ar;
            }

            Session::set('session_tourGuide',$tourGuide);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /**
     * 团队产品单项资源
     * Hugh 19-09-04
     */
    public function showPlanSingleSourceAdd(){
        $data['status'] = 1;
        $data['supplier_type_id'] = 10;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);

        $this->getCurrency2();
//        return $this->fetch('plan_single_source_add');
        return $this->fetch('n_plan_single_source_add');
    }

    /**
     * 供应商单项资源
     * Hugh 19-09-04
     */
    public function getSingleSourceAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.supplier_id');
        $oj['code'] = 400;
        $singleSourceList = $this->callSoaErp('post','/source/getSingleSource',$data);
        if(!empty($singleSourceList['data'])){
            $oj['code'] = 200;
            $oj['singleSourceData'] = $singleSourceList['data'];
        }
        unset($data['supplier_id']);
        $data['belong_supplier_id'] = input('post.supplier_id');
        $singleSourceList2 = $this->callSoaErp('post','/source/getSingleSource',$data);
        if(!empty($singleSourceList2['data'])){
            $oj['code'] = 200;
            $oj['LocalTravelAgencyAr'] = $this->getLocalTravelAgency($singleSourceList2['data']);
        }
        echo json_encode($oj);
    }

    /**
     * 地接社单项资源
     * Hugh 19-09-04
     */
    public function getSingleSourceAgentAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.agent_id');
        $data['belong_supplier_id'] = input('post.supplier_id');
        $singleSourceList = $this->callSoaErp('post','/source/getSingleSource',$data);
        $oj['code'] = 400;
        if(!empty($singleSourceList['data'])){
            $oj['code'] = 200;
            $oj['singleSourceData'] = $singleSourceList['data'];
        }
        echo json_encode($oj);
    }

    /**
     * 单项资源详情
     * Hugh 19-09-04
     */
    public function getSingleSourceInfo(){
        $data['single_source_id'] = input('post.single_source_id');
        $singleSourceList = $this->callSoaErp('post','/source/getSingleSource',$data);
        $oj['code'] = 400;
        if(!empty($singleSourceList['data'])){
            $oj['code'] = 200;
            $oj['singleSourceData'] = $singleSourceList['data'][0];
        }
        echo json_encode($oj);
    }


    /**
     * 单项资源Session
     * Hugh 18-12-18
     */
    public function NsessionSingleSourceAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            Session::delete('session_singleSource');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $single_source_name = Arrays::get($post,'single_source_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $singleSource = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['single_source_name'] = $single_source_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $singleSource[] = $ar;
                unset($ar);
            }

            Session::set('session_singleSource',$singleSource);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    /**
     * 单项资源Session
     * Hugh 19-09-04
     */
    public function sessionSingleSourceAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
             Session::delete('session_singleSource');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $single_source_name = Arrays::get($post,'single_source_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $singleSource = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['single_source_name'] = $single_source_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $singleSource[] = $ar;
            }

            Session::set('session_singleSource',$singleSource);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }


    /***
     * 团队产品自费项目
     */
    public function showPlanOptionalAdd(){
        $data['status'] = 1;
        $data['supplier_type_id'] = 11;
        $data['company_id'] = session('user')['company_id'];
        $SupplierList =  $this->callSoaErp('post','/source/getSupplier',$data);
        if(!empty($SupplierList['data']))
            $this->assign('SupplierList',$SupplierList['data']);
        $this->getCurrency2();
//        return $this->fetch('plan_ptional_add');
        return $this->fetch('n_plan_ptional_add');
    }

    /**
     * 供应商选项自费项目
     */
    public function getOptionalAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.supplier_id');
        $oj['code'] = 400;
        $singleSourceList = $this->callSoaErp('post','/source/getOwnExpense',$data);
        if(!empty($singleSourceList['data'])){
            $oj['code'] = 200;
            $oj['singleSourceData'] = $singleSourceList['data'];
        }
        unset($data['supplier_id']);
        $data['belong_supplier_id'] = input('post.supplier_id');
        $singleSourceList2 = $this->callSoaErp('post','/source/getOwnExpense',$data);
        if(!empty($singleSourceList2['data'])){
            $oj['code'] = 200;
            $oj['LocalTravelAgencyAr'] = $this->getLocalTravelAgency($singleSourceList2['data']);
        }
        echo json_encode($oj);
    }


    /**
     * 地接社选项自费项目
     */
    public function getOptionalAgentAjax(){
        $data['status'] = 1;
        $data['supplier_id'] = input('post.agent_id');
        $data['belong_supplier_id'] = input('post.supplier_id');
        $singleSourceList = $this->callSoaErp('post','/source/getOwnExpense',$data);
        $oj['code'] = 400;
        if(!empty($singleSourceList['data'])){
            $oj['code'] = 200;
            $oj['singleSourceData'] = $singleSourceList['data'];
        }
        echo json_encode($oj);
    }

    /**
     * 地接社选项详情
     */
    public function getOptionalInfo(){
        $data['own_expense_id'] = input('post.single_source_id');
        $singleSourceList = $this->callSoaErp('post','/source/getOwnExpense',$data);
        $oj['code'] = 400;
        if(!empty($singleSourceList['data'])){
            $oj['code'] = 200;
            $oj['singleSourceData'] = $singleSourceList['data'][0];
        }
        echo json_encode($oj);
    }

    public function showPlanOptionalUpdate(){
        $session_Optional = Session::get('session_Optional');
        $this->getSupplier(11);//获取供应商
        $session_Optional =  $this->linkage($session_Optional,'/source/getOwnExpense');
        $this->assign('session_Optional',$session_Optional);
//        var_dump($session_Optional[1]);exit;
        $this->getCurrency2();
//        return $this->fetch('plan_optional_update');
        return $this->fetch('n_plan_optional_update');
    }

    public function showPlanOptionalInfo(){
        $session_Optional = Session::get('session_Optional');
        $this->getSupplier(11);//获取供应商
        $session_Optional =  $this->linkage($session_Optional,'/source/getOwnExpense');
        $this->assign('session_Optional',$session_Optional);
//        var_dump($session_Optional[1]);exit;
        $this->getCurrency2();
//        return $this->fetch('plan_optional_update');
        return $this->fetch('n_plan_optional_info');
    }

    /**
     * 团价自费项目 Session
     */
    public function NsessionOptionalAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            Session::delete('session_Optional');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $own_expense_name = Arrays::get($post,'own_expense_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);

            $the_day = Arrays::get($post,'the_day');
            $cost_price = Arrays::get($post,'cost_price');
            $symbol = Arrays::get($post,'symbol');

            $Optional = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['own_expense_name'] = $own_expense_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];

                $ar['the_day'] = $the_day[$ky];
                $ar['cost_price'] = $cost_price[$ky];
                $ar['symbol'] = $symbol[$ky];

                $Optional[] = $ar;
            }

            Session::set('session_Optional',$Optional);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }


    /**
     * 团价自费项目 Session
     */
    public function sessionOptionalAjax(){
        $post = Request::instance()->param();
        if(empty($post)){
            Session::delete('session_Optional');
        }else{
            $supplier_id = Arrays::get($post,'supplier_id');
            $agent_id = Arrays::get($post,'agent_id');
            $own_expense_name = Arrays::get($post,'own_expense_name');
            $quantity = Arrays::get($post,'quantity');
            $unit_value_type = Arrays::get($post,'unit_value_type');
            $currency = Arrays::get($post,'currency');
            $unit_price = Arrays::get($post,'unit_price');
            $total = Arrays::get($post,'total');
            $team_product_allocation_id = Arrays::get($post,'team_product_allocation_id',[]);
            $Optional = [];
            foreach($supplier_id as $ky=>$vl){
                $ar['supplier_id'] = $supplier_id[$ky];
                $ar['agent_id'] = $agent_id[$ky];
                $ar['own_expense_name'] = $own_expense_name[$ky];
                $ar['quantity'] = $quantity[$ky];
                $ar['unit_value_type'] = $unit_value_type[$ky];
                $ar['currency'] = $currency[$ky];
                $ar['unit_price'] = $unit_price[$ky];
                $ar['total'] = $total[$ky];
                $ar['team_product_allocation_id'] = $team_product_allocation_id[$ky];
                $Optional[] = $ar;
            }

            Session::set('session_Optional',$Optional);
        }

        $data['code'] = 200;
        $data['msg'] = 'success';
        $data['data'] = '';
        return $data;
    }

    public function showProductQuotationHtmlInfo(){
        $post = Request::instance()->param();
        $settlement_type = Arrays::get($post,'settlement_type');
        $use_company_id = Arrays::get($post,'use_company_id');
        $plan_id = Arrays::get($post,'plan_id',0);
        $this->assign('settlement_type',$settlement_type);

        $session_hotel = Session::get('session_hotel');
        $session_dining = Session::get('session_dining');
        $session_flight = Session::get('session_flight');
        $session_cruise = Session::get('session_cruise');
        $session_visa = Session::get('session_visa');
        $session_scenic_spot = Session::get('session_scenic_spot');
        $session_vehicle = Session::get('session_vehicle');
        $session_tourGuide = Session::get('session_tourGuide');
        $session_singleSource = Session::get('session_singleSource');
        $session_Optional = Session::get('session_Optional');

        $resource = [];
        $monetarySum  = [];

        //酒店资源
        $session_hotel_total = 0;
        if($session_hotel){
            $session_hotel = $this->resourceConfigureInfo($session_hotel,'/source/getHotel','hotel_id','room_name');
            $this->assign('count_session_hotel',count($session_hotel));
            $session_hotel_total = $this->totalSum($session_hotel);
//            $session_hotel = Arrays::group($session_hotel,'supplier_id');
//            $this->assign('session_hotel',$session_hotel);
//            var_dump($session_hotel);exit;

            foreach($session_hotel as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 2;
                $ar['resource_id'] = $v['hotel_id']; //资源ID
                $ar['resource_name'] = $v['room_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }

        }
        //用餐
        $session_dining_total = 0;
        if($session_dining){
            $session_dining = $this->resourceConfigureInfo($session_dining,'/source/getDining','dining_id','dining_name');
            $this->assign('count_session_dining',count($session_dining));
            $session_dining_total = $this->totalSum($session_dining);
//            $session_dining = Arrays::group($session_dining,'supplier_id');
//            $this->assign('session_dining',$session_dining);
//            var_dump($session_dining);exit;

            foreach($session_dining as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 3;
                $ar['resource_id'] = $v['dining_id']; //资源ID
                $ar['resource_name'] = $v['dining_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }

        }
        //航班
        $session_flight_total = 0;
        if($session_flight){
            $session_flight = $this->resourceConfigureInfo($session_flight,'/source/getFlight','flight_id','flight_number');
            $this->assign('count_session_flight',count($session_flight));
            $session_flight_total = $this->totalSum($session_flight);
//            $session_flight = Arrays::group($session_flight,'supplier_id');
//            $this->assign('session_flight',$session_flight);
//            var_dump($session_flight);exit;

            foreach($session_flight as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 4;
                $ar['resource_id'] = $v['flight_id']; //资源ID
                $ar['resource_name'] = $v['flight_number']."({$v['shipping_space']})"; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }

        }
        //邮轮
        $session_cruise_total = 0;
        if($session_cruise){
            $session_cruise = $this->resourceConfigureInfo($session_cruise,'/source/getCruise','cruise_id','cruise_name');
            $this->assign('count_session_cruise',count($session_cruise));
            $session_cruise_total = $this->totalSum($session_cruise);
//            $session_cruise = Arrays::group($session_cruise,'supplier_id');
//            $this->assign('session_cruise',$session_cruise);
//            var_dump($session_cruise);exit;

            foreach($session_cruise as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 5;
                $ar['resource_id'] = $v['cruise_id']; //资源ID
                $ar['resource_name'] = $v['cruise_name']."({$v['room_name']})"; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }

        }
        //签证
        $session_visa_total = 0;
        if($session_visa){
            $session_visa = $this->resourceConfigureInfo($session_visa,'/source/getVisa','visa_id','visa_name');
            $this->assign('count_session_visa',count($session_visa));
            $session_visa_total = $this->totalSum($session_visa);
//            $session_visa = Arrays::group($session_visa,'supplier_id');
//            $this->assign('session_visa',$session_visa);
//            var_dump($session_visa);exit;

            foreach($session_visa as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 6;
                $ar['resource_id'] = $v['visa_id']; //资源ID
                $ar['resource_name'] = $v['visa_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }

        }
        //景点
        $session_scenic_spot_total = 0;
        if($session_scenic_spot){
            $session_scenic_spot = $this->resourceConfigureInfo($session_scenic_spot,'/source/getScenicSpot','scenic_spot_id','scenic_spot_name');
            $this->assign('count_session_scenic_spot',count($session_scenic_spot));
            $session_scenic_spot_total = $this->totalSum($session_scenic_spot);
//            $session_scenic_spot = Arrays::group($session_scenic_spot,'supplier_id');
//            $this->assign('session_scenic_spot',$session_scenic_spot);
//            var_dump($session_scenic_spot);exit;

            foreach($session_scenic_spot as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 7;
                $ar['resource_id'] = $v['scenic_spot_id']; //资源ID
                $ar['resource_name'] = $v['scenic_spot_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }

        }
        //车辆
        $session_vehicle_spot_total = 0;
        if($session_vehicle){
            $session_vehicle = $this->resourceConfigureInfo($session_vehicle,'/source/getVehicle','vehicle_id','vehicle_name');
            $this->assign('count_session_vehicle',count($session_vehicle));
            $session_vehicle_spot_total = $this->totalSum($session_vehicle);
//            $session_vehicle = Arrays::group($session_vehicle,'supplier_id');
//            $this->assign('session_vehicle',$session_vehicle);
//            var_dump($session_vehicle);exit;

            foreach($session_vehicle as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 8;
                $ar['resource_id'] = $v['vehicle_id']; //资源ID
                $ar['resource_name'] = $v['vehicle_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }


        }
        //导游
        $session_tourGuide_total = 0;
        if($session_tourGuide){
//            var_dump($session_tourGuide);exit;
            $session_tourGuide = $this->resourceConfigureInfo($session_tourGuide,'/source/getTourGuide','tour_guide_id','tour_guide_name');
            $this->assign('count_session_tourGuide',count($session_tourGuide));
            $session_tourGuide_total = $this->totalSum($session_tourGuide);
//            $session_tourGuide = Arrays::group($session_tourGuide,'supplier_id');
//            $this->assign('session_tourGuide',$session_tourGuide);
//            var_dump($session_tourGuide);exit;

            foreach($session_tourGuide as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 9;
                $ar['resource_id'] = $v['tour_guide_id']; //资源ID
                $ar['resource_name'] = $v['tour_guide_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }
        }



        //单项资源
        $session_singleSource_total = 0;
        if($session_singleSource){
//            var_dump($session_singleSource);exit;
            $session_singleSource = $this->resourceConfigureInfo($session_singleSource,'/source/getSingleSource','single_source_id','single_source_name');
            $this->assign('count_session_singleSource',count($session_singleSource));
            $session_singleSource_total = $this->totalSum($session_singleSource);
//            $session_singleSource = Arrays::group($session_singleSource,'supplier_id');
//            $this->assign('session_singleSource',$session_singleSource);
//            var_dump($session_singleSource);exit;

            foreach($session_singleSource as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 10;
                $ar['resource_id'] = $v['single_source_id']; //资源ID
                $ar['resource_name'] = $v['single_source_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }

        }

        //自费项目
        $OwnExpense_resource = [];
        $session_singleSource_OwnExpense_total = 0;
        if($session_Optional){
//            var_dump($session_Optional);exit;
            $session_Optional = $this->resourceConfigureInfo($session_Optional,'/source/getOwnExpense','own_expense_id','own_expense_name');
            $this->assign('count_session_Optional',count($session_Optional));
            $session_singleSource_OwnExpense_total = $this->totalSum($session_Optional);
//            $session_Optional = Arrays::group($session_Optional,'supplier_id');
//            $this->assign('session_Optional',$session_Optional);
//            var_dump($session_Optional);exit;

            foreach($session_Optional as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 11;
                $ar['resource_id'] = $v['own_expense_id']; //资源ID
                $ar['resource_name'] = $v['own_expense_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $OwnExpense_resource[] = $ar;
                unset($ar);
            }
        }


        $allTotal = $session_hotel_total+$session_dining_total+$session_flight_total+$session_cruise_total+$session_visa_total+$session_scenic_spot_total+$session_vehicle_spot_total+$session_tourGuide_total+$session_singleSource_total;
        $this->assign('allTotal',$allTotal); //总计
        $this->assign('session_singleSource_OwnExpense_total',$session_singleSource_OwnExpense_total); //自费总计
        $this->assign('monetarySum',$monetarySum);

        //货币
        $Currency =  $this->callSoaErp('post','/system/getCurrency',['status'=>1]);
        $this->assign('CurrencyList',$Currency['data']);
        $Currency = Arrays::group($Currency['data'],'currency_id');
        $this->assign('CurrencyAr',$Currency);
//        var_dump($Currency);exit;
//        var_dump($resource);exit;

        //一口价
        if($settlement_type==1){
            if($plan_id){
                //查询是否有审核的数据
                $where['pk_id'] = input('get.plan_id');
                $where['approval_type_id'] = ['in','12,13'];
                $where['status'] = 1;
                $selExamineAndApprove = $this->callSoaErp('post','/examine_and_approve/selExamineAndApprove/',$where);
//                echo '<pre>';print_r($selExamineAndApprove);exit;
                if(!empty($selExamineAndApprove['data'][0])){
                    $aryy = json_decode($selExamineAndApprove['data'][0]['examination_and_approval_content'],true);
                    $add_once_price = array_merge($aryy['add_once_price'],$aryy['edit_once_price']);
                    if($add_once_price){
                        $TeamProductPrice = Arrays::group($add_once_price,'company_id');
                        $this->assign('TeamProductPrice',$TeamProductPrice);
                    }
                }else{
                    $where['team_product_settlement_type'] = 1;
                    $where['team_product_id'] = $plan_id;
                    $where['status'] = 1;
                    $TeamProductPrice = $this->callSoaErp('post','/product/getTeamProductPrice',$where);
                    if($TeamProductPrice['data']){
                        $TeamProductPrice = Arrays::group($TeamProductPrice['data'],'company_id');
                        $this->assign('TeamProductPrice',$TeamProductPrice);
//                    var_dump($TeamProductPrice);exit;
                    }
                }


            }

            if(empty($use_company_id)){
                $where['status'] = 1;
                $Company = $this->callSoaErp('post','/system/getCompany',$where);
                if(!empty($Company['data'])){
                    foreach($Company['data'] as $vl){
                        $ar['val'] = $vl['company_id'];
                        $ar['name'] = $vl['company_name'];
                        $ar['currency_id'] = $vl['currency_id'];
                        $use_company_id[] = $ar;
                    }
                }
                unset($where);
            }
            $this->assign('use_company_id',$use_company_id);
//            var_dump($use_company_id);

            //获取汇率
            $company_currency_id = session('user')['company_currency_id'];
            $d = date('Y-m').'-01';
            $w2['proportion_time'] = date('Ymd',strtotime("{$d} -1 day"));
            $getProportionOneToOne = $this->callSoaErp('post','/system/getProportionOneToOne',$w2)['data'];


//            echo '<pre>';print_r($getProportionOneToOne);exit;

            $yiKouJia = 0;
            foreach($resource as $v){
                $yiKouJia += ($v['quantity']*$v['unit_price'])*$getProportionOneToOne[$v['currency'].'-'.$company_currency_id];
            }
            $this->assign('yiKouJia',$yiKouJia);

//            var_dump(session('user'));exit;

        }


        //内部结算价格
        $zt = [];
        if($settlement_type==2){
            $resource = Arrays::sort($resource,'the_day','asc');
            $ztt = Arrays::group($resource,'the_day');
            foreach($ztt as $k=>$v){
                $zt[$k]['count'] = count($v);
                $ztt2 = Arrays::group($v,'resource_type');
                foreach($ztt2 as $k2=>$v2){
                    $zt[$k]['data'][$k2]['count'] = count($v2);
                    $ztt3 = Arrays::group($v2,'supplier_id');
                    foreach($ztt3 as $k3=>$v3){
                        $zt[$k]['data'][$k2]['data'][$k3]['count'] = count($v3);
                        $zt[$k]['data'][$k2]['data'][$k3]['data'] = $v3;
                    }
                }
            }

            $this->assign('zt',$zt);

//            echo '<pre>';
//            print_r($zt);exit;
        }

        //自费项目
        $zt_OwnExpense_resource = [];
        if($OwnExpense_resource){
            $OwnExpense_resource = Arrays::sort($OwnExpense_resource,'the_day','asc');
            $ztt = Arrays::group($OwnExpense_resource,'the_day');
            foreach($ztt as $k=>$v){
                $zt_OwnExpense_resource[$k]['count'] = count($v);
                $ztt2 = Arrays::group($v,'resource_type');
                foreach($ztt2 as $k2=>$v2){
                    $zt_OwnExpense_resource[$k]['data'][$k2]['count'] = count($v2);
                    $ztt3 = Arrays::group($v2,'supplier_id');
                    foreach($ztt3 as $k3=>$v3){
                        $zt_OwnExpense_resource[$k]['data'][$k2]['data'][$k3]['count'] = count($v3);
                        $zt_OwnExpense_resource[$k]['data'][$k2]['data'][$k3]['data'] = $v3;
                    }
                }
            }
            $this->assign('zt_OwnExpense_resource',$zt_OwnExpense_resource);
//            echo '<pre>';
//            print_r($zt_OwnExpense_resource);exit;
        }


        //供应商类型
        $getSupplierType = $this->callSoaErp('post','/system/getSupplierType',['status'=>1]);
        $SupplierType = Arrays::group($getSupplierType['data'],'supplier_type_id');
        $this->assign('SupplierType',$SupplierType);

        return $this->fetch('nn_plan_product_quotation_info');
    }

    /**
     * 产品报价
     * Hugh 19-02-27
     */
    public function showProductQuotationHtml(){
        $post = Request::instance()->param();
        $settlement_type = Arrays::get($post,'settlement_type');
        $use_company_id = Arrays::get($post,'use_company_id');
        $plan_id = Arrays::get($post,'plan_id',0);
        $this->assign('settlement_type',$settlement_type);

        $session_hotel = Session::get('session_hotel');
        $session_dining = Session::get('session_dining');
        $session_flight = Session::get('session_flight');
        $session_cruise = Session::get('session_cruise');
        $session_visa = Session::get('session_visa');
        $session_scenic_spot = Session::get('session_scenic_spot');
        $session_vehicle = Session::get('session_vehicle');
        $session_tourGuide = Session::get('session_tourGuide');
        $session_singleSource = Session::get('session_singleSource');
        $session_Optional = Session::get('session_Optional');

        $resource = [];
        $monetarySum  = [];

        //酒店资源
        $session_hotel_total = 0;
        if($session_hotel){
            $session_hotel = $this->resourceConfigureInfo($session_hotel,'/source/getHotel','hotel_id','room_name');
            $this->assign('count_session_hotel',count($session_hotel));
            $session_hotel_total = $this->totalSum($session_hotel);
//            $session_hotel = Arrays::group($session_hotel,'supplier_id');
//            $this->assign('session_hotel',$session_hotel);
//            var_dump($session_hotel);exit;

            foreach($session_hotel as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 2;
                $ar['resource_id'] = $v['hotel_id']; //资源ID
                $ar['resource_name'] = $v['room_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }

        }
        //用餐
        $session_dining_total = 0;
        if($session_dining){
            $session_dining = $this->resourceConfigureInfo($session_dining,'/source/getDining','dining_id','dining_name');
            $this->assign('count_session_dining',count($session_dining));
            $session_dining_total = $this->totalSum($session_dining);
//            $session_dining = Arrays::group($session_dining,'supplier_id');
//            $this->assign('session_dining',$session_dining);
//            var_dump($session_dining);exit;

            foreach($session_dining as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 3;
                $ar['resource_id'] = $v['dining_id']; //资源ID
                $ar['resource_name'] = $v['dining_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }

        }
        //航班
        $session_flight_total = 0;
        if($session_flight){
            $session_flight = $this->resourceConfigureInfo($session_flight,'/source/getFlight','flight_id','flight_number');
            $this->assign('count_session_flight',count($session_flight));
            $session_flight_total = $this->totalSum($session_flight);
//            $session_flight = Arrays::group($session_flight,'supplier_id');
//            $this->assign('session_flight',$session_flight);
//            var_dump($session_flight);exit;

            foreach($session_flight as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 4;
                $ar['resource_id'] = $v['flight_id']; //资源ID
                $ar['resource_name'] = $v['flight_number']."({$v['shipping_space']})"; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }

        }
        //邮轮
        $session_cruise_total = 0;
        if($session_cruise){
            $session_cruise = $this->resourceConfigureInfo($session_cruise,'/source/getCruise','cruise_id','cruise_name');
            $this->assign('count_session_cruise',count($session_cruise));
            $session_cruise_total = $this->totalSum($session_cruise);
//            $session_cruise = Arrays::group($session_cruise,'supplier_id');
//            $this->assign('session_cruise',$session_cruise);
//            var_dump($session_cruise);exit;

            foreach($session_cruise as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 5;
                $ar['resource_id'] = $v['cruise_id']; //资源ID
                $ar['resource_name'] = $v['cruise_name']."({$v['room_name']})"; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }

        }
        //签证
        $session_visa_total = 0;
        if($session_visa){
            $session_visa = $this->resourceConfigureInfo($session_visa,'/source/getVisa','visa_id','visa_name');
            $this->assign('count_session_visa',count($session_visa));
            $session_visa_total = $this->totalSum($session_visa);
//            $session_visa = Arrays::group($session_visa,'supplier_id');
//            $this->assign('session_visa',$session_visa);
//            var_dump($session_visa);exit;

            foreach($session_visa as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 6;
                $ar['resource_id'] = $v['visa_id']; //资源ID
                $ar['resource_name'] = $v['visa_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }

        }
        //景点
        $session_scenic_spot_total = 0;
        if($session_scenic_spot){
            $session_scenic_spot = $this->resourceConfigureInfo($session_scenic_spot,'/source/getScenicSpot','scenic_spot_id','scenic_spot_name');
            $this->assign('count_session_scenic_spot',count($session_scenic_spot));
            $session_scenic_spot_total = $this->totalSum($session_scenic_spot);
//            $session_scenic_spot = Arrays::group($session_scenic_spot,'supplier_id');
//            $this->assign('session_scenic_spot',$session_scenic_spot);
//            var_dump($session_scenic_spot);exit;

            foreach($session_scenic_spot as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 7;
                $ar['resource_id'] = $v['scenic_spot_id']; //资源ID
                $ar['resource_name'] = $v['scenic_spot_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }

        }
        //车辆
        $session_vehicle_spot_total = 0;
        if($session_vehicle){
            $session_vehicle = $this->resourceConfigureInfo($session_vehicle,'/source/getVehicle','vehicle_id','vehicle_name');
            $this->assign('count_session_vehicle',count($session_vehicle));
            $session_vehicle_spot_total = $this->totalSum($session_vehicle);
//            $session_vehicle = Arrays::group($session_vehicle,'supplier_id');
//            $this->assign('session_vehicle',$session_vehicle);
//            var_dump($session_vehicle);exit;

            foreach($session_vehicle as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 8;
                $ar['resource_id'] = $v['vehicle_id']; //资源ID
                $ar['resource_name'] = $v['vehicle_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }


        }
        //导游
        $session_tourGuide_total = 0;
        if($session_tourGuide){
//            var_dump($session_tourGuide);exit;
            $session_tourGuide = $this->resourceConfigureInfo($session_tourGuide,'/source/getTourGuide','tour_guide_id','tour_guide_name');
            $this->assign('count_session_tourGuide',count($session_tourGuide));
            $session_tourGuide_total = $this->totalSum($session_tourGuide);
//            $session_tourGuide = Arrays::group($session_tourGuide,'supplier_id');
//            $this->assign('session_tourGuide',$session_tourGuide);
//            var_dump($session_tourGuide);exit;

            foreach($session_tourGuide as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 9;
                $ar['resource_id'] = $v['tour_guide_id']; //资源ID
                $ar['resource_name'] = $v['tour_guide_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }
        }



        //单项资源
        $session_singleSource_total = 0;
        if($session_singleSource){
//            var_dump($session_singleSource);exit;
            $session_singleSource = $this->resourceConfigureInfo($session_singleSource,'/source/getSingleSource','single_source_id','single_source_name');
            $this->assign('count_session_singleSource',count($session_singleSource));
            $session_singleSource_total = $this->totalSum($session_singleSource);
//            $session_singleSource = Arrays::group($session_singleSource,'supplier_id');
//            $this->assign('session_singleSource',$session_singleSource);
//            var_dump($session_singleSource);exit;

            foreach($session_singleSource as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 10;
                $ar['resource_id'] = $v['single_source_id']; //资源ID
                $ar['resource_name'] = $v['single_source_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                $monetarySum[$v['currency']] += $v['total']; //货币总和
                unset($ar);
            }

        }

        //自费项目
        $OwnExpense_resource = [];
        $session_singleSource_OwnExpense_total = 0;
        if($session_Optional){
//            var_dump($session_Optional);exit;
            $session_Optional = $this->resourceConfigureInfo($session_Optional,'/source/getOwnExpense','own_expense_id','own_expense_name');
            $this->assign('count_session_Optional',count($session_Optional));
            $session_singleSource_OwnExpense_total = $this->totalSum($session_Optional);
//            $session_Optional = Arrays::group($session_Optional,'supplier_id');
//            $this->assign('session_Optional',$session_Optional);
//            var_dump($session_Optional);exit;

            foreach($session_Optional as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 11;
                $ar['resource_id'] = $v['own_expense_id']; //资源ID
                $ar['resource_name'] = $v['own_expense_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $OwnExpense_resource[] = $ar;
                unset($ar);
            }
        }


        $allTotal = $session_hotel_total+$session_dining_total+$session_flight_total+$session_cruise_total+$session_visa_total+$session_scenic_spot_total+$session_vehicle_spot_total+$session_tourGuide_total+$session_singleSource_total;
        $this->assign('allTotal',$allTotal); //总计
        $this->assign('session_singleSource_OwnExpense_total',$session_singleSource_OwnExpense_total); //自费总计
        $this->assign('monetarySum',$monetarySum);

        //货币
        $Currency =  $this->callSoaErp('post','/system/getCurrency',['status'=>1]);
        $this->assign('CurrencyList',$Currency['data']);
        $Currency = Arrays::group($Currency['data'],'currency_id');
        $this->assign('CurrencyAr',$Currency);
//        var_dump($Currency);exit;
//        var_dump($resource);exit;

        //一口价
        if($settlement_type==1){
            if($plan_id){
                $where['team_product_settlement_type'] = 1;
                $where['team_product_id'] = $plan_id;
                $where['status'] = 1;
                $TeamProductPrice = $this->callSoaErp('post','/product/getTeamProductPrice',$where);
                if($TeamProductPrice['data']){
                    $TeamProductPrice = Arrays::group($TeamProductPrice['data'],'company_id');
                    $this->assign('TeamProductPrice',$TeamProductPrice);
//                    var_dump($TeamProductPrice);exit;
                }
            }

            if(empty($use_company_id)){
                $where['status'] = 1;
                $Company = $this->callSoaErp('post','/system/getCompany',$where);
                if(!empty($Company['data'])){
                    foreach($Company['data'] as $vl){
                        $ar['val'] = $vl['company_id'];
                        $ar['name'] = $vl['company_name'];
                        $ar['currency_id'] = $vl['currency_id'];
                        $use_company_id[] = $ar;
                    }
                }
                unset($where);
            }
            $this->assign('use_company_id',$use_company_id);
//            var_dump($use_company_id);

            //获取汇率
            $company_currency_id = session('user')['company_currency_id'];
            $d = date('Y-m').'-01';
            $w2['proportion_time'] = date('Ymd',strtotime("{$d} -1 day"));
            $getProportionOneToOne = $this->callSoaErp('post','/system/getProportionOneToOne',$w2)['data'];


//            echo '<pre>';print_r($getProportionOneToOne);exit;

            $yiKouJia = 0;
            foreach($resource as $v){
                $yiKouJia += ($v['quantity']*$v['unit_price'])*$getProportionOneToOne[$v['currency'].'-'.$company_currency_id];
            }
            $this->assign('yiKouJia',$yiKouJia);

//            var_dump(session('user'));exit;

        }


        //内部结算价格
        $zt = [];
        if($settlement_type==2){
            $resource = Arrays::sort($resource,'the_day','asc');
            $ztt = Arrays::group($resource,'the_day');
            foreach($ztt as $k=>$v){
                $zt[$k]['count'] = count($v);
                $ztt2 = Arrays::group($v,'resource_type');
                foreach($ztt2 as $k2=>$v2){
                    $zt[$k]['data'][$k2]['count'] = count($v2);
                    $ztt3 = Arrays::group($v2,'supplier_id');
                    foreach($ztt3 as $k3=>$v3){
                        $zt[$k]['data'][$k2]['data'][$k3]['count'] = count($v3);
                        $zt[$k]['data'][$k2]['data'][$k3]['data'] = $v3;
                    }
                }
            }

            $this->assign('zt',$zt);

//            echo '<pre>';
//            print_r($zt);exit;
        }

        //自费项目
        $zt_OwnExpense_resource = [];
        if($OwnExpense_resource){
            $OwnExpense_resource = Arrays::sort($OwnExpense_resource,'the_day','asc');
            $ztt = Arrays::group($OwnExpense_resource,'the_day');
            foreach($ztt as $k=>$v){
                $zt_OwnExpense_resource[$k]['count'] = count($v);
                $ztt2 = Arrays::group($v,'resource_type');
                foreach($ztt2 as $k2=>$v2){
                    $zt_OwnExpense_resource[$k]['data'][$k2]['count'] = count($v2);
                    $ztt3 = Arrays::group($v2,'supplier_id');
                    foreach($ztt3 as $k3=>$v3){
                        $zt_OwnExpense_resource[$k]['data'][$k2]['data'][$k3]['count'] = count($v3);
                        $zt_OwnExpense_resource[$k]['data'][$k2]['data'][$k3]['data'] = $v3;
                    }
                }
            }
            $this->assign('zt_OwnExpense_resource',$zt_OwnExpense_resource);
//            echo '<pre>';
//            print_r($zt_OwnExpense_resource);exit;
        }


        //供应商类型
        $getSupplierType = $this->callSoaErp('post','/system/getSupplierType',['status'=>1]);
        $SupplierType = Arrays::group($getSupplierType['data'],'supplier_type_id');
        $this->assign('SupplierType',$SupplierType);

        return $this->fetch('nn_plan_product_quotation');
    }


    /**
     * 产品报价 备份 19-02-27
     * Hugh 18-12-19
     */
    public function showProductQuotationHtml3(){
        $post = Request::instance()->param();
        $settlement_type = Arrays::get($post,'settlement_type');
        $use_company_id = Arrays::get($post,'use_company_id');
        $plan_id = Arrays::get($post,'plan_id',0);
        $this->assign('settlement_type',$settlement_type);

        $session_hotel = Session::get('session_hotel');
        $session_dining = Session::get('session_dining');
        $session_flight = Session::get('session_flight');
        $session_cruise = Session::get('session_cruise');
        $session_visa = Session::get('session_visa');
        $session_scenic_spot = Session::get('session_scenic_spot');
        $session_vehicle = Session::get('session_vehicle');
        $session_tourGuide = Session::get('session_tourGuide');
        $session_singleSource = Session::get('session_singleSource');
        $session_Optional = Session::get('session_Optional');

        $resource = [];
        //酒店资源
        $session_hotel_total = 0;
        if($session_hotel){
            $session_hotel = $this->resourceConfigureInfo($session_hotel,'/source/getHotel','hotel_id','room_name');
            $this->assign('count_session_hotel',count($session_hotel));
            $session_hotel_total = $this->totalSum($session_hotel);
//            $session_hotel = Arrays::group($session_hotel,'supplier_id');
//            $this->assign('session_hotel',$session_hotel);
//            var_dump($session_hotel);exit;

            foreach($session_hotel as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 2;
                $ar['resource_id'] = $v['hotel_id']; //资源ID
                $ar['resource_name'] = $v['room_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                unset($ar);
            }

        }
        //用餐
        $session_dining_total = 0;
        if($session_dining){
            $session_dining = $this->resourceConfigureInfo($session_dining,'/source/getDining','dining_id','dining_name');
            $this->assign('count_session_dining',count($session_dining));
            $session_dining_total = $this->totalSum($session_dining);
//            $session_dining = Arrays::group($session_dining,'supplier_id');
//            $this->assign('session_dining',$session_dining);
//            var_dump($session_dining);exit;

            foreach($session_dining as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 3;
                $ar['resource_id'] = $v['dining_id']; //资源ID
                $ar['resource_name'] = $v['dining_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                unset($ar);
            }

        }
        //航班
        $session_flight_total = 0;
        if($session_flight){
            $session_flight = $this->resourceConfigureInfo($session_flight,'/source/getFlight','flight_id','flight_number');
            $this->assign('count_session_flight',count($session_flight));
            $session_flight_total = $this->totalSum($session_flight);
//            $session_flight = Arrays::group($session_flight,'supplier_id');
//            $this->assign('session_flight',$session_flight);
//            var_dump($session_flight);exit;

            foreach($session_flight as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 4;
                $ar['resource_id'] = $v['flight_id']; //资源ID
                $ar['resource_name'] = $v['flight_number']."({$v['shipping_space']})"; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                unset($ar);
            }

        }
        //邮轮
        $session_cruise_total = 0;
        if($session_cruise){
            $session_cruise = $this->resourceConfigureInfo($session_cruise,'/source/getCruise','cruise_id','cruise_name');
            $this->assign('count_session_cruise',count($session_cruise));
            $session_cruise_total = $this->totalSum($session_cruise);
//            $session_cruise = Arrays::group($session_cruise,'supplier_id');
//            $this->assign('session_cruise',$session_cruise);
//            var_dump($session_cruise);exit;

            foreach($session_cruise as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 5;
                $ar['resource_id'] = $v['cruise_id']; //资源ID
                $ar['resource_name'] = $v['cruise_name']."({$v['room_name']})"; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                unset($ar);
            }

        }
        //签证
        $session_visa_total = 0;
        if($session_visa){
            $session_visa = $this->resourceConfigureInfo($session_visa,'/source/getVisa','visa_id','visa_name');
            $this->assign('count_session_visa',count($session_visa));
            $session_visa_total = $this->totalSum($session_visa);
//            $session_visa = Arrays::group($session_visa,'supplier_id');
//            $this->assign('session_visa',$session_visa);
//            var_dump($session_visa);exit;

            foreach($session_visa as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 6;
                $ar['resource_id'] = $v['visa_id']; //资源ID
                $ar['resource_name'] = $v['visa_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                unset($ar);
            }

        }
        //景点
        $session_scenic_spot_total = 0;
        if($session_scenic_spot){
            $session_scenic_spot = $this->resourceConfigureInfo($session_scenic_spot,'/source/getScenicSpot','scenic_spot_id','scenic_spot_name');
            $this->assign('count_session_scenic_spot',count($session_scenic_spot));
            $session_scenic_spot_total = $this->totalSum($session_scenic_spot);
//            $session_scenic_spot = Arrays::group($session_scenic_spot,'supplier_id');
//            $this->assign('session_scenic_spot',$session_scenic_spot);
//            var_dump($session_scenic_spot);exit;

            foreach($session_scenic_spot as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 7;
                $ar['resource_id'] = $v['scenic_spot_id']; //资源ID
                $ar['resource_name'] = $v['scenic_spot_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                unset($ar);
            }

        }
        //车辆
        $session_vehicle_spot_total = 0;
        if($session_vehicle){
            $session_vehicle = $this->resourceConfigureInfo($session_vehicle,'/source/getVehicle','vehicle_id','vehicle_name');
            $this->assign('count_session_vehicle',count($session_vehicle));
            $session_vehicle_spot_total = $this->totalSum($session_vehicle);
//            $session_vehicle = Arrays::group($session_vehicle,'supplier_id');
//            $this->assign('session_vehicle',$session_vehicle);
//            var_dump($session_vehicle);exit;

            foreach($session_vehicle as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 8;
                $ar['resource_id'] = $v['vehicle_id']; //资源ID
                $ar['resource_name'] = $v['vehicle_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                unset($ar);
            }


        }
        //导游
        $session_tourGuide_total = 0;
        if($session_tourGuide){
//            var_dump($session_tourGuide);exit;
            $session_tourGuide = $this->resourceConfigureInfo($session_tourGuide,'/source/getTourGuide','tour_guide_id','tour_guide_name');
            $this->assign('count_session_tourGuide',count($session_tourGuide));
            $session_tourGuide_total = $this->totalSum($session_tourGuide);
//            $session_tourGuide = Arrays::group($session_tourGuide,'supplier_id');
//            $this->assign('session_tourGuide',$session_tourGuide);
//            var_dump($session_tourGuide);exit;

            foreach($session_tourGuide as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 9;
                $ar['resource_id'] = $v['tour_guide_id']; //资源ID
                $ar['resource_name'] = $v['tour_guide_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                unset($ar);
            }


        }



        //单项资源
        $session_singleSource_total = 0;
        if($session_singleSource){
//            var_dump($session_singleSource);exit;
            $session_singleSource = $this->resourceConfigureInfo($session_singleSource,'/source/getSingleSource','single_source_id','single_source_name');
            $this->assign('count_session_singleSource',count($session_singleSource));
            $session_singleSource_total = $this->totalSum($session_singleSource);
//            $session_singleSource = Arrays::group($session_singleSource,'supplier_id');
//            $this->assign('session_singleSource',$session_singleSource);
//            var_dump($session_singleSource);exit;

            foreach($session_singleSource as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 10;
                $ar['resource_id'] = $v['single_source_id']; //资源ID
                $ar['resource_name'] = $v['single_source_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $resource[] = $ar;
                unset($ar);
            }

        }

        //自费项目
        $OwnExpense_resource = [];
        $session_singleSource_OwnExpense_total = 0;
        if($session_Optional){
//            var_dump($session_Optional);exit;
            $session_Optional = $this->resourceConfigureInfo($session_Optional,'/source/getOwnExpense','own_expense_id','own_expense_name');
            $this->assign('count_session_Optional',count($session_Optional));
            $session_singleSource_OwnExpense_total = $this->totalSum($session_Optional);
//            $session_Optional = Arrays::group($session_Optional,'supplier_id');
//            $this->assign('session_Optional',$session_Optional);
//            var_dump($session_Optional);exit;

            foreach($session_Optional as $v){
                $ar['supplier_id'] = $v['supplier_id']; //供应商
                $ar['supplier_name'] = $v['supplier_name']; //供应商名称
                $ar['resource_type'] = 11;
                $ar['resource_id'] = $v['own_expense_id']; //资源ID
                $ar['resource_name'] = $v['own_expense_name']; //资源名称
                $ar['the_day'] = $v['the_day']; //第几天
                $ar['unit_price'] = $v['unit_price']; //内部结算价
                $ar['cost_price'] = $v['cost_price']; //成本价
                $ar['symbol'] = $v['symbol']; //成本内部结算件价货币符号
                $ar['quantity'] = $v['quantity']; //数量
                $ar['currency'] = $v['currency']; //报价货币
                $ar['total'] = $v['total']; // 报价
                $OwnExpense_resource[] = $ar;
                unset($ar);
            }
        }


        $allTotal = $session_hotel_total+$session_dining_total+$session_flight_total+$session_cruise_total+$session_visa_total+$session_scenic_spot_total+$session_vehicle_spot_total+$session_tourGuide_total+$session_singleSource_total;
        $this->assign('allTotal',$allTotal); //总计
        $this->assign('session_singleSource_OwnExpense_total',$session_singleSource_OwnExpense_total); //自费总计

        //货币
        $Currency =  $this->callSoaErp('post','/system/getCurrency',['status'=>1]);
        $this->assign('CurrencyList',$Currency['data']);
        $Currency = Arrays::group($Currency['data'],'currency_id');
        $this->assign('CurrencyAr',$Currency);
//        var_dump($Currency);exit;
//        var_dump($resource);exit;

        //一口价
        if($settlement_type==1){
            if($plan_id){
                $where['team_product_settlement_type'] = 1;
                $where['team_product_id'] = $plan_id;
                $where['status'] = 1;
                $TeamProductPrice = $this->callSoaErp('post','/product/getTeamProductPrice',$where);
                if($TeamProductPrice['data']){
                    $TeamProductPrice = Arrays::group($TeamProductPrice['data'],'company_id');
                    $this->assign('TeamProductPrice',$TeamProductPrice);
//                    var_dump($TeamProductPrice);exit;
                }
            }

            if(empty($use_company_id)){
                $where['status'] = 1;
                $Company = $this->callSoaErp('post','/system/getCompany',$where);
                if(!empty($Company['data'])){
                    foreach($Company['data'] as $vl){
                        $ar['val'] = $vl['company_id'];
                        $ar['name'] = $vl['company_name'];
                        $use_company_id[] = $ar;
                    }
                }
                unset($where);
            }
            $this->assign('use_company_id',$use_company_id);
//            var_dump($use_company_id);

            $yiKouJia = 0;
            foreach($resource as $v){
                $yiKouJia += ($v['quantity']*$v['unit_price']);
            }
            $this->assign('yiKouJia',$yiKouJia);

        }


        //内部结算价格
        $zt = [];
        if($settlement_type==2){
            $resource = Arrays::sort($resource,'the_day','asc');
            $ztt = Arrays::group($resource,'the_day');
            foreach($ztt as $k=>$v){
                $zt[$k]['count'] = count($v);
                $ztt2 = Arrays::group($v,'resource_type');
                foreach($ztt2 as $k2=>$v2){
                    $zt[$k]['data'][$k2]['count'] = count($v2);
                    $ztt3 = Arrays::group($v2,'supplier_id');
                    foreach($ztt3 as $k3=>$v3){
                        $zt[$k]['data'][$k2]['data'][$k3]['count'] = count($v3);
                        $zt[$k]['data'][$k2]['data'][$k3]['data'] = $v3;
                    }
                }
            }

            $this->assign('zt',$zt);

//            echo '<pre>';
//            print_r($zt);exit;
        }

        //自费项目
        $zt_OwnExpense_resource = [];
        if($OwnExpense_resource){
            $OwnExpense_resource = Arrays::sort($OwnExpense_resource,'the_day','asc');
            $ztt = Arrays::group($OwnExpense_resource,'the_day');
            foreach($ztt as $k=>$v){
                $zt_OwnExpense_resource[$k]['count'] = count($v);
                $ztt2 = Arrays::group($v,'resource_type');
                foreach($ztt2 as $k2=>$v2){
                    $zt_OwnExpense_resource[$k]['data'][$k2]['count'] = count($v2);
                    $ztt3 = Arrays::group($v2,'supplier_id');
                    foreach($ztt3 as $k3=>$v3){
                        $zt_OwnExpense_resource[$k]['data'][$k2]['data'][$k3]['count'] = count($v3);
                        $zt_OwnExpense_resource[$k]['data'][$k2]['data'][$k3]['data'] = $v3;
                    }
                }
            }
            $this->assign('zt_OwnExpense_resource',$zt_OwnExpense_resource);
//            echo '<pre>';
//            print_r($zt_OwnExpense_resource);exit;
        }


        //供应商类型
        $getSupplierType = $this->callSoaErp('post','/system/getSupplierType',['status'=>1]);
        $SupplierType = Arrays::group($getSupplierType['data'],'supplier_type_id');
        $this->assign('SupplierType',$SupplierType);

        return $this->fetch('n_plan_product_quotation');
    }


    /**
     * 产品报价
     * Hugh 19-09-04
     */
    public function showProductQuotationHtml2(){
        $post = Request::instance()->param();
        $settlement_type = Arrays::get($post,'settlement_type');
        $use_company_id = Arrays::get($post,'use_company_id');
        $plan_id = Arrays::get($post,'plan_id',0);
        $this->assign('settlement_type',$settlement_type);
        //一口价
        if($settlement_type==1){
            if($plan_id){
                $where['team_product_settlement_type'] = 1;
                $where['team_product_id'] = $plan_id;
                $where['status'] = 1;
                $TeamProductPrice = $this->callSoaErp('post','/product/getTeamProductPrice',$where);
                if($TeamProductPrice['data']){
                    $TeamProductPrice = Arrays::group($TeamProductPrice['data'],'company_id');
                    $this->assign('TeamProductPrice',$TeamProductPrice);
//                    var_dump($TeamProductPrice);exit;
                }
            }

            if(empty($use_company_id)){
                $where['status'] = 1;
                $Company = $this->callSoaErp('post','/system/getCompany',$where);
                if(!empty($Company['data'])){
                    foreach($Company['data'] as $vl){
                        $ar['val'] = $vl['company_id'];
                        $ar['name'] = $vl['company_name'];
                        $use_company_id[] = $ar;
                    }
                }
                unset($where);
            }
            $this->assign('use_company_id',$use_company_id);
//            var_dump($use_company_id);
        }


        $session_hotel = Session::get('session_hotel');
        $session_dining = Session::get('session_dining');
        $session_flight = Session::get('session_flight');
        $session_cruise = Session::get('session_cruise');
        $session_visa = Session::get('session_visa');
        $session_scenic_spot = Session::get('session_scenic_spot');
        $session_vehicle = Session::get('session_vehicle');
        $session_tourGuide = Session::get('session_tourGuide');
        $session_singleSource = Session::get('session_singleSource');
        $session_Optional = Session::get('session_Optional');

        //酒店资源
        $session_hotel_total = 0;
        if($session_hotel){
            $session_hotel = $this->resourceConfigureInfo($session_hotel,'/source/getHotel','hotel_id','room_name');
            $this->assign('count_session_hotel',count($session_hotel));
            $session_hotel_total = $this->totalSum($session_hotel);
            $session_hotel = Arrays::group($session_hotel,'supplier_id');
            $this->assign('session_hotel',$session_hotel);
//            var_dump($session_hotel);exit;
        }
        //用餐
        $session_dining_total = 0;
        if($session_dining){
            $session_dining = $this->resourceConfigureInfo($session_dining,'/source/getDining','dining_id','dining_name');
            $this->assign('count_session_dining',count($session_dining));
            $session_dining_total = $this->totalSum($session_dining);
            $session_dining = Arrays::group($session_dining,'supplier_id');
            $this->assign('session_dining',$session_dining);
//            var_dump($session_dining);exit;
        }
        //航班
        $session_flight_total = 0;
        if($session_flight){
            $session_flight = $this->resourceConfigureInfo($session_flight,'/source/getFlight','flight_id','flight_number');
            $this->assign('count_session_flight',count($session_flight));
            $session_flight_total = $this->totalSum($session_flight);
            $session_flight = Arrays::group($session_flight,'supplier_id');
            $this->assign('session_flight',$session_flight);
//            var_dump($session_flight);exit;
        }
        //邮轮
        $session_cruise_total = 0;
        if($session_cruise){
            $session_cruise = $this->resourceConfigureInfo($session_cruise,'/source/getCruise','cruise_id','cruise_name');
            $this->assign('count_session_cruise',count($session_cruise));
            $session_cruise_total = $this->totalSum($session_cruise);
            $session_cruise = Arrays::group($session_cruise,'supplier_id');
            $this->assign('session_cruise',$session_cruise);
//            var_dump($session_cruise);exit;
        }
        //签证
        $session_visa_total = 0;
        if($session_visa){
            $session_visa = $this->resourceConfigureInfo($session_visa,'/source/getVisa','visa_id','visa_name');
            $this->assign('count_session_visa',count($session_visa));
            $session_visa_total = $this->totalSum($session_visa);
            $session_visa = Arrays::group($session_visa,'supplier_id');
            $this->assign('session_visa',$session_visa);
//            var_dump($session_visa);exit;
        }
        //景点
        $session_scenic_spot_total = 0;
        if($session_scenic_spot){
            $session_scenic_spot = $this->resourceConfigureInfo($session_scenic_spot,'/source/getScenicSpot','scenic_spot_id','scenic_spot_name');
            $this->assign('count_session_scenic_spot',count($session_scenic_spot));
            $session_scenic_spot_total = $this->totalSum($session_scenic_spot);
            $session_scenic_spot = Arrays::group($session_scenic_spot,'supplier_id');
            $this->assign('session_scenic_spot',$session_scenic_spot);
//            var_dump($session_scenic_spot);exit;
        }
        //车辆
        $session_vehicle_spot_total = 0;
        if($session_vehicle){
            $session_vehicle = $this->resourceConfigureInfo($session_vehicle,'/source/getVehicle','vehicle_id','vehicle_name');
            $this->assign('count_session_vehicle',count($session_vehicle));
            $session_vehicle_spot_total = $this->totalSum($session_vehicle);
            $session_vehicle = Arrays::group($session_vehicle,'supplier_id');
            $this->assign('session_vehicle',$session_vehicle);
//            var_dump($session_vehicle);exit;
        }
        //导游
        $session_tourGuide_total = 0;
        if($session_tourGuide){
//            var_dump($session_tourGuide);exit;
            $session_tourGuide = $this->resourceConfigureInfo($session_tourGuide,'/source/getTourGuide','tour_guide_id','tour_guide_name');
            $this->assign('count_session_tourGuide',count($session_tourGuide));
            $session_tourGuide_total = $this->totalSum($session_tourGuide);
            $session_tourGuide = Arrays::group($session_tourGuide,'supplier_id');
            $this->assign('session_tourGuide',$session_tourGuide);
//            var_dump($session_tourGuide);exit;
        }



        //单项资源
        $session_singleSource_total = 0;
        if($session_singleSource){
//            var_dump($session_singleSource);exit;
            $session_singleSource = $this->resourceConfigureInfo($session_singleSource,'/source/getSingleSource','single_source_id','single_source_name');
            $this->assign('count_session_singleSource',count($session_singleSource));
            $session_singleSource_total = $this->totalSum($session_singleSource);
            $session_singleSource = Arrays::group($session_singleSource,'supplier_id');
            $this->assign('session_singleSource',$session_singleSource);
//            var_dump($session_singleSource);exit;
        }

        //自费项目
        $session_singleSource_OwnExpense_total = 0;
        if($session_Optional){
//            var_dump($session_Optional);exit;
            $session_Optional = $this->resourceConfigureInfo($session_Optional,'/source/getOwnExpense','own_expense_id','own_expense_name');
            $this->assign('count_session_Optional',count($session_Optional));
            $session_singleSource_OwnExpense_total = $this->totalSum($session_Optional);
            $session_Optional = Arrays::group($session_Optional,'supplier_id');
            $this->assign('session_Optional',$session_Optional);
//            var_dump($session_Optional);exit;
        }


        $allTotal = $session_hotel_total+$session_dining_total+$session_flight_total+$session_cruise_total+$session_visa_total+$session_scenic_spot_total+$session_vehicle_spot_total+$session_tourGuide_total+$session_singleSource_total;
        $this->assign('allTotal',$allTotal); //总计
        $this->assign('session_singleSource_OwnExpense_total',$session_singleSource_OwnExpense_total); //自费总计

        //货币
        $Currency =  $this->callSoaErp('post','/system/getCurrency',['status'=>1]);
        $this->assign('CurrencyList',$Currency['data']);
        $Currency = Arrays::group($Currency['data'],'currency_id');
        $this->assign('CurrencyAr',$Currency);
//        var_dump($Currency);exit;

        return $this->fetch('plan_product_quotation');
    }


    /**
     * 总价求和
     * Hugh 19-09-05
     */
    public function totalSum($ar){
        $total = 0;
        foreach($ar as $vl){
            $total+= $vl['total'];
        }
        return $total;
    }


    /***
     * 资源配置信息
     * Hugh 19-09-05
     * $ar 数组
     * $url 调用的方法
     * $s1 取主键ID
     * $s2 取名称字段
     */
    public function resourceConfigureInfo($ar,$url,$s1,$s2){
        foreach($ar as $ky=>$vl){
            //代理or供应商
            $data['supplier_id'] = $ar[$ky]['supplier_id'];
            $Supplier = $this->callSoaErp('post','/source/getSupplier',$data);
            $ar[$ky]['supplier_name'] = $Supplier['data'][0]['supplier_name'];
            unset($data);
            if($vl['agent_id']){
                $data['supplier_id'] = $ar[$ky]['agent_id'];
                $Supplier = $this->callSoaErp('post','/source/getSupplier',$data);
                $ar[$ky]['agent_name'] = $Supplier['data'][0]['supplier_name'];
                $ar[$ky]['supplier_id'] = $ar[$ky]['supplier_id'].'_'.$ar[$ky]['agent_id'];
                unset($data);
            }
            //酒店资源名称
            $data[$s1] = $ar[$ky][$s2];
            $list = $this->callSoaErp('post',$url,$data);
            $ar[$ky][$s2] = $list['data'][0][$s2];
            $ar[$ky][$s1] = $list['data'][0][$s1];
            unset($data);
        }
        return $ar;
    }





    /**
     * 分公司订单-新增页面
     */
    public function showCompanyOrderBaseAdd(){

        //读取所有的获取渠道
        $data=[
        	'status'=>1,
        	'company_id'=>session('user')['company_id']	
        ];
        $distri_butor = $this->callSoaErp('post','/btob/getDistributor',$data);

        $this->assign('distri_butor',$distri_butor['data']);



        //读取基础信息
        $company_order_number = input("company_order_number");
        if(!empty($company_order_number)) {
            $data_edit = [
                "company_order_number" => $company_order_number
            ];
            $base_edit_result = $this->callSoaErp('post', '/branchcompany/getCompanyOrder', $data_edit);
            $base_edit_result['data'][0]['begin_time'] = date("Y-m-d",$base_edit_result['data'][0]['begin_time']);
            $base_edit_result['data'][0]['end_time'] = date("Y-m-d",$base_edit_result['data'][0]['end_time']);

            $this->assign('base_edit_result', $base_edit_result['data'][0]);


            $distributor_id = $base_edit_result['data'][0]['distributor_id'];
            $data_edit2=[
                "distributor_id"=>$distributor_id
            ];

            $distri_butor_edit = $this->callSoaErp('post','/btob/getDistributor',$data_edit2);
            $this->assign('distri_butor_edit_result', $distri_butor_edit['data'][0]);

        }

        return $this->fetch('company_order_base');
    }

    /**
     * 分公司订单-详情页面
     */
    public function showCompanyOrderBaseInfo(){
        //读取所有的获取渠道
        $data=[];
        $distri_butor = $this->callSoaErp('post','/btob/getDistributor',$data);

        $this->assign('distri_butor',$distri_butor['data']);

//        echo "<pre>";
//        var_dump($distri_butor['data']);exit;
//        echo "</pre>";

        //读取基础信息
        $company_order_number = input("company_order_number");
        if(!empty($company_order_number)) {
            $data_edit = [
                "company_order_number" => $company_order_number
            ];
            $base_edit_result = $this->callSoaErp('post', '/branchcompany/getCompanyOrder', $data_edit);
            $this->assign('base_edit_result', $base_edit_result['data'][0]);
    

            $distributor_id = $base_edit_result['data'][0]['distributor_id'];
            $data_edit2=[
                "distributor_id"=>$distributor_id
            ];

            $distri_butor_edit = $this->callSoaErp('post','/btob/getDistributor',$data_edit2);
            $this->assign('distri_butor_edit_result', $distri_butor_edit['data'][0]);

        }

        return $this->fetch('company_order_info');
    }

    /**
     * 分公司订单-渠道操作
     */
    public function CompanyInfoAjax(){
        $distributor_id = input("distributor_id");
        $data=[
            "distributor_id"=>$distributor_id
        ];

        $distri_butor = $this->callSoaErp('post','/btob/getDistributor',$data);

        return $distri_butor['data'];

    }

    /**
     * 分公司订单-新增订单操作
     */
    public function addCompanyOrderBaseAjax(){
        
        $channel_type = input("channel_type");
        if($channel_type==1){
            //分销商
            $data = Request::instance()->param();
            $data['buy_order_time'] = time();
            $data['begin_time'] = strtotime(input("begin_time"));
            $data['end_time'] = strtotime(input("end_time"));
            $data['user_id'] = session('user')['user_id'];
            $data['status'] = 1;
     
            $OrderBase = $this->callSoaErp('post','/branchcompany/addCompanyOrder',$data);

        }else if($channel_type==2){
            //直客
            $data = Request::instance()->param();
            $data['buy_order_time'] = time();
            $data['begin_time'] = strtotime(input("begin_time"));
            $data['end_time'] = strtotime(input("end_time"));
            $data['user_id'] = session('user')['user_id'];
            $data['status'] = 1;
            $OrderBase = $this->callSoaErp('post','/branchcompany/addCompanyOrder',$data);

        }
        

        return $OrderBase;
    }

    /**
     * 分公司订单-修改订单操作
     */
    public function editCompanyOrderBaseAjax(){

        $channel_type = input("channel_type");

        if($channel_type==1){
            $data = Request::instance()->param();
            $data['begin_time'] = strtotime(input("begin_time"));
            $data['end_time'] = strtotime(input("end_time"));
            $data['user_id'] = session('user')['user_id'];
            $data['status'] = 1;

            $OrderBaseEdit = $this->callSoaErp('post','/branchcompany/updateCompanyOrderByCompanyOrderNumber',$data);

        }else if($channel_type==2){

            $data = Request::instance()->param();
            $data['begin_time'] = strtotime(input("begin_time"));
            $data['end_time'] = strtotime(input("end_time"));
            $data['user_id'] = session('user')['user_id'];
            $data['status'] = 1;

            $OrderBaseEdit = $this->callSoaErp('post','/branchcompany/updateCompanyOrderByCompanyOrderNumber',$data);
        }

        return $OrderBaseEdit;
    }

    /**
     * 分公司订单-新增页面-游客信息
     */
    public function showCompanyOrderCustomerEdit(){

        $url = Request::instance()->param();
        $url_c = $url['company_order_number'];

        //获取游客信息
        $data=[
            "company_order_number"=>$url_c,
        ];
        $customer_result = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$data);

        

        //转换时间戳
        foreach ($customer_result['data'] as $key => $val) {
            $customer_result['data'][$key]['term_of_validity'] = date("Y-m-d", $customer_result['data'][$key]['term_of_validity']);
        }

        $this->assign('customer_result_data',$customer_result['data']);


        //读取语言
        $language_result = $this->callSoaErp('post','/system/getLanguage',$data);
        $this->assign('language_result_data',$language_result['data']);

        //读取国籍
        $data_c=[
            "pid"=>0
        ];
        $country_result = $this->callSoaErp('post','/system/getCountry',$data_c);
        $this->assign('country_result_data',$country_result['data']);

        $this->assign('company_order_number',$url_c);
        return $this->fetch('company_order_customer');
    }

    //获取单个用户信息
    public function getCustomerOnlyAjax(){
        $company_order_customer_id = input("company_order_customer_id");
        $company_order_number = input("company_order_number");
        $data=[
            "company_order_number"=>$company_order_number,
            "company_order_customer_id"=>$company_order_customer_id
        ];

        $customer_result = $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$data);

        //转换时间
        $customer_result['data'][0]['term_of_validity'] = date("Y-m-d",$customer_result['data'][0]['term_of_validity']);

        return $customer_result['data'][0];
    }

    /**
     * 分公司订单-新增游客操作
     */
    public function addCompanyOrderCustomerAjax(){
        $occupy_count = input("occupy_count");
        if($occupy_count!=""){

            $data = Request::instance()->param();
            $data['customer_id'] = 0;
            $data['user_id'] = session('user')['user_id'];

            $CompanyOrderCustomer = $this->callSoaErp('post','/branchcompany/addCompanyOrderCustomerOccupy',$data);

        }else{
            $customer_flight_data = json_decode(input("customer_flight"));

            $data = Request::instance()->param();
            $data['term_of_validity'] = strtotime(input("term_of_validity"));
            $data['user_id'] = session('user')['user_id'];
            $data['company_id'] = session('user')['company_id'];

            foreach($customer_flight_data as $key=>$val){
                foreach($val as $kk=>$vv){
                    $customer_flight[$key]['flight_code'] = $val[0];
                    $customer_flight[$key]['flight_begin_time'] = strtotime($val[1]);
                    $customer_flight[$key]['flight_end_time'] = strtotime($val[2]);
                    $customer_flight[$key]['flight_type'] = $val[3];
                    $customer_flight[$key]['start_place'] = $val[4];
                    $customer_flight[$key]['end_place'] = $val[5];
                }
            }

            $data['customer_flight'] = $customer_flight;

            $CompanyOrderCustomer = $this->callSoaErp('post','/branchcompany/addCompanyOrderCustomer',$data);

        }
  		
        return $CompanyOrderCustomer;
    }

    /**
     * 分公司订单-删除游客和站位
     */
    public function deleteCompanyOrderCustomerAjax(){

        $company_order_customer_id = input("company_order_customer_id");
        $company_order_number = input("company_order_number");

        $data=[
            "company_order_customer_id"=>$company_order_customer_id,
            "company_order_number"=>$company_order_number,
            "status"=>0,
            "user_id"=>session('user')['user_id']
        ];

        $customer_result = $this->callSoaErp('post','/branchcompany/updateCompanyOrderCustomerStatusByCompanyOrderCustomerId',$data);
        return $customer_result;
    }

    /**
     * 分公司订单-修改游客基础信息操作
     */
    public function editCompanyOrderCustomerBaseAjax(){
        $customer_flight_data = json_decode(input("customer_flight"));

        $data = Request::instance()->param();

        $data['term_of_validity']=strtotime(input("term_of_validity"));
        $data["user_id"]=session('user')['user_id'];
        $data['company_id']=session('user')['company_id'];
        $data["status"]=1;

        foreach($customer_flight_data as $key=>$val){
            foreach($val as $kk=>$vv){
                $customer_flight[$key]['flight_code'] = $val[0];
                $customer_flight[$key]['flight_begin_time'] = strtotime($val[1]);
                $customer_flight[$key]['flight_end_time'] = strtotime($val[2]);
                $customer_flight[$key]['flight_type'] = $val[3];
                $customer_flight[$key]['start_place'] = $val[4];
                $customer_flight[$key]['end_place'] = $val[5];
            }
        }

        $data['customer_flight'] = $customer_flight;
		
        $customer_result = $this->callSoaErp('post','/branchcompany/updateCompanyOrderCustomerByCompanyOrderCustomerId',$data);

        return $customer_result;
    }

    /**
     * 分公司订单-获取住宿和航班和游客信息
     */
    public function getCustomerInfoAjax(){
        $cc = input("cc");
        $company_order_number = input('company_order_number');
        if($cc == "hotel"){
            //读取游客酒店信息
            $customer_id = input("customer_id");
            $data = [
                "customer_id"=>$customer_id,
            	'company_order_number'=>$company_order_number	
            ];
            $customer_data = $this->callSoaErp('post','/branchcompany/getCompanyOrderAccommodation',$data);

            $content='[';
            $i=0;

            foreach($customer_data['data'] as $key=>$val){

                if($val['room_type']==1){
                    $val['room_type']="双人房";
                }else if($val['room_type']==2){
                    $val['room_type']="大床房";
                }else if($val['room_type']==3){
                    $val['room_type']="单人房";
                }else if($val['room_type']==4){
                    $val['room_type']="加床";
                }

                if($val['check_in']==-5){
                    $val['check_in']="提前5天";
                }else if($val['check_in']==-4){
                    $val['check_in']="提前4天";
                }else if($val['check_in']==-3){
                    $val['check_in']="提前3天";
                }else if($val['check_in']==-2){
                    $val['check_in']="提前2天";
                }else if($val['check_in']==-1){
                    $val['check_in']="提前1天";
                }else if($val['check_in']==0){
                    $val['check_in']="无特殊要求";
                }else if($val['check_in']==1){
                    $val['check_in']="延后1天";
                }else if($val['check_in']==2){
                    $val['check_in']="延后2天";
                }else if($val['check_in']==3){
                    $val['check_in']="延后3天";
                }else if($val['check_in']==4){
                    $val['check_in']="延后4天";
                }else if($val['check_in']==5){
                    $val['check_in']="延后5天";
                }

                if($val['check_on']==-5){
                    $val['check_on']="提前5天";
                }else if($val['check_on']==-4){
                    $val['check_on']="提前4天";
                }else if($val['check_on']==-3){
                    $val['check_on']="提前3天";
                }else if($val['check_on']==-2){
                    $val['check_on']="提前2天";
                }else if($val['check_on']==-1){
                    $val['check_on']="提前1天";
                }else if($val['check_on']==0){
                    $val['check_on']="无特殊要求";
                }else if($val['check_on']==1){
                    $val['check_on']="延后1天";
                }else if($val['check_on']==2){
                    $val['check_on']="延后2天";
                }else if($val['check_on']==3){
                    $val['check_on']="延后3天";
                }else if($val['check_on']==4){
                    $val['check_on']="延后4天";
                }else if($val['check_on']==5){
                    $val['check_on']="延后5天";
                }

                $content.='{"number":"'.$val['room_code']
                    .'","fangx":"'.$val['room_type']
                    .'","tiqian":"'.$val['check_in']
                    .'","yanzhu":"'.$val['check_on'].'"},';
                $i++;
            }

            $content=substr($content,0,strlen($content)-2);
            $content.='}]';
        }else if($cc == "flight"){
            //读取游客航班信息
            $customer_id = input("customer_id");
            $data = [
                "customer_id"=>$customer_id,
            	'company_order_number'=>$company_order_number,
                'status'=>1
            ];
            $customer_data = $this->callSoaErp('post','/branchcompany/getCompanyOrderFlight',$data);
//            var_dump($customer_data);exit;


            $content='[';
            $i=0;

            foreach($customer_data['data'] as $key=>$val){

                if($val['flight_type']==1){
                    $val['flight_type'] = "接机";
                }else if($val['flight_type']==2){
                    $val['flight_type'] = "送机";
                }else if($val['flight_type']==3){
                    $val['flight_type'] = "中转";
                }

                if(date("Y-m-d",$val['flight_begin_time'])!='1970-01-01'){
                    $begin_time=date("Y-m-d H:i:s",$val['flight_begin_time']);
                }else{
                    $begin_time="";
                }

                if(date("Y-m-d",$val['flight_end_time'])!='1970-01-01'){
                    $end_time=date("Y-m-d H:i:s",$val['flight_end_time']);
                }else{
                    $end_time="";
                }

                $content.='{"flight":"'.$val['flight_code']
                    .'","flight_begin_time":"'.$begin_time
                    .'","flight_end_time":"'.$end_time
                    .'","type":"'.$val['flight_type']
                    .'","start_place":"'.$val['start_place']
                    .'","end_place":"'.$val['end_place'].'"},';
                $i++;
            }
            $content=substr($content,0,strlen($content)-2);
            $content.='}]';
        }else if($cc == "customer"){
            echo input("aaa");
        }
        return $content;
    }

    /**
     * 分公司订单-新增页面-产品
     */
    public function showCompanyOrderProductEdit(){

        $company_order_number = input("company_order_number");

        $data=[
            "company_order_number"=>$company_order_number,
            'status'=>1
        ];

        $product_result = $this->callSoaErp('post','/branchcompany/getCompanyOrderProduct',$data);
	
        for($i=0;$i<count($product_result['data']['company_order_product_diy']);$i++){

            $str_customer_id='';
            for($j=0;$j<count($product_result['data']['company_order_product_diy'][$i]['customer_info']);$j++){
                $str_customer_id.=','.$product_result['data']['company_order_product_diy'][$i]['customer_info'][$j]['customer_id'];
            }
            $product_result['data']['company_order_product_diy'][$i]['customer_str'] = trim($str_customer_id,',');

        }
        for($i=0;$i<count($product_result['data']['company_order_product_source']);$i++){

        	$str_customer_id='';
        	for($j=0;$j<count($product_result['data']['company_order_product_source'][$i]['customer_info']);$j++){
        		$str_customer_id.=','.$product_result['data']['company_order_product_source'][$i]['customer_info'][$j]['company_order_customer_id'];
        	}
        	 $product_result['data']['company_order_product_source'][$i]['customer_str'] = trim($str_customer_id,',');

        }
	
        //获取币种
        $currency_result = $this->callSoaErp('post','/system/getCurrency',$data);
        $this->assign('currency_result_data',$currency_result['data']);

        //获取该订单下的所有游客
        $company_order_customer =  $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$data);
		//获取供应商
		$supplier_params  = [
			'supplier_type_id'=>12,
			'status'=>1,
			'company_id'=>session('user')['company_id']	
		];
        $supplier_result=  $this->callSoaErp('post','/source/getSupplier',$supplier_params);
  
        $this->assign('product_result_data',$product_result['data']);
    
        $this->assign('company_order_customer',$company_order_customer['data']);
        $this->assign('supplier_result',$supplier_result['data']);
        return $this->fetch('company_order_product');
    }

    /**
     * 分公司订单-产品-修改游客关联
     */
    public function Customer_Relation_Edit(){
        $company_order_number = input("company_order_number");
        $company_order_customer = trim(input("company_order_customer"),",");
        $company_order_product_diy_id = input("company_order_product_diy_id");
        $company_order_product_source_id = input('company_order_product_source_id');
        $data=[
            "company_order_number"=>$company_order_number,
            "company_order_customer"=>$company_order_customer,
            "company_order_product_diy_id"=>$company_order_product_diy_id,
        	'company_order_product_source_id'=>$company_order_product_source_id,
            "user_id"=>session('user')['user_id']
        ];
		
        $branch_product_result = $this->callSoaErp('post','/branchcompany/updateCompanyOrderProductCustomer',$data);
    


        return $branch_product_result;
    }

    /**
     * 分公司订单-新增搜索AJAX
     */
    public function CompanyOrderProductSearchAjax(){
        $branch_product_number = input("branch_product_number");
        $team_name = input("team_name");

        if(!empty($branch_product_number)){
            $data['branch_product_number'] = $branch_product_number;
        }

        if(!empty($team_name)){
            $data['team_name'] = $team_name;
        }
		$data['status'] = 1;
		$data['company_id'] = session('user')['company_id'];
        $branch_product_result = $this->callSoaErp('post','/branchcompany/getBranchProduct',$data);



        return $branch_product_result['data'];
    }

    /**
     * 分公司订单-新增产品-非自定义
     */
    public function addCompanyOrderProductAjax(){
        $branch_product_array = json_decode(input("branch_product_array"));
        $company_order_number = input("company_order_number");

        $data = [
            "company_order_number"=>$company_order_number,
            "branch_product_array"=>$branch_product_array,
            "user_id"=>session('user')['user_id']
        ];

        $branch_product_result = $this->callSoaErp('post','/branchcompany/addCompanyOrderProduct',$data);

        return $branch_product_result;
    }

    /**
     * 分公司订单-新增产品-自定义
     */
    public function addCompanyOrderProductDiyAjax(){
        $company_order_number = input("company_order_number");
        $diy_name = input("diy_name");
        $diy_cost = input("diy_cost");
        $price_currency_id = input("price_currency_id");
		$supplier_id = input('supplier_id');
        $data=[
            "company_order_number"=>$company_order_number,
            "diy_name"=>$diy_name,
            "diy_cost"=>$diy_cost,
            "cost_currency_id"=>$price_currency_id,
            "price_currency_id"=>$price_currency_id,
            "user_id"=>session('user')['user_id'],
            "status"=>1,
        	'supplier_id'=>$supplier_id	
        ];
		
        $product_diy_result = $this->callSoaErp('post','/branchcompany/addCompanyOrderProductDiy',$data);
	
        return $product_diy_result;
    }

    /**
     * 分公司订单-删除产品
     */
    public function CompanyOrderCostDelete(){
        $branch_product_number = input("branch_product_number");
        $company_order_number = input("company_order_number");
        $company_order_product_diy_number = input("company_order_product_diy_number");
        $status = input("status");
        $type = input("type");

        $data = [
            "branch_product_number"=>$branch_product_number,
            "company_order_number"=>$company_order_number,
            "company_order_product_diy_number"=>$company_order_product_diy_number,
            "type"=>$type,
            "user_id"=>session('user')['user_id'],
            "status"=>$status
        ];

        $delte_produt_result = $this->callSoaErp('post','/branchcompany/updateCompanyOrderProduct',$data);

        return $delte_produt_result;
    }

    /**
     * 分公司订单-新增页面-成本
     */
    public function showCompanyOrderCostEdit(){
        $company_order_number = input("company_order_number");

        $data=[
            "company_order_number"=>$company_order_number,
            'status'=>1
        ];

        $order_produt_result = $this->callSoaErp('post','/branchcompany/getCompanyOrderProduct',$data);


        for($i=0;$i<count($order_produt_result['data']['company_order_product_team']);$i++){
            $order_produt_result['data']['company_order_product_team'][$i]['invoice_time']=date("Y-m-d",$order_produt_result['data']['company_order_product_team'][$i]['invoice_time']);
            $order_produt_result['data']['company_order_product_team'][$i]['count']=count($order_produt_result['data']['company_order_product_team'][$i]['customer_info']);

        }
        for($i=0;$i<count($order_produt_result['data']['company_order_product_source']);$i++){
            $order_produt_result['data']['company_order_product_source'][$i]['invoice_time']=date("Y-m-d",$order_produt_result['data']['company_order_product_source'][$i]['invoice_time']);
            $order_produt_result['data']['company_order_product_source'][$i]['count']=count($order_produt_result['data']['company_order_product_source'][$i]['customer_info']);
        }
        for($i=0;$i<count($order_produt_result['data']['company_order_product_diy']);$i++){
            $order_produt_result['data']['company_order_product_diy'][$i]['invoice_time']=date("Y-m-d",$order_produt_result['data']['company_order_product_diy'][$i]['invoice_time']);
            $order_produt_result['data']['company_order_product_diy'][$i]['count']=count($order_produt_result['data']['company_order_product_diy'][$i]['customer_info']);
        }
        //获取该订单下的所有游客
        $company_order_customer =  $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$data);
        for($i=0;$i<count($order_produt_result['data']['company_order_product_diy']);$i++){

            $str_customer_id='';
            for($j=0;$j<count($order_produt_result['data']['company_order_product_diy'][$i]['customer_info']);$j++){
                $str_customer_id.=','.$order_produt_result['data']['company_order_product_diy'][$i]['customer_info'][$j]['customer_id'];
            }
            $order_produt_result['data']['company_order_product_diy'][$i]['customer_str'] = trim($str_customer_id,',');
        }

        
        //读取币种
        $currency_result = $this->callSoaErp('post','/system/getCurrency',$data);
        $this->assign('currency_result_data',$currency_result['data']);

        $this->assign('company_order_product_team',$order_produt_result['data']['company_order_product_team']);
        $this->assign('company_order_product_source',$order_produt_result['data']['company_order_product_source']);
        $this->assign('company_order_product_diy',$order_produt_result['data']['company_order_product_diy']);
        $this->assign('company_order_customer',$company_order_customer['data']);


        return $this->fetch('company_order_cost');
    }

    /**
     * 分公司订单-修改成本AJAX
     */
    public function CompanyOrderCostEditAjax(){
        $data = Request::instance()->param();
        $data['invoice_time'] = strtotime(input("invoice_time"));
        $data["user_id"]=session('user')['user_id'];

//        error_log(print_r($data,1));

        $result = $this->callSoaErp('post','/branchcompany/updateCompanyOrderCostAndPrice',$data);

        return $result;
    }
    /**
     * 分公司订单-修改成本AJAX
     */
    public function CompanyOrderCostAllEditAjax(){
    	$data = Request::instance()->param();
    	
    

    	//        error_log(print_r($data,1));
    	$info = $data['info'];
    	for($i=0;$i<count($info);$i++){
    		if($info[$i][0]==4){
    			$info[$i]['type']=4;
    			$info[$i]['company_order_product_id'] = $info[$i][3];
    			$info[$i]['price_currency_id'] = $info[$i][1];
    			$info[$i]['branch_product_price'] = $info[$i][2];
    		}else if($info[$i][0]==2){
    			$info[$i]['type']=2;
    			$info[$i]['company_order_product_source_id'] = $info[$i][3];
    			$info[$i]['price_currency_id'] = $info[$i][1];
    			$info[$i]['source_price'] = $info[$i][2];
    		}else if($info[$i][0]==3){
    			$info[$i]['type']=3;
    			$info[$i]['company_order_product_diy_number'] = $info[$i][3];
    			$info[$i]['price_currency_id'] = $info[$i][1];
    			$info[$i]['diy_price'] = $info[$i][2];
    		}
    	}
    	$data['info'] = $info;
    	
    	

    	$result = $this->callSoaErp('post','/branchcompany/updateCompanyOrderCostAllAndPrice',$data);
   
 
    	return $result;
    }
    /**
     * 分公司订单-新增页面-应收
     */
    public function showCompanyOrderPriceEdit(){

        $company_order_number = input("company_order_number");

        $data=[
            "company_order_number"=>$company_order_number,
            'status'=>1
        ];

        $order_produt_result = $this->callSoaErp('post','/branchcompany/getCompanyOrderProduct',$data);
		
        for($i=0;$i<count($order_produt_result['data']['company_order_product']);$i++){
            $order_produt_result['data']['company_order_product'][$i]['invoice_time']=date("Y-m-d",$order_produt_result['data']['company_order_product'][$i]['invoice_time']);
            $order_produt_result['data']['company_order_product'][$i]['count']=count($order_produt_result['data']['company_order_product_team'][$i]['customer_info']);
        }
        for($i=0;$i<count($order_produt_result['data']['company_order_product_source']);$i++){
            $order_produt_result['data']['company_order_product_source'][$i]['invoice_time']=date("Y-m-d",$order_produt_result['data']['company_order_product_source'][$i]['invoice_time']);
            $order_produt_result['data']['company_order_product_source'][$i]['count']=count($order_produt_result['data']['company_order_product_source'][$i]['customer_info']);
        }
        for($i=0;$i<count($order_produt_result['data']['company_order_product_diy']);$i++){
            $order_produt_result['data']['company_order_product_diy'][$i]['invoice_time']=date("Y-m-d",$order_produt_result['data']['company_order_product_diy'][$i]['invoice_time']);
            $order_produt_result['data']['company_order_product_diy'][$i]['count']=count($order_produt_result['data']['company_order_product_diy'][$i]['customer_info']);
        }

        //获取该订单下的所有游客
        $company_order_customer =  $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',$data);
        for($i=0;$i<count($order_produt_result['data']['company_order_product_diy']);$i++){

            $str_customer_id='';
            for($j=0;$j<count($order_produt_result['data']['company_order_product_diy'][$i]['customer_info']);$j++){
                $str_customer_id.=','.$order_produt_result['data']['company_order_product_diy'][$i]['customer_info'][$j]['customer_id'];
            }
            $order_produt_result['data']['company_order_product_diy'][$i]['customer_str'] = trim($str_customer_id,',');
        }

        //读取币种
        $currency_result = $this->callSoaErp('post','/system/getCurrency',$data);
        $this->assign('currency_result_data',$currency_result['data']);

        $receivable_data=[
            "order_number"=>$company_order_number,
            'status'=>1,
        
        ];
        //获取应收
        $receivable_result = $this->callSoaErp('post','/finance/getReceivable',$receivable_data);
        if(count($receivable_result['data'])>0){
            $receivable_number = $receivable_result['data'][0]['receivable_number'];
        }else{
            $receivable_number = "";
        }

        $this->assign('receivable_number',$receivable_number);
        $this->assign('company_order_product_team',$order_produt_result['data']['company_order_product_team']);
        $this->assign('company_order_product_source',$order_produt_result['data']['company_order_product_source']);
        $this->assign('company_order_product_diy',$order_produt_result['data']['company_order_product_diy']);
        $this->assign('order_produt_result',$order_produt_result['data']);

        $this->assign('company_order_customer',$company_order_customer['data']);


        return $this->fetch('company_order_price');
    }

    /**
     * 生产应收编号
     */
    public function getReceivablePrice(){
        //读取应收编号
        $company_order_number = input("company_order_number");
        $total_price = input("total_price");

        $data = [
            "order_number"=>$company_order_number,
            "payment_company_id"=>session('user')['user_id'],
            "product_type"=>3,
            "product_name"=>"aaa",
            "currency_id"=>1,
            "receivable_money"=>$total_price,
            "user_id"=>session('user')['user_id'],
            "status"=>1
        ];
        $receivable_result = $this->callSoaErp('post','/finance/addReceivable',$data);
        return $receivable_result;
    }

    /**
     * 修改应收价格
     */
    public function editReceivablePrice(){
        $receivable_number = input("receivable_number");
        $total_price = input("total_price");

        $data=[
            "receivable_number"=>$receivable_number,
            "receivable_money"=>$total_price
        ];
        $receivable_result = $this->callSoaErp('post','/finance/updateReceivableByReceivableNumber',$data);

        return $receivable_result;

    }
    
    /**
     * 获取公司订单下的顾客信息
     */
    public function getCompanyOrderCustomer(){
        $company_order_number = input('company_order_number');
        $company_order_customer =  $this->callSoaErp('post','/branchcompany/getCompanyOrderCustomer',['company_order_number'=>$company_order_number]);
        return $company_order_customer;
        
    }
    /**
     * 分公司订单-新增页面-销售收款
     */
    public function showCompanyOrderSalesEdit(){
    	
		$company_order_number = input('company_order_number');
        //读取币种
        $currency_result = $this->callSoaErp('post','/system/getCurrency',$data);
        $this->assign('currency_result_data',$currency_result['data']);
        $receivable_data = [
        	'payment_object_type'=>3,
        	'order_number'=>$company_order_number,
        	'status'=>1	
        ]; 
        //获取应收
        $receivable_result = $this->callSoaErp('post','/finance/getReceivable',$receivable_data);

        if(count($receivable_result['data'])>0){
            $receivable_number = $receivable_result['data'][0]['receivable_number'];
        }else{
            $receivable_number = "";
        }
       
        $receivable_info_data = [
            "receivable_number"=>$receivable_number,
            "receivable_info_type"=>2,
            "status"=>1
        ];
        //获取应收详情
        $receivable_info_result = $this->callSoaErp('post','/finance/getReceivableInfo',$receivable_info_data);
        foreach($receivable_info_result['data'] as $kk=>$vv){
            //转换时间
            $receivable_info_result['data'][$kk]['payment_time'] =  date("Y-m-d",$receivable_info_result['data'][$kk]['payment_time']);
        }

        //读取币种
        $currency_result = $this->callSoaErp('post','/system/getCurrency',$data);
        
        
        //获取抬头
        $bill_template_params = [
        	'company_id'=>session('user')['company_id'],
        	'status'=>1	
        ];
        $bill_template_result = $this->callSoaErp('post','/system/getBillTemplate',$bill_template_params);
        
        //获取最新用户数据
        $user_params = [
        	'user_id'=>session('user')['user_id']
        ];
        $user_result =  $this->callSoaErp('post','/user/getUser',$user_params);
		$this->assign('user_result',$user_result['data'][0]);
        $this->assign('bill_template_result',$bill_template_result['data']);
        
        $this->assign('currency_result_data',$currency_result['data']);

        $this->assign('receivable_number',$receivable_number);
        $this->assign('receivable_info',$receivable_info_result['data']);

        return $this->fetch('company_order_sales');
    }
	
    /**
     * 调取销售收款发票AJAX
     */
    public function getSaleBillAjax(){
    	
    	$bill_template_id = input("bill_template_id");//账单ID
    	$company_order_number = input('company_order_number');//公司订单
    	$receivable_info_id = input('receivable_info_id');//收款ID多个逗号隔开
    	$default_bill_template_id = input('default_bill_template_id',0);//是否默认 1为是
    	$bill_template = [
    		'bill_template_id'=>$bill_template_id,
    		'company_order_number'=>$company_order_number,
    		'receivable_info_id'=>$receivable_info_id,
    		'default_bill_template_id'=>default_bill_template_id
    	];    	
    	    	
    	$sale_bill_result = $this->callSoaErp('post','/branchcompany/getSaleBill',$bill_template);
    	return $sale_bill_result;
    	
    }
    
    
    /**
     * 分公司订单-修改销售收款
     */
    public function SalesEdit(){

        $data = Request::instance()->param();

        $data["payment_time"] =strtotime($data["payment_time"]);
        $data["status"]=1;
        $data["user_id"]=session('user')['user_id'];

        $company_order_sales = $this->callSoaErp('post','/finance/updateReceivableInfoByReceivableInfoId',$data);

        return $company_order_sales;
    }

    /**
     * 分公司订单-删除销售收款
     */
    public function SalesDelete(){

        $data = Request::instance()->param();
        $data["status"]=0;

        $company_order_sales = $this->callSoaErp('post','/finance/updateReceivableInfoByReceivableInfoId',$data);

        return $company_order_sales;
    }

    /**
     * 分公司订单-销售收款添加
     */
    public function CompanyOrderSalesAddAjax(){

        $data = Request::instance()->param();
        $data['payment_time'] = strtotime(input("payment_time"));

        $company_order_sales = $this->callSoaErp('post','/branchcompany/addCompanyOrderSale',$data);

        return $company_order_sales;
    }

    /**
     * 分公司订单-获取游客行程单
     */
    public function showCompanyOrderCustomerGuideReceipt(){

        return $this->fetch('company_order_customer_guide_receipt');

    }
    

    /**
     * 线路分类
     */
    public function showRouteTypeManage(){

        //搜索
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $route_type_name = input("route_type_name");
        $type = input("type");
        $status = input("status");

        if(!empty($route_type_name)){
            $data['route_type_name'] = $route_type_name;
        }
        if($type>0){
            $data['type'] = $type;
        }
        if($status>0){
            $data['status'] = $status;
        }
        $data['company_id'] =  session('user')['company_id'];
        $result = $this->callSoaErp('post', '/system/getRouteType',$data);
        //$this->assign('data',$result['data']);
        $this->getPageParams($result);
        return $this->fetch('route_type_manage');
    }

    /**
     * 线路分类添加界面
     */
    public function showRouteTypeAdd(){

        //线路负责人
        $data['department_id'] = session('user')['department_id'];
        $data['company_id'] = session('user')['company_id'];
        $data['status'] = 1;

        $UserList = $this->callSoaErp('post','/user/getUser',$data);
        if($UserList['data']){
            $this->assign('UserList',$UserList['data']);
        }
        unset($data['department_id']);

        //操作用户
        $this->assign('UserId',session('user')['user_id']);

        return $this->fetch('route_type_add');
    }

    /**
     * 线路分类添加数据
     */
    public function addRouteTypeAjax(){
        $route_type_name = input("route_type_name");
        $type = input("type");
        $route_type_user_id = input("route_type_user_id");
        $status = input("status");
        $user_id = session('user')['user_id'];

        $data = [
            "route_type_name"=>$route_type_name,
            "type"=>$type,
            "route_type_user_id"=>$route_type_user_id,
            "status"=>$status,
            "user_id"=>$user_id
        ];

        $result = $this->callSoaErp('post', '/system/addRouteType',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }

    /**
     * 线路分类修改加界面
     */
    public function showRouteTypeEdit(){

        $route_type_id = input("route_type_id");

        $data = [];
        $data = ["route_type_id"=>$route_type_id];

        $route_type_result = $this->callSoaErp('post', '/system/getRouteType',$data);
        $this->assign('route_type_result',$route_type_result['data'][0]);

        //线路负责人
        $data['department_id'] = session('user')['department_id'];
        $data['company_id'] = session('user')['company_id'];
        $data['status'] = 1;

        $UserList = $this->callSoaErp('post','/user/getUser',$data);
        if($UserList['data']){
            $this->assign('UserList',$UserList['data']);
        }
        unset($data['department_id']);

        //操作用户
        $this->assign('UserId',$route_type_result['data'][0]['route_type_user_id']);

        return $this->fetch('route_type_edit');
    }

    /**
     * 线路分类修改数据
     */
    public function editRouteTypeAjax(){

        $route_type_id = input("route_type_id");
        $route_type_name = input("route_type_name");
        $type = input("type");
        $route_type_user_id = input("route_type_user_id");
        $status = input("status");
        $user_id = session('user')['user_id'];

        $data = [
            "route_type_id"=>$route_type_id,
            "route_type_name"=>$route_type_name,
            "type"=>$type,
            "route_type_user_id"=>$route_type_user_id,
            "status"=>$status,
            "user_id"=>$user_id
        ];

        $result = $this->callSoaErp('post', '/system/updateRouteTypeByRouteTypeId',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }

    /**
     * 线路分类详情界面
     */
    public function showRouteTypeInfo(){

        $route_type_id = input("route_type_id");

        $data = [];
        $data = ["route_type_id"=>$route_type_id];

        $route_type_result = $this->callSoaErp('post', '/system/getRouteType',$data);
        $this->assign('route_type_result',$route_type_result['data'][0]);

        return $this->fetch('route_type_info');
    }

    /**
     *  回执单模板
     *  Hugh 2018-08-13
     */
    public function showReturnReceiptManage(){
        $setReturnReceiptManage = Session::get('setReturnReceiptManage');

        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];

        if(!empty($setReturnReceiptManage['search_Id'])){
            $data['return_receipt_id'] = $setReturnReceiptManage['search_Id'];
        }
        if(!empty($setReturnReceiptManage['search_Name'])){
            $data['return_receipt_name'] = $setReturnReceiptManage['search_Name'];
        }
        if(!empty($setReturnReceiptManage['search_status'])){
            $data['status'] = $setReturnReceiptManage['search_status'];
        }
        $data['company_id'] =  session('user')['company_id'];
        $ReturnReceiptData = $this->callSoaErp('post','/system/getReturnReceipt',$data);

        if(!empty($ReturnReceiptData['data'])){
            $this->getPageParams($ReturnReceiptData);
        }
        $this->assign('setReturnReceiptManage',$setReturnReceiptManage);
        return $this->fetch('return_receipt_manage');
    }

    /**
     * 搜索 回执单
     * Hugh 2018-08-13
     */
    public function setReturnReceiptManage(){
        Session::set('setReturnReceiptManage',$_POST);
        $this->redirect('/product/showReturnReceiptManage');
    }
    public function clearReturnReceiptManage(){
        Session::delete('setReturnReceiptManage');
        $this->redirect('/product/showReturnReceiptManage');
    }

    /**
     * 添加回执单模板
     * Hugh 2018-08-13
     */
    public function showReturnReceiptAdd(){
        return $this->fetch('return_receipt_add');
    }

    /***
     * 异步添加回执单
     * Hugh 2018-08-13
     */
    public function addShowReturnReceiptAjax(){
        $post = Request::instance()->param();
        $data['user_id'] = session('user')['user_id'];
        $data['return_receipt_name'] = Arrays::get($post,'return_receipt_name');
        $data['status'] = Arrays::get($post,'status');
   
        $ReturnReceiptData = $this->callSoaErp('post','/system/addReturnReceipt',$data);
		
        if(!empty($ReturnReceiptData['data'])){
            $return_receipt_id = $ReturnReceiptData['data'];
        }

        unset($data);
        $title = Arrays::get($post,'title');
        $content = Arrays::get($post,'content');
        $sorting = Arrays::get($post,'sorting');
        foreach($title as $ky=>$vl){
            $ar['title'] = $vl;
            $ar['content'] = $content[$ky];
            $ar['sorting'] = $sorting[$ky];
            $return_receipt_info[] = $ar;
        }
        $data['return_receipt_id'] = $return_receipt_id;
        $data['now_user_id'] = session('user')['user_id'];
        $data['status'] = 1;
        $data['return_receipt_info'] = $return_receipt_info;

        $result = $this->callSoaErp('post','/system/addReturnReceiptInfo',$data);
        return $result;
    }

    /**
     * 修改回执单模板
     * Hugh 18-08-13
     */
    public function showReturnReceiptEdit(){
        $id = input('get.id');
        $data['return_receipt_id'] = $id;
        $ReturnReceiptData = $this->callSoaErp('post','/system/getReturnReceipt',$data);
        if(!empty($ReturnReceiptData['data'])){
            $this->assign('ReturnReceiptList',$ReturnReceiptData['data'][0]);
        }

        $data['status'] = 1;
        $ReturnReceiptInfoData =  $this->callSoaErp('post','/system/getReturnReceiptInfo',$data);

        if(!empty($ReturnReceiptInfoData['data'])){
            $this->assign('ReturnReceiptInfoList',$ReturnReceiptInfoData['data']);
        }

        return $this->fetch('return_receipt_edit');
    }

    /***
     * 异步修改回执单
     * Hugh 2018-08-13
     */
    public function editShowReturnReceiptAjax(){
        $post = Request::instance()->param();
        $id = input('get.id');

        $del_return_receipt_info_id = trim(Arrays::get($post,'del_return_receipt_info_id'),',');    //被删除的回执内容

        $data['return_receipt_id'] = $id;
        $data['return_receipt_name'] = Arrays::get($post,'return_receipt_name');
        $data['status'] = Arrays::get($post,'status');
        $data['user_id'] = session('user')['user_id'];
        $this->callSoaErp('post','/system/updateReturnReceiptByReturnReceiptId',$data);
        unset($data);
        if(!empty($del_return_receipt_info_id)){
            $del_return_receipt_info_id_ar = explode(',',$del_return_receipt_info_id);
            foreach($del_return_receipt_info_id_ar as $vl){
                $data['return_receipt_info_id'] = $vl;
                $data['status'] = 2;
                $data['user_id'] = session('user')['user_id'];
                $this->callSoaErp('post','/system/updateReturnReceiptInfoByReturnReceiptInfoId',$data);
                unset($data);
            }
        }

        $title = Arrays::get($post,'title');
        $content = Arrays::get($post,'content');
        $sorting = Arrays::get($post,'sorting');
        $return_receipt_info_id = Arrays::get($post,'return_receipt_info_id');

        foreach($title as $ky=>$vl){
            if(!empty($return_receipt_info_id[$ky])){
                $data['return_receipt_info_id'] = $return_receipt_info_id[$ky];
                $data['title'] = $vl;
                $data['content'] = $content[$ky];
                $data['sorting'] = $sorting[$ky];
                $data['user_id'] = session('user')['user_id'];
                $result = $this->callSoaErp('post','/system/updateReturnReceiptInfoByReturnReceiptInfoId',$data);
                unset($data);
            }else{
                $ar['title'] = $vl;
                $ar['content'] = $content[$ky];
                $ar['sorting'] = $sorting[$ky];
                $return_receipt_info[] = $ar;
            }
        }

        if(!empty($return_receipt_info)){
            $data['return_receipt_id'] = $id;
            $data['user_id'] = session('user')['user_id'];
            $data['status'] = 1;
            $data['return_receipt_info'] = $return_receipt_info;
            $result = $this->callSoaErp('post','/system/addReturnReceiptInfo',$data);
        }
        return $result;
    }

    /**
     * 回执单模板详情
     * Hugh 18-08-13
     */
    public function showReturnReceiptInfo(){
        $id = input('get.id');
        $data['return_receipt_id'] = $id;
        $ReturnReceiptData = $this->callSoaErp('post','/system/getReturnReceipt',$data);
        if(!empty($ReturnReceiptData['data'])){
            $this->assign('ReturnReceiptList',$ReturnReceiptData['data'][0]);
        }

        $data['status'] = 1;
        $ReturnReceiptInfoData =  $this->callSoaErp('post','/system/getReturnReceiptInfo',$data);
        if(!empty($ReturnReceiptInfoData['data'])){
            $this->assign('ReturnReceiptInfoList',$ReturnReceiptInfoData['data']);
        }

        return $this->fetch('return_receipt_info');
    }
    
    /**
     * 生成订单报价
     */
    public function updateCompanyOrderCopeAndReceivable(){
    	$data['company_order_number'] = input('company_order_number');
    	
    	$result =  $this->callSoaErp('post','/branchcompany/updateCompanyOrderReveivableAndCope',$data);
    	return $result;
    }
    
    //删除行程单
    public function updateTourGuideStatus(){
    	$data['team_product_number'] = input('team_product_number');
    	 
    	$result =  $this->callSoaErp('post','/branchcompany/updateCompanyOrderReveivableAndCope',$data);
    	return $result;
    }
	/**
	 * 获取团队产品AJAX
	 * 
	 */
    public function getTeamProductAjax(){
    	$team_product_number = input('team_product_number');
    	$team_product_name = input('team_product_name');
    	$choose_company_id = input('choose_company_id');
    	$is_branch_product = 1;
    	$data['can_watch_company_id'] = session('user')['company_id'];
    	if(!empty($team_product_name)){
    		$data['team_product_name'] = $team_product_name;
    	}
    	if(!empty($team_product_number)){
    		$data['team_product_number'] = $team_product_number;
    	}
    	if(!empty($choose_company_id)){
    		$data['choose_company_id'] = $choose_company_id;
    	}   
    	if(!empty($is_branch_product)){
    		$data['is_branch_product'] = $is_branch_product;
    	}		
    	$data['status'] = 1;
    	$result =  $this->callSoaErp('post','/product/getTeamProductBase',$data);

    	return $result;
    }
}