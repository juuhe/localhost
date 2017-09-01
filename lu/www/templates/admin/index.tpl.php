<?php

if (!defined("IN_ZYCPS")) {
	exit();
}

include WEB_TPL_DIR . "/header.tpl.php";
echo "<!--[if IE]><script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/lib/visualize/excanvas.compiled.js\"></script><![endif]-->\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/lib/visualize/visualize.jQuery.js\"></script>\r\n<link type=\"text/css\" rel=\"stylesheet\" href=\"";
echo WEB_URL;
echo "js/jquery/lib/visualize/visualize.jQuery.css\"/>\r\n<link type=\"text/css\" rel=\"stylesheet\" href=\"";
echo WEB_URL;
echo "js/jquery/lib/visualize/page.css\"/>\r\n<div id=\"sidebar\">\r\n  ";
include WEB_TPL_DIR . "/sidebar.tpl.php";
echo "</div>\r\n<div id=\"main-content\">\r\n \r\n  <div class=\"row-fluid  mt60\">\r\n  \r\n   <h3 class=\"heading tab_heading\">控制面版</h3>\r\n    <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/ad.get_list");
echo "\" class=\"tab-btn  list tab-state-active\">后台首页</a>  <a href=\"";
echo url("admin/ad.get_list");
echo "\" class=\"tab-btn  ip \">后台首页</a></div>\r\n    </div>\r\n\t\r\n    \r\n    <div class=\"to24\"  style=\"margin-top:40px; padding:10px;\">\r\n      <table class=\"ivisualize\"  cellpadding=\"0\" cellspacing=\"0\"  align=\"center\" >\r\n        <thead>\r\n          <tr>\r\n            <td></td>\r\n            <th scope=\"col\">01</th>\r\n            <th scope=\"col\">02</th>\r\n            <th scope=\"col\">03</th>\r\n            <th scope=\"col\">04</th>\r\n            <th scope=\"col\">05</th>\r\n            <th scope=\"col\">06</th>\r\n            <th scope=\"col\">07</th>\r\n            <th scope=\"col\">08</th>\r\n            <th scope=\"col\">09</th>\r\n            <th scope=\"col\">10</th>\r\n            <th scope=\"col\">11</th>\r\n            <th scope=\"col\">12</th>\r\n            <th scope=\"col\">13</th>\r\n            <th scope=\"col\">14</th>\r\n            <th scope=\"col\">15</th>\r\n            <th scope=\"col\">16</th>\r\n            <th scope=\"col\">17</th>\r\n            <th scope=\"col\">18</th>\r\n            <th scope=\"col\">19</th>\r\n            <th scope=\"col\">20</th>\r\n            <th scope=\"col\">21</th>\r\n            <th scope=\"col\">22</th>\r\n            <th scope=\"col\">23</th>\r\n            <th scope=\"col\">24</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody>\r\n          <tr>\r\n            <th>已下单</th>\r\n            ";

for ($i = 1; $i < 25; $i++) {
	echo "            <td>";
	$hn = ($i == 24 ? $hour[0]["num"] : $hour[$i]["num"]);

	if (!$hn) {
		$hn = 0;
	}

	echo $hn;
	echo "</td>\r\n            ";
}

echo "          </tr>\r\n          <tr>\r\n            <th>(条)</th>\r\n            <td>0</td>\r\n          </tr>\r\n        </tbody>\r\n      </table>\r\n    </div>\r\n   <h3 class=\"heading tab_heading\"> </h3>\r\n   \r\n  \r\n\r\n  </div>\r\n</div>\r\n\r\n\r\n";
include WEB_TPL_DIR . "/footer.tpl.php";
echo "\r\n\r\n<script language=\"JavaScript\" type=\"text/javascript\">\r\nvar vwidth = $('.ivisualize').width();\r\n$('.ivisualize').hide();\r\n$(function(){\r\n\t\t\t\t//make some charts\r\n\t\t\t\t \t\r\n\t\t\t\t$('.ivisualize').visualize({\r\n\t\t\t\ttype: 'line',\r\n\t\t\t\tcolors: ['#3aaef7'],\r\n\t\t\t\tlineDots: 'double',\r\n\t\t\t\twidth:vwidth,\r\n\t\t\t\tinteraction: true,\r\n\t\t\t\tmultiHover: 5,\r\n\t\t\t\ttooltip: true,\r\n\t\t\t\ttooltiphtml: function(data) {\r\n\t\t\t\t\tvar html ='';\r\n\t\t\t\t\tfor(var i=0; i<data.point.length; i++){\r\n\t\t\t\t\t\thtml += '<p class=\"chart_tooltip\"><strong>'+data.point[i].value+'</strong> '+data.point[i].yLabels[0]+'</p>';\r\n\t\t\t\t\t}\t\r\n\t\t\t\t\treturn html;\r\n\t\t\t\t}});\r\n\t\t\t\t \r\n\t\t\t\t \r\n\t\t\t});\r\n\t\t\t\r\n</script>\r\n";

?>
