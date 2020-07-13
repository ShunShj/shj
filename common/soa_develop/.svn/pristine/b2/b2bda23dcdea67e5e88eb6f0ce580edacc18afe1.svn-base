<?php
namespace app\index\model\examine_and_approve;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class LimitsForExaminityAndApprovingAuthority extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'limits_for_examinity_and_approving_authority';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

    /**
    * 编辑角色审批权限
    */
	public function up_limits_for_examinity_and_approving_authority($params){
        $this->startTrans();
        try{
            $where['role_id'] = $params['role_id'];    
            $this->table('limits_for_examinity_and_approving_authority')->where($where)->update(['status'=>2]);

            $approval_type_id = $params['approval_type_id'];
            foreach ($approval_type_id as $key => $value) {
                 $d['approval_type_id'] = $value;
                 $d['status'] = 1;
                 $d['role_id'] = $params['role_id'];
                 $this->table('limits_for_examinity_and_approving_authority')->insert($d);
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            return $result = $e->getMessage(); 
        } 

    } 

    /**
    * 获取角色审批权限
    */
    public function sel_limits_for_examinity_and_approving_authority($params){
        if($params['role_id']){
            $where['role_id'] = $params['role_id'];
        }
        if($params['status']){
            $where['status'] = $params['status'];
        }
        if($params['limits_for_examinity_and_approving_authority_id']){
            $where['limits_for_examinity_and_approving_authority_id'] = $params['limits_for_examinity_and_approving_authority_id'];
        } 
        if($params['approval_type_id']){
            $where['approval_type_id'] = $params['approval_type_id'];
        }

        return $this->table('limits_for_examinity_and_approving_authority')->field([
            'limits_for_examinity_and_approving_authority_id',
            'approval_type_id','role_id','status',
            '(select role.role_name from role where role.role_id=limits_for_examinity_and_approving_authority.role_id) as role_name',
            '(select approval_type.apellation from approval_type where approval_type.approval_type_id=limits_for_examinity_and_approving_authority.approval_type_id) as apellation',
        ])->where($where)->select();


    }

}