<?php
require_once (dirname ( __FILE__ ) . '/../conf/conf_basic.php');

/**
 * @author bihe0832@foxmail.com
 */
class wechatApi {
	public static  $__sMemLink = "";
	
	/********************************************************
	 * *
	 * PART1：通过微信与用户交互接口（接收用手输入，返回处理结果）	*
	 * *
	 ********************************************************/
	
	/**
	 * 函数名：_clientGetUserInput
	 * 功   能：从微信服务器接口获取用户输入的内容
	 * 参   数：无
	 * 返回值：json格式的用户输入信息（不同消息类型不太一致，详细参考微信接口文档）
	 * 返回值事例：
		文本：
		{	
			"FromUserName":{"0":"o0YLXjimdKtg_arz413tEEi43b9w"},
			"ToUserName":{"0":"gh_f40e44d7f326"},
			"CreateTime":"1363071941",
			"MsgId":"5854349408690241807",
			"MsgType":"text",
			"Content":"111" 
		}
		图片：
		{
			"FromUserName":{"0":"o0YLXjimdKtg_arz413tEEi43b9w"},
			"ToUserName":{"0":"gh_f40e44d7f326"},
			"CreateTime":"1363189097",
			"MsgId":"5854852589878771997",
			"MsgType":"image",
			"PicUrl":"http:\/\/mmsns.qpic.cn\/mmsns\/hia8uVAOdBMYf71wzsz8tdbo7mhJ1yibM02hlhHPv0ibI4E9OPRAdbWJg\/0"
		}
		位置
		{
			"FromUserName":{"0":"o0YLXjimdKtg_arz413tEEi43b9w"},
			"ToUserName":{"0":"gh_f40e44d7f326"},
			"CreateTime":"1363189029",
			"MsgId":"5854852297820995867",
			"MsgType":"location",
			"Location_X":"22.547001",
			"Location_Y":"114.085945",
			"Label":"",
			"Scale":"20"
		}
		事件
		{
			"FromUserName":{"0":"o0YLXjimdKtg_arz413tEEi43b9w"},
			"ToUserName":{"0":"gh_f40e44d7f326"},
			"CreateTime":"1363189029",
			"MsgId":"5854852297820995867",
			"MsgType":"event",
			"Event":"subscribe",
			"EventKey":"114.085945",
		}
	 */
	public static function _clientGetUserInput() {
		$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
		if (! empty ( $postStr )) {
			$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
			//http://mp.weixin.qq.com/wiki/index.php
			$userInput = array ();
			//发送方帐号（一个OpenID）
			$userInput ["FromUserName"] = $postObj->FromUserName;
			//开发者微信号
			$userInput ["ToUserName"] = $postObj->ToUserName;
			//消息创建时间 （整型）
			$userInput ["CreateTime"] = trim ( $postObj->CreateTime );
			//消息id，64位整型
			$userInput ["MsgId"] = trim ( $postObj->MsgId );
			//消息类型：文本(text),图片(image),语音(voice),发送地理位置(location),上报地理位置(LOCATION),链接(link),事件(event)
			$userInput ["MsgType"] = trim ( $postObj->MsgType );
			if ($userInput ["MsgType"] == "text") {
				//文本消息内容
				$userInput ["Content"] = trim ( $postObj->Content );
			} else if ($userInput ["MsgType"] == "image") {
				//图片链接
				$userInput ["PicUrl"] = trim ( $postObj->PicUrl );
			} else if ($userInput ["MsgType"] == "voice") {
				//语音消息
				$userInput ["MediaId"] = trim ( $postObj->MediaId );
				$userInput ["Format"] = trim ( $postObj->Format );
				if ($postObj->Recognition) {
					$userInput ["Recognition"] = trim ( $postObj->Recognition );
				}
			} else if ($userInput ["MsgType"] == "location") {
				//地理位置维度
				$userInput ["Location_X"] = trim ( $postObj->Location_X );
				//地理位置经度
				$userInput ["Location_Y"] = trim ( $postObj->Location_Y );
				//地图缩放大小
				$userInput ["Label"] = trim ( $postObj->Label );
				//地理位置信息
				$userInput ["Scale"] = trim ( $postObj->Scale );
			} else if ($userInput ["MsgType"] == "link") {
				//消息标题
				$userInput ["Title"] = trim ( $postObj->Title );
				//消息描述
				$userInput ["Description"] = trim ( $postObj->Description );
				//消息链接
				$userInput ["Url"] = trim ( $postObj->Url );
			} else if ($userInput ["MsgType"] == "event") {
				//事件类型，有ENTER(进入会话)和LOCATION(地理位置)
				$userInput ["Event"] = trim ( $postObj->Event );
				$userInput ["EventKey"] = trim ( $postObj->EventKey );
				if ($userInput ["Event"] == "LOCATION") {
					//地理位置维度
					$userInput ["Latitude"] = trim ( $postObj->Latitude );
					//地理位置经度
					$userInput ["Longitude"] = trim ( $postObj->Longitude );
					//地理位置精度
					$userInput ["Precision"] = trim ( $postObj->Precision );
				} else if ($userInput ["Event"] == "scan") {
					$userInput ["Ticket"] = trim ( $postObj->Ticket );
				}
			}
			__DEBUG ( __FILE__, __LINE__, "Wechat Info", $userInput );
			return json_encode ( $userInput );
		} else {
			echo "Server error!";
			exit ();
		}
	}
	
