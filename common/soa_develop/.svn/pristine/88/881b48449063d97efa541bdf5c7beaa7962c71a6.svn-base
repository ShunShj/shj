<?php

namespace app\index\model\b2b_tour;
use think\Exception;
use think\Model;
use think\Db;
class B2bTourRoom extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'b2b_tour_room';
    public function initialize()
    {
    	parent::initialize();
    
    }


    public function addB2bTourRoom($params){

        $data['btb_tour_id'] = $params['btb_tour_id'];
        $data['room'] = $params['room'];
        $data['capacity'] = $params['capacity'];
        $data['price'] = $params['price'];
    	$data['status'] = 1;

    	try{
    		Db::name('b2b_tour_room')->insert($data);
    	} catch (\Exception $e) {

            $result = $e->getMessage();
    		\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
    		exit();
    	}

    }

    public function updateB2bTourRoom($params)
    {
        try{
            Db::name('b2b_tour_room')->startTrans();
            Db::name('b2b_tour_room')->where(['btb_tour_id' => $params['btb_tour_id']])->update(['status'=>0]);
            foreach ($params['room'] as $v)
            {
                $data['room'] = $v['room'];
                $data['capacity'] = $v['capacity'];
                $data['price'] = $v['price'];
                $data['status'] = 1;
                if ($v['b2b_tour_room_id'])
                {
                    $where = " b2b_tour_room_id = ". $v['b2b_tour_room_id'];
                    Db::name('b2b_tour_room')->where($where)->update($data);
                }
                else
                {
                    $data['btb_tour_id'] = $params['btb_tour_id'];
                    Db::name('b2b_tour_room')->insert($data);
                }
            }
            Db::name('b2b_tour_room')->commit();
            $result = 1;
        } catch (\Exception $e) {
            Db::name('b2b_tour_room')->rollback();
            $result = $e->getMessage();
            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();
        }
        return $result;
    }

    public function getB2bTourRoomByBtbTourId($btb_tour_id)
    {
        try
        {
            $where['btb_tour_id'] = $btb_tour_id;
            $where['status'] = 1;
            $result = $this->table("b2b_tour_room")->alias('b2b_tour_room')->where($where)->select();
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