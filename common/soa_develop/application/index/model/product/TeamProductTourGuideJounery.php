<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/6
 * Time: 10:13
 */
 
namespace app\index\model\product;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;

class TeamProductTourGuideJounery extends Model
{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'team_product_tour_guide_jounery';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加团队产品-导游回执单上传文件
     * 韩
     */
    public function addTeamProductGuideReceiptFile($params){
        $t = time();
        $user_id = $params['user_id'];

        $data['team_product_id'] = $params['team_product_id'];
        $data['team_product_number'] = $params['team_product_number'];
        $data['file_name'] = $params['file_name'];

        $data['create_time'] = $t;
        $data['create_user_id']= $user_id;
        $data['update_time'] = $t;
        $data['update_user_id'] = $user_id;
        $data['status'] = 1;

        //修上传文件状态
        $this->table('team_product_tour_guide_jounery')->where(array('team_product_number'=>$params['team_product_number']))->update(['status'=>0]);
  
        $this->startTrans();
        try{
            $result  = $this->insertGetId($data);
		

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
     * 获取团队产品-导游回执单上传文件
     * 韩
     */
    public function getTeamProductGuideReceiptFile($params){

        $data = "1=1 ";
        //团队产品ID
        if(isset($params['team_product_id'])){
            $data.= " and team_product_tour_guide_jounery.team_product_id ='".$params['team_product_id']."'";
        }

        //团队产品编号
        if(isset($params['team_product_number'])){
            $data.= " and team_product_tour_guide_jounery.team_product_number ='".$params['team_product_number']."'";
        }

        $result =  $this->query("select * from team_product_tour_guide_jounery where {$data} and status=1");

        return $result;
    }
    /**
     * 修改
     */
    public function updateTeamProductTourGuideJounery($params){
    	$where['team_product_number'] = $params['team_product_number'];
    	$data['status']=0;
    	$result = $this->where($where)->update($data);

    	return $result;
    }
}