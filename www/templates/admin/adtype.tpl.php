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
echo "</div>\r\n<div id=\"main-content\">\r\n  <div id=\"main_nav\">\r\n    <div class=\"mn_lfet\"></div>\r\n    <div class=\"mn_right\">\r\n      <div class=\"mn_mt\"> 广告类型</div>\r\n    </div>\r\n  </div>\r\n  <div class=\"row-fluid mt60\" >\r\n    <div class=\"heading\">\r\n      <div class=\"mbox_new\"> <a href=\"";
echo url("admin/adtype.add");
echo "\" style=\" width:110px; text-align:left\"><i class=\"n_user\"></i>新增广告类型</a> </div>\r\n    </div>\r\n   \r\n    \r\n      <table id=\"dt_inbox\" class=\"dataTable\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th>ID</th>\r\n            <th>广告类型名称</th>\r\n            <th>应用范围</th>\r\n            <th>排序</th>\r\n            <th>说明</th>\r\n            <th>状态</th>\r\n            <th>操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $adtpl as $a ) {
	echo "          <tr class=\"unread odd\">\r\n            <td>";
	echo $a["id"];
	echo "</td>\r\n            <td>";
	echo $a["name"];
	echo "</td>\r\n            <td>";
	echo $a["statstype"];
	echo "</td>\r\n            <td >";
	echo $a["sort"];
	echo "</td>\r\n            <td >";
	echo $a["description"];
	echo "</td>\r\n            <td class=\"status\">";

	switch ($a["status"]) {
	case "y":
		echo "<span class=\"notification info_bg\">正常</span>";
		break;

	case "n":
		echo "<span class=\"notification error_bg\">锁定</span>";
		break;
	}

	echo "            </td>\r\n            <td adtplid='";
	echo $a["id"];
	echo "' class=\"uld_img\"><span id=\"adtpl_";
	echo $a["id"];
	echo "\" style=\"display:none\"></span><a href=\"";
	echo url("admin/adtype.edit?id=" . $a["id"]);
	echo "\"><img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/pencil_gray.png\" alt=\"\" border=\"0\" title=\"编辑\" /></a> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/access_ok_gray.png\" alt=\"\" border=\"0\" class=\"unlock\" title=\"激活\" /> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/lock-icon.png\" alt=\"\" border=\"0\" class=\"lock\" title=\"锁定\"/> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/trashcan_gray.png\" alt=\"\" border=\"0\"  class=\"del\" title=\"删除\"/></td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n         \r\n      </div>\r\n  </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n\r\nfunction uld(type,e) {\r\n\tvar html = '';\r\n   \tvar width = 400;\r\n\tif (type == 'del') {\r\n\t\turl = '";
echo url("admin/adtype.del");
echo "';\r\n\t\ttitle = '删除广告模板';\r\n\t\ttext = '确定要删除吗？删除后无法恢复!<br>现有投放的代码无法显示';\r\n\t}\r\n\tif (type == 'lock') {\r\n\t\turl = '";
echo url("admin/adtype.lock");
echo "';\r\n\t\thtml = '<span class=\"notification error_bg\">锁定</span>';\r\n\t    text = '确认锁定吗？锁定后现有投放的代码无法显示';\r\n\t\ttitle = '锁定奖品';\r\n\t}\r\n\tif (type == 'unlock') {\r\n\t\turl = '";
echo url("admin/adtype.unlock");
echo "';\r\n\t\thtml = '<span class=\"notification info_bg\">活动</span>';\r\n\t    text = '确认激活吗';\r\n\t\ttitle = '激活信息';\r\n\t}\r\n \t\t \r\n\tbox.confirm(text,width,title,function(bool){ \r\n\t\t if(e) adtpl_id = $(e).parent().attr(\"adtplid\");\r\n\t\t adtpl_id = adtpl_id.split(',');\r\n\t\t if (bool) {\r\n\t\t\t if (type == 'del') {\r\n\t\t\t\t$.each(adtpl_id, function(i,val){   \r\n\t\t\t\t\t$(\"#adtpl_\"+val).parent().parent().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t});  \r\n\t\t\t } \r\n\t\t\t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: url,\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'id=' + adtpl_id ,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\r\n\t\t\t\t\t $.each(adtpl_id, function(i,val){    \r\n\t\t\t\t\t\t \t$(\"#adtpl_\"+val).parent().parent().find('.status').html(html);\r\n\t\t\t\t\t });   \r\n\t\t\t\t\t$(\".success\").show();\r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n}\r\n\r\n$(\".unlock,.lock,.del\").click(function(){\r\n\tuld(this.className,this);\r\n});\r\n\r\n$(\"#choose_sb\").click(function(){\r\n\tvar arr=[];\r\n\tvar choose_type = $(\"#choose_type\").val();\r\n\tif(!choose_type){\r\n\t\tbox.alert('批量操作请选择一个操作对像',300);\r\n\t\treturn ;\r\n\t}\r\n \tvar arrChk=$(\"input[name='id']:checked\"); \r\n     \r\n    for (var i=0;i<arrChk.length;i++)\r\n    {\r\n        var v = arrChk[i].value;\r\n\t\tarr.push(v);\r\n\t\t\r\n    }\r\n\tgift_id = arr.join(\",\");\r\n\tuld(choose_type);\r\n});\r\n\r\n$(\"#select_id\").click(function(){\r\n \t var a = $(\"#select_id\").attr(\"checked\");\r\n\t if(a!='checked') a = false;\r\n     $(\"input[name='id']\").attr(\"checked\",a);\r\n});\r\n </script>\r\n";

?>
