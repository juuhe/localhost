<?php

if (!defined("IN_ZYADS")) {
	exit();
}

TPL::display("header");
echo "<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/lib/jquery-validation/additional-methods.js\"></script>\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/plan.js\"></script>\r\n<link rel=\"stylesheet\" href=\"";
echo SRC_TPL_DIR;
echo "/css/rating.css\" media=\"all\" type=\"text/css\" />\r\n\r\n<div class=\"alert success\" ";

if (!$_SESSION["succ"]) {
	echo "style=\"display:none\"";
}

echo "> \r\n  <!-- <a class=\"close\" href=\"javascript:;\">×</a>--> \r\n  <strong>保存成功.</strong> </div>\r\n<div id=\"sidebar\">\r\n  ";
TPL::display("sidebar");
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid\">\r\n    <h3 class=\"heading tab_heading\">\r\n      ";
echo RUN_ACTION == "add" ? "新建" : "编辑";
echo "      广告</h3>\r\n    <div class=\"row-fluid\">\r\n      <div class=\"span8\">\r\n        <form action=\"\r\n\t\t ";

if (RUN_ACTION == "add") {
	echo url("admin/ad.add_post");
}
else {
	echo url("admin/ad.update_post");
}

echo "\"  method=\"post\" enctype=\"multipart/form-data\" name=\"form_b\" class=\"form-horizontal\" id=\"form_b\"  >\r\n          <input name=\"adsid\" id=\"adsid\" type=\"hidden\" value=\"";
echo $ads["adsid"];
echo "\" />\r\n          <input name=\"height\" id=\"height\" type=\"hidden\" value=\"";
echo $ads["height"];
echo "\" />\r\n          <input name=\"width\" id=\"width\" type=\"hidden\" value=\"";
echo $ads["width"];
echo "\" />\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">所属广告计划</label>\r\n            <div class=\"controls\">\r\n              ";

if (RUN_ACTION == "add") {
	echo "              <select name=\"planid\" id=\"planid\" >\r\n                <option value=\"\"> 请选择一个计划 </option>\r\n                ";

	foreach (explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
		echo "                <optgroup  label=\"";
		echo strtoupper($t);
		echo "\">\r\n                ";

		foreach ((array) $plan as $p ) {
			if ($p["plantype"] !== $t) {
				continue;
			}

			echo "                <option value=\"";
			echo $p["planid"];
			echo "\" ";
			if (($p["planid"] == $ads["planid"]) || ($p["planid"] == request("planid"))) {
				echo "selected";
			}

			echo ">&nbsp;";
			echo $p["planname"];
			echo "&nbsp;</option>\r\n                ";
		}

		echo "                </optgroup>\r\n                ";
	}

	echo "              </select>\r\n              <span id=\"u_text\">属于哪个计划名下的广告</span>\r\n              ";
}
else {
	echo "              <select name=\"planid\" id=\"planid\" disabled>\r\n                <option value=\"";
	echo $plan["planid"];
	echo "\" >&nbsp;";
	echo $plan["planname"];
	echo "&nbsp;</option>\r\n              </select>\r\n              ";
}

echo "            </div>\r\n          </div>\r\n          \r\n            <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">广告名称</label>\r\n            <div class=\"controls\">\r\n              <input name=\"adname\" type=\"text\" id=\"adname\"    class=\"input_text span30\" value=\"";
echo $ads["adname"] ? $ads["adname"] : "创建于" . DATETIMES;
echo "\" />\r\n            </div>\r\n          </div>\r\n          \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">广告类型</label>\r\n            <div class=\"controls\">\r\n              ";

if (RUN_ACTION == "add") {
	echo "              <select name=\"adtplid\" id=\"adtplid\" >\r\n                <option value=\"\"> 请选择一个 </option>\r\n              </select>\r\n              ";
}
else {
	echo "              <select name=\"adtplid\" id=\"adtplid\" disabled>\r\n                <option value=\"";
	echo $tpl["tplid"];
	echo "\" >&nbsp;";
	echo $tpl["tplname"];
	echo " --- ";
	echo $tpl["name"];
	echo "&nbsp;</option>\r\n              </select>\r\n              ";
}

