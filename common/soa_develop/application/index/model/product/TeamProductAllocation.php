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
 
class TeamProductAllocation extends Model
{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'team_product_allocation';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 获取团队产品(资源信息)
     * 韩
     */
    public function getTeamProductAllocation($params){
        $data = "1=1 ";

        //资源主键ID
        if(isset($params['team_product_allocation_id'])){
            $data.= " and team_product_allocation.team_product_allocation_id ='".$params['team_product_allocation_id']."'";
        }

        //团队产品ID
        if(isset($params['team_product_id'])){
            $data.= " and team_product_allocation.team_product_id ='".$params['team_product_id']."'";
        }

        //资源类型ID
        if(isset($params['supplier_type_id'])){
            $data.= " and team_product_allocation.supplier_type_id ='".$params['supplier_type_id']."'";
        }

        //对应资源ID
        if(isset($params['source_id'])){
            $data.= " and team_product_allocation.source_id ='".$params['source_id']."'";
        }

        //状态
        if(isset($params['status'])){
            $data.= " and team_product_allocation.status ='".$params['status']."'";
        }

        $result =  $this->table("team_product_allocation")->alias('team_product_allocation')->where($data)->
        field(['*',
            "(select nickname  from user where user.user_id = team_product_allocation.create_user_id)"=>'create_user_name',
            "(select nickname  from user where user.user_id = team_product_allocation.update_user_id)"=>'update_user_name',
            "(select begin_time  from team_product where team_product.team_product_id = team_product_allocation.team_product_id)"=>'begin_time',
        ])->select();

        return $result;
    }

    /**
     * 获取团队产品(资源信息-用餐)
     * 韩
     */
    public function getTeamProductAllocationHotel($params){
        $data = "1=1 ";

        //资源主键ID
        if(isset($params['team_product_allocation_id'])){
            $data.= " and team_product_allocation.team_product_allocation_id ='".$params['team_product_allocation_id']."'";
        }

        //团队产品ID
        if(isset($params['team_product_id'])){
            $data.= " and team_product_allocation.team_product_id ='".$params['team_product_id']."'";
        }

        //资源类型ID
        if(isset($params['supplier_type_id'])){
            $data.= " and team_product_allocation.supplier_type_id ='".$params['supplier_type_id']."'";
        }

        //对应资源ID
        if(isset($params['source_id'])){
            $data.= " and team_product_allocation.source_id ='".$params['source_id']."'";
        }

        $result =  $this->table("team_product_allocation")->alias('team_product_allocation')->
        join("hotel",'team_product_allocation.source_id = hotel.hotel_id','left')->
        where($data)->
        field(['*',
            "(select nickname  from user where user.user_id = team_product_allocation.create_user_id)"=>'create_user_name',
            "(select nickname  from user where user.user_id = team_product_allocation.update_user_id)"=>'update_user_name',
        ])->select();

        return $result;
    }

    /**
     * 获取团队产品(资源信息-酒店)
     * 韩
     */
    public function getTeamProductAllocationDining($params){
        $data = "1=1 ";

        //资源主键ID
        if(isset($params['team_product_allocation_id'])){
            $data.= " and team_product_allocation.team_product_allocation_id ='".$params['team_product_allocation_id']."'";
        }

        //团队产品ID
        if(isset($params['team_product_id'])){
            $data.= " and team_product_allocation.team_product_id ='".$params['team_product_id']."'";
        }

        //资源类型ID
        if(isset($params['supplier_type_id'])){
            $data.= " and team_product_allocation.supplier_type_id ='".$params['supplier_type_id']."'";
        }

        //对应资源ID
        if(isset($params['source_id'])){
            $data.= " and team_product_allocation.source_id ='".$params['source_id']."'";
        }

        $result =  $this->table("team_product_allocation")->alias('team_product_allocation')->
        join("dining",'team_product_allocation.source_id = dining.dining_id','left')->
        where($data)->
        field(['*',
            "(select nickname  from user where user.user_id = team_product_allocation.create_user_id)"=>'create_user_name',
            "(select nickname  from user where user.user_id = team_product_allocation.update_user_id)"=>'update_user_name',
        ])->select();

        return $result;
    }

