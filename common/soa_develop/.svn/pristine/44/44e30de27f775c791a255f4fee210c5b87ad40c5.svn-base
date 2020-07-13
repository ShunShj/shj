<?php
namespace app\index\controller;
use app\common\help\Contents;
use app\common\help\Help;

use app\index\controller\Base;

use app\index\model\ota_system\OtaCompanyWebsite;
use app\index\model\ota_system\OtaMediaPool;
use app\index\model\ota_system\OtaSubscribe;
use app\index\model\ota_system\OtaWebsite;
use app\index\model\ota_system\OtaWebsitePay;
use think\Model;
use think\Controller;
class OtaSystem extends Base
{
	private $_language;
    //_lang Base里的属性，
    public function __construct()
    {
    	$this->_language = config("systom_setting")['language_default'];
        parent::__construct();
    }

    public function addOtaWebsite(){
        $params = $this->input();

        $model = new OtaWebsite();

        $result = $model->addOtaWebsite($params);

        $this->outPut($result);
    }
  
    public function getOtaWebsite(){
        $params = $this->input();

        $model = new OtaWebsite();

        $result = $model->getOneOtaWebsite($params);

        $this->outPut($result);
    }

    public function getOtaWebsiteList(){
        $params = $this->input();

        $model = new OtaWebsite();

        $result = $model->getOtaWebsiteList($params);

        $this->outPut($result);
    }

    public function updateOtaWebsite(){
        $params = $this->input();

        $model = new OtaWebsite();

        $result = $model->updateOtaWebsiteById($params);

        $this->outPut($result);
    }

    public function addOtaCompanyWebsite(){
        $params = $this->input();

        $model = new OtaCompanyWebsite();

        $result = $model->addOtaCompanyWebsite($params);

        $this->outPut($result);
    }

    public function getOtaCompanyWebsite(){
        $params = $this->input();

        $model = new OtaCompanyWebsite();

        $result = $model->getOneOtaCompanyWebsite($params);

        $this->outPut($result);
    }

    public function getOtaCompanyWebsiteList(){
        $params = $this->input();

        $model = new OtaCompanyWebsite();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getOtaCompanyWebsiteList($params, true);
            $result = $model->getOtaCompanyWebsiteList($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

        $result = $model->getOtaCompanyWebsiteList($params);

        $this->outPut($result);
    }

    public function updateOtaCompanyWebsite(){
        $params = $this->input();

        $model = new OtaCompanyWebsite();

        $result = $model->updateOtaCompanyWebsiteById($params);

        $this->outPut($result);
    }

    public function getOtaMediaPoolList(){
        $params = $this->input();

        $model = new OtaMediaPool();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getOtaMediaPoolList($params, true);
            $result = $model->getOtaMediaPoolList($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

        $result = $model->getOtaMediaPoolList($params);

        $this->outPut($result);
    }

    public function addOtaMediaPool(){
        $params = $this->input();

        $model = new OtaMediaPool();

        $result = $model->addOtaMediaPool($params);

        $this->outPut($result);
    }

    public function deleteOtaMediaPool(){
        $params = $this->input();

        $model = new OtaMediaPool();

        $result = $model->deleteOtaMediaPool($params);

        $this->outPut($result);
    }



    public function openOtaWebsitePay()
    {
        $params = $this->input();
        $paramRule = [
            'website_uuid'=>'string',
        ];

        $this->paramCheckRule($paramRule,$params);

        $model = new OtaWebsitePay();
        $result = $model->openOtaWebsitePay($params);
        $this->outPut($result);

    }

    public function getOtaWebsitePayList()
    {
        $params = $this->input();

        $model = new OtaWebsitePay();

        $result = $model->getOtaWebsitePay($params);
        $this->outPut($result);

    }

    public function addSubscribe()
    {
        $params = $this->input();
        $paramRule = [
            'website_uuid'=>'string',
            'subscribe_email' => 'string',
            //'member_uuid' => 'string'
        ];

        $this->paramCheckRule($paramRule,$params);

        $model = new OtaSubscribe();
        $result = $model->addSubscribe($params);
        $this->outPut($result);
    }

    public function getSubscribe()
    {
        $params = $this->input();
        $model = new OtaSubscribe();
        $result = $model->getSubscribe($params);
        $this->outPut($result);
    }
}