echo "            </div>\r\n          </div>\r\n          \r\n         \r\n          ";
if ((RUN_ACTION == "edit") && $ads["width"]) {
	echo "          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">广告尺寸</label>\r\n            <div class=\"controls\">\r\n              <select disabled>\r\n                <option value=\"\" >&nbsp;";
	echo $ads["width"] . "x" . $ads["height"];
	echo "&nbsp;</option>\r\n              </select>\r\n            </div>\r\n          </div>\r\n          ";
}

echo "          <div id=\"wg\">\r\n            ";

for ($i = 0; $i < sizeof($htmlcontrol["htmlcontrol_title"]); $i++) {
	$ah = " <div class=\"control-group formSep adform add_div\"  > \r\n\t\t\t\t\t\t\t  <label class=\"control-label\">" . $htmlcontrol["htmlcontrol_title"][$i] . "</label> \r\n\t\t\t\t\t\t\t <div class=\"controls\">";
	$iname = $htmlcontrol["htmlcontrol_name"][$i];
	$ivalue = $ads[$iname];

	switch ($htmlcontrol["htmlcontrol_type"][$i]) {
	case "text":
		if (substr($iname, 0, 3) == "zd[") {
			preg_match_all("/(?:\[)(.*)(?:\])/i", $iname, $result);
			$ivalue = $zd_htmlcontrol[$result[1][0]];
		}

		$ah .= " <input class=\"input_text span30\" type = \"text\" name=\"" . $iname . "\" value=\"" . $ivalue . "\">";
		break;

	case "file":
		$ah .= " <input type=\"radio\" name=\"files\" value=\"up\" ";
		if (!$ads || ($ads["files"] == "up")) {
			$ah .= "checked";
		}

		$ah .= ">上传图片 <input type=\"radio\" name=\"files\" value=\"url\"  ";

		if ($ads["files"] == "url") {
			$ah .= "checked";
		}

		$ah .= ">远程文件";
		$ah .= "<br><br><span id=\"_up\"";

		if ($ads["files"] == "url") {
			$ah .= "style=\"display:none\"";
		}

		$ah .= "><input type = \"file\" class=\"input_text span30\" name=\"imageurl\" > " . $htmlcontrol["htmlcontrol_span"][$i] . "</span> <span id=\"_url\" ";

		if ($ads["files"] == "up") {
			$ah .= "style=\"display:none\"";
		}

		$ah .= "><input type=\"text\" name=\"urlfile\"  id=\"urlfile\" class=\"input_text span30\" value=" . $ads["imageurl"] . " > 远程绝对地址支持JPG,GIF,PNG,SWF,HTML（支持HTML格式文件）</span>";
		break;
	}

	$ah .= "</div></div>";
	echo $ah;
}

echo "          </div>\r\n          \r\n           <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">广告权重</label>\r\n            <div class=\"controls\">\r\n              <input name=\"priority\" type=\"text\" id=\"priority\"  size=\"30\" maxlength=\"4\" class=\"input_text span30\" value=\"";
echo $ads["priority"] ? $ads["priority"] : 1;
echo "\" /><span>1~10数字，默认为1，权重越高，显示的机率越大</span>\r\n            </div>\r\n          </div>\r\n          \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">广告描述</label>\r\n            <div class=\"controls\">\r\n              <input name=\"adinfo\" type=\"text\" id=\"adinfo\"  size=\"30\"  class=\"input_text span30\" value=\"";
echo $ads["adinfo"];
echo "\" />\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group\" style=\"height:50px; margin-bottom:30px\">\r\n            <div class=\"controls\">\r\n              <button class=\"btn bnt51a35\" type=\"submit\" id=\"f_submit\" >提交</button>\r\n            </div>\r\n          </div>\r\n        </form>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  \r\n  ";

if ($ads["imageurl"]) {
	if ($ads["width"] == 0) {
		$ads["width"] = 200;
	}

	if ($ads["height"] == 0) {
		$ads["height"] = 200;
	}

	echo "  <div class=\"row-fluid\">\r\n    <h3 class=\"heading tab_heading\">素材预览</h3>\r\n    \r\n    <iframe width=\"";
	echo $ads["width"];
	echo "\" height=\"";
	echo $ads["height"];
	echo "\" frameborder=\"0\" src=\"";
	echo url("admin/ad.view_ad?adsid=" . $ads["adsid"]);
	echo "\" marginwidth=\"0\" marginheight=\"0\" vspace=\"0\" hspace=\"0\" allowtransparency=\"true\" scrolling=\"no\"></iframe> \r\n          \r\n  </div>\r\n   ";
}

