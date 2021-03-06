<?php
/**
 * Created by PhpStorm.
 * User: jiye
 * Date: 2018/8/13
 * Time: 9:24
 */
namespace app\index\controller;

use app\common\help\Help;
use think\helper\Arr;
use \Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;

/***
* 待办模板设置
 * Hugh
 **/
class Operations extends Base
{
    public $remindDay = [1=>'index_operations_addOperations_order_date_after',2=>'index_operations_addOperations_departure_date_before',3=>'index_operations_addOperations_departure_date_after'];

    //待办模板类型
    public function types(){
        $where['company_id'] = session('user')['company_id'];
        $OperationsType = $this->callSoaErp('post','/operations/selOperationsType',$where);
        $this->assign('OperationsType',$OperationsType['data']);

        return $this->fetch('operations_types');
    }

    //添加待办类型
    public function addOperationsTypes(){
        return $this->fetch('operations_types_add');
    }

    //修改待办类型
    public function upOperationsTypes(){
        $where['id'] = input('get.id');
        $OperationsType = $this->callSoaErp('post','/operations/selOperationsType',$where);
//        var_dump($OperationsType);exit;
        $this->assign('OperationsType',$OperationsType['data'][0]);

        return $this->fetch('operations_types_update');
    }

    //执行修改待办类型
    public function upOperationsTypesAjax(){
        $post = Request::instance()->param();
        $d['id'] = Arrays::get($post,'id');
        $d['company_id'] = session('user')['company_id'];
        $d['name'] = Arrays::get($post,'name');
        $d['status'] = Arrays::get($post,'status');

        return $this->callSoaErp('post','/operations/upOperationsType',$d);

    }


    //执行添加待办类型
    public function addOperationsTypeAjax(){
        $post = Request::instance()->param();

        $d['company_id'] = session('user')['company_id'];
        $d['name'] = Arrays::get($post,'name');
        $d['status'] = Arrays::get($post,'status');

        return $this->callSoaErp('post','/operations/addOperationsType',$d);

    }


    //待办设置列表
    public function index(){
        $w['company_id'] = session('user')['company_id'];
        $w['status'] = 1;
        $operations_type_id = input('operations_type_id');
        if(is_numeric($operations_type_id)){
            $w['operations_type_id'] = $operations_type_id;
        }

        $operationsList = $this->callSoaErp('post','/operations/selOperations',$w);
        $this->assign('operationsList',$operationsList['data']);
        unset($w);

        //获取邮件模板
        $w['status'] = 1;
        $w['company_id'] = session('user')['company_id'];
        $OperationsEmail = $this->callSoaErp('post','/operations/selOperationsEmail',$w);

//        $OperationsEmailGr = Arrays::group($OperationsEmail['data'],'operation_id');
//        var_dump($OperationsEmailGr);exit;
        $this->assign('OperationsEmail',$OperationsEmail['data']);
        $this->assign('remindDay',$this->remindDay);
        return $this->fetch('operations_index');
    }

    //添加待办模板
    public function addOperations(){
        $this->assign('remindDay',$this->remindDay);

        //获取模板类型
        $w['company_id'] = session('user')['company_id'];
        $w['status'] = 1;
        $OperationsType = $this->callSoaErp('post','/operations/selOperationsType',$w);
        $this->assign('OperationsType',$OperationsType['data']);

        return $this->fetch('operations_add');
    }

    //异步添加代理办模板
    public function addOperationsAjax(){
        $post = Request::instance()->param();

        $d['company_id'] = session('user')['company_id'];
        $d['name'] = Arrays::get($post,'name');
        $d['ordering'] = Arrays::get($post,'ordering');
        $d['has_template'] = Arrays::get($post,'has_template')?:0;
        $d['remind_policy'] = Arrays::get($post,'remind_policy');
        $d['remind_day'] = Arrays::get($post,'remind_day');
        $d['operations_type_id'] = Arrays::get($post,'operations_type_id');
        return $this->callSoaErp('post','/operations/addOperations',$d);
    }

    //修改待办模板
    public function upOperations(){
        $id = input("get.id");
        $w['id'] = $id;
        $operationsList =  $this->callSoaErp('post','/operations/selOperations',$w);
        $this->assign('operations',$operationsList['data'][0]);
        $this->assign('remindDay',$this->remindDay);
        unset($w);

        //获取模板类型
        $w['company_id'] = session('user')['company_id'];
        $w['status'] = 1;
        $OperationsType = $this->callSoaErp('post','/operations/selOperationsType',$w);
        $this->assign('OperationsType',$OperationsType['data']);


        return $this->fetch('operations_update');
    }

