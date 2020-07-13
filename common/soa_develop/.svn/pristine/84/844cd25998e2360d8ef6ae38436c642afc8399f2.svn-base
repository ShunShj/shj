<?php

namespace app\index\model\system;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class Job extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'job';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加职位
     * 胡
     */
    public function addJob($params){
        $t = time();


        $data['job_name'] = $params['job_name'];
        $data['department_id'] = $params['department_id'];
        $data['company_id'] = $params['choose_company_id'];
        $data['create_time'] = $t;
        $data['create_user_id'] = $params['user_id'];
        $data['status'] = 1;








        Db::startTrans();
        try{
            Db::name('job')->insert($data);

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
     * 获取职位
     * 胡
     */
    public function getJob($params,$is_count=false){//第一个为参数，第二个为是否要获取 总数


        $data = "1=1 ";
        if(!empty($params['job_id'])){
            $data.= " and job.job_id= ".$params['job_id'];
        }
        if(!empty($params['status'])){
            $data.= " and job.status = ".$params['status'];
        }
        if(!empty($params['job_name'])){
            $data.= " and job.job_name like'%".$params['job_name']."%'";
        }
        if(!empty($params['department_id'])){
            $data.= " and job.department_id = '".$params['department_id']."'";
        }
        if(is_numeric($params['company_id'])){
            $data.= " and job.company_id = '".$params['company_id']."'";
        }
        if($is_count==true){
       
            $result = $this->where($data)->count();

        }else{
     
        	if(isset($params['page'])){
        		
                $result = $this->table("job")->alias('job')->
                join("department dept","job.department_id= dept.department_id",'left')->
                join('company','company.company_id = job.company_id','left')->
                where($data)->order('create_time desc')->limit($params['page'],$params['page_size'])->

                field(['job.job_id',"job.job_name","job.department_id",'dept.department_name','company.company_name','job.company_id',
                    "(select nickname  from user where user.user_id = job.create_user_id)"=>'create_user_name',
                    "(select nickname  from user where user.user_id = job.update_user_id)"=>'update_user_name',
                    'job.update_time','job.create_time',"job.status"])->order("create_time desc")->select();
          

            }else{
                $result = $this->table("job")->alias('job')->
                join("department dept","job.department_id= dept.department_id",'left')->
                join('company','company.company_id = job.company_id')->
                where($data)->

                field(['job.job_id',"job.job_name","job.department_id",'dept.department_name','company.company_name','job.company_id',
                    "(select nickname  from user where user.user_id = job.create_user_id)"=>'create_user_name',
                    "(select nickname  from user where user.user_id = job.update_user_id)"=>'update_user_name',
                    'job.update_time','job.create_time',"job.status"])->order("create_time desc")->select();


            }

        }




        return $result;

    }


    /**
     * 修改部门 根据job_id
     */
    public function updateJobByJobId($params){

        $t = time();


        if(!empty($params['job_name'])){
            $data['job_name'] = $params['job_name'];

        }

        if(!empty($params['department_id'])){
            $data['department_id'] = $params['department_id'];


        }
        if(!empty($params['status'])){
            $data['status'] = $params['status'];

        }
        if(isset($params['choose_company_id'])){
            $data['company_id'] = $params['choose_company_id'];
        }

        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;

        $data_en_us['update_user_id'] = $params['user_id'];
        $data_en_us['update_time'] = $t;



        Db::startTrans();
        try{
            Db::name('job')->where("job_id = ".$params['job_id'])->update($data);

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