echo "</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/form.js\"></script> \r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n$(document).ready(function() {\r\n \t\r\nadd_ad_vlt.init();\r\n";

if (RUN_ACTION == "add") {
	echo "\t\r\ndev = 1;\r\nget_adtype(); \r\n";
}
else {
	echo "dev = 0;\r\n";
}

echo "$(\"#planid\").change(function () {  \r\n\tget_adtype();\r\n});\r\n\r\n\r\n$(\"#adtplid\").change(function () {  \r\n\tget_adtpl();\r\n\tadd_ad_vlt.init();\r\n});\r\n\r\n\r\nfunction get_adtype(){\r\n\t$(\".add_div\").remove();\r\n\t $.ajax({  \r\n        type: \"POST\",  \r\n        dataType: \"json\",  \r\n        url: \"";
echo url("admin/ad.get_adtype");
echo "\",  \r\n        data: { planid: $(\"#planid\").val()},  \r\n        success: function (data) {\r\n\t\t\tif(data){ \r\n\t\t\t\t var ck,oh;\r\n\t\t\t\t $(\"#adtplid\").empty();\r\n\t\t\t\t $(\"<option value=''>请选择一个</option>\").appendTo(\"#adtplid\"); \r\n\t\t\t\t $.each(data, function (i, n) {   \r\n\t\t\t\t\tif(n.tpl){\r\n\t\t\t\t\t\toh = \"<optgroup label='\" + n.name + \"'>\";\r\n\t\t\t\t\t\t$.each(n.tpl, function (oi,on) { \r\n\t\t\t\t\t\t\t oh += \"<option value=\" + on.tplid + \" \"+ck+\">\" + on.name + \"</option>\";\r\n\t\t\t\t\t\t})\r\n\t\t\t\t\t\toh += \"</optgroup>\";\r\n\t\t\t\t\t\t$(oh).appendTo(\"#adtplid\");  }\r\n\t\t\t\t });   \r\n\t\t\t}  \t\r\n        }  \r\n     });   \r\n \t\r\n}\r\n\r\n\r\nfunction get_adtpl(){\r\n\t var v =  $(\"#adtplid\").val(),ah; \r\n\t  $(\".add_div\").remove();\r\n\t $.ajax({  \r\n        type: \"POST\",  \r\n        dataType: \"json\",  \r\n        url: \"";
echo url("admin/ad.get_adtpl");
echo "\",  \r\n        data: { tplid: $(\"#adtplid\").val()},  \r\n        success: function (data) {\r\n\t\t\tif(data){  \r\n\t\t\t\tvar hl = data.htmlcontrol;  \r\n\t\t\t\t\r\n\t\t\t\tif(data.specs[0]['specs'] &&　data.customspecs  ==1){\r\n\t\t\t\t\t \tah += ' <div class=\"control-group formSep adform add_div\" id=\"dv_specs\">'+\r\n\t\t\t\t\t\t\t  ' <label class=\"control-label\">显示尺寸</label>'+\r\n\t\t\t\t\t\t\t  ' <div class=\"controls\">'+\r\n\t\t\t\t\t\t\t  '<select name=\"specs\" id=\"specs\" ";

if (RUN_ACTION == "edit") {
	echo "disabled";
}

