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
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid mt60\" >\r\n    <h3 class=\"heading tab_heading\">数据导入记录</h3>\r\n    <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/orders.get_list");
echo "\" class=\"tab-btn  list ";

if ($status == "") {
	echo "tab-state-active";
}

echo "\">全部列表</a> <a href=\"";
echo url("admin/orders.get_list?status=0");
echo "\" class=\"tab-btn lock ";

if ($status == "0") {
	echo "tab-state-active";
}

echo "\">已撤销</a> </div>\r\n    </div>\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"tb_sts\"><a href=\"";
echo url("admin/import.add");
echo "\"  class=\"tab-btn add add_pay\">数据导入</a> </div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"revocation\" >撤销</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n          </div>\r\n        </div>\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\">\r\n            <form method=\"post\">\r\n              搜索：\r\n              <select name=\"searchtype\">\r\n                <option value=\"username\" ";

if ($searchtype == "username") {
	echo "selected";
}

echo ">站长名称</option>\r\n                <option value=\"uid\" ";

if ($searchtype == "uid") {
	echo "selected";
}

echo ">站长ID</option>\r\n                <option value=\"planname\" ";

if ($searchtype == "planname") {
	echo "selected";
}

echo ">计划名称</option>\r\n                <option value=\"planid\" ";

if ($searchtype == "planid") {
	echo "selected";
}

echo ">计划ID</option>\r\n              </select>\r\n              <input type=\"text\" class=\"input_text span30\" name=\"search\" value=\"";
echo $search;
echo "\">\r\n              <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n            </form>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <table id=\"dt_inbox\" class=\"dataTable table-bordered \">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th><span class=\"table_checkbox sorting_disabled\" style=\"width: 13px;\"><span class=\"table_checkbox sorting_disabled\" style=\"width: 13px;\">\r\n              <input type=\"checkbox\" name=\"select_id\" id=\"select_id\" />\r\n              </span></span></th>\r\n            <th>时间</th>\r\n            <th>导入会员</th>\r\n            <th>计划名称</th>\r\n            <th>结算数</th>\r\n            <th>佣金</th>\r\n            <th>操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $import as $i ) {
	echo "          <tr class=\"unread odd\"  >\r\n            <td ><input type=\"checkbox\" name=\"importid\" id=\"import_";
	echo $i["importid"];
	echo "\" value=\"";
	echo $i["importid"];
	echo "\" /></td>\r\n            <td >";
	echo $i["addtime"];
	echo "<br>\r\n              <span class=\"gray\">";
	echo $i["data"];
	echo "</span></td>\r\n            <td >";
	echo $i["username"];
	echo "</td>\r\n            <td >";
	echo $i["planname"];
	echo "<br>\r\n              <span class=\"gray\">";
	echo ucfirst($i["plantype"]);
	echo "</span></td>\r\n            <td>";
	echo $i["numaff"];
	echo "<br />\r\n              <span class=\"gray\">";
	echo $i["numadv"];
	echo "</span></td>\r\n            <td >";
	echo 0 < $i["sumpay"] ? round($i["sumpay"], 3) : 0;
	echo " <br />\r\n              <span class=\"gray\">";
	echo 0 < $i["sumadvpay"] ? round($i["sumadvpay"], 3) : 0;
	echo " </span></td>\r\n            <td importid='";
	echo $i["importid"];
	echo "' class=\"status\">";

	switch ($i["status"]) {
	case 0:
		echo " <a href=\"#\" class=\"revocation\">撤销</a>";
		break;

	case 1:
		echo "<span class=\"notification error_bg\">已撤销</span>";
		break;
	}

	echo "            </td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n function uld(type,e) {  \r\n\tvar html = '';\r\n   \tvar width = 400;\r\n\tif (type == 'del') {\r\n\t\turl = '";
echo url("admin/import.del");
echo "';\r\n\t\ttitle = '删除订单';\r\n\t\ttext = '确定要删除吗？删除后无法恢复!';\r\n\t}\r\n\tif (type == 'revocation') {\r\n\t\turl = '";
echo url("admin/import.revocation");
echo "';\r\n\t    text = '确认撤销吗';\r\n\t\ttitle = '数据撤销';\r\n\t\thtml = '<span class=\"notification error_bg\">已撤销</span>';\r\n\t} \r\n \r\n\t\r\n\tbox.confirm(text,width,title,function(bool){ \r\n\t \t if(e) import_id = $(e).parent().attr(\"importid\");\r\n\t\t import_id = import_id.split(','); \r\n\t\t if (bool) {\r\n\t\t\t if (type == 'del') {\r\n\t\t\t\t$.each(import_id, function(i,val){   \r\n\t\t\t\t\t$(\"#import_\"+val).parent().parent().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t});  \r\n\t\t\t } \r\n\t\t\t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: url,\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'importid=' + import_id ,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\r\n\t\t\t\t\t $.each(import_id, function(i,val){     \r\n\t\t\t\t\t\t \t$(\"#import_\"+val).parent().parent().find('.status').html(html);\r\n\r\n\t\t\t\t\t });   \r\n\t\t\t\t\t \r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n}\r\n\r\n$(\".revocation,.del\").click(function(){\r\n\tuld(this.className,this);\r\n});\r\n\r\n$(\"#choose_sb\").click(function(){\r\n\tvar arr=[];\r\n\tvar choose_type = $(\"#choose_type\").val();\r\n\tif(!choose_type){\r\n\t\tbox.alert('批量操作请选择一个操作对像',300);\r\n\t\treturn ;\r\n\t}\r\n \tvar arrChk=$(\"input[name='importid']:checked\"); \r\n     \r\n    for (var i=0;i<arrChk.length;i++)\r\n    {\r\n        var v = arrChk[i].value;\r\n\t\tarr.push(v);\r\n\t\t\r\n    }\r\n\t\r\n\timport_id = arr.join(\",\");  \r\n\tuld(choose_type);\r\n});\r\n\r\n$(\"#select_id\").click(function(){\r\n \t var a = $(\"#select_id\").attr(\"checked\");\r\n\t if(a!='checked') a = false;\r\n     $(\"input[name='importid']\").attr(\"checked\",a);\r\n});\r\n\r\n \r\n </script>\r\n";

?>
