<?php

if (!defined("IN_ZYADS")) {
	exit();
}

TPL::display("header");

switch (RUN_ACTION) {
case "affiliate":
	$type_text = "站长";
	break;

case "advertiser_list":
	$type_text = "广告商";
	break;

case "service_list":
	$type_text = "客服";
	break;

case "commercial_list":
	$type_text = "商务";
	break;
}

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
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid mt60\" >\r\n    <h3 class=\"heading tab_heading\">";
echo $type_text;
echo "管理</h3>\r\n    <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/user.affiliate_list");
echo "\" class=\"tab-btn  affiliate_list ";

if (RUN_ACTION == "affiliate_list") {
	echo "tab-state-active";
}

echo "\">站长管理</a> <a href=\"";
echo url("admin/user.advertiser_list");
echo "\" class=\"tab-btn advertiser_list ";

if (RUN_ACTION == "advertiser_list") {
	echo "tab-state-active";
}

echo "\"> 广告商</a> <a href=\"";
echo url("admin/user.service_list");
echo "\" class=\"tab-btn service_list ";

if (RUN_ACTION == "service_list") {
	echo "tab-state-active";
}

echo "\"> 客服管理</a> <a href=\"";
echo url("admin/user.commercial_list");
echo "\" class=\"tab-btn commercial_list ";

if (RUN_ACTION == "commercial_list") {
	echo "tab-state-active";
}

echo "\"> 商务管理</a></div>\r\n    </div>\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"tb_sts\"><a href=\"javascript:;\"  class=\"tab-btn add add_user\"  >新增会员</a><a href=\"";
echo url("admin/user." . RUN_ACTION . "?status=0");
echo "\" class=\"tab-btn red ";

if ($status == "0") {
	echo "tb_sts-active";
}

echo "\">待审核</a><a href=\"";
echo url("admin/user." . RUN_ACTION . "?status=2");
echo "\" class=\"tab-btn unlock ";

if ($status == "2") {
	echo "tb_sts-active";
}

echo "\"> 已审核</a> <a href=\"";
echo url("admin/user." . RUN_ACTION . "?status=4");
echo "\" class=\"tab-btn lock ";

if ($status == "4") {
	echo "tb_sts-active";
}

echo "\">已锁定</a></div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"unlock\">激活</option>\r\n              <option value=\"lock\" >锁定</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n          </div>\r\n        </div>\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\">\r\n            <form method=\"post\">\r\n              搜索：\r\n              <select name=\"searchtype\">\r\n                <option value=\"username\" ";

if ($searchtype == "username") {
	echo "selected";
}

echo ">站长名称</option>\r\n                <option value=\"uid\" ";

if ($searchtype == "uid") {
	echo "selected";
}

echo ">站长ID</option>\r\n                <option value=\"qq\" ";

if ($searchtype == "qq") {
	echo "selected";
}

echo ">联系QQ</option>\r\n                <option value=\"serviceid\" ";

if ($searchtype == "serviceid") {
	echo "selected";
}

echo ">属于客服</option>\r\n                <option value=\"bankname\" ";

if ($searchtype == "bankname") {
	echo "selected";
}

echo ">收款人</option>\r\n                <option value=\"recommend\" ";

if ($searchtype == "recommend") {
	echo "selected";
}

echo ">查询下线</option>\r\n              </select>\r\n              <select name=\"groupid\">\r\n                <option value=\"\">所有分组</option>\r\n                ";

foreach ((array) $group as $g ) {
	echo "                <option value=\"";
	echo $g["groupid"];
	echo "\" ";

	if ($g["groupid"] == $groupid) {
		echo "selected";
	}

	echo " >";
	echo $g["groupname"];
	echo "</option>\r\n                ";
}

echo "              </select>\r\n              <input type=\"text\" class=\"input_text span30\" name=\"search\" value=\"";
echo $search;
echo "\">\r\n              <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n            </form>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      ";

