<?php
namespace app\index\controller;
use app\common\help\Help;
use think\config;
use think\Db;
use think\Model;
use think\Controller;
use app\index\model\enquirty\Enquirty as m_Enquirty;  
use app\index\model\system\User;

/**
*  需求定制
*  Hugh
**/
class Enquirty extends Base
{
	public $mEnquirty;

	public function __construct()
    {
    	$this->mEnquirty = new m_Enquirty(); 
    	parent::__construct();
    }

    public function selEnquirty(){
    	$params = $this->input(); 
    	$result = $this->mEnquirty->selEnquirty($params);
		$this->outPut($result);

    }


    //添加需求定制
    public function addEnquirty(){
    	$params = $this->input();  
        
    	$paramRule = [
             'company_id'=>'number', 
             'travel_destinations'=>'string',
             'departure_date'=>'string',
             'travel_days'=>'number',
             'contect_name'=>'string',
             'tel'=>'string',
             'language_id'=>'number',
             'user_id'=>'number',
        ];
        $this->paramCheckRule($paramRule,$params); 

    	$result = $this->mEnquirty->addEnquirty($params);
		$this->outPut($result);
    }


    //修改需求定制
    public function editEnquirty(){
    	$params = $this->input();

    	$paramRule = [
    		 'enquiry_id' => 'number',
             'travel_destinations'=>'string',
             'departure_date'=>'string',
             'travel_days'=>'number',
             'contect_name'=>'string',
             'tel'=>'string',
             'language_id'=>'number',
             'user_id'=>'number',
        ];
        $this->paramCheckRule($paramRule,$params); 

        $result = $this->mEnquirty->editEnquirty($params);
		$this->outPut($result);

    }


    //修改需求定制负责人
    public function editEnquirtyPersonInCharge(){
    	$params = $this->input();  
		$result = $this->mEnquirty->editEnquirtyPersonInCharge($params);
		$this->outPut($result);
    }


}