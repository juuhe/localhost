<?php

echo "<html>\n<head>\n<title>PHPMailer - Sendmail basic test</title>\n</head>\n<body>\n\n";
require_once '../class.phpmailer.php';
$mail = new PHPMailer();
$mail->IsSendmail();
$body = file_get_contents('contents.html');
$body = preg_replace('/[\\]/', '', $body);
$mail->SetFrom('name@yourdomain.com', 'First Last');
$mail->AddReplyTo('name@yourdomain.com', 'First Last');
$address = 'whoto@otherdomain.com';
$mail->AddAddress($address, 'John Doe');
$mail->Subject = 'PHPMailer Test Subject via Sendmail, basic';
$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
$mail->MsgHTML($body);
$mail->AddAttachment('images/phpmailer.gif');
$mail->AddAttachment('images/phpmailer_mini.gif');

if (!$mail->Send()) {
	echo 'Mailer Error: ' . $mail->ErrorInfo;
}
else {
	echo 'Message sent!';
}

echo "\n</body>\n</html>\n";

?>
