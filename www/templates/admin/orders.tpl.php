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
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid mt60\" >\r\n    <h3 class=\"heading tab_heading\">订单管理</h3>\r\n    <div class=\"tab users\">\r\n       <div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/orders.get_list");
echo "\" class=\"tab-btn  list ";

if ($status == "") {
	echo "tab-state-active";
}

echo "\">全部列表</a> <a href=\"";
echo url("admin/orders.get_list?status=1");
echo "\" class=\"tab-btn unlock ";

if ($status == "1") {
	echo "tab-state-active";
}

echo "\"> 已确认</a> <a href=\"";
echo url("admin/orders.get_list?status=0");
echo "\" class=\"tab-btn lock ";

if ($status == "0") {
	echo "tab-state-active";
}

echo "\">待确认</a>  <a href=\"";
echo url("admin/orders.get_list?status=2");
echo "\" class=\"tab-btn lock ";

if ($status == "2") {
	echo "tab-state-active";
}

echo "\">锁定作废</a> </div>\r\n    </div>\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"tb_sts\"><a href=\"";
echo url("admin/import.add");
echo "\"  class=\"tab-btn add add_pay\">手动导入订单</a> </div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> \r\n         \r\n\t\t\t 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"unlock\" >确认有效</option>\r\n\t\t\t   <option value=\"lock\" >作废订单</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button> \r\n           \r\n          </div>\r\n        </div>\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\">\r\n            <form method=\"post\">\r\n              搜索：\r\n              <select name=\"searchtype\">\r\n\t\t\t   <option value=\"orderid\" ";

if ($searchtype == "orderid") {
	echo "selected";
}

echo ">订单号</option>\r\n                <option value=\"username\" ";

if ($searchtype == "username") {
	echo "selected";
}

echo ">会员名称</option>\r\n                <option value=\"uid\" ";

if ($searchtype == "uid") {
	echo "selected";
}

echo ">站长ID</option>\r\n\t\t\t\t<option value=\"planname\" ";

if ($searchtype == "planname") {
	echo "selected";
}

echo ">计划名称</option>\r\n\t\t\t\t<option value=\"planid\" ";

if ($searchtype == "planid") {
	echo "selected";
}

echo ">计划ID</option>\r\n              </select>\r\n              <input type=\"text\" class=\"input_text span30\" name=\"search\" value=\"";
echo $search;
echo "\">\r\n              <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n            </form>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      \r\n      <table id=\"dt_inbox\" class=\"dataTable table-bordered \">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th><span class=\"table_checkbox sorting_disabled\" style=\"width: 13px;\"><span class=\"table_checkbox sorting_disabled\" style=\"width: 13px;\">\r\n              <input type=\"checkbox\" name=\"select_id\" id=\"select_id\" />\r\n            </span></span></th>\r\n            <th>会员名称</th>\r\n            <th>订单号/状态</th>\r\n            <th>计划项目</th>\r\n            <th>商品名称</th>\r\n            <th>订单总价</th>\r\n            <th>订单佣金</th>\r\n            <th>联盟状态</th>\r\n            <th>操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $orders as $o ) {
	echo "          <tr class=\"unread odd\"  >\r\n            <td ><input type=\"checkbox\" name=\"orderid\" id=\"order_";
	echo $o["orderid"];
	echo "\" value=\"";
	echo $o["orderid"];
	echo "\" /></td>\r\n            <td >";
	echo $o["username"];
	echo "</td>\r\n            <td >";
	echo $o["ordersn"];
	echo "<br> <span style=\"color:#a3a3a3\">";
	echo $o["orderstatus"];
	echo "<br>";
	echo $o["addtime"];
	echo "</span></td>\r\n            <td >";
	echo $o["planname"];
	echo "</td>\r\n            <td><div   style=\"width:160px; overflow:hidden\">";
	echo str_replace("|", "<br>", $o["goodsname"]);
	echo "</div></td>\r\n            <td>\r\n\t\t\t";

	if (strstr($o["goodsprice"], "|")) {
		echo str_replace("|", "<br>+ ", $o["goodsprice"]) . "<br>=￥" . $o["orderamount"];
	}
	else {
		echo "￥" . $o["orderamount"];
	}

	echo "            </td>\r\n            <td> \r\n          \r\n             ";

	if (strstr($o["proportionaff"], "|")) {
		echo str_replace("|", "%<br>+ ", $o["proportionaff"]) . "%<br>=￥" . $o["payamountaff"];
	}
	else {
		echo $o["proportionaff"] . "%<br>=￥" . $o["payamountaff"];
	}

	echo "             </td>\r\n            <td classid='";
	echo $p["id"];
	echo "' class=\"status\">";

	if ($o["status"] == 0) {
		echo "<span class=\"notification error_bg\">待确认</span>";
	}

	if ($o["status"] == 1) {
		echo "<span class=\"notification info_bg\">已确认</span>";
	}

	if ($o["status"] == 2) {
		echo "<span class=\"notification error_bg\">已作废</span>";
	}

	echo "\t\t</td>\r\n             <td orderid='";
	echo $o["orderid"];
	echo "' class=\"uld_img\"> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/access_ok_gray.png\" alt=\"\" border=\"0\" class=\"unlock\" /> ";

	if ($o["status"] != "1") {
		echo "<img src=\"";
		echo SRC_TPL_DIR;
		echo "/images/lock-icon.png\" alt=\"\" border=\"0\" class=\"lock\"/>";
	}

	echo " <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/trashcan_gray.png\" alt=\"\" border=\"0\"  class=\"del\" /></td>\r\n          </tr>\r\n           \r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n \r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n function uld(type,e) {  \r\n\tvar html = '';\r\n   \tvar width = 400;\r\n\tif (type == 'del') {\r\n\t\turl = '";
