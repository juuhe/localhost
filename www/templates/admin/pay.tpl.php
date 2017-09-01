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
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid mt60\" >\r\n    <h3 class=\"heading tab_heading\">财务结算 <span class=\"h3span\"> <a href=\"#\" id=\"do_excel\" style=\" width:110px; text-align:left; padding-left:10px\"><i class=\"n_excel\"></i> 导出Excel</a></span></h3>\r\n    <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/pay.get_list");
echo "\" class=\"tab-btn  list ";

if ($status == "") {
	echo "tab-state-active";
}

echo "\">全部信息</a> <a href=\"";
echo url("admin/pay.get_list?status=0");
echo "\" class=\"tab-btn notpay ";

if ($status == "0") {
	echo "tab-state-active";
}

echo "\">等待支付</a> <a href=\"";
echo url("admin/pay.get_list?status=1");
echo "\" class=\"tab-btn unpay ";

if ($status == "1") {
	echo "tab-state-active";
}

echo "\"> 已支付</a> </div>\r\n    </div>\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"span12\">\r\n        <div class=\"alert-info tip-text\" >\r\n          <p> 未支付信息：\r\n            ";
$psum = 0;

foreach ((array) $notpay as $np ) {
	foreach ($GLOBALS["c_bank"] as $banks => $val ) {
		if ($np["bankname"] == $val[0]) {
			$bankname = $banks;
		}
	}

	$psum += $np["pay"];
	echo "<a href=\"" . url("admin/pay.get_list?status=0&bank=" . $np["bankname"]) . "\"><span style=\"padding-right:10px;\">[" . $bankname . "]：￥" . sprintf("%.2f", $np["pay"]) . "</span></a>";
}

echo "            <br>\r\n            合计：￥";
echo sprintf("%.2f", $psum);
echo " </p>\r\n        </div>\r\n      </div>\r\n      <div class=\"tb_sts\"><a href=\"javascript:;\"  class=\"tab-btn add add_pay\">手动结算</a> <a href=\"";
echo url("admin/pay.get_list?type=sumpay");
echo "\" class=\"tab-btn expired ";

if ($status == "6") {
	echo "tb_sts-active";
}

echo "\">支付汇总</a> </div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\"  >\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"pay\" >结算</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n          </div>\r\n        </div>\r\n        <div class=\"span6\" >\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\">\r\n            <form method=\"post\">\r\n              搜索：\r\n              <input type=\"text\" class=\"input_text \" name=\"search\" value=\"";
echo $search;
echo "\" />\r\n              <select name=\"searchtype\">\r\n                <option value=\"username\" ";

if ($searchtype == "username") {
	echo "selected";
}

echo ">站长名称</option>\r\n                <option value=\"uid\" ";

if ($searchtype == "uid") {
	echo "selected";
}

echo ">站长ID</option>\r\n                <option value=\"bankaccount\" ";

if ($searchtype == "bankaccount") {
	echo "selected";
}

echo ">收款帐号</option>\r\n                <option value=\"bankname\" ";

if ($searchtype == "bankname") {
	echo "selected";
}

echo ">收款人</option>\r\n              </select>\r\n              <input name=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n            </form>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      ";

