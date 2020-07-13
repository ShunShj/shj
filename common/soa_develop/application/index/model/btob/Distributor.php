<?php

namespace app\index\model\Btob;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class Distributor extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'distributor';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }

    /**
     * 添加经销商
     * 胡
     */
    public function addBtoBDistributor($params){

        $t = time();
       
        $data['company_id'] = $params['company_id'];
        $data['distributor_name'] = $params['distributor_name'];
        $data['distributor_type'] = $params['distributor_type'];
        $data['distributor_code'] = $params['distributor_code'];
        if(isset($params['distributor_name_chinese'])){
            $data['distributor_name_chinese'] = $params['distributor_name_chinese'];
        }
        if(isset($params['tcf'])){
            $data['tcf'] = $params['tcf'];
        }
        if(isset($params['licence'])){
            $data['licence'] = $params['licence'];
        }
        if(isset($params['abn'])){
            $data['abn'] = $params['abn'];
        }
        if(isset($params['manager'])){
            $data['manager'] = $params['manager'];
        }
        if(isset($params['accountant'])){
            $data['accountant'] = $params['accountant'];
        }
        if(isset($params['emailone'])){
            $data['emailone'] = $params['emailone'];
        }
        if(isset($params['emailtwo'])){
            $data['emailtwo'] = $params['emailtwo'];
        }


        if(isset($params['emailthree'])){
            $data['emailthree'] = $params['emailthree'];
        }
        if(isset($params['phone'])){
            $data['phone'] = $params['phone'];
        }
        if(isset($params['fax'])){
            $data['fax'] = $params['fax'];
        }
        if(isset($params['emergency_contact'])){
            $data['emergency_contact'] = $params['emergency_contact'];
        }
        if(isset($params['website'])){
            $data['website'] = $params['website'];
        }
        if(isset($params['logo'])){
            $data['logo'] = $params['logo'];
        }


        if(isset($params['addressone'])){
            $data['addressone'] = $params['addressone'];
        }
        if(isset($params['addresstwo'])){
            $data['addresstwo'] = $params['addresstwo'];
        }
        if(isset($params['suburb'])){
            $data['suburb'] = $params['suburb'];
        }
        if(isset($params['state'])){
            $data['state'] = $params['state'];
        }
        if(isset($params['postcode'])){
            $data['postcode'] = $params['postcode'];
        }
        if(isset($params['country'])){
            $data['country'] = $params['country'];
        }


        if(isset($params['bankname'])){
            $data['bankname'] = $params['bankname'];
        }
        if(isset($params['bsb'])){
            $data['bsb'] = $params['bsb'];
        }
        if(isset($params['account_number'])){
            $data['account_number'] = $params['account_number'];
        }
        if(isset($params['account_name'])){
            $data['account_name'] = $params['account_name'];
        }
        if(isset($params['swift_code'])){
            $data['swift_code'] = $params['swift_code'];
        }
        if(isset($params['username'])){
            $data['username'] = $params['username'];
        }
        if(isset($params['password']) && !empty($params['password'])){
            $data['password'] = $params['password'];
        }
        if(isset($params['is_commission'])){
            $data['is_commission'] = $params['is_commission'];
        }
        if(isset($params['is_latest_news'])){
            $data['is_latest_news'] = $params['is_latest_news'];
        }

        $data['part'] = 2;
        $data['create_time'] = $t;
        $data['create_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        $data['update_user_id'] = $params['user_id'];
        $data['status'] = $params['status'];




        Db::startTrans();
        try{
            $result = Db::name('distributor')->insertGetId($data);
  
       
            // 提交事务
            Db::commit();

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            Db::rollback();

        }

        return $result;
    }

    public function getBtoBDistributor($params,$is_count=false,$is_page=false,$page=null,$page_size=20){


        $data = "distributor.part = 2";
        if(!empty($params['distributor_id'])){
            $data.= " and distributor.distributor_id = ".$params['distributor_id'];
        }
        if(!empty($params['distributor_type'])){
            $data.= " and distributor.distributor_type = ".$params['distributor_type'];
        }
        if($params['status']<2 && isset($params['status'])){
            $data.= " and distributor.status = ".$params['status'];
        }
        if(!empty($params['distributor_name'])){
            $data.= " and distributor.distributor_name like'%".$params['distributor_name']."%'";
        }
        if(!empty($params['choose_company_id'])){
            $data.= " and distributor.company_id = '".$params['choose_company_id']."'";
        }

        if($is_count==true){
            $result = $this->table("distributor")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("distributor")
                    ->join('user','user.user_id = distributor.update_user_id', 'left')
                    ->join('distributor_type','distributor_type.distributor_type_id = distributor.distributor_type', 'left')
                    ->where($data)
                    ->limit($page, $page_size)
                    ->order('create_time desc')
                    ->field(['distributor.*','user.nickname','distributor_type.distributor_type_name'])
                    ->select();
            }else{
                $result = $this->table("distributor")
                    ->join('distributor_type','distributor_type.distributor_type_id = distributor.distributor_type', 'left')
                    ->where($data)
                    ->order('create_time desc')
                    ->field(['distributor.*','distributor_type.distributor_type_name'])
                    ->select();
            }
        }

        return $result;

    }


    /**
     * 修改经销商 根据distributor_id
     */
    public function updateBtoBDistributorByDistributorId($params){

        $t = time();

        $data['company_id'] = $params['company_id'];
        $data['distributor_name'] = $params['distributor_name'];
        $data['distributor_type'] = $params['distributor_type'];
        $data['distributor_code'] = $params['distributor_code'];
        if(isset($params['distributor_name_chinese'])){
            $data['distributor_name_chinese'] = $params['distributor_name_chinese'];
        }
        if(isset($params['tcf'])){
            $data['tcf'] = $params['tcf'];
        }
        if(isset($params['licence'])){
            $data['licence'] = $params['licence'];
        }
        if(isset($params['abn'])){
            $data['abn'] = $params['abn'];
        }
        if(isset($params['manager'])){
            $data['manager'] = $params['manager'];
        }
        if(isset($params['accountant'])){
            $data['accountant'] = $params['accountant'];
        }
        if(isset($params['emailone'])){
            $data['emailone'] = $params['emailone'];
        }
        if(isset($params['emailtwo'])){
            $data['emailtwo'] = $params['emailtwo'];
        }
        if(isset($params['emailthree'])){
            $data['emailthree'] = $params['emailthree'];
        }
        if(isset($params['phone'])){
            $data['phone'] = $params['phone'];
        }
        if(isset($params['fax'])){
            $data['fax'] = $params['fax'];
        }
        if(isset($params['emergency_contact'])){
            $data['emergency_contact'] = $params['emergency_contact'];
        }
        if(isset($params['website'])){
            $data['website'] = $params['website'];
        }
        if(isset($params['logo'])){
            $data['logo'] = $params['logo'];
        }
        if(isset($params['addressone'])){
            $data['addressone'] = $params['addressone'];
        }
        if(isset($params['addresstwo'])){
            $data['addresstwo'] = $params['addresstwo'];
        }
        if(isset($params['suburb'])){
            $data['suburb'] = $params['suburb'];
        }
        if(isset($params['state'])){
            $data['state'] = $params['state'];
        }
        if(isset($params['postcode'])){
            $data['postcode'] = $params['postcode'];
        }
        if(isset($params['country'])){
            $data['country'] = $params['country'];
        }
        if(isset($params['bankname'])){
            $data['bankname'] = $params['bankname'];
        }
        if(isset($params['bsb'])){
            $data['bsb'] = $params['bsb'];
        }
        if(isset($params['account_number'])){
            $data['account_number'] = $params['account_number'];
        }
        if(isset($params['account_name'])){
            $data['account_name'] = $params['account_name'];
        }
        if(isset($params['swift_code'])){
            $data['swift_code'] = $params['swift_code'];
        }
        if(isset($params['username'])){
            $data['username'] = $params['username'];
        }
        if(isset($params['password']) && !empty($params['password'])){
            $data['password'] = $params['password'];
        }
        if(isset($params['is_commission'])){
            $data['is_commission'] = $params['is_commission'];
        }
        if(isset($params['is_latest_news'])){
            $data['is_latest_news'] = $params['is_latest_news'];
        }
        $data['part'] = 2;
        $data['status'] = $params['status'];
        $data['update_user_id'] = $params['user_id'];
        $data['update_time'] = $t;

        Db::startTrans();
        try{
            Db::name('distributor')->where("distributor_id = ".$params['distributor_id'])->update($data);

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

    public function getDistributorByDistributorId($distributor_id)
    {
        return $this->table("distributor")->alias("distributor")->where(['distributor_id' => $distributor_id])->find();
    }



    public function addDistributor($params){

        $t = time();

        $data['company_id'] = $params['company_id'];
        $data['distributor_name'] = $params['distributor_name'];
        $data['associate_type'] = $params['associate_type'];
        if(isset($params['tel'])){
            $data['tel'] = $params['tel'];
        }
        if(isset($params['contect'])){
            $data['contect'] = $params['contect'];
        }
        if(isset($params['language_id'])){
            $data['language_id'] = $params['language_id'];
        }
        if(isset($params['address'])){
            $data['address'] = $params['address'];
        }
        if(isset($params['zip_code'])){
            $data['zip_code'] = $params['zip_code'];
        }
        if(isset($params['city_id'])){
            $data['city_id'] = $params['city_id'];
        }
        if(isset($params['email'])){
            $data['email'] = $params['email'];
        }
        $data['part'] = 1;
        $data['create_time'] = $t;
        $data['create_user_id'] = $params['user_id'];
        $data['update_time'] = $t;
        $data['update_user_id'] = $params['user_id'];
        $data['status'] = $params['status'];




        Db::startTrans();
        try{
            $result = Db::name('distributor')->insertGetId($data);


            // 提交事务
            Db::commit();

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            Db::rollback();

        }

        return $result;
    }

    /**
     * 获取经销商数据
     * 胡
     */
    public function getDistributor($params,$is_count=false,$is_page=false,$page=null,$page_size=20){


        $data = "distributor.part = 1";
        if(!empty($params['distributor_id'])){
            $data.= " and distributor.distributor_id = ".$params['distributor_id'];
        }
        if(is_numeric($params['status'])){
            $data.= " and distributor.status = ".$params['status'];
        }
        if(!empty($params['distributor_name'])){
            $data.= " and distributor.distributor_name like'%".$params['distributor_name']."%'";
        }
        if(!empty($params['company_id'])){
            $data.= " and distributor.company_id = '".$params['company_id']."'";
        }


        if($is_count==true){
            $result = $this->table("distributor")->alias("distributor")->where($data)->count();
        }else {
            if ($is_page == true) {
                $result = $this->table("distributor")->alias("distributor")->
                join("country country", "distributor.city_id = country.country_id", 'left')->
                join("company company", "company.company_id = distributor.company_id", 'left')->
                join("language language", "distributor.language_id = language.language_id", 'left')->
                where($data)->limit($page, $page_size)->order('create_time desc')->
                field(['distributor.distributor_id', 'distributor.distributor_name','distributor.distributor_code',
                    'distributor.tel', 'distributor.contect', 'distributor.associate_type',
                    'distributor.company_id', 'company.company_name',

                    'distributor.language_id', 'language.language_name', 'distributor.zip_code', 'distributor.address',
                    'distributor.email', 'country.country_id as city_id', 'country.country_name as city_name', 'country.pid as city_pid',
                    "(select country_name  from country as country_province where country_province.country_id= city_pid)" => 'province_name',
                    "(select country_id  from country as country_province where country_province.country_id= city_pid)"   => 'province_id',
                    "(select pid  from country as country_province where country_province.country_id= city_pid)"          => 'province_pid',
                    "(select country_name  from country as country3 where country3.country_id= province_pid)"             => 'country_name',
                    "(select country_id  from country as country3 where country3.country_id= province_pid)"               => 'country_id',
                    "(select nickname  from user where user.user_id = distributor.create_user_id)"                        => 'create_user_name',
                    "(select nickname  from user where user.user_id = distributor.update_user_id)"                        => 'update_user_name',
                    'distributor.create_user_id', 'distributor.create_time', 'distributor.update_user_id',
                    'distributor.update_time', 'distributor.status'])->

                select();
            }else{
                $result = $this->table("distributor")->alias("distributor")->
                join("country country", "distributor.city_id = country.country_id", 'left')->
                join("company company", "company.company_id = distributor.company_id", 'left')->
                join("language language", "distributor.language_id = language.language_id", 'left')->
                where($data)->order('create_time desc')->
                field(['distributor.distributor_id', 'distributor.distributor_name','distributor.distributor_code',
                    'distributor.tel', 'distributor.contect', 'distributor.associate_type',
                    'distributor.company_id', 'company.company_name',

                    'distributor.language_id', 'language.language_name', 'distributor.zip_code', 'distributor.address',
                    'distributor.email', 'country.country_id as city_id', 'country.country_name as city_name', 'country.pid as city_pid',
                    "(select country_name  from country as country_province where country_province.country_id= city_pid)" => 'province_name',
                    "(select country_id  from country as country_province where country_province.country_id= city_pid)"   => 'province_id',
                    "(select pid  from country as country_province where country_province.country_id= city_pid)"          => 'province_pid',
                    "(select country_name  from country as country3 where country3.country_id= province_pid)"             => 'country_name',
                    "(select country_id  from country as country3 where country3.country_id= province_pid)"               => 'country_id',
                    "(select nickname  from user where user.user_id = distributor.create_user_id)"                        => 'create_user_name',
                    "(select nickname  from user where user.user_id = distributor.update_user_id)"                        => 'update_user_name',
                    'distributor.create_user_id', 'distributor.create_time', 'distributor.update_user_id',
                    'distributor.update_time', 'distributor.status'])->

                select();
            }
        }




        return $result;

    }


    /**
     * 修改经销商 根据distributor_id
     */
    public function updateDistributorByDistributorId($params){

        $t = time();

        if(!empty($params['company_id'])){
            $data['company_id'] = $params['company_id'];

        }
        if(!empty($params['distributor_name'])){
            $data['distributor_name'] = $params['distributor_name'];

        }
        if(!empty($params['associate_type'])){
            $data['associate_type'] = $params['associate_type'];

        }
        if(isset($params['tel'])){
            $data['tel'] = $params['tel'];
        }
        if(isset($params['contect'])){
            $data['contect'] = $params['contect'];
        }
        if(isset($params['language_id'])){
            $data['language_id'] = $params['language_id'];
        }
        if(isset($params['address'])){
            $data['address'] = $params['address'];
        }
        if(isset($params['zip_code'])){
            $data['zip_code'] = $params['zip_code'];
        }
        if(isset($params['city_id'])){
            $data['city_id'] = $params['city_id'];
        }
        if(isset($params['email'])){
            $data['email'] = $params['email'];
        }
        if(isset($params['status'])){
            $data['status'] = $params['status'];

        }

        $data['part'] = 1;
        $data['update_user_id'] = $params['user_id'];

        $data['update_time'] = $t;




        Db::startTrans();
        try{
            Db::name('distributor')->where("distributor_id = ".$params['distributor_id'])->update($data);

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