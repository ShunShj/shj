<?php
namespace app\index\model\ota_product;

use app\common\help\Help;
use think\Db;
use think\Exception;
use think\Model;
//use app\index\model\system\User;

class OtaProductSpec extends Model{
 
    protected $table = 'ota_product_specifications';

    //自动过滤掉不存在的字段
    protected $field = true;

    public function initialize()
    {
        parent::initialize();
    }

    public function updateSpec($spec, $ota_product_uuid)
    {
        //本产品的所有规格数据设为无效
        $this->update(['status' => 0], ['ota_product_uuid' => $ota_product_uuid]);
        Db::table('ota_product_team_product')->where(['ota_product_uuid' => $ota_product_uuid])->update(['status' => 0]);
        Db::table('ota_product_source')->where(['ota_product_uuid' => $ota_product_uuid])->update(['status' => 0]);
        if ($spec['spec_uuid'])
        {
            foreach ($spec['spec_uuid'] as $k=>$v)
            {
                if (!empty($v))
                {
                    $data['product_specifications_name'] = isset($spec['product_specifications_name'][$k]) ? $spec['product_specifications_name'][$k] : '';  //规格名称
                    $data['status'] = 1;        //状态
                    $data['ota_product_uuid'] = $ota_product_uuid;        //产品uuid
                    $data['create_time'] = time();      //创建时间
                    if ($this->update($data, ['uuid' => $v]) === false) return false;
                    $where = ['product_specifications_uuid' => $v];
                    if (Db::table('ota_product_team_product')->where($where)->update(['ota_product_uuid' => $ota_product_uuid,'status' => 1]) === false) return false;
                    if (Db::table('ota_product_source')->where($where)->update(['ota_product_uuid' => $ota_product_uuid, 'status' => 1]) === false) return false;
                }
            }
        }

        return true;
    }

    public function addOne($spec)
    {
        $data = [];
        $data['product_specifications_name'] = '';  //规格名称
        $data['branch_product_id'] = $spec['branch_product_id'];      //分公司产品ID
        $data['route_template_id'] = $spec['route_template_id'];        //线路模板
        $data['product_type'] = $spec['product_type'];          //产品类型 1是分公司产品 2是团队产品
        $data['status'] = 0;        //状态
        $data['create_user_id'] = $spec['now_user_id'];       //创建人id
        $data['uuid'] = Help::getUuid();    //uuid
        $data['ota_product_uuid'] = '';
        if ($data)
        {
            try
            {
                $this->startTrans();
                if ($this->insert($data) === false)
                {
                    $this->rollback();
                    return false;
                }
                if ($this->__addTeamProduct($spec['team_product'], $data['uuid'], $data['create_user_id']) === false)
                {
                    $this->rollback();
                    return false;
                }

                if ($this->__addProductSource($spec['product_source'], $data['uuid'], $data['create_user_id']) === false)
                {

                    $this->rollback();
                    return false;
                }
                $this->commit();
                return $data['uuid'];
            }
            catch (Exception $e)
            {
                error_log(print_r($e->getMessage(),1));
                $this->rollback();
                return false;
            }

        }

    }

    private function __addTeamProduct($team_product, $spec_uuid, $user_id)
    {
        $where['product_specifications_uuid'] = $spec_uuid;        //产品uuid
        $where['status'] = 1;

        Db::table('ota_product_team_product')->where($where)->update(['status' => 0]);         //设为失效


        if ($team_product)
        {
            $save_data = [];
            foreach ($team_product as $v)
            {
                $arr = [];
                $arr['ota_product_uuid'] = '';
                $arr['uuid'] = Help::getUuid();
                $arr['team_product_id'] = $v['team_product_id'];        //团队产品id
                $arr['customer_price'] = $v['customer_price'];       //直客价
                $arr['distributor_price'] = $v['distributor_price'];       //代理价
                $arr['original_currency_id'] = $v['currency_id'];       //货币id
                $arr['original_price'] = $v['customer_price'];       //原价
                $arr['currency_id'] = $v['currency_id'];       //原价货币id
                $arr['product_specifications_uuid'] = $spec_uuid;
                $arr['status'] = 0;
                array_push($save_data, $arr);
            }

            if (!empty($save_data))
            {
                return Db::table('ota_product_team_product')->insertAll($save_data);
            }
        }

        return true;

    }

