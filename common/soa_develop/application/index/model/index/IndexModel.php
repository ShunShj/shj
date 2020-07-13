<?php

namespace app\index\model\index;
use think\Model;
use think\config;
use app\common\help\Help;
use think\Db;
class IndexModel extends Model{
    protected $autoWriteTimestamp = true;
    public function initialize()
    {
        parent::initialize();
    }

    public function updateStatusByTableId($params){
        if(isset($params['status'])) {
            $data['status'] = $params['status'];
        }

        $a = $params['table_name'];
        $b = $params['table_id_name'];
        $c = $params['table_id'];
        Db::startTrans();
        try{

            Db::name("$a")->where(" $b = '".$c."'")->update($data);
            //Db::name("$a")->where("$b =".$c)->update($data);
//            $sql = Db::table("$a")->getLastSql();
//            error_log(print_r(Help::modelDataToArr($sql),1));
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