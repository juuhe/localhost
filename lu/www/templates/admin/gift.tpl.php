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
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid\" >\r\n    <h3 class=\"heading\">积分兑换</h3>\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"tb_sts\"><a href=\"";
echo url("admin/gift.add");
echo "\"  class=\"tab-btn add \">新增奖品</a> <a href=\"";
echo url("admin/gift.get_list");
echo "\" class=\"tab-btn all ";

if (!$status) {
	echo "tb_sts-active";
}

echo "\">全部列表</a> <a href=\"";
echo url("admin/gift.get_list?status=y");
echo "\" class=\"tab-btn unlock ";

if ($status === "y") {
	echo "tb_sts-active";
}

echo "\"> 兑换中</a> <a href=\"";
echo url("admin/gift.get_list?status=n");
echo "\" class=\"tab-btn lock ";

if ($status === "n") {
	echo "tb_sts-active";
}

echo "\">已锁定</a></div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"unlock\">激活</option>\r\n              <option value=\"lock\" >锁定</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n          </div>\r\n        </div>\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\">\r\n            <form method=\"post\">\r\n              搜索：\r\n              <select name=\"searchtype\">\r\n                <option value=\"name\" ";

if ($searchtype == "name") {
	echo "selected";
}

echo ">奖品名称</option>\r\n              </select>\r\n              <input type=\"text\" class=\"input_text span30\" name=\"search\" value=\"";
echo $search;
echo "\">\r\n              <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n            </form>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <table id=\"dt_inbox\" class=\"dataTable\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\"></th>\r\n            <th>奖品名称</th>\r\n            <th>奖品分类</th>\r\n            <th>兑换积分</th>\r\n            <th>兑换次数</th>\r\n            <th>推荐</th>\r\n            <th>状态</th>\r\n            <th>操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $gift as $g ) {
	echo "          <tr class=\"unread odd\">\r\n            <td><input type=\"checkbox\" name=\"id\" id=\"gift_";
	echo $g["id"];
	echo "\" value=\"";
	echo $g["id"];
	echo "\"></td>\r\n            <td>";
	echo $g["name"];
	echo "</td>\r\n            <td>";

	foreach ($GLOBALS["gift_type"] as $key => $val ) {
		if ($g["type"] == $key) {
			echo $val;
		}
	}

	echo "</td>\r\n            <td>";
	echo $g["integral"];
	echo "</td>\r\n            <td>";
	echo $g["num"];
	echo "</td>\r\n            <td>";

	if ($g["top"] == 1) {
		echo "推荐";
	}
	else {
		echo "不推荐";
	}

	echo "</td>\r\n            <td class=\"status\">";

	switch ($g["status"]) {
	case "y":
		echo "<span class=\"notification info_bg\">兑换中</span>";
		break;

	case "n":
		echo "<span class=\"notification error_bg\">锁定</span>";
		break;
	}

	echo "            </td>\r\n            <td giftid='";
	echo $g["id"];
	echo "' class=\"uld_img\"><a href=\"";
	echo url("admin/gift.edit?id=" . $g["id"]);
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

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n\r\nfunction uld(type,e) {\r\n\tvar html = '';\r\n   \tvar width = 400;\r\n\tif (type == 'del') {\r\n\t\turl = '";
echo url("admin/gift.del");
echo "';\r\n\t\ttitle = '删除奖品';\r\n\t\ttext = '确定要删除吗？删除后无法恢复!';\r\n\t}\r\n\tif (type == 'lock') {\r\n\t\turl = '";
echo url("admin/gift.lock");
echo "';\r\n\t\thtml = '<span class=\"notification error_bg\">锁定</span>';\r\n\t    text = '确认锁定吗';\r\n\t\ttitle = '锁定奖品';\r\n\t}\r\n\tif (type == 'unlock') {\r\n\t\turl = '";
echo url("admin/gift.unlock");
echo "';\r\n\t\thtml = '<span class=\"notification info_bg\">活动</span>';\r\n\t    text = '确认激活吗';\r\n\t\ttitle = '激活信息';\r\n\t}\r\n \t\t \r\n\tbox.confirm(text,width,title,function(bool){ \r\n\t\t if(e) gift_id = $(e).parent().attr(\"giftid\");\r\n\t\t gift_id = gift_id.split(',');\r\n\t\t if (bool) {\r\n\t\t\t if (type == 'del') {\r\n\t\t\t\t$.each(gift_id, function(i,val){   \r\n\t\t\t\t\t$(\"#gift_\"+val).parent().parent().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t});  \r\n\t\t\t } \r\n\t\t\t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: url,\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'id=' + gift_id ,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\r\n\t\t\t\t\t $.each(gift_id, function(i,val){    \r\n\t\t\t\t\t\t \t$(\"#gift_\"+val).parent().parent().find('.status').html(html);\r\n\t\t\t\t\t });   \r\n\t\t\t\t\t$(\".success\").show();\r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n}\r\n\r\n$(\".unlock,.lock,.del\").click(function(){\r\n\tuld(this.className,this);\r\n});\r\n\r\n$(\"#choose_sb\").click(function(){\r\n\tvar arr=[];\r\n\tvar choose_type = $(\"#choose_type\").val();\r\n\tif(!choose_type){\r\n\t\tbox.alert('批量操作请选择一个操作对像',300);\r\n\t\treturn ;\r\n\t}\r\n \tvar arrChk=$(\"input[name='id']:checked\"); \r\n     \r\n    for (var i=0;i<arrChk.length;i++)\r\n    {\r\n        var v = arrChk[i].value;\r\n\t\tarr.push(v);\r\n\t\t\r\n    }\r\n\tgift_id = arr.join(\",\");\r\n\tuld(choose_type);\r\n});\r\n\r\n$(\"#select_id\").click(function(){\r\n \t var a = $(\"#select_id\").attr(\"checked\");\r\n\t if(a!='checked') a = false;\r\n     $(\"input[name='id']\").attr(\"checked\",a);\r\n});\r\n </script>\r\n";

?>
