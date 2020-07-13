<?php

namespace app\index\model\source;
use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use think\config;
use think\Db;
class Shopping extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'shopping';
    private $_languageList;
    private $_public_service;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	$this->_public_service = new PublicService();
    	parent::initialize();
    
    }

    /**
     * addShopping
     *
     * 添加购物店
     * @author shj
     *
     * @param $params
     *
     * @return int|string
     * Date: 2019/4/9
     * Time: 10:55
     */
    public function addShopping($params){
    	$t = time();
    	$data['source_number'] = $params['source_number'];
        $data['remark'] = $params['remark'];
    	$data['supplier_id'] = $params['supplier_id'];
    	$data['supplier_type'] = $params['supplier_type'];
		$data['shopping_name'] = $params['shopping_name'];
        $data['shopping_type'] = $params['shopping_type'];
    	$data['company_id'] = $params['choose_company_id'];
    	$data['default_language_id'] = $params['default_language_id'];
    	$data['create_time'] = $t;
    	$data['create_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['user_id'];
    	$data['status'] = $params['status'];
    	Db::startTrans();
    	try{
    		$pk_id = Db::name('shopping')->insertGetId($data);
    		$result = $pk_id;
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
     * getShopping
     *
     * 获取购物店列表
     * @author shj
     *
     * @param      $params
     * @param bool $is_count
     * @param bool $is_page
     * @param null $page
     * @param int  $page_size
     *
     * @return false|int|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Date: 2019/4/9
     * Time: 10:56
     */
    public function getShopping($params,$is_count=false,$is_page=false,$page=null,$page_size=20){

    	$data = "1=1 ";
        if(!empty($params['shopping_name'])){
            $data.= " and shopping.shopping_name like '%".$params['shopping_name']."%'";
        }
        if(!empty($params['source_number'])){
            $data.= " and shopping.source_number = '".$params['source_number']."'";
        }
    	if(is_numeric($params['status'])){
    		$data.= " and shopping.status = ".$params['status'];
    	}
    	if(!empty($params['shopping_id'])){
    		$data.= " and shopping.shopping_id = '".$params['shopping_id']."'";
    	}
    	if(!empty($params['supplier_id'])){
    		$data.= " and shopping.supplier_id = '".$params['supplier_id']."'";
    	}
        if(!empty($params['supplier_type'])){
    		$data.= " and shopping.supplier_type = '".$params['supplier_type']."'";
    	}
    	if(is_numeric($params['company_id'])){
    		$data.= " and shopping.company_id = '".$params['company_id']."'";
    	}
        if($is_count==true){
            $result = $this->table("shopping")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("shopping")->
                join('supplier', 'supplier.supplier_id = shopping.supplier_id')->
                join('company', 'company.company_id= shopping.company_id')->
                where($data)->limit($page, $page_size)->order('create_time desc')->
                field(['shopping.*','supplier.supplier_name', 'company.company_name',])->select();
            }else{
                $result = $this->table("shopping")->
                join('supplier', 'supplier.supplier_id = shopping.supplier_id')->
                join('company', 'company.company_id= shopping.company_id')->
                where($data)->limit($page, $page_size)->order('create_time desc')->
                field(['shopping.*','supplier.supplier_name', 'company.company_name',])->select();
            }
        }
        return $result;
    
    }
    /**
     * 获取用餐数据根据用餐_ID与lang_id
     */
    public function getOneShopping($params){

        $data = "1=1 ";
        if(!empty($params['shopping_id'])){
            $data.= " and shopping.shopping_id = '".$params['shopping_id']."'";
        }
        $result = $this->table("shopping")
            ->join('supplier', 'supplier.supplier_id = shopping.supplier_id')
            ->join('company', 'company.company_id= shopping.company_id')
            ->where($data)
            ->field(['shopping.*','supplier.supplier_name', 'company.company_name',])
            ->find();
        return $result;

    }
    
    /**
     * 修改用餐多语言数据根据用餐多语言ID
     */
    public function updateshoppingLanguageByshoppingLanguageId($params){
    
    	$t = time();
    	$user_id = $params['user_id'];
    
    	$original_number = $params['data'][0]['source_number'];
    
    	$original_data['source_number'] = $original_number;
    
    
    	$params = $params['data'];
    	 
    	//原始数据主键
    	$original_result = $this->getshopping($original_data);
    
    	$default_language_id = $original_result[0]['default_language_id'];
    	 
    	$this->startTrans();
    	try{
    		for($i=0;$i<count($params);$i++){
    
    			$data = [];
    			if(!trim($params[$i]['shopping_name'])==''){
    				 
    				$data['shopping_name'] = $params[$i]['shopping_name'];
    				$data['update_time'] = $t;
    				$data['update_user_id'] = $user_id;
    
    				if(is_numeric($params[$i]['shopping_language_id'])){
    						
    					$this->table('shopping_language')->where("shopping_language_id = ".$params[$i]['shopping_language_id'])->update($data);
    						
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
    					$this->table("shopping_language")->insert($data);
    
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
    public function updateShopping($params){


    	$t = time();
    	if(!empty($params['shopping_name'])){
    		$data['shopping_name'] = $params['shopping_name'];
    	}

        if(!empty($params['shopping_type'])){
            $data['shopping_type'] = $params['shopping_type'];
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

    	Db::startTrans();

    	try{
    		Db::name('shopping')->where("shopping_id = ".$params['shopping_id'])->update($data);
    		//统价
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