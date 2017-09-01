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

echo ">\r\n  <!-- <a class=\"close\" href=\"javascript:;\">×</a>-->\r\n  <strong>保存成功.</strong> </div>\r\n<div id=\"sidebar\">\r\n  ";
TPL::display("sidebar");
echo "</div>\r\n<div id=\"main-content\">\r\n  <div id=\"main_nav\">\r\n    <div class=\"mn_lfet\"></div>\r\n    <div class=\"mn_right\">\r\n      <div class=\"mn_mt\">\r\n        <ul >\r\n          <li > <a href=\"javascript:history.go(-1);\" style=\"width: 86px;\">返回用户列表</a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n          <li > <a href=\"#\" style=\"width: 97px;\">用户编辑</a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n        </ul>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div class=\"row-fluid\">\r\n    <h3 class=\"heading\">基本编辑</h3>\r\n    <div class=\"row-fluid\">\r\n      <div class=\"span8\">\r\n        <form class=\"form-horizontal\" action=\"";
echo url("admin/user.update_post");
echo "\"  method=\"post\">\r\n          <input name=\"uid\" id=\"uid\" type=\"hidden\" value=\"";
echo $user["uid"];
echo "\" />\r\n          <input name=\"rating\"  id=\"rating\" type=\"hidden\" value=\"";
echo $user["rating"];
echo "\" />\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">用户</label>\r\n            <div class=\"controls\" style=\"padding-top:5px\"> <strong>";
echo $user["username"];
echo "</strong>\r\n              ";

