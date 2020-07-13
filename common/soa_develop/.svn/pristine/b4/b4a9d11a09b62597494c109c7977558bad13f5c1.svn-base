<?php
namespace app\index\model\ota_product;

use think\Db;
use think\Model;

class OtaProductIntroduce extends Model{
 
    protected $table = 'ota_product_introduce';

    //自动过滤掉不存在的字段
    protected $field = true;

    public function initialize()
    {
        parent::initialize();
    }

    public function add($introduce, $user_id)
    {

        $data['cost_detail'] = $introduce['cost_detail'];      //费用明细
        $data['ordering_information'] = $introduce['ordering_information'];        //订购须知
        $data['status'] = 1;        //状态
        $data['create_user_id'] = $user_id;       //创建人id
        $data['create_time'] = time();      //创建时间
        $data['ota_product_uuid'] = $introduce['uuid']; //旅途产品uuid

        return $this->insertGetId($data);
    }

    public function edit($introduce, $user_id)
    {
        $data['cost_detail'] = $introduce['cost_detail'];      //费用明细
        $data['ordering_information'] = $introduce['ordering_information'];        //订购须知
        $data['status'] = 1;
        $data['update_user_id'] = $user_id;       //修改人id
        $data['update_time'] = time();      //修改时间
        $data['ota_product_uuid'] = $introduce['uuid']; //旅途产品uuid
        $this->update($data, ['ota_product_uuid' => $introduce['uuid']]);
    }


}