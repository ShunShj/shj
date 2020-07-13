<?php
namespace app\index\model\enquirty;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;

/*
* 需求定制 Hugh
*/
class Enquirty extends Model{ 
	private $_languageList;

    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize(); 
    }

    //获取需求定制
    public function selEnquirty($params){

		$w = '1=1';
    	if($params['enquiry_id']){
    		$w .= " and enquiry_id = {$params['enquiry_id']}";
    	}

    	if($params['company_id']){
    		$w .= " and company_id = {$params['company_id']}";
    	} 

        if($params['website_uuid']){
            $w .= " and website_uuid = '{$params['website_uuid']}'";
        }

    	if(!$params['is_manager'] && $params['user_id']){ //是否经理
    		$w .= " and person_in_charge = {$params['user_id']}"; 
    	}
    	 
    	//创建日期
    	if($params['s_create_time']){
    		$tt = strtotime($params['s_create_time']);
    		$w .= " and create_time>={$tt}";
    	}
    	if($params['e_create_time']){
    		$tt2 = strtotime($params['e_create_time']);
    		$w .= " and create_time<={$tt2}"; 
    	}

    	//目的地
    	if($params['travel_destinations']){
    		$w .= " and travel_destinations like '%{$params['travel_destinations']}%'"; 
    	}
    	//出发日期
    	if($params['s_departure_date']){
    		$t1 = strtotime($params['s_departure_date']); 
    		$w .= " and departure_date>={$t1}"; 
    	}
    	if($params['e_departure_date']){
    		$t2 = strtotime($params['e_departure_date']);
    		$w .= " and departure_date<={$t2}"; 
    	}
    	//联系人
    	if($params['contect_name']){
    		$w .= " and contect_name like '%{$params['contect_name']}%'"; 
    	}
    	//联系电话
		if($params['tel']){
			$w .= " and tel like '%{$params['tel']}%'";
    	}

    	if($params['is_page']){
    		// error_log(print_r($w,1)); 

    		$l['list'] = $this->table('ota_enquiry')->field([
	    		'enquiry_id','uuid','company_id','travel_destinations','departure_date','estimated_number_adults','estimated_number_children','estimated_number_the_elderly','estimated_number_bed_free_children','travel_days','accommodation_standard','contect_name','tel','email','language_id','remark','create_time','update_time','create_user_id','update_user_id','person_in_charge','status',
	    		'(select language_name from language where language.language_id=ota_enquiry.language_id) as language_name',
                '(select nickname from user where user.user_id = ota_enquiry.create_user_id) as c_nickname',
                '(select nickname from user where user.user_id = ota_enquiry.update_user_id) as u_nickname',
                '(select nickname from user where user.user_id = ota_enquiry.person_in_charge) as person_in_charge_nickname',
	    	])->where($w)->limit(($params['page']-1)*$params['size'],$params['size'])->select();

    		$l['count'] = $this->table('ota_enquiry')->field(['enquiry_id'])->where($w)->count();

    		$l['page_count'] = ceil($l['count']/$params['size']);

			return $l;
    	}else{ 
    		// error_log(print_r($w,1));
			return $enquiry = $this->table('ota_enquiry')->field([
	    		'enquiry_id','uuid','company_id','travel_destinations','departure_date','estimated_number_adults','estimated_number_children','estimated_number_the_elderly','estimated_number_bed_free_children','travel_days','accommodation_standard','contect_name','tel','email','language_id','remark','create_time','update_time','create_user_id','update_user_id','person_in_charge','status',
	    		'(select language_name from language where language.language_id=ota_enquiry.language_id) as language_name',
                '(select nickname from user where user.user_id = ota_enquiry.create_user_id) as c_nickname',
                '(select nickname from user where user.user_id = ota_enquiry.update_user_id) as u_nickname',
                '(select nickname from user where user.user_id = ota_enquiry.person_in_charge) as person_in_charge_nickname',
	    	])->where($w)->select();
    	}	 	

    }


    //添加需求定制
    public function addEnquirty($params){
    	try{
    		$d['company_id'] = $params['company_id'];
    		$d['uuid'] = Help::getUuid();
    		$d['travel_destinations'] = $params['travel_destinations'];	
	    	$d['departure_date'] = strtotime($params['departure_date']);	
	    	$d['estimated_number_adults'] = $params['estimated_number_adults']?:0; 
	    	$d['estimated_number_children'] = $params['estimated_number_children']?:0; 
	    	$d['estimated_number_the_elderly'] = $params['estimated_number_the_elderly']?:0; 
	    	$d['estimated_number_bed_free_children'] = $params['estimated_number_bed_free_children']?:0; 
	    	$d['travel_days'] = $params['travel_days']; 
	    	$d['accommodation_standard'] = $params['accommodation_standard']; 
	    	$d['contect_name'] = $params['contect_name']; 
	    	$d['tel'] = $params['tel']; 
	    	$d['email'] = $params['email']; 
	    	$d['language_id'] = $params['language_id']; 
	    	$d['remark'] = $params['remark']; 
	    	$d['person_in_charge'] = $params['user_id']; 
	    	$d['create_user_id'] = $params['user_id'];
	        $d['update_user_id'] = $params['user_id']; 
	    	$d['create_time'] = time(); 
	    	$d['update_time'] = time(); 
	    	$d['status'] = 1;
            $d['website_uuid'] = $params['website_uuid'];
	    	// error_log(print_r($d,1));
	    	Db::table('ota_enquiry')->insert($d);
	        $id = Db::name('ota_enquiry')->getLastInsID(); 
	        return $id;
    	}catch (\Exception $e) { 
            return $result = $e->getMessage(); 
        } 
    }

    /***
    * 修改需求定制
    **/
    public function editEnquirty($params){
		try{
			$w['enquiry_id'] = $params['enquiry_id'];
    		
    		$d['travel_destinations'] = $params['travel_destinations'];	
	    	$d['departure_date'] = strtotime($params['departure_date']);	
	    	$d['estimated_number_adults'] = $params['estimated_number_adults']?:0; 
	    	$d['estimated_number_children'] = $params['estimated_number_children']?:0; 
	    	$d['estimated_number_the_elderly'] = $params['estimated_number_the_elderly']?:0; 
	    	$d['estimated_number_bed_free_children'] = $params['estimated_number_bed_free_children']?:0; 
	    	$d['travel_days'] = $params['travel_days']; 
	    	$d['accommodation_standard'] = $params['accommodation_standard']; 
	    	$d['contect_name'] = $params['contect_name']; 
	    	$d['tel'] = $params['tel']; 
	    	$d['email'] = $params['email']; 
	    	$d['language_id'] = $params['language_id']; 
	    	$d['remark'] = $params['remark'];   
	        $d['update_user_id'] = $params['user_id'];  
	    	$d['update_time'] = time(); 
	    	$d['status'] = $params['status'];
	    	Db::table('ota_enquiry')->where($w)->update($d);
	        return true;
    	}catch (\Exception $e) { 
            return $result = $e->getMessage(); 
        } 
    }

    /**
    * 修改需求定制-负责人
    $this->startTrans();
	$this->commit();
	$this->rollback();
    **/
    public function editEnquirtyPersonInCharge($params){
    	try{
    		$w['enquiry_id'] = ['in',$params['enquiry_id']];
    		$u['person_in_charge'] = $params['person_in_charge'];
    		$u['update_user_id'] = $params['user_id'];  
	    	$u['update_time'] = time(); 
    		Db::table('ota_enquiry')->where($w)->update($u);
    		return true;
    	}catch (\Exception $e) { 
            return $result = $e->getMessage(); 
        } 	
    }



}