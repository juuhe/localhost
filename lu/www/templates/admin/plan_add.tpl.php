<?php

if (!defined("IN_ZYADS")) {
	exit();
}

TPL::display("header");
echo "<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/plan.js\"></script>\r\n<link rel=\"stylesheet\" href=\"";
echo SRC_TPL_DIR;
echo "/css/rating.css\" media=\"all\" type=\"text/css\" />\r\n<div class=\"alert success\" ";

if (!$_SESSION["succ"]) {
	echo "style=\"display:none\"";
}

echo ">\r\n  <!-- <a class=\"close\" href=\"javascript:;\">×</a>-->\r\n  <strong>保存成功.</strong> </div>\r\n<div id=\"sidebar\">\r\n  ";
TPL::display("sidebar");
echo "</div>\r\n<div id='valid_tip' style=''>正在验证中，请稍等...</div>\r\n<div id=\"main-content\">\r\n  \r\n  <div class=\"row-fluid\">\r\n    <h3 class=\"heading tab_heading\"> ";

if (RUN_ACTION == "add") {
	echo "新建";
}
else {
	echo "编辑";
}

echo "计划</h3>\r\n    <div class=\"tab\">\r\n      <div class=\"tab-header-right\"><span style=\"float: left; margin-top:5px\"><a href=\"javascript:;\"><font color=\"#ae432e\" id=\"tab_nf\">选项模式</font></a></span> <a href=\"#\" class=\"tab-btn se tab-state-active\" id=\"p_cg\">常规选项</a> <a href=\"#\" class=\"tab-btn se\" id=\"p_ys\">出价与预算</a> <a href=\"#\" class=\"tab-btn  se\" id=\"p_xz\">投放限制</a> <a href=\"#\" class=\"tab-btn  se\" id=\"p_dx\">定向选项</a> <a href=\"#\" class=\"tab-btn  se\" id=\"p_qt\">其它选项</a> </div>\r\n    </div>\r\n    <div class=\"row-fluid\">\r\n      <div class=\"span8\">\r\n\t  ";
if (($plan["status"] == 0) && $plan && ($plan["gradeprice"] != "2")) {
	echo "\t  <div class=\"alert alert-error\" style=\"margin-top:10px\">\r\n\t  激活需要填写站长的佣金\r\n  \t\t </div>  ";
}

echo " \t  ";
if (($plan["status"] == 0) && $plan && ($plan["gradeprice"] == "2")) {
	echo "        \r\n\t  <div class=\"alert success\" style=\"margin-top:10px\">\r\n\t  可以提交\r\n  \t\t </div>\r\n \t  ";
}

echo "      \r\n  \r\n        <form class=\"form-horizontal\" action=\"\r\n\t\t ";

if (RUN_ACTION == "add") {
	echo url("admin/plan.add_post");
}
else {
	echo url("admin/plan.update_post");
}

echo "\"  method=\"post\" id=\"form_b\" name=\"form_b\" enctype=\"multipart/form-data\">\r\n          <input name=\"planid\" id=\"planid\" type=\"hidden\" value=\"";
echo $plan["planid"];
echo "\" />\r\n          <div class=\"p_tab p_cg\">\r\n            <div class=\"control-group formSep\">\r\n              <label class=\"control-label\">投放广告商</label>\r\n              <div class=\"controls\">\r\n                <select name=\"uid\" id=\"uid\"  ";

if (RUN_ACTION == "edit") {
	echo "disabled='disabled'";
}

echo ">\r\n                  <option value=\"\">请选择一个广告商</option>\r\n                  ";

foreach ((array) $adv as $u ) {
	echo "                  <option value=\"";
	echo $u["uid"];
	echo "\" ";
	if (($plan["uid"] == $u["uid"]) || ((int) $_GET["uid"] == $u["uid"])) {
		echo "selected";
	}

	echo ">";
	echo $u["username"];
	echo " ￥";
	echo 0 < $u["money"] ? round($u["money"], 2) : 0;
	echo " </option>\r\n                  ";
}

echo "                </select> <span>帐号余额太少的广告商不显示、不能发布计划</span>\r\n                <span id=\"u_text\"></span></div>\r\n            </div>\r\n            <div class=\"control-group formSep\">\r\n              <label class=\"control-label\">计划名称</label>\r\n              <div class=\"controls\">\r\n                <input name=\"planname\" type=\"text\" class=\"input_text span30\" id=\"planname\"  value=\"";
echo $plan["planname"];
echo "\">\r\n              </div>\r\n            </div>\r\n            <div class=\"control-group formSep st\">\r\n              <label class=\"control-label\">计费模式</label>\r\n              <div class=\"controls\">\r\n                <select name=\"plantype\" id=\"plantype\"  ";

