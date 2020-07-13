<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/15
 * Time: 14:33
 */

namespace app\index\model\develop;

use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
use app\index\model\system\Currency;
use app\index\model\system\Language;
use app\index\model\system\Country;
use app\index\model\system\Company;
use app\index\model\system\Department;
use app\index\model\system\Job;
use app\index\model\product\TeamProduct;
use app\index\model\product\TeamProductReturnReceipt;
use app\index\model\product\TeamProductJourney;
use app\index\model\system\User;
use app\index\model\branchcompany\CompanyOrder;
use app\index\model\branchcompany\CompanyOrderProduct;
use app\index\model\branchcompany\CompanyOrderProductTemplate;
use app\index\model\branchcompany\CompanyOrderProductTeam;
use think\Controller;
class Importing extends Model{

   // protected $connection = ['database' => 'erp'];
    protected $table = 'importing';
    private $_languageList;
    private $_currency;
    private $_language;
    private $_country;
    private $_company;
    private $_department;
    private $_job;
    private $_team_product;
    private $_user;
	private $_team_product_return_receipt;
	private $_team_product_journey;
    public function initialize()
    {
        $this->_languageList = config('systom_setting')['language_list'];
        $this->_currency = new  Currency();
        $this->_language = new  Language();
        $this->_country = new  Country();
        $this->_company = new  Company();
        $this->_department = new  Department();
        $this->_job = new  Job();
        $this->_team_product = new TeamProduct();
        $this->_user = new User();
        $this->_team_product_return_receipt = new TeamProductReturnReceipt();
        $this->_team_product_journey = new TeamProductJourney();
        parent::initialize();

    }
	
    
    
    /**
     * 获取导入数据
     */
    public function getImporting(){
    	
    	return $this->select();
    }
    
    /**
     * 添加应收
     * 韩
     */
    public function addCope($params){
        $t = time();
        $user_id = $params['now_user_id'];
        $data['cope_number'] = $params['cope_number'];
        $data['receivable_company_id'] = $params['receivable_company_id'];
        $data['source_type_id'] = $params['source_type_id'];
        $data['product_name'] = $params['product_name'];
        $data['invoice_number'] = $params['invoice_number'];
        $data['cope_money'] = $params['cope_money'];
        $data['invoice_time'] = $params['invoice_time'];
        $data['cope_currency_id'] = $params['cope_currency_id'];

        if(isset($params['remark'])){
            $data['remark'] = $params['remark'];
        }
        $data['resource_type']= 1;//手动添加

        $data['create_time'] = $t;
        $data['create_user_id'] = $user_id;
        $data['update_time'] = $t;
        $data['update_user_id'] = $params['now_user_id'];
        $data['status'] = $params['status'];

        $this->startTrans();
        try{
            $pk_id = $this->insertGetId($data);
            $pk_number = $data['cope_number'];

            // 提交事务
            $this->commit();
            $result = $pk_number;

        } catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            $this->rollback();

        }

