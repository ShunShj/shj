<?php

namespace app\index\controller;

use app\common\help\Help;
use \Underscore\Types\Arrays;
use think\Request;

/***
 * 产品
 *
 **/
class OtaProduct extends Base
{

    //旅游产品类型列表
    public function types()
    {
        $post = Request::instance()->param();

        $post['is_like'] = 1;
        $post['page'] = $this->page();
        $post['size'] = $this->_page_size;

        $post['website_uuid'] = session('website_uuid');

        $result = $this->callSoaErp('post','/ota_product/getProductTypes',$post);
        $this->getPageParams($result);

        return $this->fetch('product_type_manage');
    }

    //添加旅游产品类型
    public function addType()
    {
        //获取公司信息
        $where['status'] = 1;
        $company_result =  $this->callSoaErp('post', '/system/getCompany', $where);
        $this->assign('company_result',$company_result['data']);

        //$where['company_id'] = session('user')['company_id'];
        $where['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_product/getProductTypes',$where);
        $type_list = Help::toTree($result['data'],  0, $level = 0, 'ota_product_type_id');
        $this->assign('type_list', $type_list);

        return $this->fetch('product_type_add');
    }

    //执行添加旅游产品类型
    public function addTypeAjax()
    {

        $post = Request::instance()->param();
        $post['company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');

        return $this->callSoaErp('post','/ota_product/addProductType',$post);

    }


    //修改旅游产品类型
    public function editType()
    {
        //$where['company_id'] = session('user')['company_id'];
        $where['uuid'] = input('get.uuid');
        $result = $this->callSoaErp('post','/ota_product/getTypeInfo',$where);

        $destination_scenic_spot = $this->callSoaErp('post','/ota_product/getDestinationAndScenicSpot',['type_uuid' => $result['data']['uuid']]);

        $where['status'] = 1;
        $where['pid'] = $result['data']['pid'];
        $where['id'] = $result['data']['ota_product_type_id'];

        $where['website_uuid'] = session('website_uuid');
        $result2 = $this->callSoaErp('post','/ota_product/getProductTypes',$where);

        $type_list = Help::toTree($result2['data'],  0, 0, 'ota_product_type_id');

        $this->assign([
            'type_info' => $result['data'],
            'type_list' => $type_list,
            'destination' => $destination_scenic_spot['data']['destination'],
            'scenic_spot' => $destination_scenic_spot['data']['scenic_spot'],

        ]);

        return $this->fetch('product_type_edit');
    }

    public function getDestinationAndScenicSpotByTypeIdsAjax()
    {
        $post = Request::instance()->param();

        if ($post['type_ids'])
        {
            $result = $this->callSoaErp('post','/ota_product/getProductTypes',$post);

            $type_ids = explode(',', $post['type_ids']);
            $uuid_list = [];

            foreach ($type_ids as $v)
            {

                $list = Help::toTree($result['data'],  $v, 0, 'ota_product_type_id');
                $uuid = array_column($list, 'uuid');
                $type_info = $this->callSoaErp('post','/ota_product/getTypeInfo', ['type_id' => $v]);
                $uuid_list = array_merge($uuid_list, $uuid, [$type_info['data']['uuid']]);
            }

            $uuid_list = array_unique($uuid_list);

            return $this->callSoaErp('post','/ota_product/getDestinationAndScenicSpot',['type_uuid' => $uuid_list]);
        }
        return ['code'=>200, 'data'=> []];

    }


    //执行修改旅游产品类型
    public function editTypeAjax()
    {
        $post = Request::instance()->param();
        $post['company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');

        return $this->callSoaErp('post','/ota_product/editProductType',$post);

    }

    public function getTypeInfo()
    {
        $where['company_id'] = session('user')['company_id'];
        $where['uuid'] = input('get.uuid');
        $where['website_uuid'] = session('website_uuid');
        $result = $this->callSoaErp('post','/ota_product/getTypeInfo',$where);

        $parent = $this->callSoaErp('post','/ota_product/getTypeInfo',['id' => $result['data']['pid']]);

        $destination_scenic_spot = $this->callSoaErp('post','/ota_product/getDestinationAndScenicSpot',['type_uuid' => $result['data']['uuid']]);

        $this->assign([
            'type_info'   => $result['data'],
            'parent_name' => $parent['data']['type_name'],
            'destination' => $destination_scenic_spot['data']['destination'],
            'scenic_spot' => $destination_scenic_spot['data']['scenic_spot'],
            ]);

        return $this->fetch('product_type_info');
    }




    //执行删除旅游产品类型
    public function delTypeAjax()
    {
        $post = Request::instance()->param();
        $d['uuid'] = $post['uuid'];
        $d['website_uuid'] = session('website_uuid');

        return $this->callSoaErp('post','/ota_product/delProductType',$d);

    }


