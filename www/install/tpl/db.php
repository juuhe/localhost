<?php

if (!defined('IS_INSTALL')) {
	exit();
}

require_once tpl . '/header.php';
echo "<script type=\"text/javascript\" src=\"../js/jquery/js/jquery-1.7.min.js\"></script>\r\n<script type=\"text/javascript\" src=\"../js/jquery/lib/jquery-validation/jquery.validate.js\"></script>\r\n<script type=\"text/javascript\">\r\nfunction show_msg(msg) {\r\n\tdocument.getElementById('notice').innerHTML += msg + '<br />';\r\n\tdocument.getElementById('notice').scrollTop = 100000000;\r\n}\r\n</script>\r\n\r\n  <div class=\"step\">\r\n    <ul>\r\n      <li class=\"on\"><em>1</em>检测环境</li>\r\n      <li class=\"on\"><em>2</em>登陆帐号</li>\r\n      <li class=\"current\"><em>3</em>创建数据</li>\r\n      <li><em>4</em>完成安装</li>\r\n    </ul>\r\n  </div>\r\n  <div class=\"db_main\">\r\n  ";

if ($install_sql == 'y') {
	echo "     <div class=\"loading\">\r\n  <img src=\"images/pop_loading.gif\" width=\"16\" height=\"16\"> 正在安装中请稍等...</div>\r\n\t<div class=\"notice\" id=\"notice\">\r\n      \r\n  </div>\r\n  ";
	iae($querysql, $db);
	echo "\r\n  ";
}
else {
	echo "<div class=\"server\">\r\n    <form action=\"\" method=\"post\" id=\"form_b\">\r\n      <h3>数据库信息   ";

	if ($db_error) {
		echo '<span class="mgs_text">';
		echo $db_error;
		echo '</span>';
	}

	echo "</h3>\r\n    \r\n      <table width=\"100%\" style=\"margin-top:10px\">\r\n        <tbody>\r\n          <tr>\r\n            <td width=\"160\" height=\"30\">数据库服务器*</td>\r\n            <td><input name=\"dbhost\" type=\"text\" class=\"input_text\" id=\"dbhost\" value=\"";
	echo $_POST['dbhost'] ? $_POST['dbhost'] : 'localhost';
	echo "\" ></td>\r\n          </tr>\r\n          <tr>\r\n            <td height=\"30\">数据库服务器端口*</td>\r\n            <td><input name=\"dbport\" type=\"text\" class=\"input_text\" id=\"dbport\" value=\"";
	echo $_POST['dbport'] ? $_POST['dbport'] : '3306';
	echo "\" ></td>\r\n          </tr>\r\n          <tr>\r\n            <td height=\"30\">数据库用户名*</td>\r\n            <td><input name=\"dbuser\" type=\"text\" class=\"input_text\" id=\"dbuser\"  value=\"";
	echo $_POST['dbuser'];
	echo "\"></td>\r\n          </tr>\r\n          <tr>\r\n            <td height=\"30\">数据库密码*</td>\r\n            <td><input name=\"dbpassword\" type=\"password\" class=\"input_text\" id=\"dbpassword\" value=\"";
	echo $_POST['dbpassword'];
	echo "\"></td>\r\n          </tr>\r\n          <tr>\r\n            <td height=\"30\">数据库名*</td>\r\n            <td><input name=\"dbname\" type=\"text\" class=\"input_text\" id=\"dbname\" value=\"";
	echo $_POST['dbname'];
	echo "\"></td>\r\n          </tr>\r\n          ";

	if ($is_install == 'y') {
		echo "          <tr>\r\n            <td height=\"30\"  style=\"color:#F00\">强制安装 </td>\r\n            <td><input name=\"forceinstall\" type=\"checkbox\"   id=\"forceinstall\" value=\"1\" > \r\n            我要删除数据，强制安装<br> <span  style=\"color:#F00\">当前数据库已包含中易广告联盟系统，强制安装将覆盖原有数据！！！</span></td>\r\n          </tr>\r\n           ";
	}

	echo "          \r\n        </tbody>\r\n      </table>\r\n      <h3>管理人信息</h3>\r\n      <table width=\"100%\" style=\"margin-top:10px\">\r\n        <tbody>\r\n          <tr>\r\n            <td width=\"160\" height=\"30\">管理员帐号*</td>\r\n            <td><input name=\"admin\" type=\"text\" class=\"input_text\" id=\"admin\" value=\"";
	echo $_POST['admin'];
	echo "\"></td>\r\n          </tr>\r\n          <tr>\r\n            <td height=\"30\">密码*</td>\r\n            <td><input name=\"admin_pwd\" type=\"password\" class=\"input_text\" id=\"admin_pwd\" value=\"";
	echo $_POST['admin_pwd'];
	echo "\"></td>\r\n          </tr>\r\n          <tr>\r\n            <td height=\"30\">重复密码*</td>\r\n            <td><input name=\"admin_repwd\" type=\"password\" class=\"input_text\" id=\"admin_repwd\" value=\"";
	echo $_POST['admin_repwd'];
	echo "\"></td>\r\n          </tr>\r\n          <tr>\r\n            <td height=\"30\"></td>\r\n            <td>&nbsp;</td>\r\n          </tr>\r\n          <tr>\r\n            <td height=\"30\"></td>\r\n            <td><div class=\"bottom\" style=\"text-align: left!important;\"> <input class=\"btn\" name=\"\" type=\"submit\" value=\"下一步\"> </div></td>\r\n          </tr>\r\n        </tbody>\r\n      </table>\r\n     \r\n    </form>\r\n  </div>\r\n   ";
}

