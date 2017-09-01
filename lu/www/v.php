<?php

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html>\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n";
require './config.php';
require_once LIB_PATH . '/kernel.php';
require APP_PATH . '/ad/show.php';
$z = cache::get_zoneinfo($_GET['id']);
$v = cache::get_view_adstyle($z['adstyleid']);
$ad = show::view_ad($z, $v['tpl']);

if (!$ad) {
	exit($GLOBALS['C_ZYIIS']['show_text_notad']);
}

$os = show::os();
echo "<style>\r\nbody {\r\n\tmargin: 0px;\r\n\tpadding: 0px\r\n}\r\n\r\na#bz {\r\n\tz-index: 100;\r\n\tdisplay: block;\r\n\tposition: absolute;\r\n\tbottom: 0;\r\n\tright: 0;\r\n\twidth: 22px;\r\n\theight: 0;\r\n\tpadding-top: 18px;\r\n\toverflow: hidden;\r\n\tbackground: url(";
echo $GLOBALS['C_ZYIIS']['img_url'] . '' . WEB_URL;
echo "images/b-1.png);\r\n\toutline: 0;\r\n\tblr: expression(this.onFocus = this.blur () );\r\n}\r\n\r\na#bz:hover {\r\n\twidth: 76px;\r\n\tbackground: url(";
echo $GLOBALS['C_ZYIIS']['img_url'] . '' . WEB_URL;
echo "images/b-2.png);\r\n}\r\n\r\n* html a#bz {\r\n\tfilter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\"";
echo $GLOBALS['C_ZYIIS']['img_url'] . '' . WEB_URL;
echo "images/b-1.png\");\r\n\tbackground: none;\r\n\tcursor: pointer;\r\n}\r\n\r\n* * html a#bz:hover {\r\n\tfilter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\"";
echo $GLOBALS['C_ZYIIS']['img_url'] . '' . WEB_URL;
echo "images/b-2.png\");\r\n\tbackground: none;\r\n\tcursor: pointer;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n\t<div id=\"container\" class=\"container\"></div>\r\n\t ";

if ($GLOBALS['C_ZYIIS']['union_bz_status'] == '1') {
	echo "\t<a id=\"bz\" class=\"bz\"\r\n\t\thref=\"http://";
	echo $GLOBALS['C_ZYIIS']['authorized_url'] . '' . WEB_URL;
	echo "\"\r\n\t\ttarget=\"_blank\">#bz</a>\r\n\t";
}

echo "</body>\r\n<script>\r\nvar pvid={pid:[],aid:[]};\r\nvar ads = ";
echo json_encode($ad);
echo "; \r\nvar config = ";
echo $z['codestyle'];
echo "; \r\nfunction pvstas(pvid){\r\n\r\n\t";
$runm = ($z['pvstep'] ? $z['pvstep'] : $GLOBALS['C_ZYIIS']['pv_step']);
$rand = rand(1, $runm);
if (($rand == 1) && $GLOBALS['C_ZYIIS']['pv_step']) {
	echo "\tvar aid ,pid;\r\n\tif(pvid.aid.length>1){\r\n\t \taid = pvid.aid.join(\",\").match( /([^,]+)(?!.*\x01)/ig);\r\n\t \tpid = pvid.pid.join(\",\").match( /([^,]+)(?!.*\x01)/ig);\r\n\t}else {\r\n\t\taid = pvid.aid;\r\n\t\tpid = pvid.pid;\r\n\t}\r\n\tvar jsurl= '";
	echo $GLOBALS['C_ZYIIS']['jump_url'] . WEB_URL;
	echo "';\r\n    var d=document,\r\n    g=d.createElement(\"script\"), \r\n    s=d.getElementsByTagName(\"head\")[0];\r\n    g.type=\"text/javascript\";\r\n    g.defer=true; \r\n    g.async=true;\r\n    g.src=jsurl+\"stats.php?adsid=\"+aid+\"&planid=\"+pid+\"&uid=";
	echo $z['uid'];
	echo '&siteid=&plantype=';
	echo $z['plantype'];
	echo '&zoneid=';
	echo $z['zoneid'];
	echo '&adtplid=';
	echo $z['adtplid'];
	echo '&sep=';
	echo $runm;
	echo "\"; \r\n    s.insertBefore(g,s.firstChild);\t\r\n    ";
}

