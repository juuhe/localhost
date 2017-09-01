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
echo "</div>\r\n<div id=\"main-content\">\r\n  <h3 class=\"heading\">IP报表</h3>\r\n  <div class=\"tab users\">\r\n    <div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/ip.get_list?timerange=" . DAYS . "_" . DAYS . "");
echo "\" class=\"tab-btn  list tab-state-active\">IP报表</a> <a href=\"javascript:;\" class=\"tab-btn ip\" id=\"z_all\" data_v=0>展开全部IP详情</a> <a href=\"javascript:;\" class=\"tab-btn truncate\"  id=\"del_allip\">清空IP数据</a> </div>\r\n  </div>\r\n  <div class=\"row-fluid\" >\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"tb_sts\" style=\"margin-bottom:10px;\">\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\">\r\n            <form method=\"post\" action=\"";
echo url("admin/ip.get_list");
echo "\">\r\n              过滤：\r\n              \r\n              \r\n              <input type=\"text\" class=\"input_text span30\" id=\"searchval\" name=\"searchval\" value=\"";
echo $searchval;
echo "\" style=\"width:160px;color:#999999;font-style: italic\" onFocus=\"this.value=''\"  >\r\n              \r\n              <select name=\"searchtype\"   id=\"searchtype\">\r\n                <option value=\"ip\" ";

if ($searchtype == "ip") {
	echo "selected";
}

echo ">IP地址</option>\r\n                <option value=\"uid\" ";

if ($searchtype == "uid") {
	echo "selected";
}

echo ">会员UID</option>\r\n                <option value=\"planid\" ";

if ($searchtype == "planid") {
	echo "selected";
}

echo ">计划ID</option>\r\n                <option value=\"adsid\" ";

if ($searchtype == "adsid") {
	echo "selected";
}

echo ">广告ID</option>\r\n              </select>\r\n              <select name=\"planid\" id=\"planid\"  ";

if (RUN_ACTION == "edit") {
	echo "disabled='disabled'";
}

echo ">\r\n                <option value=\"\"> 所有计划 </option>\r\n                ";

foreach (explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
	echo "                <optgroup  label=\"";
	echo strtoupper($t);
	echo "\">\r\n                ";

	foreach ((array) $plan as $p ) {
		if ($p["plantype"] !== $t) {
			continue;
		}

		echo "                <option value=\"";
		echo $p["planid"];
		echo "\" ";

		if ($p["planid"] == $planid) {
			echo "selected";
		}

		echo ">&nbsp;";
		echo $p["planname"];
		echo "&nbsp;</option>\r\n                ";
	}

	echo "                </optgroup>\r\n                ";
}

echo "              </select>\r\n              <select name=\"timerange\" id=\"timerange\" style=\"width:200px;margin-bottom: 10px\">\r\n                <option value=\"";

if ($timerange != "") {
	echo $timerange;
}
else {
	echo $get_timerange["day"];
}

