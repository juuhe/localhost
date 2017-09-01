<?php

echo "<html>\n<head>\n<title>PHPMailer - Mail() advanced test</title>\n</head>\n<body>\n\n";
require_once '../class.phpmailer.php';
$mail = new PHPMailer(true);

try {
	$mail->AddAddress('whoto@otherdomain.com', 'John Doe');
	$mail->SetFrom('name@yourdomain.com', 'First Last');
	$mail->AddReplyTo('name@yourdomain.com', 'First Last');
	$mail->Subject = 'PHPMailer Test Subject via mail(), advanced';
	$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
	$mail->MsgHTML(file_get_contents('contents.html'));
	$mail->AddAttachment('images/phpmailer.gif');
	$mail->AddAttachment('images/phpmailer_mini.gif');
	$mail->Send();
	echo "Message Sent OK</p>\n";
}
catch (phpmailerException $e) {
	echo $e->errorMessage();
}
catch (Exception $e) {
	echo $e->getMessage();
}

echo "</body>\n</html>\n";

?>
