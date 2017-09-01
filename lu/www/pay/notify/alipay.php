<?php

require '../../config.php';
require LIB_PATH . '/init.php';
$pay = APP::adapter('pay', 'alipay');
$g = $pay->receive();

if ($g == 'success') {
	dr('advertiser/onlinepay.update_order', $_REQUEST['out_trade_no']);
	echo 'success';
}
else {
	echo 'fail';
}

?>
