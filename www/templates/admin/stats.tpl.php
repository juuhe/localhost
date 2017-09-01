<?php

if (!defined("IN_ZYADS")) {
	exit();
}

TPL::display("header");
echo "\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/calendar/calendar.js\"></script>\r\n<link rel=\"stylesheet\" href=\"";
echo WEB_URL;
echo "js/calendar/calendar.css\" media=\"all\" type=\"text/css\" />\r\n<link rel=\"stylesheet\" href=\"";
echo SRC_TPL_DIR;
echo "/css/rating.css\" media=\"all\" type=\"text/css\" />\r\n<div class=\"alert success\" ";

if (!$_SESSION["succ"]) {
	echo "style=\"display:none\"";
}

echo "> \r\n  <!-- <a class=\"close\" href=\"javascript:;\">×</a>--> \r\n  <strong>操作成功.</strong> </div>\r\n<div class=\"alert err\" ";

if (!$_SESSION["err"]) {
	echo "style=\"display:none\"";
}

echo "> \r\n  <!-- <a class=\"close\" href=\"javascript:;\">×</a>--> \r\n  <strong>操作失败.</strong> </div>\r\n<div id=\"sidebar\">\r\n  ";
TPL::display("sidebar");
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid\" >\r\n    <h3 class=\"heading\">效果报表 <span class=\"h3span\"> <a href=\"";
echo url("admin/stats.down_execl?action=" . RUN_ACTION . "&" . http_build_query($page->params_url));
echo "\" style=\" width:110px; text-align:left; padding-left:10px\"><i class=\"n_excel\"></i> 导出Excel</a></span></h3>\r\n    <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/stats.plan_list");
echo "\" class=\"tab-btn  planstats ";

if (RUN_ACTION == "plan_list") {
	echo "tab-state-active";
}

echo "\">计划报表</a> <a href=\"";
echo url("admin/stats.user_list");
echo "\" class=\"tab-btn userstats ";

if (RUN_ACTION == "user_list") {
	echo "tab-state-active";
}

echo "\"> 站长报表</a> <a href=\"";
echo url("admin/stats.ads_list");
echo "\" class=\"tab-btn adsstats ";

if (RUN_ACTION == "ads_list") {
	echo "tab-state-active";
}

echo "\"> 广告报表</a> <a href=\"";
echo url("admin/stats.zone_list");
echo "\" class=\"tab-btn zonestats ";

if (RUN_ACTION == "zone_list") {
	echo "tab-state-active";
}

echo "\"> 广告位报表</a> <a href=\"";
echo url("admin/ip.get_list");
echo "\" class=\"tab-btn ip \"> IP报表</a>  </div>\r\n    </div>\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"tb_sts\" style=\"margin-bottom:10px;\">\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\">\r\n            <form method=\"post\" action=\"";
echo url("admin/stats." . RUN_ACTION);
echo "\">\r\n              搜索：\r\n              <input type=\"text\" class=\"input_text \" name=\"searchval\" value=\"";
echo $searchval;
echo "\"  style=\"width:80px\"/>\r\n              <select name=\"searchtype\">\r\n                <option value=\"planid\" ";

if ($searchtype == "planid") {
	echo "selected";
}

echo ">计划ID</option>\r\n                <option value=\"uid\" ";

if ($searchtype == "uid") {
	echo "selected";
}

echo ">站长ID</option>\r\n                <option value=\"recommend\" ";

if ($searchtype == "recommend") {
	echo "selected";
}

echo ">查询下线</option>\r\n                <option value=\"adsid\" ";

if ($searchtype == "adsid") {
	echo "selected";
}

echo ">广告ID</option>\r\n                <option value=\"zoneid\" ";

if ($searchtype == "zoneid") {
	echo "selected";
}

echo ">广告位ID</option>\r\n              </select>\r\n              <select name=\"sortingtype\" class=\"select\" id=\"select2\">\r\n                <option value=\"\">默认排序</option>\r\n                <option value=\"day\" ";

if ($sortingtype == "day") {
	echo "selected";
}

echo ">日期</option>\r\n                <option value=\"views\" ";

if ($sortingtype == "views") {
	echo "selected";
}

echo ">浏览量</option>\r\n                <option value=\"num\" ";

if ($sortingtype == "num") {
	echo "selected";
}

echo ">结算数</option>\r\n                <option value=\"deduction\" ";

if ($sortingtype == "deduction") {
	echo "selected";
}

echo ">扣量</option>\r\n                <option value=\"clicks\" ";

if ($sortingtype == "clicks") {
	echo "selected";
}

echo ">二次点击</option>\r\n                <option value=\"effectnum\" ";

if ($sortingtype == "effectnum") {
	echo "selected";
}

echo ">效果数</option>\r\n                <option value=\"sumpay\" ";

if ($sortingtype == "sumpay") {
	echo "selected";
}

