<?php
date_default_timezone_set('PRC');
// echo date('Y-m-d', strtotime('+2 sunday', time())); //上一个有效周日,同样适用于其它星期
// $friday = strtotime("Friday");//本周五开始时间
// $lastFri = strtotime("last Friday");//上周五开始时间  或者$friday-86400*7
// $nextFri = strtotime("next Friday");//下周五开始时间  或者$friday+86400*7 注意一下：下周五这个有时会出问题，比如现在还不到周五，这样得到的下周五的时间会和本周五一样。
// //如果需要某天截止时间可以在这一天的开始时间加上86400  也就是1天
// echo "本周五开始时间是：".date("Y-m-d",$friday)."<br />";
// echo "上周五开始时间是：".date("Y-m-d",$lastFri)."<br />";
// echo "下周五开始时间是：".date("Y-m-d",$nextFri)."<br />";
$p['addtime']='2017-08-18';
date("w",strtotime($p['addtime']));
echo date("w",strtotime($p['addtime']));
?>