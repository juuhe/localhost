<?php

echo "﻿";

if (!defined("IN_ZYADS")) {
	exit();
}

TPL::display("header");
echo "\r\n<div id=\"left\">\r\n  ";
TPL::display("left");
echo "</div>\r\n<div id=\"main\" style=\"padding-top:10px\">\r\n  <div class=\"mt30\" style=\" height:50px; \">\r\n    <button class=\"btn btn-primary ";

if ($status == "0") {
	echo "btn-red";
}

echo "\" style=\"margin-right:10px\" onclick=\"javascript:window.location.href='";
echo url("service/users.get_list?status=0");
echo "'\">待审人员</button>\r\n    <button class=\"btn btn-primary ";

if ($status == "2") {
	echo "btn-red";
}

echo "\" style=\"margin-right:10px\" onclick=\"javascript:window.location.href='";
echo url("service/users.get_list?status=2");
echo "'\">已审人员</button>\r\n    <button class=\"btn btn-primary ";

if ($status == "4") {
	echo "btn-red";
}

echo "\" style=\"margin-right:10px\" onclick=\"javascript:window.location.href='";
echo url("service/users.get_list?status=4");
echo "'\">已锁定人员</button>\r\n    <form action=\"";
echo url("service/users.get_list");
echo "\" method=\"post\" class=\"form-horizontal\" style=\"padding-left:0px\">\r\n      <div  class=\"controls\" style=\"margin-left:0px;\">\r\n        <input name=\"searchval\" type=\"text\" class=\"input-27\" style=\"width:120px\" value=\"";
echo $searchval;
echo "\"/>\r\n        <select name=\"searchtype\" style=\"padding:5px; width:100px\">\r\n          <option value=\"username\" ";

if ($searchtype == "username") {
	echo "selected";
}

echo ">会员名称</option>\r\n          <option value=\"uid\" ";

if ($searchtype == "uid") {
	echo "selected";
}

echo ">会员ID</option>\r\n        </select>\r\n        <select name=\"sortingm\"   id=\"sortingm\" style=\"padding:5px; width:100px\">\r\n          <option value=\"DESC\" ";

if ($sortingm == "DESC") {
	echo "selected";
}

echo ">降序</option>\r\n          <option value=\"ASC\" ";

if ($sortingm == "ASC") {
	echo "selected";
}

echo ">升序</option>\r\n        </select>\r\n        <select name=\"sortingtype\"  id=\"sortingtype\" style=\"padding:5px; width:100px\">\r\n          <option value=\"money\" ";

if ($sortingtype == "money") {
	echo "selected";
}

echo ">总余额</option>\r\n          <option value=\"daymoney\" ";

if ($sortingtype == "daymoney") {
	echo "selected";
}

echo ">日余额</option>\r\n          <option value=\"weekmoney\" ";

if ($sortingtype == "weekmoney") {
	echo "selected";
}

echo ">周余额</option>\r\n          <option value=\"monthmoney\" ";

if ($sortingtype == "monthmoney") {
	echo "selected";
}

echo ">月余额</option>\r\n        </select>\r\n        <button class=\"btn btn-primary\" style=\"margin-right:10px\">搜索</button>\r\n      </div>\r\n    </form>\r\n  </div>\r\n  <div class=\"box\" style=\"margin-top:30px\">\r\n    <div class=\"box-title\">\r\n      <h3><i class=\"icon-table\"></i>会员管理</h3>\r\n    </div>\r\n    <div class=\"box-content\">\r\n      <table class=\"table\">\r\n        <thead>\r\n          <tr>\r\n            <th>会员ID</th>\r\n            <th>会员名称</th>\r\n            <th>总余额</th>\r\n            <th>日余额</th>\r\n            <th>周余额</th>\r\n            <th>月余额</th>\r\n            <th>下线余额</th>\r\n            <th>下线人数</th>\r\n            <th width=\"100\">QQ</th>\r\n            <th width=\"100\">状态</th>\r\n            <th width=\"120\">操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody>\r\n          ";

foreach ((array) $get_user_list as $u ) {
	echo "          <tr >\r\n            <td>";
	echo $u["uid"];
	echo "</td>\r\n            <td><a href=\"";
	echo url("service/users.glogin?uid=" . $u["uid"]);
	echo "\" title=\"登入到会员后台\" target=\"_blank\">";
	echo $u["username"];
	echo "</a></td>\r\n            <td>";
	echo 0 < $u["money"] ? round($u["money"], 2) : 0;
	echo "</td>\r\n            <td>";
	echo 0 < $u["daymoney"] ? round($u["daymoney"], 2) : 0;
	echo "</td>\r\n            <td>";
	echo 0 < $u["weekmoney"] ? round($u["weekmoney"], 2) : 0;
	echo "</td>\r\n            <td>";
	echo 0 < $u["monthmoney"] ? round($u["monthmoney"], 2) : 0;
	echo "</td>\r\n            <td>";
	echo 0 < $u["xmoney"] ? round($u["xmoney"], 2) : 0;
	echo "</td>\r\n            <td>";
	echo $sumrecommend;
	echo "</td>\r\n            <td>";
	echo $u["qq"];
	echo "</td>\r\n            <td>";

	switch ($u["status"]) {
	case 0:
		echo "<span class=\"notification error_bg\">待审</span>";
		break;

	case 1:
		echo "<span class=\"notification error_bg\">邮件激活</span>";
		break;

	case 2:
		echo "<span class=\"notification info_bg\">活动</span>";
		break;

	case 3:
		echo "<span class=\"notification error_bg\">拒绝</span>";
		break;

	case 4:
		echo "<span class=\"notification error_bg\">锁定</span>";
	}

	echo "            </td>\r\n            <td><span class=\"hidden-480\"><a href=\"";
	echo url("service/users.unlock?uid=" . $u["uid"]);
	echo "\" onclick=\"return confirm('确认审核通过吗')\">审核</a> <a href=\"";
	echo url("service/users.lock?uid=" . $u["uid"]);
	echo "\" onclick=\"return confirm('确认锁定吗')\">锁定</a></span></td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n";

?>
