<?php
/**
 * Created by PhpStorm.
 * User: Hugh
 * Date: 2019/10/12
 * Time: 13:40
 */

namespace app\index\controller;
use \Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;
use app\common\help\Help;

class BooknexusTour extends Base
{
    public function index(){

        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $tour_code = input("tour_code");
        $tour_name = input("tour_name");
        $status = input("status");

        $data['tour_code'] = $tour_code;
        $data['tour_name'] = $tour_name;
        $data['status'] = $status;
        $data['company_id'] =  session('user')['company_id'];

        $result = $this->callSoaErp('post', '/b2b_tour/getB2bTour',$data);

        $this->getPageParams($result);

        return $this->fetch('list');
    }


    


    //获取线路产品信息
    public function  getRouteTemplate(){
        $where['route_template_id'] = $_POST['v'];
        $RouteTemplate = $this->callSoaErp('post','/product/getRouteTemplate',$where);

        if(!empty(session('createTourForm'))){
            if(session('createTourForm')['supplier_tour']!=$_POST['v']){
//                session::delete('addTourDatesForm');
//                session::delete('addTourItineraryForm');
                session('addTourDatesForm',['t'=>true]);
                session('addTourItineraryForm',['t'=>true]);
            }
        }
        return $RouteTemplate;
    }


    //获取二级TOUR type
    public function setTourTypeAjax(){
        $where['company_id'] =  session('user')['company_id'];
        $where['pid'] = $_POST['v']?:-1;
        $where['status'] = 1;
        $where['system_type'] = 'Tour';
        if ($where['pid'] != 0)
        {
            $where['system_type'] = 'Tour';
        }
        $result = $this->callSoaErp('post', '/btob/getTourTypeAjax',$where);
        return $result;
    }


    public function updateTourForm(){
        // 线路模板
        $where = ['status'=>1]; //,'can_watch_company_id'=>session('user')['company_id']
        $RouteTemplate = $this->callSoaErp('post','/product/getRouteTemplate',$where);
        $this->assign('RouteTemplate',$RouteTemplate['data']);
        unset($where);

        $where = ['status'=>1];
        $Language = $this->callSoaErp('post','/system/getLanguage',$where);
        $this->assign('Language',$Language['data']);

        //获取tour-type
        $where['company_id'] =  session('user')['company_id'];
        // $where['pid'] = 0;
        $where['status'] = 1;
        $where['system_type'] = 'Tour';
        $result = $this->callSoaErp('post', '/btob/getTourTypeAjax',$where);
        $this->assign('tour_type',Arrays::group($result['data'],'pid'));
        unset($where);
//        var_dump($result);exit;

        //佣金
        $where['status'] = 1;
        $where['company_id'] = session('user')['company_id'];
        $result = $this->callSoaErp('post', '/btob/getCommission',$where);
        $this->assign('tab',$result['data']);
        unset($where);
        //ReturnReceipt
        $where['status'] = 1;
        $where['company_id'] = session('user')['company_id'];
        $result = $this->callSoaErp('post', '/system/getReturnReceipt',$where);
        $this->assign('ReturnReceipt',Arrays::group($result['data'],'type'));
        unset($where);

        $where['btb_tour_id'] = $_GET['btb_tour_id'];
        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourGeneral',$where);
        $this->assign('TourForm',$result['data']);
//        var_dump($result);exit;



       return  $this->fetch('update_tour_form');
    }


