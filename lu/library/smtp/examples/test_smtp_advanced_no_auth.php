<?php

echo "<html>\n<head>\n<title>PHPMailer - SMTP advanced test with no authentication</title>\n</head>\n<body>\n\n";
require_once '../class.phpmailer.php';
$mail = new PHPMailer(true);
$mail->IsSMTP();

try {
	$mail->Host = 'mail.yourdomain.com';
	$mail->SMTPDebug = 2;
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

echo "\n</body>\n</html>\n";

?>
