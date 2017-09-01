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
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid mt60\" >\r\n    <h3 class=\"heading tab_heading\">充值管理</h3>\r\n    <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/onlinepay.get_list");
echo "\" class=\"tab-btn  list ";

if ($status == "") {
	echo "tab-state-active";
}

echo "\">全部信息</a> <a href=\"";
echo url("admin/onlinepay.get_list?status=0");
echo "\" class=\"tab-btn notpay ";

if ($status == "0") {
	echo "tab-state-active";
}

echo "\">充值失败</a> <a href=\"";
echo url("admin/onlinepay.get_list?status=1");
echo "\" class=\"tab-btn unpay ";

if ($status == "1") {
	echo "tab-state-active";
}

echo "\"> 已完成充值</a> <a href=\"";
echo url("admin/onlinepay.get_list?status=3");
echo "\" class=\"tab-btn add ";

if ($status == "3") {
	echo "tab-state-active";
}

echo "\"> 手动填加的</a> <a href=\"";
echo url("admin/onlinepay.get_list?status=4");
echo "\" class=\"tab-btn subtract ";

if ($status == "4") {
	echo "tab-state-active";
}

echo "\"> 手动扣除的</a> </div>\r\n    </div>\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"tb_sts\"><a href=\"javascript:;\"  class=\"tab-btn add add_pay\">手动充值</a> </div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> \r\n            <!-- \r\n\t\t\t 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"pay\" >结算</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>-->\r\n            <select size=\"1\" name=\"select\" id=\"select\" style=\"margin-left:20px\"  onchange=\"location.href = this.options[selectedIndex].value\">\r\n              <option value=\"";
echo url("admin/onlinepay.get_list?status=" . $status);
echo "\" >所有网关</option>\r\n              ";
$GLOBALS["c_onlinepay"]["手动充值"] = array("sd", 1);
$GLOBALS["c_onlinepay"]["发布补偿"] = array("bc", 1);

foreach ($GLOBALS["c_onlinepay"] as $b => $v ) {
	echo "              <option value=\"";
	echo url("admin/onlinepay.get_list?paytype=" . $v[0]);
	echo "\"  ";

	if ($paytype == $v[0]) {
		echo "selected";
	}

	echo ">";
	echo $b;
	echo "</option>\r\n              ";
}

echo "            </select>\r\n          </div>\r\n        </div>\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\">\r\n            <form method=\"post\">\r\n              搜索：\r\n              <select name=\"searchtype\">\r\n                <option value=\"orderid\" ";

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

echo ">站长ID</option>\r\n              </select>\r\n              <input type=\"text\" class=\"input_text span30\" name=\"search\" value=\"";
echo $search;
echo "\">\r\n              <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n            </form>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <table id=\"dt_inbox\" class=\"dataTable table-bordered \">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th>会员名称</th>\r\n            <!-- <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\"></th>-->\r\n            <th>日期</th>\r\n            <th>订单</th>\r\n            <th>金额</th>\r\n            <th>网关</th>\r\n            <th>说明</th>\r\n            <th>充值人</th>\r\n            <th>状态</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $onlinepay as $p ) {
	echo "          <tr class=\"unread odd\"  >\r\n            <td >";
	echo $p["username"];
	echo "</td>\r\n            <!-- <td><input type=\"checkbox\" name=\"id\" id=\"pay_";
	echo $p["id"];
	echo "\" value=\"";
	echo $p["id"];
	echo "\"></td>-->\r\n            <td >";
	echo $p["addtime"];
	echo "</td>\r\n            <td>";
	echo $p["orderid"];
	echo "</td>\r\n            <td>";
	echo $p["imoney"];
	echo "</td>\r\n            <td>";

	foreach ($GLOBALS["c_onlinepay"] as $b => $v ) {
		if ($p["paytype"] == $v[0]) {
			echo $b;
		}
	}

	echo "</td>\r\n            <td>";
	echo $p["payinfo"];
	echo "</td>\r\n            <td>";
	echo $p["adminuser"];
	echo "</td>\r\n            <td classid='";
	echo $p["id"];
	echo "' class=\"status\">";

	if ($p["status"] == "0") {
		echo "<font color=red>充值没有完成</font>";
	}
	else if ($p["status"] == "1") {
		echo "<font color=blue>充值失败</font>";
	}
	else if ($p["status"] == "2") {
		echo " <font color=#ff6600>充值完成</font>";
	}
	else if ($p["status"] == "3") {
		echo " <font color=blue>增加</font>";
	}
	else if ($p["status"] == "4") {
		echo " <font color=red>扣除</font>";
	}

	echo "</td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n<div id=\"add_pay_form\" style=\"display:none\">\r\n  <form action=\"";
echo url("admin/onlinepay.post_add_pay");
echo "\" name=\"fclearing\" id=\"fclearing\" method=\"post\">\r\n    <div class=\"txt-fld\"   id=\"to_username\">\r\n      <label for=\"\"  class=\"tit\">用户名</label>\r\n      <input id=\"pay_add_username_ajax\" name=\"username\" type=\"text\" value=\"";
echo $touser ? $touser : "";
echo "\">\r\n    </div>\r\n    <div class=\"txt-fld\">\r\n      <label for=\"\" class=\"tit\">选项</label>\r\n      <input type=\"radio\" name=\"status\" value=\"3\"  checked=\"checked\">\r\n      增加\r\n      <input type=\"radio\" name=\"status\" value=\"4\"  >\r\n      扣除 </div>\r\n    ";

