<?php

namespace app\index\model\branchcompany;
use think\Model;
use app\index\service\PublicService;
class BranchProductComment extends Model{

    protected $table = 'branch_product_comment';
    private $_languageList;
    private $_public_service;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        $this->_public_service = new PublicService();
        parent::initialize();

    }

    /**
     * 添加线路留言板
     *
     */
    public function addBranchProductComment($params){

        $data['branch_product_number'] = $params['branch_product_number'];
        $data['comment'] = $params['comment'];
        $data['create_user_id'] = $params['create_user_id'];
        $data['create_time'] = time();
        $data['status'] = 1;

        $this->startTrans();
        try{
            $result = $this->insertGetId($data);
			$this->commit();
        } catch (\Exception $e) {
            $result = $e->getMessage();

            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();
        }

        return $result;
    }


    public function getBranchProductComment($params)
    {
        $data = " 1=1 ";
        if(!empty($params['branch_product_number'])){
            $data.= " and branch_product_comment.branch_product_number = '".$params['branch_product_number']."'";
        }

        if(isset($params['create_user_id'])){
            $data.= " and branch_product_comment.create_user_id = ".$params['create_user_id'];
        }

        if(isset($params['status'])){
            $data.= " and branch_product_comment.status = ".$params['status'];
        }


        try{
            $result = $this->field(['branch_product_comment.*', 'user.username'])
                ->join('user','user.user_id = branch_product_comment.create_user_id','left')->where($data)->select();

        } catch (\Exception $e) {
            $result = $e->getMessage();

            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();

        }

        return $result;
    }


}