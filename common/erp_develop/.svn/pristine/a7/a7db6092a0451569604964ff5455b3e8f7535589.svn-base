<?php
namespace app\index\controller;
use app\common\help\Help;
use app\index\controller\Base;
use \think\File;
use think\Request;
use think\Controller;
class Auth extends Controller
{
    //判断权限
    public function check_auth($role_id)
    {

    	$user_auth_config  = session('authConfig');

    	$result = 'display:none';
    	if(session('user')['role_id']==1){
    		$result='';
    	}else{
    		for($i=0;$i<count($user_auth_config);$i++){
    			if($user_auth_config[$i]['auth_id'] ==$role_id ){
    				$result = '';
    				break;
    			}
    			 
    			 
    		}
    	}

		
        return $result;
    }

    //查询
    public function han_select(){
        $data = [
//            'team_product_settlement_type'=>2,
//            'team_product_id'=>44,
//            'supplier_type_id'=>3,
            'route_journey_id'=>25,
            'user_id'=>1,
            'status'=>0
        ];

        $result =  $this->callSoaErp('post','/product/updateRouteJourneyByRouteJourneyId',$data);

        return json_encode($result);
    }

    //修改
    public function han_edit(){

        $data = [
//            'team_product_id'=>174,
//            'edit_allocation'=>[
//                [
//                    'team_product_allocation_id'=>103,
//                    'source_total_price'=>600,
//                    'status'=>1
//                ]
//                [
//                    'team_product_journey_id'=>23,
//                    'start_city'=>'上海777',
//                    'status'=>1
//                ]
//            ],
//            'add_allocation'=>[
//                [
//                    'team_product_id' => 43,
//                    'supplier_type_id' => 3,
//                    'source_id' => 55,
//                    'payment_currency_id' => 1,
//                    'source_price' => 150,
//                    'source_count' => 3,
//                    'source_total_price' => 500
//                ]
//            ],
//            'team_product_id' => 174,
//            'edit_return_receipt'=>[
//                [
//                    'route_return_receipt_id' => 47,
//                    'title' => '非常好',
//                    'status'=> 1
//                ]
//            ],
//            'add_return_receipt'=>[
//                [
//                    'team_product_id' => 174,
//                    'title' => '我是超人',
//                    'content'=> '我是超人123',
//                    'sorting'=> 3
//                ]
//            ],
            'route_journey_id'=>12,
            'the_days'=>12,
            'user_id'=>1,
            'status'=>1
        ];

        $result =  $this->callSoaErp('post','/product/updateRouteJourneyByRouteJourneyId',$data);

        return json_encode($result);

    }

