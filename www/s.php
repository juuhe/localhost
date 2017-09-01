<?php

echo "﻿(function() {\r\n\t";
if (preg_match('/(Googlebot|Msnbot|YodaoBot|Sosospider|Baiduspider|Sogou web spider|gosospider|Huaweisymantecspider|Gigabot|OutfoxBot)/i', $_SERVER['HTTP_USER_AGENT']) || ($_SERVER['HTTP_USER_AGENT'] == 'Mozilla/4.0')) {
	header('HTTP/1.1 403 Forbidden');
	exit();
}


$zoneid = (int) $_GET['id'];

if ($zoneid < 1) {
	exit('Null zid');
}

require './config.php';
require_once LIB_PATH . '/kernel.php';
require APP_PATH . '/ad/show.php';
$z = cache::get_zoneinfo($zoneid);

if (!$z) {
	exit('document.write(\'<!--nozone-->\');})();');
}
print_r($z);
deny::user_not_status($z);

$v = cache::get_view_adstyle($z['adstyleid']);
$aesinfo = show::fl_aes($z);
$get_ad = show::view_ad($z, $v['tpl']);
$os = show::os();

if ($os['is_mobile'] == 'y') {
	if (!$get_ad) {
		exit('document.write(\'<!--noads-->\');})();');
	}

	$os['name'] = 'pc';
	echo "var gmate = document.getElementsByTagName('meta'),isviewport=1;\r\n\t\t\t\t for(var i=0,len=gmate.length;i<len;i++){  \r\n\t\t\t\t\tif(gmate[i] && gmate[i].getAttribute('name') == 'viewport'){\r\n\t\t\t\t\t\tisviewport=0;\r\n\t\t\t\t\t}\r\n\t\t\t\t}\r\n\t\t\t\tif(isviewport){\r\n\t\t\t\t\tvar node =document.createElement('meta');\r\n\t\t\t\t\tnode.content='width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no' ;\r\n\t\t\t\t\tnode.name='viewport'; \r\n\t\t\t\t\tvar head=document.getElementsByTagName('head')[0];\r\n\t\t\t\t\thead.insertBefore(node,head.firstChild);\r\n\t\t\t\t}\r\n\t\t\t\t";
}
else if (!$get_ad) {
	exit('document.write(\'<!--noads-->\');})();');
}

