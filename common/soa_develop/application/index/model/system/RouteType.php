<?php
namespace app\index\model\system;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class RouteType extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'route_type';
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
    public function addRouteType($params){


    	$t = time();
    	$data['route_type_name'] = $params['route_type_name'];
        $data['company_id'] = $params['choose_company_id'];
    	$data['pid'] = $params['pid'];
    	$data['route_type_user_id'] = $params['route_type_user_id'];
    	$data['create_time'] = $t;
    	$data['create_user_id'] = $params['now_user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['now_user_id'];
    	$data['status'] = $params['status'];
        $data['route_type_code'] = $params['route_type_code'];
    	Db::startTrans();
    	try{
    		$pk_id = Db::name('route_type')->insertGetId($data);
		
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
    public function getRouteType($params,$is_count=false,$is_page=false,$page=null,$page_size=20){


        $data = "1=1";
        if(isset($params['route_type_name'])){
            $data.= " and route_type.route_type_name like '%".$params['route_type_name']."%'";
        }
        if(isset($params['route_type_id'])){
            $data.= " and route_type.route_type_id = '".$params['route_type_id']."'";
        }
        if(isset($params['pid'])){
            $data.= " and route_type.pid = '".$params['pid']."'";
        }
        if(isset($params['route_type_user_id'])){
            $data.= " and route_type_user_id ='".$params['route_type_id']."'";
        }
        if(is_numeric($params['status'])){
            $data.= " and route_type.status = ".$params['status'];
        }

        if(is_numeric($params['company_id'])){
            $data.= " and route_type.company_id = '".$params['company_id']."'";
        }
        if($is_count==true){
            $result = $this->table("route_type")->alias("route_type")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("route_type")
                    ->join("route_type r", "r.route_type_id = route_type.pid", 'left')
                    ->join("route_type t", "t.route_type_id = r.pid", 'left')
                    ->join("company c", "c.company_id = route_type.company_id", 'left')
                    ->where($data)->limit($page, $page_size)
                    ->field(['route_type.*','r.route_type_name as p_name','t.route_type_id as p_route_type_id','r.route_type_id as p_id','c.company_name'])
                    ->select();
                return $result;
            }else{
                $result = $this->table("route_type")->
                    where($data)->field(['route_type.*'])
                    ->select();
               
                $result = Help::toTree($result);
                foreach($result as &$value){
                    $value['route_type_name'] = str_repeat('--', $value['level']).$value['route_type_name'];
                }
       
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
    public function getOneRouteType($params){

      
            $data = ['route_type_id' => $params['route_type_id']];
        

//        $result = $this->table("route_type")->
//        where($data)->field(['route_type.*'])->find();

        $result = $this->table("route_type")
            ->field(['route_type.*', "(select route_type_name  from route_type as c where route_type.pid = c.route_type_id ) as p_name",])
            ->where($data)
            ->find();

        return $result;
    }
    
    /**
     * 修改路线类型
     */
    public function updateRouteTypeByRouteTypeId($params){
    
    	$t = time();
    	
    	if(!empty($params['route_type_name'])){
    		$data['route_type_name'] = $params['route_type_name'];
    	
    	}

    	$data['status'] = $params['status'];

    	if(!empty($params['pid'])){
    		$data['pid'] = $params['pid'];
    	}

        if(!empty($params['route_type_user_id'])){
            $data['route_type_user_id'] = $params['route_type_user_id'];
        }

        if(!empty($params['choose_company_id'])){
            $data['company_id'] = $params['choose_company_id'];
        }
        if(isset($params['route_type_code'])){
            $data['route_type_code'] = $params['route_type_code'];
        }

    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;

    	$source_price=[];
    	Db::startTrans();
    	try{
    		Db::name('route_type')->where("route_type_id = ".$params['route_type_id'])->update($data);
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