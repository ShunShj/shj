<?php

namespace app\index\model\b2b_tour;
use think\Exception;
use think\Model;
use think\Db;
class B2bTourItinerary extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'b2b_tour_itinerary';
    public function initialize()
    {
    	parent::initialize();
    
    }


    public function addB2bTourItinerary($params){

        $data['btb_tour_id'] = $params['btb_tour_id'];
        $data['the_day'] = $params['the_day'];
        $data['hotel_cn'] = $params['hotel_cn'];
        $data['hotel_en'] = $params['hotel_en'];
        $data['info_cn'] = $params['info_cn'];
        $data['info_en'] = $params['info_en'];
    	$data['status'] = 1;

    	try{
    		Db::name('b2b_tour_itinerary')->insert($data);
    	} catch (\Exception $e) {

            $result = $e->getMessage();
    		\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
    		exit();
    	}

    }


    public function updateB2bTourItinerary($params)
    {
        try{
            Db::name('b2b_tour_itinerary')->startTrans();
            Db::name('b2b_tour_itinerary')->where(['btb_tour_id' => $params['btb_tour_id']])->update(['status'=>0]);
            foreach ($params['itinerary'] as $v)
            {
                $data['the_day'] = $v['the_day'];
                $data['hotel_cn'] = $v['hotel_cn'];
                $data['hotel_en'] = $v['hotel_en'];
                $data['info_cn'] = $v['info_cn'];
                $data['info_en'] = $v['info_en'];
                $data['status'] = 1;
                if ($v['b2b_tour_itinerary_id'])
                {
                    $where = " b2b_tour_itinerary_id = ". $v['b2b_tour_itinerary_id'];
                    Db::name('b2b_tour_itinerary')->where($where)->update($data);
                }
                else
                {
                    $data['btb_tour_id'] = $params['btb_tour_id'];
                    Db::name('b2b_tour_itinerary')->insert($data);
                }
            }
            Db::name('b2b_tour_itinerary')->commit();
            $result = 1;
        } catch (\Exception $e) {
            Db::name('b2b_tour_itinerary')->rollback();
            $result = $e->getMessage();
            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();
        }

        return $result;
    }

    public function getB2bTourItineraryByBtbTourId($btb_tour_id)
    {
        try
        {
            $where['btb_tour_id'] = $btb_tour_id;
            $where['status'] = 1;
            $result = $this->table("b2b_tour_itinerary")->alias('b2b_tour_itinerary')->where($where)->select();
        }
        catch (Exception $e)
        {
            $result = $e->getMessage();
            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();
        }

        return $result;
    }



}