echo "\r\n    var zone = ";
echo $z['codestyle'];
echo "; \r\n    var domain =  {jsurl:\"";
echo $GLOBALS['C_ZYIIS']['js_url'] . '' . WEB_URL;
echo '",imgurl:"';
echo $GLOBALS['C_ZYIIS']['img_url'] . '' . WEB_URL;
echo "\"};\r\n    \r\n    var __ua = navigator.userAgent.toLowerCase(), __B = {\r\n\tver : (__ua.match(/(?:rv|me|ra|ie)[\\/: ]([\\d.]+)/) || [0, \"0\"])[1],\r\n\topera : /opera/.test(__ua),\r\n\tmaxthon : /maxthon/.test(__ua),\r\n\ttheworld : /theworld/.test(__ua),\r\n\tqq : /qqbrowser/.test(__ua),\r\n\tsogou : /se /.test(__ua),\r\n\tliebao : /liebao/.test(__ua),\r\n\tfirefox : /mozilla/.test(__ua) && !/(compatible|webkit)/.test(__ua),\r\n\tchrome : /chrome|crios/.test(__ua),\r\n\tsafari : /webkit/.test(__ua),\r\n\tuc : /ucbrowser|ucweb|rv:1.2.3.4|uc/.test(__ua),\r\n\tie : /msie/.test(__ua) && !/opera/.test(__ua),\r\n\tios: !!__ua.match(/\\(i[^;]+;( U;)? CPU.+Mac OS X/),  \r\n\tandroid: /android|linux/.test(__ua),\r\n\tiphone: /iphone/.test(__ua),\r\n\tipad: /ipad/.test(__ua)\r\n};\r\nvar Base64 =  {  \r\n    k : \"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=\", \r\n    encode : function (input) {  \r\n        var output = \"\";  \r\n        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;  \r\n        var i = 0;  \r\n        input = Base64.B(input);  \r\n        while (i < input.length) {  \r\n            chr1 = input.charCodeAt(i++);  \r\n            chr2 = input.charCodeAt(i++);  \r\n            chr3 = input.charCodeAt(i++);  \r\n            enc1 = chr1 >> 2;  \r\n            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);  \r\n            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);  \r\n            enc4 = chr3 & 63;  \r\n            if (isNaN(chr2)) {  \r\n                enc3 = enc4 = 64;  \r\n            } else if (isNaN(chr3)) {  \r\n                enc4 = 64;  \r\n            }  \r\n            output = output +  \r\n            Base64.k.charAt(enc1) + Base64.k.charAt(enc2) +  \r\n            Base64.k.charAt(enc3) + Base64.k.charAt(enc4);  \r\n        }  \r\n        return output;  \r\n    } ,\r\n    B : function (string) {  \r\n        string = string.replace(/\\r\\n/g,\"\\n\");  \r\n        var utftext = \"\";  \r\n        for (var n = 0; n < string.length; n++) {  \r\n            var c = string.charCodeAt(n);  \r\n            if (c < 128) {  \r\n                utftext += String.fromCharCode(c);  \r\n            } else if((c > 127) && (c < 2048)) {  \r\n                utftext += String.fromCharCode((c >> 6) | 192);  \r\n                utftext += String.fromCharCode((c & 63) | 128);  \r\n            } else {  \r\n                utftext += String.fromCharCode((c >> 12) | 224);  \r\n                utftext += String.fromCharCode(((c >> 6) & 63) | 128);  \r\n                utftext += String.fromCharCode((c & 63) | 128);  \r\n            }  \r\n        }  \r\n        return utftext;  \r\n    }   \r\n} \r\nfunction __G(d, c) {\r\n\tc = c || window;\r\n\tif (\"string\" === typeof d || d instanceof String) {\r\n\t\treturn c.document.getElementById(d)\r\n\t} else {\r\n\t\tif (d && d.nodeName && (d.nodeType == 1 || d.nodeType == 9)) {\r\n\t\t\treturn d\r\n\t\t}\r\n\t}\r\n\treturn d\r\n}\r\nfunction __L(url,callback,zid){\r\n\tvar win = window, doc = document,U=__B,loadlist={\r\n\t\t\r\n\t},node=doc.createElement(\"script\"),head=doc.getElementsByTagName('head')[0];\tfunction clear(){\r\n\t\tnode.onload=node.onreadystatechange=node.onerror=null;\t\thead.removeChild(node);\t\thead=node=null;\t\t\r\n\t};\tfunction onLoad(){\r\n\t\tloadlist[url]=true;\t\tclear();\t\tif(callback)callback();\t\t\r\n\t}if(loadlist[url]){\r\n\t\tcallback();\t\treturn ;\t\t\r\n\t}if(U.ie&&(U.ver<9||(doc.documentMode&&doc.documentMode<9))){\r\n\t\tnode.onreadystatechange=function (){\r\n\t\t\tif(/loaded|complete/.test(node.readyState)){\r\n\t\t\t\tnode.onreadystatechange=null;\t\t\t\tonLoad();\t\t\t\t\r\n\t\t\t}\r\n\t\t};\t\t\r\n\t}else {\r\n\t\tif(U.ver>=10){\r\n\t\t\tnode.onerror=function (){\r\n\t\t\t\tsetTimeout(clear,0);\r\n\t\t\t\t\r\n\t\t\t};\t\t\tnode.onload=function (){\r\n\t\t\t\tsetTimeout(onLoad,0);\r\n\t\t\t\t\r\n\t\t\t};\t\t\t\r\n\t\t}else {\r\n\t\t\tnode.onerror=clear;\t\t\tnode.onload=onLoad;\t\t\t\r\n\t\t}\r\n\t}  \r\n\tnode.async=true;\t\r\n\tnode.src=url;\r\n\tif(zid) node.id= zid;\t\r\n\thead.insertBefore(node,head.firstChild);\t\r\n}\r\nfunction __E(a, f) {\r\n\tif (a.length && a.slice) {\r\n\t\tfor ( i = 0; i < a.length; i++) {\r\n\t\t\tswitch (typeof a[i]) {\r\n\t\t\t\tcase \"string\":\r\n\t\t\t\tcase \"function\":\r\n\t\t\t\t\tf(a[i]());\r\n\t\t\t\t\tbreak;\r\n\t\t\t\tdefault:\r\n\t\t\t\t\tbreak\r\n\t\t\t}\r\n\t\t}\r\n\t}\r\n}\r\n\r\nfunction __M(o, t) {\r\n\tif (!o || !t) {\r\n\t\treturn o\r\n\t}\r\n\tfor (var tem in t) {\r\n\t\to[tem] = t[tem];\r\n\t}\r\n\treturn o;\r\n}\r\nfunction __Gc(d, h) {\r\n\tvar c;\r\n\tvar h = h || window;\r\n\tvar g = h.document;\r\n\tvar e = new RegExp(\"(^| )\" + d + \"=([^;]*)(;|\\x24)\");\r\n\tvar f = e.exec(g.cookie);\r\n\tif (f) {\r\n\t\tc = f[2]\r\n\t}\r\n\t return c\r\n}\r\nfunction __Sc(e, f, d) {\r\n\td = d || {};\r\n\tvar c = d.expires;\r\n\tif (\"number\" == typeof d.expires) {\r\n      c = new Date();\r\n      c.setTime(c.getTime() + d.expires)\r\n     }\r\n     document.cookie = e + \"=\" + f + (d.path ? \"; path=\" + d.path : \"\") + (c ? \"; expires=\" + c.toGMTString() : \"\") + (d.domain ? \"; domain=\" + d.domain : \"\") + (d.secure ? \"; secure\" : \"\")\r\n}\r\nfunction __P() {\r\n\tvar win = window, doc = document, p = [];\r\n\tfunction r() {\r\n\t\tvar c;\r\n\t\ttry {\r\n\t\t\tc = win.opener ? win.opener.document.location.href : doc.referrer\r\n\t\t} catch (e) {\r\n\t\t\tc = doc.referrer\r\n\t\t}\r\n\t\tfunction K(r) {\r\n\t\t\tvar s = [\"wd\", \"p\", \"q\", \"keyword\", \"kw\", \"w\", \"key\", \"word\", \"query\", \"q1\", \"name\"];\r\n\t\t\tif (r != \"\" && r != null) {\r\n\t\t\t\tfor (var i = 0; i < s.length; i++) {\r\n\t\t\t\t\tvar re = new RegExp(\"[^1-9a-zA-Z]\" + s[i] + \"=\\([^&]*\\)\");\r\n\t\t\t\t\tvar kk = r.match(re);\r\n\t\t\t\t\tif (kk != null) {\r\n\t\t\t\t\t\treturn kk[1]\r\n\t\t\t\t\t}\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t\treturn \"\"\r\n\t\t}\r\n\t\tc = c ? {\r\n\t\t\tr : encodeURIComponent(c),\r\n\t\t\tk : encodeURIComponent(K(c))\r\n\t\t} : {\r\n\t\t\tr : encodeURIComponent(c)\r\n\t\t};\r\n\t\treturn c;\r\n\t}\r\n\r\n\tfunction u() {\r\n\t\tvar c;\r\n\t\ttry {\r\n\t\t\tc = win.top.document.location.href;\r\n\t\t} catch (e) {\r\n\t\t\tc = doc.location.href;\r\n\t\t}\r\n\t\treturn {\r\n\t\t\tu : encodeURIComponent(c)\r\n\t\t};\r\n\t}\r\n\tfunction sE(){\r\n\t\tvar e=0,m = navigator.mimeTypes,i;\r\n\t\tif (navigator.mimeTypes != null && navigator.mimeTypes.length > 0){\r\n\t\t\tfor (i in m) {\r\n\t\t\t\tif (m[i]['type'] == 'application/vnd.chromium.remoting-viewer') {\r\n\t\t\t\t\t e='1';\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t}\r\n\t\tif(e!='1'){\r\n\t\t\tvar _tk = \"track\" in document.createElement(\"track\"), _se = \"scoped\" in document.createElement(\"style\"), _vl = \"v8Locale\" in window;\r\n\t\t\tif (_tk && !_se && !_vl){  \r\n\t\t\t\te = '2';\r\n\t\t\t}\r\n\t\t\tif (_tk && _se && _vl){\r\n\t\t\t\te = '3';\r\n\t\t\t}\r\n\t\t}\r\n\t\treturn {\r\n\t\t\tse :e\r\n\t\t};\r\n\t}\r\n\tfunction j() {\r\n\t\treturn {\r\n\t\t\tj : navigator.javaEnabled() ? 1 : 0\r\n\t\t};\r\n\t}\r\n\r\n\tfunction p() {\r\n\t\treturn {\r\n\t\t\tp : navigator.plugins.length\r\n\t\t};\r\n\t}\r\n\r\n\tfunction m() {\r\n\t\treturn {\r\n\t\t\tm : navigator.mimeTypes.length\r\n\t\t};\r\n\t}\r\n\r\n\tfunction f() {\r\n\t\tvar v = 0;\r\n\t\tif (navigator.plugins && navigator.mimeTypes.length) {\r\n\t\t\tvar b = navigator.plugins[\"Shockwave Flash\"];\r\n\t\t\tif (b && b.description)\r\n\t\t\t\tv = b.description.replace(/([a-zA-Z]|\\s)+/, \"\").replace(/(\\s)+r/, \".\")\r\n\t\t} else if (__B.ie && !window.opera) {\r\n\t\t\tvar c = null;\r\n\t\t\ttry {\r\n\t\t\t\tc = new ActiveXObject(\"ShockwaveFlash.ShockwaveFlash.7\")\r\n\t\t\t} catch (e) {\r\n\t\t\t\tvar a = 0;\r\n\t\t\t\ttry {\r\n\t\t\t\t\tc = new ActiveXObject(\"ShockwaveFlash.ShockwaveFlash.6\");\r\n\t\t\t\t\ta = 6;\r\n\t\t\t\t\tc.AllowScriptAccess = \"always\"\r\n\t\t\t\t} catch (e) {\r\n\t\t\t\t\tif (a == 6)\r\n\t\t\t\t\t\treturn a.toString()\r\n\t\t\t\t}\r\n\t\t\t\ttry {\r\n\t\t\t\t\tc = new ActiveXObject(\"ShockwaveFlash.ShockwaveFlash\")\r\n\t\t\t\t} catch (e) {\r\n\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\t\tif (c != null) {\r\n\t\t\t\tvar a = c.GetVariable(\"\$version\").split(\" \")[1];\r\n\t\t\t\tv = a.replace(/,/g, \".\")\r\n\t\t\t}\r\n\t\t}\r\n\t\treturn {\r\n\t\t\tf : v\r\n\t\t}\r\n\t}\r\n\r\n\tfunction res() {\r\n\t\tvar D = screen, v = D.width + \"x\" + D.height;\r\n\t\treturn {\r\n\t\t\tres : v\r\n\t\t}\r\n\t}\r\n\r\n\tfunction t() {\r\n\t\tvar v = document.title;\r\n\t\treturn {\r\n\t\t\tt : encodeURIComponent(v)\r\n\t\t}\r\n\t}\r\n\r\n\tfunction l() {\r\n\t\tvar v = navigator.browserLanguage || navigator.language;\r\n\t\treturn {\r\n\t\t\tl : v\r\n\t\t}\r\n\t}\r\n\r\n\tfunction c() {\r\n\t\tvar v = navigator.cookieEnabled;\r\n\t\tv = v ? 1 : 0;\r\n\t\treturn {\r\n\t\t\tc : v\r\n\t\t}\r\n\t}\r\n\r\n\tfunction H() {\r\n\t\treturn document.body && {\r\n\t\t\th : document.body.clientHeight\r\n\t\t};\r\n\t}\r\n\t\r\n\tvar b = {};\r\n\t__E([j, p, m, f, r, u, res, t, l, c, H,sE], function(a) {\r\n\t\t__M(b, a)\r\n\t});\r\n\tfor (var e in b) {\r\n\t\tp.push(e + \"=\" + b[e]);\r\n\t}\r\n\treturn Base64.encode(p.join(\"&\"));\r\n}\r\nfunction __A(c, d, e) {\r\n\tc = __G(c);\r\n\td = d.replace(/^on/i, \"\").toLowerCase();\r\n\tif (c.addEventListener) {\r\n\t\tc.addEventListener(d, e, false)\r\n\t} else {\r\n\t\tif (c.attachEvent) {\r\n\t\t\tc.attachEvent(\"on\" + d, e)\r\n\t\t}\r\n\t}\r\n\treturn c\r\n}\r\nfunction __UA(c, d, e) {\r\n    c = __G(c);\r\n    d = d.replace(/^on/i, \"\").toLowerCase();\r\n    if (c.removeEventListener) {\r\n        c.removeEventListener(d, e, false)\r\n     } else {\r\n        if (c.detachEvent) {\r\n       \t\tc.detachEvent(\"on\" + d, e)\r\n        }\r\n     }\r\n    return c\r\n}\r\nfunction __CL(){\r\n\tif(!window._________z) {\r\n\t\t\twindow._________z = true;\r\n\t\t\t__L(\"http://cloud.zyiis.net/v.js?";
echo base64_encode($aesinfo);
echo "\",'','zy_c');\r\n\t}\r\n}\r\nfunction __LC() {\r\n\t\tvar c;\r\n\t\ttry {\r\n\t\t\tc = window.top.document.location.host;\r\n\t\t} catch (e) {\r\n\t\t\tc = document.location.host;\r\n\t\t}\r\n\t\treturn Base64.encode(c);\r\n}\r\nfunction __IH(el, where, html) {\r\n  if (!el) {\r\n  \treturn false;\r\n  }\r\n  where = where.toLowerCase();\r\n  if (el.insertAdjacentHTML) {\r\n  \tel.insertAdjacentHTML(where, html);\r\n  } else {\r\n  \tvar range = el.ownerDocument.createRange(),\r\n  \t\tfrag = null;\r\n  \t\r\n  \tswitch (where) {\r\n  \t\tcase \"beforebegin\":\r\n  \t\t\trange.setStartBefore(el);\r\n  \t\t\tfrag = range.createContextualFragment(html);\r\n  \t\t\tel.parentNode.insertBefore(frag, el);\r\n  \t\t\treturn el.previousSibling;\r\n  \t\tcase \"afterbegin\":\r\n  \t\t\tif (el.firstChild) {\r\n\t\t\t    range.setStartBefore(el.firstChild);\r\n\t\t\t    frag = range.createContextualFragment(html);\r\n\t\t\t    el.insertBefore(frag, el.firstChild);\r\n  \t\t\t} else {\r\n\t\t\t    el.innerHTML = html;\r\n  \t\t\t}\r\n  \t\t\treturn el.firstChild;\r\n  \t\tcase \"beforeend\":\r\n  \t\t\tif (el.lastChild) {\r\n\t\t\t    range.setStartAfter(el.lastChild);\r\n\t\t\t    frag = range.createContextualFragment(html);\r\n\t\t\t    el.appendChild(frag);\r\n  \t\t\t} else {\r\n\t\t\t    el.innerHTML = html;\r\n  \t\t\t}\r\n  \t\t\treturn el.lastChild;\r\n  \t\tcase \"afterend\":\r\n  \t\t\trange.setStartAfter(el);\r\n  \t\t\tfrag = range.createContextualFragment(html);\r\n  \t\t\tel.parentNode.insertBefore(frag, el.nextSibling);\r\n  \t\t\treturn el.nextSibling;\r\n  \t}\r\n  }\r\n}\r\nfunction pvstas(pvid){  \r\n\t\r\n\tvar aid ,pid;\r\n\tif(pvid.aid.length>1){\r\n\t \taid = pvid.aid.join(\",\").match( /([^,]+)(?!.*\x01)/ig);\r\n\t \tpid = pvid.pid.join(\",\").match( /([^,]+)(?!.*\x01)/ig);\r\n\t}else {\r\n\t\taid = pvid.aid;\r\n\t\tpid = pvid.pid;\r\n\t}\r\n\t";
$runm = ($z['pvstep'] ? $z['pvstep'] : $GLOBALS['C_ZYIIS']['pv_step']);
$rand = rand(1, $runm);
if (($rand == 1) && $GLOBALS['C_ZYIIS']['pv_step']) {
	echo 'var url = domain.jsurl+"stats.php?adsid="+aid+"&planid="+pid+"&uid=' . $z['uid'] . '&siteid=&plantype=' . $z['plantype'] . '&zoneid=' . $z['zoneid'] . '&adtplid=' . $z['adtplid'] . '&sep=' . $runm . "\"; \r\n\t__L(url);";
}