if (RUN_ACTION === "affiliate_list") {
	echo "      <table id=\"dt_inbox\" class=\"dataTable \">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\"></th>\r\n            <th><em>ID</em></th>\r\n            <th>站长名称</th>\r\n            <th>总余额</th>\r\n            <th>日余额</th>\r\n            <th>周余额</th>\r\n            <th>月余额</th>\r\n            <th>下线余额</th>\r\n            <th>扣量</th>\r\n            <th>用户分组</th>\r\n            <th>状态</th>\r\n            <th>操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

	foreach ((array) $user as $u ) {
		$g = dr("admin/group.get_one", $u["groupid"]);
		$deduction = unserialize($u["deduction"]);
		$money = (0 < $u["money"] ? round($u["money"], 2) : "0");
		$daymoney = (0 < $u["daymoney"] ? round($u["daymoney"], 2) : "0");
		$weekmoney = (0 < $u["weekmoney"] ? round($u["weekmoney"], 2) : "0");
		$monthmoney = (0 < $u["monthmoney"] ? round($u["monthmoney"], 2) : "0");
		$xmoney = (0 < $u["xmoney"] ? round($u["xmoney"], 2) : "0");
		echo "          <tr class=\"unread odd\" >\r\n            <td height=\"30\"><input type=\"checkbox\" name=\"uid\" id=\"uid_";
		echo $u["uid"];
		echo "\" value=\"";
		echo $u["uid"];
		echo "\"></td>\r\n            <td ><a href=\"";
		echo url("admin/user.glogin?uid=" . $u["uid"]);
		echo "\"  target=\"_blank\" class=\"glogin\">";
		echo $u["uid"];
		echo "</a></td>\r\n            <td><a href=\"#\" class=\"editable_a e_info\"  uid=\"";
		echo $u["uid"];
		echo "\" isshow=1>";
		echo $u["username"];
		echo "</a></td>\r\n            <td>";
		echo $money;
		echo "</td>\r\n            <td ><a href=\"#\"  class=\"editable_a e_money\"  uid=\"";
		echo $u["uid"];
		echo "\" money=\"";
		echo $daymoney;
		echo "\" moneytype='daymoney' >";
		echo $daymoney;
		echo "</a></td>\r\n            <td><a href=\"#\"  class=\"editable_a e_money\" uid=\"";
		echo $u["uid"];
		echo "\" money=\"";
		echo $weekmoney;
		echo "\" moneytype='weekmoney'>";
		echo $weekmoney;
		echo "</a></td>\r\n            <td><a href=\"#\"  class=\"editable_a e_money\" uid=\"";
		echo $u["uid"];
		echo "\" money=\"";
		echo $monthmoney;
		echo "\" moneytype='monthmoney'>";
		echo $monthmoney;
		echo "</a></td>\r\n            <td><a href=\"#\" class=\"editable_a e_money\" uid=\"";
		echo $u["uid"];
		echo "\"  money=\"";
		echo $xmoney;
		echo "\" moneytype='xmoney'>";
		echo $xmoney;
		echo "</a></td>\r\n            <td><a href=\"#\" class=\"editable_a e_deduction\"  uid=\"";
		echo $u["uid"];
		echo "\" ";

		foreach ((array) $deduction as $dk => $dv ) {
			echo "         " . $dk . "='" . ($dv ? $dv : 0) . "'";
		}

		echo " > ";
		echo 0 < array_sum((array) $deduction) ? implode(",", (array) $deduction) : "0";
		echo " </a></td>\r\n            <td><a href=\"#\" class=\"editable_a e_group\"   uid=\"";
		echo $u["uid"];
		echo "\" groupid=\"";
		echo $g["groupid"];
		echo "\" >";
		echo $g["groupname"] ? $g["groupname"] : "未分组";
		echo "</a></td>\r\n            <td class=\"status\">";

		switch ($u["status"]) {
		case 0:
			echo "<span class=\"notification error_bg\">待审</span>";
			break;

		case 1:
			echo "<span class=\"notification error_bg\">邮件激活</span>";
			break;

		case 2:
			echo "<span class=\"notification info_bg\">活动</span>";
			break;

		case 3:
			echo "<span class=\"notification error_bg\">拒绝</span>";
			break;

		case 4:
			echo "<span class=\"notification error_bg\">锁定</span>";
		}

		echo "</td>\r\n            <td  ><a href=\"#\" id=\"uld_unlock\" uid=\"";
		echo $u["uid"];
		echo "\">激活</a> <a href=\"#\"  id=\"uld_lock\" uid=\"";
		echo $u["uid"];
		echo "\">锁定</a> <a href=\"#\"  id=\"uld_del\" uid=\"";
		echo $u["uid"];
		echo "\">删除</a> <a href=\"";
		echo url("admin/user.edit?uid=" . $u["uid"]);
		echo "\">编辑</a></td>\r\n          </tr>\r\n          <tr class=\"u_info\" id=\"u_info_";
		echo $u["uid"];
		echo "\" style=\"display:none\">\r\n            <td colspan=\"103\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"tab_info\">\r\n                <tr>\r\n                  <td height=\"30\"><strong>用户详情</strong> <span style=\"padding-left:20px\"> <a href=\"";
		echo url("admin/stats.user_list?searchtype=uid&searchval=" . $u["uid"]);
		echo "\"  target=\"_blank\">查看报表</a> <a href=\"";
		echo url("admin/site.get_list?searchtype=uid&search=" . $u["uid"]);
		echo "\"  target=\"_blank\">查看网站</a> <a href=\"";
		echo url("admin/zone.get_list?searchtype=uid&search=" . $u["uid"]);
		echo "\" target=\"_blank\">查看广告位</a> <a href=\"";
		echo url("admin/trend.get_list?timerange=" . date("Y-n-d", mktime(0, 0, 0, date("m", TIMES), date("d", TIMES) - 6, date("Y", TIMES))) . "_" . DAYS . "&searchtype=uid&searchval=" . $u["uid"]);
		echo "\" target=\"_blank\">流量趋势</a> <a href=\"";
		echo url("admin/ip.get_list?timerange=" . DAYS . "_" . DAYS . "&searchtype=uid&searchval=" . $u["uid"]);
		echo "\" target=\"_blank\">当天IP信息</a></span></td>\r\n                  <td width=\"10\">&nbsp;</td>\r\n                  <td><strong>其它信息</strong></td>\r\n                </tr>\r\n                <tr>\r\n                  <td bgcolor=\"#FFFFFF\"><table width=\"90%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:15px; margin-bottom:15px\">\r\n                      <tr>\r\n                        <td>属于客服：</td>\r\n                        <td class=\"color_h\">";

		if ($u["serviceid"]) {
			$us = dr("admin/user.get_one", $u["serviceid"]);
			echo "<a  target=\"_blank\" href=\"" . url("admin/user.service_list?searchtype=uid&search=" . $u["serviceid"]) . "\">" . $us["username"] . "</a>";
		}
		else {
			echo "没有分配";
		}

		echo "</td>\r\n                        <td>已结算总额：</td>\r\n                        <td  >";
		echo abs(dr("admin/pay.get_sum_uid", $u["uid"]));
		echo "元</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td width=\"100\">联系人：</td>\r\n                        <td  class=\"color_h\">";
		echo $u["contact"];
		echo "</td>\r\n                        <td width=\"100\">积分:</td>\r\n                        <td class=\"color_h\">";
		echo abs($u["integral"]);
		echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>QQ：</td>\r\n                        <td class=\"color_h\"><a href=\"http://wpa.qq.com/msgrd?v=3&amp;uin=";
		echo $u["qq"];
		echo "&amp;site=qq&amp;menu=yes\" target=\"_blank\">";
		echo $u["qq"];
		echo "</a></td>\r\n                        <td>EMAIL:</td>\r\n                        <td class=\"color_h\">";
		echo $u["email"];
		echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>联系电话：</td>\r\n                        <td class=\"color_h\">";
		echo $u["mobile"];
		echo "</td>\r\n                        <td>身分证：</td>\r\n                        <td class=\"color_h\">";
		echo $u["idcard"];
		echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>收款银行：</td>\r\n                        <td class=\"color_h\">";

		foreach ($GLOBALS["c_bank"] as $b => $v ) {
			if (!$v[1]) {
				continue;
			}

			if ($u["bankname"] == $v[0]) {
				echo $b;
			}
		}

		echo "</td>\r\n                        <td>开户分行：</td>\r\n                        <td class=\"color_h\">";
		echo $u["bankbranch"];
		echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td><label>收款姓名：</label></td>\r\n                        <td class=\"color_h\">";
		echo $u["accountname"];
		echo "</td>\r\n                        <td><label>收款帐号：</label></td>\r\n                        <td class=\"color_h\">";
		echo $u["bankaccount"];
		echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>备注：</td>\r\n                        <td colspan=\"3\" class=\"color_h\">";
		echo $u["memo"];
		echo "</td>\r\n                      </tr>\r\n                    </table></td>\r\n                  <td>&nbsp;</td>\r\n                  <td bgcolor=\"#FFFFFF\"><table width=\"90%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:10px\">\r\n                      <tr>\r\n                        <td>域名限制：</td>\r\n                        <td class=\"color_h\">";

		if ($u["insite"] == "1") {
			echo "默认";
		}

		if ($u["insite"] == "2") {
			echo "开启";
		}

		if ($u["insite"] == "3") {
			echo "关闭";
		}

		echo "</td>\r\n                        <td>pv步长值：</td>\r\n                        <td class=\"color_h\">";
		echo $u["pvstep"];
		echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td width=\"100\">独立分成：</td>\r\n                        <td class=\"color_h\">";
		echo $u["usercommission"];
		echo "</td>\r\n                        <td width=\"100\">Cooke时效：</td>\r\n                        <td class=\"color_h\">";
		echo $u["commissiontime"];
		echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>下线会员：</td>\r\n                        <td class=\"color_h\">";
		echo dr("admin/user.get_sum_recommend", $u["uid"]);
		echo "个</td>\r\n                        <td>网站数量：</td>\r\n                        <td class=\"color_h\"><a href=\"";
		echo url("admin/site.get_list?searchtype=uid&search=" . $u["uid"]);
		echo "\" target=\"new\">";
		echo dr("admin/site.get_sum_recommend", $u["uid"]);
		echo "个</a></td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>推荐人：</td>\r\n                        <td class=\"color_h\"><a  target=\"_blank\" href=\"";
		echo url("admin/user.affiliate_list?searchtype=uid&search=" . $u["recommend"]);
		echo "\">";
		echo $u["recommend"];
		echo "</a></td>\r\n                        <td>广告位数量</td>\r\n                        <td class=\"color_h\"><a href=\"";
		echo url("admin/zone.get_list?searchtype=uid&search=" . $u["uid"]);
		echo "\" target=\"_blank\">";
		echo dr("admin/zone.get_sum_recommend", $u["uid"]);
		echo "个</a></td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>最近登入时间\r\n                          <label>：</label></td>\r\n                        <td colspan=\"3\" class=\"color_h\">";
		echo $u["logintime"];
		echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>最近登入IP\r\n                          <label>：</label></td>\r\n                        <td colspan=\"3\" class=\"color_h\">";
		echo $u["loginip"];
		echo " ";
		echo convert_ip($u["loginip"]);
		echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>总登入次数：</td>\r\n                        <td colspan=\"3\" class=\"color_h\">";
		echo $u["loginnum"];
		echo "</td>\r\n                      </tr>\r\n                    </table></td>\r\n                </tr>\r\n              </table></td>\r\n          </tr>\r\n          ";
	}

	echo "        </tbody>\r\n      </table>\r\n    </div>\r\n    ";
}

