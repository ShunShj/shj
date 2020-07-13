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
 
class TeamProductFlight extends Model
{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'team_product_flight';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 获取团队产品(航班信息)
     * 韩
     */
    public function getTeamProductFlight($params){
        $data = "1=1 ";

        //团队产品ID
        if(isset($params['team_product_id'])){
            $data.= " and team_product_flight.team_product_id ='".$params['team_product_id']."'";
        }

        $result =  $this->table("team_product_flight")->alias('team_product_flight')->where($data)->
        field(['*',
            "(select nickname  from user where user.user_id = team_product_flight.create_user_id)"=>'create_user_name',
            "(select nickname  from user where user.user_id = team_product_flight.update_user_id)"=>'update_user_name',

        ])->select();

        return $result;
    }

    /**
     * 修改团队产品(航班配置)
     * 韩
     * 支持同时修改插入
     */
    public function updateTeamProductFlightByTeamProductFlightId($params){

        $t = time();

        $this->startTrans();
        try{
            //修改航班状态
            $this->name('team_product_flight')->where(array('team_product_id'=>$params['team_product_id']))->update(['status'=>0]);

            //修改航班信息
            for($i=0;$i<count($params['edit_flight']);$i++){

                //第几天
                if(!empty($params['edit_flight'][$i]['the_days'])) {
                    $data[$i]['the_days'] = $params['edit_flight'][$i]['the_days'];

                }

                //出发地
                if(!empty($params['edit_flight'][$i]['start_city'])){
                    $data[$i]['start_city'] = $params['edit_flight'][$i]['start_city'];

                }

                //目的地
                if(!empty($params['edit_flight'][$i]['end_city'])){
                    $data[$i]['end_city'] = $params['edit_flight'][$i]['end_city'];

                }

                //出发时间
                if(!empty($params['edit_flight'][$i]['start_time'])){
                    $data[$i]['start_time'] = $params['edit_flight'][$i]['start_time'];

                }

                //到达时间
                if(!empty($params['edit_flight'][$i]['end_time'])){
                    $data[$i]['end_time'] = $params['edit_flight'][$i]['end_time'];

                }

                //航班号
                if(!empty($params['edit_flight'][$i]['flight_number'])){
                    $data[$i]['flight_number'] = $params['edit_flight'][$i]['flight_number'];

                }

                //接送机
                if(!empty($params['edit_flight'][$i]['flight_type'])){
                    $data[$i]['flight_type'] = $params['edit_flight'][$i]['flight_type'];

                }

                if(!empty($params['edit_flight'][$i]['status'])){
                    $data[$i]['status'] = $params['edit_flight'][$i]['status'];

                }

                $data[$i]['update_user_id'] = $params['user_id'];
                $data[$i]['update_time'] = $t;

                $this->name('team_product_flight')->where(array('team_product_flight_id'=>$params['edit_flight'][$i]['team_product_flight_id']))->update($data[$i]);
            }


            $t = time();
            $user_id = $params['user_id'];

            //添加航班信息
            $flight_values="insert into team_product_flight (team_product_id,the_days,start_city,end_city,start_time,end_time,flight_number,flight_type,create_time,create_user_id,update_time,update_user_id,status) values";

            for($i=0;$i<count($params['add_flight']);$i++){
                //团队产品ID
                $team_product_id = $params['add_flight'][$i]['team_product_id'];
                //第几天
                $the_days = $params['add_flight'][$i]['the_days'];
                //出发地
                $start_city = $params['add_flight'][$i]['start_city'];
                //目的地
                $end_city = $params['add_flight'][$i]['end_city'];
                //出发时间
                $start_time = $params['add_flight'][$i]['start_time'];
                //到达时间
                $end_time = $params['add_flight'][$i]['end_time'];
                //航班编号
                $flight_number = $params['add_flight'][$i]['flight_number'];
                //接送机
                $flight_type = $params['add_flight'][$i]['flight_type'];

                $create_time = $t;
                $create_user_id = $user_id;
                $update_time = $t;
                $update_user_id = $user_id;
                $status = $params['status'];

                if($i!=count($params['add_flight'])-1){
                    $comma = ',';
                }else{
                    $comma = '';
                }

                $flight_values.="($team_product_id,$the_days,'$start_city','$end_city',$start_time,$end_time,'$flight_number',$flight_type,$create_time,$create_user_id,$update_time,$update_user_id,$status)".$comma;

            }

            $this->execute($flight_values);

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