<?php

/**
 * 助手函数类，主要就是解决一些公用的函数所用
 */

namespace app\common\help;

use phpmailer\phpmailer;
use think\Cache;
/**
 * Description of Helper
 *
 * @author 胡
 */
class Help {

    public static function http($method,$url,$data=null)
    {

        //1初始化，创建一个新cURL资源

        $ch = curl_init();
        if($method == 'post'){
            curl_setopt($ch, CURLOPT_POST, 1);
            // Accept:application/json; charset=utf-8;Content-Type:application/x-www-form-urlencoded;charset=utf-8
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:application/json'));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//http_build_query

        }
        //2设置URL和相应的选项
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        //3抓取URL并把它传递给浏览器

        $output = curl_exec($ch);

        return $output;
        //4关闭cURL资源，并且释放系统资源

        curl_close($ch);
        exit();

    }

    public static function toTree($array, $pid =0, $level = 0, $id = 'route_type_id')
    {
        $array = json_decode(json_encode($array),true);
//
//        $a = array();
//        foreach($items as $value){
//            $a[$value['route_type_id']] = $value;
//        }
//        //第二部 遍历数据 生成树状结构
//        $tree = array();
//        foreach($a as $key => $value){
//            if(isset($a[$key['pid']])){
//                $a[$key['pid']]['son'][] = &$a[$key];
//            }else{
//                $tree[] = &$a[$key];
//            }
//        }
//        return $tree;

        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $list = [];
        foreach ($array as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['pid'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $value['level'] = $level;
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                Help::toTree($array, $value[$id], $level+1, $id);
            }
        }
        return $list;

    }



    /**
     * 模型数据转数组
     * @param $data
     * @return mixed
     */
    public static function modelDataToArr($data){
        return json_decode(json_encode($data),true);
    }
    
