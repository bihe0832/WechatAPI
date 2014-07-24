<?php
$state = htmlspecialchars($_REQUEST["state"]);
var_dump($state);
$code = htmlspecialchars($_REQUEST["code"]);
echo "<BR>code:".$code."<BR><BR>";
$openid = htmlspecialchars($_REQUEST["openid"]);
echo "<BR>openid:".$openid."<BR><BR>";
?>