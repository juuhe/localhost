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
echo "</div>\r\n<div id=\"main-content\">\r\n  <form method=\"post\" id=\"formid\"  action=\"";
echo url("admin/client_trend.get_os");
echo "\">\r\n    <h3 class=\"heading\">客户端属性 <span class=\"h3span\"> <a href=\"";
echo url("admin/client_trend.get_os?timerange=" . $get_timerange["day"]);
echo "\">今天</a> | <a href=\"";
echo url("admin/client_trend.get_os?timerange=" . $get_timerange["yesterday"]);
echo "\">昨天</a> | <a href=\"";
echo url("admin/client_trend.get_os?timerange=" . $get_timerange["7day"]);
echo "\">最近7天</a> | <a href=\"";
echo url("admin/client_trend.get_os?timerange=" . $get_timerange["30day"]);
echo "\">最近30天</a> | <a href=\"";
echo url("admin/client_trend.get_os");
echo "\">所有数据</a></span></h3>\r\n    <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\"> <a href=\"";
echo url("admin/client_trend.get_os?timerange=" . $get_timerange["day"] . "");
echo "\" class=\"tab-btn  list tab-state-active\">操作系统</a> <a href=\"";
echo url("admin/client_trend.get_browser?timerange=" . $get_timerange["day"] . "");
echo "\" class=\"tab-btn browser\">浏览器</a> <a href=\"";
echo url("admin/client_trend.get_screen?timerange=" . $get_timerange["day"] . "");
echo "\" class=\"tab-btn screen\">分辨率</a> <a href=\"";
echo url("admin/client_trend.get_isp?timerange=" . $get_timerange["day"] . "");
echo "\" class=\"tab-btn isp\">网络提供商</a> <a href=\"";
echo url("admin/client_trend.get_city?timerange=" . $get_timerange["day"] . "");
echo "\" class=\"tab-btn city\">地域分布</a> </div>\r\n    </div>\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div style=\"margin-top:20px; margin-left:30px\"> 搜站长UID\r\n        <input type=\"text\" class=\"input_text span30\" id=\"uid\" name=\"uid\" value=\"";
echo request("uid");
echo "\" style=\"width:60px;\" placeholder=\"\" />\r\n        <span class=\"dataTables_filter\">\r\n        <select name=\"timerange\" id=\"timerange\" style=\"width:200px; \">\r\n          <option value=\"";

if ($timerange != "") {
	echo $timerange;
}
else {
	echo $get_timerange["day"];
}

echo "\">\r\n            ";

if ($timerange != "") {
	echo str_replace("_", " 至 ", $timerange);
}
else {
	echo str_replace("_", " 至 ", $get_timerange["day"]);
}

echo "          </option>\r\n          <option  value=\"\" ";
echo $timerange == "" ? "selected " : "";
echo ">所有时间段</option>\r\n           <option  value=\"";
echo $get_timerange["day"];
echo "\" ";
echo $timerange == $get_timerange["day"] ? " selected" : "";
echo ">今天</option>\r\n          <option value=\"";
echo $get_timerange["yesterday"];
echo "\" ";
echo $timerange == $get_timerange["yesterday"] ? " selected" : "";
echo " >昨天</option>\r\n          <option value=\"";
echo $get_timerange["7day"];
echo "\" ";
echo $timerange == $get_timerange["7day"] ? " selected" : "";
echo " >最近7天</option>\r\n          <option value=\"";
echo $get_timerange["30day"];
echo "\" ";
echo $timerange == $get_timerange["30day"] ? " selected" : "";
echo " >最近30天</option>\r\n          <option value=\"";
echo $get_timerange["lastmonth"];
echo "\" ";
echo $timerange == $get_timerange["lastmonth"] ? " selected" : "";
echo " >上个月</option>\r\n        </select>\r\n        <img src=\"";
echo SRC_TPL_DIR;
echo "/images/calendar.png\" align=\"absmiddle\"  onclick=\"__C('timerange',2)\" style=\"margin-bottom: 3px;\"/></span>\r\n        <input name=\"_s\" id=\"_s\" type=\"image\" src=\"";
echo SRC_TPL_DIR;
echo "/images/sb.jpg\" align=\"top\" border=\"0\">\r\n      </div>\r\n      <div style=\"height:300px\" id=\"container_dh\">\r\n        <div id=\"container\"  style=\"margin-top:20px;  height:270px; float:left; width:50%\"> </div>\r\n        <div id=\"container_mob\"  style=\"margin-top:20px;  height:270px; float:left; width:49%\"> </div>\r\n      </div>\r\n      <div class=\"fold\"><a href=\"javascript:void(0);\" id=\"fold_close\"></a> </div>\r\n      <table id=\"dt_inbox\" class=\"dataTable\" style=\"margin-top:30px\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th>日期</th>\r\n            <th>操作系统 </th>\r\n            <th>是否移动</th>\r\n            <th>次数</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

