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
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid\" >\r\n    <h3 class=\"heading\">\r\n      ";
$text_type = "所有内容";

foreach ($GLOBALS["article_type"] as $key => $val ) {
	if ($type == $key) {
		$text_type = $val;
	}
}

echo $text_type;
echo "    </h3>\r\n\t\r\n\t <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/article.get_list?type=" . $type);
echo "\" class=\"tab-btn  list ";

if ($status == "") {
	echo "tab-state-active";
}

echo "\">全部列表</a> <a href=\"";
echo url("admin/article.get_list?status=y&type=" . $type);
echo "\" class=\"tab-btn unlock ";

if ($status == "y") {
	echo "tab-state-active";
}

echo "\"> 已审核</a> <a href=\"";
echo url("admin/article.get_list?status=n&type=" . $type);
echo "\" class=\"tab-btn lock ";

if ($status == "n") {
	echo "tab-state-active";
}

echo "\">已锁定</a> </div>\r\n    </div>\r\n\t\r\n\t\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"tb_sts\"><a href=\"";
echo url("admin/article.add");
echo "\"  class=\"tab-btn add \">发布文章公告</a></div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"unlock\">激活</option>\r\n              <option value=\"lock\" >锁定</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n          </div>\r\n        </div>\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\">\r\n            <form method=\"post\">\r\n              搜索：\r\n              <select name=\"searchtype\">\r\n                <option value=\"title\" ";

if ($searchtype == "title") {
	echo "selected";
}

echo ">标题</option>\r\n              </select>\r\n              <input type=\"text\" class=\"input_text span30\" name=\"search\" value=\"";
echo $search;
echo "\">\r\n              <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n            </form>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <table id=\"dt_inbox\" class=\"dataTable\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\"></th>\r\n            <th>标题</th>\r\n            <th>类型</th>\r\n            <th>时间</th>\r\n            <th>状态</th>\r\n            <th>操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $article as $a ) {
	echo "          <tr class=\"unread odd\">\r\n            <td><input type=\"checkbox\" name=\"articleid\" id=\"article_";
	echo $a["articleid"];
	echo "\" value=\"";
	echo $a["articleid"];
	echo "\"></td>\r\n            <td><a href=\"#\" target=\"_blank\"><font color=\"";
	echo $a["color"];
	echo "\">";
	echo $a["top"] == "2" ? "[置顶] " : "";
	echo $a["title"];
	echo "</font></a></td>\r\n            <td>";

	foreach ($GLOBALS["article_type"] as $key => $val ) {
		if ($a["type"] == $key) {
			echo $val;
		}
	}

	echo "</td>\r\n            <td>";
	echo $a["addtime"];
	echo "</td>\r\n            <td class=\"status\">";

	switch ($a["status"]) {
	case "y":
		echo "<span class=\"notification info_bg\">活动</span>";
		break;

	case "n":
		echo "<span class=\"notification error_bg\">锁定</span>";
		break;
	}

	echo "            </td>\r\n            <td articleid='";
	echo $a["articleid"];
	echo "' class=\"uld_img\"><a href=\"";
	echo url("admin/article.edit?articleid=" . $a["articleid"]);
	echo "\"><img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/pencil_gray.png\" alt=\"\" border=\"0\" /></a> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/access_ok_gray.png\" alt=\"\" border=\"0\" class=\"unlock\" /> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/lock-icon.png\" alt=\"\" border=\"0\" class=\"lock\"/> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/trashcan_gray.png\" alt=\"\" border=\"0\"  class=\"del\" /></td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n\r\nfunction uld(type,e) {\r\n\tvar html = '';\r\n   \tvar width = 400;\r\n\tif (type == 'del') {\r\n\t\turl = '";
echo url("admin/article.del");
echo "';\r\n\t\ttitle = '删除文章';\r\n\t\ttext = '确定要删除吗？删除后无法恢复!';\r\n\t}\r\n\tif (type == 'lock') {\r\n\t\turl = '";
echo url("admin/article.lock");
echo "';\r\n\t\thtml = '<span class=\"notification error_bg\">锁定</span>';\r\n\t    text = '确认锁定吗';\r\n\t\ttitle = '锁定文章';\r\n\t}\r\n\tif (type == 'unlock') {\r\n\t\turl = '";
echo url("admin/article.unlock");
echo "';\r\n\t\thtml = '<span class=\"notification info_bg\">活动</span>';\r\n\t    text = '确认激活吗';\r\n\t\ttitle = '激活文章';\r\n\t}\r\n \t\t \r\n\tbox.confirm(text,width,title,function(bool){ \r\n\t\t if(e) article_id = $(e).parent().attr(\"articleid\");\r\n\t\t article_id = article_id.split(',');\r\n\t\t if (bool) {\r\n\t\t\t if (type == 'del') {\r\n\t\t\t\t$.each(article_id, function(i,val){   \r\n\t\t\t\t\t$(\"#article_\"+val).parent().parent().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t});  \r\n\t\t\t } \r\n\t\t\t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: url,\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'articleid=' + article_id ,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\r\n\t\t\t\t\t $.each(article_id, function(i,val){    \r\n\t\t\t\t\t\t \t$(\"#article_\"+val).parent().parent().find('.status').html(html);\r\n\t\t\t\t\t });   \r\n\t\t\t\t\t$(\".success\").show();\r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n}\r\n\r\n$(\".unlock,.lock,.del\").click(function(){\r\n\tuld(this.className,this);\r\n});\r\n\r\n$(\"#choose_sb\").click(function(){\r\n\tvar arr=[];\r\n\tvar choose_type = $(\"#choose_type\").val();\r\n\tif(!choose_type){\r\n\t\tbox.alert('批量操作请选择一个操作对像',300);\r\n\t\treturn ;\r\n\t}\r\n \tvar arrChk=$(\"input[name='articleid']:checked\"); \r\n     \r\n    for (var i=0;i<arrChk.length;i++)\r\n    {\r\n        var v = arrChk[i].value;\r\n\t\tarr.push(v);\r\n\t\t\r\n    }\r\n\tarticle_id = arr.join(\",\");\r\n\tuld(choose_type);\r\n});\r\n\r\n$(\"#select_id\").click(function(){\r\n \t var a = $(\"#select_id\").attr(\"checked\");\r\n\t if(a!='checked') a = false;\r\n     $(\"input[name='articleid']\").attr(\"checked\",a);\r\n});\r\n </script>\r\n";

?>
