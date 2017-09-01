<?php

$cache_contents   = array (
  'tpl' => 
  array (
    'tplid' => '12',
    'adtypeid' => '10',
    'tplname' => '移动插屏',
    'tpltype' => 'script_iframe',
    'viewjs' => '',
    'iframejs' => '',
    'customspecs' => '1',
    'customcolor' => '1',
    'description' => '',
    'sort' => '1',
    'status' => 'y',
    'addtime' => '2014-06-22 22:11:14',
    'adnum' => '1',
  ),
  'style' => 
  array (
    'styleid' => '26',
    'tplid' => '12',
    'stylename' => '居中插屏',
    'htmlcontrol' => '',
    'specs' => '230x300',
    'viewjs' => 'var mw =document.body.offsetWidth-60;
if(mw<zone.width){
zone.width = mw;
}
var i = \'<iframe src="\' + ifsrc + \'" width="\'+zone.width+\'" height="\' + zone.height + \'" marginheight="0" scrolling="no" frameborder="0" allowtransparency="true"></iframe>\', o = document.createElement("div");
var arand=Math.floor(Math.random()*100000);
o.id = arand;
o.style.cssText = "position: fixed;z-index: 2147483646;top:0px;left:0px;width:100%;height:100%;text-align:center;background-color: rgba(0,0,0,.5);box-shadow: 0 -1px 1px rgba(0,0,0,.10);";
 
o.innerHTML = "<div style=\'position: relative;display:inline-block; zoom:1; *display:inline; vertical-align:middle; text-align:left;width:"+zone.width+"px;height:"+zone.height+"px\'><img src=\'"+domain.imgurl+"/images/close.png\' style=\'position:absolute;top:18px;right:18px;cursor:pointer;width;40px;height:40px;z-index:2147483647\' id=\'c"+arand+"\'>"+i+"</div><div style=\'height:100%; overflow:hidden; display:inline-block; width:1px; overflow:hidden; margin-left:-1px; zoom:1; *display:inline; *margin-top:-1px; _margin-top:0; vertical-align:middle;\'></div>";
document.body.appendChild(o);  
function close(){  
	if(o) o.style.display=\'none\';
}
__A( __G(\'c\'+arand), "click",close);',
    'iframejs' => 'var ext= ads[0].imageurl.substr(ads[0].imageurl.lastIndexOf(".")).toLowerCase();
if(ext!=\'.swf\'){
	var str = "<a target=\'_blank\' href="+ads[0].url+" id=\'v_ads\'><img src=\'"+ads[0].imageurl+"\' border=\'0\' width=\'100%\' height=\'"+config.height+"\'></a>";
}else{
	var str = \'<div id="v_ads" style="position:absolute;z-index:10000;background-color:#fff;opacity:0.01;filter:alpha(opacity:1);"><a href="\'+ads[0].url+\'" target="_blank" style="display:block;width:100%;height:\'+config.height+\'px;" id="_z_add_s_"></a></div><embed src="\'+ads[0].imageurl+\'" type="application/x-shockwave-flash" height="\'+config.height+\'" width="100%"   name="Zad" quality="high" wmode="opaque"   allowscriptaccess="always" >\';  
}
document.writeln(str);
/*
* RUN STATS
*/
pvid.aid.push(ads[0].adsid); 
pvid.pid.push(ads[0].planid);
pvstas(pvid);',
    'adnum' => '1',
    'description' => '',
    'status' => 'y',
    'addtime' => '2014-09-19 13:48:27',
  ),
);

$cache_name       = '26';
$cache_time       = 1503892122;
$cache_complete   = true;

