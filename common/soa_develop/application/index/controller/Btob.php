<?php
namespace app\index\controller;
use app\common\help\Contents;
use app\common\help\Help;
use app\index\model\btob\AccountCode;
use app\index\model\btob\CommissionTable;
use app\index\model\btob\DistributorType;
use app\index\model\btob\News;
use app\index\model\btob\TourType;
use app\index\model\btob\Product;
use think\config ;
use app\index\model\btob\Distributor;
use app\index\model\branchcompany\CompanyOrder;
use app\index\model\branchcompany\CompanyOrderProduct;
use app\index\model\branchcompany\CompanyOrderProductSource;
use app\index\model\branchcompany\CompanyOrderProductDiy;
use app\index\model\branchcompany\CompanyOrderCustomer;
use app\index\model\branchcompany\CompanyOrderRelation;
use app\index\model\system\BillTemplate;
use think\Model;
use think\Controller;
class Btob extends Base
{
	private $_language;
	private $_company_order;
	private $_company_order_product;
	private $_company_order_product_source;
	private $_company_order_product_diy;
	private $_company_order_relation;
	private $_company_order_customer;
	private $_bill_template;
    //_lang Base里的属性，
    public function __construct()
    {
    	$this->_language = config("systom_setting")['language_default'];
    	$this->_company_order =new CompanyOrder();
    	$this->_company_order_product =new CompanyOrderProduct();
    	$this->_company_order_product_source =new CompanyOrderProductSource();
    	$this->_company_order_product_diy =new CompanyOrderProductDiy();
    	$this->_company_order_relation =new CompanyOrderRelation();
    	$this->_company_order_customer =new CompanyOrderCustomer();
    	$this->_bill_template = new BillTemplate();
        parent::__construct();
    }

