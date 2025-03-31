<?php
session_start();

// Check if the user has a temporary login session
if (!isset($_SESSION["temp_login"]) || $_SESSION["temp_login"] !== true) {
    header("Location: ../pages/login.php");
    exit;
}

// Generate a new verification code
$newCode = sprintf("%06d", mt_rand(1, 999999));
$_SESSION["verification_code"] = $newCode;
$_SESSION["verification_code_time"] = time();

// Get user email and name from session
$email = $_SESSION["temp_email"];
$name = $_SESSION["temp_fname"];

// Try to use existing sendEmail function if available
$emailSent = false;
if (file_exists('sendEmail.php')) {
    include_once('sendEmail.php');
    
    if (function_exists('sendEmail')) {
        $subject = "PetAdopt Login Verification - New Code";
        $body = "
        <html>
        <head>
            <title>PetAdopt Login Verification</title>
        </head>
        <body>
            <p>Hello $name,</p>
            <p>Your new verification code for PetAdopt login is: <strong>$newCode</strong></p>
            <p>This code will expire in 10 minutes.</p>
            <p>If you did not request this code, please ignore this email.</p>
            <p>Regards,<br>PetAdopt Team</p>
        </body>
        </html>
        ";
        
        $emailSent = sendEmail($email, $subject, $body);
    }
}

// Fallback to basic mail function if sendEmail isn't available
if (!$emailSent) {
    $subject = "PetAdopt Login Verification Code - New Code";
    
    $message = "
    <html>
    <head>
        <title>PetAdopt Login Verification</title>
    </head>
    <body>
        <p>Hello $name,</p>
        <p>Your new verification code for PetAdopt login is: <strong>$newCode</strong></p>
        <p>This code will expire in 10 minutes.</p>
        <p>If you did not request this code, please ignore this email.</p>
        <p>Regards,<br>PetAdopt Team</p>
    </body>
    </html>
    ";
    
    // Set content-type header for sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@petadopt.com" . "\r\n";
    
    // Send email
    $emailSent = mail($email, $subject, $message, $headers);
}

// Redirect back to the 2FA page
if ($emailSent) {
    $_SESSION["code_resent"] = true;
} else {
    $_SESSION["code_resend_error"] = "Failed to send verification code. Please try again.";
}

header("Location: ../pages/2fa.php");
exit;
?>