    //编辑团队管理大数组
    public function han_edit2(){
        $data = [
            'team_product_id'=>83,
            'team_product_name'=>"超级杀人5日游，猎杀5",
            'edit_flight'=>[
                [
                    'team_product_flight_id'=>46,
                    'start_city'=>'bbb',
                    'status'=>1
                ],
                [
                    'team_product_flight_id'=>47,
                    'start_city'=>'bbb',
                    'status'=>1
                ]
            ],
            'add_flight'=>[
                [
                    'team_product_id'=>83,
                    'the_days' => 1,
                    'start_city' => '上海',
                    'end_city' => '泰国',
                    'start_time' => 1535091233,
                    'end_time' => 1535091255,
                    'flight_number' => 'N55555',
                    'flight_type'=>1
                ],[
                    'team_product_id'=>83,
                    'the_days' => 2,
                    'start_city' => '上海',
                    'end_city' => '泰国',
                    'start_time' => 1535091232,
                    'end_time' => 1535091256,
                    'flight_number' => 'N666666',
                    'flight_type'=>2
                ]
            ],
            'edit_journey'=>[
                [
                    'team_product_journey_id'=>27,
                    'the_days'=>2
                ]
            ],
            'add_journey'=>[
                [
                    'team_product_id'=>83,
                    'the_days' => 4,
                    'route_journey_title'=>'aaaa',
                    'route_journey_content'=>'bbbbbbbbb',
                    'route_journey_traffic'=>'战斗机',
                    'route_journey_stay'=>'',
                    'route_journey_breakfast'=>'面包',
                    'route_journey_lunch'=>'牛奶',
                    'route_journey_dinner'=>'',
                    'eat_mark'=> '1,2',
                    'route_journey_scenic_sport'=>'山洞景点1',
                    'route_journey_picture'=>'http://192.168.11.100:7009staticuploadsimages20180910c1cd4fd65b96d170fd7fc9168651d7a7.png',
                    'route_journey_remark'=>'美丽的生活'
                ]
            ],
            'edit_return_receipt'=>[
                [
                    'route_return_receipt_id'=>47,
                    'title'=>'非常好1111111',
                    'status'=>2
                ]
            ],
            'add_return_receipt'=>[
                [
                    'team_product_id'=>83,
                    'title'=>'我是超人1111111',
                    'content'=>'我是超人111111111111',
                    'sorting'=>3
                ]
            ],
            'edit_allocation'=>[
                [
                    'team_product_allocation_id'=>107,
                    'source_total_price'=>800,
                    'status'=>1
                ]
            ],
            'add_allocation'=>[
                [
                    'team_product_id' => 83,
                    'supplier_type_id' => 3,
                    'source_id' => 55,
                    'payment_currency_id' => 1,
                    'source_price' => 150,
                    'source_count' => 3,
                    'source_total_price' => 500
                ]
            ],
            'edit_once_price'=>[
                [
                    'team_product_once_price_id'=>4,
                    'total_price'=>13000,
                    'status'=>1
                ],
                [
                    'team_product_once_price_id'=>5,
                    'total_price'=>14000,
                    'status'=>1
                ]
            ],
            'add_once_price'=>[
                [
                    'team_product_id' => 83,
                    'company_id'=>2,
                    'total_price'=>13000
                ]
            ],
            'user_id' => 1,
            'status' => 1
        ];

        $result =  $this->callSoaErp('post','/product/updateTeamProductByTeamProductId',$data);

        return json_encode($result);
    }

    //查询基础信息
    public function han_select2(){
        $data = [
            "receivable_company_id" => 1,
            "invoice_number" => "A11222",
            "invoice_time" => "1542211200",
            "source_type_id" => 3,
            "product_name" => "aaaa",
            "cope_currency_id" => 1,
            "cope_money" => 1222,
            "remark" => "aaa",
            "user_id" => 1,
            "status" => 1
        ];

        $result =  $this->callSoaErp('post','/finance/addCope',$data);

        return json_encode($result);
    }

    public function asd(){

        $data = [
            "company_order_number" => "BO20181029210030",
            "customer_first_name"=> "a",
            "customer_last_name" => "a",
            "english_last_name" => "a",
            "english_first_name" => "a",
            "card_type" => 2,
            "gender" => 2,
            "card_number" => "aa",
            "term_of_validity" => "1540742400",
            "country_id" => 1,
            "phone" => 123,
            "email" => "546629756@qq.com",
            "customer_type" => 1,
            "language_id" => 1,
            "room_code" => 4,
            "room_type" => 2,
            "check_in" => 4,
            "check_on" => 4,
            "customer_flight" => [
                [
                    "flight_code" => "a",
                    "flight_time" => "1540742400",
                    "flight_type" => 1,
                    "start_place" => "aa",
                    "end_place" => "aaa"
                ],[
                    "flight_code" => "b",
                    "flight_time" => "1541174400",
                    "flight_type" => 2,
                    "start_place" => "bb",
                    "end_place" => "bbb"
                ]
            ],
            "user_id" => 1,
            "company_id" => 23
        ];

        $result =  $this->callSoaErp('post','/branchcompany/addCompanyOrderCustomer',$data);

        return json_encode($result);
    }