    public function createTourForm(){
//        echo '<pre>';print_r(session('createTourForm'));exit;


        //获取Supplier
//        $where=[
//            "company_id"=>session('user')['company_id'],
//            "status"=>1
//        ];
//        $BranchProduct = $this->callSoaErp('post','/branchcompany/getBranchProduct',$where);
//        $BranchProduct_G = Arrays::group($BranchProduct['data'],'branch_product_type_id');
//        $this->assign('BranchProduct',$BranchProduct_G);


        $where = ['status'=>1]; //,'can_watch_company_id'=>session('user')['company_id']
        $RouteTemplate = $this->callSoaErp('post','/product/getRouteTemplate',$where);
        $this->assign('RouteTemplate',$RouteTemplate['data']);
//        $RouteTemplate_G = Arrays::group($RouteTemplate['data'],'route_type_id');
//        $this->assign('RouteTemplate_G',$RouteTemplate_G);
        unset($where);
//        var_dump($BranchProduct_G);exit;

//        $where=[
//            "company_id"=>session('user')['company_id'],
//            "status"=>1
//        ];
//        $BranchProductType = $this->callSoaErp('post','/branchcompany/getBranchProductType',$where);
//        $this->assign('BranchProductType',$BranchProductType['data']);
//         unset($where);

        //Tour Languages
        $where = ['status'=>1];
        $Language = $this->callSoaErp('post','/system/getLanguage',$where);
//        var_dump($Language);exit;
        $this->assign('Language',$Language['data']);
        unset($where);
        //获取tour-type
        $where['company_id'] =  session('user')['company_id'];
        // $where['pid'] = 0;
        $where['status'] = 1;
        $where['system_type'] = 'Tour';
        $result = $this->callSoaErp('post', '/btob/getTourTypeAjax',$where);
        $this->assign('tour_type',Arrays::group($result['data'],'pid'));
        unset($where);
//        var_dump($result);exit;

        //佣金
        $where['status'] = 1;
        $where['company_id'] = session('user')['company_id'];
        $result = $this->callSoaErp('post', '/btob/getCommission',$where);
        $this->assign('tab',$result['data']);
        unset($where);
        //ReturnReceipt
        $where['status'] = 1;
        $where['company_id'] = session('user')['company_id'];
        $result = $this->callSoaErp('post', '/system/getReturnReceipt',$where);
        $this->assign('ReturnReceipt',Arrays::group($result['data'],'type'));

        return $this->fetch('create_tour_form');
    }
    public function createTourFormAjax(){
        $_POST['t'] = true;
        Session::set('createTourForm',$_POST);
        return ['code'=>200];
    }


    public function updateTourDatesForm(){
        $where['btb_tour_id'] = $_GET['btb_tour_id'];
        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourGeneral',$where);
        //根据线路模板获取团队日期
         $route_template_id =  $result['data']['route_template_id']; //$BranchProduct['data'][0]['route_teamplate_array'][0]['route_template_id'];
        unset($where);

        $where['route_template_id'] = $route_template_id;
        $where['status'] =1;
        $TeamProductBase = $this->callSoaErp('post','/product/getTeamProductBase',$where);
        $dateOj = [];
        foreach($TeamProductBase['data'] as $v){
            $dateOj[] = date('Y-m-d',$v['begin_time']);
        }
        sort($dateOj);
        $this->assign('dateOj',$dateOj);
        unset($where);



        //获取线路负责人
        $where['route_template_id'] = $route_template_id;
        $RouteTemplate = $this->callSoaErp('post','/product/getRouteTemplate',$where);
        $this->assign('RouteTemplate',$RouteTemplate['data'][0]);
        unset($where);

        //获取季节
        $where['status'] =1;
        $where['language_id'] = session('user')['language_id'];
        $result = $this->callSoaErp('post', '/system/getSeasonAjax', $where);
        $this->assign('season',$result['data']);
        unset($where);


        $where['status'] = 1;
        $where['btb_tour_id'] = $_GET['btb_tour_id'];
        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourDates',$where);
        $this->assign('TourDates',$result['data']);


        return $this->fetch('update_tour_dates_form');
    }


