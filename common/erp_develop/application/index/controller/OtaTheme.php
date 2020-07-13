<?php
/**
 * Created by PhpStorm.
 * User: jiye
 * Date: 2018/8/13
 * Time: 9:24
 */
namespace app\index\controller;

use app\common\help\Help;
use think\helper\Arr;
use \Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;

/***
 * 主题-页面
 * Hugh
 **/
class OtaTheme extends Base
{

    public function subgroup()
    {
        $sub = $this->callSoaErp('post','/ota_theme/getSubassembly',['status'=>1]);
        $ky = [];
        foreach($sub['data'] as $v){
            $ky[$v['subassembly_id']] = $v['name'];
        }

        $this->assign('subgroup',$ky);

        //获取旅游产品
        $getProductLists = $this->getProductLists();
        $this->assign('getProductLists',$getProductLists['data']);
        //获取旅游产品分类
        $getProductTypes = $this->getProductTypes();
        $this->assign('getProductTypes',$getProductTypes['data']);
        //幻灯片
        $getSlide = $this->getSlide();
        $this->assign('getSlide',$getSlide['data']);
        //文章
        $getArticle = $this->getArticle();
        $this->assign('getArticle',$getArticle['data']);
        //文章分类
        $getArticleType = $this->getArticleType();
        $this->assign('getArticleType',$getArticleType['data']);
        //广告位
        $getAdvertising = $this->getAdvertising();
        $this->assign('getAdvertising',$getAdvertising['data']);
        //菜单
        $getOtaMenu = $this->getOtaMenu();
        $this->assign('getOtaMenu',$getOtaMenu['data']);

        //友情链接
        $getfriendlyLink = $this->getfriendlyLink();
//        var_dump($getfriendlyLink);exit;
        $this->assign('getfriendlyLink',$getfriendlyLink['data']);

    }

    public function page_index()
    {
        //获取网站主题
        $w['website_uuid'] = session('website_uuid'); //网站设置ID
        $getSiteThemes = $this->callSoaErp('post','/ota_theme/getSiteThemes',$w);
        $this->assign('getSiteThemes',$getSiteThemes['data']);
        unset($w);

        //获取主题
        $w['status'] = 1;
        $getTheme = $this->callSoaErp('post','/ota_theme/getTheme',$w);
        $this->assign('Theme',$getTheme['data']);
        echo $this->fetch("index");
    }

    //修改网站主题
    public function up_web_theme(){
        $post = Request::instance()->param();

        $ota_site_theme_id = Arrays::get($post,'ota_site_theme_id');
        $ota_theme_id = Arrays::get($post,'ota_theme_id');

        if($ota_site_theme_id){
            $u['ota_site_theme_id'] = $ota_site_theme_id;
            $u['ota_theme_id'] = $ota_theme_id;
            $this->callSoaErp('post','/ota_theme/upSiteThemes',$u);
            echo 1;
        }else{
            $ad['ota_theme_id'] = $ota_theme_id;
            $ad['website_uuid'] = session('website_uuid'); //网站设置ID;
            $r = $this->callSoaErp('post','/ota_theme/addSiteThemes',$ad);
            echo $r['data'];
        }
    }

    //主题详情
    public function theme_info(){
        $w['ota_theme_id'] = input('get.ota_theme_id');
        $getTheme = $this->callSoaErp('post','/ota_theme/getTheme',$w);
        $this->assign('Theme',$getTheme['data'][0]);
        echo $this->fetch('theme_info');
    }

    //页面列表
    public function page_list(){

        //获取网站主题
        $w['website_uuid'] = session('website_uuid'); //网站设置ID
        $getSiteThemes = $this->callSoaErp('post','/ota_theme/getSiteThemes',$w);
        $this->assign('getSiteThemes',$getSiteThemes['data']);
        unset($w);
//        var_dump($getSiteThemes);exit;

        $w['ota_theme_id'] = $getSiteThemes['data']['ota_theme_id'];
        $w['website_uuid'] = session('website_uuid'); //网站设置ID;

        if($_GET['s_status']){
            $w['status'] = $_GET['s_status'];
        }
        if($_GET['s_title']){
            $w['title'] = $_GET['s_title'];
        }


        $page = $this->callSoaErp('post','/ota_theme/getPage',$w);
//        var_dump($page);
        $this->assign('page',$page['data']);
        echo $this->fetch('page_list');
    }