	/**
	 * 函数名：_clientResponseMsg
	 * 功   能：将应用生成的文本信息发送到微信服务器发送给用户
	 * 参   数：
	 		resultMsg ：需要发送给用户的信息(数组)
	 * 返回值：无
	 */
	public static function _clientResponseMsg($resultMsg) {
		$resultMsg ["UserId"] = $resultMsg ["UserId"] ? $resultMsg ["UserId"] : $postObj->FromUserName;
		$resultMsg ["AccountId"] = $resultMsg ["AccountId"] ? $resultMsg ["AccountId"] : $postObj->ToUserName;
		if ($resultMsg ["MsgType"] == "music") {
			wechatApi::_clientResponseMusicMsg ( $resultMsg );
		} else if ($resultMsg ["MsgType"] == "news") {
			wechatApi::_clientResponseArticalMsg ( $resultMsg );
		} else {
			if (is_array ( $resultMsg )) {
				$result = $resultMsg;
			} else {
				$content = $resultMsg;
				$result ["MsgType"] = "text";
				$result ["Content"] = $content;
			}
			wechatApi::_clientResponseTextMsg ( $result );
		}
	}
	
	/**
	 * 函数名：_clientResponseTextMsg
	 * 功   能：将应用生成的文本信息发送到微信服务器发送给用户
	 * 参   数：
		resultMsg
			MsgType text
			Content 需要发送给用户的文本信息
	 * 返回值：无
	 */
	public static function _clientResponseTextMsg($resultMsg) {
		$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
		if (! empty ( $postStr )) {
			$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
			$resultMsg ["UserId"] = $resultMsg ["UserId"] ? $resultMsg ["UserId"] : $postObj->FromUserName;
			$resultMsg ["AccountId"] = $resultMsg ["AccountId"] ? $resultMsg ["AccountId"] : $postObj->ToUserName;
			$resultMsg ["Content"] = $resultMsg ["Content"] ? $resultMsg ["Content"] : "文本内容为空……";
			$time = time ();
			$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[text]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
						</xml>";
			$resultStr = sprintf ( $textTpl, $resultMsg ["UserId"], $resultMsg ["AccountId"], $time, $resultMsg ["Content"] );
			echo $resultStr;
			__DEBUG ( __FILE__, __LINE__, "Wechat Text", $resultStr );
			exit ();
		} else {
			echo "文本服务器响应错误……";
			__DEBUG ( __FILE__, __LINE__, "Wechat Error", "Text" );
			exit ();
		}
	}
	
	/**
	 * 函数名：_clientResponseMusicMsg
	 * 功   能：将应用生成的音乐信息发送到微信服务器发送给用户
	 * 参   数：
		resultMsg
			MsgType music
			name 音乐名称
		    description 音乐的简单描述，建议填写歌手
		    MusicUrl 音乐链接
		    HQMusicUrl 高清音乐链接（一般链接wifi时会请求这个链接）
	 * 返回值：无
	 */
	public static function _clientResponseMusicMsg($resultMsg) {
		$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
		if (! empty ( $postStr )) {
			$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
			$resultMsg ["UserId"] = $resultMsg ["UserId"] ? $resultMsg ["UserId"] : $postObj->FromUserName;
			$resultMsg ["AccountId"] = $resultMsg ["AccountId"] ? $resultMsg ["AccountId"] : $postObj->ToUserName;
			$resultMsg ["HQMusicUrl"] = $resultMsg ["HQMusicUrl"] ? $resultMsg ["HQMusicUrl"] : $resultMsg ["MusicUrl"];
			$time = time ();
			$textTpl = "<xml>
							 <ToUserName><![CDATA[%s]]></ToUserName>
							 <FromUserName><![CDATA[%s]]></FromUserName>
							 <CreateTime>%s</CreateTime>
							 <MsgType><![CDATA[music]]></MsgType>
							 <Music>
								 <Title><![CDATA[%s]]></Title>
								 <Description><![CDATA[%s]]></Description>
								 <MusicUrl><![CDATA[%s]]></MusicUrl>
								 <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
							 </Music>
							 <FuncFlag>0</FuncFlag>
				    	</xml>";
			$resultStr = sprintf ( $textTpl, $resultMsg ["UserId"], $resultMsg ["AccountId"], $time, $resultMsg ["name"], $resultMsg ["description"], $resultMsg ["MusicUrl"], $resultMsg ["HQMusicUrl"] );
			echo $resultStr;
			__DEBUG ( __FILE__, __LINE__, "Wechat Music", $resultStr );
			exit ();
		} else {
			echo "音乐服务器响应错误……";
			__DEBUG ( __FILE__, __LINE__, "Wechat Error", "Music" );
			exit ();
		}
	}
	
