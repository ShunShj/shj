<?php
/** 审批
 * Created by PhpStorm.
 * User: jiye
 * Date: 2019/2/21
 * Time: 10:33
 */
namespace app\index\controller;

use think\helper\Arr;
use \Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;

class ExamineAndApprove extends Base
{

    public  $approval_status = [1=>'待审批',2=>'审批通过',3=>'审批不通过'];

    //编辑角色审批权限 Hugh
    public function editExamineAndApprove(){
        $role_id = input('get.role_id');
        //获取角色名
        $where['role_id'] = $role_id;
        $role =$this->callSoaErp('post','/system/getRole',$where);
        $this->assign('role',$role['data'][0]);
        unset($where);
        //获取审批类型
        $where['status'] = 1;
        $obtain_approval_type =  $this->callSoaErp('post','/examine_and_approve/obtain_approval_type',$where);
        $obtain_approval_type_g = Arrays::group($obtain_approval_type['data'],function($ar){
            return $ar['pid'].'-'.$ar['level'];
        });
        $this->assign('obtain_approval_type_g',$obtain_approval_type_g);
        unset($where);
        //获取角色审批权限
        $where['status'] = 1;
        $where['role_id'] = $role_id;
        $limits_for_examinity_and_approving_authority = $this->callSoaErp('post','/examine_and_approve/sel_limits_for_examinity_and_approving_authority',$where);
        $this->assign('$limits_for_examinity_and_approving_authority',$limits_for_examinity_and_approving_authority['data']);

        $limits_for_examinity_and_approving_authority_g = Arrays::group($limits_for_examinity_and_approving_authority['data'],'approval_type_id');
        $approval_type_id_ar =  Arrays::keys($limits_for_examinity_and_approving_authority_g);
        $this->assign('approval_type_id_ar',$approval_type_id_ar);

//       echo '<pre>';print_r(Arrays::keys($limits_for_examinity_and_approving_authority_g));exit;
        return $this->fetch('/examineandapprove/edit_examine_and_approve');
    }

    //保存角色审批权限
    public function editExamineAndApproveAjax(){
        $param = Request::instance()->param();

        $ar['role_id'] = Arrays::get($param,'role_id');
        $ar['approval_type_id'] = Arrays::get($param,'types')?:[];
        return $this->callSoaErp('post','/examine_and_approve/up_limits_for_examinity_and_approving_authority',$ar);

    }

    //待我审批
    public function toBeApprovedByMe(){
        $param = Request::instance()->param();
        $search_approval_type_id = Arrays::get($param,'search_approval_type_id');
        $search_user = Arrays::get($param,'search_user');

        //获取审批类型
        $where['level'] = 3;
        $where['status'] = 1;
        $obtain_approval_type_list = $this->callSoaErp('post','/examine_and_approve/obtain_approval_type',$where);
        $this->assign('obtain_approval_type_list',$obtain_approval_type_list['data']);
        unset($where);

        if($search_approval_type_id){
            $where['approval_type_id'] = $search_approval_type_id;
        }
        if($search_user){
            $where['nickname'] = $search_user;
        }

        $where['company_id'] = session('user')['company_id'];
        $where['role_id'] = session('user')['role_id'];
        $where['status'] =  1;
        $ExamineAndApprove = $this->callSoaErp('post','/examine_and_approve/selExamineAndApprove/',$where);
        //    var_dump($ExamineAndApprove);exit;
        $this->assign('ExamineAndApprove',$ExamineAndApprove['data']);
        unset($where);

        //我发起的
        $where['create_user_id'] = session('user')['user_id'];
        $iLaunched = $this->callSoaErp('post','/examine_and_approve/iLaunched/',$where);
        $this->assign('iLaunched',$iLaunched['data']);
        unset($where);

        //我审核的
        $where['update_user_id'] = session('user')['user_id'];
        $iChecked = $this->callSoaErp('post','/examine_and_approve/iChecked/',$where);
        $this->assign('iChecked',$iChecked['data']);


        return $this->fetch('/examineandapprove/to_be_approved_by_me');
    }

