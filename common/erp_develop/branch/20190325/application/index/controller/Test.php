<?php
namespace app\index\controller;
use app\common\help\Help;
use app\index\controller\Base;
use \think\File;
class Test extends Base
{
    public function __initialize()
    {

    }
	//获取 分公司产品-团队产品-自费项目列表
    public function test(){
    	
		$this->checkOwnInfo("");
    	
		//获取自费项目
		//$this-get(’自费项目‘);
        return $this->fetch('index');
    }
 	public function aa(){
 		return 221;
 	}
    public function test2(){
	
        $data = [

            'status'=>1,
        	'company_order_number'=>'BO20181226288746'	

    	];
        $result =  $this->callSoaErp('post','/branchcompany/aa',$data);//getSingleSource
		
        return json_encode($result);

    }
    public function test33(){

		$data = [
//         	'distributor_id'=>'2422',
// 			'bill_template_id'=>3,
			//'company_order_number'=>'BO20190110383566',
	
//			'team_product_number'=>'TP20190110532776',
// 			'can_watch_company_id'=>13	
// 			'receivable_info_id'=>'37,38'	
		//	'company_order_create_to_time'=>20181226	
				'wr'=>'1',
				'clientsource'=>'1',
				'channel_type'=>'2',
				 
				
				'begin_time'=>time(),
				'end_time'=>time(),
			
				'depart_city_id'=>15,
				'remark'=>1233,
				'contect_name'=>1,
				'tel'=>21321,
				'email'=>'asda',
				'customer_info'=>[
					'zhanwei_count'=>3,
					'customer_array'=>[
						['customer_first_name'=>'ac','customer_last_name'=>'b','customer_type'=>1,'gender'=>1,'country_id'=>1,'language_id'=>1,'card_type'=>1,'card_number'=>111,
							'customer_flight_info'=>[
								[
								'flight_code'=>1,'flight_begin_time'=>time(),'flight_end_time'=>time(),'flight_type'=>1,'start_place'=>'a','end_place'=>'b',
								],
								[
										'flight_code'=>2,'flight_begin_time'=>time(),'flight_end_time'=>time(),'flight_type'=>2,'start_place'=>'a','end_place'=>'b',
								]
									
									
									
							],
							'customer_accommodation_info'=>[
								'room_code'=>1,
								'room_type'=>2,
								'check_in'=>'+1',
								'check_on'=>'-1',
								'check_in_hotel'=>'a,2,3'	
							]
		
								
								
								
						],	
						['customer_first_name'=>'aa','customer_last_name'=>'bb','customer_type'=>1,'gender'=>1,'country_id'=>1,'language_id'=>1,'card_type'=>1,'card_number'=>1111]
								
							
					]	
						
						
				]
				
        ];
//        $result =  $this->callSoaErp('post','/finance/getReceivable',$data);
        //获取比率
//       		$result =  $this->callSoaErp('post','/branchcompany/updateCompanyOrderReveivableAndCope',$data);       
//         $result =  $this->callSoaErp('post','/finance/updateApportionProportionByApportionProportionId',$data);
//           $result =  $this->callSoaErp('post','/finance/getApportionProportionList',$data);
//        $result =  $this->callSoaErp('post','/finance/updateApportionProportion',$data);
//        $result =  $this->callSoaErp('post','/system/getTax',$data);
       	//$result =  $this->callSoaErp('post','/btob/getDistributorBill',$data);
//       	$result = $this->callSoaErp('post', '/branchcompany/getSaleBill',$data);
        $Currency = $this->callSoaErp('post','/branchcompany/addCompanyOrderBigArray',$data);
        //return json_encode($result);
    }

    public function test1(){
    
    	$data = [
//     		'branch_product_number'=>'BP20190128651748',	
//     		'branch_product_name'=>'胡测试2',
//     		'branch_product_begin_time'=>time(),
//     		'branch_product_end_time'=>time(),
// 			'branch_product_type_id'=>1,
//     		'team_product_array'=>[
//     			['team_product_number'=>'TP20181214538888','team_product_name'=>'team_product_name','supplier_name'=>'无锡分公司','branch_cost'=>1,'cost_currency_id'=>1,'price_currency_id'=>1,'branch_distributor_price'=>2,'branch_customer_price'=>3,'settlement_type'=>1,
//     				"own_expense_array"=>[
//     					['source_id'=>11,'supplier_type_id'=>11,'source_number'=>'a','source_name'=>1,'source_cost'=>2,'cost_currency_id'=>3,'source_distributor_price'=>4,'source_customer_price'=>5,'price_currency_id'=>6,'supplier_name'=>'test']	
    						
//     					,['source_id'=>12,'supplier_type_id'=>11,'source_number'=>'b','source_name'=>213,'source_cost'=>123,'cost_currency_id'=>1,'source_distributor_price'=>1,'source_customer_price'=>1,'price_currency_id'=>1,'supplier_name'=>'test']
    							    						
//     				]		

		
    					
    					
//     			],
//     			['team_product_number'=>'TP20181221291237','team_product_name'=>'HUGH 测试','supplier_name'=>'北京分公司','branch_cost'=>1,'cost_currency_id'=>1,'price_currency_id'=>1,'branch_distributor_price'=>2,'branch_customer_price'=>3,'settlement_type'=>2],
    				
//     		],
//     		'source_array'=>[
//     				['source_id'=>11,'supplier_type_id'=>11,'source_number'=>'a','source_name'=>1,'source_cost'=>2,'cost_currency_id'=>3,'source_distributor_price'=>4,'source_customer_price'=>5,'price_currency_id'=>6,'supplier_name'=>'test']
    				
//     			   ,['source_id'=>12,'supplier_type_id'=>11,'source_number'=>'b','source_name'=>213,'source_cost'=>123,'cost_currency_id'=>1,'source_distributor_price'=>1,'source_customer_price'=>1,'price_currency_id'=>1,'supplier_name'=>'test']
    				
//     		],

//   			'team_product_number'=>'1901',
//     		'is_branch_product'=>1	
    		//'branchroutetype_search'=>2
    		//'supplier_type_id'=>3
    		
//     			'branch_product_name'=>input('branch_product_name'),
//     			'branch_product_number'=>input('branch_product_number'),
//     			'create_user_name'=>input('create_user_name'),
//    			'branch_product_type_id'=>1,
				// 'source_number'=>'HLT20181217623649'
    			'team_product_number'=>'TP20181214538888'
    			
    	];
    	///branchcompany/getSaleBill
    	$result =  $this->callSoaErpTest('post','/product/getTeamProductBase',$data);
    	//$result =  $this->callSoaErpTest('post','/branchcompany/getBranchProduct',$data);
    	//return json_encode($result);
    }
    
    
    //获取语言包
    public function getLanguage(){
    	
    	
//     	$language = lang();
	  	
//     	$a = array_keys($language);
//     	for($i=0;$i<count($a);$i++){
//     		echo $a[$i]."<br />";
//     	}
    	//
    	
    }
    
}