echo "}\r\n";
echo $v['style']['iframejs'] . $v['tpl']['iframejs'];
echo ";\r\n\r\n(function() {\r\n \r\nfunction __G(d, c) {\r\n\tc = c || window;\r\n\tif (\"string\" === typeof d || d instanceof String) {\r\n\t\treturn c.document.getElementById(d)\r\n\t} else {\r\n\t\tif (d && d.nodeName && (d.nodeType == 1 || d.nodeType == 9)) {\r\n\t\t\treturn d\r\n\t\t}\r\n\t}\r\n\treturn d\r\n}\r\nfunction __A(c, d, e) {\r\n\tc = __G(c);\r\n\td = d.replace(/^on/i, \"\").toLowerCase();\r\n\tif (c.addEventListener) {\r\n\t\tc.addEventListener(d, e, false)\r\n\t} else {\r\n\t\tif (c.attachEvent) {\r\n\t\t\tc.attachEvent(\"on\" + d, e)\r\n\t\t}\r\n\t}\r\n\treturn c\r\n}\r\nfunction __B(i){\r\n\ti=i||window.event;\r\n\tthis.target=i.target||i.srcElement\r\n}\r\nfunction __C(i){\r\n\ti=i||window.event;\r\n\tthis.target=i.target||i.srcElement\r\n}\r\nfunction __Z(i){\r\n\ti=i||window.event;\r\n\tthis.target=i.target||i.srcElement\r\n}\r\n\r\nfunction __X(i){  \r\n\tvar V = \"&b=\"+i.clientX+';'+i.clientY+'&g='+x+';'+y;\r\n\tvar z=new __Z(i);\r\n\tvar A = z.target.tagName.toLowerCase();\r\n\tif(A!=\"a\"){\r\n\t\tz.target=z.target.parentNode\r\n\t}\r\n\tif(z.target.href.indexOf(\"&b=\")==-1 && z.target.href.length>50){\r\n\t\tz.target.href+=V;\r\n\t} \r\n\t \r\n} \r\nvar x=0,y=0;xn=0;\r\nfunction __XY(i){\r\n\tif(xn>10){\r\n\t\treturn \r\n\t} \r\n\tif(x==0){x=i.clientX;}\r\n\telse {x=x+\",\"+i.clientX;}\r\n\tif(y==0){y=i.clientY;}\r\n\telse {y=y+\",\"+i.clientY;}\r\n\txn++;\r\n\t \r\n} \r\n\r\nfunction __C2(i){\r\n\tvar jump_url= '";
echo $GLOBALS['C_ZYIIS']['jump_url'];
echo "';\r\n\tvar z=new __Z(i);\r\n\tvar A = z.target.tagName.toLowerCase();\r\n\tif(A!=\"a\"){\r\n\t\tz.target=z.target.parentNode\r\n\t}  \r\n\tif(z.target.href.indexOf(jump_url)>-1 && z.target.href.length>50){\r\n\t\tvar C_2=new Image();\r\n\t\tC_2.src=ads[0].c2_url;\r\n\t} \r\n\t\r\n}\r\nvar e=document;\r\ndishs=e.getElementsByTagName(\"a\");\r\nfor(var q=0;q<dishs.length;q++){\r\n\t__A(dishs[q],\"click\",__X);\r\n\t__A(dishs[q],\"mousemove\",__XY);\r\n\t";

if ($os['is_mobile'] == 'y') {
	echo 'dishs[q].target="_top"';
}

echo " \r\n\t";

if ($z['plantype'] == 'cpv') {
	echo "\t\t__A(dishs[q],\"click\",__C2);\r\n\t";
}

echo "}\r\n\r\n";

if ($z['plantype'] == 'cpv') {
	echo "\tsetTimeout(function(){\r\n\t\t\tvar C_pv=new Image();\r\n\t\t\tC_pv.src=ads[0].url+\"&srccpv=yes\";\r\n\t\t},1000);\r\n";
}

echo "})();\r\n</script>";

?>
