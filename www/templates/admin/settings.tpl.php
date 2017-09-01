<?php

if (!defined("IN_ZYADS")) {
	exit();
}

TPL::display("header");
echo "<!--[if IE]><script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/lib/visualize/excanvas.compiled.js\"></script><![endif]-->\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/lib/visualize/visualize.jQuery.js\"></script>\r\n<link type=\"text/css\" rel=\"stylesheet\" href=\"";
echo WEB_URL;
echo "js/jquery/lib/visualize/visualize.jQuery.css\"/>\r\n<link type=\"text/css\" rel=\"stylesheet\" href=\"";
echo WEB_URL;
echo "js/jquery/lib/visualize/page.css\"/>\r\n";

if ($_SESSION["succ"]) {
	echo "<div class=\"alert success\"> \r\n  <!-- <a class=\"close\" href=\"javascript:;\">×</a>--> \r\n  <strong>保存成功.</strong> </div>\r\n";
}

if ($_SESSION["err"]) {
	echo "<div class=\"alert err\"> \r\n  <!-- <a class=\"close\" href=\"javascript:;\">×</a>--> \r\n  请检查seetings.php文件权限,保存失败。 </div>\r\n";
}

echo "<div id=\"sidebar\">\r\n  ";
TPL::display("sidebar");
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid\">\r\n    <h3 class=\"heading\">联盟设置</h3>\r\n    <div class=\"tab\">\r\n      <div class=\"tab-header-right\"> <a href=\"#\" class=\"tab-btn tab-state-active se\" id=\"t_settings\">基本设置</a> <a href=\"#\" class=\"tab-btn  se\" id=\"t_deduction\">扣量设置</a> <a href=\"#\" class=\"tab-btn  se\" id=\"t_server\">服务器设置</a> <a href=\"#\" class=\"tab-btn  se\" id=\"t_email\">邮箱设置</a> <a href=\"#\" class=\"tab-btn  se\" id=\"t_pay\">在线充值</a> <a href=\"#\" class=\"tab-btn  se\" id=\"t_paylog\">财务相关</a> <a href=\"#\" class=\"tab-btn  se\" id=\"t_other\">其它设置 </a> </div>\r\n    </div>\r\n    <div id=\"content\">\r\n      <form action=\"";
echo url("admin/settings.update_post?type=" . get("type"));
echo "\" class=\"js_submit\" method=\"post\">\r\n        <div class=\"control-group t_settings\"  >\r\n          <label class=\"control-label\">联盟名称</label>\r\n          <div class=\"controls\">\r\n            <input type=\"text\" name=\"sitename\" class=\"input_text span30\" value=\"";
echo $s["sitename"];
echo "\">\r\n            <span class=\"help-block\">首页和低部或是其它地方出现的联盟名称.</span> </div>\r\n          <label class=\"control-label\">计费模式开关</label>\r\n          <div class=\"controls\">\r\n            ";
$stats_type = explode(",", $s["stats_type"]);
echo "            <input type=\"checkbox\" name=\"stats_type[]\"  value=\"cpc\" class=\"inpt_c\"  ";

if (in_array("cpc", $stats_type)) {
	echo "checked";
}

echo ">\r\n            CPC\r\n            <input type=\"checkbox\" name=\"stats_type[]\"  value=\"cpm\" class=\"inpt_c\"  ";

if (in_array("cpm", $stats_type)) {
	echo "checked";
}

echo ">\r\n            CPM\r\n            <input type=\"checkbox\" name=\"stats_type[]\"  value=\"cpv\" class=\"inpt_c\"  ";

if (in_array("cpv", $stats_type)) {
	echo "checked";
}

echo ">\r\n            CPV\r\n            <input type=\"checkbox\" name=\"stats_type[]\"  value=\"cps\" class=\"inpt_c\"  ";

if (in_array("cps", $stats_type)) {
	echo "checked";
}

echo ">\r\n            CPS\r\n            <input type=\"checkbox\" name=\"stats_type[]\"  value=\"cpa\" class=\"inpt_c\"  ";

if (in_array("cpa", $stats_type)) {
	echo "checked";
}

echo ">\r\n            CPA </div>\r\n          <label class=\"control-label\">域名限制</label>\r\n          <div class=\"controls \">\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"domain_limit\"  value=\"1\" class=\"inpt_c\"  ";

if ($s["domain_limit"] == "1") {
	echo "checked";
}

echo ">\r\n              开启 </label>\r\n            <span class=\"help-block\">开启后非在审核通过的名下域名中投放广告不会显示</span> <br>\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"domain_limit\"  value=\"0\" class=\"inpt_c\"  ";

if ($s["domain_limit"] == "0") {
	echo "checked";
}

