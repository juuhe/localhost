<?php

if (!defined("IN_ZYADS")) {
	exit();
}

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n<title>管理员后台 ";
echo $GLOBALS["C_ZYIIS"]["sitename"];
echo "</title>\r\n<link rel=\"stylesheet\" href=\"";
echo SRC_TPL_DIR;
echo "/css/style.css\" media=\"all\" type=\"text/css\" />\r\n<link rel=\"stylesheet\" href=\"";
echo SRC_TPL_DIR;
echo "/css/modal.css\" media=\"all\" type=\"text/css\" />\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/js/jquery-1.7.min.js\"></script>\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/lib/jquery-validation/jquery.validate.js\"></script>\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/lib/cookie/jquery.cookie.js\"></script>\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/function.js\"></script>\r\n\r\n</head>\r\n\r\n<div id=\"nav\"> <a class=\"logo\" href=\"";
echo url("index");
echo "\" target=\"_blank\"> <i class=\"icon-home icon-white\" style=\"margin-top: -2px;vertical-align: text-top;\" ></i><span>中易广告联盟系统</span><span class=\"vs\"></span> </a>\r\n  <ul class=\"nav_m\"  >\r\n    <li class=\"dropdown\"> <a  class=\"dropdown-toggle\" href=\"";
echo url("admin/user.affiliate_list");
echo "\"><i class=\"icon-user icon-white\"></i><span>会员管理</span><b class=\"caret\"></b></a> <i class=\"tpm\"></i>\r\n      <ul class=\"dropdown-menu\">\r\n        <li><a href=\"";
echo url("admin/user.affiliate_list");
echo "\">站长管理</a></li>\r\n        <li><a href=\"";
echo url("admin/user.advertiser_list");
echo "\">广告商管理</a></li>\r\n\t\t  <li><a href=\"";
echo url("admin/user.service_list");
echo "\">客服管理</a></li>\r\n        <li><a href=\"";
echo url("admin/user.commercial_list");
echo "\">商务管理</a></li>\r\n          <li><a href=\"";
echo url("admin/group.get_list");
echo "\">站长用户组</a></li>\r\n        <li class=\"menu_s\"><a href=\"javascript:;\"  class=\"tab-btn add add_user\"  >新建一个会员</a></li>\r\n      </ul>\r\n    </li>\r\n    <li class=\"dropdown\"> <a  class=\"dropdown-toggle\" href=\"";
echo url("admin/plan.get_list");
echo "\"><i class=\"icon-plan icon-white\"></i><span>推广计划</span><b class=\"caret\"></b></a> <i class=\"tpm\"></i>\r\n      <ul class=\"dropdown-menu\">\r\n        <li><a href=\"";
echo url("admin/plan.get_list");
echo "\">所有计划</a></li>\r\n        ";

foreach (explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
	echo "        <li><a href=\"";
	echo url("admin/plan.get_list?plantype=" . $t);
	echo "\">";
	echo strtoupper($t);
	echo "推广计划</a></li>\r\n        ";
}

echo "        <li><a href=\"";
echo url("admin/plan.add");
echo "\">新建计划</a></li>\r\n        <li  class=\"menu_s\"><a href=\"";
echo url("admin/apply.get_list");
echo "\">申请投放审核</a></li>\r\n      </ul>\r\n    </li>\r\n    <li class=\"dropdown\"> <a  class=\"dropdown-toggle\" href=\"";
echo url("admin/ad.get_list");
echo "\"><i class=\"icon-ad icon-white\"></i><span>广告创意</span><b class=\"caret\"></b></a> <i class=\"tpm\"></i>\r\n      <ul class=\"dropdown-menu\">\r\n        <li><a href=\"";
echo url("admin/ad.get_list");
echo "\">所有广告</a></li>\r\n        ";

foreach (explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
	echo "        <li><a href=\"";
	echo url("admin/ad.get_list?plantype=" . $t);
	echo "\">";
	echo strtoupper($t);
	echo "广告创意</a></li>\r\n        ";
}

