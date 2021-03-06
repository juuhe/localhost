<?php

if (!defined("IN_ZYADS")) {
	exit();
}

TPL::display("header");
$hour_text = array("0:00-1:00", "1:00-2:00", "2:00-3:00", "3:00-4:00", "4:00-5:00", "5:00-6:00", "6:00-7:00", "7:00-8:00", "8:00-9:00", "9:00-10:00", "10:00-11:00", "11:00-12:00", "12:00-13:00", "13:00-14:00", "14:00-15:00", "15:00-16:00", "16:00-17:00", "17:00-18:00", "18:00-19:00", "19:00-20:00", "20:00-21:00", "21:00-22:00", "22:00-23:00", "23:00-0:00");
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
echo url("admin/trend.get_list");
echo "\" >\r\n    <h3 class=\"heading\">趋势分析  <span class=\"h3span\"> <a href=\"";
echo url("admin/trend.get_list?timerange=" . $get_timerange["day"]);
echo "\">今天</a> | <a href=\"";
echo url("admin/trend.get_list?timerange=" . $get_timerange["yesterday"]);
echo "\">昨天</a> | <a href=\"";
echo url("admin/trend.get_list?timerange=" . $get_timerange["7day"]);
echo "\">最近7天</a> | <a href=\"";
echo url("admin/trend.get_list?timerange=" . $get_timerange["30day"]);
echo "\">最近30天</a> | <a href=\"";
echo url("admin/trend.get_list");
echo "\">所有数据</a></span></h3>\r\n    <div class=\"tab users\">\r\n      <div class=\"tab-header-right left\" > <a href=\"";
echo url("admin/trend.get_list");
echo "\" class=\"tab-btn  list tab-state-active\">趋势分析</a>  <a href=\"";
echo url("admin/client_trend.get_os?timerange=" . $get_timerange["day"] . "");
echo "\" class=\"tab-btn  os\">客户端属性</a></div>\r\n    </div>\r\n    <div  class=\"dataTables_wrapper \">\r\n      <div class=\"tb_sts\" style=\"margin-bottom:10px;\">\r\n        <div class=\"span6\">\r\n          <div class=\"dataTables_filter\" id=\"dt_outbox_filter\"> 搜索：\r\n            <input type=\"text\" class=\"input_text \" name=\"searchval\" value=\"";
echo $searchval;
echo "\" />\r\n            <select name=\"searchtype\">\r\n              <option value=\"planid\" ";

if ($searchtype == "planid") {
	echo "selected";
}

echo ">计划ID</option>\r\n              <option value=\"uid\" ";

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
echo "/images/sb.jpg\" align=\"top\" border=\"0\"  >\r\n          </div>\r\n        </div>\r\n      </div>\r\n      ";

if (request("compare") != 1) {
	echo "      <table width=\"100%\"   border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"tab_r\">\r\n        <tr>\r\n          <td align=\"center\"><span class=\"i_num\">\r\n            ";

	foreach ((array) $sum_stats as $s ) {
		$views += $s["views"];
	}

	echo (int) $views;
	echo "            </span><br>\r\n            <span class=\"i_text\">浏览量</span></td>\r\n          ";

	foreach ((array) explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
		$num = 0;

		foreach ((array) $sum_stats as $s ) {
			if ($dc != $s["day"]) {
				continue;
			}

			if ($t == $s["plantype"]) {
				$num = $s["num"];
			}
		}

		echo "          <td align=\"center\"><span class=\"i_num\">";
		echo $num;
		echo "</span><br />\r\n            <span class=\"i_text\">";
		echo strtoupper($t);
		echo "</span></td>\r\n          ";
	}

	echo "          <td align=\"center\"><span class=\"i_num\">\r\n            ";

	foreach ((array) $sum_stats as $s ) {
		if ($dc != $s["day"]) {
			continue;
		}

		$sumpay += $s["sumpay"];
	}

	echo (int) $sumpay;
	echo "            </span><br>\r\n            <span class=\"i_text\">应付</span></td>\r\n          <td align=\"center\"><span class=\"i_num\">\r\n            ";

	foreach ((array) $sum_stats as $s ) {
		if ($dc != $s["day"]) {
			continue;
		}

		$sumprofit += $s["sumprofit"];
	}

	echo (int) $sumprofit;
	echo "            </span><br>\r\n            <span class=\"i_text\">盈利</span></td>\r\n        </tr>\r\n      </table>\r\n      ";
	$sumpay = $sumprofit = 0;
}

