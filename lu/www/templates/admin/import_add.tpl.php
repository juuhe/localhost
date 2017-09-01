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
echo url("admin/import.get_list");
echo "\" style=\"width: 86px;\">返回列表</a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n          <li > <a href=\"#\" style=\"width: 97px;\"> 导入数据</a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n        </ul>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div class=\"row-fluid\">\r\n    <h3 class=\"heading\"> 导入数据</h3>\r\n    <div class=\"row-fluid\">\r\n      <div class=\"span8\">\r\n        <form class=\"form-horizontal\" action=\"";
echo url("admin/import.add_post");
echo "\"  method=\"post\" id=\"form_b\" name=\"form_b\" enctype=\"multipart/form-data\" >\r\n          <input name=\"id\" id=\"id\" type=\"hidden\" value=\"";
echo $a["id"];
echo "\" />\r\n          <div class=\"control-group formSep st\">\r\n            <label class=\"control-label\">导入广告计划</label>\r\n            <div class=\"controls\" style=\"width:420px\">\r\n              <select name=\"planid\" id=\"planid\"  ";

if (RUN_ACTION == "edit") {
	echo "disabled='disabled'";
}

echo ">\r\n                <option value=\"\"> 请选择一个计划 </option>\r\n                ";

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

echo "              </select>\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">导入方式 </label>\r\n            <div class=\"controls\">\r\n              <input name=\"datatype\" type=\"radio\" id=\"datatype_t\" value=\"text\"  ";
if (($a["framework"] == "text") || !$a) {
	echo "checked";
}

echo " />\r\n              手动输入\r\n              <input type=\"radio\" name=\"datatype\"  id=\"datatype_f\" value=\"file\" ";

if ($a["framework"] == "file") {
	echo "checked";
}

echo "/>\r\n              导入文件 <span>导入文件的格式只能是Excel Txt二种格式 </span></div>\r\n          </div>\r\n          <div id=\"datatype_text\">\r\n            <div class=\"control-group formSep htmltemplate\">\r\n              <label class=\"control-label\">导入数据<br>\r\n              </label>\r\n              <div class=\"controls\">\r\n                <textarea name=\"postdata\" class=\"input_text span30\" id=\"postdata\" style=\"overflow: hidden; word-wrap: break-word;   height: 200px;width: 500px;\">";
echo $a["htmltemplate"];
echo "</textarea>\r\n                <button class=\"btn btn-inverse f_button\" type=\"button\"   style=\"\">验证格式</button>\r\n              </div>\r\n              <div class=\"controls verify_html\"   style=\"color:#FF0000; margin-top:10px\"> </div>\r\n            </div>\r\n            <div class=\"control-group formSep\" >\r\n              <div class=\"controls\">\r\n                <button class=\"btn btn-gebo\" type=\"submit\">提交</button>\r\n              </div>\r\n            </div>\r\n            <div class=\"control-group formSep\">\r\n              <label class=\"control-label\">非Cps格式说明 </label>\r\n              <div class=\"controls\"> 采用“<font color=\"#FF0000\">|</font>”为分割符，一行一条,格式如下<BR>\r\n                <BR>\r\n                导入到日期|会员数字ID|站长结算数|广商结算数<BR>\r\n                <BR>\r\n                ";
echo DAYS;
echo "|1001|88|88 </div>\r\n            </div>\r\n            <div class=\"control-group formSep\">\r\n              <label class=\"control-label\">Cps格式说明 </label>\r\n              <div class=\"controls\"> 采用“<font color=\"#FF0000\">|</font>”为分割符，一行一条,格式如下<BR>\r\n                <BR>\r\n                导入到日期|会员数字ID|订单号|订单价格|订单给网站主分成比例|订单广告商给的分成比例 <BR>\r\n                <BR>\r\n                ";
echo DAYS;
echo "|1001|";
echo TIMES;
echo "|123.8|20|30 <BR>\r\n                <BR>\r\n              </div>\r\n            </div>\r\n          </div>\r\n          <div id=\"datatype_file\" style=\"display:none\">\r\n          <div class=\"control-group formSep htmltemplate\">\r\n            <label class=\"control-label\">导入数据<br>\r\n            </label>\r\n            <div class=\"controls\">\r\n              <input name=\"files\" type=\"file\" />\r\n               \r\n            </div>\r\n             \r\n            \r\n          </div>\r\n          <div class=\"control-group formSep\" >\r\n            <div class=\"controls\">\r\n              <button class=\"btn btn-gebo\" type=\"submit\">提交</button>\r\n            </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">非Cps格式说明 </label>\r\n            <div class=\"controls\"> 第一行为标题，第二行开始为数据,格式如下<BR>\r\n              <BR>\r\n              <img src=\"";
echo SRC_TPL_DIR;
echo "/images/20140609151044.jpg\" alt=\"\" class=\"user_avatar\"> </div>\r\n          </div>\r\n          <div class=\"control-group formSep\">\r\n          <BR>\r\n          <label class=\"control-label\">Cps格式说明 </label>\r\n          <div class=\"controls\"> 第一行为标题，第二行开始为数据,格式如下，在计划单价为固定分成时站长和广告商分比可以为空<BR>\r\n            <BR>\r\n            <img src=\"";
echo SRC_TPL_DIR;
echo "/images/20140609151045.jpg\" alt=\"\" class=\"user_avatar\"> </div>\r\n        </form>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/form.js\"></script> \r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n $(document).ready(function(){\r\n\t\t add_import_vlt.init();\t\r\n\t\t $(\".f_button\").on('click', function(option) {\r\n\t\t\t  var datatype = $(\"input[name='datatype']:checked\").val(); \r\n\t\t \t  var postdata = $(\"#postdata\").val();\r\n\t\t\t  var planid = $(\"#planid option:selected\").val();\r\n\t\t\t  $.post(\"";
echo url("admin/import.post_verify_data");
echo "\",{\"datatype\": datatype, \"postdata\": postdata,\"planid\" : planid}, function(data){  \r\n\t\t\t\t\tif(data == 'ok'){\r\n\t\t\t\t\t\t $(\".verify_html\").html('可以导入，格式正确');\r\n\t\t\t\t\t}else {\r\n\t\t\t\t\t\t  $(\".verify_html\").html(data);\r\n\t\t\t\t\t}\r\n\t\t\t\t});\r\n\t\t  });\r\n\t\t  \r\n\t\t  \r\n\t\t  $('input:radio[name=datatype]').on('click', function(option) { \t \r\n\t\t\t var v = $(this).val();  \r\n\t\t\tif(v == 'file'){\r\n\t\t\t\t$('#datatype_file').show();\r\n\t\t\t\t$('#datatype_text').hide();\r\n\t\t\t\t \r\n\t\t\t}else if(v == 'text'){\r\n\t\t\t\t \r\n\t\t\t\t$('#datatype_text').show();\r\n\t\t\t\t$('#datatype_file').hide();\r\n\t\t\t}\r\n\t\t\t\t\t\t\t\t\t\t\t\r\n\t });\r\n\t\t\t\t\t\t\t\t \r\n\r\n\t  \r\n\t});\r\n\t\r\n \r\n</script> \r\n";

?>
