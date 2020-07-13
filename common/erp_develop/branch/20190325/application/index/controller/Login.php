<?php
namespace app\index\controller;
use app\common\help\Help;
use think\Controller;
use think\Session;

use think\config;
class Login extends Controller
{
	protected $_soaerpConfig;
    /**
     * 
     * 登陆显示
     * 
     */
    public function show(){
		return $this->fetch();
    }
    
    /**
     * 验证登陆
     * 胡
     * 
     */
    public function checkLogin(){
    	$username  = input('username');
    	$password = input('password');
    	$data = [
    		'username'=>$username,
    		'password'=>$password,
    		'ip'=>$_SERVER['REMOTE_ADDR']
    			
    	];
  
    	$result = Help::callSoaErp('post','/user/login',$data);
    	if($result['code']==200){
    		Session::set('user',$result['data'][0]);
    		
    		$role_data['role_id'] = $result['data'][0]['role_id'];
    		$result =  $this->callSoaErp('post','/system/getAuthConfigByRoleId',$role_data);//getSingleSource
    	
     		$auth_result = $result['data'];
//     		$level1_data =[];
//     		for($i=0;$i<count($auth_result);$i++){
//     			if($auth_result[$i]['level']==1){
    					
//     				$level1_data[] = $auth_result[$i];
//     			}else{
//     				for($j=0;$j<count($level1_data);$j++){
//     					if($level1_data[$j]['auth_id'] == $auth_result[$i]['pid']){
    		
//     						$level1_data[$j]['children_auth'][] =  $auth_result[$i];
    		
//     						break;
//     					}
    						
//     				}
    		
//     			}
//     		}
    		Session::set('authConfig',$auth_result);
    	}
    	return $result;
    	
    }

	/**
	 * 退出登陆
	 */
    public function loginOut(){
    	
    	Session::delete('user');
    	echo "<script>location.href='/login/show'</script>";
    }
    
    public function test(){
    
    }
    /**
     * 读取soaerp方法
     */
    public function callSoaErp($method,$function,$data=[]){
    
    	$data['appKey'] = 'nexus';
    	$data['appSecret']='nexusIt';
    	$data['lang_id']  = session('user')['language_id'];
    	$data['user_company_id']  = session('user')['company_id'];
    	$this->_soaerpConfig= config('soaerp');
    	
    	$this->_soaerpUrl = $this->_soaerpConfig['ip'].':'.$this->_soaerpConfig['port'];
    	$result = Help::http($method,$this->_soaerpUrl.$function,$data);
    
    	//dump($result);
    	$result = json_decode($result,true);
    	//dump($result);
    	if($result['code']==200){
  
    		$result = ['code' => '200', 'msg' => 'success','data'=>$result['data']];
    
    	}else{
    		//$this->outPutError($result);
    		$result = ['code' => '400', 'msg' => $result['msg']];
    	}
    	return $result;
    }    
}
