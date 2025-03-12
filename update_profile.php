
<html>
    <head>
        <title>World of Pets</title>
        <?php
            include "inc/head.inc.php";
        ?>
    </head>
    <body>
        <?php
        include "inc/nav.inc.php";
        ?>
        <main class="container">
        <?php

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $email = $errorMsg = "";
            $success = true;
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
    
            if (empty($_POST["lname"])){
                $errorMsg .= "Last name is required.<br>";
                $success = false;
            }
    
            if(empty($_POST["pwd"])){
                $pwd_hashed = $_SESSION['pw_hash'];
                // $errorMsg .= "Password is required.<br>";
                // $success = false;
            }
            else{
                $pwd_hashed = hash('sha256', $_POST["pwd"]);
            }
            $fname = sanitize_input($_POST["fname"]);
            $lname = sanitize_input($_POST["lname"]);
            $address = sanitize_input($_POST["address"]);
            $dob2 = strtotime($_POST["dob"]);
            $dob = date('Y-m-d', $dob2);
            updateProfile();
    
            if ($success){
                echo "<h4>Update successful!</h4>";
                echo "<p>Email: " . $email . "</p>";
                echo "<p>First Name: " . $fname . "</p>";
                echo "<p>Last Name: " . $lname . "</p>";
                echo "<p>Address: " . $address . "</p>";
                echo "<p>Date of Birth: " . $dob . "</p>";
                echo "<p>pass hash: ";
                var_dump($pwd_hashed);
                echo"</p>";
                
                echo "<form action=index.php><button type=submit class='btn btn-success'>Log in</button></form>";
                unset($_SESSION['pw_hash']); 
            }
            else{
                echo "<h4><strong>The following input errors were detected:</strong></h4>";
                echo "<p>" . $errorMsg . "</p>";
                echo "<form action=index.php><button class='btn btn-danger'>Return to Home</button></form>";
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
        
            echo $output;
        }

        /*
        * Helper function to write the member data to the database.
        */
        function updateProfile(){
            $userID = $_SESSION['id'];
            // Create database connection.
            global $fname, $lname, $email, $address, $dob, $pwd_hashed, $errorMsg, $success;
            $config = parse_ini_file('/var/www/private/db-config.ini');
            if (!$config){
                $errorMsg = "Failed to read database config file.";
                debug_to_console($errorMsg);
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
                    debug_to_console($errorMsg);
                }
                else{
                    // Prepare the statement:
                    $stmt = $conn->prepare("UPDATE world_of_pets_members SET
                    fname=?, lname=?, email=?, password=?, address=?, dateofbirth=? WHERE member_id=?");
                    // Bind & execute the query statement:
                    $stmt->bind_param("ssssssi", $fname, $lname, $email, $pwd_hashed, $address, $dob, $userID);

                    if (!$stmt->execute()){
                        $errorMsg = "Execute failed: (" . $stmt->errno . ") " .
                        $stmt->error;
                        $success = false;
                        debug_to_console($errorMsg);
                    }
                    debug_to_console($stmt->errno);
                    $stmt->close();
                }
            $conn->close();
            debug_to_console("Done");
            }
        }
        ?>

        </main>

        <?php
        include "inc/footer.inc.php";
        ?>
    </body>
</html>