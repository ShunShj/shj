<?php
namespace app\index\controller;


use app\common\help\Help;
use app\index\model\ota_product\OtaProductSpec;
use app\index\model\ota_product\OtaProductType;
use app\index\service\OtaProductService;
use \app\index\model\ota_product\OtaProduct as OtaProductModel;
use app\index\service\SourceService;

/**
 * 产品
 */
class OtaProduct extends Base
{
	/**
	 * 首页
	 */
	public function getIndexList(){
		$array = [];

		$array1 = [
			'title'=>'中国',
			'subtitle_array'=>[
					[
					'subtitle'=>'上海',
					'href_type'=>2,
					'without_href'=>'http://www.baidu.com',
					'interior_type'=>'',
					'interior_uuid'=>''
					],
					[
						'subtitle'=>'北京',
						'href_type'=>1,
						'without_href'=>'',
						'interior_type'=>'1',
						'interior_uuid'=>'asdsadadasd333'
					],
			],
			'product_array'=>[
				[
						'ota_advertising_product_uuid'=>'asd32131',
						'product_title'=>'上海自由行2天1夜HIGH翻天',
						'currency_id'=>'3',
						'currency_name'=>'货币名称',
						'unit'=>'RMB',
						'symbol'=>'¥',
						'tag_name'=>'八星推荐',
						'banner_image'=>'http://47.244.53.248:8003/static/uploads/images/20190527/1.jpg',
						'price'=>'123.11'


				],
					[
					'ota_advertising_product_uuid'=>'asd32131',
					'product_title'=>'上海自由行2天1夜HIGH翻天',
					'currency_id'=>'3',
					'currency_name'=>'货币名称',
					'unit'=>'RMB',
					'symbol'=>'¥',
					'tag_name'=>'八星推荐',
					'banner_image'=>'http://47.244.53.248:8003/static/uploads/images/20190527/2.jpg',
					'price'=>'100.22'


				],
				[
					'ota_advertising_product_uuid'=>'asd32131',
					'product_title'=>'上海自由行2天1夜HIGH翻天',
					'currency_id'=>'3',
					'currency_name'=>'货币名称',
					'unit'=>'RMB',
					'symbol'=>'¥',
					'tag_name'=>'八星推荐',
					'banner_image'=>'http://47.244.53.248:8003/static/uploads/images/20190527/3.jpg',
					'price'=>'8000.22'

				],


			]



		];
		$array[] =$array1;

		$this->outPut($array);
	}



    /**
     * 旅游产品分类列表
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/13
     * Time: 14:30
     */
    public function getProductTypes()
    {

        $params = $this->input();

        $paramRule = [
            'website_uuid'=>'string',
        ];

        $this->paramCheckRule($paramRule,$params);

        $product_type_model = new OtaProductType();

        $result = $product_type_model->getProductType($params);
        if (isset($params['pid']))
        {
            $result = $product_type_model->unsetChild($result, $params['id']);
        }

        $this->outPut($result);

    }

    /**
     * 添加旅游产品分类
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/13
     * Time: 14:30
     */
    public function addProductType()
    {
        $params = $this->input();

        $paramRule = [
            'pid' => 'number',
            'type_name'=>'string',
            'status'=>'number',
            'website_uuid' => 'string'
        ];

        $this->paramCheckRule($paramRule,$params);

        $product_type_model = new OtaProductType();

        $type_list = $product_type_model->addProductType($params);

        $this->outPut($type_list);
    }

    /**
     * 修改旅游产品分类
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/13
     * Time: 14:47
     */
    public function editProductType()
    {
        $params = $this->input();
		
        $paramRule = [
            'uuid' => 'string',
            'pid' => 'number',
            'type_name'=>'string',
            'status'=>'number'
        ];
        $this->paramCheckRule($paramRule,$params);

        $product_type_model = new OtaProductType();

        $result = $product_type_model->editProductType($params);

        $this->outPut($result);
    }

    /**
     * 删除旅游产品分类
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/14
     * Time: 10:47
     */
    public function delProductType()
    {
        $params = $this->input();

        $paramRule = [
            'uuid' => 'string',
        ];

        $this->paramCheckRule($paramRule,$params);
        $product_type_model = new OtaProductType();

        $result = $product_type_model->delOtaProductType($params);
        $this->outPut($result);
    }


    /**
     * 获取旅游产品分类详情
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/14
     * Time: 10:47
     */
    public function getTypeInfo()
    {
        $params = $this->input();

        $product_type_model = new OtaProductType();
        $result = $product_type_model->getOne($params);
        $this->outPut($result);
    }

    public function getDestinationAndScenicSpot()
    {
        $params = $this->input();

        $product_type_model = new OtaProductType();
        $result = $product_type_model->getDestinationAndScenicSpot($params);
        $this->outPut($result);
    }


    /**
     * 旅游产品
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/15
     * Time: 9:37
     */
    public function getProducts()
    {

        $params = $this->input();

        $paramRule = [
            'website_uuid'=>'string',
        ];

        $this->paramCheckRule($paramRule,$params);

        $product_model = new OtaProductModel();
        $result = $product_model->getProducts($params);
/*
        foreach ($result as $k=>$v)
        {
            $result[$k]['traffic_icon'] = $v['traffic_icon'] ? explode(',', $v['traffic_icon']) : [];
            $result[$k]['aviation_icon'] = $v['aviation_icon'] ? explode(',', $v['aviation_icon']) : [];
        }*/

        $this->outPut($result);

    }


