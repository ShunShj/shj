<?php
/**
 * Created by PhpStorm.
 * User: jiye
 * Date: 2019/1/21
 * Time: 10:17
 */
namespace app\index\controller;

use Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;
class ReminderManagement extends Base
{

    /**
     * �ֹ�˾ϵͳ���������б�
     */
    public function SystemReminderSettings(){
        if($_GET['status']){
            $where['status'] = $_GET['status'];
        }
        if($_GET['system_alert_event_id']){
            $where['system_alert_event_id'] = $_GET['system_alert_event_id'];
        }

        $where['company_id'] = session('user')['company_id'];


        $system_alert_event = $this->callSoaErp('post','/system_alert_event/getSystemReminderList',$where);
        $this->assign('system_alert_event',$system_alert_event['data']);

        if(empty($system_alert_event['data']) && empty($_GET['status'])  && empty($_GET['system_alert_event_id']) ){
            $add['company_id'] = session('user')['company_id'];
            $add['user_id'] = session('user')['user_id'];
            $this->callSoaErp('post','/system_alert_event/addSystemReminder',$add);
            echo '<script>location.reload()</script>';
        }


        //��ȡ�¼�
        $getSystemAlertEvent= $this->callSoaErp('post','/system_alert_event/getSystemAlertEvent',['status'=>1]);
        $this->assign('getSystemAlertEvent',$getSystemAlertEvent['data']);

        return $this->fetch('system_reminder_settings');
    }


    /*
    * �༭�ֹ�˾�¼�����
    */
    public function editSystemAlertSetting(){
        $where['company_id'] = session('user')['company_id'];
        $where['system_alert_setting_id'] = $_GET['system_alert_setting_id'];
//        var_dump($where);exit;
        $system_alert_event = $this->callSoaErp('post','/system_alert_event/getSystemReminderList',$where);
        $this->assign('system_alert_event',$system_alert_event['data'][0]);

        return $this->fetch('edit_system_alert_setting');
    }

    /***
     * �첽�޸ķֹ�˾�¼�����
     */
    public function editSystemAlertSettingAjax(){
        $param = Request::instance()->param();

        $up['system_alert_setting_id'] = Arrays::get($param,'system_alert_setting_id');
        $up['is_system_reminder'] = Arrays::get($param,'is_system_reminder',2);
        $up['system_reminder_content'] = Arrays::get($param,'system_reminder_content');
        $up['is_email_reminder'] = Arrays::get($param,'is_email_reminder',2);
        $up['email_reminder_content'] = Arrays::get($param,'email_reminder_content');
        $up['update_time'] = time();
        $up['user_id'] = session('user')['user_id'];

        return $this->callSoaErp('post','/system_alert_event/updateSystemReminder',$up);

    }

    /*
    * �����޸ķֹ�˾��Ϣ����״̬
    */
    public function batchEditSystemReminderState(){
        $param = Request::instance()->param();

        $up['system_alert_setting_id'] = implode(',',$param['system_alert_setting_id']);
        $up['status'] = $param['status'];

        return $this->callSoaErp('post','/system_alert_event/batchEditSystemReminderState',$up);
    }

    /****
     * �޸ķֹ�˾��Ϣ����״̬
     */
    public function EditSystemReminderState()
    {
        $system_alert_setting_id = input('post.system_alert_setting_id');
        $ar = explode('-',$system_alert_setting_id);

        $up['system_alert_setting_id'] = $ar[1];
        $up['status'] = $ar[0];

        return $this->callSoaErp('post','/system_alert_event/batchEditSystemReminderState',$up);
    }

    /***
     * վ����
     */
    public function getInStationLetterAjax()
    {
        $where['eTime'] = time();

        if($_GET['InStationLetterStime']){
            $where['sTime'] = $_GET['InStationLetterStime'];
        }


        $where['user_id'] = session('user')['user_id'];
        $where['status'] = 1;
        $getInStationLetter =  $this->callSoaErp('post','/system_alert_event/getInStationLetter',$where);

        if(!empty($getInStationLetter['data'])){
            $getInStationLetter['InStationLetterStime'] = $where['eTime'];
        }

        return $getInStationLetter;
    }

    /***
     * �Ķ�վ����
     */
    public function readInStationLetterAjax(){
        $in_station_letter_id = input('post.in_station_letter_id');
        return $this->callSoaErp('post','/system_alert_event/readInStationLetter',['in_station_letter_id'=>$in_station_letter_id]);
    }

    /***
     * �鿴ȫ��վ����
     */
    public function allInStationLetter(){
        $where['user_id'] = session('user')['user_id'];
        
        $getInStationLetter = $this->callSoaErp('post','/system_alert_event/getInStationLetter',$where);
        
        
        $this->assign('getInStationLetter',$getInStationLetter['data']);

        //��ȡ��Ŀ����
        $getSystemAlertEvent = $this->callSoaErp('post','/system_alert_event/getSystemAlertEvent',$where);
        $getSystemAlertEventGrpup = Arrays::group($getSystemAlertEvent['data'],'system_alert_event_id');
//        var_dump($getSystemAlertEventGrpup);exit;
        $this->assign('getSystemAlertEventGrpup',$getSystemAlertEventGrpup);

        return $this->fetch('all_in_station_letter');
    }




}