<?php

echo "<html>\n<head>\n<title>POP before SMTP Test</title>\n</head>\n<body>\n\n";
require_once '../class.phpmailer.php';
require_once '../class.pop3.php';
$pop = new POP3();
$pop->Authorise('pop3.yourdomain.com', 110, 30, 'username', 'password', 1);
$mail = new PHPMailer();
$body = file_get_contents('contents.html');
$body = preg_replace('/[\\]/', '', $body);
$mail->IsSMTP();
$mail->SMTPDebug = 2;
$mail->Host = 'pop3.yourdomain.com';
$mail->SetFrom('name@yourdomain.com', 'First Last');
$mail->AddReplyTo('name@yourdomain.com', 'First Last');
$mail->Subject = 'PHPMailer Test Subject via POP before SMTP, basic';
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
