<?php

require '../../config.php';
require LIB_PATH . '/init.php';
$pay = APP::adapter('pay', 'alipay');
$g = $pay->receive();

if ($g == 'success') {
	dr('advertiser/onlinepay.update_order', $_REQUEST['out_trade_no']);
	echo '充值已完成，正在转入!  Loading......';
	redirect('advertiser/index.get_list', 1);
}
else {
	echo '失败';
}

?>
