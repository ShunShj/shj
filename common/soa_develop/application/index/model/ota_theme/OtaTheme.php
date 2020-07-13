<?php

namespace app\index\model\ota_theme;
use app\common\help\Help;
use think\Model;

use think\config;
use think\Db;
//主题
class OtaTheme extends Model{ 
  	private $_languageList;

    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
	  	parent::initialize();
	}

	//获取主题
	public function getTheme($params){
		$where = '1=1';
		if(is_numeric($params['status'])){
			$where .= " and ota_theme.status={$params['status']}";
		}
		if($params['ota_theme_id']){
			$where .= " and ota_theme.ota_theme_id={$params['ota_theme_id']}";
		}
		return $this->table('ota_theme')->field(['ota_theme_id',
			'list_diagram',
			'title',
			'describe',
			'file_name',
			'include_module',
			'status'
		])->where($where)->select();	
	} 		

	public function addSiteThemes($params){
		 $ad['ota_theme_id'] = $params['ota_theme_id'];
         $ad['website_uuid'] = $params['website_uuid']; //网站设置ID;
         return $this->table('ota_site_themes')->insertGetId($ad);
	}

	//修改网站主题
	public function upSiteThemes($params){
		$w['ota_site_theme_id'] = $params['ota_site_theme_id'];
		$u['ota_theme_id'] = $params['ota_theme_id'];
		 
		$this->table('ota_site_themes')->where($w)->update($u);
		return 1;
	}

	//获得网站主题
	public function getSiteThemes($params){
		$w['website_uuid'] = $params['website_uuid'];
		
		$ot = $this->table('ota_site_themes')->field(['ota_site_theme_id','ota_theme_id',
"(select title from ota_theme where ota_theme.ota_theme_id=ota_site_themes.ota_theme_id) as ota_theme_title"
	])->where($w)->find();
		
		// error_log(print_r($ot,1));

		return $ot;

	}

	//获取页面列表
	public function getPage($params){
		$w = 1;
		if($params['ota_page_id']){
			$w .= " and ota_page_id={$params['ota_page_id']}";
		}
		if($params['ota_theme_module_id']){
			$w .= " and ota_theme_module_id={$params['ota_theme_module_id']}";
		}
		if($params['title']){
			$w .= " and title like '%{$params['title']}%'";
		}
		if(is_numeric($params['status'])){
			$w .= " and status={$params['status']}";
		}
		if($params['website_uuid']){
			$w .= " and website_uuid='{$params['website_uuid']}'";
		}
		if($params['ota_theme_id']){
			$w .= " and ota_theme_id={$params['ota_theme_id']}";
		}
	
 		$oc = $this->table('ota_page')->field([
			'ota_page_id','ota_theme_module_id','title','pv','sort','status'
		])->where($w)->order('sort desc')->select();
 		return $oc;
	}


	//获取主题模块
	public function getThemeModule($params){
		$w = 1;
		if($params['ota_theme_id']){
			$w .= " and ota_theme_module.ota_theme_id={$params['ota_theme_id']}";
		}

		if($params['ota_theme_module_id']){
			$w .= " and ota_theme_module.ota_theme_module_id={$params['ota_theme_module_id']}";
		}

		// error_log(print_r($w,1));

		return $this->table('ota_theme_module')->field([
			'ota_theme_module.ota_theme_module_id',
			'ota_theme_module.title',
			'ota_theme_module.describe',
			'ota_theme_module.number_of_primary_keys',
			'ota_theme_module.status',
			'ota_module_type.title as module_type_title',
			'ota_module_type.html_file_name',
		])->join('ota_module_type','ota_module_type.ota_module_type_id=ota_theme_module.ota_module_type_id','left')->where($w)->select(); 
	}

	//添加页面配置
	public function newBuiltPageAjax($params){
			Db::startTrans();
		try{  
			$data['ota_theme_module_id'] = $params['ota_theme_module_id'];
			$data['title'] = $params['page_title'];
			$data['pv'] = $params['pv'];
			$data['sort'] = $params['sort'];
			$data['status'] = $params['page_status'];
			$data['ota_theme_id'] = $params['ota_theme_id'];
			$data['website_uuid'] = $params['website_uuid'];
			$data['create_user_id'] = $params['user_id'];
			$data['update_user_id'] = $params['user_id'];
			$data['create_time'] = time();
			$data['update_time'] = time();
			$ota_page_id = DB::table('ota_page')->insertGetId($data);	
			unset($data);
			
			foreach ($params['d']  as $key => $value) {
				 $data['ota_page_id'] = $ota_page_id;
				 $data['subassembly_id'] = $value['subassemblyId'];
				 if($value['subassemblyId']==7){ //文本编译
					$data['shujutext'] = $value['shuju'];
				 }else{
				 	$data['shuju'] = $value['shuju'];
				 }
				 $data['position_n'] = $value['position_n'];
				 $data['ota_css_id'] = $value['css_id'];
				 $data['status'] = 1;
				 DB::table('ota_components_under_the_page')->insert($data);
				 unset($data);
			} 
            Db::commit();
            return $ota_page_id;
		}catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            Db::rollback(); 
        }

        return $result;
	}

	//修改页面配置
	public function updateBuiltPageAjax($params){
		$ota_page_id = $params['ota_page_id'];
		Db::startTrans();
		try{ 
			$w['ota_page_id'] = $ota_page_id;
			$data['ota_theme_module_id'] = $params['ota_theme_module_id'];
			$data['title'] = $params['page_title'];
			$data['pv'] = $params['pv'];
			$data['sort'] = $params['sort'];
			$data['status'] = $params['page_status'];
			$data['ota_theme_id'] = $params['ota_theme_id'];
			$data['website_uuid'] = $params['website_uuid']; 
			$data['update_user_id'] = $params['user_id']; 
			$data['update_time'] = time();
			DB::table('ota_page')->where($w)->update($data);	
			unset($data);

			$data['status'] = 2;
			Db::table('ota_components_under_the_page')->where($w)->update($data);	
			unset($data);

			foreach ($params['d']  as $key => $value) {
				 $data['ota_page_id'] = $ota_page_id;
				 $data['subassembly_id'] = $value['subassemblyId'];
				 if($value['subassemblyId']==7){ //文本编译
					$data['shujutext'] = $value['shuju'];
				 }else{
				 	$data['shuju'] = $value['shuju'];
				 }
				 $data['position_n'] = $value['position_n'];
				 $data['ota_css_id'] = $value['css_id'];
				 $data['status'] = 1;
				 DB::table('ota_components_under_the_page')->where(['ota_components_under_the_page_id'=>$value['ota_components_under_the_page_id']])->update($data);
				 // DB::table('ota_components_under_the_page')->insert($data);
				 unset($data);
			} 
			Db::commit();
			return $ota_page_id;
		}catch (\Exception $e) {
            $result = $e->getMessage();
            // 回滚事务
            Db::rollback(); 
        }
		return $result;
	}


	//获取页面数据
	public function getBuiltPage($params){
		$w['ota_page_id'] = $params['ota_page_id'];
		$d['ota_page'] = $this->table('ota_page')->where($w)->find();
		$w['status'] = 1;
		$d['ota_components_under_the_page'] = $this->table('ota_components_under_the_page')->where($w)->select();
		return $d;
	}	

	//根据网站UUID 页面ID 获取页面信息
	public function getPageInformation($params){
		$website_uuid = $params['website_uuid'];
		$ota_module_type_id = $params['ota_module_type_id'];
	
		//获取网站主题
		$ota_theme_obj = $this->table('ota_theme')->field([
			'ota_theme.ota_theme_id',
			'ota_theme.list_diagram',
			'ota_theme.title',
			'ota_theme.describe',
			'ota_theme.file_name',
			'ota_theme.include_module',
			'ota_theme.status', 
		])
		->join('ota_site_themes','ota_theme.ota_theme_id=ota_site_themes.ota_theme_id')
		->where("ota_site_themes.website_uuid='{$website_uuid}'")->find();
	
		if(empty($ota_theme_obj)){
			$ota_theme_obj = $this->table('ota_theme')->field([
				'ota_theme.ota_theme_id',
				'ota_theme.list_diagram',
				'ota_theme.title',
				'ota_theme.describe',
				'ota_theme.file_name',
				'ota_theme.include_module',
				'ota_theme.status', 
			])->where("ota_theme_id=1")->find();
		}
	
		//获取网站的配置
		$w  =  "ota_theme_module_id = (select ota_theme_module.ota_theme_module_id from ota_theme_module where ota_theme_module.ota_theme_id={$ota_theme_obj['ota_theme_id']} and ota_theme_module.ota_module_type_id={$ota_module_type_id})";
		$w  .=  " and ota_theme_id={$ota_theme_obj['ota_theme_id']}";
		$w .= " and website_uuid='{$website_uuid}'";
		$w .= " and status=1";

		$ota_page_obj = $this->field([
			'ota_page_id',
			'ota_theme_module_id',
			'title',
			'pv',
			'sort',
			'status',
			'ota_theme_id',
			'website_uuid',
			"(select title from ota_module_type where ota_module_type_id=ota_theme_module_id) as module_type_title",
			"(select html_file_name from ota_module_type where ota_module_type_id=ota_theme_module_id) as html_file_name", 
		])->table('ota_page')->order('sort asc')->where($w)->find();
		unset($w);
		
		
		
		
		$data['ota_components_under_the_page'] = [];
		if(!empty($ota_page_obj)){
			$w['status'] = 1;
			$w['ota_page_id'] = $ota_page_obj['ota_page_id'];
			$data['ota_components_under_the_page'] = $this->field([
			'*',"(select ota_css.url from ota_css where ota_css.id=ota_components_under_the_page.ota_css_id) as css_url" 
			])->table('ota_components_under_the_page')->where($w)->select();
		} 

		$data['ota_theme'] = $ota_theme_obj;
		$data['ota_page'] = $ota_page_obj;
		return $data;

	}

	//获取页面组件分类
    public function getSubassembly($params){
    	if($params['status']){
    		$where['status'] = $params['status'];
    	}
    	$ota_subassembly = $this->field([
    		'subassembly_id','name'
    	])->table('ota_subassembly')->where($where)->select();
		return $ota_subassembly;
	}

	//获取主题模块分类
    public function getTopicPageModule($params){
    	if($params['ota_theme_module_id']){
    		$where['ota_theme_module_id'] = $params['ota_theme_module_id'];
    	}
		if($params['status']){
    		$where['status'] = $params['status'];
    	} 
    	return $this->field([
    		'topic_page_module_id','ota_theme_module_id','subassembly_id','position_n'
    	])->table('ota_topic_page_module')->where($where)->select();

    }

    //获取主题样式
    public function getOtaCss($params){
    	$where = '1=1';
    	if($params['status']){
    		$where .= " and status={$params['status']}";
    	}

		return $this->field([
    		'id','tit','url','subassembly_id',
    		'(select ota_subassembly.name from ota_subassembly where ota_subassembly.subassembly_id=ota_css.subassembly_id) as subassembly_name' 
    	])->table('ota_css')->where($where)->select();
    }


}