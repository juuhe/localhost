<?php

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<title>Admin Loign</title>\r\n<link rel=\"stylesheet\" href=\"";
echo SRC_TPL_DIR;
echo "/css/login.css\" media=\"all\" type=\"text/css\" />\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/js/jquery-1.7.min.js\"></script>\r\n\r\n<table width=\"100%\"border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"login\">\r\n  <tr>\r\n    <td> \r\n        <table width=\"470\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"  class=\"login_bg\" >\r\n          <tr>\r\n            <td  height=\"400\"  valign=\"top\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"login_form\">\r\n                <tr></tr>\r\n                <tr>\r\n                  <td class=\"tit\">用户名： <span class=\"tip\" id=\"text\"></span></td>\r\n                </tr>\r\n                <tr>\r\n                  <td><input type=\"text\" class=\"text users\" name=\"username\" id=\"username\" /></td>\r\n                </tr>\r\n                <tr>\r\n                  <td class=\"tit\"> 密码： </td>\r\n                </tr>\r\n                <tr>\r\n                  <td><input type=\"password\"  class=\"text password\" name=\"password\" id=\"password\" /></td>\r\n                </tr>\r\n                <tr>\r\n                  <td class=\"tit\"> 验证码：</td>\r\n                </tr>\r\n                <tr>\r\n                  <td><input type=\"text\"  class=\"text checkcode\" name=\"checkcode\" id=\"checkcode\" />\r\n                    <img src=\"";
echo url("admin/login.codeimage");
echo "\" align=\"absmiddle\"  title= \"看不清?请点击刷新验证码\"  onclick=\"this.src='";
echo url("admin/login.codeimage?rand=");
echo "'+Math.random();\"  style= \"cursor:pointer;\"/></td>\r\n                </tr>\r\n                <tr>\r\n                  <td>&nbsp;</td>\r\n                </tr>\r\n                <tr>\r\n                  <td height=\"90\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n                      <tr>\r\n                        <td> </td>\r\n                        <td><input type=\"button\" name=\"postlogin\"  class=\"btn\" id=\"postlogin\" value=\"登 入\" /></td>\r\n                      </tr>\r\n                    </table></td>\r\n                </tr>\r\n              </table></td>\r\n          </tr>\r\n          <tr></tr>\r\n        </table>\r\n      \r\n      <table width=\"470\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n        <tr>\r\n          <td height=\"60\" align=\"center\"><font color=\"#666666\">Powered by <a href=\"http://www.zyiis.com\" target=\"_blank\"><b><font color=\"#666666\">Zyiis</font></b></a> &nbsp;© ";
echo date("Y", TIMES);
echo "</font></td>\r\n        </tr>\r\n      </table></td>\r\n  </tr>\r\n</table>\r\n<script type=\"text/javascript\">\r\n\r\n";

if (!get("forced")) {
	echo " \r\nif($.browser.version =='6.0')\t{\r\n\twindow.location.href=\"";
	echo url("admin/login.ie6");
	echo "\"; \r\n}\r\n";
}

echo "document.onkeydown=function(e){ \r\n\te=e||window.event;\r\n\tif(e.keyCode==13){ \r\n\t\tPostis();\r\n\t} \r\n}\r\n$(document).ready(function() {\r\n\t$(\"#postlogin\").click(function(){ \r\n\t\tPostis();\r\n\t});\r\n});\r\nfunction Postis(){\r\nvar username = $(\"#username\").val();\r\n\t\tvar password = $(\"#password\").val();\r\n\t\tvar checkcode = $(\"#checkcode\").val();\r\n\t\tif(username.length=='0'){\r\n\t\t\thtml = '请输入用户名！';\r\n\t\t\t$('#text').html(html);\r\n\t\t\treturn false;\r\n\t\t}\r\n\t\telse if(password.length=='0'){\r\n\t\t\thtml = '请输入密码！';\r\n\t\t\t$('#text').html(html);\r\n\t\t\treturn false;\r\n\t\t}\r\n\t\telse if(checkcode ==''){\r\n\t\t\thtml = '验证码不能为空！';\r\n\t\t\t$('#text').html(html);\r\n\t\t\treturn false;\r\n\t\t\t  \r\n\t\t}  \r\n\t\t$('#text').html(\"正在登录......\");\r\n\t\t\r\n\t\t\r\n\t\tjQuery.ajax({  \r\n\t\t\ttype:\"post\",  \r\n\t\t\turl:\"";
echo url("admin/login.post");
echo "\",  \r\n\t\t\tdata:{ username: username, password: password,checkcode: checkcode },\r\n\t\t\tdataType:\"json\",  \r\n\t\t\tsuccess: function (data) {  \r\n\t\t\t\tif(data.err == ''){\r\n\t\t\t\t\tvar html =\"登入中...\";\r\n\t\t\t\t\ttop.location.href=data.url;\r\n\t\t\t\t}\r\n\t\t\t\telse if(data.err == 'login_err'){\r\n\t\t\t\t\tvar html = \"登录用户名或密码错误，请重新输入！\";\r\n\t\t\t\t}\r\n\t\t\t\telse if(data.err == 'login_lock'){\r\n\t\t\t\t\tvar html = \"无法登录，用户已被锁定！\";\r\n\t\t\t\t}\r\n\t\t\t\telse if(data.err == 'checkcode')\r\n\t\t\t\t{\r\n\t\t\t\t\tvar html = \"您输入的验证码错误，请重新输入！\";\r\n\t\t\t\t}\r\n\t\t\t\telse {\r\n\t\t\t\t\tvar html = \"出现系统错误，无法登陆！\";\r\n\t\t\t\t}\t\t \r\n\t\t\t\t \t$('#text').html(html);\r\n\t\t\t\t}  \r\n   \t \t});  \r\n\t\r\n\t\t\r\n}\r\n</script> \r\n";

?>
