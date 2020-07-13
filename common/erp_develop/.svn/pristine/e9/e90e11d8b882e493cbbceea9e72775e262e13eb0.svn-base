<?php

namespace app\index\controller;

use Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;
class Source extends Base
{

    /**
     * 供应商显示页面
     */
    public function showSupplierManage(){
        //读取所有的供应商信息
//        $result = $this->callSoaErp('post', '/source/getSupplier');
//        $this->assign('data',$result['data']);
        //获取供应商类别
        $supplier_result = $this->callSoaErp('post', '/system/getSuppliertype');
        $this->assign('supplier_data_result',$supplier_result['data']);
	
        /*
        //获取国家信息
        $data = ['level'=>1];
        $country_s1_result = $this->callSoaErp('post', '/system/getCountry',$data);
        $this->assign('country_s1_result',$country_s1_result['data']);

        //获取城市信息
        $data = ['level'=>2];
        $country_s2_result = $this->callSoaErp('post', '/system/getCountry',$data);
        $this->assign('country_s2_result',$country_s2_result['data']);

        //获取地区信息
        $data = ['level'=>3];
        $country_s3_result = $this->callSoaErp('post', '/system/getCountry',$data);
        $this->assign('country_s3_result',$country_s3_result['data']);
		*/
        //获取所属分公司
        $company=[
            'status'=>1,
            'company_id'=>session('user')['company_id']

        ];
        $company_result = $this->callSoaErp('post', '/system/getCompany',$company);
        $this->assign('company_result',$company_result['data']);

        //搜索操作

        $data = [];
        $supplier_type_id = input("supplier_type_id");
        $supplier_name = input("supplier_name");
        $choose_company_id = input("choose_company_id");
        $status = input('status',0);

        if(!empty($supplier_type_id)){
            $data['supplier_type_id'] = $supplier_type_id;
        }
        if(!empty($supplier_name)){
            $data['supplier_name'] = $supplier_name;
        }
        if(!empty($choose_company_id)){
            $data['choose_company_id'] = $choose_company_id;
        }
        if($status>0){
            $data['status'] = $status;
        }
        $data=[
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
            'supplier_type_id'=>  $data['supplier_type_id'],
            'supplier_name'=>  $data['supplier_name'],
            'status'=>$data['status'],
            'choose_company_id'=>  $data['choose_company_id'],
        ];
        $data['company_id'] =  session('user')['company_id'];
        $result = $this->callSoaErp('post','/source/getSupplier',$data);
        $this->getPageParams($result);
        return $this->fetch('supplier_manage');
    }

    /**
     * 供应商显示新增页面
     */
    public function showSupplierAdd(){

        //获取上一个页面的URl
        $url = $_SERVER["HTTP_REFERER"];
        $url_data = explode("/",$url);
        $this->assign('url',$url_data[4]);

        //获取供应商类别
        $data = [
            'status'=>1

        ];
        $supplier_result = $this->callSoaErp('post', '/system/getSuppliertype');
        $this->assign('supplier_data_result',$supplier_result['data']);


        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        //获取城市3级联动信息
        $data1['status'] = 1;
        $country_result = $this->callSoaErp('post', '/system/getCountryCity',$data1);

        $this->assign('country_result',$country_result['data']);
        $this->assign('company_result',$company_result['data']);
        return $this->fetch('supplier_add');
    }

    /**
     * 供应商新增数据
     */
    public function addSupplierAjax(){
        $supplier_type_id = input("supplier_type_id");
        $supplier_name = input("supplier_name");
        $country_id = input("country_id");
        $level_name = input("level_name");
        $linkman = input("linkman");
        $address = input("address");
        $zip_code = input("zip_code");
        $phone = input("phone");
        $website = input("website");
        $fax = input("fax");
        $email = input("email");
        $remark = input("remark");
        $status = input("status");
        $user_id = session('user')['user_id'];
        $choose_company_id = input('choose_company_id');

        $data = [
            "supplier_type_id"=>$supplier_type_id,
            "supplier_name"=>$supplier_name,
            "country_id"=>$country_id,
            "level_name"=>$level_name,
            "linkman"=>$linkman,
            "address"=>$address,
            "zip_code"=>$zip_code,
            "phone"=>$phone,
            "website"=>$website,
            "fax"=>$fax,
            "email"=>$email,
            "remark"=>$remark,
            "status"=>$status,
            "user_id"=>$user_id,
            'choose_company_id'=>$choose_company_id
        ];
      
        $result = $this->callSoaErp('post', '/source/addSupplier',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }

    /**
     * 供应商修改页面
     */
    public function showSupplierEdit(){

        //获取上一个页面的URl
        $url = $_SERVER["HTTP_REFERER"];
        $url_data = explode("/",$url);
        $this->assign('url',$url_data[4]);

        //获取供应商类别
        $supplier_result = $this->callSoaErp('post', '/system/getSuppliertype');
        $this->assign('supplier_data_result',$supplier_result['data']);



        //读取相应供应商信息
        $data=["supplier_id"=>input("supplier_id")];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
		
        $this->assign('data',$result['data'][0]);

        //获取城市3级联动信息

        //$country_result = $this->callSoaErp('post', '/system/getCityTop',$data);

        //$data1['level'] = 2;
        $data1['status'] = 1;
        $country_result = $this->callSoaErp('post', '/system/getCountryCity',$data1);



        $supplier_type_id = input("supplier_type_id");
        if($supplier_type_id==5){
            $this->assign('button',$supplier_type_id);
        }
        //获取公司信息
        $data = [
            'status'=>1
        ];
        if(session('user')['company_id'] == 1){
            unset($data['company_id']);
        }
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        $this->assign('company_result',$company_result['data']);

        $this->assign('country_result',$country_result['data']);
        return $this->fetch('supplier_edit');

    }

    /**
     * 供应商修改数据
     */
    public function editSupplierAjax(){
        $supplier_id = input("supplier_id");
        $supplier_type_id = input("supplier_type_id");
        $supplier_name = input("supplier_name");
        $country_id = input("country_id");
        $level_name = input("level_name");
        $linkman = input("linkman");
        $address = input("address");
        $zip_code = input("zip_code");
        $phone = input("phone");
        $website = input("website");
        $fax = input("fax");
        $email = input("email");
        $remark = input("remark");
        $status = input("status");
        $user_id = session('user')['user_id'];
        $choose_company_id = input("choose_company_id");
        $data = [
            "supplier_id"=>$supplier_id,
            "supplier_type_id"=>$supplier_type_id,
            "supplier_name"=>$supplier_name,
            "country_id"=>$country_id,
            "level_name"=>$level_name,
            "linkman"=>$linkman,
            "address"=>$address,
            "zip_code"=>$zip_code,
            "phone"=>$phone,
            "website"=>$website,
            "fax"=>$fax,
            "email"=>$email,
            "remark"=>$remark,
            "status"=>$status,
            "user_id"=>$user_id,
            'choose_company_id'=>$choose_company_id
        ];
    //TODO
//        $data1['system_alert_event_id'] = 1;
//        $data1['supplier_type'] = $supplier_type_id;
//        $data1['resource_id'] = $supplier_id;
//        $data1['company_id'] = $choose_company_id;
//        $result1 = $this->callSoaErp('post', '/system_alert_event/addInStationLetterAndEmail',$data1);

        $result = $this->callSoaErp('post', '/source/updateSupplierBySupplierId',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }

    /**
     * 供应商显示详情
     */
    public function showSupplierInfo(){
        //获取上一个页面的URl
        $url = $_SERVER["HTTP_REFERER"];
        $url_data = explode("/",$url);
        $this->assign('url',$url_data[4]);

        //获取供应商类别
        $supplier_result = $this->callSoaErp('post', '/system/getSuppliertype');
        $this->assign('supplier_data_result',$supplier_result['data']);

        //获取国家信息
        $data = ['level'=>1];
        $country_s1_result = $this->callSoaErp('post', '/system/getCountry',$data);
        $this->assign('country_s1_result',$country_s1_result['data']);

        //获取城市信息
        $data = ['level'=>2];
        $country_s2_result = $this->callSoaErp('post', '/system/getCountry',$data);
        $this->assign('country_s2_result',$country_s2_result['data']);

        //获取地区信息
        $data = ['level'=>3];
        $country_s3_result = $this->callSoaErp('post', '/system/getCountry',$data);
        $this->assign('country_s3_result',$country_s3_result['data']);

        //读取相应供应商信息
        $data=["supplier_id"=>input("supplier_id")];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $this->assign('data',$result['data'][0]);

        $supplier_type_id = input("supplier_type_id");
        if($supplier_type_id==5){
            $this->assign('button',$supplier_type_id);
        }

        return $this->fetch('supplier_info');
    }

    /**
     * 供应商资源详情
     */
    public function showSupplierSource(){

        return $this->fetch('');
    }

    /**
     * 供应商多语言编辑页面
     */
    public function showSupplierEditLanguage(){
        $data['supplier_number'] = input('supplier_number');
        $language_result = $this->callSoaErp('post','/source/getSupplierLanguage',$data);

        $this->assign('language_result',$language_result['data']);
        return $this->fetch('supplier_edit_language');
    }
    /**
     * 供应商多语言编辑AJAX
     */
    public function supplierEditLanguageAjax(){
        $data =$this->input();

        $params['data']  = $data;
        $params['user_id'] = session('user')['user_id'];


        $country_language_result = $this->callSoaErp('post','/source/updateSupplierLanguageBySupplierLanguageId',$params);
        return $country_language_result;
    }

    /**
     * 地接社显示页面
     */
    public function showAgentManage(){

        //读取所有的地接信息
        $supplier_type_id = 1;
        $supplier_name = input("supplier_name");
        $supplier_number = input("supplier_number");
        $linkman = input("linkman");
        $status = input("status");
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
            'supplier_type_id'=>$supplier_type_id,
            'supplier_name'=>$supplier_name,
            'supplier_number'=>$supplier_number,
            'linkman'=>$linkman
        ];
        $data['supplier_id']=input("supplier_id");
        $data['company_id'] =  session('user')['company_id'];
        if($status>0){
            $data['status'] = $status;
        }
        $result = $this->callSoaErp('post','/source/getSupplier',$data);
        //$this->assign('data',$result['data']);
        $this->getPageParams($result);
        return $this->fetch('agent_manage');
    }

    /**
     * 地接社显示新增页面
     */
    public function showAgentAdd(){

        //获取供应商类别
        $data = ['status'=>1];
//        $supplier_result = $this->callSoaErp('post', '/system/getSuppliertype',$data);

        //获取公司信息
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        //获取城市3级联动信息
        $country_result = $this->callSoaErp('post', '/system/getCountryCity',$data);

        //$this->assign('supplier_data_result',$supplier_result['data']);
        $this->assign('country_result',$country_result['data']);
        $this->assign('company_result',$company_result['data']);
        return $this->fetch('agent_add');
    }

    /**
     * 地接社修改页面
     */
    public function showAgentEdit(){

        $data=["supplier_id"=>input("supplier_id")];
        $result = $this->callSoaErp('post', '/source/getOneSupplier',$data);
        $this->assign('data',$result['data']);

        //获取城市3级联动信息
        $data1['status'] = 1;
        $country_result = $this->callSoaErp('post', '/system/getCountryCity',$data1);

        //获取公司信息
        if(session('user')['company_id'] == 1){
            unset($data['company_id']);
        }
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data1);

        $this->assign('company_result',$company_result['data']);

        $this->assign('country_result',$country_result['data']);

        return $this->fetch('agent_edit');
    }

    /**
     * showAgentInfo
     *
     * 地接社详情显示页面
     * @author shj
     * @return mixed
     * Date: 2019/3/20
     * Time: 15:42
     */
    public function showAgentInfo(){

        //读取相应供应商信息
        $data=["supplier_id"=>input("supplier_id")];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $this->assign('data',$result['data'][0]);

        //获取国家信息
        $data = [
            'level'=>1,
            'country_id'=>$result['data'][0]['country_id']
        ];
        $country_s1_result = $this->callSoaErp('post', '/system/getCountry',$data);
        $this->assign('country_s1_result',$country_s1_result['data'][0]);

        //获取城市信息
        $data = [
            'level'=>2,
            'country_id'=>$result['data'][0]['province_id']
        ];
        $country_s2_result = $this->callSoaErp('post', '/system/getCountry',$data);
        $this->assign('country_s2_result',$country_s2_result['data'][0]);

        //获取地区信息
        $data = [
            'level'=>3,
            'country_id'=>$result['data'][0]['city_id']
        ];
        $country_s3_result = $this->callSoaErp('post', '/system/getCountry',$data);
        $this->assign('country_s3_result',$country_s3_result['data'][0]);


        return $this->fetch('agent_info');
    }

