<?php
namespace app\index\controller;
use app\common\help\Contents;
use app\common\help\Help;

use app\index\controller\Base;

use app\index\model\ota_slide\OtaAdvertList;
use app\index\model\ota_slide\OtaSlide as SlideModel;
use app\index\model\ota_slide\OtaSlideList;
use app\index\model\ota_slide\OtaAdvert as AdvertModel;
use app\index\model\ota_slide\OtaAdvertising as AdvertisingModel;
use app\index\model\ota_slide\OtaAdvertisingSubtitle as AdvertisingSubtitleModel;
use app\index\model\ota_slide\OtaAdvertisingProduct as AdvertisingProductModel;
use think\Model;
use think\Controller;
class OtaSlide extends Base
{
	private $_language;
    //_lang Base里的属性，
    public function __construct()
    {
    	$this->_language = config("systom_setting")['language_default'];
        parent::__construct();
    }
    public function addOtaSlideList(){
        $params = $this->input();

        $model = new OtaSlideList();

        $result = $model->addOtaSlideList($params);

        $this->outPut($result);
    }

    public function getOneOtaSlideList(){
        $params = $this->input();

        $model = new OtaSlideList();

        $result = $model->getOneOtaSlideList($params);

        $this->outPut($result);
    }

    public function getOtaSlideListList(){
        $params = $this->input();
        $model = new OtaSlideList();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getOtaSlideListList($params, true);
            $result = $model->getOtaSlideListList($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }


        $result = $model->getOtaSlideListList($params);

        $this->outPut($result);
    }

    public function updateOtaSlideList(){
        $params = $this->input();

        $model = new OtaSlideList();

        $result = $model->updateOtaSlideListById($params);

        $this->outPut($result);
    }

    public function addOtaSlide(){
        $params = $this->input();

        $model = new SlideModel();

        $result = $model->addOtaSlide($params);

        $this->outPut($result);
    }
  
    public function getOtaSlide(){
        $params = $this->input();

        $model = new SlideModel();

        $result = $model->getOneOtaSlide($params);

        $this->outPut($result);
    }

    public function getOtaSlideList(){
        $params = $this->input();
        $model = new SlideModel();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getOtaSlideList($params, true);
            $result = $model->getOtaSlideList($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }


        $result = $model->getOtaSlideList($params);

        $this->outPut($result);
    }

    public function updateOtaSlide(){
        $params = $this->input();

        $model = new SlideModel();

        $result = $model->updateOtaSlideById($params);

        $this->outPut($result);
    }


    public function addOtaAdvertList(){
        $params = $this->input();

        $model = new OtaAdvertList();

        $result = $model->addOtaAdvertList($params);

        $this->outPut($result);
    }

    public function getOneOtaAdvertList(){
        $params = $this->input();

        $model = new OtaAdvertList();

        $result = $model->getOneOtaAdvertList($params);

        $this->outPut($result);
    }

    public function getOtaAdvertListList(){
        $params = $this->input();

        $model = new OtaAdvertList();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getOtaAdvertListList($params, true);
            $result = $model->getOtaAdvertListList($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

        $result = $model->getOtaAdvertListList($params);

        $this->outPut($result);
    }

    public function updateOtaAdvertList(){
        $params = $this->input();

        $model = new OtaAdvertList();

        $result = $model->updateOtaAdvertListById($params);

        $this->outPut($result);
    }



    public function addOtaAdvert(){
        $params = $this->input();

        $model = new AdvertModel();

        $result = $model->addOtaAdvert($params);

        $this->outPut($result);
    }

    public function getOtaAdvert(){
        $params = $this->input();

        $model = new AdvertModel();

        $result = $model->getOneOtaAdvert($params);

        $this->outPut($result);
    }

    public function getOtaAdvertList(){
        $params = $this->input();

        $model = new AdvertModel();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getOtaAdvertList($params, true);
            $result = $model->getOtaAdvertList($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

        $result = $model->getOtaAdvertList($params);

        $this->outPut($result);
    }

    public function updateOtaAdvert(){
        $params = $this->input();

        $model = new AdvertModel();

        $result = $model->updateOtaAdvertById($params);

        $this->outPut($result);
    }

    public function addOtaAdvertising(){
        $params = $this->input();

        $model = new AdvertisingModel();

        $result = $model->addOtaAdvertising($params);

        $this->outPut($result);
    }

    public function getOtaAdvertising(){
        $params = $this->input();

        $model = new AdvertisingModel();

        $result = $model->getOneOtaAdvertising($params);

        $this->outPut($result);
    }

    public function getOtaAdvertisingList(){
        $params = $this->input();

        $model = new AdvertisingModel();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getOtaAdvertisingList($params, true);
            $result = $model->getOtaAdvertisingList($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

        $result = $model->getOtaAdvertisingList($params);

        $this->outPut($result);
    }

    public function updateOtaAdvertising(){
        $params = $this->input();

        $model = new AdvertisingModel();

        $result = $model->updateOtaAdvertisingById($params);

        $this->outPut($result);
    }

    public function addOtaAdvertisingSubtitle(){
        $params = $this->input();

        $model = new AdvertisingSubtitleModel();

        $result = $model->addOtaAdvertisingSubtitle($params);

        $this->outPut($result);
    }

    public function getOtaAdvertisingSubtitle(){
        $params = $this->input();

        $model = new AdvertisingSubtitleModel();

        $result = $model->getOneOtaAdvertisingSubtitle($params);

        $this->outPut($result);
    }

    public function getOtaAdvertisingSubtitleList(){
        $params = $this->input();

        $model = new AdvertisingSubtitleModel();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getOtaAdvertisingSubtitleList($params, true);
            $result = $model->getOtaAdvertisingSubtitleList($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

        $result = $model->getOtaAdvertisingSubtitleList($params);

        $this->outPut($result);
    }

    public function updateOtaAdvertisingSubtitle(){
        $params = $this->input();

        $model = new AdvertisingSubtitleModel();

        $result = $model->updateOtaAdvertisingSubtitleById($params);

        $this->outPut($result);
    }

    public function addOtaAdvertisingProduct(){
        $params = $this->input();

        $model = new AdvertisingProductModel();

        $result = $model->addOtaAdvertisingProduct($params);

        $this->outPut($result);
    }

    public function getOtaAdvertisingProduct(){
        $params = $this->input();

        $model = new AdvertisingProductModel();

        $result = $model->getOneOtaAdvertisingProduct($params);

        $this->outPut($result);
    }

    public function getOtaAdvertisingProductList(){
        $params = $this->input();

        $model = new AdvertisingProductModel();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getOtaAdvertisingProductList($params, true);
            $result = $model->getOtaAdvertisingProductList($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

        $result = $model->getOtaAdvertisingProductList($params);

        $this->outPut($result);
    }

    public function updateOtaAdvertisingProduct(){
        $params = $this->input();

        $model = new AdvertisingProductModel();

        $result = $model->updateOtaAdvertisingProductById($params);

        $this->outPut($result);
    }
}
