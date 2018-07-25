<?php
require_once('email_config.php'); //store your alternative of username and pasw
require('phpmailer/PHPMailer/PHPMailerAutoload.php');

//template for output
$message = []; //message get from frontend
$output = [
    'success' => null,
    'messages' => []
];

//sanitize name field  sanitize will change untill valid
$message['name'] = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
if(empty($message['name'])) {
    $output['success'] = false;
    $output['messages'][] = 'missing name key';
}

//validate email addr only return true/false validate can  not change
$message['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
if(empty($message['email'])) {
    $output['success'] = false;
    $output['messages'][] = 'missing email key';
}

//email body
$message['message'] = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
if(empty($message['message'])) {
    $output['success'] = false;
    $output['messages'][] = 'missing message key';
}

//sanitize subject
$message['subject'] = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
if(empty($message['subject'])) {
    $output['success'] = false;
    $output['messages'][] = 'missing subject key';
}

//compare with phone number, if not 0-9 replace it with empty
$message['phone'] = preg_replace('/[^0-9]/', '', $_POST['phone_number']);

//throw error if any error
if($output['success']!==null) {
    http_response_code(400);
    echo json_encode($output);
    exit();
}

foreach($_POST as $key=> $value) {
    $_POST[$key] = htmlentities(addslashes( $value ));
}

$mail = new PHPMailer;
$mail->SMTPDebug = 0;           // 3 is tell me everything Enable verbose debug output. Change to 0 to disable debugging output.

$mail->isSMTP();                // Set mailer to use SMTP.
$mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers.
$mail->SMTPAuth = true;         // Enable SMTP authentication


$mail->Username = EMAIL_USER;   // SMTP username don;t need to change 
$mail->Password = EMAIL_PASS;   // SMTP password

//might need ot change for other email not gmail
$mail->SMTPSecure = 'tls';      // Enable TLS encryption, `ssl` also accepted, but TLS is a newer more-secure encryption
$mail->Port = 587;              // TCP port to connect to
$options = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
$mail->smtpConnect($options);
$mail->From = $message['email'];  // from sender's email address (shows in "From" field)
$mail->FromName = $message['name'];  // sender's name (shows in "From" field)
//send to my
$mail->addAddress(EMAIL_TO_ADDRESS, EMAIL_USERNAME);  // Add a recipient// user get confirmation
//$mail->addAddress('ellen@example.com');                        // Name is optional
$mail->addReplyTo($message['email'], $message['name']); //??? need variable user's real emal              // Add a reply-to address
//use for when I reply can directly reply to the submiter's email

//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);    // the content will allow HTML content

$message['subject'] = $message['name']. " has sent you message on you protfolio";
$mail->Subject =  $message['subject'];
//$mail->Subject = 'message from '. $_POST['name']. ' on '. date('Y-m-d H:i:s'). ' at '. $_SERVER['REMOTE_ADDR'] ;
//$mail->Body    = 'This is the HTML message body <b>in bold!</b>';//because isHTML(true)

// $message['message'] = n12br($message['message']); // convert newline characters to line break html tags

$mail->Body = $message['message'];
// $mail->Body = "you receiced a 
//              msg form {$_POST['name']} <br>
//              Email add: {$_POST['email']}<br>
//              Subject: {$_POST['subject']}<br>
//              Message: {$_POST['body']}";


$mail->AltBody = htmlentities($message['message']);
// $mail->AltBody = "you receiced a 
// msg form {$_POST['name']} \n
// Email add: {$_POST['email']}\n
// Subject: {$_POST['subject']}\n
// Message: {$_POST['body']}";

if(!$mail->send()) {
    $output['success'] = false;
    $output['messages'][] = $mail->ErrorInfo;
} else {
    $output['success'] = true;
}

echo json_encode($output);
// if(!$mail->send()) {
//     echo 'Message could not be sent.';
//     echo 'Mailer Error: ' . $mail->ErrorInfo;
// } else {
//     echo 'Message has been sent';
// }
?>
