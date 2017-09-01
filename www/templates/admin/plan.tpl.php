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
echo "</div>\r\n\r\n\r\n<div id=\"main-content\">\r\n \r\n  <div class=\"row-fluid mt60\" >\r\n    <h3 class=\"heading tab_heading\">计划管理</h3>\r\n    <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/plan.get_list");
echo "\" class=\"tab-btn  list ";

if ($plantype === "") {
	echo "tab-state-active";
}

echo "\">全部列表</a> ";

foreach (explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
	echo " <a href=\"";
	echo url("admin/plan.get_list?plantype=" . $t);
	echo "\" class=\"tab-btn ad";
	echo $t;
	echo " ";

	if ($plantype == $t) {
		echo "tab-state-active";
	}

	echo "\"> ";
	echo strtoupper($t);
	echo "计划</a> ";
}

echo " </div>\r\n    </div>\r\n    \r\n\t<div  class=\"dataTables_wrapper \">\r\n\t";
if ($new_num || $edit_num) {
	echo "    <div class=\"span12\">\r\n        <div class=\"alert-info tip-text\" >\r\n          <p>  ";

	if ($new_num) {
		echo "发现新建计划" . $new_num . "个需要审核";
	}

	echo " ";

	if ($edit_num) {
		echo "\t发现已修改计划" . $edit_num . "个需要审核";
	}

	echo "</p>\r\n        </div>\r\n      </div>\r\n     ";
}

echo " \r\n\t  <div class=\"tb_sts\"><a href=\"";
echo url("admin/plan.add");
echo "\"  class=\"tab-btn add \">新增计划</a><a href=\"";
echo url("admin/plan." . RUN_ACTION . "?status=0&plantype=" . $plantype);
echo "\" class=\"tab-btn red ";

if ($status == "0") {
	echo "tb_sts-active";
}

echo "\">待审核</a><a href=\"";
echo url("admin/plan." . RUN_ACTION . "?status=1&plantype=" . $plantype);
echo "\" class=\"tab-btn unlock ";

if ($status == "1") {
	echo "tb_sts-active";
}

echo "\"> 已审核</a> <a href=\"";
echo url("admin/plan." . RUN_ACTION . "?status=2&plantype=" . $plantype);
echo "\" class=\"tab-btn lock ";

if ($status == "2") {
	echo "tb_sts-active";
}

echo "\">已锁定</a> <a href=\"";
echo url("admin/plan." . RUN_ACTION . "?status=3&plantype=" . $plantype);
echo "\" class=\"tab-btn expired ";

if ($status == "3") {
	echo "tb_sts-active";
}

echo "\">限额过期</a> <a href=\"";
echo url("admin/plan." . RUN_ACTION . "?status=6&plantype=" . $plantype);
echo "\" class=\"tab-btn expired ";

if ($status == "6") {
	echo "tb_sts-active";
}

echo "\">修改待审</a></div>\r\n\t  \r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"unlock\">激活</option>\r\n              <option value=\"lock\" >锁定</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n          </div>\r\n        </div>\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\">\r\n            <form method=\"post\">\r\n              搜索：\r\n              <select name=\"searchtype\">\r\n                <option value=\"planname\" ";

if ($searchtype == "planname") {
	echo "selected";
}

echo ">计划名称</option>\r\n                <option value=\"planid\" ";

if ($searchtype == "planid") {
	echo "selected";
}

echo ">计划ID</option>\r\n                <option value=\"uid\" ";

if ($searchtype == "uid") {
	echo "selected";
}

