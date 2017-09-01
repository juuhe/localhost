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
echo url("admin/adstyle.get_list");
echo "\" style=\"width: 86px;\">返回列表</a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n          <li > <a href=\"#\" style=\"width: 97px;\">\r\n            ";
echo RUN_ACTION == "add" ? "新建" : "编辑";
echo "广告样式</a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n        </ul>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div class=\"row-fluid\">\r\n    <h3 class=\"heading\">\r\n      ";
echo RUN_ACTION == "add" ? "新建" : "编辑";
echo "      广告样式</h3>\r\n    <div class=\"row-fluid\">\r\n      <div class=\"span8\">\r\n        <form class=\"form-horizontal\" action=\"\r\n\t\t ";

if (RUN_ACTION == "add") {
	echo url("admin/adstyle.add_post");
}
else {
	echo url("admin/adstyle.update_post");
}

echo "\"  method=\"post\" id=\"form_b\" name=\"form_b\">\r\n          <input name=\"styleid\" id=\"styleid\" type=\"hidden\" value=\"";
echo $a["styleid"];
echo "\" />\r\n           <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">适用于广告样式\r\n </label>\r\n            <div class=\"controls\">\r\n             <!--  ";
$si = 1;

foreach ((array) $adtpl as $al ) {
	echo "               \t<input name=\"tplid[]\" type=\"checkbox\" value=\"";
	echo $al["tplid"];
	echo "\" ";

	if (in_array($al["tplid"], (array) $exp_tplid)) {
		echo "checked";
	}

	echo " /> ";
	echo $al["tplname"];
	echo "               ";

	if (($si % 7) == 0) {
		echo "<br><br>";
	}

	$si++;
}

echo "-->\r\n               \r\n                <select size=\"1\" name=\"tplid[]\" id=\"tplid\"  >\r\n                ";

foreach ((array) $adtpl as $at ) {
	echo "                <option value=\"";
	echo $at["tplid"];
	echo "\"  ";

	if (in_array($at["tplid"], (array) $exp_tplid)) {
		echo "selected";
	}

	echo ">";
	echo $at["tplname"];
	echo "</option>\r\n                ";
}

echo "              </select>\r\n              \r\n              \r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">样式名称</label>\r\n            <div class=\"controls\">\r\n              <input name=\"stylename\" type=\"text\" class=\"input_text span30\" id=\"stylename\"  value=\"";
echo $a["stylename"];
echo "\">\r\n            </div>\r\n          </div>\r\n          \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">广告数量</label>\r\n            <div class=\"controls\">\r\n              <input name=\"adnum\" type=\"text\" class=\"input_text span30\" id=\"adnum\"  value=\"";
echo $a["adnum"] != "" ? $a["adnum"] : "1";
echo "\">\r\n              <span>要显几个广告0为不限制 调取所有。</span> </div>\r\n          </div>\r\n        \r\n          \r\n          \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">附加HTML属性</label>\r\n            <div class=\"controls\">\r\n              <table style=\"width:100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"  class=\"c_f_i\">\r\n                <tr>\r\n                  <td><table  style=\"width:100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"  class=\"c_f_f\">\r\n                      <tr>\r\n                        <td>属性标题</td>\r\n                        <td>属性type</td>\r\n                        <td>属性name</td>\r\n                        <td>属性checked</td>\r\n                        <td>属性默认值</td>\r\n                      </tr>\r\n                      ";
$cp_num = 1;

if ($a["htmlcontrol"]) {
	$cp = (array) unserialize($a["htmlcontrol"]);
	$cp_num = count($cp["htmlcontrol_title"]);
}

for ($i = 0; $i < $cp_num; $i++) {
	echo "                      <tr>\r\n                        <td><div>\r\n                            <input name=\"htmlcontrol_title[]\" type=\"text\" class=\"input_text span30 \" style=\"width:90px\" value=\"";
	echo $cp["htmlcontrol_title"][$i];
	echo "\" />\r\n                          </div></td>\r\n                        <td><div>\r\n                            <select size=\"1\" name=\"htmlcontrol_type[]\" >\r\n                              <option value=\"text\" ";

	if ($cp["htmlcontrol_type"][$i] == "text") {
		echo "selected";
	}

	echo ">text</option>\r\n                              <option value=\"checkbox\" ";

	if ($cp["htmlcontrol_type"][$i] == "checkbox") {
		echo "selected";
	}

	echo ">checkbox</option>\r\n                              <option value=\"radio\" ";

	if ($cp["htmlcontrol_type"][$i] == "radio") {
		echo "selected";
	}

	echo ">radio</option>\r\n                            </select>\r\n                          </div></td>\r\n                        <td><div>\r\n                            <input name=\"htmlcontrol_name[]\" type=\"text\" class=\"input_text span30 \" style=\"width:50px\" value=\"";
	echo $cp["htmlcontrol_name"][$i];
	echo "\" />\r\n                          </div></td>\r\n                        <td><div>\r\n                            <input name=\"htmlcontrol_checked[]\" type=\"text\" class=\"input_text span30 \" style=\"width:80px\" value=\"";
	echo $cp["htmlcontrol_checked"][$i];
	echo "\" />\r\n                          </div></td>\r\n                        <td><div>\r\n                            <input name=\"htmlcontrol_value[]\" type=\"text\" class=\"input_text span30 \" style=\"width:120px\" value=\"";
	echo $cp["htmlcontrol_value"][$i];
	echo "\" />\r\n                            ";

	if (1 <= $i) {
		echo "                            <a href=\"javascript:;\" class=\"delbtn\"> 删</a>\r\n                            ";
	}

	echo "                          </div></td>\r\n                      </tr>\r\n                      ";
}