echo "    ";

if (RUN_ACTION === "advertiser_list") {
	echo "    <table id=\"dt_inbox\" class=\"dataTable\">\r\n      <thead>\r\n        <tr role=\"row\">\r\n          <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\" /></th>\r\n          <th><em>ID</em></th>\r\n          <th>广告商名称</th>\r\n          <th>总余额</th>\r\n          <th>QQ</th>\r\n          <th>联系人</th>\r\n          <th>电话</th>\r\n          <th>状态</th>\r\n          <th>操作</th>\r\n        </tr>\r\n      </thead>\r\n      <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n        ";

	foreach ((array) $user as $u ) {
		$g = dr("admin/group.get_one", $u["groupid"]);
		echo "        <tr class=\"unread odd\">\r\n          <td><input type=\"checkbox\" name=\"uid\" id=\"uid_";
		echo $u["uid"];
		echo "\" value=\"";
		echo $u["uid"];
		echo "\"></td>\r\n          <td  class=\"manage\" uid=\"";
		echo $u["uid"];
		echo "\"  rating=\"";
		echo $u["rating"];
		echo "\"><a href=\"";
		echo url("admin/user.glogin?uid=" . $u["uid"]);
		echo "\" style=\"border-bottom: dashed 1px #0088cc;\" target=\"_blank\">";
		echo $u["uid"];
		echo "</a></td>\r\n          <td><a href=\"#\"  class=\"editable_a e_info\"  uid=\"";
		echo $u["uid"];
		echo "\" isshow=\"1\">";
		echo $u["username"];
		echo "</a></td>\r\n          <td><a href=\"#\"  class=\"editable_a e_money\"  uid=\"";
		echo $u["uid"];
		echo "\" money=\"";
		echo 0 < $u["money"] ? round($u["money"], 2) : 0;
		echo "\" moneytype='money' >";
		echo 0 < $u["money"] ? round($u["money"], 2) : 0;
		echo "</a></td>\r\n          <td><a href=\"http://wpa.qq.com/msgrd?v=3&amp;uin=";
		echo $u["qq"];
		echo "&amp;site=qq&amp;menu=yes\" target=\"_blank\">";
		echo $u["qq"];
		echo "</a></td>\r\n          <td>";
		echo $u["controls"];
		echo "</td>\r\n          <td>";
		echo $u["mobile"];
		echo "</td>\r\n          <td class=\"status\">";

		switch ($u["status"]) {
		case 0:
			echo "<span class=\"notification error_bg\">待审</span>";
			break;

		case 1:
			echo "<span class=\"notification error_bg\">邮件激活</span>";
			break;

		case 2:
			echo "<span class=\"notification info_bg\">活动</span>";
			break;

		case 3:
			echo "<span class=\"notification error_bg\">拒绝</span>";
			break;

		case 4:
			echo "<span class=\"notification error_bg\">锁定</span>";
		}

		echo "</td>\r\n          <td ><a href=\"#\" id=\"uld_unlock\" uid=\"";
		echo $u["uid"];
		echo "\">激活</a> <a href=\"#\"  id=\"uld_lock\" uid=\"";
		echo $u["uid"];
		echo "\">锁定</a> <a href=\"#\"  id=\"uld_del\" uid=\"";
		echo $u["uid"];
		echo "\">删除</a> <a href=\"";
		echo url("admin/user.edit?uid=" . $u["uid"]);
		echo "\">编辑</a></td>\r\n        </tr>\r\n        <tr class=\"u_info\" id=\"u_info_";
		echo $u["uid"];
		echo "\" style=\"display:none\">\r\n          <td colspan=\"103\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"tab_info\">\r\n              <tr>\r\n                <td height=\"30\"><strong>用户详情</strong> <span style=\"padding-left:20px\"><a href=\"";
		echo url("admin/plan.get_list?searchtype=uid&search=" . $u["uid"]);
		echo "\" target=\"_blank\">查看计划</a> <a href=\"";
		echo url("admin/ad.get_list?searchtype=uid&search=" . $u["uid"]);
		echo "\" target=\"_blank\">查看广告</a> <a href=\"";
		echo url("admin/plan.add?uid=" . $u["uid"]);
		echo "\" target=\"_blank\">新建计划</a> </span></td>\r\n              </tr>\r\n              <tr>\r\n                <td bgcolor=\"#FFFFFF\"><table width=\"96%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:15px; margin-bottom:15px\">\r\n                    <tr>\r\n                      <td>属于商务：</td>\r\n                      <td class=\"color_h\">";

		if ($u["serviceid"]) {
			$us = dr("admin/user.get_one", $u["serviceid"]);
			echo "<a  target=\"_blank\" href=\"" . url("admin/user.commercial_list?searchtype=uid&search=" . $u["serviceid"]) . "\">" . $us["username"] . "</a>";
		}
		else {
			echo "没有分配";
		}

		echo "                        </a></td>\r\n                      <td>共有计划：</td>\r\n                      <td class=\"color_h\">";
		echo dr("admin/plan.get_num", $u["uid"]);
		echo "个 (其中待审";
		echo dr("admin/plan.get_status01_num", $u["uid"]);
		echo "个)</td>\r\n                    </tr>\r\n                    <tr>\r\n                      <td width=\"100\">联系人：</td>\r\n                      <td  class=\"color_h\">";
		echo $u["contact"];
		echo "</td>\r\n                      <td width=\"100\">共有广告：</td>\r\n                      <td class=\"color_h\">";
		echo dr("admin/ad.get_num", $u["uid"]);
		echo "个 (其中待审";
		echo dr("admin/ad.get_status01_num", $u["uid"]);
		echo "个)</td>\r\n                    </tr>\r\n                    <tr>\r\n                      <td>QQ：</td>\r\n                      <td class=\"color_h\"><a href=\"http://wpa.qq.com/msgrd?v=3&uin=";
		echo $u["qq"];
		echo "&site=qq&menu=yes\" target=\"_blank\">";
		echo $u["qq"];
		echo "</a></td>\r\n                      <td>EMAIL:</td>\r\n                      <td class=\"color_h\">";
		echo $u["email"];
		echo "</td>\r\n                    </tr>\r\n                    <tr>\r\n                      <td>联系电话：</td>\r\n                      <td class=\"color_h\">";
		echo $u["mobile"];
		echo "</td>\r\n                      <td>总登入次数：</td>\r\n                      <td class=\"color_h\">";
		echo $u["loginnum"];
		echo "</td>\r\n                    </tr>\r\n                    <tr>\r\n                      <td>最近登入IP\r\n                        <label>：</label></td>\r\n                      <td class=\"color_h\">";
		echo $u["loginip"];
		echo " ";
		echo convert_ip($u["loginip"]);
		echo "</td>\r\n                      <td >最近登入时间：</td>\r\n                      <td class=\"color_h\">";
		echo $u["logintime"];
		echo "</td>\r\n                    </tr>\r\n                  </table></td>\r\n              </tr>\r\n            </table></td>\r\n        </tr>\r\n        ";
	}

	echo "      </tbody>\r\n    </table>\r\n    ";
}

