<?php

echo "<html>\n<head>\n<title>PHPMailer - SMTP basic test with authentication</title>\n</head>\n<body>\n\n";
error_reporting(2048);
date_default_timezone_set('America/Toronto');
require_once '../class.phpmailer.php';
$mail = new PHPMailer();
$body = file_get_contents('contents.html');
$body = preg_replace('/[\\]/', '', $body);
$mail->IsSMTP();
$mail->Host = 'mail.yourdomain.com';
$mail->SMTPDebug = 2;
$mail->SMTPAuth = true;
$mail->Host = 'mail.yourdomain.com';
$mail->Port = 26;
$mail->Username = 'yourname@yourdomain';
$mail->Password = 'yourpassword';
$mail->SetFrom('name@yourdomain.com', 'First Last');
$mail->AddReplyTo('name@yourdomain.com', 'First Last');
$mail->Subject = 'PHPMailer Test Subject via smtp, basic with authentication';
$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
$mail->MsgHTML($body);
$address = 'whoto@otherdomain.com';
$mail->AddAddress($address, 'John Doe');
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
