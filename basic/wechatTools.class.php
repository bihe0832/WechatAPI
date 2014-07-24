<?php
require_once (dirname(__FILE__).'/../wechat/wechatApi.php');
require_once (dirname(__FILE__).'/../conf/conf_conn.php');

class wechatTools {
	private $appid = "";
	private $secret ="";
	
	public function __construct($appid,$secret){
		$this->appid = $appid;
		$this->secret = $secret;
	}
	
	public function _getMenu(){
		$accessResult = wechatApi::__getAdminAccessToken($this->appid, $this->secret);
		if($accessResult["errcode"] == "40001"){
			$accessResult = wechatApi::__getNewAdminAccessToken($this->appid, $this->secret);
		}
		if($accessResult["access_token"]){
			return wechatApi::_menuGetMenu($accessResult["access_token"]);
		}else{
			return $accessResult;
		}
	}
	
	public function _delMenu(){
		$accessResult = wechatApi::__getAdminAccessToken($this->appid, $this->secret);
		if($accessResult["errcode"] == "40001"){
			$accessResult = wechatApi::__getNewAdminAccessToken($this->appid, $this->secret);
		}
		if($accessResult["access_token"]){
			return wechatApi::_menuDelMenu($accessResult["access_token"]);
		}else{
			return $accessResult;
		}
	}
	
	public function _createMenu($menu){
		$accessResult = wechatApi::__getAdminAccessToken($this->appid, $this->secret);
		if($accessResult["errcode"] == "40001"){
			$accessResult = wechatApi::__getNewAdminAccessToken($this->appid, $this->secret);
		}
		if($accessResult["access_token"]){
			return wechatApi::_menuCreateMenu($accessResult["access_token"],$menu);
		}else{
			return $accessResult;
		}
	} 
	
	public function _createQrcode( $scene_id, $expire_seconds=7200){
		$accessResult = wechatApi::__getAdminAccessToken($this->appid, $this->secret);
		if($accessResult["errcode"] == "40001"){
			$accessResult = wechatApi::__getNewAdminAccessToken($this->appid, $this->secret);
		}
		if($accessResult["access_token"]){
			return wechatApi::_qrcodeGetTicket($accessResult["access_token"], $scene_id, $expire_seconds);
		}else{
			return $accessResult;
		}
	}
	
	public function _showQrcode($ticket){
		if($ticket){
			return wechatApi::_qrcodeShowCodeImg($ticket);
		}else{
			return array("ecode"=>"-1","errmsg"=>"no ticket");
		}
	}
	
}
?>