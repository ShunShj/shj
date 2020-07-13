<?php
namespace app\index\controller;
use app\common\help\Contents;
use app\common\help\Help;
use app\index\controller\Base;

use app\index\model\ota_menu\OtaMenu as OtaMenuModel;
use app\index\model\ota_menu\OtaMenuList as OtaMenuListModel;
class OtaMenu extends Base
{
	private $_language;
    //_lang Base里的属性，
    public function __construct()
    {
    	$this->_language = config("systom_setting")['language_default'];
        parent::__construct();
    }
    

    public function addOtaMenuList(){
        $params = $this->input();


        $data = [
            'title'=>$params['title'],
            'website_uuid'=>$params['website_uuid']
        ];
        $this->checkNameIsRepetition('ota_menu_list',$data);


        $paramRule = [
            'website_uuid' => 'string',
            'title'=>'string',
        ];
        $this->paramCheckRule($paramRule,$params);


        $model = new OtaMenuListModel();

        $result = $model->addOtaMenuList($params);

        $this->outPut($result);
    }

    public function getOneOtaMenuList(){
        $params = $this->input();

        $model = new OtaMenuListModel();

        $result = $model->getOneOtaMenuList($params);

        $this->outPut($result);
    }

    public function getOtaMenuListList(){
        $params = $this->input();

        $model = new OtaMenuListModel();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getOtaMenuListList($params, true);
            $result = $model->getOtaMenuListList($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

        $result = $model->getOtaMenuListList($params);

        $this->outPut($result);
    }

    public function updateOtaMenuList(){
        $params = $this->input();

        $model = new OtaMenuListModel();

        $where['ota_menu_list_id'] = $params['ota_menu_list_id'];
        $Info = $model->getOneOtaMenuList($where);
        //重复性验证
        if($Info['title'] == $params['title']){
        }else{
            //开始判断名字是否重复
            $data = [
                'title'=>$params['title'],
                'website_uuid'=>$Info['website_uuid']
            ];
            $this->checkNameIsRepetition('ota_menu_list',$data);
            //结束判断名字重复
        }


        $paramRule = [
            'title'=>'string',
        ];
        $this->paramCheckRule($paramRule,$params);

        $result = $model->updateOtaMenuListById($params);

        $this->outPut($result);
    }

    public function addOtaMenu(){
        $params = $this->input();

        $model = new OtaMenuModel();

        $result = $model->addOtaMenu($params);

        $this->outPut($result);
    }

    public function getOtaMenu(){
        $params = $this->input();

        $model = new OtaMenuModel();

        $result = $model->getOneOtaMenu($params);

        $this->outPut($result);
    }

    public function getOtaMenuList(){
        $params = $this->input();

        $model = new OtaMenuModel();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getOtaMenuList($params, true);
            $result = $model->getOtaMenuList($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

        $result = $model->getOtaWebMenuList($params);

        $this->outPut($result);
    }

    public function updateOtaMenu(){
        $params = $this->input();

        $model = new OtaMenuModel();

        $result = $model->updateOtaMenuById($params);

        $this->outPut($result);
    }
}
