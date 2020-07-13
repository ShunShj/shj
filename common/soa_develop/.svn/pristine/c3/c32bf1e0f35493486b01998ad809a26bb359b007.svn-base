<?php
namespace app\index\model\ota_product;

use app\index\model\system\User;
use think\Db;
use think\Exception;
use think\Model;
use app\common\help\Help;

class OtaProductType extends Model{
 
    protected $table = 'ota_product_type';

    //自动过滤掉不存在的字段
    protected $field = true;

    public function initialize()
    {
        parent::initialize();
    }

    public function getOne($params)
    {
        $where = [];
        if ($params['uuid'])
        {
            $where['uuid'] = $params['uuid'];
        }

        if ($params['type_id'])
        {
            $where['ota_product_type_id'] = $params['type_id'];
        }

        $info = $this->where($where)->find();

        $user_model = new User();
        $info['create_username'] = $user_model->getOneUser($info['create_user_id'])['username'];

        return $info;
    }



    public function getDestinationAndScenicSpot($params)
    {

        $where = [];
        if ($params['type_uuid'])
        {
            if (is_array($params['type_uuid']))
            {
                $where['ota_type_product_uuid'] = array('in', $params['type_uuid']);
            }
            else
            {
                $where['ota_type_product_uuid'] = $params['type_uuid'];
            }
        }

        $where['status'] = 1;
        $arr['destination'] = Db::table('ota_product_type_destination')->where($where)->select();
        $arr['scenic_spot'] = Db::table('ota_product_type_scenic_spot')->where($where)->select();
        return $arr;
    }

    /**
     * 获取旅游分类合集
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/15
     * Time: 9:54
     * @param $post_data array 查询条件数组
     * @return mixed 返回数据
     */
    public function getProductType($post_data)
    {
        $product_type_model = new OtaProductType();
 
        $where = " 1=1 ";

        if(is_numeric($post_data['type_lv']))
        {
            $where .= " and t.type_lv = ". $post_data['type_lv'];
        }

        if(is_numeric($post_data['status']))
        {
            $where .= " and t.status = ". $post_data['status'];
        }

        if (isset($post_data['is_like']) && $post_data['is_like'] == 1)
        {
            if($post_data['type_name'])
            {
                $where .= " and t.type_name like '%". $post_data['type_name'] ."%'";
            }

            if($post_data['create_username'])
            {
                $where .= " and u.username like '%". $post_data['create_username'] ."%'";
            }
        }
        else
        {
            if($post_data['type_name'])
            {
                $where .= " and t.type_name = ". $post_data['type_name'];
            }

            if($post_data['create_username'])
            {
                $where .= " and u.username = " . $post_data['create_username'];
            }
        }


        if($post_data['company_id'])
        {
            $where .= " and t.company_id  = ". $post_data['company_id'];
        }

        if($post_data['website_uuid'])
        {
            $where .= " and t.website_uuid  = '". $post_data['website_uuid']. "'";
        }

        if (isset($post_data['page']) && isset($post_data['size']))
        {

            //分页
            $count = $product_type_model->alias('t')->where($where)
                ->join('user u', 'u.user_id = t.create_user_id')
                ->count();

            $list = $product_type_model->alias('t')
                ->field(["t.ota_product_type_id", "t.uuid", "t.type_name", "t.type_lv", "t.pid", "t.create_time", "t.status", "u.username as create_username",
                    "(select type_name from ota_product_type as parent_type  where parent_type.ota_product_type_id = t.pid)" => "p_name"
                ])
                ->join('user u', 'u.user_id = t.create_user_id')
                ->where($where)->order('ota_product_type_id', 'DESC')->limit(($post_data['page']-1)*$post_data['size'], $post_data['size'])->select();

            return [
                'count' => $count,
                'list' => $list,
                'page_count' => ceil($count / $post_data['size'])
            ];

        }
        else {

            return $product_type_model->alias('t')
                ->field(["t.ota_product_type_id", "t.uuid", "t.type_name", "t.type_lv", "t.pid", "t.create_time", "t.status", "u.username as create_username",
                    "(select type_name from ota_product_type as parent_type  where parent_type.ota_product_type_id = t.pid)" => "p_name"
                    ])
                ->join('user u', 'u.user_id = t.create_user_id')
                ->where($where)->order('ota_product_type_id', 'DESC')->select();
        }
    }

