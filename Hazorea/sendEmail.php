<?php
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This has been done in php.ini, but this is how to do it if you don't have access to that
//

require 'Email/PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;

//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';

//Set the hostname of the mail server
$mail->Host = $_SESSION['EmailSettings'][1]['Parameter'];
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6

//Set the SMTP port number - 587 for authenticated TLS, or 465 for SSL
$mail->Port = $_SESSION['EmailSettings'][2]['Parameter'];

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = $_SESSION['EmailSettings'][12]['Parameter'];

//Whether to use SMTP authentication
$mail->SMTPAuth = ($_SESSION['EmailSettings'][4]['Parameter'] == 1 ? true : false);

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = $_SESSION['EmailSettings'][5]['Parameter'];

//Password to use for SMTP authentication
$mail->Password = $_SESSION['EmailSettings'][6]['Parameter'];

$mail->CharSet = 'utf-8';

//Set who the message is to be sent from
$mail->setFrom($_SESSION['EmailSettings'][5]['Parameter'],"פשוש אינטרנט אונליין");

//Set an alternative reply-to address
//$mail->addReplyTo('replyto@example.com', 'First Last');

//Set who the message is to be sent to
$mail->addAddress($to);

$subject = "הודעה מפשוש אינטרנט אונליין";
//$preferences = ["input-charset" => "windows-1255", "output-charset" => "UTF-8"];
//$encoded_subject = iconv_mime_encode("", $subject, $preferences);

//Set the subject line
$mail->Subject = $subject;//$encoded_subject;

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

//Replace the plain text body with one created manually
$mail->Body = $message;

$mail->WordWrap = 60;

//Attach an image file
if(strlen($fDocumentFileFullPathName) > 0 AND $_SESSION['ini_array']['attachToMail'] == 1){	
	$mail->addAttachment($fDocumentFileFullPathName);
}

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}
?>