echo ">\r\n              关闭 </label>\r\n          </div>\r\n          <label class=\"control-label\">安全密钥</label>\r\n          <div class=\"controls \">\r\n          \r\n          <input type=\"password\" name=\"url_key\" class=\"input_text span30\" value=\"";
echo $s["url_key"];
echo "\" style=\"width:120px\">\r\n          <span class=\"help-block\">有些地方会使此安全密钥加密，可以定制期修改，多台服务器修复需保持同步.</span>\r\n           </div>\r\n           <label class=\"control-label\">PV步长值</label>\r\n          <div class=\"controls \">\r\n          \r\n          <input type=\"text\" name=\"pv_step\" class=\"input_text span30\" value=\"";
echo $s["pv_step"];
echo "\" style=\"width:120px\">\r\n          <span class=\"help-block\">根据PV的大小设定一个合适步长，一般为10，0等于不统计PV.</span>\r\n          </div>\r\n          \r\n          <label class=\"control-label\">允许新用户注册</label>\r\n          <div class=\"controls \">\r\n            <label class=\"radio inline\">\r\n              <input type=\"checkbox\" name=\"opne_affiliate_register\"  value=\"1\" class=\"inpt_c\"  ";

if ($s["opne_affiliate_register"] == "1") {
	echo "checked";
}

echo ">\r\n              开放站长注册 </label>\r\n            <br>\r\n            <label class=\"radio inline\">\r\n              <input type=\"checkbox\" name=\"opne_advertiser_register\"  value=\"1\" class=\"inpt_c\"  ";

if ($s["opne_advertiser_register"] == "1") {
	echo "checked";
}

echo ">\r\n              开放广告商注册 </label>\r\n          </div>\r\n          <label class=\"control-label\">注册验证</label>\r\n          <div class=\"controls \">\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"register_status\" value=\"1\" class=\"inpt_c\" ";

if ($s["register_status"] == "1") {
	echo "checked";
}

echo ">\r\n              手动审核 </label>\r\n            <br>\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"register_status\" value=\"2\" class=\"inpt_c\" ";

if ($s["register_status"] == "2") {
	echo "checked";
}

echo ">\r\n              Email 验证 </label>\r\n            <span class=\"help-block\">“Email 验证”将向用户注册 Email 发送一封验证邮件以确认邮箱.</span> <br>\r\n            <label class=\"radio inline\" style=\"_margin-left:3px\">\r\n              <input type=\"radio\" name=\"register_status\" value=\"3\" class=\"inpt_c\" ";

if ($s["register_status"] == "3") {
	echo "checked";
}

echo ">\r\n              直接通过 </label>\r\n          </div>\r\n          <label class=\"control-label\">新增网站</label>\r\n          <div class=\"controls \">\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"site_status\" value=\"0\" class=\"inpt_c\" ";

if ($s["site_status"] == "0") {
	echo "checked";
}

echo ">\r\n              人工审核 </label>\r\n            <br>\r\n            <label class=\"radio inline\" style=\"_margin-left:3px\">\r\n              <input type=\"radio\" name=\"site_status\" value=\"3\" class=\"inpt_c\" ";

if ($s["site_status"] == "3") {
	echo "checked";
}

echo ">\r\n              自动通过 </label>\r\n            <br>\r\n            <label class=\"radio inline\" style=\"_margin-left:3px\">\r\n              <input type=\"radio\" name=\"site_status\" value=\"4\" class=\"inpt_c\" ";

if ($s["site_status"] == "4") {
	echo "checked";
}

echo ">\r\n              需验证网站后自动通过 </label>\r\n            <br>\r\n            <label class=\"radio inline\" style=\"_margin-left:3px\">\r\n              <input type=\"radio\" name=\"site_status\" value=\"5\" class=\"inpt_c\" ";

if ($s["site_status"] == "5") {
	echo "checked";
}

echo ">\r\n              需验证网站后人工审核 </label>\r\n          </div>\r\n          \r\n           <label class=\"control-label\">联盟标志</label>\r\n          <div class=\"controls \">\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"union_bz_status\" value=\"1\" class=\"inpt_c\" ";

if ($s["union_bz_status"] == "1") {
	echo "checked";
}

echo ">\r\n              显示 </label>\r\n            <br>\r\n            <label class=\"radio inline\" style=\"_margin-left:3px\">\r\n              <input type=\"radio\" name=\"union_bz_status\" value=\"2\" class=\"inpt_c\" ";

if ($s["union_bz_status"] == "2") {
	echo "checked";
}

echo ">\r\n              关闭 </label>\r\n            \r\n          </div>\r\n          \r\n          \r\n          <label class=\"control-label\">24小时允许注册</label>\r\n          <div class=\"controls\">\r\n            <input  type=\"text\"  name=\"24_hours_register_num\" class=\"input_text span30\" value=\"";
echo $s["24_hours_register_num"];
echo "\">\r\n            次 <span class=\"help-block\">24小时内允许注册的次数，防垃圾注册.</span> </div>\r\n          <label class=\"control-label\">屏蔽以下IP注册</label>\r\n          <div class=\"controls\">\r\n            <textarea name=\"ban_ip_register\" rows=\"3\" class=\"input_text span30\" id=\"setting_post[ban_ip_register]\" style=\"height:70px\">";
echo $s["ban_ip_register"];
echo "</textarea>\r\n            <span class=\"help-block\">多IP用“,”分隔 如：192.168.1.1,192.168.1.2.</span> </div>\r\n          <label class=\"control-label\">会员登入验证码 </label>\r\n          <div class=\"controls\">\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"login_check_code\"  value=\"1\" class=\"inpt_c\"  ";

