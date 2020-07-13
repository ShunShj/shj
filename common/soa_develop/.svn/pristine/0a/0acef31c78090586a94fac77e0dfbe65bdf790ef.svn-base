<?php

namespace app\index\model\b2b_tour;
use think\Exception;
use think\Model;
use think\Db;
class B2bTourDate extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'b2b_tour_date';
    public function initialize()
    {
    	parent::initialize();
    
    }


    public function addB2bTourDate($params){

        $data['btb_tour_id'] = $params['btb_tour_id'];
        $data['arrival_date'] = $params['arrival_date'];
        $data['departure_date'] = $params['departure_date'];
        $data['session_type'] = $params['session_type'];
        $data['office_contact'] = $params['office_contact'];
        $data['note'] = $params['note'];
    	$data['status'] = 1;

    	try{
    		Db::name('b2b_tour_date')->insert($data);
    	} catch (\Exception $e) {

            $result = $e->getMessage();
    		\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
    		exit();
    	}

    }

    public function updateB2bTourDate($params)
    {
        try{
            Db::name('b2b_tour_date')->startTrans();
            Db::name('b2b_tour_date')->where(['btb_tour_id' => $params['btb_tour_id']])->update(['status' => 0]);
            foreach ($params['date'] as $v)
            {
                $data['arrival_date'] = $v['arrival_date'];
                $data['departure_date'] = $v['departure_date'];
                $data['session_type'] = $v['session_type'];
                $data['office_contact'] = $v['office_contact'];
                $data['note'] = $v['note'];
                $data['status'] = 1;
                if ($v['b2b_tour_date_id'])
                {
                    $where = " b2b_tour_date_id = ". $v['b2b_tour_date_id'];
                    Db::name('b2b_tour_date')->where($where)->update($data);
                }
                else
                {
                    $data['btb_tour_id'] = $params['btb_tour_id'];
                    Db::name('b2b_tour_date')->insert($data);
                }
            }
            Db::name('b2b_tour_date')->commit();
            $result = 1;

        } catch (\Exception $e) {
            Db::name('b2b_tour_date')->rollback();
            $result = $e->getMessage();
            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();
        }
        return $result;
    }

    public function getB2bTourDatesByBtbTourId($btb_tour_id)
    {
        try
        {
            $where['btb_tour_id'] = $btb_tour_id;
            $where['status'] = 1;
            $result = $this->table("b2b_tour_date")->alias('b2b_tour_date')
                ->field(['b2b_tour_date.*', '(select username from user where user.user_id = b2b_tour_date.office_contact) as office_contact_name'])
                ->where($where)->order('arrival_date ASC')->select();
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