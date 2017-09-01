<?php

if (!defined('IS_INSTALL')) {
	exit();
}

require_once tpl . '/header.php';
echo " \r\n  <div class=\"step\">\r\n    <ul>\r\n      <li class=\"on\"><em>1</em>检测环境</li>\r\n      <li class=\"on\"><em>2</em>登陆帐号</li>\r\n      <li class=\"on\"><em>3</em>创建数据</li>\r\n      <li class=\"current\"><em>4</em>完成安装</li>\r\n    </ul>\r\n  </div>\r\n  <div class=\"server\">\r\n   \r\n    <h3>安装完成</h3>\r\n    <table width=\"100%\" style=\"margin-top:30px\">\r\n      <tbody>\r\n        <tr>\r\n          <td width=\"160\" height=\"40\">恭喜您！您已成功安装完成。后台管理目录默认为“manage”为了安全，请修改下这个目录名称。&quot;install&quot;目录可以删除了</td>\r\n        </tr> \r\n        <tr>\r\n          <td height=\"40\"><a href=\"../manage\">马上登入后台管理</a></td>\r\n        </tr>\r\n        <tr>\r\n          <td height=\"40\">&nbsp;</td>\r\n        </tr>\r\n        \r\n        \r\n        \r\n      </tbody>\r\n    </table>\r\n    \r\n \r\n    \r\n  \r\n  \r\n</div>\r\n</div>\r\n \r\n";
require_once tpl . '/footer.php';

?>
