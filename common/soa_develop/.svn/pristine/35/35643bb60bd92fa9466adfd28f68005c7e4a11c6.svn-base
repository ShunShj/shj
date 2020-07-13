<?php
namespace app\index\model\ota_product;

use app\common\help\Help;
use think\Db;
use think\Model;

class OtaProductJourney extends Model{
 
    protected $table = 'ota_product_journey';

    //自动过滤掉不存在的字段
    protected $field = true;

    public function initialize()
    {
        parent::initialize();
    }

    public function add($journey, $ota_product_uuid, $user_id)
    {
        $flights = $journey['flight'];
        unset($journey['flight']);
        $data = [];
        foreach ($journey as $v)
        {
            $arr = [];
            $arr['the_days'] = $v['the_days'];   //第几天
            $arr['route_journey_title'] = $v['route_journey_title'];   //行程标题
            $arr['route_journey_content'] = $v['route_journey_content'];   //行程内容
            $arr['route_journey_traffic'] = $v['route_journey_traffic'];   //小交通
            $arr['route_journey_stay'] = $v['route_journey_stay'];   //行程内容
            $arr['eat_mark'] = implode(',', $v['eat_mark']);;   //用餐
            $arr['route_journey_breakfast'] = $v['route_journey_breakfast'];   //早餐
            $arr['route_journey_lunch'] = $v['route_journey_lunch'];   //午餐
            $arr['route_journey_dinner'] = $v['route_journey_dinner'];   //晚餐
            $arr['route_journey_scenic_sport'] = $v['route_journey_scenic_sport'];   //景点
            $arr['route_journey_picture'] = implode(',', $v['route_journey_picture']);   //行程图片
            $arr['route_journey_remark'] = $v['route_journey_remark'];   //备注
            $arr['country_id'] = $v['country_id'] ? $v['country_id'] : 0;
            $arr['create_user_id'] = $user_id;       //创建人id
            $arr['create_time'] = time();      //创建时间
            $arr['ota_product_uuid'] = $ota_product_uuid;        //产品uuid
            $arr['status'] = 1;
            array_push($data, $arr);
        }

        if ($this->insertAll($data) === false) return false;
        if ($flights)
        {
            return $this->__addFlight($flights, $ota_product_uuid);
        }
    }


    public function edit($journey, $ota_product_uuid, $user_id)
    {
        $flight = $journey['flight'];
        unset($journey['flight']);
        $where['ota_product_uuid'] = $ota_product_uuid;        //产品uuid
        $where['status'] = 1;
        $journey_the_days = $this->where($where)->column('the_days');   //目前产品已经拥有的天数

        $this->update(['status' => 0], $where);         //将每一天都设为失效

        foreach ($journey as $v)
        {
            $data = [];
            $data['the_days'] = $v['the_days'];   //第几天
            $data['route_journey_title'] = $v['route_journey_title'];   //行程标题
            $data['route_journey_content'] = $v['route_journey_content'];   //行程内容
            $data['route_journey_traffic'] = $v['route_journey_traffic'];   //小交通
            $data['route_journey_stay'] = $v['route_journey_stay'];   //行程内容
            $data['eat_mark'] = implode(',', $v['eat_mark']);;   //用餐
            $data['route_journey_breakfast'] = $v['route_journey_breakfast'];   //早餐
            $data['route_journey_lunch'] = $v['route_journey_lunch'];   //午餐
            $data['route_journey_dinner'] = $v['route_journey_dinner'];   //晚餐
            $data['route_journey_scenic_sport'] = $v['route_journey_scenic_sport'];   //景点
            $data['route_journey_picture'] = implode(',', $v['route_journey_picture']);   //行程图片
            $data['route_journey_remark'] = $v['route_journey_remark'];   //备注
            $data['country_id'] = $v['country_id'] ? $v['country_id'] : 0;
            $data['status'] = 1;

            //ota_product_journey已经存在这天
            if (in_array($v['the_days'], $journey_the_days))
            {
                //存在修改
                $where['the_days'] = $v['the_days'];   //第几天
                unset($where['status']);

                $data['update_user_id'] = $user_id;       //创建人id
                $data['update_time'] = time();      //创建时间
                if ($this->update($data, $where) === false) return false;
            }
            else
            {
                //不存在添加
                $data['the_days'] = $v['the_days'];   //第几天
                $data['ota_product_uuid'] = $ota_product_uuid;

                $data['create_user_id'] = $user_id;       //创建人id
                $data['create_time'] = time();      //创建时间
                if ($this->insert($data) === false) return false;
            }

            if ($this->__addFlight($flight, $ota_product_uuid) === false) return false;
        }
        return true;
    }

    private function __addFlight($flights, $ota_product_uuid)
    {
        //产品下的全部航班设为失效
        Db::table('ota_product_flight')->where(['ota_product_uuid'=>$ota_product_uuid])->update(['status'=>0]);

        if ($flights)
        {
            $data = [];
            foreach ($flights as $v)
            {
                //添加数据
                $v['ota_product_uuid'] = $ota_product_uuid;
                $v['status'] = 1;
                $v['start_time'] = strtotime(date('Y-m-d').' '.$v['start_time']);
                $v['end_time'] = strtotime(date('Y-m-d').' '.$v['end_time']);
                array_push($data, $v);
            }
            return Db::table('ota_product_flight')->insertAll($data);
        }
        return true;
    }


    /**
     * 根据产品uuid 获取行程
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/21
     * Time: 14:21
     * @param string $product_uuid 产品的uuid
     */
    public function getJourneyByProductUuid($product_uuid)
    {
        $journey = $this->alias('j')
            ->field('j.*, c.country_name')
            ->join('country c', 'c.country_id = j.country_id', 'left')
            ->where(['ota_product_uuid' => $product_uuid, 'j.status' => 1])->select();

        if ($journey)
        {
           $journey = Help::modelDataToArr($journey);
           $journey['flight'] = Db::table('ota_product_flight')->where(['ota_product_uuid' => $product_uuid, 'status'=>1])->select();
        }
        return $journey;
    }

    public function getJourneyCountByProductUuid($product_uuid)
    {
        $journey = $this->table("ota_product_journey")
            ->where(['ota_product_uuid' => $product_uuid, 'status' => 1])->count();
        return $journey;
    }

}