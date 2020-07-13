<?php

namespace app\index\model\source;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class SourcePrice extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'source_price';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

   

    /**
     * 获取资源价格
     */
    public function getSourcePirce($params){ 
    
		if(!empty($params['pk_id'])){
			$data['pk_id'] = $params['pk_id'];
		}
    	
		if(!empty($params['supplier_type_id'])){
			$data['supplier_type_id'] = $params['supplier_type_id'];
		}
		 
    	
    	$result = $this->
    	where($data)->select();
    
    	return $result;
    }
    /**
     * 添加
     */
    
    public function addSourcePirce($params){
    
        $t = time();

        if(!empty($params['supplier_type_id'])){
            $data['supplier_type_id'] = $params['supplier_type_id'];
        }
		$data['pk_id'] = $params['pk_id'];
		$data['payment_currency_type'] = $params['payment_currency_type'];
		$data['normal_price'] = $params['normal_price'];
		$data['normal_settlement_price'] = $params['normal_settlement_price'];


        Db::startTrans();
        try{
            $result = Db::name('source_price')->insertGetId($data);


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
}