    public function addTourDatesForm(){
//        var_dump(session('addTourDatesForm'));exit;
        //获取线路模板信息
//        $where['branch_product_id'] = session('createTourForm')['supplier_tour']; //210;
//        $where['status'] =1;
//        $BranchProduct = $this->callSoaErp('post','/branchcompany/getBranchProduct',$where);
//        unset($where);//var_dump($BranchProduct);exit();
        //根据线路模板获取团队日期
        $route_template_id = session('createTourForm')['supplier_tour']; //$BranchProduct['data'][0]['route_teamplate_array'][0]['route_template_id'];
        $where['route_template_id'] = $route_template_id;
        $where['status'] =1;
        $TeamProductBase = $this->callSoaErp('post','/product/getTeamProductBase',$where);
        $dateOj = [];
        foreach($TeamProductBase['data'] as $v){
            $dateOj[] = date('Y-m-d',$v['begin_time']);
        }
        sort($dateOj);
        $this->assign('dateOj',$dateOj);
        unset($where);

        //获取线路负责人
        $where['route_template_id'] = $route_template_id;
        $RouteTemplate = $this->callSoaErp('post','/product/getRouteTemplate',$where);
        $this->assign('RouteTemplate',$RouteTemplate['data'][0]);
        unset($where);

        //获取季节
        $where['status'] =1;
        $where['language_id'] = session('user')['language_id'];
        $result = $this->callSoaErp('post', '/system/getSeasonAjax', $where);
        $this->assign('season',$result['data']);

        return $this->fetch('add_tour_dates_form');
    }
    public function addTourDatesFormAjax(){
        $_POST['t'] = true;
        Session::set('addTourDatesForm',$_POST);
        return ['code'=>200];
    }


    public function updateTourItineraryForm(){
        $where['btb_tour_id'] = $_GET['btb_tour_id'];
        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourItinerary',$where);
        $this->assign('TourItinerary',$result['data']);
//        var_dump($result);exit;

        return $this->fetch('update_tour_itinerary_form');

    }


    public function addTourItineraryForm(){
        //获取线路模板信息
//        $where['branch_product_id'] = session('createTourForm')['supplier_tour']; //210;
//        $where['status'] =1;
//        $BranchProduct = $this->callSoaErp('post','/branchcompany/getBranchProduct',$where);
//        unset($where);
//        var_dump($BranchProduct['data'][0]['route_teamplate_array']);

        //获取线路行程
        $route_template_id = session('createTourForm')['supplier_tour'];// $BranchProduct['data'][0]['route_teamplate_array'][0]['route_template_id'];
        $where['route_template_id'] = $route_template_id;$where['status'] =1;
        $RouteJourney = $this->callSoaErp('post','/product/getRouteJourney',$where);
//        echo '<pre>';print_r($RouteJourney);exit;
        $this->assign('RouteJourney',$RouteJourney['data']);

        return $this->fetch('add_tour_itinerary_form');
    }
    public function addTourItineraryFormAjax(){
        $_POST['t'] = true;
        Session::set('addTourItineraryForm',$_POST);
        return ['code'=>200];
    }


    public function updateTourRoomForm(){
        $where['btb_tour_id'] = $_GET['btb_tour_id'];
        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourRoom',$where);
        $this->assign('TourRoom',$result['data']);
//        var_dump($result);exit;
        //获取房型
        $where['status'] =1;
        $where['language_id'] = session('user')['language_id'];
        $result = $this->callSoaErp('post', '/system/getRoomTypeAjax', $where);
        $result['data']= Arrays::sort($result['data'],'accommodate','asc');
        $this->assign('roomType',$result['data']);

        return $this->fetch("update_tour_room_form");
    }


    public function addTourRoomForm(){
//        var_dump(session('addTourItineraryForm')); exit;

        //获取房型
        $where['status'] =1;
        $where['language_id'] = session('user')['language_id'];
        $result = $this->callSoaErp('post', '/system/getRoomTypeAjax', $where);
        $result['data']= Arrays::sort($result['data'],'accommodate','asc');
        $this->assign('roomType',$result['data']);
//        var_dump($result);exit;
        return $this->fetch('add_tour_room_form');
    }
    public function addTourRoomFormAjax(){
        $_POST['t'] = true;
        Session::set('addTourRoomForm',$_POST);
        return ['code'=>200];
    }

