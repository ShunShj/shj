<?php

namespace app\index\model\b2b_tour;
use think\Exception;
use think\Model;
use think\Db;
class B2bTourCommission extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'b2b_tour_commission';
    public function initialize()
    {
    	parent::initialize();
    
    }


    public function addB2bTourCommission($params){

        $data['btb_tour_id'] = $params['btb_tour_id'];

        if ($params['adult_grpss_cn'])
        {
            $data['adult_grpss_cn'] = $params['adult_grpss_cn'];
        }

        if ($params['adult_comm_cn'])
        {
            $data['adult_comm_cn'] = $params['adult_comm_cn'];
        }

        if ($params['adult_nett_cn'])
        {
            $data['adult_nett_cn'] = $params['adult_nett_cn'];
        }

        if ($params['child_wbed_gross_cn'])
        {
            $data['child_wbed_gross_cn'] = $params['child_wbed_gross_cn'];
        }

        if ($params['child_wbed_comm_cn'])
        {
            $data['child_wbed_comm_cn'] = $params['child_wbed_comm_cn'];
        }

        if ($params['child_wbed_nett_cn'])
        {
            $data['child_wbed_nett_cn'] = $params['child_wbed_nett_cn'];
        }

        if ($params['child_nbed_nett_cn'])
        {
            $data['child_nbed_nett_cn'] = $params['child_nbed_nett_cn'];
        }

        if ($params['adult_nett_en'])
        {
            $data['adult_nett_en'] = $params['adult_nett_en'];
        }

        if ($params['child_wbed_nett_en'])
        {
            $data['child_wbed_nett_en'] = $params['child_wbed_nett_en'];
        }

        if ($params['child_nbed_nett_en'])
        {
            $data['child_nbed_nett_en'] = $params['child_nbed_nett_en'];
        }

        if ($params['single_supp'])
        {
            $data['single_supp'] = $params['single_supp'];
        }

        if ($params['hotel_twin'])
        {
            $data['hotel_twin'] = $params['hotel_twin'];
        }

        if ($params['triple'])
        {
            $data['triple'] = $params['triple'];
        }

        if ($params['transfer'])
        {
            $data['transfer'] = $params['transfer'];
        }

        if ($params['infant'])
        {
            $data['infant'] = $params['infant'];
        }

        if ($params['tipping'])
        {
            $data['tipping'] = $params['tipping'];
        }

        if ($params['compulsory_program'])
        {
            $data['compulsory_program'] = $params['compulsory_program'];
        }

        if ($params['season_id'])
        {
            $data['season_id'] = $params['season_id'];
        }

        if ($params['inbound_quad_room_net'])
        {
            $data['inbound_quad_room_net'] = $params['inbound_quad_room_net'];
        }

        if ($params['inbound_triple_room_net'])
        {
            $data['inbound_triple_room_net'] = $params['inbound_triple_room_net'];
        }

        if ($params['inbound_twin_room_ent'])
        {
            $data['inbound_twin_room_ent'] = $params['inbound_twin_room_ent'];
        }

        if ($params['inbound_single_room_net'])
        {
            $data['inbound_single_room_net'] = $params['inbound_single_room_net'];
        }

        if ($params['status'])
        {
            $data['status'] = $params['status'];
        }

        if ($params['note'])
        {
            $data['note'] = $params['note'];
        }

    	try{
    		Db::name('b2b_tour_commission')->insert($data);
    	} catch (\Exception $e) {

            $result = $e->getMessage();
    		\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
    		exit();
    	}

    }


    public function updateB2bTourCommission($params)
    {
        if ($params['adult_grpss_cn'])
        {
            $data['adult_grpss_cn'] = $params['adult_grpss_cn'];
        }

        if ($params['adult_comm_cn'])
        {
            $data['adult_comm_cn'] = $params['adult_comm_cn'];
        }

        if ($params['adult_nett_cn'])
        {
            $data['adult_nett_cn'] = $params['adult_nett_cn'];
        }

        if ($params['child_wbed_gross_cn'])
        {
            $data['child_wbed_gross_cn'] = $params['child_wbed_gross_cn'];
        }

        if ($params['child_wbed_comm_cn'])
        {
            $data['child_wbed_comm_cn'] = $params['child_wbed_comm_cn'];
        }

        if ($params['child_wbed_nett_cn'])
        {
            $data['child_wbed_nett_cn'] = $params['child_wbed_nett_cn'];
        }

        if ($params['child_nbed_nett_cn'])
        {
            $data['child_nbed_nett_cn'] = $params['child_nbed_nett_cn'];
        }

        if ($params['adult_nett_en'])
        {
            $data['adult_nett_en'] = $params['adult_nett_en'];
        }

        if ($params['child_wbed_nett_en'])
        {
            $data['child_wbed_nett_en'] = $params['child_wbed_nett_en'];
        }

        if ($params['child_nbed_nett_en'])
        {
            $data['child_nbed_nett_en'] = $params['child_nbed_nett_en'];
        }

        if ($params['single_supp'])
        {
            $data['single_supp'] = $params['single_supp'];
        }

        if ($params['hotel_twin'])
        {
            $data['hotel_twin'] = $params['hotel_twin'];
        }

        if ($params['triple'])
        {
            $data['triple'] = $params['triple'];
        }

        if ($params['transfer'])
        {
            $data['transfer'] = $params['transfer'];
        }

        if ($params['infant'])
        {
            $data['infant'] = $params['infant'];
        }

        if ($params['tipping'])
        {
            $data['tipping'] = $params['tipping'];
        }

        if ($params['compulsory_program'])
        {
            $data['compulsory_program'] = $params['compulsory_program'];
        }

        if ($params['season_id'])
        {
            $data['season_id'] = $params['season_id'];
        }

        if ($params['inbound_quad_room_net'])
        {
            $data['inbound_quad_room_net'] = $params['inbound_quad_room_net'];
        }

        if ($params['inbound_triple_room_net'])
        {
            $data['inbound_triple_room_net'] = $params['inbound_triple_room_net'];
        }

        if ($params['inbound_twin_room_ent'])
        {
            $data['inbound_twin_room_ent'] = $params['inbound_twin_room_ent'];
        }

        if ($params['inbound_single_room_net'])
        {
            $data['inbound_single_room_net'] = $params['inbound_single_room_net'];
        }

        if ($params['status'])
        {
            $data['status'] = $params['status'];
        }

        if ($params['note'])
        {
            $data['note'] = $params['note'];
        }

        try{
            Db::name('b2b_tour_commission')->where(['b2b_tour_commission_id' => $params['b2b_tour_commission_id']])->update($data);

            $result = 1;

        } catch (\Exception $e) {

            $result = $e->getMessage();
            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();
        }

        return $result;

    }

    public function getB2bTourCommissionByBtbTourId($btb_tour_id)
    {
        try
        {
            $where['btb_tour_id'] = $btb_tour_id;

            $result = $this->table("b2b_tour_commission")->alias('b2b_tour_commission')->where($where)->find();
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