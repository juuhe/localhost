<?php

if (!defined("IN_ZYADS")) {
	exit();
}

TPL::display("header");
echo "\r\n<div id=\"left\">\r\n  <div class=\"subnav\">\r\n    <div class=\"subnav-title\"> <a href=\"#\" class='toggle-subnav'><i class=\"icon-angle-down\"></i><span>广告活动分类</span></a> </div>\r\n    <ul class=\"subnav-menu\">\r\n      <li ";

if (get("type") == "") {
	echo "class='current'";
}

echo "> <a href=\"";
echo url("affiliate/plan.get_list");
echo "\">所有活动</a> </li>\r\n      ";

foreach ((array) $plantype as $p ) {
	echo "      <li ";

	if (get("type") == $p["plantype"]) {
		echo "class='current'";
	}

	echo "> <a href=\"";
	echo url("affiliate/plan.get_list?type=" . $p["plantype"]);
	echo "\">";
	echo strtoupper($p["plantype"]);
	echo " 类活动</a> </li>\r\n      ";
}

echo "    </ul>\r\n  </div>\r\n  <div class=\"subnav\">\r\n    <div class=\"subnav-title\"> <a href=\"#\" class='toggle-subnav'><i class=\"icon-angle-down\"></i><span>帮助</span></a> </div>\r\n    <ul class=\"subnav-menu\">\r\n      <li> <a href=\"";
echo url("index.article?id=84");
echo "\" target=\"_blank\">如何获取广告代码？</a> </li>\r\n      <li> <a href=\"";
echo url("index.article?id=85");
echo "\" target=\"_blank\">如何过渡广告？</a> </li>\r\n      <li> <a href=\"";
echo url("index.article?id=86");
echo "\" title=\"一个广告位能显示多种广告样式吗？\">广告位显示多种广告样式？</a> </li>\r\n      <li> <a href=\"";
echo url("index.article?id=87");
echo "\">广告有哪些类型？</a> </li>\r\n      <li> <a href=\"";
echo url("index.article?id=88");
echo "\">修改了广告位没有生效？</a> </li>\r\n    </ul>\r\n  </div>\r\n</div>\r\n<div id=\"main\" style=\"padding-top:10px\">\r\n  <div class=\"box\" >\r\n    <div class=\"box-title\">\r\n      <h3><i class=\"icon-table\"></i>活动列表</h3>\r\n      <div class=\"actions\" style=\"color: #08c;\"> <span style=\" cursor:pointer\">\r\n        <select name=\"classid\" onchange=\"location.href = this.options[selectedIndex].value\">\r\n          <option value=\"";
echo url("affiliate/plan.get_list?type=" . get("type"));
echo "\">按活动分类</option>\r\n          ";

foreach ($plan_class as $pc ) {
	echo "          <option value=\"";
	echo url("affiliate/plan.get_list?type=" . get("type") . "&classid=" . $pc["classid"]);
	echo "\" ";

	if (get("classid") == $pc["classid"]) {
		echo "selected";
	}

	echo ">";
	echo $pc["classname"];
	echo "</option>\r\n          ";
}

echo "        </select>\r\n        </span> </div>\r\n    </div>\r\n    <div class=\"z_panel\" style=\"display:none\">\r\n      <table width=\"200\" border=\"0\" align=\"right\" cellpadding=\"0\" cellspacing=\"0\">\r\n        <tr>\r\n          <td width=\"50\">显示：</td>\r\n          <td><input type=\"checkbox\" name=\"checkbox\" value=\"checkbox\" />\r\n            闲置</td>\r\n        </tr>\r\n        <tr>\r\n          <td>&nbsp;</td>\r\n          <td><input type=\"checkbox\" name=\"checkbox2\" value=\"checkbox\" />\r\n            活动 </td>\r\n        </tr>\r\n        <tr>\r\n          <td>尺寸：</td>\r\n          <td><select id=\"zadsize\">\r\n              <option value=\"\">全部尺寸</option>\r\n              ";

foreach ((array) $adsize as $a ) {
	echo "              <option value=\"";
	echo $a;
	echo "\">";
	echo $a;
	echo "</option>\r\n              ";
}

echo "            </select></td>\r\n        </tr>\r\n        <tr>\r\n          <td>类型：</td>\r\n          <td><select id=\"zadtplid\">\r\n              <option value=\"\">全部类型</option>\r\n              ";

foreach ((array) $zadtplid as $a ) {
	echo "              <option value=\"";
	echo $a;
	echo "\">";
	echo $GLOBALS["ADTYPE_SPECS"][$a]["name"];
	echo "</option>\r\n              ";
}

echo "            </select></td>\r\n        </tr>\r\n        <tr>\r\n          <td class=\"brb1d\">&nbsp;</td>\r\n          <td class=\"brb1d\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n          <td height=\"30\">ID：</td>\r\n          <td><input type=\"text\" name=\"zid\" id=\"zid\" style=\"width:110px\" /></td>\r\n        </tr>\r\n        <tr>\r\n          <td height=\"30\">名称：</td>\r\n          <td><input type=\"text\" name=\"zname\"  id=\"zname\" style=\"width:110px\" /></td>\r\n        </tr>\r\n        <tr>\r\n          <td height=30</td>\r\n          <td></td>\r\n        </tr>\r\n      </table>\r\n    </div>\r\n    <div class=\"box-content\">\r\n      <table class=\"table plan_logo\">\r\n        <thead>\r\n          <tr>\r\n            <th width=\"300\">活动名称</th>\r\n            <th width=\"100\">类型</th>\r\n            <th width=\"100\">佣金</th>\r\n            <th width=\"80\">活动分类</th>\r\n            <th width=\"80\">状态</th>\r\n            <th width=\"120\">操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody>\r\n          ";

