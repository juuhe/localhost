<?php

echo "<html>\n<head>\n<title>PHPMailer - SMTP (Gmail) basic test</title>\n</head>\n<body>\n\n";
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
$mail->SMTPSecure = 'ssl';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 465;
$mail->Username = 'yourusername@gmail.com';
$mail->Password = 'yourpassword';
$mail->SetFrom('name@yourdomain.com', 'First Last');
$mail->AddReplyTo('name@yourdomain.com', 'First Last');
$mail->Subject = 'PHPMailer Test Subject via smtp (Gmail), basic';
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
