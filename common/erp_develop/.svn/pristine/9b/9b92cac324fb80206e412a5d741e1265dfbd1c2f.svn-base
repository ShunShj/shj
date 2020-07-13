<?php
/**
 * Created by PhpStorm.
 * User: godwei
 * Date: 2018/9/20
 * Time: 16:40 3333
 */

namespace app\index\controller;
use think\Cookie;
use \Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;
use app\common\help\Help;

class Distributor extends Base
{
    public function showDistributorTypeManage()
    {
        $data = Request::instance()->param();
        $data['page'] = $this->page();
        $data['size'] = $this->_page_size;
        $data['choose_company_id'] = session('user')['company_id'];

        $result = $this->callSoaErp('post', '/btob/getDistributorType', $data);
        $this->getPageParams($result);

        return $this->fetch('distributor_type_manage');
    }

    public function showDistributorTypeAdd()
    {
        $data = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data);
        $this->assign('company_result',$company_result['data']);
        return $this->fetch('distributor_type_add');
    }

    public function addDistributorTypeAjax()
    {
        $data = Request::instance()->param();
        $data['user_id'] = session('user')['user_id'];
        $data['company_id'] = session('user')['company_id'];
        $result = $this->callSoaErp('post', '/btob/addDistributorType', $data);
        return $result;
    }

    public function showDistributorTypeEdit()
    {
        $data1 = [
            'status'=>1,
            'company_id'=>session('user')['company_id']
        ];
        $company_result =  $this->callSoaErp('post', '/system/getCompany',$data1);
        $this->assign('company_result',$company_result['data']);


        $post = Request::instance()->param();
        $data['distributor_type_id'] = $post['distributor_type_id'];
        $result = $this->callSoaErp('post', '/btob/getDistributorType', $data);


        $this->assign('result', $result['data'][0]);



        return $this->fetch('distributor_type_edit');
    }

    /**
     * 异部修改渠道商
     * Hugh
     */
    public function editDistributorTypeAjax()
    {
        $data = Request::instance()->param();
        $data['user_id'] = session('user')['user_id'];
        $data['company_id'] = session('user')['company_id'];

        $result = $this->callSoaErp('post', '/btob/updateDistributorType', $data);
        return $result;
    }


    public function getDistributorAjax(){
        $params = Request::instance()->param();

        $params['company_id'] = session('user')['company_id'];
        $params['status'] = 1;
        $result = $this->callSoaErp('post', '/btob/getDistributor', $params);

        return $result;
    }


    public function showDistributorManage()
    {
        $params['choose_company_id'] = session('user')['company_id'];
        $params['status'] = 1;
        $result = $this->callSoaErp('post', '/btob/getDistributorType', $params);
        $this->assign('result',$result['data']);

        $data = Request::instance()->param();
        $data['page'] = $this->page();
        $data['size'] = $this->_page_size;
        $data['choose_company_id'] = session('user')['company_id'];

        $distributorData = $this->callSoaErp('post', '/btob/getBtoBDistributor', $data);
        $this->getPageParams($distributorData);

        return $this->fetch('distributor_manage');
    }


    /**
     * 添加渠道商
     * Hugh
     */
    public function showDistributorAdd()
    {
        $params['choose_company_id'] = session('user')['company_id'];
        $params['status'] = 1;
        $result = $this->callSoaErp('post', '/btob/getDistributorType', $params);
        $this->assign('result',$result['data']);

        //语言
        $data['status'] = 1;
        $langData = $this->callSoaErp('post', '/system/getLanguage', $data);
        $this->assign('langList', $langData['data']);

        //城市
        $data1['level'] = 3;
        $country_result = $this->callSoaErp('post', '/system/getCountryCity',$data1);
        $this->assign('cityList', $country_result['data']);

        return $this->fetch('distributor_add');
    }

    /**
     * 异部添加渠道商
     * Hugh
     */
    public function addDistributorAjax()
    {
        $data = Request::instance()->param();
        $data['user_id'] = session('user')['user_id'];
        $data['company_id'] = session('user')['company_id'];
        $result = $this->callSoaErp('post', '/btob/addBtoBDistributor', $data);
        return $result;
    }

    /**
     * 修改渠道商
     * Hugh
     */
    public function showDistributorEdit()
    {
        $params['choose_company_id'] = session('user')['company_id'];
        $params['status'] = 1;
        $result = $this->callSoaErp('post', '/btob/getDistributorType', $params);
        $this->assign('result_type',$result['data']);

        //语言
        $data['status'] = 1;
        $langData = $this->callSoaErp('post', '/system/getLanguage', $data);
        $this->assign('langList', $langData['data']);

        //城市
        $data1['level'] = 3;
        $country_result = $this->callSoaErp('post', '/system/getCountryCity',$data1);
        $this->assign('cityList', $country_result['data']);

        unset($data);
        $data['distributor_id'] = input('get.id');
        $distributorData = $this->callSoaErp('post', '/btob/getBtoBDistributor', $data);

        if (!empty($distributorData['data'])) {
            $this->assign('result', $distributorData['data'][0]);
        }

        return $this->fetch('distributor_edit');
    }

    /**
     * 异部修改渠道商
     * Hugh
     */
    public function editDistributorAjax()
    {
        $data = Request::instance()->param();
        $data['user_id'] = session('user')['user_id'];
        $data['company_id'] = session('user')['company_id'];
        $result = $this->callSoaErp('post', '/btob/updateBtoBDistributorByDistributorId', $data);
        return $result;
    }

    /**
     * 详情渠道商
     * Hugh
     */
    public function showDistributorInfo()
    {
        //语言
        $data['status'] = 1;
        $langData = $this->callSoaErp('post', '/system/getLanguage', $data);
        if (!empty($langData['data'])) {
            $this->assign('langList', $langData['data']);
        }

        //城市
        $data['level'] = 3;
        $cityData = $this->callSoaErp('post', '/system/getCountry', $data);
        if (!empty($cityData['data'])) {
            $this->assign('cityList', $cityData['data']);
        }

        $data['distributor_id'] = input('get.id');
        unset($data['status']);
        $distributorData = $this->callSoaErp('post', '/btob/getBtoBDistributor', $data);
        if (!empty($distributorData['data'])) {
            $this->assign('distributorList', $distributorData['data'][0]);
        }

        return $this->fetch('distributor_info');
    }


    public function showDistributorTemplateManage(){
        $distributor_id = input('id');
        $data['distributor_id'] = $distributor_id;
        $distributor_result = $this->callSoaErp('post', '/btob/getDistributor', $data);

        $this->assign('distributor_result',$distributor_result['data'][0]);

        //获取账单title
        $bill_template_params = [
            'company_id'=>session('user')['company_id'],
        ];
        $bill_template_result = $this->callSoaErp('post', '/system/getBillTemplate', $bill_template_params);
        $this->assign('bill_template_result',$bill_template_result['data']);

        //反查应收获取分公司产品编号
        $branch_product_params = [
            'company_id'=>session('user')['company_id'],

            'status'=>1
        ];
        $branch_product_result = $this->callSoaErp('post', '/branchcompany/getBranchProduct', $branch_product_params);
        $this->assign('branch_product_result',$branch_product_result['data']);
        return $this->fetch('distributor_template');
    }
    /**
     * 调取渠道账单AJAX
     */
    public function getDistributorTemplateAjax(){
        if(!empty(input('company_order_create_from_time'))){
            $data['company_order_create_from_time'] = strtotime(input('company_order_create_from_time'));
        }
        if(!empty(input('company_order_create_to_time'))){
            $data['company_order_create_to_time'] = strtotime(input('company_order_create_to_time'));
        }
        if(!empty(input('company_order_begin_time'))){
            $data['company_order_begin_time'] = strtotime(input('company_order_begin_time'));
        }
        if(!empty(input('company_order_end_time'))){
            $data['company_order_end_time'] = strtotime(input('company_order_end_time'));
        }
        if(!empty(input('hidden_sale_over'))){
            $data['hidden_sale_over'] = input('hidden_sale_over');
        }
        if(!empty(input('branch_product_number'))){
            $data['branch_product_number'] = input('branch_product_number');
        }

        $data['distributor_id'] = input('distributor_id');
        $data['bill_template_id'] = input('bill_template_id');
        $distributorData = $this->callSoaErp('post', '/btob/getDistributorBill', $data);

        return $distributorData;
    }

}