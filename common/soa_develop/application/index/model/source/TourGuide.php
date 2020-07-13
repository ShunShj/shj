<?php

namespace app\index\model\source;
use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use think\config;
use think\Db;
class TourGuide extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'tour_guide';
    private $_languageList;
    private $_public_service;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	$this->_public_service = new PublicService();
    	parent::initialize();
    
    }

    /**
     * 添加导游
     * 胡
     */
    public function addTourGuide($params){
    	$t = time();

    	$data['source_number'] = $params['source_number'];
    	
    	$data['supplier_id'] = $params['supplier_id'];
		$data['belong_supplier_id'] = $params['supplier_id'];
    	$data['company_id'] = $params['choose_company_id'];
    	$data['supplier_type'] = 1;
    	$data['tour_guide_name'] = $params['tour_guide_name'];
        if(isset($params['email'])){
            $data['email'] = $params['email'];
        }
        if(isset($params['identity_card'])){
            $data['identity_card'] = $params['identity_card'];
        }
        if(isset($params['phone'])){
            $data['phone'] = $params['phone'];
        }
        if(isset($params['address'])){
            $data['address'] = $params['address'];
        }
        if(isset($params['guide_id_card'])){
            $data['guide_id_card'] = $params['guide_id_card'];
        }
        if(isset($params['passport'])){
            $data['passport'] = $params['passport'];
        }
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
    		$pk_id = Db::name('tour_guide')->insertGetId($data);
    		$this->_public_service->setNumber('tour_guide', 'tour_guide_id', $pk_id, 'source_number', $data['source_number'], $pk_id);
    		$language_data['source_number'] = $params['source_number'];
    		$language_data['tour_guide_name'] = $params['tour_guide_name'];
    		$language_data['language_id']=$params['lang_id'];
    		$language_data['create_time'] = $t;
    		$language_data['create_user_id'] = $params['user_id'];
    		$language_data['update_time'] = $t;
    		$language_data['update_user_id'] = $params['user_id'];
    		$language_data['status'] = 1;
    		Db::name('tour_guide_language')->insertGetId($language_data);
    		
    		//统价
  			$source_price['normal_price']=$params['normal_price'];
  			$source_price['normal_settlement_price']=$params['normal_settlement_price'];
            $source_price['payment_currency_type']=$params['payment_currency_type'];


  			$source_price['supplier_type_id'] = 9;
  			$source_price['pk_id'] = $pk_id;
  			Db::name('source_price')->insert($source_price);

  			//判断是否有代理商
  			if(!empty($params['agent_id'])){
  				$data['source_number'] =	help::getNumber(59);
  				$data['supplier_id'] =	$params['agent_id'];
  				$data['belong_supplier_id'] =	$params['supplier_id'];
  				$data['supplier_type'] = 2;
  				$pk_id = Db::name('tour_guide')->insertGetId($data);
  				$source_price['pk_id'] = $pk_id;
  				Db::name('source_price')->insert($source_price);
  				$language_data['status'] = 1;
  				$language_data['source_number'] = $data['source_number'];
  				Db::name('tour_guide_language')->insert($language_data);
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
     * 获取导游
     * 胡
     */
    public function getTourGuide($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
    	$data = "1=1 ";
    	
    	if($params['is_branch_product'] == 1){
    	    if(!empty($params['source_name'])){
    			$data.= " and tour_guide.tour_guide_name like '%".$params['source_name']."%'";
    		}
    		if(!empty($params['source_number'])){
    			$data.= " and tour_guide.source_number like '%".$params['source_number']."%'";
    		}  
    		if(!empty($params['supplier_name'])){
    			$data.= " and supplier_name like '%".$params['supplier_name']."%'";
    		}
    	}else{
    	    if(!empty($params['tour_guide_name'])){
    			$data.= " and tour_guide.tour_guide_name like '%".$params['tour_guide_name']."%'";
    		}
    		if(!empty($params['source_number'])){
    			$data.= " and tour_guide.source_number = '".$params['source_number']."'";
    		}  
    	}    	
   	
    	if(is_numeric($params['status'])){
    		$data.= " and tour_guide.status = ".$params['status'];
    	}
    	if(!empty($params['tour_guide_id'])){
    		$data.= " and tour_guide.tour_guide_id = '".$params['tour_guide_id']."'";
    	}

    	if(!empty($params['phone'])){
    		$data.= " and tour_guide.phone = '".$params['phone']."'";
    	}

    	if(!empty($params['supplier_id'])){
    		$data.= " and tour_guide.supplier_id = '".$params['supplier_id']."'";
    	}
        if(!empty($params['supplier_type'])){
    		$data.= " and tour_guide.supplier_type = '".$params['supplier_type']."'";
    	}
    	if(!empty($params['belong_supplier_id'])){
    		$data.= " and tour_guide.belong_supplier_id = '".$params['belong_supplier_id']."'";
    	}
    	if(is_numeric($params['company_id'])){
    		$data.= " and tour_guide.company_id = '".$params['company_id']."'";
    	}
        if($is_count==true){
            $result = $this->table("tour_guide")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("tour_guide")->
                join("source_price", 'source_price.pk_id = tour_guide.tour_guide_id and source_price.supplier_type_id=9', 'left')->
                join('currency', 'currency.currency_id = source_price.payment_currency_type')->
                join('supplier', 'supplier.supplier_id = tour_guide.supplier_id')->
                join('company', 'company.company_id= tour_guide.company_id')->
                where($data)->limit($page, $page_size)->order('create_time desc')->
                field(['tour_guide.tour_guide_id', "tour_guide.tour_guide_name", 'tour_guide.source_number',
                    'tour_guide.identity_card', 'tour_guide.phone',
                    'tour_guide.address', "tour_guide.email",
                    'tour_guide.guide_id_card', 'tour_guide.passport', 'tour_guide.remark',
                    'tour_guide.default_language_id',
                    "tour_guide.company_id", 'company.company_name', 'currency.currency_name',
                    'tour_guide.supplier_id', 'supplier.supplier_name',
                    'tour_guide.supplier_type', 'tour_guide.belong_supplier_id',
                    'tour_guide.create_time', 'source_price.payment_currency_type','currency.unit',
                    'source_price.normal_price', 'source_price.normal_settlement_price',
                    "(select nickname  from user where user.user_id = tour_guide.create_user_id)"=> 'create_user_name',
                    "(select nickname  from user where user.user_id = tour_guide.update_user_id)"=> 'update_user_name',
                    'tour_guide.update_time', 'tour_guide.create_time', "tour_guide.status",
                ])->select();
            }else{
                $result = $this->table("tour_guide")->alias('tour_guide')->
                join("source_price",'source_price.pk_id = tour_guide.tour_guide_id and source_price.supplier_type_id=9','left')->
                join('currency','currency.currency_id = source_price.payment_currency_type')->
                join('supplier','supplier.supplier_id = tour_guide.supplier_id')->
                join('company','company.company_id= tour_guide.company_id')->
                where($data)->order('create_time desc')->
                field(['tour_guide.tour_guide_id',"tour_guide.tour_guide_name",'tour_guide.source_number',
                    'tour_guide.identity_card','tour_guide.phone',
                    'tour_guide.address',"tour_guide.email",
                    'tour_guide.guide_id_card','tour_guide.passport','tour_guide.remark',
                    'tour_guide.default_language_id',
                    "tour_guide.company_id",'company.company_name','currency.currency_name',
                    'tour_guide.supplier_id','supplier.supplier_name',
                    'tour_guide.supplier_type','tour_guide.belong_supplier_id',
                    'tour_guide.create_time','source_price.payment_currency_type','currency.unit',
                    'source_price.normal_price','source_price.normal_settlement_price',
                    "(select nickname  from user where user.user_id = tour_guide.create_user_id)"=>'create_user_name',
                    "(select nickname  from user where user.user_id = tour_guide.update_user_id)"=>'update_user_name',
                    'tour_guide.update_time','tour_guide.create_time',"tour_guide.status",
                ])->select();
            }
        }
            
     


        return $result;
    
    }

    /**
     * 获取导游数据根据签证_ID与lang_id
     */
    public function getTourGuideByTourGuideIdLangId($params){
    
    	$lang_id = $params['lang_id'];
    	$data['language_id'] = $lang_id;
    	$data['source_number'] = $params['source_number'];
    	$result = $this->table('tour_guide_language')->
    	where($data)->find();
    
    	return $result;
    }
    
    /**
     * 修改车辆多语言数据根据车辆多语言ID
     */
    public function updateTourGuideLanguageByTourGuideLanguageId($params){
    
    	$t = time();
    	$user_id = $params['user_id'];
    
    	$original_number = $params['data'][0]['source_number'];
    
    	$original_data['source_number'] = $original_number;
    
    
    	$params = $params['data'];
    
    	//原始数据主键
    	$original_result = $this->getTourGuide($original_data);
    
    	$default_language_id = $original_result[0]['default_language_id'];
    
    	$this->startTrans();
    	try{
    		for($i=0;$i<count($params);$i++){
    
    			$data = [];
    			if(!trim($params[$i]['tour_guide_name'])==''){
    					
    				$data['tour_guide_name'] = $params[$i]['tour_guide_name'];
    				$data['update_time'] = $t;
    				$data['update_user_id'] = $user_id;
    
    				if(is_numeric($params[$i]['vehicle_language_id'])){
    
    					$this->table('tour_guide_language')->where("tour_guide_language_id = ".$params[$i]['tour_guide_language_id'])->update($data);
    
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
    					$this->table("tour_guide_language")->insert($data);
    
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
     * 修改导游
     */
    public function updateTourGuideByTourGuideId($params){
    
    	$t = time();
    	
    	if(!empty($params['tour_guide_name'])){
    		$data['tour_guide_name'] = $params['tour_guide_name'];
    	
    	}
    	if(!empty($params['supplier_id'])){
    		$data['belong_supplier_id'] = $params['supplier_id'];
    		 
    	}
    	if(!empty($params['agent_id'])){
    		$data['supplier_id'] = $params['agent_id'];
    		 
    	}
    	if(!empty($params['identity_card'])){
    		$data['identity_card'] = $params['identity_card'];

    	}
        if(isset($params['email'])){
            $data['email'] = $params['email'];
        }
        if(isset($params['identity_card'])){
            $data['identity_card'] = $params['identity_card'];
        }
        if(isset($params['phone'])){
            $data['phone'] = $params['phone'];
        }
        if(isset($params['address'])){
            $data['address'] = $params['address'];
        }
        if(isset($params['guide_id_card'])){
            $data['guide_id_card'] = $params['guide_id_card'];
        }
        if(isset($params['passport'])){
            $data['passport'] = $params['passport'];
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
    		Db::name('tour_guide')->where("tour_guide_id = ".$params['tour_guide_id'])->update($data);
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
    		Db::name('source_price')->where("supplier_type_id = 9 and pk_id = ".$params['tour_guide_id'])->update($source_price);
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


    public function getOneTourGuide($tour_guide_id){
        $result = $this->table("tour_guide")->where(['tour_guide_id' => $tour_guide_id])->find();
        return $result;
    }

}