<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// session_start();
chdir("/var/www/html");

// Include your sendEmail.php file
require "sendEmail.php"; 

?>
<html lang="en">
<head>
    <title>verify login | PetAdopt</title>
    <?php 
    include 'inc/head.inc.php';
    include 'inc/navbar.inc.php';
     ?>
</head>
<body>
    <main class="container">
    <?php
    
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $email = $errorMsg = "";
        $success = true;
        
        // Verify reCAPTCHA, need the trim due to unknown additional trailing whitespace
        $recaptcha_secret = trim(file_get_contents("/var/www/private/recaptcha-secret.txt"));

        $recaptcha_response = $_POST['g-recaptcha-response'];
        
        // Make request to Google to verify captcha
        $verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$recaptcha_secret.'&response='.$recaptcha_response);
        $response_data = json_decode($verify_response);
        
        // Check if reCAPTCHA verification was successful
        if (!$response_data->success) {
            $errorMsg .= "Please complete the captcha verification.<br>";
            $success = false;
        }
        
        // Continue with existing validation
        if (empty($_POST["email"])){
            $errorMsg .= "Email is required.<br>";
            $success = false;
        }
        else{
            $email = sanitize_input($_POST["email"]);
        
            // Additional check to make sure e-mail address is well-formed.
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $errorMsg .= "Invalid email format.<br>";
                $success = false;
            }
        }
    
        if(empty($_POST["pwd"])){
            $errorMsg .= "Password is required.<br>";
            $success = false;
        }
        else{
            $pwd = hash('sha256', $_POST["pwd"]);
            // $pwd = ($_POST["pwd"]);
            authenticateUser($pwd);
        }
        
        if ($success){
            // Generate OTP
            $otp = rand(100000, 999999); 

            // Store OTP in session (instead of database)
            $_SESSION['2fa_otp'] = $otp;
            $_SESSION['2fa_otp_expiry'] = time() + 600; // OTP expires in 10 minutes (600 seconds)
            $_SESSION['2fa_user_id'] = $userID; // Store user ID in session

            // Send OTP via email using sendEmail function
            $subject = 'Your OTP for Login';
            $body = "Your OTP is: \n$otp\n. It expires in 10 minutes.";
            sendEmail($fname, $email, $body, $subject); // Use sendEmail function

            // Redirect to OTP verification page
            header("Location: /verify_otp");
            exit;
        }
        else{
            echo "<h1><strong>The following input errors were detected:</strong></h1>";
            echo "<p>" . $errorMsg . "</p>";
            echo "<a href=/login><button class='btn btn-danger'>Return to Login</button></a>";
        }
    }
    else{
        echo "<h4>Get request not allowed</h4>";
        echo "<p>go away</p>";
    }

    /*
    * Helper function that checks input for malicious or unwanted content.
    */
    function sanitize_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
    
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

    function authenticateUser($pwd){
        global $fname, $lname, $email, $userID, $pwd_hashed, $admin, $errorMsg, $success;
        // Create database connection.
        require_once 'db.php';
        // Prepare the statement:
        $stmt = $conn->prepare("SELECT * FROM world_of_pets_members WHERE email=?");
        // Bind & execute the query statement:
        $stmt->bind_param("s", sanitize_input($email));
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0){
            // Note that email field is unique, so should only have one row.
            $row = $result->fetch_assoc();
            $userID = $row["member_id"];
            $fname = $row["fname"];
            $lname = $row["lname"];
            $pwd_hashed = $row["password"];
            $admin = $row["admin"];
            if($admin == 1){
                $_SESSION['admin'] = $admin;
            }
            // Check if the password matches:
            if (strcmp($pwd, $pwd_hashed) != 0){
                $errorMsg = "Email not found or password doesn't match...";
                $success = false;
            }
        }
        else{
            $errorMsg = "Email not found or password doesn't match...";
            $success = false;
        }
        $stmt->close();
        $conn->close();
    }

    ?>

    </main>

    <?php
    include "inc/footer.inc.php";
    ?>
</body>
</html>