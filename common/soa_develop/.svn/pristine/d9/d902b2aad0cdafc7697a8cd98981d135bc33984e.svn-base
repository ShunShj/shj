<?php
namespace app\index\controller;
use app\common\help\Contents;
use app\common\help\Help;
use app\index\model\b2b_tour\B2bTour as B2bTourModel;
use app\index\model\b2b_tour\B2bTourCommission;
use app\index\model\b2b_tour\B2bTourDate;
use app\index\model\b2b_tour\B2bTourItinerary;
use app\index\model\b2b_tour\B2bTourOptions;
use app\index\model\b2b_tour\B2bTourRoom;
use app\index\model\b2b_tour\B2bTourSetting;
use app\index\model\b2b_tour\B2bTourTransfer;

class B2bTour extends Base
{
    public function __construct()
    {

        parent::__construct();
    }


    /**
     * 添加、编辑资产负债表
     */
    public function addB2bTour(){
        $params = $this->input();

        $model = new B2bTourModel();
        $result = $model->addB2bTour($params);
        $this->outPut($result);
    }

    public function getB2bTour()
    {
        $params = $this->input();
        $model = new B2bTourModel();
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getB2bTour($params, true);
            $result = $model->getB2bTour($params,false,'true',$page,$page_size);
        }else{
            $result = $model->getB2bTour($params);
        }

        if(isset($params['page'])){
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];
            return $this->output($data);
        }else{

            $this->outPut($result);
        }
    }

    public function updateB2bTourGeneral()
    {
        $params = $this->input();

        $model = new B2bTourModel();
        $result = $model->updateB2bTourGeneral($params);
        $this->outPut($result);
    }

    public function updateB2bTourDates()
    {
        $params = $this->input();

        $model = new B2bTourDate();
        $result = $model->updateB2bTourDate($params);
        $this->outPut($result);
    }

    public function updateB2bTourItinerary()
    {
        $params = $this->input();

        $model = new B2bTourItinerary();
        $result = $model->updateB2bTourItinerary($params);
        $this->outPut($result);
    }

    public function updateB2bTourRoom()
    {
        $params = $this->input();

        $model = new B2bTourRoom();
        $result = $model->updateB2bTourRoom($params);
        $this->outPut($result);
    }

    public function updateB2bTourTransfer()
    {
        $params = $this->input();

        $model = new B2bTourTransfer();
        $result = $model->updateB2bTourTransfer($params);
        $this->outPut($result);
    }

    public function updateB2bTourCommission()
    {
        $params = $this->input();

        $model = new B2bTourCommission();
        $result = $model->updateB2bTourCommission($params);
        $this->outPut($result);
    }

    public function updateB2bTourOptions()
    {
        $params = $this->input();

        $model = new B2bTourOptions();
        $result = $model->updateB2bTourOptions($params);
        $this->outPut($result);
    }

    public function updateB2bTourSetting()
    {
        $params = $this->input();

        $model = new B2bTourSetting();
        $result = $model->updateB2bTourSetting($params);
        $this->outPut($result);
    }

    public function getB2bTourGeneral()
    {
        $params = $this->input();

        $model = new B2bTourModel();
        $result = $model->getB2bTourGeneralById($params['btb_tour_id']);
        $this->outPut($result);
    }

    public function getB2bTourDates()
    {
        $params = $this->input();

        $model = new B2bTourDate();
        $result = $model->getB2bTourDatesByBtbTourId($params['btb_tour_id']);
        $this->outPut($result);
    }

    public function getB2bTourItinerary()
    {
        $params = $this->input();

        $model = new B2bTourItinerary();
        $result = $model->getB2bTourItineraryByBtbTourId($params['btb_tour_id']);
        $this->outPut($result);
    }

    public function getB2bTourRoom()
    {
        $params = $this->input();

        $model = new B2bTourRoom();
        $result = $model->getB2bTourRoomByBtbTourId($params['btb_tour_id']);
        $this->outPut($result);
    }

    public function getB2bTourTransfer()
    {
        $params = $this->input();

        $model = new B2bTourTransfer();
        $result = $model->getB2bTourTransferByBtbTourId($params['btb_tour_id']);
        $this->outPut($result);
    }

    public function getB2bTourCommission()
    {
        $params = $this->input();

        $model = new B2bTourCommission();
        $result = $model->getB2bTourCommissionByBtbTourId($params['btb_tour_id']);
        $this->outPut($result);
    }

    public function getB2bTourOptions()
    {
        $params = $this->input();
        $model = new B2bTourOptions();
        $result = $model->getB2bTourOptionsByBtbTourId($params['btb_tour_id']);
        $this->outPut($result);

    }

    public function getB2bTourSetting()
    {
        $params = $this->input();

        $model = new B2bTourSetting();
        $result = $model->getB2bTourSettingByBtbTourId($params['btb_tour_id']);
        $this->outPut($result);
    }

    public function updateB2bTourAccountCode()
    {
        $params = $this->input();

        $model = new B2bTourModel();
        $result = $model->updateB2bTourAccountCode($params);
        $this->outPut($result);
    }
}