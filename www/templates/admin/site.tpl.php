<?php

if (!defined("IN_ZYADS")) {
	exit();
}

TPL::display("header");
echo "\r\n<link rel=\"stylesheet\" href=\"";
echo SRC_TPL_DIR;
echo "/css/rating.css\" media=\"all\" type=\"text/css\" />\r\n<div class=\"alert success\" ";

if (!$_SESSION["succ"]) {
	echo "style=\"display:none\"";
}

echo ">\r\n  <!-- <a class=\"close\" href=\"javascript:;\">×</a>-->\r\n  <strong>操作成功.</strong> </div>\r\n<div class=\"alert err\" ";

if (!$_SESSION["err"]) {
	echo "style=\"display:none\"";
}

echo ">\r\n  <!-- <a class=\"close\" href=\"javascript:;\">×</a>-->\r\n  <strong>操作失败.</strong> </div>\r\n<div id=\"sidebar\">\r\n  ";
TPL::display("sidebar");
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid\" >\r\n    <h3 class=\"heading\">\r\n      网站管理<span class=\"tb_sts\" style=\" font-size:12px; padding-left:20px\"><a href=\"";
echo url("admin/site.add");
echo "\"  class=\"  \"><img src=\"";
echo SRC_TPL_DIR;
echo "/images/add.png\" alt=\"\" border=\"0\" style=\"padding-bottom:3px\" /> 新建网站</a> </span >    </h3>\r\n\t\r\n\t   <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/site.get_list");
echo "\" class=\"tab-btn  list ";

if ($status == "") {
	echo "tab-state-active";
}

echo "\">全部网站</a> <a href=\"";
echo url("admin/site.get_list?status=3");
echo "\" class=\"tab-btn unlock ";

if ($status == "3") {
	echo "tab-state-active";
}

echo "\"> 已审核</a>  <a href=\"";
echo url("admin/site.get_list?status=0");
echo "\" class=\"tab-btn red  ";

if ($status == "0") {
	echo "tab-state-active";
}

echo "\"> 待核</a><a href=\"";
echo url("admin/site.get_list?status=2");
echo "\" class=\"tab-btn lock ";

if ($status == "2") {
	echo "tab-state-active";
}

echo "\">已锁定</a> </div>\r\n    </div>\r\n\t\r\n    <div  class=\"dataTables_wrapper \">\r\n\t\r\n\t\r\n      <div class=\"tb_sts\"> <form method=\"post\">\r\n          \r\n              <select name=\"searchtype\">\r\n                <option value=\"siteurl\" ";

if ($searchtype == "siteurl") {
	echo "selected";
}

echo ">网站URL</option>\r\n\t\t\t\t<option value=\"sitename\" ";

if ($searchtype == "sitename") {
	echo "selected";
}

echo ">网站名称</option>\r\n\t\t\t\t<option value=\"siteid\" ";

if ($searchtype == "siteid") {
	echo "selected";
}

echo ">网站ID</option>\r\n\t\t\t\t<option value=\"username\" ";

if ($searchtype == "username") {
	echo "selected";
}

echo ">站长名称</option>\r\n\t\t\t\t<option value=\"uid\" ";

if ($searchtype == "uid") {
	echo "selected";
}

echo ">站长ID</option>\r\n              </select>\r\n              <input type=\"text\" class=\"input_text\" name=\"search\" value=\"";
echo $search;
echo "\">\r\n            \r\n              <select name=\"sitetype\" id=\"sitetype\">\r\n                <option value=\"\">所有类型</option>\r\n                ";

foreach ((array) $sitetype_data as $st ) {
	echo "                <option value=\"";
	echo $st["classid"];
	echo "\" ";

	if ($site["sitetype"] == $st["classid"]) {
		echo "selected";
	}

	echo " >";
	echo $st["classname"];
	echo "</option>\r\n                ";
}

echo "              </select>\r\n             <select name=\"grade\" id=\"grade\">\r\n\t\t\t\t\t  <option value=\"\"  >所有等级</option>\r\n\t\t\t\t\t  <option value=\"0\" ";

if ($grade == "0") {
	echo "selected";
}

echo ">0星级</option>\r\n\t\t\t\t\t  <option value=\"1\" ";

if ($grade == "1") {
	echo "selected";
}

echo ">1星级</option>\r\n\t\t\t\t\t  <option value=\"2\" ";

if ($grade == "2") {
	echo "selected";
}

echo ">2星级</option>\r\n\t\t\t\t\t  <option value=\"3\" ";