if ($s["login_check_code"] == "1") {
	echo "checked";
}

echo ">\r\n              启用 </label>\r\n            <br>\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"login_check_code\"  value=\"2\" class=\"inpt_c\"  ";

if ($s["login_check_code"] == "2") {
	echo "checked";
}

echo ">\r\n              关闭 </label>\r\n          </div>\r\n          <label class=\"control-label\">注册增送钱</label>\r\n          <div class=\"controls\">\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"register_add_money_on\"  value=\"0\" class=\"inpt_c add_money_on\" ";

if ($s["register_add_money_on"] == "0") {
	echo "checked";
}

echo ">\r\n              关闭 </label>\r\n            <br>\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"register_add_money_on\" value=\"1\" class=\"inpt_c add_money_on\" ";

if ($s["register_add_money_on"] == "1") {
	echo "checked";
}

echo ">\r\n              开启 </label>\r\n          </div>\r\n          <div class=\"add_money\" style=\"display:";

if ($s["register_add_money_on"] == "1") {
	echo "''";
}
else {
	echo "none";
}

echo "\" >\r\n            <label class=\"control-label\">增送款项到</label>\r\n            <div class=\"controls\">\r\n              <label class=\"radio inline\">\r\n                <input type=\"radio\" name=\"register_add_money_type\"  value=\"day\" class=\"inpt_c\" ";

if ($s["register_add_money_type"] == "day") {
	echo "checked";
}

echo ">\r\n                日款 </label>\r\n              <br>\r\n              <label class=\"radio inline\">\r\n                <input type=\"radio\" name=\"register_add_money_type\" value=\"week\" class=\"inpt_c\" ";

if ($s["register_add_money_type"] == "week") {
	echo "checked";
}

echo ">\r\n                周款 </label>\r\n              <br>\r\n              <label class=\"radio inline\">\r\n                <input type=\"radio\" name=\"register_add_money_type\" value=\"month\" class=\"inpt_c\" ";

if ($s["register_add_money_type"] == "month") {
	echo "checked";
}

echo ">\r\n                月款 </label>\r\n            </div>\r\n            <label class=\"control-label\">增送款金额 </label>\r\n            <div class=\"controls\">\r\n              <label class=\"radio inline\">\r\n                <input  type=\"text\"  name=\"register_add_money\" class=\"input_text span30\" value=\"";
echo $s["register_add_money"];
echo "\" >\r\n                元</label>\r\n            </div>\r\n          </div>\r\n        </div>\r\n        <div class=\"control-group t_deduction\">\r\n          ";

foreach (explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
	$dtype = $t . "_deduction";
	echo "          <label class=\"control-label\">";
	echo strtoupper($t);
	echo "设置</label>\r\n          <div class=\"controls \">\r\n            <input  type=\"text\"  name=\"";
	echo $dtype;
	echo "\" class=\"input_text span30\" value=\"";
	echo $s[$dtype];
	echo "\">\r\n            % </div>\r\n          ";
}

echo "        </div>\r\n        <div class=\"control-group t_server\">\r\n          <label class=\"control-label\">主站服务器IP</label>\r\n          <div class=\"controls\">\r\n            <input type=\"text\" name=\"site_ip\" class=\"input_text span30\" value=\"";
echo $s["site_ip"];
echo "\">\r\n            <span class=\"help-block\">联盟主站所在服务器的IP 多IP用“,”分隔 如：192.168.1.1,192.168.1.2</span> </div>\r\n          <label class=\"control-label\">JS服务器</label>\r\n          <div class=\"controls\">\r\n            <input type=\"text\"  name=\"js_url\" class=\"input_text span30\" value=\"";
echo $s["js_url"];
echo "\">\r\n            <span class=\"help-block\">JS调用服务器 可部署CDN 分布式存储、负载均衡.</span> </div>\r\n          <label class=\"control-label\">图片服务器</label>\r\n          <div class=\"controls\">\r\n            <input type=\"text\" name=\"img_url\" class=\"input_text span30\" value=\"";
echo $s["img_url"];
echo "\">\r\n            <span class=\"help-block\">图片储存服务器 可部署CDN/Squid 分布式存储、负载均衡.</span> </div>\r\n          <label class=\"control-label\">跳转服务器</label>\r\n          <div class=\"controls\">\r\n            <input type=\"text\" name=\"jump_url\" class=\"input_text span30\" value=\"";
echo $s["jump_url"];
echo "\">\r\n            <span class=\"help-block\">广告跳转服务器.</span> </div>\r\n          <label class=\"control-label\">同步服务器</label>\r\n          <div class=\"controls\">\r\n            <input type=\"text\" name=\"sync_setting\" class=\"input_text span30\" value=\"";
echo $s["sync_setting"];
echo "\">\r\n            <span class=\"help-block\">多服务器运作时需同步的缓存、设置等，分隔符 ',' 格式:code.zyiis.com,c.zyiis.com.</span> </div>\r\n          <label class=\"control-label\">Master/Slave </label>\r\n          <div class=\"controls\">\r\n            <label class=\"radio inline\">\r\n              <input name=\"db_ms\" type=\"radio\" value=\"1\" ";

