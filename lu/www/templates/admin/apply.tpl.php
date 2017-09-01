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
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid\" >\r\n    <h3 class=\"heading\">\r\n      申请列表 </h3>\r\n\t\r\n\t   <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/apply.get_list");
echo "\" class=\"tab-btn  list ";

if ($status == "") {
	echo "tab-state-active";
}

echo "\">\r\n所有申请</a> <a href=\"";
echo url("admin/apply.get_list?status=2");
echo "\" class=\"tab-btn unlock ";

if ($status == "2") {
	echo "tab-state-active";
}

echo "\"> 已审批</a> <a href=\"";
echo url("admin/apply.get_list?status=1");
echo "\" class=\"tab-btn lock ";

if ($status == "1") {
	echo "tab-state-active";
}

echo "\">已拒绝</a> <a href=\"";
echo url("admin/apply.get_list?status=0");
echo "\" class=\"tab-btn lock ";

if ($status == "0") {
	echo "tab-state-active";
}

echo "\">待审</a> </div>\r\n    </div>\r\n\t\r\n    <div  class=\"dataTables_wrapper \">\r\n\t\r\n\t\r\n      <div class=\"tb_sts\"> <form method=\"post\">\r\n          \r\n              <select name=\"searchtype\">\r\n                <option value=\"username\" ";

if ($searchtype == "username") {
	echo "selected";
}

echo ">会员名称</option>\r\n\t\t\t\t <option value=\"uid\" ";

if ($searchtype == "uid") {
	echo "selected";
}

echo ">会员ID</option>\r\n\t\t\t   <option value=\"planid\" ";

if ($searchtype == "planid") {
	echo "selected";
}

echo ">计划ID</option>\r\n              </select>\r\n              <input type=\"text\" class=\"input_text\" name=\"search\" value=\"";
echo $search;
echo "\">\r\n              <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n            </form></div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"unlock\">通过</option>\r\n              <option value=\"lock\" >拒绝</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n          </div>\r\n        </div>\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\">\r\n            \r\n          </div>\r\n        </div>\r\n      </div>\r\n      <table id=\"dt_inbox\" class=\"dataTable\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\"></th>\r\n            <th width=\"150\">申请计划</th>\r\n            <th width=\"80\">申请会员</th>\r\n            <th>拥有网站</th>\r\n            <th width=\"90\">审批人</th>\r\n            <th width=\"100\">状态</th>\r\n            <th>操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $apply as $s ) {
	$site = $sitedata = array();
	echo "          <tr class=\"unread odd\">\r\n            <td><input type=\"checkbox\" name=\"id\" id=\"apply_";
	echo $s["id"];
	echo "\" value=\"";
	echo $s["id"];
	echo "\"></td>\r\n            <td>";
	echo $s["planname"];
	echo "<br><span class=\"help-block\" style=\"margin: 0;\">";
	echo $s["addtime"];
	echo "<br>";
	echo $s["approvaltime"] == "0000-00-00 00:00:00" ? "" : $s["approvaltime"];
	echo "</span></td>\r\n            <td>";
	echo $s["username"];
	echo "</td>\r\n            <td>";

	if ($s["applysiteidtype"] == 1) {
		$site = dr("admin/site.get_list_ok", $s["uid"]);

		foreach ((array) $site as $sn ) {
			$sitedata[] = $sn["siteid"];
		}

		$site = $sitedata;
	}
	else {
		$site = explode(",", $s["siteid"]);
	}

	foreach ((array) $site as $sn ) {
		$se = dr("admin/site.get_one", $sn);
		$c = dr("admin/class.get_one", $se["sitetype"]);
		echo "<a href=javascript:window.open('http://" . $se["siteurl"] . "');>" . $se["siteurl"] . "</a>  <span class='help-block' style='margin: 0;'>Alexa/PR：" . $se["alexa"] . "/" . $se["pr"] . " 类型：" . $c["classname"] . " </span><br>";
	}

	echo "</td>\r\n            <td>";
	echo $s["approvaluser"];
	echo "</td>\r\n            <td class=\"status\">";

	switch ($s["status"]) {
	case 0:
		echo "<span class=\"notification error_bg\">等待待审</span>";
		break;

	case 1:
		echo "<span class=\"notification error_bg\">已被拒绝</span>";
		break;

	case 2:
		echo "<span class=\"notification info_bg\">通过</span>";
		break;
	}

	echo $s["denyinfo"] ? "<br>" . $s["denyinfo"] : "";
	echo "</td>\r\n            <td applyid='";
	echo $s["id"];
	echo "' class=\"uld_img\"><a href=\"";
	echo url("admin/apply.edit?siteid=" . $s["id"]);
	echo "\"></a> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/access_ok_gray.png\" alt=\"\" border=\"0\" class=\"unlock\" title=\"通过\" /> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/lock-icon.png\" alt=\"\" border=\"0\" class=\"lock\" title=\"拒绝\"/> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/trashcan_gray.png\" alt=\"\" border=\"0\"  class=\"del\" title=\"删除\"/></td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n\r\nfunction uld(type,e) {\r\n\tvar html = '';\r\n   \tvar width = 400;\r\n\tif (type == 'del') {\r\n\t\turl = '";
