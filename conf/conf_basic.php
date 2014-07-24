<?php 
//是否开启debug模式
define("ZIXIE_DEBUG", false);
//是否开启memcache模式
define("ONLINE", false);
//微信公共帐号token
define("TOKEN", "zixie");
//是否开启memcache模式
define("MEMACHE", false);
//memcahce的相关配置
if(MEMACHE){
	if(ONLINE){
		$mem_config['host'] = 'microdemo.sinaapp.com';
		$mem_config['port'] = '11211';
	}else{
		$mem_config['host'] = '127.0.0.1';
		$mem_config['port'] = '11211';
	}
}

?>