    //新建页面
    public function new_built_page(){
        $w['ota_theme_id'] = input('get.ota_theme_id'); //主题ID
        $getThemeModule = $this->callSoaErp('post','/ota_theme/getThemeModule',$w);
//        var_dump($getThemeModule);
        $this->assign('getThemeModule',$getThemeModule['data']);

        echo $this->fetch('new_built_page');

    }

    //异步添加页面数据
    public function newBuiltPageAjax(){
        $post = Request::instance()->param();
//        error_log(print_r($post,1));exit;

        $data = [];
        $data['ota_theme_module_id'] = Arrays::get($post,'ota_theme_module_id'); //主题模块
        $data['ota_theme_id'] = Arrays::get($post,'ota_theme_id'); //主题ID
        $data['website_uuid'] = session('website_uuid'); //网站设置ID
        $data['page_status'] = Arrays::get($post,'page_status');
        $data['page_title'] = Arrays::get($post,'page_title');
        $data['pv'] = Arrays::get($post,'pv');
        $data['sort'] = Arrays::get($post,'sort');
        $data['user_id'] = session('user')['user_id'];

        $subassemblyId_ar = Arrays::get($post,'subassemblyId');
        $shujuId_ar = Arrays::get($post,'shujuId');
        $css = Arrays::get($post,'css');

        foreach($subassemblyId_ar as $k=>$v){
            $ary['subassemblyId'] = $v;
            $ary['shuju'] = $shujuId_ar[$k]?:'';
            $ary['position_n'] = $k;
            $ary['css_id'] = $css[$k]?:0;
            $data['d'][] = $ary;
            unset($ary);
        }

        return  $this->callSoaErp('post','/ota_theme/newBuiltPageAjax',$data);

    }

    //修改页面
    public function update_built_page(){
        $ota_page_id = input('get.ota_page_id');
        $d = $this->callSoaErp('post','/ota_theme/getBuiltPage',['ota_page_id'=>$ota_page_id]);
        $this->assign('data',$d['data']);
//        error_log(print_r($d['data'],1));

        if($d['data']['ota_components_under_the_page']){
            $ota_components_under_the_page = Arrays::group($d['data']['ota_components_under_the_page'],'position_n');
//            error_log(print_r($ota_components_under_the_page,1));
            $this->assign('ota_components_under_the_page',$ota_components_under_the_page);
        }
        $w['ota_theme_id'] = $d['data']['ota_page']['ota_theme_id'] ; //主题ID
        $getThemeModule = $this->callSoaErp('post','/ota_theme/getThemeModule',$w);
//        var_dump($getThemeModule);
        $this->assign('getThemeModule',$getThemeModule['data']);


        //获取组件样式
        $getOtaCss = $this->callSoaErp('post','/ota_theme/getOtaCss',$w);
        $this->assign('OtaCss',Arrays::group($getOtaCss['data'],'subassembly_id'));


        $this->subgroup();
        echo $this->fetch('update_built_page');
    }

    //异步修改页面数据
    public function updateBuiltPageAjax(){
        $post = Request::instance()->param();

        $data = [];
        $data['ota_page_id'] = Arrays::get($post,'ota_page_id'); //页面配置ID
        $data['ota_theme_module_id'] = Arrays::get($post,'ota_theme_module_id'); //主题模块
        $data['ota_theme_id'] = Arrays::get($post,'ota_theme_id'); //主题ID
        $data['website_uuid'] = session('website_uuid'); //网站设置ID
        $data['page_status'] = Arrays::get($post,'page_status');
        $data['page_title'] = Arrays::get($post,'page_title');
        $data['pv'] = Arrays::get($post,'pv');
        $data['sort'] = Arrays::get($post,'sort');
        $data['user_id'] = session('user')['user_id'];

        $subassemblyId_ar = Arrays::get($post,'subassemblyId');
        $shujuId_ar = Arrays::get($post,'shujuId');
        $css = Arrays::get($post,'css');

        $ota_components_under_the_page_id_ar = Arrays::get($post,'ota_components_under_the_page_id');
        foreach($subassemblyId_ar as $k=>$v){
            $ary['ota_components_under_the_page_id'] = $ota_components_under_the_page_id_ar[$k];
            $ary['subassemblyId'] = $v;
            $ary['shuju'] = $shujuId_ar[$k]?:'';
            $ary['position_n'] = $k;
            $ary['css_id'] = $css[$k]?:0;
            $data['d'][] = $ary;
            unset($ary);
        }

//        var_dump($data);
        return  $this->callSoaErp('post','/ota_theme/updateBuiltPageAjax',$data);
    }


