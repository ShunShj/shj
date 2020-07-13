<?php
namespace app\index\model\multilingual;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class Multilingual extends Model{
	
	private $_languageList;

    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize(); 
    }

    /****
    * 语言模糊查询
    */
    public function languageFuzzyQuery($params){
       $where['original_table_name'] = $params['original_table_name']; 
       $where['original_table_field_name'] = $params['original_table_field_name']; 
       $where['translate'] = ['like',"%{$params['translate']}%"]; 
       $where['language_id'] = $params['language_id'];
       $where['status'] = 1;
       
       $ar = [];
       $original_table_id =  $this->table("multilingual")->field("original_table_id")->where($where)->select();
       if(!empty($original_table_id)){
            foreach ($original_table_id as $key => $value) {
               $ar[] =  $value['original_table_id']; 
            } 
       } 
       return implode(',', $ar);
      //return $this->table("multilingual")->getLastSql();
    }


    /*
	* 添加/编辑多语言
	*/
    public function editMultilingual($params){
    	
    	$d['original_table_name'] = $params['original_table_name'];
    	$d['original_table_field_name'] = $params['original_table_field_name'];
    	$d['original_table_id'] = $params['original_table_id'];
    	$d['translate'] = $params['translate'];
    	$d['language_id'] = $params['language_id'];
    	$d['operation_id'] = $params['operation_id'];
    	$d['operation_time'] = time();
    	$d['status'] = $params['status'];

    	if($params['multilingual_id']){
    		$where['multilingual_id'] = $params['multilingual_id'];
    		$return = Db::table('multilingual')->where($where)->update($d);
    	}else{
    		Db::table('multilingual')->insert($d);
			$return = Db::name('multilingual')->getLastInsID(); 
    	}

    	return $return;
    }

    /**
    * 获取对应的语言
    */
    public function getMultilingual($params){
    	$where['original_table_name'] = $params['original_table_name'];
    	$where['original_table_field_name'] = $params['original_table_field_name'];
    	$where['original_table_id'] = $params['original_table_id'];

    	if($params['language_id']){
    		$where['language_id'] = $params['language_id'];
    	}
    	if($params['operation_id']){
    		$where['operation_id'] = $params['operation_id'];
    	}
    	if($params['status']){
    		$where['status'] = $params['status'];
    	}

    	$return = $this->table('multilingual')->field([
    		'multilingual.multilingual_id',
    		'multilingual.original_table_name',
    		'multilingual.original_table_field_name',
    		'multilingual.original_table_id',
    		'multilingual.language_id',
    		'multilingual.translate',
    		'operation_id',
    		"(select language.language_name from language where language.language_id=multilingual.language_id) as language_name",
    		'multilingual.operation_id',
    		"(select nickname from user where user.user_id=multilingual.operation_id) as operation_name",
    		'multilingual.status',
    	])->where($where)->select();

    	return $return;

    }

    /*
    * 获取所有多语言
    */
    public function selMultilingualAll($params){
    	$where['multilingual.status'] = 1;
    	$return = $this->table('multilingual')->field([
    		'multilingual.multilingual_id',
    		'multilingual.original_table_name',
    		'multilingual.original_table_field_name',
    		'multilingual.original_table_id',
    		'multilingual.language_id',
    		'multilingual.translate',
    		'operation_id',
    		"(select language.language_name from language where language.language_id=multilingual.language_id) as language_name",
    		'multilingual.operation_id',
    		"(select nickname from user where user.user_id=multilingual.operation_id) as operation_name",
    		'multilingual.status',
    	])->where($where)->select();
    	// var_dump($return);exit;

    	return $return;	
    }



}