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
echo url("admin/specs.get_list");
echo "\" style=\"width: 86px;\">返回列表</a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n          <li > <a href=\"#\" style=\"width: 97px;\">\r\n            ";
echo RUN_ACTION == "add" ? "新建" : "编辑";
echo "广告尺寸</a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n        </ul>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div class=\"row-fluid\">\r\n    <h3 class=\"heading\">\r\n      ";
echo RUN_ACTION == "add" ? "新建" : "编辑";
echo "广告尺寸</h3>\r\n    <div class=\"row-fluid\">\r\n      <div class=\"span8\">\r\n        <form class=\"form-horizontal\" action=\"\r\n\t\t ";

if (RUN_ACTION == "add") {
	echo url("admin/specs.add_post");
}
else {
	echo url("admin/specs.update_post");
}

echo "\"  method=\"post\" id=\"form_b\" name=\"form_b\">\r\n          <input name=\"id\" id=\"id\" type=\"hidden\" value=\"";
echo $s["id"];
echo "\" />\r\n          \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">宽度</label>\r\n            <div class=\"controls\">\r\n              <input name=\"width\" type=\"text\" class=\"input_text span30\" id=\"width\"  value=\"";
echo $s["width"];
echo "\">\r\n            </div>\r\n          </div>\r\n          \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">高度</label>\r\n            <div class=\"controls\">\r\n              <input name=\"height\" type=\"text\" class=\"input_text span30\" id=\"height\"  value=\"";
echo $s["height"];
echo "\">\r\n            </div>\r\n          </div>\r\n          \r\n           <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">排序</label>\r\n            <div class=\"controls\">\r\n              <input name=\"sort\" type=\"text\" class=\"input_text span30\" id=\"sort\"  value=\"";
echo $s["sort"];
echo "\"><span>1~100数字</span>\r\n            </div>\r\n          </div>\r\n       \r\n          <div class=\"control-group\" style=\"height:20px\">\r\n            <div class=\"controls\">\r\n              <button class=\"btn btn-gebo\" type=\"submit\">提交</button>\r\n            </div>\r\n          </div>\r\n        </form>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/form.js\"></script>\r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n add_specs_vlt.init();\r\n \r\n</script>\r\n";

?>
