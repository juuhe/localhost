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

echo "> \r\n  <!-- <a class=\"close\" href=\"javascript:;\">×</a>--> \r\n  <strong>保存成功.</strong> </div>\r\n<div id=\"sidebar\">\r\n  ";
TPL::display("sidebar");
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid\">\r\n    <h3 class=\"heading\">\r\n      ";
echo RUN_ACTION == "add" ? "新建角色" : "编辑角色";
echo "    </h3>\r\n    <div class=\"row-fluid\">\r\n      <div class=\"span8\">\r\n        <form class=\"form-horizontal\" action=\"\r\n\t\t ";

if (RUN_ACTION == "add") {
	echo url("admin/roles.add_post");
}
else {
	echo url("admin/roles.update_post");
}

echo "\"  method=\"post\" id=\"form_b\" name=\"form_b\">\r\n          <input name=\"id\" id=\"id\" type=\"hidden\" value=\"";
echo $r["id"];
echo "\" />\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">角色名称</label>\r\n            <div class=\"controls\">\r\n              <input type=\"text\" id=\"name\" name=\"name\" class=\"input_text span30\" value=\"";
echo $r["name"];
echo "\"/>\r\n            </div>\r\n          </div>\r\n          <h3 class=\"heading\">权限设置\r\n            <input type=\"checkbox\" name=\"select_id\" id=\"select_id\"> <span id=\"acl_error\"></span>\r\n          </h3>\r\n          ";

foreach ((array) $ctl as $key => $val ) {
	$a = explode(",", $val["action"]);
	echo "          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">";
	echo $key;
	echo "</label>\r\n            <div class=\"controls\">\r\n              ";

	foreach ((array) $a as $s ) {
		$action = $val["controller"] . "." . $s;
		echo "              <input name=\"acl[]\" type=\"checkbox\" value=\"";
		echo $action;
		echo "\" ";

		if (in_array($action, (array) $acl)) {
			echo "checked";
		}

		echo "/>\r\n              ";
		echo $ac[$s];
		echo "              ";
	}

	echo "            </div>\r\n          </div>\r\n          ";
}

echo "          <div class=\"control-group\" style=\"height:20px\">\r\n            <div class=\"controls\">\r\n              <button class=\"btn btn-gebo\" type=\"submit\">提交</button>\r\n            </div>\r\n            <br>\r\n          </div>\r\n        </form>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/form.js\"></script> \r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n $(document).ready(function(){\r\n \t\tadd_roles_vlt.init();\r\n\t\t $(\"#select_id\").click(function(){\r\n\t\t\t var a = $(\"#select_id\").attr(\"checked\");\r\n\t\t\t if(a!='checked') a = false;\r\n\t\t\t $(\"input[name='acl[]']\").attr(\"checked\",a);\r\n\t\t});\r\n});\r\n</script> \r\n";

?>