    private function __addProductSource($product_source, $spec_uuid, $user_id)
    {
        $where['product_specifications_uuid'] = $spec_uuid;        //产品uuid
        $where['status'] = 1;

        Db::table('ota_product_source')->where($where)->update(['status' => 0]);         //设为失效

        if ($product_source)
        {
            $save_data = [];
            foreach ($product_source as $v)
            {
                $arr = [];
                $arr['ota_product_uuid'] = '';
                $arr['uuid'] = Help::getUuid();
                $arr['team_product_id'] = $v['team_product_id'];      //此自费项目对应的 团队产品ID
                $arr['team_product_allocation_id'] = $v['team_product_allocation_id'];  //团队产品资源配置ID
                $arr['source_id'] = $v['source_id'];      //资源ID
                $arr['source_type_id'] = $v['source_type_id'];        //资源类型ID
                $arr['customer_price'] = $v['customer_price'];  //报价
                $arr['distributor_price'] = $v['distributor_price'];              //代理价格
                $arr['currency_id'] = $v['currency_id'];        //货币ID
                $arr['product_specifications_uuid'] = $spec_uuid;     //规格的uuid

                $arr['original_price'] = $v['customer_price'];       //原价
                $arr['currency_id'] = $v['currency_id'];       //原价货币id
                $arr['status'] = 0;
                array_push($save_data, $arr);

            }
            if (!empty($save_data))
            {
                return Db::table('ota_product_source')->insertAll($save_data);
            }
        }

        return true;
    }


    public function getSpec($param)
    {

        $where['o.status'] = 1;
        if ($param['ota_product_uuid'])
        {
            $where['o.ota_product_uuid'] = $param['ota_product_uuid'];
        }

        if ($param['spec_uuid'])
        {
            $where['o.uuid'] = $param['spec_uuid'];
        }

        $spec = $this->alias('o')
            ->field('b.branch_product_name, b.branch_product_number, o.product_specifications_name, o.uuid, r.route_number, r.route_name, o.product_type')
            ->join('branch_product b', 'b.branch_product_id = o.branch_product_id', 'LEFT')
            ->join('route_template r', 'r.route_template_id = o.route_template_id', 'LEFT')
            ->where($where)->select();

        foreach ($spec as $v)
        {
            $where_spec = ['o.product_specifications_uuid' => $v['uuid']];
            $v['team_product'] = Db::table('ota_product_team_product')
                ->alias('o')->field('o.*, c.currency_name, c.symbol, c.unit, t.begin_time')
                ->join('team_product t', 't.team_product_id = o.team_product_id')
                ->join('currency c', 'c.currency_id = o.currency_id')
                ->order('t.begin_time', 'asc')
                ->where($where_spec)->select();
            
            $v['product_source'] = Db::table('ota_product_source')
                ->alias('o')->field('o.*, c.currency_name, c.symbol, c.unit, s.supplier_type_name, oe.own_expense_name as source_name')
                ->join('own_expense oe', 'oe.own_expense_id= o.source_id')
                ->join('supplier_type s', 's.supplier_type_id = o.source_type_id')
                ->join('currency c', 'c.currency_id = o.currency_id')
                ->where($where_spec)->select();

        }

        return $spec;
    }

    public function getTeam($param)
    {
        $where = "1=1 ";
        if (!empty($param['spec_uuid']))
        {
            $where .= " and o.product_specifications_uuid = '".$param['spec_uuid'] . "'";
        }

        if (!empty($param['team_uuid']))
        {
            $where .= " and o.uuid = '".$param['team_uuid'] . "'";
        }

        if (!empty($param['status']))
        {
            $where .= " and o.status = ".$param['status'];
        }

        return Db::table('ota_product_team_product')
            ->alias('o')->field('o.*, c.currency_name, c.symbol, c.unit, t.begin_time, t.team_product_number, rt.route_number')
            ->join('team_product t', 't.team_product_id = o.team_product_id')
            ->join('currency c', 'c.currency_id = o.currency_id')
            ->join('route_template rt', 'rt.route_template_id = t.route_template_id')
            ->order('t.begin_time', 'asc')
            ->where($where)->select();

    }