if (RUN_ACTION == "edit") {
	echo "disabled='disabled'";
}

echo "  >\r\n                  ";

foreach (explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
	echo "                  <option value=\"";
	echo $t;
	echo "\" ";

	if ($plan["plantype"] == $t) {
		echo "selected";
	}

	echo ">&nbsp;";
	echo strtoupper($t);
	echo "&nbsp;</option>\r\n                  ";
}

echo "                </select>\r\n              </div>\r\n            </div>\r\n            <div class=\"control-group formSep st\">\r\n              <label class=\"control-label\">网站审核方式</label>\r\n              <div class=\"controls\">\r\n                <input name=\"audit\" type=\"radio\" value=\"n\" ";
if (($plan["audit"] == "n") || !$plan) {
	echo "checked";
}

echo " />\r\n                无需申请\r\n                <input type=\"radio\" name=\"audit\" value=\"y\" ";

if ($plan["audit"] == "y") {
	echo "checked";
}

echo " />\r\n                人工审核 <span>需要站长申请才能投放</span> </div>\r\n            </div>\r\n\t\t\t\r\n\t\t\t\r\n\t\t\t   <div class=\"control-group formSep st\">\r\n              <label class=\"control-label\">投放设备</label>\r\n              <div class=\"controls\">\r\n                <input type=\"radio\" value=\"all\" name=\"acl[mobile][isacl]\" ";
if (($plan["checkplan"]["mobile"]["isacl"] == "all") || !$plan) {
	echo " checked";
}

echo "/>\r\n                不限\r\n                <input type=\"radio\" value=\"acls\" name=\"acl[mobile][isacl]\" id=\"acl4isacl\"   ";

if ($plan["checkplan"]["mobile"]["isacl"] == "acls") {
	echo " checked";
}

echo "/>\r\n                指定终端 <span id=\"mobile_data_error\" class=\"frc_error\"></span>\r\n                <div style=\"margin-top:20px;";
if (($plan["checkplan"]["mobile"]["isacl"] == "all") || !$plan) {
	echo "display:none";
}

echo "\">\r\n                  <table  style=\"width:450px\" class=\"class_tb\" >\r\n                    <tr>\r\n                      ";
$mobile = array("pc" => "桌面电脑", "ios" => "苹果IOS", "android" => "Android", "windows phone" => "微软WP");
$i = 1;

foreach ($mobile as $k => $m ) {
	echo "<td ><label><input type=\"checkbox\" name=\"acl[mobile][data][]\" id=\"aclsitetype\" value=\"" . $k . "\"";
	echo "                      ";

	if (in_array($k, (array) $plan["checkplan"]["mobile"]["data"])) {
		echo " checked";
	}

	echo ">" . $m . "</label></td>";

	if (($i % 6) == 0) {
		echo "</tr>";
	}

	$i++;
}

echo "                    </tr>\r\n                  </table>\r\n                </div>\r\n              </div>\r\n            </div>\r\n\t\t\t\r\n\t\t\t\r\n            <div class=\"control-group formSep htmltemplate\">\r\n              <label class=\"control-label\">结算周期</label>\r\n              <div class=\"controls\">\r\n                <select name=\"clearing\">\r\n                  <option value=\"week\" ";
if (($plan["clearing"] == "week") || !$plan) {
	echo "selected";
}

echo ">周结项目</option>\r\n                  <option value=\"day\" ";

if ($plan["clearing"] == "day") {
	echo "selected";
}

echo ">日结项目</option>\r\n                  <option value=\"month\" ";

if ($plan["clearing"] == "month") {
	echo "selected";
}

echo ">月结项目</option>\r\n                </select>\r\n              </div>\r\n            </div>\r\n\t\t\t\r\n\t\t\t\r\n\t\t\t <div class=\"control-group formSep htmltemplate\">\r\n              <label class=\"control-label\">计划活动分类</label>\r\n              <div class=\"controls\">\r\n                <select name=\"classid\">\r\n                   \r\n\t\t\t\t  <option  value=\"\">选择分类</option>\r\n\t\t\t\t  ";

foreach ($plan_class as $pc ) {
	echo "\t\t\t\t  <option value=\"";
	echo $pc["classid"];
	echo "\" ";
	if (($plan["classid"] == $pc["classid"]) && $plan) {
		echo "selected";
	}

	echo ">";
	echo $pc["classname"];
	echo "</option>\r\n\t\t\t\t  ";
}

echo "\t\t\t \r\n                </select>\r\n              </div>\r\n            </div>\r\n\t\t\t\r\n\t\t\t\r\n\t\t\t\r\n\t\t\t \r\n\t\t\t<div class=\"control-group formSep keys\" ";
if (!in_array($plan["plantype"], array("cpa", "cps", "cpas")) || !$plan) {
	echo "style=\"display:none\"";
}

