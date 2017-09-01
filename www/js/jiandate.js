 /*  Copyright jian, 2014  |  www.zyiis.com
 * -----------------------------------------------------------
 *
 * The DHTML JianDate , version 1.1  
 * This script is distributed under the GNU Lesser General Public License.
*/

// $Id: JianDate.js 8 2014-06-01 10:27:27Jian  $

var d = {
    _Z__Y: '',
    _Z__M: '',
    _Z__tab: '',
    s: function(t, c) {
        var lastday = new Date(this._Z__Y, this._Z__M, 0).getDate(); 
		
		var myDate = new Date(),days = myDate.getDate(),bgc = "dedede";
        var firstday = new Date(this._Z__Y, this._Z__M - 1, 1).getDay();
		
        var weekend = '';
        var hans = '';
        var last = new Date(this._Z__Y, this._Z__M - 1, 0).getDate() - firstday; 

		 
        _Z__W = document.getElementById(c);
        for (var h1 = 0; h1 < 7; h1++) 
        {
            last++;
            if (h1 < firstday)
                this._Z__tab.rows[1].cells[h1].innerHTML = "<span style='color:#cccccc;' >" + last + "</span>";
            else
                this._Z__tab.rows[1].cells[h1].innerHTML = (h1 - firstday + 1);
            weekend = h1 - firstday + 1;
            hans = 1;  
			if (h1==days-1)
			{
				 
				this._Z__tab.rows[1].cells[h1].style.backgroundColor = "#dedede";
				this._Z__tab.rows[1].cells[h1].style.color = "#ff0000";
			}

        }
        for (var i = 0; i < 7; i++) 
        {
            this._Z__tab.rows[5].cells[i].innerHTML = "&nbsp;";
            this._Z__tab.rows[6].cells[i].innerHTML = "&nbsp;";
        }
        af = 1;
        while (weekend < lastday) 
        {  
            for (var h2 = 0; h2 < 7; h2++) 
            {
                if (weekend + h2 < lastday) 
                {
					
                    this._Z__tab.rows[hans + 1].cells[h2].innerHTML = (weekend + 1 + h2);

					if (weekend + 1 + h2==days)
					{
						 
						//this._Z__tab.rows[hans + 1].cells[h2].style.backgroundColor = "#dedede";
						//this._Z__tab.rows[hans + 1].cells[h2].style.color = "#ff0000";
					}


                } 
                
                else {
                    this._Z__tab.rows[hans + 1].cells[h2].innerHTML = "<span style='color:#cccccc;' >" + af + "</span>";
                    af++;
                }
            
            }
            weekend += 7;
            hans += 1;

			if (h2==days-1)
			{
				 
				//this._Z__tab.rows[1].cells[h1].style.backgroundColor = "#dedede";
				//this._Z__tab.rows[1].cells[h1].style.color = "#ff0000";
			}

        }
        ;
        if (this._Z__tab.rows[6].cells[0].innerHTML == '&nbsp;') {
            for (var i = 0; i < 7; i++) {
                this._Z__tab.rows[6].cells[i].innerHTML = "<span style='color:#cccccc;' >" + af + "</span>";
                af++;
            }
        }
    },
    r: function(t, a, mm,wz) {
		
        var Sd = document.getElementById('_Z__D');
        if (Sd) {
            Sd.parentNode.removeChild(Sd);
        }
        
        if (mm == 1) {
            widtha = '200';
            widthb = '100%'
        } else {
            widtha = '400';
            widthb = '192'
        }
        ;
        var h = "<table width='" + widtha + "'border='0'cellpadding='0'cellspacing='0'style='border:1px solid #a2bae7;'bgcolor='#FFFFFF'><tr><td><table width='98%'border='0'align='center'cellpadding='0'cellspacing='0'>";
        if (mm == 2) {
            h += "<tr><td height='30'><font color='#333333'><b>开始</b>：</font><input name='_Z__s1'id='_Z__s1'type='text'style='border:1px solid #D3DDE9;font-size:14px;height:20px;line-height:20px;padding-left:4px;width:95px;'/></td>";
            
            h += "<td style='padding-left:5px'><font color='#333333'><b>结束</b>：</font><input name='_Z__s2'id='_Z__s2'type='text'style='border:1px solid #D3DDE9;font-size:14px;height:20px;line-height:20px;padding-left:4px;width:95px;'/></td></tr>";
        }
        h += "<tr><td><table width='" + widthb + "'border=0 bgcolor='#E5EFF9'><tr><td><select name='_Z__y1'id='_Z__y1'onchange='d.e()'></select>年<select name='_Z__m1'id='_Z__m1'onchange='d.e()'></select></td></tr></table></td>";
        if (mm == 2) {
            h += "<td><table width='192'border=0 align='right'bgcolor='#E5EFF9'><tr><td style='padding-left:5px'><select name='_Z__y2'id='_Z__y2'onchange='d.f()'></select>年<select name='_Z__m2'id='_Z__m2'onchange='d.f()'></select></td></tr></table></td>";
        }
        h += "</tr><tr><td><table width='" + widthb + "'border='0'cellpadding='0'cellspacing='0'bgcolor='#D3DDE9'id='_Z__tab1'><tr align='center'style='color:#000; background:#F5FAFE;font-weight:bold'><td width='21'height='19'bgcolor='#F5FAFE'>日</td><td width='21'>一</td><td width='21'>二</td><td width='21'>三</td><td width='21'>四</td><td width='21'>五</td><td width='21'>六</td></tr>";
        for (var a1 = 0; a1 < 6; a1++) 
        {
			 
            h += "<tr bgcolor='#FFFFFF'style='cursor:pointer;color:#000'><td align='center'bgcolor='#FFFFFF'width='21'height='19' onclick='d.g(this)'onmouseover=\"this.style.backgroundColor='#dedede';\" onmouseout=\"this.style.backgroundColor   =   '#FFFFFF';\"></td><td align='center'bgcolor='#FFFFFF'onclick='d.g(this)'onmouseover=\"this.style.backgroundColor='#dedede';\" onmouseout=\"this.style.backgroundColor   =   '#FFFFFF';\"></td><td align='center'bgcolor='#FFFFFF'onclick='d.g(this)'onmouseover=\"this.style.backgroundColor='#dedede';\" onmouseout=\"this.style.backgroundColor   =   '#FFFFFF';\"></td><td align='center'bgcolor='#FFFFFF'onclick='d.g(this)'onmouseover=\"this.style.backgroundColor='#dedede';\" onmouseout=\"this.style.backgroundColor   =   '#FFFFFF';\"></td><td align='center'bgcolor='#FFFFFF'onclick='d.g(this)'onmouseover=\"this.style.backgroundColor='#dedede';\" onmouseout=\"this.style.backgroundColor   =   '#FFFFFF';\"></td><td align='center'bgcolor='#FFFFFF'onclick='d.g(this)'onmouseover=\"this.style.backgroundColor='#dedede';\" onmouseout=\"this.style.backgroundColor   =   '#FFFFFF';\"></td><td align='center'bgcolor='#FFFFFF'onclick='d.g(this)'onmouseover=\"this.style.backgroundColor='#dedede';\" onmouseout=\"this.style.backgroundColor   =   '#FFFFFF';\"></td></tr>";
        }
        h += "</table></td>";
        if (mm == 2) {
            h += "<td><table width='192'border='0'align='right'cellpadding='0'cellspacing='0'bgcolor='#D3DDE9'id='_Z__tab2'><tr align='center'style='color:#000; background:#F5FAFE;font-weight:bold'><td width='21'height='19'bgcolor='#F5FAFE'>日</td><td width='21'>一</td><td width='21'>二</td><td width='21'>三</td><td width='21'>四</td><td width='21'>五</td><td width='21'>六</td></tr>";
            for (var a1 = 0; a1 < 6; a1++) 
            {
                h += "<tr bgcolor='#FFFFFF'style='cursor:pointer;color:#000'><td align='center'bgcolor='#FFFFFF'width='21'height='19' onclick='d.n(this)'onmouseover=\"this.style.backgroundColor='#dedede';\" onmouseout=\"this.style.backgroundColor   =   '#FFFFFF';\"></td><td align='center'bgcolor='#FFFFFF'onclick='d.n(this)'onmouseover=\"this.style.backgroundColor='#dedede';\" onmouseout=\"this.style.backgroundColor   =   '#FFFFFF';\"></td><td align='center'bgcolor='#FFFFFF'onclick='d.n(this)'onmouseover=\"this.style.backgroundColor='#dedede';\" onmouseout=\"this.style.backgroundColor   =   '#FFFFFF';\"></td><td align='center'bgcolor='#FFFFFF'onclick='d.n(this)'onmouseover=\"this.style.backgroundColor='#dedede';\" onmouseout=\"this.style.backgroundColor   =   '#FFFFFF';\"></td><td align='center'bgcolor='#FFFFFF'onclick='d.n(this)'onmouseover=\"this.style.backgroundColor='#dedede';\" onmouseout=\"this.style.backgroundColor   =   '#FFFFFF';\"></td><td align='center'bgcolor='#FFFFFF'onclick='d.n(this)'onmouseover=\"this.style.backgroundColor='#dedede';\" onmouseout=\"this.style.backgroundColor   =   '#FFFFFF';\"></td><td align='center'bgcolor='#FFFFFF'onclick='d.n(this)'onmouseover=\"this.style.backgroundColor='#dedede';\" onmouseout=\"this.style.backgroundColor   =   '#FFFFFF';\"></td></tr>";
            }
            h += "</table></td>";
        }
        
        h += "</tr></table></td></tr><tr><td height='6'></td></tr> </table>";
        var Wtp = document.getElementById(a);
        var Sd = document.getElementById('_Z__D');
        if (Sd) {
            Sd.style.display = "";
        } 
        else {
            var g = document.createElement("div"),R = this.ol(Wtp);
            g.id = "_Z__D";
            g.style.position = "absolute";
            g.style.top = this.ot(Wtp) + Wtp.offsetHeight + 3 + 'px';
			if(wz=='r') R = this.ol(Wtp) - (200*mm)+Wtp.offsetWidth;
			 
            g.style.left =  R + 'px';
            g.style.zIndex = 200;
            g.innerHTML = h;
            document.body.appendChild(g);
        }
        window._Z__y1 = document.getElementById('_Z__y1');
        window._Z__y2 = document.getElementById('_Z__y2');
        window._Z__m1 = document.getElementById('_Z__m1');
        window._Z__m2 = document.getElementById('_Z__m2');
        window._Z__s1 = document.getElementById('_Z__s1');
        window._Z__s2 = document.getElementById('_Z__s2');
        var Zdate = new Date();
        var gY = Zdate.getFullYear();
        var gM = Zdate.getMonth();
        if (mm == 2) {
            _Z__s1.value = Zdate.getFullYear().toString() + '-' + (Zdate.getMonth() - 0 + 1).toString() + '-' + Zdate.getDate().toString();
            _Z__s2.value = _Z__s1.value;
        }
        
        for (var i = 2012; i < 2016; i++) {
            sYs = i - 2012;
            _Z__y1.options.add(new Option(i, sYs));
            if (mm == 2)
                _Z__y2.options.add(new Option(i, sYs));
            if (gY == i) {
                _Z__y1.selectedIndex = sYs;
                if (mm == 2)
                    _Z__y2.selectedIndex = sYs;
            }
            ;
        }
        ;
        for (var Dm = 1; Dm < 13; Dm++) {
            _Z__m1.options.add(new Option(Dm, Dm));
            if (mm == 2)
                _Z__m2.options.add(new Option(Dm, Dm));
            if (gM + 1 == Dm) {
                _Z__m1.selectedIndex = gM;
                if (mm == 2)
                    _Z__m2.selectedIndex = gM;
            }
            ;
        }
        ;
    },
    ol: function(a) {  
        return a == document.body ? 0 : a.offsetLeft + this.ol(a.offsetParent) 
    },
    ot: function(a) {  
        return a == document.body ? 0 : a.offsetTop + this.ot(a.offsetParent)
    },
    a: function(t, a, m,wz) {
        _Z__mm = m;
        this.r(t, a, m,wz);
        this.b(t);
        if (m == 2)
            this.c(t);
    
    },
    b: function(t) {
        var _Z__y1 = document.getElementById('_Z__y1');
        var _Z__m1 = document.getElementById('_Z__m1');
        this._Z__Y = _Z__y1.options[_Z__y1.selectedIndex].text;
        this._Z__M = _Z__m1.options[_Z__m1.selectedIndex].text;
        this._Z__tab = document.getElementById('_Z__tab1');
        this.s('g1', t);
    },
    c: function(t) {
        var _Z__y2 = document.getElementById('_Z__y2');
        var _Z__m2 = document.getElementById('_Z__m2');
        this._Z__Y = _Z__y2.options[_Z__y2.selectedIndex].text;
        this._Z__M = _Z__m2.options[_Z__m2.selectedIndex].text;
        this._Z__tab = document.getElementById('_Z__tab2');
        this.s('g2', t);
    },
    e: function() {
        this.b(_Z__W.id);
    },
    f: function() {
        this.c(_Z__W.id);
    },
    g: function(t) { 
        var t = t.innerHTML;
        if (t.length > 3) 
        {
            return;
        }
        t = this._Z__Y + "-" + this._Z__M + "-" + t;
        if (_Z__mm == 2) {
            _Z__s1.value = t;  
			return;
            var _Z__sd1 = _Z__s1.value.split('-');
            var _Z__sd2 = _Z__s2.value.split('-');
            var _Z__db1 = new Date(_Z__sd1[0], _Z__sd1[1], _Z__sd1[2]).getTime();
            var _Z__db2 = new Date(_Z__sd2[0], _Z__sd2[1], _Z__sd2[2]).getTime();
            if (_Z__db2 < _Z__db1) {
                alert("开始时间不能大于结束时间");
                return;
            }
            if (_Z__W.tagName == 'INPUT')
                _Z__W.value = t + " / " + _Z__s2.value;
            else 
            {
                _Z__W[0].value = t + " / " + _Z__s2.value;
                _Z__W[0].innerHTML = t + " / " + _Z__s2.value;
                _Z__W.selectedIndex = 0;
            }
        } else {
            if (_Z__W.tagName == 'INPUT')
                _Z__W.value = t;
            else
                alert("NOT INPUT");
        }
		 d.x();
    },
    n: function(t) { 
        var t = t.innerHTML;
        if (t.length > 3) 
        {
            return;
        }
        t = this._Z__Y + "-" + this._Z__M + "-" + t;
        //t = _Z__y2.options[_Z__y2.selectedIndex].text+"-"+_Z__m2.options[_Z__m2.selectedIndex].text+"-"+t; 
        _Z__s2.value = t;
        var _Z__sd1 = _Z__s1.value.split('-');
        var _Z__sd2 = _Z__s2.value.split('-');
        var _Z__db1 = new Date(_Z__sd1[0], _Z__sd1[1], _Z__sd1[2]).getTime();
        var _Z__db2 = new Date(_Z__sd2[0], _Z__sd2[1], _Z__sd2[2]).getTime();
        if (_Z__db2 < _Z__db1) {
            alert("结束时间不能小于开始时间");
            return;
        }
        if (_Z__W.tagName == 'INPUT')
            _Z__W.value = _Z__s1.value + " / " + t;
        else 
        {
            _Z__W[0].value = _Z__s1.value + " / " + t;
            ;
            _Z__W[0].innerHTML = _Z__s1.value + " / " + t;
            _Z__W.selectedIndex = 0;
        } 
        d.x();
    },
    addEvent: function(el, evname, func) {
        if (el.attachEvent) { // IE
            el.attachEvent("on" + evname, func);
        } else if (el.addEventListener) { // Gecko / W3C
            el.addEventListener(evname, func, true);
        } else {
            el["on" + evname] = func;
        }
    },
    x: function() {
        var div = document.getElementById("_Z__D");
        div.parentNode.removeChild(div);
    }
};