foreach ((array) $data as $d ) {
	echo "          <tr class=\"unread odd\">\r\n            <td>";
	echo $d["day"];
	echo "</td>\r\n            <td>";
	echo $d["os"] ? $d["os"] : "其它";
	echo "</td>\r\n            <td>";
	echo $d["mobile"] == "y" ? "是" : "否";
	echo "</td>\r\n            <td>";
	echo $d["num"];
	echo "</td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n      <div class=\"zpage_bt1\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </form>\r\n</div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n$(function () {\r\n         $('#container').highcharts({\r\n        chart: {\r\n            plotBackgroundColor: null,\r\n            plotBorderWidth: null,\r\n            plotShadow: false\r\n        },\r\n        title: {\r\n            text: '操作系统'\r\n        },\r\n        tooltip: {\r\n    \t    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'\r\n        },\r\n        plotOptions: {\r\n            pie: {\r\n                allowPointSelect: true,\r\n                cursor: 'pointer',\r\n                dataLabels: {\r\n                    enabled: true,\r\n                    color: '#000000',\r\n                    connectorColor: '#000000',\r\n                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'\r\n                }\r\n            }\r\n        },\r\n        series: [{\r\n            type: 'pie',\r\n            name: '占用比例',\r\n            data: [\r\n                 ";

for ($i = 0; $i < count($os_sum); $i++) {
	if ($i == 0) {
		echo "                {\r\n                    name: '";
		echo ucfirst($os_sum[$i]["os"]);
		echo "',\r\n                    y: ";
		echo $os_sum[$i]["num"];
		echo ",\r\n                    sliced: true,\r\n                    selected: true\r\n                }\r\n\t\t\t\t";

		if ($i < (count($os_sum) - 1)) {
			echo ", ";
		}
	}
	else {
		echo "\t\t\t\t  ['";
		echo $os_sum[$i]["os"] ? ucfirst($os_sum[$i]["os"]) : "其它";
		echo "', ";
		echo $os_sum[$i]["num"];
		echo "]\r\n\t\t\t\t";

		if ($i < (count($os_sum) - 1)) {
			echo ", ";
		}
	}
}

echo "            ]\r\n        }]\r\n    });\r\n\t\r\n\t$('#container_mob').highcharts({\r\n        chart: {\r\n            plotBackgroundColor: null,\r\n            plotBorderWidth: null,\r\n            plotShadow: false\r\n        },\r\n        title: {\r\n            text: '移动端和计算机'\r\n        },\r\n        tooltip: {\r\n    \t    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'\r\n        },\r\n        plotOptions: {\r\n            pie: {\r\n                allowPointSelect: true,\r\n                cursor: 'pointer',\r\n                dataLabels: {\r\n                    enabled: true,\r\n                    color: '#000000',\r\n                    connectorColor: '#000000',\r\n                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'\r\n                }\r\n            }\r\n        },\r\n        series: [{\r\n            type: 'pie',\r\n            name: '占用比例',\r\n            data: [\r\n                 ";

for ($i = 0; $i < count($os_mob_sum); $i++) {
	if ($i == 0) {
		echo "                {\r\n                    name: '";

		if ($os_mob_sum[$i]["mobile"] == "y") {
			echo "移动设备";
		}
		else if ($os_mob_sum[$i]["mobile"] == "n") {
			echo "计算机";
		}
		else {
			echo "其它";
		}

		echo "',\r\n                    y: ";
		echo $os_mob_sum[$i]["num"];
		echo ",\r\n                    sliced: true,\r\n                    selected: true\r\n                }\r\n\t\t\t\t";

		if ($i < (count($os_mob_sum) - 1)) {
			echo ", ";
		}
	}
	else {
		echo "\t\t\t\t  ['";

		if ($os_mob_sum[$i]["mobile"] == "y") {
			echo "移动设备";
		}
		else if ($os_mob_sum[$i]["mobile"] == "n") {
			echo "计算机";
		}
		else {
			echo "其它";
		}

		echo "', ";
		echo $os_mob_sum[$i]["num"];
		echo "]\r\n\t\t\t\t";

		if ($i < (count($os_mob_sum) - 1)) {
			echo ", ";
		}
	}
}

echo "            ]\r\n        }]\r\n    });\r\n\t\r\n});\r\n\r\n$(\"#fold_close\").click(function(){\r\n\tvar o = $('#container_dh');\r\n\tif(o.css(\"display\")=='none'){\r\n\t\to.show(); \r\n\t    $(this).css(\"backgroundImage\",\"url(/www/tpl/admin/images/fold_t.jpg)\");\r\n\t}else {\r\n\t\to.hide();\r\n\t    $(this).css(\"backgroundImage\",\"url(/www/tpl/admin/images/fold_m.jpg)\");\r\n\t}\r\n});\t\r\n\r\n \r\n </script>\r\n";

?>