    public function asd2(){

        $data = [
            "company_order_customer_id" => 64,
            "customer_id" => 15,
            "company_order_number" => "BO20181031221681",
            "customer_first_name" => "asd",
            "customer_last_name" => "asd",
            "english_last_name" => "asd",
            "english_first_name" => "asd",
            "card_type" => 1,
            "gender" => 1,
            "card_number" => "sdsadasd",
            "term_of_validity" => "1111075200",
            "country_id" => 1,
            "phone" => 22222222222,
            "email" => "sad@aa.com",
            "customer_type" => 1,
            "language_id" => 1,
            "room_code" => 4,
            "room_type" => 2,
            "check_in" => 4,
            "check_on" => -1,
            "customer_flight" => [
                [
                    "flight_code" => 3,
                    "flight_time" => "1540915200",
                    "flight_type" => 2,
                    "start_place" => "e",
                    "end_place" => "e"
                ],
                [
                    "flight_code" => 33,
                    "flight_time" => "1540915200",
                    "flight_type" => 3,
                    "start_place" => "4",
                    "end_place" => "4",
                ]
            ],
            "user_id" => 1,
            "company_id" => 23,
            "status" => 1
        ];

        $result =  $this->callSoaErp('post','/branchcompany/updateCompanyOrderCustomerByCompanyOrderCustomerId',$data);

        return json_encode($result);
    }

    //添加线路模版大数组
    public function add_xlbb(){

        $data = [
            "route_name" => 'aaa',
            "route_type_id" => 1,
            "route_user_id" => 1,
            "company_id" => 1,
            "route_template_flight_info"=>[
                [
                    'the_days' => 1,
                    'start_city' => '上海1',
                    'end_city' => '泰国1',
                    'start_time' => 1535091233,
                    'end_time' => 1535091255,
                    'flight_number' => 'N55555',
                    'flight_type'=>1
                ], [
                    'the_days' => 2,
                    'start_city' => '上海2',
                    'end_city' => '泰国2',
                    'start_time' => 1535091232,
                    'end_time' => 1535091256,
                    'flight_number' => 'N666666',
                    'flight_type'=>2
                ]
            ],
            'route_template_journey_info' => [
                [
                    'the_days' => 1,
                    'route_journey_title' => '泰国人妖岛',
                    'route_journey_content' => '泰国人妖岛,非常好玩的',
                    'route_journey_traffic' => '轮船',
                    'route_journey_stay' => '人妖酒店',
                    'eat_mark' => '1,2,3',
                    'route_journey_breakfast' => '馒头',
                    'route_journey_lunch' => '面包',
                    'route_journey_dinner' => '海鲜饭',
                    'route_journey_scenic_sport' => '泰泰',
                    'route_journey_picture' => '1.jpg',
                    'route_journey_remark' => '好的'
                ]
            ],
            'route_template_allocation_info' => [
                [
                    'supplier_type_id' => 3,
                    'source_id' => 55,
                    'payment_currency_id' => 1,
                    'source_price' => 150,
                    'source_count' => 3,
                    'source_total_price' => 400
                ]
            ],
            'route_return_receipt_info' => [
                [
                    'title' => '好啊1',
                    'content' => '321',
                    'sorting' => 1
                ],[
                    'title' => '好啊2',
                    'content' => '123',
                    'sorting' => 2
                ]
            ],
            "user_id" => 1,
            "status" => 1
        ];

        $result =  $this->callSoaErp('post','/product/addRouteTemplate',$data);

        return json_encode($result);
    }

