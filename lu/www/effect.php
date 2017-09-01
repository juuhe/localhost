<?php

require './config.php';
require_once LIB_PATH . '/kernel.php';
$type = $_GET['type'];
$planid = $_GET['pid'];
$ip = $_SERVER['REMOTE_ADDR'];
if (($type == 'ecstats') || ($type == 'ef')) {
	if ($type == 'ecstats') {
		$c = addslashes($_COOKIE['do2click_' . $planid]);
	}
	else {
		$c = addslashes($_COOKIE['doEffect_' . $planid]);
	}

	if (!$c) {
		exit();
	}

	$e = explode('|', $c);
	$adsid = (int) $e[0];
	$planid = (int) $e[1];
	$uid = (int) $e[2];
	$zoneid = (int) $e[3];
	$siteid = (int) $e[4];

	if ($type == 'ecstats') {
		dr('jump/jump.updata_2click', DAYS, $ip, $planid, $adsid, $uid, $zoneid, $siteid, 2);
	}
	else {
		dr('jump/jump.updata_effect', DAYS, $ip, $planid, $adsid, $uid, $zoneid, $siteid, 1);
	}

	exit();
}

if ($type == 'ecv') {
	$adsid = (int) $_GET['adsid'];
	$planid = (int) $_GET['planid'];
	$uid = (int) $_GET['uid'];
	$zoneid = (int) $_GET['oneid'];
	$siteid = (int) $_GET['siteid'];
	$adtplid = (int) $_GET['adtplid'];
	$plantype = $_GET['plantype'];
	dr('jump/jump.updata_click', DAYS, $ip, $planid, $adsid, $uid, $zoneid, $siteid, $adtplid, $plantype, 3);
	exit();
}

echo "\r\n(function() {\r\nvar z =  {\r\n\t\ts:function(name){\r\n\t\t\tvar Then = new Date();\r\n\t\t\tThen.setTime(Then.getTime()+ 60 * 1000*60);\r\n\t\t\tdocument.cookie=name+'=1;expires='+ Then.toGMTString()+';path=/;';\r\n\t\t},\r\n\t\tg:function(name){\r\n\t\t\tvar search = name + \"=\";\r\n\t\t\tvar vauel = \"\";\r\n\t\t\tif (document.cookie.length > 0) {\r\n\t\t\t\toffset = document.cookie.indexOf(search);\r\n\t\t\t\tif (offset != -1) {\r\n\t\t\t\t\toffset += search.length;\r\n\t\t\t\t\tend = document.cookie.indexOf(\";\", offset);\r\n\t\t\t\t\tif (end == -1){\r\n\t\t\t\t\t\tvauel=unescape(document.cookie.substring(offset, document.cookie.length));\r\n\t\t\t\t\t}else{\r\n\t\t\t\t\t\tvauel=unescape(document.cookie.substring(offset, end));\r\n\t\t\t\t\t\t}\r\n\t\t\t\t\t}\r\n\t\t\t}\r\n\t\t\treturn vauel;\r\n\t\t},\r\n\t\tg:function(d, c) {\r\n\t\t\tc = c || window;\r\n\t\t\tif (\"string\" === typeof d || d instanceof String) {\r\n\t\t\t\treturn c.document.getElementById(d)\r\n\t\t\t} else {\r\n\t\t\t\tif (d && d.nodeName && (d.nodeType == 1 || d.nodeType == 9)) {\r\n\t\t\t\t\treturn d\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t\treturn d;\r\n\t\t},\r\n\t\ta:function(c, d, e) {\r\n\t\t\tc = z.g(c);\r\n\t\t\td = d.replace(/^on/i, \"\").toLowerCase();\r\n\t\t\tif (c.addEventListener) {\r\n\t\t\t\tc.addEventListener(d, e, false)\r\n\t\t\t} else {\r\n\t\t\t\tif (c.attachEvent) {\r\n\t\t\t\t\tc.attachEvent(\"on\" + d, e)\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t\treturn c;\r\n\t\t},\r\n\t\td:function (c, d, e) {\r\n\t\t\tif (c.removeEventListener) {\r\n\t\t\t\tc.removeEventListener(d, e, false);\r\n\t\t\t} else if (c.detachEvent) {\r\n\t\t\t\tc.detachEvent(\"on\" + d, e);\r\n\t\t\t} else { \r\n\t\t\t\tc[\"on\" + d] = null;\r\n\t\t\t}\r\n\t\t},\r\n\t\tb:function(){\r\n\t\t\tvar n = \"__c_";
echo $planid;
echo "\" ;\r\n\t\t\tvar a=new Image();\t\r\n\t\t\tvar url = \"";
echo $GLOBALS['C_ZYIIS']['jump_url'] . WEB_URL;
echo 'effect.php?type=ecstats&pid=';
echo $planid;
echo "\";\r\n\t\t\ta.src=url;\r\n\t\t\tz.d(document,\"click\",z.b);\r\n\t\t\tz.s(n);\r\n\t\t},\r\n\t\tc:function(){\r\n\t\t\tvar n = \"__c_";
echo $planid;
echo "\" ;\r\n\t\t\tvar a = z.g(n);\r\n\t\t\tif(!a){\r\n\t\t\t\tz.a(document,\"click\",z.b);\r\n\t\t\t}\r\n\t\t\t\r\n\t\t}\r\n\t}\r\n\tz.c();\r\n\r\n})()";

?>
