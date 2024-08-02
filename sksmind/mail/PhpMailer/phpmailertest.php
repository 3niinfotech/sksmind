<?php
require("/home/sksmdiamonds/public_html/sksmdiamonds.in/PhpMailer/PHPMailer_5.2.0/class.phpmailer.php");

$mail = new PHPMailer();

$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->Host = "mail.sksmdiamonds.com";  // specify main and backup server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->Username = "info@sksmdiamonds.com";  // SMTP username
$mail->Password = "test123"; // SMTP password

$mail->From = "nikunjsavani1721@gmail.com";
$mail->FromName = "Nikunj Savani 123";
$mail->AddAddress("niks.savani@gmail.com");               // name is optional


$mail->WordWrap = 50;                                 // set word wrap to 50 characters

$mail->IsHTML(true);                                  // set email format to HTML

$mail->Subject = "Here is the subject sksm";
$mail->Body    = "This is the HTML message body <b>in bold!</b>";
$mail->AltBody = "This is the body in plain text for non-HTML mail clients";




if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   
}


echo "Message has been sent";
?>
