<?php
namespace app\index\model\finance;
use app\index\controller\Branchcompany;
use app\index\service\CompanyOrderService;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;

class SalesReport extends Model
{
    protected $table = 'branch_product_type';
    private $_languageList;

    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    public function getSalesReport($params){

        $start = strtotime($params['startDate']);
        $end = strtotime($params['endDate']);
        if($params['startDate'] && $params['endDate']){
            $where['create_time'] = array(array('gt',$start),array('lt',$end)) ;
        }

        $data = "branch_product_type.status=1";
        if(isset($params['company_id'])) {
            $data.=" and branch_product_type.company_id=".$params['company_id'];
        }
//        if($params['branch_product_type_name']){
//            $data.=" and branch_product_type.branch_product_type_name like '%".$params['branch_product_type_name']."%'";
//        }
        if(isset($params['branch_product_type']) && $params['branch_product_type']>0){
            $data.=" and branch_product_type.branch_product_type_id =".$params['branch_product_type'];
        }

        $result = $this->table("branch_product_type")
            ->join("company", 'company.company_id = branch_product_type.company_id', 'left')
            ->where($data)
            ->order('branch_product_type.create_time desc')
            ->field(['branch_product_type.*', 'company.company_name'])
            ->select();

            
        $model = new CompanyOrderService();

        foreach ($result as $key=>$val){
            //产品号列表
            $b = $this->table('branch_product')
                ->where('branch_product.status = 1 and branch_product.branch_product_type_id = '.$val['branch_product_type_id'])
                ->column('branch_product_number');
            //订单号列表
            if(!empty($b)) {
                $d = $this->table('company_order_product')
                    ->where('status', 1)
                    ->where($where)
                    ->where('branch_product_number', 'IN', $b)
                    ->column('company_order_number');
            }
            //收客数
            if(!empty($d)) {
                $e = $this->table('company_order_customer')
                    ->where('status', 'gt', 0)
                    ->where('company_order_number', 'IN', $d)
                    ->count();
            }
            //产品总数
            $a=count($b);
            //订单号总数
            $c=count($d);
            if($c>0){
                foreach ($d as $k=>$v){
                    $list[$key][$d[$k]] = $model->getCompanyOrderReceivableInfo(['company_order_number'=>$d[$k]]);
                }
                $result[$key]['order'] = $d ? : 0;
                $result[$key]['product_total'] = $a ? : 0;
                $result[$key]['product_order_total'] = $c ? : 0;
                $result[$key]['customer_total'] = $e ? : 0;
                $result[$key]['average_customer_total'] = $e/$c ? : 0;
                $result[$key]['sale_total_price'] = array_sum(array_map(function($val){return $val['total'];}, $list[$key])) ? : 0;
                $result[$key]['average_sale_price'] = $result[$key]['sale_total_price']/$c ? : 0;
                $result[$key]['average_sale_customer_price'] = $result[$key]['sale_total_price']/$e ? : 0;
                $result[$key]['finance_receivable'] = array_sum(array_map(function($val){return $val['finance_receivable'];}, $list[$key])) ? : 0;
                $result[$key]['miss_sale_receivable'] = array_sum(array_map(function($val){return $val['miss_sale_receivable'];}, $list[$key])) ? : 0;
            }else{
                $result[$key]['order'] = 0;
                $result[$key]['product_total'] = 0;
                $result[$key]['product_order_total'] = 0;
                $result[$key]['customer_total'] = 0;
                $result[$key]['average_customer_total'] = 0;
                $result[$key]['sale_total_price'] = 0;
                $result[$key]['average_sale_price'] = 0;
                $result[$key]['average_sale_customer_price'] = 0;
                $result[$key]['finance_receivable'] = 0;
                $result[$key]['miss_sale_receivable'] = 0;
            }

        }

        return $result;

    }


    public function getSalesTeamReport($params){

        $model = new CompanyOrderService();

        $where = "company_order_product_team.status=1";
        if(isset($params['company_id'])) {
            $where.=" and company_order.company_id=".$params['company_id'];
        }
        if(isset($params['team_product_id'])){
            $where.=" and company_order_product_team.team_product_id =".$params['team_product_id'];
        }

        $start = strtotime($params['startDate']);
        $end = strtotime($params['endDate']);
        if($params['startDate']){
            $where.=" and company_order_product_team.create_time >".$start;
        }
        if($params['endDate']){
            $where.=" and company_order_product_team.create_time <".$end;
        }

        $result = $this->table('company_order_product_team')
            ->join("company_order", 'company_order.company_order_number = company_order_product_team.company_order_number', 'left')
            ->where($where)
            ->field(['company_order_product_team.*'])
            ->select();

        $arr = array();
        foreach ($result as $k=>$v){
            $arr[] = $v['team_product_id'];
//            $result[$k]['customer_total'] =  $this->table('company_order_customer')
//                ->where('status','gt',0)
//                ->where('company_order_number','eq',$v['company_order_number'])
//                ->count();
        }
        $arr = array_unique($arr);

        if($params['team_product_number']){
            $team_product = $this->table('team_product')
                ->join("company", 'company.company_id = team_product.company_id', 'left')
                ->where('team_product_id','IN',$arr)
                ->where('team_product_number','eq',$params['team_product_number'])
                ->field(['team_product.*','company.company_name'])
                ->select();
        }else{
            $team_product = $this->table('team_product')
                ->join("company", 'company.company_id = team_product.company_id', 'left')
                ->where('team_product_id','IN',$arr)
                ->field(['team_product.*','company.company_name'])
                ->select();
        }


        //error_log(print_r(Help::modelDataToArr($team_product),1));
        foreach ($team_product as $key=>$val){
            //订单号列表
            $d = $this->table('company_order_product_team')
                ->where('team_product_id','eq',$val['team_product_id'])
                ->column('company_order_number');
            //收客数
            $e = $this->table('company_order_customer')
                ->where('status','gt',0)
                ->where('company_order_number','IN',$d)
                ->count();
            //产品总数
            //订单号总数
            $c=count($d);
            if(!empty($d)){
                foreach ($d as $k=>$v){
                    $list[$key][$d[$k]] = $model->getCompanyOrderReceivableInfo(['company_order_number'=>$d[$k]]);
                }
            }
            $team_product[$key]['order'] = $d;
            $team_product[$key]['product_order_total'] = $c;
            $team_product[$key]['customer_total'] = $e;
            $team_product[$key]['average_customer_total'] = $e/$c ? : 0;
            $team_product[$key]['sale_total_price'] = array_sum(array_map(function($val){return $val['total'];}, $list[$key])) ? : 0;
            $team_product[$key]['average_sale_price'] = $team_product[$key]['sale_total_price']/$c ? : 0;
            $team_product[$key]['average_sale_customer_price'] = $team_product[$key]['sale_total_price']/$e ? : 0;
            $team_product[$key]['finance_receivable'] = array_sum(array_map(function($val){return $val['finance_receivable'];}, $list[$key])) ? : 0;
            $team_product[$key]['miss_sale_receivable'] = array_sum(array_map(function($val){return $val['miss_sale_receivable'];}, $list[$key])) ? : 0;
        }

        return $team_product;

    }