if ($s["db_ms"] == "1") {
	echo "checked";
}

echo " />\r\n              开启 </label>\r\n            <span class=\"help-block\">修改配置文件增加Slave服务器.</span> <br>\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"db_ms\" value=\"0\" ";

if ($s["db_ms"] == "0") {
	echo "checked";
}

echo " />\r\n              关闭 </label>\r\n          </div>\r\n          <label class=\"control-label\">缓存类型</label>\r\n          <div class=\"controls\">\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"cache_type\" value=\"memcached\" ";

if ($s["cache_type"] == "memcached") {
	echo "checked";
}

echo "  onclick=\"$('.memcached').show('slow')\"/>\r\n              &nbsp;&nbsp;Memcached </label>\r\n            <span class=\"help-block\">\r\n            ";

if (extension_loaded("memcache")) {
	echo "支持";
}
else {
	echo "当前服务器不支持memcache扩展";
}

echo "            </span> <br>\r\n            <label class=\"radio inline\">\r\n              <input name=\"cache_type\" type=\"radio\" value=\"file\" ";

if ($s["cache_type"] == "file") {
	echo "checked";
}

echo "  onclick=\"$('.memcached').hide('slow')\"/>\r\n              &nbsp;&nbsp;文件 </label>\r\n            <br>\r\n            <label class=\"radio inline\" style=\"_margin-left:3px;\">\r\n              <input type=\"radio\" name=\"cache_type\" value=\"none\" ";

if ($s["cache_type"] == "none") {
	echo "checked";
}

echo "  onclick=\"$('.memcached').hide('slow')\"/>\r\n              &nbsp;&nbsp;不缓存 </label>\r\n          </div>\r\n          <div class=\"memcached\" style=\"display:";

if ($s["cache_type"] == "memcached") {
	echo "''";
}
else {
	echo "none";
}

echo "\" >\r\n            <label class=\"control-label\">Memcached服务器</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" name=\"memcached_host\" class=\"input_text span30\" value=\"";
echo $s["memcached_host"];
echo "\">\r\n            </div>\r\n            <label class=\"control-label\">端口</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\"  name=\"memcached_port\" class=\"input_text span30\" value=\"";
echo $s["memcached_port"];
echo "\">\r\n              <span class=\"help-block\">默认为 11211.</span> </div>\r\n          </div>\r\n          <label class=\"control-label\">缓存时间</label>\r\n          <div class=\"controls\">\r\n            <input type=\"text\"  name=\"cache_time\" class=\"input_text span30\" value=\"";
echo $s["cache_time"];
echo "\">\r\n            秒 </div>\r\n        </div>\r\n        <div class=\"control-group t_email\">\r\n          <label class=\"control-label\">何时发送电子邮件</label>\r\n          <div class=\"controls \">\r\n            <label class=\"radio inline\">\r\n              <input name=\"tomail[]\" type=\"checkbox\" value=\"register\" ";
echo in_array("register", explode(",", $s["tomail"])) ? " checked" : "";
echo "/>\r\n              注册成功能时 </label>\r\n            <br>\r\n            <label class=\"radio inline\">\r\n              <input name=\"tomail[]\" type=\"checkbox\"  value=\"useractivate\" ";
echo in_array("useractivate", explode(",", $s["tomail"])) ? " checked" : "";
echo "/>\r\n              会员审核通过时 </label>\r\n            <br>\r\n            <label class=\"radio inline\"  style=\"_margin-left:3px;\">\r\n              <input name=\"tomail[]\" type=\"checkbox\"  value=\"siteactivate\" ";
echo in_array("siteactivate", explode(",", $s["tomail"])) ? " checked" : "";
echo "/>\r\n              网站审核通过时 </label>\r\n            <br>\r\n            <label class=\"radio inline\"  style=\"_margin-left:3px;\">\r\n              <input name=\"tomail[]\" type=\"checkbox\"  value=\"pay\" ";
echo in_array("pay", explode(",", $s["tomail"])) ? " checked" : "";
echo "/>\r\n              财务结算付款时 </label>\r\n          </div>\r\n          <label class=\"control-label\">邮件发送方式</label>\r\n          <div class=\"controls\">\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"mail_send\"  value=\"1\"  ";

if ($s["mail_send"] == "1") {
	echo "checked";
}

