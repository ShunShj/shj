<?php
namespace app\index\controller;
use app\common\help\Help;
use app\index\model\log\Log;

use app\index\model\publicmodel\Check;
use think\Model;
class Base extends \think\Controller
{
   
    public function __construct()
    {
    	$params = $this->input();
   
    	
    	if($params['appKey']!='nexus' || $params['appSecret']!='nexusIt'){
    		$this->outPutError(['msg' => "appKey or appSecret is error"],$params);
    		exit();
    	}
    	

    	
    	
        parent::__construct();
    }

    /*
     * 验证参数规则
     * 胡
     * 基本用于string number mobile
     */
    public function paramCheckRule($paramRule,$params)
    {
        $language_list = config('systom_setting')['language_list'];

//         foreach($paramRule as $name=>$key){
//             if(strpos($name,'%lang%')){

//                 $name = str_replace('%lang%','',$name);
//                 //查询配置文件默认语言数量
//                 for($i=0;$i<count($language_list);$i++){
//                     $paramRule[$name.'_'.$language_list[$i]] = $key;

//                 }
//                 unset($paramRule[$name.'%lang%']);
//             }else{
//                 $paramRule[$name] = $key;
//             }
//         }



        foreach($paramRule as $name=>$key) {

            switch ($key) {

                case "string": //如果是字符串
                    if (empty($params[$name])) {

                        $this->outPutError(['msg' => "$name is empty"],$params);
                        //\think\Response::create(['code' => '200', 'msg' => 123, 'data' => $params], 'json')->send();
                    }
                    break;
                case 'number'://如果是整型
                    if ( !isset($params[$name]) || !is_numeric($params[$name]) ) {
                        $this->outPutError(['msg' => "$name is empty or must number"],$params);
                        //\think\Response::create(['code' => '200', 'msg' => 123, 'data' => $params], 'json')->send();
                        //$this->outPutError(['msg' => "$key format must number"]);
                    }
                    break;
                case "email":
                	if(empty($params[$name]) || !preg_match("/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/",$params[$name]) ){
                		$this->outPutError(['msg' => "$name is empty or must email"],$params);
                	}
                	break;
                case "array":
                	if(!is_array($params[$name])  ){
                		$this->outPutError(['msg' => "$name isn't array "],$params);
                	}
                	break;
            }
        }
    }
    //错误日志
    protected function outPutError($errorMsg,$params) {
        //插入日志数据库
        $log = new Log("soaerp_log");
        $log->addLog($this->input(),2);
       
        \think\Response::create(['code' => '400', 'msg' =>$errorMsg], 'json')->send();
        
        //防止意外发生
        exit;
    }
    //输出
    public function outPut($result,$msg=null,$count=0) {
    	if(empty($result) && !is_array($result)){
    		$this->outPutError(['msg' => $msg],$this->input());
    		exit();
    		
    	}
    	
        //插入日志数据库
        $log = new Log("soaerp_log");
        $log->addLog($this->input(),1,$result);
        if(empty($result)){
            $result = [];
        }
        \think\Response::create(['code' => '200', 'msg'=>'success','data' => $result,'data_count'=>$count], 'json')->send();
        //防止意外发生
        exit;
    }
    protected function input() {
    	
        $contents = file_get_contents("php://input");
	
        $param = json_decode($contents, TRUE);
        
//         foreach($param as $key=>$v){
//         	$param[$key] = htmlspecialchars($v);
//         }

        if(isset($param['lang'])){
            $this->_lang = $param['lang'];
        }else{
            $this->_lang = config('systom_setting')['language_default'];
        }
        $log = new Log("soaerp_log");

        if (empty($param)){
            \think\Response::create(['code' => '400', 'msg' => 'json error'], 'json')->send();
            exit();
        }

        return $param;
    }
    /**
     * 判断是否重复根据传递来的参数
     */
    protected function checkNameIsRepetition($table_name,$data){
		$check = new Check();
		$check_result = $check->checkNameIsRepetition($table_name,$data);
		
		if($check_result>0){
			\think\Response::create(['code' => '400', 'msg' => 'field is repetition /字段不能重复'], 'json')->send();
			exit();
		}
		

    }
}
