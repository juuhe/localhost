<?php

if (!defined("IN_ZYADS")) {
	exit();
}

TPL::display("header");
echo "\r\n<link rel=\"stylesheet\" href=\"";
echo SRC_TPL_DIR;
echo "/css/rating.css\" media=\"all\" type=\"text/css\" />\r\n<div class=\"alert success\" ";

if (!$_SESSION["succ"]) {
	echo "style=\"display:none\"";
}

echo ">\r\n  <!-- <a class=\"close\" href=\"javascript:;\">×</a>-->\r\n  <strong>操作成功.</strong> </div>\r\n<div class=\"alert err\" ";

if (!$_SESSION["err"]) {
	echo "style=\"display:none\"";
}

echo ">\r\n  <!-- <a class=\"close\" href=\"javascript:;\">×</a>-->\r\n  <strong>操作失败.</strong> </div>\r\n<div id=\"sidebar\">\r\n  ";
TPL::display("sidebar");
echo "</div>\r\n<div id=\"main-content\">\r\n  \r\n  <div class=\"row-fluid mt60\" >\r\n    <h3 class=\"heading\"> 管理员管理 </h3>\r\n   \r\n    <div  class=\"dataTables_wrapper \">\r\n\t  <div class=\"tb_sts\"><a href=\"#mbox_new\" style=\" width:110px; text-align:left\" id=\"add_user\"  class=\"tab-btn add \">新增管理员</a></div>\r\n      <div class=\"row\">\r\n        <div class=\"span6\">\r\n          <div id=\"dt_outbox_length\" class=\"dataTables_length\"> 批量操作：\r\n            <select size=\"1\" name=\"choose_type\" id=\"choose_type\">\r\n              <option value=\"\">请选择</option>\r\n              <option value=\"del\" >删除</option>\r\n            </select>\r\n            <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">提交</button>\r\n          </div>\r\n        </div>\r\n        \r\n      </div>\r\n      <table id=\"dt_inbox\" class=\"dataTable\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th class=\"table_checkbox sorting_disabled\" role=\"columnheader\" rowspan=\"1\" colspan=\"1\" style=\"width: 13px;\" aria-label=\"\"><input type=\"checkbox\" name=\"select_id\" id=\"select_id\"></th>\r\n            <th>登入名称</th>\r\n            <th>角色</th>\r\n            <th>描述</th>\r\n            <th>上次登入时间</th>\r\n            <th>上次登入IP</th>\r\n            <th>登入总计</th>\r\n            <th>状态</th>\r\n            <th>操作</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $administrator as $a ) {
	$roles = dr("admin/roles.get_one", $a["rolesid"]);
	echo "          <tr class=\"unread odd\">\r\n            <td><input type=\"checkbox\" name=\"id\" id=\"admin_";
	echo $a["id"];
	echo "\" value=\"";
	echo $a["id"];
	echo "\"></td>\r\n            <td>";
	echo $a["username"];
	echo "</td>\r\n            <td>";
	echo $roles["name"];
	echo "</td>\r\n            <td>";
	echo $a["userinfo"];
	echo "</td>\r\n            <td>";
	echo $a["logintime"];
	echo "</td>\r\n            <td>";
	echo $a["loginip"];
	echo "</td>\r\n            <td>";
	echo $a["loginnum"];
	echo "</td>\r\n            <td class=\"status\">";

	switch ($a["status"]) {
	case "y":
		echo "<span class=\"notification info_bg\">活动</span>";
		break;

	case "n":
		echo "<span class=\"notification error_bg\">锁定</span>";
		break;
	}

	echo " </td>\r\n            <td adminid='";
	echo $a["id"];
	echo "' class=\"uld_img\"><img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/pencil_gray.png\" alt=\"\" border=\"0\" title=\"编辑\" onclick=\"edit('";
	echo $a["username"];
	echo "','";
	echo $a["id"];
	echo "','";
	echo $a["userinfo"];
	echo "','";
	echo $a["rolesid"];
	echo "' )\" /> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/access_ok_gray.png\" alt=\"\" border=\"0\" class=\"unlock\" title=\"激活\" /> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/lock-icon.png\" alt=\"\" border=\"0\" class=\"lock\" title=\"锁定\"/> <img src=\"";
	echo SRC_TPL_DIR;
	echo "/images/trashcan_gray.png\" alt=\"\" border=\"0\"  class=\"del\" title=\"删除\"/></td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"row\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "\r\n\r\n<div id=\"add_admin_form\" style=\"display:none\">\r\n  <form action=\"";
echo url("admin/administrator.add_post");
echo "\" id=\"form_adm\" method=\"post\">\r\n   <input name=\"id\" type=\"hidden\" value=\"\" id=\"update_id\" />\r\n    <div class=\"txt-fld\">\r\n      <label for=\"\" class=\"tit\">用户角色</label>\r\n      <select  name=\"rolesid\" id=\"rolesid\">\r\n\t   ";

foreach ($rol as $r ) {
	echo "\t\t <option value=\"";
	echo $r["id"];
	echo "\">";
	echo $r["name"];
	echo "</option>\r\n\t\t";
}