switch ($user["status"]) {
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

echo "            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">用户ID</label>\r\n            <div class=\"controls\" style=\"padding-top:5px\"> #";
echo $user["uid"];
echo "</div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">用户角色</label>\r\n            <div class=\"controls\" style=\"padding-top:5px\">\r\n              ";

if ($user["type"] == "1") {
	echo "网站主";
}
else if ($user["type"] == "2") {
	echo "广告商";
}
else if ($user["type"] == "3") {
	echo "联盟客服";
}
else if ($user["type"] == "4") {
	echo "联盟商务";
}

echo "            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">新密码</label>\r\n            <div class=\"controls\">\r\n              <input name=\"pass\" type=\"text\" class=\"input_text span30\" id=\"pass\">\r\n              <span>如果您想修改密码，请在此输入新密码。不然请留空</span><br />\r\n              <br />\r\n              <input name=\"repass\" type=\"text\" class=\"input_text span30\" id=\"repass\">\r\n              <span>再输入一遍新密码</span> </div>\r\n          </div>\r\n          <!--    <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">信誉度</label>\r\n            <div class=\"controls\" style=\"padding-top: 5px;\">\r\n              <ul class=\"rating ";
echo $user["rating"];
echo "star \">\r\n                <li class=\"one\"><a href=\"javascript:;\" title=\"1 Star\">1</a></li>\r\n                <li class=\"two\"><a href=\"javascript:;\" title=\"2 Stars\">2</a></li>\r\n                <li class=\"three\"><a href=\"javascript:;\" title=\"3 Stars\">3</a></li>\r\n                <li class=\"four\"><a href=\"javascript:;\" title=\"4 Stars\">4</a></li>\r\n                <li class=\"five\"><a href=\"javascript:;\" title=\"5 Stars\">5</a></li>\r\n              </ul>\r\n            </div>\r\n          </div>-->\r\n\t\t  \r\n\t\t    ";

if ($user["type"] == 2) {
	echo "          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">属于商务</label>\r\n            <div class=\"controls\" >\r\n              <select name=\"serviceid\" id=\"serviceid\" style=\"width:160px;\">\r\n                <option value=\"\">请选择商务</option>\r\n                ";

	foreach ($commercialuser as $s ) {
		echo "                <option value=\"";
		echo $s["uid"];
		echo "\" ";

		if ($user["serviceid"] == $s["uid"]) {
			echo "selected";
		}

		echo ">";
		echo $s["username"];
		echo "</option>\r\n                ";
	}

	echo "              </select>\r\n            </div>\r\n          </div>\r\n\t\t  ";
}

echo "          ";

if ($user["type"] == 1) {
	echo "          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">属于客服</label>\r\n            <div class=\"controls\" >\r\n              <select name=\"serviceid\" id=\"serviceid\" style=\"width:160px;\">\r\n                <option value=\"\">请选择客服</option>\r\n                ";

	foreach ($serviceuser as $s ) {
		echo "                <option value=\"";
		echo $s["uid"];
		echo "\" ";

		if ($user["serviceid"] == $s["uid"]) {
			echo "selected";
		}

		echo ">";
		echo $s["username"];
		echo "</option>\r\n                ";
	}

	echo "              </select>\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">用户分成</label>\r\n            <div class=\"controls\">\r\n              <input name=\"usercommission\" type=\"text\" class=\"input_text span30\" id=\"usercommission\" value=\"";
	echo $user["usercommission"];
	echo "\">\r\n              <span>1~100的整数，0为不设置</span> </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">分成有效期</label>\r\n            <div class=\"controls\">\r\n              <input name=\"commissiontime\" type=\"text\" class=\"input_text span30\" id=\"commissiontime\" value=\"";
	echo $user["commissiontime"];
	echo "\">\r\n              <span>天</span> </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">用户组</label>\r\n            <div class=\"controls\">\r\n              <select name=\"groupid\">\r\n                ";

	foreach ((array) $group as $g ) {
		echo "                <option value=\"";
		echo $g["groupid"];
		echo "\" ";

		if ($user["groupid"] == $g["groupid"]) {
			echo "selected";
		}

		echo " >";
		echo $g["groupname"];
		echo "</option>\r\n                ";
	}

	echo "              </select>\r\n              <span></span> </div>\r\n          </div>\r\n          ";
}

echo "          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">备注说明</label>\r\n            <div class=\"controls\">\r\n              <textarea name=\"memo\" class=\"input_text span30\" id=\"memo\" style=\"overflow: hidden; word-wrap: break-word; max-height: 80px; min-height: 80px; height: 80px;\">";
echo $user["memo"];
echo "</textarea>\r\n              <span></span> </div>\r\n          </div>\r\n          ";

if ($user["type"] == 1) {
	echo "          <h3 class=\"heading\">扣量设置</h3>\r\n          ";

	foreach (explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
		echo "          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">";
		echo strtoupper($t);
		echo "扣量</label>\r\n            <div class=\"controls\">\r\n              <input name=\"deduction[";
		echo strtoupper($t);
		echo "]\" type=\"text\" class=\"input_text span30\" id=\"deduction\"  value=\"";
		echo $deduction[strtoupper($t)];
		echo "\" maxlength=\"3\">\r\n              <span>%</span> </div>\r\n          </div>\r\n          ";
	}

	echo "          <h3 class=\"heading\">相关设置</h3>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">域名限制</label>\r\n            <div class=\"controls\">\r\n              <input type=\"radio\" name=\"insite\" value=\"1\" ";

	if ($user["insite"] == "1") {
		echo "checked";
	}

	echo "/>\r\n              默认\r\n              <input type=\"radio\" name=\"insite\" value=\"2\"  ";

	if ($user["insite"] == "2") {
		echo "checked";
	}

	echo "/>\r\n              开启\r\n              <input type=\"radio\" name=\"insite\" value=\"3\"  ";

	if ($user["insite"] == "3") {
		echo "checked";
	}

	echo "/>\r\n              关闭 </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">PV步长值</label>\r\n            <div class=\"controls\">\r\n              <input name=\"pvstep\" type=\"text\"  class=\"input_text span30\" id=\"pvstep\" value=\"";
	echo $user["pvstep"];
	echo "\" size=\"20\" maxlength=\"3\" />\r\n              <span >会员步长值优先于全局步长值 0为默认</span> </div>\r\n          </div>\r\n          ";
}

echo "          <h3 class=\"heading\">联系信息</h3>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">电子邮件</label>\r\n            <div class=\"controls\">\r\n              <input name=\"email\" type=\"text\" class=\"input_text span30\" id=\"email\" value=\"";
echo $user["email"];
echo "\">\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">真实姓名</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" class=\"input_text span30\" id=\"contact\"  name=\"contact\" value=\"";
echo $user["contact"];
echo "\" />\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">联系QQ</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" class=\"input_text span30\" id=\"qq\"    name=\"qq\" value=\"";
echo $user["qq"];
echo "\" />\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">联系电话</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" class=\"input_text span30\" id=\"mobile\"  name=\"mobile\" value=\"";
echo $user["mobile"];
echo "\" />\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">身份证号码</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" class=\"input_text span30\" id=\"idcard\"  name=\"idcard\"  value=\"";
echo $user["idcard"];
echo "\" />\r\n            </div>\r\n          </div>\r\n          ";

if ($user["type"] == 1) {
	echo "          <h3 class=\"heading\">支付信息</h3>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">收款银行</label>\r\n            <div class=\"controls\">\r\n              <select name=\"bankname\" class='select' style=\"padding:4px; margin:0px\">\r\n                ";

	foreach ($GLOBALS["c_bank"] as $b => $v ) {
		if (!$v[1]) {
			continue;
		}

		echo "                <option value=\"";
		echo $v[0];
		echo "\" ";

		if ($user["bankname"] == $v[0]) {
			echo "selected";
		}

		echo ">";
		echo $b;
		echo "</option>\r\n                ";
	}

	echo "              </select>\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">开户分行</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" class=\"input_text span30\" id=\"bankbranch\" name=\"bankbranch\" value=\"";
	echo $user["bankbranch"];
	echo "\" />\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">收款姓名</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" class=\"input_text span30\" id=\"accountname\" name=\"accountname\" value=\"";
	echo $user["accountname"];
	echo "\" />\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">收款帐号</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" class=\"input_text span30\" id=\"bankaccount\" name=\"bankaccount\" value=\"";
	echo $user["bankaccount"];
	echo "\" />\r\n            </div>\r\n          </div>\r\n          <h3 class=\"heading\">下线设置</h3>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">推荐人</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" class=\"input_text span30\" name=\"recommend\" id=\"recommend\" value=\"";
	echo $user["recommend"];
	echo "\">\r\n              <span>通过哪个会员链接注册</span> </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">已发展下线</label>\r\n            <div class=\"controls\" style=\"padding-top:5px\"> ";
	echo dr("admin/user.get_sum_recommend", $user["uid"]);
	echo "个 <a href=\"";
	echo url("admin/user.affiliate_list?searchtype=recommend&search=" . $user["uid"]);
	echo "\">查询他名下的会员</a></div>\r\n          </div>\r\n          ";
}

echo "          <div class=\"control-group\" style=\"height:20px\">\r\n            <div class=\"controls\">\r\n              <button class=\"btn btn-gebo\" type=\"submit\">提交更新</button>\r\n            </div>\r\n          </div>\r\n          <h3 class=\"heading\">注册登入信息</h3>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">注册</label>\r\n            <div class=\"controls\" style=\"padding-top:5px\"> <i style=\"padding-left:30px\">IP：";
echo $user["regip"];
echo convert_ip($user["regip"]);
echo "</i> <i style=\"padding-left:30px\">时间：";
echo $user["regtime"];
echo "</i> </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">登入</label>\r\n            <div class=\"controls\" style=\"padding-top:5px\"> <i style=\"padding-left:30px\">最近一次IP：";
echo $user["loginip"];
echo convert_ip($u["loginip"]);
echo "</i> <i style=\"padding-left:30px\">时间：";
echo $user["logintime"];
echo "</i> </div>\r\n          </div>\r\n        </form>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n var rating= $(\".rating\").find(\"li\");\r\n rating.on('click', function(option) {\r\n \t\t\r\n\t\tuid = $(\"#uid\").val();\r\n\t\tvar rating = $(this).attr('class');\r\n\t\t $.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: '";
echo url("admin/user.update_rating");
echo "',\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'uid=' + uid +'&rating='+rating,\r\n\t\t\t\tsuccess: function() \r\n\t\t\t\t{\t\r\n\t\t\t\t\t\r\n\t\t\t\t\t$(\".rating\").attr(\"class\",'rating');\r\n\t\t\t\t\t$(\".rating\").addClass(rating+\"star\");\r\n\t\t\t\t\t$(\"#credit\").val(rating);\r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t\t\r\n});\r\n\r\n$(\"form\").submit(function() {\r\n       var ps = $(\"#pass\").val();\r\n\t   var reps = $(\"#repass\").val();\r\n\t   if(ps!=reps){\r\n\t        err(\"两次输入的密码不一样\");\r\n       \t\treturn false;\r\n\t   }\r\n});\r\n</script>\r\n";

?>
