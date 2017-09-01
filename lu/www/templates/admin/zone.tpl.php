<?php

if (!defined("IN_ZYADS")) {
	exit();
}

TPL::display("header");
echo "<link rel=\"stylesheet\" href=\"";
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
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid\" >\r\n    <h3 class=\"heading\"> 广告位管理 </h3>\r\n    <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/zone.get_list");
echo "\" class=\"tab-btn  list ";

if ($plantype == "") {
	echo "tab-state-active";
}

echo "\">全部广告位</a>\r\n        ";

foreach (explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
	echo "        <a href=\"";
	echo url("admin/zone.get_list?plantype=" . $t);
	echo "\" class=\"tab-btn ad";
	echo $t;
	echo " ";

	if ($plantype == $t) {
		echo "tab-state-active";
	}

	echo "\"> ";
	echo strtoupper($t);
	echo "广告位</a>\r\n        ";
}

echo "      </div>\r\n    </div>\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"tb_sts\">\r\n        <form method=\"post\">\r\n          <select name=\"searchtype\">\r\n            <option value=\"zoneid\" ";

if ($searchtype == "zoneid") {
	echo "selected";
}

echo ">广告位ID</option>\r\n            <option value=\"siteid\" ";

if ($searchtype == "siteid") {
	echo "selected";
}

echo ">网站ID</option>\r\n            <option value=\"uid\" ";

if ($searchtype == "uid") {
	echo "selected";
}

echo ">站长ID</option>\r\n          </select>\r\n          <input type=\"text\" class=\"input_text\" name=\"search\" value=\"";
echo $search;
echo "\">\r\n          <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n        </form>\r\n      </div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n          </div>\r\n        </div>\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\"> </div>\r\n        </div>\r\n      </div>\r\n      <table id=\"dt_inbox\" class=\"dataTable\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\"></th>\r\n            <th>ID</th>\r\n            <th>站长名称</th>\r\n            <th>广告位名称</th>\r\n            <th>尺寸</th>\r\n            <th>广告类型</th>\r\n            <th>计费形式</th>\r\n            <th>操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $zone as $z ) {
	$u = dr("admin/user.get_one", $z["uid"]);
	$tpl = dr("affiliate/adtpl.get_one", $z["adtplid"]);
	echo "          <tr class=\"unread odd\">\r\n            <td><input type=\"checkbox\" name=\"zoneid\" id=\"zone_";
	echo $z["zoneid"];
	echo "\" value=\"";
	echo $z["zoneid"];
	echo "\"></td>\r\n            <td>";
	echo $z["zoneid"];
	echo "</td>\r\n            <td>";
	echo $u["username"];
	echo "</td>\r\n            <td>";
	echo $z["zonename"];
	echo "</td>\r\n            <td>";
	echo $z["width"] . "x" . $z["height"];
	echo "</td>\r\n            <td>";
	echo $tpl["tplname"];
	echo "</td>\r\n            <td>";
	echo $z["plantype"];
	echo "</td>\r\n            <td zoneid='";
	echo $z["zoneid"];
	echo "' class=\"uld_img\"><a href=\"";
	echo url("admin/zone.edit?zoneid=" . $z["zoneid"]);
	echo "\" target=\"_blank\" ><img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/pencil_gray.png\" alt=\"\" border=\"0\" title=\"编辑\"/></a><img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/trashcan_gray.png\" alt=\"\" border=\"0\"  class=\"del\" title=\"删除\"/></td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n\r\nfunction uld(type,e) {\r\n\tvar html = '';\r\n   \tvar width = 400;\r\n\tif (type == 'del') {\r\n\t\turl = '";
echo url("admin/zone.del");
echo "';\r\n\t\ttitle = '删除广告位';\r\n\t\ttext = '确定要删除吗？删除后无法恢复!';\r\n\t}\r\n\t \r\n \t\t \r\n\tbox.confirm(text,width,title,function(bool){ \r\n\t\t if(e) zone_id = $(e).parent().attr(\"zoneid\");\r\n\t\t zone_id = zone_id.split(',');\r\n\t\t if (bool) {\r\n\t\t\t if (type == 'del') {\r\n\t\t\t\t$.each(zone_id, function(i,val){   \r\n\t\t\t\t\t$(\"#zone_\"+val).parent().parent().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t});  \r\n\t\t\t } \r\n\t\t\t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: url,\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'zoneid=' + zone_id ,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\r\n\t\t\t\t\t $.each(zone_id, function(i,val){    \r\n\t\t\t\t\t\t \t$(\"#zone_\"+val).parent().parent().find('.status').html(html);\r\n\t\t\t\t\t });   \r\n\t\t\t\t\t$(\".success\").show();\r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n}\r\n\r\n$(\".unlock,.lock,.del\").click(function(){\r\n\tuld(this.className,this);\r\n});\r\n\r\n$(\"#choose_sb\").click(function(){\r\n\tvar arr=[];\r\n\tvar choose_type = $(\"#choose_type\").val();\r\n\tif(!choose_type){\r\n\t\tbox.alert('批量操作请选择一个操作对像',300);\r\n\t\treturn ;\r\n\t}\r\n \tvar arrChk=$(\"input[name='zoneid']:checked\"); \r\n     \r\n    for (var i=0;i<arrChk.length;i++)\r\n    {\r\n        var v = arrChk[i].value;\r\n\t\tarr.push(v);\r\n\t\t\r\n    }\r\n\tzone_id = arr.join(\",\");\r\n\tuld(choose_type);\r\n});\r\n\r\n$(\"#select_id\").click(function(){\r\n \t var a = $(\"#select_id\").attr(\"checked\");\r\n\t if(a!='checked') a = false;\r\n     $(\"input[name='zoneid']\").attr(\"checked\",a);\r\n});\r\n\r\n \r\n \r\n </script> \r\n";

?>
