<?php
/**
 * Created by PhpStorm.
 * User: Hugh
 * Date: 2019/11/04
 * Time: 13:40
 */

namespace app\index\controller;
use \Underscore\Types\Arrays;
use think\Session;
use think\Paginator;
use think\Request;
use think\Controller;
use app\common\help\Help;

class BookNews extends Base
{
    public function showNewsManage(){
        $data = [
            'page'=>$this->page(),
            'page_size'=>$this->_page_size,
        ];
        $title = input("title");
        $status = input("status");

        $data['title'] = $title;
        $data['status'] = $status;
        $data['company_id'] =  session('user')['company_id'];

        $result = $this->callSoaErp('post', '/btob/getNews',$data);

        $this->getPageParams($result);

        return $this->fetch('news_manage');
    }

    public function showNewsAdd(){
        return $this->fetch('news_add');
    }

    public function addNewsAjax(){
        $post = Request::instance()->param();
        $title = input("title");
        $cn_title = input("cn_title");
        $sub_title = input("sub_title");
        $date = input("date");
        $content = input("content");
        $status = input("status");
        $user_id = session('user')['user_id'];
        $company_id = input("choose_company_id") ? : session('user')['company_id'];
        $data = [
            "title"=>$title,
            "cn_title"=>$cn_title,
            "sub_title"=>$sub_title,
            "date"=>$date,
            "images"=>implode(',',$post['imgs']),
            "content"=>$content,
            "status"=>$status,
            "user_id"=>$user_id,
            "choose_company_id"=>$company_id,
        ];

        $result = $this->callSoaErp('post', '/btob/addNews',$data);
        return   $result;
    }

    public function showNewsEdit(){

        $news_id = input("news_id");

        $data = ["news_id"=>$news_id];
        $result = $this->callSoaErp('post', '/btob/getOneNews',$data);

        $result['data']['images'] = $result['data']['images'] ? explode(',',$result['data']['images']) : 1;

        $this->assign('result',$result['data']);


        return $this->fetch('news_edit');
    }


    public function editNewsAjax(){
        $post = Request::instance()->param();
        $news_id = input("news_id");
        $title = input("title");
        $cn_title = input("cn_title");
        $sub_title = input("sub_title");
        $date = input("date");
        $content = input("content");
        $status = input("status");
        $user_id = session('user')['user_id'];
        $company_id = input("choose_company_id") ? : session('user')['company_id'];
        $data = [
            "news_id"=>$news_id,
            "title"=>$title,
            "cn_title"=>$cn_title,
            "sub_title"=>$sub_title,
            "date"=>$date,
            "images"=>implode(',',$post['imgs']),
            "content"=>$content,
            "status"=> $status,
            "user_id" => $user_id,
            "choose_company_id" => $company_id,
        ];

        $result = $this->callSoaErp('post', '/btob/updateNewsByNewsId',$data);
        return   $result;
    }

}