    //异步修改代理模板
    public function upOperationsAjax(){
        $post = Request::instance()->param();

        $d['id'] = Arrays::get($post,'id');
        $d['company_id'] = session('user')['company_id'];
        $d['name'] = Arrays::get($post,'name');
        $d['ordering'] = Arrays::get($post,'ordering');
        $d['has_template'] = Arrays::get($post,'has_template')?:0;
        $d['remind_policy'] = Arrays::get($post,'remind_policy');
        $d['remind_day'] = Arrays::get($post,'remind_day');
        $d['operations_type_id'] = Arrays::get($post,'operations_type_id');
        return $this->callSoaErp('post','/operations/upOperations',$d);

    }

    //异步删除代理模板
    public function delOperationsAjax(){
       $d['id'] = input('id');
       return $this->callSoaErp('post','/operations/delOperation',$d);
    }



    //待办模板邮件设置
    public function operationsEmail(){
        $w['company_id'] = session('user')['company_id'];
        $w['status'] = 1;
        $w['has_template'] = 1;
        $operationsList = $this->callSoaErp('post','/operations/selOperations',$w);
        $this->assign('operationsList',$operationsList['data']);
        unset($where);

        $search_operation_id = input('get.search_operation_id');
        if($search_operation_id){
            $where['operation_id'] = $search_operation_id;
        }
        $where['company_id'] = session('user')['company_id'];
        $where['status'] = 1;
        $OperationsEmail = $this->callSoaErp('post','/operations/selOperationsEmail',$where);
        $this->assign('OperationsEmail',$OperationsEmail['data']);

        return $this->fetch('operations_email_index');
    }

    //创建邮件模板设置
    public function addOperationsEmail(){
//        $w['company_id'] = session('user')['company_id'];
//        $w['status'] = 1;
//        $w['has_template'] = 1;
//        $operationsList = $this->callSoaErp('post','/operations/selOperations',$w);
//        $this->assign('operationsList',$operationsList['data']);

        return $this->fetch('operations_email_add');

    }

    //异步提交创建邮件模板设置
    public function addOperationsEmailAjax(){
        $post = Request::instance()->param();

        $d['company_id'] = session('user')['company_id'];
        $d['name'] = Arrays::get($post,'name');
        $d['subject'] = Arrays::get($post,'subject');
        $d['content'] = Arrays::get($post,'content');
        $d['operation_id'] = 0; //Arrays::get($post,'operation_id');
        return $this->callSoaErp('post','/operations/addOperationsEmail/',$d);
    }

    //修改邮件模板设置
    public function upOperationsEmail(){
//        $w['company_id'] = session('user')['company_id'];
//        $w['status'] = 1;
//        $w['has_template'] = 1;
//        $operationsList = $this->callSoaErp('post','/operations/selOperations',$w);
//        $this->assign('operationsList',$operationsList['data']);

        $id = input('get.id');
        $OperationsEmail = $this->callSoaErp('post','/operations/selOperationsEmail',['id'=>$id]);
        $this->assign('OperationsEmail',$OperationsEmail['data'][0]);

        return $this->fetch('operations_email_update');
    }

    //修改邮件模板设置
    public function upOperationsEmailAjax(){
        $post = Request::instance()->param();

        $d['id'] = Arrays::get($post,'id');
        $d['company_id'] = session('user')['company_id'];
        $d['name'] = Arrays::get($post,'name');
        $d['subject'] = Arrays::get($post,'subject');
        $d['content'] = Arrays::get($post,'content');
        $d['operation_id'] = 0; //Arrays::get($post,'operation_id');

        return $this->callSoaErp('post','/operations/upOperationsEmail/',$d);
    }

    //删除邮件模板
    public function delOperationsEmail(){
        $d['id'] = input('id');
        return $this->callSoaErp('post','/operations/delOperationsEmail/',$d);
    }

    //修改邮件模板选择
    public function ModifyMailTemplateSelection(){
        $d['id'] = input('id');
        $d['email_template_id'] = input('operations_email_templates_id');
        return $this->callSoaErp('post','/operations/ModifyMailTemplateSelection',$d);
    }

