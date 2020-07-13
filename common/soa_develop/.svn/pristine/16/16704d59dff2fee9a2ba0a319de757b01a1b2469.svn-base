<?php

namespace app\index\model\system;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class Language extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'language';
    private $_languageList;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        parent::initialize();

    }
//    public function __construct()
//    {
//        $this->_languageList = config('systom_setting')['language_list'];
//        parent::__construct();
//    }

    /**
     * 添加语言
     * 胡
     */
    public function addLanguage($params){

        $t = time();
//         $data_zh_cn['create_user_id'] = $params['user_id'];
//         $data_zh_cn['name'] = $params['name_zh-cn'];

//         $data_zh_cn['create_time'] = $t;
//         $data_zh_cn['status'] = 1;

//         $data_en_us['create_user_id'] = $params['user_id'];
//         $data_en_us['name'] = $params['name_en-us'];

//         $data_en_us['create_time'] = $t;
//         $data_en_us['status'] = 1;
       
       $data['language_name'] = $params['language_name'];
       $data['status'] = $params['status'];
       $data['create_user_id'] = $params['user_id'];
       $data['create_time'] = $t;
       $data['update_user_id'] = $params['user_id'];
       $data['update_time'] = $t;

        Db::startTrans();
        try{
            Db::name('language')->insert($data);
            //Db::name("language_en-us")->insert($data_en_us);
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


    /**
     * 获取语言数据
     * 胡
     */
    public function getLanguage($params,$is_count=false){//第一个为参数，第二个为是否要获取 总数
   
    	$data = [];
    	if(!empty($params['language_id'])){
    		$data['language_id'] = $params['language_id'];
    	}
    	if(!empty($params['status'])){
    		$data['status'] = $params['status'];
    	}
    	if(!empty($params['language_name'])){
    		$data['language_name'] = ['like', "%".$params['language_name']."%"];
    	}
		
    	if($is_count==true){
    		$result = $this->where($data)->count();
    		 
    	}else{
    		if(isset($params['page'])){
       			$result =   $this->table("language")->alias('language')->
       						where($data)->limit($params['page'],$params['page_size'])->
            
		            		field(['language.language_id',"language.language_name",            		
		            		'language.create_time',
		            		"(select nickname  from user where user.user_id = language.create_user_id)"=>'create_user_name',
		            		"(select nickname  from user where user.user_id = language.update_user_id)"=>'update_user_name',
		            		'language.update_time',"language.status",
		            		])->order('create_time desc')->select();
    			 
    	
    		}else{
       			$result = $this->table("language")->alias('language')->where($data)->
            
		            		field(['language.language_id',"language.language_name",            		
		            		'language.create_time',
		            		"(select nickname  from user where user.user_id = language.create_user_id)"=>'create_user_name',
		            		"(select nickname  from user where user.user_id = language.update_user_id)"=>'update_user_name',
		            		'language.update_time',"language.status",
		            		])->order('create_time desc')->select();
    	
    		}
    		 
    	}    	
    	

    	
    	return $result;

    }

    /**
     * 修改语言 根据language_id
     */
    public function updateLanguageByLanguageId($params){

        $t = time();
        if(!empty($params['status'])){
            $data['status'] = $params['status'];

        }

        if(!empty($params['language_name'])){
            $data['language_name'] = $params['language_name'];


        }

	
        $data['update_user_id'] = $params['user_id'];

        $data['update_time'] = $t;




        Db::startTrans();
        try{
            Db::name('language')->where("language_id = ".$params['language_id'])->update($data);
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

    /**
     * getOneLanguage
     *
     * 获取一条语言信息
     * @author shj
     *
     * @param $language_id
     *
     * @return array
     * Date: 2019/2/27
     * Time: 17:41
     */
    public function getOneLanguage($language_id){
        $result = $this->table("language")->where(['language_id' => $language_id])->find();
        return $result;
    }
}