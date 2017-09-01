/**
* @version: 2.6
* @author: zyiis http://www.zyiis.com/
* @date: 2014-08-08
* @copyright: Copyright (c) 2014-2018 zyiis.com. All rights reserved.
* @license: Licensed under Apache License v2.0. See http://www.apache.org/licenses/LICENSE-2.0
* @website: http://www.zyiis.com/
*/
 
function __C(Element_Id, Num, Position) {
	var html = get_tpl();
	var ElementId = $(Element_Id);
	if ($('__Calendar')) {
		hide()
	}
	var g = document.createElement("div"),
	R = ol(ElementId);
	g.id = "__Calendar";
	g.style.position = "absolute";
	g.style.top = ot(ElementId) + ElementId.offsetHeight + 3 + 'px';
	if (Position == 'r') R = ol(ElementId) - (250 * Num) + ElementId.offsetWidth;
	g.style.left = R + 'px';
	g.style.zIndex = 200;
	g.style.backgroundColor = '#FFF';
	g.innerHTML = html;
	document.body.appendChild(g);
	var Zdate = new Date();
	var gY = Zdate.getFullYear();
	var gM = (Zdate.getMonth() + 1);
	var Day = gY + "-" + gM + "-" + Zdate.getDate();
	var months = new Array("一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二");
	if (Num == 2) {
		$('data_start').value = Day;
		$('data_end').value = Day;
		Calendar(gY, gM, 'calendar_right')
	}
	Calendar(gY, gM, 'calendar_left');
	function $(d) {
		if ("string" === typeof d || d instanceof String) {
			return window.document.getElementById(d)
		} else {
			if (d && d.nodeName && (d.nodeType == 1 || d.nodeType == 9)) {
				return d
			}
		}
		return d
	}
	function ol(a) {
		return a == document.body ? 0 : a.offsetLeft + ol(a.offsetParent)
	}
	function ot(a) {
		return a == document.body ? 0 : a.offsetTop + ot(a.offsetParent)
	}
	function UpdateTime(Tabid, type) {
		if (type == 'next') {
			gM++
		} else {
			gM--
		}
		if (gM > 12) {
			gM = 1;
			gY++
		}
		if (gM < 1) {
			gM = 12;
			gY--
		}
		$(Tabid + "_month").innerHTML = gY + "	" + months[gM - 1] + "月";
		Calendar(gY, gM, Tabid)
	}
	function Calendar(Year, Month, Tabid) {
		var lastday = new Date(Year, Month, 0).getDate();
		var firstday = new Date(Year, Month - 1, 1).getDay();
		var last = new Date(Year, Month - 1, 0).getDate() - firstday;
		var tab = $(Tabid);
		var s_row = 2;
		var hao = 1;
		var islast;
		$(Tabid + "_month").innerHTML = gY + "	" + months[gM - 1] + "月";
		for (var row = 0; row < 6; row++) {
			for (var col = 0; col < 7; col++) {
				var endDay = Year + "-" + Month + "-" + hao;
				last++;
				if (col < firstday && row == 0) {
					tab.rows[s_row].cells[col].innerHTML = last;
					tab.rows[s_row].cells[col].className = "last";
					hao--
				} else {
					tab.rows[s_row].cells[col].innerHTML = hao;
					if (endDay == Day) {
						tab.rows[s_row].cells[col].style.backgroundColor = '#357ebd';
						tab.rows[s_row].cells[col].className = "days"
					} else {
						tab.rows[s_row].cells[col].className = "";
						tab.rows[s_row].cells[col].style.backgroundColor = '#ffffff'
					}
					if (islast) {
						tab.rows[s_row].cells[col].className = "last";
						tab.rows[s_row].cells[col].style.backgroundColor = '#ffffff'
					}
					if (Num == 2) {
						if ((contrast($('data_start').value, endDay) && Tabid == 'calendar_right')) {
							tab.rows[s_row].cells[col].className = "last"
						}
					}
				}
				hao++;
				if (hao > lastday) {
					hao = 1;
					islast = true
				}
			}
			s_row++
		}
	}
	on('calendar_left', "data_start");
	if (Num == 2) {
		on('calendar_right', "data_end")
	}
	function on(Tabid, data_input) {
		t = $(Tabid);
		var c = t.getElementsByTagName("td");
		for (var i = 0; i < c.length; i++) {
			c[i].onclick = function() {
				if (this.className != 'last') {
					for (var b = 0; b < c.length; b++) {
						if (c[b].className == 'days') {
							c[b].className = '';
							c[b].style.backgroundColor = '#fff'
						}
					}
					this.style.backgroundColor = '#357ebd';
					if (Num == 2) {
						$(data_input).value = gY + "-" + (gM) + "-" + this.innerHTML
					} else {
						updateInputText(gY, gM, this.innerHTML);
						hide()
					}
					this.className = 'days';
					if (Tabid == 'calendar_left' && Num == 2) {
						if (contrast($('data_start').value, $('data_end').value)) {
							$('data_end').value = gY + "-" + (gM) + "-" + this.innerHTML
						}
						Calendar(gY, gM, 'calendar_right')
					}
					
					if (Tabid == 'calendar_right' && Num == 2) {
						updateInputText();
						hide();
					}
				}
			};
			c[i].onmouseover = function() {
				if (this.className != 'days') {
					this.style.backgroundColor = '#DEDFDE'
				}
			};
			c[i].onmouseout = function() {
				if (this.className != 'days') {
					this.style.backgroundColor = '#ffffff'
				}
			}
		}
	}
	function contrast(a, b) {
		var arr = a.split("-");
		var starttime = new Date(arr[0], arr[1], arr[2]);
		var starttimes = starttime.getTime();
		var arrs = b.split("-");
		var endtime = new Date(arrs[0], arrs[1], arrs[2]);
		var endtimes = endtime.getTime();
		if (starttimes > endtimes) {
			return true
		} else return false
	}
	$("calendar_left_next").onclick = function() {
		UpdateTime('calendar_left', 'next')
	}
	$("calendar_left_prev").onclick = function() {
		UpdateTime('calendar_left', 'prev')
	}
	if (Num == 2) {
		$("calendar_right_next").onclick = function() {
			UpdateTime('calendar_right', 'next')
		}
		$("calendar_right_prev").onclick = function() {
			UpdateTime('calendar_right', 'prev')
		}
		$("cancelBtn").onclick = function() {
			hide()
		}
		$("applyBtn").onclick = function() {
			updateInputText();
			hide()
		}
	}
	function updateInputText(gY, gM, Day) {
		
		     if (Num == 2) {
					var val = $('data_start').value + "_" + $('data_end').value
				} else {
					var val = gY + "-" + gM + "-" + Day
			  }
			  
			  
		  if ($(Element_Id).tagName == 'INPUT'){
			  $(Element_Id).value = val;
		 } else {
                $(Element_Id)[0].value = val;
                $(Element_Id)[0].innerHTML = val.replace("_"," 至 ");
                $(Element_Id).selectedIndex = 0;
          }

	}
	function hide() {
		$('__Calendar').parentNode.removeChild($('__Calendar'))
	}
	 function getRootPath(){
		var curWwwPath=window.document.location.href;
		var pathName=window.document.location.pathname;
		var pos=curWwwPath.indexOf(pathName);
		var localhostPaht=curWwwPath.substring(0,pos);
		var projectName=pathName.substring(0,pathName.substr(1).indexOf('/')+1);
		return(localhostPaht+projectName);
	}  
	function get_tpl() {
		var imgPath=getRootPath();
		var Template_2 = '<table border="0"cellpadding="0"cellspacing="0"class="Calendar_main"><tr><td width="490"height="60"><table width="95%"border="0"align="center"cellpadding="0"cellspacing="0"><tr><td><label for="data_start">从</label><input class="input-mini"type="text"name="data_start"id="data_start"value=""disabled="disabled"><label for="data_start">到</label><input class="input-mini"type="text"name="data_end"id="data_end"value=""disabled="disabled"></td><td align="right"><button class="zbtn btn-default"id="cancelBtn">取消</button>&nbsp;<button class="zbtn btn-success"id="applyBtn">确定</button></td></tr></table></td></tr><tr><td><table width="99%"border="0"align="center"cellpadding="0"cellspacing="0"style="margin-bottom:5px"><tr><td><table width="100%"border="0"cellpadding="0"cellspacing="0"class="calendar_bbb"><tr><td><table align="center"class="table-condensed"id="calendar_left"><thead><tr><th><img src="'+imgPath+'/js/calendar/prev.jpg"width="12"height="13" id="calendar_left_prev" class="prev"></th><th colspan="5"align="center"id="calendar_left_month"></th><th class="next available"><i class=""><img src="'+imgPath+'/js/calendar/next.jpg"width="12"height="13"id="calendar_left_next"class="next"></i></th></tr><tr><th>周日</th><th>周一</th><th>周二</th><th>周三</th><th>周四</th><th>周五</th><th>周六</th></tr></thead><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></td></tr></table></td><td width="5">&nbsp;</td><td><table width="100%"border="0"cellpadding="0"cellspacing="0"class="calendar_bbb"><tr><td><table align="center"class="table-condensed"id="calendar_right"><thead><tr><th><img src="'+imgPath+'/js/calendar/prev.jpg"width="12"height="13" id="calendar_right_prev"></th><th colspan="5"align="center"id="calendar_right_month"></th><th class="next available"><i class=""><img src="'+imgPath+'/js/calendar/next.jpg"width="12"height="13"id="calendar_right_next"></i></th></tr><tr><th>周日</th><th>周一</th><th>周二</th><th>周三</th><th>周四</th><th>周五</th><th>周六</th></tr></thead><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></td></tr></table></td></tr></table></td></tr></table>';
		var Template_1 = '<table border="0"cellpadding="0"cellspacing="0"class="Calendar_main"><tr><td><table align="center"class="table-condensed"id="calendar_left"><thead><tr><th><img src="'+imgPath+'/js/calendar/prev.jpg"width="12"height="13"id="calendar_left_prev"class="prev"></th><th colspan="5"align="center"id="calendar_left_month"></th><th class="next available"><i class=""><img src="'+imgPath+'/js/calendar/next.jpg"width="12"height="13"id="calendar_left_next"class="next"></i></th></tr><tr><th>周日</th><th>周一</th><th>周二</th><th>周三</th><th>周四</th><th>周五</th><th>周六</th></tr></thead><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></td></tr><tr></tr></table>';
		return Num == 2 ? Template_2: Template_1
	}
}