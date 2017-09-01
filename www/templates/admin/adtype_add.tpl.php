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
echo url("admin/adtype.get_list");
echo "\" style=\"width: 86px;\">返回列表</a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n          <li > <a href=\"#\" style=\"width: 97px;\">\r\n            ";
echo RUN_ACTION == "add" ? "新建" : "编辑";
echo "广告类型</a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n        </ul>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div class=\"row-fluid\">\r\n    <h3 class=\"heading\">\r\n      ";
echo RUN_ACTION == "add" ? "新建" : "编辑";
echo "广告类型</h3>\r\n    <div class=\"row-fluid\">\r\n      <div class=\"span8\">\r\n        <form class=\"form-horizontal\" action=\"\r\n\t\t ";

if (RUN_ACTION == "add") {
	echo url("admin/adtype.add_post");
}
else {
	echo url("admin/adtype.update_post");
}

echo "\"  method=\"post\" id=\"form_b\" name=\"form_b\">\r\n          <input name=\"id\" id=\"id\" type=\"hidden\" value=\"";
echo $a["id"];
echo "\" />\r\n          <div class=\"control-group formSep st\">\r\n            <label class=\"control-label\">计费模式</label>\r\n            <div class=\"controls\" style=\"width:420px\">\r\n              ";

foreach (explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
	echo "              <input name=\"statstype[]\" type=\"checkbox\" class=\"statstype\"  value=\"";
	echo $t;
	echo "\" ";
	echo in_array($t, explode(",", $a["statstype"])) ? " checked" : "";
	echo "/>\r\n              ";
	echo strtoupper($t);
}

echo " </div> \r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">广告类型名称</label>\r\n            <div class=\"controls\">\r\n              <input name=\"name\" type=\"text\" class=\"input_text span30\" id=\"name\"  value=\"";
echo $a["name"];
echo "\">\r\n            </div>\r\n          </div>\r\n           <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">排序</label>\r\n            <div class=\"controls\">\r\n              <input name=\"sort\" type=\"text\" class=\"input_text span30\" id=\"sort\"  value=\"";
echo $a["sort"];
echo "\"><span>1~100数字</span>\r\n            </div>\r\n          </div>\r\n         <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">说明</label>\r\n            <div class=\"controls\">\r\n              <input name=\"description\" type=\"text\" class=\"input_text span30\" id=\"description\"  value=\"";
echo $a["description"];
echo "\">\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group\" style=\"height:20px\">\r\n            <div class=\"controls\">\r\n              <button class=\"btn btn-gebo\" type=\"submit\">提交</button>\r\n            </div>\r\n          </div>\r\n        </form>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/form.js\"></script>\r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n $(document).ready(function(){\r\n\t\t add_adtpl_vlt.init();\r\n\t\t $(\".htmlcontrol\").on('click', function(option) {\r\n\t\t \t\t \r\n\t\t \t\tvar v = $(this).attr(\"value\");\r\n\t\t\t\tif(v == 'htmlcode'){\r\n\t\t\t\t\tvar a = $(this).attr(\"checked\");\r\n\t\t\t\t\tif(a!='checked'){\r\n\t\t\t\t\t\t$(\".htmltemplate\").show(\"slow\");\r\n\t\t\t\t\t}else{\r\n\t\t\t\t\t\t$(\".htmltemplate\").hide(\"slow\");\r\n\t\t\t\t\t}\r\n\t\t\t\t}\r\n\t\t  });\r\n\t});\r\n\t\r\n \r\n</script>\r\n";

?>
