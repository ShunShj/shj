<?php
namespace app\index\service;


use app\common\help\Help;
use app\index\model\ota_member\OtaMember;

class OtaMemberService{

    /**
     * 获取用户member列表
     * @param $page int 页数
     * @param $per int 条数
     * @return
     */
    public function getMemberList($post_data)
    {
        $ota_member_model = new OtaMember();

        $where = " 1=1 ";

        if(is_numeric($post_data['status'])){
            $where .= " and m.status = ". $post_data['status'];
        }

        if (isset($post_data['is_like']) && $post_data['is_like'] == 1)
        {
            //开启模糊搜索

            //用户名
            if($post_data['username'])
            {
                $where .= " and m.username like '%". trim($post_data['username']) ."%'";
            }

            //姓名
            if($post_data['nickname'])
            {
                $where .= " and m.nickname like '%". trim($post_data['nickname']) ."%'";
            }

            //手机号
            if($post_data['email'])
            {
                $where .= " and m.email like '%". trim($post_data['email']) ."%'";
            }
        }
        else
        {
            //不开启模糊搜索

            //用户名
            if($post_data['username'])
            {
                $where .= " and m.username = '". trim($post_data['username']). "'";
            }

            //姓名
            if($post_data['nickname'])
            {
                $where .= " and m.nickname = ". trim($post_data['nickname']);
            }

            //手机号
            if($post_data['phone'])
            {
                $where .= " and m.email = ". trim($post_data['email']);
            }
        }

        //网站uuid
        if($post_data['website_uuid'])
        {
            $where .= " and m.website_uuid  = '". $post_data['website_uuid'] . "'";
        }

        //公司
        /*if($post_data['company_id'])
        {
            $where .= " and m.company_id  = ". $post_data['company_id'];
        }*/

        //判断是否分页
        if (isset($post_data['page']) && isset($post_data['size']))
        {
            //总数
            $count = $ota_member_model->alias('m')->where($where)->count();

            $list = $ota_member_model->alias('m')
                ->where($where)->limit(($post_data['page']-1)*$post_data['size'], $post_data['size'])->select();

            $result = [
                'count' => $count,
                'list' => $list,
                'page_count' => ceil($count / $post_data['size'])
            ];
        }
        else
        {
            $result['list'] = $ota_member_model->alias('m')
                ->where($where)->select();
        }

        return $result;
    }


    /**
     * 修改账户
     * @param $post_data
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function editOtaMember($post_data){

        if(!empty($post_data['password'])){
            $data['password'] = md5($post_data['password']);

        }

        if(!empty($post_data['nickname'])){
            $data['nickname'] = $post_data['nickname'];

        }

        if(!empty($post_data['gender'])){
            $data['gender'] = $post_data['gender'];

        }

        if(!empty($post_data['email'])){
            $data['email'] = $post_data['email'];

        }

        if(is_numeric($post_data['status'])){
            $data['status'] = $post_data['status'];
        }

        if(!empty($post_data['company_id'])){
            $data['company_id'] = $post_data['company_id'];

        }

        if (!empty($post_data['avatar']))
        {
            $data['avatar'] = $post_data['avatar'];
        }

        $ota_member_model = new OtaMember();

        $ota_member_model->startTrans();
        try{

            if (empty($post_data['uuid']))
            {
                //添加
                if(!empty($post_data['username'])){
                    $data['username'] = $post_data['username'];
                }

                if(!empty($post_data['website_uuid'])){
                    $data['website_uuid'] = $post_data['website_uuid'];
                }

                $info = $ota_member_model->where(['username' => $data['username']])->find();

                if ($info)
                {
                    \think\Response::create(['code' => '400', 'msg' => 'username repeat'], 'json')->send();
                    exit();
                }

                $data['create_time'] = time();
                $data['status'] = 1;
                $data['uuid'] = Help::getUuid();

                $ota_member_model->addOtaMember($data);
            }
            else
            {
                //修改
                $where = [];

                if(!empty($post_data['username'])){
                    $where['username'] = $post_data['username'];
                }

                if(!empty($post_data['member_id'])){
                    $where['member_id'] = $post_data['member_id'];
                }

                if(!empty($post_data['uuid'])){
                    $where['uuid'] = $post_data['uuid'];
                }

                if(!empty($post_data['website_uuid'])){
                    $where['website_uuid'] = $post_data['website_uuid'];
                }

                $member_info = $ota_member_model->where(['uuid' => $where['uuid']])->find();

                if (!$member_info)
                {
                    \think\Response::create(['code' => '400', 'msg' => 'no member'], 'json')->send();
                    exit();
                }

                if (!empty($post_data['old_password']))
                {
                    if ($member_info['password'] != md5($post_data['old_password']))
                    {
                        \think\Response::create(['code' => '400', 'msg' => 'password error'], 'json')->send();
                        exit();
                    }
                }

                $data['update_time'] = time();

                $ota_member_model->editOtaMember($data, $where);
            }


            // 提交事务
            $ota_member_model->commit();

            return 1;

        } catch (\Exception $e) {
            // 回滚事务
            $ota_member_model->rollback();

            return false;
        }

    }
}