if ($grade == "3") {
	echo "selected";
}

echo ">3星级</option>\r\n\t\t\t </select>\r\n\t\t\t \r\n\t\t\t   <select name=\"alexapr\"   id=\"alexapr\">\r\n                      <option value=\"alexa\" ";

if ($alexapr == "alexa") {
	echo "selected";
}

echo ">Alexa</option>\r\n\t\t\t\t\t  <option value=\"pr\" ";

if ($alexapr == "pr") {
	echo "selected";
}

echo ">Pr</option>\r\n             </select>\r\n\t\t\t  > <input type=\"text\" class=\"input_text \" name=\"alexaprval\" value=\"";
echo $alexaprval;
echo "\">\r\n\t\t\t  \r\n\t\t\t  \r\n              <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n            </form></div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"unlock\">激活</option>\r\n              <option value=\"lock\" >锁定</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n          </div>\r\n        </div>\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\">\r\n            \r\n          </div>\r\n        </div>\r\n      </div>\r\n      <table id=\"dt_inbox\" class=\"dataTable\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\"></th>\r\n            <th>ID</th>\r\n            <th>站长</th>\r\n            <th>网站名称</th>\r\n            <th>网站地址</th>\r\n            <th>网站类型</th>\r\n            <th>日访问量</th>\r\n            <th>Alexa/PR</th>\r\n            <th>星级</th>\r\n            <th>状态</th>\r\n            <th>操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $site as $s ) {
	$c = dr("admin/class.get_one", $s["sitetype"]);
	$u = dr("admin/user.get_one", $s["uid"]);
	echo "          <tr class=\"unread odd\">\r\n            <td><input type=\"checkbox\" name=\"siteid\" id=\"site_";
	echo $s["siteid"];
	echo "\" value=\"";
	echo $s["siteid"];
	echo "\"></td>\r\n            <td>";
	echo $s["siteid"];
	echo "</td>\r\n            <td>";
	echo $u["username"];
	echo "</td>\r\n            <td>";
	echo $s["sitename"];
	echo "</td>\r\n            <td><a href=\"#\"  ><font color=\"";
	echo $s["color"];
	echo "\">";
	echo $s["siteurl"];
	echo "</font></a></td>\r\n            <td>";
	echo $c["classname"];
	echo "</td>\r\n            <td>";
	echo $s["dayip"];
	echo "</td>\r\n            <td class=\"alexapr\" data=\"";
	echo $s["siteurl"] . "," . $s["siteid"];
	echo "\">";
	echo $s["alexa"];
	echo "/";
	echo $s["pr"];
	echo "</td>\r\n            <td>";
	echo $s["grade"];
	echo "</td>\r\n            <td class=\"status\">";
	$deny = ($s["denyinfo"] ? " ?" : "");

	switch ($s["status"]) {
	case 0:
		echo "<span class=\"notification error_bg\" title=\"" . $s["denyinfo"] . "\" onclick=\"alert('" . $s["denyinfo"] . "')\">新增待审" . $deny . "</span>";
		break;

	case 1:
		echo "<span class=\"notification error_bg\" title=\"" . $s["denyinfo"] . "\" onclick=\"alert('" . $s["denyinfo"] . "')\">拒绝" . $deny . "</span>";
		break;

	case 2:
		echo "<span class=\"notification error_bg\" title=\"" . $s["denyinfo"] . "\" onclick=\"alert('" . $s["denyinfo"] . "')\">锁定" . $deny . "</span>";
		break;

	case 3:
		echo "<span class=\"notification info_bg\" title=\"" . $s["denyinfo"] . "\" onclick=\"alert('" . $s["denyinfo"] . "')\">正常" . $deny . "</span>";
		break;

	case 4:
		echo "<span class=\"notification error_bg\" title=\"" . $s["denyinfo"] . "\" onclick=\"alert('" . $s["denyinfo"] . "')\">修改待审" . $deny . "</span>";
	}

	echo "  </td>\r\n            <td siteid='";
	echo $s["siteid"];
	echo "' class=\"uld_img\"><a href=\"";
	echo url("admin/site.edit?siteid=" . $s["siteid"]);
	echo "\"><img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/pencil_gray.png\" alt=\"\" border=\"0\" title=\"编辑\"/></a> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/access_ok_gray.png\" alt=\"\" border=\"0\" class=\"unlock\" title=\"激活\" /> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/lock-icon.png\" alt=\"\" border=\"0\" class=\"lock\" title=\"锁定\"/> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/trashcan_gray.png\" alt=\"\" border=\"0\"  class=\"del\" title=\"删除\"/><!-- <a href=\"";
	echo url("admin/zone.get_list?&searchtype=siteid&search=" . $s["siteid"]);
	echo "\"><img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/ads_zone.png\" alt=\"\" border=\"0\"  class=\"zone\" title=\"查看广告位\"/></a>--></td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n\r\nfunction uld(type,e) {\r\n\tvar html = '';\r\n   \tvar width = 400;\r\n\tif (type == 'del') {\r\n\t\turl = '";
