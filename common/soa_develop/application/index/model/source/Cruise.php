<?php

namespace app\index\model\source;
use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use think\config;
use think\Db;
class Cruise extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'cruise';
    private $_languageList;
    private $_public_service;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	$this->_public_service = new PublicService();
    	parent::initialize();
    
    }

    /**
     * 添加游轮
     * 胡
     */
    public function addCruise($params){
    	$t = time();

    	$data['source_number'] = $params['source_number'];
  
    	$data['supplier_id'] = $params['supplier_id'];
		$data['belong_supplier_id'] = $params['supplier_id'];
    	$data['supplier_type'] = 1;
    	$data['cruise_name'] = $params['cruise_name'];
        if(isset($params['room_name'])){
            $data['room_name'] = $params['room_name'];
        }
        if(!empty($params['guest_number'])){
            $data['guest_number'] = $params['guest_number'];
        }
        if(isset($params['free_wifi'])){
            $data['free_wifi'] = $params['free_wifi'];
        }
        if(!empty($params['room_area'])){
            $data['room_area'] = $params['room_area'];

        }
        if(isset($params['is_add_bed'])){
            $data['is_add_bed'] = $params['is_add_bed'];
        }
        if(isset($params['smoke_treatment'])){
            $data['smoke_treatment'] = $params['smoke_treatment'];
        }
    	$data['deck'] = $params['deck'];
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
    		$pk_id = Db::name('cruise')->insertGetId($data);
			$this->_public_service->setNumber('cruise', 'cruise_id', $pk_id, 'source_number', $data['source_number'], $pk_id);
    		
    		$language_data['source_number'] = $params['source_number'];
    		$language_data['cruise_name'] = $params['cruise_name'];
    		$language_data['language_id']=$params['lang_id'];
    		$language_data['create_time'] = $t;
    		$language_data['create_user_id'] = $params['user_id'];
    		$language_data['update_time'] = $t;
    		$language_data['update_user_id'] = $params['user_id'];
    		$language_data['status'] = 1;
    		Db::name('cruise_language')->insertGetId($language_data);
    		
    		//统价
  			$source_price['normal_price']=$params['normal_price'];
  			$source_price['normal_settlement_price']=$params['normal_settlement_price'];
            $source_price['payment_currency_type']=$params['payment_currency_type'];

  			$source_price['supplier_type_id'] = 5;
  			$source_price['pk_id'] = $pk_id;
  			Db::name('source_price')->insert($source_price);

  			//判断是否有代理商
  			if(!empty($params['agent_id'])){
  				$data['source_number'] =	help::getNumber(55);
  				$data['supplier_id'] =	$params['agent_id'];
  				$data['belong_supplier_id'] =	$params['supplier_id'];
  				$data['supplier_type'] = 2;
  				$pk_id = Db::name('cruise')->insertGetId($data);
  				$source_price['pk_id'] = $pk_id;
  				Db::name('source_price')->insert($source_price);
  				$language_data['source_number'] = $data['source_number'];
  				
  				Db::name('cruise_language')->insert($language_data);
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
     * 获取游轮
     * 胡
     */
    public function getCruise($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
    	$data = "1=1 ";
    	
    	
    	if($params['is_branch_product'] == 1){
    	    if(!empty($params['source_name'])){
    			$data.= " and cruise.cruise_name like '%".$params['source_name']."%'";
    		}
    		if(!empty($params['source_number'])){
    			$data.= " and cruise.source_number like '%".$params['source_number']."%'";
    		}
    		if(!empty($params['supplier_name'])){
    			$data.= " and supplier_name like '%".$params['supplier_name']."%'";
    		}
    	}else{
    	    if(!empty($params['cruise_name'])){
    			$data.= " and cruise.cruise_name like '%".$params['cruise_name']."%'";
    		}
    		if(!empty($params['source_number'])){
    			$data.= " and cruise.source_number= '".$params['source_number']."'";
    		}
    	}
    	
    	if(is_numeric($params['status'])){
    		$data.= " and cruise.status = ".$params['status'];
    	}
    	if(!empty($params['cruise_id'])){
    		$data.= " and cruise.cruise_id = '".$params['cruise_id']."'";
    	}

    	if(!empty($params['supplier_id'])){
    		$data.= " and cruise.supplier_id = '".$params['supplier_id']."'";
    	}
    	if(!empty($params['supplier_type'])){
    		$data.= " and cruise.supplier_type = '".$params['supplier_type']."'";
    	}
    	if(!empty($params['belong_supplier_id'])){
    		$data.= " and cruise.belong_supplier_id = '".$params['belong_supplier_id']."'";
    	}
    	if(is_numeric($params['company_id'])){
    		$data.= " and cruise.company_id ='".$params['company_id']."'";
    	}
        if($is_count==true){
            $result = $this->table("cruise")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("cruise")->
                join("source_price", 'source_price.pk_id = cruise.cruise_id and source_price.supplier_type_id=5', 'left')->
                join('currency', 'currency.currency_id = source_price.payment_currency_type')->
                join('supplier', 'supplier.supplier_id = cruise.supplier_id')->
                join('company', 'company.company_id= cruise.company_id')->
                where($data)->limit($page, $page_size)->order('create_time desc')->
                field(['cruise.cruise_id', "cruise.cruise_name", "cruise.source_number",
                    'cruise.room_name', 'cruise.guest_number',
                    'cruise.free_wifi', "cruise.room_area",
                    'cruise.deck', 'cruise.is_add_bed', 'cruise.smoke_treatment', 'cruise.remark',
                    'cruise.supplier_id', 'supplier.supplier_name',
                    'cruise.supplier_type', 'cruise.belong_supplier_id',
                    'cruise.default_language_id',
                    "cruise.company_id", 'company.company_name', 'currency.currency_name','currency.unit',
                    'cruise.create_time', 'source_price.payment_currency_type',
                    'source_price.normal_price', 'source_price.normal_settlement_price',
                    "(select nickname  from user where user.user_id = cruise.create_user_id)"=> 'create_user_name',
                    "(select nickname  from user where user.user_id = cruise.update_user_id)"=> 'update_user_name',
                    'cruise.update_time', "cruise.status",
                ])->select();
            }else{
                $result = $this->table("cruise")->alias('cruise')->
                join("source_price",'source_price.pk_id = cruise.cruise_id and source_price.supplier_type_id=5','left')->
                join('currency','currency.currency_id = source_price.payment_currency_type')->
                join('supplier','supplier.supplier_id = cruise.supplier_id')->
                join('company','company.company_id= cruise.company_id')->
                where($data)->order('create_time desc')->
                field(['cruise.cruise_id',"cruise.cruise_name","cruise.source_number",
                    'cruise.room_name','cruise.guest_number',
                    'cruise.free_wifi',"cruise.room_area",
                    'cruise.deck','cruise.is_add_bed','cruise.smoke_treatment','cruise.remark',
                    'cruise.supplier_id','supplier.supplier_name',
                    'cruise.supplier_type','cruise.belong_supplier_id',
                    'cruise.default_language_id',
                    "cruise.company_id",'company.company_name','currency.currency_name','currency.unit',
                    'cruise.create_time','source_price.payment_currency_type',
                    'source_price.normal_price','source_price.normal_settlement_price',
                    "(select nickname  from user where user.user_id = cruise.create_user_id)"=>'create_user_name',
                    "(select nickname  from user where user.user_id = cruise.update_user_id)"=>'update_user_name',
                    'cruise.update_time',"cruise.status",
                ])->select();
            }
        }
            
     
		
		
        return $result;
    
    }
    /**
     * 获取邮轮数据根据邮轮_ID与lang_id
     */
    public function getCruiseByCruiseIdLangId($params){
    
    	$lang_id = $params['lang_id'];
    	$data['language_id'] = $lang_id;
    	$data['source_number'] = $params['source_number'];
    	$result = $this->table('cruise_language')->
    	where($data)->find();
    
    	return $result;
    }
    
    /**
     * 修改邮轮多语言数据根据邮轮多语言ID
     */
    public function updateCruiseLanguageByCruiseLanguageId($params){
    
    	$t = time();
    	$user_id = $params['user_id'];
    
    	$original_number = $params['data'][0]['source_number'];
    
    	$original_data['source_number'] = $original_number;
    
    
    	$params = $params['data'];
    
    	//原始数据主键
    	$original_result = $this->getCruise($original_data);
    
    	$default_language_id = $original_result[0]['default_language_id'];
    
    	$this->startTrans();
    	try{
    		for($i=0;$i<count($params);$i++){
    
    			$data = [];
    			if(!trim($params[$i]['cruise_name'])==''){
    					
    				$data['cruise_name'] = $params[$i]['cruise_name'];
    				$data['update_time'] = $t;
    				$data['update_user_id'] = $user_id;
    
    				if(is_numeric($params[$i]['cruise_language_id'])){
    
    					$this->table('cruise_language')->where("cruise_language_id = ".$params[$i]['cruise_language_id'])->update($data);
    
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
    					$this->table("cruise_language")->insert($data);
    
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
     * 修改游轮
     */
    public function updateCruiseByCruiseId($params){
    
    	$t = time();
    	
    	if(!empty($params['cruise_name'])){
    		$data['cruise_name'] = $params['cruise_name'];
    	
    	}
    	if(!empty($params['supplier_id'])){
    		$data['belong_supplier_id'] = $params['supplier_id'];
    		 
    	}
    	if(!empty($params['agent_id'])){
    		$data['supplier_id'] = $params['agent_id'];
    		 
    	}
    	if(!empty($params['room_name'])){
    		$data['room_name'] = $params['room_name'];
    		 
    	}
        if(isset($params['room_name'])){
            $data['room_name'] = $params['room_name'];
        }
        if(isset($params['deck'])){
        	$data['deck'] = $params['deck'];
        }
        if(isset($params['guest_number'])){
            $data['guest_number'] = $params['guest_number'];
        }
        if(isset($params['free_wifi'])){
            $data['free_wifi'] = $params['free_wifi'];
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
        if(!empty($params['choose_company_id'])){
            $data['company_id'] = $params['choose_company_id'];

        }
        if(isset($params['remark'])){
            $data['remark'] = $params['remark'];
        }
    	if(!empty($params['status'])){
    		$data['status'] = $params['status'];
    		 
    	}
    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;

    
    
    	$source_price=[];
    	Db::startTrans();
    	try{
    		Db::name('cruise')->where("cruise_id = ".$params['cruise_id'])->update($data);
    		//统价
    		if(!empty($params['normal_price']) ){
	    		$source_price['normal_price']=$params['normal_price'];

    		}
    		if(!empty($params['normal_settlement_price'])){
    			
    			$source_price['normal_settlement_price']=$params['normal_settlement_price'];
    			
    		}
    		if( !empty($params['normal_retail_price'])){

    			$source_price['normal_retail_price']=$params['normal_retail_price'];
    		}
    		//成人
    		if(!empty($params['adult_price']) ){
    			$source_price['adult_price']=$params['adult_price'];

    		}
    		//成人
    		if(!empty($params['adult_settlement_price'])){
    			
    			$source_price['adult_settlement_price']=$params['adult_settlement_price'];
    		
    		}
    		//成人
    		if( !empty($params['adult_retail_price'])){

    			$source_price['adult_retail_price']=$params['adult_retail_price'];
    		}    		
    		//占床儿童
    		if(!empty($params['child_bed_price']) ){
    			$source_price['child_bed_price']=$params['child_bed_price'];

    		}
    		if(!empty($params['child_bed_settlement_price'])){
    			
    			$source_price['child_bed_settlement_price']=$params['child_bed_settlement_price'];
    		
    		}
    		if(!empty($params['child_bed_retail_price'])){

    			$source_price['child_bed_retail_price']=$params['child_bed_retail_price'];
    		}    		
    		//老人
    		if(!empty($params['old_price']) ){
    			$source_price['old_price']=$params['old_price'];

    		}
    		if( !empty($params['old_settlement_price'])){
    			
    			$source_price['old_settlement_price']=$params['old_settlement_price'];
    		
    		}
    		if(!empty($params['old_retail_price'])){

    			$source_price['old_retail_price']=$params['old_retail_price'];
    		}    		
    		//不占儿童
    		if(!empty($params['child_price'])){
    			$source_price['child_price']=$params['child_price'];

    		}
    		if(!empty($params['child_settlement_price'])){
    		
    			$source_price['child_settlement_price']=$params['child_settlement_price'];
    		
    		}
    		if(!empty($params['child_retail_price'])){

    			$source_price['child_retail_price']=$params['child_retail_price'];
    		}    		
    		//单房差
    		if(!empty($params['single_price'])){
    			$source_price['single_price']=$params['single_price'];

    		}
    		if(!empty($params['single_settlement_price']) ){
    		
    			$source_price['single_settlement_price']=$params['single_settlement_price'];
    			
    		}
    		if( !empty($params['single_retail_price'])){

    			$source_price['single_retail_price']=$params['single_retail_price'];
    		}
            if(!empty($params['payment_currency_type'])){

                $source_price['payment_currency_type']=$params['payment_currency_type'];

            }
    		
    		Db::name('source_price')->where("supplier_type_id = 5 and pk_id = ".$params['cruise_id'])->update($source_price);
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
     * getOneCruise
     *
     * 获取一条邮轮信息
     * @author shj
     *
     * @param $cruise_id
     *
     * @return void
     * Date: 2019/2/27
     * Time: 15:38
     */
    public function getOneCruise($cruise_id){
        $result = $this->table("cruise")->where(['cruise_id' => $cruise_id])->find();
        return $result;
    }
}