<?php

namespace app\index\model\system;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class Tax extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'tax';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加税点模板
     * 韩
     */
    public function addTax($params){
        $t = time();

        $data['company_id'] = $params['choose_company_id'];
        $data['txcd'] = $params['txcd'];
        $data['gstrate'] = $params['gstrate'];
        $data['pstrate'] = $params['pstrate'];
        $data['hstrate'] = $params['hstrate'];
		$data['otx'] = $params['otx'];
        $data['note'] = $params['note'];

        $data['create_time'] = $t;
        $data['create_user_id'] = $params['now_user_id'];
        $data['update_time'] = $t;
        $data['update_user_id'] = $params['now_user_id'];
        $data['status'] = 1;


        Db::startTrans();
        try{
            $result = Db::name('tax')->insertGetId($data);


            // 提交事务
            Db::commit();

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            Db::rollback();
            //\think\Response::create(['code' => '400', 'msg' =>$result], 'json')->send();
            //exit();

        }

        return $result;
    }

    /**
     * 获取税点模板
     * 韩
     */
    public function getTax($params,$is_count=false,$is_page=false,$page=null,$page_size=20){//第一个为参数，第二个为是否要获取 总数


        $data = "1=1 ";
        if(!empty($params['company_id'])){
            $data.= " and tax.company_id= ".$params['company_id'];
        }
        if(!empty($params['txcd'])){
            $data.= " and tax.txcd= ".$params['txcd'];
        }
        if(!empty($params['gstrate'])){
            $data.= " and tax.gstrate= ".$params['gstrate'];
        }
        if(!empty($params['pstrate'])){
            $data.= " and tax.pstrate= ".$params['pstrate'];
        }
        if(!empty($params['hstrate'])){
            $data.= " and tax.hstrate= ".$params['hstrate'];
        }
        if(!empty($params['note'])){
            $data.= " and tax.note= ".$params['note'];
        }
        if(is_numeric($params['status'])){
            $data.= " and tax.status = ".$params['status'];
        }

        if(!empty($params['tax_id'])){
            $data.= " and tax.tax_id = '".$params['tax_id']."'";
        }


        if($is_count==true){
            $result = $this->where($data)->count();

        }else{
            if($is_page == true){
                $result = $this->table("tax")->alias('tax')->
                where($data)->limit($page, $page_size)->
                field(['tax.tax_id',"tax.company_id","tax.txcd","tax.gstrate","tax.pstrate","tax.hstrate",'tax.otx',"tax.note",
                	"(select company_name from company where tax.company_id = company.company_id) as company_name",
                    "(select nickname  from user where user.user_id = tax.create_user_id)"=>'create_user_name',
                    "(select nickname  from user where user.user_id = tax.update_user_id)"=>'update_user_name',
                    'tax.create_time','tax.update_time',
                    'tax.create_user_id','tax.update_user_id','tax.status'

                ])->order("create_time desc")->select();


            }else{
                $result = $this->table("tax")->alias('tax')->

                where($data)->

                field(['tax.tax_id',"tax.company_id","tax.txcd","tax.gstrate","tax.pstrate","tax.hstrate",'tax.otx',"tax.note",
                	"(select company_name from company where tax.company_id = company.company_id) as company_name",
                    "(select nickname  from user where user.user_id = tax.create_user_id)"=>'create_user_name',
                    "(select nickname  from user where user.user_id = tax.update_user_id)"=>'update_user_name',
                    'tax.create_time','tax.update_time',
                    'tax.create_user_id','tax.update_user_id','tax.status'

                ])->order("create_time desc")->select();
            }

        }


        return $result;

    }


    /**
     * 修改税点模板 根据tax_id
     * 韩
     */
    public function updateTaxByTaxId($params){

        $t = time();

        if(!empty($params['choose_company_id'])){
            $data['company_id'] = $params['choose_company_id'];
        }

        if(!empty($params['txcd'])){
            $data['txcd'] = $params['txcd'];

        }

        if(!empty($params['gstrate'])){
            $data['gstrate'] = $params['gstrate'];

        }

        if(!empty($params['pstrate'])){
            $data['pstrate'] = $params['pstrate'];

        }

        if(!empty($params['hstrate'])){
            $data['hstrate'] = $params['hstrate'];

        }
        if(!empty($params['otx'])){
            $data['otx'] = $params['otx'];

        }
        if(!empty($params['note'])){
            $data['note'] = $params['note'];

        }

        if(is_numeric($params['status'])){
            $data['status'] = $params['status'];

        }


        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;




        Db::startTrans();
        try{
            Db::name('tax')->where("tax_id = ".$params['tax_id'])->update($data);

            $result = 1;
            // 提交事务
            Db::commit();

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            Db::rollback();

        }
        return $result;
    }
}