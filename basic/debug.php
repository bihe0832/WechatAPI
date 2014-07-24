<?php
require_once (dirname ( __FILE__ ) . '/../conf/conf_conn.php');
function traceHttp() {
	debug ( __FILE__, __LINE__, "Wechat Server", "REMOTE_ADDR:" . $_SERVER ["REMOTE_ADDR"] . (strpos ( $_SERVER ["REMOTE_ADDR"], "157.97" ) ? " From Wechat" : " Unknow IP") );
	debug ( __FILE__, __LINE__, "Wechat Commond", "QUERY_STRING:" . $_SERVER ["QUERY_STRING"] );
	return true;
}

function debug($file = "", $line = "", $title = "", $content = "") {
	$type = "DEBUG";
	LL_LOG ( $type, $file, $line, $title, $content );
	return true;
}

/*
 * $logType:DEBUG、TLOG、CGI
 */
function LL_LOG($type, $file = "", $line = "", $title = "", $content = "") {
	if ($GLOBALS ["debug"]) {
		$content = json_encode ( $content );
		$log = "$type\t[" . date ( 'Y-m-d H:i:s ' ) . "]\t[$file:$line]\t" . $content . "\n";
		if ($GLOBALS ['isSAE']) {
			sae_debug ( $log );
		} else {
			if ($type == "CGI") {
				$fileName = dirname ( __FILE__ ) . '/../log/cgi.log';
			} else if ($type == "DEBUG") {
				$log = $log . "<BR>";
				$fileName = dirname ( __FILE__ ) . '/../log/debug.html';
			} else {
				$fileName = dirname ( __FILE__ ) . '/../log/default.log';
			}
			file_put_contents ( $fileName, $log, FILE_APPEND );
		}
		return true;
	}

}

function LL_TLOG($log = "") {
	if ($GLOBALS ['isSAE']) {
		$log = "TLOG\t" . $log;
		sae_debug ( $log );
	} else {
		$fileName = dirname ( __FILE__ ) . '/../log/tlog.log';
		file_put_contents ( $fileName, $log, FILE_APPEND );
	}
	return true;
}

?>