echo ">应付金额</option>\r\n                <option value=\"sumprofit\" ";

if ($sortingtype == "sumprofit") {
	echo "selected";
}

echo ">盈利</option>\r\n              </select>\r\n              <select name=\"plantype\" class=\"plantype\" id=\"plantype\">\r\n                <option value=\"\">所有类型</option>\r\n                ";

foreach (explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
	echo "                <option value=\"";
	echo $t;
	echo "\" ";

	if ($plantype == $t) {
		echo "selected";
	}

	echo " >";
	echo ucfirst($t);
	echo "类型</option>\r\n                ";
}

echo "              </select>\r\n              <select name=\"timerange\" id=\"timerange\" style=\"width:200px;\">\r\n                <option value=\"";

if ($timerange != "") {
	echo $timerange;
}
else {
	echo $get_timerange["day"];
}

echo "\">\r\n                ";

if ($timerange != "") {
	echo str_replace("_", " 至 ", $timerange);
}
else {
	echo str_replace("_", " 至 ", $get_timerange["day"]);
}

echo "                </option>\r\n                <option  value=\"\" ";
echo $timerange == "" ? "selected " : "";
echo ">所有时间段</option>\r\n                 <option  value=\"";
echo $get_timerange["day"];
echo "\" ";
echo $timerange == $get_timerange["day"] ? " selected" : "";
echo ">今天</option>\r\n                <option value=\"";
echo $get_timerange["yesterday"];
echo "\" ";
echo $timerange == $get_timerange["yesterday"] ? " selected" : "";
echo " >昨天</option>\r\n                <option value=\"";
echo $get_timerange["7day"];
echo "\" ";
echo $timerange == $get_timerange["7day"] ? " selected" : "";
echo " >最近7天</option>\r\n                <option value=\"";
echo $get_timerange["30day"];
echo "\" ";
echo $timerange == $get_timerange["30day"] ? " selected" : "";
echo " >最近30天</option>\r\n                <option value=\"";
echo $get_timerange["lastmonth"];
echo "\" ";
echo $timerange == $get_timerange["lastmonth"] ? " selected" : "";
echo " >上个月</option>\r\n              </select>\r\n              <img src=\"";
echo SRC_TPL_DIR;
echo "/images/calendar.png\" align=\"absmiddle\"  onclick=\"__C('timerange',2,'r')\" style=\"margin-bottom: 3px;\"/>\r\n              <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\" style=\"margin-left: 20px;\" />\r\n            </form>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <table id=\"dt_inbox\" class=\"dataTable\">\r\n        <thead>\r\n          <tr role=\"row\" class=\"row_w\">\r\n            <th ><input type=\"checkbox\" name=\"select_id\" id=\"select_id\" /></th>\r\n            <th>日期</th>\r\n            <th>";

switch (RUN_ACTION) {
case "plan_list":
	echo "计划名称";
	break;

case "user_list":
	echo "站长名称";
	break;

case "ads_list":
	echo "广告ID";
	break;

case "zone_list":
	echo "广告位ID";
	break;
}


echo "</th>\r\n            ";

if (RUN_ACTION == "plan_list") {
	echo "            <th>类型</th>\r\n            ";
}

echo "            <th>浏览数</th>\r\n            <th>点击数</th>\r\n            <th>二次点击</th>\r\n            <th>效果数</th>\r\n            <th>扣量</th>\r\n            <th>结算数</th>\r\n            <th>CRT</th>\r\n            <th>";
if ((RUN_ACTION === "plan_list") || (RUN_ACTION === "ads_list")) {
	echo "应付";
}
else {
	echo "佣金";
}

echo "</th>\r\n            <th>盈利</th>\r\n          </tr>\r\n          \r\n          <tr role=\"row\">\r\n            <th>汇总</th>\r\n            <th>&nbsp;</th>\r\n            <th>&nbsp;</th>\r\n            ";

if (RUN_ACTION == "plan_list") {
	echo "            <th>&nbsp;</th>\r\n            ";
}

echo "            <th>";
echo $sum_stats["views"];
echo "</th>\r\n            <th>";
echo $sum_stats["clicks"];
echo "</th>\r\n            <th>";
echo $sum_stats["do2click"];
echo "</th>\r\n            <th>";
echo $sum_stats["effectnum"];
echo "</th>\r\n            <th>";
echo $sum_stats["deduction"];
echo "</th>\r\n            <th>";
echo $sum_stats["num"];
echo "</th>\r\n            <th>";
echo Ctr($sum_stats["views"], $sum_stats["num"]);
echo "%</th>\r\n            <th>￥";
if ((RUN_ACTION === "plan_list") || (RUN_ACTION === "ads_list")) {
	echo 0 < $sum_stats["sumadvpay"] ? round($sum_stats["sumadvpay"], 4) : 0;
}
else {
	echo 0 < $sum_stats["sumpay"] ? round($sum_stats["sumpay"], 4) : 0;
}