        return $result;
    }
    /**
     * 货币导入功能
     */   
    public function addCurrency($params){
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$data = $params['data'];
    
    	$sql="insert into currency(currency_name,symbol,unit,create_time,create_user_id,update_time,update_user_id,status) values";
    	$k=0;
    	$this->execute("truncate currency");
    	for($i=0;$i<count($data);$i++){
    		$currency_name = $data[$i]['currency_name'];
    		$symbol = $data[$i]['symbol'];
    		$unit = $data[$i]['unit'];
    		$sql.="('$currency_name','$symbol','$unit',$t,$user_id,$t,$user_id,1),";
    		if($k==10){
    			$sql = trim($sql,',');
    			$this->table('currency')->execute($sql);
    			$k=0;
    			$sql="insert into currency(currency_name,symbol,unit,create_time,create_user_id,update_time,update_user_id,status) values";
    			 
    		}
    		$k++;
    	}
    	if($k>0 && strlen($sql)>0){
    		$sql = trim($sql,',');
    		$this->table('currency')->execute($sql);
    	}    	 
    	return 1;
    	 
    }
    //语言导入功能
    public function addLanguage($params){
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$data = $params['data'];

    	$sql="insert into language(language_name,create_time,create_user_id,update_time,update_user_id,status) values";
    	$k=0;
    	$this->execute("truncate language");
    	for($i=0;$i<count($data);$i++){
    		$language_name = $data[$i]['language_name'];

    		$sql.="('$language_name',$t,$user_id,$t,$user_id,1),";
    		if($k==10){
    			$sql = trim($sql,',');
    			$this->table('language')->execute($sql);
    			$k=0;
    			$sql="insert into language(language_name,create_time,create_user_id,update_time,update_user_id,status) values";
    			 
    		}
    		$k++;
    	}
    	if($k>0 && strlen($sql)>0){
    		$sql = trim($sql,',');
    		$this->table('language')->execute($sql);
    	}
    	return 1;
    
    }
    //国家导入功能
    public function addCountry($params){
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$data = $params['data'];
    
    	$sql="insert into country(pid,country_name,country_code,level,create_time,create_user_id,update_time,update_user_id,status) values";
    	$k=0;
    	$this->execute("truncate country");
    	for($i=0;$i<count($data);$i++){
    		$country_name = $data[$i]['country_name'];
    		$country_code = $data[$i]['country_code'];
    		$sql.="(0,'$country_name','$country_code',1,$t,$user_id,$t,$user_id,1),";
    		if($k==10){
    			$sql = trim($sql,',');
    			$this->table('country')->execute($sql);
    			$k=0;
    			$sql="insert into country(pid,country_name,country_code,level,create_time,create_user_id,update_time,update_user_id,status) values";
    			 
    		}
    		$k++;
    	}
    	if($k>0 && strlen($sql)>0){
    		$sql = trim($sql,',');
    		$this->table('country')->execute($sql);
    	}
    	return 1;
    
    }
    //公司导入功能
    public function addCompany($params){
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$data = $params['data'];
    	
    	$sql="insert into company(company_name,country_id,currency_id,language_id,create_time,create_user_id,update_time,update_user_id,status) values";
    	$k=0;
    	$this->execute("truncate company");
    	for($i=0;$i<count($data);$i++){
    		$company_name = $data[$i]['company_name'];
    		$currency_name = $data[$i]['currency_name'];
    		$country_name = $data[$i]['country_name'];
    		$language_name = $data[$i]['language_name'];
    		$language_result = $this->_language->getLanguage(['language_name'=>$language_name]);
  			$language_id = $language_result[0]['language_id'];
    		$currency_result = $this->_currency->getCurrency(['currency_name'=>$currency_name]);
    		$currency_id = $currency_result[0]['currency_id'];
    		
    		$country_result = $this->_country->getCountry(['country_name'=>$country_name]);
    		$country_id = $country_result[0]['country_id'];
    	
    		$sql.="('$company_name',$country_id,$currency_id,$language_id,$t,$user_id,$t,$user_id,1),";
    		
    		if($k==10){
    			$sql = trim($sql,',');
    			
    			$this->table('company')->execute($sql);
    			$k=1;
    			$sql="insert into company(company_name,country_id,currency_id,language_id,create_time,create_user_id,update_time,update_user_id,status) values";
    			
    		}
    		$k++;
    	}
 		
    	if($k>0 && strlen($sql)>0){
    	
    		
    		$sql = trim($sql,',');
    		
    		$this->table('company')->execute($sql);
    	
    	
    	}
    	return 1;
  
    }
    //部门导入功能
    public function addDepartment($params){
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$data = $params['data'];
    	 
    	$sql="insert into department(department_name,company_id,create_time,create_user_id,update_time,update_user_id,status) values";
    	$k=0;
    	$this->execute("truncate department");
    	for($i=0;$i<count($data);$i++){
    		$company_name = $data[$i]['company_name'];
    		$department_name = $data[$i]['department_name'];

    		$company_result = $this->_company->getCompany(['company_name'=>$company_name]);
    		$company_id = $company_result[0]['company_id'];

    		 
    		$sql.="('$department_name',$company_id,$t,$user_id,$t,$user_id,1),";
    
    		if($k==10){
    			$sql = trim($sql,',');
    			
    			$this->table('department')->execute($sql);
    			$k=1;
    			$sql="insert into department(department_name,company_id,create_time,create_user_id,update_time,update_user_id,status) values";
    			
    		}
    		$k++;
    	}
    		
    	if($k>0 && strlen($sql)>0){
    		 
    		
    		$sql = trim($sql,',');
    		
    		$this->table('department')->execute($sql);
    		 
    		 
    	}
    	return 1;
    
    }
    //职位导入功能
    public function addJob($params){
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$data = $params['data'];
    
    	$sql="insert into job(job_name,department_id,create_time,create_user_id,update_time,update_user_id,status) values";
    	$k=0;
    	$this->execute("truncate job");
    	for($i=0;$i<count($data);$i++){
    		$company_name = $data[$i]['company_name'];
    		$department_name = $data[$i]['department_name'];
    		$job_name = $data[$i]['job_name'];
    		//首先找到公司ID
    		$company_result = $this->_company->getCompany(['company_name'=>$company_name]);
    		$company_id = $company_result[0]['company_id'];
    		//再查找部门ID
    		$department_result = $this->_department->getDepartment(['company_id'=>$company_id,'department_name'=>$department_name]);
    		$department_id = $department_result[0]['department_id'];
    
    		 
    		$sql.="('$job_name',$department_id,$t,$user_id,$t,$user_id,1),";
    		
    		if($k==10){
    			$sql = trim($sql,',');
    			
    			$this->table('job')->execute($sql);
    			$k=1;
				$sql="insert into job(job_name,department_id,create_time,create_user_id,update_time,update_user_id,status) values";    			 
    		}
    		$k++;
    	}
    
    	if($k>0 && strlen($sql)>0){
    		 
    
    		$sql = trim($sql,',');
    		
    		$this->table('job')->execute($sql);
    		 
    		 
    	}
    	return 1;
    
    } 
    //角色 导入功能
    public function addRole($params){
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$data = $params['data'];
    
    	$sql="insert into role(role_name,create_time,create_user_id,update_time,update_user_id,status) values";
    	$k=0;
    	$this->execute("truncate role");
    	
    	for($i=0;$i<count($data);$i++){
    		$role_name = $data[$i]['role_name'];
    	
   
    		 
    		$sql.="('$role_name',$t,$user_id,$t,$user_id,1),";
    
    		if($k==10){
    			$sql = trim($sql,',');
    
    			$this->table('role')->execute($sql);
    			$k=1;
    			$sql="insert into role(role_name,create_time,create_user_id,update_time,update_user_id,status) values";
    		}
    		$k++;
    	}
    
    	if($k>0 && strlen($sql)>0){
    		 
    
    		$sql = trim($sql,',');
    
    		$this->table('role')->execute($sql);
    		 
    		 
    	}
    	return 1;
    
    }
    //线路导入功能
    public function addRouteType($params){
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$data = $params['data'];
    
    	$sql="insert into route_type(route_type_name,type,create_time,create_user_id,update_time,update_user_id,status) values";
    	$k=0;
    	$this->execute("truncate route_type");
    	for($i=0;$i<count($data);$i++){
    		$route_name = $data[$i]['route_type_name'];

    		if($data[$i]['type']=='华人团'){
    			$type=1;
    		}else{
    			$type=2;
    		}
    		 
    		$sql.="('$route_name',$type,$t,$user_id,$t,$user_id,1),";
    
    		if($k==10){
    			$sql = trim($sql,',');
    			 
    			$this->table('route_type')->execute($sql);
    			$k=1;
    			$sql="insert into route_type(route_type_name,type,create_time,create_user_id,update_time,update_user_id,status) values";
    		}
    		$k++;
    	}
    
    	if($k>0 && strlen($sql)>0){
    		 
    
    		$sql = trim($sql,',');
    
    		$this->table('route_type')->execute($sql);
    		 
    		 
    	}
    	return 1;
    
    }
    //用户导入功能
    public function addUser($params){
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$data = $params['data'];
    	
    	$sql="insert into user(username,password,job_id,nickname,email,create_time,create_user_id,update_time,update_user_id,status) values";
    	$k=0;
    	$user_result_id =1;
    	$this->execute("truncate user");
    	for($i=0;$i<count($data);$i++){
    		
    		$username = $data[$i]['username'];
    		if(strlen($username)==0){
    			$username = 'user_'.$user_result_id;
    		}
    		$password = md5('nexus2018');
    		$company_name = $data[$i]['company_name'];
    		$department_name = $data[$i]['department_name'];
    		$job_name = $data[$i]['job_name'];
    		//首先找到公司ID
    		$company_result = $this->_company->getCompany(['company_name'=>$company_name]);
    		$company_id = $company_result[0]['company_id'];

    		//再查找部门ID
    		$department_result = $this->_department->getDepartment(['company_id'=>$company_id,'department_name'=>$department_name]);
    		$department_id = $department_result[0]['department_id'];   

    		$job_result = $this->_job->getJob(['job_name'=>$job_name,'department_id'=>$department_id]);
    		if(count($job_result)>0){
    			$job_id = $job_result[0]['job_id'];
    		}else{
    			
    			$job_id='0';
    		}
    		

    		$nickname = $data[i]['first_name'].$data[$i]['last_name'];
    		$email = $data[$i]['email']; 
    		$sql.="('$username','$password',$job_id,'$nickname','$email',$t,$user_id,$t,$user_id,1),";
			
    		if($k==100){
    			$sql = trim($sql,',');
    		
    			$this->table('user')->execute($sql);
    			
    			$k=1;
    			$sql="insert into user(username,password,job_id,nickname,email,create_time,create_user_id,update_time,update_user_id,status) values";
    		}
    		$k++;
    		$user_result_id++;
    	}
    
    	if($k>0 && strlen($sql)>0){
    		 
    
    		$sql = trim($sql,',');
    
    		$this->table('user')->execute($sql);
    		 
    		 
    	}
    	return 1;
    
    }
    //回执单模板导入功能
    public function addReturnReceipt($params){
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$data = $params['data'];
    	$this->execute("truncate return_receipt");
    	$this->execute("truncate return_receipt_info");

		
    	for($i=0;$i<count($data);$i++){
    		$return_receipt_name = $data[$i]['return_receipt_name'];
    		$sql_return_receipt = "insert into return_receipt(return_receipt_name,create_time,update_time,create_user_id,update_user_id,status) values";
    		$title1 = str_replace("'",'"', $data[$i]['title1']);
    		
    		$content1 = str_replace("'",'"', $data[$i]['content1']);
    		$title2 = str_replace("'",'"', $data[$i]['title2']);
    		$content2 = str_replace("'",'"', $data[$i]['content2']);
			$sql_return_receipt.="('$return_receipt_name',$t,$t,$user_id,$user_id,1)";
		
			$this->execute($sql_return_receipt);
			$return_receipt_id = $this->getLastInsID();
			$sql_return_receipt_info="insert into return_receipt_info(return_receipt_id,title,content,sorting,create_time,update_time,create_user_id,update_user_id,status) values";
			$sql_return_receipt_info.="($return_receipt_id,'$title1','$content1',1,$t,$t,$user_id,$user_id,1),($return_receipt_id,'$title2','$content2',2,$t,$t,$user_id,$user_id,1)";
		
			$this->execute($sql_return_receipt_info);
			
    		
    	
    	}
    

    	return 1;
    
    } 
    //供应商导入功能
    public function addSupplier($params){
        $user_id = $params['now_user_id'];
    	$t = time();
    	$data = $params['data'];
    	
    	$sql="insert into supplier(supplier_number,company_id,supplier_name,supplier_type_id,country_id,linkman,address,phone,fax,email,remark,create_time,create_user_id,update_time,update_user_id,status) values";
    	$k=0;
    	
    	$this->execute("truncate supplier");
    	for($i=0;$i<count($data);$i++){
    		$supplier_number = help::getNumber(51);
    		$company_id = 4122;
    		$supplier_name = $data[$i]['supplier_name'];
    		$source_type_name = $data[$i]['source_type_name'];
    		if($source_type_name=='地接'){
    			$supplier_type_id=1;
    		}else if($source_type_name=='酒店'){
    			$supplier_type_id=2;
    		}else if($source_type_name=='邮轮'){
    			$supplier_type_id=5;
    		}else if($source_type_name=='车队'){
    			$supplier_type_id=8;
    		}
    		$source_type_id =''; //暂时不弄
    		$country_id = 4122;
    		$level_name='';
    		$linkman = $data[$i]['linkman'];
    		$address = $data[$i]['address'];
    		$phone = $data[$i]['phone'];
    		$fax = $data[$i]['fax'];
    		$email = $data[$i]['email'];
    		$remark = $data[$i]['remark'];

    		$sql.="('$supplier_number',$company_id,'$supplier_name',$supplier_type_id,$country_id,'$linkman','$address','$phone','$fax','$email','$remark',$t,$user_id,$t,$user_id,1),";
			
    		if($k==300){
    			$sql = trim($sql,',');
    		
    			$this->table('supplier')->execute($sql);
    			
    			$k=1;
    			$sql="insert into supplier(supplier_number,company_id,supplier_name,supplier_type_id,country_id,linkman,address,phone,fax,email,remark,create_time,create_user_id,update_time,update_user_id,status) values";
    		}
    		$k++;
    		
    	}
    
    	if($k>0 && strlen($sql)>0){
    		 
    
    		$sql = trim($sql,',');
    
    		$this->table('supplier')->execute($sql);
    		 
    		 
    	}
    	return  1;
    }   
    //团队产品 导入功能
    public function addTeamProduct($params){
    	
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$data = $params['data'];
    	 
    	$sql="insert into team_product(team_product_number,team_product_name,begin_time,route_type_id,use_company_id,settlement_type,team_product_user_id,create_time,create_user_id,update_time,update_user_id,status) values";
    	$k=0;
   
    	$this->execute("truncate team_product");
    	$this->execute("truncate team_product_allocation");
    	$this->execute("truncate team_product_flight");
    	$this->execute("truncate team_product_journey");
    	$this->execute("truncate team_product_once_price");
    	$this->execute("truncate team_product_return_receipt");
    	$this->execute("truncate team_product_schedule");
    	$this->execute("truncate team_product_true_price");
    	for($i=0;$i<count($data);$i++){
    		$team_product_number =help::getNumber(2);
    		
    		$team_product_name = $data[$i]['team_product_name'];
    		$begin_time = strtotime($data[$i]['begin_time']);
    		
    		
    		$source_type_name = $data[$i]['route_type_name'];
    		if($source_type_name=='华人中国特价线'){
    			$supplier_type_id=1;
    		}else if($source_type_name=='华人中国独立团'){
    			$supplier_type_id=2;
    		}else if($source_type_name=='老外中国特价线'){
    			$supplier_type_id=3;
    		}else if($source_type_name=='老外中国独立团'){
    			$supplier_type_id=4;
    		}else if($source_type_name=='华人亚洲特价线'){
    			$supplier_type_id=5;
    		}else if($source_type_name=='华人亚洲独立团'){
    			$supplier_type_id=6;
    		}else if($source_type_name=='华人中国无购物团'){
    			$supplier_type_id=7;
    		}else if($source_type_name=='俄罗斯落地散拼'){
    			$supplier_type_id=8;
    		}else if($source_type_name=='日本中心'){
    			$supplier_type_id=9;
    		}else if($source_type_name=='东南亚中心'){
    			$supplier_type_id=10;
    		}else if($source_type_name=='老外非中国团'){
    			$supplier_type_id=11;
    		}else if($source_type_name=='邮轮'){
    			$supplier_type_id=12;
    		}else if($source_type_name=='老外中国特价线（DISCOVERY)'){
    			$supplier_type_id=13;
    		}
		
    		$use_company_id = '*';
    		$settlement_type = 1;
    		$team_product_user_id = $user_id;
    		$sql.="('$team_product_number','$team_product_name',$begin_time,$supplier_type_id,'$use_company_id',$settlement_type,$team_product_user_id,$t,$user_id,$t,$user_id,1),";
    		
    		if($k==100){
    			$sql = trim($sql,',');
    			
    			$this->table('supplier')->execute($sql);
    			 
    			$k=1;
    			$sql="insert into team_product(team_product_number,team_product_name,begin_time,route_type_id,use_company_id,settlement_type,team_product_user_id,create_time,create_user_id,update_time,update_user_id,status) values";
    			 
    		}
    		$k++;
    
    	}
    	
    	if($k>0 && strlen($sql)>0){
    		 
    
    		$sql = trim($sql,',');
    
    		$this->table('supplier')->execute($sql);
    		 
    		 
    	}
    	return  1;
    }
    //团队行程 导入功能
    public function addTeamProductJourney($params){
    	 
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$data = $params['data'];
    
    	//$sql="insert into team_product_journey(route_journey_content) values";
    	$sql="insert into team_product_journey(team_product_id,the_days,route_journey_title,route_journey_content,eat_mark,route_journey_remark,create_time,create_user_id,update_time,update_user_id,status) values";
    	 
    	$k=0;
    	

    	$this->execute("truncate team_product_journey");

    	for($i=0;$i<count($data);$i++){
    		//获取团队产品名称
    		
    		$team_product_name = $data[$i]['team_product_name'];
    		
    		$team_product_result = $this->_team_product->getTeamProduct(['team_product_name'=>$team_product_name]);
    		
    	
    		if(count($team_product_result)==0){
    			continue;
    		}else{
    			for($j=0;$j<count($team_product_result);$j++){
    				$team_product_id = $team_product_result[$j]['team_product_id'];
    				$the_days = $data[$i]['the_days'];
    				$title = $data[$i]['title'];
    				$content = $data[$i]['content'];
    				$remark = $data[$i]['remark'];
    				if($data[$i]['eat_mark'] =='B'){
    					$eat_mark = '1';
    				}else if($data[$i]['eat_mark'] =='BL'){
    					$eat_mark = '1,2';
    				}else if($data[$i]['eat_mark'] =='BLD'){
    					$eat_mark = '1,2,3';
    				}else if($data[$i]['eat_mark'] =='BD'){
    					$eat_mark = '1,3';
    				}else if($data[$i]['eat_mark'] =='L'){
    					$eat_mark = '2';
    				}else if($data[$i]['eat_mark'] =='LD'){
    					$eat_mark = '2,3';
    				}else if($data[$i]['eat_mark'] =='D'){
    					$eat_mark = '3';
    				}else{
    					$eat_mark='';
    				}
    				
    				$sql.="($team_product_id,'$the_days','$title','$content','$eat_mark','$remark',$t,$user_id,$t,$user_id,1),";
    				//$sql.="('$content'),";
    				
    				$k++;
    			}
    		}
    		

    		if($k>=10){
    		
    			$sql = trim($sql,',');
    			
    			$this->table('team_product_journey')->execute($sql);
    
    			$k=0;
    			$sql="insert into team_product_journey(team_product_id,the_days,route_journey_title,route_journey_content,eat_mark,route_journey_remark,create_time,create_user_id,update_time,update_user_id,status) values";
    			
    		}
    		
    
    	}
    	 
    	if($k>0 && strlen($sql)>0){
    		 
    		
    		$sql = trim($sql,',');
    		
    		$this->table('team_product_journey')->execute($sql);
    		 
    		 
    	}
    	return  1;
    }    
    //游客 导入功能
    public function addCustomer($params){
    	 
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$data = $params['data'];
    
    	$sql="insert into customer(customer_number,company_id,customer_first_name,customer_last_name,gender,country_id,card_type,card_number,term_of_validity,create_time,create_user_id,update_time,update_user_id,status) values";
    	$k=0;
    
    	//$this->execute("truncate customer");

    	for($i=0;$i<count($data);$i++){//
    		$customer_number =help::getNumber(7);
    
    		$company_id = 1;
    		$first_name = $data[$i]['first_name'];
    		$last_name = $data[$i]['last_name'];
    		$gender = $data[$i]['gender'];
    		$country_id = 8;
    		if($data[$i]['card_type']=='身份证'){
    			$card_type=2;
    		}else if($data[$i]['card_type']=='护照'){
    			$card_type=1;
    		}else{
    			$card_type='';
    		}
			$card_number = $data[$i]['card_number'];
			$term_of_validity = $data[$i]['term_of_validity'];

    		$sql.="('$customer_number',$company_id,'$first_name','$last_name','$gender',$country_id,'$card_type','$card_number','$term_of_validity',$t,$user_id,$t,$user_id,1),";
    
    		if($k==500){
    			$sql = trim($sql,',');
    			
    			$this->table('customer')->execute($sql);
    
    			$k=1;
    			$sql="insert into customer(customer_number,company_id,customer_first_name,customer_last_name,gender,country_id,card_type,card_number,term_of_validity,create_time,create_user_id,update_time,update_user_id,status) values";
    			
    		}
    		$k++;
    
    	}
    	 
    	if($k>0 && strlen($sql)>0){
    		 
    
    		$sql = trim($sql,',');
    
    		$this->table('customer')->execute($sql);
    		 
    		 
    	}
    	return  1;
    }
    //经销商 导入功能
    public function addDistributor($params){
    
    	$user_id = $params['now_user_id'];
    	$t = time();
    	$data = $params['data'];
    
    	$sql="insert into distributor(company_id,distributor_name,tel,address,city_id,zip_code,email,create_time,create_user_id,update_time,update_user_id,status) values";
    	$k=0;
    
    	$this->execute("truncate distributor");
    
    	for($i=0;$i<count($data);$i++){//
    		if($data[$i]['company_name']=='澳洲'){
    			$company_id = 2;
    		}else if($data[$i]['company_name']=='多伦多'){
    			$company_id = 4;
    		}else if($data[$i]['company_name']=='温哥华'){
    			$company_id = 5;
    		}else if($data[$i]['company_name']=='NYC'){
    			$company_id = 8;
    		}else if($data[$i]['company_name']=='WAS'){
    			$company_id = 6;
    		}else if($data[$i]['company_name']=='无锡'){
    			$company_id = 3;
    		}else if($data[$i]['company_name']=='ORD'){
    			$company_id = 7;
    		}else if($data[$i]['company_name']=='SFO'){
    			$company_id = 9;
    		}else if($data[$i]['company_name']=='LAX'){
    			$company_id = 10;
    		}else if($data[$i]['company_name']=='Kirk'){
    			$company_id = 11;
    		}else{
    			$company_id=1;
    		}
			$distributor_name = $data[$i]['distributor_name'];
			
			$tel = $data[$i]['tel'];
			$address = $data[$i]['address'];
			$city_id = 10;
			$zip_code = $data[$i]['zip_code'];
			$email = $data[$i]['email'];
    
    		$sql.="($company_id,'$distributor_name','$tel','$address',$city_id,'$zip_code','$email',$t,$user_id,$t,$user_id,1),";
    
    		if($k==500){
    			$sql = trim($sql,',');
    			
    			$this->table('distributor')->execute($sql);
    
    			$k=1;
    			$sql="insert into distributor(company_id,distributor_name,tel,address,city_id,zip_code,email,create_time,create_user_id,update_time,update_user_id,status) values";
    			
    		}
    		$k++;
    
    	}
    
    	if($k>0 && strlen($sql)>0){
    		 
    
    		$sql = trim($sql,',');
    
    		$this->table('distributor')->execute($sql);
    		 
    		 
    	}
    	return  1;
    }    

    //利润表-预计导入
    public function addProfitStatement($params){
        $this->startTrans();
        try{
            $sql = "insert into profit_statement(company_id,years,month,number_of_staff,number_of_guests_received,order_amount,main_operating_income,external_income,internal_settlement_income,main_business_cost,external_cost,internal_settlement_cost,gross_profit,ratio_of_margin,operating_taxes_and_attachments,selling_expenses,commission,other,overhead_expenses,salary,chummage,hydroelectricity,handle_official_business,cost_of_financing,interest,exchange_gain_or_loss,poundage,nonbusiness_income,non_business_expenditure,total_profit,income_tax,net_margin,is_predict,create_time,update_time,create_user_id,update_user_id,status) values";
     
            foreach ($params['data'] as $key => $v) {
               $sql .= "({$v['company_id']},'{$v["years"]}','{$v["month"]}',{$v['number_of_staff']},{$v['number_of_guests_received']},{$v['order_amount']},{$v['main_operating_income']},{$v['external_income']},{$v['internal_settlement_income']},{$v['main_business_cost']},{$v['external_cost']},{$v['internal_settlement_cost']},{$v['gross_profit']},{$v['ratio_of_margin']},{$v['operating_taxes_and_attachments']},{$v['selling_expenses']},{$v['commission']},{$v['other']},{$v['overhead_expenses']},{$v['salary']},{$v['chummage']},{$v['hydroelectricity']},{$v['handle_official_business']},{$v['cost_of_financing']},{$v['interest']},{$v['exchange_gain_or_loss']},{$v['poundage']},{$v['nonbusiness_income']},{$v['non_business_expenditure']},{$v['total_profit']},{$v['income_tax']},{$v['net_margin']},{$v['is_predict']},{$v['create_time']},{$v['update_time']},{$v['create_user_id']},{$v['update_user_id']},{$v['status']}),";  
            
               $where['company_id'] = $v['company_id'];
               $where['years'] = $v['years'];
               $where['month'] = $v['month'];
               $where['is_predict'] = 1;
               $this->table('profit_statement')->where($where)->update(['status'=>2]);
            } 
              
            if(count($params['data'])>0){ 
                $sql = trim($sql,','); 
                $this->table('profit_statement')->execute($sql);
            }
            // 提交事务
            $this->commit();
            return 1;
        }catch (\Exception $e) {
            // 回滚事务
            $this->rollback();
            return $result = $e->getMessage();
        }

    }
	
    
    /**
     * 新团队产品
     */
    public function  addTeamProductNew($params){
    
    	$t = time();
    	$data = $params['data'];
    	$this->execute("truncate team_product");
    	$this->execute("truncate team_product_allocation");
    	$this->execute("truncate team_product_flight");
    	$this->execute("truncate team_product_journey");
    	$this->execute("truncate team_product_once_price");
    	$this->execute("truncate team_product_return_receipt");
    	$this->execute("truncate team_product_schedule");
    	$this->execute("truncate team_product_true_price");
//     	$sql="insert into distributor(company_id,distributor_name,tel,address,city_id,zip_code,email,create_time,create_user_id,update_time,update_user_id,status) values";
//     	$k=0;
    	$user = new User();
//     	$this->execute("truncate distributor");
    	$user_result_id =1;
    	for($i=0;$i<count($data);$i++){//count($data)
			
    		$plan_id = $data[$i]['plan_id'];
    		$team_product_name= $data[$i]['team_product_name'];
    		$team_product_number = $team_product_name;
    		$title = $data[$i]['title'];
    		$subtitle = $data[$i]['subtitle'];
    		$title_mark = $data[$i]['title_mark'];
    		$mark = $data[$i]['mark'];
    		$diy = $data[$i]['diy'];
    		$params['nickname'] = $data[$i]['nickname'];
    		$create_time = $data[$i]['create_time'];
    		
    		//首先查询数据库是否有该用户
    		$user_nickname_params = [
    			'nickname'=>$params['nickname']	
    		];
    		
    		$user_result = $this->_user->getUserByNickname($user_nickname_params);
    	
    		if(count($user_result)>0){
    			$user_id = $user_result['user_id'];
    		}else{
    			
    			$user_params = [
    					'username'=>'user_wuxi_'.$user_result_id,
    					'password'=>'nexus2019',
    					'nickname'=>$data[$i]['nickname'],
    					'language_id'=>1,
    					'company_id'=>3,
    					'department_id'=>5,
    					'role_id'=>1,
    					'user_id'=>1,
    			];
    			$user_id = $this->_user->addUser($user_params);
    			$user_result_id++;
    		}

    		
    		//开始插入团队产品
    		$team_product_params = [
    			'user_id'=>$user_id,
    			'team_product_number'=>$team_product_number,
    			'team_product_name'=>$team_product_name,
    			'settlement_type'=>2,
    			'team_product_user_id'=>$user_id,
    			'user_company_id'=>3,
    			'status'=>1,
    			'plan_id'=>$plan_id,
    			'create_time'=>strtotime($create_time)
    		];
    		
    		
		
    		
    		$team_product_id = $this->_team_product->addTeamProductByImport($team_product_params);
    		//拿到团队产品之后插入行程内容
    		
    		$team_product_return_receipt_params=[
    			'user_id'=>$user_id,
    			'status'=>1,
    			'add_return_receipt'=>[]
    		];
    	
    		if(!empty($title)){
    			
    		
    			$return_params = [
    				'title'=>'标题',
    				'content'=>$title,
    				'sorting'=>1,
    				'team_product_id'=>$team_product_id,
    				
    			];
    			$team_product_return_receipt_params['add_return_receipt'][] = $return_params;
    		}
    		if(!empty($subtitle)){
    		
    			$return_params = [
    				'title'=>'副标题',
    				'content'=>$subtitle,
    				'sorting'=>1,
    				'team_product_id'=>$team_product_id,
    				
    			];
    			$team_product_return_receipt_params['add_return_receipt'][] = $return_params;
    		}
    		if(!empty($title_mark)){
    	
    			$return_params = [
    				'title'=>'标题备注',
    				'content'=>$title_mark,
    				'sorting'=>1,
    				'team_product_id'=>$team_product_id,
    			
    			];
    			$team_product_return_receipt_params['add_return_receipt'][] = $return_params;
    		}
    		if(!empty($mark)){
    	
    			$return_params = [
    				'title'=>'附件',
    				'content'=>$mark,
    				'sorting'=>1,
    				'team_product_id'=>$team_product_id,
    				
    			];
    			$team_product_return_receipt_params['add_return_receipt'][] = $return_params;
    		}
    		if(!empty($diy)){
    		
    			$return_params = [
    					'title'=>'自定义',
    					'content'=>$diy,
    					'sorting'=>1,
    					'team_product_id'=>$team_product_id,
    					
    			];
    			$team_product_return_receipt_params['add_return_receipt'][] = $return_params;
    		}    	
 
    		$this->_team_product_return_receipt->updateTeamProductReturnReceiptByTeamProductReturnReceiptId($team_product_return_receipt_params);
    		
//     		$params['cellphone'] = $data[$i]['cellphone'];
//     		$params['create_time'] = $data[$i]['create_time'];
//     		$params['diy'] = $data[$i]['diy'];
    	
//     		$sql.="($company_id,'$distributor_name','$tel','$address',$city_id,'$zip_code','$email',$t,$user_id,$t,$user_id,1),";
    	
//     		if($k==500){
//     			$sql = trim($sql,',');
    			 
//     			$this->table('distributor')->execute($sql);
    	
//     			$k=1;
//     			$sql="insert into distributor(company_id,distributor_name,tel,address,city_id,zip_code,email,create_time,create_user_id,update_time,update_user_id,status) values";
    			 
//     		}
//     		$k++;
    	
//     	}
    	
//     	if($k>0 && strlen($sql)>0){
    		 
    	
//     		$sql = trim($sql,',');
    	
//     		$this->table('distributor')->execute($sql);
    		 
    		 
    	}
    	return  1;
    }

	/**
	 * 新团队产品开团时间
	 */
    public function addTeamProductNewBeginTime($params){
    	$data = $params['data'];
    	for($i=0;$i<count($data);$i++){//count($data)
    		$team_product_params = [
    			'plan_id'=>$data[$i]['plan_id'],
    			'begin_time'=>$data[$i]['begin_time']	
    				
    		]; 
    	
    		$this->_team_product->updateTeamProductBeginTimeByPlanId($team_product_params);
    	
    	
    	
    	}
    	return 1;
    }
    /**
     * 新团队产品行程
     */
    public function addTeamProductJounery($params){
    	$data = $params['data'];
    	for($i=0;$i<count($data);$i++){//count($data)
    		$team_product_params = [
    			'plan_id'=>$data[$i]['plan_id'],
    		
    	
    		];
    		 
    		$team_product_result = $this->_team_product->getTeamProduct($team_product_params);
    		$team_product_id = $team_product_result[0]['team_product_id']; 
    		//查询形成是否有数据
    		$team_product_jounery_params = [
    			'team_product_id'=>$team_product_id	
    		];
    		$team_product_journey_result = $this->_team_product_journey->getTeamProductJourney($team_product_jounery_params);
  
    		$team_product_journey_add_params = [
    			'team_product_id'=>$team_product_id,
    			'the_days'=>count($team_product_journey_result)+1,
    			'route_journey_title'=>$data[$i]['title'],
    			'route_journey_content'=>$data[$i]['title'],
    			'route_journey_remark'=>$data[$i]['remark']	
    		];
    		if($data[$i]['rice']=='BLD'){
    			$team_product_journey_add_params['eat_mark'] = '1,2,3';
    		}
    		if($data[$i]['rice']=='B'){
    			$team_product_journey_add_params['eat_mark'] = '1';
    		}
    		if($data[$i]['rice']=='L'){
    			$team_product_journey_add_params['eat_mark'] = '2';
    		}
    		if($data[$i]['rice']=='D'){
    			$team_product_journey_add_params['eat_mark'] = '3';
    		}
    		if($data[$i]['rice']=='BL'){
    			$team_product_journey_add_params['eat_mark'] = '1,2';
    		}
    		if($data[$i]['rice']=='BD'){
    			$team_product_journey_add_params['eat_mark'] = '1,3';
    		}
    		if($data[$i]['rice']=='LD'){
    			$team_product_journey_add_params['eat_mark'] = '2,3';
    		}
    	
    		$this->_team_product_journey->addTeamProductJourney($team_product_journey_add_params);
    	}
    	return 1;
    	
    	
    }
    /**
     * 团队产品关系表
     */
    public function addTeamProductRelation($params){

    	$data = $params['data'];
    	$sql="insert into import_plan_relation(plan_id,tour_id,tour_date) values";
    	$k=0;
    
    	for($i=0;$i<count($data);$i++){
    		$plan_id = $data[$i]['plan_id'];
    		$tour_id = $data[$i]['tour_id'];
    		$tour_date = $data[$i]['tour_date'];

    		$sql.="($plan_id,$tour_id,'$tour_date'),";
    	
    		if($k==10){
    			$sql = trim($sql,',');
    			
    			$this->table('import_plan_relation')->execute($sql);
    			$k=1;
    			$sql="insert into import_plan_relation(plan_id,tour_id,tour_date) values";
    		}
    		$k++;
    	}
    	
    	if($k>0 && strlen($sql)>0){
    		 
    	
    		$sql = trim($sql,',');
    	
    		$this->table('import_plan_relation')->execute($sql);
    		 
    		 
    	}
    	return 1;
    	
    	
    	
    }
    /**
     * 订单关系表
     */
    public function addBookRelation($params){
    	$data = $params['data'];
    	$sql="insert into import_booking_relation(bk_id,tour_id,tour_date) values";
    	$k=0;
    
    	for($i=0;$i<count($data);$i++){
    		$plan_id = $data[$i]['bk_id'];
    		$tour_id = $data[$i]['tour_id'];
    		$tour_date = $data[$i]['tour_date'];
    	
    		$sql.="($plan_id,$tour_id,'$tour_date'),";
    		 
    		if($k==10){
    			$sql = trim($sql,',');
    			 
    			$this->table('import_booking_relation')->execute($sql);
    			$k=1;
    			$sql="insert into import_booking_relation(bk_id,tour_id,tour_date) values";
    		}
    		$k++;
    	}
    	 
    	if($k>0 && strlen($sql)>0){
    		 
    		 
    		$sql = trim($sql,',');
    		 
    		$this->table('import_booking_relation')->execute($sql);
    		 
    		 
    	}
    	return 1;    	
    }
    
    /**
     * 
     */
    public function addTeamProductAndBk(){
    	
    	//首先获取所有的planid
    	$plan_result = $this->table('import_plan_relation')->select();
    	
    	for($i=0;$i<count($plan_result);$i++){
    		$bk_params=[
    			'tour_id'=>$plan_result[$i]['tour_id'],
    			'tour_date'=>$plan_result[$i]['tour_date']	
    		];
    		$bk_result = $this->table('import_booking_relation')->where($bk_params)->select();
    		
    		if(count($bk_result[0])==1){
    			$plan_bk_params = [
    				'plan_id'=>$plan_result[$i]['plan_id'],
    				'bk_id'=>$bk_result[0]['bk_id']	
    			];
    			$this->table('import_planid_bkid')->insert($plan_bk_params);
    		}

    		
    		
    	}
    	return 1;
    	
    }
    //添加酒店资源
    public function addHotelSource($params){


    }
    //获取团队产品关联
    public function get_plan(){
    	$plan_data = $this->table('import_plan_relation')->select();
    	return $plan_data;
    	
    }
    //获取订单 关联
    public function get_booking($params){
    	$where['tour_id'] = $params['tour_id'];
    	$where['tour_date'] = $params['tour_date'];
    	$plan_data = $this->table('import_booking_relation')->where($where)->find();
    	return $plan_data;
    	 
    }
    public function add_booking($params){
    	$where['bk_id'] = $params['bk_id'];
    	$where['plan_id'] = $params['plan_id'];
    	$plan_data = $this->table('import_plan_booking')->insert($where);
    	return $plan_data;
    	
    	
    }
    
    
    /**
     * 添加订单
     */
    public function addCompanyOrder($params){
    	 
    }
}