echo "        <li><a href=\"";
echo url("admin/ad.add");
echo "\">新建广告</a></li>\r\n      </ul>\r\n    </li>\r\n    <li class=\"dropdown\"> <a  class=\"dropdown-toggle\" href=\"";
echo url("admin/stats.plan_list?timerange=" . DAYS . "_" . DAYS . "");
echo "\"><i class=\"icon-list-alt icon-white\"></i><span>数据报表</span><b class=\"caret\"></b></a> <i class=\"tpm\"></i>\r\n      <ul class=\"dropdown-menu\">\r\n        <li><a href=\"";
echo url("admin/stats.plan_list?timerange=" . DAYS . "_" . DAYS . "");
echo "\">计划效果报表</a></li>\r\n        <li><a href=\"";
echo url("admin/ip.get_list?timerange=" . DAYS . "_" . DAYS . "");
echo "\">IP报表管理</a></li>\r\n        <li><a href=\"";
echo url("admin/stats.user_list?timerange=" . DAYS . "_" . DAYS . "");
echo "\">会员报表</a></li>\r\n        <li><a href=\"";
echo url("admin/stats.ads_list?timerange=" . DAYS . "_" . DAYS . "");
echo "\">广告报表</a></li>\r\n        <li><a href=\"";
echo url("admin/stats.zone_list?timerange=" . DAYS . "_" . DAYS . "");
echo "\">广告位报表</a></li>\r\n        <li><a href=\"";
echo url("admin/orders.get_list");
echo "\">CPS订单管理</a></li>\r\n         <li><a href=\"";
echo url("admin/cpa_report.get_list");
echo "\">CPA行为管理</a></li>\r\n        <li class=\"menu_s\"><a href=\"";
echo url("admin/import.get_list");
echo "\">导入数据管理</a></li>\r\n      </ul>\r\n    </li>\r\n    <li class=\"dropdown\"> <a  class=\"dropdown-toggle\" href=\"";
echo url("admin/trend.get_list?timerange=" . DAYS . "_" . DAYS . "");
echo "\"><i class=\"icon-trend_top icon-white\"></i><span>流量分析</span><b class=\"caret\"></b></a><i class=\"tpm\"></i>\r\n      <ul class=\"dropdown-menu\">\r\n        <li><a href=\"";
echo url("admin/trend.get_list?timerange=" . DAYS . "_" . DAYS . "");
echo "\">趋势分析</a></li>\r\n        <li><a href=\"";
echo url("admin/client_trend.get_os?timerange=" . DAYS . "_" . DAYS . "");
echo "\">客户端属性</a></li>\r\n        <li><a href=\"";
echo url("admin/search_trend.get_list?timerange=" . DAYS . "_" . DAYS . "");
echo "\">搜索引擎</a></li>\r\n        <li  class=\"menu_s\"><a href=\"";
echo url("admin/client_trend.get_city?timerange=" . DAYS . "_" . DAYS . "");
echo "\">地域分布 </a></li>\r\n      </ul>\r\n    </li>\r\n\t\r\n\t <li class=\"dropdown\"> <a  class=\"dropdown-toggle\" href=\"";
echo url("admin/pay.get_list");
echo "\"><i class=\"icon-pay icon-white\"></i><span>财务管理</span><b class=\"caret\"></b></a><i class=\"tpm\"></i>\r\n      <ul class=\"dropdown-menu\">\r\n        <li><a href=\"";
echo url("admin/pay.get_list");
echo "\">财务结算</a></li>\r\n        <li><a href=\"";
echo url("admin/onlinepay.get_list");
echo "\">充值管理</a></li>\r\n       <!-- <li><a href=\"";
echo url("admin/search_trend.get_list?timerange=" . DAYS . "/" . DAYS . "");
echo "\">发布补偿</a></li>-->\r\n       \r\n      </ul>\r\n    </li>\r\n\t\r\n  </ul>\r\n  <ul class=\"nav_r\"  >\r\n    <li class=\"vertical\"></li>\r\n    <li><span><img src=\"";
echo SRC_TPL_DIR;
echo "/images/user_avatar.png\" alt=\"\" class=\"user_avatar\">";
echo $_SESSION["admin"]["username"];
echo " <a href=\"";
echo url("admin/login.logout");
echo "\" style=\" color:#fff;\">退出</a></span></li>\r\n  </ul>\r\n</div>\r\n<script language=\"JavaScript\" type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/lib/leanmodal/leanmodal.min.js\"></script>\r\n\r\n";

?>