echo " >\r\n              <label class=\"control-label\">接口密钥 </label>\r\n              <div class=\"controls\">\r\n                <input name=\"pkey\" type=\"text\" class=\"input_text span30\" id=\"info\"  value=\"";
echo $plan["pkey"];
echo "\">  <span>商家与联盟对接数据时的密钥</span> \r\n              </div>\r\n            </div>\r\n            \r\n            \t<div class=\"control-group formSep keys\">\r\n\t\t\t\t  <label class=\"control-label\">数据返回 </label>\r\n\t\t\t\t  <div class=\"controls\">\r\n\t\t\t\t\t<input name=\"datatime\" type=\"text\" class=\"input_text span30\"  value=\"";
echo $plan["datatime"];
echo "\">  <span>返回给站长的时间，比如 隔天 月底  实时.</span> \r\n\t\t\t\t  </div>\r\n\t\t\t\t</div>\r\n\t\t\t\t\r\n\t\t\t\t<div class=\"control-group formSep keys\">\r\n\t\t\t\t  <label class=\"control-label\">cookie有效期 </label>\r\n\t\t\t\t  <div class=\"controls\">\r\n\t\t\t\t\t<input name=\"cookie\" type=\"text\" class=\"input_text span30\"  value=\"";
echo $plan["cookie"];
echo "\"> 天 <span>在cookie有效期内联盟分成或是行为有效</span> \r\n\t\t\t\t  </div>\r\n\t\t\t\t</div>\r\n                \r\n\t\t\t\r\n\t\t\t<div class=\"div_cps\" ";

if (!in_array($plan["plantype"], array("cps", "cpas"))) {
	echo "style='display:none'";
}

echo " >\r\n\t\t\t\r\n\t\t\t\r\n\t\t\t\t\r\n\t\t\t\t\r\n\t\t\t\t<div class=\"control-group formSep\">\r\n\t\t\t\t  <label class=\"control-label\">自定义链接 </label>\r\n\t\t\t\t  <div class=\"controls\"> \r\n\t\t\t\t\t<input name=\"linkon\" type=\"radio\" value=\"y\" ";
if (($plan["linkon"] == "y") || !$plan) {
	echo "checked";
}

echo " />\r\n\t\t\t\t\t开启\r\n\t\t\t\t\t<input type=\"radio\" name=\"linkon\" value=\"n\" ";

if ($plan["linkon"] == "n") {
	echo "checked";
}

echo " />关闭    <span>站长可以随意自定义商品地址.</span> \r\n\t\t\t\t  </div>\r\n\t\t\t\t</div>\r\n\t\t\t\t\r\n\t\t\t\t\r\n\t\t\t\t<div class=\"control-group formSep\" ";

if ($plan["linkon"] == "n") {
	echo "style=\"display:none\"";
}

echo " id=\"linkurl_s\">\r\n\t\t\t\t  <label class=\"control-label\">自定义链接主域 </label>\r\n\t\t\t\t  <div class=\"controls\"> \r\n\t\t\t\t <input name=\"linkurl\" type=\"text\" class=\"input_text span30\"   value=\"";
echo $plan["linkurl"];
echo "\">  <span>如：www.taobao.com可自定义到这个域名下任何链接</span> \r\n\t\t\t\t  </div>\r\n\t\t\t\t</div>\r\n\t\t\t<!-- cps_div-->\r\n\t\t\t</div>\r\n\t\t\t\r\n          <!--  <div class=\"control-group formSep\">\r\n              <label class=\"control-label\">对应合同 </label>\r\n              <div class=\"controls\">\r\n                <input name=\"info\" type=\"text\" class=\"input_text span30\" id=\"info\"  value=\"";
echo $plan["info"];
echo "\">\r\n              </div>\r\n            </div>-->\r\n          </div>\r\n          <div class=\"p_tab p_ys\">\r\n            <h3 class=\"heading\">出价与预算</h3>\r\n            <div class=\"control-group formSep\" id=\"a_price_v\" ";
if ((($plan["gradeprice"] == "1") || ($plan["gradeprice"] == "2")) && ($plan["plantype"] == "cps")) {
	echo "style='display:none'";
}

echo ">\r\n              <label class=\"control-label\" id=\"label_price_text1\">广告商单价 </label>\r\n              <div class=\"controls\">\r\n                <input name=\"priceadv\" type=\"text\" class=\"input_text span30\" id=\"priceadv\"  value=\"";
echo $plan["priceadv"] ? abs($plan["priceadv"]) : "";
echo "\"> <span id=\"price_text3\">";
echo $plan["plantype"] == "cps" ? " %" : " 元";
echo "</span>\r\n              </div>\r\n            </div>\r\n            <div class=\"control-group formSep\">\r\n              <label class=\"control-label\" id=\"label_price_text2\">站长单价分级 </label>\r\n              <div class=\"controls\">\r\n                <input type=\"radio\" name=\"gradeprice\" value=\"0\"  ";
if (($plan["gradeprice"] == "0") || !$plan) {
	echo "checked";
}