    public function getSource($param)
    {
        $where = "1=1 ";
        if (!empty($param['spec_uuid']))
        {
            $where .= " and o.product_specifications_uuid = '".$param['spec_uuid'] . "'";
        }

        if (!empty($param['status']))
        {
            $where .= " and o.status = ".$param['status'];
        }

        if (!empty($param['uuid']))
        {
            $where .= " and o.uuid = '".$param['uuid']. "'";
        }

        if (!empty($param['team_product_id']))
        {
            $where .= " and o.team_product_id = ".$param['team_product_id'];
        }

        if (!empty($param['begin_time']))
        {
            $where .= " and t.begin_time = ".$param['begin_time'];
        }

        return Db::table('ota_product_source')
            ->alias('o')->field('o.*, c.currency_name, c.symbol, c.unit, s.supplier_type_name, o.source_type_id, t.begin_time')
            ->join('supplier_type s', 's.supplier_type_id = o.source_type_id')
            ->join('team_product t', 't.team_product_id = o.team_product_id')
            ->join('currency c', 'c.currency_id = o.currency_id')
            ->order('t.begin_time', 'asc')
            ->where($where)->select();
    }

    public function updateTeam($params)
    {
        try{

            $this->startTrans();
            foreach ($params['teamPriceList'] as $v)
            {
                $data = [];
                $where = ['product_specifications_uuid' => $v['product_specifications_uuid'], 'team_product_id' => $v['team_product_id']];

                if ($v['customer_price'])
                {
                    $data['customer_price'] = $v['customer_price'];
                }

                if ($v['distributor_price'])
                {
                    $data['distributor_price'] = $v['distributor_price'];
                }

                /*if ($v['currency_id'])
                {
                    $data['currency_id'] = $v['currency_id'];
                }*/
                Db::table('ota_product_team_product')->where($where)->update($data);
            }
            // 提交事务
            $this->commit();

        } catch (\Exception $e) {
            // 回滚事务
            $this->rollback();

            return false;
        }

        return true;
    }

    public function updateSource($params)
    {
        try{

            $this->startTrans();
            foreach ($params['sourcePriceList'] as $v)
            {
                $data = [];
                if ($v['customer_price'])
                {
                    $data['customer_price'] = $v['customer_price'];
                }

                if ($v['distributor_price'])
                {
                    $data['distributor_price'] = $v['distributor_price'];
                }

                /*if ($v['currency_id'])
                {
                    $data['currency_id'] = $v['currency_id'];
                }*/

                Db::table('ota_product_source')->where(['uuid' => $v['uuid']])->update($data);
            }

            // 提交事务
            $this->commit();

        } catch (\Exception $e) {
            // 回滚事务
            $this->rollback();

            return false;
        }

        return true;
    }


  /*  private function __editTeamProduct($team_product, $spec_uuid, $user_id)
    {

        foreach ($team_product as $v)
        {
            $data = [];
            $data['team_product_id'] = $v['team_product_id'];
            $data['customer_price'] = $v['customer_price'];
            $data['distributor_price'] = $v['distributor_price'];
            $data['currency_id'] = $v['currency_id'];
            $data['product_specifications_uuid'] = $spec_uuid;
            $data['update_user_id'] = $user_id;       //修改人id
            $data['update_time'] = time();          //修改时间
            $result = Db::table('ota_product_team_product')->where(['id'=> $v['id'], 'product_specifications_uuid' => $spec_uuid])->update($data);
            if ($result === false) return false;
        }
        return true;
    }

    private function __editProductSource($product_source, $spec_uuid, $user_id)
    {
        foreach ($product_source as $v)
        {
            $data = [];
            $data['source_id'] = $v['source_id'];      //资源ID
            $data['source_type_id'] = $v['source_type_id'];        //资源类型ID
            $data['customer_price'] = $v['customer_price'];  //报价
            $data['distributor_price'] = $v['distributor_price'];              //代理价格
            $data['currency_id'] = $v['currency_id'];        //货币ID
            $data['product_specifications_uuid'] = $spec_uuid;     //规格的uuid
            $data['create_user_id'] = $user_id;       //添加人id
            $data['create_time'] = time();          //添加时间

            $result = Db::table('ota_product_source')->where(['id'=> $v['id'], 'product_specifications_uuid' => $spec_uuid])->update($data);
            if ($result === false) return false;
        }
        return true;
    }*/

}