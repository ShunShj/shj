<?php

namespace app\index\model\source;
use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use think\config;
use think\Db;
class Visa extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'visa';
    private $_languageList;
    private $_public_service;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	$this->_public_service = new PublicService();
    	parent::initialize();
    
    }

    /**
     * 添加签证
     * 胡
     */
    public function addVisa($params){
    	$t = time();

    	$data['source_number'] = $params['source_number'];
    	$data['visa_name'] = $params['visa_name'];
        if(isset($params['file_url'])){
            $data['file_url'] = $params['file_url'];
        }
    	$data['company_id'] = $params['choose_company_id'];
        if(isset($params['remark'])){
    		$data['remark'] = $params['remark'];
    	}
    	$data['supplier_id'] = $params['supplier_id'];
		$data['belong_supplier_id'] = $params['supplier_id'];
    	$data['supplier_type'] = 1;
    	
    	$data['default_language_id'] = $params['lang_id'];
    	$data['create_time'] = $t;  	
    	$data['create_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['user_id'];
    	$data['status'] = $params['status'];

    	
    	Db::startTrans();
    	try{
    		$pk_id = Db::name('visa')->insertGetId($data);
    		$this->_public_service->setNumber('visa', 'visa_id', $pk_id, 'source_number', $data['source_number'], $pk_id);
    		$language_data['source_number'] = $params['source_number'];
    		$language_data['visa_name'] = $params['visa_name'];
    		$language_data['language_id']=$params['lang_id'];
    		$language_data['create_time'] = $t;
    		$language_data['create_user_id'] = $params['user_id'];
    		$language_data['update_time'] = $t;
    		$language_data['update_user_id'] = $params['user_id'];
    		$language_data['status'] = 1;
    		Db::name('visa_language')->insertGetId($language_data);
    		//统价
  			$source_price['normal_price']=$params['normal_price'];
  			$source_price['normal_settlement_price']=$params['normal_settlement_price'];
            $source_price['payment_currency_type']=$params['payment_currency_type'];

  			$source_price['supplier_type_id'] = 6;
  			$source_price['pk_id'] = $pk_id;
  			Db::name('source_price')->insert($source_price);

  			//判断是否有代理商
  			if(!empty($params['agent_id'])){
  				
  				$data['source_number'] = Help::getNumber(56);
  				$data['supplier_id'] =	$params['agent_id'];
  				$data['belong_supplier_id'] =	$params['supplier_id'];
  				$data['supplier_type'] = 2;
  				$pk_id = Db::name('visa')->insertGetId($data);
  				$source_price['pk_id'] = $pk_id;
  				Db::name('source_price')->insert($source_price);
  				
  				$language_data['source_number'] = $data['source_number'];
  				$language_data['status'] = 1;
  				Db::name('visa_language')->insert($language_data);
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
     * 获取签证
     * 胡
     */
    public function getVisa($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
    	$data = "1=1 ";
    	
    	
    	if($params['is_branch_product'] == 1){
    	    if(!empty($params['source_name'])){
    			$data.= " and visa.visa_name like '%".$params['source_name']."%'";
    		}
    	    if(!empty($params['source_number'])){
    			$data.= " and visa.source_number like '%".$params['source_number']."%'";
    		}
    		if(!empty($params['supplier_name'])){
    			$data.= " and supplier_name like '%".$params['supplier_name']."%'";
    		}
    	}else{
    	    if(!empty($params['visa_name'])){
    			$data.= " and visa.visa_name like'%".$params['visa_name']."%'";
    		}
    	    if(!empty($params['source_number'])){
    			$data.= " and visa.source_number = '".$params['source_number']."'";
    		}	
    	}
    	
    	

    	if(is_numeric($params['status'])){
    		$data.= " and visa.status = ".$params['status'];
    	}
    	if(!empty($params['visa_id'])){
    		$data.= " and visa.visa_id = '".$params['visa_id']."'";
    	}
    	if(!empty($params['source_number'])){
    		$data.= " and visa.source_number = '".$params['source_number']."'";
    	}		
    	if(!empty($params['supplier_id'])){
    		$data.= " and visa.supplier_id = '".$params['supplier_id']."'";
    	}	
        if(!empty($params['supplier_type'])){
    		$data.= " and visa.supplier_type = '".$params['supplier_type']."'";
    	}
    	if(!empty($params['belong_supplier_id'])){
    		$data.= " and visa.belong_supplier_id = '".$params['belong_supplier_id']."'";
    	}
    	if(is_numeric($params['company_id'])){
    		$data.= " and visa.company_id = '".$params['company_id']."'";
    	}
        if($is_count==true){
            $result = $this->table("visa")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("visa")->
                join("source_price", 'source_price.pk_id = visa.visa_id and source_price.supplier_type_id=6', 'left')->
                join('currency', 'currency.currency_id = source_price.payment_currency_type')->
                join('supplier', 'supplier.supplier_id = visa.supplier_id')->
                join('company', 'company.company_id= visa.company_id')->
                where($data)->limit($page, $page_size)->order('create_time desc')->
                field(['visa.visa_id', "visa.visa_name", 'visa.source_number',
                    'visa.file_url',
                    'visa.remark',
                    'visa.default_language_id',
                    'visa.supplier_id', 'supplier.supplier_name',
                    'visa.supplier_type', 'visa.belong_supplier_id',
                    'visa.default_language_id',
                    "visa.company_id", 'company.company_name',
                    'source_price.normal_price', 'source_price.normal_settlement_price',
                    'source_price.payment_currency_type', 'currency.currency_name','currency.unit',
                    "(select nickname  from user where user.user_id = visa.create_user_id)"=> 'create_user_name',
                    "(select nickname  from user where user.user_id = visa.update_user_id)"=> 'update_user_name',
                    'visa.update_time', 'visa.create_time', "visa.status",
                ])->select();
            }else{
                $result = $this->table("visa")->alias('visa')->
                join("source_price",'source_price.pk_id = visa.visa_id and source_price.supplier_type_id=6','left')->
                join('currency','currency.currency_id = source_price.payment_currency_type')->
                join('supplier','supplier.supplier_id = visa.supplier_id')->
                join('company','company.company_id= visa.company_id')->
                where($data)->order('create_time desc')->
                field(['visa.visa_id',"visa.visa_name",'visa.source_number',
                    'visa.file_url',
                    'visa.remark',
                    'visa.default_language_id',
                    'visa.supplier_id','supplier.supplier_name',
                    'visa.supplier_type','visa.belong_supplier_id',
                    'visa.default_language_id',
                    "visa.company_id",'company.company_name',
                    'source_price.normal_price','source_price.normal_settlement_price',
                    'source_price.payment_currency_type','currency.currency_name','currency.unit',
                    "(select nickname  from user where user.user_id = visa.create_user_id)"=>'create_user_name',
                    "(select nickname  from user where user.user_id = visa.update_user_id)"=>'update_user_name',
                    'visa.update_time','visa.create_time',"visa.status",
                ])->select();
            }
        }
            
     


        return $result;
    
    }

    /**
     * 获取签证数据根据签证_ID与lang_id
     */
    public function getVisaByVisaIdLangId($params){
    
    	$lang_id = $params['lang_id'];
    	$data['language_id'] = $lang_id;
    	$data['source_number'] = $params['source_number'];
    	$result = $this->table('visa_language')->
    	where($data)->find();
    
    	return $result;
    }
    
    /**
     * 修改签证多语言数据根据签证多语言ID
     */
    public function updateVisaLanguageByVisaLanguageId($params){
    
    	$t = time();
    	$user_id = $params['user_id'];
    
    	$original_number = $params['data'][0]['source_number'];
    
    	$original_data['source_number'] = $original_number;
    
    
    	$params = $params['data'];
    
    	//原始数据主键
    	$original_result = $this->getVisa($original_data);
    
    	$default_language_id = $original_result[0]['default_language_id'];
    
    	$this->startTrans();
    	try{
    		for($i=0;$i<count($params);$i++){
    
    			$data = [];
    			if(!trim($params[$i]['visa_name'])==''){
    					
    				$data['visa_name'] = $params[$i]['visa_name'];
    				$data['update_time'] = $t;
    				$data['update_user_id'] = $user_id;
    
    				if(is_numeric($params[$i]['visa_language_id'])){
    
    					$this->table('visa_language')->where("visa_language_id = ".$params[$i]['visa_language_id'])->update($data);
    
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
    					$this->table("visa_language")->insert($data);
    
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
     * 修改签证
     */
    public function updateVisaByVisaId($params){
    
    	$t = time();
    	
    	if(!empty($params['visa_name'])){
    		$data['visa_name'] = $params['visa_name'];
    	
    	}
    	if(!empty($params['supplier_id'])){
    		$data['belong_supplier_id'] = $params['supplier_id'];
    		 
    	}
    	if(!empty($params['agent_id'])){
    		$data['supplier_id'] = $params['agent_id'];
    		 
    	}
        if(isset($params['file_url'])){
            $data['file_url'] = $params['file_url'];
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
    		Db::name('visa')->where("visa_id = ".$params['visa_id'])->update($data);
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
    		Db::name('source_price')->where("supplier_type_id = 6 and pk_id = ".$params['visa_id'])->update($source_price);
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
     * getOneVisa
     *
     * 获取一条签证
     * @author shj
     *
     * @param $visa_id
     *
     * @return void
     * Date: 2019/2/27
     * Time: 16:31
     */
    public function getOneVisa($visa_id){
        $result = $this->table("visa")->where(['visa_id' => $visa_id])->find();
        return $result;
    }
}