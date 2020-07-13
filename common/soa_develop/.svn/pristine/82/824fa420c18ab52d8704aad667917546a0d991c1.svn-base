<?php
namespace app\index\model\product;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class TeamProductReturnReceipt extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'team_product_return_receipt';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
     
    }
    
    /**
     * 获取团队产品(回执单模版)
     * 韩
     */
    public function getTeamProductReturnReceipt($params){

        $data = "1=1 ";

        //团队产品ID
        if(isset($params['team_product_id'])){
            $data.= " and team_product_return_receipt.team_product_id ='".$params['team_product_id']."'";
        }
		if(is_numeric($params['status'])){
			$data.= " and team_product_return_receipt.status ='".$params['status']."'";
			
		}
        $result =  $this->table("team_product_return_receipt")->alias('team_product_return_receipt')->where($data)->
        field(['*',
            "(select nickname  from user where user.user_id = team_product_return_receipt.create_user_id)"=>'create_user_name',
            "(select nickname  from user where user.user_id = team_product_return_receipt.update_user_id)"=>'update_user_name',

        ])->order("sorting asc")->select();

        return $result;
    
    }

    
    /**
     * 修改团队产品(回执单模版)
     * 韩
     */
    public function updateTeamProductReturnReceiptByTeamProductReturnReceiptId($params){
    
    	$t = time();

    	$this->startTrans();
    	try{

            //修改回执单模版状态
            $this->name('route_return_receipt')->where(array('team_product_id'=>$params['team_product_id']))->update(['status'=>0]);

            //修改回执单模版
            for($i=0;$i<count($params['edit_return_receipt']);$i++){

                //标题
                if(!empty($params['edit_return_receipt'][$i]['title'])) {
                    $data[$i]['title'] = $params['edit_return_receipt'][$i]['title'];

                }

                //内容
                if(!empty($params['edit_return_receipt'][$i]['content'])) {
                    $data[$i]['content'] = $params['edit_return_receipt'][$i]['content'];

                }

                //排序
                if(!empty($params['edit_return_receipt'][$i]['sorting'])) {
                    $data[$i]['sorting'] = $params['edit_return_receipt'][$i]['sorting'];

                }

                if(!empty($params['edit_return_receipt'][$i]['status'])){
                    $data[$i]['status'] = $params['edit_return_receipt'][$i]['status'];

                }

                $data[$i]['update_user_id'] = $params['user_id'];
                $data[$i]['update_time'] = $t;

                $this->name('route_return_receipt')->where(array('route_return_receipt_id'=>$params['edit_return_receipt'][$i]['route_return_receipt_id']))->update($data[$i]);
            }


            $t = time();
            $user_id = $params['user_id'];

            //添加回执单模版
            $return_receipt_values="insert into team_product_return_receipt (team_product_id,title,content,sorting,create_time,create_user_id,update_time,update_user_id,status) values";

            for($i=0;$i<count($params['add_return_receipt']);$i++){
                //团队产品ID
                $team_product_id = $params['add_return_receipt'][$i]['team_product_id'];
                //标题
                $title = $params['add_return_receipt'][$i]['title'];
                //内容
                $content = $params['add_return_receipt'][$i]['content'];
                //排序
                $sorting = $params['add_return_receipt'][$i]['sorting'];

                $create_time = $t;
                $create_user_id = $user_id;
                $update_time = $t;
                $update_user_id = $user_id;
                $status = $params['status'];

                if($i!=count($params['add_return_receipt'])-1){
                    $comma = ',';
                }else{
                    $comma = '';
                }
                $return_receipt_values.="($team_product_id,'$title','$content',$sorting,$create_time,$create_user_id,$update_time,$update_user_id,$status)".$comma;

            }
		
            $this->execute($return_receipt_values);

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
}