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
echo "</div>\r\n<div id=\"main-content\">\r\n  <div id=\"main_nav\">\r\n    <div class=\"mn_lfet\"></div>\r\n    <div class=\"mn_right\">\r\n      <div class=\"mn_mt\">\r\n        <ul >\r\n          <li > <a href=\"";
echo url("admin/adtpl.get_list");
echo "\" style=\"width: 86px;\">返回列表</a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n          <li > <a href=\"#\" style=\"width: 97px;\">\r\n            ";
echo RUN_ACTION == "add" ? "新建" : "编辑";
echo "            广告模式</a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n        </ul>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div class=\"row-fluid\">\r\n    <h3 class=\"heading\">\r\n      ";
echo RUN_ACTION == "add" ? "新建" : "编辑";
echo "      广告模式</h3>\r\n    <div class=\"row-fluid\">\r\n      <div class=\"span8\">\r\n        <form class=\"form-horizontal\" action=\"\r\n\t\t ";

if (RUN_ACTION == "add") {
	echo url("admin/adtpl.add_post");
}
else {
	echo url("admin/adtpl.update_post");
}

echo "\"  method=\"post\" id=\"form_b\" name=\"form_b\">\r\n          <input name=\"tplid\" id=\"tplid\" type=\"hidden\" value=\"";
echo $a["tplid"];
echo "\" />\r\n          <div class=\"control-group formSep st\">\r\n            <label class=\"control-label\">广告类型</label>\r\n            <div class=\"controls\"  >\r\n              <select size=\"1\" name=\"adtypeid\" id=\"adtypeid\"  >\r\n                ";

foreach ((array) $adtype as $at ) {
	echo "                <option value=\"";
	echo $at["id"];
	echo "\"  ";

	if ($a["adtypeid"] == $at["id"]) {
		echo "selected";
	}

	echo ">";
	echo $at["name"];
	echo "</option>\r\n                ";
}

echo "              </select>\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">广告模式</label>\r\n            <div class=\"controls\">\r\n              <input name=\"tplname\" type=\"text\" class=\"input_text span30\" id=\"tplname\"  value=\"";
echo $a["tplname"];
echo "\">\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">加载方式</label>\r\n            <div class=\"controls\">\r\n             <input name=\"tpltype\" type=\"radio\" value=\"script_iframe\" ";

if ($a["tpltype"] == "script_iframe") {
	echo "checked";
}

echo "/>\r\n              SCRIPT_IFRAME\r\n              <input name=\"tpltype\" type=\"radio\" value=\"script\" ";

if ($a["tpltype"] == "script") {
	echo "checked";
}

echo "/>\r\n              SCRIPT\r\n              <input name=\"tpltype\" type=\"radio\" value=\"iframe\" ";

if ($a["tpltype"] == "iframe") {
	echo "checked";
}

echo "/>\r\n              IFRAME \r\n              <input name=\"tpltype\" type=\"radio\" value=\"url_jump\" ";

if ($a["tpltype"] == "url_jump") {
	echo "checked";
}

echo "/>\r\n              URL_JUMP </div>\r\n          </div>\r\n         \r\n         \r\n           <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">广告位自定义尺寸</label>\r\n            <div class=\"controls\">\r\n             <input name=\"customspecs\" type=\"radio\" value=\"1\" ";

if ($a["customspecs"] == "1") {
	echo "checked";
}

echo "/>\r\n              不可以\r\n              <input name=\"customspecs\" type=\"radio\" value=\"2\" ";

if ($a["customspecs"] == "2") {
	echo "checked";
}

echo "/>\r\n              可以\r\n               </div>\r\n          </div>\r\n          \r\n             <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">广告位配色</label>\r\n            <div class=\"controls\">\r\n             <input name=\"customcolor\" type=\"radio\" value=\"1\" ";

if ($a["customcolor"] == "1") {
	echo "checked";
}

echo "/>\r\n              不需要\r\n              <input name=\"customcolor\" type=\"radio\" value=\"2\" ";

if ($a["customcolor"] == "2") {
	echo "checked";
}

echo "/>\r\n              需要\r\n               </div>\r\n          </div>\r\n          \r\n          \r\n            <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">排序</label>\r\n            <div class=\"controls\">\r\n              <input name=\"sort\" type=\"text\" class=\"input_text span30\" id=\"sort\"  value=\"";
echo $a["sort"];
echo "\"><span>1~100数字</span>\r\n               </div>\r\n          </div>\r\n          \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">需要HTML控件</label>\r\n            <div class=\"controls\">\r\n              <table style=\"width:100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"  class=\"c_f_i\">\r\n                <tr>\r\n                  <td><table  style=\"width:100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"  class=\"c_f_f\">\r\n                      <tr>\r\n                        <td>控件文字</td>\r\n                        <td>控件type</td>\r\n                        <td>控件name</td>\r\n                        <td>控件id</td>\r\n                        <td>控件说明</td>\r\n                      </tr>\r\n                      ";
$cp_num = 1;

if ($a["htmlcontrol"]) {
	$cp = (array) unserialize($a["htmlcontrol"]);
	$cp_num = count($cp["htmlcontrol_title"]);
}