echo ">';\r\n\t\t\t\t\t\t\t\t $.each(data.specs , function (k, v) {\r\n\t\t\t\t\t\t\t\t\t ah +=' <option value=\"'+v.specs+'\"';\t\t \r\n\t\t\t\t\t\t\t\t\t ah += '>'+v.specs+' &nbsp;&nbsp;'+v.stylename+'</option>'; \r\n\t\t\t\t\t\t\t\t });\r\n\t\t\t\t\t\t\t\t ah +='</select></div></div>'; \r\n\t\t\t\t }\r\n\t\t\t\t \r\n\t\t\t\t $.each(hl.htmlcontrol_title, function (i, h) {   \r\n\t\t\t\t \r\n\t\t\t\t  \r\n\t\t\t\t\t  ah += ' <div class=\"control-group formSep adform add_div\"  >'+\r\n\t\t\t\t\t\t\t' <label class=\"control-label\">'+h+'</label>'+\r\n\t\t\t\t\t\t\t' <div class=\"controls\">';\r\n\t\t\t\t\t  switch (hl.htmlcontrol_type[i] ) {\r\n\t\t\t\t\t\t  case 'text': \r\n\t\t\t\t\t\t    ah += ' <input class=\"input_text span30\" type = \"text\" name=\"'+hl.htmlcontrol_name[i]+'\">';\r\n\t\t\t\t\t\t\tbreak;\r\n\t\t\t\t\t\t case 'radio':  \t\t\t\t\t\t \t \r\n\t\t\t\t\t\t\tvar gname = hl.htmlcontrol_name[i].split(\"=\");\t\t\t\t\t\r\n\t\t\t\t\t\t\tvar tname = gname[1].split(\",\"); \r\n\t\t\t\t\t\t    for (key in tname ) { \r\n\t\t\t\t\t\t\t\tvar rav = tname[key].split(\"|\");\r\n\t\t\t\t\t\t\t \tah += ' <input type = \"radio\" value=\"'+rav[1]+'\" name=\"'+gname[0]+'\">'+rav[0];\r\n\t\t\t\t\t\t\t}\r\n\t\t\t\t\t\t\tbreak;\r\n\t\t\t\t\t\t\t\r\n\t\t\t\t\t\t case 'checkbox':  \t\t\t\t\t\t \t \r\n\t\t\t\t\t\t\tvar gname = hl.htmlcontrol_name[i].split(\"=\");\t\t\t\t\t\r\n\t\t\t\t\t\t\tvar tname = gname[1].split(\",\"); \r\n\t\t\t\t\t\t    for (key in tname ) { \r\n\t\t\t\t\t\t\t\tvar rav = tname[key].split(\"|\");\r\n\t\t\t\t\t\t\t \tah += ' <input type = \"checkbox\" value=\"'+rav[1]+'\" name=\"'+gname[0]+'\">'+rav[0];\r\n\t\t\t\t\t\t\t}\r\n\t\t\t\t\t\t\tbreak;\r\n\t\t\t\t\t\t\t\t\r\n\t\t\t\t\t\t case 'file': \r\n\t\t\t\t\t\t    ah += ' <input type=\"radio\" name=\"files\" value=\"up\"  checked>上传文件 <input type=\"radio\" name=\"files\" value=\"url\"  >远程文件';\r\n\t\t\t\t\t\t\t//ah += '<i id=\"files_rand\"  \"><input type=\"radio\" name=\"files\" value=\"rand\">图库随机</i>';\r\n\t\t\t\t\t\t\tah +='<br><br><span id=\"_up\"><input type = \"file\" class=\"input_text span30\" name=\"imageurl\" > '+hl.htmlcontrol_span[i]+'</span> <span id=\"_url\" style=\"display:none\"><input type=\"text\" name=\"urlfile\"  id=\"urlfile\" class=\"input_text span30\" > 远程绝对地址支持JPG,GIF,PNG,SWF,HTML（支持HTML格式文件 ）</span>';\r\n\t\t\t\t\t\t\tbreak;\t\r\n\t\t\t\t\t  }\r\n\t\t\t\t\t  ah +='</div></div>';\r\n\t\t\t\t });    \r\n\t\t\t\t\r\n\t\t\t\t $(ah).appendTo(\"#wg\");  \r\n\t\t\t\t \r\n\t\t\t\t files(); \r\n\t\t\t\t\r\n\t\t\t}  \t\r\n        }  \r\n     });   \r\n\t \r\n\t  add_ad_vlt.init();\t     \r\n \t\r\n}\r\n \r\n \r\n \r\n function files(){\r\n\t\r\n\t  $('input:radio[name=files]').on('click', function(option) {\r\n                       \r\n                        var v = $(this).val(); \r\n                        if (v == 'up') {\r\n                            $('#_up').show();\r\n                            $('#_url').hide();\r\n                        } else if (v == 'url') {\r\n                            $('#_up').hide();\r\n                            $('#_url').show();\r\n                        } else {\r\n                            $('#_up').hide();\r\n                            $('#_url').hide();\r\n\t\t\t\t\t\t}\r\n                  });\r\n  } \r\n files(); \r\n \r\n \r\n});\r\n\r\n\r\n\r\n\r\n</script> \r\n";

?>
