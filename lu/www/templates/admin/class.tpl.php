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
echo "</div>\r\n<div id=\"main-content\">\r\n   <div class=\"row-fluid mt60\" >\r\n    <h3 class=\"heading tab_heading\">分类管理</h3>\r\n\t\r\n\t\r\n    <div class=\"tab users\">\r\n\t\r\n\t<div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/class.get_list");
echo "\" class=\"tab-btn list  ";
if ((RUN_ACTION == "get_list") && !$type) {
	echo "tab-state-active";
}

echo "\">所有分类</a> ";

foreach ($GLOBALS["class_type"] as $k => $v ) {
	echo " <a href=\"";
	echo url("admin/class.get_list?type=" . $k);
	echo "\" class=\"tab-btn class_";
	echo $k;
	echo " ";

	if ($type == $k) {
		echo "tab-state-active";
	}

	echo "\"> ";
	echo $v;
	echo "</a> ";
}

echo "  </div>\r\n\t\r\n     \r\n    </div>\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"tb_sts\"><a href=\"";
echo url("admin/class.add");
echo "\"  class=\"tab-btn add \">新增分类</a></div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <table id=\"dt_inbox\" class=\"dataTable\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\"></th>\r\n            <th>ID</th>\r\n            <th>分类名称</th>\r\n            <th>分类类型</th>\r\n            <th>操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $class as $c ) {
	echo "          <tr class=\"unread odd\">\r\n            <td><input type=\"checkbox\" name=\"classid\" id=\"class_";
	echo $c["classid"];
	echo "\" value=\"";
	echo $c["classid"];
	echo "\"></td>\r\n            <td>";
	echo $c["classid"];
	echo "</td>\r\n            <td>";
	echo $c["classname"];
	echo "</td>\r\n            <td>";

	foreach ($GLOBALS["class_type"] as $key => $val ) {
		if ($c["type"] == $key) {
			echo $val;
		}
	}

	echo "</td>\r\n            <td classid='";
	echo $c["classid"];
	echo "' class=\"uld_img\"><a href=\"";
	echo url("admin/class.edit?classid=" . $c["classid"]);
	echo "\"><img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/pencil_gray.png\" alt=\"\" border=\"0\" title=\"编辑\" /></a>  <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/trashcan_gray.png\" alt=\"\" border=\"0\"  class=\"del\" title=\"删除\"/></td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n\r\nfunction uld(type,e) {\r\n\tvar html = '';\r\n   \tvar width = 400;\r\n\tif (type == 'del') {\r\n\t\turl = '";
echo url("admin/class.del");
echo "';\r\n\t\ttitle = '删除分类';\r\n\t\ttext = '确定要删除吗？删除后无法恢复!';\r\n\t}\r\n\t \r\n\tbox.confirm(text,width,title,function(bool){ \r\n\t\t if(e) class_id = $(e).parent().attr(\"classid\");\r\n\t\t class_id = class_id.split(',');\r\n\t\t if (bool) {\r\n\t\t\t if (type == 'del') {\r\n\t\t\t\t$.each(class_id, function(i,val){   \r\n\t\t\t\t\t$(\"#class_\"+val).parent().parent().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t});  \r\n\t\t\t } \r\n\t\t\t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: url,\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'classid=' + class_id ,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\r\n\t\t\t\t\t $.each(class_id, function(i,val){    \r\n\t\t\t\t\t\t \t$(\"#class_\"+val).parent().parent().find('.status').html(html);\r\n\t\t\t\t\t });   \r\n\t\t\t\t\t$(\".success\").show();\r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n}\r\n\r\n$(\".del\").click(function(){\r\n\tuld(this.className,this);\r\n});\r\n\r\n$(\"#choose_sb\").click(function(){\r\n\tvar arr=[];\r\n\tvar choose_type = $(\"#choose_type\").val();\r\n\tif(!choose_type){\r\n\t\tbox.alert('批量操作请选择一个操作对像',300);\r\n\t\treturn ;\r\n\t}\r\n \tvar arrChk=$(\"input[name='classid']:checked\"); \r\n     \r\n    for (var i=0;i<arrChk.length;i++)\r\n    {\r\n        var v = arrChk[i].value;\r\n\t\tarr.push(v);\r\n\t\t\r\n    }\r\n\tclass_id = arr.join(\",\");\r\n\tuld(choose_type);\r\n});\r\n\r\n$(\"#select_id\").click(function(){\r\n \t var a = $(\"#select_id\").attr(\"checked\");\r\n\t if(a!='checked') a = false;\r\n     $(\"input[name='classid']\").attr(\"checked\",a);\r\n});\r\n </script>\r\n";

?>