echo "    ";

if (RUN_ACTION === "service_list") {
	echo "    <table id=\"dt_inbox\" class=\"dataTable\">\r\n      <thead>\r\n        <tr role=\"row\">\r\n          <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\" /></th>\r\n          <th><em>ID</em></th>\r\n          <th>客服名称</th>\r\n          <th>联系人</th>\r\n          <th>QQ</th>\r\n          <th>名下人员</th>\r\n          <th title=\"有他名下会员当月产生的佣金费用\">当月业绩？</th>\r\n          <th>状态</th>\r\n          <th>操作</th>\r\n        </tr>\r\n      </thead>\r\n      <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n        ";

	foreach ((array) $user as $u ) {
		echo "        <tr class=\"unread odd\">\r\n          <td><input type=\"checkbox\" name=\"uid\" id=\"uid_";
		echo $u["uid"];
		echo "\" value=\"";
		echo $u["uid"];
		echo "\"></td>\r\n          <td  ><a href=\"";
		echo url("admin/user.glogin?uid=" . $u["uid"]);
		echo "\"  target=\"_blank\">";
		echo $u["uid"];
		echo "</a></td>\r\n          <td>";
		echo $u["username"];
		echo "</td>\r\n          <td>";
		echo $u["contact"];
		echo "</td>\r\n          <td>";
		echo $u["qq"];
		echo "</td>\r\n          <td><a href=\"";
		echo url("admin/user.affiliate_list?searchtype=serviceid&search=" . $u["uid"]);
		echo "\">";
		echo dr("admin/user.get_sum_service", $u["uid"]);
		echo "个</a></td>\r\n          <td>";
		$y = dr("admin/stats.get_performance", $u["uid"], $get_timerange["30day"], "service");
		echo abs($y["pay"]);
		echo "            元</td>\r\n          <td class=\"status\">";

		switch ($u["status"]) {
		case 0:
			echo "<span class=\"notification error_bg\">待审</span>";
			break;

		case 1:
			echo "<span class=\"notification error_bg\">邮件激活</span>";
			break;

		case 2:
			echo "<span class=\"notification info_bg\">活动</span>";
			break;

		case 3:
			echo "<span class=\"notification error_bg\">拒绝</span>";
			break;

		case 4:
			echo "<span class=\"notification error_bg\">锁定</span>";
		}

		echo "</td>\r\n          <td ><a href=\"";
		echo url("admin/user.k_performance?uid=" . $u["uid"]);
		echo "\" target=\"_blank\">我的业绩</a> | <a href=\"#\"  id=\"uld_lock\" uid=\"";
		echo $u["uid"];
		echo "\">锁定</a> | <a href=\"#\"  id=\"uld_del\" uid=\"";
		echo $u["uid"];
		echo "\">删除</a> | <a href=\"";
		echo url("admin/user.edit?uid=" . $u["uid"]);
		echo "\">编辑</a></td>\r\n        </tr>\r\n        ";
	}

	echo "      </tbody>\r\n    </table>\r\n    ";
}

