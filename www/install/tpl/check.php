<?php

if (!defined('IS_INSTALL')) {
	exit();
}

require_once tpl . '/header.php';
echo "<script type=\"text/javascript\" src=\"../js/jquery/js/jquery-1.7.min.js\"></script>\r\n\r\n<div class=\"step\">\r\n  <ul>\r\n    <li class=\"current\"><em>1</em>检测环境</li>\r\n    <li class=\"\"><em>2</em>登陆帐号</li>\r\n    <li class=\"\"><em>3</em>创建数据</li>\r\n    <li><em>4</em>完成安装</li>\r\n  </ul>\r\n</div>\r\n<div class=\"server\">\r\n  <table width=\"100%\">\r\n    <tbody>\r\n      <tr>\r\n        <td class=\"td1\">环境检测</td>\r\n        <td class=\"td1\" width=\"25%\">推荐配置</td>\r\n        <td class=\"td1\" width=\"25%\">当前状态</td>\r\n        <td class=\"td1\" width=\"25%\">最低要求</td>\r\n      </tr>\r\n      <tr>\r\n        <td>授权文件</td>\r\n        <td>必须</td>\r\n        <td>";

if (lic()) {
	echo '<span class="correct_span">√</span>授权' . lic();
}
else {
	echo '<span class="error_span">×</span>授权文件未安装 ';
}

echo "</td>\r\n        <td>需安装授权文件</td>\r\n      </tr>\r\n      <tr>\r\n        <td>操作系统</td>\r\n        <td>类UNIX</td>\r\n        <td><span class=\"correct_span\">√</span> ";
echo PHP_OS;
echo "</td>\r\n        <td>不限制</td>\r\n      </tr>\r\n      <tr>\r\n        <td>PHP版本</td>\r\n        <td>=5.4.x</td>\r\n        <td>";

if (php_ver() == 'ok') {
	echo '<span class="correct_span">√</span>支持';
}
else {
	echo '<span class="error_span">×</span>' . php_ver() . '版本太低 ';
}

echo "</td>\r\n        <td>5.4.x</td>\r\n      </tr>\r\n      <tr>\r\n        <td>Zend Guard Loader</td>\r\n        <td>&gt;3.3.x</td>\r\n        <td>";

if (zend_ver() == 'ok') {
	echo '<span class="correct_span">√</span>支持';
}
else {
	echo '<span class="error_span">×</span>不支持 ' . zend_ver();
}

echo "</td>\r\n        <td>3.3.x</td>\r\n      </tr>\r\n      <tr>\r\n        <td>Mysql</td>\r\n        <td>&gt;5.x.x</td>\r\n        <td>";

if (is_mysql()) {
	echo '<span class="correct_span">√</span>支持';
}
else {
	echo '<span class="error_span">×</span>不支持';
}

echo "</td>\r\n        <td>5.x.x</td>\r\n      </tr>\r\n      <tr>\r\n        <td>GD库</td>\r\n        <td>必须</td>\r\n        <td>";

if (gd_version()) {
	echo '<span class="correct_span">√</span>' . gd_version();
}
else {
	echo '<span class="error_span">×</span>未安装';
}

echo "</td>\r\n        <td>必须</td>\r\n      </tr>\r\n      <tr>\r\n        <td>Mcrypt扩展</td>\r\n        <td>必须</td>\r\n        <td>";

if (is_mcrypt()) {
	echo '<span class="correct_span">√</span>支持';
}
else {
	echo '<span class="error_span">×</span>未安装扩展';
}

echo "</td>\r\n        <td>必须</td>\r\n      </tr>\r\n      <tr>\r\n        <td>DNS网络</td>\r\n        <td>必须</td>\r\n        <td>";

if (internet()) {
	echo '<span class="correct_span">√</span>支持';
}
else {
	echo '<span class="error_span">×</span>服务器不能解析域名';
}

echo "</td>\r\n        <td>必须</td>\r\n      </tr>\r\n    </tbody>\r\n  </table>\r\n  <table width=\"100%\">\r\n    <tbody>\r\n      <tr>\r\n        <td class=\"td1\">目录、文件权限检查</td>\r\n        <td class=\"td1\" width=\"25%\">当前状态</td>\r\n        <td class=\"td1\" width=\"25%\">所需状态</td>\r\n      </tr>\r\n      ";

foreach (get_ck_path() as $key => $path) {
	echo "      <tr>\r\n        <td>";
	echo $key;
	echo "</td>\r\n        <td>";

	if (is_wr($path)) {
		echo '<span class="correct_span">√</span>可写';
	}
	else {
		echo '<span class="error_span">×</span>没有写入权限';
	}

	echo "</td>\r\n        <td>可写</td>\r\n      </tr>\r\n      ";
}

echo "    </tbody>\r\n  </table>\r\n  <table width=\"100%\">\r\n    <tbody>\r\n      <tr>\r\n        <td class=\"td1\">函数名称</td>\r\n        <td class=\"td1\" width=\"25%\">检查结果</td>\r\n        <td class=\"td1\" width=\"25%\">建议</td>\r\n      </tr>\r\n      ";

foreach (get_x_function() as $key => $fun) {
	echo "      <tr>\r\n        <td>";
	echo $key;
	echo "</td>\r\n        <td>";

	if (function_exists($fun)) {
		echo '<span class="correct_span">√</span>支持';
	}
	else {
		echo '<span class="error_span">×</span>没有写入权限';
	}

	echo "</td>\r\n        <td>必须</td>\r\n      </tr>\r\n      ";
}

echo "    </tbody>\r\n  </table>\r\n  <div class=\"bottom \"> <a href=\"index.php?action=check\" class=\"btn\">重新检测</a><a href=\"index.php?action=db\" class=\"btn next\">下一步</a> </div>\r\n</div>\r\n</div>\r\n<script type=\"text/javascript\">\r\nvar error = \$('.error_span').html();\r\nif(error){\r\n\t\$('.next').hide();\r\n\t\$.get(\"index.php?action=check&type=error\");\r\n}else{\r\n\t\$.get(\"index.php?action=check&type=sun\");\r\n}\r\n</script>\r\n";
require_once tpl . '/footer.php';

?>
