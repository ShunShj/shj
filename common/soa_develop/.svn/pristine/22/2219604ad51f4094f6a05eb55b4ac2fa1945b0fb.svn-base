<?php
namespace app\index\controller;
use app\common\help\Contents;
use app\common\help\Help;

use app\index\controller\Base; 
use think\Model;
use think\Controller;

use app\index\model\ota_theme\OtaTheme as m_OtaTheme;

//主题 Hugh
class OtaTheme extends Base
{
	private $_language;
	public $mOtaTheme;

    //_lang Base里的属性，
    public function __construct()
    {
    	$this->mOtaTheme = new m_OtaTheme(); 
    	parent::__construct();
    }

    //获取主题
    public function getTheme(){
    	$params = $this->input();
    	// $params['status'] = 1;
    	$result = $this->mOtaTheme->getTheme($params);
    	$this->outPut($result);

    }

    //获取网站主题
    public function getSiteThemes(){
    	$params = $this->input();
    	// $params['system_config_id'] = 1; //网站设置ID
    	$result = $this->mOtaTheme->getSiteThemes($params);
    	$this->outPut($result);
    }


    //添加网站主题
    public function addSiteThemes(){
        $params = $this->input();
        $result = $this->mOtaTheme->addSiteThemes($params);
        $this->outPut($result);
    }


    //修改网站主题
    public function upSiteThemes(){
		$params = $this->input();
    	$result = $this->mOtaTheme->upSiteThemes($params);
    	$this->outPut($result);
    }


    //获取页面列表
    public function getPage(){
    	$params = $this->input();
    	$result = $this->mOtaTheme->getPage($params);
    	$this->outPut($result);
    }

    //获取主题模块
    public function getThemeModule(){
    	$params = $this->input();
    	$result = $this->mOtaTheme->getThemeModule($params);
    	$this->outPut($result);
    }

    //添加页面配置
    public function newBuiltPageAjax(){
    	$params = $this->input();
    	// error_log(print_r($params,1));
    	$result = $this->mOtaTheme->newBuiltPageAjax($params);
    	$this->outPut($result);
    }
 	//修改页面配置
    public function updateBuiltPageAjax(){
    	$params = $this->input(); 
    	$result = $this->mOtaTheme->updateBuiltPageAjax($params);
    	$this->outPut($result);
    }

    //获取页面的数据
    public function getBuiltPage(){
		$params = $this->input();
    	$result = $this->mOtaTheme->getBuiltPage($params);
    	$this->outPut($result);
    }

    //根据网站UUID 页面ID 获取页面信息    
    public function getPageInformation(){
        $params = $this->input();

        // $params['website_uuid'] = '2019071510100591fe5103197643b7c032ee778543f26e'; //网站设置ID
        // $params['ota_module_type_id'] =  1; //页面的ID 表-ota_module_type
        
        $paramRule = [  
            'website_uuid'=>'string',   
            'ota_module_type_id'=>'number',                    
        ];
                            
        $this->paramCheckRule($paramRule,$params);

        $result = $this->mOtaTheme->getPageInformation($params);
        $this->outPut($result);
    }


    //获取页面组件分类
    public function getSubassembly(){
        $params = $this->input(); 
        $result = $this->mOtaTheme->getSubassembly($params);
        $this->outPut($result);
    }


    //获取主题模块分类
    public function getTopicPageModule(){

        $params = $this->input();
        $result = $this->mOtaTheme->getTopicPageModule($params);
        $this->outPut($result);
    }


    //获取主题样式
    public function getOtaCss(){
        $params = $this->input(); 
        $result = $this->mOtaTheme->getOtaCss($params);
        $this->outPut($result);
    }


}