echo "    ";

if (RUN_ACTION === "commercial_list") {
	echo "    <table id=\"dt_inbox\" class=\"dataTable\">\r\n      <thead>\r\n        <tr role=\"row\">\r\n          <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\" /></th>\r\n          <th><em>ID</em></th>\r\n          <th>商务名称</th>\r\n          <th>联系人</th>\r\n          <th>QQ</th>\r\n          <th>名下厂商</th>\r\n          <th title=\"有他名外厂商支付出的广告费用\">当月业绩？</th>\r\n          <th>状态</th>\r\n          <th>操作</th>\r\n        </tr>\r\n      </thead>\r\n      <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n        ";

	foreach ((array) $user as $u ) {
		echo "        <tr class=\"unread odd\">\r\n          <td><input type=\"checkbox\" name=\"uid\" id=\"uid_";
		echo $u["uid"];
		echo "\" value=\"";
		echo $u["uid"];
		echo "\"></td>\r\n          <td><a href=\"";
		echo url("admin/user.glogin?uid=" . $u["uid"]);
		echo "\"  target=\"_blank\">";
		echo $u["uid"];
		echo "</a></td>\r\n          <td>";
		echo $u["username"];
		echo "</td>\r\n          <td>";
		echo $u["contact"];
		echo "</td>\r\n          <td>";
		echo $u["qq"];
		echo "</td>\r\n          <td><a href=\"";
		echo url("admin/user.advertiser_list?searchtype=serviceid&search=" . $u["uid"]);
		echo "\">";
		echo dr("admin/user.get_sum_service", $u["uid"]);
		echo "个</a></td>\r\n          <td>";
		$y = dr("admin/stats.get_performance", $u["uid"], $get_timerange["30day"], "commercial");
		echo abs($y["pay"]);
		echo "            元</td>\r\n          <td class=\"status\">";

		switch ($u["status"]) {
		case 0:
			echo "<span class=\"notification error_bg\">待审</span>";
			break;

		case 1:
			echo "<span class=\"notification error_bg\">邮件激活</span>";
			break;

		case 2:
			echo "<span class=\"notification info_bg\">活动</span>";
			break;

		case 3:
			echo "<span class=\"notification error_bg\">拒绝</span>";
			break;

		case 4:
			echo "<span class=\"notification error_bg\">锁定</span>";
		}

		echo "</td>\r\n          <td><a href=\"";
		echo url("admin/user.s_performance?uid=" . $u["uid"]);
		echo "\" target=\"_blank\">我的业绩</a> | <a href=\"#\"  id=\"uld_lock\" uid=\"";
		echo $u["uid"];
		echo "\">锁定</a> | <a href=\"#\"  id=\"uld_del\" uid=\"";
		echo $u["uid"];
		echo "\">删除</a> | <a href=\"";
		echo url("admin/user.edit?uid=" . $u["uid"]);
		echo "\">编辑</a></td>\r\n        </tr>\r\n        ";
	}

	echo "      </tbody>\r\n    </table>\r\n    ";
}

