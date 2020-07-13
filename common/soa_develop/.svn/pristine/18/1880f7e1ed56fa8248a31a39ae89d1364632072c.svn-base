<?php

namespace app\index\model\source;
use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use think\config;
use think\Db;
class Dining extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'dining';
    private $_languageList;
    private $_public_service;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	$this->_public_service = new PublicService();
    	parent::initialize();
    
    }

    /**
     * 添加用餐
     * 胡
     */
    public function addDining($params){
    	$t = time();


    	$data['source_number'] = $params['source_number'];
    	
  
    	$data['standard_type'] = $params['standard_type'];
        if(isset($params['level_name'])){
    		$data['level_name'] = $params['level_name'];
    	}
        if(isset($params['remark'])){
            $data['remark'] = $params['remark'];
        }
    	$data['supplier_id'] = $params['supplier_id'];
		$data['belong_supplier_id'] = $params['supplier_id'];
    	$data['supplier_type'] = 1;
		$data['dining_name'] = $params['dining_name'];
    	$data['company_id'] = $params['choose_company_id'];
    	
    	
    	$data['default_language_id'] = $params['lang_id'];
    	$data['create_time'] = $t;  	
    	$data['create_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['user_id'];
    	$data['status'] = $params['status'];

    	
    	Db::startTrans();
    	try{
    		$pk_id = Db::name('dining')->insertGetId($data);
    		$this->_public_service->setNumber('dining', 'dining_id', $pk_id, 'source_number', $data['source_number'], $pk_id);
    		$language_data['source_number'] = $params['source_number'];
    		$language_data['dining_name'] = $params['dining_name'];
    		$language_data['language_id']=$params['lang_id'];
    		$language_data['create_time'] = $t;
    		$language_data['create_user_id'] = $params['user_id'];
    		$language_data['update_time'] = $t;
    		$language_data['update_user_id'] = $params['user_id'];
    		$language_data['status'] = 1;
    		Db::name('dining_language')->insertGetId($language_data);
    		
    		
    		
    		//统价
  			$source_price['normal_price']=$params['normal_price'];
  			$source_price['normal_settlement_price']=$params['normal_settlement_price'];
            $source_price['payment_currency_type']=$params['payment_currency_type'];
//  			$source_price['normal_retail_price']=$params['normal_retail_price'];
//   			//成人
//   			if(!empty($params['adult_price']) && !empty($params['adult_settlement_price']) && !empty($params['adult_retail_price'])){
//   				$source_price['adult_price']=$params['adult_price'];
//   				$source_price['adult_settlement_price']=$params['adult_settlement_price'];
//   				$source_price['adult_retail_price']=$params['adult_retail_price'];
//   			}

//   			//占床儿童
//   			if(!empty($params['child_bed_price']) && !empty($params['child_bed_settlement_price']) && !empty($params['child_bed_retail_price'])){
//   				$source_price['child_bed_price']=$params['child_bed_price'];
//   				$source_price['child_bed_settlement_price']=$params['child_bed_settlement_price'];
//   				$source_price['child_bed_retail_price']=$params['child_bed_retail_price'];  				
//   			}

//   			//老人
//   			if(!empty($params['old_price']) && !empty($params['old_settlement_price']) && !empty($params['old_retail_price'])){
//   				$source_price['old_price']=$params['old_price'];
//   				$source_price['old_settlement_price']=$params['old_settlement_price'];
//   				$source_price['old_retail_price']=$params['old_retail_price'];  			
//   			}

//   			//不占儿童
//   			if(!empty($params['child_price']) && !empty($params['child_settlement_price']) && !empty($params['child_retail_price'])){
//   				$source_price['child_price']=$params['child_price'];
//   				$source_price['child_settlement_price']=$params['child_settlement_price'];
//   				$source_price['child_retail_price']=$params['child_retail_price'];  			
//   			}

//   			//单房差
//   			if(!empty($params['single_price']) && !empty($params['single_settlement_price']) && !empty($params['single_retail_price'])){
//   				$source_price['single_price']=$params['single_price'];
//   				$source_price['single_settlement_price']=$params['single_settlement_price'];
//   				$source_price['single_retail_price']=$params['single_retail_price'];  			
//   			}


  			$source_price['supplier_type_id'] = 3;
  			$source_price['pk_id'] = $pk_id;
  			Db::name('source_price')->insert($source_price);

  			//判断是否有代理商
  			if(!empty($params['agent_id'])){
  				$data['source_number']= Help::getNumber(53);
  				$data['supplier_id'] =	$params['agent_id'];
  				$data['belong_supplier_id'] =	$params['supplier_id'];
  				$data['supplier_type'] = 2;
  				$pk_id = Db::name('dining')->insertGetId($data);
  				$source_price['pk_id'] = $pk_id;
  				Db::name('source_price')->insert($source_price);
  				$language_data['source_number'] = $data['source_number'];
  				
  				Db::name('dining_language')->insert($language_data);
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
     * 获取用餐
     * 胡
     */
    public function getDining($params,$is_count=false,$is_page=false,$page=null,$page_size=20){

    	$data = "1=1 ";
    	
    	if($params['is_branch_product'] == 1){
    	    if(!empty($params['source_name'])){
    			$data.= " and dining.dining_name like '%".$params['source_name']."%'";
    		}    	
    		if(!empty($params['source_number'])){
    			$data.= " and dining.source_number like '%".$params['source_number']."%'";
    		}
    		if(!empty($params['supplier_name'])){
    			$data.= " and supplier_name like '%".$params['supplier_name']."%'";
    		}
    	}else{
    	    if(!empty($params['dining_name'])){
    			$data.= " and dining.dining_name like '%".$params['dining_name']."%'";
    		}    	
    		if(!empty($params['source_number'])){
    			$data.= " and dining.source_number = '".$params['source_number']."'";
    		}
    	}
    	if(is_numeric($params['status'])){
    		$data.= " and dining.status = ".$params['status'];
    	}
    	if(!empty($params['dining_id'])){
    		$data.= " and dining.dining_id = '".$params['dining_id']."'";
    	}
		
    	if(!empty($params['supplier_id'])){
    		$data.= " and dining.supplier_id = '".$params['supplier_id']."'";
    	}	
        if(!empty($params['supplier_type'])){
    		$data.= " and dining.supplier_type = '".$params['supplier_type']."'";
    	}
    	if(!empty($params['belong_supplier_id'])){
    		$data.= " and dining.belong_supplier_id = '".$params['belong_supplier_id']."'";
    	}
    	if(is_numeric($params['company_id'])){
    		$data.= " and dining.company_id = '".$params['company_id']."'";
    	}
        if($is_count==true){
            $result = $this->table("dining")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("dining")->
                join("source_price", 'source_price.pk_id = dining.dining_id and source_price.supplier_type_id=3', 'left')->
                join('currency', 'currency.currency_id = source_price.payment_currency_type')->
                join('supplier', 'supplier.supplier_id = dining.supplier_id')->
                join('company', 'company.company_id= dining.company_id')->
                where($data)->limit($page, $page_size)->order('create_time desc')->
                field(['dining.dining_id', "dining.dining_name", 'dining.source_number',
                    'dining.standard_type',
                    'dining.level_name',
                    'dining.remark',
                    'dining.default_language_id',
                    'dining.supplier_id', 'supplier.supplier_name',
                    'dining.supplier_type', 'dining.belong_supplier_id',
                    "dining.company_id", 'company.company_name',
                    'source_price.normal_price', 'source_price.normal_settlement_price',
                    'source_price.payment_currency_type', 'currency.currency_name','currency.unit',
                    "(select nickname  from user where user.user_id = dining.create_user_id)" => 'create_user_name',
                    "(select nickname  from user where user.user_id = dining.update_user_id)" => 'update_user_name',
                    'dining.update_time', 'dining.create_time', "dining.status",
                ])->select();
            }else{
                $result = $this->table("dining")->alias('dining')->
                join("source_price",'source_price.pk_id = dining.dining_id and source_price.supplier_type_id=3','left')->
                join('currency','currency.currency_id = source_price.payment_currency_type')->
                join('supplier','supplier.supplier_id = dining.supplier_id')->
                join('company','company.company_id= dining.company_id')->
                where($data)->order('create_time desc')->
                field(['dining.dining_id',"dining.dining_name",'dining.source_number',
                    'dining.standard_type',
                    'dining.level_name',
                    'dining.remark',
                    'dining.default_language_id',
                    'dining.supplier_id','supplier.supplier_name',
                    'dining.supplier_type','dining.belong_supplier_id',
                    "dining.company_id",'company.company_name',
                    'source_price.normal_price','source_price.normal_settlement_price',
                    'source_price.payment_currency_type','currency.currency_name','currency.unit',
                    "(select nickname  from user where user.user_id = dining.create_user_id)"=>'create_user_name',
                    "(select nickname  from user where user.user_id = dining.update_user_id)"=>'update_user_name',
                    'dining.update_time','dining.create_time',"dining.status",
                ])->select();
            }
        }

        return $result;
    
    }
    /**
     * 获取用餐数据根据用餐_ID与lang_id
     */
    public function getDiningByDiningIdLangId($params){
    
    	$lang_id = $params['lang_id'];
    	$data['language_id'] = $lang_id;
    	$data['source_number'] = $params['source_number'];
    	$result = $this->table('dining_language')->
    	where($data)->find();
    	
    	return $result;
    }
    
    /**
     * 修改用餐多语言数据根据用餐多语言ID
     */
    public function updateDiningLanguageByDiningLanguageId($params){
    
    	$t = time();
    	$user_id = $params['user_id'];
    
    	$original_number = $params['data'][0]['source_number'];
    
    	$original_data['source_number'] = $original_number;
    
    
    	$params = $params['data'];
    	 
    	//原始数据主键
    	$original_result = $this->getDining($original_data);
    
    	$default_language_id = $original_result[0]['default_language_id'];
    	 
    	$this->startTrans();
    	try{
    		for($i=0;$i<count($params);$i++){
    
    			$data = [];
    			if(!trim($params[$i]['dining_name'])==''){
    				 
    				$data['dining_name'] = $params[$i]['dining_name'];
    				$data['update_time'] = $t;
    				$data['update_user_id'] = $user_id;
    
    				if(is_numeric($params[$i]['dining_language_id'])){
    						
    					$this->table('dining_language')->where("dining_language_id = ".$params[$i]['dining_language_id'])->update($data);
    						
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
    					$this->table("dining_language")->insert($data);
    
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
     * 修改用餐
     */
    public function updateDiningByDiningId($params){
    
    	$t = time();
    	
    	if(!empty($params['dining_name'])){
    		$data['dining_name'] = $params['dining_name'];
    	
    	}
    	if(!empty($params['supplier_id'])){
    		$data['belong_supplier_id'] = $params['supplier_id'];
    		 
    	}
    	if(!empty($params['agent_id'])){
    		$data['supplier_id'] = $params['agent_id'];
    		 
    	}
        if(isset($params['level_name'])){
            $data['level_name'] = $params['level_name'];
        }
        if(isset($params['remark'])){
            $data['remark'] = $params['remark'];
        }
    	if(!empty($params['standard_type'])){
    		$data['standard_type'] = $params['standard_type'];
    		 
    	}

        if(!empty($params['choose_company_id'])){
            $data['company_id'] = $params['choose_company_id'];

        }
    	if(!empty($params['status'])){
    		$data['status'] = $params['status'];

    	}



    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;

    
    
    	$source_price=[];
    	Db::startTrans();
    	try{
    		Db::name('dining')->where("dining_id = ".$params['dining_id'])->update($data);
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
    		Db::name('source_price')->where("supplier_type_id = 3 and pk_id = ".$params['dining_id'])->update($source_price);
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
     * getOneDining
     *
     * 获取一条饭店数据
     * @author shj
     *
     * @param $dining_id
     *
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Date: 2019/2/26
     * Time: 16:42
     */
    public function getOneDining($dining_id){
        $result = $this->table("dining")->where(['dining_id' => $dining_id])->find();
        return $result;
    }
}