    /**
     * 发送邮箱
     * @param type $data 邮箱队列数据 包含邮箱地址 内容
     */
    public static function  sendEmail($address,$title,$message) {
    	Vendor('phpmailer.phpmailer');
    	$mail = new \PHPMailer(); //实例化
		$mail->IsSMTP();
		// 设置邮件的字符编码，若不指定，则为'UTF-8'
		$mail->CharSet='UTF-8';
		// 添加收件人地址，可以多次使用来添加多个收件人
		$mail->AddAddress($address);
		// 设置邮件正文
		$mail->Body=$message;
		// 设置邮件头的From字段。
		$mail->From='736605639@qq.com';
		// 设置发件人名字
		$mail->FromName='胡伊敏测试';
		// 设置邮件标题
		$mail->Subject=$title;
		// 设置SMTP服务器。
		$mail->Host='smtp.qq.com';
		// 设置为“需要验证”
		$mail->SMTPAuth=true;
		// 设置用户名和密码。
		$mail->Username='736605639@qq.com';
		$mail->Password='zeozqefaigicbeab';
		// 发送邮件。
		return($mail->Send());
    	
    }
    
    
    //获取编号
    public static function getNumber($key,$type=1){//type =1只返回编号 type=2 返回完整编号
    	//
    	switch ($key){
    		case 1://线路
    			$name = 'RT';
    			break;
    		case 2://团队产品
    			$name = 'TP';
    			break;
    		case 3://分公司产品
    			$name='BP';
    			break;
    		case 4://分公司订单
    			$name='Bk';
    			break;
    		case 5://应收汇总
    			$name='RC';
    			break;
    		case 6://应付汇总
    			$name='CO';
    			break;
    		case 7://游客
    			$name='CUS';
    			break;
    		case 8://公司订单产品-自定义
    			$name='COPD';
    			break;
    	    case 9://地接报账
    			$name='TAR';
    			break;    			
    		case 51://供应商
    			$name='LTA';
    			break;    			
    		case 52://酒店
    			$name='HLT';
    			break;
    		case 53://用餐
    			$name='RST';
    			break;
    		case 54://航班
    			$name='AIR';
    			break;
    		case 55://游轮
    			$name='CRS';
    			break;
    		case 56://签证
    			$name='AGC';
    			break;
    		case 57://景点
    			$name='SSC';
    			break;
    		case 58://车辆
    			$name='MTC';
    			break;
    		case 59://导游
    			$name='LXS';
    			break;
    		case 60://单项资源
    			$name='OTH';
    			break;    	
    		case 61://自费项目
    			$name='OEX';
    			break;
            case 62://购物店
                $name='OS';
                break;
    		case 200://收款编号
    			$name='SKBH';
    			break;
    		case 201://付款编号
    			$name='FKBH';
    			break;
    		case 202://发票
    			$name='RN-';
            case 203:   //b2b_booking_invoice
                $name='INV-';
    		break;
    	}
    	if($type==1){
    		return $name;
    		exit();
    	}

  
    	//首先获取缓存
    	$result = Cache::get($name);
    	//JSON字符串转成数组
    	$result =json_decode($result,true);

    	//如果为空则
    	if(empty($result)){
    		$result = [];
    	}
    	$product_number = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    	//开始判断是否存在数组
    	while(in_array($product_number,$result)){
    		
    		$product_number =str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    	}
    	//把新获得的 产品订单号插入数组
    	$result[] = $product_number;
    	
    	//再把数组转换成JSON
    	$result = json_encode($result);
    	
    	//再把数据插入缓存 每天的凌晨会重置
    	Cache::set($name,$result,date('Y-m-d 00:00:00',strtotime('+1 day')));
    	return $name.date('ymd').$product_number;
    	
    }
//     //获取不重复的编码
//     public  static function getNumber(){
//     	return md5(uniqid(md5(microtime(true)),true));
    	
//     }
	public static function getFeeType($params){
		$array = [
				
				['fee_type_name'=>'应付供应商的酒店款','fee_type_type'=>101,'fee_type_code'=>1],
				['fee_type_name'=>'应付供应商的用餐款','fee_type_type'=>101,'fee_type_code'=>2],
				['fee_type_name'=>'应付供应商的航班款','fee_type_type'=>101,'fee_type_code'=>3],
				['fee_type_name'=>'应付供应商的邮轮款','fee_type_type'=>101,'fee_type_code'=>4],
				['fee_type_name'=>'应付供应商的签证款','fee_type_type'=>101,'fee_type_code'=>5],
				['fee_type_name'=>'应付供应商的景点款','fee_type_type'=>101,'fee_type_code'=>6],
				['fee_type_name'=>'应付供应商的车辆款','fee_type_type'=>101,'fee_type_code'=>7],
				['fee_type_name'=>'应付供应商的导游款','fee_type_type'=>101,'fee_type_code'=>8],
				['fee_type_name'=>'应付供应商的单项资源款','fee_type_type'=>101,'fee_type_code'=>9],
				['fee_type_name'=>'应付供应商的自费项目款','fee_type_type'=>101,'fee_type_code'=>10],
				['fee_type_name'=>'应付供应商的其他项目款','fee_type_type'=>101,'fee_type_code'=>11],
				['fee_type_name'=>'地接报账的应付供应商的酒店款','fee_type_type'=>102,'fee_type_code'=>12],
				['fee_type_name'=>'地接报账的应付供应商的用餐款','fee_type_type'=>102,'fee_type_code'=>13],
				['fee_type_name'=>'地接报账的应付供应商的航班款','fee_type_type'=>102,'fee_type_code'=>14],
				['fee_type_name'=>'地接报账的应付供应商的邮轮款','fee_type_type'=>102,'fee_type_code'=>15],
				['fee_type_name'=>'地接报账的应付供应商的签证款','fee_type_type'=>102,'fee_type_code'=>16],
				['fee_type_name'=>'地接报账的应付供应商的景点款','fee_type_type'=>102,'fee_type_code'=>17],
				['fee_type_name'=>'地接报账的应付供应商的车辆款','fee_type_type'=>102,'fee_type_code'=>18],
				['fee_type_name'=>'地接报账的应付供应商的导游款','fee_type_type'=>102,'fee_type_code'=>19],
				['fee_type_name'=>'地接报账的应付供应商的单项资源款','fee_type_type'=>102,'fee_type_code'=>20],
				['fee_type_name'=>'地接报账的应付供应商的自费项目款','fee_type_type'=>102,'fee_type_code'=>21],
				['fee_type_name'=>'地接报账的应付供应商的其他项目款','fee_type_type'=>102,'fee_type_code'=>22],
				['fee_type_name'=>'凭空添加的其他应付供应商的酒店款','fee_type_type'=>103,'fee_type_code'=>23],
				['fee_type_name'=>'凭空添加的其他应付供应商的用餐款','fee_type_type'=>103,'fee_type_code'=>24],
				['fee_type_name'=>'凭空添加的其他应付供应商的航班款','fee_type_type'=>103,'fee_type_code'=>25],
				['fee_type_name'=>'凭空添加的其他应付供应商的邮轮款','fee_type_type'=>103,'fee_type_code'=>26],
				['fee_type_name'=>'凭空添加的其他应付供应商的签证款','fee_type_type'=>103,'fee_type_code'=>27],
				['fee_type_name'=>'凭空添加的其他应付供应商的景点款','fee_type_type'=>103,'fee_type_code'=>28],
				['fee_type_name'=>'凭空添加的其他应付供应商的车辆款','fee_type_type'=>103,'fee_type_code'=>29],
				['fee_type_name'=>'凭空添加的其他应付供应商的导游款','fee_type_type'=>103,'fee_type_code'=>30],
				['fee_type_name'=>'凭空添加的其他应付供应商的单项资源款','fee_type_type'=>103,'fee_type_code'=>31],
				['fee_type_name'=>'凭空添加的其他应付供应商的自费项目款','fee_type_type'=>103,'fee_type_code'=>32],
				['fee_type_name'=>'凭空添加的其他应付供应商的其他项目款','fee_type_type'=>103,'fee_type_code'=>33],
				['fee_type_name'=>'应收分公司团队产品款','fee_type_type'=>201,'fee_type_code'=>34],
				['fee_type_name'=>'应收分公司的酒店款','fee_type_type'=>201,'fee_type_code'=>35],
				['fee_type_name'=>'应收分公司的用餐款','fee_type_type'=>201,'fee_type_code'=>36],
				['fee_type_name'=>'应收分公司的航班款','fee_type_type'=>201,'fee_type_code'=>37],
				['fee_type_name'=>'应收分公司的邮轮款','fee_type_type'=>201,'fee_type_code'=>38],
				['fee_type_name'=>'应收分公司的签证款','fee_type_type'=>201,'fee_type_code'=>39],
				['fee_type_name'=>'应收分公司的景点款','fee_type_type'=>201,'fee_type_code'=>40],
				['fee_type_name'=>'应收分公司的车辆款','fee_type_type'=>201,'fee_type_code'=>41],
				['fee_type_name'=>'应收分公司的导游款','fee_type_type'=>201,'fee_type_code'=>42],
				['fee_type_name'=>'应收分公司的单项资源款','fee_type_type'=>201,'fee_type_code'=>43],
				['fee_type_name'=>'应收分公司的自费项目款','fee_type_type'=>201,'fee_type_code'=>44],
				['fee_type_name'=>'应收分公司的其他项目款','fee_type_type'=>201,'fee_type_code'=>45],
				['fee_type_name'=>'地接报账的应收分公司团队产品款','fee_type_type'=>202,'fee_type_code'=>46],
				['fee_type_name'=>'地接报账的应收分公司的酒店款','fee_type_type'=>202,'fee_type_code'=>47],
				['fee_type_name'=>'地接报账的应收分公司的用餐款','fee_type_type'=>202,'fee_type_code'=>48],
				['fee_type_name'=>'地接报账的应收分公司的航班款','fee_type_type'=>202,'fee_type_code'=>49],
				['fee_type_name'=>'地接报账的应收分公司的邮轮款','fee_type_type'=>202,'fee_type_code'=>50],
				['fee_type_name'=>'地接报账的应收分公司的签证款','fee_type_type'=>202,'fee_type_code'=>51],
				['fee_type_name'=>'地接报账的应收分公司的景点款','fee_type_type'=>202,'fee_type_code'=>52],
				['fee_type_name'=>'地接报账的应收分公司的车辆款','fee_type_type'=>202,'fee_type_code'=>53],
				['fee_type_name'=>'地接报账的应收分公司的导游款','fee_type_type'=>202,'fee_type_code'=>54],
				['fee_type_name'=>'地接报账的应收分公司的单项资源款','fee_type_type'=>202,'fee_type_code'=>55],
				['fee_type_name'=>'地接报账的应收分公司的自费项目款','fee_type_type'=>202,'fee_type_code'=>56],
				['fee_type_name'=>'地接报账的应收分公司的其他项目款','fee_type_type'=>202,'fee_type_code'=>57],
				['fee_type_name'=>'凭空添加的其他应收分公司团队产品款','fee_type_type'=>203,'fee_type_code'=>58],
				['fee_type_name'=>'凭空添加的其他应收分公司的酒店款','fee_type_type'=>203,'fee_type_code'=>59],
				['fee_type_name'=>'凭空添加的其他应收分公司的用餐款','fee_type_type'=>203,'fee_type_code'=>60],
				['fee_type_name'=>'凭空添加的其他应收分公司的航班款','fee_type_type'=>203,'fee_type_code'=>61],
				['fee_type_name'=>'凭空添加的其他应收分公司的邮轮款','fee_type_type'=>203,'fee_type_code'=>62],
				['fee_type_name'=>'凭空添加的其他应收分公司的签证款','fee_type_type'=>203,'fee_type_code'=>63],
				['fee_type_name'=>'凭空添加的其他应收分公司的景点款','fee_type_type'=>203,'fee_type_code'=>64],
				['fee_type_name'=>'凭空添加的其他应收分公司的车辆款','fee_type_type'=>203,'fee_type_code'=>65],
				['fee_type_name'=>'凭空添加的其他应收分公司的导游款','fee_type_type'=>203,'fee_type_code'=>66],
				['fee_type_name'=>'凭空添加的其他应收分公司的单项资源款','fee_type_type'=>203,'fee_type_code'=>67],
				['fee_type_name'=>'凭空添加的其他应收分公司的自费项目款','fee_type_type'=>203,'fee_type_code'=>68],
				['fee_type_name'=>'凭空添加的其他应收分公司的其他项目款','fee_type_type'=>203,'fee_type_code'=>69],
				['fee_type_name'=>'应付发布公司团队产品款','fee_type_type'=>104,'fee_type_code'=>70],
				['fee_type_name'=>'应付供应商的酒店款','fee_type_type'=>104,'fee_type_code'=>71],
				['fee_type_name'=>'应付供应商的用餐款','fee_type_type'=>104,'fee_type_code'=>72],
				['fee_type_name'=>'应付供应商的航班款','fee_type_type'=>104,'fee_type_code'=>73],
				['fee_type_name'=>'应付供应商的邮轮款','fee_type_type'=>104,'fee_type_code'=>74],
				['fee_type_name'=>'应付供应商的签证款','fee_type_type'=>104,'fee_type_code'=>75],
				['fee_type_name'=>'应付供应商的景点款','fee_type_type'=>104,'fee_type_code'=>76],
				['fee_type_name'=>'应付供应商的车辆款','fee_type_type'=>104,'fee_type_code'=>77],
				['fee_type_name'=>'应付供应商的导游款','fee_type_type'=>104,'fee_type_code'=>78],
				['fee_type_name'=>'应付供应商的单项资源款','fee_type_type'=>104,'fee_type_code'=>79],
				['fee_type_name'=>'应付供应商的自费项目款','fee_type_type'=>104,'fee_type_code'=>80],
				['fee_type_name'=>'应付供应商的其他项目款','fee_type_type'=>104,'fee_type_code'=>81],
				['fee_type_name'=>'应收代理分公司产品款','fee_type_type'=>204,'fee_type_code'=>82],
				['fee_type_name'=>'应收代理的自费项目款','fee_type_type'=>204,'fee_type_code'=>83],
				['fee_type_name'=>'应收直客分公司产品款','fee_type_type'=>204,'fee_type_code'=>84],
				['fee_type_name'=>'应收直客的自费项目款','fee_type_type'=>204,'fee_type_code'=>85],
				['fee_type_name'=>'销售代收的实收代理分公司产品款','fee_type_type'=>601,'fee_type_code'=>86],
				['fee_type_name'=>'销售代收的实收代理的自费项目款','fee_type_type'=>601,'fee_type_code'=>87],
				['fee_type_name'=>'销售代收的实收直客分公司产品款','fee_type_type'=>601,'fee_type_code'=>88],
				['fee_type_name'=>'销售代收的实收直客的自费项目款','fee_type_type'=>601,'fee_type_code'=>89],
				['fee_type_name'=>'地接报账的应付供应商的酒店款','fee_type_type'=>105,'fee_type_code'=>90],
				['fee_type_name'=>'地接报账的应付供应商的用餐款','fee_type_type'=>105,'fee_type_code'=>91],
				['fee_type_name'=>'地接报账的应付供应商的航班款','fee_type_type'=>105,'fee_type_code'=>92],
				['fee_type_name'=>'地接报账的应付供应商的邮轮款','fee_type_type'=>105,'fee_type_code'=>93],
				['fee_type_name'=>'地接报账的应付供应商的签证款','fee_type_type'=>105,'fee_type_code'=>94],
				['fee_type_name'=>'地接报账的应付供应商的景点款','fee_type_type'=>105,'fee_type_code'=>95],
				['fee_type_name'=>'地接报账的应付供应商的车辆款','fee_type_type'=>105,'fee_type_code'=>96],
				['fee_type_name'=>'地接报账的应付供应商的导游款','fee_type_type'=>105,'fee_type_code'=>97],
				['fee_type_name'=>'地接报账的应付供应商的单项资源款','fee_type_type'=>105,'fee_type_code'=>98],
				['fee_type_name'=>'地接报账的应付供应商的自费项目款','fee_type_type'=>105,'fee_type_code'=>99],
				['fee_type_name'=>'地接报账的应付供应商的其他项目款','fee_type_type'=>105,'fee_type_code'=>100],
				['fee_type_name'=>'地接报账的应收分公司团队产品款','fee_type_type'=>205,'fee_type_code'=>101],
				['fee_type_name'=>'地接报账的应收分公司的酒店款','fee_type_type'=>205,'fee_type_code'=>102],
				['fee_type_name'=>'地接报账的应收分公司的用餐款','fee_type_type'=>205,'fee_type_code'=>103],
				['fee_type_name'=>'地接报账的应收分公司的航班款','fee_type_type'=>205,'fee_type_code'=>104],
				['fee_type_name'=>'地接报账的应收分公司的邮轮款','fee_type_type'=>205,'fee_type_code'=>105],
				['fee_type_name'=>'地接报账的应收分公司的签证款','fee_type_type'=>205,'fee_type_code'=>106],
				['fee_type_name'=>'地接报账的应收分公司的景点款','fee_type_type'=>205,'fee_type_code'=>107],
				['fee_type_name'=>'地接报账的应收分公司的车辆款','fee_type_type'=>205,'fee_type_code'=>108],
				['fee_type_name'=>'地接报账的应收分公司的导游款','fee_type_type'=>205,'fee_type_code'=>109],
				['fee_type_name'=>'地接报账的应收分公司的单项资源款','fee_type_type'=>205,'fee_type_code'=>110],
				['fee_type_name'=>'地接报账的应收分公司的自费项目款','fee_type_type'=>205,'fee_type_code'=>111],
				['fee_type_name'=>'地接报账的应收分公司的其他项目款','fee_type_type'=>205,'fee_type_code'=>112],
				['fee_type_name'=>'财务实收分公司团队产品款','fee_type_type'=>602,'fee_type_code'=>113],
				['fee_type_name'=>'财务实收分公司的酒店款','fee_type_type'=>602,'fee_type_code'=>114],
				['fee_type_name'=>'财务实收分公司的用餐款','fee_type_type'=>602,'fee_type_code'=>115],
				['fee_type_name'=>'财务实收分公司的航班款','fee_type_type'=>602,'fee_type_code'=>116],
				['fee_type_name'=>'财务实收分公司的邮轮款','fee_type_type'=>602,'fee_type_code'=>117],
				['fee_type_name'=>'财务实收分公司的签证款','fee_type_type'=>602,'fee_type_code'=>118],
				['fee_type_name'=>'财务实收分公司的景点款','fee_type_type'=>602,'fee_type_code'=>119],
				['fee_type_name'=>'财务实收分公司的车辆款','fee_type_type'=>602,'fee_type_code'=>120],
				['fee_type_name'=>'财务实收分公司的导游款','fee_type_type'=>602,'fee_type_code'=>121],
				['fee_type_name'=>'财务实收分公司的单项资源款','fee_type_type'=>602,'fee_type_code'=>122],
				['fee_type_name'=>'财务实收分公司的自费项目款','fee_type_type'=>602,'fee_type_code'=>123],
				['fee_type_name'=>'财务实收分公司的其他项目款','fee_type_type'=>602,'fee_type_code'=>124],
				['fee_type_name'=>'财务实收地接报账的收分公司团队产品款','fee_type_type'=>603,'fee_type_code'=>125],
				['fee_type_name'=>'财务实收地接报账的应收分公司的酒店款','fee_type_type'=>603,'fee_type_code'=>126],
				['fee_type_name'=>'财务实收地接报账的应收分公司的用餐款','fee_type_type'=>603,'fee_type_code'=>127],
				['fee_type_name'=>'财务实收地接报账的应收分公司的航班款','fee_type_type'=>603,'fee_type_code'=>128],
				['fee_type_name'=>'财务实收地接报账的应收分公司的邮轮款','fee_type_type'=>603,'fee_type_code'=>129],
				['fee_type_name'=>'财务实收地接报账的应收分公司的签证款','fee_type_type'=>603,'fee_type_code'=>130],
				['fee_type_name'=>'财务实收地接报账的应收分公司的景点款','fee_type_type'=>603,'fee_type_code'=>131],
				['fee_type_name'=>'财务实收地接报账的应收分公司的车辆款','fee_type_type'=>603,'fee_type_code'=>132],
				['fee_type_name'=>'财务实收地接报账的应收分公司的导游款','fee_type_type'=>603,'fee_type_code'=>133],
				['fee_type_name'=>'财务实收地接报账的应收分公司的单项资源款','fee_type_type'=>603,'fee_type_code'=>134],
				['fee_type_name'=>'财务实收地接报账的应收分公司的自费项目款','fee_type_type'=>603,'fee_type_code'=>135],
				['fee_type_name'=>'财务实收地接报账的应收分公司的其他项目款','fee_type_type'=>603,'fee_type_code'=>136],
				['fee_type_name'=>'财务实收凭空添加的其他应收分公司团队产品款','fee_type_type'=>604,'fee_type_code'=>137],
				['fee_type_name'=>'财务实收凭空添加的其他应收分公司的酒店款','fee_type_type'=>604,'fee_type_code'=>138],
				['fee_type_name'=>'财务实收凭空添加的其他应收分公司的用餐款','fee_type_type'=>604,'fee_type_code'=>139],
				['fee_type_name'=>'财务实收凭空添加的其他应收分公司的航班款','fee_type_type'=>604,'fee_type_code'=>140],
				['fee_type_name'=>'财务实收凭空添加的其他应收分公司的邮轮款','fee_type_type'=>604,'fee_type_code'=>141],
				['fee_type_name'=>'财务实收凭空添加的其他应收分公司的签证款','fee_type_type'=>604,'fee_type_code'=>142],
				['fee_type_name'=>'财务实收凭空添加的其他应收分公司的景点款','fee_type_type'=>604,'fee_type_code'=>143],
				['fee_type_name'=>'财务实收凭空添加的其他应收分公司的车辆款','fee_type_type'=>604,'fee_type_code'=>144],
				['fee_type_name'=>'财务实收凭空添加的其他应收分公司的导游款','fee_type_type'=>604,'fee_type_code'=>145],
				['fee_type_name'=>'财务实收凭空添加的其他应收分公司的单项资源款','fee_type_type'=>604,'fee_type_code'=>146],
				['fee_type_name'=>'财务实收凭空添加的其他应收分公司的自费项目款','fee_type_type'=>604,'fee_type_code'=>147],
				['fee_type_name'=>'财务实收凭空添加的其他应收分公司的其他项目款','fee_type_type'=>604,'fee_type_code'=>148],
				['fee_type_name'=>'财务实收代理分公司产品款','fee_type_type'=>605,'fee_type_code'=>149],
				['fee_type_name'=>'财务实收代理的自费项目款','fee_type_type'=>605,'fee_type_code'=>150],
				['fee_type_name'=>'财务实收直客分公司产品款','fee_type_type'=>605,'fee_type_code'=>151],
				['fee_type_name'=>'财务实收直客的自费项目款','fee_type_type'=>605,'fee_type_code'=>152],
				['fee_type_name'=>'财务创建应收团队产品款','fee_type_type'=>206,'fee_type_code'=>153],
				['fee_type_name'=>'财务创建应收酒店款','fee_type_type'=>206,'fee_type_code'=>154],
				['fee_type_name'=>'财务创建应收用餐款','fee_type_type'=>206,'fee_type_code'=>155],
				['fee_type_name'=>'财务创建应收航班款','fee_type_type'=>206,'fee_type_code'=>156],
				['fee_type_name'=>'财务创建应收邮轮款','fee_type_type'=>206,'fee_type_code'=>157],
				['fee_type_name'=>'财务创建应收签证款','fee_type_type'=>206,'fee_type_code'=>158],
				['fee_type_name'=>'财务创建应收景点款','fee_type_type'=>206,'fee_type_code'=>159],
				['fee_type_name'=>'财务创建应收车辆款','fee_type_type'=>206,'fee_type_code'=>160],
				['fee_type_name'=>'财务创建应收导游款','fee_type_type'=>206,'fee_type_code'=>161],
				['fee_type_name'=>'财务创建应收单项资源款','fee_type_type'=>206,'fee_type_code'=>162],
				['fee_type_name'=>'财务创建应收自费项目款','fee_type_type'=>206,'fee_type_code'=>163],
				['fee_type_name'=>'财务创建应收其他项目款','fee_type_type'=>206,'fee_type_code'=>164],
				['fee_type_name'=>'创建应付对应的应收团队产品款','fee_type_type'=>106,'fee_type_code'=>165],
				['fee_type_name'=>'创建应付对应的应收酒店款','fee_type_type'=>106,'fee_type_code'=>166],
				['fee_type_name'=>'创建应付对应的应收用餐款','fee_type_type'=>106,'fee_type_code'=>167],
				['fee_type_name'=>'创建应付对应的应收航班款','fee_type_type'=>106,'fee_type_code'=>168],
				['fee_type_name'=>'创建应付对应的应收邮轮款','fee_type_type'=>106,'fee_type_code'=>169],
				['fee_type_name'=>'创建应付对应的应收签证款','fee_type_type'=>106,'fee_type_code'=>170],
				['fee_type_name'=>'创建应付对应的应收景点款','fee_type_type'=>106,'fee_type_code'=>171],
				['fee_type_name'=>'创建应付对应的应收车辆款','fee_type_type'=>106,'fee_type_code'=>172],
				['fee_type_name'=>'创建应付对应的应收导游款','fee_type_type'=>106,'fee_type_code'=>173],
				['fee_type_name'=>'创建应付对应的应收单项资源款','fee_type_type'=>106,'fee_type_code'=>174],
				['fee_type_name'=>'创建应付对应的应收自费项目款','fee_type_type'=>106,'fee_type_code'=>175],
				['fee_type_name'=>'创建应付对应的应收其他项目款','fee_type_type'=>106,'fee_type_code'=>176],
				['fee_type_name'=>'财务创建实收团队产品款','fee_type_type'=>606,'fee_type_code'=>177],
				['fee_type_name'=>'财务创建实收酒店款','fee_type_type'=>606,'fee_type_code'=>178],
				['fee_type_name'=>'财务创建实收用餐款','fee_type_type'=>606,'fee_type_code'=>179],
				['fee_type_name'=>'财务创建实收航班款','fee_type_type'=>606,'fee_type_code'=>180],
				['fee_type_name'=>'财务创建实收邮轮款','fee_type_type'=>606,'fee_type_code'=>181],
				['fee_type_name'=>'财务创建实收签证款','fee_type_type'=>606,'fee_type_code'=>182],
				['fee_type_name'=>'财务创建实收景点款','fee_type_type'=>606,'fee_type_code'=>183],
				['fee_type_name'=>'财务创建实收车辆款','fee_type_type'=>606,'fee_type_code'=>184],
				['fee_type_name'=>'财务创建实收导游款','fee_type_type'=>606,'fee_type_code'=>185],
				['fee_type_name'=>'财务创建实收单项资源款','fee_type_type'=>606,'fee_type_code'=>186],
				['fee_type_name'=>'财务创建实收自费项目款','fee_type_type'=>606,'fee_type_code'=>187],
				['fee_type_name'=>'财务创建实收其他项目款','fee_type_type'=>606,'fee_type_code'=>188],
				['fee_type_name'=>'创建应付对应的应收团队产品款的实付款','fee_type_type'=>107,'fee_type_code'=>189],
				['fee_type_name'=>'创建应付对应的应收酒店款的实付款','fee_type_type'=>107,'fee_type_code'=>190],
				['fee_type_name'=>'创建应付对应的应收用餐款的实付款','fee_type_type'=>107,'fee_type_code'=>191],
				['fee_type_name'=>'创建应付对应的应收航班款的实付款','fee_type_type'=>107,'fee_type_code'=>192],
				['fee_type_name'=>'创建应付对应的应收邮轮款的实付款','fee_type_type'=>107,'fee_type_code'=>193],
				['fee_type_name'=>'创建应付对应的应收签证款的实付款','fee_type_type'=>107,'fee_type_code'=>194],
				['fee_type_name'=>'创建应付对应的应收景点款的实付款','fee_type_type'=>107,'fee_type_code'=>195],
				['fee_type_name'=>'创建应付对应的应收车辆款的实付款','fee_type_type'=>107,'fee_type_code'=>196],
				['fee_type_name'=>'创建应付对应的应收导游款的实付款','fee_type_type'=>107,'fee_type_code'=>197],
				['fee_type_name'=>'创建应付对应的应收单项资源款的实付款','fee_type_type'=>107,'fee_type_code'=>198],
				['fee_type_name'=>'创建应付对应的应收自费项目款的实付款','fee_type_type'=>107,'fee_type_code'=>199],
				['fee_type_name'=>'创建应付对应的应收其他项目款的实付款','fee_type_type'=>107,'fee_type_code'=>200],
				['fee_type_name'=>'财务实付分公司团队产品款','fee_type_type'=>501,'fee_type_code'=>201],
				['fee_type_name'=>'财务实付分公司的酒店款','fee_type_type'=>501,'fee_type_code'=>202],
				['fee_type_name'=>'财务实付分公司的用餐款','fee_type_type'=>501,'fee_type_code'=>203],
				['fee_type_name'=>'财务实付分公司的航班款','fee_type_type'=>501,'fee_type_code'=>204],
				['fee_type_name'=>'财务实付分公司的邮轮款','fee_type_type'=>501,'fee_type_code'=>205],
				['fee_type_name'=>'财务实付分公司的签证款','fee_type_type'=>501,'fee_type_code'=>206],
				['fee_type_name'=>'财务实付分公司的景点款','fee_type_type'=>501,'fee_type_code'=>207],
				['fee_type_name'=>'财务实付分公司的车辆款','fee_type_type'=>501,'fee_type_code'=>208],
				['fee_type_name'=>'财务实付分公司的导游款','fee_type_type'=>501,'fee_type_code'=>209],
				['fee_type_name'=>'财务实付分公司的单项资源款','fee_type_type'=>501,'fee_type_code'=>210],
				['fee_type_name'=>'财务实付分公司的自费项目款','fee_type_type'=>501,'fee_type_code'=>211],
				['fee_type_name'=>'财务实付分公司的其他项目款','fee_type_type'=>501,'fee_type_code'=>212],
				['fee_type_name'=>'财务实付地接报账的收分公司团队产品款','fee_type_type'=>502,'fee_type_code'=>213],
				['fee_type_name'=>'财务实付地接报账的应收分公司的酒店款','fee_type_type'=>502,'fee_type_code'=>214],
				['fee_type_name'=>'财务实付地接报账的应收分公司的用餐款','fee_type_type'=>502,'fee_type_code'=>215],
				['fee_type_name'=>'财务实付地接报账的应收分公司的航班款','fee_type_type'=>502,'fee_type_code'=>216],
				['fee_type_name'=>'财务实付地接报账的应收分公司的邮轮款','fee_type_type'=>502,'fee_type_code'=>217],
				['fee_type_name'=>'财务实付地接报账的应收分公司的签证款','fee_type_type'=>502,'fee_type_code'=>218],
				['fee_type_name'=>'财务实付地接报账的应收分公司的景点款','fee_type_type'=>502,'fee_type_code'=>219],
				['fee_type_name'=>'财务实付地接报账的应收分公司的车辆款','fee_type_type'=>502,'fee_type_code'=>220],
				['fee_type_name'=>'财务实付地接报账的应收分公司的导游款','fee_type_type'=>502,'fee_type_code'=>221],
				['fee_type_name'=>'财务实付地接报账的应收分公司的单项资源款','fee_type_type'=>502,'fee_type_code'=>222],
				['fee_type_name'=>'财务实付地接报账的应收分公司的自费项目款','fee_type_type'=>502,'fee_type_code'=>223],
				['fee_type_name'=>'财务实付地接报账的应收分公司的其他项目款','fee_type_type'=>502,'fee_type_code'=>224],
				['fee_type_name'=>'财务实付凭空添加的其他应收分公司团队产品款','fee_type_type'=>503,'fee_type_code'=>225],
				['fee_type_name'=>'财务实付凭空添加的其他应收分公司的酒店款','fee_type_type'=>503,'fee_type_code'=>226],
				['fee_type_name'=>'财务实付凭空添加的其他应收分公司的用餐款','fee_type_type'=>503,'fee_type_code'=>227],
				['fee_type_name'=>'财务实付凭空添加的其他应收分公司的航班款','fee_type_type'=>503,'fee_type_code'=>228],
				['fee_type_name'=>'财务实付凭空添加的其他应收分公司的邮轮款','fee_type_type'=>503,'fee_type_code'=>229],
				['fee_type_name'=>'财务实付凭空添加的其他应收分公司的签证款','fee_type_type'=>503,'fee_type_code'=>230],
				['fee_type_name'=>'财务实付凭空添加的其他应收分公司的景点款','fee_type_type'=>503,'fee_type_code'=>231],
				['fee_type_name'=>'财务实付凭空添加的其他应收分公司的车辆款','fee_type_type'=>503,'fee_type_code'=>232],
				['fee_type_name'=>'财务实付凭空添加的其他应收分公司的导游款','fee_type_type'=>503,'fee_type_code'=>233],
				['fee_type_name'=>'财务实付凭空添加的其他应收分公司的单项资源款','fee_type_type'=>503,'fee_type_code'=>234],
				['fee_type_name'=>'财务实付凭空添加的其他应收分公司的自费项目款','fee_type_type'=>503,'fee_type_code'=>235],
				['fee_type_name'=>'财务实付凭空添加的其他应收分公司的其他项目款','fee_type_type'=>503,'fee_type_code'=>236],
				['fee_type_name'=>'财务实付代理分公司产品款','fee_type_type'=>504,'fee_type_code'=>237],
				['fee_type_name'=>'财务实付代理的自费项目款','fee_type_type'=>504,'fee_type_code'=>238],
				['fee_type_name'=>'财务实付直客分公司产品款','fee_type_type'=>504,'fee_type_code'=>239],
				['fee_type_name'=>'财务实付直客的自费项目款','fee_type_type'=>504,'fee_type_code'=>240],
				['fee_type_name'=>'财务创建应付团队产品款','fee_type_type'=>108,'fee_type_code'=>241],
				['fee_type_name'=>'财务创建应付酒店款','fee_type_type'=>108,'fee_type_code'=>242],
				['fee_type_name'=>'财务创建应付用餐款','fee_type_type'=>108,'fee_type_code'=>243],
				['fee_type_name'=>'财务创建应付航班款','fee_type_type'=>108,'fee_type_code'=>244],
				['fee_type_name'=>'财务创建应付邮轮款','fee_type_type'=>108,'fee_type_code'=>245],
				['fee_type_name'=>'财务创建应付签证款','fee_type_type'=>108,'fee_type_code'=>246],
				['fee_type_name'=>'财务创建应付景点款','fee_type_type'=>108,'fee_type_code'=>247],
				['fee_type_name'=>'财务创建应付车辆款','fee_type_type'=>108,'fee_type_code'=>248],
				['fee_type_name'=>'财务创建应付导游款','fee_type_type'=>108,'fee_type_code'=>249],
				['fee_type_name'=>'财务创建应付单项资源款','fee_type_type'=>108,'fee_type_code'=>250],
				['fee_type_name'=>'财务创建应付自费项目款','fee_type_type'=>108,'fee_type_code'=>251],
				['fee_type_name'=>'财务创建应付其他项目款','fee_type_type'=>108,'fee_type_code'=>252],
				['fee_type_name'=>'创建应收对应的应付团队产品款','fee_type_type'=>207,'fee_type_code'=>253],
				['fee_type_name'=>'创建应收对应的应付酒店款','fee_type_type'=>207,'fee_type_code'=>254],
				['fee_type_name'=>'创建应收对应的应付用餐款','fee_type_type'=>207,'fee_type_code'=>255],
				['fee_type_name'=>'创建应收对应的应付航班款','fee_type_type'=>207,'fee_type_code'=>256],
				['fee_type_name'=>'创建应收对应的应付邮轮款','fee_type_type'=>207,'fee_type_code'=>257],
				['fee_type_name'=>'创建应收对应的应付签证款','fee_type_type'=>207,'fee_type_code'=>258],
				['fee_type_name'=>'创建应收对应的应付景点款','fee_type_type'=>207,'fee_type_code'=>259],
				['fee_type_name'=>'创建应收对应的应付车辆款','fee_type_type'=>207,'fee_type_code'=>260],
				['fee_type_name'=>'创建应收对应的应付导游款','fee_type_type'=>207,'fee_type_code'=>261],
				['fee_type_name'=>'创建应收对应的应付单项资源款','fee_type_type'=>207,'fee_type_code'=>262],
				['fee_type_name'=>'创建应收对应的应付自费项目款','fee_type_type'=>207,'fee_type_code'=>263],
				['fee_type_name'=>'创建应收对应的应付其他项目款','fee_type_type'=>207,'fee_type_code'=>264],
				['fee_type_name'=>'财务创建实付团队产品款','fee_type_type'=>505,'fee_type_code'=>265],
				['fee_type_name'=>'财务创建实付酒店款','fee_type_type'=>505,'fee_type_code'=>266],
				['fee_type_name'=>'财务创建实付用餐款','fee_type_type'=>505,'fee_type_code'=>267],
				['fee_type_name'=>'财务创建实付航班款','fee_type_type'=>505,'fee_type_code'=>268],
				['fee_type_name'=>'财务创建实付邮轮款','fee_type_type'=>505,'fee_type_code'=>269],
				['fee_type_name'=>'财务创建实付签证款','fee_type_type'=>505,'fee_type_code'=>270],
				['fee_type_name'=>'财务创建实付景点款','fee_type_type'=>505,'fee_type_code'=>271],
				['fee_type_name'=>'财务创建实付车辆款','fee_type_type'=>505,'fee_type_code'=>272],
				['fee_type_name'=>'财务创建实付导游款','fee_type_type'=>505,'fee_type_code'=>273],
				['fee_type_name'=>'财务创建实付单项资源款','fee_type_type'=>505,'fee_type_code'=>274],
				['fee_type_name'=>'财务创建实付自费项目款','fee_type_type'=>505,'fee_type_code'=>275],
				['fee_type_name'=>'财务创建实付其他项目款','fee_type_type'=>505,'fee_type_code'=>276],
				['fee_type_name'=>'创建应收对应的应付团队产品款的实付款','fee_type_type'=>506,'fee_type_code'=>277],
				['fee_type_name'=>'创建应收对应的应付酒店款的实付款','fee_type_type'=>506,'fee_type_code'=>278],
				['fee_type_name'=>'创建应收对应的应付用餐款的实付款','fee_type_type'=>506,'fee_type_code'=>279],
				['fee_type_name'=>'创建应收对应的应付航班款的实付款','fee_type_type'=>506,'fee_type_code'=>280],
				['fee_type_name'=>'创建应收对应的应付邮轮款的实付款','fee_type_type'=>506,'fee_type_code'=>281],
				['fee_type_name'=>'创建应收对应的应付签证款的实付款','fee_type_type'=>506,'fee_type_code'=>282],
				['fee_type_name'=>'创建应收对应的应付景点款的实付款','fee_type_type'=>506,'fee_type_code'=>283],
				['fee_type_name'=>'创建应收对应的应付车辆款的实付款','fee_type_type'=>506,'fee_type_code'=>284],
				['fee_type_name'=>'创建应收对应的应付导游款的实付款','fee_type_type'=>506,'fee_type_code'=>285],
				['fee_type_name'=>'创建应收对应的应付单项资源款的实付款','fee_type_type'=>506,'fee_type_code'=>286],
				['fee_type_name'=>'创建应收对应的应付自费项目款的实付款','fee_type_type'=>506,'fee_type_code'=>287],
				['fee_type_name'=>'创建应收对应的应付其他项目款的实付款','fee_type_type'=>506,'fee_type_code'=>288],
				['fee_type_name'=>'应付发布公司自费项目款','fee_type_type'=>104,'fee_type_code'=>289],				
				['fee_type_name'=>'应付发布公司的酒店款','fee_type_type'=>104,'fee_type_code'=>290],
				['fee_type_name'=>'应付发布公司的用餐款','fee_type_type'=>104,'fee_type_code'=>291],
				['fee_type_name'=>'应付发布公司的航班款','fee_type_type'=>104,'fee_type_code'=>292],
				['fee_type_name'=>'应付发布公司的邮轮款','fee_type_type'=>104,'fee_type_code'=>293],
				['fee_type_name'=>'应付发布公司的签证款','fee_type_type'=>104,'fee_type_code'=>294],
				['fee_type_name'=>'应付发布公司的景点款','fee_type_type'=>104,'fee_type_code'=>295],
				['fee_type_name'=>'应付发布公司的车辆款','fee_type_type'=>104,'fee_type_code'=>296],
				['fee_type_name'=>'应付发布公司的导游款','fee_type_type'=>104,'fee_type_code'=>297],
				['fee_type_name'=>'应付发布公司的单项资源款','fee_type_type'=>104,'fee_type_code'=>298],
				['fee_type_name'=>'应付发布公司的自费项目款','fee_type_type'=>104,'fee_type_code'=>299],				
				['fee_type_name'=>'应付发布公司的其他款','fee_type_type'=>104,'fee_type_code'=>300],
				['fee_type_name'=>'应付发布公司的操作款','fee_type_type'=>104,'fee_type_code'=>301],
				['fee_type_name'=>'应收分公司的操作项目款','fee_type_type'=>201,'fee_type_code'=>302],
				['fee_type_name'=>'应收代理自定义款','fee_type_type'=>204,'fee_type_code'=>303],
				['fee_type_name'=>'应收直客自定义款','fee_type_type'=>204,'fee_type_code'=>304],				
				['fee_type_name'=>'团队产品应收供应商酒店款','fee_type_type'=>208,'fee_type_code'=>305],
				['fee_type_name'=>'团队产品应收供应商用餐款','fee_type_type'=>208,'fee_type_code'=>306],
				['fee_type_name'=>'团队产品应收供应商航班款','fee_type_type'=>208,'fee_type_code'=>307],
				['fee_type_name'=>'团队产品应收供应商邮轮款','fee_type_type'=>208,'fee_type_code'=>308],
				['fee_type_name'=>'团队产品应收供应商签证款','fee_type_type'=>208,'fee_type_code'=>309],
				['fee_type_name'=>'团队产品应收供应商景点款','fee_type_type'=>208,'fee_type_code'=>310],
				['fee_type_name'=>'团队产品应收供应商车辆款','fee_type_type'=>208,'fee_type_code'=>311],
				['fee_type_name'=>'团队产品应收供应商导游款','fee_type_type'=>208,'fee_type_code'=>312],
				['fee_type_name'=>'团队产品应收供应商单项资源款','fee_type_type'=>208,'fee_type_code'=>313],
				['fee_type_name'=>'团队产品应收供应商自费项目款','fee_type_type'=>208,'fee_type_code'=>314],
				['fee_type_name'=>'团队产品应收供应商其他款','fee_type_type'=>208,'fee_type_code'=>315],				
				['fee_type_name'=>'团队产品应付分公司团队产品款','fee_type_type'=>109,'fee_type_code'=>316],
				['fee_type_name'=>'团队产品应付分公司酒店款','fee_type_type'=>109,'fee_type_code'=>317],
				['fee_type_name'=>'团队产品应付分公司用餐款','fee_type_type'=>109,'fee_type_code'=>318],
				['fee_type_name'=>'团队产品应付分公司航班款','fee_type_type'=>109,'fee_type_code'=>319],
				['fee_type_name'=>'团队产品应付分公司邮轮款','fee_type_type'=>109,'fee_type_code'=>320],
				['fee_type_name'=>'团队产品应付分公司签证款','fee_type_type'=>109,'fee_type_code'=>321],
				['fee_type_name'=>'团队产品应付分公司景点款','fee_type_type'=>109,'fee_type_code'=>322],
				['fee_type_name'=>'团队产品应付分公司车辆款','fee_type_type'=>109,'fee_type_code'=>323],
				['fee_type_name'=>'团队产品应付分公司导游款','fee_type_type'=>109,'fee_type_code'=>324],
				['fee_type_name'=>'团队产品应付分公司单项资源款','fee_type_type'=>109,'fee_type_code'=>325],
				['fee_type_name'=>'团队产品应付分公司自费项目款','fee_type_type'=>109,'fee_type_code'=>326],
				['fee_type_name'=>'团队产品应付分公司其他款','fee_type_type'=>109,'fee_type_code'=>327],				
				['fee_type_name'=>'团队产品应付分公司团队产品的应收款','fee_type_type'=>209,'fee_type_code'=>328],
				['fee_type_name'=>'团队产品应付分公司酒店应收款','fee_type_type'=>209,'fee_type_code'=>329],
				['fee_type_name'=>'团队产品应付分公司用餐应收款','fee_type_type'=>209,'fee_type_code'=>330],
				['fee_type_name'=>'团队产品应付分公司航班应收款','fee_type_type'=>209,'fee_type_code'=>331],
				['fee_type_name'=>'团队产品应付分公司邮轮应收款','fee_type_type'=>209,'fee_type_code'=>332],
				['fee_type_name'=>'团队产品应付分公司签证应收款','fee_type_type'=>209,'fee_type_code'=>333],
				['fee_type_name'=>'团队产品应付分公司景点应收款','fee_type_type'=>209,'fee_type_code'=>334],
				['fee_type_name'=>'团队产品应付分公司车辆应收款','fee_type_type'=>209,'fee_type_code'=>335],
				['fee_type_name'=>'团队产品应付分公司导游应收款','fee_type_type'=>209,'fee_type_code'=>336],
				['fee_type_name'=>'团队产品应付分公司单项资源应收款','fee_type_type'=>209,'fee_type_code'=>337],
				['fee_type_name'=>'团队产品应付分公司自费项目应收款','fee_type_type'=>209,'fee_type_code'=>338],
				['fee_type_name'=>'团队产品应付分公司其他应收款','fee_type_type'=>209,'fee_type_code'=>339],				
				['fee_type_name'=>'应付发布公司的团队产品款','fee_type_type'=>104,'fee_type_code'=>340],				
				['fee_type_name'=>'应收代理团费款','fee_type_type'=>2010,'fee_type_code'=>341],				
				['fee_type_name'=>'应收直客团费款','fee_type_type'=>2010,'fee_type_code'=>342],
				['fee_type_name'=>'应收代理团队自费项目款','fee_type_type'=>2010,'fee_type_code'=>343],				
				['fee_type_name'=>'应收直客团队自费项目款','fee_type_type'=>2010,'fee_type_code'=>344],
				['fee_type_name'=>'应收分公司往来账的团队产品款','fee_type_type'=>2011,'fee_type_code'=>345],
				['fee_type_name'=>'应收分公司往来账的酒店款','fee_type_type'=>2011,'fee_type_code'=>346],
				['fee_type_name'=>'应收分公司往来账的用餐款','fee_type_type'=>2011,'fee_type_code'=>347],
				['fee_type_name'=>'应收分公司往来账的航班款','fee_type_type'=>2011,'fee_type_code'=>348],
				['fee_type_name'=>'应收分公司往来账的邮轮款','fee_type_type'=>2011,'fee_type_code'=>349],
				['fee_type_name'=>'应收分公司往来账的签证款','fee_type_type'=>2011,'fee_type_code'=>350],
				['fee_type_name'=>'应收分公司往来账的景点款','fee_type_type'=>2011,'fee_type_code'=>351],
				['fee_type_name'=>'应收分公司往来账的车辆款','fee_type_type'=>2011,'fee_type_code'=>352],
				['fee_type_name'=>'应收分公司往来账的导游款','fee_type_type'=>2011,'fee_type_code'=>353],
				['fee_type_name'=>'应收分公司往来账的单项资源款','fee_type_type'=>2011,'fee_type_code'=>354],
				['fee_type_name'=>'应收分公司往来账的自费款','fee_type_type'=>2011,'fee_type_code'=>355],
				['fee_type_name'=>'应收分公司往来账的购物店款','fee_type_type'=>2011,'fee_type_code'=>356],
				['fee_type_name'=>'应收分公司往来账的其他款','fee_type_type'=>2011,'fee_type_code'=>357],
				['fee_type_name'=>'应付分公司往来账的团队产品款','fee_type_type'=>2012,'fee_type_code'=>358],
				['fee_type_name'=>'应付分公司往来账的酒店款','fee_type_type'=>2012,'fee_type_code'=>359],
				['fee_type_name'=>'应付分公司往来账的用餐款','fee_type_type'=>2012,'fee_type_code'=>360],
				['fee_type_name'=>'应付分公司往来账的航班款','fee_type_type'=>2012,'fee_type_code'=>361],
				['fee_type_name'=>'应付分公司往来账的邮轮款','fee_type_type'=>2012,'fee_type_code'=>362],
				['fee_type_name'=>'应付分公司往来账的签证款','fee_type_type'=>2012,'fee_type_code'=>363],
				['fee_type_name'=>'应付分公司往来账的景点款','fee_type_type'=>2012,'fee_type_code'=>364],
				['fee_type_name'=>'应付分公司往来账的车辆款','fee_type_type'=>2012,'fee_type_code'=>365],
				['fee_type_name'=>'应付分公司往来账的导游款','fee_type_type'=>2012,'fee_type_code'=>366],
				['fee_type_name'=>'应付分公司往来账的单项资源款','fee_type_type'=>2012,'fee_type_code'=>367],
				['fee_type_name'=>'应付分公司往来账的自费款','fee_type_type'=>2012,'fee_type_code'=>368],
				['fee_type_name'=>'应付分公司往来账的购物店款','fee_type_type'=>2012,'fee_type_code'=>369],
				['fee_type_name'=>'应付分公司往来账的其他款','fee_type_type'=>2012,'fee_type_code'=>370],	
				['fee_type_name'=>'应收分公司往来账款','fee_type_type'=>2011,'fee_type_code'=>371],
				['fee_type_name'=>'应付分公司往来账款','fee_type_type'=>2012,'fee_type_code'=>372],
				['fee_type_name'=>'应收供应商账款','fee_type_type'=>5000,'fee_type_code'=>373],
				['fee_type_name'=>'应付供应商账款','fee_type_type'=>5001,'fee_type_code'=>374],
				['fee_type_name'=>'应收渠道商账款','fee_type_type'=>5002,'fee_type_code'=>375],
				['fee_type_name'=>'应付渠道商账款','fee_type_type'=>5003,'fee_type_code'=>376],				
				['fee_type_name'=>'应收直客代售产品款','fee_type_type'=>6001,'fee_type_code'=>377],
				['fee_type_name'=>'应收直客酒店款','fee_type_type'=>6001,'fee_type_code'=>378],
				['fee_type_name'=>'应收直客用餐账款','fee_type_type'=>6001,'fee_type_code'=>379],
				['fee_type_name'=>'应收直客航班款','fee_type_type'=>6001,'fee_type_code'=>380],
				['fee_type_name'=>'应收直客邮轮款','fee_type_type'=>6001,'fee_type_code'=>381],
				['fee_type_name'=>'应收直客签证款','fee_type_type'=>6001,'fee_type_code'=>382],
				['fee_type_name'=>'应收直客景点款','fee_type_type'=>6001,'fee_type_code'=>383],
				['fee_type_name'=>'应收直客车辆款','fee_type_type'=>6001,'fee_type_code'=>384],
				['fee_type_name'=>'应收直客导游款','fee_type_type'=>6001,'fee_type_code'=>385],
				['fee_type_name'=>'应收直客单项资源款','fee_type_type'=>6001,'fee_type_code'=>386],
				['fee_type_name'=>'应收直客自费款','fee_type_type'=>6001,'fee_type_code'=>387],
				['fee_type_name'=>'应收直客购物店款','fee_type_type'=>6001,'fee_type_code'=>388],
				['fee_type_name'=>'应收直客其他款','fee_type_type'=>6001,'fee_type_code'=>389],
				['fee_type_name'=>'应收直客团费款','fee_type_type'=>6001,'fee_type_code'=>390],
				['fee_type_name'=>'应收代理代售产品款','fee_type_type'=>6002,'fee_type_code'=>391],
				['fee_type_name'=>'应收代理酒店款','fee_type_type'=>6002,'fee_type_code'=>392],
				['fee_type_name'=>'应收代理用餐账款','fee_type_type'=>6002,'fee_type_code'=>393],
				['fee_type_name'=>'应收代理航班款','fee_type_type'=>6002,'fee_type_code'=>394],
				['fee_type_name'=>'应收代理邮轮款','fee_type_type'=>6002,'fee_type_code'=>395],
				['fee_type_name'=>'应收代理签证款','fee_type_type'=>6002,'fee_type_code'=>396],
				['fee_type_name'=>'应收代理景点款','fee_type_type'=>6002,'fee_type_code'=>397],
				['fee_type_name'=>'应收代理车辆款','fee_type_type'=>6002,'fee_type_code'=>398],
				['fee_type_name'=>'应收代理导游款','fee_type_type'=>6002,'fee_type_code'=>399],
				['fee_type_name'=>'应收代理单项资源款','fee_type_type'=>6002,'fee_type_code'=>400],
				['fee_type_name'=>'应收代理自费款','fee_type_type'=>6002,'fee_type_code'=>401],
				['fee_type_name'=>'应收代理购物店款','fee_type_type'=>6002,'fee_type_code'=>402],
				['fee_type_name'=>'应收代理其他款','fee_type_type'=>6002,'fee_type_code'=>403],
				['fee_type_name'=>'应收代理团费款','fee_type_type'=>6002,'fee_type_code'=>404],

				
				
				
		];
		
		
		if(!empty($params['fee_type_code'])){
			$result = $array[$params['fee_type_code']-1];
		}
		return $result;
		
	}
	//排队 TC OC
	public static function getLineupPrefix($linep_type){
		if($linep_type==1){
			return 'OC';
		}else{
			return 'TC';
		}
	}
	
