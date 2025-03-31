<?php
session_start();
?>
<html>
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
            
            // Verify reCAPTCHA
            $recaptcha_secret = "6LdU2QQrAAAAALd0FH42FiiRoVCTGEFDOotnSwTD";
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
                echo "<h4>Login successful!</h4>";
                echo "<p>Welcome Back " . $fname . "</p>";
                
                // to change a session variable, just overwrite it
                $_SESSION["login"] = true;
                $_SESSION["id"] = $userID;
                
                header("Location: /");
            }
            else{
                echo "<h4><strong>The following input errors were detected:</strong></h4>";
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
                        // Note that email field is unique, so should only have one row.
                        $row = $result->fetch_assoc();
                        $userID = $row["member_id"];
                        $fname = $row["fname"];
                        $lname = $row["lname"];
                        $pwd_hashed = $row["password"];
                        $admin = $row["admin"];
                        if($admin == 1){
                            debug_to_console($admin);
                            $_SESSION['admin'] = $admin;
                        }
                        // Check if the password matches:
                        if (strcmp($pwd, $pwd_hashed) != 0){
                            // Don't tell hackers which one was wrong, keep them guessing...
                            $errorMsg = "Email not found or password doesn't match...";
                            $success = false;
                        }
                    }
                    else{
                        $errorMsg = "Email not found or password doesn't match...";
                        $success = false;
                    }
                $stmt->close();
                }
            $conn->close();
            }
        }

        function cookieCreate(){
            $selector = base64_encode(random_bytes(9));
            $authenticator = random_bytes(33);
            $token = $selector.':'.base64_encode($authenticator);

            $COOKIE_NAME    = "MYCOOKIE";
            $COOKIE_VALUE   = "HAI";
            if(!isset($_COOKIE[$COOKIE_NAME])){
                setcookie(
                    $COOKIE_NAME, 
                    $token, 
                    time() + 864000,
                    "/",
                    "http://35.212.148.165",
                    false,
                    true
                );
                // header("Location: /");
                exit;
            }
            else{
                echo ($_COOKIE[$COOKIE_NAME]);
            }
            $config = parse_ini_file('/var/www/private/db-config.ini');
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
                $stmt = $conn->prepare("INSERT INTO auth_tokens (selector, token, userid, expires) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssis", $selector,
                                        hash('sha256', $authenticator),
                                        $userID,
                                        date('Y-m-d\TH:i:s', time() + 864000));
                $stmt->execute();
                $stmt->close();
            }
            $conn->close();
        }
        ?>

        </main>

        <?php
        include "inc/footer.inc.php";
        ?>
    </body>
</html>