echo "    <div class=\"row\">\r\n      ";
echo $page->echoPage();
echo "    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n</div>\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/lib/editable/editable.js\"></script> \r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\nvar a_url={\r\n\te_money:\"";
echo url("admin/user.update_money");
echo "\",\r\n\te_deduction:\"";
echo url("admin/user.update_deduction");
echo "\",\r\n\te_group:\"";
echo url("admin/user.update_group");
echo "\",\r\n\tdel:\"";
echo url("admin/user.del");
echo "\",\r\n\tunlock:\"";
echo url("admin/user.unlock");
echo "\",\r\n\tlock:\"";
echo url("admin/user.lock");
echo "\",\r\n};\r\n\r\nvar e_deduction_columns = [ {type:\"hidden\", name:\"uid\"}, \r\n\t\t\t\t";

foreach (explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
	$ek[] = "\"" . $t . "\"";
	echo ",{title:\"" . strtoupper($t) . "\",type:\"text\", name:\"" . $t . "\",number:true,maxlength:3,up:true}";
}

echo "];\r\nvar deduction_attr =[";
echo implode(",", (array) $ek);
echo "];\t\r\n\r\nvar e_group_columns\t= [\r\n\t\t \t {type:\"hidden\", name:\"uid\"},\r\n \t\t     {type:\"select\", name:\"groupid\",option:[\r\n\t\t\t   ";

foreach ((array) $group as $g ) {
	$gr[] = " {value: " . $g["groupid"] . ", text: '" . $g["groupname"] . "'}";
}

echo implode(",", (array) $gr);
echo "              ]},        \r\n             ];\r\n </script> \r\n<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/users.js\"></script>\r\n";
TPL::display("footer");

?>