echo url("admin/apply.del");
echo "';\r\n\t\ttitle = '删除';\r\n\t\ttext = '确定要删除吗？删除后无法恢复!';\r\n\t}\r\n\tif (type == 'lock') {\r\n\t\turl = '";
echo url("admin/apply.lock");
echo "';\r\n\t\thtml = '<span class=\"notification error_bg\">拒绝投放</span>';\r\n\t    text = '<textarea  class=\"input_text span70 textarea_text\" name=\"log_text\" id=\"log_text\">不能达到要求，申请不能通过！</textarea>';\r\n\t\ttitle = '拒绝投放';\r\n\t}\r\n\tif (type == 'unlock') {\r\n\t\turl = '";
echo url("admin/apply.unlock");
echo "';\r\n\t\thtml = '<span class=\"notification info_bg\">通过申请</span>';\r\n\t    text = '<textarea  class=\"input_text span70 textarea_text\" name=\"log_text\" id=\"log_text\"></textarea>';\r\n\t\ttitle = '通过申请';\r\n\t}\r\n \t\t \r\n\tbox.confirm(text,width,title,function(bool){ \r\n\t\t var log_text = $(\"#log_text\").text(); \r\n\t\t if(e) apply_id = $(e).parent().attr(\"applyid\");\r\n\t\t apply_id = apply_id.split(',');\r\n\t\t if (bool) {\r\n\t\t\t if (type == 'del') {\r\n\t\t\t\t$.each(apply_id, function(i,val){   \r\n\t\t\t\t\t$(\"#apply_\"+val).parent().parent().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t});  \r\n\t\t\t } \r\n\t\t\t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: url,\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'id=' + apply_id + '&log_text=' + log_text,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\r\n\t\t\t\t\t $.each(apply_id, function(i,val){    \r\n\t\t\t\t\t\t \t$(\"#apply_\"+val).parent().parent().find('.status').html(html);\r\n\t\t\t\t\t });   \r\n\t\t\t\t\t$(\".success\").show();\r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n}\r\n\r\n$(\".unlock,.lock,.del\").click(function(){\r\n\tuld(this.className,this);\r\n});\r\n\r\n$(\"#choose_sb\").click(function(){\r\n\tvar arr=[];\r\n\tvar choose_type = $(\"#choose_type\").val();\r\n\tif(!choose_type){\r\n\t\tbox.alert('批量操作请选择一个操作对像',300);\r\n\t\treturn ;\r\n\t}\r\n \tvar arrChk=$(\"input[name='id']:checked\"); \r\n     \r\n    for (var i=0;i<arrChk.length;i++)\r\n    {\r\n        var v = arrChk[i].value;\r\n\t\tarr.push(v);\r\n\t\t\r\n    }\r\n\tapply_id = arr.join(\",\");\r\n\tuld(choose_type);\r\n});\r\n\r\n$(\"#select_id\").click(function(){\r\n \t var a = $(\"#select_id\").attr(\"checked\");\r\n\t if(a!='checked') a = false;\r\n     $(\"input[name='id']\").attr(\"checked\",a);\r\n});\r\n\r\n \r\n \r\n </script>\r\n";

?>