foreach ((array) $plantype_list as $p ) {
	$c = dr("main/class.get_one", (int) $p["classid"]);
	$adnum = dr("affiliate/ad.get_planid_adnum", (int) $p["planid"]);
	$notap = 0;
	echo "          <tr class=\"d_a\" >\r\n            <td><table width=\"90%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"logo\">\r\n                <tr>\r\n                  <td width=\"120\" rowspan=\"2\">\r\n\t\t\t\r\n                  <a href=\"";
	echo url("affiliate/plan.info?planid=" . $p["planid"]);
	echo "\"><img src=\"";
	echo $p["logo"] ? $p["logo"] : SRC_TPL_DIR . "/images/no.gif";
	echo "\"  border=\"0\" /></a></td>\r\n                  <td><a href=\"";
	echo url("affiliate/plan.info?planid=" . $p["planid"]);
	echo "\">";
	echo $p["planname"];
	echo "\t  ";

	if ($p["top"]) {
		echo "<font color=\"#FF0000\">[荐]</font>";
	}

	echo "</a></td>\r\n                </tr>\r\n                <tr>\r\n                  <td>结算：\r\n                    ";

	if ($p["clearing"] == "day") {
		echo "日结";
	}

	if ($p["clearing"] == "week") {
		echo "周结";
	}

	if ($p["clearing"] == "month") {
		echo "月结";
	}

	echo "</td>\r\n                </tr>\r\n              </table></td>\r\n            <td>";
	echo strtoupper($p["plantype"]);
	echo "</td>\r\n            <td>";
	$af = ($p["plantype"] == "cps" ? "%" : "元");
	$price = main_public::format_plan_print($p["planid"]);

	if (is_array($price)) {
		echo $price["min"] . $af . "-" . $price["max"] . $af;
	}
	else {
		echo $price . $af;
	}

	echo "</td>\r\n            <td>";
	echo $c["classname"];
	echo "</td>\r\n            <td class=\"status\">";

	if ($p["status"] == 3) {
		echo "<span class=\"notification error_bg\">饱和</span>";
	}
	else if ($p["status"] == 5) {
		echo "<span class=\"notification error_bg\">暂停申请</span>";
	}
	else if ($p["audit"] == "y") {
		$ap = dr("affiliate/apply.get_apply_status", (int) $_SESSION["affiliate"]["uid"], $p["planid"]);

		if ($ap["status"] == "0") {
			echo "<span class=\"notification error_bg\">审核中</span>";
		}
		else if ($ap["status"] == "1") {
			echo "<span class=\"notification error_bg\">已被拒绝</span>";
		}
		else if ($ap["status"] == "2") {
			echo "<span class=\"notification info_bg\">活动</span>";
		}
		else {
			echo "<span class=\"notification error_bg\">未申请</span>";
			$notap = 1;
		}
	}
	else {
		echo "<span class=\"notification info_bg\">活动</span>";
	}

	echo "</td>\r\n            <td>";
	if (($p["audit"] == "y") && $notap) {
		echo "              <a href=\"javascript:;\" class=\"apply\" planid=\"";
		echo $p["planid"];
		echo "\">申请广告</a>\r\n              ";
	}

	echo "              ";
	if ($adnum || ($p["linkon"] == "y")) {
		echo "              <a href=\"";
		echo url("affiliate/plan.get_ad?planid=" . $p["planid"]);
		echo "\">查看广告</a>\r\n              ";
	}
	else {
		echo "              <span class=\"notification error_bg\">缺少广告</span>\r\n              ";
	}

	echo "              <a href=\"";
	echo url("affiliate/plan.info?planid=" . $p["planid"]);
	echo "\">详细</a></td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n    </div>\r\n  </div>\r\n  <div id=\"apply_html\" style=\"display:none\">\r\n    ";

if ($site_num === 0) {
	echo "    <div class=\"alert alert-info\" style=\"margin-top:10px\"> 警告！缺少活动网站。 <a href=\"";
	echo url("affiliate/site.create");
	echo "\">增加一个网站</a></div>\r\n    ";
}

echo "    <table   border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width:450px\">\r\n      <tr>\r\n        <td>&nbsp;</td>\r\n        <td height=\"40\"></td>\r\n        <td><button type=\"button\" class=\"btn btn-primary post_apply\"> 确认提交申请 </button></td>\r\n      </tr>\r\n    </table>\r\n  </div>\r\n  <div>\r\n    ";
echo $page->echoPage();
echo "  </div>\r\n</div>\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/clipboard/clipboard.js\"></script> \r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/js/jquery-1.7.min.js\"></script> \r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/lib/leanmodal/leanmodal.min.js\"></script>\r\n<link rel=\"stylesheet\" href=\"";
echo SRC_TPL_DIR;
echo "/style/modal.css\">\r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n  \r\n$('.apply').on('click', function(option) { \r\n applyplanid = $(this).attr(\"planid\"); \r\n status_o =  $(this).parent().parent().find('.status');\r\n box.confirm('确认申请',300,'审请广告',function(bool){ \r\n\t if(bool){\r\n\t\t $.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: '";
echo url("affiliate/apply.post_apply");
echo "',\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'planid='+applyplanid+'&siteid=&applysiteidtype=1' ,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\r\n\t\t\t\t\tbox._hide();\r\n\t\t\t\t\tstatus_o.html('<span class=\"notification error_bg\">申请中</span>');\r\n\t\t\t\t\t \r\n\t\t\t\t\tbox.alert('申请成功，请等待我们审核',300);\r\n\r\n\t\t\t\t}\r\n\t\t\t}); \r\n\t\t}\t\r\n });\r\n\r\n \r\n  \r\n});\r\n\r\n\r\n\r\n\r\n \t\r\n\t\r\n</script> \r\n";

?>
