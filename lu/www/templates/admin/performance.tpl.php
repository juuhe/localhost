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
echo url("admin/user." . RUN_ACTION);
echo "\" >\r\n    <h3 class=\"heading\">";
echo RUN_ACTION == "s_performance" ? "商务" : "客服";
echo "业绩查询 <span class=\"h3span\"><a href=\"";
echo url("admin/trend.get_list");
echo "\"></a></span></h3>\r\n    <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\"><a href=\"";
echo url("admin/user.k_performance");
echo "\" class=\"tab-btn service_list ";

if (RUN_ACTION == "k_performance") {
	echo "tab-state-active";
}

echo "\"> 客服业绩</a> <a href=\"";
echo url("admin/user.s_performance");
echo "\" class=\"tab-btn commercial_list ";

if (RUN_ACTION == "s_performance") {
	echo "tab-state-active";
}

echo "\"> 商务业绩</a></div>\r\n    </div>\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"tb_sts\" style=\"margin-bottom:10px;\">\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\"> 职员：\r\n            <select name=\"uid\" id=\"uid\" style=\"width:160px;\">\r\n              ";

foreach ($user as $s ) {
	echo "              <option value=\"";
	echo $s["uid"];
	echo "\" ";

	if ($uid == $s["uid"]) {
		echo "selected";
	}

	echo ">";
	echo $s["username"];
	echo "</option>\r\n              ";
}

echo "            </select>\r\n            <select name=\"timerange\" id=\"timerange\" style=\"width:200px;margin-bottom: 10px\">\r\n              <option value=\"";

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

echo "              </option>\r\n              <option value=\"";
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
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <div id=\"container\"  style=\"margin-top:20px;  height:270px\"> </div>\r\n      <div class=\"fold\"> <a href=\"javascript:void(0);\" id=\"fold_close\"></a> </div>\r\n      <script language=\"JavaScript\" type=\"text/javascript\">\r\n$(function () {\r\n        $('#container').highcharts({\r\n\t\t   chart:{\r\n\t\t   \tborderWidth:0,\r\n\t\t\tborderRadius:2\r\n\t\t   },\r\n            title: {\r\n                text: '";
echo str_replace("_", " 至 ", $timerange);
echo "',\r\n                x: -20 //center\r\n            },\r\n            \r\n            xAxis: {\r\n                categories: [";
echo $xAxis;
echo "]\r\n            },\r\n            yAxis: {\r\n                title: {\r\n                    text: '业绩 (元)'\r\n                },\r\n                plotLines: [{\r\n                    value: 0,\r\n                    width: 1,\r\n                    color: '#808080'\r\n                }],\r\n\t\t\t\tmin: 0\r\n            },\r\n            tooltip: {\r\n                valueSuffix: 'IP'\r\n            } ,\r\n            legend: {\r\n\t\t\t\tborderWidth: 0,\r\n                align: 'right',\r\n                x: -10,\r\n                verticalAlign: 'top',\r\n                y: 0,\r\n                floating: true,\r\n                backgroundColor: '#FFFFFF',\r\n\t\t\t\tborderColor: '#FFFFFF'\r\n            },\r\n            series: [\r\n\t\t\t \r\n\t\t\t{\t\r\n                name: 'aaa',\r\n                data: [";
echo $series_data;
echo "]\r\n            }\r\n\t\t \r\n\t\t\t\r\n\t\t\t\r\n\t\t\t]\r\n\t\t\t\r\n\t\t\t\r\n        });\r\n    });\r\n    \r\n  $('input:radio[name=\"group\"]').on('click', function(option) {\t\r\n        $('input:radio[name=group]').attr(\"checked\", false);\r\n\t\t$(this).attr(\"checked\",true);\r\n\t\t$('input:checkbox[name=\"compare\"]').attr(\"checked\",false)\r\n        $(\"#formid\").submit();\r\n    });\r\n\t\r\n\t$('input:checkbox[name=\"compare\"]').on('click', function(option) {\t\r\n        if($('input:checkbox[name=\"compare\"]').attr(\"checked\")){\r\n\t\t\t $(\".compare\").show();\r\n\t\t}else {\r\n\t\t\t $(\".compare\").hide();\r\n\t\t}\r\n\t\t \r\n    });\r\n \r\n\r\n </script>\r\n      <table id=\"dt_inbox\" class=\"dataTable\" style=\"margin-top:30px\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th>日期</th>\r\n            <th>业绩</th>\r\n            <th>";
echo RUN_ACTION == "s_performance" ? "厂商" : "会员";
echo "</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

if ($group == "day") {
	$day_hour = $day_sum_stats = $day_sum_stats_page;
}

foreach ((array) $performance as $p ) {
	echo "          <tr class=\"unread odd\">\r\n            <td>";
	echo $p["day"];
	echo "</td>\r\n            <td>";
	echo abs($p["pay"]);
	echo "元</td>\r\n            <td>";
	echo $p["u_num"];
	echo "个</td>\r\n          </tr>\r\n          ";
}

echo "        </tbody>\r\n      </table>\r\n    </div>\r\n  </form>\r\n</div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n $(\"#_s\").click(function(){\r\n\t$('input:checkbox[name=\"compare\"]').attr(\"checked\",false)\r\n});\t\r\n\r\n$(\"#fold_close\").click(function(){\r\n\r\n\tvar o = $('#container');\r\n\tif(o.css(\"display\")=='none'){\r\n\t\to.show(); \r\n\t    $(this).css(\"backgroundImage\",\"url(";
echo SRC_TPL_DIR;
echo "/images/fold_t.jpg)\");\r\n\t}else {\r\n\t\to.hide();\r\n\t    $(this).css(\"backgroundImage\",\"url(";
echo SRC_TPL_DIR;
echo "/images/fold_m.jpg)\");\r\n\t}\r\n});\t\r\n\r\n </script> \r\n";

?>