    public function updateTourTransferForm(){
        $where['btb_tour_id'] = $_GET['btb_tour_id'];
        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourTransfer',$where);
        $this->assign('TourTransfer',$result['data']);
//        var_dump($result);exit;
        return $this->fetch('update_tour_transfer_form');
    }

    public function addTourTransferForm(){
//        var_dump(session('addTourTransferForm'));exit;
        return $this->fetch('add_tour_transfer_form');
    }
    public function addTourTransferFormAjax(){
        $_POST['t'] = true;
        Session::set('addTourTransferForm',$_POST);
        return ['code'=>200];
    }


    public function addTourCommissionForm(){
        return $this->fetch('add_tour_commission_form');
    }
    public function addTourCommissionFormAjax(){
        $_POST['t'] = true;
        Session::set('addTourCommissionForm',$_POST);
        return ['code'=>200];
    }

    public function updateTourCommissionForm(){
        $where['btb_tour_id'] = $_GET['btb_tour_id'];

        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourCommission',$where);

        $this->assign('TourCommission',$result['data']);

        return $this->fetch("update_tour_commission_form");
    }


    public function addTourOptionsForm(){
//        var_dump(session('addTourOptionsForm'));exit;
        //获取 Supplier
        $Supplier = $this->callSoaErp('post','/source/getB2bHotel',['status'=>1]);
        $this->assign('supplier',$Supplier['data']);
//        var_dump($Supplier);exit;

        return $this->fetch('add_tour_options_form');
    }
    public function addTourOptionsFormAjax(){
        $_POST['t'] = true;
        Session::set('addTourOptionsForm',$_POST);
        return ['code'=>200];
    }

    public function updateTourOptionsForm(){
        $where['btb_tour_id'] = $_GET['btb_tour_id'];

        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourOptions',$where);
        $this->assign('TourOptions',$result['data']);

        $Supplier = $this->callSoaErp('post','/source/getB2bHotel',['status'=>1]);

        $this->assign('supplier',$Supplier['data']);

        return $this->fetch("update_tour_options_form");
    }


    public function addTourSettingForm(){
//        var_dump(session('addTourSettingForm'));exit;


        return $this->fetch('add_tour_setting_form');
    }
    public function addTourSettingFormAjax(){
        $_POST['t'] = true;
        Session::set('addTourSettingForm',$_POST);
        return ['code'=>200];
    }

    public function updateTourSettingForm(){
        $where['btb_tour_id'] = $_GET['btb_tour_id'];

        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourSetting',$where);
        $result['data']['department'] = explode(',', $result['data']['department']);

        $this->assign('TourSetting',$result['data']);

        return $this->fetch("update_tour_setting_form");
    }