echo "/>\r\n                <i id=\"price_text1\">";
echo $plan["plantype"] == "cps" ? "固定分成" : "不分等级";
echo "</i>\r\n                <input type=\"radio\" name=\"gradeprice\" id=\"gradeprice\" value=\"1\"   ";

if ($plan["gradeprice"] == "1") {
	echo "checked";
}

echo "/>\r\n                <i id=\"price_text2\">";
echo $plan["plantype"] == "cps" ? "按类目分成" : "分网站等级";
echo "</i>  \r\n\t\t\t\t\r\n\t\t\t\t\r\n                <i id=\"price_texts\" style=\" ";
echo $plan["plantype"] != "cps" ? "display:none" : "";
echo "\"><input type=\"radio\" name=\"gradeprice\" id=\"gradeprice\" value=\"2\"   ";

if ($plan["gradeprice"] == "2") {
	echo "checked";
}

echo "/>接口自定义分成比例</i>\r\n\t\t\t\t\r\n\t\t\t\t\r\n\t\t\t\t </div>\r\n            </div>\r\n            <div class=\"control-group formSep\" id=\"a_price\" ";
if (($plan["gradeprice"] == "1") || ($plan["gradeprice"] == "2")) {
	echo "style='display:none'";
}

echo ">\r\n              <label class=\"control-label\" id=\"label_price_text3\">站长单价 </label>\r\n              <div class=\"controls\">\r\n                <input name=\"price\" type=\"text\" class=\"input_text span30\" id=\"price\"  value=\"";
echo $plan["price"] ? abs($plan["price"]) : "";
echo "\"><span id=\"price_text4\">";
echo $plan["plantype"] == "cps" ? " %" : " 元";
echo "</span>\r\n              </div>\r\n            </div>\r\n            \r\n            \r\n            \r\n            \r\n            \r\n            <div class=\"control-group formSep\"  id=\"s_price\" ";
if (($plan["gradeprice"] == "0") || !$plan || ($plan["plantype"] == "cps")) {
	echo "style='display:none'";
}

echo ">\r\n              <label class=\"control-label\" id=\"label_price_text4\">站长分级单价 </label>\r\n              <div class=\"controls\">\r\n\t\t\t  \r\n\t\t\t    ";

for ($i = 0; $i < SITE_GRADE; $i++) {
	$sp = (array) unserialize($plan["siteprice"]);
	echo "                <div style=\"margin-top:20px\"> ";
	echo $i;
	echo "星级\r\n                  <input name=\"siteprice[";
	echo $i;
	echo "]\" type=\"text\" class=\"input_text span30\" id=\"s";
	echo $i;
	echo "price\"  value=\"";
	echo $sp[$i];
	echo "\">\r\n                </div>\r\n                ";
}

echo "              </div>\r\n            </div>\r\n\t\t\t\r\n\t\t\t\r\n\t\t\t<div class=\"control-group formSep\"  id=\"s_price_f_cps\" ";
if (($plan["plantype"] != "cps") || ($plan["gradeprice"] == "0") || ($plan["gradeprice"] == "2")) {
	echo "style='display:none'";
}

echo " >\r\n              <label class=\"control-label\" id=\"label_price_text5\">按类提成 </label>\r\n              <div class=\"controls\">\r\n                <div style=\"position: relative;\">  \r\n                  <table style=\"width:100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"  class=\"c_f_i\">\r\n                    <tr>\r\n                      <td><table  style=\"width:100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"  class=\"c_f_f\">\r\n                        <tr>\r\n                          <td>分成标识</td>\r\n                          <td>标识说明</td>\r\n                          <td>站长佣金（%）</td>\r\n                          <td>扣除广告商（%）</td>\r\n                          <td>备注</td>\r\n                        </tr>\r\n\t\t\t\t\t\t";
$cp_num = 1;

if ($plan) {
	$cp = (array) unserialize($plan["classprice"]);
	$cp_num = count($cp["classprice_mark"]);
}

