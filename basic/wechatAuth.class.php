<?php
require_once (dirname(__FILE__).'/../wechat/wechatApi.php');
require_once (dirname(__FILE__).'/wechatBase.class.php');

class wechatAuth extends wechatBase{
	private $appid = "";
	private $secret ="";
	private $redirect_uri="";
	
	public function __construct($appid,$secret,$redirect_uri){
		parent::__construct();
		$this->appid = $appid;
		$this->secret = $secret;
		$this->redirect_uri = $redirect_uri;
	}
	
	public function _getAuthorizeLink($state,$scope_type){
		$scopeArr = array("1"=>"snsapi_base","2"=>"snsapi_userinfo");
		$link = wechatApi::_authGetAuthorizeLink($this->appid, $this->redirect_uri, $scopeArr["$scope_type"], $state);
		return $link;
	}
	
	public function _getAccessTokenByCode($code,$grant_type="authorization_code"){
		$result = wechatApi::_authGetAccessTokenByCode($this->appid,$this->secret,$code,$grant_type);
		$this->_recordAccessToken($result);
		return $result;
	}
	
	public function _refreshAccessToken($refresh_token,$grant_type="refresh_token"){
		return wechatApi::_authRefreshAccessToken($this->appid,$refresh_token,$grant_type);
	}
	
	public function _recordAccessToken($result){
		if($result["access_token"] && $result["refresh_token"] && $result["openid"]){
			$sql = "SELECT `id`, `wechat_id` FROM `t_authorize` WHERE `wechat_id` ='".$result["openid"]."'";
			$dbResult = mysql_query($sql,$this->_basicDBLink);
			$tempResult = mysql_fetch_assoc($dbResult);
			if($tempResult["wechat_id"]){
				$sql = "UPDATE `t_authorize` set `wechat_id`='".$result["openid"]."',`access_token`='".$result["access_token"]."',`refresh_token` ='".$result["refresh_token"]."';";
			}else{
				$sql = "INSERT INTO `t_authorize`( `wechat_id`, `access_token`, `refresh_token`) VALUES ('".$result["openid"]."','".$result["access_token"]."','".$result["refresh_token"]."');";
			}
			$dbResult = mysql_query($sql,$this->_basicDBLink);
			if($this->_memLink){
				$key = md5($result["openid"]."AUTHORIZE");
				$cacheAccessToken = array("access_token"=>$result["access_token"],"refresh_token"=>$result["refresh_token"]);
				$this->_memLink->set($key,$cacheAccessToken,0,120);
			}
			return true;
		}else{
			return false;
		}
	}
	
	
	public function _getUserInfoByopenID($openid){
		if($this->_memLink){
			$key = md5($openid."AUTHORIZE");
			$accessResult = $this->_memLink->get($key);
		}
		if(!$accessResult["access_token"]){
			//数据库读取openid对应的access token和refresh token
			$sql = "select wechat_id,access_token,refresh_token from t_authorize where wechat_id='".$openid."'";
			$dbResult = mysql_query($sql,$this->_basicDBLink);
			$accessResult = mysql_fetch_assoc($dbResult);
			if($accessResult["access_token"]){
				$cacheAccessToken = array("access_token"=>$accessResult["access_token"],"refresh_token"=>$accessResult["refresh_token"]);
				if($this->_memLink){
					$this->_memLink->set($key,$cacheAccessToken,0,120);
				}
			}
		}
		$userInfo = "";
		if($accessResult["access_token"]){
			$userInfo =  wechatApi::_authGetUserInfoByToken($accessResult["access_token"],$openid);
			//是否获得数据（token是否有效）
			if($userInfo["openid"]){
				return $userInfo;
			}else{
				//刷新token
				$result = $this->_refreshAccessToken($accessResult["refresh_token"]);
				if($result["access_token"]){
					$this->_recordAccessToken($result);
					$userInfo = wechatApi::_authGetUserInfoByToken($result["access_token"],$openid);
					return $userInfo;
				}else{
					//refresh_token过期，重新授权
					return array("ecode"=>"-1","errmsg"=>$result["ecode"]);
				}
			}
		}else{
			//尚未授权
			return array("ecode"=>"-2","errmsg"=>$result["ecode"]);
		}
		
	}
}
?>