    //添加产品保存
    public function addBooknexusTourAjax(){
        $post = Session::get('createTourForm');

        $data['route_template_id'] = $post['supplier_tour'];
        $data['can_watch_company_id'] = session('user')['company_id'];
        $data['status'] = 1;
        $route_template = $this->callSoaErp('post', '/product/getRouteTemplate', $data)['data'][0];
        if (empty($route_template)) return ['code'=> 400];

        $branch_data['branch_product_name'] = $post['tour_name'];
        $branch_data['branch_product_type_id'] = $post['tour_type'] ? $post['tour_type'] : -2;
        $branch_data['branch_product_begin_time'] = strtotime('2020-01-01');  //开始日期
        $branch_data['status'] = 1;
        $branch_data['route_template_array'][0]['route_number'] = $route_template['route_number'];
        $branch_data['route_template_array'][0]['price_currency_id'] = $route_template['currency_id'];
        $branch_data['route_template_array'][0]['distributor_price'] = $route_template['once_price'];
        $branch_data['route_template_array'][0]['customer_price'] = $route_template['once_price'];
        $branch_data['route_template_array'][0]['settlement_type'] = $route_template['settlement_type'];
        $branch_data['route_template_array'][0]['once_price'] = $route_template['once_price'];
        $branch_data['route_template_array'][0]['real_price'] = $route_template['real_price'];
        $branch_data['route_template_array'][0]['currency_id'] = $route_template['currency_id'];
        $branch_data['type'] = 2;
        $add_branch_result = $this->callSoaErp('post', '/branchcompany/addBranchProductBigArray', $branch_data);

        if ($add_branch_result['code'] == 400) return $add_branch_result;

        $branch_result = $this->callSoaErp('post', '/branchcompany/getBranchProduct', ['branch_product_number' => $add_branch_result['data']]);
        $post['supplier_tour'] =  $branch_result['data'][0]['branch_product_id'];

        $post['date'] = Session::get('addTourDatesForm')['date'];
        $post['itinerary'] = Session::get('addTourItineraryForm')['itinerary'];
        $post['room'] = Session::get('addTourRoomForm')['room'];
        $post['transfer'] = Session::get('addTourTransferForm')['transfer'];
        $post['commission'] = Session::get('addTourCommissionForm');
        $post['options'] = Session::get('addTourOptionsForm')['options'];
        $post['setting'] = Request::instance()->param();

        $result = $this->callSoaErp('post','/b2b_tour/addB2bTour',$post);
        if($result['code']==200){
              session::delete('createTourForm');
              session::delete('addTourDatesForm');
              session::delete('addTourItineraryForm');
              session::delete('addTourRoomForm');
              session::delete('addTourTransferForm');
              session::delete('addTourCommissionForm');
              session::delete('addTourOptionsForm');
              session::delete('addTourSettingForm');
        }


        return $result;

    }

    public function updateB2bTourGeneralAjax()
    {
        $post = Request::instance()->param();

 /*       if ($post['btb_tour_id'])
        {
            $b2b_tour_result = $this->callSoaErp('post','/b2b_tour/getB2bTour',['btb_tour_id' => $post['btb_tour_id']]);
            if ($b2b_tour_result['code'] == 400 || empty($b2b_tour_result['data'])) return $b2b_tour_result;

            //获取当前b2b的分公司产品
            //$branch_result = $this->callSoaErp('post', '/branchcompany/getBranchProduct', ['branch_product_id' => $b2b_tour_result['data'][0]['supplier_tour']]);

            //当前分公司产品的线路模板id不等于提交上来的线路模板id
            if (empty($post['supplier_tour']) && $branch_result['data'][0]['route_teamplate_array']['0']['route_template_id'] != $post['supplier_tour'])
            {
                //不等于更新
                $data['route_template_id'] = $post['supplier_tour'];
                $data['can_watch_company_id'] = session('user')['company_id'];
                $data['status'] = 1;
                $route_template = $this->callSoaErp('post', '/product/getRouteTemplate', $data)['data'][0];
                if (empty($route_template)) return ['code'=> 400];

                $branch_data['branch_product_number'] = $branch_result['data']['0']['branch_product_number'];
                $branch_data['branch_product_name'] = $post['tour_name'];
                $branch_data['branch_product_type_id'] = $post['tour_type'] ? $post['tour_type'] : -2;
                $branch_data['branch_product_begin_time'] = strtotime('2020-01-01');  //开始日期
                $branch_data['status'] = 1;
                $branch_data['route_template_array'][0]['route_number'] = $route_template['route_number'];
                $branch_data['route_template_array'][0]['price_currency_id'] = $route_template['currency_id'];
                $branch_data['route_template_array'][0]['distributor_price'] = $route_template['once_price'];
                $branch_data['route_template_array'][0]['customer_price'] = $route_template['once_price'];
                $branch_data['route_template_array'][0]['settlement_type'] = $route_template['settlement_type'];
                $branch_data['route_template_array'][0]['once_price'] = $route_template['once_price'];
                $branch_data['route_template_array'][0]['real_price'] = $route_template['real_price'];
                $branch_data['route_template_array'][0]['currency_id'] = $route_template['currency_id'];
                $branch_data['type'] = 2;
                $branch_data['branch_product_name'] = 'dasda';
                $add_branch_result = $this->callSoaErp('post', '/branchcompany/addBranchProductBigArray', $branch_data);
                if ($add_branch_result['code'] == 400) return $add_branch_result;

                $branch_result = $this->callSoaErp('post', '/branchcompany/getBranchProduct', ['branch_product_number' => $branch_data['branch_product_number']]);

                $post['supplier_tour'] =  $branch_result['data'][0]['branch_product_id'];
            }
            else
            {
                $post['supplier_tour'] = $b2b_tour_result['data'][0]['supplier_tour'];
            }


        }*/

        $result = $this->callSoaErp('post','/b2b_tour/updateB2bTourGeneral',$post);

        return $result;

    }