    //我发起的
    public function iLaunched(){
        $param = Request::instance()->param();
        $search_status = Arrays::get($param,'search_status');
        $search_approval_type_id = Arrays::get($param,'search_approval_type_id');
        $search_user = Arrays::get($param,'search_user');

        //我发起的
        if($search_status){
            $where['status'] = $search_status;
        }
        if($search_approval_type_id){
            $where['approval_type_id'] = $search_approval_type_id;
        }
        if($search_user){
            $where['approver'] = $search_user;
        }
        $where['create_user_id'] = session('user')['user_id'];
        $iLaunched = $this->callSoaErp('post','/examine_and_approve/iLaunched/',$where);
        $this->assign('iLaunched',$iLaunched['data']);
        unset($where);

        //待审批
        $where['company_id'] = session('user')['company_id'];
        $where['role_id'] = session('user')['role_id'];
        $where['status'] =  1;
        $ExamineAndApprove = $this->callSoaErp('post','/examine_and_approve/selExamineAndApprove/',$where);
        $this->assign('ExamineAndApprove',$ExamineAndApprove['data']);
        unset($where);

        //我审核的
        $where['update_user_id'] = session('user')['user_id'];
        $iChecked = $this->callSoaErp('post','/examine_and_approve/iChecked/',$where);
        $this->assign('iChecked',$iChecked['data']);

        //获取审批类型
        $where['level'] = 3;
        $where['status'] = 1;
        $obtain_approval_type_list = $this->callSoaErp('post','/examine_and_approve/obtain_approval_type',$where);
        $this->assign('obtain_approval_type_list',$obtain_approval_type_list['data']);
        unset($where);

        //审批状态
        $this->assign('approval_status',$this->approval_status);
        return $this->fetch('/examineandapprove/i_launched');

    }

    //已审批
    public function iChecked(){
        $param = Request::instance()->param();
        $search_approval_type_id = Arrays::get($param,'search_approval_type_id');
        $search_user = Arrays::get($param,'search_user');

        //已审批
        if($search_approval_type_id){
            $where['approval_type_id'] = $search_approval_type_id;
        }
        if($search_user){
            $where['nickname'] = $search_user;
        }

        $where['update_user_id'] = session('user')['user_id'];
        $iChecked = $this->callSoaErp('post','/examine_and_approve/iChecked',$where);
        $this->assign('iChecked',$iChecked['data']);

        //待审批
        $where['company_id'] = session('user')['company_id'];
        $where['role_id'] = session('user')['role_id'];
        $where['status'] =  1;
        $ExamineAndApprove = $this->callSoaErp('post','/examine_and_approve/selExamineAndApprove/',$where);
        $this->assign('ExamineAndApprove',$ExamineAndApprove['data']);
        unset($where);

        //我发起的
        $where['create_user_id'] = session('user')['user_id'];
        $iLaunched = $this->callSoaErp('post','/examine_and_approve/iLaunched/',$where);
        $this->assign('iLaunched',$iLaunched['data']);
        unset($where);


        //审批状态
        $this->assign('approval_status',$this->approval_status);
        //获取审批类型
        $where['level'] = 3;
        $where['status'] = 1;
        $obtain_approval_type_list = $this->callSoaErp('post','/examine_and_approve/obtain_approval_type',$where);
        $this->assign('obtain_approval_type_list',$obtain_approval_type_list['data']);
        unset($where);
        return $this->fetch('/examineandapprove/i_checked');
    }

    //审批
    public function examineAndApproveAjax(){
        $param = Request::instance()->param();
        $status =  Arrays::get($param,'status');
        //获取审批内容
        $where['examine_and_approve_id'] = Arrays::get($param,'examine_and_approve_id');
        $ExamineAndApprove = $this->callSoaErp('post','/examine_and_approve/selExamineAndApprove/',$where);
        $data = $ExamineAndApprove['data'][0];

        if($data['approval_type_id'] == 12 && $status==2){ // 12 创建团队产品 - 通过
            $this->addTeamProduct($data['pk_id'],$data['examination_and_approval_content']);
        }
        if($data['approval_type_id'] == 13 && $status==2){ // 13 变更团队产品 - 通过
            $this->upTeamProduct($data['pk_id'],$data['examination_and_approval_content']);
        }
        //12创建 13变更团队产品 不予通过
        if(($data['approval_type_id'] == 13 || $data['approval_type_id'] == 12) && $status==3){
            $this->TeamProductApprovalFailed($data['approval_type_id'],$data['pk_id'],$data['examination_and_approval_content']);
        }


        $w['examine_and_approve_id'] = $data['examine_and_approve_id'];
        $w['approval_opinion'] = Arrays::get($param,'approval_opinion');
        $w['status'] = $status;
        $w['user_id'] = session('user')['user_id'];
        return $this->callSoaErp('post','/examine_and_approve/upExamineAndApproveStatus/',$w);

    }

    //团队审批失败
    public function TeamProductApprovalFailed($approval_type_id,$team_product_id,$json){
        $d = json_decode($json,true);
        $d['team_product_id'] = $team_product_id;
        if($approval_type_id==12){
            $d['status'] = -1;
        }
        if($approval_type_id==13){
            $d['status'] = $d['old_status'];
        }

        $this->callSoaErp('post','/product/updateTeamProductBaseByTeamProductBaseId',$d);

    }

