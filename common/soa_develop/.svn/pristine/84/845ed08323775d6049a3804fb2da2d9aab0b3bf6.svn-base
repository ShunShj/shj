<?php

namespace app\index\model\ota_system;
use app\common\help\Help;
use think\Model;

use think\config;
use think\Db;
class OtaWebsitePay extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'ota_website_pay';

    public function initialize()
    {

        parent::initialize();

    }

    public function openOtaWebsitePay($params){

        $data['status'] = 1;
        if (isset($params['status']))
        {
            $data['status'] = $params['status'];
        }

        if (isset($params['pay_name']))
        {
            $data['pay_name'] = $params['pay_name'];
        }

        if (isset($params['pay_type']))
        {
            $data['pay_type'] = $params['pay_type'];
        }

        if (isset($params['pay_desc']))
        {
            $data['pay_desc'] = json_encode($params['pay_desc']);
        }

        $this->startTrans();
        try{
            if (isset($params['ota_website_pay_id']) && $params['ota_website_pay_id'] > 0)
            {
                $where['ota_website_pay_id'] = $params['ota_website_pay_id'];
                $where['website_uuid'] = $params['website_uuid'];
                $this->where($where)->update($data);

            }
            else
            {
                $data['website_uuid'] = $params['website_uuid'];

                $this->insertGetId($data);
            }

            $result = 1;
            // 提交事务
            $this->commit();

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            $this->rollback();
            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();

        }

        return $result;
    }


    public function getOtaWebsitePay($params)
    {
        $where = " 1 = 1 ";
        if($params['website_uuid']){
            $where .= " and website_uuid = '" . $params['website_uuid'] . "'";
        }

        if($params['ota_website_pay_id']){
            $where .= " and ota_website_pay_id = " . $params['ota_website_pay_id'];
        }

        $result = $this->where($where)->select();

        return $result;
    }

}