echo "      <div style=\"margin-top:20px; margin-left:30px\">\r\n        <input name=\"group\" type=\"radio\" value=\"day\" ";
if (($group == "day") || !$group) {
	echo "checked";
}

echo "/>\r\n        按天\r\n        <input name=\"group\" type=\"radio\" value=\"hour\" ";

if ($group == "hour") {
	echo "checked";
}

echo "/>\r\n        按时\r\n        <input name=\"compare\" type=\"checkbox\" id=\"compare\" value=\"1\" ";

if (request("compare") == 1) {
	echo "checked";
}

echo "/>\r\n        对比数据 <span class=\"compare\" style=\"display:";

if (request("compare") != 1) {
	echo "none";
}

echo "\">\r\n        <input name=\"compare_1\" id=\"compare_1\" type=\"text\"  class=\"input_text\" onclick=\"__C('compare_1',1)\" style=\"width:80px\" value=\"";
echo request("compare_1");
echo "\"/>\r\n        与\r\n        <input name=\"compare_2\" id=\"compare_2\" type=\"text\"  class=\"input_text \"  onclick=\"__C('compare_2',1)\" style=\"width:80px\" value=\"";
echo request("compare_2");
echo "\"/>\r\n        <select name=\"plantype\" class=\"plantype\" id=\"plantype\">\r\n          <option value=\"\">所有类型</option>\r\n          ";

foreach (explode(",", $GLOBALS["C_ZYIIS"]["stats_type"]) as $t ) {
	echo "          <option value=\"";
	echo $t;
	echo "\" ";

	if (request("plantype") == $t) {
		echo "selected";
	}

	echo " >对比";
	echo strtoupper($t);
	echo "</option>\r\n          ";
}

echo "        </select>\r\n        <button class=\"rowbnt\" type=\"submit\" id=\"choose_sb\">对比</button>\r\n        </span> </div>\r\n      <div id=\"container\"  style=\"margin-top:20px;  height:270px\"> </div>\r\n      <div class=\"fold\"> <a href=\"javascript:void(0);\" id=\"fold_close\"></a> </div>\r\n      <script language=\"JavaScript\" type=\"text/javascript\">\r\n$(function () {\r\n\t\t \r\n        $('#container').highcharts({\r\n\t\t   chart:{\r\n\t\t   \tborderWidth:0,\r\n\t\t\tborderRadius:2\r\n\t\t   },\r\n            title: {\r\n                text: '";
echo str_replace("_", " 至 ", $timerange);
echo "',\r\n                x: -20 //center\r\n            },\r\n            \r\n            xAxis: {\r\n                categories: [";
echo $xAxis;
echo "],\t\t\t  \r\n                tickInterval: ";
$cn = count(explode(",", $xAxis));
echo 7 < $cn ? (int) $cn * 0.2 : 1;
echo "  ,\r\n\t\t\t\t \r\n            },\r\n            yAxis: {\r\n                title: {\r\n                    text: '流量统计'\r\n                },\r\n                plotLines: [{\r\n                    value: 0,\r\n                    width: 1,\r\n                    color: '#808080'\r\n                }],\r\n\t\t\t\tmin: 0\r\n            },\r\n            tooltip: {\r\n                valueSuffix: '次'\r\n            } ,\r\n            legend: {\r\n\t\t\t\tborderWidth: 0,\r\n                align: 'right',\r\n                x: -10,\r\n                verticalAlign: 'top',\r\n                y: 0,\r\n                floating: true,\r\n                backgroundColor: '#FFFFFF',\r\n\t\t\t\tborderColor: '#FFFFFF'\r\n            },\r\n\t\t\t \r\n            series: [";
echo str_replace(array("pv", "ip"), array("浏览量", "结算数"), $series);
echo "]\r\n\t\t\t\r\n\t\t\t\r\n        });\r\n    });\r\n    \r\n  $('input:radio[name=\"group\"]').on('click', function(option) {\t\r\n        $('input:radio[name=group]').attr(\"checked\", false);\r\n\t\t$(this).attr(\"checked\",true);\r\n\t\t$('input:checkbox[name=\"compare\"]').attr(\"checked\",false)\r\n        $(\"#formid\").submit();\r\n    });\r\n\t\r\n\t$('input:checkbox[name=\"compare\"]').on('click', function(option) {\t\r\n        if($('input:checkbox[name=\"compare\"]').attr(\"checked\")){\r\n\t\t\t $(\".compare\").show();\r\n\t\t}else {\r\n\t\t\t $(\".compare\").hide();\r\n\t\t}\r\n\t\t \r\n    });\r\n \r\n\r\n </script>\r\n \r\n ";