    /**
     * 地接社修改酒店数据
     */
    public function editAgentHotelAjax(){

        $room_name = input("room_name");
        $hotel_id = input("hotel_id");
        $normal_settlement_price = input("normal_settlement_price");
//        $adult_settlement_price = input("adult_settlement_price");
//        $child_bed_settlement_price = input("child_bed_settlement_price");
//        $old_settlement_price = input("old_settlement_price");
//        $child_settlement_price = input("child_settlement_price");
//        $single_settlement_price = input("single_settlement_price");
        $user_id = session('user')['user_id'];

        $data = [
            "room_name"=>$room_name,
            "hotel_id"=>$hotel_id,
            "normal_settlement_price"=>$normal_settlement_price,
//            "adult_settlement_price"=>$adult_settlement_price,
//            "child_bed_settlement_price"=>$child_bed_settlement_price,
//            "old_settlement_price"=>$old_settlement_price,
//            "child_settlement_price"=>$child_settlement_price,
//            "single_settlement_price"=>$single_settlement_price,
            "user_id"=>$user_id
        ];

        $result = $this->callSoaErp('post', '/source/updateHotelByHotelId',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 代理商修改用餐数据
     */
    public function editAgentDiningAjax(){
        $dining_name = input("dining_name");
        $dining_id = input("dining_id");
        $normal_settlement_price = input("normal_settlement_price");
        $user_id = session('user')['user_id'];

        $data = [
            "dining_name"=>$dining_name,
            "dining_id"=>$dining_id,
            "normal_settlement_price"=>$normal_settlement_price,
            "user_id"=>$user_id
        ];

        $result = $this->callSoaErp('post', '/source/updateDiningByDiningId',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 代理商修改用航班据
     */
    public function editAgentFlightAjax(){
        $flight_name = input("flight_name");
        $flight_id = input("flight_id");
        $normal_settlement_price = input("normal_settlement_price");
        $user_id = session('user')['user_id'];

        $data = [
            "flight_name"=>$flight_name,
            "flight_id"=>$flight_id,
            "normal_settlement_price"=>$normal_settlement_price,
            "user_id"=>$user_id
        ];

        $result = $this->callSoaErp('post', '/source/updateFlightByFlightId',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 代理商修改用邮轮数据
     */
    public function editAgentCruiseAjax(){
        $room_name = input("room_name");
        $cruise_id = input("cruise_id");
        $normal_settlement_price = input("normal_settlement_price");
        $user_id = session('user')['user_id'];

        $data = [
            "room_name"=>$room_name,
            "cruise_id"=>$cruise_id,
            "normal_settlement_price"=>$normal_settlement_price,
            "user_id"=>$user_id
        ];

        $result = $this->callSoaErp('post', '/source/updateCruiseByCruiseId',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 代理商修改用签证数据
     */
    public function editAgentVisaAjax(){
        $visa_name = input("visa_name");
        $visa_id = input("visa_id");
        $normal_settlement_price = input("normal_settlement_price");
        $user_id = session('user')['user_id'];

        $data = [
            "visa_name"=>$visa_name,
            "visa_id"=>$visa_id,
            "normal_settlement_price"=>$normal_settlement_price,
            "user_id"=>$user_id
        ];

        $result = $this->callSoaErp('post', '/source/updateVisaByVisaId',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 代理商修改用景点数据
     */
    public function editAgentScenicSpotAjax(){
        $scenic_spot_name = input("scenic_spot_name");
        $scenic_spot_id = input("scenic_spot_id");
        $normal_settlement_price = input("normal_settlement_price");
        $user_id = session('user')['user_id'];

        $data = [
            "scenic_spot_name"=>$scenic_spot_name,
            "scenic_spot_id"=>$scenic_spot_id,
            "normal_settlement_price"=>$normal_settlement_price,
            "user_id"=>$user_id
        ];

        $result = $this->callSoaErp('post', '/source/updateScenicSpotByScenicSpotId',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 代理商修改用车辆数据
     */
    public function editAgentVehicleSpotAjax(){
        $vehicle_name = input("vehicle_name");
        $vehicle_id = input("vehicle_id");
        $normal_settlement_price = input("normal_settlement_price");
        $user_id = session('user')['user_id'];

        $data = [
            "vehicle_name"=>$vehicle_name,
            "vehicle_id"=>$vehicle_id,
            "normal_settlement_price"=>$normal_settlement_price,
            "user_id"=>$user_id
        ];

        $result = $this->callSoaErp('post', '/source/updateVehicleByVehicleId',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 代理商修改导游数据
     */
    public function editAgentTourGuideAjax(){
        $tour_guide_name = input("tour_guide_name");
        $tour_guide_id = input("tour_guide_id");
        $normal_settlement_price = input("normal_settlement_price");
        $user_id = session('user')['user_id'];

        $data = [
            "tour_guide_name"=>$tour_guide_name,
            "tour_guide_id"=>$tour_guide_id,
            "normal_settlement_price"=>$normal_settlement_price,
            "user_id"=>$user_id
        ];

        $result = $this->callSoaErp('post', '/source/updateTourGuideByTourGuideId',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 代理商修改单项资源数据
     */
    public function editAgentSingleSourceAjax(){
        $single_source_name = input("single_source_name");
        $single_source_id = input("single_source_id");
        $normal_settlement_price = input("normal_settlement_price");
        $user_id = session('user')['user_id'];

        $data = [
            "single_source_name"=>$single_source_name,
            "single_source_id"=>$single_source_id,
            "normal_settlement_price"=>$normal_settlement_price,
            "user_id"=>$user_id
        ];

        $result = $this->callSoaErp('post', '/source/updateSingleSourceBySingleSourceId',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 代理商修改自费数据
     */
    public function editAgentOwnExpenseAjax(){
        $own_expense_name = input("own_expense_name");
        $own_expense_id = input("own_expense_id");
        $normal_settlement_price = input("normal_settlement_price");
        $user_id = session('user')['user_id'];

        $data = [
            "own_expense_name"=>$own_expense_name,
            "own_expense_id"=>$own_expense_id,
            "normal_settlement_price"=>$normal_settlement_price,
            "user_id"=>$user_id
        ];

        $result = $this->callSoaErp('post', '/source/updateOwnExpenseByOwnExpenseId',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 代理商资源详情
     */
//    public function showAgentSource(){
//        $supplier_id = input("supplier_id");
//        $supplier_type_id = input("supplier_type_id") ? : 2;
//        //读取相关酒店信息 2
//        $data = [
//            'page'=>$this->page(),
//            'page_size'=>$this->_page_size,
//            'supplier_id'=>$supplier_id,
//        ];
//        switch($supplier_type_id)
//        {
//            case 2:
//                $result = $this->callSoaErp('post', '/source/getHotel',$data);
//                break;
//            case 3:
//                $result = $this->callSoaErp('post', '/source/getDining',$data);
//                break;
//            case 4:
//                $result = $this->callSoaErp('post', '/source/getFlight',$data);
//                break;
//            case 5:
//                $result = $this->callSoaErp('post', '/source/getCruise',$data);
//                break;
//            case 6:
//                $result = $this->callSoaErp('post', '/source/getVisa',$data);
//                break;
//            case 7:
//                $result = $this->callSoaErp('post', '/source/getScenicSpot',$data);
//                break;
//            case 8:
//                $result = $this->callSoaErp('post', '/source/getVehicle',$data);
//                break;
//            case 9:
//                $result = $this->callSoaErp('post', '/source/getTourGuide',$data);
//                break;
//            case 10:
//                $result = $this->callSoaErp('post', '/source/getSingleSource',$data);
//                break;
//            case 11:
//                $result = $this->callSoaErp('post', '/source/getOwnExpense',$data);
//                break;
//        }
//
//        //$this->assign('data',$result['data']);
//        $this->getPageParams($result);
//
//        return $this->fetch('agent_sourcee');
//    }

//    public function showAgentSource1(){
//        $supplier_id = input("supplier_id");
//        $supplier_type_id = input("supplier_type_id") ? : 2;
//        //读取相关酒店信息 2
//        $data = [
//            'page'=>$this->page(),
//            'page_size'=>$this->_page_size,
//            'supplier_id'=>$supplier_id,
//        ];
//        switch($supplier_type_id)
//        {
//            case 2:
//                $result = $this->callSoaErp('post', '/source/getHotel',$data);
//                break;
//            case 3:
//                $result = $this->callSoaErp('post', '/source/getDining',$data);
//                break;
//            case 4:
//                $result = $this->callSoaErp('post', '/source/getFlight',$data);
//                break;
//            case 5:
//                $result = $this->callSoaErp('post', '/source/getCruise',$data);
//                break;
//            case 6:
//                $result = $this->callSoaErp('post', '/source/getVisa',$data);
//                break;
//            case 7:
//                $result = $this->callSoaErp('post', '/source/getScenicSpot',$data);
//                break;
//            case 8:
//                $result = $this->callSoaErp('post', '/source/getVehicle',$data);
//                break;
//            case 9:
//                $result = $this->callSoaErp('post', '/source/getTourGuide',$data);
//                break;
//            case 10:
//                $result = $this->callSoaErp('post', '/source/getSingleSource',$data);
//                break;
//            case 11:
//                $result = $this->callSoaErp('post', '/source/getOwnExpense',$data);
//                break;
//        }
//
//        return $result;
//    }
    /**
     * 代理商资源详情
     */
    public function showAgentSource(){
        $supplier_id = input("supplier_id");
        //读取相关酒店信息 2
        $data = [
            'supplier_id'=>$supplier_id,
        ];
        $hotel_result = $this->callSoaErp('post', '/source/getHotel',$data);
        $this->assign('hotel_result',$hotel_result['data']);
        //$this->getPageParams($hotel_result);
        //读取相关用餐信息 3
        $dining_result = $this->callSoaErp('post', '/source/getDining',$data);
        $this->assign('dining_result',$dining_result['data']);

        //读取相关航班信息 4
        $flight_result = $this->callSoaErp('post', '/source/getFlight',$data);
        $this->assign('flight_result',$flight_result['data']);

        //读取相关邮轮信息 5
        $cruise_result = $this->callSoaErp('post', '/source/getCruise',$data);
        $this->assign('cruise_result',$cruise_result['data']);

        //读取相关签证信息 6
        $visa_result = $this->callSoaErp('post', '/source/getVisa',$data);
        $this->assign('visa_result',$visa_result['data']);

        //读取相关景点信息 7
        $scenic_spot_result = $this->callSoaErp('post', '/source/getScenicSpot',$data);
        $this->assign('scenic_spot_result',$scenic_spot_result['data']);

        //读取相车辆点信息 8
        $vehicle_result = $this->callSoaErp('post', '/source/getVehicle',$data);
        $this->assign('vehicle_result',$vehicle_result['data']);

        //读取相车导游信息 9
        $tour_guide_result = $this->callSoaErp('post', '/source/getTourGuide',$data);
        $this->assign('tour_guide_result',$tour_guide_result['data']);

        //读取相关单项资源 10
        $single_source_result = $this->callSoaErp('post', '/source/getSingleSource',$data);
        $this->assign('single_source_result',$single_source_result['data']);

        //读取相关自费 11
        $own_expense_result = $this->callSoaErp('post', '/source/getOwnExpense',$data);
        $this->assign('own_expense_result',$own_expense_result['data']);

        return $this->fetch('agent_source');
    }

    /**
     * 酒店显示页面
     */
    public function showHotelManage(){

        //读取所有的供应商信息
        $supplier_type_id = 2;

//        var_dump(session('user'));exit;

        //搜索
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $supplier_name = input("supplier_name");
        $status = input("status");
        $data['supplier_type_id']=$supplier_type_id;

        if(!empty($supplier_name)){
            $data['supplier_name'] = $supplier_name;
        }
        if($status>0){
            $data['status'] = $status;
        }
        $data['company_id'] =  session('user')['company_id'];
        $result = $this->callSoaErp('post','/source/getSupplier',$data);
        $this->getPageParams($result);

        return $this->fetch('hotel_manage');
    }

    /**
     * 酒店新增页面
     */
    public function showHotelAdd(){

        //读取所有的地接信息
        $supplier_type_id = 1;
        $agent_data = [
            'add' => 1,
            'company_id'=>session('user')['company_id'],
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1
        ];
        $result_agent = $this->callSoaErp('post', '/source/getSupplier',$agent_data);
        $this->assign('data_agent',$result_agent['data']);

        //读取所有的供应商信息
        $supplier_type_id = 2;
        $data = [
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);

        $arr = array_merge($result['data'],$result_agent['data']);

        $this->assign('data',$arr);
        //获取公司信息
        $data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_id=input('currency_id');
        $currency_data = [
            'status'=>1,
            'currency_id'=>$currency_id
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);
        return $this->fetch('hotel_add');
    }

    /**
     * 酒店添加数据
     */
    public function addHotelAjax(){
        $supplier_id = input("supplier_id");
        $status = input("status");
        $agent_id = input("agent_id");
        $level_name = input("level_name");
        $room_name = input("room_name");
        $room_type = input("room_type");
        $guest_number = input("guest_number");
        $free_wifi = input("free_wifi");
        $room_area = input("room_area");
        $floor = input("floor");
        $is_add_bed = input("is_add_bed");
        $smoke_treatment = input("smoke_treatment");
        $normal_price = input("normal_price");
        $normal_settlement_price = input("normal_settlement_price");
//        $normal_retail_price = input("normal_retail_price");
//        $adult_price = input("adult_price");
//        $adult_settlement_price = input("adult_settlement_price");
//        $adult_retail_price = input("adult_retail_price");
//        $child_bed_price = input("child_bed_price");
//        $child_bed_settlement_price = input("child_bed_settlement_price");
//        $child_bed_retail_price = input("child_bed_retail_price");
//        $old_price = input("old_price");
//        $old_settlement_price = input("old_settlement_price");
//        $old_retail_price = input("old_retail_price");
//        $child_price = input("child_price");
//        $child_settlement_price = input("child_settlement_price");
//        $child_retail_price = input("child_retail_price");
//        $single_price = input("single_price");
//        $single_settlement_price = input("single_settlement_price");
//        $single_retail_price = input("single_retail_price");
//        $remark = input("remark");
        $user_id = session('user')['user_id'];
        $choose_company_id = input('choose_company_id');
        $payment_currency_type = input('payment_currency_type');

        $data = [
            "supplier_id"=>$supplier_id,
            "status"=>$status,
            "agent_id"=>$agent_id,
            "level_name"=>$level_name,
            "room_name"=>$room_name,
            "room_type"=>$room_type,
            "guest_number"=>$guest_number,
            "free_wifi"=>$free_wifi,
            "room_area"=>$room_area,
            "floor"=>$floor,
            "is_add_bed"=>$is_add_bed,
            "smoke_treatment"=>$smoke_treatment,
            "normal_price"=>$normal_price,
            "normal_settlement_price"=>$normal_settlement_price,
//            "normal_retail_price"=>$normal_retail_price,
//            "adult_price"=>$adult_price,
//            "adult_settlement_price"=>$adult_settlement_price,
//            "adult_retail_price"=>$adult_retail_price,
//            "child_bed_price"=>$child_bed_price,
//            "child_bed_settlement_price"=>$child_bed_settlement_price,
//            "child_bed_retail_price"=>$child_bed_retail_price,
//            "old_price"=>$old_price,
//            "old_settlement_price"=>$old_settlement_price,
//            "old_retail_price"=>$old_retail_price,
//            "child_price"=>$child_price,
//            "child_settlement_price"=>$child_settlement_price,
//            "child_retail_price"=>$child_retail_price,
//            "single_price"=>$single_price,
//            "single_settlement_price"=>$single_settlement_price,
//            "single_retail_price"=>$single_retail_price,
//            "remark"=>$remark,
            "user_id"=>$user_id,
            'choose_company_id'=>$choose_company_id,
            'payment_currency_type'=>$payment_currency_type
        ];

        $result = $this->callSoaErp('post', '/source/addHotel',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }

    /**
     * 酒店修改页面
     */
    public function showHotelEdit(){

        //读取所有的地接信息
        $supplier_type_id = 1;
        $agent_data = ['supplier_type_id'=>$supplier_type_id];

        $result_agent = $this->callSoaErp('post', '/source/getSupplier',$agent_data);
        $this->assign('data_agent',$result_agent['data']);

        //读取所有的供应商信息
        $supplier_type_id = 2;
        $data = ['supplier_type_id'=>$supplier_type_id];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);

        $arr = array_merge($result['data'],$result_agent['data']);

        $this->assign('data',$arr);

        //获取当前的酒店信息
        $hotel_id = input("hotel_id");
        $hotel_data = [
            "hotel_id"=>$hotel_id
        ];
        $hotel_result = $this->callSoaErp('post', '/source/getHotel',$hotel_data);
        $this->assign('hotel_result',$hotel_result['data'][0]);
        //获取公司信息
        $data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);

        return $this->fetch('hotel_edit');
    }

    /**
     * 酒店修改数据
     */
    public function editHotelAjax(){
        $hotel_id = input("hotel_id");
        $supplier_id = input("supplier_id");
        $status = input("status");
//        $agent_id = input("agent_id");
        $level_name = input("level_name");
        $room_name = input("room_name");
        $room_type = input("room_type");
        $guest_number = input("guest_number");
        $free_wifi = input("free_wifi");
        $room_area = input("room_area");
        $floor = input("floor");
        $is_add_bed = input("is_add_bed");
        $smoke_treatment = input("smoke_treatment");
        $normal_price = input("normal_price");
        $normal_settlement_price = input("normal_settlement_price");
//        $normal_retail_price = input("normal_retail_price");
//        $adult_price = input("adult_price");
//        $adult_settlement_price = input("adult_settlement_price");
//        $adult_retail_price = input("adult_retail_price");
//        $child_bed_price = input("child_bed_price");
//        $child_bed_settlement_price = input("child_bed_settlement_price");
//        $child_bed_retail_price = input("child_bed_retail_price");
//        $old_price = input("old_price");
//        $old_settlement_price = input("old_settlement_price");
//        $old_retail_price = input("old_retail_price");
//        $child_price = input("child_price");
//        $child_settlement_price = input("child_settlement_price");
//        $child_retail_price = input("child_retail_price");
//        $single_price = input("single_price");
//        $single_settlement_price = input("single_settlement_price");
//        $single_retail_price = input("single_retail_price");
        $remark = input("remark");
        $user_id = session('user')['user_id'];
        $choose_company_id = input("choose_company_id");
        $payment_currency_type = input('payment_currency_type');

        $data = [
            "hotel_id" =>$hotel_id,
            "supplier_id"=>$supplier_id,
            "status"=>$status,
//            "agent_id"=>$agent_id,
            "level_name"=>$level_name,
            "room_name"=>$room_name,
            'room_type'=>$room_type,
            "guest_number"=>$guest_number,
            "free_wifi"=>$free_wifi,
            "room_area"=>$room_area,
            "floor"=>$floor,
            "is_add_bed"=>$is_add_bed,
            "smoke_treatment"=>$smoke_treatment,
            "normal_price"=>$normal_price,
            "normal_settlement_price"=>$normal_settlement_price,
//            "normal_retail_price"=>$normal_retail_price,
//            "adult_price"=>$adult_price,
//            "adult_settlement_price"=>$adult_settlement_price,
//            "adult_retail_price"=>$adult_retail_price,
//            "child_bed_price"=>$child_bed_price,
//            "child_bed_settlement_price"=>$child_bed_settlement_price,
//            "child_bed_retail_price"=>$child_bed_retail_price,
//            "old_price"=>$old_price,
//            "old_settlement_price"=>$old_settlement_price,
//            "old_retail_price"=>$old_retail_price,
//            "child_price"=>$child_price,
//            "child_settlement_price"=>$child_settlement_price,
//            "child_retail_price"=>$child_retail_price,
//            "single_price"=>$single_price,
//            "single_settlement_price"=>$single_settlement_price,
//            "single_retail_price"=>$single_retail_price,
            "remark"=>$remark,
            "user_id"=>$user_id,
            'choose_company_id'=>$choose_company_id,
            'payment_currency_type'=>$payment_currency_type
        ];

        $result = $this->callSoaErp('post', '/source/updateHotelByHotelId',$data);

        if($result){
            $data1['system_alert_event_id'] = 1;
            $data1['supplier_type'] = 2;
            $data1['resource_id'] = $hotel_id;
            $data1['company_id'] = $choose_company_id;
            if(session('user')['company_id']==1){
                $data1['choose_company_id'] = $choose_company_id;
            }
            $result1 = $this->callSoaErp('post', '/system_alert_event/addInStationLetterAndEmail',$data1);
        }

        return   $result;//['code' => '400', 'msg' => $data];
    }

    /**
     * 酒店详情页面
     */
    public function showHotelInfo(){

        //读取所有的供应商信息
        $supplier_type_id = 2;
        $data = ['supplier_type_id'=>$supplier_type_id];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $this->assign('data',$result['data']);
        //获取当前的酒店信息
        $hotel_id = input("hotel_id");
        $hotel_data = [
            "hotel_id"=>$hotel_id
        ];
        $hotel_result = $this->callSoaErp('post', '/source/getHotel',$hotel_data);
        $this->assign('hotel_result',$hotel_result['data'][0]);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);

        return $this->fetch('hotel_info');
    }

    /**
     * 酒店资源页面
     */
    public function showHotelSource(){

        //读取对应的酒店信息
        $supplier_id = input("supplier_id");
        $hotel = ['supplier_id'=>$supplier_id];
        $result = $this->callSoaErp('post', '/source/getHotel',$hotel);
        $this->assign('data',$result['data']);

        //对应的supplier_id
        $this->assign('supplier_id',input("supplier_id"));

        //搜索
        $sid = input('get.id');
        $this->getSupplierName($sid);
        $setHotelManage = Session::get('setHotelManage');
        $this->assign('setHotelManage',$setHotelManage);
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $status = input("status");
//        $level_name = input("level_name");
        $room_name = input("room_name");
        $supplier_id = input("supplier_id");
//        if(!empty($level_name)){
//            $data['level_name'] = $level_name;
//        }
        if(!empty($room_name)){
            $data['room_name'] = $room_name;
        }
        if(!empty($supplier_id)){
            $data['supplier_id'] = $supplier_id;
        }
        if($status>0){
            $data['status'] = $status;
        }
        if(!empty($sid)){
            $data['supplier_id'] = $sid;
        }

        $data['company_id'] =  session('user')['company_id'];
        $result = $this->callSoaErp('post','/source/getHotel',$data);
        $this->getPageParams($result);

        return $this->fetch('hotel_source');
    }
    /**
     * 酒店多语言编辑页面
     */
    public function showHotelEditLanguage(){
        $data['source_number'] = input('source_number');
        $language_result = $this->callSoaErp('post','/source/getHotelLanguage',$data);

        $this->assign('language_result',$language_result['data']);
        return $this->fetch('hotel_edit_language');
    }
    /**
     * 酒店多语言编辑AJAX
     */
    public function HotelEditLanguageAjax(){
        $data =$this->input();

        $params['data']  = $data;
        $params['user_id'] = session('user')['user_id'];


        $country_language_result = $this->callSoaErp('post','/source/updateHotelLanguageByHotelLanguageId',$params);
        return $country_language_result;
    }

    /**
     * 用餐显示页面
     */
    public function showDiningManage(){

        //读取所有的供应商信息
        $supplier_type_id = 3;
        $data = ['supplier_type_id'=>$supplier_type_id];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $this->assign('data',$result['data']);

        //搜索
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $supplier_name = input("supplier_name");
        $status = input("status");

        $data['supplier_type_id']=$supplier_type_id;

        if(!empty($supplier_name)){
            $data['supplier_name'] = $supplier_name;
        }
        if($status>0){
            $data['status'] = $status;
        }
        $data['company_id'] =  session('user')['company_id'];
        $data_result = $this->callSoaErp('post','/source/getSupplier',$data);
        $this->getPageParams($data_result);

        return $this->fetch('dining_manage');
    }

    /**
     * 用餐新增页面
     */
    public function showDiningAdd(){

        //读取所有的地接信息
        $supplier_type_id = 1;
        $agent_data = [
            'add' => 1,
            'company_id'=>session('user')['company_id'],
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1
        ];
        $result_agent = $this->callSoaErp('post', '/source/getSupplier',$agent_data);
        $this->assign('data_agent',$result_agent['data']);

        //获取上一个页面的URl
        $url = $_SERVER["HTTP_REFERER"];
        $url_data = explode("/",$url);
        $this->assign('url',$url_data[4]);

        //读取所有的供应商信息
        $supplier_type_id = 3;
        $data = [
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $arr = array_merge($result['data'],$result_agent['data']);

        $this->assign('data',$arr);
        //获取公司信息
        $data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);
        $this->assign('currency_data_result',$currency_data_result['data']);
        return $this->fetch('dining_add');
    }

    /**
     * 用餐新增数据
     */
    public function addDiningAjax(){
        $supplier_id = input("supplier_id");
        $status = input("status");
        $agent_id = input("agent_id");
        $level_name = input("level_name");
        $dining_name = input("dining_name");
        $standard_type = input("standard_type");
        $normal_price = input("normal_price");
        $normal_settlement_price = input("normal_settlement_price");
//        $normal_retail_price = input("normal_retail_price");
//        $adult_price = input("adult_price");
//        $adult_settlement_price = input("adult_settlement_price");
//        $adult_retail_price = input("adult_retail_price");
//        $child_bed_price = input("child_bed_price");
//        $child_bed_settlement_price = input("child_bed_settlement_price");
//        $child_bed_retail_price = input("child_bed_retail_price");
//        $old_price = input("old_price");
//        $old_settlement_price = input("old_settlement_price");
//        $old_retail_price = input("old_retail_price");
//        $child_price = input("child_price");
//        $child_settlement_price = input("child_settlement_price");
//        $child_retail_price = input("child_retail_price");
//        $single_price = input("single_price");
//        $single_settlement_price = input("single_settlement_price");
//        $single_retail_price = input("single_retail_price");
        $remark = input("remark");
        $user_id = session('user')['user_id'];
        $choose_company_id = input('choose_company_id');
        $payment_currency_type = input('payment_currency_type');

        $data = [
            "supplier_id"=>$supplier_id,
            "status"=>$status,
            "agent_id"=>$agent_id,
            "level_name"=>$level_name,
            "dining_name"=>$dining_name,
            "standard_type"=>$standard_type,
            "normal_price"=>$normal_price,
            "normal_settlement_price"=>$normal_settlement_price,
//            "normal_retail_price"=>$normal_retail_price,
//            "adult_price"=>$adult_price,
//            "adult_settlement_price"=>$adult_settlement_price,
//            "adult_retail_price"=>$adult_retail_price,
//            "child_bed_price"=>$child_bed_price,
//            "child_bed_settlement_price"=>$child_bed_settlement_price,
//            "child_bed_retail_price"=>$child_bed_retail_price,
//            "old_price"=>$old_price,
//            "old_settlement_price"=>$old_settlement_price,
//            "old_retail_price"=>$old_retail_price,
//            "child_price"=>$child_price,
//            "child_settlement_price"=>$child_settlement_price,
//            "child_retail_price"=>$child_retail_price,
//            "single_price"=>$single_price,
//            "single_settlement_price"=>$single_settlement_price,
//            "single_retail_price"=>$single_retail_price,
            "remark"=>$remark,
            "user_id"=>$user_id,
            "choose_company_id"=>$choose_company_id,
            'payment_currency_type'=>$payment_currency_type
        ];

        $result = $this->callSoaErp('post', '/source/addDining',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }

    /**
     * 用餐修改页面
     */
    public function showDiningEdit(){

        //读取所有的地接信息
        $supplier_type_id = 1;
        $agent_data = ['supplier_type_id'=>$supplier_type_id];
        $result_agent = $this->callSoaErp('post', '/source/getSupplier',$agent_data);
        $this->assign('data_agent',$result_agent['data']);

        //读取所有的供应商信息
        $supplier_type_id = 3;
        $data = ['supplier_type_id'=>$supplier_type_id];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $arr = array_merge($result['data'],$result_agent['data']);

        $this->assign('data',$arr);

        //获取当前的用餐信息
        $dining_id = input("dining_id");
        $dining_data = [
            "dining_id"=>$dining_id
        ];
        $dining_result = $this->callSoaErp('post', '/source/getDining',$dining_data);
        $this->assign('dining_result',$dining_result['data'][0]);
        //搜索操作
        $data = [];
        $level_name = input("level_name");
        $dining_name = input("dining_name");
        $status = input("status");

        $data['supplier_id']=input("supplier_id");

        if(!empty($level_name)){
            $data['level_name'] = $level_name;
        }
        if(!empty($dining_name)){
            $data['dining_name'] = $dining_name;
        }
        if($status>0){
            $data['status'] = $status;
        }

        $data = $this->callSoaErp('post','/source/getDining',$data);
        $this->assign('data',$data['data']);
        //获取公司信息
        $data = [
            'status'=>1,
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);

        return $this->fetch('dining_edit');
    }

    /**
     * 用餐修改数据
     */
    public function editDiningAjax(){
        $dining_id = input("dining_id");
        $supplier_id = input("supplier_id");
        $status = input("status");
//        $agent_id = input("agent_id");
        $level_name = input("level_name");
        $dining_name = input("dining_name");
        $standard_type = input("standard_type");
        $normal_price = input("normal_price");
        $normal_settlement_price = input("normal_settlement_price");
        // $normal_retail_price = input("normal_retail_price");
        // $adult_price = input("adult_price");
        // $adult_settlement_price = input("adult_settlement_price");
        // $adult_retail_price = input("adult_retail_price");
        // $child_bed_price = input("child_bed_price");
        // $child_bed_settlement_price = input("child_bed_settlement_price");
        // $child_bed_retail_price = input("child_bed_retail_price");
        // $old_price = input("old_price");
        // $old_settlement_price = input("old_settlement_price");
        // $old_retail_price = input("old_retail_price");
        // $child_price = input("child_price");
        // $child_settlement_price = input("child_settlement_price");
        // $child_retail_price = input("child_retail_price");
        // $single_price = input("single_price");
        // $single_settlement_price = input("single_settlement_price");
        // $single_retail_price = input("single_retail_price");
        $remark = input("remark");
        $user_id = session('user')['user_id'];
        $payment_currency_type = input('payment_currency_type');
        $choose_company_id = input("choose_company_id");

        $data = [
            "dining_id"=>$dining_id,
            "supplier_id"=>$supplier_id,
            "status"=>$status,
//            "agent_id"=>$agent_id,
            "level_name"=>$level_name,
            "dining_name"=>$dining_name,
            "standard_type"=>$standard_type,
            "normal_price"=>$normal_price,
            "normal_settlement_price"=>$normal_settlement_price,
            // "normal_retail_price"=>$normal_retail_price,
            // "adult_price"=>$adult_price,
            // "adult_settlement_price"=>$adult_settlement_price,
            // "adult_retail_price"=>$adult_retail_price,
            // "child_bed_price"=>$child_bed_price,
            // "child_bed_settlement_price"=>$child_bed_settlement_price,
            // "child_bed_retail_price"=>$child_bed_retail_price,
            // "old_price"=>$old_price,
            // "old_settlement_price"=>$old_settlement_price,
            // "old_retail_price"=>$old_retail_price,
            // "child_price"=>$child_price,
            // "child_settlement_price"=>$child_settlement_price,
            // "child_retail_price"=>$child_retail_price,
            // "single_price"=>$single_price,
            // "single_settlement_price"=>$single_settlement_price,
            // "single_retail_price"=>$single_retail_price,
            "remark"=>$remark,
            "user_id"=>$user_id,
            'choose_company_id'=>$choose_company_id,
            "payment_currency_type"=>$payment_currency_type

        ];
        $result = $this->callSoaErp('post', '/source/updateDiningByDiningId',$data);

        if($result){
            $data1['system_alert_event_id'] = 1;
            $data1['supplier_type'] = 3;
            $data1['resource_id'] = $dining_id;
            $data1['company_id'] = $choose_company_id;
            if(session('user')['company_id']==1){
                $data1['choose_company_id'] = $choose_company_id;
            }
            $result1 = $this->callSoaErp('post', '/system_alert_event/addInStationLetterAndEmail',$data1);
        }

        return   $result;//['code' => '400', 'msg' => $data];
    }

    /**
     * 用餐详情页面
     */
    public function showDiningInfo(){

        //读取所有的供应商信息
        $supplier_type_id = 3;
        $data = ['supplier_type_id'=>$supplier_type_id];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $this->assign('data',$result['data']);

        //获取当前的航班信息
        $dining_id = input("dining_id");
        $dining_data = [
            "dining_id"=>$dining_id
        ];
        $dining_result = $this->callSoaErp('post', '/source/getDining',$dining_data);
        $this->assign('dining_result',$dining_result['data'][0]);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);

        return $this->fetch('dining_info');
    }

    /**
     * 用餐资源页面
     */
    public function showDiningSource(){

        //读取对应的用餐信息
        //对应的supplier_id
        $this->assign('supplier_id',input("supplier_id"));
        //搜索
        $sid = input('get.id');
        $this->getSupplierName($sid);
        $setDiningManage = Session::get('setDiningManage');
        $this->assign('setDiningManage',$setDiningManage);
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $status = input("status");
//        $level_name = input("level_name");
        $dining_name = input("dining_name");
        $supplier_id = input("supplier_id");
//        if(!empty($level_name)){
//            $data['level_name'] = $level_name;
//        }
        if(!empty($dining_name)){
            $data['dining_name'] = $dining_name;
        }
        if(!empty($supplier_id)){
            $data['supplier_id'] = $supplier_id;
        }
        if($status>0){
            $data['status'] = $status;
        }
        if(!empty($sid)){
            $data['supplier_id'] = $sid;
        }
        $data['company_id'] =  session('user')['company_id'];

        $result = $this->callSoaErp('post','/source/getDining',$data);


        $this->getPageParams($result);
        return $this->fetch('dining_source');
    }

    /**
     * 用餐多语言编辑页面
     */
    public function showDiningEditLanguage(){
        $data['source_number'] = input('source_number');
        $language_result = $this->callSoaErp('post','/source/getDiningLanguage',$data);

        $this->assign('language_result',$language_result['data']);
        return $this->fetch('dining_edit_language');
    }
    /**
     * 用餐多语言编辑AJAX
     */
    public function diningEditLanguageAjax(){
        $data =$this->input();

        $params['data']  = $data;
        $params['user_id'] = session('user')['user_id'];


        $language_result = $this->callSoaErp('post','/source/updateDiningLanguageByDiningLanguageId',$params);


        return $language_result;
    }

    /**
     * 航班显示页面
     */
    public function showFlightManage(){

        //读取所有的供应商信息
        $supplier_type_id = 4;
        $data = ['supplier_type_id'=>$supplier_type_id];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $this->assign('data',$result['data']);

        //搜索
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $supplier_name = input("supplier_name");
        $status = input("status");
        $data['supplier_type_id']=$supplier_type_id;

        if(!empty($supplier_name)){
            $data['supplier_name'] = $supplier_name;
        }
        if($status>0){
            $data['status'] = $status;
        }
        $data['company_id'] =  session('user')['company_id'];
        $data_result = $this->callSoaErp('post','/source/getSupplier',$data);
        $this->getPageParams($data_result);
        return $this->fetch('flight_manage');
    }

    /**
     * 航班新增页面
     */
    public function showFlightAdd(){

        //读取所有的地接信息
        $supplier_type_id = 1;
        $agent_data = [
            'add' => 1,
            'company_id'=>session('user')['company_id'],
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1
        ];
        $result_agent = $this->callSoaErp('post', '/source/getSupplier',$agent_data);
        $this->assign('data_agent',$result_agent['data']);

        //读取所有的供应商信息
        $supplier_type_id = 4;
        $data = [
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1,
            'company_id'=>session('user')['company_id']

        ];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $arr = array_merge($result['data'],$result_agent['data']);

        $this->assign('data',$arr);

        //获取所有航班信息
        //获取所有航班信息
//        $data = [
//            'status'=>1
//        ];
//        $flight_result =  $this->callSoaErp('post', '/source/getFlight',$data);
//        $this->assign('data',$result['data']);

        //获取出发地、到达地信息
        $data1['level'] = 3;
        $country_result = $this->callSoaErp('post', '/system/getCountryCity',$data1);
        $this->assign('country_s3_result',$country_result['data']);

        //获取支付币种
        $currency_result = $this->callSoaErp('post', '/system/getCurrency',$data);
        $this->assign('currency_result',$currency_result['data']);
        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);

        return $this->fetch('flight_add');
    }

    /**
     * 航班新增数据
     */
    public function addFlightAjax(){
        $supplier_id = input("supplier_id");
        $status = input("status");
        $agent_id = input("agent_id");
        $flight_number = input("flight_number");
        $airplane_type_name = input("airplane_type_name");
        $shipping_space = input("shipping_space");
        $begin_country_id = input("begin_country_id");
        $end_country_id = input("end_country_id");
        $normal_price = input("normal_price");
        $normal_settlement_price = input("normal_settlement_price");
//        $normal_retail_price = input("normal_retail_price");
//        $adult_price = input("adult_price");
//        $adult_settlement_price = input("adult_settlement_price");
//        $adult_retail_price = input("adult_retail_price");
//        $child_bed_price = input("child_bed_price");
//        $child_bed_settlement_price = input("child_bed_settlement_price");
//        $child_bed_retail_price = input("child_bed_retail_price");
//        $old_price = input("old_price");
//        $old_settlement_price = input("old_settlement_price");
//        $old_retail_price = input("old_retail_price");
//        $child_price = input("child_price");
//        $child_settlement_price = input("child_settlement_price");
//        $child_retail_price = input("child_retail_price");
//        $single_price = input("single_price");
//        $single_settlement_price = input("single_settlement_price");
//        $single_retail_price = input("single_retail_price");
//        $remark = input("remark");
        $user_id = session('user')['user_id'];
        $choose_company_id = input('choose_company_id');
        $payment_currency_type = input('payment_currency_type');

        $data = [
            "supplier_id"=>$supplier_id,
            "status"=>$status,
            "agent_id"=>$agent_id,
            "flight_number"=>$flight_number,
            "airplane_type_name"=>$airplane_type_name,
            "shipping_space"=>$shipping_space,
            "begin_country_id"=>$begin_country_id,
            "end_country_id"=>$end_country_id,
            "normal_price"=>$normal_price,
            "normal_settlement_price"=>$normal_settlement_price,
//            "normal_retail_price"=>$normal_retail_price,
//            "adult_price"=>$adult_price,
//            "adult_settlement_price"=>$adult_settlement_price,
//            "adult_retail_price"=>$adult_retail_price,
//            "child_bed_price"=>$child_bed_price,
//            "child_bed_settlement_price"=>$child_bed_settlement_price,
//            "child_bed_retail_price"=>$child_bed_retail_price,
//            "old_price"=>$old_price,
//            "old_settlement_price"=>$old_settlement_price,
//            "old_retail_price"=>$old_retail_price,
//            "child_price"=>$child_price,
//            "child_settlement_price"=>$child_settlement_price,
//            "child_retail_price"=>$child_retail_price,
//            "single_price"=>$single_price,
//            "single_settlement_price"=>$single_settlement_price,
//            "single_retail_price"=>$single_retail_price,
//            "remark"=>$remark,
            "user_id"=>$user_id,
            "choose_company_id"=>$choose_company_id,
            'payment_currency_type'=>$payment_currency_type
        ];

        $result = $this->callSoaErp('post', '/source/addFlight',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }

    /**
     * 航班修改页面
     */
    public function showFlightEdit(){

        //读取所有的地接信息
        $supplier_type_id = 1;
        $agent_data = ['supplier_type_id'=>$supplier_type_id];
        $result_agent = $this->callSoaErp('post', '/source/getSupplier',$agent_data);
        $this->assign('data_agent',$result_agent['data']);

        //读取所有的供应商信息
        $supplier_type_id = 4;
        $data = ['supplier_type_id'=>$supplier_type_id];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $arr = array_merge($result['data'],$result_agent['data']);

        $this->assign('data',$arr);

        //获取出发地、到达地信息
        $data1['level'] = 3;
        $country_result = $this->callSoaErp('post', '/system/getCountryCity',$data1);
        $this->assign('country_s3_result',$country_result['data']);

        //获取支付币种
        $currency_result = $this->callSoaErp('post', '/system/getCurrency',$data);
        $this->assign('currency_result',$currency_result['data']);

        //获取当前的航班信息
        $flight_id = input("flight_id");
        $flight_data = [
            "flight_id"=>$flight_id
        ];
        $flight_result = $this->callSoaErp('post', '/source/getFlight',$flight_data);
        $this->assign('flight_result',$flight_result['data'][0]);
        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);

        return $this->fetch('flight_edit');
    }

    /**
     * 航班修改数据
     */
    public function editFlightAjax(){

        $flight_id = input("flight_id");
        $supplier_id = input("supplier_id");
        $status = input("status");
//        $agent_id = input("agent_id");
        $flight_number = input("flight_number");
        $airplane_type_name = input("airplane_type_name");
        $shipping_space = input("shipping_space");
        $begin_country_id = input("begin_country_id");
        $end_country_id = input("end_country_id");
        $normal_price = input("normal_price");
        $normal_settlement_price = input("normal_settlement_price");
//        $normal_retail_price = input("normal_retail_price");
//        $adult_price = input("adult_price");
//        $adult_settlement_price = input("adult_settlement_price");
//        $adult_retail_price = input("adult_retail_price");
//        $child_bed_price = input("child_bed_price");
//        $child_bed_settlement_price = input("child_bed_settlement_price");
//        $child_bed_retail_price = input("child_bed_retail_price");
//        $old_price = input("old_price");
//        $old_settlement_price = input("old_settlement_price");
//        $old_retail_price = input("old_retail_price");
//        $child_price = input("child_price");
//        $child_settlement_price = input("child_settlement_price");
//        $child_retail_price = input("child_retail_price");
//        $single_price = input("single_price");
//        $single_settlement_price = input("single_settlement_price");
//        $single_retail_price = input("single_retail_price");
//        $remark = input("remark");
        $user_id = session('user')['user_id'];
        $choose_company_id = input("choose_company_id");
        $payment_currency_type = input('payment_currency_type');

        $data = [
            "flight_id"=>$flight_id,
            "supplier_id"=>$supplier_id,
            "status"=>$status,
//            "agent_id"=>$agent_id,
            "flight_number"=>$flight_number,
            "shipping_space"=>$shipping_space,
            "airplane_type_name"=> $airplane_type_name,
            "begin_country_id"=>$begin_country_id,
            "end_country_id"=>$end_country_id,
            "normal_price"=>$normal_price,
            "normal_settlement_price"=>$normal_settlement_price,
//            "normal_retail_price"=>$normal_retail_price,
//            "adult_price"=>$adult_price,
//            "adult_settlement_price"=>$adult_settlement_price,
//            "adult_retail_price"=>$adult_retail_price,
//            "child_bed_price"=>$child_bed_price,
//            "child_bed_settlement_price"=>$child_bed_settlement_price,
//            "child_bed_retail_price"=>$child_bed_retail_price,
//            "old_price"=>$old_price,
//            "old_settlement_price"=>$old_settlement_price,
//            "old_retail_price"=>$old_retail_price,
//            "child_price"=>$child_price,
//            "child_settlement_price"=>$child_settlement_price,
//            "child_retail_price"=>$child_retail_price,
//            "single_price"=>$single_price,
//            "single_settlement_price"=>$single_settlement_price,
//            "single_retail_price"=>$single_retail_price,
//            "remark"=>$remark,
            "user_id"=>$user_id,
            'choose_company_id'=>$choose_company_id,
            "payment_currency_type"=>$payment_currency_type
        ];
        $result = $this->callSoaErp('post', '/source/updateFlightByFlightId',$data);

        if($result){
            $data1['system_alert_event_id'] = 1;
            $data1['supplier_type'] = 4;
            $data1['resource_id'] = $flight_id;
            $data1['company_id'] = $choose_company_id;
            if(session('user')['company_id']==1){
                $data1['choose_company_id'] = $choose_company_id;
            }
            $result1 = $this->callSoaErp('post', '/system_alert_event/addInStationLetterAndEmail',$data1);
        }

        return   $result;//['code' => '400', 'msg' => $data];
    }

    /**
     * 航班详情页面
     */
    public function showFlightInfo(){

        //读取所有的供应商信息
        $supplier_type_id = 4;
        $data = ['supplier_type_id'=>$supplier_type_id];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $this->assign('data',$result['data']);

        //获取出发地、到达地信息
        $data = ['level'=>3];
        $country_s3_result = $this->callSoaErp('post', '/system/getCountry',$data);
        $this->assign('country_s3_result',$country_s3_result['data']);

        //获取支付币种
        $currency_result = $this->callSoaErp('post', '/system/getCurrency',$data);
        $this->assign('currency_result',$currency_result['data']);

        //获取当前的航班信息
        $flight_id = input("flight_id");
        $flight_data = [
            "flight_id"=>$flight_id
        ];
        $flight_result = $this->callSoaErp('post', '/source/getFlight',$flight_data);
        $this->assign('flight_result',$flight_result['data'][0]);
        //获取货币
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);

        return $this->fetch('flight_info');
    }

    /**
     * 航班资源页面
     */
    public function showFlightSource(){

        //读取对应的航班信息
        $supplier_id = input("supplier_id");
        $data = ['supplier_id'=>$supplier_id];
        $result = $this->callSoaErp('post', '/source/getFlight',$data);
        $this->assign('data',$result['data']);

        //对应的supplier_id
        $this->assign('supplier_id',input("supplier_id"));

        //获取当前供应商信息
        $data_supplier_id = ['supplier_id'=>$supplier_id];
        $result_supplier_id = $this->callSoaErp('post', '/source/getSupplier',$data_supplier_id);
        $this->assign("supplier_name",$result_supplier_id['data'][0]['supplier_name']);

        //获取出发地、到达地信息
        $data = ['level'=>3];
        $country_s3_result = $this->callSoaErp('post', '/system/getCountry',$data);
        $this->assign('country_s3_result',$country_s3_result['data']);

        //搜索操作
        $sid = input('get.id');
        $this->getSupplierName($sid);
        $setFlightManage = Session::get('setFlightManage');
        $this->assign('setFlightManage',$setFlightManage);
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
//        $begin_country_id = input("begin_country_id");
//        $end_country_id = input("end_country_id");
//        $shipping_space = input("shipping_space");
        $flight_number = input("flight_number");
        $status = input("status");
        $supplier_id=input("supplier_id");

//        if(!empty($begin_country_id)){
//            $data['begin_country_id'] = $begin_country_id;
//        }
//        if(!empty($end_country_id)){
//            $data['end_country_id'] = $end_country_id;
//        }
//        if(!empty($shipping_space)){
//            $data['shipping_space'] = $shipping_space;
//        }
        if(!empty($flight_number)){
            $data['flight_number'] = $flight_number;
        }
        if(!empty($supplier_id)){
            $data['supplier_id'] = $supplier_id;
        }
        if($status>0){
            $data['status'] = $status;
        }

        if(!empty($sid)){
            $data['supplier_id'] = $sid;
        }
        $data['company_id'] =  session('user')['company_id'];
        $result = $this->callSoaErp('post','/source/getFlight',$data);
        $this->getPageParams($result);
        return $this->fetch('flight_source');
    }
    /**
     * 航班多语言编辑页面
     */
    public function showFlightEditLanguage(){
        $data['source_number'] = input('source_number');

        $language_result = $this->callSoaErp('post','/source/getFlightLanguage',$data);

        $this->assign('language_result',$language_result['data']);
        return $this->fetch('flight_edit_language');
    }
    /**
     * 航班多语言编辑AJAX
     */
    public function flightEditLanguageAjax(){

        $data =$this->input();

        $params['data']  = $data;
        $params['user_id'] = session('user')['user_id'];


        $language_result = $this->callSoaErp('post','/source/updateFLightLanguageByFlightLanguageId',$params);


        return $language_result;
    }

    /**
     * 邮轮显示页面
     * Hugh 18-08-02
     */
    public function showCruisesManage(){

        $setCruisesManage =  Session::get('setCruisesManage');
        $this->assign('setCruisesManage',$setCruisesManage);
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $data['supplier_type_id'] = 5;// 邮轮 5
        if(!empty($setCruisesManage['search_Id'])){
            $data['supplier_id'] = $setCruisesManage['search_Id'];
        }
        if(!empty($setCruisesManage['search_Name'])){
            $data['supplier_name'] = $setCruisesManage['search_Name'];
        }
        if(!empty($setCruisesManage['search_status'])){
            $data['status'] = $setCruisesManage['search_status'];
        }
        $data['company_id'] =  session('user')['company_id'];
        $SupplierData = $this->callSoaErp('post','/source/getSupplier',$data);
        $this->getPageParams($SupplierData);


        return $this->fetch('cruises_manage');
    }

    /**
     * 邮轮资源页面
     * Hugh 18-08-02
     */
    public function showCruisesSourceManage(){
        $sid = input('get.id');
        $this->getSupplierName($sid);
        $setCruisesSourceManage = Session::get('setCruisesSourceManage');
        $this->assign('setCruisesSourceManage',$setCruisesSourceManage);
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        if($sid){
            $data['supplier_id'] = $sid;
        }
        if(!empty($setCruisesSourceManage['search_Id'])){
            $data['cruise_id'] = $setCruisesSourceManage['search_Id'];
        }
        if(!empty($setCruisesSourceManage['search_Name'])){
            $data['cruise_name'] = $setCruisesSourceManage['search_Name'];
        }
        if(!empty($setCruisesSourceManage['search_status'])){
            $data['status'] = $setCruisesSourceManage['search_status'];
        }
        $data['company_id'] =  session('user')['company_id'];
        $CruiseData = $this->callSoaErp('post','/source/getCruise',$data);
        $this->getPageParams($CruiseData);
        return $this->fetch('cruises_source_manage');
    }

    /**
     * 邮轮资源新增页面
     * Hugh 18-08-02
     */
    public function showCruisesSourceAdd(){
        $sid = input('get.id');
        $this->getSupplierName($sid);
        //获取代理商
        $supplier_type_id = 1;
        $agent_data = [
            'add' => 1,
            'company_id'=>session('user')['company_id'],
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1
        ];
        $agentAr = $this->callSoaErp('post', '/source/getSupplier',$agent_data);
        $this->assign('agentAr',$agentAr['data']);
//        $agentAr = $this->getSupplierBySupplierTypeId(1);
//        $this->assign('agentAr',$agentAr);
        //邮轮供应商
        $supplier_type_id = 5;
        $cruise_data = [
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $CruisesAr = $this->callSoaErp('post', '/source/getSupplier',$cruise_data);
        $arr = array_merge($agentAr['data'],$CruisesAr['data']);

        $this->assign('CruisesAr',$arr);
        //$this->assign('CruisesAr',$CruisesAr['data']);
//        $CruisesAr = $this->getSupplierBySupplierTypeId(5);
//        $this->assign('CruisesAr',$CruisesAr);
        //获取货币
        $data['status'] = 1;
        $CurrencyAr = $this->getCurrency($data);
        $this->assign('currencyAr',$CurrencyAr);
        unset($data);
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);
        return $this->fetch('cruises_source_add');
    }

    /***
     * 异步添加邮轮资源
     * Hugh 18-08-07
     */
    public function addCruisesSourceAjax(){
        $data = Request::instance()->param();

        $data['user_id'] = session('user')['user_id'];
        $result =  $this->callSoaErp('post','/source/addCruise',$data);
        return $result;
    }

    /**
     * 邮轮修改页面
     * Hugh 18-08-07
     */
    public function showCruisesSourceEdit(){
        $id = input('get.id');
        $data['cruise_id'] = $id;
        $CruiseData = $this->callSoaErp('post','/source/getCruise',$data);
        if(!empty($CruiseData['data'])){
            $this->assign('CruiseAr',$CruiseData['data'][0]);
            $this->getSupplierName($CruiseData['data'][0]['supplier_id']);
        }
        //获取代理商
        $agentAr = $this->getSupplierBySupplierTypeId(1);

        //邮轮供应商
        $CruisesAr = $this->getSupplierBySupplierTypeId(5);
        $supplierList = array_merge($agentAr,$CruisesAr);

        $this->assign('supplierList',$supplierList);
        //获取货币
        $data['status'] = 1;
        $CurrencyAr = $this->getCurrency($data);
        $this->assign('currencyAr',$CurrencyAr);
        unset($data);
        // 获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        $this->assign('company_result',$company_result['data']);

        $this->assign('data_agent',$agentAr);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);
        return $this->fetch('cruises_source_edit');
    }

    /***
     * 异步修改邮轮资源
     * Hugh 18-08-07
     */
    public function editCruisesSourceAjax(){
        $data = Request::instance()->param();
        $choose_company_id = input("choose_company_id");
        $data['user_id'] = session('user')['user_id'];
        $data['cruise_id'] = input('get.id');
        $result =  $this->callSoaErp('post','/source/updateCruiseByCruiseId',$data);
        if($result){
            $data1['system_alert_event_id'] = 1;
            $data1['supplier_type'] = 5;
            $data1['resource_id'] = $data['cruise_id'];
            $data1['company_id'] = $choose_company_id;
            if(session('user')['company_id']==1){
                $data1['choose_company_id'] = $choose_company_id;
            }
            $result1 = $this->callSoaErp('post', '/system_alert_event/addInStationLetterAndEmail',$data1);
        }
        return $result;
    }


    /**
     * 邮轮详情页面
     * Hugh 18-08-02
     */
    public function showCruisesSourceInfo(){
        $id = input('get.id');
        $data['cruise_id'] = $id;
        $CruiseData = $this->callSoaErp('post','/source/getCruise',$data);
        if(!empty($CruiseData['data'])){
            $this->assign('CruiseAr',$CruiseData['data'][0]);
            $this->getSupplierName($CruiseData['data'][0]['supplier_id']);
        }
        //获取代理商
        $agentAr = $this->getSupplierBySupplierTypeId(1);

        //邮轮供应商
        $CruisesAr = $this->getSupplierBySupplierTypeId(5);
        $supplierList = array_merge($agentAr,$CruisesAr);

        $this->assign('supplierList',$supplierList);
        //获取货币
        $data['status'] = 1;
        $CurrencyAr = $this->getCurrency($data);
        $this->assign('currencyAr',$CurrencyAr);
        unset($data);
        // 获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        $this->assign('company_result',$company_result['data']);

        $this->assign('data_agent',$agentAr);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);

        return $this->fetch('cruises_source_info');

    }

    /**
     * 邮轮列表搜索
     * Hugh 18-08-02
     */
    public function setCruisesManage(){
        Session::set('setCruisesManage',$_POST);
        $this->redirect('/source/showCruisesManage');
    }
    /***
     * 邮轮列表搜索清除
     * Hugh 18-08-02
     */
    public function clearCruisesManage(){
        Session::delete('setCruisesManage');
        $this->redirect('/source/showCruisesManage');
    }

    /****
     * 邮轮资源搜索
     * Hugh 18-08-02
     */
    public function setCruisesSourceManage(){
        Session::set('setCruisesSourceManage',$_POST);
        $this->redirect("/source/showCruisesSourceManage?id={$_GET['id']}");
    }
    /****
     * 邮轮资源搜索清除
     * Hugh 18-08-02
     */
    public function clearCruisesSourceManage(){
        Session::delete('setCruisesSourceManage');
        $this->redirect("/source/showCruisesSourceManage?id={$_GET['id']}");
    }
    /**
     * 邮轮多语言编辑页面
     */
    public function showCruiseEditLanguage(){
        $data['source_number'] = input('source_number');
        $language_result = $this->callSoaErp('post','/source/getCruiseLanguage',$data);

        $this->assign('language_result',$language_result['data']);
        return $this->fetch('cruises_edit_language');
    }
    /**
     * 邮轮多语言编辑AJAX
     */
    public function cruiseEditLanguageAjax(){

        $data =$this->input();

        $params['data']  = $data;
        $params['user_id'] = session('user')['user_id'];


        $country_language_result = $this->callSoaErp('post','/source/updateCruiseLanguageByCruiseLanguageId',$params);
        return $country_language_result;
    }


    /**
     * 签证显示页面
     * Hugh 18-08-07
     */
    public function showVisaManage(){
        $data = [
            'page'=>$this->page(),
            'supplier_type_id'=>6,
            'page_size'=>$this->_page_size,
        ];
        $setShowVisaManage = Session::get('setShowVisaManage');
        if(!empty($setShowVisaManage['search_Id'])){
            $data['supplier_id'] = $setShowVisaManage['search_Id'];
        }
        if(!empty($setShowVisaManage['search_Name'])){
            $data['supplier_name'] = $setShowVisaManage['search_Name'];
        }
        if(!empty($setShowVisaManage['search_status'])){
            $data['status'] = $setShowVisaManage['search_status'];
        }
        $data['company_id'] =  session('user')['company_id'];
        $supplierData = $this->callSoaErp('post','/source/getSupplier',$data);
        $this->getPageParams($supplierData);

        $this->assign('setShowVisaManage',$setShowVisaManage);
        return $this->fetch('visa_manage');
    }

    /**
     * 搜索签证
     */
    public function setShowVisaManage(){
        Session::set('setShowVisaManage',$_POST);
        $this->redirect('/source/showVisaManage');
    }
    /****
     * 搜索签证清除
     * Hugh 18-08-02
     */
    public function clearVisaManage(){
        Session::delete('setShowVisaManage');
        $this->redirect('/source/showVisaManage');
    }


    /*****
     *签证资源列表
     * HUGH 18-08-02
     */
    public function showVisaSourceManage(){
        $sid = input('get.id');
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $this->getSupplierName($sid);
        if($sid){
            $data['supplier_id'] = $sid;
        }
        $getVisaSourceManage = Session::get('getVisaSourceManage');

        if(!empty($getVisaSourceManage['search_Id'])){
            $data['visa_id'] = $getVisaSourceManage['search_Id'];
        }
        if(!empty($getVisaSourceManage['search_Name'])){
            $data['visa_name'] = $getVisaSourceManage['search_Name'];
        }
        if(!empty($getVisaSourceManage['search_status'])){
            $data['status'] = $getVisaSourceManage['search_status'];
        }
        $data['company_id'] =  session('user')['company_id'];
        $VisaSourceData = $this->callSoaErp('post','/source/getVisa',$data);
        $this->getPageParams($VisaSourceData);
        $this->assign('getVisaSourceManage',$getVisaSourceManage);
        return $this->fetch('visa_source_manage');
    }

    /**
     * 搜索签证资源
     * Hugh 18-08-07
     */
    public function getVisaSourceManage(){
        Session::set('getVisaSourceManage',$_POST);
        $this->redirect("/source/showVisaSourceManage?id={$_GET['id']}");
    }
    /**
     * 搜索签证资源清除
     * Hugh 18-08-07
     */
    public function clearVisaSourceManage(){
        Session::delete('getVisaSourceManage');
        $this->redirect("/source/showVisaSourceManage?id={$_GET['id']}");
    }


    /**
     * 签证资源新增页面
     * Hugh 18-08-07
     */
    public function showVisaSourceAdd(){
        $sid = input('get.id');
        $this->getSupplierName($sid);
        //获取代理商
        $supplier_type_id = 1;
        $agent_data = [
            'add' => 1,
            'company_id'=>session('user')['company_id'],
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1
        ];
        $agentAr = $this->callSoaErp('post', '/source/getSupplier',$agent_data);
        $this->assign('agentAr',$agentAr['data']);
//        $agentAr = $this->getSupplierBySupplierTypeId(1);
//        $this->assign('agentAr',$agentAr);
        //签证供应商
        $supplier_type_id = 6;
        $visa_data = [
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $VisaSourceAr = $this->callSoaErp('post', '/source/getSupplier',$visa_data);

        $arr = array_merge($VisaSourceAr['data'],$agentAr['data']);

        $this->assign('VisaSourceAr',$arr);
//        $VisaSourceAr = $this->getSupplierBySupplierTypeId(6);
//        $this->assign('VisaSourceAr',$VisaSourceAr);
        //获取货币
        $data['status'] = 1;
        $CurrencyAr = $this->getCurrency($data);
        $this->assign('currencyAr',$CurrencyAr);
        unset($data);
        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);
        return $this->fetch('visa_source_add');
    }

    /**
     * 异步添加签证资源
     * Hugh 2018-08-07
     */
    public function addVisaSourceManage(){
        $data = Request::instance()->param();
       
        $data['user_id'] = session('user')['user_id'];
        $result =  $this->callSoaErp('post','/source/addVisa',$data);
        return $result;
    }


    /***
     * 文件上传
     * Hugh 18-08-02
     */
    public function uploadVisa(){
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/visa');
            if($info){
                $ar['code'] = 0;
                $ar['src'] = '/uploads/visa/'.$info->getSaveName();
                echo json_encode($ar);
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }

    /**
     * 签证资源修改页面
     * HUGH 18-08-07
     */
    public function showVisaSourceEdit(){
        $id = input("get.id");
        $data['visa_id'] = $id;
        $VisaData = $this->callSoaErp('post','/source/getVisa',$data);
        if(!empty($VisaData['data'])){
            $this->getSupplierName($VisaData['data'][0]['supplier_id']);
            $this->assign('VisaAr',$VisaData['data'][0]);
        }
        unset($data);

        //获取代理商
        $agentAr = $this->getSupplierBySupplierTypeId(1);
        //签证供应商
        $VisaSourceAr = $this->getSupplierBySupplierTypeId(6);
        $SupplierAr = array_merge($agentAr,$VisaSourceAr);
        $this->assign('SupplierAr',$SupplierAr);
        //获取货币
        $data['status'] = 1;
        $CurrencyAr = $this->getCurrency($data);
        $this->assign('currencyAr',$CurrencyAr);
        unset($data);
        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);
        $this->assign('company_result',$company_result['data']);
        $this->assign('data_agent',$agentAr);
        return $this->fetch('visa_source_edit');
    }

    /**
     * 异步修改签证资源
     * Hugh 18-08-07
     */
    public function editVisaSourceManage(){
        $data = Request::instance()->param();
        $choose_company_id = input("choose_company_id");
        $data['user_id'] = session('user')['user_id'];
        $data['visa_id'] = input('get.id');
        $result =  $this->callSoaErp('post','/source/updateVisaByVisaId',$data);

        if($result){
            $data1['system_alert_event_id'] = 1;
            $data1['supplier_type'] = 6;
            $data1['resource_id'] = $data['visa_id'];
            $data1['company_id'] = $choose_company_id;
            if(session('user')['company_id']==1){
                $data1['choose_company_id'] = $choose_company_id;
            }
            $result1 = $this->callSoaErp('post', '/system_alert_event/addInStationLetterAndEmail',$data1);
        }

        return $result;
    }

    /**
     * 签证资源详情页面
     */
    public function showVisaSourceInfo(){
        $id = input("get.id");
        $data['visa_id'] = $id;
        $VisaData = $this->callSoaErp('post','/source/getVisa',$data);
        if(!empty($VisaData['data'])){
            $this->getSupplierName($VisaData['data'][0]['supplier_id']);
            $this->assign('VisaAr',$VisaData['data'][0]);
        }
        unset($data);

        //获取代理商
        $agentAr = $this->getSupplierBySupplierTypeId(1);
        //签证供应商
        $VisaSourceAr = $this->getSupplierBySupplierTypeId(6);
        $SupplierAr = array_merge($agentAr,$VisaSourceAr);
        $this->assign('SupplierAr',$SupplierAr);
        //获取货币
        $data['status'] = 1;
        $CurrencyAr = $this->getCurrency($data);
        $this->assign('currencyAr',$CurrencyAr);
        unset($data);
        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);
        $this->assign('company_result',$company_result['data']);
        $this->assign('data_agent',$agentAr);
        return $this->fetch('visa_source_info');
    }

    /**
     * 签证多语言编辑页面
     */
    public function showVisaEditLanguage(){
        $data['source_number'] = input('source_number');
        $language_result = $this->callSoaErp('post','/source/getVisaLanguage',$data);

        $this->assign('language_result',$language_result['data']);
        return $this->fetch('visa_edit_language');
    }
    /**
     * 签证多语言编辑AJAX
     */
    public function visaEditLanguageAjax(){

        $data =$this->input();

        $params['data']  = $data;
        $params['user_id'] = session('user')['user_id'];


        $language_result = $this->callSoaErp('post','/source/updateVisaLanguageByVisaLanguageId',$params);
        return $language_result;
    }

    /**
     * 景点显示页面
     * Hugh 18-08-06
     */
    public function showScenicSpotManage(){
        $data = [
            'page'=>$this->page(),
            'supplier_type_id'=>7,
            'page_size'=>$this->_page_size,
        ];

        $setScenicSpotManage = Session::get('setScenicSpotManage');
        if(!empty($setScenicSpotManage['search_Id'])){
            $data['supplier_id'] = $setScenicSpotManage['search_Id'];
        }
        if(!empty($setScenicSpotManage['search_Name'])){
            $data['supplier_name'] = $setScenicSpotManage['search_Name'];
        }
        if(!empty($setScenicSpotManage['search_status'])){
            $data['status'] = $setScenicSpotManage['search_status'];
        }
        $data['company_id'] =  session('user')['company_id'];
        $SupplierAr =  $this->callSoaErp('post', '/source/getSupplier', $data);

        $this->getPageParams($SupplierAr);

        $this->assign('setScenicSpotManage',$setScenicSpotManage);
        return $this->fetch('/source/scenic_spot_manage');
    }

    /***
     * 搜索景点
     */
    public function setScenicSpotManage(){
        Session::set('setScenicSpotManage',$_POST);
        $this->redirect('/source/showScenicSpotManage');
    }
    /***
     * 搜索景点清除
     */
    public function clearScenicSpotManage(){
        Session::delete('setScenicSpotManage');
        $this->redirect('/source/showScenicSpotManage');
    }

    /***
     * 景点资源列表
     * Hugh 18-08-06
     */
    public function showScenicSpotSourceManage(){
        $sid = input('get.id');
        $this->getSupplierName($sid);
        //国家
        $data['level'] = 1;
        $CountryAr = $this->callSoaErp('post', '/system/getCountry', $data);
        $this->assign('Country',$CountryAr['data']);
        unset($dta);
        //城市
        $data['level'] = 3;
        $CityAr = $this->callSoaErp('post', '/system/getCountry', $data);
        $this->assign('CityAr',$CityAr['data']);
        unset($dta);

        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $setScenicSpotSourceManage = Session::get('setScenicSpotSourceManage');
        if($sid){
            $data['supplier_id'] = $sid;
        }
        if(!empty($setScenicSpotSourceManage['search_Id'])){
            $data['scenic_spot_id'] = $setScenicSpotSourceManage['search_Id'];
        }
        if(!empty($setScenicSpotSourceManage['search_Name'])){
            $data['scenic_spot_name'] = $setScenicSpotSourceManage['search_Name'];
        }
        if(!empty($setScenicSpotSourceManage['Owned_country'])){

        }
        if(!empty($setScenicSpotSourceManage['Owned_City'])){
            $data['country_id'] = $setScenicSpotSourceManage['Owned_City'];
        }
        if(!empty($setScenicSpotSourceManage['search_status'])){
            $data['status'] = $setScenicSpotSourceManage['search_status'];
        }
        $data['company_id'] =  session('user')['company_id'];
        $result = $this->callSoaErp('post','/source/getScenicSpot',$data);
        $this->getPageParams($result);
        $this->assign('setScenicSpotSourceManage',$setScenicSpotSourceManage);
        return $this->fetch('/source/scenic_spot_source_manage');
    }

    /***
     * 搜索景点资源
     */
    public function setScenicSpotSourceManage(){
        Session::set('setScenicSpotSourceManage',$_POST);
        $this->redirect("/source/showScenicSpotSourceManage?id={$_GET['id']}");
    }
    /***
     * 搜索景点资源清除
     */
    public function clearScenicSpotSourceManage(){
        Session::delete('setScenicSpotSourceManage');
        $this->redirect("/source/showScenicSpotSourceManage?id={$_GET['id']}");
    }
    /**
     * 景点多语言编辑页面
     */
    public function showScenicSpotEditLanguage(){
        $data['source_number'] = input('source_number');
        $language_result = $this->callSoaErp('post','/source/getScenicSpotLanguage',$data);

        $this->assign('language_result',$language_result['data']);
        return $this->fetch('scenic_spot_edit_language');
    }
    /**
     * 景点多语言编辑AJAX
     */
    public function scenicSpotEditLanguageAjax(){

        $data =$this->input();

        $params['data']  = $data;
        $params['user_id'] = session('user')['user_id'];


        $language_result = $this->callSoaErp('post','/source/updateScenicSpotLanguageByScenicSpotLanguageId',$params);
        return $language_result;
    }

    /***
     * 根据国家获取城市
     * Hugh 18-08-06
     */
    public function getCityAjax(){
        $cid = input('post.cid');
        $data = [];
        $CityAr = [];
        if(!empty($cid)){
            $data['country_id'] = $cid;
            $ar = $this->callSoaErp('post','/system/getCountryInfo',$data);
//           var_dump($ar);exit;
            foreach($ar['data']['province'] as $v){
                foreach ($v['city'] as $vv) {
                    $CityAr[] = $vv;
                }
            }
        }else{
            $data['level'] = 3;
            $CityArData = $this->callSoaErp('post', '/system/getCountry', $data);
            if(!empty($CityArData['data'])){
                $CityAr = $CityArData['data'];
            }
        }
        echo json_encode(['data'=>$CityAr]);
    }


    /**
     * 景点资源新增页面
     * Hugh 18-08-06
     */
    public function showScenicSpotSourceAdd(){
        $sid = input('get.id');
        $this->getSupplierName($sid);
        //获取代理商
        $supplier_type_id = 1;
        $agent_data = [
            'add' => 1,
            'company_id'=>session('user')['company_id'],
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1
        ];
        $agentAr = $this->callSoaErp('post', '/source/getSupplier',$agent_data);
        $this->assign('agentAr',$agentAr['data']);
//        $agentAr = $this->getSupplierBySupplierTypeId(1);
//        $this->assign('agentAr',$agentAr);
        //景点供应商
        $supplier_type_id = 7;
        $scenicspot_data = [
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $ScenicSpotSupplierAr = $this->callSoaErp('post', '/source/getSupplier',$scenicspot_data);

        $arr = array_merge($ScenicSpotSupplierAr['data'],$agentAr['data']);

        $this->assign('ScenicSpotSupplierAr',$arr);
//        $ScenicSpotSupplierAr = $this->getSupplierBySupplierTypeId(7);
//        $this->assign('ScenicSpotSupplierAr',$ScenicSpotSupplierAr);
        //获取货币
        $data['status'] = 1;
        $CurrencyAr = $this->getCurrency($data);
        $this->assign('currencyAr',$CurrencyAr);
        unset($data);
        
        //国家
        $data['level'] = 1;
        $CountryAr = $this->callSoaErp('post', '/system/getCountry', $data);
        $this->assign('Country',$CountryAr['data']);
        unset($data);
        //城市
        $data['level'] = 3;
        $CityAr = $this->callSoaErp('post', '/system/getCountry', $data);
        $this->assign('CityAr',$CityAr['data']);
        unset($data);
        
        // 获取公司信息
        $data = ['status'=>1];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        
        //获取城市3级联动信息
        $data1['status'] = 1;
        $country_result = $this->callSoaErp('post', '/system/getCountryCity',$data1);
        
        //获取币种
        $currency_data = ['status'=>1];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('country_result',$country_result['data']);
        $this->assign('company_result',$company_result['data']);
        $this->assign('currency_data_result',$currency_data_result['data']);


        return $this->fetch('scenic_spot_source_add');
    }

    /***
     * 异部添加景点资源
     * Hugh 18-08-06
     */
    public function addScenicSpotSourceAjax(){
        $data = Request::instance()->param();
        $data['user_id'] = session('user')['user_id'];

        $result =  $this->callSoaErp('post','/source/addScenicSpot',$data);
        return $result;
    }


    /**
     * 景点修改页面
     */
    public function showScenicSpotSourceEdit(){
        $id = input('get.id');
        $data['scenic_spot_id'] = $id;
        $ScenicSpotSourceData = $this->callSoaErp('post','/source/getScenicSpot',$data);
        unset($data);
        $country_id = 0;
        if(!empty($ScenicSpotSourceData['data'])){
            $this->assign('ScenicSpotSourceAr',$ScenicSpotSourceData['data'][0]);
            $this->getSupplierName($ScenicSpotSourceData['data'][0]['supplier_id']);
            $country_id =  $ScenicSpotSourceData['data'][0]['country_id'];
        }

        $agentAr = $this->getSupplierBySupplierTypeId(1);
        $ScenicSpotSupplierAr = $this->getSupplierBySupplierTypeId(7);
        $SupplierList = array_merge($agentAr,$ScenicSpotSupplierAr);
        $this->assign('SupplierList',$SupplierList);

        //获取货币
        $data['status'] = 1;
        $CurrencyAr = $this->getCurrency($data);
        $this->assign('currencyAr',$CurrencyAr);
        unset($data);
        //国家
        $data['level'] = 1;
        $CountryAr = $this->callSoaErp('post', '/system/getCountry', $data);
        $this->assign('Country',$CountryAr['data']);
        unset($dta);
        //城市
        $CityAr =  $this->getCityByCountryId($country_id);
        $this->assign('CityAr',$CityAr);
        // 获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        $this->assign('company_result',$company_result['data']);

        //获取代理商
        $agentAr = $this->getSupplierBySupplierTypeId(1);
        $this->assign('agentAr',$agentAr);
        //获取城市3级联动信息

        //$country_result = $this->callSoaErp('post', '/system/getCityTop',$data);
        $data1['status'] = 1;
        $country_result = $this->callSoaErp('post', '/system/getCountryCity',$data1);
        $this->assign('country_result',$country_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);
        return $this->fetch('scenic_spot_source_edit');
    }

    /***
     * 异步修改景点资源
     * Hugh 18-08-06
     */
    public function editScenicSpotSourceAjax(){
        $data = Request::instance()->param();
        $choose_company_id = input("choose_company_id");
        $data['user_id'] = session('user')['user_id'];
        $data['scenic_spot_id'] = $_GET['id'];

        $result =  $this->callSoaErp('post','/source/updateScenicSpotByScenicSpotId',$data);

        if($result){
            $data1['system_alert_event_id'] = 1;
            $data1['supplier_type'] = 7;
            $data1['resource_id'] = $data['scenic_spot_id'];
            $data1['company_id'] = $choose_company_id;
            if(session('user')['company_id']==1){
                $data1['choose_company_id'] = $choose_company_id;
            }
            $result1 = $this->callSoaErp('post', '/system_alert_event/addInStationLetterAndEmail',$data1);
        }

        return $result;
    }


    /**
     * 景点详情页面
     */
    public function showScenicSpotSourceInfo(){
        $id = input('get.id');
        $data['scenic_spot_id'] = $id;
        $ScenicSpotSourceData = $this->callSoaErp('post','/source/getScenicSpot',$data);
        unset($data);
        $country_id = 0;
        if(!empty($ScenicSpotSourceData['data'])){
            $this->assign('ScenicSpotSourceAr',$ScenicSpotSourceData['data'][0]);
            $this->getSupplierName($ScenicSpotSourceData['data'][0]['supplier_id']);
            $country_id =  $ScenicSpotSourceData['data'][0]['country_id'];
        }

        $agentAr = $this->getSupplierBySupplierTypeId(1);
        $ScenicSpotSupplierAr = $this->getSupplierBySupplierTypeId(7);
        $SupplierList = array_merge($agentAr,$ScenicSpotSupplierAr);

        $this->assign('SupplierList',$SupplierList);

        //获取货币
        $data['status'] = 1;
        $CurrencyAr = $this->getCurrency($data);
        $this->assign('currencyAr',$CurrencyAr);
        unset($data);
        //国家
        $data['level'] = 1;
        $CountryAr = $this->callSoaErp('post', '/system/getCountry', $data);
        $this->assign('Country',$CountryAr['data']);
        unset($dta);
        //城市
        $CityAr =  $this->getCityByCountryId($country_id);
        $this->assign('CityAr',$CityAr);
        // 获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        $this->assign('company_result',$company_result['data']);

        //获取代理商
        $agentAr = $this->getSupplierBySupplierTypeId(1);
        $this->assign('agentAr',$agentAr);
        //获取城市3级联动信息

        $country_result = $this->callSoaErp('post', '/system/getCityTop',$data);

        $this->assign('country_result',$country_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);

        return $this->fetch('scenic_spot_source_info');
    }

    /**
     * 根据国家ID获取城市数据
     * @param $country_id
     */
    public function getCityByCountryId($country_id){
        $data = [];
        $CityAr = [];
        if(!empty($country_id)){
            $data['country_id'] = $country_id;
            $ar = $this->callSoaErp('post','/system/getCountryInfo',$data);
//           var_dump($ar);exit;
            foreach($ar['data']['province'] as $v){
                foreach ($v['city'] as $vv) {
                    $CityAr[] = $vv;
                }
            }
        }else{
            $data['level'] = 3;
            $CityArData = $this->callSoaErp('post', '/system/getCountry', $data);
            if(!empty($CityArData['data'])){
                $CityAr = $CityArData['data'];
            }
        }
        unset($dta);
        return $CityAr;
    }

    /**
     * 车辆显示页面
     * Hugh 18-08-03
     */
    public function showVehicleManage(){
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
            'supplier_type_id'=>8,
        ];
        $setVehicleManage = Session::get('setVehicleManage');
        if(!empty($setVehicleManage['search_ID'])){
            $data['supplier_id'] = $setVehicleManage['search_ID'];
        }
        if(!empty($setVehicleManage['search_Name'])){
            $data['supplier_name'] = $setVehicleManage['search_Name'];
        }
        if(!empty($setVehicleManage['search_operation'])){
            //$data['supplier_id'] = $setTourGuideManage['search_operation'];
        }
        if(!empty($setVehicleManage['search_status'])){
            $data['status'] = $setVehicleManage['search_status'];
        }
        $data['company_id'] =  session('user')['company_id'];
        $data_result =  $this->callSoaErp('post', '/source/getSupplier', $data);
        $this->getPageParams($data_result);

        $this->assign('setVehicleManage',$setVehicleManage);
        return $this->fetch('vehicle_manage');
    }

    /***
     * 搜索车辆
     */
    public function setVehicleManage(){
        Session::set('setVehicleManage',$_POST);
        $this->redirect("/source/showVehicleManage");
    }
    public function clearVehicleManage(){
        Session::delete('setVehicleManage');
        $this->redirect("/source/showVehicleManage");
    }


    /***
     * 车辆资源
     * Hugh 18-08-03
     */
    public function showVehicleSourceManage(){
        $sid = input('get.id');
        $this->getSupplierName($sid);
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $setVehicleSourceManage = Session::get('setVehicleSourceManage');

        if(!empty($setVehicleSourceManage['search_ID'])){
            $data['vehicle_id'] = $setVehicleSourceManage['search_ID'];
        }
        if(!empty($setVehicleSourceManage['search_Name'])){
            $data['vehicle_name'] = $setVehicleSourceManage['search_Name'];
        }
        if(!empty($setVehicleSourceManage['search_status'])){
            $data['status'] = $setVehicleSourceManage['search_status'];
        }


        if(!empty($sid)){
            $data['supplier_id'] = $sid;
        }
        $data['company_id'] =  session('user')['company_id'];
        $result = $this->callSoaErp('post','/source/getVehicle',$data);
        $this->getPageParams($result);
        $this->assign('setVehicleSourceManage',$setVehicleSourceManage);

        return $this->fetch('vehicle_source_manage');
    }


    /***
     * 搜索车辆资源
     * Hugh 18-08-06
     */
    public function setVehicleSourceManage(){
        Session::set('setVehicleSourceManage',$_POST);
        $this->redirect("/source/showVehicleSourceManage?id={$_GET['id']}");
    }
    public function clearVehicleSourceManage(){
        Session::delete('setVehicleSourceManage');
        $this->redirect("/source/showVehicleSourceManage?id={$_GET['id']}");
    }


    /****
     * 异步添加车辆资源
     * Hugh 18-08-03
     */
    public function addVehicleSourceAjax(){
        $data = Request::instance()->param();
        $data['user_id'] = session('user')['user_id'];

        $result =  $this->callSoaErp('post','/source/addVehicle',$data);
        return $result;
    }

    /**
     * 车辆新增页面
     * Hugh 18-08-03
     */
    public function showVehicleSourceAdd(){
        $sid = input('get.id');
        $this->getSupplierName($sid);

        //获取代理商
        $supplier_type_id = 1;
        $agent_data = [
            'add' => 1,
            'company_id'=>session('user')['company_id'],
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1
        ];
        $agentAr = $this->callSoaErp('post', '/source/getSupplier',$agent_data);
        $this->assign('agentAr',$agentAr['data']);
//        $agentAr = $this->getSupplierBySupplierTypeId(1);
//        $this->assign('agentAr',$agentAr);

        //车辆供应商
        $supplier_type_id = 8;
        $vehicle_data = [
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $VehicleSupplierAr = $this->callSoaErp('post', '/source/getSupplier',$vehicle_data);

        $arr = array_merge($VehicleSupplierAr['data'],$agentAr['data']);

        $this->assign('VehicleSupplierAr',$arr);
//        $VehicleSupplierAr = $this->getSupplierBySupplierTypeId(8);
//        $this->assign('VehicleSupplierAr',$VehicleSupplierAr);
        //获取货币
        $data['status'] = 1;
        $CurrencyAr = $this->getCurrency($data);
        $this->assign('currencyAr',$CurrencyAr);
        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);


        return $this->fetch('vehicle_source_add');
    }

    /***
     * 异部修改车辆资源
     */
    public function editVehicleSourceAjax(){
        $data = Request::instance()->param();
        $choose_company_id = input("choose_company_id");
        $data['user_id'] = session('user')['user_id'];
        $data['vehicle_id'] = input('get.id');

        $result =  $this->callSoaErp('post','/source/updateVehicleByVehicleId',$data);

        if($result){
            $data1['system_alert_event_id'] = 1;
            $data1['supplier_type'] = 8;
            $data1['resource_id'] = $data['vehicle_id'];
            $data1['company_id'] = $choose_company_id;
            if(session('user')['company_id']==1){
                $data1['choose_company_id'] = $choose_company_id;
            }
            $result1 = $this->callSoaErp('post', '/system_alert_event/addInStationLetterAndEmail',$data1);
        }

        return $result;
    }

    /**
     * 车辆修改页面
     */
    public function showVehicleSourceEdit(){
        $vehicle_id = input('get.id'); //车辆ID
        $data['vehicle_id'] = $vehicle_id;
        $VehicleAr = $this->callSoaErp('post','/source/getVehicle',$data);
        if(!empty($VehicleAr['data'])){
            $this->assign('VehicleAr',$VehicleAr['data'][0]);
            $this->getSupplierName($VehicleAr['data'][0]['supplier_id']);
        }

        //获取代理商
        $agentAr = $this->getSupplierBySupplierTypeId(1);
        //车辆供应商
        $VehicleSupplierAr = $this->getSupplierBySupplierTypeId(8);

        $SupplierList = array_merge($agentAr,$VehicleSupplierAr);
        $this->assign('SupplierList',$SupplierList);

        //获取货币
        $data['status'] = 1;
        $CurrencyAr = $this->getCurrency($data);
        $this->assign('currencyAr',$CurrencyAr);
        //获取公司信息
        $data = [
            'status'=>1
        ];
        if(session('user')['company_id'] == 1){
            unset($data['company_id']);
        }
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);
        //获取代理商
        $agentAr = $this->getSupplierBySupplierTypeId(1);
        $this->assign('agentAr',$agentAr);
        return $this->fetch('vehicle_source_edit');
    }
    /**
     * 车辆详情页面
     */
    public function showVehicleSourceInfo(){
        $vehicle_id = input('get.id'); //车辆ID
        $data['vehicle_id'] = $vehicle_id;
        $VehicleAr = $this->callSoaErp('post','/source/getVehicle',$data);
        if(!empty($VehicleAr['data'])){
            $this->assign('VehicleAr',$VehicleAr['data'][0]);
            $this->getSupplierName($VehicleAr['data'][0]['supplier_id']);
        }

        //获取代理商
        $agentAr = $this->getSupplierBySupplierTypeId(1);
        //车辆供应商
        $VehicleSupplierAr = $this->getSupplierBySupplierTypeId(8);

        $SupplierList = array_merge($agentAr,$VehicleSupplierAr);
        $this->assign('SupplierList',$SupplierList);

        //获取货币
        $data['status'] = 1;
        $CurrencyAr = $this->getCurrency($data);
        $this->assign('currencyAr',$CurrencyAr);
        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);
        //获取代理商
        $agentAr = $this->getSupplierBySupplierTypeId(1);
        $this->assign('agentAr',$agentAr);
        return $this->fetch('vehicle_source_info');
    }
    /**
     * 车辆多语言编辑页面
     */
    public function showVehicleSpotEditLanguage(){
        $data['source_number'] = input('source_number');
        $language_result = $this->callSoaErp('post','/source/getVehicleLanguage',$data);

        $this->assign('language_result',$language_result['data']);
        return $this->fetch('vehicle_edit_language');
    }
    /**
     * 车辆多语言编辑AJAX
     */
    public function vehicleEditLanguageAjax(){

        $data =$this->input();

        $params['data']  = $data;
        $params['user_id'] = session('user')['user_id'];


        $language_result = $this->callSoaErp('post','/source/updateVehicleLanguageByVehicleLanguageId',$params);
        return $language_result;
    }

    /**
     * 导游显示页面
     * Hugh 18-08-02
     */
    public function showTourGuideManage(){
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
            'supplier_type_id'=>9,
        ];
        $setTourGuideManage = Session::get('setTourGuideManage');
        if(!empty($setTourGuideManage['search_ID'])){
            $data['supplier_id'] = $setTourGuideManage['search_ID'];
        }
        if(!empty($setTourGuideManage['search_Name'])){
            $data['supplier_name'] = $setTourGuideManage['search_Name'];
        }
        if(!empty($setTourGuideManage['search_operation'])){
            //$data['supplier_id'] = $setTourGuideManage['search_operation'];
        }
        if(!empty($setTourGuideManage['search_status'])){
            $data['status'] = $setTourGuideManage['search_status'];
        }
        $data['company_id'] =  session('user')['company_id'];
        $data_result =  $this->callSoaErp('post', '/source/getSupplier', $data);
        $this->getPageParams($data_result);
        $this->assign('setTourGuideManage',$setTourGuideManage);
        return $this->fetch('tour_guide_manage');
    }

    /***
     * 导游资源列表
     * Hugh 18-08-03
     */
    public function showTourGuideSourceManage(){
        $sid = input('get.id');
        $this->getSupplierName($sid);
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $setTourGuideSourceManage = Session::get('setTourGuideSourceManage');
        if(!empty($setTourGuideSourceManage['search_ID'])){
            $data['tour_guide_id'] = $setTourGuideSourceManage['search_ID'];
        }
        if(!empty($setTourGuideSourceManage['search_Name'])){
            $data['tour_guide_name'] = $setTourGuideSourceManage['search_Name'];
        }
        if(!empty($setTourGuideSourceManage['search_status'])){
            $data['status'] = $setTourGuideSourceManage['search_status'];
        }
        if($sid){
            $data['supplier_id'] = $sid;
        }
        $data['company_id'] =  session('user')['company_id'];
        $result =  $this->callSoaErp('post','/source/getTourGuide',$data);
        $this->getPageParams($result);
        $this->assign('setTourGuideSourceManage',$setTourGuideSourceManage);
        return $this->fetch('tour_guide_source_manage');
    }


    /***
     * 导游搜索
     * Hugh
     */
    public function setTourGuideManage(){
        Session::set('setTourGuideManage',$_POST);
        $this->redirect("/source/showTourGuideManage");
    }
    public function clearTourGuideManage(){
        Session::delete('setTourGuideManage');
        $this->redirect("/source/showTourGuideManage");
    }

    /***
     * 导游资源搜索
     * Hugh
     */
    public function setTourGuideSourceManage(){
        Session::set('setTourGuideSourceManage',$_POST);
        $this->redirect("/source/showTourGuideSourceManage?id={$_GET['id']}");
    }
    public function clearTourGuideSourceManage(){
        Session::delete('setTourGuideSourceManage');
        $this->redirect("/source/showTourGuideSourceManage?id={$_GET['id']}");
    }


    /**
     * 导游资源新增页面
     * Hugh 18-08-03
     */
    public function showTourGuideSourceAdd(){
        $sid = input('get.id');
        $this->getSupplierName($sid);

        //获取代理商
        $supplier_type_id = 1;
        $agent_data = [
            'add' => 1,
            'company_id'=>session('user')['company_id'],
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1
        ];
        $agentAr = $this->callSoaErp('post', '/source/getSupplier',$agent_data);
        $this->assign('agentAr',$agentAr['data']);
//        $agentAr = $this->getSupplierBySupplierTypeId(1);
//        $this->assign('agentAr',$agentAr);
        //导游供应商
        $supplier_type_id = 9;
        $guide_data = [
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $guideSupplierAr = $this->callSoaErp('post', '/source/getSupplier',$guide_data);
        $arr = array_merge($guideSupplierAr['data'],$agentAr['data']);
        $this->assign('guideSupplierAr',$arr);
//        $guideSupplierAr = $this->getSupplierBySupplierTypeId(9);
//        $this->assign('guideSupplierAr',$guideSupplierAr);
        //获取货币
        $data['status'] = 1;
        $CurrencyAr = $this->getCurrency($data);
        $this->assign('currencyAr',$CurrencyAr);
        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);

        return $this->fetch('tour_guide_source_add');
    }



    /***
     * 异步添加导游资源
     * Hugh 18-08-03
     */
    public function addTourGuideSourceAjax(){
        $data = Request::instance()->param();
        $data['user_id'] = session('user')['user_id'];
        $result =  $this->callSoaErp('post','/source/addTourGuide',$data);
        return $result;
    }


    /**
     * 导游资源修改页面
     * Hugh 18-08-03
     */
    public function showTourGuideSourceEdit(){
        //获取代理商
        $agentAr = $this->getSupplierBySupplierTypeId(1);
        //导游供应商
        $guideSupplierAr = $this->getSupplierBySupplierTypeId(9);
        $guideSupplierList = array_merge($agentAr,$guideSupplierAr);
        $this->assign('guideSupplierAr',$guideSupplierList);

        //获取货币
        $data['status'] = 1;
        $CurrencyAr = $this->getCurrency($data);
        $this->assign('currencyAr',$CurrencyAr);

        //获取导游信息
        $id = input('get.id');
        $guideData['tour_guide_id'] = $id;
        $TourGuideAr = $this->callSoaErp('post','/source/getTourGuide',$guideData);
        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);
        //获取代理商
        $agentAr = $this->getSupplierBySupplierTypeId(1);
        $this->assign('agentAr',$agentAr);
        $this->assign('company_result',$company_result['data']);
        $this->assign('currency_data_result',$currency_data_result['data']);
        $this->assign('TourGuideAr',$TourGuideAr['data'][0]);
        $this->getSupplierName($TourGuideAr['data'][0]['supplier_id']);


        return $this->fetch('tour_guide_source_edit');

    }


    /*****
     * 异步修改导游资源
     * Hugh 18-08-03
     */
    public function editTourGuideSourceAjax(){
        $data = Request::instance()->param();
        $choose_company_id = input("choose_company_id");
        $data['user_id'] = session('user')['user_id'];
        $data['tour_guide_id'] = input('get.id');
        $result =  $this->callSoaErp('post','/source/updateTourGuideByTourGuideId',$data);

        if($result){
            $data1['system_alert_event_id'] = 1;
            $data1['supplier_type'] = 9;
            $data1['resource_id'] = $data['tour_guide_id'];
            $data1['company_id'] = $choose_company_id;
            if(session('user')['company_id']==1){
                $data1['choose_company_id'] = $choose_company_id;
            }
            $result1 = $this->callSoaErp('post', '/system_alert_event/addInStationLetterAndEmail',$data1);
        }

        return $result;
    }


    /**
     * 导游详情页面
     * Hugh 18-08-03
     */
    public function showTourGuideSourceInfo(){
        //获取代理商
        $agentAr = $this->getSupplierBySupplierTypeId(1);
        //导游供应商
        $guideSupplierAr = $this->getSupplierBySupplierTypeId(9);
        $guideSupplierList = array_merge($agentAr,$guideSupplierAr);
        $this->assign('guideSupplierAr',$guideSupplierList);

        //获取货币
        $data['status'] = 1;
        $CurrencyAr = $this->getCurrency($data);
        $this->assign('currencyAr',$CurrencyAr);

        //获取导游信息
        $id = input('get.id');
        $guideData['tour_guide_id'] = $id;
        $TourGuideAr = $this->callSoaErp('post','/source/getTourGuide',$guideData);

        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);
        //获取代理商
        $agentAr = $this->getSupplierBySupplierTypeId(1);
        $this->assign('agentAr',$agentAr);
        $this->assign('company_result',$company_result['data']);
        $this->assign('currency_data_result',$currency_data_result['data']);
        $this->assign('TourGuideAr',$TourGuideAr['data'][0]);
        $this->getSupplierName($TourGuideAr['data'][0]['supplier_id']);
        return $this->fetch('tour_guide_source_info');
    }

    /**
     * 导游多语言编辑页面
     */
    public function showTourGuideSpotEditLanguage(){
        $data['source_number'] = input('source_number');
        $language_result = $this->callSoaErp('post','/source/getTourGuideLanguage',$data);

        $this->assign('language_result',$language_result['data']);
        return $this->fetch('tour_guide_edit_language');
    }
    /**
     * 导游多语言编辑AJAX
     */
    public function tourGuideEditLanguageAjax(){

        $data =$this->input();

        $params['data']  = $data;
        $params['user_id'] = session('user')['user_id'];


        $language_result = $this->callSoaErp('post','/source/updateTourGuideLanguageByTourGuideLanguageId',$params);
        return $language_result;
    }

    /**
     * 单项资源显示页面
     */
    public function showSingleSourceManage(){

        //搜索
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
            'supplier_type_id'=>10,
        ];
        $supplier_name = input("supplier_name");
        $status = input("status");

        //读取所有的供应商信息

        if(!empty($supplier_name)){
            $data['supplier_name'] = $supplier_name;
        }
        if($status>0){
            $data['status'] = $status;
        }
        $data['company_id'] =  session('user')['company_id'];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $this->getPageParams($result);
        return $this->fetch('single_source_manage');
    }

    /**
     * 单项资源新增页面
     */
    public function showSingleSourceAdd(){

        //读取所有的地接信息
        $supplier_type_id = 1;
        $agent_data = [
            'add' => 1,
            'company_id'=>session('user')['company_id'],
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1
        ];
        $result_agent = $this->callSoaErp('post', '/source/getSupplier',$agent_data);
        $this->assign('data_agent',$result_agent['data']);

        //读取所有的供应商信息
        $supplier_type_id = 10;
        $data = [
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);

        $arr = array_merge($result['data'],$result_agent['data']);

        $this->assign('data',$arr);
        //获取公司信息
        $data = [
            'status'=>1
        ];
        if(session('user')['company_id'] == 1){
            unset($data['company_id']);
        }
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);

        return $this->fetch('single_source_add');
    }

    /**
     * 单项资源添加数据
     */
    public function addSingleSourceAjax(){

        $supplier_id = input("supplier_id");
        $status = input("status");
        $agent_id = input("agent_id");
        // $supplier_type = input("supplier_type");
        // $belong_supplier_id = input("belong_supplier_id");
        $single_source_name = input("single_source_name");
        $is_own_expense = input("is_own_expense");
        $normal_price = input("normal_price");
        $normal_settlement_price = input("normal_settlement_price");
        $remark = input("remark");
        $user_id = session('user')['user_id'];
        $choose_company_id = input('choose_company_id');
        $payment_currency_type = input('payment_currency_type');

        $data = [
            "supplier_id"=>$supplier_id,
            "status"=>$status,
            "agent_id"=>$agent_id,
            // "supplier_type"=>$supplier_type,
            // "belong_supplier_id"=>$belong_supplier_id,
            "single_source_name"=>$single_source_name,
            "is_own_expense"=>$is_own_expense,
            "normal_price"=>$normal_price,
            "normal_settlement_price"=>$normal_settlement_price,
            "remark"=>$remark,
            "user_id"=>$user_id,
            "choose_company_id"=>$choose_company_id,
            "payment_currency_type"=>$payment_currency_type
        ];
        $result = $this->callSoaErp('post', '/source/addSingleSource',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }

    /**
     * 单项资源修改页面
     */
    public function showSingleSourceEdit(){

        //读取所有的地接信息
        $supplier_type_id = 1;
        $agent_data = ['supplier_type_id'=>$supplier_type_id];
        $result_agent = $this->callSoaErp('post', '/source/getSupplier',$agent_data);
        $this->assign('data_agent',$result_agent['data']);

        //读取所有的供应商信息
        $supplier_type_id = 10;
        $data = ['supplier_type_id'=>$supplier_type_id];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $arr = array_merge($result['data'],$result_agent['data']);

        $this->assign('data',$arr);

        //获取当前的单项资源信息
        $single_source_id = input("single_source_id");
        $single_source_data = [
            "single_source_id"=>$single_source_id
        ];
        $single_source_result = $this->callSoaErp('post', '/source/getSingleSource',$single_source_data);


        $this->assign('single_source_result',$single_source_result['data'][0]);
        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);

        return $this->fetch('single_source_edit');
    }

    /**
     * 单项资源修改数据
     */
    public function editSingleSourceAjax(){
        $single_source_id = input("single_source_id");
//        $supplier_id = input("supplier_id");
        $status = input("status");
        $single_source_name = input("single_source_name");
//        $supplier_type = input("supplier_type");
        // $belong_supplier_id = input("belong_supplier_id");
//         $agent_id = input("agent_id");
        // $single_source_name = input("single_source_name");
//        $is_own_expense = input("is_own_expense");
        $normal_price = input("normal_price");
        $normal_settlement_price = input("normal_settlement_price");
//        $remark = input("remark");
        $user_id = session('user')['user_id'];
        $choose_company_id = input("choose_company_id");
        $payment_currency_type = input('payment_currency_type');

        $data = [
            "single_source_id"=>$single_source_id,
//            "supplier_id"=>$supplier_id,
            "status"=>$status,
//            "agent_id"=>$agent_id,
            // "supplier_type"=>$supplier_type,
            // "belong_supplier_id"=>$belong_supplier_id,
            "single_source_name"=>$single_source_name,

            "normal_price"=>$normal_price,
            "normal_settlement_price"=>$normal_settlement_price,
//            "remark"=>$remark,
            "user_id"=>$user_id,
            'choose_company_id'=>$choose_company_id,
            "payment_currency_type"=>$payment_currency_type
        ];

        $result = $this->callSoaErp('post', '/source/updateSingleSourceBySingleSourceId',$data);
        if($result){
            $data1['system_alert_event_id'] = 1;
            $data1['supplier_type'] = 10;
            $data1['resource_id'] = $single_source_id;
            $data1['company_id'] = $choose_company_id;
            if(session('user')['company_id']==1){
                $data1['choose_company_id'] = $choose_company_id;
            }
            $result1 = $this->callSoaErp('post', '/system_alert_event/addInStationLetterAndEmail',$data1);
        }
        return   $result;//['code' => '400', 'msg' => $data];
    }

    /**
     * 单项资源详情页面
     */
    public function showSingleSourceInfo(){

        //读取所有的供应商信息
        $supplier_type_id = 10;
        $data = ['supplier_type_id'=>$supplier_type_id];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $this->assign('data',$result['data']);

        //获取当前的单项资源信息
        $single_source_id = input("single_source_id");
        $single_source_data = [
            "single_source_id"=>$single_source_id
        ];
        $single_source_result = $this->callSoaErp('post', '/source/getSingleSource',$single_source_data);
        $this->assign('single_source_result',$single_source_result['data'][0]);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);

        $this->assign('currency_data_result',$currency_data_result['data']);

        return $this->fetch('single_source_info');
    }

    /**
     * 单项资源资源页面
     */
    public function showSingleSourceSource(){

        //搜索操作
        $sid = input('get.id');
        $this->getSupplierName($sid);
        $setSingleSourceManage = Session::get('setSingleSourceManage');
        $this->assign('setSingleSourceManage',$setSingleSourceManage);
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $single_source_id = input("single_source_id");
        $single_source_name = input("single_source_name");
        $supplier_id = input("supplier_id");
        $status = input("status");

        if(!empty($single_source_id)){
            $data['single_source_id'] = $single_source_id;
        }
        if(!empty($single_source_name)){
            $data['single_source_name'] = $single_source_name;
        }
        if(!empty($supplier_id)){
            $data['supplier_id'] = $supplier_id;
        }
        if($status>0){
            $data['status'] = $status;
        }

        if(!empty($sid)){
            $data['supplier_id'] = $sid;
        }
        $data['company_id'] =  session('user')['company_id'];
        $result = $this->callSoaErp('post', '/source/getSingleSource',$data);
        $this->getPageParams($result);

        //对应的supplier_id
        $this->assign('supplier_id',input("supplier_id"));

        return $this->fetch('single_source_source');
    }
    /**
     * 单项资源多语言编辑页面
     */
    public function showSingleSourceEditLanguage(){
        $data['source_number'] = input('source_number');
        $language_result = $this->callSoaErp('post','/source/getSingleSourceLanguage',$data);

        $this->assign('language_result',$language_result['data']);
        return $this->fetch('single_source_edit_language');
    }
    /**
     * 单项资源多语言编辑AJAX
     */
    public function singleSourceEditLanguageAjax(){


        $data =$this->input();

        $params['data']  = $data;
        $params['user_id'] = session('user')['user_id'];


        $language_result = $this->callSoaErp('post','/source/updateSingleSourceLanguageBySingleSourceLanguageId',$params);
        return $language_result;
    }
    /**
     * 自费显示页面
     * 王18-10-08
     */
    public function showOwnExpenseManage(){
        //读取所有的供应商信息
        $supplier_type_id = 11;
        $data = ['supplier_type_id'=>$supplier_type_id];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $this->assign('data',$result['data']);
        //搜索
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
            'supplier_type_id'=>11,
        ];
        $supplier_name = input("supplier_name");
        $status = input("status");

        $data['supplier_type_id']=$supplier_type_id;

        if(!empty($supplier_name)){
            $data['supplier_name'] = $supplier_name;
        }
        if($status>0){
            $data['status'] = $status;
        }
        $data['company_id'] =  session('user')['company_id'];
        $data_result = $this->callSoaErp('post','/source/getSupplier',$data);
        $this->getPageParams($data_result);
        return $this->fetch('own_expense_manage');
    }
    /**
     * 自费新增页面
     */
    public function showOwnExpenseAdd(){

        //读取所有的地接信息
        $supplier_type_id = 1;
        $agent_data = [
            'add' => 1,
            'company_id'=>session('user')['company_id'],
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1
        ];
        $result_agent = $this->callSoaErp('post', '/source/getSupplier',$agent_data);
        $this->assign('data_agent',$result_agent['data']);

        //读取所有的供应商信息
        $supplier_type_id = 11;
        $data = [
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $arr = array_merge($result['data'],$result_agent['data']);

        $this->assign('data',$arr);
        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);
        $this->assign('currency_data_result',$currency_data_result['data']);

        return $this->fetch('own_expense_add');
    }
    /**
     * 自费添加数据
     */
    public function addOwnExpenseAjax(){
        $own_expense_name = input("own_expense_name");
        $supplier_id = input("supplier_id");
        $status = input("status");
        $agent_id = input("agent_id");
        $normal_price = input("normal_price");
        $normal_settlement_price = input("normal_settlement_price");
//        $remark = input("remark");
        $user_id = session('user')['user_id'];
        $choose_company_id = input('choose_company_id');
        $payment_currency_type = input('payment_currency_type');


        $data = [
            "own_expense_name"=>$own_expense_name,
            "supplier_id"=>$supplier_id,
            "status"=>$status,
            "agent_id"=>$agent_id,
            "normal_price"=>$normal_price,
            "normal_settlement_price"=>$normal_settlement_price,
//            "remark"=>$remark,
            "user_id"=>$user_id,
            'choose_company_id'=>$choose_company_id,
            "payment_currency_type"=>$payment_currency_type
        ];

        $result = $this->callSoaErp('post', '/source/addOwnExpense',$data);
        return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 自费修改页面
     */
    public function showOwnExpenseEdit(){

        //读取所有的地接信息
        $supplier_type_id = 1;
        $agent_data = ['supplier_type_id'=>$supplier_type_id];
        $result_agent = $this->callSoaErp('post', '/source/getSupplier',$agent_data);
        $this->assign('data_agent',$result_agent['data']);

        //读取所有的供应商信息
        $supplier_type_id = 11;
        $data = ['supplier_type_id'=>$supplier_type_id];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $arr = array_merge($result['data'],$result_agent['data']);

        $this->assign('data',$arr);

        //获取当前的自费信息
        $own_expense_id = input("own_expense_id");
        $own_expense_data = [
            "own_expense_id"=>$own_expense_id
        ];
        $own_expense_result = $this->callSoaErp('post', '/source/getOwnExpense', $own_expense_data);


        $this->assign('own_expense_result',$own_expense_result['data'][0]);
        //获取公司信息
        $data = [
            'status'=>1
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);

        $this->assign('company_result',$company_result['data']);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);
        $this->assign('currency_data_result',$currency_data_result['data']);

        return $this->fetch('own_expense_edit');
    }

    /**
     * 自费修改数据
     */
    public function editOwnExpenseAjax(){
        $own_expense_id = input("own_expense_id");
        $own_expense_name = input("own_expense_name");
//        $supplier_id = input("supplier_id");
        $status = input("status");
//        $agent_id = input("agent_id");
        $normal_price = input("normal_price");
        $normal_settlement_price = input("normal_settlement_price");
//        $remark = input("remark");
        $user_id = session('user')['user_id'];
        $choose_company_id = input("choose_company_id");
        $payment_currency_type = input('payment_currency_type');

        $data = [
            "own_expense_id"=>$own_expense_id,
//            "supplier_id"=>$supplier_id,
            "status"=>$status,
//            "agent_id"=>$agent_id,
            "own_expense_name"=>$own_expense_name,
            "normal_price"=>$normal_price,
            "normal_settlement_price"=>$normal_settlement_price,
//            "remark"=>$remark,
            "user_id"=>$user_id,
            'choose_company_id'=>$choose_company_id,
            "payment_currency_type"=>$payment_currency_type
        ];

        $result = $this->callSoaErp('post', '/source/updateOwnExpenseByOwnExpenseId',$data);

        if($result){
            $data1['system_alert_event_id'] = 1;
            $data1['supplier_type'] = 11;
            $data1['resource_id'] = $own_expense_id;
            $data1['company_id'] = $choose_company_id;
            if(session('user')['company_id']==1){
                $data1['choose_company_id'] = $choose_company_id;
            }
            $result1 = $this->callSoaErp('post', '/system_alert_event/addInStationLetterAndEmail',$data1);
        }

        return   $result;//['code' => '400', 'msg' => $data];
    }
    /**
     * 自费详情页面
     */
    public function showOwnExpenseInfo(){

        //读取所有的供应商信息
        $supplier_type_id = 11;
        $data = ['supplier_type_id'=>$supplier_type_id];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);
        $this->assign('data',$result['data']);

        //获取当前的自费信息
        $own_expense_id = input("own_expense_id");
        $own_expense_data = [
            "own_expense_id"=>$own_expense_id
        ];
        $own_expense_result = $this->callSoaErp('post', '/source/getOwnExpense',$own_expense_data);
        $this->assign('own_expense_result',$own_expense_result['data'][0]);
        //获取币种
        $currency_data = [
            'status'=>1
        ];
        $currency_data_result =  $this->callSoaErp('post', '/system/getCurrency', $currency_data);
        $this->assign('currency_data_result',$currency_data_result['data']);
        return $this->fetch('own_expense_info');
    }
    /**
     * 自费项目资源页面
     */
    public function showOwnExpenseSource(){

        //搜索操作
        $sid = input('get.id');
        $this->getSupplierName($sid);
        $setOwnExpenseManage = Session::get('setOwnExpenseManage');
        $this->assign('setOwnExpenseManage',$setOwnExpenseManage);
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $own_expense_id = input("own_expense_id");
        $own_expense_name = input("own_expense_name");
        $supplier_id = input("supplier_id");
        $status = input("status");

        if(!empty($own_expense_id)){
            $data['own_expense_id'] = $own_expense_id;
        }
        if(!empty($own_expense_name)){
            $data['own_expense_name'] = $own_expense_name;
        }
        if(!empty($supplier_id)){
            $data['supplier_id'] = $supplier_id;
        }
        if($status>0){
            $data['status'] = $status;
        }

        if(!empty($sid)){
            $data['supplier_id'] = $sid;
        }
        $data['company_id'] =  session('user')['company_id'];
        $result = $this->callSoaErp('post', '/source/getOwnExpense',$data);
        $this->getPageParams($result);
        //对应的supplier_id
        $this->assign('supplier_id',input("supplier_id"));

        return $this->fetch('own_expense_source');
    }

    /**
     * 自费项目多语言编辑页面
     */
    public function showOwnExpenseEditLanguage(){
        $data['source_number'] = input('source_number');
        $language_result = $this->callSoaErp('post','/source/getOwnExpenseLanguage',$data);

        $this->assign('language_result',$language_result['data']);
        return $this->fetch('own_expense_edit_language');
    }
    /**
     * 自费项目多语言编辑AJAX
     */
    public function ownExpenseEditLanguageAjax(){


        $data =$this->input();

        $params['data']  = $data;
        $params['user_id'] = session('user')['user_id'];


        $language_result = $this->callSoaErp('post','/source/updateOwnExpenseLanguageByOwnExpenseLanguageId',$params);
        return $language_result;
    }


	/**
	 * 获取资源AJAX
	 */
    public function getSourceAjax(){
		$data = [
			'source_number'=>input('source_number'),
			'source_name'=>input('source_name'),
			'supplier_type_id'=>input('supplier_type_id'),
			'supplier_name'=>input('supplier_name'),
			'is_branch_product'=>input('is_branch_product')
		];
    	$source_result = $this->callSoaErp('post', '/source/getSource', $data);

    	// dump($source_result);
    	return $source_result;
    	
    	
    	
    }
	/**
	 * 获取供应商ID
	 */
    public function getSupplierAjax(){
    	
    	$params = Request::instance()->param();
    	$params['company_id'] = session('user')['company_id'];
    	$result = $this->callSoaErp('post', '/source/getSupplier', $params);
    	 
    	return $result;
    	
    }

    /**
     * showShoppingManage
     *
     * 购物店列表
     * @author shj
     * @return mixed
     * Date: 2019/4/4
     * Time: 11:07
     */
    public function showShoppingManage(){
        //搜索
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
            'supplier_type_id'=>12,
        ];
        $supplier_name = input("supplier_name");
        $status = input("status");

        if(!empty($supplier_name)){
            $data['supplier_name'] = $supplier_name;
        }
        if($status>0){
            $data['status'] = $status;
        }
        $data['company_id'] =  session('user')['company_id'];
        $data_result = $this->callSoaErp('post','/source/getSupplier',$data);
        $this->getPageParams($data_result);
        return $this->fetch('shopping_manage');
    }

    /**
     * showShoppingSource
     *
     * 显示购物店资源列表页面
     * @author shj
     * @return mixed
     * Date: 2019/4/4
     * Time: 17:11
     */
    public function showShoppingSource()
    {
        //搜索操作
        $data          = [
            'page'      => $this->page(),
            'page_size' => $this->_page_size,
        ];
        $shopping_id   = input("shopping_id");
        $shopping_name = input("shopping_name");
        $supplier_id   = input("supplier_id");
        $status        = input("status");

        if (!empty($shopping_id)) {
            $data['shopping_id'] = $shopping_id;
        }
        if (!empty($shopping_name)) {
            $data['shopping_name'] = $shopping_name;
        }
        if (!empty($supplier_id)) {
            $data['supplier_id'] = $supplier_id;
        }
        if ($status < 2) {
            $data['status'] = $status;
        }
        $data['company_id'] = session('user')['company_id'];
        $result             = $this->callSoaErp('post', '/source/getShopping', $data);
        $this->getPageParams($result);
        //对应的supplier_id
        $this->assign('supplier_id', input("supplier_id"));

        return $this->fetch('shopping_source');
    }

    /**
     * showShoppingAdd
     *
     * 新增购物店资源页面
     * @author shj
     * @return mixed
     * Date: 2019/4/4
     * Time: 17:12
     */
    public function showShoppingAdd(){

        //读取所有的供应商信息
        $supplier_type_id = 12;
        $data = [
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);

        $this->assign('data',$result['data']);
        //获取公司信息
        $data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        $this->assign('company_result',$company_result['data']);

        return $this->fetch('shopping_add');

    }

    /**
     * addShoppingAjax
     *
     * 新增购物店资源数据
     * @author shj
     * @return array|bool|mixed|string
     * Date: 2019/4/4
     * Time: 17:13
     */
    public function addShoppingAjax(){
        $shopping_name = input("shopping_name");
        $supplier_id = input("supplier_id");
        $status = input("status");
        $remark = input("remark");
        $user_id = session('user')['user_id'];
        $choose_company_id = input('choose_company_id');
        $shopping_type = input('shopping_type');


        $data = [
            "shopping_name"=>$shopping_name,
            "supplier_id"=>$supplier_id,
            "status"=>$status,
            "remark"=>$remark,
            "user_id"=>$user_id,
            'choose_company_id'=>$choose_company_id,
            "shopping_type"=>$shopping_type,
            'supplier_type'=>1,
            'default_language_id'=>session('user')['language_id']
        ];

        $result = $this->callSoaErp('post', '/source/addShopping',$data);
        return   $result;
    }

    /**
     * showShoppingInfo
     *
     * 购物店详情页面
     * @author shj
     * @return mixed
     * Date: 2019/4/9
     * Time: 11:51
     */
    public function showShoppingInfo(){

        //获取公司信息
        $data = [
            'status'=>1,
            'shopping_id'=>input("shopping_id")
        ];
        $result =  $this->callSoaErp('post', '/source/getOneShopping',$data);
        $this->assign('vo',$result['data']);

        return $this->fetch('shopping_info');

    }

    /**
     * showShoppingEdit
     *
     * 修改购物店页面
     * @author shj
     * @return mixed
     * Date: 2019/4/9
     * Time: 16:27
     */
    public function showShoppingEdit(){

        //获取购物店信息
        $data = [
            'status'=>1,
            'shopping_id'=>input("shopping_id")
        ];
        $result =  $this->callSoaErp('post', '/source/getOneShopping',$data);
        $this->assign('result',$result['data']);

        //读取所有的供应商信息
        $supplier_type_id = 12;
        $data = [
            'supplier_type_id'=>$supplier_type_id,
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $result = $this->callSoaErp('post', '/source/getSupplier',$data);

        $this->assign('data',$result['data']);
        //获取公司信息
        $data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        $this->assign('company_result',$company_result['data']);
        return $this->fetch('shopping_edit');
    }

    /**
     * editShoppingAjax
     *
     * 修改购物店数据
     * @author shj
     * @return array|bool|mixed|string
     * Date: 2019/4/9
     * Time: 16:28
     */
    public function editShoppingAjax(){
        $shopping_id = input("shopping_id");
        $shopping_name = input("shopping_name");
        $supplier_id = input("supplier_id");
        $status = input("status");
        $remark = input("remark");
        $user_id = session('user')['user_id'];
        $choose_company_id = input('choose_company_id');
        $shopping_type = input('shopping_type');

        $data = [
            "shopping_id"=>$shopping_id,
            "shopping_name"=>$shopping_name,
            "supplier_id"=>$supplier_id,
            "status"=>$status,
            "remark"=>$remark,
            "user_id"=>$user_id,
            'choose_company_id'=>$choose_company_id,
            "shopping_type"=>$shopping_type,
        ];

        $result = $this->callSoaErp('post', '/source/editShopping',$data);
        return  $result;
    }

        public function showCompanySource(){
            $params = [
                'company_id' => session('user')['company_id'],
                'company_name' => session('user')['company_name'],
            ];

            $supplier_result = $this->callSoaErp('post', '/source/getOneSupplierByArr',$params);

            $supplier_id = $supplier_result['data']["supplier_id"];
            //读取相关酒店信息 2
            $data = [
                'status' => 1,
                'supplier_id' => $supplier_id,
            ];
            $hotel_result = $this->callSoaErp('post', '/source/getHotel',$data);
            $this->assign('hotel_result',$hotel_result['data']);

            //读取相关用餐信息 3
            $dining_result = $this->callSoaErp('post', '/source/getDining',$data);
            $this->assign('dining_result',$dining_result['data']);

            //读取相关航班信息 4
            $flight_result = $this->callSoaErp('post', '/source/getFlight',$data);
            $this->assign('flight_result',$flight_result['data']);

            //读取相关邮轮信息 5
            $cruise_result = $this->callSoaErp('post', '/source/getCruise',$data);
            $this->assign('cruise_result',$cruise_result['data']);

            //读取相关签证信息 6
            $visa_result = $this->callSoaErp('post', '/source/getVisa',$data);
            $this->assign('visa_result',$visa_result['data']);

            //读取相关景点信息 7
            $scenic_spot_result = $this->callSoaErp('post', '/source/getScenicSpot',$data);
            $this->assign('scenic_spot_result',$scenic_spot_result['data']);

            //读取相车辆点信息 8
            $vehicle_result = $this->callSoaErp('post', '/source/getVehicle',$data);
            $this->assign('vehicle_result',$vehicle_result['data']);

            //读取相车导游信息 9
            $tour_guide_result = $this->callSoaErp('post', '/source/getTourGuide',$data);
            $this->assign('tour_guide_result',$tour_guide_result['data']);

            //读取相关单项资源 10
            $single_source_result = $this->callSoaErp('post', '/source/getSingleSource',$data);
            $this->assign('single_source_result',$single_source_result['data']);

            //读取相关自费 11
            $own_expense_result = $this->callSoaErp('post', '/source/getOwnExpense',$data);
            $this->assign('own_expense_result',$own_expense_result['data']);

            return $this->fetch('company_agent_source');
    }
}

