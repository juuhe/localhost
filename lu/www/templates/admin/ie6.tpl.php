<?php

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<style>\r\nbody {\r\n\tbackground: url(";
echo SRC_TPL_DIR;
echo "/images/ie6/bg.png) repeat;\r\n\tfont-family: microsoft yahei, Tahoma, Verdana, 宋体;\r\n}\r\n \r\n</style>\r\n<title>升级浏览器</title>\r\n</head>\r\n\r\n<body>\r\n<table width=\"960\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:100px\">\r\n  <tr>\r\n    <td height=\"100\" align=\"center\"> <img src=\"";
echo SRC_TPL_DIR;
echo "/images/ie6/logo.jpg\"   /></td>\r\n  </tr>\r\n  <tr>\r\n    <td><table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n      <tr>\r\n        <td><img src=\"";
echo SRC_TPL_DIR;
echo "/images/ie6/tit.jpg\"    /></td>\r\n        <td><span  style=\"color:#3E414A\">您的浏览器版本太低啦~联盟后台管理中心已经不支持IE6了，建议您对浏览器进行升级</span></td>\r\n      </tr>\r\n    </table></td>\r\n  </tr>\r\n  <tr>\r\n    <td height=\"110\" align=\"center\"><a href=\"";
echo url("admin/login.start?forced=yes");
echo "\">强行进入</a></td>\r\n  </tr>\r\n  <tr>\r\n    <td height=\"50\" align=\"center\"><img src=\"";
echo SRC_TPL_DIR;
echo "/images/ie6/f.jpg\" /></td>\r\n  </tr>\r\n  <tr>\r\n    <td><table border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:20px\">\r\n      <tr>\r\n        <td width=\"120\" align=\"center\"><img src=\"";
echo SRC_TPL_DIR;
echo "/images/ie6/chrome.jpg\" /></td>\r\n        <td width=\"120\" align=\"center\"><img src=\"";
echo SRC_TPL_DIR;
echo "/images/ie6/firefox.jpg\"  /></td>\r\n        <td width=\"120\" align=\"center\"><img src=\"";
echo SRC_TPL_DIR;
echo "/images/ie6/ie.jpg\"  /></td>\r\n        <td width=\"120\" align=\"center\"><img src=\"";
echo SRC_TPL_DIR;
echo "/images/ie6/sogou.jpg\"  /></td>\r\n        <td width=\"150\" align=\"center\"><img src=\"";
echo SRC_TPL_DIR;
echo "/images/ie6/360.jpg\"  /></td>\r\n        </tr>\r\n      <tr>\r\n        <td height=\"40\" align=\"center\"><a href=\"http://www.baidu.com/s?wd=Chrome&amp;ie=UTF-8\" target=\"_blank\">Chrome</a></td>\r\n        <td align=\"center\"><a href=\"http://www.firefox.com.cn/\" target=\"_blank\">Firefox</a></td>\r\n        <td align=\"center\"><a href=\"http://www.baidu.com/s?wd=ie浏览器&amp;ie=utf-\" target=\"_blank\">IE7+</a></td>\r\n        <td align=\"center\"><a href=\"http://ie.sogou.com/index.html\" target=\"_blank\">搜狗浏览器</a></td>\r\n        <td align=\"center\"><a href=\"http://se.360.cn/\" target=\"_blank\">360(极速模式)</a></td>\r\n        </tr>\r\n    </table></td>\r\n  </tr>\r\n</table>\r\n \r\n</body>\r\n</html>";

?>