    //添加团队产品（审批通过）
    public function addTeamProduct($team_product_id,$json){
        $d = json_decode($json,true);
        $d['team_product_id'] = $team_product_id;
        $this->callSoaErp('post','/product/updateTeamProductBaseByTeamProductBaseId',$d);
    }


    //修改团队产品 （审批通过）
    public function upTeamProduct($team_product_id,$json){
        $d = json_decode($json,true);
        $d['team_product_id'] = $team_product_id;
        $this->callSoaErp('post','/product/updateTeamProductByTeamProductId',$d);
    }
	
    /*
     * 财务待我审批
     */
    public function financeApproveByMe(){
    	//首先必须是财务角色
    	
    	$param = Request::instance()->param();
    	$data['company_id'] = session('user')['company_id'];
    	$data['status'] = 1;
    	$data['is_like'] = 1;
    	if(!empty($param['create_user_name'])){
    		$data['create_user_name'] = $param['create_user_name'];
    	}
    	
    	if(!empty($param['team_product_number'])){
    		$data['team_product_number'] = $param['team_product_number'];
    	}   	
    	if(is_numeric($param['finance_type'])){
    		$data['finance_type'] = $param['finance_type'];
    	}   	
    	if(!empty($param['create_time'])){
    		$data['create_time'] = $param['create_time'];
    	}
    	if(!empty($param['company_order_number'])){
    		$data['company_order_number'] = $param['company_order_number'];
    	}   
 		
    	if(session('user')['role_id']==5 || session('user')['role_id']==6 || session('user')['role_id']==1){
	    	$data['page']=$this->page();
	    	$data['page_size']=$this->_page_size;
	    	$result = $this->callSoaErp('post','/finance/getFinanceApprove',$data);
	    	
	    	
	    	$this->getPageParams($result);
    	}
    	
		
    	return $this->fetch('/examineandapprove/finance_approce_by_me');
    }
    /*
     * 我发起的
     */
    public function iPostFinanceApprove(){
    	//首先必须是财务角色
    	
    	$param = Request::instance()->param();
    	$data['is_like'] = 1;
    	if(!empty($param['create_time'])){
    		$data['create_time'] = $param['create_time'];
    	}
		if(!empty($param['status'])){
			if($param['status'] == 1){
				$data['status'] = 1;
			}else if($param['status'] == 2){
				$data['approve_result'] = 1;
				
			}else if($param['status'] == 3){
				$data['approve_result'] = 2;
				
			}
			
    		
    	}
		
    	if(!empty($param['company_order_number'])){
    		$data['company_order_number'] = $param['company_order_number'];
    	}
    	if(!empty($param['create_user_name'])){
    		$data['create_user_name'] = $param['create_user_name'];
    	}
    	 
    	if(!empty($param['team_product_number'])){
    		$data['team_product_number'] = $param['team_product_number'];
    	}
    	if(is_numeric($param['finance_type'])){
    		$data['finance_type'] = $param['finance_type'];
    	}
    	if(session('user')['company_id']==1 && (session('user')['role_id']==5 || session('user')['role_id']==1)){
    		
    		
    	}else{
    		$data['company_id'] = session('user')['company_id'];
    		$data['create_user_id'] =  session('user')['user_id'];
    	}
    	
	    	$data['page']=$this->page();
	    	$data['page_size']=$this->_page_size;
	    	$result = $this->callSoaErp('post','/finance/getFinanceApprove',$data);
	    	$this->getPageParams($result);
    
    	 
    	 
    	$this->assign('result',$result);
    	return $this->fetch('/examineandapprove/i_post_finance_approve');
    }
    /*
     * 审批结束
     */
    public function financeApproveOver(){
    
    	//首先必须是财务角色
    	$param = Request::instance()->param();
    	$data['is_like'] = 1;
    	$data['company_id'] = session('user')['company_id'];
    	$data['status'] =  2;
    	if(!empty($param['create_time'])){
    		$data['create_time'] = $param['create_time'];
    	}
    	if(!empty($param['company_order_number'])){
    		$data['company_order_number'] = $param['company_order_number'];
    	}
    	if(!empty($param['approve_time'])){
    		$data['approve_time'] = $param['approve_time'];
    	}
    	if(!empty($param['approve_result'])){
    		$data['approve_result'] = $param['approve_result'];
    	}
    	if(!empty($param['create_user_name'])){
    		$data['create_user_name'] = $param['create_user_name'];
    	}
    	 
    	if(!empty($param['team_product_number'])){
    		$data['team_product_number'] = $param['team_product_number'];
    	}
    	if(is_numeric($param['finance_type'])){
    		$data['finance_type'] = $param['finance_type'];
    	}
    	if(session('user')['role_id']==5 || session('user')['role_id']==1 || session('user')['role_id']==6){

    		
    		
    		
    		$this->assign('result',$result);
    		
    	}else{
    		$data['create_user_id'] =  session('user')['user_id'];
    	

    	}

    	$data['page']=$this->page();
    	$data['page_size']=$this->_page_size;
    	$result = $this->callSoaErp('post','/finance/getFinanceApprove',$data);
    	
    	$this->getPageParams($result);
    	
    	
    	
    	
    	return $this->fetch('/examineandapprove/finance_approve_over');
    } 
    
