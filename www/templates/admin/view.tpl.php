<?php

if (!defined("IN_ZYADS")) {
	exit();
}

echo "\r\n<link rel=\"stylesheet\" href=\"";
echo SRC_TPL_DIR;
echo "/css/rating.css\" media=\"all\" type=\"text/css\" />\r\n<title>预览</title>\r\n<h4>预览</h4>\r\n<div>注：此页面中点击和展示将不被记录在报告中</div>\r\n<div style=\"margin-top:10px; border-top:#CCCCCC  solid 1px; padding:10px\">\r\n  ";

if ($a["plantype"] == "cpm") {
	echo "<a href='javascript:void(0)' onclick='tourl(\"" . $a["url"] . "\")'>" . $a["url"] . "</a>";
}
else {
	echo api("ad.view", $a["adsid"], $a, 0);
}

echo "</div>\r\n ";

?>