echo "  onclick=\"$('.stmps').hide('slow')\"/>\r\n              通过 PHP 函数的 Sendmail 发送(推荐此方式) </label>\r\n            <span class=\"help-block\">需要支持Sendmail.</span> <br>\r\n            <label class=\"radio inline\">\r\n              <input  type=\"radio\"  name=\"mail_send\" value=\"2\" ";

if ($s["mail_send"] == "2") {
	echo "checked";
}

echo "  onclick=\"$('.stmps').show('slow')\"/>\r\n              通过 SOCKET 连接 SMTP 服务器发送 </label>\r\n          </div>\r\n          <div class=\"stmps\" style=\"display:";

if ($s["mail_send"] == "2") {
	echo "''";
}
else {
	echo "none";
}

echo "\" >\r\n            <label class=\"control-label\">SMTP 服务</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\"  name=\"mail_server\" class=\"input_text span30\" value=\"";
echo $s["mail_server"];
echo "\">\r\n              <span class=\"help-block\">设置 SMTP 服务器的地址.</span> </div>\r\n            <label class=\"control-label\">SMTP 端口</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" name=\"mail_port\" class=\"input_text span30\" value=\"";
echo $s["mail_port"];
echo "\">\r\n              <span class=\"help-block\">默认为 25.</span> </div>\r\n            <label class=\"control-label\">SMTP 要求身份验证</label>\r\n            <div class=\"controls\">\r\n              <label class=\"radio inline\">\r\n                <input type=\"radio\" name=\"mail_auth\"   value=\"1\" class=\"inpt_c\" ";

if ($s["mail_auth"] == "1") {
	echo "checked";
}

echo ">\r\n                是 </label>\r\n              <span class=\"help-block\">如果 SMTP 服务器要求身份验证，请选择“是”.</span> <br>\r\n              <label class=\"radio inline\">\r\n                <input type=\"radio\" name=\"mail_auth\"   value=\"0\" class=\"inpt_c\" ";

if ($s["mail_auth"] == "0") {
	echo "checked";
}

echo ">\r\n                否 </label>\r\n            </div>\r\n            <label class=\"control-label\">发信人邮件地址</label>\r\n            <div class=\"controls\">\r\n              <input  type=\"text\" name=\"mail_from\" class=\"input_text span30\" value=\"";
echo $s["mail_from"];
echo "\">\r\n              <span class=\"help-block\">需要验证的, 必须为本服务器的邮件地址.</span> </div>\r\n            <label class=\"control-label\">SMTP 用户名</label>\r\n            <div class=\"controls\">\r\n              <input  type=\"text\" name=\"mail_username\" class=\"input_text span30\" value=\"";
echo $s["mail_username"];
echo "\">\r\n            </div>\r\n            <label class=\"control-label\">SMTP 密码</label>\r\n            <div class=\"controls\">\r\n              <input  type=\"password\" name=\"mail_password\" class=\"input_text span30\" value=\"";
echo $s["mail_password"];
echo "\">\r\n            </div>\r\n          </div>\r\n          <div style=\"margin-left:30px;\">\r\n            <button class=\"btn btn-51a351\" type=\"button\" id=\"test_email\">测式发送</button>\r\n            <span>(保存设置后再点测试发送)</span> <span id=\"test_email_text\" style=\"color:#F00; padding-left:10px; display:none\">正在发送测试邮件，请稍等......</span></div>\r\n        </div>\r\n        <div class=\"control-group t_pay\">\r\n          <label class=\"control-label\">在线充值最低额</label>\r\n          <div class=\"controls\">\r\n            <input  type=\"text\" name=\"min_pay\" class=\"input_text span30\" value=\"";
echo $s["min_pay"];
echo "\">\r\n            元 <span class=\"help-block\">在线充值的最低金额限制， 如：100.</span> </div>\r\n          <label class=\"control-label\">默认使用网关</label>\r\n          <div class=\"controls\">\r\n            ";

foreach ($GLOBALS["c_onlinepay"] as $b => $v ) {
	if (!$v[1]) {
		continue;
	}

	echo "            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"default_pay\" value=\"";
	echo $v[0];
	echo "\" ";

	if ($s["default_pay"] == $v[0]) {
		echo "checked";
	}

	echo " class=\"p_pay\"/>\r\n              &nbsp;&nbsp;";
	echo $b;
	echo " </label>\r\n            <br>\r\n            ";
}

echo "          </div>\r\n          ";