echo url("admin/site.del");
echo "';\r\n\t\ttitle = '删除网站';\r\n\t\ttext = '确定要删除吗？删除后无法恢复!';\r\n\t}\r\n\tif (type == 'lock') {\r\n\t\turl = '";
echo url("admin/site.lock");
echo "';\r\n\t\thtml = '<span class=\"notification error_bg\">锁定</span>';\r\n\t    text = '<textarea  class=\"input_text span70 textarea_text\" name=\"log_text\" id=\"log_text\">信息不完整或是作弊嫌疑！</textarea>';\r\n\t\ttitle = '锁定网站';\r\n\t}\r\n\tif (type == 'unlock') {\r\n\t\turl = '";
echo url("admin/site.unlock");
echo "';\r\n\t\thtml = '<span class=\"notification info_bg\">活动</span>';\r\n\t    text = '<textarea  class=\"input_text span70 textarea_text\" name=\"log_text\" id=\"log_text\"></textarea>';\r\n\t\ttitle = '激活网站';\r\n\t}\r\n \t\t \r\n\tbox.confirm(text,width,title,function(bool){ \r\n\t\t var log_text = $(\"#log_text\").text(); \r\n\t\t if(e) site_id = $(e).parent().attr(\"siteid\");\r\n\t\t site_id = site_id.split(',');\r\n\t\t if (bool) {\r\n\t\t\t if (type == 'del') {\r\n\t\t\t\t$.each(site_id, function(i,val){   \r\n\t\t\t\t\t$(\"#site_\"+val).parent().parent().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t});  \r\n\t\t\t } \r\n\t\t\t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: url,\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'siteid=' + site_id + '&log_text=' + log_text,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\r\n\t\t\t\t\t $.each(site_id, function(i,val){    \r\n\t\t\t\t\t\t \t$(\"#site_\"+val).parent().parent().find('.status').html(html);\r\n\t\t\t\t\t });   \r\n\t\t\t\t\t$(\".success\").show();\r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n}\r\n\r\n$(\".unlock,.lock,.del\").click(function(){\r\n\tuld(this.className,this);\r\n});\r\n\r\n$(\"#choose_sb\").click(function(){\r\n\tvar arr=[];\r\n\tvar choose_type = $(\"#choose_type\").val();\r\n\tif(!choose_type){\r\n\t\tbox.alert('批量操作请选择一个操作对像',300);\r\n\t\treturn ;\r\n\t}\r\n \tvar arrChk=$(\"input[name='siteid']:checked\"); \r\n     \r\n    for (var i=0;i<arrChk.length;i++)\r\n    {\r\n        var v = arrChk[i].value;\r\n\t\tarr.push(v);\r\n\t\t\r\n    }\r\n\tsite_id = arr.join(\",\");\r\n\tuld(choose_type);\r\n});\r\n\r\n$(\"#select_id\").click(function(){\r\n \t var a = $(\"#select_id\").attr(\"checked\");\r\n\t if(a!='checked') a = false;\r\n     $(\"input[name='siteid']\").attr(\"checked\",a);\r\n});\r\n\r\n \r\nvar c_alexapr = $.cookie('alexapr'); \r\nif(!c_alexapr){\r\n\tvar a = $(\".alexapr\");\r\n\t$.each(a, function(i,o){     \r\n\t\tif($(o).html() == '0/0'){\t\r\n\t\t\t$(o).html('<img src=\"";
echo SRC_TPL_DIR;
echo "/images/loading.gif\" alt=\"\" border=\"0\" />')\r\n\t\t\t$.get(\"";
echo url("admin/site.get_alexapr?data=");
echo "\"+$(o).attr(\"data\"), function(result){\r\n\t\t\t \t$(o).html(result)\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n\t$.cookie('alexapr','1',{ expires: 2 }); \r\n}\r\n </script>\r\n";

?>