    //旅游产品
    public function products(){

        $title = trim(input('get.title'));
        $create_username = trim(input('get.create_username'));
        $data['website_uuid'] = session('website_uuid');

        if(is_numeric(input('status')))
        {
            $data['status'] = input('status');
        }

        if(!empty($title))
        {
            $data['title'] = $title;
        }
        if(!empty($create_username))
        {
            $data['create_username'] = $create_username;
        }

        $data['is_like'] = 1;
        $data['page'] = $this->page();
        $data['size'] = $this->_page_size;

        $result = $this->callSoaErp('post','/ota_product/getProducts',$data);

        $this->getPageParams($result);
        return $this->fetch('product_manage');
    }

    //添加旅游产品
    public function addProduct()
    {
        $where['website_uuid'] = session('website_uuid');
        $where['status'] = 1;
        //获取分类数据
        $type_result = $this->callSoaErp('post','/ota_product/getProductTypes',$where);
        //城市
        $data1['level'] = 3;
        $country_result = $this->callSoaErp('post', '/system/getCountryCity',$data1);

        $this->assign([
            'type_list' => Help::toTree($type_result['data'], 0, 0, 'ota_product_type_id'),
            'CountryList' => $country_result['data']
        ]);

        return $this->fetch('product_add');
    }

    //执行添加旅游产品
    public function addProductAjax()
    {
        $post = Request::instance()->param();
        $post['company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');

        $result = $this->callSoaErp('post','/ota_product/addProduct', $post);
        return $result;
    }

    public function addSpecAjax()
    {
        $route_template_id = input('route_template_id');
        $product_type = input('product_type');

        if ($product_type ==1 )
        {
            //获取选择的分公司产品信息
            $branch_product_id = input('branch_product_id');
            if (empty($branch_product_id)) return false;
            $branch_product = $this->callSoaErp('post','/branchcompany/getBranchProduct',['branch_product_id' => $branch_product_id, 'status'=>1])['data'][0];
            $post['branch_product_id'] = $branch_product_id;
        }
        elseif ($product_type == 2)
        {
            $route_template = $this->callSoaErp('post','/product/getRouteTemplate',['route_template_id' => $route_template_id, 'company_id'=>session('user')['company_id'], 'status' => 1])['data'][0];

        }
        else
        {
            return false;
        }

        $post['route_template_id'] = $route_template_id;
        $data['route_template_id'] = $route_template_id;
        $data['status'] = 1;
        $result =  $this->callSoaErp('post','/product/getTeamProductBase',$data);

        if ($result['code'] == 200)
        {
            $post['team_product'] = [];
            $post['product_source'] = [];
            $post['product_type'] = $product_type;
            foreach ($result['data'] as $team_v)
            {
                if ($product_type ==1)
                {
                    $customer_price = $branch_product['customer_price'] ? $branch_product['customer_price'] : 0;
                    $distributor_price = $branch_product['distributor_price'] ? $branch_product['distributor_price'] : 0;
                }
                else
                {
                    $customer_price = $distributor_price= $route_template['once_price'] ? $route_template['once_price'] : 0;
                }
                $team = [];
                $team['team_product_id'] = $team_v['team_product_id'];
                $team['currency_id'] = $team_v['currency_id'];
                $team['customer_price'] = $customer_price;
                $team['distributor_price'] = $distributor_price;
                array_push($post['team_product'], $team);

                $source_result = $this->callSoaErp('post','/product/getTeamProductAllocation',['team_product_id' => $team_v['team_product_id'], 'supplier_type_id' => 11, 'status'=>1]);

                if ($source_result['code'] == 200 && !empty($source_result['data']))
                {
                    foreach ($source_result['data'] as $source_v)
                    {
                        $source = [];
                        $source['source_type_id'] = $source_v['supplier_type_id'];
                        $source['source_id'] = $source_v['source_id'];
                        $source['team_product_id'] = $team_v['team_product_id'];
                        $source['team_product_allocation_id'] = $source_v['team_product_allocation_id'];
                        $source['customer_price'] = $source_v['source_total_price'];
                        $source['distributor_price'] = $source_v['source_total_price'];
                        $source['currency_id'] = $team['currency_id'];
                        array_push($post['product_source'], $source);
                     }
                };


                foreach ($branch_product['source_array'] as $branch_product_source_v)
                {
                    if($branch_product_source_v['supplier_type_id'] == 11)
                    {
                        $branch_source = [];
                        $branch_source['source_type_id'] = $branch_product_source_v['supplier_type_id'];
                        $branch_source['source_id'] = $branch_product_source_v['source_id'];
                        $branch_source['team_product_id'] = $team_v['team_product_id'];
                        $branch_source['customer_price'] = $branch_product_source_v['source_customer_price'];
                        $branch_source['distributor_price'] = $branch_product_source_v['source_distributor_price'];
                        $branch_source['currency_id'] = $branch_product_source_v['price_currency_id'];
                        array_push($post['product_source'], $branch_source);
                    }
                }
            }

            $result =  $this->callSoaErp('post','/ota_product/addSpec',$post);
        }

        return $result;
    }

