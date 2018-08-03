<?php
/**
 * Created by PhpStorm.
 * User: Shj
 * Date: 2018/8/2
 * Time: 17:16
 */
namespace app\ctrl;
use common\lib\Controller;

class userCtrl extends Controller{

    public function index(){
        $model = new \app\model\userModel();
        $res = $model->all();
        $this->assign('data',$res);
        $this->display('user/index.html');

    }

}