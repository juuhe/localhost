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
echo "</div>\r\n<div id=\"main-content\">\r\n  \r\n  <div class=\"row-fluid\">\r\n    <h3 class=\"heading\">\r\n      ";
echo RUN_ACTION == "add" ? "发布分类" : "编辑分类";
echo "      </h3>\r\n    <div class=\"row-fluid\">\r\n      <div class=\"span8\">\r\n        <form class=\"form-horizontal\" action=\"\r\n\t\t ";

if (RUN_ACTION == "add") {
	echo url("admin/class.add_post");
}
else {
	echo url("admin/class.update_post");
}

echo "\"  method=\"post\" id=\"form_b\" name=\"form_b\">\r\n          <input name=\"classid\" id=\"classid\" type=\"hidden\" value=\"";
echo $c["classid"];
echo "\" />\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">分类名称</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" id=\"classname\" name=\"classname\" class=\"input_text span30\" value=\"";
echo $c["classname"];
echo "\"/>\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">类型</label>\r\n            <div class=\"controls\">\r\n              <select name=\"type\" id=\"type\">\r\n\t\t\t\t  <option  value=\"\">选择分类</option>\r\n\t\t\t\t  ";

foreach ($GLOBALS["class_type"] as $key => $val ) {
	echo "\t\t\t\t  <option value=\"";
	echo $key;
	echo "\" ";
	if (($c["type"] == $key) && $c) {
		echo "selected";
	}

	echo ">";
	echo $val;
	echo "</option>\r\n\t\t\t\t  ";
}

echo "\t\t\t\t</select>\r\n              <span></span> </div>\r\n          </div>\r\n          \r\n          <div class=\"control-group\" style=\"height:20px\">\r\n            <div class=\"controls\">\r\n              <button class=\"btn btn-gebo\" type=\"submit\">提交</button>\r\n            </div>\r\n          </div>\r\n        </form>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/form.js\"></script>\r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n $(document).ready(function(){\r\n\t\t add_class_vlt.init();\r\n});\r\n</script>\r\n";

?>