if ($type == "sumpay") {
	echo "      <table id=\"dt_inbox\" class=\"dataTable table-bordered \">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th>结算日期</th>\r\n            <th>汇总</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

	foreach ((array) $pay as $p ) {
		$dbs = dr("admin/pay.get_date_bankname_sumpay", $p["addtime"]);
		echo "          <tr class=\"unread odd\"  >\r\n            <td> ";
		echo $p["addtime"];
		echo " </td>\r\n            <td >";
		$psum = 0;
		$bankname = "";

		foreach ((array) $dbs as $sp ) {
			foreach ($GLOBALS["c_bank"] as $banks => $val ) {
				if ($sp["bankname"] == $val[0]) {
					$bankname = $banks;
				}
			}

			$psum += $sp["pay"];
			echo "<span style=\"padding-right:10px;\">" . $bankname . "：￥" . sprintf("%.2f", $sp["pay"]) . "</span><br>";
		}

		echo " \r\n             ";

		if (1 < count($dbs)) {
			echo "<span style=\"color:#F00\">合计：" . sprintf("%.2f", $psum) . "</span>";
		}

		echo " \r\n            </td>\r\n          </tr>\r\n         \r\n          ";
	}

	echo "        </tbody>\r\n      </table>\r\n      ";
}
else {
	echo "      <table id=\"dt_inbox\" class=\"dataTable table-bordered \">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\"></th>\r\n            <th>&nbsp;</th>\r\n            <th>站长名称</th>\r\n            <th>款项</th>\r\n            <th>日期</th>\r\n            <th>佣金</th>\r\n            <th>下线提成</th>\r\n            <th>劳务税</th>\r\n            <th>手续费</th>\r\n            <th>实付费用</th>\r\n            <th>操作人</th>\r\n            <th>操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

	foreach ((array) $pay as $p ) {
		echo "          <tr class=\"unread odd\"  >\r\n            <td><input type=\"checkbox\" name=\"id\" id=\"pay_";
		echo $p["id"];
		echo "\" value=\"";
		echo $p["id"];
		echo "\"></td>\r\n            <td><img src=\"";
		echo SRC_TPL_DIR;
		echo "/images/bank/";
		echo $p["bankname"];
		echo ".png\" width=\"16\" height=\"16\" /></td>\r\n            <td><a  href=\"#\" class=\"editable_a p_info\"  pid=\"";
		echo $p["id"];
		echo "\" isshow=1>";
		echo $p["username"] ? $p["username"] : "会员已删除";
		echo "</a></td>\r\n            <td >";

		if ($p["clearingtype"] == "daymoney") {
			$ct = "日款";
		}

		if ($p["clearingtype"] == "weekmoney") {
			$ct = "周款";
		}

		if ($p["clearingtype"] == "monthmoney") {
			$ct = "月款";
		}

		echo $ct;
		echo "</td>\r\n            <td >";
		echo $p["addtime"];
		echo "</td>\r\n            <td>";
		echo abs($p["money"]);
		echo "</td>\r\n            <td>";
		echo abs($p["xmoney"]);
		echo "</td>\r\n            <td>";
		echo $p["tax"];
		echo "</td>\r\n            <td>";
		echo $p["charges"];
		echo "</td>\r\n            <td>";
		echo abs($p["pay"]);
		echo "</td>\r\n            <td>";
		echo $p["clearingadmin"];
		echo "</td>\r\n            <td classid='";
		echo $p["id"];
		echo "' class=\"status\">";

		if ($p["status"] == "0") {
			echo "              <a  href=\"#\" class=\"editable_a p_info\"  pid=\"";
			echo $p["id"];
			echo "\" isshow=1>未支付</a>\r\n              ";
		}
		else {
			echo "已支付";
		}

		echo "</td>\r\n          </tr>\r\n          <tr class=\"u_info\" id=\"u_info_";
		echo $p["id"];
		echo "\" style=\"display:none\">\r\n            <td colspan=\"104\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"tab_info\">\r\n                <tr>\r\n                  <td height=\"30\"><strong>付款信息</strong> <span style=\"padding-left:20px\"><a href=\"";
		echo url("admin/pay.get_list?searchtype=username&search=" . $p["username"]);
		echo "\" >查看他的付款记录</a></span></td>\r\n                </tr>\r\n                <tr>\r\n                  <td bgcolor=\"#FFFFFF\"><table width=\"96%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:15px; margin-bottom:15px\">\r\n                      <tr>\r\n                        <td width=\"90\">银行信息：</td>\r\n                        <td>";

		foreach ($GLOBALS["c_bank"] as $banks => $val ) {
			if ($p["bankname"] == $val[0]) {
				echo $banks . " " . $p["bankbranch"];
			}
		}

		echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>收款姓名：</td>\r\n                        <td>";
		echo $p["accountname"];
		echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>收款帐号：</td>\r\n                        <td>";
		echo $p["bankaccount"];
		echo "</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>实付金额：</td>\r\n                        <td>";
		echo $p["pay"];
		echo "</td>\r\n                      </tr>\r\n                      ";

		if ($p["status"] == "0") {
			echo "                      <tr>\r\n                        <td height=\"5\"></td>\r\n                        <td></td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td>&nbsp;</td>\r\n                        <td><button type=\"button\"  class=\"btn btn-51a351\" onclick=\"javascript:uld('one','";
			echo $p["id"];
			echo "','";
			echo $p["pay"];
			echo "','";
			echo $p["username"];
			echo "')\">已完成转帐</button></td>\r\n                      </tr>\r\n                      ";
		}
		else {
			echo "                      <tr>\r\n                        <td>支付时间：</td>\r\n                        <td>";
			echo $p["paytime"];
			echo "</td>\r\n                      </tr>\r\n                      ";
		}

		echo "                    </table></td>\r\n                </tr>\r\n              </table></td>\r\n          </tr>\r\n          ";
	}

	echo "        </tbody>\r\n      </table>\r\n        ";
}

