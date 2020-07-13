<?php

namespace app\index\model\system;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class BillTemplate extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'bill_template';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

    /**
     * 添加账单模板
     * 胡
     */
    public function addBillTemplate($params){
    	$t = time();

        if(isset($params['bill_template_title_pic'])){
            $data['bill_template_title_pic'] = $params['bill_template_title_pic'];
        }
        if(isset($params['bill_template_foot_pic'])){
            $data['bill_template_foot_pic'] = $params['bill_template_foot_pic'];
        }
    	$data['bill_template_title'] = $params['bill_template_title'];
        $data['company_id'] = $params['choose_company_id'];

    	$data['create_time'] = $t;  	
    	$data['create_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['user_id'];
        $data['status'] = $params['status'];

    	
    
    
    	

    
    
    	Db::startTrans();
    	try{
    		$result = Db::name('bill_template')->insertGetId($data);
  
    		 
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
     * 获取账单模板
     * 胡
     */
    public function getBillTemplate($params,$is_count=false,$is_page=false,$page=null,$page_size=20){//第一个为参数，第二个为是否要获取 总数
    

    	$data = "1=1 ";
        if(isset($params['bill_template_title'])){
            $data.= " and bill_template.bill_template_title like '%".$params['bill_template_title']."%'";
        }
        if(is_numeric($params['status'])){
            $data.= " and bill_template.status = ".$params['status'];
        }

    	if(!empty($params['bill_template_id'])){
    		$data.= " and bill_template.bill_template_id = '".$params['bill_template_id']."'";
    	}

        if(isset($params['company_id'])) {
            $data .= " and bill_template.company_id ='" . $params['company_id'] . "'";
        }
  
    	if($is_count==true){
    		$result = $this->where($data)->count();
    	
    	}else{
    		if($is_page == true){
	            $result = $this->table("bill_template")->alias('bill_template')->
                join('company','company.company_id= bill_template.company_id')->
	            where($data)->limit($page,$page_size)->
	            
	            field(['bill_template.bill_template_id',"bill_template.bill_template_title","bill_template.bill_template_title_pic",
                        "bill_template.bill_template_foot_pic", "bill_template.company_id",'company.company_name',
	            		"(select nickname  from user where user.user_id = bill_template.create_user_id)"=>'create_user_name',
	            		"(select nickname  from user where user.user_id = bill_template.update_user_id)"=>'update_user_name',
	            		'bill_template.create_time','bill_template.update_time',
	            		'bill_template.create_user_id','bill_template.update_user_id','bill_template.status'
	            		
	            ])->select();
	            
	     
    		}else{
	            $result = $this->table("bill_template")->alias('bill_template')->
                join('company','company.company_id= bill_template.company_id')->

	            where($data)->

                field(['bill_template.bill_template_id',"bill_template.bill_template_title","bill_template.bill_template_title_pic",
                    "bill_template.bill_template_foot_pic","bill_template.company_id",'company.company_name',
                    "(select nickname  from user where user.user_id = bill_template.create_user_id)"=>'create_user_name',
                    "(select nickname  from user where user.user_id = bill_template.update_user_id)"=>'update_user_name',
                    'bill_template.create_time','bill_template.update_time',
                    'bill_template.create_user_id','bill_template.update_user_id','bill_template.status'

                ])->select();
    		}
    	
    	}




        return $result;
    
    }

    
    /**
     * 修改账单模板 根据bill_template_id
     */
    public function updateBillTemplateByBillTemplateId($params){
    
    	$t = time();
    	
		
    	if(!empty($params['bill_template_title'])){
    		$data['bill_template_title'] = $params['bill_template_title'];
    		
    	}
  
       $data['bill_template_title_pic'] = $params['bill_template_title_pic'];
  
      
       $data['bill_template_foot_pic'] = $params['bill_template_foot_pic'];
    
    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    		
    	}
        if(!empty($params['choose_company_id'])){
            $data['company_id'] = $params['choose_company_id'];

        }



    	$data['update_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;

    
    
    
    	Db::startTrans();
    	try{
    		Db::name('bill_template')->where("bill_template_id = ".$params['bill_template_id'])->update($data);
    	
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
     * getOneBillTemplate
     *
     * 获取一条账单模版信息
     * @author shj
     *
     * @param $bill_template_id
     *
     * @return void
     * Date: 2019/2/28
     * Time: 14:17
     */
    public function getOneBillTemplate($bill_template_id){
        $result = $this->table("bill_template")->where(['bill_template_id' => $bill_template_id])->find();
        return $result;
    }
}