    //获取页面模块组件
    public function getPageModuleComponent(){
        $w['ota_theme_module_id'] = input('post.ota_theme_module_id');
        if($w['ota_theme_module_id']){
            $getThemeModule = $this->callSoaErp('post','/ota_theme/getThemeModule',$w);
            $this->assign('getThemeModule',$getThemeModule['data'][0]);
        }

//        var_dump($getThemeModule['data']);exit;
        //获取主题页面组件
        $w['status'] = 1;
        $getTopicPageModule = $this->callSoaErp('post','/ota_theme/getTopicPageModule',$w);
//        var_dump($getTopicPageModule);eixt;
        $this->assign('TopicPageModule',Arrays::group($getTopicPageModule['data'],'position_n'));

        //获取页面组件样式
        $getOtaCss = $this->callSoaErp('post','/ota_theme/getOtaCss',$w);

        $this->assign('OtaCss',Arrays::group($getOtaCss['data'],'subassembly_id'));

//        var_dump(Arrays::group($getOtaCss['data'],'subassembly_id'));exit;

        $this->subgroup();
        echo $this->fetch('page_module_component');
    }

    //获取组件数据   [1=>'旅游产品',2=>'旅游产品分类',3=>'幻灯片',4=>'文章',5=>'文章分类',6=>'广告位',7=>'自定义代码',8=>'菜单'];
    public function getSubassemblyData(){
        $subassemblyId = input('post.subassemblyId');
        switch($subassemblyId){
            case 1: return $this->getProductLists(); break;
            case 2: return $this->getProductTypes(); break;
            case 3: return $this->getSlide(); break;
            case 4: return $this->getArticle(); break;
            case 5: return $this->getArticleType(); break;
            case 6: return $this->getAdvertising(); break;
            case 7:  break;
            case 8: return $this->getOtaMenu(); break;
            case 9: return $this->getfriendlyLink();break;
        }
    }

    //获取友情链接
    public function getfriendlyLink(){
        $where['website_uuid'] = session('website_uuid');
        $where['status'] = 1;
        return  $result = $this->callSoaErp('post','/ota_slide/getOtaAdvertListList',$where);
    }

    //获取旅游产品
    public function getProductLists(){
        $where['website_uuid'] = session('website_uuid');
        $where['status'] = 1;
        $ProductLists = $this->callSoaErp('post','/ota_product/getProducts',$where);
       //error_log(print_r($ProductLists,1));
       return  $ProductLists;
    }

    //获取旅游产品分类
    public function getProductTypes(){
        $where['website_uuid'] = session('website_uuid');
        $where['status'] = 1;
        $ProductTypes = $this->callSoaErp('post','/ota_product/getProductTypes',$where);
        //error_log(print_r($ProductTypes,1));
        return $ProductTypes;
    }

    //获取幻灯片
    public function getSlide(){
        $post['choose_company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $post['status'] = 1;
        $result = $this->callSoaErp('post','/ota_slide/getOtaSlideListList',$post);
//        error_log(print_r($result,1));
        return $result;
    }

    //获取文章
    public function getArticle(){
        $post['choose_company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $post['status'] = 1;
        $result = $this->callSoaErp('post','/ota_article/getArticleList',$post);
        return $result;
    }

    //获取文章分类
    public function getArticleType(){
        $post['choose_company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $post['status'] = 1;
        $result = $this->callSoaErp('post','/ota_article/getArticleTypeList',$post);
       
        return $result;
    }

    //获取广告位
    public function getAdvertising(){
        $post['choose_company_id'] = session('user')['company_id'];
        $post['website_uuid'] = session('website_uuid');
        $post['status'] = 1;
        $result = $this->callSoaErp('post','/ota_slide/getOtaAdvertisingList',$post);
        return $result;
    }

    //获取菜单
    public function getOtaMenu(){
        $post['website_uuid'] = session('website_uuid');
        $post['status'] = 1;
        $result = $this->callSoaErp('post','/ota_menu/getOtaMenuListList',$post);
        return $result;
    }


    



}