    public function updateB2bTourDatesAjax()
    {
        $post = Request::instance()->param();
        $result = $this->callSoaErp('post','/b2b_tour/updateB2bTourDates',$post);

        return $result;
    }

    public function updateB2bTourItineraryAjax()
    {
        $post = Request::instance()->param();
        $result = $this->callSoaErp('post','/b2b_tour/updateB2bTourItinerary',$post);

        return $result;
    }

    public function updateB2bTourRoomAjax()
    {
        $post = Request::instance()->param();
        $result = $this->callSoaErp('post','/b2b_tour/updateB2bTourRoom',$post);

        return $result;
    }


    public function updateB2bTourTransferAjax()
    {
        $post = Request::instance()->param();
        $result = $this->callSoaErp('post','/b2b_tour/updateB2bTourTransfer',$post);

        return $result;
    }


    public function updateB2bTourCommissionAjax()
    {
        $post = Request::instance()->param();

        $result = $this->callSoaErp('post','/b2b_tour/updateB2bTourCommission',$post);

        return $result;
    }

    public function updateB2bTourOptionsAjax()
    {
        $post = Request::instance()->param();

        $result = $this->callSoaErp('post','/b2b_tour/updateB2bTourOptions',$post);

        return $result;
    }

    public function updateB2bTourSettingAjax()
    {
        $post = Request::instance()->param();

        $result = $this->callSoaErp('post','/b2b_tour/updateB2bTourSetting',$post);

        return $result;
    }

    public function getB2bTourGeneralAjax()
    {
        $post = Request::instance()->param();

        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourGeneral',$post);

        return $result;
    }

    public function getB2bTourDatesAjax()
    {
        $post = Request::instance()->param();

        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourDates',$post);

        return $result;
    }

    public function getB2bTourItineraryAjax()
    {
        $post = Request::instance()->param();

        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourItinerary',$post);

        return $result;
    }

    public function getB2bTourRoomAjax()
    {
        $post = Request::instance()->param();

        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourRoom',$post);

        return $result;
    }


    public function getB2bTourTransferAjax()
    {
        $post = Request::instance()->param();

        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourTransfer',$post);

        return $result;
    }

    public function getB2bTourCommissionAjax()
    {
        $post = Request::instance()->param();

        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourCommission',$post);

        return $result;
    }

    public function getB2bTourOptionsAjax()
    {
        $post = Request::instance()->param();

        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourOptions',$post);

        return $result;
    }

    public function getB2bTourSettingAjax()
    {
        $post = Request::instance()->param();

        $result = $this->callSoaErp('post','/b2b_tour/getB2bTourSetting',$post);

        return $result;
    }



    /************************************************************************************************/

    public function showB2BHotelManage()
    {
        $params = Request::instance()->param();
        $params['page'] = $this->page();
        $params['page_size'] = $this->_page_size;
        $result = $this->callSoaErp('post','/source/getB2bHotel',$params);

        $this->getPageParams($result);

        //城市
        $country_result = $this->callSoaErp('post', '/system/getCountryCity', ['level'=>3]);
        $this->assign('CountryList',$country_result['data']);

        return $this->fetch('b2b_hotel_manage');
    }