    public function unsetChild($data, $id)
    {
        //获取子类的id
        $child_ids = array_column(Help::toTree($data, $id, 0, 'ota_product_type_id'), 'ota_product_type_id');
        $child_ids[] = $id;

        $arr = [];
        foreach ($data as $k => $v)
        {
            if (!in_array($v['ota_product_type_id'], $child_ids)) $arr[] = $v;
        }

        return $arr;
    }

    public function addProductType($post_data)
    {
        $data['type_name'] = $post_data['type_name'];
        $data['pid'] = $post_data['pid'];
        $data['status'] = $post_data['status'];
        $data['company_id'] = $post_data['user_company_id'];
        $data['create_user_id'] = $post_data['now_user_id'];
        $data['create_time'] = time();
        $data['uuid'] = Help::getUuid();
        $data['website_uuid'] = $post_data['website_uuid'];
        $data['author'] = $post_data['author'];
        $data['keywords'] = $post_data['keywords'];
        $data['description'] = $post_data['description'];
        $data['type_lv'] = 0;
        if ($data['pid'] > 0)
        {
            $parent_info = $this->where(['ota_product_type_id' => $data['pid']])->find();

            if (!$parent_info) return false;
            $data['type_lv'] = $parent_info['type_lv']++;
        }


        if ($this->where(['type_name' => $post_data['type_name']])->find())
        {
            \think\Response::create(['code' => '400', 'msg' => 'type_name repeat'], 'json')->send();
            exit;
        }

        $this->startTrans();
        try
        {
            if ($this->insert($data) === false) return false;

            if (trim($post_data['destination']))
            {
                if ($this->addDestination($post_data['destination'], $data['uuid']) === false) return false;
            }

            if (trim($post_data['scenic_spot']))
            {
                if ($this->addScenicSpot($post_data['scenic_spot'], $data['uuid']) === false) return false;
            }

            $this->commit();
            return true;

        }
        catch (Exception $e)
        {
            $this->rollback();
            return $e->getMessage();
        }
    }


    public function addDestination($destination, $type_uuid)
    {

        $destination = explode(',', trim(str_replace("，",",", $destination), ','));
        $destination_arr = [];
        foreach ($destination as $v)
        {
            $arr = [];
            $arr['ota_type_product_uuid'] = $type_uuid;
            $arr['destination_name'] = $v;
            $arr['uuid'] = Help::getUuid();
            $arr['status'] = 1;
            array_push($destination_arr, $arr);
        }
        return Db::table('ota_product_type_destination')->insertAll($destination_arr);
    }

    public function addScenicSpot($scenic_spot, $type_uuid)
    {

        $scenic_spot = explode(',', trim(str_replace("，",",", $scenic_spot), ','));
        $scenic_spot_arr = [];
        foreach ($scenic_spot as $v)
        {
            $arr = [];
            $arr['ota_type_product_uuid'] = $type_uuid;
            $arr['scenic_spot_name'] = $v;
            $arr['uuid'] = Help::getUuid();
            $arr['status'] = 1;
            array_push($scenic_spot_arr, $arr);
        }
        return Db::table('ota_product_type_scenic_spot')->insertAll($scenic_spot_arr);
    }