for ($i = 0; $i < $cp_num; $i++) {
	echo "\t\t\t\t\t\t\r\n                        <tr>\r\n                          <td><div><input name=\"classprice_mark[]\" type=\"text\" class=\"input_text span30 \" style=\"width:90px\" value=\"";
	echo $cp["classprice_mark"][$i];
	echo "\" /></div></td>\r\n                          <td><div><input name=\"classprice_mark_info[]\" type=\"text\" class=\"input_text span30 \" style=\"width:120px\" value=\"";
	echo $cp["classprice_mark_info"][$i];
	echo "\" /></div></td>\r\n                          <td><div><input name=\"classprice_aff[]\" type=\"text\" class=\"input_text span30 \" style=\"width:50px\" value=\"";
	echo $cp["classprice_aff"][$i];
	echo "\" />\r\n%</div></td>\r\n                          <td><div><input name=\"classprice_adv[]\" type=\"text\" class=\"input_text span30 \" style=\"width:50px\" value=\"";
	echo $cp["classprice_adv"][$i];
	echo "\" />\r\n%</div></td>\r\n                          <td><div><input name=\"classprice_memo[]\" type=\"text\" class=\"input_text span30 \" style=\"width:120px\" value=\"";
	echo $cp["classprice_memo"][$i];
	echo "\" />\r\n\t\t\t\t\t\t ";

	if (1 <= $i) {
		echo "<a href=\"javascript:;\" class=\"delbtn\"> 删</a> ";
	}

	echo "\t\t\t\t\t\t  </div></td>\r\n                        </tr>\r\n\t\t\t\t\t\t";
}

echo "\t\t\t\t\t\t\r\n                      </table></td>\r\n                      <td width=\"100\"><a href=\"javascript:;\" class=\"newbtn\">增加一行</a></td>\r\n                    </tr>\r\n                  </table>\r\n                </div>\r\n                \r\n              </div>\r\n            </div>\r\n\t\t\t\r\n\t\t\t  <div class=\"control-group formSep\" >\r\n              <label class=\"control-label\">移动设备出价 </label>\r\n              <div class=\"controls\">\r\n                <input name=\"mobile_price\" type=\"text\" class=\"input_text span30\" id=\"mobile_price\"  value=\"";
echo $plan["mobile_price"] ? abs($plan["mobile_price"]) : "1";
echo "\">   <span>倍 为电脑设备的几倍</span>\r\n              </div>\r\n            </div>\r\n\t\t\t\r\n\t\t\t\r\n            <div class=\"control-group formSep\">\r\n              <label class=\"control-label\">每日限额 </label>\r\n              <div class=\"controls\">\r\n                <input name=\"budget\" type=\"text\" class=\"input_text span30\" id=\"budget\"  value=\"";
echo $plan["budget"];
echo "\">\r\n                <span>达到每日预算限额时，广告就会在当天停止展示</span> </div>\r\n            </div>\r\n            <div class=\"control-group formSep\">\r\n              <label class=\"control-label\">价格说明 </label>\r\n              <div class=\"controls\">\r\n                <input name=\"priceinfo\" type=\"text\" class=\"input_text span30\" id=\"priceinfo\"  value=\"";
echo $plan["priceinfo"];
echo "\">\r\n                <span>广告计费简要说明</span> </div>\r\n            </div>\r\n            <div class=\"control-group formSep\">\r\n              <label class=\"control-label\">项目扣量 </label>\r\n              <div class=\"controls\">\r\n                <input name=\"deduction\" type=\"text\" class=\"input_text span30\" id=\"deduction\"  value=\"";
echo $plan["deduction"];
echo "\">\r\n                <span>项目扣量优先全局扣量，低于站长单独扣量，为空既按全局扣量</span> </div>\r\n            </div>\r\n          </div>\r\n          <div class=\"p_tab p_xz\">\r\n            <h3 class=\"heading\">投放限制</h3>\r\n            <div class=\"control-group formSep\">\r\n              <label class=\"control-label\">结束日期 </label>\r\n              <div class=\"controls\">\r\n                <input type=\"radio\" name=\"expire_date\" value=\"0000-00-00\"  ";
if (($plan["expire"] == "0000-00-00") || !$plan) {
	echo "checked";
}

echo "/>\r\n                没有结束日期 <br>\r\n                <input name=\"expire_date\" type=\"radio\" value=\"no\" ";
if (($plan["expire"] != "0000-00-00") && $plan) {
	echo "checked";
}

echo "/>\r\n                <select name=\"expire_year\" id=\"expire_year\" ";
if (($plan["expire"] == "0000-00-00") || !$plan) {
	echo "disabled='disabled'";
}

echo ">\r\n                  ";
$expire = @explode("-", $plan["expire"]);

for ($i = date("Y"); $i < (date("Y") + 21); $i++) {
	echo "                  <option value=\"";
	echo $i;
	echo "\" ";
	if ((($i == date("Y") + 1) && !$plan) || ($expire[0] == $i)) {
		echo "selected";
	}

	echo ">";
	echo $i;
	echo "年</option>\r\n                  ";
}

echo "                </select>\r\n                <select name=\"expire_month\" id=\"expire_month\" ";
if (($plan["expire"] == "0000-00-00") || !$plan) {
	echo "disabled='disabled'";
}