    public function addDistributor(){

        $params = $this->input();

        $paramRule = [
            'company_id' => 'mumber',
            'distributor_name'=>'string',
            'associate_type'=>'string',
            'status'=>'number',
            'user_id'=>'number'

        ];
        $this->paramCheckRule($paramRule,$params);

        //首先判断名字是否有重复
        $data = [
            'distributor_name'=>$params['distributor_name'],
            'company_id'=>$params['company_id'],
        ];
        $this->checkNameIsRepetition('distributor',$data);
        //结束判断名字重复

        $distributor = new Distributor();
        $distributor_result = $distributor->addDistributor($params);

        $this->outPut($distributor_result);
    }
    /**
     */
    public function getDistributor(){


        $params = $this->input();

        $distributor = new Distributor();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $distributor->getDistributor($params, true);
            $result = $distributor->getDistributor($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
        $distributor_result = $distributor->getDistributor($params);

        $this->outPut($distributor_result);

    }

    /**
     */

    public function updateDistributorByDistributorId(){
        $params = $this->input();

        $paramRule = [
            'distributor_id' => 'number',
            'user_id'=>'number',

        ];

        $this->paramCheckRule($paramRule,$params);


        $distributor = new Distributor();
        $Info = $distributor->getDistributorByDistributorId($params['distributor_id']);
        if($Info['distributor_name'] == $params['distributor_name']){
        }else{

            //开始判断名字是否重复
            $data = [
                'distributor_name'=>$params['distributor_name'],
                'company_id'=>$Info['company_id'],
            ];
            $this->checkNameIsRepetition('distributor',$data);
            //结束判断名字重复
        }


        $distributor_result = $distributor->updateDistributorByDistributorId($params);
        $this->outPut($distributor_result);


    }

    public function addDistributorType(){

        $params = $this->input();

        $paramRule = [
            'company_id' => 'mumber',
            'distributor_type_name'=>'string',
            'status'=>'number',
            'user_id'=>'number'
        ];
        $this->paramCheckRule($paramRule,$params);

        //首先判断名字是否有重复
        $data = [
            'distributor_type_name'=>$params['distributor_type_name'],
            'company_id'=>$params['company_id'],
        ];
        $this->checkNameIsRepetition('distributor_type',$data);
        //结束判断名字重复

        $model = new DistributorType();
        $result = $model->addDistributorType($params);

        $this->outPut($result);
    }
    /**
     */
    public function getDistributorType(){


        $params = $this->input();

        $model = new DistributorType();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getDistributorType($params, true);
            $result = $model->getDistributorType($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
        $result = $model->getDistributorType($params);

        $this->outPut($result);

    }

    /**
     */

    public function updateDistributorType(){
        $params = $this->input();

        $paramRule = [
            'distributor_type_id' => 'number',
            'user_id'=>'number',
        ];

        $this->paramCheckRule($paramRule,$params);
        $model = new DistributorType();
        $result = $model->updateDistributorType($params);
        $this->outPut($result);

    }






    /**
     * 添加分销商数据
     * 胡
     */
    public function addBtoBDistributor(){
    
    	$params = $this->input();
    
    	$paramRule = [
 			'company_id' => 'number',
    		'distributor_name'=>'string',
    		'distributor_type'=>'number',
    		'status'=>'number',
    		'user_id'=>'number'	
    	];
    	$this->paramCheckRule($paramRule,$params);

        //首先判断名字是否有重复
        $data = [
            'distributor_name'=>$params['distributor_name'],
            'company_id'=>$params['company_id']
        ];
        $this->checkNameIsRepetition('distributor',$data);
        //结束判断名字重复
    
    	$distributor = new Distributor();
    	$distributor_result = $distributor->addBtoBDistributor($params);
    
    	$this->outPut($distributor_result);
    }
    /**
     * 获取分销商数据
     * 胡
     */
    public function getBtoBDistributor(){
    
    
    	$params = $this->input();
    
    	$distributor = new Distributor();
		
        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $distributor->getBtoBDistributor($params, true);
            $result = $distributor->getBtoBDistributor($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }
    	$distributor_result = $distributor->getBtoBDistributor($params);
    
    	$this->outPut($distributor_result);
    
    }
    
    /**
     * 修改分销商
     * 胡
     */
    
    public function updateBtoBDistributorByDistributorId(){
    	$params = $this->input();
    
    	$paramRule = [
 			'distributor_id' => 'number',
    		'user_id'=>'number',
    
    	];
    	
    	$this->paramCheckRule($paramRule,$params);
        $distributor = new Distributor();
        $Info = $distributor->getDistributorByDistributorId($params['distributor_id']);
        if($Info['distributor_name'] == $params['distributor_name']){
        }else{

            //开始判断名字是否重复
            $data = [
                'distributor_name'=>$params['distributor_name'],
                'company_id'=>$Info['company_id'],
            ];
            $this->checkNameIsRepetition('distributor',$data);
            //结束判断名字重复
        }


    	$distributor_result = $distributor->updateBtoBDistributorByDistributorId($params);
    	$this->outPut($distributor_result);
    
    
    }    

    /**
     * 获取分销商账单 
     */
    public function getDistributorBill(){
    	$params = $this->input();
 
    	$paramRule = [
    		'distributor_id' => 'number', 
    		'bill_template_id'=>'number'	
    	];
    
    	$this->paramCheckRule($paramRule,$params);
    	$company_order_create_from_time = $params['company_order_create_from_time'];
    	$company_order_create_to_time = $params['company_order_create_to_time'];
    	$company_order_begin_time = $params['company_order_begin_time'];
    	$company_order_end_time = $params['company_order_end_time'];
    	$hidden_sale_over = $params['hidden_sale_over'];
    	
    	$branch_product_number_one = $params['branch_product_number'];
    	
    	//首先获取账单数据
    	$bill_template_params = [
    		'bill_template_id'=>$params['bill_template_id']
    	];
    	$bill_result = $this->_bill_template->getBillTemplate($bill_template_params);
    
    	//首先获取所有的订单数据
    	$distributor_bill = [];
    	$distributor_bill['bill_template_title_pic'] = $bill_result[0]['bill_template_title_pic'];
    	$distributor_bill['bill_template_foot_pic'] = $bill_result[0]['bill_template_foot_pic'];
    	$company_order_result = $this->_company_order->getCompanyOrderForDistributorBill($params);
    	
    	$distributor_bill['company_order_count'] = count($company_order_result);
    	$company_order_customer_count=0;
  		//error_log(print_r(help::modelDataToArr($company_order_result),1));

//     	$new_bill_data = [];

//     	for($i=0;$i<count($company_order_result);$i++){
//     		if(!empty($branch_product_number_one)){
//     			if($branch_product_number_one==$company_order_result[$i]['branch_product_number']){
    	
//     				if($hidden_sale_over==1){
//     					if($company_order_result[$i]['miss_payment']>0){
//     						$new_bill_data[] = $company_order_result[$i];
//     					}
//     				}else{
//     					$new_bill_data[] = $company_order_result[$i];
//     				}
    				 
//     			}
//     		}else{    			 
//     			if($hidden_sale_over==1){
//     				//error_log(print_r($new_company_order_result[$i]['miss_payment'],1));
//     				if($company_order_result[$i]['miss_payment']>0){
//     					$new_bill_data[] = $company_order_result[$i];   						
//     				}
//     			}else{   	
//     				$new_bill_data[] = $company_order_result[$i];
//     			}    			 
//     		}    	    	
//     	}

    	$new_bill_data = $company_order_result;
    
    	//开始计算总的应收
    	$total=0;
    	//实收
    	$paid=0;
    	$currency_info = [];
    	$new_company_order_result = [];
    	for($i=0;$i<count($new_bill_data);$i++){//开始查询订单下有几个分公司产品
    		$new_bill_data[$i]['miss_payment'] = number_format($new_bill_data[$i]['payment_money']-$new_bill_data[$i]['true_payment'],2);
    		$new_bill_data[$i]['payment_money'] = number_format($new_bill_data[$i]['payment_money'],2);
    		$new_bill_data[$i]['true_payment'] = number_format($new_bill_data[$i]['true_payment'],2);
    		if($hidden_sale_over==1){
    	
    			if($new_bill_data[$i]['miss_payment']<=0){
    				continue;
    			}
    		}
    		$branch_product_number='';
    		if(!empty($params['branch_product_number'])){
    			$company_order_product_params = [
    				'status'=>1,
    				'company_order_number'=>$new_bill_data[$i]['company_order_number'],
    				'branch_product_number'=>$params['branch_product_number']
    			];
    		}else{
    			$company_order_product_params = [
    				'status'=>1,
    				'company_order_number'=>$new_bill_data[$i]['company_order_number'],
    				
    			];
    		}
    		
    		$company_order_product_result = $this->_company_order_product->getCompanyOrderProduct($company_order_product_params);
    		if(count($company_order_product_result)==0){
    
    			continue;
    		}
    		//开始循环名字
    		$company_order_product_name='';
    		for($j=0;$j<count($company_order_product_result);$j++){
    			if($j==0){
    				$company_order_product_name=$company_order_product_result[$j]['branch_product_name'];
    				$branch_product_number=$company_order_product_result[$j]['branch_product_number'];
    			}else{
    				$company_order_product_name.=','.$company_order_product_result[$j]['branch_product_name'];
    				$branch_product_number.=','.$company_order_product_result[$j]['branch_product_number'];
    			}
    		}
    		
    		
    		
    		$new_bill_data[$i]['company_order_product_name'] = $company_order_product_name;
    		$new_bill_data[$i]['branch_product_number'] = $branch_product_number;
    		
    		
    		//查询每个订单有多少个游客
    		if($new_bill_data[$i]['fee_type_code']==82){
    			$company_order_customer_params = [
    					'company_order_number'=>$new_bill_data[$i]['company_order_number']
    			];
    			$company_order_customer_result = $this->_company_order_customer->getCompanyOrderCustomer($company_order_customer_params);
    			
    		}else if($new_bill_data[$i]['fee_type_code']==83){
    			//首先获取订单的资源编号
    			$company_order_product_source_params = [
    				'receivable_number'=>$new_bill_data[$i]['receivable_number']	
    			];
    			$company_order_product_source_result = $this->_company_order_product_source->getCompanyOrderProductSource($company_order_product_source_params);
    			
    			$company_order_relation_params= [
    				'company_order_product_source_id'=>	$company_order_product_source_result[0]['company_order_product_source_id'],
    				'company_order_number'=>$new_bill_data[$i]['company_order_number'],
    				'status'=>1	
    			];
    			
    			$company_order_customer_result = $this->_company_order_relation->getCompanyOrderRelation($company_order_relation_params);
    		}else{
    			//首先获取订单的资源编号
    			$company_order_product_diy_params = [
    					'receivable_number'=>$new_bill_data[$i]['receivable_number']
    			];
    			$company_order_product_diy_result = $this->_company_order_product_diy->getCompanyOrderProductDiy($company_order_product_diy_params);
    			$company_order_relation_params= [
    					'company_order_product_diy_id'=>	$company_order_product_diy_result[0]['company_order_product_diy_id'],
    					'company_order_number'=>$new_bill_data[$i]['company_order_number'],
    					'status'=>1
    			];
    			$company_order_customer_result = $this->_company_order_relation->getCompanyOrderRelation($company_order_relation_params);
    			
    			
    		}

    		
    		
    		$new_bill_data[$i]['company_order_customer_count'] = count($company_order_customer_result);
    		$company_order_customer_count+=count($company_order_customer_result);
    		
    		$total+=$new_bill_data[$i]['payment_money'];
    		$paid+=$new_bill_data[$i]['true_payment'];
    		
    		

    		//开始计算每个币种的总价 已付 未付
    		$currency_info[$new_bill_data[$i]['receivable_currency_id']]['currency_id']=$new_bill_data[$i]['receivable_currency_id'];
    		$currency_info[$new_bill_data[$i]['receivable_currency_id']]['unit']=$new_bill_data[$i]['unit'];
    		
    		$currency_info[$new_bill_data[$i]['receivable_currency_id']]['currency_name']=$new_bill_data[$i]['currency_name'];
    		$currency_info[$new_bill_data[$i]['receivable_currency_id']]['total']+=$new_bill_data[$i]['payment_money'];
    		$currency_info[$new_bill_data[$i]['receivable_currency_id']]['paid']+=$new_bill_data[$i]['true_payment'];
    		$currency_info[$new_bill_data[$i]['receivable_currency_id']]['balance']+=$new_bill_data[$i]['payment_money']-$new_bill_data[$i]['true_payment'];
    		$company_order_customer_name='';
    		//开始算人名
    		for($k=0;$k<count($company_order_customer_result);$k++){
    			if($k==0){
    				$company_order_customer_name=$company_order_customer_result[$k]['customer_name'];
    			}else{
    				$company_order_customer_name.=','.$company_order_customer_result[$k]['customer_name'];
    			}
    		}
    		$new_bill_data[$i]['company_order_customer_name'] = $company_order_customer_name;
    		
    		$new_company_order_result[] = $new_bill_data[$i];
    	}
    	
   
    	

    
    	$distributor_bill['company_order_customer_count'] = $company_order_customer_count;
    	$distributor_bill['total'] = number_format($total,2);
    	$distributor_bill['paid'] = number_format($paid,2);
    	$distributor_bill['balance'] = number_format($total-$paid,2);
    	$distributor_bill['bill_data'] = $new_company_order_result;
    	$currency_info = array_values($currency_info);
    	for($i=0;$i<count($currency_info);$i++){
    		$currency_info[$i]['total'] = number_format($currency_info[$i]['total'],2);
    		$currency_info[$i]['paid'] = number_format($currency_info[$i]['paid'],2);
    		$currency_info[$i]['balance'] = number_format($currency_info[$i]['balance'],2);
    		
    		
    	}
    	$distributor_bill['currency_info'] = $currency_info;
    	$this->outPut($distributor_bill);
    }

    /**
     * addTourType
     *
     * 添加B2B代售产品类型
     * @author shj
     * @return void
     * Date: 2019/11/8
     * Time: 14:47
     */
    public function addTourType(){

        $params = $this->input();

        $paramRule = [
            'tour_type_name' => 'string',
            'status'=>'number',
            'pid'=>'number',
        ];
        $this->paramCheckRule($paramRule,$params);

        $tour_type = new TourType();
        $result = $tour_type->addTourType($params);

        $this->outPut($result);
    }

    /**
     * getTourType
     *
     * 获取B2B代售产品类型
     * @author shj
     * @return void
     * Date: 2019/11/8
     * Time: 14:48
     */
    public function getTourType(){

        $params = $this->input();

        $tour_type = new TourType();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $tour_type->getTourType($params, true);
            $result = $tour_type->getTourType($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

        $result = $tour_type->getTourType($params);

        $this->outPut($result);
    }

    public function getTourTypeAjax(){

        $params = $this->input();

        $tour_type = new TourType();

        $result = $tour_type->getTourTypeAjax($params);

        $this->outPut($result);
    }

    /**
     * getOneTourType
     *
     * 获取一条B2B代售产品类型
     * @author shj
     * @return void
     * Date: 2019/3/14
     * Time: 10:28
     */
    public function getOneTourType(){

        $params = $this->input();
        $tour_type = new TourType();
        $result = $tour_type->getOneTourType($params);
        $this->outPut($result);
    }

    /**
     * updateTourTypeByTourTypeId
     *
     * 修改B2B代售产品类型
     * @author shj
     * @return void
     * Date: 2019/11/8
     * Time: 14:48
     */
    public function updateTourTypeByTourTypeId(){
        $params = $this->input();

        $paramRule = [
            'user_id'=>'number',
            'tour_type_id'=>'number'
        ];

        $this->paramCheckRule($paramRule,$params);
        $tour_type = new TourType();
        $result = $tour_type->updateTourTypeByTourTypeId($params);
        $this->outPut($result);

    }


    public function addProduct(){

        $params = $this->input();

        $paramRule = [
            'product_name' => 'string',
            'status'=>'number',
        ];
        $this->paramCheckRule($paramRule,$params);

        $tour_type = new Product();
        $result = $tour_type->addProduct($params);

        $this->outPut($result);
    }

    public function getProduct(){

        $params = $this->input();

        $tour_type = new Product();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $tour_type->getProduct($params, true);
            $result = $tour_type->getProduct($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

        $result = $tour_type->getProduct($params);

        $this->outPut($result);
    }

    public function getOneProduct(){

        $params = $this->input();
        $tour_type = new Product();
        $result = $tour_type->getOneProduct($params);
        $this->outPut($result);
    }

    /**
     * updateTourTypeByTourTypeId
     *
     * 修改B2B代售产品类型
     * @author shj
     * @return void
     * Date: 2019/11/8
     * Time: 14:48
     */
    public function updateProductByProductId(){
        $params = $this->input();

        $paramRule = [
            'user_id'=>'number',
            'product_name'=>'string'
        ];

        $this->paramCheckRule($paramRule,$params);
        $tour_type = new Product();
        $result = $tour_type->updateProductByProductId($params);
        $this->outPut($result);

    }

    public function addCommission(){

        $params = $this->input();

        $paramRule = [
            'name' => 'string',
            'status'=>'number',
        ];
        $this->paramCheckRule($paramRule,$params);

        $model = new CommissionTable();
        $result = $model->addCommission($params);

        $this->outPut($result);
    }

    public function getCommission(){

        $params = $this->input();

        $model = new CommissionTable();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getCommission($params, true);
            $result = $model->getCommission($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

        $result = $model->getCommission($params);

        $this->outPut($result);
    }

    public function getOneCommission(){

        $params = $this->input();
        $model = new CommissionTable();
        $result = $model->getOneCommission($params);
        $this->outPut($result);
    }


    public function updateCommissionByCommissionId(){
        $params = $this->input();

        $paramRule = [
            'user_id'=>'number',
            'name'=>'string'
        ];

        $this->paramCheckRule($paramRule,$params);
        $model = new CommissionTable();
        $result = $model->updateCommissionByCommissionId($params);
        $this->outPut($result);

    }


    public function addNews(){

        $params = $this->input();

        $paramRule = [
            'title' => 'string',
            'status'=>'number',
        ];
        $this->paramCheckRule($paramRule,$params);

        $model = new News();
        $result = $model->addNews($params);

        $this->outPut($result);
    }

    public function getNews(){

        $params = $this->input();

        $model = new News();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getNews($params, true);
            $result = $model->getNews($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

        $result = $model->getNews($params);

        $this->outPut($result);
    }

    public function getOneNews(){

        $params = $this->input();
        $model = new News();
        $result = $model->getOneNews($params);
        $this->outPut($result);
    }


    public function updateNewsByNewsId(){
        $params = $this->input();

        $paramRule = [
            'user_id'=>'number',
            'title'=>'string'
        ];

        $this->paramCheckRule($paramRule,$params);
        $model = new News();
        $result = $model->updateNewsByNewsId($params);
        $this->outPut($result);

    }

    public function addAccountCode(){

        $params = $this->input();

        $paramRule = [
            'name' => 'string',
            'status'=>'number',
        ];
        $this->paramCheckRule($paramRule,$params);

        $model = new AccountCode();
        $result = $model->addAccountCode($params);

        $this->outPut($result);
    }

    public function getAccountCode(){

        $params = $this->input();

        $model = new AccountCode();

        if(isset($params['page'])){
            $page_size =  isset($params['page_size'])?$params['page_size']:Contents::PAGE_SIZE;
            $page = ($params['page']-1)*$page_size;
            $count = $model->getAccountCode($params, true);
            $result = $model->getAccountCode($params,false,'true',$page,$page_size);
            $data = [
                'count'=>$count,
                'list'=>$result,
                'page_count'=>ceil($count/$page_size)
            ];

            return $this->output($data);
        }

        $result = $model->getAccountCode($params);

        $this->outPut($result);
    }

    public function getOneAccountCode(){

        $params = $this->input();
        $model = new AccountCode();
        $result = $model->getOneAccountCode($params);
        $this->outPut($result);
    }


    public function updateAccountCodeByAccountCodeId(){
        $params = $this->input();

        $paramRule = [
            'user_id'=>'number',
            'name'=>'string'
        ];

        $this->paramCheckRule($paramRule,$params);
        $model = new AccountCode();
        $result = $model->updateAccountCodeByAccountCodeId($params);
        $this->outPut($result);

    }

}	
