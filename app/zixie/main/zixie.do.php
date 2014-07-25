<?php
require_once (dirname(__FILE__).'/../../app.do.php');

class zixieDo extends appDo {
	public function __construct()	{
		parent::__construct(APPCODE);
	}
	
	//建议子类重写这部分
	public function startModel($userInput,$command){
		$basicCommandArr = array("SUB","UNSUB","IMG","LOC","VOC","HELP","CLEAR");
		$menuKeyArrayNews = array("NEWGAME","MORENOTE","CURNOTE","FORUM","HOME","LIST","IMC","ICS","IVR","GAME");
		$commandKeyArrayCore = array("VOCMAIN","MAIN","MOREANSWER","ASK","ADVICE","A","B","C","MSG","HUMAN");
		$VoteArrayNews = array("TOUPIAO");
		$tips = "";
		if(in_array($command,$menuKeyArrayNews) || in_array($command,$basicCommandArr)){
			//TODO 处理基本反悔内容
		}else if(in_array($command,$VoteArrayNews)){
			// TODO 处理个性需求
		}else {
			// TODO 处理个性需求
		}
		//TODO 这里为具体的业务逻辑代码
		return $command;
	}
}
?>