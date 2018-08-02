<?php
/**
 * Created by PhpStorm.
 * User: Shj
 * Date: 2018/8/2
 * Time: 17:16
 */
namespace app\ctrl;
use common\lib\Controller;

class indexCtrl extends Controller{

    public function index(){
        p(1);die;
        $model = new \app\model\userModel();
        $res = $model->all();
        $this->assign('data',$res);
        $this->display('index.html');

    }

}