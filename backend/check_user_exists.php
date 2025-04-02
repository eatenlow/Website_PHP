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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <title>Home | PetAdopt</title>
    <?php include 'inc/navbar.inc.php'; ?>
</head>
<body>
    <main class="container">
    <?php
    
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $email = $errorMsg = "";
        $success = true;
        
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
            else{
                checkUser($email);
            }
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
        echo "<p>Get request not allowed</p><br>";
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

    function checkUser($email){
        global $email, $userID, $errorMsg, $success;
        // Create database connection.
        $config = parse_ini_file('/var/www/private/db-config.ini');
        if (!$config){
            $errorMsg = "Failed to read database config file.";
            $success = false;
        }
        else{
            $conn = new mysqli(
                $config['servername'],
                $config['username'],
                $config['password'],
                $config['dbname']
            );
            // Check connection
            if ($conn->connect_error){
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            }
            else{
                // Prepare the statement:
                $stmt = $conn->prepare("SELECT * FROM world_of_pets_members WHERE email=?");
                // Bind & execute the query statement:
                $stmt->bind_param("s", sanitize_input($email));
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $userID = $row["member_id"];
                    $admin = $row["admin"];
                    if($admin == 1){
                        $_SESSION['admin'] = $admin;
                    }
                }
                else{
                    $errorMsg = "Account not found...";
                    $success = false;
                }
                $stmt->close();
            }
            $conn->close();
        }
    }

    ?>

    </main>

    <?php
    include "inc/footer.inc.php";
    ?>
</body>
</html>