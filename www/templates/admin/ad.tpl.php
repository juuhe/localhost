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
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid mt60\" >\r\n    <h3 class=\"heading tab_heading\">广告管理</h3>\r\n    <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/ad.get_list");
echo "\" class=\"tab-btn  list ";

if ($plantype === "") {
	echo "tab-state-active";
}

echo "\">全部列表</a> ";

foreach (explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
	echo " <a href=\"";
	echo url("admin/ad.get_list?plantype=" . $t);
	echo "\" class=\"tab-btn ad";
	echo str_replace("+", "", $t);
	echo " ";

	if ($plantype == $t) {
		echo "tab-state-active";
	}

	echo "\"> ";
	echo strtoupper($t);
	echo "广告</a> ";
}

echo " </div>\r\n    </div>\r\n    \r\n\t<div  class=\"dataTables_wrapper \">\r\n\t\r\n\t   \r\n\t    <div class=\"tb_sts\"><a href=\"";
echo url("admin/ad.add");
echo "\"  class=\"tab-btn add \">新增广告</a><a href=\"";
echo url("admin/ad." . RUN_ACTION . "?status=0&plantype=" . $plantype);
echo "\" class=\"tab-btn red ";

if ($status == "0") {
	echo "tb_sts-active";
}

echo "\">待审核</a><a href=\"";
echo url("admin/ad." . RUN_ACTION . "?status=3&plantype=" . $plantype);
echo "\" class=\"tab-btn unlock ";

if ($status == "3") {
	echo "tb_sts-active";
}

echo "\"> 投放中</a> <a href=\"";
echo url("admin/ad." . RUN_ACTION . "?status=1&plantype=" . $plantype);
echo "\" class=\"tab-btn lock ";

if ($status == "1") {
	echo "tb_sts-active";
}

echo "\">已锁定</a> <a href=\"";
echo url("admin/ad." . RUN_ACTION . "?status=6&plantype=" . $plantype);
echo "\" class=\"tab-btn expired ";

if ($status == "6") {
	echo "tb_sts-active";
}

echo "\">计划停止中</a></div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\"   style=\"width:55%\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"unlock\">激活</option>\r\n              <option value=\"lock\" >锁定</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n \r\n\t\t\t <select size=\"1\" name=\"choose_type\" id=\"choose_type\" style=\"margin-left:20px\"  onchange=\"location.href = this.options[selectedIndex].value\">\r\n\t\t\t\r\n               <option value=\"";
echo url("admin/ad." . RUN_ACTION . "?status=" . $status . "&plantype=" . $plantype);
echo "\" >所有类型</option>\r\n\t\t\t   ";

foreach ((array) $get_adtype_all as $key => $at ) {
	$oh = "<optgroup label='" . $at["name"] . "'>";

	if ($at["tpl"]) {
		foreach ((array) $at["tpl"] as $al ) {
			$oh .= "<option value=" . url("admin/ad." . RUN_ACTION . "?status=" . $status . "&plantype=" . $plantype . "&adtplid=" . $al["tplid"]) . "";

			if ($adtplid == $al["tplid"]) {
				$oh .= " selected";
			}

			$oh .= ">" . $al["name"] . "</option>";
		}
	}

	$oh .= "</optgroup>";
	echo $oh;
	echo "             \r\n               ";
}

echo "            </select>\r\n\t\t\t\r\n\t\t\t<!-- <option value=\"";
echo url("admin/ad." . RUN_ACTION . "?status=" . $status . "&plantype=" . $plantype . "&adtplid=" . $key);
echo "\"  ";

if ($adtplid == $key) {
	echo "selected";
}

echo ">";
echo $at["name"];
echo "</option>-->\r\n             \r\n          </div>\r\n        </div>\r\n        <div class=\"span6\"  style=\"width:45%\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\">\r\n            <form method=\"post\">\r\n              搜索：\r\n              <select name=\"searchtype\">\r\n                <option value=\"adsid\" ";

if ($searchtype == "adsid") {
	echo "selected";
}

echo ">广告ID</option>\r\n                <option value=\"planid\" ";

