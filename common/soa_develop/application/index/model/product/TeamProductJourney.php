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
 
class TeamProductJourney extends Model
{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'team_product_journey';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 获取团队产品(行程信息)
     * 韩
     */
    public function getTeamProductJourney($params){
        $data = "1=1 and status=1";

        //团队产品ID
        if(isset($params['team_product_id'])){
            $data.= " and team_product_journey.team_product_id ='".$params['team_product_id']."'";
        }
		
        
        $result =  $this->table("team_product_journey")->alias('team_product_journey')->where($data)->
        field(['*',
            "(select nickname  from user where user.user_id = team_product_journey.create_user_id)"=>'create_user_name',
            "(select nickname  from user where user.user_id = team_product_journey.update_user_id)"=>'update_user_name',

        ])->select();

        return $result;
    }

    /**
     * 修改团队产品(行程配置)
     * 韩
     */
    public function updateTeamProductJourneyByTeamProductJourneyId($params){

        $t = time();

        $this->startTrans();
        try{
            //修改行程状态
            $this->name('team_product_journey')->where(array('team_product_id'=>$params['team_product_id']))->update(['status'=>0]);

            //修改行程信息
            for($i=0;$i<count($params['edit_journey']);$i++){
                //第几天
                if(!empty($params['edit_journey'][$i]['the_days'])){
                    $data[$i]['the_days'] = $params['edit_journey'][$i]['the_days'];

                }

                //行程标题
                if(!empty($params['edit_journey'][$i]['route_journey_title'])){
                    $data[$i]['route_journey_title'] = $params['edit_journey'][$i]['route_journey_title'];

                }

                //行程内容
                if(!empty($params['edit_journey'][$i]['route_journey_content'])){
                    $data[$i]['route_journey_content'] = $params['edit_journey'][$i]['route_journey_content'];

                }

                //交通
                if(!empty($params['edit_journey'][$i]['route_journey_traffic'])){
                    $data[$i]['route_journey_traffic'] = $params['edit_journey'][$i]['route_journey_traffic'];

                }

                //航班号
                if(!empty($params['edit_journey'][$i]['flight_number'])){
                    $data[$i]['flight_number'] = $params['edit_journey'][$i]['flight_number'];

                }

                //住宿
                if(!empty($params['edit_journey'][$i]['route_journey_stay'])){
                    $data[$i]['route_journey_stay'] = $params['edit_journey'][$i]['route_journey_stay'];

                }

                //吃饭标注
                if(!empty($params['edit_journey'][$i]['eat_mark'])){
                    $data[$i]['eat_mark'] = $params['edit_journey'][$i]['eat_mark'];

                }

                //早餐
                if(!empty($params['edit_journey'][$i]['route_journey_breakfast'])){
                    $data[$i]['route_journey_breakfast'] = $params['edit_journey'][$i]['route_journey_breakfast'];

                }

                //午餐
                if(!empty($params['edit_journey'][$i]['route_journey_lunch'])){
                    $data[$i]['route_journey_lunch'] = $params['edit_journey'][$i]['route_journey_lunch'];

                }

                //晚餐
                if(!empty($params['edit_journey'][$i]['route_journey_dinner'])){
                    $data[$i]['route_journey_dinner'] = $params['edit_journey'][$i]['route_journey_dinner'];

                }

                //景点
                if(!empty($params['edit_journey'][$i]['route_journey_scenic_sport'])){
                    $data[$i]['route_journey_scenic_sport'] = $params['edit_journey'][$i]['route_journey_scenic_sport'];

                }

                //图片
                if(!empty($params['edit_journey'][$i]['route_journey_picture'])){
                    $data[$i]['route_journey_picture'] = $params['edit_journey'][$i]['route_journey_picture'];

                }

                //备注
                if(!empty($params['edit_journey'][$i]['route_journey_remark'])){
                    $data[$i]['route_journey_remark'] = $params['edit_journey'][$i]['route_journey_remark'];

                }

                if(!empty($params['edit_journey'][$i]['status'])){
                    $data[$i]['status'] = $params['edit_journey'][$i]['status'];

                }

                $data[$i]['update_user_id'] = $params['user_id'];
                $data[$i]['update_time'] = $t;

                $this->name('team_product_journey')->where(array('team_product_journey_id'=>$params['edit_journey'][$i]['team_product_journey_id']))->update($data[$i]);
            }

            $t = time();
            $user_id = $params['user_id'];

            //添加行程内容
            $journey_values="insert into team_product_journey (team_product_id,the_days,route_journey_title,route_journey_content,route_journey_traffic,route_journey_stay,eat_mark,route_journey_breakfast,route_journey_lunch,route_journey_dinner,route_journey_scenic_sport,route_journey_picture,route_journey_remark,create_time,create_user_id,update_time,update_user_id,status) values";

            for($i=0;$i<count($params['add_journey']);$i++){
                //团队产品ID
                $team_product_id = $params['add_journey'][$i]['team_product_id'];
                //第几天
                $the_days = $params['add_journey'][$i]['the_days'];
                //行程标题
                $route_journey_title = $params['add_journey'][$i]['route_journey_title'];
                //行程内容
                $route_journey_content = $params['add_journey'][$i]['route_journey_content'];
                //交通
                $route_journey_traffic = $params['add_journey'][$i]['route_journey_traffic'];
                //住宿
                $route_journey_stay = $params['add_journey'][$i]['route_journey_stay'];
                //吃饭标注
                $eat_mark = $params['add_journey'][$i]['eat_mark'];
                //早餐
                $route_journey_breakfast = $params['add_journey'][$i]['route_journey_breakfast'];
                //午餐
                $route_journey_lunch = $params['add_journey'][$i]['route_journey_lunch'];
                //晚餐
                $route_journey_dinner = $params['add_journey'][$i]['route_journey_dinner'];
                //景点
                $route_journey_scenic_sport = $params['add_journey'][$i]['route_journey_scenic_sport'];
                //图片
                $route_journey_picture = $params['add_journey'][$i]['route_journey_picture'];
                //备注
                $route_journey_remark = $params['add_journey'][$i]['route_journey_remark'];

                $create_time = $t;
                $create_user_id = $user_id;
                $update_time = $t;
                $update_user_id = $user_id;
                $status = $params['status'];

                if($i!=count($params['add_journey'])-1){
                    $comma = ',';
                }else{
                    $comma = '';
                }

                $journey_values.="($team_product_id,$the_days,'$route_journey_title','$route_journey_content','$route_journey_traffic','$route_journey_stay','$eat_mark','$route_journey_breakfast','$route_journey_lunch','$route_journey_dinner','$route_journey_scenic_sport','$route_journey_picture','$route_journey_remark',$create_time,$create_user_id,$update_time,$update_user_id,$status)".$comma;

            }

            $this->execute($journey_values);

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
     * 添加团队产品行程
     */
    public function addTeamProductJourney($params){

    	$t = time();
    	
    	$this->startTrans();
    	try{

    	
    		$data['team_product_id']= $params['team_product_id'];
    		$data['the_days']  = $params['the_days'];
    		$data['route_journey_title']  = $params['route_journey_title'];
    		$data['route_journey_content']  = $params['route_journey_content'];
    		
    		if(!empty($params['eat_mark'])){
    			$data['eat_mark']  = $params['eat_mark'];
    		}
    		if(!empty($params['route_journey_remark'])){
    			$data['route_journey_remark']  = $params['route_journey_remark'];
    		}   		
    		
    		$data['create_time']= $t;

    		$data['update_time']= $t;
    		$data['create_user_id'] = 1;
    		$data['update_user_id'] = 1;
    		$data['status'] = 1;


    	
    		$this->insert($data);
    	
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