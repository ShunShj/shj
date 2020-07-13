<?php

namespace app\index\model\product;
use think\Model;
use app\index\service\PublicService;
class RouteTemplateComment extends Model{

    protected $table = 'route_template_comment';
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
    public function addRouteTemplateComment($params){

        $data['route_template_id'] = $params['route_template_id'];
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


    public function getRouteTemplateComment($params)
    {
        $data = " 1=1 ";
        if(!empty($params['route_template_id'])){
            $data.= " and route_template_comment.route_template_id = '".$params['route_template_id']."'";
        }

        if(isset($params['create_user_id'])){
            $data.= " and route_template_comment.create_user_id = ".$params['create_user_id'];
        }

        if(isset($params['status'])){
            $data.= " and route_template_comment.status = ".$params['status'];
        }


        try{
            $result = $this->field(['route_template_comment.*', 'user.username'])
                ->join('user','user.user_id = route_template_comment.create_user_id','left')->where($data)->select();

        } catch (\Exception $e) {
            $result = $e->getMessage();

            \think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            exit();

        }

        return $result;
    }


}