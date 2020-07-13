<?php

namespace app\index\model\source;
use think\Exception;
use think\Model;
use app\common\help\Help;
use app\index\service\PublicService;
use think\config;
use think\Db;
class SupplierAccount extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'supplier_account';
    public function initialize()
    {
    	parent::initialize();
    }


    /**
     * @param
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/9/4
     * Time: 15:09
     */
    public function getSupplierAccount($params){

    	$where['object_company_id'] = $params['object_company_id'];
        $where['supplier_id'] = $params['supplier_id'];

        try
        {
            $result = $this->table("supplier_account")->alias('s')
                ->field(['s.*', 'c.symbol'])
                ->join('currency c', 's.object_currency_id = c.currency_id')
                ->where($where)
                ->find();
        }
        catch (Exception $e)
        {
            \think\Response::create(['code' => '400', 'msg' =>$e->getMessage()], 'json')->send();
            exit();
        }

        return $result;
    
    }

    public function getSupplierAccountInfo($params)
    {
        try
        {

            $where = " s.object_company_id = " . $params['object_company_id'] . " and s.supplier_id = " . $params['supplier_id'];

            if ($params['begin_time'])
            {
                $where .= "  and s.create_time > " . strtotime($params['begin_time']);
            }

            if ($params['end_time'])
            {
                $where .= "  and s.create_time < " . strtotime($params['end_time']);
            }

            $result = Db::table('supplier_account_info')->alias('s')
                ->field(['s.*', 'c.symbol'])
                ->join('currency c', 's.currency_id = c.currency_id')
                ->where($where)
                ->select();
        }
        catch (Exception $e)
        {
            \think\Response::create(['code' => '400', 'msg' =>$e->getMessage()], 'json')->send();
            exit();
        }

        return $result;
    }

}