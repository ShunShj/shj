#PHP
    学习搭建PHP框架
#框架运行流程
    入口文件 -> 定义常量 -> 引入函数库 -> 自动加载类 -> 启动框架 <- 路由解析 <- 加载控制器 <- 返回结果
#入口文件
#自动加载类
    spl_autoload_register('\common\kfw::load');
#路由类
    默认的路由地址： xxx.com/index.php/index/index
    隐藏index.php
    在根目录添加.htaccess文件
    /index/index/id/1/str/2
#模型类
    medoo轻量级PHP数据库框架
    中文文档地址 https://medoo.lvtao.net/1.2/doc.php
    PDO支持   
#视图类
#配置类
#控制器
    添加common\lib\Controller基类
    添加beforeAction方法
    添加afterAction方法
#日志类
    文件
    数据库
#composer加载
    新建composer.json文件
    composer install
    composer update