echo "      </select>\r\n\t</div>\r\n    <div class=\"txt-fld\">\r\n      <label for=\"\"  class=\"tit\">用户名</label>\r\n      <input id=\"username\" name=\"username\" type=\"text\">\r\n    </div>\r\n    <div class=\"txt-fld\">\r\n      <label for=\"\"  class=\"tit\">用户密码</label>\r\n      <input id=\"password\" name=\"password\" type=\"password\">\r\n\t  <span style=\"color: #666;font-size: 12px; display:none\" id=\"password_span\">不修改为空</span>\r\n    </div>\r\n    <div class=\"txt-fld\">\r\n      <label for=\"\"  class=\"tit\">描述</label>\r\n      <input id=\"userinfo\" name=\"userinfo\" type=\"text\">\r\n    </div>\r\n    \r\n    <div class=\"btn-fld\">\r\n      <button type=\"submit\">提交 »</button>\r\n    </div>\r\n  </form>\r\n</div>\r\n\r\n\r\n<script type=\"text/javascript\" src=\"";
echo SRC_TPL_DIR;
echo "/js/form.js\"></script>\r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\n\r\n\r\nfunction uld(type,e) {\r\n\tvar html = '';\r\n   \tvar width = 400;\r\n\tif (type == 'del') {\r\n\t\turl = '";
echo url("admin/administrator.del");
echo "';\r\n\t\ttitle = '删除管理员';\r\n\t\ttext = '确定要删除吗？删除后无法恢复!';\r\n\t}\r\n\t if (type == 'lock') {\r\n\t\turl = '";
echo url("admin/administrator.lock");
echo "';\r\n\t\thtml = '<span class=\"notification error_bg\">锁定</span>';\r\n\t    text = '确认锁定吗';\r\n\t\ttitle = '锁定管理员';\r\n\t}\r\n\tif (type == 'unlock') {\r\n\t\turl = '";
echo url("admin/administrator.unlock");
echo "';\r\n\t\thtml = '<span class=\"notification info_bg\">活动</span>';\r\n\t    text = '确认激活吗';\r\n\t\ttitle = '激活管理员';\r\n\t}\r\n\tbox.confirm(text,width,title,function(bool){ \r\n\t\t if(e) admin_id = $(e).parent().attr(\"adminid\");\r\n\t\t admin_id = admin_id.split(',');\r\n\t\t if (bool) {\r\n\t\t\t if (type == 'del') {\r\n\t\t\t\t$.each(admin_id, function(i,val){   \r\n\t\t\t\t\t$(\"#admin_\"+val).parent().parent().css(\"backgroundColor\", \"#faa\").hide('normal');\r\n\t\t\t\t});  \r\n\t\t\t } \r\n\t\t\t$.ajax(\r\n\t\t\t{\r\n\t\t\t\tdataType: 'html',\r\n\t\t\t\turl: url,\r\n\t\t\t\ttype: 'post',\r\n\t\t\t\tdata: 'id=' + admin_id ,\r\n\t\t\t\tsuccess: function(text) \r\n\t\t\t\t{\r\n\t\t\t\t\t if(text){\r\n\t\t\t\t\t \tif(text == 'err_count'){\r\n\t\t\t\t\t\t\t$(\".err\").show();\r\n\t\t\t\t\t\t\t$(\".err > strong\").html('删除失败！最后一个管理员不能删除');\r\n\t\t\t\t\t\t\t $.each(admin_id, function(i,val){    \r\n\t\t\t\t\t\t\t\t$(\"#admin_\"+val).parent().parent().css(\"backgroundColor\", \"\").show('normal');\r\n\t\t\t\t\t\t\t });\r\n\t\t\t\t\t\t}\r\n\t\t\t\t\t }else {\r\n\t\t\t\t\t\t $.each(admin_id, function(i,val){    \r\n\t\t\t\t\t\t\t\t$(\"#admin_\"+val).parent().parent().find('.status').html(html);\r\n\t\t\t\t\t\t });   \r\n\t\t\t\t\t\t$(\".success\").show();\r\n\t\t\t\t\t}\r\n\t\t\t\t}\r\n\t\t\t});\r\n\t\t}\r\n\t});\r\n}\r\n\r\n$(\".unlock,.lock,.del\").click(function(){\r\n\tuld(this.className,this);\r\n});\r\n\r\n\r\n\r\n$(\"#choose_sb\").click(function(){\r\n\tvar arr=[];\r\n\tvar choose_type = $(\"#choose_type\").val();\r\n\tif(!choose_type){\r\n\t\tbox.alert('批量操作请选择一个操作对像',300);\r\n\t\treturn ;\r\n\t}\r\n \tvar arrChk=$(\"input[name='id']:checked\"); \r\n     \r\n    for (var i=0;i<arrChk.length;i++)\r\n    {\r\n        var v = arrChk[i].value;\r\n\t\tarr.push(v);\r\n\t\t\r\n    }\r\n\tadmin_id = arr.join(\",\");\r\n\tuld(choose_type);\r\n});\r\n\r\n$(\"#select_id\").click(function(){\r\n \t var a = $(\"#select_id\").attr(\"checked\");\r\n\t if(a!='checked') a = false;\r\n     $(\"input[name='id']\").attr(\"checked\",a);\r\n});\r\n\r\n$(\"#add_user\").click(function(){\r\n\tbox.form(\"#add_admin_form\",\"新建用户\");\r\n\tadd_adminuser_vlt.init();\r\n});\r\n\r\nfunction edit(username,id,usrinfo,rloesid){\r\n\tbox.form(\"#add_admin_form\",\"编辑用户#\"+username);\r\n\t$(\"#username\").parent().hide();\r\n\t$(\"#username\").val(username);\r\n\t$(\"#userinfo\").val(usrinfo);\r\n\t$(\"#rolesid\").val(rloesid);\r\n\t$(\"#update_id\").val(id);\r\n\t$(\"#password_span\").show();\r\n\t$(\"#form_adm\").attr('action', '";
echo url("admin/administrator.update_post");
echo "');\r\n} \r\n\r\n </script>\r\n";

?>
