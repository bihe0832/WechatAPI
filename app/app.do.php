<?php
require_once (dirname(__FILE__).'/../basic/wechatBase.class.php');

class appDo extends wechatBase {
	public $appCode ="";
	public $userID = "";
	public function __construct($appCode)	{
		parent::__construct();
		$this->appCode = $appCode;
	}
	
	//程序主入口
	public function startApp($userInput){
		debug(__FILE__,__LINE__,"startApp","Wekefu Start:".$userInput);
		//解析玩家请求
		$userInputDecodeInfo =  json_decode($userInput, true);
		//获取到玩家的微信ID
		$this->userID = $userInputDecodeInfo["FromUserName"][0];
		//解析玩家请求，根据命令返回返回给用户的内容
		$command = $this->_checkCommand($userInputDecodeInfo);
		$result = $this->startModel($userInput,$command);
		$ResultMsg = array();
		if(is_array($result)){
			$ResultMsg = $result;
		}else{
			$ResultMsg ["MsgType"] = "text";
			$ResultMsg["Content"] = $result;
		}
		debug(__FILE__,__LINE__,"startApp","News Finished:".json_encode($ResultMsg));
		return $ResultMsg;
	}
	
	//建议子类重写这部分
	public function startModel($userInput,$command){
		return $command;
	}
	
	public function _checkCommand($userInputDecodeInfo){
		//判断用户输入类型
		if("text" != $userInputDecodeInfo["MsgType"]){
			//如果是事件，处理方法
			if("event" == $userInputDecodeInfo["MsgType"]){
				if("subscribe" == $userInputDecodeInfo["Event"]){
					//用户关注
					return "SUB";
				}else if("unsubscribe" == $userInputDecodeInfo["Event"]){
					//用户取消关注
					return "UNSUB";
				}else{
					//获取菜单的key值
					$eventKey = $userInputDecodeInfo["EventKey"];
					return substr($eventKey,5);
				}
			}else if("image" == $userInputDecodeInfo["MsgType"]){
				return "IMG";
			}else if("location" == $userInputDecodeInfo["MsgType"]){
				return "LOC";
			}else if("voice" == $userInputDecodeInfo["MsgType"]){
				if($userInputDecodeInfo["Recognition"]){
					//增加语音转文字接口
					return "VOCMAIN";
				}else{
					//语音
					return "VOC";
				}
			}else{
				return "MSG";
			}
		}else{
			$userCommand = explode(" ",$this->__merge_spaces($userInputDecodeInfo["Content"]));
			$command = strtoupper($userCommand[0]);
			//直接输入数字
			if($command > 0){
				return "MAIN";
			}else if($userCommand[0] == "投票" || $command == '投票'){
				//中文，投票
				return "TOUPIAO";
			}else if($userCommand[0] == "?" || $userCommand[0] == "？" || $command == '?' || $command == '？'){
				//游戏帮助
				return "HELP";
			}else if($userCommand[0] == "反馈" || $command == '反馈'){
				//中文，反馈
				return "ADVICE";
			}else if($userCommand[0] == "提问" || $command == '提问'){
				//中文，提问
				return "ASK";
			}else if($userCommand[0] == "人工" || $command == '人工'){
				//中文，人工服务
				return "HUMAN";
			}else{
				switch($command){
					case "A":
						return "A";
						break;
					case "B":
						return "B";
						break;
					case  "C":
						return "C";
						break;
					case "ZIXIE":
						return "CLEAR";
						break;
					default:
						return "MAIN";
						break;
				}
			}
		}
	}
}
?>