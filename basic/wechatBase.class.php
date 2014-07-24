<?php
require_once (dirname(__FILE__).'/../conf/conf_conn.php');
require_once (dirname(__FILE__).'/debug.php');

class wechatBase{
	//log配置
	protected $_log_conf = Array();
	//数据库链接，系统数据库的链接配置
	public $_basicDBLink = "";
	//cache链接
	public $_memLink = "";
	
	//构造函数
	public function __construct()	{
		//链接DB
		$this->_basicDBLink = $this->__dbConnect($GLOBALS['db_config']);
		mysql_select_db($GLOBALS['db_config']['dbname'],$this->_basicDBLink);
		mysql_query("set names utf8",$this->_basicDBLink);
		debug(__FILE__,__LINE__,"__construct","Wekefu Data:DB is OK!");
		//提示信息配置
		$this->_log_conf =  $GLOBALS['log_config'];
		debug(__FILE__,__LINE__,"__construct","Wekefu Data:LOG is OK!");
		//cache配置
		if($GLOBALS['isMemcache']){
			$this->_memLink = new Memcache;
			$this->_memLink->connect($GLOBALS['mem_config']['host'], $GLOBALS['mem_config']['port']);
			debug(__FILE__,__LINE__,"__construct","Wekefu Data:Cache is OK!");
		}
	}
	
	//入口函数，子类需要覆写
	public function startModel(){

	}
	
	//将用户输入数字转化为中文，字母转化为小写
	public function __preReplace($str){
		$numArr = array('/0/','/1/','/2/','/3/','/4/','/5/','/6/','/7/','/8/','/9/');
		$wordArr = array("零","一","二","三","四","五","六","七","八","九");
		$str = preg_replace($numArr,$wordArr,$str);
		return strtolower($str);
	}
	
	//根据SQL读cache,如果cache没有读DB,并把结果写cache.
	public function __getInfoFromCacheBySQL($sql,$dbLink="",$time=300){
		$result = "";
		if($this->_memLink){
			$tempResult = $this->_memLink->get(md5($sql));
		}
		if($tempResult){
			$result = $tempResult;
		}else{
			$dbLink = $dbLink ? $dbLink : $this->_basicDBLink;
			$dbResult = mysql_query($sql,$dbLink);
			$result = mysql_fetch_assoc($dbResult);
			if($this->_memLink){
				$this->_memLink->set(md5($sql),$result,0,$time);
			}
		}
		return $result;
	}
	
	//彻底清空缓存
	public function __flushCache(){
		if($this->_memLink->flush()){
			return "Finished, Good Job!";
		}else{
			return "Error, bad Job!";
		}
	}
	
	//根据用户wechatID 和cache type删除对应cache,其余cache保留的时间
	public function __delCacheByWechatID($userID,$type="",$time=0){
		if($type && $type != ""){
			$userCacahe = $this->_memLink->get($userID);
			if(is_array($userCacahe)){
				unset($userCacahe[$type]);
			}
			$time = $time > 1 ? $time :60;
			$this->_memLink->set($userID,$userCacahe,0,$time);
		}else{
			$this->_memLink->delete($userID,0);
		}
	}
	
	//根据key删除对应cache
	public function __delCacheByKey($key){
		return $this->_memLink->delete($key,0);
	}
	
	//首次关注,增加来源
	public function __subscribe($userID,$appCode){
		$tips = "";
		$sql = "select `id`,`wechat_id` from `t_user` where `wechat_id` = '$userID'";
		$dbResult = mysql_query($sql,$this->_basicDBLink);
		$result = mysql_fetch_assoc($dbResult);
		if($result){
			//update
			$sql = "update `t_user` set `status` = 1, `last_update`=".time()." where `wechat_id` = '$userID'";
			$dbResult = mysql_query($sql,$this->_basicDBLink);
			$this->__addLog($userID,'SUBSCRIBE_BACK',$appCode);
			if($result["id"] % 10 == 1){
				$tips =  $this->__getTips('SUBSCRIBE_BACK',$appCode)."\nPS:您是第".$result["id"]."位关注者哟！\n";
			}else{
				$tips =  $this->__getTips('SUBSCRIBE_BACK',$appCode);
			}
		}else{
			//insert
			$sql = "insert into `t_user` (`wechat_id`,`subscribe`, `last_update`,`status`) values('$userID',".time().",".time().",1)";
			$dbResult = mysql_query($sql,$this->_basicDBLink);
			$id = mysql_insert_id();
			$this->__addLog($userID,'SUBSCRIBE',$appCode);
			if($id % 10 == 1){
				$tips = $this->__getTips('SUBSCRIBE',$appCode)."\nPS:您是第".$id."位关注者哟！\n";
			}else{
				$tips = $this->__getTips('SUBSCRIBE',$appCode);
			}
		}
		return $tips;
	}
	