    /**
     * 添加旅游产品
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/16
     * Time: 14:31
     */
    public function addProduct()
    {

        $params = $this->input();

        //主表数据
        $product = $params['product'];
        $product['website_uuid'] = $params['website_uuid'];
        $product['company_id'] = $params['company_id'];
        $paramRule = [
            'title'=> 'string',
            'website_uuid'=> 'string'
        ];
        $this->paramCheckRule($paramRule,$product);

        //详细介绍数据
        $info = $params['info'];
        $paramRule = [
            'product_description'=> 'string',
            //'video_url'=> 'string',
            'image_urls'=> 'string',
        ];
        $this->paramCheckRule($paramRule,$info);

        $product_type_service = new OtaProductService();

        $result = $product_type_service->editOtaProduct($product, $params['spec'], $info, $params['journey'], $params['now_user_id']);

        $this->outPut($result);
    }


    public function editProduct()
    {
        $params = $this->input();

        //主表数据
        $product = $params['product'];
        $product['website_uuid'] = $params['website_uuid'];
        $paramRule = [
            'sorting' => 'mumber',
            'title'=> 'string',
            'website_uuid'=> 'string',
        ];
        $this->paramCheckRule($paramRule,$product);

        //详细介绍数据
        $info = $params['info'];
        $paramRule = [
            'product_description'=> 'string',
            //'video_url'=> 'string',
            'image_urls'=> 'string',
        ];
        $this->paramCheckRule($paramRule,$info);

        $product_type_service = new OtaProductService();

        $result = $product_type_service->editOtaProduct($product, $params['spec'], $info, $params['journey'], $params['now_user_id']);

        $this->outPut($result);
    }


    public function addSpec()
    {

        $params = $this->input();
        $spec_model = new OtaProductSpec();
        $result = $spec_model->addOne($params);
        $this->outPut($result);
    }

    public function getSpec()
    {
        $params = $this->input();
        $spec_model = new OtaProductSpec();
        $result = $spec_model->getSpec($params);
        $this->outPut($result);
    }

    public function getSpecTeam()
    {
        $params = $this->input();
        $paramRule = [
            'spec_uuid' => 'string',
        ];

        $this->paramCheckRule($paramRule,$params);
        $spec_model = new OtaProductSpec();
        $result = $spec_model->getTeam($params);
        $this->outPut($result);
    }

    public function getSpecSource()
    {
        $params = $this->input();
        $paramRule = [
            'spec_uuid' => 'string',
        ];

        $this->paramCheckRule($paramRule,$params);
        $spec_model = new OtaProductSpec();
        $result = $spec_model->getSource($params);

        $source_service = new  SourceService();

        foreach ($result as $k=>$v)
        {
            $data = $source_service->getSource(['supplier_type_id' => $v['source_type_id'], 'source_id' => $v['source_id']]);
            $result[$k]['source_number'] = empty($data) ? '' : $data['source_number'];
            $result[$k]['source_name'] = empty($data) ? '' : $data['source_name'];
        }
        $this->outPut($result);
    }

    public function updateTeam()
    {
        $params = $this->input();
        $spec_model = new OtaProductSpec();
        $result = $spec_model->updateTeam($params);
        $this->outPut($result);
    }

    public function updateSource()
    {
        $params = $this->input();
        $spec_model = new OtaProductSpec();
        $result = $spec_model->updateSource($params);
        $this->outPut($result);
    }


    /**
     * 删除旅游产品
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/17
     * Time: 16:35
     */
    public function delOtaProduct()
    {
        $params = $this->input();

        $paramRule = [
            'uuid' => 'string',
        ];

        $this->paramCheckRule($paramRule,$params);
        $product_model = new OtaProductModel();
        $params['delete_time'] = time();
        $params['is_del'] = 1;
        $result = $product_model->edit($params, $params['now_user_id']);
        $this->outPut($result);
    }

    /**
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/6/6
     * Time: 16:47huo
     * 获取旅游产品详情 给前端
     */
    public function getProductInfo()
    {
        $params = $this->input();

        $paramRule = [
            'uuid' => 'string',
        ];
        $this->paramCheckRule($paramRule,$params);

        $product_service = new OtaProductService();

        $result = $product_service->getProductOne($params);

        $type_model = new OtaProductType();

        $product_type = $type_model->getProductTypesByProductUuid($result['uuid']);

        $type_uuid = array_column($product_type, 'uuid');

        $arr = $type_model->getDestinationAndScenicSpot(['type_uuid' => $type_uuid]);
        $result['destination_list'] = $arr['destination'];
        $result['scenic_spot_list'] = $arr['scenic_spot'];

        $result['day_num'] = 0;
        if ($result['journey'])
        {
            $journey = $result['journey'];
            unset($journey['flight']);
            $result['day_num'] = count($journey);
        }


        $this->outPut($result);
    }


    public function getChildByType()
    {
        $params = $this->input();
        $paramRule = [
            'type_uuid' => 'string',
            'website_uuid' => 'string'
        ];
        $this->paramCheckRule($paramRule,$params);
        $type_model = new OtaProductType();
        $result = $type_model->getChildByType($params);

        $this->outPut($result);
    }


}