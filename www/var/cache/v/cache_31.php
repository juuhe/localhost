<?php

$cache_contents   = array (
  'tpl' => 
  array (
    'tplid' => '22',
    'adtypeid' => '10',
    'tplname' => '固定banner',
    'tpltype' => 'script_iframe',
    'viewjs' => '',
    'iframejs' => '',
    'customspecs' => '1',
    'customcolor' => '1',
    'description' => '',
    'sort' => '1',
    'status' => 'y',
    'addtime' => '2015-05-12 11:35:43',
    'adnum' => '1',
  ),
  'style' => 
  array (
    'styleid' => '31',
    'tplid' => '22',
    'stylename' => '默认',
    'htmlcontrol' => '',
    'specs' => '320x75',
    'viewjs' => 'var i = \'<iframe src="\' + ifsrc + \'" width="100%" height="\' + zone.height + \'" marginheight="0" scrolling="no" frameborder="0" allowtransparency="true"></iframe>\';

var arand=Math.floor(Math.random()*100000);
var o = "<div id="+arand+" style=\'width:100%;height:"+zone.height+"px\'><div>"+i+"</div></div>";
document.write(o);

function close(){  
	if(o) o.style.display=\'none\';
}
__A( __G(\'c\'+arand), "click",close);',
    'iframejs' => 'var ext= ads[0].imageurl.substr(ads[0].imageurl.lastIndexOf(".")).toLowerCase();
if(ext!=\'.swf\'){
	var str = "<a target=\'_blank\' href="+ads[0].url+" id=\'v_ads\'><img src=\'"+ads[0].imageurl+"\' border=\'0\' width=\'100%\' height=\'"+config.height+"\'></a>";
}else{
	var str = \'<div id="v_ads" style="position:absolute;z-index:10000;background-color:#fff;opacity:0.01;filter:alpha(opacity:1);"><a href="\'+ads[0].url+\'" target="_blank" style="display:block;width:\'+config.width+\'px;height:\'+config.height+\'px;" id="_z_add_s_"></a></div><embed src="\'+ads[0].content+\'" type="application/x-shockwave-flash" height="\'+config.height+\'" width="\'+config.width+\'"   name="Zad" quality="high" wmode="opaque"   allowscriptaccess="always" >\';  
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
    'addtime' => '2015-05-12 11:39:50',
  ),
);

$cache_name       = '31';
$cache_time       = 1504230296;
$cache_complete   = true;

