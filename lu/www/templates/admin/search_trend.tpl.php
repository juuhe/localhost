<?php

if (!defined("IN_ZYADS")) {
	exit();
}

TPL::display("header");
echo "<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/jquery/lib/highcharts/js/highcharts.js\"></script>\r\n<script type=\"text/javascript\" src=\"";
echo WEB_URL;
echo "js/calendar/calendar.js\"></script>\r\n<link rel=\"stylesheet\" href=\"";
echo WEB_URL;
echo "js/calendar/calendar.css\" media=\"all\" type=\"text/css\" />\r\n<link rel=\"stylesheet\" href=\"";
echo SRC_TPL_DIR;
echo "/css/rating.css\" media=\"all\" type=\"text/css\" />\r\n<div id=\"sidebar\">\r\n  ";
TPL::display("sidebar");
echo "</div>\r\n<div id=\"main-content\">\r\n  <form method=\"post\" id=\"formid\" action=\"";
echo url("admin/search_trend.get_list");
echo "\">\r\n    <h3 class=\"heading\">搜索引擎 <span class=\"h3span\"> <a href=\"";
echo url("admin/search_trend.get_list?timerange=" . $get_timerange["day"]);
echo "\">今天</a> | <a href=\"";
echo url("admin/search_trend.get_list?timerange=" . $get_timerange["yesterday"]);
echo "\">昨天</a> | <a href=\"";
echo url("admin/search_trend.get_list?timerange=" . $get_timerange["7day"]);
echo "\">最近7天</a> | <a href=\"";
echo url("admin/search_trend.get_list?timerange=" . $get_timerange["30day"]);
echo "\">最近30天</a> | <a href=\"";
echo url("admin/search_trend.get_list");
echo "\">所有数据</a></span></h3>\r\n    <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\" > <a href=\"";
echo url("admin/search_trend.get_list");
echo "\" class=\"tab-btn  list tab-state-active\">搜索引擎</a> </div>\r\n    </div>\r\n    <div  class=\"dataTables_wrapper \"> \r\n    \r\n    \r\n    <div class=\"tb_sts\" style=\"margin-bottom:10px;\">\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\"> 搜索：\r\n            <input type=\"text\" class=\"input_text \" name=\"searchval\" value=\"";
echo $searchval;
echo "\" />\r\n            <select name=\"searchtype\">\r\n              <option value=\"uid\" ";

if ($searchtype == "uid") {
	echo "selected";
}

echo ">站长ID</option>\r\n            </select>\r\n            <select name=\"timerange\" id=\"timerange\" style=\"width:200px;margin-bottom: 10px\">\r\n              <option value=\"";

if ($timerange != "") {
	echo $timerange;
}
else {
	echo $get_timerange["day"];
}

echo "\">\r\n              ";

if ($timerange != "") {
	echo str_replace("_", " 至 ", $timerange);
}
else {
	echo str_replace("_", " 至 ", $get_timerange["day"]);
}

echo "              </option>\r\n              <option  value=\"\" ";
echo $timerange == "" ? "selected " : "";
echo ">所有时间段</option>\r\n               <option  value=\"";
echo $get_timerange["day"];
echo "\" ";
echo $timerange == $get_timerange["day"] ? " selected" : "";
echo ">今天</option>\r\n              <option value=\"";
echo $get_timerange["yesterday"];
echo "\" ";
echo $timerange == $get_timerange["yesterday"] ? " selected" : "";
echo " >昨天</option>\r\n              <option value=\"";
echo $get_timerange["7day"];
echo "\" ";
echo $timerange == $get_timerange["7day"] ? " selected" : "";
echo " >最近7天</option>\r\n              <option value=\"";
echo $get_timerange["30day"];
echo "\" ";
echo $timerange == $get_timerange["30day"] ? " selected" : "";
echo " >最近30天</option>\r\n              <option value=\"";
echo $get_timerange["lastmonth"];
echo "\" ";
echo $timerange == $get_timerange["lastmonth"] ? " selected" : "";
echo " >上个月</option>\r\n            </select>\r\n            <img src=\"";
echo SRC_TPL_DIR;
echo "/images/calendar.png\" align=\"absmiddle\"  onclick=\"__C('timerange',2)\" style=\"margin-bottom: 3px;\"/>\r\n            <input name=\"_s\" id=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <div id=\"container\"  style=\"margin-top:20px;  height:270px\"> </div>\r\n      <div class=\"fold\"><a href=\"javascript:void(0);\" id=\"fold_close\"></a> </div>\r\n      <table id=\"dt_inbox\" class=\"dataTable\" style=\"margin-top:30px\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th>日期</th>\r\n            <th>搜索引擎 </th>\r\n            <th>搜索词</th>\r\n            <th>目标地址</th>\r\n            <th>搜索次数</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $search as $s ) {
	echo "          <tr class=\"unread odd\">\r\n            <td>";
	echo $s["day"];
	echo "</td>\r\n            <td><a href=\"javascript:window.open('";
	echo $s["search_url"];
	echo "')\" target=\"_blank\">";
	echo $s["search"];
	echo "</a></td>\r\n            <td>";
	echo $s["keyword"];
	echo "</td>\r\n            <td>";
	echo $s["site_url"];
	echo "</td>\r\n            <td>";
	echo $s["num"];
	echo "</td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"zpage_bt1\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </form>\r\n</div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n$(function () {\r\n         $('#container').highcharts({\r\n        chart: {\r\n            plotBackgroundColor: null,\r\n            plotBorderWidth: null,\r\n            plotShadow: false\r\n        },\r\n        title: {\r\n            text: '搜索引擎分析'\r\n        },\r\n        tooltip: {\r\n    \t    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'\r\n        },\r\n        plotOptions: {\r\n            pie: {\r\n                allowPointSelect: true,\r\n                cursor: 'pointer',\r\n                dataLabels: {\r\n                    enabled: true,\r\n                    color: '#000000',\r\n                    connectorColor: '#000000',\r\n                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'\r\n                }\r\n            }\r\n        },\r\n        series: [{\r\n            type: 'pie',\r\n            name: '占用比例',\r\n            data: [\r\n                 ";

for ($i = 0; $i < count($search_sum); $i++) {
	if ($i == 0) {
		echo "                {\r\n                    name: '";
		echo ucfirst($search_sum[$i]["search"]);
		echo "',\r\n                    y: ";
		echo $search_sum[$i]["num"];
		echo ",\r\n                    sliced: true,\r\n                    selected: true\r\n                }\r\n\t\t\t\t";

		if ($i < (count($search_sum) - 1)) {
			echo ", ";
		}
	}
	else {
		echo "\t\t\t\t  ['";
		echo ucfirst($search_sum[$i]["search"]);
		echo "', ";
		echo $search_sum[$i]["num"];
		echo "]\r\n\t\t\t\t";

		if ($i < (count($search_sum) - 1)) {
			echo ", ";
		}
	}
}

echo "            ]\r\n        }]\r\n    });\r\n});\r\n\r\n$(\"#fold_close\").click(function(){\r\n\r\n\tvar o = $('#container');\r\n\tif(o.css(\"display\")=='none'){\r\n\t\to.show(); \r\n\t    $(this).css(\"backgroundImage\",\"url(/www/tpl/admin/images/fold_t.jpg)\");\r\n\t}else {\r\n\t\to.hide();\r\n\t    $(this).css(\"backgroundImage\",\"url(/www/tpl/admin/images/fold_m.jpg)\");\r\n\t}\r\n});\t\r\n\r\n \r\n </script> \r\n";

?>
