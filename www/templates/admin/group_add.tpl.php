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
echo RUN_ACTION == "add" ? "新建站长用户组" : "编辑站长用户组";
echo "      </h3>\r\n    <div class=\"row-fluid\">\r\n      <div class=\"span8\">\r\n        <form class=\"form-horizontal\" action=\"\r\n\t\t ";

if (RUN_ACTION == "add") {
	echo url("admin/group.add_post");
}
else {
	echo url("admin/group.update_post");
}

echo "\"  method=\"post\" id=\"form_b\" name=\"form_b\">\r\n          <input name=\"groupid\" id=\"groupid\" type=\"hidden\" value=\"";
echo $g["groupid"];
echo "\" />\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">用户组名称</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" id=\"groupname\" name=\"groupname\" class=\"input_text span30\" value=\"";
echo $g["groupname"];
echo "\"/>\r\n            </div>\r\n          </div>\r\n          \r\n          <div class=\"control-group\" style=\"height:20px\">\r\n            <div class=\"controls\">\r\n              <button class=\"btn btn-gebo\" type=\"submit\">提交</button>\r\n            </div>\r\n          </div>\r\n        </form>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/form.js\"></script>\r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n $(document).ready(function(){\r\n\t\t add_group_vlt.init();\r\n});\r\n</script>\r\n";

?>
