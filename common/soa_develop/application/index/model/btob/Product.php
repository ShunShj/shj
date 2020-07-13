<?php
namespace app\index\model\Btob;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class Product extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'b2b_product';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

    /**
     * 添加路线类型
     * 胡
     */
    public function addProduct($params){

    	$t = time();
    	$data['product_code'] = $params['product_code'];
        $data['product_name'] = $params['product_name'];
        $data['sort'] = $params['sort'];
        $data['company_id'] = $params['choose_company_id'];
    	$data['create_time'] = $t;
    	$data['create_user_id'] = $params['now_user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['now_user_id'];
    	$data['status'] = $params['status'];

    	Db::startTrans();
    	try{
    		$pk_id = Db::name('b2b_product')->insertGetId($data);
		
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
     * 获取路线类型
     * 胡
     */
    public function getProduct($params,$is_count=false,$is_page=false,$page=null,$page_size=20){

        $data = "1=1";
        if(!empty($params['product_name'])){
            $data.= " and b2b_product.product_name like '%".$params['product_name']."%'";
        }
        if(!empty($params['product_code'])){
            $data.= " and b2b_product.product_code = '".$params['product_code']."'";
        }
        if($params['status']<2 && is_numeric($params['status'])){
            $data.= " and b2b_product.status = ".$params['status'];
        }
        if(is_numeric($params['company_id'])){
            $data.= " and b2b_product.company_id = '".$params['company_id']."'";
        }
        if($is_count==true){
            $result = $this->table("b2b_product")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("b2b_product")
                    ->where($data)->limit($page, $page_size)
                    ->field(['b2b_product.*'])
                    ->select();

            }else{
                $result = $this->table("b2b_product")
                    ->where($data)
                    ->field(['b2b_product.*'])
                    ->select();
            }
        }
      
        return $result;
    }

    /**
     * getOneRouteType
     *
     * 获取一条线路类型
     * @author shj
     *
     * @param $params
     *
     * @return array
     * Date: 2019/3/14
     * Time: 10:32
     */
    public function getOneProduct($params){

        $data = ['product_id' => $params['product_id']];

        $result = $this->table("b2b_product")
            ->field(['b2b_product.*'])
            ->where($data)
            ->find();

        return $result;
    }
    
    /**
     * 修改路线类型
     */
    public function updateProductByProductId($params){
    
    	$t = time();
    	
    	if(isset($params['product_name'])){
    		$data['product_name'] = $params['product_name'];
    	}

    	$data['status'] = $params['status'];

    	if(isset($params['product_code'])){
    		$data['product_code'] = $params['product_code'];
    	}

        if(isset($params['choose_company_id'])){
            $data['company_id'] = $params['choose_company_id'];
        }

        if(isset($params['sort'])){
            $data['sort'] = $params['sort'];
        }


    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;

    	Db::startTrans();
    	try{
    		Db::name('b2b_product')->where("product_id = ".$params['product_id'])->update($data);
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