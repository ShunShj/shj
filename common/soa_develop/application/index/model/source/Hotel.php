<?php

namespace app\index\model\source;
use app\index\model\system_alert_event\InStationLetter;
use app\index\model\system_alert_event\SystemAlertSetting;
use think\Model;
use app\common\help\Help;


use think\config;

use think\Db;
use app\index\service\PublicService;
class Hotel extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'hotel';
    private $_languageList;
    private $_public_service;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	$this->_public_service = new PublicService();
    	parent::initialize();
    
    }

    /**
     * 添加酒店
     * 胡
     */
    public function addHotel($params){
    	$t = time();
		
    	$data['source_number'] = $params['source_number'];
    	$data['room_name'] = $params['room_name'];
    	$data['room_type'] = $params['room_type'];
        $data['supplier_id'] = $params['supplier_id'];
        $data['belong_supplier_id'] = $params['supplier_id'];
        $data['supplier_type'] = 1;
//        if(isset($params['guest_number'])){
//            $data['guest_number'] = $params['guest_number'];
//        }
        if(!empty($params['guest_number'])){
            $data['guest_number'] = $params['guest_number'];

        }
        if(isset($params['room_area'])){
            $data['room_area'] = $params['room_area'];
        }
        if(isset($params['phone'])){
            $data['phone'] = $params['phone'];
        }
        if(isset($params['address'])){
            $data['address'] = $params['address'];
        }
        if(isset($params['floor'])){
            $data['floor'] = $params['floor'];
        }
        if(isset($params['level_name'])){
            $data['level_name'] = $params['level_name'];
        }
        if(isset($params['is_add_bed'])){
            $data['is_add_bed'] = $params['is_add_bed'];
        }
        if(isset($params['free_wifi'])){
            $data['free_wifi'] = $params['free_wifi'];
        }
        if(isset($params['smoke_treatment'])){
            $data['smoke_treatment'] = $params['smoke_treatment'];
        }
    	$data['company_id'] = $params['choose_company_id'];

    	if(isset($params['remark'])){
    		$data['remark'] = $params['remark'];
    	}
		$data['default_language_id'] = $params['lang_id'];
    	$data['create_time'] = $t;  	
    	$data['create_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['user_id'];
    	$data['status'] = $params['status'];   		

    	
    	Db::startTrans();
    	try{
    		$pk_id = Db::name('hotel')->insertGetId($data);
			
//     		$source_params = [
//     			'source_number'=>$data['source_number'].'-'.$pk_id	
//     		];
//     		Db::name('hotel')->where("hotel_id = $pk_id")->update($source_params);
			$this->_public_service->setNumber('hotel', 'hotel_id', $pk_id, 'source_number', $data['source_number'], $pk_id);    		
    		
    		
    		$language_data['source_number'] = $params['source_number'];
    		$language_data['room_name'] = $params['room_name'];
    		$language_data['language_id']=$params['lang_id'];
    		$language_data['create_time'] = $t;
    		$language_data['create_user_id'] = $params['user_id'];
    		$language_data['update_time'] = $t;
    		$language_data['update_user_id'] = $params['user_id'];
    		$language_data['status'] = 1;
    		Db::name('hotel_language')->insertGetId($language_data);
    		//统价
  			$source_price['normal_price']=$params['normal_price'];
  			$source_price['normal_settlement_price']=$params['normal_settlement_price'];
            $source_price['payment_currency_type']=$params['payment_currency_type'];


  			$source_price['supplier_type_id'] = 2;
  			$source_price['pk_id'] = $pk_id;
  			Db::name('source_price')->insert($source_price);

  			//判断是否有代理商
  			if(!empty($params['agent_id'])){
  				$data['source_number']= Help::getNumber(52);
  				$data['supplier_id'] =	$params['agent_id'];
  				$data['belong_supplier_id'] =	$params['supplier_id'];
  				$data['supplier_type'] = 2;
  				$pk_id = Db::name('hotel')->insertGetId($data);
  				$this->_public_service->setNumber('hotel', 'hotel_id', $pk_id, 'source_number', $data['source_number'], $pk_id);
  				$source_price['pk_id'] = $pk_id;
  				Db::name('source_price')->insert($source_price);
  				
  				$language_data['source_number'] = $data['source_number'];
  				$language_data['status'] = 1;
  				Db::name('hotel_language')->insert($language_data);
  			} 			
    		$result = 1;
    		// 提交事务
    		Db::commit();
    
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		Db::rollback();
    		//\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
    		//exit();
    
    	}	
  
    	return $result;
    }
    
    /**
     * 获取酒店
     * 胡
     */
    public function getHotel($params,$is_count=false,$is_page=false,$page=null,$page_size=20){

    	$data = "1=1 ";
    	
    	if($params['is_branch_product'] == 1){
    		if(!empty($params['source_name'])){
    			$data.= " and hotel.room_name like '%".$params['source_name']."%'";
    		}
    		if(!empty($params['source_number'])){
    			$data.= " and hotel.source_number like '%".$params['source_number']."%'";
    		}
    		if(!empty($params['supplier_name'])){
    			$data.= " and supplier_name like '%".$params['supplier_name']."%'";
    		}
    	}else{
    		if(!empty($params['room_name'])){
    			$data.= " and hotel.room_name like '%".$params['room_name']."%'";
    		}
    		if(!empty($params['source_number'])){
    			$data.= " and hotel.source_number = '".$params['source_number']."'";
    		}
    	}
 
    	if(is_numeric($params['status'])){
    		$data.= " and hotel.status = ".$params['status'];
    	}
    	if(!empty($params['hotel_id'])){
    		$data.= " and hotel.hotel_id = '".$params['hotel_id']."'";
    	}

    	if(!empty($params['supplier_id'])){
    		$data.= " and hotel.supplier_id = '".$params['supplier_id']."'";
    	}
        if(!empty($params['supplier_type'])){
    		$data.= " and hotel.supplier_type = '".$params['supplier_type']."'";
    	}
    	if(!empty($params['belong_supplier_id'])){
    		$data.= " and hotel.belong_supplier_id = '".$params['belong_supplier_id']."'";
    	}
    	if(is_numeric($params['company_id'])){
    		$data.= " and hotel.company_id = '".$params['company_id']."'";
    	}
    	
    	
        if($is_count==true){
            $result = $this->table("hotel")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("hotel")->
                join("source_price", 'source_price.pk_id = hotel.hotel_id and source_price.supplier_type_id=2', 'left')->
                join('currency', 'currency.currency_id = source_price.payment_currency_type','left')->
                join('supplier', 'supplier.supplier_id = hotel.supplier_id','left')->
                join('company', 'company.company_id= hotel.company_id','left')->
                where($data)->limit($page, $page_size)->order('create_time desc')->
                field(['hotel.hotel_id', "hotel.room_name", 'hotel.source_number',
                    "hotel.room_type",
                    'hotel.guest_number',
                    'hotel.free_wifi', "hotel.room_area",
                    'hotel.floor', 'hotel.is_add_bed', 'hotel.smoke_treatment', 'hotel.remark',
                    'hotel.default_language_id',
                    'hotel.level_name','hotel.phone','hotel.address',
                    'hotel.supplier_id', 'supplier.supplier_name',
                    'hotel.supplier_type', 'hotel.belong_supplier_id',
                    "hotel.company_id", 'company.company_name', 'currency.currency_name',
                	'currency.unit',	
                    'hotel.create_time', 'source_price.payment_currency_type',
                    'source_price.normal_price', 'source_price.normal_settlement_price',
                    "(select nickname  from user where user.user_id = hotel.create_user_id)"=> 'create_user_name',
                    "(select nickname  from user where user.user_id = hotel.update_user_id)"=> 'update_user_name',
                    'hotel.update_time', 'hotel.create_time', "hotel.status",
                ])->select();
            }else{
                $result = $this->table("hotel")->alias('hotel')->
                join("source_price", 'source_price.pk_id = hotel.hotel_id and source_price.supplier_type_id=2', 'left')->
                join('currency', 'currency.currency_id = source_price.payment_currency_type','left')->
                join('supplier', 'supplier.supplier_id = hotel.supplier_id','left')->
                join('company', 'company.company_id= hotel.company_id','left')->
                where($data)->order('create_time desc')->
                field(['hotel.hotel_id', "hotel.room_name", 'hotel.source_number',
                    "hotel.room_type",
                    'hotel.guest_number',
                    'hotel.free_wifi', "hotel.room_area",
                    'hotel.floor', 'hotel.is_add_bed', 'hotel.smoke_treatment', 'hotel.remark',
                    'hotel.default_language_id',
                    'hotel.level_name','hotel.phone','hotel.address',
                    'hotel.supplier_id', 'supplier.supplier_name',
                    'hotel.supplier_type', 'hotel.belong_supplier_id',
                    "hotel.company_id", 'company.company_name', 'currency.currency_name',
                	'currency.unit',
                    'hotel.create_time', 'source_price.payment_currency_type',
                    'source_price.normal_price', 'source_price.normal_settlement_price',
                    "(select nickname  from user where user.user_id = hotel.create_user_id)"                                              => 'create_user_name',
                    "(select nickname  from user where user.user_id = hotel.update_user_id)"                                              => 'update_user_name',
                    'hotel.update_time', 'hotel.create_time', "hotel.status",
                ])->select();
            }
        }

        
        return $result;
    
    }
    /**
     * 获取酒店数据根据酒店_ID与lang_id
     */
    public function getHotelByHotelIdLangId($params){
    	 
    	$lang_id = $params['lang_id'];
    	$data['language_id'] = $lang_id;
    	$data['source_number'] = $params['source_number'];
    	$result = $this->table('hotel_language')->
    	where($data)->find();

    	return $result;
    }
    
    /**
     * 修改酒店多语言数据根据酒店 多语言ID
     */
    public function updateHotelLanguageByHotelLanguageId($params){
    	 
    	$t = time();
    	$user_id = $params['user_id'];
    	 
    	$original_number = $params['data'][0]['source_number'];
    	 
    	$original_data['source_number'] = $original_number;
    	 
    
    	$params = $params['data'];
   
    	//原始数据主键   	 
    	$original_result = $this->getHotel($original_data);

    	$default_language_id = $original_result[0]['default_language_id'];
    	
    	$this->startTrans();
    	try{
    		for($i=0;$i<count($params);$i++){
    		
    			$data = [];
    			if(!trim($params[$i]['room_name'])==''){
    			
    				$data['room_name'] = $params[$i]['room_name'];
    				$data['update_time'] = $t;
    				$data['update_user_id'] = $user_id;
    
    				if(is_numeric($params[$i]['hotel_language_id'])){
    					
    					$this->table('hotel_language')->where("hotel_language_id = ".$params[$i]['hotel_language_id'])->update($data);
    					
    					//再查询是否是原始数据  如果是原始数据那么原始 数据也要更改
    					if($default_language_id == $params[$i]['lang_id']){
    							
    						$this->where("source_number = '$original_number'")->update($data);

    					}
    				}else{
    					
    					$data['create_time'] = $t;
    					$data['create_user_id'] = $user_id;
    					$data['status'] = 1;
    					$data['source_number'] = $original_number;
    					$data['language_id'] = $params[$i]['lang_id'];   
    					$this->table("hotel_language")->insert($data);

    				}
    			}
    		}
    
    		$result = 1;
    		// 提交事务
    		$this->commit();
    		 
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		$this->rollback();
    		 
    	}
    
    	return $result;
    
    }
    /**
     * 修改酒店
     */
    public function updateHotelByHotelId($params){

    	$t = time();
    	
    	if(!empty($params['room_name'])){
    		$data['room_name'] = $params['room_name'];
    	
    	}
    	if(!empty($params['room_type'])){
    		$data['room_type'] = $params['room_type'];
    		 
    	}
    	if(!empty($params['supplier_id'])){
    		$data['belong_supplier_id'] = $params['supplier_id'];
    		 
    	}
    	if(!empty($params['agent_id'])){
    		$data['supplier_id'] = $params['agent_id'];
    		 
    	}
        if(isset($params['guest_number'])){
            $data['guest_number'] = $params['guest_number'];
        }
        if(isset($params['free_wifi'])){
            $data['free_wifi'] = $params['free_wifi'];
        }
        if(isset($params['phone'])){
            $data['phone'] = $params['phone'];
        }
        if(isset($params['address'])){
            $data['address'] = $params['address'];
        }
        if(isset($params['floor'])){
            $data['floor'] = $params['floor'];
        }
        if(isset($params['room_area'])){
            $data['room_area'] = $params['room_area'];
        }
        if(isset($params['is_add_bed'])){
            $data['is_add_bed'] = $params['is_add_bed'];
        }
        if(isset($params['smoke_treatment'])){
            $data['smoke_treatment'] = $params['smoke_treatment'];
        }
        if(isset($params['level_name'])){
            $data['level_name'] = $params['level_name'];
        }
        if(isset($params['remark'])){
            $data['remark'] = $params['remark'];
        }
    	if(!empty($params['status'])){
    		$data['status'] = $params['status'];
    		 
    	}
        if(!empty($params['choose_company_id'])){
            $data['company_id'] = $params['choose_company_id'];

        }



    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;

    
    
    	$source_price=[];
    	Db::startTrans();
    	try{
    		Db::name('hotel')->where("hotel_id = ".$params['hotel_id'])->update($data);


//            $a['supplier_type_id'] = 2;
//            $a['source_id'] = $params['hotel_id'];
//            $b = $this->_public_service->getSourceRemindObject($a);
//
//            $aa = new SystemAlertSetting();
//
//            $w['system_alert_event_id'] = 1;
//            $w['company_id'] = $params['choose_company_id'];
//            $system_alert_setting = $aa->getSystemAlertSetting($w);
//            $system_alert_setting = $system_alert_setting[0];
//            $bb = new InStationLetter();
//
//            if($system_alert_setting['status'] = 1 && $system_alert_setting['is_system_reminder'] = 1){
//
//                foreach ($b as $k=>$v){
//                    $data1['user_id'] = $v;
//                    $data1['system_alert_event_id'] = $system_alert_setting['system_alert_event_id'];
//                    $data1['content'] = '酒店资源ID:'.$params['hotel_id'].$system_alert_setting['system_reminder_content'];
//                    $data1['url'] = '/source/showHotelInfo?hotel_id='.$params['hotel_id'];
//                    $data1['status'] = 1;
//                    $bb->addInStationLetter($data1);
//                    unset($data1);
//                }
//
//            }


    	//统价
    		if(!empty($params['normal_price']) ){
	    		$source_price['normal_price']=$params['normal_price'];

    		}
    		if(!empty($params['normal_settlement_price'])){
    			
    			$source_price['normal_settlement_price']=$params['normal_settlement_price'];
    			
    		}
            if(!empty($params['payment_currency_type'])){

                $source_price['payment_currency_type']=$params['payment_currency_type'];

            }
//    		if( !empty($params['normal_retail_price'])){
//
//    			$source_price['normal_retail_price']=$params['normal_retail_price'];
//    		}
//    		//成人
//    		if(!empty($params['adult_price']) ){
//    			$source_price['adult_price']=$params['adult_price'];
//
//    		}
//    		//成人
//    		if(!empty($params['adult_settlement_price'])){
//
//    			$source_price['adult_settlement_price']=$params['adult_settlement_price'];
//
//    		}
//    		//成人
//    		if( !empty($params['adult_retail_price'])){
//
//    			$source_price['adult_retail_price']=$params['adult_retail_price'];
//    		}
//    		//占床儿童
//    		if(!empty($params['child_bed_price']) ){
//    			$source_price['child_bed_price']=$params['child_bed_price'];
//
//    		}
//    		if(!empty($params['child_bed_settlement_price'])){
//
//    			$source_price['child_bed_settlement_price']=$params['child_bed_settlement_price'];
//
//    		}
//    		if(!empty($params['child_bed_retail_price'])){
//
//    			$source_price['child_bed_retail_price']=$params['child_bed_retail_price'];
//    		}
//    		//老人
//    		if(!empty($params['old_price']) ){
//    			$source_price['old_price']=$params['old_price'];
//
//    		}
//    		if( !empty($params['old_settlement_price'])){
//
//    			$source_price['old_settlement_price']=$params['old_settlement_price'];
//
//    		}
//    		if(!empty($params['old_retail_price'])){
//
//    			$source_price['old_retail_price']=$params['old_retail_price'];
//    		}
//    		//不占儿童
//    		if(!empty($params['child_price'])){
//    			$source_price['child_price']=$params['child_price'];
//
//    		}
//    		if(!empty($params['child_settlement_price'])){
//
//    			$source_price['child_settlement_price']=$params['child_settlement_price'];
//
//    		}
//    		if(!empty($params['child_retail_price'])){
//
//    			$source_price['child_retail_price']=$params['child_retail_price'];
//    		}
//    		//单房差
//    		if(!empty($params['single_price'])){
//    			$source_price['single_price']=$params['single_price'];
//
//    		}
//    		if(!empty($params['single_settlement_price']) ){
//
//    			$source_price['single_settlement_price']=$params['single_settlement_price'];
//
//    		}
//    		if( !empty($params['single_retail_price'])){
//
//    			$source_price['single_retail_price']=$params['single_retail_price'];
//    		}
    		
    		Db::name('source_price')->where("supplier_type_id = 2 and pk_id = ".$params['hotel_id'])->update($source_price);
    		$result = 1;
    		// 提交事务
    		Db::commit();
    
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		Db::rollback();
    
    	}
    	return $result;
    }

    /**
     * findOneHotel
     *
     * 获取一条酒店数据
     * @author shj
     * @return void
     * Date: 2019/2/26
     * Time: 10:41
     */
    public function getOneHotel($hotel_id){
        $result = $this->table("hotel")->where(['hotel_id' => $hotel_id])->find();
        return $result;
    }
}