    //添加线路模版大数组
    public function edit_xlbb(){

        $data = [
            'route_template_id'=>296,
            'route_name'=>"bbb",
            'edit_flight'=>[
                [
                    'route_flight_id'=>115,
                    'start_city'=>'bbb',
                    'status'=>0
                ],
                [
                    'route_flight_id'=>116,
                    'start_city'=>'ccc',
                    'status'=>0
                ]
            ],
            'add_flight'=>[
                [
                    'route_template_id'=>296,
                    'the_days' => 1,
                    'start_city' => '上海111',
                    'end_city' => '泰国111',
                    'start_time' => 1535091233,
                    'end_time' => 1535091255,
                    'flight_number' => 'N55555',
                    'flight_type'=>1
                ],[
                    'route_template_id'=>296,
                    'the_days' => 2,
                    'start_city' => '上海222',
                    'end_city' => '泰国222',
                    'start_time' => 1535091232,
                    'end_time' => 1535091256,
                    'flight_number' => 'N6666662',
                    'flight_type'=>2
                ]
            ],
            'edit_journey'=>[
                [
                    'route_journey_id'=>92,
                    'the_days'=>2,
                    'status'=>0
                ]
            ],
            'add_journey'=>[
                [
                    'route_template_id'=>296,
                    'the_days' => 4,
                    'route_journey_title'=>'ccc',
                    'route_journey_content'=>'dddddd',
                    'route_journey_traffic'=>'战斗机',
                    'route_journey_stay'=>'',
                    'route_journey_breakfast'=>'面包',
                    'route_journey_lunch'=>'牛奶',
                    'route_journey_dinner'=>'',
                    'eat_mark'=> '1,2',
                    'route_journey_scenic_sport'=>'山洞景点1',
                    'route_journey_picture'=>'http://192.168.11.100:7009staticuploadsimages20180910c1cd4fd65b96d170fd7fc9168651d7a7.png',
                    'route_journey_remark'=>'美丽的生活'
                ]
            ],
            'edit_allocation'=>[
                [
                    'route_source_allocation_id'=>255,
                    'source_total_price'=>800,
                    'status'=>0
                ]
            ],
            'add_allocation'=>[
                [
                    'route_template_id'=>296,
                    'supplier_type_id' => 3,
                    'source_id' => 55,
                    'payment_currency_id' => 1,
                    'source_price' => 150,
                    'source_count' => 3,
                    'source_total_price' => 600
                ]
            ],
            'edit_return_receipt'=>[
                [
                    'route_return_receipt_id' => 42,
                    'title' => '非常好',
                    'status'=> 0
                ]
            ],
            'add_return_receipt'=>[
                [
                    'route_template_id' => 296,
                    'title' => '我是超人',
                    'content'=> '我是超人555',
                    'sorting'=> 3
                ]
            ],
            'user_id' => 1,
            'status' => 1
        ];

        $result =  $this->callSoaErp('post','/product/updateRouteTemplateByRouteTemplateId',$data);

        return json_encode($result);
    }

    public function www(){
        $data=[
            'route_name'=>'HUGH11',
	        'route_type_id'=>2,
	        'status'=>1,
	        'route_user_id'=>1,
	        'user_id'=>1,
	        'company_id'=>24,
	        'route_template_flight_info'=>[
	            [
                    'the_days'=>1,
		            'flight_number'=>'航班1',
		            'flight_type'=>2,
		            'start_city'=>'上海',
		            'end_city'=>'毛球',
		            'start_time'=>1542682500,
		            'end_time'=>1542650700,
                    'status'=>1
	            ],[
                    'the_days'=>2,
                    'flight_number'=>'航班1',
		            'flight_type'=>1,
		            'start_city'=>'上海',
		            'end_city'=>'毛球',
		            'start_time'=>1542682500,
		            'end_time'=>1542650700,
                    'status'=>1
	            ]
            ],
	        'route_template_journey_info'=>[
	            [
                    'the_days'=> 2,
                    'route_journey_title'=>'行程标题2',
                    'route_journey_content'=>'行程内容2',
                    'route_journey_traffic'=>'交通2',
                    'route_journey_stay'=>'住宿2',
                    'eat_mark'=>'1,3',
                    'route_journey_breakfast'=>'早2',
                    'route_journey_lunch'=>'',
                    'route_journey_dinner'=>'晚2',
                    'route_journey_scenic_sport'=>'景点2',
                    'route_journey_picture'=>'http:\/\/47.244.53.248:8003\/static\/uploads\/images\/20181120\/bc7d1dd2ae18974ab0f87cfd9d467fb2.png',
                    'route_journey_remark'=>'2222',
                    'status'=>1
                ]
	        ],
	        'route_template_allocation_info'=>[
	            [
                    'supplier_type_id'=>2,
		            'source_id'=>87,
		            'payment_currency_id'=>1,
		            'source_price'=>7.00,
		            'source_count'=>1,
		            'source_total_price'=>7.00,
                    'status'=>1
	            ],[
                    'supplier_type_id'=>2,
		            'source_id'=>77,
		            'payment_currency_id'=>1,
		            'source_price'=>700.00,
		            'source_count'=>1,
		            'source_total_price'=>700,
                    'status'=>1
                ]
	        ]
        ];

        $result =  $this->callSoaErp('post','/product/addRouteTemplate',$data);

        return json_encode($result);
    }

