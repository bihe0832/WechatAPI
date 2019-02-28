<?php
require_once (dirname(__FILE__).'/../../../model/basic/wechatAuth.class.php');
require_once (dirname(__FILE__).'/../conf/zixie.conf.php');

class zixieAuth extends wechatAuth {
	
	public function __construct(){
		parent::__construct(APP_ID,APP_KEY,REDIRECTURL);
	}
	
	public function startApp($state="",$openid=""){
		//根据state跳转到授权的页面
		switch($state){
			case 1:
				//游戏官网
				$result["url"] = "https://blog.bihe0832.com/";
				break;
			case 2:
				//游戏官网
				$result["url"] = "http://bihe0832.github.com/";
				break;
			default:
				$result["url"] = "http://game.bihe0832.com/";
				break;
		}
		return $result;
	}
	
}
?>