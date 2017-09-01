<?php

if (!defined("IN_ZYADS")) {
	exit();
}

echo "<link rel=\"stylesheet\" href=\"";
echo SRC_TPL_DIR;
echo "/css/error.css\" media=\"all\" type=\"text/css\" />\r\n\r\n";

if ($lic == "host") {
	echo "<div class=\"content\" id=\"error_default\" style=\"display: block;\">\r\n  <div class=\"error-box\">\r\n    <p id=\"h_default\"><span id=\"error_header\">域名限制</span> </p>\r\n    <p class=\"p_error_detail\"> <span>非中易授权域名，请联系中易客服人员。</span> </p>\r\n    \r\n    \r\n  </div>\r\n  \r\n</div>\r\n";
}
else if ($lic == "ip") {
	echo "<div class=\"content\" id=\"error_default\" style=\"display: block;\">\r\n  <div class=\"error-box\">\r\n    <p id=\"h_default\"><span id=\"error_header\">IP限制</span> </p>\r\n    <p class=\"p_error_detail\"> <span>服务器IP没有授权，请联系中易客服人员。</span> </p>\r\n    \r\n    \r\n  </div>\r\n  \r\n</div>\r\n";
}
else if ($lic == "ip1") {
	echo "<div class=\"content\" id=\"error_default\" style=\"display: block;\">\r\n  <div class=\"error-box\">\r\n    <p id=\"h_default\"><span id=\"error_header\">IP限制(error01)</span> </p>\r\n    <p class=\"p_error_detail\"> <span>服务器IP没有授权，请联系中易客服人员。</span> </p>\r\n    \r\n    \r\n  </div>\r\n  \r\n</div>\r\n";
}
else if ($lic == "lic1") {
	echo "<div class=\"content\" id=\"error_default\" style=\"display: block;\">\r\n  <div class=\"error-box\">\r\n  <p id=\"h_default\"><span id=\"error_header\">域名限制(error01)</span> </p>\r\n    <p class=\"p_error_detail\"> <span>非中易授权域名，请联系中易客服人员。</span> </p>\r\n    \r\n  </div>\r\n  \r\n</div>\r\n";
}
else if ($lic == "lic2") {
	echo "<div class=\"content\" id=\"error_default\" style=\"display: block;\">\r\n  <div class=\"error-box\">\r\n  <p id=\"h_default\"><span id=\"error_header\">域名限制(error02)</span> </p>\r\n    <p class=\"p_error_detail\"> <span>非中易授权域名，请联系中易客服人员。</span> </p>\r\n    \r\n  </div>\r\n  \r\n</div>\r\n";
}
else if ($lic == "lic3") {
	echo "<div class=\"content\" id=\"error_default\" style=\"display: block;\">\r\n  <div class=\"error-box\">\r\n  <p id=\"h_default\"><span id=\"error_header\">域名限制(error03)</span> </p>\r\n    <p class=\"p_error_detail\"> <span>非中易授权域名，请联系中易客服人员。</span> </p>\r\n    \r\n  </div>\r\n  \r\n</div>\r\n";
}
else {
	echo "<div class=\"content\" id=\"error_default\" style=\"display: block;\">\r\n  <div class=\"error-box\">\r\n    <p id=\"h_default\"><span id=\"error_header\">访问被拒绝</span> </p>\r\n    <p class=\"p_error_detail\"> <span>您可能没有权限访问此网页，请联管理人员。</span> </p>\r\n    <p >\r\n      <button class=\"btn btn-inverse\" type=\"button\" onclick=\"javascript:history.go(-1);\">返回上一页》</button>\r\n    </p>\r\n    \r\n  </div>\r\n  <div class=\"error_url\"><p>您正在访问： ";
	echo "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	echo "</p></div>\r\n</div>\r\n";
}

?>
