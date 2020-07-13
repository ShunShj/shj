<?php

namespace app\index\model\ota_system;
use app\common\help\Help;
use think\Exception;
use think\Model;

use think\config;
use think\Db;
class OtaSubscribe extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'ota_subscribe';

    public function initialize()
    {

        parent::initialize();

    }

    public function addSubscribe($params){

        $data['uuid'] =
        $data['subscribe_email'] = $params['subscribe_email'];
        $data['website_uuid'] = $params['website_uuid'];
        $data['member_uuid'] = $params['member_uuid'];
        $data['create_time'] = time();
        $this->startTrans();
        try{
            $this->insert($data);

            $result = 1;
            // 提交事务
            Db::commit();

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            $this->rollback();

        }

        return $result;
    }




    public function getSubscribe($params)
    {

        $where = " 1=1 ";

        //订阅邮箱
        if (!empty($params['subscribe_email']))
        {
            $where .= "and s.subscribe_email like '%". $params['subscribe_email']."%'";
        }

        //订阅人
        if (!empty($params['nickname']))
        {
            $where .= "and m.nickname like '%". $params['nickname']."%'";
        }

        if (!empty($params['website_uuid']))
        {
            $where .= "and s.website_uuid = '". $params['website_uuid']."'";
        }

        try
        {
            if (isset($params['page']) && $params['size'])
            {
                $count = $this->alias('s')->where($where)
                    ->join('ota_member m', 's.member_uuid = m.uuid', 'LEFT')
                    ->count();

                $list = $this->alias('s')
                    ->field(['s.*', 'm.nickname'])
                    ->join('ota_member m', 's.member_uuid = m.uuid', 'LEFT')
                    ->where($where)->order('create_time DESC')->limit(($params['page']-1)*$params['size'], $params['size'])->select();

                $result = [
                    'count' => $count,
                    'list' => $list,
                    'page_count' => ceil($count / $params['size'])
                ];
            }
            else
            {

                $result = $this->alias('s')
                    ->field(['s.*', 'm.nickname'])
                    ->join('ota_member m', 's.member_uuid = m.uuid', 'LEFT')
                    ->where($where)->order('create_time DESC')->select();
            }
        }
        catch (Exception $e)
        {
            error_log(print_r($e->getMessage(),1));
        }

        return $result;
    }


}