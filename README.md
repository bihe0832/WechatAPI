wechatAPI
=========

支持多微信公共账号同时接入的微信公共账号开发框架

###代码结构：
     — app：具体微信公共账号业务逻辑层
     	- app.do.php：公共账号逻辑处理的基类
     	- app.test.conf.php：对于appDo的测试用例
     	- app.test.php：对于appDo的测试入口地址
     	- zixie：具体公共账号demo（zixie）相关的代码，每一个公共账号相关的所有内容都在这里
     		- conf：
     			zixie.conf.php：具体公共账号demo（zixie）的基本配置
     		- main：
     			zixie.do.php：具体公共账号demo（zixie）的业务逻辑处理类
     			zixie.test.conf.php：对于zixieDo的测试用例
     			zixie.test.php：zixieDo测试入口地址
     		- tools：
     			zixie.auth.do.php：具体公共账号demo（zixie）网页授权处理类
     			zixie.auth.php：具体公共账号demo（zixie）网页授权跳转地址
     			zixie.tools.php：具体公共账号demo（zixie）工具类，负责菜单，临时二维码生成等
     		- zixie.php：具体公共账号demo（zixie）的公共平台入口地址，配置在微信公共平台
     	……
     - basic：
     	- debug.php：日志记录类
     	- wechatAuth.class.php：微信公共账号网页授权基础类
     	- wechatAuth.getData.php：微信公共账号网页授权测试，获取code等数据页面
     	- wechatAuth.test.php：微信公共账号网页授权基础类测试代码
     	- wechatBase.class.php：框架基础类，完成通用函数、框架配置；关注、取消关注等基础功能
     	- wechatBase.test.php：框架基础类测试代码
     	- wechatTools.class.php：微信公共账号工具类，完成菜单、二维码相关功能
     	- wechatTools.do.php：微信公共账号工具类接口封装类
     	- wechatTools.test.php：微信公共账号工具类测试代码
     - conf：
     	- conf_basic.php：框架与环境相关的基本配置
     	- conf_conn.php：框架与DB、cache、通用错误码相关的配置
     - data：
     	- data.sql：框架依赖的一些数据表，记录用户信息、授权信息、提示消息等
     - log：系统日志文件夹
     - wechat：
     	- wechatApi.php：框架对微信所有接口的封装，主要分为六个部分
     	- wx_sample.php：

     
