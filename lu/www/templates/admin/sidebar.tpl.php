<?php

echo "﻿<div class=\"r_search\">\r\n  <form action=\"";
echo url("admin/user.affiliate_list?searchtype=username");
echo "\" class=\"input-append\" method=\"post\">\r\n    <div style=\"float:left; margin-right:3px;\">\r\n      <input autocomplete=\"off\" name=\"search\" class=\"search_query\" size=\"16\" type=\"text\" placeholder=\"搜索站长名称\">\r\n    </div>\r\n    <div >\r\n      <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"absmiddle\" border=\"0\" >\r\n    </div>\r\n  </form>\r\n</div>\r\n<div id=\"side_accordion\" class=\"accordion\">\r\n  <div id=\"r_nav\">\r\n    <div class=\"nav_list nav_list_active\"><a href=\"";
echo url("admin/settings.get_list");
echo "\" class=\"list_a\"  id='nav_cy'><i class=\"icon-cy icon-black\"></i>基本设置</a>\r\n      <div class=\"inner\">\r\n      <!--  <p style=\"height:10px;\"></p>\r\n        <p ";
if ((RUN_CONTROLLER === "admin/settings") && (get("type") == "t_settings")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/settings.get_list?type=t_settings");
echo "\">系统设置</a></p>\r\n        <p ";
if ((RUN_ACTION === "admin/settings") && (get("type") == "t_server")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/settings.get_list?type=t_server");
echo "\">服务器设置</a></p>\r\n         <p ";
if ((RUN_CONTROLLER === "admin/settings") && (get("type") == "t_email")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/settings.get_list?type=t_email");
echo "\">邮箱设置</a></p>\r\n        \r\n         <p ";
if ((RUN_CONTROLLER === "admin/settings") && (get("type") == "t_pay")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/settings.get_list?type=t_pay");
echo "\">在线充值</a></p>\r\n         \r\n        <p ";
if ((RUN_CONTROLLER === "admin/settings") && (get("type") == "t_paylog")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/settings.get_list?type=t_paylog");
echo "\">财务相关</a></p>\r\n        \r\n        <p style=\"height:10px;\"></p>-->\r\n      </div>\r\n    </div>\r\n    \r\n    <div class=\"nav_list nav_list_active\"><a href=\"javascript:;\" class=\"list_a\"  id='nav_user'><i class=\"icon-user icon-black\"></i>会员管理</a>\r\n      <div class=\"inner\">\r\n        <p style=\"height:10px;\"></p>\r\n        <p ";

if (RUN_ACTION === "affiliate_list") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/user.affiliate_list");
echo "\">站长管理</a></p>\r\n        <p ";

if (RUN_ACTION === "advertiser_list") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/user.advertiser_list");
echo "\">广告商管理</a></p>\r\n        \r\n        <p ";

if (RUN_ACTION === "service_list") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/user.service_list");
echo "\">客服管理</a></p>\r\n        <p ";

if (RUN_ACTION === "commercial_list") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/user.commercial_list");
echo "\">商务管理</a></p>\r\n        \r\n        <p><a href=\"";
echo url("admin/group.get_list");
echo "\">站长用户组</a></p>\r\n        <p style=\"height:10px;\"></p>\r\n      </div>\r\n    </div> \r\n    <div class=\"nav_list nav_list_active\"><a href=\"javascript:;\" class=\"list_a\"  id='nav_plan'><i class=\"icon-plan icon-black\"></i>计划管理</a>\r\n      <div class=\"inner\">\r\n        <p style=\"height:10px;\"></p>\r\n        <p ";
if ((RUN_ACTION !== "add") && (RUN_CONTROLLER === "admin/plan")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/plan.get_list");
echo "\">计划列表</a></p>\r\n        <p ";
if ((RUN_ACTION === "add") && (RUN_CONTROLLER === "admin/plan")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/plan.add");
echo "\">新建计划</a></p>\r\n        <p ";

if (RUN_CONTROLLER === "admin/apply") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/apply.get_list");
echo "\">申请投放审核</a></p>\r\n        <p style=\"height:10px;\"></p>\r\n      </div>\r\n    </div> \r\n     <div class=\"nav_list nav_list_active\"><a href=\"javascript:;\" class=\"list_a\"  id='nav_ads'><i class=\"icon-ad icon-black\"></i>广告管理</a>\r\n      <div class=\"inner\">\r\n        <p style=\"height:10px;\"></p>\r\n        <p ";
if ((RUN_ACTION !== "add") && (RUN_CONTROLLER === "admin/ad") && !request("plantype")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/ad.get_list");
echo "\">所有广告列表</a></p>\r\n        \r\n        ";

foreach (explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
	echo " \r\n        \r\n        \r\n         <p ";
	if ((RUN_ACTION == "get_list") && (RUN_CONTROLLER === "admin/ad") && (request("plantype") == $t)) {
		echo "class=\"action\"";
	}

	echo "><a href=\"";
	echo url("admin/ad.get_list?plantype=" . $t);
	echo "\">";
	echo strtoupper($t);
	echo "广告管理</a></p>\r\n        \r\n         ";
}

echo " \r\n        \r\n        \r\n        <p ";
if ((RUN_ACTION === "add") && (RUN_CONTROLLER === "admin/ad")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/ad.add");
echo "\">新建广告</a></p>\r\n         \r\n        <p style=\"height:10px;\"></p>\r\n      </div>\r\n    </div> \r\n    \r\n   \r\n    <div class=\"nav_list nav_list_active\"><a href=\"javascript:;\" class=\"list_a\"  id='nav_site'><i class=\"icon-site icon-black\"></i>网站与广告位</a>\r\n      <div class=\"inner\">\r\n        <p style=\"height:10px;\"></p>\r\n        <p ";
if ((RUN_ACTION !== "add") && (RUN_CONTROLLER === "admin/site")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/site.get_list");
echo "\">网站管理</a></p>\r\n        <p ";

if (RUN_CONTROLLER === "admin/zone") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/zone.get_list");
echo "\">广告位管理</a></p>\r\n        <p ";
if ((RUN_CONTROLLER === "admin/site") && (RUN_ACTION === "add")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/site.add");
echo "\">手动增加网站</a></p>\r\n        <p style=\"height:10px;\"></p>\r\n      </div>\r\n    </div>\r\n   \r\n <div class=\"nav_list nav_list_active\"><a href=\"javascript:;\" class=\"list_a\"  id='nav_stats'><i class=\"icon-list-alt icon-black\"></i>数据报表</a>\r\n      <div class=\"inner\">\r\n        <p style=\"height:10px;\"></p>\r\n        \r\n        <p ";
if ((RUN_ACTION == "plan_list") && (RUN_CONTROLLER === "admin/stats")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/stats.plan_list?timerange=" . DAYS . "_" . DAYS . "");
echo "\">数据报表</a></p>\r\n       <p ";

if (RUN_CONTROLLER === "admin/ip") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/ip.get_list?timerange=" . DAYS . "_" . DAYS . "");
echo "\">实时IP</a></p>\r\n        <p ";

if (RUN_CONTROLLER === "admin/orders") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/orders.get_list");
echo "\">订单报表</a></p>\r\n        <p ";

if (RUN_CONTROLLER === "admin/import") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/import.add");
echo "\">导入数据</a></p>\r\n        \r\n        <p style=\"height:10px;\"></p>\r\n      </div>\r\n    </div>\r\n    <div class=\"nav_list nav_list_active\"><a href=\"javascript:;\" class=\"list_a\"  id='nav_trend'><i class=\"icon-trend icon-black\"></i>流量分析</a>\r\n      <div class=\"inner\">\r\n        <p style=\"height:10px;\"></p>\r\n        <p ";

if (RUN_CONTROLLER === "admin/trend") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/trend.get_list?timerange=" . DAYS . "_" . DAYS . "");
echo "\">趋势分析</a></p>\r\n        <p ";

if (RUN_CONTROLLER === "admin/search_trend") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/search_trend.get_list?timerange=" . DAYS . "_" . DAYS . "");
echo "\">搜索引擎</a></p>\r\n        <p ";
if ((RUN_CONTROLLER === "admin/client_trend") && (RUN_ACTION === "get_os")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/client_trend.get_os?timerange=" . DAYS . "_" . DAYS . "");
echo "\">客户端属性</a></p>\r\n        <p ";
if ((RUN_CONTROLLER === "admin/client_trend") && (RUN_ACTION === "get_city")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/client_trend.get_city?timerange=" . DAYS . "_" . DAYS . "");
echo "\">地域分布 </a></p>\r\n        <p style=\"height:10px;\"></p>\r\n      </div>\r\n    </div> \r\n    <div class=\"nav_list \"><a href=\"javascript:;\" class=\"list_a\" id='nav_article'><i class=\"icon-article icon-black\"></i>文章公告</a>\r\n      <div class=\"inner\">\r\n        <p style=\"height:10px;\"></p>\r\n        ";

foreach ($GLOBALS["article_type"] as $key => $val ) {
	echo "        <p ";

	if ($type == $key) {
		echo "class=\"action\"";
	}

	echo "><a href=\"";
	echo url("admin/article.get_list?type=" . $key);
	echo "\">";
	echo $val;
	echo "</a></p>\r\n        ";
}

echo "        <p ";
if ((RUN_ACTION === "add") && (RUN_CONTROLLER === "admin/article")) {
	echo "class=\"action\"";
}

echo "> <a href=\"";
echo url("admin/article.add");
echo "\">发布文章公告</a></p>\r\n        <p style=\"height:10px;\"></p>\r\n      </div>\r\n    </div>\r\n    \r\n      <div class=\"nav_list nav_list_active\"><a href=\"javascript:;\" class=\"list_a\"  id='nav_adtpl'><i class=\"icon-ad icon-black\"></i>广告模板管理</a>\r\n      <div class=\"inner\">\r\n        <p style=\"height:10px;\"></p>\r\n        <p ";

if (RUN_CONTROLLER === "admin/adtype") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/adtype.get_list");
echo "\">广告类型</a></p>\r\n        <p ";

if (RUN_CONTROLLER === "admin/adtpl") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/adtpl.get_list");
echo "\">广告模式</a></p>\r\n        <p ";

if (RUN_CONTROLLER === "admin/adstyle") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/adstyle.get_list");
echo "\">广告样式</a></p>\r\n        <p ";

if (RUN_CONTROLLER === "admin/specs") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/specs.get_list");
echo "\">广告尺寸</a></p>\r\n        <p style=\"height:10px;\"></p>\r\n      </div>\r\n    </div>\r\n    \r\n    <div class=\"nav_list nav_list_active\"><a href=\"javascript:;\" class=\"list_a\"  id='nav_other'><i class=\"icon-other icon-black\"></i>其它管理</a>\r\n      <div class=\"inner\">\r\n        <p style=\"height:10px;\"></p>\r\n        \r\n         <p ";

if (RUN_CONTROLLER === "admin/administrator") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/administrator.get_list");
echo "\">管理员管理</a></p>\r\n        <p ";

if (RUN_CONTROLLER === "admin/roles") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/roles.get_list");
echo "\">管理员角色</a></p>\r\n        <p class=\"divider\"></p>\r\n        <p ";

if (RUN_CONTROLLER === "admin/loginlog") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/loginlog.get_list");
echo "\">登入日志</a></p>\r\n        <p ";

if (RUN_CONTROLLER === "admin/syslog") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/syslog.get_list");
echo "\">操作日志</a></p>\r\n        <p class=\"divider\"></p>\r\n        \r\n         <p style=\"height:10px;\"></p>\r\n        <p ";
if ((RUN_CONTROLLER === "admin/msg") && (RUN_ACTION != "add")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/msg.get_list");
echo "\">消息管理</a></p>\r\n        <p ";
if ((RUN_CONTROLLER === "admin/msg") && (RUN_ACTION == "add")) {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/msg.add");
echo "\">发布消息</a></p>\r\n        <p class=\"divider\"></p>\r\n        \r\n        <p ";

if (RUN_CONTROLLER === "admin/gift") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/gift.get_list");
echo "\">积分兑换</a></p>\r\n        <p ";

if (RUN_CONTROLLER === "admin/giftlog") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/giftlog.get_list");
echo "\">积分兑换记录</a></p>\r\n        <p class=\"divider\"></p>\r\n        <p ";

if (RUN_CONTROLLER === "admin/class") {
	echo "class=\"action\"";
}

echo "><a href=\"";
echo url("admin/class.get_list");
echo "\">网站分类</a></p>\r\n        \r\n       \r\n        <p style=\"height:10px;\"></p>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n\t\r\n$('.list_a').on('click', function(option) {\r\n\t\t  \r\n\t    $('.inner').hide(\"\");\r\n\t\t$(this).parent().find('.inner').show();\r\n\t\t var nav_id = this.id;\r\n\t\t $.cookie('nav_id',nav_id);\r\n\t\t return;\r\n\t\t //\r\n\t    var pa = $(this).parent(); \r\n\t\tif(pa.find('.inner').is(\":hidden\")){\r\n\t\t\tpa.find('.inner').show(\"\");\r\n\t\t\t var nav_id = this.id;\r\n\t\t\t $.cookie(nav_id,'show'); \r\n\t\t }else{\r\n\t\t \tpa.find('.inner').hide(\"\");\r\n\t\t\t var nav_id = this.id;\r\n\t\t\t $.cookie(nav_id,'hide');\r\n\t\t }\r\n\t\t \r\n });\r\n\r\nvar nav_id = $.cookie('nav_id');   \r\nvar show = $(\"#\"+nav_id).parent();  \r\nshow.find('.inner').show();\r\n\r\n/*\t\t\r\n$('.list_a').on('click', function(option) {\r\n\t\t  \r\n\t\t $('.inner').hide(\"normal\");\r\n\t    var pa = $(this).parent(); \r\n\t\tif(pa.find('.inner').is(\":hidden\")){\r\n\t\t\tpa.find('.inner').show(\"\");\r\n\t\t\t var nav_id = this.id;\r\n\t\t\t $.cookie(nav_id,'show'); \r\n\t\t }else{\r\n\t\t \tpa.find('.inner').hide(\"\");\r\n\t\t\t var nav_id = this.id;\r\n\t\t\t $.cookie(nav_id,'hide');\r\n\t\t }\r\n\t\t \r\n });\r\n\r\n$.each($('.list_a'), function(i,val){    \r\n\tvar nav_s = $.cookie(this.id);\r\n\tif(nav_s == 'show'){\r\n\t\t$(this).parent().find('.inner').show();\r\n\t}\r\n});\r\n*/\r\n</script> \r\n";

?>