echo "</th>\r\n            <th><span class=\"status\">￥";
echo abs($sum_stats["sumprofit"]);
echo "</span></th>\r\n          </tr>\r\n          \r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $stats as $s ) {
	$p = dr("admin/plan.get_one", $s["planid"]);

	if (RUN_ACTION == "user_list") {
		$u = dr("admin/user.get_one", $s["uid"]);
	}

	echo "          <tr class=\"unread odd\">\r\n            <td>\r\n            \r\n            ";

	switch (RUN_ACTION) {
	case "plan_list":
		$vlaue = $s["day"] . "_" . $p["planid"] . "_planid";
		break;

	case "user_list":
		$vlaue = $s["day"] . "_" . $u["uid"] . "_uid";
		break;

	case "ads_list":
		$vlaue = $s["day"] . "_" . $s["adsid"] . "_adsid";
		break;

	case "zone_list":
		$vlaue = $s["day"] . "_" . $s["zoneid"] . "_zoneid";
		break;
	}

	echo " <input type=\"checkbox\" name=\"statsid\" id=\"" . $vlaue . "\" value=\"" . $vlaue . "\"  />";
	echo "             \r\n            </td>\r\n            <td  class=\"manage\"><a href=\"#\" onclick=\"return false\" style=\"border-bottom: dashed 1px #0088cc;\">";
	echo $s["day"];
	echo "</a></td>\r\n            <td>";

	switch (RUN_ACTION) {
	case "plan_list":
		echo "<a href=\"" . url("admin/plan.get_list?searchtype=planid&search=" . $p["planid"]) . "\">" . $p["planname"] . "</a>";
		echo "<div class='pop_con'><a href='" . url("admin/stats.user_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=planid&searchval=" . $s["planid"] . "") . "'>投放站长</a> <a href='" . url("admin/stats.ads_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=planid&searchval=" . $s["planid"] . "") . "'>投放广告</a> <a href='" . url("admin/stats.zone_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=planid&searchval=" . $s["planid"] . "") . "'>投放广告位</a>  <a href='" . url("admin/ip.get_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=planid&searchval=" . $s["planid"] . "") . "'>投放IP</a>  <a href='" . url("admin/trend.get_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=planid&searchval=" . $s["planid"] . "") . "'>趋势</a></div>";
		break;

	case "user_list":
		echo $u["username"] ? "<a href=\"" . url("admin/user.affiliate_list?searchtype=uid&search=" . $u["uid"]) . "\">" . $u["username"] . "</a>" : "已删除";
		echo "<div class='pop_con'><a href='" . url("admin/stats.plan_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=uid&searchval=" . $s["uid"] . "") . "'>投放计划</a> <a href='" . url("admin/stats.ads_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=uid&searchval=" . $s["uid"] . "") . "'>投放广告</a> <a href='" . url("admin/stats.zone_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=uid&searchval=" . $s["uid"] . "") . "'>投放广告位</a>  <a href='" . url("admin/ip.get_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=uid&searchval=" . $s["uid"] . "") . "'>投放IP</a>  <a href='" . url("admin/trend.get_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=uid&searchval=" . $s["uid"] . "") . "'>趋势</a></div>";
		break;

	case "ads_list":
		echo $s["adsid"] ? "<a href=\"" . url("admin/ad.get_list?searchtype=adsid&search=" . $s["adsid"]) . "\">#" . $s["adsid"] . "</a>" : "#" . $s["adsid"] . "(不存在的)";
		echo "<div class='pop_con'><a href='" . url("admin/stats.user_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=adsid&searchval=" . $s["adsid"] . "") . "'>投放会员</a> <a href='" . url("admin/stats.zone_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=adsid&searchval=" . $s["adsid"] . "") . "'>投放广告位</a>  <a href='" . url("admin/ip.get_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=adsid&searchval=" . $s["adsid"] . "") . "'>投放IP</a></div>";
		break;

	case "zone_list":
		echo $s["zoneid"] ? "<a href=\"" . url("admin/zone.get_list?searchtype=zoneid&search=" . $s["zoneid"]) . "\">#" . $s["zoneid"] . "</a>" : "#" . $s["zoneid"] . "(不存在的)";
		echo "<div class='pop_con'><a href='" . url("admin/stats.plan_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=zoneid&searchval=" . $s["zoneid"] . "") . "'>投放计划</a> <a href='" . url("admin/stats.ads_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=zoneid&searchval=" . $s["zoneid"] . "") . "'>投放广告</a> <a href='" . url("admin/ip.get_list?timerange=" . $s["day"] . "_" . $s["day"] . "&searchtype=zoneid&searchval=" . $s["zoneid"] . "") . "'>投放IP</a></div>";
		break;
	}

	echo "</td>\r\n            ";

	if (RUN_ACTION == "plan_list") {
		echo "            <td>";
		echo ucfirst($p["plantype"]);
		echo "</td>\r\n            ";
	}

	echo "            <td>";
	echo $s["views"];
	echo "</td>\r\n            <td>";
	echo $s["clicks"];
	echo "</td>\r\n            <td>";
	echo $s["do2click"];
	echo "</td>\r\n            <td>";
	echo $s["effectnum"];
	echo "</td>\r\n            <td>";
	echo $s["deduction"];
	echo "</td>\r\n            <td>";
	echo $s["num"];
	echo "</td>\r\n            <td>";
	echo Ctr($s["views"], $s["num"]);
	echo "%</td>\r\n            <td>￥";
	if ((RUN_ACTION === "plan_list") || (RUN_ACTION === "ads_list")) {
		echo 0 < $s["sumadvpay"] ? round($s["sumadvpay"], 4) : 0;
	}
	else {
		echo 0 < $s["sumpay"] ? round($s["sumpay"], 4) : 0;
	}

	echo "</td>\r\n            <td class=\"status\">￥";
	echo abs($s["sumprofit"]);
	echo "</td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n<div class=\"popover right\" > <b>◆</b> <span>◆</span>\r\n  <div class=\"popover-title\">\r\n    <div style=\"float:left\">操作</div>\r\n  </div>\r\n  <div class=\"popover-content\"> </div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n //box.form_textarea_html(\"/www/super/index.php?e=admin/user.advertiser_list\",\"a\",'信息无误，允许通过！','审核',1,1);\r\n\r\n$(\".manage\").mouseenter(function() {  \r\n    position = $(this).position();\r\n    poptop = position.top - 20;\r\n    popleft = position.left + $(this).width();\r\n\tvar html = $(this).next().find('.pop_con').html();\r\n    $(\".popover-content\").html(html)\r\n    $(\".popover\").show().css(\"top\", poptop + \"px\").css(\"left\", popleft + \"px\");\r\n\t \r\n});\r\n\r\n$(\".popover,.manage\").mouseleave(function(){\r\n\t$(\".popover\").hide();\r\n});\r\n\r\n$(\".popover\").mouseenter(function(){\r\n\t$(\".popover\").show();\r\n});\r\n\r\n \r\nfunction uld(type,htmls) {\r\n\tvar html = '';\r\n   \tvar width = 500;\r\n\tif (type == 'del') {\r\n\t\turl = '";
