<?php
namespace app\index\controller;
use app\common\help\Help;
use app\index\controller\Base;
use \think\File;
use think\Session;
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
 		$language_tag = Session::get('language_tag');
 		
 		$found_key = array_search('logo', array_column($language_tag, 'code_name'));
 		
 	
 	}
    public function test2(){
	
        $data = [

            
       

    	];
        $result =  $this->callSoaErpTest('post','/branchcompany/getBranchProduct',$data);//getSingleSource
		
        return json_encode($result);

    }
    public function test3(){

		$data = [

			'company_order_number'=>'BO-300'
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
        $Currency = $this->callSoaErpTest('post','/branchcompany/getCompanyOrderCustomer',$data);
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
				'team_product_number'=>'asd',
    			'cope_info_array'=>[
    				[
    					'source_type_id'=>2,
    					'payment_object_id'=>1,
    					'order_number'=>'asd',	
    					'product_name'=>1,
    					
    					'cope_currency_id'=>1,

    					'cope_name'=>12,	
    						
    				],
    					[
    					'cope_number'=>'CO20190224972204',
    					'source_type_id'=>2,
    					'payment_object_id'=>1,
    					'order_number'=>'asd',
    					'product_name'=>1,
    						
    					'cope_currency_id'=>1,
 
    					'cope_name'=>12,
    					'company_order_customer_id'=>'18,28'
    					]
    					
    			],
			   //'company_order_number'=>'BO20190201530960' 		
			'receivable_info_id'=>43,

    	
    	];
		$data = [
			
		'company_order_number'=>'BO-300'
		//'company_order_customer_id'=>258
			//'company_id'=>3
		//'company_order_number'=>'BO20190325083211',
// 			'page'=>1,
// 			'limit'=>1
		];
		
    	///branchcompany/getSaleBill
    	//$result =  $this->callSoaErpTest('post','/branchcompany/getCompanyOrderProduct',$data);
    	$result =  $this->callSoaErpTest('post','/branchcompany/getCompanyOrderProduct',$data);
  	 //	$result =  $this->callSoaErpTest('post','/branchcompany/updateReceivableAndCopeToCompanyOrderProduct',$data);
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