echo ">\r\n                  ";

for ($i = 1; $i < 13; $i++) {
	echo "                  <option value=\"";
	echo $i;
	echo "\" ";
	if ((($i == date("n")) && !$plan) || ($expire[1] == $i)) {
		echo "selected";
	}

	echo ">";
	echo $i;
	echo "月</option>\r\n                  ";
}

echo "                </select>\r\n                <select name=\"expire_day\" id=\"expire_day\" ";
if (($plan["expire"] == "0000-00-00") || !$plan) {
	echo "disabled='disabled'";
}

echo ">\r\n                  ";

for ($i = 1; $i < 32; $i++) {
	echo "                  <option value=\"";
	echo $i;
	echo "\" ";
	if ((($i == date("j", TIMES)) && !$plan) || ($expire[2] == $i)) {
		echo "selected";
	}

	echo ">";
	echo $i;
	echo "日</option>\r\n                  ";
}

echo "                </select>\r\n              </div>\r\n            </div>\r\n            <div class=\"control-group formSep\">\r\n              <label class=\"control-label\">站长限制 </label>\r\n              <div class=\"controls\">\r\n                <input type=\"radio\" name=\"restrictions\" id=\"restrictions\" value=\"1\" ";
if (($plan["restrictions"] == "1") || !$plan) {
	echo "checked";
}

echo "/>\r\n                不限制\r\n                <input type=\"radio\" name=\"restrictions\" value=\"2\" ";

if ($plan["restrictions"] == "2") {
	echo "checked";
}

echo "/>\r\n                允许以下站长\r\n                <input type=\"radio\" name=\"restrictions\"  value=\"3\" ";

if ($plan["restrictions"] == "3") {
	echo "checked";
}

echo "/>\r\n                屏蔽以下站长 </div>\r\n            </div>\r\n            <div class=\"control-group formSep\" id=\"resuids\"  ";
if (($plan["restrictions"] == "1") || !$plan) {
	echo "style='display:none'";
}

echo ">\r\n              <label class=\"control-label\">站长限制ID </label>\r\n              <div class=\"controls\">\r\n                <textarea name=\"resuid\" id=\"resuid\"  class=\"input_text span30\" style=\"width:380px\">";
echo $plan["resuid"];
echo "</textarea>\r\n                <span>多ID限制格式“,”逗号隔开</span> </div>\r\n            </div>\r\n            <div class=\"control-group formSep\">\r\n              <label class=\"control-label\">网站限制 </label>\r\n              <div class=\"controls\">\r\n                <input type=\"radio\" name=\"sitelimit\" id=\"sitelimit\" value=\"1\" ";
if (($plan["sitelimit"] == "1") || !$plan) {
	echo "checked";
}

echo "/>\r\n                不限制\r\n                <input type=\"radio\" name=\"sitelimit\" value=\"2\" ";

if ($plan["sitelimit"] == "2") {
	echo "checked";
}

echo "/>\r\n                允许以下网站\r\n                <input type=\"radio\" name=\"sitelimit\"  value=\"3\" ";

if ($plan["sitelimit"] == "3") {
	echo "checked";
}

echo "/>\r\n                屏蔽以下网站 </div>\r\n            </div>\r\n            <div class=\"control-group formSep\" id=\"limitsiteids\"  ";
if (($plan["sitelimit"] == "1") || !$plan) {
	echo "style='display:none'";
}

echo ">\r\n              <label class=\"control-label\">限制网站ID </label>\r\n              <div class=\"controls\">\r\n                <textarea name=\"limitsiteid\" id=\"limitsiteid\"  class=\"input_text span30\" style=\"width:380px\">";
echo $plan["limitsiteid"];
echo "</textarea>\r\n              </div>\r\n            </div>\r\n          </div>\r\n          <div class=\"p_tab p_dx\">\r\n            <h3 class=\"heading\">定向设置</h3>\r\n            <div class=\"control-group formSep st\">\r\n              <label class=\"control-label\">投放地域</label>\r\n              <div class=\"controls\">\r\n                <input type=\"radio\" value=\"all\" name=\"acl[city][isacl]\" ";
if (($plan["checkplan"]["city"]["isacl"] == "all") || !$plan) {
	echo " checked";
}

echo "/>\r\n                不限\r\n                <input type=\"radio\" value=\"acls\" name=\"acl[city][isacl]\"  id=\"acl0isacl\"  ";

if ($plan["checkplan"]["city"]["isacl"] == "acls") {
	echo " checked";
}

echo "/>\r\n                选择地域 <span id=\"city_data_error\" class=\"frc_error\"></span>\r\n                <div style=\"margin-top:10px;";
if (($plan["checkplan"]["city"]["isacl"] == "all") || !$plan) {
	echo "display:none";
}

