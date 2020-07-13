<?php
namespace app\index\service;
use app\index\model\ota_product\OtaProduct;
use app\index\model\ota_product\OtaProductInfo;
use app\index\model\ota_product\OtaProductIntroduce;
use app\index\model\ota_product\OtaProductJourney;
use app\index\model\ota_product\OtaProductList;
use app\index\model\ota_product\OtaProductSpec;
use app\common\help\Help;

class OtaProductService{



    /**
     * 修改|添加 旅游产品
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/15
     * Time: 9:55
     * @param $product array 提交的产品主表数据
     * @param $spec array 规格数据
     * @param $info array 详细介绍数据
     * @param $journey array 行程数据
     * @param $user_id  用户id
     * @return boolean 返回结果
     * @throws \think\exception\PDOException
     */
    public function editOtaProduct($product, $spec, $info, $journey, $user_id)
    {
        $product_model = new OtaProduct();
        $spec_model = new OtaProductSpec();
        $info_model = new OtaProductInfo();
        $journey_model = new OtaProductJourney();
        $introduce_model = new OtaProductIntroduce();
        $product_info = $product_model->where(['title' => $product['title'], 'status'=>1])->find();

        $product_model->startTrans();
        try
        {
            if ($product['uuid'])
            {
                //修改
                if ($product_info && $product_info['uuid'] != $product['uuid'])
                {
                    \think\Response::create(['code' => '400', 'msg' => 'title repeat'], 'json')->send();
                    exit;
                }

                //修改主表 product
                if ($product_model->edit($product, $user_id) === false) return false;
                //规格
                if ($spec_model->updateSpec($spec, $product['uuid']) === false) return false;
                //详细介绍
                $info['ota_product_uuid'] = $product['uuid'];
                if ($info_model->edit($info, $user_id) === false) return false;
                //行程
                if ($journey_model->edit($journey, $product['uuid'],$user_id) === false) return false;
                //费用明细 && 订购须知
                if ($introduce_model->edit($product, $user_id) === false) return false;

            }
            else
            {
                if ($product_info)
                {
                    \think\Response::create(['code' => '400', 'msg' => 'title repeat'], 'json')->send();
                    exit;
                }
                //添加
                $product['uuid'] = $info['ota_product_uuid'] = Help::getUuid();
                //插入主表 product

                if ($product_model->add($product, $user_id) === false) return false;

                //规格
                if ($spec){
                    if ($spec_model->updateSpec($spec, $product['uuid']) === false) return false;
                }

                //详细介绍
                if ($info_model->add($info, $user_id) === false) return false;

                //行程
                if ($journey)
                {
                    if ($journey_model->add($journey, $product['uuid'], $user_id) === false) return false;
                }

                //费用明细 && 订购须知
                if ($introduce_model->add($product, $user_id) === false) return false;

            }

            //事务提交
            $product_model->commit();
            return true;
        }
        catch (\Exception $e)
        {
            //事务回滚
            $product_model->rollback();
     
        }

        return false;
    }

    public function getProductOne($post_data)
    {
        $product_model = new OtaProduct();
        //基本信息
        $data = $product_model->getProductByUuid($post_data['uuid']);
        if (!$data) return false;

        //info
        $info_model = new OtaProductInfo();
        $data['info'] = $info_model->getInfoByProductUuid($data['uuid']);
        $data['info']['traffic_icon'] = explode(',', $data['info']['traffic_icon']);
        $data['info']['aviation_icon'] = explode(',', $data['info']['aviation_icon']);
        //行程
        $journey_model = new OtaProductJourney();

        $data['journey'] = $journey_model->getJourneyByProductUuid($data['uuid']);

        //费用明细 && 订购须知
        $introduce_model = new OtaProductIntroduce();
        $introduce = $introduce_model->where(['ota_product_uuid' => $data['uuid']])->find();

        if ($introduce)
        {
            $data['cost_detail'] = $introduce['cost_detail'];
            $data['ordering_information'] = $introduce['ordering_information'];
        }

        //规格信息
        $spec_model = new OtaProductSpec();
        $data['spec'] = $spec_model->getSpec(['ota_product_uuid' => $data['uuid']]);
        return $data;
    }



}