echo "\">\r\n                  ";

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
echo "/images/calendar.png\" align=\"absmiddle\"  onclick=\"__C('timerange',2)\" style=\"margin-bottom: 3px;\"/>\r\n              <input name=\"_s\" id=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\" />\r\n            </form>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <table id=\"dt_inbox\" class=\"dataTable\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\" /></th>\r\n            <th>IP</th>\r\n            <th>地域</th>\r\n            <th>会员名称</th>\r\n            <th>计名名称</th>\r\n            <th>类型</th>\r\n            <th>广告ID</th>\r\n            <th>有效</th>\r\n            <th>重复</th>\r\n            <th>显示/记录时间</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";
echo convert_ip("110.188.72.226");
foreach ((array) $ip as $i ) {
	$p = dr("admin/plan.get_one", $i["planid"]);
	$u = dr("admin/user.get_one", $i["uid"]);
	$dal_id = $i["last_time"] . "_" . $i["planid"] . "_" . $i["adsid"] . "_" . $i["uid"] . "_" . $i["ip"];
	echo "          <tr class=\"unread odd\">\r\n            <td><input type=\"checkbox\" name=\"del_id\" id=\"del_id_";
	echo $dal_id;
	echo "\" value=\"";
	echo $dal_id;
	echo "\" /></td>\r\n            <td><div class=\"ip-content\"></div>\r\n              <a href=\"";
	echo url("admin/ip.get_list?searchval=" . $i["ip"] . "&searchtype=ip");
	echo "\">";
	echo $i["ip"];
	echo "</a></td>\r\n            <td>";
	echo convert_ip($i["ip"]);
	echo "</td>\r\n            <td><a href=\"";
	echo url("admin/user.affiliate_list?search=" . $u["username"] . "&searchtype=username");
	echo "\">";
	echo $u["username"];
	echo "</a></td>\r\n            <td><a href=\"";
	echo url("admin/plan.get_list?search=" . $p["planid"] . "&searchtype=planid");
	echo "\">";
	echo $p["planname"];
	echo "</a></td>\r\n            <td> ";
	echo ucfirst($p["plantype"]);
	echo " </td>\r\n            <td><a href=\"";
	echo url("admin/ad.get_list?search=" . $i["adsid"] . "&searchtype=adsid");
	echo "\">";
	echo $i["adsid"];
	echo "</a></td>\r\n            <td>";

	if ($i["deduction"] == "y") {
		echo "<font  color='#ff000'>扣量</font>";
	}
	else {
		echo "有效";
	}

	echo "</td>\r\n            <td>";
	echo $i["visitnum"];
	echo "</td>\r\n            <td>";
	echo $i["first_time"];
	echo "</td>\r\n          </tr>\r\n          <tr>\r\n            <td>&nbsp;</td>\r\n            <td colspan=\"8\" style=\"padding-left: 8px;\">\r\n            <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/os/";
	echo $i["os"] ? $i["os"] : "un";
	echo ".jpg\" width=\"14\" height=\"14\" title=\"";
	echo ucfirst($i["os"]);
	echo "\"//> \r\n            <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/browsers/";
	echo $i["browser_name"] ? strtolower(preg_replace("/[^a-z]/i", "", $i["browser_name"])) : "un";
	echo ".jpg\" width=\"14\" height=\"14\" title=\"";
	echo ucfirst($i["browser_name"]);
	echo " ";
	echo $i["screen"];
	echo "\"/>\r\n              ";

	if ($i["cookie"]) {
		echo "              <img src=\"";
		echo SRC_TPL_DIR;
		echo "/images/plugins/cookie.gif\" width=\"14\" height=\"14\" title=\"支持Cookie\"/>\r\n              ";
	}

	echo "              ";

	if ($i["flash"]) {
		echo "              <img src=\"";
		echo SRC_TPL_DIR;
		echo "/images/plugins/flash.gif\" width=\"14\" height=\"14\" title=\"支持Flash\"/>\r\n              ";
	}

	echo "              ";

	if ($i["java"]) {
		echo "              <img src=\"";
		echo SRC_TPL_DIR;
		echo "/images/plugins/java.gif\" width=\"14\" height=\"14\" title=\"支持JAVA\"/>\r\n              ";
	}

	echo "              ";

	if ($i["visitnum"]) {
		echo "              - <img src=\"";
		echo SRC_TPL_DIR;
		echo "/images/re_visitor.gif\" width=\"14\" height=\"14\" title=\"老访客\" />\r\n              ";
	}

	echo "              ";

	if ($i["is_mobile"] == "y") {
		echo "              - <img src=\"";
		echo SRC_TPL_DIR;
		echo "/images/os/mobile.jpg\" width=\"14\" height=\"14\" title=\"移动访客\" />\r\n              ";
	}

	echo "</td>\r\n            <td style=\"padding-left: 8px;\">";
	echo $i["last_time"];
	echo "</td>\r\n          </tr>\r\n          <tr class=\"tr_td_b1 ip_con\" style=\"display:none\">\r\n            <td>&nbsp;</td>\r\n            <td colspan=\"9\" style=\"padding-left: 8px;\"> AGENT:";
	echo $i["useragent"];
	echo "<br>\r\n              投放页面：";
	echo rawurldecode($i["site_page"]);
	echo "<br>\r\n              系统：";
	echo $i["os"];
	echo "<br>\r\n              浏览器：";
	echo $i["browser_name"];
	echo "/";
	echo $i["browser_version"];
	echo " （";
	echo $i["browser_lang"];
	echo "）<br>\r\n              屏幕：";
	echo $i["screen"];
	echo " <br>\r\n              位置：";
	echo $i["ch"];
	echo "<br>\r\n              插件：";
	echo $i["flash"];
	echo "<br>\r\n              ";

	if ($i["page_title"]) {
		echo "              页面标题：";
		echo rawurldecode($i["page_title"]);
		echo "<br>\r\n              ";
	}

	echo "              ";

	if ($i["referer_url"]) {
		echo "              来源于：";
		echo rawurldecode($i["referer_url"]);
		echo "<br>\r\n              ";
	}

	echo "              ";

	if ($i["referer_keyword"]) {
		echo "              来源关键词：";
		echo urldecode($i["referer_keyword"]);
		echo "<br>\r\n              ";
	}

	echo "              点击坐标：";
	echo $i["xy"];
	echo " 轨迹:";
	echo $i["xxyy"];
	echo "</td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n \r\n\r\nfunction gourl(url){\r\n\t\twindow.location.href = url+_planid;\r\n}\r\n\r\n\r\n \r\n\r\n\r\nfunction uld(type,htmls) {\r\n\tvar html = '';\r\n   \tvar width = 500;\r\n\tif (type == 'del') {\r\n\t\turl = '";
echo url("admin/ip.del");
echo "';\r\n\t\ttitle = '删除IP信息';\r\n\t\ttext = '确定要删除吗？删除后无法恢复!';\r\n\t}\r\n\t \r\n  \r\n\t\t\t \r\n\tbox.confirm(text,width,title,function(bool){ \r\n\t\t  __del_id = _del_id.split(','); \r\n\t\t if (bool) {\r\n\t\t\t if (type == 'del') {\r\n\t\t\t\t$.each(__del_id, function(i,val){  \r\n\t\t\t\t\tvar od = $(\"input[name='del_id']:checked:eq(\"+i+\")\").parent().parent(); \r\n\t\t\t\t\tod.css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t\tod.next().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t\tod.next().next().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t});  \r\n\t\t\t } \r\n\t\t\t \r\n\t\t\t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: url,\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'del_id=' + __del_id ,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\r\n\t\t\t\t\t$(\".success\").show();\r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n}\r\n\r\n\r\n\r\n$('.ip-content').on('click', function(option) { \r\n    var o = $(this).parents().parents().next().next();  \r\n\tif(o.css(\"display\")=='none'){\r\n\t\to.show(); \r\n\t    $(this).css(\"backgroundImage\",\"url(";
echo SRC_TPL_DIR;
echo "/images/g_ico_16.jpg)\");\r\n\t}else {\r\n\t\to.hide();\r\n\t    $(this).css(\"backgroundImage\",\"url(";
echo SRC_TPL_DIR;
echo "/images/z_ico_16.jpg)\");\r\n\t}\r\n});\r\n  \r\n$('#z_all').on('click', function(option) { \r\n\tif($(this).attr(\"data_v\") == 1){  \r\n\t\tvar o = $('.ip_con').hide();\r\n\t\t$(this).attr(\"data_v\",0);\r\n\t\t$(\".ip-content\").css(\"backgroundImage\",\"url(";
echo SRC_TPL_DIR;
echo "/images/z_ico_16.jpg)\"); \r\n\t\r\n\t}else {\r\n    \tvar o = $('.ip_con').show();\r\n\t\t$(this).attr(\"data_v\",1);\r\n\t\t$(\".ip-content\").css(\"backgroundImage\",\"url(";
echo SRC_TPL_DIR;
echo "/images/g_ico_16.jpg)\"); \r\n\t}\r\n});\r\n\r\n$(\"#select_id\").click(function(){\r\n \t var a = $(\"#select_id\").attr(\"checked\");\r\n\t if(a!='checked') a = false;\r\n     $(\"input[name='del_id']\").attr(\"checked\",a);\r\n});\r\n\r\n$(\"#choose_sb\").click(function(){\r\n\tvar arr=[];\r\n\tvar choose_type = $(\"#choose_type\").val();\r\n\tif(!choose_type){\r\n\t\tbox.alert('批量操作请选择一个操作对像',300);\r\n\t\treturn ;\r\n\t}\r\n \tvar arrChk=$(\"input[name='del_id']:checked\"); \r\n     \r\n    for (var i=0;i<arrChk.length;i++)\r\n    {\r\n        var v = arrChk[i].value;\r\n\t\tarr.push(v);\r\n\t\t\r\n    }\r\n\t_del_id = arr.join(\",\");\r\n\tuld(choose_type);\r\n});\r\n\r\n \r\n\r\n$(\"#_s\").click(function(){\r\n\tvar timerange = $(\"#timerange\").val();\r\n\tvar searchval = $(\"#searchval\").val();\r\n\tif(timerange == '搜索日期-默认所有') $(\"#timerange\").val('');\r\n\tif(searchval == '搜索IP相关') $(\"#searchval\").val('')\r\n});\r\n\r\n $(\".truncate\").click(function(){\r\n \r\n  \r\n  \r\n  box.confirm(\"确认清空所有IP吗？<br>清空IP报表不影响会员数据。\",300,'清空所有IP',function(bool){ \r\n\t\t if (bool) { \r\n\t\t\t $.get(\"";
echo url("admin/ip.truncate");
echo "\", function(result){\r\n\t\t\t\t $(\".success\").show();\r\n\t\t\t\t window.location.reload();\r\n\t\t\t  });\r\n\t\t}\r\n\t});\r\n\t\r\n\t\r\n});\r\n \r\n </script>\r\n";
TPL::display("footer");

?>