    public function getSalesReportAgent($params){
        $data = "company_order.status>0";

        if(!empty($params['create_by'])) {
            $userInfo = $this->table("user")->where('nickname','eq',$params['create_by'])->find();
            if($userInfo){
                $data.=" and company_order.create_user_id=".$userInfo['user_id'];
            }
        }

        if(!empty($params['company_id'])) {
            $data.=" and company_order.company_id=".$params['company_id'];
        }
        if(!empty($params['wr'])) {
            $data.=" and company_order.wr=".$params['wr'];
        }

        $start = strtotime($params['startDate']);
        $end = strtotime($params['endDate']);
        if($params['startDate'] && $params['endDate']){
            $where['create_time'] = array(array('gt',$start),array('lt',$end)) ;
        }

        $result = $this->table("company_order")
            ->join('user', 'user.user_id = company_order.create_user_id', 'left')
            ->where($data)
            ->where($where)
            ->field(['company_order.company_order_number,company_order.wr,company_order.create_user_id,user.nickname'])
            ->select();

        foreach($result as $k => $v){
            $names[$v['create_user_id']] = $v['nickname'];
        }
        $names = array_unique($names);

        foreach ($names as $key=>$val){
            $data1[$val.'1']['nickname'] = $val;
            $data1[$val.'1']['wr'] = 1;
            $data1[$val.'1']['user_id'] = $key;
            foreach ($result as $k => $v){
                if($result[$k]['wr'] == 1 && $result[$k]['nickname'] == $val){
                    $data1[$val.'1']['company_order_number'][] = $result[$k]['company_order_number'];
                }
                if($result[$k]['wr'] == 2 && $result[$k]['nickname'] == $val){
                    $data1[$val.'2']['company_order_number'][] = $result[$k]['company_order_number'];
                }
            }
            $data1[$val.'2']['nickname'] = $val;
            $data1[$val.'2']['user_id'] = $key;
            $data1[$val.'2']['wr'] = 2;
        }

        $model = new CompanyOrderService();


        foreach ($data1 as $key=>$val){

            $d = $val['company_order_number'];

            $type = $this->table("company_order_product")
                ->join('branch_product', 'branch_product.branch_product_number = company_order_product.branch_product_number', 'left')
                ->join('branch_product_type','branch_product_type.branch_product_type_id = branch_product.branch_product_type_id','left')
                ->where('company_order_number','IN',$d)
                ->field(['company_order_product.company_order_number,branch_product_type.branch_product_type_name'])
                ->select();

            foreach ($type as $k1=>$v1){
                $data1[$key]['test'][$v1['branch_product_type_name']]['type'] = $v1['branch_product_type_name'];
                //$data1[$key]['test'][$v1['branch_product_type_name']]['company_order_number'][] = $v1['company_order_number'];
                $data1[$key]['test'][$v1['branch_product_type_name']]['customer_total'] += $this->table('company_order_customer')
                    ->where('status','gt',0)
                    ->where('company_order_number','IN',$v1['company_order_number'])
                    ->count();
                $data1[$key]['test'][$v1['branch_product_type_name']]['sale_total_price'] += $model->getCompanyOrderReceivableInfo(['company_order_number'=>$v1['company_order_number']])['total'];
            }


            //收客数
            $e = $this->table('company_order_customer')
                ->where('status','gt',0)
                ->where('company_order_number','IN',$d)
                ->count();
            //订单号总数
            if(!empty($d)){
                foreach ($d as $k=>$v){
                    $list[$key][$d[$k]] = $model->getCompanyOrderReceivableInfo(['company_order_number'=>$d[$k]]);
                }
            }
            $user = $this->table("user")->where('user_id','eq',$val['user_id'])->find();
            $company_name = $this->table("company")->where('company_id','eq',$user['company_id'])->find();
            $data1[$key]['company_name'] = $company_name['company_name'];
            $data1[$key]['customer_total'] = $e;
            $data1[$key]['sale_total_price'] = array_sum(array_map(function($val){return $val['total'];}, $list[$key])) ? : 0;
            //$data1[$key]['test'] = $type;
        }


        return $data1;

    }
}