	/**
	 * 函数名：_clientResponseArticalMsg
	 * 功   能： 将应用生成的一条或多条图文消息发送到微信服务器发送给用户
	 * 参   数：
		resultMsg
			MsgType news
			news 图文消息的多维数组：
		    	title 图文消息名称，多条时显示在首页；单条时显示在图片上方
		     	desc 图文消息描述；多条时不显示
	 *320，小图80*80，限制图片链接的域名需要与开发者填写的基本资料中的Url一致
		     	url 点击图文消息跳转链接
			news参数事例：
				$array = array(
					array(
							"title" => "第一条消息",
							"desc" => "第一条消息的内容描述",
							"picURL" =>"http://wekefu.sinaapp.com/resource/1.jpg",
							"url" =>"http://wekefu.sinaapp.com/resource/1.html",
					),
					array(
							"title" => "第二条消息",
							"desc" => "第二条消息的内容描述",
							"picURL" =>"http://wekefu.sinaapp.com/resource/2.jpg",
							"url" =>"http://open.weixin.qq.com",
					)
					
				);
	 * 返回值：无
	 */
	public static function _clientResponseArticalMsg($resultMsg) {
		$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
		if (! empty ( $postStr )) {
			$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
			$resultMsg ["UserId"] = $resultMsg ["UserId"] ? $resultMsg ["UserId"] : $postObj->FromUserName;
			$resultMsg ["AccountId"] = $resultMsg ["AccountId"] ? $resultMsg ["AccountId"] : $postObj->ToUserName;
			$itemNum = count ( $resultMsg ["news"] );
			$time = time ();
			if ($itemNum > 0 && is_array ( $resultMsg ["news"] )) {
				$textTpl = "<xml>
				    			<ToUserName><![CDATA[%s]]></ToUserName>
				    			<FromUserName><![CDATA[%s]]></FromUserName>
				    			<CreateTime>%s</CreateTime>
				    			<MsgType><![CDATA[news]]></MsgType>
				    			<ArticleCount>" . $itemNum . "</ArticleCount>
				    			<Articles>";
				foreach ( $resultMsg ["news"] as $item ) {
					$textTpl .= "<item>
				    				<Title><![CDATA[" . $item ["title"] . "]]></Title>
				    				<Description><![CDATA[" . $item ["desc"] . "]]></Description>
				    				<PicUrl><![CDATA[" . $item ["picURL"] . "]]></PicUrl>
				    				<Url><![CDATA[" . $item ["url"] . "]]></Url>
				    				</item>";
				}
				$textTpl .= "
    							</Articles>
    							<FuncFlag>0</FuncFlag>
    						</xml>";
			} else {
				$errTips = "图文内容为空！";
				$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[text]]></MsgType>
							<Content><![CDATA[$errTips]]></Content>
							<FuncFlag>0</FuncFlag>
						</xml>";
			}
			$resultStr = sprintf ( $textTpl, $resultMsg ["UserId"], $resultMsg ["AccountId"], $time );
			echo $resultStr;
			__DEBUG ( __FILE__, __LINE__, "Wechat Artical", $resultStr );
			exit ();
		} else {
			echo "图文服务器响应错误……";
			__DEBUG ( __FILE__, __LINE__, "Wechat Error", "Artical" );
			exit ();
		}
	}
	
	/**
	 * 函数名：_clientSimpleValid
	 * 功   能：公共平台校验(正常校验无法通过时使用，推荐使用正常校验流程)
	 * 参   数：无
	 * 返回值：无 
	 */
	public static function _clientSimpleValid() {
		$echoStr = $_GET ["echostr"];
		echo $echoStr;
	}
	
	/**
	 * 函数名：_clientValid
	 * 功   能：公共平台校验
	 * 参   数：无
	 * 返回值：无
	 */
	public static function _clientValid() {
		$echoStr = $_GET ["echostr"];
		__DEBUG ( __FILE__, __LINE__, "Valid echostr", "$echoStr" );
		//请原样返回echostr参数内容，则接入生效，否则接入失败。
		if (wechatApi::_clientCheckSignature ()) {
			echo $echoStr;
		}
	}
	
	/**
	 * 函数名：_clientCheckSignature
	 * 功   能：微信接入时检验signature对请求进行校验
	 * 参   数：无
	 * 返回值：校验结果值：true false
	 */
	public static  function _clientCheckSignature() {
		//微信加密签名
		$signature = $_GET ["signature"];
		//时间戳
		$timestamp = $_GET ["timestamp"];
		//随机数
		$nonce = $_GET ["nonce"];
		//加密/校验流程：
		$tmpArr = array (TOEKN, $timestamp, $nonce );
		//1. 将token、timestamp、nonce三个参数进行字典序排序
		sort ( $tmpArr, SORT_STRING );
		//2. 将三个参数字符串拼接成一个字符串进行sha1加密
		$tmpStr = implode ( $tmpArr );
		$tmpStr = sha1 ( $tmpStr );
		//3. 开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
		__DEBUG ( __FILE__, __LINE__, "Valid signature", "$signature" );
		__DEBUG ( __FILE__, __LINE__, "Valid tmpStr", "$tmpStr" );
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}
	
	/****************************************************
	 * *
	 * PART2：微信 OAuth2.0 相关代码				*
	 * *
	 ****************************************************/
	
	/**
	 * 函数名：_authGetAuthorizeLink
	 * 功   能：获取授权链接
	 * 参   数：
			appid:请求appid
			redirect_uri:授权回调地址
			scope：授权信息
			state：透传字段
	 * 返回值:
			授权链接
	 * 返回值事例:
	 */
	public static function _authGetAuthorizeLink($appid, $redirect_uri, $scope, $state) {
		$link = "https://open.weixin.qq.com/connect/oauth2/authorize";
		$para = "appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=" . $scope . "&state=" . $state . "#wechat_redirect";
		return $link . "?" . $para;
	}
	
	/**
	 * 函数名：_authGetAccessTokenByCode
	 * 功   能：根据帐号的appid和key获取accesstoken
	 * 参   数：
			code:作为换取access_token的票据，每次用户授权带上的code将不一样，code只能使用一次，5分钟未被使用自动过期
			grant_type:授权域
	 * 返回值：
			access_token:网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同
			expires_in:access_token接口调用凭证超时时间，单位（秒）
			refresh_token:用户刷新access_token
			openid:用户唯一标识，请注意，在未关注公众号时，用户访问公众号的网页，也会产生一个用户和公众号唯一的OpenID
			scope:用户授权的作用域，使用逗号（,）分隔
	 * 返回值事例:
		{
			"access_token":"ACCESS_TOKEN",
			"expires_in":7200,
			"refresh_token":"REFRESH_TOKEN",
			"openid":"OPENID",
			"scope":"SCOPE"
		}
	 */
	public static function _authGetAccessTokenByCode($appid, $secret, $code, $grant_type = "authorization_code") {
		$link = "https://api.weixin.qq.com/sns/oauth2/access_token";
		$para = "appid=$appid&secret=$secret&code=$code&grant_type=$grant_type";
			__DEBUG ( __FILE__, __LINE__, "_authGetAccessTokenByCode", $link . "?" . $para );
		$result = wechatApi::__sendCurlRequest ( $link, $para );
		return $result;
	}
	
	/**
	 * 函数名：_authRefreshAccessToken
	 * 功   能：根据appid和refresh_token获取最新的accessToken
	 * 参   数：	
			refresh_token：当前可用的刷新token
			grant_type:授权域
	 * 返回值:
			access_token：网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同
			expires_in：access_token接口调用凭证超时时间，单位（秒）
			refresh_token：用户刷新access_token
			openid：用户唯一标识
			scope：用户授权的作用域，使用逗号（,）分隔
	 * 返回值事例:
			{
				"access_token":"ACCESS_TOKEN",
				"expires_in":7200,
				"refresh_token":"REFRESH_TOKEN",
				"openid":"OPENID",
				"scope":"SCOPE"
			}
	 */
	public static function _authRefreshAccessToken($appid, $refresh_token, $grant_type = "refresh_token") {
		$link = "https://api.weixin.qq.com/sns/oauth2/refresh_token";
		$para = "appid=$appid&refresh_token=$refresh_token&grant_type=$grant_type";
		__DEBUG ( __FILE__, __LINE__, "_authRefreshAccessToken", $link . "?" . $para );
		$result = wechatApi::__sendCurlRequest ( $link, $para );
		return $result;
	}
	
	/**
	 * 函数名：_authGetUserInfoByToken
	 * 功   能：根据用户的token和openID获取用户的个人信息
	 * 参   数：
		access_token：当前用户的token
		openid:当前用户的openID
	 * 返回值:
		openid：用户的唯一标识
		nickname：用户昵称
		sex：用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
		province：用户个人资料填写的省份
		city：普通用户个人资料填写的城市
		country：国家，如中国为CN
	 *640正方形头像），用户没有头像时该项为空
		privilege：用户特权信息，json 数组，如微信沃卡用户为（chinaunicom）
	 * 返回值事例:
		{
			"openid":" OPENID",
			"nickname": NICKNAME,
			"sex":"1",
			"province":"PROVINCE"
			"city":"CITY",
			"country":"COUNTRY",
			"headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46",
			"privilege":[
			"PRIVILEGE1"
			"PRIVILEGE2"
			]
		}
	 */
	public static function _authGetUserInfoByToken($access_token, $openid) {
		$link = "https://api.weixin.qq.com/sns/userinfo";
		$para = "access_token=$access_token&openid=$openid";
		__DEBUG ( __FILE__, __LINE__, "_authGetUserInfoByToken", $link . "?" . $para );
		$result = wechatApi::__sendCurlRequest ( $link, $para );
		return $result;
	}
	
	/************************************************************
	 * *
	 * PART3：微信 客服接口（主动推送消息）				*
	 * *
	 ************************************************************/
	
	/**
	 * 函数名：_backSendMsg
	 * 功   能：通过access token 主动推送消息给用户
	 * 参   数：
		access_token：调用接口凭证 
		msg:消息数组或者json数据
			touser：普通用户openid 
    		msgtype：消息类型
    			文本消息：text
					content：文本内容
				图片消息:image
					media_id：发送的图片的媒体ID（事先上传）
				语音消息:voice
					media_id：发送的语音的媒体ID（事先上传）
				视频消息:voice
					media_id：发送的视频的媒体ID （事先上传）
					thumb_media_id：视频缩略图的媒体ID 
				音乐消息:music	
					title：音乐标题
					description：音乐描述
					musicurl：音乐链接
					hqmusicurl：高品质音乐链接，wifi环境优先使用该链接播放音乐
				图文消息:news	
					articles：
						title：标题
						description：描述
						url：点击后跳转的链接
	 *320，小图80*80 
	 * 返回值：无
	 */
	public static function _backSendMsg($access_token, $msg) {
		if (is_array ( $msg )) {
			$msg = json_encode ( $msg );
		}
		$link = "https://api.weixin.qq.com/cgi-bin/message/custom/send";
		$para = "access_token=" . $access_token;
		__DEBUG ( __FILE__, __LINE__, "_backSendMsg", $link . "?" . $para . "&" . $msg );
		$result = wechatApi::__sendCurlRequest ( $link, $para, $msg );
		return $result;
	}
	
	/********************************************************
	 * *
	 * PART4：微信用户管理（分组，关注者列表等）			*
	 * *
	 ********************************************************/
	
	/**
	 * 函数名：_userGetGroupList
	 * 功   能：根据accessToken获取公众平台的分组
	 * 参   数：
		access_token：调用接口凭证 
	 * 返回值:
		groups:公众平台分组信息列表
			id:分组id，由微信分配
			name:分组名字，UTF8编码
			count:分组内用户数量 
	 * 返回值事例:
		{
   			"groups": [
		        {"id": 0,"name": "未分组", "count": 72596}, 
		        {"id": 1, "name": "黑名单", "count": 36}, 
		        {"id": 2, "name": "星标组",  "count": 8}, 
		        {"id": 106,"name": "测试组","count": 1}
		    ]
		}
	 */
	public static function _userGetGroupList($access_token) {
		$link = "https://api.weixin.qq.com/cgi-bin/groups/get";
		$para = "access_token=" . $access_token;
		__DEBUG ( __FILE__, __LINE__, "_userGetGroupList", $link . "?" . $para );
		$result = wechatApi::__sendCurlRequest ( $link, $para );
		return $result;
	}
	
	/**
	 * 函数名：_userCreateGroup(一个公众账号，最多支持创建500个分组)
	 * 功   能：根据accessToken创建分组
	 * 参   数：
		access_token：调用接口凭证
		data ：
			POST数据格式：json
			POST数据例子：{"group":{"name":"test"}}
	 * 返回值:
			id：分组id，由微信分配
			name：分组名字，UTF8编码
	 * 返回值事例:
			{"group": {"id": 107, "name": "test"}}
	 */
	public static function _userCreateGroup($access_token, $name) {
		$link = "https://api.weixin.qq.com/cgi-bin/groups/create";
		$para = "access_token=" . $access_token;
		$data = json_encode ( array ("group" => array ("name" => $name ) ) );
		__DEBUG ( __FILE__, __LINE__, "_userGreateGroup", $link . "?" . $data . "&" . $para );
		$result = wechatApi::__sendCurlRequest ( $link, $para, $data );
		return $result;
	}
	
	/**
	 * 函数名：_userChangeGroupName
	 * 功   能：根据accessToken创建分组
	 * 参   数：
		access_token：调用接口凭证
		data ：
			POST数据格式：json
				id：分组id，由微信分配
				name：分组名字（30个字符以内） 
			POST数据例子：{"group":{"id":108,"name":"test2_modify2"}}
	 * 返回值:
			errcode：返回码
			errmsg：提示信息
	 * 返回值事例:
		{"errcode": 0, "errmsg": "ok"}
	 */
	public static function _userChangeGroupName($access_token, $groupId, $name) {
		$link = "https://api.weixin.qq.com/cgi-bin/groups/update";
		$para = "access_token=" . $access_token;
		$data = json_encode ( array ("group" => array ("id" => $groupId, "name" => $name ) ) );
		__DEBUG ( __FILE__, __LINE__, "_userGreateGroup", $link . "?" . $data . "&" . $para );
		$result = wechatApi::__sendCurlRequest ( $link, $para, $data );
		return $result;
	}
	
	/**
	 * 函数名：_userChangeUserGroup
	 * 功   能：根据accessToken调账玩家的分组
	 * 参   数：
		access_token：调用接口凭证
		data ：
			POST数据格式：json
			openid:用户唯一标识符
			to_groupid:分组id 
			POST数据例子：{"openid":"oDF3iYx0ro3_7jD4HFRDfrjdCM58","to_groupid":108}
	 * 返回值:
			errcode：返回码
			errmsg：提示信息
	 * 返回值事例:
			{"errcode": 0, "errmsg": "ok"}
	 */
	public static function _userChangeUserGroup($access_token, $openid, $groupId) {
		$link = "https://api.weixin.qq.com/cgi-bin/groups/members/update";
		$para = "access_token=" . $access_token;
		$data = json_encode ( array ("group" => array ("openid" => $openid, "to_groupid" => $groupId ) ) );
		__DEBUG ( __FILE__, __LINE__, "_userChangeUserGroup", $link . "?" . $data . "&" . $para );
		$result = wechatApi::__sendCurlRequest ( $link, $para, $data );
		return $result;
	}
	
	/**
	 * 函数名：_userGetUserInfoByAdminToken
	 * 功   能：根据公共号通用的token和openID获取用户的个人信息
	 * 参   数：
			access_token：微信公共帐号管理端通用token
			openid:当前用户的openID
	 * 返回值:
			ubscribe：用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息。
			openid：用户的标识，对当前公众号唯一
			nickname：用户的昵称
			sex：用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
			city：用户所在城市
			country：用户所在国家
			province：用户所在省份
			language：用户的语言，简体中文为zh_CN
	 *640正方形头像），用户没有头像时该项为空
			subscribe_time：用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间 
	 * 返回值事例:
			{
		    "subscribe": 1, 
		    "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M", 
		    "nickname": "Band", 
		    "sex": 1, 
		    "language": "zh_CN", 
		    "city": "广州", 
		    "province": "广东", 
		    "country": "中国", 
		    "headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0", 
		   "subscribe_time": 1382694957
			}
	 */
	public static function _userGetUserInfoByAdminToken($access_token, $openid) {
		$link = "https://api.weixin.qq.com/cgi-bin/user/info";
		$para = "access_token=$access_token&openid=$openid";
		__DEBUG ( __FILE__, __LINE__, "_userGetUserInfoByAdminToken", $link . "?" . $para );
		$result = wechatApi::__sendCurlRequest ( $link, $para );
		return $result;
	}
	
	/**
	 * 函数名：_userGetUserList
	 * 功   能：根据accessToken获取关注用户列表
	 * 参   数：
			access_token：调用接口凭证
			next_openid：第一个拉取的OPENID，不填默认从头开始拉取 
	 * 返回值:
			total：关注该公众账号的总用户数
			count：拉取的OPENID个数，最大值为10000
			data：列表数据，OPENID的列表
			next_openid：拉取列表的后一个用户的OPENID
	 * 返回值事例:
		{"total":2,"count":2,"data":{"openid":["","OPENID1","OPENID2"]},"next_openid":"NEXT_OPENID"}
	 */
	public static function _userGetUserList($access_token, $next_openid) {
		$link = "https://api.weixin.qq.com/cgi-bin/user/get";
		$para = "access_token=" . $access_token;
		if ($next_openid) {
			$para .= "&next_openid=" . $next_openid;
		}
		__DEBUG ( __FILE__, __LINE__, "_userGetUserList", $link . "?" . $para );
		$result = wechatApi::__sendCurlRequest ( $link, $para );
		return $result;
	}
	
	/****************************************************
	 * *
	 * PART5：微信界面菜单管理（创建、查询、删除等）	*
	 * *
	 ****************************************************/
	
	/**
	 * 函数名：_menuGetMenu
	 * 功   能：根据accessToken获取公众平台的菜单信息
	 * 参   数：
		access_token：调用接口凭证
	 * 返回值:
		menu:json格式的菜单信息
	 * 返回值事例:
		{"menu":{"button":[{"type":"click","name":"今日歌曲","key":"V1001_TODAY_MUSIC","sub_button":[]},{"type":"click","name":"歌手简介","key":"V1001_TODAY_SINGER","sub_button":[]},{"name":"菜单","sub_button":[{"type":"view","name":"搜索","url":"http://www.soso.com/","sub_button":[]},{"type":"view","name":"视频","url":"http://v.qq.com/","sub_button":[]},{"type":"click","name":"赞一下我们","key":"V1001_GOOD","sub_button":[]}]}]}}
	 */
	public static function _menuGetMenu($access_token) {
		$link = "https://api.weixin.qq.com/cgi-bin/menu/get";
		$para = "access_token=" . $access_token;
		__DEBUG ( __FILE__, __LINE__, "_menuGetMenu", $link . "?" . $para );
		$result = wechatApi::__sendCurlRequest ( $link, $para );
		return $result;
	}
	
	/**
	 * 函数名：_menuCreateMenu
	 * 功   能：根据accessToken创建菜单
	 * 参   数：
			access_token：调用接口凭证
			menu：
				button：一级菜单数组，个数应为1~3个
				sub_button：二级菜单数组，个数应为1~5个
				type：菜单的响应动作类型，目前有click、view两种类型
				name：菜单标题，不超过16个字节，子菜单不超过40个字节
				key：click类型必须 	菜单KEY值，用于消息接口推送，不超过128字节
				url：view类型必须 	网页链接，用户点击菜单可打开链接，不超过256字节 
	 * 返回值:
			json格式的结果信息
	 * 返回值事例:
		{"errcode":0,"errmsg":"ok"}
	 */
	public static function _menuCreateMenu($access_token, $menu) {
		$link = "https://api.weixin.qq.com/cgi-bin/menu/create";
		$para = "access_token=" . $access_token;
		__DEBUG ( __FILE__, __LINE__, "_menuCreateMenu", $link . "?" . $para . "&" . $menu );
		$result = wechatApi::__sendCurlRequest ( $link, $para, $menu );
		return $result;
	}
	
	/**
	 * 函数名：_menuDelMenu
	 * 功   能：根据accessToken删除公众平台菜单
	 * 参   数：
			access_token：调用接口凭证
	 * 返回值:
			json格式的结果信息
	 * 返回值事例:
			{"errcode":0,"errmsg":"ok"}
	 */
	public static function _menuDelMenu($access_token) {
		$link = "https://api.weixin.qq.com/cgi-bin/menu/delete";
		$para = "access_token=" . $access_token;
		__DEBUG ( __FILE__, __LINE__, "_menuDelMenu", $link . "?" . $para );
		$result = wechatApi::__sendCurlRequest ( $link, $para );
		return $result;
	}
	
	/************************************************
	 * *
	 * PART6：微信 二维码接口（带参数二维码）			*
	 * *
	 ************************************************/
	
	/**
	 * 函数名：_qrcodeGetTicket
	 * 功   能：通过access token获得一个带参数二维码的ticket
	 * 参   数：
		access_token：调用接口凭证
		POST数据格式：json
			expire_seconds:该二维码有效时间，以秒为单位。 最大不超过1800。
			action_name:二维码类型，QR_SCENE为临时,QR_LIMIT_SCENE为永久
			action_info:二维码详细信息
				scene_id:场景值ID，临时二维码时为32位整型,建议从1001开始使用，永久二维码时最大值为1000 
		POST数据例子：{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}
	 * 返回值：
		ticket:获取的二维码ticket，凭借此ticket可以在有效时间内换取二维码。
		expire_seconds:二维码的有效时间，以秒为单位。最大不超过1800。 
	 * 返回值事例：
		{"ticket":"gQG28DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL0FuWC1DNmZuVEhvMVp4NDNMRnNRAAIEesLvUQMECAcAAA==","expire_seconds":1800}
	 */
	public static  function _qrcodeGetTicket($access_token, $scene_id, $expire_seconds) {
		if ($expire_seconds) {
			$data = array ("expire_seconds" => $expire_seconds, "action_name" => "QR_SCENE", "action_info" => array ("scene" => array ("scene_id" => $scene_id ) ) );
		} else {
			$data = array ("action_name" => "QR_LIMIT_SCENE", "action_info" => array ("scene" => array ("scene_id" => $scene_id ) ) );
		}
		$link = "https://api.weixin.qq.com/cgi-bin/qrcode/create";
		$para = "access_token=" . $access_token;
		__DEBUG ( __FILE__, __LINE__, "_qrcodeGetTicket", $link . "?" . $data . "&" . $para );
		$result = wechatApi::__sendCurlRequest ( $link, $para, $data );
		return $result;
	}
	
	/**
	 * 函数名：_qrcodeShowCodeImg
	 * 功   能：开发者可用ticket换取二维码图片。请注意，本接口无须登录态即可调用。
	 * 参   数：
		ticket：ticket可以在有效时间内换取二维码。
	 * 返回值：
		ticket正确情况下，返回下载链接。
	 */
	public static function _qrcodeShowCodeImg($ticket) {
		$link = "https://mp.weixin.qq.com/cgi-bin/showqrcode";
		$para = "ticket=" . $ticket;
		__DEBUG ( __FILE__, __LINE__, "_qrcodeShowCodeImg", $link . "?" . $para );
		return $link . "?" . $para;
	}
	
	/************************************************
	 * *
	 * PART0：部分通用函数（curl请求，管理端token）	*
	 * *
	 ************************************************/
	
	/**
	 * 函数名：__getAdminAccessToken
	 * 功   能：从cache获取一个应用的admin access token，如果cache没有，就生成一个
	 * 参   数：
		APPID：应用ID
		SECRET：应用key
	 * 返回值：
		result:
			access_token:token
			expires_in :有效期
			ecode:错误码
	 */
	public static function __getAdminAccessToken($appid, $secret) {
		$key = md5 ( "WECHATADMINACCESSTOKEN" . TOKEN );
		if (wechatApi::$__sMemLink) {
			$result = wechatApi::$__sMemLink->get ( $key );
		}
		if (! $result ["access_token"]) {
			$result = wechatApi::__getNewAdminAccessToken ( $appid, $secret );
		}
		return $result;
	}
	
	/**
	 * 函数名：__getAdminAccessToken
	 * 功   能：从cache获取一个应用的admin access token，如果cache没有，就生成一个
	 * 参   数：
		APPID：应用ID
		SECRET：应用key
	 * 返回值：
		result:
			access_token:token
			expires_in :有效期
			ecode:错误码
	 */
	public static function __getNewAdminAccessToken($appid, $secret) {
		$link = "https://api.weixin.qq.com/cgi-bin/token";
		$para = "grant_type=client_credential&appid=$appid&secret=$secret";
		__DEBUG ( __FILE__, __LINE__, "__getAdminAccessToken", $link . "?" . $para );
		$result = wechatApi::__sendCurlRequest ( $link, $para );
		if (wechatApi::$__sMemLink && $result ["access_token"]) {
			$key = md5 ( "WECHATADMINACCESSTOKEN" . TOKEN );
			wechatApi::$__sMemLink->set ( $key, $result, 0, 7200 );
		}
		return $result;
	}
	
	/**
	 * 函数名：__sendCurlRequest
	 * 功   能：使用curl发送请求
	 * 参   数：
		link：请求地址
		para：请求参数
		data：请求内容,不用实现json格式化
	 * 注意：
		由于微信服务器对数据格式有要求，因此如果参数中有中文，先对中文urlencode
	 */
	public static function __sendCurlRequest($link, $para, $data="") {
		$tempData = "";
		if($data){
			if (is_array ( $data )) {
			$tempData = json_encode ( $data );
		}
			$tempData = urldecode ( $tempData );
		}
		__DEBUG ( __FILE__, __LINE__, "__sendCurlRequest", $link . "?" . $para . "&" . $tempData );
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		if ($tempData) {
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $tempData . "&" . $para );
		} else {
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $para );
		}
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_URL, $link );
		$result = curl_exec ( $ch );
		if (curl_errno ( $ch )) {
			echo 'Error:' . curl_error ( $ch ); //捕抓异常
			exit ();
		}
		curl_close ( $ch );
		__DEBUG ( __FILE__, __LINE__, "__sendCurlRequest", $result );
		return json_decode ( $result, true );
	}
}
;

/**
 * 函数名：__DEBUG
 * 功   能：微信端debug
 * 参   数：
	file,line：日志记录位置
	title：记录参数信息
	cotent：日志信息
 */
function __DEBUG($file, $line, $title, $content) {
	if (ZIXIE_DEBUG) {
		if (is_array ( $content )) {
			$content = json_encode ( $content );
		}
		$log = "[" . date ( 'Y-m-d H:i:s ' ) . "]\t[$file:$line]\t[$title]\t" . $content . "\n";
		$fileName = dirname ( __FILE__ ) . '/wechat_debug_'.TOKEN.'_' . date ( 'Ym' ) . '.log';
		file_put_contents ( $fileName, $log, FILE_APPEND );
		return true;
	} else {
		return false;
	}
}

if (MEMACHE) {
	wechatApi::$__sMemLink = new Memcache ();
	wechatApi::$__sMemLink->connect ( $GLOBALS ['mem_config'] ['host'], $GLOBALS ['mem_config'] ['port'] );
}
?>