echo "\">\r\n                  <input id=\"radio\"  type=\"radio\"  value=\"==\" name=\"acl[city][comparison]\" ";
if (($plan["checkplan"]["city"]["comparison"] == "==") || ($plan["checkplan"]["city"]["comparison"] == "") || !$plan) {
	echo " checked";
}

echo "/>\r\n                  允许\r\n                  <input id=\"radio\"  type=\"radio\" value=\"!=\" name=\"acl[city][comparison]\" ";

if ($plan["checkplan"]["city"]["comparison"] == "!=") {
	echo " checked";
}

echo "/>\r\n                  拒绝 </div>\r\n                <div style=\"margin-top:10px; margin-left:-50px;";
if (($plan["checkplan"]["city"]["isacl"] == "all") || !$plan) {
	echo "display:none";
}

echo "\">\r\n                  <SCRIPT LANGUAGE=\"JavaScript\">\t\t\t\t  \r\n\t\t\t\t  var _province = '";
echo implode(",", (array) $plan["checkplan"]["city"]["province"]);
echo "';\r\n\t\t\t\t  var _city = '";
echo implode(",", (array) $plan["checkplan"]["city"]["data"]);
echo "';\r\n\t\t\t\t  var _c = acity();document.write(_c);\r\n\t\t\t\t  </SCRIPT>\r\n                </div>\r\n              </div>\r\n            </div>\r\n            <div class=\"control-group formSep st\">\r\n              <label class=\"control-label\">网站类型</label>\r\n              <div class=\"controls\">\r\n                <input type=\"radio\"  value=\"all\" name=\"acl[siteclass][isacl]\"  ";
if (($plan["checkplan"]["siteclass"]["isacl"] == "all") || !$plan) {
	echo " checked";
}

echo "/>\r\n                不限\r\n                <input type=\"radio\"  value=\"acls\" name=\"acl[siteclass][isacl]\" id=\"acl1isacl\"  ";

if ($plan["checkplan"]["siteclass"]["isacl"] == "acls") {
	echo " checked";
}

echo "/>\r\n                指定类目 <span id=\"class_data_error\" class=\"frc_error\"></span>\r\n                <div style=\"margin-top:10px;";
if (($plan["checkplan"]["siteclass"]["isacl"] == "all") || !$plan) {
	echo "display:none";
}

echo "\">\r\n                  <input  type=\"radio\"   value=\"==\" name=\"acl[siteclass][comparison]\" ";
if (($plan["checkplan"]["siteclass"]["comparison"] == "==") || ($plan["checkplan"]["siteclass"]["comparison"] == "") || !$plan) {
	echo " checked";
}

echo "/>\r\n                  允许\r\n                  <input  type=\"radio\" value=\"!=\" name=\"acl[siteclass][comparison]\" ";

if ($plan["checkplan"]["siteclass"]["comparison"] == "!=") {
	echo " checked";
}

echo "/>\r\n                  拒绝 </div>\r\n                <div style=\"margin-top:10px; ";
if (($plan["checkplan"]["siteclass"]["isacl"] == "all") || !$plan) {
	echo "display:none";
}

echo "\">\r\n                  <table width=\"100%\"  id=\"s1\"   class=\"class_tb\" >\r\n                    <tr>\r\n                      ";
$i = 1;

foreach ($site_class as $c ) {
	echo "<td><label><input type=\"checkbox\" name=\"acl[siteclass][data][]\"  value=\"" . $c["classid"] . "\"";
	echo "                      ";

	if (in_array($c["classid"], (array) $plan["checkplan"]["siteclass"]["data"])) {
		echo " checked";
	}

	echo " />" . $c["classname"] . "</label></td>";

	if (($i % 6) == 0) {
		echo "</tr>";
	}

	$i++;
}

echo "                    </tr>\r\n                  </table>\r\n                </div>\r\n              </div>\r\n            </div>\r\n            <div class=\"control-group formSep st\">\r\n              <label class=\"control-label\">周期日程</label>\r\n              <div class=\"controls\">\r\n                <input type=\"radio\" value=\"all\" name=\"acl[week][isacl]\"  ";
if (($plan["checkplan"]["week"]["isacl"] == "all") || !$plan) {
	echo " checked";
}

echo "/>\r\n                不限\r\n                <input type=\"radio\" value=\"acls\" name=\"acl[week][isacl]\" id=\"acl2isacl\"  ";

if ($plan["checkplan"]["week"]["isacl"] == "acls") {
	echo " checked";
}

echo "/>\r\n                设定周期日程 <span id=\"week_data_error\" class=\"frc_error\"></span>\r\n                <div style=\"margin-top:20px;";
if (($plan["checkplan"]["week"]["isacl"] == "all") || !$plan) {
	echo "display:none";
}

