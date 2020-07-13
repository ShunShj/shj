<?php
namespace app\index\controller;

use app\common\help\Help;
use app\index\model\log\LoginLog;
use app\index\service\OtaMemberService;
use app\index\model\ota_member\OtaMember as OtaMemberModel;

class OtaMember extends Base
{

    /**
     * 用户列表
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/14
     * Time: 10:51
     */
    public function getOtaMemberList()
    {

        $params = $this->input();
        $paramRule = [
            'website_uuid'=> 'string',
        ];

        $this->paramCheckRule($paramRule,$params);
        $ota_member_service = new OtaMemberService();

        $result = $ota_member_service->getMemberList($params);

        $this->outPut($result);

    }

    /**
     * 添加账号
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/28
     * Time: 10:08
     */
    public function addOtaMember()
    {
        $params = $this->input();

        $paramRule = [
            'username'=>'string',
            'password'=>'string',
            'email' => 'string',
            'website_uuid'=> 'string'
        ];
        $this->paramCheckRule($paramRule,$params);
        $ota_member_service = new OtaMemberService();

        $result = $ota_member_service->editOtaMember($params);

        $this->outPut($result);
    }

    /**
     * 修改账号
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/14
     * Time: 12:03
     * @throws \think\exception\PDOException
     */
    public function editOtaMember()
    {
        $params = $this->input();

        $paramRule = [
            'uuid'=> 'string',
            'website_uuid'=> 'string',
        ];
        $this->paramCheckRule($paramRule,$params);
        $ota_member_service = new OtaMemberService();

        $result = $ota_member_service->editOtaMember($params);

        $this->outPut($result);
    }

    /**
     * 删除账号
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/28
     * Time: 10:08
     */
    public function delOtaMember()
    {
        $params = $this->input();

        $paramRule = [
            'uuid'=> 'string',
            'website_uuid'=> 'string',
        ];
        $this->paramCheckRule($paramRule,$params);
        $params['status'] = 0;
        $ota_member_service = new OtaMemberService();

        $result = $ota_member_service->editOtaMember($params);

        $this->outPut($result);
    }

    /**
     * 获取账户详情
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/14
     * Time: 10:47
     */
    public function getMemberInfo()
    {
        $params = $this->input();

        $paramRule = [
            'uuid' => 'string',
        ];

        $this->paramCheckRule($paramRule,$params);
        $ota_member_model = new OtaMemberModel();
        $result = $ota_member_model->getOtaMember($params);
        $this->outPut($result);
    }


    /**
     * 注册
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/24
     * Time: 15:30
     */
    public function register()
    {
        $params = $this->input();

        $paramRule = [
            'username' => 'string',
            'password' => 'string',
            'email'    => 'string',
            'website_uuid'=> 'string',
        ];

        $this->paramCheckRule($paramRule,$params);
        $ota_member_service = new OtaMemberService();

        $result = $ota_member_service->editOtaMember($params);
        $this->outPut($result);
    }

    /**
     * 登陆
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/5/24
     * Time: 15:44
     */
    public function login()
    {
        $params = $this->input();

        $paramRule = [
            'username' => 'string',
            'password' => 'string',
            'website_uuid' => 'string'
        ];
        $this->paramCheckRule($paramRule,$params);

        $ota_member_service = new OtaMemberService();

        $result = $ota_member_service->getMemberList($params);

        if(empty($result) ){
            \think\Response::create(['code' => '400', 'msg' => ' username or password is error'], 'json')->send();
            exit();
        }

        if($result['list'][0]['password'] != md5($params['password'])){
            \think\Response::create(['code' => '400', 'msg' => ' username or password is error'], 'json')->send();
            exit();


        }
        if($result['list'][0]['status'] != 1){
            \think\Response::create(['code' => '400', 'msg' => ' user is disabled'], 'json')->send();
            exit();
        }

        $this->outPut($result['list'][0]);
    }

}
