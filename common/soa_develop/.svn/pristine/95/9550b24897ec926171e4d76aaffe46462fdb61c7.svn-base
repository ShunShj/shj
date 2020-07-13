<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/27
 * Time: 17:13
 */

namespace app\index\model\product;
use think\Model;

class RouteOncePrice extends Model
{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'route_once_price';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();
 
    }


    public function getRouteOncePrice($params){
        $data = "1=1 ";
        //团队产品ID
        if(isset($params['route_template_id'])){
            $data.= " and route_once_price.route_template_id='".$params['route_template_id']."'";
        }
        if(isset($params['company_id'])){
        	$data.= " and route_once_price.company_id ='".$params['company_id']."'";
        }
		
        $result =  $this->table("route_once_price")->where($data)->
        field(['route_once_price.*'])->select();

        return $result;
    }

}