for ($i = 0; $i < $cp_num; $i++) {
	echo "                      <tr>\r\n                        <td><div>\r\n                            <input name=\"htmlcontrol_title[]\" type=\"text\" class=\"input_text span30 \" style=\"width:90px\" value=\"";
	echo $cp["htmlcontrol_title"][$i];
	echo "\" />\r\n                          </div></td>\r\n                        <td><div>\r\n                            <select size=\"1\" name=\"htmlcontrol_type[]\" >\r\n                              \r\n                              <option value=\"text\" ";

	if ($cp["htmlcontrol_type"][$i] == "text") {
		echo "selected";
	}

	echo ">text</option>\r\n                              <option value=\"file\" ";

	if ($cp["htmlcontrol_type"][$i] == "file") {
		echo "selected";
	}

	echo " >file</option>\r\n                              <option value=\"checkbox\" ";

	if ($cp["htmlcontrol_type"][$i] == "checkbox") {
		echo "selected";
	}

	echo ">checkbox</option>\r\n                              <option value=\"radio\" ";

	if ($cp["htmlcontrol_type"][$i] == "radio") {
		echo "selected";
	}

	echo ">radio</option>\r\n                            </select>\r\n                          </div></td>\r\n                        <td><div>\r\n                            <input name=\"htmlcontrol_name[]\" type=\"text\" class=\"input_text span30 \" style=\"width:80px\" value=\"";
	echo $cp["htmlcontrol_name"][$i];
	echo "\" />\r\n                          </div></td>\r\n                        <td><div>\r\n                            <input name=\"htmlcontrol_id[]\" type=\"text\" class=\"input_text span30 \" style=\"width:50px\" value=\"";
	echo $cp["htmlcontrol_id"][$i];
	echo "\" />\r\n                          </div></td>\r\n                        <td><div>\r\n                            <input name=\"htmlcontrol_span[]\" type=\"text\" class=\"input_text span30 \" style=\"width:120px\" value=\"";
	echo $cp["htmlcontrol_span"][$i];
	echo "\" />\r\n                            ";

	if (1 <= $i) {
		echo "                            <a href=\"javascript:;\" class=\"delbtn\"> 删</a>\r\n                            ";
	}

	echo "                          </div></td>\r\n                      </tr>\r\n                      ";
}

echo "                    </table></td>\r\n                  <td width=\"100\"><a href=\"javascript:;\" class=\"newbtn\">增加一行</a></td>\r\n                </tr>\r\n              </table>\r\n            </div>\r\n          </div>\r\n         \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">view_js</label>\r\n            <div class=\"controls\">\r\n              <textarea name=\"viewjs\" class=\"input_text span30\" id=\"viewjs\" style=\"width:600px; height:200px\">";
echo $a["viewjs"];
echo "</textarea>\r\n            </div>\r\n          </div>\r\n           <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">样式代码</label>\r\n            <div class=\"controls\">\r\n              <textarea name=\"iframejs\" class=\"input_text span30\" id=\"iframejs\" style=\"width:600px; height:200px\">";
echo $a["iframejs"];
echo "</textarea>\r\n           <br> <br>会被追加到广告样式的样式代码后面</div>\r\n          </div>\r\n          \r\n          \r\n          <div class=\"control-group\" style=\"height:20px\">\r\n            <div class=\"controls\">\r\n              <button class=\"btn btn-gebo\" type=\"submit\">提交</button>\r\n            </div>\r\n          </div>\r\n        </form>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/form.js\"></script> \r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n $(document).ready(function(){\r\n\t \r\n\t $(\"#select_id\").click(function(){\r\n \t\t var a = $(\"#select_id\").attr(\"checked\");\r\n\t\tif(a!='checked') a = false;\r\n     \t$(\"input[name='specs[]']\").attr(\"checked\",a);\r\n\t });\r\n\t \r\n\t \r\n\t  $(\".newbtn\").bind(\"click\", function(){\r\n\t \r\n\t  $(\".c_f_f\").append(' <tr> <td><div><input name=\"htmlcontrol_title[]\" type=\"text\" class=\"input_text span30 \" style=\"width:90px\" value=\"\" /></div></td><td><div><select size=\"1\" name=\"htmlcontrol_type[]\" ><option value=\"text\">text</option><option value=\"file\" >file</option><option value=\"checkbox\">checkbox</option><option value=\"radio\" >radio</option></select></div></td><td><div><input name=\"htmlcontrol_name[]\" type=\"text\" class=\"input_text span30 \" style=\"width:80px\" value=\"\" /></div></td><td><div><input name=\"htmlcontrol_id[]\" type=\"text\" class=\"input_text span30 \" style=\"width:50px\" value=\"\" /></div></td><td><div><input name=\"htmlcontrol_span[]\" type=\"text\" class=\"input_text span30 \" style=\"width:120px\" value=\"\" /><a href=\"javascript:;\" class=\"delbtn\"> 删</a></div></td></tr>');\r\n\t  \r\n\t   $(\".delbtn\").bind(\"click\", function(){\r\n\t     $(this).parent().parent().parent().remove(); \r\n\t\t  \r\n\t   }); \r\n\t    \r\n\t }); \r\n\t \r\n\t \r\n\t \r\n $(\".delbtn\").bind(\"click\", function(){\r\n\t     $(this).parent().parent().parent().remove(); \r\n\t\t  \r\n\t   }); \r\n\t\t \r\n });\r\n\t\r\n \r\n</script> \r\n";

?>