echo ">广告商ID</option>\r\n              </select>\r\n              <input type=\"text\" class=\"input_text span30\" name=\"search\" value=\"";
echo $search;
echo "\">\r\n              <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n            </form>\r\n          </div>\r\n        </div>\r\n      </div>\r\n\t  \r\n      <table id=\"dt_inbox\" class=\"dataTable\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\" /></th>\r\n            <th><em>ID</em></th>\r\n            <th>计划名称</th>\r\n            <th>类型</th>\r\n            <th>广告商</th>\r\n            <th title=\"上行为站长单价 下行为广告商单价\">会员单价</th>\r\n            <th>厂商出价</th>\r\n            <th>限额</th>\r\n            <th>结算</th>\r\n            <th>扣量</th>\r\n            <th>状态</th>\r\n            <th>操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $plan as $p ) {
	$u = dr("admin/user.get_one", $p["uid"]);
	echo "          <tr class=\"unread odd\">\r\n            <td><input type=\"checkbox\" name=\"planid\" id=\"planid_";
	echo $p["planid"];
	echo "\" value=\"";
	echo $p["planid"];
	echo "\" ";

	if ($plan["status"] == "0") {
		echo "disabled";
	}

	echo " /></td>\r\n\t\t\t\r\n            <td>";
	echo $p["planid"];
	echo "</td>\r\n            <td><a href=\"#\" class=\"editable_a  e_info\"  planid=\"";
	echo $p["planid"];
	echo "\" isshow=1>";
	echo $p["planname"];
	echo "</a></td>\r\n            <td>";
	echo ucfirst($p["plantype"]);
	echo "</td>\r\n            <td>";

	if ($u["username"] == "") {
		echo "[已删除]<br>" . $p["username"];
	}
	else {
		echo "<a  target=\"_blank\" href=\"" . url("admin/user.advertiser_list?searchtype=uid&search=" . $u["uid"]) . "\">" . $u["username"] . "</a>";
	}

	echo "</td>\r\n            <td>";
	$af = ($p["plantype"] == "cps" ? "%" : "");
	$price = format_plan_print($p);

	if (is_array($price)) {
		echo $price["min"] . $af . "~" . $price["max"] . $af;
	}
	else if ($price == "custom") {
		echo "接口自定义";
	}
	else {
		echo "<a href=\"#\"  class=\"editable_a e_price\"  planid=\"" . $p["planid"] . "\" price=\"" . $price . "\">" . $price . "</a>";
	}

	echo "</td>\r\n            <td>";
	$af = ($p["plantype"] == "cps" ? "%" : "");
	$price = format_plan_print($p, "adv");

	if (is_array($price)) {
		echo $price["min"] . $af . "~" . $price["max"] . $af;
	}
	else if ($price == "custom") {
		echo "接口自定义";
	}
	else {
		echo "<a href=\"#\"  class=\"editable_a e_priceadv\"  planid=\"" . $p["planid"] . "\" priceadv=\"" . $price . "\">" . $price . "</a>";
	}

	echo "</td>\r\n            <td><a href=\"#\"  class=\"editable_a e_budget\" planid=\"";
	echo $p["planid"];
	echo "\" budget=\"";
	echo abs($p["budget"]);
	echo "\">";
	echo abs($p["budget"]);
	echo "</a></td>\r\n            <td><a href=\"#\" class=\"editable_a e_clearing\"   planid=\"";
	echo $p["planid"];
	echo "\" clearing=\"";
	echo $p["clearing"];
	echo "\" >";

	if ($p["clearing"] == "day") {
		echo "日结";
	}

	if ($p["clearing"] == "week") {
		echo "周结";
	}

	if ($p["clearing"] == "month") {
		echo "月结";
	}

	echo "</a></td>\r\n            <td><a href=\"#\" class=\"editable_a e_deduction\"   planid=\"";
	echo $p["planid"];
	echo "\" deduction=\"";
	echo $p["deduction"];
	echo "\" >";
	echo abs($p["deduction"]);
	echo "%</a></td>\r\n            <td class=\"status\">";

	if ($u["status"] != "2") {
		$p["status"] = 5;
	}

	switch ($p["status"]) {
	case 0:
		echo "<span class=\"notification error_bg\">待审</span>";
		break;

	case 1:
		echo "<span class=\"notification info_bg\">活动</span>";
		break;

	case 2:
		echo "<span class=\"notification error_bg\">锁定</span>";
		break;

	case 3:
		echo "<span class=\"notification error_bg\">暂停中(限额)</span>";
		break;

	case 4:
		echo "<span class=\"notification error_bg\">停止(过期或是余额不足)</span>";
	case 5:
		echo "<span class=\"notification error_bg\">广告商未激活</span>";
	case 6:
		echo "<span class=\"notification error_bg\">暂停中(修改)</span>";
	}

	echo "            </td>\r\n            <td status=\"";
	echo $p["status"];
	echo "\"><a href=\"#\" id=\"uld_unlock\" planid=\"";
	echo $p["planid"];
	echo "\">激活</a> <a href=\"#\"  id=\"uld_lock\" planid=\"";
	echo $p["planid"];
	echo "\">锁定</a> <a href=\"#\"  id=\"uld_del\" planid=\"";
	echo $p["planid"];
	echo "\">删除</a> <a href=\"";
	echo url("admin/plan.edit?planid=" . $p["planid"]);
	echo "\">编辑</a></td>\r\n          </tr>\r\n          \r\n          \r\n          \r\n            <tr class=\"u_info\" id=\"u_info_";
	echo $p["planid"];
	echo "\" style=\"display:none\">\r\n            <td colspan=\"103\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"tab_info\">\r\n                <tr>\r\n                  <td width=\"55%\" height=\"30\"><strong>基本信息</strong><span style=\"padding-left:20px\">\r\n                  <a href=\"";
	echo url("admin/ad.add?planid=" . $p["planid"]);
	echo "\"  target=\"_blank\">新建广告</a>\r\n                  <a href=\"";
	echo url("admin/ad.get_list?searchtype=planid&search=" . $p["planid"]);
	echo "\"  target=\"_blank\">查看广告</a>\r\n                  <a href=\"";
	echo url("admin/stats.plan_list?searchtype=planid&searchval=" . $p["planid"]);
	echo "\" target=\"_blank\">查看报表</a>\r\n                  <a href=\"";
	echo url("admin/trend.get_list?timerange=" . date("Y-n-d", mktime(0, 0, 0, date("m", TIMES), date("d", TIMES) - 6, date("Y", TIMES))) . "_" . DAYS . "&searchtype=planid&searchval=" . $p["planid"]);
	echo "\" target=\"_blank\">流量趋势</a>\r\n                  <a href=\"";
	echo url("admin/ip.get_list?timerange=" . DAYS . "_" . DAYS . "&searchtype=planid&searchval=" . $p["planid"]);
	echo "\" target=\"_blank\">当天IP信息</a><a href=\"#\" class=\"get_plancode\" planid=\"";
	echo $p["planid"];
	echo "\">获取跟踪代码</a></span></td>\r\n                  <td width=\"10\">&nbsp;</td>\r\n                  <td width=\"44%\"><strong>流量走势</strong></td>\r\n                </tr>\r\n                <tr>\r\n                  <td bgcolor=\"#FFFFFF\"><table width=\"90%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:15px; margin-bottom:15px\">\r\n                      <tr>\r\n                        <td>投放设备：</td>\r\n                        <td class=\"color_h\">";
	$ucp = (array) unserialize($p["checkplan"]);
	$pc_mob = array();

	if ($ucp["mobile"]["isacl"] != "all") {
		$md = $ucp["mobile"]["data"];

		if (in_array("pc", (array) $md)) {
			$pc_mob[] = "pc";
		}

		if (in_array("pc", (array) $md) && (1 < count($md))) {
			$pc_mob[] = "pc";
			$pc_mob[] = "mob";
		}

		if (!in_array("pc", (array) $md) && $md) {
			$pc_mob[] = "mob";
		}
	}

	if (!$pc_mob) {
		echo "所有设备";
	}
	else {
		if (in_array("pc", (array) $pc_mob)) {
			echo "桌面电脑";
		}

		if (in_array("mob", (array) $pc_mob)) {
			echo 2 < count($pc_mob) ? "、" : "";
			echo "移动设备";
		}
	}

	echo "</td>\r\n                        <td>定向功能：</td>\r\n                        <td  class=\"color_h\" > ";
	$checkplan = false;

	foreach ($ucp as $k => $v ) {
		if (($v["isacl"] != "all") && ($k != "mobile")) {
			$checkplan = true;
		}
	}

	if ($checkplan) {
		echo "已启用";
	}
	else {
		echo "未启用";
	}

	echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td width=\"100\">是否需要申请：</td>\r\n                        <td  class=\"color_h\">";
	echo $p["audit"] == "n" ? "无需申请" : "需要申请";
	echo "</td>\r\n                        <td width=\"100\">活动分类:</td>\r\n                        <td class=\"color_h\">";
	$c = dr("admin/class.get_one", $p["classid"]);
	echo $c["classname"];
	echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>活动周期：</td>\r\n                        <td class=\"color_h\"> ";

	if ($p["expire"] == "0000-00-00") {
		echo "长期有效";
	}
	else {
		echo substr($p["addtime"], 0, 10) . " 至 " . $p["expire"];
	}

	echo " </td>\r\n                        <td>是否推荐:</td>\r\n                        <td class=\"color_h\">";

	if ($p["top"] == "0") {
		echo "不推荐";
	}
	else {
		echo "推荐";
	}

	echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>会员限制：</td>\r\n                        <td class=\"color_h\">";

	if ($p["restrictions"] == "1") {
		echo "不限制";
	}
	else {
		echo "有限制";
	}

	echo "</td>\r\n                        <td>网站限制：</td>\r\n                        <td class=\"color_h\">";

	if ($p["sitelimit"] == "1") {
		echo "不限制";
	}
	else {
		echo "有限制";
	}

	echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>移动出价：</td>\r\n                        <td class=\"color_h\">";
	echo $p["mobile_price"];
	echo "倍</td>\r\n                        <td>应用包大小：</td>\r\n                        <td class=\"color_h\">";
	echo $p["size"];
	echo "M</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>Logo：</td>\r\n                        <td colspan=\"3\" class=\"color_h\">\r\n\t\t\t\t\t\t";

	if ($p["logo"]) {
		echo "                        <img src=\"";
		echo $p["logo"];
		echo "\" border=\"0\" />\r\n                        ";
	}

	echo "                        </td>\r\n                      </tr>\r\n                    </table></td>\r\n                  <td>&nbsp;</td>\r\n                  <td bgcolor=\"#FFFFFF\"> <div id=\"container_";
	echo $p["planid"];
	echo "\" style=\" height:200px; line-height:200px; text-align:center\"></div></td>\r\n                </tr>\r\n              </table></td>\r\n          </tr>\r\n          \r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n  </div>\r\n  </div>\r\n</div>\r\n</div>\r\n\r\n \r\n\r\n \r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/lib/highcharts/js/highcharts.js\"></script>\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/lib/editable/editable.js\"></script> \r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\nvar a_url={\r\n\te_price:\"";