echo "      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n<div id=\"add_pay_form\" style=\"display:none\">\r\n  <form action=\"";
echo url("admin/pay.add_pay");
echo "\" name=\"fclearing\" id=\"fclearing\" method=\"post\">\r\n    <div class=\"txt-fld\">\r\n      <label for=\"\" class=\"tit\">类型</label>\r\n      <input type=\"radio\" name=\"alone\" value=\"1\"  checked=\"checked\">\r\n      结算所有会员\r\n      <input type=\"radio\" name=\"alone\" value=\"0\"  >\r\n      结算单个会员 </div>\r\n    <div class=\"txt-fld\"   id=\"to_username\" style=\"display:none\">\r\n      <label for=\"\"  class=\"tit\">用户名</label>\r\n      <input id=\"pay_add_username_ajax\" name=\"username\" type=\"text\">\r\n    </div>\r\n    <div class=\"txt-fld\">\r\n      <label for=\"\"  class=\"tit\">结算款项</label>\r\n      <input name=\"clearingType[]\" type=\"checkbox\" value=\"day\"  >\r\n      日款&nbsp;&nbsp;\r\n      <input name=\"clearingType[]\" type=\"checkbox\" value=\"week\"  />\r\n      周款&nbsp;&nbsp;\r\n      <input name=\"clearingType[]\" type=\"checkbox\" value=\"month\"  />\r\n      月款 </div>\r\n    <div class=\"btn-fld\">\r\n      <button type=\"button\"  id=\"post_pay_but\">提交 »</button>\r\n    </div>\r\n  </form>\r\n</div>\r\n\r\n\r\n<div id=\"do_excel_html\" style=\"display:none\">\r\n  <form action=\"";
echo url("admin/pay.down_execl");
echo "\" method=\"post\" id=\"down_execl_form\">\r\n    <div class=\"txt-fld\">\r\n      <label for=\"\" class=\"tit\">支付状态</label>\r\n      <input type=\"radio\" name=\"export_status\" value=\"1\"  checked=\"checked\">\r\n      未支付\r\n      <input type=\"radio\" name=\"export_status\" value=\"2\" >\r\n      已支付\r\n      <input type=\"radio\" name=\"export_status\" value=\"3\" >\r\n       全部\r\n      </div>\r\n    <div class=\"txt-fld\">\r\n      <label for=\"\"  class=\"tit\">结算时间</label>\r\n    <input type=\"radio\" name=\"export_type\" value=\"1\"  checked=\"checked\">指定： <select name=\"export_date\">\r\n             ";

foreach ((array) $pay_date as $pd ) {
	echo "            <option value=\"";
	echo $pd["addtime"];
	echo "\">";
	echo $pd["addtime"];
	echo "</option> \r\n            ";
}

echo "            <option value=\"0\">导出所有</option>\r\n          </select><input type=\"radio\" name=\"export_type\" value=\"2\" >全部\r\n    </div>\r\n   \r\n    \r\n    <div class=\"btn-fld\">\r\n      <button type=\"submit\"  id=\"post_do_excel_but\">导出 »</button>\r\n    </div>\r\n  </form>\r\n</div>\r\n\r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\nvar send_email=0;\r\n";

if (in_array("pay", explode(",", $GLOBALS["C_ZYIIS"]["tomail"]))) {
	echo "  \r\n\t send_email=1;\r\n";
}

echo "var a_url={\r\n\tremote_user:\"";
echo url("admin/user.remote_user");
echo "\",\r\n\tpost_payment:\"";
echo url("admin/pay.post_payment");
echo "\",\r\n\tsend_email:\"";
echo url("admin/pay.send_email?id=");
echo "\",\r\n\tdel:\"";
echo url("admin/pay.del");
echo "\",\r\n\t \r\n};\r\n\r\n \r\n </script> \r\n<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/pay.js\"></script>\r\n";
TPL::display("footer");

?>