echo "</div>\r\n</div>\r\n<script>\r\n \$(\"#form_b\").validate({\r\n\t\t    errorClass :\"error\", \r\n\t\t\thighlight: function(element) {\r\n\t\t\t\t\t\$(element).closest('td').addClass(\"f_error\");\r\n\t\t\t\t},\r\n\t\t\tunhighlight: function(element) {\r\n\t\t\t\t\$(element).closest('td').removeClass(\"f_error\");\r\n\t\t\t},\r\n\t\t\trules: {\r\n                  dbhost: {\r\n\t\t\t\t   \trequired: true \r\n                   }, \r\n\t\t\t\t  dbport: {\r\n\t\t\t\t   \trequired: true \r\n                   }, \r\n\t\t\t\t dbuser: {\r\n\t\t\t\t   \trequired: true \r\n                 }, \r\n\t\t\t\t dbpassword: {\r\n\t\t\t\t   \trequired: true \r\n                   }, \r\n\t\t\t\t dbname: {\r\n\t\t\t\t   \trequired: true \r\n                   \t}, \r\n\t\t\t\t admin: {\r\n\t\t\t\t   \trequired: true \r\n                   \t}, \r\n\t\t\t\t admin_pwd: {\r\n\t\t\t\t   \trequired: true \r\n                   \t}, \r\n\t\t\t\t admin_repwd: {\r\n\t\t\t\t   \trequired: true,\r\n\t\t\t\t\tequalTo: \"#admin_pwd\"\r\n \r\n                   \t}\r\n\t\t\t\t },\r\n\t\t\tmessages: {\r\n\t\t\t\tdbhost:\"数据库服务器不能为空\" ,\r\n\t\t \t\tdbport:\"数据库端口不能为空\" ,\r\n\t\t\t\tdbuser:\"数据库用户名不能为空\",\r\n\t\t\t\tdbpassword:\"密码不能为空\" ,\r\n\t\t\t\tdbname:\"库名不能为空\"  ,\r\n\t\t\t\tadmin:\"管理员用户名不能空\"  ,\r\n\t\t\t\tadmin_pwd:\"管理员密码不能空\"  ,\r\n\t\t\t\tadmin_repwd:{ \r\n\t\t\t\t\trequired:\"请再一次输入密码！\",\r\n\t\t\t\t\tequalTo:\"两次输入的密码不一样！\"\r\n\t\t\t\t}  \r\n\t\t\t},\r\n\t \r\n\t\t\terrorElement: 'span',\r\n\t\t\t \r\n        });\r\n</script>\r\n";
require_once tpl . '/footer.php';

?>
