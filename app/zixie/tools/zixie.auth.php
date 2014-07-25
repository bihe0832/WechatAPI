<?php
require_once (dirname(__FILE__).'/zixie.auth.do.php');
require_once (dirname(__FILE__).'/../conf/zixie.conf.php');
//所有网页授权的redict_uri都写这个
$zixieAuthObj = new zixieAuth(APP_ID,APP_KEY,REDIRECTURL);
//标识到哪个页面
$state = htmlspecialchars($_REQUEST["state"]);
if($state < 0 || $state >2){
	//不合法地址，跳转到1
	$url = $zixieAuthObj -> _getAuthorizeLink(1,1);
	echo "<script type=\"text/javascript\">window.location=\"".$url."\"</script>";
}
$code = htmlspecialchars($_REQUEST["code"]);
$openid = htmlspecialchars($_REQUEST["openid"]);
//第一步授权回调
if($code && $code != "authdeny"){
	//获取token和openID
	$result = $zixieAuthObj -> _getAccessTokenByCode($code);
	if($result["openid"]){
		//根据state跳转页面
		$result = $zixieAuthObj -> startApp($state,$result["openid"]);
		if($result["ecode"] > 0){
			if($result["url"]){
				echo "<script type=\"text/javascript\">window.location=\"".$result["url"]."\"</script>";
				exit;
			}else{
				//var_dump($result);
				exit;
			}
		}else{
			//玩家没有游戏
			echo "<script type=\"text/javascript\">alert('您尚未设置默认游戏，点击进入平台最新游戏。');window.location=\"".$result["url"]."\"</script>";
			exit;
		}
	}else{
		//解析失败原因，有可能为code无效，重新授权
		$url = $zixieAuthObj -> _getAuthorizeLink($state,2);
		//echo '为了更好的为您提供服务，请点击链接完成用户授权。<a href="'.$url.'">'.$url.'</a>';
		echo "<script type=\"text/javascript\">window.location=\"".$url."\"</script>";
		exit;
	}
}

//非授权回调（cgi等），带参数的请求
if($openid != ""){
	//获取信息
	$result = $zixieAuthObj -> startApp($state,$openid);
	if($result["ecode"] > 0){
		if($result["url"]){
			echo "<script type=\"text/javascript\">window.location=\"".$result["url"]."\"</script>";
			exit;
		}else{
			//var_dump($result);
			exit;
		}
	}else{
		//其他问题，基本信息授权
		$url = $zixieAuthObj -> _getAuthorizeLink($state,2);
		echo "<script type=\"text/javascript\">window.location=\"".$url."\"</script>";
		exit;
	}
}else{
	if($code == "authdeny"){
		//授权失败
		$result = $zixieAuthObj -> startApp();
		echo "<script type=\"text/javascript\">window.location=\"".$result["url"]."\"</script>";
		exit;
	}else{
		//其他问题，例如没有拿到openid,授权获取
		$url = $zixieAuthObj -> _getAuthorizeLink($state,1);
		//echo '2为了更好的为您提供服务，请点击链接完成用户授权。<a href="'.$url.'">'.$url.'</a>';
		echo "<script type=\"text/javascript\">window.location=\"".$url."\"</script>";
		exit;
	}
}
exit;
?>