echo "}\r\nvar ifsrc = domain.jsurl + \"v.php?id=\" + zone.zoneid + '&p=' + __P()+'&l='+__LC(); \r\nfunction __I() {\r\n\t\tvar i = '<iframe src=\"' + ifsrc + '\" width=\"' + zone.width + '\" height=\"' + zone.height + '\" marginheight=\"0\" scrolling=\"no\" frameborder=\"0\" allowtransparency=\"true\"></iframe>'; \r\n\t\treturn i; \r\n\t\t} \r\nfunction __LS() {\r\n\t\tvar url = domain.jsurl + \"v.php?id=\" + zone.zoneid + '&' + __P();\r\n\t\t__L(url);\r\n\t\t} \t\t\r\nfunction __S() {\r\nif(!document.body && !__G('_nobody')){\r\n\tdocument.write(\"<a id='_nobody' style='display: none'>none</a>\");\r\n};\r\nvar pvid={pid:[],aid:[]};  \r\n\r\n\r\n\t";

switch ($v['tpl']['tpltype']) {
case 'iframe':
	echo "var a = __I();\r\n\t\t\tdocument.write(a);";
	break;

case 'script_iframe':
	echo $v['tpl']['viewjs'] . $v['style']['viewjs'];
	break;

case 'script':
	echo 'var ads = ' . json_encode($get_ad) . ';var config = ' . $z['codestyle'] . ';';
	echo "for (key in ads) {\r\n\t\t\t\t\t\t\t\t\tads[key].url = ads[key].url+'&p='+ __P();\r\n\t\t\t\t\t\t\t\t}";
	echo $v['tpl']['viewjs'] . $v['style']['viewjs'];
	break;
}

if ($GLOBALS['C_ZYIIS']['zy_cloud'] < 2) {
	echo "__CL();\t \r\n";
}

echo "}\r\n__S();\r\n})();";

?>
