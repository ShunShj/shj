<?php

namespace app\index\model\b2b_tour;
use think\Exception;
use think\Model;
use think\Db;
class B2bTourOptions extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'b2b_tour_options';
    public function initialize()
    {
    	parent::initialize();
    
    }


    public function addB2bTourOptions($params){

        $data['btb_tour_id'] = $params['btb_tour_id'];
        $data['cn_title'] = $params['cn_title'];
        $data['en_title'] = $params['en_title'];
        $data['price'] = $params['price'];
        $data['cost'] = $params['cost'];
        $data['supplier'] = $params['supplier'];
        $data['commission_precentage'] = $params['commission_precentage'];
        $data['commission_fixed_rate'] = $params['commission_fixed_rate'];
        $data['cn_note'] = $params['cn_note'];
        $data['en_note'] = $params['en_note'];
        $data['pay_by'] = $params['pay_by'];
        $data['type'] = $params['type'];
    	$data['status'] = $params['status'];

    	try{
    		Db::name('b2b_tour_options')->insert($data);
    	} catch (\Exception $e) {

            $result = $e->getMessage();
    		\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
    		exit();
    	}

    }

    public function updateB2bTourOptions($params)
    {
        try{
            Db::name('b2b_tour_options')->startTrans();
            Db::name('b2b_tour_options')->where(['btb_tour_id' => $params['btb_tour_id']])->update(['status'=>0]);
            foreach ($params['options'] as $v)
            {
                $data['cn_title'] = $v['cn_title'];
                $data['en_title'] = $v['en_title'];
                $data['price'] = $v['price'];
                $data['cost'] = $v['cost'];
                $data['supplier'] = $v['supplier'];
                $data['commission_precentage'] = $v['commission_precentage'];
                $data['commission_fixed_rate'] = $v['commission_fixed_rate'];
                $data['cn_note'] = $v['cn_note'];
                $data['en_note'] = $v['en_note'];
                $data['pay_by'] = $v['pay_by'];
                $data['type'] = $v['type'];
                $data['status'] = $v['status'];
                if ($v['b2b_tour_options_id'])
                {
                    $where = " b2b_tour_options_id = ". $v['b2b_tour_options_id'];
                    Db::name('b2b_tour_options')->where($where)->update($data);
                }
                else
                {
                    $data['btb_tour_id'] = $params['btb_tour_id'];
                    Db::name('b2b_tour_options')->insert($data);
                }
            }
            Db::name('b2b_tour_options')->commit();
            $result = 1;
        } catch (\Exception $e) {
            Db::name('b2b_tour_options')->rollback();
            $result = $e->getMessage();
            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();
        }

        return $result;
    }


    public function getB2bTourOptionsByBtbTourId($btb_tour_id)
    {
        try
        {
            $where['btb_tour_id'] = $btb_tour_id;

            $result = $this->table("b2b_tour_options")->alias('b2b_tour_options')->where($where)->select();
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