if (get("type") != 2) {
	echo "    <div class=\"txt-fld\">\r\n      <label for=\"\"  class=\"tit\">结算款项</label>\r\n      <input name=\"clearing\" type=\"radio\" value=\"day\"  >\r\n      日款&nbsp;&nbsp;\r\n      <input name=\"clearing\" type=\"radio\" value=\"week\" checked=\"checked\" />\r\n      周款&nbsp;&nbsp;\r\n      <input name=\"clearing\" type=\"radio\" value=\"month\"  />\r\n      月款&nbsp;&nbsp;\r\n      <input name=\"clearing\" type=\"radio\" value=\"x\"  />\r\n      下线款&nbsp;&nbsp;\r\n      <input name=\"clearing\" type=\"radio\" value=\"integral\"  />\r\n      积分 </div>\r\n    ";
}

echo "    <div class=\"txt-fld\"   id=\"to_username\">\r\n      <label for=\"\"  class=\"tit\">金额</label>\r\n      <input id=\"imoney\" name=\"imoney\" type=\"text\">\r\n    </div>\r\n    <div class=\"txt-fld\"   id=\"to_username\">\r\n      <label for=\"\"  class=\"tit\">说明</label>\r\n      <input id=\"payinfo\" name=\"payinfo\" type=\"text\">\r\n    </div>\r\n    <div class=\"btn-fld\">\r\n      <button type=\"button\"  id=\"post_pay_but\">提交 »</button>\r\n    </div>\r\n  </form>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n\r\n";

if ($touser) {
	echo " sd_pay();\r\n";
}

echo "$(\".add_pay\").click(function(){\r\n\t\r\nsd_pay();\r\n});\r\n\r\nfunction sd_pay(){\r\n\t\tbox.form(\"#add_pay_form\",\"手动充值\");\r\n\t\r\n \r\n\t\r\n\t$(\"#post_pay_but\").click(function(){\r\n\t\tvar touser  = $('#pay_add_username_ajax').val(); \r\n\t\tvar imoney = $('#imoney').val();  \r\n\t\t \r\n\t\t if(!touser){\r\n\t\t\talert(\"请输入会员名称！\");\t\t\r\n\t\t\treturn false;\r\n\t\t }  \r\n\t\tif(!imoney){\t\t\r\n\t\t\talert(\"请输入金额\");\t\t\r\n\t\t\treturn false;\r\n\t\t }\r\n\t\t \r\n\t\t $.post(\"";
echo url("admin/user.remote_user");
echo "\",{username:touser}, function(data){\t \r\n\t\t\t\tif( data == \"false\" ){\r\n\t\t\t\t\t document.forms[\"fclearing\"].submit();\r\n\t\t\t\t }else{\r\n\t\t\t\t\talert('没有这个会员');\r\n\t\t\t\t }\r\n\t\t });\r\n\t\r\n\t});\r\n}\r\n\r\n\r\nfunction uld(type,pay_id,m,u) {\r\n\tvar html = '';\r\n   \tvar width = 400;\r\n\tif (type == 'del') {\r\n\t\turl = '";
echo url("admin/pay.del");
echo "';\r\n\t\ttitle = '删除分类';\r\n\t\ttext = '确定要删除吗？删除后无法恢复!';\r\n\t}\r\n\tif (type == 'one') {\r\n\t url = '";
echo url("admin/pay.post_payment");
echo "';\r\n\t var text = u+\" 的佣金<font color='red'>\"+m+\"</font>已付，确认结算\";\r\n\t title = '已清款结算';\r\n\t} \r\n\tif (type == 'pay') {\r\n\t url = '";
echo url("admin/pay.post_payment");
echo "';\r\n\t var text = \"确认批量结算\";\r\n\t title = '已清款结算';\r\n\t} \r\n\t\r\n\tbox.confirm(text,width,title,function(bool){ \r\n\t\t pay_id = pay_id.split(',');\r\n\t\t if (bool) {\r\n\t\t\t if (type == 'del') {\r\n\t\t\t\t$.each(pay_id, function(i,val){   \r\n\t\t\t\t\t$(\"#pay_\"+val).parent().parent().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t});  \r\n\t\t\t } \r\n\t\t\t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: url,\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'id=' + pay_id ,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\r\n\t\t\t\t\t $.each(pay_id, function(i,val){     \r\n\t\t\t\t\t\t \t$(\"#pay_\"+val).parent().parent().find('.status').html(\"已支付\");\r\n\t\t\t\t\t\t\t$(\"#pay_\"+val).parent().parent().css(\"backgroundColor\", \"#9adf8f\").css(\"color\", \"#fff\");\r\n\t\t\t\t\t\t\t$(\"#pay_\"+val).parent().parent().next().hide();\r\n\r\n\t\t\t\t\t });   \r\n\t\t\t\t\t$.getJSON(\"";
echo url("admin/pay.send_email?id=");
echo "\"+pay_id);\r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n}\r\n\r\n$(\".del\").click(function(){\r\n\tuld(this.className,this);\r\n});\r\n\r\n$(\"#choose_sb\").click(function(){\r\n\tvar arr=[];\r\n\tvar choose_type = $(\"#choose_type\").val();\r\n\tif(!choose_type){\r\n\t\tbox.alert('批量操作请选择一个操作对像',300);\r\n\t\treturn ;\r\n\t}\r\n \tvar arrChk=$(\"input[name='id']:checked\"); \r\n     \r\n    for (var i=0;i<arrChk.length;i++)\r\n    {\r\n        var v = arrChk[i].value;\r\n\t\tarr.push(v);\r\n\t\t\r\n    }\r\n\tpay_id = arr.join(\",\"); \r\n\tuld(choose_type,pay_id);\r\n});\r\n\r\n$(\"#select_id\").click(function(){\r\n \t var a = $(\"#select_id\").attr(\"checked\");\r\n\t if(a!='checked') a = false;\r\n     $(\"input[name='id']\").attr(\"checked\",a);\r\n});\r\n\r\n \r\n </script> \r\n";

?>
