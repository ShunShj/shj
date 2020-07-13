<?php

namespace app\index\model\source;
use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use think\config;
use think\Db;
class ScenicSpot extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'scenic_spot';
    private $_languageList;
    private $_public_service;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	$this->_public_service = new PublicService();
    	parent::initialize();
    
    }

    /**
     * 添加景点
     * 胡
     */
    public function addScenicSpot($params){
    	$t = time();

    	$data['source_number'] = $params['source_number'];
    	$data['scenic_spot_name'] = $params['scenic_spot_name'];
        if(isset($params['country_id'])){
            $data['country_id'] = $params['country_id'];
        }
        if(isset($params['linkman'])){
            $data['linkman'] = $params['linkman'];
        }
        if(isset($params['phone'])){
            $data['phone'] = $params['phone'];
        }
        if(isset($params['addess'])){
            $data['addess'] = $params['addess'];
        }
        if(isset($params['email'])){
            $data['email'] = $params['email'];
        }
        if(isset($params['website'])){
            $data['website'] = $params['website'];
        }
        if(isset($params['level_name'])){
            $data['level_name'] = $params['level_name'];
        }
        if(isset($params['remark'])){
            $data['remark'] = $params['remark'];
        }
    	$data['company_id'] = $params['choose_company_id'];
    	$data['supplier_id'] = $params['supplier_id'];
		$data['belong_supplier_id'] = $params['supplier_id'];
    	$data['supplier_type'] = 1;
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
    		$pk_id = Db::name('scenic_spot')->insertGetId($data);
    		$this->_public_service->setNumber('scenic_spot', 'scenic_spot_id', $pk_id, 'source_number', $data['source_number'], $pk_id);
    		$language_data['source_number'] = $params['source_number'];
    		$language_data['scenic_spot_name'] = $params['scenic_spot_name'];
    		$language_data['language_id']=$params['lang_id'];
    		$language_data['create_time'] = $t;
    		$language_data['create_user_id'] = $params['user_id'];
    		$language_data['update_time'] = $t;
    		$language_data['update_user_id'] = $params['user_id'];
    		$language_data['status'] = 1;
    		Db::name('scenic_spot_language')->insertGetId($language_data);
    		
    		//统价
  			$source_price['normal_price']=$params['normal_price'];
  			$source_price['normal_settlement_price']=$params['normal_settlement_price'];
            $source_price['payment_currency_type']=$params['payment_currency_type'];


  			$source_price['supplier_type_id'] = 7;
  			$source_price['pk_id'] = $pk_id;
  			Db::name('source_price')->insert($source_price);

  			//判断是否有代理商
  			if(!empty($params['agent_id'])){
  				$data['source_number'] =	help::getNumber(57);
  				$data['supplier_id'] =	$params['agent_id'];
  				$data['belong_supplier_id'] =	$params['supplier_id'];
  				$data['supplier_type'] = 2;
  				$pk_id = Db::name('scenic_spot')->insertGetId($data);
  				$source_price['pk_id'] = $pk_id;
  				Db::name('source_price')->insert($source_price);
  				
  				$language_data['source_number'] = $data['source_number'];
  				$language_data['status'] = 1;
  				Db::name('scenic_spot_language')->insert($language_data);
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
     * 获取景点
     * 胡
     */
    public function getScenicSpot($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
    	$data = "1=1 ";
    	
    	if($params['is_branch_product'] == 1){
    	    if(!empty($params['source_name'])){
    			$data.= " and scenic_spot.scenic_spot_name like '%".$params['source_name']."%'";
    		}
    	
    		if(!empty($params['source_number'])){
    			$data.= " and scenic_spot.source_number like '%".$params['source_number']."%'";
    		}
    		if(!empty($params['supplier_name'])){
    			$data.= " and supplier_name like '%".$params['supplier_name']."%'";
    		}
    	}else{
    	    if(!empty($params['scenic_spot_name'])){
    			$data.= " and scenic_spot.scenic_spot_name like '%".$params['scenic_spot_name']."%'";
    		}
    	
    		if(!empty($params['source_number'])){
    			$data.= " and scenic_spot.source_number = '".$params['source_number']."'";
    		}
    	}

    	if(is_numeric($params['status'])){
    		$data.= " and scenic_spot.status = ".$params['status'];
    	}
    	if(!empty($params['scenic_spot_id'])){
    		$data.= " and scenic_spot.scenic_spot_id = '".$params['scenic_spot_id']."'";
    	}


    	if(!empty($params['supplier_id'])){
    		$data.= " and scenic_spot.supplier_id = '".$params['supplier_id']."'";
    	}
    	if(!empty($params['country_id'])){
    		$data.= " and scenic_spot.country_id = '".$params['country_id']."'";
    	}	
        if(!empty($params['supplier_type'])){
    		$data.= " and scenic_spot.supplier_type = '".$params['supplier_type']."'";
    	}
    	if(!empty($params['belong_supplier_id'])){
    		$data.= " and scenic_spot.belong_supplier_id = '".$params['belong_supplier_id']."'";
    	}
    	if(is_numeric($params['company_id'])){
    		$data.= " and scenic_spot.company_id = '".$params['company_id']."'";
    	}
        if($is_count==true){
            $result = $this->table("scenic_spot")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("scenic_spot")->alias('scenic_spot')->
                join("source_price", 'source_price.pk_id = scenic_spot.scenic_spot_id and source_price.supplier_type_id=7', 'left')->
                join('currency', 'currency.currency_id = source_price.payment_currency_type')->
                join('supplier', 'supplier.supplier_id = scenic_spot.supplier_id')->
                join('country', 'country.country_id = scenic_spot.country_id')->
                join('company', 'company.company_id= scenic_spot.company_id')->
                //join("source_level",'source_level.source_level_id = scenic_spot.source_level_id')->
                where($data)->limit($page, $page_size)->order('create_time desc')->
                field(['scenic_spot.scenic_spot_id', "scenic_spot.scenic_spot_name", 'scenic_spot.source_number',
                    'scenic_spot.supplier_id', 'supplier.supplier_name',
                    'scenic_spot.supplier_type', 'scenic_spot.belong_supplier_id',
                    'scenic_spot.country_id as city_id', 'country.country_name as city_name', 'country.pid as city_pid',
                    "(select country_name  from country as country_province where country_province.country_id= city_pid)"=> 'province_name',
                    "(select country_id  from country as country_province where country_province.country_id= city_pid)"=> 'province_id',
                    "(select pid  from country as country_province where country_province.country_id= city_pid)"=> 'province_pid',
                    "(select country_name  from country as country3 where country3.country_id= province_pid)"=> 'country_name',
                    "(select country_id  from country as country3 where country3.country_id= province_pid)"=> 'country_id',
                    'scenic_spot.linkman',
                    'scenic_spot.level_name',
                    'scenic_spot.addess', 'scenic_spot.phone',
                    'scenic_spot.email', "scenic_spot.website",
                    "scenic_spot.default_language_id",
                    'scenic_spot.remark',
                    "scenic_spot.company_id", 'company.company_name',
                    //'scenic_spot.source_level_id','source_level.source_level_name',
                    'source_price.normal_price', 'source_price.normal_settlement_price',
                    'source_price.payment_currency_type', 'currency.currency_name','currency.unit',
                    "(select nickname  from user where user.user_id = scenic_spot.create_user_id)"=> 'create_user_name',
                    "(select nickname  from user where user.user_id = scenic_spot.update_user_id)"=> 'update_user_name',
                    'scenic_spot.update_time', 'scenic_spot.create_time', "scenic_spot.status",
                ])->select();
            }else{
                $result = $this->table("scenic_spot")->alias('scenic_spot')->
                join("source_price",'source_price.pk_id = scenic_spot.scenic_spot_id and source_price.supplier_type_id=7','left')->
                join('currency','currency.currency_id = source_price.payment_currency_type')->
                join('supplier','supplier.supplier_id = scenic_spot.supplier_id')->
                join('country','country.country_id = scenic_spot.country_id')->
                join('company','company.company_id= scenic_spot.company_id')->
                //join("source_level",'source_level.source_level_id = scenic_spot.source_level_id')->
                where($data)->order('create_time desc')->
                field(['scenic_spot.scenic_spot_id',"scenic_spot.scenic_spot_name",'scenic_spot.source_number',
                    'scenic_spot.supplier_id','supplier.supplier_name',
                    'scenic_spot.supplier_type','scenic_spot.belong_supplier_id',
                    'scenic_spot.country_id as city_id','country.country_name as city_name','country.pid as city_pid',
                    "(select country_name  from country as country_province where country_province.country_id= city_pid)"=>'province_name',
                    "(select country_id  from country as country_province where country_province.country_id= city_pid)"=>'province_id',
                    "(select pid  from country as country_province where country_province.country_id= city_pid)"=>'province_pid',
                    "(select country_name  from country as country3 where country3.country_id= province_pid)"=>'country_name',
                    "(select country_id  from country as country3 where country3.country_id= province_pid)"=>'country_id',
                    'scenic_spot.linkman',
                    'scenic_spot.level_name',
                    'scenic_spot.addess','scenic_spot.phone',
                    'scenic_spot.email',"scenic_spot.website",
                    "scenic_spot.default_language_id",
                    'scenic_spot.remark',
                    "scenic_spot.company_id",'company.company_name',
                    //'scenic_spot.source_level_id','source_level.source_level_name',
                    'source_price.normal_price','source_price.normal_settlement_price',
                    'source_price.payment_currency_type','currency.currency_name','currency.unit',
                    "(select nickname  from user where user.user_id = scenic_spot.create_user_id)"=>'create_user_name',
                    "(select nickname  from user where user.user_id = scenic_spot.update_user_id)"=>'update_user_name',
                    'scenic_spot.update_time','scenic_spot.create_time',"scenic_spot.status",
                ])->select();
            }
        }
            
     


        return $result;
    
    }
    /**
     * 获取签证数据根据签证_ID与lang_id
     */
    public function getScenicSpotByScenicSpotIdLangId($params){
    
    	$lang_id = $params['lang_id'];
    	$data['language_id'] = $lang_id;
    	$data['source_number'] = $params['source_number'];
    	$result = $this->table('scenic_spot_language')->
    	where($data)->find();
    
    	return $result;
    }
    
    /**
     * 修改签证多语言数据根据签证多语言ID
     */
    public function updateScenicSpotLanguageByScenicSpotLanguageId($params){
    
    	$t = time();
    	$user_id = $params['user_id'];
    
    	$original_number = $params['data'][0]['source_number'];
    
    	$original_data['source_number'] = $original_number;
    
    
    	$params = $params['data'];
    
    	//原始数据主键
    	$original_result = $this->getScenicSpot($original_data);
    
    	$default_language_id = $original_result[0]['default_language_id'];
    
    	$this->startTrans();
    	try{
    		for($i=0;$i<count($params);$i++){
    
    			$data = [];
    			if(!trim($params[$i]['scenic_spot_name'])==''){
    					
    				$data['scenic_spot_name'] = $params[$i]['scenic_spot_name'];
    				$data['update_time'] = $t;
    				$data['update_user_id'] = $user_id;
    
    				if(is_numeric($params[$i]['scenic_spot_language_id'])){
    
    					$this->table('scenic_spot_language')->where("scenic_spot_language_id = ".$params[$i]['scenic_spot_language_id'])->update($data);
    
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
    					$this->table("scenic_spot_language")->insert($data);
    
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
     * 修改景点
     */
    public function updateScenicSpotByScenicSpotId($params){
    
    	$t = time();
    	
    	if(!empty($params['scenic_spot_name'])){
    		$data['scenic_spot_name'] = $params['scenic_spot_name'];
    	
    	}
    	if(!empty($params['supplier_id'])){
    		$data['belong_supplier_id'] = $params['supplier_id'];
    		 
    	}
    	if(!empty($params['agent_id'])){
    		$data['supplier_id'] = $params['agent_id'];
    		 
    	}
        if(isset($params['country_id'])){
            $data['country_id'] = $params['country_id'];
        }
        if(isset($params['linkman'])){
            $data['linkman'] = $params['linkman'];
        }
        if(isset($params['phone'])){
            $data['phone'] = $params['phone'];
        }
        if(isset($params['addess'])){
            $data['addess'] = $params['addess'];
        }
        if(isset($params['email'])){
            $data['email'] = $params['email'];
        }
        if(isset($params['website'])){
            $data['website'] = $params['website'];
        }
        if(isset($params['level_name'])){
            $data['level_name'] = $params['level_name'];
        }
        if(isset($params['remark'])){
            $data['remark'] = $params['remark'];
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
    		Db::name('scenic_spot')->where("scenic_spot_id = ".$params['scenic_spot_id'])->update($data);
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
    		Db::name('source_price')->where("supplier_type_id = 7 and pk_id = ".$params['scenic_spot_id'])->update($source_price);
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
     * getOneScenicSpot
     *
     * 获取一条景点数据
     * @author shj
     *
     * @param $scenic_spot_id
     *
     * @return void
     * Date: 2019/2/27
     * Time: 16:52
     */
    public function getOneScenicSpot($scenic_spot_id){
        $result = $this->table("scenic_spot")->where(['scenic_spot_id' => $scenic_spot_id])->find();
        return $result;
    }
}