echo url("admin/orders.del");
echo "';\r\n\t\ttitle = '删除订单';\r\n\t\ttext = '确定要删除吗？删除后无法恢复!';\r\n\t}\r\n\tif (type == 'lock') {\r\n\t\turl = '";
echo url("admin/orders.lock");
echo "';\r\n\t    text = '确认作废订单吗';\r\n\t\ttitle = '订单作废';\r\n\t\thtml = '<span class=\"notification error_bg\">订单作废</span>';\r\n\t} \r\n\tif (type == 'unlock') {\r\n\t   url = '";
echo url("admin/orders.unlock");
echo "';\r\n\t   text = '确认订单有效吗';\r\n\t   title = '确认订单';\r\n\t   html = '<span class=\"notification info_bg\">已确认</span>';\r\n\t} \r\n\t\r\n\tbox.confirm(text,width,title,function(bool){ \r\n\t \t if(e) order_id = $(e).parent().attr(\"orderid\");\r\n\t\t order_id = order_id.split(',');\r\n\t\t if (bool) {\r\n\t\t\t if (type == 'del') {\r\n\t\t\t\t$.each(order_id, function(i,val){   \r\n\t\t\t\t\t$(\"#order_\"+val).parent().parent().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t});  \r\n\t\t\t } \r\n\t\t\t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: url,\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'orderid=' + order_id ,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\r\n\t\t\t\t\t $.each(order_id, function(i,val){     \r\n\t\t\t\t\t\t \t$(\"#order_\"+val).parent().parent().find('.status').html(html);\r\n\r\n\t\t\t\t\t });   \r\n\t\t\t\t\t \r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n}\r\n\r\n$(\".unlock,.lock,.del\").click(function(){\r\n\tuld(this.className,this);\r\n});\r\n\r\n$(\"#choose_sb\").click(function(){\r\n\tvar arr=[];\r\n\tvar choose_type = $(\"#choose_type\").val();\r\n\tif(!choose_type){\r\n\t\tbox.alert('批量操作请选择一个操作对像',300);\r\n\t\treturn ;\r\n\t}\r\n \tvar arrChk=$(\"input[name='orderid']:checked\"); \r\n     \r\n    for (var i=0;i<arrChk.length;i++)\r\n    {\r\n        var v = arrChk[i].value;\r\n\t\tarr.push(v);\r\n\t\t\r\n    }\r\n\t\r\n\torder_id = arr.join(\",\"); \r\n\tuld(choose_type);\r\n});\r\n\r\n$(\"#select_id\").click(function(){\r\n \t var a = $(\"#select_id\").attr(\"checked\");\r\n\t if(a!='checked') a = false;\r\n     $(\"input[name='orderid']\").attr(\"checked\",a);\r\n});\r\n\r\n \r\n </script>\r\n";

?>
