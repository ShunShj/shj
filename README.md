# PHP
学习搭建PHP框架
# 框架运行流程
入口文件 -> 定义常量 -> 引入函数库 -> 自动加载类 -> 启动框架 <- 路由解析 <- 加载控制器 <- 返回结果
# 入口文件
# 自动加载类
spl_autoload_register('\common\kfw::load');
# 路由类
1. 默认的路由地址： xxx.com/index.php/index/index
1. 隐藏index.php
1. 在根目录添加.htaccess文件
1. /index/index/id/1/str/2
# 模型类
1. medoo轻量级PHP数据库框架
1. 中文文档地址 https://medoo.lvtao.net/1.2/doc.php
1. PDO支持   
# 视图类
# 配置类
# 控制器
1. 添加common\lib\Controller基类
1. 添加beforeAction方法
1. 添加afterAction方法
# 日志类
1. 文件
1. 数据库
# composer加载
1. 新建composer.json文件
1. composer install
1. composer update