foreach ($GLOBALS["c_onlinepay"] as $b => $v ) {
	if (!$v[1]) {
		continue;
	}

	echo "          <div class=\"";
	echo $v[0];
	echo "_s s_s_pay\" style=\" ";

	if ($s["default_pay"] == $v[0]) {
		echo "display:block";
	}

	echo "\">\r\n            ";

	if ($v[0] == "alipay") {
		echo "            <label class=\"control-label\">支付宝Email</label>\r\n            <div class=\"controls\">\r\n              <input  type=\"text\" name=\"alipay_email\" class=\"input_text span30\" value=\"";
		echo $s["alipay_email"];
		echo "\">\r\n            </div>\r\n            ";
	}

	echo "            <label class=\"control-label\">";
	echo $b;
	echo "ID</label>\r\n            <div class=\"controls\">\r\n              <input  type=\"text\" name=\"";
	echo $v[0];
	echo "_id\" class=\"input_text span30\" value=\"";
	echo $s[$v[0] . "_id"];
	echo "\">\r\n            </div>\r\n            <label class=\"control-label\">KEY</label>\r\n            <div class=\"controls\">\r\n              <input  type=\"password\" name=\"";
	echo $v[0];
	echo "_key\" class=\"input_text span30\" value=\"";
	echo $s[$v[0] . "_key"];
	echo "\">\r\n            </div>\r\n          </div>\r\n          ";
}

echo "        </div>\r\n        <div class=\"control-group t_paylog\">\r\n          <label class=\"control-label\">最低付款金额</label>\r\n          <div class=\"controls\">\r\n            <input  type=\"text\" name=\"min_clearing\" class=\"input_text span30\" value=\"";
echo $s["min_clearing"];
echo "\">\r\n            元 <span class=\"help-block\">余额达到这个最低值时给于结算.</span> </div>\r\n          <label class=\"control-label\">结算方式</label>\r\n          <div class=\"controls\">\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"clearing_atuo\" value=\"0\" ";

if ($s["clearing_atuo"] == "0") {
	echo "checked";
}

echo " />\r\n              &nbsp;&nbsp;自动结算 </label>\r\n            <span class=\"help-block\">自动生成结算信息，还需人工转款</span> <br>\r\n            <label class=\"radio inline\">\r\n              <input name=\"clearing_atuo\" type=\"radio\" value=\"1\" ";

if ($s["clearing_atuo"] == "1") {
	echo "checked";
}

echo "/>\r\n              &nbsp;&nbsp;手动结算 </label>\r\n            <span class=\"help-block\">联盟相关人员手动生成结算信息</span> <br>\r\n            <label class=\"radio inline\" style=\"_margin-left:3px;\">\r\n              <input type=\"radio\" name=\"clearing_atuo\" value=\"2\" ";

if ($s["clearing_atuo"] == "2") {
	echo "checked";
}

echo " />\r\n              &nbsp;&nbsp;提现 </label>\r\n            <span class=\"help-block\"  style=\" margin-left:30px\">达到标准会员手动申请支付</span> </div>\r\n          <label class=\"control-label\">劳务税</label>\r\n          <div class=\"controls\">\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"tax_status\" value=\"0\" ";

if ($s["tax_status"] == "0") {
	echo "checked";
}

echo "/>\r\n              &nbsp;&nbsp;不代扣 </label>\r\n            <span class=\"help-block\"></span> <br>\r\n            <label class=\"radio inline\">\r\n              <input name=\"tax_status\" type=\"radio\" value=\"1\" ";

if ($s["tax_status"] == "1") {
	echo "checked";
}

echo "/>\r\n              &nbsp;&nbsp;带扣 </label>\r\n            <span class=\"help-block\"></span> </div>\r\n          <label class=\"control-label\">手续费</label>\r\n          <div class=\"controls\">\r\n            <input  type=\"text\" name=\"clearing_charges\" class=\"input_text span30\" value=\"";
echo $s["clearing_charges"];
echo "\">\r\n            % <span class=\"help-block\">扣除相应手续费.</span> </div>\r\n          \r\n          <label class=\"control-label\">周结日期</label>\r\n          <div class=\"controls\">\r\n            <select name=\"clearing_weekdata\" id=\"clearing_weekdata\" style=\"WIDTH: 120px\">\r\n              <option value=\"1\" ";

if ($GLOBALS["C_ZYIIS"]["clearing_weekdata"] == "1") {
	echo "selected";
}

echo ">星期一</option>\r\n              <option value=\"2\" ";

if ($GLOBALS["C_ZYIIS"]["clearing_weekdata"] == "2") {
	echo "selected";
}

echo ">星期二</option>\r\n              <option value=\"3\" ";

if ($GLOBALS["C_ZYIIS"]["clearing_weekdata"] == "3") {
	echo "selected";
}

echo ">星期三</option>\r\n              <option value=\"4\" ";

if ($GLOBALS["C_ZYIIS"]["clearing_weekdata"] == "4") {
	echo "selected";
}

echo ">星期四</option>\r\n              <option value=\"5\" ";

if ($GLOBALS["C_ZYIIS"]["clearing_weekdata"] == "5") {
	echo "selected";
}

echo ">星期五</option>\r\n              <option value=\"6\" ";

if ($GLOBALS["C_ZYIIS"]["clearing_weekdata"] == "6") {
	echo "selected";
}

echo ">星期六</option>\r\n              <option value=\"0\" ";

