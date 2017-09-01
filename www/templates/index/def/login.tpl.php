<?php

if (!defined("IN_ZYADS")) {
	exit();
}

echo "<!doctype html>\r\n<html>\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<meta name=\"keywords\" content=\"广告联盟\" />\r\n<meta name=\"description\" content=\"\" />\r\n<meta name=\"generator\" content=\"zyiis.com v9\" />\r\n<meta name=\"author\" content=\"The YingZhong network Science and Technology CO.Ltd All rights reserved\" />\r\n<meta name=\"copyright\" content=\"2005-2018 YingZhong Inc.\" />\r\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n</head>\r\n<script src=\"";
echo WEB_URL;
echo "js/jquery/js/jquery-1.7.min.js\" type=\"text/javascript\"></script>\r\n</head>\r\n<link rel=\"stylesheet\" href=\"";
echo SRC_TPL_DIR;
echo "/css.css\">\r\n<title>登入 ";
echo $GLOBALS["C_ZYIIS"]["sitename"];
echo "</title>\r\n\r\n<body  >\r\n<div class=\"head\">\r\n  <div class=\"head_box\">\r\n    <div class=\"head_list\">\r\n      <h1 class=\"logo\"> <a href=\"";
echo WEB_URL;
echo "\" > <img src=\"";
echo SRC_TPL_DIR;
echo "/images/logo.gif\" border=\"0\"  > </a> </h1>\r\n      <h2 class=\"logo-title\"> 登入 </h2>\r\n    </div>\r\n  </div>\r\n</div>\r\n<div class=\"main\" style=\"padding-top:30px\">\r\n  <form   id=\"form1\" name=\"form1\" method=\"post\" action=\"";
echo url("index.postlogin");
echo "\" onSubmit=\"return doLogin()\">\r\n    <div class=\"left_list\">\r\n<div class=\"intro_box\"> \r\n";

if (get("key") == "login_timeout") {
	echo "<span style=\"font-size:14px; color:#ff0000\">登入超时，请重新登入</span>\r\n";
}
else {
	echo "欢迎光临\r\n";
}

echo "</div>\r\n      <div class=\"title_box\">\r\n        <div class=\"step item3\">\r\n          <ul>\r\n            <li class=\"on\" id=\"verifiy_username\"><span>登入</span></li>\r\n          </ul>\r\n        </div>\r\n      </div>\r\n      <ul class=\"form_list\" id=\"ul_username\" style=\"\">\r\n        <li style=\"position:relative;\">\r\n          <label><em class=\"red\">*</em> 用户名：</label>\r\n          <input type=\"text\" class=\"input_text\" name=\"username\" id=\"username\" placeholder=\"请输入用户名\" maxlength=\"20\">\r\n          <div class=\"tip\" id=\"txt_username_tip\"></div>\r\n        </li>\r\n        <li style=\"position:relative;\">\r\n          <label><em class=\"red\">*</em> 密码：</label>\r\n          <input type=\"password\" class=\"input_text\" name=\"password\" id=\"password\" placeholder=\"请输入密码\" maxlength=\"20\">\r\n          <div class=\"tip\" id=\"txt_password_tip\"></div>\r\n        </li>\r\n        <li style=\"position:relative;\">\r\n          <label><em class=\"red\">*</em> 验证码：</label>\r\n          <input type=\"text\" class=\"input_text\" name=\"checkcode\" id=\"img_code\" placeholder=\"请输入验证码\" maxlength=\"6\">\r\n          <img src=\"";
echo url("index.codeimage");
echo "\" align=\"absmiddle\"  title= \"看不清?请点击刷新验证码\"  onclick=\"this.src='";
echo url("index.codeimage?rand=");
echo "'+Math.random();\"  style= \"cursor:pointer;\"  />\r\n          <div class=\"tip\" id=\"img_code_tip\" >验证码不正确</div>\r\n        </li>\r\n        <li>\r\n          <label></label>\r\n          <input type=\"submit\" value=\"登入\" class=\"btn_css\" id=\"btn_username\">\r\n        </li>\r\n        <li class=\"noMar gray9\">\r\n          <label></label>\r\n          <a href=\"";
echo url("index.register");
echo "\" class=\"blue\">如需注册，请点击这里！</a> </li>\r\n      </ul>\r\n    </div>\r\n    <div class=\"right_login\">\r\n      <ul class=\"right_list\">\r\n        <li>没有由帐号？马上注册</li>\r\n        <li class=\"btn_box\">\r\n          <input type=\"button\" value=\"注 册\" class=\"btn_css\" onclick=\"window.open('";
echo url("index.register");
echo "')\">\r\n        </li>\r\n        <li>您还可以用其他方式直接登录：</li>\r\n        ";

if ($GLOBALS["C_ZYIIS"]["oauth_qq_app_id"]) {
	echo "        <li class=\"other\"> <a href=\"";
	echo url("oauth/qq.login");
	echo "\"><img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/qqonline.gif\"  border=\"0\" alt=\"腾讯QQ登录\"> QQ登录</a> </li>\r\n        ";
}

echo "      </ul>\r\n    </div> \r\n  </form>\r\n  <div class=\"clear\"></div>\r\n</div>\r\n<div class=\"footer\">Copyright ©";
echo date("Y", TIMES);
echo " All Rights Reserved</div>\r\n<script>\r\n function doLogin () {\r\n\t var username = $.trim($(\"#username\").val());\r\n     if (username == \"\") {\r\n        $(\"#txt_username_tip\").html('用户名不能为空').show();\t\r\n        return false;\r\n     }\r\n\t $(\"#txt_username_tip\").hide();\t\r\n\t var password = $.trim($(\"#password\").val());\r\n     if (password == \"\") {\r\n        $(\"#txt_password_tip\").html('密码不能为空').show();\t\r\n        return false;\r\n     }\r\n\t $(\"#txt_password_tip\").hide();\t\r\n\t var img_code = $.trim($(\"#img_code\").val());\r\n     if (img_code == \"\") {\r\n        $(\"#img_code_tip\").html('验证码不能为空').show();\t\r\n        return false;\r\n     }\r\n\t $(\"#img_code_tip\").hide();\t\r\n \t \r\n} \r\n</script> \r\n";

?>