	//取消关注
	public function __unsubscribe($userID,$appCode){
		$sql = "update `t_players` set `last_update`=".time().", `status` = 2 where `wechat_id` = '$userID'";
		$dbResult = mysql_query($sql,$this->_basicDBLink);
		$this->__addLog($userID,'UNSUBSCRIBE',$appCode);
		return $this->__getTips('UNSUBSCRIBE',$appCode);
	}
	
	//日志记录，目前是记录在数据库，或者直接写本地log
	public function __addLog($userID,$type,$appCode=""){
		$typeId = $this->_log_conf[$type];
		$appName = $appCode ? $appCode : "basic";
		$date = date("Y-m-d");
		$time = time();
		$content = "$appName\t$typeId\t$userID\t$time\t$date\n";
		LL_TLOG($content);
	}
	
	//根据appcode的错误时返回的tips
	public function __getTips($type,$appCode=""){
		$appCode = $appCode ? $appCode : "basic";
		$typeId = $this->_log_conf[$type];
		debug(__FILE__,__LINE__,"__getTips","type:".$type);
		if($appCode != ""){
			$sql = "select `tips_info` from t_".$appCode."tips where `tips_type` = '".$typeId."' and status = 1";
			$dbResult = mysql_query($sql,$this->_basicDBLink);
			$row = mysql_num_rows($dbResult);
			if($row > 0){
				$result = $this->__getRandInfoBySQLResult($dbResult);
				return $result["tips_info"] ? $result["tips_info"] : "努力学技术，潜心做精品！";
			}
		}
		$sql = "select `tips_info` from t_basictips where `tips_type` = '".$typeId."' and status = 1";
		$dbResult = mysql_query($sql,$this->_basicDBLink);
		$result = $this->__getRandInfoBySQLResult($dbResult);
		return $result["tips_info"] ? $result["tips_info"] :"努力学技术，潜心做精品！";
	}
	
	//根据mysql的查询结果，随机返回结果集里面的一条记录
	public function __getRandInfoBySQLResult($dbResult){
		$num = rand(1,mysql_num_rows($dbResult));
		$result = "";
		$id =1;
		while($tempResult = mysql_fetch_assoc($dbResult)){
			if($id==$num){
				$result = $tempResult;
				break;
			}
			$id++;
		}
		return $result;
	}
	
	//用户帮助
	public function __getHelp(){
		$this->__addLog('ozfxduHtxvts4Q8waTBgeq4y1d20','HELP');
		return $this->__getTips('HELP');
	}
	
	//特殊输入
	public function __basicRequestType($command){
		switch($command){
			case "HELP":
				return $this->__getHelp();
				break;
			case "CLEAR":
				return $this->__flushCache();
				break;
			default :
				return $this->__getHelp();
				break;
		}
	}
	//合并命令行之间的多个空格为一个
	public function __merge_spaces($string){
		return preg_replace("/\s(?=\s)/","\\1",trim($string));
	}
	
	public function __dbConnect($db_config){
		$link ="";
		if(in_array("port", array_keys($db_config)) && $db_config['port'] !=""){
			$link = mysql_connect($db_config['host'].":".$db_config['port'], $db_config['user'], $db_config['passwd']);
		}else{
			$link = mysql_connect($db_config['host'], $db_config['user'], $db_config['passwd']);
		}
		mysql_select_db($db_config['dbname'], $link);
		mysql_query("set names utf8");
		return $link;
	}
}
?>