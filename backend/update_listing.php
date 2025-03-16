
<html>
    <head>
        <?php 
            include 'inc/head.inc.php';
            include 'inc/navbar.inc.php'; 
        ?>
    </head>
    <body>
        <main class="container">
        <?php

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $errorMsg = "";
            $success = true;
            if (empty($_POST["name"])){
                $errorMsg .= "Name is required.<br>";
                $success = false;
            }
            else{
                $name = sanitize_input($_POST["name"]);
            }
    
            if (empty($_POST["type"])){
                $errorMsg .= "Type is required.<br>";
                $success = false;
            }
            else{
                $type = sanitize_input($_POST["type"]);
            }
    
            if(empty($_POST["breed"])){
                $errorMsg .= "Breed is required.<br>";
                $success = false;
            }
            else{
                $breed = sanitize_input($_POST["breed"]);
            }

            if(empty($_POST["age"])){
                $errorMsg .= "Age is required.<br>";
                $success = false;
            }
            else{
                $age = sanitize_input($_POST["age"]);
            }

            if(empty($_POST["gender"])){
                $errorMsg .= "Gender is required.<br>";
                $success = false;
            }
            else{
                $gender = sanitize_input($_POST["gender"]);
            }

            if(empty($_POST["cost"])){
                $errorMsg .= "Cost is required.<br>";
                $success = false;
            }
            else{
                $cost = sanitize_input($_POST["cost"]);
            }

            updateListing();
    
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
                
                echo "<a action=/home><button type=submit class='btn btn-success'>Log in</button></a>";
                unset($_SESSION['pw_hash']); 
            }
            else{
                echo "<h4><strong>The following input errors were detected:</strong></h4>";
                echo "<p>" . $errorMsg . "</p>";
                echo "<a action=/home><button class='btn btn-danger'>Return to Home</button></a>";
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
        function updateListing(){
            $petID = $_SESSION['edit_pet_id'];
            // Create database connection.
            global $name, $type, $breed, $age, $cost, $gender, $errorMsg, $success;
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
                    $stmt = $conn->prepare("UPDATE pets SET
                    pet_name=?, pet_type=?, breed=?, age=?, adopt_cost=?, gender=? WHERE pet_ID=?");
                    // Bind & execute the query statement:
                    $stmt->bind_param("sssiisi", $name, $type, $breed, $age, $cost, $gender, $petID);

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