<?php
namespace app\index\controller;
use app\common\help\Help;
use app\index\model\Member as MemberModel;
use \app\index\controller\Base;
class Member extends Base
{
    /**
     *  会员注册
     *  胡伊敏
     *  2018-7-19
     *
     */
    public function memberRegister()
    {
        $paramRule = [
            'username' => 'string',
            'password'=>'string'

        ];


        $params = $this->input();
        $this->paramCheckRule($paramRule,$params);

        $member = new MemberModel();
        $memberResult = $member->getMemberInfoByUsernameAndPassword($params);

        $this->outPut($memberResult);


    }
}
