<?php

namespace app\index\model\b2b_tour;
use think\Exception;
use think\Model;
use think\Db;
class B2bTourSetting extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'b2b_tour_setting';
    public function initialize()
    {
    	parent::initialize();
    
    }


    public function addB2bTourSetting($params){

        $data['btb_tour_id'] = $params['btb_tour_id'];
        $data['is_tour_voucher'] = $params['is_tour_voucher'];
        $data['is_pricing'] = $params['is_pricing'];
        $data['is_tip'] = $params['is_tip'];
        $data['is_compulsory_program'] = $params['is_compulsory_program'];
        $data['is_optional_tip'] = $params['is_optional_tip'];
        $data['is_season'] = $params['is_season'];
        $data['is_age'] = $params['is_age'];
        $data['is_ethnicity'] = $params['is_ethnicity'];
        $data['is_child_with_bed'] = $params['is_child_with_bed'];
        $data['is_triple_surcharge'] = $params['is_triple_surcharge'];
        $data['dates_rule'] = $params['dates_rule'];
        $data['department'] = implode(',', $params['department']);
        $data['inbound_team_emails'] = $params['inbound_team_emails'];
        $data['form_type'] = $params['form_type'];
    	$data['status'] = 1;

    	try{
    		Db::name('b2b_tour_setting')->insert($data);
    	} catch (\Exception $e) {

            $result = $e->getMessage();
    		\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
    		exit();
    	}

    }


    public function updateB2bTourSetting($params)
    {
        $data['is_tour_voucher'] = $params['is_tour_voucher'];
        $data['is_pricing'] = $params['is_pricing'];
        $data['is_tip'] = $params['is_tip'];
        $data['is_compulsory_program'] = $params['is_compulsory_program'];
        $data['is_optional_tip'] = $params['is_optional_tip'];
        $data['is_season'] = $params['is_season'];
        $data['is_age'] = $params['is_age'];
        $data['is_ethnicity'] = $params['is_ethnicity'];
        $data['is_child_with_bed'] = $params['is_child_with_bed'];
        $data['is_triple_surcharge'] = $params['is_triple_surcharge'];
        $data['form_type'] = $params['form_type'];
        $data['status'] = 1;

        if ($params['dates_rule'])
        {
            $data['dates_rule'] = $params['dates_rule'];
        }

        if ($params['department'])
        {
            $data['department'] = implode(',', $params['department']);
        }

        if ($params['inbound_team_emails'])
        {
            $data['inbound_team_emails'] = $params['inbound_team_emails'];
        }

        $where['b2b_tour_setting_id'] = $params['b2b_tour_setting_id'];
        $where['btb_tour_id'] = $params['btb_tour_id'];
        try{
            Db::name('b2b_tour_setting')->where($where)->update($data);
            $result = 1;
        } catch (\Exception $e) {

            $result = $e->getMessage();
            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();
        }
        return $result;
    }


    public function getB2bTourSettingByBtbTourId($btb_tour_id)
    {
        try
        {
            $where['btb_tour_id'] = $btb_tour_id;
            $where['status'] = 1;
            $result = $this->table("b2b_tour_setting")->alias('b2b_tour_setting')->where($where)->find();

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