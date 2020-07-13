<?php
namespace app\index\controller;
use app\common\help\Help;

use app\index\model\index\IndexModel;
use app\index\service\PublicService;
use app\index\model\Member;
class Index extends Base
{
	private $_public_service;
	public function __construct()
	{

		$this->_public_service = new PublicService();
		parent::__construct();
	}
    public function index()
    {
    	$a = $this->_public_service->getRouteTypeRecursion(46);
    	$this->outPut($a);
    }

    public function changeStatus(){
        $params = $this->input();
        $model = new IndexModel();
        $result = $model->updateStatusByTableId($params);
        $this->outPut($result);
    }


}
