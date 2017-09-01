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

echo ">\r\n  <!-- <a class=\"close\" href=\"javascript:;\">×</a>-->\r\n  <strong>操作成功.</strong> </div>\r\n  \r\n<div class=\"alert err\" ";

if (!$_SESSION["err"]) {
	echo "style=\"display:none\"";
}

echo ">\r\n  <!-- <a class=\"close\" href=\"javascript:;\">×</a>-->\r\n  <strong>操作失败.</strong> </div>\r\n  \r\n<div id=\"sidebar\">\r\n  ";
TPL::display("sidebar");
echo "</div>\r\n<div id=\"main-content\">\r\n  \r\n  <div class=\"row-fluid mt60\" >\r\n    <h3 class=\"heading\"> 登入日志 </h3>\r\n    <div class=\"tab users\">\r\n      <div class=\"tab-header-right  left\"> <a href=\"";
echo url("admin/loginlog.get_list");
echo "\" class=\"tab-btn  list ";

if ($status === "") {
	echo "tab-state-active";
}

echo "\">全部列表</a> <a href=\"";
echo url("admin/loginlog.get_list?status=1");
echo "\" class=\"tab-btn tab_success ";

if ($status === "1") {
	echo "tab-state-active";
}

echo "\"> 登入成功</a> <a href=\"";
echo url("admin/loginlog.get_list?status=2");
echo "\" class=\"tab-btn tab_error ";

if ($status === "2") {
	echo "tab-state-active";
}

echo "\" id=\"t_pay\">登入失败</a></div>\r\n    </div>\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n          </div>\r\n        </div>\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\">\r\n            <form method=\"post\">\r\n              搜索：\r\n              <select name=\"searchtype\">\r\n                <option value=\"username\" ";

if ($searchtype == "username") {
	echo "selected";
}

echo ">登入名称</option>\r\n\t\t\t\t <option value=\"ip\" ";

if ($searchtype == "ip") {
	echo "selected";
}

echo ">登入IP</option>\r\n              </select>\r\n              <input type=\"text\" class=\"input_text span30\" name=\"search\" value=\"";
echo $search;
echo "\">\r\n              <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n            </form>\r\n          </div>\r\n        </div>\r\n      </div>\r\n\t  \r\n\t   \r\n      <table id=\"dt_inbox\" class=\"dataTable\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\"></th>\r\n            <th>登入名称</th>\r\n            <th>登入时间</th>\r\n            <th>登入IP</th>\r\n            <th>方式</th>\r\n            <th>状态</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $loginlog as $log ) {
	echo "          <tr class=\"unread odd\">\r\n            <td><input type=\"checkbox\" name=\"loginlogid\" id=\"log_";
	echo $log["id"];
	echo "\" value=\"";
	echo $log["id"];
	echo "\"></td>\r\n            <td>";
	echo $log["username"];
	echo "</td>\r\n            <td>";
	echo $log["time"];
	echo "</td>\r\n            <td>";
	echo $log["ip"] . "-" . convert_ip($log["ip"]);
	echo "</td>\r\n            <td>\r\n\t\t\t";

	if ($type == "1") {
		echo "QQ互联";
	}
	else {
		echo "网页";
	}

	echo "            </td>\r\n            <td class=\"status\">";

	switch ($log["status"]) {
	case 1:
		echo "<span class=\"notification info_bg\">成功</span>";
		break;

	case 2:
		echo "<span class=\"notification error_bg\">失败</span>";
		break;
	}

	echo "            </td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n\t  \r\n\t  \r\n\t  \r\n\t  \r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo " \r\n \r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n\r\nfunction uld(type,e) {\r\n\tvar html = '';\r\n   \tvar width = 400;\r\n\tif (type == 'del') {\r\n\t\turl = '";
echo url("admin/loginlog.del");
echo "';\r\n\t\ttitle = '删除日志';\r\n\t\ttext = '确定要删除吗？删除后无法恢复!';\r\n\t}\r\n\tbox.confirm(text,width,title,function(bool){ \r\n\t\t if(e) id = $(e).parent().attr(\"loginlogid\");\r\n\t\t id = id.split(',');\r\n\t\t if (bool) {\r\n\t\t\t if (type == 'del') {\r\n\t\t\t\t$.each(id, function(i,val){   \r\n\t\t\t\t\t$(\"#log_\"+val).parent().parent().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t});  \r\n\t\t\t } \r\n\t\t\t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: url,\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'id=' + id ,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\r\n\t\t\t\t\t $.each(id, function(i,val){    \r\n\t\t\t\t\t\t \t$(\"#log_\"+val).parent().parent().find('.status').html(html);\r\n\t\t\t\t\t });   \r\n\t\t\t\t\t$(\".success\").show();\r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n}\r\n\r\n \r\n\r\n$(\"#choose_sb\").click(function(){\r\n\tvar arr=[];\r\n\tvar choose_type = $(\"#choose_type\").val();\r\n\tif(!choose_type){\r\n\t\tbox.alert('批量操作请选择一个操作对像',300);\r\n\t\treturn ;\r\n\t}\r\n \tvar arrChk=$(\"input[name='loginlogid']:checked\"); \r\n     \r\n    for (var i=0;i<arrChk.length;i++)\r\n    {\r\n        var v = arrChk[i].value;\r\n\t\tarr.push(v);\r\n\t\t\r\n    }\r\n\tid = arr.join(\",\");\r\n\tuld(choose_type);\r\n});\r\n\r\n$(\"#select_id\").click(function(){\r\n \t var a = $(\"#select_id\").attr(\"checked\");\r\n\t if(a!='checked') a = false;\r\n     $(\"input[name='loginlogid']\").attr(\"checked\",a);\r\n});\r\n </script>\r\n";

?>