    public function getTeamAjax()
    {
        $spec_uuid = input('spec_uuid');
        if(!empty($spec_uuid)){
            $data['spec_uuid'] = $spec_uuid;
        }

        $result =  $this->callSoaErp('post','/ota_product/getSpecTeam',$data);

        return $result;
    }

    public function updateTeamAjax()
    {
        $post = Request::instance()->param();

        return $this->callSoaErp('post','/ota_product/updateTeam',$post);
    }

    public function getSourceAjax()
    {
        $spec_uuid = input('spec_uuid');
        if(!empty($spec_uuid)){
            $data['spec_uuid'] = $spec_uuid;
        }

        $result =  $this->callSoaErp('post','/ota_product/getSpecSource',$data);

        return $result;
    }

    public function updateSourceAjax()
    {
        $post = Request::instance()->param();

        return $this->callSoaErp('post','/ota_product/updateSource',$post);
    }



    //修改旅游产品
    public function editProduct()
    {
        $post = Request::instance()->param();

        $where['website_uuid'] = session('website_uuid');
        $where['status'] = 1;
        //所有分类
        $type_result = $this->callSoaErp('post','/ota_product/getProductTypes',$where);

        //城市
        $data1['level'] = 3;
        $country_result = $this->callSoaErp('post', '/system/getCountryCity',$data1);

        //产品详情
        $where['uuid'] = $post['uuid'];
        $product_info = $this->callSoaErp('post','/ota_product/getProductInfo',$where);

        $flight = $product_info['data']['journey']['flight'];
        unset($product_info['data']['journey']['flight']);
        
        $this->assign([
            'type_list' => Help::toTree($type_result['data'], 0, 0, 'ota_product_type_id'),
            'CountryList' => $country_result['data'],
            'flight'     => $flight,
            'product_info' => $product_info['data'],
            'destination_json' => json_encode($product_info['data']['destination']),
            'scenic_spot_json' => json_encode($product_info['data']['scenic_spot'])
        ]);

        return $this->fetch('product_edit');
    }

    //执行修改旅游产品
    public function editProductAjax()
    {

        $post = Request::instance()->param();
        $post['company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');

        return $this->callSoaErp('post','/ota_product/editProduct',$post);

    }


    //执行删除旅游产品
    public function delProductAjax()
    {
        $post = Request::instance()->param();
        $d['uuid'] = Arrays::get($post,'uuid');
        $d['delete_user_id'] = session('user')['user_id'];
        return $this->callSoaErp('post','/ota_product/delOtaProduct',$d);
    }




    /**
     * 获取分公司产品类型
     */
    public function getProductTypeAjax(){
        $product_type = input('product_type');
        if ($product_type == 1)
        {
            $data=[
                "can_watch_company_id" => session('user')['company_id']
            ];
            $result = $this->callSoaErp('post', '/branchcompany/getBranchProductType', $data);
        }

        if ($product_type == 2)
        {
            $data=[
                "company_id" => session('user')['company_id']
            ];
            $result = $this->callSoaErp('post', '/system/getRouteType', $data);
        }

        return $result;

    }

    //获取分公司产品的AJAX
    public function getBranchProductAjax(){
        $data=[
            "company_id"=>session('user')['company_id'],
            'status'=>input('status'),
            'is_like'=>input('is_like')
        ];
        $product_type = input('product_type') ? input('product_type') : 1;
        if ($product_type == 1)
        {
            $data['branch_product_name'] = input('branch_product_name');
            $data['branch_product_number'] = input('branch_product_number');
            $data['branch_product_type_id'] = input('branch_product_type_id');
            $result = $this->callSoaErp('post', '/branchcompany/getBranchProduct', $data);
        }
        else
        {
            $data['route_name'] = input('branch_product_name');
            $data['route_number'] = input('branch_product_number');
            $data['route_type_id'] = input('branch_product_type_id');
            $result = $this->callSoaErp('post', '/product/getRouteTemplate', $data);
            if ($result['code'] == 200)
            {
                $arr = [];
                foreach ($result['data'] as $v)
                {
                    $team_product =  $this->callSoaErp('post','/product/getTeamProductBase',['route_template_id' => $v['route_template_id'], 'status'=>1])['data'][0];
                    if (!empty($team_product)) array_push($arr, $v);
                }
                $result['data'] = $arr;
            }
        }
        return $result;

    }


    public function getJourneyAjax()
    {
        $post = Request::instance()->param();
        $post['status'] = 1;
        $journey_result = $this->callSoaErp('post','/product/getRouteJourney', $post);

        $flight = $this->callSoaErp('post','/product/getRouteFlight', $post);
        $journey_result['flight'] = $flight['data'];

        return $journey_result;
    }

}