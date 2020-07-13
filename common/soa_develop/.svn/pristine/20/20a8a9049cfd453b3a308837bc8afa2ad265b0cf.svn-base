<?php
namespace app\index\model\product;
use think\Model;
use app\common\help\Help;
use think\config;
use think\Db;
class RouteJourney extends Model{
    //protected $connection = ['database' => 'erp'];
    protected $table = 'route_journey';
    private $_languageList;
    public function initialize()
    {
    	$this->_languageList = config('systom_setting')['language_list'];
    	parent::initialize();
     
    }

    /**
     * 添加路线行程表
     * 胡
     */
    public function addRouteJourney($params){
    	$t = time();


    	$data['route_template_id'] = $params['route_template_id'];
    	
    	if(!empty($params['the_days'])){
    		$data['the_days'] = $params['the_days'];
    	}
    	if(!empty($params['route_journey_title'])){
    		$data['route_journey_title'] = $params['route_journey_title'];
    	}
    	if(!empty($params['route_journey_content'])){
    		$data['route_journey_content'] = $params['route_journey_content'];
    	}
    	if(!empty($params['route_journey_traffic'])){
    		$data['route_journey_traffic'] = $params['route_journey_traffic'];
    	}
    	if(!empty($params['route_journey_stay'])){
    		$data['route_journey_stay'] = $params['route_journey_stay'];
    	}

    	if(!empty($params['route_journey_breakfast'])){
    		$data['route_journey_breakfast'] = $params['route_journey_breakfast'];
    	}
    	if(!empty($params['route_journey_lunch'])){
    		$data['route_journey_lunch'] = $params['route_journey_lunch'];
    	}    	
    	if(!empty($params['route_journey_dinner'])){
    		$data['route_journey_dinner'] = $params['route_journey_dinner'];
    	}
    	if(!empty($params['eat_mark'])){
    		$data['eat_mark'] = $params['eat_mark'];
    	}
    	if(!empty($params['route_journey_scenic_sport'])){
    		$data['route_journey_scenic_sport'] = $params['route_journey_scenic_sport'];
    	} 
    	if(!empty($params['route_journey_picture'])){
    		$data['route_journey_picture'] = $params['route_journey_picture'];
    	}
    	if(!empty($params['route_journey_remark'])){
    		$data['route_journey_remark'] = $params['route_journey_remark'];
    	}
    	     	
    	
    	$data['create_time'] = $t;
    	$data['create_user_id'] = $params['user_id'];
    	$data['update_time'] = $t;
    	$data['update_user_id'] = $params['user_id'];
    	
    	$data['status'] = $params['status'];
    	

    	
        $this->startTrans();
    	try{
    		$result = $this->insertGetId($data);
			
    		 
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
     * 获取路线出团
     * 胡
     */
    public function getRouteJourney($params){
    

    	$data = [];
    	if(isset($params['route_journey_id'])){
    		$data['route_journey_id']= $params['route_journey_id'];
    	}
    	if(isset($params['route_template_id'])){
    		$data['route_template_id']= $params['route_template_id'];
    	}
    	if(isset($params['status'])){
    		$data['status']= $params['status'];
    	}
    	 
        $result = $this->table("route_journey")->alias('route_journey')->where($data)->
            
            field([
            		"route_journey.route_journey_id","route_journey.route_template_id","route_journey.the_days","route_journey.route_journey_title",
            		"route_journey.route_journey_content","route_journey.route_journey_traffic","route_journey.route_journey_stay","route_journey.eat_mark",
            		"route_journey.route_journey_zone",
                    "route_journey.route_journey_breakfast","route_journey.route_journey_lunch","route_journey.route_journey_dinner","route_journey.route_journey_scenic_sport",
            		"route_journey.route_journey_picture","route_journey.route_journey_remark",
            		"(select nickname  from user where user.user_id = route_journey.create_user_id)"=>'create_user_name',
            		"(select nickname  from user where user.user_id = route_journey.update_user_id)"=>'update_user_name',
            		"route_journey.create_user_id","route_journey.update_user_id","route_journey.update_time",
            		"route_journey.create_time","route_journey.status"
            		
            		
            ])->select();

        return $result;
    
    }

    
    /**
     * 修改路线行程
     */
    public function updateRouteJourneyByRouteJourneyId($params){
    
    	$t = time();
    	
        if(!empty($params['the_days'])){
    		$data['the_days'] = $params['the_days'];
    	}
    	if(!empty($params['route_journey_title'])){
    		$data['route_journey_title'] = $params['route_journey_title'];
    	}
    	if(!empty($params['route_journey_content'])){
    		$data['route_journey_content'] = $params['route_journey_content'];
    	}
    	if(!empty($params['route_journey_traffic'])){
    		$data['route_journey_traffic'] = $params['route_journey_traffic'];
    	}
    	if(!empty($params['route_journey_stay'])){
    		$data['route_journey_stay'] = $params['route_journey_stay'];
    	}

    	if(!empty($params['route_journey_breakfast'])){
    		$data['route_journey_breakfast'] = $params['route_journey_breakfast'];
    	}
    	if(!empty($params['route_journey_lunch'])){
    		$data['route_journey_lunch'] = $params['route_journey_lunch'];
    	}    	
    	if(!empty($params['route_journey_dinner'])){
    		$data['route_journey_dinner'] = $params['route_journey_dinner'];
    	}
    	if(!empty($params['eat_mark'])){
    		$data['eat_mark'] = $params['eat_mark'];
    	}
    	if(!empty($params['route_journey_scenic_sport'])){
    		$data['route_journey_scenic_sport'] = $params['route_journey_scenic_sport'];
    	} 
    
    		$data['route_journey_picture'] = $params['route_journey_picture'];
    
    	if(!empty($params['route_journey_remark'])){
    		$data['route_journey_remark'] = $params['route_journey_remark'];
    	}
    	if(is_numeric($params['status'])){
    		$data['status'] = $params['status'];
    	}   	     	
    	

    	$data['update_user_id'] = $params['user_id'];   
    	$data['update_time'] = $t;

    
    
 		
    	Db::startTrans();
    	try{
    		Db::name('route_journey')->where("route_journey_id = ".$params['route_journey_id'])->update($data);
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
}