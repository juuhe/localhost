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
echo url("admin/msg.get_list");
echo "\" style=\"width: 86px;\">返回列表</a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n          <li > <a href=\"#\" style=\"width: 97px;\">";
echo RUN_ACTION == "add" ? "发布" : "编辑";
echo "消息</a>\r\n            <div class=\"chevronOverlay\" style=\"display: block;\"></div>\r\n          </li>\r\n        </ul>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div class=\"row-fluid\">\r\n    <h3 class=\"heading\">";
echo RUN_ACTION == "add" ? "发布" : "编辑";
echo "消息</h3>\r\n    <div class=\"row-fluid\">\r\n      <div class=\"span8\">\r\n        <form class=\"form-horizontal\" action=\"\r\n\t\t ";

if (RUN_ACTION == "add") {
	echo url("admin/msg.add_post");
}
else {
	echo url("admin/msg.update_post");
}

echo "\"  method=\"post\" id=\"form_b\" name=\"form_b\">\r\n          <input name=\"messageid\" id=\"messageid\" type=\"hidden\" value=\"";
echo $m["messageid"];
echo "\" />\r\n          \r\n\t\t  \r\n            <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">发送到</label>\r\n            <div class=\"controls\">\r\n               <select name=\"type\" id=\"type\">\r\n\t\t\t\t  <option  value=\"1\" ";

if ("1" == $m["type"]) {
	echo "selected";
}

echo ">所有站长</option>\r\n\t\t\t \r\n\t\t\t\t   <option  value=\"2\" ";

if ("2" == $m["type"]) {
	echo "selected";
}

echo ">所有广告商</option>\r\n\t\t\t \r\n\t\t\t\t</select>\r\n              </div>\r\n          </div>\r\n\t\t  \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">标题颜色</label>\r\n            <div class=\"controls\">\r\n               <select name=\"color\" id=\"color\">\r\n\t\t\t\t  <option value=\"\">标题颜色</option>\r\n\t\t\t\t  <option value=\"#FF0000\" ";

if ("#FF0000" == $m["color"]) {
	echo "selected";
}

echo ">红色</option>\r\n\t\t\t\t  <option value=\"#0000FF\" ";

if ("#0000FF" == $m["color"]) {
	echo "selected";
}

echo ">蓝色</option>\r\n\t\t\t\t</select>\r\n              </div>\r\n          </div>\r\n\t\t  \r\n\t\t  \r\n\t\t  \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">标题</label>\r\n            <div class=\"controls\">\r\n              <input name=\"title\" type=\"text\" class=\"input_text span30\" id=\"title\"  value=\"";
echo $m["title"];
echo "\">\r\n               </div>\r\n          </div>\r\n          \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">内容</label>\r\n            <div class=\"controls\">\r\n              <textarea name=\"content\" class=\"input_text span30\" id=\"content_a\" style=\"overflow: hidden; word-wrap: break-word;   height: 150px;width: 500px;\">";
echo $m["content"];
echo "</textarea>\r\n\t\t\t  </div>\r\n          </div>\r\n           \r\n           <div class=\"control-group\" style=\"height:20px\">\r\n            <div class=\"controls\">\r\n              <button class=\"btn btn-gebo\" type=\"submit\">提交</button>\r\n            </div>\r\n          </div>\r\n          \r\n        </form>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/form.js\"></script>\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/tinymce/tiny_mce.js\"></script>\r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\nfunction getContent(editor) { \r\n        var container = $('#' + editor.editorId);\r\n        $(editor.formElement).find(\"button[type=submit]\").click(\r\n        function(event)\r\n        {    \r\n            container.val(editor.getContent());\r\n\t\t\tadd_article_vlt.init();\r\n        });\r\n} \r\ntinyMCE.init({ \r\n            mode : \"textareas\",\r\n\t\t\ttheme : \"advanced\",\r\n\t\t\tlanguage:\"ch\",\r\n\t\t\ttheme_advanced_buttons1 : \"bold,italic,underline,separator,undo,redo,link,unlink,paste,copy,image,forecolor,fontsizeselect,code\",\r\n\t\t\ttheme_advanced_buttons2 : \"\",\r\n\t\t\ttheme_advanced_buttons3 : \"\",\r\n\t\t\ttheme_advanced_toolbar_location : \"top\",\r\n\t\t\ttheme_advanced_toolbar_align : \"left\" ,\r\n           init_instance_callback : \"getContent\" \r\n\r\n}); \r\n</script>\r\n";

?>
