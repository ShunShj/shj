<?php
namespace app\index\model\examine_and_approve;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class ExamineAndApprove extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'approval_type';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

    /**
    * 添加审批内容
    */
    public function addExamineAndApprove($params){
        try{
            $d['pk_id'] = $params['pk_id'];
            $d['company_id'] = $params['company_id'];
            $d['approval_type_id'] = $params['approval_type_id'];
            $d['examination_and_approval_content'] = $params['examination_and_approval_content'];
            $d['remarks'] = $params['remarks'];
            $d['create_time'] = time();
            $d['update_time'] = time();
            $d['create_user_id'] = $params['user_id'];
            // $d['update_user_id'] = $params['user_id'];
            $d['status'] = $params['status'];

            Db::table('examine_and_approve')->insert($d);
            $examine_and_approve_id = Db::name('examine_and_approve')->getLastInsID(); 

            return $examine_and_approve_id;
        }catch (\Exception $e) { 
            return $result = $e->getMessage(); 
        }  
    }

    /**
    * 修改审批状态
    **/
    public function upExamineAndApproveStatus($params){
        try{
           $where['examine_and_approve_id'] = $params['examine_and_approve_id'];
           $up['approval_opinion'] = $params['approval_opinion'];
           $up['status'] = $params['status'];
           $up['update_user_id'] = $params['user_id'];
           $up['update_time'] = time();
           Db::table('examine_and_approve')->where($where)->update($up); 
           return true;
        }catch (\Exception $e) { 
            return $result = $e->getMessage(); 
        } 
    }

    /**
    * 获取审批内容
    **/
    public function selExamineAndApprove($params){
        if($params['examine_and_approve_id']){
            $where['examine_and_approve_id'] = $params['examine_and_approve_id'];   
        }
        if($params['company_id']){
            $where['company_id'] = $params['company_id'];   
        }
        if($params['approval_type_id']){
            $where['approval_type_id'] = $params['approval_type_id'];   
        }
        if($params['examine_and_approve_id']){
            $where['examine_and_approve_id'] = $params['examine_and_approve_id'];   
        }
        if($params['nickname']){
            $w = "nickname like '%{$params['nickname']}%' and status=1";
            $user_id_ar = $this->table('user')->field(['user_id'])->where($w)->select();
            $user_id_ay = [];
            foreach ($user_id_ar as $key => $value) {
                $user_id_ay[] = $value['user_id'];
            }
            $where['create_user_id'] = ['in',$user_id_ay]; 
        }
        if($params['status']){
            $where['status'] = $params['status'];   
        }
        if($params['pk_id']){
            $where['pk_id'] = $params['pk_id'];
        }

        if($params['role_id']){
            $ww['role_id'] = $params['role_id'];
            $ww['status'] = 1;
            $approval_type_id_ar = $this->table('limits_for_examinity_and_approving_authority')->field(['approval_type_id'])->where($ww)->select();
            $approval_type_id_ay = [];
            foreach ($approval_type_id_ar as $key => $value) {
                $approval_type_id_ay[] = $value['approval_type_id'];
            }
            $where['approval_type_id'] = ['in',$approval_type_id_ay];

            if($params['approval_type_id']){
                if(in_array($params['approval_type_id'], $approval_type_id_ay)){
                    $where['approval_type_id'] = $params['approval_type_id']; 
                }else{
                    $where['approval_type_id']=0;
                } 
            }

        }


        return $this->table('examine_and_approve')->field([
            'examine_and_approve_id','company_id','approval_type_id','examination_and_approval_content','remarks','approval_opinion','pk_id',
            'create_time','update_time','create_user_id','update_user_id','status',
            '(select company.company_name from company where company.company_id=examine_and_approve.company_id) as company_name',
            '(select approval_type.apellation from approval_type where approval_type.approval_type_id=examine_and_approve.approval_type_id)  as apellation',
            '(select user.nickname from user where user.user_id=examine_and_approve.create_user_id) as create_nickname',
            '(select user.nickname from user where user.user_id=examine_and_approve.update_user_id) as update_nickname',
        ])->where($where)->order('examine_and_approve.create_time desc')->select();

    }

    /**
    * 我发起的
    */
    public function iLaunched($params){
        $where['create_user_id'] =  $params['create_user_id']; 
        if($params['status']){
            $where['status'] = $params['status'];
        }
        if($params['approval_type_id']){
            $where['approval_type_id'] = $params['approval_type_id'];
        }
        if($params['approver']){
            $w = "nickname like '%{$params['approver']}%' and status=1";
            $user_id_ar = $this->table('user')->field(['user_id'])->where($w)->select();
            $user_id_ay = [];
            foreach ($user_id_ar as $key => $value) {
                $user_id_ay[] = $value['user_id'];
            }
            $where['update_user_id'] = ['in',$user_id_ay]; 
        }

        return $this->table('examine_and_approve')->field([
            'examine_and_approve_id','company_id','approval_type_id','examination_and_approval_content','remarks','approval_opinion','pk_id',
            'create_time','update_time','create_user_id','update_user_id','status',
            '(select company.company_name from company where company.company_id=examine_and_approve.company_id) as company_name',
            '(select approval_type.apellation from approval_type where approval_type.approval_type_id=examine_and_approve.approval_type_id)  as apellation',
            '(select user.nickname from user where user.user_id=examine_and_approve.create_user_id) as create_nickname',
            '(select user.nickname from user where user.user_id=examine_and_approve.update_user_id) as update_nickname',
        ])->where($where)->order('examine_and_approve.create_time desc')->select();

    }

    /**
    * 我审核的
    */
    public function iChecked($params){
        $where['update_user_id'] =  $params['update_user_id']; 
        $where['status'] = ['between','2,3'];

        if($params['status']){
            $where['status'] = $params['status'];
        }
        if($params['approval_type_id']){
            $where['approval_type_id'] = $params['approval_type_id'];
        }
        if($params['nickname']){
            $w = "nickname like '%{$params['nickname']}%' and status=1";
            $user_id_ar = $this->table('user')->field(['user_id'])->where($w)->select();
            $user_id_ay = [];
            foreach ($user_id_ar as $key => $value) {
                $user_id_ay[] = $value['user_id'];
            }
            $where['create_user_id'] = ['in',$user_id_ay]; 
        } 

        return $this->table('examine_and_approve')->field([
            'examine_and_approve_id','company_id','approval_type_id','examination_and_approval_content','remarks','approval_opinion','pk_id',
            'create_time','update_time','create_user_id','update_user_id','status',
            '(select company.company_name from company where company.company_id=examine_and_approve.company_id) as company_name',
            '(select approval_type.apellation from approval_type where approval_type.approval_type_id=examine_and_approve.approval_type_id)  as apellation',
            '(select user.nickname from user where user.user_id=examine_and_approve.create_user_id) as create_nickname',
            '(select user.nickname from user where user.user_id=examine_and_approve.update_user_id) as update_nickname',
        ])->where($where)->order('examine_and_approve.create_time desc')->select();

    }


}