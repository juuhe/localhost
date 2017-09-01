<?php

$resource = array(
	'基本设置'       => array('controller' => 'settings', 'action' => 'get_list,update_post'),
	'管理员角色'    => array('controller' => 'roles', 'action' => 'get_list,add_post,update_post,del'),
	'管理员管理'    => array('controller' => 'administrator', 'action' => 'get_list,add_post,update_post,del,lock,unlock'),
	'财务结算'       => array('controller' => 'pay', 'action' => 'get_list,post_payment,del,add_pay,down_execl'),
	'充值管理'       => array('controller' => 'onlinepay', 'action' => 'get_list,post_add_pay'),
	'数据报表'       => array('controller' => 'stats', 'action' => 'plan_list,user_list,ads_list,zone_list,del,down_execl'),
	'CPS订单管理'    => array('controller' => 'orders', 'action' => 'get_list,del,lock,unlock'),
	'CPA行为管理'    => array('controller' => 'cpa_report', 'action' => 'get_list,del,lock,unlock'),
	'IP报表'           => array('controller' => 'ip', 'action' => 'get_list,del,truncate'),
	'趋势分析'       => array('controller' => 'trend', 'action' => 'get_list,del'),
	'客户端属性'    => array('controller' => 'client_trend', 'action' => 'get_browser,get_screen,get_isp,get_city,get_os'),
	'搜索引擎'       => array('controller' => 'search_trend', 'action' => 'get_list'),
	'数据导入管理' => array('controller' => 'import', 'action' => 'get_list,add_post,revocation,del'),
	'网站管理'       => array('controller' => 'site', 'action' => 'get_list,add_post,update_post,del,lock,unlock,get_alexapr'),
	'广告位管理'    => array('controller' => 'zone', 'action' => 'get_list,update_post,del,lock,unlock'),
	'广告管理'       => array('controller' => 'ad', 'action' => 'get_list,add_post,update_post,del,lock,unlock,implant_zone'),
	'计划管理'       => array('controller' => 'plan', 'action' => 'get_list,add_post,update_post,del,lock,unlock'),
	'广告申请审核' => array('controller' => 'apply', 'action' => 'get_list,del,lock,unlock'),
	'会员管理'       => array('controller' => 'user', 'action' => 'affiliate_list,advertiser_list,commercial_list,service_list,add_post,update_post,del,lock,unlock'),
	'用户组'          => array('controller' => 'group', 'action' => 'get_list,add_post,update_post,del,lock,unlock'),
	'文章公告'       => array('controller' => 'article', 'action' => 'get_list,add_post,update_post,del,lock,unlock'),
	'操作日志'       => array('controller' => 'syslog', 'action' => 'get_list'),
	'登入日志'       => array('controller' => 'loginlog', 'action' => 'get_list'),
	'积分兑换记录' => array('controller' => 'giftlog', 'action' => 'get_list,del,delivery'),
	'积分兑换'       => array('controller' => 'gift', 'action' => 'get_list,add_post,update_post,del,lock,unlock'),
	'网站分类'       => array('controller' => 'class', 'action' => 'get_list,add_post,update_post,del'),
	'消息管理'       => array('controller' => 'msg', 'action' => 'get_list,add_post,update_post,del'),
	'广告类型'       => array('controller' => 'adtype', 'action' => 'get_list,add_post,update_post,del,lock,unlock'),
	'广告模式'       => array('controller' => 'adtpl', 'action' => 'get_list,add_post,update_post,del,lock,unlock'),
	'广告样式'       => array('controller' => 'adstyle', 'action' => 'get_list,add_post,update_post,del,lock,unlock'),
	'广告尺寸'       => array('controller' => 'specs', 'action' => 'get_list,add_post,update_post,del')
	);
$ac = array('add_post' => '新建', 'update_post' => '编辑', 'del' => '删除', 'get_list' => '查看列表', 'lock' => '锁定', 'unlock' => '激活审批', 'delivery' => '发货', 'truncate' => '清空', 'affiliate_list' => '站长管理', 'advertiser_list' => '广告商管理', 'commercial_list' => '商务管理', 'service_list' => '客服管理', 'post_payment' => '财务结算', 'post_add_pay' => '手动充值', 'plan_list' => '计划报表', 'user_list' => '站长报表', 'ads_list' => '广告报表', 'zone_list' => '广告位报表', 'get_os' => '操作系统', 'get_browser' => '浏览器', 'get_screen' => '分辨率', 'get_isp' => '网络提供商', 'get_city' => '地域分布', 'revocation' => '撤销', 'get_alexapr' => '获取Alexa', 'add_pay' => '手动充值', 'implant_zone' => '植入到广告位', 'down_execl' => '导出EXECL', 'update_money' => '更新余额', 'update_deduction' => '更新扣量', 'update_group' => '更新分组', 'update_price' => '更新单价');

?>