    //订单待办列表  参数（company_order_number）
    public function OperationsListAjax(){
        //获取订单的基本信息
        $w['company_order_number'] = input('company_order_number');
        $CompanyOrder = $this->callSoaErp('post','/branchcompany/getCompanyOrder',$w);
        unset($w);
//        var_dump($CompanyOrder);exit;
        //获取邮件模板
        $w['company_id'] = session('user')['company_id'];
        $w['status'] = 1;
        $OperationsEmail = $this->callSoaErp('post','/operations/selOperationsEmail',$w);
//        $OperationsEmailGr = Arrays::group($OperationsEmail['data'],'operation_id');
//        $this->assign('OperationsEmailGr',$OperationsEmailGr);
//        var_dump($OperationsEmail);exit;
        $this->assign('OperationsEmailGr',$OperationsEmail['data']);
        unset($w);

        //根据分公司订单ID获取订单待办模板
        $w['company_order_id'] = $CompanyOrder['data'][0]['company_order_id'];
        $w['operations_type_id'] = $CompanyOrder['data'][0]['operations_type_id'];
        $CompanyOrderOperations = $this->callSoaErp('post','/operations/setCompanyOrderOperationsByCompanyOrderId',$w);
        $this->assign('CompanyOrderOperations',$CompanyOrderOperations['data']);
        unset($w);
//        var_dump($CompanyOrderOperations);exit;
        //负责人
        $w['department_id'] = session('user')['department_id'];
        $w['status'] = 1;
        $UserList = $this->callSoaErp('post','/user/getUser',$w);
        $this->assign('UserList',$UserList['data']);
        unset($w);

        //待办类型
        $w['company_id'] = session('user')['company_id'];
        $w['status'] = 1;
        $OperationsType = $this->callSoaErp('post','/operations/selOperationsType',$w);
        unset($w);
        $this->assign('OperationsType',$OperationsType['data']);
//        var_dump($CompanyOrderOperations['data'],$CompanyOrder['data'][0]['operations_type_id']);exit;
        //创建公司待办
        if(empty($CompanyOrderOperations['data']) && empty($CompanyOrder['data'][0]['operations_type_id'])){
            $d['company_order_id'] = $CompanyOrder['data'][0]['company_order_id'];
            $d['company_order_number'] = $CompanyOrder['data'][0]['company_order_number'];
            $d['company_id'] = session('user')['company_id'];
            $d['user_id'] = session('user')['user_id'];
            $CompanyOrderOperations = $this->callSoaErp('post','/operations/FoundCompanyOrderOperations',$d);
            $this->assign('CompanyOrderOperations',$CompanyOrderOperations['data']);
        }

        $this->assign('company_order_id',$CompanyOrder['data'][0]['company_order_id']);

        $w['company_order_number'] = input('company_order_number');
        $CompanyOrder = $this->callSoaErp('post','/branchcompany/getCompanyOrder',$w);
        unset($w);
        $this->assign('operations_type_id',$CompanyOrder['data'][0]['operations_type_id']);

//        var_dump($CompanyOrderOperations);
        return $this->fetch('operations_list');

    }

