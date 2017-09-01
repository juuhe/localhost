<?php

require '../../config.php';
require LIB_PATH . '/init.php';
$pay = APP::adapter('pay', 'chinabank');
$g = $pay->receive();

if ($g == 'success') {
	dr('advertiser/onlinepay.update_order', $_POST['v_oid']);
	echo 'ok';
}
else {
	echo '失败';
}

?>