    public function eee(){
        $data=[
            "route_name"=>"HUGH1122-2",
	        "route_type_id"=>17,
	        "status"=>0,
	        "route_user_id"=>8,
	        "user_id"=>7,
	        "company_id"=>18,
	        "route_template_id"=>308,
	        "edit_flight"=>[
	            [
                    "the_days"=>"1",
		            "flight_number"=>"航班号1",
                    "flight_type"=>1,
                    "start_city"=>"出发地1",
                    "end_city"=>"目的地1",
                    "start_time"=>1542852480,
                    "end_time"=>1542852480,
                    "status"=>1,
                    "route_flight_id"=>126
	            ],[
                    "the_days"=>"1",
                    "flight_number"=>"航班号1-2",
                    "flight_type"=>3,
                    "start_city"=>"出发地1-2",
                    "end_city"=>"目的地1-2",
                    "start_time"=>1542816000,
                    "end_time"=>1542852540,
                    "status"=>1,
                    "route_flight_id"=>127
	            ]
            ],
	        "add_flight"=>[
	            [
                    "route_template_id"=>308,
                    "the_days"=>"2",
                    "flight_number"=>"航班1",
                    "flight_type"=>1,
                    "start_city"=>"美国",
                    "end_city"=>"恒丰路",
                    "start_time"=>1542855300,
                    "end_time"=>1542816300,
                    "status"=>1
	            ]
            ],
	        "edit_journey"=>[
	            [
                    "the_days"=>"1",
                    "route_journey_title"=>"行程标题1",
                    "route_journey_content"=>"行程内容1",
                    "route_journey_traffic"=>"交通1",
                    "route_journey_stay"=>"住宿1",
                    "eat_mark"=>"1,2,3",
                    "route_journey_breakfast"=>"早1",
                    "route_journey_lunch"=>"午1",
                    "route_journey_dinner"=>"晚1",
                    "route_journey_scenic_sport"=>"景点1",
                    "route_journey_picture"=>"http:\/\/47.244.53.248:8003\/static\/uploads\/images\/20181122\/4668366558829f03e113fbe715a866d5.png",
                    "route_journey_remark"=>"备注",
                    "status"=>1,
                    "route_journey_id"=>106
	            ],[
                    "the_days"=>"2",
                    "route_journey_title"=>"行程标题2",
                    "route_journey_content"=>"行程内容2",
                    "route_journey_traffic"=>"交通2",
                    "route_journey_stay"=>"住宿2",
                    "eat_mark"=>"1,2",
                    "route_journey_breakfast"=>"",
                    "route_journey_lunch"=>"",
                    "route_journey_dinner"=>"",
                    "route_journey_scenic_sport"=>"景点2",
                    "route_journey_picture"=>"http:\/\/47.244.53.248:8003\/static\/uploads\/images\/20181122\/e9a6e4681a1076f327542dfe4cbf5b6c.jpg,http:\/\/47.244.53.248:8003\/static\/uploads\/images\/20181122\/fe99e4838ddd3ff37769e6ef41e77fa7.png,http:\/\/47.244.53.248:8003\/static\/uploads\/images\/20181122\/31aee2ca75a8898a494a13f5c6437c77.jpg,http:\/\/47.244.53.248:8003\/static\/uploads\/images\/20181122\/1d3c5d00ab4b817715b795692e74f6bb.png",
                    "route_journey_remark"=>"备注2",
                    "status"=>1,
                    "route_journey_id"=>107
                ]
            ],
	        "add_journey"=>[],
	        "edit_return_receipt"=>[],
	        "add_return_receipt"=>[
	            [
                    "route_template_id"=>308,
                    "title"=>"行程特色",
                    "content"=>"1.此线路机票为散客10人以上成团后的单价，申请的是团队机票，出票后不能签转和退票；\r\n2.有关服务标准内住宿标准的详细说明：\r\n①行程内所含住宿，是按一个双标间（2张床）中的1张床进行核算，如果您是1个人独自出游，您就需要和另外一位团内的游客同住一个房间，如您需要单房，需补足单房差；\r\n②同是一间房，标间（2张床）和大床房（1张大床）可能存在差价，如您在补足单房差的同时需升级使用大床房，请在报名前先行咨询我们的销售人员；\r\n③很多酒店没有三人间的房型（或该房型甚少先到先得），也不允许在双标间内加床。此时，您的出游人数如果为奇数，单出来的这一个人就需要与他人拼房；如您需要单房，需补足单房差（参考第一条）；有些游客认为关系好可以三个人挤一间房，我们会尽可能的尊重您的决定，但该形为是被酒店是严令禁止的，一旦发现需按前台售卖价补足房费；\r\n④非我社可控因素造成的需要补单房差或房费的情况，据实际的情况分析，由双方友好商定如何承担。\r\n3.散拼团接送机和带团导游不为同一导游，但我们将事先做好衔接工作，请游客放心；\r\n4.有关门票优惠及离团退费说明：\r\n①行程内所含景点的门票，均按团购价进行采购，比持证件购票更加优惠，因此不再享有各类证件的优惠，请持有老年证、军官证、学生证、残疾证等各类证件的游客知悉；\r\n②团购门票，一经购买，不可退票！如您未按照旅游合同执行而自愿放弃行程中某景点项目，或中途离团，未游览的景点及项目门票无法退还，请知悉；\r\n③为确保有位，游客一经报名我们便会同时下单操作，因游客自身原因需要中途离团的，行程中所有费用看似未参与其实已产生，一经下单便无法退款，请谅解并知悉；\r\n5.旅途中因旅游者自身原因需离团独行的，双方按变更合同进行处理。因旅游者自行离团未给旅行社造成损失的，旅行社退回相应的费用；如因自行离团给旅行社造成损失时，旅游者应承担相应的损害赔偿责任。\r\n6.因不可抗力或者旅行社已尽合理注意义务仍不能避免的事件而影响旅游行程的，按《中华人民共和国旅游法》第六十七条列举的情形分别进行处理。\r\n7.由于航班政策及市场销售原因，同一天不同航班或者同一航班都有可能出现单价差异，以合同上的单价为准\r\n8.请游客离滇前不要忘记填写《意见单》这是您对此次游览质量的最终考核标准；我社质检中心将以此作为团队质量调查的依据，不签意见单者视为放弃，按无意见处理；\r\n9.我社已购买了旅行社责任险，旅途时间较长，希望游客自愿购买旅游意外险；\r\n10.景点景区，酒店，餐厅内如有商品出售，请游客谨慎选择，因此类商品不属于行程购物安排，如果游客在这些地方购物，完全属个人行为；",
                    "sorting"=>1,
                    "status"=>1
                ],[
                    "route_template_id"=>308,
                    "title"=>"价格说明",
                    "content"=>"①含3正1早、正餐10人\/桌7菜1汤，不足10人，菜依次减少。\r\n②岛上旅游空调车，一人一座，普陀山大船往返\r\n③当地优秀地陪导游\r\n费用不包含\r\n①不含往返车上用餐\r\n②不含民宿空调",
                    "sorting"=>2,
                    "status"=>1
	            ],[
                    "route_template_id"=>308,
                    "title"=>"参团须知",
                    "content"=>"1. 中國及亞洲特價團優惠只限持有北美，加拿大，澳洲，新西蘭，歐洲的護照\/永居證的華人華僑人士(能流利中文)。東南亞團不允許當地人參團。单项资源人士如欲參加，請諮詢地接社旅行社。\r\n2. 價格適用於兩人或兩人以上同時報名參加。費用包含與不包含請參閱最新的宣傳章程。\r\n3. 免費接送機條件：\r\n❍ 必須符合接送機當天日期\r\n❍ 時間及機場限制，請參照最新的宣傳單。若航班延誤超過半小時以上，視為自動放棄，我公司有權取消接送服務，並不退還所交之相關費用。\r\n❍ 參加日本團的客人需根據本公司已安排的交通工具乘車\/或酒\r\n店免費接駁士自行前往酒店或前往機場。更多指引請參閱最終出團單張。\r\n4. 宣傳單所列之特色旅遊項目必須參加，而自選項目則可選擇不參加。項目及價格請參照宣傳單張。\r\n5. 此團報名一經確認後，不可隨意更改姓名、更換參團日期。任何情況下如欲更改更換，需繳交手續費。費用請致電查詢。\r\n6. 此團不能中途擅自離團或自由改變行程，全程必須保證團進團出，否則需補付離團費用每人每天USD$99 orUSD$150。此團屬於特價促銷團，不能因任何原因要求部分或全部退款。本公司不會為離團後之行為付任何責任。\r\n7. 報名時請確保所有同行人員同時報名。在不同時間報名的客人，我公司不保證能分配在同一輛車中。\r\n8. 客人取消旅行團：請務必儘早以書面形式通知您的地接社旅行社。\r\n中國特價團及無購物團：\r\n9. 團出發15天前及以上免費取消；團出發前15天內取消，扣除每人$100的退團費，其餘金額返還。\r\n10. 包含機票的旅行團：出機票前取消，收取每人$500；出機票後並且在團出發前15天及以上取消，收取每人$1000；15天內取消，團款不退。\r\n亞洲特價團：\r\n11. 團費低於$200的旅行團，無論任何理由取消，均不退團款。\r\n12. 地接社團費超過$200的旅行團：團出發30天前及以上取消，扣除每人$50的退團費，其餘金額返還；團出發前21-29天內取消，扣除每人$200的退團費，其餘金額返還；團出發前20天及以內取消，團費及自費不退，小費退還。\r\n13. 包含機票的旅行團：出機票前取消，收取每人$500；出機票後並且在團出發前20天及以上取消，收取每人$1000；20天內取消，團款不退。\r\n14. 聯誼假期取消旅行團：本公司保留因天氣、人數不足或单项资源原因造成旅行團取消或改期的權利。若團確定取消。我公司會提前7天通知，並全額退團款。\r\n15. 我公司強烈建議您購買個人旅遊保險，以保障您因個別因素而不能參團。\r\n16. 如全程單人一間房，包括因臨時有人取消參團而導致單人一間房，則必須額外支付單房差（如之後自行找到人同房，差額不退）。\r\n17. 我公司不幫助配房，並請在報名表中詳細註明。\r\n18. 此團提供雙人房或三人房。三人房為雙人房中加多一床。我公司有權利根據實際情況分配房型，不可另行要求。\r\n19. 一般情況下，此團只提供國語和廣東話導遊服務。請在報名表註明所需語言。行團時，我司有權根據不同團體需要調整服務語言。\r\n20. 此團旅遊的每個城市都會介紹當地的特色產品，若有興趣可自行購買，我公司絕對不會強迫客人購物。\r\n21. 由於參團人數眾多，酒店的確認及資訊將會大約在團出發前1週內通知您。請諮詢您的地接社旅行社。\r\n22. 此團必需繳小費之金額請參照旅行團宣傳單張。\r\n行程內容可能因季節 交通 航班 政治或单项资源因素而有所差異。為保證團隊最佳效果，聯誼假期保留調整及變動行程順序的權利。\r\n報名時必需繳付全款。報名時需提供所有參團者護照複印件。永居人士必須攜帶您的永居證明參團。\r\n25. 參團時，請務必攜帶您的護照及行程確認單，以便我們確認您的身份。永居人士必須攜帶您的永居證明參團。否则将取消参团资格。\r\n26. 一張報名表只需參團其中一人代為簽名，簽名者必須對報名表中单项资源參團者負責。簽名等同於您已經閱讀明白聯誼假期旅遊團的規章，並同意參加聯誼假期旅行團。\r\n27. 不管您是否已報名，我公司均有權利修改或調整行程內容及價目，恕不另行通知。可能影響內容包括：小費、特色旅遊項目、自選項目及单项资源費用。\r\n28. 具體的參團規定請參閱我社的報名條款。客人一旦參團報到後，我社均視為該客人已經全部知道，並接受且願意遵守我社的相關條款規定。任何因客人不遵守條款而產生的後果，均由本人自行承擔。對於不尊重他人，不願意配合導遊順利完成旅行任務的客人，我社有權即時停止服務。對於造成惡劣後果的客人，我社保留追述的法律權利。\r\n29. 地接社旅行社有責任保證客人所提供資料的真實性及準確性，並且客人在報名前已了解我公司的報名",
                    "sorting"=>3,
                    "status"=>1
	            ],[
                    "route_template_id"=>308,
                    "title"=>"抵达联系方式",
                    "content"=>"緊急聯系電話：4008781211（6:00-24:00）\r\n\r\n接機方式： 機場就壹個出口，導遊舉聯誼假期接機牌\r\n\r\n1.緊急聯繫人：\r\n第壹聯系人:客服:15306170176\r\n第二聯系人:耿巍云（0510）82707566\r\n第三聯系電話：张媛18050053175\r\n\r\n2.參團期間請客人攜戴有效證件：比如美國\/加拿大護照或綠卡（原件）；如參團期間無法出示有效證件則不能享有當地優惠政策，本公司導遊會向客人收取原價參團費用。\r\n\r\n3.自行入住的客，請在出發前24小時與我們的同事再次聯繫，第壹晚入住酒店的地址。",
                    "sorting"=>4,
                    "status"=>1
                ]
            ],
            "edit_allocation"=>[
                [
                    "supplier_type_id"=>2,
                    "source_id"=>87,
                    "payment_currency_id"=>43,
                    "source_price"=>7.00,
                    "source_count"=>1,
                    "source_total_price"=>7.00,
                    "status"=>1,
                    "route_source_allocation_id"=>352
                ],[
                    "supplier_type_id"=>2,
                    "source_id"=>85,
                    "payment_currency_id"=>43,
                    "source_price"=>8.00,
                    "source_count"=>1,
                    "source_total_price"=>8.00,
                    "status"=>1,
                    "route_source_allocation_id"=>353
                ],[
                    "supplier_type_id"=>2,
                    "source_id"=>73,
                    "payment_currency_id"=>1,
                    "source_price"=>700.00,
                    "source_count"=>1,
                    "source_total_price"=>700.00,
                    "status"=>1,
                    "route_source_allocation_id"=>354
                ]
	        ],
	        "add_allocation"=>[],
            "appKey"=>"nexus",
            "appSecret"=>"nexusIt",
            "lang_id"=>"1",
            "user_company_id"=>"18"
        ];

        $result =  $this->callSoaErp('post','/product/updateRouteTemplateByRouteTemplateId',$data);

        return json_encode($result);
    }

    public function sss(){
        $data=[
            "team_product_number"=>"TP20181206031201"
        ];

        $result =  $this->callSoaErp('post','/product/getTeamProductBase',$data);

        return json_encode($result);
    }
}