    /**
     * 修改团队产品(资源配置)
     * 韩
     */
    public function updateTeamProductAllocationByTeamProductAllocationId($params){

        $t = time();

        $this->startTrans();
        try{
            //修改资源状态
            $this->name('team_product_allocation')->where(array('team_product_id'=>$params['team_product_id']))->update(['status'=>0]);

            //修改资源信息
            for($i=0;$i<count($params['edit_allocation']);$i++) {
                //资源类型ID
                if(!empty($params['edit_allocation'][$i]['supplier_type_id'])){
                    $data[$i]['supplier_type_id'] = $params['edit_allocation'][$i]['supplier_type_id'];

                }

                //对应资源ID
                if(!empty($params['edit_allocation'][$i]['source_id'])){
                    $data[$i]['source_id'] = $params['edit_allocation'][$i]['source_id'];

                }

                //币种
                if(!empty($params['edit_allocation'][$i]['payment_currency_id'])){
                    $data[$i]['payment_currency_id'] = $params['edit_allocation'][$i]['payment_currency_id'];

                }

                //单价
                if(!empty($params['edit_allocation'][$i]['source_price'])){
                    $data[$i]['source_price'] = $params['edit_allocation'][$i]['source_price'];

                }

                //数量
                if(!empty($params['edit_allocation'][$i]['source_count'])){
                    $data[$i]['source_count'] = $params['edit_allocation'][$i]['source_count'];

                }

                //总价
                if(!empty($params['edit_allocation'][$i]['source_total_price'])){
                    $data[$i]['source_total_price'] = $params['edit_allocation'][$i]['source_total_price'];

                }

                if(!empty($params['edit_allocation'][$i]['status'])){
                    $data[$i]['status'] = $params['edit_allocation'][$i]['status'];

                }

                $data[$i]['update_user_id'] = $params['user_id'];
                $data[$i]['update_time'] = $t;

                $this->name('team_product_allocation')->where(array('team_product_allocation_id'=>$params['edit_allocation'][$i]['team_product_allocation_id']))->update($data[$i]);
            }

            $t = time();
            $user_id = $params['user_id'];

            //添加资源信息
            $allocation_values="insert into team_product_allocation (team_product_id,supplier_type_id,source_id,payment_currency_id,source_price,source_count,source_total_price,create_time,create_user_id,update_time,update_user_id,status) values";

            for($i=0;$i<count($params['add_allocation']);$i++){
                //团队产品ID
                $team_product_id = $params['add_allocation'][$i]['team_product_id'];
                //资源类型ID
                $supplier_type_id = $params['add_allocation'][$i]['supplier_type_id'];
                //对应资源ID
                $source_id = $params['add_allocation'][$i]['source_id'];
                //币种
                $payment_currency_id = $params['add_allocation'][$i]['payment_currency_id'];
                //单价
                $source_price = $params['add_allocation'][$i]['source_price'];
                //数量
                $source_count = $params['add_allocation'][$i]['source_count'];
                //总价
                $source_total_price = $params['add_allocation'][$i]['source_total_price'];

                $create_time = $t;
                $create_user_id = $user_id;
                $update_time = $t;
                $update_user_id = $user_id;
                $status = $params['status'];

                if($i!=count($params['add_allocation'])-1){
                    $comma = ',';
                }else{
                    $comma = '';
                }
                $allocation_values.="($team_product_id,$supplier_type_id,$source_id,$payment_currency_id,$source_price,$source_count,$source_total_price,$create_time,$create_user_id,$update_time,$update_user_id,$status)".$comma;

            }

            $this->execute($allocation_values);

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
     * 获取团队产品(产品报价)
     * 韩
     */
    public function getTeamProductPrice($params){
        $data = "1=1 ";

        //资源主键ID
        if(isset($params['team_product_allocation_id'])){
            $data.= " and team_product_allocation.team_product_allocation_id ='".$params['team_product_allocation_id']."'";
        }

        //团队产品ID
        if(isset($params['team_product_id'])){
            $data.= " and team_product_allocation.team_product_id ='".$params['team_product_id']."'";
        }

        $result =  $this->table("team_product_allocation")->alias('team_product_allocation')->where($data)->
        field(['*',
            "(select nickname  from user where user.user_id = team_product_allocation.create_user_id)"=>'create_user_name',
            "(select nickname  from user where user.user_id = team_product_allocation.update_user_id)"=>'update_user_name',

        ])->select();

        return $result;
    }

    public function getSourceRemindObject($data){
        $where['supplier_type_id'] = $data['supplier_type_id'];
        $where['source_id'] = $data['source_id'];
        $where['status'] = 1;

        $arr =  $this->table("team_product_allocation")
            ->where($where)
            ->column('team_product_id');

        $arr = array_unique($arr);

        $result = $this->table("team_product")
            ->where('team_product_id', 'IN', $arr)
            ->column('team_product_user_id');

        $result = array_unique($result);

        return $result;
    }
}