echo url("admin/plan.update_price?type=aff");
echo "\",\r\n\te_priceadv:\"";
echo url("admin/plan.update_price?type=adv");
echo "\",\r\n\te_budget:\"";
echo url("admin/plan.update_budget");
echo "\",\r\n\te_deduction:\"";
echo url("admin/plan.update_deduction");
echo "\",\r\n\te_clearing:\"";
echo url("admin/plan.update_clearing");
echo "\",\r\n\tdel:\"";
echo url("admin/plan.del");
echo "\",\r\n\tunlock:\"";
echo url("admin/plan.unlock");
echo "\",\r\n\tlock:\"";
echo url("admin/plan.lock");
echo "\",\r\n\tstatus0:\"";
echo url("admin/plan.edit?planid=");
echo "\",\r\n\tjumpurl:\"";
echo $GLOBALS["C_ZYIIS"]["jump_url"] . WEB_URL;
echo "\"\r\n}; \r\n\r\n \r\n\t\t\t \r\nvar charts ={\r\n\turl:\"";
echo url("admin/plan.get_7day_trend");
echo "\",\r\n\tdata:\"timerange=";
echo $get_timerange["7day"];
echo "&planid=\",\r\n\tloading:'<img src=\"";
echo SRC_TPL_DIR;
echo "/images/loading.gif\" width=\"19\" height=\"18\" />'\r\n} \r\n\r\n </script>\r\n<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/plan.js\"></script>\r\n\r\n\r\n";
TPL::display("footer");

?>
