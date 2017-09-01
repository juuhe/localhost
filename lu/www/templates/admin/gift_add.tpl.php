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
echo "</div>\r\n<div id=\"main-content\">\r\n  <div class=\"row-fluid\">\r\n    <h3 class=\"heading\">";
echo RUN_ACTION == "add" ? "发布" : "编辑";
echo "奖品</h3>\r\n    <div class=\"row-fluid\">\r\n      <div class=\"span8\">\r\n        <form class=\"form-horizontal\" action=\"\r\n\t\t ";

if (RUN_ACTION == "add") {
	echo url("admin/gift.add_post");
}
else {
	echo url("admin/gift.update_post");
}

echo "\"  method=\"post\" id=\"form_b\" name=\"form_b\" enctype=\"multipart/form-data\">\r\n          <input name=\"id\" id=\"id\" type=\"hidden\" value=\"";
echo $g["id"];
echo "\" />\r\n          \r\n\t\t  \r\n            <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">分类</label>\r\n            <div class=\"controls\">\r\n               <select name=\"type\" id=\"type\">\r\n\t\t\t\t  <option  value=\"\">选择分类</option>\r\n\t\t\t\t  ";

foreach ($GLOBALS["gift_type"] as $key => $val ) {
	echo "\t\t\t\t  <option value=\"";
	echo $key;
	echo "\" ";
	if (($g["type"] == $key) && $g) {
		echo "selected";
	}

	echo ">";
	echo $val;
	echo "</option>\r\n\t\t\t\t  ";
}

echo "\t\t\t\t</select>\r\n              </div>\r\n          </div>\r\n\t\t  \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">奖品名称</label>\r\n            <div class=\"controls\">\r\n              <input name=\"name\" type=\"text\" class=\"input_text span30\" id=\"name\"  value=\"";
echo $g["name"];
echo "\">\r\n\t\t\t  <span>最多32个字符</span>\r\n              </div>\r\n          </div>\r\n\t\t  \r\n\t\t  \r\n\t\t  \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">兑奖积分</label>\r\n            <div class=\"controls\">\r\n              <input name=\"integral\" type=\"text\" class=\"input_text span30\" id=\"integral\"  value=\"";
echo $g["integral"];
echo "\">\r\n\t\t\t   <span>填写一个整数1积分=1元</span>\r\n               </div>\r\n          </div>\r\n          \r\n\t\t  \r\n\t\t  <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">上传图片</label>\r\n            <div class=\"controls\">\r\n             <input name=\"imageurl\" type=\"file\" id=\"imageurl\" size=\"40\"  />\r\n\t\t\t    \r\n               </div>\r\n          </div>\r\n\t\t  \r\n\t\t   <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">推荐</label>\r\n            <div class=\"controls\">\r\n              <input name=\"top\" type=\"radio\" value=\"1\"  ";
if (($g["top"] == 1) || !$g) {
	echo "checked";
}

echo " />否  <input type=\"radio\" name=\"top\" value=\"2\" ";

if ($g["top"] == 2) {
	echo "checked";
}

echo "/>是\r\n               </div>\r\n          </div>\r\n\t\t  \r\n          <div class=\"control-group formSep\">\r\n            <label class=\"control-label\">奖品介绍</label>\r\n            <div class=\"controls\">\r\n              <textarea name=\"content\" class=\"input_text span30\" id=\"content_a\" style=\"overflow: hidden; word-wrap: break-word;   height: 150px;width: 500px;\">";
echo $g["content"];
echo "</textarea>\r\n\t\t\t  </div>\r\n          </div>\r\n           \r\n           <div class=\"control-group\" style=\"height:20px\">\r\n            <div class=\"controls\">\r\n              <button class=\"btn btn-gebo\" type=\"submit\">提交</button>\r\n            </div>\r\n          </div>\r\n          \r\n        </form>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/form.js\"></script>\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/tinymce/tiny_mce.js\"></script>\r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n $(document).ready(function(){\r\n\t  \ttype = '";
echo RUN_ACTION;
echo "';\r\n\t\tfunction getContent(editor) { \r\n        var container = $('#' + editor.editorId);\r\n        $(editor.formElement).find(\"button[type=submit]\").click(\r\n        function(event)\r\n        {    \r\n            container.val(editor.getContent());\r\n\t\t\tadd_gift_vlt.init();\r\n        });\r\n\t\t} \r\n\t\ttinyMCE.init({ \r\n\t\t\t\t\tmode : \"textareas\",\r\n\t\t\t\t\ttheme : \"advanced\",\r\n\t\t\t\t\tlanguage:\"ch\",\r\n\t\t\t\t\ttheme_advanced_buttons1 : \"bold,italic,underline,separator,undo,redo,link,unlink,paste,copy,image,forecolor,fontsizeselect,code\",\r\n\t\t\t\t\ttheme_advanced_buttons2 : \"\",\r\n\t\t\t\t\ttheme_advanced_buttons3 : \"\",\r\n\t\t\t\t\ttheme_advanced_toolbar_location : \"top\",\r\n\t\t\t\t\ttheme_advanced_toolbar_align : \"left\" ,\r\n\t\t\t\t   init_instance_callback : \"getContent\" \r\n\t\t\r\n\t\t}); \r\n\t\t\r\n\t\t add_gift_vlt.init();\r\n\t});\r\n\r\n\r\n</script>\r\n";

?>