echo url("admin/stats.del");
echo "';\r\n\t\ttitle = '删除计划';\r\n\t\ttext = '确定要删除吗？删除后无法恢复!';\r\n\t}\r\n \t \r\n\tbox.confirm(text,width,title,function(bool){ \r\n\t\t  var add_get = ''; \r\n\t\t __statsid = _statsid.split(','); \r\n\t\t if (bool) {\r\n\t\t\t if (type == 'del') {\r\n\t\t\t\t$.each(__statsid, function(i,val){   \r\n\t\t\t\t\t$(\"#\"+val).parent().parent().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t});  \r\n\t\t\t } \r\n\t\t\t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: url,\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'statsid=' + __statsid,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\r\n\t\t\t\t\t $.each(__statsid, function(i,val){   \r\n\t\t\t\t\t\t $(\"#planid_\"+val).parent().parent().find('.status').html(html);\r\n\t\t\t\t\t });   \r\n\t\t\t\t\t$(\".success\").show();\r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n}\r\n\r\n \r\n \r\n$(\"#select_id\").click(function(){\r\n \t var a = $(\"#select_id\").attr(\"checked\");\r\n\t if(a!='checked') a = false;\r\n     $(\"input[name='statsid']\").attr(\"checked\",a);\r\n});\r\n\r\n$(\"#choose_sb\").click(function(){\r\n\tvar arr=[];\r\n\tvar choose_type = $(\"#choose_type\").val();\r\n\tif(!choose_type){\r\n\t\tbox.alert('批量操作请选择一个操作对像',300);\r\n\t\treturn ;\r\n\t}\r\n \tvar arrChk=$(\"input[name='statsid']:checked\"); \r\n     \r\n    for (var i=0;i<arrChk.length;i++)\r\n    {\r\n        var v = arrChk[i].value;\r\n\t\tarr.push(v);\r\n\t\t\r\n    }\r\n\t_statsid = arr.join(\",\");\r\n\tuld(choose_type);\r\n});\r\n\r\n \r\n\r\n\r\n$('.report_menu').on('click', function(option) { \r\n\t\t $('.report_menu_html').show();\r\n });\r\n \r\n $('#off_report_menu_html').on('click', function(option) { \r\n\t\t$('.report_menu_html').hide();\r\n });\r\n \r\n </script> \r\n";

?>