if ($searchtype == "planid") {
	echo "selected";
}

echo ">计划ID</option>\r\n                <option value=\"uid\" ";

if ($searchtype == "uid") {
	echo "selected";
}

echo ">广告商ID</option>\r\n\t\t\t\t <option value=\"url\" ";

if ($searchtype == "url") {
	echo "selected";
}

echo ">URL地址</option>\r\n              </select>\r\n              <input type=\"text\" class=\"input_text span30\" name=\"search\" value=\"";
echo $search;
echo "\">\r\n              <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n            </form>\r\n          </div>\r\n        </div>\r\n      </div>\r\n\t  \r\n      <table id=\"dt_inbox\" class=\"dataTable\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\" /></th>\r\n            <th><em>ID</em></th>\r\n            <th>广告名称</th>\r\n            <th>类型</th>\r\n            <th>计划名称</th>\r\n            \r\n            <th>尺寸</th>\r\n            <th>广告商</th>\r\n            <th>计费</th>\r\n            <th>权重</th>\r\n            <th>状态</th>\r\n            <th>操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $ad as $p ) {
	$u = dr("admin/user.get_one", $p["uid"]);
	$plan = dr("admin/plan.get_one", $p["planid"]);
	$tpl = dr("admin/adtpl.get_one", $p["adtplid"]);
	echo "          <tr class=\"unread odd\">\r\n            <td><input type=\"checkbox\" name=\"adsid\" id=\"adsid_";
	echo $p["adsid"];
	echo "\" value=\"";
	echo $p["adsid"];
	echo "\" ";

	if ($ad["status"] == "0") {
		echo "disabled";
	}

	echo " /></td>\r\n            <td> <a href=\"#\" class=\"editable_a e_info\"  adsid=\"";
	echo $p["adsid"];
	echo "\" isshow=1>";
	echo $p["adsid"];
	echo "</a></td>\r\n            <td><a href=\"#\"  class=\"editable_a e_adname\" adsid=\"";
	echo $p["adsid"];
	echo "\" adname=\"";
	echo $p["adname"] ? $p["adname"] : "创建于" . $p["addtime"];
	echo "\">";
	echo $p["adname"] ? $p["adname"] : "创建于" . $p["addtime"];
	echo "</a></td>\r\n            <td>";
	echo $tpl["tplname"];
	echo "</td>\r\n            <td><a href=\"";
	echo url("admin/plan.get_list?searchtype=planid&search=" . $p["planid"]);
	echo "\">";
	echo $plan["planname"];
	echo "</a></td>\r\n            \r\n            <td>";
	echo $p["width"] . "x" . $p["height"];
	echo "</td>\r\n            <td><a href=\"";
	echo url("admin/user.advertiser_list?searchtype=uid&search=" . $p["uid"]);
	echo "\">";
	echo $u["username"] == "" ? "[已删除]<br>" . $u["username"] : $u["username"];
	echo "</a></td>\r\n            <td>";
	echo strtoupper($plan["plantype"]);
	echo "</td>\r\n            <td><a href=\"#\"  class=\"editable_a e_priority\" adsid=\"";
	echo $p["adsid"];
	echo "\" priority=\"";
	echo $p["priority"];
	echo "\">";
	echo $p["priority"];
	echo "</a></td>\r\n            <td class=\"status\">";

	if ($u["status"] != "2") {
		$p["status"] = 5;
	}

	if ($plan["status"] != "1") {
		$p["status"] = 6;
	}

	switch ($p["status"]) {
	case 0:
		echo "<span class=\"notification error_bg\">新增待审</span>";
		break;

	case 1:
		echo "<span class=\"notification error_bg\">已被锁定</span>";
		break;

	case 2:
		echo "<span class=\"notification error_bg\">修改待审</span>";
		break;

	case 3:
		echo "<span class=\"notification info_bg\">活动</span>";
		break;

	case 4:
		echo "<span class=\"notification error_bg\">已被锁定</span>";
		break;

	case 5:
		echo "<span class=\"notification error_bg\">广告商未激活</span>";
		break;

	case 6:
		echo "<span class=\"notification error_bg\">计划未激活</span>";
		break;
	}

	echo "            </td>\r\n            <td><a href=\"#\" id=\"uld_unlock\" adsid=\"";
	echo $p["adsid"];
	echo "\">激活</a> <a href=\"#\"  id=\"uld_lock\" adsid=\"";
	echo $p["adsid"];
	echo "\">锁定</a> <a href=\"#\"  id=\"uld_del\" adsid=\"";
	echo $p["adsid"];
	echo "\">删除</a> <a href=\"";
	echo url("admin/ad.edit?adsid=" . $p["adsid"]);
	echo "\">编辑</a></td>\r\n          </tr>\r\n          \r\n          \r\n          \r\n          \r\n          <tr class=\"u_info\" id=\"u_info_";
	echo $p["adsid"];
	echo "\" style=\"display:none\">\r\n            <td colspan=\"103\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"tab_info\">\r\n                <tr>\r\n                  <td width=\"50%\" height=\"30\"><strong>基本信息</strong><span style=\"padding-left:20px\"><a href=\"";
	echo url("admin/stats.ads_list?searchtype=adsid&searchval=" . $p["adsid"]);
	echo "\" target=\"_blank\">查看报表</a>\r\n                  \r\n                  <a href=\"";
	echo url("admin/ip.get_list?timerange=" . DAYS . "_" . DAYS . "&searchtype=adsid&searchval=" . $p["adsid"]);
	echo "\" target=\"_blank\">当天IP信息</a>\r\n                  <a href=\"javascript:;\" class=\"implant_zone\" adsid=\"";
	echo $p["adsid"];
	echo "\">植入到广告位</a>\r\n                  </span></td>\r\n                  <td width=\"10\">&nbsp;</td>\r\n                  <td width=\"49%\"><strong>广告预览</strong></td>\r\n                </tr>\r\n                <tr>\r\n                  <td bgcolor=\"#FFFFFF\"><table width=\"90%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:15px; margin-bottom:15px\">\r\n                      <tr>\r\n                        <td width=\"100\">创建时间：</td>\r\n                        <td class=\"color_h\">";
	echo $p["addtime"];
	echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>单价：</td>\r\n                        <td class=\"color_h\">";
	$af = ($p["plantype"] == "cps" ? "%" : "元");
	$price = format_plan_print($p);

	if (is_array($price)) {
		echo $price["min"] . $af . "~" . $price["max"] . $af;
	}
	else if ($price == "custom") {
		echo "接口自定义";
	}
	else {
		echo $price . $af;
	}

	echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>描述：</td>\r\n                        <td class=\"color_h\">";
	echo $p["adinfo"];
	echo "</td>\r\n                      </tr>\r\n                    </table></td>\r\n                  <td>&nbsp;</td>\r\n                  <td bgcolor=\"#FFFFFF\"> <div id=\"view_";
	echo $p["adsid"];
	echo "\" style=\" margin-top:10px;text-align:center\"></div></td>\r\n                </tr>\r\n              </table></td>\r\n          </tr>\r\n          \r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n  </div>\r\n  </div>\r\n</div>\r\n</div>\r\n\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/lib/editable/editable.js\"></script> \r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\nvar a_url={\r\n\tdel:\"";
echo url("admin/ad.del");
echo "\",\r\n\tunlock:\"";
echo url("admin/ad.unlock");
echo "\",\r\n\tlock:\"";
echo url("admin/ad.lock");
echo "\",\r\n\timplant_zone:\"";
echo url("admin/ad.implant_zone");
echo "\",\r\n\te_adname:\"";
echo url("admin/ad.update_adname");
echo "\",\r\n\te_priority:\"";
echo url("admin/ad.update_priority");
echo "\",\r\n\tview:\"";
echo url("admin/ad.demo?adsid=");
echo "\",\r\n}; \r\n \r\n </script>\r\n \r\n <script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/a_d.js\"></script>\r\n";
TPL::display("footer");
//echo " ";

?>
