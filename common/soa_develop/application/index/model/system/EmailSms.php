<?php

namespace app\index\model\system;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class EmailSms extends Model{
  
    protected $table = 'email_sms';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加邮箱验证码
     * 韩
     */
    public function addEmailSms($params){
        $t = time();
		
        $data['user_id'] = $params['now_user_id'];
        $data['code'] = $params['code'];
        $data['email'] = $params['email'];
        $data['type'] = $params['type'];
        $data['create_time'] = $t;

        $data['status'] = 1;


        $this->startTrans();
        try{
            $result = $this->insertGetId($data);


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
     * 获取税点模板
     * 韩
     */
    public function getEmailSms($params,$is_count=false,$is_page=false,$page=null,$page_size=20){//第一个为参数，第二个为是否要获取 总数


        $data = "1=1 ";
        if(!empty($params['user_id'])){
            $data.= " and user_id= ".$params['user_id'];
        }

        if(!empty($params['status'])){
        	$data.= " and status= ".$params['status'];
        }
        if(!empty($params['email'])){
            $data.= " and email = '".$params['email']."'";
        }


        if($is_count==true){
            $result = $this->where($data)->count();

        }else{
            if($is_page == true){
                $result = $this->table("email_sms")->
                where($data)->limit($page, $page_size)->
				order("create_time desc")->select();


            }else{
                $result = $this->table("email_sms")->

                where($data)->order("create_time desc")->select();
            }

        }


        return $result;

    }


    /**
     * 修改税点模板 根据tax_id
     * 韩
     */
    public function updateEmailSms($params){



        if(is_numeric($params['status'])){
            $data['status'] = $params['status'];

        }






        $this->startTrans();
        try{
            $this->where("email_sms_id = ".$params['email_sms_id'])->update($data);

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