    /*
     * 结算单待我审批
     */
    public function statementApproveByMe(){
    	//首先必须是财务角色
    	 
    	$param = Request::instance()->param();
    	$data['company_id'] = session('user')['company_id'];
    	$data['status'] = 1;
    	if(!empty($param['create_time'])){
    		$data['create_time'] = $param['create_time'];
    	}

    
    	//if(session('user')['role_id']==5 || session('user')['role_id']==1){
    		$result = $this->callSoaErp('post','/product/getStatementApprove',$data);
    		$result = $result['data'];
    
    		 
    		 
    		$this->assign('result',$result);
    	//}
    	 
    
    	return $this->fetch('/examineandapprove/statement_approce_by_me');
    }
    /*
     * 往来账待我审批
     */
    public function companyFinanceApproveByMe(){
    	//首先必须是财务角色
    	 
    	$param = Request::instance()->param();
    	$data['company_id'] = session('user')['company_id'];
    	$data['status'] = 1;
    	if(!empty($param['create_time'])){
    		$data['create_time'] = $param['create_time'];
    	}
    	if(!empty($param['company_order_number'])){
    		$data['company_order_number'] = $param['company_order_number'];
    	}
    	if(session('user')['company_id']==1){
    		if(session('user')['role_id']==5 || session('user')['role_id']==1){
    			$result = $this->callSoaErp('post','/finance/getCompanyFinanceApprove',$data);
		    	$data['page']=$this->page();
		    	$data['page_size']=$this->_page_size;
		    	$result = $this->callSoaErp('post','/finance/getCompanyFinanceApprove',$data);
		    	
		    	$this->getPageParams($result);
    		}
    	}

    	return $this->fetch('/examineandapprove/company_finance_approve_by_me');
    }  
    /*
     * 往来账我发起的
     */
    public function iPostCompanyFinanceApprove(){
    	//首先必须是财务角色
    
    	$param = Request::instance()->param();
    	$data['company_id'] = session('user')['company_id'];
    	
    	if(!empty($param['create_time'])){
    		$data['create_time'] = $param['create_time'];
    	}
    	if(!empty($param['company_order_number'])){
    		$data['company_order_number'] = $param['company_order_number'];
    	}
    	if(session('user')['company_id']==1 &&  session('user')['role_id']==1){
    	
    	
    	}else{
    		$data['company_id'] = session('user')['company_id'];
    		$data['create_user_id'] =  session('user')['user_id'];
    	}
    	$data['page']=$this->page();
    	$data['page_size']=$this->_page_size;
    	$result = $this->callSoaErp('post','/finance/getCompanyFinanceApprove',$data);
    	
    	$this->getPageParams($result);
    		
    
    
    	return $this->fetch('/examineandapprove/i_post_company_finance_approve');
    }
    /*
     * 往来账已审批Approve
     */
    public function companyFinanceApproveOver(){
    	//首先必须是财务角色
    
    	$param = Request::instance()->param();
    	$data['company_id'] = session('user')['company_id'];
    	$data['status'] = 2;
    	if(!empty($param['create_time'])){
    		$data['create_time'] = $param['create_time'];
    	}
    	if(!empty($param['company_order_number'])){
    		$data['company_order_number'] = $param['company_order_number'];
    	}
        if(session('user')['role_id']==5 || session('user')['role_id']==1){
    	
   
    		
    	}else{
    		$data['create_user_id'] =  session('user')['user_id'];
    		

    	}
    	$data['page']=$this->page();
    	$data['page_size']=$this->_page_size;
    	$result = $this->callSoaErp('post','/finance/getCompanyFinanceApprove',$data);
    	
    	$this->getPageParams($result);

    	
    	return $this->fetch('/examineandapprove/company_finance_approve_over');
    }    
}