echo "                    </table></td>\r\n                  <td width=\"100\"><a href=\"javascript:;\" class=\"newbtn\">增加一行</a></td>\r\n                </tr>\r\n              </table>\r\n            </div>\r\n          </div>\r\n          \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">绑定尺寸<br> <br> <input name=\"select_id\" id=\"select_id\" type=\"checkbox\" value=\"\" />全选</label>\r\n            <div class=\"controls\">\r\n               ";
$si = 1;

foreach ((array) $specs as $sp ) {
	echo "               \t<input name=\"specs[]\" type=\"checkbox\" value=\"";
	echo $sp["width"] . "x" . $sp["height"];
	echo "\" ";

	if (in_array($sp["width"] . "x" . $sp["height"], (array) $exp_specs)) {
		echo "checked";
	}

	echo " /> ";
	echo $sp["width"] . "x" . $sp["height"];
	echo "               ";

	if (($si % 7) == 0) {
		echo "<br><br>";
	}

	$si++;
}

echo "            </div>\r\n          </div>\r\n         \r\n         \r\n         <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">view_js</label>\r\n            <div class=\"controls\">\r\n              <textarea name=\"viewjs\" class=\"input_text span30\" id=\"viewjs\" style=\"width:600px; height:200px\">";
echo $a["viewjs"];
echo "</textarea>\r\n            </div>\r\n          </div>\r\n          \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">样式代码</label>\r\n            <div class=\"controls\">\r\n              <textarea name=\"iframejs\" class=\"input_text span30\" id=\"iframejs\" style=\"width:600px; height:200px\">";
echo $a["iframejs"];
echo "</textarea>\r\n            </div>\r\n          </div>\r\n          \r\n             <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">描述说明</label>\r\n            <div class=\"controls\">\r\n              <input name=\"description\" type=\"text\" class=\"input_text span30\" id=\"description\"  value=\"";
echo $a["description"];
echo "\">\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group\" style=\"height:20px\">\r\n            <div class=\"controls\">\r\n              <button class=\"btn btn-gebo\" type=\"submit\">提交</button>\r\n            </div>\r\n          </div>\r\n        </form>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/form.js\"></script> \r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n $(document).ready(function(){\r\n\t \r\n\t $(\"#select_id\").click(function(){\r\n \t\t var a = $(\"#select_id\").attr(\"checked\");\r\n\t\tif(a!='checked') a = false;\r\n     \t$(\"input[name='specs[]']\").attr(\"checked\",a);\r\n\t });\r\n\t \r\n\t \r\n\t $(\".newbtn\").bind(\"click\", function(){\r\n\t \r\n\t  $(\".c_f_f\").append(' <tr> <td><div><input name=\"htmlcontrol_title[]\" type=\"text\" class=\"input_text span30 \" style=\"width:90px\" value=\"\" /></div></td><td><div><select size=\"1\" name=\"htmlcontrol_type[]\" ><option value=\"text\">text</option><option value=\"checkbox\">checkbox</option><option value=\"radio\" >radio</option></select></div></td><td><div><input name=\"htmlcontrol_name[]\" type=\"text\" class=\"input_text span30 \" style=\"width:50px\" value=\"\" /></div></td><td><div><input name=\"htmlcontrol_checked[]\" type=\"text\" class=\"input_text span30 \" style=\"width:80px\" value=\"\" /></div></td><td><div><input name=\"htmlcontrol_value[]\" type=\"text\" class=\"input_text span30 \" style=\"width:120px\" value=\"\" /><a href=\"javascript:;\" class=\"delbtn\"> 删</a></div></td></tr>');\r\n\t  \r\n\t   $(\".delbtn\").bind(\"click\", function(){  \r\n\t     $(this).parent().parent().parent().remove(); \r\n\t\t  \r\n\t   }); \r\n\t    \r\n\t }); \r\n\t \r\n\t \r\n\t $(\".delbtn\").bind(\"click\", function(){ \r\n\t     $(this).parent().parent().parent().remove(); \r\n\t\t  \r\n\t   }); \r\n\t\t \r\n });\r\n\t\r\n \r\n</script> \r\n";

?>