if ($group == "day") {
	echo " \r\n <table id=\"dt_inbox\" class=\"dataTable\" style=\"margin-top:30px\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th>日期</th>\r\n          \r\n            <th>浏览量</th>\r\n            <th>结算数</th>\r\n            <th>点击量</th>\r\n            <th>效果数</th>\r\n            <th>扣量</th>\r\n            <th>二次点击</th>\r\n            <th>CTR</th>\r\n            <th>应付金额</th>\r\n            <th>盈利</th>\r\n            \r\n          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

	if ($group == "day") {
		$day_hour = $day_sum_stats = $day_sum_stats_page;
	}

	foreach ((array) $day_hour as $d ) {
		echo "          <tr class=\"unread odd\">\r\n            <td>";
		echo $group == "day" ? $d["day"] : $hour_text[$d];
		echo "</td>\r\n            \r\n            <td>";
		echo $d["views"];
		echo "</td>\r\n            <td>";
		echo $d["num"];
		echo "</td>\r\n            <td>";
		echo $d["clicks"];
		echo "</td>\r\n            <td>";
		echo $d["effectnum"];
		echo "</td>\r\n            <td>";
		echo $d["deduction"];
		echo "</td>\r\n            <td>";
		echo $d["do2click"];
		echo "</td>\r\n            <td>";
		echo Ctr($d["views"], $d["num"]);
		echo "%</td>\r\n            <td>";
		echo abs($d["sumpay"]);
		echo "</td>\r\n            <td>";
		echo abs($d["sumprofit"]);
		echo "</td>\r\n            \r\n          </tr>\r\n          ";
	}

	echo "        </tbody>\r\n      </table>\r\n      \r\n   ";
}
else {
	echo "       \r\n      <table id=\"dt_inbox\" class=\"dataTable\" style=\"margin-top:30px\">\r\n        <thead>\r\n          <tr role=\"row\">\r\n            <th>日期</th>\r\n            ";

	foreach ((array) $st as $t ) {
		echo "            <th>";
		echo strtoupper($t);
		echo "</th>\r\n            ";
	}

	echo "          </tr>\r\n        </thead>\r\n        <tbody role=\"alert\" aria-live=\"polite\" aria-relevant=\"all\">\r\n          ";

	if ($group == "day") {
		$day_hour = $day_sum_stats = $day_sum_stats_page;
	}

	foreach ((array) $day_hour as $d ) {
		echo "          <tr class=\"unread odd\">\r\n            <td>";
		echo $group == "day" ? $d["day"] : $hour_text[$d];
		echo "</td>\r\n            ";

		foreach ((array) $st as $t ) {
			echo "            <td>";
			$num = 0;

			foreach ((array) $day_sum_stats as $s ) {
				if ($group == "day") {
					if (($t == $s["plantype"]) && ($s["day"] == $d["day"])) {
						$num = $s["num"];
					}
				}
				else if ($t == $s["plantype"]) {
					$ft = "hour" . $d;
					$num = $s[$ft];
				}
			}

			echo $num;
			echo "</td>\r\n            ";
		}

		echo "          </tr>\r\n          ";
	}

	echo "        </tbody>\r\n      </table>\r\n      \r\n      ";
}

echo "       \r\n      <div class=\"zpage_bt1\">\r\n        ";
echo $page->echoPage();
echo "      </div>\r\n    </div>\r\n  </form>\r\n</div>\r\n</div>\r\n</div>\r\n";
TPL::display("footer");
echo "<script language=\"JavaScript\" type=\"text/javascript\">\r\n $(\"#_s\").click(function(){\r\n\t$('input:checkbox[name=\"compare\"]').attr(\"checked\",false)\r\n});\t\r\n\r\n$(\"#fold_close\").click(function(){\r\n\r\n\tvar o = $('#container');\r\n\tif(o.css(\"display\")=='none'){\r\n\t\to.show(); \r\n\t    $(this).css(\"backgroundImage\",\"url(";
echo SRC_TPL_DIR;
echo "/images/fold_t.jpg)\");\r\n\t}else {\r\n\t\to.hide();\r\n\t    $(this).css(\"backgroundImage\",\"url(";
echo SRC_TPL_DIR;
echo "/images/fold_m.jpg)\");\r\n\t}\r\n});\t\r\n\r\n </script> \r\n";

?>
