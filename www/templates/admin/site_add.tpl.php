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
echo "</div>\r\n<div id=\"main-content\">\r\n  <div id=\"main_nav\">\r\n    <div class=\"mn_lfet\"></div>\r\n    <div class=\"mn_right\">\r\n      <div class=\"mn_mt\">\r\n        <ul >\r\n          <li > <a href=\"";
echo url("admin/site.get_list");
echo "\" style=\"width: 86px;\">返回列表</a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n          <li > <a href=\"#\" style=\"width: 97px;\">\r\n            ";
echo RUN_ACTION == "add" ? "新建网站" : "编辑网站";
echo "            </a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n        </ul>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div class=\"row-fluid\">\r\n  \r\n     \r\n    <h3 class=\"heading\">\r\n      ";
echo RUN_ACTION == "add" ? "新建网站" : "编辑网站";
echo "        \r\n      </h3>\r\n\t  \r\n\t  \r\n    <div class=\"row-fluid\">\r\n      <div class=\"span8\">\r\n        <form class=\"form-horizontal\" action=\"\r\n\t\t ";

if (RUN_ACTION == "add") {
	echo url("admin/site.add_post");
}
else {
	echo url("admin/site.update_post");
}

echo "\"  method=\"post\" id=\"form_b\" name=\"form_b\">\r\n          <input name=\"siteid\" id=\"siteid\" type=\"hidden\" value=\"";
echo $site["siteid"];
echo "\" />\r\n\t\t  \r\n\t\t  ";

if (RUN_ACTION == "add") {
	echo "\t\t   <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">站长名称<span class=\"red_x\">*</span></label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" name=\"username\" id=\"username\" class=\"input_text span30\" value=\"";
	echo $site["siteurl"];
	echo "\" ";

	if (RUN_ACTION == "edit") {
		echo "disabled=\"disabled\" ";
	}

	echo ">\r\n            </div>\r\n          </div>\r\n\t\t  ";
}
else {
	echo "\t\t  \r\n\t\t     <div class=\"control-group formSep\">\r\n            <label class=\"control-label\" style=\"padding-top:0px\">创建时间</label>\r\n            <div class=\"controls\">\r\n              ";
	echo $site["addtime"];
	echo "            </div>\r\n          </div>\r\n\t\t\r\n\t\t  ";
}

echo "\t\t  \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">网站域名<span class=\"red_x\">*</span></label>\r\n            <div class=\"controls\">\r\n             http:// <input type=\"text\" name=\"siteurl\" id=\"siteurl\" class=\"input_text span30\" value=\"";
echo $site["siteurl"];
echo "\" ";

if (RUN_ACTION == "edit") {
	echo "disabled=\"disabled\" ";
}

echo ">\r\n            </div>\r\n          </div>\r\n\t\t  \r\n\t\t  <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">网站附属域名</label>\r\n            <div class=\"controls\">\r\n              <textarea name=\"pertainurl\" class=\"input_text span30\" id=\"pertainurl\" style=\"height:50px\">";
echo $site["pertainurl"];
echo "</textarea>\r\n\t\t\t<span>网站其它域名，\",\"分割符。\"*\"通配所有二级域名，比如*.ad.com</span>\r\n            </div>\r\n\t\t\t\r\n          </div>\r\n\t\t  \r\n\t\t  \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">网站名称<span class=\"red_x\">*</span></label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" name=\"sitename\" id=\"sitename\" class=\"input_text span30\" value=\"";
echo $site["sitename"];
echo "\">\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">网站备案信息</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" name=\"beian\" id=\"beian\" class=\"input_text span30\" value=\"";
echo $site["beian"];
echo "\">\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">星级</label>\r\n            <div class=\"controls\">\r\n              <input name=\"grade\" type=\"radio\" value=\"0\"   ";

if ($site["grade"] == 0) {
	echo "checked";
}

echo "/>\r\n              <img alt=\"\" src=\"";
echo SRC_TPL_DIR;
echo "/images/s0.jpg\" title=\"0\">\r\n              <input type=\"radio\" name=\"grade\" value=\"1\" ";

if ($site["grade"] == 1) {
	echo "checked";
}

echo "/>\r\n              <img alt=\"\" src=\"";
echo SRC_TPL_DIR;
echo "/images/s1.jpg\"  title=\"1 star\"/>\r\n              <input type=\"radio\" name=\"grade\" value=\"2\"";

if ($site["grade"] == 2) {
	echo "checked";
}

echo " />\r\n              <img alt=\"\" src=\"";
echo SRC_TPL_DIR;
echo "/images/s2.jpg\" title=\"2\"/>\r\n              <input type=\"radio\" name=\"grade\" value=\"3\" ";

if ($site["grade"] == 3) {
	echo "checked";
}

echo "/>\r\n              <img alt=\"\" src=\"";
echo SRC_TPL_DIR;
echo "/images/s3.jpg\" title=\"3 stars\"/> </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">网站类别</label>\r\n            <div class=\"controls\">\r\n              <select name=\"sitetype\" id=\"sitetype\">\r\n                <option value=\"\">请选择</option>\r\n                ";

foreach ((array) $sitetype as $st ) {
	echo "                <option value=\"";
	echo $st["classid"];
	echo "\" ";

	if ($site["sitetype"] == $st["classid"]) {
		echo "selected";
	}

	echo " >";
	echo $st["classname"];
	echo "</option>\r\n                ";
}

echo "              </select>\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">日访问量</label>\r\n            <div class=\"controls\">\r\n              <input name=\"dayip\" type=\"text\" class=\"input_text span30\" id=\"dayip\"   value=\"";
echo $site["dayip"];
echo "\" />\r\n              IP </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">网站描述</label>\r\n            <div class=\"controls\">\r\n              <textarea name=\"siteinfo\" class=\"input_text span30\" id=\"siteinfo\" style=\"height:50px\">";
echo $site["siteinfo"];
echo "</textarea>\r\n            </div>\r\n          </div>\r\n\t\t  \r\n\t\t  <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">其它信息</label>\r\n            <div class=\"controls\">\r\n              <textarea name=\"denyinfo\" class=\"input_text span30\" id=\"denyinfo\" style=\"height:50px\">";
echo $site["denyinfo"];
echo "</textarea>\r\n            </div>\r\n          </div>\r\n\t\t  \r\n\t\t  \r\n          <div class=\"control-group\" style=\"height:20px\">\r\n            <div class=\"controls\">\r\n              <button class=\"btn btn-gebo\" type=\"submit\">提交</button>\r\n            </div>\r\n          </div>\r\n        </form>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/form.js\"></script>\r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n $(document).ready(function(){\r\n \t\tremote_url = \"";
echo url("admin/user.remote_user?repeat=false");
echo "\";\r\n\t\t add_site_vlt.init();\r\n\t});\r\n\r\n\r\n</script>\r\n";

?>
