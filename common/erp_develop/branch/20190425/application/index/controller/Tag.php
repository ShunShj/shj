<?php
/**
 * Created by PhpStorm.
 * User: shj
 * Date: 2019/3/26
 * Time: 15:39
 */

namespace app\index\controller;

class Tag extends Base
{
    /**
     * showTagManage
     *
     * 标签管理显示页面
     * @author shj
     * @return void
     * Date: 2019/3/26
     * Time: 15:55
     */
    public function showTagManage(){

        return $this->fetch('tag_manage');
    }
}