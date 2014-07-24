<?php
require_once (dirname(__FILE__).'/wechatTools.class.php');
class  wechatToolsDo extends wechatTools{

	public function __construct($app_id,$app_key){
		parent::__construct($app_id,$app_key);
	}
	
	public function showMenu(){
		echo "<BR>------------------------------GET MENU------------------------------<BR>";
		$result = $this->_getMenu();
		var_dump($result);
		echo "<BR>------------------------------GET MENU------------------------------<BR>";
	}
	
	public function delMenu(){
		echo "<BR>------------------------------GET MENU------------------------------<BR>";
		$result = $this->_getMenu();
		var_dump($result);
		echo "<BR>------------------------------GET MENU------------------------------<BR>";
		
		echo "<BR>------------------------------DEL MENU------------------------------<BR>";
		$result = $this->_delMenu();
		var_dump($result);
		echo "<BR>------------------------------DEL MENU------------------------------<BR>";
		
		echo "<BR>------------------------------GET MENU------------------------------<BR>";
		$result = $this->_getMenu();
		var_dump($result);
		echo "<BR>------------------------------GET MENU------------------------------<BR>";
	}
	
	public function createMenu($menu){
		echo "<BR>------------------------------GET MENU------------------------------<BR>";
		$result = $this->_getMenu();
		var_dump($result);
		echo "<BR>------------------------------GET MENU------------------------------<BR>";
		
		echo "<BR>------------------------------CREATE MENU------------------------------<BR>";
		$result = $this->_createMenu($menu);
		var_dump($result);
		echo "<BR>------------------------------GET MENU------------------------------<BR>";
		
		echo "<BR>------------------------------GET MENU------------------------------<BR>";
		$result = $this->_getMenu();
		var_dump($result);
		echo "<BR>------------------------------GET MENU------------------------------<BR>";
	}
	
	public function createQrcode($scene_id){
		echo "<BR>------------------------------GET QRCODE TICKET------------------------------<BR>";
		$result = $this->_createQrcode($scene_id);
		var_dump($result);
		echo "<BR>------------------------------GET QRCODE TICKET------------------------------<BR>";
		
		echo "<BR>------------------------------GET QRCODE ------------------------------------<BR>";
		$result = $this->_showQrcode($result["ticket"]);
		var_dump($result);
		echo "<BR>------------------------------GET QRCODE ------------------------------------<BR>";
	}
	
	public function showQrcodeLink($ticket){
		echo "<BR>------------------------------GET QRCODE ------------------------------------<BR>";
		$result = $this->_showQrcode($ticket);
		var_dump($result);
		echo "<BR>------------------------------GET QRCODE ------------------------------------<BR>";
	}
	
}
?>