<?php
namespace app\index\model\ota_product;

use app\common\help\Help;
use think\Db;
use think\Model;

class OtaProductInfo extends Model{
 
    protected $table = 'ota_product_info';

    //自动过滤掉不存在的字段
    protected $field = true;

    public function initialize()
    {
        parent::initialize();
    }

    public function add($params, $user_id)
    {
        $params['cover_image'] = $params['image_urls']['0'];
        $data['cover_image'] = $params['cover_image'];   //封面图片url
        $data['annex_url'] = $params['annex_url'];  //附件的url
        $data['map_url'] = $params['map_url'];  //地图的url
        $data['traffic_icon'] = implode(',', $params['traffic_icon']);  //交通工具图标
        $data['slogan'] = $params['slogan'];  //标语
        $data['aviation_icon'] = implode(',', $params['aviation_icon']);  //航空公司图标
        $data['product_description'] = $params['product_description'];   //产品简介
        $data['product_content'] = $params['product_content'];   //详细介绍
        $data['status'] = 1;        //状态
        $data['create_user_id'] = $user_id;       //创建人id
        $data['create_time'] = time();      //创建时间
        $data['uuid'] = Help::getUuid();    //uuid
        $data['ota_product_uuid'] = $params['ota_product_uuid'];        //产品uuid

        if ($this->insertGetId($data) === false) return false;
        if ($this->__addVideo($params) === false) return false;
        if ($this->__addImage($params) === false) return false;

        return true;
    }

    public function edit($params, $user_id)
    {
        $info = $this->where([
            'ota_product_uuid' => $params['ota_product_uuid'],
            'status' => 1
        ])->find();

        if (!$info) return $this->add($params, $user_id);

        $params['cover_image'] = $params['image_urls']['0'];
        $data['cover_image'] = $params['cover_image'];   //封面图片url
        $data['annex_url'] = $params['annex_url'];  //附件的url
        $data['map_url'] = $params['map_url'];  //地图的url
        $data['traffic_icon'] = implode(',', $params['traffic_icon']);  //交通工具图标
        $data['slogan'] = $params['slogan'];  //标语
        $data['aviation_icon'] = implode(',', $params['aviation_icon']);  //航空公司图标
        $data['product_description'] = $params['product_description'];   //产品简介
        $data['product_content'] = $params['product_content'];   //详细介绍
        $data['update_user_id'] = $user_id;       //修改人id
        $data['update_time'] = time();      //修改时间
        $result = $this->update($data, ['uuid' => $info['uuid']]);

        if ($result !== false)
        {
            $sta1 = $sta2 = true;
            if ($params['video_urls'])
            {
                $sta1 = $this->__addVideo($params);
            }

            if ($params['image_urls'])
            {
                $sta2 = $this->__addImage($params);
            }
            return $sta1 && $sta2;
        }
        return false;

    }

    private function __addVideo($params)
    {
        //删除所有视频
        Db::table('ota_product_video')->where(['ota_product_uuid' => $params['ota_product_uuid']])->delete();
        if ($params['video_urls'])
        {
            $video_arr = [];
            foreach ($params['video_urls'] as $v)
            {
                $arr = [];
                $arr['video_url'] = $v;
                $arr['video_type'] = 1;
                $arr['ota_product_uuid'] = $params['ota_product_uuid'];
                array_push($video_arr, $arr);
            }

            return Db::table('ota_product_video')->insertAll($video_arr);
        }
        return true;
    }

    private function __addImage($params)
    {
        //删掉所有图片
        Db::table('ota_product_image')->where(['ota_product_uuid' => $params['ota_product_uuid']])->delete();

        $image_arr = [];
        foreach ($params['image_urls'] as $v)
        {
            $arr = [];
            $arr['image_url'] = $v;
            $arr['ota_product_uuid'] = $params['ota_product_uuid'];
            array_push($image_arr, $arr);
        }

        return Db::table('ota_product_image')->insertAll($image_arr);
    }


    public function getInfoByProductUuid($product_uuid)
    {
        $info = $this->where([
            'ota_product_uuid' => $product_uuid,
            'status' => 1
        ])->find();


        if (!$info) return [];

        $info['image'] = Db::table('ota_product_image')->where(['ota_product_uuid' => $product_uuid])->column('image_url');
        $info['video'] = Db::table('ota_product_video')->where(['ota_product_uuid' => $product_uuid])->column('video_url');

        return $info;
    }
}