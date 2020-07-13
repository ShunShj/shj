<?php

namespace app\index\model\system;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class Country extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'country';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
    
    }

    /**
     * 添加国家
     * 胡
     */
    public function addCountry($params){
    
    	$t = time();
    	//如果添加国家
    	if($params['level'] == 1){
    		
    
 		
    		$data['country_code'] = $params['country_code'];
    		$data['pid'] = 0;

    	}else if($params['level'] == 2){
    		

    		$data['pid'] = $params['pid'];

    		
    	}else if($params['level'] == 3){

            if(is_numeric($params['language_id'])){
                $data['language_id'] = $params['language_id'];
            }
            if(isset($params['timezone'])){
                $data['timezone'] = $params['timezone'];
            }
            if(isset($params['currency_id'])){
                $data['currency_id'] = $params['currency_id'];
            }
    		$data['pid'] = $params['pid'];


    	}
    	$data['country_name'] = $params['country_name'];
    	$data['level'] = $params['level'];
    	if(!empty($params['lang_id'])){
    		$data['default_language_id'] = $params['lang_id'];
    	}
    	
    	$data['create_time'] = $t;
    	$data['create_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['user_id'];
    	$data['status'] = 1;
    	
    	

    	
    
    	Db::startTrans();
    	try{
    		$pk_id = Db::name('country')->insertGetId($data);
    		//插入数据到country_language
    		$country_language_data['country_id'] = $pk_id;
    		$country_language_data['country_name'] = $params['country_name'];
    		if(!empty($params['lang_id'])){
    			$country_language_data['language_id'] = $params['lang_id'];
    		}
    	
    		$country_language_data['create_time'] = $t;
    		$country_language_data['create_user_id'] = $params['user_id'];
    		$country_language_data['update_time'] = $t;
    		$country_language_data['update_user_id'] = $params['user_id'];
    		$country_language_data['status'] = 1;
    		
    		Db::name('country_language')->insert($country_language_data);
    		
    
    		$result = 1;
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
     * 获取国家数据
     * 胡
     */
    public function getCountry($params,$is_count=false){//第一个为参数，第二个为是否要获取 总数


    	$data = "1=1";
    	if(isset($params['country_id'])){
    		$data.= " and country.country_id = ".$params['country_id'];
    	}
    	if(isset($params['status'])){
    		$data.= " and country.status = ".$params['status'];
    	}
    	if(isset($params['country_name'])){
    		$data.= " and country.country_name like '%".$params['country_name']."%'";
    	}
    	if(isset($params['level'])){
    		$data.= " and country.level = ".$params['level'];
    	}

    	if(isset($params['pid'])){
    		$data.= " and country.pid = ".$params['pid'];
    	}
    	
    	if($is_count==true){
    		$result = $this->where($data)->count();
    		
    	}else{
    		if(isset($params['page'])){
    
		       $result = $this->table("country")->alias('country')->
		       join("language l","l.language_id = country.language_id",'left')->
		       join("currency cu","cu.currency_id= country.currency_id",'left')->
		       where($data)->order('create_time desc')->limit($params['page'],$params['page_size'])->
		       field(['country.country_id','country.pid','country. country_name',"l.language_name","cu.currency_name",
		       		  'l.language_name','cu.currency_name', 
		       		  'country.create_time','country.create_user_id',
		       		  "country.language_id","country.currency_id","country.country_code,country.level",'country.timezone',"country.status"])
					  ->order("create_time desc")->select();

    	
    		}else{
    			
		       $result = $this->table("country")->alias('country')->
		       join("language l","l.language_id = country.language_id",'left')->
		       join("currency cu","cu.currency_id= country.currency_id",'left')->
		       where($data)->            
		       field(['country.country_id','country.pid','country. country_name',"l.language_name","cu.currency_name",
		       		  'l.language_name','cu.currency_name', 
		       		  'country.create_time','country.create_user_id','default_language_id',
		       		"(select nickname  from user where user.user_id = country.create_user_id)"=>'create_user_name',
		       		"(select nickname  from user where user.user_id = country.update_user_id)"=>'update_user_name',
		       		  "country.language_id","country.currency_id","country.country_code,country.level",'country.timezone',"country.status"])
					  ->order("create_time desc")->select();
    		}
    	}

        return $result;
    
    }

    /**
     * getCountryCity
     *
     * 重写获取地区
     * @author shj
     *
     * @param      $params
     * @param bool $is_count
     *
     * @return array
     * Date: 2019/3/4
     * Time: 16:14
     */
    public function getCountryCity($params,$is_count=false){//第一个为参数，第二个为是否要获取 总数
        $data = "1=1";
        if(!empty($params['country_id'])){
            $data.= " and country.country_id = ".$params['country_id'];
        }
        if(isset($params['status'])){
            $data.= " and country.status = ".$params['status'];
        }
        if(isset($params['country_name'])){
            $data.= " and country.country_name like '%".$params['country_name']."%'";
        }
        if(isset($params['level'])){
            $data.= " and country.level = ".$params['level'];
        }
        if(isset($params['pid'])){
            $data.= " and country.pid = ".$params['pid'];
        }
     
        if($is_count==true){
            $result = $this->where($data)->count();
        }else {
            if (isset($params['page'])) {
                $result = $this->table("country")->alias('country')->
                join("language l", "l.language_id = country.language_id", 'left')->
                join("currency cu", "cu.currency_id= country.currency_id", 'left')->
                field(['country.*', "l.language_name", "cu.currency_name", 'l.language_name', 'cu.currency_name',
                    "(select country_name  from country as c where pid = c.country_id ) as city_name",
                ])->
                where($data)->limit($params['page'], $params['page_size'])->select();
                foreach ($result as $k => $v) {
                    if ($v['level'] == 1) {
                        $v['city_name'] = '';
                        $v['zone_name'] = '';
                    } elseif ($v['level'] == 2) {
                        $v['zone_name']    = '';
                        $v['city_name']    = $v['country_name'];
                        $countryInfo       = $this->getCountryInfo(['country_id' => $v['pid']]);
                        $v['country_name'] = $countryInfo[0]['country_name'];
                    } elseif ($v['level'] == 3) {
                        $v['zone_name']    = $v['country_name'];
                        $cityInfo          = $this->getCountryInfo(['country_id' => $v['pid']]);
                        $v['city_name']    = $cityInfo[0]['country_name'];
                        $countryInfo       = $this->getCountryInfo(['country_id' => $cityInfo[0]['pid']]);
                        $v['country_name'] = $countryInfo[0]['country_name'];
                    }
                }
            } else {
            	
                if($params['level'] == 1){
                    $result = Db::query("select a.country_id, a.country_name, a.country_en,'' province_id, '' province_name, '' province_en,'' city_id, '' city_name, '' city_en, 1 as `level` from country a where a.`level`=1 and a.`status`=1 order by country_name, province_name, city_name");
                }elseif ($params['level'] == 2){
                    $result = Db::query("select a.country_id, a.country_name, a.country_en,b.country_id province_id, b.country_name province_name, b.country_en province_en,'' city_id, '' city_name, '' city_en, 2 as `level` from country a, country b where a.`level`=1 and b.`level`=2 and b.pid=a.country_id and a.`status`=1 and b.`status`=1 ");
                }elseif ($params['level'] == 3){
                    $result = Db::query("select a.country_id, a.country_name, a.country_en, b.country_id province_id, b.country_name province_name, b.country_en province_en,c.country_id city_id, c.country_name city_name, c.country_en city_en, 3 as `level` from country a, country b, country c where a.`level`=1 and b.`level`=2 and c.`level`=3 and c.pid=b.country_id and b.pid=a.country_id and a.`status`=1 and b.`status`=1 and c.`status`=1");
                }
                else{
                    $result = Db::query("select a.country_id, a.country_name, a.country_en,b.country_id province_id, b.country_name province_name, b.country_en province_en,c.country_id city_id, c.country_name city_name, c.country_en city_en, 3 as `level` from country a, country b, country c where a.`level`=1 and b.`level`=2 and c.`level`=3 and c.pid=b.country_id and b.pid=a.country_id and a.`status`=1 and b.`status`=1 and c.`status`=1 UNION all select a.country_id, a.country_name, a.country_en,b.country_id province_id, b.country_name province_name, b.country_en province_en,'' city_id, '' city_name, '' city_en, 2 as `level` from country a, country b where a.`level`=1 and b.`level`=2 and b.pid=a.country_id and a.`status`=1 and b.`status`=1 UNION all select a.country_id, a.country_name, a.country_en,'' province_id, '' province_name, '' province_en,'' city_id, '' city_name, '' city_en, 1 as `level` from country a where a.`level`=1 and a.`status`=1 order by country_name, province_name, city_name");
                }
                
              
                foreach ($result as $k => $v) {
                    if ($v['level'] == 1) {
                        $result[$k]['id']   = $v['country_id'];
                        $result[$k]['cpc_name'] = $v['country_name'];
                        $result[$k]['cpc_en']   = $v['country_en'];
                    } elseif ($v['level'] == 2) {
                        $result[$k]['id']   = $v['province_id'];
                        $result[$k]['cpc_name'] = $v['country_name'] . '-' . $v['province_name'];
                        $result[$k]['cpc_en']   = $v['country_en'] . '-' . $v['province_en'];
                    } elseif ($v['level'] == 3) {
                        $result[$k]['id']   = $v['city_id'];
                        $result[$k]['cpc_name'] = $v['country_name'] . '-' . $v['province_name'] . '-' . $v['city_name'];
                        $result[$k]['cpc_en']   = $v['country_en'] . '-' . $v['province_en'] . '-' . $v['city_en'];
                    }
                }
            }
        }
        
     
        return $result;
    }

    /**
     * 获取国家数据
     * 胡
     */
    public function getCountryInfo($params){
    
    
    	$data = "1=1";
    	if(isset($params['country_id'])){
    		$data.= " and co.country_id = ".$params['country_id'];
    	}

    	$result = $this->table("country")->alias('co')->
    	join("language l","l.language_id = co.language_id",'left')->
    	join("currency cu","cu.currency_id= co.currency_id",'left')->
    	where($data)->
    	field(['co.*',"l.language_name","cu.currency_name"])->select();
    	return $result;
    	
    }   
    /**
     * 通过城市ID获取该城市父级以及爷爷级的信息
     * 胡
     */
    public function getCityTop($params){
    	$data = "1=1 and country.level=3";
    	if(is_numeric($params['city_id'])){
    		$data.=" and country.country_id =".$params['city_id'];
    	}
        if(is_numeric($params['status'])){
            $data.= " and country.status = ".$params['status'];
        }
    	$result = $this->table("country")->alias('country')->

    	where($data)->
    	field(['country_id as city_id','pid as city_pid','country_name as city_name',
    		 "(select country_name as province_name   from country as province where city_pid = province.country_id ) province_name",// as province_name
    		 "(select country_id as province_id from country as province where city_pid = province.country_id ) as province_id",
    		 "(select pid  from country as province where city_pid = province.country_id ) as province_pid",
    		 "(select country_name  from country as c where province_pid = c.country_id ) as country_name",
    		 "(select country_id  from country as c where province_pid = c.country_id ) as country_id",
    		 "(select pid  from country as c where province_pid = c.country_id ) as country_pid",
            "country.status"])->select();
    	return $result;    	
    	
    }
    /**
     * 通过省市ID获取该省市父级
     * 胡
     */
    public function getProvinceTop($params){
    	$data = "1=1 and country.level=2";
    	if(is_numeric($params['province_id'])){
    		$data.=" and country.country_id =".$params['province_id'];
    
    	}
    	$result = $this->table("country")->alias('country')->
    
    	where($data)->
    	field(['country_id as province_id','pid as province_pid','country_name as province_name',

    			"(select country_name  from country as c where province_pid = c.country_id ) as country_name",
    			"(select country_id  from country as c where province_pid = c.country_id ) as country_id",
    		
    				
    	])->select();
    	return $result;
    	 
    }   
    /**
     * 获取国家数据根据COUNTRY_ID 与lang_id
     */
    public function getCountryByCountryIdLangId($params){
    	$country_id = $params['country_id'];
    	$lang_id = $params['lang_id'];
    	$data['language_id'] = $lang_id;
    	$data['country_id'] = $country_id;
    	$result = $this->table('country_language')->
    	where($data)->field("")->find();
    	return $result;
    }
    
    /**
     * 修改国家多语言数据根据国家多语言ID
     */
    public function updateCoungtryLanguageByCountryLanguageId($params){

    	$t = time();
    	$user_id = $params['user_id'];
    	$original_data['country_id'] = $params['country_id'];
		
    	
    	$params = $params['data'];
    	//原始数据主键
    	$original_id = $params[0]['country_id'];
    	$country_result = $this->getCountry($original_data);
    	$default_language_id = $country_result[0]['default_language_id'];
    	$this->startTrans();
    	try{
    		for($i=0;$i<count($params);$i++){
    			$data = [];
    			if(!trim($params[$i]['country_name'])==''){
    			
    				$data['country_name'] = $params[$i]['country_name'];
    				$data['update_time'] = $t;
    				$data['update_user_id'] = $user_id;
    				
    				if(is_numeric($params[$i]['country_language_id'])){
    					$this->table('country_language')->where("country_language_id = ".$params[$i]['country_language_id'])->update($data);
    					//再查询是否是原始数据  如果是原始数据那么原始 数据也要更改
    					if($default_language_id == $params[$i]['lang_id']){
    							
    						$this->where("country_id = ".$original_id)->update($data);
    					
    					}
    				}else{
    					
    					$data['create_time'] = $t;
    					$data['create_user_id'] = $user_id;
    					$data['status'] = 1;
    					$data['country_id'] = $original_id;
    					$data['language_id'] = $params[$i]['lang_id'];
    				
						$this->table("country_language")->insert($data);
    			
    				}
    			}    			
    		}

    		$result = 1;
    		// 提交事务
    		$this->commit();
    	
    	} catch (\Exception $e) {
    		$result = $e->getMessage();
    		// 回滚事务
    		$this->rollback();
    	
    	}
    	return $result;
    	
    }
    
    
    /**
     * 修改国家 根据country_id
     */
    public function updateCountryByCountryId($params){
    	
    	$t = time();
    	
    	//开始判断层级，不同层级修改的内容不一样
    	if($params['level'] == 1){
    		if(!empty($params['country_code'])){ //国家 代号
    			$data['country_code'] = $params['country_code'];
    		
    		}
    		
    	}else if($params['level'] == 2){
    		$data['pid'] = $params['pid'];
    	}else if($params['level'] == 3){
    		$data['pid'] = $params['pid'];
            if(isset($params['language_id'])){
                $data['language_id'] = $params['language_id'];
            }
            if(isset($params['timezone'])){
                $data['timezone'] = $params['timezone'];
            }
            if(isset($params['currency_id'])){
                $data['currency_id'] = $params['currency_id'];
            }
    	}
    	
    	if(!empty($params['status'])){
    		$data['status'] = $params['status'];
    
    	}

        if(!empty($params['country_name'])){
    		$data['country_name'] = $params['country_name'];
    
    		
    	}
    	$data['update_user_id'] = $params['user_id'];
    
    	$data['update_time'] = $t;

    
    

    	Db::startTrans();
    	try{
    		Db::name('country')->where("country_id = ".$params['country_id'])->update($data);

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
    
    public function del_country($params){
    	$this->where($params)->delete();
    	error_log(print_r($this->getlastsql(),1));
    	return 1;
    	
    }

    public function getOneCountry($country_id){
        $result = $this->table("country")->where(['country_id' => $country_id])->find();
        return $result;
    }
}