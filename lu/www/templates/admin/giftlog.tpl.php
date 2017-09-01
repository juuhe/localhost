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
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid\" >\r\n    <h3 class=\"heading\">积分兑换记录</h3>\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"tb_sts\"><a href=\"";
echo url("admin/gift.add");
echo "\"  class=\"tab-btn add \">新增奖品</a> <a href=\"";
echo url("admin/giftlog.get_list");
echo "\" class=\"tab-btn all ";

if (!$status) {
	echo "tb_sts-active";
}

echo "\">全部列表</a> <a href=\"";
echo url("admin/giftlog.get_list?status=y");
echo "\" class=\"tab-btn tab_gift ";

if ($status === "y") {
	echo "tb_sts-active";
}

echo "\"> 已发货</a> <a href=\"";
echo url("admin/giftlog.get_list?status=n");
echo "\" class=\"tab-btn tab_gift_gray ";

if ($status === "n") {
	echo "tb_sts-active";
}

echo "\" >未发货</a></div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"delivery\">发货</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n          </div>\r\n        </div>\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\">\r\n            <form method=\"post\">\r\n              搜索：\r\n              <select name=\"searchtype\">\r\n                <option value=\"name\" ";

if ($searchtype == "name") {
	echo "selected";
}

echo ">用户名称</option>\r\n                <option value=\"name\" ";

if ($searchtype == "name") {
	echo "selected";
}

echo ">收件人</option>\r\n              </select>\r\n              <input type=\"text\" class=\"input_text span30\" name=\"search\" value=\"";
echo $search;
echo "\">\r\n              <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n            </form>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <table id=\"dt_inbox\" class=\"dataTable\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\"></th>\r\n            <th>用户名称</th>\r\n            <th>兑换奖品</th>\r\n            <th>兑奖积分</th>\r\n            <th>收件人</th>\r\n            <th>电话</th>\r\n            <th>地址</th>\r\n            <th>兑换时间</th>\r\n            <th>操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $giftlog as $g ) {
	$gift = dr("admin/gift.get_one", $g["giftid"]);
	echo "          <tr class=\"unread odd\">\r\n            <td><input type=\"checkbox\" name=\"id\" id=\"giftlog_";
	echo $g["id"];
	echo "\" value=\"";
	echo $g["id"];
	echo "\"></td>\r\n            <td>";
	echo $g["username"];
	echo "</td>\r\n            <td>";
	echo $gift["name"];
	echo "</td>\r\n            <td>";
	echo $g["integral"];
	echo "</td>\r\n            <td>";
	echo $g["contact"];
	echo "</td>\r\n            <td>";
	echo $g["tel"];
	echo "</td>\r\n            <td>";
	echo $g["address"];
	echo "</td>\r\n            <td>";
	echo $g["addtime"];
	echo "</td>\r\n            <td giftid='";
	echo $g["id"];
	echo "' class=\"status uld_img\">";

	switch ($g["status"]) {
	case "n":
		echo "<img src=\"" . SRC_TPL_DIR . "/images/gift_gray.png\" alt=\"\" border=\"0\" class=\"delivery\" title=\"发货\" />";
		break;

	case "y":
		echo "<span class=\"notification info_bg\">已发货</span>";
		break;
	}

	echo "            </td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n\r\nfunction uld(type,e) {\r\n\tvar html = '';\r\n   \tvar width = 400;\r\n\tif (type == 'del') {\r\n\t\turl = '";
echo url("admin/giftlog.del");
echo "';\r\n\t\ttitle = '删除文章';\r\n\t\ttext = '确定要删除吗？删除后无法恢复!';\r\n\t}\r\n \r\n\tif (type == 'delivery') {\r\n\t\turl = '";
echo url("admin/giftlog.delivery");
echo "';\r\n\t\thtml = '<span class=\"notification info_bg\">已发货</span>';\r\n\t    text = '确认发货吗';\r\n\t\ttitle = '确认发货';\r\n\t}\r\n \t\t \r\n\tbox.confirm(text,width,title,function(bool){ \r\n\t\t if(e) giftlog_id = $(e).parent().attr(\"giftid\");\r\n\t\t giftlog_id = giftlog_id.split(',');\r\n\t\t if (bool) {\r\n\t\t\t if (type == 'del') {\r\n\t\t\t\t$.each(giftlog_id, function(i,val){   \r\n\t\t\t\t\t$(\"#giftlog_\"+val).parent().parent().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t});  \r\n\t\t\t } \r\n\t\t\t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: url,\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'id=' + giftlog_id ,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\r\n\t\t\t\t\t $.each(giftlog_id, function(i,val){    \r\n\t\t\t\t\t\t \t$(\"#giftlog_\"+val).parent().parent().find('.status').html(html);\r\n\t\t\t\t\t });   \r\n\t\t\t\t\t$(\".success\").show();\r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n}\r\n\r\n$(\".delivery\").click(function(){\r\n\tuld(this.className,this);\r\n});\r\n\r\n$(\"#choose_sb\").click(function(){\r\n\tvar arr=[];\r\n\tvar choose_type = $(\"#choose_type\").val();\r\n\tif(!choose_type){\r\n\t\tbox.alert('批量操作请选择一个操作对像',300);\r\n\t\treturn ;\r\n\t}\r\n \tvar arrChk=$(\"input[name='id']:checked\"); \r\n     \r\n    for (var i=0;i<arrChk.length;i++)\r\n    {\r\n        var v = arrChk[i].value;\r\n\t\tarr.push(v);\r\n\t\t\r\n    }\r\n\tgiftlog_id = arr.join(\",\");\r\n\tuld(choose_type);\r\n});\r\n\r\n$(\"#select_id\").click(function(){\r\n \t var a = $(\"#select_id\").attr(\"checked\");\r\n\t if(a!='checked') a = false;\r\n     $(\"input[name='id']\").attr(\"checked\",a);\r\n});\r\n </script>\r\n";

?>
