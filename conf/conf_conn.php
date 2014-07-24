<?php
ini_set('display_errors', false);
error_reporting(0);
$isSAE = false;
$isMemcache = false;
$debug = false;
$db_config= array();
if($isSAE){
	$db_config['host'] = SAE_MYSQL_HOST_M;
	$db_config['port'] = SAE_MYSQL_PORT;
	$db_config['user'] = SAE_MYSQL_USER;
	$db_config['passwd'] = SAE_MYSQL_PASS;
	$db_config['dbname'] = SAE_MYSQL_DB;
}else{
	$db_config['host'] = 'localhost';
	$db_config['user'] = 'root';
	$db_config['passwd'] = '123';
	$db_config['dbname'] = 'db_wechat';
}

$mem_config = array();
if($isMemcache){
	if($isSAE){
	
	}else{
		$mem_config['host'] = '127.0.0.1';
		$mem_config['port'] = '11211';
	}
}

$log_config =  array();
$log_config['HELP'] = 1;//1.帮助
$log_config['ADVICE'] = 2;//2.用户反馈
$log_config['VOC'] = 3;//3.输入语音
$log_config['IMG'] = 4;//4.发送图片
$log_config['LOC'] = 5;//5.发送位置
$log_config['SUBSCRIBE'] = 10;//10.用户关注
$log_config['UNSUBSCRIBE'] = 11;//11.用户取消关注
$log_config['SUBSCRIBE_BACK'] = 12;//12.用户再次关注
?>