    /**
     * 酒店新增页面
     */
    public function showB2BHotelAdd(){

        //城市
        $country_result = $this->callSoaErp('post', '/system/getCountryCity', ['level'=>3]);
        $this->assign('CountryList',$country_result['data']);
        return $this->fetch('b2b_hotel_add');
    }

    public function addB2BHotelAjax()
    {
        $params = Request::instance()->param();
        $params['status'] = 1;
        return $this->callSoaErp('post', '/source/addB2bHotel', $params);
    }


    public function showB2BHotelUpdate(){

        $params = Request::instance()->param();
        $params['company_id'] =  session('user')['company_id'];

        $result = $this->callSoaErp('post','/source/getB2bHotel',$params);

        $this->assign('info',$result['data'][0]);

        //城市
        $country_result = $this->callSoaErp('post', '/system/getCountryCity', ['level'=>3]);

        $this->assign('CountryList',$country_result['data']);

        return $this->fetch('b2b_hotel_update');
    }

    public function updateB2BHotelAjax()
    {
        $params = Request::instance()->param();

        $params['choose_company_id'] = session('user')['company_id'];
        $params['user_id']  = session('user')['user_id'];

        return $this->callSoaErp('post', '/source/updateB2bHotel', $params);
    }

    /**
     * showTourTypeManage
     *
     * b2b代售产品类型首页
     * @author shj
     * @return mixed
     * Date: 2019/11/7
     * Time: 15:32
     */
    public function showTourTypeManage(){

        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $tour_type_name = input("tour_type_name");
        $pid = input("pid");
        $status = input("status");

        $data['tour_type_name'] = $tour_type_name;
        $data['pid'] = $pid;
        if($status <> 2){
            $data['status'] = $status;
        }
        $data['company_id'] =  session('user')['company_id'];

        $result = $this->callSoaErp('post', '/btob/getTourType',$data);

        $where['tour_type_id'] = $pid;
        $p_id = $this->callSoaErp('post', '/btob/getOneTourType',$where);
        $this->assign('url_pid',$p_id['data']['pid']);

        $this->getPageParams($result);

        return $this->fetch('tour_type_manage');
    }

    /**
     * showTourTypeAdd
     *
     * 新增b2b代售产品页面
     * @author shj
     * @return mixed
     * Date: 2019/11/7
     * Time: 15:33
     */
    public function showTourTypeAdd(){

        $data['company_id'] = session('user')['company_id'];
        $data['status'] = 1;

        $cateList = $this->callSoaErp('post','/btob/getTourType',$data);
        if($cateList['data']){
            $this->assign('cateList',$cateList['data']);
        }

        //获取公司信息
        $data1 = ['status'=>1];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data1);
        $this->assign('company_result',$company_result['data']);

