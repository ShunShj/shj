<?php

namespace app\index\model\source;
use think\Exception;
use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use think\config;
use think\Db;
class B2bHotel extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'b2b_hodel';
    private $_languageList;
    private $_public_service;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	$this->_public_service = new PublicService();
    	parent::initialize();
    
    }

    /**
     * 添加供应商
     * 胡
     */
    public function addB2bHotel($params){

        $data['hotel_name_cn'] = $params['hotel_name_cn'];
        $data['hotel_name_en'] = $params['hotel_name_en'];
        $data['address_cn'] = $params['address_cn'];
        $data['address_en'] = $params['address_en'];
        $data['country_id'] = $params['country_id'];

    	$data['create_time'] = time();
    	$data['create_user_id'] = $params['now_user_id'];
    	$data['status'] = $params['status'];

    	Db::startTrans();
    	try{
    		Db::name('b2b_hotel')->insertGetId($data);
    		
    		$result = 1;
    		// 提交事务
    		Db::commit();
    
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		Db::rollback();
    		\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
    		exit();
    	}
    	
    	return $result;
    }
    
    /**
     * 获取供应商
     * 胡
     */
    public function getB2bHotel($params,$is_count=false,$is_page=false,$page=null,$page_size=20){
    
    
    	$data = "1=1 ";

    	if(!empty($params['b2b_hotel_id'])){
    		$data.= " and b2b_hotel.b2b_hotel_id= ".$params['b2b_hotel_id'];
    	}

    	if(isset($params['status'])){
    		$data.= " and b2b_hotel.status = ".$params['status'];
    	}

        if(!empty($params['hotel_name_cn'])){
            $data.= " and b2b_hotel.hotel_name_cn like '%".$params['hotel_name_cn']."%'";
        }

        if(!empty($params['hotel_name_en'])){
            $data.= " and b2b_hotel.hotel_name_en like '%".$params['hotel_name_en']."%'";
        }
        if(!empty($params['address_cn'])){
            $data.= " and b2b_hotel.address_cn like '%".$params['address_cn']."%'";
        }
        if(!empty($params['address_en'])){
            $data.= " and b2b_hotel.address_en like '%".$params['address_en']."%'";
        }

        if(!empty($params['country_id'])){
            $data.= " and b2b_hotel.country_id = ".$params['country_id'];
        }

        try
        {
            if($is_count==true){
                $result = $this->table("b2b_hotel")->alias("b2b_hotel")->where($data)->count();
            }else {
                if ($is_page == true) {
                    $result = $this->table("b2b_hotel")->alias('b2b_hotel')->
                    join("country", "b2b_hotel.country_id= country.country_id", 'left')->
                    join("country a", "a.country_id= country.pid", 'left')->
                    join("country b", "b.country_id= a.pid", 'left')->
                    where($data)->limit($page, $page_size)->order('create_time desc')->
                    field(['b2b_hotel.*', 'country.country_id as city_id','country.country_name as city_name','country.level as city_level','country.pid as city_pid',
                        'a.country_id as province_id','a.country_name as province_name','a.pid as province_pid',
                        'b.country_id as country_id','b.country_name as country_name'])->select();
                }else{
                    $result = $this->table("b2b_hotel")->alias('b2b_hotel')->
                    join("country", "b2b_hotel.country_id= country.country_id", 'left')->
                    join("country a", "a.country_id= country.pid", 'left')->
                    join("country b", "b.country_id= a.pid", 'left')->
                    where($data)->order('create_time desc')->
                    field(['b2b_hotel.*', 'country.country_id as city_id','country.country_name as city_name','country.level as city_level','country.pid as city_pid',
                        'a.country_id as province_id','a.country_name as province_name','a.pid as province_pid',
                        'b.country_id as country_id','b.country_name as country_name'])->select();
                }
            }
        }
        catch (Exception $e)
        {
            $result = $e->getMessage();
            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();
        }

    	return $result;
    
    }

    /**
     * 修改供应商 根据supplier_id
     */
    public function updateB2bHotel($params){

    	$data['update_user_id'] = $params['user_id'];
    	$data['update_time'] = time();

        if(isset($params['address_cn'])){
            $data['address_cn'] = $params['address_cn'];
        }

        if(isset($params['address_en'])){
            $data['address_en'] = $params['address_en'];
        }

        if(isset($params['hotel_name_cn'])){
            $data['hotel_name_cn'] = $params['hotel_name_cn'];
        }

        if(isset($params['hotel_name_en'])){
            $data['hotel_name_en'] = $params['hotel_name_en'];
        }

        if(isset($params['country_id'])){
            $data['country_id'] = $params['country_id'];
        }

        if(isset($params['status'])){
            $data['status'] = $params['status'];
        }

        $where = " b2b_hotel_id = ". $params['b2b_hotel_id'];

    	Db::startTrans();
    	try{
    		Db::name('b2b_hotel')->where($where)->update($data);

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