if ($GLOBALS["C_ZYIIS"]["clearing_weekdata"] == "0") {
	echo "selected";
}

echo ">星期日</option>\r\n            </select>\r\n            \r\n            <!-- <input  type=\"text\" name=\"clearing_weekdata\" class=\"input_text span30\" value=\"";
echo $s["clearing_weekdata"];
echo "\">--> \r\n            <span class=\"help-block\">每周的星期几结算</span> </div>\r\n          <label class=\"control-label\">月结几号</label>\r\n          <div class=\"controls\"> \r\n            <!--<input  type=\"text\" name=\"clearing_monthdata\" class=\"input_text span30\" value=\"";
echo $s["clearing_monthdata"];
echo "\"> 号-->\r\n            \r\n            <select name=\"clearing_monthdata\" id=\"clearing_monthdata\" style=\"width:80px\">\r\n              ";

for ($i = 1; $i < 32; $i++) {
	echo "              <option value=\"";
	echo $i;
	echo "\" ";

	if ($GLOBALS["C_ZYIIS"]["clearing_monthdata"] == $i) {
		echo "selected";
	}

	echo ">";
	echo $i;
	echo "号</option>\r\n              ";
}

echo "            </select>\r\n            <span class=\"help-block\">每月的几号结算</span> </div>\r\n        </div>\r\n        <div class=\"control-group t_other\">\r\n          <label class=\"control-label\">云端作弊扫描</label>\r\n          <div class=\"controls\">\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"zy_cloud\"  value=\"1\" class=\"inpt_c\" ";

if ($s["zy_cloud"] == "1") {
	echo "checked";
}

echo ">\r\n              开启（推荐） </label>\r\n            <span class=\"help-block\">启用后代码中会加载中易云端检测代码</span> <br>\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"zy_cloud\" value=\"2\" class=\"inpt_c\" ";

if ($s["zy_cloud"] == "2") {
	echo "checked";
}

echo ">\r\n              关闭 </label>\r\n          </div>\r\n          <label class=\"control-label\">管理后台限制IP</label>\r\n          <div class=\"controls\">\r\n            <textarea name=\"ban_ip_admin\" rows=\"3\" class=\"input_text span30\" id=\"setting_post[ban_ip_admin]\" style=\"height:70px\">";
echo $s["ban_ip_admin"];
echo "</textarea>\r\n            <span class=\"help-block\">屏蔽IP进入管理后台，多IP用“,”分隔 如：192.168.1.1,192.168.1.2.</span> </div>\r\n          <label class=\"control-label\">开启积分功能</label>\r\n          <div class=\"controls\">\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"integral_status\"  value=\"1\" class=\"inpt_c\" ";

if ($s["integral_status"] == "1") {
	echo "checked";
}

echo " onclick=\"$('#n_integra').show('slow')\">\r\n              开启 </label>\r\n            <br>\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"integral_status\" value=\"0\" class=\"inpt_c\" ";

if ($s["integral_status"] == "0") {
	echo "checked";
}

echo " onclick=\"$('#n_integra').hide('slow')\">\r\n              关闭 </label>\r\n          </div>\r\n          \r\n           <div id=\"n_integra\"  style=\"display:";

if ($s["integral_status"] == "1") {
	echo "''";
}
else {
	echo "none";
}

echo "\" > \r\n           \r\n          <label class=\"control-label\">按PV获取积分</label>\r\n          <div class=\"controls\"> 浏览量达到\r\n            <input  type=\"text\"  name=\"integral_daypv\" class=\"input_text span30\" value=\"";
echo $s["integral_daypv"];
echo "\" style=\"width:60px\">\r\n            获取\r\n            <input  type=\"text\"  name=\"integral_day\" class=\"input_text span30\" value=\"";
echo $s["integral_day"];
echo "\" style=\"width:60px\">\r\n            积分 <span class=\"help-block\">一天24小时不间断投放广告PV达到多少时获的多少分</span> </div>\r\n          <label class=\"control-label\"> 1元等于多少积分</label>\r\n          <div class=\"controls\">\r\n            <input  type=\"text\"  name=\"integral_topay\" class=\"input_text span30\" value=\"";
echo $s["integral_topay"];
echo "\" >\r\n            <span class=\"help-block\">广告收入转成积分设置，财务管理结算会自动转换成积分</span> </div>\r\n            </div>\r\n          <label class=\"control-label\">开启下线分成</label>\r\n          <div class=\"controls \">\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"recommend_status\"  value=\"1\" class=\"inpt_c\" ";

if ($s["recommend_status"] == "1") {
	echo "checked";
}

echo " onclick=\"$('#n_recommend_tc').show('slow')\">\r\n              开启 </label>\r\n            <br>\r\n            <label class=\"radio inline\">\r\n              <input type=\"radio\" name=\"recommend_status\" value=\"2\" class=\"inpt_c\" ";

if ($s["recommend_status"] == "2") {
	echo "checked";
}