echo "\">\r\n                  <table width=\"100%\"  id=\"s1\"   class=\"week_tb\" >\r\n                    ";
$week = array(1 => "一", 2 => "二", 3 => "三", 4 => "四", 5 => "五", 6 => "六", 7 => "日");

foreach ($week as $i => $v ) {
	echo "                    <tr>\r\n                      <td width=\"80\" valign=\"top\" style=\"text-align: left;\"><label>\r\n                        <input type='checkbox'  name='acl[week][data][]' value='";
	echo $i;
	echo "' class=\"week\" ";
	if (in_array($i, (array) $plan["checkplan"]["week"]["data"]) && $plan) {
		echo " checked";
	}

	echo ">\r\n                        星期";
	echo $v;
	echo "</label></td>\r\n                      ";

	for ($s = 0; $s < 24; $s++) {
		echo "                      <td align=\"center\"  ><label>\r\n                        <input type='checkbox'  name='acl[week][";
		echo $i;
		echo "][data][]' value='";
		echo $s;
		echo "'  class=\"week_in\" ";
		if (in_array($s, (array) $plan["checkplan"]["week"][$i]["data"]) && $plan) {
			echo " checked";
		}

		echo ">\r\n                        <br>\r\n                        ";
		echo $s;
		echo "</label></td>\r\n                      ";
	}

	echo "                    </tr>\r\n                    ";
}

echo "                  </table>\r\n                </div>\r\n              </div>\r\n            </div>\r\n            \r\n         \r\n          </div>\r\n          <div class=\"p_tab p_qt\">\r\n            <h3 class=\"heading\">项目其它</h3>\r\n             <div class=\"control-group formSep st\">\r\n              <label class=\"control-label\">应用包大小</label>\r\n              <div class=\"controls\">\r\n                 <input name=\"size\" type=\"text\" class=\"input_text span30\" id=\"size\"  value=\"";
echo $plan["size"];
echo "\"> M <span>应用包或是APP包大小</span> </div>\r\n            </div>\r\n            <div class=\"control-group formSep st\">\r\n              <label class=\"control-label\">推荐</label>\r\n              <div class=\"controls\">\r\n                <input type=\"radio\" name=\"top\" value=\"0\" ";
if (($plan["top"] == "0") || !$plan) {
	echo "checked";
}

echo "/>\r\n                不推荐\r\n                <input type=\"radio\" name=\"top\" value=\"1\"  ";

if ($plan["top"] == "1") {
	echo "checked";
}

echo "/>\r\n                推荐 </div>\r\n            </div>\r\n            <div class=\"control-group formSep\">\r\n              <label class=\"control-label\">Logo </label>\r\n              <div class=\"controls\">\r\n                <input id=\"logo_imageurl\" class=\"input_text span30\" title=\"上传文件\" type=\"file\" name=\"logo_imageurl\">\r\n                <span>规格120x45</span> ";

if ($plan["logo"]) {
	echo "<img src=\"";
	echo $plan["logo"];
	echo "\"  style=\"120px; height:45px;\"   border=\"0\" align=\"absmiddle\">";
}

echo "</div>\r\n            </div>\r\n            <div class=\"control-group formSep\">\r\n              <label class=\"control-label\">项目介绍 </label>\r\n              <div class=\"controls\">\r\n                <textarea name=\"planinfo\" id=\"planinfo\" class=\"input_text span30\" style=\"width:320px;height:100px\">";
echo $plan["planinfo"];
echo "</textarea>\r\n              </div>\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group\" style=\"height:50px; margin-bottom:30px\">\r\n            <div class=\"controls\">\r\n              <button class=\"btn btn-gebo\" type=\"button\" id=\"f_button\" style=\"";

if ($plan) {
	echo "display:none";
}

echo "\">下一步</button>\r\n              <button class=\"btn bnt51a35\" type=\"submit\" id=\"f_submit\" style=\"";

if (!$plan) {
	echo "display:none";
}

echo "\">提交</button>\r\n            </div>\r\n          </div>\r\n        </form>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/form.js\"></script>\r\n<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/plan_add.js\"></script>\r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n$(document).ready(function() {\r\n\r\n";
if (($plan["status"] == 0) && $plan) {
	echo "\t$('.tab-btn').removeClass(\"tab-state-active\");\r\n\t$('.p_tab').hide(\"\");\r\n\t$(\"#p_ys\").addClass(\"tab-state-active\");\r\n\t$('.p_ys').show(\"\").find(\"h3\").hide(\"\");\r\n\t gp(";
	echo $plan["gradeprice"];
	echo ");\r\n\t//$('#tab_nf').html('');\r\n";
}

echo "\t\r\n\r\n});\r\n\r\n\r\n</script>\r\n";

?>