	/**
	 * 二维数组根据某字段进行排序
	 * sort 1升序 2倒序
	 */
	public function arraySort($array,$string,$sort=1){
		if($sort ==1){
			$sort_sting='SORT_ASC';
		}else{
			$sort_sting='SORT_DESC';
		}
		
		return array_multisort($array,$sort_sting,array_column($array,$string));
	}


    /**
     * 替换数组中的值
     * Created by PhpStorm.
     * User: yyy
     * Date: 2019/4/23
     * Time: 13:59
     * @param array $arr  数组
     * @param mixed $from 要替换的值
     * @param mixed $to  替换后的值
     * @return array $arr 替换后的数组
     *
     */
    public static function replaceNull(&$arr, $from, $to)
    {
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                self::replaceNull($arr[$key], $from, $to);
            }
            else
            {
                $val === $from && $arr[$key] = $to;
            }
        }
        return $arr;
    }

    public static function sendOperationsEmail($params){
    	
    	$mail_SMTPSecure = config('send_mail')['mail_SMTPSecure'];
    	$mail_port = config('send_mail')['mail_port'];

     
        Vendor('phpmailer.phpmailer');
         
        $mail = new \PHPMailer(); //实例化
        $mail->IsSMTP();
        // 设置邮件的字符编码，若不指定，则为'UTF-8'
        $mail->CharSet='UTF-8';
        // 添加收件人地址，可以多次使用来添加多个收件人
        $mail->AddAddress($params['to_email']);
        // 设置邮件正文
        $mail->Body=$params['content'];
        // 设置邮件头的From字段。
        $mail->From= $params['from_email'] ? $params['from_email'] : 'system@nexusholidays.com';
        // 设置发件人名字
        $mail->FromName='联谊假期';
        // 设置邮件标题
        $mail->Subject=$params['subject'];
        //设置附件
        foreach($params['email_attachment'] as $v){
            $mail->addAttachment($v);
        }
         
        // 设置SMTP服务器。
        $mail->Host='secure.emailsrvr.com';  
        // $mail->Host='smtp.gmail.com';  
       // $mail->Host='smtp.office365.com';
 
        // 设置为“需要验证”
        $mail->SMTPAuth=true; 
        	
        $mail->SMTPSecure ='ssl';
        $mail->Port = 465;
         
        
        // 设置用户名和密码。
        $mail->Username='system@nexusholidays.com';
        $mail->Password='NeXus_20!9';

        // $mail->Username='nexus.jiye@gmail.com';
        // $mail->Password='Aa123456!@#';

        // $mail->Username='nexus.us@outlook.com';
        // $mail->Password='Aa123456!@#';

        // 发送邮件。
        if($mail->Send()){
            return 1;
        }else{
            return $mail->ErrorInfo;
        }

    }

    public static function getSupplierTypeNameById($supplier_type_id)
    {
        $array = [
            '2'=>'酒店',
            '3'=>'用餐',
            '4'=>'航班',
            '5'=>'邮轮',
            '6'=>'签证',
            '7'=>'景点',
            '8'=>'车辆',
            '9'=>'导游',
            '10'=>'单项资源',
            '11'=>'自费项目',
            '12'=>'其他'
        ];
        return $array[$supplier_type_id];
    }

    /**
     * 获取uuid
     */
    public static function getUuid()
    {
        return date("YmdHis").md5(uniqid(md5(microtime(true)),true));
    }
	/**
	 * 获取6位随机数包含大小写数字
	 */
    public static function  getRandomNumber($length){
    	
    	//生成一个包含 大写英文字母, 小写英文字母, 数字 的数组    	
    	$shuzi = array_merge(range(0, 9));
    	$xiaoxie = array_merge(range('a', 'z'));
    	$daxie = array_merge(range('A', 'Z'));
    	$str = $xiaoxie[rand(0,25)].$xiaoxie[rand(0,25)].$daxie[rand(0,25)].$daxie[rand(0,25)].$shuzi[rand(0,9)].$shuzi[rand(0,9)];    	
    	
    	return $str;
    	
    	
    }
    
	/**
	 * 获取邮箱文案
	 */
    public static function getEmailContent($type){
    	if($type == 1){
			$content= '您的验证码为/you code is';		
    	}
    	
    	return $content;
    	
    }
	
    
    //转换时区
    /**
     * 
     * @param unknown $date_time 请传2019-11-10 22:10:10 格式不要传时间戳
     * @param string $format 
     * @param string $to 转换后的时区格式 Europe/Rome
     * @param string $from 转换前的时区格式 Asia/Shanghai
     */
	public static function changeTimeZone($date_time, $format = 'Y-m-d H:i:s', $from,$to='Asia/Shanghai') {
    	$datetime = new DateTime($date_time, new DateTimeZone($from));
    	$datetime->setTimezone(new DateTimeZone($to));
    	return $datetime->format($format);
	}
}
