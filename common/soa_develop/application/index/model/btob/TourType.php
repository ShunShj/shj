<?php
namespace app\index\model\Btob;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class TourType extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'b2b_tour_type';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

    /**
     * addTourType
     *
     * 添加B2B代售产品类型
     * @author shj
     *
     * @param $params
     *
     * @return int|string
     * Date: 2019/11/8
     * Time: 14:51
     */
    public function addTourType($params){

    	$t = time();
    	$data['tour_type_name'] = $params['tour_type_name'];
        $data['cn_name'] = $params['cn_name'];
        $data['system_type'] = $params['system_type'];
        $data['company_id'] = $params['choose_company_id'];
    	$data['pid'] = $params['pid'];
    	$data['create_time'] = $t;
    	$data['create_user_id'] = $params['now_user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['now_user_id'];
    	$data['status'] = $params['status'];

    	Db::startTrans();
    	try{
    		$pk_id = Db::name('b2b_tour_type')->insertGetId($data);
		
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
     * getTourType
     *
     * 获取B2B代售产品类型
     * @author shj
     *
     * @param      $params
     * @param bool $is_count
     * @param bool $is_page
     * @param null $page
     * @param int  $page_size
     *
     * @return array|false|int|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Date: 2019/11/8
     * Time: 14:51
     */
    public function getTourType($params,$is_count=false,$is_page=false,$page=null,$page_size=20){


        $data = "1=1";
        if(isset($params['tour_type_name'])){
            $data.= " and b2b_tour_type.tour_type_name like '%".$params['tour_type_name']."%'";
        }
        if(isset($params['system_type'])){
            $data.= " and b2b_tour_type.system_type like '%".$params['system_type']."%'";
        }
        if(isset($params['tour_type_id'])){
            $data.= " and b2b_tour_type.tour_type_id = '".$params['tour_type_id']."'";
        }
        if(isset($params['pid'])){
            $data.= " and b2b_tour_type.pid = ".$params['pid'];
        }
        if(is_numeric($params['status'])){
            $data.= " and b2b_tour_type.status = ".$params['status'];
        }
        if(is_numeric($params['company_id'])){
            $data.= " and b2b_tour_type.company_id = '".$params['company_id']."'";
        }
        if($is_count==true){
            $result = $this->table("b2b_tour_type")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("b2b_tour_type")
                    ->join("b2b_tour_type r", "r.tour_type_id = b2b_tour_type.pid", 'left')
                    ->join("b2b_tour_type t", "t.tour_type_id = r.pid", 'left')
                    ->join("company c", "c.company_id = b2b_tour_type.company_id", 'left')
                    ->where($data)->limit($page, $page_size)
                    ->field(['b2b_tour_type.*','r.tour_type_name as p_name','t.tour_type_id as p_tour_type_id','r.tour_type_id as p_id','c.company_name'])
                    ->select();
                return $result;
            }else{
                $result = $this->table("b2b_tour_type")
                    ->where($data)
                    ->field(['b2b_tour_type.*'])
                    ->select();

                $result = Help::toTree($result,0,0,'tour_type_id');

                foreach($result as &$value){
                    $value['tour_type_name'] = str_repeat('--', $value['level']).$value['tour_type_name'];
                }
            }
        }
      
        return $result;
    }


    public function getTourTypeAjax($params){

        $data = "1=1";
        if(isset($params['tour_type_name'])){
            $data.= " and b2b_tour_type.tour_type_name like '%".$params['tour_type_name']."%'";
        }
        if(isset($params['system_type'])){
            $data.= " and b2b_tour_type.system_type like '%".$params['system_type']."%'";
        }
        if(isset($params['tour_type_id'])){
            $data.= " and b2b_tour_type.tour_type_id = '".$params['tour_type_id']."'";
        }
        if(isset($params['pid'])){
            $data.= " and b2b_tour_type.pid = ".$params['pid'];
        }
        if(is_numeric($params['status'])){
            $data.= " and b2b_tour_type.status = ".$params['status'];
        }
        if(is_numeric($params['company_id'])){
            $data.= " and b2b_tour_type.company_id = '".$params['company_id']."'";
        }

        $result = $this->table("b2b_tour_type")
            ->where($data)
            ->field(['b2b_tour_type.*'])
            ->select();

        return $result;
    }

    /**
     * getOneTourType
     *
     * 获取一条添加B2B代售产品类型
     * @author shj
     *
     * @param $params
     *
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Date: 2019/11/8
     * Time: 14:52
     */
    public function getOneTourType($params){

        $data = ['tour_type_id' => $params['tour_type_id']];

        $result = $this->table("b2b_tour_type")
            ->field(['b2b_tour_type.*', "(select tour_type_name  from b2b_tour_type as c where b2b_tour_type.pid = c.tour_type_id ) as p_name",])
            ->where($data)
            ->find();

        return $result;
    }

    /**
     * updateTourTypeByTourTypeId
     *
     * 修改添加B2B代售产品类型
     * @author shj
     *
     * @param $params
     *
     * @return int|string
     * Date: 2019/11/8
     * Time: 14:52
     */
    public function updateTourTypeByTourTypeId($params){
    
    	$t = time();
    	
    	if(isset($params['tour_type_name'])){
    		$data['tour_type_name'] = $params['tour_type_name'];
    	}

    	$data['status'] = $params['status'];

    	if(isset($params['pid'])){
    		$data['pid'] = $params['pid'];
    	}

        if(isset($params['choose_company_id'])){
            $data['company_id'] = $params['choose_company_id'];
        }

        if(isset($params['cn_name'])){
            $data['cn_name'] = $params['cn_name'];
        }

        if(isset($params['system_type'])){
            $data['system_type'] = $params['system_type'];
        }

    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;

    	Db::startTrans();
    	try{
    		Db::name('b2b_tour_type')->where("tour_type_id = ".$params['tour_type_id'])->update($data);
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