    public function editProductType($post_data)
    {
        $where['uuid'] = $post_data['uuid'];

        if ($post_data['website_uuid'])
        {
            $where['website_uuid'] = $post_data['website_uuid'];
        }

        $data['type_name'] = $post_data['type_name'];
        $data['pid'] = $post_data['pid'];
        $data['author'] = $post_data['author'];
        $data['keywords'] = $post_data['keywords'];
        $data['description'] = $post_data['description'];

        $info = $this->where(['type_name' => $post_data['type_name']])->find();
        if ($info && $info['uuid'] != $where['uuid'])
        {
            \think\Response::create(['code' => '400', 'msg' => 'type_name repeat'], 'json')->send();
            exit;
        }

        $product_type_info = $this->where($where)->find();

        //数据库没有这条数据
        if (!$product_type_info) return false;

        //修改的pid不可以是自己的id
        if ($product_type_info['ota_product_type_id'] == $post_data['pid']) return false;

        //搜索自己的父级
        $parent_info = $this->where(['ota_product_type_id'=>$product_type_info["pid"]])->find();

        if ($parent_info['pid'] == $product_type_info['ota_product_type_id']) return false;


        $data['status'] = $post_data['status'];
        $data['company_id'] = $post_data['user_company_id'];
        $data['update_user_id'] = $post_data['now_user_id'];
        $data['update_time'] = time();

        $this->startTrans();
        try
        {
            if ($this->update($data, $where) === false) return false;

            Db::table('ota_product_type_destination')->where([
                'ota_type_product_uuid' => $where['uuid'],
                'uuid' => ['not in', implode(',', $post_data['destination_uuid'])]
            ])->update(['status' => 0]);

            Db::table('ota_product_type_scenic_spot')->where([
                'ota_type_product_uuid' => $where['uuid'],
                'uuid' => ['not in', implode(',', $post_data['scenic_spot_uuid'])]
            ])->update(['status' => 0]);
            if ($post_data['destination'])
            {
                $destination = implode(',', $post_data['destination']);

                if ($this->addDestination($destination, $where['uuid']) === false) return false;
            }

            if ($post_data['scenic_spot'])
            {
                $scenic_spot = implode(',', $post_data['scenic_spot']);

                if ($this->addScenicSpot($scenic_spot, $where['uuid']) === false) return false;
            }

            $this->commit();
            return true;
        }
        catch (Exception $e)
        {
            $this->rollback();
            return $e->getMessage();
        }

    }


    /**
     * 删除旅游产品分类  status = 0  子类也全部设为 0
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/15
     * Time: 9:55
     * @param $post_data array 提交的数据
     * @return boolean 返回结果
     */
    public function delOtaProductType($post_data)
    {
        $product_type_model = new OtaProductType();
        $product_type_model->startTrans();
        try
        {
            $where['uuid'] = $post_data['uuid'];

            if ($post_data['website_uuid'])
            {
                $where['website_uuid'] = $post_data['website_uuid'];
            }
            $type_info = $this->where($where)->find();

            if ($type_info)
            {
                $data['status'] = 0;
                $this->update($data, $where);
                $child_list = $this->getChildByType(['uuid' => $post_data['uuid']]);

                foreach ($child_list as $v)
                {
                    //将子分类设为无效
                    $this->update($data, ['uuid' => $v["uuid"]]);
                    //删除所有子分类关联的产品
                    //Db::table('ota_product_types')->where(['product_type_uuid'=>$v["uuid"]])->delete();
                }

                //删除分类下的所有目的地and景点
                Db::table('ota_product_type_destination')->where(['ota_type_product_uuid'=> $where['uuid']])->update($data);
                Db::table('ota_product_type_scenic_spot')->where(['ota_type_product_uuid'=> $where['uuid']])->update($data);
            }

            //分类下所有关联的产品
            $product_type_model->commit();
            return true;
        }
        catch (\Exception $e)
        {
            $product_type_model->rollback();
            return false;
        }
    }

    public function getChildProductType($pid)
    {
        return $this->where(['pid'=> $pid])->select();
    }

    /**
     * 获取当前产品的所属分类
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/30
     * Time: 14:05
     * @param $product_uuid string 产品的uuid
     */
    public function getProductTypesByProductUuid($product_uuid)
    {
        return $this->alias('t')->join('ota_product_types o', 'o.product_type_uuid = t.uuid')->where("o.product_uuid = '".$product_uuid."'")->select();
    }


    public function getChildByType($params)
    {
        $type_list = $this->getProductType($params);
        $type_info = $this->getOne($params);
        $child_list = Help::toTree($type_list,  $type_info['ota_product_type_id'], 0, 'ota_product_type_id');

        return $child_list;

    }


}