echo " onclick=\"$('#n_recommend_tc').hide('slow')\">\r\n              关闭 </label>\r\n            <span class=\"help-block\">会员通过固定的联盟链接发展下级会员，并获的下线分成.</span> </div>\r\n            \r\n          <div id=\"n_recommend_tc\"  style=\"display:";

if ($s["recommend_status"] == "1") {
	echo "''";
}
else {
	echo "none";
}

echo "\" > \r\n          <label class=\"control-label\">下线分成比例</label>\r\n          <div class=\"controls\">\r\n            <input  type=\"text\"  name=\"recommend_tc\" class=\"input_text span30\" value=\"";
echo $s["recommend_tc"];
echo "\">\r\n            % <span class=\"help-block\">下线获得的广告费，联盟需按此比例额外支付给他的推荐人.</span> </div>\r\n            </div>\r\n            \r\n          <label class=\"control-label\">广告异常提示文字</label>\r\n          <div class=\"controls\">\r\n            <input  type=\"text\"  name=\"show_text_nouserstatus\" class=\"input_text span30\" value=\"";
echo $s["show_text_nouserstatus"];
echo "\">\r\n            <span class=\"help-block\">会员锁定或是非正常状态下还在投放代码</span><br>\r\n             <input  type=\"text\"  name=\"show_text_domain_limit\" class=\"input_text span30\" value=\"";
echo $s["show_text_domain_limit"];
echo "\">\r\n            <span class=\"help-block\">域名限制</span>\r\n            <br>\r\n             <input  type=\"text\"  name=\"show_text_notad\" class=\"input_text span30\" value=\"";
echo $s["show_text_notad"];
echo "\">\r\n            <span class=\"help-block\">没有任何广告可以展示</span>\r\n             </div>\r\n             \r\n             <label class=\"control-label\">QQ OAuth2.0</label>\r\n          <div class=\"controls\">\r\n            <input  type=\"text\"  name=\"oauth_qq_app_id\" class=\"input_text span30\" value=\"";
echo $s["oauth_qq_app_id"];
echo "\">\r\n            <span class=\"help-block\">APP ID</span><br>\r\n             <input  type=\"text\"  name=\"oauth_qq_app_key\" class=\"input_text span30\" value=\"";
echo $s["oauth_qq_app_key"];
echo "\">\r\n            <span class=\"help-block\">APP KEY</span>\r\n           \r\n             </div>\r\n             \r\n        </div>\r\n        <div class=\"sbut\">\r\n          <button class=\"btn btn-inverse\" type=\"submit\">保存设置</button>\r\n        </div>\r\n      </form>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n\r\n \r\n\r\n$('#test_email').on('click', function(option) {\r\n\t$('#test_email_text').show();\r\n \t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: '";
echo url("admin/settings.test_email");
echo "',\r\n\t\t\t\ttype: 'get',\r\n\t\t\t\tdata: '',\r\n\t\t\t\tsuccess: function(data) \r\n\t\t\t\t{\r\n\t\t\t\t\tdata != '1' ? d = '无法发送，请检查配置是否正确'+data : d = '发送成功';\r\n\t\t\t\t\t$('#test_email_text').html(d);\r\n\t\t\t\t}\r\n\t\t\t});\r\n\r\n});\r\n\r\n$('.p_pay').on('click', function(option) {\r\n \t\t $('.s_s_pay').hide(\"\");\r\n\t\t var sid = $(this).attr('value');  \r\n\t\t $('.'+sid+'_s').show(\"slow\");\r\n});\r\n\r\n\r\n$('.tab-btn').on('click', function(option) {\r\n \t\t$('.tab-btn').removeClass(\"tab-state-active\");\r\n\t\t$(this).addClass(\"tab-state-active\");\r\n\t\t$('.control-group').hide(\"slow\");\r\n\t\tvar sid = $(this).attr('id');  \r\n\t\t$('.'+sid ).show(\"slow\");\r\n\t\t$.cookie('s_tab',sid); \r\n});\r\n \r\n \r\n$('.add_money_on').on('click', function(option) {\r\n \t\tvar v= $(this).val();\r\n\t\tif(v==1){\r\n\t\t  $('.add_money').show(\"slow\");\r\n\t\t}else {\r\n\t\t\t\t$('.add_money').hide(\"slow\");\r\n\t\t\t}\r\n});\r\n\r\n\r\nvar nav = nav1 = $.cookie('s_tab');   \r\nif(!nav)  nav = \"t_settings\";   \r\n//$('.tab-btn').removeClass(\"tab-state-active\");\r\n\r\n";

if (get("type")) {
}

echo "\r\nif(nav1!=nav && nav1) { nav =nav1}\r\n\r\n\t\r\n$('.tab-btn').removeClass(\"tab-state-active\");\t\r\n$('.control-group').hide(\"\");\r\n$('.'+nav).show(\"\");\t\r\n$('#'+nav).addClass(\"tab-state-active\");\t\r\n\r\n\t\r\n</script> \r\n";

?>