    //订单待办模板附件
    public function upload_operations_attachments(){
        $file = request()->file('file');
        if($file){
            $file_result = $file->getInfo();
            $info = $file->move(ROOT_PATH . 'public' . DS . 'static'.DS.'uploads'.DS.'images');

            $image_name = $file_result['name'];
            //获取后缀名
            $image_name = substr($image_name,strpos($image_name,'.')+1);
            $temp_name = $file_result['tmp_name'];
            $temp_name = str_replace('tmp',$image_name,$temp_name);
            $url = config('soaupload')['ip'].':'.config('soaupload')['port'];


            $result = help::curlImages($info->getPathname(), $url."/index/uploadImages");

            if($result){
                $result = json_decode($result,true);
                $result['get'] = $_GET;
                $result['image_name'] = $file_result['name'];

                $d['company_order_operations_id'] = $_GET['company_order_operations_id'];
                $d['company_order_id'] = $_GET['company_order_id'];
                $d['name'] = $file_result['name'];
                $d['savepath'] = $result['data'];
                $d['uploaded_by'] = session('user')['user_id'];
                $result['company_order_operations_attachments_id'] = $this->callSoaErp('post','/operations/addCompanyOrderOperationsAttachments',$d);

                return json_encode($result);

            }else{
                $d['data'] = '/static/uploads/images/'.$info->getSaveName();
                $d['code'] = 200;
                $d['get'] = $_GET;
                echo json_encode($d);
            }
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }

    //删除附件
    public function delCompanyOrderOperationsAttachmentsAjax(){
        $w['id'] = input('post.company_order_operations_attachments_id');
        return $this->callSoaErp('post','/operations/delCompanyOrderOperationsAttachments',$w);
    }

    //修改订单待办邮件模板
    public function upEmailTemplateIdAjax(){
        $d['id'] = input('company_order_operations_id');
        $d['email_template_id'] = input('email_template_id');
        return $this->callSoaErp('post','/operations/upCompanyOrderOperations',$d);
    }

    //修改订单待办备注
    public function upRemarkAjax(){
        $d['id'] = input('company_order_operations_id');
        $d['remark'] = input('remark');
        return $this->callSoaErp('post','/operations/upCompanyOrderOperations',$d);
    }

    //修改订单提醒谁
    public function upRemindTo(){
        $d['id'] = input('company_order_operations_id');
        $d['remind_to'] = input('remind_to');
        return $this->callSoaErp('post','/operations/upCompanyOrderOperations',$d);
    }

    //修改订单待办状态
    public function upStatus(){
        $d['id'] = input('company_order_operations_id');
        $d['status'] = input('status');
        return $this->callSoaErp('post','/operations/upCompanyOrderOperations',$d);
    }

    //修改订单待办类型
    public function upCompanyOrderOperationsTypeId(){
        $d['company_order_number'] = input('company_order_number');
        $d['operations_type_id'] = input('operations_type_id');
        return $this->callSoaErp('post','/branchcompany/updateCompanyOrderByCompanyOrderNumber',$d);
    }

    //发送待办邮件
    public function dispatchOperationsEmail(){
        $company_order_operations_id = input('company_order_operations_id');
        $company_order_number = input('company_order_number');
        //获取To邮箱
        $CompanyOrder = $this->callSoaErp('post','/branchcompany/getCompanyOrder',['company_order_number'=>$company_order_number]);

        if($CompanyOrder['data'][0]['channel_type']==2){ //直客
            $toEmail = $CompanyOrder['data'][0]['email'];
        }else{ //经销商
             $toEmail = $CompanyOrder['data'][0]['email'];
        }
        //获取用户邮箱
        $email = session('user')['email'];

        //获取邮件模板信息
        $w['id'] = $company_order_operations_id;
        $OperationsEmailTemplates = $this->callSoaErp('post','/operations/selOperationsEmailTemplatesByCompanyOrderOperationsId/',$w);
        $this->assign('OperationsEmailTemplates',$OperationsEmailTemplates['data']);


        $this->assign('toEmail',$toEmail);
        $this->assign('fromEmail',$email);

        return $this->fetch('dispatch_operations_email');
    }

    //邮件附件上传
    public  function emailAttachment(){
        $file = request()->file('file');
        if($file){
//            $file_result = $file->getInfo();
//            $info = $file->move(ROOT_PATH . 'public' . DS . 'static'.DS.'uploads'.DS.'images');
//            $image_name = $file_result['name'];
//            //获取后缀名
//            $image_name = substr($image_name,strpos($image_name,'.')+1);
//            $temp_name = $file_result['tmp_name'];
//            $temp_name = str_replace('tmp',$image_name,$temp_name);
//            $url = config('soaupload')['ip'].':'.config('soaupload')['port'];
//
//            $result = help::curlImages($info->getPathname(), $url."/index/uploadImages");
//
//            if($result){
//                $result = json_decode($result,true);
//                $result['get'] = $_GET;
//                $result['image_name'] = $file_result['name'];
//                return json_encode($result);
//            }else{
//                $d['data'] = '/static/uploads/images/'.$info->getSaveName();
//                $d['code'] = 200;
//                $d['get'] = $_GET;
//                echo json_encode($d);
//            }

            $file_result = $file->getInfo();
            $info = $file->move(ROOT_PATH . 'public' . DS . 'static'.DS.'uploads'.DS.'images');
            $image_name = $file_result['name'];
            $image_name = substr($image_name,strpos($image_name,'.')+1);
            $temp_name = $file_result['tmp_name'];
            $temp_name = str_replace('tmp',$image_name,$temp_name);

                $result['code'] = 200;
                $result['data'] = $info->getPathname() ;
                $result['get'] = $_GET;
                $result['image_name'] = $file_result['name'];
                return json_encode($result);
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }

    //发送待办邮件
    public function send_operations_email(){
       $post = Request::instance()->param();
       return  $this->callSoaErp('post','/user/sendOperationsEmail',$post);
    } 
 
    /**
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/4/25
     * Time: 11:50
     */
    public function getgetServiceReminderListAjax()
    {
        $data['department_id'] = session('user')['department_id'];
        $data['company_id'] = session('user')['company_id'];
        $result =  $this->callSoaErp('post', '/Operations/getServiceReminderList', $data);
        return $result;
    }

 
}