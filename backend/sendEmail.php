<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
function sendEmail($recipient, $recipient_add, $body, $subject){
$mail = new PHPMailer(true);
try{
    $mail->IsSMTP(); // Send via SMTP
    $mail->Host = "smtp.gmail.com"; // Change to your SMTP server or localhost
    $mail->Port = 587; // Google SMTP port
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = "pet.adopt.alert@gmail.com"; // SMTP username
    $mail->Password = "mlzz trxi macd eftt"; // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 

    $mail->setFrom("pet.adopt@gmail.com", "Pet Adopt");
    $mail->AddAddress($recipient_add, $recipient); // Recipient

    $mail->AddReplyTo("pet.adopt.alert@gmail.com", "Pet Adopt"); // Reply-To address

    $mail->Subject = $subject; // Email subject
    $mail->Body = nl2br($body); // HTML message body
    // $mail->AltBody = "This is the text-only body"; // Fallback text-only body

    $mail->WordWrap = 50; // Word wrapping
    $mail->IsHTML(true); // Send as HTML

    if(!$mail->Send()) {
        echo "Message was not sent";
        echo "Mailer Error: " . $mail->ErrorInfo;
        exit;
    }
    echo "Email has been sent";
}
catch(Exception $e){
    echo "Mailer Error: " . $mail->ErrorInfo;
}

// $mail->From = "email@domain.com"; // Your email
// $mail->FromName = "Your Name"; // Sender's name
}



?>