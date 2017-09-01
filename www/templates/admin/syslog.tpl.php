<?php

if (!defined("IN_ZYADS")) {
	exit();
}

TPL::display("header");
echo "\r\n<link rel=\"stylesheet\" href=\"";
echo SRC_TPL_DIR;
echo "/css/rating.css\" media=\"all\" type=\"text/css\" />\r\n \r\n  \r\n<div id=\"sidebar\">\r\n  ";
TPL::display("sidebar");
echo "</div>\r\n<div id=\"main-content\">\r\n  \r\n  <div class=\"row-fluid \" >\r\n     <h3 class=\"heading\"> 操作日志 </h3>\r\n    <div class=\"tab users\">\r\n      <div class=\"tab-header-right  left\"> <a href=\"";
echo url("admin/syslog.get_list");
echo "\" class=\"tab-btn  list ";

if ($type === "") {
	echo "tab-state-active";
}

echo "\">全部列表</a> <a href=\"";
echo url("admin/syslog.get_list?type=del");
echo "\" class=\"tab-btn tab_del ";

if ($type === "del") {
	echo "tab-state-active";
}

echo "\"> 删除动作</a> <a href=\"";
echo url("admin/syslog.get_list?type=add_post");
echo "\" class=\"tab-btn tab_new ";

if ($type === "add") {
	echo "tab-state-active";
}

echo "\"> 新建动作</a> <a href=\"";
echo url("admin/syslog.get_list?type=update_post");
echo "\" class=\"tab-btn tab_edit ";

if ($type === "edit") {
	echo "tab-state-active";
}

echo "\">修改动作</a> <a href=\"";
echo url("admin/syslog.get_list?type=unlock");
echo "\" class=\"tab-btn unlock ";

if ($type === "unlock") {
	echo "tab-state-active";
}

echo "\"> 激活动作</a> <a href=\"";
echo url("admin/syslog.get_list?type=lock");
echo "\" class=\"tab-btn lock ";

if ($type === "lock") {
	echo "tab-state-active";
}

echo "\">锁定动作</a></div>\r\n    </div>\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"row\">\r\n        \r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\"  style=\"text-align:left\">\r\n            <form method=\"post\">\r\n              搜索：\r\n              <select name=\"searchtype\">\r\n                <option value=\"username\" ";

if ($searchtype == "username") {
	echo "selected";
}

echo ">操作人员</option>\r\n\t\t\t\t <option value=\"ip\" ";

if ($searchtype == "ip") {
	echo "selected";
}

echo ">操作IP</option>\r\n              </select>\r\n              <input type=\"text\" class=\"input_text span30\" name=\"search\" value=\"";
echo $search;
echo "\">\r\n              <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n            </form>\r\n          </div>\r\n        </div>\r\n      </div>\r\n\t  \r\n\t   \r\n      <table id=\"dt_inbox\" class=\"dataTable\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th>操作人员</th>\r\n            <th>操作IP</th>\r\n            <th>模块</th>\r\n            <th>动作</th>\r\n            <th style=\"width:40%\">内容</th>\r\n            <th>操作时间</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $syslog as $log ) {
	echo "          <tr class=\"unread odd\">\r\n            <td>";
	echo $log["username"];
	echo "</td>\r\n            <td>";
	echo $log["ip"] . "<br>" . convert_ip($log["ip"]);
	echo "</td>\r\n            <td>";

	foreach ((array) $ctl as $key => $val ) {
		if ($val["controller"] == $log["controller"]) {
			echo $key;
		}
	}

	echo "</td>\r\n            <td>\r\n            \r\n            \r\n\t\t\t";
	echo $ac[$log["action"]];
	echo "            \r\n            </td>\r\n            <td><textarea name=\"\" style=\"width:300px; height:100px\">";
	echo $log["content"];
	echo "</textarea>              </td>\r\n            <td>";
	echo $log["time"];
	echo "</td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n\t  \r\n\t  \r\n\t  \r\n\t  \r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo " \r\n";

?>
