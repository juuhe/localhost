function shake(ele, cls, times) {
    var i = 0, t = false, o = ele.attr("class") + " ", c = "", times = times || 2;
    if (t)
        return;
    t = setInterval(function() {
        i++;
        c = i % 2 ? o + cls : o;
        ele.attr("class", c);
        if (i == 2 * times) {
            clearInterval(t);
            ele.removeClass(cls);
        }
    }, 300);
}
function tourl(url){
		window.open(url);
}