        return $this->fetch('tour_type_add');
    }

    /**
     * addTourTypeAjax
     *
     * 添加b2b代售产品数据
     * @author shj
     * @return array|bool|mixed|string
     * Date: 2019/11/7
     * Time: 15:33
     */
    public function addTourTypeAjax(){
        $tour_type_name = input("tour_type_name");
        $cn_name = input("cn_name");
        $pid = input("pid");
        $status = input("status");
        $user_id = session('user')['user_id'];
        $company_id = input("choose_company_id");
        $system_type = input("system_type");
        $data = [
            "tour_type_name"=>$tour_type_name,
            "cn_name"=>$cn_name,
            "pid"=>$pid,
            "status"=>$status,
            "user_id"=>$user_id,
            "choose_company_id"=>$company_id,
            "system_type"=>$system_type
        ];

        $result = $this->callSoaErp('post', '/btob/addTourType',$data);
        return   $result;
    }

    /**
     * showTourTypeEdit
     *
     * 修改b2b代售产品页面
     * @author shj
     * @return mixed
     * Date: 2019/11/7
     * Time: 15:34
     */
    public function showTourTypeEdit(){

        $tour_type_id = input("tour_type_id");

        $data = ["tour_type_id"=>$tour_type_id];
        $tour_type_result = $this->callSoaErp('post', '/btob/getOneTourType',$data);
        $this->assign('tour_type_result',$tour_type_result['data']);


        $data1['status'] = 1;
        $data1['company_id'] = session('user')['company_id'];
        $cateList = $this->callSoaErp('post','/btob/getTourType',$data1);
        $cateList = Help::toArrData($cateList['data'],$tour_type_result['data']['tour_type_id']);

        if($cateList){
            $this->assign('cateList',$cateList);
        }
        //获取公司信息
        $data2 = ['status'=>1];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data2);
        $this->assign('company_result',$company_result['data']);

        return $this->fetch('tour_type_edit');
    }

    /**
     * editRouteTypeAjax
     *
     * 修改b2b代售产品数据
     * @author shj
     * @return array|bool|mixed|string
     * Date: 2019/11/7
     * Time: 15:34
     */
    public function editTourTypeAjax(){

        $tour_type_id = input("tour_type_id");
        $tour_type_name = input("tour_type_name");
        $cn_name = input("cn_name");
        $pid = input("pid");
        $status = input("status");
        $user_id = session('user')['user_id'];
        $company_id = input("choose_company_id");
        $system_type = input("system_type");
        $data = [
            "tour_type_id"=>$tour_type_id,
            "tour_type_name"=>$tour_type_name,
            "cn_name"=>$cn_name,
            "pid"=>$pid,
            "status"=>$status,
            "user_id"=>$user_id,
            "choose_company_id"=>$company_id,
            "system_type"=>$system_type
        ];

        $result = $this->callSoaErp('post', '/btob/updateTourTypeByTourTypeId',$data);
        return   $result;
    }



    public function showCommissionManage(){

        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $name = input("name");
        $status = input("status");
        $data['name'] = $name;
        if($status <> 2){
            $data['status'] = $status;
        }
        $data['company_id'] =  session('user')['company_id'];

        $result = $this->callSoaErp('post', '/btob/getCommission',$data);

        $this->getPageParams($result);

        return $this->fetch('commission_table_manage');
    }


    public function showCommissionAdd(){

        //获取公司信息
        $data1 = ['status'=>1];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data1);
        $this->assign('company_result',$company_result['data']);

        return $this->fetch('commission_table_add');
    }


    public function addCommissionAjax(){
        $name = input("name");
        $order = input("order");
        $color = input("color");
        $status = input("status");
        $user_id = session('user')['user_id'];
        $company_id = input("choose_company_id") ? : session('user')['company_id'];
        $content = input("content");
        $data = [
            "name"=>$name,
            "order"=>$order,
            "color"=>$color,
            "status"=>$status,
            "user_id"=>$user_id,
            "choose_company_id"=>$company_id,
            "content"=>$content
        ];

        $result = $this->callSoaErp('post', '/btob/addCommission',$data);
        return   $result;
    }


    public function showCommissionEdit(){

        $commission_id = input("commission_id");

        $data = ["commission_id"=>$commission_id];
        $result = $this->callSoaErp('post', '/btob/getOneCommission',$data);
        $this->assign('result',$result['data']);

        //获取公司信息
        $data2 = ['status'=>1];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data2);
        $this->assign('company_result',$company_result['data']);

        return $this->fetch('commission_table_edit');
    }


    public function editCommissionAjax(){

        $commission_id = input("commission_id");
        $name = input("name");
        $order = input("order");
        $color = input("color");
        $status = input("status");
        $user_id = session('user')['user_id'];
        $company_id = input("choose_company_id") ? : session('user')['company_id'];
        $content = input("content");
        $data = [
            "commission_id" => $commission_id,
            "name"=>$name,
            "order"=>$order,
            "color"=>$color,
            "status"=>$status,
            "user_id"=>$user_id,
            "choose_company_id"=>$company_id,
            "content"=>$content
        ];

        $result = $this->callSoaErp('post', '/btob/updateCommissionByCommissionId',$data);
        return   $result;
    }
}