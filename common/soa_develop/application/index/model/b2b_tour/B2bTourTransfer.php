<?php

namespace app\index\model\b2b_tour;
use think\Exception;
use think\Model;
use think\Db;
class B2bTourTransfer extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'b2b_tour_transfer';
    public function initialize()
    {
    	parent::initialize();
    
    }


    public function addB2bTourTransfer($params){

        $data['btb_tour_id'] = $params['btb_tour_id'];
        $data['airport'] = $params['airport'];
        $data['from'] = $params['from'];
        $data['to'] = $params['to'];
        $data['min_pax'] = $params['min_pax'];
        $data['type'] = $params['type'];
        $data['note'] = $params['note'];
    	$data['status'] = 1;

    	try{
    		Db::name('b2b_tour_transfer')->insert($data);
    	} catch (\Exception $e) {

            $result = $e->getMessage();
    		\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
    		exit();
    	}

    }


    public function updateB2bTourTransfer($params)
    {

        try{
            Db::name('b2b_tour_transfer')->startTrans();
            Db::name('b2b_tour_transfer')->where(['btb_tour_id' => $params['btb_tour_id']])->update(['status'=>0]);
            foreach ($params['transfer'] as $v)
            {
                $data['airport'] = $v['airport'];
                $data['from'] = $v['from'];
                $data['to'] = $v['to'];
                $data['min_pax'] = $v['min_pax'];
                $data['type'] = $v['type'];
                $data['note'] = $v['note'];
                $data['status'] = 1;
                if ($v['b2b_tour_room_id'])
                {
                    $where = " b2b_tour_transfer_id = ". $v['b2b_tour_transfer_id'];
                    Db::name('b2b_tour_transfer')->where($where)->update($data);
                }
                else
                {
                    $data['btb_tour_id'] = $params['btb_tour_id'];
                    Db::name('b2b_tour_transfer')->insert($data);
                }
            }
            Db::name('b2b_tour_transfer')->commit();
            $result = 1;

        } catch (\Exception $e) {
            Db::name('b2b_tour_transfer')->rollback();
            $result = $e->getMessage();
            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();
        }
        return $result;
    }


    public function getB2bTourTransferByBtbTourId($btb_tour_id)
    {
        try
        {
            $where['btb_tour_id'] = $btb_tour_id;
            $where['status'] = 1;
            $result = $this->table("b2b_tour_transfer")->alias('b2b_tour_transfer')->where($where)->select();
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